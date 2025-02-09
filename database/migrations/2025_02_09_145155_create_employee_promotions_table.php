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
        Schema::dropIfExists('employee_promotions');
        Schema::create('employee_promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('previous_designation_id');
            $table->unsignedBigInteger('new_designation_id');
            $table->unsignedBigInteger('previous_department_id');
            $table->unsignedBigInteger('new_department_id');
            $table->date('promotion_date');
            $table->longText('remarks');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')
                ->onDelete('cascade');
            $table->foreign('previous_designation_id')->references('id')->on('designations')
                ->onDelete('cascade');
            $table->foreign('new_designation_id')->references('id')->on('designations')
                ->onDelete('cascade');
            $table->foreign('previous_department_id')->references('id')->on('departments')
                ->onDelete('cascade');
            $table->foreign('new_department_id')->references('id')->on('departments')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_promotions');
    }
};
