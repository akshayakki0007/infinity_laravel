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
              			<form action="{{url($modulePath.'/download_reports')}}" method="POST" >
              				@csrf
		              		<div class="col-md-12">
		              			<div class="col-md-8">
				              		<div class="form-group">
		                                <label class="radio-inline" for="option1">
		                                  <input type="radio" class="search_radio_box" id="option1" name="search_type" value="0" checked="">
		                                  <strong>Search Via Report IDs</strong>
		                                </label><br>
		                                <label class="radio-inline" for="option2">
		                                  <input type="radio" class="search_radio_box" id="option2" name="search_type" value="1">
		                                  <strong>Search Via Report ID Range</strong>
		                                </label>
		                            </div>
	                            </div>
                            </div>
		              		<div class="col-md-12" id="reportFilterDiv">
		              			<div class="col-md-10" id="reportFilterDiv1">
			              			<div class="form-group">
					            		<label for="search_report_id">Search Via Report IDs:</label>
		                                <input type="text" class="form-control" name="search_report_id" id="search_report_id">
		                            </div>
		                            <p><label class="label label-warning">NOTE: ReportIDs, Should be comma Separated e.g. 305001, 305003, etc, Max 500 ReportIDs</label></p>
					            </div>
		              			<div class="col-md-10" id="reportFilterDiv2" style="display: none;">
					            	<label for="search_from_report_id">Search Via Report ID Range:</label>
						            <div class="form-group">
						                <div class="col-md-6">
						                  <input type="text" class="form-control" name="search_from_report_id" id="search_from_report_id" placeholder="">
						                </div>
						                <div class="col-md-6">
						                  <input type="text" class="form-control" name="search_to_report_id" id="search_to_report_id" placeholder="">
						                </div>
					                </div>
		                            <p><label class="label label-warning">NOTE: Max Range limit is 500</label></p>
					            </div>
		              		</div>
		              		<div class="col-md-12 btnDiv">
		              			<button type="button" class="btn btn-primary" id="btn_search">Search</button>
		              			<a href="{{ url($modulePath) }}" class="btn btn-danger">Reset</a>
		              			<button type="submit" class="btn btn-success" id="btnDownload" style="display:none;">Download</button>
		              		</div>
		              	</form>
              		</div>

          			<div class="dataTables_wrapper form-inline dt-bootstrap">
          				<table id="listingTable" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
          					<thead>
          						<th>Sr. No.</th>
          						<th>Report ID</th>
          						<th>Report Title</th>
          						<th>Category</th>
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
	//filterTable();

	function filterTable()
	{
		var targetURL = adminPath+'/bulk_reports_download/getData'; 

	    var tbl = $('#listingTable').DataTable( 
	    {
	        responsive: 'true',
	        serverSide: 'true',
	        processing: 'true',
	        searching: false,
	        ajax:{
	            url: targetURL,
	            method:'GET',
	            data:{
	                search_type:$("input[name='search_type']:checked").val(),
	                search_report_id:$('#search_report_id').val(),
	                search_from_report_id:$('#search_from_report_id').val(),
	                search_to_report_id:$('#search_to_report_id').val()	
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
	            { "data": "report_title",   "orderseble": "true"},
	            { "data": "fk_category_id", "orderseble": "true"},
	        ],
	        columnDefs: [{    
                'searchable':false,
                'orderable':false,
                'render': function (data, type, full, meta){
                	return data;
                },
                'targets': [0,3],
                'orderable': false,
            }],
	        lengthMenu: [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
	        aaSorting: [[0, 'DESC']],
	        language: {
	          processing: "Loading ..."
	        }
	    });
	}

	$(document).on('click','#btn_search',function()
	{
		if($("input[name='search_type']:checked").val() == 0)
        {
        	if($('#search_report_id').val() == '')
			{
				toastr.error('Enter a report id');
				return false;
			}
        }
        else
        {
        	if($('#search_from_report_id').val() == '')
			{
				toastr.error('Enter a report id');
				return false;
			}
        	if($('#search_to_report_id').val() == '')
			{
				toastr.error('Enter a report id');
				return false;
			}
        }

        $('#btnDownload').show();
	    $('#listingTable').DataTable().destroy();
	    filterTable();
	});

	$(document).on('click','#btnDownload',function()
	{
		if($('#publisher').val() == '')
		{
			toastr.error('Select a publisher');
			return false;
		}
		if($('#category').val() == '')
		{
			toastr.error('Select a category');
			return false;
		}

	    $('#listingTable').DataTable().destroy();
	    filterTable();
	});

	$(document).ready(function() {
	    $("input[name$='search_type']").click(function() {
	        if($(this).val() == 0)
	        {
	        	$('#reportFilterDiv1').show();
	        	$('#reportFilterDiv2').hide();
	        }
	        else
	        {
	        	$('#reportFilterDiv2').show();
	        	$('#reportFilterDiv1').hide();

	        }
	    });
	});
</script>
@stop