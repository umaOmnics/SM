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
        Schema::dropIfExists('students');
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->date('dob')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('phone')->nullable();
            $table->string('profile_photo_url')->nullable();
            $table->string('mobile_number')->nullable();
            $table->longText('address_1')->nullable();
            $table->longText('address_2')->nullable();
            $table->string('city')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('admission_number')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('roll_number')->nullable();
            $table->string('parent_first_name')->nullable();
            $table->string('parent_last_name')->nullable();
            $table->string('relation')->nullable();
            $table->string('occupation')->nullable();
            $table->string('parent_email')->nullable();
            $table->string('parent_phone')->nullable();
            $table->string('parent_mobile_number')->nullable();
            $table->string('parent_address_1')->nullable();
            $table->string('parent_address_2')->nullable();
            $table->string('parent_city')->nullable();
            $table->unsignedBigInteger('parent_state_id')->nullable();
            $table->unsignedBigInteger('parent_country_id')->nullable();
            $table->string('parent_postal_code')->nullable();
            $table->timestamps();

            $table->softDeletes();

            $table->foreign('state_id')->references('id')->on('countries_states')
                ->onDelete('set null');
            $table->foreign('country_id')->references('id')->on('countries')
                ->onDelete('set null');
            $table->foreign('parent_state_id')->references('id')->on('countries_states')
                ->onDelete('set null');
            $table->foreign('parent_country_id')->references('id')->on('countries')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
