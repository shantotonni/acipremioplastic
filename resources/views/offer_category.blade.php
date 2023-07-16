@extends('layouts.app')

@section('title','Category Product | '.config('app.name'))

@section('content')
    <?php
    $image_url = config('app.base_image_url');
    ?>
    <!--    main content start-->

    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="center-1">
                <div class="page category-page">
                    <div class="page-title" style="background: #cc1b7b;">
                        <h1 style="color: white">OFFERS</h1>
                    </div>
                    <div class="page-body">
                        <div class="category-grid sub-category-grid">
                            <div class="item-grid">
                                @foreach($offers as $offer)
                                <div class="item-box">
                                    <div class="sub-category-item">
                                        <div class="picture">
                                            <a href="{{ route('offer.details',$offer->ID) }}" style="padding-top: 70px">
                                                <img src="{{ $image_url.'offer_image/'.$offer->OfferImage }}" class="img-fluid" data-lazyloadsrc="{{ $image_url.'offer_image/'.$offer->OfferImage }}" alt=""/>
                                            </a>
                                        </div>
                                        <h2 class="title">
                                            <a href="{{ route('offer.details',$offer->ID) }}" title="Show products in category Rice">
                                                {{ $offer->OfferName }}
                                            </a>
                                        </h2>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="product-filters"></div>

                        <div class="returned-products-marker"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!--    main content end-->

@endsection

@push('js')

@endpush

