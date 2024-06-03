<?php

namespace App\Services;

use App\Models\Payment;
use PhpOffice\PhpWord\TemplateProcessor;

class PHPWord
{
    public function __construct(private Payment $payment)
    {
    }

    public function makeWord($payment)
    {
        // Obter os dados do pagamento com os relacionamentos necessários
        $object = $this->payment
            ->with([
                'report' => [
                    'company',
                    'location',
                    'note',
                    'user'
                ],
            ])
            ->find($payment->id);

        // Carregar o template do documento
        $templateProcessor = new TemplateProcessor(
            storage_path("app/{$object->report->file->path}")
        );

        // Preencher os valores estáticos no template
        $this->fillTemplateValues($templateProcessor, $object);

        // Preencher o histórico de pagamentos no template
        $this->fillPaymentHistory($templateProcessor, $object);

        // Salvar o documento gerado
        $fileName = $this->generateFileName($payment, $object);
        $templateProcessor->saveAs($fileName);

        // Retornar o nome do arquivo gerado
        return $fileName;
    }

    private function fillTemplateValues(
        TemplateProcessor $templateProcessor,
        $object
    ) {
        // Preencher os valores estáticos no template
        $note = $object->report->note;

        $values = $this->getFormattedPaymentValue($object);
        $transformedValues = $values->map(function ($value) {
            return getPrice($value);
        });
        $formattedString = $transformedValues->implode(' + ');

        $templateProcessor->setValues([
            //nota de empenho
            "modalidade" => $note->modality,
            "processo" => $note->modality_process,
            "processo_secom" => $note->process,
            "ne" => $note->number . "/" . $note->year,
            "objeto" => $note->service,
            "inicio" => $note->start->format("d/m/Y"),
            "vigencia" => ceil($note->start->diffInMonths($note->end->subDay())),
            "fim" => $note->end->format("d/m/Y"),
            "valor_total_contrato" => getPrice($note->amount),
            "valor_mensal_contrato" => getPrice($note->monthly_payment),
            "data_assinatura" => $object->signature_date->isoFormat(
                "D [de] MMMM [de] YYYY"
            ),
            //localização
            "localidade" => $object->report->location->name,
            //prestador(a) de serviço
            "empresa" => $object->report->company->name,
            "cnpj" => $object->report->company->cnpj,
            //pagamento
            "valor_apresentado" => $formattedString,
            "vencimento" => $object->due_date->format("d/m/Y"),
            "mes_referencia" => reference($object->reference),
            //ocorrências
            "eventuais_ocorrencias" => $object->occurrences['occurrence'] ?? null,
            "eventuais_falhas" => $object->occurrences['failures'] ?? null,
            "sugestoes" => $object->occurrences['suggestions'] ?? null,
            "fatura" => $object->invoice,
            //relatório elaborado por:
            "autor" => $object->report->manager,
            "departamento" => $object->report->department,
        ]);

    }

    private function fillPaymentHistory(
        TemplateProcessor $templateProcessor,
        $object
    ) {
        // Preencher o histórico de pagamentos no template
        $payments = $this->getPaymentsHistory($object);

        $this->cloneBlockInTemplate($templateProcessor, $payments);
    }

    private function getFormattedPaymentValue($object)
    {
        // Obter os pagamentos associados ao objeto
        $join = $this->payment
            ->where("report_id", $object->report_id)
            ->where("reference", $object->reference)
            ->where("signature_date", $object->signature_date)
            ->pluck('price');

        // Formatar os valores de pagamento
        return $join;
    }

    private function getPaymentsHistory($object)
    {
        // Obter o histórico de pagamentos associado ao objeto
        $payments = $this->payment
            ->where("report_id", $object->report_id)
            ->whereDate("reference", "<=", $object->reference)
            ->whereDate("signature_date", "<=", $object->signature_date)
            ->orderBy("signature_date", "asc")
            ->get();

        $saldo_acumulado = $object->report->note->amount;
        $saldos_acumulados = [];

        // Calcular o saldo acumulado
        foreach ($payments as $payment) {
            $saldo_acumulado -= convertFloat($payment->price);
            $saldos_acumulados[] = [
                "n_nota_fiscal" => $payment->invoice,
                "n_referencia" => reference($payment->reference),
                "n_preco" => getPrice($payment->price),
                "n_vencimento" => $payment->due_date->format("d/m/Y"),
                "n_saldo" => getPrice($saldo_acumulado),
                "n_protocolo" => $payment->process
            ];
        }

        return ["Acompanhamento" => $saldos_acumulados];
    }

    private function cloneBlockInTemplate(
        TemplateProcessor $templateProcessor,
        $data
    ) {
        // Clonar o bloco de notas no template e preencher com os dados
        $replacements = [];
        $i = 0;
        foreach ($data as $name => $cert) {
            $replacements[] = [
                "name" => $name,
                "n_nota_fiscal" => '${n_nota_fiscal_' . $i . "}",
                "n_referencia" => '${n_referencia_' . $i . "}",
                "n_preco" => '${n_preco_' . $i . "}",
                "n_vencimento" => '${n_vencimento_' . $i . "}",
                "n_saldo" => '${n_saldo' . $i . "}",
                "n_protocolo" => '${n_protocolo' . $i . "}",
            ];
            $i++;
        }
        $templateProcessor->cloneBlock(
            "notas",
            count($replacements),
            true,
            false,
            $replacements
        );

        $i = 0;
        foreach ($data as $group) {
            $values = [];
            foreach ($group as $row) {
                $values[] = [
                    "n_nota_fiscal_{$i}" => $row["n_nota_fiscal"],
                    "n_referencia_{$i}" => $row["n_referencia"],
                    "n_preco_{$i}" => $row["n_preco"],
                    "n_vencimento_{$i}" => $row["n_vencimento"],
                    "n_saldo{$i}" => $row["n_saldo"],
                    "n_protocolo{$i}" => $row["n_protocolo"],
                ];
            }
            $templateProcessor->cloneRowAndSetValues(
                "n_nota_fiscal_{$i}",
                $values
            );

            $i++;
        }
    }

    private function generateFileName($payment, $object)
    {
        // Gerar o nome do arquivo com base nos dados do pagamento e do objeto
        return friendly(
            $payment,
            $object,
            $this->getFormattedPaymentValue($object)
        );
    }
}
