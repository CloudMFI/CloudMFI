<!DOCTYPE html>

<?php
	  //$this->erp->print_arrays($grouploans);
?>
<html>
	<head>
		<title><?php echo $contract_info->reference_no?$contract_info->reference_no:'N/A';?></title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Battambang" rel="stylesheet"> 
	<style type="text/css">
        html, body {
            /*height: 100%;*/
            width: 100%;

        }
		.contain-wrapper {
		width: 95%;
		min-height: 29.7cm;
		
		
		background: white;
		
		font-family: 'Zawgyi-One', Times New Roman;
		}
		
		
		.thb{
			font-weight: bold;
		}
		th{
			padding: 3px !important;
			text-align: center !important;
		}
		
		
		#logo img{
			width:150px;
			margin-left:15%;			
			opacity: 0.8; 
			
		}
	</style>
	</head>
	<body>
		<div class="contain-wrapper" style="padding:">
			<div style="margin-top:20px; margin-left:15px;">
				<div class="header" style="width:100%;float:left; ">
					<div class="brand-name" style="width:30%; float:left; margin-left:40%;">
						<div id="logo">
							<span> 
								<?php if ($Settings->logo2) {
									echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" />';
								} ?> 
							</span> 
						</div>
					</div>	
									
				</div>
				<div class="header" style="width:100%;float:left;margin-top:20px;">								
					<div style="text-align:center">							 
							<span style=" font-size:12px;font-family:Zawgyi-One;">လိုင္စင္ရေငြေရးေၾကးေရးလုပ္ငန္း</span> 
							<br>
							<span style=" font-size:12px;font-family:Zawgyi-One;">ေငြေပး/ ေငြရေျပစာ</span>							 					
					</div>
					<div style="text-align:right">							 
							<!--<span style="float: right;">ရက္စဲြ(Date):								
								<?php echo $this->erp->hrsd($contract_info->approved_date)?>
							</span>	<br>-->
						 
							<span style="float: right;">ရက္စဲြ(Disbursement Date):
							<?= $this->erp->hrsd($disbursement_info->date); ?>
							</span>
						 
					</div>
				</div>
				





				<div>
					<table width="100%" style="font-size:12px; ">
						<tr>
							<td style="width: 15%;padding-bottom: 8px;" class="thb">
								ေခ်းေငြ အမ်ိဳးအစား<br>
								(Loan Type)
							</td>
							<td style="width: 25%;padding-bottom: 8px; ">: <?= $saleiterm->product_name?$saleiterm->product_name:'N/A'; ?>
							</td>
							<td style="width: 5%;padding-bottom: 8px; "></td>
							<td style="width: 10%;padding-bottom: 8px; " class="thb">
								၀ိုင္းၾကီးခ်ဳပ္နံပါတ္<br>(Group No.)
							</td>
							<td style="width: 45%;padding-bottom: 8px; ">: <?= $group->name?$group->name:'N/A'; ?>

							</td>
						</tr>

						<tr>
							<td style="width: 15%;padding-bottom: 8px; " class="thb">
								ေခ်းေငြ အရာရွိ ID <br>
								(Credit Officer ID)
							</td>
							<td style="width: 25%;padding-bottom: 8px; ">:<?= $co->first_name; ?> <?= $co->last_name; ?>
							</td>
							<td style="width: 5%;padding-bottom: 8px; "></td>
							<td style="width: 10%;padding-bottom: 8px; " class="thb">
								ေခ်းေငြကာလ<br>(Loan term)
							</td>
							<td style="width: 45%;padding-bottom: 8px; ">: <?= round($contract_info->terms) / 30 ?> လ
							</td>
						</tr>
						<tr>
							<td style="width: 15%;padding-bottom: 8px; " class="thb">
								လိပ္စာ <br>(Clients Address)
							</td>
							<td style="width: 25%;padding-bottom: 8px; ">:
								<?php echo $village->village?$village->village:'NA';?>
								<?php echo $sangkat->sangkat?$sangkat->sangkat:'NA';?>
								<?php echo $district->district?$district->district:'NA';?>
								<?php echo $state->state?$state->state:'NA';?>	
							</td>
							<td style="width: 5%;padding-bottom: 8px; "></td>
							<td style="width: 10%;padding-bottom: 8px; "></td>
							<td style="width: 45%;padding-bottom: 8px; "></td>
						</tr>
						
					</table>
				</div>

				<br>			
				<div style="overflow:hidden;width:100%;clear:both;padding:10px 0px 10px 0px;font-size:9px; ">
					<div >
						
						<span style="font-size:13px;">
							 ထုတ္ေခ်းေငြ၏ ၁% ကိုစီမံခန္႔ခြဲရန္ ၀န္ေဆာင္ခ အေနျဖင့္လည္းေကာင္း၊ ၁% ကို လူမွဳေထာက္ပ့ံေရး ရန္ပံုေငြ အျဖစ္လည္းေကာင္း ၊ ၅% ကို မျဖစ္မေန စုေဆာင္းေငြ အျဖစ္ လည္းေကာင္း ေကာက္ခံမည္ျဖစ္ပါသည္။ စုေငြအရင္းမ်ားကို လူၾကီးမင္းကုမၸဏီမွ ဆက္လက္ထုတ္ေခ်းရန္ စိတ္ဆႏၵမရွိေတာ့သည့္ အခ်ိန္တြင္ ျပန္လည္ထုတ္ေပးသြားမည္ျဖစ္သည္။
						</span>
						<br>
						<br> 
					</div>		
					
				</div>
				<br>
				<br>
				<div>
					<table width="100%" border="solid" style="text-align:center;font-size:12px; ">
						 
							<tr style="border-width: 5px;">
								<th>No<br>စဥ္</th>
								<th >Name<br>အမည္</th>
								<th >NRC<br>ႏိုင္ငံသားမွတ္ပံုတင္အမွတ္</th>
								<th>Loan ID<br>ေခ်းေငြ အမွတ္</th>
								<th>Disbursement <br>Amount<br>ေခ်းေငြပမာဏ</th>
								<?php foreach($services as $service){ ?>
								<th><?= $service->description?></th> 
								<?php } ?>
								<th>Compulsory <br>Saving 5%<br>မျဖစ္မေန စုေဆာင္းရမည့္ေငြ ၅%</th>	
								<th>Received <br>Amount<br>လက္ခံရရွိမည့္ေငြပမာဏ</th>
								<th>Clients Signature<br>လက္မွတ္</th>
							</tr>
						 
							<?php 
							$i = 1; 
								foreach($grouploans as $grouploan){
									$total_disburse = $this->erp->convertCurrency($saleiterm->currency_code,$setting->default_currency, $grouploan->grand_total);
									$saving = $this->erp->convertCurrency($saleiterm->currency_code,$setting->default_currency, $grouploan->saving);
								?>
								<tr>
									<td><?= $i ?></td>
									<td><?= $grouploan->customer_name ?></td>
									<td><?= $grouploan->gov_id ?></td>
									<td><?= $grouploan->reference_no ?></td>
									<td> <?= $this->erp->roundUpMoney($total_disburse, $saleiterm->currency_code) ?> </td>
									
									<?php 
										$tatalservice = 0;
										$total_service_charge = 0;
										$service->amount = 0;
										foreach($grouploan->service as $service){ 
											if($service->service_paid == 1) {
												$loan_amt = $this->erp->convertCurrency($saleiterm->currency_code, $setting->default_currency, $grouploan->total);
												if($service->type == 'Percentage'){
													$amount = $service->amount * $loan_amt;
													$service->amount = $amount + ( $amount * $service->tax_rate);
												}
												else{
													$amount = $this->erp->convertCurrency($saleiterm->currency_code, $setting->default_currency, $service->amount);
													$service->amount = $amount + ( $amount * $service->tax_rate);
												}
												$total_service_charge += $service->amount;
												
											}
											$tatalservice = $total_service_charge;
										
									?>								 
									<td><?= $service->amount?></td>
									<?php 
											$disburse_amount = $total_disburse - $tatalservice - $saving;
										}										 
									?>
									
									<td><?= $saving ?></td>
									<td> <?= $this->erp->roundUpMoney($disburse_amount, $saleiterm->currency_code) ?>  </td>
									<td>   </td>
									 
								</tr>
							<?php
								
								$i++;
								}
								 
							?>
						 
					</table>
				</div>
				<div style="width:100%;text-align:center;">
					
					<div style="margin-left: 80%; float:left;font-weight: bold;font-size:12px; padding-top: 20px;">
						<p>Teller(Payer/Receiver) :...............<br>ေငြကိုင္</p>
						
					</div>				
				</div>
			</div>
					</div>
		
		
	</body>
</html>
