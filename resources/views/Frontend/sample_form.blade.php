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
                <form name="request_form" action="{{ url('request_form') }}" method="post" >
                    {{ csrf_field() }}
                    <input type="hidden" name="report_id" value="{{ $arrReports->id }}">
                    <input type="hidden" name="sample_type" value="{{ $sample_type }}">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <input type="text" name="name" placeholder="Your name *" required="" oninvalid="this.setCustomValidity('Please Enter Your Name')" oninput="setCustomValidity('')">
                        </div>
                        <div class="form-group col-lg-6">
                            <input type="text" name="company_name" placeholder="Company name *" required=""  oninvalid="this.setCustomValidity('Please Enter Company Name')" oninput="setCustomValidity('')" >
                        </div>
                    </div>
                    <div class="row shipping_calculator">
                        <div class="form-group col-lg-6">
                            <div class="custom_select">
                                <input type="hidden" name="country_name" id="countryName">
                                <select class="form-control select-active" name="country" id="country" required=""  oninvalid="this.setCustomValidity('Please Select a Country')" oninput="setCustomValidity('')"  onchange="changeCode(this.value)">
                                    <option value="">Please select a Country</option>
                                    @if(count($arrCountry) > 0)
                                        @foreach($arrCountry as $key => $val)
                                            <option value="{{ $val->phonecode }}">{{ $val->nicename }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-lg-6">
                            <input type="text" name="contact_no" id="contact_no" placeholder="Contact Number *" required=""  oninvalid="this.setCustomValidity('Please Enter Contact Number')" oninput="setCustomValidity('')" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <input type="text" name="email_id" placeholder="Your Email *" required=""  oninvalid="this.setCustomValidity('Please Enter Your Email Id')" oninput="setCustomValidity('')" >
                        </div>
                        <div class="form-group col-lg-6">
                            <input type="text" name="job_title" placeholder="Job Title *" required=""  oninvalid="this.setCustomValidity('Please Enter Job Title')" oninput="setCustomValidity('')" >
                        </div>
                    </div>
                    <div class="form-group mb-30">
                        <textarea name="description" placeholder="Specific Requirements *" rows="5" required=""  oninvalid="this.setCustomValidity('Please Enter Specific Requirements')" oninput="setCustomValidity('')" ></textarea>
                        <p>(Please suggests the details you wish to cover in the report)</p>
                    </div>
                    <button type="submit" class="btn btn-sm btn-default" name="submit" >Submit</button>
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

        function changeCode(code) {
            $("#contact_no").val("+"+code+"-");
            $("#contact_no").focus();
            var country = $("#country option:selected").text();
            $('#countryName').val(country);
        }
    </script>
@stop