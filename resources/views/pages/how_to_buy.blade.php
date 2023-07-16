@extends('layouts.app')

@section('title','How to Buy | '.config('app.name'))

@push('css')

@endpush


@section('content')

    <!--    main content start-->

    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="center-1">
                <div class="page topic-page" id="ph-topic">
                    <div class="page-title" id="ph-title">
                        <h1>How To Buy</h1>
                    </div>
                    <div class="page-body">
                        <div class="section">
                            <h4>Step 1 </h4>
                            <p>You Need an Account to create an order. <b>You will land on a 2-step checkout page</b>.</p>
                            <ol>
                                <li>Click My Account Logo top right of the page.</li>
                                <li>If you have an account already, click <b>Log in</b>.
                                    <ul>
                                        <li>The system will ask for inputting your Username and password. Enter your <b>Username</b> and <b>Password</b> and login into your account.</li>
                                        <li>If you do not have an account please click Register.
                                            <ol>
                                                <li>The System will ask you your 11-digit mobile number. After Input mobile number you will click SEND OTP button. The system will send an OTP SMS to your Phone.</li>
                                                <li>Input the OTP and click on VERIFY NOW button.</li>
                                                <li>Then, the system will ask you to fill up a from which is you <b>Name, Email ID and choose and set a password</b>.</li>
                                                <li>Input your preferred password, and click on <b>REGISTER</b> button.</li>
                                                <li>After Clicking System will show your information. If the information is correct then click on the <b>SAVE</b> button.</li>
                                            </ol>
                                        </li>
                                    </ul>
                                </li>
                            </ol>
                        </div>
                        <div class="section">
                            <h4>Step 2 </h4>
                            <p>Go to products category and choose the product(s) you want to buy. If you are on the category page, click on the <b>Product Photo</b> for viewing the detailed description or, you can directly add the product to the cart by clicking on the <b>Add To Cart</b> button.</p>
                        </div>
                        <div class="section">
                            <h4>Step 3</h4>
                            <p>If you are on the details page, read the details of the product. Select your desired size (if applicable) and input quantity and then press <b>Add To Cart</b> or <b>Buy Now</b> button.</p>
                        </div>
                        <div class="section">
                            <h4>Step 4 </h4>
                            <p>Once you click ADD TO CART, then go to Cart Page by clicking Cart logo </p>
                            <ul>
                                <li>If you want to buy more items, add more items to your cart please Press the CONTINUE SHOPPING button.</li>
                                <li>If you have a coupon code, click on the Apply Discount Code and Voucher button at the top of the mini-cart, a coupon code field will appear. Apply your coupon here.</li>
                                <li>Press the APPLY button.</li>
                            </ul>
                        </div>
                        <div class="section">
                            <h4>Step 5</h4>
                            <p>Please tick Delivery Charge condition button and the click <b>CHECKOUT</b> button.</p>
                        </div>
                        <div class="section">
                            <h4>Step 6 </h4>
                            <p>Fill your available Shipping Address list and click on the <b>Continue</b> button.</p>
                        </div>
                        <div class="section">
                            <h4>Step 7 </h4>
                            <p>Next System will ask you to check your total order info. </p>
                            <ul>
                                <li>Click Your payment System Cash on delivery or Online payment. If you have chosen online payment, you will be redirected to the payment gateway where you need to input your card info for completing the payment.</li>
                                <li>Finally click on the Confirm button, you will get a thank you message.</li>
                                <li>Our customer support team will contact you for processing the order.</li>
                            </ul>
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

