<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Author;
use Livewire\WithPagination;

class AdminAuthorsComponent extends Component
{
    use WithPagination;

    public function deleteAuthor($id)
    {
        $author = Author::find($id);
        $author->delete();
        session()->flash('message','Author has been deleted successfully!');
    }

    public function render()
    {
        $authors = Author::paginate(5);
        return view('livewire.admin.admin-authors-component',['authors'=>$authors])->layout('layouts.base');
    }
}
