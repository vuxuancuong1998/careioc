<?php include "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
        <title><?php echo $this->helper->get_config("website_name");?></title>

        <link type="image/x-icon" href="https://cestalk.com/wp-content/uploads/2022/06/cropped-favicon-192x192.jpg" rel="icon" />

        <link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/css/bootstrap.min.css" />

        <link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/plugins/fontawesome/css/fontawesome.min.css" />
        <link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/plugins/fontawesome/css/all.min.css" />

        <link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/css/owl.carousel.min.css" />
        <link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/css/owl.theme.default.min.css" />
        <link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/css/style.css" />
		<link rel="icon" href="https://cestalk.com/wp-content/uploads/2022/06/cropped-favicon-32x32.jpg" sizes="32x32" />
		<link rel="icon" href="https://cestalk.com/wp-content/uploads/2022/06/cropped-favicon-192x192.jpg" sizes="192x192" />
		<link rel="apple-touch-icon" href="https://cestalk.com/wp-content/uploads/2022/06/cropped-favicon-180x180.jpg" />
       
		<link
		  rel="stylesheet"
		  href="https://unpkg.com/swiper@8/swiper-bundle.min.css"
		/>
        <!-- CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

        <!-- JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    </head>
    <body class="home-page">
        <div class="main-wrapper">
            <header class="header main-header">
                <div class="top-header">
                    <div class="container-fluid">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-12 col-md-8">
                                <div class="header-top-left">
                                     <i class="fa fa-globe" aria-hidden="true"></i> Đường Bà Triệu, Phường Kon Tum, Tỉnh Quảng Ngãi
                                    <ul class="nav">
                                       
                                        <li class="nav-item dropdown has-arrow flag-nav">
                                            
                                            <!-- <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"> <img src="<?php echo $template_path; ?>/assets/img/flags/vn.png" alt="" height="20" /> <span>Tiếng Việt</span> </a>
                                            <div class="dropdown-menu">
                                                <a href="javascript:void(0);" class="dropdown-item"> <img src="<?php echo $template_path; ?>/assets/img/flags/us.png" alt="" height="16" /> English </a>
                                            </div> -->
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- <div class="col-12 col-md-4">
                                <h3 class="offer-text">PHÒNG KHÁM – <span>CAO ĐẲNG KON TUM</span></h3>
                            </div> -->
                            <div class="col-12 col-md-4">
                                <div class="header-top-right">
                                    <div class="social-icon">
                                        <ul>
                                            <li>
                                                <a href="#" target="_blank"><i class="fab fa-facebook-f"></i> </a>
                                            </li>
                                            <!-- <li>
                                                <a href="#" target="_blank"><i class="fab fa-twitter"></i> </a>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank"><i class="fab fa-youtube"></i></a>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                            </li> -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <nav class="navbar navbar-expand-lg header-nav">
                    <div class="navbar-header">
                        <a id="mobile_btn" href="javascript:void(0);">
                            <span class="bar-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </a>
                        <a href="#" class="navbar-brand logo">
                            <img src="<?php echo $template_path; ?>/assets/img/logo_pk.png" class="img-fluid" alt="Logo" />
                        </a>
                    </div>
                    <div class="main-menu-wrapper">
                        <div class="menu-header">
                            <a href="#" class="menu-logo">
                                <img src="https://cestalk.com/wp-content/uploads/2022/06/cestalk-logo.svg" class="img-fluid" alt="Logo" />
                            </a>
                            <a id="menu_close" class="menu-close" href="javascript:void(0);">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                        <ul class="main-nav">
                            <li class="active">
                                <a href="<?php echo XC_URL;?>">Trang chủ</a>
                            </li>
                            <li class="has-submenu">
                                <a href="#">Giới thiệu <i class="fas fa-chevron-down"></i></a>
                                <ul class="submenu">
                                    <li><a href="#">Về chúng tôi</a></li>
                                    <li><a href="#">Triết lý văn hoá</a></li>
                                    <li><a href="#">Tầm nhìn</a></li>
                                    <li><a href="#">Sứ mệnh</a></li>
                                    <li><a href="#">Thương hiệu</a></li>
                                </ul>
                            </li>
							<li class="has-submenu">
                                <a href="#">Diễn giả <i class="fas fa-chevron-down"></i></a>
                                <ul class="submenu">
                                    <li><a href="#">Danh sách diễn giả</a></li>
                                    <li><a href="#">Đăng ký diễn giả</a></li>
                                </ul>
                            </li>
							<li class="">
                                <a href="<?php echo XC_URL;?>">Sự kiện</a>
                            </li>
                            <li class="has-submenu">
                                <a href="#">Tin tức/Blog <i class="fas fa-chevron-down"></i></a>
                                <ul class="submenu">
                                    <li><a href="#">Tin hoạt động</a></li>
                                    <li><a href="#">Tin cộng đồng</a></li>
                                    <li><a href="#">Hướng dẫn</a></li>
                                </ul>
                            </li>
							<li class="">
                                <a href="<?php echo XC_URL;?>">Liên hệ</a>
                            </li>
                            <li class="nav-item align-items-center d-flex">
                                <a class="nav-link header-login" href="#">Đăng ký/Đăng nhập</a>
                            </li>
							
                            <li class="login-link">
                                <a href="#">Đăng ký/Đăng nhập</a>
                            </li>
                        </ul>
                    </div>
                    <ul class="nav header-navbar-rht">
                        <li class="nav-item contact-item">
                            <a href="javascript:void(0);" class="header-contact-img">
                                <img src="<?php echo $template_path; ?>/assets/img/mic-icon.png" alt="mic" />
                            </a>
                            <a href="javascript:void(0);" class="header-contact-detail">
                                <p class="contact-header">Giỏ hàng</p>
                                <p class="contact-info-header">500.000đ</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </header>
