<?php

use Carbon\Carbon;

if (! function_exists('avatar')) {
    function avatar($user = null) {
        if(!$user)
            return 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6c7a91&color=ffffff'; // Exemplo: adicione '&size=200' para definir o tamanho do avatar como 200x200 pixels
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6c7a91&color=ffffff'; // Exemplo: adicione '&size=200' para definir o tamanho do avatar como 200x200 pixels
    }
}

if (! function_exists('tab_id')) {
    function tab_id($var = null) {
        return str()->slug($var, '-');
    }
}

if (!function_exists("friendly")) {
    function friendly($payment, $object, $join)
    {
        if($join->count() > 1){
            $id = 'x';
        }else{
            $id = $object->id;
        }        
        return $payment->reference->format('mY').'-'.str()->slug($object->report->company->name .'-'. $object->report->location->name.'-'.$id, '-'). ".docx";//$object->id . ".docx";
    }
}

if (!function_exists("setMonthAndYear")) {
    function setMonthAndYear($input)
    {
        return Carbon::createFromFormat('Y-m', $input)->firstOfMonth()->format('Y-m-d');
    }
}

if (!function_exists("getPrice")) {
    function getPrice($input)
    {
        $fmt = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);
        return $fmt->formatCurrency($input, "BRL");
    }
}

if (!function_exists("reference")) {
    function reference($input)
    {
        return Carbon::parse($input)->translatedFormat('F/Y'); 
    }
}

if (!function_exists("convertFloat")) {
    function convertFloat($input)
    {
        $value = preg_replace("/[^0-9,.]/", "", $input);
        $value = str_replace(',', '.', $value);
        $float = (float) $value;
        return $float;
    }
}

if (!function_exists("removeCurrency")) {
    function removeCurrency($value)
    {
        return trim(str_replace("R$", "", getPrice($value)));
    }
}

if (!function_exists("multiplePayment")) {
    function multiplePayment($payment)
    {
        return $payment
            ->where("report_id", $payment->report_id)
            ->where("reference", $payment->reference)
            ->where("signature_date", $payment->signature_date)
            ->get();
    }
}

if (!function_exists("formatPeriod")) {
    function formatPeriod($start, $end)
    {
        return $start->format('d/m/y').' até '.$end->format('d/m/y');
    }
}

if (!function_exists("setPrice")) {
    function setPrice($input)
    {
        $input = str_replace(".", "", $input);
        $input = str_replace(",", ".", $input);
        return $input;
    }
}

if (!function_exists("removeParams")) {
    function removeParams($key)
    {
        return rawurldecode(preg_replace('~(\?|&)' . $key . '=[^&]*~', '$1', url()->full()));
    }
}

if (!function_exists("replaceTag")) {
    function replaceTag($input)
    {
        $input = str_replace("-", " ", $input);
        return $input;
    }
}

