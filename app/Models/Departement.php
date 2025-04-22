<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $guarded = [];
    // protected $with = ['products'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
