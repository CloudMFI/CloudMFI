<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
		<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('add_payment'); ?></h4>
        </div>
		<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("down_payment/add_payment/" . $sale->id, $attrib); ?>
        <div class="modal-body">
            <?php echo form_open('down_payment', 'id="action-form"'); ?>
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("applicant", "applicant"); ?>
						<?php
						$applicant = "";
						foreach($customers as $customer) {
							$applicant[$customer->id] = $customer->family_name ." ". $customer->name;
						}
						echo form_dropdown('applicant', $applicant, $sale->customer_id, 'class="form-control select" id="applicant" placeholder="' . lang("select") . ' ' . lang("applicant") . '" style="width:100%"');
						?>
					</div>
				</div>
				
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("dealer", "dealer"); ?>
						<?php
						$bl[""] = "";
						foreach ($billers as $dealer) {
							$bl[$dealer->id] = $dealer->company != '-' ? $dealer->company : $dealer->name;
						}
						echo form_dropdown('dealer', $bl, ($sale->biller_id ? $sale->biller_id : $Settings->default_biller), 'id="dealer" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("dealer") . '" data-bv-notempty="true" class="form-control input-tip select" style="width:100%;"');
						?>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("down_percentage", "down_percentage"); ?>
						<?php
						$percentage = "";
						foreach($down_percentages as $down_percentage) {
							$percentage[$down_percentage->amount] = $down_percentage->description;
						}
						echo form_dropdown('down_percentage', $percentage, ($sale->advance_percentage_payment ? $sale->advance_percentage_payment : ''), 'id="down_percentage" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("down_percentage") . '" data-bv-notempty="true" class="form-control input-tip select" style="width:100%;"');
						?>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("advance_payment", "advance_payment"); ?>
						<?php echo form_input('advance_payment', $this->erp->formatMoney($sale->advance_payment), 'class="form-control" id="advance_payment"'); ?>
						<input type="hidden" name="price" id="price" value="<?=$sale->grand_total?>" />
						<input type="hidden" name="sale_id" id="sale_id" value="<?=$sale->id?>" />
					</div>
				</div>
				<?php
				$total_services_amount = 0;
				if($services){
				foreach($services as $service) {
					$total_services_amount += $service->amount;
				?>
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("". $service->description ."", "services[]"); ?>
						<?php echo form_input('services[]', $this->erp->formatMoney($service->amount), 'class="form-control services" id="services" readonly '); ?>
					</div>
				</div>
				<?php 
				} }
				$total_amount = $total_services_amount + $sale->advance_payment;
				
				?>
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("other_amount", "other_amount"); ?>
						<?php echo form_input('other_amount', '0', 'class="form-control" id="other_amount"'); ?>
						<input type="hidden" name="total_services_amount" id="total_services_amount" value="<?=$total_services_amount?>" />
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("total_amount", "total_amount"); ?>
						<?php echo form_input('total_amount', $this->erp->formatMoney($total_amount), 'class="form-control" id="total_amount"'); ?>
						<input type="hidden" name="total_amount_h" id="total_amount_h" value="<?=$total_amount?>" />
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("payment_method", "pay_method"); ?>
						<?php
						$pay_method[""] = "";
						$pay_method["cash"] = "Cash";
						$pay_method["wing"] = "Wing";
						$pay_method["Visa"] = "Visa Card";
						echo form_dropdown('pay_method', $pay_method, '', 'class="form-control select" id="pay_method" placeholder="' . lang("select") . ' ' . lang("pay_method") . '" style="width:100%" data-bv-notempty="true"');
						?>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("payment_status", "payment_status"); ?>
						<?php
						$payment_status[""] = "";
						$payment_status["received"] = lang("paid");
						echo form_dropdown('payment_status', $payment_status, '', 'class="form-control select" id="payment_status" placeholder="' . lang("select") . ' ' . lang("payment_status") . '" style="width:100%" data-bv-notempty="true"');
						?>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("payment_date", "pay_date"); ?>
						<?php echo form_input('pay_date', $this->erp->hrld(date('Y-m-d H:m')), 'class="form-control datetime" id="pay_date" data-bv-notempty="true"'); ?>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("installment_date", "due_date"); ?>
						<?php echo form_input('due_date', $this->erp->hrsd(date('Y-m-d H:m')), 'class="form-control date" id="due_date"'); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
            <?php echo form_submit('add_payment', lang('submit'), 'class="btn btn-primary" id="add_payment"'); ?>
        </div>
	</div>
	<?php echo form_close(); ?>
</div>
<?= $modal_js ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#down_percentage').on('change', function() {
			var percentage = $(this).val()-0;
			var amount = $('#price').val()-0;
			var other_amount = $('#other_amount').val()-0;
			var total_services_amount = $('#total_services_amount').val()-0;
			
			var installment = amount * percentage;
			var total_amount = installment + other_amount + total_services_amount;
			
			$('#advance_payment').val(formatMoney(installment));
			$('#total_amount').val(formatMoney(total_amount));
			$('#total_amount_h').val(total_amount);
		});
		$('#other_amount').on('change', function() {
			var total_amount_h = $('#total_amount_h').val()-0;
			var other_amount = $(this).val()-0;
			
			var total_amount = total_amount_h + other_amount;
			
			$('#total_amount').val(formatMoney(total_amount));
			$('#total_amount_h').val(total_amount);			
		});
	});
</script>