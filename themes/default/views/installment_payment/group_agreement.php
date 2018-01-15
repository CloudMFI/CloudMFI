<!DOCTYPE html>
<?php  
	//$this->erp->print_arrays($total);
	//$this->erp->print_arrays($group);

?>
<html>
	<head>
		<title><?php echo $contract->reference_no?$contract->reference_no:'N/A';?></title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Battambang" rel="stylesheet"> 
	<style type="text/css">
        html, body {
            height: 100%;
        }
        p{
        	text-align: justify;
        }
		.contain-wrapper {
		width: 21cm;
		min-height: 29.7cm;
		padding: 2cm;
		margin: 1cm auto;
		//border: 1px #D3D3D3 solid;
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
		.chat tr td {
			padding:10px;
		}
		.order-num{
			font-weight:bold;
		}
		#logo img{
			width:150px;
		}
		.pha2-text{
			line-height: 25px;

		}
	</style>
	</head>
	<body>
		<div class="contain-wrapper" style="padding:0; margin:0 auto;padding-bottom: 30px;">
			<div class="header" style="width:100%;float:left;">
				<div class="brand-name" style="width:20%; float:left;margin-top:40px; margin-left:30px;">
					<div style="float:left;" id="logo">
					<span> <?php if ($Settings->logo2) {
						echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" style="margin-bottom:10px;" />';
					} ?> </span> 
					</div>
					<div style="margin-top:50px; margin-left:30px;">
						 <?php echo $this->session->branchName ?>&nbsp; 
					</div>
				</div>				
				<div class="nationality-identifier" style="width:50%; float:left;margin-top:20px;position:relative;">
					<p style="font-family:Zawgyi-One;text-align:center;font-size:18px;"​><b>ျပည္ေထာင္စုသမၼတျမန္မာနိုင္ငံေတာ္</b></p>
					<p style="font-family:Zawgyi-One;text-align:center;font-size:1၈px;"><b>ေငြေခ်းငွားျခင္းစာခ်ဳပ္</b></p>
									
				</div>
				<div style="width:20%; float:left;margin-top:70px;">
				</div>
				<div style="width:20%; float:left;margin-top:10px;">
					<span style="font-size:12px;">ေျပစာအမွတ္ &nbsp; <b> <?php echo $contract->reference_no?$contract->reference_no:'N/A';?></b></span><br>

					<span style="font-size:12px;">ေခ်းေငြစာရင္း &nbsp; <b> </b></span><br>

					<span style="font-size:12px;">    အုပ္စု        &nbsp; <b> </b></span><br>
				</div>
							
			</div>
			<div class="phara" style="overflow:hidden;width:100%;min-height:100px;clear:both;padding-top:15px;padding-left: 30px;">
				<div class="pha1-num" style="float:left;">
				<p class="order-num" style="padding-left: 5px">၁။</p>
				</div>
				<div class="pha1" style="float:left;width:97%;">
					<p class="pha2-text" style="font-size:14px;">
						ေငြေခ်းငွားသူ (ျမီးရွင္) တရား၀င္ကိုယ္စားျပဳေဆာင္ရြက္သူအမည္ - ေဒၚ <b> <?= $contract->approv_name?$contract->approv_name:'N/A' ?> </b> ၊ရံုးခြဲမန္ေနဂ်ာ၊ ပဲခူးရံုးခ်ဳပ္ မိုရာေကာ့မိုက္ခရို ဖိုင္းနန့္ (စ္) ျမန္မာ ကုမၸဏီ(ဤမွစ၍ပုဂိၢဳ လ္’က”ဟုေခၚသည္။)
					</p>
					<p style="font-size:14px;text-align: center">ႏွင္႔</p>
				</div>

				<div class="pha2-num" style="float:left;">
				<p class="order-num" style="padding-left: 5px">၂။  </p>
				</div>
				<div class="pha2" style="float:left;width:97%;">
					<p class="pha2-text" style="font-size:14px;">
						ေငြေခ်းယူသူ(ျမီးစား) ိုယ္စားလွယ္အမည္ <b> <?= $contract->customer_name?$contract->customer_name:'N/A' ?> </b>
						<br>
						လိပ္စာ  <?= $village->village ?> / <?= $sangkat->sangkat?> / <?= $sangkat->sangkat?> / <?=$district->district?> / <?=$state->state?> / <?= $contract->house_no?>

					</p>
					<P style="font-size:14px;text-align: center">အဖြဲ႕၀င္အေရအတြက္ <b> <?= $group_name->name ?> </b> ဦး</P>
					<p style="font-size:14px;float:left;width:97%;">ေငြေခ်းယူသူမ်ား၏ အမည္မ်ားႏွင့္ လက္၀ဲလက္မပံုစံမ်ားကိုေအာက္္တြင္စာရင္းျပဳစုထားသည္။</p>
				</div>
				<div>
					<P style="font-size:14px;text-align: center">(ဤမွစ၍ပုဂိၢဳလ္’ခ”ဟုေခၚသည္။)</P>
					<p style="font-size:14px;float:left;width:97%;">ပုဂိၢဳလ္’က’ နွင့္ ပုဂိၢဳလ္’ခ”  တို့သည္ ေငြေခ်းငွားစာခ်ဳပ္တြင္ ပါရွိ သည့္ ေအာက္ေဖၚျပပါ စည္းမ်ဥ္းစည္းကမ္းမ်ားကို  လိုက္နာရန္ သေဘာတူႀကပါသည္။</p>
				</div>
				<div style="height:120px;"></div>

				<p style="text-align:center;"><span style="float: left;"><b>အပိုဒ္ ၁  </b></span> <b style="text-align:center;">အေထြေထြစည္းကမ္းခ်က္မ်ား</b></p>
				<div>
					<p style="float: left;line-height: 25px;padding-left: 40px;">ေငြေခ်းေလွ်ာက္လႊာ အေထြေထြေငြေခ်းငွားျခင္း  လက္ဆြဲလမ္းညွြန္းခ်က္ႏွင့္  စည္းကမ္းခ်က္မ်ားတြင္ ပါရွိသည္႔  ညြွန္းႀကားခ်က္မ်ားအေပၚ အေျခခံ၍ ပုဂိၢဳလ္’က’သည္ ပုဂိၢဳလ္’ခ” အားေငြထုတ္ေခ်းရန္ သေဘာတူပီး ပုဂိၢဳလ္’ခ’ကလည္း ေအာက္တြင္ေဖၚျပပါ ေခ်း ေငြပမာဏႏွင့္ စည္းကမ္းခ်က္မ်ားႏွင့္ အညီ ပုဂိ ၢဳလ္’က’သို႕ျပန္လည္ေပးဆပ္ရန္ သေဘာတူပါသည္၊</p>
				</div>
				
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၁-၁</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						ေခ်းေငြလံုျခံုေရးအတြက္ ထားရွိေသာ ကြ်န္ေတာ္/ ကြ်န္မတို၏ ပိုင္ဆိုင္မွု့မ်ားမွာ အုပ္စုေခ်းေငြကတိက၀တ္ ျပဳ သေဘာတူညီ ခ်က္ထဲတြင္   ေဖၚျပထားသည့္ 
              			အတိုင္းျဖစ္သည္၊

					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၁-၂ </p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						 ပုဂိၢဳလ္’ခ”လက္ခံရရွိသည္္႕ ေခ်းေငြပမာဏ (ဂဏန္းျဖင့္)<?= $this->erp->roundUpMoney($this->erp->convertCurrency($saleiterm->currency_code,$setting->default_currency, $contract->total), $saleiterm->currency_code); ?>က်ပ္ ( စာျဖင့္)<?=$saleiterm->description?>က်ပ္					
					
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၁-၃</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						 ေခ်းေငြသက္တမ္း <?= round($contract->terms) ?>ရက် (ေခ်းေငြလက္ခံရရွိသည့္ေနမွ စတင္ေရတြက္သည္)
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၁-၄ </p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						  ျပန္လည္ေပးေခ်မွု့ ပံုစံ; ျပန္လည္ေပးေခ်မွဳ့ဇယားအတိုင္းျဖစ္သည္ 
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၁-၆ </p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						 တလအတိုးနွုန္း၂.၅% 
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၁-၅ </p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						ျပန္လည္ေပးဆပ္ရမည္ေနရာနွင့္ဌာန
					</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:55px;">
					<p class="pha2-text" style="font-size:14px;">
						- မိုရာေကာ့မိုက္ခရို ဖိုင္းနန့္ (စ္) ျမန္မာကုမၼဏီရံုးထံ သို့ တိုက္ရိုက္ေပးေခ်မွဳ့ 
					</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:55px;margin-bottom: 30px;">
					<p class="pha2-text" style="font-size:14px;">
						- ျပန္လည္ေပးဆပ္ျခင္းဇယား ေခ်းေငြကတိက၀တ္ျပဳစာခ်ဳပ္ ေခ်းေငြထုတ္ေပးမွဳ့ ေျပစာ (သိဳ႔မဟုတ္) ေခ်းေငြထုတ္ျဖတ္ပိုင္းမ်ားအား FDF ----------------------  
  ပုံစံ ေငြလက္ခံရရွိေႀကာင္းေျပစာ (ေခ်းေငြ၀န္ေဆာင္ခ ရွိခဲ့လွ်င္) တို႔သည္ဤစာခ်ဳပ္၏ ေနာက္ဆက္တြဲမ်ားအျဖစ္ပါရွိပါသည္။

					</p>
				</div>

				<div style="height:150px;"></div>

				<p style="text-align:center;"><span style="float: left;"><b>အပိုဒ္(၂)</b></span> <b style="text-align:center;">အထူးစည္းကမ္းခ်က္မ်ား</b></p>
				<div>
					<p style="float: left;line-height: 25px;padding-left: 40px;">အကယ္ ၍ ပုဂိၢဳလ္’ခ”သည္ အပိုဒ္(၁) ေအာက္ရွိ(၁-၁ ၊၁-၂၊၁-၃၊၁-၄၊၁-၅ ၊၁-၆)တို႕အတိုင္း ေငြအရင္းႏွင့္အတိုးတို႕ကို  ေပးေခ်ရန္  ပ်က္ကြက္ပါက ပုဂိၢဳလ္’က” သည္ ပုဂိၢဳလ္’ခ”အား ေအာက္ပါအတိုင္းအေရးယူေဆာင္၇ြက္နိုင္သည္။</p>
				</div>
				
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၂-၁ -</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						အကယ္ ၍ အုပ္စုအေနျဖင့္ ျပန္လည္ေပးေခ်မွဳဇယားအတိုင္း ျပန္လည္ေပးေခ်ရန္ ေနွာင့္ေနွးမွုမ်ားရွိပါက ဓဏ္ေၾကးေပးေဆာင္ရန္ အုပ္စုက သေဘာတူပါသည္။   
             ဒဏ္ေၾကးမွာ  ၅.၀၀ % ျဖစ္ပီး ေပးေခ်ရန္ပ်က္ကြက္သည့္  ေငြေၾကးပမာဏအေပၚ မူတည္ ၍ တြက္ခ်က္မည္ ျဖစ္သည္။ဒဏ္ေၾကးေပးေဆာင္ရန္အတြက္  
            တြက္ခ်က္သည့္ေန႕တြင္ျပန္လည္ေပးေခ်ရန္ပ်က္ကြက္သည့္ေန႕၏ ေလးရက္ေျမာက္ေန႕တြင္ စတင္ေရတြက္မည္ျဖစ္ပီး   ၄င္းအတြက္ ေပးေခ်မွဳကို ေငြသားျဖင့္ 
            တိုက္ရိုက္ေပးေခ်ရမည္ ။ 


					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၂-၂</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						 အုပ္စုအဖြဲ႕၀င္တစ္ဦးဦးက  ၄င္းေခ်းယူထာသည့္  ေခ်းေငြမ်ားသက္တမ္းမကုန္မွီ အျပည့္အ၀ေပးေခ်ပါက  ေခ်းေငြသက္တမ္းကုန္ဆံုးရန္ က်န္ရွိေနေသးသည့္ ရက္အေပၚ မူတည္ကာ  အတိုးကို ေလ်ာ့ခ်ေပးမည္ျဖစ္သည္။ ထိုေၾကာင့္ က်န္ရွိေနေသးသည့္ အတိ္ုး၏ ၇၅% ကို ၄င္းအဖြဲ႕၀င္အား ေပးမည္ ျဖစ္သည္။					
					
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၂-၃</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						 အကယ္ ၍ အုပ္စုအဖြဲ႕၀င္တစ္ဦးဦးက   ၄င္းေခ်းယူထားသည့္ ေခ်းေငြမ်ားအားလံုးကို  ေခ်းေငြသက္တမ္းမကုန္ဆံုးမည္ အျပည့္အ၀ျပန္လည္ေပးေခ်ပီး ေသာ္ လည္း အျခားအဖြဲ႕၀င္မ်ားအားလံုး  ေခ်းေငြကို အျပည့္အ၀  ျပန္လည္ေပးေခ်နိုင္သည့္အထိ  ၄င္းတြင္ တာ၀န္ရွိေနမည္ျဖစ္သည္။
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၂-၄</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						 အုပ္စုအဖြဲ႕၀င္အားလံုးႏွင့္ အုပ္စုကိုယ္စာလွယ္မ်ားသည္ ပူးတြဲထားသည့္ ေပးေခ်မွုဇယားအတိုင္ အေၾကြးမ်ားကို ေပးေခ်ရန္ သေဘာတူပီး တစ္ဦးႏွင့္ တစ္ဦး အခ်င္းခ်င္း တာ၀န္ယူရန္ ကတိျပဳၾကသည္။ အကယ္ ၍  အုပ္စု၏ အဖြဲ႕၀င္တစ္ဦးဦးက အေခ်းမ်ားကို ျပန္လည္ေပးေခ်ရန္ ပ်က္ကြက္ပါက အျခားအဖြဲ႕၀င္မ်ားက ျပန္လည္ေပးေခ်မွု ဇယားတြင္ ေဖာ္ျပသည့္အတိုင္း  ေငြရင္းႏွင့္ အတိုးမ်ားကို ၄င္းကိုယ္စား ေပးေခ်ရမည္ျဖစ္သည္။
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၂-၅</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						 ကိုယ္စားလွယ္ ေပးေခ်မည့္အဖြဲ႕သည္ ျပန္လည္္ေပးေခ်ရန္ ပ်က္ကြက္သူ ၏   ပိုင္ဆိုင္မွဳပစၥည္းမ်ားကို ေလလံေစ်းျဖင့္ေရာင္းခ်ရန္  တာ၀န္ရွိေၾကာင္း က်ြန္ေတာ္ / က်ြန္မတို႕ သိရွိသေဘာတူပါသည္။ 
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၂-၆</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						ေခ်းေငြမ်ားအျပည့္အ၀ မေပးေခ်နိုင္ေသးမွီ   ပုဂိၢဳလ္’က ထံတြင္ တင္ျပထားပီး  ပိုင္ဆိုင္မွဳမ်ားကို  ပုဂိၢဳလ္’က ၏ သေဘာတူညီခ်က္မရပဲ  မည္သည့္ ပုဂိၢဳလ္’ခ’ အဖြဲ႕၀င္မဆို  ပုဂိၢဳလ္တစံုတစ္ေယာက္ထံသို႕   ေရာင္းခ်ျခင္း  လဲလွယ္ျခင္း  လႊဲေျပာင္းျခင္း  အပ္ႏွံျခင္းမ်ား ျပဳလုပ္ခြင့္မရွိေပ ။
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၂-၇</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						အကယ္ ၍  ျပန္လည္ေပးေခ်မွဳမ်ားကို တစ္ဦးခ်င္းေပးေခ်မွဳဇယားအတိုင္းအခ်ိန္မွီ ေပးေခ်ရန္ ပ်က္ကြက္ပါက  ပုဂိၢဳလ္’က” ထံသို႕ ေပးေနေသးသည့္  ေငြရင္းအပါအ၀င္ အတိုးနွဳန္း ဒဏ္ေၾကး ကိုပါ ေပးေခ်နိုင္ရန္   က်ြန္ေတာ္ / က်ြန္မတို႕၏ ပိုင္ဆိုင္မွဳမ်ားကို ေရာင္းခ်ရန္ သေဘာတူပါသည္။
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၂-၈</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						အကယ္ ၍ အေၾကြးဆပ္ရန္ ပ်က္ကြက္သည့္ ေန႕မွ စ ၍ တစ္လအတြင္း ပိုင္ဆိုင္မွဳမ်ားကို ေရာင္းခ်ရန္ ၊ မတတ္နိုင္ပါက အုပ္စုကို တာ၀န္ယူရန္ ကတိက၀တ္ျပဳသူထံ အပ္နွံပီး ေလလံေစ်းျဖင့္ ေရာင္းခ်ေစကာ  ပုဂိၢဳလ္’က’ အား ၄င္းေပးေခ်ရန္ ရွိသည္မ်ားကို ေပးေခ်ရန္  သေဘာတူပါသည္။
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၂-၉</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						က်ြနု္ပ္တို႕အေနျဖင့္  အပိုင္း  (၂-၈ )တြင္ ေဖာ္ျပထားသည့္အတိုင္ လုပ္ေဆာင္ရန္  ပ်က္ကြက္ပါက  ပုဂိ ၢဳလ္’က’အေနျဖင့္ ပိုင္ဆိုင္မွဳမ်ားကို ေလလံေစ်းျဖင့္ ေရာင္းခ်ရန္  သက္ဆိုင္ရာ အာဏာပိုင္မ်ား  တရားရံုးတို႕တြင္  ဦးတိုက္ ေလွ်ာက္ထားနိုင္သည္ကို  သေဘာတူသိရွိနားလည္ပါသည္။ သို႕ျဖစ္သည့္တိုင္  ပုဂိၢဳလ္’ခ’သည္  ေခ်းေငြကို  အျပည့္အ၀ျပန္လည္မေပးဆပ္နိုင္ေသးပါက  ေက်လည္သြားသည္အထိဆက္လက္ ေပးေခ်သြားရမည္။
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၂-၁၀</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						ေငြေခ်းစာခ်ဳပ္သက္တမ္းကာလအတြင္ း ပုဂိၢဳလ္’ခ’သည္ တခ်ိန္ခ်ိန္တြင္ ေငြေပးဆပ္ရန္ ပ်က္ကြက္ျခင္းေၾကာ င့္ ပုဂၢိဳလ္ ’က ’  က ပုဂိၢဳလ္’ခ’ အား  မယံုၾကည္ ေတာ့ပဲ  မိမိေခ်းေငြ ဆံုးပါးသြားမည္ကို စိုးရိမ္ေၾကာင္းက်မွဳ ရွိလာေသာေၾကာင့္ စာခ်ဳပ္သက္တမ္းမေစ့မွီ တရားရံုးကို ေတာင္းဆို ၍ ေခ်းေငြစာခ်ဳပ္အား  ဖ်က္ သိမ္း ျခင္းကို  ပုဂိၢဳလ္’ခ’သည္ ျခြင္းခ်က္မရွိ  သေဘာတူရမည္။ ထိုအျပင္ ပုဂိ ၢဳလ္’ခ’  သည္ ဒဏ္ေၾကးအပါအ၀င္ အတိုးအရင္းကို ပုဂၢိဳလ္’က ’ သို႕ ေပးဆပ္ ရမည္။ အက ယ္၍ ပုဂိၢဳလ္’ခ’ သည္ ပုဂိၢဳလ္’က’အား ေခ်းေငြ အျပည့္အ၀ ျပန္လည္ မေပးဆပ္နိုင္ပါက ပုဂိ ၢဳလ္’က ’ သည္    မိမိေခ်းေငြ  ျပန္လည္ေပး ဆပ္ နိုင္ရန္အတြက္  ပုဂိၢဳလ္’ခ’ မွတင္ျပထားသည့္ တရာ၀င္ပိုင္ဆိုင္ထားေသာ ေရြ႕ေျပာင္းနိုင္ေသာ ပစၥည္း ၊ မေရြ႕ေျပာင္းနိုင္ေသာ ပစၥည္းမ်ားအား ယာယီသိမ္းယူ ထားနိုင္ ေရးႏွင့္  ေခ်းေငြျပန္လည္ရရွိရန္ ေလလံတင္ ေရာင္းခ်နိုင္ေရးအတြက္ တရားရံုးသို ႕ ေလွ်ာက္ထားနိုင္ခြင့္ရွိသည္။ သို႕ျဖစ္သည့္တိုင္ေအာင္ပုဂိၢဳလ္ (ခ) သည္ ေခ်းေငြမ်ား အျပည့္အ၀ေက်လည္ေအာင္  မေပးဆပ္နိုင္ လွ်င္ အားလံုးေက်လည္သြားသည့္အထိ ေပးသြင္းရန္ တာ၀န္ရွိသည္။ အထက္ပါကိစၥမ်ားကို ေျဖရွင္းရာတြင္ ကုန္က်စရိတ္မွန္သမွ်  ပုဂိၢဳလ္ (ခ)က ကုန္က်ခံရမည္။
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num" style="padding-right: 20px;">၂-၁၁</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						ပုဂိၢဳလ္ (ခ) က ေၾကြးမဆပ္နိုင္လွ်င္ ၾကားခံ ပုဂိၢဳလ္ ဟုေခၚေသာ တာ၀န္ယူရန္ ကတိက၀တ္ျပဳသူ (သို႕မဟုတ္ )  ပုဂိၢဳလ္ (ခ) ၏ အေမြစားအေမြခံတို႕က ေၾကြး ေက် ေအာင္ ေပးဆပ္ရပါမည္။ ဤတြင္ ထိုၾကားလူသည္ ပုဂိၢဳလ္ (ခ) ၏ ျမီးရွင္ျဖစ္လာပီး ပုဂိၢဳလ္ (ခ) အေနျဖင့္ ပုဂိၢဳလ္ (က) ထံတြင္ တင္ျပထားေသာ ပစၥည္း မ်ားကို တတိယၾကားခံ ပုဂိၢဳလ္ ထံသို႔ လႊဲေျပာင္းေပးျခင္းကို သေဘာတူရပါမည္။ၾကားခံ ပုဂိၢဳလ္က ယင္းပစၥည္းမ်ားအား မည္သည့္ သင့္ေလ်ာ္ေသာ နည္းလမ္း ျဖင့္ မဆို ထိန္းသိမ္းေစာင့္ ေရွာက္ျခင္း စီမံခန္႕ခြဲျခင္းႏွင့္ စီစဥ္ေဆာင္ရြက္ျခင္း တို႕ ျပဳလုပ္သြားပါမည္။ထို႕အျပင္ ပုဂိၢဳလ္(က )သည္ ယင္းပစၥည္းမ်ား ပ်က္စီး ဆံုးရွံဳး ျခင္း၊ ပစၥည္းတန္ဖိုးအက်ိဳးအျမတ္မ်ား ေလ်ာ့နည္းသြားျခင္းတို႕အတြက္ တာ၀န္မရွိပါ ။ ယင္းတို႕ျဖစ္ျခင္းတို႕အတြက္ ပုဂိၢဳလ္(ခ)  သည္ ပုဂိၢဳလ္(က )အား ကန္႕ကြက္ျခင္း (သို႕မဟုတ္ ) ေလ်ာ္ေၾကးေတာင္းဆိုျခင္းမ်ားမျပဳလုပ္ရန္ သေဘာတူပါသည္။
					</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:55px;">
					<p class="pha2-text" style="font-size:14px;">
						- မိုရာေကာ့မိုက္ခရို ဖိုင္းနန့္ (စ္) ျမန္မာကုမၼဏီရံုးထံ သို့ တိုက္ရိုက္ေပးေခ်မွဳ့ 
					</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:55px;">
					<p class="pha2-text" style="font-size:14px;margin-bottom: 30px;">
						- ျပန္လည္ေပးဆပ္ျခင္းဇယား ေခ်းေငြကတိက၀တ္ျပဳစာခ်ဳပ္ ေခ်းေငြထုတ္ေပးမွဳ့ ေျပစာ (သိဳ႔မဟုတ္) ေခ်းေငြထုတ္ျဖတ္ပိုင္းမ်ားအား FDF ----------------------  
  ပုံစံ ေငြလက္ခံရရွိေႀကာင္းေျပစာ (ေခ်းေငြ၀န္ေဆာင္ခ ရွိခဲ့လွ်င္) တို႔သည္ဤစာခ်ဳပ္၏ ေနာက္ဆက္တြဲမ်ားအျဖစ္ပါရွိပါသည္။

					</p>
				</div>
				<br><br><br>

				<div style="height:150px;"></div>

				<p style="clear: both;"><span style="text-align: left;"><b>အပိုဒ္(၃)</b></span> 
					<span style="padding-left: 30%;"><b>ေနာက္ဆံုးစည္းကမ္းခ်က္မ်ား</b></span></p>
				<div>
					<p style="float: left;line-height: 25px;padding-left: 40px;">ပုဂိၢဳလ္(က ) ႏွင့္ ပုဂိၢဳလ္(ခ )တို႕သည္ စာခ်ဳပ္ပါ စည္းကမ္းခ်က္မ်ားအား ေလးစားလိုက္နာ ေဆာင္ရြက္ရန္  သေဘာတူၾကပါသည္။ မည္သည့္အေၾကာင္းေၾကာ င့္ျဖစ္ေစ  လိုက္နာရန္ ပ်က္ကြက္ပါက စည္းကမ္းေဖာက္ဖ်က္သူသည္ တရားဥပေဒအ၇ ရင္ဆိုင္ေျဖရ်င္းေဆာင္ရြက္ရန္ တာ၀န္ရွိပါသည္။ အျငင္းပြားမွဳ ေျဖရွင္းရာတြင္ ကုန္က်စရိတ္အားလံုးကို စည္းကမ္းေဖာက္ဖ်က္သူ အမွားက်ဴးလြန္သူက အကုန္က်ခံရပါမည္။ ဤ စာခ်ဳပ္ကို မည္သည့္အားဓမၼတိုက္တြန္း ခုိင္းေစခ်က္မွ မပါပဲ  မိမိတို႕၏ သေဘာဆႏၵအေလ်ာက္ ခ်ဳပ္ဆိုၾကျခင္း ျဖစ္ပီး လတ္မွတ္ထိုး လက္ေဗြနိပ္သည့္ ေန႕မွစပီး အက်ိဳးသက္ေရာက္ အတည္ျဖစ္ပါသည္။</p>
				</div>
				<div>
					<p style="float: left;line-height: 25px;padding-left: 40px;">ဤစာခ်ဳပ္ကို သံုးေစာင္ ျပဳစုခ်ဳပ္ဆိုထားပါသည္။(မူရင္းႏွစ္ေစာင္ ႏွင့္ မိတၱဴတစ္ေစာင္ ထားရွိပါသည္။</p>
				</div>
				<div>
					<p style="float: left;line-height: 25px;padding-left: 40px;">
						<span>ပုဂိၢဳလ္(က )…………………………………မူရင္းႏွစ္ေစာင္</span><br>
						<span>ပုဂိၢဳလ္(ခ  )…………………………………မိတၱဴတစ္ေစာင္</span><br>
					</p>
				</div><br><br>
				
				<div class="letter-footer" style="clear:both;width: 100%; height:200px; font-size:14px;text-align: center;margin:30px 0px 30px 0px">		
					
					<div class="left-footer">
						<p style="text-align:center;font-family:Zawgyi-One;">
							 <span>ျမီးစား/ ပုဂိၢဳလ္(ခ )</span><br>
							 <span>ေငြေခ်းယူမ်ား၏လက္၀ဲလက္မလက္ေဗြမ်ား</span><br>
						</p>
					</div>
					<div style="width: 150px;height: 150px;border: 2px solid black;float: left;margin-top: 30px;">
					</div>	
					<div style="width: 150px;height: 150px;border: 2px solid black;margin-left: 30px;float: left;margin-top: 30px;">
					</div>
					<div style="margin-left:50px;margin-top:80px;float:left;font-family:Zawgyi-One">
						<p>ပုဂိၢဳလ္(က )၏ကိုယ္စားလွယ္</p>
					</div>	

			</div>			
		</div>
	</body>
</html>
<!--<?php echo($contract->gender=male?'checked="checked"':''); ?>
	 <?php echo $contract->gender($gender=='female')?'checked':'' ?>
-->


<!-- <!DOCTYPE html>
<?php  
	//$this->erp->print_arrays($total);
	//$this->erp->print_arrays($group);

?>
<html>
	<head>
		<title><?php echo $contract->reference_no?$contract->reference_no:'N/A';?></title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Battambang" rel="stylesheet"> 
	<style type="text/css">
        html, body {
            height: 100%;
        }
		.contain-wrapper {
		width: 21cm;
		min-height: 29.7cm;
		padding: 2cm;
		margin: 1cm auto;
		//border: 1px #D3D3D3 solid;
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
			<div class="header" style="width:100%;float:left;">
				<div class="brand-name" style="width:20%; float:left;margin-top:40px; margin-left:30px;">
					<div style="float:left;" id="logo">
					<span> <?php if ($Settings->logo2) {
						echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" style="margin-bottom:10px;" />';
					} ?> </span> 
					</div>
					<div style="margin-top:50px; margin-left:30px;">
						 <?php echo $this->session->branchName ?>&nbsp; 
					</div>
				</div>				
				<div class="nationality-identifier" style="width:50%; float:left;margin-top:20px;position:relative;">
					<p style="font-family:Khmer OS Muol;text-align:center;font-size:12px;"​>ព្រះរាជាណាចក្រកម្ពុជា</p>
					<p style="font-family:Khmer OS Muol;text-align:center;font-size:12px;">ជាតិ​​​ សាសនា​ ព្រះមហាក្សត្រ</p>
					<p style="font-family:Khmer OS Muol;text-align:center;font-size:12px;"><b> <?php echo $setting->site_name ?> </b></p>					
					<p style=" text-align:center;font-size:12px; text-align:center;"><b>កិច្ចសន្យាខ្ចីប្រាក់ </b></p>
					<p style=" text-align:center;font-size:12px; text-align:center;"><b>កិច្ចសន្យានេះត្រូវបានធ្វើឡើងរវាង  </b></p>
				</div>
				<div style="width:20%; float:left;margin-top:70px;">
				</div>
				<div style="width:20%; float:left;margin-top:10px;">
					<span style="font-size:10px;"> លេខគណនីបញ្ជី: &nbsp; <b> <?php echo $contract->reference_no?$contract->reference_no:'N/A';?></b></span>
				</div>				
			</div>
			<div class="phara" style="overflow:hidden;width:100%;min-height:100px;clear:both;padding-top:15px;">
				<div class="pha1-num" style="float:left;">
				<p class="order-num">១​.</p>
				</div>
				<div class="pha1" style="float:left;width:97%;">
					<p class="pha2-text" style="font-size:14px;">
						ភាគីឲ្យខ្ចី៖  <b> <?php echo $setting->site_name ?> </b>​  មានអាសយដ្ឋាននៅ ​ភូមិ<b><?php echo $br_village->village?$br_village->village:'NA';?> </b> ឃុំ <b><?php echo $br_sangkat->sangkat?$br_sangkat->sangkat:'NA';?> </b> ស្រុក <b><?php echo $br_district->district?$br_district->district:'NA';?> </b> <b><?php echo $br_state->state?$br_state->state:'NA';?> </b> <br> ដែលតំណាងដោយ​​ លោក/លោកស្រី<b> <?php echo $contract->approv_name?$contract->approv_name:'NA'; ?> </b> មានតួនាទីជា  <b> ប្រធានសាខា </b> តទៅនេះហៅភាគីថា  <span class="small-letter">(ក) </span>
					</p>
				</div>		
				<div class="pha2-num" style="float:left;">
				<p class="order-num">២​.</p>
				</div>
				<div class="pha2" style="float:left;width:97%;">
					<p class="pha2-text" style="font-size:14px;">
						ភាគីអ្នកខ្ចីប្រាក់៖ &nbsp; មានសមាជិករាយនាមដូចខាងក្រោម៖ <br/>
						<?php 
							$r =1;
							foreach ($group as $g){
								echo '<span> ឈ្មោះអ្នកខ្ចីទី'.$r.'   <b>'. ($g->customer_name ? $g->customer_name:"N/A") .' </b> </span>&nbsp;&nbsp;&nbsp;';
							$r++;	
							}
						?>
						ហៅភាគី​<span class="small-letter">(ខ)</span>
					</p>
				</div>
				<div style="height:120px;"></div>
				<p style="text-align:center;"> <b>ភាគីទាំងពីរបានព្រមព្រៀងគ្នាដូចតទៅ</b></p>
				
				
				<div class="pha1-num" style="float:left;">
					<p class="order-num">ប្រការ១៖</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						ភាគី<span class="small-letter">(ក) </span>​ យល់ព្រមទទួលនូវការធានាអះអាងរបស់  ភាគី​<span class="small-letter">(ខ)</span>
						ក្នុងការធានាគ្នាទៅវិញទៅមកចំពោះសមាជិកនៅក្នុងក្រុម របស់ខ្លួនដើម្បីជួយចេញសងនៅពេលដែលសមាជិក ណាម្នាក់ខកខាន ក្នុងការសងរំលោះទុនដែលបានខ្ចីពី ភាគី<span class="small-letter">(ក)</span>។ 
						ទឹកប្រាក់ ដែលសមាជិកទាំងអស់ខ្ចីមានចំនួន <b><?php echo $this->erp->convertCurrency($currency->currency_code,$setting->default_currency, $loan_amount->loan_amount)?> <?= $currency->cname ?></b>​ គត់ នៅ <b>ថ្ងៃទី  <?php echo $this->erp->hrsd($contract->app_date)?></b> ដើម្បីយកទៅចែកចាយ សមាជិកក្នុង ក្រុមធានា (ចំនួនទឹកប្រាក់សំរាប់សមាជិកម្នាក់ៗ ដូចបាន បញ្ជាក់នៅក្នុង កិច្ចសន្យាខ្ចីប្រាក់ម្នាក់ៗ ) ។
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num">ប្រការ២៖</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						 ក្នុងករណី សមាជិកនៃ</b>ភាគី<span class="small-letter">(ខ)</span>
					ណាម្នាក់ខកខានមិនបានសងប្រាក់ ‎ឬសងមិនបានគ្រប់ ចំនួន នៅក្នុងពេលណាមួយ ឬមាន បញ្ហា‎មិនអាចសងបាន សមាជិកទាំងអស់ដែលនៅក្នុង ក្រុមធានាគ្នានេះ ស្ម័គ្រចិត្ត ‎រៃប្រាក់គ្នាបង់ឲ្យ ភាគី ‎<span class="small-letter">(ក)</span> ‎ឲ្យគ្រប់ចំនួនមានប្រាក់ដើម ‎ប្រាក់ការ។</p>					
					
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num">ប្រការ៣៖</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						 ប្រសិនបើសមាជិកនៃ ភាគី<span class="small-letter">(ខ)</span>សងទៀតទាត់ល្អនោះ ភាគី<span class="small-letter">(ក)</span>នឹងមានផ្តល់រង្វាន់លើក ទឹកចិត្តតាមរយៈការបង្កើនទុនប្រាក់កម្ចីឲ្យ ភាគី <span class="small-letter">(ខ)</span> នៅពេលដែល ភាគី<span class="small-letter">(ខ)</span> មានបំណងខ្ចី ប្រាក់បន្តទៀត ពេលដែលបានបង់សងរួចរាល់តាមកាលបរិច្ឆេទកំណត់ត្រឹមត្រូវ។
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num">ប្រការ៤៖</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						  ប្រសិនបើ ភាគី <span class="small-letter">(ខ)</span> និងអ្នកធានាមិនគោរពតាមលក្ខខណ្ឌទាំងឡាយនៃកិច្ចសន្យានេះទេ ហើយមិនបានបង់ប្រាក់ រំលោះជា ច្រើនលើកច្រើនសារ ភាគី <span class="small-letter">(ក)</span>
						អាចធ្វើការប្តឹងទៅ ស្ថាប័នគតិយុត្តិដ៏សមស្របមួយដែលបានជ្រើសរើស ដោយ ភាគី <span class="small-letter">(ក)</span> ដើម្បី ទាមទារ ប្រាក់ជំពាក់ទោះបីមុនកាលកំណត់នៃការសងប្រាក់ក៏ដោយ។ 
						ប្រសិនបើមាន ការចាត់ វិធាន ការតាម ផ្លូវច្បាប់ <b>ថ្លៃឈ្នួលនឹង ថ្លៃចំណាយផ្សេងៗ តាមផ្លូវច្បាប់ គឺជាបន្ទុករបស់ភាគី ដែលរំលោភ បំពាន លើកិច្ចសន្យាទាំងស្រុង</b>។
					</p>
				</div>
				<div class="pha1-num" style="float:left;">
					<p class="order-num">ប្រការ៥៖</p>
				</div>
				<div class="pha1" style="float:left;width:90%; margin-left:10px;">
					<p class="pha2-text" style="font-size:14px;">
						  កិច្ចសន្យានេះត្រូវបានធ្វើឡើងដោយពុំមានការបង្ខិតបង្ខំពីភាគីណាមួយឡើយ ភាគីទាំងពីរ បានអាននិងព្រមព្រៀងគ្នាគ្រប់លក្ខខណ្ឌនិងខ្លឹមសារ ទាំងអស់នៃកិច្ចសន្យានេះ ហើយចុះ ហត្ថលេខា និងផ្តិតមេដៃស្តាំទុកជាភស្តុតាង ។
					</p>
				</div>
				
				<div class="letter-footer" style="with:100%; height:200px; font-size:12px;">
					<div class="left-footer" style="width: 35%; float:left;padding:10px 10px 10px; 0px;">
						<p class="left-footer" style="text-align:center;" >
							ធ្វើនៅថ្ងៃទី <?php echo $this->erp->hrsd($contract->app_date)?>
						</p>
						<p class="left-footer" style="text-align:center; font-family:Khmer OS Muol;" >
							តំណាងម្ចាស់កម្ចី
						</p>
						</p><br/><br/><br/>
						<p style="text-align:center;font-weight:bold;">
							 <?php echo $contract->approv_name?$contract->approv_name:'N/A'; ?>
						</p>
					</div>
					
					<div class="left-footer" style="width: 10%; float:left;padding:10px;">
						<p style="text-align:center;font-family:Khmer OS Muol;">
							 
						</p>
						</p><br/><br/>
						<p style="text-align:center;font-weight:bold;">
							 
						</p>
					</div>
					<div class="left-footer" style="width: 55%; float:left;padding:10px;">
						<p class="left-footer" style="text-align:center;" >
							ធ្វើនៅថ្ងៃទី <?php echo $this->erp->hrsd($contract->app_date)?>
						</p>
						<p style="text-align:center;">
							ស្នាមមេដៃស្តាំសមាជិកក្រុមធានាគ្នា ភាគី<span class="small-letter">(ខ)</span>
						</p><br/><br/><br/>
						<p style="text-align:center; font-weight:bold;">
							 <?php 
							foreach ($group as $g){
								echo '<span> <b>'. $g->customer_name .' </b> </span>&nbsp;&nbsp;&nbsp;';
							}
						?>
						</p>
					</div>
					
				</div>
				
			</div>
			
		</div>
	</body>
</html> -->
<!--<?php echo($contract->gender=male?'checked="checked"':''); ?>
	 <?php echo $contract->gender($gender=='female')?'checked':'' ?>
-->