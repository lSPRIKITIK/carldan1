<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{   
    use HasFactory;
    protected $fillable = ['name', 'type', 'unit_cost'];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('required_quantity')->withTimestamps();
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
