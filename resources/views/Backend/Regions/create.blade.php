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
			   	<form onsubmit="return actionSave(this)" action="{{url($modulePath.'/store')}}" >
			   		<div class="box-header with-border">
		          		<h3 class="box-title"></h3>
			          	<div class="box-tools pull-right">
			          		<a title="Back to Repository" href="{{ url($modulePath) }}" class="btn btn-social btn-linkedin" ><i class="fa fa-arrow-left"></i>{{'Back'}}</a>
			          	</div>
		        	</div>
			      	<div class="box-body">
			      		<div class="col-md-12">
						   	<div class="form-group">
						      	<label for="name">Region</label>
						      	<input type="text" class="form-control" id="name" name="name" placeholder="Enter region">
						   	</div>
						</div>
					</div>
			      	<div class="box-footer">
			      		<div class="col-md-12">
				         	<button type="submit" class="btn btn-primary">Submit</button>
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
		    			window.location.replace(data.url);
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