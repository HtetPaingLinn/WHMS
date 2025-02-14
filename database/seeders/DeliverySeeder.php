<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Delivery;
use Illuminate\Support\Facades\Hash;

class DeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Delivery::create([
            'driver_name' => 'Driver 11',
            'email' => 'driver11@example.com',
            'password' => Hash::make('password1234'), // Hash the password
            'phone_number' => '123-555-6789',
            'license_number' => 'XYZ-e123',
            'vehicle_type' => 'Truck',
            'vehicle_plate_number' => 'TRK-1234',
            'status' => 'Available', // Valid status: 'Available', 'On Delivery', or 'Offline'
            'assigned_orders_count' => 0,
            'last_delivery_time' => null,
            'rating' => 4.8,
            'hire_date' => now(),
            'profile_picture' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Delivery::create([
            'driver_name' => 'Driver 12',
            'email' => 'driver12@example.com',
            'password' => Hash::make('password1234'), // Hash the password
            'phone_number' => '123-555-6790',
            'license_number' => 'XYZ-f123',
            'vehicle_type' => 'Bicycle',
            'vehicle_plate_number' => 'BIC-5678',
            'status' => 'On Delivery', // Ensure valid status
            'assigned_orders_count' => 3,
            'last_delivery_time' => now(),
            'rating' => 4.9,
            'hire_date' => now(),
            'profile_picture' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Delivery::create([
            'driver_name' => 'Driver 13',
            'email' => 'driver13@example.com',
            'password' => Hash::make('password1234'), // Hash the password
            'phone_number' => '123-555-6791',
            'license_number' => 'XYZ-g123',
            'vehicle_type' => 'Electric Scooter',
            'vehicle_plate_number' => 'ESC-1234',
            'status' => 'Offline', // Valid status: 'Available', 'On Delivery', or 'Offline'
            'assigned_orders_count' => 1,
            'last_delivery_time' => now(),
            'rating' => 4.7,
            'hire_date' => now(),
            'profile_picture' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
