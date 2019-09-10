<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $code
 * @property int $discount
 * @property bool $percent
 * @property string $title
 * @property \Carbon\Carbon $date_start
 * @property \Carbon\Carbon $date_end
 * @property int $limit
 * @property int $used
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 */
class Promo extends Model
{
    protected $fillable = [
        'code', 'discount', 'percent', 'title', 'date_start', 'date_end', 'limit', 'used',
    ];
    protected $dates = [
        'date_start', 'date_end',
    ];
    protected $casts = [
        'percent' => 'boolean',
    ];


    /**
     * @return bool|string
     */
    public function check()
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

        return true;
    }
}