@extends('layouts.app')

@section('title','Contact Us | '.config('app.name'))

@push('css')

@endpush


@section('content')

    <!--    main content start-->

    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="center-1">
                <div class="page topic-page" id="ph-topic">
{{--                    <div class="about-image">--}}
{{--                        <img src="{{asset('images/about.jpg')}}" alt="">--}}
{{--                    </div>--}}
                    <div class="page-title" id="ph-title">
                        <h1>Delivery Policy</h1>
                    </div>
                    <div class="page-body">
                        <div class="section">
                            <h4>Delivery Charges:</h4>
                            <p>We are charging Maximum 200.00 taka and Minimum 100.00 taka for all over Bangladesh.</p>
                        </div>
                        <div class="section">
                            <h4>Products Dropping/Picking Policy:</h4>
                            <p>We don’t drop or pick any product to customer doorstep. We deliver products only in front of customer house where our vehicle can go easily. We prefer minimum 12 feet bitumen concrete road.</p>
                        </div>
                        <div class="section">
                            <h4>Delivery Time & Duration:</h4>
                            <p>For regular items our deliver time 4 to 7 working days & for Furniture, Toys and Households items. If any natural disaster or accident happen product delivery time might be varied.</p>
                        </div>
                        <div class="section">
                            <h4>Auto Canceled Delivery:</h4>
                            <p>If customer mobile number will wrong or customer do not pick up the phone, the order will be cancelled and customer will need to place a new order.</p>
                        </div>
                        <div class="section">
                            <h4>Instant Return:</h4>
                            <p>
                                After delivery to the customer's house if the customer does not want to pick up the product even though the product is not defective, (change of mind) then double delivery charges will be charged for delivery and return.
                            </p>
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

