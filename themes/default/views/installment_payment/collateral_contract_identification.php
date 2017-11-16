
<!DOCTYPE html>
<html>
	<head>
		<title>collateral contract</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Battambang" rel="stylesheet"> 
	<style type="text/css">
        html, body {
            height: 100%;
			font-family: 'Battambang', Time New Roman;
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
				<div class="brand-name" style="width:40%; float:left;margin-top:20px;">
					<div class="logo" style="float:left;width:25%;">
						<img src="<?php echo base_url().'assets/uploads/logos/brand.png';?>" style="width: 100%;">
					</div>
					<div class="text" style="float:left;margin-top:10px;width:75%;">
						<p style="font-family:Khmer OS Muol;font-size:10px;">គ្រឹះស្ថាន ជ ម៉ីជីងហ្វាយនែន ភីអិលស៊ី</p>
						<p style="font-size:12px;">CHOR MEY JING Finance PLC</p>						
					</div>
					
				</div>
				<div class="nationality-identifier" style="width:30%; float:left;margin-top:20px;position:relative;">
					<p style="font-family:Khmer OS Muol;text-align:center;font-size:14px;"​>ព្រះរាជាណាចក្រកម្ពុជា</p>
					<p style="font-family:Khmer OS Muol;text-align:center;font-size:14px;">ជាតិ​​​ សាសនា​ ព្រះមហាក្សត្រ</p>
					<img src="<?php echo base_url().'assets/uploads/logos/line.png';?>" alt="line" style="display:block;margin: 0 auto;">
					<p style="font-family:Khmer OS Muol; text-align:center;text-decoration:underline;font-size:12px;">កិចសន្យាដាក់ដាក់បញ្ជាំ</p>
					<div style="position: absolute;left:-150px;">
						<h6 style="font-family:Khmer OS Muol; text-decoration:underline;font-size:10px;">កិច្ចសន្យាធ្វើឡើងនៅថ្ងៃទី&nbsp <?php echo $this->erp->hrsd($contract_info->approved_date)?> &nbspរវាងៈ</h6>
					</div>
					
				</div>
				<div class="pictures" style="width:30%; float:left;margin-top:20px;">
					<div class="img1" style="width:45%; height:120px;margin-right:7px;float:left;border:1px solid black;">
					</div>
					<div class="img2" style="width:45%; height:120px;float:left;border:1px solid black;">				
					</div>
				</div>
				
			</div>
			
			<div class="phara" style="overflow:hidden;width:100%;min-height:100px;clear:both;padding-top:35px;">
				<div class="pha1-num" style="float:left;">
				<p class="order-num">១​.</p>
				</div>
				<div class="pha1" style="float:left;width:97%;">
					<p class="pha1-text" style="font-size:14px;">
						ភាគីឲ្យខ្ចីៈ គ្រឹះស្ថាន ជ ម៉ីជីងហ្វាយនែន ភីអិលស៊ី សាខា  <b>  <?php echo $this->session->branchName ?>&nbsp </b>   តំណាងពេញច្បាប់ដោយលោក/លោកស្រី <b> <?php echo $contract_info->approv_name ?> </b>  តួនាទីជា  ………………………………………………… តទៅនេះហៅថាភាគី <span class="small-letter"> «ក» និង</span>
					</p>
				</div>
				
				<div class="pha2-num" style="float:left;">
				<p class="order-num">២​.</p>
				</div>
				
				<div class="pha2" style="float:left;width:97%;">
					<p class="pha2-text" style="font-size:14px;">
						ភាគីសុំខ្ចីប្រាក់ៈ ឈ្មោះ <b>&nbsp <?php echo $contract_info->customer_name?>&nbsp &nbsp </b>  ភេទៈ <b> <?php echo $contract_info->gender ?></b> &nbsp ថ្ងៃខែឆ្នាំកំណើត  <b><?php echo $this->erp->hrsd($contract_info->date_of_birth)?>&nbsp &nbsp </b> <input type="checkbox" name="gender" value="gender">អត្តសញ្ញាណប័ណ្ណ <br>
							<input type="checkbox" name="gender" value="gender">សៀវភៅគ្រួសារ &nbsp <input type="checkbox" name="gender" value="gender">សំបុត្រកំណើត  &nbspផ្សេងៗ ........................................................... លេខ....................ចុះថ្ងៃទី  <b> &nbsp <?php echo $this->erp->hrsd($contract_info->issue_date) ?> &nbsp </b>&nbsp </b>   ចេញដោយ <b> &nbsp <?php echo $contract_info->issue_by ?> &nbsp </b> និងឈ្មោះ  <b><?php echo $contract_info->spouse_name ?> &nbsp </b> ភេទ <b> <?php echo $contract_info->sp_gender ?> &nbsp </b> &nbspថ្ងៃខែឆ្នាំកំណើត<b> <?php echo $this->erp->hrsd($contract_info->sp_date) ?> &nbsp </b> ត្រូវជា<b><?php echo $contract_info->sp_status ?></b> តទៅនេះហៅថា ភាគី <span class="small-letter">«ខ»</span>ឬ កូនបំណុល។
					</p>
				</div>
				<p style="text-align:center;">ភាគី <span class=small-letter>«ក»</span>និង ភាគី <span class=small-letter>«ខ»</span>បានព្រមព្រៀងទទួលយកនូវលក្ខន្តិកៈដូចខាងក្រោមៈ</p>
				<p><span class="small-letter">ប្រការ១-</span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
				<span class="small-letter">អំពីលក្ខខណ្ឌរួម</span></p>
				<p style="padding-left:50px;">
					លើមូលដ្ឋាននៃសំណុំឯកសារឥណទាន ភាគី <span class="small-letter">«ក» </span>យល់ព្រមទទួលយកការដាក់ធានា ហើយភាគី <span class="small-letter">«ខ» </span>ក៏យល់ព្រមដាក់ធានាដោយ ស្ម័គ្រចិត្តអោយភាគី<span class="small-letter"> «ខ»</span> នូវចលនវត្ថុ ឬអចលនវត្ថុ ដូចមានបញ្ជាក់នៅក្នុងចំណុចខាងក្រោម ដើម្បីធានាបំណុលៈ
				</p>
				
				<div class="pha5-num" style="float:left;">
				<p class="order-num">១​.១.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ទ្រព្យធានា៖    ក្រោមការដាក់បញ្ចាំ /ដាក់អះអាងដើម្បីបំណុលទាំងប្រាក់ដើម ការប្រាក់ និងប្រាក់ពិន័យ ភាគី <span class="small-letter">«ខ» </span>យល់ព្រមដាក់ធានា នូវចលនវត្ថុ ដោយប្រគល់តម្កល់ឯកសារច្បាប់ ដើម កាតគ្រីទោចក្រយានយន្ត(ម៉ូតូ)  ឬកាតគ្រីយានយន្ត(ឡាន)
					</p>
				</div>
				
				<div class="chat" style="width: 100%;">
					<table>
						<tr>
							<td>ប្រភេទនៃកម្មសិទ្ធិ</td>
							<td><input type="checkbox" name="gender" value="gender">ផ្ទាល់ខ្លួន <input type="checkbox" name="gender" value="gender">ដាក់ធានាជំនួសភាគី<span class="small-letter">«ខ»</span></td>
							<td><input type="checkbox" name="gender" value="gender">ផ្ទាល់ខ្លួន <input type="checkbox" name="gender" value="gender">ដាក់ធានាជំនួសភាគី<span class="small-letter">«ខ»</span></td>
						</tr>
						<tr>
							<td>ឈ្មោះម្ចាស់កម្មសិទ្ធិ</td>
							<td></td><td></td>
						</tr>
						<tr>
							<td>ប័ណ្ណសម្គាល់យានយន្ត</td>
							<td>ពណ៌ <b>&nbsp <?php echo $contract_info->color?>&nbsp &nbsp </b>  		ប្រភេទ <b>&nbsp <?php echo $contract_info->type ?>&nbsp </b>  <br>
							កំលាំង <b>&nbsp <?php echo $contract_info->power?>&nbsp &nbsp </b> 		 ម៉ាក <b>&nbsp <?php echo $contract_info->brand?>&nbsp </b>  <br>
							លេខម៉ាស៊ីន <b>&nbsp <?php echo $contract_info->engine_no?>&nbsp &nbsp </b>	លេខតួ <b>&nbsp <?php echo $contract_info->frame_no?>&nbsp </b>  <br>
							ផ្លាកលេខ <b>&nbsp <?php echo $contract_info->plaque_no?>&nbsp </b>
							ប័ណ្ណសម្គាល់ចុះថ្ងៃទី…………………
							ចេញដោយ……………………………។
							</td>
							<td>ពណ៌ <b>&nbsp <?php echo $contract_info->color?>&nbsp &nbsp </b>  		ប្រភេទ <b>&nbsp <?php echo $contract_info->type ?>&nbsp </b>  <br>
							កំលាំង <b>&nbsp <?php echo $contract_info->power?>&nbsp &nbsp </b> 		 ម៉ាក <b>&nbsp <?php echo $contract_info->brand?>&nbsp </b>  <br>
							លេខម៉ាស៊ីន <b>&nbsp <?php echo $contract_info->engine_no?>&nbsp &nbsp </b>	លេខតួ <b>&nbsp <?php echo $contract_info->frame_no?>&nbsp </b>  <br>							
							ផ្លាកលេខ <b>&nbsp <?php echo $contract_info->plaque_no?>&nbsp </b>
							ប័ណ្ណសម្គាល់ចុះថ្ងៃទី…………………………
							ចេញដោយ……………………………។
							</td>
						</tr>
					</table>
				</div>
				
				<p><span class="small-letter">ប្រការ២-</span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
				<span class="small-letter">អំពីលក្ខខណ្ឌរឲពិសេស</span></p>
				
				<div class="pha6-num" style="float:left;">
				<p class="order-num">២.១​.</p>
				</div>
				<div class="pha6" style="float:left;width:95%;">
					<p class="pha6-text" style="font-size:14px;">
						ភាគី <span class="small-letter">«ខ»</span>សូមអះអាងថា អចលនវត្ថុខាងលើពិតជាកម្មសិទ្ធិស្របច្បាប់របស់ខ្លួនពិតប្រាកដមែន និងមិនមានជាប់ពាក់ព័ន្ធវិវាទ មិនស្ថិតក្នុងតំបន់អភិរក្ស មិនមែនជាទ្រព្យសម្បត្តិសាធារណៈមិនស្ថិតនៅក្រោមការដាក់បញ្ចាំមិនស្ថិតក្រោមដំណើការផ្ទេរកម្មសិទ្ធិឬធ្វើអំណោយ ឬស្ថិតក្រោមដីការឃាត់រក្សាការពាររបស់តុលាការមានសមត្ថកិច្ចទេ បើផ្ទុយពីនេះ ភាគី <span class="small-letter">«ខ» </span> សុខចិត្តទទួលខុសត្រូវចំពោះមុខច្បាប់។
					</p>
				</div>
				
				<div class="pha7-num" style="float:left;">
				<p class="order-num">២.២.</p>
				</div>
				<div class="pha7" style="float:left;width:95%;">
					<p class="pha7-text" style="font-size:14px;">
						ភាគី <span class="small-letter">«ខ» </span>សូមអះអាងថា លិខិត/ប័ណ្ណកម្មសិទ្ធិខាងលើនេះគឺជាលិខិតច្បាប់ដើម ហើយមានតែមួយគត់ ហើយសុខចិត្តទទួលខុសត្រូវចំពោះមុខច្បាប់ ប្រសិនបើត្រូវបានរកឃើញថា មានការក្លែងបន្លំ។
					</p>
				</div>
				
				<div class="pha7-num" style="float:left;">
				<p class="order-num">២.​៣.</p>
				</div>
				<div class="pha7" style="float:left;width:95%;">
					<p class="pha7-text" style="font-size:14px;">
						ភាគី <span class="small-letter">«ខ»</span> សូមអះអាងថា លិខិត/ប័ណ្ណកម្មសិទ្ធិខាងលើនេះគឺជាលិខិតច្បាប់ដើម ហើយមានតែមួយគត់ ហើយសុខចិត្តទទួលខុសត្រូវចំពោះមុខច្បាប់ ប្រសិនបើត្រូវបានរកឃើញថា មានការក្លែងបន្លំ។
					</p>
				</div>
				
				<div class="pha8-num" style="float:left;">
				<p class="order-num">២.​៤.</p>
				</div>
				<div class="pha8" style="float:left;width:95%;">
					<p class="pha8-text" style="font-size:14px;">
						ភាគី<span class="small-letter">«ខ»</span>សន្យាថែរក្សា និងគ្រប់គ្រងអចលនទ្រព្យដែលដាក់ធានាប្រាក់កម្ចីរហូតដល់ប្រាក់កម្ចីត្រូវបានបង់សងគ្រប់ចំនួន។
					</p>
				</div>
				
				<div class="pha9-num" style="float:left;">
				<p class="order-num">២.​៥.</p>
				</div>
				<div class="pha9" style="float:left;width:95%;">
					<p class="pha9-text" style="font-size:14px;">
						ក្នុងករណីអតិថិជនមិនបានបង់សងប្រាក់ត្រឡប់មកជ ម៉ីជីងហ្វាយនែនវិញគ្រប់ចំនួន ភាគី<span class="small-letter"> «ខ»</span> សុខចិត្តចាត់ចែងលក់អចលនទ្រព្យដែលបានដាក់ធានាប្រាក់កម្ចីនេះ ឬអនុញ្ញាតឲ្យ ភាគី<span class="small-letter"> «ក»</span> ចាត់ចែងលក់ឡាយឡុងទ្រព្យតម្កល់ធានានេះក្នុងតម្លៃដែលអាចលក់បានដើម្បីទូទាត់ទឹកប្រាក់ដែលមិនបានបង់សង (ប្រាក់ដើម ការប្រាក់ និងប្រាក់ពិន័យ)។ ក្នុងករណីប្រាក់ដែលទទួលបានពីការលក់នោះមិនគ្រប់ចំនួន ភាគី <span class="small-letter">«ខ»</span> ត្រូវមានកាតព្វកិច្ចត្រូវសងបន្ថែមរហូតទាល់តែគ្រប់ចំនួន។
					</p>
				</div>
				
				<div class="pha10-num" style="float:left;">
				<p class="order-num">២.​៦.</p>
				</div>
				<div class="pha10" style="float:left;width:95%;">
					<p class="pha10-text" style="font-size:14px;">
						កភាគី<span class="small-letter"> «ខ»</span> យល់ព្រមឲ្យ ភាគី<span class="small-letter"> «ក» </span>ចែកចាយព័ត៌មានរបស់ខ្លួនទៅបុគ្គល ឬស្ថាប័នពាក់ព័ន្ធក្នុងគោលបំណងធានាគុណភាពប្រាក់កម្ចី។
					</p>
				</div>
				
				<p><span class="small-letter">ប្រការ៣-</span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
				<span class="small-letter">អំពីលក្ខខណ្ឌអវសាន</span></p>
				<p style="padding-left:50px;">
					ភាគី <span class="small-letter"> «ក» </span> និង ភាគី <span class="small-letter">«ខ» </span>សន្យាគោរពតាមយ៉ាងម៉ឺងម៉ាត់រាល់ប្រការដូចមានចែងខាងលើ។ ក្នុងករណីដែលអនុវត្តផ្ទុយ ឬ រំលោភលើលក្ខខណ្ឌ នៃកិច្ចសន្យា ភាគីដែលល្មើស ត្រូវទទួលខុសត្រូវចំពោះមុខច្បាប់។ រាល់ចំណាយក្នុងការដោះស្រាយវិវាទ ជាបន្ទុករបស់ ភាគីរំលោភបំពានលើកិច្ចសន្យា។ស្នាមផ្តិតមេដៃស្តាំ និង ហត្ថលេខា ខាងក្រោមនេះ គឺជាភស្តុតាងដែលយើងខ្ញុំទាំងអស់គ្នា បានយល់ព្រមអនុវត្តតាមគ្រប់ប្រការនៃកិច្ចសន្យានេះ ហើយកិច្ចសន្យានេះនឹងមានសុពលភាព ចាប់ពីថ្ងៃចុះហត្ថលេខានេះតទៅ។កិច្ចសន្យានេះត្រូវធ្វើឡើង
					ជាបីច្បាប់ (១ច្បាប់ដើមជាភាសាខ្មែរ និង២ច្បាប់ថតចម្លង)ដើម្បីតម្កល់ទុកនៅ៖
				</p>
				
				<p style="padding-left:70px;">
					-	ភាគី<span class="small-letter"> «ក» </span> ………………………………………………………………០១ច្បាប់។</br>
					-	ភាគី<span class="small-letter"> «ខ» </span>………………………………………………………………០១ច្បាប់។</br>
					-	អាជ្ញាធរដែនដី………………………………………………………០១ច្បាប់។</br>

				</p>
				<div>
					<p style="float:right;">
						ធ្វើនៅ...............................ថ្ងៃទី..................ខែ....................ឆ្នាំ....................
					</p>
				</div>
				
				<div style="width:100%;clear:both;height:150px;padding:10px;">
					<div style="width:33%;float:left;">
						<p>សហកម្មសិទ្ធិករ</p>
					</div>
					<div style="width:33%; float:left;">
						<p>កម្មសិទ្ធិករ</p>
					</div>
					<div style="width:33%;float:left;">
						<p>តំណាងភាគីផ្តល់ប្រាក់កម្ចី (ភាគី <span class="small-letter">«ក»</span> )</p>
					</div>
				</div>
				
				<div style="width:100%;clear:both;height:100px;padding:10px;">
					<div style="width:33%;float:left;">
						<p>ឈ្មោះ............……………</p>
					</div>
					<div style="width:33%; float:left;">
						<p>ឈ្មោះ............…………</p>
					</div>
					<div style="width:33%;float:left;">
						<p>ហត្ថលេខា និង ឈ្មោះ........……………</p>
					</div>
				</div>
				
				<div class="letter-footer" style="with:100%;">
					<div class="left-footer" style="width: 50%; float:left;padding:10px;">
						<p>
							<span class="small-letter">លេខៈ.................................</span></br>
							បានឃើញ និងបញ្ជាក់ថា : សេចក្ដីបញ្ជាក់របស់ </br>
							លោកមេភូមិ ……………............</br>
							នេះពិតជាត្រឹមត្រូវប្រាកដមែន ។</br>
						</p>
						<p style="padding-left:30px;">
							ថ្ងៃទី.............ខែ..............ឆ្នាំ...................
						</p>
						<p style="padding-left:50px;">
							<span class="small-letter">មេឃុំ-ចៅសង្កាត់......................................</span>
						</p>
						<p style="padding-left:70px;">
							<span class="small-letter">ហត្ថលេខា និងត្រា</span> 
						</p>
						
					</div>
					
					<div class="right-footer" style="width: 50%;float:left;padding:10px;">
						<p>
							<span class="small-letter">បានឃើញ និងបញ្ជាក់ថា : </span></br>
							ស្នាមមេដៃស្ដាំខាងលើនេះ ពិតជារបស់ភាគី<span class="small-letter">«ខ»</span> ប្រាកដមែន។</br>
							ទ្រព្យសម្បត្ដិ ដូច ខាងលើ ពុំមានពាក់ព័ន្ធនឹងបញ្ហាអ្វីឡើយហើយ</br>
							ភាគី<span class="small-letter">«ខ»</span> បានយល់ព្រមដាក់បញ្ចាំឲ្យគ្រឹះស្ថាន ជ ម៉ីជីង ហ្វាយនែន </br>
							ភីអិលស៊ី ពិតប្រាកដមែន ។</br>
						</p>
						<p style="padding-left:30px;">
							ថ្ងៃទី.............ខែ..............ឆ្នាំ...................
						</p>
						<p style="padding-left:50px;">
							<span class="small-letter">មេភូមិ.............................................................</span>
						</p>
						<p style="padding-left:70px;padding-bottom:70px;">
							<span class="small-letter">ហត្ថលេខា</span> 
						</p>
						
						
					</div>
					
				</div>
				
			</div>
			
		</div>
	</body>
</html>