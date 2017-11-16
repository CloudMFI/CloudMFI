<?php //echo $this->erp->print_arrays($identify_type) ?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_shareholder'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("shareholder/insert", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
						<?= lang("identify_type", "jl_identify_type"); ?>
						<?php
							$ident_all[(isset($_POST['jl_identify_id']) ? $_POST['jl_identify_id'] : '')] = (isset($_POST['jl_identify_id']) ? $_POST['jl_identify_id'] : '');
							if(array($identify_type)) {
								foreach($identify_type as $ident_){
									$ident_all[$ident_->id] = $ident_->name;
								}
							}
							echo form_dropdown('jl_identify_id', $ident_all, '', 'class="form-control jl_identify_type" id="jl_identify_type" placeholder="' . lang("select_identify_to_load") . '" data-bv-notempty="true"');																					
						?>
					</div>
                </div>
				<div class="col-md-6">
                    <div class="form-group">	
						<label id="jl_identify" for="jl_gov_id"></label>
						<input type="hidden" name="jl_identify" id="jl_identify" class="jl_identify"  />																			
						<?php echo form_input('jl_gov_id', (isset($_POST['jl_gov_id']) ? $_POST['jl_gov_id'] : ''), 'class="form-control" id="jl_gov_id" data-bv-notempty="true"'); ?>
					</div>
                </div>
				<div class="col-md-6">
                    <div class="form-group">	
						<?= lang("name", "name"); ?>
						<?php echo form_input('shareholder_name', (isset($_POST['shareholder_name']) ? $_POST['shareholder_name'] : ''), 'class="form-control" id="shareholder_name" required="required"' ); ?>
					</div>
                </div>
				<div class="col-md-6">
					<div class="form-group">
						<?= lang("date_of_birth", "jl_dob"); ?>
						<?php echo form_input('jl_dob', (isset($_POST['jl_dob']) ? $_POST['jl_dob'] : ''), 'class="form-control date" id="jl_dob" data-bv-notempty="true"'); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<?= lang("phone", "phone"); ?>
						<input type="tel" name="phone" class="form-control number_only" maxlength="10" id="phone" value="<?php (isset($_POST['phone']) ? $_POST['phone'] : '') ?>"/>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<?= lang("age", "jl_age"); ?>
						<?php echo form_input('jl_age', (isset($_POST['jl_age']) ? $_POST['jl_age'] : ''), 'class="form-control date" id="jl_age" style="pointer-events: none;"'); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<?php echo lang('email', 'email'); ?>
						<div class="controls">
							<input type="email" id="email" name="email" class="form-control" required="required"/>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<?= lang("place_of_birth", "cus_pob"); ?>
						<?php echo form_input('cus_pob', (isset($_POST['cus_pob']) ? $_POST['cus_pob'] : ''), 'class="form-control" id="cus_pob"'); ?>
					</div>
				</div>
				<div class="col-md-12 show_cash">
					<div class="form-group all">
						<?= lang('address', 'address'); ?>
						<textarea name="ldescription" id="ldescription"
								  class="pa form-control kb-text ldescription"></textarea>
					</div>
				</div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_shareholder', lang('add_shareholder'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<!-- Filter Address --->
<script type="text/javascript">
	function getOrderSelect(url, child_obj, lang, pholder) {
		$('#modal-loading').show();
		$.ajax({
			type: "get",
			async: false,
			url: url,
			dataType: "json",
			success: function (scdata) {
				if (scdata != null) {
					$("#"+child_obj).select2("destroy").empty().attr("placeholder", lang).select2({
						placeholder: pholder,
						data: scdata
					});
				}
			},
			error: function () {
				bootbox.alert('<?= lang('ajax_error') ?>');
				$('#modal-loading').hide();
			}
		});
		$('#modal-loading').hide();
	}
	$(document).ready(function() {/*  ---------- Select all Provinces When Form Load ----------- */
		$(window).load(function() {
			var url = "<?= site_url('quotes/getProvinces') ?>";
			var child_obj = 'cus_province';
			var child_emp_province = 'emp_province';
			var child_province = 'province';
			var lang = '<?= lang('select_province') ?>';
			var pholder = '<?= lang('select_province_to_load') ?>';
			getSelected2By3Child(child_obj, child_emp_province, child_province, url, lang, pholder);
		});
		
		/* --------------- Filter Provinces By Country -------------- */
		$('#cus_country').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getProvinces') ?>/" + v;
			var child_obj = 'cus_province';
			var lang = '<?= lang('select_province') ?>';
			var pholder = '<?= lang('select_province_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		$('#emp_country').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getProvinces') ?>/" + v;
			var child_obj = 'emp_province';
			var lang = '<?= lang('select_province') ?>';
			var pholder = '<?= lang('select_province_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		$('#country').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getProvinces') ?>/" + v;
			var child_obj = 'province';
			var lang = '<?= lang('select_province') ?>';
			var pholder = '<?= lang('select_province_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		
		/* --------------- Filter Districts By Province -------------- */
		$('#cus_province').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getDistricts') ?>/" + v;
			var child_obj = 'cus_district';
			var lang = '<?= lang('select_district') ?>';
			var pholder = '<?= lang('select_district_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		$('#emp_province').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getDistricts') ?>/" + v;
			var child_obj = 'emp_district';
			var lang = '<?= lang('select_district') ?>';
			var pholder = '<?= lang('select_district_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		$('#province').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getDistricts') ?>/" + v;
			var child_obj = 'district';
			var lang = '<?= lang('select_district') ?>';
			var pholder = '<?= lang('select_district_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		
		/* --------------- Filter Communces By District -------------- */
		$('#cus_district').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getCommunces') ?>/" + v;
			var child_obj = 'cus_communce';
			var lang = '<?= lang('select_communce') ?>';
			var pholder = '<?= lang('select_communce_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		$('#emp_district').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getCommunces') ?>/" + v;
			var child_obj = 'emp_communce';
			var lang = '<?= lang('select_communce') ?>';
			var pholder = '<?= lang('select_communce_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		$('#district').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getCommunces') ?>/" + v;
			var child_obj = 'communce';
			var lang = '<?= lang('select_communce') ?>';
			var pholder = '<?= lang('select_communce_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		
		/* --------------- Filter Villages By Communce -------------- */
		$('#cus_communce').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getVillages') ?>/" + v;
			var child_obj = 'cus_village';
			var lang = '<?= lang('select_village') ?>';
			var pholder = '<?= lang('select_village_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		$('#emp_communce').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getVillages') ?>/" + v;
			var child_obj = 'emp_village';
			var lang = '<?= lang('select_village') ?>';
			var pholder = '<?= lang('select_village_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		$('#communce').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getVillages') ?>/" + v;
			var child_obj = 'village';
			var lang = '<?= lang('select_village') ?>';
			var pholder = '<?= lang('select_village_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
	});
</script>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#biller_logo').change(function (event) {
            var biller_logo = $(this).val();
            $('#logo-con').html('<img src="<?=base_url('assets/uploads/logos')?>/' + biller_logo + '" alt="">');
        });
		/* --------------- Get All Province ------------------ */
		var url = "<?= site_url('branch/getProvinces') ?>";
		var child_province = 'province';
		var lang = '<?= lang('select_province') ?>';
		var pholder = '<?= lang('select_province_to_load') ?>';
		getOrderSelect(url, child_province, lang, pholder);
    });
	
	$('#jl_identify_type').live('change', function() {
		var id_type = $("#jl_identify_type option:selected").text();
		$('#jl_identify').val(id_type);
		$('#jl_identify').text(id_type);
	});
	$('#jl_dob').live('change', function() {
		var dob = $(this).val();
		if(dob != '') {
			var age = getAge(dob);
			$('#jl_age').val(age +' Year old');
		}else {
			$('#jl_age').val('');
		}
	});
</script>
<?= $modal_js ?>
