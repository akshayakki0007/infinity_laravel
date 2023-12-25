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
          						<th>Sr.  No.</th>
          						<th>Report Id</th>
          						<th>Name</th>
						        <th>Email</th>
						        <th>Contact</th>
						        <th>Company</th>
						        <th>Job</th>
						        <th>Price</th>
						        <th>Payment type</th>
						        <th>Payment status</th>
          						<th>Created date</th>
          						<th>Action</th>
          					</thead>
          					<tbody>
          					</tbody></label>
          				</table>
          			</div>
              	</div>
	      	</div>
	    </section>
	</div>
@stop

@section('scripts')

	@include('Backend.Transactions.scripts')

@stop