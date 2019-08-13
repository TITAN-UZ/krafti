<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $course_id
 * @property int $video_id
 * @property int $bonus_id
 * @property int $file_id
 * @property int $author_id
 * @property array $products
 * @property int $views
 * @property int $likes
 * @property int $rank
 * @property int $section
 * @property bool $active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property-read Course $course
 * @property-read Video $video
 * @property-read Video $bonus
 * @property-read File $file
 * @property-read User $author
 */
class Lesson extends Model
{
    protected $fillable = ['title', 'description', 'products', 'active', 'course_id', 'video_id', 'bonus_id', 'file_id',
        'author_id', 'rank', 'section'];
    protected $casts = [
        'products' => 'array',
        'active' => 'boolean',
    ];


    /**
     * @return BelongsTo
     */
    public function course()
    {
        return $this->belongsTo('App\Model\Course');
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
     * @return BelongsTo
     */
    public function file()
    {
        return $this->belongsTo('App\Model\File');
    }


    /**
     * @return BelongsTo
     */
    public function author()
    {
        return $this->belongsTo('App\Model\User');
    }

}