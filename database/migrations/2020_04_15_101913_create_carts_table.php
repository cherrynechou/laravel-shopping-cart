<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('cart.database.prefix', '');
        $table_name = $prefix . 'carts';
        if (!Schema::hasTable($table_name)){
            Schema::create($table_name, function (Blueprint $table) {
                $table->string('key');
                $table->string('__raw_id');
                $table->string('guard')->nullable();

                $table->integer('user_id')->nullable();
                $table->integer('community_id')->comment('商品id');
                $table->integer('goods_id')->comment('商品id');
                $table->integer('qty')->comment('数量');
                $table->string('type')->nullable();

                $table->string('__model')->nullable()->comment('模型');
            
                $table->text('attributes')->nullable();
                $table->primary(['key', '__raw_id']);

                $table->timestamps();
            });
            DB::statement("ALTER TABLE {$table_name} comment '商城购物车表'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $prefix = config('cart.database.prefix', '');
        $table_name = $prefix . 'carts';
        Schema::dropIfExists(table_name);
    }
}
