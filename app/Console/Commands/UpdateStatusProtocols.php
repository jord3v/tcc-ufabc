<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\Erp;
use Illuminate\Console\Command;

class UpdateStatusProtocols extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-status-protocols';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(Erp $erp)
    {
        parent::__construct();
        $this->erp = $erp;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $payments = Payment::with([
            'report' => [
                'location', 
                'company', 
                'note'
            ],
        ])->whereNotNull('process')
        ->where(function($query) {
            $query->where('status', '!=', 'Solucionado')
                  ->orWhereNull('status');
        })
        ->get();

        foreach ($payments as $payment) {
            $data = [
                "tipoProtocolo" => "ADM",
                "numeroProtocolo" => $payment->process,
            ];
            $string = $this->erp->post('ObterProtocolo', $data);
            preg_match('/situacao:\s*([^,]+)/', $string, $matches);
            $payment->update(['status' => $matches[1]]);
        }
    }
}
