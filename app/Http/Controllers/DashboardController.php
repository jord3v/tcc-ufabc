<?php

namespace App\Http\Controllers;
use App\Models\{Company, Location, Note, Payment, Report};
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
        $payments = $this->payment
            ->whereHas('report.note', function ($query) {
                $query->whereIn('sector_id', auth()->user()->sectors->pluck('id'));
            })
            ->whereBetween('reference', [now()->subMonths(11)->startOfMonth(), now()->endOfMonth()])
            ->where("status", "!=", "Cancelado")
            ->get();

        $data = [
            'months' => [],
            'sum' => []
        ];

        $period = CarbonPeriod::create(now()->subMonths(11)->startOfMonth(), '1 month', now()->endOfMonth());

        foreach ($period as $dt) {
            $paymentsInMonth = $payments->filter(function ($payment) use ($dt) {
                return Carbon::parse($payment->reference)->isSameMonth($dt);
            });

            $data['months'][] = $dt->translatedFormat('M/Y');
            $data['sum'][] = $paymentsInMonth->sum('price');
            $data['count'][] = $paymentsInMonth->count();
        }

        $companies = $this->company->get();
        $reports = $this->report->whereHas('note', function ($query) {
            $query->where('active', true);
        })
        ->get();
        
        $notes = $this->note->with([
            'reports' => function ($query) {
                $query->withSum(['payments' => function ($query) {
                    $query->where('status', '!=', 'Cancelado');
                }], 'price');
            }
        ])
        ->where('active', true)
        ->get();
        
        $locations = $this->location->get();

        return view('dashboard.index', compact('data', 'companies', 'reports', 'notes', 'locations'));
    }
}
