<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FileController extends Controller
{
    public function __construct(private File $file)
    {
        $this->middleware('permission:file-list', ['only' => ['index','show']]);
        $this->middleware('permission:file-create', ['only' => ['create','store']]);
        $this->middleware('permission:file-edit', ['only' => ['edit','update']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $files = $this->file->with('user')->paginate(10);
        return view('dashboard.files.index', compact('files'));
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
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        
        if (Storage::exists('public/files/' . $originalName)) {
            return back()->with('error', 'Um arquivo com o mesmo nome já existe.');
        }
        $path = $file->storeAs('public/files', $originalName);
        $file = auth()->user()->files()->create([
            'filename' => $originalName,
            'path' => $path,
        ]);
        return back()->with('success', 'Template adicionado com sucesso!');
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
        $file = $this->file->findOrFail($id);
        return response()->json($file);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $file = $this->file->find($id);
        $request->has('active') ? $request['active'] = true : $request['active'] = false;
        $file->update($request->all());
        return back()->with('success', 'Template atualizado com sucesso!');
    }
}
