<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;


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
 * @property int $views_count
 * @property int $likes_count
 * @property int $dislikes_count
 * @property int $rank
 * @property int $section
 * @property bool $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read Course $course
 * @property-read Video $video
 * @property-read Video $bonus
 * @property-read File $file
 * @property-read User $author
 * @property-read UserLike[] $likes
 * @property-read Homework[] $homeworks
 */
class Lesson extends Model
{
    protected $fillable = ['title', 'description', 'products', 'course_id', 'video_id', 'bonus_id', 'file_id', 'author_id',
        'rank', 'section', 'active'];
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


    /**
     * @return hasMany
     */
    public function likes()
    {
        return $this->hasMany('App\Model\UserLike');
    }


    /**
     * @return hasMany
     */
    public function homeworks()
    {
        return $this->hasMany('App\Model\Homework');
    }


    /**
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        if (!$this->id || $this->isDirty('section')) {
            $this->rank = $this->course->lessons()
                ->where(['section' => $this->section])
                ->count();
        }

        $likes = ($this->isDirty('likes_count') || $this->isDirty('dislikes_count')) && $this->course;
        $lessons = $this->isDirty('active') && $this->course;

        $save = parent::save($options);

        if ($likes) {
            $this->course->updateLikesCount();
        }
        if ($lessons) {
            $this->course->updateLessonsCount();
        }

        return $save;
    }


    public function delete()
    {
        $delete = parent::delete();

        if ($this->course) {
            $this->course->lessons_count = $this->course->lessons()->where(['active' => true])->count();
            $this->course->likes_sum = $this->course->lessons()->where(['active' => true])->sum('likes_count') -
                $this->course->lessons()->where(['active' => true])->sum('dislikes_count');
            $this->course->save();
        }

        return $delete;
    }


    /**
     * @return int
     */
    public function updateViewsCount()
    {
        $count = 0;
        if ($this->video) {
            $count += $this->video->views_count;
        }
        if ($this->bonus) {
            $count += $this->bonus->views_count;
        }
        if ($this->views_count != $count) {
            $this->views_count = $count;
            $this->save();

            $this->course->updateViewsCount();
        }

        return $count;
    }

}