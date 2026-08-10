<?php
namespace App\Controllers;
class Notauthorized extends BaseController
{
	public function __construct()
    {  
	$this->session 	= \Config\Services::session();
	}
    public function index()
    {	
	    return view('notauthorized');
	  
	}

}
