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
        Schema::create('daily_delivery_list', function (Blueprint $table) {
            $table->id();
            $table->date('delivery_date');
            $table->unsignedBigInteger('delivery_id');
            $table->unsignedBigInteger('partner_shop_id');
            $table->unsignedBigInteger('sales_invoice_id')->nullable();
            $table->enum('delivery_status', ['Pending', 'In Progress', 'Completed'])->default('Pending');
            $table->timestamps(0); // Adds created_at and updated_at columns

            // Foreign keys
            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('cascade');
            $table->foreign('partner_shop_id')->references('partner_shops_id')->on('partner_shops')->onDelete('cascade');
            $table->foreign('sales_invoice_id')->references('id')->on('sales_invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_delivery_list');
    }
};
