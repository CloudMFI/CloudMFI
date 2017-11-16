<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('edit_payment'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('enter_info'); ?></p>
				<div class="box-content">
					<div class="row">
						<div class="col-md-12">							
							<div class="tab-content">
									<?php
									$attrib = array('data-toggle' => 'validator', 'role' => 'form');
									echo form_open_multipart("Installment_payment/edit_payment", $attrib)
									?>
									
										<div class="modal-body">
											<div class="row">
												<div class="col-lg-12">
													<div class="panel panel-primary">
														<div class="panel-heading"><?= lang('edit_payment') ?></div>
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
																			<?php echo form_input('penalty_amount', $this->erp->roundUpMoney($penalty_amount, $sale_item->currency_code), 'class="form-control" id="penalty_amount" '); ?>
																		</div>
																	</div>																	
																</div>
																<div class="col-md-12">
																	<div class="col-md-6" >
																		<div class="form-group">
																			<?= lang("principle", "principle"); ?>
																			<?php echo form_input('principle', $this->erp->roundUpMoney($principle_amount, $sale_item->currency_code), 'class="form-control" id="principle"'); ?>
																		</div>
																	</div>
																	<div class="col-md-6" >
																		<div class="form-group">
																			<?= lang("interest", "interest"); ?>
																			<?php echo form_input('interest',$this->erp->roundUpMoney($interest_amount, $sale_item->currency_code), 'class="form-control" id="interest" style="pointer-events: none;"'); ?>
																		</div>
																	</div>
																	<!--<div class="col-md-6" >
																		<div class="form-group">
																			<?= lang("total_servives", "total_servives"); ?>
																			<?php echo form_input('total_servives',$this->erp->roundUpMoney($total_service_amount, $sale_item->currency_code), 'class="form-control" id="total_servives" '); ?>
																		</div>
																	</div>-->								
																</div>
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
																			<?= lang("owed_paid", "owed_paid"); ?>
																			<?php echo form_input('owed_paid', $this->erp->roundUpMoney($owed_paid, $sale_item->currency_code), 'class="form-control number_only" id="owed_paid"'); ?>
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
																			<input type="hidden" name="total_service_charge" id="total_service_charge" value="<?= $total_services_amount ?>" />
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
																			<?php echo form_input('balance',$this->erp->roundUpMoney($balance, $sale_item->currency_code), 'class="form-control" id="balance" style="pointer-events: none;"'); ?>
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
																			echo form_dropdown('payment_status', $payment_status,'', 'class="form-control select" id="payment_status" placeholder="' . lang("select") . ' ' . lang("payment_status") . '"  ');
																			?>
																		</div>
																	</div>
																</div>
															</div>							
													</div>
												</div>
												
											</div>
										</div>
									
									<div class="tab-pane">
										<input type="submit" class="btn btn-primary" value="<?=lang('submit')?>" name="submit" />
									</div>
									<?php echo form_close(); ?>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>

<script type="text/javascript">
	$(window).load(function() {
		$("#pay_method").trigger('change');		
	});
	
	$(document).ready(function () {		
		$('#penalty_amount, #principle, #interest, #owed_paid, #other_paid, .services, #payments').on('keyup', function() {			
			var penalty = $('#penalty_amount').val()? ($('#penalty_amount').val()) : 0;
			var penalty_amt = penalty ? formatDecimal(penalty.split(',').join('')) : 0;
			
			var principle = $('#principle').val()? ($('#principle').val()) : 0;
			var principle_amt = principle ? formatDecimal(principle.split(',').join('')) : 0;
			
			var interest = $('#interest').val()? ($('#interest').val()) : 0;
			var interest_amt = interest ? formatDecimal(interest.split(',').join('')) : 0;
			
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
			
			var total_payment = penalty_amt + principle_amt + interest_amt +  total_services + owed_paid_amt + other_paid_amt;
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
