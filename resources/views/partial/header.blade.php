<style>
    #search-layer{
        position:absolute;
        background:rgba(255,255,255,0.5);
        z-index:9;
        left:0px;
        top:0px;
    }
    /***********************************/
    #livesearch{
        z-index: 99999;
        max-height: 260px;
        overflow: auto;
        width: 92%;
        box-shadow: 0px 2px 4px #444;
        position: absolute;
        top: 47px;
    }
    /***********************************/
    .live-outer{
        width:100%;
        height:60px;
        border-bottom:1px solid #ccc;
        background:#fff;
    }
    .live-outer:hover{
        background:#F3F3F3;
    }
    .live-im{
        float:left;
        width:12%;
        height:60px;
    }
    .live-im img{
        width:100%;
        height:100%;
    }
    .live-product-det{
        float:left;
        width: 60%;
        height:60px;
        text-align: left;
    }
    .live-product-name{
        width:100%;
        height:22px;
        margin-top:4px;
    }
    .live-product-name p{
        margin:0px;
        color:#333;
        text-shadow: 1px 1px 1px #DDDDDD;
        font-size:17px;
    }
    .live-product-price{
        width:100%;
        height:25px;
    }
    .live-product-price-text{
        float:left;
        width:50%;
    }
    .live-product-price-text p{
        margin:0px;
        font-size:16px;
    }
    .link-p-colr{
        color:#333;
    }
</style>
<div class="header">
        <div class="header-lower">
            <div class="header-logo">
                <a href="{{ route('home') }}" class="logo"> <img alt="aciebazar.com Limited" title="aciebazar.com Limited" src="{{ asset('assets/images/logo/logoZ.png') }}"/></a>
            </div>
            <div class="search-box store-search-box">
                <div class="close-side-menu">
                    <span class="close-side-menu-text">Search</span>
                    <span class="close-side-menu-btn">Close</span>
                </div>
                <form method="get" id="small-search-box-form" action="#">
                    <input type="text" class="search-box-text" id="search" autocomplete="off" name="search" placeholder="Search"/>
                    <input type="submit" class="button-1 search-box-button" value="Search"/>
                    <div id="livesearch"></div>
                </form>
            </div>

            <div class="header-links-wrapper">
                <div class="header-links">
                    <ul>
                        <li>
                            <a href="" class="ico-account opener">My account</a>
                            <div class="profile-menu-box login-form-in-header">
                                <div class="close-side-menu">
                                    <span class="close-side-menu-text">My account</span>
                                    <span class="close-side-menu-btn">Close</span>
                                </div>
                                <div class="header-form-holder">
                                    <a href="{{ route('customer.profile') }}" class="ico-register">My Account</a>
                                    @if (Auth::guest())
                                    <a href="{{ route('register') }}" class="ico-register">Register</a>
                                    <a href="{{ route('login') }}" class="ico-login">Log in</a>
                                    @else
                                        <a class="ico-login" href="{{ route('logout') }}" onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="{{ route('customer.wishlist') }}" class="ico-wishlist">
                                <span class="wishlist-label">Wishlist</span>
                                <?php
                                    $wishlist = \App\Model\WishList::where('CustomerID',isset(Auth::user()->CustomerID) ? Auth::user()->CustomerID : '')->get();
                                ?>
                                <span class="wishlist-qty">{{ count($wishlist) }}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="mobile-flyout-wrapper">
                        <div class="close-side-menu">
                            <span class="close-side-menu-text">Shopping cart</span>
                            <span class="close-side-menu-btn">Close</span>
                        </div>
                        <div id="flyout-cart" class="flyout-cart">
                            <div id="topcartlink">
                                <a href="{{ route('cart') }}" class="ico-cart">
                                    <span class="cart-qty">
                                     @if (Cart::instance('default')->count() > 0)
                                            {{ Cart::instance('default')->count() }}
                                        @else
                                         0
                                        @endif
                                    </span>
                                    <span class="cart-label top-cart-total"> {{ Cart::total() }}&#x9F3; </span>
                                </a>
                            </div>

                                <div class="mini-shopping-cart">
                                    @if(Cart::count() > 0)
                                    <div class="flyout-cart-scroll-area">
                                        <div class="items ps-container">
                                            @foreach(Cart::content() as $item)
                                            <div class="item first">
                                                <div class="picture">
                                                    <a href="{{ route('product.details', $item->model->ProductSlug) }}">
                                                        <img alt="" src="{{ $image_url.'product/'.$item->model->ProductImageFileName }}">
                                                    </a>
                                                </div>
                                                <div class="product">
                                                    <div class="left">
                                                        <div class="name">
                                                            <a href="{{ route('product.details', $item->model->ProductSlug) }}">{{ $item->name }}</a>
                                                        </div>
                                                    </div>
                                                    <div class="right">
                                                        <div class="price"> <span>{{ $item->price }}৳</span></div>
                                                        <div class="quantity">Qty: <span>{{ $item->qty }}</span></div>
                                                    </div>
                                                    <a class="remove-item" href="javascript:;" title="remove">remove</a>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="flyout-lower">
                                            <div class="count">
                                                <a href="{{ route('cart') }}">{{ Cart::instance('default')->count() }} item(s)</a>
                                            </div>
                                            <div class="totals">Total: <strong>{{ Cart::total() }}৳</strong></div>
                                            <div class="buttons">
{{--                                                <input type="button" value="Go to cart" class="button-1 cart-button" onclick="setLocation('cart')">--}}
                                                <a href="{{ route('cart') }}" class="button-1 cart-button" style="width: 100%;text-align: center;padding-top: 11px;color: white;font-size: 20px;">Go to cart</a>
                                            </div>
                                        </div>
                                    </div>
                                    @else

                                        <div class="flyout-cart-scroll-area">
                                            <p>You have no items in your shopping cart.</p>
                                        </div>
                                    @endif
                                </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-menu-parent">
            <div class="header-menu categories-in-side-panel">
                <div class="category-navigation-list-wrapper">
                    <span class="category-navigation-title">All Categories</span>
                    <ul class="category-navigation-list sticky-flyout"></ul>
                </div>
                <div class="close-menu">
                    <span class="close-menu-text">Menu</span>
                    <span class="close-menu-btn">Close</span>
                </div>

                <input type="hidden" value="false" id="isRtlEnabled"/>

                <ul class="top-menu">
                    <li class="all-categories">
                        <a href="#">All Categories</a>
                        <div class="plus-button"></div>
                        <div class="sublist-wrap">
                            <ul class="sublist">
                                <li class="back-button">
                                    <span>Back</span>
                                </li>

                                @foreach($categories as $category)
                                    <li class="root-category-items">
                                        <a href="{{ route('category',$category->CategorySlug) }}" class="{{ count($category->subcategory) > 0 ? 'with-subcategories' : '' }}">{{ $category->Category }}</a>
                                        @if(isset($category->subcategory) && !empty($category->subcategory) && count($category->subcategory) > 0)
                                            <div class="plus-button"></div>
                                            <div class="sublist-wrap ">
                                                <ul class="sublist">
                                                    <li class="back-button">
                                                        <span>Back</span>
                                                    </li>
                                                    @foreach($category->subcategory as $subcat)
                                                        <li>
                                                            <a href="{{ route('category',$subcat->SubcategorySlug) }}">{{ $subcat->SubCategory }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li><a href="{{ route('home') }}">Home</a></li>
{{--                    <li><a href="{{ route('get.offers') }}" style="color: #cc1b7b!important;">Get Offers</a></li>--}}
                    <li><a href="{{ route('new.arrivals') }}">New Arrivals</a></li>
                    <li><a href="{{ route('offer.category') }}">Offer</a></li>
                    <li><a href="{{ route('current.offer') }}">Current Offer</a></li>
                    <li style="background: #37d426;"><a style="color: white!important;" href="{{ route('kids.educational.app') }}">Kids Educational Apps</a></li>
{{--                    <li><a href="{{ route('kids.educational.app') }}">Kids Educational Apps</a></li>--}}
                    <li style="background: #CC1B7B;"><a style="color: white!important;" href="tel:09606666678">HOT LINE: 09606666674</a></li>
                    <li style="background: #e27c20;"><a style="color: white!important;" href="{{ route('order.track') }}">Track Your Order</a></li>
                </ul>
                <div class="mobile-menu-items"></div>
            </div>
        </div>
    </div>
