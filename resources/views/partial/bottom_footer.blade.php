@include('partial.quick_view')

<script src="{{ asset('assets/lib/jquery-validate/jquery.validate-v1.17.0/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/lib/jquery-validate/jquery.validate.unobtrusive-v3.2.10/jquery.validate.unobtrusive.min.js') }}"></script>
<script src="{{ asset('assets/lib/jquery-ui/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/lib/jquery-migrate/jquery-migrate-3.0.1.min.js') }}"></script>
<script src="{{ asset('assets/js/public.common.js') }}"></script>
<script src="{{ asset('assets/js/public.ajaxcart.js') }}"></script>
<script src="{{ asset('assets/lib/kendo/2014.1.318/kendo.core.min.js') }}"></script>
<script src="{{ asset('assets/lib/kendo/2014.1.318/kendo.userevents.min.js') }}"></script>
<script src="{{ asset('assets/lib/kendo/2014.1.318/kendo.draganddrop.min.js') }}"></script>
<script src="{{ asset('assets/lib/kendo/2014.1.318/kendo.window.min.js') }}"></script>
<script src="{{ asset('assets/Plugins/SevenSpikes.Core/Scripts/swipeEvents.min.js') }}"></script>
<script src="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.JCarousel/Scripts/slick.min.js') }}"></script>
<script src="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.SmartProductCollections/Scripts/Products.min.js') }}"></script>
<script src="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.AnywhereSliders/Scripts/AnywhereSliders.min.js') }}"></script>
<script src="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.AnywhereSliders/Scripts/nivo/jquery.nivo.slider.min.js') }}"></script>
<script src="{{ asset('assets/Plugins/SevenSpikes.Core/Scripts/sevenspikes.core.min.js') }}"></script>
<script src="{{ asset('assets/Plugins/SevenSpikes.Core/Scripts/jquery.json-2.4.min.js') }}"></script>
<script src="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.AjaxCart/Scripts/AjaxCart.min.js') }}"></script>
<script src="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.ProductRibbons/Scripts/ProductRibbons.min.js') }}"></script>
<script src="{{ asset('assets/lib/fineuploader/jquery.fine-uploader.min.js') }}"></script>
<script src="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.QuickView/Scripts/QuickView.min.js') }}"></script>
<script src="{{ asset('assets/Plugins/SevenSpikes.Core/Scripts/cloudzoom.core.min.js') }}"></script>
<script src="{{ asset('assets/Plugins/SevenSpikes.Core/Scripts/footable.min.js') }}"></script>
<script src="{{ asset('assets/Plugins/SevenSpikes.Core/Scripts/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/Plugins/SevenSpikes.Core/Scripts/sevenspikes.theme.min.js') }}"></script>
<script src="{{ asset('assets/Plugins/SevenSpikes.Core/Scripts/slick-slider-1.6.0.min.js') }}"></script>
<script src="{{ asset('assets/Themes/Emporium/Content/scripts/emporium5e1f.js?v=2') }}"></script>
{{--<script src="{{ asset('js/easyzoom.js') }}') }}"></script>--}}
{{--<!--<script src="Themes/Emporium/Content/scripts/tawk3860.js?v=1') }}"></script>-->--}}
<script src="{{ asset('js/toastr.min.js') }}"></script>

{!! Toastr::message() !!}

@stack('js')

<script>
    $(document).ready(function () {
        var carouselHtmlElementId = "jcarousel-1-303";
        var jCarousel = $("#" + carouselHtmlElementId + " .slick-carousel");

        if (jCarousel.length === 0) {
            console.log("Slick NOt initilized");
            return;
        }
        var numOfSlidesToScroll = 1;
        var loopItems = true;
        var pauseOnHover = true;
        var isRtl = false;
        var autoscrollTimeout = 3;
        var autoscroll = autoscrollTimeout > 0 ? true : false;
        var navigationArrows = true;
        var navigationDots = false;
        // TODO: Make a validator for this setting.
        var numberOfVisibleItems = 6 < 1 ? 1 : 6;
        var animationSpeedString = "slow";
        var initialSlide = 1 - 1;
        var prevArrowHtml = '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button">Previous</button>';
        var nextArrowHtml = '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button">Next</button>';
        var animationSpeed;

        switch (animationSpeedString) {
            case "slow":
                animationSpeed = 300;
                break;
            case "fast":
                animationSpeed = 150;
                break;
            default:
                animationSpeed = 0;
        }

        var responsiveBreakpointsObj = {};

        try {
            responsiveBreakpointsObj = JSON.parse(
                '[{"breakpoint":1500,"settings":{"slidesToShow":4}},{"breakpoint":1200,"settings":{"slidesToShow":4}},{"breakpoint":980,"settings":{"slidesToShow":4}},{"breakpoint":460,"settings":{"slidesToShow":2}},{"breakpoint":300,"settings":{"slidesToShow":1}}]'
            );
            for (var i = 0; i < responsiveBreakpointsObj.length; i++) {
                if (responsiveBreakpointsObj[i].settings.slidesToShow < numOfSlidesToScroll) {
                    responsiveBreakpointsObj[i].settings.slidesToScroll = responsiveBreakpointsObj[i].settings.slidesToShow;
                }
            }
        } catch (e) {
            console.log("Invalid slick slider responsive breakpoints setting value!");
        }

        jCarousel.on("init", function () {
            $.event.trigger({type: "newProductsAddedToPageEvent"});
            $(".carousel-title").show();
        });

        jCarousel.slick({
            rtl: isRtl,
            infinite: loopItems,
            slidesToShow: numberOfVisibleItems,
            slidesToScroll: numOfSlidesToScroll,
            dots: navigationDots,
            speed: animationSpeed,
            autoplay: autoscroll,
            autoplaySpeed: autoscrollTimeout * 1000,
            arrows: navigationArrows,
            cssEase: "linear",
            respondTo: "slider",
            edgeFriction: 0.05,
            initialSlide: initialSlide,
            pauseOnHover: pauseOnHover,
            draggable: false,
            prevArrow: prevArrowHtml,
            nextArrow: nextArrowHtml,
            responsive: responsiveBreakpointsObj,
        });

        var slidesCount = jCarousel.slick("getSlick").slideCount;

        if (slidesCount > numberOfVisibleItems && navigationArrows) {
            $("#" + carouselHtmlElementId + " .carousel-title").addClass("has-navigation");
        }

        <?php foreach ($categories= \App\Model\Category::all() as $category) {
            ?>
        $("#<?php echo $category->CategorySlug;?>").slick({
            slidesToShow: 2,
            slidesToScroll: 1,
            //autoplay: true,
            //autoplaySpeed: 2000,
            responsive: [
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
            ]
        });
        <?php
        }?>

        //quick view toggle
        $(".quick-view-button").click(function () {
            $(".k-overlay").css('display','block');
            var ProductCode = $(this).attr("data-ProductCode");
            $('#ProductCode').val(ProductCode);

            var parentDiv = $(this).parent().parent().parent('.product-item');
            var selectedImageSrc  = parentDiv.children('.picture').children('a').children('img').attr('src');
            var productName = parentDiv.children('.details').children('.product-title').children('a').text();
            var productPrice = parentDiv.children('.details').children('.add-info').children('.prices').children('.actual-price').text();
            // console.log(selectedImage.attr('src'));
            $(".quickViewWindow .product-content img").attr('src',selectedImageSrc);
            $(".quickViewWindow .product-name").text(productName);
            $(".quickViewWindow .product-price").children().text(productPrice);
            // Reinitilize Cloud Zoom
            CloudZoom.quickStart();
            $(".quickView").css('top','10%');
            $(".quickView").css('position','fixed');
            $(".quickView").css('display','block');
        });

        $(".k-window-actions").click('click',function (e) {
            e.preventDefault();
            $(".k-overlay").css('display','none');
            $(".quickView").css('display','none');

            (".quickViewWindow .product-content img").attr('src','');
            $(".quickViewWindow .product-name").text('');
            $(".quickViewWindow .product-price").children().text('');
        });
    });
</script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function(){

        $('.add-to-cart').click(function(e){
            e.preventDefault();
            var ProductCode = $(this).attr("data-ProductCode");
            var quantity = $('#quantity'+ProductCode).val();

            $.ajax({
                url: '{{ route('cart.store') }}',
                type: "POST",
                data: {ProductCode : ProductCode,quantity : quantity},
                dataType: 'JSON',
                success: function (data) {
                    console.log(data);
                    if($.isEmptyObject(data.error)){
                        toastr.success(data.success);
                        $('.cart-qty').html(data.qty);
                        $('.mini-shopping-cart').html(data.cart_data);
                        $('.top-cart-total').html(data.total_price);
                    }else{
                        toastr.error(data.error);
                    }
                }
            });
        });

        $('.buy-now').click(function(e){
            e.preventDefault();
            var ProductCode = $(this).attr("data-ProductCode");
            var quantity = $('#quantity'+ProductCode).val();

            $.ajax({
                url: '{{ route('buy.now.cart.store') }}',
                type: "POST",
                data: {ProductCode : ProductCode,quantity : quantity},
                dataType: 'JSON',
                success: function (data) {
                    console.log(data);
                    if($.isEmptyObject(data.error)){
                        toastr.success(data.success);
                        $('.cart-qty').html(data.qty);
                        $('.mini-shopping-cart').html(data.cart_data);
                        $('.top-cart-total').html(data.total_price);
                        window.location = '{{ route('checkout') }}';
                    }else{
                        toastr.error(data.error);
                    }
                }
            });
        });

        $('.quick-view-add-to-cart').click(function(e){
            e.preventDefault();
            var ProductCode = $('#ProductCode').val();
            var quantity = $('.quantity').val();

            $.ajax({
                url: '{{ route('cart.store') }}',
                type: "POST",
                data: {ProductCode : ProductCode,quantity : quantity},
                dataType: 'JSON',
                success: function (data) {
                    console.log(data);
                    if($.isEmptyObject(data.error)){
                        toastr.success(data.success);
                        $('.cart-qty').html(data.qty);
                        $('.top-cart-total').html(data.total_price);
                    }else{
                        toastr.error(data.error);
                    }
                }
            });
        });

        $('.add-to-wishlist').click(function(e){
            e.preventDefault();
            var ProductCode = $(this).attr("data-ProductCode");

            $.ajax({
                url: '{{ route('customer.wishlist.store') }}',
                type: "POST",
                data: {ProductCode : ProductCode},
                dataType: 'JSON',
                success: function (data) {
                    console.log(data);
                    if($.isEmptyObject(data.error)){
                        toastr.success(data.success);
                        $('.wishlist-qty').html(data.qty);
                    }else{
                        toastr.error(data.error);
                    }
                }
            });
        });

        $('#search').keyup(function(e){
            e.preventDefault();

            var q = $('#search').val();

            $.ajax({
                url: '{{ route('global.search') }}',
                type: "GET",
                data: {q : q},
                dataType: 'JSON',
                success: function (data) {
                    console.log(data);
                    $('#livesearch').show();
                    $('#livesearch').html(data);

                    $('body').click(function() {
                        $('#livesearch').hide();
                    });
                }
            });
        });

    });
</script>
