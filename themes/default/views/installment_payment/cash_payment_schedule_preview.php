 <?php //echo $this->erp->print_arrays($stcurrency) ?>
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
	//$this->erp->print_arrays($get_service);
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
					<td><?= lang("customer_name") ?>:</td>
					<!--<td class="color_blue">: <b><?=isset($sale->customer)?($sale->customer):'N/A';?></b></td>-->
					<td class="color_blue">: <span id="customer_name"></span></td>
					<td><?= lang("customer_latin_name") ?></td>
					<td class="color_blue">: <span id="customer_latin_name"></span></td>
					<td class="b_top b_left b_right"></td>
					<td class="b_top b_right"></td>
				</tr>
				 
				<tr>
					<td><?= lang("phone1") ?></td>
					<td class="color_blue">: <span id="phone"></span></td>
					<td><?= lang("c_o_name") ?></td>
					<!--<td class="color_blue">: <?= $this->session->username; ?></td>-->
					<td class="color_blue">: <span id="co_name_loan"></span></td>
					<td class="b_left b_right" style="width:90px;"></td>
					<td class=" b_right" style="width:90px;"></td>
				</tr>
				 
				<tr>
					<td><?= lang("term_loan") ?></td>
					<td class="color_blue">: <?= number_format($term,0); ?> <?=lang("day") ?></td>
					<td><?= lang("part") ?></td>
					<td class="color_blue">: 1</td>
					<td class="b_left b_right b_bottom t_c"><b>  </b></td>
					<td class="b_right b_bottom"></td>
				</tr>
				 
				<tr>
					<td><?= lang("total_balance_schedule") ?></td>
					<td class="color_blue">: <?= $this->erp->formatMoney($amount).' '.($currency? $currency->name_other : ''); ?></td>
					<td><?= lang("interest_rate_schedule") ?></td>
					<td colspan="3" class="color_blue">: <?= $this->erp->formatDecimal($interest_rate * 100); ?>% </td>
				</tr>
				<tr>
					<!--<td>ការប្រាក់សរុប(១)</td>
					<td class="color_blue">: <?= $this->erp->formatMoney($all['total_interest']).' '.$currency->name_other; ?></td>
					-->
					<td><?= lang("purpose_loan") ?></td>
					<td class="color_blue">:<span id="purpose_loan"></span></td>
					<td><?= lang("penalty") ?></td>
					<td colspan="3" class="color_blue">: <?=(($setting->penalty_types == 'Percentage')? ($setting->penalty_amount*100).'%' : $this->erp->formatMoney($setting->penalty_amount)) ?> <?= $stcurrency->name ?></td>
				</tr>
				 
				
				<?php
				//$this->erp->print_arrays($currency, '<br>' ,$stcurrency);
					foreach ($services as $service){	
						$one = 0;
						$description = '';
						foreach($get_service as $gs){
							if ($service['service_paid']==1){
								if($service['id'] == $gs->id) {
									$description = $gs->description;
								}
							}
						}
						if ($service['service_paid']==1){
							if($service['status']=="Percentage"){
								$one = $service['amount'] * $amount;
							}else {
								$one = $this->erp->convertCurrency($currency->code, $stcurrency->code, $service['amount']);
							}
							echo'<tr>																																	
									<td> '. $description .'  </td> <td class="color_blue">: '.$this->erp->roundUpMoney($one, $currency->code).' '. $currency->name .' </td> 																		
								</tr>';
						}	
					}
					
				?>
				<!--<tr>
					<td><?= lang("collateral_schedule") ?></td>
					<td class="color_blue">:<span id="collateral"></span></td>
				</tr>-->
				<tr>
					<!--<td width="215px">វត្ថុដាក់បញ្ចាំ</td>
					<td class="color_blue">:<?php echo $collateraltype->cl_type ?></td>-->
					<td><?= lang("address_approved") ?></td>
					<td colspan="3" class="color_blue">: <span id="cur_address"></span></td>
				</tr>
			</table>	
		</div>
			<table style="font-size:11px;border-collapse:collapse;width:100%;" class="b_top b_left b_right b_bottom">
				<tr class="b_top  p_l_r" style="background-color:#009900;color:white;">
					<td  class="t_c" style="width: 10%;"><?= lang("no") ?></td>
					<td  class="t_c" style="width: 10%;"><?= lang("intallment_date") ?></td>
					<td  class="t_c" style="width: 20%;"><?= lang("principle_paid") ?></td>					
					<td  class="t_c" style="width: 20%;"><?= lang("interest_paid") ?></td>	

					<?php
						foreach ($get_service as $service){
							if ($service->service_paid==2) {
								echo '<td  class="t_c" style="width: 20%;"> '. $service->description .' </td>';
							}
							if($service->service_paid==3) {
								echo '<td  class="t_c" style="width: 20%;"> '. $service->description .' </td>';
							}
							if($service->service_paid==4) {
								echo '<td  class="t_c" style="width: 20%;"> '. $service->description .' </td>';
							} 	
						}
					?>
					<td  class="t_c" style="width: 20%;"><?= lang("principle_balance") ?></td>	
					<td  class="t_c" style="width: 20%;"><?= lang("total_intallment") ?></td>
					<td  class="t_c" style="width: 20%;"><?= lang("saving_interest") ?></td>
				</tr>
			<?php
				//$this->erp->print_arrays($pts);
				$total_principle = 0;
				$total_interest = 0;
				$total_payment = 0;
				if(array($pts)) {
					$countrow  = count($pts)/2;
					foreach($pts as $pt){
					$balance = (($pt['balance'] > 0)? $pt['balance'] : 0);
					$principles = $this->erp->roundUpMoney($pt['principle'], $currency->code);
					$interests = $this->erp->roundUpMoney($pt['interest'], $currency->code);
					$saving_interest = $this->erp->roundUpMoney($pt['saving_interest'], $currency->code);
					
					echo '<tr class="b_top">
						<td class="t_c" style="padding-left: 5px; padding-right: 5px; height: 25px;">'. $pt['period'] .'</td>
						<td class="t_c" style="padding-left:5px;padding-right:5px;">'. $this->erp->hrsd($pt['dateline']) .'</td>
						<td class="t_c" style="padding-left:5px;padding-right:5px;">'. $principles .'</td>													
						<td class="t_c" style="padding-left:5px;padding-right:5px;">'. $interests .'</td>';	
						
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
							if ($service['service_paid']==2){	
								$haft = 0;
								if($service['status']=="Percentage"){									
									//$haft = ($service['charge_by'] == 1)? ( $service['amount'] * $amount ): (($servicee['charge_by'] == 2)? ($service['amount'] * $loan_balance ) : 0 ) ;	
									//$haft = ($service['charge_by'] == 2)? ( $service['amount'] * $loan_balance): ($service['amount'] * $amount);
									$haft = ($service['charge_by'] == 2)? ( $service['amount'] * $loan_balance): (($service['charge_by'] == 3)? ($service['amount'] * $payment_amt ) : $service['amount'] * $amount ) ;
								}else{
									$haft = $this->erp->convertCurrency($currency->code, $stcurrency->code, $service['amount']);
								}
								$haft_ = $haft + ($haft * $service['tax_rate']);
								$haft_service_paid = $this->erp->roundUpMoney($haft_, $currency->code);
								$haft_paid += str_replace(',', '', $haft_service_paid);
								echo'<td class="t_c" style="padding-left:5px;padding-right:5px;">'. (($pt['period'] >= 1 && $pt['period'] <= $countrow)? $haft_service_paid :'0.00') .'</td>';							
							}
							if ($service['service_paid']==3){
								$alls = 0;
								if($service['status']=="Percentage"){
									$alls = ($service['charge_by'] == 2)? ( $service['amount'] * $loan_balance): (($service['charge_by'] == 3)? ($service['amount'] * $payment_amt ) : $service['amount'] * $amount ) ;
								}
								else {
									$alls = $this->erp->convertCurrency($currency->code, $stcurrency->code, $service['amount']);
								}
								$alls_ = $alls +($alls*$service['tax_rate']);
								$all_service_paid = $this->erp->roundUpMoney($alls_, $currency->code);
								$all_paid += str_replace(',', '', $all_service_paid);
								echo'<td class="t_c" style="padding-left:5px;padding-right:5px;">'. $all_service_paid .'</td>';
							}
							if ($service['service_paid']==4){	
								$insurence = 0;
								if($service['status']=="Percentage"){
									$insurence = ($service['amount'] * $amount) / $countrow;
								}
								else {
									$insurence = $this->erp->convertCurrency($currency->code, $stcurrency->code, $service['amount']) /$countrow;
									/*if($currency->rate > $stcurrency->rate){
										$insurence = ($service['amount'] * $currency->rate) / $countrow;
									}
									else if($currency->rate < $stcurrency->rate){
										$insurence = ($service['amount'] / $stcurrency->rate) / $countrow; 
									}
									else if($currency->rate = $stcurrency->rate){
										$insurence = $service['amount'] / $countrow;
									})*/
								}
								$insurence_ = $insurence + ($insurence * $service['tax_rate']);
								$insurence_paid = $this->erp->roundUpMoney($insurence_, $currency->code);
								$insurences_paid += str_replace(',', '', $insurence_paid);
								echo'<td class="t_c" style="padding-left:5px;padding-right:5px;">'. (($pt['period'] >= 1 && $pt['period'] <= $countrow)? $insurence_paid:'0.00') .'</td>';
							} 
						}
						
						if($pt['period'] >= 1 && $pt['period'] <= $countrow){
							$payment = str_replace(',', '', $principles) + str_replace(',', '', $interests) + $all_paid + $haft_paid + $insurences_paid;
						}else{
							$payment = str_replace(',', '', $principles) + str_replace(',', '', $interests) + $all_paid ;
						}
						
					echo'
						<td class="t_c" style="padding-left:5px;padding-right:5px;">'. $this->erp->roundUpMoney((($pt['balance'] > 0)? $pt['balance'] : 0), $currency->code) .'</td>
						<td class="t_c" style="padding-left:5px;padding-right:5px;">'. $this->erp->roundUpMoney($payment, $currency->code) .'</td>
						<td class="t_r" style="padding-left:5px;padding-right:5px;">'. $saving_interest .'</td>
					</tr>';
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
					<td class="t_c" style="padding-left:5px;padding-right:5px;"><?= $this->erp->roundUpMoney($total_principle ,$currency->code); ?></td>
										
					<td class="t_c" style="padding-left:5px;padding-right:5px;"><?= $this->erp->roundUpMoney($total_interest ,$currency->code); ?></td>
					<?php
						foreach ($services as $service){
							if($service['service_paid']==2) {
								echo '<td style="padding-left:25px;padding-right:5px;"> </td>'; //'. $this->erp->roundUpMoney($total_haft , $currency->code) .'
							}if($service['service_paid']==3) {
								echo '<td style="padding-left:25px;padding-right:5px;"> </td>'; //'. $this->erp->roundUpMoney($total_alls , $currency->code) .'
							}if($service['service_paid']==4) {
								echo '<td style="padding-left:25px;padding-right:5px;"> </td>'; //'. $this->erp->roundUpMoney($total_insurence , $currency->code) .'
							} 	
					
						}
					?>
					<td class="t_c" style="padding-left:5px;padding-right:5px;"></td>
					<td class="t_c" style="padding-left:5px;padding-right:5px;"><?= $this->erp->roundUpMoney($total_payment,$currency->code); ?></td>
					<td class="t_c" style="padding-left:5px;padding-right:5px;"><?= $this->erp->roundUpMoney($total_saving,$currency->code); ?></td>
					
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
		var customer_name= localStorage.getItem('name');
		$( "#customer_name" ).text( customer_name );
		var customer_latin_name= localStorage.getItem('latin');
		$( "#customer_latin_name" ).text( customer_latin_name );
		var phone_num= localStorage.getItem('phone');
		$( "#phone" ).text( phone_num );
		//var collateral_type =localStorage.getItem('collateral');
		//$( "#collateral" ).text( collateral_type );
		var cur_address =localStorage.getItem('cus_house_no');
		$( "#cur_address" ).text( cur_address );
		var purpose_loan =localStorage.getItem('purpose');
		$( "#purpose_loan" ).text( purpose_loan );
		var co_name_loan =localStorage.getItem('co_name');
		$( "#co_name_loan" ).text( co_name_loan );
	});
</script>