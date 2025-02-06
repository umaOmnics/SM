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
        if (Schema::hasTable('ticket_management')) {
            Schema::dropIfExists('ticket_management');
        }

        Schema::create('ticket_management', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('quantity')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('replied_by')->nullable();
            $table->timestamp('deleted_at');
            $table->timestamps();

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';

            $table->foreign('item_id', 'fk_ticket_item_id')
                ->references('id')->on('items')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('created_by', 'fk_ticket_created_users_id')
                ->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('replied_by', 'fk_ticket_replied_users_id')
                ->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_management');
    }
};
