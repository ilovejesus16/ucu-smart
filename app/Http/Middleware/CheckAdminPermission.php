<?php

namespace App\Http\Middleware;

use App\Support\AdminPermissions;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    public function handle(
        Request $request,
        Closure $next,
        string $feature,
        string $minimumLevel = 'view'
    ): Response {
        $user = $request->user();

        if (!$user || !in_array($user->role, ['admin', 'facilities_admin'], true)) {
            abort(403, 'Unauthorized access.');
        }

        if (!array_key_exists($feature, AdminPermissions::FEATURES)) {
            abort(403, 'Invalid permission feature.');
        }

        $permissions = $user->adminPermission?->permissions ?? [];

        $level = $permissions[$feature] ?? 'none';

        $allowedLevels = [
            'none' => 0,
            'view' => 1,
            'full' => 2,
        ];

        if (
            !isset($allowedLevels[$level]) ||
            !isset($allowedLevels[$minimumLevel]) ||
            $allowedLevels[$level] < $allowedLevels[$minimumLevel]
        ) {
            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}
