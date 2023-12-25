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
	              			<div class="col-md-4">
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
				            <div class="col-md-4">
				            	<div class="form-group">
				            		<label for="pipline_status">Pipeline status:</label>
	                                <select class="form-control" name="pipline_status" id="pipline_status">
	                                    <option value="">Select a Pipeline Option</option>
	                                    <option value="in_process">In-Process</option>
	                                    <option value="replay">Reply</option>
	                                    <option value="not_interested">Not-Interested</option>
	                                    <option value="forwarded">Forwarded</option>
	                                    <option value="non_responsive">Non-Responsive</option>
	                                    <option value="in_conversion">In-Conversation</option>
	                                    <option value="intersted">Interested</option>
	                                    <option value="invoice">Invoice</option>
	                                    <option value="close">Close</option>
	                                </select>
	                            </div>
				            </div>
				            <div class="col-md-4">
				            	<div class="form-group">
				            		<label for="lead_type">Workable status:</label>
	                                <select class="form-control" name="lead_type" id="lead_type">
	                                    <option value="">Select Workable Status</option>
	                                    <option value="workable">Workable</option>
	                                    <option value="non_workable">Non workable</option>
	                                </select>
	                            </div>
				            </div>
	              		</div>
	              		<div class="col-md-12 btnDiv">
	              			<button type="button" class="btn btn-primary" id="btn_action_search">Search</button>
	              			<button type="button" class="btn btn-primary" id="btn_action_today">Today</button>
	              			<a href="{{ url($modulePath) }}" class="btn btn-danger">Reset</a>
	              			<button type="button" class="btn btn-success" id="btn_action_download" data-toggle="modal" data-target="#dowloadReportModal">Download</button>
	              		</div>
              		</div>

          			<div class="dataTables_wrapper form-inline dt-bootstrap">
          				<table id="listingTable" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
          					<thead>
          						<th>Sr. No.</th>
          						<th>Name</th>
						        <th>Email</th>
						        <th>Contact</th>
						        <th>Report</th>
						        <th>Company</th>
						        <th>Job</th>
						        <th>Sale Person</th>
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

	<div class="modal fade" id="assignModal">
      	<div class="modal-dialog">
	        <div class="modal-content">
	          	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	              <span aria-hidden="true">&times;</span></button>
	            	<h4 class="modal-title">Assign Lead</h4>
	          	</div>
	          	<form onsubmit="return actionAssignLead(this)" action="{{url($modulePath.'/assign_lead')}}"  >
	          		<div class="modal-body row">
		            	<div class="col-md-12">
		            		<input type="hidden" name="lead_id" id="lead_id">
						   	<div class="form-group">
						      	<label for="sales_person">Sales User</label>
						      	@if(count($arrSalesUsers) > 0)
						      		<select class="form-control select2" name="sales_person" id="sales_person" style="width: 100%;">
							      		<option value="">Please select a option</option>
							      		@foreach($arrSalesUsers as $key => $row)
							      			<option value="{{ $row->id }}">{{ $row->name }}</option>
							      		@endforeach
						      		</select>
						      	@endif
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

	<div class="modal fade" id="dowloadReportModal">
      	<div class="modal-dialog">
	        <div class="modal-content">
	          	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	              <span aria-hidden="true">&times;</span></button>
	            	<h4 class="modal-title">Download report</h4>
	          	</div>
	          	<form action="{{url($modulePath.'/download_reports')}}" method="POST" >
	          		@csrf
	          		<div class="modal-body row">
		            	<div class="col-md-12">
		            		<div class="form-group">
						   		<label>Download Report Type</label>
						   		<select class="form-control" name="download_reports_type" id="download_reports_type">
						   			<option value="">Please select a option</option>
						   			<option value="today">Today</option>
						   			<option value="date_range">Date range</option>
						   		</select>
						   	</div>
						   	<div class="form-group" id="date_range_div" style="display: none;">
				                <label for="searchDate">Search Date range:</label>
				                <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-calendar"></i>
				                  </div>
				                  <input type="text" class="form-control searchDate" name="search_report_date" id="searchDate">
				                </div>
				            </div>
						</div>
		          	</div>
		          	<div class="modal-footer">
		            	<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		            	<button type="submit" class="btn btn-primary">Download</button>
		          	</div>
		       	</form>
	        </div>
      	</div>
    </div>
@stop

@section('scripts')

	@include('Backend.Leads.scripts')

@stop