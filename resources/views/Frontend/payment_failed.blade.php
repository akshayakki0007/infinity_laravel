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
                        Your payment is failed. If it has happened by mistake, please contact us.<br/>
                        <a href="tel:+15183003575">+1 518 300 3575</a>
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