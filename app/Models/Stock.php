<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'supplier_id',
        'stock_in',
        'quantity',
        'unit_cost' 
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }


    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function movements()
    {
        return $this->hasMany(\App\Models\StockMovement::class);
    }
}