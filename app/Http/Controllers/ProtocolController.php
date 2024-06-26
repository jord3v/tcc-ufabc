<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\Erp;
use Illuminate\Http\{RedirectResponse, Request};

class ProtocolController extends Controller
{
    public function __construct(private Payment $payment, private Erp $erp){}

    public function index(): void
    {
        
    }

    public function show($uuid): RedirectResponse
    {
        try {
            $payment = $this->payment->with([
                'report' => [
                    'location', 
                    'company', 
                    'note'
                ],
            ])->where('uuid', $uuid)
                ->whereNull('process')
                ->firstOrFail();
            $join = multiplePayment($payment);
            $description = $this->generateDescription($join, $payment);
            $protocol = $this->createProtocol($description, $payment);
            $this->updatePayments($join, $protocol);
            return back()->with('success', 'Protocolo '.$protocol.' gerado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', "Não foi possível a comunicação com o ERP");
        }
    }


    public function attachment(Request $request): RedirectResponse
    {
        $payment = $this->payment->findOrFail($request->id);
        if($payment->uuid !== $request->uuid){
            return back()->with('error', 'Não autorizado');
        }
        $base64File = base64_encode(file_get_contents($request->arquivo));
        $data = [
            "tipoProtocolo" => "ADM",
            "numeroProtocolo" => $payment->process,
            "tipoImagem" => $request->tipo,
            "nomeImagem" => $request->arquivo->getClientOriginalName(),
            "arquivo"  => $base64File,
        ];
        $protocol = $this->erp->post('SPW/API_spw/SPR/protocolo/AnexarDocumento', $data);
        return back()->with('success', $protocol);
    }

    private function generateDescription($payments, $payment): string
    {
        $prices = $payments->pluck('price')->map(fn ($price) => getPrice($price));

        return $prices->implode(" + ") . " - " .
            $payment->report->note->service . " - " .
            $payment->report->company->name . " - " .
            $payment->report->location->name . " - REF: " .
            str()->upper(reference($payment->reference));
    }

    private function createProtocol($description, $payment)
    {
        $data = [
            "TipoProc" => "ADM",
            "Nome" => $payment->report->company->commercial_name,
            "tipoRecebimento" => 3,
            "CodigoAssunto" => 555,
            "Responsavel" => auth()->user()->username,
            "CodigoCategoria"  => 0,
            "CodigoOrigem"  => 23,
            "CodigoIdentOrigem"  => 5689,
            "Discriminacao"  => $description,
            "LocalizacaoAtual"  => 23,
            "CodigoSituacao"  => 6
        ];

        return $this->erp->post('SPW/API_spw/SPR/protocolo/IncluirProtocolo', $data);
    }


    private function updatePayments($payments, $protocol): void
    {
        $payments->each(function ($payment) use ($protocol) {
            $payment->process = $protocol;
            $payment->save();
        });
    }
}
