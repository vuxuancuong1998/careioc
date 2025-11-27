<?php include "config.php"; ?>


<!doctype html>
<html lang="en">
  
<head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Administrator - CESTalk</title>
      
      <link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/css/backend-plugin.min.css">
      <link rel="stylesheet" href="<?php echo $template_path; ?>/backend/assets/css/backend.css?v=1.0.0">  </head>
  <body class=" ">
    <!-- loader Start -->
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>
    <!-- loader END -->
    
      <div class="wrapper">
      <section class="login-content">
         <div class="container h-100">
            <div class="row align-items-center justify-content-center h-100">
               <div class="col-md-5">
                  <div class="card">
                     <div class="card-body">
                        <div class="auth-logo">
                           <img src="https://cestalk.com/wp-content/uploads/2022/06/cestalk-logo.svg" class="img-fluid rounded-normal" alt="logo">
                        </div>
                        <h2 class="mb-2 text-center">Đăng nhập quản trị</h2>
                        <p class="text-center"></p>
                        <form id="admin-login-form" class="needs-validation" novalidate action="">
                           <div class="row">
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <label>Email/SĐT</label>
                                    <input class="form-control" id="login-username" required name="login-username" type="text" placeholder="Email hoặc SĐT">
									<div class="valid-feedback"></div>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <label>Mật khẩu</label>
                                    <input class="form-control" id="login-password" required name="login-password" type="password" placeholder="Mật khẩu">
									<div class="valid-feedback"></div>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                    <label class="custom-control-label" for="customCheck1">Lưu đăng nhập</label>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <a href="auth-recoverpw.html" class="text-primary float-right">Quên mật khẩu?</a>
                              </div>
                           </div>
                           <div class="d-flex justify-content-between align-items-center">
                              <span>Chưa có tài khoản <a href="auth-sign-up.html" class="text-primary">Đăng ký</a></span>
                              <button type="submit" class="btn btn-primary">ĐĂNG NHẬP</button>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      </div>
    
    <!-- Backend Bundle JavaScript -->
    <script src="<?php echo $template_path; ?>/backend/assets/js/backend-bundle.min.js"></script>
    <!-- Chart Custom JavaScript -->
    <script src="<?php echo $template_path; ?>/backend/assets/js/customizer.js"></script>
    
    <script src="<?php echo $template_path; ?>/backend/assets/js/sidebar.js"></script>
    
    <!-- Flextree Javascript-->
    <script src="<?php echo $template_path; ?>/backend/assets/js/flex-tree.min.js"></script>
    <script src="<?php echo $template_path; ?>/backend/assets/js/tree.js"></script>
    
    <!-- Table Treeview JavaScript -->
    <script src="<?php echo $template_path; ?>/backend/assets/js/table-treeview.js"></script>
    
    <!-- SweetAlert JavaScript -->
    <script src="<?php echo $template_path; ?>/backend/assets/js/sweetalert.js"></script>
    
    <!-- Vectoe Map JavaScript -->
    <script src="<?php echo $template_path; ?>/backend/assets/js/vector-map-custom.js"></script>
    
    <!-- Chart Custom JavaScript -->
    <script src="<?php echo $template_path; ?>/backend/assets/js/chart-custom.js"></script>
    <script src="<?php echo $template_path; ?>/backend/assets/js/charts/01.js"></script>
    <script src="<?php echo $template_path; ?>/backend/assets/js/charts/02.js"></script>
    
    <!-- slider JavaScript -->
    <script src="<?php echo $template_path; ?>/backend/assets/js/slider.js"></script>
    
    
    
    <!-- app JavaScript -->
    <script src="<?php echo $template_path; ?>/backend/assets/js/app.js"></script>  
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.4/jquery.validate.min.js" integrity="sha512-FOhq9HThdn7ltbK8abmGn60A/EMtEzIzv1rvuh+DqzJtSGq8BRdEN0U+j0iKEIffiw/yEtVuladk6rsG4X6Uqg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
		$(document).ready(function() {
			jQuery("#admin-login-form").validate({
				rules:
				{
					"login-username": {required:!0,minlength:3},
					"login-password":{required:!0,minlength:5}},
				messages:{
					"login-username":{
						required:"Vui lòng nhập email hoặc số điện thoại",
						minlength:"Your username must consist of at least 3 characters"
					},
					"login-password":{
						required:"Vui lòng nhập mật khẩu",
						minlength:"Your password must be at least 5 characters long"
					}
				}
			});
			$("#admin-login-form").on("submit", function()
			{
				if($("#admin-login-form").valid()){
					var username = $("#login-username").val();
					var password = $("#login-password").val();
					$.ajax({
						type: "POST",
						url: "<?php echo XC_URL;?>/api/stafflogin",
						data: {username: username,password: password},
						dataType: "json",
						cache: false,
						success: function(data)
						{
							if(data.status != "200")
							{
								Swal.fire({
								  icon: 'error',
								  title: 'Oops...',
								  text: 'Tài khoản hoặc mật khẩu không đúng, vui lòng kiểm tra lại...',
								  footer: '<a href>Xem thêm về lỗi này?</a>'
								})
							}
							else
							{
								window.location= ("<?php echo XC_URL;?>/admin");                
							}
						}
					});
				}
					return false;
				});
					
		});
		</script>
	</body>

</html>