<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'supplier_name', 
        'supplier_contact', 
        'supplier_street', 
        'supplier_city'
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
