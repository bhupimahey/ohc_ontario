<style>
table {color:#000;}
 .select2-container--default .select2-selection--single .select2-selection__arrow b{margin-left: -4px;
    margin-top: -2px;position: relative!important;top: 50%;width: 0;left:0!important;height: 0!important;border-width:0px!important;
 }
 </style>
 <script>
 
function closemodal(){
    $(".modal").modal("hide");
}

        $(window).on('load',  function(){
        if (feather) {
          feather.replace({ width: 14, height: 14 });
        }
        
        $("body").addClass("menu-collapsed");
      });
      
      function confirm_closed(path){
         Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, closed it!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ms-1'
        },
        buttonsStyling: false
      }).then(function (result) {
        if (result.value) {
          window.location.href=path;
        }
      });
	  return false;  
          
          
      }
      
     $(document).on('click', '.markcomplete', function () {
		var workingtid = $(this).data('id');
		var modal = $('#markcompletediv'+workingtid);
		
	   var basicPickr = $('.flatpickr-basic');
		  basicPickr.flatpickr();
          modal.modal('show');
      });
   
   
     $(document).on('click', '.addworkinghours', function () {
		var workingtid = $(this).data('id');
		var modal = $('#workinghoursdiv'+workingtid);
        modal.modal('show');
    });
   
      
      $(document).on('click', '.payment', function () {
		var paymenytid = $(this).data('id');
		var modal = $('#paymentBy'+paymenytid);
        modal.modal('show');
    });
    $(document).on('click', '.activitymodal', function () {		var paymenytid = $(this).data('id');		var modal = $('#activityBy'+paymenytid);        modal.modal('show');    });
    
    $(document).on("click",".view_remarks_modal",function(){
    var modal_id = $(this).data("id");
    $("#RemarksModal"+modal_id).modal("show");
    
});


    $(document).on('click', '.close_paymenthist_model', function () {
     	//var paymenytid = $(this).data('id');
	  //  $('#paymentBy'+paymenytid).modal('hide');
	    
	      $('.modal').modal('hide');
    });
    
    $(document).on('click', '.close_addpaymnt_model', function () {
	//	var paymenytid = $(this).data('id');
	//	var modaldd = $('#AddPaymentBy'+paymenytid);
     //   modaldd.modal('hide');
        
        $('.modal').modal('hide');
    });
    
    $(document).on('click', '.addpayment', function () {   
	    var treatmentid = $(this).data('id');
		var modal = $('#AddPaymentBy'+treatmentid);
		modal.modal('show');
    });
      
      
	  function confirm_delete(path){
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-danger ms-1'
        },
        buttonsStyling: false
      }).then(function (result) {
        if (result.value) {
          window.location.href=path;
        }
      });
	  return false;
    }
    </script>
  </body>
  <!-- END: Body-->
</html>