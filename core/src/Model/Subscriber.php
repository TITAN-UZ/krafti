<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $email
 * @property int $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Subscriber extends Model
{
    protected $primaryKey = 'email';
    protected $fillable = ['email', 'user_id'];
}