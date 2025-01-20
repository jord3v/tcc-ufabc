<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\{JsonResponse,RedirectResponse};
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private User $user, private Role $role)
    {
        $this->middleware('permission:user-list', ['only' => ['index','show']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = $this->user->with(['roles', 'sectors'])->paginate(10);
        $roles = $this->role->get();
        $departments = Department::with('sectors')->get();
        return view('dashboard.users.index', compact('users', 'roles', 'departments'));
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
    public function store(UserRequest $request): RedirectResponse
    {
        $user = $this->user->create($request->all());
        $user->roles()->sync($request->roles);
        $user->sectors()->sync($request->sectors);
        return back()->with('success', 'Usuário adicionado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): JsonResponse
    {
        $user = $this->user->with(['roles', 'sectors'])->findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id): RedirectResponse
    {
        $user = $this->user->findOrFail($id);
        $input = $request->all();
        if (empty($input['password'])) {unset($input['password']);}
        $user->update($input);
        $user->roles()->sync($request->roles);
        $user->sectors()->sync($request->sectors);
        return back()->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $user = $this->user->findOrFail($id);
        $user->delete();
        return back()->with('success', 'Usuário removído com sucesso!');
    }

    public function updateProfile(UserRequest $request)
    {

        $user = auth()->user();
        $input = $request->all();
        if (empty($input['password'])) {unset($input['password']);}
        $user->update($input);
        return back()->with('success', 'Usuário atualizado com sucesso!');
    }
}
