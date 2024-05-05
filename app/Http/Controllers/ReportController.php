<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\File;
use App\Models\Location;
use App\Models\Note;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(private Report $report, private Location $location, private Company $company, private Note $note, private File $file)
    {
        $this->middleware('permission:report-list', ['only' => ['index','show']]);
        $this->middleware('permission:report-create', ['only' => ['create','store']]);
        $this->middleware('permission:report-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:report-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $companies = $this->company->with(['user', 'reports.location', 'reports.note','reports.file', 'reports.payments'])->get();
        $locations = $this->location->get();
        $notes = $this->note->get();
        $files = $this->file->get();
        return view('dashboard.reports.index', compact('locations', 'companies', 'notes', 'files'));
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
        $report = auth()->user()->reports()->create($request->all());
        return back()->with('success', 'Relatório adicionado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
