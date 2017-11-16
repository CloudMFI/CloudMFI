<?php //echo $this->erp->print_arrays($receipt_voucher) ?>
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
	legend_{display:block;width:100%;padding:0;margin-bottom:25px;font-size:21px;line-height:inherit;color:#333;border:0;border-bottom:1px solid #e5e5e5}
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
	div[size="A4"] {  
	  width: 21cm;
	  height: 29.7cm; 
	  margin: 25px auto;
	}
	/*@media print{
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
	}*/
    @media print{
        .modal-dialog{
            width: 95% !important;
        }
        .modal-content{
            border: none !important;
        }
    }
</style>
	<div class="modal-dialog modal-lg no-modal-header">
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
							echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" style="margin-bottom:10px;" />';
							} ?> </span>
						</div>
					</div>
					<div class="col-sm-8">
						<div class="form-group">
							<div style="text-align:center; font-size:12px;" ><br/>
								<span class="kh_m" style="font-size:14px;"><b> <?php echo $setting->site_name ?> </b></span><br/>
								<span><b><?=lang('address')?></b> : <?php echo $branch_info->name; ?></span><br/>
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
							<?= lang("payment_voucher") ?>
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
							<div class="col-sm-6">
								 <form>
								  <fieldset>
									<legend><strong><?= lang('pay_to') ?></strong></legend>
									<div class="payto_refernce">
										<table>
											<tr>
												<td><?=lang('name')?> </td>
												<td>:</td>
												<td><b> <?php echo $receipt_voucher->family_name_other.' '.$receipt_voucher->name_other; ?></b></td>
											</tr>
											<tr>
												<td><?=lang('address')?> </td>
												<td>:</td>
												<td><b><?php echo $receipt_voucher->house_no; ?> </b></td>
											</tr>
											<tr>
												<td><?=lang('phone')?> </td>
												<td>:</td>
												<td><b><?php echo $receipt_voucher->phone1; ?> <?php if(($receipt_voucher->phone2)){echo ' / '.$receipt_voucher->phone2;} ?></b></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td></td>
											</tr>
										</table>
									</div>
								  </fieldset>
								</form> 
							</div>
							<div class="col-sm-6">
								<form>
								  <fieldset>
									<legend><strong><?= lang('reference') ?></strong></legend>
									<div class="payto_refernce">	
										<table class="payto_refernce">
											<tr>
												<td><?=lang('receipt_no')?> </td>
												<td>:</td>
												<td><b><?php echo $receipt_voucher->reference_no; ?> </b></td>
											</tr>
											<tr>
												<td><?=lang('invoice_no')?> </td>
												<td>:</td>
												<td><b><b><?php echo $receipt_voucher->loan_id; ?> </b> </b></td>
											</tr>
											<tr>
												<td><?=lang('receipt_date')?> </td>
												<td>:</td>
												<td><b> <?php echo $this->erp->hrsd($receipt_voucher->date); ?> </b></td>
											</tr>
											<tr>
												<td><?=lang('payment_type')?> </td>
												<td>:</td>
												<td><b> <?php echo $receipt_voucher->paid_by; ?></b></td>
											</tr>
										</table>
									</div>
								  </fieldset>
								</form> 
							</div>
						</div>
						<div class="row" style="margin:0 auto;">
							<div class="col-md-12">
								<div class="col-md-8">
									 <h6><?=lang('description')?> :</h6>
									 <div class="payto_refernce">
										 <table>
											<tr style="height:25px;">
												<td><?=lang('old_owed')?> </td>
												<td>:</td>
												<td> &nbsp; &nbsp;<b> <?php echo $this->erp->formatMoney($this->erp->convertCurrency($currency->currency_code,$setting->default_currency,$receipt_voucher->owed_paid)); ?> </b></td>
											</tr>
											<tr style="height:25px;">
												<td><?=lang('principle')?> </td>
												<td>:</td>
												<td> &nbsp; &nbsp; <b><?php echo $this->erp->formatMoney($this->erp->convertCurrency($currency->currency_code,$setting->default_currency,$receipt_voucher->principle_amount)); ?> </b></td>
											</tr>
											<tr style="height:25px;">
												<td><?=lang('penalty_amount')?> </td>
												<td>:</td>
												<td> &nbsp; &nbsp; <b> <?php echo $this->erp->formatMoney($this->erp->convertCurrency($currency->currency_code,$setting->default_currency,$receipt_voucher->penalty_amount)); ?></b></td>
											</tr>
											
											<?php if ($receipt_voucher->interest_discount <> 0){ ?>
											<tr style="height:25px;">
												<td><?=lang('total_interest')?> </td>
												<td>:</td>
												<td> &nbsp; &nbsp; <b> <?php echo $this->erp->formatMoney($this->erp->convertCurrency($currency->currency_code,$setting->default_currency,$receipt_voucher->total_interest)); ?></b></td>
											</tr>
											<tr style="height:25px;">
												<td><?=lang('interest_discount')?> </td>
												<td>:</td>
												<td> &nbsp; &nbsp; <b> <?php echo ($receipt_voucher->interest_discount * 100) ; ?> %</b></td>
											</tr>
											<?php } ?>											
											<tr style="height:25px;">
												<td><?=lang('interest')?> </td>
												<td>:</td>
												<td> &nbsp; &nbsp; <b><?php echo $this->erp->formatMoney($this->erp->convertCurrency($currency->currency_code,$setting->default_currency,$receipt_voucher->interest_amount)); ?> </b></td>
											</tr>
											<?php if($service_payment) {
												foreach($service_payment as $service) {
											?>
											<tr>
												<td> <?php echo $service->description; ?> </td>
												<td>:</td>
												<td> &nbsp; &nbsp; <b><?php echo $this->erp->formatMoney($this->erp->convertCurrency($currency->currency_code,$setting->default_currency,$service->amount)); ?> </b></td>
											</tr>
											<?php
												}
											}
											?>
											<tr style="height:25px;">
												<td><?=lang('other_payment')?> </td>
												<td>:</td>
												<td> &nbsp; &nbsp; <b><?php echo $this->erp->formatMoney($this->erp->convertCurrency($currency->currency_code,$setting->default_currency,$receipt_voucher->other_payment)); ?> </b></td>
											</tr>
											<tr style="height:25px;">
												<td><?=lang('paid_amount')?> </td>
												<td>:</td>
												<td> &nbsp; &nbsp; <b><?php echo $this->erp->formatMoney($this->erp->convertCurrency($currency->currency_code,$setting->default_currency,$receipt_voucher->amount)); ?> </b></td>
											</tr>
											<tr style="height:25px;">
												<td><?=lang('owed_balance')?> </td>
												<td>:</td>
												<td> &nbsp; &nbsp; <b> <?php echo $this->erp->formatMoney($this->erp->convertCurrency($currency->currency_code,$setting->default_currency,$receipt_voucher->owed)); ?></b></td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div><br/>
						<div class="row" style="margin:0 auto;">
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
<?= $modal_js ?>
