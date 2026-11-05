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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 160);
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('whatsapp', 40)->nullable();
            $table->string('email', 254)->nullable();
            $table->string('website')->nullable();
            $table->string('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 2)->nullable();
            $table->string('instagram_url')->nullable();
            $table->unsignedBigInteger('instagram_followers_at_registration')->nullable();
            $table->timestamp('instagram_followers_recorded_at')->nullable();
            $table->string('logo_path')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->index(['is_published', 'name']);
            $table->index(['is_published', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
