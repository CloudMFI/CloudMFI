 <?php  //echo $this->erp->print_arrays($saving) ?>
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
					<style="font-family:Zawgyi-One">လိုင္စင္ရေငြေရးေၾကးေရးလုပ္ငန္း</span><br/>
					<span style="font-size:18px;"> Repayment Schedule for Group Loan</span><br/>
				</center></b>
			</div>
			<div style="float:left;width:25%;">
				<center><span style="line-height:140%; font-size:12px;"></span></center>
			</div>
		</div>		
		<div>
					<table style="width:100%;border-top: 1px solid black;border-bottom: 1px solid black;margin-top:5px;font-size:11px;font-weight: normal;"> <!-- MSM add 1/12/2017-->
						<tr>
							<td width="32%">Group ID : </td>
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
							<td width="10%"  style="vertical-align: top;">: <b><?= number_format($sale->term,0); ?>ရက်</b></td>
							<td width="5%" style="vertical-align: top;">ထုတ္ေခ်းသည့္ရက္စြဲ <br>(Disbursement Date)</td>
							<td width="5%"  style="vertical-align: top;">:<b> </b></td>
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
							<td colspan="5"  style="vertical-align: top;">:<b><?='#'.$customer->house_no; ?></b></td>
							
						</tr>
						
					</table>
					
		
		</div>
			<table style="font-size:11px;border-collapse:collapse;width:100%;" class="b_top b_left b_right b_bottom">
				<tr class="b_top  p_l_r" style="background-color:#009900;color:white;">
					<td  class="t_c" style="width: 10%;"><?= lang("no") ?></td>
					<td  class="t_c" style="width: 10%;">ေပးေခ်ရမည့္ေန႔<br>(Due Date)</td>
					<td  class="t_c" style="width: 20%;">အရင္းေငြ<br>(Principle)</td>					
					<td  class="t_c" style="width: 20%;">အတိုးႏႈန္း<br>(Interest)</td>	

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
					<td  class="t_c" style="width: 20%;">လက္က်န္ အရင္းေငြ<br>(Balance)</td>	
					<td  class="t_c" style="width: 20%;">လစဥ္သြင္းရန္အရစ္က်ေငြ<br>(Total Due)</td>
					<?php if ($saving > 0){?>
					<td  class="t_c" style="width: 20%;"> Saving Interest </td>
					<?php } ?>
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
						<td class="t_c" style="padding-left:5px;padding-right:5px;">'. $this->erp->roundUpMoney($payment, $currency->code) .'</td>';
					if($saving > 0){
						echo 
						'<td class="t_r" style="padding-left:5px;padding-right:5px;">'. $saving_interest .'</td>';}
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
					<td class="t_c" style="padding-left: 5px; padding-right: 5px; height: 25px;" colspan="2">Total</td>
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
					<?php if ($saving){?>
					<td class="t_c" style="padding-left:5px;padding-right:5px;"><?= $this->erp->roundUpMoney($total_saving,$currency->code); ?></td>
					<?php } ?>
				</tr>
				
			</table>
			<div style="margin-top: 10px; margin-bottom: 10px;">
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