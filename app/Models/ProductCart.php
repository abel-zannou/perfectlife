<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'product_id',
        'product_name',
        'product_code',
        'image',
        'product_weight',
        'quantity',
        'unite_price',
        'total_price'
    ];

    protected $with = ['user', 'product'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
