<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            SystemUsersSeeder::class,
            PartnerShopsSeeder::class,
            ServiceCentersSeeder::class,
            SalesInvoiceSeeder::class,
            DeliverySeeder::class,
            DailyDeliveryListSeeder::class,
        ]);
    }
}
