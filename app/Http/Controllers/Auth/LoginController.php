<?php

/**
 * Fichier : app/Http/Controllers/Auth/LoginController.php
 * Description : composant du projet Cricroc.
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials['email'] = strtolower(trim($credentials['email']));

        $user = User::where('email', $credentials['email'])->first();
        $validPassword = false;

        if ($user) {
            try {
                $validPassword = Hash::check($credentials['password'], $user->password);
            } catch (\RuntimeException $exception) {
                if ($exception->getMessage() === 'This password does not use the Bcrypt algorithm.'
                    && $credentials['password'] === $user->password) {
                    $user->password = $credentials['password'];
                    $user->save();
                    $validPassword = true;
                }
            }
        }

        if (! $user || ! $validPassword) {
            return back()->withErrors([
                'email' => 'Identifiants incorrects.',
            ])->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
