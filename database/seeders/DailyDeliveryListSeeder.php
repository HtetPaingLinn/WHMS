<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DailyDeliveryListSeeder extends Seeder
{
    public function run()
    {
        DB::table('daily_delivery_list')->insert([
            'delivery_date' => '2025-02-13',
            'delivery_id' => 1,
            'partner_shop_id' => 1,
            'sales_invoice_id' => 1,
            'delivery_status' => 'Pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
