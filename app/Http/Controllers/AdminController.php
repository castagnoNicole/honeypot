<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function indexUsers()
    {
        $users = User::where('is_admin', false);

        return view('app.users', compact('users'));
    }

}
