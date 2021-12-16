<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Author;
use App\Models\Ilustrator;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class AdminEditProductComponent extends Component
{
    use WithFileUploads;
    public $name;
    public $slug;
    public $short_description;
    public $description;
    public $number_of_pages;
    public $isbn;
    public $regular_price;
    public $sale_price;
    public $stock_status;
    public $featured;
    public $quantity;
    public $image;
    public $category_id;
    public $newimage;
    public $product_id;
    public $author_id;
    public $ilustrator_id;

    public $images;
    public $newimages;
    public $scategory_id;

    public function mount($product_slug)
    {
        $product = Product::where('slug',$product_slug)->first();
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->short_description = $product->short_description;
        $this->description = $product->description;
        $this->number_of_pages = $product->number_of_pages;
        $this->isbn = $product->isbn;
        $this->regular_price = $product->regular_price;
        $this->sale_price = $product->sale_price;
        $this->stock_status = $product->stock_status;
        $this->featured = $product->featured;
        $this->quantity = $product->quantity;
        $this->image = $product->image;
        $this->images = explode(",",$product->images);
        $this->category_id = $product->category_id;
        $this->scategory_id = $product->subcategory_id;
        $this->product_id = $product->id;
        $this->author_id = $product->author_id;
        $this->ilustrator_id = $product->ilustrator_id;
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->name,'-');
    }

    public function updated($fields)
    {
        $this->validateOnly($fields,[
            'name' => 'required',
            'slug' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'number_of_pages' => 'numeric',
            'isbn' => 'string',
            'regular_price' => 'required|numeric',
            'sale_price' => 'numeric',
            'stock_status' => 'required',
            'quantity' => 'required|numeric',
            'category_id' => 'required'
        ]);
        if($this->newimage)
        {
            $this->validateOnly($fields,[
                'newimage' => 'required|mimes:jpeg,png'
            ]);
        }
    }

    public function updateProduct()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required|numeric',
            'sale_price' => 'numeric',
            'stock_status' => 'required',
            'quantity' => 'required|numeric',
            'category_id' => 'required'
        ]);
        if($this->newimage)
        {
            $this->validate([
                'newimage' => 'required|mimes:jpeg,png'
            ]);
        }
        $product = Product::find($this->product_id);
        $product->name = $this->name;
        $product->slug = $this->slug;
        $product->short_description = $this->short_description;
        $product->description = $this->description;
        $product->regular_price = $this->regular_price;
        $product->sale_price = $this->sale_price;
        $product->stock_status = $this->stock_status;
        $product->featured = $this->featured;
        $product->quantity = $this->quantity;
        if($this->newimage)
        {
            unlink('assets/images/products' . '/' . $product->image);
            $imageName = Carbon::now()->timestamp.'.'.$this->newimage->extension();
            $this->newimage->storeAs('products',$imageName);
            $product->image = $imageName; 
        }

        if($this->newimages)
        {
            if($product->images)
            {
                $images = explode(",",$product->images);
                foreach($images as $image)
                {
                    if($image)
                    {
                        unlink('assets/images/products' . '/' . $image);
                    }
                }
            }
            $imagesname = '';
            foreach($this->newimages as $key=>$image)
            {
                $imgName = Carbon::now()->timestamp . $key . '.' . $image->extension();
                $image->storeAs('products',$imgName);
                $imagesname = $imagesname . ',' . $imgName;
            }
            $product->images = $imagesname;
        }
        
        $product->category_id = $this->category_id;
        if($this->scategory_id)
        {
            $product->subcategory_id = $this->scategory_id;
        }
        $product->author_id = $this->author_id;
        $product->ilustrator_id = $this->ilustrator_id;
        $product->save();
        session()->flash('message','Product has been updated successfully!');
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();
        $scategories = Subcategory::where('category_id',$this->category_id)->get();
        $authors = Author::orderBy('name')->get();
        $ilustrators = Ilustrator::orderBy('name')->get();
        return view('livewire.admin.admin-edit-product-component',['categories'=>$categories,'scategories'=>$scategories,'authors'=>$authors,'ilustrators'=>$ilustrators])->layout('layouts.base');
    }
}