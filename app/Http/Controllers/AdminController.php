<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //

    public function listAllUsers()
    {
        $users = User::all();
        return view('admin')->with (compact('users'));
    }

}
