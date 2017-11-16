<!DOCTYPE html>
<html>
	<head>
		<title>Net Open Position</title>
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
			height:20px;
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
						<h5 style="margin-left:55%;">ការគណនាស្ថានភាពចំហរសុទ្ធនៃរូបិយប័ណ្ណ</h5>
						<h5 style="margin-left:55%;padding-bottom:5px;">Net Open Position</h5>
					</div>	  
					<div class="col-xs-6" style="float: left;font-size:12px;padding-left:0px;">
						<h6>INSTITUTION NAME : <span style="margin-left:5px;"><b><?php echo $setting->site_name ?></b></span></h6>
						<br/>
						<h6>REPORT AS AT Mar 31th, 2016 </h6>
					</div>
					<div class="col-xs-4" style="float: right;font-size:12px;margin-left:0px;padding-left:120px;">
						<h6>អត្រាប្តូរប្រាក់&nbsp;១ដុល្លារ&nbsp;(Exchange&nbsp;Rate&nbsp;1USD)=&nbsp;US$</h6>
						<h6 style="padding-left:50px;">មូលនិធិផ្ទាល់សុទ្ធ&nbsp;(Bank's&nbsp;Net&nbsp;Worth)&nbsp;<span style="padding-left:30px;">43,056,73</span></h6>
					</div>
				</div>
			</div>
				<div>
                <table style="width: 155%;">				
					<tbody style="font-size: 12px;">					
						<tr style="text-align:center;">	
							<td style="width:10%;vertical-align:top;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:15%;vertical-align:middle;"></td>
							<td style="width:15%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:top​ !important;;">គិតជាលានរៀល</td>
							<td style="width:10%;vertical-align:top !important;">In Million Riel</td>
							<td style="width:10%;vertical-align:top !important;">4006</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td rowspan="4" style="width:10%;vertical-align:top;">រូបិយប័ណ្ណ Currency</td>
							<td colspan="4" style="width:10%;vertical-align:middle;">ខ្ធង់ផ្សេងៗបន្ទាប់ពីដកចេញសំវិធានធន Element after deduction of affected provisions</td>
							
							<td style="width:15%;vertical-align:middle;">ស្ថានភាពរូបិយប័ណ្ណចំហរសុទ្ធ Net Open Position</td>
							<td rowspan="4" style="width:10%;vertical-align:top;">រូបិយប័ណ្ណចំហរសុទ / មូលនិធិផ្ទាល់សុទ្ធ (%) Net Open Position Net Worth(%)</td>
							<td rowspan="4" style="width:10%;vertical-align:top;">កំណត់(%) Limit%</td>
							<td rowspan="4" style="width:10%;vertical-align:top;">ភាពលើស(១) Excess(1)</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							
							<td style="width:10%;vertical-align:middle;">1</td>
							<td style="width:10%;vertical-align:middle;">2</td>
							<td style="width:15%;vertical-align:middle;">3</td>
							<td style="width:10%;vertical-align:middle;">4</td>
							<td style="width:15%;vertical-align:middle;">5</td>
							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							
							<td style="width:10%;vertical-align:middle;">ទ្រព្យសកម្ម Assets</td>
							<td style="width:10%;vertical-align:middle;">ទ្រព្យអកម្មនិងមូលធន Liabilities and Capital</td>
							<td style="width:15%;vertical-align:middle;">រូបិយប័ណ្ណត្រូវទទួល Currencies receivable</td>
							<td style="width:10%;vertical-align:middle;">រូបិយប័ណ្ណត្រូវចំណាយ Currencies Payable</td>
							<td style="width:15%;vertical-align:middle;">(+(វែង) ឬ - (ខ្លី)​) (long)OR=(Short)</td>
							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:10%;vertical-align:middle;"></td>
							<td style="width:15%;vertical-align:middle;">ខ្ទង់ក្រៅតារាងតុល្យការ +Off Balance Sheet+</td>
							<td style="width:10%;vertical-align:middle;">ខ្ទង់ក្រៅតារាងតុល្យការ -Off Balance Sheet-</td>
							<td style="width:15%;vertical-align:middle;">(1+2+3+4)</td>
							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:10%;vertical-align:top;">USD</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:10%;vertical-align:top;">KHR</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:10%;vertical-align:top;">EUR</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:10%;vertical-align:top;">SGD</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:10%;vertical-align:top;">HKD</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:10%;vertical-align:top;">THB</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:10%;vertical-align:top;">YPY</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:10%;vertical-align:top;">VND</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:10%;vertical-align:top;">(Grand Total)</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:10%;vertical-align:top;"></td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:15%;vertical-align:middle;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
							<td style="width:10%;vertical-align:top;">00000</td>
						</tr>
					</tbody>
                </table>			
            </div>
		<div class="col-xs-12" style="font-size:11px;"> 
			<p>(1)&nbsp;នៅពេលដែលមានភាពលើស&nbsp;គ្រឹះស្ថានធនាគារនិងហិថញ្ញវត្ថុត្រូវពន្យល់ជាលាយលក្ខណ៍អក្សរពីមួលហេតុនិងវិធានការសម្រួល</p>
			<p>(1) Where is an excess, the bank shall submit a written explanation of the origin of each excess, and the measures</p>
		</div>
		<div class="col-xs-12" style="font-size:12px;text-align:right;width:150%;">
			<p>ភ្នំពេញ ថ្ងៃទី&nbsp;&nbsp;&nbsp;ខែ&nbsp;&nbsp;&nbsp;&nbsp;ឆ្នំា&nbsp;&nbsp;&nbsp;</p>
		</div>
        <div class="row" style="padding-left:55px;font-size:11px;width: 200%;">
                <div class="col-xs-6" style="line-height:10px;">
					<p>Taken to remedy the situaltion</p>
					<br/>
                    <p>2 ចំនួនសរុបទ្រព្យសកម្មក្នុងតារាងតុល្យការ</p>
					<p>2 Total equal total assets on the balance sheet</p>
					<p>3&nbsp;ចំនួនសរុបទ្រព្យសកម្ម&nbsp;និងមូលធនក្នុងតារាងតុល្សគ្នា</p>
					<p>3 Total equal to toal liabilities assets on the balance sheet</p>
					<p>4&nbsp;ចំនួនសរុប = សូន្យ</p>
					<p>4 Total = Zero</p>
                </div>                
				<div class="col-xs-3" style="text-align:center;margin-left:-370px;">
					<p>អនុម័តដោយៈ</p>
					<p>Chief Executive Officer</p>
					<hr/>
                    <p></p>                    
                </div>
				<div class="col-xs-1 " style="text-align:center">
					<p>ត្រួតពិនិត្យដោយ</p>
					<p>Checked by</p>
					<hr/>
                    <p>&nbsp;</p>                    
                </div>
				<div class="col-xs-2 pull-left" style="padding-left:100px;text-align:center">
					<p>រៀបចំដោយ</p>
					<p>&nbsp</p>
					<hr/>
                    <p>&nbsp;</p>                    
                </div>
			</div>
		</div>
	</body>
</html>