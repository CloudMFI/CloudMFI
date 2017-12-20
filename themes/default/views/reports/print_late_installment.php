<?php  //$this->erp->print_arrays($df_currency); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form').hide();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
    });
</script>
<style>
	#QUData {
		overflow-x: scroll;
		max-width: 100%;
		min-height: 300px;
		display: block;
		//cursor: pointer;
		white-space: nowrap;
	}
	@media print{
				#print{
					display:none;
				}
				#form{
					display:none;
				}
	}	
</style>
<?php if ($Owner) {
    echo form_open('reports/installment_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue">
			<i class="fa-fw fa fa-heart-o"></i><?= lang('bad_loan_repayments'); ?>
        </h2>
		<div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>">
                        <i class="icon fa fa-toggle-up"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>">
                        <i class="icon fa fa-toggle-down"></i>
                    </a>
                </li>
            </ul>
        </div>
		<div class="box-icon">
            <ul class="btn-tasks">
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i
							class="icon fa fa-tasks tip" data-placement="left"
							title="<?= lang("action") ?>"></i></a>
					<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu"
						aria-labelledby="dLabel">
						<li><a href="<?= site_url('reports/print_installment') ?>"><i
									class="fa fa-building-o"></i> <?= lang('daily_repayments') ?></a>
						</li>
						<li class="divider"></li>
						<li><a href="<?= site_url('reports/print_late_installment') ?>"><i
									class="fa fa-building-o"></i> <?= lang('bad_loan_repayments') ?></a>
						</li>
						<?php if ($Owner || $Admin) {?>
							<li>
								<a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?>
								</a>
							</li>
							<li>
								<a href="#" id="pdf" data-action="export_pdf"><i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?>
								</a>
							</li>
						<?php }?>
					</ul>
				</li>
            </ul>
        </div>
    </div> 
	<?php if ($Owner || $Admin) {?>
		<div style="display: none;">
			<input type="hidden" name="form_action" value="" id="form_action"/>
			<?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
		</div>
		<?= form_close()?>
	<?php }
	?> 
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
				<button id="print" onclick="window.print()" style="margin-bottom:15px;"><i class="fa fa-print" style="font-size:20px;"></i></button>
				
				<!--<p class="introtext"><?= lang('list_results'); ?></p>-->
				<!---Start Search------->
				<div id="form">
					<?php echo form_open('Reports/print_late_installment/', 'id="action-form"'); ?>
					<div class="row" style="padding:10px;">
						<div class="col-sm-4">
							<div class="form-group">
								<?= lang("start_date", "start_date"); ?>
								<?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control date" id="start_date" '); ?>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<?= lang("end_date", "end_date"); ?>
								<?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control date" id="end_date" ' ); ?>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="user"><?= lang("c.o_name"); ?></label>
								<?php
									$us[""] = "";
									if(is_array(isset($co) ?$co  : (''))){
									foreach ($co as $co_name) {
										$us[$co_name->id] = $co_name->first_name . " " . $co_name->last_name;
									}}
									echo form_dropdown('user', isset($us) ?$us  : (''), (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("user") . '"');
								?>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="customer"><?= lang("by_branch"); ?></label>
								<?php
								$bn[""] = "";
								if(is_array(isset($branch_name) ?$branch_name  : (''))){
									foreach ($branch_name as $branch) {
										$bn[$branch->id = $branch->branch_id] = $branch->name;
									}
								}
								echo form_dropdown('branch', isset($bn) ?$bn  : (''), (isset($_POST['branch']) ? $_POST['branch'] : ""), 'class="form-control" id="branch_name" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("branch") . '"');
								?>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group" style="margin-top:30px;">
								<?php echo form_submit('submit_report', $this->lang->line("search"), 'class="btn btn-primary"'); ?>	
							</div>
						</div>
					</div>
					<?php echo form_close(); ?>
				</div>
				<!---End Search------->
				
				<div class="clearfix"></div>
					<p style="font-size:20px; text-align:center;">  
						 <B> <?= lang("bad_loan_repayments"); ?>  <?= date('d/m/Y'); ?>   </B>
					</p>
                    <table id="QUData" class="table table-bordered table-hover table-striped table-responsive">
                        <thead>
                        <tr class="active">
                            <th style="text-align:center;"><?= lang('no') ?></th>
							<th style="text-align:center;"><?= lang('reference_no') ?></th>
							<th style="text-align:center;"><?= lang('name_kh') ?></th>
							<th style="text-align:center;"><?= lang('c.o_name') ?></th>
							<th style="text-align:center;"><?= lang('branch') ?></th>
							<th style="text-align:center;"><?= lang('address') ?></th>
							<th style="text-align:center;"><?= lang('phone') ?></th>
							<th style="text-align:center;"><?= lang('payment_date') ?></th>
							<th style="text-align:center;"><?= lang('due_day') ?></th>
							<th style="text-align:center;"><?= lang('penalty') ?></th>
							<th style="text-align:center;"><?= lang('owed') ?></th>
							<!--<th style="text-align:center;"><?= lang('service') ?></th>-->
							<th style="text-align:center;"><?= lang('payments') ?></th>
							<th style="text-align:center;"><?= lang('balance') ?></th>
							<th style="text-align:center;"><?= lang('total_payments') ?></th>
							<th style="text-align:center;"><?= lang('currency') ?></th>
							<th style="text-align:center;"><?= lang('received') ?></th>
						</tr>
                        </thead>
                        <tfoot class="dtFilter">
						<?php 
						$i = 1;
						$principle_balance = 0;
						$balance = 0;
						$remaining_amt = 0;
							foreach($installment_info as $ins_info){
								$services = $ins_info->services;
								$countrow = round($ins_info->term);
								$haftterm = round($countrow/2);
								$total = $this->erp->convertCurrency($ins_info->currency_code, $setting->default_currency, $ins_info->total);
								$loan_balance = str_replace(',', '', $this->erp->roundUpMoney($ins_info->principles, $ins_info->currency_code)) + str_replace(',', '', $this->erp->roundUpMoney($ins_info->balances,$ins_info->currency_code));
								$payment_amt = str_replace(',', '', $this->erp->roundUpMoney($ins_info->principles, $ins_info->currency_code)) + str_replace(',', '', $this->erp->roundUpMoney($ins_info->interest_rate,$ins_info->currency_code));
								$amount = 0;
								$service_amount = 0;
								$service_amounts = 0;
								$total_service = 0;

								foreach($services as $service) {
									if($service->service_paid == 2){
										if($ins_info->period <= $haftterm){
											if($service->type == "Percentage"){
												$amount = ($service->charge_by == 2)? ($service->amount * $loan_balance): (($service->charge_by == 3)? ($service->amount * $payment_amt ) : $service->amount * $total ) ;
												$service_amount = $amount + ($amount * $service->tax_rate);
											}else{
												$amount = $this->erp->convertCurrency($ins_info->currency_code, $setting->default_currency, $service->amount);
												$service_amount = $amount + ($amount * $service->tax_rate);
											}
										}else{
											$service_amount = 0;
										}
										$service_amounts = $service_amount;
									}
									if($service->service_paid == 3){
										if($service->type == "Percentage"){
											$amount = ($service->charge_by == 2)? ($service->amount * $loan_balance): (($service->charge_by == 3)? ($service->amount * $payment_amt ) : $service->amount * $total ) ;
											$service_amount = $amount + ($amount * $service->tax_rate);
										}else{
											$amount = $this->erp->convertCurrency($ins_info->currency_code, $setting->default_currency, $service->amount);
											$service_amount = $amount + ($amount * $service->tax_rate);
										}
										$service_amounts = $service_amount;
									}
									if($service->service_paid == 4){
										if($ins_info->period <= $haftterm){
											if($service->type == "Percentage"){
												$amount = ( $service->amount * $total) / $haftterm;
												$service_amount = $amount + ($amount * $service->tax_rate);
											}else{
												$amount = ($this->erp->convertCurrency($ins_info->currency_code, $setting->default_currency, $service->amount)) / $haftterm;
												$service_amount = $amount + ($amount * $service->tax_rate);
											}
										} else{
											$service_amount = 0;
										}
										$service_amounts = $service_amount;
									}
									$total_service +=$service_amounts;
								}
								$total_services = str_replace(',', '', $this->erp->roundUpMoney($total_service,$ins_info->currency_code));
								
								$defualt_service = $this->erp->convertCurrency( $setting->default_currency,$ins_info->currency_code, $total_services);
								$defualt_owed = $this->erp->convertCurrency( $setting->default_currency, $ins_info->currency_code, $ins_info->owed);
								$defualt_interest = $this->erp->convertCurrency( $setting->default_currency, $ins_info->currency_code, $ins_info->interest_rate);
								$defualt_principle = $this->erp->convertCurrency( $setting->default_currency, $ins_info->currency_code, $ins_info->principles);
								$defualt_balance = $this->erp->convertCurrency( $setting->default_currency, $ins_info->currency_code, $ins_info->balances);
								$defualt_penalty = $this->erp->convertCurrency( $setting->default_currency, $ins_info->currency_code, $ins_info->penalty_amount);
								
								$payments = $ins_info->principles + $ins_info->interest_rate + $total_services;
								$installment  = $defualt_principle + $defualt_interest + $defualt_service;
								$total_inst = $ins_info->principles + $ins_info->interest_rate + $ins_info->owed + $ins_info->penalty_amount + $total_services ;
								$installment_amt = $defualt_principle + $defualt_interest + $defualt_owed + $defualt_penalty + $defualt_service;

								?>
								<tr>
									<td><?=$i?></td>
									<td><?= $ins_info->reference_no; ?></td>
									<td><?= $ins_info->customer_name_other; ?></td>
									<td><?= $ins_info->co_name; ?></td>
									<td><?= $ins_info->name; ?></td>
									<td style="text-align:left;"> <?= $ins_info->house_no;?> </td>
									<td><?= $ins_info->phone1; ?></td>
									<td><?= $this->erp->hrsd($ins_info->ins_date); ?></td>
									<td><?= $ins_info->due_days; ?></td>
									<td style="text-align:right;"><?= $this->erp->formatMoney($ins_info->penalty_amount); ?></td>
									<td style="text-align:right;"><?= $this->erp->formatMoney($ins_info->owed); ?></td>
									<!--<td style="text-align:right;"><?= $this->erp->formatMoney($total_services); ?></td>-->
									<td style="text-align:right;"><?= $this->erp->formatMoney(str_replace(',','',$this->erp->roundUpMoney($payments,$ins_info->currency_code))); ?></td>
									<td style="text-align:right;"><?= $this->erp->formatMoney(str_replace(',','',$this->erp->roundUpMoney($ins_info->balances,$ins_info->currency_code))); ?></td>
									<td style="text-align:right;"><?= $this->erp->formatMoney(str_replace(',','',$this->erp->roundUpMoney($total_inst,$ins_info->currency_code))); ?></td>
									<td><?= $ins_info->cname; ?></td>
									<td><input type="checkbox" name="received"></td>
								</tr>
								<?php
								$i++;
								
								$principle_balance +=$ins_info->principle;
								$balance +=$ins_info->balance;
								$interest +=$ins_info->interest;
								$penalty_amt +=$ins_info->penalty_amounts;
								$total_pay += $installment;
								$total_payments += $installment_amt;
								$total_owed += $defualt_owed;
								$total_balance += $defualt_balance;
								$total_penalty += $defualt_penalty;
							}
						for($k = 0;$k<7;$k++){
						?>
						<!--<tr class="blank">
							<td><?=$i?></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							
							<td style="text-align:right;">$</td>
						</tr>-->
						<?php $i++; }?>
						<tr>
							<td colspan="9" style="text-align:right; font-weight:bold;"><?= lang('sub_total') ?></td>
							<!--<td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney(str_replace(',','',$this->erp->roundUpMoney($principle_balance,$setting->default_currency)));?></td>-->
							<td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney(str_replace(',','',$this->erp->roundUpMoney($total_penalty, $setting->default_currency)));?></td>
							<td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney(str_replace(',','',$this->erp->roundUpMoney($total_owed, $setting->default_currency)));?></td>
							<td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney(str_replace(',','',$this->erp->roundUpMoney($total_pay, $setting->default_currency)));?></td>
							<td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney(str_replace(',','',$this->erp->roundUpMoney($total_balance, $setting->default_currency)));?></td>
							<td style="text-align:right; font-weight:bold; color:red"><?= $this->erp->formatMoney(str_replace(',','',$this->erp->roundUpMoney($total_payments, $setting->default_currency)));?> </td>
							<td style="font-weight:bold; text-align:left;"><?= $df_currency->name; ?></td>
							<td style="text-align:right; font-weight:bold;"></td>
							
						</tr>
						
                        </tfoot>
                    </table>
            </div>
        </div>
    </div>
</div>

<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
<?php } ?>
<script>		
	$(document).ready(function(){
			$("#excel").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url('reports/installment_actions/0/xls/')?>";
			return false;
		});
		$('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/installment_actions/pdf/?v=1'.$v)?>";
            return false;
        });
	});
</script>