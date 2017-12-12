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
		width: 99%;
		min-height: 29.7cm;
		padding: 2cm;
		margin: 1cm auto;
		border-radius: 5px;
		background: white;
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
		font-family: Zawgyi-One,'Battambang', Times New Roman;
		}
		.ch-box{
			width:15px;height:15px;border:1px solid black;display:inline-block;
		}
		.small-letter{
			font-family:Zawgyi-One,khmer os muol;font-weight:bold;font-size:12px;
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
		th{
			padding: 10px;
			vertical-align:center;
			text-align: center;
		}
		span{
			font-size:13px;
		}
		.no_border{
			padding: 5px;
			border-bottom-color: white;
		}
		.no_border1{
			padding: 5px;
			border-bottom-color: white;
			text-align: center;
			font-weight: bold;
		}
		.no_border2{
			padding: 5px;
			border-bottom-color: white;
			text-align: right;
		}
		.no_border3{
			text-align: right;
			padding: 5px;
		}

	</style>
	</head>
	<body>

		<div class="contain-wrapper" style="padding:0; margin:0 auto;">
			  
			<div class="header" style=" text-align:center;">
				<div style=" " id="logo">
					<span> 
						<?php if ($Settings->logo2) {
							echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" style="margin-bottom:10px;" />';
						} ?> 
					</span> 
				</div>
					<b>
					 <?php
						//echo $this->session->branchName; 
						 echo $setting->site_name ; 
					 ?>
					</b>
			
			</div>
			<div>
				<p style="text-align:center;" class="small-letter"> <b> <?= $Settings->site_name ?>  Planning Monthly Report Format</b></p>
				<p style="padding-left:10px;text-align:left;" class="small-letter"> <b>အဖြဲ႕အစည္းအမည္ - <?= $Settings->site_name ?> (November'2017) </b></p>
				
			</div>
			<div style=" padding-left:10px; padding-right:10px;">
				<table border="1" style="max-width:100%; font-size:11px;">
					<tr>
						<th style="width:2%" rowspan="2">no</th>
						<th style="width:35%" rowspan="2">အေၾကာင္းအရာ</th>
						<th style="width:3%" rowspan="2">ေရတြက္ပံု</th>
						<th style="width:30%" colspan="3">၂၀၁၅-၂၀၁၆ ခုႏွစ္</th>
						<th style="width:30%" colspan="3">၂၀၁၇-၂၀၁၈ ခုႏွစ္</th>
					</tr>
					<tr>
						<th style="width:10%">စီမံကိန္း</th>
						<th style="width:10%">အစီရင္ခံသည့္လအတြင္း</th>
						<th style="width:10%">အစီရင္ခံသည့္လအထိ</th>
						<th style="width:10%">စီမံကိန္း</th>
						<th style="width:10%">အစီရင္ခံသည့္လအတြင္း</th>
						<th style="width:10%">အစီရင္ခံသည့္လအထိ</th>
					</tr>
					<tr>
						<th style="padding:5px">၁</th>
						<th style="padding:5px">၂</th>
						<th style="padding:5px">၃ </th>
						<th style="padding:5px">၄</th>
						<th style="padding:5px">၅</th>
						<th style="padding:5px">၆</th>
						<th style="padding:5px">၇</th>
						<th style="padding:5px">၈</th>
						<th style="padding:5px">၉</th>
						
					</tr>
					<tr>
						<td class="no_border"></td>
						<td class="no_border">Income from system</td>
						<td  class="no_border">Ks </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td class="no_border3"> </td>
						<td class="no_border3"> -</td>
					</tr>
					<tr>
						<td class="no_border"> </td>
						<td class="no_border1">Total </td>
						<td  class="no_border"></td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td class="no_border3"> -</td>
						<td class="no_border3"> -</td>
					</tr>
					<tr>
						<td class="no_border"></td>
						<td class="no_border">Expanse from system</td>
						<td  class="no_border">Ks </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border2">4,895,850 </td>
						<td class="no_border2"> -</td>
					</tr>
					<tr>
						<td class="no_border"> </td>
						<td class="no_border">Utility, small Item, License fee, Fee and charges paid , stationery , printing and form exp , security, motor vehicle, miscellaneous, Bad and doubtful, Loss Due to late repayment, Bank Charges, Software Maintenance Expanse (Half Payment)</td>
						<td  class="no_border"> ks</td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border2">1,436,950 </td>
						<td class="no_border2"> -</td>
						
					</tr>
					<tr>
						<td class="no_border"> </td>
						<td class="no_border">Rental, communication, Marketing and Advertising Expanse and Travel Expanse </td>
						<td  class="no_border"> ks</td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border2">1,161,750</td>
						<td class="no_border2"> -</td>
						
					</tr>
					<tr>
						<td class="no_border"> </td>
						<td class="no_border"> Renovation Expanses </td>
						<td  class="no_border">ks</td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border2">63,300</td>
						<td class="no_border2"> -</td>
						
					</tr>		
					<tr>
						<td class="no_border"> </td>
						<td class="no_border"> Depreciation </td>
						<td  class="no_border"> ks</td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border2"></td>
						<td class="no_border2"> -</td>
						
					</tr>
					<tr>
						<td class="no_border"> </td>
						<td class="no_border"> Repair and Maintenance </td>
						<td  class="no_border"> ks</td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border2">15,500</td>
						<td class="no_border2"> -</td>
						
					</tr>
					<tr>
						<td class="no_border"> </td>
						<td class="no_border"> Salary and other enplloyee expanse </td>
						<td  class="no_border">ks </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border2"> 2,218,850</td>
						<td class="no_border2"> </td>
						
					</tr>
					<tr>
						<td class="no_border"> </td>
						<td class="no_border"> Interest Expanses</td>
						<td  class="no_border">ks </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border"> </td>
						<td class="no_border2"> </td>
						<td class="no_border2"> -</td>
						
					</tr>
					<tr>
						<td class="no_border"> </td>
						<td class="no_border1"> </td>
						<td class="no_border"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td class="no_border3"> -</td>
						<td class="no_border3"> -</td>
						
					</tr>	
					<tr>
						<td class="no_border" rowspan="2"> </td>
						<td class="no_border1" rowspan="2"> Income from System </td>
						<td class="no_border"  rowspan="2">ks </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td class="no_border3">4,895,850 </td>
						<td class="no_border3">-</td>
						
					</tr><tr>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"></td>
						<td class="no_border3">-</td>
						<td class="no_border3">-</td>
						
					</tr>	

				</table>				 
			</div>
			
			
		</div>
	</body>
</html>
<!--<?php echo($contract_info->gender=male?'checked="checked"':''); ?>
	 <?php echo $contract_info->gender($gender=='female')?'checked':'' ?>
-->