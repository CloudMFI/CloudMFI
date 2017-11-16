<!DOCTYPE html>
<html>
<?php
//$this->erp->print_arrays($contract_gr_info);
?>
	<head>
		<title><?php echo $contract_info->gov_id?$contract_info->gov_id:'NA';?></title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Battambang" rel="stylesheet">
	<style type="text/css">
        html, body {
            height: 100%;
			font-family: 'Battambang', Time New Roman;
			line-height: 22px;
        }
		.contain-wrapper {
		width: 21cm;
		min-height: 29.7cm;
		padding: 2cm;
		margin: 1cm auto;
		border: 1px #D3D3D3 solid;
		border-radius: 5px;
		background: white;
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
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

	</style>
	</head>
	<body>
		<div class="contain-wrapper" style="padding:10px;">
			<div class="header" style="width:100%;float:left;">
				<div class="brand-name" style="width:20%; float:left;margin-top:20px;">
				</div>
				<div class="nationality-identifier" style="width:50%; float:left;margin-top:20px;position:relative;">
					<p style="font-family:Khmer OS Muol;text-align:center;font-size:14px;"​>ព្រះរាជាណាចក្រកម្ពុជា</p>
					<p style="font-family:Khmer OS Muol;text-align:center;font-size:14px;">ជាតិ​​​ សាសនា​ ព្រះមហាក្សត្រ</p>
					
					<p style=" text-align:center;font-size:12px; text-align:center;"><b>កិច្ចសន្យាខ្ចីប្រាក់</b></p>
					<p style=" text-align:center;font-size:12px; text-align:center;"><b>កិច្ចសន្យាធ្វើឡើងនៅថ្ងៃទី  &nbsp <?php echo $this->erp->hrsd($contract_info->approved_date)?> &nbsp  រវាងៈ</b></p>
					<!--<div style="position: absolute;left:-150px; padding-left:100px;">
						<h6 style="font-family:Khmer OS Muol; text-decoration:underline;font-size:10px;">កិច្ចសន្យាធ្វើឡើងនៅថ្ងៃទី.............ខែ.............ឆ្នាំ................រវាងៈ</h6>
					</div>-->
				</div>
				
				<div style="width:20%; float:left;margin-top:70px;">
					<span style="font-size:10px;"> លេខកិច្ចសន្យា: &nbsp <b> <?php echo $contract_info->reference_no?$contract_info->reference_no:'NA';?></b></span>
				</div>
				<div style="width:20%; float:left;margin-top:10px;">
					<span style="font-size:10px;"> លេខអតិថិជន:&nbsp <b><?php echo $contract_info->gov_id?$contract_info->gov_id:'NA';?></b></span>
				</div>				
			</div>
			
			<div class="phara" style="overflow:hidden;width:100%;min-height:100px;clear:both;padding-top:35px;">
				<div class="pha1-num" style="float:left;">
				<p class="order-num">១​.</p>
				</div>
				<div class="pha1" style="float:left;width:97%;">
					<p class="pha2-text" style="font-size:14px;">
						ភាគីឱ្យខ្ចីប្រាក់ឈ្មោះ  <b> <?php echo $setting->site_name ?> </b>ដែលហៅកាត់ថា <b>មុីជីង </b>​ តំណាងពេញច្បាប់ដោយលោក/លោកស្រី<b> <?php echo $contract_info->approv_name?$contract_info->approv_name:'NA'; ?> </b> តួនាទីជា <b> ប្រធានសាខា </b> ជាម្ចាស់​បំណុលតទៅនេះហៅភាគីថា  <span class="small-letter">&quot;ក&quot; </span>
					</p>
				</div>
								
				<div class="pha2-num" style="float:left;">
				<p class="order-num">២​.</p>
				</div>
				
				<div class="pha2" style="float:left;width:97%;">
					<p class="pha2-text" style="font-size:14px;">
						ភាគីសុំខ្ចីប្រាក់ឈ្មោះ &nbsp <b><?php echo $contract_info->customer_name?$contract_info->customer_name:'NA';?> </b> &nbsp ភេទៈ<b><?php echo $contract_info->gender?$contract_info->gender:'NA';?> </b>  ថ្ងៃខែឆ្នាំកំណើត<b><?php echo $this->erp->hrsd($contract_info->date_of_birth)?> </b> &nbsp &nbsp  ឯកសារសំគាល់ <b><?php echo $contract_info->identname?$contract_info->identname:'NA';?> </b>  លេខ<b><?php echo $contract_info->gov_id?$contract_info->gov_id:'NA'?> </b> ចុះថ្ងៃទី<b> &nbsp <?php echo $this->erp->hrsd($contract_info->issue_date) ?> &nbsp </b>  ចេញដោយ <b> &nbsp <?php echo $contract_info->issue_by?$contract_info->issue_by:'NA' ?> &nbsp </b> លេខទំនាក់ទំនង<b><?php echo $contract_info->phone?$contract_info->phone:'NA';?> </b> <br> និងឈ្មោះ <b><?php echo $contract_info->spouse_name?$contract_info->spouse_name:'NA'; ?> &nbsp </b>ភេទ<b> <?php echo $contract_info->sp_gender?$contract_info->sp_gender:'NA'; ?> &nbsp </b> ថ្ងៃខែឆ្នាំកំណើត<b> <?php echo $this->erp->hrsd($contract_info->sp_date) ?> &nbsp </b>  ត្រូវជា <b><?php echo $contract_info->sp_status?$contract_info->sp_status:'NA'; ?> &nbsp </b> តទៅនេះហៅភាគីថា <span class="small-letter">&quot;ខ&quot; </span> ឬកូនបំណុល​ ។
					</p>
				</div>
				<p style="text-align:center;"><b>ភាគី <span class=small-letter>&quot;ក&quot;</span>និង ភាគី <span class=small-letter>&quot;ខ&quot;</span>បានព្រមព្រៀងទទួលយកនូវលក្ខន្តិកៈដូចខាងក្រោមៈ</b></p>
				<p><span class="small-letter">ប្រការ១-</span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
				<span class="small-letter">អំពីលក្ខខណ្ឌរួម</span></p>
				<p style="padding-left:50px;">
					លើមូលដ្ឋាននៃពាក្យសុំខ្ចីចងការប្រាក់ សេចក្តីណែនាំស្តីពីគោលការណ៍ និងលក្ខខណ្ឌនៃការឲ្យខ្ចីចងការប្រាក់ភាគី ​<span class=small-letter>&quot;ក&quot;</span>​ យល់ព្រមឲ្យភាគី​ ​<span class="small-letter">&quot;ខ&quot; </span>
					ខ្ចីចងការ ហើយភាគី​​<span class="small-letter">&quot;ខ&quot; </span>ក៏ព្រមទទួល និងសន្យាសងមកភាគី <span class=small-letter>&quot;ក&quot;</span>​​ វិញជាដាច់ខាតនូវប្រាក់ដើម និង ការប្រាក់ គ្រប់ចំនួន និងគោរពលក្ខខណ្ឌដែលបានព្រមព្រៀងគ្នាដូចតទៅនេះ៖
				</p>
				
				<div class="pha5-num" style="float:left;">
					<p class="order-num">១​.១.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ទ្រព្យធានា៖    ក្រោមការដាក់បញ្ចាំ/ដាក់ធានាអះអាងដើម្បីបំណុលទាំងប្រាក់ដើម ការប្រាក់ និងប្រាក់ពិន័យ 
					</p>
					<ul>
						
						<li> ទ្រព្យធានា <b><?php echo $contract_info->type?$contract_info->type:'NA';?> </b> ជារបស់ឈ្មោះ  <b><?php echo $contract_info->customer_name?$contract_info->customer_name:'NA';?> </b>  និងឈ្មោះ <b><?php echo $contract_info->spouse_name?$contract_info->spouse_name:'NA'; ?> &nbsp </b> ជាអ្នកយល់ព្រមដាក់បញ្ចាំជំនួសភាគី​ <span class="small-letter">&quot;ខ&quot; </span> ដែលមានបញ្ជាក់ក្នុងកិច្ចសន្យាដាក់បញ្ចាំ។</li>
					</ul>
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">១​.២.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">					
					<span class="pha5-text" style="font-size:14px; width:60%; float:left; border:0 px;">
						 ប្រាក់ខ្ចីចងការដែលភាគី <span class="small-letter">&quot;ខ&quot;</span>បានទទួលចំនួនជាលេខ <b><?php echo $this->erp->formatMoney($contract_info->total)?></b>
					</span>	 
					<span style="width:35%;float:right;  border:0 px;">	 
						 <span class="pha5-text" style="font-size:14px; width:30%; float:left; border:0 px;">
							  ចំនូនអក្សរ  
						</span>	
						<span style="width:15%;float:left;  border:0px;">	 
							 <b><?php echo $contract_info->description?$contract_info->description:'NA'; ?> </b>
						</span>
					</span>					
				</div>
				<div class="pha5-num" style="float:left; clear:left;">
					<p class="order-num">១​.៣.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						អត្រាការប្រាក់ប្រចាំ <b> <?php echo $contract_info->frequency?$contract_info->frequency:'NA'; ?> ថ្ងៃ&emsp; <?php echo $contract_info->interest?$contract_info->interest:'NA'; ?>  </b>ដោយគណនាទៅតាមសមតុល្យប្រាក់ដើមជាក់ស្តែងដែលនៅជំពាក់។
					</p>
				</div>
				<div class="pha5-num" style="float:left; clear:left;">
					<p class="order-num">១​.៤.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						រយៈពេលប្រាក់កម្ចី <b><?php echo $contract_info->term?$contract_info->term:'NA'; ?>ឆ្នាំ </b> គិតចាប់ពីថ្ងៃទទួលប្រាក់តទៅរហូតដល់ថ្ងៃដែលត្រូវបង់ប្រាក់ចុងក្រោយដូចមានចែងនៅក្នុង កាលវិភាគសងប្រាក់ដែលភាគី  <span class="small-letter">&quot;ក&quot; </span> បានផ្តល់ជូន។
					</p>
				</div>
				<div class="pha5-num" style="float:left; clear:left;">
					<p class="order-num">១​.៥. </p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						របៀបសងត្រឡប់ប្រាក់កម្ចី អនុវត្តន៍តាមតារាងកាលវិភាគសងប្រាក់ដោយបង់ប្រាក់ផ្ទាល់ ឬកាត់ចេញពីគណនីរបស់ <span class="small-letter">&quot;ខ&quot;</span>​។
					</p>
					
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">១​.៦.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						គោលបំណងប្រើប្រាស់ៈ ដូចមានបញ្ជាក់នៅក្នុងពាក្យស្នើសុំខ្ចីប្រាក់។
					</p>
					
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">១​.៧.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ទីកន្លែបង់ប្រាក់ និង ឧបសម្ព័ន្ធៈ
					</p>
					<ul>
						<li> ភាគី <span class="small-letter">&quot;ខ&quot;</span>​ យល់ព្រមបង់ប្រាក់មកភាគី  <span class="small-letter">&quot;ក&quot;</span>​ វិញតាមមធ្យោបាយ.................................. </li>
						<li> កាលវិភាគសងប្រាក់ កិច្ចសន្យា កិច្ចសន្យាដាក់បញ្ចាំ ប័ណ្ណចំណាយប្រាក់អោយខ្ចី ឬប័ណ្ណដកប្រាក់ជាឧបសម្ព័ន្ធនៃកិច្ចសន្យានេះ។</li>
					</ul>
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">១​.៨.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ភាគី <span class="small-letter">&quot;ខ&quot;</span>​​សន្យាថានឹងមិនប្រើប្រាស់ឥណទានដែលទទួលបានពីភាគី <span class="small-letter">&quot;ក&quot;</span>ធ្វើឲ្យប៉ះពាល់អវិជ្ជមានដល់បរិស្ថាន សង្គមនិងប្រើប្រាស់លើសកម្មភាពទាំងឡាយដែលហាមឃាត់ដោយច្បាប់របស់ព្រះរាជាណាចក្រកម្ពុជាឡើយ។
					</p>
					
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">១​.៩.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ភាគី <span class="small-letter">&quot;ខ&quot;</span>​​សន្យាថានឹងមិនប្រើប្រាស់កម្ចីដែលទទួលបានពីភាគី <span class="small-letter">&quot;ក&quot;</span>យកទៅចែកអ្នកដទៃឬប្រើប្រាស់ខុសពីគោលបំណងនៃពាក្យស្នើសុំខ្ចីប្រាក់ឡើយ។
					</p>
					
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">១​.១០.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ភាគី <span class="small-letter">&quot;ខ&quot;</span>​​យល់ព្រមឲ្យភាគី <span class="small-letter">&quot;ក&quot;</span>ធ្វើការត្រួតពិនិត្យតាមដានការប្រើប្រាស់ប្រាក់កម្ចី បើសិនភាគី<span class="small-letter">&quot;ក&quot;</span>រកឃើញថា ភាគី <span class="small-letter">&quot;ខ&quot;</span>មិនបានប្រើប្រាស់ប្រាក់កម្ចីតាមគោលបំណងដែលបានបញ្ជាក់ក្នុងពាក្យស្នើសុំខ្ចីប្រាក់ ឬចែករំលែកប្រាក់កម្ចីជាមួយភាគីផ្សេងទៀត ភាគី <span class="small-letter"><<ក>></span>​សូមរក្សាសិទ្ធិដោយតម្រូវឲ្យ ភាគី  <span class="small-letter">&quot;ខ&quot;</span>បង់ប្រាក់កម្ចីទាំងអស់មក <b> <?php echo $setting->site_name ?> </b> វិញភ្លាម ឬអាចធ្វើការប្តឹងភាគី<span class="small-letter">&quot;ខ&quot;</span>ពីបទរំលោភលើសេចក្តីទុកចិត្ត។
					</p>
					
				</div>
				
				<div class="small-letter" style="float:left;">
					<p>​ប្រការ៣៖ &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;	<span class="small-letter">អំពីលក្ខខណ្ឌរឲពិសេស</span></p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ក្នុងករណីដែលភាគី<span class="small-letter">&quot;ខ&quot;</span>មិនបានអនុវត្តតាមកិច្ចសន្យាចងការប្រាក់ដូចមានចែងក្នុងប្រការ១ ចំណុច ១.១ ដល់ ចំណុច ១.១០ខាងលើនេះ ភាគី <span class="small-letter">&quot;ខ&quot;</span> យល់ព្រមឲ្យ ភាគី<span class="small-letter">&quot;ក&quot;</span> អនុវត្តវិធានការដូចតទៅៈ
					</p>					
				</div>
				<div style="clear:both;"></div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">៣.១. </p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
						ក្នុងករណីកូនបំណុលខកខានមិនបានសងប្រាក់តាមកាលកំណត់ក្នុងតារាងសងប្រាក់ទេនោះ កូនបំណុលត្រូវបាននឹងត្រូវធ្វើការបង់ប្រាក់ពិន័យក្នុងអត្រា <b> $<?= $this->erp->formatMoney($setting->penalty_amount); ?> </b>ក្នុងមួយថ្ងៃ<!-- <b> <?php echo $setting->penalty_days ?>--></b> ដោយគណនាលើទំហំទឹកប្រាក់សរុបដែលអតិថិជនត្រូវបង់សង ប៉ុន្តែមិនបានបង់សងតាមកាលកំណត់។ ការគណនាប្រាក់ពិន័យគឺត្រូវរាប់បញ្ចូលទាំងថ្ងៃចុងសប្តាហ៍ និងថ្ងៃឈប់សម្រាកសាធារណៈផងដែរ។
					<p class="pha5-text" style="font-size:14px;">
					</p>					
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">៣.២. </p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						អតិថិជនអាចត្រូវបានអនុញ្ញាតឲ្យបង់ផ្តាច់ដើមមុនកាលកំណត់ក្រោយពេលធ្វើការបង់ប្រាក់តាមកាលវិភាគសងប្រាក់បានរយៈពេល៣ខែហើយ ដោយពុំមានការផាកពិន័យអ្វីឡើយ។ ដោយឡែក បើអតិថិជនបង់ផ្តាច់ដើមមុនពេលខ្លួនបានអនុវត្តការបង់ប្រាក់តាមកាលវិភាគរយៈពេលនោះអតិថិជនត្រូវបង់ប្រាក់ពិន័យដែលស្មើនឹង៥០%ការប្រាក់នាខែបន្ទាប់ចំនួន១ខែទៀត។ អ្នកខ្ចីប្រាក់អាចអនុញ្ញាត ឲ្យបង់ផ្តាច់ដើមមុនកាលកំណត់លុះត្រាតែបានសងប្រាក់មកវិញយ៉ាងតិចបំផុត ៦ខែតាមកាលវិភាគសងប្រាក់ជាមុនសិន រឺត្រូវរងការផាកពិន័យ ចំនួនការប្រាក់ចាប់ពីខែដែលបង់ចុងក្រោយ រហូតដល់ខែទី៦ ដែលបានកំណត់។
					</p>					
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">៣.៣. </p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ក្នុងករណី ភាគី<span class="small-letter"><<ខ>></span> បង់រំលស់ប្រាក់ដើមមុនកាលកំណត់ ចាប់ពី ១០% នៃប្រាក់កម្ចីសរុបនៅជំពាក់ ឡើងទៅ ការប្រាក់ត្រូវបានកាត់បន្ថយ  សម្រាប់ខែបន្ទាប់ ដោយគណនាទៅតាមប្រាក់កម្ចីដែលនៅសល់។
					</p>					
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">៣.៤. </p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ក្នុងករណីដែលខកខានសងបំណុលតាមការកំណត់តាមចំណុច <span class="small-letter">ប្រការ១</span>  កន្លងផុតរយៈពេល ០១ខែ ភាគី <span class="small-letter">&quot;ខ&quot;</span> សុខចិត្តលក់ទ្រព្យ សម្បត្តិរបស់ខ្លួន ដើម្បីទូទាត់បំណុល ឬ ឱ្យអ្នកធានាបំណុលរបស់ខ្លួន ដែលបានសងជំនួស ឬ ភាគី <span class="small-letter">&quot;ក&quot;</span> កាត់កង ឬ ប្តឹងទៅ តុលាការ ដើម្បីរឹបអូសទ្រព្យសម្បត្តិខ្លួន ជាចលនទ្រព្យ, អចលនទ្រព្យដែលដាក់ធានា, ឬ ទ្រព្យសម្បត្តិផ្សេងទៀត ដូចជា សន្និធិ, ទំនិញ, ចលនទ្រព្យ, ឬ អចលនទ្រព្យ ដែលមានក្រៅពីទ្រព្យដាក់ធានា លក់ឡាយឡុង ដើម្បីកាត់យកមកទូទាត់ប្រាក់សំណង ដែលបានសងជួស ឬ ទូទាត់បំណុលដែលនៅជំពាក់ភាគី <span class="small-letter">&quot;ខ&quot;</span> ត្រូវមានកាតព្វកិច្ចសងបំណុលដែលនៅសល់បន្ថែមទៀតរហូតទាល់ តែគ្រប់ចំនួន ។
					</p>					
				</div>
				<div class="small-letter" style="float:left;">
					<p>​ប្រការ៤៖ &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;	<span class="small-letter">អំពីលក្ខខណ្ឌអវសាន្ត</span></p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ភាគី  <span class="small-letter">&quot;ក&quot;</span> និងភាគី <span class="small-letter">&quot;ខ&quot;</span> សន្យាគោរពយ៉ាងម៉ឺងម៉ាត់តាមរាល់ប្រការនៃខសន្យានានាខាងលើ ។   ក្នុងករណីមានការអនុវត្តផ្ទុយ ឬ ដោយរំលោភលើលក្ខខណ្ឌណាមួយនៃកិច្ចសន្យានេះ ភាគីដែលល្មើសត្រូវទទួលខុសត្រូវចំពោះមុខច្បាប់ជាធរមាន ។ រាល់សោហ៊ុយ ចំណាយទាក់ទងក្នុងការដោះស្រាយលើវិវាទ ជាបន្ទុករបស់ភាគីដែលរំលោភបំពានលើកិច្ចសន្យា។ កិច្ចសន្យានេះត្រូវបានធ្វើឡើង ដោយមានការព្រមព្រៀងពិតប្រាកដ និងដោយសេរី គ្មានការបង្ខិតបង្ខំពីភាគីណាមួយឡើយ ហើយមានប្រសិទ្ឋភាពចាប់ពីថ្ងៃ  &nbsp <b> <?php echo $this->erp->hrsd($contract_info->approved_date)?> </b> &nbsp ។          

					</p>					
				</div>
				<!---->
				<div class="header" style="width:100%;float:left;">
					<div class="nationality-identifier" style="width:100%; float:left;margin-top:20px;position:relative;">
						<p style="font-family:Khmer OS Muol;text-align:center;font-size:12px;"​>កិច្ចសន្យានេះត្រូវបានធ្វើឡើងជាពីរច្បាប់ ជាភាសាខ្មែរ ដើម្បីតម្កល់ទុកនៅៈ</p>
						<p style="text-align:center;font-size:12px;"​>- ភាគី <span class="small-letter">&quot;ក&quot;​</span> ​<b> <?php echo $setting->site_name?$setting->site_name:'NA'; ?> </b>០១ ច្បាប់ (ច្បាប់ដើម)</p>
						<p style="text-align:center;font-size:12px;"​>- ភាគី <span class="small-letter">&quot;ខ&quot;</span> &emsp;​​<b> <?php echo $contract_info->customer_name?$contract_info->customer_name:'NA'; ?> </b> &emsp;​០១ ច្បាប់ (ច្បាប់ដើម)</p>
												
					</div>												
				</div>
				
				<div class="nationality-identifier" style="width:100%; float:left;position:relative; text-align:right;margin-bottom:20px;">
					ធ្វើនៅ &nbsp <b> <?php echo $this->erp->hrsd($contract_info->approved_date)?> </b> &nbsp				
				</div>												
				
				
				<div class="chat" style="width: 100%;">
					<table>
						<tr>
							<td style="width: 25%;"> សាក្សី​ </td>
							<td colspan="2" style="width: 40%;"> ស្នាមមេដៃស្ដាំអ្នកខ្ចីប្រាក់  (ភាគី<span class="small-letter">&quot;ខ&quot;</span>)</td>
							<td style="width: 35%;">តំណាងភាគីផ្តល់ប្រាក់កម្ចី​ (ភាគី<span class="small-letter">&quot;ក&quot;</span>)</td>
						</tr>
						
						<tr height="100px;" style=" vertical-align: text-top;">
							<td></td>
							<td>អ្នករូមខ្ចី</td>
							<td>អ្នកសុំខ្ចី</td>
							<td></td>
						</tr>
					</table>
				</div>
				
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						<span class="small-letter">កិច្ចព្រមព្រៀង និង សេចក្តីបញ្ជាក់របស់អ្នកធានា៖ </span> ខ្ញុំបាទ/នាងខ្ញុំឈ្មោះ  <b> <?php echo $contract_gr_info->name?$contract_gr_info->name:'NA';?> </b> ភេទៈ  <b> <?php echo $contract_gr_info->gender?$contract_gr_info->gender:'NA'?> </b>   ថ្ងៃខែឆ្នាំកំណើត <b><?php echo $this->erp->hrsd($contract_gr_info->date_of_birth)?></b>  ឯកសារ​សំគាល់​ <b> <?php echo $contract_gr_info->identname?$contract_gr_info->identname:'NA';?> </b>​ ​លេខ​<b> <?php echo $contract_gr_info->gov_id?$contract_gr_info->gov_id:'NA'?> </b>​ &emsp; ចុះថ្ងៃទី <b><?php echo $this->erp->hrsd($contract_gr_info->issue_date)?> </b> ចេញដោយ <b> <?php echo $contract_gr_info->issue_by?$contract_gr_info->issue_by:'NA';?> </b> លេខទំនាក់ទំនង <b> <?php echo $contract_gr_info->phone?$contract_gr_info->phone:'NA';?>  </b>
						&emsp; និងឈ្មោះ <b> <?php echo $contract_join_gr_info->name?$contract_join_gr_info->name:'NA';?> </b> ភេទ<b> <?php echo $contract_join_gr_info->gender?$contract_join_gr_info->gender:'NA';?> </b>​​  ថ្ងៃខែឆ្នាំកំណើត<b><?php echo $this->erp->hrsd($contract_join_gr_info->date_of_birth)?></b>  ឯកសារ​សំគាល់​ <b> <?php echo $contract_join_gr_info->identname?$contract_join_gr_info->identname:'NA';?> </b>​ ​លេខ​ <b> <?php echo $contract_join_gr_info->gov_id?$contract_join_gr_info->gov_id:'NA';?> </b> ចុះថ្ងៃទី  <b><?php echo $this->erp->hrsd($contract_join_gr_info->issue_date)?> </b> ចេញដោយ  <b> <?php echo $contract_join_gr_info->issue_by?$contract_join_gr_info->issue_by:'NA';?> </b>លេខទំនាក់ទំនង<b> <?php echo $contract_join_gr_info->phone?$contract_join_gr_info->phone:'NA';?>  </b>
						ជាអ្នកធានា និង អ្នករួមធានានូវបំណុលរបស់ ភាគី  <span class="small-letter">&quot;ខ&quot;</span> សូមធានាអះអាងថា ក្នុងករណីដែល ភាគី <span class="small-letter">&quot;ខ&quot;</span> មិនអាចអនុវត្តកាព្វកិច្ចសងបំណុលឲ្យបានគ្រប់ចំនួន ទាំងប្រាក់ដើម ទាំងការប្រាក់ ព្រមទាំងប្រាក់ពិន័យ ទៅតាមលក្ខខណ្ឌដូចបានរៀបរាប់នៅក្នុងកិច្ចសន្យាខាងលើ ខ្ញុំ/យើងខ្ញុំជា អ្នកធានា និង អ្នករួមធានា សុខចិត្តចេញសងជំនួសដោយឥតលក្ខខណ្ឌ។ 
					</p>
				
				</div>
				
				<div class="letter-footer" style="with:100%;">
					<div class="left-footer" style="width: 50%; float:left;padding:10px;">
						<p>
							ស្នាមផ្ដិតមេដៃ​ស្ដាំអ្នករួមធានា 
						</p> <br>						
						<p style="padding-left:50px;">
							<span class="small-letter">ឈ្មោះ <b> <?php echo $contract_join_gr_info->name?$contract_join_gr_info->name:'NA';?> </b></span>
						</p>
						
						
					</div>
					
					<div class="left-footer" style="width: 50%; float:left;padding:10px;">
						<p>
							ធ្វើនៅ &nbsp <b> <?php echo $this->erp->hrsd($contract_info->approved_date)?> </b> &nbsp
						</p>
						<p>
							ស្នាម​ផ្ដិតមេដៃស្ដាំអ្នកធានា
						</p>						
						<p style="padding-left:50px;">
							<span class="small-letter">ឈ្មោះ   <b> <?php echo $contract_gr_info->name?$contract_gr_info->name:'NA';?> </b></span>
						</p>
						
						
					</div>
					
				</div>
				
			</div>
			
		</div>
	</body>
</html>
<!--<?php echo($contract_info->gender=male?'checked="checked"':''); ?>
	 <?php echo $contract_info->gender($gender=='female')?'checked':'' ?>
-->