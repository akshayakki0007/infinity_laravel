@extends('admin.auth.login_master')

@section('styles')
<style type="text/css">
  .form-group.captcha_code_div {
      display: inline-flex;
      background: #f2f2f2;
      padding: 10px;
  }
  .form-group.captcha_code_div .secureCodePanel {
      text-align: center;
      padding: 0;
      margin: 0;
  }
  .form-group.captcha_code_div .secureCodePanel p {
      margin: 0;
      padding: 5px;
      background: #888888;
      color: #fff;
  }
  .form-group.captcha_code_div .secureCodePanel p button#refreshButton {
      border: none;
      color: #fff;
      background: transparent;
      padding: 0 10px;
  }
  a.textBack.forgot.text-muted {
    font-size: 16px;
  }
</style>
@stop

@section('content')
<div class="login-box">
    <div class="login-box-body">
      <div class="form-content">
        <div class="login-logo">
          <p><b>{{$moduleTitle}}</b></p>
        </div>
        <form onsubmit="return forget_password(this)" action="{{asset('admin/forgot')}}" method="post">
          {{ csrf_field() }}
          <div class="form-group has-feedback">
            <input type="text" name="email" id="email" class="form-control" placeholder="Email Id Or Username" value="">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group captcha_code_div">
            <div class="col-md-6 secureCodePanel">
              <p><span class="captcha_div"></span><button id="refreshButton" type="button"><i class="fa fa-refresh" aria-hidden="true"></i></button></p>
            </div>
            <div class="col-md-6 secureCodeAnswer">
              <input type="text" class="form-control" id="captcha_code" name="captcha_code" >
            </div>
          </div>
          <div class="form-group has-feedback">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Reset 
            </button>
          </div>
          <div class="form-group">
            <a class="textBack forgot text-muted" href="{{ url('/admin/login') }}">Back</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@stop

@section('scripts')
  <!-- Custom JS -->
  <script type="text/javascript" src="{{asset('/public/custom_js/admin/auth/forgot_password_js.js')}}"></script>
  <script type="text/javascript">
    var adminPath = $('meta[name="admin-path"]').attr('content');

    $(document).on('click', '#refreshButton', function()
    {
        getCaptcha();
    });

    getCaptcha();

    function getCaptcha()
    {
      $.ajax({
        type: 'GET',
        url: adminPath+'/getCaptcha',
        processData: false,
        contentType: false,
        success: function(data)
        {
          toastr.clear();
          $('.uploadForm').closest('.box').LoadingOverlay("hide");

          if (data.status == 'success') 
          {
            $('.captcha_div').html(data.captcha_code);
          }
          else
          {
            toastr.error(data.msg);
          }
        }
      });
    }
  </script>
@stop