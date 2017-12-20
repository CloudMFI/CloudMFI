<?php //$this->erp->print_arrays($StaffPerformanceInfo) ?>
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
				#tb tr th{
					background-color: #DCDCDC !important;
				}
				#body{
					width:1000px;
					height:100%;
					margin:0 auto;
					background:#fff !important;
				}
				#print{
					display:none;
				}
				#form{
					display:none;
				}
				#foot{
					width:100%;
					background:#fff !important;
				}	
				.fon{
					color: rgba(0, 0, 0, 0.3) !important;
				}
				.left_ch{
					 left: 80px !important;
				}
			}
</style>
<div class="box">
    <div class="box-header">
        <h2 class="blue">
			<i class="fa-fw fa fa-heart-o"></i><?= lang('staff_performance'); ?>
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
					<?php echo form_open('Reports/staff_performance/', 'id="action-form"'); ?>
					<div class="row" style="padding:10px;">
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
					<B> <?= lang("staff_performance"); ?> </B>
				</p>
                <div class="table-responsive">
                    <table id="QUData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr class="active">
                            <th style="text-align:center;"><?= lang('account') ?></th>
							<th style="text-align:center;"><?= lang('no_client') ?></th>
							<th style="text-align:center;"><?= lang('loan_outstanding') ?></th>
							<th style="text-align:center;"><?= lang('client_disburse') ?></th>
							<th style="text-align:center;"><?= lang('loan_disburse') ?></th>
							<th style="text-align:center;"><?= lang('principle_collection') ?></th>
							<th style="text-align:center;"><?= lang('interest_collection') ?></th>
							<th style="text-align:center;"><?= lang('service_fee') ?></th>
							<th style="text-align:center;"><?= lang('penalty_collection') ?></th>
							<!--<th style="text-align:center;" colspan="2"><?= lang('10_d_collection') ?></th>
							<th style="text-align:center;"><?= lang('30_d_collection') ?></th>
							<th style="text-align:center;"><?= lang('60_d_collection') ?></th>-->
							<th style="text-align:center;"><?= lang('total_collection') ?></th>
							<th style="text-align:center;"><?= lang('par%') ?></th>
						</tr>
                        </thead>
                        <tfoot class="dtFilter">
						<?php 
						$i = 1;
						$client = 0;
						$disburse = 0;
						$remaining_amt = 0;
							foreach($StaffPerformanceInfo as $s_info){
								
								$tt_collection = $s_info->outstanding + $s_info->loan_disburse + $s_info->principles + $s_info->interest + $s_info->service_fee + $s_info->penalty;
								
								?>
								<tr>
									<td><?= $s_info->account; ?></td>
									<td style="text-align:right;"><?= $s_info->no_client; ?></td>
									<td style="text-align:right;"><?= $this->erp->formatDecimal($s_info->outstanding); ?></td>
									<td style="text-align:right;"><?= $s_info->client_disburse; ?></td>
									<td style="text-align:right;"><?= $this->erp->formatDecimal($s_info->loan_disburse);?></td>
									<td style="text-align:right;"><?= $this->erp->formatDecimal($s_info->principles); ?></td>
									<td style="text-align:right;"><?= $this->erp->formatDecimal($s_info->interest); ?></td>
									<td style="text-align:right;"><?= $this->erp->formatDecimal($s_info->service_fee); ?></td>
									<td style="text-align:right;"><?= $this->erp->formatDecimal($s_info->penalty); ?></td>
									<!--<td></td>
									<td></td>
									<td></td>
									<td></td>-->
									<td style="text-align:right;"><?= $this->erp->formatDecimal($tt_collection); ?></td>
									<td></td>
								</tr>
								<?php
								$i++;
								
								$client 	   += $s_info->no_client;
								$outstanding   += $s_info->outstanding;
								$c_disburse    += $s_info->client_disburse;
								$loan_disburse += $s_info->loan_disburse;
								$principle     += $s_info->principles;
								$interest 	   += $s_info->interest;
								$service_fee   += $s_info->service_fee;
								$penalty       += $s_info->penalty;
								$tt_coll 	   += $tt_collection;
							}
						for($k = 0;$k<7;$k++){
						?>
						<?php $i++; }?>
						<tr>
							<td style="text-align:right; font-weight:bold;"><?= lang('total') ?></td>
							<td style="text-align:right; font-weight:bold;"><?= $client;?></td>
							<td style="text-align:right; font-weight:bold;"><?= $this->erp->formatDecimal($outstanding);?></td>
							<td style="text-align:right; font-weight:bold;"><?= $c_disburse;?></td>
							<td style="text-align:right; font-weight:bold;"><?= $this->erp->formatDecimal($loan_disburse);?></td>
							<td style="text-align:right; font-weight:bold;"><?= $this->erp->formatDecimal($principle);?></td>
							<td style="text-align:right; font-weight:bold;"><?= $this->erp->formatDecimal($interest);?></td>
							<td style="text-align:right; font-weight:bold;"><?= $this->erp->formatDecimal($service_fee);?></td>
							<td style="text-align:right; font-weight:bold;"><?= $this->erp->formatDecimal($penalty);?></td>
							<!--<td></td>
							<td></td>
							<td></td>
							<td></td>-->
							<td style="text-align:right; font-weight:bold; color:red"><?= $this->erp->formatDecimal($tt_coll);?></td>
							<td></td>
						</tr>
                        </tfoot>
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