<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Manually check credentials since column names are different
        $user = \App\Models\PartnerShop::where('partner_shops_email', $credentials['email'])->first();

        if (!$user || !\Hash::check($credentials['password'], $user->partner_shops_password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('dashboard');
    }


    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function systemUserLogin(Request $request)
    {
        // Validate incoming request data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Manually check credentials using the SystemUser model
        $user = \App\Models\SystemUser::where('email', $credentials['email'])->first();
        return redirect()->intended('adm-dsh');
    }

}

