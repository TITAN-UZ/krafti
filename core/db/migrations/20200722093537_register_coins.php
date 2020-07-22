<?php

use App\Model\User;
use App\Model\UserTransaction;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Services\Migration;

class RegisterCoins extends Migration
{
    public function up(): void
    {
        $users = User::query()->whereDoesntHave('transactions', static function (Builder $c) {
            $c->where('action', 'register');
        });
        $coins = getenv('COINS_REGISTER');

        /** @var User $user */
        foreach ($users->get() as $user) {
            /** @var UserTransaction $transaction */
            $transaction = $user->transactions()->create([
                'amount' => $coins,
                'action' => 'register',
            ]);

            $user->sendMessage(
                "Мы начислили вам {$coins} крафтиков за регистрацию на нашем сайте!\n\nВообще, мы должны были начислить их сразу, но забыли. Так что исправляемся сегодня!",
                'register',
                null,
                ['transaction_id' => $transaction->id]
            );

            $user->account += $coins;
            $user->save();
        }
    }

    public function down(): void
    {
        $users = User::query()->whereHas('transactions', static function (Builder $c) {
            $c->where('action', 'register');
        });
        $coins = getenv('COINS_REGISTER');

        /** @var User $user */
        foreach ($users->get() as $user) {
            $user->transactions()->where('action', 'register')->delete();
            $user->messages()->where('type', 'register')->delete();

            $user->account -= $coins;
            if ($user->account < 0) {
                $user->account = 0;
            }
            $user->save();
        }
    }
}
