<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::with('permissions')->get();

        return view('admin.role.index', compact('roles'));
    }

    public function create()
    {
        abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all();

        return view('admin.role.create', compact('permissions'));
    }

    public function edit(Role $role)
    {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all();
        $role->load('permissions');

        return view('admin.role.edit', compact('role', 'permissions'));
    }

    public function show(Role $role)
    {
        abort_if(Gate::denies('role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->load('permissions');

        return view('admin.role.show', compact('role'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'title' => 'required|string|min:2|max:255|unique:roles,title',
            'permissions' => 'array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role = Role::create([
            'title' => $request->title,
        ]);

        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function update(Request $request, Role $role)
    {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'title' => 'required|string|min:2|max:255|unique:roles,title,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role->update([
            'title' => $request->title,
        ]);

        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
