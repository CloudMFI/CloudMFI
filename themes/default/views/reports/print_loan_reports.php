
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
		width: 28cm;
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
				<?php if($reference_no || $customer_id) { ?>
					<div class="row" style="padding: 5px 0px;">
						<div class="col-md-4"><?= ($reference_no? ('Reference No: '. $reference_no):'') ?></div>
						<div class="col-md-4"></div>
						<div class="col-md-4"><?= ($customer_id? ('Customer: '. $customer->family_name .' '. $customer->name .' ('. $customer->family_name_other .' '. $customer->name_other .')'):'') ?></div>
					</div>
				<?php } ?>
				<?php if($user_id || $branch_id) { ?>
					<div class="row" style="padding: 5px 0px;">
						<div class="col-md-4"><?= ($co? ('C.O: '. $co->username):'') ?></div>
						<div class="col-md-4"></div>
						<div class="col-md-4"><?= ($branch? ('Branch: '. $branch->name):'') ?></div>
					</div>
				<?php } ?>
				<?php if($loan_type || $loan_term) { ?>
					<div class="row" style="padding: 5px 0px;">
						<div class="col-md-4"><?= ($loan_type? ('Loan Type: '. $loan_type):'') ?></div>
						<div class="col-md-4"></div>
						<div class="col-md-4"><?= ($loan_term? ('Loan Term: '. $loan_term):'') ?> Days</div>
					</div>
				<?php } ?>
				<?php if($start_date || $end_date) { ?>
					<div class="row" style="padding: 5px 0px 0px 0px;">
						<div class="col-md-3"><?= ($start_date? ('From Date: '. $this->erp->hrsd($start_date)):'') ?></div>
						<div class="col-md-3"><?= ($end_date? ('To Date: '. $this->erp->hrsd($end_date)):'') ?></div>
						<div class="col-md-6"></div>
					</div>
				<?php } ?>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<thead>
								<tr class="active">
									<th class="text-center" style="vertical-align: middle;"><?php echo $this->lang->line("no"); ?></th>
									<th class="text-center" style="vertical-align: middle;"><?php echo $this->lang->line("reference_no"); ?></th>
									<th class="text-center" style="vertical-align: middle;"><?php echo $this->lang->line("name_en"); ?></th>
									<th class="text-center" style="vertical-align: middle;"><?php echo $this->lang->line("name_kh"); ?></th>
									<th class="text-center" style="vertical-align: middle;"><?php echo $this->lang->line("gender"); ?></th>
									<th class="text-center" style="vertical-align: middle;"><?php echo $this->lang->line("phone"); ?></th>
									<th class="text-center" style="vertical-align: middle;"><?php echo $this->lang->line("disburse_date"); ?></th>
									<th class="text-center" style="vertical-align: middle;"><?php echo $this->lang->line("interest"); ?></th>
									<th class="text-center" style="vertical-align: middle;"><?php echo $this->lang->line("term"); ?></th>
									<th class="text-center" style="vertical-align: middle;"><?php echo $this->lang->line("co"); ?></th>
									<th class="text-center" style="vertical-align: middle;"><?php echo $this->lang->line("amount"); ?></th>
									<th class="text-center" style="vertical-align: middle;"><?php echo $this->lang->line("balance"); ?></th>
								</tr>
								</thead>
								<tbody>
									<?php
										if($sales) {
											foreach($sales as $sale) {
												echo '<tr>';
												echo '<td class="text-center">'. $sale->id .'</td>';
												echo '<td>'. $sale->reference_no .'</td>';
												echo '<td>'. $sale->customer_name_en .'</td>';
												echo '<td>'. $sale->customer_name_kh .'</td>';
												echo '<td>'. (($sale->gender == 'male')? 'Male':'Female') .'</td>';
												echo '<td>'. $sale->phone1 .'</td>';
												echo '<td>'. $this->erp->hrsd($sale->contract_date) .'</td>';
												echo '<td class="text-right">'. $this->erp->formatMoney($sale->interest) .'</td>';
												echo '<td>'. number_format($sale->term) .'</td>';
												echo '<td>'. $sale->co .'</td>';
												echo '<td class="text-right">'. $this->erp->formatMoney($sale->grand_total) .'</td>';
												echo '<td class="text-right">'. $this->erp->formatMoney($sale->balance) .'</td>';
												echo '</tr>';
											}
										}else {
											echo '<td colspan="12">'. $this->lang->line("loading_data").'</td>';
										}
									?>
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