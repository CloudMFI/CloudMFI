<?php
	//$this->erp->print_arrays($loan_owed->owed);
	$overdue_days = 0;
	$penalty_amount = 0;
	$total_amount = 0;
	$installment_amount = 0;
	$interest_amount = 0;
	$installment_amt = 0;
	$total_paid_amt = 0;
	$ovdays = 0;
	$owed = 0;
	$ovamounts = 0;
	$loans_id = '';
	$principle_amount = 0;
	$penalty_days = $setting->penalty_days;
	$penalty_rate = $setting->penalty_amount;
	
	$ldata = array();
	
	
	$loans_num = count($loans);
	
	$countrows = count($count);
	$countrow  = count($count) /2;
	
	$i = 0;
	$curency_name = $getLoan->name ? $getLoan->name : '';
	$deposit = $deposits->deposit_amount ? $deposits->deposit_amount :'0' ;
	
	foreach($loans as $loan) {
		
		if($i ==0) {
			$loans_id = $loan->id;
		}else {
			$loans_id .= '_'.$loan->id;
		}
		if($loan->paid_amount > 0) {
			$ovdays = 0;
			$ovamounts = 0;
		}else {
			$dateline = date('Y-m-d', strtotime($loan->dateline));
			$final_dateline = date('Y-m-d', strtotime("+".$penalty_days." days", strtotime($dateline)));
			$current_date = date('Y-m-d');
			if($final_dateline < $current_date) {
				$ovdays = (strtotime($current_date) - strtotime($dateline))/(3600 * 24);
				$ovamounts = $ovdays * $penalty_rate;
				$overdue_days += $ovdays;
			}else {
				$ovdays = 0;
				$ovamounts = 0;
			}
		}
		
		foreach($services as $service) {
			
			if($service->service_paid != 1) {
					$service->amount = $this->erp->convertCurrency($getLoan->code, $setting->default_currency, $service->amount);
					$service->total_charge += (($service->service_paid == 2 && $loan->period <= $countrow)? ($service->amount):(($service->service_paid == 3)? ($service->amount ):0) ?:(($service->service_paid == 4 && $loan->period <= $countrow )? (($service->amount) / $countrow):0));
				
			}else {
				$service->total_charge = $service->amount;
			}
		}
		
		$installment_amount += $loan->payment;
		$interest_amount += $loan->interest;
		$principle_amount += $loan->principle;
		$installment_amt += ($loan->payment - $loan->paid_amount);
		if($loan->overdue_amount > 0) {
			$installment_amount += $loan->overdue_amount;
			$installment_amt += $loan->overdue_amount;
		}
		$owed += $loan->owed;
		$total_paid_amt += $loan->paid_amount;
		$ldata[] = array(
							'installment' => $loan->payment,
							'ovdays' => $ovdays,
							'ovamounts' => $ovamounts,
							'owed' => $loan->owed,
							'paid_amount' => $loan->paid_amount,
						);
		$i++;
	}
	//$this->erp->print_arrays($deposits);
	$penalty_amount = $overdue_days * $penalty_rate;
	$penalty_amount = $this->erp->convertCurrency($getLoan->code, $setting->default_currency, $penalty_amount);
	//$total_amount += $penalty_amount + $installment_amt;
?>
<div class="modal-dialog modal-md no-modal-header" style="width:50%;">
    <div class="modal-content">
		<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('add_payment'); ?></h4>
        </div>
		<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("Installment_payment/add_payment/" . $sale->id .'/'. $loans_id, $attrib .'/'. $sale->quote_id ); ?>
        <div class="modal-body">
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= lang('add_payment') ?></div>
							<?php echo form_open('down_payment', 'id="action-form"'); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-6">
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
											$applicant = "";
											foreach($customers as $customer) {
												$applicant[$customer->id] = $customer->family_name ." ". $customer->name;
											}
											echo form_dropdown('applicant', $applicant, $sale->customer_id, 'class="form-control select" id="applicant" placeholder="' . lang("select") . ' ' . lang("applicant") . '" style="width:100%; pointer-events: none;" data-bv-notempty="true"');
											?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("penalty_days", "penalty_days"); ?>
											<?php echo form_input('penalty_days', $overdue_days, 'class="form-control" id="penalty_days" style="pointer-events: none;" '); ?>
										</div>
									</div>								
								</div>
								
								<div class="col-md-12">
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("penalty_amount", "penalty_amount"); ?>
											<?php echo form_input('penalty_amount', $this->erp->roundUpMoney($penalty_amount,$getLoan->code), 'class="form-control" id="penalty_amount" '); ?>
										</div>
									</div>
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("principle", "principle"); ?>
											<?php echo form_input('principle', $this->erp->roundUpMoney($principle_amount,$getLoan->code), 'class="form-control" id="principle"'); ?>
										</div>
									</div>								
								</div>
								
								<div class="col-md-12">
									<?php
									$total_services_amount = 0;
									$total_service_amount = 0;
									if($services) {
										foreach($services as $service) {
											if($service->service_paid != 1) {
											$total_services_amount += $service->total_charge;
										?>
										<div class="col-md-6">
											<div class="form-group">
												<?= lang($service->description, "services[]"); ?>
												<?php echo form_input('services[]', $this->erp->roundUpMoney($service->total_charge,$getLoan->code), 'class="form-control services" id="services" style="pointer-events: none;" '); ?>
												<input type="hidden" name="service_id[]" class="services_id" value="<?= $service->id; ?>" />
											</div>
										</div>
										<?php 
											}
										}
									}
									$total_service_amount += $total_services_amount;
									?>
								
								</div>
								
								<div class="col-md-12">
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("interest", "interest"); ?>
											<?php echo form_input('interest', $this->erp->roundUpMoney($interest_amount,$getLoan->code), 'class="form-control" id="interest"'); ?>
										</div>
									</div>
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("total_servives", "total_servives"); ?>
											<?php echo form_input('total_servives',$this->erp->roundUpMoney($total_service_amount,$getLoan->code), 'class="form-control" id="total_servives" style="pointer-events: none;"'); ?>
										</div>
									</div>
								
								</div>
								<div class="col-md-12">										
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("owed_paid", "owed_paid"); ?>
											<?php echo form_input('owed_paid', $this->erp->roundUpMoney($loan_owed->owed,$getLoan->code), 'class="form-control number_only" id="owed_paid"'); ?>
										</div>
									</div>	
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("other_payment", "other_paid"); ?>
											<?php echo form_input('other_paid',' ', 'class="form-control number_only" id="other_paid"'); ?>
										</div>
									</div>								
								</div>
								<div class="col-md-12">
									
									<?php
										$total_amount = $total_service_amount + $interest_amount + $principle_amount + $penalty_amount  + $loan_owed->owed - $total_paid_amt;
									?>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("total_payments", "total_payments"); ?> 
											<?php echo form_input('total_payments', $this->erp->roundUpMoney($total_amount,$getLoan->code), 'class="form-control" id="total_payments" style="pointer-events: none;" '); ?>
											<input type="hidden" name="total_service_charge" id="total_service_charge" value="<?= $total_services_amount ?>" />
											<input type="hidden" name="h_total_amount" id="h_total_amount" value="<?=$this->erp->roundUpMoney($total_amount,$getLoan->code)?>" />
										</div>
									</div>	
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("payments", "payments"); ?>
											<?php echo form_input('payments', '', 'class="form-control number_only" id="payments" data-bv-notempty="true"'); ?>
										</div>
									</div>									
								</div>
								
								<div class="col-md-12">										
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("payment_date", "pay_date"); ?>
											<?php echo form_input('pay_date', $this->erp->hrld(date('Y-m-d H:m')), 'class="form-control datetime" id="pay_date" data-bv-notempty="true"'); ?>
											<?php foreach($ldata as $data) { ?>
													<input type="hidden" name="installment[]" class="installment" value="<?=$data['installment']?>" />
													<input type="hidden" name="ovdays[]" class="ovdays" value="<?=$data['ovdays']?>" />
													<input type="hidden" name="ovamounts[]" class="ovamounts" value="<?=$data['ovamounts']?>" />
													<input type="hidden" name="owed[]" class="owed" value="<?=$data['owed']?>" />
													<input type="hidden" name="paid_amount[]" class="paid_amount" value="<?=$data['paid_amount']?>" />
											<?php } ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("balance", "balance"); ?>
											<?php echo form_input('balance', '', 'class="form-control" id="balance" style="pointer-events: none;"'); ?>
											<input type="hidden" name="cus_deposit" id="cus_deposit" value="<?= $deposits->deposit_amount; ?>" />
											<input type="hidden" name="cus_depositid" id="cus_depositid" value="<?= $deposits->id; ?>" />
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
											echo form_dropdown('pay_method', $pay_method, '', 'class="form-control select" id="pay_method" placeholder="' . lang("select") . ' ' . lang("pay_method") . '" style="width:100%" data-bv-notempty="true"');
											?>
										</div>
									</div>									
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("bank_account", "bank_account"); ?>
											<?php
												$all_bank[(isset($_POST['bank_account']) ? $_POST['bank_account'] : '')] = (isset($_POST['bank_account']) ? $_POST['bank_account'] : '');
												if(array($banks)) {
													foreach($banks as $bank) {
														$all_bank[$bank->accountcode] = $bank->accountname;
													}
												}
												echo form_dropdown('bank_account', $all_bank, (isset($_POST['bank_account']) ? $_POST['bank_account'] : ''), 'id="bank_account" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("bank_account") . '" style="width:100%;" required="required"');
											?>
										</div>
									</div>								
								</div>
								<!------------------------------------->
								
							</div>
							
							<div class="col-md-6" style="display:none;">
								<div class="form-group">
									<?= lang("installment_amount", "installment_amount"); ?>
									<?php echo form_input('installment_amount', $this->erp->formatMoney($installment_amount), 'class="form-control" id="installment_amount" style="pointer-events: none;" '); ?>
									<input type="hidden" name="interest_amount" id="interest_amount" value="<?= $interest_amount; ?>" />
								</div>
							</div>
							
							<?php if($this->erp->formatMoney($total_paid_amt) > 0) { ?>
							<div class="col-md-6" style="display:none;">
								<div class="form-group">
									<?= lang("total_payment", "paid"); ?>
									<?php echo form_input('paid', $this->erp->roundUpMoney($total_paid_amt,$getLoan->code), 'class="form-control" id="paid" style="pointer-events: none;"'); ?>
								</div>
							</div>
							<?php } ?>
							<?php if($this->erp->formatMoney($owed) > 0) { ?>
							<div class="col-md-6">
								<div class="form-group">
									<?= lang("previous_months_owed", "owed"); ?>
									<?php echo form_input('owed', $this->erp->roundUpMoney($owed,$getLoan->code), 'class="form-control" id="owed" style="pointer-events: none;"'); ?>
									<input type="hidden" name="loan_owed_id" id="loan_owed_id" value="<?= $sale->loan_owed_id; ?>" />
								</div>
							</div>
							<?php } ?>							
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-6" style="display:none;">
										<div class="form-group">
											<?= lang("payment_status", "payment_status"); ?>
											<?php
											$payment_status[""] = "";
											$payment_status["received"] = lang("paid");
											$payment_status["partial"]  = lang("partial");
											echo form_dropdown('payment_status', $payment_status, '', 'class="form-control select" id="payment_status" placeholder="' . lang("select") . ' ' . lang("payment_status") . '"  data-bv-notempty="true"');
											?>
										</div>
									</div>
									
								</div>
							</div>
							
					</div>
				</div>
                
			</div>
		</div>
		<div class="modal-footer">
            <?php echo form_submit('add_payment', lang('submit'), 'class="btn btn-primary" id="add_payment"'); ?>
        </div>
	</div>
	<?php echo form_close(); ?>
</div>
<?= $modal_js ?>
<script type="text/javascript">
	$(document).ready(function () {		
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
		
		
		$('#penalty_amount').on('change', function() {
			var currency = <?=$getLoan->type ?>;
			var installment_amount = $('#installment_amount').val();
			installment_amount = formatDecimal(installment_amount.replace(',', ''));
			var penalty_amount = parseFloat($('#penalty_amount').val());
			var total_amount = installment_amount + penalty_amount;
			$('#total_amount').val(roundUpMoney(total_amount , currency));			
		});
	});
	
</script>


<script type="text/javascript">
	$(document).ready(function() {
		$('#payments').on('keyup  change', function() {
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
	});
</script>

<!---deposit---->
<script type="text/javascript">
	$(document).ready(function() {		
		/*
		
		$('#partials').keyup(function(){
			var currency = <?=$getLoan->type ?>;
			var total_amounts = $('#h_total_amount').val()? parseFloat($('#h_total_amount').val()) : 0;///$('#h_total_amount').val()-0;
			var other_paids = $('#other_paid').val()? parseFloat($('#other_paid').val()) : 0;
			var partials = $('#partials').val()? parseFloat($('#partials').val()) : 0;	
			var total = total_amounts + other_paids;
			var balance = total - partials;
			$('#bl').val(roundUpMoney(balance , currency));	
		});	
		
		$('#partials').keyup(function(){
			var status = $('#pay_method').val();
			if (status == "deposit"){
				var pay_deposits = $('#partials').val();
				if( pay_deposits > <?=$deposit?> ){
					$('#partials').val( <?=$deposit?> );
					var total_amounts = $('#h_total_amount').val()? parseFloat($('#h_total_amount').val()) : 0;///$('#h_total_amount').val()-0;
					var other_paids = $('#other_paid').val()? parseFloat($('#other_paid').val()) : 0;
					var partials = $('#partials').val()? parseFloat($('#partials').val()) : 0;	
					var total = total_amounts + other_paids;
					var balance = total - partials;
					$('#bl').val(formatMoney(balance));	
				}
			}else{
				//var pay_partial = $('#partials').val();
				//var total = <?= $total_amount ?>;
				//if( pay_partial > total){
					//$('#partials').val( <?= $total_amount ?> );
				//}
			} 
			
		});	
		
		$('#pay_method').change(function(){
			var method = $(this).val();
			if(method == 'deposit'){
				alert("You have  <?=$deposit?> <?=$curency_name?> on Deposit Balance... ");
			}
		});*/
		
	});
</script>