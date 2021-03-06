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
        localStorage.setItem('qustatus', '<?=$inv->status?>');
        localStorage.setItem('qunote', '<?= str_replace(array("\r", "\n"), "", $this->erp->decode_html($inv->note)); ?>');
        localStorage.setItem('qudiscount', '<?=$inv->order_discount_id?>');
        localStorage.setItem('qutax2', '<?=$inv->order_tax_id?>');
        localStorage.setItem('qushipping', '<?=$inv->shipping?>');
        localStorage.setItem('quitems', JSON.stringify(<?=$inv_items;?>));
        <?php } ?>
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
</script>


<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-edit"></i><?= lang('transfer_contract'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
				
                <p class="introtext"><?php echo lang('enter_info'); ?></p>
				
				<div class="col-md-12">
							<ul id="dbTab" class="nav nav-tabs">
								<li class=""><a href="#applicants"><?= lang('applicants') ?></a></li>
								<li class=""><a href="#financial_products"><?= lang('financial_products') ?></a></li>
								<li class=""><a href="#employee"><?= lang('employee') ?></a></li>
								<li class=""><a href="#guarantors"><?= lang('guarantors') ?></a></li>
								<li class=""><a href="#documents"><?= lang('documents') ?></a></li>
								<li class=""><a href="#fields_check"><?= lang('fields_check') ?></a></li>
								<li class=""><a href="#comments"><?= lang('Comments') ?></a></li>
							</ul>
							<div class="tab-content">
								<?php
								$attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class' => 'edit-qu-form');
								echo form_open_multipart("Down_Payment/transfer_contract/" . $id, $attrib)
								?>
								<div id="applicants" class="tab-pane fade in">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<p><?= lang('enter_info'); ?></p>
												<div class="row">
													<div class="col-lg-12">
														<div class="col-md-6">
															<?php if ($Owner || $Admin || !$this->session->userdata('biller_id')) { ?>
																	<div class="form-group">
																		<?= lang("dealer", "qubiller"); ?>
																		<?php
																		$bl[""] = "";
																		foreach ($billers as $biller) {
																			$bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
																		}
																		echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $inv->biller_id), 'id="qubiller" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("dealer") . '" required="required" class="form-control input-tip select" style="width:100%;"');
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
															} ?>
															<div class="form-group">
																<?= lang("civility", "cus_civility"); ?>
																<?php
																$cus_civility[""] = "";
																$cus_civility['male'] = "Mr.";
																$cus_civility['female'] = "Mrs.";
																echo form_dropdown('cus_civility', $cus_civility, isset($applicant->civility)?$applicant->civility:'', 'class="form-control select" id="cus_civility" placeholder="' . lang("select") . ' ' . lang("civility") . '" style="width:100%" data-bv-notempty="true"')
																?>
															</div>
															<div class="form-group">
																<?= lang("family_name", "cus_family_name"); ?>
																<?php echo form_input('cus_family_name', $applicant->family_name, 'class="form-control tip" id="cus_family_name" data-bv-notempty="true"'); ?>
															</div>
															<div class="form-group">
																<?= lang("first_name", "cus_first_name"); ?>
															   <?php echo form_input('cus_first_name', $applicant->name, 'class="form-control" id="cus_first_name" required="required"'); ?>
															</div>
															<div class="form-group person" style="display:none;">
																<?= lang("nick_name", "cus_nick_name"); ?>
																<?php echo form_input('cus_nick_name', $applicant->nickname, 'class="form-control tip" id="cus_nick_name"'); ?>
															</div>
															<div class="form-group">
																<?= lang("spouse_first_name", "cus_sp_fname"); ?>
																<?php echo form_input('cus_sp_fname', $applicant->spouse_name, 'class="form-control" id="cus_sp_fname"'); ?>
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
															<div class="form-group">
																<?= lang("place_of_birth", "cus_pob"); ?>
																<?php echo form_input('cus_pob', $applicant->address, 'class="form-control" id="cus_pob"'); ?>
															</div>
															<div class="form-group">
																<?= lang("age", "cus_age"); ?>
																<?php echo form_input('cus_age', $applicant->age == 0? '':$applicant->age, 'class="form-control" id="cus_age"'); ?>
															</div>
															<div class="form-group">
																<?= lang("whose_income", "cus_whose_income"); ?>
																<?php echo form_input('cus_whose_income', $applicant->whose_income, 'class="form-control" id="cus_whose_income"'); ?>
															</div>
															<div class="form-group">
																<?= lang("nationality", "cus_nationality"); ?>
																<?php
																$cus_nationality[""] = "";
																$cus_nationality['cam'] = "Cambodia";
																$cus_nationality['tha'] = "Thailand";
																echo form_dropdown('cus_nationality', $cus_nationality, isset($applicant->nationality)?$applicant->nationality:'', 'class="form-control select" id="cus_nationality" placeholder="' . lang("select") . ' ' . lang("nationality") . '" style="width:100%" data-bv-notempty="true"')
																?>
															</div>
															<div class="form-group">
																<?= lang("phone_2", "cus_phone_2"); ?>
																<input type="tel" name="cus_phone_2" class="form-control" id="cus_phone_2" value="<?=$applicant->phone2?>" />
															</div>
														</div>
														
														<div class="col-md-6">
															<?php if (isset($this->permission['reports-underwriting']) ?$this->permission['reports-underwriting'] : ('') || $this->Admin || $this->Owner){ ?>
																	<div class="form-group">
																		<?= lang("status", "qstatus"); ?>
																		<?php
																		$status_q = array('' => '', 'applicant' => lang('applicant'));
																		if(isset($sale->sale_status) && $sale->sale_status == 'approved'){
																			unset($status_q['applicant']);
																		}
																		echo form_dropdown('status', $status_q, ($inv->status? $inv->status : ''), 'id="qstatus" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("status") . '" class="form-control input-tip " style="width:100%;"');
																		?>
																	</div>
															<?php } ?>
															<div class="form-group">
																<?= lang("government_id", "cus_gov_id"); ?>
																<?php echo form_input('cus_gov_id', $applicant->gov_id, 'class="form-control" id="cus_gov_id" data-bv-notempty="true"'); ?>
															</div>
															<div class="form-group">
																<?= lang("family_name_other", "cus_family_name_other"); ?>
																<?php echo form_input('cus_family_name_other', $applicant->family_name_other, 'class="form-control" id="cus_family_name_other" '); ?>
															</div>
															<div class="form-group">
																<?= lang("first_name_other", "cus_first_name_other"); ?>
																<?php echo form_input('cus_first_name_other', $applicant->name_other, 'class="form-control" id="cus_first_name_other"'); ?>
															</div>
															<div class="form-group">
																<?= lang("spouse_family_name", "cus_sp_fam_name"); ?>
																<?php echo form_input('cus_sp_fam_name', $applicant->spouse_family_name, 'class="form-control" id="cus_sp_fam_name"'); ?>
															</div>
															<div class="form-group">
																<?= lang("number_of_children", "cus_num_of_child"); ?>
																<?php echo form_input('cus_num_of_child', $applicant->num_of_child == 0? '':$applicant->num_of_child, 'class="form-control" id="cus_num_of_child"'); ?>
															</div>
															<div class="form-group">
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
															<div class="form-group">
																<?= lang("date_of_birth", "cus_dob"); ?>
																<?php echo form_input('cus_dob', $this->erp->hrsd($applicant->date_of_birth), 'class="form-control date" id="cus_dob"'); ?>
															</div>
															<div class="form-group">
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
															<div class="form-group">
																<?= lang("phone_1", "cus_phone_1"); ?>
																<input type="tel" name="cus_phone_1" class="form-control" id="cus_phone_1" value="<?=$applicant->phone1?>" required="required"/>

															</div>
															<div class="form-group">
																<?= lang("spouse_mobile_phone", "cus_sp_phone"); ?>
																<input type="tel" name="cus_sp_phone" class="form-control" id="cus_sp_phone" value="<?=$applicant->spouse_phone?>"/>
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
																			<?= lang("house_no", "cus_house_no"); ?>
																			<?php echo form_input('cus_house_no', $applicant->house_no, 'class="form-control" id="cus_house_no"'); ?>
																		</div>
																		<div class="form-group">
																			<b style="padding-bottom:5px; display:block;"><?= lang("time_at_this_address"); ?></b>
																			<?php echo form_input('cus_years', $applicant->years, 'class="form-control" id="cus_years" placeholder="' . lang("years") . '" style="display:inline !important; width:35% !important;"'); ?>
																			<?= lang("years", "cus_years"); ?>
																			<?php echo form_input('cus_months', $applicant->months, 'class="form-control" id="cus_months" placeholder="' . lang("months") . '" style="display:inline !important; width:35% !important;"'); ?>
																			<?= lang("months", "cus_months"); ?>
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
																		<div class="form-group">
																			<?= lang("street", "cus_street"); ?>
																			<?php echo form_input('cus_street', $applicant->street, 'class="form-control" id="cus_street"'); ?>
																		</div>
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
																		$cat_all = array();
																		if(is_array(isset($categories) ?$categories  : (''))){
																		foreach($categories as $cat_){
																			$cat_all[$cat_->id] = $cat_->name;
																		}}
																		echo form_dropdown('category_id',isset( $cat_all) ? $cat_all  : (''), isset($product->category_id) ?$product->category_id  : (''), 'class="form-control category" required');
																		?>
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group">
																		<?php echo lang('sub_category', 'sub_category') ?>
																		<?php
																		echo form_input('sub_category', isset($product->subcategory_id) ?$product->subcategory_id  : (''), 'class="form-control sub_category" id="sub_category"  placeholder="' . lang("select_category_to_load") . '"');
																		?>
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group">
																		<?php echo lang('product', 'product') ?>
																		<?php
																		$pr_all = array();
																		if(is_array(isset($products) ?$products  : (''))){
																		foreach($products as $pr_){
																			$pr_all[$pr_->id] = $pr_->name;
																		}}
																		echo form_input('product_id', isset($product->product_id) ?$product->product_id  : (''), 'class="form-control product_id" id="product_id"  placeholder="' . lang("select_product_to_load") . '" ');
																		?>
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group all">
																		<?= lang("price", "price") ?>
																		<?= form_input('price', (isset($product->unit_price) ? $this->erp->formatDecimal($product->unit_price) : ''), 'class="form-control" id="total_amount"  required="required"') ?>
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group all">
																		<?= lang("color", "color") ?>
																		<?php
																		$vari[""] = "";
																		if($variants){
																		foreach ($variants as $variant) {
																			$vari[$variant->id] = $variant->name;
																		}}
																		echo form_dropdown('color', $vari, (isset($product->color) ? $product->color : ''), 'id="color" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("color") . '"  class="form-control input-tip select" style="width:100%;"');
																		?>
																	</div>
																</div>
																
																<div class="col-md-4">
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
																
																<div class="col-md-4">
																	<div class="form-group all">
																		<?= lang("engine", "engine") ?>
																		<?= form_input('engine', (isset($product->engine) ? $product->engine : ''), 'class="form-control" id="engine"'); ?>
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group all">
																		<?= lang("frame_number", "frame") ?>
																		<?= form_input('frame', (isset($product->frame) ? $product->frame : ''), 'class="form-control" id="frame"'); ?>
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group all">
																		<?= lang("power", "power") ?>
																		<?= form_input('power', (isset($product->power) ? $product->power: ''), 'class="form-control" id="power"'); ?>
																	</div>
																</div>

																<div class="col-md-4">
																	<div class="form-group all">
																		<?= lang("distance_mile", "distance") ?>
																		<?= form_input('distance', (isset($product->distance_mile) ? $product->distance_mile : ''), 'class="form-control" id="distance"'); ?>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												
												<div class="col-sm-12">
													<div class="panel panel-primary">
														<div class="panel-heading"><?= lang('services') ?></div>
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
																		<?= lang('split_with_installment'); ?>
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<?= lang('total_installment_amount'); ?>
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
																		<?php echo form_input('service[]', $this->erp->formatMoney($service->amount), 'class="form-control input-tip services" id="services" readonly '); ?>
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<input type="checkbox" class="form-control ch_services" amount="<?php echo isset($service->amount ) ?$service->amount   : ('')?>" name="ch_services[]" value="1" <?php echo set_checkbox('ch_services[]', '1', isset($quote_service[$k]->quote_id)==$id?TRUE:FALSE); ?>>
																	</div>
																</div>
																<?php
																if($k == 0) {
																?>
																<div class="col-md-3">
																	<div class="form-group">
																		<?php echo form_input('total_inst', 0, 'class="form-control input-tip" id="total_inst" readonly'); ?>
																	</div>
																</div>
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
												
												<div class="col-sm-12">
													<div class="panel panel-primary">
														<div class="panel-heading"><?= lang('financial_products') ?></div>
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
																	$frequency["monthly"] = "Monthly";
																	$frequency["quarterly"] = "Quarterly";
																	$frequency["haft_year"] = "Haft Year";
																	echo form_dropdown('frequency', $frequency, $inv->frequency, 'id="frequency" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("frequency") . '"  class="form-control input-tip select" style="width:100%;"');
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("rate_type", "rate_type"); ?>
																	<?php
																	$rate_type[""] = "";
																	$rate_type["1"] = "Normal";
																	$rate_type["2"] = "Fixed";
																	$rate_type["3"] = "Normal_Fixed";
																	$rate_type["4"] = "Custom";
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
																	<?= lang("term_in_month", "term_in_month"); ?>
																	<?php
																	$term[""] = "";
																	foreach ($term_in_months as $term_in_month) {
																		$term[$term_in_month->amount] = $term_in_month->description;
																	}
																	echo form_dropdown('term_in_month', $term, $inv->term, 'id="term_in_month" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("term_in_month") . '" class="form-control input-tip select" style="width:100%;"');
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
																			$emp_status = array('active' => 'Active');
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
																		<?php echo form_input('work_phone', $quote_employee? $quote_employee->work_phone:'', 'class="form-control input-tip" id="work_phone"'); ?>
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
																<!--
																<div class="col-md-12">
																	<div class="form-group">
																		<?php echo form_checkbox(['name' => 'same_applicant_address', 'value' => 1]); ?>
																		<?= lang("same_applicant_address", "same_applicant_address"); ?>
																	</div>
																</div>
																-->																
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("country", "emp_country"); ?>
																		<?php
																			foreach ($countries as $ctry) {
																				$emp_country[$ctry->code] = $ctry->name;
																			}
																			echo form_dropdown('emp_country', $emp_country, $quote_employee? $quote_employee->country:'', 'class="form-control select" id="emp_country" placeholder="' . lang("select") . ' ' . lang("country") . '" style="width:100%" data-bv-notempty="true"');
																		?>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("district", "emp_district"); ?>
																			<?php echo form_input('emp_district', isset($customer->district)?$customer->district:'', 'class="form-control" id="cus_district"  placeholder="' . lang("select_province_to_load") . '" data-bv-notempty="true"');?>
																		</div>
																</div>
																<!--<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("province", "emp_province"); ?>
																		<?php echo form_input('emp_province', ($quote_employee ? $quote_employee->province : ''), 'class="form-control" id="emp_province"  placeholder="' . lang("select_province") . '"');?>
																	</div>
																</div>
																-->
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("district", "emp_district"); ?>
																		<?php echo form_input('emp_district', ($quote_employee ? $quote_employee->district : ''), 'class="form-control" id="emp_district"  placeholder="' . lang("select_district") . '"');?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("commune", "emp_communce"); ?>
																		<?php echo form_input('emp_communce', ($quote_employee ? $quote_employee->communce : ''), 'class="form-control" id="emp_communce"  placeholder="' . lang("select_communce") . '"');?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("village", "emp_village"); ?>
																		<?php echo form_input('emp_village', ($quote_employee ? $quote_employee->village : ''), 'class="form-control" id="emp_village"  placeholder="' . lang("select_village") . '"');?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("street", "emp_street")?>
																		<?php echo form_input('emp_street', $quote_employee? $quote_employee->street:'', 'class="form-control input-tip" id="emp_street"'); ?>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("house_no", "emp_house_no")?>
																		<?php echo form_input('emp_house_no', $quote_employee? $quote_employee->house_no:'', 'class="form-control input-tip" id="emp_house_no"'); ?>
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
								<div id="guarantors" style="display: none;" class="tab-pane fade">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
													<p><?= lang('enter_info'); ?></p>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<?= lang("civility", "civility"); ?>
															<?php
															$civility[""] = "";
															$civility['mr'] = "Mr.";
															$civility['mrs'] = "Mrs.";
															$civility_status = '';
															$civility = array('mr' => 'Mr.', 'mrs' => 'Mrs.');
															
															if($guarantor) {
																if($guarantor->civility == 'male' || $guarantor->civility == 'mr'){
																	$civility_status = $civility['mr'];
																}elseif($guarantor->civility == 'female' || $guarantor->civility == 'mrs'){
																	$civility_status = $civility['mrs'];
																}
															}
															echo form_dropdown('civility', $civility, $civility_status, 'class="form-control select" id="civility" placeholder="' . lang("select") . ' ' . lang("civility") . '" style="width:100%" ')
															?>
														</div>
														<div class="form-group">
															<?= lang("family_name", "family_name"); ?>
															<?php echo form_input('family_name', $guarantor? $guarantor->family_name:'', 'class="form-control tip" id="family_name" '); ?>
														</div>
														<div class="form-group">
															<?= lang("first_name", "first_name"); ?>
														   <?php echo form_input('first_name', $guarantor? $guarantor->name:'', 'class="form-control" id="first_name" '); ?>
														</div>
														<div class="form-group person">
															<?= lang("nick_name", "nick_name"); ?>
															<?php echo form_input('nick_name', $guarantor? $guarantor->nickname:'', 'class="form-control tip" id="nick_name"'); ?>
														</div>
														<div class="form-group">
															<?= lang("spouse_first_name", "sp_fname"); ?>
															<?php echo form_input('sp_fname', $guarantor? $guarantor->spouse_family_name:'', 'class="form-control" id="sp_fname" '); ?>
														</div>
														<div class="form-group">
															<?= lang("gender", "gender"); ?>
															<?php
															$gender[""] = "";
															$gender['male'] = "Male";
															$gender['female'] = "Female";
															echo form_dropdown('gender', $gender, $guarantor? $guarantor->gender:'', 'class="form-control select" id="gender" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%" ')
															?>
														</div>
														<div class="form-group">
															<?= lang("place_of_birth", "pob"); ?>
															<?php echo form_input('pob', $guarantor? $guarantor->address:'', 'class="form-control" id="pob"'); ?>
														</div>
														<div class="form-group">
															<?= lang("nationality", "nationality"); ?>
															<?php
															$nationality[""] = "";
															$nationality['cam'] = "Cambodia";
															$nationality['tha'] = "Thailand";
															echo form_dropdown('nationality', $nationality, $guarantor? $guarantor->nationality:'', 'class="form-control select" id="nationality" placeholder="' . lang("select") . ' ' . lang("nationality") . '" style="width:100%"')
															?>
														</div>
														<div class="form-group">
															<?= lang("phone_2", "phone_2"); ?>
															<input type="tel" name="phone_2" class="form-control" id="phone_2" value="<?=$guarantor? $guarantor->phone2:''?>"/>
														</div>
														<div class="form-group">
															<?= lang("country", "country"); ?>
															<?php
															foreach ($countries as $ctr) {
																$country[$ctr->code] = $ctr->name;
															}
															echo form_dropdown('country', $country, ($guarantor)? $guarantor->country:'', 'class="form-control select" id="country" placeholder="' . lang("select") . ' ' . lang("country") . '" style="width:100%" ');
															?>
														</div>
														<div class="form-group">
															<?= lang("district", "district"); ?>
															<?php echo form_input('district', ($guarantor ? $guarantor->district : ''), 'class="form-control" id="district"  placeholder="' . lang("select_district") . '"');?>
														</div>
														<div class="form-group">
															<?= lang("village", "village"); ?>
															<?php echo form_input('village', ($guarantor ? $guarantor->village : ''), 'class="form-control" id="village"  placeholder="' . lang("select_village") . '"');?>
														</div>
														<div class="form-group">
															<?= lang("house_no", "house_no"); ?>
															<?php echo form_input('house_no', $guarantor? $guarantor->phone2:'', 'class="form-control" id="house_no"'); ?>
														</div>
														<div class="form-group">
															<b style="padding-bottom:5px; display:block;"><?= lang("time_at_this_address"); ?></b>
															<?php echo form_input('years', $guarantor? $guarantor->years:'', 'class="form-control" id="years" placeholder="' . lang("years") . '" style="display:inline !important; width:35% !important;"'); ?>
															<?= lang("years", "years"); ?>
															<?php echo form_input('months', $guarantor? $guarantor->months:'', 'class="form-control" id="months" placeholder="' . lang("months") . '" style="display:inline !important; width:35% !important;"'); ?>
															<?= lang("months", "months"); ?>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<?= lang("government_id", "gov_id"); ?>
															<?php echo form_input('gov_id', $guarantor? $guarantor->gov_id:'', 'class="form-control" id="gov_id" '); ?>
														</div>
														<div class="form-group">
															<?= lang("family_name_other", "family_name_other"); ?>
															<?php echo form_input('family_name_other', $guarantor? $guarantor->family_name_other:'', 'class="form-control" id="family_name_other" '); ?>
														</div>
														<div class="form-group">
															<?= lang("first_name_other", "first_name_other"); ?>
															<?php echo form_input('first_name_other', $guarantor? $guarantor->name_other:'', 'class="form-control" id="first_name_other" '); ?>
														</div>
														<div class="form-group">
															<?= lang("spouse_family_name", "sp_fam_name"); ?>
															<?php echo form_input('sp_fam_name', $guarantor? $guarantor->spouse_family_name:'', 'class="form-control" id="sp_fam_name"'); ?>
														</div>
														<div class="form-group">
															<?= lang("number_of_children", "num_of_child"); ?>
															<?php echo form_input('num_of_child', $guarantor? $guarantor->num_of_child:'', 'class="form-control" id="num_of_child"'); ?>
														</div>
														<div class="form-group">
															<?= lang("marital_status", "marital_status"); ?>
															<?php
															$marital_status[""] = "";
															$marital_status['married'] = "Married ";
															$marital_status['divorced'] = "Divorced";
															$marital_status['single'] = "Single";
															$marital_status['widow/Widower'] = "Widow/Widower";
															$marital_status['unknown'] = "unknown";
															echo form_dropdown('marital_status', $marital_status, $guarantor? $guarantor->status:'', 'class="form-control select" id="marital_status" placeholder="' . lang("select") . ' ' . lang("marital_status") . '" style="width:100%"');
															?>
														</div>
														<div class="form-group">
															<?= lang("date_of_birth", "dob"); ?>
															<?php echo form_input('dob', $guarantor? $this->erp->hrsd($guarantor->date_of_birth):'', 'class="form-control date" id="dob"'); ?>
														</div>
														<div class="form-group">
															<?= lang("phone_1", "phone_1"); ?>
															<input type="tel" name="phone_1" class="form-control" id="phone_1"  value="<?php echo $guarantor? $guarantor->phone1:'' ?>" />

														</div>
														<div class="form-group">
															<?= lang("spouse_mobile_phone", "sp_phone"); ?>
															<input type="tel" name="sp_phone" class="form-control" id="sp_phone" value="<?php echo $guarantor? $guarantor->spouse_phone:'' ?>" />
														</div>
														<div class="form-group">
															<?= lang("province", "province"); ?>
															<?php echo form_input('province', ($guarantor ? $guarantor->state : ''), 'class="form-control" id="province"  placeholder="' . lang("select_province") . '"');?>
														</div>
														<div class="form-group">
															<?= lang("communce", "communce"); ?>
															<?php echo form_input('communce', ($guarantor ? $guarantor->sangkat : ''), 'class="form-control" id="communce"  placeholder="' . lang("select_communce") . '"');?>
														</div>
														<div class="form-group">
															<?= lang("street", "street"); ?>
															<?php echo form_input('street', $guarantor? $guarantor->street:'', 'class="form-control" id="street"'); ?>
														</div>
														<div class="form-group">
															<?= lang("housing", "housing"); ?>
															<?php
															$housing[(isset($_POST['housing']) ? $_POST['housing'] : '')] = (isset($_POST['housing']) ? $_POST['housing'] : '');
															$housing["owner"] = "Owner";
															$housing["living_with_parent"] = "Living with parent";
															$housing["renting"] = "Renting";
															echo form_dropdown('housing', $housing, $guarantor? $guarantor->housing:'', 'class="form-control select" id="housing" placeholder="' . lang("select") . ' ' . lang("housing") . '" style="width:100%" ');
															?>
														</div>
													</div>
												</div>
											</div>
											</div>
										</div>
									</div>
									<div id="documents" style="display: none;" class="tab-pane fade">
								        <div class="modal-body">
								            <p>Please fill in the information below. The field labels marked with * are required input fields.</p>
											
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
												}
											}
											?>
											
											<div class="col-md-12">
												<div class="col-md-10">
													<div class="form-group">
														<label for="document">Current Address <?php echo ($dc_current_address_name)? '(' . count($dc_current_address_name) . ' file)':'' ?></label>
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
														<label for="document">Family Book <?php echo ($dc_family_book_name)? '(' . count($dc_family_book_name) . ' file)':'' ?></label>
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
														<label for="document">Gavernment ID <?php echo ($dc_gov_id_name)? '(' . count($dc_gov_id_name) . ' file)':'' ?></label>
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
														<label for="document">House Photo <?php echo ($dc_house_photo_name)? '(' . count($dc_house_photo_name) . ' file)':'' ?></label>
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
														<label for="document">Store Photo <?php echo ($dc_store_photo_name)? '(' . count($dc_store_photo_name) . ' file)':'' ?></label>
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
														<label for="document">Employment Certificate <?php echo ($dc_employment_certificate_name)? '(' . count($dc_employment_certificate_name) . ' file)':'' ?></label>
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
														<label for="document">Applicant Photo<?php echo ($dc_applicant_photo_name)? '(' . count($dc_applicant_photo_name) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="applicant_photo" id="document">
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
														<label for="document">Spouse Photo<?php echo ($dc_spouse_photo_name)? '(' . count($dc_spouse_photo_name) . ' file)':'' ?></label>
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
														<label for="document">Guarantors Photo<?php echo ($dc_guarantors_photo_name)? '(' . count($dc_guarantors_photo_name) . ' file)':'' ?></label>
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
														<label for="document">Birth Registration Letter<?php echo ($dc_birth_registration_letter_name)? '(' . count($dc_birth_registration_letter_name) . ' file)':'' ?></label>
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
														<label for="document">Passport<?php echo ($dc_passport_name)? '(' . count($dc_passport_name) . ' file)':'' ?></label>
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
														<label for="document">Marriage Certificate<?php echo ($dc_marriage_certificate_name)? '(' . count($dc_marriage_certificate_name) . ' file)':'' ?></label>
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
														<label for="document">Driver License<?php echo ($dc_driver_license_name)? '(' . count($dc_driver_license_name) . ' file)':'' ?></label>
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
														<label for="document">Working Contract<?php echo ($dc_working_contract_name)? '(' . count($dc_working_contract_name) . ' file)':'' ?></label>
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
														<label for="document">Invoice Salary<?php echo ($dc_invoice_salary)? '(' . count($dc_invoice_salary) . ' file)':'' ?></label>
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
														<label for="document">Business Certificate<?php echo ($dc_business_certificate)? '(' . count($dc_business_certificate) . ' file)':'' ?></label>
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
														<label for="document">Profit For The Last 3 Month<?php echo ($dc_profit_for_the_last_3_month)? '(' . count($dc_profit_for_the_last_3_month) . ' file)':'' ?></label>
														<input type="file" class="form-control file" data-show-preview="false" data-show-upload="false" 
															name="profit_for_the_last_3_month" id="document">
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
														<label for="document">Other Document <?php echo ($dc_other_dc_name)? '(' . count($dc_other_dc_name) . ' file)':'' ?></label>
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
									
									<div id="fields_check" style="display: none;" class="tab-pane fade">
								        <div class="modal-body">
								            
											<!-- Fields Check -->
											
											<p>ឯកសារយោងនៃអាសយដ្ឋានបច្ចុប្បន្ន</p>
											<div class="row">
												<div class="col-md-12 col-lg-12">
													<div class="col-md-3 col-sm-6">
														<input type="checkbox" name="fc_id_card" id="id_card" <?= ($field_check && $field_check->govid)? 'checked':''?> >អត្តសញ្ញាណប័ណ្ណ
													</div>	
													<div class="col-md-4 col-sm-6">
														<input type="checkbox" name="fc_family_book" id="family_book" <?= ($field_check && $field_check->family_book)? 'checked':''?> >សៀវភៅគ្រួសារ 
													</div>
													<div class="col-md-3 col-sm-6">
														<input type="checkbox" name="fc_staying_book" id="staying_book" <?= ($field_check && $field_check->place_book)? 'checked':''?> >សៀវភៅស្នាក់នៅ
													</div>
													<div class="col-md-3 col-sm-6">
														<input type="checkbox" name="fc_water_invoice" id="water_invoice" <?= ($field_check && $field_check->water_letter)? 'checked':''?> >វិក័យប័ត្រទឹក
													</div>
													<div class="col-md-4 col-sm-6">
													<input type="checkbox" name="fc_electricity_invoice" id="electricity_invoice" <?= ($field_check && $field_check->edc_letter)? 'checked':''?> >វិក័យប័ត្រប្រើប្រាស់អគ្គិសនី
													</div>
													<div class="col-md-4 col-sm-6">		
														<input type="checkbox" name="fc_check_property" id="check_property" <?= ($field_check && $field_check->property_check_letter)? 'checked':''?> >លិខិតបញ្ជាក់អចលនទ្រព្យ
													</div>
													<div class="col-md-7 col-sm-6">		
														<input type="checkbox" name="fc_check_landlord" id="check_landlord" <?= ($field_check && $field_check->claim_letter)? 'checked':''?> >លិខិតបញ្ជាក់ពីអាជ្ញាធរ(មេភូមិ, ចៅសង្កាត់/មេឈំ)
													</div>
													<div class="col-md-5">	
														<div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">	
														<input type="checkbox" name="fc_other" id="other" <?= ($field_check && $field_check->other)? 'checked':''?> >ផ្សេងៗ
														</div>
														<div class="col-md-8" style="padding-left: 0px; padding-right: 0px;">
															<?= form_input('fc_other_textbox',  ($field_check)? $field_check->other_note : '', 'class="form-control tip" id="other_textbox"') ?>
														</div>
													</div>
													<div>
															<div class="col-md-4"><p>អាសយដ្ឋាន​បច្ចុប្បន្នអ្នកស្នើសុំ</p></div>
															<div class="col-md-8"><?= form_input('fc_current_address',  ($field_check)? $field_check->requestor_curr_address: '', 'class="form-control tip" id="current_address"') ?></div>
													</div>
													<div>
															<div class="col-md-4"><p>លេខទូរសព្ទអ្នកស្នើសុំ</p></div>
															<div class="col-md-8"><?= form_input('fc_phone_number', ($field_check)? $field_check->requestor_phone: '', 'class="form-control tip" id="phone_number"') ?></div>
													</div>
												</div>
											</div>
											<div class="row">
											<br/>
											<?php
											if($field_check && $field_check->latitude && $field_check->longitude) {
											?>
												<iframe width="100%" height="350px" src = "https://maps.google.com/maps?q=<?=$field_check->latitude?>,<?=$field_check->longitude?>&hl=es;z=20&amp;output=embed"></iframe>
											<?php 
											} else {
											?>
												Latitude  : <span id="lat"></span><br/><input type="hidden" name="latitude_" id="latitude_" value="" />
												Longitude: <span id="long"></span><input type="hidden" name="longtitute_" id="longtitute_" value="" />
												<div id="map" style="width:100%; height:300px;"></div>
											<?php
											}
											?>
											<br/>
											</div>
											<div class="row">
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
											</div>
											<div class="row">
												<div class="col-md-12">
													<p>សេចក្តីសំរេច:</p>
													<div class="col-md-3">
														<input type="checkbox" name="fc_evaluate" id="evaluate" <?= ($field_check && $field_check->go_there)? 'checked':''?> >ចុះវាយតំលៃ
													</div>
													<div class="col-md-3">
														<input type="checkbox" name="fc_none_evaluate" id="none_evaluate" <?= ($field_check && $field_check->not_go_there)? 'checked':''?> >មិនចុះវាយតំលៃ
													</div>
												</div>
											</div>
										</div>
									
									<div id="comments" style="display: none;" class="tab-pane fade">
								        <div class="modal-body">
											<div class="comment-wrap">
												<div class="comment-list">
													
												</div>
												<div class="comment-text">
													<input type="hidden" class="comment-quote-id" value="<?php echo $id ?>">
													<div class="form-group">
														<label for="comment"><?php echo lang('comment') ?></label>
														<?php echo form_textarea('comment', '', 'class="form-control" id="comment"') ?>
													</div>
													<div class="form-group">
														<button class="btn btn-primary" id="comment-submit"><?php echo lang('comment') ?></button>
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
		$('.ch_services').trigger('ifChanged');
		refreshComment();
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
		
		$('.category').on('change', function(){
			var category_id = $(this).val();
			$.ajax({
				url: site.base_url + 'quotes/ajaxGetSubCategoryByCatID/'+category_id,
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
			$('.ch_services').each(function() {
				if($(this).is(':checked')) {
					total += $(this).attr('amount')-0;
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
		$('#interest_rate, #term_in_month, #grand_amount, #rate_type').on('change', function() {
			var interest = parseFloat($('#interest_rate').val());
			var term = Number($('#term_in_month').val());
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
		$('#qstatus').focus('change', function() {
			alert();
		});
	});
</script>

<!-- Filter Address --->
<script>
	$(document).ready(function() {
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
				}{
					localStorage.setItem('product', 'N/A');
				}
				
				var dealer = $('#qubiller').select2('data');
				localStorage.setItem('dealer', dealer.text);	
				var year=	$('#year').select2('data');
				if( year!=null){
					localStorage.setItem('year', year.text);
				}{
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
				var term_in_month=$('#term_in_month').val();
				localStorage.setItem('term_in_month', term_in_month);
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
				
				var link= $('<a href="Installment_Payment/payment_schedule_preview/'+leaseamount+'/'+rate_type+'/'+interest_rate+'/'+term_in_month+'" rel="lightbox" id="print_payment'+count_link+'" data-toggle="modal" data-target="#myModal"></a>');
			
						$("body").append(link);
						  $('#print_payment'+count_link).click();
			count_link++;
			
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
		
	
	
	
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDqn76Ds7-8TecI83wsTceWqK_WCIj1P5c&callback=initMap"></script>
<!-- end get lat long -->

