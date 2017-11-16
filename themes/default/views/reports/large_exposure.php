<html>
	<head>
		<title>List of Large Exposure</title>
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
						<h4 style="margin-left:40%;"><b>LIST&nbsp;OF&nbsp;LARGE&nbsp;EXPOSURES<b></h4>
						<h4 style="margin-left:40%;padding-bottom:20px;">(Loan&nbsp;to&nbsp;borrowers&nbsp;exceeding&nbsp;5%&nbsp;of&nbsp;Institutions&nbsp;Net&nbsp;Worth)</h4>
					</div>	  
					<div class="col-xs-10" style="float: left;font-size:12px;margin-top:-10px;padding-left:0px;">
						<h4><b>INSTITUTION NAME : <span style="margin-left:40px;"><?php echo $setting->site_name ?></span></b></h4>
						<br/>
						<h5>REPORT AS AT Mar 31th, 2016 </h5>
						<br/>
					</div>
					
				<div>
                <table style="width: 155%;">				
					<tbody style="font-size: 15px;">						
						<tr style="text-align:center;font-weight:bold;background-color:#D3D3D3 !important;" class="tdborder">	
							<td style="width:5%;vertical-align:middle;">No.</td>
							<td style="width:25%;vertical-align:middle;">NAME</td>
							<td style="width:15%;vertical-align:middle;">LOCATION</td>
							<td style="width:10%;vertical-align:middle;">Number of Loans</td>
							<td style="width:15%;vertical-align:middle;">Amount in Riels(Millios)</td>
							<td style="width:15%;vertical-align:middle;">Other currents translated into Riels</td>
							<td style="width:15%;vertical-align:middle;">Total in millions of Riels</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:25%;vertical-align:middle;"></td>
							<td style="width:15%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:15%;vertical-align:middle;"></td>
							<td style="width:15%;vertical-align:middle;"></td>
							<td style="width:15%;vertical-align:middle;"></td>							
						</tr>
						<?php  
							for($i=1;$i<=10;$i++){
						?>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:5%;vertical-align:middle;"><?= $i?></td>
							<td style="width:25%;vertical-align:middle;">0000</td>
							<td style="width:15%;vertical-align:middle;">0000</td>
							<td style="width:10%;vertical-align:middle;">0000</td>
							<td style="width:15%;vertical-align:middle;">0000</td>
							<td style="width:15%;vertical-align:middle;">0000</td>
							<td style="width:15%;vertical-align:middle;">0000</td>							
						</tr>	<?php } ?>
						<tr style="text-align:center;">	
							<td colspan="7" style="width:5%;vertical-align:middle;">&nbsp;</td>													
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td colspan="7" style="width:5%;vertical-align:middle;">&nbsp;</td>													
						</tr>
					</tbody>
						
                </table>	
			
            </div>
		
        <div class="col-md-12">
					<br/>
					<br/>
					<br/>
					<br/>					
					<div class="" style="width:60%;border-bottom:1px solid black;">Signatures:</div>
        </div>
		<div class="col-md-12">
					<br/>
					<br/>
					<div class="" style="width:60%;border-bottom:1px solid black;">Date:</div>
        </div>
	</body>
</html>