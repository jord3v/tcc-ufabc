<?php

namespace App\Http\Controllers;

use App\Models\{Company, Payment, Report};
use App\Services\PHPWord;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request, Response};
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;


class PaymentController extends Controller
{
    public function __construct(private Payment $payment, private Report $report, private Company $company, private PHPWord $word, private ZipArchive $zip)
    {
        $this->middleware('permission:payment-list', ['only' => ['index','show']]);
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
            ->where("report_id", $request->report)
            ->whereHas("report", function ($query) use ($request) {
                return $query->where("company_id", "=", $request->company);
            })
            ->whereHas('report.note', function ($query) use ($request) {
                $query->where('active', $request->active);
            })
            ->orderBy("signature_date", "asc")
            ->get();

        $report = $this->report
            ->with('note')
            ->whereHas("company", function ($query) use ($request) {
                return $query->where("company_id", "=", $request->company);
            })
            ->find($request->report);

        if ($request->company && !$report) {
            
        }

        return view(
            "dashboard.payments.index",
            compact("companies", "filters", "payments", "report")
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
        return response()
            ->download($this->word->makeWord($payment))
            ->deleteFileAfterSend(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): JsonResponse
    {
        $payment = $this->payment->whereUuid($id)->firstOrFail();
        $paymentArray = $payment->toArray();

        if (isset($paymentArray['reference']) && is_string($paymentArray['reference'])) {
            $paymentArray['reference'] = substr($paymentArray['reference'], 0, 7);
        }
        return response()->json($paymentArray);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $payment = $this->payment->whereUuid($id)->firstOrFail();
        $payment->update($request->all());
        return back()->with('success', 'Pagamento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $payment = $this->payment->whereUuid($id)->firstOrFail();
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
        return view('dashboard.payments.fill', compact('reports'));
    }

    public function download($zipname): BinaryFileResponse
    {
        
        return response()
            ->download($zipname)
            ->deleteFileAfterSend(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function last(Request $request)
    {
        $query = $this->payment->where('report_id', $request->id)->get()->groupBy('invoice')->keys();
        if($query->count() == 1) 
            return response()->json(['last_invoice' => $query->first()]);
        return response()->json(['error' => 'Ocorreu um erro interno no servidor'], 500);
    }
}
