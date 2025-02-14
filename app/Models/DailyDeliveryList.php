<?php

// DailyDeliveryList.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DailyDeliveryList extends Model
{
    use HasFactory;

    protected $table = 'daily_delivery_list'; // Explicitly set the table nam
    protected $fillable = ['delivery_date', 'delivery_id',  'sales_invoice_id', 'delivery_status',];


    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id'); // Correct foreign key
    }

    public function driver()
    {
        return $this->belongsTo(Delivery::class, 'id');
    }

    public function partnerShop()
    {
        return $this->belongsTo(PartnerShop::class, 'partner_shops_id');
    }
}
