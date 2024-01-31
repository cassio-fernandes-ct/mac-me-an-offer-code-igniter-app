<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

    <!-- begin:: Subheader -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">Customer synchronization Process</h3>

                <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                   <!-- <a href="" class="kt-subheader__breadcrumbs-link">
                            Builder                        </a>-->
                    <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
            </div>
            <div class="kt-subheader__toolbar">
               
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->

    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        
        <div class="kt-portlet kt-portlet--tabs">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-toolbar">
                    <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-left nav-tabs-line-primary" role="tablist">
                        <!--
				<li class="nav-item">
					<a class="nav-link " data-toggle="tab" href="#kt_builder_skins" role="tab">
						Skins
					</a>
				</li>
				-->
                        <li class="nav-item">
                            <a class="nav-link active " data-toggle="tab" href="#kt_builder_page" role="tab">
						Total
					</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " data-toggle="tab" href="#kt_builder_header" role="tab">
						Mac me an offer remaining sync 
					</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " data-toggle="tab" href="#kt_builder_topbar" role="tab">
						Mac of all trades
					</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!--begin::Form-->
            <form class="kt-form kt-form--label-right" action="" method="POST">
                <div class="kt-portlet__body">

                    
                    <?php $update   = $this->session->userdata('mmtupdate'); 
                    if(isset($update) && !empty($update)){ ?>
                    <div class="alert alert-solid-success alert-bold" role="alert">
                        <div class="alert-text">Mac of all trades All customer synchronization processes completed.</div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="la la-close"></i></span>
                            </button>
                        </div>
                    </div>
                    <?php } ?>
                    <?php $this->session->unset_userdata('mmtupdate');  ?>
                     <?php $updatee   = $this->session->userdata('mmoupdate'); 
                    if(isset($updatee) && !empty($updatee)){ ?>
                    <div class="alert alert-solid-success alert-bold" role="alert">
                        <div class="alert-text">Mac me an offer All customer synchronization processes completed.</div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="la la-close"></i></span>
                            </button>
                        </div>
                    </div>
                    <?php  ?>
                    <?php $this->session->unset_userdata('mmoupdate'); } ?>
                    <div class="tab-content">
                       
                        <div class="tab-pane active" id="kt_builder_page">
                            <div class="kt-section kt-margin-t-30">
                                <div class="kt-section__body">
                                  	<div class="form-group row">
					                    <label class="col-lg-5 col-form-label">Big commerce-Mac me an offer total customer: </label>
					                    <div class="col-lg-4">
					                         <label class="col-form-label"><?php echo @$totalcustomermmo; ?></label>
					                        <span class="form-text text-muted"></span>
					                    </div>
				                	</div>
				                	<div class="form-group row">
					                    <label class="col-lg-5 col-form-label">Big commerce-Mac of all trades total customer: </label>
					                    <div class="col-lg-4">
					                         <label class="col-form-label"><?php echo @$totalcustomermmt; ?></label>
					                        <span class="form-text text-muted"></span>
					                    </div>
				                	</div>
                                 
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane " id="kt_builder_header">
                            <div class="kt-section kt-margin-t-30">
                                <div class="kt-section__body">
                                    
                                    <div class="kt-portlet__body">
                                        <div class="kt-form kt-form--label-right">
                                            <div class="row align-items-center">
                                                <div class="col-xl-8 order-2 order-xl-1">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                                            <label class="col-form-label"><b> No sync customer Mac me an offer: <?php echo @$noSyncMmoIdGet; ?></b></label>
                                                        </div>
                                                        <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                                            
                                                        </div>
                                                        <?php if($noSyncMmoIdGet != 0){  ?>
                                                        <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                                            <div style = "float: right;"  class="kt-form__group kt-form__group--inline">
                                                                <a href="<?php echo base_url();?>admin/Customerupdate/noSyncMmoIdGet" target="blank" class="btn btn-brand"> <i class="flaticon2-plus"></i>customer Sync</a>
                                                                <span class="form-text text-muted"></span>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <?php if($noSyncMmoIdGet != 0){  ?>
                                    <div class="quote_data_ajax kt-datatable kt-datatable--default kt-datatable--brand kt-datatable--loaded" id="ajax_data" style="">
                                        <table class="kt-datatable__table" style="display: block;">
                                            <thead class="kt-datatable__head">
                                                <tr class="kt-datatable__row" style="left: 0px;">
                                                    <th data-field="id" class="kt-datatable__cell--center kt-datatable__cell">
                                                        <span style="width: 30px;">#</span>
                                                    </th>
                                                    <th data-field="productname" class="kt-datatable__cell">
                                                        <span style="width: 260px;">Email</span>
                                                    </th>
                                                    <th data-field="email" class="kt-datatable__cell">
                                                        <span style="width: 157px;">Firstname</span>
                                                    </th>
                                                    <th data-field="price" class="kt-datatable__cell">
                                                        <span style="width: 157px;">Lastname</span>
                                                    </th>
                                                    <th data-field="status" class="kt-datatable__cell">
                                                        <span style="width: 157px;">Mmo Sync</span>
                                                    </th>
                                                    <th data-field="action" class="kt-datatable__cell">
                                                        <span style="width: 157px;">Mmt Sync</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="kt-datatable__body" style="">
                                                <?php if(isset($noSyncMmoIdGetD) && !empty($noSyncMmoIdGetD)){ 
                                                    $i = 1;
                                                foreach($noSyncMmoIdGetD as $mmodetils): ?>
                                                <tr data-row="0" class="kt-datatable__row" style="left: 0px;">
                                                    <td class="kt-datatable__cell--sorted kt-datatable__cell--center kt-datatable__cell" data-field="id">
                                                        <span style="width: 30px;"></span><?php echo $i; ?></span>
                                                    </td>
                                                    <td data-field="productname" class="kt-datatable__cell">
                                                        <span style="width: 260px;"><?php echo $mmodetils['email']; ?></span>
                                                    </td>
                                                    <td data-field="email" class="kt-datatable__cell">
                                                        <span style="width: 157px;"><?php echo $mmodetils['firstname']; ?></span>
                                                    </td>
                                                    <td data-field="price" class="kt-datatable__cell">
                                                        <span style="width: 157px;"><?php echo $mmodetils['lastname']; ?></span>
                                                    </td>
                                                    <td data-field="status" class="kt-datatable__cell">
                                                        <span style="width: 157px;"><?php if(isset($mmodetils['bc_id_mmo']) && !empty($mmodetils['bc_id_mmo']) && $mmodetils['bc_id_mmo'] != '') { echo 'yes'; }else {echo '-';} ?></span>
                                                    </td>
                                                    <td data-field="action" class="kt-datatable__cell">
                                                        <span style="width: 157px;"><?php if(isset($mmodetils['bc_id_mmt']) && !empty($mmodetils['bc_id_mmt']) && $mmodetils['bc_id_mmt'] != '') { echo 'yes'; }else {echo '-';} ?></span>
                                                    </td>
                                                </tr>
                                                <?php endforeach; 
                                                } ?>
                                            </tbody>
                                        </table>   
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane " id="kt_builder_topbar">
                            <div class="kt-section kt-margin-t-30">
                                <div class="kt-section__body">
                                     <div class="kt-portlet__body">
                                        <div class="kt-form kt-form--label-right">
                                            <div class="row align-items-center">
                                                <div class="col-xl-8 order-2 order-xl-1">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                                            <label class="col-form-label"><b> No sync customer Mac of all trades: <?php echo @$noSyncMmtIdGet; ?></b></label>
                                                        </div>
                                                        <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                                            
                                                        </div>
                                                        <?php if($noSyncMmtIdGet != 0){  ?>
                                                        <div  class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                                            <div style = "float: right;" class="kt-form__group kt-form__group--inline">
                                                                <a href="<?php echo base_url();?>admin/Customerupdate/noSyncMmtIdGet" target="blank" class="btn btn-brand"> <i class="flaticon2-plus"></i>customer Sync</a>
                                                                <span class="form-text text-muted"></span>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <?php if($noSyncMmtIdGet != 0){  ?>
                                    <div class="quote_data_ajax kt-datatable kt-datatable--default kt-datatable--brand kt-datatable--loaded" id="ajax_data" style="">
                                        <table class="kt-datatable__table" style="display: block;">
                                            <thead class="kt-datatable__head">
                                                <tr class="kt-datatable__row" style="left: 0px;">
                                                    <th data-field="id" class="kt-datatable__cell--center kt-datatable__cell">
                                                        <span style="width: 30px;">#</span>
                                                    </th>
                                                    <th data-field="productname" class="kt-datatable__cell">
                                                        <span style="width: 260px;">Email</span>
                                                    </th>
                                                    <th data-field="email" class="kt-datatable__cell">
                                                        <span style="width: 157px;">Firstname</span>
                                                    </th>
                                                    <th data-field="price" class="kt-datatable__cell">
                                                        <span style="width: 157px;">Lastname</span>
                                                    </th>
                                                    <th data-field="status" class="kt-datatable__cell">
                                                        <span style="width: 157px;">Mmo Sync</span>
                                                    </th>
                                                    <th data-field="action" class="kt-datatable__cell">
                                                        <span style="width: 157px;">Mmt Sync</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="kt-datatable__body" style="">
                                                <?php if(isset($noSyncMmtIdGetD) && !empty($noSyncMmtIdGetD)){ 
                                                    $i = 1;
                                                foreach($noSyncMmtIdGetD as $mmodetils): ?>
                                                <tr data-row="0" class="kt-datatable__row" style="left: 0px;">
                                                    <td class="kt-datatable__cell--sorted kt-datatable__cell--center kt-datatable__cell" data-field="id">
                                                        <span style="width: 30px;"></span><?php echo $i; ?></span>
                                                    </td>
                                                    <td data-field="productname" class="kt-datatable__cell">
                                                        <span style="width: 260px;"><?php echo $mmodetils['email']; ?></span>
                                                    </td>
                                                    <td data-field="email" class="kt-datatable__cell">
                                                        <span style="width: 157px;"><?php echo $mmodetils['firstname']; ?></span>
                                                    </td>
                                                    <td data-field="price" class="kt-datatable__cell">
                                                        <span style="width: 157px;"><?php echo $mmodetils['lastname']; ?></span>
                                                    </td>
                                                    <td data-field="status" class="kt-datatable__cell">
                                                        <span style="width: 157px;"><?php if(isset($mmodetils['bc_id_mmo']) && !empty($mmodetils['bc_id_mmo']) && $mmodetils['bc_id_mmo'] != '') { echo 'yes'; }else {echo '-';} ?></span>
                                                    </td>
                                                    <td data-field="action" class="kt-datatable__cell">
                                                        <span style="width: 157px;"><?php if(isset($mmodetils['bc_id_mmt']) && !empty($mmodetils['bc_id_mmt']) && $mmodetils['bc_id_mmt'] != '') { echo 'yes'; }else {echo '-';} ?></span>
                                                    </td>
                                                </tr>
                                                <?php endforeach; 
                                                } ?>
                                            </tbody>
                                        </table>   
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                       
                    </div>
                </div>
            </form>
            <!--end::Form-->
        </div>
        <!--end::Portlet-->

        <!--begin::Modal-->
       <!-- <div class="modal fade kt-modal-purchase" id="kt-modal-purchase" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLabel">reCaptcha Verification</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div id="alert-message" class="alert alert-danger kt-alert kt-alert--air kt-hide" role="alert"></div>

                        <form class="kt-form">
                            <div class="form-group">
                                <script src="https://www.google.com/recaptcha/api.js"></script>
                                <div class="g-recaptcha" data-sitekey="6Lf92jMUAAAAANk8wz68r73rA2uPGr4_e0gn96BL">
                                    <div style="width: 304px; height: 78px;">
                                        <div>
                                            <iframe src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6Lf92jMUAAAAANk8wz68r73rA2uPGr4_e0gn96BL&amp;co=aHR0cHM6Ly9rZWVudGhlbWVzLmNvbTo0NDM.&amp;hl=en&amp;v=TYDIjJAqCk6g335bFk3AjlC3&amp;size=normal&amp;cb=54d9lb1m1j2j" width="304" height="78" role="presentation" name="a-ykb1197261yg" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox"></iframe>
                                        </div>
                                        <textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submit-verify">Submit</button>
                    </div>
                </div>
            </div>
        </div>-->
        <!--end::Modal-->
    </div>
    <!-- end:: Content -->
</div>