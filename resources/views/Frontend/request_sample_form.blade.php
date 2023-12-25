@extends('Frontend.Partials.master')

@section('title')
   {{ $moduleAction }}
@stop

@section('styles')
<style type="text/css">
    p.report_sub_details {
        font-size: 18px;
    }
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
                <span></span><a href="{{ url('/report/'.$arrReports->slug.'-'.$arrReports->id) }}">{{ $arrReports->report_title }}</a>
            @else
                <span></span>{{ $arrReports->report_title }}
            @endif
            <span></span>{{ $moduleTitle }}
        </div>
    </div>
</div>
<div class="container mb-80 mt-50">
    <div class="row">
        <div class="col-xl-9">
            <div class="row">
                <h3 class="mb-10">{{ $arrReports->report_title }}</h3>
                <p class="report_sub_details mb-20 mt-20">
                    <strong>Report ID</strong>: {{ $arrReports->id }} | <strong>Category:</strong> {{ $arrReports->cat_name }} | <strong>Pages:</strong> {{ $arrReports->pages }} | <strong>Format:</strong> PDF | <strong>Published Date:</strong> <?php echo date('F d, Y', strtotime($arrReports->created_at)); ?>
                </p>
                <form method="post">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <input type="text" required="" name="fname" placeholder="First name *">
                        </div>
                        <div class="form-group col-lg-6">
                            <input type="text" required="" name="lname" placeholder="Last name *">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <input type="text" name="billing_address" required="" placeholder="Address *">
                        </div>
                        <div class="form-group col-lg-6">
                            <input type="text" name="billing_address2" required="" placeholder="Address line2">
                        </div>
                    </div>
                    <div class="row shipping_calculator">
                        <div class="form-group col-lg-6">
                            <div class="custom_select">
                                <select class="form-control select-active">
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-lg-6">
                            <input required="" type="text" name="city" placeholder="City / Town *">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <input required="" type="text" name="zipcode" placeholder="Postcode / ZIP *">
                        </div>
                        <div class="form-group col-lg-6">
                            <input required="" type="text" name="phone" placeholder="Phone *">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <input required="" type="text" name="cname" placeholder="Company Name">
                        </div>
                        <div class="form-group col-lg-6">
                            <input required="" type="text" name="email" placeholder="Email address *">
                        </div>
                    </div>
                    <div class="form-group mb-30">
                        <textarea rows="5" placeholder="Additional information"></textarea>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-xl-3 primary-sidebar sticky-sidebar mt-30">
            <!-- Price -->
            <div class="sidebar-widget range mb-30">
                <h5 class="section-title style-1 mb-30">Prices</h5>
                <div class="list-group">
                    <div class="list-group-item mb-10 mt-10">
                        <div class="custome-radio">
                            <input class="form-check-input" type="radio" name="payment_type" id="singleUser" value="{{ url('/checkout?id='.$arrReports->id.'&'.$arrReports->single_user_price)  }}" checked />
                            <label class="form-check-label mb-10 mt-10" for="singleUser"><span>Single User - <strong id="slider-range-value1" class="text-brand">$<?php echo number_format($arrReports->single_user_price, 2, '.', ''); ?></strong></span></label><br/>
                            <input class="form-check-input" type="radio" name="payment_type" id="multiUser" value="{{ url('/checkout?id='.$arrReports->id.'&'.$arrReports->multi_user_price)  }}" />
                            <label class="form-check-label" for="multiUser"><span>Multi User - <strong id="slider-range-value1" class="text-brand">$<?php echo number_format($arrReports->multi_user_price, 2, '.', ''); ?></strong></span></label>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-default" id="buynowbtn">Buy Now<i class="fi-rs-sign-out ml-15"></i></button>
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