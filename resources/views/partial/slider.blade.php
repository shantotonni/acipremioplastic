<?php
//dd(count($sliders));
?>
<div class="Home-page-main-slider">
        <div class="slider-wrapper anywhere-sliders-nivo-slider theme- no-captions" data-imagescount="{{ count($sliders) }}"
                data-sliderhtmlelementid="WidgetSlider-home_page_main_slider-1" data-animspeed="500" data-pausetime="4000">
            <div id="WidgetSlider-home_page_main_slider-1" class="nivoSlider">
                @foreach($sliders as $slider)
                    <a href="{{ $slider->Url }}" target="_blank">
                        <img src="{{ $image_url.'banner/'.$slider->BannerImageFile }}"
                           data-thumb="{{ $image_url.'banner/'.$slider->BannerImageFile }}" alt="slider image" />
                    </a>
                @endforeach
{{--                <div class="nivo-caption"></div>--}}
            </div>
        </div>
</div>
