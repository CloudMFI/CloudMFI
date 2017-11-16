<?php //$this->erp->print_arrays($currency); ?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_capital'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("capital/insert", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			
				<div class="col-sm-12">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= lang('add_capital') ?></div>
						<div class="panel-body" style="padding: 5px;">            
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-6">
										<div class="form-group company">
											<?= lang("reference", "reference"); ?>
											<?php echo form_input('reference', $reference_cap, 'class="form-control tip" id="reference" data-bv-notempty="true" readonly="true"'); ?>
										</div>
									   
									</div>
									<div class="col-md-6">
										
										<div class="form-group person">
											<?= lang("date", "date"); ?>
											<?php echo form_input('date',(isset($_POST['date']) ? $_POST['date'] : date('d/m/Y')), 'class="form-control date" id="date" data-bv-notempty="true"'); ?>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-6">
										<div class="form-group company">
											<?= lang("shareholder", "shareholder"); ?>
											<?php
												$all_holder[(isset($_POST['shareholder']) ? $_POST['shareholder'] : '')] = (isset($_POST['shareholder']) ? $_POST['shareholder'] : '');
												if(array($shareholder)) {
													foreach($shareholder as $shareholder) {
														$all_holder[$shareholder->id] = $shareholder->name;
													}
												}
												echo form_dropdown('shareholder', $all_holder, (isset($_POST['shareholder']) ? $_POST['shareholder'] : ''), 'id="shareholder" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("shareholder") . '" required="required" style="width:100%;" ');
											?>
										</div>
									</div>
									<div class="col-md-6">
										
										<div class="form-group">
											<?= lang("branch", "branch"); ?>
											<?php
												$all_branch[(isset($_POST['branch']) ? $_POST['branch'] : '')] = (isset($_POST['branch']) ? $_POST['branch'] : '');
												if(array($branchs)) {
													foreach($branchs as $branch) {
														$all_branch[$branch->id] = $branch->name;
													}
												}
												echo form_dropdown('branch', $all_branch, (isset($_POST['branch']) ? $_POST['branch'] : ''), 'id="branch" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("branch") . '" required="required" style="width:100%;" ');
											?>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-6">
										<div class="form-group">
											<?php echo lang('currency', 'currency') ?>
											<?php
											$crr[(isset($_POST['currency']) ? $_POST['currency'] : '')] = (isset($_POST['currency']) ? $_POST['currency'] : '');
											if(array($currencies)) {
												foreach($currencies as $currency){
													$crr[$currency->code] = $currency->name;
												}
											}
											echo form_dropdown('currency', $crr, '', 'class="form-control currency" id="currency" placeholder="' . lang("select_currency") . '" required="required"');
											?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group company">
											<?= lang("amount", "amount") ?>
											<?php echo form_input('amount', '', 'class="form-control" id="amount" data-bv-notempty="true"'); ?>
										</div>									   
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-6">										
										<div class="form-group person">
											<?= lang("cash_in", "bank_account"); ?>
											<?php
												$all_bank[(isset($_POST['bank_account']) ? $_POST['bank_account'] : '')] = (isset($_POST['bank_account']) ? $_POST['bank_account'] : '');
												if(array($banks)) {
													foreach($banks as $bank) {
														$all_bank[$bank->accountcode] = $bank->accountname;
													}
												}
												echo form_dropdown('bank_account', $all_bank, (isset($_POST['bank_account']) ? $_POST['bank_account'] : ''), 'id="bank_account" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("bank_account") . '"  style="width:100%;" required="required"');
											?>
										</div>
									</div>
									<div class="col-md-6">
									 
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<?= lang("note", "note"); ?>
										<?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="note" style="margin-top: 10px; height: 100px;"'); ?>
									
									</div>
								</div>
							</div>
						</div>
					</div>		
				</div>			
				

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_capital', lang('add_capital'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>



<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#biller_logo').change(function (event) {
            var biller_logo = $(this).val();
            $('#logo-con').html('<img src="<?=base_url('assets/uploads/logos')?>/' + biller_logo + '" alt="">');
        });
    });
	/*----Amount----*/
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
	});
</script>
<?= $modal_js ?>
