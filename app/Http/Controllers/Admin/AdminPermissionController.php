<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPermission;
use App\Models\User;
use App\Support\AdminPermissions;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminPermissionController extends Controller
{
    public function edit(User $user)
    {
        abort_unless(
            in_array($user->role, ['admin', 'facilities_admin'], true),
            404
        );

        $permissions = array_merge(
            AdminPermissions::defaults(),
            $user->adminPermission?->permissions ?? []
        );

        return view('admin.users.permissions', [
            'user' => $user,
            'features' => AdminPermissions::FEATURES,
            'levels' => AdminPermissions::LEVELS,
            'permissions' => $permissions,
        ]);
    }

    public function update(Request $request, User $user)
    {
        abort_unless(
            in_array($user->role, ['admin', 'facilities_admin'], true),
            404
        );

        $validated = $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => [
                'required',
                Rule::in(array_keys(AdminPermissions::LEVELS)),
            ],
        ]);

        $permissions = array_merge(
            AdminPermissions::defaults(),
            $validated['permissions']
        );

        // Keep the current administrator from accidentally
        // removing their own access to the permission editor.
        if ($request->user()->is($user)) {
            abort_if(
                ($permissions['user_management'] ?? 'none') === 'none',
                422,
                'You cannot remove your own access to User Management.'
            );
        }

        AdminPermission::updateOrCreate(
            ['user_id' => $user->id],
            ['permissions' => $permissions]
        );

        return redirect()
            ->route('admin.users.permissions.edit', $user)
            ->with('success', 'Administrator permissions updated successfully.');
    }
}
