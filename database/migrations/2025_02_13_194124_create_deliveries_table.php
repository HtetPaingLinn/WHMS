<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('driver_name');
            $table->string('email')->unique();
            $table->text('password');
            $table->text('phone_number');
            $table->string('license_number');
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_plate_number')->nullable();
            $table->enum('status', ['Available', 'On Delivery', 'Offline'])->default('Available');
            $table->integer('assigned_orders_count')->default(0);
            $table->dateTime('last_delivery_time')->nullable();
            $table->decimal('rating', 2, 1)->nullable();
            $table->dateTime('hire_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('profile_picture')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
