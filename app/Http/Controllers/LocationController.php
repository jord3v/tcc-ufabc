<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct(private Location $location)
    {
        $this->middleware('permission:location-list', ['only' => ['index','show']]);
        $this->middleware('permission:location-create', ['only' => ['create','store']]);
        $this->middleware('permission:location-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:location-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $locations = $this->location->with('user')->paginate(10);
        return view('dashboard.locations.index', compact('locations'));
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
    public function store(Request $request): RedirectResponse
    {
        $location = auth()->user()->locations()->create($request->all());
        return back()->with('success', 'Localidade adicionada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $location = $this->location->with('user')->findOrFail($id);
        return response()->json($location);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $location = $this->location->find($id);
        $request->has('active') ? $request['active'] = true : $request['active'] = false;
        $location->update($request->all());
        return back()->with('success', 'Localidade atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        //
    }
}
