<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Payment;
use App\Models\Report;
use App\Services\PHPWord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
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
    public function index(Request $request)
    {

        $request->has('active') ? $request->active : $request['active'] = true;



        $companies = $this->company->with(["reports.location"])->get();

        $filters = $this->report
            ->with(["location", "note"])
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
            ->with(["note"])
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
    public function store(Request $request)
    {
        foreach ($request->payments as $key => $value) {
            $payment = $this->payment->create($value);
            $files[] = $this->word->makeWord($payment);
        }
        $zipname = now()->timestamp.".rar";
        $this->createZipFile($zipname, $files);
        $this->cleanUpTempFiles($files);
        return redirect()->route('reports.index')->with('success', 'Pagamento(s) adicionado(s) com sucesso!')->with('download', $zipname);
    }
    

    /**
     * Display the specified resource.
     */
    public function show($uuid)
    {
        $payment = $this->payment->with('report.company', 'report.note')->where("uuid", $uuid)->first();
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
        $reports = $this->report->with(['company', 'location', 'note', 'file', 'user'])->whereKey($request->reports)->get()->groupBy('company.name');
        return view('dashboard.payments.fill', compact('reports'));
    }

    public function download($zipname){
        
        return response()
            ->download($zipname)
            ->deleteFileAfterSend(true);
    }

    private function createZipFile($zipname, $files)
    {
        $this->zip->open($zipname, $this->zip::CREATE);
        foreach ($files as $file) {
            $this->zip->addFile($file, basename($file));
        }
        $this->zip->close();
    }

    private function cleanUpTempFiles($files)
    {
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}
