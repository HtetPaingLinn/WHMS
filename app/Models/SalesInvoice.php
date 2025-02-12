<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_date',
        'invoice_no',
        'partner_shops_id',
        'product_id',
        'cash_back_mmk',
        'quantity',
        'total_mmk',
        'delivered',
        'payment',
        'completed',
        'remarks',
    ];

    public static function create(array $array)
    {

    }

    public function partnerShop()
    {
        return $this->belongsTo(PartnerShop::class, 'partner_shops_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
