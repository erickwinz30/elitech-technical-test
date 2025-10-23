<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
	/**
	 * Show the login form
	 */
	public function showLoginForm()
	{
		return Inertia::render('Authentication/Login');
	}

	/**
	 * Handle login request
	 */
	public function login(Request $request)
	{
		// Validate the request
		$credentials = $request->validate([
			'username' => ['required', 'string'],
			'password' => ['required', 'string'],
		]);

		// Attempt to authenticate the user
		if (Auth::attempt($credentials)) {
			$request->session()->regenerate();

			// Redirect based on user's department
			$user = Auth::user();

			if ($user->department === 'ppic') {
				return redirect()->route('ppic.dashboard')->with('success', 'Login berhasil!');
			} elseif ($user->department === 'production') {
				return redirect()->route('production.dashboard')->with('success', 'Login berhasil!');
			}

			// Fallback: logout user if department doesn't match
			Auth::logout();
			$request->session()->invalidate();
			$request->session()->regenerateToken();

			return redirect('/login')->with('error', 'Department tidak valid.');
		}

		// If authentication fails
		return back()->withErrors([
			'username' => 'Username atau password salah.',
		])->onlyInput('username');
	}

	/**
	 * Handle logout request
	 */
	public function logout(Request $request)
	{
		Auth::logout();

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return redirect('/login')->with('success', 'Logout berhasil!');
	}
}
