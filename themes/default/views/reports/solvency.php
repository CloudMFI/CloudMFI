<html>
	<head>
		<title>Solvency</title>
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
		.tdborder td,th{
			
			height:20px;
		}
		.tdmainrow{			
			vertical-align:middle;
			text-align:left;
		}
		.tdsubrow{
			
			padding:0px;
			text-align:left;
			padding-left:50px;
		}
		.tdnextsubrow{
			
			padding:0px;
			text-align:left;
			padding-left:90px;
		}
		.colsecond{
			width:30%;
			vertical-align:middle;
			text-align:right;
			padding-right:10px;
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
						<h4><b>NATIONAL BANK OF CAMBODIA</b></h4>
						<h4 style="margin-top:20px;"><b>SOLVENCY RATIO<b></h4>
						<h4 style="margin-top:-10px;padding-bottom:20px;"><b>FOR MICROFINANCE INSTITUTIONS<b></h4>
					</div>	  
					<div class="col-xs-10" style="float: left;font-size:12px;margin-top:-10px;padding-left:0px;">
						<h4><b>INSTITUTION NAME : <?php echo $setting->site_name ?></b></h4>
						<h5>REPORT AS AT Mar 31th, 2016 </h5>
					</div>
					<div class="col-xs-5" style="float: right;">
						<div style="width:100%;border-bottom:1px solid black;margin:0px;padding:0px;">
							<p style="margin:0px;">Exchange Rate:</p>
						</div>
						<br/>
						<p style="float:right;">In Millions of Riels</p>
					</div>		
					<div class="col-xs-6" style="padding-left:0px;margin-top:60px;">
						<h5>NUMBERATOR: NET WORTH</h5>
					</div>
				</div>
			</div>	
			<div>
                <table style="width: 100%;">				
					<tbody style="font-size: 15px;">		
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdmainrow"><b>I. Sub-total A: Items to be added</b></td>
							<td class="colsecond"></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Capital or endowment</td>
							<td class="colsecond">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Reserve, other than revaluation reserves</td>
							<td class="colsecond">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Premium related to capital(share premiums)</td>
							<td class="colsecond">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Provision for general banking risks</td>
							<td class="colsecond">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Retained earnings</td>
							<td class="colsecond">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Audited net profit for the last financial year</td>
							<td class="colsecond">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Other items</td>
							<td class="colsecond">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="height:10px !important;" colspan="2"></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow"><b>Sub-total A </b></td>
							<td class="colsecond"><b>00000</b></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="height:10px !important;" colspan="2"></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdmainrow"><b>II. Sub-total B: Items to be deducted</b></td>
							<td class="colsecond"></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">For shareholders,director,manager,and their next of kin</td>
							<td class="colsecond"></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="height:10px !important;" colspan="2"></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdnextsubrow">Unpaid portion of capital</td>
							<td class="colsecond">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdnextsubrow">Avances,loans,security and the agreement</td>
							<td class="colsecond">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdnextsubrow">of the persons concerned as defined above</td>
							<td class="colsecond">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="height:10px !important;" colspan="2"></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Holding of own shares at thier book value</td>
							<td class="colsecond">0000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Accumulated losses</td>
							<td class="colsecond">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Formation expenses</td>
							<td class="colsecond">00000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Losses determined on dates</td>
							<td class="colsecond">000000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="height:10px !important;" colspan="2"></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow"><b>Sub-total B</b></td>
							<td class="colsecond"><b>00000</b></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="height:10px !important;" colspan="2"></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdmainrow"><b>III. Total C: BASE NET WORTH=A-B</b></td>
							<td class="colsecond">000000000</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="height:10px !important;" colspan="2"></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdmainrow"><b>IV. Sub-Total D: Item to be added</b></td>
							<td class="colsecond"></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Revaluation reserves</td>
							<td class="colsecond">-</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Subbordinated debt(up to 100% of base net worth)</td>
							<td class="colsecond">-</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Other items(not more than base net worth)</td>
							<td class="colsecond">-</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="height:10px !important;" colspan="2"></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow"><b>Sub-total D</b></td>
							<td class="colsecond"><b>-</b></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="height:10px !important;" colspan="2"></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdmainrow"><b>V. Sub-Total E: Item to be deducted</b></td>
							<td class="colsecond"></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Equity participation in Banking and financial institutions</td>
							<td class="colsecond">-</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Other items</td>
							<td class="colsecond">-</td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="height:10px !important;" colspan="2"></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow"><b>Sub-total E</b></td>
							<td class="colsecond"><b>000000</b></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td style="height:10px !important;" colspan="2"></td>
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdmainrow"><b>Total F:Total NET WORTH=C+D-E</b></td>
							<td class="colsecond"><b>0000000</b></td>
						</tr>
					</tbody>
					</table>
				</div>
				<!-- Secode Page for Invoice -->
			<div>
			  <div class="break"><br/><b>DENOMINATOR : RISK-WEIGHTED ASSETS</b></div>
                <div class=""><br/></div>
				<table style="width: 100%;">
					<tbody style="font-size: 15px;">
						<tr style="text-align:center;" class="tdborder">	
							<td style="width:50%;vertical-align:middle;height:60px;"></td>
							<td style="width:17%;vertical-align:middle;height:60px;"><b>Amount in Millions of Riels</b></td>
							<td style="width:13%;vertical-align:middle;height:60px;"><b>Risk Weighting</b></td>
							<td style="width:20%;vertical-align:middle;height:60px;"><b>Risk Weighted Amount</b></td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdmainrow">Zero weighting Asset</td>
							<td></td>
							<td></td>
							<td></td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Cash</td>
							<td>0000</td>
							<td>000</td>
							<td>00000</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Gold</td>
							<td>0000</td>
							<td>000</td>
							<td>00000</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Claims on the NBC</td>
							<td>0000</td>
							<td>000</td>
							<td>00000</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Assets collateralized by deposits lodge with the bank</td>
							<td>0000</td>
							<td>000</td>
							<td>00000</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Claims on or guaranteed by sovereigns related AAA to AA-</td>
							<td>0000</td>
							<td>000</td>
							<td>00000</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">							
							<td colspan="4"></td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdmainrow">20 percent weighted Asset</td>
							<td>0000</td>
							<td>000</td>
							<td>00000</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Claims on or guaranteed by sovereigns related A+ to A-</td>
							<td>0000</td>
							<td>000</td>
							<td>00000</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Claims on or guaranteed by banks or corporations rated AAA+ to AA-</td>
							<td>0000</td>
							<td>000</td>
							<td>00000</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">							
							<td colspan="4"></td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdmainrow">50 percent weighting Asset</td>
							<td>0000</td>
							<td>000</td>
							<td>00000</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Claims on guaranteed by sovereigns rated BBB+ to BBB-</td>
							<td>0000</td>
							<td>000</td>
							<td>00000</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">Claims on guaranteed by sovereigns rated A+ to A-</td>
							<td>0000</td>
							<td>000</td>
							<td>00000</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">							
							<td colspan="4"></td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdmainrow">100 percent weighting Asset</td>
							<td>0000</td>
							<td>000</td>
							<td>00000</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">All other assets</td>
							<td>0000</td>
							<td>000</td>
							<td>00000</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdsubrow">All off-balance sheet items</td>
							<td>0000</td>
							<td>000</td>
							<td>00000</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">							
							<td colspan="4"></td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdmainrow"><b>Total</b></td>
							<td><b>0000</b></td>
							<td> </td>
							<td>00000</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">							
							<td colspan="4"></td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdmainrow"><b>Total G: TOTAL RISK-WEIGHT ASSETS</b></td>
							<td></td>
							<td></td>
							<td>00000</td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">							
							<td colspan="4"></td>							
						</tr>
						<tr style="text-align:center;" class="tdborder">	
							<td class="tdmainrow"><b>SOLVENCY RATIO=TOTAL F / TOTAL G</b></td>
							<td></td>
							<td></td>
							<td style="border:1px solid black !important;">00000</td>							
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
		</div>
	</body>
</html>