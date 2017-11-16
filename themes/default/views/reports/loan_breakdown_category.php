<html>
	<head>
		<title>Loan Break down Category</title>
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
				margin-top:-170px;
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
					<div>
						<p style="margin-top:5px;">Form MFI 03</p>
					</div>
					<div class="text-center" >
						<h3 style="margin-left:40%;"><b>National&nbsp;Bank&nbsp;of&nbsp;Cambodia</b></h3>
						<h4 style="margin-left:40%;padding-bottom:20px;">LOAN&nbsp;BREAKDOWN&nbsp;BY&nbsp;CATEGORY</h4>
					</div>	  
					<div class="col-xs-10" style="float: left;font-size:12px;margin-top:-10px;padding-left:0px;">
						<h4><b>INSTITUTION NAME : <span style="margin-left:60px;"><?php echo $setting->site_name ?></span></b></h4>
						<h5>REPORT AS AT Mar 31th, 2016 </h5>
					</div>
					<div><br/></div>
				<div>
                <table style="width: 155%;">				
					<tbody style="font-size: 15px;">
						<tr style="text-align:center;font-size:12px !important;">	
							<td style="width:30%;vertical-align:middle;text-align:left;"></td>
							<td style="width:8%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:8%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:8%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:8%;vertical-align:middle;text-align:right;">In Million Riels</td>
							<td style="width:10%;vertical-align:middle;text-align:right;">4,006</td>
						</tr>
						<tr style="text-align:center;background-color:#D3D3D3 !important;" class="tdborder">	
							<td rowspan="2" style="width:30%;vertical-align:middle;"><b>TYPE OF BUSINESS</b></td>
							<td colspan="2" style="width:8%;vertical-align:middle;"><b>GROUP LOANS</b></td>
							
							<td colspan="2" style="width:8%;vertical-align:middle;"><b>INDIVIDUAL LOANS</b></td>
							
							<td colspan="2" style="width:8%;vertical-align:middle;"><b>SMALL BUSINESS</b></td>
							
							<td colspan="2" style="width:8%;vertical-align:middle;"><b>TOTAL LOANS</b></td>
							
						</tr>
						<tr style="text-align:center;background-color:#D3D3D3 !important;" class="tdborder">	
							
							<td style="width:8%;vertical-align:middle;">Number of accounts</td>
							<td style="width:10%;vertical-align:middle;">Amount</td>
							<td style="width:8%;vertical-align:middle;">Number of accounts</td>
							<td style="width:10%;vertical-align:middle;">Amount</td>
							<td style="width:8%;vertical-align:middle;">Number of Accounts</td>
							<td style="width:10%;vertical-align:middle;">Amount</td>
							<td style="width:8%;vertical-align:middle;">Number of acccounts</td>
							<td style="width:10%;vertical-align:middle;">Amount</td>
						</tr>
							
						<?php
							$frow = array(
										"frow1" => "Agriculture",
										"frow2" => "Trade and Commerce",
										"frow3" => "Services",
										"frow4" => "Transportation",
										"frow5" => "Construction",
										"frow6" => "Education",
										"frow5" => "Health and social work",
										"frow5" => "Other Categories",
								    );													
							foreach($frow as $m){
						?>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:30%;vertical-align:middle;text-align:left;"><?= $m?></td>
							<td style="width:8%;vertical-align:middle;">000000</td>
							<td style="width:10%;vertical-align:middle;">000000</td>
							<td style="width:8%;vertical-align:middle;">000000</td>
							<td style="width:10%;vertical-align:middle;">000000</td>
							<td style="width:8%;vertical-align:middle;">000000</td>
							<td style="width:10%;vertical-align:middle;">000000</td>
							<td style="width:8%;vertical-align:middle;">000000</td>
							<td style="width:10%;vertical-align:middle;">000000</td>
						</tr>						
						<?php } ?> 
						<tr style="text-align:center;font-weight:bold;" class="tdborder">	
							<td style="width:30%;vertical-align:middle;text-align:left;">Total</td>
							<td style="width:8%;vertical-align:middle;">000000</td>
							<td style="width:10%;vertical-align:middle;">000000</td>
							<td style="width:8%;vertical-align:middle;">000000</td>
							<td style="width:10%;vertical-align:middle;">000000</td>
							<td style="width:8%;vertical-align:middle;">000000</td>
							<td style="width:10%;vertical-align:middle;">000000</td>
							<td style="width:8%;vertical-align:middle;">000000</td>
							<td style="width:10%;vertical-align:middle;">000000</td>
						</tr>
						<tr style="text-align:center;font-weight:bold;" class="tdborder">	
							<td style="width:30%;vertical-align:middle;text-align:left;">INTEREST RATE CHARGED(MONTHLY)</td>
							<td style="width:8%;vertical-align:middle;">000000</td>
							<td style="width:10%;vertical-align:middle;">000000</td>
							<td style="width:8%;vertical-align:middle;">000000</td>
							<td style="width:10%;vertical-align:middle;">000000</td>
							<td style="width:8%;vertical-align:middle;">000000</td>
							<td style="width:10%;vertical-align:middle;">000000</td>
							<td style="width:8%;vertical-align:middle;">000000</td>
							<td style="width:10%;vertical-align:middle;">000000</td>
						</tr>
					</tbody>
                </table>	
			
            </div>
		
        <div class="row" style="width: 200%;" >
			<div class="col-xs-6 pull left" >
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<div class="" style="width:50%;border-bottom:1px solid black;margin-left:20px;">Signatures:</div>
			</div>
			<div class="col-xs-6 pull right">
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<div class="" style="width:50%;border-bottom:1px solid black;">Date:</div>
			</div>	
		</div>
	</body>
</html>