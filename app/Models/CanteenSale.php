<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CanteenSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'canteen_item_id', 'quantity', 'unit_price', 'total_amount', 'buyer_type', 'buyer_id', 'sold_at', 'notes'
    ];

    protected $casts = [
        'sold_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(CanteenItem::class, 'canteen_item_id');
    }
}
