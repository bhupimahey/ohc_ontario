var pagerTable=$('#activity_table').DataTable({
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
				
            },        
        ],	
	     "ajax":{
            url : baseurl+"/report/ajax_staff_activity", 
            type: "post", 
			"data": function ( send_data ) {
			     send_data.filter_staff_id    = $("#filter_staff_id").val();
				 send_data.filter_daterange  = $("#filter_daterange").val();
			    		
            }
          }
        }); 
  
      
  $('#search_staff_btn').on("click",function(){ 
       $('#activity_table').DataTable().ajax.reload();
       
} );
$('#download_rpt_btn').on("click",function(){ 
      
       $(".buttons-html5").trigger("click");
   });