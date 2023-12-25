@extends('Frontend.Partials.master')

@section('title')
   {{ $moduleAction }}
@stop

@section('styles')
<style type="text/css">
    .custome-radio {
        font-size: 16px;
    }
    .btn.btn-sm, .button.btn-sm {
        font-size: 14px;
    }
</style>
@stop

@section('content')
<div class="page-header mt-30 mb-50">
    <div class="container">
        <div class="archive-header">
            <div class="row align-items-center">
                <div class="col-xl-12">
                    <h1 class="mb-15">{{ $moduleTitle }}</h1>
                    <div class="breadcrumb">
                        <a href="{{ url('/') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                        <span></span> {{ $moduleTitle }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container mb-30">
    <div class="row">
        <div class="col-12">
            <div class="row product-grid">
                @if(count($arrReports) > 0)
                    @foreach($arrReports as $key => $val)
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                            <div class="product-cart-wrap mb-30">
                                <div class="product-content-wrap">
                                    @if(!empty($arrCategory->name))
                                        <div class="product-category">
                                            <a href="{{ url('/category/'.$arrCategory->cat_slug) }}">{{ $arrCategory->name }}</a>
                                        </div>
                                    @endif
                                    <h2><a href="{{ url('/report/'.$val->slug.'-'.$val->id) }}">{{ $val->report_title }}</a></h2>
                                    <div>
                                        <span class="font-small text-muted">By <a href="javascripit:void(0)">{{ $val->publisher_name }}</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-lg-1-6 col-md-12 col-12 col-sm-5">
                        <h2 class="text-center">No reports found</h2>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')

@stop