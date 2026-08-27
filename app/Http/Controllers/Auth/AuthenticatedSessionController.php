<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Email Verification
        |--------------------------------------------------------------------------
        */

        if (!$user->hasVerifiedEmail()) {

            Auth::logout();

            return back()->withErrors([
                'email' => 'Please verify your email before logging in.',
            ])->onlyInput('username');
        }

        /*
        |--------------------------------------------------------------------------
        | Registrar Approval
        |--------------------------------------------------------------------------
        */

        if ($user->status !== 'active') {

            Auth::logout();

            return back()->withErrors([
                'email' => 'Your account is still awaiting Registrar approval.',
            ])->onlyInput('username');
        }

        /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Role-Based Redirect
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            return redirect()->route('admin.dashboard');

        }

        if ($user->role === 'instructor') {

            return redirect()->route('instructor.dashboard');

        }

        return redirect()->route('student.dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}