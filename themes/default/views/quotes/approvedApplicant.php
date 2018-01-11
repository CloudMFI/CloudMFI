<?php 
	 //$this->erp->print_arrays($inv);
	  ///$this->erp->print_arrays($user_id);
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
	 .table-responsive > table > tbody > tr{
	  height:35px;
	 }
	 .title-bold{
	  font-weight:bold; 
	  padding-right:5px;
	 }
	 
	 .table-responsive2 > table > tbody > tr{
	  height:25px;
	 }
	 .title-bold{
	  font-weight:bold; 
	  padding-right:5px;
	 }
	 .uppercase { text-transform: capitalize;} ;
</style>
<script type="text/javascript">
    var count = 1, an = 1, DT = <?= $Settings->default_tax_rate ?>, allow_discount = <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? 1 : 0; ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, shipping = 0,
        tax_rates = <?php echo json_encode($tax_rates); ?>;
    var audio_success = new Audio('<?=$assets?>sounds/sound2.mp3');
    var audio_error = new Audio('<?=$assets?>sounds/sound3.mp3');
    $(document).ready(function () {

		
		$('input:text').attr("readonly",true);
		$('input:checkbox').attr("readonly",true);
		$('.select').attr("disabled",true);
		$('.phone_disabled').attr("disabled",true);
		$('#st_inst_date').attr('readonly', false);
		$('#app_date').attr('readonly', false);
		
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
	
	$(window).load(function() {
		<?php if($inv->status == 'rejected') { ?>
			$('input:text').attr("readonly",true);
			$('input:checkbox').attr("readonly",true);;
			$('.select').attr("disabled",true);			
			$('.phone_disabled').attr("disabled",true);
			$('#st_inst_date').attr('readonly', false);
			$('#app_date').attr('readonly', false);
			$('.number_only').attr("disabled",true);			
			$('.form-control').attr("disabled",true);
			$('.check').attr("disabled",true);
			$('input:submit').css("display","none");
		<?php } else if($inv->status == 'approved' || $inv->status == 'activated' || $inv->status == 'completed'){ ?>	
			$('.form-control').attr("disabled",true);
			$('input:submit').css("display","none");
		<?php } ?>
		
		//marital_status
		<?php if($applicant->status != 'married') { ?>
			$('#spname').hide();
			$('#spphone').hide();
			$('#spchild').hide();
			$('#whoseincome').hide();
			$('#incomecombine').hide();
			$('#sp_gender').hide();
			$('#sp_status').hide();
			$('#sp_date').hide();
		<?php } ?>
	});
</script>


<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-edit"></i><?= lang('quotation_details'); ?></h2>
    </div>
    <div class="box-content" style="padding-top: 0px;">
        <div class="row">
		<style>
		.top_bar div{ color:white;}
		</style>
            <div class="col-lg-12">
					<div class="row top_bar" style="background-color: #428bca; margin-left: -20px; margin-right: -20px; padding-bottom: 5px; padding-top: 5px;">	
						<div class="col-md-3"><?=lang('applicant');?><span style="margin-left:20px;">: <?php echo $applicant->family_name.' '.$applicant->name;?></span></div>
						<div class="col-md-3"><?=lang('reference');?><span style="margin-left:45px;">:<?php echo ' '.$quote->reference_no;?></span></div>
						<div class="col-md-3"><?=lang('start_date');?><span style="margin-left:10px;">: N/A</span></div>
						<div class="col-md-3"><?=lang('end_date');?><span style="margin-left:25px;">: N/A</span></div>
						<div class="col-md-3"><?=lang('penalty_day');?><span style="margin-left:4px;">: <?php echo $settings->penalty_days;?></div>
						<div class="col-md-3"><?=lang('penalty_amount');?><span style="margin-left:7px;">: <?php echo $settings->penalty_amount;?></span></div>
						<div class="col-md-3"><?=lang('guarantor');?><span style="margin-left:12px;">:<?php 
					
						echo $guarantor ? ($guarantor->family_name.' '.$guarantor->name) : ''; 
						
						?></span></div>
						<div class="col-md-3"><?=lang('outstanding');?><span style="margin-left:5px;">: N/A</span></div>
						<div class="col-md-3"><?=lang('status');?><span style="margin-left:40px;">: <?php echo ucwords(' '.$quote->status); ?></span></div>
						<div class="col-md-3"><span style="font-weight:bold;"><?=lang('credit_scoring');?></span><span style="margin-left:17px;">: N/A</span></div>
					</div>
				
				
				<div class="col-md-12" style="margin-top: 20px;">
							<ul id="dbTab" class="nav nav-tabs">
								<li class=""><a href="#applicants"><?= lang('applicants') ?></a></li>								
								<li class=""><a href="#financial_products"><?= lang('financial_products') ?></a></li>
								<li class="" id="credit_assessment_tap"><a href="#credit_assessment"><?= lang('credit_assessment') ?></a></li>
								<li class="" id="join_lease_tap"><a href="#join_lease"><?= lang('join_lease') ?></a></li>
								<li class=""><a href="#employee"><?= lang('employee') ?></a></li>
								<li class=""><a href="#guarantors"><?= lang('guarantors') ?></a></li>
								<li class="" id="collateral_tap"><a href="#collateral"><?= lang('collateral') ?></a></li>
								<li class=""><a href="#documents"><?= lang('documents') ?></a></li>
								<!--<li class=""><a href="#fields_check"><?= lang('fields_check') ?></a></li>-->
								<li class=""><a href="#comments"><?= lang('comments') ?></a></li>
							</ul>
							<div class="tab-content">
								<?php
								$attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class' => 'edit-qu-form');
								echo form_open_multipart("quotes/approvedApplicant/" . $id, $attrib)
								?>
								<div id="applicants" class="tab-pane fade in">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<div class="row">
													<div class="col-lg-12">
													<?php if ($GP['advance-approve'] || $GP['quotes-approve'] || $GP['quotes-pending_for_PO'] || $GP['quotes-rejected'] || $this->Admin || $this->Owner){ ?>
														<div class="row">															
															<div class="col-md-6">
																<div class="form-group">
																	<?= lang("approved_date", "app_date"); ?>
																	<?php echo form_input('app_date', (isset($_POST['app_date']) ? $_POST['app_date'] : date('d/m/Y')), 'class="form-control input-tip date" id="app_date"'); ?>
																</div>
															</div>
															<div class="col-md-6" <?= (($inv->mfi != '1')? 'style="display:none;"' : ''); ?> >
																<div class="form-group">
																	<?= lang("start_installment_date", "st_inst_date"); ?>
																	<?php //echo form_input('st_inst_date', ($applicant ? $this->erp->hrsd($applicant->installment_date) : ''), 'class="form-control input-tip date"  id="st_inst_date"'); ?>
																	<?php echo form_input('st_inst_date', ($applicant ? $this->erp->hrsd($applicant->installment_date) : ''), 'class="form-control input-tip date"  id="st_inst_date"'); ?>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group">
																	<?= lang("status", "qstatus"); ?>
																	<?php
																	$status_q = array('applicant' => lang('applicant'), 'rejected' => lang('rejected'), 'approved' => lang('approved'), 'approved_condition' => lang('approved_condition'), 'pending_po' => lang('pending_po'));
																	/*if(isset($sale->sale_status) && $sale->sale_status == 'approved'){
																		unset($status_q['applicant']);
																	}*/
																	if(isset($quote->status) && $quote->status == 'applicant'){
																		unset($status_q['activated']);
																	}
																	if($setting->adv_app_amount < $product->total && !$GP['advance-approve'] && !$this->Admin && !$this->Owner) {
																		unset($status_q['approved']);
																	}
																	if($cate_detail->mfi == 1) {
																		unset($status_q['approved_condition']);
																		unset($status_q['pending_po']);
																	}
																		echo form_dropdown('status', $status_q, $inv->status, 'id="qstatus" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("status") . '" required="required" class="form-control input-tip " style="width:100%;"');
																	
																	?>
																</div>
															</div>
															<div class="col-sm-6">
																<div class="form-group">
																	<label><?= lang("approve_by"); ?></label>
																	<?php
																	$us[""] = "";
																	if(is_array(isset($users) ?$users  : (''))){
																	foreach ($users as $user) {
																		$us[$user->id] = $user->first_name . " " . $user->last_name;
																	}}
																	echo form_dropdown('approve_by', $us,  $sale->approved_by ? $sale->approved_by : $user_id, 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("user") . '"');
																	?>
																</div>
															</div>
														</div>
													<?php } ?>
														<!----->
														<!---Reject_Reason--->
														<div class="col-sm-12" id="reject_reason">
															<div class="panel panel-primary">
																<div class="panel-heading"><?= lang('reject_reason') ?></div>
																<div class="panel-body" style="padding:10px;">
																	
																	<!---->
																	<?php
																	if(array($reject_rs)) {
																	for($i=0;$i<sizeof($reject_rs);$i++) {
																	?>
																	<div class="col-sm-10">
																		<div class="col-md-1">
																			<div class="form-group">
																				<input type="checkbox" name="cus_reject[]" value="<?php echo $reject_rs[$i]->id ?>" <?php echo set_checkbox('cus_reject[]', '1', isset($quote_reject[$i]->id)==$id?TRUE:FALSE); ?>>
																			</div>
																		</div>
																		
																		<div class="col-md-8">
																			<div class="form-group">
																				<?= $reject_rs[$i]->description; ?>
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
														<!---Reject_Reason--->
														<div class="col-sm-12">
															<div class="panel panel-primary">
																<div class="panel-heading"><?= lang('applicant') ?></div>
																<div class="panel-body" style="padding: 5px;">
																	<?php if ($GP['advance-approve'] || $GP['quotes-pending_for_PO'] || $GP['quotes-approve'] || $this->Admin || $this->Owner){ ?>
															
														<?php } ?>
														<div class="col-md-5">
															
															<!--<?php if ($Owner || $Admin || !$this->session->userdata('biller_id')) { ?>
																	<div class="form-group">
																		<?= lang("dealer"); ?><span style="margin-left:70px;">:</span>
																		<?php
																		$bl[""] = "";
																		foreach ($billers as $biller) {
																			$bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
																		}
																		if($inv->biller_id) {
																			$dealer= $inv->biller_id;
																			echo $bl[$dealer];
																		}else {
																			echo '';
																		}
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
															<div class="table-responsive">
															<table>
																<tbody>																																		
																	<tr>
																		<td><?= lang("applicant_name"); ?> </td>
																		<td><b> : <?php
																			if($applicant->gender=='ប្រុស'){
																				echo 'Mr. ';
																			}
																			else{echo 'Mrs. ';}
																				echo $applicant->family_name.' '.$applicant->name; 
																			?> </b>
																		</td>
																	</tr>																	
																	<tr>
																		<td><?= lang("issue_by"); ?></td>
																		<td><b> : <?php echo $applicant->issue_by?$applicant->issue_by:'';?> </b></td>
																	</tr>
																	<tr>
																		<td><?= lang("khmer_name"); ?></td>
																		<td><b> : <?php echo $applicant->family_name_other.' '.$applicant->name_other; ?> </b></td>
																	</tr>
																	<tr>
																		<td><?= lang("phone"); ?></td>
																		<td><b> :<?=$applicant->phone1?>
																				<?php
																					if(($applicant->phone2)){
																						echo ' / '.$applicant->phone2;
																					}
																				?></b>
																		</td>
																	</tr>																	
																	<tr>
																		<td> <?= lang("gender"); ?> </td>
																		<td><b> :<?php echo $applicant->gender?$applicant->gender:''; ?> </b></td>
																	</tr>
																	<tr>
																		<td><?= lang("date_of_birth"); ?></td>
																		<td><b> : <?php echo $this->erp->hrsd($applicant->date_of_birth); ?> </b></td>
																	</tr>
																	<tr>
																		<td><?= lang("age"); ?></td>
																		<td><b> : <?php echo $applicant->age; ?> </b></td>
																	</tr>
																	
																	<tr>
																		<td><?= lang("place_of_birth"); ?></td>
																		<td><b> : <?php echo $applicant->address; ?> </b></td>
																	</tr>
																	
																	<tr>
																		<td><?= lang("nationality"); ?></td>
																		<td><b> : <?php
																				$cus_nationality[""] = "";
																				$cus_nationality['cam'] = "Cambodian";
																				$cus_nationality['tha'] = "Thailand";
																				$cus_nationality['vie'] = "Vietnamese";
																				$cus_nationality['chi'] = "Chinese";
																				$cus_nationality['bm'] = "Burma";
																				echo  isset($applicant->nationality)? $cus_nationality[$applicant->nationality] : '';
																				?> </b>
																		</td>
																	</tr>																	
																</tbody>
															</table>
															</div>
															<!--<div class="form-group">
																<?= lang("issue_by"); ?><span style="margin-left:65px;">:</span>
																<?php
																echo isset($applicant->issue_by)?lang($applicant->issue_by):'';
																?>
															</div>
															<div class="form-group">
																<?= lang("applicant_name"); ?><span style="margin-left:10px;">:</span>
																<?php
																if($applicant->civility=='male'){
																	echo 'Mr. ';
																}
																else{echo 'Mrs. ';}
																	echo $applicant->family_name.' '.$applicant->name; 
																?>
															</div>
															
															<div class="form-group person" style="display:none;">
																<?= lang("nick_name"); ?>
																<?php echo form_input('cus_nick_name', $applicant->nickname, 'class="form-control tip" id="cus_nick_name"'); ?>
															</div>
															<div class="form-group">
																<?= lang("phone"); ?><span style="margin-left:70px;">:</span>
																<?=$applicant->phone1?>
																<?php
																	if(($applicant->phone2)){
																		echo ' / '.$applicant->phone2;
																	}
																?>
															</div>
															<div class="form-group">
																<?= lang("gender"); ?><span style="margin-left:65px;">:</span>
																<?php
																echo isset($applicant->gender)?lang($applicant->gender):'';
																?>
															</div>
															<div class="form-group">
																<?= lang("age"); ?><span style="margin-left:88px;">:</span>
																<?php echo $applicant->age; ?>
															</div>
															<div class="form-group">
																<?= lang("date_of_birth"); ?><span style="margin-left:63px;">:</span>
																<?php echo $this->erp->hrsd($applicant->date_of_birth); ?>
															</div>
															<div class="form-group">
																<?= lang("place_of_birth"); ?><span style="margin-left:25px;">:</span>
																<?php echo $applicant->address; ?>
															</div>
															
															<div class="form-group">
																<?= lang("whose_income"); ?><span style="margin-left:20px;">:</span>
																<?php echo$applicant->whose_income; ?>
															</div>
															<div class="form-group">
																<?= lang("nationality"); ?><span style="margin-left:45px;">:</span>
																<?php
																$cus_nationality[""] = "";
																$cus_nationality['cam'] = "Cambodian";
																$cus_nationality['tha'] = "Thailand";
																$cus_nationality['vie'] = "Vietnamese";
																$cus_nationality['chi'] = "Chinese";
																echo  isset($applicant->nationality)? $cus_nationality[$applicant->nationality] : '';
																?>
															</div>-->	
														</div>
														
														<div class="col-md-5">
															<div class="table-responsive">
															<table>
																<tbody>																																		
																	<tr>
																		<td><?php echo $applicant->ident_name ?></td>
																		<td><b> : <?php echo $applicant->gov_id; ?> </b></td>
																	</tr>
																	<tr>
																		<td><?= lang("issue_date"); ?></td>
																		<td><b> : <?php	echo $this->erp->hrsd($applicant->issue_date)?$this->erp->hrsd($applicant->issue_date):'';	?> </b></td>
																	</tr>
																	<tr>
																		<td><?= lang("father_name"); ?></td>
																		<td><b> : <?php echo (isset($applicant->father_name)? $applicant->father_name : ''); ?> </b></td>
																	</tr>
																	<tr>
																		<td><?= lang("marital_status"); ?></td>
																		<td><b><span class="uppercase">: <?php
																			$cus_marital_status[""] = "";
																			$cus_marital_status['Married'] = "Married ";
																			$cus_marital_status['Divorced'] = "Divorced";
																			$cus_marital_status['Single'] = "Single";
																			$cus_marital_status['Widow/Widower'] = "Widow/Widower";
																			$cus_marital_status['Unknown'] = "Unknown";																			
																			echo  lang($applicant->status);
																			?>
																			</b></span>
																		</td>
																	</tr>			
																	<tr id="sp_gender">
																		<td><?= lang("spouse_gender"); ?></td>
																		<td><b> : <?php echo (isset($applicant->spouse_gender)? $applicant->spouse_gender : ''); ?> </b></td>
																	</tr>
																	<tr id="sp_status">
																		<td><?= lang("spouse_status"); ?></td>
																		<td><b> : <?php echo (isset($applicant->spouse_status)? $applicant->spouse_status : ''); ?> </b></td>
																	</tr>
																	<tr id="spname">
																		<td><?= lang("spouse_name"); ?></td>
																		<td><b> : <?php echo $applicant->spouse_family_name.' '.$applicant->spouse_name; ?> </b></td>
																	</tr>
																	<tr id="sp_date">
																		<td><?= lang("spouse_birthdate"); ?></td>
																		<td><b> : <?php echo (isset($applicant->spouse_birthdate)? $this->erp->hrsd($applicant->spouse_birthdate): ''); ?> </b></td>
																	</tr>
																	<tr id="whoseincome">
																		<td><?= lang("whose_income"); ?></td>
																		<td><b> : <?php echo$applicant->whose_income; ?> </b></td>
																	</tr>
																	<tr id="spchild">
																		<td><?= lang("number_of_children"); ?></td>
																		<td><b> : <?php echo (isset($applicant->num_of_child)? $applicant->num_of_child : ''); ?> </b></td>
																	</tr>
																	
																	<tr id="spphone">
																		<td><?= lang("spouse_mobile_phone"); ?></td>
																		<td><b> : <?=$applicant->spouse_phone?> </b></td>
																	</tr>	
																	<tr id ="incomecombine">
																		<td><?= lang("income_combination"); ?></td>
																		<td><b> : <?php
																				$cus_inc_comb['0'] = "No";
																				$cus_inc_comb['1'] = "Yes";
																				if($applicant->income_combination=='0'){
																					echo 'No';
																				}elseif($applicant->income_combination=='1'){echo 'Yes';}
																				else{echo 'N/A';}
																				
																				?> </b>
																		</td>
																	</tr>		
																</tbody>
															</table>
															</div>
															<!--
															<div class="form-group">
																<?= lang("issue_date"); ?><span style="margin-left:65px;">:</span>
																<?php
																echo isset($applicant->issue_date)?lang($applicant->issue_date):'';
																?>
															</div>
															<div class="form-group">																
																<?php echo $applicant->ident_name ?><span style="margin-left:50px;">:</span>
																<?php echo $applicant->gov_id; ?>
															</div>
															<div class="form-group">
																<?= lang("khmer_name"); ?><span style="margin-left:63px;">:</span>
																<?php echo $applicant->family_name_other.' '.$applicant->name_other; ?>
															</div>
															<div class="form-group">
																<?= lang("income_combination"); ?><span style="margin-left:15px;">:</span>
																<?php
																$cus_inc_comb['0'] = "No";
																$cus_inc_comb['1'] = "Yes";
																if($applicant->income_combination=='0'){
																	echo 'No';
																}elseif($applicant->income_combination=='1'){echo 'Yes';}
																else{echo 'N/A';}
																
																?>
															</div>
															<div class="form-group">
																<?= lang("marital_status"); ?><span style="margin-left:59px;">:</span>
																<?php
																$cus_marital_status[""] = "";
																$cus_marital_status['Married '] = "Married ";
																$cus_marital_status['Divorced'] = "Divorced";
																$cus_marital_status['Single'] = "Single";
																$cus_marital_status['Widow/Widower'] = "Widow/Widower";
																$cus_marital_status['Unknown'] = "Unknown";
																
																echo  lang($applicant->status);
																?>
															</div>
															<div class="form-group">
																<?= lang("spouse_name"); ?><span style="margin-left:27px;">:</span>
																<?php echo $applicant->spouse_family_name.' '.$applicant->spouse_name; ?>
															</div>
															<div class="form-group">
																<?= lang("spouse_gender"); ?><span style="margin-left:19px;">:</span>
																<?php echo (isset($applicant->spouse_gender)? $applicant->spouse_gender : ''); ?>
															</div>
															<div class="form-group">
																<?= lang("spouse_status"); ?><span style="margin-left:19px;">:</span>
																<?php echo (isset($applicant->spouse_status)? $applicant->spouse_status : ''); ?>
															</div>
															<div class="form-group">
																<?= lang("number_of_children"); ?><span style="margin-left:19px;">:</span>
																<?php echo (isset($applicant->num_of_child)? $applicant->num_of_child : ''); ?>
															</div>
															<div class="form-group">
																<?= lang("spouse_birthdate"); ?><span style="margin-left:19px;">:</span>
																<?php echo (isset($applicant->spouse_birthdate)? $applicant->spouse_birthdate : ''); ?>
															</div>
															<div class="form-group">
																<?= lang("spouse_mobile_phone"); ?><span style="margin-left:11px;">:</span>
																<?=$applicant->spouse_phone?>
															</div>	-->
															<div class="form-group" style="display:none;">
																<?= lang("black_list_customer"); ?>
																<?php
																$cus_black_list['0'] = "No";
																$cus_black_list['1'] = "Yes";
																echo form_dropdown('cus_black_list', $cus_black_list, $applicant->black_list, 'class="form-control select" id="cus_black_list" style="width:100%"');
																?>
															</div>
														</div>
														<div class="col-md-2" style="float:right;">
															<!--<div style=" width:120px; height:150px; background-color:#ccc;">
																<?php echo '<img src="' . base_url() . 'assets/uploads/documents/' . $qphoto->name .'"  style=" width:120px; height:150px;" /> '?>
															</div>-->
															<div style=" width:120px; height:150px; background-color:#ccc; margin-top:20px;">
																<?php echo '<img src="' . base_url() . 'assets/uploads/documents/' . ($qphoto->name?$qphoto->name:'male.png') .'"  style=" width:120px; height:150px;" />' ?>
																
															</div>
														</div>
																</div>
															</div>
														</div>
														<!----->
														<div class="col-sm-12">
															<div class="panel panel-primary">
																<div class="panel-heading"><?= lang('address') ?></div>
																<div class="panel-body" style="padding: 5px;">
																	<div class="col-md-6">
																		
																			<div class="table-responsive2">
																			<table>
																				<tbody>
																					<tr>
																						<td><?= lang("country"); ?></td>
																						<td><b> : <?php	echo $address? $address['country'] : 'N/A';	?> </b></td>
																					<tr>																					
																					<tr>
																						<td><?= lang("district"); ?></td>
																						<td><b> : <?php echo $address ? $address['district'] : 'N/A';?> </b></td>
																					<tr>																					
																					<tr>
																						<td><?= lang("village"); ?></td>
																						<td><b> : <?php echo $address ? $address['village'] : 'N/A';?> </b></td>
																					<tr>																					
																					<tr>
																						<td><?= lang("current_address"); ?></td>
																						<td><b> : <?php echo $applicant->house_no; ?> </b></td>
																					<tr>
																				</tbody>
																			</table>
																			</div>
																		<!--
																		<div class="form-group">
																			<?= lang("country"); ?><span style="margin-left:41px;">:</span>
																			<?php
																				echo $address? $address['country'] : 'N/A';
																			?>
																		</div>
																		<div class="form-group">
																			<?= lang("district"); ?><span style="margin-left:47px;">:</span>
																			<?php echo $address ? $address['district'] : 'N/A';?>
																		</div>
																		<div class="form-group">
																			<?= lang("village"); ?><span style="margin-left:51px;">:</span>
																			<?php echo $address ? $address['village'] : 'N/A';?>
																		</div>
																		<div class="form-group">
																			<?= lang("current_address"); ?><span style="margin-left:33px;">:</span>
																			<?php echo $applicant->house_no; ?>
																		</div>-->
																		
																	</div>
																	<div class="col-md-6">
																		<div class="table-responsive2">
																			<table>
																				<tbody>
																					<tr>
																						<td><?= lang("province"); ?></td>
																						<td><b> : <?= $address ? $address['province'] : 'N/A';?> </b></td>
																					<tr>																					
																					<tr>
																						<td><?= lang("communce"); ?></td>
																						<td><b> : <?= $address ? $address['communce'] : 'N/A';?> </b></td>
																					<tr>																					
																					<tr>
																						<td><?= lang("time_at_this_address"); ?></td>
																						<td><b> : <?php echo $applicant->years; ?> </b>
																							<?= lang("years"); ?>
																							<b> <?php echo $applicant->months; ?> </b>
																							<?= lang("months"); ?> 
																						</td>
																					<tr>
																				</tbody>
																			</table>
																			</div>
																		<!--	
																		<div class="form-group">
																			<?= lang("province"); ?><span style="margin-left:35px;">:</span>
																			<?= $address ? $address['province'] : 'N/A';?>
																		</div>
																		<div class="form-group">
																			<?= lang("communce"); ?><span style="margin-left:15px;">:</span>
																			<?= $address ? $address['communce'] : 'N/A';?>
																		</div>
																		<div class="form-group">
																			<b style="padding-bottom:5px; display:block;"><?= lang("time_at_this_address"); ?></b>
																			<?php echo $applicant->years; ?>
																			<?= lang("years"); ?>
																			<?php echo $applicant->months; ?>
																			<?= lang("months"); ?>
																		</div>-->
																		<!--<div class="form-group">
																			<?= lang("street"); ?><span style="margin-left:51px;">:</span>
																			<?php echo $applicant->street; ?>
																		</div>-->
																		<!--<div class="form-group">
																			<?= lang("housing"); ?><span style="margin-left:40px;">:</span>
																			<?php
																			$cus_housing[(isset($_POST['cus_housing']) ? $_POST['cus_housing'] : '')] = (isset($_POST['cus_housing']) ? $_POST['cus_housing'] : '');
																			$cus_housing["owner"] = "Owner";
																			$cus_housing["living_with_parent"] = "Living with parent";
																			$cus_housing["renting"] = "Renting";
																			echo lang($applicant->housing);
																			?>
																		</div>-->
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
																		<?php echo lang('category') ?><span style="margin-left:50px;">:</span>
																		<b> <?php
																		$cat_all = array();
																		if(is_array($categories)){
																		foreach($categories as $cat_){
																			$cat_all[$cat_->id] = $cat_->name;
																		}}
																		$product_id = $product?$product->category_id:'';
																			if($product_id!=''){
																				echo $cat_all[$product_id];
																			}
																		?> </b>
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group">
																		<?php echo lang('sub_category') ?><span style="margin-left:79px;">:</span>
																		<b> <?php
																		echo isset($product->sname) ?$product->sname  : ('') ;
																		?> </b>
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group">
																		<?php echo lang('product') ?><span style="margin-left:70px;">:</span>
																		<b> <?php
																			echo $product?$product->pname  : ('');
																		?> </b>
																		<input type="hidden" name="product" id="product" value="<?= $product?$product->id:''; ?>" />
																	</div>
																</div>
																
																<div class="col-md-4" <?= ($inv->mfi? '' : 'style="display:none;"'); ?> >
																	<div class="form-group all">
																		<?= lang("currency") ?><span style="margin-left:50px;">:</span>
																		<b> <?= (isset($currency->name) ?$currency->name  : ''); ?> </b>
																	</div>
																</div>
																
																<div class="col-md-4">
																	<div class="form-group all">
																		<?= lang("amount") ?><span style="margin-left:60px;">:</span>
																		<b> <?= (isset($product->unit_price) ? $this->erp->formatMoney($product->unit_price) : '') ?> </b>
																		<input id="total_amount" class="form-control" name="price" value="<?= (isset($product->unit_price) ? $product->unit_price : ''); ?>" type="hidden">
																	</div>
																</div>
																<div class="col-md-4">
																	<div class="form-group all">
																		<?= lang("group_loans") ?><span style="margin-left:42px;">:</span>
																		<b> <?php
																			echo $product?$product->group_name  : ('');
																		?> </b>
																	</div>
																</div>
																<div class="col-md-4">
																	<div class="form-group all">
																		<?= lang("purpose") ?><span style="margin-left:55px;">:</span>
																		<b> <?php
																			echo $applicant?$applicant->note  : ('');
																		?> </b>
																	</div>
																</div>
																<?php 
																$commission = $product->total;
																$commission_amt = $commission * $applicants->commission;
																?>
																<div class="col-md-4">
																	<div class="form-group all">
																		<input type="hidden" name="commission" id="commission" value="<?php echo ($commission_amt ? $commission_amt : '') ?>" />
																	</div>
																</div>
																
																<div class="col-md-12" <?= ($inv->mfi? '' : 'style="display:none;"'); ?> >
																	<div class="form-group all">
																		<?= lang("description") ?><span style="margin-left:35px;">:</span>
																		<?php
																		$ldescription = '';
																		if(isset($product->description)) {
																			$ldescription = explode('<p>', $product->description);
																		}
																		?>
																		<b> <?= ((isset($product->description))? strip_tags($product->description) : ''); ?> </b> <!---<b> <?= ((isset($product->description))? strip_tags($product->description, '<p>') : ''); ?> </b>--->
																	</div>
																</div>
																
																<div class="col-md-4" <?= ($inv->mfi? 'style="display:none;"' : ''); ?> >
																	<div class="form-group all">
																		<?= lang("color") ?><span style="margin-left:74px;">:</span>
																		<?php
																		$vari[""] = "";
																		if($variants){
																		foreach ($variants as $variant) {
																			$vari[$variant->id] = $variant->name;
																			}
																		}
																		echo $vari[$product?$product->color:''];
																		?>
																	</div>
																</div>
																
																<div class="col-md-4" <?= ($inv->mfi? 'style="display:none;"' : ''); ?> >
																	<div class="form-group all">
																		<?= lang("year") ?><span style="margin-left:92px;">:</span>
																		<?php
																		$Y[""] = "";
																		$dur = date('Y') - 1990;
																		for($i=0;$i<=$dur;$i++) {
																			$yyyy = date('Y', strtotime('-'.$i.' years'));
																			$Y[$yyyy] = $yyyy;
																		}
																		echo isset($Y[$product?$product->product_year:'']) ?$Y[$product?$product->product_year:'']  : ('');
																		?>
																	</div>
																</div>
																
																<div class="col-md-4" <?= ($inv->mfi? 'style="display:none;"' : ''); ?> >
																	<div class="form-group all">
																		<?= lang("engine") ?><span style="margin-left:67px;">:</span>
																		<?= $product?$product->engine:''; ?>
																	</div>
																</div>
																
																<div class="col-md-4" <?= ($inv->mfi? 'style="display:none;"' : ''); ?> >
																	<div class="form-group all">
																		<?= lang("frame_number") ?><span style="margin-left: 15px;">:</span>
																		<?= $product?$product->frame:''; ?>
																	</div>
																</div>
																
																<div class="col-md-4" <?= ($inv->mfi? 'style="display:none;"' : ''); ?> >
																	<div class="form-group all">
																		<?= lang("power") ?><span style="margin-left:81px;">:</span>
																		<?= $product?$product->power:''; ?>
																	</div>
																</div>

																<div class="col-md-4" <?= ($inv->mfi? 'style="display:none;"' : ''); ?> >
																	<div class="form-group all">
																		<?= lang("distance_mile") ?><span style="margin-left:26px;">:</span>
																		<?= $product?$product->distance_mile:''; ?>
																	</div>
																</div>
															</div>
														</div>
													</div>
											</div>
												
												<?php
												if($qu_saving) {
												?>
												<div class="col-sm-12">
													<div class="panel panel-primary">
														<div class="panel-heading"><?= lang('compulsory_saving') ?></div>
														<div class="panel-body" style="padding: 5px;">
															<div class="col-sm-12">
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang('saving_amount_%'); ?> <span style="margin-left:100px;">:</span>
																		<b> <?php echo $qu_saving->saving_rate * 100 .'%' ; ?> </b>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang('saving_amount'); ?> <span style="margin-left:80px;">:</span>
																		<?php $saving_amount = $this->erp->convertCurrency($product->currency_code, $setting->default_currency, $qu_saving->saving_amount) ; ?>
																		<b> <?php echo $this->erp->formatMoney($saving_amount) ; ?> </b>
																	</div>
																</div>
																
															</div>
															
															<div class="col-sm-12">
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang('interest_of_saving_%'); ?> <span style="margin-left:83px;">:</span>
																		<b> <?php echo $qu_saving->saving_interest_rate * 100 .'%' ; ?> </b>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang('saving_type'); ?> <span style="margin-left:100px;">:</span>
																		<b> <?php 	$saving_type[1] = "Normal";
																				echo  $saving_type[$qu_saving->saving_type]; ?> </b>
																	</div>
																</div>																
															</div>															
														</div>
													</div>
												</div>
												<?php } ?>
												
												
												<?php
												if($services) {
												?>
												<div class="col-sm-12">
													<div class="panel panel-primary">
														<div class="panel-heading"><?= lang('services') ?></div>
														<div class="panel-body" style="padding: 5px;">
															<div class="col-sm-12">
																<div class="col-md-3">
																	<div class="form-group">
																		<?= lang('descriptions'); ?>
																		<?php echo $this->erp->formatDecimal($inv->advance_payment); ?>
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<?= lang('descriptions'); ?>
																		<?php echo $this->erp->formatDecimal($inv->advance_payment); ?>
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
																		<b> <?php echo (($service->method == 'Percentage')? $this->erp->formatNumber($service->amount*100).'%' : $this->erp->formatMoney($service->amount)); ?> </b>
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<input type="checkbox" class="form-control ch_services" amount="<?php echo isset($service->amount ) ? $service->amount   : ('')?>" status="<?= $service->method; ?>", service_paid="<?= $service->service_paid; ?>"​ ,  name="ch_services[]", value="<?= $service->id; ?>" <?php echo set_checkbox('ch_services[]', '1', isset($quote_service[$k]->quote_id)==$id?TRUE:FALSE); ?> <?= (isset($quote_service[$k]->quote_id)==$id? 'disabled="true"':''); ?> >
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group">
																		<b> <?php echo $service->tax_name;	?></b>
																	</div>
																</div>
																<?php
																if($k == 0) {
																?>
																<div class="col-md-3" style="display:none;">
																	<b> <div class="form-group total_services_charge">
																		
																	</div> </b>
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
												<?php } ?>
												<div class="col-sm-12" <?= ($inv->mfi? 'style="display:none;"' : ''); ?> >
													<div class="panel panel-primary">
														<div class="panel-heading"><?= lang('financial_products') ?></div>
														<div class="panel-body" style="padding: 5px;">
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("financial_product"); ?><span style="margin-left:25px;">:</span>
																	<?php
																	$fin_pro[""] = "";
																	foreach ($finacal_products as $financial_product) {
																		$fin_pro[$financial_product->id] = $financial_product->name;
																	}
																	echo $fin_pro[$inv->customer_group] ;
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("advance_percentage"); ?><span style="margin-left:25px;">:</span>
																	<?php
																	$percentage[""] = "";
																	foreach ($advance_percentages as $advance_percentage) {
																		$percentage[$advance_percentage->amount] = $advance_percentage->description;
																	}
																	echo  ($inv->advance_percentage_payment*100).'%';
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("advance_payment"); ?><span style="margin-left:20px;">:</span>
																	<?php echo $this->erp->formatDecimal($inv->advance_payment); ?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("lease_amount"); ?><span style="margin-left:65px;">:</span>
																	<?php 
																		$lease_amount = ($product->unit_price - $inv->advance_payment);
																		echo (($product && $inv)? $this->erp->formatMoney($lease_amount) : 0); 
																	?>
																	<input 	type="hidden" name="grand_amount" id="grand_amount" value="" />
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("frequency"); ?><span style="margin-left:68px;">:</span>
																	<?php
																	$frequency[""] = "";
																	$frequency[1] = "Daily";
																	$frequency[7] = "Weekly";
																	$frequency[14] = "Two Week";
																	$frequency[30] = "Monthly";
																	$frequency[90] = "Quarterly";
																	$frequency[180] = "Haft Year";
																	$frequency[360] = "Yearly";
																	echo  $frequency[$inv->frequency];
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("rate_type"); ?><span style="margin-left:90px;">:</span>
																	<?php
																	$rate_type[""] = "";
																	$rate_type["1"] = "Normal";
																	$rate_type["2"] = "Fixed";
																	$rate_type["3"] = "Normal_Fixed";
																	$rate_type["4"] = "Seasons";
																	$rate_type["5"] = "Custom";
																	echo $rate_type[$inv->rate_type];
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("interest_rate"); ?><span style="margin-left:55px;">:</span>
																	<?php
																	$interest[""] = "";
																	foreach ($interest_rates as $interest_rate) {
																		$interest[$interest_rate->amount] = $interest_rate->description;
																	}
																	echo ($inv->interest_rate*100).'%';
																	?>
																</div>
															</div>
															<div class="col-lg-6">
																<!--<div class="form-group">
																	<?= lang("term"); ?><span style="margin-left:121px;">:</span>
																	<?php
																	$term[""] = "";
																	if(array($terms)) {
																		foreach ($terms as $tm) {
																			$term[$tm->amount] = $tm->description;
																		}
																	}
																	$myterm = number_format($inv->term);
																	echo $term[$myterm];
																	?>
																</div>-->
																
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	
																	<?= lang("installment_amount"); ?><span style="margin-left:7px;">:</span>
																	<?php echo $this->erp->formatMoney($this->erp->getInstallmentAmount($lease_amount, $inv->rate_type, $inv->interest_rate, $inv->term)); ?>
																</div>
															</div>
															<div class="col-lg-6 btn_print_payment_schedule">
																<?php
																	if($lease_amount && $inv) {
																		echo '<a href="InstallmentAmount/cash_payment_schedule_preview/'.$lease_amount.'/'.$inv->rate_type.'/'.$inv->interest_rate.'/'.$inv->term.'/'.$inv->frequency.'" class="btn btn-primary" rel="lightbox" data-toggle="modal" data-target="#myModal">'.lang('print_payment_schedule').'</a>';
																	} 
																?> 
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-12" <?= ($inv->mfi? '' : 'style="display:none;"'); ?> >
													<div class="panel panel-primary">
														<div class="panel-heading"><?= lang('loan_information') ?></div>
														<div class="panel-body" style="padding: 5px;">
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("interest_rate"); ?><span style="margin-left:55px;">:</span>
																	<b> <?php
																	$interest[""] = "";
																	foreach ($interest_rates as $interest_rate) {
																		$interest[$interest_rate->amount] = $interest_rate->description;
																	}
																	echo ($inv->interest_rate*100).'%';
																	?> </b>
																</div>
																
																<div class="form-group" style="display:none;">
																	<?= lang("rate_text"); ?><span style="margin-left:55px;">:</span> 
																	<?php echo ($inv->rate_text); ?> 
																</div>
																
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("frequency"); ?><span style="margin-left:68px;">:</span>
																	<b> <?php
																	$frequency[""] = "";
																	$frequency[1] = "Daily";
																	$frequency[7] = "Weekly";
																	$frequency[14] = "Two Week";
																	$frequency[30] = "Monthly";
																	$frequency[90] = "Quarterly";
																	$frequency[180] = "Haft Year";
																	$frequency[360] = "Yearly";
																	echo  $frequency[$inv->frequency];
																	?> </b>
																</div>
															</div>
															<div class="col-lg-6">
																<!--<div class="form-group">
																	<?= lang("term"); ?><span style="margin-left:105px;">:</span>
																	<b> <?php
																	$term[""] = "";
																	if(array($terms)) {
																		foreach ($terms as $tm) {
																			$term[$tm->amount] = $tm->description;
																		}
																	}
																	$myterm = round($inv->term);
																	echo $term[$myterm];
																	?> </b>
																</div>-->
																<div class="form-group">
																	<?= lang("term"); ?><span style="margin-left:105px;">:</span>
																	<b> <?php echo ($inv->term / $inv->frequency); ?> </b>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<?= lang("rate_type"); ?><span style="margin-left:72px;">:</span>
																	<b> <?php
																	$rate_type[""] = "";
																	$rate_type["1"] = "Normal";
																	$rate_type["2"] = "Fixed";
																	$rate_type["3"] = "Normal_Fixed";
																	$rate_type["4"] = "All_Fixed";
																	$rate_type["5"] = "Seasons";
																	$rate_type["6"] = "Loan Amounts";
																	echo $rate_type[$inv->rate_type];
																	?> </b>
																</div>
															</div>
															
															<?php
																if($inv->rate_type == 5) {
															?>
															<div class="col-lg-6">
																<div class="form-group">
																		<?= lang("principle_frequency"); ?><span style="margin-left:15px;">:</span> 
																		<b> <?php echo  ($inv->principle_frequency); ?>  </b>
																</div>
															</div>
															<?php
																}
															?>
															
															<?php 
																$appr_date = date('m/d/Y h:i:s a', time());
																$app_date = date('Y-m-d',strtotime($appr_date));
																
																$cdate = date('Y-m-d',strtotime($applicant->installment_date));
															?>
															<div class="col-lg-6" style="display:none;">
																<div class="form-group">
																	<?= lang("total_interest_rate"); ?><span style="margin-left:20px;">:</span>
																	<b> <?php 
																		$cash = $product? $product->unit_price : 0;
																		$all_total = 0;
																		if($cash && $inv) {
																			$all_total = $this->erp->getAllTotal($cash, $inv->rate_type, $inv->interest_rate, $inv->term, $inv->frequency);
																		}
																		echo $this->erp->formatMoney($all_total['total_interest']); 
																		?> 
																	</b>
																</div>
															</div>
															<div class="col-lg-6 btn_print_payment_schedule_cash">
																<?php
																	if($cash && $inv) {
																		echo '<a href="quotes/cash_payment_schedule_applicant/'.$cash.'/'.$inv->rate_type.'/'.$inv->interest_rate.'/'.$inv->term.'/'.$inv->frequency.'/'.$product->currency_code.'/'.$inv->id.'/'.$cdate.'/'.$app_date.'/' .$inv->principle_frequency.'/' . 0 .'/' .$saving_amount.'/' .$qu_saving->saving_interest_rate.'/' .$qu_saving->saving_type.'" class="btn btn-primary" rel="lightbox" data-toggle="modal" data-target="#myModal">'.lang('print_payment_schedule').'</a>';
																	}
																?>
															</div>
														</div>
													</div>
												</div>
										</div>
									</div>
								</div>
								
								<div id="credit_assessment" style="display:none;" class="tab-pane fade in">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<div class="row">
													<div class="col-lg-12">
														 
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div id="join_lease" style="display:none;" class="tab-pane fade">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<div class="row">
													<div class="col-lg-12">
														<div class="col-md-6">
															<div class="table-responsive">
																<table>
																	<tbody>
																		<tr>
																			<td><?php echo $join_lease->ident_name ?></td>
																			<td><b> : <?php echo $join_lease->gov_id; ?></b></td>
																		</tr>
																		<tr>
																			<td><?= lang("date_of_birth"); ?></td>
																			<td><b> : <?php echo $this->erp->hrsd($join_lease->date_of_birth); ?> </b></td>
																		</tr>
																		<tr>
																			<td><?= lang("gender"); ?></td>
																			<td><b> : <?php echo $join_lease->gender?$join_lease->gender:'';?> </b></td>
																		</tr>
																		<tr>
																			<td><?= lang("children_member"); ?></td>
																			<td><b> : <?php echo $join_lease->num_of_child; ?> </b></td>
																		</tr>
																		<tr>
																			<td><?= lang("address"); ?></td>
																			<td><b> : <?php 
																					$address = str_replace('<p>', '', $join_lease->address);
																					$address = str_replace('</p>', '', $address);
																					echo $address; 
																				?> </b>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</div>
															<!--<div class="form-group">
																<?php echo $join_lease->ident_name ?><span style="margin-left:16px;">:</span>
																<?php echo $join_lease->gov_id; ?>
															</div>
															<div class="form-group">
																<?= lang("date_of_birth"); ?><span style="margin-left:28px;">:</span>
																<?php echo $this->erp->hrsd($join_lease->date_of_birth); ?>
															</div>
															<div class="form-group">
																<?= lang("gender"); ?><span style="margin-left:70px;">:</span>
																<?php
																echo  isset($join_lease->gender)?lang($join_lease->gender):'';
																?>
															</div>
															<div class="form-group">
																<?= lang("children_member"); ?><span style="margin-left:4px;">:</span>
																<?php echo $join_lease->num_of_child; ?>
															</div>
															<div class="form-group">
																<?= lang("address"); ?><span style="margin-left:62px;">:</span>
																<?php 
																	$address = str_replace('<p>', '', $join_lease->address);
																	$address = str_replace('</p>', '', $address);
																	echo $address; 
																?>
															</div>	-->												
														</div>
														
														<div class="col-md-6">
															<div class="table-responsive">
																<table>
																	<tbody>
																		<tr>
																			<td><?= lang("applicant_name"); ?></td>
																			<td><b> : <?php echo $join_lease->name;  ?> </b></td>
																		</tr>
																		<tr>
																			<td><?= lang("age"); ?></td>
																			<td><b> : <?php echo $join_lease->age; ?> </b></td>
																		</tr>
																		<tr>
																			<td><?= lang("phone"); ?></td>
																			<td><b> : <?=$join_lease->phone1?>
																					<?php
																						if(($join_lease->phone2)){
																							echo ' / '.$join_lease->phone2;
																						}
																					?> </b>
																			</td>
																		</tr>
																		<tr>
																			<td><?= lang("status"); ?></td>
																			<td><b> : <?php echo $join_lease->status; ?></b></td>
																		</tr>
																		<tr>
																			<td><?= lang("family_member"); ?></td>
																			<td><b> : <?php echo $join_lease->family_member; ?> </b></td>
																		</tr>
																		
																	</tbody>
																</table>
															</div>
															<!--<div class="form-group">
																<?= lang("applicant_name"); ?><span style="margin-left:46px;">:</span>
																<?php
																	//echo $join_lease->family_name.' '.$join_lease->name .'&nbsp;&nbsp;&nbsp;('. $join_lease->family_name_other.' '.$join_lease->name_other .')'; 
																	echo $join_lease->name; 
																?>
															</div>
															
															<div class="form-group">
																<?= lang("age"); ?><span style="margin-left:123px;">:</span>
																<?php echo $join_lease->age; ?>
															</div>
															<div class="form-group">
																<?= lang("phone"); ?><span style="margin-left:105px;">:</span>
																<?=$join_lease->phone1?>
																<?php
																	if(($join_lease->phone2)){
																		echo ' / '.$join_lease->phone2;
																	}
																?>
															</div>
															<div class="form-group">
																<?= lang("family_member"); ?><span style="margin-left:50px;">:</span>
																<?php echo $join_lease->family_member; ?>
															</div>-->
														</div>
													</div>
												</div>
												<div class="clearfix"></div>
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
																		<?= lang("position"); ?><span style="margin-left:97px;">:</span>
																		<b> <?php echo isset($quote_employee->position) ?$quote_employee->position  : (''); ?> </b>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("employment_status"); ?><span style="margin-left:25px;">:</span>
																		<b> <?php
																			$emp_status = array('active' => 'Active');
																			echo isset($quote_employee->emp_status) ?$quote_employee->emp_status  : ('');
																			?> </b>
																	</div>
																</div>																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("employment_industry"); ?><span style="margin-left:12px;">:</span>
																		<b> <?php
																			$emp_industrial = array('manufacturing' => 'Manufacturing');
																			echo isset($quote_employee->emp_industry) ?$quote_employee->emp_industry  : ('');
																			?> </b>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("seniorities_level"); ?><span style="margin-left:47px;">:</span>
																		<b> <?php
																			$seniorities_level = array('staff' => 'Staff');
																			echo isset($quote_employee->senior_level) ?$quote_employee->senior_level : ('');
																			?> </b>
																	</div>
																</div>																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("work_place_name"); ?><span style="margin-left:38px;">:</span>
																		<b> <?php echo isset($quote_employee->workplace_name) ?$quote_employee->workplace_name : (''); ?> </b>
																	</div>
																</div>																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("work_phone"); ?><span style="margin-left:72px;">:</span>
																		<b> <?php echo isset($quote_employee->work_phone) ?$quote_employee->work_phone : (''); ?> </b>
																	</div>
																</div>																
																<div class="col-md-6">
																	<div class="form-group">
																		<input type="checkbox" disabled id="allow_call_to_work_place" class="form-control" name="allow_call_to_work_place" value="1" <?php echo set_checkbox('allow_call_to_work_place', '1',isset( $quote_employee->allow_call_to_work_place) ? $quote_employee->allow_call_to_work_place : ('')==1?TRUE:FALSE); ?>>
																		<?= lang("allow_call_to_work_place"); ?>
																	</div>
																</div>																
																<div class="col-md-6">
																	<div class="form-group">
																		<b style="padding-bottom:5px; display:block;"><?= lang("time_at_this_address"); ?></b>
																		<b> <?php echo isset( $quote_employee->years) ? $quote_employee->years : (''); ?> </b>
																		<?= lang("years"); ?>
																		<b> <?php echo isset( $quote_employee->months) ? $quote_employee->months : (''); ?> </b>
																		<?= lang("months"); ?>
																	</div>
																</div>																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("basic_salary"); ?><span style="margin-left:75px;">:</span>
																		<b> <?php echo isset($quote_employee->basic_salary) ?$quote_employee->basic_salary : (''); ?> </b>
																	</div>
																</div>																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("allowance_etc"); ?><span style="margin-left:62px;">:</span>
																		<b> <?php echo isset($quote_employee->allowance_etc) ?$quote_employee->allowance_etc : (''); ?> </b>
																	</div>
																</div>																
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("business_expense"); ?><span style="margin-left:37px;">:</span>
																		<b> <?php echo isset($quote_employee->business_expense) ?$quote_employee->business_expense : (''); ?> </b>
																		<?= lang("month"); ?>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("employment_address"); ?><span style="margin-left:15px;">:</span>
																		<b> <?php echo $applicant->house_no; ?> </b>
																	</div>	
																</div>
															</div>
														</div>
														<div class="panel panel-primary" style="display:none;">
															<div class="panel-heading"><?= lang('current_employment_address') ?></div>
															<div class="panel-body" style="padding: 5px;">
																<!--
																<div class="col-md-12">
																	<div class="form-group">
																		<?php echo form_checkbox(['name' => 'same_applicant_address', 'value' => 1]); ?>
																		<?= lang("same_applicant_address"); ?>
																	</div>
																</div>
																-->	
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("country"); ?><span style="margin-left:41px;">:</span>
																		<b> <?php
																			echo $address_employee && $address_employee['country']? $address_employee['country'] : 'N/A';
																		?> </b>
																	</div>
																	<div class="form-group">
																		<?= lang("district"); ?><span style="margin-left:47px;">:</span>
																		<b> <?php echo $address_employee ? $address_employee['district'] : 'N/A';?> </b>
																	</div>
																	<div class="form-group">
																		<?= lang("village"); ?><span style="margin-left:51px;">:</span>
																		<b> <?php echo $address_employee ? $address_employee['village'] : 'N/A';?> </b>
																	</div>
																	<div class="form-group">
																		<?= lang("house_no"); ?><span style="margin-left:33px;">:</span>
																		<b> <?php echo $applicant->house_no; ?> </b>
																	</div>																		
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<?= lang("province"); ?><span style="margin-left:35px;">:</span>
																		<b> <?= $address_employee ? $address_employee['province'] : 'N/A';?> </b>
																	</div>
																	<div class="form-group">
																		<?= lang("communce"); ?><span style="margin-left:18px;">:</span>
																		<b> <?= $address_employee ? $address_employee['communce'] : 'N/A';?> </b>
																	</div>
																	<div class="form-group">
																		<?= lang("street"); ?><span style="margin-left:51px;">:</span>
																		<b> <?php echo $applicant->street; ?> </b>
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
								<!--Sethy guarantors-->
								<div id="guarantors" style="display: none;" class="tab-pane fade">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<div class="row">
													<div class="col-lg-12">
														<div class="col-sm-12">
															<div class="panel panel-primary">
																<div class="panel-heading"><?= lang('guarantors_1') ?></div>
																<div class="panel-body" style="padding: 5px;">
																	<div class="col-md-6">
																		<div class="table-responsive">
																			<table>
																				<tbody>
																					<tr>
																						<td><?php echo $guarantor->ident_name ?></td>
																						<td><b> : <?php echo ($guarantor)?$guarantor->gov_id:''; ?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("issue_by"); ?></td>
																						<td><b> : <?=$guarantor->issue_by ?$guarantor->issue_by : ('')?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("issue_date"); ?></td>
																						<td><b> : <?php echo $this->erp->hrsd($guarantor->issue_date) ?$this->erp->hrsd($guarantor->issue_date) : ('')?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("date_of_birth"); ?></td>
																						<td><b> : <?php echo $this->erp->hrsd(isset($guarantor->date_of_birth) ?$guarantor->date_of_birth : ('')); ?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("phone"); ?></td>
																						<td><b> : <?=isset($guarantor->phone1) ?$guarantor->phone1 : ('')?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("job"); ?></td>
																						<td><b> : <?php	echo  isset($guarantor->job)? $guarantor->job : '';	?> </b></td>
																					</tr>
																				</tbody>
																			</table>
																		</div>
																		<!--
																		<div class="form-group">
																			<?php echo $guarantor->ident_name ?> <span style="margin-left:19px;">:</span>
																			<?php echo ($guarantor)?$guarantor->gov_id:''; ?>
																		</div>
																		<div class="form-group">
																			<?= lang("issue_by"); ?><span style="margin-left:74px;">:</span>
																			<?=isset($guarantor->issue_by) ?$guarantor->issue_by : ('')?>
																		</div>
																		<div class="form-group">
																			<?= lang("issue_date"); ?><span style="margin-left:74px;">:</span>
																			<?=isset($guarantor->issue_date) ?$guarantor->issue_date  : ('')?>
																		</div>
																		<div class="form-group">
																			<?= lang("date_of_birth"); ?><span style="margin-left:30px;">:</span>
																			<?php echo $this->erp->hrsd(isset($guarantor->date_of_birth) ?$guarantor->date_of_birth : ('')); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("phone"); ?><span style="margin-left:74px;">:</span>
																			<?=isset($guarantor->phone1) ?$guarantor->phone1 : ('')?>
																		</div>
																		<div class="form-group">
																			<?= lang("job"); ?><span style="margin-left:93px;">:</span>
																			<?php	echo  isset($guarantor->job)? $guarantor->job : '';	?>
																		</div>-->
																	</div>
																	<div class="col-md-6">
																		<div class="table-responsive">
																			<table>
																				<tbody>
																					<tr>
																						<td><?= lang("guarantor_name"); ?></td>
																						<td><b> : <?php echo $guarantor ? ($guarantor->name) : ''; ?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("age"); ?></td>
																						<td><b> : <?=isset($guarantor->age) ?$guarantor->age : ('')?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("gender"); ?></td>
																						<td><b> : <?php echo $guarantor->gender?$guarantor->gender:''; ?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("status"); ?></td>
																						<td><b> : <?php echo $guarantor->status?$guarantor->status:''; ?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("address"); ?></td>
																						<td><b> : <?php 
																								$gl_1_address = '';
																								if($guarantor->address) {
																									$gl_1_address = str_replace('<p>', '', $guarantor->address);
																									$gl_1_address = str_replace('</p>', '', $gl_1_address);
																								}
																								echo $gl_1_address; 
																							?> </b>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</div>
																		<!--<div class="form-group">
																			<?= lang("guarantor_name"); ?><span style="margin-left:42px;">:</span>
																			<?php echo $guarantor ? ($guarantor->name)  : ''; ?>
																		</div>
																		<div class="form-group">
																			<?= lang("age"); ?><span style="margin-left:123px;">:</span>
																			<?=isset($guarantor->age) ?$guarantor->age  : ('')?>
																		</div>
																		<div class="form-group">
																			<?= lang("gender"); ?><span style="margin-left:101px;">:</span>
																			<?php echo  isset($guarantor->gender)?lang($guarantor->gender):''; ?>
																		</div>
																		<div class="form-group">
																			<?= lang("address"); ?><span style="margin-left:96px;">:</span>
																			<?php 
																				$gl_1_address = '';
																				if($guarantor->address) {
																					$gl_1_address = str_replace('<p>', '', $guarantor->address);
																					$gl_1_address = str_replace('</p>', '', $gl_1_address);
																				}
																				echo $gl_1_address; 
																			?>
																		</div>-->
																	</div>
																</div>
															</div>
														</div>
														<div class="col-sm-12">
															<div class="panel panel-primary">
																<div class="panel-heading"><?= lang('guarantors_2') ?></div>
																<div class="panel-body" style="padding: 5px;">
																	<div class="col-md-6">
																		<div class="table-responsive">
																			<table>
																				<tbody>
																					<tr>
																						<td><?php echo $join_guarantor->ident_name ?></td>
																						<td><b> : <?php echo ($join_guarantor)?$join_guarantor->gov_id:''; ?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("issue_by"); ?></td>
																						<td><b> : <?=isset($join_guarantor->issue_by) ?$join_guarantor->issue_by  : ('')?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("issue_date"); ?></td>
																						<td><b> : <?=isset($join_guarantor->issue_date) ?$join_guarantor->issue_date  : ('')?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("date_of_birth"); ?></td>
																						<td><b> : <?php echo $this->erp->hrsd(isset($join_guarantor->date_of_birth) ?$join_guarantor->date_of_birth  : ('')); ?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("phone"); ?></td>
																						<td><b> : <?=isset($join_guarantor->phone1) ?$join_guarantor->phone1  : ('')?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("job"); ?></td>
																						<td><b> : <?php echo  isset($join_guarantor->job)? $join_guarantor->job : '';	?> </b></td>
																					</tr>
																				</tbody>
																			</table>
																		</div>
																		<!--
																		<div class="form-group">
																			<?php echo $join_guarantor->ident_name ?> <span style="margin-left:19px;">:</span>
																			<?php echo ($join_guarantor)?$join_guarantor->gov_id:''; ?>
																		</div>
																		<div class="form-group">
																			<?= lang("issue_by"); ?><span style="margin-left:74px;">:</span>
																			<?=isset($join_guarantor->issue_by) ?$join_guarantor->issue_by  : ('')?>
																		</div>
																		<div class="form-group">
																			<?= lang("issue_date"); ?><span style="margin-left:74px;">:</span>
																			<?=isset($join_guarantor->issue_date) ?$join_guarantor->issue_date  : ('')?>
																		</div>
																		<div class="form-group">
																			<?= lang("date_of_birth"); ?><span style="margin-left:30px;">:</span>
																			<?php echo $this->erp->hrsd(isset($join_guarantor->date_of_birth) ?$join_guarantor->date_of_birth  : ('')); ?>
																		</div>
																		<div class="form-group">
																			<?= lang("phone"); ?><span style="margin-left:74px;">:</span>
																			<?=isset($join_guarantor->phone1) ?$join_guarantor->phone1  : ('')?>
																		</div>
																		<div class="form-group">
																			<?= lang("job"); ?><span style="margin-left:93px;">:</span>
																			<?php echo  isset($join_guarantor->job)? $join_guarantor->job : '';	?>
																		</div>-->
																	</div>
																	<div class="col-md-6">
																		<div class="table-responsive">
																			<table>
																				<tbody>
																					<tr>
																						<td><?= lang("guarantor_name"); ?></td>
																						<td><b> : <?php echo $join_guarantor ? ($join_guarantor->name)  : ''; ?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("age"); ?></td>
																						<td><b> : <?=isset($join_guarantor->age) ?$join_guarantor->age  : ('')?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("gender"); ?></td>
																						<td><b> : <?php echo $join_guarantor->gender?$join_guarantor->gender:'';?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("status"); ?></td>
																						<td><b> : <?php echo $join_guarantor->status?$join_guarantor->status:'';?> </b></td>
																					</tr>
																					<tr>
																						<td><?= lang("address"); ?></td>
																						<td><b> : <?php 
																								$gl_2_address = '';
																								if(isset($join_guarantor->address)) {
																									$gl_2_address = str_replace('<p>', '', $join_guarantor->address);
																									$gl_2_address = str_replace('</p>', '', $gl_2_address);
																								}
																								echo $gl_2_address; 
																								?> </b>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</div>
																		<!--
																		<div class="form-group">
																			<?= lang("guarantor_name"); ?><span style="margin-left:42px;">:</span>
																			<?php
																				//echo $join_guarantor ? ($join_guarantor->family_name.' '.$join_guarantor->name .'&nbsp;&nbsp;&nbsp;('.$join_guarantor->family_name_other .' '. $join_guarantor->name_other .')')  : ''; 
																				echo $join_guarantor ? ($join_guarantor->name)  : ''; 
																			?>
																		</div>
																		<div class="form-group">
																			<?= lang("age"); ?><span style="margin-left:123px;">:</span>
																			<?=isset($join_guarantor->age) ?$join_guarantor->age  : ('')?>
																		</div>
																		<div class="form-group">
																			<?= lang("gender"); ?><span style="margin-left:101px;">:</span>
																			<?php echo  isset($join_guarantor->gender)?lang($join_guarantor->gender):'';?>
																		</div>
																		<div class="form-group">
																			<?= lang("address"); ?><span style="margin-left:96px;">:</span>
																			<?php 
																				$gl_2_address = '';
																				if(isset($join_guarantor->address)) {
																					$gl_2_address = str_replace('<p>', '', $join_guarantor->address);
																					$gl_2_address = str_replace('</p>', '', $gl_2_address);
																				}
																				echo $gl_2_address; 
																			?>
																		</div>-->
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
												<?php
													foreach($getcollateral as $collateral){
												?>
												<div class="row">
													<div class="col-md-6">
													
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("code"); ?></span>:
															<b> <?php echo isset($collateral->code)?$collateral->code:'';?> </b>
														</div>
														<?php if($collateral->type) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("type"); ?></span>:
															<b> <?php echo isset($collateral->type)?$collateral->type:'';?> </b>
														</div>
														<?php } ?>
														<?php if($collateral->adj_north) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("adjacent_north"); ?></span>:
															<b> <?php echo isset($collateral->adj_north)?$collateral->adj_north:'';?> </b>
														</div>
														<?php } ?>
														<?php if($collateral->adj_east) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("adjacent_east"); ?></span>:
															<b> <?php echo isset($collateral->adj_east)?$collateral->adj_east:'';?> </b>
														</div>
														<?php } ?>
														
														<?php if($collateral->roof) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("roof"); ?></span>:
															<b> <?php echo isset($collateral->roof)?$collateral->roof:'';?> </b>
														</div>
														<?php } ?>
														<?php if($collateral->card_no) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("card_number"); ?></span>:
															<b> <?php echo isset($collateral->card_no)?$collateral->card_no:'';?> </b>
														</div>
														<?php } ?>
														<!--vehicles-->
														
														<?php if($collateral->power) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("power"); ?></span>:
															<b> <?php echo isset($collateral->power)?$collateral->power:'';?> </b>
														</div>
														<?php } ?>
														<?php if($collateral->engine_no) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("engine_number"); ?></span>:
															<b> <?php echo isset($collateral->engine_no)?$collateral->engine_no:'';?> </b>
														</div>
														<?php } ?>
														<?php if($collateral->plaque_no) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("plaque_number"); ?></span>:
															<b> <?php echo isset($collateral->plaque_no)?$collateral->plaque_no:'';?> </b>
														</div>
														<?php } ?>
														<?php if($collateral->owner_name) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("owner_name"); ?></span>:
															<b> <?php echo isset($collateral->owner_name)?$collateral->owner_name:'';?> </b>
														</div>
														<?php } ?>
													</div>													
													<div class="col-md-6">
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("type"); ?></span>:
															<b> <?php
															$cl_type[''] = '';
															if($collateral_type) {
															foreach($collateral_type as $c_type){
																$cl_type[$c_type->id] = $c_type->type;
															}
															}
															echo $cl_type[isset($collateral->cl_type)?$collateral->cl_type:''];
															?> </b>
														</div>
														<?php if($collateral->size) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("size"); ?></span>:
															<b> <?php echo isset($collateral->size)?$collateral->size:'';?> </b>
														</div> 
														<?php } ?>
														<?php if($collateral->adj_south) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("adjacent_south"); ?></span>:
															<b> <?php echo isset($collateral->adj_south)? $collateral->adj_south:'';?> </b>
														</div>
														<?php } ?>
														<?php if($collateral->adj_west) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("adjacent_west"); ?></span>:
															<b> <?php echo isset($collateral->adj_west)?$collateral->adj_west:'';?> </b>
														</div>
														<?php } ?>
														<?php if($collateral->wall) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("wall"); ?></span>:
															<b> <?php echo isset($collateral->wall)?$collateral->wall:'';?> </b>
														</div>
														<?php } ?>
														
														<!--<?php if($collateral->address) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("address"); ?></span>:
															<?php echo isset($collateral->address)?$collateral->address:'';?>
														</div>-->
														<?php } ?>
														
														<?php if($collateral->address) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("address"); ?></span>:
															<b> <?php 
																$cl_address = '';
																if(isset($collateral->address)) {
																	$cl_address = str_replace('<p>', '', $collateral->address);
																	$cl_address = str_replace('</p>', '', $cl_address);
																}
																echo $cl_address;
															?> </b>
														</div>
														<?php } ?>
														
														<!--vehicles-->
														<?php if($collateral->color) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("color"); ?></span>:
															<b> <?php echo isset($collateral->color)?$collateral->color:'';?> </b>
														</div>
														<?php } ?>
														<?php if($collateral->brand) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("brand"); ?></span>:
															<b> <?php echo isset($collateral->brand)?$collateral->brand:'';?> </b>
														</div>
														<?php } ?>
														<?php if($collateral->frame_no) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("frame_number"); ?></span>:
															<b> <?php echo isset($collateral->frame_no)?$collateral->frame_no:'';?> </b>
														</div>
														<?php } ?>
														<?php if($collateral->issue_date) { ?>
														<div class="form-group">
															<span style="float:left;width:130px;"><?= lang("issue_date"); ?></span>:
															<b> <?php echo isset($collateral->issue_date)?$collateral->issue_date:'';?> </b>
														</div>
														<?php } ?>
													</div>
												</div> <hr>
												<?php
													}
												?>
											</div>
										</div>
									</div>
								</div>
									
									<div id="documents" style="display: none;" class="tab-pane fade">
								        <div class="modal-body">
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_current_address_name){ ?>
													<div class="col-md-12" style="padding-top:29px;">
														<label for="document"> <?=lang("current_address")?></label><a href="<?php echo $dc_current_address ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
													
														<?php if($dc_family_book_name){ ?>
													<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?= lang("family_book") ?></label><a href="<?php echo $dc_family_book ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>
											
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_gov_id_name){ ?>
													
													<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?=lang("government_id")?></label><a href="<?php echo $dc_gov_id ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>
											
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_house_photo_name){ ?>
													<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?=lang("house_photo")?></label> <a href="<?php echo $dc_house_photo ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>
											
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_store_photo_name){ ?>
													<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?=lang("store_photo")?></label> <a href="<?php echo $dc_store_photo ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>
											
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_employment_certificate_name){ ?>
														<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?=lang("employment_certificate")?></label> <a href="<?php echo $dc_employment_certificate ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_applicant_photo_name){ ?>
														<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?=lang("applicant_photo")?></label> <a href="<?php echo $dc_applicant_photo ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_spouse_photo_name){ ?>
														<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?=lang("spouse_photo")?></label> <a href="<?php echo $dc_spouse_photo ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_guarantors_photo_name){ ?>
														<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?=lang("guarantors_photo")?></label> <a href="<?php echo $dc_guarantors_photo ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_birth_registration_letter_name){ ?>
														<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?=lang("birth_registeration_letter")?></label> <a href="<?php echo $dc_birth_registration_letter ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_passport_name){ ?>
														<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?=lang("passport")?></label> <a href="<?php echo $dc_passport ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_marriage_certificate_name){ ?>
														<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?=lang("marriage_certificate")?></label> <a href="<?php echo $dc_marriage_certificate ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_driver_license_name){ ?>
														<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?=lang("driver_license")?></label> <a href="<?php echo $dc_driver_license ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_working_contract_name){ ?>
														<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?=lang("working_contract")?></label> <a href="<?php echo $dc_working_contract ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_invoice_salary_name){ ?>
														<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?=lang("invoice_salary")?></label> <a href="<?php echo $dc_invoice_salary ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_business_certificate_name){ ?>
														<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?=lang("business_certificate")?></label> <a href="<?php echo $dc_business_certificate ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_profit_for_the_last_3_month_name){ ?>
														<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?=lang("profit_for_the_last_three_months")?></label> <a href="<?php echo $dc_profit_for_the_last_3_month ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>
													<?php } ?>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<?php if($dc_other_dc_name){ ?>
													<div class="col-md-12" style="padding-top:29px;">
														<label for="document"><?=lang("other_ducument")?> </label><a href="<?php echo $dc_other_dc ?>" target="_blank" class="btn btn-warning form-control"><i class="fa fa-download"></i> <?php echo lang('download') ?></a>
													</div>	
													<?php } ?>	
												</div>								
											</div>
								        </div>
										<div class="clearfix"></div>
									</div>
									
									<div id="fields_check" style="display:none;" class="tab-pane fade">
											<div class="modal-body">
												
												<!-- Fields Check -->
												
												<p><?= lang("the_current_address_base_on_documents_provide_below") ?></p>
												<div class="row">
													<div class="col-md-12 col-lg-12">
														<div class="col-md-3 col-sm-6">
															<input type="checkbox" disabled name="fc_id_card" id="id_card" <?php if(isset($field_check->govid)?$field_check->govid:''==1){echo 'checked';} ?> > <?= lang("identify_card") ?>
														</div>	
														<div class="col-md-4 col-sm-6">
															<input type="checkbox" disabled name="fc_family_book" id="family_book" <?php if($field_check?$field_check->family_book:''==1){echo 'checked';}?> > <?= lang("family_book") ?>
														</div>
														<div class="col-md-3 col-sm-6">
															<input type="checkbox" disabled name="fc_staying_book" id="staying_book" <?php if($field_check?$field_check->place_book:''==1){echo 'checked';}?> > <?= lang("address_book") ?>
														</div>
														<div class="col-md-3 col-sm-6">
															<input type="checkbox" disabled name="fc_water_invoice" id="water_invoice" <?php if($field_check?$field_check->water_letter:''==1){echo 'checked';}?> > <?= lang("water_bill") ?>
														</div>
														<div class="col-md-4 col-sm-6">
														<input type="checkbox" disabled name="fc_electricity_invoice" id="electricity_invoice" <?php if(isset($field_check->edc_letter)?$field_check->edc_letter:''==1){echo 'checked';}?> > <?= lang("electrical_bill") ?>
														</div>
														<div class="col-md-4 col-sm-6">		
															<input type="checkbox" disabled name="fc_check_property" id="check_property" <?php if(isset($field_check->property_check_letter)?$field_check->property_check_letter:''==1){echo 'checked';}?> > <?= lang("asset_certificate") ?>
														</div>
														<div class="col-md-7 col-sm-6">		
															<input type="checkbox" disabled name="fc_check_landlord" id="check_landlord" <?php if(isset($field_check->claim_letter)?$field_check->claim_letter:''==1){echo 'checked';}?> > <?= lang("chief_of_village_certify_letter") ?>
														</div>
														<div class="col-md-5">	
															<div class="col-md-4" style="padding-left: 0px; padding-right: 0px;">	
															<input type="checkbox" disabled name="fc_other" id="other" <?php if(isset($field_check->other)?$field_check->other:''==1){echo 'checked';}?> ><?= lang("other") ?><span style="margin-left:10px;">:</span>
															</div>
															<div class="col-md-8" style="padding-left: 0px; padding-right: 0px;">
																<?= isset($field_check->other_note) ?$field_check->other_note  : (''); ?>
															</div>
														</div>
														<div>
																<div class="col-md-12"><p> <?= lang("current_address") ?> <span style="margin-left:10px;">:</span><?=isset($field_check->requestor_curr_address) ?$field_check->requestor_curr_address  : ('');?></p></div>
															
														</div>
														<div>
															<div class="col-md-12"><p> <?= lang("phone_number") ?> <span style="margin-left:60px;">:</span><?=isset($field_check->requestor_phone) ?$field_check->requestor_phone  : ('');?></p></div>	
														</div>
													</div>
												</div>
												<div class="row">
												<br/>
												
												<iframe width="100%" height="350px" src = "https://maps.google.com/maps?q=<?=$field_check->latitude?>,<?=$field_check->longitude?>&hl=es;z=20&amp;output=embed"></iframe>
												
												<br/>
												
												</div>
												<!-----field_check
												<div class="row" style="display:none;">
													<div class="col-md-12 col-lg-12">
														<p>បរិយាយកាសការងារ</p>
															<div class="col-md-2">
																<input type="checkbox" disabled name="fc_business1" id="business1" <?php if(isset($field_check->business1)?$field_check->business1:''==1){echo 'checked';}?> >ជំនួញ
															</div>
															<div class="col-md-2">
															<input type="checkbox" disabled name="fc_company1" id="company1" <?php if(isset($field_check->company1)?$field_check->company1:''==1){echo 'checked';}?> >ក្រុមហ៊ុន
															</div>
															<div class="col-md-2">
															<input type="checkbox" disabled name="fc_other1" id="other1"  <?php if(isset($field_check->other1)?$field_check->other1:''==1){echo  'checked';}?> >ផ្សេងៗ
															</div>
															<div>
																<div class="col-md-2"><p>ឈ្មោះ :</p></div>
																<div class="col-md-3"><?= isset($field_check->name)?$field_check->name:''; ?></div>
															</div>
														</div>	
														<div class="col-md-12 col-lg-12">
															<div class="col-md-2">
																<input type="checkbox" disabled name="fc_business2" id="business2"  <?php if(isset($field_check->business2)?$field_check->business2:''==1){echo 'checked';} ?> >ជំនួញ
															</div>
															<div class="col-md-2">
															<input type="checkbox" disabled name="fc_company2" id="company2" <?php if(isset($field_check->company2)?$field_check->company2:''==1){echo 'checked';}?> >ក្រុមហ៊ុន
															</div>
															<div class="col-md-2">
															<input type="checkbox" disabled name="fc_other2" id="other2" <?php if(isset($field_check->other2)?$field_check->other2:''==1){echo 'checked';}?> >ផ្សេងៗ
															</div>
															<div>
																<div class="col-md-2"><p>លេខទូរសព្ទ :</p></div>
																<div class="col-md-3"><?=  isset($field_check->phone)?$field_check->phone:''; ?></div>
															</div>
														</div>	
														<div class="col-md-12 col-lg-12">
															<div class="col-md-2">
																<input type="checkbox" disabled name="fc_business3" id="business3" <?php if(isset($field_check->business3)?$field_check->business3:''==1){echo 'checked';} ?> >ជំនួញ
															</div>
															<div class="col-md-2">
															<input type="checkbox" disabled name="fc_company3" id="company3" <?php if(isset($field_check->company3)?$field_check->company3:''==1){echo  'checked';}?> >ក្រុមហ៊ុន
															</div>
															<div class="col-md-2">
															<input type="checkbox" disabled name="fc_other3" id="other3" <?php if(isset($field_check->other3)?$field_check->other3:''==1){echo 'checked';} ?> >ផ្សេងៗ
															</div>
															<div>
																<div class="col-md-2"><p>អាសយដ្ឋាន​បច្ចុប្បន្ន:</p></div>
																<div class="col-md-3"><?= isset($field_check->address)?$field_check->address :'';?></div>
															</div>
														</div>	
														<div class="col-md-12 col-lg-12">
															<div class="col-md-2">
																<input type="checkbox" disabled name="fc_business4" id="business4" <?php if(isset($field_check->business4)?$field_check->business4:''==1){echo 'checked';}?> >ជំនួញ
															</div>
															<div class="col-md-2">
															<input type="checkbox" disabled name="fc_company4" id="company4" <?php if(isset($field_check->company4)?$field_check->company4:''==1){echo  'checked';}?> >ក្រុមហ៊ុន
															</div>
															<div class="col-md-2">
															<input type="checkbox" disabled name="fc_other4" id="other4" <?php if(isset($field_check->other4)?$field_check->other4:''==1){echo 'checked';}?> >ផ្សេងៗ
															</div>
															<div class="col-md-12" style="padding-top:10px;">
																<div class="col-md-12"><p>ម៉ោងធ្វើកា​រពី <?= isset($field_check->start_work)?$field_check->start_work:''; ?> ដល់ <?= isset($field_check->end_work)?$field_check->end_work:''; ?></p></div>
										
															</div>
														</div>
														<div class="col-md-12 col-lg-12">
															<div class="col-md-12" style="padding-top:10px;">
																<div class="col-md-12"><p>ធ្វើការ <?= isset($field_check->hours) ?$field_check->hours  : (''); ?> ថ្ងៃក្នុងមួយសប្តាហ៍</p></div>
															
															</div>
														</div>
												</div>---->
									
											</div>
												<div class="row">
													<div class="col-md-12">
														<p><?= lang("approval_note") ?>:</p>
														<div class="col-md-2">
															<input type="checkbox" disabled name="fc_evaluate"  id="evaluate" <?php if(isset($field_check->go_there)?$field_check->go_there:''==1){echo 'checked';}?> ><?= lang("filed_check")?>
														</div>
														<div class="col-md-2">
															<input type="checkbox" disabled name="fc_none_evaluate"  id="none_evaluate" <?php if(isset($field_check->not_go_there)?$field_check->not_go_there:''==1){echo 'checked';}?> > <?= lang("no_filed_check")?>
														</div>
														<div class="col-md-3">
															<p> <?= lang("co_name")?>  :  &nbsp;<b><?=isset($field_check->official_name) ?$field_check->official_name  : ('');?></b></p>
														</div>
														<div class="col-md-3">
															<p> <?= lang("phone_number")?> : &nbsp;<b><?=isset($field_check->official_phone) ?$field_check->official_phone  : ('');?></b></p> 
														</div>
														<!--<div class="table-responsive">	
															<table class="table">
																<tbody>
																  <tr>
																	<td><input type="checkbox" disabled name="fc_evaluate"  id="evaluate" <?php if(isset($field_check->go_there)?$field_check->go_there:''==1){echo 'checked';}?> >ចុះវាយតំលៃ</td>
																	<td><input type="checkbox" disabled name="fc_none_evaluate"  id="none_evaluate" <?php if(isset($field_check->not_go_there)?$field_check->not_go_there:''==1){echo 'checked';}?> >មិនចុះវាយតំលៃ</td>
																	<td style="width:14%; text-align:center;"><p>ឈ្មោះមន្រ្តីចុះវាយតម្លៃ :</p></td>
																	<td style="text-align:left;"><?=isset($field_check->official_name) ?$field_check->official_name  : ('');?></td>
																	<td style="width:8%;"><p>លេខទូរស័ព្ទ :</p></td>
																	<td style="text-align:left;"><?=isset($field_check->official_phone) ?$field_check->official_phone  : ('');?></td>
																  </tr>
																</tbody>
															</table>
														</div>-->
													</div>
												</div>
										</div>
									
									<div id="comments" style="display: none;" class="tab-pane fade">
								        <div class="modal-body">
											<div class="comment-wrap">
												<div class="comment-list">
													
												</div>
												<div class="comment-text">
													<input type="hidden" class="comment-quote-id" value="<?php echo isset($id) ?>">
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
									
									<?php if ($GP['advance-approve'] || $GP['quotes-pending_for_PO'] || $GP['quotes-approve'] || $this->Admin || $this->Owner){ ?>
										<div class="tab-pane">
											<input type="submit" class="btn btn-primary" value="<?=lang('submit')?>" name="submitQoute" />
										</div>
									<?php } ?>
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
		$(".ch_services").trigger('ifChanged');
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
			$('.total_services_charge').text(formatMoney(total));
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
		$('#interest_rate, #term, #grand_amount').on('change', function() {
			var interest = parseFloat($('#interest_rate').val());
			var term = Number($('#term').val());
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
		$(window).load(function(){
			$('#qstatus').trigger('change');
		});
		$('#reject_reason').hide();	
		$('#qstatus').on('change', function() {		
			if($('#qstatus').val()=="rejected"){					
				$('#reject_reason').show();
				
			}
			if($('#qstatus').val()!="rejected"){			
				$('#reject_reason').hide();				
			}
		});
		
		$('.btn_print_payment_schedule_cash').on( "click", function() {
			var services = '';
			$(".ch_services:checked").each(function(){	
				var s_id = $(this).val();
				var amount = $(this).attr('amount');
				var type = $(this).attr('status');
				var service_paid = $(this).attr('service_paid');
				//var title = $(this).attr('title');
				if(services == '') {
					services = s_id +"__" + amount + "__" + type +"__" + service_paid ;
				}else {
					services += "___" + s_id +"__" + amount + "__" + type +"__" + service_paid ;
				}
			});
			if(services=='') {
				
				//window.location.href="<?=site_url('Quotes/cash_payment_schedule_preview/'.$lease_amount.'/'. $inv->rate_type .'/'. $inv->interest_rate .'/'. $inv->term .'/'. $inv->frequency) ?>" ;
			}
			
		});
	});
	
</script>

