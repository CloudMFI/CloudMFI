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
					<p style=" text-align:center;font-size:12px; text-align:center;"><b>កិច្ចសន្យាខ្ចីប្រាក់</b></p>
				</div>
				<div style="width:20%; float:left;margin-top:70px;">
				</div>
				<div style="width:20%; float:left;margin-top:10px;">
					<span style="font-size:10px;"> លេខគណនីបញ្ជី: &nbsp; <b> <?php echo $contract_info->reference_no?$contract_info->reference_no:'N/A';?></b></span>
				</div>				
			</div>
			<span style="color:red; font-family: 'Battambang'; font-weight:bold;" class="phone">
				លេខទូរសព្ទទំនាក់ទំនងបន្ទាន់ ៖ ០៩៣ ៩០០ ៩១៤
			</span>
			<div class="phara" style="overflow:hidden;width:100%;min-height:100px;clear:both;padding-top:15px;">
				<div class="pha1-num" style="float:left;">
				<p class="order-num">១​.</p>
				</div>
				<div class="pha1" style="float:left;width:97%;">
					<p class="pha2-text" style="font-size:14px;">
						ភាគីឱ្យខ្ចីប្រាក់ឈ្មោះ  <b> <?php echo $setting->site_name ?> </b>​  អាស័យដ្ឋាននៅ ភូមិ<b><?php echo $br_village->village?$br_village->village:'NA';?> </b> ឃុំ <b><?php echo $br_sangkat->sangkat?$br_sangkat->sangkat:'NA';?> </b> ស្រុក <b><?php echo $br_district->district?$br_district->district:'NA';?> </b> <b><?php echo $br_state->state?$br_state->state:'NA';?> </b> ។​ តំណាងពេញច្បាប់ដោយលោក/លោកស្រី<b> <?php echo $contract_info->approv_name?$contract_info->approv_name:'NA'; ?> </b> តួនាទីជា <b> ប្រធានសាខា </b> តទៅនេះហៅភាគីថា  <span class="small-letter">(ក) </span>
					</p>
				</div>		
				<div class="pha2-num" style="float:left;">
				<p class="order-num">២​.</p>
				</div>
				<div class="pha2" style="float:left;width:97%;">
					<p class="pha2-text" style="font-size:14px;">
						ភាគីអ្នកខ្ចី ឈ្មោះ &nbsp; <b><?php echo $contract_info->customer_name?$contract_info->customer_name:'NA';?> </b> &nbsp; ភេទៈ<b><?php echo $contract_info->gender?$contract_info->gender:'NA';?> </b> &nbsp;  កើតថ្ងៃទី​ &nbsp; <b><?php echo $this->erp->hrsd($contract_info->date_of_birth)?> </b> &nbsp; &nbsp;  ឯកសារកំណត់អត្តសញ្ញាណ <b><?php echo $contract_info->identname?$contract_info->identname:'NA';?> </b>  លេខ<b><?php echo $contract_info->gov_id?$contract_info->gov_id:'NA'?> </b> ចុះថ្ងៃទី<b> &nbsp; <?php echo $this->erp->hrsd($contract_info->issue_date) ?> &nbsp; </b> អាស័យដ្ឋាន ភូមិ <b><?php echo $village->village?$village->village:'NA';?> </b> ឃុំ <b><?php echo $sangkat->sangkat?$sangkat->sangkat:'NA';?> </b> ស្រុក <b><?php echo $district->district?$district->district:'NA';?> </b> <b><?php echo $state->state?$state->state:'NA';?> </b> តទៅនេះហៅភាគីថា  <span class="small-letter">(ខ) </span>
					</p>
				</div>
				<div class="pha2-num" style="float:left;">
					<p class="order-num">៣.</p>
				</div>
				<div class="pha2" style="float:left;width:97%;">
					<span class="pha2-text" style="font-size:14px;">
						អ្នកចូលរួមខ្ចី​ ឈ្មោះ &nbsp; <b> <?php echo $join_lease->name?$join_lease->name:'NA';?> </b> &nbsp; ភេទៈ <b> <?php echo $join_lease->gender?$join_lease->gender:'NA';?> </b> &nbsp;  កើតថ្ងៃទី​ &nbsp; <b><?php echo $this->erp->hrsd($join_lease->date_of_birth)?></b> &nbsp; &nbsp;  ឯកសារកំណត់អត្តសញ្ញាណ <b> <?php echo $join_lease->identname?$join_lease->identname:'NA';?> </b>  លេខ<b> <?php echo $join_lease->gov_id?$join_lease->gov_id:'NA';?> </b> ចុះថ្ងៃទី <b><?php echo $this->erp->hrsd($join_lease->issue_date)?> </b>  អាស័យដ្ឋាន <span style="display:inline-block;"> <b> <?php echo $join_lease->address?$join_lease->address:'NA';?> </b></span>​ ត្រូវជា<b> <?php echo $join_lease->status?$join_lease->status:'NA';?> </b> ។
					</span>
				</div>
				<p style="text-align:center;" class="small-letter"> <b>ភាគីទាំងពីរបានព្រមព្រៀងគ្នាដូចតទៅ</b></p>
				<div>
				<p><span class="small-letter">ប្រការ១</span> &nbsp; &nbsp;  ភាគី<span class="small-letter">(ខ) </span>​ បានខ្ចីប្រាក់ចំនួន ( <b> <?php echo $this->erp->convertCurrency($currency->currency_code,$setting->default_currency, $contract_info->total)?> <?= $currency->cname ?>​ )</b>ពីភាគី​<span class="small-letter">(ក)</span>ដើម្បីយកទៅប្រើប្រាស់ក្នុងគោលបំណង  <b> <?php echo $contract_info->note ? $contract_info->note : 'N/A' ?> </b>  ដែលមានអត្រាការប្រាក់សមរម្យលើប្រាក់ដើមដែលនៅសល់ប្រចាំសប្តាហ៍ ឬប្រចាំខែ ដែលបានកំណត់តាមតារាងសងប្រាក់។</p>
				</div>
				
				<div>
					<p><span class="small-letter">ប្រការ២</span> &nbsp; &nbsp; </b>ភាគី<span class="small-letter">(ខ)</span>រឺអ្នកដាក់ទ្រព្យធានាជំនួស យល់ព្រមដាក់ទ្រព្យធានា <b> <?php echo $collaterals->type?$collaterals->type:'N/A' ?> </b>  ដែលមានលេខសំគាល់ក្នុងប្រព័ន្ធ  <b><?php echo $collaterals->code?$collaterals->code:'N/A' ?></b>  មកឳ្យភាគី <span class="small-letter">(ក)</span>ដើម្បីធានាបំណុលទាំង ប្រាក់ដើមការប្រាក់និងប្រាក់ពិន័យ។</p>					
					
					<!--<table style="border-collapse:collapse; width:90%; margin-left:30px; text-align:center;" border="1";>
						<tr>
<<<<<<< HEAD
							<td>ប្រភេទទ្រព្យធានា</td>
							<td>លេខសំគាល់</td>
							<td>កាលបរិច្ឆេទ</td>
							<td>ឈ្មោះម្ចាស់ទ្រព្យធានា</td>
=======
							<td> ប្រភេទទ្រព្យធានា </td>
							<td> លេខសំគាល់ </td>
							<td> កាលបរិច្ឆេទ </td>
							<td> ឈ្មោះម្ចាស់ទ្រព្យធានា </td>
>>>>>>> 53fb76705307bf5f5f2028a4e774ad11206f2506
						</tr>
						<?php
							foreach($collateral as $collaterals){
						?>
						<tr>
<<<<<<< HEAD
							<td><?php echo $collaterals->type ?></td>
							<td><?php echo $collaterals->code ?></td>
							<td><?php echo $this->erp->hrsd($collaterals->issue_date)?></td>
							<td><?php echo $collaterals->owner_name?$collaterals->owner_name:'NA';?></td>
=======
							<td> <?php echo $collaterals->type ?> </td>
							<td> <?php echo $collaterals->code ?> </td>
							<td> <?php echo $this->erp->hrsd($collaterals->issue_date)?> </td>
							<td> <?php echo $collaterals->owner_name?$collaterals->owner_name:'NA';?> </td>
>>>>>>> 53fb76705307bf5f5f2028a4e774ad11206f2506
						</tr>
						<?php
							}
						?>
						<tr style="height:100px;" valign="bottom">
<<<<<<< HEAD
							<td colspan="2">ស្នាមមេដៃម្ចាស់ទ្រព្យធានាទី១</td>
							<td colspan="2">ស្នាមមេដៃម្ចាស់ទ្រព្យធានាទី២</td>
=======
							<td colspan="2"> ស្នាមមេដៃម្ចាស់ទ្រព្យធានាទី១ </td>
							<td colspan="2"> ស្នាមមេដៃម្ចាស់ទ្រព្យធានាទី២ </td>
>>>>>>> 53fb76705307bf5f5f2028a4e774ad11206f2506
						</tr>
					</table>-->
				</div>
				<div style="margin-top:10px;">
				<p><span class="small-letter">ប្រការ៣</span> &nbsp; &nbsp; ក្នុងកំឡុងពេលនៃការសងប្រាក់ភាគី<span class="small-letter">(ខ)</span>និងបង់ប្រាក់សេវាមោឃភាពដើម្បីទុកជាមោឃះនូវប្រាក់ជំពាក់ចុងក្រោយ បន្ទាប់ពីទូទាត់ប្រាក់ក្នុងលក្ខខណ្ឌដែលភាគី<span class="small-letter">(ខ)</span>បានបង់ប្រាក់សេវាមោឃៈភាព ដែលបាត់បង់អាយុជីវិត នោះប្រាក់ជំពាក់នឹងមិនទាមទារ ពីក្រុមគ្រួសារ អ្នកស្នងមរតក ឬអ្នកធានារបស់ភាគី<span class="small-letter">(ខ)</span>ទៀតឡើយ។</p>	
				</div>
<<<<<<< HEAD
				
				<div>
				<p><span class="small-letter">ប្រការ៤</span> &nbsp; &nbsp; ភាគី<span class="small-letter">(ខ)</span>ត្រូវសងរំលោះឱ្យបានទៀងទាត់ និងគ្រប់ចំនួន តាមតារាងបង់ប្រាក់ដែលបានកំណត់។ <!--ត្រូវសងរំលោះឱ្យបានគ្រប់តាមចំនួនប្រាក់ក្នុងតារាងបង់ប្រាក់ជារៀងរាល់ សប្តាហ៍/ខែ ដោយចាប់ផ្តើមបង់ពីថ្ងៃទី<b><?php echo $this->erp->hrsd($contract_info->startdate)?> </b>សម្រាប់រយៈពេល<b> <?php echo $contract_info->cperiod?$contract_info->cperiod:'NA';?> </b>ដង។ កាលបរិច្ឆេទសងបញ្ចប់ថ្ងៃទី <b><?php echo $this->erp->hrsd($contract_info->enddate)?> </b>។--></p>	
				</div>
				
				<div>
				<p><span class="small-letter">ប្រការ៥</span> &nbsp; &nbsp; ក្នុងកំឡុងពេលភាគី<span class="small-letter">(ខ)</span>យល់ព្រមបង់ប្រាក់ពិន័យ ២០០០រៀលក្នុងមួយថ្ងៃ ក្នុងករណីយឺតយ៉ាវ តាមកាលកំណត់​ ក្នុងតារាងសងប្រាក់។ </p>	
				</div>
				
				<div>
				<p><span class="small-letter">ប្រការ៦</span> &nbsp; &nbsp; ប្រសិនបើ ភាគី <span class="small-letter">(ខ)</span>ចង់បង់ផ្តាច់មុនកាលកំណត់ភាគី<span class="small-letter">(ខ)</span>យល់ព្រមបង់ការប្រាក់ និងប្រាក់សេវាមោឃភាព យ៉ាងតិចបំផុត៦ដង នៃតារាងសងប្រាក់។</p>	
				</div>
				
				<div>
				<p><span class="small-letter">ប្រការ៧</span> &nbsp; &nbsp; ក្នុងករណីគ្មានលទ្ធភាពសងបំណុលតាមកាលវិភាគកំណត់សងប្រាក់ទេ ឬខកខានមិនបានសងប្រាក់នោះ ភាគី<span class="small-letter">(ខ)</span> និងអ្នកធានា សុខចិត្តឱ្យ ភាគី<span class="small-letter">(ក)</span>រឹបអូសទ្រព្យសម្បត្តិរបស់ខ្លួនលក់ឡៃឡុងដើម្បីយកប្រាក់មកទូទាត់បំណុល ដែលខ្លួននៅជំពាក់រហូតទាល់តែគ្រប់ចំនួន រួមទាំងប្រាក់ដើមការប្រាក់ និងប្រាក់ពិន័យ។</p>			
				</div>
				
				<div>
				<p><span class="small-letter">ប្រការ៨</span> &nbsp; &nbsp; ក្នុងករណីដែល ភាគី<span class="small-letter">(ខ)</span> គ្មានលទ្ធភាពសងប្រាក់ជំពាក់ អ្នកធានានៃកិច្ចសន្យានេះនឹងសងប្រាក់ជំពាក់ទាំងអស់ ដែលមិនទាន់សងជូនដល់ភាគី <span class="small-letter">(ក)</span> ។</p>			
				</div>
				
				<div>
				<p><span class="small-letter">ប្រការ៩</span> &nbsp;; &nbsp; ភាគី<span class="small-letter">(ខ)</span> យល់ព្រមជ្រើសយកមធ្យោបាយសងប្រាក់មក ភាគី <span class="small-letter">(ក)</span>វិញតាមរយះដៃគួរផ្ទេរប្រាក់ <b>ទ្រូមានី </b></p>				
				</div>
				
				<div>
				<p><span class="small-letter">ប្រការ១០</span> &nbsp; &nbsp; ប្រសិនបើ ភាគី<span class="small-letter">(ខ)</span> និងអ្នកធានាមិនគោរពតាមលក្ខខណ្ឌទាំងឡាយនៃកិច្ចសន្យានេះទេហើយមិនបាន បង់ប្រាក់រំលោះជាច្រើនលើកច្រើនសារភាគី<span class="small-letter">(ក)</span>អាចធ្វើការប្តឹងទៅស្ថាប័នគតិយុត្តិដ៏សមស្របណាមួយដែល បានជ្រើសរើសដោយភាគី<span class="small-letter">(ក)</span>ដើម្បីទាមទារប្រាក់ជំពាក់ ទោះបីមុនកាលកំណត់នៃការសងប្រាក់ក៏ដោយ ។ ប្រសិនបើមានការចាត់វិធានការតាមផ្លូវច្បាប់ ថ្លៃឈ្នួល និងថ្លៃចំណាយផ្សេងៗតាមផ្លូវច្បាប់ គឺជាបន្ទុករបស់ភាគី  <span class="small-letter">(ខ)</span>ទាំងស្រុង។</p>				
				</div>
				
				<div>
				<p><span class="small-letter">ប្រការ១១</span>	&nbsp; &nbsp; កិច្ចសន្យានេះត្រូវបានធ្វើឡើងដោយពុំមានការបង្ខិតបង្ខំពីភាគីណាមួយឡើយ ភាគីទាំងពីរបានអាន និងព្រមព្រៀងគ្នា គ្រប់លក្ខខណ្ឌ និងខ្លឹមសារទាំងអស់នៃកិច្ចសន្យានេះ ហើយចុះហត្ថលេខា និងផ្តិតមេដៃស្តាំទុកជាភស្តុតាង។ </p>				
				</div>
				
				<div>
					<p style="text-align:right; margin-right:100px;">ធ្វើនៅថ្ងៃទី&nbsp; <b> <?php echo $this->erp->hrsd($contract_info->approved_date)?> </b> &nbsp;</p>
				</div>
				
=======

				<div>
				<p><span class="small-letter">ប្រការ៤</span> &nbsp; &nbsp; ភាគី<span class="small-letter">(ខ)</span>ត្រូវសងរំលោះឱ្យបានទៀងទាត់ និងគ្រប់ចំនួន តាមតារាងបង់ប្រាក់ដែលបានកំណត់។ <!--ត្រូវសងរំលោះឱ្យបានគ្រប់តាមចំនួនប្រាក់ក្នុងតារាងបង់ប្រាក់ជារៀងរាល់ សប្តាហ៍/ខែ ដោយចាប់ផ្តើមបង់ពីថ្ងៃទី<b><?php echo $this->erp->hrsd($contract_info->startdate)?> </b>សម្រាប់រយៈពេល<b> <?php echo $contract_info->cperiod?$contract_info->cperiod:'NA';?> </b>ដង។ កាលបរិច្ឆេទសងបញ្ចប់ថ្ងៃទី <b><?php echo $this->erp->hrsd($contract_info->enddate)?> </b>។--></p>	
				</div>

				<div>
				<p><span class="small-letter">ប្រការ៥</span> &nbsp; &nbsp; ក្នុងកំឡុងពេលភាគី<span class="small-letter">(ខ)</span>យល់ព្រមបង់ប្រាក់ពិន័យ ២០០០រៀលក្នុងមួយថ្ងៃ ក្នុងករណីយឺតយ៉ាវ តាមកាលកំណត់​ ក្នុងតារាងសងប្រាក់។ </p>	
				</div>

				<div>
				<p><span class="small-letter">ប្រការ៦</span> &nbsp; &nbsp; ប្រសិនបើ ភាគី <span class="small-letter">(ខ)</span>ចង់បង់ផ្តាច់មុនកាលកំណត់ភាគី<span class="small-letter">(ខ)</span>យល់ព្រមបង់ការប្រាក់ និងប្រាក់សេវាមោឃភាព យ៉ាងតិចបំផុត៦ដង នៃតារាងសងប្រាក់។</p>	
				</div>

				<div>
				<p><span class="small-letter">ប្រការ៧</span> &nbsp; &nbsp; ក្នុងករណីគ្មានលទ្ធភាពសងបំណុលតាមកាលវិភាគកំណត់សងប្រាក់ទេ ឬខកខានមិនបានសងប្រាក់នោះ ភាគី<span class="small-letter">(ខ)</span> និងអ្នកធានា សុខចិត្តឱ្យ ភាគី<span class="small-letter">(ក)</span>រឹបអូសទ្រព្យសម្បត្តិរបស់ខ្លួនលក់ឡៃឡុងដើម្បីយកប្រាក់មកទូទាត់បំណុល ដែលខ្លួននៅជំពាក់រហូតទាល់តែគ្រប់ចំនួន រួមទាំងប្រាក់ដើមការប្រាក់ និងប្រាក់ពិន័យ។</p>			
				</div>

				<div>
				<p><span class="small-letter">ប្រការ៨</span> &nbsp; &nbsp; ក្នុងករណីដែល ភាគី<span class="small-letter">(ខ)</span> គ្មានលទ្ធភាពសងប្រាក់ជំពាក់ អ្នកធានានៃកិច្ចសន្យានេះនឹងសងប្រាក់ជំពាក់ទាំងអស់ ដែលមិនទាន់សងជូនដល់ភាគី <span class="small-letter">(ក)</span> ។</p>			
				</div>

				<div>
				<p><span class="small-letter">ប្រការ៩</span> &nbsp;; &nbsp; ភាគី<span class="small-letter">(ខ)</span> យល់ព្រមជ្រើសយកមធ្យោបាយសងប្រាក់មក ភាគី <span class="small-letter">(ក)</span>វិញតាមរយះដៃគួរផ្ទេរប្រាក់ <b>ទ្រូមានី </b></p>				
				</div>

				<div>
				<p><span class="small-letter">ប្រការ១០</span> &nbsp; &nbsp; ប្រសិនបើ ភាគី<span class="small-letter">(ខ)</span> និងអ្នកធានាមិនគោរពតាមលក្ខខណ្ឌទាំងឡាយនៃកិច្ចសន្យានេះទេហើយមិនបាន បង់ប្រាក់រំលោះជាច្រើនលើកច្រើនសារភាគី<span class="small-letter">(ក)</span>អាចធ្វើការប្តឹងទៅស្ថាប័នគតិយុត្តិដ៏សមស្របណាមួយដែល បានជ្រើសរើសដោយភាគី<span class="small-letter">(ក)</span>ដើម្បីទាមទារប្រាក់ជំពាក់ ទោះបីមុនកាលកំណត់នៃការសងប្រាក់ក៏ដោយ ។ ប្រសិនបើមានការចាត់វិធានការតាមផ្លូវច្បាប់ ថ្លៃឈ្នួល និងថ្លៃចំណាយផ្សេងៗតាមផ្លូវច្បាប់ គឺជាបន្ទុករបស់ភាគី  <span class="small-letter">(ខ)</span>ទាំងស្រុង។</p>				
				</div>

				<div>
				<p><span class="small-letter">ប្រការ១១</span>	&nbsp; &nbsp; កិច្ចសន្យានេះត្រូវបានធ្វើឡើងដោយពុំមានការបង្ខិតបង្ខំពីភាគីណាមួយឡើយ ភាគីទាំងពីរបានអាន និងព្រមព្រៀងគ្នា គ្រប់លក្ខខណ្ឌ និងខ្លឹមសារទាំងអស់នៃកិច្ចសន្យានេះ ហើយចុះហត្ថលេខា និងផ្តិតមេដៃស្តាំទុកជាភស្តុតាង។ </p>				
				</div>

				<div>
					<p style="text-align:right; margin-right:100px;">ធ្វើនៅថ្ងៃទី&nbsp; <b> <?php echo $this->erp->hrsd($contract_info->approved_date)?> </b> &nbsp;</p>
				</div>

>>>>>>> 53fb76705307bf5f5f2028a4e774ad11206f2506
				<div class="letter-footer" style="with:100%; height:200px; font-size:12px;">
					<div class="left-footer" style="width: 35%; float:left;padding:10px;">
						<p class="left-footer" style="text-align:center; font-family:Khmer OS Muol;" >
							តំណាងម្ចាស់កម្ចី
						</p>
						</p><br/><br/><br/>
						<p style="text-align:center;font-weight:bold;">
							<span style="text-transform: capitalize;"> <?php echo $contract_info->approv_name?$contract_info->approv_name:'N/A'; ?></span>
						</p>
					</div>
<<<<<<< HEAD
					
=======
>>>>>>> 53fb76705307bf5f5f2028a4e774ad11206f2506
					<div class="left-footer" style="width: 30%; float:left;padding:10px;">
						<p style="text-align:center;font-family:Khmer OS Muol;">
							 ស្នាមមេដៃអ្នកចូលរូមខ្ចី
						</p>
						</p><br/><br/><br/>
						<p style="text-align:center;font-weight:bold;">
							 <?php echo $join_lease->name?$join_lease->name:'NA';?>
						</p>
					</div>
					<div class="left-footer" style="width: 30%; float:left;padding:10px;">
						<p style="text-align:center;font-family:Khmer OS Muol;">
							 ស្នាមមេដៃអ្នកខ្ចី
						</p><br/><br/><br/>
						<p style="text-align:center; font-weight:bold;">
							 <span style="text-transform: capitalize;"><?php echo $contract_info->customer_name?$contract_info->customer_name:'N/A';?></span>
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