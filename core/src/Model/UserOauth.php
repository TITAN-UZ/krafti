<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property string $provider
 * @property int $identifier
 * @property string $email
 * @property string $profileURL
 * @property string $photoURL
 * @property string $displayName
 * @property string $firstName
 * @property string $lastName
 * @property string $gender
 * @property int $phone
 * @property int $age
 * @property int $birthDay
 * @property int $birthMonth
 * @property int $birthYear
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 */
class UserOauth extends Model
{
    use Traits\CompositeKey;

    protected $primaryKey = ['user_id', 'provider'];
    protected $fillable = [
        'user_id',
        'provider',
        'identifier',
        'email',
        'profileURL',
        'photoURL',
        'displayName',
        'firstName',
        'lastName',
        'gender',
        'phone',
        'age',
        'birthDay',
        'birthMonth',
        'birthYear',
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
