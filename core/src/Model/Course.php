<?php

namespace App\Model;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * @property int $id
 * @property string $title
 * @property string $tagline
 * @property string $description
 * @property array $price
 * @property string $category
 * @property array $properties
 * @property string $age
 * @property int $template_id
 * @property int $cover_id
 * @property int $video_id
 * @property int $diploma_id
 * @property int $views_count
 * @property int $reviews_count
 * @property int $likes_sum
 * @property int $lessons_count
 * @property int $videos_count
 * @property bool $active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Template $template
 * @property-read File $cover
 * @property-read Video $video
 * @property-read Lesson $bonus
 * @property-read File $diploma
 * @property-read Lesson[] $lessons
 * @property-read Order[] $orders
 * @property-read UserProgress[] $progresses
 */
class Course extends Model
{
    protected $fillable = [
        'title',
        'tagline',
        'description',
        'price',
        'category',
        'properties',
        'age',
        'cover_id',
        'video_id',
        'diploma_id',
        'active',
    ];
    protected $casts = [
        'properties' => 'array',
        'price' => 'array',
        'active' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * @return BelongsTo
     */
    public function cover()
    {
        return $this->belongsTo(File::class);
    }

    /**
     * @return BelongsTo
     */
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * @return belongsTo
     */
    public function diploma()
    {
        return $this->belongsTo(File::class);
    }

    /**
     * @return hasOne
     */
    public function bonus()
    {
        return $this->hasOne(Lesson::class)->where(['section' => 0]);
    }

    /**
     * @return HasMany
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return HasMany
     */
    public function progresses()
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * @return bool|null
     * @throws Exception
     */
    public function delete()
    {
        if ($this->cover) {
            $this->cover->delete();
        }

        return parent::delete();
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function wasBought($user)
    {
        /** @var User $user */
        if ($user) {
            if ($user->hasScope('courses')) {
                return true;
            }
            /** @var Order $order */
            $order = $this->orders()
                ->where(['user_id' => $user->id, 'status' => 2])
                ->orderBy('paid_till', 'desc')
                ->first();

            return $order && date('Y-m-d', $order->paid_till->timestamp) >= date('Y-m-d');
        }

        return false;
    }

    /**
     * @param User $user
     * @param Promo $promo
     *
     * @return array|false
     */
    public function getDiscount($user, $promo = null)
    {
        $user_discount = $promo_discount = [];

        // Определяем скидку для указанного юзера
        if ($user) {
            if ($this->orders()->where(['user_id' => $user->id, 'status' => 2])->where('paid_till', '<',
                date('Y-m-d H:i:s'))->count()) {
                $user_discount = [
                    'discount' => getenv('COURSE_PROLONG_DISCOUNT'),
                    'type' => 'order',
                ];
            } elseif ($user->referrer_id && !$this->orders()->where(['user_id' => $user->id, 'status' => 2])->count()) {
                $user_discount = [
                    'discount' => getenv('COURSE_DISCOUNT'),
                    'type' => 'referrer',
                ];
            }
        }

        // Определяем скидку для указанного промо-кода
        if ($promo) {
            $check = $promo->check($this->id);
            if ($check === true) {
                $promo_discount = [
                    'discount' => $promo->discount,
                    'type' => 'promo',
                ];
                if ($promo->percent) {
                    $promo_discount['discount'] .= '%';
                }
            }
        }

        $test_price = $user_price = $promo_price = $this->price[array_keys($this->price)[0]];
        // Определяем итоговую стоимость со скидкой юзера
        if ($user_discount) {
            if (substr($user_discount['discount'], -1) === '%') {
                $user_price = $test_price * (rtrim($user_discount['discount'], '%') / 100);
            } else {
                $user_price = $test_price - $user_discount['discount'];
            }
            $user_price = $test_price - $user_price;
        }
        // Определяем итоговую стоимость со промо-кода
        if ($promo_discount) {
            if (substr($promo_discount['discount'], -1) === '%') {
                $promo_price = $test_price * (rtrim($promo_discount['discount'], '%') / 100);
            } else {
                $promo_price = $test_price - $promo_discount['discount'];
            }
            $promo_price = $test_price - $promo_price;
        }

        $discount = false;
        // Сравниваем итоговые цены и выбираем наибольшую скидку
        if ($user_price < $test_price) {
            $discount = $user_discount;
        }
        if ($promo_price < $user_price) {
            $discount = $promo_discount;
        }

        return $discount;
    }

    /**
     * @param array|null $discount
     * @return array
     */
    public function getPrice($discount = null)
    {
        $price = $this->price;
        if ($discount) {
            array_walk($price, function (&$v) use ($discount) {
                if (substr($discount['discount'], -1) === '%') {
                    $v -= $v * (rtrim($discount['discount'], '%') / 100);
                } else {
                    $v -= $v - $discount['discount'];
                }
                if ($v < 0) {
                    $v = 0;
                }
            });
        }

        return $price;
    }

    /**
     * @param bool $save
     *
     * @return int
     */
    public function updateViewsCount($save = true)
    {
        $sum = $this->lessons()->where(['active' => true])->sum('views_count');
        if ($this->views_count != $sum) {
            $this->views_count = $sum;
            if ($save) {
                $this->save();
            }
        }

        return $sum;
    }

    /**
     * @param bool $save
     *
     * @return int
     */
    public function updateLikesCount($save = true)
    {
        $sum = $this->lessons()->where(['active' => true])->sum('likes_count') -
            $this->lessons()->where(['active' => true])->sum('dislikes_count');
        if ($this->likes_sum != $sum) {
            $this->likes_sum = $sum;
            if ($save) {
                $this->save();
            }
        }

        return $sum;
    }

    /**
     * @param bool $save
     */
    public function updateLessonsCount($save = true)
    {
        $lessons_count = $videos_count = 0;
        /** @var Lesson $lesson */
        foreach ($this->lessons()->where(['active' => true])->get() as $lesson) {
            $lessons_count++;

            if ($lesson->video_id) {
                $videos_count++;
            }
            if ($lesson->bonus_id) {
                $videos_count++;
            }
        }
        if ($this->lessons_count != $lessons_count || $this->videos_count != $videos_count) {
            $this->lessons_count = $lessons_count;
            $this->videos_count = $videos_count;
            if ($save) {
                $this->save();
            }
        }
    }

}