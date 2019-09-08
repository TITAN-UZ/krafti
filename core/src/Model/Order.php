<?php

namespace App\Model;

use App\Container;
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
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
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


    /**
     * @param Container $container
     *
     * @return \App\Service\Payment\Payment|bool
     */
    public function getPaymentHandler(Container $container)
    {
        $service = false;
        switch ($this->service) {
            case 'paypal':
                $service = new \App\Service\Payment\Paypal($container);
                break;
            case 'robokassa':
                $service = new \App\Service\Payment\Robokassa($container);
                break;
        }

        return $service;
    }


    /**
     * @param $status
     *
     * @return bool
     */
    public function changeStatus($status)
    {
        if (!$this->status != $status) {
            if ($status == 2) {
                $this->status = 2;
                $this->paid_at = date('Y-m-d H:i:s');
                $this->paid_till = date('Y-m-d H:i:s', strtotime("+$this->period month"));
                $this->save();

                if ($this->discount) {
                    $user = $this->user;
                    $user->referrer->makeTransaction(getenv('COINS_PROMO'), 'purchase', [
                        'referral_id' => $user->id,
                        'course_id' => $this->course->id,
                    ]);
                }
            }
        }

        return true;
    }
}