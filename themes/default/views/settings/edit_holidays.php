<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button insurances="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_reject_reasons'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/update_holidays/".$row->id."", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
	
            <div class="form-group">
                <div class="form-group all">
					<?= lang("holiday_date", "holiday_date") ?>
					<?= form_input('holiday_date', (isset($_POST['holiday_date']) ? $_POST['holiday_date'] : (isset($row) ? $row->holiday_date : '')), 'class="form-control date" id="holiday_date"'); ?>
				</div>
				
				<div class="form-group all">
					<?= lang("description", "description") ?>
					<?= form_textarea('description', (isset($_POST['description']) ? $_POST['description'] : (isset($row) ? $row->descriptions : '')), 'class="form-control" id="description"'); ?>
				</div>
            </div>
			
        <div class="modal-footer">
            <?php echo form_submit('update_holidays', lang('update_holidays'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
	
    <?php  echo form_close(); ?>
</div>
<script insurances="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>
