@extends('Frontend.Partials.master')

@section('title')
   {{ $moduleAction }}
@stop

@section('styles')
<style type="text/css">
    .select2-container {
        max-width: 100%;
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
<div class="page-content pt-50">
    <div class="container">
        <div class="row">
            <div class="col-xl-10 col-lg-12 m-auto">
                <section class="mb-50">
                    <div class="row mb-60">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <h4 class="mb-15 text-brand">USA OFFICE</h4>
                            473 Mundet Place,<br />
                            Hillside, New Jersey,<br />
                            United States, Zip 07205
                        </div>
                        <div class="col-md-4 mb-4 mb-md-0">
                            <h4 class="mb-15 text-brand">HEADQUARTERS</h4>
                            205 North Michigan Avenue,<br/>Suite 810<br />
                            Chicago, 60601, USA
                        </div>
                        <div class="col-md-4">
                            <h4 class="mb-15 text-brand">GET IN TOUCH WITH US:</h4>
                            <abbr title="Phone">Phone:</abbr> {{ $arrSetting->site_mobile }}<br />
                            <abbr title="Email">Email: </abbr>{{ $arrSetting->site_email }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-8">
                            <div class="contact-from-area padding-20-row-col">
                                <h5 class="text-brand mb-10">Contact form</h5>
                                <h2 class="mb-10">Drop Us a Line</h2>
                                <p class="text-muted mb-30 font-sm">Your email address will not be published. Required fields are marked *</p>
                                <form name="request_form" class="contact-form-style mt-30" id="contact-form" action="{{ url('contact_us') }}" method="post" >
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="input-style mb-20">
                                                <input type="text" name="name" placeholder="Your name *" required="" oninvalid="this.setCustomValidity('Please Enter Your Name')" oninput="setCustomValidity('')">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="input-style mb-20">
                                                <input type="text" name="company_name" placeholder="Company name *" required=""  oninvalid="this.setCustomValidity('Please Enter Company Name')" oninput="setCustomValidity('')" >
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="input-style mb-20">
                                                <input type="hidden" name="country_name" id="countryName">
                                                <select class="form-control select-active" name="country" id="country" required=""  oninvalid="this.setCustomValidity('Please Select a Country')" oninput="setCustomValidity('')"  onchange="changeCode(this.value)" style="width:100%;">
                                                    <option value="">Please select a Country</option>
                                                    @if(count($arrCountry) > 0)
                                                        @foreach($arrCountry as $key => $val)
                                                            <option value="{{ $val->phonecode }}">{{ $val->nicename }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="input-style mb-20">
                                                <input type="text" name="contact_no" id="contact_no" placeholder="Contact Number *" required=""  oninvalid="this.setCustomValidity('Please Enter Contact Number')" oninput="setCustomValidity('')" >
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="input-style mb-20">
                                                <input type="text" name="email_id" placeholder="Your Email *" required=""  oninvalid="this.setCustomValidity('Please Enter Your Email Id')" oninput="setCustomValidity('')" >
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="input-style mb-20">
                                                <input type="text" name="job_title" placeholder="Job Title *" required=""  oninvalid="this.setCustomValidity('Please Enter Job Title')" oninput="setCustomValidity('')" >
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="textarea-style mb-30">
                                                <textarea name="message" name="description" id="description" placeholder="Message"></textarea>
                                            </div>
                                            <button class="submit submit-auto-width" type="submit">Send message</button>
                                        </div>
                                    </div>
                                </form>
                                <p class="form-messege"></p>
                            </div>
                        </div>
                        <div class="col-lg-4 pl-50 d-lg-block d-none">
                            
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script type="text/javascript">
    function changeCode(code) {
        $("#contact_no").val("+"+code+"-");
        $("#contact_no").focus();
        var country = $("#country option:selected").text();
        $('#countryName').val(country);
    }
</script>
@stop