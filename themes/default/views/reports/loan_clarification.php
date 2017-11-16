<html>
	<head>
		<title>Laon Clarification</title>
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
		.tdcol{
			border:1px solid black !important;
		}
		</style>
	</head>
	<body class="rotate">
		<div class="invoice" id="wrap" style="width: 90%; margin: 0 auto;">
			<div class="row">
				<div class="col-lg-12">
					<div class="text-center" style="margin-left:50%;">
						<h4 style="margin-left:40%;"><b>NATIONAL&nbsp;BANK&nbsp;OF&nbsp;CAMBODIA</b></h4>
						<h4 style="margin-top:-0px;padding-bottom:20px;"><b>LOANCLARIFICATION,PROVISIONING&nbsp;AND&nbsp;DELINQUENCY&nbsp;RATIO<b></h4>
					</div>	  
					<div class="col-xs-10" style="float: left;font-size:12px;padding-left:0px;margin-top:-25px;">
						<h4><b>INSTITUTION NAME : <span style="margin-left:60px;"><?php echo $setting->site_name ?></span></b></h4>
						<h5>REPORT AS AT Mar 31th, 2016 </h5>
						<br/>						
					</div>
							
				<!-- first table -->
				<div>
				<div><br/><br/></div>	
				<table style="width: 155%;">				
					<tbody style="font-size: 13px;">							
						<tr style="text-align:center;background-color:#D3D3D3 !important;" class="tdborder">	
							<td rowspan="2" style="width:30%;vertical-align:middle;">CLASSIFICATION</td>
							<td rowspan="2" style="width:10%;vertical-align:middle;">NUMBER OF LOANS</td>
							<td rowspan="2" style="width:20%;vertical-align:middle;">AMOUNT OUTSTANDING</td>							
							<td rowspan="2" style="width:10%;vertical-align:middle;">ACCGUED INTEREST</td>
							<td colspan="3" style="width:10%;vertical-align:middle;">PROVISION</td>
							
						</tr>
						<tr style="text-align:center;background-color:#D3D3D3 !important;" class="tdborder">							
							<td colspan="2" style="width:10%;vertical-align:middle;">REQUIRED</td>
							<td style="width:10%;vertical-align:middle;">ACTUAL</td>
						</tr>
						<?php
							$arr = Array(
									"ah1" => "1. LOANS OF ONE YEAR OR LESS",
									"ah2" => "2. LOANS OF MORE THAN ONE YEAR",									
								);
							$barr = Array(
									"bh1" => "1.1 STANDARD",
									"bh2" => "1.2 SUB STANDARD PAST DUE>30 DAYS",
									"bh3" => "1.3 DOUBLEFUL PAST DUE>60 DAYS",
									"bh4" => "1.3 LOSS PAST DUE>90 DAYS",
								);
							$carr = Array(
									"ch1" => "SUB-TOTAL 1",																
								);
							foreach($arr as $a){								
						?>
						<tr style="text-align:center;background-color:#D3D3D3 !important;" class="tdborder">
							<td style="width:30%;vertical-align:middle;text-align:left;"><b><?= $a ?></b></td>
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:20%;vertical-align:middle;">0000</td>							
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:10%;vertical-align:middle;">0000</td>
						</tr> <?php foreach($barr as $b){ ?>
						<tr style="text-align:center;" class="tdborder">
							<td style="width:30%;vertical-align:middle;text-align:left;"><?= $b ?></td>
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:20%;vertical-align:middle;">0000</td>							
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:10%;vertical-align:middle;">0000</td>
						</tr> <?php } ?>
						<?php foreach($carr as $c){ ?>
						<tr style="text-align:center;background-color:#D3D3D3 !important;" class="tdborder">
							<td style="width:30%;vertical-align:middle;text-align:left;"><?= $c ?></td>
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:20%;vertical-align:middle;">0000</td>							
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:10%;vertical-align:middle;">0000</td>
						</tr>
						<?php }
								
							}
						?>						
					</tbody>
                </table>				
			</div>
			<!-- Second table -->
			
			<table style="width: 155%;">				
					<tbody style="font-size:13px;">							

						<tr style="text-align:center;">	
							<td colspan="8" style="width:20%;vertical-align:middle;">&nbsp;&nbsp;</td>							
						</tr>
						<tr style="text-align:center;">	
							<td rowspan="2" style="width:25%;vertical-align:middle;text-align:left;padding-left:10px;"><b>DELIQUENCY RATIO</b></td>
							<td rowspan="2" style="width:5%;vertical-align:middle;">=</td>
							<td colspan="2" style="width:20%;vertical-align:middle;text-align:left;border-bottom:1px solid black !important;">ALL LOAN PAST DUE > 30 DAYS</td>							
							
							<td class="tdcol" style="width:10%;vertical-align:middle;">7,516.67</td>
							<td class="tdcol" rowspan="2" style="width:10%;vertical-align:middle;">3.62%</td>							
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
						</tr>
						<tr style="text-align:center;">	
							
							
							<td colspan="2" style="width:30%;vertical-align:middle;text-align:left;border-top:1px solid black !important;">TOTAL LOANS OUT STANDING</td>							
							
							<td class="tdcol" style="width:10%;vertical-align:middle;">207,720.71</td>
														
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
						</tr>
						<tr style="text-align:center;">	
							<td colspan="8" style="width:20%;vertical-align:middle;">&nbsp;&nbsp;</td>							
						</tr>
						<tr style="text-align:center;">	
							<td rowspan="2" style="width:15%;vertical-align:middle;text-align:left;padding-left:10px;"><b>LOAN WRITE OFFS:</b></td>
							<td colspan="2" rowspan="2" style="width:5%;vertical-align:middle;">CURRENR PERIOD</td>
														
							<td class="tdcol" rowspan="2" style="width:20%;vertical-align:middle;text-align:left;"></td>
							<td rowspan="2" style="width:10%;vertical-align:middle;">YEAR TO DATE</td>
							<td class="tdcol" rowspan="2" colspan="2" style="width:10%;vertical-align:middle;"></td>							
							
							<td rowspan="2" style="width:10%;vertical-align:middle;"></td>
						</tr>
						<tr style="text-align:center;">			
							
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
					<div class="" style="width:50%;border-bottom:1px solid black;margin-left:40px;">Signatures:</div>
			</div>
			<div class="col-xs-6 pull right">
					<br/>
					<br/>
					<br/>
					<br/>
					<div class="" style="width:50%;border-bottom:1px solid black;">Date:</div>
			</div>	
		</div>
	</body>
</html>