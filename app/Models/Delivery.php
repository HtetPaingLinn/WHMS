<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Delivery extends Authenticatable
{
    use HasFactory, Notifiable;

    // Table associated with the model
    protected $table = 'deliveries';

    // Mass assignable attributes
    protected $fillable = [
        'id',
        'driver_name',
        'email',
        'password',
        'phone_number',
        'license_number',
        'vehicle_type',
        'vehicle_plate_number',
        'status',
        'assigned_orders_count',
        'last_delivery_time',
        'rating',
        'hire_date',
        'profile_picture',
        'is_active',
    ];

    // Hidden attributes for security
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Attribute casting
    protected $casts = [
        'is_active' => 'boolean',
        'last_delivery_time' => 'datetime',
        'hire_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Automatically hash passwords when setting them
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    // Example relationship: a delivery driver might have many assigned orders
    public function orders()
    {
        return $this->hasMany(Order::class, 'id');
    }
}
