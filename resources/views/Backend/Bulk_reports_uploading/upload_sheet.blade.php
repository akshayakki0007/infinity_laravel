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
			   	<form action="{{url($modulePath.'/upload_sheet_report', [ $arrObject->id ])}}"  method="POST" >
			   		@csrf
			   		<div class="box-header with-border">
		          		<h3 class="box-title"></h3>
			          	<div class="box-tools pull-right">
			          		<a title="Back to Repository" href="{{ url($modulePath) }}" class="btn btn-social btn-linkedin" ><i class="fa fa-arrow-left"></i>{{'Back'}}</a>
			          	</div>
		        	</div>
			      	<div class="box-body">
			      		<div class="dataTables_wrapper form-inline dt-bootstrap">
	          				<table class="table table-bordered">
	          					<tbody>
	          						<tr>
									   <th>TITLE</th>
									   <td>
									      <select name="reportTitle" class="form-control">
									        @foreach($arrHeader as $key => $value)
									         	<option value="{{ $key }}">{{ $value }}</option>
									        @endforeach
									      </select>
									   </td>
									</tr>
									<tr>
									   <th>PUBLISHER</th>
									   	<td>
									      	<select name="publisher" class="form-control">
									         	@foreach($arrPublisher as $value)
									         		<option value="{{ $value->id }}">{{ $value->name }}</option>
									        	@endforeach
									      	</select>
									   	</td>
									</tr>
									<tr>
									   <th>CATEGORY</th>
									   <td>
									      <select name="category" class="form-control">
									        @foreach($arrHeader as $key => $value)
									         	<option value="{{ $key }}">{{ $value }}</option>
									        @endforeach
									      </select>
									   </td>
									</tr>
									<tr>
									   <th>SINGLE PRICE</th>
									   <td>
									      <select name="single" class="form-control">
									        @foreach($arrHeader as $key => $value)
									         	<option value="{{ $key }}">{{ $value }}</option>
									        @endforeach
									      </select>
									   </td>
									</tr>
									<tr>
									   <th>MULTI PRICE</th>
									   <td>
									      <select name="multi" class="form-control">
									        @foreach($arrHeader as $key => $value)
									         	<option value="{{ $key }}">{{ $value }}</option>
									        @endforeach
									      </select>
									   </td>
									</tr>
									<tr>
									   <th>PAGES</th>
									   <td>
									      <select name="pages" class="form-control">
									        @foreach($arrHeader as $key => $value)
									         	<option value="{{ $key }}">{{ $value }}</option>
									        @endforeach
									      </select>
									   </td>
									</tr>
									<tr>
									   <th>DATE</th>
									   <td>
									      <select name="pub_date" class="form-control">
									        @foreach($arrHeader as $key => $value)
									         	<option value="{{ $key }}">{{ $value }}</option>
									        @endforeach
									      </select>
									   </td>
									</tr>
									<tr>
									   <th>TOC</th>
									   <td>
									      <select name="content" class="form-control">
									        @foreach($arrHeader as $key => $value)
									         	<option value="{{ $key }}">{{ $value }}</option>
									        @endforeach
									      </select>
									   </td>
									</tr>
									<tr>
									   <th>SUMMARY</th>
									   <td>
									      <select name="summary" class="form-control">
									        @foreach($arrHeader as $key => $value)
									         	<option value="{{ $key }}">{{ $value }}</option>
									        @endforeach
									      </select>
									   </td>
									</tr>
	          					</tbody>
	          				</table>
	          				<p>NOTE: System will upload only 1000 reports others will be skipped.</p>
	          			</div>
					</div>
			      	<div class="box-footer">
			      		<div class="col-md-12">
				         	<button type="submit" class="btn btn-primary">Upload</button>
				      	</div>
			      	</div>
			   	</form>
          	</div>
	    </section>
	</div>

@stop

@section('scripts')
<script type="text/javascript">
	
</script>
@stop