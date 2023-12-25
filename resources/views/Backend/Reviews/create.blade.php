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
						      	<label for="name">Name</label>
						      	<input type="text" class="form-control" id="name" name="name" placeholder="Enter email">
						   	</div>
						   	<div class="form-group">
						      	<label for="designation">Designation</label>
						      	<input type="text" class="form-control" id="designation" name="designation" placeholder="Enter designation">
						   	</div>
						   	<div class="form-group">
                                <label for="review_type_generic">Type</label><br>
                                <label class="radio-inline" for="review_type_generic">
                                  	<input type="radio" name="review_type" id="review_type_generic" value="generic">&nbsp;Generic report
                                </label>
                                <label class="radio-inline" for="review_type_specific">
                                  	<input type="radio" name="review_type" id="review_type_specific" value="specific">&nbsp;Specific report
                                </label>
                            </div>
						   	<div class="form-group">
						      	<label for="description">Description</label>
						      	<textarea class="form-control" id="description" name="description" placeholder="Enter a description"></textarea>
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
<script type="text/javascript" src="{{ asset('/public/Backend/bower_components/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
	$(function () {
	    CKEDITOR.replace('description');
	});

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
		}, 1500);
		return false
	}
</script>
@stop