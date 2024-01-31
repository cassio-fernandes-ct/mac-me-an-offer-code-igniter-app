<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
	<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">
		<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
		<div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
			
		</div>
		<div class="kt-header__topbar">
			<div class="kt-header__topbar-item kt-header__topbar-item--user">
				<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
					<div class="kt-header__topbar-user">
						<span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
						<?php $session_data = @$this->session->userdata('admin_session'); ?>
						<span class="kt-header__topbar-username kt-hidden-mobile"><?php echo $session_data['username']; ?></span>
						<img alt="Pic" class="kt-radius-100" src="<?php echo $this->config->base_url();?>assets/logo/images.jpeg" />
					</div>
				</div>
				<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">

					<!--begin: Head -->
					<div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url(<?php echo $this->config->base_url();?>assets/media/misc/bg-1.jpg)">
						<div class="kt-user-card__avatar">
							<span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success"><?php echo strtoupper($session_data['username'][0]); ?></span>
						</div>
						<div class="kt-user-card__name">
							<?php echo $session_data['username']; ?>
						</div>
						
					</div>
					<div class="kt-notification">
						<div class="kt-notification__custom kt-space-between">
							<a href="<?php echo base_url('admin/login/logout'); ?>" class="btn btn-label btn-label-brand btn-sm btn-bold">Sign Out</a>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
