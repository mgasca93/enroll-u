<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        $credentials = request()->validate([
            'username'  => 'required',
            'password'  => 'required'
        ]);

        if( Auth::attempt( $credentials ) ) :
            request()->session()->regenerate();
            return redirect()->route('home');
        endif;

        return back()->withErrors([
            'username'  => 'Your credentials are invalid'
        ]);

    }

    public function logout(){
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    }
}
