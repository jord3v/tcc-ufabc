<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
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
        $files = $this->file->paginate(10);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        //
    }
}
