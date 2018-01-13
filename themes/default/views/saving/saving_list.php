<?php 
	//$this->erp->print_arrays( $sales->saving_amount);

?>
<style type="text/css">
		@media print {
			.phone {color:red;}
		}
	        html, body {
	            height: 100%;
	        }
		.contain-wrapper {
		width: 21cm;
		min-height: 29.7cm;
		padding: 2cm;
		margin: 1cm auto;
		border-radius: 5px;
		background: white;
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
		font-family: Zawgyi-One,'Battambang', Times New Roman;
		}
		.ch-box{
			width:15px;height:15px;border:1px solid black;display:inline-block;
		}
		.small-letter{
			font-family:Zawgyi-One,khmer os muol;font-weight:bold;font-size:12px;
		}
		.chat table{
			border-collapse:collapse;
			width: 100%;
			margin-bottom:20px;
		}
		.chat table tr td{
			border:1px solid black;
		}
		.chat tr td {
			padding:10px;
		}
		.order-num{
			font-weight:bold;
		}
		#logo img{
			width:150px;
		}
		th{
			padding: 10px;
			vertical-align:center;
			text-align: center;
		}
		span{
			font-size:13px;
		}

	</style>
</style>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <div>
				<h2 style="text-align:center;padding-top:10px;"  class="small-letter"> <b>စုေဆာင္းေငြစာရင္း</b></h2>
				<h2 style="text-align:center;" class="small-letter"> <b>Saving List</b></h2>
			</div>
        </div>
        <div class="modal-body">
			<div class="row">
				<div class="container">
					 <div style=" height:80px; padding-left:15px;font-size:12px;line-height:18px;">
						<table width="100%">
							<tr>
								<td style="width:28%;">အေကာင့္နံပါတ္ (Account Number)</td>
								<td>: <?= $sales->reference_no ?></td>
								<td style="width:28%;">ရက္စဲြ (Date)</td>
								<td>:<?= $this->erp->hrsd($sales->date) ?></td>
							</tr>
							<tr>
								<td style="width:28%;">အမည္ (Name)</td>
								<td>:<?= $customer->family_name_other ?>  <?= $customer->name_other ?></td>
								<td style="width:28%;">ေခ်းေငြ၏ ၅% စုေငြ Saving Amount <?= $sales->saving_rate * 100 ?> %</td>
								<td>:<?= $this->erp->roundUpMoney($this->erp->convertCurrency($sale_iterm->currency_code, $settings->default_currency, $sales->saving_amount), $sale_iterm->currency_code)?></td>
							</tr>
							<tr>
								<td style="width:28%;">နို္င္ငံသားမွတ္ပံုတင္အမွတ္ (NRC Number)</td>
								<td>: <?= $customer->gov_id ?></td>
								<td style="width:28%;">စုေငြ၏အတိုးႏႈန္း Interest rate </td>
								<td>:<?= $sales->saving_interest_rate * 100 ?> %</td>
							</tr>
							<tr>
								<td style="width:28%;">အေကာင့္အမ်ိဳးအစား(Account Type)</td>
								<td>:<?= $sale_iterm->product_name ?></</td>
								<td style="width:28%;"> </td>
								<td> </td>
							</tr>

						</table>				 
					</div>
					
					<div style=" padding-left:20px; padding-right:30px;font-size:13px;">
						<table border="1" width="100%">
							<tr style="background-color:#a7cce7;">
								<th style="width:5%">No</th>
								<th style="width:20%"><span>ရက္စဲြ</span><br>(Date)</th> 
								<th style="width:30%"><span>စုေငြ၏အတိုးႏႈန္း</span><br>Interest rate <?= $sales->saving_interest_rate * 100 ?> %</th> 
								<th style="width:30%"><span>လက္က်န္</span><br>Balance</th>
								<th style="width:15%"><span>တာဝန္ခံ</span><br>Officer Certify</th>
							</tr>
							<?php 
							$i = 1;
							foreach($savings as $saving){
								$saving_amount = $this->erp->convertCurrency($sale_iterm->currency_code, $settings->default_currency, $sales->saving_amount);
								$balance = ($saving->saving_interest * $i) + $sales->saving_amount ;
								 
							?>
								<tr> 
									<td style="width:5%; text-align:center;"> <?= $i ?> </td>
									<td style="width:20%; text-align:center;"> <?= $this->erp->hrsd($saving->dateline); ?></td>
									<td style="width:30%; text-align:center;"> <?= $this->erp->roundUpMoney($saving->saving_interest, $sale_iterm->currency_code) ; ?> </td>
									<td style="width:30%; text-align:center;"> <?= $this->erp->roundUpMoney($balance, $sale_iterm->currency_code) ; ?></td>
									<td style="width:15%; text-align:center;">  </td>
								</tr>
							<?php
								$i++;
							}
							?>
						</table>				 
					</div>
					
					<div>
						<p style="padding:15px 0px 0px 10px;font-size:15px;">လူၾကီးမင္း၏ စုေဆာင္းေငြနဲ ့ ပက္သတ္ေသာ အေသးစိတ္အေၾကာင္းအရာမ်ားအတြက္ ကုမၸဏီ၏ Hot Line :               ၊ ဖုန္းနံပါတ္ :                      ၊ Email :                            သို ့ ရံုးခ်ိန္အတြင္း ဆက္သြယ္ေမးျမန္းနိုင္ပါသည္။ </p>
					</div>
				</div>
			</div>
        </div>
        
		 
    </div>
</div>
<?= isset($modal_js) ?$modal_js  : ('') ?>
<script>

	 

</script>