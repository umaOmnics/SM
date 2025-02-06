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
        if (Schema::hasTable('vendors')) {
            Schema::dropIfExists('vendors');
        }

        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->longText('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';

        });

        if (Schema::hasTable('items')) {
            Schema::dropIfExists('items');
        }

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->nullable();
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->string('category')->nullable();
            $table->string('quantity')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('width')->nullable();
            $table->string('depth')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('sub_category')->nullable();
            $table->string('type')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';

            $table->foreign('vendor_id', 'fk_vendor_id')
                ->references('id')->on('vendors')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
        Schema::dropIfExists('items');
    }
};
