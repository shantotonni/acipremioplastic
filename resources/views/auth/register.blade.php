@extends('layouts.app')

@section('title','Registration | '.config('app.name'))

@push('css')
    <style>
        .display{
            display: none;
        }
    </style>
@endpush


@section('content')

    <!--    main content start-->
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="center-1">
                <div class="page registration-page">
                    <div class="page-title">
                        <h1>Register</h1>
                    </div>
{{--                    <div class="alert alert-danger print-error-msg" style="display:none">--}}
{{--                        <ul></ul>--}}
{{--                    </div>--}}

                    <div class="page-body otp-panel">
                        <div class="fieldset">
                            <div class="title">
                                <strong>Mobile number (11 digit) - To verify your mobile, an OTP will be sent</strong>
                            </div>
                            <div class="form-fields">
                                <div id="error-msg-div" class="message-error" style="display: none;">
                                    <ul></ul>
                                </div>

                                <div id="success-msg-div" class="message-success" style="color: green;font-size: 15px">

                                </div>

                                <div class="inputs" id="MobileNumberInput">
                                    <label>Mobile Number</label>
                                    <input type="text" name="MobileNumber" id="mobile-number" />
                                    <span id="spnPhoneStatus"></span>
                                </div>

                                <div class="inputs display" id="OtpReceivedInput">
                                    <label>OTP-- Enter the OTP that you have received via SMS</label>
                                    <input type="text" name="OTPNumber" id="otp-number" />
                                </div>

                                <div>
{{--                                    <a id="register-link" href="" class="ico-register">Click here to Register</a>--}}
{{--                                    <a id="login-link" href="" class="ico-login" style="display: none;">Click here to Login</a>--}}
                                </div>
                            </div>
                        </div>

                        <div class="buttons">
                            <input type="submit" id="otp-send-button" class="button-1 register-next-step-button" value="Send OTP" name="OtpSendButton" />
                            <input type="submit" id="otp-verify-button" class="button-1 register-next-step-button display" value="Verify Now" name="OtpVerifyButton" />
                        </div>
                    </div>


                    <div class="page-body register-panel" style="display: none">
                        <form method="post" action="" novalidate="novalidate" id="form-data">
                            <div class="fieldset">
                                <div class="title">
                                    <strong>Your Personal Details</strong>
                                </div>
                                <div class="form-fields">
                                    <div id="error-msg-registration-div" class="message-error" style="display: none;">
                                        <ul></ul>
                                    </div>

                                    <div class="inputs">
                                        <label for="FirstName">First name:</label>
                                        <input type="text" id="FirstName" name="CustomerFirstName"/>
                                        <span class="required">*</span>
                                    </div>
                                    <div class="inputs">
                                        <label for="LastName">Last name:</label>
                                        <input type="text" id="LastName" name="CustomerLastName" />
                                        <span class="required">*</span>
                                    </div>
                                    <div class="inputs">
                                        <label for="Username">Mobile:</label>
                                        <input readonly="" type="text" id="phone" name="CustomerMobileNo" />
                                        <span class="required">*</span>
                                    </div>
                                    <div class="inputs">
                                        <label for="Username">Email:</label>
                                        <input type="text" id="CustomerEmail" name="CustomerEmail" />
                                        <span class="required">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="fieldset">
                                <div class="title">
                                    <strong>Your Password</strong>
                                </div>
                                <div class="form-fields">
                                    <div class="inputs">
                                        <label for="Password">Password:</label>
                                        <input type="password" id="Password" name="password"/>
                                        <span class="required">*</span>
                                    </div>
                                    <div class="inputs">
                                        <label for="ConfirmPassword">Confirm password:</label>
                                        <input type="password" id="ConfirmPassword" name="password_confirmation"/>
                                        <span class="required">*</span>
                                    </div>
                                </div>
                            </div>

{{--                            <div class="fieldset">--}}
{{--                                <div class="form-fields">--}}
{{--                                    <div class="inputs accept-consent">--}}
{{--                                        <input id="accept-consent" type="checkbox" name="accept-consent" />--}}
{{--                                        <label for="accept-consent">I accept privacy policy</label>--}}
{{--                                        <span class="read" id="read-privacyinfo"><a href="{{ route('privacy') }}" target="_blank">(read)</a></span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="buttons">
                                <input type="submit" id="register-button" class="button-1 register-next-step-button" value="Register" name="register-button" />
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

            $('#otp-send-button').click(function(){
                var phone = $('#mobile-number').val()
                $.ajax({
                    url: '{{ route('send.otp') }}',
                    type: "POST",
                    data: {phone : phone},
                    dataType: 'JSON',
                    success: function (data) {
                        console.log(data)
                        if($.isEmptyObject(data.error)){
                            $("#success-msg-div").html(data.success);
                            $("#success-msg-div").fadeOut(5000);
                            $('.display').addClass().css('display','inline-block');
                            $('#otp-send-button').css('display','none');
                            toastr.success(data.success);
                        }else{
                            toastr.error(data.error);
                            printErrorMsg(data.error);
                        }
                    }
                });
            });

            function printErrorMsg (msg) {
                $(".message-error").find("ul").html('');
                $(".message-error").css('display','block');
                $.each( msg, function( key, value ) {
                    $(".message-error").find("ul").append('<li>'+value+'</li>');
                });
            }

            $('#otp-verify-button').click(function(){
                var phone = $('#mobile-number').val()
                var otp_number = $('#otp-number').val()
                $.ajax({
                    url: '{{ route('send.verify') }}',
                    type: "POST",
                    data: {phone : phone,otp_number : otp_number},
                    dataType: 'JSON',
                    success: function (data) {
                        if($.isEmptyObject(data.error)){
                            $('.otp-panel').css('display','none');
                            $('.register-panel').css('display','block');
                            toastr.success(data.success);
                            $('#phone').val(phone)
                        }else{
                            printErrorMsg(data.error);
                        }
                    }
                });
            });

            $('#register-button').click(function(e){
                e.preventDefault();
                var data = $('#form-data').serialize();
                $.ajax({
                    url: '{{ route('register') }}',
                    type: "POST",
                    data: data,
                    dataType: 'JSON',
                    success: function (data) {
                        console.log(data);
                        if($.isEmptyObject(data.error)){
                            toastr.success(data.success);
                            window.location = '{{ route('registration.success') }}';
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

