<link href="https://fonts.googleapis.com/css?family=Battambang" rel="stylesheet"> 
 <?php
	$sale->total = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $sale->total);
	//$this->erp->print_arrays($last_payment);
?>
 <style type="text/css">
    .container {
        width: 800px;
        margin-left: auto;
        margin-right: auto;
	}	
	.t_c{text-align:center;}
	.t_r{text-align:right;}
    @media print
	{    
		.no-print, .no-print *
		{
			display: none !important;
		}
		
	}
	.kh_m{
		font-family: "Khmer OS Muol";
	}
	.b_top{
		border-top:1px solid black;
		margin-bottom: 20px;
		max-width: 100%;
		width: 100%;
		}
	.b_bottom{border-bottom:1px solid black}
	.b_left{border-left:1px solid black;}
	.b_right{border-right:1px solid black;}
	.text-bold td{font-weight:bold;}
	.p_l_r td{padding-left:5px;padding-right:5px;}
	.top_info tr td{
		height:25px;
	}
	.color_blue{color:#3366cc;}
	.color_blue{color:#3366cc;}
	#logo img{
		width:110px;
	}
	.table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td{
		border:none;
		border: 1px ;
		padding:4px;
	}
	
	
</style>


<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('payments'); ?></h2>
    </div>
    <div class="box-content"
        <div class="row">

				<div class="box-content">
					<div class="row">
						<div class="col-md-12">							
							<div class="tab-content">
								<div>
									<div style="float:left;width:25%;" id="logo">
										<span> <?php if ($Settings->logo2) {
										echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" style="margin-bottom:10px;" />';
										} ?> </span> 
									</div>
									<div style="float:left;width:50%; font-family:Battambang;">	
										<center><b>
											<span class="kh_m"><b> <?php echo $setting->site_name ?> </b></span><br/>
											<span> <?= lang("branch_company_name") ?> : <?= $this->session->branchName; ?></span><br/>
											<span style="font-size:18px;;"> <?= lang("installments") ?> </span><br/>
										</center></b>
									</div>
									<div style="float:left;width:25%;">
										<center><span style="line-height:140%; font-size:12px;"><?=lang("agree_to_pay_by_schedule")?> <br/><?=lang("date") ?>: <?= $this->erp->hrsd(date('Y-m-d')); ?><br/><?= lang("right_thumbprints") ?></span></center>
									</div>
								</div>
								
								<div>
									
									<table class="b_top" style="font-size:11px; border:none">
										<tbody>
										  <tr>
											<td>  <?= lang("customer_name") ?></td>
											<td class="color_blue">: <b><?php echo $customer->family_name_other.' '.$customer->name_other; ?></b></td>
											<td>  <?= lang("customer_latin_name") ?></td>
											<td class="color_blue">: <?=$customer->family_name.' '.$customer->name;?></td>
											<td rowspan="5" style="width:90px; border:1px solid grey;"></td>
											<td rowspan="5" style="width:90px; border:1px solid grey;"><?php echo '<img src="' . base_url() . 'assets/uploads/documents/' . ($qphoto->name?$qphoto->name:'male.png') .'"  style=" width:90px; height:110px;" id="inputimg"/> '?></td>
										  </tr>
										  <tr>
											<td><?= lang("account_number") ?></td>
											<td class="color_blue">: <?=$sale->reference_no;?> </td>
											<td><?= lang("c_o_name") ?></td>
											<td class="color_blue">: <?= $creator->first_name . ' ' . $creator->last_name ; ?></td>
											
										  </tr>
										  <tr>
											<td><?= lang("phone1") ?></td>
											<td class="color_blue">: <b> <?= $customer->phone1; ?> </b></td>
											<td><?= lang("c_o_phone") ?></td>
											<td class="color_blue">: <?= $creator->phone; ?> </td>
											
										  </tr>
										  <tr>
											<td><?= lang("disburse_date") ?></td>
											<td class="color_blue">: <?= $this->erp->hrsd($sale->approved_date); ?></td>
											<td><?= lang("part") ?></td>
											<td class="color_blue">: 1</td>
											
										  </tr>
										  <tr>
											<td><?= lang("payments_times") ?></td><td class="color_blue">: <?= number_format(($sale->term / $sale->frequency),0); ?>  <?= lang("times") ?></td>
											<td><?= lang("payment_frequency") ?></td>
											<td class="color_blue">: <?= number_format($sale->frequency,0); ?>  <?= lang("days_times") ?> </td>
											
											
										  </tr>
										  <tr>
											<td><?= lang("term_loan") ?></td>
											<td class="color_blue">: <?= number_format($sale->term,0); ?> <?= lang("days") ?></td>
											<td><?= lang("penalty") ?></td>
											<td class="color_blue">: <?=(($setting->penalty_types == 'Percentage')? ($setting->penalty_amount*100).'%' : $this->erp->formatMoney($setting->penalty_amount)) ?>​​​​​​​​ <?= $stcurrency->name ?></td>
											
										  </tr>
										  <tr>
											<td><?= lang("total_balance_schedule") ?></td>
											<td class="color_blue">: <?= $this->erp->formatMoney($sale->total) .' '. $currency->name_other; ?></td>
											<td><?= lang("collateral_schedule") ?></td>
											<td class="color_blue">: <?php  echo $collateraltype->type ?></td>
											<td style="text-align:center"><b><?php echo $customer->family_name_other.' '.$customer->name_other; ?></b></td>
											<td style="text-align:center"><b><?= lang("photo") ?></b></td>
										  </tr>
										  <tr>
											<td><?= lang("interest_rate_schedule") ?></td>
											<td class="color_blue">: <?= $this->erp->formatDecimal($sale->interest_rate * 100); ?>%</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										  </tr>
										<?php
											foreach ($services as $service){	
												if ($service->service_paid==1){
													$one = 0;
													if($service->method =="Percentage"){
														$one = $service->amount * $sale->total ;												
													}else{
														$one = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $service->amount);
													}
													$one_ = $one + ($one * $service->tax_rate);
													echo'<tr>																																	
															<td> '.  $service->description .' </td> 
															<td class="color_blue">: '.$this->erp->roundUpMoney($one_, $sale_item->currency_code).' '. $currency->name_other.'</td> 																		
															<td></td>
															<td></td>
															<td></td>
															<td></td>
														</tr>';
												}	
											}
										?>
										  <tr>
											<td><?= lang("address_approved") ?></td>
											<td colspan="4">: <?='#'.$customer->house_no;?></td>
											<td></td>
										  </tr>
										</tbody>
									  </table>
								</div>					
								<table style="font-size:11px;border-collapse:collapse;width:100%;" class="schedule" border="0" >
									<tr class="p_l_r" style="background-color:#009900;color:white; width:100%;">
										<?php 
											$days = $stdays->days;
											$DiffDate = $diffday->DiffDate ? $diffday->DiffDate:'0';
											if($DiffDate > $days){
												echo '<td class="t_c" style="width: 5%;"> <div style="background-color: white; width:22px; text-align:center; margin-left:30px;"><input type="checkbox" name="all_check" id="all_check"></div></td>' ;
											} else{
												echo '<td class="t_c" style="width: 5%;"> <div style="background-color: white; width:22px; text-align:center; margin-left:30px; pointer-events: none;"><input type="checkbox" name="all_check" id="all_check"></div></td>' ;
											}
										?>							
										<td  class="t_c" style="width: 5%;"> <?= lang("no") ?> </td>
										<td  class="t_c" style="width: 10%;"><?= lang("intallment_date") ?></td>
										<td  class="t_c" style="width:  10%;"><?= lang("principle_paid") ?></td>
										<td  class="t_c" style="width:  10%;"><?= lang("interest_paid") ?></td>
										<?php
											foreach ($services as $service){
												if($service->service_paid==2) { 
													echo '<td class="t_c" style="padding-top:10px;width:  10%;"> '. $service->description.' </td>';
												}
												if($service->service_paid==3) { 
													echo '<td class="t_c" style="padding-top:10px;width:  10%;">'.  $service->description .'</td>';
												}
												 
												if($service->service_paid==4) {
													echo '<td class="t_c" style="padding-top:10px;width:  10%;"> '. $service->description .'</td>';
												} 
											}
										?>
										<td class="t_c" style="width:10%;"><?= lang("principle_balance") ?></td>
										<td class="t_c" style="width:10%;"><?= lang("total_intallment") ?></td>
										<td class="t_c" style="width:7%;"><?= lang("saving_interest") ?></td>
										<td class="t_c" style="padding: 5px; width:10%;"><?= lang("action") ?></td>
									</tr>						
									<?php
										//$this->erp->print_arrays($stcurrency);
										$total_principle = 0;
										$total_interest = 0;
										$total_payment = 0;
										$total_alls = 0 ;
										$total_haft = 0 ;
										$total_insurence = 0;
										$total_pay = 0;
										$countrows = count($countloans);
										$countrow  = count($countloans) /2;
										$counter = 1;
										
										if(array($loan)) {
											foreach($loan as $pt){
												$princ=$this->erp->formatMoney($pt->principle);
												$interest=$this->erp->formatMoney($pt->interest);									
												$overdue_amt = (($pt->paid_amount > 0)? $pt->overdue_amount : 0);
												$payment = $pt->payment + $overdue_amt;
												$paid = $pt->paid_amount? $pt->paid_amount : 0;
												$other_paid = $pt->other_amount? $pt->other_amount : 0;
												$services_charge = $pt->total_service_charge? $pt->total_service_charge : 0;
												$paid_amount = $paid + $other_paid + $services_charge + (($pt->paid_amount > 0)? $overdue_amt : 0);
												$balance = $payment - $paid_amount;
												$balance_moeny = $this->erp->formatMoney($pt->balance);
												
												$Principles = $this->erp->roundUpMoney($pt->principle, $sale_item->currency_code);
												$interests = $this->erp->roundUpMoney($pt->interest, $sale_item->currency_code);
												$saving_interest = $this->erp->roundUpMoney($pt->saving_interest, $sale_item->currency_code);
												
											echo '<tr class="row-data" '.(($pt->paid_amount > 0)? 'style="background-color:#B4D8E8;"':'').' style="width:100%;">';
											//<!--<td class="t_c" ><input type="checkbox" name="ch_check[]" class="ch_check" value="'.(( $pt->paid_amount == 0 || $pt->owed > 0)? $pt->id:'').'" '.(($pt->paid_amount > 0)? 'checked':'') .'></td> -->
											//<td class="t_c" style="width: 5%;"> <input type="checkbox" name="ch_check[]" style"margin-left:30px;" class="ch_check" value="'.(($pt->paid_amount == 0)? $pt->id:'').'" '.(($pt->paid_amount > 0)? 'checked':'') .'></td>';
											if($pt->paid_amount == 0){
												echo '<td class="t_c" style="width: 5%;"> <input type="checkbox" name="ch_check[]" style"margin-left:30px;" class="ch_check" value="'.$pt->id.'"></td>';
											}else if($pt->paid_amount > 0){
												echo '<td class="t_c" style="width: 5%; pointer-events: none;"> <input type="checkbox" name="chekedbox" style"margin-left:30px;" class="chekedbox" value="'.$pt->id.'" '.'checked'.' readonly></td>';
											}
											echo'
												<td class="t_c" style="padding-left:5px; padding-right: 5px; height: 25px; width:5%" >'. $pt->period .'</td>
												<td class="t_c" style="padding-left:5px; padding-right:5px; width:10%">'. $this->erp->hrsd($pt->dateline) .'</td>
												<td class="t_c" style="padding-left:5px; padding-right:5px; width:10%">'. $Principles .'</td>
												<td class="t_c" style="padding-left:5px; padding-right:5px; width:10%">'. $interests .'</td>';
												$balances = (($pt->balance > 0)? $pt->balance : 0);
												$balances = str_replace(',', '', $this->erp->roundUpMoney($balances, $sale_item->currency_code));
												$principle_amt = str_replace(',', '', $Principles);
												$interest_amt = str_replace(',', '', $interests);
												$payment_amt = $principle_amt + $interest_amt;
												$loan_balance = $balances + $principle_amt;
												$haft_paid = 0;
												$insurences_paid = 0;
												$all_paid = 0;
												foreach($services as $service){
													if ($service->service_paid==2){	
														$haft = 0 ;
														if($service->method =="Percentage"){
															$haft = ($service->charge_by == 2)? ($service->amount * $loan_balance): (($service->charge_by == 3)? ($service->amount * $payment_amt ) : $service->amount * $sale->total ) ;
														}else{
															$haft = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $service->amount);
														}
														$haft_ = $haft + ($haft * $service->tax_rate);
														$haft_service_paid = $this->erp->roundUpMoney($haft_, $sale_item->currency_code);
														$haft_paid += str_replace(',', '', $haft_service_paid);
														echo'<td class="t_c" style="padding-left:5px; padding-right:5px; width:10%">'. (($pt->period >= 1 && $pt->period <= $countrow)? $haft_service_paid:'0.00') .'</td>';
													}
													else if ($service->service_paid==3){
														$alls = 0;
														if($service->method =="Percentage"){
															$alls = ($service->charge_by == 2)? ($service->amount * $loan_balance): (($service->charge_by == 3)? ($service->amount * $payment_amt ) : $service->amount * $sale->total ) ;
														}else{
															$alls = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $service->amount);
														}	
														$alls_ = $alls + ($alls * $service->tax_rate);
														$all_service_paid = $this->erp->roundUpMoney($alls_, $sale_item->currency_code);
														$all_paid += str_replace(',', '', $all_service_paid);
														echo'<td class="t_c" style="padding-left:5px; padding-right:5px; width:10%">'. $all_service_paid .'</td>';
													}
													else if ($service->service_paid==4){
														$insurence = 0 ;
														if($service->method =="Percentage"){
															$insurence = ($service->amount * $sale->total) / $countrow;	
														}else{
															$insurence = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $service->amount) /$countrow;
														}
														$insurence_ = $insurence + ($insurence * $service->tax_rate);
														$insurence_paid = $this->erp->roundUpMoney($insurence_, $sale_item->currency_code );
														$insurences_paid += str_replace(',', '', $insurence_paid);
														echo'<td class="t_c" style="padding-left:5px;padding-right:5px; font-weight:normal width:10%">'. (($pt->period >= 1 && $pt->period <= $countrow)? $insurence_paid:'0.00') .'</td>';
													}
												}
												$Principles_amount = str_replace(',', '', $Principles);
												$interests_amount = str_replace(',', '', $interests);
												if($pt->period >= 1 && $pt->period <= $countrow){
													$payment = $Principles_amount + $interests_amount + $all_paid + $haft_paid + $insurences_paid;
												}else{
													$payment = $Principles_amount + $interests_amount + $all_paid;
												}
												$balances = (($pt->balance > 0)? $pt->balance : 0);
											echo '<td class="t_c" style="padding-left:5px;padding-right:5px; width:10%">'. $this->erp->roundUpMoney($balances, $sale_item->currency_code) .'</td>
												  <td class="t_c" style="padding-left:5px;padding-right:5px; width:10%">'. $this->erp->roundUpMoney($payment, $sale_item->currency_code) .'</td>
												  <td class="t_r" style="padding-left:5px;padding-right:25px;font-weight:normal; text-align:right">'. $saving_interest  .'</td>
												 ';	

												if ($pt->id <= $last_payment->loan_id){
													if ($pt->id < $last_payment->loan_id){
														$re_payment ='&ensp; &ensp;';
													}													
													if($pt->id == $last_payment->loan_id && $last_payment->owed > 0){
														if ($Owner || $Admin || $this->permission['payment-add']){
														$re_payment = anchor('Installment_payment/re_payments/'.$pt->id.'/'.$sale->id, '<i class="fa fa-plus-square" style="font-size:18px; color:#1E90FF;" title="​'. lang("re_payments") .'"></i>','data-toggle="modal" data-target="#myModal"');
														}
													}
													if ($Owner || $Admin || $this->permission['payment-index']){
														$view_payment = anchor('Installment_payment/view_payments/'.$pt->id.'/'.$sale->id,'<i class="fa fa-money" style="font-size:18px; color:#1E90FF;" title="​'. lang("view_payments") .'"></i>','data-toggle="modal" data-target="#myModal"');
													}
												}
												if ($pt->id > $last_payment->loan_id){
													$re_payment   = "";
													$view_payment = "";													
												}
    												
											echo '<td class="t_c" id="hide_action2" style="padding-left:5px;padding-right:5px; width:10%"> '. $re_payment .' &ensp; '. $view_payment .'</td>
											
											</tr>';
											
											$total_principle += str_replace(',', '', $Principles);
											$total_interest += str_replace(',', '', $interests);
											$total_payment += $payment;									
											$total_alls += str_replace(',', '', $all_service_paid);
											$total_haft += str_replace(',', '', $haft_service_paid)/2 ;
											$total_insurence += str_replace(',', '', $insurence_paid) /2 ;
											$total_saving += str_replace(',', '', $saving_interest);
											$total_pay = ($total_payment);
											}
										}
									?>	
									<tr class=" text-bold">
										<td class="t_c" style="padding-left: 5px; padding-right: 5px; height: 25px;" colspan="3"><?= lang("total_schedule") ?></td>
										<td class="t_c" style="padding-left:5px;padding-right:5px;"><?= $this->erp->roundUpMoney($total_principle, $sale_item->currency_code); ?></td>
										<td class="t_c" style="padding-left:5px;padding-right:5px;"><?= $this->erp->roundUpMoney($total_interest, $sale_item->currency_code); ?></td>
										<?php
											foreach ($services as $service){
												if($service->service_paid==2) {
													//echo '<td class="t_r" style="padding-left:5px;padding-right:5px;"> '.$this->erp->roundUpMoney($total_haft, $sale_item->currency_code) .' </td>';
													echo '<td class="t_r" style="padding-left:5px;padding-right:5px;">   </td>';
												}
												if($service->service_paid==3) { 
													//echo '<td class="t_r" style="padding-left:5px;padding-right:5px;"> '.$this->erp->roundUpMoney($total_alls, $sale_item->currency_code) .' </td>';
													echo '<td class="t_r" style="padding-left:5px;padding-right:5px;">   </td>';
												}
												if($service->service_paid==4) { 
													//echo '<td class="t_r" style="padding-left:5px;padding-right:5px;">  '.$this->erp->roundUpMoney($total_insurence, $sale_item->currency_code) .'</td>';
													echo '<td class="t_r" style="padding-left:5px;padding-right:5px;">   </td>';
												}
											}
										?>
										
										<td></td>
										<td class="t_c" style="padding-left:5px;padding-right:5px;"><?= $this->erp->roundUpMoney($total_pay, $sale_item->currency_code); ?></td>
										<td class="t_r" style="padding-left:5px;padding-right:25px; text-align:right"><?= $this->erp->roundUpMoney($total_saving,$sale_item->currency_code); ?></td>
										<td></td>
										
									</tr>
									
								</table>
								<div style="margin-top: 10px; margin-bottom: 10px;">
									<table style="font-size:11px;">
										<tr>
											<td style="width:110px;"><b> <?= lang("note") ?>:</b> <td>
											<td><?= lang("payment_note")?><td>
										</tr>
										<tr>
											<td><td>
											<td> <?=lang("the_contract_does_not_comply")?> <b><?php echo $setting->site_name ?> </b> &nbsp <?= lang("company_will_take_legal_action") ?></td>
										</tr>
										
									</table>
								</div>	
							</div>
						</div>

					</div>
				</div>
				
				
				<div class="buttons">
					<div class="btn-group btn-group-justified no-print">
						<?php if ($Owner || $Admin || $this->permission['payment-add']) { ?> 
							<div class="btn-group">
								<a href="#" data-toggle="modal" data-target="#myModal" class="add_payment tip btn btn-primary" id="add_payment" title="<?= lang('add_payment') ?>">
									<i class="fa fa-money"></i>
									<span class="hidden-sm hidden-xs"><?= lang('add_payment') ?></span>
								</a>
							</div>
						<?php } ?>
						<div class="btn-group">
							<a href="#" data-toggle="modal" data-target="#myModal" class="change_date tip btn btn-primary" title="<?= lang('change_date') ?>">
								<i class="fa fa-edit"></i>
								<span class="hidden-sm hidden-xs"><?= lang('change_date') ?></span>
							</a>
						</div>
						<div class="btn-group">
							<a href="#" data-toggle="modal" data-target="#myModal" class="pdf tip btn btn-primary" id="pdf" title="<?= lang('add_payment') ?>">
								<i class="fa fa-money"></i>
								<span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
							</a>
						</div>
						<div class="btn-group">
							<a href="#" data-toggle="modal" data-target="#myModal" class="excel tip btn btn-primary" id="excel" title="<?= lang('add_payment') ?>">
								<i class="fa fa-money"></i>
								<span class="hidden-sm hidden-xs"><?= lang('excel') ?></span>
							</a>
						</div>
						<div class="btn-group">
							<a class="tip btn btn-warning" title="<?= lang('print') ?>" onclick="window.print();">
								<i class="fa fa-print"></i>
								<span class="hidden-sm hidden-xs"><?= lang('print') ?></span>
							</a>
						</div>
					</div>
				</div>
				
				
			
		</div>
	</div>
	</div>
</div>

<script>
	
	$(document).ready(function() {
		$('#all_check').on('ifChanged', function(){
			if($(this).is(':checked')) {
				$('.ch_check').each(function() {
					$(this).iCheck('check');
				});
			}else{
				$('.ch_check').each(function() {
					$(this).iCheck('uncheck');
				});
			}
		});		
		$('#add_payment').on('click', function() {
			if($(".schedule .ch_check:checked").length > 0){
				var sale_id = <?= $sale->id; ?>;
				var i = 0;
				var loans_id = '';
				$(".schedule .ch_check:checked").each(function(){	
					if($(this).val()) {
						if(i == 0) {
							loans_id = $(this).val();
						}else{
							loans_id += '_'+$(this).val();
						}
						i += 1;
					}
				});
				//alert(loans_id);
				if(loans_id){
					$(this).attr('href', "<?= site_url('Installment_payment/add_payment/') ?>/" + sale_id +'/'+loans_id);
					
				}else{
					alert("Please check..");
					return false;
				}
			}else {
				alert("Please check..");
				return false;
			}
		});
		
		$(".change_date").bind('click',function(){
			var id = '';
			if($(".ch_check:checked").length > 0){
				
				$(".ch_check:checked").each(function(){	
					var parent = $(this).parent().parent().parent().parent();
					id += $(this).val() +'_';
				});
				$(this).attr('href', "<?= site_url('Installment_payment/changePaymentDate') ?>/"+id);
			}else {
				alert("Please Check...");
				return false;
			}
			
		});
	});

	/*
	$(document).ready(function() {
	$('#all_check').on('ifChanged', function(){
		if($(this).is(':checked')) {
			var days = <?= $stdays->days; ?>;
			var DiffDate = <?= $diffday->DiffDate ? $diffday->DiffDate:'0'; ?>;
			if(DiffDate > days){
				$('.ch_check').each(function() {
					$(this).iCheck('check');
				});
			}else{
				
				alert("You can't paid all installment right now..");
				$(this).iCheck('uncheck');
			}
		}else{
			$('.ch_check').each(function() {
				if($(this).val() > 0) {
					$(this).iCheck('uncheck');
				}
			});
		}
	});
		
		
		<?php 
			$days = $stdays->days;
			$DiffDate = $diffday->DiffDate ? $diffday->DiffDate:'0';
			if($DiffDate > days){
				echo '<td class="t_c"><div  style="background-color: white; width: 21px; margin-left: 0;"><input type="checkbox" name="all_check" id="all_check"></div></td>' ;
			}else{
				echo '<td class="t_c"></td>' ;
			}
		?>
		*/

</script>
