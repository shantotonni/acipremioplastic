@extends('layouts.app')

@section('title','Contact Us | '.config('app.name'))

@push('css')

@endpush


@section('content')

    <!--    main content start-->

    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="center-1">
                <div class="page contact-page">
                    <div class="page-title">
                        <h1>Contact Us</h1>
                    </div>
                    <div class="page-body">
                        <div class="topic-block">
                            <div class="topic-block-body">
                                <p style="text-align: center;">
                                    245, Tejgaon Industrial Area. Dhaka 1208, Bangladesh
                                </p>
                                <p style="text-align: center;">
                                    <span style="font-size: 12pt; color: #333333; font-family: georgia, palatino;">Email us at&nbsp;<a href="">support@acipremioplastics.com</a></span>
                                </p>
                            </div>
                        </div>

                        <form method="post" action="" novalidate="novalidate">
                            <div class="fieldset">
                                <div class="form-fields">
                                    <div class="inputs">
                                        <label for="FullName">Your name:</label>
                                        <input placeholder="Enter your name." class="fullname" type="text" data-val="true" data-val-required="Enter your name" id="FullName" name="FullName" value="" />
                                        <span class="required">*</span>
                                        <span class="field-validation-valid" data-valmsg-for="FullName" data-valmsg-replace="true"></span>
                                    </div>
                                    <div class="inputs">
                                        <label for="Email">Your email:</label>
                                        <input placeholder="Enter your email address." class="email" type="email" data-val="true" data-val-email="Wrong email" data-val-required="Enter email" id="Email" name="Email" value="" />
                                        <span class="required">*</span>
                                        <span class="field-validation-valid" data-valmsg-for="Email" data-valmsg-replace="true"></span>
                                    </div>
                                    <div class="inputs">
                                        <label for="Enquiry">Enquiry:</label>
                                        <textarea placeholder="Enter your enquiry." class="enquiry" data-val="true" data-val-required="Enter enquiry" id="Enquiry" name="Enquiry"></textarea>
                                        <span class="required">*</span>
                                        <span class="field-validation-valid" data-valmsg-for="Enquiry" data-valmsg-replace="true"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons">
                                <input type="submit" name="send-email" class="button-1 contact-us-button" value="Submit" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')

@endpush

