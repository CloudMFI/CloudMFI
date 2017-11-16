
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('saving_register'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('enter_info'); ?></p>
				<div class="box-content">
					<div class="row">
						<div class="col-md-12">
							<ul id="dbTab" class="nav nav-tabs">
								<li class="" id="register_tap"><a href="#register"><?= lang('register') ?></a></li>								
								<li class="" id="saving_products_tap"><a href="#saving_products"><?= lang('saving_products') ?></a></li>
							</ul>
							<div class="tab-content">
								<?php
								$attrib = array('data-toggle' => 'validator', 'role' => 'form');
								echo form_open_multipart("saving/register", $attrib)
								?>
								<div id="register" class="tab-pane fade in">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<div class="row">
													<!---->
													<div class="col-lg-12">
														<div class="col-md-6">
															
															<div class="form-group">
																<?= lang("identify_type", "identify_type"); ?>
																<?php
																	$ident_all[(isset($_POST['identify_id']) ? $_POST['identify_id'] : '')] = (isset($_POST['identify_id']) ? $_POST['identify_id'] : '');
																	if(array($identify_type)) {
																		foreach($identify_type as $ident_){
																			$ident_all[$ident_->id] = $ident_->name;
																		}
																	}
																	echo form_dropdown('identify_id', $ident_all, '', 'class="form-control identify_type" id="identify_type" placeholder="' . lang("select_identify_to_load") . '" data-bv-notempty="true"');																		
																?>
															</div>
															
														</div>
														<div class="col-md-6">															
															<div class="form-group">
																<label id="identify" for="cus_gov_id">Identify ID</label>
																<input type="hidden" name="h_identify" id="h_identify" class="h_identify"  />
																<?php echo form_input('cus_gov_id', (isset($_POST['cus_gov_id']) ? $_POST['cus_gov_id'] : ''), 'class="form-control" id="cus_gov_id" data-bv-notempty="true"'); ?>
															</div>
														</div>
													</div>
													
												<!---->
													<div class="col-lg-12">
														<div class="col-md-6">
															<div class="form-group">
																<?= lang("issue_by", "cus_issue_by"); ?>
																<?php echo form_input('cus_issue_by', (isset($_POST['cus_issue_by']) ? $_POST['cus_issue_by'] : ''), 'class="form-control" id="cus_issue_by"'); ?>
															</div>
															<div class="form-group">
																<?= lang("family_name_(en)", "cus_family_name"); ?>
																<?php echo form_input('cus_family_name', (isset($_POST['cus_family_name']) ? $_POST['cus_family_name'] : ''), 'class="form-control tip" id="cus_family_name" data-bv-notempty="true"'); ?>
															</div>
															<div class="form-group">
																<?= lang("first_name_(en)", "cus_first_name"); ?>
															   <?php echo form_input('cus_first_name', (isset($_POST['cus_first_name']) ? $_POST['cus_first_name'] : ''), 'class="form-control" id="cus_first_name" required="required"'); ?>
															</div>															
															<div class="form-group" id="cus_gender">
																<?= lang("gender", "cus_gender"); ?>
																<?php
																$cus_gender[(isset($_POST['cus_gender']) ? $_POST['cus_gender'] : '')] = (isset($_POST['cus_gender']) ? $_POST['cus_gender'] : '');
																$cus_gender['ប្រុស'] = "ប្រុស";
																$cus_gender['ស្រី'] = "ស្រី";
																echo form_dropdown('cus_gender', $cus_gender, isset($customer->gender)?$customer->gender:'', 'class="form-control select" id="cus_gender" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%" data-bv-notempty="true"')
																?>
															</div>
															<div class="form-group">
																<?= lang("date_of_birth", "cus_dob");?>
																<?php echo form_input('cus_dob', (isset($_POST['cus_dob']) ? $_POST['cus_dob'] : ''), 'class="form-control date" id="cus_dob"');?>
															</div>
															<div class="form-group">
																<?= lang("age", "cus_age"); ?>
																<?php echo form_input('cus_age', (isset($_POST['cus_age']) ? $_POST['cus_age'] : ''), 'class="form-control" id="cus_age" style="pointer-events: none;"'); ?>
															</div>
															
															<div class="form-group">
																<?= lang("by_c.o", "cus_by_co"); ?>
																<?php
																	$us[""] = "";
																	if(is_array(isset($users) ?$users  : (''))){
																	foreach ($users as $user) {
																		$us[$user->id] = $user->first_name . " " . $user->last_name;
																	}}
																	echo form_dropdown('cus_by_co', isset($us) ?$us  : (''), (isset($_POST['cus_by_co']) ? $_POST['cus_by_co'] : ""), 'class="form-control" id="cus_by_co" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("C.O") . '"');
																?>
															</div>
														</div>														
														<div class="col-md-6">
															<div class="form-group">
																<?= lang("issue_date", "cus_issue_date"); ?>
																<?php echo form_input('cus_issue_date', (isset($_POST['cus_issue_date']) ? $_POST['cus_issue_date'] : ''), 'class="form-control date" id="cus_issue_date"'); ?>
															</div>
															<div class="form-group">
																<?= lang("family_name_(kh)", "cus_family_name_other"); ?>
																<?php echo form_input('cus_family_name_other', (isset($_POST['cus_family_name_other']) ? $_POST['cus_family_name_other'] : ''), 'class="form-control" id="cus_family_name_other" '); ?>
															</div>
															<div class="form-group">
																<?= lang("first_name_(kh)", "cus_first_name_other"); ?>
																<?php echo form_input('cus_first_name_other', (isset($_POST['cus_first_name_other']) ? $_POST['cus_first_name_other'] : ''), 'class="form-control" id="cus_first_name_other"'); ?>
															</div>
															<div class="form-group">
																<?= lang("place_of_birth", "cus_pob"); ?>
																<?php echo form_input('cus_pob', (isset($_POST['cus_pob']) ? $_POST['cus_pob'] : ''), 'class="form-control" id="cus_pob"'); ?>
															</div>
															<div class="form-group">
																<?= lang("nationality", "cus_nationality"); ?>
																<?php
																$cus_nationality[(isset($_POST['cus_nationality']) ? $_POST['cus_nationality'] : '')] = (isset($_POST['cus_nationality']) ? $_POST['cus_nationality'] : '');
																$cus_nationality['cam'] = "Cambodian";
																$cus_nationality['tha'] = "Thailand";
																$cus_nationality['vie'] = "Vietnamese";
																$cus_nationality['chi'] = "Chinese";
																echo form_dropdown('cus_nationality', $cus_nationality, isset($customer->nationality)?$customer->nationality:'cam', 'class="form-control select" id="cus_nationality" placeholder="' . lang("select") . ' ' . lang("nationality") . '" style="width:100%"')
																?>
															</div>
															<div class="form-group">
																<?= lang("phone_1", "cus_phone_1"); ?>
																<input type="tel"  name="cus_phone_1" maxlength="10" class="form-control number_only"  id="cus_phone_1"  value="<?= (isset($_POST['cus_phone_1']) ? $_POST['cus_phone_1'] : '') ?>"/>
															</div>
															<div class="form-group">
																<?= lang("phone_2", "cus_phone_2"); ?>
																<input type="tel" name="cus_phone_2" maxlength="10" class="form-control number_only"  id="cus_phone_2"  value="<?= (isset($_POST['cus_phone_2']) ? $_POST['cus_phone_2'] : '') ?>"/>
															</div>
														</div>
														<div class="col-sm-12">
															<div class="panel panel-primary">
																<div class="panel-heading"><?= lang('address') ?></div>
																<div class="panel-body" style="padding: 5px;">
																	<div class="col-md-6">
																		<div class="form-group">
																			<?= lang("country", "cus_country"); ?>
																			<?php
																			if(array($countries)) {
																				foreach ($countries as $ct) {
																					$cus_country[$ct->code] = $ct->name;
																				}
																			}
																			echo form_dropdown('cus_country', $cus_country, isset($customer->country)?$customer->country: $branch->country , 'class="form-control select" id="cus_country" data-placeholder="' . lang("select_country") . '" style="width:100%" required="required"');
																			?>
																		</div>
																		<div class="form-group">
																			<?= lang("district", "cus_district"); ?>
																			<?php echo form_input('cus_district', isset($customer->district)?$customer->district:'', 'class="form-control" id="cus_district"  placeholder="' . lang("select_province_to_load") . '" data-bv-notempty="true"');?>
																		</div>
																		<div class="form-group">
																			<?= lang("village", "cus_village"); ?>
																			<?php echo form_input('cus_village', isset($customer->village)?$customer->village:'', 'class="form-control" id="cus_village"  placeholder="' . lang("select_communce_to_load") . '" data-bv-notempty="true"');?>
																		</div>
																		<div class="form-group">
																			<?= lang("current_address", "cus_house_no"); ?>
																			<?php echo form_input('cus_house_no', (isset($_POST['cus_house_no']) ? $_POST['cus_house_no'] : ''), 'class="form-control" id="cus_house_no"'); ?>
																		</div>
																	</div>
																	<div class="col-md-6">
																		<div class="form-group">
																			<?= lang("province", "cus_province"); ?>
																			<?php echo form_input('cus_province', isset($customer->province)?$customer->province: $branch->state, 'class="form-control" id="cus_province"  placeholder="' . lang("select_province") . '" required="required"');?>
																		</div>
																		<div class="form-group">
																			<?= lang("communce", "cus_communce"); ?>
																			<?php echo form_input('cus_communce', isset($customer->communce)?$customer->communce:'', 'class="form-control" id="cus_communce"  placeholder="' . lang("select_district_to_load") . '" data-bv-notempty="true"');?>
																		</div>
																		<!--<div class="form-group">
																			<?= lang("street", "cus_street"); ?>
																			<?php echo form_input('cus_street', (isset($_POST['cus_street']) ? $_POST['cus_street'] : ''), 'class="form-control" id="cus_street"'); ?>
																		</div>-->
																		<div class="form-group">
																			<?= lang("housing", "cus_housing"); ?>
																			<?php
																			$cus_housing[(isset($_POST['cus_housing']) ? $_POST['cus_housing'] : '')] = (isset($_POST['cus_housing']) ? $_POST['cus_housing'] : '');
																			$cus_housing["owner"] = "Owner";
																			$cus_housing["living_with_parent"] = "Living with parent";
																			$cus_housing["renting"] = "Renting";
																			echo form_dropdown('cus_housing', $cus_housing, isset($customer->housing)?$customer->housing:'', 'class="form-control select" id="cus_housing" placeholder="' . lang("select") . ' ' . lang("housing") . '" style="width:100%" data-bv-notempty="true"');
																			?>
																		</div>
																		<div class="form-group">
																			<b style="padding-bottom:5px; display:block;"><?= lang("time_at_this_address"); ?></b>
																			<?php echo form_input('cus_years', (isset($_POST['cus_years']) ? $_POST['cus_years'] : ''), 'class="form-control" id="cus_years" placeholder="' . lang("years") . '" style="display:inline !important; width:35% !important;"'); ?>
																			<?= lang("years", "cus_years"); ?>
																			<?php echo form_input('cus_months', (isset($_POST['cus_months']) ? $_POST['cus_months'] : ''), 'class="form-control" id="cus_months" placeholder="' . lang("months") . '" style="display:inline !important; width:35% !important;"'); ?>
																			<?= lang("months", "cus_months"); ?>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>	
														
												</div>
												<div class="col-md-12" id="sticker">
												</div>
												<div class="clearfix"></div>
											</div>
										</div>
									</div>
								</div>
								
								<div id="saving_products" style="display: none;" class="tab-pane fade">
									<div class="row">
										<div class="col-sm-12">
											<div class="col-sm-12">
												<div class="panel panel-primary">
													<div class="panel-heading"><?= lang('product_info') ?></div>
													<div class="panel-body" style="padding: 5px;">
														<div class="col-sm-12">
																														
															
															<div class="col-md-6">
																<div class="form-group">
																	<?php echo lang('currency', 'currency') ?>
																	<?php
																	$crr[(isset($_POST['currency']) ? $_POST['currency'] : '')] = (isset($_POST['currency']) ? $_POST['currency'] : '');
																	if(array($currencies)) {
																		foreach($currencies as $currency){
																			$crr[$currency->code] = $currency->name;
																		}
																	}
																	echo form_dropdown('currency', $crr, '', 'class="form-control currency" id="currency" placeholder="' . lang("select_currency") . '"');
																	?>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group all">
																	<?= lang("amount", "price") ?>
																	<?= form_input('price', (isset($_POST['price']) ? $_POST['price'] : ''), ' class="form-control" id="total_amount" style="font-size:20px;" ') ?>
																	
																</div>
															</div>		
															<div class="col-md-12">
																<div class="form-group all">
																	<?= lang('description', 'ldescription'); ?>
																	<textarea name="ldescription" id="ldescription"
																			  class="pa form-control kb-text ldescription"></textarea>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
												
												
												<div class="col-sm-12 hide_cash-show">
													<div class="panel panel-primary">
														<div class="panel-heading"><?= lang('loan_information') ?></div>
														<div class="panel-body" style="padding: 5px;">
															
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("start_saving_date", "st_inst_date"); ?>
																	<?php echo form_input('st_inst_date', (isset($_POST['st_inst_date']) ? $_POST['st_inst_date'] : ''), 'class="form-control date" id="st_inst_date"'); ?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("interest_rate", "interest_rate_cash"); ?>
																	<input type="hidden" name="interest_rate_cash" id="interest_rate_cash" class="interest_rate_cash"/>
																	<?= form_input('interest_rate', (isset($inv->rate_text ) ? $inv->rate_text : ''), ' class="form-control" id="interest_rate_cash_2" style="font-size:14px;" ') ?>
																
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("term", "term_cash"); ?>
																	<?php
																	$term[""] = "";
																	if(array($terms)) {
																		foreach ($terms as $tm) {
																			$term[$tm->amount] = $tm->description;
																		}
																	}
																	echo form_dropdown('term_cash', $term, (isset($_POST['term_cash']) ? $_POST['term_cash'] : ''), 'id="term_cash" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("term") . '" class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("saving_frequency", "frequency_cash"); ?>
																	<?php
																	$frequency_cash[""] = "";
																	$frequency_cash[1] = "Daily";
																	$frequency_cash[7] = "Weekly";
																	$frequency_cash[14] = "Two Week";
																	$frequency_cash[30] = "Monthly";
																	//$frequency_cash[90] = "Quarterly";
																	$frequency_cash[180] = "Haft Year";
																	$frequency_cash[360] = "Yearly";
																	echo form_dropdown('frequency_cash', $frequency_cash, (isset($_POST['frequency_cash']) ? $_POST['frequency_cash'] : ''), 'id="frequency_cash" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("frequency") . '"  class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("rate_type", "rate_type_cash"); ?>
																	<?php
																	$rate_type[""] = "";
																	$rate_type["1"] = "Normal";
																	$rate_type["2"] = "Fixed";
																	$rate_type["3"] = "Normal_Fixed";
																	$rate_type["4"] = "Seasons";
																	//$rate_type["5"] = "Custom";
																	echo form_dropdown('rate_type_cash', $rate_type, (isset($_POST['rate_type_cash']) ? $_POST['rate_type_cash'] : ''), 'id="rate_type_cash" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("rate_type") . '"  class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
															
															<div class="col-lg-6 btn_print_payment_schedule_cash" style="vertical-align: middle; padding: 2.5% 0% 1% 1.3%; display:none;">
																<input type="button" class="btn btn-primary" value="<?=lang('print_payment_schedule')?>" name="print_payment_schedule_cash" id="print_payment_schedule_cash" />
															</div>
														</div>
													</div>
												</div>
										</div>
									</div>
								</div>
								
								
									<div class="tab-pane">
										<input type="submit" class="btn btn-primary" value="<?=lang('submit')?>" name="submitQoute" />
									</div>
									<?php echo form_close(); ?>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(window).load(function() {
		$(".category").trigger('change');
		$("#category").trigger('change');
		$(".sub_category").trigger('change');
	});
	

	
	$(document).ready(function() {
		
		var count_gov=0;
		$('#cus_gov_id').on('change', function() {
			var cus_gov_id = $(this).val();
			$.ajax({
				type: 'get',
				url: '<?= site_url('quotes/getExistingGovIDInfo'); ?>',
				dataType: "json",
				data: {
					gov_id : cus_gov_id
				},
				success: function (data) {
					if(data.id) {
						var link = $('<a href="quotes/gov_id_report/'+data.id +'" rel="lightbox" id="link'+count_gov+'" data-toggle="modal" data-target="#myModal"></a>');
						  $("body").append(link);
						  $("#link"+count_gov).click();
						count_gov++;
					}
				}
			});
		});
		
		
		$('.category').on('change', function(){
			var category = $(this).val();
			var category_val = category.split('#');
			$('#mfi').val(category_val[1]);
			if(category_val[1]){
				$('.hide_cash').hide();
				$('.hide_cash-show').show();
				$('.show_cash').show();
			}else{
				$('.hide_cash').show();
				$('.hide_cash-show').hide();
				$('.show_cash').hide();
			}
			$.ajax({
				url: site.base_url + 'quotes/ajaxGetSubCategoryByCatID/'+category_val[0],
				dataType: 'json',
				success: function(scdata){
					
					if (scdata != null) {
						$(".sub_category").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
							placeholder: "<?= lang('select_category_to_load') ?>",
							data: scdata
						});
					}
				},
				error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                    }
			});
		});

		
		$('#product_id').on('change', function(){
			var product_id = $(this).val();	
			
			$.ajax({	
				url: site.base_url + 'quotes/ajaxGetProductBySubCategoryID2/'+product_id,
				dataType: 'json',
				success: function(scdata){
					if (scdata) {
						$('#group').show();
					}else{
						$('#group').hide();	
					}
				},
				
			});
		});
				
		
		$('.sub_category').on('change', function(){
			var sub_category_id = $(this).val();
			$.ajax({
				url: site.base_url + 'quotes/ajaxGetProductBySubCategoryID/'+sub_category_id,
				dataType: 'json',
				success: function(scdata){
					if (scdata != null) {
						$(".product_id").select2("destroy").empty().attr("placeholder", "<?= lang('select_product') ?>").select2({
							placeholder: "<?= lang('select_product_to_load') ?>",
							data: scdata
						});
					}
				},
				error: function () {
					bootbox.alert('<?= lang('ajax_error') ?>');
					$('#modal-loading').hide();
				}
			});
		});
		
	});
</script>

<!-- Filter Address --->
<script>
	$(document).ready(function() {
		/*  ---------- Select all Provinces When Form Load ----------- */
		$(window).load(function() {
			var url = "<?= site_url('quotes/getProvinces') ?>";
			var child_obj = 'cus_province';
			var child_emp_province = 'emp_province';
			var child_province = 'province';
			var lang = '<?= lang('select_province') ?>';
			var pholder = '<?= lang('select_province_to_load') ?>';
			getSelected2By3Child(child_obj, child_emp_province, child_province, url, lang, pholder);
		});
		
		$('#cus_dob').live('change', function() {
			var dob = $(this).val();
			if(dob != '') {
				var age = getAge(dob);
				$('#cus_age').val(age +' Year old');
			}else {
				$('#cus_age').val('');
			}
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
		
		$('#dob').live('change', function() {
			var dob = $(this).val();
			if(dob != '') {
				var age = getAge(dob);
				$('#age').val(age +' Year old');
			}else {
				$('#age').val('');
			}
		});
		
		$('#dob2').live('change', function() {
			var dob = $(this).val();
			if(dob != '') {
				var age = getAge(dob);
				$('#age2').val(age +' Year old');
			}else {
				$('#age2').val('');
			}
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

<!-- get lat long  ---->
 <script>		
		$('#identify_type').live('change', function() {
			var id_type = $("#identify_type option:selected").text();
			$('#h_identify').val(id_type);
			$('#identify').text(id_type);
		});
		/*----Amount----*/
		$('#total_amount').live('change', function(e) {
			var price = $(this).val().toLowerCase();
			var amount = 0;
			var new_amount = 0; 
			if(price.search('k') > 0) {
				amount = price.split('k');
				new_amount = parseFloat(amount[0] * 1000);
			}else if(price.search('m') > 0) {
				amount = price.split('m');
				new_amount = parseFloat(amount[0] * 1000000);
			}else {
				amt = price - 0;
				if(!Number(amt)) {
					new_amount = 0;
				}else {
					new_amount = price;
				}
			}
			$(this).val(new_amount);
			$('.ch_services').trigger('ifChanged');
			$('#interest_rate_cash').trigger('change');
		});
		
		
		$('#interest_rate_cash_2').live('change', function(e) {
			var interest_rate_cash = $(this).val().toLowerCase();
			var interest_rate = 0;
			if(interest_rate_cash.search('%') > 0) {
				interest_rate_cash = interest_rate_cash.replace('%', '');
				interest_rate = (interest_rate_cash/100);
			}else {
				rate = interest_rate_cash - 0;
				if(!Number(rate)) {
					interest_rate = 0;
				}else {
					interest_rate = interest_rate_cash;
				}
			}
			$('#interest_rate_cash').val(interest_rate);
			$('#interest_rate_cash').trigger('change');
		});
		
</script>

<!-- end get lat long -->
