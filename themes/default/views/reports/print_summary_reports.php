
<!DOCTYPE html>
<html>
	<head>
		<title>Summary Reports</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style type="text/css">
        html, body {
            height: 100%;
        }
		.contain-wrapper {
		width: 21cm;
		min-height: 29.7cm;
		padding: 2cm;
		margin: 1cm auto;
		border: 1px #D3D3D3 solid;
		border-radius: 5px;
		background: white;
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
		}
		
		.ch-box{
			width:15px;height:15px;border:1px solid black;display:inline-block;
		}
		.small-letter{
			font-family:khmer os muol;font-weight:bold;font-size:12px;
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

	</style>
	</head>
	<?php 
	
		$n = count($sales);
		$clients = 0;
		$disbursement = 0;
		$loan_out_standing = 0;
		$collection = 0;
		//$this->erp->print_arrays($sales);
		if($n > 0) {
			$clients = $n;
			foreach($sales as $sale) {
				if($sale->grand_total > 0) {
					$disbursement += $sale->grand_total;
					if($sale->paid) {
						$loan_out_standing += $sale->grand_total - $sale->paid;
						$collection += $sale->paid;
					}
				}
			}
		}
	
	?>
	<body>
		<div class="contain-wrapper" style="padding:10px;">
			<div class="header" style="width:100%;float:left;">
				<div class="brand-name" style="width:40%; float:left;margin-top:20px;">
					<div class="logo" style="float:left;width:25%;">
						<img src="<?php echo base_url().'assets/uploads/logos/brand.png';?>" style="width: 100%;">
					</div>
					<div class="text" style="float:left;margin-top:10px;width:75%;">
						<p style="font-family:Khmer OS Muol;font-size:10px;">គ្រឹះស្ថាន ជ ម៉ីជីងហ្វាយនែន ភីអិលស៊ី</p>
						<p style="font-size:12px;">CHOR MEY JING Finance PLC</p>						
					</div>
				</div>
				<div class="nationality-identifier" style="width:30%; float:left;margin-top:20px;position:relative;">
					<p style="font-family:Khmer OS Muol;text-align:center;font-size:12px;"​>ព្រះរាជាណាចក្រកម្ពុជា</p>
					<p style="font-family:Khmer OS Muol;text-align:center;font-size:12px;">ជាតិ​​​ សាសនា​ ព្រះមហាក្សត្រ</p>
					<img src="<?php echo base_url().'assets/uploads/logos/line.png';?>" alt="line" style="display:block;margin: 0 auto;">
					<div style="position: absolute;left:-150px;"></div>
				</div>
				<div class="pictures" style="width:30%; float:left;margin-top:20px;"> </div>
			</div>
			<div class="phara" style="overflow:hidden;width:100%;min-height:100px;clear:both;padding-top:35px;">
				<?php if($start_date) { ?>
					<div class="row">
						<div class="col-md-3">From Date: <?= $this->erp->hrsd($start_date) ?></div>
						<div class="col-md-3">To Date: <?= $this->erp->hrsd($end_date) ?></div>
					</div>
				<?php } ?>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<thead>
									<th class="text-center">Reports</th>
									<th class="text-center">Amount</th>
								</thead>
								<tbody>
									<tr>
										<td><?=lang('clients');?></td>
										<td><?= $clients ?></td>
									</tr>
									<tr>
										<td><?=lang('disbursement');?></td>
										<td><?= $this->erp->formatMoney($disbursement) ?></td>
									</tr>
									<tr>
										<td><?=lang('loan_out_standing');?></td>
										<td><?= $this->erp->formatMoney($loan_out_standing) ?></td>
									</tr>
									<tr>
										<td><?=lang('collection');?></td>
										<td><?= $this->erp->formatMoney($collection) ?></td>
									</tr>
									<tr>
										<td><?=lang('income');?></td>
										<td><?= $this->erp->formatMoney(0) ?></td>
									</tr>
									<tr>
										<td><?=lang('expense');?></td>
										<td><?= $this->erp->formatMoney(0) ?></td>
									</tr>
									<tr>
										<td><?=lang('cash_on_hand');?></td>
										<td><?= $this->erp->formatMoney(0) ?></td>
									</tr>
									<tr>
										<td><?=lang('liability');?></td>
										<td><?= $this->erp->formatMoney(0) ?></td>
									</tr>
									<tr>
										<td><?=lang('write_off');?></td>
										<td><?= $this->erp->formatMoney(0) ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript">
	window.print(); 
</script>