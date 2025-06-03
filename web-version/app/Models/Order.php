<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_name',
        'product_id',
        'quantity',
        'order_type',
        'address',
        'distance_km',
        'total_amount',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}