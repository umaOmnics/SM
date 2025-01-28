<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the table if it exists
        Schema::dropIfExists('users');

        // Create the table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable(false);
            $table->string('last_name')->nullable(false);
            $table->string('email')->unique()->nullable(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(false);
            $table->string('username')->unique()->nullable(false);
            $table->boolean('sys_admin')->default(false);
            $table->boolean('is_active')->default(true);
            $table->mediumText('two_factor_secret_key')->nullable();
            $table->mediumText('recovery_codes')->nullable();
            $table->text('address_1')->nullable();
            $table->text('address_2')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->timestamps();

            $table->foreignId('salutations_id')->nullable(false)->constrained();
            $table->foreignId('titles_id')->nullable()->constrained();
        });
        // Insert the records perfectly and then move on
        Artisan::call('db:seed', [
            '--class' => 'UserSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
