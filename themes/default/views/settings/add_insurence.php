<?php
	//$this->erp->print_arrays($service_income);
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_insurence'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/add_insurence", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			<div class="form-group">
                <?= lang("code", "code"); ?>
                <input name="code" type="text" id="code" value="" class="form-control" required="required"/>
            </div>
			<div class="form-group">
                <?= lang("amount", "amount"); ?>
                <input name="amount" type="text" id="amount" value="" class="form-control" required="required"/>
				<input type="hidden" name="paid_status" id="paid_status" value="Insurence" />
				<input type="hidden" name="service_paid" id="service_paid" value="4" />
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
			<!--
			<div class="form-group all">
				<?= lang("paid_status", "paid_status"); ?>
				<div class="form-group all" style="border: solid 1px #ccc; height:40px; padding:10px;">
					<div class="col-sm-4">
						<b>One Time</b>		
						<input type="checkbox" name="one_time" id="one_time"/>
					</div>
					<div class="col-sm-4">
						<b>Haft of Term</b>	
						<input type="checkbox" name="haft_term" id="haft_term"/>
					</div>
					<div class="col-sm-4">
						<b>All Times</b>	
						<input type="checkbox" name="all_time" id="all_time"/>
					</div>					
				</div>
			</div> -->
			
			<div class="form-group all">
				<div class="form-group">
					<?= lang("default_service_income","default_service_income"); ?>
					<?php
						$acc_section = array(""=>"");
						$get_service_income = "";
						foreach($service_income as $service_income){
							$get_service_income = $service_income->accountname;
						}
						foreach($chart_accounts as $section){
							$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
						}
						echo form_dropdown('default_service_income', $acc_section, '' ,'id="default_service_income" class="form-control" placeholder="' . lang("select") . ' ' . lang("default_service_income") . '" style="width:100%;" required="required"');
					?>
				</div>
			</div>
			<div class="form-group all">
				<div class="form-group">
					<?= lang("tax","state_tax"); ?>
					<?php
						$tax[""] = "";
						if(is_array(isset($state_taxes) ?$state_taxes  : (''))){
						foreach ($state_taxes as $s_tax) {
							$tax[$s_tax->id] = $s_tax->name;
						}}
						echo form_dropdown('state_tax', isset($tax) ?$tax  : (''), (isset($_POST['state_tax']) ? $_POST['state_tax'] : ""), 'class="form-control" id="state_tax" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("tax") . '" required="required"');
					?>
				</div>
			</div>
			
			<div class="form-group all">
				<?= lang("description", "description") ?>
				<?= form_textarea('description', (isset($_POST['description']) ? $_POST['description'] : (isset($product) ? $product->description : '')), 'class="form-control" id="details"'); ?>
			</div>
			<div class="form-group all">
				<?= lang("description_other", "description_other") ?>
				<?= form_textarea('description_other', (isset($_POST['description_other']) ? $_POST['description_other'] : (isset($product) ? $product->description : '')), 'class="form-control" id="details"'); ?>
			</div>
		</div>
        <div class="modal-footer">
            <?php echo form_submit('add_financial', lang('add_financial'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>

<script>
 
$(document).ready(function(){

});
</script>
<?= $modal_js ?>