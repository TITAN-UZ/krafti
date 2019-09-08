<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $dob
 * @property bool $gender
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read User $user
 */
class UserChild extends Model
{
    protected $table = 'user_children';
    protected $fillable = ['user_id', 'name', 'dob', 'gender'];
    protected $casts = [
        'gender' => 'bool',
    ];


    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
}