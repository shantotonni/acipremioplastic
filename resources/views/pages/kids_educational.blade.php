@extends('layouts.app')

@section('title','Contact Us | '.config('app.name'))

@push('css')

@endpush


@section('content')
    <div class="Kids-page-main-slider">
        <div class="slider-wrapper anywhere-sliders-nivo-slider theme- no-captions"
             data-sliderhtmlelementid="WidgetSlider-home_page_main_slider-1" data-animspeed="500" data-pausetime="4000">
            <div id="WidgetSlider-home_page_main_slider-1">
                <img src="{{ asset('uploads/banner/KBB banner.png') }}" alt="kids slider image"/>
            </div>
        </div>
    </div>

    <!--    main content start-->

    <div class="master-wrapper-content">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="center-1">
                    <div class="page home-page">
                        <div class="page-body">
                            <div class="sb-home-page-category-slider-kids-edu">
                                <div id="jcarousel-1-303" class="jCarouselMainWrapper">
                                    <div class="nop-jcarousel category-grid home-page-category-grid">
                                        <div class="slick-carousel item-grid">
                                            {{--                                        @foreach($categories as $category)--}}
                                            <div class="kids-edu">
                                                <div class="item-box">
                                                    <div class="category-item1">
                                                        <div class="picture">
                                                            <img src="{{ asset('uploads/banner/KBB_Logo.png') }}" alt="Supernova_Logo"/>
                                                        </div>
                                                        <h2 class="title" style="padding: 10px 15px">
                                                            <span style="display: block;padding-bottom: 10px">Kids Brain Builder</span>
                                                            ৩ থেকে ১৫ বছরের শিশুদের মেধাবিকাশে সহায়ক অ্যাপ
                                                            <a class="kidsDownload" style="background: white; margin-top: 10px; padding: 10px 10px;"
                                                               href="https://play.google.com/store/apps/details?id=com.aci.medhabirsupernova.kidsbrainbuilder " title="">Download</a>
                                                        </h2>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="kids-edu">
                                                <div class="item-box">
                                                    <div class="category-item1">
                                                        <div class="picture">
                                                            <img src="{{ asset('uploads/banner/Supernova_Logo.png') }}" alt="Supernova_Logo"/>
                                                        </div>
                                                        <h2 class="title" style="padding: 10px 15px">
                                                            <span style="display: block;padding-bottom: 10px">Medhabir Supernova</span>
                                                            ষষ্ঠ থেকে দশম শ্রেণি পর্যন্ত সকল বিষয়ের শিক্ষা সহায়ক অ্যাপ
                                                            <a class="kidsDownload" style="background: white; margin-top: 10px; padding: 10px 10px;"
                                                               href="https://play.google.com/store/apps/details?id=com.aci.medhabirsupernova" title="">Download</a>
                                                        </h2>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>

    </div>

    <!--    main content end-->

@endsection

@push('js')

@endpush


