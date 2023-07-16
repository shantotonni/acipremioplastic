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
                <div class="page account-page change-password-page" style="min-height: 329px;">
                    <div class="page-body">
                        <form method="post" novalidate="novalidate" id="form-data">
                            <div class="fieldset">
                                <div id="error-msg-registration-div" class="message-error" style="display: none;">
                                    <ul></ul>
                                </div>
                                <div class="form-fields">
                                    <div class="inputs">
                                        <label for="OldPassword">Old password:</label>
                                        <input type="password" id="previous_password" name="previous_password" />
                                        <span class="required">*</span>
                                    </div>
                                    <div class="inputs">
                                        <label for="NewPassword">New password:</label>
                                        <input type="password" id="password" name="password"/>
                                        <span class="required">*</span>
                                    </div>
                                    <div class="inputs">
                                        <label for="ConfirmNewPassword">Confirm password:</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"/>
                                        <span class="required">*</span>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons">
                                <input type="submit" class="button-1 change-password-button" id="change-password" value="Change password" />
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

            $('#change-password').click(function(e){
                e.preventDefault();
                var data = $('#form-data').serialize();

                $.ajax({
                    url: '{{ route('customer.change.password.store') }}',
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

