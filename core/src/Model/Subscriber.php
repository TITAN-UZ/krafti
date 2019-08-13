<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $email
 * @property int $user_id
 */
class Subscriber extends Model
{
    public $timestamps = false;
    protected $fillable = ['email', 'user_id'];

}