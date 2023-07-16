<!DOCTYPE html>
<html lang="en">
<head>
    <title>Invoice</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap.min.css') }}">

    <style type="text/css" media="screen">
        * {
            font-family: "DejaVu Sans";
        }
        html {
            margin: 0;
        }
        body {
            font-size: 10px;
            margin: 36pt;
        }
        body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
            line-height: 1.1;
        }
        .party-header {
            font-size: 1.5rem;
            font-weight: 400;
        }
        .total-amount {
            font-size: 12px;
            font-weight: 700;
        }
    </style>
</head>

<body>
{{-- Header --}}

    <img src="{{ asset('assets/images/logo/logoZ.png') }}" alt="logo" height="100">

<table class="table mt-5">
    <tbody>
    <tr>
        <td class="border-0 pl-0" width="70%">
            <h4 class="text-uppercase">
                <strong>RECEIPT</strong>
            </h4>
        </td>
        <td class="border-0 pl-0">
            <p>Serial No <strong>{{ $invoice->InvoiceNo }}</strong></p>
            <p>Invoice Date: <strong>{{ $invoice->InvoiceDate }}</strong></p>
            <p>Invoice Status: <strong>{{ $invoice->invoiceStatus->InvStatus }}</strong></p>
        </td>
    </tr>
    </tbody>
</table>

{{-- Seller - Buyer --}}
<table class="table">
    <thead>
    <tr>
        <th class="border-0 pl-0 party-header" width="48.5%">
            Seller
        </th>
        <th class="border-0" width="3%"></th>
        <th class="border-0 pl-0 party-header">
            Buyer
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="px-0">
            <p class="seller-name">
                <strong>ACIEbazar</strong>
            </p>

            <p class="seller-address">
               Address: 245, Tejgaon Industrial Area. Dhaka 1208, Bangladesh
            </p>

            <p class="seller-phone">
                Phone: 09606 666 678
            </p>
            <p class="seller-phone">
                Payment Method: Cash On Delivery
            </p>
        </td>
        <td class="border-0"></td>
        <td class="px-0">
            <p class="buyer-name">
                <strong>{{ $invoice->CustomerName }}</strong>
            </p>
            <p class="buyer-address">
                Address: {{ $invoice->DeliveryAddress }}, {{ $invoice->ThanaName }}, {{ $invoice->DistrictName }}
            </p>
            <p class="buyer-phone">
                Phone: {{ $invoice->CustomerMobileNo }}
            </p>
            <p class="buyer-custom-field">
                Order Number: {{ $invoice->InvoiceNo }}
            </p>
            <p class="seller-phone">
                Payment Method: Cash On Delivery
            </p>
        </td>
    </tr>
    </tbody>
</table>

{{-- Table --}}
<table class="table">
    <thead>
    <tr>
        <th scope="col" class="border-0 pl-0">Name</th>
        <th scope="col" class="text-center border-0">Price</th>
        <th scope="col" class="text-center border-0">Quantity</th>
        <th scope="col" class="text-right border-0">Total</th>
    </tr>
    </thead>
    <tbody>

    @foreach($invoice->invoiceDetail as $item)
        <tr>
            <td class="pl-0">{{ $item->ProductName }}</td>
            <td class="text-center">{{ $item->ItemFinalPrice }} TK</td>
            <td class="text-center">{{ $item->Quantity }}</td>
            <td class="text-right">{{ $item->Quantity * $item->ItemFinalPrice }} TK</td>
        </tr>
    @endforeach
    {{-- Summary --}}
    @if(!empty($invoice->DiscountAmount) && isset($invoice->DiscountAmount) && $invoice->DiscountAmount > 0)
        <tr>
            <td colspan="2" class="border-0"></td>
            <td class="text-right pl-0">Discount({{ $invoice->coupon->Offer }}%)</td>
            <td class="text-right pr-0">
                {{ $invoice->DiscountAmount }} TK
            </td>
        </tr>
    @endif

    <tr>
        <td colspan="2" class="border-0"></td>
        <td class="text-right pl-0">Shipping</td>
        <td class="text-right pr-0">
            0 TK
        </td>
    </tr>
    <tr>
        <td colspan="2" class="border-0"></td>
        <td class="text-right pl-0">Total Amount</td>
        <td class="text-right pr-0 total-amount">
            {{ $invoice->GrandTotal }} TK
        </td>
    </tr>
    </tbody>
</table>

{{--@if($invoice->notes)--}}
{{--    <p>--}}
{{--        {{ trans('invoices::invoice.notes') }}: {!! $invoice->notes !!}--}}
{{--    </p>--}}
{{--@endif--}}

{{--<p>--}}
{{--    {{ trans('invoices::invoice.amount_in_words') }}: 12--}}
{{--</p>--}}
{{--<p>--}}
{{--    {{ trans('invoices::invoice.pay_until') }}: 12--}}
{{--</p>--}}

</body>
</html>
