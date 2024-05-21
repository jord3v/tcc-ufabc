<?php

namespace App\Http\Controllers;
use App\Models\{Company, Location, Note, Payment, Report};
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private Company $company, private Report $report, private Note $note, private Location $location, private Payment $payment) {}
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {

        $paymentsPerMonth = $this->payment->select(DB::raw('SUM(price) as total, MONTH(signature_date) as month'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $data = array_fill(1, 12, 0);

        foreach ($paymentsPerMonth as $payment) {
            $data[$payment->month] = $payment->total;
        }

        $companies = $this->company->get();
        $reports = $this->report->get();
        $notes = $this->note->get();
        $locations = $this->location->get();

        return view('dashboard.index', compact('data', 'companies', 'reports', 'notes', 'locations'));
    }
}
