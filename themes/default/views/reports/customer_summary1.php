<?php

	if($sales) {
		$n = count($sales);
	}else {
		$n = 0;
	}
	$clients = 0;
	$disbursement = 0;
	$loan_out_standing = 0;
	$collection = 0;
	//$this->erp->print_arrays($sales);
	if($n > 0) {
		$clients = $n;
		foreach($sales as $sale) {
			if($sale->grand_total > 0) {
				$disbursement += $sale->grand_total;
				if($sale->paid) {
					$loan_out_standing += $sale->grand_total - $sale->paid;
					$collection += $sale->paid;
				}
			}
		}
	}

	$v = "";
	if ($this->input->post('start_date')) {
		$v .= "&start_date=" . $this->input->post('start_date');
	}
	if ($this->input->post('end_date')) {
		$v .= "&end_date=" . $this->input->post('end_date');
	}
	if ($this->input->post('customer')) {
		$v .= "&customer=" . $this->input->post('customer');
	}
	if ($this->input->post('reference_no')) {
		$v .= "&reference_no=" . $this->input->post('reference_no');
	}
	if ($this->input->post('user')) {
		$v .= "&user=" . $this->input->post('user');
	}
	if ($this->input->post('branch')) {
		$v .= "&branch=" . $this->input->post('branch');
	}
	if ($this->input->post('loan_type')) {
		$v .= "&loan_type=" . $this->input->post('loan_type');
	}
	if ($this->input->post('loan_term')) {
		$v .= "&loan_term=" . $this->input->post('loan_term');
	}
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

<?php if ($Owner) {
    echo form_open('quotes/quote_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue">
			<i class="fa-fw fa fa-heart-o"></i><?= lang('summary_reports'); ?>
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
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li>
							<a href="<?= site_url('reports/print_summary_reports/'.((isset($_POST['start_date']))? ($this->erp->fsd($_POST['start_date']).'/'):'').((isset($_POST['end_date']))? ($this->erp->fsd($_POST['end_date'])):'')) ?>" target="_blank"><i class="fa fa-print"></i> <?= lang('print') ?></a>
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
						<?php }else{ ?>
							<?php if($GP['quotes-export']) { ?>
								<li>
									<a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?>
									</a>
								</li>
								<li>
									<a href="#" id="pdf" data-action="export_pdf"><i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?>
									</a>
								</li>
							<?php }?>
						<?php }?>	
						
                        <li>
                            <a href="#" id="combine" data-action="combine">
                                <i class="fa fa-file-pdf-o"></i> <?=lang('combine_to_pdf')?>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
	<?php if ($Owner) {?>
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

                <p class="introtext"><?= lang('list_results'); ?></p>
				
				<div id="form">

                    <?php echo form_open("reports/summary_reports"); ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control date" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control date" id="end_date"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
						<input type="hidden" name="v" value="v" required="required" />
                        <div class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
				
				<div class="clearfix"></div>
                <div class="table-responsive">
                    <table id="QUData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr class="active">
                            <th><?php echo $this->lang->line("reports"); ?></th>
							<th><?php echo $this->lang->line("total"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
							<tr>
								<td><?=lang('clients');?></td>
								<td><?= $clients ?></td>
							</tr>
							<tr>
								<td><?=lang('disbursement');?></td>
								<td><?= $this->erp->formatMoney($disbursement) ?></td>
							</tr>
							<tr>
								<td><?=lang('loan_out_standing');?></td>
								<td><?= $this->erp->formatMoney($loan_out_standing) ?></td>
							</tr>
							<tr>
								<td><?=lang('collection');?></td>
								<td><?= $this->erp->formatMoney($collection) ?></td>
							</tr>
							<tr>
								<td><?=lang('income');?></td>
								<td><?= $this->erp->formatMoney(0) ?></td>
							</tr>
							<tr>
								<td><?=lang('expense');?></td>
								<td><?= $this->erp->formatMoney(0) ?></td>
							</tr>
							<tr>
								<td><?=lang('cash_on_hand');?></td>
								<td><?= $this->erp->formatMoney(0) ?></td>
							</tr>
							<tr>
								<td><?=lang('liability');?></td>
								<td><?= $this->erp->formatMoney(0) ?></td>
							</tr>
							<tr>
								<td><?=lang('write_off');?></td>
								<td><?= $this->erp->formatMoney(0) ?></td>
							</tr>
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