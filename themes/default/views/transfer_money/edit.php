<?php
	//$this->erp->print_arrays($transfer);
?>
<script>
$(window).load(function() {
	$('#reference').attr("readonly",true);
	$("#shareholder").trigger('change');
	$("#branch").trigger('change')
});
</script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_transfer'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("transfer_money/update/".$transfer->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

				<div class="col-sm-12">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= lang('edit_transfer') ?></div>
						<div class="panel-body" style="padding: 5px;">            
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-6">
										<div class="form-group company">
											<?= lang("reference", "reference"); ?>
											<?php echo form_input('reference',(isset($transfer->reference) ? $transfer->reference : ''), 'class="form-control tip" id="reference" data-bv-notempty="true" readonly="true"'); ?>
										</div>
									   
									</div>
									<div class="col-md-6">										
										<div class="form-group person">
											<?= lang("date", "date"); ?>
											<?php echo form_input('date',(isset($transfer->date) ? $this->erp->hrsd($transfer->date) : ''), 'class="form-control date" id="date" data-bv-notempty="true"'); ?>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-6">
										<div class="form-group company">
											<?= lang("from_branch", "from_branch"); ?>
											<?php
												$all_branch[(isset($_POST['from_branch']) ? $_POST['from_branch'] : '')] = (isset($_POST['from_branch']) ? $_POST['from_branch'] : '');
												if(array($branchs)) {
													foreach($branchs as $branch) {
														$all_branch[$branch->id] = $branch->name;
													}
												}
												echo form_dropdown('from_branch', $all_branch, (isset($transfer->from_branch_id ) ? $transfer->from_branch_id : ''), 'id="from_branch" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("branch") . '" required="required" style="width:100%;" ');
											?>
										</div>
									   
									</div>
									<div class="col-md-6">										
										<div class="form-group">
											<?= lang("to_branch", "to_branch"); ?>
											<?php
												$all_branch[(isset($_POST['to_branch']) ? $_POST['to_branch'] : '')] = (isset($_POST['to_branch']) ? $_POST['to_branch'] : '');
												if(array($branchs)) {
													foreach($branchs as $branch) {
														$all_branch[$branch->id] = $branch->name;
													}
												}
												echo form_dropdown('to_branch', $all_branch,(isset($transfer->to_branch_id) ? $transfer->to_branch_id : ''), 'id="to_branch" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("branch") . '" required="required" style="width:100%;" ');
											?>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-6">										
										<div class="form-group person">
											<?= lang("cash_out", "bank_account"); ?>
											<?php
												$all_bank[(isset($_POST['bank_account']) ? $_POST['bank_account'] : '')] = (isset($_POST['bank_account']) ? $_POST['bank_account'] : '');
												if(array($banks)) {
													foreach($banks as $bank) {
														$all_bank[$bank->accountcode] = $bank->accountname;
													}
												}
												echo form_dropdown('bank_account', $all_bank, (isset($transfer->bank_account) ? $transfer->bank_account : ''), 'id="bank_account" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("bank_account") . '"  style="width:100%;" required="required"');
											?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group company">
											<?= lang("amount", "amount"); ?>
											<?php echo form_input('amount', $this->erp->formatDecimal($transfer->amount), 'class="form-control tip number_only_" id="amount" data-bv-notempty="true"'); ?>
										</div>
										<input type="hidden" name="bramount" id="bramount" />
									    <input type="hidden" name="branch_id" id="branch_id" />
									    <input type="hidden" name="bank_code" id="bank_code" />
									   
									</div>									
								</div>
							</div>							
						</div>
					</div>		
				</div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_transfer', lang('edit_transfer'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript" charset="utf-8">

	$(window).load(function() {
		$("#bank_account").trigger('change');
		$("#from_branch").trigger('change');
	});
	
    $(document).ready(function () {
		$('#bank_account').change(function(){
			var branch_id = $('#from_branch').val();
			var bank_code = $('#bank_account').val();
			var none = '';
			$('#branch_id').val(branch_id);
			$('#bank_code').val(bank_code);
			$.ajax({
				url: site.base_url + 'transfer_money/ajaxBranchBalance/'+ branch_id +'/' + bank_code,
				dataType: 'json',
				success: function(scdata){
					$('#bramount').val(scdata.amount);
				},
				error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                }
			});
		}).trigger('change');
		
		$('#amount').keyup(function(){
			var amount = $('#amount').val()? parseFloat($('#amount').val()) : 0;
			var bramount = $('#bramount').val()? parseFloat($('#bramount').val()) : 0;
			if (amount > bramount){
				$('#amount').val(formatDecimal(bramount));	
			}					
		});
		
        $('#biller_logo').change(function (event) {
            var biller_logo = $(this).val();
            $('#logo-con').html('<img src="<?=base_url('assets/uploads/logos')?>/' + biller_logo + '" alt="">');
        });
		/*----Amount----
		$('#amount').live('change', function(e) {
			var price = $(this).val().toLowerCase();
			var amount = 0;
			var new_amount = 0; 
			if(price.search('k') > 0) {
				amount = price.split('k');
				new_amount = parseFloat(amount[0] * 1000);
			}else if(price.search('m') > 0) {
				amount = price.split('m');
				new_amount = parseFloat(amount[0] * 1000000);
			}else {
				amt = price - 0;
				if(!Number(amt)) {
					new_amount = 0;
				}else {
					new_amount = price;
				}
			}
			$(this).val(new_amount);
		});*/
    });
</script>
<?= $modal_js ?>
