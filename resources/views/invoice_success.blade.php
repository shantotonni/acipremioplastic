@extends('layouts.app')

@section('title','Order Success | '.config('app.name'))

@push('css')
    <style>
        .btn-google{
            padding: 15px;
            background: #663300;
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
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="center-1">
                <div class="page login-page">
                    <div class="page-body">
                        <div class="customer-blocks">
                            <div class="page-title">
                                <h1>Order Successfully Placed</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')

@endpush


