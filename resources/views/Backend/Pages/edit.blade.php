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
						      	<label for="name">Name</label>
						      	<input type="text" class="form-control" id="name" name="name" placeholder="Enter email" value="{{ $object->name }}">
						   	</div>
						   	<div class="form-group">
						      	<label for="slug">Slug</label>
						      	<input type="text" class="form-control" id="slug" name="slug" placeholder="Enter slug"  value="{{ $object->slug }}">
						   	</div>
						   	<div class="form-group">
						      	<label for="description">Description</label>
						      	<textarea class="form-control" id="description" name="description" rows="5" placeholder="Enter description">{{ $object->contents }}</textarea>
						   	</div>
						   	<div class="form-group">
						      	<label for="meta_tite">Meta tite</label>
						      	<input type="text" class="form-control" id="meta_tite" name="meta_tite" placeholder="Enter meta tite" value="{{ $object->meta_tite }}">
						   	</div>
						   	<div class="form-group">
						      	<label for="meta_keyword">Meta keyword</label>
						      	<input type="text" class="form-control" id="meta_keyword" name="meta_keyword" placeholder="Enter meta keyword" value="{{ $object->meta_keyword }}">
						   	</div>
						   	<div class="form-group">
						      	<label for="meta_descriptions">Meta description</label>
						      	<textarea class="form-control" id="meta_descriptions" name="meta_descriptions" rows="5" placeholder="Enter meta description">{{ $object->meta_descriptions }}</textarea>
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
	    CKEDITOR.replace('meta_descriptions');
	});

	$("#name").keyup(function() {
	  	var val_title = $(this).val();
	  	val_title = val_title.toLowerCase().replace(/ /g, '-');
	  	val_title = val_title.toLowerCase().replace(/[^a-z0-9\s]/gi, '-').replace(/[_\s]/g, '-');

	  	$('#slug').val(val_title);
	});

	$(document).ready(function()
	{
		$('#name').focus();
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