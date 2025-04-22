<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function ProductDetail(Request $request)
    {
        $id = $request->id;

        $productDetails = ProductDetail::with('product')->where('product_id', $id)->get();

        return $productDetails;
    }
}
