<html>
	<head>
		<title>Network Information</title>
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
		hr{
			border-color: #333;
			width:100px;
			margin-top: 70px;
			
		}
		.tdborder td {
			border: 1px solid black;
			height:25px;
			}
		@media print{
			.rotate {
				width:90%;
				height:100%;
				margin-top:-150px;
				margin-left:-18%;
				-webkit-transform: rotate(90deg);
				-moz-transform: rotate(90deg);
				-o-transform: rotate(90deg);
				writing-mode: lr-tb;					
			}
		}
		</style>
	</head>
	<body class="rotate">
		<div class="invoice" id="wrap" style="width: 90%; margin: 0 auto;">
			<div class="row">
				<div class="col-lg-12">					
					<div class="text-center" >
						<h4 style="margin-left:40%;"><b>NATIONAL&nbsp;BANK&nbsp;OF&nbsp;CAMBODIA</b></h4>
						<h4 style="margin-left:40%;padding-bottom:10px;">NETWORK&nbsp;INFORMATION</h4>
					</div>	  
					<div class="col-xs-10" style="float: left;font-size:12px;padding-left:0px;">
						<h4><b>INSTITUTION NAME : <span style="margin-left:20%;"><?php echo $setting->site_name ?></span></b></h4>
						<h5>REPORT AS AT Mar 31th, 2016 </h5>
					</div>
					<div><br/></div>
				<div>
                <table style="width: 155%;">				
					<tbody style="font-size: 12px;">
						<tr style="text-align:center;">	
							<td style="width:15%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:10;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td colspan="2" style="width:5%;vertical-align:middle;">In Million Riels</td>							
							<td style="width:5%;vertical-align:middle;">4,006</td>
						</tr>
						<tr style="text-align:center;font-weight:bold;background-color:#D3D3D3 !important;" class="tdborder">	
							<td rowspan="3" style="width:15%;vertical-align:middle;">PROVINCES</td>
							<td colspan="3" style="width:5%;vertical-align:middle;">NUMBER OF</td>
							
							<td colspan="4" style="width:10;vertical-align:middle;">LOAN OUTSTANDING</td>
							
							<td colspan="4" style="width:10%;vertical-align:middle;">DEPOSIT BALANCES</td>
							
							
							<td colspan="3" rowspan="2" style="width:5%;vertical-align:middle;">NUMBER OF EMPLOYEES</td>
						</tr>
						<tr style="text-align:center;font-weight:bold;background-color:#D3D3D3 !important;" class="tdborder">	
							<td style="width:5%;vertical-align:middle;">DISTRICT</td>
							<td style="width:5%;vertical-align:middle;">COMMUNES</td>
							<td style="width:5%;vertical-align:middle;">VILLAGES</td>
							<td style="width:10;vertical-align:middle;">AMOUNT MILLION OF RIELS</td>
							<td colspan="3" style="width:5%;vertical-align:middle;">NUMBER OF BORROWERS</td>							
							<td style="width:10%;vertical-align:middle;">AMOUNT MILLIONS OF RILS</td>
							<td colspan="3" style="width:5%;vertical-align:middle;">NUMBER OF BORROWERS</td>					
						</tr>
						<tr style="text-align:center;font-weight:bold;background-color:#D3D3D3 !important;" class="tdborder">	
							
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:10;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;">MALE</td>
							<td style="width:5%;vertical-align:middle;">FEMALE</td>
							<td style="width:5%;vertical-align:middle;">TOTAL</td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;">MALE</td>
							<td style="width:5%;vertical-align:middle;">FEMALE</td>
							<td style="width:5%;vertical-align:middle;">TOTAL</td>
							<td style="width:5%;vertical-align:middle;">MALE</td>
							<td style="width:5%;vertical-align:middle;">FEMALE</td>
							<td style="width:5%;vertical-align:middle;">TOTAL</td>
						</tr>						
						<?php
							$frow = array(
										"frow1" => "BATTAMBANG",
										"frow2" => "PHNOM PENH",
										"frow3" => "KOMPONG CHAM",
										"frow4" => "TAKEO",
										"frow5" => "KOMPOT",
										"frow6" => "KOMPONG CHNNANG",
										"frow7" => "KOMPONG SPUR",										
								    );													
							foreach($frow as $m){ ?>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:15%;vertical-align:middle;text-align:left;"><?= $m?></td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:10;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
						</tr>					
						<?php } ?> 
					<tr style="text-align:center;font-weight:bold;" class="tdborder">	
							<td style="width:15%;vertical-align:middle;text-align:left;">Total</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:10;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
						</tr>	
					</tbody>
                </table>	
			
            </div>
		
        <div class="col-md-12">
					<br/>
					<br/>
					<br/>
					<br/>					
					<div class="" style="width:40%;border-bottom:1px solid black;">Signatures:</div>
        </div>
		<div class="col-md-12">
					<br/>
					<br/>
					<div class="" style="width:40%;border-bottom:1px solid black;">Date:</div>
        </div>
	</body>
</html>