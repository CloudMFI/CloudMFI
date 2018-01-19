<link href="https://fonts.googleapis.com/css?family=Battambang" rel="stylesheet"> 
 <?php
	$sale->total = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $sale->total);
 //$this->erp->print_arrays($saving);
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
											<span class="kh_m"><b> <?php echo $setting->site_name ?></span><br/>
											<span style="font-family:Zawgyi-One">လိုင္စင္ရေငြေရးေၾကးေရးလုပ္ငန္း</span><br/>
											<span style="font-size:18px;"> Repayment Schedule for Group Loan</span><br/>
										</center></b>
									</div>
									<div style="float:left;width:25%;">
									</div>
								</div>
								
								<div>
									<table style="width:100%;border-top: 1px solid black;border-bottom: 1px solid black;margin-top:5px;font-size:11px;font-weight: normal;"> <!-- MSM add 1/12/2017-->
										<tr>
											<td width="32%">Group ID :<b> <?=$group_loan->name;?> </b></td>
											<td width="32%">Account ID :<b> <?=$sale->reference_no;?> </b></td>
											<td width="32%">Name : <b><?php echo $customer->family_name_other.' '.$customer->name_other; ?></b></td>
										</tr>
									</table>
									
									<table style="width:90%;font-size:11px;margin-top:5px;line-height:15px !important;font-weight: normal;"> 
										<tr>
											<td width="5%" style="vertical-align: top;">ေပးေခ်သည့္ပံုစံ<br>(Repayment Interval)</td>
											<td width="10%" style="vertical-align: top;">: <span style="display:none;"><?= $sale->frequency; ?></span>
												<?php
												$frequency[""] = "";
												$frequency[1] = "Daily";
												$frequency[7] = "Weekly";
												$frequency[14] = "Two Week";
												$frequency[30] = "Monthly";
												$frequency[90] = "Quarterly";
												$frequency[180] = "Haft Year";
												$frequency[360] = "Yearly";
												echo  $frequency[$sale->frequency];?></td>
											<td width="1%" style="vertical-align: top;">ေခ်းေငြသက္တမ္း<br>(Loan Term)</td>
											<td width="10%"  style="vertical-align: top;">: <b><?= round($sale->term / 30) ?>လ</b></td>
											<td width="5%" style="vertical-align: top;">ထုတ္ေခ်းသည့္ရက္စြဲ <br>(Disbursement Date)</td>
											<td width="5%"  style="vertical-align: top;">:<b><?= $this->erp->hrsd($disbursement_info->date); ?></b></td>
										</tr>
										<tr>
											<td width="5%" style="vertical-align: top;">အတိုးႏွဳန္း<br>(Interest Rate)</td>
											<td width="10%"  style="vertical-align: top;">: <b><?= ($sale->rate_text ? $sale->rate_text : "")?></b></td>
											<td width="10%" style="vertical-align: top;">ေခ်းေငြတာ၀န္ခံအမွတ္<br>(Credit Officer ID)</td>
											<td width="10%"  style="vertical-align: top;">: <b><?= $creator->first_name . ' ' . $creator->last_name ; ?></b></td>
											<td width="5%" style="vertical-align: top;">စတင္ေပးေခ်းရမည့္ရက္စြဲ<br>(First Repayment Date)</b></td>
											<?php 
												
												if(array($loan)) {
													$i = 0;
													$len = count($loan);
													foreach($loan as $pt){	
														if ($pt === reset($loan)){
											?>			
											<td width="5%"  style="vertical-align: top;">:<b><?= $this->erp->hrsd($pt->dateline); ?></b></td>
											<?php }}} ?>
										</tr>
										<tr>
											<td width="5%" style="vertical-align: top;">ေငြေခ်းသူလိပ္စာ<br>(Leader/ Borrower Address)</td>
											<td colspan="5"  style="vertical-align: top;">:<b><?= $address ?></b></td>
											
										</tr>
										
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
										<td  class="t_c" style="width: 5%;">No</td>
										<td  class="t_c" style="width: 10%;">ေပးေခ်ရမည့္ေန႔<br>(Due Date)</td>
                                        <td  class="t_c" style="width: 10%;">နေ့ရက်များ<br>(Days)</td>
										<td  class="t_c" style="width:  10%;">အရင္းေငြ<br>(Principle)</td>
										<td  class="t_c" style="width:  10%;">အတိုးႏႈန္း<br>(Interest)</td>
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
										<td class="t_c" style="width:10%;">လက္က်န္ အရင္းေငြ<br>(Balance)</td>
										<td class="t_c" style="width:10%;">လစဥ္သြင္းရန္အရစ္က်ေငြ<br>(Total Due)</td>
										 
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


                                    //==============================
                                    //==============================
                                    $pre_day = $disbursement_info->date;
                                    $ii = 0;
                                    $balance_due = $sale->grand_total;

                                    $frequency = $sale->frequency;

                                    //echo($interest);
                                    //                                                die();
                                    //==============================
                                    //==============================

										if(array($loan)) {
											foreach($loan as $pt){

											    $num_day = $ii == 0?$this->site->dateDiff($pre_day, $pt->dateline):$this->site->dateDiff($pre_day, $pt->dateline)-1;

                                                $ii++;

                                                $in_rate = $sale->interest_rate;



												$princ=$this->erp->formatMoney($pt->principle);

//												$interest=$this->erp->formatMoney($pt->interest); lion
												$interest=$balance_due*$in_rate*$num_day/$frequency;
												$interest2=$balance_due*$in_rate*$num_day/$frequency;

//                                                echo($interest);
//                                                die();
//                                                exit;


												$overdue_amt = (($pt->paid_amount > 0)? $pt->overdue_amount : 0);
												$payment = $pt->payment + $overdue_amt;
												$paid = $pt->paid_amount? $pt->paid_amount : 0;
												$other_paid = $pt->other_amount? $pt->other_amount : 0;
												$services_charge = $pt->total_service_charge? $pt->total_service_charge : 0;
												$paid_amount = $paid + $other_paid + $services_charge + (($pt->paid_amount > 0)? $overdue_amt : 0);

												$balance = $payment - $paid_amount;

                                                //==lion
                                                $balance_due = $balance_due - $pt->principle;

												$balance_moeny = $this->erp->formatMoney($balance_due);
//												$balance_moeny = $this->erp->formatMoney($pt->balance);

												$Principles = $this->erp->roundUpMoney($pt->principle, $sale_item->currency_code);
//												$interests = $this->erp->roundUpMoney($pt->interest, $sale_item->currency_code);lion
												$interests = $this->erp->roundUpMoney($interest, $sale_item->currency_code);
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
												<td class="t_c" style="padding-left:5px; padding-right:5px; width:10%">'. $num_day  .'</td>
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
												  <td class="t_c" style="padding-left:5px;padding-right:5px; width:10%">'. $this->erp->roundUpMoney($payment, $sale_item->currency_code) .'</td>';	
											  
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
											$total_pay = ($total_payment);


                                                $pre_day = $pt->dateline;


                                                $this->db->update('loans', array('interest' => $interest, 'payment' => ($Principles + $interest)), array('id' => $pt->id));

											}
											 
										}
									?>	
									<tr class=" text-bold">
										<td class="t_c" style="padding-left: 5px; padding-right: 5px; height: 25px;" colspan="4"> Total </td>
										<td class="t_c" style="padding-left:5px;padding-right:5px;"><?= $this->erp->roundUpMoney($total_principle, $sale_item->currency_code); ?></td>
										<td class="t_c" style="padding-left:5px;padding-right:5px;"><?= $this->erp->roundUpMoney($total_interest, $sale_item->currency_code); ?></td>
										<?php
											foreach ($services as $service){
												if($service->service_paid==2) {
													 echo '<td class="t_r" style="padding-left:5px;padding-right:5px;">   </td>';
												}
												if($service->service_paid==3) { 
													 echo '<td class="t_r" style="padding-left:5px;padding-right:5px;">   </td>';
												}
												if($service->service_paid==4) { 
													 echo '<td class="t_r" style="padding-left:5px;padding-right:5px;">   </td>';
												}
											}
										?>
										
										<td></td>
										<td class="t_c" style="padding-left:5px;padding-right:5px;"><?= $this->erp->roundUpMoney($total_pay, $sale_item->currency_code); ?></td>
										 
										<td></td>
										
									</tr>
									
								</table>
								<div style="margin-top: 10px; margin-bottom: 10px;">
									 
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
			var days = '<?= $stdays->days; ?>';
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
