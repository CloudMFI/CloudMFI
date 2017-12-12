<?php 
	//$this->erp->print_arrays($saving);

?>
 <style type="text/css">
         .container {
		/*	height: 842px;*/
        width: 675px;
        /* to centre page on screen*/
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
		 @media print
		{    
			.no-print, .no-print *
			{
				display: none !important;
			}
		}
		#logo img{
			width:110px;
		}
	}
    </style>
<?php 
 //foreach($services as $sv) {
	// $sv['id']
	// $sv->id;
 //}
 //$this->erp->print_arrays($services);
?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
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
			<div style="float:left;width:50%;">	
				<center><b>
					<span class="kh_m"><b> <?php echo $setting->site_name ?> </b></span><br/>
					<span><?= lang("branch_company_name") ?> : <?= $this->session->branchName; ?></span><br/>
					<span class="kh_m"><u><?= lang("installments") ?></u></span><br/>
				</center></b>
			</div>
			<div style="float:left;width:25%;">
				<center><span style="line-height:140%; font-size:12px;"><?=lang("agree_to_pay_by_schedule")?> <br/><?=lang("date") ?>: <?= $this->erp->hrsd(date('Y-m-d')); ?><br/><?= lang("right_thumbprints") ?></span></center>
			</div>
		</div>		
		<div>
			<table class="top_info" style="font-size:11px;border-collapse:collapse;width:100%;">
				<tr>
					<td><?= lang("customer_name") ?></td>
					<td class="color_blue">: <?= $applicant->family_name.' '.$applicant->name;?></td>
					<td><?= lang("customer_latin_name") ?></td>
					<td class="color_blue">: <?= $applicant->family_name_other.' '.$applicant->name_other; ?></td>
					<td class="b_top b_left b_right"></td>
					<td class="b_top b_right"></td>
				</tr>
				
				<tr>
					<td><?= lang("account_number") ?></td>
					<td class="color_blue">: N / A </td>
					<td><?= lang("c_o_name") ?></td>
					<td class="color_blue">: <?= $users->first_name . ' ' . $users->last_name ; ?> </td>
					<td class="b_left b_right" style="width:90px;"></td>
					<td class=" b_right" style="width:90px;"></td>
				</tr>				
				<tr>
					<td><?= lang("phone1") ?></td>
					<td class="color_blue">: <?=$applicant->phone1?><?php if(($applicant->phone2)){echo ' / '.$applicant->phone2;} ?></td>
					<td><?= lang("c_o_phone") ?></td>
					<td class="color_blue">: <?= $users->phone; ?> </td>
					<td class="b_left b_right" style="width:90px;"></td>
					<td class=" b_right" style="width:90px;"></td>
				</tr>				
				<tr>
					<td><?= lang("total_balance_schedule") ?></td>
					<td class="color_blue">: <?= $this->erp->formatMoney($amount).' '.($currency? $currency->name_other : ''); ?></td>
					<td><?= lang("term_loan") ?></td>
					<td class="color_blue">: <?= number_format($term,0); ?> <?=lang("day") ?> </td>
					<td class="b_left b_right" style="width:90px;"></td>
					<td class=" b_right" style="width:90px;"></td>
				</tr>				
				<tr>
					<td><?= lang("payments_times") ?></td>
					<td class="color_blue">: <?= number_format(($inv->term / $inv->frequency),0); ?>  <?= lang("times") ?></td>
					<td><?= lang("payment_freq") ?></td>
					<td class="color_blue">: <?= number_format($inv->frequency,0); ?>  <?= lang("days_times") ?> </td>
					<td class="b_left b_right b_bottom t_c"><b>  </b></td>
					<td class="b_right b_bottom"></td>
				</tr>
				<tr>
					<td><?= lang("interest_rate_schedule") ?></td>
					<td class="color_blue">: <?= $this->erp->formatDecimal($interest_rate * 100); ?>%</td>
					<td><?= lang("penalty") ?></td><td colspan="3" class="color_blue">: <?=(($setting->penalty_types == 'Percentage')? ($setting->penalty_amount*100).'%' : $this->erp->formatMoney($setting->penalty_amount)) ?> <?= $stcurrency->name?> </td>
				</tr>
				<tr>
					<td><?= lang("purpose_loan") ?></td>
					<td class="color_blue">: <?php echo (isset($applicant->note) ? $applicant->note :'N/A') ?></td>
					<td><?= lang("collateral_schedule") ?></td><td colspan="3" class="color_blue">: <?php echo $collateraltype->cl_type ?></td>
				</tr>
				
			
				
				<?php
					
					foreach ($services as $service){	
						if ($service->service_paid==1){
							$one = 0;
								if($service->type=="Percentage"){
									$one = $service->amount * $amount ;												
								}else{
									$one = $this->erp->convertCurrency($product->currency_code, $setting->default_currency, $service->amount);
								}	
								$one_ = $one + ($one * $service->tax_rate);
													
							echo'<tr>																																	
									<td> '.  $service->description .' </td> <td class="color_blue">: '.$this->erp->roundUpMoney($one_ , $product->currency_code) .' '.($currency? $currency->name_other : '') .' </td> 																		
								</tr>';
						}
					}
				?> 
				<tr>
					<td><?= lang("address_approved") ?></td>
					<td colspan="3" class="color_blue">: <?php echo $applicant->house_no; ?></td>
				</tr>
				
			</table>
		</div>
		
			<table style="font-size:11px;border-collapse:collapse;width:100%;" class="b_top b_left b_right b_bottom">
				<tr class="b_top  p_l_r" style="background-color:#009900;color:white;">
					<td  class="t_c" style="width: 25px;"><?= lang("no") ?></td>
					<td  class="t_c" style="width: 104px;"><?= lang("intallment_date") ?></td>
					<td  class="t_c" style="width: 85px;"><?= lang("principle_paid") ?></td>					
					<td  class="t_c" style="width: 78px;"><?= lang("interest_paid") ?></td>	

					<?php
						foreach ($services as $service){
							if ($service->service_paid==2) {
								echo '<td  class="t_c" style="width: 78px;"> '. $service->description .' </td>';
							}if($service->service_paid==3) {
								echo '<td  class="t_c" style="width: 78px;"> '. $service->description .' </td>';
							}if($service->service_paid==4) {
								echo '<td  class="t_c" style="width: 78px;"> '. $service->description .' </td>';
							}
						}
					?>
					<td  class="t_c" style="width: 80px;"><?= lang("principle_balance") ?></td>	
					<td  class="t_c" style="width: 80px;"><?= lang("total_intallment") ?></td>
					<?php if($saving){?>
					<td  class="t_c" style="width: 80px;"><?= lang("saving_interest") ?></td>
					<?php }?>
				</tr>				
			<?php
				$total_principle = 0;
				$total_interest = 0;
				$total_payment = 0;
				if(array($pts)) {
					$countrow  = count($pts)/2;
					foreach($pts as $pt){					
					$principles = $this->erp->roundUpMoney($pt['principle'], $product->currency_code);
					$interests = $this->erp->roundUpMoney($pt['interest'], $product->currency_code);
					
					$saving_interest = $this->erp->roundUpMoney($pt['saving_interest'], $product->currency_code);
					echo '<tr class="b_top">
						<td class="t_c" style="padding-left: 5px; padding-right: 5px; height: 25px;">'. $pt['period'] .'</td>
						<td class="t_c" style="padding-left:5px;padding-right:5px;">'. $this->erp->hrsd($pt['dateline']) .'</td>	
						<td class="t_c" style="padding-left:5px;padding-right:5px;">'. $principles .'</td>
						<td class="t_c" style="padding-left:5px;padding-right:50px; text-align:right">'. $interests .'</td>';
						
						$balances = (($pt['balance'] > 0)? $pt['balance'] : 0) ;
						$balances = str_replace(',', '', $this->erp->roundUpMoney($balances, $product->currency_code));
						$principle_amt = str_replace(',', '', $principles );
						$interest_amt = str_replace(',', '', $interests);
						$payment_amt = $principle_amt + $interest_amt;
						$loan_balance = $balances + $principle_amt;
						$haft_paid = 0;
						$insurences_paid = 0;
						$all_paid = 0;
						foreach($services as $service){
							if ($service->service_paid==2){	
								$haft = 0 ;
								if($service->type=="Percentage"){
									//$haft = ($service->charge_by == 1)? ($service->amount * $amount ): (($service->charge_by == 2)? ($service->amount * $loan_balance ) : 0 ) ;												
									//$haft = ($service->charge_by == 2)? ($service->amount * $loan_balance): ( $service->amount * $amount) ;
									$haft = ($service->charge_by == 2)? ($service->amount * $loan_balance): (($service->charge_by == 3)? ($service->amount * $payment_amt ) : $service->amount * $amount ) ;
								}else{
									$haft = $this->erp->convertCurrency($product->currency_code, $setting->default_currency, $service->amount);
								}
								$haft_ = $haft + ($haft * $service->tax_rate);
								$haft_service_paid = $this->erp->roundUpMoney($haft_ , $product->currency_code);	
								$haft_paid += str_replace(',', '', $haft_service_paid);
								echo'<td class="t_c" style="padding-left:5px;padding-right:5px;">'. (($pt['period'] >= 1 && $pt['period'] <= $countrow)? $haft_service_paid:'0.00') .'</td>';
							} 
							
							if ($service->service_paid==3){
								$alls = 0;
								if($service->type=="Percentage"){
									$alls = ($service->charge_by == 2)? ($service->amount * $loan_balance): (($service->charge_by == 3)? ($service->amount * $payment_amt ) : $service->amount * $amount ) ;								
								}else{
									$alls = $this->erp->convertCurrency($product->currency_code, $setting->default_currency, $service->amount);
								}
								$alls_ = $alls + ($alls * $service->tax_rate);
								$all_service_paid = $this->erp->roundUpMoney($alls_ , $product->currency_code);
								$all_paid += str_replace(',', '', $all_service_paid);
								echo'<td class="t_c" style="padding-left:5px;padding-right:5px;">'. $all_service_paid .'</td>';
							} 
							
							if ($service->service_paid==4){	
								$insurence = 0 ;
								if($service->type=="Percentage"){
									$insurence = ($service->amount * $amount) /$countrow;											
								}else{
									$insurence = $this->erp->convertCurrency($product->currency_code, $setting->default_currency, $service->amount) /$countrow;
								}
								$insurence_ = $insurence + ($insurence * $service->tax_rate);
								$insurence_paid = $this->erp->roundUpMoney($insurence_ , $product->currency_code);
								$insurences_paid += str_replace(',', '', $insurence_paid);								
								echo'<td class="t_c" style="padding-left:5px;padding-right:5px;font-weight:normal">'. (($pt['period'] >= 1 && $pt['period'] <= $countrow)? $insurence_paid :'0.00') .'</td>';
							}						
						}		
						if($pt['period'] >= 1 && $pt['period'] <= $countrow){
							$payment = str_replace(',', '', $principles) + str_replace(',', '', $interests) + $all_paid + $haft_paid + $insurences_paid ;
						}else{
							$payment = str_replace(',', '', $principles) + str_replace(',', '', $interests) + $all_paid ;
						}
						
					echo'
						<td class="t_c" style="padding-left:5px;padding-right:5px;">'. $this->erp->roundUpMoney((($pt['balance'] > 0)? $pt['balance'] : 0),$product->currency_code) .'</td>
						<td class="t_c" style="padding-left:5px;padding-right:5px;">'. $this->erp->roundUpMoney($payment , $product->currency_code) .'</td>';
					if($saving){
						echo 
						'<td class="t_c" style="padding-left:5px;padding-right:5px;">'. $saving_interest .'</td>';}
					echo'</tr>';
					$total_principle += str_replace(',', '', $principles);
					$total_interest += str_replace(',', '', $interests);
					$total_payment += $payment;
					$total_alls += str_replace(',', '', $all_service_paid);
					$total_haft += str_replace(',', '', $haft_service_paid)/2 ;
					$total_insurence += str_replace(',', '', $insurence_paid) /2 ;
					$total_saving += str_replace(',', '', $saving_interest);
					}
				}
			?>	
				<tr class="b_top text-bold">
					<td class="t_c" style="padding-left: 5px; padding-right: 5px; height: 25px;" colspan="2"><?=lang("total_schedule")?></td>
					<td class="t_c" style="padding-left:5px;padding-right:5px;"><?= $this->erp->roundUpMoney($total_principle, $product->currency_code) ?></td>
					
					<td class="t_c" style="padding-left:5px;padding-right:5px;"><?= $this->erp->roundUpMoney($total_interest, $product->currency_code) ?></td>
					<?php
						foreach ($services as $service){
							if ($service->service_paid==2) {
								//echo '<td style="padding-left:15px;">'. $this->erp->roundUpMoney($total_haft , $product->currency_code) .' </td>';
								echo '<td style="padding-left:15px;"> </td>';
							}if($service->service_paid==3) {
								//echo '<td style="padding-left:15px;">'. $this->erp->roundUpMoney($total_alls , $product->currency_code) .' </td>';
								echo '<td style="padding-left:15px;"> </td>';
							}if($service->service_paid==4) {
								//echo '<td style="padding-left:15px;">'. $this->erp->roundUpMoney($total_insurence , $product->currency_code) .' </td>';
								echo '<td style="padding-left:15px;"> </td>';
							}
						}
					?>
					<td class="t_c" style="padding-left:5px;padding-right:5px;"></td>
					<td class="t_c" style="padding-left:5px;padding-right:5px;"><?= $this->erp->roundUpMoney($total_payment, $product->currency_code); ?></td>
					<?php if ($saving){?>
					<td class="t_c" style="padding-left:5px;padding-right:5px;"><?= $this->erp->roundUpMoney($total_saving, $product->currency_code); ?></td>
					<?php } ?>
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
						<td>- <?=lang("the_contract_does_not_comply")?> <b><?php echo $setting->site_name ?> </b> &nbsp <?= lang("company_will_take_legal_action") ?></td>
					</tr>
				</table>
			</div>

	</div>			
	
        </div>
        <div class="modal-footer">
           <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
        </div>
    </div>
</div>
<?= isset($modal_js) ?$modal_js  : ('') ?>


<script>
	$(document).ready(function() {
		var t_service= localStorage.getItem('total_inst');
		$( "#t_service" ).text( t_service );
	});
</script>