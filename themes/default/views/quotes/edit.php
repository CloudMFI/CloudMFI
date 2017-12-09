<?php
	//$this->erp->print_arrays($app_num);
?>
<style>
	.comment-wrap {
		max-width:100%;
		min-width:100%;
		position:relative;
	}
	.comment-list {
		width: auto;
		height: 80vh;
		overflow-y: scroll;
	}
	.user-comment {
		float: left;
		width:55%;
		background-color: rgba(183, 209, 218, 0.5);
		padding:15px;
		border-radius: 8px;
		-moz-border-radius: 8px;
		-webkit-border-radius: 8px;
	}
	.user-current-comment {
		background-color: rgba(183, 209, 218, 0.5);
	}
	.user-other {
		background-color: rgba(228, 223, 218, 0.5);
	}
	.user-name {
		padding-top:5px;
		width:10%;
		margin-right:20px;
		font-size:14px;
		font-weight:700;
		text-align:right;
	}
	.comment-date {
		padding-top:5px;
		color: #7e7f9a;
		margin-left:2%;
	}
</style>
<script type="text/javascript">
    var count = 1, an = 1, DT = <?= $Settings->default_tax_rate ?>, allow_discount = <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? 1 : 0; ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, shipping = 0,
        tax_rates = <?php echo json_encode($tax_rates); ?>;
    var audio_success = new Audio('<?=$assets?>sounds/sound2.mp3');
    var audio_error = new Audio('<?=$assets?>sounds/sound3.mp3');
    $(document).ready(function () {
		
        <?php if ($inv) { ?>
        localStorage.setItem('qudate', '<?= date($dateFormats['php_ldate'], strtotime($inv->date))?>');
        localStorage.setItem('qucustomer', '<?=$inv->customer_id?>');
        localStorage.setItem('qubiller', '<?=$inv->biller_id?>');
        localStorage.setItem('quref', '<?=$inv->reference_no?>');
        localStorage.setItem('quwarehouse', '<?=$inv->warehouse_id?>');
        localStorage.setItem('qustatus', '<?=$inv->quote_status?>');
        localStorage.setItem('qunote', '<?= str_replace(array("\r", "\n"), "", $this->erp->decode_html($inv->note)); ?>');
        localStorage.setItem('qudiscount', '<?=$inv->order_discount_id?>');
        localStorage.setItem('qutax2', '<?=$inv->order_tax_id?>');
        localStorage.setItem('qushipping', '<?=$inv->shipping?>');
        localStorage.setItem('quitems', JSON.stringify(<?=$inv_items;?>));
        <?php } ?>
		
		/*<?php if($product->group_name != null)  {?>				
			$('.categ').attr("readonly",true);
			$('.sub_categ').attr("readonly",true);
			$('.produc').attr("readonly",true);
			$('.gro_loan').attr("readonly",true);
		<?php  } ?> */
		
        <?php if ($Owner || $Admin) { ?>
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
                        warehouse_id: $("#quwarehouse").val(),
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
                    //$(this).val('');
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
					// $(this).val('');
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

        $(window).bind('beforeunload', function (e) {
            $.get('<?= site_url('welcome/set_data/remove_quls/1'); ?>');
            if (count > 1) {
                var message = "You will loss data!";
                return message;
            }
        });
        $('#reset').click(function (e) {
            $(window).unbind('beforeunload');
        });
        $('#edit_quote').click(function () {
            $(window).unbind('beforeunload');
            $('form.edit-qu-form').submit();
        });
    });
	
	
	
	//Start my script
	$(window).load(function() {
		$('#interest_rate_cash_2').change(function() { 
			var interest_rate_cash = $(this).val();
			var interest_rate = 0;
			if(interest_rate_cash.search('%')) {
				interest_rate_cash = interest_rate_cash.replace('%', '');
				interest_rate = (interest_rate_cash/100);
			}else {
				interest_rate = interest_rate_cash;
			}
			$('#interest_rate_cash').val(interest_rate);
			$('#interest_rate_cash').trigger('change');
		});	
		
		<?php if($inv->quote_status != 'applicant' && $inv->quote_status != 'draft') { ?>
			$('input:text').attr("readonly",true);
			$('input:checkbox').attr("readonly",true);
			$('input:text').attr("readonly",true);
			$('input:checkbox').attr("readonly",true);
			$('.select').attr("disabled",true);			
			$('.phone_disabled').attr("disabled",true);
			$('#st_inst_date').attr('readonly', false);
			$('.number_only').attr("disabled",true);			
			$('.form-control').attr("disabled",true);
			$('.form-group').attr("disabled",true);
			$('.check').attr("disabled",true);
			$('input:submit').css("display","none");
		<?php } ?>
		
		//Group Loans
		<?php if($app_num->app_num > 1) { ?>
			$('#category').attr("readonly",true);
			$('#sub_category').attr("readonly",true);
			$('#product_id').attr("readonly",true);
			$('#group_loans').attr("readonly",true);
			$('#customer_type').attr("readonly",true);
			$('#interest_rate_cash_2').attr("readonly",true);
			$('#term_cash').attr("readonly",true);
			$('#frequency_cash').attr("readonly",true);
			$('#rate_type_cash').attr("readonly",true);
			$('#currency').attr("readonly",true);
			$('.services').attr("readonly",true);
			$('#principle_frequency').attr("readonly",true);
			$('.ch_services').attr("disabled", true);
			
			$("#customer_type").trigger('change');
			$("#interest_rate_cash_2").trigger('change');
			$("#term_cash").trigger('change');
			$("#frequency_cash").trigger('change');
			$("#rate_type_cash").trigger('change');
		<?php } ?>
			
	});	
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-edit"></i><?= lang('quotation_details'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('enter_info'); ?></p>
				<div class="col-md-12">
							<ul id="dbTab" class="nav nav-tabs">
								<li class=""><a href="#applicants"><?= lang('applicants') ?></a></li>								
								<li class=""><a href="#financial_products"><?= lang('financial_products') ?></a></li>
								<li class="" id="join_lease_tap"><a href="#join_lease"><?= lang('join_lease') ?></a></li>
								<li class=""><a href="#employee"><?= lang('employee') ?></a></li>
								<li class=""><a href="#guarantors"><?= lang('guarantors') ?></a></li>
								<li class="" id="collateral_tap"><a href="#collateral"><?= lang('collateral') ?></a></li>
								<li class=""><a href="#documents"><?= lang('documents') ?></a></li>
								<!--<li class=""><a href="#fields_check"   class="field_check"><?= lang('fields_check') ?></a></li>-->
								
							</ul>
							<div class="tab-content">
								<?php
								$attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class' => 'edit-qu-form');
								echo form_open_multipart("quotes/edit/" . $id, $attrib)
								?>
								<div id="applicants" class="tab-pane fade in">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<p><?= lang('enter_info'); ?></p>
												<div class="row">
													<!---->
													<div class="col-lg-12">
														<div class="col-md-6">
														
															<?php if (isset($this->permission['reports-underwriting']) ?$this->permission['reports-underwriting'] : ('') || $this->Admin || $this->Owner || $GP['quotes-edit']){ ?>
																	<div class="form-group">
																		<?= lang("status", "qstatus"); ?>
																		<?php
																		$status_q = array('' => '', 'applicant' => lang('applicant'),'draft' => lang('draft'));
																		if(isset($sale->sale_status) && $sale->sale_status == 'approved'){
																			unset($status_q['applicant']);
																		}
																		echo form_dropdown('status', $status_q, ($inv->quote_status? $inv->quote_status : ''), 'id="qstatus" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("status") . '" class="form-control input-tip " style="width:100%;"');
																		?>
																	</div>
															<?php } ?>
															
															<div class="form-group">
																<?= lang("identify_type", "identify_type"); ?>
																<?php
																	$ident_all[(isset($_POST['identify_id']) ? $_POST['identify_id'] : '')] = (isset($_POST['identify_id']) ? $_POST['identify_id'] : '');
																	if(array($identify_type)) {
																		foreach($identify_type as $ident_){
																			$ident_all[$ident_->id] = $ident_->name;
																		}
																	}
																	echo form_dropdown('identify_id', $ident_all, $applicant->identify, 'class="form-control identify_type" id="identify_type" placeholder="' . lang("select_identify_to_load") . '" data-bv-notempty="true"');																		
																?>
																<input type="hidden" name="reference_no" id="quref" value="<?=(isset($_POST['reference_no']) ? $_POST['reference_no'] : $inv->reference_no);?>" />
															</div>
															<div class="form-group">																
																<label id="identify" for="cus_gov_id"> <?php echo $applicant->ident_name ?> </label>
																<input type="hidden" name="h_identify" id="h_identify" class="h_identify"  />
																<?php echo form_input('cus_gov_id', $applicant->gov_id, 'class="form-control" id="cus_gov_id" data-bv-notempty="true"'); ?>
															</div>
														</div>
														<div class="col-md-6" style="margin-top:15px;">
															<div class="col-md-0"></div>
															<div class="col-md-3">
																<div style=" width:115px; height:135px; background-color:#ccc;">
																	<!--<?php echo '<img src="' . base_url() . 'assets/uploads/documents/' . $qphoto->name .'"  style=" width:115px; height:135px;" id="inputimg"/> '?>-->
																	<?php echo '<img src="' . base_url() . 'assets/uploads/documents/' . ($qphoto->name?$qphoto->name:'male.png') .'"  style=" width:115px; height:135px;" id="inputimg"/> '?>
																</div>
															</div>
															<div class="col-md-4">
																<div style="margin-top:60px;">
																	
																</div>
																<div>
																	<label for="document"><?= lang("photo_applicant")?></label>
																	<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" value="<?= $qphoto->name ?>" name="applicant_photo" id="document">
																	
																</div>
															</div>
														</div>
													
													</div>
													<!---->
													<div class="col-lg-12">
														<div class="col-md-6">
															<!--<?php if ($Owner || $Admin || !$this->session->userdata('biller_id')) { ?>
																	<div class="form-group">
																		<?= lang("dealer", "qubiller"); ?>
																		<?php
																		$bl[""] = "";
																		foreach ($billers as $biller) {
																			$bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
																		}
																		echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $biller->id), 'id="qubiller" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("dealer") . '" required="required" class="form-control input-tip select" style="width:100%;"');
																		?>
																		<input type="hidden" name="reference_no" id="quref" value="<?=(isset($_POST['reference_no']) ? $_POST['reference_no'] : $inv->reference_no);?>" />
																	</div>
															<?php } else {
																$biller_input = array(
																	'type' => 'hidden',
																	'name' => 'biller',
																	'id' => 'qubiller',
																	'value' => $this->session->userdata('biller_id'),
																);

																echo form_input($biller_input);
															} ?>-->
															<!--<div class="form-group">
																<?= lang("civility", "cus_civility"); ?>
																<?php
																$cus_civility[""] = "";
																$cus_civility['male'] = "Mr.";
																$cus_civility['female'] = "Mrs.";
																echo form_dropdown('cus_civility', $cus_civility, isset($applicant->civility)?$applicant->civility:'', 'class="form-control select" id="cus_civility" placeholder="' . lang("select") . ' ' . lang("civility") . '" style="width:100%" data-bv-notempty="true"')
																?>
															</div>-->
															
															
															<!--<div class="form-group">
																<?= lang("identify_type", "identify_type"); ?>
																<?php
																	$ident_all[(isset($_POST['identify_id']) ? $_POST['identify_id'] : '')] = (isset($_POST['identify_id']) ? $_POST['identify_id'] : '');
																	if(array($identify_type)) {
																		foreach($identify_type as $ident_){
																			$ident_all[$ident_->id] = $ident_->name;
																		}
																	}
																	echo form_dropdown('identify_id', $ident_all, $applicant->identify, 'class="form-control identify_type" id="identify_type" placeholder="' . lang("select_identify_to_load") . '" data-bv-notempty="true"');																		
																?>
															</div>-->
															
															<div class="form-group">
																<?= lang("issue_by", "cus_issue_by"); ?>
																<?php echo form_input('cus_issue_by', $applicant->issue_by, 'class="form-control tip" id="cus_issue_by"'); ?>
															</div>
															<div class="form-group">
																<?= lang("family_name_(en)", "cus_family_name"); ?>
																<?php echo form_input('cus_family_name', $applicant->family_name, 'class="form-control tip" id="cus_family_name" data-bv-notempty="true"'); ?>
															</div>
															<div class="form-group">
																<?= lang("first_name_(en)", "cus_first_name"); ?>
															   <?php echo form_input('cus_first_name', $applicant->name, 'class="form-control" id="cus_first_name" required="required"'); ?>
															</div>
															<div class="form-group">
																<?= lang("gender", "cus_gender"); ?>
																<?php
																$cus_gender[""] = "";
																$cus_gender['male'] = "Male";
																$cus_gender['female'] = "Female";
																echo form_dropdown('cus_gender', $cus_gender, isset($applicant->gender)?$applicant->gender:'', 'class="form-control select" id="cus_gender" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%" data-bv-notempty="true"')
																?>
															</div>
															<div class="form-group person" style="display:none;">
																<?= lang("nick_name", "cus_nick_name"); ?>
																<?php echo form_input('cus_nick_name', $applicant->nickname, 'class="form-control tip" id="cus_nick_name"'); ?>
															</div>	
															<div class="form-group">
																<?= lang("date_of_birth", "cus_dob"); ?>
																<?php echo form_input('cus_dob', $this->erp->hrsd($applicant->date_of_birth), 'class="form-control date" id="cus_dob"'); ?>
															</div>
															<div class="form-group">
																<?= lang("age", "cus_age"); ?>
																<?php echo form_input('cus_age', $applicant->age == 0? '':$applicant->age, 'class="form-control" id="cus_age" style="pointer-events: none;"'); ?>
															</div>
															<div class="form-group" id="spname">
																<?= lang("spouse_full_name", "cus_sp_fname"); ?>
																<?php echo form_input('cus_sp_fname', $applicant->spouse_name, 'class="form-control" id="cus_sp_fname"'); ?>
															</div>
															<div class="form-group" id="spphone">
																<?= lang("spouse_mobile_phone", "cus_sp_phone"); ?>
																<input type="tel" name="cus_sp_phone" maxlength="11"  class="form-control number_only" id="cus_sp_phone" value="<?=$applicant->spouse_phone?>"/>
															</div>
															<div class="form-group" id="spchild">
																<?= lang("number_of_children", "cus_num_of_child"); ?>
																<?php echo form_input('cus_num_of_child', $applicant->num_of_child == 0? '':$applicant->num_of_child, 'class="form-control" id="cus_num_of_child"'); ?>
															</div>
															<div class="form-group">
																<?= lang("phone_1", "cus_phone_1"); ?>
																<input type="tel" name="cus_phone_1" class="form-control number_only" maxlength="11" id="cus_phone_1" value="<?=$applicant->phone1?>" required="required"/>
															</div>
															<div class="form-group">
																<?= lang("phone_2", "cus_phone_2"); ?>
																<input type="tel" name="cus_phone_2" class="form-control number_only" maxlength="11" id="cus_phone_2" value="<?=$applicant->phone2?>" />
															</div>
															<div class="form-group">
																<?= lang("by_c.o", "cus_by_co"); ?>
																
																<?php
																	$us[""] = "";
																	if(is_array(isset($users) ?$users  : (''))){
																	foreach ($users as $user) {
																		$us[$user->id] = $user->first_name . " " . $user->last_name;
																	}}
																	echo form_dropdown('cus_by_co', $us, $applicant->created_by, 'class="form-control" id="cus_by_co" placeholder="' . lang("Select_C.O_load") . '" data-bv-notempty="true"');																		
																?>
															</div>
														</div>
														
														<div class="col-md-6">
															<!--<?php if (isset($this->permission['reports-underwriting']) ?$this->permission['reports-underwriting'] : ('') || $this->Admin || $this->Owner){ ?>
																	<div class="form-group">
																		<?= lang("status", "qstatus"); ?>
																		<?php
																		$status_q = array('' => '', 'applicant' => lang('applicant'));
																		if(isset($sale->sale_status) && $sale->sale_status == 'approved'){
																			unset($status_q['applicant']);
																		}
																		echo form_dropdown('status', $status_q, ($inv->quote_status? $inv->quote_status : ''), 'id="qstatus" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("status") . '" class="form-control input-tip " style="width:100%;"');
																		?>
																	</div>
															<?php } ?>-->
															
															<!--<div class="form-group">																
																<label id="identify" for="cus_gov_id"> <?php echo $applicant->ident_name ?> </label>
																<input type="hidden" name="h_identify" id="h_identify" class="h_identify"  />
																<?php echo form_input('cus_gov_id', $applicant->gov_id, 'class="form-control" id="cus_gov_id" data-bv-notempty="true"'); ?>
															</div>-->
															<div class="form-group">
																<?= lang("issue_date", "cus_issue_date"); ?>
																<?php echo form_input('cus_issue_date', $this->erp->hrsd($applicant->issue_date), 'class="form-control date" id="cus_issue_date"'); ?>
															</div>
															<div class="form-group">
																<?= lang("family_name_(kh)", "cus_family_name_other"); ?>
																<?php echo form_input('cus_family_name_other', $applicant->family_name_other, 'class="form-control" id="cus_family_name_other" '); ?>
															</div>
															<div class="form-group">
																<?= lang("first_name_(kh)", "cus_first_name_other"); ?>
																<?php echo form_input('cus_first_name_other', $applicant->name_other, 'class="form-control" id="cus_first_name_other"'); ?>
															</div>
															<div class="form-group">
																<?= lang("father_name", "father_name"); ?>
																<?php echo form_input('father_name', $applicant->father_name, 'class="form-control" id="father_name"'); ?>
															</div>
															<div class="form-group">
																<?= lang("place_of_birth", "cus_pob"); ?>
																<?php echo form_input('cus_pob', $applicant->address, 'class="form-control" id="cus_pob"'); ?>
															</div>
															<div class="form-group">
																<?= lang("nationality", "cus_nationality"); ?>
																<?php
																$cus_nationality[""] = "";
																$cus_nationality['cam'] = "Cambodian";
																$cus_nationality['tha'] = "Thailand";
																$cus_nationality['vie'] = "Vietnamese";
																$cus_nationality['chi'] = "Chinese";
																$cus_nationality['bm'] = "Burma";
																echo form_dropdown('cus_nationality', $cus_nationality, isset($applicant->nationality)?$applicant->nationality:'', 'class="form-control select" id="cus_nationality" placeholder="' . lang("select") . ' ' . lang("nationality") . '" style="width:100%" data-bv-notempty="true"')
																?>
															</div>
															<div class="form-group" id="maritalstatus">
																<?= lang("marital_status", "cus_marital_status"); ?>
																<?php
																$cus_marital_status[""] = "";
																$cus_marital_status['married'] = "Married ";
																$cus_marital_status['divorced'] = "Divorced";
																$cus_marital_status['single'] = "Single";
																$cus_marital_status['widow/Widower'] = "Widow/Widower";
																$cus_marital_status['unknown'] = "unknown";
																
																echo form_dropdown('cus_marital_status', $cus_marital_status, $applicant->status, 'class="form-control select" id="cus_marital_status" placeholder="' . lang("select") . ' ' . lang("marital_status") . '" style="width:100%" data-bv-notempty="true"');
																?>
															</div>
															<!--<div class="form-group">
																<?= lang("spouse_family_name", "cus_sp_fam_name"); ?>
																<?php echo form_input('cus_sp_fam_name', $applicant->spouse_family_name, 'class="form-control" id="cus_sp_fam_name"'); ?>
															</div>-->
															<div class="form-group" id="sp_gender">
																<?= lang("spouse_gender", "sp_gender"); ?>
																<?php
																$sp_gender[""] = "";
																$sp_gender['male'] = "Male";
																$sp_gender['female'] = "Female";
																echo form_dropdown('sp_gender', $sp_gender, isset($applicant->spouse_gender)?$applicant->spouse_gender:'', 'class="form-control select" id="sp_gender" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%" ')
																?>
															</div>
															<div class="form-group" id="sp_status">
																<?= lang("spouse_status", "sp_status"); ?>
																<?php
																$sp_status[""] = "";
																$sp_status['husband'] = "Husband";
																$sp_status['wife'] = "Wife";
																echo form_dropdown('sp_status', $sp_status, isset($applicant->spouse_status)?$applicant->spouse_status:'', 'class="form-control select" id="sp_status" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%" ')
																?>
															</div>															
															<div class="form-group" id="sp_date">
																<?= lang("spouse_birthdate", "sp_date_of_birth"); ?>
																<?php echo form_input('sp_date_of_birth', $this->erp->hrsd($applicant->spouse_birthdate), 'class="form-control date" id="sp_date_of_birth"'); ?>
															</div>															
															<div class="form-group" id="whoseincome">
																<?= lang("whose_income", "cus_whose_income"); ?>
																<?php echo form_input('cus_whose_income', $applicant->whose_income, 'class="form-control" id="cus_whose_income"'); ?>
															</div>
															<div class="form-group" id="incomecombine">
																<?= lang("income_combination", "cus_inc_comb"); ?>
																<?php
																$inc_comb['0'] = "No";
																$inc_comb['1'] = "Yes";
																echo form_dropdown('cus_inc_comb', isset($inc_comb) ?$inc_comb  : (''), $applicant->income_combination, 'class="form-control select" id="cus_inc_comb" style="width:100%"');
																?>
															</div>															
															
															<div class="form-group" style="display:none;">
																<?= lang("black_list_customer", "cus_black_list"); ?>
																<?php
																$cus_black_list['0'] = "No";
																$cus_black_list['1'] = "Yes";
																echo form_dropdown('cus_black_list', $cus_black_list, $applicant->black_list, 'class="form-control select" id="cus_black_list" style="width:100%"');
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
																			foreach ($countries as $ct) {
																				$cus_country[$ct->code] = $ct->name;
																			}
																			echo form_dropdown('cus_country', $cus_country, $applicant->country, 'class="form-control select" id="cus_country" placeholder="' . lang("select") . ' ' . lang("country") . '" style="width:100%" data-bv-notempty="true"');
																			?>
																		</div>
																		<div class="form-group">
																			<?= lang("district", "cus_district"); ?>
																			<?php echo form_input('cus_district', ($applicant ? $applicant->district : ''), 'class="form-control" id="cus_district"  placeholder="' . lang("select_district") . '"');?>
																		</div>
																		<div class="form-group">
																			<?= lang("village", "cus_village"); ?>
																			<?php echo form_input('cus_village', ($applicant ? $applicant->village : ''), 'class="form-control" id="cus_village"  placeholder="' . lang("select_village") . '"');?>
																			
																		</div>
																		<div class="form-group">
																			<?= lang("current_address", "cus_house_no"); ?>
																			<?php echo form_input('cus_house_no', $applicant->house_no, 'class="form-control" id="cus_house_no"'); ?>
																		</div>
																		
																	</div>
																	<div class="col-md-6">
																		<div class="form-group">
																			<?= lang("province", "cus_province"); ?>
																			<?php echo form_input('cus_province', ($applicant ? $applicant->state : ''), 'class="form-control" id="cus_province"  placeholder="' . lang("select_province") . '"');?>
																		</div>
																		<div class="form-group">
																			<?= lang("communce", "cus_communce"); ?>
																			<?php echo form_input('cus_communce', ($applicant ? $applicant->sangkat : ''), 'class="form-control" id="cus_communce"  placeholder="' . lang("select_communce") . '"');?>
																		</div>
																		<!--<div class="form-group">
																			<?= lang("street", "cus_street"); ?>
																			<?php echo form_input('cus_street', $applicant->street, 'class="form-control" id="cus_street"'); ?>
																		</div>-->
																		<div class="form-group">
																			<?= lang("housing", "cus_housing"); ?>
																			<?php
																			$cus_housing[(isset($_POST['cus_housing']) ? $_POST['cus_housing'] : '')] = (isset($_POST['cus_housing']) ? $_POST['cus_housing'] : '');
																			$cus_housing["owner"] = "Owner";
																			$cus_housing["living_with_parent"] = "Living with parent";
																			$cus_housing["renting"] = "Renting";
																			echo form_dropdown('cus_housing', $cus_housing, $applicant->housing, 'class="form-control select" id="cus_housing" placeholder="' . lang("select") . ' ' . lang("housing") . '" style="width:100%" data-bv-notempty="true"');
																			?>
																		</div>
																		<div class="form-group">
																			<b style="padding-bottom:5px; display:block;"><?= lang("time_at_this_address"); ?></b>
																			<?php echo form_input('cus_years', $applicant->years, 'class="form-control" id="cus_years" placeholder="' . lang("years") . '" style="display:inline !important; width:35% !important;"'); ?>
																			<?= lang("years", "cus_years"); ?>
																			<?php echo form_input('cus_months', $applicant->months, 'class="form-control" id="cus_months" placeholder="' . lang("months") . '" style="display:inline !important; width:35% !important;"'); ?>
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
																	<div class="form-group">
																		<?php echo lang('category', 'category') ?>
																		<?php
																		$cat_all[''] = array();
																		if(is_array(isset($categories) ?$categories  : (''))){
																		foreach($categories as $cat_){
																			$cat_all[$cat_->id .'#'.$cat_->mfi .'#'.$cat_->group_loan] = $cat_->name;
																		}}
																		echo form_dropdown('category_id', (isset( $cat_all) ? $cat_all  : ''), (isset($product->category_id) ?$product->category_id.'#'.$inv->mfi  : ''), 'class="form-control category categ" id="category" placeholder="' . lang("select_category_to_load") . '" required');
																		?>
																		<input type="hidden" name="mfi" id="mfi" value="<?= (isset($inv->mfi)? $inv->mfi : ''); ?>" />
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group">
																		<?php echo lang('sub_category', 'sub_category') ?>
																		<?php
																		echo form_input('sub_category', isset($product->subcategory_id) ?$product->subcategory_id  : (''), 'class="form-control sub_category sub_categ" id="sub_category"  placeholder="' . lang("select_category_to_load") . '" required="required"');
																		?>
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group">
																		<?php echo lang('product', 'product_id') ?> 
																		<?php
																		$pr_all = array();
																		if(is_array(isset($products) ?$products  : (''))){
																		foreach($products as $pr_){
																			$pr_all[$pr_->id] = $pr_->name;
																		}}
																		echo form_input('product_id', isset($product->product_id) ?$product->product_id  : (''), 'class="form-control product_id produc" id="product_id"  placeholder="' . lang("select_product_to_load") . '" required="required"');
																		
																		?>
																	</div>
																</div>
																<div class="col-md-4" id="group" style="display:none;">
																	<div class="form-group all">
																		<?= lang("group_loans", "group_loans") ?>
																		<?= form_input('group_loans', (isset($product->group_name) ? $product->group_name : ''), ' class="form-control group_loans gro_loan" id="group_loans" ') ?>
																	</div>
																	<input type="hidden" name="groupid" id="groupid" value="<?= $product->grlid ?>" />
																</div>
																
																<div class="col-md-4 show_cash">
																	<div class="form-group">
																		<?php echo lang('currency', 'currency') ?>
																		<?php
																		$crr[''] = '';
																		if(array($currencies)) {
																			foreach($currencies as $currency){
																				$crr[$currency->code] = $currency->name;
																			}
																		}
																		echo form_dropdown('currency', $crr, (isset($product->currency_code) ? $product->currency_code  : ''), 'class="form-control currency" id="currency" placeholder="' . lang("select_currency") . '" required="required"');
																		?>
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group all">
																		<?= lang("amount", "total_amount") ?>
																		<?= form_input('price', (isset($product->unit_price) ? $this->erp->formatDecimal($product->unit_price) : ''), 'class="form-control" id="total_amount"  required="required"') ?>
																	</div>
																</div>
																<div class="col-md-4">
																	<div class="form-group all">
																		<?= lang("purpose", "purpose") ?>
																		<?= form_input('purpose', (isset($applicant->note) ? $applicant->note : ''), ' class="form-control" id="purpose" ') ?>
																	</div>
																</div>
																<div class="col-md-12 show_cash">
																	<div class="form-group all">
																		<?= lang('description', 'ldescription'); ?>
																		<textarea name="ldescription" id="ldescription"
																				  class="pa form-control kb-text ldescription"><?= (isset($product->description)? $product->description : ''); ?></textarea>
																	</div>
																</div>
																
																<div class="col-md-4 hide_cash">
																	<div class="form-group all">
																		<?= lang("color", "color") ?>
																		<?php
																		$vari[""] = "";
																		if($variants){
																			foreach ($variants as $variant) {
																				$vari[$variant->id] = $variant->name;
																			}
																		}
																		echo form_dropdown('color', $vari, (isset($product->color) ? $product->color : ''), 'id="color" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("color") . '"  class="form-control input-tip select" style="width:100%;"');
																		?>
																	</div>
																</div>
																
																<div class="col-md-4 hide_cash">
																	<div class="form-group all">
																		<?= lang("year", "year") ?>
																		<?php
																		$Y[""] = "";
																		$dur = date('Y') - 1990;
																		for($i=0;$i<=$dur;$i++) {
																			$yyyy = date('Y', strtotime('-'.$i.' years'));
																			$Y[$yyyy] = $yyyy;
																		}
																		echo form_dropdown('year', $Y, (isset($product->product_year) ? $product->product_year : ''), 'id="year" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("year") . '"  class="form-control input-tip select" style="width:100%;"');
																		?>
																	</div>
																</div>
																
																<div class="col-md-4 hide_cash">
																	<div class="form-group all">
																		<?= lang("engine", "engine") ?>
																		<?= form_input('engine', (isset($product->engine) ? $product->engine : ''), 'class="form-control" id="engine"'); ?>
																	</div>
																</div>
																
																<div class="col-md-4 hide_cash">
																	<div class="form-group all">
																		<?= lang("frame_number", "frame") ?>
																		<?= form_input('frame', (isset($product->frame) ? $product->frame : ''), 'class="form-control" id="frame"'); ?>
																	</div>
																</div>
																
																<div class="col-md-4 hide_cash">
																	<div class="form-group all">
																		<?= lang("power", "power") ?>
																		<?= form_input('power', (isset($product->power) ? $product->power: ''), 'class="form-control" id="power"'); ?>
																	</div>
																</div>

																<div class="col-md-4 hide_cash">
																	<div class="form-group all">
																		<?= lang("distance_mile", "distance") ?>
																		<?= form_input('distance', (isset($product->distance_mile) ? $product->distance_mile : ''), 'class="form-control" id="distance"'); ?>
																	</div>
																</div>
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
																	<?php echo form_input('saving_rate', (isset($qu_saving->saving_rate) ? $qu_saving->saving_rate * 100 .'%' : 0), 'class="form-control" id="saving_rate" '); ?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("saving_amount", "saving_amount"); ?>
																	<?php $saving_amount = $this->erp->convertCurrency($product->currency_code, $setting->default_currency, $qu_saving->saving_amount) ; ?>
																	<?php echo form_input('saving_amount', (isset($qu_saving->saving_amount) ? $this->erp->formatMoney($saving_amount) : 0), 'class="form-control number_only" id="saving_amount" style="pointer-events: none;" readonly'); ?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("saving_interest_rate_%", "saving_interest_rate"); ?>
																	<?php echo form_input('saving_interest_rate', (isset($qu_saving->saving_interest_rate) ? $qu_saving->saving_interest_rate * 100 .'%' : 0), 'class="form-control" id="saving_interest_rate" '); ?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("saving_type", "saving_type"); ?>
																	<?php
																	$saving_type[""] = "";
																	$saving_type[1] = "Normal";
																	echo form_dropdown('saving_type', $saving_type, (isset($qu_saving->saving_type) ? $qu_saving->saving_type : 1), 'id="saving_type" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("saving_type") . '"  class="form-control input-tip select" style="width:100%;"');
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
																		<?php echo form_input('service_'.$service->id, (($service->method == 'Percentage')? $this->erp->formatNumber($service->amount) * 100 .'%' : $this->erp->formatMoney($service->amount)), 'class="form-control input-tip services" id="services_'.$k.'" '); ?>																		
																		<input type="hidden" name="h_type_<?= $service->id ?>" class="h_type" id="h_type<?= $k ?>" value="<?= $service->method ?>" />
																		<input type="hidden" name="service_paid_<?= $service->id ?>" class="service_paid" id="service_paid<?= $k ?>" value="<?= $service->service_paid ?>" />																	
																		<input type="hidden" name="charge_by_<?= $service->id ?>" class="charge_by" id="charge_by<?= $k ?>" value="<?= $service->charge_by ?>" />
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group"  id="ch_services_">
																		<?php 
																			echo form_checkbox(['name' => 'ch_services[]', 'id' => $k, 'class' => 'ch_services', 'amount' => $service->amount, 'status' => $service->method, 'value' => $service->id, 'service_paid' => $service->service_paid, 'charge_by' => $service->charge_by, 'checked' => $service->checked, 'tax_rate' => $service->tax_rate]); 
																		?>
																	</div>
																</div>
																
																<div class="col-md-3">
																	<div class="form-group">
																		<?php 
																			$tax[''] = "";
																			if(array($tax_rate)) {
																				foreach($tax_rate as $rate){
																					$tax[$rate->id .'#'. $rate->rate] = $rate->name;	
																				}
																			}
																			echo form_dropdown('state_tax',$tax,$service->tax_id, 'class="form-control state_tax" id="state_tax_'.$service->id .'" data-item="'.$service->id .'" placeholder="' . $service->name . '"');																		
																		
																		?>
																		<input type="hidden" name="tax_rate_<?= $service->id ?>" id="tax_<?=$service->id?>" class="tax_rate" value="<?= $service->tax_rate ?>"/>
																		<input type="hidden" name="tax_rateid_<?= $service->id ?>" id="tax_id_<?= $service->id ?>" class="tax_id" value="<?= $service->tax_id ?>" />
																	</div>
																</div>
																
																<?php
																if($k == 0) {
																?>
																<!--<div class="col-md-3" style="display:none;">
																	<div class="form-group" >
																		<?php echo form_input('total_inst', 0, 'class="form-control input-tip" id="total_inst" readonly'); ?>
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
															?>
														</div>
													</div>
												</div>
												<?php } ?>
												<div class="col-sm-12 hide_cash">
													<div class="panel panel-primary">
														<div class="panel-heading"><?= lang('financial_product') ?></div>
														<div class="panel-body" style="padding: 5px;">
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("financial_product", "financial_product"); ?>
																	<?php
																	$fin_pro[""] = "";
																	foreach ($finacal_products as $financial_product) {
																		$fin_pro[$financial_product->id] = $financial_product->name;
																	}
																	echo form_dropdown('financial_product', $fin_pro, $inv->customer_group, 'id="financial_product" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("finacal_product") . '" class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("advance_percentage", "advance_percentage"); ?>
																	<?php
																	$percentage[""] = "";
																	foreach ($advance_percentages as $advance_percentage) {
																		$percentage[$advance_percentage->amount] = $advance_percentage->description;
																	}
																	echo form_dropdown('advance_percentage', $percentage, $inv->advance_percentage_payment, 'id="advance_percentage" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("advance_percentage") . '"  class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("advance_payment", "advance_payment"); ?>
																	<?php echo form_input('advance_payment', ($inv->advance_payment)?$this->erp->formatDecimal($inv->advance_payment):0, 'class="form-control input-tip" id="advance_payment" readonly'); ?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("lease_amount", "lease_amount"); ?>
																	<?php echo form_input('lease_amount', 0, 'class="form-control input-tip" id="lease_amount" readonly'); ?>
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
																	echo form_dropdown('frequency', $frequency, $inv->frequency, 'id="frequency" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("frequency") . '"  class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("rate_type", "rate_type"); ?>
																	<?php
																	$rate_type[""] = "";
																	//$rate_type["1"] = "Normal";
																	//$rate_type["2"] = "Fixed";
																	//$rate_type["3"] = "Normal_Fixed";
																	//$rate_type["4"] = "Seasons";
																	//$rate_type["5"] = "Custom";
																	echo form_dropdown('rate_type', $rate_type, $inv->rate_type, 'id="rate_type" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("rate_type") . '"  class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("interest_rate", "interest_rate"); ?>
																	<?php
																	$interest[""] = "";
																	foreach ($interest_rates as $interest_rate) {
																		$interest[$interest_rate->amount] = $interest_rate->description;
																	}
																	echo form_dropdown('interest_rate', $interest, $inv->interest_rate, 'id="interest_rate" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("interest_rate") . '"  class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("term", "term"); ?>
																	<?php
																	$term[""] = "";
																	foreach ($terms as $tm) {
																		$term[$tm->amount] = $tm->description;
																	}
																	echo form_dropdown('term', $term, $inv->term, 'id="term" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("term") . '" class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("installment_amount", "installment_amount"); ?>
																	<?php echo form_input('installment_amount', 0, 'class="form-control input-tip" id="installment_amount" readonly'); ?>
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
														<div class="panel-heading"><?= lang('financial_products') ?></div>
														<div class="panel-body" style="padding: 5px;">
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("customer_type", "customer_type"); ?>
																	<?php
																	$customer_type[(isset($_POST['customer_type']) ? $_POST['customer_type'] : '')] = (isset($_POST['customer_type']) ? $_POST['customer_type'] : '');
																	if(array($finacal_products)) {
																		foreach ($finacal_products as $cust_type) {
																			$customer_type[$cust_type->id] = $cust_type->name;
																		}
																	}
																	echo form_dropdown('customer_type', $customer_type, (isset($inv->customer_group) ? $inv->customer_group : ''), 'id="customer_type" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("finacal_product") . '"  class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
																<div class="form-group">
																	<?= lang("payment_frequency", "frequency_cash"); ?>
																	<?php
																	$frequency_cash[""] = "";
																	$frequency_cash[1] = "Daily";
																	$frequency_cash[7] = "Weekly";
																	$frequency_cash[14] = "Two Week";
																	$frequency_cash[30] = "Monthly";
																	//$frequency_cash[90] = "Quarterly";
																	//$frequency_cash[180] = "Haft Year";
																	$frequency_cash[360] = "Yearly";
																	echo form_dropdown('frequency_cash', $frequency_cash, (isset($inv->frequency) ? $inv->frequency : ''), 'id="frequency_cash" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("frequency") . '"  class="form-control input-tip select" style="width:100%;" required="required"');
																	?>
																</div>
																<div class="form-group">
																	<?= lang("interest_rate", "interest_rate_cash_2"); ?>
																	<?php
																	/*$interest[""] = "";
																	if(array($interest_rates)) {
																		foreach ($interest_rates as $interest_rate) {
																			$interest[$interest_rate->amount] = $interest_rate->description;
																		}
																	}
																	echo form_dropdown('interest_rate_cash', $interest, (isset($inv->interest_rate) ? $inv->interest_rate : ''), 'id="interest_rate_cash" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("interest_rate") . '" class="form-control input-tip select" style="width:100%;"');
																	*/
																	?>
																	<input type="hidden" name="interest_rate_cash" id="interest_rate_cash" class="interest_rate_cash"/>
																	<?php echo form_input('interest_rate_cash_2', (isset($inv->rate_text ) ? $inv->rate_text : ''), 'class="form-control" id="interest_rate_cash_2"  required="required"') ?>
																</div>
																
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("start_installment_date", "st_inst_date"); ?>
																	<?php echo form_input('st_inst_date', ($applicant ? $this->erp->hrsd($applicant->installment_date) : ''), 'class="form-control date" id="st_inst_date" required="required"'); ?>
																</div>
																
																<!--<div class="form-group" style="display:none;">
																	<?= lang("term", "term_cash"); ?>
																	<?php
																	$term[""] = "";
																	if(array($terms)) {
																		foreach ($terms as $tm) {
																			$term[$tm->amount] = $tm->description;
																		}
																	}
																	echo form_dropdown('term_cash', $term, (isset($inv->term) ? $inv->term : ''), 'id="term_cash" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("term") . '" class="form-control input-tip select" style="width:100%;" required="required"');
																	?>
																</div>-->
																
																<div class="form-group">
																	<?= lang("term", "term_cash"); ?>
																	<?php echo form_input('term_cash',(isset($inv->term) ? $inv->term / $inv->frequency : ''), 'class="form-control number_only" id="term_cash" required="required"');?>
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
																	$rate_type["5"] = "Seasons";
																	$rate_type["6"] = "Loan Amounts";
																	echo form_dropdown('rate_type_cash', $rate_type, (isset($inv->rate_type) ? $inv->rate_type : ''), 'id="rate_type_cash" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("rate_type") . '"  class="form-control input-tip select" style="width:100%;" required="required"');
																	?>
																</div>
															</div>
															
															<div class="col-lg-6" id="payment_time">
																<div class="form-group">
																	<?= lang("principle_frequency", "principle_frequency"); ?>
																	<?php echo form_input('principle_frequency',(isset($inv->principle_frequency) ? $inv->principle_frequency : 1), 'class="form-control number_only" id="principle_frequency" required="required"');?>
																</div>	
															</div>
															<div class="row">
																<div class="col-lg-12" >																	
																	<div class="col-lg-6" style="display:none;">
																		<div class="form-group">
																			<?= lang("total_interest_rate", "total_interest_rate"); ?>
																			<?php echo form_input('total_interest_rate', (isset($_POST['total_interest_rate']) ? $_POST['total_interest_rate'] : 0), 'class="form-control input-tip" id="total_interest_rate" readonly'); ?>
																		</div>
																	</div>																	
																	<div class="col-lg-6 btn_print_payment_schedule_cash" style="display:none; padding: 2.5% 0% 1% 1.3%;">
																		<input type="button" class="btn btn-primary" value="<?=lang('print_payment_schedule')?>" name="print_payment_schedule_cash" id="print_payment_schedule_cash" />
																	</div>
																</div>
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
																	echo form_dropdown('jl_identify_id',$ident_all, $join_lease->identify, 'class="form-control jl_identify_type" id="jl_identify_type" placeholder="' . lang("select_identify_to_load") . '" data-bv-notempty="true"');																					
																		
																?>
															</div>
															<div class="form-group">
																<?= lang("name", "jl_name"); ?>
																<?php echo form_input('jl_name', (isset($join_lease) ? $join_lease->name : ''), 'class="form-control" id="jl_name"'); ?>
															</div>
															<!--<div class="form-group">
																<?= lang("date_of_birth", "jl_dob"); ?>
																<?php echo form_input('jl_dob', (isset($join_lease) ? $this->erp->hrsd($join_lease->date_of_birth) : ''), 'class="form-control date" id="jl_dob" data-bv-notempty="true"'); ?>
															</div>	-->
															<div class="form-group">
																<?= lang("date_of_birth", "jl_dob"); ?>
																<?php echo form_input('jl_dob', $this->erp->hrsd($join_lease->date_of_birth) , 'class="form-control date" id="jl_dob" data-bv-notempty="true"'); ?>
															</div>	
															<div class="form-group">
																<?= lang("children_member", "jl_children_member"); ?>
																<?php echo form_input('jl_dependent_children', (isset($join_lease) ? $join_lease->num_of_child : ''), 'class="form-control" id="jl_dependent_children"'); ?>
															</div>
															<div class="form-group">
																<?= lang("family_member", "jl_family_member"); ?>
																<?php echo form_input('jl_family_member', (isset($join_lease)?$join_lease->family_member:''), 'class="form-control" id="jl_family_member" '); ?>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">																
																<label id="jl_identify" for="jl_gov_id"><?php echo $join_lease->ident_name ?></label>
																<input type="hidden" name="jl_identify" id="jl_identify" class="jl_identify"  />
																<?php echo form_input('jl_gov_id', (isset($join_lease) ? $join_lease->gov_id : ''), 'class="form-control" id="jl_gov_id" data-bv-notempty="true"'); ?>
															</div>
															<div class="form-group">
																<?= lang("gender", "jl_gender"); ?>
																<?php
																$jl_gender[(isset($_POST['jl_gender']) ? $_POST['jl_gender'] : '')] = (isset($_POST['jl_gender']) ? $_POST['jl_gender'] : '');
																$jl_gender['male'] = "Male";
																$jl_gender['female'] = "Female";
																echo form_dropdown('jl_gender', $jl_gender, isset($join_lease) ? $join_lease->gender : '' , 'class="form-control select" id="jl_gender" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%" data-bv-notempty="true"')
																?>
															</div>
															<div class="form-group">
																<?= lang("age", "jl_age"); ?>
																<?php echo form_input('jl_age', (isset($join_lease) ? $join_lease->age : ''), 'class="form-control" id="jl_age" data-bv-notempty="true"'); ?>
															</div>
															<div class="form-group">
																<?= lang("phone", "jl_phone_1"); ?>
																<input type="tel" name="jl_phone_1" class="form-control number_only" maxlength="11" id="jl_phone_1" value="<?php echo (isset($join_lease) ? $join_lease->phone1 : '') ?>"/>
															</div>
															<div class="form-group">
																<?= lang("status", "jl_status"); ?>
																<?php echo form_input('jl_status', (isset($join_lease) ? $join_lease->status : ''), 'class="form-control" id="jl_status"'); ?>
															</div>
														</div>
														<div class="col-sm-12">
															<div class="form-group">
																<?= lang("address", "jl_address"); ?>
																<?php echo form_textarea('jl_address', (isset($join_lease)?$join_lease->address:''), 'class="form-control" id="jl_address" style="margin-top: 10px; height: 130px;"'); ?>
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
														<div class="panel panel-primary">
															<div class="panel-heading"><?= lang('current_employment') ?></div>
															<div class="panel-body" style="padding: 5px;">
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("position", "position"); ?>
																		<?php echo form_input('position', $quote_employee? $quote_employee->position:'', 'class="form-control input-tip"  id="position"'); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("employment_status", "employment_status"); ?>
																		<?php
																			$emp_status = array('working' => 'Working','not_working' => 'Not Working');
																			echo form_dropdown('employment_status', $emp_status, $quote_employee? $quote_employee->emp_status:'', 'id="employment_status" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("employment_status") . '"  style="width:100%;" ');
																			?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("employment_industry", "employment_industry"); ?>
																		<?php
																			$emp_industrial = array('manufacturing' => 'Manufacturing');
																			echo form_dropdown('employment_industry', $emp_industrial, $quote_employee? $quote_employee->emp_industry:'', 'id="employment_industry" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("employment_industry") . '"  style="width:100%;" ');
																			?>
																	</div>
																</div>

																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("seniorities_level", "seniorities_level"); ?>
																		<?php
																			$seniorities_level = array('staff' => 'Staff');
																			echo form_dropdown('seniorities_level', $seniorities_level, $quote_employee? $quote_employee->senior_level:'', 'id="seniorities_level" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("seniorities_level") . '" style="width:100%;" ');
																			?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("work_place_name", "work_place_name"); ?>
																		<?php echo form_input('work_place_name', $quote_employee? $quote_employee->workplace_name:'', 'class="form-control input-tip"  id="work_place_name"'); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("work_phone", "work_phone"); ?>
																		<?php echo form_input('work_phone', $quote_employee? $quote_employee->work_phone:'', 'class="form-control input-tip number_only"  maxlength="11" id="work_phone"'); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<input type="checkbox" id="allow_call_to_work_place" class="form-control" name="allow_call_to_work_place" value="1" <?php echo set_checkbox('allow_call_to_work_place', '1', ($quote_employee && $quote_employee->allow_call_to_work_place==1)?TRUE:FALSE); ?>>
																		<?= lang("allow_call_to_work_place", "allow_call_to_work_place"); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<b style="padding-bottom:5px; display:block;"><?= lang("time_at_this_address"); ?></b>
																		<?php echo form_input('emp_years', $quote_employee? $quote_employee->years:'', 'class="form-control" id="emp_years" placeholder="' . lang("years") . '" style="display:inline !important; width:35% !important;"'); ?>
																		<?= lang("years", "emp_years"); ?>
																		<?php echo form_input('emp_months', $quote_employee? $quote_employee->months:'', 'class="form-control" id="emp_months" placeholder="' . lang("months") . '" style="display:inline !important; width:35% !important;"'); ?>
																		<?= lang("months", "emp_months"); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("basic_salary", "basic_salary"); ?>
																		<?php echo form_input('basic_salary', $quote_employee? $quote_employee->basic_salary:'', 'class="form-control input-tip" id="basic_salary"'); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("allowance_etc", "allowance_etc"); ?>
																		<?php echo form_input('allowance_etc', $quote_employee? $quote_employee->allowance_etc:'', 'class="form-control input-tip" id="allowance_etc"'); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("business_expense", "business_expense"); ?>
																		<?php echo form_input('business_expense', $quote_employee? $quote_employee->business_expense:'', 'class="form-control input-tip" id="business_expense" style="display:inline !important; width:80% !important;"'); ?>
																		<?= lang("month", "month"); ?>
																	</div>
																</div>
																
															</div>
														</div>
														
														
														<div class="panel panel-primary">
															<div class="panel-heading"><?= lang('current_employment_address') ?></div>
															<div class="panel-body" style="padding: 5px;">	
																<div class="form-group">
																	<?= lang("address", "emp_address"); ?>
																	<?php echo form_textarea('emp_address', ($quote_employee ? $quote_employee->address : ''), 'class="form-control" id="emp_address" style="margin-top: 10px; height: 100px;"'); ?>
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
																		<?php echo form_input('emp_province',  ($quote_employee ? $quote_employee->province : ''), 'class="form-control" id="emp_province"  placeholder="' . lang("select_province") . '"');?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("district", "emp_district"); ?>
																		<?php echo form_input('emp_district',  ($quote_employee ? $quote_employee->district : ''), 'class="form-control" id="emp_district"  placeholder="' . lang("select_province_to_load") . '"');?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("commune", "emp_communce"); ?>
																		<?php echo form_input('emp_communce', ($quote_employee ? $quote_employee->communce : ''), 'class="form-control" id="emp_communce"  placeholder="' . lang("select_district_to_load") . '"');?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("village", "emp_village"); ?>
																		<?php echo form_input('emp_village', ($quote_employee ? $quote_employee->village : ''), 'class="form-control" id="emp_village"  placeholder="' . lang("select_communce_to_load") . '"');?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("street", "emp_street")?>
																		<?php echo form_input('emp_street', ($quote_employee ? $quote_employee->street : ''), 'class="form-control input-tip" id="emp_street"'); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("house_no", "emp_house_no")?>
																		<?php echo form_input('emp_house_no', ($quote_employee ? $quote_employee->house_no : ''), 'class="form-control input-tip" id="emp_house_no"'); ?>
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
								<!--Sethy guarantors-->
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
																				echo form_dropdown('gr_identify_id', $ident_all, $guarantor->identify, 'class="form-control gr_identify_type" id="gr_identify_type" placeholder="' . lang("select_identify_to_load") . '" data-bv-notempty="true"');		
																																							
																			?>
																		</div>
																		<div class="form-group">
																			<?= lang("issue_by", "gr_issue_by"); ?>
																			<?php echo form_input('gr_issue_by', ($guarantor ? $guarantor->issue_by : ''), 'class="form-control" id="gr_issue_by" '); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("name", "name"); ?>
																			<?php echo form_input('gt_name', ($guarantor ? $guarantor->name : ''), 'class="form-control" id="gt_name" '); ?>
																		</div>
																		<!--<div class="form-group">
																			<?= lang("family_name", "family_name"); ?>
																			<?php echo form_input('family_name',($guarantor ? $guarantor->family_name : ''), 'class="form-control tip" id="family_name" '); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("first_name", "first_name"); ?>
																		   <?php echo form_input('first_name', ($guarantor ? $guarantor->name : ''), 'class="form-control" id="first_name"'); ?>
																		</div>-->
																		<div class="form-group">
																			<?= lang("date_of_birth", "dob"); ?>
																			<?php echo form_input('dob', ($guarantor ? $this->erp->hrsd($guarantor->date_of_birth) : ''), 'class="form-control date" id="dob"'); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("phone", "phone_1"); ?>
																			<input type="tel" name="phone_1" class="form-control number_only" maxlength="11" value="<?= ($guarantor ? $guarantor->phone1 : '') ?>" id="phone_1"/>
																		</div>
																		<div class="form-group">
																			<?= lang("job", "j_job_1"); ?>
																			<?php echo form_input('j_job_1', ($guarantor ? $guarantor->job : ''), 'class="form-control" id="j_job_1" '); ?>
																		</div>		
																	</div>																	
																	<div class="col-md-6">
																		<div class="form-group">
																			<label id="gr_identify" for="gr_gov_id"><?php echo $guarantor->ident_name ?></label>
																			<input type="hidden" name="gr_identify" id="gr_identify" class="gr_identify"  />
																			<?php echo form_input('gov_id', ($guarantor ? $guarantor->gov_id : ''), 'class="form-control" id="gov_id" data-bv-notempty="true"'); ?>
																		</div>
																		
																		<!--<div class="form-group">
																			<?= lang("family_name_other", "family_name_other"); ?>
																			<?php echo form_input('family_name_other', ($guarantor ? $guarantor->family_name_other : ''), 'class="form-control" id="family_name_other" '); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("first_name_other", "first_name_other"); ?>
																			<?php echo form_input('first_name_other', ($guarantor ? $guarantor->name_other : ''), 'class="form-control" id="first_name_other" '); ?>
																		</div>-->
																		<div class="form-group">
																			<?= lang("issue_date", "gr_issue_date"); ?>
																			<?php echo form_input('gr_issue_date', ($guarantor ? $this->erp->hrsd($guarantor->issue_date) : ''), 'class="form-control date" id="gr_issue_date"'); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("gender", "gender"); ?>
																			<?php
																			$gender[ (isset($_POST['gender']) ? $_POST['gender'] : '')] =  (isset($_POST['gender']) ? $_POST['gender'] : '');
																			$gender['male'] = "Male";
																			$gender['female'] = "Female";
																			echo form_dropdown('gender', $gender, ($guarantor ? $guarantor->gender : ''), 'class="form-control select" id="gender" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%" ')
																			?>
																		</div>
																		<div class="form-group">
																			<?= lang("age", "age"); ?>
																			<?php echo form_input('age', ($guarantor ? $guarantor->age : ''), 'class="form-control" id="age" style="pointer-events:none;"');?>
																		</div>	
																		<div class="form-group">
																			<?= lang("status", "g_status"); ?>
																			<?php echo form_input('g_status', (isset($guarantor) ? $guarantor->status : ''), 'class="form-control" id="g_status"'); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("address", "gl_1_address"); ?>
																			<?php echo form_textarea('gl_1_address', ($guarantor ? $guarantor->address : ''), 'class="form-control" id="gl_1_address" style="margin-top: 10px; height: 100px;"'); ?>
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
																				echo form_dropdown('gr_identify_id_2',$ident_all, $join_lease->identify, 'class="form-control gr_identify_type_2" id="gr_identify_type_2" placeholder="' . lang("select_identify_to_load") . '" data-bv-notempty="true"');
																					
																			?>
																		</div>
																		<!--<div class="form-group">
																			<?= lang("family_name", "family_name"); ?>
																			<?php echo form_input('family_name2',($join_guarantor ? $join_guarantor->family_name : ''), 'class="form-control tip" id="family_name2" '); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("first_name", "first_name2"); ?>
																		   <?php echo form_input('first_name2', ($join_guarantor ? $join_guarantor->name : ''), 'class="form-control" id="first_name2"'); ?>
																		</div>-->
																		<div class="form-group">
																			<?= lang("issue_by", "gr2_issue_by"); ?>
																			<?php echo form_input('gr2_issue_by', ($join_guarantor ? $join_guarantor->issue_by : ''), 'class="form-control" id="gr2_issue_by" '); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("name", "family_name_other2"); ?>
																			<?php echo form_input('gt_name2', ($join_guarantor ? $join_guarantor->name : ''), 'class="form-control" id="gt_name2" '); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("date_of_birth", "dob"); ?>
																			<?php echo form_input('dob2', ($join_guarantor ? $this->erp->hrsd($join_guarantor->date_of_birth) : ''), 'class="form-control date" id="dob2"'); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("phone", "phone_2"); ?>
																			<input type="tel" name="phone_2" class="form-control number_only" maxlength="11" value="<?= ($join_guarantor ? $join_guarantor->phone1 : '') ?>" id="phone_2"/>
																		</div>
																		<div class="form-group">
																			<?= lang("job", "j_job_2"); ?>
																			<?php echo form_input('j_job_2', ($join_guarantor ? $join_guarantor->job : ''), 'class="form-control" id="j_job_2" '); ?>
																		</div>		
																	</div>																	
																	<div class="col-md-6">
																		<div class="form-group">
																			<label id="gr_identify_2" for="gov_id2"><?php echo $join_guarantor->ident_name ?></label>
																			<input type="hidden" name="gr_identify_2" id="gr_identify_2" class="gr_identify_2"  />
																			<?php echo form_input('gov_id2', ($join_guarantor ? $join_guarantor->gov_id : ''), 'class="form-control" id="gov_id2" '); ?>
																		</div>
																		
																		<!--<div class="form-group">
																			<?= lang("family_name_other", "family_name_other2"); ?>
																			<?php echo form_input('family_name_other2', ($join_guarantor ? $join_guarantor->family_name_other : ''), 'class="form-control" id="family_name_other2" '); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("first_name_other", "first_name_other"); ?>
																			<?php echo form_input('first_name_other2', ($join_guarantor ? $join_guarantor->name_other : ''), 'class="form-control" id="first_name_other2" '); ?>
																		</div>-->
																		<div class="form-group">
																			<?= lang("issue_date", "gr2_issue_date"); ?>
																			<?php echo form_input('gr2_issue_date', ($join_guarantor ? $this->erp->hrsd($join_guarantor->issue_date) : ''), 'class="form-control date" id="gr2_issue_date"'); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("gender", "gender2"); ?>
																			<?php
																			$gender[ (isset($_POST['gender2']) ? $_POST['gender2'] : '')] =  (isset($_POST['gender2']) ? $_POST['gender2'] : '');
																			$gender['male'] = "Male";
																			$gender['female'] = "Female";
																			echo form_dropdown('gender2', $gender, ($join_guarantor ? $join_guarantor->gender : ''), 'class="form-control select" id="gender2" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%" ')
																			?>
																		</div>
																		<div class="form-group">
																			<?= lang("age", "age2"); ?>
																			<?php echo form_input('age2', ($join_guarantor ? $join_guarantor->age : ''), 'class="form-control" id="age2" style="pointer-events:none;"');?>
																		</div>
																		<div class="form-group">
																			<?= lang("status", "g_status_2"); ?>
																			<?php echo form_input('g_status_2', ($join_guarantor ? $join_guarantor->status : ''), 'class="form-control" id="g_status_2"'); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("address", "gl_2_address"); ?>
																			<?php echo form_textarea('gl_2_address', ($join_guarantor ? $join_guarantor->address : ''), 'class="form-control" id="gl_2_address" style="margin-top: 10px; height: 100px;"'); ?>
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
									<?php
											$dc_current_address = '#';
											$dc_current_address_name = '';
											$dc_family_book = '#';
											$dc_family_book_name = '';
											$dc_gov_id = '#';
											$dc_gov_id_name = '';
											$dc_house_photo = '#';
											$dc_house_photo_name = '';
											$dc_store_photo = '#';
											$dc_store_photo_name = '';
											$dc_employment_certificate = '#';
											$dc_employment_certificate_name = '';	
											$dc_applicant_photo = '#';
											$dc_applicant_photo_name = '';
											$dc_spouse_photo = '#';
											$dc_spouse_photo_name = '';
											$dc_guarantors_photo = '#';
											$dc_guarantors_photo_name = '';
											$dc_birth_registration_letter = '#';
											$dc_birth_registration_letter_name = '';
											$dc_passport = '#';
											$dc_passport_name = '';
											$dc_marriage_certificate = '#';
											$dc_marriage_certificate_name = '';
											$dc_driver_license = '#';
											$dc_driver_license_name = '';
											
											$cl_upload_picture='#';
											$cl_upload_picture_name='';
											
											
											$dc_working_contract = '#';
											$dc_working_contract_name = '';
											
											$dc_invoice_salary = '#';
											$dc_invoice_salary_name = '';
											
											$dc_business_certificate = '#';
											$dc_business_certificate_name = '';
											
											$dc_profit_for_the_last_3_month = '#';
											$dc_profit_for_the_last_3_month_name = '';
											
											
											$dc_other_dc = '#';
											$dc_other_dc_name = '';
											$base_dc_path = base_url() . 'assets/uploads/documents/';
											if($documents) {
												foreach($documents as $dc){
													if($dc->type == 'current_address'){
														$dc_current_address = $base_dc_path . $dc->name;
														$dc_current_address_name = $dc->name;
													}
													if($dc->type == 'family_book'){
														$dc_family_book = $base_dc_path . $dc->name;
														$dc_family_book_name = $dc->name;
													}
													
													if($dc->type == 'ganervment_id'){
														$dc_gov_id = $base_dc_path . $dc->name;
														$dc_gov_id_name = $dc->name;
													}
													if($dc->type == 'house_photo'){
														$dc_house_photo = $base_dc_path . $dc->name;
														$dc_house_photo_name = $dc->name;
													}
													if($dc->type == 'store_photo'){
														$dc_store_photo = $base_dc_path . $dc->name;
														$dc_store_photo_name = $dc->name;
													}
													if($dc->type == 'employment_certificate'){
														$dc_employment_certificate = $base_dc_path . $dc->name;
														$dc_employment_certificate_name = $dc->name;
													}
													
													if($dc->type == 'applicant_photo'){
														$dc_applicant_photo = $base_dc_path . $dc->name;
														$dc_applicant_photo_name = $dc->name;
													}
													if($dc->type == 'spouse_photo'){
														$dc_spouse_photo = $base_dc_path . $dc->name;
														$dc_spouse_photo_name = $dc->name;
													}
													if($dc->type == 'guarantors_photo'){
														$dc_guarantors_photo = $base_dc_path . $dc->name;
														$dc_guarantors_photo_name = $dc->name;
													}
													if($dc->type == 'birth_registration_letter'){
														$dc_birth_registration_letter = $base_dc_path . $dc->name;
														$dc_birth_registration_letter_name = $dc->name;
													}
													if($dc->type == 'passport'){
														$dc_passport = $base_dc_path . $dc->name;
														$dc_passport_name = $dc->name;
													}
													if($dc->type == 'marriage_certificate'){
														$dc_marriage_certificate = $base_dc_path . $dc->name;
														$dc_marriage_certificate_name = $dc->name;
													}
													if($dc->type == 'driver_license'){
														$dc_driver_license = $base_dc_path . $dc->name;
														$dc_driver_license_name = $dc->name;
													}
													if($dc->type == 'working_contract'){
														$dc_working_contract = $base_dc_path . $dc->name;
														$dc_working_contract_name = $dc->name;
													}
													if($dc->type == 'invoice_salary'){
														$dc_invoice_salary = $base_dc_path . $dc->name;
														$dc_invoice_salary_name = $dc->name;
													}
													if($dc->type == 'business_certificate'){
														$dc_business_certificate = $base_dc_path . $dc->name;
														$dc_business_certificate_name = $dc->name;
													}
													if($dc->type == 'profit_for_the_last_3_month'){
														$dc_profit_for_the_last_3_month = $base_dc_path . $dc->name;
														$dc_profit_for_the_last_3_month_name = $dc->name;
													}
													if($dc->type == 'other_document'){
														$dc_other_dc = $base_dc_path . $dc->name;
														$dc_other_dc_name = $dc->name;
													}
													if($dc->type == 'upload_picture'){
														$cl_upload_picture = $base_dc_path . $dc->name;
														$cl_upload_picture_name = $dc->name;
													}
												}
											}
											?>
											
									<div id="collateral" style="display: none;" class="tab-pane fade">										
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<?php foreach($collaterals as $collateral) {	
														echo '<input type="hidden" name="cl_id[]" class="cl_id" id="cl_id_'. $collateral->id .'" value="'. $collateral->id .'" />';
														if($collateral->cl_type == 1) {
													?>
															<div class="row">
																<div class="col-lg-12">
																	<div class="panel panel-primary">
																		<div class="panel-heading"><?= lang('land_collateral') ?></div>	
																			<div class="col-md-6">													
																				<div class="form-group">
																					<?= lang("code", "cl_code"); ?>
																					<?php echo form_input('cl_code[]', ($collateral? $collateral->code : ''), 'class="form-control" id="cl_code" required="required"  readonly="readonly');?>
																				</div>																				
																				<div class="form-group">
																					<?= lang("type", "cl_land_type"); ?>
																					<?php echo form_input('type[]', (isset($collateral) ? $collateral->type : ''), 'class="form-control" id="cl_land_type"');?>
																				</div>	
																				<div class="form-group">
																					<?= lang("adjacent_north", "cl_north"); ?>
																					<?php echo form_input('cl_north[]', (isset($collateral)?$collateral->adj_north:''), 'class="form-control" id="cl_north"');?>
																				</div>																					
																				<div class="form-group">
																					<?= lang("adjacent_south", "cl_south"); ?>
																					<?php echo form_input('cl_south[]', (isset($collateral)?$collateral->adj_south:''), 'class="form-control" id="cl_south"');?>
																				</div>
																				<div class="form-group">
																					<?= lang("owner_name", "land_owner_name"); ?>
																					<?php echo form_input('owner_name[]', (isset($collateral)?$collateral->owner_name:''), 'class="form-control" id="land_owner_name"');?>
																				</div>
																				<div class="form-group">
																					<?= lang("address", "cl_land_addres"); ?>
																					<?php echo form_textarea('address[]', (isset($collateral)?$collateral->address:''), 'class="form-control" id="cl_land_addres" style="margin-top: 10px; height: 100px;"'); ?>
																				</div>
																			</div>
																			<div class="col-md-6">
																			
																				<div class="form-group">
																					<?= lang("type", "cl_type"); ?>
																					<?php
																					$cl_type[''] = '';
																					if($collateral_type) {
																						foreach($collateral_type as $c_type){
																							$cl_type[$c_type->id] = $c_type->type;
																						}
																					}
																					echo form_dropdown('cl_type[]', $cl_type, ($collateral? $collateral->cl_type : ''), 'class="form-control select" id="cl_type" placeholder="' . lang("select") . ' ' . lang("type") . '" style="width:100%" ');
																					?>
																				</div>														
																				<div class="form-group">
																					<?= lang("size", "cl_land_size"); ?>
																					<?php echo form_input('size[]', (isset($collateral) ? $collateral->size : ''), 'class="form-control" id="cl_land_size"');?>
																				</div>
																				<div class="form-group">
																					<?= lang("adjacent_east", "cl_east"); ?>
																					<?php echo form_input('cl_east[]', (isset($collateral)?$collateral->adj_east:''), 'class="form-control" id="cl_east"');?>
																				</div>
																				<div class="form-group">
																					<?= lang("adjacent_west", "cl_west"); ?>
																					<?php echo form_input('cl_west[]', (isset($collateral)?$collateral->adj_west:''), 'class="form-control" id="cl_west"');?>
																				</div>
																				<div class="form-group">
																					<?= lang("title_number", "cl_card_number"); ?>
																					<?php echo form_input('cl_card_number[]', $collateral->card_no, 'class="form-control" id="cl_card_number"');?>
																				</div>
																				<div class="form-group">
																					<?= lang("issue_date", "land_issue_date"); ?>
																					<?php echo form_input('issue_date[]', $collateral->issue_date, 'class="form-control date" id="land_issue_date"');?>
																				</div>
																				
																				<input type="hidden" name="cl_roof[]" />
																				<input type="hidden" name="cl_wall[]" />																				
																				<input type="hidden" name="vcl_power[]" />
																				<input type="hidden" name="vcl_engine_no[]" />
																				<input type="hidden" name="vcl_plaque_no[]" />
																				<input type="hidden" name="vcl_color[]" />
																				<input type="hidden" name="vcl_brand[]" />
																				<input type="hidden" name="vcl_frame_no[]" />
																				<!--<input type="hidden" name="home_owner_name[]" />
																				<input type="hidden" name="home_issue_date[]" />
																				<input type="hidden" name="land_owner_name[]" />
																				<input type="hidden" name="vcl_issue_date[]" />-->
																				
																			</div>																
																	</div>
																</div>
															</div>
													<?php 
														}else if($collateral->cl_type == 2) {
													?>
															<!---->
															<div class="row">
																<div class="col-lg-12">
																	<div class="panel panel-primary">
																		<div class="panel-heading"><?= lang('home_collateral') ?></div>	
																			<div class="col-md-6">													
																				<div class="form-group">
																					<?= lang("code", "cl_code"); ?>
																					<?php echo form_input('cl_code[]', ($collateral? $collateral->code : ''), 'class="form-control" id="cl_code" required="required"  readonly="readonly');?>
																				</div>
																				<div class="form-group">
																					<?= lang("type", "cl_home_type"); ?>
																					<?php echo form_input('type[]', (isset($collateral)?$collateral->type:''), 'class="form-control" id="cl_home_type"');?>
																				</div>	
																				<div class="form-group">
																					<?= lang("roof", "cl_roof"); ?>
																					<?php echo form_input('cl_roof[]', (isset($collateral)?$collateral->roof:''), 'class="form-control" id="cl_roof"');?>
																				</div>
																				<div class="form-group">
																					<?= lang("address", "cl_home_address"); ?>
																					<?php echo form_textarea('address[]', (isset($collateral) ? $collateral->address:''), 'class="form-control" id="cl_home_address" style="margin-top: 10px; height: 100px;"'); ?>
																				</div>	
																																					
																			</div>
																			<div class="col-md-6">
																			
																				<div class="form-group">
																					<?= lang("type", "cl_type"); ?>
																					<?php
																					$cl_type[''] = '';
																					if($collateral_type) {
																						foreach($collateral_type as $c_type){
																							$cl_type[$c_type->id] = $c_type->type;
																						}
																					}
																					echo form_dropdown('cl_type[]', $cl_type, ($collateral? $collateral->cl_type : ''), 'class="form-control select" id="cl_type" placeholder="' . lang("select") . ' ' . lang("type") . '" style="width:100%" ');
																					?>
																				</div>	
																				
																				<div class="form-group">
																					<?= lang("size", "cl_land_size"); ?>
																					<?php echo form_input('size[]', (isset($collateral)?$collateral->size:''), 'class="form-control" id="cl_home_size"');?>
																				</div>
																				<div class="form-group">
																					<?= lang("wall", "cl_wall"); ?>
																					<?php echo form_input('cl_wall[]', (isset($collateral)?$collateral->wall:''), 'class="form-control" id="cl_wall"');?>
																				</div>
																				<div class="form-group">
																					<?= lang("owner_name", "home_owner_name"); ?>
																					<?php echo form_input('owner_name[]', (isset($collateral)?$collateral->owner_name:''), 'class="form-control" id="home_owner_name"');?>
																				</div>
																				<div class="form-group">
																					<?= lang("issue_date", "home_issue_date"); ?>
																					<?php echo form_input('issue_date[]', (isset($collateral)?$collateral->issue_date:''), 'class="form-control date" id="home_issue_date"');?>
																				</div>
																					
																				<input type="hidden" name="cl_north[]" />
																				<input type="hidden" name="cl_south[]" />
																				<input type="hidden" name="cl_east[]" />
																				<input type="hidden" name="cl_west[]" />
																				<input type="hidden" name="cl_card_number[]" />
																				<input type="hidden" name="vcl_power[]" />
																				<input type="hidden" name="vcl_engine_no[]" />
																				<input type="hidden" name="vcl_plaque_no[]" />
																				<input type="hidden" name="vcl_color[]" />
																				<input type="hidden" name="vcl_brand[]" />
																				<input type="hidden" name="vcl_frame_no[]" />
																				<!--<input type="hidden" name="home_owner_name[]" />
																				<input type="hidden" name="home_issue_date[]" />
																				<input type="hidden" name="land_owner_name[]" />
																				<input type="hidden" name="vcl_issue_date[]" />-->
																				
																			</div>																
																	</div>
																</div>					
															</div>
													<?php
														}else {
													?>
															<!---->
															<div class="row">
																<div class="col-lg-12">
																	<div class="panel panel-primary">
																		<div class="panel-heading"><?= lang('vehicles_collateral') ?></div>	
																			<div class="col-md-6">													
																				<div class="form-group">
																					<?= lang("code", "cl_code"); ?>
																					<?php echo form_input('cl_code[]', ($collateral? $collateral->code : ''), 'class="form-control" id="cl_code" required="required"  readonly="readonly');?>
																				</div>																				
																				<div class="form-group">
																					<?= lang("type", "vcl_vehicles_type"); ?>
																					<?php echo form_input('type[]', (isset($collateral)?$collateral->type:''), 'class="form-control" id="vcl_vehicles_type"');?>
																				</div>
																				<div class="form-group">
																					<?= lang("power", "vcl_power"); ?>
																					<?php echo form_input('vcl_power[]', (isset($collateral)?$collateral->power:''), 'class="form-control" id="vcl_power"');?>
																				</div>
																				<div class="form-group">
																					<?= lang("engine_number", "vcl_engine_no"); ?>
																					<?php echo form_input('vcl_engine_no[]', (isset($collateral)?$collateral->engine_no:''), 'class="form-control" id="vcl_engine_no"');?>
																				</div>																				
																				<div class="form-group">
																					<?= lang("owner_name", "vcl_owner_name"); ?>
																					<?php echo form_input('owner_name[]', (isset($collateral)?$collateral->owner_name:''), 'class="form-control" id="vcl_owner_name"');?>
																				</div>
																				<div class="form-group">
																					<?= lang("issue_date", "vcl_issue_date"); ?>
																					<?php echo form_input('issue_date[]', (isset($collateral)?$collateral->issue_date:''), 'class="form-control date" id="vcl_issue_date"');?>
																				</div>
																				
																			</div>
																			<div class="col-md-6">																			
																				<div class="form-group">
																					<?= lang("type", "cl_type"); ?>
																					<?php
																					$cl_type[''] = '';
																					if($collateral_type) {
																						foreach($collateral_type as $c_type){
																							$cl_type[$c_type->id] = $c_type->type;
																						}
																					}
																					echo form_dropdown('cl_type[]', $cl_type, ($collateral? $collateral->cl_type : ''), 'class="form-control select" id="cl_type" placeholder="' . lang("select") . ' ' . lang("type") . '" style="width:100%" ');
																					?>
																				</div>														
																				<div class="form-group">
																					<?= lang("color", "vcl_color"); ?>
																					<?php echo form_input('vcl_color[]', (isset($collateral)?$collateral->color:''), 'class="form-control" id="vcl_color"');?>
																				</div>																
																				<div class="form-group">
																					<?= lang("brand", "vcl_brand"); ?>
																					<?php echo form_input('vcl_brand[]', (isset($collateral)?$collateral->brand:''), 'class="form-control" id="vcl_brand"');?>
																				</div>
																				<div class="form-group">
																					<?= lang("frame_number", "vcl_frame_no"); ?>
																					<?php echo form_input('vcl_frame_no[]', (isset($collateral)?$collateral->frame_no:''), 'class="form-control" id="vcl_frame_no"');?>																					
																				</div>	
																				<div class="form-group">
																					<?= lang("plaque_number", "vcl_plaque_no"); ?>
																					<?php echo form_input('vcl_plaque_no[]', (isset($collateral)?$collateral->plaque_no:''), 'class="form-control" id="vcl_plaque_no"');?>
																				</div>
																				<input type="hidden" name="address[]" />
																				<input type="hidden" name="cl_roof[]" />
																				<input type="hidden" name="cl_wall[]" />
																				<input type="hidden" name="size[]" />
																				<input type="hidden" name="cl_north[]" />
																				<input type="hidden" name="cl_south[]" />
																				<input type="hidden" name="cl_east[]" />
																				<input type="hidden" name="cl_west[]" />
																				<input type="hidden" name="cl_card_number[]" />
																				<!--<input type="hidden" name="home_owner_name[]" />
																				<input type="hidden" name="home_issue_date[]" />
																				<input type="hidden" name="land_owner_name[]" />
																				<input type="hidden" name="vcl_issue_date[]" />-->
																			</div>
																	</div>
																</div>
															</div>
													<?php 
														}
													}
													?>
													<!---->
												</div>													
											</div>
										</div>
									</div>										
									<!---->
									

									<div id="documents" style="display: none;" class="tab-pane fade">
								        <div class="modal-body">
								            <p><?=lang("information_below") ?></p>
											
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("current_address")?><?php echo ($dc_current_address_name)? '(' . count($dc_current_address_name) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="current_address" id="document" value="<?php echo $dc_current_address_name ?>">
													</div>
												</div>
												<?php if($dc_current_address_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_current_address ?>" target="_blank" class="btn btn-primary"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?= lang("family_book") ?><?php echo ($dc_family_book_name)? '(' . count($dc_family_book_name) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="family_book" id="document">
													</div>
												</div>
												<?php if($dc_family_book_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_family_book ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>											
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("government_id")?><?php echo ($dc_gov_id_name)? '(' . count($dc_gov_id_name) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="ganervment_id" id="document">
													</div>
												</div>
												<?php if($dc_gov_id_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_gov_id ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>											
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("house_photo")?><?php echo ($dc_house_photo_name)? '(' . count($dc_house_photo_name) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="house_photo" id="document">
													</div>
												</div>
												<?php if($dc_house_photo_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_house_photo ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>											
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("store_photo")?> <?php echo ($dc_store_photo_name)? '(' . count($dc_store_photo_name) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="store_photo" id="document">
													</div>
												</div>
												<?php if($dc_store_photo_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_store_photo ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>											
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("employment_certificate")?> <?php echo ($dc_employment_certificate_name)? '(' . count($dc_employment_certificate_name) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="employment_certificate" id="document">
													</div>
												</div>
												<?php if($dc_employment_certificate_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_employment_certificate ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("applicant_photo")?><?php echo ($dc_applicant_photo_name)? '(' . count($dc_applicant_photo_name) . ' file)':'' ?></label>
														<input type="" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="applicant_photo" id="document" style="pointer-events:none;">
													</div>
												</div>
												<?php if($dc_applicant_photo_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_applicant_photo ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("spouse_photo")?> <?php echo ($dc_spouse_photo_name)? '(' . count($dc_spouse_photo_name) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="spouse_photo" id="document">
													</div>
												</div>
												<?php if($dc_spouse_photo_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_spouse_photo ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("guarantors_photo")?> <?php echo ($dc_guarantors_photo_name)? '(' . count($dc_guarantors_photo_name) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="guarantors_photo" id="document">
													</div>
												</div>
												<?php if($dc_guarantors_photo_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_guarantors_photo ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("birth_registeration_letter")?> <?php echo ($dc_birth_registration_letter_name)? '(' . count($dc_birth_registration_letter_name) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="birth_registration_letter" id="document">
													</div>
												</div>
												<?php if($dc_birth_registration_letter_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_birth_registration_letter ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("passport")?> <?php echo ($dc_passport_name)? '(' . count($dc_passport_name) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="passport" id="document">
													</div>
												</div>
												<?php if($dc_passport_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_passport ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("marriage_certificate")?> <?php echo ($dc_marriage_certificate_name)? '(' . count($dc_marriage_certificate_name) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="marriage_certificate" id="document">
													</div>
												</div>
												<?php if($dc_marriage_certificate_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_marriage_certificate ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("driver_license")?> <?php echo ($dc_driver_license_name)? '(' . count($dc_driver_license_name) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="driver_license" id="document">
													</div>
												</div>
												<?php if($dc_driver_license_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_driver_license ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("working_contract")?> <?php echo ($dc_working_contract_name)? '(' . count($dc_working_contract_name) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="working_contract" id="document">
													</div>
												</div>
												<?php if($dc_working_contract_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_working_contract ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("invoice_salary")?> <?php echo ($dc_invoice_salary)? '(' . count($dc_invoice_salary) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="invoice_salary" id="document">
													</div>
												</div>
												<?php if($dc_invoice_salary_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_invoice_salary ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("business_certificate")?> <?php echo ($dc_business_certificate)? '(' . count($dc_business_certificate) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="business_certificate" id="document">
													</div>
												</div>
												<?php if($dc_business_certificate_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_business_certificate ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("profit_for_the_last_three_months")?> <?php echo ($dc_profit_for_the_last_3_month)? '(' . count($dc_profit_for_the_last_3_month) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="profit_for_the_last_3_month" id="document">
													</div>
												</div>
												<?php if($dc_profit_for_the_last_3_month_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_profit_for_the_last_3_month ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>
												<?php } ?>
											</div>
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document"><?=lang("other_ducument")?>  <?php echo ($dc_other_dc_name)? '(' . count($dc_other_dc_name) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="other_document" id="document">
													</div>
												</div>
												<?php if($dc_other_dc_name){ ?>
												<div class="col-md-2" style="padding-top:29px;">
													<a href="<?php echo $dc_other_dc ?>" target="_blank" class="btn btn-primary form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
												</div>	
												<?php } ?>												
											</div>
								        </div>
									</div>
									
									<!--<div id="fields_check" style="display:none;" class="tab-pane fade show_map_click">-->
									<div id="fields_check" style="display:none;" class="tab-pane fade">
								        <div class="modal-body">
								            
											<!-- Fields Check -->
											
											<p><?= lang("the_current_address_base_on_documents_provide_below") ?></p>
											<div class="row">
												<div class="col-md-12 col-lg-12">
													<div class="col-md-3 col-sm-6">
														<input type="checkbox" name="fc_id_card" id="id_card" <?= ($field_check && $field_check->govid)? 'checked':''?> > <?= lang("identify_card") ?>
													</div>	
													<div class="col-md-4 col-sm-6">
														<input type="checkbox" name="fc_family_book" id="family_book" <?= ($field_check && $field_check->family_book)? 'checked':''?> > <?= lang("family_book") ?> 
													</div>
													<div class="col-md-3 col-sm-6">
														<input type="checkbox" name="fc_staying_book" id="staying_book" <?= ($field_check && $field_check->place_book)? 'checked':''?> > <?= lang("address_book") ?>
													</div>
													<div class="col-md-3 col-sm-6">
														<input type="checkbox" name="fc_water_invoice" id="water_invoice" <?= ($field_check && $field_check->water_letter)? 'checked':''?> > <?= lang("water_bill") ?>
													</div>
													<div class="col-md-4 col-sm-6">
													<input type="checkbox" name="fc_electricity_invoice" id="electricity_invoice" <?= ($field_check && $field_check->edc_letter)? 'checked':''?> > <?= lang("electrical_bill") ?>
													</div>
													<div class="col-md-4 col-sm-6">		
														<input type="checkbox" name="fc_check_property" id="check_property" <?= ($field_check && $field_check->property_check_letter)? 'checked':''?> > <?= lang("asset_certificate") ?>
													</div>
													<div class="col-md-7 col-sm-6">		
														<input type="checkbox" name="fc_check_landlord" id="check_landlord" <?= ($field_check && $field_check->claim_letter)? 'checked':''?> > <?= lang("chief_of_village_certify_letter") ?>
													</div>
													<div class="col-md-5" >	
														<div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">	
														<input type="checkbox" name="fc_other" id="other" <?= ($field_check && $field_check->other)? 'checked':''?> > <?= lang("other") ?>
														</div>
														<div class="col-md-8" style="padding-left: 0px; padding-right: 0px;">
															<?= form_input('fc_other_textbox',  ($field_check)? $field_check->other_note : '', 'class="form-control tip" id="other_textbox"') ?>
														</div>
													</div>
													<div>
															<div class="col-md-4"><p> <?= lang("current_address") ?></p></div>
															<div class="col-md-8"><?= form_input('fc_current_address',  ($field_check)? $field_check->requestor_curr_address: '', 'class="form-control tip" id="current_address"') ?></div>
													</div>
													<div>
															<div class="col-md-4"><p> <?= lang("phone_number") ?></p></div>
															<div class="col-md-8"><?= form_input('fc_phone_number', ($field_check)? $field_check->requestor_phone: '', 'class="form-control tip" id="phone_number"') ?></div>
													</div>
												</div>
											</div>
											<div class="row">
												<!--<button id="show_map" type="button" class="btn btn-primary">Map</button>-->
												<br/>
												<?= lang("latitude") ?>: <span id="lat"></span><br/><input type="hidden" name="latitude_" id="latitude_" value="" />
												<?= lang("longitude") ?>: <span id="long"></span><input type="hidden" name="longtitute_" id="longtitute_" value="" />
												<br/>
												
												<?php
												if($field_check && $field_check->latitude && $field_check->longitude) {
												?>
												<!--<div id="map" style="width:100%; height:300px;">
													<iframe width="100%" height="350px" src = "https://maps.google.com/maps?q=<?=$field_check->latitude?>,<?=$field_check->longitude?>&hl=es;z=20&amp;output=embed"></iframe>
												</div>-->
												<iframe width="100%" height="350px" src = "https://maps.google.com/maps?q=<?=$field_check->latitude?>,<?=$field_check->longitude?>&hl=es;z=20&amp;output=embed"></iframe>
													<?php 
												} else {
												?>
													<div id="map" style="width:99%; height:300px;"></div>
												<?php
												}
												?>
												<br/>
											</div>
											
											<!----field_check---------------->
											<!---<div class="row" style="display:none;">
												<div class="col-md-12 col-lg-12">
													<p>បរិយាយកាសការងារ</p>
														<div class="col-md-2">
															<input type="checkbox" name="fc_business1" id="business1" <?= ($field_check && $field_check->business1)? 'checked':''?> >ជំនួញ
														</div>
														<div class="col-md-2">
														<input type="checkbox" name="fc_company1" id="company1" <?= ($field_check && $field_check->company1)? 'checked':''?> >ក្រុមហ៊ុន
														</div>
														<div class="col-md-2">
														<input type="checkbox" name="fc_other1" id="other1" <?= ($field_check && $field_check->other1)? 'checked':''?> >ផ្សេងៗ
														</div>
														<div>
															<div class="col-md-2"><p>ឈ្មោះ</p></div>
															<div class="col-md-3"><?= form_input('fc_name', ($field_check)? $field_check->name:'', 'class="form-control tip" id="name"') ?></div>
														</div>
													</div>	
													<div class="col-md-12 col-lg-12">
														<div class="col-md-2">
															<input type="checkbox" name="fc_business2" id="business2" <?= ($field_check && $field_check->business2)? 'checked':''?> >ជំនួញ
														</div>
														<div class="col-md-2">
														<input type="checkbox" name="fc_company2" id="company2" <?= ($field_check && $field_check->company2)? 'checked':''?> >ក្រុមហ៊ុន
														</div>
														<div class="col-md-2">
														<input type="checkbox" name="fc_other2" id="other2" <?= ($field_check && $field_check->other2)? 'checked':''?> >ផ្សេងៗ
														</div>
														<div>
															<div class="col-md-2"><p>លេខទូរសព្ទ</p></div>
															<div class="col-md-3"><?= form_input('fc_phone', ($field_check)? $field_check->phone:'', 'class="form-control tip" id="name"') ?></div>
														</div>
													</div>	
													<div class="col-md-12 col-lg-12">
														<div class="col-md-2">
															<input type="checkbox" name="fc_business3" id="business3" <?= ($field_check && $field_check->business3)? 'checked':''?> >ជំនួញ
														</div>
														<div class="col-md-2">
														<input type="checkbox" name="fc_company3" id="company3" <?= ($field_check && $field_check->company3)? 'checked':''?> >ក្រុមហ៊ុន
														</div>
														<div class="col-md-2">
														<input type="checkbox" name="fc_other3" id="other3" <?= ($field_check && $field_check->other3)? 'checked':''?> >ផ្សេងៗ
														</div>
														<div>
															<div class="col-md-2"><p>អាសយដ្ឋាន​បច្ចុប្បន្ន</p></div>
															<div class="col-md-3"><?= form_input('fc_address', ($field_check)? $field_check->address:'', 'class="form-control tip" id="current_address"') ?></div>
														</div>
													</div>	
													<div class="col-md-12 col-lg-12">
														<div class="col-md-2">
															<input type="checkbox" name="fc_business4" id="business4" <?= ($field_check && $field_check->business4)? 'checked':''?> >ជំនួញ
														</div>
														<div class="col-md-2">
														<input type="checkbox" name="fc_company4" id="company4" <?= ($field_check && $field_check->company4)? 'checked':''?> >ក្រុមហ៊ុន
														</div>
														<div class="col-md-2">
														<input type="checkbox" name="fc_other4" id="other4" <?= ($field_check && $field_check->other4)? 'checked':''?> >ផ្សេងៗ
														</div>
														<div class="col-md-12" style="padding-top:10px;">
															<div class="col-md-3"><p>ម៉ោងធ្វើកា​រពី</p></div>
															<div class="col-md-2"><?= form_input('fc_start_time', ($field_check)? $field_check->start_work:'', 'class="form-control tip" id="start_time"') ?></div>
															<div class="col-md-1"><p>ដល់</p></div>
															<div class="col-md-2"><?= form_input('fc_end_time', ($field_check)? $field_check->end_work:'', 'class="form-control tip" id="end_time"') ?></div>
														</div>
													</div>
													<div class="col-md-12 col-lg-12">
														<div class="col-md-12" style="padding-top:10px;">
															<div class="col-md-3"><p>ធ្វើការប៉ុន្មានថ្ងៃក្នុងមួយសប្តាហ៍?</p></div>
															<div class="col-md-9"><?= form_input('hours', ($field_check)? $field_check->hours:'', 'class="form-control tip" id="hours"') ?></div>
														</div>
													</div>
												</div>
											</div>---->
											
											<div class="row">
												<div class="col-md-12">
													<p><?= lang("approval_note") ?>:</p>
													<!--<div class="col-md-3">
														<input type="checkbox" name="fc_evaluate" id="evaluate" <?= ($field_check && $field_check->go_there)? 'checked':''?> >ចុះវាយតំលៃ
													</div>
													<div class="col-md-3">
														<input type="checkbox" name="fc_none_evaluate" id="none_evaluate" <?= ($field_check && $field_check->not_go_there)? 'checked':''?> >មិនចុះវាយតំលៃ
													</div>-->
													<div class="table-responsive">	
														<table class="table">
															<tbody>
															  <tr>
																<td><input type="checkbox" name="fc_evaluate" id="evaluate" <?= ($field_check && $field_check->go_there)? 'checked':''?> ><?= lang("filed_check")?></td>
																<td><input type="checkbox" name="fc_none_evaluate" id="none_evaluate" <?= ($field_check && $field_check->not_go_there)? 'checked':''?> ><?= lang("no_filed_check")?></td>
																<td><p><?= lang("co_name")?> :</p></td>
																<td><?= form_input('official_evaluate', ($field_check)? $field_check->official_name:'', 'class="form-control tip" id="official_evaluate"') ?></td>
																
																<td><p><?= lang("phone_number")?>:</p></td>
																<td><?= form_input('official_num', ($field_check)? $field_check->official_phone:'', 'class="form-control tip" id="official_num"') ?></td>
															  </tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									
									
								</div>
								<div class="tab-pane">
									<input type="submit" class="btn btn-primary hide_btnsubmit" value="<?=lang('submit')?>" name="submitQoute" />
								</div>
								<?php echo form_close(); ?>
							</div>
						</div>	
				</div>
			</div>
		<div>
	</div>
</div>
<div class="modal" id="mModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="mModalLabel"><?= lang('add_product_manually') ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mcode">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mname" class="col-sm-4 control-label"><?= lang('product_name') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mname">
                        </div>
                    </div>
                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group">
                            <label for="mtax" class="col-sm-4 control-label"><?= lang('product_tax') ?> *</label>

                            <div class="col-sm-8">
                                <?php
                                $tr[""] = "";
                                foreach ($tax_rates as $tax) {
                                    $tr[$tax->id] = $tax->name;
                                }
                                echo form_dropdown('mtax', $tr, "", 'id="mtax" class="form-control input-tip select" style="width:100%;"');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= lang('quantity') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mquantity">
                        </div>
                    </div>
                    <?php if ($Settings->product_discount && ($Owner || $Admin || $this->session->userdata('allow_discount'))) { ?>
                        <div class="form-group">
                            <label for="mdiscount"
                                   class="col-sm-4 control-label"><?= lang('product_discount') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="mdiscount">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="mprice" class="col-sm-4 control-label"><?= lang('unit_price') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mprice">
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="mnet_price"></span></th>
                            <th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="mpro_tax"></span></th>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addItemManually"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

	function refreshComment(){
		$(".comment-list").empty();
		var quote_id = $(".comment-quote-id").val();
		if(quote_id){
			$.ajax({
				url: site.base_url + 'quotes/ajaxGetComments/' + quote_id,
				dataType: 'json',
				async: true,
				cache: false,
				success: function(data){
					if(data){
						var comment_list = $(".comment-list");
						html = '';
						$.each(data, function(){
							console.log(formatDateTimeAMPM(this.date));
							html += '<div style="display:block;margin-bottom:20px;" class="clearfix">';
							html += '	<span class="user-name pull-left">'+ this.user_name +'</span>';
							html += '	<span class="user-comment pull-left">'+ this.comment +'</span>';
							html += '	<span class="comment-date"><i class="fa fa-check" aria-hidden="true"></i> '+ formatDateTimeAMPM(this.date, true) +'</span>';
							html += '</div>';
						});
						comment_list.append(html);
					}else{
						console.log(data);
					}
				}
			});
		}
	}

	$(window).load(function() {
		$("#advance_percentage").trigger('change');
		$(".category").trigger('change');
		$(".sub_category").trigger('change');
		$("#category").trigger('change');
		$('.ch_services').trigger('ifChanged');
		$('#total_amount').trigger('change');
		refreshComment();
		$('#cus_marital_status').trigger('change');
		$('#interest_rate_cash_2').trigger('change');
		$('#group').trigger('change');
		
		$('#tax_'+ _id).trigger('change');
		$('#tax_id_'+ _id).trigger('change');
		
		
	});
	$(document).ready(function() {
		var inFormOrLink;
		$('a').live('click', function() { inFormOrLink = true; });
		$('form').bind('submit', function() { inFormOrLink = true; });

		$(window).bind('beforeunload', function(eventObject) {
			var returnValue = undefined;
			if (! inFormOrLink) {
				returnValue = "Do you really want to close?";
			}
			eventObject.returnValue = returnValue;
			return returnValue;
		});
		
		$("#comment-submit").on('click', function(){
			var comment = $("#comment").val();
			var quote_id = $(".comment-quote-id").val();
			if(comment){
				$.ajax({
					url: site.base_url + 'quotes/addComment',
					dataType: 'POST',
					data:{'quote_id':quote_id, 'comment':comment},
					async: false,
					success: function(response){
						refreshComment();
					}
				});
			}
			
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
				dataType: 'JSON',
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
		//////////////
		
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
		
		////////////////
		$('.sub_category').on('change', function(){
			var sub_category_id = $(this).val();
			$.ajax({
				url: site.base_url + 'quotes/ajaxGetProductBySubCategoryID/'+sub_category_id,
				dataType: 'JSON',
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
		
		$('.ch_services').on('ifChanged', function(){
			var total = 0;
			var price = $('#total_amount').val()-0;
			$('.ch_services').each(function() {
				var amount = 0;
				if($(this).is(':checked')) {
					if($(this).attr('status') === 'Percentage') {
						if(price > 0) {
							amount = $(this).attr('amount') * price;
						}else {
							alert('Please enter price!');
							return false;
						}
					}else {
						amount = $(this).attr('amount')-0;
					}
					total += amount;
				}
			});

			$('#total_inst').val(formatMoney(total));
		});
		
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
					
						var link = $('<a href="quotes/gov_id_report/'+data.id +'" rel="lightbox" id="link" data-toggle="modal" data-target="#myModal"></a>');
						  $("body").append(link);
						  $("#link")[0].click();
					}
				}
			});
		});
		
		$('#total_amount').on('change', function() {
			if($('#advance_percentage').val()) {
				$('#advance_percentage').trigger('change');
			}
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
		
		$('#interest_rate_cash, #term_cash, #total_amount, #rate_type_cash, #frequency_cash').on('change', function() {
			var interest = parseFloat($('#interest_rate_cash').val());
			var term = Number($('#term_cash').val());
			var lease_amount = parseFloat($('#total_amount').val());
			var rate_type = $('#rate_type_cash').val();
			var frequency_cash = (($('#frequency_cash').val() > 0)? $('#frequency_cash').val() : '');
			
			if(lease_amount > 0 && interest > 0 && term > 0 && rate_type != '' && frequency_cash > 0) {
				var all_total = getAllTotal(lease_amount, rate_type, interest, term, frequency_cash);
				$('#total_interest_rate').val(formatMoney(all_total['total_interest']));
				$('.btn_print_payment_schedule_cash').show();
			} else {
				$('#total_interest_rate').val(formatMoney(0));
				$('.btn_print_payment_schedule_cash').hide();
			}		
		});
				
	});
</script>

<!-- Filter Address --->
<script>
	$(document).ready(function() {
		
			$('.show_map_click').click(function() {
				$("#map").show();
			var map_text='<iframe width="100%" height="350px" src = "https://maps.google.com/maps?q=<?=$field_check->latitude?>,<?=$field_check->longitude?>&hl=es;z=20&amp;output=embed"></iframe>';
					initMap();								
				$("#map").append(map_text);
				$("#map").refresh();
			}); 
				
			$('#show_map').live('click', function() {
				
				$("#map").show();
			var map_text='<iframe width="100%" height="350px" src = "https://maps.google.com/maps?q=<?=$field_check->latitude?>,<?=$field_check->longitude?>&hl=es;z=20&amp;output=embed"></iframe>';
					initMap();								
				$("#map").append(map_text);
				$("#map").refresh();
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
		
		$('#cus_dob').live('change', function() {
			var dob = $(this).val();
			if(dob != '') {
				var age = getAge(dob);
				$('#cus_age').val(age +' Year old');
			}else {
				$('#cus_age').val('');
			}
		});
		
		/* --------------- Filter Provinces By Country -------------- */
		$('#cus_country').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getProvinces') ?>/" + v;
			var child_obj = 'cus_province';
			var lang = '<?= lang('select_province') ?>';
			var pholder = '<?= lang('select_province_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		$('#emp_country').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getProvinces') ?>/" + v;
			var child_obj = 'emp_province';
			var lang = '<?= lang('select_province') ?>';
			var pholder = '<?= lang('select_province_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		$('#country').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getProvinces') ?>/" + v;
			var child_obj = 'province';
			var lang = '<?= lang('select_province') ?>';
			var pholder = '<?= lang('select_province_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		
		/* --------------- Filter Districts By Province -------------- */
		$('#cus_province').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getDistricts') ?>/" + v;
			var child_obj = 'cus_district';
			var lang = '<?= lang('select_district') ?>';
			var pholder = '<?= lang('select_district_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		$('#emp_province').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getDistricts') ?>/" + v;
			var child_obj = 'emp_district';
			var lang = '<?= lang('select_district') ?>';
			var pholder = '<?= lang('select_district_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		$('#province').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getDistricts') ?>/" + v;
			var child_obj = 'district';
			var lang = '<?= lang('select_district') ?>';
			var pholder = '<?= lang('select_district_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		
		/* --------------- Filter Communces By District -------------- */
		$('#cus_district').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getCommunces') ?>/" + v;
			var child_obj = 'cus_communce';
			var lang = '<?= lang('select_communce') ?>';
			var pholder = '<?= lang('select_communce_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		$('#emp_district').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getCommunces') ?>/" + v;
			var child_obj = 'emp_communce';
			var lang = '<?= lang('select_communce') ?>';
			var pholder = '<?= lang('select_communce_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		$('#district').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getCommunces') ?>/" + v;
			var child_obj = 'communce';
			var lang = '<?= lang('select_communce') ?>';
			var pholder = '<?= lang('select_communce_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		
		/* --------------- Filter Villages By Communce -------------- */
		$('#cus_communce').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getVillages') ?>/" + v;
			var child_obj = 'cus_village';
			var lang = '<?= lang('select_village') ?>';
			var pholder = '<?= lang('select_village_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		$('#emp_communce').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getVillages') ?>/" + v;
			var child_obj = 'emp_village';
			var lang = '<?= lang('select_village') ?>';
			var pholder = '<?= lang('select_village_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		$('#communce').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getVillages') ?>/" + v;
			var child_obj = 'village';
			var lang = '<?= lang('select_village') ?>';
			var pholder = '<?= lang('select_village_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
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
		
		
	});
</script>


<script type="text/javascript">
    $(document).ready(function () {
		$('#cus_country').trigger('change');
		$('#cus_province').trigger('change');
		$('#cus_district').trigger('change');
		$('#cus_communce').trigger('change');
		
		$('#emp_country').trigger('change');
		$('#emp_province').trigger('change');
		$('#emp_district').trigger('change');
		$('#emp_communce').trigger('change');
		
		$('#country').trigger('change');
		$('#province').trigger('change');
		$('#district').trigger('change');
		$('#communce').trigger('change');
		
		$('#cl_type').trigger('change');
		$('#product_id').trigger('change');
		//$('#interest_rate_cash_2').trigger('change');
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
	
	var count_link=0;
		$('#print_payment_schedule').click(function() {
				var product = $('.product_id').select2('data');
				localStorage.setItem('product', product.text);
				if( product!=null){
						localStorage.setItem('product', product.text);
				}else{
					localStorage.setItem('product', 'N/A');
				}
				var dealer = $('#qubiller').select2('data');
				localStorage.setItem('dealer', dealer.text);	
				var year=	$('#year').select2('data');
				if( year!=null){
					localStorage.setItem('year', year.text);
				}else{
					localStorage.setItem('year', 'N/A');
				}	
				var power=	$('#power').val();
				localStorage.setItem('power', power);
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
				var frequency = $('#frequency').val();
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
				var link= $('<a href="Installment_Payment/payment_schedule_preview/'+leaseamount+'/'+rate_type+'/'+interest_rate+'/'+term+'/'+frequency+'" rel="lightbox" id="print_payment'+count_link+'" data-toggle="modal" data-target="#myModal"></a>');
			
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
			
				
			if(localStorage.getItem('total_inst')){
				localStorage.removeItem('total_inst');
				var t_service=$('#total_inst').val();
				localStorage.setItem('total_inst', t_service);
			}
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
			
			var currency = $('#currency').val();
			var cdate = ($('#st_inst_date').val()).split('/');
			var principle_fq = $('#principle_frequency').val();
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
				//var title = $(this).attr('title');
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
			
			//var link1= $('<a href="Quotes/cash_payment_schedule_preview/'+lease_amount+'/'+rate_type+'/'+interest_rate+'/'+term_cash+'/'+frequency_cash+'/'+currency+'" rel="lightbox" id="print_payment'+count_link1+'" data-toggle="modal" data-target="#myModal"></a>');
			//alert(services)
			if(services == '') {
				var link1= $('<a href="Quotes/cash_payment_schedule_preview/'+lease_amount+'/'+rate_type+'/'+interest_rate+'/'+term_cash+'/'+frequency_cash+'/'+currency+'/'+new_date+'/'+principle_fq+'/'+null+'/'+saving_amount+'/'+saving_interest_amount+'/'+saving_type+'" rel="lightbox" id="print_payment'+count_link1+'" data-toggle="modal" data-target="#myModal"></a>');
			}else {
				var link1= $('<a href="Quotes/cash_payment_schedule_preview/'+lease_amount+'/'+rate_type+'/'+interest_rate+'/'+term_cash+'/'+frequency_cash+'/'+currency+'/'+new_date+'/'+principle_fq+'/'+services+'/'+saving_amount+'/'+saving_interest_amount+'/'+saving_type+'" rel="lightbox" id="print_payment'+count_link1+'" data-toggle="modal" data-target="#myModal"></a>');
			}
				$("body").append(link1);
				$('#print_payment'+count_link1).click();
			count_link1++;
		});
		
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
		/*=======Alert Msg group_loan when same group_name====*/
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
		
		<!--SETHY-->
		$('#land').hide();
		$('#home').hide();
		$('#vehicles').hide();
		$('#cl_type').on('change', function() {	
			
			if($('#cl_type').val()=="1"){
				$('#home').show();
				$('#land').hide();
				$('#vehicles').hide();				
			}
			if($('#cl_type').val()=="2"){
				$('#land').show();
				$('#home').hide();	
				$('#vehicles').hide();
			}
			if($('#cl_type').val()=="3"){
				$('#land').hide();
				$('#home').hide();
				$('#vehicles').show();
			}
			
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
		
		$('#saving_rate').live('keyup , change', function(e) {
			var saving_rate = $(this).val();
			var total_amount = $('#total_amount').val();
			var saving_rates = saving_rate.replace('%', '');
			if(saving_rate.search('%') > 0) { 
				var saving = (saving_rates/100);
				var saving_amt = total_amount * saving ;
				$('#saving_amount').val(formatMoney(saving_amt));
			}
		});
		
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLDtd4RIGX4kRWPxYneXRsS9oiPsIGx4A&callback=initMap"></script>
<!-- end get lat long -->

<!--
if($collaterals) {
			foreach($collaterals as $collateral) {
				$coll = $this->db->get_where('collateral', array('id' => $collateral->id, 'quote_id' => $id));
				if($coll->row()) {
					$this->db->update('collateral', $collateral, array('id' => $collateral->id, 'quote_id' => $id));
				}else {
					$this->db->insert('collateral', $collateral);
				}
			}
		}
-->