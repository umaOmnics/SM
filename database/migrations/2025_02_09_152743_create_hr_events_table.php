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
        Schema::dropIfExists('hr_events');
        Schema::create('hr_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name')->nullable();
            $table->date('event_date')->nullable();
            $table->unsignedBigInteger('organized_by')->nullable();
            $table->string('location')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_events');
    }
};
