<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_one',
        'image_two',
        'short_description',
        'product_weight',
        'product_code',
    ];

    protected $with = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
