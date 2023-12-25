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
              			<form action="{{url($modulePath.'/download_reports')}}" method="POST" >
		              		<div class="col-md-12">
		              			<div class="col-md-6">
			              			<div class="form-group">
					            		<label for="publisher">Publisher:</label>
		                                <select class="form-control select2" name="publisher" id="publisher" style="width: 100%;" required>
		                                    <option value="">Please select a Option</option>
		                                    @if(count($arrPublisher) > 0)
		                                    	@foreach($arrPublisher as $key => $row)
		                                    		<option value="{{ $row->id }}">{{ $row->name }}</option>
		                                    	@endforeach
		                                    @endif
		                                </select>
		                            </div>
					            </div>
					            <div class="col-md-6">
					            	<div class="form-group">
					            		<label for="category">Category:</label>
		                                <select class="form-control select2" name="category" id="category" style="width: 100%;" required>
		                                    <option value="">Please select a Option</option>
		                                    @if(count($arrCategory) > 0)
		                                    	@foreach($arrCategory as $key => $row)
		                                    		<option value="{{ $row->id }}">{{ $row->name }}</option>
		                                    	@endforeach
		                                    @endif
		                                </select>
		                            </div>
					            </div>
		              		</div>
		              		<div class="col-md-12 btnDiv">
		              			<button type="button" class="btn btn-primary" id="btn_search">Search</button>
		              			<a href="{{ url($modulePath) }}" class="btn btn-danger">Reset</a>
		              			<button type="submit" class="btn btn-success" id="btnDownload">Download</button>
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
	function filterTable()
	{
		var targetURL = adminPath+'/custom_reports_download/getData'; 

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
	                publisher:$('#publisher').val(),
	                category:$('#category').val()
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
	        columnDefs: [
	            {    
	                'searchable':false,
	                'orderable':false,
	                'render': function (data, type, full, meta){
	                    return data;
	                },
	                'targets': [0,3],
	                'orderable': false,
	            }
	        ],
	        lengthMenu: [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
	        aaSorting: [[0, 'DESC']],
	        language: {
	          processing: "Loading ..."
	        }
	    });


	    $('#listingTable').on( 'length.dt', function ( e, settings, len ) {
	        $('#chkAll').prop("checked", false);
	    });
	}

	$(document).on('click','#btn_search',function()
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
</script>
@stop