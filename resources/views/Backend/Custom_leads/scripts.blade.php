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
		var targetURL = adminPath+'/custom_leads/getData'; 

	    var action            = action;
	    var pipline_status    = $('#pipline_status').val();
	    var lead_type         = $('#lead_type').val();
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
	                lead_status:lead_type,
	                pipline_status:pipline_status,
	                search_order_date:search_order_date
	            },
	            dataFilter: function(data){
	                json = $.parseJSON( data );
	                json.recordsFiltered = json.recordsFiltered;
	                return JSON.stringify( json ); 
	            }
	        },
	        columns: [
	            { "data": "id",           "orderseble": "true"},
	            { "data": "report_id",    "orderseble": "true"},
	            { "data": "report_title", "orderseble": "true"},
	            { "data": "country",      "orderseble": "true"},
	            { "data": "fk_source_id", "orderseble": "true"},
	            { "data": "regions",      "orderseble": "true"},
	            { "data": "created_date", "orderseble": "true"},
	            { "data": "actions"}
	        ],
	        columnDefs: [
	            {    
	                'searchable':false,
	                'orderable':false,
	                'render': function (data, type, full, meta){
	                    return data;
	                },
	                'targets': [0,1,2,3,5,6,7],
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

	$(document).on('click','#btn_action_search',function()
	{
	    $('#listingTable').DataTable().destroy();
	    filterTable('search');
	});

	$(document).on('click','#btn_action_today',function()
	{
	    $('#listingTable').DataTable().destroy();
	    filterTable('today');
	});
	
	$(document).on('change','#download_reports_type',function()
	{
	  	if($(this).val() == 'date_range')
	  	{
	  		$('#date_range_div').show();
	  	}
	  	else
	  	{
	  		$('#date_range_div').hide();
	  	}
	});

	$(document).on('click','.btnDownload',function()
	{
		$('.box').LoadingOverlay("show", 
		{
		    image       : "",
		    background  : "rgba(165, 190, 100, 0.4)",
		    fontawesome : "fa fa-cog fa-spin"
		});

	  	setTimeout(function ()
		{
			$('.box').LoadingOverlay("hide");
			$('#dowloadReportModal').modal('hide');
		}, 2000);
	});

	function actionSource(element)
	{
	    var $this = $(element);
	    var lead_id = $this.attr('data-rwid');
	    var source_id = $this.attr('data-source-id');

	    $('#source').select2().select2('val',source_id)
	    $('#lead_id').val(lead_id);
	    $('#assignSourceModal').modal('show');
	}

	function actionAssignSource(element)
	{
		$('.box').LoadingOverlay("show", 
		{
		    image       : "",
		    background  : "rgba(165, 190, 100, 0.4)",
		    fontawesome : "fa fa-cog fa-spin"
		});

		var $this = $(element);            		
		var formData = new FormData($this[0]);	
		var action = $this.attr('action');

		$('#submit_button').hide();
		$('#assignSourceModal').modal('hide');

		$.ajax(
		{
		  	type: 'POST',
		  	url: action,
		  	data: formData,
		  	processData: false,
		  	contentType: false,
		  	success: function(data)
		  	{
		  		toastr.clear();
		  		$('.box').LoadingOverlay("hide");

		    	if (data.status == 'success') 
		    	{
		    		$this[0].reset();
	    			toastr.success(data.msg);
		    		setTimeout(function ()
		    		{
		    			$('#submit_button').show();
		    			location.reload(true);
		    		}, 1500);
		    	}
		    	else
		    	{
		    		$('#submit_button').show();
		    		toastr.error(data.msg);
		    	}
		
		  	},
		  	error: function (data)
		  	{
		  		$('.box').LoadingOverlay("hide");
		    	$('#submit_button').show();

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

		return false;
	}
</script>