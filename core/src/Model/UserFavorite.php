<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property int $course_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 * @property-read Course $course
 */
class UserFavorite extends Model
{
    use Traits\CompositeKey;

    protected $primaryKey = ['user_id', 'course_id'];
    protected $fillable = ['user_id', 'course_id'];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
