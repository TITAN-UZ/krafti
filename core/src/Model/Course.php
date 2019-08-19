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
 * @property int $views_count
 * @property int $reviews_count
 * @property int $likes_sum
 * @property int $lessons_count
 * @property bool $active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property-read File $cover
 * @property-read Video $video
 * @property-read Lesson $bonus
 * @property-read Lesson[] $lessons
 * @property-read Order[] $orders
 */
class Course extends Model
{
    protected $fillable = ['title', 'tagline', 'description', 'price', 'category', 'properties', 'age',
        'cover_id', 'video_id', 'active'];
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
        /** @var Order $order */
        $order = $this->orders()->where(['user_id' => $user_id, 'status' => 2])->first();

        return $order && $order->paid_till > date('Y-m-d H:i:s');
    }

}