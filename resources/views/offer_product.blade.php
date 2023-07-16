@extends('layouts.app')

@section('title')
    {{ $coupon->CouponName }} Products | {{ config('app.name') }}
@endsection

@push('css')

@endpush

@section('content')

    <!--    main content start-->
    <?php
    $image_url = config('app.base_image_url');
    ?>
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="center-1">
                <div class="page category-page">
                    <div class="page-title" style="background: #cc1b7b;padding: 5px;">
                        <h1 style="color: white;font-size: 25px">{{ $coupon->CouponName }} Products List</h1>
                    </div>
                    <div class="page-body">
                        <div class="product-grid">
                            <div class="item-grid">

                                @if(isset($coupon->couponProduct) && !empty($coupon->couponProduct) && count($coupon->couponProduct) > 0)
                                    @foreach($coupon->couponProduct as $couponProduct)
                                        <div class="item-box">
                                            <div class="product-item">
                                                <div class="picture">
                                                    <a href="{{ route('product.details',$couponProduct->product->ProductSlug) }}">
                                                        <img src="{{ $image_url.'product/'.$couponProduct->product->ProductImageFileName }}" data-lazyloadsrc="{{ $image_url.'product/'.$couponProduct->product->ProductImageFileName }}" alt="" class="picture-img"/>
                                                    </a>
                                                    <div class="btn-wrapper">
                                                        <button type="button" data-ProductCode="{{ $couponProduct->product->ProductCode }}" title="Add to wishlist" class="button-2 add-to-wishlist add-to-wishlist-button">
                                                            Add to wishlist
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="details">
                                                    <h2 class="product-title">
                                                        <a href="{{ route('product.details',$couponProduct->product->ProductSlug) }}">{{ $couponProduct->product->ProductName }}</a>
                                                    </h2>
                                                    <div class="description"></div>
                                                    <div class="add-info">
                                                        <div class="prices">
                                                            @if(!empty($couponProduct->product->Discount) && isset($couponProduct->product->Discount) && $couponProduct->product->Discount != 0)
                                                                <span class="price old-price">
                                                                       <?php
                                                                    $old_price = ($couponProduct->product->ItemPrice * $couponProduct->product->VAT) / 100;
                                                                    ?>
                                                                    {{ $couponProduct->product->ItemPrice + $old_price }}৳
                                                                    </span>
                                                            @endif
                                                            <span class="price actual-price">{{ $couponProduct->product->ItemFinalPrice }}&#x9F3;</span>
                                                        </div>

                                                        <div class="buttons">
                                                            <div class="ajax-cart-button-wrapper">
                                                                <input type="button" value="BUY NOW" data-ProductCode="{{ $couponProduct->product->ProductCode }}"
                                                                       class="button-2 by_now product-box-add-to-cart-button">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                @else
                                    <h2>Product Not Found</h2>
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
    <script>
        $(document).ready(function(){

            $('.by_now').click(function(e){
                e.preventDefault();
                var ProductCode = $(this).attr("data-ProductCode");
                var CouponCode = "{{ $coupon->CouponCode }}";
                var offer = "{{ $coupon->Offer }}";

                $.ajax({
                    url: '{{ route('cart.store.with.offer') }}',
                    type: "POST",
                    data: { ProductCode : ProductCode,quantity : 1, CouponCode:CouponCode, offer:offer },
                    dataType: 'JSON',
                    success: function (data) {
                        console.log(data);
                        if($.isEmptyObject(data.error)){
                            toastr.success(data.success);
                            window.location = "{{ route('checkout') }}";
                        }else{
                            toastr.error(data.error);
                        }
                    }
                });
            });

        });
    </script>
@endpush

