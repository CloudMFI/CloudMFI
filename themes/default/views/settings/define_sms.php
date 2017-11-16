<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('define_sms'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/define_sms", $attrib); ?>
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
						echo form_dropdown('cust_type', $cust_type, "", 'id="cust_type" class="form-control input-tip select" style="width:100%;"'); 
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
						echo form_dropdown('by_dealer', $by_dealer, "", 'id="by_dealer" class="form-control input-tip select" style="width:100%;"'); 
					?> 
				</div>
            </div>
            <div class="form-group">
                <label class="control-label" for="send_date"><?php echo $this->lang->line("send_date"); ?></label>
                <div class="controls"> <?php echo form_input('send_date', '', 'class="form-control datetime" id="send_date"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="due_days"><?php echo $this->lang->line("send_by_due_days"); ?></label>
                <div class="controls"> <?php echo form_input('due_days', '', 'class="form-control" id="due_days"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="message"><?php echo $this->lang->line("message"); ?></label>

                <div class="controls"> <?php echo form_textarea('message', '', 'class="form-control" id="message" required="required"'); ?> </div>
            </div>
			<!--
            <div class="form-group">
                <label class="control-label" for="code"><?php echo $this->lang->line("code"); ?></label>

                <div class="controls"> <?php echo form_input('code', '', 'class="form-control" id="code" required="required"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="name"><?php echo $this->lang->line("name"); ?></label>

                <div
                    class="controls"> <?php echo form_input('name', '', 'class="form-control" id="name" required="required"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="phone"><?php echo $this->lang->line("phone"); ?></label>

                <div class="controls"> <?php echo form_input('phone', '', 'class="form-control" id="phone"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="email"><?php echo $this->lang->line("email"); ?></label>

                <div class="controls"> <?php echo form_input('email', '', 'class="form-control" id="email"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="address"><?php echo $this->lang->line("address"); ?></label>

                <div
                    class="controls"> <?php echo form_textarea('address', '', 'class="form-control" id="address" required="required"'); ?> </div>
            </div>
            <div class="form-group">
                <?= lang("warehouse_map", "image") ?>
                <input id="image" type="file" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="form-control file">
            </div>
			-->
        </div>
        <div class="modal-footer">
            <?php echo form_submit('define_sms', lang('save'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>
