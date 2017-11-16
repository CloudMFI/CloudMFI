
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x"> &times; </i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_transfer'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("transfer_money/insert", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			
				<div class="col-sm-12">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= lang('add_transfer') ?></div>
						<div class="panel-body" style="padding: 5px;">            
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-6">
										<div class="form-group company">
											<?= lang("reference", "reference"); ?>
											<?php echo form_input('reference', $reference_to, 'class="form-control tip" id="reference" data-bv-notempty="true" readonly="true"'); ?>
										</div>
									   
									</div>
									<div class="col-md-6">
										
										<div class="form-group person">
											<?= lang("date", "date"); ?>
											<?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : date('d/m/Y')), 'class="form-control date" id="date" data-bv-notempty="true"'); ?>
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
												echo form_dropdown('from_branch', $all_branch, (isset($_POST['from_branch']) ? $_POST['from_branch'] : ''), 'id="from_branch" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("branch") . '" required="required" style="width:100%;" ');
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
												echo form_dropdown('to_branch', $all_branch, (isset($_POST['to_branch']) ? $_POST['to_branch'] : ''), 'id="to_branch" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("branch") . '" required="required" style="width:100%;" ');
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
												echo form_dropdown('bank_account', $all_bank, (isset($_POST['bank_account']) ? $_POST['bank_account'] : ''), 'id="bank_account" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("bank_account") . '" style="width:100%;" required="required"');
											?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group company">
											<?= lang("amount", "amount"); ?>
											<?php echo form_input('amount', '', 'class="form-control tip number_only_" id="amount" data-bv-notempty="true"'); ?>
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
            <?php echo form_submit('add_transfer', lang('add_transfer'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>



<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {	
		$('#bank_account, #from_branch').change(function(){
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
					$('#amount').val(none);
				},
				error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                }
			});
		});
		
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
		$('#amount').change(function(){
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
