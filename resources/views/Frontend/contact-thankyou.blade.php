@extends('Frontend.Partials.master')

@section('title')
   {{ $moduleAction }}
@stop

@section('styles')
<style type="text/css">
    .thankyouText
    {
        font-size: 50px;
    }
</style>
@stop

@section('content')
<div class="page-content pt-100 pb-150">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-10 col-md-12 m-auto text-center">
                <div class="product-info">
                    <h2 class="display-2 mb-30 thankyouText">Thanks for being awesome!</h2>
                    <p class="font-lg text-grey-700 mb-30">
                        We have received your message and would like to thank you for writing to us. If your inquiry is urgent, please use the telephone number listed below to talk to one of our staff members. Otherwise, we will reply by email as soon as possible.<br>Talk to you soon,<br><br>
                    </p>
                    <h3>{{ $siteSetting->site_name }}</h3>
                    <a class="btn btn-default submit-auto-width font-xs hover-up mt-30" href="{{ url('/') }}"><i class="fi-rs-home mr-5"></i> Back To Home Page</a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')

@stop