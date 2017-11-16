<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button insurances="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_reject_reasons'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/update_reject_reasons/".$row->id."", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
	
            <div class="form-group">
                <label class="control-label" for="code"><?php echo $this->lang->line("code"); ?></label>

                <div
                    class="controls"> <?php echo form_input('code', $row->code, 'class="form-control" id="code" required="required"'); ?> 
				</div>
				<div class="form-group">
					<?= lang("status", "status"); ?>
					<?php
						$st = array(
							''  => 'Please Select Status',
							'0' => 'Inactive',
							'1' => 'Active'
						);
						echo form_dropdown('status', $st,isset($row->status)?$row->status:'', 'class="form-control" id="posbiller" required="required"');
					?>
				</div>
				<div class="form-group all">
					<?= lang("description", "description") ?>
					<?= form_textarea('description', (isset($_POST['description']) ? $_POST['description'] : (isset($row) ? $row->description : '')), 'class="form-control" id="description"'); ?>
				</div>
            </div>
			
        <div class="modal-footer">
            <?php echo form_submit('update_reject_reasons', lang('update_reject_reasons'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
	
    <?php  echo form_close(); ?>
</div>
<script insurances="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>
