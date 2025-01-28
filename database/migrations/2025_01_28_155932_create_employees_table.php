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
        Schema::dropIfExists('employees');
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->boolean('type')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('phone')->nullable();
            $table->string('profile_photo_url')->nullable();
            $table->string('mobile_number')->nullable();
            $table->longText('address_1')->nullable();
            $table->longText('address_2')->nullable();
            $table->string('city')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('postal_code')->nullable();
            $table->timestamps();

            $table->softDeletes();

            $table->foreign('state_id')->references('id')->on('countries_states')
                ->onDelete('set null');
            $table->foreign('country_id')->references('id')->on('countries')
                ->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')
                ->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('set null');
            $table->foreign('designation_id')->references('id')->on('designations')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
