<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('g_number')->nullable();
            $table->dateTime('date')->nullable();
            $table->date('last_change_date')->nullable();
            $table->string('supplier_article')->nullable();
            $table->string('tech_size')->nullable();
            $table->bigInteger('barcode')->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->integer('discount_percent')->nullable();
            $table->string('warehouse_name')->nullable();
            $table->string('oblast')->nullable();
            $table->bigInteger('income_id')->nullable();
            $table->string('odid')->nullable();
            $table->bigInteger('nm_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->boolean('is_cancel')->default(false)->nullable();
            $table->dateTime('cancel_dt')->nullable();

            $table->foreignId('account_id')->constrained('accounts');

            $table->unique(['g_number', 'date', 'last_change_date', 'income_id', 'supplier_article', 'barcode', 'account_id'], 'main_unique_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
