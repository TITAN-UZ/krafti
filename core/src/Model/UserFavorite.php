<?php

namespace App\Model;

use App\Model\Traits\CompositeKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property int $course_id
 *
 * @property-read User $user
 * @property-read Course $course
 */
class UserFavorite extends Model
{
    use CompositeKey;

    public $timestamps = false;
    protected $fillable = ['user_id', 'course_id'];
    protected $primaryKey = ['user_id', 'course_id'];


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