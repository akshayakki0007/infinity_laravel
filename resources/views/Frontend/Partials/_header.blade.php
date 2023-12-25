<!-- Modal -->
<div class="modal fade custom-modal" id="onloadModal" tabindex="-1" aria-labelledby="onloadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="deal" style="background-image: url('{{ asset('public/Frontend/imgs/banner/popup-1.png') }}')">
                    <div class="deal-top">
                        <h6 class="mb-10 text-brand-2">Deal of the Day</h6>
                    </div>
                    <div class="deal-content detail-info">
                        <h4 class="product-title"><a href="shop-product-right.html" class="text-heading">Organic fruit for your family's health</a></h4>
                        <div class="clearfix product-price-cover">
                            <div class="product-price primary-color float-left">
                                <span class="current-price text-brand">$38</span>
                                <span>
                                    <span class="save-price font-md color3 ml-15">26% Off</span>
                                    <span class="old-price font-md ml-15">$52</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="deal-bottom">
                        <p class="mb-20">Hurry Up! Offer End In:</p>
                        <div class="deals-countdown pl-5" data-countdown="2025/03/25 00:00:00">
                            <span class="countdown-section"><span class="countdown-amount hover-up">03</span><span class="countdown-period"> days </span></span><span class="countdown-section"><span class="countdown-amount hover-up">02</span><span class="countdown-period"> hours </span></span><span class="countdown-section"><span class="countdown-amount hover-up">43</span><span class="countdown-period"> mins </span></span><span class="countdown-section"><span class="countdown-amount hover-up">29</span><span class="countdown-period"> sec </span></span>
                        </div>
                        <div class="product-detail-rating">
                            <div class="product-rate-cover text-end">
                                <div class="product-rate d-inline-block">
                                    <div class="product-rating" style="width: 90%"></div>
                                </div>
                                <span class="font-small ml-5 text-muted"> (32 rates)</span>
                            </div>
                        </div>
                        <a href="shop-grid-right.html" class="btn hover-up">Shop Now <i class="fi-rs-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<header class="header-area header-style-1 header-height-2">
    <div class="header-middle header-middle-ptb-1 d-none d-lg-block">
        <div class="container">
            <div class="header-wrap">
                <div class="logo logo-width-1">
                    <a href="{{ url('/') }}"><img src="{{ asset('public/Frontend/images/logo.png') }}" alt="logo" /></a>
                </div>
                <div class="header-right">
                    <div class="search-style-2">
                        <form action="{{url('/search')}}" method="POST">
                            @csrf
                            <input type="text" name="search_report" id="search_report" placeholder="Search for items..." />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom header-bottom-bg-color sticky-bar">
        <div class="container">
            <div class="header-wrap header-space-between position-relative">
                <div class="logo logo-width-1 d-block d-lg-none">
                    <a href="{{ url('/') }}"><img src="{{ asset('public/Frontend/images/logo.png') }}" alt="logo" /></a>
                </div>
                <div class="header-nav d-none d-lg-flex">
                    <div class="main-menu main-menu-padding-1 main-menu-lh-2 d-none d-lg-block font-heading">
                        <nav>
                            <ul>
                                <li>
                                    <a class="active" href="{{ url('/') }}">Home</a>
                                </li>
                                <li>
                                    <a href="{{ url('/about-us') }}">About Us</a>
                                </li>
                                <li>
                                    <a class="active" href="{{ url('/') }}">Industries <i class="fi-rs-angle-down"></i></a>
                                    <ul class="sub-menu">
                                        @if(count($arrCategoryObj) > 0)
                                            @foreach($arrCategoryObj as $key => $row)
                                                <li><a href="{{ url('category/'.$row->slug) }}">{{ $row->name }}</a></li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{ url('/our-services') }}">Services/What We Do</a>
                                </li>
                                <li>
                                    <a href="{{ url('/frequently-asked-questions') }}">FAQ</a>
                                </li>
                                <li>
                                    <a href="{{ url('/contact') }}">Contact Us</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="header-action-icon-2 d-block d-lg-none">
                    <div class="burger-icon burger-icon-white">
                        <span class="burger-icon-top"></span>
                        <span class="burger-icon-mid"></span>
                        <span class="burger-icon-bottom"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="mobile-header-active mobile-header-wrapper-style">
    <div class="mobile-header-wrapper-inner">
        <div class="mobile-header-top">
            <div class="mobile-header-logo">
                <a href="{{ url('/') }}"><img src="{{ asset('public/Frontend/images/logo.png') }}" alt="logo" /></a>
            </div>
            <div class="mobile-menu-close close-style-wrap close-style-position-inherit">
                <button class="close-style search-close">
                    <i class="icon-top"></i>
                    <i class="icon-bottom"></i>
                </button>
            </div>
        </div>
        <div class="mobile-header-content-area">
            <div class="mobile-search search-style-3 mobile-header-border">
                <form action="#">
                    <input type="text" placeholder="Search for items…" />
                    <button type="submit"><i class="fi-rs-search"></i></button>
                </form>
            </div>
            <div class="mobile-menu-wrap mobile-header-border">
                <!-- mobile menu start -->
                <nav>
                    <ul class="mobile-menu font-heading">
                        <li class="menu-item-has-children">
                            <a href="{{ url('/') }}">Home</a>
                        </li>
                        <ul class="mobile-menu font-heading">
                            <li class="menu-item-has-children">
                                <a href="{{ url('/') }}">Home</a>
                            </li>
                            <li class="menu-item-has-children">
                                <a href="{{ url('/about-us') }}">About Us</a>
                            </li>
                            <li class="menu-item-has-children">
                                <a href="{{ url('/our-services') }}">Services/What We Do</a>
                            </li>
                            <li class="menu-item-has-children">
                                <a href="{{ url('/frequently-asked-questions') }}">FAQ's</a>
                            </li>
                            <li class="menu-item-has-children">
                                <a href="{{ url('/contact') }}">Contact Us</a>
                            </li>
                        </ul>
                    </ul>
                </nav>
                <!-- mobile menu end -->
            </div>
            <div class="mobile-social-icon mb-50">
                <h6 class="mb-15">Follow Us</h6>
                <a href="https://www.facebook.com/Infinity-Business-Insights-352172809160429"><img src="{{ asset('public/Frontend/imgs/theme/icons/icon-facebook-white.svg') }}" alt="" /></a>
                <a href="https://twitter.com/IBInsightsLLP"><img src="{{ asset('public/Frontend/imgs/theme/icons/icon-twitter-white.svg') }}" alt="" /></a>
                <!-- <a href="#"><img src="{{ asset('public/Frontend/imgs/theme/icons/icon-instagram-white.svg') }}" alt="" /></a>
                <a href="#"><img src="{{ asset('public/Frontend/imgs/theme/icons/icon-pinterest-white.svg') }}" alt="" /></a>
                <a href="#"><img src="{{ asset('public/Frontend/imgs/theme/icons/icon-youtube-white.svg') }}" alt="" /></a> -->
            </div>
            <div class="site-copyright">{{ date('Y') }} © {{ $arrSetting->site_name }}.</div>
        </div>
    </div>
</div>