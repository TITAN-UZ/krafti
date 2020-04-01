<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $referral_id
 * @property int $course_id
 * @property int $amount
 * @property int $account
 * @property string $action
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 * @property-read User $referral
 * @property-read Course $course
 * @property-read Lesson $lesson
 */
class UserTransaction extends Model
{
    protected $fillable = ['lesson_id', 'user_id', 'referral_id', 'course_id', 'amount', 'account', 'action'];


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
    public function referral()
    {
        return $this->belongsTo('App\Model\User');
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
    public function course()
    {
        return $this->belongsTo('App\Model\Course');
    }


    /**
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        $this->account = $this->user->account;

        return parent::save($options);
    }
}