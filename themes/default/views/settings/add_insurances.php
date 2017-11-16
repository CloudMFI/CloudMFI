<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button insurances="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_model'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/insert_insurances", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group company">
                        <?= lang("company_name", "company_name"); ?>
                        <?php echo form_input('company_name', '', 'class="form-control tip" id="company_name" data-bv-notempty="true"'); ?>
                    </div>
                   
                </div>
			</div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add', lang('add'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script insurances="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>