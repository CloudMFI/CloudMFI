<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_model'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/update_type/".$row->id."", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			<div class="form-group">
                <?php echo lang('type', 'subcategory'); ?>
                <div class="controls"> <?php
                    $ct[$row->subcategory_id] = $this->lang->line("select") . "". $this->lang->line("model");
                    foreach ($subcategories as $subcategory) {
                        $ct[$subcategory->id] = $subcategory->name;
                    }
                    echo form_dropdown('subcategory', $ct, (isset($_POST['subcategory']) ? $_POST['subcategory'] : isset($parent_id)?$parent_id:''), 'class="form-control select" id="subcategory" required="required"');
                    ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="code"><?php echo $this->lang->line("code"); ?></label>

                <div
                    class="controls"> <?php echo form_input('code', $row->code, 'class="form-control" id="code" required="required"'); ?> </div>
            </div>
			<div class="form-group">
                <label class="control-label" for="code"><?php echo $this->lang->line("name"); ?></label>

                <div
                    class="controls"> <?php echo form_input('name',$row->name , 'class="form-control" id="name" required="required"'); ?> </div>
            </div>
        <div class="modal-footer">
            <?php echo form_submit('update_type', lang('update_model'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
	
    <?php  echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>
