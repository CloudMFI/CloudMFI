<html>
	<head>
		<title>Currency Explosure</title>
		
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
						<h3 style="margin-top:-7px;">ការគិតរូបិយប័ណ្ណចំហរ</h3>
						<h4 style="margin-top:15px;padding-bottom:20px;">CALCULATION OF FOREIGN CURRENCY EXPLOSURE</h4>
					</div>	  
					<div class="col-xs-10" style="float: left;font-size:12px;margin-top:-10px;padding-left:0px;">
						<h4><b>INSTITUTION NAME : <?php echo $setting->site_name ?></b></h4>						
						<h5>REPORT AS AT Mar 31th, 2016 </h5>
					</div>
					<div><br/></div>
				<div>
                <table style="width: 100%;">				
					<tbody style="font-size: 15px;">
						<tr>
							<td></td>
							<td></td>
							<td style="padding-left:10px;"></td>
							
							<td colspan="2" style="text-align:center;padding-right:10px;">In Million Riel</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:40%;vertical-align:middle;"></td>
							<td style="width:15%;vertical-align:middle;"><b>RIELS</b></td>
							<td style="width:15%;vertical-align:middle;"><b>USD</b></td>
							<td style="width:15%;vertical-align:middle;"><b>USD</b></td>
							<td style="width:15%;vertical-align:middle;"><b>OTHER</b></td>
						</tr>
						
						<?php
							$frow = array(
										"frow1" => "1 .Assets in Foreign Currency",
										"frow2" => "2 .Minus:Liabilities in that Currency",
										"frow3" => "3 .Net Position(Long or Short)",
										"frow4" => "4 .Minus Provision for FX Losses",
										"frow5" => "5 .Adjusted Net Position(Long or Short)",
										"frow6" => "6 .Neth Worth",
								    );										
							foreach($frow as $m){
						?>
						<tr style="text-align:center;background-color:#D3D3D3 !important;" class="tdborder">	
							<td style="width:40%;vertical-align:middle;text-align:left;"><?= $m ?></td>
							<td style="width:15%;vertical-align:middle;text-align:center;">0000</td>
							<td style="width:15%;vertical-align:middle;text-align:center;">0000</td>
							<td style="width:15%;vertical-align:middle;text-align:center;">0000</td>
							<td style="width:15%;vertical-align:middle;text-align:center;">0000</td>
						</tr>						
							<tr class="tdborder">	
								<td style="width:20%;vertical-align:bottom;text-align:right;padding-right:10px;" colspan="5"></td>							
							</tr>
						<?php } ?>
						<tr style="text-align:center;background-color:#D3D3D3 !important;" class="tdborder">	
							<td style="width:40%;vertical-align:middle;text-align:left;">7 .Foreign Currency Exposure Ratio:5/6</td>
							<td style="width:15%;vertical-align:middle;text-align:center;">0000</td>
							<td style="width:15%;vertical-align:middle;text-align:center;">0000</td>
							<td style="width:15%;vertical-align:middle;text-align:center;">0000</td>
							<td style="width:15%;vertical-align:middle;text-align:center;">0000</td>
						</tr>		
						<tr class="tdborder">	
								<td style="width:20%;vertical-align:bottom;text-align:right;padding-right:10px;border-left:0px;border-right:0px;" colspan="5"></td>							
						</tr>
						<tr class="tdborder">	
								<td style="width:20%;vertical-align:bottom;text-align:right;padding-right:10px;" colspan="5"></td>							
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