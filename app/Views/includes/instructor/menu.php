<?php
    $local_session = \Config\Services::session(); 
    $user_id       = $local_session->get('s_user_id');
    $s_user_type   = $local_session->get('s_user_type');
    $folder_path   = getenv('InstructorPath');
    $base_url      = base_url().'/'.$folder_path;
  ?>
  <style>
      .main-menu .navbar-header .navbar-brand .brand-logo img {
        max-width: 170px!important;
       }
      .vertical-layout.vertical-menu-modern.menu-collapsed .main-menu{width: 66px;}
  </style>
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                 <li class="nav-item me-auto"><a class="navbar-brand" href="<?php echo base_url();?>"><span class="brand-logo">
                            <img src="<?php echo base_url();?>/public/app-assets/images/cfalogo.png" ></span>
                     
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class=" nav-item"><a class="d-flex align-items-center" href="<?php echo $base_url;?>dashboard"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboards">Dashboard</span></a>
                
                </li>
               
                <li class=" nav-item"><a  href="<?php echo $base_url;?>timetable" class="d-flex align-items-center" ><i data-feather="clock"></i><span class="menu-item text-truncate" data-i18n="List">My Timetable</span></a>
                
				</li>
				
				<li class=" nav-item"><a  href="<?php echo $base_url;?>leaves" class="d-flex align-items-center" ><i data-feather="clock"></i><span class="menu-item text-truncate" data-i18n="List">My Leaves</span></a>
                
				</li>
				
			<li class=" nav-item"><a  href="<?php echo $base_url;?>sessions" class="d-flex align-items-center" ><i data-feather="clock"></i><span class="menu-item text-truncate" data-i18n="List">My Sessions</span></a>
                
				</li>	
            </ul>
        </div>
    </div>