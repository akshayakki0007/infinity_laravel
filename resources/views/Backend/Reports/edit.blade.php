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
						      	<label for="report_title">Title</label>
						      	<input type="text" class="form-control" id="report_title" name="report_title" placeholder="Enter report title" value="{{ $object->report_title }}">
						   	</div>
						   	<div class="form-group">
						      	<label for="slug">Slug</label>
						      	<input type="text" class="form-control" id="slug" name="slug" placeholder="Enter report title" value="{{ $object->slug }}">
						   	</div>
						   	<div class="form-group">
						      	<label for="category">Category</label>
						      	<select class="form-control select2" id="category" name="category" style="width: 100%;">
						      		<option value="">Plase select a option</option>
						      		@if(count($arrCategory) > 0)
						      			@foreach($arrCategory as $key => $val)
						      				<option value="{{ $val->id }}" @if($object->fk_category_id == $val->id) selected @endif >{{ $val->name }}</option>
						      			@endforeach
						      		@endif
						      	</select>
						   	</div>
						   	<div class="form-group">
						      	<label for="publisher">Publisher</label>
						      	<select class="form-control select2" id="publisher" name="publisher" style="width: 100%;">
						      		<option value="">Plase select a option</option>
						      		@if(count($arrPublisher) > 0)
						      			@foreach($arrPublisher as $key => $val)
						      				<option value="{{ $val->id }}" @if($object->fk_publisher_id == $val->id) selected @endif >{{ $val->name }}</option>
						      			@endforeach
						      		@endif
						      	</select>
						   	</div>
						   	<div class="form-group">
							   	<div class="row">
								   <div class="col-md-4">
								   		<label for="single_user_price">Single user price ($)</label>
								      	<input type="text" class="form-control float-number" name="single_user_price" id="single_user_price" placeholder="Enter Single user price" value="{{ $object->single_user_price }}">
								   </div>
								   <div class="col-md-4">
								   		<label for="multi_user_price">Multi user price ($)</label>
								      	<input type="text" class="form-control float-number" name="multi_user_price" id="multi_user_price" placeholder="Enter Multi user price" value="{{ $object->multi_user_price }}">
								   </div>
								   <div class="col-md-4">
								   		<label for="pages">Pages</label>
								      	<input type="text" class="form-control float-number" name="pages" id="pages" placeholder="Enter Multi user price" value="{{ $object->pages }}">
								   </div>
								</div>
							</div>
						   	<div class="form-group">
						      	<label for="description">Description</label>
						      	<textarea class="form-control" id="description" name="description" rows="5" placeholder="Enter description">{{ $object->description }}</textarea>
						   	</div>
						   	<div class="form-group">
						      	<label for="toc">Table of Contents</label>
						      	<textarea class="form-control" id="toc" name="toc" rows="5" placeholder="Enter table of contents">{{ $object->toc }}</textarea>
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
<script type="text/javascript" src="{{ asset('/public/Backend/bower_components/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
	$(function () {
	    CKEDITOR.replace('description')
	    CKEDITOR.replace('toc')
	});

	$(document).ready(function()
	{
		$('#report_title').focus();
	});
	
	$("#report_title").keyup(function() {
	  	var val_title = $(this).val();
	  	val_title = val_title.toLowerCase().replace(/ /g, '-');
	  	val_title = val_title.toLowerCase().replace(/[^a-z0-9\s]/gi, '-').replace(/[_\s]/g, '-');

	  	$('#slug').val(val_title);
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
			var $this    = $(element);            		
			var formData = new FormData($this[0]);	
			var action   = $this.attr('action');
			
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
		}, 1000)

		return false;
	}
</script>
@stop