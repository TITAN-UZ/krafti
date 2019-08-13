<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $channel
 * @property int $level
 * @property string $message
 * @property string $time
 * @property string $object
 * @property int $object_id
 * @property int $user_id
 * @property string $action
 * @property array $data
 */
class Log extends Model
{
    public $timestamps = false;
    protected $fillable = ['channel', 'level', 'message', 'time', 'object', 'object_id', 'user_id', 'action', 'data'];
    protected $casts = [
        'data' => 'array',
    ];
}