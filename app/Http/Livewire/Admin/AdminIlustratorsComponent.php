<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Ilustrator;
use Livewire\WithPagination;

class AdminIlustratorsComponent extends Component
{
    use WithPagination;
    public function deleteIlustrator($id)
    {
        $ilustrator = Ilustrator::find($id);
        $ilustrator->delete();
        session()->flash('message','Ilustrator has been deleted successfully!');
    }
    public function render()
    {
        $ilustrators = Ilustrator::orderBy('name')->paginate(10);
        return view('livewire.admin.admin-ilustrators-component',['ilustrators'=>$ilustrators])->layout("layouts.base");
    }
}
