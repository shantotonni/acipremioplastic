@extends('layouts.app')

@section('title','Track Order | '.config('app.name'))

@push('css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .card {
            z-index: 0;
            background-color: #ECEFF1;
            padding-bottom: 20px;
            /*margin-top: 90px;*/
            margin-bottom: 90px;
            border-radius: 10px
        }

        .top {
            padding-top: 40px;
            padding-left: 13% !important;
            padding-right: 13% !important
        }

        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            color: #455A64;
            padding-left: 0px;
            margin-top: 30px
        }

        #progressbar li {
            list-style-type: none;
            font-size: 13px;
            width: 33%;
            float: left;
            position: relative;
            font-weight: 400
        }

        #progressbar .step0:before {
            font-family: FontAwesome;
            content: "\f10c";
            color: #fff
        }

        #progressbar li:before {
            width: 40px;
            height: 40px;
            line-height: 45px;
            display: block;
            font-size: 20px;
            background: #C5CAE9;
            border-radius: 50%;
            margin: auto;
            padding: 0px
        }

        #progressbar li:after {
            content: '';
            width: 100%;
            height: 12px;
            background: #C5CAE9;
            position: absolute;
            left: 0;
            top: 16px;
            z-index: -1
        }

        #progressbar li:last-child:after {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            position: absolute;
            left: -50%
        }

        #progressbar li:nth-child(2):after,
        #progressbar li:nth-child(3):after {
            left: -50%
        }

        #progressbar li:first-child:after {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            position: absolute;
            left: 50%
        }

        #progressbar li:last-child:after {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px
        }

        #progressbar li:first-child:after {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px
        }

        #progressbar li.active:before,
        #progressbar li.active:after {
            background: #651FFF
        }

        #progressbar li.active:before {
            font-family: FontAwesome;
            content: "\f00c"
        }

        .icon {
            width: 60px;
            height: 60px;
            margin-right: 15px
        }

        .icon-content {
            padding-bottom: 20px
        }

        @media screen and (max-width: 992px) {
            .icon-content {
                width: 50%
            }
        }
    </style>
@endpush

@section('content')
    <!--    main content start-->
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            @if (!isset($invoice))
                <div class="center-1">
                    <div class="page contact-page">
                        <div class="page-body">
                            <form method="post" action="{{ route('order.track.post') }}" novalidate="novalidate">
                                {{ csrf_field() }}
                                <div class="fieldset">
                                    <div class="form-fields">
                                        <div class="inputs">
                                            <label for="FullName">Your Order ID:</label>
                                            <input placeholder="Enter your Order Id." class="InvoiceNo" type="text" data-val="true" id="InvoiceNo" required name="InvoiceNo" />
                                            <span class="required">*</span>
                                            <span class="field-validation-valid" data-valmsg-for="InvoiceNo" data-valmsg-replace="true"></span>
                                            @if ($errors->has('InvoiceNo'))
                                                <span class="help-block" style="color: red"><strong>{{ $errors->first('InvoiceNo') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-fields">
                                        <div class="inputs">
                                            <label for="FullName">Your Mobile Number:</label>
                                            <input placeholder="Enter your Mobile Number" class="CustomerMobileNo" type="text" data-val="true" id="CustomerMobileNo" name="CustomerMobileNo" required />
                                            <span class="required">*</span>
                                            <span class="field-validation-valid" data-valmsg-for="CustomerMobileNo" data-valmsg-replace="true"></span>
                                            @if ($errors->has('CustomerMobileNo'))
                                                <span class="help-block"><strong>{{ $errors->first('CustomerMobileNo') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="buttons">
                                    <input type="submit" class="button-1 contact-us-button" value="Submit" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
            @if (isset($invoice))
                <div class="container px-1 px-md-4 py-5 mx-auto">
                    <div class="card">
                        <div class="row d-flex justify-content-between px-3 top">
                            <div class="d-flex">
                                <h5>ORDER <span class="text-primary font-weight-bold">#{{ $invoice->InvoiceNo }}</span></h5>
                            </div>
                            <div class="d-flex  text-sm-center">
                                <p class="mb-0">Order Placed Date <span>{{ \Carbon\Carbon::parse($invoice->InvoiceDate)->diffForHumans() }}</span></p>
                            </div>
                            <div class="d-flex flex-column text-sm-right">
                                <p class="mb-0"><a href="{{ route('order.track') }}" class="btn btn-success">Back</a></p>
                            </div>
                        </div> <!-- Add class 'active' to progress -->
                        <div class="row d-flex justify-content-center">
                            <div class="col-12">
                                <ul id="progressbar" class="text-center">
                                    @if ($invoice->InvStatusID==1)
                                        <li class="active step0"></li>
                                        <li class="step0"></li>
                                        <li class="step0"></li>
                                    @elseif ($invoice->InvStatusID==2)
                                        <li class="active step0"></li>
                                        <li class="active step0"></li>
                                        <li class="step0"></li>
                                    @elseif ($invoice->InvStatusID==3)
                                        <li class="active step0"></li>
                                        <li class="active step0"></li>
                                        <li class="active step0"></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="row justify-content-between top">
                            <div class="row d-flex icon-content"> <img class="icon" src="https://i.imgur.com/9nnc9Et.png">
                                <div class="d-flex flex-column">
                                    <p class="font-weight-bold">Order<br>Received</p>
                                </div>
                            </div>
                            <div class="row d-flex icon-content"> <img class="icon" src="https://i.imgur.com/u1AzR7w.png">
                                <div class="d-flex flex-column">
                                    <p class="font-weight-bold">Order<br>Processed</p>
                                </div>
                            </div>
                            <div class="row d-flex icon-content"> <img class="icon" src="https://i.imgur.com/HdsziHP.png">
                                <div class="d-flex flex-column">
                                    <p class="font-weight-bold">Order<br>Delivered</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
    <!--    main content end-->
@endsection

@push('js')

@endpush

