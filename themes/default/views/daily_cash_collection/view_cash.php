 <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?= lang('daily_cash_collection')?></h4>
		</div>
		<div class="modal-body">
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
					<table class="table table-striped table-bordered">
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
							<?php
								$i = 1;
								$qty = '';
								foreach($product as $pro){
									$qty += $pro->quantity;
									$amount = $pro->quantity * $pro->unit_price;
									$total += $amount; 
							?>
							<tr class="item">
								<td class="text-left">#<?=$i?></td>
								<td class="text-left"><?=$pro->product_name;?></td>
								<td class="text-center">
									<?=$this->erp->formatQuantity($pro->quantity);?>
								</td>
								<td class="text-center">$ 
									<?=$this->erp->formatMoney($pro->unit_price);?>
								</td>
								<td class="text-right">$ 
									<?=$this->erp->formatMoney($amount)?>
								</td>		
							</tr>
							<?php
								$i++;
								}
							?>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="2">Rate : <?=$this->erp->formatMoney($exchange_rate_kh_c->rate)?> ? | Qty= (<?=$qty?>)</th>
								<th class="text-right" colspan="2">Total</th>
								<th class="text-right">$ <?=$this->erp->formatMoney($total)?></th>
							</tr>
							
							<tr colspan="5"></tr>
						</tfoot>
					</table>
					<table class="table table-striped">
					
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
										$ <?= $PaymentByRef->total_paid;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
										<?php
											$word = $PaymentByRef->penalty_amount + $PaymentByRef->other_amount + $PaymentByRef->installl_payment + $total_receve;
										?>
										( <?= $this->erp->convert_number_to_words($this->erp->formatMoney($PaymentByRef->total_paid));?> )											
									</strong>
								</td>
							</tr>
							
							<tr>
								<td width="25%" class="text-left">
									<strong>Balance</strong>
								</td>
								<td><strong>$ <?= $this->erp->formatMoney($PaymentByRef->total_paid - $word);?></strong></td>
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
		</div>
    </div>

</div>