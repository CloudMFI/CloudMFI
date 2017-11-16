

    <style type="text/css">
         body {
	
		/*	height: 842px;*/
        /* width: 675px; */
        /* to centre page on screen*/
        margin-left: auto;
        margin-right: auto;
            background: #FFF;
			font-family: "Times New Roman", Times, serif;
			font-size:11pt;
        }	
	
		table{
			border-collapse: collapse;
		}
	
		

		.t_c{text-align:center;}
    </style>


	<div class="container">
			<center>
				<img width="150px" height="150px" src="<?php echo base_url().'assets\uploads\logos\World_Wise_Garage.jpg' ?>"/>
				<p>
					<span class="info_addr"><?php echo $PaymentByRef->caddr; ?></span><br/>
					<span class="info_tel"><b>Tel: </b><?php echo $PaymentByRef->cphone; ?></span><br/>
					<span class="info_email">Email: <?php echo $PaymentByRef->cemail; ?> </span>
				</p>
			</center>
			<div class="head-info" style="width:50%;float:left;">
					<p><b>Payment Reference</b>: <?php echo $PaymentByRef->reference_no; ?></p>
					<p><b>Date Invoice</b>: <?php echo $PaymentByRef->date; ?></p>
					<p><b>Date Received</b>: <?php echo $PaymentByRef->date; ?></p>
			</div>
			<div class="head-info" style="width:50%;float:right;">
				<div style="float:right;">
					<p><b>Username</b>: owner</p>
					<p><b>Customer</b>:<?php echo $PaymentByRef->cname; ?></p>
				</div>	
			</div>
			<!-- END HEAD -->
			<div>
				<table class="table table-striped">
					<thead>
						<tr style="background-color:#428bca !important;color:white;">
							<th>No</th>
							<th>Description</th>
							<th>Qty</th>
							<th>Unit</th>
							<th style="padding-left:10px;padding-right:10px;">Amount </th>
						</tr>
					</thead>
					<tbody>
						<tr class="item">
						<td class="text-left">#X</td>
						<td class="text-left">XX</td>
						<td class="text-center">X</td>
						<td class="text-center">$ X</td>
						<td class="text-right">$ X</td>					
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="2">Rate : XXXX ៛ | Qty= (X)</th>
							<th class="text-right" colspan="2">Total</th>
							<th class="text-right">$ XXXX</th>
						</tr>
						
						<tr colspan="5">
							</tr></tfoot></table><table class="table table-striped">
							
								<tbody>
									<tr>
										<td width="25%" class="text-left">
											<strong>Installment Amount</strong>
										</td>
										<td><strong>$ <?= $this->erp->formatMoney($PaymentByRef->installl_payment);?></strong></td>
									</tr>
									<?php
										$total_receve = '';
										foreach($service as $ser){
											$total_receve += $ser->amount;
									?>
										<tr>
											<td width="25%" class="text-left">
												<strong><?=$ser->description;?></strong>
											</td>
											<td><strong>$ <?=$this->erp->formatMoney($ser->amount);?></strong></td>
										</tr>
									<?php
										}
									?>
									<tr>
										<td width="25%" class="text-left">
											<strong>Penalty Amount</strong>
										</td>
										<td><strong>$ <?= $this->erp->formatMoney($PaymentByRef->penalty_amount);?></strong></td>
									</tr>
									<tr>
										<td width="25%" class="text-left">
											<strong>Other Amount</strong>
										</td>
										<td><strong>$ <?= $this->erp->formatMoney($PaymentByRef->other_amount);?></strong></td>
									</tr>									
									
									<tr>
										<td width="25%" class="text-left">
											<strong>Payment Received</strong>
										</td>
										<td>
											<strong>
												$ <?= $this->erp->formatMoney($PaymentByRef->penalty_amount + $PaymentByRef->other_amount + $PaymentByRef->installl_payment + $total_receve);?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
												<?php
													$word = $PaymentByRef->penalty_amount + $PaymentByRef->other_amount + $PaymentByRef->installl_payment + $total_receve;
												?>
												( <?= $this->erp->convert_number_to_words($this->erp->formatMoney($word));?> )											
											</strong>
										</td>
									</tr>
									
									<tr>
										<td width="25%" class="text-left">
											<strong>Balance</strong>
										</td>
										<td><strong>$ X</strong></td>
									</tr>
									<tr>
										<td><strong>Paid by</strong></td>
										<td>
											<strong> : 
												<?php echo $PaymentByRef->paid_by; ?>											</strong>
										</td>
									</tr>
									<tr>
										<td>
											<strong>Noted</strong>
										</td>
										<td><strong><?php echo $PaymentByRef->note; ?></strong></td>
									</tr>
								</tbody>
							
							</table>
            </div>
		
	</div>


<?=$modal_js;?>








