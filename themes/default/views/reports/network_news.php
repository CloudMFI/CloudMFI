<!DOCTYPE html>
<html>
	<head>
		<title>Network News</title>
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
						<h4 style="margin-left:55%;">ពត៌មានបណ្តាញប្រតិបត្តិការណ៍</h4>
						<h4 style="margin-left:55%;padding-bottom:5px;">ប្រចំា ខែ មិនា ឆ្នំា ២០១៦</h4>
					</div>	  
					<div class="col-xs-10" style="float: left;font-size:12px;padding-left:0px;">
						<h5>ឈ្មោះ MFI : <span style="margin-left:5px;"><b><?php echo $setting->site_name ?></b></span></h5>
						<h5>REPORT AS AT Mar 31th, 2016 </h5>
					</div>
					
				<div>
                <table style="width: 155%;">				
					<tbody style="font-size: 12px;">
						<tr style="text-align:center;font-size:9px !important;">	
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:15%;vertical-align:middle;"></td>
							<td style="width:25%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:top;height:15px !important;">អត្រាប្តូរប្រាក់ ១ដុល្លារ =</td>
							<td style="width:15%;vertical-align:top;height:15px !important;">4,006</td>
						</tr>
						<tr style="text-align:center;background-color:#D3D3D3 !important;" class="tdborder">	
							<td style="width:5%;vertical-align:middle;">ល.រ</td>
							<td style="width:15%;vertical-align:middle;">ក្រុង / ខេត្ត</td>
							<td style="width:25%;vertical-align:middle;">ស្រុក / ខណ្ឌ</td>
							<td style="width:10%;vertical-align:middle;">សង្កាត់</td>
							<td style="width:5%;vertical-align:middle;">ភូមិ</td>
							<td colspan="3" style="width:5%;vertical-align:middle;">ចំនួនអ្នកខ្ចីប្រាក់</td>
							
							
							<td style="width:5%;vertical-align:middle;">ចំនួន​សាខា</td>
							<td style="width:5%;vertical-align:middle;">ចំនួន​ប៉ុស្តិ៏​សេវា</td>
							<td style="width:5%;vertical-align:middle;">តំបន់​ប្រតិបត្តិ</td>
							<td style="width:10%;vertical-align:middle;">សមតុល្យឥណទាន</td>
							<td style="width:15%;vertical-align:middle;"></td>
						</tr>
						<tr style="text-align:center;background-color:#D3D3D3 !important;" class="tdborder">	
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:15%;vertical-align:middle;"></td>
							<td style="width:25%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;">ប្រុស</td>
							<td style="width:5%;vertical-align:middle;">ស្រី</td>
							<td style="width:5%;vertical-align:middle;">សរុប</td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;">ទឹកប្រាក់ជា​ដុល្លារអាមេរិក</td>
							<td style="width:15%;vertical-align:middle;">&nbsp;ទឹកប្រាក់ជារៀល​(លានរៀល)&nbsp;</td>
						</tr>
							
						<?php
							$frow = array(
										"frow1" => " ភ្នំពេញ ",
										"frow2" => "កណ្តាល",										
								    );
							$srow = array(
										"srow1" => " ៧ មករា ",
										"srow2" => "ចំការមន",
										"srow3" => "ដង្កោ",
										"srow4" => "ដូនពេញ",
										"srow5" => "ទួលគោក",
										"srow6" => "មានជ័យ",	
								    );
							$a="";	
							$i=1;
							foreach($frow as $m){									
								    foreach($srow as $s){									
						?>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:5%;vertical-align:middle;text-align:left;"><?= $i?></td>
							<td style="width:15%;vertical-align:middle;text-align:left;"><?= $m?></td>							
							<td style="width:25%;vertical-align:middle;text-align:left;"><?= $s?></td>						
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:15%;vertical-align:middle;"></td>
						</tr> 
						<?php  
								$m=$a;
								$i=$a;
							}  $i++;
							?>
							
						<tr style="text-align:center;font-weight:bold;" class="tdborder">	
							<td style="width:5%;vertical-align:middle;text-align:left;">សរុប</td>
							<td style="width:15%;vertical-align:middle;"></td>
							<td style="width:25%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:5%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:15%;vertical-align:middle;"></td>
						</tr>
					<?php	} ?>
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