<footer class="main">
    <section class="section-padding footer-mid">
        <div class="container pt-15 pb-20">
            <div class="row">
                <div class="col">
                    <div class="widget-about font-md mb-md-3 mb-lg-3 mb-xl-0 wow animate__animated animate__fadeInUp" data-wow-delay="0">
                        <div class="logo mb-30 footerImg">
                            <a href="{{ url('/') }}" class="mb-15"><img src="{{ asset('public/Frontend/images/logo.png') }}" alt="logo"/></a>
                            <p class="font-lg text-heading">{{ $arrSetting->site_name }}</p>
                        </div>
                        <ul class="contact-infor">
                            <li><img src="{{ asset('public/Frontend/imgs/theme/icons/icon-location.svg') }}" alt="" /><strong>Address: </strong> <span><?php echo stripcslashes($arrSetting->site_address); ?></span></li>
                            <li><img src="{{ asset('public/Frontend/imgs/theme/icons/icon-contact.svg') }}" alt="" /><strong>Call Us:</strong><span>{{ $arrSetting->site_mobile }}</span></li>
                            <li><img src="{{ asset('public/Frontend/imgs/theme/icons/icon-email-2') }}.svg" alt="" /><strong>Email:</strong><span>{{ $arrSetting->site_email }}</span></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-link-widget col wow animate__animated animate__fadeInUp" data-wow-delay=".2s">
                    <h4 class="widget-title">Industries</h4>
                    <ul class="footer-list mb-sm-5 mb-md-0">
                        @if(count($arrCategoryObj) > 0)
                            @foreach($arrCategoryObj as $key => $row)
                                <li><a href="{{ url('category/'.$row->slug) }}">{{ $row->name }}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="footer-link-widget col wow animate__animated animate__fadeInUp" data-wow-delay=".3s">
                    <h4 class="widget-title">Policies</h4>
                    <ul class="footer-list mb-sm-5 mb-md-0">
                        <li><a href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ url('/refund-policy') }}">Return Policy</a></li>
                        <li><a href="{{ url('/disclaimer') }}">Disclaimer</a></li>
                        <li><a href="{{ url('/terms-and-conditions') }}">Terms and Condition</a></li>
                    </ul>
                </div>
                <div class="footer-link-widget col wow animate__animated animate__fadeInUp" data-wow-delay=".4s">
                    <h4 class="widget-title">About</h4>
                    <ul class="footer-list mb-sm-5 mb-md-0">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('/about-us') }}">About Us</a></li>
                        <li><a href="{{ url('/our-services') }}">Services</a></li>
                        <li><a href="{{ url('/all-reports') }}">Reports</a></li>
                        <li><a href="{{ url('/sitemap.xml') }}">XML Sitemap</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="container pb-30 wow animate__animated animate__fadeInUp" data-wow-delay="0">
        <div class="row align-items-center">
            <div class="col-12 mb-30">
                <div class="footer-bottom"></div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6">
                <p class="font-sm mb-0">&copy; {{ date('Y') }} <strong class="text-brand">{{ $arrSetting->site_name }}.</strong><br />All rights reserved</p>
            </div>
            <div class="col-xl-4 col-lg-6 text-center d-none d-xl-block">
                <div class="hotline d-lg-inline-flex">
                    <img src="{{ asset('public/Frontend/imgs/theme/icons/phone-call.svg') }}" alt="hotline" />
                    <p>{{ $arrSetting->site_mobile }}</p>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 text-end d-none d-md-block">
                <div class="mobile-social-icon">
                    <h6>Follow Us</h6>
                    <a href="https://www.facebook.com/Infinity-Business-Insights-352172809160429"><img src="{{ asset('public/Frontend/imgs/theme/icons/icon-facebook-white') }}.svg" alt="" /></a>
                    <a href="https://twitter.com/IBInsightsLLP"><img src="{{ asset('public/Frontend/imgs/theme/icons/icon-twitter-white') }}.svg" alt="" /></a>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Preloader Start -->
<div id="preloader-active">
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
            <div class="text-center">
                <img src="{{ asset('public/Frontend/imgs/theme/Spinner.gif') }}" alt=""  />
            </div>
        </div>
    </div>
</div>