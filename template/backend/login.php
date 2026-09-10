<!doctype html>
<html lang="en">
   
<head>
        <meta charset="utf-8">  
      <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
      <title>MMExpress - HiCarrier Platform</title>
      <meta name="description" content="HiReal - Real Easte Portal Platform">
      <meta name="author" content="VCFMedia">
      <meta name="robots" content="noindex, nofollow">
      <meta property="og:title" content="HiReal - Real Easte Portal Platform">
      <meta property="og:site_name" content="HiReal - Real Easte Portal Platform">
      <meta property="og:description" content="HiReal - Real Easte Portal Platform">
      <meta property="og:type" content="website">
      <meta property="og:url" content="">
      <meta property="og:image" content="">
      <link rel="shortcut icon" href="<?php echo $template_path;?>/assets/media/favicons/favicon.png">
      <link rel="icon" type="image/png" sizes="192x192" href="<?php echo $template_path;?>/assets/media/favicons/favicon-192x192.png">
      <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $template_path;?>/assets/media/favicons/apple-touch-icon-180x180.png">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap">
    <link rel="stylesheet" id="css-main" href="<?php echo $template_path;?>/assets/css/dashmix.min-3.1.css">
    </head>
<body>
<div id="page-container">
                <main id="main-container">
<div class="bg-image" style="background-image: url('<?php echo $template_path;?>/assets/media/photos/photo22%402x.jpg');">
    <div class="row no-gutters bg-primary-op">
        <div class="hero-static col-md-6 d-flex align-items-center bg-white">
            <div class="p-3 w-100">
                <div class="mb-3 text-center">
                    <a class="link-fx font-w700 font-size-h1" href="index.html">
                        <span class="text-dark">M&M Express</span><span class="text-primary">Staff</span>
                    </a>
                    <p class="text-uppercase font-w700 font-size-sm text-muted">Đăng nhập</p>
                </div>
                <div class="row no-gutters justify-content-center">
                    <div class="col-sm-8 col-xl-6">
                        <form id="admin-login-form" class="js-validation-signin" action="#" method="POST">
                            <div class="py-3">
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-lg form-control-alt" id="login-username" name="login-username" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg form-control-alt" id="login-password" name="login-password" placeholder="Mật khẩu">
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-hero-lg btn-hero-primary">
                                    <i class="fa fa-fw fa-sign-in-alt mr-1"></i> Đăng nhập
                                </button>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-static col-md-6 d-none d-md-flex align-items-md-center justify-content-md-center text-md-center">
            <div class="p-3">
                <p class="display-4 font-w700 text-white mb-3">
                    HiCarrier 3.2.6
                </p>
                <p class="font-size-lg font-w600 text-white-75 mb-0">
                    Copyright &copy; <span data-toggle="year-copy"></span> by M&M Express
                </p>
            </div>
        </div>
    </div>
</div>
    </main>
    </div>
<script src="<?php echo $template_path;?>/assets/js/dashmix.core.min-3.1.js"></script>
<script src="<?php echo $template_path;?>/assets/js/dashmix.app.min-3.1.js"></script>
<script src="<?php echo $template_path;?>/assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="<?php echo $template_path;?>/assets/js/pages/op_auth_signin.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
		$(document).ready(function() {
			$("#admin-login-form").on("submit", function()
			{
				if($("#admin-login-form").valid()){
					var user = $("#login-username").val();
					var pass = $("#login-password").val();
					$.ajax({
						type: "POST",
						url: "<?php echo XC_URL;?>/api/stafflogin",
						data: {username: user,password: pass},
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
