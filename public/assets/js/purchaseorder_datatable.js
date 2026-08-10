var pagerTable=$('#purchaseorder_table').DataTable({
         "bProcessing": true,
         "serverSide": true,
		 "bStateSave": true,
		 "ordering": false,
		 bFilter: false,
		 dom: 'Blfrtip',
		 buttons: [],		
	     "ajax":{
            url : baseurl+"/purchase_orders/ajax_purchaseorders_view", 
            type: "post", 
			"data": function ( send_data ) {
			     send_data.filter_vendor_id    = $("#filter_vendor_id").val();
			     send_data.filter_job_id       = $("#filter_job_id").val();
			     send_data.filter_daterange    = $("#filter_daterange").val();		
            },
            error: function(){              
            }
          },
         footerCallback: function (row, data, start, end, display) {
        let api = this.api();
 
        // Remove the formatting to get integer data for summation
        let intVal = function (i) {
            return typeof i === 'string'
                ? i.replace(/[\$,]/g, '') * 1
                : typeof i === 'number'
                ? i
                : 0;
        };
 
        // Total over all pages
        total = api
            .column(4)
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);
 
        // Total over this page
        pageTotal = api
            .column(4, { page: 'current' })
            .data()
            .reduce((a, b) => intVal(a) + intVal(b), 0);
 
 
        // Update footer
        api.column(8).footer().innerHTML =
          (pageTotal).toLocaleString('en-US', {
  style: 'currency',
  currency: 'USD',
});
    } 
        }); 
  
      
  $('#search_users_btn').on("click",function(){ 
       $('#purchaseorder_table').DataTable().ajax.reload();
       
} );

$(document).on("click",".view_remarks_modal",function(){
    var modal_id = $(this).data("id");
    $("#RemarksModal"+modal_id).modal("show");
    
});
