<?php

namespace App\Http\Controllers\Admin;

use App\Facades\CustomLogger;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->can('view permission module'), 401, "Unauthorized!");

        $roles = Role::where('id', '!=', 1)->get();
        return view('admin.roles.index', [
            'pageData' => $roles,
        ]);
    }

    public function create()
    {
        abort_if(!auth()->user()->can('view permission module'), 401, "Unauthorized!");

        return view('admin.roles.alter', [
            'action' => 'Add',
            'actionUrl' => route('admin.roles.store'),
            'permissions' => Permission::where('name', '!=', 'view permission module')->get(),
        ]);
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('view permission module'), 401, "Unauthorized!");

        $validated = $request->validate([
            'name' => ['required','min:3','max:25', 'regex:/^[\pL\s]+$/u', Rule::unique('roles', 'name') ],
            'permissions' => 'required|array|min:1',
        ]);

        try {
            $role = Role::create([
                'name' => trim($validated['name']),
                'guard_name' => 'admin'
            ]);

            foreach($validated['permissions'] as $permission) {
                $role->givePermissionTo(Permission::findOrFail($permission));
            }

            return redirect(route('admin.roles.index'))->with('success', config('constants.default_data_insert_msg'));
        } catch (\Throwable $th) {
            CustomLogger::write('Role', ERROR, $th->getMessage());
            return redirect(route('admin.roles.index'))->with('error',config('constants.default_data_failed_msg'));
        }
    }

    public function edit(Role $role)
    {
        abort_if(!auth()->user()->can('view permission module'), 401, "Unauthorized!");

        return view('admin.roles.alter', [
            'action' => 'Edit',
            'data' => $role,
            'permissions' => Permission::where('name', '!=', 'view permission module')->get(),
            'rolePermissions' => $role->permissions()->get()->pluck('id')->toArray(),
            'actionUrl' => route('admin.roles.update', $role),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        abort_if(!auth()->user()->can('view permission module'), 401, "Unauthorized!");

        $validated = $request->validate([
            'name' => ['required','min:3','max:25', 'regex:/^[\pL\s]+$/u', Rule::unique('roles', 'name')->ignore($role->id) ],
            'permissions' => 'required|array|min:1',
        ]);

        try {
            $role->update(['name' => trim($validated['name'])]);
            foreach($validated['permissions'] as $permission) {
                $p = Permission::findOrFail($permission);
                $permissionsToAssign[] = $p->name;
            }
            $role->syncPermissions($permissionsToAssign);

            return redirect(route('admin.roles.index'))->with('success', config('constants.default_data_insert_msg'));
        } catch (\Throwable $th) {
            CustomLogger::write('Role', ERROR, $th->getMessage());
            return redirect(route('admin.roles.index'))->with('error',config('constants.default_data_failed_msg'));
        }
    }

    public function destroy(Role $role)
    {
        abort_if(!auth()->user()->can('view permission module'), 401, "Unauthorized!");

        try {
            $role->syncPermissions([]);
            $role->delete();

            return redirect(route('admin.roles.index'))->with('success', config('constants.default_data_insert_msg'));
        } catch (\Throwable $th) {
            CustomLogger::write('Role', ERROR, $th->getMessage());
            return redirect(route('admin.roles.index'))->with('success', config('constants.default_data_failed_msg'));
        }
    }
}
