<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_model'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/add_type", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <?php echo lang('type', 'subcategory'); ?>
                <div class="controls"> <?php
                    $ct[$parent_id] = $this->lang->line("select") . " " . $this->lang->line("type");
                    foreach ($subcategories as $subcategory) {
                        $ct[$subcategory->id] = $subcategory->name;
                    }
                    echo form_dropdown('subcategory', $ct, (isset($_POST['subcategory']) ? $_POST['subcategory'] : $parent_id), 'class="form-control select" id="subcategory" required="required"');
                    ?> </div>
            </div>
            <div class="form-group">
                <?php echo lang('model_code', 'code'); ?>
                <div class="controls">
                    <?php echo form_input($code); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo lang('model_name', 'name'); ?>
                <div class="controls">
                    <?php echo form_input($name); ?>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_type', lang('add_model'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>