<div class="modal-dialog modal-lg" style="width:60%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('cash_withdrawal'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'addAcc');
        echo form_open_multipart("saving/cash_withdrawal", $attrib); ?>
        <div class="modal-body">
            <div class="row">
				<div class="col-lg-12">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= lang('cash_withdrawal') ?></div>
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
										<?php echo form_input('reference', $reference_pp, 'class="form-control" id="reference" data-bv-notempty="true" readonly'); ?>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="col-md-6">
									<div class="form-group">
										<?= lang("saving_reference", "contract_id"); ?>
										<?php
										$all_contract[(isset($_POST['contract_id']) ? $_POST['contract_id'] : '')] = (isset($_POST['contract_id']) ? $_POST['contract_id'] : '');
										if(array($contracts)) {
											foreach($contracts as $contract){
												$all_contract[$contract->id .'#'.$contract->saving_balance ] = $contract->reference_no;
											}
										}
										echo form_dropdown('contract_id', $all_contract, '', 'class="form-control" id="contract_id" placeholder="' . lang("select_saving_ref") . '"');
										?>
									</div>
									  
								</div>
								<div class="col-md-6">									
									<div class="form-group">
										<?= lang("name", "name"); ?>
										<?php echo form_input('name', '' , 'class="form-control " id="name" style="pointer-events: none;" '); ?>
									</div>
									<input type="hidden" name="sale_id" id="sale_id" />
									<input type="hidden" name="saving_balances" id="saving_balances" />
									<input type="hidden" name="branch_amount" id="branch_amount" />
									<input type="hidden" name="df_rate" id="df_rate" />
									<input type="hidden" name="save_rate" id="save_rate" />
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
										<?= lang("available_balance", "available_balance"); ?>
										<?php echo form_input('available_balance', '' , 'class="form-control number_only" id="available_balance" style="pointer-events: none;"'); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<?= lang("cash_withdrawal", "cash_withdrawal"); ?>
										<?php echo form_input('cash_withdrawal', (isset($_POST['cash_withdrawal']) ? $_POST['cash_withdrawal'] : ''), 'class="form-control number_only" id="cash_withdrawal" data-bv-notempty="true"'); ?>
									</div>
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
			var saving_balance = contract_val[1]; 
			$('#sale_id').val(sale_id);
			$('#cash_withdrawal').val(null);
			$.ajax({
				url: site.base_url + 'saving/ajaxGetSavingBysaleID/'+sale_id,
				dataType: 'json',
				success: function(scdata){
					 
					$('#name').val(scdata.customer);	
					$('#saving_balances').val(scdata.balance);	
					$('#df_rate').val(scdata.def_rate);
					$('#save_rate').val(scdata.save_rate);
					$('#available_balance').val(scdata.balances); 
				},
				error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                }
			});
			
		});	


		$('#bank_account').on("change", function(){
			var branch_amt = $('#bank_account').val();
			var branch_amount = branch_amt.split('#');			
			var br_amount = branch_amount[1];
			var save_rate = $('#save_rate').val()? parseFloat($('#save_rate').val()) : 0;
			var df_rate = $('#df_rate').val()? parseFloat($('#df_rate').val()) : 0;			
			var branch_amounts = convertCurrency(save_rate, df_rate, br_amount);
			$('#branch_amount').val(branch_amounts);
			$('#cash_withdrawal').val(null);
		});
		
		
		$('#cash_withdrawal').keyup(function(){
			var amount = $('#cash_withdrawal').val()? parseFloat($('#cash_withdrawal').val()) : 0;	 
			var grand_total = $('#saving_balances').val()? parseFloat($('#saving_balances').val()) : 0; 
			var branch_amount = $('#branch_amount').val()? parseFloat($('#branch_amount').val()) : 0; 	
			if (amount > grand_total && amount < branch_amount && branch_amount > 0){
				$('#cash_withdrawal').val(formatMoney(grand_total));	
			}
			if (amount > grand_total && amount > branch_amount && branch_amount > 0){
				$('#cash_withdrawal').val(formatMoney(branch_amount));	
			}
			if (amount > branch_amount && branch_amount > 0){
				$('#cash_withdrawal').val(formatMoney(branch_amount));	
			}
			if (amount > grand_total && grand_total < branch_amount && branch_amount > 0){
				$('#cash_withdrawal').val(formatMoney(grand_total));	
			}
			if ( amount > grand_total && grand_total > branch_amount && branch_amount > 0){
				$('#cash_withdrawal').val(formatMoney(branch_amount));	
			}
			if(branch_amount <= 0){
				$('#cash_withdrawal').val(0);
			}
		});
		
	});
	
	 
</script>

 
 
 