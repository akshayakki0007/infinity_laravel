@extends('Backend.Partials.master')

@section('title')
	{{ $moduleAction }}
@stop

@section('styles')
<style type="text/css">
	#listingTable label {
		font-weight : 500;
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
      		<div class="box box-primary">
			   	<form onsubmit="return actionSave(this)" action="{{url($modulePath.'/update_access')}}"  method="POST" enctype="multipart/form-data">
			   		<div class="box-header with-border">
		          		<h3 class="box-title"></h3>
			          	<div class="box-tools pull-right">
			          		<a title="Back to Repository" href="{{ url($modulePath) }}" class="btn btn-social btn-linkedin" ><i class="fa fa-arrow-left"></i>{{'Back'}}</a>
			          	</div>
		        	</div>
			      	<div class="box-body">
			      		<div class="col-md-6">
              				@if(Session::has('message'))
								<p class="alert {{ Session::get('alert-class', Session::has('alert-class')) }}">{{ Session::get('message') }}</p>
							@endif
              			</div>
			      		<div class="col-md-12">
			      			<input type="hidden" name="role" value="<?php echo $slug ?>">
						   	<table id="listingTable" class="table table-bordered dataTable" role="grid" aria-describedby="example2_info">
	          					<thead>
	          						<th>Modules</th>
	          						<th>List</th>
	          					</thead>
	          					<tbody>
	          						@if(count($arrModules) > 0)
		              					@foreach($arrModules as $key => $val)
		              						<?php 
	              								$arrValue = $val->getPermissons($slug,$val->slug);
	              							?>
		              						<input type="hidden" name="modules[]" value="{{ $val->slug }}">
				          					@if(!empty($arrValue))	
				          						<tr>
				          							<td><label for="slug_{{$key}}">{{ $val->name }}</label></td>
				          							<td><input type="checkbox" name="<?php echo $val->slug; ?>[list]" id="slug_{{$key}}" value="1"  <?php if($arrValue->list == '1'){ echo 'checked'; } ?> ></td>
				          						</tr>
				          					@else	
				          						<tr>
				          							<td><label for="slug_{{$val->name}}">{{ $val->name }}</label></td>
				          							<td><input type="checkbox" name="<?php echo $val->slug; ?>[list]" id="slug_{{$val->name}}" value="1" ></td>
				          						</tr>
				          					@endif
			          					@endforeach
			          				@endif
	          					</tbody>
	          				</table>
	          				<p><label for="select_all"><strong>Check all</strong>&nbsp;&nbsp;<input type="checkbox" name="check_all" id="select_all" value="1" ></label></p>
						</div>
					</div>
			      	<div class="box-footer">
			      		<div class="col-md-12">
				         	<button type="submit" class="btn btn-primary">Update</button>
				      	</div>
			      	</div>
			   	</form>
          	</div>
	    </section>
	</div>
@stop

@section('scripts')
<script type="text/javascript">
		$("#select_all").click(function () {
            $("input[type='checkbox']").attr("checked", this.checked);
        });
	</script>
@stop