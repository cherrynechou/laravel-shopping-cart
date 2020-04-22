<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            Schema::create(table_name, function (Blueprint $table) {
                $table->string('key');
                $table->string('__raw_id');
                $table->string('guard')->nullable();
                $table->integer('user_id')->nullable();

                $table->integer('id')->comment('商品id');
                $table->string('name')->comment('商品名称');
                $table->integer('qty')->comment('数量');

                $table->string('__model')->nullable()->comment('模型');

                $table->string('type')->nullable();
                $table->text('attributes')->nullable();
                $table->primary(['key', '__raw_id']);
                
                $table->timestamps();
            });
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
