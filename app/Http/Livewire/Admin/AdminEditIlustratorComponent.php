<?php

namespace App\Http\Livewire\Admin;

use App\Models\Ilustrator;
use Illuminate\Support\Str;
use Livewire\Component;

class AdminEditIlustratorComponent extends Component
{
    public $name;
    public $slug;

    public function mount($ilustrator_slug) 
    {
        $this->ilustrator_slug = $ilustrator_slug;
        $ilustrator = Ilustrator::where('slug',$ilustrator_slug)->first();
        $this->ilustrator_id = $ilustrator->id;
        $this->name = $ilustrator->name;
        $this->slug = $ilustrator->slug;                
    }

    public function generateslug()
    {
        $this->slug = Str::slug($this->name);
    }

    public function updated($fields)
    {
        $this->validateOnly($fields,[
            'name' => 'required',
            'slug' => 'required|unique:ilustrators' // Constraint for prevent add duplicated ilustrators
        ]);
    }

    public function updateIlustrator()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:ilustrators'
        ]);
        $ilustrator = Ilustrator::find($this->ilustrator_id);
        $ilustrator->name = $this->name;
        $ilustrator->slug = $this->slug;
        $ilustrator->save();        
        session()->flash('message','Ilustrator has been updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.admin-edit-ilustrator-component')->layout('layouts.base');
    }
}
