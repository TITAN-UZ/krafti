<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $sender_id
 * @property string $message
 * @property string $type  // reply, child dob, payment, transaction, diplomas
 * @property array $data
 * @property bool $read
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 * @property-read User $sender
 */
class Message extends Model
{

    protected $fillable = [
        'user_id', 'sender_id', 'message', 'type', 'data', 'read',
    ];
    protected $casts = [
        'read' => 'boolean',
        'data' => 'array',
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
    public function sender()
    {
        return $this->belongsTo('App\Model\User');
    }

}