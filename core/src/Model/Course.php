<?php

namespace App\Model;

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
 * @property int $cover_id
 * @property int $video_id
 * @property int $diploma_id
 * @property int $views_count
 * @property int $reviews_count
 * @property int $likes_sum
 * @property int $lessons_count
 * @property int $videos_count
 * @property bool $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
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
    protected $fillable = ['title', 'tagline', 'description', 'price', 'category', 'properties', 'age',
        'cover_id', 'video_id', 'diploma_id', 'active'];
    protected $casts = [
        'properties' => 'array',
        'price' => 'array',
        'active' => 'boolean',
    ];


    /**
     * @return BelongsTo
     */
    public function cover()
    {
        return $this->belongsTo('App\Model\File');
    }


    /**
     * @return BelongsTo
     */
    public function video()
    {
        return $this->belongsTo('App\Model\Video');
    }


    /**
     * @return belongsTo
     */
    public function diploma()
    {
        return $this->belongsTo('App\Model\File');
    }


    /**
     * @return hasOne
     */
    public function bonus()
    {
        return $this->hasOne('App\Model\Lesson')->where(['section' => 0]);
    }


    /**
     * @return HasMany
     */
    public function lessons()
    {
        return $this->hasMany('App\Model\Lesson');
    }


    /**
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany('App\Model\Order');
    }


    /**
     * @return HasMany
     */
    public function progresses()
    {
        return $this->hasMany('App\Model\UserProgress');
    }


    /**
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        if ($this->cover) {
            $this->cover->delete();
        }

        return parent::delete();
    }


    /**
     * @param $user_id
     *
     * @return bool
     */
    public function wasBought($user_id)
    {
        /** @var User $user */
        if ($user = User::query()->find($user_id)) {
            if ($user->role_id < 3) {
                return true;
            }
            /** @var Order $order */
            $order = $this->orders()->where(['user_id' => $user_id, 'status' => 2])->first();

            return $order && $order->paid_till > date('Y-m-d H:i:s');
        }

        return false;
    }


    /**
     * @param $user_id
     *
     * @return bool
     */
    public function getDiscount($user_id)
    {
        $discount = 0;
        /** @var User $user */
        if ($user = User::query()->find($user_id)) {
            if ($user->referrer_id && !$this->orders()->where(['user_id' => $user_id, 'status' => 2])->count()) {
                $discount = getenv('COURSE_DISCOUNT');
            }
        }

        return $discount;
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
     *
     * @return int
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

        /*$count = $this->lessons()->where(['active' => true])->count();
        if ($this->lessons_count != $count) {
            $this->lessons_count = $count;
            if ($save) {
                $this->save();
            }
        }*/

        return $count;
    }

}