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
               <div class="col-xl-10">
                   <h1 class="mb-15">Blogs</h1>
                   <div class="breadcrumb">
                       <a href="{{ url('/') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                       <span></span> <a href="{{ url('/blogs') }}" rel="nofollow">Blogs</a>
                       <span></span> {{ $arrBlog->title }}
                   </div>
               </div>
           </div>
       </div>
   </div>
</div>
<div class="page-content mb-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 m-auto">
                <div class="single-page pt-50 pr-30">
                    <div class="single-header style-2">
                        <div class="row">
                            <div class="col-xl-10 col-lg-12 m-auto">
                                @if(!empty($val->category))
                                    <h6 class="mb-10"><a href="{{ url('/category/'.$val->category->slug) }}">{{ $val->category->name }}</a></h6>
                                @endif
                                <h2 class="mb-10">{{ $arrBlog->title }}</h2>
                                <div class="single-header-meta">
                                    <div class="entry-meta meta-1 font-xs mt-15 mb-15">
                                        <a class="author-avatar" href="#">
                                            <img class="img-circle" src="assets/imgs/blog/author-1.png" alt="">
                                        </a>
                                        <span class="post-by">By <a href="#">{{ $arrBlog->author }}</a></span>
                                        <span class="post-on has-dot">2 hours ago</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="single-content">
                        <div class="row">
                            <div class="col-xl-10 col-lg-12 m-auto">
                                <?php echo stripcslashes($arrBlog->short_description); ?>
                            </div>
                            <div class="col-xl-10 col-lg-12 m-auto">
                                <?php echo stripcslashes($arrBlog->description); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')

@stop