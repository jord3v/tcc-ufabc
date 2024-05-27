<?php

namespace App\Http\Controllers;

use App\Models\{Company, File, Location, Note, Payment, Report};
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\View\View;
use App\Services\PHPWord;
use ZipArchive;

class ReportController extends Controller
{
    public function __construct(private Report $report, private Location $location, private Company $company, private Note $note, private File $file, private Payment $payment, private PhpWord $word, private ZipArchive $zip)
    {
        $this->middleware('permission:report-list', ['only' => ['index','show']]);
        $this->middleware('permission:report-create', ['only' => ['create','store', 'list', 'download']]);
        $this->middleware('permission:report-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:report-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $reports = $this->report
        ->with([
            'company' => [
                'user'
            ], 
            'location', 
            'note', 
            'file', 
            'payments'
        ])
        ->whereHas('note', function ($query) use ($request) {
            $query->where('year', $request->year ?? now()->format('Y'));
            $query->where('active', true);
        })
        ->get()
        ->groupBy('company.name');

        $locations = $this->location->where('active', true)->get();
        $notes = $this->note->where('active', true)->get();
        $files = $this->file->where('active', true)->get();
        $companies = $this->company->get();
        return view('dashboard.reports.index', compact('locations', 'reports', 'notes', 'files', 'companies'));
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

    /**
     * Download report
     */
    public function list(Request $request)
    {
        $payments = $this->payment->with([
            'report' => [
                'location', 
                'company', 
                'note'
            ],
        ])
        ->whereHas('report.note', function ($query) use ($request) {
            $query->where('year', $request->year ?? now()->format('Y'));
        })
        ->orderBy("signature_date", "desc")
        ->get()
        ->groupBy('signature_date');
        return view('dashboard.reports.list', compact('payments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function download(Request $request): RedirectResponse
    {
        $payments = $this->payment::whereIn('uuid', $request->payments)->get();

        foreach ($payments as $key => $payment) {
            $files[] = $this->word->makeWord($payment);
        }
        $zipname = now()->timestamp.".rar";
        $this->createZipFile($zipname, $files);
        $this->cleanUpTempFiles($files);
        return back()->with([
            'success' => 'Download realizado com sucesso!',
            'download' => $zipname
        ]);
    }

    private function createZipFile($zipname, $files): void
    {
        $this->zip->open($zipname, $this->zip::CREATE);
        foreach ($files as $file) {
            $this->zip->addFile($file, basename($file));
        }
        $this->zip->close();
    }

    private function cleanUpTempFiles($files): void
    {
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}
