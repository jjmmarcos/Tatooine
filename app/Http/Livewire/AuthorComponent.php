<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Author;
use App\Models\Category;
use App\Models\Subcategory;
use Cart;
use Livewire\WithPagination;

class AuthorComponent extends Component
{
    public $sorting;
    public $pagesize;
    public $author_slug; 

    public $min_price;
    public $max_price;

    public function mount($author_slug)
    {
        $this->sorting = "default";
        $this->pagesize = 12;
        $this->author_slug = $author_slug;
        
        $this->min_price = 1;
        $this->max_price = 100;
    }

    public function store($product_id,$product_name,$product_price)
    {
        Cart::add($product_id,$product_name,1,$product_price)->associate('App\Models\Product');
        session()->flash('success_message','Item added in Cart');
        return redirect()->route('product.cart');
    }

    use WithPagination;
    public function render()
    {
        $author_id = null;
        $author_name = "";
        $filter = "";
        $author = Author::where('slug',$this->author_slug)->first();
        $author_id = $author->id;
        $author_name = $author->name;
        $filter = "";

        if($this->sorting=='date')
        {
            $products = Product::where($filter.'author_id',$author_id)->whereBetween('regular_price',[$this->min_price,$this->max_price])->orderBy('created_at','DESC')->paginate($this->pagesize);
        }
        else if($this->sorting=="price")
        {
            $products = Product::where($filter.'author_id',$author_id)->whereBetween('regular_price',[$this->min_price,$this->max_price])->orderBy('regular_price','ASC')->paginate($this->pagesize);
        }
        else if($this->sorting=="price-desc") 
        {
            $products = Product::where($filter.'author_id',$author_id)->whereBetween('regular_price',[$this->min_price,$this->max_price])->orderBy('regular_price','DESC')->paginate($this->pagesize);
        }
        else
        {
            $products = Product::where($filter.'author_id',$author_id)->whereBetween('regular_price',[$this->min_price,$this->max_price])->paginate($this->pagesize);
        }

        $authors = Author::orderBy('name')->get();
        $categories = Category::all();
        return view('livewire.author-component',['products'=> $products,'categories'=>$categories,'authors'=>$authors,'author_name'=>$author_name])->layout("layouts.base");
    }
}
