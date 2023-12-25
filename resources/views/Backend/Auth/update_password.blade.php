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
        <form onsubmit="return forget_password(this)" action="{{asset('admin/update_password')}}" method="post">
          <p>Username : {{ $arrObject->email }}</p>
          <input type="hidden" name="token" value="{{$token}}"> 
          <div class="form-group has-feedback">
            <input type="password" name="password" id="password" class="form-control" placeholder="New Password"> 
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="New Password Confirmation"> 
            <span id='message'></span>
          </div>
          <div class="form-group captcha_code_div">
            <div class="col-md-6 secureCodePanel">
              <p><span class="captcha_div"></span><button id="refreshButton" type="button"><i class="fa fa-refresh" aria-hidden="true"></i></button></p>
            </div>
            <div class="col-md-6 secureCodeAnswer">
              <input type="text" class="form-control" id="captcha_code" name="captcha_code" >
            </div>
          </div>
          <div class="row">
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Update</button>
            </div>
          </div>
        </form>
      </div>
    </div>
</div>
@stop

@section('scripts')
  <!-- Custom JS -->
  <script type="text/javascript">
    $('#password, #confirm_password').on('keyup', function () {
        if ($('#password').val() == $('#confirm_password').val())
        {
            $('#btnLogin').show();
            $('#message').html('Matching').css('color', 'green');
        }
        else 
        {
            $('#btnLogin').hide();
            $('#message').html('Not Matching').css('color', 'red');
        }
    });

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
  <script type="text/javascript" src="{{asset('/public/custom_js/admin/auth/update_password_js.js')}}"></script>
@stop