<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{   
    use HasFactory;
    
    protected $fillable = ['supplier_id', 'material_id', 'stock_in', 'stock_out', 'quantity'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
