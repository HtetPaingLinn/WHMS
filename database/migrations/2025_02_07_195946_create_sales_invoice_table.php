<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->id();
            $table->date('sale_date');
            $table->string('invoice_no', 255);
            $table->foreignId('partner_shops_id')->constrained('partner_shops', 'partner_shops_id')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('cash_back_mmk', 15, 2)->default(0.00);
            $table->integer('quantity');
            $table->decimal('total_mmk', 15, 2);
            $table->boolean('delivered')->default(0);
            $table->enum('payment', array('Pending','Paid','Cancel'))->default('Pending');
            $table->boolean('completed')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_invoices');
    }
};
