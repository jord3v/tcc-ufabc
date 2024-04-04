<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleAndPermissionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private Role $role, private Permission $permission)
    {
        $this->middleware('permission:role-list', ['only' => ['index','show']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $roles = $this->role->with('permissions')->paginate(10);
        $permissions = $this->permission->get()->groupBy('module');
        return view('dashboard.roles-and-permissions.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleAndPermissionRequest $request)
    {
        $role = $this->role->create(['name' => $request->name]);
        $permissions = array_map('intval', $request->permissions);
        $role->syncPermissions($permissions);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = $this->role->with('permissions')->find($id);
        return response()->json($role);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRoleAndPermissionRequest $request, string $id): RedirectResponse
    {
        $role = $this->role->find($id);
        $role->update($request->all());
        $permissions = array_map('intval', $request->permissions);
        $role->syncPermissions($permissions);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        if(!$role = $this->role->find($id))
            return redirect()->back();
        $role->delete();
        return redirect()->back();
    }
}
