<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            Session::put('user_id', Auth::user()->id);
            return redirect('/')->with('success', 'Login successful');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:male,female,other',
        ]);

        // First check if any automatic creation is happening
        // $countBefore = Patient::count();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Tenant',
        ]);

        // $countAfter = Patient::count();

        
        // if ($countAfter > $countBefore) {
        //     $patient = Patient::where('user_id', $user->id)->first();

        //     if ($patient) {
        //         $patient->age = (int)$request->age;
        //         $patient->gender = $request->gender;
        //         $patient->save();

        //         Auth::login($user);
        //         return redirect('/')->with('success', 'Registration successful!');
        //     }
        // }

        // If no automatic creation happened, create patient normally
        try {
            // Use updateOrCreate to handle case where patient record already exists
            Tenant::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'age' => (int)$request->age,
                    'gender' => $request->gender,
                ]
            );

            Auth::login($user);
            $request->session()->regenerate();
            Session::put('user_id', Auth::user()->id);
            return redirect('/')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            // If patient creation fails, delete the user we created
            if ($user) {
                $user->delete();
            }

            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::forget('user_id');

        return redirect('/');
    }
}
