var pagerTable=$('#users_table').DataTable({
         "bProcessing": true,
         "serverSide": true,
		 "bStateSave": true,
		 "ordering": false,
		 bFilter: false,
		 dom: 'Blfrtip',
		 buttons: [],		
	     "ajax":{
            url : baseurl+"/users/ajax_users_view", 
            type: "post", 
			"data": function ( send_data ) {
			     send_data.filter_search_name    = $("#filter_search_name").val();
				 send_data.filter_search_mobile  = $("#filter_search_mobile").val();
			     send_data.filter_user_role      = $("#filter_user_role").val();
			    		
            },
            error: function(){              
            }
          }
        }); 
  
      
  $('#search_users_btn').on("click",function(){ 
       $('#users_table').DataTable().ajax.reload();
       
} );