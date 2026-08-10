<?php

    $local_session  = \Config\Services::session(); // Needed for Point 5
    $user_id        =  $local_session->get('s_user_id');
    $s_user_type    =  $local_session->get('s_user_type');
    $s_name         =  $local_session->get('s_name');
    $photo_path     =  $local_session->get('s_photo_path');  
    $common_model   = new \App\Models\CommonModel;
    $referrals_list = $common_model->total_enquiry_list();
    $uri            = service('uri');
    if($uri->getSegment(1)=='referrals_result'){
       $referral_type  = $uri->getSegment(3);
       $referral_id    = $uri->getSegment(2);
    }
   else{
       $referral_type   = 'inquiry';
       $referral_id     = '';
      }
      
    $folder_path   = getenv('InstructorPath');
    $base_url      = base_url().'/'.$folder_path;    
?>
<style>
    .navbar-container .search-input.open{padding: 16px!important;}
</style>
<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
                </ul>
                
            </div>
            <ul class="nav navbar-nav align-items-center ms-auto">
                
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon" data-feather="moon"></i></a></li>
                
                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder">
                            <?php if($s_user_type!='S'){ ?>
                            <?php echo $s_name;?><?php } else{?> Administrator<?php } ?></span></div><span class="avatar">
                            <?php if($photo_path==''){ ?>
                            <img class="round" src="<?php echo base_url();?>/public/app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="40" width="40">
                            <?php } else{ ?>
                           <img class="round" src="<?php echo base_url();?>/public/documents/teachers/<?php echo $photo_path;?>" alt="avatar" height="40" width="40">
                            <?php } ?>
                        <span class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                      <a class="dropdown-item" href="<?php echo $base_url;?>logout"><i class="me-50" data-feather="power"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>