@extends('Backend.Partials.master')

@section('title')
	{{ $moduleAction }}
@stop

@section('styles')
<style type="text/css">
	.detailsClass
	{
		font-size: 20px;
	}
</style>
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
			   	<form onsubmit="return actionSave(this)" action="{{url($modulePath.'/update', [ $object->id ])}}"  method="put" enctype="multipart/form-data">
			   		<div class="box-header with-border">
		          		<h3 class="box-title"></h3>
			          	<div class="box-tools pull-right">
			          		<a title="Back to Repository" href="{{ url($modulePath) }}" class="btn btn-social btn-linkedin" ><i class="fa fa-arrow-left"></i>{{'Back'}}</a>
			          	</div>
		        	</div>
		        	<div class="box-body">
			      		<div class="col-md-12">
						   	<div class="form-group">
						      	<label>Report Title</label>
						      	<p class="detailsClass"><?php echo !empty($object->reports) ? $object->reports->report_title : ''; ?></p>
						   	</div>
						   	<div class="form-group">
							   	<div class="row">
							   		<div class="col-md-6">
							   			<label>Name</label>
							   			<p class="detailsClass">{{ $object->name }}</p>
							   		</div>
							   		<div class="col-md-6">
							   			<label>Email</label>
							   			<p class="detailsClass">{{ $object->email_id }}</p>
							   		</div>
							   	</div>
						   	</div>
						   	<div class="form-group">
							   	<div class="row">
							   		<div class="col-md-6">
							   			<label>Contact</label>
							   			<p class="detailsClass">{{ $object->contact_no }}</p>
							   		</div>
							   		<div class="col-md-6">
							   			<label>Job</label>
							   			<p class="detailsClass">{{ $object->job_title }}</p>
							   		</div>
							   	</div>
						   	</div>
						   	<div class="form-group">
							   	<div class="row">
							   		<div class="col-md-6">
							   			<label>Company</label>
							   			<p class="detailsClass">{{ $object->company_name }}</p>
							   		</div>
							   		<div class="col-md-6">
							   			<label>Country</label>
							   			<p class="detailsClass">{{ $object->country }}</p>
							   		</div>
							   	</div>
						   	</div>
						   	<div class="form-group">
							   	<div class="row">
							   		<div class="col-md-6">
							   			<label>City</label>
							   			<p class="detailsClass">{{ $object->city }}</p>
							   		</div>

							   		<div class="col-md-6">
							   			<label>Zip code</label>
							   			<p class="detailsClass">{{ $object->zip_code }}</p>
							   		</div>
							   	</div>
						   	</div>
						   	<div class="form-group">
							   	<div class="row">
							   		<div class="col-md-6">
							   			<label>Amount</label>
							   			<p class="detailsClass">{{ $object->licence_price }}</p>
							   		</div>
							   		<div class="col-md-6">
							   			<label>Payment status</label>
							   			<p class="detailsClass">{{ $object->payment_status }}</p>
							   		</div>
							   	</div>
						   	</div>
						</div>
					</div>
			      	<div class="box-footer">
			      		<div class="col-md-12">
				         	<button type="submit" class="btn btn-primary">Update</button>
				      	</div>
			      	</div>
			   	</form>
          	</div>
	    </section>
	</div>
@stop

@section('scripts')
<script type="text/javascript">
	function actionSave(element)
	{
		$(element).closest('.box').LoadingOverlay("show", 
		{
		    image       : "",
		    background  : "rgba(165, 190, 100, 0.4)",
		    fontawesome : "fa fa-cog fa-spin"
		});

		$('#submit_button').hide();

		setTimeout(function ()
		{
			var $this = $(element);            		
			var formData = new FormData($this[0]);	
			var action = $this.attr('action');

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
			    		}, 1500);
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
		}, 1500);
		return false;
	}
</script>
@stop