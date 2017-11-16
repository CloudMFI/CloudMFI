<link href="https://fonts.googleapis.com/css?family=Battambang" rel="stylesheet"> 
 <?php
	$sale->total = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $sale->total);
	$service->amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $service->amount);
	
?>
 <style type="text/css">
    .container {
        width: 800px;
        margin-left: auto;
        margin-right: auto;
	}	
	.t_c{text-align:center;}
	.t_r{text-align:center;}
    @media print
	{    
		.no-print, .no-print *
		{
			display: none !important;
		}
		#hidden_print{display:none;}
		#hidden-total{display:none;}
		#hide_action{display:none;}
		#hide_action2{display:none;}
		#hide_action3{display:none;}
	}
	.kh_m{
		font-family: "Khmer OS Muol";
	}
	.b_top{border-top:1px solid black;}
	.b_bottom{border-bottom:1px solid black}
	.b_left{border-left:1px solid black;}
	.b_right{border-right:1px solid black;}
	.text-bold td{font-weight:bold;}
	.p_l_r td{padding-left:5px;padding-right:5px;}
	.top_info tr td{
		height:25px;
	}
	.color_blue{color:#3366cc;}
	#logo img{
		width:110px;
	}
	.border table{
		border:1px solid gray;
	}
	.border table tr td{
		border-bottom:1px solid gray;
	}
</style>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('payment_schedule'); ?></h4>
        </div>
        <div class="modal-body">
			<div class="row">
				<div class="container">
					<div>
						<div style="float:left;width:25%;" id="logo">
							<span> <?php if ($Settings->logo2) {
                            echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" style="margin-bottom:10px;" />';
							} ?> </span> 
						</div>
						
						<div style="float:left;width:50%; font-family:Battambang">	
							<center><b>
								<span class="kh_m"><b> <?php echo $setting->site_name ?></span><br/>
								<span> <?= lang("branch_company_name") ?> : <?= $this->session->branchName; ?></span><br/>
								<span style="font-size:18px;"> <?= lang("installments") ?> </span><br/>
							</center></b>
						</div>
						<div style="float:left;width:25%;">
								<center><span style="line-height:140%; font-size:12px; font-weight:normal;"><?=lang("agree_to_pay_by_schedule")?> <br/><?=lang("date") ?>: <?= $this->erp->hrsd(date('Y-m-d')); ?><br/><?= lang("right_thumbprints") ?></span></center>
						</div>
					</div>
					
					<div>
						<table class="top_info" style="font-size:11px;border-collapse:collapse;width:100%;font-weight:normal">
							<tr>
								<td><?= lang("customer_name") ?></td>
								<td class="color_blue">: <b><?php echo $customer->family_name_other.' '.$customer->name_other; ?></b></td>
								<td><?= lang("customer_latin_name") ?></td><td class="color_blue">: <?=$customer->family_name.' '.$customer->name;?></td>
								<td class="b_top b_left b_right"></td>
								<td class="b_top b_right"></td>
							</tr>
							<tr>
								<td><?=lang("loan_account_number")?> </td>
								<td class="color_blue">: <?=$sale->reference_no;?> </td>
								<td><?= lang("c_o_name") ?></td>
								<td class="color_blue">: <?= $creator->first_name . ' ' . $creator->last_name ; ?></td>
								<td class="b_left b_right" style="width:90px;"></td>
								<td class="b_right" style="width:90px;"></td>
							</tr>
							<tr>
								<td><?= lang("phone1") ?></td><td class="color_blue">: <b> <?= $customer->phone1; ?> </b></td>
								<td><?= lang("c_o_phone") ?></td>
								<td class="color_blue">: <?= $creator->phone; ?> </td>
								<td class="b_left b_right"></td>
								<td class="b_right"></td>
							</tr>
							<!--<tr>
								<td>លេខទូស័ព្ទទី2</td><td class="color_blue">: <?= $customer->phone2; ?></td><td>ថ្ងៃសងចុងក្រោយ</td><td class="color_blue">: <?= $this->erp->hrsd($this->erp->getLastPaymentDate($sale->term, $sale->frequency, $sale->due_date)); ?></td>
								<td class="b_left b_right"></td><td class="b_right"></td>
							</tr>-->
							<tr>
								<td><?= lang("disburse_date") ?></td><td class="color_blue">: <?= $this->erp->hrsd($sale->approved_date); ?></td>
								<td><?= lang("term_loan") ?></td>
								<td class="color_blue">: <?= number_format($sale->term,0); ?>  <?= lang("days") ?> </td>
								
								<td class="b_left b_right t_c"><b>  </b></td>
								<td class="b_right"></td>
							</tr>
							<tr>
								<td><?= lang("payments_times") ?></td><td class="color_blue">: <?= number_format(($sale->term / $sale->frequency),0); ?>  <?= lang("times") ?></td>
								<td><?= lang("payment_frequency") ?></td>
								<td class="color_blue">: <?= number_format($sale->frequency,0); ?>  <?= lang("days_times") ?> </td>
								
								<td class="b_left b_right t_c"><b>  </b></td>
								<td class="b_right"></td>
							</tr>
							<tr>
								<td><?= lang("total_balance_schedule") ?></td>
								<td class="color_blue">: <?= $this->erp->formatMoney($sale->total) .' '. $currency->name_other; ?></td>
								<td><?= lang("part") ?></td>
								<td class="color_blue">: 1</td>
								<td class="b_left b_right b_bottom t_c"><b>  </b></td>
								<td class="b_right b_bottom"></td>
							</tr>
							<tr>
								<td><?= lang("interest_rate_") ?></td>
								<td class="color_blue">: <?= ($sale->rate_text ? $sale->rate_text : "")?></td>
								<td><?= lang("penalty") ?></td><td colspan="3" class="color_blue">: <?=(($setting->penalty_types == 'Percentage')? ($setting->penalty_amount*100).'%' : $this->erp->formatMoney($setting->penalty_amount).'R') ?>​​​​​​​​</td>
							</tr>
							<tr>
								<td><?= lang("purpose_loan") ?></td>
								<td class="color_blue">: <?= ($sale->note ? $sale->note : "N/A")?>
								<td><?= lang("collateral_schedule") ?></td>
								<td class="color_blue">: <?php  echo $collateraltype->type ?></td>
							</tr>
							
							<!--
							<tr>
								<td>កំរៃសេវាឥណទាន(២)</td><td class="color_blue">: 100.00 ដុល្លារ</td><td>មំរៃសេវាឆៃក CBC(៣)</td><td colspan="3" class="color_blue">: 2.86 ដុល្លារ</td>
							</tr><td width="215px"> សរុបតម្លៃសេវា </td><td class="color_blue">: <?= $this->erp->formatMoney($total_service->service_amount) .' '. $currency->name_other; ?></td>
							-->							
							
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
												<td> '.  $service->description .' </td> <td class="color_blue">: '.$this->erp->roundUpMoney($one_, $sale_item->currency_code).' '. $currency->name_other .' </td> 																		
											</tr>';
									}
								}
							?>
							<tr>
								<td><?= lang("address_approved") ?></td><td colspan="4" class="color_blue"> : <?='#'.$customer->house_no; ?> </td>
							</tr>
							<!--<tr>
								<td>គោលបំណងកម្ចី</td><td colspan="5" class="color_blue">: <?= strip_tags($sale_item->description) ?></td>
							<tr><td>អាស័យដ្ឋាន</td><td colspan="5" class="color_blue">: <?='#'.$customer->house_no.','.$customer->street.','.$address;?></td></tr>
							</tr>-->
							
						</table>
					</div>
					<div class="border">	
						<table style="font-size:11px;width:100%;" class="schedule">
							<tr class="p_l_r" style="background-color:#009900;color:white;width:100%; height:30px;">
								<td  class="t_c"><?= lang("no") ?></td>
								<td  class="t_c"><?= lang("intallment_date") ?></td>
								
								<td  class="t_c"><?= lang("principle_paid") ?></td>
								<td  class="t_c"><?= lang("interest_paid") ?></td>	
								<?php
									foreach ($services as $service){
										if($service->service_paid==2) { 
											echo '<td class="t_c" style="padding-top:10px;width: 92px;"> '. $service->description.' </td>';
										}
										if($service->service_paid==3) { 
											echo '<td class="t_c" style="padding-top:10px;width: 92px;">'.  $service->description .'</td>';
										}
										 
										if($service->service_paid==4) {
											echo '<td class="t_c" style="padding-top:10px;width: 92px;"> '. $service->description .'</td>';
										} 
									}
								?>
								<td  class="t_c"><?= lang("principle_balance") ?></td>
								<td  class="t_c"><?= lang("total_intallment") ?></td>
								
							</tr>
							<?php
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
								
								//$this->erp->print_arrays($cuscurrency);
								if(array($loan)) {
									foreach($loan as $pt){									
										$princ=$this->erp->formatMoney($pt->principle);
										$interest=$this->erp->formatMoney($pt->interest);
										$overdue_amt = (($pt->paid_amount > 0)? $pt->overdue_amount : 0);
										$payment = $pt->payment + $overdue_amt ;
										$paid = $pt->paid_amount? $pt->paid_amount : 0;
										$other_paid = $pt->other_amount? $pt->other_amount : 0;
										$services_charge = $pt->total_service_charge? $pt->total_service_charge : 0;
										$paid_amount = $paid + $other_paid + $services_charge + (($pt->paid_amount > 0)? $overdue_amt : 0);
										$balance = $payment - $paid_amount;
										$balance_moeny=$this->erp->formatMoney($pt->balance);	
										
										$Principles = $this->erp->roundUpMoney($pt->principle, $sale_item->currency_code);
										$interests = $this->erp->roundUpMoney($pt->interest, $sale_item->currency_code);
										
											echo '<tr class="row-data" '.(($pt->paid_amount > 0)? 'style="background-color:#B4D8E8;font-weight:normal;"':'').'>
												<td class="t_c" style="padding-left: 5px; padding-right: 5px; height: 25px;font-weight:normal;">'. $pt->period .'</td>
												<td class="t_c" style="padding-left:5px;padding-right:5px;font-weight:normal">'. $this->erp->hrsd($pt->dateline) .'</td>
												
												<td class="t_c" style="padding-left:5px;padding-right:5px;font-weight:normal">'. $Principles .'</td>										
												<td class="t_c" style="padding-left:5px;padding-right:5px;font-weight:normal">'. $interests .'</td>';
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
														//$haft = ($service->charge_by == 2)? ($service->amount * $loan_balance): ( $service->amount * $sale->total) ;		
													}else{
														$haft = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $service->amount);
													}
													$haft_ = $haft + ($haft * $service->tax_rate);
													$haft_service_paid = $this->erp->roundUpMoney($haft_, $sale_item->currency_code);
													$haft_paid += str_replace(',', '', $haft_service_paid);
													echo'<td class="t_c" style="padding-left:5px;padding-right:5px;font-weight:normal">'. (($pt->period >= 1 && $pt->period <= $countrow)? $haft_service_paid:'0.00') .'</td>';
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
													echo'<td class="t_c" style="padding-left:5px;padding-right:5px;font-weight:normal">'. $all_service_paid .'</td>';
												}
												
												else if ($service->service_paid==4){	
													$insurence = 0 ;
													if($service->method =="Percentage"){
														$insurence = ($service->amount * $sale->total) /$countrow;											
													}else{
														$insurence = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $service->amount) /$countrow;
													}
													$insurence_ = $insurence + ($insurence * $service->tax_rate);
													$insurence_paid = $this->erp->roundUpMoney($insurence_ , $sale_item->currency_code );
													$insurences_paid += str_replace(',', '', $insurence_paid);
													echo'<td class="t_c" style="padding-left:5px;padding-right:5px;font-weight:normal">'. (($pt->period >= 1 && $pt->period <= $countrow)? $insurence_paid:'0.00') .'</td>';
												}											
											}
											$Principles_amount = str_replace(',', '', $Principles);
											$interests_amount = str_replace(',', '', $interests);
											
											if($pt->period >= 1 && $pt->period <= $countrow){
												$payment = $Principles_amount +  $interests_amount + $all_paid + $haft_paid +  $insurences_paid;
											}else{
												$payment = $Principles_amount +  $interests_amount + $all_paid;
											}
											$balance = (($pt->balance > 0)? $pt->balance : 0);
											
											echo '<td class="t_c" style="padding-left:5px;padding-right:5px;font-weight:normal">'. $this->erp->roundUpMoney($balance, $sale_item->currency_code) .'</td>
												<td class="t_c" style="padding-left:5px;padding-right:5px;font-weight:normal">'. $this->erp->roundUpMoney($payment, $sale_item->currency_code) .'</td>
												
												</tr>';
												
										$total_principle += str_replace(',', '', $Principles);
										$total_interest += str_replace(',', '', $interests);
										$total_payment += $payment;									
										$total_alls += str_replace(',', '', $all_service_paid);
										$total_haft += str_replace(',', '', $haft_service_paid)/2 ;
										$total_insurence += str_replace(',', '', $insurence_paid) /2 ;
										$total_pay = ($total_payment);
									}
								}
							?>
						
							<tr class=" text-bold" id="hidden-total">
								<td class="t_c" style="padding-left: 5px; padding-right: 5px; height: 25px;" colspan="2"><?= lang("total_schedule") ?></td>
								
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
								<td id="hide_action3"></td>
							</tr>
							
						</table>
					</div>
					<div style="margin-top: 10px; margin-bottom: 10px; font-weight:normal">
						<table style="font-size:11px;">
							<tr valign="top">
								<td style="width:110px;"> <b> <?= lang("note") ?> :</b> <td>
								<td height="30%"><?= lang("payment_note")?><td>
							</tr>
							<tr>
								<td><td>
								<td>- <?=lang("the_contract_does_not_comply")?> <b><?php echo $setting->site_name ?> </b> &nbsp <?= lang("company_will_take_legal_action") ?><td>
							</tr>
						</table>
					</div>

				</div>
			</div>
        </div>
        
		<div class="modal-footer no-print">
			<a href="<?=base_url().'Installment_payment/export_loan/0/1/'.$sale_id; ?>">
				<div class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;">
					<i class="fa fa-file-excel-o "></i> <?= lang('export_excel'); ?>
				</div>
			</a>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
			
        </div>
		<!--<div class="buttons">
		 
			<div class="btn-group btn-group-justified no-print">
				<div class="btn-group">
					<a href="<?php echo base_url().'Installment_payment/export_loan/0/1/'.$sale_id; ?>"  class="tip btn btn-primary" id="" title="<?= lang('export') ?>">
						<i class="fa fa-money"></i>
						<span class="hidden-sm hidden-xs"><?= lang('excel') ?></span>
					</a>
				</div>
			</div>
        </div>-->
    </div>
</div>
<?= isset($modal_js) ?$modal_js  : ('') ?>
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
		
	});

</script>