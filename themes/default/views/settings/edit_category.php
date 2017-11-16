<script type="text/javascript">
	$('#code').attr("disabled",true);
</script>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_category'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/edit_category/" . $id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('update_info'); ?></p>

            <div class="form-group">
                <?php echo lang('category_code', 'code'); ?>
                <div class="controls">
                    <?php echo form_input($code); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo lang('category_name', 'name'); ?>
                <div class="controls">
                    <?php echo form_input($name); ?>
                </div>
            </div>
			<div class="form-group" style="display:none;">
				<?= lang("gruop", "gruop"); ?>
				<div class="form-group" style="border: solid 1px #ccc; height:40px; padding:10px;">
					<div class="col-sm-4">								
						<input type="checkbox" value="1" name="group_loan" id="group_loan" <?= ($group_loan? 'checked':''); ?> />
						<b>Group Loans</b>
					</div>		
				</div>
			</div>
            <div class="form-group">
                <?= lang("category_image", "image") ?>
                <input id="image" type="file" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="form-control file">
            </div>
			<div class="form-group">
                <input type="checkbox" value="1" name="mfi" id="mfi" <?= ($mfi? 'checked':''); ?> /><span style="padding-left:10px;"><?= lang('mfi'); ?></span>
            </div>
            <?php echo form_hidden('id', $id); ?>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_category', lang('edit_category'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>