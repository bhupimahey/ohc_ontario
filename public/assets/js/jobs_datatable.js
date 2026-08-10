var pagerTable=$('#jobs_table').DataTable({
         "bProcessing": true,
         "serverSide": true,
		 "bStateSave": true,
		 "ordering": false,
		 bFilter: false,
		 dom: 'Blfrtip',
		 buttons: [],		
	     "ajax":{
            url : baseurl+"/jobs/ajax_jobs_view", 
            type: "post", 
			"data": function ( send_data ) {
			     send_data.filter_customer_id  = $("#filter_customer_id").val();
				 send_data.filter_user_id      = $("#filter_user_id").val();
			     send_data.filter_daterange    = $("#filter_daterange").val();
			     send_data.filter_job_status    = $("#filter_job_status").val();		
            },
            error: function(){              
            }
          }
        }); 
  
      
  $('#search_users_btn').on("click",function(){ 
       $('#jobs_table').DataTable().ajax.reload();
       
} );

$(document).on("click",".view_remarks_modal",function(){
    var modal_id = $(this).data("id");
    $("#RemarksModal"+modal_id).modal("show");
    
});
$("#export_excel").on("click",function(){
	var filter_customer_id   = $("#filter_customer_id").val();
	var filter_user_id = $("#filter_user_id").val();
	var filter_daterange     = $("#filter_daterange").val();
	var filter_job_status     = $("#filter_job_status").val();
	
window.location.href=	baseurl+"/jobs/export_jobs/?filter_customer_id="+filter_customer_id+"&filter_user_id="+filter_user_id+"&filter_daterange="+filter_daterange+"&filter_job_status="+filter_job_status;
	
});