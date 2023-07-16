@extends('layouts.app')

@section('title','Checkout | '.config('app.name'))

@push('css')
    <style>
        .alert-dismissible{
            text-align: center;
            width: 20%;
            margin: 0 auto;
            margin-bottom: 10px;
            color: red;
        }
    </style>
@endpush

@section('content')

    <!--    main content start-->
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="center-1">
                <div class="page checkout-page">
                    <div class="page-title">
                        <h1>Checkout</h1>
                    </div>
                    @if(count($errors) > 0 )
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="p-0 m-0" style="list-style: none;">
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="page-body checkout-data">
                        <ol class="opc" id="checkout-steps">
                            <li id="opc-billing" class="tab-section allow">
                                <div class="step-title">
                                    <h2 class="title">All Information</h2>
                                </div>
                                <div id="checkout-step-billing" class="step a-item" style="">
                                    <form id="co-billing-form" action="{{ route('checkout.confirm') }}" method="post" novalidate="novalidate">
                                        {{ csrf_field() }}
                                        <div id="checkout-billing-load">
                                            <div class="checkout-data">
                                                <div class="section new-billing-address" id="billing-new-address-form" style="">
                                                    <div class="enter-address">
                                                        <div class="edit-address">
                                                            <div class="inputs">
                                                                <label for="FirstName">First name:</label>
                                                                <input type="text" id="FirstName" name="FirstName" value="{{ $customer->CustomerFirstName }}" class="valid"/>
                                                            </div>
                                                            <div class="inputs">
                                                                <label for="LastName">Last name:</label>
                                                                <input type="text" id="LastName" name="LastName" value="{{ $customer->CustomerLastName }}" />
                                                            </div>
                                                            <div class="inputs">
                                                                <label for="BillingNewAddress_Email">Email:</label>
                                                                <input type="email" id="CustomerEmail" name="CustomerEmail" value="{{ $customer->CustomerEmail }}"/>
                                                            </div>

                                                            <div class="inputs">
                                                                <label for="District">District / Area:</label>
                                                                <select id="District" name="District" required>
                                                                    @foreach($districts as $district)
                                                                        <option value="{{ $district->DistrictCode }}-{{ $district->DistrictName }}">{{ ucfirst($district->DistrictName) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="inputs">
                                                                <label for="Thana">Thana / Area:</label>
                                                                <select id="Thana" name="Thana" required>

                                                                </select>
                                                            </div>

                                                            <div class="inputs">
                                                                <label for="DeliveryAddress">Delivery Address:</label>
                                                                <input type="text" id="DeliveryAddress" name="DeliveryAddress" value="{{ isset($customer->customerAddressTwo->CustomerAddress) ? $customer->customerAddressTwo->CustomerAddress :'' }}" required />
                                                            </div>
                                                            <div class="inputs">
                                                                <label for="CustomerMobileNo">Phone number:</label>
                                                                <input type="text" id="CustomerMobileNo" name="CustomerMobileNo" value="{{ $customer->CustomerMobileNo }}" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="buttons" id="billing-buttons-container" style="opacity: 1;">
                                            <input type="submit" title="Continue" class="button-1 new-address-next-step-button" value="Continue" />
                                        </div>
                                    </form>
                                </div>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--    main content end-->

@endsection

@push('js')

    <script>
        $("#District").change(function(){
            var district = $("#District").val();
            $.ajax({
                type: "POST",
                url: "{{ url('district/wise/thana') }}",
                data: { district : district }
            }).done(function(data){
                console.log(data);
                $("#Thana").html(data);
            });
        });
    </script>

@endpush

