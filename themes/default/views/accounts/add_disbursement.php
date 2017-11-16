<?php
	//$this->erp->print_arrays($branch);
?>
<div class="modal-dialog modal-lg" style="width:60%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_disbursement'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'addAcc');
        echo form_open_multipart("account/add_disbursement", $attrib); ?>
        <div class="modal-body">
            <div class="row">
				<div class="col-lg-12">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= lang('add_disbursement') ?></div>
						<div class="panel-body" style="padding: 5px;">
							<div class="col-lg-12">
								<div class="col-md-6">
									<div class="form-group" style="display:none;">
										<?= lang("date1", "date1"); ?>
										<?php echo form_input('date1', (isset($_POST['date1']) ? $_POST['date1'] : ''), 'class="form-control" '); ?>
									</div>
									<div class="form-group">
										<?= lang("date", "date"); ?>
										<?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : date('d/m/Y')), 'class="form-control date" id="date" data-bv-notempty="true"'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?= lang("reference", "reference"); ?>
										<?php echo form_input('reference', $reference_sp, 'class="form-control" id="reference" data-bv-notempty="true" readonly'); ?>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="col-md-6">
									<div class="form-group">
										<?= lang("contract_id", "contract_id"); ?>
										<?php
										$all_contract[(isset($_POST['contract_id']) ? $_POST['contract_id'] : '')] = (isset($_POST['contract_id']) ? $_POST['contract_id'] : '');
										if(array($contracts)) {
											foreach($contracts as $contract){
												$all_contract[$contract->id .'#'.$contract->total ] = $contract->reference_no;
											}
										}
										echo form_dropdown('contract_id', $all_contract, '', 'class="form-control" id="contract_id" placeholder="' . lang("select_contract_id") . '"');
										?>
									</div>
									<input type="hidden" name="grand_total" id="grand_total" />
									<input type="hidden" name="sale_id" id="sale_id" />
									<input type="hidden" name="branch_amount" id="branch_amount" />
									<input type="hidden" name="df_rate" id="df_rate" />
									<input type="hidden" name="sale_rate" id="sale_rate" />
									
								</div>
								<div class="col-md-6">									
									<div class="form-group">
										<?= lang("name", "name"); ?>
										<?php echo form_input('name', '' , 'class="form-control " id="name" style="pointer-events: none;" '); ?>
									</div>
								</div>
								
							</div>
							<div class="col-lg-12">
								<div class="col-md-6" id = "services">
								</div>
								<div class="col-md-6" id = "total_service_payment">
								</div>
							</div>
							<div class="col-lg-12">
								<!--<div class="col-md-6">
									<div class="form-group">
										<?= lang("service_payment", "service_payment"); ?>
										<?php echo form_input('service_payment', (isset($_POST['service_payment']) ? $_POST['service_payment'] : ''), 'class="form-control number_only" id="service_payment" style="pointer-events: none;"'); ?>
									</div>
								</div>-->
								<div class="col-md-6">
									<div class="form-group" id ="payment_method">
										<?= lang("paid_by", "paid_by"); ?>
										<?php
										$pay_method[""] = "";
										$pay_method["cash"] = "Cash";
										$pay_method["wing"] = "Wing";
										$pay_method["Visa"] = "Visa Card";
										echo form_dropdown('paid_by', $pay_method, 'cash', 'class="form-control select" id="paid_by" placeholder="' . lang("select") . ' ' . lang("pay_method") . '" style="width:100%" data-bv-notempty="true"');
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?= lang("cash_out", "bank_account"); ?>
										<?php
											$all_bank[(isset($_POST['bank_account']) ? $_POST['bank_account'] : '')] = (isset($_POST['bank_account']) ? $_POST['bank_account'] : '');
											if(array($banks)) {
												foreach($banks as $bank) {
													$all_bank[$bank->accountcode .'#'.$bank->amount] = $bank->accountname;
												}
											}
											echo form_dropdown('bank_account', $all_bank, (isset($_POST['bank_account']) ? $_POST['bank_account'] : '111200'), 'id="bank_account" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("bank_account") . '" style="width:100%;" data-bv-notempty="true"');
										?>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="col-md-6">									
									<div class="form-group">
										<?= lang("remaining", "remaining"); ?>
										<?php echo form_input('remaining', '' , 'class="form-control number_only" id="remaining" style="pointer-events: none;"'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?= lang("disburse_amount", "amount"); ?>
										<?php echo form_input('amount', (isset($_POST['amount']) ? $_POST['amount'] : ''), 'class="form-control number_only" id="amount" data-bv-notempty="true"'); ?>
									</div>
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
				<?php echo form_submit('add_disbursement', lang('save'), 'class="btn btn-primary" id="add_chart"'); ?>
			</div>
		</div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>


<script type="text/javascript">
	$(document).ready(function () {
		$('#contract_id').on( "change", function() {
			var lease = $('#contract_id').val();
			var contract_val = lease.split('#');			
			var sale_id = contract_val[0];
			$.ajax({
				url: site.base_url + 'account/ajaxGetSaleBysaleID/'+sale_id,
				dataType: 'json',
				success: function(scdata){
					$('#name').val(scdata.customer);	
					$('#remaining').val(scdata.total);	
					$('#grand_total').val(scdata.g_total);
					$('#service_payment').val(formatMoney(scdata.service));
					$('#df_rate').val(scdata.def_rate);
					$('#sale_rate').val(scdata.sale_rate);
					var box = '';
					var box1 = '';
					
					$.each(scdata.one_services, function(index) {						
						//alert(scdata.one_services[index].id +"###"+ scdata.one_services[index].description +"###"+ scdata.one_services[index].amount);
						var sv_amount = 0;
						if(scdata.one_services[index].type == "Percentage"){
							var amount = scdata.one_services[index].amount; 
							var total = scdata.total;
							var total_amt = formatDecimal(total.split(',').join(''));  ///total.replace(',', '');	
							var svamount = amount * total_amt ;
							var tax = scdata.one_services[index].tax_rate;
							var tax_rate = tax * svamount;
							sv_amount = svamount + tax_rate;
						}else{
							var svamount = convertCurrency(scdata.sale_rate, scdata.def_rate, scdata.one_services[index].amount);
							var tax = scdata.one_services[index].tax_rate;
							var tax_rate = tax * svamount;
							sv_amount = svamount + tax_rate;
						}						
						box += 
								'<div class="form-group">'+
									'<label for="'+ scdata.one_services[index].id +'">'+ scdata.one_services[index].description +'</label>'+ 
									'<input type="text" class="form-control" name="service[]" id="'+ scdata.one_services[index].id +'" value="'+ formatMoney(sv_amount) +'" style="pointer-events: none;" />'+
									'<input type="hidden" class="form-control" name="service_id[]" id="'+ scdata.one_services[index].id +'" value="'+ scdata.one_services[index].id +'" />'+
								'</div>';
						box1 = 
								'<div class="form-group">'+
									'<label> <?= lang("customer_payment", "service_payment"); ?> </label>'+ 
									'<input type="text" class="form-control" name="service_payment" id="service_payment" value="'+ formatMoney(scdata.service) +'" style="pointer-events: none;" />'+
								'</div>';
								
					});
					
					$('#services').html(box);
					$('#total_service_payment').html(box1);
				},
				error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                }
			});
			
		});		
	});
	
	$(document).ready(function (){
		$('#amount').keyup(function(){
			var amount = $('#amount').val()? parseFloat($('#amount').val()) : 0;			
			var grand_total = $('#grand_total').val();
			var branch_amount = $('#branch_amount').val()? parseFloat($('#branch_amount').val()) : 0;			
			if (amount > grand_total && amount < branch_amount && branch_amount > 0){
				$('#amount').val(formatMoney(grand_total));	
			}
			if (amount > grand_total && amount > branch_amount && branch_amount > 0){
				$('#amount').val(formatMoney(branch_amount));	
			}
			if (amount > branch_amount && branch_amount > 0){
				$('#amount').val(formatMoney(branch_amount));	
			}
			if (amount > grand_total && grand_total < branch_amount && branch_amount > 0){
				$('#amount').val(formatMoney(grand_total));	
			}
			if ( amount > grand_total && grand_total > branch_amount && branch_amount > 0){
				$('#amount').val(formatMoney(branch_amount));	
			}
			if(branch_amount <= 0){
				$('#amount').val(0);
			}
		});
	});
	
	$(document).ready(function (){
		$('#payment_schedule').live( "click", function() {
			var id = $('#contract_id').val();
			var sale_id = id.split('#');
			$('#sale_id').val(sale_id[0]);			
			var sale_id = $('#sale_id').val();
			var link= $('<a href="Installment_payment/payment_schedule/'+ null +'/'+ null +'/'+sale_id+'" rel="lightbox" id="print_payment'+count_link+'" data-toggle="modal" data-target="#myModal"></a>');
			$("body").append(link);
			$('#print_payment'+count_link).click();
		});		
	});
</script>

<script type="text/javascript">
	$(document).ready(function (){
		$('#bank_account, #contract_id').on("change", function(){
			var branch_amt = $('#bank_account').val();
			var branch_amount = branch_amt.split('#');			
			var br_amount = branch_amount[1];
			var none = '';
			var sale_rate = $('#sale_rate').val()? parseFloat($('#sale_rate').val()) : 0;
			var df_rate = $('#df_rate').val()? parseFloat($('#df_rate').val()) : 0;			
			var branch_amounts = convertCurrency(sale_rate, df_rate, br_amount);
			$('#branch_amount').val(branch_amounts);
			$('#amount').val(none);
		});
	});
	
</script>
