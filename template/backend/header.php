<?php include "config.php";?>
<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
      <title>MMExpress - HiCarrier Platform</title>
      <meta name="description" content="MMExpress - HiCarrier Platform">
      <meta name="author" content="VCFMedia">
      <meta name="robots" content="noindex, nofollow">
      <meta property="og:title" content="MMExpress - HiCarrier Platform">
      <meta property="og:site_name" content="MMExpress - HiCarrier Platform">
      <meta property="og:description" content="MMExpress - HiCarrier Platform">
      <meta property="og:type" content="website">
      <meta property="og:url" content="">
      <meta property="og:image" content="">
      <link rel="shortcut icon" href="<?php echo $template_path;?>/assets/media/favicons/favicon.png">
      <link rel="icon" type="image/png" sizes="192x192" href="<?php echo $template_path;?>/assets/media/favicons/favicon-192x192.png">
      <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $template_path;?>/assets/media/favicons/apple-touch-icon-180x180.png">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap">
	  <link rel="stylesheet" href="<?php echo $template_path;?>/assets/js/plugins/select2/css/select2.min.css">
      <link rel="stylesheet" id="css-main" href="<?php echo $template_path;?>/assets/css/dashmix.min-3.1.css">
	  <script src="<?php echo $template_path;?>/assets/js/dashmix.core.min-3.1.js"></script>
      <script src="<?php echo $template_path;?>/assets/js/dashmix.app.min-3.1.js"></script>
      <script src="<?php echo $template_path;?>/assets/js/plugins/chart.js/Chart.bundle.min.js"></script>
	  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
      
   </head>
   <body>
      <div id="page-container" class="sidebar-o enable-page-overlay side-scroll page-header-fixed main-content-narrow side-trans-enabled page-header-dark">
         <?php include_once "aside.php";?>
         <?php include_once "nav.php";?>
		 
		 
         
         <header id="page-header">
            <div class="content-header">
               <div>
                  <button type="button" class="btn btn-dual" data-toggle="layout" data-action="sidebar_toggle">
                  <i class="fa fa-fw fa-bars"></i>
                  </button>
                  <button type="button" class="btn btn-dual" data-toggle="layout" data-action="header_search_on">
                  <i class="fa fa-fw fa-search"></i> <span class="ml-1 d-none d-sm-inline-block">Search</span>
                  </button>
               </div>
               <div>
                  <div class="dropdown d-inline-block">
                     <button type="button" class="btn btn-dual" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <i class="fa fa-fw fa-user d-sm-none"></i>
                     <span class="d-none d-sm-inline-block"><?php echo $_SESSION['staff']['fullname'];?></span>
                     <i class="fa fa-fw fa-angle-down ml-1 d-none d-sm-inline-block"></i>
                     </button>
                     <div class="dropdown-menu dropdown-menu-right p-0" aria-labelledby="page-header-user-dropdown">
                        <div class="bg-primary rounded-top font-w600 text-white text-center p-3">
                           User Options
                        </div>
                        <div class="p-2">
                           
                           <a class="dropdown-item" href="<?php echo XC_URL;?>/logout">
                           <i class="far fa-fw fa-arrow-alt-circle-left mr-1"></i> Sign Out
                           </a>
                        </div>
                     </div>
                  </div>
                  <div class="dropdown d-inline-block">
                     <button type="button" class="btn btn-dual" id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <i class="fa fa-fw fa-bell"></i>
                     <span class="badge badge-secondary badge-pill">5</span>
                     </button>
                     <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0" aria-labelledby="page-header-notifications-dropdown">
                        <div class="bg-primary rounded-top font-w600 text-white text-center p-3">
                           Notifications
                        </div>
                        <ul class="nav-items my-2">
                           
                        </ul>
                        <div class="p-2 border-top">
                           <a class="btn btn-light btn-block text-center" href="javascript:void(0)">
                           <i class="fa fa-fw fa-eye mr-1"></i> View All
                           </a>
                        </div>
                     </div>
                  </div>
                  <button type="button" class="btn btn-dual" data-toggle="layout" data-action="side_overlay_toggle">
                  <i class="far fa-fw fa-list-alt"></i>
                  </button>
               </div>
            </div>
            <div id="page-header-search" class="overlay-header bg-header-dark">
               <div class="bg-white-10">
                  <div class="content-header">
                     <form class="w-100" action="#" method="POST">
                        <div class="input-group">
                           <div class="input-group-prepend">
                              <button type="button" class="btn btn-alt-primary" data-toggle="layout" data-action="header_search_off">
                              <i class="fa fa-fw fa-times-circle"></i>
                              </button>
                           </div>
                           <input type="text" class="form-control border-0" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
                        </div>
                     </form>
                  </div>
               </div>
            </div>
            <div id="page-header-loader" class="overlay-header bg-header-dark">
               <div class="bg-white-10">
                  <div class="content-header">
                     <div class="w-100 text-center">
                        <i class="fa fa-fw fa-sun fa-spin text-white"></i>
                     </div>
                  </div>
               </div>
            </div>
         </header>