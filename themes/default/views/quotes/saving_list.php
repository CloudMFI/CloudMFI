<?php //$this->erp->print_arrays($count_group) ?>
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

	</style>
	</head>
	<body>
		<div class="contain-wrapper" style="padding:0; margin:0 auto;">
			  
			<div class="header" style=" text-align:center;">
				<div style=" " id="logo">
					<span> 
						<?php //if ($Settings->logo2) {
							// echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" style="margin-bottom:10px;" />';
						// } ?> 
					</span> 
				</div>
					
			
			</div>
			<div>
			
				<h2 style="text-align:center;padding-top:10px;"  class="small-letter"> <b>စုေဆာင္းေငြစာရင္း</b></h2>
				<h2 style="text-align:center;" class="small-letter"> <b>Saving List</b></h2>
			</div>
			
			<div style=" height:100px; padding-left:15px;font-size:10px;line-height: 18px;">
				<table width="100%">
					<tr>
						<td style="width:30%;">အေကာင့္နံပါတ္ (Account Number)</td>
						<td>:</td>
					</tr>
					<tr>
						<td style="width:30%;">အမည္ (Name)</td>
						<td>:</td>
					</tr>
					<tr>
						<td style="width:30%;">နို္င္ငံသားမွတ္ပံုတင္အမွတ္ (NRC Number)</td>
						<td>:</td>
					</tr>
					<tr>
						<td style="width:30%;">အေကာင့္အမ်ိဳးအစား(Account Type)</td>
						<td>:</td>
					</tr>

				</table>				 
			</div>
			
			<div style=" padding-left:10px; padding-right:10px;font-size:10px;">
				<table border="1" style="max-width:100%;">
					<tr>
						<th style="width:5%"><span>ရက္စဲြ</span><br>(Date)</th>
						<th style="width:10%"><span>ေခ်းေငြ၏ ၅% စုေငြ</span><br>Saving Amount 5%</th>
						<th style="width:20%"><span>စုေငြ၏အတိုးႏႈန္း</span><br>Interest rate 1.25%</th>
						<th style="width:15%"><span>ထုတ္ေငြ</span><br>Withdrawal</th>
						<th style="width:10%"><span>လက္က်န္</span><br>Balance</th>
						<th style="width:10%"><span>တာဝန္ခံ</span><br>Officer Certify</th>
					</tr>
					<tr>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						
					</tr>
					<tr>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						
					</tr>
<tr>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						<td style="padding:5px"> </td>
						
					</tr>					
				</table>				 
			</div>
			
			<div>
				<p style="text-align:center; padding-top:15px;" class="small-letter"> </p>
			</div>
			
		</div>
	</body>
</html>