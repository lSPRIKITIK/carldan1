<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'type', 
        'price'
    ];

    
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
                    ->withPivot('required_quantity')
                    ->withTimestamps();
    }

    public function stock_movements()
    {
        return $this->hasMany(\App\Models\StockMovement::class);
    }
}