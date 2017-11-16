<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('registration'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open("down_payment/register_form", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
                        <?php echo lang('engine_number', 'engine_number'); ?>
                        <div class="controls">
                            <?php echo form_input('engine_number', '', 'class="form-control" id="engine_number" required="required"'); ?>
                        </div>
                    </div>
				</div>
				<div class="col-sm-12">
					<div class="form-group">
                        <?php echo lang('frame_number', 'frame_number'); ?>
                        <div class="controls">
                            <?php echo form_input('frame_number', '', 'class="form-control" id="frame_number" required="required"'); ?>
                        </div>
                    </div>
				</div>
				<div class="col-sm-12">
					<div class="form-group">
                        <?php echo lang('flate_number', 'flate_number'); ?>
                        <div class="controls">
                            <?php echo form_input('flate_number', '', 'class="form-control" id="flate_number"'); ?>
                        </div>
                    </div>
				</div>
            </div>
        </div>
        <div class="modal-footer">
			<input type="hidden" name="id" id="id" value="<?= $id; ?>" />
            <?php echo form_submit('submit', lang('save'), 'class="btn btn-primary"'); ?>
        </div>
		<?php echo form_close(); ?>
    </div>
</div>



