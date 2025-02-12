<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('partner_shops', function (Blueprint $table) {
            $table->id('partner_shops_id');
            $table->string('partner_shops_name', 255);
            $table->string('partner_shops_email', 30)->nullable();
            $table->string('partner_shops_password', 255)->nullable();
            $table->string('partner_shops_address', 100)->nullable();
            $table->string('partner_shops_township', 30)->nullable();
            $table->string('partner_shops_region', 20)->nullable();
            $table->string('contact_primary', 30)->nullable();
            $table->string('contact_secondary', 30)->nullable();
            $table->decimal('points', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('partner_shops');
    }
};
