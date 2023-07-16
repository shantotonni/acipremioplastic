@extends('layouts.app')

@section('title','Customer Order | '.config('app.name'))

@push('css')

@endpush

@section('content')

    <!--    main content start-->
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="page-title">
                <h1>My account - Orders</h1>
            </div>
            <div class="side-2">
                <div class="select-navigation mobile">
                    @include('customer.customer_mobile_sidebar')
                </div>
                <div class="block block-account-navigation desktop">
                    <div class="title">
                        <strong>My account</strong>
                    </div>
                    <div class="listbox">
                        @include('customer.customer_sidebar')
                    </div>
                </div>
            </div>
            <div class="center-2">
                <div class="page account-page order-list-page" style="min-height: 329px;">
                    <div class="page-body">
                        @if (isset($orders) && !empty($orders) && count($orders) > 0)
                            <div class="order-list">
                                @foreach($orders as $order)
                                    <div class="section order-item" style="">
                                        <div class="title">
                                            <strong>Order Number: {{ $order->InvoiceNo }}</strong>
                                        </div>
                                        <ul class="info">
                                            <li>Order status: <span class="order-status pending">{{ $order->invoiceStatus->InvStatus }}</span></li>
                                            <li>Order Date: <span class="order-date">{{ $order->InvoiceDate }}</span></li>
                                            <li>Order Total: <span class="order-total">{{ $order->GrandTotal }}৳</span></li>
                                        </ul>
                                        <div class="buttons">
                                            <a href="{{ route('invoice.details',$order->InvoiceNo) }}" style="padding: 10px 20px!important;" class="button-2 order-details-button">Details</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else

                            <h1 style="text-align: center;color: #0c5460">Empty Order</h1>

                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--    main content end-->

@endsection

@push('js')

@endpush

