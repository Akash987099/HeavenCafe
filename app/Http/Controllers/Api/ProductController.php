<?php

namespace App\Http\Controllers\Api;

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
    public function products()
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

            // $categoryIds = $data->pluck('products')
            //     ->flatten()
            //     ->pluck('category')
            //     ->filter()
            //     ->unique()
            //     ->values();

            // $categories = [];

            // foreach ($categoryIds as $categoryId) {

            //     $cat = $this->categorysubcategory($categoryId);

            //     if ($cat) {
            //         $categories[] = $cat;
            //     }
            // }

            return response()->json([
                'status' => true,
                // 'categories' => $categories,
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

    public function summerProducts($id)
    {
        try {
            $products = Product::leftJoin('discounts', 'discounts.id', '=', 'products.discount')
                ->leftJoin('brands', 'brands.id', '=', 'products.brands')
                ->where('products.summer_id', $id)
                ->select(
                    'products.id',
                    'products.name',
                    'products.image',
                    'products.price',
                    'products.ac_price',
                    'products.stock',
                    'products.in_stock',
                    'products.slug',
                    'products.category',
                    'products.short_description',
                    'discounts.name as discount',
                    'brands.name as brand',
                    'products.product_type',
                    'products.type',
                    'products.type_value',
                    'products.brand_name'
                )
                ->get();

            $products->each(function ($product) {
                $product->url = Str::slug($product->name) . '-' . $product->id;
                // unset($product->id);
            });

            if ($products->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'data'   => []
                ], 404);
            }

            $categoryIds = $products->pluck('category')
                ->filter()
                ->unique()
                ->values();

            $categories = [];

            foreach ($categoryIds as $categoryId) {

                $cat = $this->categorysubcategory($categoryId);

                if ($cat) {
                    $categories[] = $cat;
                }
            }

            return response()->json([
                'status' => true,
                'categories' => $categories,
                'data'   => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data'   => [],
                'error'  => $e->getMessage()
            ], 500);
        }
    }

    public function comboProducts()
    {
        try {

            $combos = Combo::with('product')
                ->select('combo_product_id', 'image', 'price', 'description')
                ->get();

            $data = [];

            foreach ($combos as $combo) {

                $items = Combo::where('combo_product_id', $combo->combo_product_id)
                    ->with('product')
                    ->get();

                $comboName = $items->first()->product->name ?? 'combo';

                $data[] = [
                    'combo_id'   => $combo->combo_product_id,
                    'name'       => $comboName,
                    'url'        => '4' . '-' . Str::slug($comboName) . '-' . $combo->combo_product_id,
                    'image'      => $combo->image,
                    'price'      => $combo->price,
                    'description' => $combo->description,

                    'items' => $items->map(function ($item) {
                        return [
                            'id'    => $item->product->id ?? null,
                            'name'  => $item->product->name ?? '',
                            'image' => $item->product->image ?? '',
                            'price' => $item->product->price ?? 0,
                            'url'   => Str::slug($item->product->name ?? '') . '-' . ($item->product->id ?? 0) // ✅ item url bhi
                        ];
                    })
                ];
            }

            return response()->json([
                'status' => true,
                'data'   => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => $e->getMessage()
            ]);
        }
    }

    public function allProducts()
    {
        try {
            $products = Product::leftJoin('discounts', 'discounts.id', '=', 'products.discount')
                ->leftJoin('brands', 'brands.id', '=', 'products.brands')
                ->select(
                    'products.id',
                    'products.name',
                    'products.image',
                    'products.price',
                    'products.ac_price',
                    'products.stock',
                    'products.in_stock',
                    'products.slug',
                    'products.category',
                    'products.short_description',
                    'discounts.name as discount',
                    'brands.name as brand',
                    'products.product_type',
                    'products.type',
                    'products.type_value',
                    'products.brand_name'
                )
                ->get();

            $products->each(function ($product) {
                $product->url = Str::slug($product->name) . '-' . $product->id;
                // unset($product->id);
            });

            $categoryIds = $products->pluck('category')
                ->filter()
                ->unique()
                ->values();

            $categories = [];

            foreach ($categoryIds as $categoryId) {

                $cat = $this->categorysubcategory($categoryId);

                if ($cat) {
                    $categories[] = $cat;
                }
            }

            if ($products->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'data'   => []
                ], 404);
            }

            return response()->json([
                'status' => true,
                'categories' => $categories,
                'data'   => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data'   => [],
                'error'  => $e->getMessage()
            ], 500);
        }
    }

    public function categoryProducts($name)
    {

        preg_match_all('/\d+/', $name, $matches);

        $type = !empty($matches[0]) ? $matches[0][0] : null;
        $id   = !empty($matches[0]) ? end($matches[0]) : null;

        try {
            $products = Product::leftJoin('discounts', 'discounts.id', '=', 'products.discount')
                ->leftJoin('brands', 'brands.id', '=', 'products.brands');

            if ($type == 1) {
                $products = $products->where('summer_id', $id);
            } else if ($type == 2) {
                $products = $products->where('category', $id);
            } else if ($type == 3) {
                $products = $products->where('sub_category', $id);
            } else {
                $products = $products->where('brands', $id);
            }

            $products = $products->select(
                'products.id',
                'products.name',
                'products.image',
                'products.price',
                'products.ac_price',
                'products.stock',
                'products.in_stock',
                'products.slug',
                'products.category',
                'products.short_description',
                'discounts.name as discount',
                'brands.name as brand',
                'products.product_type',
                'products.type',
                'products.type_value',
                'products.brand_name'
            )
                ->get();

            $products->each(function ($product) {
                $product->url = Str::slug($product->name) . '-' . $product->id;
                // unset($product->id);
            });

            $categoryIds = $products->pluck('category')
                ->filter()
                ->unique()
                ->values();

            $categories = [];

            foreach ($categoryIds as $categoryId) {

                $cat = $this->categorysubcategory($categoryId);

                if ($cat) {
                    $categories[] = $cat;
                }
            }

            if ($products->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'data'   => []
                ], 404);
            }

            return response()->json([
                'status' => true,
                'categories' => $categories,
                'data'   => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data'   => [],
                'error'  => $e->getMessage()
            ], 500);
        }
    }

    // Product Details 
    public function productsDetails($name)
    {
        preg_match_all('/\d+/', $name, $matches);

        $id = !empty($matches[0]) ? end($matches[0]) : null;

        $product = Product::where('id', $id)->select('id', 'name', 'brand_name', 'image', 'price', 'ac_price', 'sku_code as sku', 'hsn_code as hsn', 'tags', 'meta_tag', 'category', 'sub_category', 'stock', 'in_stock', 'barcode_base as barcode', 'description', 'short_description')->first();

        $aplus = $this->aplus($id);
        $variants = $this->variants($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'data'   => []
            ], 404);
        }

        $similar = $product->similar;

        if (empty($similar) && $similar == null) {
            $similarProducts = $this->categorySubcategoryProducts($product->category, $product->sub_category, $id);
        } else {
            $similarProducts = $this->similarProducts($similar, $id);
        }

        $category = Category::find($product->category);
        $product->category = $category ? Str::slug($category->name) : null;
        $product->category_url = $category
            ? '2' . '-' . Str::slug($category->name) . '-' . $category->id
            : null;

        $sub_category = SubCategory::find($product->sub_category);
        $product->sub_category = $sub_category ? Str::slug($sub_category->name) : null;
        $product->sub_category_url = $sub_category
            ? '3' . '-' . Str::slug($sub_category->name) . '-' . $sub_category->id
            : null;

        $gallery = Gallery::where('product_id', $id)->select('image')->get();

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data'   => $product,
            'variants' => $variants,
            'gallery' => $gallery,
            'aplus'   => $aplus,
            'similar_products' => $similarProducts,
        ], 200);
    }

    private function variants($product_id)
    {

        $variants = Varient::where('product_id', $product_id)->get();

        if ($variants->isEmpty()) {
            return [];
        }

        $data = [];

        foreach ($variants as $variant) {

            $values = VarientValue::leftJoin('attribute_values', 'attribute_values.id', '=', 'product_variant_values.attribute_value_id')
                ->leftJoin('attributes', 'attributes.id', '=', 'product_variant_values.attribute_id')
                ->where('product_variant_values.variant_id', $variant->id)
                ->select(
                    'attributes.name as attribute',
                    'attribute_values.value'
                )
                ->get()
                ->toArray();

            $data[] = [
                'variant_id' => $variant->id,
                'sku' => $variant->sku,
                'price' => $variant->price,
                'stock' => $variant->stock,
                'image' => $variant->image,
                'attributes' => $values
            ];
        }

        return $data;
    }

    public function productsReview($id)
    {
        $reviews = Review::leftJoin('users', 'users.id', '=', 'reviews.user_id')
            ->where('reviews.product_id', $id)
            ->select(
                'reviews.id',
                'reviews.rating',
                'reviews.review',
                'reviews.created_at',
                'users.name',
                'users.email'
            )
            ->orderBy('reviews.id', 'desc')
            ->get();

        $reviewCount = $reviews->count();

        $avgRating = $reviewCount > 0
            ? round($reviews->avg('rating'), 1)
            : 0;

        $ratingDistribution = [];

        for ($i = 5; $i >= 1; $i--) {

            $count = $reviews->filter(function ($review) use ($i) {
                return (int)$review->rating === $i;
            })->count();

            $percentage = $reviewCount > 0
                ? round(($count / $reviewCount) * 100)
                : 0;

            $ratingDistribution[] = [
                'stars' => $i,
                'count' => $count,
                'percentage' => $percentage
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Product reviews',
            'data' => [
                'rating' => $avgRating,
                'total_reviews' => $reviewCount,
                'rating_distribution' => $ratingDistribution,
                'reviews' => $reviews->map(function ($review) {
                    return [
                        'user_name' => $review->name,
                        'user_email' => $review->email,
                        'rating' => (int)$review->rating,
                        'review' => $review->review,
                        'date' => date('d M Y', strtotime($review->created_at))
                    ];
                })
            ]
        ]);
    }

    private function review($productId)
    {
        $reviews = Review::leftJoin('users', 'users.id', '=', 'reviews.user_id')
            ->where('reviews.product_id', $productId)
            ->select(
                'reviews.id',
                'reviews.rating',
                'reviews.review',
                'reviews.created_at',
                'users.name',
                'users.email'
            )
            ->latest()
            ->get();

        $avgRating = $reviews->avg('rating');
        $reviewCount = $reviews->count();

        return [
            'rating' => round(min($avgRating, 5), 1),
            'total_reviews' => $reviewCount,
            'reviews' => $reviews->map(function ($review) {
                return [
                    'user_name' => $review->name,
                    'user_email' => $review->email,
                    'rating' => min($review->rating, 5),
                    'review' => $review->review,
                    'date' => date('d M Y', strtotime($review->created_at))
                ];
            })
        ];
    }

    private function categorysubcategory($categoryId)
    {
        $category = Category::select('id', 'name', 'image')
            ->where('id', $categoryId)
            ->first();

        if (!$category) {
            return null;
        }

        $subcategories = SubCategory::where(function ($query) use ($category) {
                $query->where('category_id', $category->id)
                    ->orWhereHas('categories', function ($categoryQuery) use ($category) {
                        $categoryQuery->whereIn('category.id', [$category->id]);
                    });
            })
            ->select('id', 'name', 'image')
            ->withCount(['products' => function ($q) {
                $q->where('status', 'active');
            }])
            ->get()
            ->map(function ($sub) {
                return [
                    'url'  => '3-' . Str::slug($sub->name) . '-' . $sub->id,
                    'name' => $sub->name,
                    'image' => $sub->image,
                    'products' => $sub->products_count
                ];
            })
            ->values();

        return [
            'url' => '2-' . Str::slug($category->name) . '-' . $category->id,
            'name' => $category->name,
            'image' => $category->image,
            'subcategories' => $subcategories
        ];
    }

    private function aplus($id)
    {
        $aplus = ProductAplus::with('images')
            ->where('product_id', $id)
            ->orderBy('id', 'asc')
            ->get();

        if ($aplus->isEmpty()) {
            return [];
        }

        $data = $aplus->map(function ($section) {

            $images = $section->images->map(function ($img) {
                return asset('aplus/' . $img->image);
            });

            return [
                'type'   => $section->section_type,
                'images' => $images
            ];
        });

        return $data;
    }

    private function similarProducts($ids, $id)
    {
        $idsArray = json_decode($ids, true);

        if (empty($idsArray) || !is_array($idsArray)) {
            return collect();
        }

        $products = Product::whereIn('id', $idsArray)
            ->where('id', '!=', $id)
            ->select(
                'id',
                'name',
                'sku_code as sku',
                'brand_name',
                'image',
                'price',
                'ac_price',
                'hsn_code as hsn',
                'description'
            )
            ->get();

        $products->each(function ($product) {
            $product->url = Str::slug($product->name) . '-' . $product->id;
            unset($product->id);
        });

        return $products;
    }

    private function categorySubcategoryProducts($category, $subcategory, $id)
    {
        $products = Product::where('id', '!=', $id)
            ->where(function ($query) use ($category, $subcategory) {
                $query->where('category', $category)
                    ->orWhere('sub_category', $subcategory);
            })
            ->select(
                'id',
                'name',
                'sku_code as sku',
                'brand_name',
                'image',
                'price',
                'ac_price',
                'hsn_code as hsn',
                'description'
            )
            ->get();

        $products->each(function ($product) {
            $product->url = Str::slug($product->name) . '-' . $product->id;
            unset($product->id);
        });

        return $products;
    }

    public function search(Request $request)
    {
        try {
            $keyword = $request->get('q');

            if (!$keyword) {
                return response()->json([
                    'status' => false,
                    'data' => []
                ], 400);
            }

            $products = Product::leftJoin('brands', 'brands.id', '=', 'products.brands')
                ->leftJoin('discounts', 'discounts.id', '=', 'products.discount')
                ->where(function ($query) use ($keyword) {
                    $query->where('products.name', 'LIKE', "%{$keyword}%")
                        ->orWhere('products.tags', 'LIKE', "%{$keyword}%")
                        ->orWhere('brands.name', 'LIKE', "%{$keyword}%");
                })
                ->select(
                    'products.id',
                    'products.name',
                    'products.image',
                    'products.price',
                    'products.ac_price',
                    'products.slug',
                    'brands.name as brand',
                    'discounts.name as discount'
                )
                ->limit(10) // 🔥 important for live search
                ->get();

            $products->each(function ($product) {
                $product->url = Str::slug($product->name) . '-' . $product->id;
                unset($product->id);
            });

            return response()->json([
                'status' => true,
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
