<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

use App\Models\administrators;
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

    public function login( ){
        $validator = Validator::make( request()->all(),[
            'username'      => 'required',
            'password'      => 'required'
        ]);

        if( $validator->fails() ){
            return response()->json( $validator->errors() );
        }

        $credentials = [
            'username'  => request('username'),
            'password'  => request('password')
        ];
        if( !Auth::attempt( $credentials ) ) :
            return response()->json(['message' => 'Unauthorized'], 401);
        endif;

        $administrator = administrators::where('username', request('username'))->firstOrFail();
        $token = $administrator->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Welcome' . $administrator->name,
            'accessToken'   => $token,
            'token_type'    => 'Bearer'
        ], 200);
    }
}
