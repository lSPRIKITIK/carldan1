<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'material_id',
        'order_id',
        'movement_type',
        'quantity',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
