<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\View\View;

class NoteController extends Controller
{
    public function __construct(private Note $note)
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
            'reports' => function ($query) {
                $query->withSum('payments', 'price');
            }
        ])
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
    public function edit(string $id): JsonResponse
    {
        $note = $this->note->findOrFail($id);
        return response()->json($note);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $note = $this->note->find($id);
        $request->has('active') ? $request['active'] = true : $request['active'] = false;
        $note->update($request->all());
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
