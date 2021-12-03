<?php

namespace App\Http\Livewire\Admin;

use App\Models\Author;
use Illuminate\Support\Str;
use Livewire\Component;

class AdminEditAuthorComponent extends Component
{
    public $name;
    public $slug;

    public function mount($author_slug) 
    {
        $this->author_slug = $author_slug;
        $author = Author::where('slug',$author_slug)->first();
        $this->author_id = $author->id;
        $this->name = $author->name;
        $this->slug = $author->slug;                
    }

    public function generateslug()
    {
        $this->slug = Str::slug($this->name);
    }

    public function updated($fields)
    {
        $this->validateOnly($fields,[
            'name' => 'required',
            'slug' => 'required|unique:categories' // Constraint for prevent add duplicated categories
        ]);
    }

    public function updateAuthor()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories'
        ]);
        $author = Author::find($this->author_id);
        $author->name = $this->name;
        $author->slug = $this->slug;
        $author->save();        
        session()->flash('message','Author has been updated successfully!');
    }

    public function render()
    {
        $authors = Author::orderBy('name')->get();
        return view('livewire.admin.admin-edit-author-component')->layout("layouts.base");
    }
}
