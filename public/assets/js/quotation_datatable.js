var pagerTable=$('#quotation_table').DataTable({
         "bProcessing": true,
         "serverSide": true,
		 "bStateSave": true,
		 "ordering": false,
		 bFilter: false,
		 dom: 'Blfrtip',
		 buttons: [],		
	     "ajax":{
            url : baseurl+"/quotations/ajax_quotations_view", 
            type: "post", 
			"data": function ( send_data ) {
			     send_data.filter_search_name    = $("#filter_search_name").val();
				 send_data.filter_search_mobile  = $("#filter_search_mobile").val();
			    		
            }
          }
        }); 
  
      
  $('#search_users_btn').on("click",function(){ 
       $('#quotation_table').DataTable().ajax.reload();
       
} );