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
          						<th>Role</th>
          						<th>Action</th>
          					</thead>
          					<tbody>
          						<tr>
          							<td>1</td>
          							<td>Admin</td>
          							<td><a title="Add access" class="btn btn-default btn-circle" href="{{ url($modulePath.'/access/admin') }}"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
          						</tr>
          						<tr>
          							<td>2</td>
          							<td>Sales admin</td>
          							<td><a title="Add access" class="btn btn-default btn-circle" href="{{ url($modulePath.'/access/sales_admin') }}"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
          						</tr>
          						<tr>
          							<td>3</td>
          							<td>sales</td>
          							<td><a title="Add access" class="btn btn-default btn-circle" href="{{ url($modulePath.'/access/sales') }}"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
          						</tr>
          						<tr>
          							<td>4</td>
          							<td>SEO</td>
          							<td><a title="Add access" class="btn btn-default btn-circle" href="{{ url($modulePath.'/access/seo') }}"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
          						</tr>
          						<tr>
          							<td>5</td>
          							<td>Data</td>
          							<td><a title="Add access" class="btn btn-default btn-circle" href="{{ url($modulePath.'/access/data') }}"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
          						</tr>
          						<tr>
          							<td>6</td>
          							<td>Research</td>
          							<td><a title="Add access" class="btn btn-default btn-circle" href="{{ url($modulePath.'/access/research') }}"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
          						</tr>
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
		
	</script>
@stop