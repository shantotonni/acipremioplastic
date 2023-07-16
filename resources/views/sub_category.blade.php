@extends('layouts.app')

@section('title','Category Product | '.config('app.name'))

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
                <div class="page category-page">
                    <div class="page-title" style="background: #cc1b7b;">
                        <h1 style="color: white">{{ $category->Category }}</h1>
                    </div>
                    <div class="page-body">
                        <div class="category-grid sub-category-grid">
                            <div class="item-grid">
                                @foreach($category->subcategory as $subcate)
                                <div class="item-box">
                                    <div class="sub-category-item">
                                        <div class="picture">
                                            <a href="{{ route('category',$subcate->SubcategorySlug) }}">
                                                <img src="{{ $image_url.'subcategory/'.$subcate->SubCategoryImage }}" data-lazyloadsrc="{{ $image_url.'subcategory/'.$subcate->SubCategoryImage }}" alt=""/>
                                            </a>
                                        </div>
                                        <h2 class="title">
                                            <a href="{{ route('category',$subcate->SubcategorySlug) }}" title="Show products in category Rice">
                                                {{ $subcate->SubCategory }}
                                            </a>
                                        </h2>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="product-filters"></div>

                        <div class="returned-products-marker"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!--    main content end-->

@endsection

@push('js')

@endpush

