@extends('layouts.app')

@section('title','Cart List | '.config('app.name'))

@push('css')
@endpush

@section('content')
    <?php
    $image_url = config('app.base_image_url');
    ?>
    <!--    main content start-->
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            <div class="center-1">
                <div class="page checkout-page">
                    <div class="page-title">
                        <h1>Checkout Confirm</h1>
                    </div>
                    <div class="page-body checkout-data">
                        <ol class="opc" id="checkout-steps">
                            <form action="{{ route('checkout.store') }}" id="co-payment-method-form" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="AddressID" value="{{ $data[0]['AddressID'] }}">
                                <input type="hidden" name="AddressTypeID" value="{{ $data[0]['AddressTypeID'] }}">
                                <input type="hidden" name="CustomerName" value="{{ $data[0]['CustomerName'] }}">
                                <input type="hidden" name="CustomerEmail" value="{{ $data[0]['CustomerEmail'] }}">
                                <input type="hidden" name="CustomerMobileNo" value="{{ $data[0]['CustomerMobileNo'] }}">
                                <input type="hidden" name="DeliveryAddress" value="{{ $data[0]['DeliveryAddress'] }}">
                                <input type="hidden" name="DistrictName" value="{{ $data[0]['DistrictName'] }}">
                                <input type="hidden" name="DistrictCode" value="{{ $data[0]['DistrictCode'] }}">
                                <input type="hidden" name="ThanaName" value="{{ $data[0]['ThanaName'] }}">
                                <input type="hidden" name="ThanaCode" value="{{ $data[0]['ThanaCode'] }}">
                                <li id="opc-payment_method" class="tab-section allow active">
                                    <div class="step-title">
                                        <h2 class="title">Checkout Confirm</h2>
                                    </div>
                                    <div id="checkout-step-payment-method" class="step a-item">
                                        <div id="checkout-payment-method-load">
                                            <div class="checkout-data">
                                                <div class="section payment-method">
                                                    <ul class="method-list" id="payment-method-block">
                                                        <li>
                                                            <div class="method-name">
                                                                <div class="payment-logo">
                                                                    <label for="paymentmethod_1">
                                                                        <img src="https://sobjibazaar.com/plugins/Payments.CashOnDelivery/logo.jpg" alt="Cash On Delivery (COD)" />
                                                                    </label>
                                                                </div>
                                                                <div class="payment-details">
                                                                    <input id="paymentmethod" type="radio" name="paymentmethod" value="cod" checked />
                                                                    <label for="paymentmethod">Cash On Delivery (COD)</label>
                                                                    <div class="payment-description">Pay by "Cash on delivery"</div>
                                                                </div>
                                                            </div>

                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="checkout-step-confirm-order" class="step a-item" style="">
                                        <div id="checkout-confirm-order-load">
                                            <div class="checkout-data">
                                                <div class="section order-summary">
                                                    <div class="order-summary-content">
                                                        <div class="order-review-data">
                                                            <div class="billing-info-wrap" style="height: 352px;">
                                                                <div class="billing-info">
                                                                    <div class="title">
                                                                        <strong>Delivery Address</strong>
                                                                    </div>
                                                                    <ul class="info-list">
                                                                        <li class="name">
                                                                            {{ $data[0]['CustomerName'] }}
                                                                        </li>
                                                                        <li class="email">
                                                                            {{ $data[0]['CustomerEmail'] }}
                                                                        </li>
                                                                        <li class="phone">
                                                                            {{ $data[0]['CustomerMobileNo'] }}
                                                                        </li>
                                                                        <li class="address1">
                                                                            {{ $data[0]['DeliveryAddress'] }}
                                                                        </li>
                                                                        <li class="thana">
                                                                            {{ $data[0]['ThanaName'] }}
                                                                        </li>
                                                                        <li class="district">
                                                                            {{ $data[0]['DistrictName'] }}
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="table-wrapper">
                                                            <table class="cart">
                                                                <colgroup>
                                                                    <col width="1" />
                                                                    <col />
                                                                    <col width="1" />
                                                                    <col width="1" />
                                                                    <col width="1" />
                                                                </colgroup>
                                                                <thead>
                                                                <tr class="cart-header-row">
                                                                    <th class="product-picture">Image</th>
                                                                    <th class="product">Product(s)</th>
                                                                    <th class="unit-price">Price</th>
                                                                    <th class="quantity">Qty</th>
                                                                    <th class="subtotal">Total</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $mrp_total = 0;
                                                                ?>
                                                                @foreach(Cart::content() as $item)
                                                                    <?php
                                                                    $mrp_total += $item->options->ItemPrice;
                                                                    ?>
                                                                    <tr class="cart-item-row">
                                                                        <td class="product-picture">
                                                                            <a href="">
                                                                                <img src="{{ $image_url.'product/'.$item->model->ProductImageFileName }}" data-lazyloadsrc="{{ $image_url.'product/'.$item->model->ProductImageFileName }}" alt=""/>
                                                                            </a>
                                                                        </td>
                                                                        <td class="product">
                                                                            <a href="{{ route('product.details', $item->model->ProductSlug) }}" class="product-name">{{ $item->name }}</a>
                                                                        </td>
                                                                        <td class="unit-price">
                                                                            <label class="td-title">Price:</label>
                                                                            <span class="product-unit-price">{{ $item->price }}৳</span>
                                                                        </td>
                                                                        <td class="quantity">
                                                                            <div class="product-qty-container">
                                                                                <label class="product-qty-elm td-title" for="itemquantity10583">Qty:</label>
                                                                                <span class="product-quantity">{{ $item->qty }}</span>
                                                                            </div>
                                                                        </td>
                                                                        <td class="subtotal">
                                                                            <label class="td-title">Total:</label>
                                                                            <span class="product-subtotal">{{ $item->price * $item->qty }}৳</span>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="cart-footer">
                                                            <div class="cart-options"></div>
                                                            <div class="totals">
                                                                <div class="total-info">
                                                                    <table class="cart-total">
                                                                        <tbody>
                                                                            <tr class="order-subtotal">
                                                                                <td class="cart-total-left">
                                                                                    <label>MRP:</label>
                                                                                </td>
                                                                                <td class="cart-total-right">
                                                                                    <span class="value-summary">{{ $mrp_total }}৳</span>
                                                                                </td>
                                                                            </tr>
                                                                            @if (!empty(session()->get('spin_offer')['CouponCode']))
                                                                                    <?php
                                                                                    $offer = session()->get('spin_offer')['offer'];
                                                                                    $offer_amount = (Cart::subtotal() * $offer) /100;
                                                                                    ?>
                                                                                <input type="hidden" name="DiscountAmount" value="{{ $offer_amount }}">
                                                                                <tr class="order-subtotal-discount">
                                                                                    <td class="cart-total-left">
                                                                                        <label>Discount <span style="font-size: 10px">({{ $offer }}%)</span>:</label>
                                                                                    </td>
                                                                                    <td class="cart-total-right">
                                                                                        <span class="value-summary">{{ $offer_amount }}৳</span>
                                                                                    </td>
                                                                                </tr>
                                                                            @endif

                                                                            @if (!empty(session()->get('coupon_offer')['CouponCode']))
                                                                                    <?php
                                                                                    $offer = session()->get('coupon_offer')['offer'];
                                                                                    $offer_amount = (str_replace(',','',$mrp_total) * $offer) /100;
//                                                                                    $offer_amount = (str_replace(',','',Cart::subtotal()) * $offer) /100;
                                                                                    ?>
                                                                                <input type="hidden" name="DiscountAmount" value="{{ $offer_amount }}">
                                                                                <tr class="order-subtotal-discount">
                                                                                    <td class="cart-total-left">
                                                                                        <label>Discount <span style="font-size: 10px">({{ $offer }}%)</span>:</label>
                                                                                    </td>
                                                                                    <td class="cart-total-right">
                                                                                        <span class="value-summary">{{ $offer_amount }}৳</span>
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                            <tr class="order-subtotal">
                                                                                <td class="cart-total-left">
                                                                                    <label>Sub Total:</label>
                                                                                </td>
                                                                                <td class="cart-total-right">
                                                                                    <span class="value-summary">{{ str_replace(',','',$mrp_total) - (isset($offer_amount) ? $offer_amount : 0) }}৳</span>
{{--                                                                                    <span class="value-summary">{{ Cart::subtotal() }}৳</span>--}}
                                                                                </td>
                                                                            </tr>



                                                                        <tr class="shipping-cost">
                                                                            <td class="cart-total-left">
                                                                                <label>Delivery Charge:</label>
                                                                            </td>
                                                                            <td class="cart-total-right">
                                                                                <span class="value-summary">Depends on your location.</span>
                                                                            </td>
                                                                        </tr>

                                                                        <tr class="order-total">
                                                                            <td class="cart-total-left">
                                                                                <label>Total:</label>
                                                                            </td>
                                                                            <td class="cart-total-right">
                                                                                @if (!empty(session()->get('spin_offer')['CouponCode']))

                                                                                    <span class="value-summary"><strong>{{ Cart::total() - (isset($offer_amount) ? $offer_amount : 0) }}৳</strong></span>
                                                                                @elseif (!empty(session()->get('coupon_offer')['CouponCode']))
{{--                                                                                    <span class="value-summary"><strong>{{ str_replace(',','',Cart::total()) - (isset($offer_amount) ? $offer_amount : 0) }}৳</strong></span>--}}
{{--                                                                                @else--}}
                                                                                    <span class="value-summary"><strong>{{ Cart::total()}}৳</strong></span>
                                                                                @endif

                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <div class="buttons" id="payment-method-buttons-container">
                                    <p class="back-link">
                                        <a href="{{ route('checkout') }}"><small>« </small>Back</a>
                                    </p>
                                    <input type="submit" class="button-1 payment-method-next-step-button" value="Confirm" />
                                </div>
                            </form>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--    main content end-->

@endsection

@push('js')

@endpush

