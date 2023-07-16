@extends('layouts.app')

@section('title','Wish List | '.config('app.name'))

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
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
        .update_button{
            background: #1ECDA3;
            border: 1px solid #1ECDA3;
            color: white;
            border-radius: 4px;
            padding: 10px 8px;
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
            <div class="center-1">
                <div class="page wishlist-page">
                    <div class="page-title">
                        <h1>
                            Wishlist
                        </h1>
                    </div>
                    <div class="page-body">
                        <div class="wishlist-content">
                            <form method="post" action="/wishlist">
                                <div class="table-wrapper">
                                    <table class="cart" id="all-data">
                                        <colgroup>
                                            <col width="1">
                                            <col width="1">
                                            <col>
                                            <col width="1">
                                            <col width="1">
                                            <col width="1">
                                        </colgroup>
                                        <thead>
                                        <tr class="cart-header-row">
                                            <th class="remove-from-cart">Remove</th>
                                            <th class="product-picture">Image</th>
                                            <th class="product">Product(s)</th>
                                            <th class="unit-price">Price</th>
                                            <th class="quantity">Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @foreach($wishlists as $wishlist)

                                            <tr class="cart-item-row">
                                                <td class="remove-from-cart">
                                                    <input type="checkbox" name="removefromcart" onclick="destroy({{ $wishlist->WishListID }})" value="{{ $wishlist->WishListID }}" id="removechackbox{{ $wishlist->WishListID }}">
                                                </td>
                                                <td class="product-picture">
                                                    <a href="">
                                                        <img src="{{ $image_url.'product/'.$wishlist->product->ProductImageFileName }}" data-lazyloadsrc="{{ url('assets/images/food/',$wishlist->product->ProductImageFileName) }}" alt="" loadedimage="true">
                                                    </a>
                                                </td>
                                                <td class="product">
                                                    <a href="{{ route('product.details', $wishlist->product->ProductSlug) }}" class="product-name">{{ $wishlist->product->ProductName }}</a>
                                                </td>
                                                <td class="unit-price">
                                                    <label class="td-title">Price:</label>
                                                    <span class="product-unit-price">{{ $wishlist->product->ItemFinalPrice }}৳</span>
                                                </td>
                                                @if(isset($wishlist->product->stock) && !empty($wishlist->product->stock) && $wishlist->product->stock->Opening !=0)
                                                    <td class="quantity">
                                                        <div class="product-qty-container">
                                                            <input type="button" class="update_button wishlist-to-cart" data-ProductCode="{{ $wishlist->product->ProductCode }}" id="update-cart" name="update" value="Add To cart">
                                                        </div>
                                                    </td>
                                                @else
                                                    <td class="quantity">
                                                        <div class="product-qty-container">
                                                            <input type="button" class="update_button" style="background: red" id="update-cart" name="update" value="Out Of Stock" disabled>
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--    main content end-->

@endsection

@push('js')
    <script src="{{ asset('assets/js/core/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function(){

            $('.wishlist-to-cart').click(function(e){
                e.preventDefault();
                var ProductCode = $(this).attr("data-ProductCode");

                $.ajax({
                    url: '{{ route('wishlist.to.cart') }}',
                    type: "POST",
                    data: {ProductCode : ProductCode},
                    dataType: 'JSON',
                    success: function (data) {
                        console.log(data);
                        if($.isEmptyObject(data.error)){
                            toastr.success(data.success);
                            window.location = '{{ route('customer.wishlist') }}';
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
                        url: "{{ route('wishlist.destroy', '__id__') }}".replace('__id__', id),
                        method: 'DELETE'
                    }).done(function(data) {
                        console.log(data);
                        toastr.success(data.success);
                        Swal.fire({
                            title: 'Success',
                            text: "Cart Item trashed",
                            type: 'success',
                        }).then(function(res){
                            window.location = '{{ route('customer.wishlist') }}';
                        });
                    });
                }
            })
        }
    </script>
@endpush

