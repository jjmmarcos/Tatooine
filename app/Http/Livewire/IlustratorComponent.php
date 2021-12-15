<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Author;
use App\Models\Ilustrator;
use App\Models\Category;
use App\Models\Subcategory;
use Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class IlustratorComponent extends Component
{
    public $sorting;
    public $pagesize;
    public $ilustrator_slug; 

    public $min_price;
    public $max_price;

    public function mount($ilustrator_slug)
    {
        $this->sorting = "default";
        $this->pagesize = 12;
        $this->ilustrator_slug = $ilustrator_slug;
        
        $this->min_price = 1;
        $this->max_price = 100;
    }

    public function store($product_id,$product_name,$product_price)
    {
        Cart::add($product_id,$product_name,1,$product_price)->associate('App\Models\Product');
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
        $ilustrator_id = null;
        $ilustrator_name = "";
        $filter = "";
        $ilustrator = Ilustrator::where('slug',$this->ilustrator_slug)->first();
        $ilustrator_id = $ilustrator->id;
        $ilustrator_name = $ilustrator->name;
        $filter = "";

        if($this->sorting=='date')
        {
            $products = Product::where($filter.'ilustrator_id',$ilustrator_id)->whereBetween('regular_price',[$this->min_price,$this->max_price])->orderBy('created_at','DESC')->paginate($this->pagesize);
        }
        else if($this->sorting=="price")
        {
            $products = Product::where($filter.'ilustrator_id',$ilustrator_id)->whereBetween('regular_price',[$this->min_price,$this->max_price])->orderBy('regular_price','ASC')->paginate($this->pagesize);
        }
        else if($this->sorting=="price-desc") 
        {
            $products = Product::where($filter.'ilustrator_id',$ilustrator_id)->whereBetween('regular_price',[$this->min_price,$this->max_price])->orderBy('regular_price','DESC')->paginate($this->pagesize);
        }
        else
        {
            $products = Product::where($filter.'ilustrator_id',$ilustrator_id)->whereBetween('regular_price',[$this->min_price,$this->max_price])->paginate($this->pagesize);
        }

        $authors = Author::orderBy('name')->get();
        $ilustrators = Ilustrator::orderBy('name')->get();
        $categories = Category::all();
        return view('livewire.ilustrator-component',['products'=> $products,'categories'=>$categories,'ilustrators'=>$ilustrators,'ilustrator_name'=>$ilustrator_name,'authors'=>$authors])->layout("layouts.base");
    }
}
