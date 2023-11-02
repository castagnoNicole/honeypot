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
        $users = User::all();
        return view('admin')->with (compact('users'));
    }

    public function enable($id): RedirectResponse
    {
        $user = User::find($id);
        $user->is_enabled = true;
        $user->save();

        return redirect()->back();
    }

    public function disable($id): RedirectResponse
    {
        $user = User::find($id);
        $user->is_enabled = false;
        $user->save();
        $user->sessions()->delete();

        return redirect()->back();
    }
}
