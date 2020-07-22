<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Throwable;

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
class User extends \Vesp\Models\User
{
    protected $hidden = ['password', 'tmp_password'];
    protected $fillable = [
        'email',
        'password',
        'fullname',
        'dob',
        'phone',
        'instagram',
        'active',
        'photo_id',
        'company',
        'description',
        'long_description',
        'favorite',
        'background_id',
        'role_id',
        'referrer_id',
        'account',
        'logged_at',
        'reset_at',
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
        if ($key === 'tmp_password') {
            $value = password_hash($value, PASSWORD_DEFAULT);
        }
        parent::setAttribute($key, $value);
    }

    public function resetPassword(string $password): bool
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

    public function getProfile(): array
    {
        $oauth2 = [];
        foreach ($this->oauths as $obj) {
            $oauth2[$obj->provider] = $obj->displayName;
        }

        return [
            'id' => $this->id,
            'email' => $this->email,
            'instagram' => $this->instagram,
            'company' => $this->company,
            'description' => $this->description,
            'fullname' => $this->fullname,
            'account' => $this->account,
            'dob' => $this->dob,
            'phone' => $this->phone,
            'scope' => $this->role->scope,
            'photo' => $this->photo_id
                ? [
                    'id' => $this->photo->id,
                    'updated_at' => $this->photo->updated_at,
                ]
                : null,
            'background' => $this->background_id
                ? [
                    'id' => $this->background->id,
                    'updated_at' => $this->background->updated_at,
                ]
                : null,
            'promo' => $this->promo,
            'favorites' => $this->favorites()->pluck('course_id')->toArray(),
            'oauth2' => $oauth2,
            'children' => $this->children()->get(['id', 'name', 'dob', 'gender'])->toArray(),
            'unread' => $this->messages()->where('read', false)->count(),
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(UserRole::class);
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(__CLASS__);
    }

    public function photo(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function background(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(UserFavorite::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(UserLike::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(__CLASS__, 'referrer_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(UserTransaction::class);
    }


    public function oauths(): HasMany
    {
        return $this->hasMany(UserOauth::class);
    }


    public function tokens(): HasMany
    {
        return $this->hasMany(UserToken::class);
    }


    public function children(): HasMany
    {
        return $this->hasMany(UserChild::class);
    }


    public function diplomas(): HasMany
    {
        return $this->hasMany(Diploma::class)->whereNotNull('file_id');
    }


    public function homeworks(): HasMany
    {
        return $this->hasMany(Homework::class);
    }


    public function progresses(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }


    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function save(array $options = []): bool
    {
        if (!$this->promo) {
            while (true) {
                $promo = $this->randomPassword();
                if (!self::query()->where('promo', $promo)->count()) {
                    $this->promo = $promo;
                    break;
                }
            }
        }

        return parent::save($options);
    }

    /**
     * @throws Throwable
     */
    public function delete(): ?bool
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

    protected function randomPassword(int $length = 8): string
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = [];
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $n = random_int(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass);
    }

    public function updateAccount(bool $save = true): bool
    {
        $sum = $this->transactions()->sum('amount');
        if ($this->account !== $sum) {
            $this->account = $sum;
            if ($save) {
                $this->save();
            }
        }

        return $sum;
    }

    public function makeTransaction(int $amount, string $action, array $data = []): void
    {
        if ($action === 'register' && $this->transactions()->where('action', $action)->count()) {
            return;
        }
        if ($action === 'bonus') {
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
                $this->sendMessage(
                    'Мы списали у вас ' . ($amount * -1) . ' крафтиков за покупку бонусного урока',
                    $action,
                    null,
                    ['transaction_id' => $transaction->id]
                );
                break;
            case 'homework':
                $this->sendMessage(
                    'Мы начислили вам ' . $amount . ' крафтиков за выполнение домашней работы',
                    $action,
                    null,
                    ['transaction_id' => $transaction->id]
                );
                break;
            case 'purchase':
                $this->sendMessage(
                    'Мы начислили вам ' . $amount . ' крафтиков за первую покупку вашего друга',
                    $action,
                    null,
                    ['transaction_id' => $transaction->id]
                );
                break;
            case 'palette':
                $this->sendMessage(
                    'Мы начислили вам ' . $amount . ' крафтиков за полностью закрытую палитру урока!',
                    $action,
                    null,
                    ['transaction_id' => $transaction->id]
                );
                break;
            case 'register':
                $this->sendMessage(
                    'Приветствуем в онлайн-академии творчества KRAFTi! С нами вы всей семьёй сможете самостоятельно создавать домашние шедевры.
Благодарим вас за приобретение детского курса по рисованию и желаем красивых работ и вдохновения!

Спасибо, что поделились своей электронной почтой! Теперь мы сможем сообщать вам о наших новых курсах, акциях и новостях. Вместе с этим письмом мы дарим вам крафтики, которые вы сможете использовать для приобретения бонусов.
Кстати, крафтики можно получить не только за регистрацию, но и за заполнение палитры прогресса. Если к нам придут ваши друзья, мы отблагодарим их скидкой на курс, а вас — нашими крафтиками.
Если вы тоже захотите поделиться чем-то с нами — пишите на нашу почту, в Instagram или WhatsApp.
В этом письме мы хотим ответить на основные вопросы — это поможет сделать творчество легче и приятнее.

Сначала мы расскажем, как сориентироваться на нашем сайте.
В первую очередь советуем обратить внимание на личный кабинет. Чем больше данных вы заполните, тем лучше мы сможем познакомиться друг с другом.
В блоке «Наша команда» вы можете узнать больше о нас — тех, кто старается сделать вас ближе к мастерству живописи.
В блоке «Курсы» мы подробно расписали процесс обучения. Всё просто: после изучения каждого модуля нужно выполнить домашнее задание, после чего открывается следующий модуль.
А в конце курса, после выполнения всех домашек, вас ждёт полезный бонус.',
                    $action,
                    null,
                    ['transaction_id' => $transaction->id]
                );
                break;
            case 'subscribe':
                $this->sendMessage(
                    'Мы начислили вам ' . $amount . ' крафтиков за подписку на наши новости',
                    $action,
                    null,
                    ['transaction_id' => $transaction->id]
                );
                break;
        }

        $this->updateAccount();
    }

    public function getProgress(Course $course): UserProgress
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

    public function makeProgress(Course $course, int $section, int $rank = 0): UserProgress
    {
        $key = [
            'user_id' => $this->id,
            'course_id' => $course->id,
        ];

        if (!$progress = UserProgress::query()->where($key)->first()) {
            $progress = new UserProgress($key);
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
     * @return bool
     */
    public function hasScope($scope): bool
    {
        if ($this->role_id === 1) {
            return true;
        }

        return parent::hasScope($scope);
    }

    public function sendMessage(string $message, string $type, int $sender_id = null, array $data = null): void
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
