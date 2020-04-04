<?php

use \App\Service\Migration;
use Illuminate\Database\Schema\Blueprint;

class OrderDiscountType extends Migration
{
    public function up()
    {
        $this->schema->table('orders', function (Blueprint $table) {
            $table->string('discount', 20)->nullable()->default(null)->change();
            $table->string('discount_type', 20)->nullable()->after('discount');
        });

        \App\Model\Order::query()->where('discount', '0')->update(['discount' => null]);
    }
}
