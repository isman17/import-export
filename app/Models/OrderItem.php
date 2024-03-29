<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'parent_sku',
        'name',
        'sku',
        'variant_name',
        'regular_price',
        'discount_price',
        'quantity',
        'total_price',
        'total_discount',
        'seller_discount',
        'shopee_discount',
        'weight',
    ];

    public static function findBySku($sku)
    {
        return self::where('sku', $sku)->first();
    }
}
