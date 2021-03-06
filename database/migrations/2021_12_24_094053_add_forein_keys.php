<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeinKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_addresses', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()
                ->after('id')->constrained('users')
                ->restrictOnUpdate()->cascadeOnDelete();
        });

        Schema::table('orders_addresses', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()
                ->after('id')->constrained('orders')
                ->restrictOnUpdate()->cascadeOnDelete();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->after('id')
                ->nullable()->constrained('categories')
                ->restrictOnUpdate()->nullOnDelete();
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('product_id')->after('id')->constrained('products')
                ->restrictOnUpdate()->cascadeOnDelete();
            $table->foreignId('user_id')->after('id')->constrained('users')
                ->restrictOnUpdate()->cascadeOnDelete();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('buyer_id')->after('id')->nullable()->constrained('users')
                ->restrictOnUpdate()->nullOnDelete();
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->foreignId('order_id')->after('id')
                ->nullable()->constrained('orders')
                ->restrictOnUpdate()->cascadeOnDelete();
            $table->foreignId('product_id')->after('id')
                ->nullable()->constrained('products')
                ->restrictOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}