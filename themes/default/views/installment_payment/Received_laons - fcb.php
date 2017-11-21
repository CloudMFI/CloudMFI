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
							<p style="font-family:khmer moul; Muolfont-size:11px;"><b> <?php echo $setting->site_name ?> </b></p>					
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
							<p style="text-align:center;font-size:16px;font-family:khmer moul;"> លិខិតប្រគល់ និងទទួលប្រាក់ </p>					
						</div>
					</div>				
					<div style="width:10%; float:left;margin-top:10px;"></div>				
				</div>
				
				<div class="phara" style="overflow:hidden;width:100%;min-height:100px;clear:both;padding-top:0px;">
					<div class="pha1" style="float:left;width:97%;">
						<span class="pha2-text" style="font-size:13px;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; យោងកិច្ចសន្យាខ្ចីប្រាក់ដែលមានលេខសម្គាល់&nbsp <b><?php echo $contract_info->reference_no?$contract_info->reference_no:'NA';?> </b> &nbspថ្ងៃទី &nbsp <b><?php echo $this->erp->hrsd($contract_info->approved_date)?> </b> &nbsp ខ្ញុំបាទ/នាងខ្ញុំឈ្មោះ&nbsp <b><?php echo $contract_info->customer_name?$contract_info->customer_name:'NA';?> </b> &nbspភេទ &nbsp <b><?php echo $contract_info->gender?$contract_info->gender:'NA';?> </b> &nbsp ថ្ងៃខែឆ្នាំកំណើត&nbsp <b><?php echo $this->erp->hrsd($contract_info->date_of_birth)?> </b> &nbspកាន់លិខិតសម្គាល់<b><?php echo $contract_info->identname?$contract_info->identname:'NA';?> </b> &nbsp លេខ &nbsp <b><?php echo $contract_info->gov_id?$contract_info->gov_id:'NA'?> </b> &nbsp ចេញដោយ &nbsp <b><?php echo $contract_info->issue_by?$contract_info->issue_by:'NA'?> </b> &nbsp ។ <br> និង អ្នករួមខ្ចីត្រូវជា &nbsp <b> <?php echo $join_lease->status?$join_lease->status:'NA';?> </b> &nbsp ឈ្មោះ &nbsp <b> <?php echo $join_lease->name?$join_lease->name:'NA';?> </b> &nbspភេទ &nbsp <b> <?php echo $join_lease->gender?$join_lease->gender:'NA';?> </b> &nbspថ្ងៃខែឆ្នាំកំណើត&nbsp <b><?php echo $this->erp->hrsd($join_lease->date_of_birth)?></b> &nbspកាន់លិខិតសម្គាល់ខ្លួន &nbsp <b> <?php echo $join_lease->identname?$join_lease->identname:'NA';?> </b> &nbsp លេខ &nbsp <b> <?php echo $join_lease->gov_id?$join_lease->gov_id:'NA';?> </b> &nbsp ចេញដោយ &nbsp <b> <?php echo $join_lease->issue_by?$join_lease->issue_by:'NA';?> </b> &nbsp ។អស័យដ្ឋានបច្ចុប្បន្ន៖ ភូមិ &nbsp <b><?php echo $village->village?$village->village:'NA';?> </b> &nbsp ឃុំ &nbsp <b><?php echo $sangkat->sangkat?$sangkat->sangkat:'NA';?> </b> &nbsp ស្រុក &nbsp <b><?php echo $district->district?$district->district:'NA';?> </b> &nbsp ខេត្ត&nbsp <b><?php echo $state->state?$state->state:'NA';?> </b>&nbsp។ លេខទូរស័ព្ទទំនាក់ទំនងខ្សែទី១&nbsp <b><?php echo $contract_info->phone?$contract_info->phone:'NA';?> </b> &nbspខ្សែទី២ &nbsp <b> <?php echo $join_lease->phone?$join_lease->phone:'NA';?> </b> &nbsp
						</span>
					</div>
					<div class="pha1" style="float:left;width:97%;">
						<br>
						<span class="pha2-text" style="font-size:13px;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; នាងខ្ញុំ/ខ្ញុំបាទ ជាអ្នកខ្ចីប្រាក់ពី <?php echo $setting->site_name ?> ពិតជាបានទទួលប្រាក់ចំនួនជាលេខ៖  &nbsp <b><?php echo $this->erp->formatMoney($contract_info->total)?></b> &nbsp រៀល <br>ជាអក្សរ&nbsp <span style="display:inline-block;"> <b><?php echo $contract_info->description?$contract_info->description:'NA';?></b> </span> &nbspគ្រប់ចំនួន។ ក្រែងពុំពិតប្រាកដ នាងខ្ញុំ/ខ្ញុំបាទ សូមផ្តិតមេដៃទុកជាភស្តុតាងសម្រាប់ ទៅថ្ងៃក្រោយ។
						</span>
					</div>
				</div>
				<div style="width:85%;float:left;text-align:right; margin-right:100px;">
					<p> ថ្ងៃទី &nbsp <b><?php echo $this->erp->hrsd($contract_info->approved_date)?> </b> &nbsp </p>			
				</div>
				<div style="width:100%;float:left;text-align:center">
					<div style="width:30%; float:left;">
						<p>តំណាងម្ចាស់កម្ចី</p>
						<p>បានឃើញ និង ឯកភាព</p>
						<p>..........................................</p>
					</div>	
					<div style="width:30%; float:left;">
						<p>មន្ត្រីឥណទាន ឬ បេឡាធិកា</p>
					</div>
					<div style="width:30%; float:left;">
						<p>ស្នាមមេដៃស្ដាំអ្នកទទួលប្រាក់</p>
						<p style="text-align:center"><?php echo $contract_info->customer_name?$contract_info->customer_name:'NA';?></p>
					</div>				
				</div>
			</div>
			<div style="width:100%; height:200px;"></div>
			
			<div style="margin-left:15px;">
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
							<p style="font-family:khmer moul; Muolfont-size:11px;"><b> <?php echo $setting->site_name ?> </b></p>					
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
							<p style="text-align:center;font-size:16px;font-family:khmer moul;"> លិខិតប្រគល់ និងទទួលប្រាក់ </p>					
						</div>
					</div>				
					<div style="width:10%; float:left;margin-top:10px;"></div>				
				</div>
				
				<div class="phara" style="overflow:hidden;width:100%;min-height:100px;clear:both;padding-top:0px;">
					<div class="pha1" style="float:left;width:97%;">
						<span class="pha2-text" style="font-size:13px;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; យោងកិច្ចសន្យាខ្ចីប្រាក់ដែលមានលេខសម្គាល់&nbsp <b><?php echo $contract_info->reference_no?$contract_info->reference_no:'NA';?> </b> &nbspថ្ងៃទី &nbsp <b><?php echo $this->erp->hrsd($contract_info->approved_date)?> </b> &nbsp ខ្ញុំបាទ/នាងខ្ញុំឈ្មោះ&nbsp <b><?php echo $contract_info->customer_name?$contract_info->customer_name:'NA';?> </b> &nbspភេទ &nbsp <b><?php echo $contract_info->gender?$contract_info->gender:'NA';?> </b> &nbsp ថ្ងៃខែឆ្នាំកំណើត&nbsp <b><?php echo $this->erp->hrsd($contract_info->date_of_birth)?> </b> &nbspកាន់លិខិតសម្គាល់<b><?php echo $contract_info->identname?$contract_info->identname:'NA';?> </b> &nbsp លេខ &nbsp <b><?php echo $contract_info->gov_id?$contract_info->gov_id:'NA'?> </b> &nbsp ចេញដោយ &nbsp <b><?php echo $contract_info->issue_by?$contract_info->issue_by:'NA'?> </b> &nbsp ។ <br> និង អ្នករួមខ្ចីត្រូវជា &nbsp <b> <?php echo $join_lease->status?$join_lease->status:'NA';?> </b> &nbsp ឈ្មោះ &nbsp <b> <?php echo $join_lease->name?$join_lease->name:'NA';?> </b> &nbspភេទ &nbsp <b> <?php echo $join_lease->gender?$join_lease->gender:'NA';?> </b> &nbspថ្ងៃខែឆ្នាំកំណើត&nbsp <b><?php echo $this->erp->hrsd($join_lease->date_of_birth)?></b> &nbspកាន់លិខិតសម្គាល់ខ្លួន &nbsp <b> <?php echo $join_lease->identname?$join_lease->identname:'NA';?> </b> &nbsp លេខ &nbsp <b> <?php echo $join_lease->gov_id?$join_lease->gov_id:'NA';?> </b> &nbsp ចេញដោយ &nbsp <b> <?php echo $join_lease->issue_by?$join_lease->issue_by:'NA';?> </b> &nbsp ។អស័យដ្ឋានបច្ចុប្បន្ន៖ ភូមិ &nbsp <b><?php echo $village->village?$village->village:'NA';?> </b> &nbsp ឃុំ &nbsp <b><?php echo $sangkat->sangkat?$sangkat->sangkat:'NA';?> </b> &nbsp ស្រុក &nbsp <b><?php echo $district->district?$district->district:'NA';?> </b> &nbsp ខេត្ត&nbsp <b><?php echo $state->state?$state->state:'NA';?> </b>&nbsp។ លេខទូរស័ព្ទទំនាក់ទំនងខ្សែទី១&nbsp <b><?php echo $contract_info->phone?$contract_info->phone:'NA';?> </b> &nbspខ្សែទី២ &nbsp <b> <?php echo $join_lease->phone?$join_lease->phone:'NA';?> </b> &nbsp
						</span>
					</div>
					<div class="pha1" style="float:left;width:97%;">
						<br>
						<span class="pha2-text" style="font-size:13px;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; នាងខ្ញុំ/ខ្ញុំបាទ ជាអ្នកខ្ចីប្រាក់ពី <?php echo $setting->site_name ?> ពិតជាបានទទួលប្រាក់ចំនួនជាលេខ៖  &nbsp <b><?php echo $this->erp->formatMoney($contract_info->total)?></b> &nbsp រៀល <br>ជាអក្សរ&nbsp <span style="display:inline-block;"> <b><?php echo $contract_info->description?$contract_info->description:'NA';?></b> </span> &nbspគ្រប់ចំនួន។ ក្រែងពុំពិតប្រាកដ នាងខ្ញុំ/ខ្ញុំបាទ សូមផ្តិតមេដៃទុកជាភស្តុតាងសម្រាប់ ទៅថ្ងៃក្រោយ។
						</span>
					</div>
				</div>
				<div style="width:85%;float:left;text-align:right">
					<p> ថ្ថៃទី &nbsp <b><?php echo $this->erp->hrsd($contract_info->approved_date)?> </b> &nbsp </p>			
				</div>
				<div style="width:100%;float:left;text-align:center">
					<div style="width:30%; float:left;">
						<p>តំណាងម្ចាស់កម្ចី</p>
						<p>បានឃើញ និង ឯកភាព</p>
						<p>..........................................</p>
					</div>				
								
					<div style="width:30%; float:left;">
						<p>មន្ត្រីឥណទាន ឬ បេឡាធិកា</p>
					</div>
					<div style="width:30%; float:left;">
						<p>ស្នាមមេដៃស្ដាំអ្នកទទួលប្រាក់</p>
						<p style="text-align:center"><?php echo $contract_info->customer_name?$contract_info->customer_name:'NA';?></p>
					</div>				
				</div>
			</div>
		</div>
		
		
	</body>
</html>
