<?php

namespace App\Http\Controllers;

use App\Models\{Company, Note, Payment, Report, User};
use App\Services\PHPWord;
use Carbon\CarbonPeriod;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request, Response};
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

use function Ramsey\Uuid\v1;

class PaymentController extends Controller
{
    public function __construct(private Payment $payment, private Report $report, private Company $company, private Note $note, private PHPWord $word, private ZipArchive $zip, private User $user)
    {
        $this->middleware('permission:payment-list', ['only' => ['index','show', 'pending', 'pendingsTotal']]);
        $this->middleware('permission:payment-create', ['only' => ['create','store', 'fill']]);
        $this->middleware('permission:payment-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:payment-delete', ['only' => ['destroy']]);
        $this->middleware('permission:file-download', ['only' => ['download']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {

        $request->has('active') ? $request->active : $request['active'] = true;

        $companies = $this->company->with([
            'reports' => [
                'location'
            ],
        ])->get();

        $filters = $this->report
            ->with([
                'location', 
                'note'
            ])
            ->where("company_id", $request->company)
            ->whereHas('note', function ($query) use ($request) {
                $query->where('active', $request->active);
            })
            ->get()
            ->sortBy(function ($report) {
                return $report->location->name;
            })
            ->groupBy(function ($report) {
                return $report->location->name;
            });

        $payments = $this->payment
            ->with('report')
            ->where("report_id", $request->report)
            ->whereHas("report", function ($query) use ($request) {
                return $query->where("company_id", "=", $request->company);
            })
            ->whereHas('report.note', function ($query) use ($request) {
                $query->where('active', $request->active);
                $query->whereIn('sector_id', auth()->user()->sectors->pluck('id'));
            })
            ->orderBy("signature_date", "asc")
            ->get();


            $report = $this->report
            ->with([
                'note' => function ($query) {
                    // Adicionar filtro para garantir que as notas estejam vinculadas aos setores permitidos
                    $query->whereIn('sector_id', auth()->user()->sectors->pluck('id'));
                }
            ])
            ->whereHas('note', function ($query) {
                // Certificar-se de que o filtro seja aplicado também no whereHas
                $query->whereIn('sector_id', auth()->user()->sectors->pluck('id'));
            })
            ->whereHas('company', function ($query) use ($request) {
                return $query->where("company_id", "=", $request->company);
            })
            ->find($request->report);
        


        $unresolved = $this->payment->whereNotNull('process')
        ->where(function($query) {
            $query->where('status', '!=', 'Solucionado')
                  ->orWhereNull('status');
        })
        ->get();


        return view(
            "dashboard.payments.index",
            compact("companies", "filters", "payments", "report", "unresolved")
        );
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
        foreach ($request->payments as $payment) {
            $this->payment->create($payment);
        }
        return to_route('reports.list')->with(['success' => 'Pagamento(s) adicionado(s) com sucesso!']);
    }
    

    /**
     * Display the specified resource.
     */
    public function show($uuid): BinaryFileResponse
    {
        $payment = $this->payment->with([
            'report' => [
                'company', 
                'note'
            ]
        ])->where("uuid", $uuid)->first();
        $payment->logDownload();
        return response()
            ->download($this->word->makeWord($payment))
            ->deleteFileAfterSend(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment): JsonResponse
    {
        $this->authorize('edit', $payment);
        $payment = $payment->whereUuid($payment->uuid)->firstOrFail();
        $paymentArray = $payment->toArray();

        if (isset($paymentArray['reference']) && is_string($paymentArray['reference'])) {
            $paymentArray['reference'] = substr($paymentArray['reference'], 0, 7);
        }
        return response()->json($paymentArray);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $this->authorize('update', $payment);
        $payment = $payment->whereUuid($payment->uuid)->firstOrFail();
        $payment->update($request->all());
        return back()->with('success', 'Pagamento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        $this->authorize('edit', $payment);
        $payment = $payment->whereUuid($payment->uuid)->firstOrFail();
        $payment->delete();
        return back()->with('success', 'Pagamento removído com sucesso!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function fill(Request $request): View
    {
        $reports = $this->report->with([
            'company', 
            'location', 
            'note', 
            'file', 
            'user'
        ])->whereKey($request->reports)->get()->groupBy('company.name');
           
        $sectors = auth()->user()->getSectorsGroupedByDepartment()->flatten()->pluck('id');
        

        $managers = User::whereHas('sectors', function ($query) use ($sectors) {
            $query->whereIn('sectors.id', $sectors);
        })->get();

        return view('dashboard.payments.fill', compact('reports', 'managers'));
    }

    public function download($zipname): BinaryFileResponse
    {
        
        return response()
            ->download($zipname)
            ->deleteFileAfterSend(true);
    }

    /**
     * missing payments by reference.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function pending()
    {
        $period = CarbonPeriod::create(now()->startOfYear(), "1 month", now()->endOfMonth());

        $pendingPayments = [];

        foreach ($period as $date) {
            $reportsWithPendingPayments = $this->report->with(['location', 'note', 'company', 'payments'])
                ->whereHas('note', function ($query) {
                    $query->where('active', true);
                })
                ->whereDoesntHave('payments', function ($query) use ($date) {
                    $query->whereYear('reference', now()->year)
                        ->whereMonth('reference', $date->month);
                })
                ->whereHas('payments', function ($query) use ($date) {
                    $query->whereDate('reference', '<=', $date->endOfMonth());
                })
            ->get();
            $filteredReports = $reportsWithPendingPayments->filter(function ($report) use ($date) {
                $firstPayment = $report->payments()->orderBy('reference', 'asc')->first();
                return $firstPayment && $firstPayment->reference->month <= $date->month;
            });
            $pendingPayments[$date->translatedFormat('F/Y')] = $filteredReports;
        }

        $unresolved = $this->payment->whereNotNull('process')
        ->where(function($query) {
            $query->where('status', '!=', 'Solucionado')
                  ->orWhereNull('status');
        })
        ->whereHas('report.note', function ($query) {
            $query->whereIn('sector_id', auth()->user()->sectors->pluck('id'));
        })
        ->get();

        return view('dashboard.payments.pending', compact('pendingPayments', 'unresolved'));
    }

    public function unresolved(){
        $unresolved = $this->payment->with([
            'report' => [
                'location', 
                'company', 
                'note'
            ],
        ])->whereNotNull('process')
        ->where(function($query) {
            $query->where('status', '!=', 'Solucionado')
                  ->orWhereNull('status');
        })->whereHas('report.note', function ($query) {
            $query->whereIn('sector_id', auth()->user()->sectors->pluck('id'));
        })
        ->get();
        return view('dashboard.payments.unresolved', compact('unresolved'));
    }

    public function pendingsTotal()
    {
        $period = CarbonPeriod::create(now()->startOfYear(), "1 month", now()->endOfMonth());

        $pendingPayments = [];

        foreach ($period as $date) {
            $reportsWithPendingPayments = $this->report->with(['location', 'note', 'company', 'payments'])
                ->whereHas('note', function ($query) {
                    $query->where('active', true);
                })
                ->whereDoesntHave('payments', function ($query) use ($date) {
                    $query->whereYear('reference', now()->year)
                        ->whereMonth('reference', $date->month);
                })
                ->whereHas('payments', function ($query) use ($date) {
                    $query->whereDate('reference', '<=', $date->endOfMonth());
                })
            ->get();
            $filteredReports = $reportsWithPendingPayments->filter(function ($report) use ($date) {
                $firstPayment = $report->payments()->orderBy('reference', 'asc')->first();
                return $firstPayment && $firstPayment->reference->month <= $date->month;
            });
            $pendingPayments[$date->translatedFormat('F/Y')] = $filteredReports;
        }
        $total = array_sum(array_map(function($report) {
            return $report->count();
        }, $pendingPayments));
        return response('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-urgent"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M8 16v-4a4 4 0 0 1 8 0v4"></path><path d="M3 12h1m8 -9v1m8 8h1m-15.4 -6.4l.7 .7m12.1 -.7l-.7 .7"></path><path d="M6 16m0 1a1 1 0 0 1 1 -1h10a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-10a1 1 0 0 1 -1 -1z"></path></svg>'.$total.' pendências', 200)->header('Content-Type', 'text/html');
    }
}
