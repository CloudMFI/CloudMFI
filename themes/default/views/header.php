<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<base href="<?= site_url() ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>
			<?= $page_title ?> -
				<?= $Settings->site_name ?>
		</title>
		<link rel="shortcut icon" href="<?= $assets ?>images/icon.png"/>
		<link href="<?= $assets ?>styles/theme.css" rel="stylesheet" />
		<link href="<?= $assets ?>styles/style.css" rel="stylesheet" />
		<script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="<?= $assets ?>js/jquery-migrate-1.2.1.min.js"></script>
		<!--[if lt IE 9]>
		<script src="<?= $assets ?>js/jquery.js"></script>
		<![endif]-->
		<noscript>
			<style type="text/css">
				#loading {
					display: none;
				}
			</style>
		</noscript>
		<?php if ($Settings->rtl) { ?>
			<link href="<?= $assets ?>styles/helpers/bootstrap-rtl.min.css" rel="stylesheet" />
			<link href="<?= $assets ?>styles/style-rtl.css" rel="stylesheet" />
			<script type="text/javascript">
				$(document).ready(function () {
					$('.pull-right, .pull-left').addClass('flip');
				});
			</script>
			<?php } ?>
				<script type="text/javascript">
					$(window).load(function () {
						$("#loading").fadeOut("slow");
					});
				</script>
	</head>

	<body>
		<noscript>
			<div class="global-site-notice noscript">
				<div class="notice-inner">
					<p><strong>JavaScript seems to be disabled in your browser.</strong>
						<br>You must have JavaScript enabled in your browser to utilize the functionality of this website.</p>
				</div>
			</div>
		</noscript>
		<div id="loading"></div>
		<div id="app_wrapper">
			<header id="header" class="navbar">
				<div class="container">
					<a class="navbar-brand" href="<?= site_url() ?>"><span class="logo"><?= $Settings->site_name ?></span><br/><span class="visible-md visible-lg visible-sm" style="font-size:12px;"> Branch: <?php echo $getBranchName?$getBranchName->name:'N/A';?>
					
					<?php 
					/*
					if(isset($_SESSION["branchName"])!=''){
						echo '<br/><span class="visible-md visible-lg visible-sm" style="font-size:12px;"> Branch: ';
						echo $_SESSION["branchName"];
					}
						*/
					?></span></a>
					
					<div class="btn-group visible-xs pull-right btn-visible-sm">
						<a class="btn bdarkGreen" style="margin-left:10px !important;margin-right:10px !important;margin-top:1px !important;padding-right:10px !important" title="<?= lang('loans') ?>" data-placement="left" href="<?= site_url('pos') ?>">
							<i class="fa fa-th-large"></i> <span class="padding02"><?= lang('loans') ?></span>
						</a>

						<button class="navbar-toggle btn" type="button" data-toggle="collapse" data-target="#sidebar_menu">
							<span class="fa fa-bars"></span>
						</button>

						<a href="<?= site_url('users/profile/' . $this->session->userdata('user_id')); ?>" class="btn">
							<span class="fa fa-user"></span>
						</a>
						<a href="<?= site_url('logout'); ?>" class="btn">
							<span class="fa fa-sign-out"></span>
						</a>
					</div>
					<div class="header-nav">
						<ul class="nav navbar-nav pull-right">
							<li class="dropdown">
								<a class="btn account dropdown-toggle" data-toggle="dropdown" href="#">
									<img alt="" src="<?= $this->session->userdata('avatar') ? site_url() . 'assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" class="mini_avatar img-rounded">                        
									<br>
									<div class="user">
										<p><?= $this->session->userdata('username'); ?></p>
									</div>
								</a>
								<ul class="dropdown-menu pull-right">
									<li>
										<a href="<?= site_url('users/profile/' . $this->session->userdata('user_id')); ?>">
											<i class="fa fa-user"></i>
											<?= lang('profile'); ?>
										</a>
									</li>
									<li>
										<a href="<?= site_url('users/profile/' . $this->session->userdata('user_id') . '/#cpassword'); ?>"><i class="fa fa-key"></i> <?= lang('change_password'); ?>
									</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="<?= site_url('logout'); ?>">
											<i class="fa fa-sign-out"></i>
											<?= lang('logout'); ?>
										</a>
									</li>
								</ul>
							</li>
						</ul>
						<ul class="nav navbar-nav pull-right">
							<li class="dropdown hidden-xs">
								<a class="btn tip" title="<?= lang('dashboard') ?>" data-placement="left" href="<?= site_url('welcome') ?>"><i class="fa fa-dashboard"></i><p><?= lang('dashboard') ?></p></a>
							</li>
							<li class="dropdown hidden-xs">
								<a class="btn tip" title="<?= lang('calculator') ?>" data-placement="left" href="#" data-toggle="dropdown">
									<i class="fa fa-calculator"></i><p><?= lang('calculator') ?></p>
								</a>
								<ul class="dropdown-menu pull-right calc">
									<li class="dropdown-content">
										<span id="inlineCalc"></span>
									</li>
								</ul>
							</li>			
							<?php if ($info) { ?>
								<li class="dropdown hidden-sm">
									<a class="btn tip" title="<?= lang('notifications') ?>" data-placement="left" href="#" data-toggle="dropdown">
										<i class="fa fa-comments"></i><p><?= lang('notifications') ?></p>
										<span class="number blightOrange black"><?= sizeof($info) ?></span>
										<span class="number blightOrange black"><?= sizeof($info) ?></span>
									</a>
									<ul class="dropdown-menu pull-right content-scroll">
										<li class="dropdown-header"><i class="fa fa-comments"></i>
											<?= lang('notifications'); ?>
										</li>
										<li class="dropdown-content">
											<div class="scroll-div">
												<div class="top-menu-scroll">
													<ol class="oe">
														<?php foreach ($info as $n) {
												echo '<li>' . $n->comment . '</li>';
											} ?>
													</ol>
												</div>
											</div>
										</li>
									</ul>
								</li>
							<?php } ?>
							<?php if ($events) { ?>
								<li class="dropdown hidden-xs">
									<a class="btn tip" title="<?= lang('calendar') ?>" data-placement="left" href="#" data-toggle="dropdown">
										<i class="fa fa-calendar"></i><p><?= lang('Calendar') ?></p>
										<span class="number blightOrange black"><?= sizeof($events) ?>Calander Me</span>
									</a>
									<ul class="dropdown-menu pull-right content-scroll">
										<li class="dropdown-header">
											<i class="fa fa-calendar"></i><p><?= lang('calendar') ?></p>
											<?= lang('upcoming_events'); ?>
										</li>
										<li class="dropdown-content">
											<div class="top-menu-scroll">
												<ol class="oe">
													<?php 
													if($events){
													foreach ($events as $event) {
														//echo '<li><strong>' . date($dateFormats['php_sdate'], strtotime($event->date)) . ':</strong><br>' . $this->erp->decode_html($event->data) . '</li>';
														echo '<li><strong>' .$this->erp->hrld($event->start). ': <span style="color:#2980b9;">'. $event->title.'</span></strong><br></li>';
													}} ?>
												</ol>
											</div>
										</li>
										<li class="dropdown-footer">
											<a href="<?= site_url('calendar') ?>" class="btn-block link">
												<i class="fa fa-calendar"></i><p><?= lang('calendar') ?></p>
											</a>
										</li>
									</ul>
								</li>
							<?php } else {?>
									<li class="dropdown hidden-xs">
										<a class="btn tip" title="<?= lang('calendar') ?>" data-placement="left" href="<?= site_url('calendar') ?>">
											<i class="fa fa-calendar"></i><p><?= lang('calendar') ?></p>
										</a>
									</li>
							<?php } ?>
							<li class="dropdown hidden-sm">
								<a class="btn tip" title="<?= lang('styles') ?>" data-placement="left" data-toggle="dropdown" href="#">
									<i class="fa fa-css3"></i><p><?= lang('styles') ?></p>
								</a>
								<ul class="dropdown-menu pull-right">
									<li class="bwhite noPadding">
										<a href="#" id="fixed" class="">
											<i class="fa fa-angle-double-left"></i>
											<span id="fixedText">Fixed</span>
										</a>
										<a href="#" id="cssLight" class="grey">
											<i class="fa fa-stop"></i> Grey
										</a>
										<a href="#" id="cssBlue" class="blue">
											<i class="fa fa-stop"></i> Blue
										</a>
										<a href="#" id="cssBlack" class="black">
											<i class="fa fa-stop"></i> Black
										</a>
										<a href="#" id="cssPurpie" class="purple">
											<i class="fa fa-stop"></i> Purple
										</a>
										<a href="#" id="cssGreen" class="green">
											<i class="fa fa-stop"></i> Green
										</a>
									</li>
								</ul>
							</li>
							
							<li class="dropdown hidden-xs">
								<a class="btn tip" title="<?= lang('language') ?>" data-placement="left" data-toggle="dropdown" href="#">
									<img src="<?= base_url('assets/images/'.$Settings->language .'.png'); ?>" alt=""><p><?= lang('language') ?></p>
								</a>
								<ul class="dropdown-menu pull-right">
									<?php 
										$scanned_lang_dir = array_map(function ($path) { return basename($path); }, glob(APPPATH . 'language/*', GLOB_ONLYDIR));
										foreach ($scanned_lang_dir as $entry) {
									?>
										<li>
											<a href=" <?= site_url('welcome/language/'. $entry); ?> ">
												<img src="<?= base_url();?>assets/images/<?= $entry;?>.png" class="language-img"> 
												&nbsp;&nbsp; <?= ucwords($entry); ?>
											</a>
										</li>
									<?php } ?>
								</ul>
							</li>
							<?php if ($Owner && $Settings->update) { ?>
								<li class="dropdown hidden-sm">
									<a class="btn blightOrange tip" title="<?= lang('update_available') ?>" data-placement="bottom" data-container="body" href="<?= site_url('system_settings/updates') ?>">
										<i class="fa fa-download"></i>
									</a>
								</li>
							<?php } ?>
							<?php if (($Owner || $Admin) || ($new_applicant > 0 || $approved_applicant > 0 || $activated_applicant > 0)) { ?>
								<li class="dropdown hidden-sm">
									<?php $all_alert = $new_applicant + $approved_applicant + $activated_applicant; ?>
									<a class="btn tip" title="<?= lang('alerts') ?>" data-placement="left" data-toggle="dropdown" href="#" style="background-color:#fabb3d">
									<span class="label label-danger pull-right" style="background-color:#FF0000 !important;position: absolute; margin-top: -3px; font-size: 10px; margin-right: -9px; padding-right: 6px; right: 10px; top: 4px;"><?= ($all_alert? $all_alert:''); ?></span>	
								<i class="fa fa-exclamation-triangle" style=""></i><p style=""><?= lang('alerts') ?></p>
									</a>
									<ul class="dropdown-menu pull-right">
										<li>
											<a href="<?= site_url('quotes') ?>" class="">
												<span class="label label-danger pull-right" style="margin-top:3px;"><?= $new_applicant; ?></span>
												<span style="padding-right: 35px;"><?= lang('new_applicant') ?></span>
											</a>
										</li>
										<li>
											<a href="<?= site_url('quotes') ?>" class="">
												<span class="label label-danger pull-right" style="margin-top:3px;"><?= $approved_applicant; ?></span>
												<span style="padding-right: 35px;"><?= lang('approved_applicant') ?></span>
											</a>
										</li>
										<li>
											<a href="<?= site_url('down_payment/contract_list') ?>" class="">
												<span class="label label-danger pull-right" style="margin-top:3px;"><?= $activated_applicant; ?></span>
												<span style="padding-right: 35px;"><?= lang('activated_applicant') ?></span>
											</a>
										</li>
									</ul>
								</li>
							<?php } ?>								 
							<?php if ($Owner || $Admin) { ?> 
								<li class="dropdown hidden-xs">
									<a class="btn bred tip" title="<?= lang('clear_ls') ?>" data-placement="bottom" id="clearLS" href="#">
										<i class="fa fa-eraser"></i><p><?= lang('clear') ?></p> 
									</a>
								</li>
							<?php } ?>
							
						</ul>
					</div>
				</div>
			</header>
		</div>

        <div class="container bblack" id="container">
            <div class="row" id="main-con">
                <div id="sidebar-left" class="col-lg-2 col-md-2">
                    <div class="sidebar-nav nav-collapse collapse navbar-collapse" id="sidebar_menu">
                        <ul class="nav main-menu">
                            <li class="mm_welcome">
                                <a href="<?= site_url() ?>">
                                    <i class="fa fa-dashboard"></i>
                                    <span class="text"> <?= lang('dashboard'); ?></span>
                                </a>
                            </li>

							<?php if ($Owner || $Admin) { ?>
								<li class="mm_notifications">
                                    <a class="submenu" href="<?= site_url('notifications'); ?>">
                                        <i class="fa fa-newspaper-o"></i><span class="text"> <?= lang('notifications'); ?></span>
                                    </a>
                                </li> 
								<!-- Simulation -->
								<li class="mm_simulation">
                                    <a class="submenu" href="<?= site_url('simulation'); ?>">
                                        <i class="fa fa-calculator"></i><span class="text"> <?= lang('simulation'); ?></span>
                                    </a>
                                </li> 
								
								<!----- Manage Applicant-->
                                <li class="mm_quotes mm_down_payment">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-file-archive-o"></i>
                                        <span class="text"> <?= lang('manage_loan'); ?> </span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
                                        <li id="quotes_add" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('quotes/add'); ?>">
                                                <i class="fa fa-plus"></i>
                                                <span class="text"> <?= lang('loan_applications'); ?></span>
                                            </a>
                                        </li>
										<!--<li id="quotes_fields_check" class="sub_navigation">
											<a class="submenu" href="<?= site_url('quotes/fields_check'); ?>">
												<i class="fa fa-map"></i>
												<span class="text"> <?= lang('field_check'); ?></span>
											</a>
										</li>-->
										<li id="quotes_index" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('quotes'); ?>">
                                                <i class="fa fa-list"></i>
                                                <span class="text"> <?= lang('loans_applies'); ?></span>
                                            </a>
                                        </li>
										<li id="down_payment_contract_list" class="sub_navigation">
												<a class="submenu" href="<?= site_url('down_payment/contract_list'); ?>">
													<i class="fa fa-list"></i>
													<span class="text"> <?= lang('loan_approved'); ?></span>
												</a>
										</li>
										<li id="down_payment_loan_activated" class="sub_navigation">
												<a class="submenu" href="<?= site_url('down_payment/loan_activated'); ?>">
													<i class="fa fa-list"></i>
													<span class="text"> <?= lang('loan_active'); ?></span>
												</a>
										</li>
										<li id="down_payment_loans_completed" class="sub_navigation">
												<a class="submenu" href="<?= site_url('down_payment/loans_completed'); ?>">
													<i class="fa fa-quote-left"></i>
													<span class="text"> <?= lang('loans_pay_off'); ?></span>
												</a>
										</li>
										<li id="quotes_loan_rejected" class="sub_navigation">
												<a class="submenu" href="<?= site_url('quotes/loan_rejected'); ?>">
													<i class="fa fa-quote-left"></i>
													<span class="text"> <?= lang('loan_rejected'); ?></span>
												</a>
										</li>
										<li id="down_payment_transfer_co" class="sub_navigation">
												<a class="submenu" href="<?= site_url('down_payment/transfer_co'); ?>">
													<i class="fa fa-exchange"></i>
													<span class="text"> <?= lang('co_transfer'); ?></span>
												</a>
										</li>
										
                                    </ul>
                                </li>
								
								<!-- Installment_Payment -->
                                <li class="mm_installment_payment">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-money"></i>
                                        <span class="text"> <?= lang('repayments'); ?> </span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
										<li id="installment_payment_frequency_payments">
											<a class="submenu" href="<?= site_url('Installment_payment/frequency_payments'); ?>">
												<i class="fa fa-bar-chart"></i><span class="text"> <?= lang('frequency_payments'); ?></span>
											</a>
										</li>
										<li id="installment_payment_index">
											<a class="submenu" href="<?= site_url('Installment_payment'); ?>">
												<i class="fa fa-paypal"></i><span class="text"> <?= lang('list_payments'); ?></span>
											</a>
										</li>
										<li id="">
                                            <a class="submenu" href="<?= site_url('account/add_deposit'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_deposit'); ?></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
								
								<!----- collection -->
                                <li class="mm_collection">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-yelp"></i>
                                        <span class="text"> <?= lang('bad_loans'); ?> </span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
                                        <li id="collection_index" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('collection'); ?>">
                                                <i class="fa fa-users"></i>
                                                <span class="text"> <?= lang('bad_loans_list'); ?></span>
                                            </a>
                                        </li>
										
                                    </ul>
                                </li>
								
								<!----- Manage Saving-->
                                <li class="mm_saving">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-cc-mastercard"></i>
                                        <span class="text"> <?= lang('manage_saving'); ?> </span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
										<!--<li id="saving_open_account" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('saving/open_account'); ?>">
                                                <i class="fa fa-plus"></i>
                                                <span class="text"> <?= lang('open_account'); ?></span>
                                            </a>
                                        </li> -->
										<li id="saving_compulsory_saving" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('saving/compulsory_saving'); ?>">
                                                <i class="fa fa-list"></i>
                                                <span class="text"> <?= lang('compulsory_saving'); ?></span>
                                            </a>
                                        </li>
										<li id="saving_cash_withdrawal">
                                            <a class="submenu" href="<?= site_url('saving/cash_withdrawal'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-money"></i><span class="text"> <?= lang('cash_withdrawal'); ?></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
								<!----- Manage Account -->
                                <li class="mm_account">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-book"></i>
                                        <span class="text"> <?= lang('manage_accounts') ?></span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
										<li id="">
                                            <a class="submenu" href="<?= site_url('account/add_disbursement'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_disbursement'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_listjournal" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('account/listJournal'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_journal'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_add_journal" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('account/add_journal'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_journal'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_list_ac_recevable" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('account/list_ac_recevable'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_ac_receivable'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_list_ar_aging" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('account/list_ar_aging'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_ar_aging'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_billreceipt" class="sub_navigation">
                                            <a href="<?= site_url('account/billReceipt') ?>">
                                                <i class="fa fa-money"></i><span class="text"> <?= lang('bill_receipt'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_list_ac_payable">
                                            <a class="submenu" href="<?= site_url('account/list_ac_payable'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_ac_payable'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_list_ap_aging">
                                            <a class="submenu" href="<?= site_url('account/list_ap_aging'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_ap_aging'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_billpayable">
                                            <a href="<?= site_url('account/billPayable') ?>">
                                                <i class="fa fa-money"></i><span class="text"> <?= lang('bill_payable'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_index">
                                            <a class="submenu" href="<?= site_url('account'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_ac_head'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_add">
                                            <a class="submenu" href="<?= site_url('account/add'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_ac_head'); ?></span>
                                            </a>
                                        </li>
										
										
										<li id="account_deposits" style="display:none;">
                                            <a class="submenu" href="<?= site_url('account/deposits'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_deposits'); ?></span>
                                            </a>
                                        </li>	
										<li id="account_settings" class="sub_navigation">
											<a class="submenu" href="<?= site_url('account/settings'); ?>">
												<i class="fa fa-download"></i>
												<span class="text"> <?= lang('account_settings'); ?></span>
											</a>
										</li>		
                                    </ul>
                                </li>


								<!----- manage purchases ---->
									<li class="mm_purchases">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-star"></i>
                                        <span class="text"> <?= lang('expense'); ?> 
                                    </span> <span class="chevron closed"></span>
                                    </a>
                                    <ul>
                                        
										<li id="purchases_add_expense">
                                            <a class="submenu" href="<?= site_url('purchases/add_expense'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_expense'); ?></span>
                                            </a>
                                        </li>
                                        <li id="purchases_expenses">
                                            <a class="submenu" href="<?= site_url('purchases/expenses'); ?>">
                                                <i class="fa fa-dollar"></i>
                                                <span class="text"> <?= lang('list_expenses'); ?></span>
                                            </a>
                                        </li>
										
                                    </ul>
                                </li>

								<!----- Mange People-->
                                <li class="mm_auth mm_customers mm_branch mm_capital mm_transfer_money">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-users"></i>
                                        <span class="text"> <?= lang('manage_people'); ?> </span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
									<?php if ($Owner || $Admin) { ?>
                                        <li id="auth_create_user" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('auth/create_user'); ?>">
                                                <i class="fa fa-plus"></i>
                                                <span class="text"> <?= lang('add_user'); ?></span>
                                            </a>
                                        </li>
										<li id="auth_users" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('users'); ?>">
                                                <i class="fa fa-list"></i>
                                                <span class="text"> <?= lang('list_users'); ?></span>
                                            </a>
                                        </li>
									<?php } ?>
										<li id="customers_index">
											<a class="submenu" href="<?= site_url('customers'); ?>">
												<i class="fa fa-users"></i><span class="text"> <?= lang('list_customers'); ?></span>
											</a>
										</li>
										<li id="branch_add">
                                                <a class="submenu" href="<?= site_url('branch/add'); ?>" data-toggle="modal" data-target="#myModal">
                                                    <i class="fa fa-plus"></i><span class="text"> <?= lang('add_branch'); ?></span>
                                                </a>
                                        </li>
										<li id="branch_index" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('branch'); ?>">
                                                <i class="fa fa-list"></i>
                                                <span class="text"> <?= lang('list_branchs'); ?></span>
                                            </a>
                                        </li>
										
										<li id="transfer_money_add" class="sub_navigation">
                                             <a class="submenu" href="<?= site_url('transfer_money/add'); ?>" data-toggle="modal" data-target="#myModal">
												<i class="fa fa-plus"></i><span class="text"> <?= lang('add_money_transfer'); ?></span>
											</a>
                                        </li>
										<li id="transfer_money_index" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('Transfer_money'); ?>">
                                                <i class="fa fa-list"></i>
                                                <span class="text"> <?= lang('list_money_transfer'); ?></span>
                                            </a>
                                        </li>
										
                                    </ul>
                                </li>
								
								<!----- Report-->
                                <li class="mm_reports mm_documents">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-file-text-o"></i>
                                        <span class="text"> <?= lang('reports'); ?> </span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
										<li id="reports_summary_report">
											<a href="<?= site_url('reports/summary_report') ?>">
												<i class="fa fa-building"></i><span class="text"><?= lang('summary_report_chart'); ?></span>
											</a>
										</li>										
										
										<li class="mm_laons_reports">
											<a class="dropmenu" href="#">
												<i class="fa fa-money"></i>
												<span class="text"> <?= lang('loans_reports'); ?> </span>
												<span class="chevron closed"></span>
											</a>
											<ul>												
												
												<li id="reports_loan_report">
													<a href="<?= site_url('reports/loan_report') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('loan_report'); ?></span>
													</a>
												</li>												
												<li id="reports_daily_sales">
													<a href="<?= site_url('reports/daily_sales') ?>">
														<i class="fa fa-list"></i><span class="text"> <?= lang('loan_daily'); ?></span>
													</a>
												</li>
												<li id="reports_loan_monthly">
													<a href="<?= site_url('reports/monthly_sales') ?>">
														<i class="fa fa-list"></i><span class="text"> <?= lang('loan_monthly'); ?></span>
													</a>
												</li>
												<li id="reports_daily_applicant_list" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/daily_applicant_list'); ?>">
														<i class="fa fa-list"></i>
														<span class="text"><?= lang('daily_applicant_list'); ?></span>
													</a>
												</li>
												<li id="reports_underwriter_performance" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/underwriter_performance'); ?>">
														<i class="fa fa-barcode"></i>
														<span class="text"> <?= lang('underwriter_performance'); ?></span>
													</a>
												</li>
												<li id="reports_contract" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/contract'); ?>">
														<i class="fa fa-barcode"></i>
														<span class="text"> <?= lang('contract_report'); ?></span>
													</a>
												</li>
												<!--<li id="reports_list_end_contract" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/loans_completed'); ?>">
														<i class="fa fa-list"></i>
														<span class="text"> <?= lang('loans_completed'); ?></span>
													</a>
												</li>-->
												<li id="reports_outstanding_reports" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/outstanding_reports'); ?>">
														<i class="fa fa-barcode"></i>
														<span class="text"> <?= lang('outstanding_reports'); ?></span>
													</a>
												</li>
											</ul>
										</li>
									
										<li class="mm_payments_reports">
											<a class="dropmenu" href="#">
												<i class="fa fa-money"></i>
												<span class="text"> <?= lang('payments_reports'); ?> </span>
												<span class="chevron closed"></span>
											</a>
											<ul>
												
												<li id="reports_print_installment" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/print_installment'); ?>">
														<i class="fa fa-barcode"></i>
														<span class="text"> <?= lang('daily_repayments'); ?></span>
													</a>
												</li>
												<li id="reports_print_late_installment" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/print_late_installment'); ?>">
														<i class="fa fa-barcode"></i>
														<span class="text"> <?= lang('bad_loan_repayments'); ?></span>
													</a>
												</li>
												<li id="reports_payments" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/payments'); ?>">
														<i class="fa fa-money"></i>
														<span class="text"> <?= lang('payments_report'); ?></span>
													</a>
												</li>
												<li id="reports_loan_daily_collection" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/loan_daily_collection'); ?>">
														<i class="fa fa-money"></i>
														<span class="text"> <?= lang('loan_daily_collection'); ?></span>
													</a>
												</li>
												<li id="reports_loan_daily_collection_details" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/loan_daily_collection_details'); ?>">
														<i class="fa fa-money"></i>
														<span class="text"> <?= lang('daily_loan_collection_details'); ?></span>
													</a>
												</li>
												<li id="reports_repayment_reports" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/repayment_reports'); ?>">
														<i class="fa fa-money"></i>
														<span class="text"> <?= lang('repayment_reports'); ?></span>
													</a>
												</li>
											</ul>
										</li>
										
										<li class="mm_co_reports">
											<a class="dropmenu" href="#">
												<i class="fa fa-money"></i>
												<span class="text"> <?= lang('c_o_reports'); ?> </span>
												<span class="chevron closed"></span>
											</a>
											<ul>
												<li id="reports_co_reports" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/co_reports'); ?>">
														<i class="fa fa-barcode"></i>
														<span class="text"> <?= lang('co_reports'); ?></span>
													</a>
												</li>
												<li id="reports_co_collection" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/co_collection'); ?>">
														<i class="fa fa-barcode"></i>
														<span class="text"> <?= lang('co_collection_reports'); ?></span>
													</a>
												</li>
												<li id="reports_branch_report">
													<a href="<?= site_url('reports/branch_report') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('branch_report'); ?></span>
													</a>
												</li>
												<li id="reports_staff_performance" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/staff_performance'); ?>">
														<i class="fa fa-barcode"></i>
														<span class="text"> <?= lang('staff_performance'); ?></span>
													</a>
												</li>
												
											</ul>
										</li>
										
										<li class="mm_nbc_reports">
											<a class="dropmenu" href="#">
												<i class="fa fa-money"></i>
												<span class="text"> <?= lang('nec_report'); ?> </span>
												<span class="chevron closed"></span>
											</a>
											<ul>
												<!---Start new report--->
												<li id="reports_balance_sheet_reports">
													<a href="<?= site_url('reports/balance_sheet_reports') ?>">
														<i class="fa fa-list"></i><span class="text"> <?= lang('balance_sheet_reports'); ?></span>
													</a>
												</li>
												<li id="reports_income_statement_report">
													<a href="<?= site_url('reports/income_statement_report') ?>">
														<i class="fa fa-list"></i><span class="text"> <?= lang('income_statement_report'); ?></span>
													</a>
												</li>
												<li id="reports_income_statement_report">
													<a href="<?= site_url('reports/income_statement_report') ?>">
														<i class="fa fa-list"></i><span class="text"> <?= lang('income_statement_report'); ?></span>
													</a>
												</li>
												<li id="reports_loan_classification">
													<a href="<?= site_url('reports/loan_classification') ?>">
														<i class="fa fa-list"></i><span class="text"> <?= lang('loan_classification'); ?></span>
													</a>
												</li>
												<li id="reports_weigth_interest_rate_report">
													<a href="<?= site_url('reports/weigth_interest_rate_report') ?>">
														<i class="fa fa-list"></i><span class="text"> <?= lang('weigth_interest_rate_report'); ?></span>
													</a>
												</li>
												<li id="reports_credit_by_types_report">
													<a href="<?= site_url('reports/credit_by_types_report') ?>">
														<i class="fa fa-list"></i><span class="text"> <?= lang('credit_by_types_report'); ?></span>
													</a>
												</li>
												<li id="reports_credit_by_currencies_report">
													<a href="<?= site_url('reports/credit_by_currencies_report') ?>">
														<i class="fa fa-list"></i><span class="text"> <?= lang('credit_by_currencies_report'); ?></span>
													</a>
												</li>
												<li id="reports_large_exposure_report">
													<a href="<?= site_url('reports/large_exposure_report') ?>">
														<i class="fa fa-list"></i><span class="text"> <?= lang('large_exposure_report'); ?></span>
													</a>
												</li>
												<li id="reports_deposit_by_types_report">
													<a href="<?= site_url('reports/deposit_by_types_report') ?>">
														<i class="fa fa-list"></i><span class="text"> <?= lang('deposit_by_types_report'); ?></span>
													</a>
												</li>
												<li id="reports_deposit_by_currencies">
													<a href="<?= site_url('reports/deposit_by_currencies') ?>">
														<i class="fa fa-list"></i><span class="text"> <?= lang('deposit_by_currencies'); ?></span>
													</a>
												</li>
												<li id="reports_network_informations">
													<a href="<?= site_url('reports/network_informations') ?>">
														<i class="fa fa-list"></i><span class="text"> <?= lang('network_informations'); ?></span>
													</a>
												</li>
											</ul>
										</li>
										
										<li class="mm_accounting_reports">
											<a class="dropmenu" href="#">
												<i class="fa fa-money"></i>
												<span class="text"> <?= lang('accounting_reports'); ?> </span>
												<span class="chevron closed"></span>
											</a>
											<ul>
												<li id="reports_ledger">
													<a href="<?= site_url('reports/ledger') ?>">
														<i class="fa fa-book"></i><span class="text"> <?= lang('ledger'); ?></span>
													</a>
												</li>
												<li id="reports_trial_balance">
													<a href="<?= site_url('reports/trial_balance') ?>">
														<i class="fa fa-bars"></i><span class="text"> <?= lang('trial_balance'); ?></span>
													</a>
												</li>
												<li id="reports_balance_sheet">
													<a href="<?= site_url('reports/balance_sheet') ?>">
														<i class="fa fa-balance-scale"></i><span class="text"> <?= lang('balance_sheet'); ?></span>
													</a>
												</li>
												<li id="reports_balance_sheet_details">
													<a href="<?= site_url('reports/balance_sheet_details') ?>">
														<i class="fa fa-balance-scale"></i><span class="text"> <?= lang('balance_sheet_details'); ?></span>
													</a>
												</li>																
												<li id="reports_income_statement">
													<a href="<?= site_url('reports/income_statement') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('income_statement'); ?></span>
													</a>
												</li>																
												<li id="reports_income_statement_detail">
													<a href="<?= site_url('reports/income_statement_detail') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('income_statement_detail'); ?></span>
													</a>
												</li>
												<li id="reports_cash_books">
													<a href="<?= site_url('reports/cash_books') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('cash_book'); ?></span>
													</a>
												</li>
												<li id="reports_cash_book_details">
													<a href="<?= site_url('reports/cash_book_details') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('cash_book_details'); ?></span>
													</a>
												</li>
												<li id="reports_capital_reports">
													<a href="<?= site_url('reports/capital_reports') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('capital_reports'); ?></span>
													</a>
												</li>
												<li id="reports_shareholder_reports">
													<a href="<?= site_url('reports/shareholder_reports') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('shareholder_reports'); ?></span>
													</a>
												</li>
											</ul>
										</li>
										
										
										<li id="reports_daily_transaction_report">
											<a href="<?= site_url('reports/daily_transaction_report') ?>">
												<i class="fa fa-money"></i><span class="text"> <?= lang('daily_transaction_report'); ?></span>
											</a>
										</li>
										<li id="reports_daily_cash_transaction_report">
											<a href="<?= site_url('reports/daily_cash_transaction_report') ?>">
												<i class="fa fa-money"></i><span class="text"> <?= lang('daily_cash_transaction_report'); ?></span>
											</a>
										</li>
										
                                    </ul>
                                </li>
								
								<!----- Setting-->
                                <li class="mm_system_settings mm_products">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-cogs"></i>
                                        <span class="text"> <?= lang('settings'); ?> </span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
										<li id="system_settings_index" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('system_settings'); ?>">
                                                <i class="fa fa-cog"></i>
                                                <span class="text"> <?= lang('system_settings'); ?></span>
                                            </a>
                                        </li>							
                                        <li id="system_settings_user_groups" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('system_settings/user_groups'); ?>">
                                                <i class="fa fa-users"></i>
                                                <span class="text"> <?= lang('define_user_group'); ?></span>
                                            </a>
                                        </li>
										<li id="system_settings_customer_groups" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('system_settings/customer_groups'); ?>">
                                                <i class="fa fa-users"></i>
                                                <span class="text"> <?= lang('customer_groups'); ?></span>
                                            </a>
                                        </li>
										<li id="system_settings_categories" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('system_settings/categories'); ?>">
                                                <i class="fa fa-object-group"></i>
                                                <span class="text"> <?= lang('categories'); ?></span>
                                            </a>
                                        </li>
										<li id="system_settings_subcategories" class="sub_navigation">
											<a class="submenu" href="<?= site_url('system_settings/subcategories'); ?>">
												<i class="fa fa-cubes"></i>
												<span class="text"> <?= lang('types'); ?></span>
											</a>
										</li>
										<li id="products_index" class="sub_navigation">
											<a class="submenu" href="<?= site_url('products'); ?>">
												<i class="fa fa-list"></i>
												<span class="text"> <?= lang('products'); ?></span>
											</a>
										</li>										
										<li id="system_settings_financial_product" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('system_settings/financial_product'); ?>">
                                                <i class="fa fa-calendar-o"></i>
                                                <span class="text"> <?= lang('service'); ?></span>
                                            </a>
                                        </li>	
										<li id="system_settings_insurances" class="sub_navigation"style="display:none;">
                                            <a class="submenu" href="<?= site_url('system_settings/insurances'); ?>">
                                                <i class="fa fa-shield"></i>
                                                <span class="text"> <?= lang('insurance_companies'); ?></span>
                                            </a>
                                        </li>	
										<li id="system_settings_currencies" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('system_settings/currencies'); ?>">
                                                <i class="fa fa-usd"></i>
                                                <span class="text"> <?= lang('define_currency'); ?></span>
                                            </a>
                                        </li>
										<li id="system_settings_tax_rates" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('system_settings/tax_rates'); ?>">
                                                <i class="fa fa-bar-chart"></i>
                                                <span class="text"> <?= lang('tax_rates'); ?></span>
                                            </a>
                                        </li>
										
										
										<li id="system_settings_identify_type" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('system_settings/identify_type'); ?>">
                                                <i class="fa fa-key"></i>
                                                <span class="text"> <?= lang('indentify_types'); ?></span>
                                            </a>
                                        </li>
										<!--<li id="system_settings_term" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('system_settings/term'); ?>">
                                                <i class="fa fa-calendar-o"></i>
                                                <span class="text"> <?= lang('define_term'); ?></span>
                                            </a>
                                        </li>-->	
										<!--<li id="system_settings_collateral_types" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('system_settings/collateral_types'); ?>">
                                                <i class="fa fa-file-o"></i>
                                                <span class="text"> <?= lang('collateral_types'); ?></span>
                                            </a>
                                        </li>-->
										<!--<li id="system_settings_type" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('system_settings/type'); ?>">
                                                <i class="fa fa-car"></i>
                                                <span class="text"> <?= lang('model'); ?></span>
                                            </a>
                                        </li>-->										
										<!--<li id="system_settings_audit_trail" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('system_settings/audit_trail'); ?>">
                                                <i class="fa fa-random"></i>
                                                <span class="text"> <?= lang('audit_trail'); ?></span>
                                            </a>
                                        </li>-->
										<!--<li id="system_settings_sms" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('system_settings/sms'); ?>">
                                                <i class="fa fa-envelope"></i>
                                                <span class="text"> <?= lang('sms'); ?></span>
                                            </a>
                                        </li>-->
										<!--<li id="system_settings_warehouses" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('system_settings/warehouses'); ?>">
                                                <i class="fa fa-cog"></i>
                                                <span class="text"> <?= lang('warehouses'); ?></span>
                                            </a>
                                        </li>-->
										<li id="system_settings_backups" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('system_settings/backups'); ?>">
                                                <i class="fa fa-download"></i>
                                                <span class="text"> <?= lang('backup/restore'); ?></span>
                                            </a>
                                        </li>
										<li id="system_settings_cbc" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('system_settings/cbc'); ?>">
                                                <i class="fa fa-download"></i>
                                                <span class="text"> <?= lang('data_export_cbc'); ?></span>
                                            </a>
                                        </li>
										
										<!----Reject--->
										<li id="system_settings_reject_reasons" class="sub_navigation">
											<a class="submenu" href="<?= site_url('system_settings/reject_reasons'); ?>">
												<i class="fa fa-y-combinator"></i>
												<span class="text"> <?= lang('reject_reasons'); ?></span>
											</a>
										</li>
										
										<!----holidays--->
										<li id="system_settings_holidays" class="sub_navigation">
											<a class="submenu" href="<?= site_url('system_settings/holidays'); ?>">
												<i class="fa fa-calendar"></i>
												<span class="text"> <?= lang('define_holidays'); ?></span>
											</a>
										</li>
										
										<!----policy_completly_payment--->
										<li id="system_settings_policy_payments" class="sub_navigation">
											<a class="submenu" href="<?= site_url('system_settings/policy_payments'); ?>">
												<i class="fa fa-calendar"></i>
												<span class="text"> <?= lang('define_policy_payments'); ?></span>
											</a>
										</li>
										
                                    </ul>
                                </li>
							<?php } else { // not owner and not admin ?>
									<li class="mm_notifications">
										<a class="submenu" href="<?= site_url('notifications'); ?>">
											<i class="fa fa-newspaper-o"></i><span class="text"> <?= lang('notifications'); ?></span>
										</a>
									</li> 
								<!-- Simulation -->
								<?php if($GP['simulation-index']) { ?>
									<li class="mm_simulation">
										<a class="submenu" href="<?= site_url('simulation'); ?>">
											<i class="fa fa-calculator"></i><span class="text"> <?= lang('simulation'); ?></span>
										</a>
									</li> 
								<?php } ?>
								
								
								<!----- Manage Applicant-->
								<?php if($GP['quotes-index'] || $GP['quotes-add'] || $GP['field_check-add'] || $GP['reject-index']) { ?>
									<li class="mm_quotes mm_down_payment">
										<a class="dropmenu" href="#">
											<i class="fa fa-file-archive-o"></i>
											<span class="text"> <?= lang('manage_loan'); ?> </span>
											<span class="chevron closed"></span>
										</a>
										<ul>
											<?php if($GP['quotes-add']) { ?>
												<li id="quotes_add" class="sub_navigation">
													<a class="submenu" href="<?= site_url('quotes/add'); ?>">
														<i class="fa fa-plus"></i>
														<span class="text"> <?= lang('loan_applications'); ?></span>
													</a>
												</li>
											<?php } ?>
											<?php if($GP['field_check-add']) { ?>
												<!--<li id="quotes_fields_check" class="sub_navigation">
													<a class="submenu" href="<?= site_url('quotes/fields_check'); ?>">
														<i class="fa fa-map"></i>
														<span class="text"> <?= lang('field_check'); ?></span>
													</a>
												</li>-->
											<?php } ?>
											<?php if($GP['quotes-index']) { ?>
												<li id="quotes_index" class="sub_navigation">
													<a class="submenu" href="<?= site_url('quotes'); ?>">
														<i class="fa fa-list"></i>
														<span class="text"> <?= lang('loans_applies'); ?></span>
													</a>
												</li>
											<?php } ?>
											<?php if($GP['contract-index']) { ?>
											<li id="down_payment_contract_list" class="sub_navigation">
													<a class="submenu" href="<?= site_url('down_payment/contract_list'); ?>">
														<i class="fa fa-list"></i>
														<span class="text"> <?= lang('loan_approved'); ?></span>
													</a>
											</li>
											<?php } ?>
											<?php if($GP['contract-index']) { ?>
											<li id="down_payment_loan_activated" class="sub_navigation">
												<a class="submenu" href="<?= site_url('down_payment/loan_activated'); ?>">
													<i class="fa fa-list"></i>
													<span class="text"> <?= lang('loans_active'); ?></span>
												</a>
											</li>
											<?php } ?>
											<?php if($GP['completed-index']) { ?>
											<li id="down_payment_loans_completed" class="sub_navigation">
													<a class="submenu" href="<?= site_url('down_payment/loans_completed'); ?>">
														<i class="fa fa-quote-left"></i>
														<span class="text"> <?= lang('loans_pay_off'); ?></span>
													</a>
											</li>
											<?php } ?>
											<?php if($GP['reject-index']) { ?>
											<li id="quotes_loan_rejected" class="sub_navigation">
													<a class="submenu" href="<?= site_url('quotes/loan_rejected'); ?>">
														<i class="fa fa-quote-left"></i>
														<span class="text"> <?= lang('loan_rejected'); ?></span>
													</a>
											</li>
											<?php } ?>
											<?php if($GP['quotes-transfer']) { ?>
											<li id="down_payment_transfer_co" class="sub_navigation">
												<a class="submenu" href="<?= site_url('down_payment/transfer_co'); ?>">
													<i class="fa fa-exchange"></i>
													<span class="text"> <?= lang('co_transfer'); ?></span>
												</a>
											</li>
											<?php } ?>
											<?php if($GP['contract-index']) { ?>
											<li id="down_payment_tranfer_contract" class="sub_navigation">
													<a class="submenu" href="<?= site_url('down_payment/tranfer_contract'); ?>">
														<i class="fa fa-exchange"></i>
														<span class="text"> <?= lang('loan_transfer'); ?></span>
													</a>
											</li>
											<?php } ?>
										</ul>
									</li>
								<?php } ?>
								
								
									
								<!-- Installment_Payment -->
								<?php if($GP['installment_payment-index'] || $GP['installment_payment-add'] || $GP['installment_payment-edit'] || $GP['installment_payment-delete'] || $GP['installment_payment-import'] || $GP['installment_payment-export'] || $GP['deposit-add']) { ?>
										<li class="mm_installment_payment">
											<a class="dropmenu" href="#">
												<i class="fa fa-money"></i>
												<span class="text"> <?= lang('repayments'); ?> </span>
												<span class="chevron closed"></span>
											</a>
											<ul>
											<?php if($GP['installment_payment-index']){ ?>
												<li id="installment_payment_frequency_payments">
													<a class="submenu" href="<?= site_url('Installment_payment/frequency_payments'); ?>">
														<i class="fa fa-bar-chart"></i><span class="text"> <?= lang('frequency_payments'); ?></span>
													</a>
												</li>
												<li id="installment_payment_index">
													<a class="submenu" href="<?= site_url('Installment_payment'); ?>">
														<i class="fa fa-paypal"></i><span class="text"> <?= lang('list_payments'); ?></span>
													</a>
												</li>
											<?php } ?>
											<?php if($GP['deposit-add']){ ?>
												<li id="">
													<a class="submenu" href="<?= site_url('account/add_deposit'); ?>" data-toggle="modal" data-target="#myModal">
														<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_deposit'); ?></span>
													</a>
												</li>
											<?php } ?>	
											</ul>
										</li>
								<?php } ?>
								
								<?php if($GP['money_saving-index'] || $GP['money_saving-add'] || $GP['money_saving-edit'] || $GP['money_saving-delete']) { ?>
									<!----- Manage Saving-->
									<li class="mm_saving">
										<a class="dropmenu" href="#">
											<i class="fa fa-cc-mastercard"></i>
											<span class="text"> <?= lang('manage_saving'); ?> </span>
											<span class="chevron closed"></span>
										</a>
										<ul>
										<?php if($GP['money_saving-add']) { ?>
											<!--<li id="saving_register" class="sub_navigation">
												<a class="submenu" href="<?= site_url('saving/register'); ?>">
													<i class="fa fa-plus"></i>
													<span class="text"> <?= lang('register'); ?></span>
												</a>
											</li>-->
										<?php } ?>
										<?php if($GP['money_saving-index']){ ?>
											<!--<li id="saving_index" class="sub_navigation">
												<a class="submenu" href="<?= site_url('saving'); ?>">
													<i class="fa fa-list"></i>
													<span class="text"> <?= lang('list_saving'); ?></span>
												</a>
											</li>-->
									    <?php } ?>
										<?php if($GP['money_saving-compulsory']){ ?>
											<li id="saving_compulsory_saving" class="sub_navigation">
												<a class="submenu" href="<?= site_url('saving/compulsory_saving'); ?>">
													<i class="fa fa-list"></i>
													<span class="text"> <?= lang('compulsory_saving'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if($GP['money_saving-withdrawal']){ ?>
											<li id="saving_cash_withdrawal">
												<a class="submenu" href="<?= site_url('saving/cash_withdrawal'); ?>" data-toggle="modal" data-target="#myModal">
													<i class="fa fa-money"></i><span class="text"> <?= lang('cash_withdrawal'); ?></span>
												</a>
											</li>
										</ul>
										<?php } ?>
									</li> 
								<?php } ?>
								
								<!----- Manage Account -->
								<?php if($GP['accounts-index'] || $GP['accounts-add'] || $GP['disbursement-add'] || $GP['account-list_receivable'] || $GP['account-list_ar_aging'] || $GP['account-bill_receipt'] || $GP['account-list_ac_payable'] || $GP['account-list_ap_aging'] || $GP['account-settings'] || $GP['account-add_ac_head']) { ?>
                                <li class="mm_account">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-book"></i>
                                        <span class="text"> <?= lang('manage_accounts') ?></span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
									<?php if($GP['disbursement-add']){ ?>
										<li id="">
                                            <a class="submenu" href="<?= site_url('account/add_disbursement'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_disbursement'); ?></span>
                                            </a>
                                        </li>
									<?php } ?>
									<?php if($GP['accounts-index']){ ?>
                                        <li id="account_listjournal" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('account/listJournal'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_journal'); ?></span>
                                            </a>
                                        </li>
									<?php } ?>
									<?php if($GP['accounts-add']){ ?>
                                        <li id="account_add_journal" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('account/add_journal'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_journal'); ?></span>
                                            </a>
                                        </li>
									<?php } ?>
									<?php if($GP['account-list_receivable']){ ?>
                                        <li id="account_list_ac_recevable" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('account/list_ac_recevable'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_ac_receivable'); ?></span>
                                            </a>
                                        </li>
									<?php } ?>
									<?php if($GP['account-list_ar_aging']){ ?>
                                        <li id="account_list_ar_aging" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('account/list_ar_aging'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_ar_aging'); ?></span>
                                            </a>
                                        </li>
									<?php } ?>
									<?php if($GP['account-bill_receipt']){ ?>
                                        <li id="account_billreceipt" class="sub_navigation">
                                            <a href="<?= site_url('account/billReceipt') ?>">
                                                <i class="fa fa-money"></i><span class="text"> <?= lang('bill_receipt'); ?></span>
                                            </a>
                                        </li>
									<?php } ?>
									<?php if($GP['account-list_ac_payable']){ ?>
                                        <li id="account_list_ac_payable">
                                            <a class="submenu" href="<?= site_url('account/list_ac_payable'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_ac_payable'); ?></span>
                                            </a>
                                        </li>
									<?php } ?>
									<?php if($GP['account-list_ap_aging']){ ?>
                                        <li id="account_list_ap_aging">
                                            <a class="submenu" href="<?= site_url('account/list_ap_aging'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_ap_aging'); ?></span>
                                            </a>
                                        </li>
									<?php } ?>
									<?php if($GP['account-bill_payable']){ ?>
                                        <li id="account_billpayable">
                                            <a href="<?= site_url('account/billPayable') ?>">
                                                <i class="fa fa-money"></i><span class="text"> <?= lang('bill_payable'); ?></span>
                                            </a>
                                        </li>
									<?php } ?>
									<?php if($GP['account-list_ac_head']){ ?>
                                        <li id="account_index">
                                            <a class="submenu" href="<?= site_url('account'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_ac_head'); ?></span>
                                            </a>
                                        </li>
									<?php } ?>
									<?php if($GP['account-add_ac_head']){ ?>
                                        <li id="account_add">
                                            <a class="submenu" href="<?= site_url('account/add'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_ac_head'); ?></span>
                                            </a>
                                        </li>
									<?php } ?>	
										
										<li id="account_deposits" style="display:none;">
                                            <a class="submenu" href="<?= site_url('account/deposits'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_deposits'); ?></span>
                                            </a>
                                        </li>	
									<?php if($GP['account-settings']){ ?>
										<li id="account_settings" class="sub_navigation">
											<a class="submenu" href="<?= site_url('account/settings'); ?>">
												<i class="fa fa-download"></i>
												<span class="text"> <?= lang('account_settings'); ?></span>
											</a>
										</li>	
									<?php } ?>			
                                    </ul>
                                </li>
								<?php } ?>

								<!----- manage purchases ---->
								<?php if($GP['purchases-index'] || $GP['purchases-add'] || $GP['purchases-edit'] || $GP['purchases-delete']){ ?>
									<li class="mm_purchases">
										<a class="dropmenu" href="#">
											<i class="fa fa-star"></i>
											<span class="text"> <?= lang('expenses'); ?> 
										</span> <span class="chevron closed"></span>
										</a>
										<ul>
										<?php if($GP['purchases-add']) { ?>
											<li id="purchases_add_expense">
												<a class="submenu" href="<?= site_url('purchases/add_expense'); ?>" data-toggle="modal" data-target="#myModal">
													<i class="fa fa-plus-circle"></i>
													<span class="text"> <?= lang('add_expense'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if($GP['purchases-expenses']) { ?>
											<li id="purchases_expenses">
												<a class="submenu" href="<?= site_url('purchases/expenses'); ?>">
													<i class="fa fa-dollar"></i>
													<span class="text"> <?= lang('list_expenses'); ?></span>
												</a>
											</li>
										<?php } ?>
										</ul>
									</li>
								<?php } ?>			
								<!----- collection -->
								<?php if($GP['collection-index']) { ?>
									<li class="mm_collection">
										<a class="dropmenu" href="#">
											<i class="fa fa-yelp"></i>
											<span class="text"> <?= lang('bad_loans'); ?> </span>
											<span class="chevron closed"></span>
										</a>
										<ul>
											<?php if($GP['collection-index']) { ?>
												<li id="collection_index" class="sub_navigation">
													<a class="submenu" href="<?= site_url('collection'); ?>">
														<i class="fa fa-users"></i>
														<span class="text"> <?= lang('overdue_customer'); ?></span>
													</a>
												</li>
											<?php } ?>
										</ul>
									</li>
								<?php } ?>

								
								<!----- Mange People-->
								<?php if ($Owner || $Admin) { ?> 
									<li class="mm_users">
										<a class="dropmenu" href="#">
											<i class="fa fa-users"></i>
											<span class="text"> <?= lang('mange_people'); ?> </span>
											<span class="chevron closed"></span>
										</a>
										<ul>
											<li id="users_create_user" class="sub_navigation">
												<a class="submenu" href="<?= site_url('users/create_user'); ?>">
													<i class="fa fa-plus"></i>
													<span class="text"> <?= lang('add_user'); ?></span>
												</a>
											</li>
											<li id="users_index" class="sub_navigation">
												<a class="submenu" href="<?= site_url('users'); ?>">
													<i class="fa fa-list"></i>
													<span class="text"> <?= lang('list_users'); ?></span>
												</a>
											</li>
											<li id="users_">
													<a class="submenu" href="<?= site_url('branch/add'); ?>" data-toggle="modal" data-target="#myModal">
														<i class="fa fa-plus"></i><span class="text"> <?= lang('add_branch'); ?></span>
													</a>
											</li>
											<li id="users_" class="sub_navigation">
												<a class="submenu" href="<?= site_url('branch'); ?>">
													<i class="fa fa-list"></i>
													<span class="text"> <?= lang('list_branch'); ?></span>
												</a>
											</li>
											
											<li id="users_">
													<a class="submenu" href="<?= site_url('billers/add'); ?>" data-toggle="modal" data-target="#myModal">
														<i class="fa fa-plus"></i><span class="text"> <?= lang('add_biller'); ?></span>
													</a>
											</li>
											<li id="users_">
													<a class="submenu" href="<?= site_url('billers'); ?>">
														<i class="fa fa-list"></i><span class="text"> <?= lang('list_billers'); ?></span>
													</a>
												</li>	
										</ul>
									</li>
								<?php } ?>
								
									<!----- Report-->
								<?php if($GP['reports-quote'] || $GP['reports-underwriting'] || $GP['reports-contract'] || $GP['reports-collection'] || $GP['reports-installment'] || $GP['reports-daily_loan'] || $GP['reports-summary_chart']) { ?>
									<li class="mm_reports">
										<a class="dropmenu" href="#">
											<i class="fa fa-file-text-o"></i>
											<span class="text"> <?= lang('reports'); ?> </span>
											<span class="chevron closed"></span>
										</a>
										<ul>
											<?php if($GP['reports-summary_chart']) { ?>
												<li id="reports_summary_report">
													<a href="<?= site_url('reports/summary_report') ?>">
														<i class="fa fa-building"></i><span class="text"><?= lang('summary_report_chart'); ?></span>
													</a>
												</li>
											<?php } ?>
											
											<?php if($GP['reports-daily_loan']) { ?>
												<!--<li id="reports_cash_in_out" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/cash_in_out'); ?>">
														<i class="fa fa-list"></i>
														<span class="text"><?= lang('cash_in_out_list'); ?></span>
													</a>
												</li>-->
											<?php } ?>	
											
											
											
											<li class="mm_laons_reports">
												<a class="dropmenu" href="#">
													<i class="fa fa-money"></i>
													<span class="text"> <?= lang('loans_reports'); ?> </span>
													<span class="chevron closed"></span>
												</a>
												<ul>												
													<?php if($GP['reports-applicant']){ ?>
														<li id="reports_applicant" class="sub_navigation">
															<a class="submenu" href="<?= site_url('reports/applicant'); ?>">
																<i class="fa fa-barcode"></i>
																<span class="text"> <?= lang('applicant_report'); ?></span>
															</a>
														</li>
													<?php  } ?>
													<?php if($GP['reports-loans']){ ?>
														<li id="reports_loan_report">
															<a href="<?= site_url('reports/loan_report') ?>">
																<i class="fa fa-money"></i><span class="text"> <?= lang('loan_report'); ?></span>
															</a>
														</li>
													<?php } ?>											
													<?php if($GP['reports-daily_loan']) { ?>	
														<li id="reports_daily_sales">
															<a href="<?= site_url('reports/daily_sales') ?>">
																<i class="fa fa-list"></i><span class="text"> <?= lang('daily_loan_list'); ?></span>
															</a>
														</li>
													<?php } ?>
													<?php if($GP['report-daily_applicant']) { ?>
														<li id="reports_daily_applicant_list" class="sub_navigation">
															<a class="submenu" href="<?= site_url('reports/daily_applicant_list'); ?>">
																<i class="fa fa-list"></i>
																<span class="text"> <?= lang('daily_applicant_list'); ?></span>
															</a>
														</li>
													<?php } ?>
													<?php if($GP['reports-underwriting']) { ?>
														<li id="reports_underwriter_performance" class="sub_navigation">
															<a class="submenu" href="<?= site_url('reports/underwriter_performance'); ?>">
																<i class="fa fa-barcode"></i>
																<span class="text"> <?= lang('underwriter_performance'); ?></span>
															</a>
														</li>
													<?php } ?>
													<?php if($GP['reports-contract_excel']) { ?>
														<li id="reports_contract" class="sub_navigation">
															<a class="submenu" href="<?= site_url('reports/contract'); ?>">
																<i class="fa fa-barcode"></i>
																<span class="text"> <?= lang('contract_report'); ?></span>
															</a>
														</li>
													<?php } ?>
													
												</ul>
											</li>
											
											
											<li class="mm_payments_reports">
												<a class="dropmenu" href="#">
													<i class="fa fa-money"></i>
													<span class="text"> <?= lang('payments_reports'); ?> </span>
													<span class="chevron closed"></span>
												</a>
												<ul>
													<?php if($GP['reports-installments']) { ?>
														<li id="reports_print_installment" class="sub_navigation">
															<a class="submenu" href="<?= site_url('reports/print_installment'); ?>">
																<i class="fa fa-barcode"></i>
																<span class="text"> <?= lang('daily_repayments'); ?></span>
															</a>
														</li>
													<?php } ?>
													<?php if($GP['reports-installments']) { ?>
														<li id="reports_print_late_installment" class="sub_navigation">
															<a class="submenu" href="<?= site_url('reports/print_late_installment'); ?>">
																<i class="fa fa-barcode"></i>
																<span class="text"> <?= lang('bad_loan_repayments'); ?></span>
															</a>
														</li>
													<?php } ?>
													<?php if($GP['reports-installment']) { ?>
														<li id="reports_payments" class="sub_navigation">
															<a class="submenu" href="<?= site_url('reports/payments'); ?>">
																<i class="fa fa-money"></i>
																<span class="text"> <?= lang('payments_report'); ?></span>
															</a>
														</li>
													<?php } ?>
												</ul>
											</li>
											
											<li class="mm_c_o_reports">
												<a class="dropmenu" href="#">
													<i class="fa fa-money"></i>
													<span class="text"> <?= lang('c_o_reports'); ?> </span>
													<span class="chevron closed"></span>
												</a>
												<ul>
													<li id="reports_co_reports" class="sub_navigation">
														<a class="submenu" href="<?= site_url('reports/co_reports'); ?>">
															<i class="fa fa-barcode"></i>
															<span class="text"> <?= lang('co_reports'); ?></span>
														</a>
													</li>
													<li id="reports_branch_report">
														<a href="<?= site_url('reports/branch_report') ?>">
															<i class="fa fa-money"></i><span class="text"> <?= lang('branch_report'); ?></span>
														</a>
													</li>
													<li id="reports_staff_performance" class="sub_navigation">
														<a class="submenu" href="<?= site_url('reports/staff_performance'); ?>">
															<i class="fa fa-barcode"></i>
															<span class="text"> <?= lang('staff_performance'); ?></span>
														</a>
													</li>
												</ul>
											</li>
											
											<?php if($GP['reports-daily_register']) { ?>
												<li id="reports_daily_registration" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/daily_registration'); ?>">
														<i class="fa fa-registered"></i>
														<span class="text"> <?= lang('daily_registration'); ?></span>
													</a>
												</li>
											<?php } ?>
											
											<?php if($GP['reports-collection']) { ?>
												<li id="reports/phone_collection" class="sub_navigation">
													<a class="submenu" href="<?= site_url('reports/phone_collection'); ?>">
														<i class="fa fa-phone-square"></i>
														<span class="text"> <?= lang('phone_collection'); ?></span>
													</a>
												</li>
											<?php } ?>
											
											<?php if($GP['reports-nbc']){ ?>
												<li class="mm_reports">
													<a class="dropmenu" href="#">
														<i class="fa fa-money"></i>
														<span class="text"> <?= lang('nec_report'); ?> </span>
														<span class="chevron closed"></span>
													</a>
													<ul>
														<!---Start new report--->
														<li id="reports_balance_sheet_reports">
															<a href="<?= site_url('reports/balance_sheet_reports') ?>">
																<i class="fa fa-list"></i><span class="text"> <?= lang('balance_sheet_reports'); ?></span>
															</a>
														</li>
														<li id="reports_income_statement_report">
															<a href="<?= site_url('reports/income_statement_report') ?>">
																<i class="fa fa-list"></i><span class="text"> <?= lang('income_statement_report'); ?></span>
															</a>
														</li>
														<li id="reports_income_statement_report">
															<a href="<?= site_url('reports/income_statement_report') ?>">
																<i class="fa fa-list"></i><span class="text"> <?= lang('income_statement_report'); ?></span>
															</a>
														</li>
														<li id="reports_loan_classification">
															<a href="<?= site_url('reports/loan_classification') ?>">
																<i class="fa fa-list"></i><span class="text"> <?= lang('loan_classification'); ?></span>
															</a>
														</li>
														<li id="reports_weigth_interest_rate_report">
															<a href="<?= site_url('reports/weigth_interest_rate_report') ?>">
																<i class="fa fa-list"></i><span class="text"> <?= lang('weigth_interest_rate_report'); ?></span>
															</a>
														</li>
														<li id="reports_credit_by_types_report">
															<a href="<?= site_url('reports/credit_by_types_report') ?>">
																<i class="fa fa-list"></i><span class="text"> <?= lang('credit_by_types_report'); ?></span>
															</a>
														</li>
														<li id="reports_credit_by_currencies_report">
															<a href="<?= site_url('reports/credit_by_currencies_report') ?>">
																<i class="fa fa-list"></i><span class="text"> <?= lang('credit_by_currencies_report'); ?></span>
															</a>
														</li>
														<li id="reports_large_exposure_report">
															<a href="<?= site_url('reports/large_exposure_report') ?>">
																<i class="fa fa-list"></i><span class="text"> <?= lang('large_exposure_report'); ?></span>
															</a>
														</li>
														<li id="reports_deposit_by_types_report">
															<a href="<?= site_url('reports/deposit_by_types_report') ?>">
																<i class="fa fa-list"></i><span class="text"> <?= lang('deposit_by_types_report'); ?></span>
															</a>
														</li>
														<li id="reports_deposit_by_currencies">
															<a href="<?= site_url('reports/deposit_by_currencies') ?>">
																<i class="fa fa-list"></i><span class="text"> <?= lang('deposit_by_currencies'); ?></span>
															</a>
														</li>
														<li id="reports_network_informations">
															<a href="<?= site_url('reports/network_informations') ?>">
																<i class="fa fa-list"></i><span class="text"> <?= lang('network_informations'); ?></span>
															</a>
														</li>
													</ul>
												</li>
												<?php } ?>
												
												
												<?php if($GP['reports-account']){ ?>
												<li class="mm_accounting_reports">
													<a class="dropmenu" href="#">
														<i class="fa fa-money"></i>
														<span class="text"> <?= lang('accounting_reports'); ?> </span>
														<span class="chevron closed"></span>
													</a>
													<ul>
														<?php if($GP['reports-ledger']) { ?>
														<li id="reports_ledger">
															<a href="<?= site_url('reports/ledger') ?>">
																<i class="fa fa-book"></i><span class="text"> <?= lang('ledger'); ?></span>
															</a>
														</li>
														<?php } ?>
														
														<?php if($GP['reports-trial_balance']) { ?>
														<li id="reports_trial_balance">
															<a href="<?= site_url('reports/trial_balance') ?>">
																<i class="fa fa-bars"></i><span class="text"> <?= lang('trial_balance'); ?></span>
															</a>
														</li>
														<?php } ?>
														
														<?php if($GP['reports-balance_sheet']) { ?>
														<li id="reports_balance_sheet">
															<a href="<?= site_url('reports/balance_sheet') ?>">
																<i class="fa fa-balance-scale"></i><span class="text"> <?= lang('balance_sheet'); ?></span>
															</a>
														</li>
														<li id="reports_balance_sheet_details">
															<a href="<?= site_url('reports/balance_sheet_details') ?>">
																<i class="fa fa-balance-scale"></i><span class="text"> <?= lang('balance_sheet_details'); ?></span>
															</a>
														</li>
														<?php } ?>
														
														<?php if($GP['reports-income_statement']) { ?>
														<li id="reports_income_statement">
															<a href="<?= site_url('reports/income_statement') ?>">
																<i class="fa fa-money"></i><span class="text"> <?= lang('income_statement'); ?></span>
															</a>
														</li>																
														<li id="reports_income_statement_detail">
															<a href="<?= site_url('reports/income_statement_detail') ?>">
																<i class="fa fa-money"></i><span class="text"> <?= lang('income_statement_detail'); ?></span>
															</a>
														</li>														
														<?php } ?>
														
														<?php if($GP['reports-cash_books']) { ?>
														<li id="reports_cash_books">
															<a href="<?= site_url('reports/cash_books') ?>">
																<i class="fa fa-money"></i><span class="text"> <?= lang('cash_book'); ?></span>
															</a>
														</li>
														<li id="reports_cash_book_details">
															<a href="<?= site_url('reports/cash_book_details') ?>">
																<i class="fa fa-money"></i><span class="text"> <?= lang('cash_book_details'); ?></span>
															</a>
														</li>
														<?php } ?>
														
													</ul>
												</li>
												<?php } ?>
										
											
											<?php if($GP['reports-daily_transaction']){ ?>
												<li id="reports_daily_transaction_report">
													<a href="<?= site_url('reports/daily_transaction_report') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('daily_transaction_report'); ?></span>
													</a>
												</li>
											<?php } ?>
											<?php if($GP['reports-daily_cash']){ ?>
												<li id="reports_daily_cash_transaction_report">
													<a href="<?= site_url('reports/daily_cash_transaction_report') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('daily_cash_transaction_report'); ?></span>
													</a>
												</li>
											<?php } ?>	
										</ul>
									</li>
								<?php } ?>
									
								<!----- Setting-->
								<?php if ($Owner || $Admin) { ?> 
									<li class="mm_system_settings">
										<a class="dropmenu" href="#">
											<i class="fa fa-cogs"></i>
											<span class="text"> <?= lang('settings'); ?> </span>
											<span class="chevron closed"></span>
										</a>
										<ul>
											<li id="system_settings_index" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings'); ?>">
													<i class="fa fa-cog"></i>
													<span class="text"> <?= lang('system_settings'); ?></span>
												</a>
											</li>
											<li id="system_settings_user_groups" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/user_groups'); ?>">
													<i class="fa fa-users"></i>
													<span class="text"> <?= lang('define_user_group'); ?></span>
												</a>
											</li>
											<li id="system_settings_customer_groups" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/customer_groups'); ?>">
													<i class="fa fa-users"></i>
													<span class="text"> <?= lang('customer_groups'); ?></span>
												</a>
											</li>
											<li id="system_settings_categories" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/categories'); ?>">
													<i class="fa fa-object-group"></i>
													<span class="text"> <?= lang('categories'); ?></span>
												</a>
											</li>
											<li id="system_settings_subcategories" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/subcategories'); ?>">
													<i class="fa fa-cubes"></i>
													<span class="text"> <?= lang('types'); ?></span>
												</a>
											</li>
											<li id="products_index" class="sub_navigation">
												<a class="submenu" href="<?= site_url('products'); ?>">
													<i class="fa fa-list"></i>
													<span class="text"> <?= lang('products'); ?></span>
												</a>
											</li>
											
											<li id="system_settings_account_settings" class="sub_navigation">
												<a class="submenu" href="<?= site_url('account/settings'); ?>">
													<i class="fa fa-download"></i>
													<span class="text"> <?= lang('account_settings'); ?></span>
												</a>
											</li>
											<li id="system_settings_down_persentages" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/down_persentages'); ?>">
													<i class="fa fa-credit-card"></i>
													<span class="text"> <?= lang('define_down_persentages'); ?></span>
												</a>
											</li>
											<li id="system_settings_interest_rate" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/interest_rate'); ?>">
													<i class="fa fa-line-chart"></i>
													<span class="text"> <?= lang('define_rate'); ?></span>
												</a>
											</li>
											<li id="system_settings_term" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/term'); ?>">
													<i class="fa fa-calendar-o"></i>
													<span class="text"> <?= lang('define_term'); ?></span>
												</a>
											</li>	
											<li id="system_settings_variants" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/variants'); ?>">
													<i class="fa fa-pencil-square"></i>
													<span class="text"> <?= lang('define_product_color'); ?></span>
												</a>
											</li>	
											<!--<li id="system_settings_financial_product" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/insurence'); ?>">
													<i class="fa fa-calendar-o"></i>
													<span class="text"> <?= lang('insurence'); ?></span>
												</a>
											</li>-->
											<li id="system_settings_financial_product" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/financial_product'); ?>">
													<i class="fa fa-calendar-o"></i>
													<span class="text"> <?= lang('service'); ?></span>
												</a>
											</li>	
											<li id="system_settings_insurance_companies" class="sub_navigation" style="display:none;">
												<a class="submenu" href="<?= site_url('system_settings/insurances'); ?>">
													<i class="fa fa-shield"></i>
													<span class="text"> <?= lang('insurance_companies'); ?></span>
												</a>
											</li>	
											<li id="system_settings_currencies" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/currencies'); ?>">
													<i class="fa fa-usd"></i>
													<span class="text"> <?= lang('define_currency'); ?></span>
												</a>
											</li>
											<li id="system_settings_tax_rates" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/tax_rates'); ?>">
													<i class="fa fa-bar-chart"></i>
													<span class="text"> <?= lang('tax_rates'); ?></span>
												</a>
											</li>											
											
											<li id="system_settings_subcategories" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/type'); ?>">
													<i class="fa fa-car"></i>
													<span class="text"> <?= lang('model'); ?></span>
												</a>
											</li>
											<li id="system_settings_define_address" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/define_address'); ?>">
													<i class="fa fa-location-arrow"></i>
													<span class="text"> <?= lang('define_address'); ?></span>
												</a>
											</li>
											<li id="system_settings_audit_trail" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/audit_trail'); ?>">
													<i class="fa fa-random"></i>
													<span class="text"> <?= lang('audit_trail'); ?></span>
												</a>
											</li>
											
											<li id="system_settings_backups" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/backups'); ?>">
													<i class="fa fa-download"></i>
													<span class="text"> <?= lang('backup/restore'); ?></span>
												</a>
											</li>
											<li id="system_settings_cbc" class="sub_navigation">
												<a class="submenu" href="<?= site_url('system_settings/cbc'); ?>">
													<i class="fa fa-download"></i>
													<span class="text"> <?= lang('data_export_cbc'); ?></span>
												</a>
											</li>
										</ul>
									</li>
								<?php } ?>
							<?php } ?>
                        </ul>
                    </div>
                    <a href="#" id="main-menu-act" class="full visible-md visible-lg">
                        <i class="fa fa-angle-double-left"></i>
                    </a>
                </div>		
				<div id="content" class="col-lg-10 col-md-10">
					<div class="row">
						<div class="col-sm-12 col-md-12">
							<ul class="breadcrumb">
								<?php foreach ($bc as $b) {
									if ($b['link'] === '#') {
										echo '<li class="active">' . $b['page'] . '</li>';
									} else {
										echo '<li><a href="' . $b['link'] . '">' . $b['page'] . '</a></li>';
									}
								}
								?>
								<li class="right_log hidden-xs">
									<?= lang('your_ip') . ' ' . $ip_address . " <span class='hidden-sm'>( " . lang('last_login_at') . ": " . date($dateFormats['php_ldate'], $this->session->userdata('old_last_login')) . " " . ($this->session->userdata('last_ip') != $ip_address ? lang('ip:') . ' ' . $this->session->userdata('last_ip') : '') . " )</span>" ?>
								</li>
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<?php if ($message) { ?>
								<div class="alert alert-success">
									<button data-dismiss="alert" class="close" type="button">×</button>
									<?= $message; ?>
								</div>
							<?php } ?>
							<?php if ($error) { ?>
								<div class="alert alert-danger">
									<button data-dismiss="alert" class="close" type="button">×</button>
									<?= $error; ?>
								</div>
							<?php } ?>
							<?php if ($warning) { ?>
								<div class="alert alert-warning">
									<button data-dismiss="alert" class="close" type="button">×</button>
									<?= $warning; ?>
								</div>
							<?php } ?>
							<div id="alerts"></div>
						</div>
					</div>
			
	</body>
</html>