<?php  //$this->erp->print_arrays($group_loan) ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $contract_info->reference_no?$contract_info->reference_no:'N/A';?></title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Battambang|Moul" rel="stylesheet"> 
		<?php
			$sale->total = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $sale->total);
			$service->amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $service->amount);
			
		?>
	<style type="text/css">
		@media print {
			.phone {color:red;}
		}
	        html, body {
	            height: 100%;
	        }
		.contain-wrapper {
		width: 21cm;
		min-height: 29.7cm;
		padding: 2cm;
		margin: 1cm auto;
		border-radius: 5px;
		background: white;
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
		font-family: Zawgyi-One,'Battambang', Times New Roman;
		}
		.ch-box{
			width:15px;height:15px;border:1px solid black;display:inline-block;
		}
		.small-letter{
			font-family:Zawgyi-One,khmer os muol;font-weight:bold;font-size:12px;
		}
		.chat table{
			border-collapse:collapse;
			width: 100%;
			margin-bottom:20px;
		}
		.chat table tr td{
			border:1px solid black;
		}
		.chat tr td {
			padding:10px;
		}
		.order-num{
			font-weight:bold;
		}
		#logo img{
			width:150px;
		}
		th{
			padding: 10px;
			vertical-align:center;
			text-align: center;
		}
		span{
			font-size:13px;
		}

	</style>
	</head>
	<body>
		<div class="contain-wrapper" style="padding:0; margin:0 auto;">
			  
			<div class="header" style=" text-align:center;">
				<div style=" " id="logo">
					<span> 
						<?php if ($Settings->logo2) {
							echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" style="margin-bottom:10px;" />';
						} ?> 
					</span> 
				</div>
					<b>
					 <?php
						//echo $this->session->branchName; 
						 echo $setting->site_name ; 
					 ?>
					</b>
			
			</div>
			<div>
			
				<p style="text-align:center;" class="small-letter"> <b>လိုင္စင္ရဘ႑ာေရးစက္မႈလုပ္ငန္း</b></p>
				<p style="text-align:center;" class="small-letter"> <b>အုပ္စု ေခ်းေငြစာခ်ုပ္</b></p>
			</div>
			
			<div style=" height:100px; padding-left:15px;font-size:10px;line-height: 18px;">
				<table width="100%">
					<tr>
						<td style="width:18%;">ရက္စဲြ<br>(Date)</td>
						<td style="width:15%;vertical-align: top;">:<b><?= $this->erp->hrsd(date('Y-m-d')); ?></b></td>
						<td style="width:13%">ေခ်းေငြကာလ<br>(Loan term)</td>
						<td style="width:15%;vertical-align: top;">:<b><?= round($contract_info->terms); ?> <?= lang("day") ?></b></td>
						<td style="width:18%">စတင္ေပးေခ်ရမည္ေန႕ရက္<br>(First Repayment Date)</td>
						<td style="width:15%;vertical-align: top;">: <b><?= $this->erp->hrsd($contract_info->installment_date); ?></b> </td>
					</tr>
					<tr>
						<td style="width:18%">အဖြဲ႕၀င္အေရအတြက္<br>(Number of member)</td>
						<td style="width:15%;vertical-align: top;">:</td>
						<td style="width:13%">ေပးေခ်သည့္ပံုစံ<br>(Repayment Interval)</td>
						<td style="width:15%;vertical-align: top;">:<b><span style="display:none;"><?= $contract_info->frequency; ?></span>
								<?php
								$frequency[""] = "";
								$frequency[1] = "Daily";
								$frequency[7] = "Weekly";
								$frequency[14] = "Two Week";
								$frequency[30] = "Monthly";
								$frequency[90] = "Quarterly";
								$frequency[180] = "Haft Year";
								$frequency[360] = "Yearly";
								echo  $frequency[$contract_info->frequency];?></b></td>
						<td style="width:18%">စာခ်ဳပ္အမ်ိဳးအစား<br>(Contract Type)</td>
						<td style="width:15%;vertical-align: top;">:</td>
					</tr>
					<tr>
						<td style="width:18%">၀ိုင္းၾကီးခ်ဳပ္နံပါတ္<br>(Group No. )</td>
						<td style="width:15%;vertical-align: top;">:<b> <?= $group_name->name; ?></b></td>
						<td style="width:13%">အတိုးနႈန္း<br>(Interest Rate)</td>
						<td style="width:15%;vertical-align: top;">:<b><?php echo $contract_info->interest ?></b></td>
						<td style="width:18%">ေခ်းေငြအရာရွိ ID<br>(Credit Officer ID)</td>
						<td style="width:15%;vertical-align: top;">:<b><?= $contract_info->approv_name; ?></b></td>
					</tr>
					<tr>
						<td style="width:18%">လိပ္စာ<br>(Leader/Borrower Address )</td>
						<td style="vertical-align: top;">:<b><?='#'.$customer->house_no; ?></td>
					</tr>
				</table>				 
			</div>
			<br><br>
			<div style=" width:100%;min-height:100px; padding-top:15px; padding-left:10px;">				 
				<p>
					ရံုးလိပ္စာ(Office Address ) :အမွတ္ ၁၈/၅၆ ၊ဥသာျမိဳ႕သစ္(၆)ရပ္ကြက္ ၊ရန္ကုန္ မႏၲေလးလမ္းမၾကီးသာမေဏေက်ာ စာသင္တိုက္အနီး၊ ပဲခူးျမိဳ႕။ Ph - ၀၉၁၁၂၃၄၅၆၇၈
				</p>
				<br>
				<p>
					ေခ်းေငြ မေပးသြင္းနိုင္ပါက ၀ိုင္းၾကီးခ်ဳပ္စနစ္ျဖင့္  အဖြဲ႕ အမွတ္ ………………… ၏ အဖြဲ႕၀င္မ်ားႏွင့္ပူးတဲြ ေငြေခ်းသူတို႔က အညီအမွ် တာ၀န္ယူေျဖရွင္းေပးမည္ ျဖစ္ေၾကာင္းလက္မွတ္ေရးထိုး ပါသည္။
				</p>	
			</div>
			
			<div style=" padding-left:10px; padding-right:10px;">
				<table border="1" style="max-width:100%; font-size:11px;">
					<tr>
						<th style="width:5%"><span>(စဥ္)</span><br>No</th>
						<th style="width:10%"><span>( ေခ်းေငြ    ID)</span><br>Loan ID</th>
						<th style="width:20%"><span>(ေငြေခ်းသူအမည္)</span><br>Borrower Name</th>
						<th style="width:15%"><span>(မွတ္ပံုတင္အမွတ္)</span><br> NRC No </th>
						<th style="width:10%"><span>( ေခ်းေငြပမာဏ    )</span><br>Loan Amount</th>
						<th style="width:10%"><span>( လက္မွတ္)</span><br>Signature</th>
					</tr>
					<?php 
					$i = 1;
					foreach($group_loan as $group){						
					?>
					<tr>
						<td style="padding:5px"> <?= $i; ?> </td>
						<td style="padding:5px"> <?= $group->reference_no; ?></td>
						<td style="padding:5px"> <?= $group->customer_name; ?> </td>
						<td style="padding:5px"> <?= $group->gov_id; ?> </td>
						<td style="padding:5px"> <?= $this->erp->formatMoney($this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $group->total)) ; ?> </td>
						<td style="padding:5px"> </td>
						
					</tr>
					<?php
					$i++;
					}
					?>			
				</table>				 
			</div>
			
			<div>
				<p style="text-align:center; padding-top:15px;" class="small-letter"> </p>
			</div>
			<div class="rules">
					<div class="rules_header" style="text-align: center; padding-top:15px;"><b>အထူးစည္းမ်ဥ္းမ်ား</b></div>
					<div class="rules_body">
						<ol>
							<li style="word-wrap: break-word;padding: 15px;">
								လူၾကီးမင္း၏ ေခ်းေငြေလွ်ာက္လႊာအတြက္ ေက်းဇူးတင္ရွိျပီး <?php echo $setting->site_name ?> ထံမွ ေခ်းေငြထုတ္ယူေသာ အခ်ိန္မွစ၍ ေခ်းေငြျပန္ဆပ္သည့္အခ်ိန္ထိ ၄င္းစည္းမ်ဥ္း စည္းကမ္းမ်ားကိုနားလည္ရန္ ႏွင့္ ၄င္းစည္းမ်ဥ္းစည္းကမ္းတြင္ ပါရွိသည့္အတိုင္း ေပးထားေသာ ကတိမ်ားကို ေစာင့္ထိန္းရန္ အေရးၾကီးပါသည္။ ကိုယ္ေရးအခ်က္အလက္မ်ား ႏွင့္ပတ္သက္ေသာ သတင္းမ်ားကို အခ်ိန္ႏွင့္တေျပးညီအသိေပးရန္ ကတိျပဳျပီး အကယ္၍ အေျပာင္းအလဲရွိပါက ခ်က္ခ်င္း <?php echo $setting->site_name ?> ထံသို႕အေၾကာင္းၾကားရန္ လိုအပ္ပါသည္။
							</li>
							<li style="word-wrap: break-word;padding: 15px;">
								ေခ်းေငြ အတိုးႏွဳန္းမွာတစ္လလွ်င္ ၂.၅% အတိုးႏႈန္း ျဖစ္ပီးေခ်းေငြလက္က်န္စာရင္းအေပၚတြင္ ရက္ေပါင္း ၃၀ ႏႈန္းျဖင့္အတိုးကိုတြက္ခ်က္ပါသည္။ေစာလ်င္စြာ (သို႔) ေနာက္က်စြာ (သို႔) ပိုမ်ားေသာပမာဏမ်ား (သို႔) ပိုနည္းေသာပမာဏမ်ားကိုကၽြႏု္ပ္ တို႔၏ ခြင့္ျပဳခ်က္ မပါရွိပဲ ျပန္လည္ေပးေခ်ျခင္းမျပဳလုပ္ရပါ။ 
							</li>
							<li style="word-wrap: break-word;padding: 15px;">							
								ထုတ္ေခ်းေငြ၏  ၁%  ကိုစီမံခန႔္ခြဲရန္ အခေၾကးေငြ ၀န္ေဆာင္ခ  အေနျဖင့္လည္းေကာင္း  ၊၁%  ကို လူမႈေထာက္ပံ့ေရးရန္ပံုေငြ အျဖစ္လည္းေကာင္း  ၊  ေခ်းေငြ၏  ၃%ကို မျဖစ္မေနစုေဆာင္းေငြ အျဖစ္လည္ေကာင္း  ေခ်းေငြထုတ္သည့္ေန႔တြင္ တစ္ၾကိမ္ေကာက္ခံမည္ျဖစ္သည္။  လူမႈေထာက္ပံ့ေရးရန္ပံုေငြ ေပးသြင္းျခင္းျဖင့္<?php echo $setting->site_name ?> ႏွင့္ အဖြဲ႕၀င္ျဖစ္ေနစဥ္အတြင္း  ေငြေခ်းသူေသဆံုးပါက  ေခ်းေငြမ်ားကိုပယ္ဖ်က္ေပးရန္ အေထာက္အကူျပဳပါသည္။<?php echo $setting->site_name ?>တြင္ ေခ်းထားေသာေခ်းေငြမ်ား မရွိေတာ့ေသာအခါ ႏွင့္<?php echo $setting->site_name ?>၏ အဖြဲ႕၀င္အျဖစ္မွ ႏွဳတ္ထြက္ေသာအခါတြင္္ မျဖစ္မေနစုေဆာင္းေငြအားလံုးကို ထုတ္ယူႏုိင္မည္ ျဖစ္သည္။ စုေငြအတိုးႏွဳန္းမွာ ၁.၂၅%ျဖစ္ျပီး တစ္လ ရက္ (၃၀ )ထားျပီး အတိုးတြက္ခ်က္ပါသည္။
							</li>
							<li style="word-wrap: break-word;padding: 15px;">
								ျပန္လည္ေပးဆပ္ရမည့္ ေငြအားလံုးကို ေပးဆပ္ရမည့္ေန႔တြင္  ညေန(၂)နာရီထက္ ေနာက္မက်ဘဲ ေပးသြင္းရပါမည္။ ထိုေန႔တြင္ ညေန(၂)နာရီထက္ေနာက္က်၍ ေပးသြင္းပါက ေနာက္က်   ေပးသြင္းသည္ဟု သတ္မွတ္ျပီး   ေနာက္က်ေၾကးေပးသြင္းရမည္ ျဖစ္သည္။၁ ရက္ထက္  ေနာက္က်ပါက ေနာက္က်ေၾကး ေပးသြင္းရမည္ျဖစ္ျပီး ထိုေနာက္က်ေၾကးသည္ တစ္ၾကိမ္ျပန္ဆပ္ေငြပမာဏ၏   ၁%  ျဖစ္ျပီး  ၄င္းကိုေနာက္က်သည့္အခါတိုင္း ေနာက္က်ေၾကးအျဖစ္ ေပးသြင္းရပါမည္။ ေငြျပန္ဆပ္ရန္ ပ်က္ကြက္ခဲ့လွ်င္ ေနာက္က်ေၾကးအျပင္  အျခားအေရးယူမႈမ်ားကို ျပဳလုပ္မည္ကို ထုတ္ေခ်းသူဘက္က  သိရွိရန္လိုအပ္ပါသည္။
							</li>
							<li style="word-wrap: break-word;padding: 15px;">
								အကယ္၍ အဖြဲ႕၀င္ တစ္ဦးဦး အခ်ိန္မွီျပန္လည္ေပးဆပ္ရန္ ပ်က္ကြက္ျခင္း၊  ေနာက္က်ျခင္း မ်ားျဖစ္ေပၚပါက ၀ိုင္းၾကီးခ်ဳပ္၏ စည္းကမ္းအရ က်န္ေသာ ၀ိုင္းၾကီးခ်ဳပ္ အဖြဲ႕၀င္မ်ား  အညီအမွ်  တာ၀န္ယူ  ေျဖရွင္းေပးရမည္  ျဖစ္ပီး<?php echo $setting->site_name ?> ထံသို႕  ေငြကို  ခ်က္ခ်င္းျပန္လည္ေပးေခ်ရန္ တာ၀န္ရွိပါသည္။ သင္သည္ေခ်းေငြအဖြဲ႕၀င္ ျဖစ္ေနစဥ္ကာလအတြင္း    ဥပေဒ    (သို႔) စည္းမ်ဥ္းစည္းကမ္းမ်ား    ေျပာင္းလဲျခင္းမ်ားရွိပါက    (သို႔မဟုတ္)    ဥပေဒ    (သို႔) စည္းမ်ဥ္းစည္းကမ္းအသစ္မ်ား  ထြက္  ရွိပါက<?php echo $setting->site_name ?> မွဥပေဒ  ႏွင့္အညီ စည္းမ်ဥ္းစည္းကမ္း အေျပာင္းအလဲ ျပဳလုပ္မည္ကိုလည္း သေဘာတူ ပါသည္။ 
							</li>
							<li style="word-wrap: break-word;padding: 15px;">
								<?php echo $setting->site_name ?> ထံမွေငြေခ်းယူသူသည္ ရရွိသည့္ ေခ်းေငြကို ေခ်းေငြစာခ်ဳပ္တြင္ ေဖာ္ျပထားေသာ လုပ္ငန္းမ်ား လုပ္ကိုင္ရန္အတြက္သာ အသံုးျပဳရမည္ ျဖစျ္ပီး ျပည္ေထာင္စု သမၼတ ျမန္မာနိုင္ငံေတာ္၏ တည္ဆဲဥပေဒမ်ားအရ တားျမစ္ထားသည့္ အျခားေသာ လုပ္ေဆာင္မွဳ မ်ားအတြက္ အသံုးမျပဳရန္ လိုအပ္ပါသည္။
							</li>
							<li style="word-wrap: break-word;padding: 15px;">
								ေငြေခ်းသူအေနျဖင့္  စာခ်ဳပ္ပါ အခ်က္မ်ား အားလံုးကို အတိအက် လိုက္နာ ေဆာင္ရြက္ရန္  လိုအပ္ျပီး  အကယ္ ၍ စာခ်ဳပ္ပါ အခ်က္မ်ားက္ု ခ်ဳိးေဖာက္လွ်င္  ျပည္ေထာင္စုသမၼတ ျမန္မာနိုင္ငံေတာ္ ၏တည္ဆဲ ဥပေဒမ်ားအရ အေရးယူ ေဆာင္ရြက္ခံရမည္ ျဖစ္ပီး တရားစြဲဆိုမွဳ(သို႔)  ေျဖရွင္းမွဳ႕ႏွင့္ စပ္လ်ဥ္းသည့္ ကုန္က်စရိတ္ စုစုေပါင္းကို ပ်က္ကြက္သူ(သို႔) ခ်ိဳးေဖာက္သူဖက္မွ က်ခံရမည္ ျဖစ္သည္ -စသည့္ စည္းမ်ဥ္းစည္းကမ္း အခ်က္မ်ားကို  ေကာင္းစြာ လိုက္နာ ေဆာင္ရြက္မည္ ျဖစ္ေၾကာင္းကို မိမိတို႕၏ သေဘာဆႏၵအရ  လက္မွတ္ေရးထိုးပါသည္
							</li>
						</ol>
					</div>
			</div>
			<div class="phara" style="overflow:hidden;width:100%;min-height:100px;clear:both;padding-top:15px; padding-left:10px;">	
				<div style="padding-left:50px;">
					<p>ေငြေခ်းသူႏွင့္ ပူးတြဲ ေငြေခ်းသူ၏ ဘယ္လက္မလက္ေဗြ <br> ေငြေခ်းသူ	</p>
				</div>				
			</div>
			
			<div style=" width:100%;min-height:100px; padding-top:15px; padding-left:10px;">				 
				1. ........................... &nbsp;&nbsp;&nbsp;&nbsp; 2. ........................... &nbsp;&nbsp;&nbsp;&nbsp; 3. ........................... &nbsp;&nbsp;&nbsp;&nbsp; 4. ........................... &nbsp;&nbsp;&nbsp;&nbsp; 5. ........................... &nbsp;&nbsp;&nbsp;&nbsp; 6. ...........................
			</div>			
			<div class="phara" style="overflow:hidden;width:100%;min-height:100px;clear:both;padding-top:15px; padding-left:10px;">	
				<div style="padding-left:50px;">
					<p> ပူးတြဲ ေငြေခ်းသူ/အိမ္ေထာင္ဦးစီး /မိသားစု၀င္</p>
				</div>				
			</div>			
			<div style=" width:100%;min-height:100px; padding-top:15px; padding-left:10px;">				 
				1. ........................... &nbsp;&nbsp;&nbsp;&nbsp; 2. ........................... &nbsp;&nbsp;&nbsp;&nbsp; 3. ........................... &nbsp;&nbsp;&nbsp;&nbsp; 4. ........................... &nbsp;&nbsp;&nbsp;&nbsp; 5. ........................... &nbsp;&nbsp;&nbsp;&nbsp; 6. ...........................
			</div>
			
		</div>
	</body>
</html>
<!--<?php echo($contract_info->gender=male?'checked="checked"':''); ?>
	 <?php echo $contract_info->gender($gender=='female')?'checked':'' ?>
-->