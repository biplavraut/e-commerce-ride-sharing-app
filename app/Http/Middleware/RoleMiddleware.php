<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @param string $role
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next, $role)
	{
		if (!$request->user('admin')->hasRole($role)) {
			return response()->json([
				'message' => 'You are not Authorized to perform this action!',
			], Response::HTTP_UNAUTHORIZED);
		}

		return $next($request);
	}
}
