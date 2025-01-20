<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Sector;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\View\View;

class NoteController extends Controller
{
    public function __construct(private Note $note, private Sector $sector) 
    {
        $this->middleware('permission:note-list', ['only' => ['index','show']]);
        $this->middleware('permission:note-create', ['only' => ['create','store']]);
        $this->middleware('permission:note-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:note-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $notes = $this->note->with([
            'sector.department',
            'reports' => function ($query) {
                $query->withSum('payments', 'price');
            }
        ])
        ->orderByDesc('active')
        ->paginate(10);
        return view('dashboard.notes.index', compact('notes'));
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
        $this->note->create($request->all());
        return back()->with('success', 'Nota de empenho adicionada com sucesso!');
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
    public function edit(Note $note): JsonResponse
    {
        $this->authorize('edit', $note);
        return response()->json($note);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note): RedirectResponse
    {
        // corrigir isso daqui.
        $this->authorize('update', $note);

        $validated = $request->validate([
            'sector_name' => 'nullable|string|exists:sectors,name'
        ]);

        
        $note = $note->findOrFail($note->id);
        $request->has('active') ? $request['active'] = true : $request['active'] = false;

        if ($request->filled('sector_name')) {
            $sector = $this->sector->where('name', $request->sector_name)->first();
            $note->sector_id = $sector?->id;
        }

        $fieldsToUpdate = $request->except(['sector_name']); 
        $note->fill($fieldsToUpdate);
        $note->save(); 
        return back()->with('success', 'Nota de empenho atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        //
    }
}
