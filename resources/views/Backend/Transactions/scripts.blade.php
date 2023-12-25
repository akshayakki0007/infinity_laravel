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
		var targetURL = adminPath+'/transactions/getData'; 
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
	            { "data": "report_id",      "orderseble": "true"},
	            { "data": "name",           "orderseble": "true"},
	            { "data": "email_id",       "orderseble": "true"},
	            { "data": "contact_no",     "orderseble": "true"},
	            { "data": "company_name",   "orderseble": "true"},
	            { "data": "job_title",      "orderseble": "true"},
	            { "data": "licence_price",  "orderseble": "true"},
	            { "data": "payment_type",   "orderseble": "true"},
	            { "data": "payment_status", "orderseble": "true"},
	            { "data": "created_at",     "orderseble": "true"},
	            { "data": "actions"}
	        ],
	        columnDefs: [
	            {    
	                'searchable':false,
	                'orderable':false,
	                'render': function (data, type, full, meta){
	                    return data;
	                },
	                'targets': [0,11],
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
	    var targetURL = adminPath+'/transactions/destroy/'+id;

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

</script>