<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Author;
use App\Models\Ilustrator;
use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;
use Cart;
use DB;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ShopComponent extends Component
{
    public $sorting;
    public $pagesize;

    public $min_price;
    public $max_price;

    public function mount()
    {
        $this->sorting = "default";
        $this->pagesize = 12;

        $this->min_price = 1;
        $this->max_price = 100;

    }
    public function store($product_id,$product_name,$product_price)
    {
        Cart::instance('cart')->add($product_id, $product_name,1, $product_price)->associate('App\Models\Product');
        session()->flash('success_message','Item added in Cart');
        return redirect()->route('product.cart');
    }

    public function addToWishlist($product_id,$product_name,$product_price) 
    {
        Cart::instance('wishlist')->add($product_id, $product_name,1, $product_price)->associate('App\Models\Product');
        $this->emitTo('whislist-count-component','refreshComponent');
    }

    public function removeFromWishlist($product_id)
    {
        foreach(Cart::instance('wishlist')->content() as $witem)
        {
            if($witem->id == $product_id)
            {
                Cart::instance('wishlist')->remove($witem->rowId);
                $this->emitTo('whislist-count-component','refreshComponent');
                return;
            }
        }
    }

    use WithPagination;
    public function render()
    {
        if($this->sorting=='date')
        {
            $products = Product::whereBetween('regular_price',[$this->min_price,$this->max_price])->orderBy('created_at','DESC')->paginate($this->pagesize);
        }
        else if($this->sorting=="price")
        {
            $products = Product::whereBetween('regular_price',[$this->min_price,$this->max_price])->orderBy('regular_price','ASC')->paginate($this->pagesize);
        }
        else if($this->sorting=="price-desc") 
        {
            $products = Product::whereBetween('regular_price',[$this->min_price,$this->max_price])->orderBy('regular_price','DESC')->paginate($this->pagesize);
        }
        else
        {
            $products = Product::whereBetween('regular_price',[$this->min_price,$this->max_price])->paginate($this->pagesize);
        }

        $categories = Category::all();

        if(Auth::check())
        {
            Cart::instance('cart')->store(Auth::user()->email);
            Cart::instance('wishlist')->store(Auth::user()->email);
        }

        $authors = Author::orderBy('name')->get();
        $ilustrators = Ilustrator::orderBy('name')->get();
        $reviews = Review::orderBy('rating')->limit(5)->get();
        $popularProducts = DB::table('products')
            ->join('reviews', 'products.id', '=', 'reviews.product_id')
            ->select('products.*')
            ->orderByRaw('rating DESC')
            ->limit(3)
            ->get();        
        return view('livewire.shop-component', ['products'=> $products,'categories'=>$categories,'authors'=>$authors,'ilustrators'=>$ilustrators,'popularProducts'=>$popularProducts])->layout("layouts.base");
    }
}


