 <div class="footer">
        <div class="footer-upper">
            <div class="footer-logo">
                <img src="{{ asset('assets/images/logo/logoZ.png') }}" alt="footer_logo"/>
            </div>

            <address class="company-address">
                245, Tejgaon Industrial Area. Dhaka 1208, Bangladesh
            </address>
<!--            <div class="partnership-block">-->
<!--                <div class="title">-->
<!--                    <strong>Partnership with</strong>-->
<!--                </div>-->
<!--                <img src="Themes/Emporium/Content/img/partners.jpg" alt="Partners of sobjibazaar.com limited"/>-->
<!--            </div>-->
        </div>
        <div class="footer-middle">
            <div class="footer-block">
                <div class="title">
                    <strong>Information</strong>
                </div>
                <ul class="list">
                    <li><a href="{{ route('about.us') }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                    <li><a href="{{ route('how.to.buy') }}">How to Buy</a></li>
                    <li><a href="{{ route('terms.condition') }}">Terms & Conditions</a></li>
                    <li><a href="{{route('delivery.policy')}}">Delivery Policy</a></li>
                    <li><a href="{{ route('refund.policy') }}">Refund & Return Policy</a></li>
                </ul>
            </div>
            <div class="footer-block">
                <div class="title">
                    <strong>My account</strong>
                </div>
                <ul class="list">
                    <li><a href="{{ route('customer.profile') }}">My account</a></li>
                    <li><a href="{{ route('customer.order') }}">Orders</a></li>
                    <li><a href="{{ route('customer.address') }}">Addresses</a></li>
{{--                    <li><a href="">Recently viewed</a></li>--}}
                </ul>
            </div>
            <div class="footer-block">
                <div class="title">
                    <strong>Customer service</strong>
                </div>
                <ul class="list">
                    <li><a href="{{ route('cart') }}">My Cart</a></li>
                    <li><a href="{{ route('customer.wishlist') }}">Wishlist</a></li>
{{--                    <li><a href="">Apply for vendor account</a></li>--}}
                </ul>

                <div class="social-icons-links">
                    <div class="title">
                        <strong>Follow us</strong>
                    </div>

                    <ul class="social-sharing">
                        <li><a target="_blank" class="facebook" href="#"></a></li>
                        <li><a target="_blank" class="rss" href="#"></a></li>
                    </ul>
                </div>
            </div>

            <div class="socials-and-payments">
                <div class="title">
                    <strong>Call for order</strong>
                    <p>Careline: 09606 666 674</p>
                    <p style="margin-top: 0">Call 9am-6pm (Sun - Thu)</p>
                </div>
                <style>
                    .title p{
                        color: #959595;
                        font-size: 13px;
                        margin-top: 10px;
                    }
                </style>

                <br>

<!--                <div class="pay-options">-->
<!--                    <h3 class="title"></h3>-->
<!--                    <h3 class="title" style="margin-bottom: -10px;">Membership</h3>-->
<!--                    <img src="Themes/Emporium/Content/img/basis.png" alt="basis"/>-->
<!--                    <img src="Themes/Emporium/Content/img/e-cab-logo.png" alt="e-cab bangladesh"/>-->
<!--                </div>-->

                <div class="newsletter">
                    <div class="title">
                        <strong>Newsletter</strong>
                    </div>
                    <div class="newsletter-subscribe" id="newsletter-subscribe-block">
                        <div class="newsletter-email">
                            <input id="newsletter-email" class="newsletter-subscribe-text"
                                   placeholder="Enter your email here..." aria-label="Sign up for our newsletter"
                                   type="email" name="NewsletterEmail" value=""/>
                            <input type="button" value="Subscribe" id="newsletter-subscribe-button"
                                   class="button-1 newsletter-subscribe-button"/>
                        </div>
                        <div class="newsletter-validation">
                            <span id="subscribe-loading-progress" style="display: none;" class="please-wait">Wait...</span>
                            <span class="field-validation-valid" data-valmsg-for="NewsletterEmail" data-valmsg-replace="true"></span>
                        </div>
                    </div>
                    <div class="newsletter-result" id="newsletter-result-block"></div>
                </div>
            </div>
        </div>
        {{--<div class="footer-ssl-banner">--}}
            {{--<img class="small-screen" src="{{ asset('assets/Themes/Emporium/Content/img/ssl-commerz-pay-with-logo-all-size-01.png') }}" alt="sslCommerz"/>--}}
            {{--<img class="large-screen" src="{{ asset('assets/Themes/Emporium/Content/img/ssl-commerz-pay-with-logo-all-size.png') }}" alt="sslCommerz"/>--}}
        {{--</div>--}}
        <div class="footer-lower">
            <div class="footer-disclaimer">
                Copyright &copy; <?php echo date('Y')?> <a href="https://www.acipremioplastics.com/">acipremioplastics.com</a> Limited. All rights reserved.
            </div>
        </div>
    </div>
