<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'customer_name',
        'phone',
        'net_amount',
        'product_id',
        'quantity'
    ];

    protected $dates = ['deleted_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
