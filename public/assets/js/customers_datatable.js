var pagerTable=$('#customers_table').DataTable({
         "bProcessing": true,
         "serverSide": true,
		 "bStateSave": true,
		 "ordering": false,
		 bFilter: false,
		 dom: 'Blfrtip',
		 buttons: [],		
	     "ajax":{
            url : baseurl+"/customers/ajax_customers_view", 
            type: "post", 
			"data": function ( send_data ) {
			     send_data.filter_search_name    = $("#filter_search_name").val();
				 send_data.filter_search_mobile  = $("#filter_search_mobile").val();
				 send_data.filter_daterange      = $("#filter_daterange").val();
            },
            error: function(){              
            }
          }
        }); 
  
      
  $('#search_users_btn').on("click",function(){ 
       $('#customers_table').DataTable().ajax.reload();
       
} );

$("#export_excel").on("click",function(){
	var filter_search_name   = $("#filter_search_name").val();
	var filter_search_mobile = $("#filter_search_mobile").val();
	var filter_daterange     = $("#filter_daterange").val();
	
window.location.href=	baseurl+"/customers/export_customers/?filter_search_name="+filter_search_name+"&filter_search_mobile="+filter_search_mobile+"&filter_daterange="+filter_daterange;
	
});