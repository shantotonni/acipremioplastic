@extends('layouts.app')

@section('title','Customer Address Create | '.config('app.name'))

@push('css')

@endpush

@section('content')

    <!--    main content start-->
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="page-title">
                <h1>My account - Address Create</h1>
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
                <form method="post" action="{{ route('customer.address.store') }}" novalidate="novalidate">
                    {{ csrf_field() }}
                    <div class="page account-page address-edit-page" style="min-height: 329px;">
                        <div class="page-body">
                            <input type="hidden" data-val="true" data-val-required="The Id field is required." id="Address_Id" name="Address.Id" value="0" />
                            <div class="edit-address">
                                <div class="inputs">
                                    <label for="Address_FirstName">Customer Address:</label>
                                    <input type="text" name="CustomerAddress" required />
                                </div>

                                <div class="inputs">
                                    <label for="Address_ThanaId">Address Type:</label>
                                    <select name="AddressTypeID" class="valid" required>
                                        <option>Select Address Type</option>
                                        @foreach($address_types as $address_type)
                                        <option value="{{ $address_type->AddressTypeID }}">{{ ucfirst($address_type->AddressTypeName) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="inputs">
                                    <label for="Address_ThanaId">District:</label>
                                    <select name="District" class="valid" required>
                                        <option>Select District</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->DistrictCode }}-{{ $district->DistrictName }}">{{ ucfirst($district->DistrictName) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="inputs">
                                    <label for="Address_ThanaId">Thana / Area:</label>
                                    <select name="Thana" class="valid" required>
                                        <option>Select Thana</option>
                                        @foreach($thanas as $thana)
                                            <option value="{{ $thana->UpazillaCode }}-{{ $thana->UpazillaName }}">{{ ucfirst($thana->UpazillaName) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="buttons">
                                <input type="submit" class="button-1 save-address-button" value="Save" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <!--    main content end-->

@endsection

@push('js')

@endpush

