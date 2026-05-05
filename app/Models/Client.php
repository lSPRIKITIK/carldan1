<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 
        'middle_name', 
        'last_name', 
        'contact_number', 
        'street', 
        'city'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}