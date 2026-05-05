<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{   
    use HasFactory;
    protected $fillable = ['client_id', 'employee_id', 'status', 'order_date', 'delivery_date'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price')->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    // Accessor methods for payment calculations
    public function getTotalAmountAttribute()
    {
        return $this->products->sum(function($product) {
            return $product->pivot->quantity * $product->pivot->price;
        });
    }

    public function getAmountPaidAttribute()
    {
        return $this->payments->sum('amount');
    }

    public function getDownpaymentAttribute()
    {
        return $this->total_amount * 0.50;
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->total_amount - $this->amount_paid;
    }
    
}