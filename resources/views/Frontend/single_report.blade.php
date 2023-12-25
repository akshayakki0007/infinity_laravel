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
<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ url('/') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
            @if(!empty($arrReports->cat_slug))
                <span></span><a href="{{ url('/category/'.$arrReports->cat_slug) }}">{{ $arrReports->cat_slug }}</a>
                <span></span>{{ $arrReports->report_title }}
            @else
                <span></span>{{ $arrReports->report_title }}
            @endif
        </div>
    </div>
</div>
<div class="container mb-30">
    <div class="row">
        <div class="col-xl-11 col-lg-12 m-auto">
            <div class="row">
                <div class="col-xl-9">
                    <div class="product-detail accordion-detail">
                        <div class="row mb-20 mt-20">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="detail-info pr-30 pl-30">
                                    <h2 class="title-detail">{{ $arrReports->report_title }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-20 mt-20">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="detail-info pr-30 pl-30">
                                    <p class="title-detail"><strong>Report ID</strong>: {{ $arrReports->id }} | <strong>Category:</strong> {{ $arrReports->cat_name }} | <strong>Pages:</strong> {{ $arrReports->pages }} | <strong>Format:</strong> PDF | <strong>Published Date:</strong> <?php echo date('F d, Y', strtotime($arrReports->created_at)); ?></p>

                                </div>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="tab-style3">
                                <ul class="nav nav-tabs text-uppercase">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="Description-tab" data-bs-toggle="tab" href="#Description">Description</a> <!-- Report Descriptions -->
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="Additional-info-tab" data-bs-toggle="tab" href="#Additional-info">Additional info</a> <!-- View Table of Contents -->
                                    </li>
                                </ul>
                                <div class="tab-content shop_info_tab entry-main-content">
                                    <div class="tab-pane fade show active" id="Description">
                                        <div class="">
                                            <?php 
                                                echo stripcslashes($arrReports->description);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="Additional-info">
                                        <?php 
                                            echo stripcslashes($arrReports->toc);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-60">
                            <div class="col-12">
                                <h2 class="section-title style-1 mb-30">Related products</h2>
                            </div>
                            <div class="col-12">
                                <div class="row related-products">
                                    @if(!empty($arrRelatedReports))
                                        @foreach($arrRelatedReports as $key => $val)
                                            <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                                <div class="product-cart-wrap hover-up">
                                                    <div class="product-content-wrap">
                                                        @if(!empty($arrReports->cat_name))
                                                            <div class="product-category">
                                                                <a href="{{ url('/category/'.$arrReports->cat_slug) }}">{{ $arrReports->cat_name }}</a>
                                                            </div>
                                                        @endif
                                                        <h2><a href="{{ url('/report/'.$val->slug.'-'.$val->id) }}" tabindex="0">{{ $val->report_title }}</a></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 primary-sidebar sticky-sidebar mt-30">
                    <div class="sidebar-widget font-heading widget-category-2 mb-30">
                        <h5 class="section-title style-1 mb-30"></h5>
                        <ul>
                            <li>
                                <a href="{{ url('request_sample.php?id='.$arrReports->id) }}"> Request Sample</a>
                            </li>
                            <li>
                                <a href="{{ url('enquiry_before_buying.php?id='.$arrReports->id) }}"> Enquiry before buying </a>
                            </li>
                            <li>
                                <a href="{{ url('ask_for_discount.php?id='.$arrReports->id) }}"> Ask For Discount </a>
                            </li>
                        </ul>
                    </div>
                    <!-- Price -->
                    <div class="sidebar-widget range mb-30">
                        <h5 class="section-title style-1 mb-30">Prices</h5>
                        <div class="list-group">
                            <div class="list-group-item mb-10 mt-10">
                                <div class="custome-radio">
                                    <input class="form-check-input" type="radio" name="payment_type" id="singleUser" value="{{ url('/checkout?id='.$arrReports->id.'&price='.$arrReports->single_user_price)  }}" checked />
                                    <label class="form-check-label mb-10 mt-10" for="singleUser"><span>Single User - <strong id="slider-range-value1" class="text-brand">$<?php echo number_format($arrReports->single_user_price, 2, '.', ''); ?></strong></span></label>
                                    <input class="form-check-input" type="radio" name="payment_type" id="multiUser" value="{{ url('/checkout?id='.$arrReports->id.'&price='.$arrReports->multi_user_price)  }}" />
                                    <label class="form-check-label" for="multiUser"><span>Multi User - <strong id="slider-range-value1" class="text-brand">$<?php echo number_format($arrReports->multi_user_price, 2, '.', ''); ?></strong></span></label>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-default" id="buynowbtn">Buy Now<i class="fi-rs-sign-out ml-15"></i></button>
                    </div>

                    <!-- Offer -->
                    <div class="banner-img wow fadeIn mb-lg-0 animated d-lg-block d-none">
                        <img src="{{ asset('/public/Frontend/imgs/banner/banner-11.png') }}" alt="" />
                        <div class="banner-text">
                            <span>Oganic</span>
                            <h4>
                                Save 17% <br />
                                on <span class="text-brand">Oganic</span><br />
                                Juice
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script type="text/javascript">
    $("#buynowbtn").click(function() {     
        window.location.replace($("input[name=payment_type]:checked").val());
    });
</script>
@stop