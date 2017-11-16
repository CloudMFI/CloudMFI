<!DOCTYPE html>
	<head>
		<title><?= lang('contract'); ?></title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style type="text/css">
        html, body {
            height: 100%;
            background: #FFF;
        }

        body:before, body:after {
            display: none !important;
        }		
		.tdborder td {
			border: 1px solid black;
			height:25px;
		}
		
		</style>
	</head>
	<body>
		<div class="form" id="wrap" style="width: 95%; margin: 0 auto;">
			<div class="row">
				<div class="col-lg-12">					
					<div class="text-center" style="font-family:Khmer OS Muol;margin-top:30px;">
						<h4><b>ព្រះរាជាណាចក្រកម្ពុជា</b></h4>
						<h4 style="margin-top:10px;"><b>ជាតិ សាសនា ព្រះមហាក្សត្រ</b></h4>
						<img src="<?php echo base_url().'themes/default/assets/images/symbol.png'; ?>"/>						
						<h4 style="margin-top:15px;padding-bottom:20px;"><b>ជ​ មុី ជីង ហ្វាយនែន ភីអិលសុី</b></h4>						
					</div>
					<div class="col-xs-6"style="margin-top:-120px;">
						<img style="width:35%;height:35%;float:left;padding-left:0px;" src="<?php echo base_url().'themes/default/assets/images/logo.png'; ?>"/>					
					</div>
					<div class="col-xs-12" style="padding-left:20px;margin-top:-10px;padding-bottom:20px;">
						<h4 style="color:green !important;">ធ្វើអោយគ្រួសាររុងរឿង</h4>
					</div>		
					<div class="col-xs-12" style="text-align:center;margin-top:-45px;font-family:Khmer OS Muol !important;">
						<h3>កិច្ចសន្យាខ្ចីប្រាក់</h3>	
						<br/>
					</div>			
				<div class="col-xs-12" style="font-size:15px;padding-left:0px;text-align:justify;line-height:30px;">
						<p><b>គណនីកម្ជីលេខ៖ <?= (($contract && $contract->reference_no)? $contract->reference_no : 'N/A'); ?></b></p>						
						<p><b>១. ភាគីអោយខ្ចី៖</b> <b>ជ​ មុី ជិង ហ្វាយនែន ភីអិលសុី</b> អាស័យដ្ធាននៅ ផ្ទះលេខ 5Z, ផ្លូវលេខ 181 ឃុំ/សង្គាត់ ទំនប់ទឹក ស្រុក/ខណ្ឌ ចំការមន​ ខេត្ត/ក្រុង ភ្នំពេញ ។ អ្នកតំណាងដោយ <b> <?= $approval->first_name .' '. $approval->last_name; ?> </b>មានមុខងារជា <b>នាយកសាខា(BM)</b> តទៅនេះ <b>ហៅថា​ ភាគី "ក" </b>។</p>
						<?php if($applicant) { ?>
						<p><b>២.</b> <b>ភាគីអ្នកខ្ចី៖</b> ឈ្មោះ<b> <?= (($applicant && $applicant->family_name_other)? $applicant->family_name_other : $applicant->family_name) .' '. (($applicant && $applicant->name_other)? $applicant->name_other : $applicant->name); ?> </b>ភេទ <b><?= (($applicant && $applicant->gender == 'male')? 'ប្រុស' : (($applicant && $applicant->gender == 'female')? 'ស្រី' : 'N/A')); ?></b> កើតថ្ងៃទី <b>
							<?= (($applicant && $applicant->date_of_birth)? (date('d', strtotime($applicant->date_of_birth)) .'-'. $this->erp->KhmerMonth(date('m', strtotime($applicant->date_of_birth))) .'-'. date('Y', strtotime($applicant->date_of_birth))) : 'N/A'); ?></b>  ឯកសារកំណត់អត្តសញ្ញាណ<b>កាន់អត្តសញ្ញាណប័ណ្ណ លេខ <?= (($applicant && $applicant->gov_id)? $applicant->gov_id : 'N/A'); ?> 
							ចុះថ្ងៃទី <?= (($applicant && $applicant->gov_id_date)? (date('d', strtotime($applicant->gov_id_date)) .'-'. $this->erp->KhmerMonth(date('m', strtotime($applicant->gov_id_date))) .'-'. date('Y', strtotime($applicant->gov_id_date))) : 'N/A'); ?>  </b>អត្តសញ្ញាណប័ណ្ណ អាស័យដ្ធាន ភូមិ <?= (($addr && $addr["village"])? $addr["village"] : 'N/A'); ?> ឃុំ/សង្គាត់ <?= (($addr && $addr["communce"])? $addr["communce"] : 'N/A'); ?>
							ស្រុក/ខណ្ឌ <?= (($addr && $addr["district"])? $addr["district"] : 'N/A'); ?> ខេត្ត/ក្រុង <?= (($addr && $addr["province"])? $addr["province"] : 'N/A'); ?> តទៅនេះ ហៅថា <span style="font-size:18px !important"><b>ភាគី "ខ"</b></span> ។</p>
						<?php } ?>
						<?php if($guarantor) { ?>
						<p><b>៣.</b><b> អ្នកធានាបំណុល៖</b> ឈ្មោះ <b><?= (($guarantor && $guarantor->family_name_other)? ($guarantor->family_name_other) : (($guarantor && $guarantor->family_name)? $guarantor->family_name : '') .' '. (($guarantor && $guarantor->name_other)? ($guarantor->name_other) : (($guarantor && $guarantor->name)? $guarantor->name : ''))); ?> </b>ភេទ <?= (($guarantor && $guarantor->gender == 'male')? 'ប្រុស' : (($guarantor && $guarantor->gender == 'female')? 'ស្រី' : 'N/A')); ?> កើតថ្ងៃទី <b>
							<?= (($guarantor && $guarantor->date_of_birth)? (date('d', strtotime($guarantor->date_of_birth)) .'-'. $this->erp->KhmerMonth(date('m', strtotime($guarantor->date_of_birth))) .'-'. date('Y', strtotime($guarantor->date_of_birth))) : 'N/A'); ?> </b>ឯកសារកំណត់អត្តសញ្ញាណ <b>កាន់អត្តសញ្ញាណប័ណ្ណ លេខ <?= (($guarantor && $guarantor->gov_id)? $guarantor->gov_id : 'N/A'); ?>  
							ចុះថ្ងៃទី <?= (($guarantor && $guarantor->gov_id_date)? (date('d', strtotime($guarantor->gov_id_date)) .'-'. $this->erp->KhmerMonth(date('m', strtotime($guarantor->gov_id_date))) .'-'. date('Y', strtotime($guarantor->gov_id_date))) : 'N/A'); ?> </b>អត្តសញ្ញាណប័ណ្ណ អាស័យដ្ធាន ភូមិ <?= (($g_addr && $g_addr["village"])? $g_addr["village"] : 'N/A'); ?> ឃុំ/សង្គាត់ <?= (($g_addr && $g_addr["communce"])? $g_addr["communce"] : 'N/A'); ?> 
							ស្រុក/ខណ្ឌ <?= (($g_addr && $g_addr["district"])? $g_addr["district"] : 'N/A'); ?> ខេត្ត/ក្រុង <?= (($g_addr && $g_addr["province"])? $g_addr["province"] : 'N/A'); ?> ។</p>
				</div>
						<?php } ?>
				<div class="col-xs-12" style="text-align:center;">
						<br/>
						<h4>ភាគីទំាងពីរបានព្រមព្រៀងគ្នាដូចតទៅ៖</h4>
				</div>
                <table style="width: 100%;">				
					<tbody style="font-size:15px;line-height:30px;font-family:Khmer OS System !important;" >
						<tr style="text-align:justify;">
							<td style="width:20px;vertical-align:top;"><b>ប្រការ១៖&nbsp;</b></td>
							<td><b> ភាគី "ខ"</b> បានខ្ចីប្រាក់​ចំនួន<b> <?= $this->erp->formatMoney(($contract && $contract->grand_total)? $contract->grand_total : 0) .' '. (($currency && $currency->name_other)? $currency->name_other : 'N/A'); ?> </b>ពី<b> ភាគី "ក"</b> ដើម្បីយក​ទៅ​ប្រើ​បា្រស់ក្នុង​គោលបំណង​មាន​អត្រា​ការ​ប្រាក់​សម​រម្យលើ​ប្រាក់​ដើម​ដែល​នៅ​សល់​ប្រចំា​សប្តាហ៏ ឬប្រចំាខែ ។</td>							
						</tr>
						<tr style="text-align:justify;">
							<td style="width:20px;vertical-align:top;"><b>ប្រការ២៖&nbsp;</b></td>
							<td>ក្នុងកំឡុងពេលនៃការសងប្រាក់<b> ភាគី "ខ"​ </b>នឹងបង់ប្រាក់សេវាមោឃភាព ដើម្បីទុក​ជាមោឃៈនូវប្រាក់ជំពាក់​ចុង​ក្រោយ​បន្ទាប់ពីទូទាត់ប្រាក់ក្នុងលក្ខខណ្ឌដែល<b> ភាគី "ខ" </b>បាន​បង់​ប្រាក់សេវា​មោឃៈ​ភាព​ដែល​បាត់បង់​អាយុជីវិត​នោះ​ប្រាក់ជំពាក់​នឹង​មិនទាមទារ​ពីក្រុមគ្រួសារអ្នក​ស្នងមរតក ឬអ្នក​ធានារបស់<b> ភាគី "ខ" </b>ទៀតឡើយ ។</td>							
						</tr>
						<tr style="text-align:justify;">
							<td style="width:20px;vertical-align:top;"><b>ប្រការ៣៖&nbsp;</b></td>
							<?php
								$last_date = $this->erp->getLastPaymentDate($contract->term, $contract->frequency, $contract->due_date);
							?>
							<td><b> ភាគី"ខ" </b>ត្រូវសងរំលោះអោយ​បាន​គ្រប់​តាម​ចំនួន​ប្រាក់ក្នុង​តារាងបង់ប្រាក់ជា​រៀង​រាល់ ​​សប្តាហ៌/ខែ ដោយ​ចាប់​ផ្តើមបង់ពីថ្ងៃទី<b> <?= (($contract && $contract->due_date)? (date('d', strtotime($contract->due_date)) .'-'. $this->erp->KhmerMonth(date('m', strtotime($contract->due_date))) .'-'. date('Y', strtotime($contract->due_date))) : 'N/A'); ?> </b>សម្រាប់​​រយៈពេល<b> <?= round($contract->term/$contract->frequency); ?></b>ដង ។ កាលបរិច្ឆេត​សងបព្ចាប់​ថ្ងៃទី <b><?= (($last_date)? (date('d', strtotime($last_date)) .'-'. $this->erp->KhmerMonth(date('m', strtotime($last_date))) .'-'. date('Y', strtotime($last_date))) : 'N/A'); ?> </b> ។</td>							
						</tr>
						<tr style="text-align:justify;">
							<td style="width:20px;vertical-align:top;"><b>ប្រការ៤៖&nbsp;</b></td>
							<td>ក្នុងកំឡុងពេល<b> ភាគី"ខ" </b>នៅជំពាក់ប្រាក់ ប្រសិនបើ<b> ​ភាគី"ខ" ​</b>ខក​ខាន​បង់បា្រក់​តាម​កាល​​​​វិភាគ​​សង​ប្រាក់<b>​ ភាគី"ខ"​ </b>​សុខ​​ចិត្ត​​បង់​​ប្រាក់​​ពិន័យ​១ថ្ងៃ<b> <?= $this->erp->formatMoney($sys_setting->penalty_amount) .'ដុល្លារ'; ?></b>។</td>							
						</tr>
						<tr style="text-align:justify;">
							<td style="width:20px;vertical-align:top;"><b>ប្រការ៥៖&nbsp;</b></td>
							<td>ប្រសិនបើប្រាក់កម្ចីដល់កាលកំណត់ហើយនៅពុំទាន់​សង​អស់​ការ​ប្រាក់និង​ត្រូវ​បង់​​បន្ត​​រហូត​​ដល់​ពេល​​ដែល​​ប្រាក់​​ជំពាក់​​ត្រូវ​បានគ្រប់​ចំនួន ។</td>							
						</tr>
						<tr style="text-align:justify;">
							<td style="width:20px;vertical-align:top;"><b>ប្រការ៦៖&nbsp;</b></td>
							<td>ក្នុងករណីគ្មានលទ្ធភាពសងបំណុលតាមកាលវិភាគកំណត់សងប្រាក់ទេ ឬខកខានមិនបានសង​ប្រាក់​នេះ​<b> ភាគី"ខ" </b>​និង​អ្នក​ធាន​សុខ​ចិត្ត​អោយ <b>​ភាគី"ក" </b>​រឹប​អូស​ទ្រព្យ​សម្បត្តិ​របស់​ខ្លួន​លក់​ឡៃឡុង​ដើម្បី​យក​ប្រាក់​មក​ទូទាត់​បំណុល​ដែល​ខ្លួន​នៅ​ជំពាក់​រហូត​ទាល់​តែ​គ្រប់​ចំនួន​រួម​ទាំង​ប្រាក់​ដើម​ការ​ប្រាក់​និង​ប្រាក់ពិន័យ ។</td>							
						</tr>
						<tr style="text-align:justify;">
							<td style="width:20px;vertical-align:top;"><b>ប្រការ៧៖&nbsp;</b></td>
							<td>ក្នុងករណីដែល<b> ភាគី "ខ" </b>គ្មានលទ្ធភាពសងប្រាក់​ជំពាក់​អ្នក​ធានានៃ​កិច្ច​សន្យា​នេះ​និង​សង​ប្រាក់​ជំពាក់​ទំាង​អស់​ដែល​មិន​ទាន់​សង​ជូន​ដល់ <b> ភាគី"ក" </b>។</td>							
						</tr>
						<tr style="text-align:justify;">
							<td style="width:20px;vertical-align:top;"><b>ប្រការ៨៖&nbsp;</b></td>
							<td>ប្រសិនបើ<b> ភាគី"ខ" </b>និងអ្នកធានាមិនគោរព​តាម​លក្ខខណ្ឌ​ទំាង​ឡាយ​នៃ​កិច្ច​សន្យា​នេះ​ទេ​ហើយមិន​​បាន​បង់​ប្រាក់​រំលោះ​​ជា​​ច្រើន​​លើក​​ច្រើន​​សារ​​<b> ភាគី"ក" </b>អាច​ធ្វើ​ការ​ប្តឹង​ទៅ​ស្ថាប័ន​គតិ​យុត្តិ​ដ៏​សម​ស្រប​ណា​មួយ​​ដែល​​បាន​ជ្រើស​រើស​ដោយ​<b> ភាគី"ក" </b>​ដើម្បី​ទាម​ទារ​ប្រាក់​ជំពាក់​ទោះបី​មុន​កាល​កំណត់​នៃ​ការ​សង​ប្រាក់​ក៏​ដោយ​ ។ ​ប្រសិន​បើ​មាន​ការ​​ចាត់​វិធាន​ការ​តាម​ផ្លូវ​ច្បាប់​ថ្លៃ​ឈ្នួល​និង​ថ្លៃ​ចំណាយផ្សេងៗតាមផ្លូវច្បាប់គឺជា​បន្ទុក​របស់​<b> ភាគី"ខ" </b>ទំាងស្រុង ។</td>							
						</tr>
						<tr style="text-align:justify;">
							<td style="width:20px;vertical-align:top;"><b>ប្រការ៩៖&nbsp;</b></td>
							<td>កិច្ចសន្យានេះត្រូវបានធ្វើឡើងដោយពុំមាន​ការ​បង្ខិត​បង្ខំ​ពី​ភាគី​ណាមួយ​ឡើយ​ភាគី​ទំាង​ពីរ​បាន​អាន​និង​ព្រមព្រៀងគ្នា​គ្រប់​លក្ខខណ្ឌ​និង​ខ្លឹមសារ​ទំាងអស់​នៃ​កិច្ចសន្យា​នេះ​ហើយ​ចុះ​ហត្ថ​លេខា​និង​ផ្តិត​មេដៃស្តំា​ទុក​ជា​ភស្តុតាង ។</td>							
						</tr>
					</tbody>
                </table>
            </div>
		<div><br/></div>
       <div class="row" style="font-size:15px;">
				
                <div class="col-xs-4" style="text-align:center;margin-left:0px;padding-left:0px;">
					<p style="padding-left:5px;">ធ្វើនៅ ថ្ងៃទី <?= (($contract && $contract->contract_date)? date('d', strtotime($contract->contract_date)) : ''); ?> ខែ <?= (($contract && $contract->contract_date)? $this->erp->KhmerMonth(date('m', strtotime($contract->contract_date))) : ''); ?> ឆ្នំា <?= (($contract && $contract->contract_date)? date('Y', strtotime($contract->contract_date)) : ''); ?></p>
					<br/>
                    <p>តំណាងម្ចាស់កម្ចី</p>
                </div>              
				
				<div class="col-xs-5" style="text-align:center;">
					<p>ធ្វើនៅ ថ្ងៃទី <?= (($contract && $contract->contract_date)? date('d', strtotime($contract->contract_date)) : ''); ?> ខែ <?= (($contract && $contract->contract_date)? $this->erp->KhmerMonth(date('m', strtotime($contract->contract_date))) : ''); ?> ឆ្នំា <?= (($contract && $contract->contract_date)? date('Y', strtotime($contract->contract_date)) : ''); ?></p>
					<br/>
                    <p>ស្នាមមេដៃស្តំាអ្នកធានា</p>
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<p><?= (($guarantor && $guarantor->family_name_other)? ($guarantor->family_name_other) : (($guarantor && $guarantor->family_name)? $guarantor->family_name : '') .' '. (($guarantor && $guarantor->name_other)? ($guarantor->name_other) : (($guarantor && $guarantor->name)? $guarantor->name : ''))); ?></p>
                </div>
				<div class="col-xs-3" style="margin-left:0px;padding-left:0px;">					
					<p>&nbsp;</p>
					<br/>
					<p>ស្នាមមេដៃស្តំាអ្នកខ្ខី</p>
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<p><?= ($applicant->family_name_other? $applicant->family_name_other : $applicant->family_name) .' '. ($applicant->name_other? $applicant->name_other : $applicant->name); ?></p>
                </div>
			</div>
	</body>
</html>