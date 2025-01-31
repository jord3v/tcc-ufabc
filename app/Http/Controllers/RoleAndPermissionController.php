<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleAndPermissionRequest;
use Illuminate\View\View;
use Illuminate\Http\{JsonResponse,RedirectResponse};
use Spatie\Permission\Models\{Permission,Role};

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
    public function store(RoleAndPermissionRequest $request): RedirectResponse
    {
        $role = $this->role->create(['name' => $request->name]);
        $role->permissions()->sync($request->permissions);
        return back()->with('success', 'Função adicionada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): JsonResponse
    {
        $role = $this->role->with('permissions')->findOrFail($id);
        return response()->json($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleAndPermissionRequest $request, string $id): RedirectResponse
    {
        $role = $this->role->findOrFail($id);
        $role->update($request->all());
        $role->permissions()->sync($request->permissions);
        return back()->with('success', 'Função atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $role = $this->role->findOrFail($id);
        $role->delete();
        return back()->with('success', 'Função removída com sucesso!');
    }
}
