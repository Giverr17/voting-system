<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerate();

        return redirect('/');
    }
    public function login()
    {
        return view('login');
    }

    // public function loginAuth(Request $request)
    // {
    //     $request->validate([
    //         'identifier' => 'required|email',
    //         'password' => 'required|string',
    //     ]);

    //     $identifier = $request->identifier;


    //     if (str_contains($identifier, '@')) {
    //         $user = User::where('email', $identifier)
    //             ->where('role', 'admin')
    //             ->first();

    //         if (!$user || !Hash::check($request->password, $user->password)) {
    //             return back()->with('error', 'Please verify your credentials and try again.');
    //         }
    //         Auth::login($user);
    //         return redirect('/admin/dashboard');
    //     }

    //     $user = User::where('mat_no', $identifier)
    //         ->where('role', 'user')
    //         ->first();

    //     if (!$user || !Hash::check($request->code, $user->code)) {
    //         return back()->withErrors(['identifier' => 'Invalid voting code']);
    //     }

    //     Auth::login($user);
    //     return redirect('/vote');
    // }
}
