<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //

    public function listAllUsers()
    {
        $users = User::whereNotIn('id', [2])->get();
        return view('admin')->with (compact('users'));
    }

    public function enable($id,Request $request): RedirectResponse
    {
        if($request->user()->is_admin == 1){
            $user = User::find($id);
            $user->is_enabled = true;
            $user->save();

            return redirect()->back();
        }else{
           return redirect('home')->with('error', 'You don\'t have admin access.');
        }

    }

    public function disable($id, Request $request): RedirectResponse
    {
        if($request->user()->is_admin == 1){
            $user = User::find($id);
            $user->is_enabled = false;
            $user->save();
            $user->sessions()->delete();

            return redirect()->back();
        }else{
            return redirect()->route('home')->with('error', 'You don\'t have admin access.');
        }


    }
}
