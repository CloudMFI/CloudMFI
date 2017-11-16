<?php// $this->erp->print_arrays($sales); ?>
<div class="modal-dialog modal-lg no-modal-header" style="width:60%; margin-top:150px;">
    <div class="modal-content">
		<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('cash_widrawal'); ?></h4>
        </div>
		<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("saving/cash_widrawal/" . $sales->id, $attrib); ?>
        <div class="modal-body">
            <?php echo form_open('saving', 'id="action-form"'); ?>
			<div class="row">
				<!---------------->
				<div class="col-lg-12">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= lang('cash_widrawal') ?></div>
							<?php echo form_open('saving', 'id="action-form"'); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-6">									
										<div class="form-group">
											<?= lang("widrawal_date", "widrawal_date"); ?>
											<?php echo form_input('widrawal_date', (isset($_POST['widrawal_date']) ? $_POST['widrawal_date'] : date('d/m/Y')), 'class="form-control date" id="widrawal_date" data-bv-notempty="true"'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("reference", "reference"); ?>
											<?php echo form_input('reference', $reference_pp, 'class="form-control" id="reference" data-bv-notempty="true" style="pointer-events: none;"'); ?>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="col-md-6">									
										<div class="form-group">
											<?= lang("name", "name"); ?>
											<?php echo form_input('name',$sales->customer, 'class="form-control " id="name" style="pointer-events: none;"'); ?>
										</div>
									</div>
									<div class="col-md-6">	
										<div class="form-group">
											<?= lang("payment", "payment"); ?>
											<?php echo form_input('payment','', 'class="form-control k_m_amount" id="payment" data-bv-notempty="true" data-bv-notempty="true"'); ?>
										</div>
									</div>
								</div>
								<div class="col-md-12">																
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("paid_by", "paid_by"); ?>
											<?php
											$pay_method[""] = "";
											$pay_method["cash"] = "Cash";
											$pay_method["wing"] = "Wing";
											$pay_method["Visa"] = "Visa Card";
											echo form_dropdown('paid_by', $pay_method, '', 'class="form-control select" id="paid_by" placeholder="' . lang("select") . ' ' . lang("pay_method") . '" style="width:100%" ');
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
												echo form_dropdown('bank_account', $all_bank, (isset($_POST['bank_account']) ? $_POST['bank_account'] : ''), 'id="bank_account" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("bank_account") . '" style="width:100%;" data-bv-notempty="true"');
											?>
										</div>
									</div>
								</div>
								
							</div>
					</div>
				</div>
				<!------------------->
			</div>
		</div>
		<div class="modal-footer">
            <?php echo form_submit('add_saving', lang('submit'), 'class="btn btn-primary" id="add_saving"'); ?>
        </div>
	</div>
	<?php echo form_close(); ?>
</div>
<?= $modal_js ?>
<script type="text/javascript">
	$(document).ready(function() {
		
	});
</script>