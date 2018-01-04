 

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_branch'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("branch/insert", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group company">
                        <?= lang("branch_code", "branch_code"); ?>
                        <?php echo form_input('branch_code', '', 'class="form-control tip" id="branch_code" data-bv-notempty="true"'); ?>
                    </div>
                   
                </div>
				<div class="col-md-6">
                    
                    <div class="form-group person">
                        <?= lang("branch_name", "branch_name"); ?>
                        <?php echo form_input('branch_name', '', 'class="form-control tip" id="branch_name" data-bv-notempty="true"'); ?>
                    </div>
                </div>
				
				<div class="col-md-6">
                    <div class="form-group company">
                        <?= lang("tel", "tel"); ?>
                        <?php echo form_input('tel', '', 'class="form-control tip" id="tel" data-bv-notempty="true"'); ?>
                    </div>
                </div>
				<div class="col-md-6">
                    <div class="form-group person">
                        <?= lang("e_mail", "e_mail"); ?>
                        <?php echo form_input('e_mail', '', 'class="form-control tip" id="e_mail" data-bv-notempty="true"'); ?>
                    </div>
                </div>
				<div class="col-md-6" style="display:none;">
					<div class="form-group">
						<?= lang("default_purchase","default_purchase"); ?>
						<?php 
							$acc_section = array(""=>"");
							$purchase = "";
							foreach($purchases as $buy){
								$purchase = $buy->accountname;
							}
							foreach($chart_accounts as $section){
								$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
							}
							echo form_dropdown('default_purchase', $acc_section, '' ,'id="default_purchase" class="form-control" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("chat_account") . '" style="width:100%;" multiple');
						?>
					</div>
				</div>
                
				<div class="col-sm-12">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= lang('address') ?></div>
						<div class="panel-body" style="padding: 5px;">
							<div class="col-md-6">
								<div class="form-group">
										<?= lang("country", "country"); ?>
										<?php
										foreach ($countries as $ct) {
											$country[$ct->code] = $ct->name;
										}
										echo form_dropdown('country', $country, (isset($branch->country) ? $branch->country : ''), 'class="form-control select" id="country" placeholder="' . lang("select") . ' ' . lang("country") . '" style="width:100%" data-bv-notempty="true"');
										?>
								</div>
								<div class="form-group">
									<?= lang("district", "district"); ?>
									<?php echo form_input('district', (isset($branch->district) ? $branch->district : ''), 'class="form-control" id="district"  placeholder="' . lang("select_district_to_load") . '"');?>
								</div>
								<div class="form-group">
									<?= lang("village", "village"); ?>
									<?php echo form_input('village', (isset($branch->village) ?$branch->village : ''), 'class="form-control" id="village"  placeholder="' . lang("select_village_to_load") . '"');?>
								</div>
								<div class="form-group">
									<?= lang("house_no", "house_no"); ?>
									<?php echo form_input('house_no', (isset($branch->house_no) ? $branch->house_no : ''), 'class="form-control" id="house_no"'); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?= lang("province", "province"); ?>
									<?php echo form_input('province', (isset($branch->state) ? $branch->state : ''), 'class="form-control" id="province"  placeholder="' . lang("select_province_to_load") . '"');?>
								</div>
								<div class="form-group">
									<?= lang("communce", "communce"); ?>
									<?php echo form_input('communce', (isset($branch->sangkat) ? $branch->sangkat : ''), 'class="form-control" id="communce"  placeholder="' . lang("select_communce_to_load") . '"');?>
								</div>
								<div class="form-group">
									<?= lang("street", "street"); ?>
									<?php echo form_input('street', (isset($branch->street) ? $branch->street : ''), 'class="form-control" id="street"'); ?>
								</div>
								<div class="form-group">
									<?= lang("group", "group"); ?>
									<?php echo form_input('group', (isset($branch->group) ? $branch->group : ''), 'class="form-control" id="group"'); ?>
								</div>								
							</div>
						</div>
					</div>
				</div>
				
            </div>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_branch', lang('add_branch'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<!-- Filter Address --->
<script type="text/javascript">

	$(window).load(function() {
		$("#country").trigger('change');
		$("#district").trigger('change');
		$("#village").trigger('change');  
		$('#province').trigger('change');
		$('#communce').trigger('change'); 
	}); 
	
	
	
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
	
	
</script>
<?= $modal_js ?>
