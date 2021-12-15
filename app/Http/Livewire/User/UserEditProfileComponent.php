<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserEditProfileComponent extends Component
{
    use WithFileUploads;
    public $firstname;
    public $lastname;
    public $email;
    public $mobile;
    public $image;
    public $line1;
    public $line2;
    public $city;
    public $province;
    public $country;
    public $zipcode;
    public $newimage;

    public $profile;
    public $profile_id;

    public $userid;
    public $imageName;
    public $isNewImage = false;

    public function mount()
    {
        $user = User::find(Auth::user()->id);
        $this->userid = Auth::user()->id;
        $this->firstname = $user->name;        
        $this->email = $user->email;

        if (Profile::where('user_id', '=', $this->userid)->exists()) {
            $this->profile = Profile::where('user_id',$this->userid)->first();
            $this->lastname = $this->profile->lastname; 
            $this->mobile = $this->profile->mobile;
            $this->image = $this->profile->image;
            $this->line1 = $this->profile->line1;
            $this->line2 = $this->profile->line2;
            $this->city = $this->profile->city;
            $this->province = $this->profile->province;
            $this->country = $this->profile->country;
            $this->zipcode = $this->profile->zipcode;
            $this->cardnumber = $this->profile->cardnumber;
            $this->expiry_month = $this->profile->expiry_month;
            $this->expiry_year = $this->profile->expiry_year;   
            $this->profile_id = $this->profile->id;
        } 
    }

    public function updated($fields)
    {
        $this->validateOnly($fields,[
            'firstname' => 'required|string',
            'email' => 'required|email'
        ]);
    }

    public function updateProfile()
    {
        $this->validate([
            'firstname' => 'required|string',
            'email' => 'required|email'
        ]);              
        if($this->newimage)
        {
            if($this->image)
            {
                unlink('assets/images/profile/' . $this->image);
            }
            $this->imageName = Carbon::now()->timestamp . '.' . $this->newimage->extension();
            $this->newimage->storeAs('profile',$this->imageName);
            $this->isNewImage = true;
            
        }
        $user = User::find(Auth::user()->id);
        $user->name = $this->firstname;
        $user->email = $this->email;
        $user->save();

        if(!Profile::where('user_id', '=', $this->userid)->exists()) {
            $this->profile = new Profile();
        }        
        $this->profile->user_id = Auth::user()->id;
        if($this->isNewImage) {
            $this->profile->image = $this->imageName;
        } 
        $this->profile->firstname = $this->firstname;
        $this->profile->lastname = $this->lastname;
        $this->profile->email = $this->email;
        $this->profile->mobile = $this->mobile;
        $this->profile->line1 = $this->line1;
        $this->profile->line2 = $this->line2;
        $this->profile->city = $this->city;
        $this->profile->province = $this->province;
        $this->profile->country = $this->country;
        $this->profile->zipcode = $this->zipcode;
        $this->profile->cardnumber = $this->cardnumber;
        $this->profile->expiry_month = $this->expiry_month;
        $this->profile->expiry_year = $this->expiry_year;
        $this->profile->save();
        session()->flash('message','Profile has been updated successfully!');
    }

    public function render()
    {
        $profiles = Profile::all();
        $user = User::find(Auth::user()->id);
        return view('livewire.user.user-edit-profile-component',['profile'=>$this->profile,'user'=>$user])->layout('layouts.base');
    }
}
