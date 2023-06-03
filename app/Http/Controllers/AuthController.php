<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function logIn(Request $request) {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->input('remember');

        if (isset($remember)) {
            $remember = true;
        } else {
            $remember = false;
        }

        if (Auth::guard('admin') -> attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect('/');
        } else {
            return response()->json(['errors' => ['message' => 'Email and password were not matched']], 401);
        }
    }

    public function validateCredentials(Request $request) {
        $validation = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($request->fails())
            return response()->json(['errors' => $request->errors()], 422);
        // if the request is valid, fails() doesn't exist, and laravel will return response with code 500, so we can catch the response code 500 with specific message, so that we know that its valid
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
