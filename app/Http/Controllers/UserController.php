<?php

namespace App\Http\Controllers;

use App\Events\PictureUpdated;
use App\Models\User;
use App\Rules\SQLiRule;
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

    public function uploadPicture(Request $request)
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

        event(new PictureUpdated($user));

        return redirect()->back();
    }

    public function updateName(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => [new SQLiRule,'string', 'min:4', 'unique:users,name,' . Auth()->user()->id],
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $user = auth()->user();
        $user->name = $request->name;
        $user->save();

        // @TODO Log name update

        return redirect()->back();
    }
}
