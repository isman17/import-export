<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order2 extends Model
{
    use HasFactory;

    protected $table = 'orders2';

    protected $fillable = [
        'code',
        'status',
        'cancel_reason',
        'cancel_status',
        'airwaybill',
        'delivery_service',
        'delivery_pickup',
        'delivery_before',
        'delivery_at',
        'created_at',
        'pay_at',
        'total_quantity',
        'total_weight',
    ];

    public $timestamps = false;

    public static function findByCode($code)
    {
        return self::where('code', $code)->first();
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            Order2Item::class,
            'order_code',
            'code'
        );
    }

    public function getQuantitySumAttribute()
    {
        return $this->items()->sum('quantity');
    }
}
