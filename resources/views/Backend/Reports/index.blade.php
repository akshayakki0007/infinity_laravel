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
		          	<div class="box-tools pull-right">
		          		<a title="{{'Add New'}}" href="{{ url($modulePath.'/create') }}" class="btn btn-social btn-linkedin" ><i class="fa fa-plus"></i>{{'Add New'}}</a>
		          	</div>
	        	</div>

              	<div class="box-body">
              		<div class="filterDiv">
              			<div class="col-md-6">
              				@if(Session::has('message'))
								<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
							@endif
              			</div>
	              		<div class="col-md-12">
	              			<div class="col-md-6">
		              			<div class="form-group">
					                <label for="searchDate">Search Date range:</label>
					                <div class="input-group">
					                  <div class="input-group-addon">
					                    <i class="fa fa-calendar"></i>
					                  </div>
					                  <input type="text" class="form-control searchDate" name="search_date" id="searchDate" autocomplete="off">
					                </div>
				            	</div>
			            	</div>
	              		</div>
	              		<div class="col-md-12 btnDiv">
	              			<button type="button" class="btn btn-primary" id="btn_action_search">Search</button>
	              			<a href="{{ url($modulePath) }}" class="btn btn-danger">Reset</a>
	              		</div>
              		</div>
          			<div class="dataTables_wrapper form-inline dt-bootstrap">
          				<table id="listingTable" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
          					<thead>
          						<th>Sr. No.</th>
          						<th>Report Id</th>
          						<th>Category</th>
          						<th>Title</th>
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
		$('.searchDate').daterangepicker({
		    timePicker: false,
		    startDate: moment().startOf('hour'),
		    endDate: moment().startOf('hour').add(48, 'hour'),
		    locale: {
		      format: 'DD/MM/YYYY'
		    }
		});
		
		$('#searchDate').val("");

		$(document).ready(function() 
		{
		    filterTable();    
		});

		function filterTable(action='')
		{
			var targetURL = adminPath+'/reports/getData'; 
		    var search_order_date = $('#searchDate').val();
		    var tbl = $('#listingTable').DataTable( 
		    {
		        responsive: 'true',
		        serverSide: 'true',
		        processing: 'true',
		        searching: true,
		        ajax:{
		            url: targetURL,
		            method:'GET',
		            data:{
		            	action:action,
		                search_order_date:search_order_date
		            },
		            dataFilter: function(data){
		                json = $.parseJSON( data );
		                json.recordsFiltered = json.recordsFiltered;
		                return JSON.stringify( json ); 
		            }
		        },
		        columns: [
		            { "data": "id",             "orderseble": "true"},
		            { "data": "id",             "orderseble": "true"},
		            { "data": "fk_category_id", "orderseble": "true"},
		            { "data": "report_title",   "orderseble": "true"},
		            { "data": "created_date",   "orderseble": "true"},
		            { "data": "status",         "orderseble": "true"},
		            { "data": "actions"}
		        ],
		        columnDefs: [
		            {    
		                'searchable':false,
		                'orderable':false,
		                'render': function (data, type, full, meta){
		                    return data;
		                },
		                'targets': [0,6],
		                'orderable': false,
		            }
		        ],
		        lengthMenu: [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
		        aaSorting: [[0, 'DESC']],
		        language: {
		          processing: "Loading ..."
		        }
		    });
		}

		$(document).on('click','#btn_action_search',function()
		{
		    $('#listingTable').DataTable().destroy();
		    filterTable('search');
		});

		function actionDelete(element)
		{
		    var $this = $(element);
		    var id = $this.attr('data-qsnid');
		    
		    var adminPath = $('meta[name="admin-path"]').attr('content');
		    var targetURL = adminPath+'/category/destroy/'+id;

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
		    var targetURL = adminPath+'/category/updateStatus/'+id;

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