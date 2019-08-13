<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property int $id
 * @property string $title
 * @property string $tagline
 * @property string $description
 * @property int $price
 * @property string $category
 * @property array $properties
 * @property string $age
 * @property int $cover_id
 * @property int $video_id
 * @property int $bonus_id
 * @property int $views_count
 * @property int $reviews_count
 * @property int $likes_count
 * @property int $lessons_count
 * @property bool $active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property-read File $cover
 * @property-read Video $video
 * @property-read Video $bonus
 * @property-read Lesson[] $lessons
 */
class Course extends Model
{
    protected $fillable = ['title', 'tagline', 'description', 'price', 'category', 'properties', 'age',
        'cover_id', 'video_id', 'bonus_id', 'active'];
    protected $casts = [
        'properties' => 'array',
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
     * @return BelongsTo
     */
    public function bonus()
    {
        return $this->belongsTo('App\Model\Video');
    }


    /**
     * @return HasMany
     */
    public function lessons()
    {
        return $this->hasMany('App\Model\Lesson');
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

}