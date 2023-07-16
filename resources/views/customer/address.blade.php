@extends('layouts.app')

@section('title','Customer Profile | '.config('app.name'))

@push('css')
    <style>
        .button-2.edit-address-button, .button-2.delete-address-button {
            border: none;
            border-radius: 3px;
            width: 40px;
            height: 40px;
            margin: 0 2px;
            background-color: #f1f1f1;
            background-position: center;
            background-repeat: no-repeat;
            padding: 5px;
        }
    </style>
@endpush

@section('content')

    <!--    main content start-->
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="page-title">
                <h1>My account - Addresses</h1>
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
                <div class="page account-page address-list-page" style="min-height: 329px;">
                    <div class="page-body">
                        <div class="address-list">
                            <div class="section address-item" style="height: 260px;">
                                <div class="title">
                                    <strong>{{ $user->CustomerFirstName }} {{ $user->CustomerLastName }}</strong>
                                </div>
                                <ul class="info">
                                    <li class="name">
                                        {{ $user->CustomerFirstName }} {{ $user->CustomerLastName }}
                                    </li>
                                    <li class="email">
                                        <label>Email:</label>
                                        {{ $user->CustomerEmail }}
                                    </li>
                                    <li class="phone">
                                        <label>Phone number:</label>
                                        {{ $user->CustomerMobileNo }}
                                    </li>
                                </ul>
                                <div class="buttons">
                                    <a href="{{ route('customer.profile') }}" class="button-2 edit-address-button" >Edit</a>
                                </div>
                            </div>
                            @if (isset($user->customerAddress))
                                @foreach($user->customerAddress as $address)
                                    <div class="section address-item" style="height: 260px;">
                                        <div class="title">
                                           Address Type :  <strong>{{ ucfirst(isset($address->addressType->AddressTypeName) ? $address->addressType->AddressTypeName : '') }}</strong>
                                        </div>
                                        <ul class="info">
                                            <li class="name">
                                                {{ $user->CustomerFirstName }} {{ $user->CustomerLastName }}
                                            </li>
                                            <li class="email">
                                                <label>Email:</label>
                                                {{ $user->CustomerEmail }}
                                            </li>
                                            <li class="phone">
                                                <label>Phone number:</label>
                                                {{ $user->CustomerMobileNo }}
                                            </li>
                                            <li class="phone">
                                                <label>District Name:</label>
                                                {{ $address->DistrictName }}
                                            </li>
                                            <li class="phone">
                                                <label>Thana Name:</label>
                                                {{ $address->ThanaName }}
                                            </li>
                                            <li class="phone">
                                                <label>Customer Address:</label>
                                                {{ $address->CustomerAddress }}
                                            </li>
                                        </ul>
                                        <div class="buttons">
                                            <a href="{{ route('customer.address.edit',$address->AddressID) }}" class="button-2 edit-address-button" >Edit</a>
                                            <a href="{{ route('customer.address.delete',$address->AddressID) }}" class="button-2 edit-address-button" >Delete</a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif


                            <div class="add-button address-item" style="height: 260px;">
                                <input type="button" class="button-1 add-address-button" onclick="location.href='{{ route('customer.address.create') }}'" value="Add new" />
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

