@extends('Backend.Auth.login_master')

@section('styles')

@stop

@section('content')
<div class="login-box">
  <div class="login-logo">
    <a href=""><b>Welcome to Admin</a>
  </div>
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form onsubmit="return checkLogin(this)" action="{{asset('admin/login')}}" method="post">
      {{ csrf_field() }}
      <div class="form-group has-feedback">
        <input type="email" class="form-control" name="email" placeholder="Email" value="admin@gmail.com">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password" placeholder="Password" value="123456789">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="col-xs-12">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="remember_me"> Remember Me
            </label>
          </div>
        </div>
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat" id="submit_button">Sign In</button>
        </div>
      </div>
    </form>

    <a href="#">I forgot my password</a><br>

  </div>
</div>
@section('scripts')
<script type="text/javascript">
  function checkLogin(element)
  {
    $('.login-page').LoadingOverlay("show", 
    {
        image       : "",
        background  : "rgba(165, 190, 100, 0.4)",
        fontawesome : "fa fa-cog fa-spin"
    });

    var $this = $(element);               
    var formData = new FormData($this[0]);  
    var action = $this.attr('action');
    $('#submit_button').hide();
    
    $.ajax(
    {
        type: 'POST',
        url: action,
        data: formData,
        processData: false,
        contentType: false,
        success: function(data)
        {
          $('.login-page').LoadingOverlay("hide");
          toastr.clear();
          if (data.status == 'success') 
          {
            window.location.href = data.url;
          }
          else
          {
            $('.login-page').LoadingOverlay("hide");
            $('#submit_button').show();
            toastr.error(data.msg);
          }
        },
        error: function (data)
        {
          $('.login-page').LoadingOverlay("hide");
          $('#submit_button').show();
          
          toastr.clear();
          if(data.status === 422 ) 
          {
            var errorBag = $.parseJSON(data.responseText);
            if (errorBag) 
            {
              var x = 0;
              $.each(errorBag.errors, function(row, fields)
              {
                if (x == 0) 
                {
                  toastr.error(fields);
                }
                x++;
                });
            }
          }
        }
    });

    return false
  }
</script>
@stop