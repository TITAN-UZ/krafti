<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property string $service
 * @property int $cost
 * @property int $discount
 * @property int $status
 * @property int $period
 * @property string $paid_at
 * @property string $paid_till
 * @property string $created_at
 * @property string $updated_at
 *
 * @property-read User $user
 * @property-read Course $course
 */
class Order extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'service', 'cost', 'discount', 'status', 'period', 'paid_at', 'paid_till',
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
}