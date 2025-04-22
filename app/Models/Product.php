<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['departement'];

    protected static function booted()
    {
        static::creating(function ($product) {
            //creation de product code 
            $currentYearMonth = now()->format('Ym');
            // Prendre les 3 premières lettres du nom, sans espace, en majuscules
            $prefix = strtoupper(substr(preg_replace('/\s+/', '', $product->product_name), 0, 3));
            $countForThisMonth = Product::where('product_code', 'like', $prefix . $currentYearMonth . '-%')->count() + 1;
    
            $product->product_code = $prefix . $currentYearMonth . '-' . str_pad($countForThisMonth, 6, '0', STR_PAD_LEFT);

            // Assigner les users si connecté
            $user = auth()->user();
            $product->created_by = $user ? $user->id : null;
            $product->updated_by = $user ? $user->id : null;
        });

        // static::created(function ($product) {
        //     $currentYearMonth = now()->format('Ym');
        //     $countForThisMonth = Product::where('product_code', 'like', 'PRD-' . $currentYearMonth . '-%')->count() + 1;
    
        //     $product->product_code = 'PRD-' . $currentYearMonth . '-' . str_pad($countForThisMonth, 6, '0', STR_PAD_LEFT);
        //     $product->saveQuietly();
        // });

        static::updating(function ($product){
            $user = auth()->user();
            $product->updated_by = $user ? $user->id : null;

        });
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id', 'id');
    }

    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class);
    }

    public function product_reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id', 'id');
    }

    public function product_cart()
    {
        return $this->hasOne(ProductCart::class, 'product_id');
    }

    public function product_stock()
    {
        return $this->belongsTo(ProductStock::class);
    }
}
