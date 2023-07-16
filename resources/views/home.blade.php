@extends('layouts.app')

@section('title','Home | '.config('app.name'))

@push('css')

@endpush

@section('content')
    <?php
    $image_url = config('app.base_image_url');
    ?>

    @include('partial/slider')

    <!--    main content start-->
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="center-1">
                <div class="page home-page">
                    <div class="page-body">
                        <div class="sb-home-page-category-slider">
                            <div id="jcarousel-1-303" class="jCarouselMainWrapper">
                                <div class="nop-jcarousel category-grid home-page-category-grid">
                                    <div class="slick-carousel item-grid">
                                        @foreach($categories as $category)
                                            <div class="carousel-item">
                                                <div class="item-box">
                                                    <div class="category-item">
                                                        <div class="picture">
                                                            <a href="{{ route('category',$category->CategorySlug) }}">
                                                                <img class="lazy" style="height: 300px!important;" alt="" src="{{ $image_url.'category/'.$category->CategoryImage }}"/>
                                                            </a>
                                                        </div>
                                                        <h2 class="title">
                                                            <a href="{{ route('category',$category->CategorySlug) }}" title="{{ $category->Category }}">{{ $category->Category }}</a>
                                                        </h2>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="sb-homepage-category-grid">
                            <div class="category-grid home-page-category-grid">
                                <div class="item-grid">
                                    @foreach($categories as $category)
                                    <div class="item-box">
                                        <div class="category-item">
                                            <div class="picture">
                                                <a href="{{ route('category',$category->CategorySlug) }}">
                                                    <img class="lazy" style="height: 180px!important;" src="{{ $image_url.'category/'.$category->CategoryImage }}"
                                                         data-lazyloadsrc="{{ $image_url.'category/'.$category->CategoryImage }}" alt=""/>
                                                </a>
                                            </div>
                                            <h2 class="title">
                                                <a href="{{ route('category',$category->CategorySlug) }}" title="{{ $category->Category }}">{{ $category->Category }}</a>
                                            </h2>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="sb-homepage-bottom-section">
                            @foreach($categories as $category)
                                @if ($category->IsFeatured == 'Y')
                                    <div class="spc spc-categories portrait">
                                        <div class="spc-body">
                                            <div class="category-info">
                                                <div class="category-details">
                                                    <div class="category-picture">
                                                        <a href="{{ route('category.product',$category->CategorySlug) }}" title="Fruits">
                                                            <img class="lazy" src="{{ $image_url.'category/'.$category->CategoryImage }}" />
                                                            <label class="category-name">{{ $category->Category }}</label>
                                                            <label class="spc-to-all-products">See all products</label>
                                                        </a>
                                                    </div>
                                                    <div class="mobile-navigation category-mobile-navigation">
                                                        <select class="category-mobile-navigation-select">
                                                            <option class="tab" value="10" data-tabid="10">BEST SELLER</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="category-products">
                                                <ul class="navigation">
                                                    <li class="tab active" data-tabid="10">
                                                        <span>BEST SELLER</span>
                                                    </li>
                                                </ul>
                                                <div class="product-grid active">
                                                    <div class="item-grid" id="{{$category->CategorySlug}}">

                                                        <?php
                                                            $i = 0;
                                                            $totalProduct = count($category->products);
                                                            while($i < $totalProduct) {
                                                        ?>

                                                        <div class="slick-slide" style="width: 447px;">
                                                            <div style="width: 100%; display: inline-block;">
                                                                <div class="item-box">
                                                                    <div class="product-item sevenspikes-ajaxcart">
                                                                        <div class="picture">
                                                                            <a href="{{ route('product.details',$category->products[$i]->ProductSlug) }}">
                                                                                <img src="{{ $image_url.'product/'.$category->products[$i]->ProductImageFileName }}" data-lazyloadsrc="{{ $image_url.'product/'.$category->products[$i]->ProductImageFileName }}" alt="" class="lazy picture-img" loadedimage="true"/>
                                                                            </a>
                                                                            <div class="btn-wrapper">
                                                                                <div class="quick-view-button" data-ProductCode="{{ $category->products[$i]->ProductCode }}">
                                                                                    <a title="Quick View">Quick View</a>
                                                                                </div>
                                                                                <button type="button" title="Add to wishlist" data-ProductCode="{{ $category->products[$i]->ProductCode }}" class="button-2 add-to-wishlist add-to-wishlist-button">
                                                                                    Add to wishlist
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="details">
                                                                            <h2 class="product-title">
                                                                                <a href="{{ route('product.details',$category->products[$i]->ProductSlug) }}">{{ $category->products[$i]->ProductName }}</a>
                                                                            </h2>
                                                                            <div class="add-info">
                                                                                <div class="prices">
                                                                                    @if(!empty($category->products[$i]->Discount) && isset($category->products[$i]->Discount) && $category->products[$i]->Discount != 0)
                                                                                        <span class="price old-price">
                                                                                            <?php
                                                                                             $old_price = ($category->products[$i]->ItemPrice * $category->products[$i]->VAT) / 100;
                                                                                            ?>
                                                                                            ৳{{ $category->products[$i]->ItemPrice + $old_price }}
                                                                                        </span>
                                                                                    @endif
                                                                                    @if($category->products[$i]->Discount > 0)
                                                                                            <span class="price actual-price" style="margin-right: 5px;font-size: 11px;color: red">
                                                                                             (-{{ $category->products[$i]->Discount }}%)
                                                                                        </span>
                                                                                    @endif

                                                                                    <span class="price actual-price">{{ $category->products[$i]->ItemFinalPrice }} ৳</span>
                                                                                </div>
                                                                                @if(isset($category->products[$i]->stock) && !empty($category->products[$i]->stock) && $category->products[$i]->stock->Opening !=0)
                                                                                <div class="buttons">
                                                                                    <div class="ajax-cart-button-wrapper">
                                                                                        <div class="add-to-cart-qty-wrapper">
                                                                                            <input type="text" class="productQuantityTextBox" id="quantity{{ $category->products[$i]->ProductCode }}" name="quantity" value="1">
                                                                                            <span class="plus">i</span>
                                                                                            <span class="minus">h</span>
                                                                                        </div>
                                                                                        <input type="button" value="Add to cart" data-ProductCode="{{ $category->products[$i]->ProductCode }}"
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
                                                            </div>

                                                            {{--                                                    Bottom Part--}}
                                                            <?php if(isset($category->products[$i+1])){?>
                                                            <div style="width: 100%; display: inline-block;">
                                                                <div class="item-box">
                                                                    <div class="product-item sevenspikes-ajaxcart">
                                                                        <div class="picture">
                                                                            <a href="{{ route('product.details',$category->products[$i+1]->ProductSlug) }}" >
                                                                                <img src="{{ $image_url.'product/'.$category->products[$i+1]->ProductImageFileName }}" data-lazyloadsrc="{{ $image_url.'product/'.$category->products[$i+1]->ProductImageFileName }}" alt="" class="picture-img lazy" loadedimage="true"/>
                                                                            </a>
                                                                            <div class="btn-wrapper">
                                                                                <div class="quick-view-button" data-ProductCode="{{ $category->products[$i+1]->ProductCode }}">
                                                                                    <a title="Quick View">Quick View</a>
                                                                                </div>
                                                                                <button type="button" title="Add to wishlist" data-ProductCode="{{ $category->products[$i+1]->ProductCode }}" class="button-2 add-to-wishlist add-to-wishlist-button">
                                                                                    Add to wishlist
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="details">
                                                                            <h2 class="product-title">
                                                                                <a href="{{ route('product.details',$category->products[$i+1]->ProductSlug) }}">{{ $category->products[$i+1]->ProductName }}</a>
                                                                            </h2>
                                                                            <div class="add-info">
                                                                                <div class="prices">
                                                                                    @if(!empty($category->products[$i+1]->Discount) && isset($category->products[$i+1]->Discount) && $category->products[$i+1]->Discount != 0)
                                                                                        <span class="price old-price">
                                                                                            <?php
                                                                                            $old_price = ($category->products[$i+1]->ItemPrice * $category->products[$i+1]->VAT) / 100;
                                                                                            ?>
                                                                                            ৳{{ $category->products[$i+1]->ItemPrice + $old_price }}
                                                                                        </span>
                                                                                    @endif
                                                                                    @if($category->products[$i+1]->Discount > 0)
                                                                                        <span class="price actual-price" style="margin-right: 5px;font-size: 11px;color: red">
                                                                                         (-{{ $category->products[$i+1]->Discount }}%)
                                                                                    </span>
                                                                                    @endif
                                                                                    <span class="price actual-price">{{ $category->products[$i+1]->ItemFinalPrice }} ৳</span>
                                                                                </div>
                                                                                @if(isset($category->products[$i+1]->stock) && !empty($category->products[$i+1]->stock) && $category->products[$i+1]->stock->Opening !=0)
                                                                                <div class="buttons">
                                                                                    <div class="ajax-cart-button-wrapper">
                                                                                        <div class="add-to-cart-qty-wrapper">
                                                                                            <input type="text" class="productQuantityTextBox" id="quantity{{ $category->products[$i+1]->ProductCode }}" name="quantity" value="1">
                                                                                            <span class="plus">i</span>
                                                                                            <span class="minus">h</span>
                                                                                        </div>
                                                                                        <input type="button" value="Add to cart" data-ProductCode="{{ $category->products[$i+1]->ProductCode }}"
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
                                                            </div>
                                                            <?php }?>

                                                        </div>
                                                        <?php
                                                        $i= $i+2;
                                                        }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="two-row-carousels small-products"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--    main content end-->

@endsection

@push('js')

@endpush

