<!DOCTYPE html>
<html lang="en" class="html-home-page">
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<!-- /Added by HTTrack -->
<head>
    <title>@yield('title')</title>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
    <meta name="description" content="{{ config('app.name') }} - Trusted online plastic furniture, toys & household items shop in Bangladesh. Shop now & get discount."/>
    <meta name="keywords" content="{{ config('app.name') }},  online plastic furniture,  plastic furniture shop,  bangladesh online shop, dhaka online shop"/>
    <meta name="generator" content="nopCommerce"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <meta name="facebook-domain-verification" content="nkqt3mw2souc1edghaskyy9g9zmski" />
    <meta name="google-site-verification" content="MW9HgstyUg7ZHkXBn6Ol8itISZEnTXYX5pRpxZJRo2E" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&amp;subset=cyrillic-ext,greek-ext" rel="stylesheet"/>

    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link href="{{ asset('assets/Themes/Emporium/Content/css/animate5e1f.css?v=2') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Themes/Emporium/Content/css/styles7b30.css?v=4') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Themes/Emporium/Content/css/tables7b30.css?v=4') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Themes/Emporium/Content/css/mobile7b30.css?v=4') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Themes/Emporium/Content/css/4807b30.css?v=4') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Themes/Emporium/Content/css/7688c94.css?v=4.1') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Themes/Emporium/Content/css/10247b30.css?v=4') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Themes/Emporium/Content/css/12807b30.css?v=4') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Themes/Emporium/Content/css/16007b30.css?v=4') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Plugins/SevenSpikes.Core/Styles/slick-slider-1.6.0.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Plugins/SevenSpikes.Core/Styles/perfect-scrollbar.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.JCarousel/Styles/slick.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.JCarousel/Themes/Emporium/Content/JCarousel.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.SmartProductCollections/Themes/Emporium/Content/SmartProductCollectionse67d.css?v=1.3') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.AnywhereSliders/Styles/nivo/nivo-slider.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.AnywhereSliders/Themes/Emporium/Content/nivo/nivo.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.AjaxCart/Themes/Emporium/Content/ajaxCart.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.ProductRibbons/Styles/Ribbons.common.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.ProductRibbons/Themes/Emporium/Content/Ribbons.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/Plugins/SevenSpikes.Nop.Plugins.QuickView/Themes/Emporium/Content/QuickView.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/lib/fineuploader/fine-uploader.min.css') }}" rel="stylesheet" type="text/css"/>
{{--    <link href="{{ asset('css/easyzoom.css') }}" rel="stylesheet" type="text/css"/>--}}
    <link href="{{ asset('assets/Themes/Emporium/Content/css/theme.custom-14290.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css"/>

    @stack('css')

    <script src="{{ asset('assets/lib/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/Plugins/SevenSpikes.Core/Scripts/iOS-12-array-reverse-fix.min.js') }}"></script>
    <script src="{{ asset('assets/Themes/Emporium/Content/scripts/jquery.jscroll.min.js') }}"></script>

    <link rel="icon" href="{{ asset('assets/images/logo/icon.png') }}" type="image/gif" sizes="16x16">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-252052638-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-252052638-1');
    </script>

    <!-- Meta Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '521157879960580');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=521157879960580&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Meta Pixel Code -->

    <!-- Eskimi DSP Pixel Code -->
    <script>
        !function(f,e,t,u,n,s,p) {if(f.esk)return;n=f.esk=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f.___esk)f.___esk=n;n.push=n;n.loaded=!0;n.queue=[];s=e.createElement(t);s.async=!0;s.src=u;p=e.getElementsByTagName(t)[0];p.parentNode.insertBefore(s,p)}(window,document,'script', 'https://dsp-media.eskimi.com/assets/js/e/gtr.min.js?_=0.0.0.5');
        esk('init', '30611');
    </script>
    <!-- End Eskimi DSP Pixel Code -->

</head>
