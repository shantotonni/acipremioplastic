@extends('layouts.app')

@section('title','Cart List | '.config('app.name'))

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <style>
        .cart td {
            padding: 5px 13px;
            color: #7e7e7e;
            font-weight: 700;
        }
        .product-qty-container {
            display: flex;
            justify-content: space-around;
        }
        .product-qty-container .product-qty-elm {
            margin: 2px;
        }
        .product-qty-elm.btn-decrement, .product-qty-elm.btn-increment {
            padding: 7px 10px;
            border: 1px solid #c4c4c4;
            background: #e9e9e9;
            border-radius: 3px;
        }
        .quantity{
            height: 33px;
            width: 45px;
            text-align: center;
        }
        .update_button{
            background: #CC1B7B;
            border: 1px solid #CC1B7B;
            color: white;
            border-radius: 4px;
            padding: 0px 8px;
            margin-left: 5px;
        }
    </style>
@endpush

@section('content')
    <?php
    $image_url = config('app.base_image_url');
    ?>
    <!--    main content start-->
    <div class="master-wrapper-content">
        <div class="master-column-wrapper">
            @if(Session()->has('showModal'))
            <h1 id="alert_message" style="color: red">You need to Purchase Minimum 1000 Tk</h1>
            @endif
            <div class="center-1">
                <div class="page shopping-cart-page">
                    <div class="page-title">
                        <h1>My Cart</h1>
                    </div>
                    <div class="page-body">
                        <div class="order-summary-content">
                                @if(Cart::count() > 0)
                                <p style="text-align: center;display: none" class="empty-shopping-cart">Your Shopping Cart is empty!</p>
                                <div class="table-wrapper shopping_cart">
                                    <table class="cart" id="all-data">
                                        <colgroup>
                                            <col width="1">
                                            <col width="1">
                                            <col>
{{--                                            <col width="1">--}}
                                            <col width="1">
                                            <col width="1">
                                            <col width="1">
                                        </colgroup>
                                        <thead>
                                        <tr class="cart-header-row">
                                            <th class="remove-from-cart">Remove</th>
                                            <th class="product-picture">Image</th>
                                            <th class="product">Product(s)</th>
                                            <th class="unit-price">MRP</th>
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
                                           $mrp_total += $item->options->ItemPrice * $item->qty;
                                        ?>

                                        <tr class="cart-item-row">
                                            <td class="remove-from-cart">
                                                <input type="checkbox" name="removefromcart" onclick="destroy({{ $item->id }})" value="{{ $item->rowId }}" id="removechackbox{{ $item->id }}">
                                            </td>
                                            <td class="product-picture">
                                                <a href="">
                                                    <img src="{{ $image_url.'product/'.$item->model->ProductImageFileName }}" data-lazyloadsrc="{{ $image_url.'product/'.$item->model->ProductImageFileName }}" alt="">
                                                </a>
                                            </td>
                                            <td class="product">
                                                <a href="{{ route('product.details', $item->model->ProductSlug) }}" class="product-name">{{ $item->name }}</a>
                                            </td>
                                            <td class="unit-price">
                                                <label class="td-title">Price:</label>
                                                <span class="product-unit-price">{{ $item->options->ItemPrice }}৳</span>
                                            </td>
                                            <td class="unit-price">
                                                <label class="td-title">Price:</label>
                                                <span class="product-unit-price">{{ $item->price }}৳</span>
                                            </td>
                                            <td class="quantity">
                                                <div class="product-qty-container">
                                                    @if (!empty(session()->get('spin_offer')['CouponCode']))
                                                        <label class="product-qty-elm td-title" for="itemquantity">Qty:</label>
                                                        <input type="number" readonly class="quantity" id="quantity{{ $item->id }}" name="quantity" value="{{ $item->qty }}" min="1" max="100">
                                                    @else
                                                        <label class="product-qty-elm td-title" for="itemquantity">Qty:</label>
                                                        <input type="number" class="quantity" id="quantity{{ $item->id }}" name="quantity" value="{{ $item->qty }}" min="1" max="100">
                                                        <input type="button" class="update_button" data-ProductCode="{{ $item->id }}" data-ItemPrice="{{ $item->price }}" data-rowId="{{ $item->rowId }}" id="update-cart" name="update" value="Update" min="1" max="100">
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="subtotal">
                                                <label class="td-title">Total:</label>
                                                <span class="product-subtotal{{ $item->id }}">{{ $item->price * $item->qty }}৳</span>
                                            </td>
                                        </tr>
                                    @endforeach

                                        </tbody>
                                    </table>
                                    <div class="common-buttons">
{{--                                        <button type="submit" value="Update shopping cart" id="cart-update" class="button-2 update-cart-button"><span>Update shopping cart</span></button>--}}
                                        <button type="button" value="Clear shopping cart" id="clear-cart" class="button-2 clear-cart-button"><span>Clear shopping cart</span></button>
                                        <button type="submit" name="continueshopping" value="Continue shopping" class="button-2 continue-shopping-button">Continue shopping</button>
                                    </div>
                                    <p style="font-weight: bold;background: #32b792;color: white;padding: 5px;font-size: 16px">
                                        * Delivery Charge (100 taka to 200 taka) depends on your location.
                                    </p>
                                </div>
                                @else
                                    <p style="text-align: center">Your Shopping Cart is empty!</p>
                                @endif

                                <div class="cart-footer">
                                    <form action="{{ route('apply.coupon') }}" method="post">
                                    <div class="cart-collaterals">
                                        <div class="deals">
                                            <div class="title">Discount codes and Vouchers</div>
                                            @if(\Illuminate\Support\Facades\Auth::check())
                                                    {{ csrf_field() }}
                                                    <div class="list">
                                                        <div class="coupon-box">
                                                            <div class="title">
                                                                <strong>Discount Code</strong>
                                                            </div>
                                                            <div class="hint">
                                                                Enter your coupon here
                                                            </div>
                                                            <div class="coupon-code">
                                                                <input name="categoryId"  type="hidden">
                                                                <input name="coupon_code" id="coupon_code" type="text" class="discount-coupon-code" required>
                                                                <input type="submit" value="Apply" class="button-2 apply-discount-coupon-code-button">
                                                            </div>
                                                        </div>
                                                    </div>

                                            @else
                                                <div class="list">
                                                    If You want to apply coupon code Please login!
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    </form>
                                    <div class="totals">
                                        <div class="total-info">
                                            <table class="cart-total">
                                                <tbody>
                                                <tr class="order-subtotal">
                                                    <td class="cart-total-left">
                                                        <label>MRP:</label>
                                                    </td>
                                                    <td class="cart-total-right">
                                                        <span class="value-summary total" id="mrp_total">{{ $mrp_total }}৳</span>
                                                    </td>
                                                </tr>
                                                @if (!empty(session()->get('coupon_offer')['CouponCode']))
                                                    <?php

                                                    $offer = session()->get('coupon_offer')['offer'];
                                                    $offer_amount = (str_replace(',','',$mrp_total) * $offer) /100;
                                                    ?>

                                                    <tr class="order-subtotal-discount">
                                                        <td class="cart-total-left">
                                                            <label>Discount:({{ $offer }}% Off)</label>
                                                        </td>
                                                        <td class="cart-total-right">
                                                            <span class="value-summary discount">{{ $offer_amount }}৳</span>
                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr class="order-subtotal">
                                                    <td class="cart-total-left">
                                                        <label>Sub Total:</label>
                                                    </td>
                                                    <td class="cart-total-right">

                                                        @if(!empty($offer_amount))
                                                            <span class="value-summary total" id="sub_total">{{ str_replace(',','',$mrp_total) - (isset($offer_amount) ? $offer_amount : 0) }}৳</span>
                                                        @else
                                                            <span class="value-summary total" id="sub_total">{{ Cart::subtotal() }}৳</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                @if (!empty(session()->get('spin_offer')['CouponCode']))
                                                        <?php
                                                        $offer = session()->get('spin_offer')['offer'];
                                                        $offer_amount = (Cart::subtotal() * $offer) /100;
                                                        ?>

                                                    <tr class="order-subtotal-discount">
                                                        <td class="cart-total-left">
                                                            <label>Discount:({{ $offer }}% Off)</label>
                                                        </td>
                                                        <td class="cart-total-right">
                                                            <span class="value-summary discount">{{ $offer_amount }}৳</span>
                                                        </td>
                                                    </tr>
                                                @endif

                                                <tr class="shipping-cost">
                                                    <td class="cart-total-left">
                                                        <label>Delivery Charge:</label>
                                                    </td>
                                                    <td class="cart-total-right">
                                                        <span class="value-summary shipping">Depends on your location</span>
                                                    </td>
                                                </tr>
                                                <tr class="order-total">
                                                    <td class="cart-total-left">
                                                        <label>Total:</label>
                                                    </td>
                                                    <td class="cart-total-right">
                                                        @if (!empty(session()->get('spin_offer')['CouponCode']))
                                                            <span class="value-summary grand-total"><strong>{{ Cart::total() - (isset($offer_amount) ? $offer_amount : 0) }}৳</strong></span>
                                                        @elseif (!empty(session()->get('coupon_offer')['CouponCode']))
                                                            @if(!empty($offer_amount))
                                                                <span class="value-summary total" id="cart_total">{{ str_replace(',','',$mrp_total) - (isset($offer_amount) ? $offer_amount : 0) }}৳</span>
                                                            @else
                                                                <span class="value-summary grand-total"><strong>{{str_replace(',','',Cart::total()) }}৳</strong></span>
                                                            @endif
                                                        @else
                                                              <span class="value-summary grand-total"><strong>{{  Cart::total() }}৳</strong></span>

                                                        @endif
                                                    </td>
{{--                                                    <td class="cart-total-right">--}}
{{--                                                        @if (!empty(session()->get('spin_offer')['CouponCode']))--}}
{{--                                                            <span class="value-summary grand-total"><strong>{{ Cart::total() - (isset($offer_amount) ? $offer_amount : 0) }}৳</strong></span>--}}
{{--                                                        @elseif (!empty(session()->get('coupon_offer')['CouponCode']))--}}
{{--                                                           --}}
{{--                                                            <span class="value-summary grand-total"><strong>{{ str_replace(',','',Cart::total()) - (isset($offer_amount) ? $offer_amount : 0) }}৳</strong></span>--}}
{{--                                                        @else--}}
{{--                                                            <span class="value-summary grand-total"><strong>{{ Cart::total() }}৳</strong></span>--}}
{{--                                                            @endif--}}
{{--                                                    </td>--}}
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="checkout-buttons" style="display: flex">
                                            <a href="{{ route('checkout') }}" id="checkout" name="checkout" value="checkout" class="button-1 checkout-button">Checkout</a>
                                        </div>
                                        <div class="addon-buttons"></div>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{--    <!-- Modal -->--}}
{{--    <div class="modal fade" id="showCartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title" id="exampleModalLabel">Warning</h5>--}}
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    You need to Purchase Minimum 1000 Tk--}}
{{--                </div>--}}
{{--                <div class="modal-footer">--}}
{{--                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!--    main content end-->--}}

@endsection

@push('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/js/core/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function(){

            $('.update_button').click(function(e){
                e.preventDefault();
                var ProductCode = $(this).attr("data-ProductCode");
                var ItemPrice = $(this).attr("data-ItemPrice");
                var rowId = $(this).attr("data-rowId");
                var quantity = $('#quantity'+ProductCode).val();

                $.ajax({
                    url: '{{ route('cart.update') }}',
                    type: "POST",
                    data: {ProductCode : ProductCode, quantity : quantity, rowId : rowId, ItemPrice : ItemPrice},
                    dataType: 'JSON',
                    success: function (data) {
                        console.log(data);
                        if($.isEmptyObject(data.error)){
                            toastr.success(data.success);
                            $('.cart-qty').html(data.qty);
                            $('.product-subtotal'+ProductCode).html(data.price);
                            $('#sub_total').html(data.subtotal + '৳');
                            $('#cart_total').html(data.total_price + '৳');
                            $('#mrp_total').html(data.total_mrp + '৳');
                            $('.grand-total').html(data.total_price );
                            $('.top-cart-total').html(data.total_price);
                            $('.discount').html(data.offer_amount);
                        }else{
                            toastr.error(data.error);
                        }
                    }
                });
            });

            $('#clear-cart').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: '{{ route('cart.clear') }}',
                    type: "GET",
                    dataType: 'JSON',
                    success: function (data) {
                        console.log(data);
                        if($.isEmptyObject(data.error)){
                            toastr.success(data.success);
                            $('.cart-qty').html(data.qty);
                            $(".shopping_cart").css('display','none');
                            $(".empty-shopping-cart").css('display','block');

                        }else{
                            toastr.error(data.error);
                        }
                    }
                });
            });

        });

        function destroy(Id) {
            var id = $('#removechackbox'+Id).val();
            Swal.fire({
                title: 'Are you sure?',
                text: "Cart Item will be trashed",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, trash it!'
            }).then(function(result){
                if (result.value) {
                    $.ajax({
                        url: "{{ route('cart.destroy', '__id__') }}".replace('__id__', id),
                        method: 'DELETE'
                    }).done(function(data) {
                        console.log(data);
                        toastr.success(data.success);
                        Swal.fire({
                            title: 'Success',
                            text: "Cart Item trashed",
                            type: 'success',
                        }).then(function(res){
                            window.location = '{{ route('cart') }}';
                        });
                    });
                }
            })
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            $('#decrease-qty').on('click', function(e) {
                e.preventDefault();
                var id = $(this).attr("data-id");
                $('#itemquantity'+id).val(function(i, oldval) {
                    if (parseInt(oldval) > 1) {
                        return --oldval;
                    } else {
                        return oldval;
                    }
                });
            });

            $('#increase-qty').on('click', function(e) {
                e.preventDefault();
                var id = $(this).attr("data-id");
                $('#itemquantity'+id).val(function(i, oldval) {
                    return ++oldval;
                });
            });

            $('.continue-shopping-button').on('click', function(e) {
                e.preventDefault();
                window.location = '{{ route('home') }}';
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            @if(Session()->has('showModal'))
            $('#showCartModal').modal('show');
            @endif
        });
    </script>
@endpush

