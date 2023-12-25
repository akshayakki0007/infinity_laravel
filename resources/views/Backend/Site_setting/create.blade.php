@extends('Backend.Partials.master')

@section('title')
	{{ $moduleAction }}
@stop

@section('styles')

@stop

@section('content')
	<div class="content-wrapper">
	    <section class="content-header">
	      <h1>
	        {{ $moduleAction }}
	      </h1>
	      <ol class="breadcrumb">
	        <li class=""><a href="{{ url('/admin/dashboard') }}">Dashboard</a></li>
	        <li class="active">{{ $moduleAction }}</li>
	      </ol>
	    </section>
	    
	    <section class="content">
      		<div class="box box-primary">
			   	<form onsubmit="return actionSave(this)" action="{{url($modulePath.'/update')}}"  method="put" enctype="multipart/form-data">
			   		<div class="box-header with-border">
		          		<h3 class="box-title"></h3>
			          	<div class="box-tools pull-right">
			          		<a title="Back to Repository" href="{{ url($modulePath) }}" class="btn btn-social btn-linkedin" ><i class="fa fa-arrow-left"></i>{{'Back'}}</a>
			          	</div>
		        	</div>
			      	<div class="box-body">
			      		<div class="col-md-12">
						   	<div class="form-group">
						      	<label for="site_name">Site Name</label>
						      	<input type="text" class="form-control" id="site_name" name="site_name" value="{{ $arrSettingObj->site_name }}">
						   	</div>
						   	<div class="form-group">
						      	<label for="site_email">Site Email Id</label>
						      	<input type="text" class="form-control" id="site_email" name="site_email" value="{{ $arrSettingObj->site_email }}">
						   	</div>
						   	<div class="form-group">
						      	<label for="site_mobile">Site Mobile</label>
						      	<input type="text" class="form-control" id="site_mobile" name="site_mobile" value="{{ $arrSettingObj->site_mobile }}">
						   	</div>
						   	<div class="form-group">
						      	<label for="site_address">Site Address</label>
						      	<textarea class="form-control" id="site_address" name="site_address" rows="5">{{ $arrSettingObj->site_address }}</textarea>
						   	</div>
						   	<div class="form-group">
						      	<label for="site_address">Site Logo</label>
						      	<input type="file" class="form-control" name="site_logo">
						   	</div>

						   	<h3>Discount Setting</h3>
						   	<hr/>
						   	<div class="form-group">
						      	<label for="discount">Discount</label>
						      	<input type="text" class="form-control" id="discount" name="discount" value="{{ $arrSettingObj->discount_amt }}">
						   	</div>

						   	<h3>Email Setting</h3>
						   	<hr/>
						   	<?php 
						   		if(!empty($arrSettingObj->email_setting))
						   		{
						   			$arrEmailData = json_decode($arrSettingObj->email_setting);
						   		}
						   	?>
						   	<div class="form-group">
						      	<label for="email_from">Email From</label>
						      	<input type="text" class="form-control" id="email_from" name="email[email_from]" value="<?php echo !empty($arrEmailData->email_from) ? $arrEmailData->email_from : ''; ?>">
						   	</div>
						   	<div class="form-group">
						      	<label for="email_to">Email To</label>
						      	<input type="text" class="form-control" id="email_to" name="email[email_to]" value="<?php echo !empty($arrEmailData->email_to) ? $arrEmailData->email_to : ''; ?>">
						   	</div>
						   	<div class="form-group">
						      	<label for="email_cc">Email CC</label>
						      	<input type="text" class="form-control" id="email_cc" name="email[email_cc]" value="<?php echo !empty($arrEmailData->email_cc) ? $arrEmailData->email_cc : ''; ?>">
						   	</div>
						   	<div class="form-group">
						      	<label for="smtp_host">Host</label>
						      	<input type="text" class="form-control" id="smtp_host" name="email[smtp_host]" value="<?php echo !empty($arrEmailData->smtp_host) ? $arrEmailData->smtp_host : ''; ?>">
						   	</div>
						   	<div class="form-group">
						      	<label for="smtp_user">Username</label>
						      	<input type="text" class="form-control" id="smtp_user" name="email[smtp_user]" value="<?php echo !empty($arrEmailData->smtp_user) ? $arrEmailData->smtp_user : ''; ?>">
						   	</div>
						   	<div class="form-group">
						      	<label for="smtp_pass">Password</label>
						      	<input type="text" class="form-control" id="smtp_pass" name="email[smtp_pass]" value="<?php echo !empty($arrEmailData->smtp_pass) ? $arrEmailData->smtp_pass : ''; ?>">
						   	</div>

						   	<h3>Payment Setting</h3>
						   	<hr/>
						   	<?php 
						   		if(!empty($arrSettingObj->stripe_setting))
						   		{
						   			$arrStripeData = json_decode($arrSettingObj->stripe_setting);
						   		}
						   	?>
						   	<h4><b>Stripe</b></h4>
						   	<div class="form-group">
						      	<label for="publish_key">Publish key</label>
						      	<input type="text" class="form-control" id="publish_key" name="stripe[publish_key]" value="<?php echo !empty($arrStripeData->publish_key) ? $arrStripeData->publish_key : '' ?>">
						   	</div>
						   	<div class="form-group">
						      	<label for="api_key">API key</label>
						      	<input type="text" class="form-control" id="api_key" name="stripe[api_key]" value="<?php echo !empty($arrStripeData->api_key) ? $arrStripeData->api_key : '' ?>">
						   	</div>


						   	<?php 
						   		if(!empty($arrSettingObj->paypal_setting))
						   		{
						   			$arrPaypalData = json_decode($arrSettingObj->paypal_setting);
						   		}
						   	?>
						   	<h4><b>Paypal</b></h4>
						   	<div class="form-group">
						      	<label for="paypal_account">Account</label>
						      	<input type="text" class="form-control" id="paypal_account" name="paypal[paypal_account]" value="<?php echo !empty($arrPaypalData->paypal_account) ? $arrPaypalData->paypal_account : ''; ?>">
						   	</div>
						   	<div class="form-group">
						      	<label for="paypal_client_id">Client Id</label>
						      	<input type="text" class="form-control" id="paypal_client_id" name="paypal[paypal_client_id]" value="<?php echo !empty($arrPaypalData->paypal_client_id) ? $arrPaypalData->paypal_client_id : ''; ?>">
						   	</div>
						   	<div class="form-group">
						      	<label for="paypal_secret_key">Secret key</label>
						      	<input type="text" class="form-control" id="paypal_secret_key" name="paypal[paypal_secret_key]" value="<?php echo !empty($arrPaypalData->paypal_secret_key) ? $arrPaypalData->paypal_secret_key : ''; ?>">
						   	</div>
						</div>
					</div>
			      	<div class="box-footer">
			      		<div class="col-md-12">
				         	<button type="submit" class="btn btn-primary" id="submit_button">Submit</button>
				      	</div>
			      	</div>
			   	</form>
          	</div>
	    </section>
	</div>
@stop

@section('scripts')
<script type="text/javascript">
	var adminPath = $('meta[name="admin-path"]').attr('content');

	function actionSave(element)
	{
		$(element).closest('.box').LoadingOverlay("show", 
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
		  		toastr.clear();
		  		$(element).closest('.box').LoadingOverlay("hide");

		    	if (data.status == 'success') 
		    	{
		    		$this[0].reset();
	    			toastr.success(data.msg);
		    		setTimeout(function ()
		    		{
		    			$('#submit_button').show();
		    			location.reload(true);
		    		}, 1500)
		    	}
		    	else
		    	{
		    		$('#submit_button').show();
		    		toastr.error(data.msg);
		    	}
		  	},
		  	error: function (data)
		  	{
		  		$(element).closest('.box').LoadingOverlay("hide");
		    	$('#submit_button').show();

	  		 	toastr.clear();
	  		 	if( data.status === 422 ) 
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
			    else
			    {
		  			toastr.error('Something went wrong on server, Please try again later.');
			    }
		  	}
		});

		return false
	}
</script>
@stop