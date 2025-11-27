<?php include "config.php"; ?>
<!doctype html>
<html lang="en">
  
<head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Quản trị - CESTalk</title>
      
      <link rel="stylesheet" href="<?php echo $template_path; ?>/fonts/fontawesome/css/all.min.css"/>
      <link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/css/backend-plugin.min.css">
      <link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/css/backend.css?v=1.0.0">  
      <link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/css/custom.css?v=1.0.0">  
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css" integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	  
	  <script src="<?php echo $template_path; ?>/backend/assets/js/backend-bundle.min.js"></script>
    <!-- Chart Custom JavaScript -->
    <script src="<?php echo $template_path; ?>/backend/assets/js/customizer.js"></script>
    
    <script src="<?php echo $template_path; ?>/backend/assets/js/sidebar.js"></script>
	  </head>
  <body class="  ">
    <!-- loader Start -->
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>
    <!-- loader END -->
    <!-- Wrapper Start -->
    <div class="wrapper">
      <div class="iq-sidebar  sidebar-default  ">
          <div class="iq-sidebar-logo d-flex align-items-end justify-content-between">
               <a href="index.html" class="header-logo">
                  <img src="https://cestalk.com/wp-content/uploads/2022/06/Cestalk-white.svg" class="img-fluid rounded-normal light-logo" alt="logo">
                  <img src="https://cestalk.com/wp-content/uploads/2022/06/Cestalk-white.svg" class="img-fluid rounded-normal d-none sidebar-light-img" alt="logo">    
              </a>
              <div class="side-menu-bt-sidebar-1">
                      <svg xmlns="http://www.w3.org/2000/svg" class="text-light wrapper-menu" width="30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                  </div>
          </div>
          <div class="data-scrollbar" data-scroll="1">
              <?php include_once "nav.php"; ?>
              <div class="pt-5 pb-5"></div>
          </div>
      </div>
       <div class="iq-top-navbar">
			<?php include_once "top-nav.php"; ?>
      </div>