@extends('layouts.app')

@section('title','Invoice Details | '.config('app.name'))

@push('css')
@endpush

@section('content')
<?php
    //dd($invoice);
?>
    <!--    main content start-->
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="center-1">
                <div class="page order-details-page">
                    <div class="page-title">
                        <h1>Order information</h1>
                        <a href="{{ route('customer.invoice.print',$invoice->InvoiceNo) }}" target="_blank" class="button-2 print-order-button">Print</a>
{{--                        <a href="" class="button-2 pdf-invoice-button">PDF Invoice</a>--}}
                    </div>
                    <div class="page-body">
                        <div class="order-overview">
                            <div class="order-number">
                                <strong>Order #{{ $invoice->InvoiceNo }}</strong>
                            </div>
                            <ul class="order-overview-content">
                                <li class="order-date">Order Date: <strong>{{ $invoice->InvoiceDate }}</strong></li>
                                <li class="order-status">Order Status: <strong>{{ $invoice->invoiceStatus->InvStatus }}</strong></li>
                                <li class="order-total">Order Total: <strong>{{ $invoice->GrandTotal }}৳</strong></li>
                            </ul>
                        </div>
                        <div class="order-details-area">
                            <div class="billing-info-wrap" style="height: 342px;">
                                <div class="billing-info">
                                    <div class="title">
                                        <strong>Address</strong>
                                    </div>
                                    <ul class="info-list">
                                        <li class="name">
                                            Name : {{ $invoice->CustomerName }}
                                        </li>
                                        <li class="email">
                                            Email : {{ $invoice->CustomerEmail }}
                                        </li>
                                        <li class="phone">
                                            Phone: {{ $invoice->CustomerMobileNo }}
                                        </li>
                                        <li class="address1">
                                            Delivery Address : {{ $invoice->DeliveryAddress }}
                                        </li>
                                        <li class="thana">
                                            Thana Name : {{ $invoice->ThanaName }}
                                        </li>
                                        <li class="district">
                                            District Name : {{ $invoice->DistrictName }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="payment-method-info">
                                    <div class="title">
                                        <strong>Payment</strong>
                                    </div>
                                    <ul class="info-list">
                                        <li class="payment-method">
                                            <span class="label">
                                                Payment Method:
                                            </span>
                                            <span class="value">
                                                COD
                                            </span>
                                        </li>
                                        <li class="payment-method-status">
                                            <span class="label">
                                                Payment Status:
                                            </span>
                                            <span class="value">
                                                Pending
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="section products">
                            <div class="table-wrapper">
                                <table class="data-table">
                                    <colgroup>
                                        <col />
                                        <col width="1" />
                                        <col width="1" />
                                        <col width="1" />
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th class="name">Name</th>
                                            <th class="price">Price</th>
                                            <th class="quantity">Qty</th>
                                            <th class="total">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($invoice->invoiceDetail as $details)
                                        @if ($details->Quantity > 0)
                                            <tr>
                                                <td class="product">
                                                    <em><a href="{{ route('product.details', $details->product->ProductSlug) }}">{{ $details->ProductName }}</a></em>
                                                </td>
                                                <td class="unit-price">
                                                    <label class="td-title">Price: </label>
                                                    <span class="product-unit-price">{{ $details->ItemFinalPrice }}৳</span>
                                                </td>
                                                <td class="quantity">
                                                    <label class="td-title">Qty: </label>
                                                    <span class="product-quantity">{{ $details->Quantity }}</span>
                                                </td>
                                                <td class="total">
                                                    <label class="td-title">Total: </label>
                                                    <span class="product-subtotal">{{ $details->Quantity * $details->ItemFinalPrice }}৳</span>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="section options"></div>
                        <div class="section totals">
                            <div class="total-info">
                                <table class="cart-total">
                                    <tbody>
                                    <tr>
                                        <td class="cart-total-left">
                                            <label>Sub-Total:</label>
                                        </td>
                                        <td class="cart-total-right">
                                            <span>{{ $invoice->TotalAmount }}৳</span>
                                        </td>
                                    </tr>
                                    @if (!empty($invoice->DiscountAmount) && isset($invoice->DiscountAmount) && $invoice->DiscountAmount > 0)
                                        <tr>
                                            <td class="cart-total-left">
                                                <label>Discount:</label>
                                            </td>
                                            <td class="cart-total-right">
                                                <span>{{ $invoice->DiscountAmount }}৳</span>
                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td class="cart-total-left">
                                            <label>Delivery Charge:</label>
                                        </td>
                                        <td class="cart-total-right">
                                            <span>
<!--                                                 --><?php
//                                                    if ($invoice->DeliveryCharge >1) {
//                                                        echo $invoice->DeliveryCharge;
//                                                    }else{
//                                                        echo "Depends on your location.";
//                                                    }
//                                                ?>
                                                Depends on your location.
                                            </span>
                                        </td>
                                    </tr>

                                    <tr class="order-total">
                                        <td class="cart-total-left">
                                            <label>Order Total:</label>
                                        </td>
                                        <td class="cart-total-right">
                                            <span><strong>{{ $invoice->GrandTotal }}৳</strong></span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--    main content end-->

@endsection

@push('js')

@endpush

