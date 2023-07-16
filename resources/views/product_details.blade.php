@extends('layouts.app')

@section('title','Product Details | '.config('app.name'))

@push('css')
{{--    <link href="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.CloudZoom/Themes/Emporium/Content/cloud-zoom/CloudZoom.css') }}" rel="stylesheet" type="text/css"/>--}}
{{--    <link href="{{ asset('assets/lib/magnific-popup/magnific-popup.css') }}" rel="stylesheet" type="text/css"/>--}}
<link href="{{ asset('css/easyzoom.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .add-to-cart{
            display: inline-block!important;
        }
        .buy-now{
            display: inline-block!important;
            width: calc(100% - 55px);
            margin-bottom: 15px;
        }

        .product-section-images {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            grid-gap: 8px;
            /*margin-top: 20px;*/
            width: 100%;
        }
        .product-section-thumbnail {
            display: flex;
            align-items: center;
            border: 1px solid lightgray;
            min-height: 66px;
            cursor: pointer;
            margin: 15px 0;
        }
        .product-section-thumbnail:hover {
            border: 1px solid #979797;
        }

        .product-section-image {
            /*display: flex;*/
            /*justify-content: center;*/
            /*align-items: center;*/
            /*border: 1px solid #979797;*/
            /*padding: 30px;*/
            /*text-align: center;*/
            /*height: 400px;*/
        }

        .product-section-image img {
            opacity: 0;
            transition: opacity 0.1s ease-in-out;
            max-height: 100%;
        }

        .product-section-image img.active {
            opacity: 1;
        }
        .heading_title{
            background: #cc1b7b;
            padding: 10px;
            font-size: 20px;
            color: white;
            text-align: left;
        }
        .starrr {
            display: inline-block; }
        .starrr a {
            font-size: 16px;
            padding: 0 1px;
            cursor: pointer;
            color: #FFD119;
            text-decoration: none;
        }
        .fa-star:before {
            font-family: inherit!important;
        }
        .checked {
            color: orange;
        }
        .list-group img{
            height: 53px;
            width: 51px;
            border: 1px solid;
            border-radius: 28px;
            display: block;
            float: left;
        }

        .add-to-cart-button {
            width: 105px !important;
        }

        @media (min-width: 1281px){
            .product-prices-box {
                position: absolute;
                top: 0;
                right: 0;
                left: auto;
                width: 362px !important;
                text-align: center;
            }
        }




        /*.image-container {*/
        /*    display: inline-block;*/
        /*    padding: 1em;*/
        /*    max-width: 50%;*/
        /*    vertical-align: top;*/
        /*    width: -webkit-fit-content;*/
        /*    width: -moz-fit-content;*/
        /*    width: fit-content;*/
        /*}*/

        /*.image-container:hover {*/
        /*    background-color: #cde;*/
        /*}*/

        /*.image {*/
        /*    background-position: center;*/
        /*    background-repeat: no-repeat;*/
        /*    background-size: contain;*/
        /*    cursor: crosshair;*/
        /*    display: block;*/
        /*    max-width: 100%;*/
        /*    padding-bottom: 10em;*/
        /*    width: 100em;*/
        /*}*/
        /*.zoomWrapper{*/
        /*    height: 290px!important;*/
        /*    width: 208px!important;*/
        /*}*/
        /*#currentImage{*/
        /*    transition: transform .2s;*/
        /*    margin: 0 auto;*/
        /*}*/
        /*#currentImage:hover {*/
        /*    -ms-transform: scale(1.5); !* IE 9 *!*/
        /*    -webkit-transform: scale(1.5); !* Safari 3-8 *!*/
        /*    transform: scale(1.5);*/
        /*}*/

        .category{
            padding: 6px 10px;
            display: block;
            background: #cc1b7b;
            margin: 5px 0;
            /*text-align: center;*/
            color: white;
            font-size: 18px;
        }
       .category:hover {
            color: white!important;
            text-decoration: none!important;
        }
        .gallery {
            float: left!important;
        }
        .overview {
            display: table!important;
        }
        .also-purchased-products-grid .title{
            background: #cc1b7b;
            color: white!important;
        }

        .product_video_title{
            background: #cc1b7b;
            color: white!important;
            padding: 5px;
            font-size: 15px;
            margin-bottom: 5px;
        }

    </style>

<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">
@endpush

@section('content')
    <?php
    $image_url = config('app.base_image_url');
    ?>
    <!--    main content start-->

    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="center-1">
                <div class="page product-details-page">
                    <div class="page-body">
                        <form method="post" id="product-details-form" action="">
                            <div class="product-essential">
                                <div class="mobile-name-holder"></div>
                                <div class="mobile-prev-next-holder"></div>

                                <div class="gallery">
                                    <div class="picture-wrapper">
                                        {{--                                            <div class="image-container">--}}
                                        {{--                                                <div class="image detail-view" id="currentImage" style="background-image: url({{ $image_url.'product/'.$product->ProductImageFileName }});"></div>--}}
                                        {{--                                            </div>--}}
                                        <div style="display: block!important;position: relative;z-index: 1">
                                            <a href="{{ $image_url.'product/'.$product->ProductImageFileName }}">
                                                <img src="{{ $image_url.'product/'.$product->ProductImageFileName }}" alt="" id="currentImage"/>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="product-section-images thumbnails">
                                        <div class="product-section-thumbnail selected">
                                            <a href="{{ $image_url.'product/'.$product->ProductImageFileName }}" data-standard="{{ $image_url.'product/'.$product->ProductImageFileName }}">
                                                <img class="change-image" src="{{ $image_url.'product/'.$product->ProductImageFileName }}" alt="product">
                                            </a>
                                        </div>

                                        @if ($product->productImage)
                                            @foreach ($product->productImage as $image)
                                                <div class="product-section-thumbnail">
                                                    <a href="{{ $image_url.'product_others/'.$image->ImageFileName }}" data-standard="{{ $image_url.'product_others/'.$image->ImageFileName }}">
                                                        <img class="change-image" src="{{ $image_url.'product_others/'.$image->ImageFileName }}" alt="product">
                                                    </a>
                                                </div>
                                            @endforeach
                                        @endif

                                        @if ($product->ProductVideo && !empty($product->ProductVideo))
                                        <div class="product-section-thumbnail selected">
                                            <!-- Button trigger modal -->
                                            <button type="button" class="video-btn" style="border: none" data-toggle="modal" data-src="{{ $product->ProductVideo }}" data-target="#myModal">
                                                <img src="{{ asset('images/video.png') }}" alt="product">
                                            </button>
                                        </div>
                                        @endif

                                    </div>
                                </div>

                                <div class="overview">
                                    <div class="prev-next-holder"></div>
                                    <div class="product-name">
                                        <h1 style="font-size: 20px" itemprop="name">{{ $product->ProductName }}</h1>
                                        @php
                                            $total_rating_sum = $product->average->sum('Rating');
                                            $total_count = $product->average->count();
                                            if ($total_count){
                                                 $average = $total_rating_sum / $total_count;
                                                 $floar = floor($average);
                                            }else{
                                                $floar = null;
                                            }
                                        @endphp
                                        <span class="badge badge-primary badge-pill">
                                                @if ($floar == 1)
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                            @endif
                                            @if($floar == 2)
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                            @endif
                                            @if($floar == 3)

                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                            @endif
                                            @if($floar == 4)

                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star"></span>
                                            @endif
                                            @if($floar == 5)
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                            @endif
                                            @if($floar == 0)
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                                <span class="fa fa-star"></span>
                                            @endif
                                             </span>
                                        {{ isset($floar) ? $floar : 0 }} Ratings
                                        <br>
                                        <br>
                                        <a href="{{ route('category.product',$product->category->CategorySlug) }}" class="category" >Category : {{ $product->category->Category }}</a>
                                    </div>

                                    <div class="delivery">
                                        <p style="font-weight: bold">
                                            <img width="50" height="30" src="{{ asset('images/delivery.jpg') }}" alt="">
                                            Home Delivery
                                           ( <span style="color: #cc1b7b">4 - 7 days</span>)

                                        </p>
                                    </div>
                                    <div class="delivery">
                                        <p style="font-weight: bold">
                                            <img width="50" height="30" src="{{ asset('images/cod.png') }}" alt="">
                                            Cash on Delivery Available
                                        </p>
                                    </div>
                                    <div class="delivery">
                                        <p style="font-weight: bold">
                                            <img width="50" height="50" src="{{ asset('images/dc.png') }}" alt="">
                                            Delivery Charge: Depends on your location
                                        </p>
                                    </div>
                                    <p style="font-weight: bold;background: #32b792;color: white;padding: 5px;font-size: 16px">
                                        * Delivery Charge (100 taka to 200 taka) depends on your location.
                                    </p>

{{--                                    <div class="product-social-buttons">--}}
{{--                                        <label class="product-social-label">Share:</label>--}}
{{--                                        <ul class="product-social-sharing">--}}
{{--                                            <li>--}}
{{--                                                <!-- Twitter -->--}}
{{--                                                <a class="twitter" title="Share on Twitter" href="">Share on Twitter</a>--}}
{{--                                            </li>--}}
{{--                                            <li>--}}
{{--                                                <!-- Facebook -->--}}
{{--                                                <a class="facebook" title="Share on Facebook" href="">Share on Facebook</a>--}}
{{--                                            </li>--}}
{{--                                            <li>--}}
{{--                                                <!-- Pinterest -->--}}
{{--                                                <a class="pinterest" title="Share on Pinterest" href="">Share on Pinterest</a>--}}
{{--                                            </li>--}}
{{--                                            <li>--}}
{{--                                                <!-- Google+ -->--}}
{{--                                                <a class="google" title="Share on Google Plus" href="">Share on Google Plus</a>--}}
{{--                                            </li>--}}
{{--                                            <li>--}}
{{--                                                <!-- Email a friend -->--}}
{{--                                                <div class="email-a-friend">--}}
{{--                                                    <input type="button" value="Email a friend" class="button-2 email-a-friend-button" />--}}
{{--                                                </div>--}}
{{--                                            </li>--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}

                                    <div class="product-prices-box">
                                        <div>
                                            <div class="prices">
                                                <div class="product-price">
                                                    @if(!empty($product->Discount) && isset($product->Discount) && $product->Discount != 0)
                                                        <span itemprop="price" content="200.00" class="price-value-1036" style="font-size: 14px;text-decoration: line-through;margin-right: 10px">
                                                                {{ $product->ItemPrice }} ৳
                                                        </span>
                                                    @endif

                                                    @if($product->Discount > 0)
                                                        <span class="price actual-price" style="margin-right: 5px;font-size: 11px;color: red">
                                                               (-{{ $product->Discount }}%)
                                                        </span>
                                                    @endif
                                                    <span class="price actual-price">{{ $product->ItemFinalPrice }}৳</span>
                                                </div>
                                            </div>
                                            @if(isset($product->stock) && !empty($product->stock) && $product->stock->Opening !=0)
                                                <div class="add-to-cart-panel">
                                                    <label class="qty-label" for="quantity{{ $product->ProductCode }}">Qty:</label>
                                                    <div class="add-to-cart-qty-wrapper">
                                                        <input readonly type="text" class="qty-input productQuantityTextBox" id="quantity{{ $product->ProductCode }}" name="quantity" value="1"/>
                                                        <span class="plus">i</span>
                                                        <span class="minus">h</span>
                                                    </div>
                                                    <input type="button" style="" class="button-1 add-to-cart add-to-cart-button" value="Add to cart" data-ProductCode="{{ $product->ProductCode }}"/>
                                                    <input type="button" style="margin-left: 10px;" class="button-1 add-to-cart add-to-cart-button" value="BUY NOW" data-ProductCode="{{ $product->ProductCode }}"/>
{{--                                                    <input type="button" style="margin-left: 10px;" class="button-1 buy-now add-to-cart-button" value="BUY NOW" data-ProductCode="{{ $product->ProductCode }}"/>--}}
                                                </div>
                                            @else
                                                <input type="button" class="button-1 add-to-cart-button" style="color: white;display: inline-block!important;width: 100%!important;margin-bottom: 10px;" value="Out Of Stock" />
                                            @endif
                                            <!--sample download-->

                                            <!--add to wishlist-->
                                            <div class="add-to-wishlist">
                                                <button type="button" class="button-2 add-to-wishlist-button" value="Add to wishlist">
                                                    <span>Add to wishlist</span>
                                                </button>
                                            </div>
                                        </div>
                                        <!--delivery-->
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="review_rating">
                                <div class="heading_title">
                                    <strong>Product details of {{ $product->ProductName }}</strong>
                                </div>
                                <br>
                                <div class="col-md-6">
                                    <div class="short-description" style="text-align: left">
                                        {!! $product->ProductDetails !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="review_rating">
                                <div class="heading_title">
                                    <strong>Product Review And Rating</strong>
                                </div>
                                @if (Auth::check())
                                    <div class="row">
                                        <form action="{{ route('store.review.rating') }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="rating" id="rating">
                                            <input type="hidden" name="product_id" value="{{ $product->ProductCode }}">
                                            <div class="col-md-6">
                                                <div class="text-left" style="text-align: left">
                                                    <h5>Click to rate:</h5>
                                                    <div class='starrr' id='star1'></div>
                                                    <div>&nbsp;
                                                        <span class='your-choice-was' style='display: none;'>
                                                            Your rating was <span class='choice'></span>.
                                                        </span>
                                                    </div>
                                                </div>
                                                <textarea id="comment" class="form-control" name="comment" rows="4" cols="50" required></textarea>
                                                <br>
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    <div>
                                        <p style="font-size: 18px;padding: 30px;">if you want to give your comment please <a href="{{ route('login') }}">login</a></p>
                                    </div>
                                @endif

                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-group" style="text-align: left">
                                            @foreach($product->review as $review)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <img src="{{ asset('assets/img/demo_user.png') }}" alt="demo.png">
                                                    <span style="margin-left: 20px">Shanto islam</span>
                                                    <span class="badge badge-primary badge-pill">
                                                       @if ($review->Rating == 1)
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                        @endif
                                                        @if($review->Rating == 2)
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                        @endif
                                                        @if($review->Rating == 3)

                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star"></span>
                                                            <span class="fa fa-star"></span>
                                                        @endif
                                                        @if($review->Rating == 4)

                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star"></span>
                                                        @endif
                                                        @if($review->Rating == 5)
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                            <span class="fa fa-star checked"></span>
                                                        @endif
                                                   </span>
                                                    <br>
                                                    <span style="margin-left: 20px;font-size: 12px">{{ date("F j, Y",strtotime($review->CreatedAt)) }}</span>
                                                    <p style="padding-top: 40px;">{{ $review->Comment }}</p>
                                                </li>
                                            @endforeach

                                        </ul>
                                    </div>
                                    {{--                                <div class="col-md-6">--}}
                                    {{--                                    <div class="product_video_title">--}}
                                    {{--                                        <strong>Product Video</strong>--}}
                                    {{--                                    </div>--}}
                                    {{--                                    <iframe width="100%" height="400" src="{{ $product->ProductVideo }}"></iframe>--}}
                                    {{--                                </div>--}}
                                </div>

                            </div>
                        </div>

                        <!-- related products grid -->
                        <div class="also-purchased-products-grid product-grid">
                            <div class="title">
                                <strong>Also Bought</strong>
                            </div>
                            <div class="item-grid">
                                @foreach($products as $related_product)
                                    @if($related_product->ProductCode != $product->ProductCode)
                                        <div class="item-box">
                                            <div class="product-item">
                                                <div class="picture">
                                                    <a href="{{ route('product.details',$related_product->ProductSlug) }}">
                                                        <img src="{{ $image_url.'product/'.$related_product->ProductImageFileName }}" data-lazyloadsrc="{{ $image_url.'product/'.$related_product->ProductImageFileName }}" alt="" class="picture-img"/>
                                                    </a>
                                                    <div class="btn-wrapper">
                                                        <button type="button" data-ProductCode="{{ $product->ProductCode }}" title="Add to wishlist" class="button-2 add-to-wishlist add-to-wishlist-button">
                                                            Add to wishlist
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="details">
                                                    <h2 class="product-title">
                                                        <a href="{{ route('product.details',$related_product->ProductSlug) }}">{{ $related_product->ProductName }}</a>
                                                    </h2>
                                                    <div class="description"></div>
                                                    <div class="add-info">
                                                        <div class="prices">
                                                            @if(!empty($related_product->Discount) && isset($related_product->Discount) && $related_product->Discount != 0)
                                                                <span class="price old-price">
                                                                       <?php
                                                                    $old_price = ($related_product->ItemPrice * $related_product->VAT) / 100;
                                                                    ?>
                                                                    ৳{{ $related_product->ItemPrice + $old_price }}
                                                                </span>
                                                            @endif
                                                                @if($related_product->Discount > 0)
                                                                    <span class="price actual-price" style="margin-right: 5px;font-size: 11px;color: red">
                                                                   (-{{ $related_product->Discount }}%)
                                                                </span>
                                                                @endif
                                                            <span class="price actual-price">{{ $related_product->ItemFinalPrice }}&#x9F3;</span>
                                                        </div>
                                                        @if(isset($related_product->stock) && !empty($related_product->stock) && $related_product->stock->Opening !=0)
                                                        <div class="buttons">
                                                            <div class="ajax-cart-button-wrapper">
                                                                <div class="add-to-cart-qty-wrapper">
                                                                    <input type="text" class="productQuantityTextBox" id="quantity{{ $related_product->ProductCode }}" name="quantity" value="1">
                                                                    <span class="plus">i</span>
                                                                    <span class="minus">h</span>
                                                                </div>
                                                                <input type="button" value="Add to cart" data-ProductCode="{{ $related_product->ProductCode }}"
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
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="carousel-wrapper"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <!-- 16:9 aspect ratio -->
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="" id="video"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!--    main content end-->

@endsection

@push('js')
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/easyzoom.js') }}"></script>

<script>
    (function() {
        // Get all images with the `detail-view` class
        var zoomBoxes = document.querySelectorAll('.detail-view');

        // Extract the URL
        zoomBoxes.forEach(function(image) {
            var imageCss = window.getComputedStyle(image, false),
                imageUrl = imageCss.backgroundImage
                    .slice(4, -1).replace(/['"]/g, '');

            // Get the original source image
            var imageSrc = new Image();
            imageSrc.onload = function() {
                var imageWidth = imageSrc.naturalWidth,
                    imageHeight = imageSrc.naturalHeight,
                    ratio = imageHeight / imageWidth;

                // Adjust the box to fit the image and to adapt responsively
                var percentage = ratio * 100 + '%';
                image.style.paddingBottom = percentage;

                // Zoom and scan on mousemove
                image.onmousemove = function(e) {
                    // Get the width of the thumbnail
                    var boxWidth = image.clientWidth,
                        // Get the cursor position, minus element offset
                        x = e.pageX - this.offsetLeft,
                        y = e.pageY - this.offsetTop,
                        // Convert coordinates to % of elem. width & height
                        xPercent = x / (boxWidth / 100) + '%',
                        yPercent = y / (boxWidth * ratio / 100) + '%';

                    // Update styles w/actual size
                    Object.assign(image.style, {
                        backgroundPosition: xPercent + ' ' + yPercent,
                        backgroundSize: imageWidth + 'px'
                    });
                };

                // Reset when mouse leaves
                image.onmouseleave = function(e) {
                    Object.assign(image.style, {
                        backgroundPosition: 'center',
                        backgroundSize: 'cover'
                    });
                };
            }
            imageSrc.src = imageUrl;
        });
    })();
</script>

<script>

    $(document).ready(function () {
        $('.change-image').click(function () {
            var image = $(this).attr('src');
            $('#currentImage').attr('src',image);
        })
    });

</script>

<script>
    // Instantiate EasyZoom instances
    var $easyzoom = $('.easyzoom').easyZoom();

    // Setup thumbnails example
    var api1 = $easyzoom.filter('.easyzoom--with-thumbnails').data('easyZoom');

    $('.thumbnails').on('click', 'a', function(e) {
        var $this = $(this);

        e.preventDefault();

        // Use EasyZoom's `swap` method
        api1.swap($this.data('standard'), $this.attr('href'));
    });

    // Setup toggles example
    var api2 = $easyzoom.filter('.easyzoom--with-toggle').data('easyZoom');

    $('.toggle').on('click', function() {
        var $this = $(this);

        if ($this.data("active") === true) {
            $this.text("Switch on").data("active", false);
            api2.teardown();
        } else {
            $this.text("Switch off").data("active", true);
            api2._init();
        }
    });


</script>

<script>
    var slice = [].slice;

    (function($, window) {
        var Starrr;
        window.Starrr = Starrr = (function() {
            Starrr.prototype.defaults = {
                rating: void 0,
                max: 5,
                readOnly: false,
                emptyClass: 'fa fa-star',
                fullClass: 'fa fa-star',
                change: function(e, value) {}
            };

            function Starrr($el, options) {
                this.options = $.extend({}, this.defaults, options);
                this.$el = $el;
                this.createStars();
                this.syncRating();
                if (this.options.readOnly) {
                    return;
                }
                this.$el.on('mouseover.starrr', 'a', (function(_this) {
                    return function(e) {
                        return _this.syncRating(_this.getStars().index(e.currentTarget) + 1);
                    };
                })(this));
                this.$el.on('mouseout.starrr', (function(_this) {
                    return function() {
                        return _this.syncRating();
                    };
                })(this));
                this.$el.on('click.starrr', 'a', (function(_this) {
                    return function(e) {
                        e.preventDefault();
                        return _this.setRating(_this.getStars().index(e.currentTarget) + 1);
                    };
                })(this));
                this.$el.on('starrr:change', this.options.change);
            }

            Starrr.prototype.getStars = function() {
                return this.$el.find('a');
            };

            Starrr.prototype.createStars = function() {
                var j, ref, results;
                results = [];
                for (j = 1, ref = this.options.max; 1 <= ref ? j <= ref : j >= ref; 1 <= ref ? j++ : j--) {
                    results.push(this.$el.append("<a href='#' />"));
                }
                return results;
            };

            Starrr.prototype.setRating = function(rating) {
                if (this.options.rating === rating) {
                    rating = void 0;
                }
                this.options.rating = rating;
                this.syncRating();
                return this.$el.trigger('starrr:change', rating);
            };

            Starrr.prototype.getRating = function() {
                return this.options.rating;
            };

            Starrr.prototype.syncRating = function(rating) {
                var $stars, i, j, ref, results;
                rating || (rating = this.options.rating);
                $stars = this.getStars();
                results = [];
                for (i = j = 1, ref = this.options.max; 1 <= ref ? j <= ref : j >= ref; i = 1 <= ref ? ++j : --j) {
                    results.push($stars.eq(i - 1).removeClass(rating >= i ? this.options.emptyClass : this.options.fullClass).addClass(rating >= i ? this.options.fullClass : this.options.emptyClass));
                }
                return results;
            };

            return Starrr;

        })();
        return $.fn.extend({
            starrr: function() {
                var args, option;
                option = arguments[0], args = 2 <= arguments.length ? slice.call(arguments, 1) : [];
                return this.each(function() {
                    var data;
                    data = $(this).data('starrr');
                    if (!data) {
                        $(this).data('starrr', (data = new Starrr($(this), option)));
                    }
                    if (typeof option === 'string') {
                        return data[option].apply(data, args);
                    }
                });
            }
        });
    })(window.jQuery, window);
</script>

<script>
    $('#star1').starrr({
        change: function(e, value){
            if (value) {
                $('.your-choice-was').show();
                $('.choice').text(value);
                $('#rating').val(value);
            } else {
                $('.your-choice-was').hide();
            }
        }
    });

    var $s2input = $('#star2_input');
    $('#star2').starrr({
        max: 10,
        rating: $s2input.val(),
        change: function(e, value){
            $s2input.val(value).trigger('input');
        }
    });
</script>
<script type="text/javascript">
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-39205841-5', 'dobtco.github.io');
    ga('send', 'pageview');
</script>

<script>
    $(document).ready(function() {
        // Gets the video src from the data-src on each button
        var $videoSrc;
        $(".video-btn").click(function() {
            $videoSrc = $(this).attr("data-src");
            console.log("button clicked" + $videoSrc);
        });

        // when the modal is opened autoplay it
        $("#myModal").on("shown.bs.modal", function(e) {
            console.log("modal opened" + $videoSrc);
            // set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
            $("#video").attr(
                "src",
                $videoSrc + "?autoplay=1&showinfo=0&modestbranding=1&rel=0&mute=1"
            );
        });

        // stop playing the youtube video when I close the modal
        $("#myModal").on("hide.bs.modal", function(e) {
            // a poor man's stop video
            $("#video").attr("src", $videoSrc);
        });

    });
</script>
@endpush

