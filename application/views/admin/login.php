<!DOCTYPE html>
<html lang="en">
	<head>
		<base href="../../../">
		<meta charset="utf-8" />
		<title>Login Page</title>
		<meta name="description" content="Login page example">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
		<link href="<?php echo $this->config->base_url();?>assets/css/pages/login/login-3.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!-- Error meassage icon-->
		<link href="assets/plugins/general/morris.js/morris.css" rel="stylesheet" type="text/css">
		<link href="assets/plugins/general/sweetalert2/dist/sweetalert2.css" rel="stylesheet" type="text/css">
		<link href="assets/plugins/general/socicon/css/socicon.css" rel="stylesheet" type="text/css">
		<link href="assets/plugins/general/plugins/line-awesome/css/line-awesome.css" rel="stylesheet" type="text/css">
		<link href="assets/plugins/general/plugins/flaticon/flaticon.css" rel="stylesheet" type="text/css">
		<link href="assets/plugins/general/plugins/flaticon2/flaticon.css" rel="stylesheet" type="text/css">
		<link href="assets/plugins/general/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
		<!-- Error meassage icon -->
		<script src="<?php echo $this->config->base_url();?>assets/js/jquery.min.js" type="text/javascript"></script>
		<script src="<?php echo $this->config->base_url();?>assets/js/jquery.validate.min.js"></script>
		<script src="<?php echo $this->config->base_url();?>assets/js/additional-methods.min.js"></script>
		<link rel="shortcut icon" href="<?php echo $this->config->base_url();?>assets/logo/favicon-32x32.png" />
		<style type="text/css">
			.logo_loginpage{
				width: 25%;
			}
			#email-error{
				width: 100%;
			}
			#password-error{
				width: 100%;
			}
			.error{
				color: red;
			}
		</style>
	</head>
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-page--loading">

		<!-- begin:: Page -->
		<div class="kt-grid kt-grid--ver kt-grid--root kt-page">
			<div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v3 kt-login--signin" id="kt_login">
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url(assets/media//bg/bg-3.jpg);">
					<div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
						<div class="kt-login__container">
							<div class="kt-login__logo">
								<a  href="<?php echo $this->config->base_url();?>admin">
									<img  class = "logo_loginpage" src="<?php echo $this->config->base_url();?>assets/logo/mmao-logo.png">
								</a>
							</div>
							<div class="kt-login__signin">
								<div class="kt-login__head">
									<h3 class="kt-login__title">Sign In To Admin</h3>
								</div>
								<?php  if(!empty($errmsg)) { ?>
								<div class="alert alert-solid-danger alert-bold" role="alert">
		                            <div class="alert-text"><?php echo $errmsg; ?></div>
									<div class="alert-close">
		                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		                                    <span aria-hidden="true"><i class="la la-close"></i></span>
		                                </button>
		                            </div>
		                        </div>
		                    	<?php } ?>
								<form class="kt-form" name = "loginForm" action="<?php echo $this->config->base_url()?>admin/login" method="post">
									<div class="input-group">
										<input class="form-control" type="text" placeholder="Username" name="email" autocomplete="off">
									</div>
									<div class="input-group">
										<input class="form-control" type="password" placeholder="Password" name="password">
									</div>
									
									<div class="kt-login__actions">
										<input id="kt_login_signin_submit" name = "commit" type = "submit" class="btn btn-brand btn-elevate kt-login__btn-primary" value="Sign In">
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			$("input[name='commit']").click(function(e) {
				$("form[name='loginForm']").validate({
			   
					rules: {
					 
						email: "required",
						password: "required",
						
					},
					submitHandler: function(form) {
						form.submit();
					}
				});
			});
			$(".close").click(function(){
			  $(".alert-bold").hide();
			});
		</script>
		<!-- end:: Page -->

		<!-- begin::Global Config(global config for global JS sciprts) -->
		<script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#2c77f4",
						"light": "#ffffff",
						"dark": "#282a3c",
						"primary": "#5867dd",
						"success": "#34bfa3",
						"info": "#36a3f7",
						"warning": "#ffb822",
						"danger": "#fd3995"
					},
					"base": {
						"label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
						"shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
					}
				}
			};
		</script>

		<!-- end::Global Config -->

		<!--begin::Global Theme Bundle(used by all pages) -->
		<script src="<?php echo $this->config->base_url();?>assets/plugins/global/plugins.bundle.js" type="text/javascript"></script>
		<script src="<?php echo $this->config->base_url();?>assets/js/scripts.bundle.js" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Scripts(used by this page) -->
		<script src="<?php echo $this->config->base_url();?>assets/js/pages/custom/login/login-general.js" type="text/javascript"></script>

		<!--end::Page Scripts -->
	</body>

	<!-- end::Body -->
</html>