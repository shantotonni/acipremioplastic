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
                    <div class="page-title" id="ph-title">
                        <h1>Return & Installation Service Policy</h1>
                    </div>
                    <div class="page-body">
                        <div class="section">
                            <h4>RETURN POLICY</h4>
{{--                            <p>Please refer to the section below on Return Policy per Category.</p>--}}
                            <ol>
                                <li>Product(s) cannot be returned once received by the customer.</li>
                                <li>Customer(s) are requested to check the product carefully during delivery.</li>
                                <li>Customer(s) can return the product instantly if product is found different/defective during delivery.</li>
                                <li>If any product problem related issue arises after the delivery of the product(s), customer will notify ACI Premio Plastics.</li>
                                <li>ACI Premio Plastics service team will visit customer location and will take necessary
                                    actions to solve the problem by part(s)/product(s) replacement which one is applicable.</li>
                            </ol>
                        </div>
                        <div class="section">
                            <h4>INSTALLATION SERVICE POLICY:</h4>
                            <p>Installation service will be provided instantly during product(s) delivery or within
                                3 working days after the delivery if required by the customer(s).</p>
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

