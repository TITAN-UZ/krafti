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
 * @property int $referrer_id
 * @property array $children
 * @property int $account
 * @property string $promo
 * @property string $company
 * @property string $description
 * @property string $logged_at
 * @property string $reset_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property-read UserRole $role
 * @property-read User $referrer
 * @property-read File $photo
 * @property-read File $background
 * @property-read UserFavorite[] $favorites
 * @property-read UserLike[] $likes
 * @property-read Order[] $orders
 * @property-read User[] $referrals
 * @property-read UserTransaction[] $transactions
 */
class User extends Model
{
    public $timestamps = true;
    protected $fillable = ['email', 'password', 'fullname', 'dob', 'phone', 'instagram', 'active', 'photo_id',
        'company', 'description',
        'background_id', 'role_id', 'referrer_id', 'children', 'account', 'logged_at', 'reset_at',
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
    public function referrer()
    {
        return $this->belongsTo('App\Model\User');
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
    public function favorites()
    {
        return $this->hasMany('App\Model\UserFavorite');
    }


    /**
     * @return HasMany
     */
    public function likes()
    {
        return $this->hasMany('App\Model\UserLike');
    }


    /**
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany('App\Model\Order');
    }


    /**
     * @return HasMany
     */
    public function referrals()
    {
        return $this->hasMany('App\Model\User', 'referrer_id');
    }


    /**
     * @return HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Model\UserTransaction');
    }


    /**
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        if (!$this->promo) {
            while (true) {
                $promo = $this->randomPassword();
                if (!User::query()->where(['promo' => $promo])->count()) {
                    $this->promo = $promo;
                    break;
                }
            }
        }

        return parent::save($options);
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


    /**
     * @param int $length
     *
     * @return string
     */
    protected function randomPassword($length = 8)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = [];
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass);
    }


    /**
     * @param bool $save
     *
     * @return bool
     */
    public function updateAccount($save = true)
    {
        $sum = $this->transactions()->sum('amount');
        if ($this->account != $sum) {
            $this->account = $sum;
            if ($save) {
                $this->save();
            }
        }

        return $sum;
    }


    /**
     * @param int $amount
     * @param string $action
     * @param array $data
     */
    public function makeTransaction($amount, $action, array $data = [])
    {
        $transaction = new UserTransaction([
            'user_id' => $this->id,
            'amount' => $amount,
            'action' => $action,
        ]);
        $transaction->fill($data);
        $transaction->save();

        $this->updateAccount();
    }
}