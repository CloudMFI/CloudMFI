<!DOCTYPE html>
<html>
	<head>
		<title>collateral contract</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
					<p style="font-family:Khmer OS Muol;text-align:center;font-size:12px;"​>ព្រះរាជាណាចក្រកម្ពុជា</p>
					<p style="font-family:Khmer OS Muol;text-align:center;font-size:12px;">ជាតិ​​​ សាសនា​ ព្រះមហាក្សត្រ</p>
					
					<p style=" text-align:center;text-decoration:underline;font-size:12px; text-align:center;"><b>កិច្ចសន្យាចងការប្រាក់</b></p>
					<p style=" text-align:center;text-decoration:underline;font-size:12px; text-align:center;"><b>កិច្ចសន្យាធ្វើឡើងនៅថ្ងៃទី.............ខែ.............ឆ្នាំ................រវាងៈ</b></p>
					<!--<div style="position: absolute;left:-150px; padding-left:100px;">
						<h6 style="font-family:Khmer OS Muol; text-decoration:underline;font-size:10px;">កិច្ចសន្យាធ្វើឡើងនៅថ្ងៃទី.............ខែ.............ឆ្នាំ................រវាងៈ</h6>
					</div>-->
				</div>
				
				<div style="width:20%; float:left;margin-top:70px;">
					<span> លេខកិច្ចសន្យា:.......................</span>
				</div>
				<div style="width:20%; float:left;margin-top:10px;">
					<span> លេខអតិថិជន:.........................</span>
				</div>				
			</div>
			
			<div class="phara" style="overflow:hidden;width:100%;min-height:100px;clear:both;padding-top:35px;">
				<div class="pha1-num" style="float:left;">
				<p class="order-num">១​.</p>
				</div>
				<div class="pha1" style="float:left;width:97%;">
					<p class="pha2-text" style="font-size:14px;">
						ភាគីឱ្យខ្ចីប្រាក់ឈ្មោះ.................  ភេទៈ <input type="checkbox" name="gender" value="female" > ស្រី &nbsp <input type="checkbox" name="gender" value="male">ប្រុស  ថ្ងៃខែឆ្នាំកំណើត......./......./........  &nbsp </b> <input type="checkbox" name="idf_card">អត្តសញ្ញាណប័ណ្ណ <br>
						<input type="checkbox" name="family_book">សៀវភៅគ្រួសារ    &nbsp <input type="checkbox" name="birthpaper">សំបុត្រកំណើត  ផ្សេងៗ ................................ លេខ....................ចុះថ្ងៃទី ......./......./........  ចេញដោយ............................................​ តទៅនេះហៅភាគីថា  <span class="small-letter"><<ក>> </span>
					</p>
				</div>
				
				<div class="pha2-num" style="float:left;">
				<p class="order-num">២​.</p>
				</div>
				
				<div class="pha2" style="float:left;width:97%;">
					<p class="pha2-text" style="font-size:14px;">
						ភាគីសុំខ្ចីប្រាក់ឈ្មោះ.............<?php echo $contract_info->customer_name?>................. ភេទៈ <input type="checkbox" name="gender" value="female" > ស្រី &nbsp <input type="checkbox" name="gender" value="male">ប្រុស  ថ្ងៃខែឆ្នាំកំណើត......./......./........  &nbsp </b> <input type="checkbox" name="idf_card">អត្តសញ្ញាណប័ណ្ណ <br>
						<input type="checkbox" name="family_book">សៀវភៅគ្រួសារ    &nbsp <input type="checkbox" name="birthpaper">សំបុត្រកំណើត  ផ្សេងៗ ................................ លេខ....................ចុះថ្ងៃទី ......./......./........  ចេញដោយ............................................​ តទៅនេះហៅភាគីថា <span class="small-letter"><<ខ>> </span> ឬកូនបំណុល​ ។
					</p>
				</div>
				<p style="text-align:center;"><b>ភាគី <span class=small-letter>«ក»</span>និង ភាគី <span class=small-letter>«ខ»</span>បានព្រមព្រៀងទទួលយកនូវលក្ខន្តិកៈដូចខាងក្រោមៈ</b></p>
				<p><span class="small-letter">ប្រការ១-</span>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
				<span class="small-letter">អំពីលក្ខខណ្ឌរួម</span></p>
				<p style="padding-left:50px;">
					លើមូលដ្ឋាននៃពាក្យសុំខ្ចីចងការប្រាក់ សេចក្តីណែនាំស្តីពីគោលការណ៍ និងលក្ខខណ្ឌនៃការឲ្យខ្ចីចងការប្រាក់ភាគី ​<span class=small-letter>«ក»</span>​ យល់ព្រមឲ្យភាគី​ ​<span class="small-letter"><<ខ>> </span>
					ខ្ចីចងការ ហើយភាគី​​<span class="small-letter"><<ខ>> </span>ក៏ព្រមទទួល និងសន្យាសងមកភាគី <span class=small-letter>«ក»</span>​​ វិញជាដាច់ខាតនូវប្រាក់ដើម និង ការប្រាក់ គ្រប់ចំនួន និងគោរពលក្ខខណ្ឌដែលបានព្រមព្រៀងគ្នាដូចតទៅនេះ៖
				</p>
				
				<div class="pha5-num" style="float:left;">
					<p class="order-num">១​.១.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ទ្រព្យធានា៖    ក្រោមការដាក់បញ្ចាំ/ដាក់ធានាអះអាងដើម្បីបំណុលទាំងប្រាក់ដើម ការប្រាក់ និងប្រាក់ពិន័យ 
					</p>
					<ul>
						<li> <input type="checkbox"/> ចលនវត្ថុ  &nbsp <input type="checkbox"/>អចលនវត្ថុរបស់ឈ្មោះ.......................................... និងឈ្មោះ............................................... ដែលមានបញ្ជាក់ក្នុងកិច្ចសន្យាដាក់បញ្ចាំ។</li>
						<li> <input type="checkbox"/> ចលនវត្ថុ  &nbsp <input type="checkbox"/>អចលនវត្ថុរបស់ឈ្មោះ.......................................... និងឈ្មោះ...............................................ជាអ្នកយល់ព្រមដាក់បញ្ចាំជំនួសភាគី​ <span class="small-letter"><<ខ>> </span> ដែលមានបញ្ជាក់ក្នុងកិច្ចសន្យាដាក់បញ្ចាំ។</li>
					</ul>
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">១​.២.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ប្រាក់ខ្ចីចងការដែលភាគី <span class="small-letter"><<ខ>> </span> បានទទួលចំនួនជាលេខ.................................... ចំនូនអក្សរ .......................................
					</p>
					
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">១​.៣.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						អត្រាការប្រាក់ប្រចាំ<input type="checkbox"/> ថ្ងៃ​ &nbsp <input type="checkbox"/> សប្តាហ៍ &nbsp <input type="checkbox"/>ខែ...........%ដោយគណនាទៅតាមសមតុល្យប្រាក់ដើមជាក់ស្តែងដែលនៅជំពាក់។
					</p>
					
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">១​.៤.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						រយៈពេលប្រាក់កម្ចី........................&nbsp​ <input type="checkbox"/> ថ្ងៃ​ &nbsp&nbsp​ <input type="checkbox"/> សប្តាហ៍ &nbsp&nbsp​ <input type="checkbox"/> ខែ &nbsp​  គិតចាប់ពីថ្ងៃទទួលប្រាក់តទៅ រហូតដល់ថ្ងៃដែលត្រូវបង់ប្រាក់ចុងក្រោយដូចមានចែងនៅក្នុងកាលវិភាគសងប្រាក់ដែលភាគី <span class="small-letter"><<ក>> </span> បានផ្ដល់ជូន
					</p>
					
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">១​.៥.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						របៀបសងត្រឡប់ប្រាក់កម្ចី អនុវត្តន៍តាមតារាងកាលវិភាគសងប្រាក់ដោយបង់ប្រាក់ផ្ទាល់ ឬកាត់ចេញពីគណនីរបស់ <span class="small-letter"><<ខ>></span>​។
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
						<li> ត្រូវបង់ប្រាក់<input type="checkbox"/> បង់សងប្រាក់ជាមួយភាគី <span class="small-letter"><<ក>></span> </li>
						<li> កាលវិភាគសងប្រាក់ កិច្ចសន្យា កិច្ចសន្យាដាក់បញ្ចាំ ប័ណ្ណចំណាយប្រាក់អោយខ្ចី ឬប័ណ្ណដកប្រាក់ជាឧបសម្ព័ន្ធនៃកិច្ចសន្យានេះ។</li>
					</ul>
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">១​.៨.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ភាគី <span class="small-letter"><<ខ>></span>​​សន្យាថានឹងមិនប្រើប្រាស់ឥណទានដែលទទួលបានពីភាគី <span class="small-letter"><<ក>></span>ធ្វើឲ្យប៉ះពាល់អវិជ្ជមានដល់បរិស្ថាន សង្គមនិងប្រើប្រាស់លើសកម្មភាពទាំងឡាយដែលហាមឃាត់ដោយច្បាប់របស់ព្រះរាជាណាចក្រកម្ពុជាឡើយ។
					</p>
					
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">១​.៩.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ភាគី <span class="small-letter"><<ខ>></span>​​សន្យាថានឹងមិនប្រើប្រាស់កម្ចីដែលទទួលបានពីភាគី <span class="small-letter"><<ក>></span>យកទៅចែកអ្នកដទៃឬប្រើប្រាស់ខុសពីគោលបំណងនៃពាក្យស្នើសុំខ្ចីប្រាក់ឡើយ។
					</p>
					
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">១​.១០.</p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ភាគី <span class="small-letter"><<ខ>></span>​​យល់ព្រមឲ្យភាគី <span class="small-letter"><<ក>></span>ធ្វើការត្រួតពិនិត្យតាមដានការប្រើប្រាស់ប្រាក់កម្ចី បើសិនភាគី<span class="small-letter"><<ក>></span>រកឃើញថា ភាគី <span class="small-letter"><<ខ>></span>មិនបានប្រើប្រាស់ប្រាក់កម្ចីតាមគោលបំណងដែលបានបញ្ជាក់ក្នុងពាក្យស្នើសុំខ្ចីប្រាក់ ឬចែករំលែកប្រាក់កម្ចីជាមួយភាគីផ្សេងទៀត ភាគី <span class="small-letter"><<ក>></span>​សូមរក្សាសិទ្ធិដោយតម្រូវឲ្យ ភាគី  <span class="small-letter"><<ខ>></span>បង់ប្រាក់កម្ចីទាំងអស់មកជ ម៉ីជីងហ្វាយនែន វិញភ្លាម ឬអាចធ្វើការប្តឹងភាគី<span class="small-letter"><<ខ>></span>ពីបទរំលោភលើសេចក្តីទុកចិត្ត។
					</p>
					
				</div>
				
				<div class="small-letter" style="float:left;">
					<p>​ប្រការ៣៖ &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;	<span class="small-letter">អំពីលក្ខខណ្ឌរឲពិសេស</span></p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ក្នុងករណីដែលភាគី<span class="small-letter"><<ខ>></span>មិនបានអនុវត្តតាមកិច្ចសន្យាចងការប្រាក់ដូចមានចែងក្នុងប្រការ១ ចំណុច ១.១ ដល់ ចំណុច ១.១០ខាងលើនេះ ភាគី <span class="small-letter"><<ខ>></span> យល់ព្រមឲ្យ ភាគី<span class="small-letter"><<ក>></span> អនុវត្តវិធានការដូចតទៅៈ
					</p>					
				</div>
				<div style="clear:both;"></div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">៣.១. </p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ក្នុងករណីកូនបំណុលខកខានមិនបានសងប្រាក់តាមកាលកំណត់ក្នុងតារាងសងប្រាក់ទេនោះ កូនបំណុលត្រូវបាននឹងត្រូវធ្វើការបង់ប្រាក់ពិន័យក្នុងអត្រា0.៥០ %ក្នុង១ថ្ងៃដោយគណនាលើទំហំទឹកប្រាក់សរុបដែលអតិថិជនត្រូវបង់សង ប៉ុន្តែមិនបានបង់សងតាមកាលកំណត់។ ការគណនាប្រាក់ពិន័យគឺត្រូវរាប់បញ្ចូលទាំងថ្ងៃចុងសប្តាហ៍ និងថ្ងៃឈប់សម្រាកសាធារណៈផងដែរ។
					</p>					
				</div>
				<div class="pha5-num" style="float:left;">
					<p class="order-num">៣.២. </p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						អតិថិជនអាចត្រូវបានអនុញ្ញាតឲ្យបង់ផ្តាច់ដើមមុនកាលកំណត់ក្រោយពេលធ្វើការបង់ប្រាក់តាមកាលវិភាគសងប្រាក់បានរយៈពេល៣ខែហើយ ដោយពុំមានការផាកពិន័យអ្វីឡើយ។ ដោយឡែក បើអតិថិជនបង់ផ្តាច់ដើមមុនពេលខ្លួនបានអនុវត្តការបង់ប្រាក់តាមកាលវិភាគរយៈពេល៣ខែ នោះអតិថិជនត្រូវបង់ប្រាក់ពិន័យដែលស្មើនឹង៥០%ការប្រាក់នាខែបន្ទាប់ចំនួន១ខែទៀត។
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
						ក្នុងករណីដែលខកខានសងបំណុលតាមការកំណត់តាមចំណុច <span class="small-letter">ប្រការ១</span>  កន្លងផុតរយៈពេល ០១ខែ ភាគី <span class="small-letter"><<ខ>></span> សុខចិត្តលក់ទ្រព្យ សម្បត្តិរបស់ខ្លួន ដើម្បីទូទាត់បំណុល ឬ ឱ្យអ្នកធានាបំណុលរបស់ខ្លួន ដែលបានសងជំនួស ឬ ភាគី <span class="small-letter"><<ក>></span> កាត់កង ឬ ប្តឹងទៅ តុលាការ ដើម្បីរឹបអូសទ្រព្យសម្បត្តិខ្លួន ជាចលនទ្រព្យ, អចលនទ្រព្យដែលដាក់ធានា, ឬ ទ្រព្យសម្បត្តិផ្សេងទៀត ដូចជា សន្និធិ, ទំនិញ, ចលនទ្រព្យ, ឬ អចលនទ្រព្យ ដែលមានក្រៅពីទ្រព្យដាក់ធានា លក់ឡាយឡុង ដើម្បីកាត់យកមកទូទាត់ប្រាក់សំណង ដែលបានសងជួស ឬ ទូទាត់បំណុលដែលនៅជំពាក់ភាគី <span class="small-letter"><<ខ>></span> ត្រូវមានកាតព្វកិច្ចសងបំណុលដែលនៅសល់បន្ថែមទៀតរហូតទាល់ តែគ្រប់ចំនួន ។
					</p>					
				</div>
				<div class="small-letter" style="float:left;">
					<p>​ប្រការ៤៖ &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;	<span class="small-letter">អំពីលក្ខខណ្ឌអវសាន្ត</span></p>
				</div>
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						ភាគី  <span class="small-letter">«ក»</span> និងភាគី <span class="small-letter">«ខ»</span> សន្យាគោរពយ៉ាងម៉ឺងម៉ាត់តាមរាល់ប្រការនៃខសន្យានានាខាងលើ ។   ក្នុងករណីមានការអនុវត្តផ្ទុយ ឬ ដោយរំលោភលើលក្ខខណ្ឌណាមួយនៃកិច្ចសន្យានេះ ភាគីដែលល្មើសត្រូវទទួលខុសត្រូវចំពោះមុខច្បាប់ជាធរមាន ។ រាល់សោហ៊ុយ ចំណាយទាក់ទងក្នុងការដោះស្រាយលើវិវាទ ជាបន្ទុករបស់ភាគីដែលរំលោភបំពានលើកិច្ចសន្យា។ កិច្ចសន្យានេះត្រូវបានធ្វើឡើង ដោយមានការព្រមព្រៀងពិតប្រាកដ និងដោយសេរី គ្មានការបង្ខិតបង្ខំពីភាគីណាមួយឡើយ ហើយមានប្រសិទ្ឋភាពចាប់ពីថ្ងៃ....................................................................................................។          

					</p>					
				</div>
				<!---->
				<div class="header" style="width:100%;float:left;">
					<div class="nationality-identifier" style="width:100%; float:left;margin-top:20px;position:relative;">
						<p style="font-family:Khmer OS Muol;text-align:center;font-size:12px;"​>កិច្ចសន្យានេះត្រូវបានធ្វើឡើងជាពីរច្បាប់ ជាភាសាខ្មែរ ដើម្បីតម្កល់ទុកនៅៈ</p>
						<p style="text-align:center;font-size:12px;"​>- ភាគី <span class="small-letter">«ក»​ ...............................................................................</span> ០១ ច្បាប់ (ច្បាប់ដើម)</p>
						<p style="text-align:center;font-size:12px;"​>- ភាគី <span class="small-letter">«ខ»​ ...............................................................................</span> ០១ ច្បាប់ (ច្បាប់ដើម)</p>
												
					</div>												
				</div>
				
				<div class="nationality-identifier" style="width:100%; float:left;position:relative; text-align:right;margin-bottom:20px;">
					ធ្វើនៅ.............................ថ្ងៃទី..................ខែ....................ឆ្នាំ....................					
				</div>												
				
				
				
				<div class="chat" style="width: 100%;">
					<table>
						<tr>
							<td style="width: 25%;"> សាក្សី​ </td>
							<td colspan="2" style="width: 40%;"> ស្នាមមេដៃស្ដាំអ្នកខ្ចីប្រាក់  (ភាគី<span class="small-letter">«ខ»</span>)</td>
							<td style="width: 35%;">តំណាងភាគីផ្តល់ប្រាក់កម្ចី​ (ភាគី<span class="small-letter">«ក»</span>)</td>
						</tr>
						
						<tr>
							<td></td>
							<td>អ្នករូមខ្ចី</td>
							<td>អ្នកសុំខ្ចី</td>
							<td></td>
						</tr>
					</table>
				</div>
				
				<div class="pha5" style="float:left;width:95%;">
					<p class="pha5-text" style="font-size:14px;">
						<span class="small-letter">កិច្ចព្រមព្រៀង និង សេចក្តីបញ្ជាក់របស់អ្នកធានា៖ </span> ខ្ញុំបាទ/នាងខ្ញុំឈ្មោះ .......................................... ភេទៈ  <input type="checkbox"/>ស្រី   &emsp; <input type="checkbox"/>ប្រុស   ថ្ងៃខែឆ្នាំកំណើត ........./........../........  &emsp; <input type="checkbox"/>អត្តសញ្ញាណប័ណ្ណ   &emsp; <input type="checkbox"/>សៀវភៅគ្រួសារ   &emsp; <input type="checkbox"/>សំបុត្រកំណើត  &emsp; <input type="checkbox"/>ផ្សេងៗ......................លេខ.........................ចុះថ្ងៃទី........./.........../...........ចេញដោយ..................... និងឈ្មោះ............................    ភេទ   <input type="checkbox"/>ស្រី    &emsp; <input type="checkbox"/>ប្រុស​​  ថ្ងៃខែឆ្នាំកំណើត......../......./.........ត្រូវជា......................លេខទំនាក់ទំនង.......................... ជាអ្នកធានា និង អ្នករួមធានានូវបំណុលរបស់ ភាគី  <span class="small-letter">«ខ»</span> សូមធានាអះអាងថា ក្នុងករណីដែល ភាគី <span class="small-letter">«ខ»</span> មិនអាចអនុវត្តកាព្វកិច្ចសងបំណុលឲ្យបានគ្រប់ចំនួន ទាំងប្រាក់ដើម ទាំងការប្រាក់ ព្រមទាំងប្រាក់ពិន័យ ទៅតាមលក្ខខណ្ឌដូចបានរៀបរាប់នៅក្នុងកិច្ចសន្យាខាងលើ ខ្ញុំ/យើងខ្ញុំជា អ្នកធានា និង អ្នករួមធានា សុខចិត្តចេញសងជំនួសដោយឥតលក្ខខណ្ឌ។ 
					</p>
				
				</div>
				
				<div class="letter-footer" style="with:100%;">
					<div class="left-footer" style="width: 50%; float:left;padding:10px;">
						<p>
							ស្នាមផ្ដិតមេដៃ​ស្ដាំអ្នករួមធានា (ប្ដី/ ប្រពន្ធ/......................)  
						</p> <br>						
						<p style="padding-left:50px;">
							<span class="small-letter">ឈ្មោះ...................................</span>
						</p>
						
						
					</div>
					
					<div class="left-footer" style="width: 50%; float:left;padding:10px;">
						<p>
							ធ្វើនៅ.................ថ្ងៃទី..............ខែ............ឆ្នាំ................
						</p>
						<p>
							ស្នាម​ផ្ដិតមេដៃស្ដាំអ្នកធានា
						</p>						
						<p style="padding-left:50px;">
							<span class="small-letter">ឈ្មោះ......................................</span>
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