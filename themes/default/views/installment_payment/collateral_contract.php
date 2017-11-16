<?php //$this->erp->print_arrays($collateral) ?>
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
			font-family:Moul;font-weight:bold;font-size:12px;
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
					<p style="font-family:Moul;text-align:center;font-size:12px;"​>ព្រះរាជាណាចក្រកម្ពុជា</p>
					<p style="font-family:Moul;text-align:center;font-size:12px;">ជាតិ​​​ សាសនា​ ព្រះមហាក្សត្រ</p>
					<p style="font-family:Moul;text-align:center;font-size:12px;"><b> <?php echo $setting->site_name ?> </b></p>					
					<p style=" text-align:center;font-size:12px; text-align:center;"><b>កិច្ចព្រងព្រៀងដាក់តំកល់ទ្រព្យធានា</b></p>
				</div>
				<div style="width:20%; float:left;margin-top:70px;">
				</div>
				<div style="width:20%; float:left;margin-top:10px;">
					<span style="font-size:10px;"> លេខគណនីបញ្ជី: &nbsp; <b> <?php echo $contract_info->reference_no?$contract_info->reference_no:'N/A';?></b></span>
				</div>				
			</div>
			<div class="phara" style="overflow:hidden;width:100%;min-height:100px;clear:both;padding-top:15px;">
				<br/>
				<div class="pha2" style="padding-left:10px; line-height:25px;" >
					<p class="pha2-text" style="font-size:14px;  text-indent: 50px;">
						ខ្ញុំបាទ /នាងខ្ញុំឈ្មោះ &nbsp; <b><?php echo $contract_info->customer_name?$contract_info->customer_name:'NA';?> </b>&nbsp; ភេទៈ<b> <?php echo $contract_info->gender?$contract_info->gender:'NA';?> </b>&nbsp; &nbsp;  ឯកសារកំណត់អត្តសញ្ញាណ <b><?php echo $contract_info->identname?$contract_info->identname:'NA';?> </b>  លេខ<b> <?php echo $contract_info->gov_id?$contract_info->gov_id:'NA'?> </b>  ចុះថ្ងៃទី<b> &nbsp; <?php echo $this->erp->hrsd($contract_info->issue_date) ?> &nbsp; </b> 
						និងឈ្មោះ &nbsp; <b> <?php echo $join_lease->name?$join_lease->name:'NA';?> </b>  ភេទៈ <b> <?php echo $join_lease->gender?$join_lease->gender:'NA';?> </b>
						 ឯកសារកំណត់អត្តសញ្ញាណ <b> <?php echo $join_lease->identname?$join_lease->identname:'NA';?> </b>  លេខ<b> <?php echo $join_lease->gov_id?$join_lease->gov_id:'NA';?> </b> ចុះថ្ងៃទី <b><?php echo $this->erp->hrsd($join_lease->issue_date)?> </b> ត្រូវជា<b> <?php echo $join_lease->status?$join_lease->status:'NA';?> </b>  
						 មានអាសយដ្ឋាននបច្ចុប្បន្ន ភូមិ <b><?php echo $village->village?$village->village:'NA';?> </b> ឃុំ <b><?php echo $sangkat->sangkat?$sangkat->sangkat:'NA';?> </b> ស្រុក <b><?php echo $district->district?$district->district:'NA';?> </b> <b><?php echo $state->state?$state->state:'NA';?> </b>។ 
					</p>
					<p class="pha2-text" style="font-size:14px;  text-indent:50px; text-align:left;">
						ខ្ញុំ/យើងខ្ញុំ សូមជម្រាបជូន&nbsp; <b> <?php echo $contract_info->approv_name?$contract_info->approv_name:'NA'; ?> </b>&nbsp; មានមុខងារជា <b>នាយកសាខា</b> នៃ  
						<b> <?php echo $setting->site_name ?> </b>​ 
						មេត្តាទទួលជ្រាបថាៈដើម្បីការពារហានិភ័យផ្សេងៗដែលអាចកើតមានជៀស វាងការបាត់បង់ប្រភេទទ្រព្យធានា ដើម្បីធានានិងបញ្ជាក់អះអាងបន្ថែម
						លើការខ្ចីប្រាក់ពីស្ថាប័នរបស់លោក ខ្ញុំ/យើងខ្ញុំ សូមប្រគល់ដោយស្ម័គ្រចិត្តជូនស្ថាប័នរបស់លោក  រក្សាទុកនូវប្រភេទ ទ្រព្យធានារហូតដល់ សងបំណុលទាំងឡាយដល់ស្ថាប័នរបស់លោករួចរាល់ ដូចមានបញ្ជាក់ នៅចំណុចខាងក្រោម ៖
					</p>
					<table style="border-collapse:collapse; width:90%; margin-left:30px; text-align:center;" border="1";>
						<tr>
							<td>ប្រភេទទ្រព្យធានា</td>
							<td>លេខសំគាល់</td>
							<td>កាលបរិច្ឆេទ</td>
							<td>ឈ្មោះម្ចាស់ទ្រព្យធានា</td>
						</tr>
						<?php
							foreach($collateral as $collaterals){
						?>
						<tr>
							<td><?php echo $collaterals->type ?></td>
							<td><?php echo $collaterals->code ?></td>
							<td><?php echo $this->erp->hrsd($collaterals->issue_date)?></td>
							<td><?php echo $collaterals->owner_name?$collaterals->owner_name:'NA';?></td>
						</tr>
						<?php
							}
						?>
						<tr style="height:100px;" valign="bottom">
							<td colspan="2">ស្នាមមេដៃម្ចាស់ទ្រព្យធានាទី១</td>
							<td colspan="2">ស្នាមមេដៃម្ចាស់ទ្រព្យធានាទី២</td>
						</tr>
					</table><br/>
					<p class="pha2-text" style="font-size:14px;  text-indent:50px; text-align:left;">
						ខ្ញុំ/យើងខ្ញុំ សូមអះអាងថាប្រភេទទ្រព្យធានាខាងលើនេះ ពិតជាកម្មសិទ្ធិរបស់ខ្ញុំ/យើងខ្ញុំពិតប្រាកដ ដោយពុំមានពាក់ព័ន្ធនឹងបញ្ហាអ្វី ឬ ជនណាមួយដែលធ្វើឲ្យបាត់បង់នូវភាពជាកម្មសិទ្ធិឡើយ
						បើផ្ទុយពីនេះ ខ្ញុំ/យើងខ្ញុំហ៊ានទទួលខុសត្រូវចំពោះមុខច្បាប់ ។
					</p>
					<p class="pha2-text" style="font-size:14px;  text-indent:50px; text-align:left;">
						<em><b>លក្ខខណ្ឌនៃការដកប្រភេទទ្រព្យធានាត្រូវមានឈ្មោះម្នាក់ក្នុងចំនោមអ្នកទាំងពីរដែលបានធ្វើកិច្ចព្រមព្រៀងដាក់
						ទ្រព្យធានាមកឥណទានលើកស្ទួយសមត្ថភាពគ្រួសារ។</b></em>
					</p>
					<p class="pha2-text" style="font-size:14px;  text-indent:50px; text-align:left;">
						អាស្រ័យដូចបានជម្រាបជូនខាងលើ សូមលោកប្រធានសាខា <b> <?php echo $setting->site_name ?> </b> មេត្តាទទួល និងរក្សាទុកនូវលិខិតសម្គាល់កម្មសិទ្ធិរបស់ខ្ញុំ/យើងខ្ញុំ ដោយអនុគ្រោះ ។
					</p>
				</div>
				
				
				<div>
					<p style="text-align:right; margin-right:50px;">ធ្វើនៅថ្ងៃទី&nbsp; <b> <?php echo $this->erp->hrsd($contract_info->approved_date)?> </b> &nbsp;</p>
				</div>
				
				<div class="letter-footer" style="with:100%; height:200px; font-size:12px;">
					<div class="left-footer" style="width: 30%; float:left;padding:10px;">
						<p class="left-footer" style="text-align:center; font-family:Moul;" >
							បានឃើញ និងឯកភាព
						</p>
						</p><br/><br/><br/>
						<p style="text-align:center;font-weight:bold;">
							 <?php echo $contract_info->approv_name?$contract_info->approv_name:'N/A'; ?>
						</p>
					</div>
					
					<div class="left-footer" style="width: 35%; float:left;padding:10px;">
						<p style="text-align:center;font-family:Moul;">
							 ហត្ថលេខាអ្នកទទួល
						</p>
						</p><br/><br/><br/>
						<p style="text-align:center;font-weight:bold;">
							 <?php echo $join_lease->name?$join_lease->name:'N/A';?>
						</p>
					</div>
					<div class="left-footer" style="width: 35%; float:left;padding:10px;">
						<p style="text-align:center;font-family:Moul;">
							 ស្នាមមេដៃស្តាំអ្នកដាក់តំកល់
						</p><br/><br/><br/>
						<p style="text-align:center; font-weight:bold;">
							 <?php echo $contract_info->customer_name?$contract_info->customer_name:'N/A';?>
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