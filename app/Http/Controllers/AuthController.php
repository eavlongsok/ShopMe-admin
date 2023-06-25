<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function logIn(Request $request) {

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $usernameExists = Admin::where('username', $username)->exists();
        if (!$usernameExists) {
            return response()->json(['errors' => ['accountNotFound' => 'Username doesn\'t exist']], 401);
        }

        $remember = $request->input('remember');

        if (Auth::guard('admin') -> attempt(['username' => $username, 'password' => $password], $remember)) {
            $admin = Admin::where('username', $username)->first();
            $token = $admin->createToken(time())->plainTextToken;
            $admin->api_token = $token;
            $admin->save();
            $request->session()->regenerate();
            return response()->json(['success' => ['message' => 'Login successfully'], 'token' => $token], 200);
        } else {
            return response()->json(['errors' => ['unmatched' => 'Email and password were not matched']], 401);
        }
    }

    public function homePage() {
        if (auth()->guard('admin')->check()) {
            return view('home');
        }
        else return redirect('/login');
    }

    public function logOut() {
        auth()->guard('admin')->logout();
        return redirect('/login');
    }
}
