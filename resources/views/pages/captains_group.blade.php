@extends('layouts.app')

@section('title','Product Catalogue | '.config('app.name'))

@push('css')

@endpush

@section('content')

    <div class="master-wrapper-content">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="center-1">
                    <div class="page home-page">
                        <div class="page-body">
                            <div class="sb-home-page-category-slider-kids-edu">
                                <div id="jcarousel-1-303" class="jCarouselMainWrapper">
                                        <div class="form-group">
                                            <embed src="{{asset('uploads/catalogue/Toys-Catalogue.pdf')}}"type="application/pdf" width="100%" height="100%">
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>

    </div>

@endsection

@push('js')

@endpush

<style>
    p {
        font-size: 17px;
        align-items: center;
    }
    .box a {
        display: inline-block;
        background-color: #fff;
        padding: 15px;
        border-radius: 3px;
    }
    .modal {
        align-items: center;
        display: flex;
        justify-content: center;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(254, 126, 126, 0.7);
        transition: all 0.4s;
        visibility: hidden;
        opacity: 0;
    }
    .content {
        position: absolute;
        background: transparent;
        width: 50%;
        height: 100%;
        padding: 1em 2em;
        border-radius: 4px;
        z-index: 10;
    }
    .modal:target {
        visibility: visible;
        opacity: 1;
    }
    .box-close {
        position: absolute;
        top: 20px;
        right: 28px;
        color: #fe0606;
        text-decoration: none;
        font-size: 30px;
    }

    @media only screen and (max-width:500px) {
        .content {
            width: 100%;
            top: 10%;
            z-index: 10;
            padding: 40% 14%;
        }
        .box-close{
            top: 17%;
            right: 5%;
        }

    }
    @media only screen and (max-width:490px) {
        .content {
            width: 100%;
            top: 7%;
            z-index: 10;
            padding: 125px 5px;
        }
        .box-close{
            top: 11%;
            right: 5%;
        }
    }
    @media only screen and (max-width:419px) {
        .content {
            width: 100%;
            top: 7%;
            z-index: 10;
            padding: 187px 58px;
        }
        .box-close{
            top: 18%;
            right: 10%;
        }
    }
    @media only screen and (max-width:375px) {
        .content {
            width: 100%;
            top: 7%;
            z-index: 10;
            padding: 22px 5px;
        }
        .box-close{
            top: 2%;
            right: 0%;
        }
    }

</style>

