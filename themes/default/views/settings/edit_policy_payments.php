<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button insurances="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_days'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/update_policy_payments/".$row->id."", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
	
            <div class="form-group">
                <div class="form-group all">
					<?= lang("policy_payments_days", "days") ?>
					<?= form_input('days', (isset($_POST['days']) ? $_POST['days'] : (isset($row) ? $row->days : '')), 'class="form-control" id="days"'); ?>
				</div>
				
            </div>
			
        <div class="modal-footer">
            <?php echo form_submit('update_policy_payments', lang('update_policy_payments'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
	
    <?php  echo form_close(); ?>
</div>
<script insurances="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>
