<?php //echo $this->erp->print_arrays($capital_info) ?>
<link href="https://fonts.googleapis.com/css?family=Moul" rel="stylesheet"> 
<style type="text/css">
	
	hr.style_dot{
	border-top: 1px dotted #8c8b8b;
	}
	hr.style_out{
	border-top: 1px solid #8c8b8b;
	}
	hr.style1{
	border-top: 2px solid #8c8b8b;
	}
	hr{
		margin-bottom: 1px;
		margin-top: 0px;
	}
	.payment_voucher{
		text-align:center;
		vertical-align: text-top;
		position:relative;
		top:-10px;
		font-style: italic;
	}
	fieldset{
		border-radius:5px;
		height:auto;
		min-height:110px;
	}
	fieldset_{min-width:0;padding:0;margin:0;border:0}
	legend_{display:block;width:100%;padding:0;margin-bottom:20px;font-size:21px;line-height:inherit;color:#333;border:0;border-bottom:1px solid #e5e5e5}
	.payto_refernce table tr td{
		padding-left:5px;
		font-size:12px;
	}
	.signature{
		width:300px;
		text-align:left;
		margin:0 auto;
		font-size:12px;
		margin-top:15px;
	}
	.signature span{
		font-size:8px;
	}
	.kh_m{
		font-family:Moul;
		font-size:14px;
	}
		div[size="A4"] {  
	  width: 21cm;
	  height: 29.7cm; 
	  margin: 30px auto;
	}
	@media print{
		#paper_print{
			width: 210mm;
			height: 297mm;
		  }
		.col-sm-2{float:left; width: 16.6667%;}
		.col-sm-8{float:left; width:66.6667%;}	
		.col-sm-6{float:left; width:50%;}
		.col-sm-4{float:left;width: 33.3333%;}
		.col-sm-2{float:left;}
		div[size="A4"] {margin: 0px auto;}
		#signature_box{position:relative; left:-50px;}
	}
</style>
<div size="A4">
	<div id="paper_print">
		<div class="modal-content " >
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="padding-right:10px;"><i class="fa fa-2x">&times;</i>
			</button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:10px; margin-top:10px;" onclick="window.print();">
				<i class="fa fa-print"></i> <?= lang('print'); ?>
			</button><br/>
			<?php 
				$i=0;
				for($i=0;$i<2;$i++){
			?>
			<div class="container">
				<!--<h4 class="modal-title" id="myModalLabel"><?php echo lang('receipt_vocher'); ?></h4>-->
				<div class="row">
					<div class="col-sm-2">
						<div id="logo">
							<span> <?php if ($Settings->logo2) {
							echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" style="margin-bottom:10px; width:120px;" />';
							} ?> </span> 
						</div>
					</div>
					<div class="col-sm-8">
						<div class="form-group">
							<div style="text-align:center; font-size:12px;" ><br/>
								<span class="kh_m"><b> <?php echo $setting->site_name ?> </b></span><br/>
								<span><b><?=lang('address')?></b> : <?php echo $branch_info->br_address; ?></span><br/>
								<span><b><?=lang('tel')?></b> : <?php echo $branch_info->phone; ?></span>&nbsp;,
								<span><b><?=lang('e_mail')?></b> : <?php echo $branch_info->email; ?></span><br/>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="col-sm-6">
							<hr class="style_out">
							<hr class="style1">
							<hr class="style_out">
						</div>
						<div class="col-sm-4 payment_voucher">
							<?= lang("receipt_vocher") ?>
						</div>
						<div class="col-sm-2">
							<hr class="style_out">
							<hr class="style1">
							<hr class="style_out">
						</div>
					</div>
				</div>
			</div>
				<div class="container">           
						<div class="row" style="margin:0 auto;">
							<div class="col-sm-6 col-lg-12">
								 <form>
								  <fieldset>
									<legend><strong><?= lang('capital') ?></strong></legend>
									<div class="payto_refernce">
										<table>
											<tr>
												<td><?=lang('reference')?> </td>
												<td>:</td>
												<td><b> <?php echo (($capital->reference) ? $capital->reference : '') ?></b></td>
											</tr>
											<tr>
												<td><?=lang('date')?> </td>
												<td>:</td>
												<td><b><?php echo (($capital->date) ? $this->erp->hrsd($capital->date) : '') ?> </b></td>
											</tr>
											<tr>
												<td><?=lang('shareholder')?> </td>
												<td>:</td>
												<td><b><?php echo (($capital_info->name) ? $capital_info->name : 'N/A') ?></b></td>
											</tr>
											<tr>
												<td><?=lang('branch')?> </td>
												<td>:</td>
												<td><b><?php echo $capital_info->cname; ?></b></td>
											</tr>
											<tr>
												<td><?=lang('amount')?> </td>
												<td>:</td>
												<td><b><?php echo $this->erp->convertCurrency($capital->currency_code, $defualt_currency->default_currency, $capital->amount) ?> <?php echo $capital_info->c_name; ?></b></td>
											</tr>
											<tr>
												<td><?=lang('bank_account')?> </td>
												<td>:</td>
												<td><b><?php echo $capital_info->accountname ?></b></td>
											</tr>
											
										</table>
									</div>
								  </fieldset>
								</form> 
							</div>
							
						</div>
						
						<div class="row" style="margin:0 auto;">
							<div class="col-md-12">
								<div class="col-md-6 col-lg-12">
									 <h6><?=lang('description')?> :</h6>
									 <div class="payto_refernce">
										 <table>
											<tr>
												<td><?php echo (($capital->note) ? $capital->note : 'N/A') ?> </td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div><br/>
						<div class="row" style="margin:0 auto;" id="signature_box">
								<div class="col-sm-6" style="text-align:center;">
									<h6><b><?=lang('receiver')?></b></h6>
									<div class="signature" style="text-align:center;">
										<span>..........................................................................................................</span><br/>
										<?=lang('name')?>&nbsp; : <span>...............................................................</span><br/>
										<?=lang('receipt_date')?>&nbsp;&nbsp;&nbsp; : <span>..................../...................../...................</span>
									</div>
								</div>
								<div class="col-sm-6" style="text-align:center;">
									<h6><b><?=lang('cashier')?></b></h6>
									<div class="signature" style="text-align:center;">
										<span>..........................................................................................................</span><br/>
										<?=lang('name')?>&nbsp; : <span>...............................................................</span><br/>
										<?=lang('receipt_date')?>&nbsp;&nbsp;&nbsp; : <span>..................../...................../...................</span>
									</div>
								</div>
						</div>
				</div><br/>
				<?php if($i<1){?>
					<hr class="style_dot"><br/>
				<?php } ?>
			<?php }?>
			
		</div>	
	</div>
</div>
<?= $modal_js ?>
