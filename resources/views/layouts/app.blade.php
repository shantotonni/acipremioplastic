@include('partial.top_head')
<body class="home-page-body">
    {{-- Messenget Chat Head --}}
    <!-- Load Facebook SDK for JavaScript -->
    <div id="fb-root"></div>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          xfbml            : true,
          version          : 'v7.0'
        });
      };

      (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

    <!-- Your Chat Plugin code -->
    <div class="fb-customerchat" attribution=setup_tool page_id="303954723467775" theme_color="#ff5ca1" logged_in_greeting="Welcome to ACI Premio Plastics! How can I help you, sir?" logged_out_greeting="Welcome to ACI Premio Plastics! How can I help you, sir?">
    </div>

    {{-- End Messenget Chat Head --}}

<?php
    $image_url = config('app.base_image_url');
?>

{{--<!--[if lte IE 8]>--}}
{{--<div style="clear: both; height: 59px; text-align: center; position: relative;">--}}
{{--    <a href="http://www.microsoft.com/windows/internet-explorer/default.aspx" target="_blank">--}}
{{--        <img src="/Themes/Emporium/Content/img/ie_warning.jpg" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />--}}
{{--    </a>--}}
{{--</div>--}}
{{--<![endif]-->--}}

<div class="master-wrapper-page">
    <div class="responsive-nav-wrapper-parent">
        <div class="responsive-nav-wrapper">
            <div class="menu-title">
                <span>Menu</span>
            </div>
            <div class="search-wrap">
                <span>Search</span>
            </div>
            <div class="mobile-logo">
                <a href="{{ route('home') }}" class="logo"> <img alt="acipremioplastics.com Limited" title="acipremioplastics.com Limited" src="{{ asset('assets/images/logo/logoZ.png') }}"/></a>
            </div>
            <div class="shopping-cart-link"></div>
            <div class="personal-button" id="header-links-opener">
                <span>Personal menu</span>
            </div>
        </div>
    </div>

    @include('partial/header')

    <div class="overlayOffCanvas"></div>

    @yield('content')

    @include('partial/footer')

</div>


@include('partial/bottom_footer')


</body>

<!-- Mirrored from sobjibazaar.com/ by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 01 Jun 2020 13:45:37 GMT -->

