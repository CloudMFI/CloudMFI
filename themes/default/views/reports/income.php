<html>
	<head>
		<title>INCOME AND EXPENDITURE ACCOUNT</title>
		
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
					<div class="text-center">
						<h3><b>National Bank of Cambodia</b></h3>
						<h4 style="margin-top:-5px;padding-bottom:20px;"><b>INCOME AND EXPENDITURE ACCOUNT<b></h4>
					</div>	  
					<div class="col-xs-10" style="float: left;font-size:12px;margin-top:-10px;padding-left:0px;">
						<h4><b>INSTITUTION NAME : <?php echo $setting->site_name ?></b></h4>
						<br/>
						<h5>REPORT AS AT Mar 31th, 2016 </h5>
					</div>
					<div><br/></div>
				<div>
                <table style="width: 100%;">				
					<tbody style="font-size: 15px;">
						<tr style="text-align:center;">	
							<td style="width:30%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:20%;vertical-align:middle;"></td>
							<td style="width:20%;vertical-align:middle;">In Million Riels</td>							
							<td style="width:20%;vertical-align:middle;">4,006</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td rowspan="2" style="width:30%;vertical-align:middle;"></td>
							<td colspan="2" style="width:10%;vertical-align:middle;">Current month</td>
							
							<td style="width:20%;vertical-align:middle;"></td>							
							<td rowspan="2" style="width:20%;vertical-align:middle;">Year to date in Riels</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							
							<td style="width:10%;vertical-align:middle;">Riels</td>
							<td style="width:20%;vertical-align:middle;">Other Currenies translate into Riels</td>
							<td style="width:20%;vertical-align:middle;">Total in Riels</td>							
							
						</tr>
						
						<?php
							$frow = array(
										"frow1" => "1 Income Test Income",
										"frow2" => "2 Interest Expenses",
										"frow3" => "3 Net Interest Income(3=1-2)",
										"frow4" => "4 Net Interest Income(3=1-2)",
										"frow5" => "5 Net Interest Income(3=1-2)",
								    );
							$srow = array(
										  "srow1" => "1.1 Loans and Advances",
										  "srow2" => "1.2 Accounts with Banks",
										);							
							foreach($frow as $m){
						?>
						<tr style="text-align:center;font-weight:bold;background-color:#D3D3D3 !important;" class="tdborder">	
							<td style="width:30%;vertical-align:middle;text-align:left;"><?= $m?></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:20%;vertical-align:middle;"></td>
							<td style="width:20%;vertical-align:middle;"></td>							
							<td style="width:20%;vertical-align:middle;"></td>
						</tr>
							<?php foreach($srow as $s) {?>
							<tr style="text-align:center;" class="tdborder">	
								<td style="width:30%;vertical-align:middle;text-align:left;"><?= $s?></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:20%;vertical-align:middle;"></td>
							<td style="width:20%;vertical-align:middle;"></td>							
							<td style="width:20%;vertical-align:middle;"></td>							
							</tr> 
							<?php } ?>
							<tr class="tdborder">	
								<td style="width:20%;vertical-align:bottom;text-align:right;padding-right:10px;" colspan="5"></td>							
							</tr>
						<?php } ?>				
					</tbody>
                </table>		
            </div>
		
        <div class="col-md-12" style="margin-left:40px;">
					<br/>
					<br/>
					<br/>
					<br/>
					<div class="" style="width:60%;border-bottom:1px solid black;">Signatures:</div>
        </div>
		<div class="col-md-12" style="margin-left:40px;">
					<br/>
					<br/>
					<div class="" style="width:60%;border-bottom:1px solid black;">Date:</div>
        </div>
	</body>
</html>