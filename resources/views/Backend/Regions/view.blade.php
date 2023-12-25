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
		   		<div class="box-header with-border">
	          		<h3 class="box-title">
	          			Region : {{ $object->name }}
	          		</h3>
		          	<div class="box-tools pull-right">
		          		<a title="{{'Add New Country'}}" class="btn btn-social btn-linkedin" data-toggle="modal" data-target="#addCountryModal" ><i class="fa fa-plus"></i>{{'Add New Country'}}</a>
		          		&nbsp;
		          		<a title="Back to Repository" href="{{ url($modulePath) }}" class="btn btn-social btn-linkedin" ><i class="fa fa-arrow-left"></i>{{'Back'}}</a>
		          	</div>
	        	</div>
		      	<div class="box-body">
		      		<div></div>
		      		<div class="col-md-12">
					   	<table id="listingTable" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
          					<thead>
          						<th>Sr. No.</th>
          						<th>Name</th>
          						<th>Status</th>
          						<th>Action</th>
          					</thead>
          					<tbody>
          					</tbody>
          				</table>
					</div>
				</div>
          	</div>
	    </section>
	</div>

	<div class="modal fade" id="addCountryModal">
      	<div class="modal-dialog">
	        <div class="modal-content">
	          	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	              <span aria-hidden="true">&times;</span></button>
	            	<h4 class="modal-title">Add Country</h4>
	          	</div>
	          	<form onsubmit="return actionSaveCountry(this)" action="{{url($modulePath.'/add_country')}}" >
	          		<input type="hidden" name="fk_region_id" value="{{ $object->id }}">
		          	<div class="modal-body row">
		            	<div class="col-md-12">
						   	<div class="form-group">
						      	<label for="country_name">Country</label>
						      	<input type="text" class="form-control" id="country_name" name="country_name" placeholder="Enter region">
						   	</div>
						</div>
		          	</div>
		          	<div class="modal-footer">
		            	<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		            	<button type="submit" class="btn btn-primary">Submit</button>
		          	</div>
		       	</form>
	        </div>
      	</div>
    </div>

	<div class="modal fade" id="editCountryModal">
      	<div class="modal-dialog">
	        <div class="modal-content">
	          	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	              <span aria-hidden="true">&times;</span></button>
	            	<h4 class="modal-title">Add Country</h4>
	          	</div>
	          	<form onsubmit="return actionUpdate(this)" action="{{url($modulePath.'/update_country')}}"  method="put" enctype="multipart/form-data">
		          	<div class="modal-body row">
		          		<input type="hidden" name="regions_id" value="{{ $object->id }}">
		          		<input type="hidden" name="fk_country_id" id="country_id" value="">
		            	<div class="col-md-12">
						   	<div class="form-group">
						      	<label for="country_name">Country</label>
						      	<input type="text" class="form-control" id="countryName" name="country_name" placeholder="Enter country">
						   	</div>
						</div>
		          	</div>
		          	<div class="modal-footer">
		            	<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		            	<button type="submit" class="btn btn-primary">Submit</button>
		          	</div>
		       	</form>
	        </div>
      	</div>
    </div>
@stop

@section('scripts')
<script type="text/javascript">
		$(document).ready(function() 
		{
		    var adminPath = $('meta[name="admin-path"]').attr('content');
		    var targetURL = adminPath+'/regions/getCountryData'; 

		    $('#listingTable').DataTable( 
		    {        
		        responsive: 'true',
		        serverSide: 'true',
		        processing: 'true',
		        ajax: targetURL,
		        columns: [
		            { "data": "id",           "orderseble": "true"},
		            { "data": "name",         "orderseble": "true"},
		            { "data": "status",       "orderseble": "true"},
		            { "data": "actions"}
		        ],
		        columnDefs: [
		            {    
		                'searchable':false,
		                'orderable':false,
		                'render': function (data, type, full, meta){
		                    return data;
		                },
		                'targets': [0,1,3],
		                'orderable': false,
		            }
		        ],
		        lengthMenu: [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
		        aaSorting: [[0, 'DESC']],
		        language: {
		          processing: "Loading ..."
		        }
		    });
		});

		function actionSaveCountry(element)
		{
			$('.box').LoadingOverlay("show", 
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
			  		$('.box').LoadingOverlay("hide");

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
			  		$('.box').LoadingOverlay("hide");
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

			return false;
		}

		function actionUpdate(element)
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

			$('#editCountryModal').modal('hide');

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

			return false
		}

		function actionDelete(element)
		{
		    var $this = $(element);
		    var id = $this.attr('data-qsnid');
		    
		    var adminPath = $('meta[name="admin-path"]').attr('content');
		    var targetURL = adminPath+'/regions/destroy_country/'+id;

		    if (id != '') 
		    {
		        swal({
		           	title: "Are you sure !!",
		          	text: "You want to delete?",
		          	type: "warning",
		          	showCancelButton: true,
		          	confirmButtonText: "Delete",
		          	confirmButtonClass: "btn-danger",
		          	closeOnConfirm: false,
		          	showLoaderOnConfirm: true
		        }, 
		        function () 
		        {
		            $.ajax({
		                type:'POST',
		                url:targetURL,
		                dataType:'json',
		                success: function(data)
		                {
		                    setTimeout(function () 
		                    {
		                        if (data.status == 'success') 
		                        {
		                        	setTimeout(function () 
		                    		{
		                            	location.reload();
		                            }, 1500);
		                            swal("Success", data.msg,'success');
		                        }
		                        else
		                        {
		                            swal("Error",data.msg,'error');
		                        }
		                    }, 500);
		                }
		            })
		        });
		    }  
		}

		function actionUpdateStatus(element)
		{
		    var $this = $(element);
		    var id = $this.attr('data-rwid');
		    
		    var adminPath = $('meta[name="admin-path"]').attr('content');
		    var targetURL = adminPath+'/regions/updateCountryStatus/'+id;

		    if (id != '') 
		    {
		        swal({
		           title: "Are you sure !!",
		          text: "You want to update status ?",
		          type: "warning",
		          showCancelButton: true,
		          confirmButtonText: "Update",
		          confirmButtonClass: "btn-danger",
		          closeOnConfirm: false,
		          showLoaderOnConfirm: true
		        }, 
		        function () 
		        {
		            $.ajax({
		                type:'POST',
		                url:targetURL,
		                dataType:'json',
		                success: function(data)
		                {
		                    setTimeout(function () 
		                    {
		                        if (data.status == 'success') 
		                        {
		                            $('#listingTable').DataTable().ajax.reload();
		                            swal("Success", data.msg,'success');
		                        }
		                        else
		                        {
		                            swal("Error",data.msg,'error');
		                        }
		                    }, 1000);
		                }
		            })
		        });
		    }  
		}

		function actionUpdateCountry(element)
		{
		    var $this = $(element);
		    var id = $this.attr('data-qsnid');
		    var value = $this.attr('data-value');
		    $('#countryName').val(value);
		    $('#country_id').val(id);
		    $('#editCountryModal').modal('show');
		}
	</script>
@stop