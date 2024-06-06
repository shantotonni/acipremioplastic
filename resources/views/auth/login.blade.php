@extends('layouts.app')

@section('title','Login | '.config('app.name'))

@push('css')
    <style>
        .btn-google{
            padding: 15px;
            background: #cc1b7b;
            color: white;
            width: 300px;
            margin: 0 auto;
            margin-bottom: 20px;
            font-size: 16px;
            display: block;
        }
    </style>
@endpush

@section('content')
    <!--    main content start-->
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="center-1">
                <div class="page login-page">
                    <div class="page-title">
                        <h1>Welcome, Please Sign In!</h1>
                    </div>

                    <div class="page-body">
                        <div class="customer-blocks">
                            <form action="{{ route('guest.order') }}" method="post">
                                {{ csrf_field() }}
                                <div class="new-wrapper register-block returning-wrapper fieldset">
                                    <div class="title" style="background: #cc1b7b!important;">
                                        <strong style="color: white">For Guest Order</strong>
                                    </div>
                                    <div class="form-fields">
                                        <div class="inputs">
                                            <label for="FirstName">First name:</label>
                                            <input type="text" id="CustomerFirstName" name="CustomerFirstName" required placeholder="Enter First Name" />
                                            <span class="required">*</span>
                                        </div>
                                        <div class="inputs">
                                            <label for="LastName">Last name:</label>
                                            <input type="text" id="CustomerLastName" name="CustomerLastName" required placeholder="Enter Last Name" />
                                            <span class="required">*</span>
                                        </div>
                                        <div class="inputs">
                                            <label for="LastName">Date Of Birth:</label>
                                            <input type="date" id="DateOfBirth" name="DateOfBirth" style="width: 100%;padding: 10px" required />
                                            <span class="required">*</span>
                                        </div>
                                        <?php
                                        use Illuminate\Support\Facades\DB;
                                        $districts = DB::select(DB::raw('SELECT * FROM vDistrict'));
                                        ?>
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
                                            <input type="text" id="DeliveryAddress" name="DeliveryAddress" value="" required />
                                        </div>
                                        <div class="inputs">
                                            <label for="CustomerMobileNo">Phone number:</label>
                                            <input type="text" id="CustomerMobileNo" name="CustomerMobileNo" value="" required />
                                        </div>
                                    </div>
                                    <div class="buttons">
                                        <input type="submit" class="button-1 register-button" value="Order" />
                                    </div>
                                </div>
                            </form>
                            <div class="returning-wrapper fieldset">
                                <form method="post" action="{{ route('login') }}">
                                    {{ csrf_field() }}
                                    <div class="title">
                                        <strong>Returning Customer</strong>
                                    </div>

                                    <div class="form-fields">
                                        <div class="inputs">
                                            <label for="Username">Username:</label>
                                            <input class="username" autofocus="autofocus" placeholder="Mobile Number" type="text" id="CustomerMobileNo" name="CustomerMobileNo" />
                                            @error('CustomerMobileNo')
                                                <span class="invalid-feedback" role="alert">
                                                     <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="inputs">
                                            <label for="Password">Password:</label>
                                            <input class="password" placeholder="Password" type="password" id="password" name="password" />
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                     <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="inputs reversed">
                                            <span class="forgot-password">
                                                <a href="{{ route('password.request') }}">Forgot password?</a>
                                            </span>
                                            <label for="RememberMe"><a href="{{ route('register') }}" style="font-weight: bold">Create Account</a></label>
                                        </div>
                                    </div>
                                    <div class="buttons">
                                        <input class="button-1 login-button" type="submit" value="Log in" />
                                    </div>
                                    or
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a class="btn btn-lg btn-google btn-block text-uppercase btn-outline" href="{{ url('/login/google/redirect') }}">
                                                <img style="height: 17px" src="{{ asset('assets/images/google_icon.png') }}"> Signup Using Google
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="topic-block">
                            <div class="topic-block-title">
                                <h2>About login / registration</h2>
                            </div>
                            <div class="topic-block-body">
                                <p>Put your login / registration information here. You can edit this in the admin site.</p>
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

