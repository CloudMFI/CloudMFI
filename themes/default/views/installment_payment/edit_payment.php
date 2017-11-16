<?php

	
	$penalty_amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->penalty_amount);
	$principle_amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->principle_amount + $payment->owed_principle);
	$total_interest = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->total_interest);
	$interest_amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->interest_amount + $payment->owed_interest);
	$total_service_amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->service_amount + $payment->owed_services);
	$amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->amount);
	$balance = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->owed);
	$other_amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->other_paid + $payment->owed_other_paid);
	$owed_paid = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->owed_paid);
	$total_payment = $amount + $balance;
	//$this->erp->print_arrays($payment);
?>
<div class="modal-dialog modal-lg" style="width:50%;">
    <div class="modal-content">
		<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('edit_payment'); ?></h4>
        </div>
		<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("Installment_payment/edit_payments/" . $loan_id .'/'. $sale_id, $attrib ); ?>
        <div class="modal-body">
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= lang('edit_payment') ?></div>
							<?php echo form_open('down_payment', 'id="action-form"'); ?>
							<div class="row">
								<div class="col-md-12">
									<!--<div class="col-md-6">
										<div class="form-group">
											<?= lang("customer_type", "financial_product"); ?>
											<?php
											$fin_pro[""] = "";
											foreach ($customers as $financial_product) {
												$fin_pro[$financial_product->id] = $financial_product->customer_group_name;
											}
											echo form_dropdown('financial_product', $fin_pro, $sale->customer_id, 'id="financial_product" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("finacal_product") . '" class="form-control input-tip select" style="width:100%; pointer-events: none;" disable ="true"');
											?>
										</div>
									</div>-->
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("reference", "reference"); ?>
											<?php echo form_input('reference', $payment->reference_no , 'class="form-control" id="reference" data-bv-notempty="true" style="pointer-events: none;" readonly'); ?>
										</div>									
									</div>
									<div class="col-md-6">
										<div class="form-group" style="display:none;">
											<?= lang("dealer", "dealer"); ?>
											<?php
											$bl[""] = "";
											foreach ($billers as $dealer) {
												$bl[$dealer->id] = $dealer->company != '-' ? $dealer->company : $dealer->name;
											}
											echo form_dropdown('dealer', $bl, ($sale->biller_id ? $sale->biller_id : $Settings->default_biller), 'id="dealer" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("dealer") . '" class="form-control input-tip select" style="width:100%; pointer-events: none;"');
											?>
										</div>
										<div class="form-group">
											<?= lang("document", "document"); ?>
											<input type="file" class="file" data-show-preview=" false" data-show-upload="true" name="document" id="document">
										</div>
									</div>								
								</div>
								<div class="col-md-12">
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("applicant", "applicant"); ?>
											<?php											
											echo form_input('applicant',$payment->family_name .' '. $payment->name, 'class="form-control select" id="applicant" placeholder="' . lang("select") . ' ' . lang("applicant") . '" style="width:100%; pointer-events: none;"');
											?>
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
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("penalty_days", "penalty_days"); ?>
											<?php echo form_input('penalty_days', $overdue_days, 'class="form-control" id="penalty_days" style="pointer-events: none;" '); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("penalty_amount", "penalty_amount"); ?>
											<?php echo form_input('penalty_amount', $this->erp->roundUpMoney($penalty_amount, $sale_item->currency_code), 'class="form-control number_only" id="penalty_amount" '); ?>
										</div>
									</div>																	
								</div>
								<div class="col-md-12">
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("principle", "principle"); ?>
											<?php echo form_input('principle', $this->erp->roundUpMoney($principle_amount, $sale_item->currency_code), 'class="form-control" id="principle" style="pointer-events: none;"'); ?>
										</div>
									</div>
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("interest", "interest"); ?>
											<?php echo form_input('interest',$this->erp->roundUpMoney($total_interest, $sale_item->currency_code), 'class="form-control number_only" id="interest" '); ?>
										</div>
									</div>
									<!--<div class="col-md-6" >
										<div class="form-group">
											<?= lang("total_servives", "total_servives"); ?>
											<?php echo form_input('total_servives',$this->erp->roundUpMoney($total_service_amount, $sale_item->currency_code), 'class="form-control" id="total_servives" '); ?>
										</div>
									</div>-->								
								</div>
								<?php if ($setting->interest_discount == "enable"){ ?>
								<?php $discount = $payment->interest_discount * 100 ;?>
								<div class="col-md-12">
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("interest_discount_rate", "interest_discount_rate"); ?>
											<?php echo form_input('interest_discount_rate', $discount .'%', 'class="form-control" id="interest_discount_rate"'); ?>
										</div>
										<input type="hidden" name="discount_rate" class="discount_rate" id="discount_rate" value="<?= $payment->interest_discount; ?>"/>
									</div>
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("interest_payment", "interest_payment"); ?>
											<?php echo form_input('interest_payment',$this->erp->roundUpMoney($interest_amount, $sale_item->currency_code), 'class="form-control number_only" id="interest_payment" '); ?>
										</div>
									</div>
								</div>
								<?php } ?>
								<div class="col-md-12">
									<?php
									if($service_payment) {
										foreach($service_payment as $service) {
											$service_amt = $service->amount + $service->owed;
											$service_amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $service_amt);
										?>
										<div class="col-md-6">
											<div class="form-group">
												<label> <?php echo $service->description; ?> </label>
												<?php echo form_input('services[]', $this->erp->roundUpMoney($service_amount, $sale_item->currency_code), 'class="form-control services" id="services_'. $service->service_id .'"  '); ?>
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
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("old_owed", "owed_paid"); ?>
											<?php echo form_input('owed_paid', $this->erp->roundUpMoney($owed_paid, $sale_item->currency_code), 'class="form-control number_only" id="owed_paid" style="pointer-events: none;"'); ?>
										</div>
									</div>
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("other_payment", "other_paid"); ?>
											<?php echo form_input('other_paid',$this->erp->roundUpMoney($other_amount, $sale_item->currency_code), 'class="form-control number_only" id="other_paid"'); ?>
										</div>
									</div>								
								</div>
								<div class="col-md-12">
									<?php
										$total_amount = $total_service_amount + $interest_amount + $principle_amount + $penalty_amount  + $owed - $total_paid_amt;
									?>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("total_payments", "total_payments"); ?> 
											<?php echo form_input('total_payments', $this->erp->formatMoney($total_payment) , 'class="form-control" id="total_payments" style="pointer-events: none;" '); ?>
											<input type="hidden" name="h_total_amount" id="h_total_amount" value="<?=$total_payment;?>" />
										</div>
									</div>								
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("payments", "payments"); ?>
											<?php echo form_input('payments', $this->erp->formatDecimal($amount), 'class="form-control number_only" id="payments" required="required"');?>
										</div>
									</div>
								</div>								
								<div class="col-md-12">	
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("payment_date", "pay_date"); ?>
											<?php echo form_input('pay_date', $this->erp->hrld(date('Y-m-d H:m')), 'class="form-control datetime" id="pay_date" '); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("owed_balance", "balance"); ?>
											<?php echo form_input('balance',$this->erp->roundUpMoney($balance, $sale_item->currency_code), 'class="form-control" id="balance" style="pointer-events: none;"'); ?>
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
            <?php echo form_submit('edit_payment', lang('submit'), 'class="btn btn-primary" id="edit_payment"'); ?>
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
			$('#interest_discount_rate').on('keyup , change', function() {	
				var interest_discount_rate = $(this).val().toLowerCase();
				var interest = $('#interest').val()? ($('#interest').val()) : 0;
				var interest_amt = interest ? formatDecimal(interest.split(',').join('')) : 0;
				var interest_rate = 0;
				if(interest_discount_rate.search('%') > 0) {
					interest_discount_rate = interest_discount_rate.replace('%', '');
					interest_rate = (interest_discount_rate/100);
				}
				var discount_amt = interest_amt * interest_rate;
				var interest_payment = interest_amt - discount_amt;				
				$('#interest_payment').val(interest_payment);				
				var discount = '<?= $setting->interest_discount; ?>';			
				var penalty = $('#penalty_amount').val()? ($('#penalty_amount').val()) : 0;
				var penalty_amt = formatDecimal(penalty.split(',').join(''));
				var principle = $('#principle').val()? ($('#principle').val()) : 0;
				var principle_amt = formatDecimal(principle.split(',').join(''));
				
				var owed_paid = $('#owed_paid').val()? ($('#owed_paid').val()) : 0;
				var owed_paid_amt = formatDecimal(owed_paid.split(',').join(''));
				var other_paid = $('#other_paid').val()? ($('#other_paid').val()) : 0;
				var other_paid_amt = formatDecimal(other_paid.split(',').join(''));			
				
				var total_services = 0;
				$('.services').each(function() {
					total_services += formatDecimal(($(this).val()).replace(',', ''));
				});			
				var total_payment = penalty_amt + principle_amt + interest_payment + total_services + owed_paid_amt + other_paid_amt;
				
				$('#discount_rate').val(interest_rate);
				$('#total_payments').val(formatMoney(total_payment));
				$('#h_total_amount').val(total_payment);
				$('#total_services').val(total_services);
			});
		});
		
</script>


<script type="text/javascript">
	
	
	$(document).ready(function () {		
		$('#penalty_amount, #principle, #interest, #owed_paid, #other_paid, .services, #payments, #interest_payment').on('keyup , change', function() {			
			var discount = '<?= $setting->interest_discount; ?>';	
			var penalty = $('#penalty_amount').val()? ($('#penalty_amount').val()) : 0;
			var penalty_amt = penalty ? formatDecimal(penalty.split(',').join('')) : 0;
			
			var principle = $('#principle').val()? ($('#principle').val()) : 0;
			var principle_amt = principle ? formatDecimal(principle.split(',').join('')) : 0;
			
			var interest = $('#interest').val()? ($('#interest').val()) : 0;
			var interest_amt = interest ? formatDecimal(interest.split(',').join('')) : 0;
			var interest_pay = $('#interest_payment').val()? ($('#interest_payment').val()) : 0;			
			var interest_payment =  interest_pay ? formatDecimal(interest_pay.split(',').join('')) : 0;
			var interest_amount = 0;
			if(discount == "enable"){
				interest_amount = interest_payment;
			}if(discount == "disable"){
				interest_amount = interest_amt;
			}
			
			
			
			var owed_paid = $('#owed_paid').val()? ($('#owed_paid').val()) : 0;
			var owed_paid_amt = owed_paid ? formatDecimal(owed_paid.split(',').join('')) : 0;
			
			var other_paid = $('#other_paid').val()? ($('#other_paid').val()) : 0;
			var other_paid_amt = other_paid ? formatDecimal(other_paid.split(',').join('')) : 0;
			var total_service = 0;
			var total_services = 0;
			$('.services').each(function() {
				total_service = formatDecimal(($(this).val()).replace(',', ''));
				total_services += total_service ? total_service : 0;
			});
			
			var total_payment = penalty_amt + principle_amt + interest_amount +  total_services + owed_paid_amt + other_paid_amt;
			$('#total_payments').val(formatMoney(total_payment));
			$('#h_total_amount').val(total_payment);
			$('#total_services').val(total_services);
			
			var payment = $('#payments').val();
				var balance = total_payment - payment;
				$('#balance').val(formatMoney(balance));
				if(payment > total_payment){
					$('#payments').val(formatMoney(total_payment));
					$('#balance').val(0);
				}
		});
	});
	
	/*$(document).ready(function (){		
		$('#other_paid').keyup(function(){			
			var other_paid = $('#other_paid').val()? parseFloat($('#other_paid').val()) : 0;
			var amount = $('#h_total_amount').val()? ($('#h_total_amount').val()) : 0;
			var amounts = amount.replace(",", "");
			var total_amount = parseFloat(other_paid) + parseFloat(amounts);
			$('#total_payments').val(formatMoney(total_amount));
			$('#balance').val(formatMoney(total_amount));
		});		
	});	
	$(document).ready(function() {
		$('#payments').on('keyup', function() {
			var payment = $(this).val();
			var total_payment = $('#total_payments').val();
			var total_payments = total_payment.replace(",", "");
			var total = parseFloat(total_payments);
			var balance = total - payment;
			$('#balance').val(balance);	
			if(payment > total){
				$('#payments').val(total);
				$('#balance').val(0);
			}
		});
	});*/
	
	
</script>

