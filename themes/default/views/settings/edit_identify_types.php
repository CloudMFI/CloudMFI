<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button insurances="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_identify_types'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/update_identify_types/".$row->id."", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
	
            <div class="form-group">
                <label class="control-label" for="code"><?php echo $this->lang->line("identify_types"); ?></label>

                <div
                    class="controls"> <?php echo form_input('name', $row->name, 'class="form-control" id="name" required="required"'); ?> </div>
            </div>
			
        <div class="modal-footer">
            <?php echo form_submit('update_identify_types', lang('updateidentify_types'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
	
    <?php  echo form_close(); ?>
</div>
<script insurances="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>
