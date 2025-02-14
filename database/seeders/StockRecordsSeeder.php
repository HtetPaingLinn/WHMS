<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockRecordsSeeder extends Seeder
{
    public function run()
    {
        DB::table('stock_records')->truncate();

        DB::table('stock_records')->insert([
            [
                'record_date' => '2024-12-30',
                'product_id' => 1,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 5,
                'received' => 0,
                'dispatched' => 3,
                'closing_balance' => 2,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'record_date' => '2024-12-30',
                'product_id' => 2,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 6,
                'received' => 0,
                'dispatched' => 5,
                'closing_balance' => 1,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'record_date' => '2024-12-30',
                'product_id' => 3,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 9,
                'received' => 2,
                'dispatched' => 7,
                'closing_balance' => 4,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'record_date' => '2024-12-30',
                'product_id' => 4,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 10,
                'received' => 0,
                'dispatched' => 9,
                'closing_balance' => 1,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'record_date' => '2024-12-30',
                'product_id' => 5,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 15,
                'received' => 0,
                'dispatched' => 9,
                'closing_balance' => 6,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'record_date' => '2024-12-30',
                'product_id' => 6,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 15,
                'received' => 0,
                'dispatched' => 6,
                'closing_balance' => 9,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'record_date' => '2024-12-30',
                'product_id' => 7,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 10,
                'received' => 0,
                'dispatched' => 6,
                'closing_balance' => 4,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'record_date' => '2024-12-30',
                'product_id' => 8,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 13,
                'received' => 9,
                'dispatched' => 6,
                'closing_balance' => 16,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'record_date' => '2024-12-30',
                'product_id' => 9,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 3,
                'received' => 9,
                'dispatched' => 6,
                'closing_balance' => 6,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'record_date' => '2024-12-30',
                'product_id' => 10,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 31,
                'received' => 9,
                'dispatched' => 6,
                'closing_balance' => 34,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'record_date' => '2024-12-30',
                'product_id' => 10,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 31,
                'received' => 9,
                'dispatched' => 6,
                'closing_balance' => 34,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'record_date' => '2024-12-30',
                'product_id' => 11,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 30,
                'received' => 9,
                'dispatched' => 16,
                'closing_balance' => 23,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'record_date' => '2024-12-30',
                'product_id' => 12,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 3,
                'received' => 9,
                'dispatched' => 10,
                'closing_balance' => 2,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'record_date' => '2024-12-30',
                'product_id' => 13,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 5,
                'received' => 9,
                'dispatched' => 6,
                'closing_balance' => 8,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'record_date' => '2024-12-30',
                'product_id' => 14,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 1,
                'received' => 9,
                'dispatched' => 6,
                'closing_balance' => 4,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'record_date' => '2024-12-30',
                'product_id' => 15,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 3,
                'received' => 10,
                'dispatched' => 6,
                'closing_balance' => 7,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'record_date' => '2024-12-30',
                'product_id' => 16,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 3,
                'received' => 9,
                'dispatched' => 2,
                'closing_balance' => 10,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'record_date' => '2024-12-30',
                'product_id' => 17,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 10,
                'received' => 9,
                'dispatched' => 6,
                'closing_balance' =>13,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'record_date' => '2024-12-30',
                'product_id' => 18,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 31,
                'received' => 9,
                'dispatched' => 6,
                'closing_balance' => 34,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'record_date' => '2024-12-30',
                'product_id' => 19,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 5,
                'received' => 9,
                'dispatched' => 6,
                'closing_balance' => 8,
                'system_users_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
