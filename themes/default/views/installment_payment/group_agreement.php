<!DOCTYPE html>
<?php  
	//$this->erp->print_arrays($total);
	//$this->erp->print_arrays($group);

?>
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
					<span style="font-size:10px;"> លេខគណនីបញ្ជី: &nbsp; <b> <?php echo $contract_info->reference_no?$contract_info->reference_no:'N/A';?></b></span>
				</div>				
			</div>
			<div class="phara" style="overflow:hidden;width:100%;min-height:100px;clear:both;padding-top:15px;">
				<div class="pha1-num" style="float:left;">
				<p class="order-num">១​.</p>
				</div>
				<div class="pha1" style="float:left;width:97%;">
					<p class="pha2-text" style="font-size:14px;">
						ភាគីឲ្យខ្ចី៖  <b> <?php echo $setting->site_name ?> </b>​  មានអាសយដ្ឋាននៅ ​ភូមិ<b><?php echo $br_village->village?$br_village->village:'NA';?> </b> ឃុំ <b><?php echo $br_sangkat->sangkat?$br_sangkat->sangkat:'NA';?> </b> ស្រុក <b><?php echo $br_district->district?$br_district->district:'NA';?> </b> <b><?php echo $br_state->state?$br_state->state:'NA';?> </b> <br> ដែលតំណាងដោយ​​ លោក/លោកស្រី<b> <?php echo $contract_info->approv_name?$contract_info->approv_name:'NA'; ?> </b> មានតួនាទីជា  <b> ប្រធានសាខា </b> តទៅនេះហៅភាគីថា  <span class="small-letter">(ក) </span>
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
						ទឹកប្រាក់ ដែលសមាជិកទាំងអស់ខ្ចីមានចំនួន <b><?php echo $this->erp->convertCurrency($currency->currency_code,$setting->default_currency, $loan_amount->loan_amount)?> <?= $currency->cname ?></b>​ គត់ នៅ <b>ថ្ងៃទី  <?php echo $this->erp->hrsd($contract_info->app_date)?></b> ដើម្បីយកទៅចែកចាយ សមាជិកក្នុង ក្រុមធានា (ចំនួនទឹកប្រាក់សំរាប់សមាជិកម្នាក់ៗ ដូចបាន បញ្ជាក់នៅក្នុង កិច្ចសន្យាខ្ចីប្រាក់ម្នាក់ៗ ) ។
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
							ធ្វើនៅថ្ងៃទី <?php echo $this->erp->hrsd($contract_info->app_date)?>
						</p>
						<p class="left-footer" style="text-align:center; font-family:Khmer OS Muol;" >
							តំណាងម្ចាស់កម្ចី
						</p>
						</p><br/><br/><br/>
						<p style="text-align:center;font-weight:bold;">
							 <?php echo $contract_info->approv_name?$contract_info->approv_name:'N/A'; ?>
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
							ធ្វើនៅថ្ងៃទី <?php echo $this->erp->hrsd($contract_info->app_date)?>
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
</html>
<!--<?php echo($contract_info->gender=male?'checked="checked"':''); ?>
	 <?php echo $contract_info->gender($gender=='female')?'checked':'' ?>
-->