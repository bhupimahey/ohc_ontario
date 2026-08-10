<?php
namespace App\Libraries;

class auth_session {
 public $session;
 function __construct(){			
		$this->session  = \Config\Services::session();						
	 }	

  function admin_restrict(){			 
	   if ($this->session->get('s_user_id')=='' )
			{
            header("Location:".base_url().'/login');
			die();
			}
	}

function role_restrict($role_type){			 

        if ( $this->session->get('s_user_id')>0 && $this->session->get('s_user_type')!=$role_type)
			{
          		  header("Location:".base_url()."/notauthorized");            
			   die();
			}
	}
	
	
}