<html>
	<head>
		<title>Deposit Break down Currency</title>
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
						<h4 style="margin-left:40%;padding-bottom:20px;">DEPOSIT&nbsp;BREAKDOWN&nbsp;BY&nbsp;CURRENCY</h4>
					</div>	  
					<div class="col-xs-10" style="float: left;font-size:12px;margin-top:-10px;padding-left:0px;">
						<h4><b>INSTITUTION NAME : <span style="margin-left:60px;"><?php echo $setting->site_name ?></span></b></h4>
						<br/>
						<h5>REPORT AS AT Mar 31th, 2016 </h5>
						<br/>
					</div>
					
				<div>
                <table style="width: 155%;">				
					<tbody style="font-size: 15px;">						
						<tr style="text-align:center;background-color:#D3D3D3 !important;" class="tdborder">	
							<td style="width:35%;vertical-align:middle;"><b>CURRENCY</b></td>
							<td style="width:15%;vertical-align:middle;"><b># OF ACCOUNTS</b></td>
							<td style="width:10%;vertical-align:middle;"><b>BALANCE OUTSTANDINGS</b></td>
							<td style="width:15%;vertical-align:middle;"><b>BALANCE OUTSTANDING (convert into Riels)</b></td>
							<td style="width:15%;vertical-align:middle;"><b>INTEREST RATE PAID (indicate whether per month, per year or otherwise)</b></td>
							<td style="width:10%;vertical-align:middle;"><b>REMARKS</b></td>							
						</tr>						
						<?php
							$frow = array(
										"frow1" => "1-KHMER RIELS",
										"frow2" => "2-US DOLLARS",
										"frow3" => "3-THAI BAHTS",
										"frow4" => "4-OTHER CURRENCY",
										"frow5" => "5-TOTAL DEPOSIT",
								    );													
							foreach($frow as $m){
						?>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:35%;vertical-align:middle;text-align:left;"><?= $m?></td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>							
						</tr>						
						<?php } ?>				
					</tbody>
                </table>	
			
            </div>
		
        <div class="col-md-12">
					<br/>
					<br/>
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