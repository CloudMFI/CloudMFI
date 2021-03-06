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
			<i class="fa-fw fa fa-heart-o"></i><?= lang('co_reports'); ?>
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
					<?php echo form_open('Reports/co_reports/', 'id="action-form"'); ?>
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
					<B> <?= lang("co_reports"); ?> </B>
				</p>
                <div>
                    <table id="QUData" class="table table-bordered table-hover table-striped table-condensed">
                        <thead>
                        <tr class="active">
                            <th style="text-align:center; width:10px;">#</th>
							<th style="text-align:center;"><?= lang('customer') ?></th>
							<th style="text-align:center;"><?= lang('submit_date') ?></th>
							<th style="text-align:center;"><?= lang('approved_date') ?></th>
							<th style="text-align:center;"><?= lang('l_disburse') ?></th>
							<th style="text-align:center;"><?= lang('interest') ?></th>
							<th style="text-align:center;"><?= lang('term') ?></th>
							<th style="text-align:center;"><?= lang('principle_collection') ?></th>
							<th style="text-align:center;"><?= lang('interest_collection') ?></th>
							<th style="text-align:center;"><?= lang('service_fee') ?></th>
							<th style="text-align:center;"><?= lang('penalty_collection') ?></th>
							<th style="text-align:center;"><?= lang('total_collection') ?></th>
						</tr>
                        </thead>
                         <tbody>
						 <?php 
							if(is_array($branches)){
							   foreach($branches as $branch_name){
							?>
							<tr>
								<td colspan="12" class="text-left" style="font-weight:bold; font-size:19px !important; color:green;">
									<?= lang("branch"); ?>
									<i class="fa fa-angle-double-right" aria-hidden="true"></i>
									&nbsp;&nbsp;<?=$branch_name->name?>
								</td>
							</tr>
							 <?php 
								$credit_offier 	= $branch_name->co_id;
								foreach($credit_offier AS $user){ ?>
								
								<tr>
									<td colspan="12" class="text-left" style="font-weight:bold; color:#083686;">&nbsp;&nbsp;&nbsp;&nbsp; 								
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
									$total_collection = $co_l->principle_collection + $co_l->interest_collection + $co_l->service_collection + $co_l->penalty_collection;
									?>
										<tr>
											<td style="text-align:right;"><?= $i ?></td>
											<td style="text-align:left;"><?= $co_l->cus_name; ?></td>
											<td style="text-align:left;"><?= $this->erp->hrsd($co_l->date); ?></td>
											<td style="text-align:left;"><?= $this->erp->hrsd($co_l->approved_date); ?></td>
											<td style="text-align:right;"><?= $this->erp->formatDecimal($co_l->l_disburse) ?></td>
											<td style="text-align:right;"><?= $co_l->interest; ?></td>
											<td style="text-align:left;"><?= $co_l->term; ?></td>
											<td style="text-align:right;"><?= $this->erp->formatDecimal($co_l->principle_collection); ?></td>
											<td style="text-align:right;"><?= $this->erp->formatDecimal($co_l->interest_collection); ?></td>
											<td style="text-align:right;"><?= $this->erp->formatDecimal($co_l->service_collection); ?></td>
											<td style="text-align:right;"><?= $this->erp->formatDecimal($co_l->penalty_collection); ?></td>
											<td style="text-align:right;"><?= $this->erp->formatDecimal($total_collection); ?></td>
										</tr>
									<?php 
									$i++;
									$tt_disburse += $co_l->l_disburse;
									$principle_amt += $co_l->principle_collection;
									$interest_amt += $co_l->interest_collection;
									$service_amt += $co_l->service_collection;
									$penalty_amt += $co_l->penalty_collection;
									$tt_coll += $total_collection;
									} ?>
									<tr class="active">
										<td colspan="4" class="right" style="font-weight:bold;"><?= lang("total") ?> 
											<i class="fa fa-angle-double-right" aria-hidden="true"></i> 
										</td>
										<td class="text-right"><b><?= $this->erp->formatDecimal($tt_disburse); ?></b></td>
										<td></td>
										<td></td>
										<td class="text-right"><b><?= $this->erp->formatDecimal($principle_amt); ?></b></td>
										<td class="text-right"><b><?= $this->erp->formatDecimal($interest_amt); ?></b></td>
										<td class="text-right"><b><?= $this->erp->formatDecimal($service_amt); ?></b></td>
										<td class="text-right"><b><?= $this->erp->formatDecimal($penalty_amt); ?></b></td>
										<td class="text-right"><b><?= $this->erp->formatDecimal($tt_coll); ?></b></td>
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