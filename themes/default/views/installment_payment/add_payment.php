<?php
	//$this->erp->print_arrays($setting );
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
		
		/*foreach($services as $service) {
			
			if($service->service_paid != 1) {
					$service->amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $service->amount);
					$service->total_charge += (($service->service_paid == 2 && $loan->period <= $countrow)? ($service->amount):(($service->service_paid == 3)? ($service->amount ):0) ?:(($service->service_paid == 4 && $loan->period <= $countrow )? (($service->amount) / $countrow):0));
				
			}else {
				$service->total_charge = $service->amount;
			}
		}*/
		$loan_balance =  str_replace(',', '', $this->erp->roundUpMoney($loan->principle,$sale_item->currency_code)) +  str_replace(',', '', $this->erp->roundUpMoney($loan->balance,$sale_item->currency_code));
		$amount = 0;
		$service_amount = 0;
		foreach($services as $service) {
			$sale->total = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $sale->total);
			$interest_ = str_replace(',', '', $this->erp->roundUpMoney($loan->interest, $sale_item->currency_code));
			$principle_ = str_replace(',', '', $this->erp->roundUpMoney($loan->principle, $sale_item->currency_code));
			$payment_amt = $interest_ + $principle_ ;
			
			if($service->service_paid == 2){
				if($loan->period <= $countrow){
					if($service->type == "Percentage"){
						//$amount = ($service->charge_by == 2)? ($service->amount * $loan_balance): ( $service->amount * $sale->total) ;
						$amount = ($service->charge_by == 2)? ($service->amount * $loan_balance): (($service->charge_by == 3)? ($service->amount * $payment_amt ) : $service->amount * $sale->total ) ;
						$service_amount = $amount + ($amount * $service->tax_rate);
					}else{
						$amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $service->amount);
						$service_amount = $amount + ($amount * $service->tax_rate);						
					}
				}else{
					$service_amount = 0;
				}
			}
			if($service->service_paid == 3){
				if($service->type == "Percentage"){
					$amount = ($service->charge_by == 2)? ($service->amount * $loan_balance): (($service->charge_by == 3)? ($service->amount * $payment_amt ) : $service->amount * $sale->total ) ;
					$service_amount = $amount + ($amount * $service->tax_rate);
				}else{
					$amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $service->amount);
					$service_amount = $amount + ($amount * $service->tax_rate);
				}
			}
			
			if($service->service_paid == 4){
				if($loan->period <= $countrow){
					if($service->type == "Percentage"){
						$amount = ( $service->amount * $sale->total) / $countrow;
						$service_amount = $amount + ($amount * $service->tax_rate);
					}else{
						$amount = ($this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $service->amount)) / $countrow;
						$service_amount = $amount + ($amount * $service->tax_rate);
						
					}
				}else{
					$service_amount = 0;
				}
			}
			$service->total_charge +=$service_amount;
		}
		//$this->erp->print_arrays($service_amount); 
		$saving_interest += str_replace(',', '', $this->erp->roundUpMoney($loan->saving_interest,$sale_item->currency_code)); //$saving_interest;
		$installment_amount += str_replace(',', '', $this->erp->roundUpMoney($loan->payment,$sale_item->currency_code)); //$loan->payment;
		$interest_amount += str_replace(',', '', $this->erp->roundUpMoney($loan->interest,$sale_item->currency_code)); //$loan->interest;
		$principle_amount += $loan->principle;
		$installment_amt += ( $loan->payment - $loan->paid_amount );
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
	 
	$penalty_amount = $overdue_days * $penalty_rate;
	$penalty_amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $penalty_amount); 
	$interest_am = $this->erp->roundUpMoney($interest_amount,$sale_item->currency_code);
	$principle_am = $this->erp->roundUpMoney($principle_amount,$sale_item->currency_code);
	$penalty_am = $this->erp->roundUpMoney($penalty_amount,$sale_item->currency_code);
	$loan_owed = $this->erp->roundUpMoney($loan_owed->owed, $sale_item->currency_code);
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
											<?php echo form_input('reference', $reference_sp, 'class="form-control" id="reference" data-bv-notempty="true" style="pointer-events: none;" readonly'); ?>
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
											<?= lang("invoce_no", "invoce_no"); ?>
											<?php echo form_input('invoce_no','', 'class="form-control" id="invoce_no" '); ?>
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
											<?php echo form_input('penalty_amount', $penalty_am, 'class="form-control number_only" id="penalty_amount" '); ?>
										</div>
									</div>																	
								</div>
								<div class="col-md-12">
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("principle", "principle"); ?>
											<?php echo form_input('principle', $principle_am, 'class="form-control number_only" id="principle" style="pointer-events: none;"' ); ?>
										</div>
									</div>
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("interest", "interest"); ?>
											<?php echo form_input('interest', $interest_am, 'class="form-control number_only" id="interest"'); ?>
										</div>
									</div>									
									<!--<div class="col-md-6" >
										<div class="form-group">
											<?= lang("total_servives", "total_servives"); ?>
											<?php echo form_input('total_servives',$this->erp->roundUpMoney($total_service_amount, $sale_item->currency_code), 'class="form-control" id="total_servives" style="pointer-events: none;"'); ?>
										</div>
									</div>-->
								</div>
								<?php if ($setting->interest_discount == "enable"){ ?>
								<div class="col-md-12">
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("interest_discount_rate", "interest_discount_rate"); ?>
											<?php echo form_input('interest_discount_rate','', 'class="form-control" id="interest_discount_rate"'); ?>
										</div>
										<input type="hidden" name="discount_rate" class="discount_rate" id="discount_rate" />
									</div>
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("interest_payment", "interest_payment"); ?>
											<?php echo form_input('interest_payment', $interest_am, 'class="form-control number_only" id="interest_payment" ' ); ?>
										</div>
									</div>
								</div>
								<?php } ?>
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
												<label> <?php echo $service->description; ?> </label>
												<?php echo form_input('services[]', $this->erp->roundUpMoney($service->total_charge, $sale_item->currency_code), 'class="form-control number_only services" id="services_'. $service->id .'"  '); ?>
												<input type="hidden" name="service_id[]" class="services_id" value="<?= $service->id; ?>" />
											</div>
										</div>
										
										<?php 
											}
										}
									}
									$total_service_amount += $total_services_amount;
									
									$total_service_am = $this->erp->roundUpMoney($total_service_amount, $sale_item->currency_code);
									?>
									<input type="hidden"  name="total_services" id="total_services"/>
								</div>
								<div class="col-md-12">										
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("old_owed", "owed_paid"); ?>
											<?php echo form_input('owed_paid', $loan_owed, 'class="form-control number_only" id="owed_paid" style="pointer-events: none;"'); ?>
										</div>
									</div>	
									<div class="col-md-6" >
										<div class="form-group">
											<?= lang("other_payment", "other_paid"); ?>
											<?php echo form_input('other_paid','0', 'class="form-control number_only" id="other_paid"'); ?>
										</div>
									</div>								
								</div>
								
								<div class="col-md-12">
									
									<?php
										$total_amount = str_replace(',', '', $total_service_am) + str_replace(',', '', $interest_am) + str_replace(',', '', $principle_am) + str_replace(',', '', $penalty_am)  + str_replace(',', '', $loan_owed) - $total_paid_amt;
									?>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("total_payments", "total_payments"); ?> 
											<?php echo form_input('total_payments','', 'class="form-control" id="total_payments" style="pointer-events: none;" '); ?>
											<input type="hidden" name="total_service_charge" id="total_service_charge" value="<?= $total_services_amount ?>" />
											<input type="hidden" name="h_total_amount" id="h_total_amount" />
											<input type="hidden" name="saving_interest" id="saving_interest" value="<?= $saving_interest ?>" />
											
										</div>
									</div>	
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("payments", "payments"); ?>
											<?php echo form_input('payments', '', 'class="form-control number_only" id="payments" required="required"'); ?>
										</div>
									</div>									
								</div>								
								<div class="col-md-12">										
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("payment_date", "pay_date"); ?>
											<?php echo form_input('pay_date', $this->erp->hrld(date('Y-m-d')), 'class="form-control datetime" id="pay_date" data-bv-notempty="true"'); ?>
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
											<?= lang("owed_balance", "balance"); ?>
											<?php echo form_input('balance', '', 'class="form-control" id="balance" style="pointer-events: none;"'); ?>
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
											echo form_dropdown('pay_method', $pay_method, 'cash', 'class="form-control select" id="pay_method" placeholder="' . lang("select") . ' ' . lang("pay_method") . '" style="width:100%" data-bv-notempty="true"');
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
												echo form_dropdown('bank_account', $all_bank, (isset($_POST['bank_account']) ? $_POST['bank_account'] : '111200'), 'id="bank_account" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("bank_account") . '" style="width:100%;" required="required"');
											?>
										</div>
									</div>								
								</div>
								<!------------------------->
							</div>
							
							<div class="col-md-6" style="display:none;">
								<div class="form-group">
									<?= lang("installment_amount", "installment_amount"); ?>
									<?php echo form_input('installment_amount', $this->erp->formatMoney($installment_amount), 'class="form-control" id="installment_amount" style="pointer-events: none;" '); ?>
									<input type="hidden" name="interest_amount" id="interest_amount" value="<?= $interest_amount; ?>" />
								</div>
							</div>							
							<div class="col-md-6" style="display:none;">
								<div class="form-group">
									<?= lang("total_payment", "paid"); ?>
									<?php echo form_input('paid', $this->erp->roundUpMoney($total_paid_amt,$sale_item->currency_code), 'class="form-control" id="paid" style="pointer-events: none;"'); ?>
								</div>
							</div>							
							<div class="col-md-6"style="display:none;">
								<div class="form-group">
									<?= lang("previous_months_owed", "owed"); ?>
									<?php echo form_input('owed', $this->erp->roundUpMoney($owed,$sale_item->currency_code), 'class="form-control" id="owed" style="pointer-events: none;"'); ?>
									<input type="hidden" name="loan_owed_id" id="loan_owed_id" value="<?= $sale->loan_owed_id; ?>" />
								</div>
							</div>													
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
			var discount = '<?= $setting->interest_discount; ?>';			
			var penalty = $('#penalty_amount').val()? ($('#penalty_amount').val()) : 0;
			var penalty_amt = formatDecimal(penalty.split(',').join(''));
			var principle = $('#principle').val()? ($('#principle').val()) : 0;
			var principle_amt = formatDecimal(principle.split(',').join(''));	
			
			var interest = $('#interest').val()? ($('#interest').val()) : 0;
			var interest_amt = formatDecimal(interest.split(',').join(''));
			var interest_pay = $('#interest_payment').val()? ($('#interest_payment').val()) : 0;			
			var interest_payment =  interest_pay ? formatDecimal(interest_pay.split(',').join('')) : 0;
			
			var interest_amount = 0;
			if(discount == "enable"){
				interest_amount = interest_payment;
			}else{
				interest_amount = interest_amt;
			}
			
			var owed_paid = $('#owed_paid').val()? ($('#owed_paid').val()) : 0;
			var owed_paid_amt = formatDecimal(owed_paid.split(',').join(''));
			var other_paid = $('#other_paid').val()? ($('#other_paid').val()) : 0;
			var other_paid_amt = formatDecimal(other_paid.split(',').join(''));			
			
			var total_services = 0;
			$('.services').each(function() {
				total_services += formatDecimal(($(this).val()).replace(',', ''));
			});			
			var total_payment = penalty_amt + principle_amt + interest_amount + total_services + owed_paid_amt + other_paid_amt;
			
			$('#total_payments').val(formatMoney(total_payment));
			$('#h_total_amount').val(total_payment);
			$('#total_services').val(total_services);
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
		
		/*$('.services').on('keyup', function() {
			var total_services = 0;
			$('.services').each(function() {
				total_services += formatDecimal(($(this).val()).replace(',', ''));
			});
			alert(total_services);
		});*/
		
	});
</script>