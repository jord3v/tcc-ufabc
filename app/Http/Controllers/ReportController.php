<?php

namespace App\Http\Controllers;

use App\Models\{Company, File, Location, Note, Payment, Report};
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
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

        $locations = $this->location->where('active', true)->orderBy('name')->get();
        $notes = $this->note->where('active', true)->get();
        $files = $this->file->where('active', true)->get();
        $companies = $this->company->orderBy('name')->get();
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
    public function store(Request $request)//: RedirectResponse
    {
        $exist = $this->report->whereIn('location_id', $request->locations)->where('note_id', $request->note_id)->exists();
        if(!$exist){
            foreach ($request->locations as $location_id) {
                $data = $request->all();
                $data['location_id'] = $location_id;
                auth()->user()->reports()->create($data);
            }
            return to_route('reports.index')->with(['success' => 'Relatório(s) adicionado(s) com sucesso!']);
        }else{
            return back()->with('error', 'Já existe um relatório com a mesma nota de empenho, localização e prestador de serviço.');
        }
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
    public function edit(Report $report): JsonResponse
    {
        $this->authorize('edit', $report);
        return response()->json($report);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report): RedirectResponse
    {
        $this->authorize('update', $report);
        $report = $report->findOrFail($report->id);
        $report->update($request->all());
        return back()->with('success', 'Relatório circunstanciado atualizado com sucesso!');
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
        $request->merge([
            'start' => $request->filled('start') ? $request->input('start') : now()->startOfWeek(),
            'end' => $request->filled('end') ? $request->input('end') : now()->endOfWeek(),
        ]);

        $companies = $this->company->get();
        $payments = $this->payment->with([
            'report' => [
                'location', 
                'company', 
                'note'
            ],
        ])
        ->whereHas('report.note', function ($query) {
            // Filtrar setores com base nos setores permitidos do usuário autenticado
            $query->whereIn('sector_id', auth()->user()->sectors->pluck('id'));
        })
        ->whereHas('report.company', function ($query) use ($request) {
            $query->when($request->filled('company'), function ($query) use ($request) {
                $query->where('id', $request->company);
            });
        })
        ->when($request->start && $request->end, function ($query) use ($request) {
            $query->whereBetween('signature_date', [$request->start, $request->end]);
        })
        ->orderBy("signature_date", "desc")
        ->get()
        ->groupBy('signature_date');

        $total = $payments->sum(function($group) {
            return $group->count();
        });

        return view('dashboard.reports.list', compact('payments', 'total', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function download(Request $request): RedirectResponse
    {
        $payments = $this->payment::whereIn('uuid', $request->payments)->get();

        foreach ($payments as $key => $payment) {
            $payment->logDownload();
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
