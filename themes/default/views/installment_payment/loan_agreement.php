<?php //$this->erp->print_arrays($contract_info) ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $contract_info->reference_no?$contract_info->reference_no:'N/A';?></title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Battambang|Moul" rel="stylesheet"> 
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
		font-family: 'Battambang', Times New Roman;
		}
		.ch-box{
			width:15px;height:15px;border:1px solid black;display:inline-block;
		}
		.small-letter{
			font-family:khmer os muol;font-weight:bold;font-size:12px;
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
				<p style="text-align:center;" class="small-letter"> <b>လိုင်စင်ရဘဏ္ဍာရေးစက်မှုလုပ်ငန်း</b></p>
				<p style="text-align:center;" class="small-letter"> <b>အုပ္စ ချေးငွေစာချုပ်</b></p>
			</div>
			
			<div style=" height:100px; padding-left:15px;">
				<table width="100%">
					<tr>
						<td style="width:15%">ရက္စြ</td>
						<td>:</td>
						<td style="width:15%">ချေးငွေကာလ</td>
						<td>:</td>
						<td style="width:15%">စတင္ပးခ်မည္န႕ရက္</td>
						<td>:</td>
					</tr>
					<tr>
						<td style="width:15%">င်အရေအတွက်တိုင်းတာဝန်</td>
						<td>:</td>
						<td style="width:15%">ဘယ်လိုဆပ်ဖို့</td>
						<td>:</td>
						<td style="width:15%">စာခ်ဳပ္အမ်ိဳးအစား</td>
						<td>:</td>
					</tr>
					<tr>
						<td style="width:15%">လိပ္စာ </td>
						<td>:</td>
						<td style="width:15%">အတိုးနှုန်း</td>
						<td>:</td>
						<td style="width:15%">Credit Officer ID</td>
						<td>:</td>
					</tr>
				</table>				 
			</div>
			<div style=" width:100%;min-height:100px; padding-top:15px; padding-left:10px;">				 
				<p>
					ရံုးလိပ္စာ Office Address အမွတ္ ၁၈/၅၆ ၊ဥသာျမိဳ႕သစ္(၆)ရပ္ကြက္ ၊ရန္ကုန္ မႏၲေလးလမ္းမၾကီးသာမေဏေက်ာ စာသင္တိုက္အနီး၊ ပဲခူးျမိဳ႕Ph.-
				</p>
				<p>
					ေခ်းေင ြ မေပးသငြ ္းနိုင္ပါက ၀ိုင္းၾကီးခ်ဳပ္စနစ္ျဖင ့္ အဖ႕ဲြ အမွတ…္ ………………… ၏ အဖ႕ဲြ ၀င္မ်ား ႏွင ့္ ပူးတဲြ ေငြေခ်းသ ူ တ႕ို က အညီအမွ် တာ၀န္ယ ူ ေျဖရငွ ္းေပးမည ္ ျဖစ္ေၾကာင္းတ္မွတ္ေရးထိုး ပါသည္။
				</p>	
			</div>
			
			<div style=" padding-left:10px; padding-right:10px;">
				<table border="1" style="max-width:100%; font-size:11px;">
					<tr>
						<th style="width:5%">No.</th>
						<th style="width:10%">ID</th>
						<th style="width:20%">Group Loan Name</th>
						<th style="width:15%"> NRC No </th>
						<th style="width:10%">Loan Account ID </th>
						<th style="width:10%">Amount</th>
						<th style="width:10%">Signature</th>
						<th style="width:10%">Sign Of  </th> 
					</tr>
					<tr>
						<td>1 </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td> 
					</tr>
					<tr>
						<td>2 </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td> 
					</tr>
					<tr>
						<td>3 </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td> 
					</tr><tr>
						<td>4 </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td> 
					</tr>
					<tr>
						<td>5 </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td>
						<td> </td> 
					</tr>					
				</table>				 
			</div>
			
			<div>
				<p style="text-align:center; padding-top:15px;" class="small-letter"> </p>
			</div>
			<div class="rules">
					<div class="rules_header" style="text-align: center; padding-top:15px;"><b>အထူးစည်းမျဉ်းများ</b></div>
					<div class="rules_body">
						<ol>
							<li style="word-wrap: break-word;padding: 15px;text-align: justify;">
								လူၾကီးမင္း၏ေခ်းေငြေလွ်ာက္လႊာအတြက္ ေက်းဇူးတင္ရွိပီး <?php echo $setting->site_name ?> ထံမွ ေခ်းေငြထုတ္ယူေသာအခ်ိန္မွစ ၍ ေခ်းေငြျပန္ဆပ္သည့္အခ်ိန္ထိ ၄င္းစည္းမ်ဥ္း စည္းကမ္းမ်ားကိုနားလည္ရန္ ႏွင့္ ၄င္းစည္းမ်ဥ္းစည္းကမ္းတြင္ ပါရွိသည့္အတိုင္း ေပးထားေသာကတိမ်ားကိုေစာင့္ထိန္းရန္ အေရးၾကီးပါသည္။ 
								ကိုယ္ေရးအခ်က္အလက္မ်ားႏွင့္ပတ္သက္ေသာ သတင္းမ်ားကို အခ်ိန္ႏွင့္တေျပးညီအသိေပးရန္ ကတိျပဳျပီး အကယ္၍ အေျပာင္းအလဲရွိပါက ခ်က္ခ်င္း <?php echo $setting->site_name ?> ထံသို႕အေၾကာင္းၾကားရန္ လိုအပ္ပါသည္။ 
							</li>
							<li style="word-wrap: break-word;padding: 15px;text-align: justify;">
								ေခ်းေငြ အတိုးႏွဳန္းမွာတစ္လလွ်င္ ၂.၅% အတိုးႏႈန္း ျဖစ္ပီးေခ်းေငြလက္က်န္စာရင္းအေပၚတြင္ ရက္ေပါင္း ၃၀ ႏႈန္းျဖင့္အတိုးကိုတြက္ခ်က္ပါသည္။ေစာလ်င္စြာ (သို႔) ေနာက္က်စြာ (သို႔) ပိုမ်ားေသာပမာဏမ်ား (သို႔) ပိုနည္းေသာပမာဏမ်ားကိုကၽြႏု္ပ္ တို႔၏ ခြင့္ျပဳခ်က္ မပါရွိပဲ ျပန္လည္ေပးေခ်ျခင္းမျပဳလုပ္ရပါ။ 
							</li>
							<li style="word-wrap: break-word;padding: 15px;text-align: justify;">							
								ထုတ္ေခ်းေငြ၏  ၁%  ကိုစီမံခန႔
								္ခြဲရန္
								  အခေၾကးေငြ
								  ၀န္ေဆာင္ခ  အေနျဖင့္
								  လည္ေကာင္း  ၊၁%  ကိုလူမႈေထာက္ပံ့ေရးရန္ပံုေငြအျဖစ္
								  လည္ေကာင္း  ၊  ေခ်းေငြ၏  ၃%ကို 
								မျဖစ္မေနစုေဆာင္းေငြအျဖစ္လည္ေကာင္း  ေခ်းေငြထုတ္သည့္ေန႔
								  တြင္
								  တစ္ၾကိမ္ေကာက္ခံမည္ျဖစ္သည္။  လူမႈေထာက္ပံ့ေရးရန္ပံုေငြ
								  ေပးသြင္းျခင္းျဖင့္<?php echo $setting->site_name ?> ႏွင့္
								  အဖြဲ
								႕၀င္ျဖစ္ေနစဥ္
								အတြင္း  ေငြေခ်းသူေသဆံုးပါက  ေခ်းေငြမ်ားကိုပယ္ဖ်က္ေပးရန္
								  အေထာက္အကူျပဳပါသည္။<?php echo $setting->site_name ?>တြင္ ေခ်းထားေသာေခ်းေငြမ်ားမရွိေတာ့ေသာအခါ ႏွင့္<?php echo $setting->site_name ?>၏ အဖြဲ
								႕၀င္အ ျဖစ္								မွ
								 ႏွဳတ္ထြက္ 
								ေသာအခါတြင္္
								 မျဖစ္မေနစုေဆာင္းေငြအားလံုးကိုထုတ္ယူႏုိင္မည္
								 ျဖစ္
								သည္။စုေငြအတိုးႏွဳန္းမွာ 1.25% ျဖစ္ပီးတစ္လ ရက္
								(၃၀ )ထားပီးအတိုးတြက္ခ်က္ပါသည္။
							</li>
							<li style="word-wrap: break-word;padding: 15px;text-align: justify;">
								ျပန္လည္ေပးဆပ္ရမည့္ေငြအားလံုးကိုေပးဆပ္ရမည့္ေန႔
								တြင္
								 ညေန(2)နာရီထက္ ေနာက္မက်ဘဲေပးသြင္းရပါမည္။ ထိုေန႔
								တြင္
								 ညေန(2)နာရီထက္ေနာက္က်၍ေပးသြင္းပါက 
								ေနာက္က်   ေပးသြင္းသည္ဟုသတ္မွတ္ျပီး   ေနာက္က်ေၾကးေပးသြင္းရမည္ျဖစ္သည္။၁ ရက္ထက္  ေနာက္က်ပါက ေနာက္က်ေၾကးေပးသြင္းရမည္ျဖစ္ပီး 
								ထိုေနာက္က်ေၾကးသည္
								   တစ္ၾကိမ္ျပန္ဆပ္ေငြပမာဏ၏   ၁%   ျဖစ္
								ပီး   ၄င္းကိုေနာက္က်သည့္အခါတိုင္းေနာက္က်ေၾကးအျဖစ္
								   ေပးသြင္းရပါမည္။ေငြျပန္ဆပ္ရန္
								ပ်က္ကြက္ခဲ့လွ်င္
								 ေနာက္က်ေၾကးအျပင္
								 အျခားအေရးယူမႈမ်ားကို
								 ျပဳလုပ္
								မည္ကို
								 ထုတ္ေခ်းသူဘက္
								က  သိရွိရန္
								 လိုအပ္
								ပါသည္။ 
							</li>
							<li style="word-wrap: break-word;padding: 15px;text-align: justify;">
								အကယ္၍ အဖြဲ
								႕၀င္ တစ္ဦးဦး အခ်ိန္မွီျပန္လည္ေပးဆပ္ရန္
								 ပ်က္ကြက္ျခင္း၊  ေနာက္က်ျခင္း မ်ားျဖစ္ေပၚပါက ၀ိုင္းၾကီးခ်ဳပ္၏ စည္းကမ္းအရ က်န္ေသာ ၀ိုင္းၾကီးခ်ဳပ္
								 အဖြဲ
								႕၀င္
								မ်ား  အညီအမွ်  တာ၀န္ယူ  ေျဖရွင္းေပးရမည္  ျဖစ္ပီး<?php echo $setting->site_name ?> ထံသို
								႕  ေငြကို  ခ်က္ခ်င္းျပန္လည္ေပးေခ်ရန္
								  တာ၀န္
								ရွိ
								ပါသည္။ 
								သင္သည္ေခ်းေငြအဖြဲ
								႕၀င္ျဖစ္ေနစဥ္ကာလအတြင္း    ဥပေဒ    (သို
								႕)    စည္းမ်ဥ္းစည္းကမ္းမ်ား    ေျပာင္းလဲျခင္းမ်ားရွိပါက    (သို
								႕မဟုတ္)    ဥပေဒ    (သို
								႕) 
								စည္းမ်ဥ္းစည္းကမ္းအသစ္မ်ား  ထြက္  ရွိပါက<?php echo $setting->site_name ?>မွဥပေဒ  ႏွင့္
								  အညီ
								  စည္းမ်ဥ္းစည္းကမ္းအေျပာင္းအလဲ
									ျပဳလုပ္
								မည္ကို
								လည္းသေဘာတူ ပါသည္။  
							</li>
							<li style="word-wrap: break-word;padding: 15px;text-align: justify;">
								<?php echo $setting->site_name ?>ထံ
								မွ
								  ေငြေခ်းယူသူသည္
								  ရရွိသည့္
								 ေခ်းေငြကို
								  ေခ်းေငြစာခ်ဳပ္တြင္
								 ေဖာ္ျပထားေသာ လုပ္ငန္းမ်ား လုပ္ကိုင္ရန္အတြက္သာ 
								အသံုးျပဳရမည္
								 ျဖစ္ပီး ျပည္ေထာင္စု
								 သမၼတ ျမန္မာနိုင္ငံေတာ္၏တည္ဆဲ ဥပေဒမ်ားအရ တားျမစ္ထားသည့္ အျခားေသာ လုပ္ေဆာင္မွဳ မ်ားအတြက္
								 အသံုးမျပဳရန္
								လိုအပ္ပါသည္။ 
							</li>
							<li style="word-wrap: break-word;padding: 15px;text-align: justify;">
								ေငြေခ်းသူ အေနျဖင့္  စာခ်ဳပ္ပါ အခ်က္မ်ား အားလံုးကို အတိအက် လိုက္နာ ေဆာင္ရြက္ရန္  လိုအပ္ပီး  အကယ္ ၍ စာခ်ဳပ္ပါ အခ်က္မ်ားက္ု ခ်ဳိးေဖာက္လွ်င္  ျပည္ေထာင္စု သမၼတ ျမန္မာ နိုင္ငံ ေတာ္ ၏တည္ဆဲ ဥပေဒမ်ားအရ အေရးယူ ေဆာင္ရြက္ခံရမည္ ျဖစ္ပီး တရားစြဲဆိုမွဳ(သိဳ႕)  ေျဖရွင္းမွဳ႕ႏွင့္ စပ္လ်ဥ္းသည့္ ကုန္က်စရိတ္ စုစုေပါင္းကို ပ်က္ကြက္သူ(သို႕) ခ်ိဳးေဖာက္သူဖက္မွ က်ခံရမည္ ျဖစ္သည္ -စသည့္ စည္းမ်ဥ္းစည္းကမ္း အခ်က္မ်ားကို  ေကာင္းစြာ လိုက္နာ ေဆာင္ရြက္မည္ ျဖစ္ေၾကာင္းကို မိမိတို႕၏ သေဘာ ဆႏၵအရ  လတ္မွတ္ေရးထိုးပါသည္ 
							</li>
						</ol>
					</div>
			</div>
			<div class="phara" style="overflow:hidden;width:100%;min-height:100px;clear:both;padding-top:15px; padding-left:10px;">	
				<div style="padding-left:50px;">
					<p>ေငြေခ်းသူႏွင့္ ပူးတြဲ ေငြေခ်းသူ၏ ဘယ္လက္မလက္ေဗြ​ុ ေငြေခ်းသူ </p>
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