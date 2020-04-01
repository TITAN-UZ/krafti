<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property int $lesson_id
 * @property int $file_id
 * @property int $section
 * @property bool $approved
 * @property string $comment
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 * @property-read Course $course
 * @property-read Lesson $lesson
 * @property-read File $file
 */
class Homework extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'lesson_id', 'file_id', 'section', 'approved', 'comment',
    ];
    protected $casts = [
        'approved' => 'boolean',
    ];


    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }


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
    public function lesson()
    {
        return $this->belongsTo('App\Model\Lesson');
    }


    /**
     * @return BelongsTo
     */
    public function file()
    {
        return $this->belongsTo('App\Model\File');
    }
}