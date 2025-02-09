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
        if (Schema::hasTable('employee_leaves')) {
            Schema::dropIfExists('employee_leaves');
        }

        Schema::create('employee_leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('leave_type_id')->nullable();
            $table->string('status')->nullable();
            $table->longText('reason')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('applied_date')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';

            $table->foreign('employee_id', 'fk_employee_leave_id')
                ->references('id')->on('employees')
                ->onDelete('cascade');

            $table->foreign('leave_type_id', 'fk_employee_leave_type_id')
                ->references('id')->on('employee_leaves')
                ->onDelete('set null');
            
            $table->foreign('approved_by', 'fk_leave_approved_by_id')
                ->references('id')->on('employees')
                ->onDelete('set null');

            $table->foreign('rejected_by', 'fk_leave_rejected_by_id')
                ->references('id')->on('employees')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_leaves');
    }
};
