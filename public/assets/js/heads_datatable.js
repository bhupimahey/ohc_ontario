var pagerTable=$('#paymentheads_table').DataTable({
         "bProcessing": true,
         "serverSide": true,
		 "bStateSave": true,
		 "ordering": false,
		 bFilter: false,
		 dom: 'Blfrtip',
		 buttons: [],		
	     "ajax":{
            url : baseurl+"/heads/ajax_heads_view", 
            type: "post", 
			"data": function ( send_data ) {
			  
			    		
            },
            error: function(){              
            }
          }
        }); 
  
      
  $('#search_users_btn').on("click",function(){ 
       $('#paymentheads_table').DataTable().ajax.reload();
       
} );