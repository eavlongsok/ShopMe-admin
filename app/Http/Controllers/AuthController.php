<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function logIn(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $remember = $request->input('remember');

        if (Auth::guard('admin') -> attempt(['email' => $email, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return response()->json(['success' => ['message' => 'Login successfully']], 200);
        } else {
            return response()->json(['errors' => ['message' => 'Email and password were not matched']], 401);
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
