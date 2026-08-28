<?php

namespace App\Http\Controllers\pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Discount;
use App\Models\Product;
use Milon\Barcode\DNS1D;
use App\Models\Gallery;
use App\Models\Summer;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Support\Str;
use App\Models\ProductAplus;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use App\Models\Varient;
use App\Models\VarientValue;
use App\Models\Combo;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $data = Summer::whereNotNull('position')
                ->whereHas('products')->with(['products' => function ($query) {
                    $query
                        ->leftJoin('discounts', 'discounts.id', '=', 'products.discount')
                        ->leftJoin('brands', 'brands.id', '=', 'products.brands')
                        ->select(
                            'products.id as url',
                            'products.id',
                            'products.name',
                            'products.image',
                            'products.price',
                            'products.ac_price',
                            'products.stock',
                            'products.in_stock',
                            'products.short_description',
                            'products.summer_id',
                            'products.slug',
                            'products.slug',
                            'products.type',
                            'products.type_value',
                            'products.category',
                            'products.product_type',
                            'products.brand_name',
                            'discounts.name as discount',
                            'brands.name as brand'
                        );
                }])
                ->select('id', 'name', 'position', 'image as banner')
                ->whereNotNull('position')
                ->where('status', 1)
                ->orderBy('position', 'Asc')
                ->get();

            $data->each(function ($summer) {
                $summer->url = '1' . '-' . Str::slug($summer->name) . '-' . $summer->id;

                $summer->products->each(function ($product) {
                    $product->url = Str::slug($product->name) . '-' . $product->url;
                });
            });

            return response()->json([
                'status' => true,
                'data'   => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data'   => [],
                'error'  => $e->getMessage()
            ], 500);
        }
    }
}