<style>
 .select2-container--default .select2-selection--single .select2-selection__arrow b{margin-left: -4px;
    margin-top: -2px;position: relative!important;top: 50%;width: 0;left:0!important;height: 0!important;border-width:0px!important;
 }
 </style>
 <script>
   $("#SearchReferrals").select2({ placeholder: "Choose Referral" });
   $("#SearchReferralsType").select2({ placeholder: "Choose Type" });
   
   $(document).on("click","#search_referrals_btn",function(){
     var referral_id   = $("#SearchReferrals").val();
     var referral_type = $("#SearchReferralsType").val();
      if(referral_id!='' && referral_type!='')
        window.location.href ="<?php echo base_url();?>/referrals_result/"+referral_id+"/"+referral_type;
      })
 
      $(window).on('load',  function(){
        if (feather) {
          feather.replace({ width: 14, height: 14 });
        }
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