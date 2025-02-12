<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'product_id',
        'product_name',
        'quantity',
        'issue_type',
        'customer_phone',
        'remark',
        'status',
        'complain_date',
        'admin_response',
        'service_center_id',
        'warehouse_branch',
        'owner_id'
    ];

    protected $casts = [
        'complain_date' => 'datetime',
        'service_center_id' => 'string',
        'warehouse_branch' => 'string'
    ];

    // You can add these constants for status values
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_REJECTED = 'rejected';

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
            self::STATUS_RESOLVED,
            self::STATUS_REJECTED
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'invoice_no');
    }

    public function serviceCenter()
    {
        return $this->belongsTo(ServiceCenter::class, 'service_center_id', 'center_id');
    }
}
