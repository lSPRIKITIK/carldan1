<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['first_name', 'middle_name', 'last_name', 'contact_number', 'address'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}