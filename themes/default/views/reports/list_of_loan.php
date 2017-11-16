<html>
	<head>
		<title>List of Loan</title>
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
					<div class="text-center">
						<h4 style="margin-left:40%;"><b>NATIONAL&nbsp;BANK&nbsp;OF&nbsp;CAMBODIA</b></h4>
						<h4 style="margin-left:40%;padding-bottom:10px;"><b>LIST&nbsp;OF&nbsp;LOANS&nbsp;TO&nbsp;INSIDERS&nbsp;AND&nbsp;RELATED&nbsp;PARTIES</b></h4>
					</div>	  
					<div class="col-xs-10" style="float: left;font-size:12px;margin-top:-10px;padding-left:0px;">
						<h4><b>INSTITUTION NAME : <span style="margin-left:10%;"><?php echo $setting->site_name ?></b></span></h4>
						<br/>
						<h5>REPORT AS AT Mar 31th, 2016 </h5>
					</div>					
				<div>
                <table style="width: 155%;">				
					<tbody style="font-size: 15px;">						
						<tr style="text-align:center;font-weight:bold;background-color:#D3D3D3 !important;"class="tdborder">	
								<td style="width:35%;vertical-align:middle;">BORROWERS</td>						
								<td style="width:20%;vertical-align:middle;">Number of loans</td>
								<td style="width:15%;vertical-align:middle;">Amounts in Riles (Millions)</td>
								<td style="width:15%;vertical-align:middle;">Other currencies</td>
								<td style="width:15%;vertical-align:middle;">Total in million of Riels</td>	
						</tr>
								
						<?php
							$frow = array(
										"frow1" => "1. Sharedholders",
										"frow2" => "2. Managers",										
								    );
							$srow = array(
											"srow1" => "1.1 of which individuals",
											"srow2" => "1.2 of which corporations",
											"srow3" => "1.3 of which others",
										);
							foreach($frow as $m){
						?>
						<tr style="text-align:center;"class="tdborder">	
								<td style="width:35%;vertical-align:middle;text-align:left;"><?= $m?></td>						
								<td style="width:20%;vertical-align:middle;">00000</td>
								<td style="width:15%;vertical-align:middle;">00000</td>
								<td style="width:15%;vertical-align:middle;">00000</td>
								<td style="width:15%;vertical-align:middle;">00000</td>	
						</tr> <?php foreach($srow as $s) { ?>
						<tr style="text-align:center;"class="tdborder">	
								<td style="width:35%;vertical-align:middle;text-align:left;padding-left:20px;"><?= $s?></td>						
								<td style="width:20%;vertical-align:middle;">00000</td>
								<td style="width:15%;vertical-align:middle;">00000</td>
								<td style="width:15%;vertical-align:middle;">00000</td>
								<td style="width:15%;vertical-align:middle;">00000</td>	
						</tr> <?php } ?>
						<tr style="text-align:center;"class="tdborder">	
								
								<td colspan="5" style="width:15%;vertical-align:middle;"></td>	
						</tr>
							<?php }?>
						<tr style="text-align:center;font-weight:bold;background-color:#D3D3D3 !important;"class="tdborder">	
								<td style="width:35%;vertical-align:middle;text-align:left;padding-left:20px;">Total</td>						
								<td style="width:20%;vertical-align:middle;">00000</td>
								<td style="width:15%;vertical-align:middle;">00000</td>
								<td style="width:15%;vertical-align:middle;">00000</td>
								<td style="width:15%;vertical-align:middle;">00000</td>	
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