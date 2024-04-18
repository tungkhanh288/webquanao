<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
class PageController extends Controller
{
    //
    public function getProduct(){
        $products = Product::join('categories', 'categories.category_id', 'products.category_id')
        ->select(
            'products.product_id',
            'products.product_name',
            'products.product_image',
            'products.product_price',
            'products.product_discount',
            'categories.category_gender'
        )
        ->orderByRaw('RAND()')
        ->limit(10)->get();
        $sellings = Product::select(
            'products.product_id',
            'products.product_name',
            'products.product_image',
            'products.product_price',
            'products.product_discount'
        )
        ->orderByRaw('RAND()')
        ->limit(10)->get();
        return view('frontend.homepage', compact('products', 'sellings'));
    }
    public function getDetail($id_name, Request $req){
        $size = $req->get('size');
        $products = Product::where('product_id', $id_name)->first();
        $quantities = $products->sizes()->withPivot('quantity')->get();
        return view('frontend.detailProduct', compact('products', 'quantities', 'size'));
    }
    public function search(Request $req)
    {
        $product = Product::where('product_name', 'like', '%' . $req->key . '%')
            ->orwhere('product_price', $req->key)
            ->paginate(12);
        return view('frontend.search', compact('product'));
    }
    public function getAllCategories(Request $req)
    {
        if($req->key == 'begai')
            $key = 'Nữ';
        elseif($req->key == 'betrai')
            $key = 'Nam';
        else
            $key = 'Phụ kiện';
        $products = Product::join('categories', 'products.category_id', 'categories.category_id')
        ->select(
            'products.product_id',
            'products.product_name',
            'products.product_image',
            'products.product_price',
            'products.product_discount'
        )
        ->orderByRaw('RAND()')
        ->where('categories.category_gender', $key)->limit(8)->get();
        return view('frontend.category', compact('products'));
    }
}
