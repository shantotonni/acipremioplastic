@extends('layouts.app')

@section('title','Category Product | '.config('app.name'))

@push('css')

@endpush


@section('content')

    <!--    main content start-->
    <?php
    $image_url = config('app.base_image_url');
    ?>
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            @if(isset($offer) && !empty($offer))
                <div class="slide">
                    <img src="{{ $image_url.'offer_image/'.$offer->OfferBanner }}" data-lazyloadsrc="{{ $image_url.'offer_image/'.$offer->OfferBanner }}" alt="" class="picture-img"/>
                </div>

            @endif

            <div class="center-1">
                <div class="page category-page">
                    <div class="page-title" >
                        @if(isset($offer) && !empty($offer))
                        <h1 style="color: black;margin-top:30px;">{{ $offer->OfferName }}</h1>
                        <div style="margin-top: 20px;margin-bottom:30px;text-align:left;">
                            {!! $offer->OfferDescription !!}
                        </div>
                        @endif
                    </div>
                    <div class="page-body">
                        <div class="product-selectors">
                            <div class="filters-button-wrapper">
                                <button class="filters-button">Filters</button>
                            </div>
                        </div>
                        <div class="product-filters"></div>

                        <div class="product-grid">
                            <div class="item-grid">
                                @if(isset($offer) && !empty($offer))
                                    @if(isset($offer->offer_products) && !empty($offer->offer_products))
                                        @foreach($offer->offer_products as $product)
                                        <div class="item-box">
                                            <div class="product-item">
                                                <div class="picture">
                                                    <a href="{{ route('product.details',$product->products->ProductSlug) }}">
                                                        <img src="{{ $image_url.'product/'.$product->products->ProductImageFileName }}" data-lazyloadsrc="{{ $image_url.'product/'.$product->products->ProductImageFileName }}" alt="" class="picture-img"/>
                                                    </a>
                                                    <div class="btn-wrapper">
                                                        <button type="button" data-ProductCode="{{ $product->products->ProductCode }}" title="Add to wishlist" class="button-2 add-to-wishlist add-to-wishlist-button">
                                                            Add to wishlist
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="details">
                                                    <h2 class="product-title">
                                                        <a href="{{ route('product.details',$product->products->ProductSlug) }}">{{ $product->products->ProductName }}</a>
                                                    </h2>
                                                    <div class="description"></div>
                                                    <div class="add-info">
                                                        <div class="prices">
                                                            @if(!empty($product->products->Discount) && isset($product->products->Discount) && $product->products->Discount != 0)
                                                                <span class="price old-price">
                                                                       <?php
                                                                    $old_price = ($product->products->ItemPrice * $product->VAT) / 100;
                                                                    ?>
                                                                   ৳{{ $product->products->ItemPrice + $old_price }}
                                                                </span>
                                                            @endif
                                                            @if($product->products->Discount > 0)
                                                                <span class="price actual-price" style="margin-right: 5px;font-size: 11px;color: red">
                                                                   (-{{ $product->products->Discount }}%)
                                                                </span>
                                                            @endif
                                                            <span class="price actual-price">{{ $product->products->ItemFinalPrice }}&#x9F3;</span>
                                                        </div>
                                                        @if(isset($product->products->stock) && !empty($product->products->stock) && $product->products->stock->Opening !=0)
                                                        <div class="buttons">
                                                            <div class="ajax-cart-button-wrapper">
                                                                <div class="add-to-cart-qty-wrapper">
                                                                    <input type="text" class="productQuantityTextBox" id="quantity{{ $product->products->ProductCode }}" name="quantity" value="1">
                                                                    <span class="plus">i</span>
                                                                    <span class="minus">h</span>
                                                                </div>
                                                                <input type="button" value="Add to cart" data-ProductCode="{{ $product->products->ProductCode }}"
                                                                       class="button-2 add-to-cart product-box-add-to-cart-button">
                                                            </div>
                                                        </div>
                                                        @else
                                                            <div class="buttons">
                                                                <input type="button" style="color: red" value="Out Of Stock" class="button-2 product-box-add-to-cart-button">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <h2>Product Not Found</h2>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="returned-products-marker"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')

@endpush

