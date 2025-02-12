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
        Schema::table('complaints', function (Blueprint $table) {
            // Drop existing column if it exists
            if (Schema::hasColumn('complaints', 'service_center_id')) {
                $table->dropForeign(['service_center_id']);
                $table->dropColumn('service_center_id');
            }

            // Add the new column with exact same type as service_centers.center_id
            $table->string('service_center_id', 255)->nullable();
        });

        // Add foreign key separately to ensure it's created after the column
        Schema::table('complaints', function (Blueprint $table) {
            $table->foreign('service_center_id')
                ->references('center_id')
                ->on('service_centers')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropForeign(['service_center_id']);
            $table->dropColumn('service_center_id');
        });
    }
};
