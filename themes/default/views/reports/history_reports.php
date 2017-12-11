 <style type="text/css">
    
    @media print{ 
		#title {
			background-color:#ccc !important; 
		}
		
		#total {
			background-color:#ccc !important; 
		}		
	}
</style>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('customer_payments_history'); ?></h4>
        </div>
        <div class="modal-body">
			<div class="row">
				<div class="col-lg-12">
					<div class="container">
						<table width="100%" border="0">
							<tr style="text-align:center;">
								<td colspan="4"> <b> <?= lang('customer_informations') ?> </b> </td> 
							</tr>
							<tr>
								<td width="25%"> <?= lang('loan_id') ?></td>
								<td width="25%">: <?=$sales->reference_no;?></td>
								<td> <?= lang('disbursement_date') ?></td>
								<td>: <?= $this->erp->hrsd($sales->approved_date); ?></td>
							</tr>
							<tr>
								<td> <?= lang('customer_name_(eng)') ?></td>
								<td>: <?=$customer->family_name.' '.$customer->name;?> </td>
								<td width="25%"> <?= lang('loan_term') ?></td>
								<td width="25%">: <?= number_format($sales->term,0); ?>  <?= lang("days") ?></td>
							</tr>
							<tr>
								<td> <?= lang('customer_name_(other)') ?></td>
								<td>: <?php echo $customer->family_name_other.' '.$customer->name_other; ?> </td>
								<td> <?= lang('loan_frequency') ?> </td>
								<?php
									$frequency['1'] = "Daily";
									$frequency['7'] = "Weekly";
									$frequency['14'] = "Two Weeks";
									$frequency['30'] = "Monthly";
									$frequency['360'] = "Yearly";
								?>
								<td>: <?= $frequency[$sales->frequency]; ?></td>
							</tr>
							<tr>
								<td> <?= lang('phone_number') ?></td>
								<td>: <?= $customer->phone1; ?> </td>
								<td> <?= lang('currency') ?></td>
								<td>: <?= $currency->name_other; ?></td>
							</tr>
							<tr>
								<td> <?= lang('loan_principle') ?></td>
								<td>: <?= $this->erp->formatMoney($sales->total) .' '. $currency->name_other; ?></td>
								<td> <?= lang('co_name') ?></td>
								<td>:  <?= $c_o->first_name . ' ' . $c_o->last_name ; ?></td>
							</tr>
						</table>
						   
						<table width="100%" border="1" style="margin-top:30px;">
							<thead style="text-align:center;">
								<td colspan="7"> <b> <?= lang('payment_history_details') ?> </b> </td>									
							</thead>
							<thead>
							<tr style="background-color:#ccc;" id="title">
								<th  style="text-align:center;"><?= lang('no') ?></th>
								<th  style="text-align:center;"><?= lang('receive_date') ?></th>
								<th  style="text-align:center;"><?= lang('principle') ?></th>
								<th  style="text-align:center;"><?= lang('interest') ?></th>
								<th  style="text-align:center;"><?= lang('saving') ?></th>
								<th  style="text-align:center;"><?= lang('penalty') ?></th>
								<th  style="text-align:center;"><?= lang('total') ?></th>
							</tr>
							</thead>
							<body>
							<?php
							$i = 1; 
							foreach($payments as $payment) {
								$priciple = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency,$payment->principle_amount);
								$interest = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency,$payment->interest_amount);
								$service = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency,$payment->service_amount);
								$penalty = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency,$payment->penalty_amount);
								$amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency,$payment->amount);
							?>
							<tr>
								<td  style="text-align:center;"><?= $i; ?></td>
								<td  style="text-align:center;"><?= $this->erp->hrsd($payment->date); ?></td>
								<td  style="text-align:center;"><?= $this->erp->formatMoney($priciple); ?></td>
								<td  style="text-align:center;"><?= $this->erp->formatMoney($interest); ?></td>
								<td  style="text-align:center;"><?= $this->erp->formatMoney($service); ?></td>
								<td  style="text-align:center;"><?= $this->erp->formatMoney($penalty); ?></td>
								<td  style="text-align:center;"><?= $this->erp->formatMoney($amount); ?></td>
							</tr>
							<?php 
							$i++;
							$total_principle += $priciple;
							$total_interest += $interest;
							$total_service += $service;
							$total_penalty += $penalty;
							$total_amount += $amount;
							}?>
							</body>
							<tfoot style="background-color:#ccc;" id="total">
								<th colspan="2" style="text-align:center;"> <?= lang('total') ?> </th> 
								<th  style="text-align:center;"><?= $this->erp->formatMoney($total_principle); ?></th>
								<th  style="text-align:center;"><?= $this->erp->formatMoney($total_interest); ?></th>
								<th  style="text-align:center;"><?= $this->erp->formatMoney($total_service); ?></th>
								<th  style="text-align:center;"><?= $this->erp->formatMoney($total_penalty); ?></th>
								<th  style="text-align:center;"><?= $this->erp->formatMoney($total_amount); ?></th>
							</tfoot>
						</table>
						
						
					</div>
				</div>
			</div>
        </div>
        
		 
    </div>
</div>
<?= isset($modal_js) ?$modal_js  : ('') ?>
 