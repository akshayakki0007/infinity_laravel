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
							   			<label>Type</label>
							   			<p class="detailsClass">
							   				@switch($object->sample_type)
									            @case('1')
									                Request Sample
									            @break
									            @case('2')
									            	Enquiry Before Buying
									            @break
									            @default
									                Ask For Discount
									        @endswitch
							   			</p>
							   		</div>
							   	</div>
						   	</div>
						   	<div class="form-group">
							   	<div class="row">
							   		<div class="col-md-6">
							   			<label>Country</label>
							   			<p class="detailsClass">{{ $object->country }}</p>
							   		</div>
							   		<div class="col-md-6">
							   			<label>Message</label>
							   			<p class="detailsClass">{{ $object->description }}</p>
							   		</div>
							   	</div>
						   	</div>
						   	<div class="form-group">
							   	<div class="row">
							   		<div class="col-md-6">
							   			<label>Report Id</label>
							   			<p class="detailsClass">#{{ $object->report_id }}</p>
							   		</div>
							   		<div class="col-md-6">
							   			<label>Publisher Id</label>
							   			<p class="detailsClass">
							   				<?php 
							   					$publisher_id = !empty($object->reports) ? $object->reports->fk_publisher_id : 0;

							   					$arrPublisher = $object->getPublisher($publisher_id);
							   				?>
							   				@if(!empty($arrPublisher))
							   					{{ $arrPublisher->name }}
							   				@endif
							   			</p>
							   		</div>
							   	</div>
						   	</div>
						   	<div class="form-group">
							   	<div class="row">
							   		<div class="col-md-6">
							   			<label>Sales Person</label>
							   			<p class="detailsClass">
							   				<?php 
							   					echo !empty($object->sales) ? $object->sales->name.'('.$object->sales->email.')' : '-';
							   				?>
							   			</p>
							   		</div>
							   		<div class="col-md-6">
							   			<label></label>
							   		</div>
							   	</div>
						   	</div>
						   	<div class="form-group">
							   	<div class="row">
							   		<div class="col-md-6">
							   			<label>Pipeline status</label>
							   			<select class="form-control" name="pipline_status" id="pipline_status">
                                            <option value="">Select a Pipeline Option</option>
                                            <option value="in_process" <?php if($object->pipline_status == 'in_process'){ echo 'selected'; } ?> >In-Process</option>
                                            <option value="replay" <?php if($object->pipline_status == 'replay'){ echo 'selected'; } ?> >Reply</option>
                                            <option value="not_interested" <?php if($object->pipline_status == 'not_interested'){ echo 'selected'; } ?> >Not-Interested</option>
                                            <option value="forwarded" <?php if($object->pipline_status == 'forwarded'){ echo 'selected'; } ?> >Forwarded</option>
                                            <option value="non_responsive" <?php if($object->pipline_status == 'non_responsive'){ echo 'selected'; } ?> >Non-Responsive</option>
                                            <option value="in_conversion" <?php if($object->pipline_status == 'in_conversion'){ echo 'selected'; } ?> >In-Conversation</option>
                                            <option value="interested" <?php if($object->pipline_status == 'interested'){ echo 'selected'; } ?> >Interested</option>
                                            <option value="invoice" <?php if($object->pipline_status == 'invoice'){ echo 'selected'; } ?> >Invoice</option>
                                            <option value="close" <?php if($object->pipline_status == 'close'){ echo 'selected'; } ?> >Close</option>
                                        </select>
							   		</div>
							   		<div class="col-md-6">
							   			<label>Workable/Non Workable</label>
							   			<select class="form-control" name="lead_status" id="lead_status">
                                            <option value="">Select a status</option>
                                            <option value="workable" <?php if($object->lead_status == 'workable'){ echo 'selected'; } ?> >Workable</option>
                                            <option value="non_workable" <?php if($object->lead_status == 'non_workable'){ echo 'selected'; } ?> >Non workable</option>
                                        </select>
							   		</div>
							   	</div>
						   	</div>
						   	<div class="form-group">
							   	<div class="row">
							   		<div class="col-md-6">
							   			<label>Source</label>
							   			@if(count($arrSources) > 0)
							   				<select class="form-control" name="source" id="source">
							   					<option value="">Please select a option</option>
								   				@foreach($arrSources as $key => $row)
								   					<option value="{{ $row->id }}" @if($row->id == $object->fk_source_id) selected @endif >{{ $row->name }}</option>
								   				@endforeach
							   				</select>
							   			@else
							   				<p class="detailsClass">-</p>
							   			@endif
							   		</div>
							   		<div class="col-md-6">
							   			<label></label>
							   		</div>
							   	</div>
						   	</div>
						   	<div class="form-group">
							   	<div class="row">
							   		<div class="col-md-6">
							   			<label>Comment</label>
							   			<textarea class="form-control" name="comment" id="comment">{{ $object->comment }}</textarea>
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