<?php
    $local_session = \Config\Services::session(); // Needed for Point 5
    $user_id       =  $local_session->get('s_user_id');
    $s_user_type   =  $local_session->get('s_user_type');
  ?>
  <style>
      .main-menu .navbar-header .navbar-brand .brand-logo img {
          max-width: 170px!important;
        }
      .vertical-layout.vertical-menu-modern.menu-collapsed .main-menu{ width: 66px;}
  </style>
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item me-auto"><a class="navbar-brand" href="<?php echo base_url();?>"><span class="brand-logo">
                            <img src="<?php echo base_url();?>/public/documents/invoice-logo.jpeg" style="height: 75px;margin-top: -24px;"></span>
                     
                    </a></li>
                
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class=" nav-item"><a class="d-flex align-items-center" href="<?php echo base_url();?>/dashboard"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboards">Dashboard</span></a>
                
                </li>
                
                <li class=" nav-item"><a class="d-flex align-items-center" ><i data-feather="shield"></i><span class="menu-title text-truncate" data-i18n="Email">Jobs</span></a>
                 <ul class="menu-content">
                        <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/jobs/add"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Add Jobs</span></a>
                        </li>
                        <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/jobs"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Preview">View Jobs</span></a>
                        </li>
                    </ul>
				</li>
				 
				
                <li class=" nav-item"><a class="d-flex align-items-center" ><i data-feather="layers"></i><span class="menu-title text-truncate" data-i18n="Email">Reports</span></a> 
				    <ul class="menu-content">
				         <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/report/profit_summary"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Profit Summary</span></a></li>
                      <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/report/pending_payment"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Pending Payments</span></a></li>
                       <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/report/staff_payroll"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Staff Payroll</span></a></li>
                       <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/report/staff_activity"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Staff Activity</span></a></li>
                   
                   <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/dashboard/accounts"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Accounts</span></a></li>
                   
                    
                    
                    </ul>  
               </li>
               
                
                
                 <li class=" nav-item"><a class="d-flex align-items-center" ><i data-feather="server"></i><span class="menu-title text-truncate" data-i18n="Email">Staff</span></a>
                 <ul class="menu-content">
                        <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/staff/add"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Add Staff</span></a>
                        </li>
                        <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/staff"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Preview">View Staff</span></a>
                        </li>
                        
                    </ul>
				</li>
				
				
                  <li class=" nav-item"><a class="d-flex align-items-center" ><i data-feather="grid"></i><span class="menu-title text-truncate" data-i18n="Email">Admins</span></a>
                 <ul class="menu-content">
                        <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/users/add"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Add Admins</span></a>
                        </li>
                        <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/users"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Preview">View Admins</span></a>
                        </li>
                        
                    </ul>
				</li>
                <li class=" nav-item"><a class="d-flex align-items-center" ><i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="Email">Customers</span></a>
                 <ul class="menu-content">
                        <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/customers/add"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Add Customer</span></a>
                        </li>
                        <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/customers"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Preview">View Customers</span></a>
                        </li>
                        
                    </ul>
				</li>
               
                <li class=" nav-item"><a class="d-flex align-items-center" ><i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="Email">Vendors</span></a>
                 <ul class="menu-content">
                        <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/vendors/add"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Add Vendor</span></a>
                        </li>
                        <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/vendors"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Preview">View Vendors</span></a>
                        </li>
                        
                    </ul>
				</li>
               <li class=" nav-item"><a class="d-flex align-items-center" ><i data-feather="clipboard"></i><span class="menu-title text-truncate" data-i18n="Email">Quotations</span></a>
                 <ul class="menu-content">
                        <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/quotations/add"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Add Quotation</span></a>
                        </li>
                        <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/quotations"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Preview">View Quotations</span></a>
                        </li>
                        
                    </ul>
				</li>
				
				
			
				 
				 <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/purchase_orders"><i data-feather="shopping-cart"></i><span class="menu-item text-truncate" data-i18n="List">Purchase Orders</span></a>
                       
				
               
                <li class=" nav-item"><a class="d-flex align-items-center" ><i data-feather="settings"></i><span class="menu-title text-truncate" data-i18n="Email">Settings</span></a> 
				    <ul class="menu-content">
                      <li><a class="d-flex align-items-center" href="<?php echo base_url();?>/heads"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Payment Heads</span></a></li>
                    </ul>  
               </li>
            </ul>
        </div>
    </div>