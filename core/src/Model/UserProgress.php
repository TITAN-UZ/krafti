<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property int $course_id
 * @property int $section
 * @property int $rank
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read User $user
 * @property-read Course $course
 */
class UserProgress extends Model
{
    use Traits\CompositeKey;

    protected $primaryKey = ['user_id', 'course_id'];
    protected $fillable = ['user_id', 'course_id', 'section', 'rank'];


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
}