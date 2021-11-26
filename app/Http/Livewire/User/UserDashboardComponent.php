<?php

namespace App\Http\Livewire\User;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Livewire\Component;

class UserDashboardComponent extends Component
{
    public function render()
    {
        $orders = Order::orderBy('created_at','DESC')->where('user_id',Auth::user()->id)->get()->take(10);
        $totalCost = Order::where('status','!=','canceled')->where('user_id',Auth::user()->id)->sum('total');
        $totalPurchase = Order::where('status','!=','canceled')->where('user_id',Auth::user()->id)->count();
        $totalDelivered = Order::where('status','delivered')->where('user_id',Auth::user()->id)->count();
        $totalCanceled = Order::where('status','canceled')->where('user_id',Auth::user()->id)->count();
        return view('livewire.user.user-dashboard-component',['orders'=>$orders,'totalCost'=>$totalCost,'totalPurchase'=>$totalPurchase,'totalDelivered'=>$totalDelivered,'totalCanceled'=>$totalCanceled])->layout('layouts.base');
    }
}
