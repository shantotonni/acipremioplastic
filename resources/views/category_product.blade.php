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
            <div class="center-1">
                <div class="page category-page">
                    <div class="page-title" style="background: #cc1b7b;">
                        @if(isset($category) && !empty($category))
                        <h1 style="color: white">{{ $category->Category }}</h1>
                        @endif
                        @if(isset($sub_category) && !empty($sub_category))
                            <h1 style="color: white">{{ $sub_category->SubCategory }}</h1>
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
                                @if(isset($category) && !empty($category))
                                    @if(isset($category->products) && !empty($category->products))
                                        @foreach($category->products as $product)
                                        <div class="item-box">
                                            <div class="product-item">
                                                <div class="picture">
                                                    <a href="{{ route('product.details',$product->ProductSlug) }}">
                                                        <img src="{{ $image_url.'product/'.$product->ProductImageFileName }}" data-lazyloadsrc="{{ $image_url.'product/'.$product->ProductImageFileName }}" alt="" class="picture-img"/>
                                                    </a>
                                                    <div class="btn-wrapper">
                                                        <button type="button" data-ProductCode="{{ $product->ProductCode }}" title="Add to wishlist" class="button-2 add-to-wishlist add-to-wishlist-button">
                                                            Add to wishlist
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="details">
                                                    <h2 class="product-title">
                                                        <a href="{{ route('product.details',$product->ProductSlug) }}">{{ $product->ProductName }}</a>
                                                    </h2>
                                                    <div class="description"></div>
                                                    <div class="add-info">
                                                        <div class="prices">
                                                            @if(!empty($product->Discount) && isset($product->Discount) && $product->Discount != 0)
                                                                <span class="price old-price">
                                                                       <?php
                                                                    $old_price = ($product->ItemPrice * $product->VAT) / 100;
                                                                    ?>
                                                                   ৳{{ $product->ItemPrice + $old_price }}
                                                                </span>
                                                            @endif
                                                            @if($product->Discount > 0)
                                                                <span class="price actual-price" style="margin-right: 5px;font-size: 11px;color: red">
                                                                   (-{{ $product->Discount }}%)
                                                                </span>
                                                            @endif
                                                            <span class="price actual-price">{{ $product->ItemFinalPrice }}&#x9F3;</span>
                                                        </div>
                                                        @if(isset($product->stock) && !empty($product->stock) && $product->stock->Opening !=0)
                                                        <div class="buttons">
                                                            <div class="ajax-cart-button-wrapper">
                                                                <div class="add-to-cart-qty-wrapper">
                                                                    <input type="text" class="productQuantityTextBox" id="quantity{{ $product->ProductCode }}" name="quantity" value="1">
                                                                    <span class="plus">i</span>
                                                                    <span class="minus">h</span>
                                                                </div>
                                                                <input type="button" value="Add to cart" data-ProductCode="{{ $product->ProductCode }}"
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

                                @if(isset($sub_category) && !empty($sub_category))
                                        @if(isset($sub_category->products) && !empty($sub_category->products))
                                            @foreach($sub_category->products as $product)
                                                <div class="item-box">
                                                    <div class="product-item">
                                                        <div class="picture">
                                                            <a href="{{ route('product.details',$product->ProductSlug) }}">
                                                                <img src="{{ $image_url.'product/'.$product->ProductImageFileName }}" data-lazyloadsrc="{{ $image_url.'product/'.$product->ProductImageFileName }}" alt="" class="picture-img"/>
                                                            </a>
                                                            <div class="btn-wrapper">
                                                                <button type="button" data-ProductCode="{{ $product->ProductCode }}" title="Add to wishlist" class="button-2 add-to-wishlist add-to-wishlist-button">
                                                                    Add to wishlist
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="details">
                                                            <h2 class="product-title">
                                                                <a href="{{ route('product.details',$product->ProductSlug) }}">{{ $product->ProductName }}</a>
                                                            </h2>
                                                            <div class="description"></div>
                                                            <div class="add-info">
                                                                <div class="prices">
                                                                    @if(!empty($product->Discount) && isset($product->Discount) && $product->Discount != 0)
                                                                    <span class="price old-price">
                                                                       <?php
                                                                        $old_price = ($product->ItemPrice * $product->VAT) / 100;
                                                                        ?>
                                                                        ৳{{ $product->ItemPrice + $old_price }}
                                                                    </span>
                                                                    @endif
                                                                        <span class="price actual-price" style="margin-right: 5px;font-size: 11px;color: red">(-{{ $product->Discount }}%)</span>
                                                                    <span class="price actual-price">{{ $product->ItemFinalPrice }}৳</span>
                                                                </div>
                                                                @if(isset($product->stock) && !empty($product->stock) && $product->stock->Opening !=0)
                                                                <div class="buttons">
                                                                    <div class="ajax-cart-button-wrapper">
                                                                        <div class="add-to-cart-qty-wrapper">
                                                                            <input type="text" class="productQuantityTextBox" id="quantity{{ $product->ProductCode }}" name="quantity" value="1">
                                                                            <span class="plus">i</span>
                                                                            <span class="minus">h</span>
                                                                        </div>
                                                                        <input type="button" value="Add to cart" data-ProductCode="{{ $product->ProductCode }}"
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
{{--                        <div class="pager">--}}
{{--                            <ul>--}}
{{--                                <li class="current-page"><span>1</span></li>--}}
{{--                                <li class="individual-page"><a href="category_product.php">2</a></li>--}}
{{--                                <li class="individual-page"><a href="category_product.php">3</a></li>--}}
{{--                                <li class="next-page"><a href="category_product.php">Next</a></li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}

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

