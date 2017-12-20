<?php //$this->erp->print_arrays($branches) ?>
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
		white-space: nowrap;
	}
	@media print{
				 
				#print{
					display:none;
				}
			   
			}
</style>
<div class="box">
    <div class="box-header">
        <h2 class="blue">
			<i class="fa-fw fa fa-heart-o"></i><?= lang('disbursement_reports'); ?>
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
    </div> 
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
				<button id="print" onclick="window.print()" style="margin-bottom:15px;"><i class="fa fa-print" style="font-size:20px;"></i></button>
				
				<!---Start Search------->
				<div id="form">
					<?php echo form_open('Reports/disbursement_reports/', 'id="action-form"'); ?>
					<div class="row" style="padding:10px;">
						<div class="col-sm-4">
							<div class="form-group">
								<?= lang("start_date", "start_date"); ?>
								<?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date" '); ?>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<?= lang("end_date", "end_date"); ?>
								<?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date" ' ); ?>
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
										$bn[$branch->branch_id] = $branch->name;
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
					<B> <?= lang("disbursement_reports"); ?> </B>
				</p>
                <div>
                    <table id="QUData" class="table table-bordered table-hover table-striped table-condensed">
                        <thead>
                        <tr class="active">
                            <th style="text-align:center; width:10px;">#</th>
							<th style="text-align:center;"><?= lang('loan_reference') ?></th>
							<th style="text-align:center;"><?= lang('customer') ?></th>
							<th style="text-align:center;"><?= lang('disburse_date') ?></th>
							<!--<th style="text-align:center;"><?= lang('service') ?></th>-->
							<th style="text-align:center;"><?= lang('interest') ?></th>
							<th style="text-align:center;"><?= lang('payment_terms') ?></th>
							<th style="text-align:center;"><?= lang('disburse_amount') ?></th>
						</tr>
                        </thead>
                         <tbody>
						 <?php 
							if(is_array($branches)){
							   foreach($branches as $branch_name){
							?>
							<tr class="branch">
								<td colspan="12" class="text-left" style="font-weight:bold; font-size:19px !important; color:green;">
									<?= lang("branch"); ?>
									<i class="fa fa-angle-double-right" aria-hidden="true"></i> &nbsp;&nbsp;<?=$branch_name->name?>
								</td>
							</tr>
							 <?php 
								$credit_offier 	= $branch_name->co_id;
								foreach($credit_offier AS $user){ ?>
								
								<tr class="co">
									<td colspan="12" class="text-left" style="font-weight:bold; color: #083686;">&nbsp;&nbsp;&nbsp;&nbsp;								
										<?= lang("credit_officer"); ?>
										<i class="fa fa-angle-double-right" aria-hidden="true"></i>	 
										&nbsp;&nbsp;&nbsp;
										<?= $user->first_name.' '.$user->last_name; ?>							
									</td>
								</tr>
									<?php
									$i=1;
									$tt_disburse 	= 0;
									$principle_amt	= 0;
									$interest_amt 	= 0;
									$service_amt 	= 0;
									$penalty_amt	= 0;
									$tt_coll		= 0;
									$loans = $user->sale;				
									foreach($loans as $co_l){ 
										$frequency_cash[""] = "";
										$frequency_cash[1] = "Daily";
										$frequency_cash[7] = "Weekly";
										$frequency_cash[14] = "Two Week";
										$frequency_cash[30] = "Monthly";
										$frequency_cash[360] = "Yearly";
									?>
										<tr>
											<td style="text-align:center;"><?= $i ?></td>
											<td style="text-align:center;"><?= $co_l->reference_no; ?></td>
											<td style="text-align:center;"><?= $co_l->cus_name; ?></td>
											<td style="text-align:center;"><?= $this->erp->hrsd($co_l->disburse_date); ?></td>											
											<!--<td style="text-align:center;"> <?= $this->erp->formatDecimal($co_l->service_amount); ?> </td>-->
											<td style="text-align:center;"><?= $co_l->interest; ?></td>
											<td style="text-align:center;"><?= $frequency_cash[$co_l->frequency]; ?></td>
											<td style="text-align:center;"> <?= $this->erp->formatDecimal($co_l->disburse_amount); ?> </td>
										</tr>
									<?php 
									$i++;
									$total_disburse += $co_l->disburse_amount;
									$total_service += $co_l->service_amount;
									 
									} ?>
									<tr class="active" id="total">
										<td colspan="6" class="right" style="font-weight:bold;"><?= lang("total") ?> 
											<i class="fa fa-angle-double-right" aria-hidden="true"></i> 
										</td>
										<!--<td class="text-center"><b><?= $this->erp->formatDecimal($total_service); ?></b></td>-->
										 
										<td class="text-center"><b><?= $this->erp->formatDecimal($total_disburse); ?></b></td>
										
									</tr>
							<?php 
								}
								}
								} ?>	
							
						<?php
						$i++;	
						for($k = 0;$k<7;$k++){
						?>
						<?php $i++; }?>
						 
                       </tbody>
                    </table>
                </div>
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