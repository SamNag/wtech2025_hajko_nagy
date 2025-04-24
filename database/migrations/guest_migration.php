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
        Schema::table('orders', function (Blueprint $table) {
            // Make user_id nullable for guest orders
            $table->uuid('user_id')->nullable()->change();

            // Add new fields
            $table->double('price')->after('status')->default(0);
            $table->string('delivery_type')->after('price')->default('ups');
            $table->string('guest_name')->nullable()->after('delivery_type');
            $table->string('guest_email')->nullable()->after('guest_name');
            $table->string('guest_phone')->nullable()->after('guest_email');
            $table->timestamp('updated_at')->nullable()->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert user_id to non-nullable (note: this could fail if there are guest orders)
            $table->uuid('user_id')->nullable(false)->change();

            // Remove added columns
            $table->dropColumn([
                'price',
                'delivery_type',
                'guest_name',
                'guest_email',
                'guest_phone',
                'updated_at'
            ]);
        });
    }
};
