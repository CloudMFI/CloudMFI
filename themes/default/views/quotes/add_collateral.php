<div class="modal-dialog" style="width:70%">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_collateral'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("quotes/add_collateral/" . $id, $attrib); ?>
        <div class="modal-body">
			<div class="row">
				<div class="col-md-6">													
					<div class="form-group">
						<?= lang("code", "cl_code"); ?>
						<?php echo form_input('cl_code', $reference_cl, 'class="form-control" id="cl_code" required="required"  readonly="readonly');?>
					</div>
					<div class="form-group">
					</div>
					<div class="form-group">
					</div>	
					<div class="form-group">
					</div>														
				</div>													
				<div class="col-md-6">
					<div class="form-group">
					<?= lang("type", "cl_type"); ?>
					<?php
						$cl_type[(isset($_POST['housing']) ? $_POST['housing'] : '')] = (isset($_POST['housing']) ? $_POST['housing'] : '');
																	
						if($collateral_type) {
							foreach($collateral_type as $c_type){
								$cl_type[$c_type->id] = $c_type->type;
							}
						}
						echo form_dropdown('cl_type', $cl_type, isset($customer->housing)?$customer->housing:'', 'class="form-control select" id="cl_type1" placeholder="' . lang("select") . ' ' . lang("type") . '" style="width:100%" ');
						?>
					</div>														
					<div class="form-group">
					</div>
					<div class="form-group">
					</div>															
				</div>
			</div>
			
			<!---Sethy ប្លង់ផ្ទះ-->													
			<div class="row" id="home">														
				<div class="col-md-6">
					<div class="form-group">
						<?= lang("type", "cl_home_type"); ?>
						<?php echo form_input('cl_home_type', (isset($_POST['cl_home_type']) ? $_POST['cl_home_type'] : ''), 'class="form-control" id="cl_home_type"');?>
					</div>	
					<div class="form-group">
						<?= lang("roof", "cl_roof"); ?>
						<?php echo form_input('cl_roof', (isset($_POST['cl_roof']) ? $_POST['cl_roof'] : ''), 'class="form-control" id="cl_roof"');?>
					</div>
					<div class="form-group">
						<?= lang("address", "cl_home_address"); ?>
						<?php echo form_textarea('cl_home_address', (isset($_POST['cl_home_address']) ? $_POST['cl_home_address'] : ""), 'class="form-control" id="cl_home_address" style="margin-top: 10px; height: 100px;"'); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<?= lang("size", "cl_home_size"); ?>
						<?php echo form_input('cl_home_size', (isset($_POST['cl_home_size']) ? $_POST['cl_home_size'] : ''), 'class="form-control" id="cl_home_size"');?>
					</div>
					<div class="form-group">
						<?= lang("wall", "cl_wall"); ?>
						<?php echo form_input('cl_wall', (isset($_POST['cl_wall']) ? $_POST['cl_wall'] : ''), 'class="form-control" id="cl_wall"');?>
					</div>
					<div class="form-group">
						<?= lang("owner_name", "home_owner_name"); ?>
						<?php echo form_input('home_owner_name', (isset($_POST['home_owner_name']) ? $_POST['home_owner_name'] : ''), 'class="form-control" id="home_owner_name"');?>
					</div>
					<div class="form-group">
						<?= lang("issue_date", "home_issue_date"); ?>
						<?php echo form_input('home_issue_date', (isset($_POST['home_issue_date']) ? $_POST['home_issue_date'] : ''), 'class="form-control date" id="home_issue_date"');?>
					</div>
				</div>
				
			</div>		
			
			<!---Sethy ប្លង់ដី-->												
			<div class="row" id="land">													
				<div class="col-md-6">													
					<div class="form-group">
						<?= lang("type", "cl_land_type"); ?>
						<?php echo form_input('cl_land_type', (isset($_POST['cl_land_type']) ? $_POST['cl_land_type'] : ''), 'class="form-control" id="cl_land_type"');?>
					</div>														
					<div class="form-group">
						<?= lang("adjacent_north", "cl_north"); ?>
						<?php echo form_input('cl_north', (isset($_POST['cl_north']) ? $_POST['cl_north'] : ''), 'class="form-control" id="cl_north"');?>
					</div>	
					<div class="form-group">
						<?= lang("adjacent_east", "cl_east"); ?>
						<?php echo form_input('cl_east', (isset($_POST['cl_east']) ? $_POST['cl_east'] : ''), 'class="form-control" id="cl_east"');?>
					</div>
					<div class="form-group">
						<?= lang("owner_name", "land_owner_name"); ?>
						<?php echo form_input('land_owner_name', (isset($_POST['land_owner_name']) ? $_POST['land_owner_name'] : ''), 'class="form-control" id="land_owner_name"');?>
					</div>
					<div class="form-group">
						<?= lang("address", "cl_land_address"); ?>
						<?php echo form_textarea('cl_land_address', (isset($_POST['cl_land_address']) ? $_POST['cl_land_address'] : ""), 'class="form-control" id="cl_land_address" style="margin-top: 10px; height: 100px;"'); ?>
					</div>					
				</div>
				
				<div class="col-md-6">
					<div class="form-group">
						<?= lang("size", "cl_land_size"); ?>
						<?php echo form_input('cl_land_size', (isset($_POST['cl_size']) ? $_POST['cl_size'] : ''), 'class="form-control" id="cl_land_size"');?>
					</div>
					<div class="form-group">
						<?= lang("adjacent_south", "cl_south"); ?>
						<?php echo form_input('cl_south', (isset($_POST['cl_south']) ? $_POST['cl_south'] : ''), 'class="form-control" id="cl_south"');?>
					</div>
					<div class="form-group">
						<?= lang("adjacent_west", "cl_west"); ?>
						<?php echo form_input('cl_west', (isset($_POST['cl_west']) ? $_POST['cl_west'] : ''), 'class="form-control" id="cl_west"');?>
					</div>
					<div class="form-group">
						<?= lang("title_number", "cl_card_number"); ?>
						<?php echo form_input('cl_card_number', (isset($_POST['cl_card_number']) ? $_POST['cl_card_number'] : ''), 'class="form-control" id="cl_card_number"');?>
					</div>
					<div class="form-group">
						<?= lang("issue_date", "land_issue_date"); ?>
						<?php echo form_input('land_issue_date', (isset($_POST['land_issue_date']) ? $_POST['land_issue_date'] : ''), 'class="form-control date" id="land_issue_date"');?>
					</div>
					
				</div>
			</div>
			<!--vehicles-->
			<div class="row" id="vehicles">														
				<div class="col-md-6">
					<div class="form-group">
						<?= lang("type", "vcl_vehicles_type"); ?>
						<?php echo form_input('vcl_vehicles_type', (isset($_POST['vcl_vehicles_type']) ? $_POST['vcl_vehicles_type'] : ''), 'class="form-control" id="vcl_vehicles_type"');?>
					</div>																																												
					<div class="form-group">
						<?= lang("power", "vcl_power"); ?>
						<?php echo form_input('vcl_power', (isset($_POST['vcl_power']) ? $_POST['vcl_power'] : ''), 'class="form-control" id="vcl_power"');?>
					</div>
					<div class="form-group">
						<?= lang("engine_number", "vcl_engine_no"); ?>
						<?php echo form_input('vcl_engine_no', (isset($_POST['vcl_engine_no']) ? $_POST['vcl_engine_no'] : ''), 'class="form-control" id="vcl_engine_no"');?>
					</div>															
					<div class="form-group">
						<?= lang("owner_name", "vcl_owner_name"); ?>
						<?php echo form_input('vcl_owner_name', (isset($_POST['vcl_owner_name']) ? $_POST['vcl_owner_name'] : ''), 'class="form-control" id="vcl_owner_name"');?>
					</div>
					<div class="form-group">
						<?= lang("issue_date", "vcl_issue_date"); ?>
						<?php echo form_input('vcl_issue_date', (isset($_POST['vcl_issue_date']) ? $_POST['vcl_issue_date'] : ''), 'class="form-control date" id="vcl_issue_date"');?>
					</div>															
				</div>
				<div class="col-md-6">															
					<div class="form-group">
						<?= lang("color", "vcl_color"); ?>
						<?php echo form_input('vcl_color', (isset($_POST['vcl_color']) ? $_POST['vcl_color'] : ''), 'class="form-control" id="vcl_color"');?>
					</div>
					<div class="form-group">
						<?= lang("brand", "vcl_brand"); ?>
						<?php echo form_input('vcl_brand', (isset($_POST['vcl_brand']) ? $_POST['vcl_brand'] : ''), 'class="form-control" id="vcl_brand"');?>
					</div>															
					<div class="form-group">
						<?= lang("frame_number", "vcl_frame_no"); ?>
						<?php echo form_input('vcl_frame_no', (isset($_POST['vcl_frame_no']) ? $_POST['vcl_frame_no'] : ''), 'class="form-control" id="vcl_frame_no"');?>
					</div>	
					<div class="form-group">
						<?= lang("plaque_number", "vcl_plaque_no"); ?>
						<?php echo form_input('vcl_plaque_no', (isset($_POST['vcl_plaque_no']) ? $_POST['vcl_plaque_no'] : ''), 'class="form-control" id="vcl_plaque_no"');?>
					</div>					
				</div>
				
			</div>
			
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_collateral', lang('save'), 'class="btn btn-primary" id="save"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>



<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>
<script type="text/javascript" charset="UTF-8">
	
	$('#land').hide();
	$('#home').hide();
	$('#vehicles').hide();
	
	$('#cl_type1').on('change', function() {
		if($('#cl_type1').val()=="1"){			
			$('#land').show();
			$('#home').hide();			
			$('#vehicles').hide();		
		}
		if($('#cl_type1').val()=="2"){					
			$('#land').hide();
			$('#home').show();			
			$('#vehicles').hide();
		}
		if($('#cl_type1').val()=="3"){			
			$('#land').hide();
			$('#home').hide();
			$('#vehicles').show();
		}
		
	});
</script>


