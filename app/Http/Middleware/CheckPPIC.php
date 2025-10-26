<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPPIC
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		// Check if user is authenticated
		if (!auth()->check()) {
			return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
		}

		$user = auth()->user();

		// Manager bisa akses semua department
		// Staff hanya bisa akses department mereka sendiri
		if ($user->role === 'manager') {
			return $next($request);
		}

		// Check if user's department is 'ppic'
		if ($user->department !== 'ppic') {
			return redirect('/login')->with('error', 'Akses ditolak. Anda tidak memiliki akses ke halaman PPIC.');
		}

		return $next($request);
	}
}
