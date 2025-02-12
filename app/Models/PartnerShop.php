<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PartnerShop extends Authenticatable
{
    use Notifiable;

    protected $table = 'partner_shops';
    protected $primaryKey = 'partner_shops_id';

    protected $fillable = [
        'partner_shops_name',
        'partner_shops_email',
        'partner_shops_password',
        'partner_shops_address',
        'partner_shops_township',
        'partner_shops_region',
        'contact_primary',
        'contact_secondary',
        'points'
    ];


    protected $hidden = [
        'partner_shops_password',
    ];

    public function getAuthPassword()
    {
        return $this->partner_shops_password;
    }

}
