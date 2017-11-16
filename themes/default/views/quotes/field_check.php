<script type="text/javascript">
    $(function () {
        $('.bcc').hide();
        $(".toggle_form").slideDown('hide');
        $('.toggle_form').click(function () {
            $("#bcc").slideToggle();
            return false;
        });
    });
</script>
<link href="<?= $assets ?>styles/theme.css" rel="stylesheet" />
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('field_check'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open("quotes/email/" . $id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <?= lang("currenct_address_map", "document") ?>
				<input id="document" type="file" name="document" data-show-upload="false"
					   data-show-preview="false" class="form-control file">
            </div>
			<div class="form-group">
                <?= lang("family_book", "document") ?>
				<input id="document" type="file" name="document" data-show-upload="false"
					   data-show-preview="false" class="form-control file">
            </div>
			<div class="form-group">
                <?= lang("gavernmanet_id", "document") ?>
				<input id="document" type="file" name="document" data-show-upload="false"
					   data-show-preview="false" class="form-control file">
            </div>
			<div class="form-group">
                <?= lang("house_photo", "document") ?>
				<input id="document" type="file" name="document" data-show-upload="false"
					   data-show-preview="false" class="form-control file">
            </div>
			<div class="form-group">
                <?= lang("store_photo", "document") ?>
				<input id="document" type="file" name="document" data-show-upload="false"
					   data-show-preview="false" class="form-control file">
            </div>
			<div class="form-group">
                <?= lang("employment_certificate", "document") ?>
				<input id="document" type="file" name="document" data-show-upload="false"
					   data-show-preview="false" class="form-control file">
            </div>
			<div class="form-group">
                <?= lang("other_ducument", "document") ?>
				<input id="document" type="file" name="document" data-show-upload="false"
					   data-show-preview="false" class="form-control file">
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('submit', lang('submit'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
