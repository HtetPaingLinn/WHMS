<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');  // Changed from invoice_id
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->integer('quantity');
            $table->string('issue_type');
            $table->string('customer_phone');
            $table->text('remark')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('complain_date');
            $table->unsignedBigInteger('owner_id');
            $table->text('admin_response')->nullable();
            $table->string('warehouse_branch')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('owner_id')->references('partner_shops_id')->on('partner_shops');
        });
    }

    public function down()
    {
        Schema::dropIfExists('complaints');
    }
};
