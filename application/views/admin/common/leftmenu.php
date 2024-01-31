<!DOCTYPE html>

<html lang="en">
	<head>
		<base href="">
		<meta charset="utf-8" />
		<title><?php echo @$title ?> | Mac Me An Offer</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/tether/dist/css/tether.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/css/plugins.bundle.css" rel="stylesheet" type="text/css" />
		
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css" />
		<!--
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css" rel="stylesheet" type="text/css" />
		-->
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/select2/dist/css/select2.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/nouislider/distribute/nouislider.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/owl.carousel/dist/assets/owl.carousel.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/owl.carousel/dist/assets/owl.theme.default.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/dropzone/dist/dropzone.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/quill/dist/quill.snow.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/summernote/dist/summernote.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/bootstrap-markdown/css/bootstrap-markdown.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/animate.css/animate.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/dual-listbox/dist/dual-listbox.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/morris.js/morris.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/sweetalert2/dist/sweetalert2.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/socicon/css/socicon.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/plugins/line-awesome/css/line-awesome.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/plugins/flaticon/flaticon.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/plugins/flaticon2/flaticon.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/general/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />

		<!--end:: Vendor Plugins -->
		<link href="<?php echo $this->config->base_url();?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />

		<!--begin:: Vendor Plugins for custom pages -->
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/@fullcalendar/core/main.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/@fullcalendar/daygrid/main.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/@fullcalendar/list/main.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/@fullcalendar/timegrid/main.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/datatables.net-autofill-bs4/css/autoFill.bootstrap4.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/datatables.net-colreorder-bs4/css/colReorder.bootstrap4.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/datatables.net-fixedcolumns-bs4/css/fixedColumns.bootstrap4.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/datatables.net-keytable-bs4/css/keyTable.bootstrap4.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/datatables.net-rowgroup-bs4/css/rowGroup.bootstrap4.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/datatables.net-rowreorder-bs4/css/rowReorder.bootstrap4.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/datatables.net-scroller-bs4/css/scroller.bootstrap4.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

		<link href="<?php echo $this->config->base_url();?>assets/css/style.css" rel="stylesheet" type="text/css" />
		<!--
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/jstree/dist/themes/default/style.css" rel="stylesheet" type="text/css" />
		-->
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/jqvmap/dist/jqvmap.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->config->base_url();?>assets/plugins/custom/uppy/dist/uppy.min.css" rel="stylesheet" type="text/css" />
		<script src="<?php echo $this->config->base_url();?>assets/js/jquery.min.js" type="text/javascript"></script>
		<script src="<?php echo $this->config->base_url();?>assets/js/jquery.validate.min.js"></script>
		<script src="<?php echo $this->config->base_url();?>assets/js/additional-methods.min.js"></script>
		<script src="<?php echo $this->config->base_url();?>assets/js/ajaxupload.3.5.js"></script>
		<script type="text/javascript">
			var base_url = '<?php echo $this->config->base_url();?>';
		</script>
		<!--end:: Vendor Plugins for custom pages -->

		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->

		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="<?php echo $this->config->base_url();?>assets/logo/favicon-32x32.png" />
		<style type="text/css">
			.header_logo{
				max-width: 27%;
			}
		</style>
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-page--loading">

		<!-- begin:: Page -->

		<!-- begin:: Header Mobile -->
		<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
			<div class="kt-header-mobile__logo">
				<a href="index&demo=demo12.html">
					<!--<img alt="Logo" src="assets/media/logos/logo-12.png" />-->
				</a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__toolbar-toggler kt-header-mobile__toolbar-toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
			</div>
		</div>

		<!-- end:: Header Mobile -->
		<div class="kt-grid kt-grid--hor kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

				<!-- begin:: Aside -->
				<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
				<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">

					<!-- begin:: Aside -->
					<div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
						<!--  style="background-color: white;text-align: center;margin-right: 20px;" -->
						<div class="kt-aside__brand-logo">
							<a href="<?php echo $this->config->base_url();?>admin">
								<img class = "header_logo" alt="Logo" src="<?php echo $this->config->base_url();?>assets/logo/mmao-logo.png">
							</a>
						</div>
						<div class="kt-aside__brand-tools">
							<button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler"><span></span></button>
						</div>
					</div>

					<!-- end:: Aside -->

					<!-- begin:: Aside Menu -->
					<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
						<div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
							<ul class="kt-menu__nav ">
								<li class="kt-menu__item <?php if($this->router->class =='dashboard')echo 'kt-menu__item--active';?>" aria-haspopup="true">
									<a href="<?php echo base_url('admin/dashboard'); ?>" class="kt-menu__link ">
										<i class="kt-menu__link-icon flaticon2-architecture-and-city"></i>
										<span class="kt-menu__link-text">Dashboard</span>
									</a>
								</li>
								
								<li class="kt-menu__item <?php if($this->router->class =='category')echo 'kt-menu__item--active';?> " aria-haspopup="true">
									<a href="<?php echo base_url('admin/category'); ?>" class="kt-menu__link ">
										<i class="kt-menu__link-icon flaticon2-list-3"></i>
										<span class="kt-menu__link-text">Category</span>
									</a>
								</li>
								<li class="kt-menu__item <?php if($this->router->class =='series')echo 'kt-menu__item--active';?> " aria-haspopup="true">
									<a href="<?php echo base_url('admin/series'); ?>" class="kt-menu__link ">
										<i class="kt-menu__link-icon flaticon-layer"></i>
										<span class="kt-menu__link-text">Series</span>
									</a>
								</li>
								<li class="kt-menu__item <?php if($this->router->class =='quote')echo 'kt-menu__item--active';?> " aria-haspopup="true">
									<a href="<?php echo base_url('admin/quote'); ?>" class="kt-menu__link ">
										<i class="kt-menu__link-icon flaticon2-cube"></i>
										<span class="kt-menu__link-text">Quotes</span>
									</a>
								</li>
								<li class="kt-menu__item <?php if($this->router->class =='storecredit')echo 'kt-menu__item--active';?> " aria-haspopup="true">
									<a href="<?php echo base_url('admin/storecredit'); ?>" class="kt-menu__link ">
										<i class="kt-menu__link-icon flaticon2-contract"></i>
										<span class="kt-menu__link-text">Store Credit</span>
									</a>
								</li>
								<li class="kt-menu__item  kt-menu__item--submenu <?php if($this->router->class =='import' 
								|| $this->router->class =='serial' )echo 'kt-menu__item--open kt-menu__item--here';?> " aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
									<a href="javascript:;" class="kt-menu__link kt-menu__toggle">
										<i class="kt-menu__link-icon flaticon2-laptop"></i>
										<span class="kt-menu__link-text">Serial</span>
										<i class="kt-menu__ver-arrow la la-angle-right"></i>
									</a>
								    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
								        <ul class="kt-menu__subnav">
								            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Pages</span></span>
								            </li>
								            <li class="kt-menu__item <?php if($this->router->class =='serial')echo 'kt-menu__item--active';?>" aria-haspopup="true"><a href="<?php echo base_url('admin/serial'); ?>" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Serial </span></a></li>
								            <li class="kt-menu__item  <?php if($this->router->class =='import')echo 'kt-menu__item--active';?>" aria-haspopup="true"><a href="<?php echo base_url('admin/import'); ?>" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Import/Export </span></a></li>
								           
								        </ul>
								    </div>
								</li>
								<?php 
								if($this->session->userdata('admin_session') != ''){
									$session_data = @$this->session->userdata('admin_session');
									if($session_data['role'] !='user'){ 
								?>
									<li class="kt-menu__item <?php if($this->router->class =='settings')echo 'kt-menu__item--active';?> " aria-haspopup="true">
										<a href="<?php echo base_url('admin/settings'); ?>" class="kt-menu__link ">
											<i class="kt-menu__link-icon flaticon2-console"></i>
											<span class="kt-menu__link-text">Settings</span>
										</a>
									</li>
								<?php } } ?>
								
								<!--
								<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon2-telegram-logo"></i><span class="kt-menu__link-text">Applications</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
										<ul class="kt-menu__subnav">
											<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Applications</span></span></li>
											<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Users</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
												<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
													<ul class="kt-menu__subnav">
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/user/list-default&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">List - Default</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/user/list-datatable&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">List - Datatable</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/user/list-columns-1&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">List - Columns 1</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/user/list-columns-2&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">List - Columns 2</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/user/add-user&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Add User</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/user/edit-user&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Edit User</span></a></li>
														<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Profile 1</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
															<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
																<ul class="kt-menu__subnav">
																	<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/user/profile-1/overview&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Overview</span></a></li>
																	<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/user/profile-1/personal-information&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Personal Information</span></a></li>
																	<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/user/profile-1/account-information&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Account Information</span></a></li>
																	<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/user/profile-1/change-password&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Change Password</span></a></li>
																	<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/user/profile-1/email-settings&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Email Settings</span></a></li>
																</ul>
															</div>
														</li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/user/profile-2&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Profile 2</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/user/profile-3&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Profile 3</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/user/profile-4&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Profile 4</span></a></li>
													</ul>
												</div>
											</li>
											<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Contacts</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
												<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
													<ul class="kt-menu__subnav">
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/contacts/list-columns&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">List - Columns</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/contacts/list-datatable&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">List - Datatable</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/contacts/view-contact&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">View Contact</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/contacts/add-contact&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Add Contact</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/contacts/edit-contact&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Edit Contact</span></a></li>
													</ul>
												</div>
											</li>
											<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Chat</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
												<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
													<ul class="kt-menu__subnav">
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/chat/private&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Private</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/chat/group&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Group</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/chat/popup&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Popup</span></a></li>
													</ul>
												</div>
											</li>
											<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Projects</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
												<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
													<ul class="kt-menu__subnav">
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/projects/list-columns-1&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">List - Columns 1</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/projects/list-columns-2&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">List - Columns 2</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/projects/list-columns-3&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">List - Columns 3</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/projects/list-columns-4&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">List - Columns 4</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/projects/list-datatable&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">List - Datatable</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/projects/view-project&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">View Project</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/projects/add-project&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Add Project</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/projects/edit-project&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Edit Project</span></a></li>
													</ul>
												</div>
											</li>
											<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Support Center</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
												<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
													<ul class="kt-menu__subnav">
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/support-center/home-1&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Home 1</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/support-center/home-2&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Home 2</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/support-center/faq-1&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">FAQ 1</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/support-center/faq-2&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">FAQ 2</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/support-center/faq-3&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">FAQ 3</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/support-center/feedback&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Feedback</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/support-center/license&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">License</span></a></li>
													</ul>
												</div>
											</li>
											<li class="kt-menu__item " aria-haspopup="true"><a href="custom/apps/inbox&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Inbox</span><span class="kt-menu__link-badge"><span class="kt-badge kt-badge--danger kt-badge--inline">new</span></span></a></li>
										</ul>
									</div>
								</li>-->

								<!--<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon2-laptop"></i><span class="kt-menu__link-text">Pages</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
									<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
										<ul class="kt-menu__subnav">
											<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Pages</span></span></li>
											<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Wizard</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
												<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
													<ul class="kt-menu__subnav">
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/wizard/wizard-1&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Wizard 1</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/wizard/wizard-2&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Wizard 2</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/wizard/wizard-3&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Wizard 3</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/wizard/wizard-4&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Wizard 4</span></a></li>
													</ul>
												</div>
											</li>
											<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Pricing Tables</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
												<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
													<ul class="kt-menu__subnav">
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/pricing/pricing-1&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Pricing Tables 1</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/pricing/pricing-2&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Pricing Tables 2</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/pricing/pricing-3&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Pricing Tables 3</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/pricing/pricing-4&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Pricing Tables 4</span></a></li>
													</ul>
												</div>
											</li>
											<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Invoices</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
												<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
													<ul class="kt-menu__subnav">
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/invoices/invoice-1&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Invoice 1</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/invoices/invoice-2&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Invoice 2</span></a></li>
													</ul>
												</div>
											</li>
											<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">FAQ</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
												<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
													<ul class="kt-menu__subnav">
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/faq/faq-1&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">FAQ 1</span></a></li>
													</ul>
												</div>
											</li>
											<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">User Pages</span><span class="kt-menu__link-badge"><span class="kt-badge kt-badge--rounded kt-badge--brand">2</span></span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
												<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
													<ul class="kt-menu__subnav">
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/user/login-1&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Login 1</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/user/login-2&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Login 2</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/user/login-3&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Login 3</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/user/login-4&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Login 4</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/user/login-5&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Login 5</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/user/login-6&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Login 6</span></a></li>
													</ul>
												</div>
											</li>
											<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Error Pages</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
												<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
													<ul class="kt-menu__subnav">
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/error/error-1&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Error 1</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/error/error-2&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Error 2</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/error/error-3&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Error 3</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/error/error-4&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Error 4</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/error/error-5&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Error 5</span></a></li>
														<li class="kt-menu__item " aria-haspopup="true"><a href="custom/pages/error/error-6&demo=demo12.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Error 6</span></a></li>
													</ul>
												</div>
											</li>

										</ul>
									</div>
								</li>-->
							</ul>
						</div>
					</div>

					<!-- end:: Aside Menu -->
				</div>