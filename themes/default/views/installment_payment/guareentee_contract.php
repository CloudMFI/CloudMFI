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
		padding: 1cm;
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
			font-family:khmer os muol;font-weight:bold;font-size:10px;
		}
		.chat table{
			width: 100%;
			margin-bottom:30px;
		}
		.put-border tr td{
			border:1px solid black;
			border-collapse:collapse;
			padding:5px;
		}
		
		
		
	</style>
	</head>
	<body>
		<div class="contain-wrapper">
			<div class="header" style="width:100%;text-align:center;">
				<h5 style="font-family:Khmer OS Muol;">
				ព្រះរាជាណាចក្រកម្ពុជា</br></br>
				ជាតិ សាសនា ព្រះមហាក្សត្រ</br>
				</h5>
				<img src="<?php echo base_url().'assets/uploads/logos/line.png';?>" alt="line" style="">
				<h5>
				កិច្ចសន្យាធានាអះអាងបំណុល
				</h5>
				<h5>ធ្វើនៅ ថ្ងៃទី.....................ខែ....................ឆ្នាំ........................</h5>
			</div>
			<div class="content" style="width:100%; min-height:100px;">
				<p style="font-family:Khmer OS Battambang;">
					១-ឈ្មោះ <b>&nbsp <?php echo $guareentee_info->customer_name?>&nbsp &nbsp </b> ភេទ <b>&nbsp <?php echo $guareentee_info->gender?>&nbsp &nbsp </b> សញ្ជាតិ <b>&nbsp <?php echo $guareentee_info->nationality ?>&nbsp &nbsp </b> &nbsp &nbsp <input type="checkbox" value="" <?php echo($guareentee_info->family_book=='1'?'checked="checked"':''); ?> />សៀវភៅគ្រួសារ   &nbsp &nbsp <input type="checkbox" <?php echo($guareentee_info->govid=='1'?'checked="checked"':''); ?> />អត្តសញ្ញាណប័ណ្ណ  &nbsp &nbsp <input type="checkbox" <?php echo($guareentee_info->place_book=='1'?'checked="checked"':''); ?>  />សៀវភៅស្នាក់នៅ  &nbsp &nbsp <input type="checkbox" />សំបុត្រកំណើត
					&nbsp &nbsp <input type="checkbox"<?php echo($guareentee_info->other_note=='1'?'checked="checked"':''); ?> />ផ្សេងៗ ........................... លេខៈ ......................... សញ្ជាតិ <b> <?php echo $guareentee_info->nationality ?>&nbsp </b> <input type="checkbox"<?php echo($guareentee_info->family_book=='1'?'checked="checked"':''); ?> />សៀវភៅគ្រួសារ  &nbsp &nbsp <input type="checkbox"<?php echo($guareentee_info->govid=='1'?'checked="checked"':''); ?> />អត្តសញ្ញាណប័ណ្ណ  &nbsp &nbsp <input type="checkbox" <?php echo($guareentee_info->place_book=='1'?'checked="checked"':''); ?> />សៀវភៅស្នាក់នៅ  &nbsp &nbsp <input type="checkbox" />សំបុត្រកំណើត  &nbsp &nbsp <input type="checkbox" <?php echo($guareentee_info->other_note=='1'?'checked="checked"':''); ?> />ផ្សេងៗ ............................. លេខៈ................................ត្រូវជា  &nbsp &nbsp <input type="checkbox" />ប្តី   &nbsp &nbsp <input type="checkbox" />ប្រពន្ធ  &nbsp &nbsp <input type="checkbox" <?php echo($guareentee_info->other_note=='1'?'checked="checked"':''); ?> /> ផ្សេងៗ..................................

				</p>
				
				<p>
					២-អាស័យដ្ឋានបច្ចុប្បន្នៈផ្ទះលេខ...............ផ្លូវលេខ<b>&nbsp <?php echo $guareentee_info->street ?>&nbsp </b> ក្រុមទី.............ភូមិ  <b>&nbsp <?php echo $guareentee_info->village?>&nbsp </b>  ឃុំ/សង្កាត់ <b>&nbsp <?php echo $guareentee_info->sangkat?>&nbsp </b>
					ស្រុក/ខណ្ឌ/<b>&nbsp <?php echo $guareentee_info->district?>&nbsp </b> ខេត្ត/រាជធានី  <b>&nbsp <?php echo $guareentee_info->city?>&nbsp </b>  ហៅថាភាគី(តតិយជន រឺភាគី«គ»)   ក្រោយពីបានអាននូវកិច្ចសន្យាខ្ចីប្រាក់ របស់ឈ្មោះ <b>&nbsp <?php echo $guareentee_info->customer_name?>&nbsp &nbsp </b> និងឈ្មោះ <b>&nbsp <?php echo $guareentee_info->sp_name?>&nbsp &nbsp </b>   ត្រូវជា &nbsp &nbsp <input type="checkbox" />ប្តី &nbsp &nbsp <input type="checkbox" />ប្រពន្ធ &nbsp &nbsp <input type="checkbox" />ផ្សេងៗ.................................ស័យដ្ឋានបច្ចុប្បន្នៈផ្ទះលេខ...........ផ្លូវលេខ <b>&nbsp <?php echo $guareentee_info->street ?>&nbsp </b> ក្រុមទី.............ភូមិ<b>&nbsp <?php echo $guareentee_info->village?>&nbsp </b>ឃុំ/សង្កាត់ <b>&nbsp <?php echo $guareentee_info->sangkat?>&nbsp </b> ស្រុក/ខណ្ឌ <b>&nbsp <?php echo $guareentee_info->district?>&nbsp </b> ខេត្ត/រាជធានី <b>&nbsp <?php echo $guareentee_info->city?>&nbsp </b>ជាភាគី«ខ»ដែលជាកូនបំណុលរបស់ គ្រឹះស្ថាន ជ ម៉ីជីងហ្វាយនែន ភីអិលស៊ី សាខា  <b>  <?php echo $this->session->branchName ?>&nbsp </b>   ជាភាគី«ក»
				</p>
				<p style="padding-left:20px;">យើងខ្ញុំបានយល់ច្បាស់ពីខ្លឹមសារគ្រប់ចំណុចទាំងអស់ ដែលមានចែងក្នុងកិច្ចសន្យាខ្ចីប្រាក់ ហើយបានព្រមព្រៀងគ្នាធានាបំណុលទាំងស្រុងរបស់</p>
				<p>
					ភាគី«ខ»ចំនួនទឹកប្រាក់ <b> &nbsp <?php echo $guareentee_info->total ?>&nbsp </b> (ចំនួនជាអក្សរ..............................................................)   
					ដោយយល់ព្រមចុះ កិច្ចសន្យាព្រមព្រៀងធានានិងអនុវត្ដតាមរាល់ប្រការដូចខាងក្រោម៖ 
				</p>
				<p style="padding-left:20px;">
					-ចលនទ្រព្យដែលមានឯកសារកម្មសិទ្ធិ </br>
					<input type="checkbox" /> រថយន្ត <input type="checkbox" />ទោចក្រយានយន្ត ដែលទិញដោយប្រាក់កម្ចី ដែលមានលក្ខណៈសម្គាល់ដូចខាងក្រោម ៖
				</p>
				
				<div class="chat">
					<table class="put-border">
						<tr style="text-align:center;">
							<td style="border-left:none !important;border-top:none !important;"></td>
							<td><P>ប្រភេទទី ០១</P></td>
							<td><P>ប្រភេទទី ០២</P></td>
							<td><P>ប្រភេទទី ០៣</P></td>
						</tr>
						<tr>
							<td>ប្រភេទ ឬម៉ាក</td>
							<td><b> <?php echo $guareentee_info->type?>  </b></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>ពណ៌</td>
							<td><b> <?php echo $guareentee_info->color?> </b></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>លេខម៉ាស៊ីន</td>
							<td> <b> <?php echo $guareentee_info->engine_no?> </b> </td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>លេខតួ</td>
							<td><b> <?php echo $guareentee_info->frame_no?> </b></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>ស៊េរីឆ្នាំ </td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						
						<tr>
							<td>កម្លាំងម៉ាស៊ីន</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>គុណភាព</td>
							<td> <b> <?php echo $guareentee_info->power?> </b> </td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>បង្កាន់ដៃបង់ពន្ធលេខ</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>តម្លៃ</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						
						
					</table>
				</div>
				
				<p>
					<span style="font-weight:bold;font-family:khmer os muol;">ប្រការ ១: </span>យើងខ្ញុំ សូមធានាអះអាងលើបំណុលនេះទាំងស្រុង ប្រសិនបើភាគី<span style="font-weight:bold;font-family:khmer os muol";>«ខ»</span>មិនបានអនុវត្ដត្រឹមត្រូវតាមកិច្ចសន្យាខ្ចីប្រាក់ដោយប្រការណាមួយនោះ     យើងខ្ញុំសូមធានាទទួលខុសត្រូវ និងបង់ប្រាក់បំណុលសងជំនួស ទាំងស្រុងដោយគ្មានលក្ខខណ្ឌ ។		
				</p>
				<p>
					<span style="font-weight:bold;font-family:khmer os muol;">ប្រការ   ២ : </span>ដើម្បីជាភស្ដុតាងបញ្ជាក់លើកិច្ចសន្យាធានា យើងខ្ញុំសូមដាក់ទ្រព្យសម្បត្ដិដូចខាងក្រោម ៖
				</p>
				<div class="evendeince" style="width:100%;margin-top:20px;overflow:hidden;font-family:khmer os muol; padding-bottom:100px;">
					<div style="width:33%;float:left;">
						<p><span class="ch-box"></span> អ្នកស្នងមរតក  <span class="ch-box"></span> សាក្សី </p>
					</div>
					<div style="width:33%;float:left;">
						<p>ស្នាមមេដៃស្ដាំភាគី“គ”</p>
					</div>
					<div style="width:33%;float:left;">
						<p>តំណាងភាគី «ក»ហត្ថលេខា និងត្រា</p>
					</div>
				</div>
				<div style="overflow:hidden;padding-left:10px;">
					<p style="font-family:khmer os muol;float:left;">ឈ្មោះ.....................    ឈ្មោះ........................       ឈ្មោះ..................</p>
					<p style="font-family:khmer os muol;float:right;">..................................................</p>
					
				</div>
				<p style="font-family:khmer os muol;padding-left:10px;">ឈ្មោះ......................</p>
				
				<p style="width:120px;height:30px;border:1px solid black;text-align:center;padding-top:5px;font-weight:bold;">OPD-Ind-0013 </p>
				<div class="futer" style="width:100%;overflow:hidden;">
					<div class="left-futer" style="width:50%; float:left;">
						<p style="font-family:khmer os muol;">លេខៈ...................................................</p>
						<p>បានឃើញ និងបញ្ជាក់ថា : សេចក្ដីបញ្ជាក់របស់ លោកមេភូមិ </br>
						……………នេះពិតជាត្រឹមត្រូវប្រាកដមែន ។</p>
						<p style="padding-left:20px;">ថ្ងៃទី........ខែ........ ឆ្នាំ..............</p>
						<p style="font-family:khmer os muol;padding-left:30px;">មេឃុំ-ចៅសង្កាត់..........................</p>
						<p style="font-family:khmer os muol;padding-left:50px;" >ហត្ថលេខា និងត្រា </p>
						<p style="padding-left:30px;padding-top:100px;" >ឈ្មោះ......................</p>
						
					</div>
					
					<div class="right-futer"style="width:50%; float:left;">
						<p style="font-family:khmer os muol;">បានឃើញ និងបញ្ជាក់ថា : </p>
						<p style="line-height:30px;">
							ស្នាមមេដៃស្ដាំខាងលើនេះ ពិតជារបស់ភាគី<span style="font-family:khmer os muol;">“គ”</span> ពិតប្រាកដមែន។
							ទ្រព្យសម្បត្ដិ ដូច ខាងលើ ពុំមានពាក់ព័ន្ធនឹងបញ្ហាអ្វីឡើយហើយភាគី
							“គ” បានយល់ព្រមដាក់បញ្ចាំជំនួស ភាគី“ខ” ឲ្យគ្រឹះស្ថាន ជ ម៉ីជី ហ្វាយ
							 នែន ភីអិលស៊ី ពិតប្រាកដមែន ។
						</p>
						
						<p style="padding-left:20px;">ថ្ងៃទី........ខែ........ ឆ្នាំ..............</p>
						<p style="font-family:khmer os muol;padding-left:30px;">មេភូមិ...............................</p>
						<p style="font-family:khmer os muol;padding-left:50px;" >ហត្ថលេខា </p>
						<p style="padding-left:30px;padding-top:10px;" >ឈ្មោះ......................</p>
						
					</div>
				</div>
				
			</div>
	
		</div>
	</body>
</html>