@extends('Frontend.Partials.master')

@section('title')
   {{ $moduleAction }}
@stop

@section('styles')

@stop

@section('content')
<div class="page-header mt-30 mb-50">
    <div class="container">
        <div class="archive-header">
            <div class="row align-items-center">
                <div class="col-xl-2">
                    <h1 class="mb-15">Reports</h1>
                    <div class="breadcrumb">
                        <a href="{{ url('/') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                        <span></span> Reports <span></span>
                    </div>
                </div>
                <div class="col-xl-10 text-end d-none d-xl-block">
                    @if(count($arrCategory) > 0)
                    <ul class="tags-list">
                        @foreach($arrCategory as $key => $cRow)
                            <li class="hover-up">
                                <a href="{{ url('/category/'.$cRow->slug) }}"></i>{{ $cRow->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container mb-30">
    <div class="row">
        <div class="col-12">
            <div class="shop-product-fillter">
                <div class="totall-product">
                    <p>We found <strong class="text-brand"><?php echo count($arrReports); ?></strong> items for you!</p>
                </div>
            </div>
            <div class="row product-grid">
                @if(count($arrReports) > 0)
                    @foreach($arrReports as $key => $rVal)
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                            <div class="product-cart-wrap mb-30">
                                <div class="product-content-wrap">
                                    <div class="product-category">
                                        <a href="{{ url('/category/'.$rVal->cat_slug) }}">{{ $rVal->cat_name }}</a>
                                    </div>
                                    <h2><a href="{{ url('/report/'.$rVal->slug.'-'.$rVal->id) }}">{{ $rVal->report_title }}</a></h2>
                                    <div>
                                        <span class="font-small text-muted">By <a href="vendor-details-1.html">{{ $rVal->publisher_name }}</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')

@stop