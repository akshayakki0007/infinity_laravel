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
	      	<div class="box">
	        	<div class="box-header with-border">
		          	<h3 class="box-title">
		          	</h3>
		          	<div class="box-tools pull-right">
		          		<a title="{{'Add New'}}" href="{{ url($modulePath.'/create') }}" class="btn btn-social btn-linkedin" ><i class="fa fa-plus"></i>{{'Add New'}}</a>
		          	</div>
	        	</div>

              	<div class="box-body">
          			<div class="dataTables_wrapper form-inline dt-bootstrap">
          				<table id="listingTable" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
          					<thead>
          						<th>Sr. No.</th>
          						<th>Name</th>
          						<th>Created date</th>
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
@stop

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() 
		{
		    var adminPath = $('meta[name="admin-path"]').attr('content');
		    var targetURL = adminPath+'/regions/getData'; 

		    $('#listingTable').DataTable( 
		    {        
		        responsive: 'true',
		        serverSide: 'true',
		        processing: 'true',
		        ajax: targetURL,
		        columns: [
		            { "data": "id",           "orderseble": "true"},
		            { "data": "name",         "orderseble": "true"},
		            { "data": "created_date", "orderseble": "true"},
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
		                'targets': [0,1,4],
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

		function actionDelete(element)
		{
		    var $this = $(element);
		    var id = $this.attr('data-qsnid');
		    
		    var adminPath = $('meta[name="admin-path"]').attr('content');
		    var targetURL = adminPath+'/regions/destroy/'+id;

		    if (id != '') 
		    {
		        swal({
		           title: "Are you sure !!",
		          text: "You want to delete ?",
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
		    var targetURL = adminPath+'/regions/updateStatus/'+id;

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
	</script>
@stop