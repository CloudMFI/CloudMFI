
<?php // $this->erp->print_arrays($loan); ?>
<style type="text/css">
    @media print {
        #myModal .modal-content {
            display: block !important;
        }
		#myModal .modal-content .noprint {
			display: none !important;
		}
    }
</style>
<div class="modal-dialog modal-lg" style="width:85%">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('view_payments'); ?></h4>
        </div>
        <div class="modal-body print">
            <div class="table-responsive">
                <table id="CompTable" cellpadding="0" cellspacing="0" border="0"
                       class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th style="width:10%;"> <?= $this->lang->line("payment_date"); ?> </th>
						<th style="width:7%;"> <?= $this->lang->line("term_no"); ?> </th>
                        <th style="width:10%;"> <?= $this->lang->line("reference_no"); ?> </th>
						<th style="width:10%;"> <?= $this->lang->line("payments"); ?> </th>
						<th style="width:8%;"> <?= $this->lang->line("old_owed"); ?> </th>						
						<th style="width:8%;"> <?= $this->lang->line("interests"); ?> </th>
						<th style="width:8%;"> <?= $this->lang->line("services"); ?> </th>
						<th style="width:7%;"> <?= $this->lang->line("penalty_"); ?> </th>
						<th style="width:7%;"> <?= $this->lang->line("other"); ?> </th>  
						<th style="width:8%;"> <?= $this->lang->line("principles"); ?> </th>						
						<th style="width:10%;"> <?= $this->lang->line("owed_balances"); ?> </th>
                        <th style="width:8%;"> <?= $this->lang->line("paid_by"); ?> </th>
                        <th class="noprint" style="width:10%;"> <?= $this->lang->line("actions"); ?> </th>
                    </tr>
                    </thead>
                    <tbody>
						<?php if (!empty($payments)) {
							foreach ($payments as $payment) { ?>
								<tr class="row<?= $payment->id ?>">
									<td style="text-align:center;"><?= $this->erp->hrld($payment->date); ?></td>
									<td style="text-align:center;"> <?= $payment->period; ?></td>
									<td style="text-align:center;"><?= $payment->reference_no; ?></td>
									<td style="text-align:right;"><?= $this->erp->formatMoney($this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->amount));?> </td>
									<td style="text-align:right;"><?= $this->erp->formatMoney($this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->owed_paid)); ?></td>									
									<td style="text-align:right;"><?= $this->erp->formatMoney($this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->interest_amount)); ?></td>
									<td style="text-align:right;"><?= $this->erp->formatMoney($this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->service_amount)); ?></td>
									<td style="text-align:right;"><?= $this->erp->formatMoney($this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->penalty_amount)); ?></td>
									<td style="text-align:right;"><?= $this->erp->formatMoney($this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->other_paid)); ?></td>
									<td style="text-align:right;"><?= $this->erp->formatMoney($this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->principle_amount)); ?></td>
									<td style="text-align:right;"><?= $this->erp->formatMoney($this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $payment->owed)); ?></td>
									<td style="text-align:center;"><?= lang($payment->paid_by); ?></td>
									<td class="noprint">
										<div class="text-center">
											<?php
												$print_recipt = anchor('Installment_payment/payment_voucher/'.$payment->loan_id .'/'.$payment->sale_id .'/'.$payment->id, ' <i class="fa fa-print" style="font-size:20px; color:#1E90FF;" ></i>','data-toggle="modal" data-target="#myModal2"');	
												
												if($payment->id == $last_payment->id){
													$edit_payment = anchor('Installment_payment/edit_payments/'.$payment->loan_id .'/'.$payment->sale_id .'/'.$payment->id, '<i class="fa fa-pencil-square" style="font-size:20px; color:#1E90FF;"></i>','data-toggle="modal" data-target="#myModal2"');
												}else{
													$edit_payment = '<i class="fa fa-pencil-square" style="font-size:20px; pointer-events: none; color:#6495ED;" ></i>';
												}
											?>										
											
											<?php
												if ($Owner || $Admin || $this->permission['payment-edit']){
													echo $edit_payment;
												}
												echo '&ensp;';
												
												if($Owner || $Admin || $GP['installment-payment_voucher']){
													echo $print_recipt;
												}
											?>
										</div>
									</td>
								</tr>
							<?php }
						} else {
							echo "<tr><td colspan='12'>" . lang('no_data_available') . "</td></tr>";
						} ?>
                    </tbody>
					<tfoot>
						<tr style="height:50px;">
							<td colspan="13" class="text-right">  </td>
						</tr>						
					</tfoot>
                </table>
            </div>
			
        </div>
    </div>
</div>
<?= isset($modal_js) ?$modal_js  : ('') ?>
<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {
        $(document).on('click', '.po-delete', function () {
            var id = $(this).attr('id');
            $(this).closest('tr').remove();
        });
    });
</script>
