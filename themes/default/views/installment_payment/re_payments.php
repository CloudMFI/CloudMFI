<?php

	$penalty_amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->penalty_amount);
	$principle_amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->principle_amount + $payment->owed_principle);
	$interest_amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->interest_amount + $payment->owed_interest);
	$total_service_amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->service_amount + $payment->owed_services);
	$amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->amount);
	$old_balance = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->owed);	
	$other_amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->other_paid + $payment->owed_other_paid);
	$owed_paid = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->owed_paid);
	$balance = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $re_payment->owed);
	$total_payment = $amount + $old_balance;
	$payment_amount = $total_payment - $balance;
	
?>
<div class="modal-dialog modal-md no-modal-header" style="width:50%;">
    <div class="modal-content">
		<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('re_payments'); ?></h4>
        </div>
		<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("Installment_payment/re_payments/" . $loan_id .'/'. $sale_id, $attrib ); ?>
        <div class="modal-body">
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= lang('re_payments') ?></div>
							<?php echo form_open('installment_payment_model', 'id="action-form"'); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("reference", "reference"); ?>
											<?php echo form_input('reference', $reference_sp, 'class="form-control" id="reference" data-bv-notempty="true" style="pointer-events: none;" readonly'); ?>
										</div>									
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("applicant", "applicant"); ?>
											<?php											
											echo form_input('applicant',$payment->family_name .' '. $payment->name, 'class="form-control select" id="applicant" placeholder="' . lang("select") . ' ' . lang("applicant") . '" style="width:100%; pointer-events: none;"');
											?>
										</div>
									</div>
									<!--<div class="col-md-6">
										<div class="form-group">
											<?= lang("document", "document"); ?>
											<input type="file" class="file" data-show-preview=" false" data-show-upload="true" name="document" id="document">
										</div>
									</div>-->							
								</div>							
								<div class="col-md-12">
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("penalty_amount", "penalty_amount"); ?>
											<?php echo form_input('penalty_amount', $this->erp->roundUpMoney($penalty_amount, $sale_item->currency_code), 'class="form-control" id="penalty_amount" style="pointer-events: none;"'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("invoce_no", "invoce_no"); ?>
											<?php echo form_input('invoce_no', $payment->invoce_no , 'class="form-control" id="invoce_no"  '); ?>
										</div>
									</div>
								</div>								
								<div class="col-md-12">	
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("principle", "principle"); ?>
											<?php echo form_input('principle', $this->erp->roundUpMoney($principle_amount, $sale_item->currency_code), 'class="form-control" id="principle"style="pointer-events: none;"'); ?>
										</div>
									</div>
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("interest", "interest"); ?>
											<?php echo form_input('interest',$this->erp->roundUpMoney($interest_amount, $sale_item->currency_code), 'class="form-control" id="interest" style="pointer-events: none;" '); ?>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("old_owed", "owed_paid"); ?>
											<?php echo form_input('owed_paid', $this->erp->roundUpMoney($owed_paid, $sale_item->currency_code), 'class="form-control number_only" id="owed_paid" style="pointer-events: none;"'); ?>
										</div>
									</div>
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("other_payment", "other_paid"); ?>
											<?php echo form_input('other_paid',$this->erp->roundUpMoney($other_amount, $sale_item->currency_code), 'class="form-control number_only" id="other_paid" style="pointer-events: none;"'); ?>
										</div>
									</div>								
								</div>
								<div class="col-md-12">
								
									<?php
									if($last_service_payment) {
										foreach($last_service_payment as $service) {
											$service_amt = $service->owed;
											$service_amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $service_amt);
										?>	
											<input type="hidden" name="owed_services[]" class="owed_services" value="<?= $this->erp->roundUpMoney($service_amount, $sale_item->currency_code)?>" />
											<input type="hidden" name="owed_service_id[]" class="owed_service_id" value="<?= $service->service_id; ?>" />												
										
										<?php 
										}										
									}									
									?>
									
									
									<?php
									if($service_payment) {
										foreach($service_payment as $service) {
											$service_amt = $service->amount + $service->owed;
											$service_amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $service_amt);
										?>
										<div class="col-md-6">
											<div class="form-group">
												<label> <?php echo $service->description; ?> </label>
												<?php echo form_input('services[]', $this->erp->roundUpMoney($service_amount, $sale_item->currency_code), 'class="form-control services" id="services_'. $service->service_id .'"  style="pointer-events: none;"'); ?>
												<input type="hidden" name="service_id[]" class="services_id" value="<?= $service->service_id; ?>" />
											</div>
											
										</div>
										<?php 
										}										
									}									
									?>	
									<input type="hidden" name="total_services" id = "total_services" value="<?php echo $this->erp->roundUpMoney($total_service_amount, $sale_item->currency_code)?> "/>
								</div>
								<div class="col-md-12">	
									<?php
										$total_amount = $total_service_amount + $interest_amount + $principle_amount + $penalty_amount  + $owed - $total_paid_amt;
									?>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("total_payments", "total_payments"); ?> 
											<?php echo form_input('total_payments', $this->erp->formatMoney($total_payment) , 'class="form-control" id="total_payments" style="pointer-events: none;" '); ?>
											<input type="hidden" name="total_service_charge" id="total_service_charge" value="<?= $total_services_amount ?>" />
											<input type="hidden" name="h_total_amount" id="h_total_amount" value="<?=$total_payment;?>" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("payments", "payments"); ?>
											<?php echo form_input('payments', $this->erp->formatDecimal($payment_amount), 'class="form-control number_only" id="payments" style="pointer-events: none;" ');?>
										</div>
									</div>
								</div>
								<div class="col-md-12">	
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("owed_balance", "balance"); ?>
											<?php echo form_input('balance',$this->erp->roundUpMoney($balance, $sale_item->currency_code), 'class="form-control" id="balance" style="pointer-events: none;" style="pointer-events: none;"'); ?>
											<input type="hidden" name="cus_deposit" id="cus_deposit" value="<?= $deposits->deposit_amount; ?>" />
											<input type="hidden" name="cus_depositid" id="cus_depositid" value="<?= $deposits->id; ?>" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("re_payments", "re_payments"); ?>
											<?php echo form_input('re_payments', ' ', 'class="form-control number_only" id="re_payments" required="required"');?>
										</div>
									</div>
								</div>								
								<div class="col-md-12">	
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("payment_date", "pay_date"); ?>
											<?php echo form_input('pay_date', $this->erp->hrld(date('Y-m-d H:m')), 'class="form-control datetime" id="pay_date" data-bv-notempty="true"'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("new_owed_balance", "new_owed_balance");?>
											<?php echo form_input('new_owed_balance', ' ', 'class="form-control number_only" id="new_owed_balance" style="pointer-events: none;"');?>
										</div>
									</div>
								</div>
								
								<div class="col-md-12">		
									<div class="col-md-6">
										<div class="form-group" id ="payment_method">
											<?= lang("payment_method", "pay_method"); ?>
											<?php
											$pay_method[""] = "";
											$pay_method["cash"] = "Cash";
											$pay_method["wing"] = "Wing";
											$pay_method["Visa"] = "Visa Card";
											echo form_dropdown('pay_method', $pay_method,  $payment->paid_by, 'class="form-control select" id="pay_method" placeholder="' . lang("select") . ' ' . lang("pay_method") . '" style="width:100%" data-bv-notempty="true"');
											?>
										</div>
									</div>									
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("cash_in", "bank_account"); ?>
											<?php
												$all_bank[(isset($_POST['bank_account']) ? $_POST['bank_account'] : '')] = (isset($_POST['bank_account']) ? $_POST['bank_account'] : '');
												if(array($banks)) {
													foreach($banks as $bank) {
														$all_bank[$bank->accountcode] = $bank->accountname;
													}
												}
												echo form_dropdown('bank_account', $all_bank, (isset($_POST['bank_account']) ? $_POST['bank_account'] : $payment->bank_acc_code), 'id="bank_account" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("bank_account") . '" style="width:100%;" required="required"');
											?>
										</div>
									</div>
								</div>
								<!------------------------------------->								
							</div>
					</div>
				</div>                
			</div>
		</div>
		<div class="modal-footer">
            <?php echo form_submit('re_payment', lang('submit'), 'class="btn btn-primary" id="re_payment"'); ?>
        </div>
	</div>
	<?php echo form_close(); ?>
</div>
<?= $modal_js ?>
<script type="text/javascript">
	$(window).load(function() {
		$("#pay_method").trigger('change');		
	});
	

	$(document).ready(function () {		
		$('#re_payments').on('keyup', function() {
			var re_payment = formatDecimal(($(this).val()).replace(',', ''));
			var owed_balances = $('#balance').val();
			var owed_balance = formatDecimal(owed_balances.split(',').join(''));
			
			var balance = owed_balance - re_payment;
			$('#new_owed_balance').val(formatMoney(balance));
			if(re_payment > owed_balance){
					$('#re_payments').val(formatDecimal(owed_balance));
					$('#new_owed_balance').val(0);
				}
			//alert(re_payment);
		});
	});
	
</script>

