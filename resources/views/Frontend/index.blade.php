@extends('Frontend.Partials.master')

@section('title')
   {{ $moduleAction }}
@stop

@section('styles')

@stop

@section('content')
<main class="main">
  <section class="home-slider position-relative mb-30">
      <div class="container">
          <div class="home-slide-cover mt-30">
              <div class="hero-slider-1 style-4 dot-style-1 dot-style-1-position-1">
                  <div class="single-hero-slider single-animation-wrap" style="background-image: url({{ asset('public/Frontend/imgs/slider/slider-1.png') }} )">
                      <div class="slider-content">
                          <h1 class="display-2 mb-40">
                              Don’t miss amazing<br />
                              grocery deals
                          </h1>
                          <p class="mb-65">Sign up for the daily newsletter</p>
                      </div>
                  </div>
                  <div class="single-hero-slider single-animation-wrap" style="background-image: url({{ asset('public/Frontend/imgs/slider/slider-2.png') }} )">
                      <div class="slider-content">
                          <h1 class="display-2 mb-40">
                              Fresh Vegetables<br />
                              Big discount
                          </h1>
                          <p class="mb-65">Save up to 50% off on your first order</p>
                      </div>
                  </div>
              </div>
              <div class="slider-arrow hero-slider-1-arrow"></div>
          </div>
      </div>
  </section>
  <!--End hero slider-->
  <section class="popular-categories section-padding">
      <div class="container wow animate__animated animate__fadeIn">
          <div class="section-title">
              <div class="title">
                  <h3>Featured Categories</h3>
                  @if(count($arrCategory) > 0)
                    @foreach($arrCategory as $key => $row)
                      <ul class="list-inline nav nav-tabs links">
                        <li class="list-inline-item nav-item">
                          <a class="nav-link <?php if($key == 0){ echo 'active'; } ?>" href="{{ url('/category/'.$row->slug) }}">{{ $row->name }}</a>
                        </li>
                        <?php 
                          if($key == 3)
                          {
                            break;
                          }
                        ?>
                      </ul>
                    @endforeach
                  @endif
              </div>
              <div class="slider-arrow slider-arrow-2 flex-right carausel-10-columns-arrow" id="carausel-10-columns-arrows"></div>
          </div>
          <div class="carausel-10-columns-cover position-relative">
              <div class="carausel-10-columns" id="carausel-10-columns">
                @if(count($arrCategory) > 0)
                  <?php 
                    $intColorCnt = 9;
                  ?>
                  @foreach($arrCategory as $key => $row)
                    <div class="card-2 bg-{{ $intColorCnt }} wow animate__animated animate__fadeInUp" data-wow-delay="<?php echo '.'.$key.'s'; ?>">
                        <h6><a href="{{ url('/category/'.$row->slug) }}">{{ $row->name }}</a></h6>
                        <span><?php echo getCategoryReportCount($row->id); ?> items</span>
                    </div>
                  <?php 
                    $intColorCnt++;

                    if($intColorCnt == 11)
                    {
                      $intColorCnt = 9;
                    }
                  ?>
                  @endforeach
                @endif

              </div>
          </div>
      </div>
  </section>
  <!--End category slider-->
  <section class="banners mb-25">
      <div class="container">
          <div class="row">
              <div class="col-lg-4 col-md-6">
                  <div class="banner-img wow animate__animated animate__fadeInUp" data-wow-delay="0">
                      <img src="{{ asset('public/Frontend/imgs/banner/banner-1.png') }} " alt="" />
                      <div class="banner-text">
                          <h4>
                              Everyday Fresh & <br />Clean with Our<br />
                              Products
                          </h4>
                          <a href="{{ url('/') }}" class="btn btn-xs">Shop Now <i class="fi-rs-arrow-small-right"></i></a>
                      </div>
                  </div>
              </div>
              <div class="col-lg-4 col-md-6">
                  <div class="banner-img wow animate__animated animate__fadeInUp" data-wow-delay=".2s">
                      <img src="{{ asset('public/Frontend/imgs/banner/banner-2.png') }} " alt="" />
                      <div class="banner-text">
                          <h4>
                              Make your Breakfast<br />
                              Healthy and Easy
                          </h4>
                          <a href="{{ url('/') }}" class="btn btn-xs">Shop Now <i class="fi-rs-arrow-small-right"></i></a>
                      </div>
                  </div>
              </div>
              <div class="col-lg-4 d-md-none d-lg-flex">
                  <div class="banner-img mb-sm-0 wow animate__animated animate__fadeInUp" data-wow-delay=".4s">
                      <img src="{{ asset('public/Frontend/imgs/banner/banner-3.png') }} " alt="" />
                      <div class="banner-text">
                          <h4>The best Organic <br />Products Online</h4>
                          <a href="{{ url('/') }}" class="btn btn-xs">Shop Now <i class="fi-rs-arrow-small-right"></i></a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </section>
  <!--End banners-->
  <section class="product-tabs section-padding position-relative">
      <div class="container">
        <div class="section-title style-2 wow animate__animated animate__fadeIn">
          <h3>Popular Products</h3>
          <ul class="nav nav-tabs links" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="nav-tab-one" data-bs-toggle="tab" data-bs-target="#tab-one" type="button" role="tab" aria-controls="tab-one" aria-selected="true">All</button>
              </li>
              @if(count($arrCategory) > 0)
                @foreach($arrCategory as $key => $row)
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="nav-tab-two" data-bs-toggle="tab" data-bs-target="#tab-{{ $key }}" type="button" role="tab" aria-controls="tab-{{ $key }}" aria-selected="false">{{ $row->name }}</button>
                    </li>
                    <?php 
                      if($key == 4)
                      {
                        break;
                      }
                    ?>
                @endforeach
              @endif
          </ul>
        </div>
        <!--End nav-tabs-->
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
            <div class="row product-grid-4">
              @if(count($arrReports) > 0)
                @foreach($arrReports as $key => $val)
                  <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                    <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".1s">
                      <div class="product-content-wrap">
                        @if(!empty($val->cat_name))
                          <div class="product-category">
                            <a href="{{ url('/category/'.$val->cat_slug) }}">{{ $val->cat_name }}</a>
                          </div>
                        @endif
                        <h2><a href="{{ url('/report/'.$val->report_slug.'-'.$val->id) }}">{{ $val->report_title }}</a></h2>
                        @if(!empty($val->publisher_name))
                          <div>
                            <span class="font-small text-muted">By <a href="javascript:voide(0)">{{ $val->publisher_name }}</a></span>
                          </div>
                        @endif
                      </div>
                    </div>
                  </div>
                @endforeach
              @endif
            </div>
          </div>
          @if(count($arrCategory) > 0)
            @foreach($arrCategory as $key => $row)
              <div class="tab-pane fade" id="tab-{{ $key }}" role="tabpanel" aria-labelledby="tab-{{ $key }}">
                <div class="row product-grid-4">
                  <?php 
                    $arrReportObj = getCategoryReports($row->id);
                  ?>
                  @if(count($arrReportObj) > 0)
                    @foreach($arrReportObj as $key => $rVal)
                      <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                        <div class="product-cart-wrap mb-30" >
                          <div class="product-content-wrap">
                            @if(!empty($rVal->cat_name))
                              <div class="product-category">
                                <a href="{{ url('/category/'.$rVal->cat_slug) }}">{{ $rVal->cat_name }}</a>
                              </div>
                            @endif
                            <h2><a href="{{ url('/report/'.$rVal->report_slug.'-'.$rVal->id) }}">{{ $rVal->report_title }}</a></h2>
                            @if(!empty($rVal->publisher_name))
                              <div>
                                <span class="font-small text-muted">By <a href="javascript:voide(0)">{{ $rVal->publisher_name }}</a></span>
                              </div>
                            @endif
                          </div>
                        </div>
                      </div>
                    @endforeach
                  @else
                    <div class="col-lg-1-5 col-md-12 col-12 col-sm-12">
                      <h4>No report found</h4>
                    </div>
                  @endif
                </div>
              </div>
              <?php if($key == 4){ break;} ?>
            @endforeach
          @endif
        </div>
        <!--End tab-content-->
      </div>
  </section>
  <!--End Best Sales-->
  <section class="section-padding mb-30">
      <div class="container">
          <div class="row">
              <div class="col-xl-3 col-lg-4 col-md-6 mb-sm-5 mb-md-0 wow animate__animated animate__fadeInUp" data-wow-delay="0">
                  <h4 class="section-title style-1 mb-30 animated animated">Top Selling</h4>
                  <div class="product-list-small animated animated">
                    @if(count($arrTopSelling) > 0)
                      @foreach($arrTopSelling as $key => $val)
                        <article class="row align-items-center hover-up">
                            <div class="col-md-8 mb-0">
                                <h6>
                                    <a href="{{ url('/report/'.$val->report_slug.'-'.$val->id) }}">{{ $val->report_title }}</a>
                                </h6>
                                <div class="product-category">
                                  <a href="{{ url('/category/'.$val->cat_slug) }}">{{ $val->cat_name }}</a>
                                </div>
                            </div>
                        </article>
                      @endforeach
                    @endif
                  </div>
              </div>
              <div class="col-xl-3 col-lg-4 col-md-6 mb-md-0 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                  <h4 class="section-title style-1 mb-30 animated animated">Trending Products</h4>
                  <div class="product-list-small animated animated">
                      @if(count($arrTopTrending) > 0)
                        @foreach($arrTopTrending as $key => $val)
                          <article class="row align-items-center hover-up">
                              <div class="col-md-8 mb-0">
                                  <h6>
                                      <a href="{{ url('/report/'.$val->report_slug.'-'.$val->id) }}">{{ $val->report_title }}</a>
                                  </h6>
                                  <div class="product-category">
                                    <a href="{{ url('/category/'.$val->cat_slug) }}">{{ $val->cat_name }}</a>
                                  </div>
                              </div>
                          </article>
                        @endforeach
                      @endif
                  </div>
              </div>
              <div class="col-xl-3 col-lg-4 col-md-6 mb-sm-5 mb-md-0 d-none d-lg-block wow animate__animated animate__fadeInUp" data-wow-delay=".2s">
                  <h4 class="section-title style-1 mb-30 animated animated">Recently added</h4>
                  <div class="product-list-small animated animated">
                      @if(count($arrRecenAdded) > 0)
                        @foreach($arrRecenAdded as $key => $val)
                          <article class="row align-items-center hover-up">
                              <div class="col-md-8 mb-0">
                                  <h6>
                                      <a href="{{ url('/report/'.$val->report_slug.'-'.$val->id) }}">{{ $val->report_title }}</a>
                                  </h6>
                                  <div class="product-category">
                                    <a href="{{ url('/category/'.$val->cat_slug) }}">{{ $val->cat_name }}</a>
                                  </div>
                              </div>
                          </article>
                        @endforeach
                      @endif
                  </div>
              </div>
              <div class="col-xl-3 col-lg-4 col-md-6 mb-sm-5 mb-md-0 d-none d-xl-block wow animate__animated animate__fadeInUp" data-wow-delay=".3s">
                  <h4 class="section-title style-1 mb-30 animated animated">Top Rated</h4>
                  <div class="product-list-small animated animated">
                      @if(count($arrTopRated) > 0)
                        @foreach($arrTopRated as $key => $val)
                          <article class="row align-items-center hover-up">
                              <div class="col-md-8 mb-0">
                                  <h6>
                                      <a href="{{ url('/report/'.$val->report_slug.'-'.$val->id) }}">{{ $val->report_title }}</a>
                                  </h6>
                                  <div class="product-category">
                                    <a href="{{ url('/category/'.$val->cat_slug) }}">{{ $val->cat_name }}</a>
                                  </div>
                              </div>
                          </article>
                        @endforeach
                      @endif
                  </div>
              </div>
          </div>
      </div>
  </section>
  <!--End 4 columns-->
  <section class="newsletter mb-15 wow animate__animated animate__fadeIn">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="position-relative newsletter-inner">
                    <div class="newsletter-content">
                        <h2 class="mb-20">
                            Stay home & get your daily <br />
                            needs from our shop
                        </h2>
                        <p class="mb-45">Start You'r Daily Shopping with <span class="text-brand">Nest Mart</span></p>
                    </div>
                    <img src="{{ asset('public/Frontend/imgs/banner/banner-9.png') }}" alt="newsletter" />
                </div>
            </div>
        </div>
    </div>
  </section>
  <section class="featured section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-1-5 col-md-4 col-12 col-sm-6 mb-md-4 mb-xl-0">
                <div class="banner-left-icon d-flex align-items-center wow animate__animated animate__fadeInUp" data-wow-delay="0">
                    <div class="banner-icon">
                        <img src="{{ asset('public/Frontend/imgs/theme/icons/icon-1.svg') }}" alt="" />
                    </div>
                    <div class="banner-text">
                        <h3 class="icon-box-title">Best prices & offers</h3>
                        <p>Orders $50 or more</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                <div class="banner-left-icon d-flex align-items-center wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                    <div class="banner-icon">
                        <img src="{{ asset('public/Frontend/imgs/theme/icons/icon-2.svg') }}" alt="" />
                    </div>
                    <div class="banner-text">
                        <h3 class="icon-box-title">Free delivery</h3>
                        <p>24/7 amazing services</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                <div class="banner-left-icon d-flex align-items-center wow animate__animated animate__fadeInUp" data-wow-delay=".2s">
                    <div class="banner-icon">
                        <img src="{{ asset('public/Frontend/imgs/theme/icons/icon-3.svg') }}" alt="" />
                    </div>
                    <div class="banner-text">
                        <h3 class="icon-box-title">Great daily deal</h3>
                        <p>When you sign up</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                <div class="banner-left-icon d-flex align-items-center wow animate__animated animate__fadeInUp" data-wow-delay=".3s">
                    <div class="banner-icon">
                        <img src="{{ asset('public/Frontend/imgs/theme/icons/icon-4.svg') }}" alt="" />
                    </div>
                    <div class="banner-text">
                        <h3 class="icon-box-title">Wide assortment</h3>
                        <p>Mega Discounts</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                <div class="banner-left-icon d-flex align-items-center wow animate__animated animate__fadeInUp" data-wow-delay=".4s">
                    <div class="banner-icon">
                        <img src="{{ asset('public/Frontend/imgs/theme/icons/icon-5.svg') }}" alt="" />
                    </div>
                    <div class="banner-text">
                        <h3 class="icon-box-title">Easy returns</h3>
                        <p>Within 30 days</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-1-5 col-md-4 col-12 col-sm-6 d-xl-none">
                <div class="banner-left-icon d-flex align-items-center wow animate__animated animate__fadeInUp" data-wow-delay=".5s">
                    <div class="banner-icon">
                        <img src="{{ asset('public/Frontend/imgs/theme/icons/icon-6.svg') }}" alt="" />
                    </div>
                    <div class="banner-text">
                        <h3 class="icon-box-title">Safe delivery</h3>
                        <p>Within 30 days</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </section>
 </main>
@stop

@section('scripts')
@stop