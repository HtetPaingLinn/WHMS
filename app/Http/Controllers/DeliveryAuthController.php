<?php

namespace App\Http\Controllers;

use Illuminate\Auth\AuthenticationException;
use App\Models\Delivery; // Your Delivery model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException; // Import ValidationException

class DeliveryAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('delivery_login'); // Your login view
    }

    public function login(Request $request)
    {
        try {
            // Validate user input
            $input = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);

//            dd($input);

            // Retrieve the driver by email
            $driver = Delivery::where('email', $input['email'])->first();

            if (!$driver) {
                // Throw custom exception for invalid email
                throw new AuthenticationException('Invalid email');
            }

            if ($input['password'] != $driver->password) {
                // Throw custom exception for incorrect password
                throw new AuthenticationException('Incorrect password');
            }

            // Password matches, log the driver in
            Auth::login($driver);
            return redirect()->route('del-dsh')->with('success', 'Login successful');

        } catch (AuthenticationException $e) {
            // If authentication fails (invalid email or password)
            return back()->withErrors(['email' => $e->getMessage()]);
        } catch (ValidationException $e) {
            // If validation fails (invalid input)
            return back()->withErrors($e->errors());
        }

    }

    public function logout(Request $request)
    {
        Auth::guard('delivery')->logout(); // Logout using the 'delivery' guard

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/del-login'); // Redirect to login page
    }
}
