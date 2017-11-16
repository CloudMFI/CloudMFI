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
		min-height: 38cm;
		padding: 1cm;
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
			opacity: 0.8; 
			padding-top:20px;
		}
	</style>
	</head>
	<body>
		<div class="contain-wrapper" style="padding:5px;">
			<div style="margin-top:5px; margin-left:5px;">
				<div class="header" style="width:100%;float:left; ">					
					<div style="width:100%; float:left;margin-top:10px; text-align:center;">					
						<div id="logo">
							<span> 
								<?php if ($Settings->logo2) {
									echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" style="margin-bottom:10px;" />';
								} ?> 
							</span> 
						</div>
						<div>
							<p style="font-family:khmer moul; Muolfont-size:11px;"><b> <?php echo $setting->site_name ?> </b></p>					
						</div>
						<div>
							<p style="font-size:10px;"> ENATEAN &nbsp; LERKSTUOY &nbsp; SAMATEPEAP &nbsp; KRUOSA Plc </p>
							<hr style="border:1px solid black;">
						</div>
					</div>					
					<div style="width:100%; float:left;margin-top:15px; text-align:center;">
						<p style="font-family:khmer moul; Muolfont-size:11px; text-align:center;"><b> កិច្ចព្រមព្រៀងធានាបំណុល </b></p>
						<p style="font-family:khmer moul; Muolfont-size:11px; text-align:center;"><b> រវាង </b></p>						
					</div>									
				</div>
				<div class="header" style="width:100%;float:left;margin-top:10px;">
					<div class="pha1" style="float:left;width:97%;">
						<p class="pha2-text" style="font-size:14px;">
							<b>១.  ភាគីឱ្យខ្ចីប្រាក់ : <?php echo $setting->site_name ?> </b>​  អាស័យដ្ឋាននៅ ភូមិ<b> &nbsp;<?php echo $br_village->village?$br_village->village:'N/A';?> </b> ឃុំ/សង្កាត់<b> &nbsp;<?php echo $br_sangkat->sangkat?$br_sangkat->sangkat:'N/A';?> </b> ស្រុក/ខ័ណ្ឌ <b>&nbsp;<?php echo $br_district->district?$br_district->district:'N/A';?> </b> ខេត្ត/ក្រុង <b>&nbsp;<?php echo $br_state->state?$br_state->state:'N/A';?> </b> ។​ តំណាងពេញច្បាប់ដោយលោក/លោកស្រី<b> <?php echo $contract_info->approv_name?$contract_info->approv_name:'N/A'; ?> </b> តួនាទីជា <b> នាយក​ប្រតិបត្តិ </b> តទៅនេះហៅភាគីថា  <span class="small-letter">(ក) </span>
						</p>
					</div>
					<div class="pha1" style="float:left;width:97%;">
						<p><b>២.  ភាគីធានាបំណុល  </b>​ </p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>- អ្នកធានាទី១ ​</b> ឈ្មោះ <b> &nbsp;<?php echo $guarantor->g_name?$guarantor->g_name:'N/A';?> </b> &nbsp; ឈ្មោះក្រៅ ............................... &nbsp; ភេទ <b> &nbsp;<?php echo $guarantor->gender?$guarantor->gender:'N/A';?> &nbsp;</b>  ថ្ងៃខែឆ្នាំកំណើត<b> &nbsp;<?php echo $this->erp->hrsd($guarantor->date_of_birth);?> </b></p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; សញ្ជាតិ ...............................  &nbsp; ឯកសារកំណត់អត្តសញ្ញាណ <b> &nbsp; <?php echo $guarantor->identname?$guarantor->identname:'N/A';?> &nbsp;&nbsp; </b> លេខ <b> &nbsp; <?php echo $guarantor->gov_id?$guarantor->gov_id:'N/A'?> &nbsp </b></p>
						<span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ​ ចុះថ្ងៃទី <b> &nbsp; <?php echo $this->erp->hrsd($guarantor->issue_date) ?> &nbsp; </b> ​ អាស័យដ្ឋានបច្ចុប្បន្ន  <span style="display:inline-block; "> <b> <?php echo $guarantor->address?$guarantor->address:'N/A';?> </b></span> &nbsp; ទូរស័ព្ទលេខ <b> &nbsp; <?php echo $guarantor->phone?$guarantor->phone:'N/A';?></b> </span>
					</div>					
					<div class="pha1" style="float:left;width:97%;">
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>- អ្នកធានាទី១ ​</b> ឈ្មោះ <b> &nbsp;<?php echo $join_guarantor->g_name?$join_guarantor->g_name:'N/A';?> </b> &nbsp; ឈ្មោះក្រៅ ............................... &nbsp; ភេទ <b> &nbsp;<?php echo $join_guarantor->gender?$join_guarantor->gender:'N/A';?> &nbsp;</b>  ថ្ងៃខែឆ្នាំកំណើត<b> &nbsp;<?php echo $this->erp->hrsd($join_guarantor->date_of_birth);?> </b></p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; សញ្ជាតិ ...............................  &nbsp; ឯកសារកំណត់អត្តសញ្ញាណ <b> &nbsp; <?php echo $join_guarantor->identname?$join_guarantor->identname:'N/A';?> &nbsp;&nbsp; </b> លេខ <b> &nbsp; <?php echo $join_guarantor->gov_id?$join_guarantor->gov_id:'N/A'?> &nbsp </b></p>
						<span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ​ ចុះថ្ងៃទី <b> &nbsp; <?php echo $this->erp->hrsd($join_guarantor->issue_date) ?> &nbsp; </b> ​ អាស័យដ្ឋានបច្ចុប្បន្ន  <span style="display:inline-block; "> <b> <?php echo $join_guarantor->address?$join_guarantor->address:'N/A';?> </b></span> &nbsp; ទូរស័ព្ទលេខ <b> &nbsp; <?php echo $join_guarantor->phone?$join_guarantor->phone:'N/A';?></b> </span>
					</div>					
				</div>
				<div  style="width:100%;float:left;">					
					<div style="width:100%; float:left;margin-top:15px; text-align:center;">
						<div>
							<p style="font-family:khmer moul; Muolfont-size:11px;"><b> អ្នកធានាទី១ </b> និង​ <b> អ្នកធានាទី២ </b> ​តទៅនេះ <b> ហៅថា ភាគី “គ”  </b></p>
							<p style="font-family:khmer moul; Muolfont-size:11px;"><b> ភាគីទាំងពីរបានព្រមព្រៀងលើលក្ខខណ្ឌដូចតទៅ </b></p>	
						</div>
					</div>
					<div class="pha1" style="float:left;width:97%;">
						<p> <b>ប្រការ១ ៖  </b> &nbsp; <b>ភាគី ”ក”​</b> បានយល់ព្រមទទួលការធានាអះអាងរបស់​ &nbsp;<b>ភាគី”គ” ​</b> មកលើ &nbsp;<b>ភាគី”ខ”​</b> ឈ្មោះ <b> &nbsp;&nbsp; <?php echo $contract_info->customer_name?$contract_info->customer_name:'NA';?> </b></p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ភេទ <b> &nbsp;<?php  echo $contract_info->gender?$contract_info->gender:'NA';?> &nbsp;</b>  ថ្ងៃខែឆ្នាំកំណើត <b> &nbsp; <?php echo $this->erp->hrsd($contract_info->date_of_birth)?> &nbsp;</b> ត្រូវជា ............................. &nbsp; និងត្រូវជាកូនបំណុលដែលបានខ្ចីប្រាក់ពី</p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ភាគី ”ក”​</b>នូវទឹកប្រាក់ចំនួន <b> &nbsp; <?php echo $this->erp->convertCurrency($currency->currency_code,$setting->default_currency, $contract_info->total)?> <?= $currency->cname ?>​ &nbsp;</b> ទឹកប្រាក់ជាអក្សរ  <b> &nbsp; <?php echo $contract_info->description ? $contract_info->description : 'N/A' ?> &nbsp;</b>  រយៈពេល  <b> &nbsp; <?php echo  $contract_info->terms * 1 ;?>ថ្ងៃ &nbsp;</b>។</p>
					</div>
					<div class="pha1" style="float:left;width:97%;">
						<p><b>ប្រការ២ ៖  </b>​ &nbsp; <b>ភាគី ”ខ”​</b>បានដាក់តំកល់ទ្រព្យធានាបំណុលដូចបានរៀបរាប់នៅក្នុងកិច្ចព្រមព្រៀងតំកល់ទ្រព្យធានា ។</p>
					</div>
					<div class="pha1" style="float:left;width:97%;">
						<p><b>ប្រការ៣ ៖  </b>​ &nbsp;<b>ភាគី ”ខ”​</b>អះអាងថាទ្រព្យសម្បត្តិទាំងអស់ដែលមាននៅក្នុងកិច្ចព្រមព្រៀងតំកល់ទ្រព្យធានា ពុំមានបញ្ហាទំនាស់ ពាក់ព័ន្ធក្នុង</p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  ការផ្ទេរសិទ្ធិ លក់ដូរ ឬ បញ្ចាំឲ្យទៅបុគ្គលណាមួយ ដែលធ្វើឲ្យបាត់បង់ភាពជាកម្មសិទ្ធិលើទ្រព្យនោះឡើយ។ យើងខ្ញុំសូមសន្យា</p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ថានឹងការពារនៅក្នុងរយៈពេលនៃការដាក់តំកល់ទ្រព្យធានានេះ នឹងមិនឲ្យមានធ្វើការ ដោះដូរ លក់ ផ្ទេរសិទ្ធិ កាត់ចំណែកលក់</p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ធ្វើអំណោយ ឬដាក់បញ្ចាំឲ្យជនណាផ្សេងទៀតជាដាច់ខាតក្នុងករណីនៅ មិនទាន់បញ្ចប់កិច្ច សន្យាខ្ចីប្រាក់ ហើយសូមសន្យា</p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ថែរក្សាគ្រប់គ្រងបណ្តោះអាសន្នទ្រព្យតំកល់ធានាឲ្យល្អគង់ វង្សរហូត លុះត្រាតែសង បំណុលរួច។  </p>
					</div>
					<div class="pha1" style="float:left;width:97%;">
						<p><b>ប្រការ៤ ៖  </b>​&nbsp;  ក្នុងករណី  <b>ភាគី ”ខ”​</b>គ្មានលទ្ធភាពសងបំណុលតាមតារាងបង់ប្រាក់ រឺខកខានមិនបានសងប្រាក់នោះទេ </p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>ភាគី”ខ”​</b>សុខចិត្ត ឲ្យ &nbsp; <b>ភាគី”ក”​</b> ចាត់ចែងនូវទ្រព្យសម្បត្តិដែលបានដាក់ធានាខាងលើ ដើម្បីយកប្រាក់មកទូទាត់បំណុល</p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  ដែលនៅជំពាក់ឲ្យគ្រប់ចំនួនទាំងអស់រួមទាំងប្រាក់ដើម ការប្រាក់ និងប្រាក់ពិន័យ ។</p>
					</div>
					<div class="pha1" style="float:left;width:97%;">
						<p><b>ប្រការ៥ ៖  </b>​&nbsp; ក្នុងករណី  <b>ភាគី ”ខ”​</b>គ្មានលទ្ធភាពសងប្រាក់ដែលជំពាក់  <b>ភាគី ”ក”​</b> ទេនោះអ្នកធានានៃកិច្ចសន្យានេះនឹងសងប្រាក់ទាំងអស់</p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ដែលមិនទាន់សងជូនដល់   <b>ភាគី ”ក”​</b>។ <b>ភាគី ”គ”​</b>​ មានសិទ្ធិចាត់ចែងទ្រព្យតំកល់ធានា​ដើម្បីទូទាត់សងបំណុលជូនដល់ <b>ភាគី ”ក”​</b></p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ឲ្យគ្រប់ចំនួនទាំងអស់ រួមទាំងប្រាក់ដើម ការប្រាក់ និងប្រាក់ពិន័យ ។ </p>
					</div>
					<div class="pha1" style="float:left;width:97%;">
						<p><b>ប្រការ៦ ៖  </b>​&nbsp; ប្រសិនបើ <b>ភាគី”ខ”</b> និង <b>ភាគី”គ”</b> មិនគោរពតាមលក្ខខណ្ឌទាំងឡាយនៃកិច្ចសន្យានេះ ហើយមិនបានបង់ប្រាក់រំលោះ </p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ជាច្រើនលើកច្រើនសារ <b>ភាគី”ក”</b> អាចធ្វើការប្តឹងទៅស្ថាប័នគតិយុត្តិដ៏សមស្របមួយដែលបានជ្រើសរើសដោយ <b>ភាគី”ក”</b></p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ដើម្បីទាមទារបំណុលដែលនៅជំពាក់ ទោះបីមុនកាលកំណត់នៃការសងប្រាក់ក៏ដោយ។ រាល់ការចំណាយទៅលើ ថ្លៃឈ្នួល </p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ថ្លៃពលកម្ម និង ថ្លៃចំណាយផ្សេងៗ គឺជាបន្ទុករបស់ <b>ភាគី”ខ”</b>និង <b>ភាគី”គ”</b> ដែលរំលោភបំពានលើកិច្ចសន្យាទាំងស្រុង ។ </p>
					</div>
					<div class="pha1" style="float:left;width:97%;">
						<p><b>ប្រការ៧ ៖  </b>​&nbsp; កិច្ចសន្យានេះត្រូវបានធ្វើឡើងដោយពុំមានការបង្ខិតបង្ខំពីភាគីណាមួយឡើយ។ ភាគីទាំងបីបានអាននិងព្រម​ព្រៀងជាមួយគ្នា</p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; គ្រប់លក្ខខណ្ឌ និងខ្លឹមសារទាំងអស់នៃកិច្ចសន្យានេះ ហើយចុះហត្ថលេខា និង ផ្តិតមេដៃស្តាំទុកជាភស្តុតាង។</p>
					</div>
								
				</div>
				<div style="width:100%; margin-top:15px; text-align:center;">
					<div>
						<p style="font-family:khmer moul; Muolfont-size:11px;"> ធ្វើនៅថ្ងៃទី&nbsp; <b> <?php echo $this->erp->hrsd($contract_info->approved_date)?> </b> </p>
					</div>
				</div>
				<div class="letter-footer" style="with:100%; height:100px; font-size:12px; margin-top:10px;">
					<div class="left-footer" style="width: 25%; float:left;padding:5px;">
						<p class="left-footer" style="text-align:center; font-family:Khmer OS Muol;" >
							តំណាងម្ចាស់បំណុល
						</p>
						</p><br/><br/><br/>
						<p style="text-align:center;font-weight:bold;">
							<span style="text-transform: capitalize;"> ឈ្មោះ ...............................</span>
						</p>
					</div>
					
					<div class="left-footer" style="width: 25%; float:left;padding:5px;">
						<p style="text-align:center;font-family:Khmer OS Muol;">
							 ស្នាមមេដៃសាក្សី
						</p>
						</p><br/><br/><br/>
						<p style="text-align:center;font-weight:bold;">
							<span style="text-transform: capitalize;"> ឈ្មោះ ...............................</span>
						</p>
					</div>
					<div class="left-footer" style="width: 25%; float:left;padding:5px;">
						<p style="text-align:center;font-family:Khmer OS Muol;">
							 អ្នកធានាទី២
						</p><br/><br/><br/>
						<p style="text-align:center; font-weight:bold;">
							 <span style="text-transform: capitalize;"> <?php echo $join_guarantor->g_name?$join_guarantor->g_name:' N/A ';?> </span>
						</p>
					</div>
					<div class="left-footer" style="width: 25%; float:left;padding:5px;">
						<p style="text-align:center;font-family:Khmer OS Muol;">
							អ្នកធានាទី១
						</p><br/><br/><br/>
						<p style="text-align:center; font-weight:bold;">
							 <span style="text-transform: capitalize;"> <?php echo $guarantor->g_name?$guarantor->g_name:' N/A ';?> </span>
						</p>
					</div>
				</div>
			</div>
		</div>
		
	</body>
</html>
