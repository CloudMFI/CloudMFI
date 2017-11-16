<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button insurances="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_reject_reason'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/insert_reject_reason", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group company">
                        <?= lang("code", "reject_code"); ?>
                        <?php echo form_input('code', '', 'class="form-control tip" id="code" data-bv-notempty="true"'); ?>
                    </div>
					<div class="form-group">
						<?= lang("status", "status"); ?>
						<?php
							$bl = array(
								''  => 'Please Select Status',
								'0' => 'Inactive',
								'1' => 'Active'
							);
							echo form_dropdown('status', $bl, '', 'class="form-control" id="posbiller" required="required"');
						?>
					</div>
					<div class="form-group all">
						<?= lang("description", "description") ?>
						<?= form_textarea('description', (isset($_POST['description']) ? $_POST['description'] : (isset($product) ? $product->description : '')), 'class="form-control" id="description"'); ?>
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