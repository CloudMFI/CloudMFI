<?php  
//$this->erp->print_arrays($sale_id);
//$this->erp->print_arrays($sales);

 ?>

<div class="modal-dialog modal-lg" style="width:60%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_deposit'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'addAcc');
        echo form_open_multipart("account/add_deposit", $attrib); ?>
        <div class="modal-body">
            <div class="row">
				<div class="col-lg-12">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= lang('add_deposit') ?></div>
						<div class="panel-body" style="padding: 5px;">
							<div class="col-lg-12">
								<div class="col-md-6">
									<div class="form-group">
										<?= lang("contract_id", "contract_id"); ?>
										<?php
										$all_contract[(isset($_POST['contract_id']) ? $_POST['contract_id'] : '')] = (isset($_POST['contract_id']) ? $_POST['contract_id'] : '');
										if(array($contracts)) {
											foreach($contracts as $contract){
												$all_contract[$contract->id] = $contract->reference_no;
											}
										}
										echo form_dropdown('contract_id', $all_contract , $sale_id ? $sale_id: '', 'class="form-control" id="contract_id" placeholder="' . lang("search_contract_id") . '" ');
										?>
										
									</div>
									<input type="hidden" name="grand_total" id="grand_total"/>
									<input type="hidden" name="sale_id" id="sale_id" />
									<input type="hidden" name="loan_id" id="loan_id" />
									<input type="hidden" name="saving_interest" id="saving_interest" />
									
								</div>
								
								<div class="col-md-6"> 
									<div class="form-group">
										<?= lang("date", "date"); ?>
										<?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : date('d/m/Y')), 'class="form-control date" id="date" data-bv-notempty="true"'); ?>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="col-md-6">
									<div class="form-group">
										<?= lang("reference", "reference"); ?>
										<?php echo form_input('reference', $reference_sp, 'class="form-control" id="reference" data-bv-notempty="true" style="pointer-events: none;"'); ?>
									</div>									
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?= lang("name", "name"); ?>
										<?php echo form_input('name','', 'class="form-control" id="name"  '); ?>
									</div>									
								</div>
								
							</div>
							
							<div class="col-lg-12">
								<div class="col-md-6">
									<div class="form-group">
										<?= lang("principle", "principle"); ?>
										<?php echo form_input('principle', (isset($_POST['principle']) ? $_POST['principle'] : ''), 'class="form-control number_only" id="principle" '); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?= lang("interest", "interest"); ?>
										<?php echo form_input('interest', (isset($_POST['interest']) ? $_POST['interest'] : ''), 'class="form-control number_only" id="interest" '); ?>
									</div>
								</div>								
							</div>
							<div class="col-lg-12">								
								
								<div class="col-md-6">
									<div class="form-group">
										<?= lang("penalty", "penalty"); ?>
										<?php echo form_input('penalty', (isset($_POST['penalty']) ? $_POST['penalty'] : ''), 'class="form-control number_only" id="penalty" '); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?= lang("old_owed", "owed_balance"); ?>
										<?php echo form_input('owed_balance', (isset($_POST['owed_balance']) ? $_POST['owed_balance'] : ''), 'class="form-control number_only" id="owed_balance"  style="pointer-events: none;"'); ?>
									</div>
								</div>
							</div>
							<div class="col-lg-12" id = "services">
								
							</div> 
							<div class="col-lg-12">
								<input type="hidden" name="total_services" id="total_services"/>
								<div class="col-md-6">
									<div class="form-group">
										<?= lang("other_paid", "other_paid"); ?>
										<?php echo form_input('other_paid', (isset($_POST['other_paid']) ? $_POST['other_paid'] : '0'), 'class="form-control number_only" id="other_paid"'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?= lang("total_payment", "amount"); ?>
										<?php echo form_input('amount', (isset($_POST['amount']) ? $_POST['amount'] : ''), 'class="form-control number_only" id="amount"  style="pointer-events: none;"'); ?>
									</div>
									<input type="hidden" name="total_amount" id="total_amount" />
								</div>														
							</div>
							<div class="col-lg-12">
								<div class="col-md-6">
									<div class="form-group" id ="payment_method">
										<?= lang("payments", "paid"); ?>
										<?php echo form_input('paid', (isset($_POST['paid']) ? $_POST['paid'] : ''), 'class="form-control number_only" id="paid" data-bv-notempty="true"'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group" id ="payment_method">
										<?= lang("owed_balance", "balance"); ?>
										<?php echo form_input('balance', (isset($_POST['balance']) ? $_POST['balance'] : ''), 'class="form-control number_only" id="balance" style="pointer-events: none;"'); ?>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="col-md-6">
									<div class="form-group" id ="payment_method">
										<?= lang("paid_by", "paid_by"); ?>
										<?php
										$pay_method[""] = "";
										$pay_method["cash"] = "Cash";
										$pay_method["wing"] = "Wing";
										$pay_method["True Money"] = "True Money";
										$pay_method["Visa"] = "Visa Card";
										echo form_dropdown('paid_by', $pay_method, 'cash', 'class="form-control select" id="paid_by" placeholder="' . lang("select") . ' ' . lang("pay_method") . '" style="width:100%" ');
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
							<div class="col-lg-12">
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
								<div class="col-md-6">
								</div>
							</div>
							<div class="col-lg-12" style="display:none;">
								<div class="col-md-6">
									<div class="form-group">
										<div class="col-lg-6 payment_schedule" style="vertical-align: middle; padding: 2.5% 0% 1% 1.3%; margin-top: 20px;">
											<input type="button" class="btn btn-primary" value="<?=lang('payment_schedule')?>" name="payment_schedule" id="payment_schedule" />
										</div>
									</div>
								</div>
								<div class="col-md-6">
									
								</div>
							</div>
						</div>
					</div>
				</div>
                
			</div>
			<div class="modal-footer">
				<?php echo form_submit('add_deposit', lang('save'), 'class="btn btn-primary" id="add_chart"'); ?>
			</div>
		</div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
<script type="text/javascript">
	$(window).load(function() {
		//$("#contract_id").trigger('change');
		$("#services").trigger('change');
	});
	
	//////----------------------
	$(document).ready(function () {
		$('#contract_id').on('change', function(){
			//e.preventDefault();
			var contract = $(this).val();
			if(contract){
				var contract_val = contract.split('#');
				var sale_id = contract_val[0];
				$.ajax({
					url: site.base_url + 'account/ajaxGetLoanBysaleID/'+sale_id,
					dataType: 'json',
					success: function(scdata){
						//$('#amount').val(formatMoney(scdata.principle + scdata.interest + scdata.overdue_amount + scdata.total_service_charge + scdata.sumOweds));
						//alert(scdata.saving_interest);
						$('#balance').val(formatMoney(scdata.payment));
						$('#amount').val(formatMoney(scdata.payment));
						$('#principle').val(formatMoney(scdata.principle));
						$('#interest').val(formatMoney(scdata.interest));
						$('#penalty').val(formatMoney(scdata.overdue_amount));
						$('#owed_balance').val(formatMoney(scdata.sumOweds));
						$('#saving_interest').val(scdata.saving_interest);
						$('#loan_id').val(scdata.id);
						//$('#date').val(scdata.dateline);
						$('#name').val(scdata.customer);
						var haftterm = scdata.haftterm;
						var period = scdata.period;
						var currency_type = scdata.currency_type;
						var loan_balance = scdata.loan_balance;
						
						var principle = scdata.principle;
						var interest = scdata.interest;
						var payment_amt = (formatDecimal(principle.split(',').join(''))) + (formatDecimal(interest.split(',').join('')));
						
						var loan_balances = formatDecimal(loan_balance.split(',').join(''));
						var total = convertCurrency(scdata.sale_rate, scdata.def_rate, scdata.total);
						
						var box = '';
						var sv_amount = 0;
						var total_sv = 0;
						var totalsv = 0;
						var svamount = 0;
						var service_amount = 0;
						var total_service_amount = 0;
						$.each(scdata.service_payment, function(index) {							
							var amount =  scdata.service_payment[index].amount ;
							var service_paid = scdata.service_payment[index].service_paid;
							var type  = scdata.service_payment[index].type;
							var tax_rate  = scdata.service_payment[index].tax_rate;
							var charge_by  = scdata.service_payment[index].charge_by;
							
							if (service_paid == 2 && period <= haftterm){
								
								if(type == "Percentage"){
									if(charge_by == 2){
										svamount = amount * loan_balances;
									}if(charge_by == 3){
										svamount = amount * payment_amt;
									}else{
										svamount = amount * total;										
									}
								}else{
									svamount = convertCurrency(scdata.sale_rate, scdata.def_rate, scdata.service_payment[index].amount);
								}
								sv_amount = svamount + (svamount * tax_rate) ;
								//totalsv += svamount + (svamount * tax_rate) ;
							}
							else if (service_paid == 3 ){
								if(type == "Percentage"){
									if(charge_by == 2){
										svamount = amount * loan_balances;
									}if(charge_by == 3){
										svamount = amount * payment_amt;
									}else{
										svamount = amount * total;										
									}
								}else{
									svamount = convertCurrency(scdata.sale_rate, scdata.def_rate, scdata.service_payment[index].amount);
								}
								sv_amount = svamount + (svamount * tax_rate) ;
								//totalsv += svamount + (svamount * tax_rate) ;
							}
							else if (service_paid == 4 && period <= haftterm){
								if(type == "Percentage"){
									svamount = (amount * total) / haftterm;
								}else{
									svamount = convertCurrency(scdata.sale_rate, scdata.def_rate, scdata.service_payment[index].amount) / haftterm;
								}
								sv_amount = svamount + (svamount * tax_rate) ;
								//totalsv += svamount + (svamount * tax_rate) ;
							}
							
							service_amount = roundUpMoney(sv_amount, currency_type);
							total_service_amount += roundUpMoney(sv_amount, currency_type);
							
							box += '<div class="col-md-6">'+
										'<div class="form-group">'+
											'<label for="'+ scdata.service_payment[index].id +'">'+ scdata.service_payment[index].description +'</label>'+ 
											'<input type="text" class="form-control services" name="service[]" id="services_'+ scdata.service_payment[index].id +'" value="'+ formatMoney(service_amount) +'" style="pointer-events: none;" />'+ 
											'<input type="hidden" class="form-control" name="service_id[]" id="'+ scdata.service_payment[index].id +'" value="'+ scdata.service_payment[index].id +'" />'+
										'</div>'+
									'</div>';
						});
						$('#services').html(box);
						//total_sv += totalsv;
						total_sv += total_service_amount;
						total_sv = formatDecimal(roundUpMoney(total_sv, currency_type));
						$('#total_services').val(total_sv);
						$('#balance').val(formatMoney(scdata.payment + total_sv));
						$('#amount').val(formatMoney(scdata.payment + total_sv));
						$('#total_amount').val(scdata.payment + total_sv);
					},				
					error: function () {
							bootbox.alert('<?= lang('ajax_error') ?>');
							$('#modal-loading').hide();
						}
				});
			}
		}).trigger('change');
		
	});
	
</script>

<script type="text/javascript">
	
	$(document).ready(function () {
		$('#penalty, #principle, #interest, #owed_balance, #other_paid, .services, #paid').on('keyup', function() {			
			var penalty = $('#penalty').val()? ($('#penalty').val()) : 0;
			var penalty_amt = penalty ? formatDecimal(penalty.split(',').join('')) : 0;
			
			var principle = $('#principle').val()? ($('#principle').val()) : 0;			
			var principle_amt = principle ? formatDecimal(principle.split(',').join('')) : 0;
			
			var interest = $('#interest').val()? ($('#interest').val()) : 0;
			var interest_amt = interest ? formatDecimal(interest.split(',').join('')) : 0;
			
			var owed_paid = $('#owed_balance').val()? ($('#owed_balance').val()) : 0;
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
			$('#amount').val(formatMoney(total_payment));
			$('#total_amount').val(total_payment);
			$('#total_services').val(total_services);
			
			var payment = $('#paid').val()? parseFloat($('#paid').val()) : 0;
				var balance = total_payment - payment;
				$('#balance').val(formatMoney(balance));
				if(payment > total_payment){
					$('#paid').val(formatDecimal(total_payment));
					$('#balance').val(0);
				}
		});
		
	});
	
	/*$('.services').on('keyup', function() {
		var total_services = 0;
		$('.services').each(function() {
			total_services += formatDecimal(($(this).val()).replace(',', ''));
		});
		alert(total_services);
	});
	$(document).ready(function () {
		///////----------------------
		$('#paid').keyup(function(){   
			var payment = $('#paid').val()? parseFloat($('#paid').val()) : 0;
			var amount = $('#amount').val()? ($('#amount').val()) : 0;
			var amounts = amount.split(',').join('');
			var balance = amounts - payment;
			$('#balance').val(balance);			
				if (payment > amounts){
					$('#paid').val(formatMoney(amounts));	
					$('#balance').val(0);
				}
		});
		/683
		
		$('#other_paid').keyup(function(){			
			var other_paid = $('#other_paid').val()? parseFloat($('#other_paid').val()) : 0;
			var amount = $('#total_amount').val()? ($('#total_amount').val()) : 0;
			var amounts = amount.split(',').join(''); //replace(",", "");
			var total_amount = parseFloat(other_paid) + parseFloat(amounts);
			$('#amount').val(formatMoney(total_amount));
			$('#balance').val(formatMoney(total_amount));
		});
		
	});
	
	$(document).ready(function () {
		$('#payment_schedule').live("click", function() {
			var id = $('#contract_id').val();
			var sale_id = id.split('#');
			$('#sale_id').val(sale_id[0]);
			var sale_id = $('#sale_id').val();
			var link= $('<a href="Installment_payment/payment_schedule/'+ null +'/'+ null +'/'+sale_id+'" rel="lightbox" id="print_payment'+count_link+'" data-toggle="modal" data-target="#myModal"></a>');
			$("body").append(link);
			$('#print_payment'+count_link).click();
		});
		
	});*/
	
	
</script>
