<!DOCTYPE html>
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
		td{
			height:35px;
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
					<div>
							<p style="padding-left: 48%;font-size:12px;font-family:Zawgyi-One;">လိုင္စင္ရေငြေရးေၾကးေရးလုပ္ငန္း
								<span style="float: right;">ရက္စဲြ(Date):
								<?php echo $this->erp->hrsd($contract_info->approved_date)?>
								</span>
								<br>
								<span style="text-align:center;font-size:12px;font-family:Zawgyi-One;">ေငြေပး/ ေငြရေျပစာ</span>
							</p>					
						</div>
				</div>
			
				<div>
					<table width="100%" style="font-size:12px; ">
						<tr>
							<td style="width: 15%;padding-bottom: 8px;" class="thb">
								ေခ်းေငြ အမ်ိဳးအစား<br>
								(Loan Type)
							</td>
							<td style="width: 25%;padding-bottom: 8px; ">:
							</td>
							<td style="width: 5%;padding-bottom: 8px; "></td>
							<td style="width: 10%;padding-bottom: 8px; " class="thb">
								၀ိုင္းၾကီးခ်ဳပ္နံပါတ္<br>(Group No.)
							</td>
							<td style="width: 45%;padding-bottom: 8px; ">:
								...........
							</td>
						</tr>

						<tr>
							<td style="width: 15%;padding-bottom: 8px; " class="thb">
								ေခ်းေငြ အရာရွိ ID <br>
								(Credit Officer ID)
							</td>
							<td style="width: 25%;padding-bottom: 8px; ">:
								......
							</td>
							<td style="width: 5%;padding-bottom: 8px; "></td>
							<td style="width: 10%;padding-bottom: 8px; " class="thb">
								ေခ်းေငြကာလ<br>(Loan term)
							</td>
							<td style="width: 45%;padding-bottom: 8px; ">:
								......
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
				<div style="overflow:hidden;width:100%;clear:both;padding:10px 0px 20px 0px;font-size:9px; ">
					<div >
						
						<span style="font-size:13px;">
							 ထုတ္ေခ်းေငြ၏ ၅,၀၀၀ က်ပ္ကိုစီမံခန္႔ခြဲရန္ ၀န္ေဆာင္ခ အေနျဖင့္လည္းေကာင္း၊ ၁% ကို လူမွဳေထာက္ပ့ံေရး ရန္ပံုေငြ အျဖစ္လည္းေကာင္း ၊ ၅% ကို မျဖစ္မေန စုေဆာင္းေငြ အျဖစ္ လည္းေကာင္း ေကာက္ခံမည္ျဖစ္ပါသည္။ စုေငြအရင္းမ်ားကို လူၾကီးမင္းကုမၸဏီမွ ဆက္လက္ထုတ္ေခ်းရန္ စိတ္ဆႏၵမရွိေတာ့သည့္ အခ်ိန္တြင္ ျပန္လည္ထုတ္ေပးသြားမည္ျဖစ္သည္။
						</span>
						<br>
						<!-- <span style="font-size:13px;text-align: justify-all;">
							Morakot Microfinance Myanmar Limited will charge you a one-time adminstration fee of 5,000ks of the amount of the loan initially provided to you this fee upfont from the loan disbursed to you at the time of disbursement and may safe keep 5% the disbursement amount perloan as a compulsory
							 saving to you at the time of disbursement.You may withdraw all (but not part) of a compulsory saving if you have no loans outstanding at Morakot Microfinance Myanmar Limited at the time withdrawal and cease to be a member of Morakot Microfinance Myanmar Limited.At any time if you have loans due to Morakot Microfinance 
Myanmar Limited you may not withdraw any compulsory saving and pays interest on each compulsory saving at rate of 1.25% per month,calculate
the interest based on a 30 day month.Interest is paid at the end of year and at the time of withdrawal of the compulsory saving.
						</span> -->
					</div>		
					
				</div>
				
				<div>
					<table width="100%" border="solid" style="text-align:center;font-size:12px; ">
						<thead>
							<tr style="border-width: 5px;">
								<th rowspan="3">No<br>စဥ္</th>
								<th rowspan="3" >Name<br>အမည္</th>
								<th rowspan="3" >NRC<br>ႏိုင္ငံသားမွတ္ပံုတင္အမွတ္</th>
								<th rowspan="3">Loan ID<br>ေခ်းေငြ အမွတ္</th>
								<th rowspan="3">Disbursement <br>Amount<br>ေခ်းေငြပမာဏ</th>
								<th rowspan="3">Fee 1%<br>၀န္ေဆာင္ခ ၁%</th>
								<th rowspan="3">Beneficiary <br>Welfare Fund 1%<br>လူမႈေရးေထာက္ပ့ံေရး ရန္ပံုေငြ ၁%</th>
								<th rowspan="3">Compulsory <br>Saving 5%<br>မျဖစ္မေန စုေဆာင္းရမည့္ေငြ ၅%</th>	
								<th rowspan="3">Received<br>Amount<br>လက္ခံရရွိမည့္ေငြပမာဏ</th>
								<th rowspan="3">Clients Signature<br>လက္မွတ္</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1.</td>
								<td>Ma Myat Noe Linn</td>
								<td>12/bbb(N)123456</td>
								<td>1</td>
								<td>500,000</td>
								<td>5,000</td>
								<td>5,000</td>
								<td>25,000</td>
								<td>465,000</td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div style="width:100%;text-align:center;">
					
					<div style="margin-left: 80%; float:left;font-weight: bold;font-size:12px; padding-top: 20px;">
						<p>Teller :...............<br>ေငြကိုင္</p>
						
					</div>				
				</div>
			</div>
					</div>
		
		
	</body>
</html>
