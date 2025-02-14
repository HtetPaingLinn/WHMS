<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'brand',
        'category',
        'product_segment',
        'product_serial_number',
        'unit_price_mmk',
        'product_image_url',
    ];

    public function salesInvoices()
    {
        return $this->hasMany(SalesInvoice::class, 'product_id');
    }

    public function stockRecords()
    {
        return $this->hasMany(StockRecord::class, 'product_id');
    }

    public function getImageUrlAttribute()
    {
        return $this->product_image_url;
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($product) {
            $systemUserId = SystemUser::first()?->id ?? 1; // Get first available user, fallback to 1

            StockRecord::create([
                'record_date' => now()->toDateString(),
                'product_id' => $product->id,
                'warehouse_branch' => 'Dawbon',
                'opening_balance' => 0,
                'received' => 0,
                'dispatched' => 0,
                'closing_balance' => 0,
                'system_users_id' => $systemUserId,
            ]);
        });
    }
}
