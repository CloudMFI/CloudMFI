<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('define_sms'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/editSMS/".$sms->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
            <div class="form-group">
                <label class="control-label" for="cust_type"><?php echo $this->lang->line("choose_customer_type"); ?></label>
				<div class="controls"> 
					<?php 
						$cust_type = array(
											'' => lang(''),
											'applicant' => lang('applicant'),
											'approved' => lang('approved'),
											'approved_condition' => lang('approved_condition'),
											'pending_po' => lang('pending_po'),
											'rejected' => lang('rejected'),
											'activated' => lang('activated'),
											'overdue' => lang('overdue'),
										  );
						echo form_dropdown('cust_type', $cust_type, ($sms? $sms->customer_type:''), 'id="cust_type" class="form-control input-tip select" style="width:100%;"'); 
					?> 
				</div>
            </div>
            <div class="form-group">
                <label class="control-label" for="by_dealer"><?php echo $this->lang->line("choose_by_dealer"); ?></label>
				<div class="controls"> 
					<?php 
						$by_dealer[''] = '';
						foreach($dealers as $dealer) {
							$by_dealer[$dealer->id] = $dealer->name;
						}
						echo form_dropdown('by_dealer', $by_dealer, ($sms? $sms->dealer_id:''), 'id="by_dealer" class="form-control input-tip select" style="width:100%;"'); 
					?> 
				</div>
            </div>
            <div class="form-group">
                <label class="control-label" for="send_date"><?php echo $this->lang->line("send_date"); ?></label>
                <div class="controls"> <?php echo form_input('send_date', ($sms? $this->erp->hrld($sms->send_date):''), 'class="form-control datetime" id="send_date"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="due_days"><?php echo $this->lang->line("send_by_due_days"); ?></label>
                <div class="controls"> <?php echo form_input('due_days', ($sms? $sms->overdue_days:''), 'class="form-control" id="due_days"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="message"><?php echo $this->lang->line("message"); ?></label>

                <div class="controls"> <?php echo form_textarea('message', ($sms? $sms->message:''), 'class="form-control" id="message" required="required"'); ?> </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('editSMS', lang('save'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>
