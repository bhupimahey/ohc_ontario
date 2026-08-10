var pagerTable=$('#pendingfee_table').DataTable({
         "bProcessing": true,
         "serverSide": true,
		 "bStateSave": true,
		 "ordering": false,
		 bFilter: false,
		 dom: 'Blfrtip',
		 buttons: [
            {
                extend:    'excelHtml5',
                text:      '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel',
				exportOptions: {
                columns: [0,1,2,3,4,5,6]
				}
            },        
        ],		
	     "ajax":{
            url : baseurl+"/report/ajax_pendingfee_report_view", 
            type: "post", 
			"data": function ( send_data ) {
			     send_data.filter_search_name    = $("#filter_search_name").val();
				 send_data.filter_daterange  =  $("#filter_daterange").val();
              }
          }
        }); 
  
      
   $('#search_rpt_btn').on("click",function(){ 
       $('#pendingfee_table').DataTable().ajax.reload();
       
   });
   
  

