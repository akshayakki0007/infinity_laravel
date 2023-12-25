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

    .paymentImg {
        width: 25%;
        position: relative;
        top: 15px;
    }

    .custome-radio input[type="radio"] + .form-check-label.stripLabel::after {
        content: "";
        height: 10px;
        width: 10px;
        border-radius: 100%;
        position: absolute;
        top: 27px;
        left: 4px;
        opacity: 0;
    }
    .custome-radio input[type="radio"]:checked + .form-check-label.stripLabel::after {
        opacity: 1;
        background-color: #3BB77E;
    }

    .custome-radio input[type="radio"] + .form-check-label.paypalLabel::after {
        content: "";
        height: 10px;
        width: 10px;
        border-radius: 100%;
        position: absolute;
        top: 53px;
        left: 4px;
        opacity: 0;
    }

    .custome-radio input[type="radio"]:checked + .form-check-label.paypalLabel::after {
        opacity: 1;
        background-color: #3BB77E;
    }
</style>
@stop

@section('content')
<?php 
    $arrStripeSetting = json_decode($siteSetting->stripe_setting);
?>
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
    <form onsubmit="return actionSave(this)" action="{{ url('checkout_form') }}" method="post" >
        {{ csrf_field() }}
        <div class="row">
            <div class="col-xl-9">
                <div class="row">
                    <h3 class="mb-10">{{ $arrReports->report_title }}</h3>
                    <p class="report_sub_details mb-20 mt-20">
                        <strong>Report ID</strong>: {{ $arrReports->id }} | <strong>Category:</strong> {{ $arrReports->cat_name }} | <strong>Pages:</strong> {{ $arrReports->pages }} | <strong>Format:</strong> PDF | <strong>Published Date:</strong> <?php echo date('F d, Y', strtotime($arrReports->created_at)); ?>
                    </p>
                    <p>Thank you for choosing <strong>{{ $arrReports->report_title }}</strong>. Please fill below form to be in touch with us.</p>
                    <input type="hidden" name="discount" value="{{ $discount }}">
                    <input type="hidden" name="report_id" value="{{ $arrReports->id }}">
                    <input type="hidden" name="licence_price" value="{{ $price }}">
                    <input type="hidden" name="report_title" value="{{ $arrReports->report_title }}">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <input type="text" name="name" placeholder="Your name *" required="" oninvalid="this.setCustomValidity('Please Enter Your Name')" oninput="setCustomValidity('')" >
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
                            <input type="text" name="city" placeholder="Your City *" required=""  oninvalid="this.setCustomValidity('Please Enter City')" oninput="setCustomValidity('')" value="" >
                        </div>
                        <div class="form-group col-lg-6">
                            <input type="text" name="email_id" placeholder="Your Email *" required=""  oninvalid="this.setCustomValidity('Please Enter Your Email Id')" oninput="setCustomValidity('')" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <input type="text" name="zip_code" placeholder="Your Zip code *" required=""  oninvalid="this.setCustomValidity('Please Enter Your Zip code')" oninput="setCustomValidity('')" >
                        </div>
                        <div class="form-group col-lg-6">
                            <input type="text" name="job_title" placeholder="Job Title *" required=""  oninvalid="this.setCustomValidity('Please Enter Job Title')" oninput="setCustomValidity('')">
                        </div>
                    </div>
                    <div class="form-group mb-30">
                        <div class="form-group col-lg-6">
                            <div class="custom_select">
                                <?php 
                                    if($discount != 0 && $discount <= $siteSetting->discount_amt)
                                    {
                                        $intSingleUserPrice = $arrReports->single_user_price-($arrReports->single_user_price*$discount/100);
                                        $intMultiUserPrice = $arrReports->multi_user_price-($arrReports->multi_user_price*$discount/100);

                                        $singleUserPrice = $intSingleUserPrice;
                                        $multiUserPrice = $intMultiUserPrice;

                                        $singleUserPriceText = 'Single user - Original Price ($'.$arrReports->single_user_price.') - Discounted Price ($'.$intSingleUserPrice.')';
                                        $multiUserPriceText = 'Multi User - Original Price ($'.$arrReports->multi_user_price.') - Discounted Price ($'.$intMultiUserPrice.')';
                                    }
                                    else
                                    {
                                        $singleUserPrice = $arrReports->single_user_price;
                                        $multiUserPrice = $arrReports->multi_user_price;

                                        $singleUserPriceText = 'Single user - ($'.$arrReports->single_user_price.')';
                                        $multiUserPriceText = 'Multi User - ($'.$arrReports->multi_user_price.')';
                                    }
                                ?>
                                <select class="form-control " name="licence_type" id="licence_type" required=""  oninvalid="this.setCustomValidity('Please Select a Licence Type')" oninput="setCustomValidity('')">
                                    <option value="">Please select a Licence Type</option>
                                    <option value="<?php echo $singleUserPrice; ?>" <?php if($price == $singleUserPrice){ echo "selected='selected'"; } ?> ><?php echo $singleUserPriceText;?></option>
                                    <option value="<?php echo $multiUserPrice;?>" <?php if($price == $multiUserPrice){ echo "selected='selected'"; } ?> ><?php echo $multiUserPriceText;?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 primary-sidebar sticky-sidebar mt-30">
                <!-- Price -->
                <div class="sidebar-widget range mb-10">
                    <h5 class="section-title style-1 mb-10">Make Payment Using</h5>
                    <div class="list-group">
                        <div class="list-group-item mb-5">
                            <div class="custome-radio">
                                <input class="form-check-input" type="radio" name="payment_type" id="stripe" value="stripe" checked  />
                                <label class="form-check-label stripLabel" for="stripe"><span><img src="{{ url('public/Frontend/images/stripe.png') }}" class="paymentImg"></span></label><br/>

                                <input class="form-check-input" type="radio" name="payment_type" id="paypal" value="paypal"  />
                                <label class="form-check-label paypalLabel mb-10" for="paypal"><span><img src="{{ url('public/Frontend/images/paypal.png') }}" class="paymentImg"></span></label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-default" id="btnBuyNow">Buy Now<i class="fi-rs-sign-out ml-15"></i></button>
                </div>
            </div>
        </div>
    </form>
</div>
@stop

@section('scripts')
<script type="text/javascript">
    var publish_key = '{{ $arrStripeSetting->publish_key }}';
    
    var stripe = Stripe(publish_key); 

    function changeCode(code)
    {
        $("#contact_no").val("+"+code+"-");
        $("#contact_no").focus();
        var country = $("#country option:selected").text();
        $('#countryName').val(country);
    }
    
    function actionSave(element)
    {
        var $this    = $(element);                 
        var formData = new FormData($this[0]);  
        var action   = $this.attr('action');
        
        $('#btnBuyNow').hide();
        $('#preloader-active').show();

        $.ajax({
            type: 'POST',
            url: action,
            data: formData,
            processData: false,
            contentType: false,
            success: function(data)
            {
                if (data.status == 'success') 
                {
                    $('#btnBuyNow').show();
                    $('#preloader-active').hide();
                    if(data.payment_type == 'paypal')
                    {
                        window.location.href = data.url;
                    }
                    else
                    {
                        
                        return stripe.redirectToCheckout({ sessionId: data.session_id });
                    }
                }
                else
                {
                    $('#btnBuyNow').show();
                    toastr["error"](data.msg);
                }
            },
            error: function (data)
            {
                $('#btnBuyNow').show();

                toastr.clear();
                if( data.status === 422 ) 
                {
                    var errorBag = $.parseJSON(data.responseText);
                    if (errorBag) 
                    {
                        var x = 0;
                        $.each(errorBag.errors, function(row, fields)
                        {
                            if (x == 0) 
                            {
                                toastr["error"](fields);
                            }
                            x++;
                        });
                    }
                }
                else
                {
                    toastr["error"]('Something went wrong on server, Please try again later.');
                }
            }
        });
    
        return false;
    }
</script>
@stop