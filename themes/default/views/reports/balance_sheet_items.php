<html>
	<head>
		<title>Balance Sheet Items</title>
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
		.break{
			page-break-before:always !important;
		}
		</style>
	</head>
	<body>
		<div class="invoice" id="wrap" style="width: 95%; margin: 0 auto;">
			<div class="row">
				<div class="col-lg-12">
					<div>
						<p style="margin-top:5px;">Form MFI 03</p>
					</div>
					<div class="text-center">
						<h3><b>National Bank of Cambodia</b></h3>
						<h4 style="padding-bottom:20px;">OFF BALANCE SHEET ITEMS</h4>
					</div>	  
					<div class="col-xs-10" style="float: left;font-size:12px;margin-top:-10px;padding-left:0px;">
						<h4><b>INSTITUTION NAME : <?php echo $setting->site_name ?></b></h4>
						<h5>REPORT AS AT Mar 31th, 2016 </h5>
					</div>
					<div><br/></div>
				<div>
                <table style="width: 100%;">				
					<tbody style="font-size: 15px;">						
						<tr style="text-align:center;background-color:#D3D3D3 !important;" class="tdborder">	
							<td style="width:46%;vertical-align:middle;height:60px;"></td>
							<td style="width:10%;vertical-align:middle;height:60px;"><b>Riels</b></td>
							<td style="width:20%;vertical-align:middle;height:60px;"><b>Other Currenies translate into Riels</b></td>
							<td style="width:24%;vertical-align:middle;height:60px;"><b>TOTAL in Riels</b></td>							
						</tr>
						<tr class="tdborder">	
								<td style="width:40%;vertical-align:bottom;text-align:left;padding-left:8px;"></td>						
								<td style="width:15%;vertical-align:bottom;text-align:right;padding-right:10px;"></td>
								<td style="width:25%;vertical-align:bottom;text-align:right;padding-right:10px;"></td>
								<td style="width:20%;vertical-align:bottom;text-align:right;padding-right:10px;"></td>							
							</tr>
						<?php
							$frow = array(
										"frow1" => "1 FINANCE COMMITMENTS",
										"frow2" => "2 GUARANEES",
										"frow3" => "3 FOREIGN CURRENCY TRANSACTIONS",
										"frow4" => "4 OTHER COMMITMENTS",
								    );
							$srow = array(
										  "srow1" => "1.1 Received from Bank",
										  "srow2" => "1.2 In favour of Banks",
										  "srow3" => "1.3 In favour of Customers",
										);						
							foreach($frow as $m){
						?>
							<tr style="background-color:#D3D3D3 !important;" class="tdborder">	
								<td style="width:45%;vertical-align:bottom;text-align:left;padding-left:8px;"><?= $m ?></td>						
								<td style="width:15%;vertical-align:bottom;text-align:right;padding-right:10px;">-</td>
								<td style="width:25%;vertical-align:bottom;text-align:right;padding-right:10px;">-</td>
								<td style="width:15%;vertical-align:bottom;text-align:right;padding-right:10px;">-</td>							
							</tr>
							<?php foreach($srow as $s) {?>
							<tr style="text-align:center;" class="tdborder">	
								<td style="width:40%;text-align:left;vertical-align:bottom;padding-left:20px;"><?= $s ?></td>						
								<td style="width:15%;text-align:right;vertical-align:bottom;padding-right:10px;">-</td>
								<td style="width:25%;text-align:right;vertical-align:bottom;padding-right:10px;">-</td>
								<td style="width:20%;text-align:right;vertical-align:bottom;padding-right:10px;">-</td>							
							</tr> <?php } ?>
							<tr class="tdborder">	
								<td style="width:20%;vertical-align:bottom;text-align:right;padding-right:10px;" colspan="4"></td>							
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
					<div class="" style="width:40%;border-bottom:1px solid black;">Signatures:</div>
        </div>
		<div class="col-md-12">
					<br/>
					<br/>
					<div class="" style="width:40%;border-bottom:1px solid black;">Date:</div>
        </div>
	</body>
</html>