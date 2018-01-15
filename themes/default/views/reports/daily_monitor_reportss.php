<?php
  //$this->erp->print_arrays($monitor_report);
	 
?>
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
		min-height: 500px;
		display: block;
		white-space: nowrap;
		overflow-x: scroll;
		overflow-y: scroll;
	}
	@media print{
				
				#body{
					width:1000px;
					height:100%;
					margin:0 auto;
				}
				#print{
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
			<i class="fa-fw fa fa-heart-o"></i><?= lang('daily_monitor_reports'); ?>
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
				<li><a href="#" id="xls" class="tip" title="<?= lang('download_xls') ?>"><i class="icon fa fa-file-excel-o"></i></a></li>
            </ul>
        </div>
    </div> 
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
				<button id="print" onclick="window.print()" style="margin-bottom:15px;"><i class="fa fa-print" style="font-size:20px;"></i></button>
				
				<!---Start Search------->
				<div id="form">
					<?php echo form_open('Reports/daily_monitor_report/', 'id="action-form"'); ?>
					<div class="row" style="padding:10px;">
						 
						<div class="col-sm-4">
							<div class="form-group">
								<?= lang("loans_term", "loans_term"); ?>
								<?php
								$loans_term[""] = "";
								$loans_term[1] = "Daily";
								$loans_term[7] = "Weekly";
								$loans_term[14] = "Two Week";
								$loans_term[30] = "Monthly";
								echo form_dropdown('loans_term', $loans_term, (isset($_POST['loans_term']) ? $_POST['loans_term'] : ''), 'id="loans_term" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("loans_term") . '"  class="form-control input-tip select"  ');
								?>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<?= lang("late_days", "late_days"); ?>
								<?php
								$late_days[""] = ""; 
								$late_days[7] = "A Week";
								$late_days[14] = "Two Weeks";
								$late_days[21] = "Three Weeks";
								$late_days[30] = "A Month";
								echo form_dropdown('late_days', $late_days, (isset($_POST['late_days']) ? $_POST['late_days'] : ''), 'id="late_days" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("late_days") . '"  class="form-control input-tip select"  ');
								?>
							</div>
						</div>
						<!--<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label" for="user"><?= lang("c.o_name"); ?></label>
								<?php
									$us[""] = "";
									if(is_array(isset($co) ?$co  : (''))){
									foreach ($co as $co_name) {
										$us[$co_name->id] = $co_name->first_name . " " . $co_name->last_name;
									}}
									echo form_dropdown('user', isset($us) ?$us  : (''), (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("co_name") . '"');
								?>
							</div>
						</div>-->
						
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="customer"><?= lang("by_branch"); ?></label>
								<?php
								$bn[""] = "";
								if(is_array(isset($branches) ?$branches  : (''))){
									foreach ($branches as $branch) {
										$bn[$branch->id] = $branch->name;
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
                <div>
					<p style="font-size:20px; text-align:center;"> 
						<?= lang("daily_monitor_reports_(o_d_r)"); ?>  
					</p>
                    <table id="QUData" class="table table-bordered table-hover table-striped">
                        <thead>
							<tr class="active">
								<th colspan="2">  </th>
								 
								<th colspan="3"><?= lang("outstanding"); ?> </th>
								<th>  </th> 
								
								<th colspan="2"> <?= lang("disbursement"); ?> </th>
								<th>  </th>
								
								<th colspan="5"> <?= lang("re_payment_bad_loan"); ?> </th>
								 
								<th colspan="5"> <?= lang("re_payment_good_loan"); ?>  </th> 
							</tr>
                        </thead>
						
						<tbody>
							<th><?= lang("no"); ?></th>
							<th> <?= lang("name"); ?> </th>
							<th> <?= lang("total_principle"); ?> </th>
							<th> <?= lang("good_loan_prin"); ?> </th>
							<th> <?= lang("bad_loan_prin"); ?> </th>
							<th> <?= lang("percent_(%)"); ?> </th>
							<th> <?= lang("disburse_old"); ?> </th>
							<th> <?= lang("disburse_new"); ?> </th>
							<th> <?= lang("total_received"); ?> </th>
							<th> <?= lang("total_bad_loan"); ?> </th>
							<th> <?= lang("priciple"); ?></th>
							<th> <?= lang("interest"); ?> </th>
							<th> <?= lang("saving"); ?> </th>
							<th> <?= lang("penalty"); ?> </th>
							<th> <?= lang("total_good_loan"); ?> </th>
							<th> <?= lang("priciple"); ?></th>
							<th> <?= lang("interest"); ?> </th>
							<th> <?= lang("saving"); ?> </th>
							<th> <?= lang("penalty"); ?> </th> 
						</tbody>
						
						<tbody>
							<?php 
							$i = 1; 
								foreach($monitor_report as $co){ 
							?>
									<tr>
										<td><?=$i?></td>
										<td><?= $co->first_name; ?> <?= $co->last_name; ?></td>
										<td><?= $this->erp->formatMoney($co->total_outstanding); ?> </td>
										<td> </td>
										<td> </td>
										<td> </td>
										<td> </td>
										<td> </td>
										<td> </td>
										<td> </td>
										<td> </td>
										<td> </td>
										<td> </td>
										<td> </td>
										<td> </td>
										<td> </td>
										<td> </td>
										<td> </td>
										<td> </td>
									</tr>
									<?php
									$i++;
									 
								} 
							?>
							 
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



<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        
		/*$('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/ledger/pdf/0/'.$biller_id . '?v=1'.$v)?>";
            return false;
        });*/
        $('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/ledger/0/xls/')?>";
            return false;
        });
        
    });
</script>