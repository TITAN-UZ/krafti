<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $code
 * @property int $discount
 * @property bool $percent
 * @property string $title
 * @property array $courses
 * @property Carbon $date_start
 * @property Carbon $date_end
 * @property int $limit
 * @property int $used
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Order[] $orders
 */
class Promo extends Model
{
    protected $fillable = [
        'code',
        'discount',
        'percent',
        'title',
        'courses',
        'date_start',
        'date_end',
        'limit',
        'used',
    ];
    protected $dates = [
        'date_start',
        'date_end',
    ];
    protected $casts = [
        'percent' => 'boolean',
        'courses' => 'array',
    ];

    /**
     * @return HasMany
     */
    public function orders() {
        return $this->hasMany('\App\Model\Order');
    }

    /**
     * @param int $course_id
     * @return bool|string
     */
    public function check($course_id = 0)
    {
        $date = date('Y-m-d H:i:s');

        if ($this->date_start && $this->date_start > $date) {
            return 'Срок действия этого промокода еще не наступил';
        }
        if ($this->date_end && $this->date_end < $date) {
            return 'Срок действия этого промокода уже закончился';
        }
        if ($this->limit && $this->used >= $this->limit) {
            return 'Этот промокод больше не действителен';
        }

        if ($course_id && !empty($this->courses)) {
            if (!in_array($course_id, $this->courses)) {
                return 'Ваш промокод не действует на этот курс';
            }
        }

        return true;
    }
}