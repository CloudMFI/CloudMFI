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
					<li class=""><a href="#employee"><?= lang('employee') ?></a></li>
					<li class=""><a href="#guarantors"><?= lang('guarantors') ?></a></li>
					<li class=""><a href="#documents"><?= lang('documents') ?></a></li>
					<li class=""><a href="#fields_check"><?= lang('fields_check') ?></a></li>
					<li class=""><a href="#comments"><?= lang('Comments') ?></a></li>
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
										<div class="col-lg-12">
											
											<div class="col-md-6">
												<div class="form-group">
													<?= lang("dealer", "qubiller"); ?>
													<?php echo form_input('dealer', $applicant->company, 'class="form-control tip" id="dealer" data-bv-notempty="true"'); ?>
												</div>
												<div class="form-group">
													<?= lang("civility", "cus_civility"); ?>
													<?php echo form_input('civility', $applicant->civility, 'class="form-control tip" id="civility" data-bv-notempty="true"'); ?>
												</div>
												<div class="form-group">
													<?= lang("family_name", "cus_family_name"); ?>
													<?php echo form_input('cus_family_name', $applicant->family_name, 'class="form-control tip" id="cus_family_name" data-bv-notempty="true"'); ?>
												</div>
												<div class="form-group">
													<?= lang("first_name", "cus_first_name"); ?>
												   <?php echo form_input('cus_first_name', $applicant->name, 'class="form-control" id="cus_first_name" required="required" data-bv-notempty="true"'); ?>
												</div>
												<div class="form-group person" style="display:none;">
													<?= lang("nick_name", "cus_nick_name"); ?>
													<?php echo form_input('cus_nick_name', $applicant->nickname, 'class="form-control tip" id="cus_nick_name" data-bv-notempty="true"'); ?>
												</div>
												<div class="form-group">
													<?= lang("spouse_first_name", "cus_sp_fname"); ?>
													<?php echo form_input('cus_sp_fname', $applicant->spouse_name, 'class="form-control" id="cus_sp_fname" required="required" data-bv-notempty="true"'); ?>
												</div>
												<div class="form-group">
													<?= lang("gender", "cus_gender"); ?>
													<?php echo form_input('cus_gender', $applicant->gender, 'class="form-control" id="cus_gender" required="required" data-bv-notempty="true"'); ?>
												</div>
												<div class="form-group">
													<?= lang("place_of_birth", "cus_pob"); ?>
													<?php echo form_input('cus_pob', $applicant->address, 'class="form-control" id="cus_pob" data-bv-notempty="true"'); ?>
												</div>
												<div class="form-group">
													<?= lang("age", "cus_age"); ?>
													<?php echo form_input('cus_age', $applicant->age, 'class="form-control" id="cus_age" required="required" data-bv-notempty="true"'); ?>
												</div>
												<div class="form-group">
													<?= lang("whose_income", "cus_whose_income"); ?>
													<?php echo form_input('cus_whose_income', $applicant->whose_income, 'class="form-control" id="cus_whose_income" required="required" data-bv-notempty="true"'); ?>
												</div>
												<div class="form-group">
													<?= lang("nationality", "cus_nationality"); ?>
													<?php echo form_input('cus_nationality', $applicant->nationality, 'class="form-control" id="cus_nationality" required="required" data-bv-notempty="true"'); ?>
												</div>
												<div class="form-group">
													<?= lang("phone_2", "cus_phone_2"); ?>
													<?php echo form_input('cus_phone_2', $applicant->phone2, 'class="form-control" id="cus_phone_2" required="required" data-bv-notempty="true"'); ?>
												</div>
											</div>
											
											<div class="col-md-6">
												<div class="form-group">
													<?= lang("status", "qstatus"); ?>
													<?php echo form_input('status', $applicant->status, 'class="form-control" id="status" data-bv-notempty="true"'); ?>
												</div>
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
													<?php echo form_input('cus_first_name_other', $applicant->name_other, 'class="form-control" id="cus_first_name_other" required="required"'); ?>
												</div>
												<div class="form-group">
													<?= lang("spouse_family_name", "cus_sp_fam_name"); ?>
													<?php echo form_input('cus_sp_fam_name', $applicant->spouse_family_name, 'class="form-control" id="cus_sp_fam_name"'); ?>
												</div>
												<div class="form-group">
													<?= lang("number_of_children", "cus_num_of_child"); ?>
													<?php echo form_input('cus_num_of_child', $applicant->num_of_child, 'class="form-control" id="cus_num_of_child"'); ?>
												</div>
												<div class="form-group">
													<?= lang("marital_status", "cus_marital_status"); ?>
													<?php echo form_input('cus_marital_status', $applicant->status, 'class="form-control" id="cus_marital_status"'); ?>
												</div>
												<div class="form-group">
													<?= lang("date_of_birth", "cus_dob"); ?>
													<?php echo form_input('cus_dob', $this->erp->fsd($applicant->date_of_birth), 'class="form-control date" id="cus_dob"'); ?>
												</div>
												<div class="form-group">
													<?= lang("income_combination", "cus_inc_comb"); ?>
													<?php
													if($applicant->income_combination == 1){
														$in_combi = 'Yes';
													}else{
														$in_combi = 'No';
													}
													echo form_input('cus_inc_comb', $in_combi, 'class="form-control date" id="cus_inc_comb"')
													?>
												</div>
												<div class="form-group" style="display:none;">
													<?= lang("black_list_customer", "cus_black_list"); ?>
													<?php
													if($applicant->black_list == 1){
														$black_list = 'Yes';
													}else{
														$black_list = 'No';
													}
													echo form_input('cus_black_list', $black_list, 'class="form-control date" id="cus_black_list"');
													?>
												</div>
												<div class="form-group">
													<?= lang("phone_1", "cus_phone_1"); ?>
													<?php echo form_input('cus_phone_1', $applicant->phone1, 'class="form-control" id="cus_phone_1"'); ?>
												</div>
												<div class="form-group">
													<?= lang("spouse_mobile_phone", "cus_sp_phone"); ?>
													<?php echo form_input('cus_sp_phone', $applicant->spouse_phone, 'class="form-control" id="cus_sp_phone"'); ?>
												</div>
											</div>
				
											<div class="col-sm-12">
												<div class="panel panel-primary">
													<div class="panel-heading"><?= lang('address') ?></div>
													<div class="panel-body" style="padding: 5px;">
														<div class="col-md-6">
															<div class="form-group">
																<?= lang("house_no", "cus_house_no"); ?>
																<?php echo form_input('cus_house_no', $applicant->house_no, 'class="form-control" id="cus_house_no"'); ?>
															</div>
															<div class="form-group">
																<?= lang("housing", "cus_housing"); ?>
																<?php
																echo form_input('cus_housing', $applicant->housing, 'class="form-control" id="cus_housing" style="width:100%" data-bv-notempty="true"');
																?>
															</div>
															<div class="form-group">
																<?= lang("district", "cus_district"); ?>
																<?php
																echo form_input('cus_district', $applicant->dname, 'class="form-control" id="cus_district" style="width:100%" data-bv-notempty="true"');
																?>
															</div>
															<div class="form-group">
																<?= lang("province", "cus_province"); ?>
																<?php
																echo form_input('cus_province', $applicant->pname, 'class="form-control" id="cus_province" style="width:100%" data-bv-notempty="true"');
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
														
														<div class="col-md-6">
															<div class="form-group">
																<?= lang("street", "cus_street"); ?>
																<?php echo form_input('cus_street', $applicant->street, 'class="form-control" id="cus_street"'); ?>
															</div>
															<div class="form-group">
																<?= lang("village", "cus_village"); ?>
																<?php
																echo form_input('cus_village', $applicant->vname, 'class="form-control" id="cus_village" style="width:100%" data-bv-notempty="true"');
																?>
															</div>
															<div class="form-group">
																<?= lang("communce", "cus_communce"); ?>
																<?php
																echo form_input('cus_communce', $applicant->cname, 'class="form-control" id="cus_communce" style="width:100%" data-bv-notempty="true"');
																?>
															</div>
															<div class="form-group">
																<?= lang("country", "cus_country"); ?>
																<?php
																echo form_input('cus_country', $applicant->coname, 'class="form-control" id="cus_country" style="width:100%" data-bv-notempty="true"');
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
															echo form_input('category_id', $product->caname, 'class="form-control category" required');
															?>
														</div>
													</div>
													
													<div class="col-md-4">
														<div class="form-group">
															<?php echo lang('sub_category', 'sub_category') ?>
															<?php
															echo form_input('sub_category', $product->subname, 'class="form-control sub_category" id="sub_category"  placeholder="' . lang("select_category_to_load") . '"');
															?>
														</div>
													</div>
													
													<div class="col-md-4">
														<div class="form-group">
															<?php echo lang('product', 'product') ?>
															<?php
															echo form_input('product_id', $product->pname, 'class="form-control" id="product_id" ');
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
															foreach ($variants as $variant) {
																$vari[$variant->id] = $variant->name;
															}
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
															<?= form_input('distance', (isset($product->distance_mile) ? $product->distance_mile : ''), 'class="form-control" id="distance" required="required"'); ?>
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
															<input type="checkbox" class="form-control ch_services" amount="<?php echo $service->amount ?>" name="ch_services[]" value="1" <?php echo set_checkbox('ch_services[]', '1', $quote_service[$k]->quote_id==$id?TRUE:FALSE); ?>>
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
															<?php echo form_input('position', $quote_employee->position, 'class="form-control input-tip"  id="position"'); ?>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<?= lang("employment_status", "employment_status"); ?>
															<?php
																$emp_status = array('active' => 'Active');
																echo form_dropdown('employment_status', $emp_status, $quote_employee->emp_status, 'id="employment_status" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("employment_status") . '"  style="width:100%;" ');
																?>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<?= lang("employment_industry", "employment_industry"); ?>
															<?php
																$emp_industrial = array('manufacturing' => 'Manufacturing');
																echo form_dropdown('employment_industry', $emp_industrial, $quote_employee->emp_industry, 'id="employment_industry" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("employment_industry") . '"  style="width:100%;" ');
																?>
														</div>
													</div>

													<div class="col-md-6">
														<div class="form-group">
															<?= lang("seniorities_level", "seniorities_level"); ?>
															<?php
																$seniorities_level = array('staff' => 'Staff');
																echo form_dropdown('seniorities_level', $seniorities_level, $quote_employee->senior_level, 'id="seniorities_level" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("seniorities_level") . '" style="width:100%;" ');
																?>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<?= lang("work_place_name", "work_place_name"); ?>
															<?php echo form_input('work_place_name', $quote_employee->workplace_name, 'class="form-control input-tip"  id="work_place_name"'); ?>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<?= lang("work_phone", "work_phone"); ?>
															<?php echo form_input('work_phone', $quote_employee->work_phone, 'class="form-control input-tip" id="work_phone"'); ?>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<input type="checkbox" id="allow_call_to_work_place" class="form-control" name="allow_call_to_work_place" value="1" <?php echo set_checkbox('allow_call_to_work_place', '1', $quote_employee->allow_call_to_work_place==1?TRUE:FALSE); ?>>
															<?= lang("allow_call_to_work_place", "allow_call_to_work_place"); ?>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<b style="padding-bottom:5px; display:block;"><?= lang("time_at_this_address"); ?></b>
															<?php echo form_input('emp_years', $quote_employee->years, 'class="form-control" id="emp_years" placeholder="' . lang("years") . '" style="display:inline !important; width:35% !important;"'); ?>
															<?= lang("years", "emp_years"); ?>
															<?php echo form_input('emp_months', $quote_employee->months, 'class="form-control" id="emp_months" placeholder="' . lang("months") . '" style="display:inline !important; width:35% !important;"'); ?>
															<?= lang("months", "emp_months"); ?>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<?= lang("basic_salary", "basic_salary"); ?>
															<?php echo form_input('basic_salary', $quote_employee->basic_salary, 'class="form-control input-tip" id="basic_salary"'); ?>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<?= lang("allowance_etc", "allowance_etc"); ?>
															<?php echo form_input('allowance_etc', $quote_employee->allowance_etc, 'class="form-control input-tip" id="allowance_etc"'); ?>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<?= lang("business_expense", "business_expense"); ?>
															<?php echo form_input('business_expense', $quote_employee->business_expense, 'class="form-control input-tip" id="business_expense" style="display:inline !important; width:80% !important;"'); ?>
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
															<?= lang("house_no", "emp_house_no")?>
															<?php echo form_input('emp_house_no', $quote_employee->house_no, 'class="form-control input-tip" id="emp_house_no"'); ?>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<?= lang("street", "emp_street")?>
															<?php echo form_input('emp_street', $quote_employee->street, 'class="form-control input-tip" id="emp_street"'); ?>
														</div>
													</div>
													
													
													<div class="col-md-6">
														<div class="form-group">
															<?= lang("country", "emp_country"); ?>
															<?php
																$emp_country = array('cambodia' => 'Cambodia');
																echo form_dropdown('emp_country', $emp_country, $quote_employee->country, 'id="emp_country" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("country") . '" style="width:100%;" ');
																?>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<?= lang("province", "emp_province"); ?>
															<?php
																$emp_province = array('phnom_penh' => 'Phnom Penh');
																echo form_dropdown('emp_province', $emp_province, $quote_employee->province, 'id="emp_province" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("province") . '"  style="width:100%;" ');
																?>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<?= lang("district", "emp_district"); ?>
															<?php
																$emp_district = array('russey_keo' => 'Russey Keo');
																echo form_dropdown('emp_district', $emp_district, $quote_employee->district, 'id="emp_district" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("district") . '"  style="width:100%;" ');
															?>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<?= lang("commune", "emp_communce"); ?>
															<?php
																$emp_communce = array('russey_keo' => 'Russey Keo');
																echo form_dropdown('emp_communce', $emp_communce, $quote_employee->communce, 'id="emp_communce" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("commune") . '"  style="width:100%;" ');
																?>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<?= lang("village", "emp_village"); ?>
															<?php
																$emp_village = array('rom_doul' => 'Rom Doul');
																echo form_dropdown('emp_village', $emp_village, $quote_employee->village, 'id="emp_village" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("village") . '" style="width:100%;" ');
																?>
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
												
												if($guarantor->civility == 'male' || $guarantor->civility == 'mr'){
													$civility_status = $civility['mr'];
												}elseif($guarantor->civility == 'female' || $guarantor->civility == 'mrs'){
													$civility_status = $civility['mrs'];
												}
												
												echo form_dropdown('civility', $civility, $civility_status, 'class="form-control select" id="civility" placeholder="' . lang("select") . ' ' . lang("civility") . '" style="width:100%" ')
												?>
											</div>
											<div class="form-group">
												<?= lang("family_name", "family_name"); ?>
												<?php echo form_input('family_name', $guarantor->family_name, 'class="form-control tip" id="family_name" '); ?>
											</div>
											<div class="form-group">
												<?= lang("first_name", "first_name"); ?>
											   <?php echo form_input('first_name', $guarantor->name, 'class="form-control" id="first_name" '); ?>
											</div>
											<div class="form-group person">
												<?= lang("nick_name", "nick_name"); ?>
												<?php echo form_input('nick_name', $guarantor->nickname, 'class="form-control tip" id="nick_name"'); ?>
											</div>
											<div class="form-group">
												<?= lang("spouse_first_name", "sp_fname"); ?>
												<?php echo form_input('sp_fname', $guarantor->spouse_family_name, 'class="form-control" id="sp_fname" '); ?>
											</div>
											<div class="form-group">
												<?= lang("gender", "gender"); ?>
												<?php
												$gender[""] = "";
												$gender['male'] = "Male";
												$gender['female'] = "Female";
												echo form_dropdown('gender', $gender, $guarantor->gender, 'class="form-control select" id="gender" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%" ')
												?>
											</div>
											<div class="form-group">
												<?= lang("place_of_birth", "pob"); ?>
												<?php echo form_input('pob', $guarantor->address, 'class="form-control" id="pob"'); ?>
											</div>
											<div class="form-group">
												<?= lang("nationality", "nationality"); ?>
												<?php
												$nationality[""] = "";
												$nationality['cam'] = "Cambodia";
												$nationality['tha'] = "Thailand";
												echo form_dropdown('nationality', $nationality, $guarantor->nationality, 'class="form-control select" id="nationality" placeholder="' . lang("select") . ' ' . lang("nationality") . '" style="width:100%"')
												?>
											</div>
											<div class="form-group">
												<?= lang("phone_2", "phone_2"); ?>
												<input type="tel" name="phone_2" class="form-control" id="phone_2" value="<?=$guarantor->phone2?>"/>
											</div>
											<div class="form-group">
												<?= lang("house_no", "house_no"); ?>
												<?php echo form_input('house_no', $guarantor->phone2, 'class="form-control" id="house_no"'); ?>
											</div>
											<div class="form-group">
												<?= lang("housing", "housing"); ?>
												<?php
												$housing[""] = "";
												$housing["0"] = "105";
												echo form_dropdown('housing', $housing, $guarantor->housing, 'class="form-control select" id="housing" placeholder="' . lang("select") . ' ' . lang("housing") . '" style="width:100%" ');
												?>
											</div>
											<div class="form-group">
												<?= lang("district", "district"); ?>
												<?php
												$district[""] = "";
												$district["0"] = "Tek Thlar";
												echo form_dropdown('district', $district, $guarantor->district, 'class="form-control select" id="district" placeholder="' . lang("select") . ' ' . lang("district") . '" style="width:100%"');
												?>
											</div>
											<div class="form-group">
												<?= lang("province", "province"); ?>
												<?php
												$province[""] = "";
												$province["0"] = "Kandal";
												echo form_dropdown('province', $province, $guarantor->state, 'class="form-control select" id="province" placeholder="' . lang("select") . ' ' . lang("province") . '" style="width:100%" ');
												?>
											</div>
											<div class="form-group">
												<b style="padding-bottom:5px; display:block;"><?= lang("time_at_this_address"); ?></b>
												<?php echo form_input('years', $guarantor->years, 'class="form-control" id="years" placeholder="' . lang("years") . '" style="display:inline !important; width:35% !important;"'); ?>
												<?= lang("years", "years"); ?>
												<?php echo form_input('months', $guarantor->months, 'class="form-control" id="months" placeholder="' . lang("months") . '" style="display:inline !important; width:35% !important;"'); ?>
												<?= lang("months", "months"); ?>
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="form-group">
												<?= lang("government_id", "gov_id"); ?>
												<?php echo form_input('gov_id', $guarantor->gov_id, 'class="form-control" id="gov_id" '); ?>
											</div>
											<div class="form-group">
												<?= lang("family_name_other", "family_name_other"); ?>
												<?php echo form_input('family_name_other', $guarantor->family_name_other, 'class="form-control" id="family_name_other" '); ?>
											</div>
											<div class="form-group">
												<?= lang("first_name_other", "first_name_other"); ?>
												<?php echo form_input('first_name_other', $guarantor->name_other, 'class="form-control" id="first_name_other" '); ?>
											</div>
											<div class="form-group">
												<?= lang("spouse_family_name", "sp_fam_name"); ?>
												<?php echo form_input('sp_fam_name', $guarantor->spouse_family_name, 'class="form-control" id="sp_fam_name"'); ?>
											</div>
											<div class="form-group">
												<?= lang("number_of_children", "num_of_child"); ?>
												<?php echo form_input('num_of_child', $guarantor->num_of_child, 'class="form-control" id="num_of_child"'); ?>
											</div>
											<div class="form-group">
												<?= lang("marital_status", "marital_status"); ?>
												<?php
												$marital_status[""] = "";
												$marital_status['single'] = "single";
												$marital_status['marrited'] = "marrited";
												echo form_dropdown('marital_status', $marital_status, isset($guarantor->status)?$guarantor->status:'', 'class="form-control select" id="marital_status" placeholder="' . lang("select") . ' ' . lang("marital_status") . '" style="width:100%"');
												?>
											</div>
											<div class="form-group">
												<?= lang("date_of_birth", "dob"); ?>
												<?php echo form_input('dob', $this->erp->hrsd($guarantor->date_of_birth), 'class="form-control date" id="dob"'); ?>
											</div>
											<div class="form-group">
												<?= lang("phone_1", "phone_1"); ?>
												<input type="tel" name="phone_1" class="form-control" id="phone_1"  value="<?php echo $guarantor->phone1 ?>" />

											</div>
											<div class="form-group">
												<?= lang("spouse_mobile_phone", "sp_phone"); ?>
												<input type="tel" name="sp_phone" class="form-control" id="sp_phone" value="<?php echo $guarantor->spouse_phone ?>" />
											</div>
											<div class="form-group">
												<?= lang("street", "street"); ?>
												<?php echo form_input('street', $guarantor->street, 'class="form-control" id="street"'); ?>
											</div>
											<div class="form-group">
												<?= lang("village", "village"); ?>
												<?php
												$village[""] = "";
												$village["0"] = "PCP";
												echo form_dropdown('village', $village, isset($guarantor->village)?$guarantor->village:'', 'class="form-control select" id="village" placeholder="' . lang("select") . ' ' . lang("village") . '" style="width:100%" ');
												?>
											</div>
											<div class="form-group">
												<?= lang("communce", "communce"); ?>
												<?php
												$communce[""] = "";
												$communce["0"] = "Sen Sok";
												echo form_dropdown('communce', $communce, isset($guarantor->sangkat)?$guarantor->sangkat:'', 'class="form-control select" id="communce" placeholder="' . lang("select") . ' ' . lang("communce") . '" style="width:100%" ');
												?>
											</div>
											<div class="form-group">
												<?= lang("country", "country"); ?>
												<?php
												$country[""] = "";
												$country["Cam"] = "Cambodia";
												echo form_dropdown('country', $country, isset($guarantor->country)?$guarantor->country:'', 'class="form-control select" id="country" placeholder="' . lang("select") . ' ' . lang("country") . '" style="width:100%" ');
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
								$dc_other_dc = '#';
								$dc_other_dc_name = '';
								$base_dc_path = base_url() . 'assets/uploads/documents/';
								
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
									if($dc->type == 'other_document'){
										$dc_other_dc = $base_dc_path . $dc->name;
										$dc_other_dc_name = $dc->name;
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
											<input type="checkbox" name="fc_id_card" id="id_card" <?=$field_check->govid? 'checked':''?> >អត្តសញ្ញាណប័ណ្ណ
										</div>	
										<div class="col-md-4 col-sm-6">
											<input type="checkbox" name="fc_family_book" id="family_book" <?=$field_check->family_book? 'checked':''?> >សៀវភៅគ្រួសារ 
										</div>
										<div class="col-md-3 col-sm-6">
											<input type="checkbox" name="fc_staying_book" id="staying_book" <?=$field_check->place_book? 'checked':''?> >សៀវភៅស្នាក់នៅ
										</div>
										<div class="col-md-3 col-sm-6">
											<input type="checkbox" name="fc_water_invoice" id="water_invoice" <?=$field_check->water_letter? 'checked':''?> >វិក័យប័ត្រទឹក
										</div>
										<div class="col-md-4 col-sm-6">
										<input type="checkbox" name="fc_electricity_invoice" id="electricity_invoice" <?=$field_check->edc_letter? 'checked':''?> >វិក័យប័ត្រប្រើប្រាស់អគ្គិសនី
										</div>
										<div class="col-md-4 col-sm-6">		
											<input type="checkbox" name="fc_check_property" id="check_property" <?=$field_check->property_check_letter? 'checked':''?> >លិខិតបញ្ជាក់អចលនទ្រព្យ
										</div>
										<div class="col-md-7 col-sm-6">		
											<input type="checkbox" name="fc_check_landlord" id="check_landlord" <?=$field_check->claim_letter? 'checked':''?> >លិខិតបញ្ជាក់ពីអាជ្ញាធរ(មេភូមិ, ចៅសង្កាត់/មេឈំ)
										</div>
										<div class="col-md-5">	
											<div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">	
											<input type="checkbox" name="fc_other" id="other" <?=$field_check->other? 'checked':''?> >ផ្សេងៗ
											</div>
											<div class="col-md-8" style="padding-left: 0px; padding-right: 0px;">
												<?= form_input('fc_other_textbox', $field_check->other_note, 'class="form-control tip" id="other_textbox"') ?>
											</div>
										</div>
										<div>
												<div class="col-md-4"><p>អាសយដ្ឋាន​បច្ចុប្បន្នអ្នកស្នើសុំ</p></div>
												<div class="col-md-8"><?= form_input('fc_current_address', $field_check->requestor_curr_address, 'class="form-control tip" id="current_address"') ?></div>
										</div>
										<div>
												<div class="col-md-4"><p>លេខទូរសព្ទអ្នកស្នើសុំ</p></div>
												<div class="col-md-8"><?= form_input('fc_phone_number', $field_check->requestor_phone, 'class="form-control tip" id="phone_number"') ?></div>
										</div>
									</div>
								</div>
								<div class="row">
								<br/>
								
								<iframe width="100%" height="350px" src = "https://maps.google.com/maps?q=<?=$field_check->latitude?>,<?=$field_check->longitude?>&hl=es;z=20&amp;output=embed"></iframe>
								
								<br/>
								
								</div>
								<div class="row">
									<div class="col-md-12 col-lg-12">
										<p>បរិយាយកាសការងារ</p>
											<div class="col-md-2">
												<input type="checkbox" name="fc_business1" id="business1" <?=$field_check->business1? 'checked':''?> >ជំនួញ
											</div>
											<div class="col-md-2">
											<input type="checkbox" name="fc_company1" id="company1" <?=$field_check->company1? 'checked':''?> >ក្រុមហ៊ុន
											</div>
											<div class="col-md-2">
											<input type="checkbox" name="fc_other1" id="other1" <?=$field_check->other1? 'checked':''?> >ផ្សេងៗ
											</div>
											<div>
												<div class="col-md-2"><p>ឈ្មោះ</p></div>
												<div class="col-md-3"><?= form_input('fc_name',$field_check->name, 'class="form-control tip" id="name"') ?></div>
											</div>
										</div>	
										<div class="col-md-12 col-lg-12">
											<div class="col-md-2">
												<input type="checkbox" name="fc_business2" id="business2" <?=$field_check->business2? 'checked':''?> >ជំនួញ
											</div>
											<div class="col-md-2">
											<input type="checkbox" name="fc_company2" id="company2" <?=$field_check->company2? 'checked':''?> >ក្រុមហ៊ុន
											</div>
											<div class="col-md-2">
											<input type="checkbox" name="fc_other2" id="other2" <?=$field_check->other2? 'checked':''?> >ផ្សេងៗ
											</div>
											<div>
												<div class="col-md-2"><p>លេខទូរសព្ទ</p></div>
												<div class="col-md-3"><?= form_input('fc_phone', $field_check->phone, 'class="form-control tip" id="name"') ?></div>
											</div>
										</div>	
										<div class="col-md-12 col-lg-12">
											<div class="col-md-2">
												<input type="checkbox" name="fc_business3" id="business3" <?=$field_check->business3? 'checked':''?> >ជំនួញ
											</div>
											<div class="col-md-2">
											<input type="checkbox" name="fc_company3" id="company3" <?=$field_check->company3? 'checked':''?> >ក្រុមហ៊ុន
											</div>
											<div class="col-md-2">
											<input type="checkbox" name="fc_other3" id="other3" <?=$field_check->other3? 'checked':''?> >ផ្សេងៗ
											</div>
											<div>
												<div class="col-md-2"><p>អាសយដ្ឋាន​បច្ចុប្បន្ន</p></div>
												<div class="col-md-3"><?= form_input('fc_address', $field_check->address, 'class="form-control tip" id="current_address"') ?></div>
											</div>
										</div>	
										<div class="col-md-12 col-lg-12">
											<div class="col-md-2">
												<input type="checkbox" name="fc_business4" id="business4" <?=$field_check->business4? 'checked':''?> >ជំនួញ
											</div>
											<div class="col-md-2">
											<input type="checkbox" name="fc_company4" id="company4" <?=$field_check->company4? 'checked':''?> >ក្រុមហ៊ុន
											</div>
											<div class="col-md-2">
											<input type="checkbox" name="fc_other4" id="other4" <?=$field_check->other4? 'checked':''?> >ផ្សេងៗ
											</div>
											<div class="col-md-12" style="padding-top:10px;">
												<div class="col-md-3"><p>ម៉ោងធ្វើកា​រពី</p></div>
												<div class="col-md-2"><?= form_input('fc_start_time', $field_check->start_work, 'class="form-control tip" id="start_time"') ?></div>
												<div class="col-md-1"><p>ដល់</p></div>
												<div class="col-md-2"><?= form_input('fc_end_time', $field_check->end_work, 'class="form-control tip" id="end_time"') ?></div>
											</div>
										</div>
										<div class="col-md-12 col-lg-12">
											<div class="col-md-12" style="padding-top:10px;">
												<div class="col-md-3"><p>ធ្វើការប៉ុន្មានថ្ងៃក្នុងមួយសប្តាហ៍?</p></div>
												<div class="col-md-9"><?= form_input('fc_start_time', $field_check->hours, 'class="form-control tip" id="start_time"') ?></div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p>សេចក្តីសំរេច:</p>
										<div class="col-md-3">
											<input type="checkbox" name="fc_evaluate" id="evaluate" <?=$field_check->go_there? 'checked':''?> >ចុះវាយតំលៃ
										</div>
										<div class="col-md-3">
											<input type="checkbox" name="fc_none_evaluate" id="none_evaluate" <?=$field_check->not_go_there? 'checked':''?> >មិនចុះវាយតំលៃ
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
		<div></div>
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
		$("#advance_percentage, .ch_services").trigger('change');
		$(".category").trigger('change');
		$(".sub_category").trigger('change');
		refreshComment();
	});
	$(document).ready(function() {
		// $("input:text").attr('readonly','readonly');
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
			}
		});
		$('#interest_rate, #term_in_month, #grand_amount').on('change', function() {
			var interest = parseFloat($('#interest_rate').val());
			var term = Number($('#term_in_month').val());
			var lease_amount = parseFloat($('#grand_amount').val());
			if(interest > 0 && term > 0 && lease_amount > 0) {
				var principle = lease_amount/term;
				var interest_rate = lease_amount*(interest);
				var installment_amount = principle + interest_rate;
				$('#installment_amount').val(formatMoney(installment_amount));
			} else {
				$('#installment_amount').val(formatMoney(0));
			}		
		});
	});
</script>
