<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'delivery_address',
        'status',
        'delivery_date',
        'actual_delivery_date',
        'carrier',
        'tracking_number',
        'shipping_cost',
    ];

}
