<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        // Mapping string role ke integer role id
        $roleMap = [
            'superadmin' => User::ROLE_SUPERADMIN,
            'kasir' => User::ROLE_KASIR,
            'operator' => User::ROLE_OPERATOR,
            (string) User::ROLE_SUPERADMIN => User::ROLE_SUPERADMIN,
            (string) User::ROLE_KASIR => User::ROLE_KASIR,
            (string) User::ROLE_OPERATOR => User::ROLE_OPERATOR,
        ];

        $allowedRoleIds = [];
        foreach ($roles as $role) {
            $key = strtolower(trim($role));
            if (isset($roleMap[$key])) {
                $allowedRoleIds[] = $roleMap[$key];
            } elseif (is_numeric($role)) {
                $allowedRoleIds[] = (int) $role;
            }
        }

        if (! in_array($user->role, $allowedRoleIds, true)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
