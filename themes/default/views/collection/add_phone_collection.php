<?php
	$dateline = date('Y-m-d', strtotime(isset($loan->dateline) ?$loan->dateline  : ('')));
	$penalty_days = $setting->penalty_days;
	$penalty_amount = $setting->penalty_amount;
	$final_dateline = date('Y-m-d', strtotime("+".$penalty_days." days", strtotime($dateline)));
	$current_date = date('Y-m-d');
	$overdue_days = 0;
	$penalty_amount = 0;
	$total_amount = 0;
	if($final_dateline < $current_date) {
		$overdue_days = (strtotime($current_date) - strtotime($dateline))/(3600 * 24);
		$penalty_amount = $overdue_days * $setting->penalty_amount;
		$total_amount = $penalty_amount;
	}
?>
<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
		<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('add_phone_collection'); ?></h4>
        </div>
		<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("collection/insert_phone_collection");?>
        <div class="modal-body">
            <?php echo form_open('down_payment', 'id="action-form"'); ?>
			<div class="row">
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("financial_product", "financial_product"); ?>
						<?php
						$fin_pro[""] = "";
						if(is_array($finacal_products)){
						foreach ($finacal_products as $financial_product) {
							$fin_pro[$financial_product->id] = $financial_product->name;
						}}
						
						echo form_dropdown('financial_product', isset($fin_pro) ?$fin_pro  : (''),isset( $sale->customer_group) ? $sale->customer_group  : (''), 'id="financial_product" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("finacal_product") . '" disabled class="form-control input-tip select" style="width:100%;"');
						?>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("applicant", "applicant"); ?>
						<?php
						$applicant = "";
						if(is_array($customers)){
						foreach($customers as $customer) {
							$applicant[$customer->id] = $customer->family_name ." ". $customer->name;
						}}
						echo form_dropdown('applicant', isset($applicant) ?$applicant  : (''), isset($sale->customer_id) ?$sale->customer_id  : (''), 'class="form-control select" disabled id="applicant" placeholder="' . lang("select") . ' ' . lang("applicant") . '" style="width:100%" ');
						?>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("dealer", "dealer"); ?>
						<?php
						$bl[""] = "";
						if(is_array($billers)){
						foreach ($billers as $dealer) {
							$bl[$dealer->id] = $dealer->company != '-' ? $dealer->company : $dealer->name;
						}}
						echo form_dropdown('dealer',isset( $bl) ? $bl  : (''), (isset($sale->biller_id) ? $sale->biller_id : $Settings->default_biller), 'id="dealer" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("dealer") . '" disabled class="form-control input-tip select" style="width:100%;"');
						?>
					</div>
				</div>
				<?php
				$total_services_amount = 0;
				if(is_array($services)){
				foreach($services as $service) {
					$total_services_amount += isset($service->amount) ?$service->amount  : ('');
				?>
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("". $service->description ."", "services[]"); ?>
						<?php echo form_input('services[]', $this->erp->formatMoney(isset($service->amount) ?$service->amount  : ('')), 'class="form-control services" id="services" readonly '); ?>
					</div>
				</div>
				<?php 
				} }
				$total_amount += $total_services_amount + isset($loan->payment) ?$loan->payment  : ('');
				?>
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("penalty_days", "penalty_days"); ?>
						<?php echo form_input('penalty_days', isset($overdue_days) ?$overdue_days  : (''), 'class="form-control" id="penalty_days" readonly '); ?>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("penalty_amount", "penalty_amount"); ?>
						<?php echo form_input('penalty_amount', $this->erp->formatMoney(isset($penalty_amount) ?$penalty_amount  : ('')), 'class="form-control" id="penalty_amount" readonly '); ?>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("installment_amount", "installment_amount"); ?>
						<?php echo form_input('installment_amount', $this->erp->formatMoney(isset($loan->payment) ?$loan->payment  : (0)), 'class="form-control" id="installment_amount" readonly '); ?>
					</div>
				</div>
				
				<div class="col-lg-6">
					<div class="form-group">
						<?= lang("status", "status"); ?>
						<?php
						$applicant = array('' => lang(''), 
						'Already paid' => 'Already paid', 
						'Search contact number' => 'Search contact number', 		
						'Call again' => 'Call again', 
						'Waiting Lessee promise' => 'Waiting Lessee promise', 
						'Waiting Guarantor promise' => 'Waiting Guarantor promise', 
						'POS -Request visit lessee' => 'POS -Request visit lessee');
						
						echo form_dropdown('status',isset( $applicant) ? $applicant  : (''), isset($sale->customer_id) ?$sale->customer_id  : (''), 'class="form-control select" id="status" placeholder="' . lang("select") . ' ' . lang("status") . '" style="width:100%" ');
						?>
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

<script type="text/javascript">
	$(document).ready(function() {
		$('#other_amount').on('change', function() {
			var other_amount = $(this).val()-0;
			var amount = $('#h_total_amount').val()-0;
			var total_amount = other_amount + amount;
			
			$('#total_amount').val(formatMoney(total_amount));
		});
	});
</script>
<?= $modal_js ?>