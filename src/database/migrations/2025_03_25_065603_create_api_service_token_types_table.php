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
        Schema::create('api_service_token_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_service_id')->constrained('api_services')->onDelete('cascade');
            $table->foreignId('token_type_id')->constrained('token_types')->onDelete('cascade');
            $table->unique(['api_service_id', 'token_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_service_token_types');
    }
};
