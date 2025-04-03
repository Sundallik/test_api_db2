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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index()->nullable();
            $table->date('last_change_date')->nullable();
            $table->string('supplier_article')->nullable();
            $table->string('tech_size')->nullable();
            $table->bigInteger('barcode')->index()->nullable();
            $table->integer('quantity')->default(0)->nullable();
            $table->boolean('is_supply')->default(true)->nullable();
            $table->boolean('is_realization')->default(false)->nullable();
            $table->integer('quantity_full')->nullable();
            $table->string('warehouse_name')->nullable();
            $table->integer('in_way_to_client')->default(0)->nullable();
            $table->integer('in_way_from_client')->default(0)->nullable();
            $table->bigInteger('nm_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->bigInteger('sc_code')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->default(0)->nullable();

            $table->foreignId('account_id')->constrained('accounts');

            $table->unique(['barcode', 'nm_id', 'account_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
