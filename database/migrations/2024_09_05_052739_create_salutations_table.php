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
        Schema::dropIfExists('salutations');

        // Create the table
        Schema::create('salutations', function (Blueprint $table) {
            $table->id();
            $table->string('name',255)->unique()->nullable(false);
            $table->timestamps();
        });
        // Insert the records perfectly and then move on
       Artisan::call('db:seed', [
            '--class' => 'SalutationsSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salutations');
    }
};
