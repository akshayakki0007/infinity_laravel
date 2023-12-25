@extends('Backend.Partials.master')

@section('title')
	{{ $moduleAction }}
@stop

@section('styles')
<style type="text/css">
	.filterDiv {
	    margin-bottom: 16px;
	    margin-top: 10px;
	    clear: both;
	    display: inline-block;
	}

	.filterDiv .col-md-12
	{
		padding: 0;
	}
	.col-md-12.btnDiv {
	    margin-left: 18px;
	    padding: 0;
	}

	#reportFilterDiv2 {
	    margin-bottom: 10px;
	}
	#reportFilterDiv2 .col-md-6 {
	    padding: 0;
	}

	.label-warning
	{
		font-size: 13px;
	}

	#reportFilterDiv2 .form-group {
	    display: -webkit-box;
	    clear: both;
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
	      	<div class="box">
	        	<div class="box-header with-border">
		          	<h3 class="box-title">
		          	</h3>
	        	</div>

              	<div class="box-body">
              		<div class="filterDiv">
              			<div class="col-md-12">
              				@if(Session::has('message'))
								<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
							@endif
              			</div>
              			<form action="{{url($modulePath.'/reports_uploading')}}" method="POST" >
              				@csrf
		              		<div class="col-md-12">
			              		<div class="form-group">
	                                <input type="file" class="form-control" name="report_file" id="report_file" onchange="uploadFile()">
	                            </div>
                            </div>
		              	</form>
              		</div>

          			<div class="dataTables_wrapper form-inline dt-bootstrap">
          				<table id="listingTable" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
          					<thead>
          						<th>Sr. No.</th>
          						<th>File Name</th>
          						<th>Status</th>
          						<th>Date</th>
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

@stop

@section('scripts')
<script type="text/javascript">
	filterTable();

	function filterTable()
	{
		var targetURL = adminPath+'/bulk_reports_uploading/getData'; 

	    var tbl = $('#listingTable').DataTable( 
	    {
	        responsive: 'true',
	        serverSide: 'true',
	        processing: 'true',
	        searching: false,
	        ajax:{
	            url: targetURL,
	            method:'GET',
	            dataFilter: function(data){
	                json = $.parseJSON( data );
	                json.recordsFiltered = json.recordsFiltered;
	                return JSON.stringify( json ); 
	            }
	        },
	        columns: [
	            { "data": "id",              "orderseble": "true"},
	            { "data": "file_name",       "orderseble": "true"},
	            { "data": "download_status", "orderseble": "true"},
	            { "data": "created_at",      "orderseble": "true"},
	            { "data": "actions"}
	        ],
	        columnDefs: [{    
                'searchable':false,
                'orderable':false,
                'render': function (data, type, full, meta){
                	return data;
                },
                'targets': [0,4],
                'orderable': false,
            }],
	        lengthMenu: [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
	        aaSorting: [[0, 'DESC']],
	        language: {
	          processing: "Loading ..."
	        }
	    });
	}

	function uploadFile()
	{
		$('.content-wrapper').LoadingOverlay("show", 
		{
		    image       : "",
		    background  : "rgba(165, 190, 100, 0.4)",
		    fontawesome : "fa fa-cog fa-spin"
		});

        var file = $('#report_file')[0].files[0]; 
		var formdata = new FormData();
		formdata.append("report_file", file);

        $.ajax({ 
            url: adminPath+'/bulk_reports_uploading/upload_report',
            type: 'POST', 
            data: formdata, 
            contentType: false, 
            processData: false, 
            success: function(data)
		  	{
		  		toastr.clear();
		  		$('.content-wrapper').LoadingOverlay("hide");

		    	if (data.status == 'success') 
		    	{
	    			toastr.success(data.msg);
		    		setTimeout(function ()
		    		{
		    			location.reload(true);
		    		}, 1500);
		    	}
		    	else
		    	{
		    		toastr.error(data.msg);
		    	}
		  	},
		  	error: function (data)
		  	{
		  		$('.content-wrapper').LoadingOverlay("hide");

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
    }
</script>
@stop