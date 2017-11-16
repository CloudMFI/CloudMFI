<html>
	<head>
		<title>Source of financing</title>
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
				margin-top:-200px;
				margin-left:-15%;
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
						<h3><b>National Bank of Cambodia</b></h3>
						<h4 style="margin-top:-5px;padding-bottom:20px;"><b>SOURCE OF FINANCING<b></h4>
					</div>	  
					<div class="col-xs-10" style="float: left;font-size:12px;margin-top:-30px;padding-left:0px;">
						<h4><b>INSTITUTION NAME : <span style="padding-left:40px;"><?php echo $setting->site_name ?></span></b></h4>
						<h5>REPORT AS AT Mar 31th, 2016 </h5>
					</div>
					<div><br/></div>
				<div >
                <table style="width: 163%;" >				
					<tbody style="font-size: 13px;">
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>							
							<td style="text-align:right;padding-right:10px;">4,006</td>
							<td style="text-align:right;"></td>
						</tr>						
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:5%;vertical-align:middle;">No</td>
							<td style="width:20%;vertical-align:middle;">Name of Creditor</td>
							<td style="width:10%;vertical-align:middle;">Amount Approved</td>
							<td style="width:10%;vertical-align:middle;">Amount Disbursed</td>
							
							<td colspan="2" style="width:8%;vertical-align:middle;">Conditions</td>
							<td style="width:6%;vertical-align:middle;">Request of Date</td>
							
							<td colspan="5" style="width:5%;vertical-align:middle;">Payment</td>	
							<td rowspan="2" style="width:28%;vertical-align:middle;">Balance Loan Oustanding</td>
						</tr>						
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:20%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;">Interest Rate</td>
							<td style="width:8%;vertical-align:middle;">Term of Playment</td>
							<td style="width:6%;vertical-align:middle;">Request of Date</td>
							<td style="width:6%;vertical-align:middle;">Maturity Date</td>							
							
							<td colspan="2" style="width:5%;vertical-align:middle;">Principle</td>
							
							<td colspan="2" style="width:5%;vertical-align:middle;">Interest</td>	
							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:20%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:8%;vertical-align:middle;"></td>
							<td style="width:6%;vertical-align:middle;"></td>
							<td style="width:6%;vertical-align:middle;"></td>							
							<td style="width:5%;vertical-align:middle;">Current</td>
							<td style="width:5%;vertical-align:middle;">Accumulated</td>
							<td style="width:5%;vertical-align:middle;">Current</td>
							<td style="width:5%;vertical-align:middle;">Accumulated</td>	
							<td style="width:28%;vertical-align:middle;"></td>
						</tr>
						<?php
							$arr = Array(
									"sh1" => "Group Lease Holding PTE",
									"sh2" => "ABA Bank",
									"sh3" => "CPMI",
									"sh4" => "Responsibility",
									"sh5" => "Group Lease Holding PTE",
									"sh6" => "Group Lease Holding PTE",
									"sh7" => "Group Lease Holding PTE",
								 );
							foreach($arr as $m){
						?>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:5%;vertical-align:middle;">0001</td>
							<td style="width:20%;vertical-align:middle;"><?= $m ?></td>
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;">0000</td>
							<td style="width:8%;vertical-align:middle;">1 year</td>
							<td style="width:6%;vertical-align:middle;">10-Mar-17</td>
							<td style="width:6%;vertical-align:middle;">10-Mar-17</td>							
							<td style="width:5%;vertical-align:middle;">00000</td>
							<td style="width:5%;vertical-align:middle;">00000</td>
							<td style="width:5%;vertical-align:middle;">00000</td>
							<td style="width:5%;vertical-align:middle;">00000</td>	
							<td style="width:28%;vertical-align:middle;">000000</td>
						</tr>
						<?php } ?>
						<tr style="text-align:center;font-weight: bold;" class="tdborder">							
							<td colspan="2" style="width:20%;vertical-align:middle;text-align:left;">Total</td>
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:8%;vertical-align:middle;"></td>
							<td style="width:6%;vertical-align:middle;"></td>
							<td style="width:6%;vertical-align:middle;"></td>							
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;">00000</td>
							<td style="width:5%;vertical-align:middle;">00000</td>
							<td style="width:5%;vertical-align:middle;">00000</td>	
							<td style="width:28%;vertical-align:middle;">000000</td>
						</tr>
					</tbody>
                </table>					
            </div>
		<div><br/><br/><br/><br/></div>
		<div class="row" style="width: 200%;" >
			<div class="col-xs-6 pull left" >
					<br/>
					<br/>					
					<div class="" style="width:60%;border-bottom:1px solid black;margin-left:40px;">Signatures:</div>
			</div>
			<div class="col-xs-6 pull right">
					<br/>
					<br/>					
					<div class="" style="width:60%;border-bottom:1px solid black;">Date:</div>
			</div>	
		</div>
	</body>
</html>