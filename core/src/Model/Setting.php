<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $key
 * @property string $type
 * @property string $value
 * @property string $title
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Setting extends Model
{
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    protected $fillable = ['key', 'type', 'value', 'title'];
}