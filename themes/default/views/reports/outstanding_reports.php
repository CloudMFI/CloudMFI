<?php
//$this->erp->print_arrays($setting);
	/*$v = "";
	if ($this->input->post('reference_no')) {
		$v .= "&reference_no=" . $this->input->post('reference_no');
	}
	if ($this->input->post('customer')) {
		$v .= "&customer=" . $this->input->post('customer');
	}*/
	//$this->erp->print_arrays($reference);
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
		min-height: 300px;
		display: block;
		//cursor: pointer;
		white-space: nowrap;
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
			<i class="fa-fw fa fa-heart-o"></i><?= lang('outstanding_reports'); ?>
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
					<?php echo form_open('Reports/outstanding_reports/', 'id="action-form"'); ?>
					<div class="row" style="padding:10px;">
						<!--<div class="col-sm-4">
							<div class="form-group">
								<?= lang("start_date", "start_date"); ?>
								<?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : $start_date), 'class="form-control date" id="start_date" '); ?>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<?= lang("end_date", "end_date"); ?>
								<?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : $end_date), 'class="form-control date" id="end_date" ' ); ?>
							</div>
						</div>
						<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>
                            </div>
                        </div>-->
						
						<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $reference), 'class="form-control tip" id="reference_no"'); ?>
							</div>
                        </div>
						<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="rapplication"><?= lang("customer"); ?></label>
                                <?php echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="rapplication" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer") . '"'); ?>
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
									echo form_dropdown('user', isset($us) ?$us  : (''), (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("co_name") . '"');
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
                <div>
                    <table id="QUData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr class="active">
							<th style="text-align:center;"><?= lang('reference_no') ?></th>
							<th style="text-align:center;"><?= lang('customer_eng') ?></th>
							<th style="text-align:center;"><?= lang('customer_other') ?></th>
							<th style="text-align:center;"><?= lang('co_name') ?></th>
							<th style="text-align:center;"><?= lang('branch') ?></th>
							<th style="text-align:center;"><?= lang('principle') ?></th>
							<th style="text-align:center;"><?= lang('currency') ?></th>
						</tr>
                        </thead>
                        <tfoot class="dtFilter">
						<?php 
						$i = 1;
						$principle_amt = 0;
							foreach($outstanding as $principle){
								?>
								<tr>
									<td style="text-align:left;"><?= $principle->reference_no; ?></td>
									<td style="text-align:left;"><?= $principle->customer; ?></td>
									<td style="text-align:left;"><?= $principle->other_name; ?></td>
									<td style="text-align:left;"><?= $principle->cname; ?></td>
									<td style="text-align:left;"><?= $principle->branch; ?></td>
									<td style="text-align:right;"><?= $this->erp->formatMoney(str_replace(',','',$this->erp->roundUpMoney($principle->outstanding_amt,$principle->currency_code))); ?></td>
									<td style="text-align:left;"><?= $principle->cur_name; ?></td>
								</tr>
								<?php
								$i++;
								$principle_amt += $this->erp->convertCurrency($dfCurrncy->code , $principle->currency_code, $principle->outstanding_amt);
								//$principle_amt +=$principle->outstanding_amt;
								
							}
						for($k = 0;$k<7;$k++){
							
						?>
						<?php $i++; }?>
						<tr>
							<td colspan="5" style="text-align:right; font-weight:bold;"><?= lang('total') ?></td>
							<td style="text-align:right; font-weight:bold; color:red;"><?= $this->erp->formatMoney($principle_amt) ?></td>
							<td style="color:red; font-weight:bold;"> <?= $dfCurrncy->name; ?> </td>
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