<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'type', 'price'];

    public function materials()
    {
        return $this->belongsToMany(Material::class)->withPivot('required_quantity')->withTimestamps();
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price')->withTimestamps();
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }
}
