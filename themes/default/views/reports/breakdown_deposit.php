<html>
	<head>
		<title>Breakdown of Deposits</title>
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
					<div class="text-center">
						<h4 style="margin-left:40%;"><b>NATIONAL&nbsp;BANK&nbsp;OF&nbsp;CAMBODIA</b></h4>
						<h4 style="margin-left:40%;padding-bottom:10px;"><b>LIST&nbsp;OF&nbsp;LOANS&nbsp;TO&nbsp;INSIDERS&nbsp;AND&nbsp;RELATED&nbsp;PARTIES</b></h4>
					</div>	  
					<div class="col-xs-10" style="float: left;font-size:12px;margin-top:-10px;padding-left:0px;">
						<h4><b>INSTITUTION NAME : <span style="margin-left:10%;">GL FINANCE PLC</b></span></h4>
						<br/>
						<h5>REPORT AS AT Mar 31th, 2016 </h5>
					</div>					
				<div>
                <table style="width: 155%;">				
					<tbody style="font-size: 15px;">						
						<tr style="text-align:center;font-weight:bold;background-color:#D3D3D3 !important;"class="tdborder">	
								<td rowspan="2" style="width:23%;vertical-align:middle;">CATEGORY</td>						
								<td rowspan="2" style="width:15%;vertical-align:middle;">INTEREST RATE PAID/FREQUENCY</td>
								<td colspan="2" style="width:8%;vertical-align:middle;">LESS THAN 250,000 Riels</td>
								
								<td colspan="2" style="width:8%;vertical-align:middle;">250,000 to 1,000,000 Riels</td>
														
								<td colspan="2" style="width:8%;vertical-align:middle;">MORE THAN 1,000,000 Riels</td>
								
								<td colspan="2" style="width:8%;vertical-align:middle;">TOTAL</td>
								
						</tr>
						<tr style="text-align:center;" class="tdborder">	
														
								
								<td style="width:8%;vertical-align:middle;">Number of accounts</td>
								<td style="width:8%;vertical-align:middle;">Amount</td>
								<td style="width:8%;vertical-align:middle;">Number of account</td>
								<td style="width:8%;vertical-align:middle;">Amount</td>						
								<td style="width:8%;vertical-align:middle;">Number of account</td>
								<td style="width:8%;vertical-align:middle;">Amount</td>
								<td style="width:8%;vertical-align:middle;">Number of account</td>
								<td style="width:8%;vertical-align:middle;">Amount</td>
						</tr>							
						<?php
							$frow = array(
										"frow1" => "1. VOLUNTARY",
										"frow2" => "2. COMPULSORY",											
								    );
							$srow = array(
											"srow1" => "1.1-DEMAND",
											"srow2" => "1.2-SAVINGS",
											"srow3" => "1.3-TERM",
										);
							foreach($frow as $m){
						?>
						<tr style="text-align:center;font-weight:bold;background-color:#D3D3D3 !important;"class="tdborder">	
								<td style="width:23%;vertical-align:middle;text-align:left;"><?= $m?></td>						
								<td style="width:15%;vertical-align:middle;">0000</td>
								<td style="width:8%;vertical-align:middle;">0000</td>
								<td style="width:8%;vertical-align:middle;">0000</td>
								<td style="width:8%;vertical-align:middle;">0000</td>
								<td style="width:8%;vertical-align:middle;">0000</td>						
								<td style="width:8%;vertical-align:middle;">0000</td>
								<td style="width:8%;vertical-align:middle;">0000</td>
								<td style="width:8%;vertical-align:middle;">0000</td>
								<td style="width:8%;vertical-align:middle;">0000</td>
						</tr> <?php foreach($srow as $s) { ?>
						<tr style="text-align:center;"class="tdborder">	
								<td style="width:23%;vertical-align:middle;text-align:left;padding-left:20px;"><?= $s?></td>						
								<td style="width:15%;vertical-align:middle;">0000</td>
								<td style="width:8%;vertical-align:middle;">0000</td>
								<td style="width:8%;vertical-align:middle;">0000</td>
								<td style="width:8%;vertical-align:middle;">0000</td>
								<td style="width:8%;vertical-align:middle;">0000</td>						
								<td style="width:8%;vertical-align:middle;">0000</td>
								<td style="width:8%;vertical-align:middle;">0000</td>
								<td style="width:8%;vertical-align:middle;">0000</td>
								<td style="width:8%;vertical-align:middle;">0000</td>
						</tr> <?php } ?>
						
						<?php }?>
						<tr style="text-align:center;">	
							<td colspan="8" style="width:23%;vertical-align:middle;">&nbsp;</td>								
						</tr>
						<tr style="text-align:center;">	
								<td style="width:23%;vertical-align:middle;text-align:left;">TOTAL RESERVABLE DEPOSITS</td>						
								<td style="width:15%;vertical-align:middle;border:1px solid black !important;text-align:right;">0</td>
								<td style="width:8%;vertical-align:middle;"></td>
								<td style="width:8%;vertical-align:middle;"></td>
								<td colspan="3" style="width:8%;vertical-align:middle;">5% RESERVE REQUIREMENT</td>
								
								<td style="width:8%;vertical-align:middle;"></td>
								<td colspan="2" style="width:8%;vertical-align:middle;border:1px solid black !important;text-align:right;">0</td>
								
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
					<div class="" style="width:70%;border-bottom:1px solid black;margin-left:20px;">Signatures:</div>
			</div>
			<div class="col-xs-6 pull right">
					<br/>
					<br/>
					<br/>
					<br/>
					<br/>
					<div class="" style="width:55%;border-bottom:1px solid black;">Date:</div>
			</div>	
		</div>
	</body>
</html>