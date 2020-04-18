<?php

namespace App\Model;

use Carbon\Carbon;
use Exception;
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
 * @property bool $favorite
 * @property int $account
 * @property string $promo
 * @property string $company
 * @property string $description
 * @property string $long_description
 * @property string $logged_at
 * @property string $reset_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
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
 * @property-read UserOauth[] $oauths
 * @property-read UserToken[] $tokens
 * @property-read UserChild[] $children
 * @property-read Diploma[] $diplomas
 * @property-read Message[] $messages
 * @property-read Homework[] $homeworks
 * @property-read UserProgress[] $progresses
 */
class User extends Model
{
    public $timestamps = true;
    protected $hidden = ['password', 'tmp_password'];
    //protected $guarded = ['id', 'tmp_password', 'created_at', 'updated_at', 'promo'];
    protected $fillable = ['email', 'password', 'fullname', 'dob', 'phone', 'instagram', 'active', 'photo_id',
        'company', 'description', 'long_description', 'favorite',
        'background_id', 'role_id', 'referrer_id', 'account', 'logged_at', 'reset_at',
    ];
    protected $casts = [
        'active' => 'boolean',
        'confirmed' => 'boolean',
        'favorite' => 'boolean',
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
     * @return HasMany
     */
    public function oauths()
    {
        return $this->hasMany('App\Model\UserOauth');
    }


    /**
     * @return HasMany
     */
    public function tokens()
    {
        return $this->hasMany('App\Model\UserToken');
    }


    /**
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany('App\Model\UserChild');
    }


    /**
     * @return HasMany
     */
    public function diplomas()
    {
        return $this->hasMany('App\Model\Diploma')->whereNotNull('file_id');
    }


    /**
     * @return HasMany
     */
    public function homeworks()
    {
        return $this->hasMany('App\Model\Homework');
    }


    /**
     * @return HasMany
     */
    public function progresses()
    {
        return $this->hasMany('App\Model\UserProgress');
    }


    /**
     * @return HasMany
     */
    public function messages()
    {
        return $this->hasMany('App\Model\Message');
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
     * @throws Exception
     */
    public function delete()
    {
        if ($this->photo) {
            $this->photo->delete();
        }
        if ($this->background) {
            $this->background->delete();
        }
        $this->orders()->where('status', '!=', 2)->delete();

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
        if ($action == 'bonus') {
            if ($this->transactions()->where(['course_id' => @$data['course_id'], 'action' => $action])->count()) {
                return;
            }
        }

        $transaction = new UserTransaction([
            'user_id' => $this->id,
            'amount' => $amount,
            'action' => $action,
        ]);

        $transaction->fill($data);
        $transaction->save();

        switch ($action) {
            case 'bonus':
                $this->sendMessage('Мы списали у вас ' . ($amount * -1) . ' крафтиков за покупку бонусного урока', $action, null, [
                    'transaction_id' => $transaction->id,
                ]);
                break;
            case 'homework':
                $this->sendMessage('Мы начислили вам ' . $amount . ' крафтиков за выполнение домашней работы', $action, null, [
                    'transaction_id' => $transaction->id,
                ]);
                break;
            case 'purchase':
                $this->sendMessage('Мы начислили вам ' . $amount . ' крафтиков за первую покупку вашего друга', $action, null, [
                    'transaction_id' => $transaction->id,
                ]);
                break;
            case 'palette':
                $this->sendMessage('Мы начислили вам ' . $amount . ' крафтиков за полностью закрытую палитру урока!', $action, null, [
                    'transaction_id' => $transaction->id,
                ]);
                break;
            case 'subscribe':
                $this->sendMessage('Мы начислили вам ' . $amount . ' крафтиков за подписку на наши новости', $action, null, [
                    'transaction_id' => $transaction->id,
                ]);
                break;
        }

        $this->updateAccount();
    }


    /**
     * @param Course $course
     *
     * @return UserProgress
     */
    public function getProgress($course)
    {
        $key = [
            'user_id' => $this->id,
            'course_id' => $course->id,
        ];

        if (!$progress = UserProgress::query()->where($key)->first()) {
            $progress = $this->makeProgress($course, 1);
        }

        return $progress;
    }


    /**
     * @param Course $course
     * @param int $section
     * @param int $rank
     *
     * @return UserProgress
     */
    public function makeProgress($course, $section, $rank = 0)
    {
        $key = [
            'user_id' => $this->id,
            'course_id' => $course->id,
        ];

        if (!$progress = UserProgress::query()->where($key)->first()) {
            $progress = new UserProgress($key);
            $progress->section = 1;
        }
        $progress->section = $section;
        $progress->rank = $rank;
        if ($progress->section === 1 && !$progress->rank) {
            /** @var Lesson $first */
            $first = $course->lessons()
                ->where(['active' => true, 'section' => $section])
                ->where('free', '!=', true)
                ->where('extra', '!=', true)
                ->orderBy('rank', 'asc')
                ->first();
            if ($first) {
                $progress->rank = $first->rank;
            }
        }
        $progress->save();

        // Generate diplomas
        if (!$section && !$rank) {
            $key = ['course_id' => $course->id, 'user_id' => $this->id];
            /** @var Diploma $diploma */
            if (!$diploma = Diploma::query()->where($key)->first()) {
                $diploma = new Diploma($key);
                $diploma->child_id = null;
                $diploma->save();
            }
            if ($this->children) {
                foreach ($this->children as $child) {
                    $key['child_id'] = $child->id;
                    if (!$diploma = Diploma::query()->where($key)->first()) {
                        $diploma = new Diploma($key);
                        $diploma->save();
                    }
                }
            }
        }

        return $progress;
    }


    /**
     * @param array|string $scope
     *
     * @return bool
     */
    public function hasScope($scope)
    {
        if ($this->role_id === 1) {
            return true;
        }

        if (!is_array($scope)) {
            $scope = [$scope];
        }
        $user_scope = $this->role->scope;

        foreach ($scope as $v) {
            if (!in_array($v, $user_scope)) {
                return false;
            }
        }

        return true;
    }


    /**
     * @param string $message
     * @param string $type
     * @param null $sender_id
     * @param array $data
     */
    public function sendMessage($message, $type, $sender_id = null, array $data = null)
    {
        $obj = new Message([
            'user_id' => $this->id,
            'type' => $type,
            'message' => $message,
            'read' => false,
        ]);
        if ($sender_id) {
            $obj->sender_id = $sender_id;
        }
        if ($data) {
            $obj->data = $data;
        }
        $obj->save();
    }
}