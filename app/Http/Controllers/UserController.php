<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function show(): View
    {
        return view('user.profile');
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'profile_pic' => 'image|max:2048' //jpg, jpeg, png, bmp, gif, svg, or webp
        ]);

        if ($validation->fails()) {

            return redirect()->back()->withErrors($validation)->withInput();
        }


        $path = $request->file('profile_pic')->store('images');
        $user = auth()->user();
        $user->profile_pic = $path;
        $user->save();
        return redirect()->back();
    }

}
