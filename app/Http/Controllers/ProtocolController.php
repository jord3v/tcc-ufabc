<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\Erp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProtocolController extends Controller
{
    public function __construct(private Payment $payment, private Erp $erp){}

    public function index()
    {
        $payments = $this->payment->with(['report.location', 'report.company', 'report.note'])
            ->orderBy("signature_date", "desc")
            ->paginate(20);
        return view('dashboard.protocols.index', compact('payments'));
    }

    public function store(Request $request)
    {
        try {
            $payment = $this->payment->with(['report.location', 'report.company', 'report.note'])
                ->findOrFail($request->id);
            if ($payment->uuid !== $request->uuid) {
                return back()->with('error', 'Não autorizado');
            }
            $join = multiplePayment($payment);
            $description = $this->generateDescription($join, $payment);
            $protocol = $this->createProtocol($description, $payment);
            $this->updatePayments($join, $protocol);
            return back()->with('success', 'Protocolo '.$protocol.' gerado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', "Não foi possível a comunicação com o ERP");
        }
    }


    public function attachment(Request $request)
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

    private function generateDescription($payments, $payment)
    {
        $prices = $payments->pluck("price")->map(fn ($price) => getPrice($price));

        return $prices->implode(" + ") . " - " .
            $payment->report->note->service . " - " .
            $payment->report->company->name . " - " .
            $payment->report->location->name . " - REF: " .
            Str::upper(reference($payment->reference));
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
            "LocalizacaoAtual"  => 13,
            "CodigoSituacao"  => 6
        ];

        return $this->erp->post('SPW/API_spw/SPR/protocolo/IncluirProtocolo', $data);
    }


    private function updatePayments($payments, $protocol)
    {
        $payments->each(function ($payment) use ($protocol) {
            $payment->process = $protocol;
            $payment->save();
        });
    }
}
