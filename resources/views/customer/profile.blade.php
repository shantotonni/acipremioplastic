@extends('layouts.app')

@section('title','Customer Profile | '.config('app.name'))

@push('css')

@endpush

@section('content')

    <!--    main content start-->
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="page-title">
                <h1>My account - Customer info</h1>
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
                <div class="page account-page customer-info-page" style="min-height: 329px;">
                    <div class="page-body">
                        <form method="post" id="form-data">
                            {{ csrf_field() }}
                            <div class="fieldset">
                                <div class="title">
                                    <strong>Your Personal Details</strong>
                                </div>
                                <input type="hidden" value="{{ $customer->CustomerID }}" name="CustomerID">
                                <div class="form-fields">
                                    <div id="error-msg-registration-div" class="message-error" style="display: none;">
                                        <ul></ul>
                                    </div>
                                    <div class="inputs">
                                        <label for="FirstName">First name:</label>
                                        <input type="text" id="CustomerFirstName" name="CustomerFirstName" value="{{ $customer->CustomerFirstName }}" />
                                        <span class="required">*</span>
                                    </div>
                                    <div class="inputs">
                                        <label for="LastName">Last name:</label>
                                        <input type="text" id="CustomerLastName" name="CustomerLastName" value="{{ $customer->CustomerLastName }}" />
                                        <span class="required">*</span>
                                    </div>
                                    <div class="inputs">
                                        <label for="Email">Email:</label>
                                        <input type="email" id="CustomerEmail" name="CustomerEmail" value="{{ $customer->CustomerEmail }}" />
                                        <span class="required">*</span>
                                    </div>

                                    <div class="inputs">
                                        <label for="Username">Mobile:</label>
                                        <input type="text" id="CustomerMobileNo" name="CustomerMobileNo" value="{{ $customer->CustomerMobileNo }}" />
                                    </div>
                                </div>
                            </div>

{{--                            <div class="fieldset">--}}
{{--                                <div class="title">--}}
{{--                                    <strong>Preferences</strong>--}}
{{--                                </div>--}}
{{--                                <div class="form-fields">--}}
{{--                                    <div class="inputs">--}}
{{--                                        <label for="Signature">Signature:</label>--}}
{{--                                        <textarea class="account-signature-text" id="Signature" name="Signature"></textarea>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="buttons">
                                <input type="submit" id="save-info-button" value="Save" name="save-info-button" class="button-1 save-customer-info-button" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--    main content end-->

@endsection

@push('js')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function(){

            $('#save-info-button').click(function(e){
                e.preventDefault();
                var data = $('#form-data').serialize();

                $.ajax({
                    url: '{{ route('customer.profile.update') }}',
                    type: "POST",
                    data: data,
                    dataType: 'JSON',
                    success: function (data) {
                        console.log(data);
                        if($.isEmptyObject(data.error)){
                            toastr.success(data.success);
                        }else{
                            printErrorMsgForRegistration(data.error);
                        }
                    }
                });
            });

            function printErrorMsgForRegistration (msg) {
                $("#error-msg-registration-div").find("ul").html('');
                $("#error-msg-registration-div").css('display','block');
                $.each( msg, function( key, value ) {
                    $("#error-msg-registration-div").find("ul").append('<li>'+value+'</li>');
                });
            }

        });
    </script>
@endpush

