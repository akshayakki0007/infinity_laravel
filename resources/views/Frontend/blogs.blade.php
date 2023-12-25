@extends('Frontend.Partials.master')

@section('title')
   {{ $moduleAction }}
@stop

@section('styles')
@stop

@section('content')
<div class="page-header mt-30 mb-75">
   <div class="container">
       <div class="archive-header">
           <div class="row align-items-center">
               <div class="col-xl-3">
                   <h1 class="mb-15">Blogs</h1>
                   <div class="breadcrumb">
                       <a href="{{ url('/') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                       <span></span> Blogs
                   </div>
               </div>
           </div>
       </div>
   </div>
</div>
<div class="page-content mb-50">
   <div class="container">
       <div class="row">
           <div class="col-lg-12">
               <div class="shop-product-fillter mb-50">
                   <div class="totall-product">
                       <h2>
                           <img class="w-36px mr-10" src="assets/imgs/theme/icons/category-1.svg" alt="" />
                           Recips Articles
                       </h2>
                   </div>
               </div>
               <div class="loop-grid">
                   <div class="row">
                        @if(count($arrBlogs) > 0)
                            @foreach($arrBlogs as $key => $val)
                                <article class="col-xl-3 col-lg-4 col-md-6 text-center hover-up mb-30 animated">
                                   <div class="entry-content-2">
                                        <h6 class="mb-10 font-sm">
                                            @if(!empty($val->category))
                                                <a class="entry-meta text-muted" href="{{ url('/category/'.$val->category->slug) }}">{{ $val->category->name }}</a>
                                            @endif
                                        </h6>
                                        <h5 class="post-title mb-15">
                                           <a href="{{ url('/blog/'.$val->title) }}">{{ $val->title }}</a>
                                        </h5>
                                        <div class="entry-meta font-xs color-grey mt-10 pb-10">
                                           <div>
                                               <span class="post-on mr-10"><?php echo date('d F Y', strtotime($val->publish_date)); ?></span>
                                               <span class="hit-count has-dot">by {{ $val->author }}</span>
                                           </div>
                                        </div>
                                   </div>
                                </article>
                           @endforeach
                        @endif
                   </div>
               </div>
           </div>
       </div>
   </div>
</div>
@stop

@section('scripts')

@stop