<?php
namespace App\Libraries;

class message_output {
	var $success       = array();
	var $error         = array();
    public $session;
 function __construct(){			
		$this->session  = \Config\Services::session();	
		if($this->session->getFlashdata('_error')) $this->session->getFlashdata('_error');
		if($this->session->getFlashdata('_success')) $this->session->getFlashdata('_success');				
	}	

	function set_error($msg){		
		$this->session->setFlashdata('_error', $msg);		
	}
	
	function set_success($msg){
		$this->session->setFlashdata('_success', $msg);	
		
	}
	
	function run(){
		
			$success = $this->session->getFlashdata('_success');
			$error   = $this->session->getFlashdata('_error');
	
		if($success!=''){
			?>
           <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="alert-body">
          	<?php echo $success; ?>
			</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
           </div>
			<?php			
		}
		if($error!=''){
			?>
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="alert-body">
             <?php echo $error; ?>
			</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
           </div>		 
			<?php	
		}
	}

}