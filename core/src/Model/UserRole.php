<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property array $scope
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read User[] $users
 */
class UserRole extends Model
{
    public $timestamps = true;
    protected $fillable = ['title', 'scope'];
    protected $casts = [
        'scope' => 'array',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\Model\User');
    }
}