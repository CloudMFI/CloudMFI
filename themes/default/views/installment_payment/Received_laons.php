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
            height: 100%;
            width: 100%;
        }
		.contain-wrapper {
		width: 100%;
		min-height: 29.7cm;
		padding: 2cm;
		margin: 1cm auto;
		border: 1px #D3D3D3 solid;
		border-radius: 5px;
		background: white;
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
		font-family: 'Zawgyi-One', Times New Roman;
		}
		.ch-box{
			width:15px;height:15px;border:1px solid black;display:inline-block;
		}
		.small-letter{
			font-family:Zawgyi-One;font-weight:bold;font-size:12px;
		}
		.chat table{
			border-collapse:collapse;
			width: 100%;
			margin-bottom:20px;
		}
		.chat table tr td{
			border:1px solid black;
		}
		th{
			padding: 15px !important;
			text-align: center !important;
		}
		.chat tr td {
			padding:10px;
		}
		.order-num{
			font-weight:bold;
		}
		#logo img{
			width:150px;
			margin-left:15%;			
			opacity: 0.8; 
			padding-top:20px;
		}
	</style>
	</head>
	<body>
		<div class="contain-wrapper" style="padding:10px;">
			<div style="margin-top:20px; margin-left:15px;">
				<div class="header" style="width:100%;float:left; ">
					<div class="brand-name" style="width:30%; float:left; margin-left:30px;">
						<div style="float:left;" id="logo">
							<span> 
								<?php if ($Settings->logo2) {
									echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" style="margin-bottom:10px;" />';
								} ?> 
							</span> 
						</div>
					</div>	
					<div style="width:50%; float:left;margin-top:20px;text-align:center;">
						<div>
							<p style="font-family:Zawgyi-One; Muolfont-size:11px;"><b> <?php echo $setting->site_name ?> </b></p>					
						</div>
						<div>
							<p style="font-size:10px;"> ENATEAN &nbsp; LERKSTUOY &nbsp; SAMATEPEAP &nbsp; KRUOSA Plc </p>					
						</div>
					</div>
					<div style="width:10%; float:right;margin-top:20px;">
						
					</div>					
				</div>
				<div class="header" style="width:100%;float:left;margin-top:20px;">
					<div style="width:10%; float:left;margin-top:10px;"></div>				
					<div class="nationality-identifier" style="width:70%; float:left;margin-top:0px;position:relative;">
						
						<div>
							<p style="text-align:center;font-size:16px;font-family:Zawgyi-One;">လိုင္စင္ရေငြေရးေၾကးေရးလုပ္ငန္း<br>
								<span style="text-align:center;font-size:16px;font-family:Zawgyi-One;">ေငြေပး/ ေငြရေျပစာ</span>
							</p>					
						</div>
					</div>				
					<div style="width:10%; float:left;margin-top:10px;"></div>				
				</div>
				

				<div>
					<table width="100%" style="font-weight: bold;">
						<tr>
							<td style="width: 25%;padding-bottom: 20px;">ရက္စဲြ(Date)</td>
							<td style="width: 15%;padding-bottom: 20px;"><?php echo $this->erp->hrsd($contract_info->approved_date)?></td>
							<td style="width: 20%;padding-bottom: 20px;"></td>
							<td style="width: 20%;padding-bottom: 20px;">Type of Content</td>
							<td style="width: 20%;padding-bottom: 20px"></td>
						</tr>
						<tr style="margin-bottom: 20;">
							<td style="width: 25%;padding-bottom: 20px;">
								၀ိုင္းၾကီးခ်ဳပ္နံပါတ္(Group No.)
							</td>
							<td style="width: 15%;padding-bottom: 20px;"></td>
							<td style="width: 20%;padding-bottom: 20px;">
								
							</td>
							<td style="width: 20%;padding-bottom: 20px;">
								Client Officer ID
							</td>
							<td style="width: 20%;padding-bottom: 20px;">
								......
							</td>

						</tr>
						<tr>
							<td style="width: 25%;padding-bottom: 20px;">
								လိပ္စာ (Clients Address)
							</td>
							<td style="width: 15%;padding-bottom: 20px;">
								<?php echo $village->village?$village->village:'NA';?>
								<?php echo $sangkat->sangkat?$sangkat->sangkat:'NA';?>
								<?php echo $district->district?$district->district:'NA';?>
								<?php echo $state->state?$state->state:'NA';?>
							</td>
							<td style="width: 20%;padding-bottom: 20px;"></td>
							<td style="width: 20%;padding-bottom: 20px;">
								ေခ်းေငြကာလ(loan term)
							</td>
							<td style="width: 20%;padding-bottom: 20px;">
								......
							</td>
						</tr>
					</table>
				</div>
				
				
				<div class="phara" style="overflow:hidden;width:100%;min-height:100px;clear:both;padding-top:0px;">
					<div class="pha1" style="float:left;width:99%;">
						<strong class="pha2-text" style="font-size: 13px;">
							ရံုးလိပ္စာ Office Addressအမွတ္ ၁၈/၅၆၊ ဥသာျမိဳ႕သစ္(၆)ရပ္ကြက္၊ရန္ကုန္ မႏၱေလးလမ္းမၾကီး သာမေဏေက်ာ္ စာသင္တိုက္အနီး ၊ပဲခူးျမိဳ႕ ၊Ph
						</strong><br><br>
						<span class="pha2-text" style="font-size:13px;">
							 ထုတ္ေခ်းေငြ၏  ၁% ကိုစီမံခန္႔ခြဲရန္ ၀န္ေဆာင္ခ အေနျဖင့္လည္းေကာင္း၊ ၁% ကို လူမွဳေထာက္ပ့ံေရး ရန္ပံုေငြ အျဖစ္လည္းေကာင္း ၊ ၃% ကို မျဖစ္မေန စုေဆာင္းေငြ အျဖစ္ လည္းေကာင္း ေကာက္ခံမည္ျဖစ္ပါသည္။ စုေငြအရင္းမ်ားကို လူၾကီးမင္းကုမၸဏီမွ ဆက္လက္ထုတ္ေခ်းရန္ စိတ္ဆႏၵမရွိေတာ့သည့္ အခ်ိန္တြင္ ျပန္လည္ထုတ္ေပးသြားမည္ျဖစ္သည္။
						</span><br><br>
						<span class="pha2-text" style="font-size:13px;">
							Morakot Microfinance Myanmar Limited will charge you a one-time adminstration fee of 1% of the amount of the loan initially provided to you this fee upfont from the loan disbursed to you at the time of disbursement and may safe keep 2% the disbursement amount perloan as a compulsory deposit to you at the time of disbursement.You may withdraw all (but not part) of a compulsory deposit if you have no loans outstanding at Morakot Microfinance Myanmar Limited at the time withdrawal and cease to be a member of Morakot Microfinance Myanmar Limited.At any time if you have loans due to Morakot Microfinance 
Myanmar Limited you may not withdraw any compulsory deposit and pays interest on each compulsory deposit at rate of 1.25% per month,calculate
the interest based on a 30 day month.Interest is paid at the end of year and at the time of withdrawal of the compulsory deposit.
						</span>
					</div>		
					
				</div>
				<br>
				<div>
					<table width="100%" border="solid" style="text-align:center;">
						<thead>
							<tr style="border-width: 5px;">
								<th rowspan="2">No</th>
								<th rowspan="2">Name</th>
								<th rowspan="2">NRC</th>
								<th rowspan="2">Loan ID</th>
								<th rowspan="2">Disbursement Amount</th>
								<th colspan="3" >Payment Amonth</th>	
								<th rowspan="2">Receive Amount</th>
								<th rowspan="2">Clients Signature</th>
							</tr>

							<tr style="border-width: 5px;">
								<th >Fee 1%</th>
								<th>Beneficiary Welfare Fund 1%</th>
								<th>Compulsory Saving 3%</th>
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
								<td>15,000</td>
								<td>475,000</td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div style="width:100%;float:left;text-align:center;padding: 30px;">
					
					<div style="margin-left: 60%; float:left;font-weight: bold;">
						<p>Cashier (Payer/Receiver) ...............</p>
						
					</div>				
				</div>
			</div>
					</div>
		
		
	</body>
</html>
