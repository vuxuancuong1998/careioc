<nav id="sidebar" aria-label="Main Navigation">
            <div class="bg-header-dark">
               <div class="content-header bg-white-10">
                  <a class="font-w600 text-white tracking-wide" href="index.html">
                  <span class="smini-visible">
                  M&M <span class="opacity-75">Express</span>
                  </span>
                  <span class="smini-hidden">
                  M&M <span class="opacity-75">Express</span>
                  </span>
                  </a>
                  <div>
                     <a class="js-class-toggle text-white-75" data-target="#sidebar-style-toggler" data-class="fa-toggle-off fa-toggle-on" onclick="Dashmix.layout('sidebar_style_toggle');Dashmix.layout('header_style_toggle');" href="javascript:void(0)">
                     <i class="fa fa-toggle-off" id="sidebar-style-toggler"></i>
                     </a>
                     <a class="d-lg-none text-white ml-2" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
                     <i class="fa fa-times-circle"></i>
                     </a>
                  </div>
               </div>
            </div>
            <div class="js-sidebar-scroll">
               <div class="content-side">
                  <ul class="nav-main">
                     <li class="nav-main-item">
                        <a class="nav-main-link <?php echo ($page == "dashboard")? "active" : "";?>" href="<?php echo XC_URL;?>/admin/">
                        <i class="nav-main-link-icon fa fa-location-arrow"></i>
                        <span class="nav-main-link-name">Tổng quan</span>
                        </a>
                     </li>
					 
                     <li class="nav-main-heading">Tài khoản</li>
					 <?php if($this->helper->check_permission(3))
					 {
					?>
					 <li class="nav-main-item">
                        <a class="nav-main-link <?php echo ($page == "customer")? "active" : "";?> " href="<?php echo XC_URL;?>/admin/customers">
                        <i class="nav-main-link-icon fa fa-border-all"></i>
                        <span class="nav-main-link-name">Khách hàng</span>
                        </a>
                     </li>
					 <?php } ?>
					 <?php if($this->helper->check_permission(37))
					 {
					?>
					 <li class="nav-main-item">
                        <a class="nav-main-link <?php echo ($page == "agency")? "active" : "";?>" href="<?php echo XC_URL;?>/admin/agency">
                        <i class="nav-main-link-icon fa fa-border-all"></i>
                        <span class="nav-main-link-name">Đại lý</span>
                        </a>
                     </li>
					 <?php 
					 }
					 if($this->helper->check_permission(6))
					 {
					?>
					 <li class="nav-main-item">
                        <a class="nav-main-link <?php echo ($page == "user")? "active" : "";?>" href="<?php echo XC_URL;?>/admin/users">
                        <i class="nav-main-link-icon fa fa-border-all"></i>
                        <span class="nav-main-link-name">Nhân viên</span>
                        </a>
                     </li>
					 <li class="nav-main-item" >
                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon fa fa-boxes"></i>
                        <span class="nav-main-link-name">Phân quyền</span>
                        </a>
                        <ul class="nav-main-submenu">
                           <li class="nav-main-item">
                              <a class="nav-main-link" href="<?php echo XC_URL;?>/admin/departments">
                              <span class="nav-main-link-name">Phòng ban</span>
                              </a>
                           </li>
                           <li class="nav-main-item" style="display: none">
                              <a class="nav-main-link" href="<?php echo XC_URL;?>/admin/users">
                              <span class="nav-main-link-name">Phần quyền nhân viên</span>
                              </a>
                           </li>
                           
                        </ul>
                     </li>
                     <?php 
					 }
					 ?>
					 
					 
					 
                     
                     <?php if($this->helper->check_permission(1))
					 {
					?>
                     <li class="nav-main-heading">Đơn hàng</li>
					 <li class="nav-main-item">
                        <a class="nav-main-link <?php echo ($page == "orders")? "active" : "";?>" href="<?php echo XC_URL;?>/admin/orders">
                        <i class="nav-main-link-icon fa fa-border-all"></i>
                        <span class="nav-main-link-name">Danh sách đơn hàng</span>
                        </a>
                     </li>
					 <?php
					 }
					 ?>
					 <?php if($this->helper->check_permission(4))
					 {
					?>
					 <li class="nav-main-item">
                        <a class="nav-main-link " href="<?php echo XC_URL;?>/order/scan">
                        <i class="nav-main-link-icon fa fa-barcode"></i>
                        <span class="nav-main-link-name">Nhập kho TQ</span>
                        </a>
                     </li>
					 <li class="nav-main-item">
                        <a class="nav-main-link " href="<?php echo XC_URL;?>/order/packages">
                        <i class="nav-main-link-icon fa fa-cube"></i>
                        <span class="nav-main-link-name">Kiện hàng</span>
                        </a>
                     </li>
					 <?php
					 }
					 ?>
					 <?php if($this->helper->check_permission(5))
					 {
					?>
					 <li class="nav-main-heading">Tài chính</li>
					 <li class="nav-main-item">
                        <a class="nav-main-link <?php echo ($page == "transactions")? "active" : "";?>" href="<?php echo XC_URL;?>/admin/transactions">
                        <i class="nav-main-link-icon fa fa-border-all"></i>
                        <span class="nav-main-link-name">Giao dịch</span>
                        </a>
                     </li>
					 <li class="nav-main-item">
                        <a class="nav-main-link <?php echo ($page == "deposite")? "active" : "";?>" href="<?php echo XC_URL;?>/admin/deposites">
                        <i class="nav-main-link-icon fa fa-border-all"></i>
                        <span class="nav-main-link-name">Yêu cầu nạp tiền</span>
                        </a>
                     </li>
					 <li class="nav-main-item">
                        <a class="nav-main-link <?php echo ($page == "withdrawal")? "active" : "";?>" href="<?php echo XC_URL;?>/admin/withdrawal">
                        <i class="nav-main-link-icon fa fa-border-all"></i>
                        <span class="nav-main-link-name">Yêu cầu rút tiền</span>
                        </a>
                     </li>
					 <li class="nav-main-item">
                        <a class="nav-main-link " href="<?php echo XC_URL;?>/admin/servicefee">
                        <i class="nav-main-link-icon fa fa-border-all"></i>
                        <span class="nav-main-link-name">Phí dịch vụ</span>
                        </a>
                     </li>
					 <li class="nav-main-item">
                        <a class="nav-main-link " href="<?php echo XC_URL;?>/admin/servicefee">
                        <i class="nav-main-link-icon fa fa-border-all"></i>
                        <span class="nav-main-link-name">Phí vận chuyển</span>
                        </a>
                     </li>
					 <?php
					 }
					 ?>
					 <li class="nav-main-heading">Báo cáo</li>
					 <li class="nav-main-item">
                        <a class="nav-main-link " href="<?php echo XC_URL;?>/report">
                        <i class="nav-main-link-icon fa fa-chart-pie"></i>
                        <span class="nav-main-link-name">Báo cáo chung</span>
                        </a>
                     </li>
					 <?php if($_SESSION['staff']['group'] == 1)
					 {
						 ?>
					 <li class="nav-main-heading">Hệ thống</li>
					 <li class="nav-main-item">
                        <a class="nav-main-link " href="<?php echo XC_URL;?>/admin/config">
                        <i class="nav-main-link-icon fa fa-wrench"></i>
                        <span class="nav-main-link-name">Cấu hình hệ thống</span>
                        </a>
                     </li>
					 
					 <li class="nav-main-item">
                        <a class="nav-main-link " href="<?php echo XC_URL;?>/admin/config/system">
                        <i class="nav-main-link-icon fa fa-wrench"></i>
                        <span class="nav-main-link-name">Thiết lập frontend</span>
                        </a>
                     </li>
                     <?php } ?>
                  </ul>
               </div>
            </div>
         </nav>