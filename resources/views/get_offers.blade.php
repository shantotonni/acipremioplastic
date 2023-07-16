
<html>
<head>
    <title>Premio Spin the Wheel</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--        css-->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" type="text/css" />

    <style>
        .display{
            display: none;
        }
        .otp-panel{
            display: none;
        }

    </style>

    <!--        js-->
    <script type="text/javascript" src="{{ asset('js/Winwheel.js') }}"></script>
    <script src="{{ asset('js/TweenMax.min.js') }}"></script>
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
</head>
<body>
<div>
    <a href="{{ route('home') }}" class="btn btn-default">Back</a>
</div>
<div align="center">

    <div class="header" style="margin-top: 20px">
        <img src="{{ asset('images/play_and_win.png') }}"/>
        <br>
        <span style="color:#FED900; font-size:18px;padding-top: 20px;display: block">Welcome to ACI Premio plastics. Lets play the game</span>
    </div>

    <div class="wheal">
        <img src="{{ asset('images/arrow.png') }}" height="65" alt="">
        <div width="438" height="582" class="the_wheel" align="center">
            <canvas id="canvas" width="290" height="290">
                <p style="{color: white}" align="center">Sorry, your browser doesn't support canvas. Please try another.</p>
            </canvas>
        </div>
    </div>
    <?php
        $user = \Illuminate\Support\Facades\Auth::user();
    ?>
    <div align="center" class="action">
        <input type="hidden" id="count" value="1">
        <img height="46px;" id="spin_button" src="{{ asset('images/spin.png') }}" alt="Spin" onClick="startSpin();" />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @if($user)
            <button type="button" id="claim_button" onClick="startClaim();" class=""></button>
        @else
        <button type="button" id="claim_button" class="" data-toggle="modal" data-target="#myModal"></button>
        @endif
    </div>

    <div>
        <div id="bottom_left_div" style="width:10%" class="pull-left" ><img class="img-responsive" src="{{ asset('images/Asset7.png') }}"/></div>
        <div id="bottom_right_div" style="width:10%" class="pull-right"><img class="img-responsive" src="{{ asset('images/Asset10.png') }}"/></div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="page-body login-panel">
            <div id="error-msg-div" class="message-error" style="display: none;">
                <ul></ul>
            </div>
            <div id="success-msg-div" class="message-success" style="color: green;font-size: 15px"></div>

            <form method="post" id="login-form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">If You Claim your offer Please Login</h4>
                    </div>
                    <input name="offer" type="hidden" id="offer" class="form-control"/>
                    <input name="offer_name" type="hidden" id="offer_name"/>
                    <input name="couponCode" type="hidden" id="couponCode"/>
                    <div class="modal-body">
                        <div class="form-group">
                            <input name="CustomerMobileNo" id="CustomerMobileNo" class="form-control CustomerMobileNo" placeholder="Mobile Number" />
                        </div>

                        <div class="form-group">
                            <input class="form-control password" placeholder="Password" type="password" id="password" name="password" />
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div style="float: left">
                            If you have no account pleas <a href="#" onClick="registrationPanel()" class="registration_panel"> Registration</a>
                        </div>
                        <button id="otp-send-button" onClick="loginUser()" type="button" class="btn btn-success">Login</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal content-->
        <div class="page-body otp-panel">
            <div id="error-msg-div" class="message-error" style="display: none;">
                <ul></ul>
            </div>
            <div id="success-msg-div" class="message-success" style="color: green;font-size: 15px"></div>

            <form action="{{ route('offer.claim') }}" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Claim your offer</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input name="phone" id="phone" class="form-control phone" placeholder="Enter 11 digit phone number" />
                        </div>
                        <div class="form-group">
                            <input name="otp" id="otp" class="form-control otp" placeholder="Enter Otp For Verify" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="otp-send-button" onClick="sendOtp()" type="button" class="btn btn-success">Send Otp</button>
                        <button id="otp-verify-button" onClick="verifyOtp()" type="button" class="btn btn-success register-next-step-button display">Verify</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="page-body register-panel" style="display: none">
            <form method="post" id="form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Registration</h4>
                    </div>

                    <input name="offer" type="hidden" id="offer" class="form-control"/>
                    <input name="offer_name" type="hidden" id="offer_name"/>
                    <input name="couponCode" type="hidden" id="couponCode"/>

                    <div id="error-msg-registration-div" class="message-error" style="display: none;">
                        <ul></ul>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="CustomerFirstName" id="FirstName" class="form-control" placeholder="Enter First Name" />
                        </div>

                        <div class="form-group">
                            <input type="text" name="CustomerLastName" id="LastName" class="form-control" placeholder="Enter Last Name" />
                        </div>

                        <div class="form-group">
                            <input readonly name="CustomerMobileNo" id="CustomerMobileNo" class="form-control" placeholder="Enter 11 digit phone number" />
                        </div>

                        <div class="form-group">
                            <input name="CustomerEmail" id="CustomerEmail" class="form-control" placeholder="Enter Email" />
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" id="Password" class="form-control" placeholder="Enter Password" />
                        </div>

                        <div class="form-group">
                            <input type="password" name="password_confirmation" id="ConfirmPassword" class="form-control" placeholder="Enter Confirm Password" />
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button id="register-button" onClick="register()" type="button" class="btn btn-success button-1 register-next-step-button">Register</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<input type="hidden" name="spin_segment" id="spin_segment" value="{{json_encode($pages_array)}}">

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var segments = $("#spin_segment").val();
    segments =  JSON.parse(segments);

    $('.otp').hide();
    document.getElementById('claim_button').disabled=true;
    document.getElementById('claim_button').className='disable_btn';

    // Create new wheel object specifying the parameters at creation time.
    let theWheel = new Winwheel({
        'numSegments'   : segments.length,   // Specify number of segments.
        'lineWidth'     : .5,
        'outerRadius'   : 125,  // Set radius to so wheel fits the background.
        'innerRadius'   : 5,  // Set inner radius to make wheel hollow.
        'textFontSize'  : 16,   // Set font size accordingly.
        'textMargin'    : 0,    // Take out default margin.
        //'responsive'   : true,  // This wheel is responsive!
        'segments'      :  segments,
        'animation' :           // Define spin to stop animation.
            {
                'type'     : 'spinToStop',
                'duration' : 5,
                'spins'    : 8,
                'callbackFinished' : alertPrize
            }
    });

    // Vars used by the code in this page to do power controls.
    let wheelPower    = 3;
    let wheelSpinning = false;

    // -------------------------------------------------------
    // Function to handle the onClick on the power buttons.
    // -------------------------------------------------------
    function powerSelected(powerLevel)
    {
        // Ensure that power can't be changed while wheel is spinning.
        if (wheelSpinning == false) {

            wheelPower = powerLevel;
        }
    }

    // -------------------------------------------------------
    // Click handler for spin button.
    // -------------------------------------------------------

    function startSpin()
    {
        document.getElementById('claim_button').disabled=true;
        document.getElementById('claim_button').className='disable_btn';
        resetWheel();
        // Ensure that spinning can't be clicked again while already running.
        if (wheelSpinning == false) {
            // Based on the power level selected adjust the number of spins for the wheel, the more times is has
            // to rotate with the duration of the animation the quicker the wheel spins.
            if (wheelPower == 1) {
                theWheel.animation.spins = 3;
            } else if (wheelPower == 2) {
                theWheel.animation.spins = 8;
            } else if (wheelPower == 3) {
                theWheel.animation.spins = 15;
            }
            // Begin the spin animation by calling startAnimation on the wheel object.
            theWheel.startAnimation();
            // Set to true so that power can't be changed and spin button re-enabled during
            // the current animation. The user will have to reset before spinning again.
            wheelSpinning = true;
        }
    }

    function registrationPanel(){

        $(".login-panel").css('display','none');
        $(".otp-panel").css('display','block');
    }

    function loginUser(){
        var data = $('#login-form-data').serialize();
        $.ajax({
            url: '{{ route('login.with.offer') }}',
            type: "POST",
            data: data,
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                var id = data.CouponID;
                if($.isEmptyObject(data.error)){
                    toastr.success(data.success);
                    window.location = "{{ route('product.with.offer', '__id__') }}".replace('__id__', id);
                }else{
                    printErrorMsgForRegistration(data.error);
                }
            }
        });

    }

    function resetWheel()
    {
        theWheel.stopAnimation(false);  // Stop the animation, false as param so does not call callback function.
        theWheel.rotationAngle = 0;     // Re-set the wheel angle to 0 degrees.
        theWheel.draw();                // Call draw to render changes to the wheel.
        wheelSpinning = false;          // Reset to false to power buttons and spin can be clicked again.
    }

    function alertPrize(indicatedSegment){
        //alert(indicatedSegment.strokeStyle);
        var offer = 0;
        var string = indicatedSegment.text;
        var couponCode = indicatedSegment.couponCode;
        var number = string.match(/[0-9]+/g)
        if (number == null){
            startSpin();
        }else
            alert("You have won " + indicatedSegment.text);

        $('#offer').val(number);
        $('#offer_name').val(string);
        $('#couponCode').val(couponCode);

        document.getElementById('claim_button').disabled=false;
        document.getElementById('claim_button').className='';

    }

    function sendOtp(){
        var phone = document.getElementById('phone').value;

        $.ajax({
            url: '{{ route('send.otp') }}',
            type: "POST",
            data: {phone : phone},
            dataType: 'JSON',
            success: function (data) {
                if($.isEmptyObject(data.error)){
                    $("#success-msg-div").html(data.success);
                    $("#success-msg-div").fadeOut(5000);
                    $('.display').addClass().css('display','inline-block');
                    $('#otp-send-button').css('display','none');
                    $('.otp').show();
                    toastr.success(data.success);
                }else{
                    toastr.error(data.error);
                    printErrorMsg(data.error);
                }
            }
        });
    }

    function startClaim(){

        var offer = $('#offer').val();
        var offer_name = $('#offer_name').val();
        var couponCode = $('#couponCode').val();
        alert('Are You Sure?');
        $.ajax({
            url: '{{ route('start.claim') }}',
            type: "POST",
            data: {offer : offer,offer_name : offer_name,couponCode:couponCode},
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                var id = data.CouponID;
                if($.isEmptyObject(data.error)){
                    toastr.success(data.success);
                    window.location = "{{ route('product.with.offer', '__id__') }}".replace('__id__', id);
                }else{
                    printErrorMsgForRegistration(data.error);
                }
            }
        });

    }

    function printErrorMsg (msg) {
        $(".message-error").find("ul").html('');
        $(".message-error").css('display','block');
        $.each( msg, function( key, value ) {
            $(".message-error").find("ul").append('<li>'+value+'</li>');
        });
    }

    function verifyOtp(){
        var phone = document.getElementById('phone').value;
        var otp = document.getElementById('otp').value;

        $.ajax({
            url: '{{ route('send.verify') }}',
            type: "POST",
            data: {phone : phone,otp_number : otp},
            dataType: 'JSON',
            success: function (data) {
                if($.isEmptyObject(data.error)){
                    $('.otp-panel').css('display','none');
                    $('.register-panel').css('display','block');
                    $('#CustomerMobileNo').val(phone)
                    toastr.success(data.success);
                }else{
                    printErrorMsg(data.error);
                }
            }
        });
    }

    function register(){
        var data = $('#form-data').serialize();
        $.ajax({
            url: '{{ route('register.with.offer') }}',
            type: "POST",
            data: data,
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                var id = data.CouponID;
                if($.isEmptyObject(data.error)){
                    toastr.success(data.success);
                    window.location = "{{ route('product.with.offer', '__id__') }}".replace('__id__', id);
                }else{
                    printErrorMsgForRegistration(data.error);
                }
            }
        });
    }

    function printErrorMsgForRegistration (msg) {
        $("#error-msg-registration-div").find("ul").html('');
        $("#error-msg-registration-div").css('display','block');
        $.each( msg, function( key, value ) {
            $("#error-msg-registration-div").find("ul").append('<li>'+value+'</li>');
        });
    }

</script>
</body>
</html>
