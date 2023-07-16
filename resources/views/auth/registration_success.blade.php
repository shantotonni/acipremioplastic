@extends('layouts.app')

@section('title','Registration Success | '.config('app.name'))

@push('css')

@endpush

@section('content')

    <!--    main content start-->
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="center-1">
                <div class="page registration-result-page">
                    <div class="page-title">
                        <h1>Register</h1>
                    </div>
                    <div class="page-body">
                        <div class="result">
                            Your registration completed
                        </div>
                        <div class="buttons">
                            <input type="submit" onclick="setLocation('{{ route('home') }}')" name="register-continue" class="button-1 register-continue-button" value="Continue" />
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

