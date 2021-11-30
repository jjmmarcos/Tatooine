<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Author;
use Illuminate\Support\Str;

class AdminAddAuthorComponent extends Component
{
    public $name;
    public $slug;

    public function generateslug()
    {
        $this->slug = Str::slug($this->name);
    }

    public function updated($fields)
    {
        $this->validateOnly($fields,[
            'name' => 'required',
            'slug' => 'unique:authors'
        ]);
    }

    public function storeCategory()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'unique:authors'
        ]);       
        $author = new Author();
        $author->name = $this->name;
        $author->slug = $this->slug;
        $author->save();
        session()->flash('message','Author has been created successfully');
    }

    public function render()
    {
        $authors = Author::all();
        return view('livewire.admin.admin-add-author-component',['authors'=>$authors])->layout('layouts.base');
    }
}
