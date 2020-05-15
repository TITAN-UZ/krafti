<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property bool $course_palette
 * @property bool $lesson_bonus
 * @property bool $course_steps
 * @property bool $course_homeworks
 * @property bool $course_bonus
 *
 * @property-read Course[] $courses
 */
class Template extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'title',
        'course_palette',
        'lesson_bonus',
        'course_steps',
        'course_homeworks',
        'course_bonus',
    ];
    protected $casts = [
        'course_palette' => 'boolean',
        'lesson_bonus' => 'boolean',
        'course_steps' => 'boolean',
        'course_homeworks' => 'boolean',
        'course_bonus' => 'boolean',
    ];

    /**
     * @return HasMany
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
