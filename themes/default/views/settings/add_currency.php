<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_currency'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open("system_settings/add_currency", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <label class="control-label" for="code"><?php echo $this->lang->line("currency_code"); ?></label>

                <div
                    class="controls"> <?php echo form_input('code', '', 'class="form-control" id="code" required="required"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="name"><?php echo $this->lang->line("currency_name"); ?></label>

                <div
                    class="controls"> <?php echo form_input('name', '', 'class="form-control" id="name" required="required"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="name_other"><?php echo $this->lang->line("currency_name_other"); ?></label>

                <div
                    class="controls"> <?php echo form_input('name_other', '', 'class="form-control" id="name_other" required="required"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="rate"><?php echo $this->lang->line("exchange_rate"); ?></label>

                <div
                    class="controls"> <?php echo form_input('rate', '', 'class="form-control" id="rate" required="required"'); ?> </div>
            </div>
			
			<div class="form-group all">
				<?= lang("currency_type_*", "currency_type"); ?>
				<div class="form-group all" style="border: solid 1px #ccc; height:40px; padding:10px;">
					<div class="col-sm-4">
						<b>Hundred Round</b>		
						<input type="checkbox" name="hundred" id="hundred" value="1"/>
					</div>
					<div class="col-sm-4">
						<b>No Decimal</b>	
						<input type="checkbox" name="no_decimal" id="no_decimal" value="2"/>
					</div>
					<div class="col-sm-4">
						<b>Decimal Round</b>	
						<input type="checkbox" name="decimal" id="decimal" value="3"/>
					</div>					
				</div>
			</div> 
			
            <div class="form-group">
                <input type="checkbox" value="1" name="auto_update" id="auto_update">
                <label class="padding-left-10"
                       for="auto_update"><?php echo $this->lang->line("auto_update_rate"); ?></label>
            </div>
			 
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_currency', lang('add_currency'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
