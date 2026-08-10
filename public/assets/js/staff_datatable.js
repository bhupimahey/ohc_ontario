var pagerTable=$('#staff_table').DataTable({
         "bProcessing": true,
         "serverSide": true,
		 "bStateSave": true,
		 "ordering": false,
		 bFilter: false,
		 dom: 'Blfrtip',
		 buttons: [],		
	     "ajax":{
            url : baseurl+"/staff/ajax_staff_view", 
            type: "post", 
			"data": function ( send_data ) {
			     send_data.filter_search_name    = $("#filter_search_name").val();
				 send_data.filter_search_mobile  = $("#filter_search_mobile").val();
			    		
            }
          }
        }); 
  
      
  $('#search_staff_btn').on("click",function(){ 
       $('#staff_table').DataTable().ajax.reload();
       
} );