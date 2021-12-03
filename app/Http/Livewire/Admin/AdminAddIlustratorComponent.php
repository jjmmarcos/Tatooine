<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Ilustrator;
use Illuminate\Support\Str;

class AdminAddIlustratorComponent extends Component
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
            'slug' => 'unique:ilustrators'
        ]);
    }

    public function storeIlustrator()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'unique:ilustrators'
        ]);       
        $ilustrator = new Ilustrator();
        $ilustrator->name = $this->name;
        $ilustrator->slug = $this->slug;
        $ilustrator->save();
        session()->flash('message','Ilustrator has been created successfully');
    }

    public function render()
    {
        $ilustrators = Ilustrator::orderBy('name')->get();
        return view('livewire.admin.admin-add-ilustrator-component',['ilustrators'=>$ilustrators])->layout('layouts.base');
    }
}
