<?php
	//$this->erp->print_arrays($branch->state);
?>
<!--
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQ8Hg1S1CjrMsi6AlupxsPEa5KVKeZF8s"></script>

<script type="text/javascript">
    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>, allow_discount = <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? 1 : 0; ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, shipping = 0,
        tax_rates = <?php echo json_encode($tax_rates); ?>;
    var audio_success = new Audio('<?=$assets?>sounds/sound2.mp3');
    var audio_error = new Audio('<?=$assets?>sounds/sound3.mp3');
    $(document).ready(function () {
		if($('#add_item').val()!=""){
		$('#add_item').focus();
        }
		
		<?php if($this->input->get('customer')) { ?>
        if (!localStorage.getItem('quitems')) {
            localStorage.setItem('qucustomer', <?=$this->input->get('customer');?>);	
		}
        <?php } ?>
        <?php if ($Owner || $Admin) { ?>
        if (!localStorage.getItem('qudate')) {
            $("#qudate").datetimepicker({
                format: site.dateFormats.js_ldate,
                fontAwesome: true,
                language: 'erp',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', new Date());
        }
        $(document).on('change', '#qudate', function (e) {
            localStorage.setItem('qudate', $(this).val());
        });
        if (qudate = localStorage.getItem('qudate')) {
            $('#qudate').val(qudate);
        }
        $(document).on('change', '#qubiller', function (e) {
            localStorage.setItem('qubiller', $(this).val());
        });
        if (qubiller = localStorage.getItem('qubiller')) {
            $('#qubiller').val(qubiller);
        }
        <?php } ?>
        if (!localStorage.getItem('qutax2')) {
            localStorage.setItem('qutax2', <?=$Settings->default_tax_rate2;?>);
        }
        ItemnTotals();
        $("#add_item").autocomplete({
            source: function (request, response) {
                if (!$('#qucustomer').val()) {
                    $('#add_item').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    //response('');
                    $('#add_item').focus();
                    return false;
                }
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('quotes/suggestions'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        customer_id: $("#qucustomer").val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                   // $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                  //  $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_invoice_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            }
        });
        $('#add_item').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });
    });
</script>
-->

<!---Start show block popup for applicant same identify_id--->

<!--End black popup applicant ------------>


<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_quote'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('enter_info'); ?></p>
				<div class="box-content">
					<div class="row">
						<div class="col-md-12">
							<ul id="dbTab" class="nav nav-tabs">
								<li class="" id="application_tap"><a href="#applicants"><?= lang('applicants') ?></a></li>								
								<li class="" id="financial_products_tap"><a href="#financial_products"><?= lang('financial_products') ?></a></li>
								<li class="" id="join_lease_tap"><a href="#join_lease"><?= lang('join_lease') ?></a></li>
								<li class="" id="employee_tap"><a href="#employee"><?= lang('employee') ?></a></li>
								<li class="" id="guarantors_tap"><a href="#guarantors"><?= lang('guarantors') ?></a></li>
								<li class="" id="collateral_tap"><a href="#collateral"><?= lang('collateral') ?></a></li>
								<li class="" id="documents_tap"><a href="#documents"><?= lang('documents') ?></a></li>
								<!--<li class="" id="fields_check_tap"><a id="field_check" href="#fields_check"><?= lang('fields_check') ?></a></li>-->
							</ul>
							<div class="tab-content">
								<?php
								$attrib = array('data-toggle' => 'validator', 'role' => 'form');
								echo form_open_multipart("quotes/add", $attrib)
								?>
								<div id="applicants" class="tab-pane fade in">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<div class="row">
													<!---->
													<div class="col-lg-12">
														<div class="col-md-6">
																<div class="form-group">
																	<?= lang("status", "qstatus"); ?>
																	<?php
																		$status_q = array('draft' => lang('draft'), 'applicant' => lang('applicant'));
																			echo form_dropdown('status', $status_q, (isset($_POST['status'])? $_POST['status'] : ''), 'id="qstatus" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("status") . '" class="form-control input-tip " style="width:100%;"');	
																	?>
																</div>
															<div class="form-group">
																<?= lang("identify_type", "identify_type"); ?>
																<?php
																	$ident_all[(isset($_POST['identify_id']) ? $_POST['identify_id'] : '')] = (isset($_POST['identify_id']) ? $_POST['identify_id'] : '');
																	if(array($identify_type)) {
																		foreach($identify_type as $ident_){
																			$ident_all[$ident_->id] = $ident_->name;
																		}
																	}
																	echo form_dropdown('identify_id', $ident_all, (($applicant->identify)?$applicant->identify:''), 'class="form-control identify_type" id="identify_type" placeholder="' . lang("select_identify_to_load") . '" data-bv-notempty="true"');																		
																?>
															</div>
															<div class="form-group">
																<label id="identify" for="cus_gov_id"></label>
																<input type="hidden" name="h_identify" id="h_identify" class="h_identify"  />
																<?php echo form_input('cus_gov_id', (isset($_POST['cus_gov_id']) ? $_POST['cus_gov_id'] : $applicant->gov_id), 'class="form-control" id="cus_gov_id" data-bv-notempty="true"'); ?>
															</div>
														</div>
														<div class="col-md-6"style="margin-top:15px;">
															<div class="col-md-0"></div>
															<div class="col-md-3">
																<div style=" width:115px; height:135px; background-color:#ccc;">																	
																	<?php echo '<img src="' . base_url() . 'assets/uploads/documents/' . ($qphoto->name?$qphoto->name:'male.png') .'"  style=" width:115px; height:135px;" id="inputimg"/> '?>
																</div>
															</div>															
															<div class="col-md-4">
																<div style="margin-top:60px;">
																</div>
																<div>
																	<label for="document"><?=lang("photo_applicant")?></label>
																	<input type="file" class=" file" data-show-preview="false" value="<?= $qphoto->name ?>" data-show-upload="false" name="applicant_photo" id="document">
																</div>
															</div>
														</div>
													</div>
													
												<!---->
													<div class="col-lg-12">
														<div class="col-md-6">															
															<!--<div class="form-group">
																<?= lang("civility", "cus_civility"); ?>
																<?php
																$cus_civility[(isset($_POST['cus_civility']) ? $_POST['cus_civility'] : '')] = (isset($_POST['cus_civility']) ? $_POST['cus_civility'] : '');
																$cus_civility['male'] = "Mr.";
																$cus_civility['female'] = "Mrs.";
																echo form_dropdown('cus_civility', $cus_civility, isset($customer->civility)?$customer->civility:'', 'class="form-control select" id="cus_civility" placeholder="' . lang("select") . ' ' . lang("civility") . '" style="width:100%" data-bv-notempty="true"')
																?>
															</div>-->															
															<div class="form-group">
																<?= lang("issue_by", "cus_issue_by"); ?>
																<?php echo form_input('cus_issue_by', (isset($_POST['cus_issue_by']) ? $_POST['cus_issue_by'] : $applicant->issue_by), 'class="form-control" id="cus_issue_by"'); ?>
															</div>
															<div class="form-group">
																<?= lang("family_name_(en)", "cus_family_name"); ?>
																<?php echo form_input('cus_family_name', (isset($_POST['cus_family_name']) ? $_POST['cus_family_name'] : $applicant->family_name), 'class="form-control tip" id="cus_family_name" data-bv-notempty="true"'); ?>
															</div>
															<div class="form-group">
																<?= lang("first_name_(en)", "cus_first_name"); ?>
															   <?php echo form_input('cus_first_name', (isset($_POST['cus_first_name']) ? $_POST['cus_first_name'] : $applicant->name), 'class="form-control" id="cus_first_name" required="required"'); ?>
															</div>
															<div class="form-group person" style="display:none;">
																<?= lang("nick_name", "cus_nick_name"); ?>
																<?php echo form_input('cus_nick_name', (isset($_POST['cus_nick_name']) ? $_POST['cus_nick_name'] : ''), 'class="form-control tip" id="cus_nick_name"'); ?>
															</div>
															<div class="form-group" id="cus_gender">
																<?= lang("gender", "cus_gender"); ?>
																<?php
																$cus_gender[(isset($_POST['cus_gender']) ? $_POST['cus_gender'] : '')] = (isset($_POST['cus_gender']) ? $_POST['cus_gender'] : '');
																$cus_gender['male'] = "Male";
																$cus_gender['female'] = "Female";
																echo form_dropdown('cus_gender', $cus_gender, isset($customer->gender)?$customer->gender:$applicant->gender, 'class="form-control select" id="cus_gender" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%" data-bv-notempty="true"')
																?>
															</div>
															<div class="form-group">
																<?= lang("date_of_birth", "cus_dob"); ?>
																<?php echo form_input('cus_dob',$applicant->date_of_birth?$this->erp->hrsd($applicant->date_of_birth):'', 'class="form-control date" id="cus_dob"'); ?>
															</div>
															<div class="form-group">
																<?= lang("age", "cus_age"); ?>
																<?php echo form_input('cus_age', (isset($_POST['cus_age']) ? $_POST['cus_age'] : $applicant->age), 'class="form-control" id="cus_age" style="pointer-events: none;"'); ?>
															</div>
															<div class="form-group" id="spname">
																<?= lang("spouse_full_name", "cus_sp_fname"); ?>
																<?php echo form_input('cus_sp_fname', (isset($_POST['cus_sp_fname']) ? $_POST['cus_sp_fname'] : $applicant->spouse_name), 'class="form-control" id="cus_sp_fname"'); ?>
															</div>
															<div class="form-group" id="spphone">
																<?= lang("spouse_mobile_phone", "cus_sp_phone"); ?>
																<input type="tel" name="cus_sp_phone" maxlength="11" class="form-control number_only" id="cus_sp_phone" value="<?=$applicant->spouse_phone?>"/>
															</div>
															<div class="form-group" id="spchild">
																<?= lang("number_of_children", "cus_num_of_child"); ?>
																<?php echo form_input('cus_num_of_child', (isset($_POST['cus_num_of_child']) ? $_POST['cus_num_of_child'] : $applicant->num_of_child == 0? '':$applicant->num_of_child), 'class="form-control" id="cus_num_of_child"'); ?>
															</div>
															<div class="form-group">
																<?= lang("phone_1", "cus_phone_1"); ?>
																<input type="tel"  name="cus_phone_1" maxlength="11" class="form-control number_only"  id="cus_phone_1"  value="<?= (isset($_POST['cus_phone_1']) ? $_POST['cus_phone_1'] : $applicant->phone1) ?>" required="required"/>
															</div>
															<div class="form-group">
																<?= lang("phone_2", "cus_phone_2"); ?>
																<input type="tel" name="cus_phone_2" maxlength="11" class="form-control number_only"  id="cus_phone_2"  value="<?= (isset($_POST['cus_phone_2']) ? $_POST['cus_phone_2'] : $applicant->phone2) ?>"/>
															</div>
															<div class="form-group">
																<?= lang("by_c.o", "cus_by_co"); ?>
																<?php
																	$us[""] = "";
																	if(is_array(isset($users) ?$users  : (''))){
																	foreach ($users as $user) {
																		$us[$user->id] = $user->first_name . " " . $user->last_name;
																	}}
																	echo form_dropdown('cus_by_co', isset($us) ?$us  : (''), (isset($_POST['cus_by_co']) ? $_POST['cus_by_co'] : $applicant->created_by), 'class="form-control" id="cus_by_co" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("C.O") . '" data-bv-notempty="true"');
																?>
															</div>
														</div>														
														<div class="col-md-6">
															<div class="form-group">
																<?= lang("issue_date", "cus_issue_date"); ?>
																<?php echo form_input('cus_issue_date', $applicant->issue_date?$this->erp->hrsd($applicant->issue_date):'', 'class="form-control date" id="cus_issue_date"'); ?>
															</div>
															<div class="form-group">
																<?= lang("family_name_(kh)", "cus_family_name_other"); ?>
																<?php echo form_input('cus_family_name_other', (isset($_POST['cus_family_name_other']) ? $_POST['cus_family_name_other'] : $applicant->family_name_other), 'class="form-control" id="cus_family_name_other" data-bv-notempty="true"'); ?>
															</div>
															<div class="form-group">
																<?= lang("first_name_(kh)", "cus_first_name_other"); ?>
																<?php echo form_input('cus_first_name_other', (isset($_POST['cus_first_name_other']) ? $_POST['cus_first_name_other'] : $applicant->name_other), 'class="form-control" id="cus_first_name_other" data-bv-notempty="true"'); ?>
															</div>
															<div class="form-group">
																<?= lang("father_name", "father_name"); ?>
																<?php echo form_input('father_name', (isset($_POST['father_name']) ? $_POST['father_name'] : $applicant->father_name), 'class="form-control" id="father_name"'); ?>
															</div>
															<div class="form-group">
																<?= lang("place_of_birth", "cus_pob"); ?>
																<?php echo form_input('cus_pob', (isset($_POST['cus_pob']) ? $_POST['cus_pob'] : $applicant->address), 'class="form-control" id="cus_pob"'); ?>
															</div>
															<div class="form-group">
																<?= lang("nationality", "cus_nationality"); ?>
																<?php
																$cus_nationality[(isset($_POST['cus_nationality']) ? $_POST['cus_nationality'] : '')] = (isset($_POST['cus_nationality']) ? $_POST['cus_nationality'] : '');
																$cus_nationality['cam'] = "Cambodian";
																$cus_nationality['tha'] = "Thailand";
																$cus_nationality['vie'] = "Vietnamese";
																$cus_nationality['chi'] = "Chinese";
																$cus_nationality['bm'] = "Burma";
																echo form_dropdown('cus_nationality', $cus_nationality, isset($customer->nationality)?$customer->nationality:isset($applicant->nationality)?$applicant->nationality:'cam', 'class="form-control select" id="cus_nationality" placeholder="' . lang("select") . ' ' . lang("nationality") . '" style="width:100%"')
																?>
															</div>
															<div class="form-group" id="maritalstatus">
																<?= lang("marital_status", "cus_marital_status"); ?>
																<?php
																//----
																$cus_marital_status[(isset($_POST['cus_marital_status']) ? $_POST['cus_marital_status'] : '')] = (isset($_POST['cus_marital_status']) ? $_POST['cus_marital_status'] : '');
																$cus_marital_status['married'] = "Married ";
																$cus_marital_status['divorced'] = "Divorced";
																$cus_marital_status['single'] = "Single";
																$cus_marital_status['widow/Widower'] = "Widow/Widower";
																$cus_marital_status['unknown'] = "Unknown";																
																echo form_dropdown('cus_marital_status', $cus_marital_status, isset($customer->marital_status)?$customer->marital_status:isset($applicant->status)?$applicant->status:'single', 'class="form-control select" id="cus_marital_status" placeholder="' . lang("select") . ' ' . lang("marital_status") . '" style="width:100%" ');
																?>
															</div>
															<!--<div class="form-group">
																<?= lang("spouse_family_name", "cus_sp_fam_name"); ?>
																<?php echo form_input('cus_sp_fam_name', (isset($_POST['cus_sp_fam_name']) ? $_POST['cus_sp_fam_name'] : ''), 'class="form-control" id="cus_sp_fam_name"'); ?>
															</div>-->
															<div class="form-group" id="sp_gender">
																<?= lang("spouse_gender", "sp_gender"); ?>
																<?php
																$sp_gender[(isset($_POST['sp_gender']) ? $_POST['sp_gender'] : '')] = (isset($_POST['sp_gender']) ? $_POST['sp_gender'] : '');
																$sp_gender['male'] = "Male";
																$sp_gender['female'] = "Female";
																echo form_dropdown('sp_gender', $sp_gender, isset($customer->spouse_gender)?$customer->spouse_gender:$applicant->spouse_gender, 'class="form-control select" id="sp_gender" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%" ')
																?>
															</div>
															<div class="form-group" id="sp_status">
																<?= lang("spouse_status", "sp_status"); ?>
																<?php
																$sp_status[(isset($_POST['sp_status']) ? $_POST['sp_status'] : '')] = (isset($_POST['sp_status']) ? $_POST['sp_status'] : '');
																$sp_status['husband'] = "Husband";
																$sp_status['wife'] = "Wife";
																echo form_dropdown('sp_status', $sp_status, isset($customer->spouse_status)?$customer->spouse_status:$applicant->spouse_status, 'class="form-control select" id="sp_status" placeholder="' . lang("select") . ' ' . lang("status") . '" style="width:100%"')
																?>
															</div>
															<div class="form-group" id="sp_date">
																<?= lang("spouse_birthdate", "sp_date_of_birth"); ?>
																<?php echo form_input('sp_date_of_birth', (isset($_POST['sp_date_of_birth']) ? $_POST['sp_date_of_birth'] : $this->erp->hrsd($applicant->spouse_birthdate)), 'class="form-control date" id="sp_date_of_birth"'); ?>
															</div>
															
															<div class="form-group" id="whoseincome">
																<?= lang("whose_income", "cus_whose_income"); ?>
																<?php echo form_input('cus_whose_income', (isset($_POST['cus_whose_income']) ? $_POST['cus_whose_income'] : $applicant->whose_income), 'class="form-control" id="cus_whose_income"'); ?>
															</div>
															<div class="form-group" id="incomecombine">
																<?= lang("income_combination", "cus_inc_comb"); ?>
																<?php
																$cus_inc_comb['0'] = "No";
																$cus_inc_comb['1'] = "Yes";
																echo form_dropdown('cus_inc_comb', $cus_inc_comb, isset($customer->inc_comb)?$customer->inc_comb:$applicant->income_combination, 'class="form-control select" id="cus_inc_comb" style="width:100%"');
																?>
															</div>
															<div class="form-group" style="display:none;">
																<?= lang("black_list_customer", "cus_black_list"); ?>
																<?php
																$cus_black_list['0'] = "No";
																$cus_black_list['1'] = "Yes";
																echo form_dropdown('cus_black_list', $cus_black_list, isset($customer->black_list)?$customer->black_list:'', 'class="form-control select" id="cus_black_list" style="width:100%"');
																?>
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
																			echo form_dropdown('cus_country', $cus_country, isset($customer->country)?$customer->country: isset($applicant->country)?$applicant->country:$branch->country , 'class="form-control select" id="cus_country" data-placeholder="' . lang("select") . ' ' . lang("country") . '" style="width:100%" required="required"');
																			?>
																		</div>
																		<!-- Country end -->
																		<div class="form-group">
																			<?= lang("district", "cus_district"); ?>
																			<?php echo form_input('cus_district', isset($customer->district)?$customer->district:$applicant->district, 'class="form-control" id="cus_district"  placeholder="' . lang("select_province_to_load") . '" data-bv-notempty="true"');?>
																		</div>
																		<!-- District end -->
																		<div class="form-group">
																			<?= lang("village", "cus_village"); ?>
																			<?php echo form_input('cus_village', isset($customer->village)?$customer->village:$applicant->village, 'class="form-control" id="cus_village"  placeholder="' . lang("select_communce_to_load") . '" data-bv-notempty="true"');?>
																		</div>
																		<!-- Village end -->
																		<div class="form-group">
																			<?= lang("current_address", "cus_house_no"); ?>
																			<?php echo form_input('cus_house_no', (isset($_POST['cus_house_no']) ? $_POST['cus_house_no'] : $applicant->house_no), 'class="form-control" id="cus_house_no" required="required"'); ?>
																		</div>
																		<!-- Current end -->
																	</div>
																	
																	<div class="col-md-6">
																		<div class="form-group">
																			<?= lang("province", "cus_province"); ?>
																			<?php echo form_input('cus_province', isset($customer->province)?$customer->province: isset($applicant->state)?$applicant->state:$branch->state, 'class="form-control" id="cus_province"  placeholder="' . lang("select_province") . '" required="required"');?>
																		</div>
																		<!-- Provience end -->
																		<div class="form-group">
																			<?= lang("communce", "cus_communce"); ?>
																			<?php echo form_input('cus_communce', isset($customer->communce)?$customer->communce:($applicant ? $applicant->sangkat : ''), 'class="form-control" id="cus_communce"  placeholder="' . lang("select_district_to_load") . '" "');?>
																		</div>
																		<!-- Communce end //amm -->




																		<!--<div class="form-group">
																			<?= lang("street", "cus_street"); ?>
																			<?php echo form_input('cus_street', (isset($_POST['cus_street']) ? $_POST['cus_street'] : $applicant->street), 'class="form-control" id="cus_street"'); ?>
																		</div>-->
																		<div class="form-group">
																			<?= lang("housing", "cus_housing"); ?>
																			<?php
																			$cus_housing[(isset($_POST['cus_housing']) ? $_POST['cus_housing'] : '')] = (isset($_POST['cus_housing']) ? $_POST['cus_housing'] : $applicant->housing);
																			$cus_housing["owner"] = "Owner";
																			$cus_housing["living_with_parent"] = "Living with parent";
																			$cus_housing["renting"] = "Renting";
																			echo form_dropdown('cus_housing', $cus_housing, isset($customer->housing)?$customer->housing:'', 'class="form-control select" id="cus_housing" placeholder="' . lang("select") . ' ' . lang("housing") . '" style="width:100%" data-bv-notempty="true"');
																			?>
																		</div>
																		<!-- Housing end -->
																		<div class="form-group">
																			<b style="padding-bottom:5px; display:block;"><?= lang("time_at_this_address"); ?></b>
																			<?php echo form_input('cus_years', (isset($_POST['cus_years']) ? $_POST['cus_years'] : $applicant->years), 'class="form-control" id="cus_years" placeholder="' . lang("years") . '" style="display:inline !important; width:35% !important;"'); ?>
																			<?= lang("years", "cus_years"); ?>
																			<?php echo form_input('cus_months', (isset($_POST['cus_months']) ? $_POST['cus_months'] : $applicant->months), 'class="form-control" id="cus_months" placeholder="' . lang("months") . '" style="display:inline !important; width:35% !important;"'); ?>
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
								
								<div id="financial_products" style="display: none;" class="tab-pane fade">
									<div class="row">
										<div class="col-sm-12">
											<div class="col-sm-12">
													<div class="panel panel-primary">
														<div class="panel-heading"><?= lang('product_info') ?></div>
														<div class="panel-body" style="padding: 5px;">
															<div class="col-sm-12">
																<div class="col-md-4">
																	<!--<div class="form-group">
																		<?php echo lang('category', 'category') ?>
																		<?php
																		$cat_all[(isset($_POST['category_id']) ? $_POST['category_id'] : '')] = (isset($_POST['category_id']) ? $_POST['category_id'] : '');
																		if(array($categories)) {
																			foreach($categories as $cat_){
																				$cat_all[$cat_->id.'#'.$cat_->mfi] = $cat_->name;
																			}
																		}
																		echo form_dropdown('category_id', $cat_all, '', 'class="form-control category" placeholder="' . lang("select_category_to_load") . '" data-bv-notempty="true"');
																		?>
																		<input type="hidden" name="mfi" id="mfi" />
																	</div>-->
																	<div class="form-group">
																		<?php echo lang('category', 'category') ?>
																		<?php
																		$cat_all = array();
																		if(array($categories)) {
																			foreach($categories as $cat_){
																				$cat_all[$cat_->id .'#'.$cat_->mfi .'#'.$cat_->group_loan] = $cat_->name;
																			}
																		}
																		echo form_dropdown('category_id', $cat_all, (isset($_POST['category_id']) ? $_POST['category_id'] : ''), 'class="form-control category" id="category" placeholder="' . lang("select_category_to_load") . '" data-bv-notempty="true"');
																		?>
																		<input type="hidden" name="mfi" id="mfi" />
																		<input type="hidden" name="group_loan" id="group_loan" />
																	</div>
																</div>																
																<div class="col-md-4">
																	<div class="form-group">
																		<?php echo lang('sub_category', 'sub_category') ?>
																		<?php
																		echo form_input('sub_category', (isset($_POST['sub_category']) ? $_POST['sub_category'] : ''), 'class="form-control sub_category" id="sub_category"  placeholder="' . lang("select_category_to_load") . '" data-bv-notempty="true"');
																		?>
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group">
																		<?php echo lang('product', 'product_id') ?>
																		<?php
																		$pr_all = array();
																		if(array($products)) {
																			foreach($products as $pr_){
																				$pr_all[$pr_->id .'#'.$pr_->group_loan] = $pr_->name;
																			}
																		}
																		echo form_input('product_id', (isset($_POST['product']) ? $_POST['product'] : ''), ' class="form-control product_id" id="product_id"  placeholder="' . lang("select_product_to_load") . '" data-bv-notempty="true"');
																		
																		?>
																	</div>
																</div>
																
																<div class="col-md-4" id="group" style="display:none;">
																	<div class="form-group all">
																		<?= lang("group_loans", "group_loans") ?>
																		<?= form_input('group_loans', (isset($_POST['group_loans']) ? $_POST['group_loans'] : ''), ' class="form-control" id="group_loans"  ') ?>
																	</div>
																</div>
																
																<div class="col-md-4 show_cash">
																	<div class="form-group">
																		<?php echo lang('currency', 'currency') ?>
																		<?php
																		//$crr[(isset($_POST['currency']) ? $_POST['currency'] : '')] = (isset($_POST['currency']) ? $_POST['currency'] : '');
																		$crr = array();
																		if(array($currencies)) {
																			foreach($currencies as $currency){
																				$crr[$currency->code] = $currency->name;
																			}
																		}
																		echo form_dropdown('currency', $crr, (isset($crr['currency']) ? $crr['currency'] : ''), 'class="form-control currency" id="currency" placeholder="' . lang("select_currency") . '" data-bv-notempty="true"');
																		
																		?>
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group all">
																		<?= lang("amount", "total_amount") ?>
																		<?= form_input('price', (isset($_POST['price']) ? $_POST['price'] : ''), ' class="form-control total_loans" id="total_amount" style="font-size:20px;" data-bv-notempty="true"') ?>
																		<!--<input type="hidden" name="price" id="price" class="price">-->
																	</div>
																</div>
																<div class="col-md-4">
																	<div class="form-group all">
																		<?= lang("purpose", "purpose") ?>
																		<?= form_input('purpose', (isset($_POST['purpose']) ? $_POST['purpose'] : ''), ' class="form-control" id="purpose" style="font-size:14px;" ') ?>
																	</div>
																</div>			
																<div class="col-md-12 show_cash">
																	<div class="form-group all">
																		<?= lang('description', 'ldescription'); ?>
																		<textarea name="ldescription" id="ldescription"
																				  class="pa form-control kb-text ldescription"></textarea>
																	</div>
																</div>																
																<div class="col-md-4 hide_cash">
																	<div class="form-group all">
																		<?= lang("color", "color") ?>
																		<?php
																		$vari[""] = "";
																		if($variants) {
																			foreach ($variants as $variant) {
																				$vari[$variant->id] = $variant->name;
																			}
																		}
																		echo form_dropdown('color',  (isset($_POST['color']) ? $_POST['color'] : ''), 'id="color" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("color") . '"  class="form-control input-tip select"  style="width:100%;"');
																		?>
																	</div>
																</div>																
																<div class="col-md-4 hide_cash">
																	<div class="form-group all">
																		<?= lang("year", "year") ?>
																		<?php
																		$Y[""] = "";
																		$dur = date('Y') - 1990;
																		for($i=-1; $i<=$dur; $i++) {
																			$yyyy = date('Y', strtotime('-'.$i.' years'));
																			$Y[$yyyy] = $yyyy;
																		}
																		echo form_dropdown('year', $Y, (isset($_POST['year']) ? $_POST['year'] : ''), 'id="year" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("year") . '" class="form-control input-tip select" style="width:100%;"');
																		?>
																	</div>
																</div>																
																<!--<div class="col-md-4">
																	<div class="form-group all">
																		<?= lang("price", "price") ?>
																		<?= form_input('price', (isset($_POST['price']) ? $_POST['price'] : ''), ' class="form-control" id="total_amount" style="font-size:20pt;" ') ?>
																	</div>
																</div>-->
																
																<!--
																<div class="col-md-4 hide_cash">
																	<div class="form-group all">
																		<?= lang("engine", "engine") ?>
																		<?= form_input('engine', (isset($_POST['engine']) ? $_POST['engine'] : ''), 'class="form-control" id="engine"'); ?>
																	</div>
																</div>
																
																<div class="col-md-4 hide_cash">
																	<div class="form-group all">
																		<?= lang("frame_number", "frame") ?>
																		<?= form_input('frame', (isset($_POST['frame']) ? $_POST['frame'] : ''), 'class="form-control" id="frame"'); ?>
																	</div>
																</div>
																
																<div class="col-md-4 hide_cash">
																	<div class="form-group all">
																		<?= lang("power", "power") ?>
																		<?= form_input('power', (isset($_POST['power']) ? $_POST['power'] : ''), 'class="form-control" id="power"'); ?>
																	</div>
																</div>

																<div class="col-md-4 hide_cash">
																	<div class="form-group all">
																		<?= lang("distance_mile", "distance") ?>
																		<?= form_input('distance', (isset($_POST['distance']) ? $_POST['distance'] : ''), 'class="form-control" id="distance"'); ?>
																	</div>
																</div>
																-->
															</div>
														</div>
													</div>
											</div>
											
												<!--compulsory_saving--->
												
												<?php 													
													if( $setting->compulsory_saving =="enable" ){														
												?>
												<div class="col-sm-12">
													<div class="panel panel-primary">
														<div class="panel-heading"><?= lang('compulsory_saving') ?></div>
														<div class="panel-body" style="padding: 5px;">
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("saving_rate_%", "saving_rate"); ?>
																	<?php echo form_input('saving_rate', (isset($_POST['saving_rate']) ? $_POST['saving_rate'] : 0), 'class="form-control" id="saving_rate" '); ?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("saving_amount", "saving_amount"); ?>
																	<?php echo form_input('saving_amount', (isset($_POST['saving_amount']) ? $_POST['saving_amount'] : 0), 'class="form-control number_only" id="saving_amount" style="pointer-events: none;" readonly'); ?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("saving_interest_rate_%", "saving_interest_rate"); ?>
																	<?php echo form_input('saving_interest_rate', (isset($_POST['saving_interest_rate']) ? $_POST['saving_interest_rate'] : 0), 'class="form-control" id="saving_interest_rate" '); ?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("saving_type", "saving_type"); ?>
																	<?php
																	$saving_type[""] = "";
																	$saving_type[1] = "Normal";
																	echo form_dropdown('saving_type', $saving_type, (isset($_POST['saving_type']) ? $_POST['saving_type'] : 1), 'id="saving_type" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("saving_type") . '"  class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
														</div>
													</div>
												</div>
												<?php } ?>
												
												<!------>
												
												
												<?php 													
													if( $services ){
												?>
												<div class="col-sm-12">
													<div class="panel panel-primary">
														<div class="panel-heading"><?= lang('services_fee') ?></div>
														<div class="panel-body" style="padding: 5px;">
															<div class="col-sm-12">
																<div class="col-md-3">
																	<div class="form-group">
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<?= lang('amount'); ?>
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<?= lang('select_for_charge'); ?>
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<?= lang('tax'); ?>
																	</div>
																</div>
															</div>
															<?php
															$k = 0;
															if(array($services)) {
															foreach($services as $service) {
															?>
															<div class="col-sm-12">
																<div class="col-md-3">
																	<div class="form-group">
																		<?= $service->description; ?>
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<?php echo form_input('service_'.$service->id, (($service->method == 'Percentage')? $this->erp->formatNumber(($service->amount*100)).'%' : $this->erp->formatMoney($service->amount)), 'class="form-control input-tip services" id="services_'.$k.'" '); ?>
																		<input type="hidden" name="h_service_<?= $service->id ?>" class="h_service" id="h_service_<?= $k ?>" value="" />
																		<input type="hidden" name="h_type_<?= $service->id ?>" class="h_type" id="h_type<?= $k ?>" value="<?= $service->method ?>" />
																		<input type="hidden" name="service_paid_<?= $service->id ?>" class="service_paid" id="service_paid<?= $k ?>" value="<?= $service->service_paid ?>" />
																		<input type="hidden" name="charge_by_<?= $service->id ?>" class="charge_by" id="charge_by<?= $k ?>" value="<?= $service->charge_by ?>" />
																		
																</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<?php echo form_checkbox(['name' => 'ch_services[]', 'id' => $k, 'class' => 'ch_services','service_paid' => $service->service_paid, 'title' => $service->description, 'amount' => $service->amount, 'status' => $service->method, 'value' => $service->id, 'charge_by' => $service->charge_by, 'tax_rate' => $service->tax_rate]); ?>
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		
																		<?php 
																			$tax[(isset($_POST['state_tax']) ? $_POST['state_tax'] : '')] = (isset($_POST['state_tax']) ? $_POST['state_tax'] : '');
																			if(array($tax_rate)) {
																				foreach($tax_rate as $rate){
																					$tax[$rate->id .'#'. $rate->rate] = $rate->name;		
																				}
																			}
																			echo form_dropdown('state_tax',$tax,$service->tax_id, 'class="form-control state_tax" id="state_tax_'.$service->id .'" data-item="'.$service->id .'" placeholder="' . $service->name . '"');																		
																		?>
																		<input type="hidden" name="tax_rate_<?= $service->id ?>" id="tax_<?=$service->id?>" class="tax_rate" value="<?= $service->tax_rate ?>" />
																		<input type="hidden" name="tax_rateid_<?= $service->id ?>" id="tax_id_<?= $service->id ?>" class="tax_id" value="<?= $service->tax_id ?>"/>
																	</div>
																</div>
																<?php
																if($k == 0) {
																?>
																<!--<div class="col-md-3">
																	<div class="form-group">
																		<?php echo form_input('total_inst', (isset($_POST['total_inst']) ? $_POST['total_inst'] :0), 'class="form-control input-tip" id="total_inst"'); ?>
																	</div>
																</div>-->
																
																<?php
																} else {
																?>
																<div class="col-md-3">
																	<div class="form-group">
																	
																	</div>
																</div>
																<?php } ?>
															</div>
															<?php
																$k++;
															}
															}
															?>
														</div>
													</div>
												</div>
												<?php } ?>
												
												<div class="col-sm-12 hide_cash">
													<div class="panel panel-primary">
														<div class="panel-heading"><?= lang('financial_products') ?></div>
														<div class="panel-body" style="padding: 5px;">
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("financial_product", "financial_product"); ?>
																	<?php
																	$fin_pro[(isset($_POST['financial_product']) ? $_POST['financial_product'] : '')] = (isset($_POST['financial_product']) ? $_POST['financial_product'] : '');
																	if(array($finacal_products)) {
																		foreach ($finacal_products as $financial_product) {
																			$fin_pro[$financial_product->id] = $financial_product->name;
																		}
																	}
																	echo form_dropdown('financial_product', $fin_pro, (isset($_POST['financial_product']) ? $_POST['financial_product'] : ''), 'id="financial_product" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("finacal_product") . '"  class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("advance_percentage", "advance_percentage"); ?>
																	<?php
																	$percentage[""] = "";
																	if($advance_percentages) {
																		foreach ($advance_percentages as $advance_percentage) {
																			$percentage[$advance_percentage->amount] = $advance_percentage->description;
																		}
																	}
																	echo form_dropdown('advance_percentage', $percentage, (isset($_POST['advance_percentage']) ? $_POST['advance_percentage'] : ''), 'id="advance_percentage" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("advance_percentage") . '" class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("advance_payment", "advance_payment"); ?>
																	<?php echo form_input('advance_payment', (isset($_POST['advance_payment']) ? $_POST['advance_payment'] : 0), 'class="form-control input-tip" id="advance_payment" readonly'); ?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("lease_amount", "lease_amount"); ?>
																	<?php echo form_input('lease_amount', (isset($_POST['lease_amount']) ? $_POST['lease_amount'] : 0), 'class="form-control input-tip" id="lease_amount" readonly'); ?>
																	<input 	type="hidden" name="grand_amount" id="grand_amount" value="" />
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("frequency", "frequency"); ?>
																	<?php
																	$frequency[""] = "";
																	//$frequency[1] = "Daily";
																	$frequency[7] = "Weekly";
																	$frequency[14] = "Two Week";
																	$frequency[30] = "Monthly";
																	//$frequency[90] = "Quarterly";
																	//$frequency[180] = "Haft Year";
																	//$frequency[360] = "Yearly";
																	echo form_dropdown('frequency', $frequency, (isset($_POST['frequency']) ? $_POST['frequency'] : ''), 'id="frequency" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("frequency") . '"  class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
															<!--<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("rate_type", "rate_type"); ?>
																	<?php
																	
																	echo form_dropdown('rate_type', $rate_type, (isset($_POST['rate_type']) ? $_POST['rate_type'] : ''), 'id="rate_type" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("rate_type") . '"  class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>-->
															
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("interest_rate", "interest_rate"); ?>
																	<?php
																	$interest[""] = "";
																	if(array($interest_rates)) {
																		foreach ($interest_rates as $interest_rate) {
																			$interest[$interest_rate->amount] = $interest_rate->description;
																		}
																	}
																	echo form_dropdown('interest_rate', $interest, (isset($_POST['interest_rate']) ? $_POST['interest_rate'] : ''), 'id="interest_rate" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("interest_rate") . '" class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("term", "term"); ?>
																	<?php
																	$term[""] = "";
																	if(array($terms)) {
																		foreach ($terms as $tm) {
																			$term[$tm->amount] = $tm->description;
																		}
																	}
																	echo form_dropdown('term', $term, (isset($_POST['term']) ? $_POST['term'] : ''), 'id="term" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("term") . '" class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("installment_amount", "installment_amount"); ?>
																	<?php echo form_input('installment_amount', (isset($_POST['installment_amount']) ? $_POST['installment_amount'] : 0), 'class="form-control input-tip" id="installment_amount" readonly'); ?>
																</div>
															</div>
															<div class="col-lg-6 btn_print_payment_schedule" style="display:none;">
																<div class="form-group" style="padding-top:7.5%;">
																	
																	<input type="button" class="btn btn-primary" value="<?=lang('print_payment_schedule')?>" name="print_payment_schedule" id="print_payment_schedule" />
																	
																</div>
															</div>
														</div>
													</div>
												</div>
												
												<div class="col-sm-12 hide_cash-show" style="display:none">
													<div class="panel panel-primary">
														<div class="panel-heading"><?= lang('loan_information') ?></div>
														<div class="panel-body" style="padding: 5px;">
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("customer_type", "customer_type"); ?>
																	<?php
																	//$ [(isset($_POST['customer_type']) ? $_POST['customer_type'] : '')] = (isset($_POST['customer_type']) ? $_POST['customer_type'] : '');
																	$customer_type = array();
																	if(array($finacal_products)) {
																		foreach ($finacal_products as $cust_type) {
																			$customer_type[$cust_type->id] = $cust_type->name;
																		}
																	}
																	echo form_dropdown('customer_type', $customer_type, (isset($_POST['customer_type']) ? $_POST['customer_type'] : ''), 'id="customer_type" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("finacal_product") . '"  class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("start_installment_date", "st_inst_date"); ?>
																	<?php echo form_input('st_inst_date', (isset($_POST['st_inst_date']) ? $_POST['st_inst_date'] : ''), 'class="form-control date" id="st_inst_date" data-bv-notempty="true"'); ?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("payment_frequency", "frequency_cash"); ?>
																	<?php
																	$frequency_cash[""] = "";
																	$frequency_cash[1] = "Daily";
																	$frequency_cash[7] = "Weekly";
																	$frequency_cash[14] = "Two Week";
																	$frequency_cash[30] = "Monthly";
																	$frequency_cash[360] = "Yearly";
																	echo form_dropdown('frequency_cash', $frequency_cash, (isset($_POST['frequency_cash']) ? $_POST['frequency_cash'] : ''), 'id="frequency_cash" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("frequency") . '"  class="form-control input-tip select" style="width:100%;" data-bv-notempty="true"');
																	?>
																</div>
															</div>
															
															<!--<div class="col-lg-6" style="display:none;">
																<div class="form-group">
																	<?= lang("term", "term_cash"); ?>
																	<?php
																	$term[""] = "";
																	if(array($terms)) {
																		foreach ($terms as $tm) {
																			$term[$tm->amount] = $tm->description;
																		}
																	}
																	echo form_dropdown('term_cash', $term, (isset($_POST['term_cash']) ? $_POST['term_cash'] : ''), 'id="term_cash" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("term") . '" class="form-control input-tip select" style="width:100%;" data-bv-notempty="true"');
																	?>
																</div>
															</div>-->
															
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("term", "term_cash"); ?>
																	<?php echo form_input('term_cash', (isset($_POST['term_cash']) ? $_POST['term_cash'] :''), 'class="form-control input-tip" id="term_cash" data-bv-notempty="true"'); ?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("interest_rate", "interest_rate_cash_2"); ?>
																	<input type="hidden" name="interest_rate_cash" id="interest_rate_cash" class="interest_rate_cash"/>
																	<?= form_input('interest_rate_cash_2', (isset($inv->rate_text ) ? $inv->rate_text : ''), ' class="form-control" id="interest_rate_cash_2" style="font-size:14px;" data-bv-notempty="true"') ?>
																
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
																	//$rate_type["4"] = "All_Fixed";
																	$rate_type["5"] = "Seasons";
																	$rate_type["6"] = "Loan Amounts";																	
																	echo form_dropdown('rate_type_cash', $rate_type, (isset($_POST['rate_type_cash']) ? $_POST['rate_type_cash'] : 1), 'id="rate_type_cash" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("rate_type") . '"  class="form-control input-tip select" style="width:100%;" data-bv-notempty="true"');
																	?>
																</div>
															</div>
															<div class="col-lg-6" id="payment_time">
																<div class="form-group">
																	<?= lang("principle_frequency", "principle_frequency"); ?>
																	<?php echo form_input('principle_frequency', (isset($_POST['principle_frequency']) ? $_POST['principle_frequency'] : 1), 'class="form-control number_only" id="principle_frequency" data-bv-notempty="true"');?>
																</div>	
															</div>
															<div class="col-lg-6" style="display:none;">
																<div class="form-group">
																	<?= lang("total_interest_rate", "total_interest_rate"); ?>
																	<?php echo form_input('total_interest_rate', (isset($_POST['total_interest_rate']) ? $_POST['total_interest_rate'] : 0), 'class="form-control input-tip" id="total_interest_rate" readonly'); ?>
																</div>
															</div>
															
															<div class="col-lg-6 btn_print_payment_schedule_cash" style="vertical-align: middle; padding: 2.5% 0% 1% 1.3%;">
																<input type="button" class="btn btn-primary" value="<?=lang('print_payment_schedule')?>" name="print_payment_schedule_cash" id="print_payment_schedule_cash" />
															</div>
														</div>
													</div>
												</div>
										</div>
									</div>
								</div>
								
								<div id="join_lease" style="display:none;" class="tab-pane fade in">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<div class="row">
													<div class="col-lg-12">
														<div class="col-md-6">
															<div class="form-group">
																<?= lang("identify_type", "jl_identify_type"); ?>
																<?php
																	$ident_all[(isset($_POST['jl_identify_id']) ? $_POST['jl_identify_id'] : '')] = (isset($_POST['jl_identify_id']) ? $_POST['jl_identify_id'] : '');
																	if(array($jl_identify_type)) {
																		foreach($jl_identify_type as $ident_){
																			$ident_all[$ident_->id] = $ident_->name;
																		}
																	}
																	echo form_dropdown('jl_identify_id', $ident_all, '', 'class="form-control jl_identify_type" id="jl_identify_type" placeholder="' . lang("select_identify_to_load") . '" data-bv-notempty="true"');																					
																?>
															</div>
															<div class="form-group">
																<?= lang("name", "jl_name"); ?>
																<?php echo form_input('jl_name', (isset($_POST['jl_name']) ? $_POST['jl_name'] : ''), 'class="form-control" id="jl_name" data-bv-notempty="true"'); ?>
															</div>
															
															<!--<div class="form-group">
																<?= lang("family_name", "jl_family_name"); ?>
																<?php echo form_input('jl_family_name', (isset($_POST['jl_family_name']) ? $_POST['jl_family_name'] : ''), 'class="form-control" id="jl_family_name" data-bv-notempty="true"'); ?>
															</div>
															<div class="form-group">
																<?= lang("first_name", "jl_first_name"); ?>
																<?php echo form_input('jl_first_name', (isset($_POST['jl_first_name']) ? $_POST['jl_first_name'] : ''), 'class="form-control" id="jl_first_name" data-bv-notempty="true"'); ?>
															</div>-->
															<div class="form-group">
																<?= lang("date_of_birth", "jl_dob"); ?>
																<?php echo form_input('jl_dob', (isset($_POST['jl_dob']) ? $_POST['jl_dob'] : ''), 'class="form-control date" id="jl_dob" data-bv-notempty="true"'); ?>
															</div>													
															<div class="form-group">
																<?= lang("children_member", "jl_children_member"); ?>
																<?php echo form_input('jl_dependent_children', (isset($_POST['jl_dependent_children']) ? $_POST['jl_dependent_children'] : ''), 'class="form-control" id="jl_dependent_children"'); ?>
															</div>
															<div class="form-group">
																<?= lang("family_member", "jl_family_member"); ?>
																<?php echo form_input('jl_family_member', (isset($_POST['jl_family_member']) ? $_POST['jl_family_member'] : ''), 'class="form-control" id="jl_family_member" '); ?>
															</div>
														</div>
														<div class="col-md-6">															
															<!--<div class="form-group">
																<?= lang("family_name_other", "jl_family_name_other"); ?>
																<?php echo form_input('jl_family_name_other', (isset($_POST['jl_family_name_other']) ? $_POST['jl_family_name_other'] : ''), 'class="form-control" id="jl_family_name_other"'); ?>
															</div>
															<div class="form-group">
																<?= lang("first_name_other", "jl_first_name_other"); ?>
																<?php echo form_input('jl_first_name_other', (isset($_POST['jl_first_name_other']) ? $_POST['jl_first_name_other'] : ''), 'class="form-control" id="jl_first_name_other"'); ?>
															</div>-->
															<div class="form-group">	
																<label id="jl_identify" for="jl_gov_id"></label>
																<input type="hidden" name="jl_identify" id="jl_identify" class="jl_identify"  />																			
																<?php echo form_input('jl_gov_id', (isset($_POST['jl_gov_id']) ? $_POST['jl_gov_id'] : ''), 'class="form-control" id="jl_gov_id" data-bv-notempty="true"'); ?>
															</div>
															<div class="form-group">
																<?= lang("gender", "jl_gender"); ?>
																<?php
																$jl_gender[(isset($_POST['jl_gender']) ? $_POST['jl_gender'] : '')] = (isset($_POST['jl_gender']) ? $_POST['jl_gender'] : '');
																$jl_gender['male'] = "Male";
																$jl_gender['female'] = "Female";
																echo form_dropdown('jl_gender', $jl_gender, isset($_POST['jl_gender'])?$_POST['jl_gender']:'', 'class="form-control select" id="jl_gender" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%" data-bv-notempty="true"')
																?>
															</div>
															<div class="form-group">
																<?= lang("age", "jl_age"); ?>
																<?php echo form_input('jl_age', (isset($_POST['jl_age']) ? $_POST['jl_age'] : ''), 'class="form-control date" id="jl_age" style="pointer-events: none;"'); ?>
															</div>
															<div class="form-group">
																<?= lang("phone", "jl_phone_1"); ?>
																<input type="tel" name="jl_phone_1" class="form-control number_only" maxlength="11" id="jl_phone_1" value="<?php (isset($_POST['jl_phone_1']) ? $_POST['jl_phone_1'] : '') ?>"/>
															</div>
															<div class="form-group">
																<?= lang("status", "jl_status"); ?>																
																<?php echo form_input('jl_status', (isset($_POST['jl_status']) ? $_POST['jl_status'] : ''), 'class="form-control" id="jl_status" '); ?>
															</div>
														</div>
														<div class="col-sm-12">
															<div class="form-group">
																<?= lang("address", "jl_address"); ?>
																<?php echo form_textarea('jl_address', (isset($_POST['jl_address']) ? $_POST['jl_address'] : ""), 'class="form-control" id="jl_address" style="margin-top: 10px; height: 130px;"'); ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
					
								<div id="employee" style="display: none;" class="tab-pane fade">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<p><?= lang('enter_info'); ?></p>
												<div class="row">
													<div class="col-lg-12">
														<div class="panel panel-warning">
															<div class="panel-heading"><?= lang('current_employment') ?></div>
															<div class="panel-body" style="padding: 5px;">
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("position", "position"); ?>
																		<?php echo form_input('position',(isset($_POST['position']) ? $_POST['position'] : ''), 'class="form-control input-tip"  id="position"'); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("employment_status", "employment_status"); ?>
																		<?php
																			$emp_status = array('working' => 'Working','not_working' => 'Not Working');
																			echo form_dropdown('employment_status', $emp_status, (isset($_POST['employment_status']) ? $_POST['employment_status'] : $Settings->default_warehouse), 'id="employment_status" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("employment_status") . '"  style="width:100%;" ');
																			?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("employment_industry", "employment_industry"); ?>
																		<?php
																			$emp_industrial = array('' => '', 'manufacturing' => 'Manufacturing', 'industry' => 'Industry', 'factory' => 'Factory');
																			echo form_dropdown('employment_industry', $emp_industrial, (isset($_POST['employment_industry']) ? $_POST['employment_industry'] : ''), 'id="employment_industry" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("employment_industry") . '"  style="width:100%;" ');
																			?>
																	</div>
																</div>

																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("seniorities_level", "seniorities_level"); ?>
																		<?php
																			$seniorities_level = array('' => '', 'staff' => 'Staff', 'senior' => 'Senior', 'manager' => 'Manager', 'director' => 'director', 'ceo' => 'CEO');
																			echo form_dropdown('seniorities_level', $seniorities_level, (isset($_POST['seniorities_level']) ? $_POST['seniorities_level'] : ''), 'id="seniorities_level" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("seniorities_level") . '" style="width:100%;" ');
																			?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("work_place_name", "work_place_name"); ?>
																		<?php echo form_input('work_place_name', (isset($_POST['work_place_name']) ? $_POST['work_place_name'] : ''), 'class="form-control input-tip "   id="work_place_name"'); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("work_phone", "work_phone"); ?>
																		<?php echo form_input('work_phone', (isset($_POST['work_phone']) ? $_POST['work_phone'] : ''), 'class="form-control input-tip number_only" maxlength="11" id="work_phone" '); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?php echo form_checkbox(['name' => 'allow_call_to_work_place', 'value' => 1]); ?>
																		<?= lang("allow_call_to_work_place", "allow_call_to_work_place"); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<b style="padding-bottom:5px; display:block;"><?= lang("working_time_at_this_address"); ?></b>
																		<?php echo form_input('emp_years', '', 'class="form-control" id="emp_years" placeholder="' . lang("years") . '" style="display:inline !important; width:35% !important;"'); ?>
																		<?= lang("years", "emp_years"); ?>
																		<?php echo form_input('emp_months', (isset($_POST['emp_months']) ? $_POST['emp_months'] : ''), 'class="form-control" id="emp_months" placeholder="' . lang("months") . '" style="display:inline !important; width:35% !important;"'); ?>
																		<?= lang("months", "emp_months"); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("basic_salary", "basic_salary"); ?>
																		<?php echo form_input('basic_salary', (isset($_POST['basic_salary']) ? $_POST['basic_salary'] : ''), 'class="form-control input-tip" id="basic_salary"'); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("allowance_etc", "allowance_etc"); ?>
																		<?php echo form_input('allowance_etc', (isset($_POST['allowance_etc']) ? $_POST['allowance_etc'] : ''), 'class="form-control input-tip" id="allowance_etc"'); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("business_expense", "business_expense"); ?>
																		<?php echo form_input('business_expense', (isset($_POST['business_expense']) ? $_POST['business_expense'] : ''), 'class="form-control input-tip" id="business_expense" style="display:inline !important; width:80% !important;"'); ?>
																		<?= lang("month", "month"); ?>
																	</div>
																</div>
																
															</div>
														</div>
														
														
														<div class="panel panel-warning">
															<div class="panel-heading"><?= lang('current_employment_address') ?></div>
															<div class="panel-body" style="padding: 5px;">
																<div class="form-group">
																	<?= lang("address", "emp_address"); ?>
																	<?php echo form_textarea('emp_address', (isset($_POST['emp_address']) ? $_POST['emp_address'] : ""), 'class="form-control" id="emp_address" style="margin-top: 10px; height: 100px;"'); ?>
																</div>
																<!--<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("country", "emp_country"); ?>
																		<?php
																			if(array($countries)) {
																				foreach($countries as $ctry) {
																					$emp_country[$ctry->code] = $ctry->name;
																				}
																			}
																			echo form_dropdown('emp_country', $emp_country, (isset($_POST['country']) ? $_POST['country'] : ''), 'id="emp_country" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("country") . '" required="required" style="width:100%;" ');
																		?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("province", "emp_province"); ?>
																		<?php echo form_input('emp_province', (isset($_POST['emp_province']) ? $_POST['emp_province'] : ''), 'class="form-control" id="emp_province"  placeholder="' . lang("select_province") . '"');?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("district", "emp_district"); ?>
																		<?php echo form_input('emp_district', (isset($_POST['emp_district']) ? $_POST['emp_district'] : ''), 'class="form-control" id="emp_district"  placeholder="' . lang("select_province_to_load") . '"');?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("commune", "emp_communce"); ?>
																		<?php echo form_input('emp_communce', (isset($_POST['emp_communce']) ? $_POST['emp_communce'] : ''), 'class="form-control" id="emp_communce"  placeholder="' . lang("select_district_to_load") . '"');?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("village", "emp_village"); ?>
																		<?php echo form_input('emp_village', (isset($_POST['emp_village']) ? $_POST['emp_village'] : ''), 'class="form-control" id="emp_village"  placeholder="' . lang("select_communce_to_load") . '"');?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("street", "emp_street")?>
																		<?php echo form_input('emp_street', (isset($_POST['emp_street']) ? $_POST['emp_street'] : ''), 'class="form-control input-tip" id="emp_street"'); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("house_no", "emp_house_no")?>
																		<?php echo form_input('emp_house_no', (isset($_POST['emp_house_no']) ? $_POST['emp_house_no'] : ''), 'class="form-control input-tip" id="emp_house_no"'); ?>
																	</div>
																</div>-->
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div id="guarantors" style="display: none;" class="tab-pane fade">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">	
													<div class="row">
														<div class="col-lg-12">
															<div class="panel panel-primary">
																<div class="panel-heading"><?= lang('guarantors_1') ?></div>
																<div class="panel-body" style="padding: 5px;">																	
																	<div class="col-md-6">																		
																		<div class="form-group">
																			<?= lang("identify_type", "gr_identify_type"); ?>
																			<?php
																				$ident_all[(isset($_POST['gr_identify_id']) ? $_POST['gr_identify_id'] : '')] = (isset($_POST['gr_identify_id']) ? $_POST['gr_identify_id'] : '');
																				if(array($gr_identify_type)) {
																					foreach($gr_identify_type as $ident_){
																						$ident_all[$ident_->id] = $ident_->name;
																					}
																				}
																				echo form_dropdown('gr_identify_id', $ident_all, '', 'class="form-control gr_identify_type" id="gr_identify_type" placeholder="' . lang("select_identify_to_load") . '" data-bv-notempty="true"');																					
																			?>
																		</div>
																		<!--<div class="form-group">
																			<?= lang("government_id", "gov_id"); ?>
																			<?php echo form_input('gov_id', (isset($_POST['gov_id']) ? $_POST['gov_id'] : ''), 'class="form-control" id="gov_id" data-bv-notempty="true"'); ?>
																		</div>-->
																		<!--
																		<div class="form-group">
																			<?= lang("family_name", "family_name"); ?>
																			<?php echo form_input('family_name',(isset($_POST['family_name']) ? $_POST['family_name'] : ''), 'class="form-control tip" id="family_name" '); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("first_name", "first_name"); ?>
																		   <?php echo form_input('first_name', (isset($_POST['first_name']) ? $_POST['first_name'] : ''), 'class="form-control" id="first_name"'); ?>
																		</div>-->
																		<div class="form-group">
																			<?= lang("issue_by", "gr_issue_by"); ?>
																			<?php echo form_input('gr_issue_by', (isset($_POST['gr_issue_by']) ? $_POST['gr_issue_by'] : ''), 'class="form-control" id="gr_issue_by" '); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("name", "name"); ?>
																			<?php echo form_input('gt_name', (isset($_POST['gt_name']) ? $_POST['gt_name'] : ''), 'class="form-control" id="gt_name" data-bv-notempty="true"'); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("date_of_birth", "dob"); ?>
																			<?php echo form_input('dob', (isset($_POST['dob']) ? $_POST['dob'] : ''), 'class="form-control date" id="dob"'); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("phone", "phone_1"); ?>
																			<input type="tel" name="phone_1" class="form-control number_only" maxlength="11" id="phone_1" />
																		</div>
																		<div class="form-group">
																			<?= lang("job", "j_job_1"); ?>
																			<?php echo form_input('j_job_1', (isset($_POST['j_job_1']) ? $_POST['j_job_1'] : ''), 'class="form-control" id="j_job_1" '); ?>
																		</div>		
																	</div>
																	
																	<div class="col-md-6">
																		<div class="form-group">
																			<label id="gr_identify" for="gov_id"></label>
																			<input type="hidden" name="gr_identify" id="gr_identify" class="gr_identify"/>
																			<?php echo form_input('gov_id', (isset($_POST['gov_id']) ? $_POST['gov_id'] : ''), 'class="form-control" id="gov_id" data-bv-notempty="true"'); ?>
																		</div>
																		
																		<!--<div class="form-group">
																			<?= lang("family_name_other", "family_name_other"); ?>
																			<?php echo form_input('family_name_other', (isset($_POST['family_name_other']) ? $_POST['family_name_other'] : ''), 'class="form-control" id="family_name_other" '); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("first_name_other", "first_name_other"); ?>
																			<?php echo form_input('first_name_other', (isset($_POST['first_name_other']) ? $_POST['first_name_other'] : ''), 'class="form-control" id="first_name_other" '); ?>
																		</div>-->
																		<div class="form-group">
																			<?= lang("issue_date", "gr_issue_date"); ?>
																			<?php echo form_input('gr_issue_date', (isset($_POST['gr_issue_date']) ? $_POST['gr_issue_date'] : ''), 'class="form-control date" id="gr_issue_date"'); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("gender", "gender"); ?>
																			<?php
																			$gender[ (isset($_POST['gender']) ? $_POST['gender'] : '')] =  (isset($_POST['gender']) ? $_POST['gender'] : '');
																			$gender['male'] = "Male";
																			$gender['female'] = "Female";
																			echo form_dropdown('gender', $gender, isset($customer->gender)?$customer->gender:'', 'class="form-control select" id="gender" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%" ')
																			?>
																		</div>
																		<div class="form-group">
																			<?= lang("age", "age"); ?>
																			<?php echo form_input('age', (isset($_POST['age']) ? $_POST['age'] : ''), 'class="form-control" id="age" style="pointer-events:none;"');?>

																		</div>
																		<div class="form-group">
																			<?= lang("status", "g_status"); ?>																
																			<?php echo form_input('g_status', (isset($_POST['g_status']) ? $_POST['g_status'] : ''), 'class="form-control" id="g_status" '); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("address", "gl_1_address"); ?>
																			<?php echo form_textarea('gl_1_address', (isset($_POST['gl_1_address']) ? $_POST['gl_1_address'] : ""), 'class="form-control" id="gl_1_address" style="margin-top: 10px; height: 100px;"'); ?>
																		</div>
																	</div>
																</div>
															</div>
															<div class="panel panel-primary">
																<div class="panel-heading"><?= lang('guarantors_2') ?></div>
																<div class="panel-body" style="padding: 5px;">
																	
																	<div class="col-md-6">
																		<div class="form-group">
																			<?= lang("identify_type", "gr_identify_type_2"); ?>
																			<?php
																				$ident_all[(isset($_POST['gr_identify_id_2']) ? $_POST['gr_identify_id_2'] : '')] = (isset($_POST['gr_identify_id_2']) ? $_POST['gr_identify_id_2'] : '');
																				if(array($gr_identify_type_2)) {
																					foreach($gr_identify_type_2 as $ident_){
																						$ident_all[$ident_->id] = $ident_->name;
																					}
																				}
																				echo form_dropdown('gr_identify_id_2', $ident_all, '', 'class="form-control gr_identify_type_2" id="gr_identify_type_2" placeholder="' . lang("select_identify_to_load") . '" data-bv-notempty="true"');																					
																			?>
																		</div>
																		<!--<div class="form-group">
																			<?= lang("government_id", "gov_id2"); ?>
																			<?php echo form_input('gov_id2', (isset($_POST['gov_id2']) ? $_POST['gov_id2'] : ''), 'class="form-control" id="gov_id2" data-bv-notempty="true"'); ?>
																		</div>-->
																		<!--<div class="form-group">
																			<?= lang("family_name", "family_name"); ?>
																			<?php echo form_input('family_name2',(isset($_POST['family_name2']) ? $_POST['family_name2'] : ''), 'class="form-control tip" id="family_name2" '); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("first_name", "first_name2"); ?>
																		   <?php echo form_input('first_name2', (isset($_POST['first_name2']) ? $_POST['first_name2'] : ''), 'class="form-control" id="first_name2"'); ?>
																		</div>-->
																		<div class="form-group">
																			<?= lang("issue_by", "gr2_issue_by"); ?>
																			<?php echo form_input('gr2_issue_by', (isset($_POST['gr2_issue_by']) ? $_POST['gr2_issue_by'] : ''), 'class="form-control" id="gr2_issue_by" '); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("name", "name2"); ?>
																			<?php echo form_input('gt_name2', (isset($_POST['gt_name2']) ? $_POST['gt_name2'] : ''), 'class="form-control" id="gt_name2" data-bv-notempty="false"'); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("date_of_birth", "dob"); ?>
																			<?php echo form_input('dob2', (isset($_POST['dob2']) ? $_POST['dob2'] : ''), 'class="form-control date" id="dob2"'); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("phone", "phone_2"); ?>
																			<input type="tel" name="phone_2" class="form-control number_only" maxlength="11" id="phone_2"/>
																		</div>
																		<div class="form-group">
																			<?= lang("job", "j_job_2"); ?>
																			<?php echo form_input('j_job_2', (isset($_POST['j_job_2']) ? $_POST['j_job_2'] : ''), 'class="form-control" id="j_job_2" '); ?>
																		</div>		
																	</div>
																	
																	<div class="col-md-6">
																		<div class="form-group">
																			<label id="gr_identify_2" for="gov_id2"></label>
																			<input type="hidden" name="gr_identify_2" id="gr_identify_2" class="gr_identify_2"  />
																			<?php echo form_input('gov_id2', (isset($_POST['gov_id2']) ? $_POST['gov_id2'] : ''), 'class="form-control" id="gov_id2" data-bv-notempty="false"'); ?>
																		</div>
																		
																		<!--<div class="form-group">
																			<?= lang("family_name_other", "family_name_other2"); ?>
																			<?php echo form_input('family_name_other2', (isset($_POST['family_name_other2']) ? $_POST['family_name_other2'] : ''), 'class="form-control" id="family_name_other2" '); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("first_name_other", "first_name_other"); ?>
																			<?php echo form_input('first_name_other2', (isset($_POST['first_name_other2']) ? $_POST['first_name_other2'] : ''), 'class="form-control" id="first_name_other2" '); ?>
																		</div>-->
																		<div class="form-group">
																			<?= lang("issue_date", "gr2_issue_date"); ?>
																			<?php echo form_input('gr2_issue_date', (isset($_POST['gr2_issue_date']) ? $_POST['gr2_issue_date'] : ''), 'class="form-control date" id="gr2_issue_date"'); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("gender", "gender2"); ?>
																			<?php
																			$gender[ (isset($_POST['gender2']) ? $_POST['gender2'] : '')] =  (isset($_POST['gender2']) ? $_POST['gender2'] : '');
																			$gender['male'] = "Male";
																			$gender['female'] = "Female";
																			echo form_dropdown('gender2', $gender, isset($customer->gender)?$customer->gender:'', 'class="form-control select" id="gender2" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%" ')
																			?>
																		</div>
																		<div class="form-group">
																			<?= lang("age", "age2"); ?>
																			<?php echo form_input('age2', (isset($_POST['age2']) ? $_POST['age2'] : ''), 'class="form-control" id="age2" style="pointer-events:none;"');?>
																		</div>
																		<div class="form-group">
																			<?= lang("status", "g_status_2"); ?>																
																			<?php echo form_input('g_status_2', (isset($_POST['g_status_2']) ? $_POST['g_status_2'] : ''), 'class="form-control" id="g_status_2" '); ?>
																		</div>		
																		<div class="form-group">
																			<?= lang("address", "gl_2_address"); ?>
																			<?php echo form_textarea('gl_2_address', (isset($_POST['gl_2_address']) ? $_POST['gl_2_address'] : ""), 'class="form-control" id="gl_2_address" style="margin-top: 10px; height: 100px;"'); ?>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>											
											</div>
										</div>
									</div>
								</div>
									
									<div id="collateral" style="display: none;" class="tab-pane fade">
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
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
														<!--
															<div class="form-group">
																<?= lang("account_related", "account_related"); ?>
																<?php echo form_input('cl_account_related', (isset($_POST['cl_account_related']) ? $_POST['cl_account_related'] : ''), 'class="form-control" id="cl_account_related"');?>
															</div>	
														-->
															
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
													
													<!---Sethy home-->
													
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
											</div>
										</div>
									</div>
									
									
									<div id="documents" style="display: none;" class="tab-pane fade">
								        <div class="modal-body">
								            <p><?=lang("information_below") ?></p>
											
								            <div class="form-group">
								                <label for="document"><?=lang("current_address")?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="current_address" id="document">
								            </div>
											<div class="form-group">
								                <label for="document"><?= lang("family_book") ?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="family_book" id="document">
								            </div>
											<div class="form-group">
								                <label for="document"><?=lang("government_id")?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="ganervment_id" id="document">
								            </div>
											<div class="form-group">
								                <label for="document"><?=lang("house_photo")?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="house_photo" id="document">
								            </div>
											<div class="form-group">
								                <label for="document"><?=lang("store_photo")?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="store_photo" id="document">
								            </div>
											<div class="form-group">
								                <label for="document"><?=lang("employment_certificate")?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="employment_certificate" id="document">
								            </div>
											<!--<div class="form-group">
								                <label for="document">Applicant 4x6 Photo</label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="applicant_photo" id="document">
								            </div>-->
											<div class="form-group">
								                <label for="document"><?= lang("spouse_4x6_photo") ?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="spouse_photo" id="document">
								            </div>
											<div class="form-group">
								                <label for="document"><?= lang("guarantors_4x6_photo") ?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="guarantors_photo" id="document">
								            </div>
											<div class="form-group">
								                <label for="document"><?= lang("birth_registeration_letter") ?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="birth_registration_letter" id="document">
								            </div>
											<div class="form-group">
								                <label for="document"><?=lang("passport")?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="passport" id="document">
								            </div>
											<div class="form-group">
								                <label for="document"><?=lang("marriage_certificate")?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="marriage_certificate" id="document">
								            </div>
											<div class="form-group">
								                <label for="document"><?=lang("driver_license")?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="driver_license" id="document">
								            </div>
											
											<div class="form-group">
								                <label for="document"><?=lang("working_contract")?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="working_contract" id="document">
								            </div>
											<div class="form-group">
								                <label for="document"><?=lang("invoice_salary")?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="invoice_salary" id="document">
								            </div>
											<div class="form-group">
								                <label for="document"><?=lang("business_certificate")?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="business_certificate" id="document">
								            </div>
											<div class="form-group">
								                <label for="document"><?=lang("profit_for_the_last_three_months")?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="profit_for_the_last_3_month" id="document">
								            </div>
											<div class="form-group">
								                <label for="document"><?=lang("other_ducument")?></label>
								                <input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
								                	name="other_document" id="document">
								            </div>
								        </div>
									</div>
									
									<div id="fields_check" style="display:none;" class="tab-pane fade">
								        <div class="modal-body">
								            
											<!-- Fields Check -->
											
											<p><?= lang("the_current_address_base_on_documents_provide_below") ?></p>
											<div class="row">
												<div class="col-md-12 col-lg-12">
													<div class="col-md-3 col-sm-6">
														<input type="checkbox" name="fc_id_card" id="id_card"> <?= lang("identify_card") ?>
													</div>	
													<div class="col-md-4 col-sm-6">
														<input type="checkbox" name="fc_family_book" id="family_book"> <?= lang("family_book") ?> 
													</div>
													<div class="col-md-3 col-sm-6">
														<input type="checkbox" name="fc_staying_book" id="staying_book"> <?= lang("address_book") ?>
													</div>
													<div class="col-md-3 col-sm-6">
														<input type="checkbox" name="fc_water_invoice" id="water_invoice"> <?= lang("water_bill") ?>
													</div>
													<div class="col-md-4 col-sm-6">
													<input type="checkbox" name="fc_electricity_invoice" id="electricity_invoice"> <?= lang("electrical_bill") ?>
													</div>
													<div class="col-md-4 col-sm-6">		
														<input type="checkbox" name="fc_check_property" id="check_property"> <?= lang("asset_certificate") ?>
													</div>
													<div class="col-md-7 col-sm-6">		
														<input type="checkbox" name="fc_check_landlord" id="check_landlord"> <?= lang("chief_of_village_certify_letter") ?>
													</div>
													
													<div class="col-md-5">	
														<div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">	
														<input type="checkbox" name="fc_other" id="other"> <?= lang("other") ?>
														</div>
														<div class="col-md-8" style="padding-left: 0px; padding-right: 0px;">
															<?= form_input('fc_other_textbox',(isset($_POST['fc_other_textbox']) ? $_POST['fc_other_textbox'] : ''), 'class="form-control tip" id="other_textbox"') ?>
														</div>
													</div>
													<div>
															<div class="col-md-4"><p> <?= lang("current_address") ?></p></div>
															<div class="col-md-8"><?= form_input('fc_current_address',(isset($_POST['fc_current_address']) ? $_POST['fc_current_address'] : ''), 'class="form-control tip" id="current_address"') ?></div>
													</div>
													<div>
															<div class="col-md-4"><p> <?= lang("phone_number") ?></p></div>
															<div class="col-md-8"><?= form_input('fc_phone_number',(isset($_POST['fc_phone_number']) ? $_POST['fc_phone_number'] : ''), 'class="form-control tip" id="phone_number"') ?></div>
													</div>
												</div>
											</div>
											<div class="row">
												<?= lang("latitude") ?>: <span id="lat"></span><br/><input type="hidden" name="latitude_" id="latitude_" value="" />
												<?= lang("longitude") ?>: <span id="long"></span><input type="hidden" name="longtitute_" id="longtitute_" value="" />
												<br/>
												<div id="map" style="width:100%; height:300px;"></div>
												<br/>
											</div>
											
											<!--------fields_check----------------------------
											<div class="row" style="display:none;">
												<div class="col-md-12 col-lg-12">
													<p>បរិយាយកាសការងារ</p>
														<div class="col-md-2">
															<input type="checkbox" name="fc_business1" id="business1">ជំនួញ
														</div>
														<div class="col-md-2">
														<input type="checkbox" name="fc_company1" id="company1">ក្រុមហ៊ុន
														</div>
														<div class="col-md-2">
														<input type="checkbox" name="fc_other1" id="other1">ផ្សេងៗ
														</div>
														<div>
															<div class="col-md-2"><p>ឈ្មោះ</p></div>
															<div class="col-md-3"><?= form_input('fc_name',"", 'class="form-control tip" id="name"') ?></div>
														</div>
													</div>	
													<div class="col-md-12 col-lg-12">
														<div class="col-md-2">
															<input type="checkbox" name="fc_business2" id="business2">ជំនួញ
														</div>
														<div class="col-md-2">
														<input type="checkbox" name="fc_company2" id="company2">ក្រុមហ៊ុន
														</div>
														<div class="col-md-2">
														<input type="checkbox" name="fc_other2" id="other2">ផ្សេងៗ
														</div>
														<div>
															<div class="col-md-2"><p>លេខទូរសព្ទ</p></div>
															<div class="col-md-3"><?= form_input('fc_phone',(isset($_POST['fc_phone']) ? $_POST['fc_phone'] : ''), 'class="form-control tip" id="name"') ?></div>
														</div>
													</div>	
													<div class="col-md-12 col-lg-12">
														<div class="col-md-2">
															<input type="checkbox" name="fc_business3" id="business3">ជំនួញ
														</div>
														<div class="col-md-2">
														<input type="checkbox" name="fc_company3" id="company3">ក្រុមហ៊ុន
														</div>
														<div class="col-md-2">
														<input type="checkbox" name="fc_other3" id="other3">ផ្សេងៗ
														</div>
														<div>
															<div class="col-md-2"><p>អាសយដ្ឋាន​បច្ចុប្បន្ន</p></div>
															<div class="col-md-3"><?= form_input('fc_address',(isset($_POST['fc_address']) ? $_POST['fc_address'] : ''), 'class="form-control tip" id="current_address"') ?></div>
														</div>
													</div>	
													<div class="col-md-12 col-lg-12">
														<div class="col-md-2">
															<input type="checkbox" name="fc_business4" id="business4">ជំនួញ
														</div>
														<div class="col-md-2">
														<input type="checkbox" name="fc_company4" id="company4">ក្រុមហ៊ុន
														</div>
														<div class="col-md-2">
														<input type="checkbox" name="fc_other4" id="other4">ផ្សេងៗ
														</div>
														<div class="col-md-12" style="padding-top:10px;">
															<div class="col-md-3"><p>ម៉ោងធ្វើកា​រពី</p></div>
															<div class="col-md-2"><?= form_input('fc_start_time',(isset($_POST['fc_start_time']) ? $_POST['fc_start_time'] : ''), 'class="form-control tip" id="start_time"') ?></div>
															<div class="col-md-1"><p>ដល់</p></div>
															<div class="col-md-2"><?= form_input('fc_end_time',(isset($_POST['fc_end_time']) ? $_POST['fc_end_time'] : ''), 'class="form-control tip" id="end_time"') ?></div>
														</div>
													</div>
													<div class="col-md-12 col-lg-12">
														<div class="col-md-12" style="padding-top:10px;">
															<div class="col-md-3"><p>ធ្វើការប៉ុន្មានថ្ងៃក្នុងមួយសប្តាហ៍?</p></div>
															<div class="col-md-9"><?= form_input('hours',(isset($_POST['hours']) ? $_POST['hours'] : ''), 'class="form-control tip" id="hours"') ?></div>
														</div>
													</div>
												</div>
											</div>---------->
											
											<div class="row">
											<div class="col-md-12">
											<br/>
											<!--<div id="map-canvas" style="height:100%;width:100%"></div>-->
											<!--
											<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d690.9392807942934!2d104.91320950409512!3d11.584597304753792!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xed6f304ce6b0ced0!2sCloudNET+Cambodia!5e0!3m2!1sen!2skh!4v1471332406301" width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
										
											LAT: <span id="lat"></span><br/>
											LONG: <span id="long"></span>
											<div id="map" style="width:100%; height:300px;"></div>
																					
												-->
											<br/>
											</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<p><?= lang("approval_note") ?>:</p>
														<!--<div class="col-md-2">
															<input type="checkbox" name="fc_evaluate" id="evaluate">ចុះវាយតំលៃ
														</div>
														<div class="col-md-2">
															<input type="checkbox" name="fc_none_evaluate" id="none_evaluate">មិនចុះវាយតំលៃ
														</div>-->
													<div class="table-responsive">	
														<table class="table" >
															<tbody> 
															  <tr>
																<td><input type="checkbox" name="fc_evaluate" id="evaluate"> <?= lang("filed_check")?></td>
																<td><input type="checkbox" name="fc_none_evaluate" id="none_evaluate"> <?= lang("no_filed_check")?></td>
																<td><p> <?= lang("co_name")?> :</p></td>
																<td><?= form_input('official_evaluate',(isset($_POST['official_evaluate']) ? $_POST['official_evaluate'] : ''), 'class="form-control tip" id="official_evaluate"') ?></td>
																<td><p> <?= lang("phone_number")?> :</p></td>
																<td><?= form_input('official_num',(isset($_POST['official_num']) ? $_POST['official_num'] : ''), 'class="form-control tip" id="official_num"') ?></td>
															  </tr>
															</tbody>
														</table>
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
		$("#cus_province").trigger('change');
		$("#cus_district").trigger('change');
		$('#cus_marital_status').trigger('change');
		$('#identify_type').trigger('change');
		
		$('#cus_country').trigger('change');
		$('#cus_province').trigger('change');
		$('#cus_district').trigger('change');
		$('#cus_communce').trigger('change');
		$('#country').trigger('change');
		$('#province').trigger('change');
		$('#district').trigger('change');
		$('#communce').trigger('change');
	});
	
	/*========================State_tax=========================*/
	var rate = new Array();
	$('.state_tax').on( "change", function() {
		var tax_rates = $(this).val();
		var _id = $(this).attr('data-item');
		var rates = tax_rates.split('#');
		var rate = (rates[1]/100);
		var tax_id = rates[0];
		$('#tax_'+ _id).val(rate);
		$('#tax_id_'+ _id).val(tax_id);
		$('#tax_'+ _id).trigger('change');
		$('#tax_id_'+ _id).trigger('change');
	});
	
	$(document).ready(function() {
		var count_link=0;
		$('#print_payment_schedule').click(function() {
				var product = $('.product_id').select2('data');
				localStorage.setItem('product', product.text);	
				var dealer = $('#qubiller').select2('data');
				if(dealer !=null){
				localStorage.setItem('dealer', dealer.text);	
				}else{
					localStorage.setItem('dealer', '');	
				}
				var year=	$('#year').select2('data');
				if(year !=null){
				localStorage.setItem('year', year.text);	
				}else{localStorage.setItem('year', '');}
				
				var power=	$('#power').val();
				if(power !=null){
				localStorage.setItem('power', power);
				}else{localStorage.setItem('power', '');}
				var Advance_payment_rate=$('#advance_percentage').val();
				localStorage.setItem('Advance_payment_rate', Advance_payment_rate);
				var phone=$('#cus_phone_1').val();
				var phone1=$('#cus_phone_2').val();
				
				if(phone1!=''){
					phone+=' / '+phone1;
				}
				localStorage.setItem('phone', phone);
				
				var price=$('#total_amount').val();
				localStorage.setItem('price', price);
				
				var interest_rate=$('#interest_rate').val();
				localStorage.setItem('interest_rate', interest_rate);
				var term=$('#term').val();
				localStorage.setItem('term', term);
				var rate_type=$('#rate_type').val();
				var lease_amount=$('#lease_amount').val();
				
				var name=$('#cus_family_name').val()+' '+$('#cus_first_name').val();
				
				var other_fname=$('#cus_first_name_other').val();
				var other_name=$('#cus_family_name_other').val();
				if(other_fname && other_name !=''){
					name +='( '+other_name+' '+other_fname+' )';
				}
				
				
				localStorage.setItem('name', name);
				
				var leaseamount=lease_amount.replace(",", "");
				var frequency = $('#frequency').val();
				
				var link= $('<a href="Installment_payment/payment_schedule_preview/'+leaseamount+'/'+rate_type+'/'+interest_rate+'/'+term+'/'+frequency+'" rel="lightbox" id="print_payment'+count_link+'" data-toggle="modal" data-target="#myModal"></a>');
			
						$("body").append(link);
						  $('#print_payment'+count_link).click();
			count_link++;
			
		});
		var count_link1=0;
		$('#print_payment_schedule_cash').live( "click", function() {
			//get_customer_name
			var name=$('#cus_family_name').val()+' '+$('#cus_first_name').val();	
			localStorage.setItem('name', name);
			var latin=$('#cus_family_name_other').val()+' '+$('#cus_first_name_other').val();
			localStorage.setItem('latin', latin);
			//get phone...
			var phone=$('#cus_phone_1').val();
			var phone1=$('#cus_phone_2').val();
			if(phone1!=''){
				phone+=' / '+phone1;
			}
			localStorage.setItem('phone', phone);
			//get collateral
			var collateral=$('.select2-chosen').val() ? $('.select2-chosen').val() : 'N/A';
			localStorage.setItem('collateral', collateral);
			//get cur_address
			var cus_house_no =$('#cus_house_no').val();
			localStorage.setItem('cus_house_no',cus_house_no);
			//get by co
			var co_name = $("#cus_by_co option:selected").text();
			localStorage.setItem('co_name', co_name);
			//get purpose
			var purpose = $('#purpose').val() ? $('#purpose').val() : 'N/A';
			localStorage.setItem('purpose', purpose);
			
			var cus_family_name = $('#cus_family_name').val();
			var cus_family_name_other = $('#cus_family_name_other').val();
			var cus_first_name = $('#cus_first_name').val();
			var cus_first_name_other = $('#cus_first_name_other').val();
			var ldescription = $('#ldescription').val()? $('#ldescription').val() : 'N/A';
			var services = '';
			
			
			var i = 0;
			$('.ch_services').each(function() {
				if($(this).is(':checked')) {
					if(i == 0) {
						services = $(this).val();
					}else {
						services = '___'+ $(this).val();
					}
					i += 1;
				}
			});
			//localStorage.setItem('cust_name', ((cus_family_name && cus_first_name)? cus_family_name+' '+cus_first_name : 'N/A'));
			//localStorage.setItem('cust_name_other', ((cus_family_name_other && cus_first_name_other)? cus_family_name_other+' '+cus_first_name_other : 'N/A'));
			//localStorage.setItem('ldescription', ldescription);
			//localStorage.setItem('services', services);
			
			var lease_amount = $('#total_amount').val();
			var interest_rate = $('#interest_rate_cash').val();			
			var rate_type = $('#rate_type_cash').val();
			var frequency_cash = $('#frequency_cash').val()? parseFloat($('#frequency_cash').val()) : 0;
			var term = $('#term_cash').val()? parseFloat($('#term_cash').val()) : 0;
			var term_cash = frequency_cash * term;
			
			var principle_fq = $('#principle_frequency').val();
			var currency = (($('#currency').val())? $('#currency').val():0);
			
			var cdate = ($('#st_inst_date').val()).split('/');
			var new_date = '';
			for(var i = 0; i < cdate.length; i++) {
				if(i == 0) {
					new_date = cdate[i];
				}else {
					new_date += '___'+cdate[i];
				}
			}
			//alert(lease_amount +'###'+ interest_rate +'###'+ term_cash +'###'+ rate_type +'###'+ frequency_cash);
			
			var services = '';
			$(".ch_services:checked").each(function(){	
				var s_id = $(this).val();
				var amount = $(this).attr('amount');
				var type = $(this).attr('status');
				var service_paid = $(this).attr('service_paid');
				var charge_by = $(this).attr('charge_by');
				var tax = $(this).attr('tax_rate');
				
				
				
				if(services == '') {
					services = s_id +"__" + amount + "__" + type +"__" + service_paid +"__" + charge_by +"__"+tax;
				}else {
					services += "___" + s_id +"__" + amount + "__" + type +"__" + service_paid +"__" + charge_by +"__"+tax;
				}
			});
			
			////////////Saving
			var saving_rate = $('#saving_rate').val()? ($('#saving_rate').val()) : 0; 
			var saving_rate_amt = saving_rate_amt ? saving_rate.replace('%', '') : 0;			
			var saving_rate_amount = (saving_rate_amt/100);
			
			
			var saving_amt = $('#saving_amount').val()? ($('#saving_amount').val()) : 0; 
			var saving_amount = saving_amt ? formatDecimal(saving_amt.split(',').join('')) : 0 ;
			
			var saving_interest_rate = $('#saving_interest_rate').val()? ($('#saving_interest_rate').val()) : 0;
			var saving_interest_amt = saving_interest_rate ?  saving_interest_rate.replace('%', '') : 0; 
			var saving_interest_amount = (saving_interest_amt/100);
			
			var saving_type = $('#saving_type').val()? ($('#saving_type').val()) : 0;
			//
			if(services == '') {
				var link1= $('<a href="Quotes/cash_payment_schedule_preview/'+lease_amount+'/'+rate_type+'/'+interest_rate+'/'+term_cash+'/'+frequency_cash+'/'+currency+'/'+new_date+'/'+principle_fq+'/'+null+'/'+saving_amount+'/'+saving_interest_amount+'/'+saving_type+'" rel="lightbox" id="print_payment'+count_link1+'" data-toggle="modal" data-target="#myModal"></a>');
			}else {
				var link1= $('<a href="Quotes/cash_payment_schedule_preview/'+lease_amount+'/'+rate_type+'/'+interest_rate+'/'+term_cash+'/'+frequency_cash+'/'+currency+'/'+new_date+'/'+principle_fq+'/'+services+'/'+saving_amount+'/'+saving_interest_amount+'/'+saving_type+'" rel="lightbox" id="print_payment'+count_link1+'" data-toggle="modal" data-target="#myModal"></a>');
			}	
				$("body").append(link1);
				$('#print_payment'+count_link1).click();
				
			count_link1++;
		});
		/*======== show view details Applicant by identify_id ====================*/
		var count_gov = 0;
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
						var link = $('<a href="quotes/gov_id_report/'+data.id +'" rel="lightbox" id="link'+count_gov+'" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"></a>');
						//var link = $('<a href="quotes/add/'+data.id +'" rel="lightbox" id="link'+count_gov+'" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"></a>');
						  $("body").append(link);
						  $("#link"+count_gov).click();
						count_gov++;
					}
				}
			});
		});
		
		/*======= Alert Msg group_loan when same group_name====*/
		$('#group_loans').on('change', function() {
			var group_loans = $(this).val();
			$.ajax({
				type: 'get',
				url: '<?= site_url('quotes/getExistingGroupLoanIDInfo'); ?>',
				dataType: "json",
				data: {
					gl_id : group_loans
				},
				success: function (data) {
					if(data.id) {
						alert('This group loan has already.');
					}
				}
			});
		});		
		
		$('#total_amount').on('change', function() {
			$('.ch_services').trigger('ifChanged');
			if($('#advance_percentage').val()) {
				$('#advance_percentage').trigger('change');
			}
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
		///////////
		/*$('#category').on('change', function(){
			var category = $(this).val();
			var category_val = category.split('#');
			$('#group_loan').val(category_val[2]);
			if(category_val[2]){
				$('#group_loans1').show();
			}else{
				$('#group_loans1').hide();
			}			
		});*/
		
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
				//error: function () {
				//	bootbox.alert('<?= lang('ajax_error') ?>');
				//	$('#modal-loading').hide();
				//}
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
		
		$('.services').on('change', function() {
			var service_amount = $(this).val();
			var ch_id = $(this).attr('id');
			var c_id = ch_id.split('_');
			var s_amount = service_amount.split('%');
			if(service_amount.search('%') != -1) {
				$('#'+c_id[1]).attr('amount', (s_amount[0]/100));
				$('#'+c_id[1]).attr('status', 'Percentage');
			}else {
				$('#'+c_id[1]).attr('amount', s_amount[0]);
				$('#'+c_id[1]).attr('status', 'Fixed_Amount');
			}
			$('.ch_services').trigger('ifChanged');
		});
		
		$('.ch_services').on('ifChanged', function(){
			var total = 0;
			var price = $('#total_amount').val()-0;
			$('.ch_services').each(function() {
				var chk_id = $(this).attr('id');
				var amount = 0;
				if($(this).is(':checked')) {
					if($(this).attr('status') === 'Percentage') {
						if(price > 0) {
							amount = $(this).attr('amount') * price;
						}else {
							price = price.toLowerCase();
							if(price.search('k') < 0 || price.search('m') < 0) {
								alert('Please enter price!');
								return false;
							}
						}
					}else {
						amount = $(this).attr('amount')-0;
					}
					$('#h_service_'+chk_id).val(amount);
					total += amount;
				}
			});
			$('#total_inst').val(formatMoney(total));
		});
		$('#advance_percentage').on('change', function() {
			var total_amount = parseFloat($('#total_amount').val());
			if(total_amount > 0) {
				var advance_percent = parseFloat($(this).val());
				var advance_payment = total_amount * advance_percent;
				var lease_amount = total_amount - advance_payment;
				$('#advance_payment').val(formatMoney(advance_payment));
				$('#lease_amount').val(formatMoney(lease_amount));
				$('#grand_amount').val(lease_amount);
				$('#grand_amount').trigger('change');
			} else {
				$('#installment_amount').val(formatMoney(0));
				$('.btn_print_payment_schedule').hide();
				alert("Please input price of asset first!");
			}
		});
		$('#interest_rate, #term, #grand_amount, #rate_type').on('change', function() {
			var interest = parseFloat($('#interest_rate').val());
			var term = Number($('#term').val());
			var lease_amount = parseFloat($('#grand_amount').val());
			var rate_type = $('#rate_type').val();
			
			if(interest > 0 && term > 0 && lease_amount > 0 && rate_type != '') {
				var installment_amount = getInstallmentAmount(lease_amount, rate_type, interest, term);
				$('#installment_amount').val(formatMoney(installment_amount));
				$('.btn_print_payment_schedule').show();
			} else {
				$('#installment_amount').val(formatMoney(0));
				$('.btn_print_payment_schedule').hide();
			}		
		});
		
		$('#interest_rate_cash, #term_cash, #total_amount, #rate_type_cash, #frequency_cash, #principle_frequency').on('change', function(){
			
			var interest = parseFloat($('#interest_rate_cash').val());
			var frequency_cash = $('#frequency_cash').val()? parseFloat($('#frequency_cash').val()) : 0;
			var term_cash = $('#term_cash').val()? parseFloat($('#term_cash').val()) : 0;
			var term = frequency_cash * term_cash;
			//var term = Number($('#term_cash').val());
			var lease_amount = parseFloat($('#total_amount').val());
			var rate_type = $('#rate_type_cash').val();
			var principle_fq = $('#principle_frequency').val();
			
			//alert(rate_type);
			if(lease_amount > 0 && interest > 0 && term > 0 && rate_type != '' && frequency_cash > 0) {
				var all_total = getAllTotal(lease_amount, rate_type, interest, term, frequency_cash, principle_fq);
				$('#total_interest_rate').val(formatMoney(all_total['total_interest']));
				$('.btn_print_payment_schedule_cash').show();
			} else {
				$('#total_interest_rate').val(formatMoney(0));
				$('.btn_print_payment_schedule_cash').hide();
			}		
		});
		
		$('#field_check').live('click', function() {
			initMap();
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
	function initMap() {
		var map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: -34.397, lng: 150.644},
			zoom: 19
        });
		var infoWindow = new google.maps.InfoWindow({map: map});
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				var pos = {
					lat: position.coords.latitude,
					lng: position.coords.longitude
				};
				document.getElementById("lat").innerHTML = pos['lat'];
				document.getElementById("latitude_").value = pos['lat'];
				document.getElementById("long").innerHTML = pos['lng'];
				document.getElementById("longtitute_").value = pos['lng'];
				infoWindow.setPosition(pos);
				infoWindow.setContent('<i class="fa fa-street-view" aria-hidden="true"></i>');
				map.setCenter(pos);
			}, function() {
				handleLocationError(true, infoWindow, map.getCenter());
			});
        } else {
			handleLocationError(false, infoWindow, map.getCenter());
		}
	}

	function handleLocationError(browserHasGeolocation, infoWindow, pos) {
		infoWindow.setPosition(pos);
		infoWindow.setContent(browserHasGeolocation ? 'Error: The location service failed.' : 'Error: Your browser doesn\'t support location.');
	}
	
	<!--SETHY-->
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
	
		$('#spname').hide();
		$('#spphone').hide();
		$('#spchild').hide();
		$('#whoseincome').hide();
		$('#incomecombine').hide();	
		$('#sp_date').hide();
		$('#sp_status').hide();
		$('#sp_gender').hide();	
		$('#cus_marital_status').on('change', function() {		
			if($('#cus_marital_status').val()=="married"){					
				$('#spname').show();
				$('#spphone').show();
				$('#spchild').show();
				$('#whoseincome').show();
				$('#incomecombine').show();
				$('#sp_date').show();
				$('#sp_status').show();
				$('#sp_gender').show();
				
			}
			if($('#cus_marital_status').val()!="married"){			
				$('#spname').hide();
				$('#spphone').hide();
				$('#spchild').hide();	
				$('#whoseincome').hide();
				$('#incomecombine').hide();
				$('#sp_date').hide();
				$('#sp_status').hide();
				$('#sp_gender').hide();				
			}
		});
		//Seasons
		$('#payment_time').hide();
		$('#rate_type_cash').on('change', function() {		
			if($('#rate_type_cash').val()=="5"){					
				$('#payment_time').show();
			}else{					
				$('#payment_time').hide();
			}
		});
		
		$('#identify_type').live('change', function() {
			var id_type = $("#identify_type option:selected").text();
			$('#h_identify').val(id_type);
			$('#identify').text(id_type);
		});
		$('#jl_identify_type').live('change', function() {
			var id_type = $("#jl_identify_type option:selected").text();
			$('#jl_identify').val(id_type);
			$('#jl_identify').text(id_type);
		});
		$('#gr_identify_type').live('change', function() {
			var id_type = $("#gr_identify_type option:selected").text();
			$('#gr_identify').val(id_type);
			$('#gr_identify').text(id_type);
		});
		
		$('#gr_identify_type_2').live('change', function() {
			var id_type = $("#gr_identify_type_2 option:selected").text();
			$('#gr_identify_2').val(id_type);
			$('#gr_identify_2').text(id_type);
		});
		/*----interest_rate----*/
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
		
		$('#saving_rate, #total_amount').on('keyup , change', function() {
			var saving_rate = $(this).val();
			var total_amount = $('#total_amount').val();
			var saving_rates = saving_rate.replace('%', '');
			if(saving_rate.search('%') > 0) { 
				var saving = (saving_rates/100);
				var saving_amt = total_amount * saving ;
				$('#saving_amount').val(formatMoney(saving_amt));
			}
		});
		
		
		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					$('#inputimg').attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}
		
		$("#document").change(function(){
			readURL(this);
		});
		
		///////////////////		
		/*$(document).ready(function(){
		
			$('#total_amount').keyup(function(event) {

				  // skip for arrow keys
				  if(event.which >= 37 && event.which <= 40) return;

				  // format number
				  $(this).val(function(index, value) {
					return value
					.replace(/\D/g, "")
					.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
					;
				  });
				});
			
		});*/
		
</script>


<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDqn76Ds7-8TecI83wsTceWqK_WCIj1P5c&callback=initMap"></script>
<!-- end get lat long -->
