<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $password
 * @property string $tmp_password
 * @property string $fullname
 * @property string $email
 * @property string $dob
 * @property string $phone
 * @property string $instagram
 * @property bool $active
 * @property bool $confirmed
 * @property int $photo_id
 * @property int $background_id
 * @property int $role_id
 * @property array $children
 * @property int $coins
 * @property string $logged_at
 * @property string $reset_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property-read UserRole $role
 * @property-read File $photo
 * @property-read File $background
 * @property-read UserFavorite[] $favorites
 */
class User extends Model
{
    public $timestamps = true;
    protected $fillable = ['email', 'password', 'fullname', 'dob', 'phone', 'instagram', 'active', 'photo_id',
        'background_id', 'role_id', 'children', 'coins', 'logged_at', 'reset_at',
    ];
    protected $casts = [
        'active' => 'boolean',
        'confirmed' => 'boolean',
        'children' => 'array',
    ];
    protected $hidden = [
        'password', 'tmp_password',
    ];


    /**
     * @param string $key
     * @param mixed $value
     *
     * @return mixed|void
     */
    public function setAttribute($key, $value)
    {

        if (in_array($key, ['password', 'tmp_password'])) {
            $value = password_hash($value, PASSWORD_DEFAULT);
        }

        parent::setAttribute($key, $value);
    }


    /**
     * @param $password
     *
     * @return bool
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }


    /**
     * @param $password
     *
     * @return bool
     */
    public function resetPassword($password)
    {
        if (password_verify($password, $this->tmp_password)) {
            parent::setAttribute('password', $this->tmp_password);
            parent::setAttribute('tmp_password', null);
            parent::setAttribute('reset_at', null);
            parent::save();

            return true;
        }

        return false;
    }


    /**
     * @return BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Model\UserRole');
    }


    /**
     * @return BelongsTo
     */
    public function photo()
    {
        return $this->belongsTo('App\Model\File');
    }


    /**
     * @return BelongsTo
     */
    public function background()
    {
        return $this->belongsTo('App\Model\File');
    }


    /**
     * @return HasMany
     */
    public function favorites() {
        return $this->hasMany('App\Model\UserFavorite');
    }


    /**
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        if ($this->photo) {
            $this->photo->delete();
        }
        if ($this->background) {
            $this->background->delete();
        }

        return parent::delete();
    }
}