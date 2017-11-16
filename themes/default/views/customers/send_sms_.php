<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('send_sms'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        echo form_open_multipart("customers/add", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <label class="control-label"
                       for="customer_group"><?php echo $this->lang->line("to"); ?>
				</label>

                <div class="controls"> 
					<ul style="list-style: outside none none; border: 1px solid rgb(204, 204, 204); padding-top: 10px; padding-bottom: 10px; padding-left: 10px;" name="sms_to">
						<?php 
						if (!empty($phone)) {
							foreach ($phone as $SMS) 
							{ 
								echo '<li style="background: rgb(48, 113, 169) none repeat scroll 0% 0%; width: 50%; color: rgb(255, 255, 255); padding: 5px; margin-bottom: 5px;">';
									echo '<i aria-hidden="true" class="fa fa-times" id="clear" style="cursor:pointer;"></i>';
									echo $SMS->phone1;
								echo '</li>';
							}
						}
						?>
					</ul>
                </div>
            </div>
			
			<div class="row">
				<div class="col-sm-6">
					<?php echo form_submit('send', lang('send'), 'class="btn btn-primary"'); ?>
					<?php echo form_submit('saveDraft', lang('Save as Draft'), 'class="btn btn-default"'); ?>
				</div>
				<div class="col-sm-6">
					<?php echo form_submit('template', lang('Select SMS Template'), 'class="btn btn-default" style="float:right"'); ?>
				</div>
			</div><br/>
			
            <div class="form-group">
                <?= form_textarea('sms_content', '', 'class="form-control" id="sms_content"'); ?>
			</div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script>
	$("#sms_content").not('.skip').redactor({
        buttons: ["formatting", "|", "alignleft", "aligncenter", "alignright", "justify", "|", "bold", "italic", "underline", "|", "unorderedlist", "orderedlist", "|", "link", "|", "html"],
        formattingTags: ["p", "pre", "h3", "h4"],
        minHeight: 100,
        changeCallback: function(e) {
            var editor = this.$editor.next('#sms_content');
            if($(editor).attr('required')){
                $('form[data-toggle="validator"]').bootstrapValidator('revalidateField', $(editor).attr('name'));
            }
	   }
    });
</script>