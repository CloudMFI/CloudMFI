<?php
	function row_status($x){
		if($x == 'completed' || $x == 'paid' || $x == 'activated' || $x == 'received') {
			return '<div class="text-center"><span class="label label-success">'.lang($x).'</span></div>';
		}elseif($x == 'applicant'){
			return '<div class="text-center"><span class="label label-warning">'.lang($x).'</span></div>';
		}elseif($x == 'approved'){
			return '<div class="text-center"><span class="label label-info">'.lang($x).'</span></div>';
		}elseif($x == 'rejected' || $x == 'returned'){
			return '<div class="text-center"><span class="label label-danger">'.lang($x).'</span></div>';
		}else{
			return '<div class="text-center"><span class="label label-default">'.lang($x).'</span></div>';
		}
	}
?>
<div class="modal-dialog modal-lg" style="width:1100px;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
			<!--<a href="#" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="sendEmail();">
				<i class="fa fa-send"></i> <?= lang('Send'); ?>
			</a>-->
            <h4 class="modal-title" id="myModalLabel"><?= lang('daily_loans').' ('.$this->erp->hrsd($date).')'; ?></h4>
        </div>
		<p style="font-size:20px; text-align:center;">  
			<?= lang("daily_loans"); ?>  <?= date('d/m/Y'); ?>  
		</p>
        <div class="table-responsive">
				<table id="POData" cellpadding="0" cellspacing="0" border="0" class="table table-condensed table-bordered table-hover table-striped">
                    <thead>
                        <tr class="active">
                        	<th style="display: none;"></th>
                            <th><?php echo $this->lang->line("submit_date"); ?></th>
							<th><?php echo $this->lang->line("appr_rej_date"); ?></th>
                            <th><?php echo $this->lang->line("reference_no"); ?></th>
                            <th><?php echo $this->lang->line("name_en"); ?></th>
                            <th><?php echo $this->lang->line("name_kh"); ?></th>
                            <th><?php echo $this->lang->line("status"); ?></th>
							<th><?php echo $this->lang->line("co_name"); ?></th>
                            <th><?php echo $this->lang->line("branch"); ?></th>
                            <th><?php echo $this->lang->line("loan_request"); ?></th>
                            <th><?php echo $this->lang->line("disburse"); ?></th>
							<th><?php echo $this->lang->line("currency"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
							$total = 0;
							$grand_total=0;
							foreach($sales as $sale){
								$total += $sale->total;
								$grand_total+=$sale->grand_total;
						?>
							<tr>
								<td style="display: none;"></td>
								<td><?= $this->erp->hrld($sale->date); ?></td>
								<td><?= ($sale->approved_date?$this->erp->hrld($sale->approved_date):'') ?></td>
								<td><?= (($sale->reference_no)?$sale->reference_no:$sale->reference); ?></td>
								<td><?= $sale->name_en; ?></td>
								<td><?= $sale->name_kh; ?></td>
								<td><?= row_status($sale->status); ?></td>
								<td><?= $sale->coname; ?></td>
								<td><?= $sale->branches; ?></td>
								<td><?= number_format($sale->total,2); ?></td>
								<td><?= number_format($sale->grand_total,2); ?></td>
								<td><?= $sale->crname; ?></td>
							</tr>
						<?php
							}
						?>
                    </tbody>
                    <tfoot>
                        <tr class="active">
                        	<th style="display: none;"></th>
                            <th><?php echo $this->lang->line("submit_date"); ?></th>
                            <th><?php echo $this->lang->line("appr_rej_date"); ?></th>
                            <th><?php echo $this->lang->line("reference_no"); ?></th>
                            <th><?php echo $this->lang->line("name_en"); ?></th>
                            <th><?php echo $this->lang->line("name_kh"); ?></th>
                            <th><?php echo $this->lang->line("status"); ?></th>
							<th><?php echo $this->lang->line("co_name"); ?></th>
                            <th><?php echo $this->lang->line("branch"); ?></th>
                            <th><?php echo number_format($total,2); ?></th>
                            <th><?php echo number_format($grand_total,2); ?></th>
							<th><?php echo $this->lang->line("currency"); ?></th>
                        </tr>
                    </tfoot>
                </table>
        </div>
    </div>

</div>
<style type="text/css">
	table { 
		white-space: nowrap;
	}
	@media print {
		.row {
			display: none !important;
		}
	}
</style>

<script>
	$(function(){
		$("#POData").dataTable({
			"aaSorting": [[0, "desc"]],
			"iDisplayLength": 20,
		});
	})
	function sendEmail(){
	var email = prompt("<?= lang("email_address"); ?>", "<?= isset($customer->email)?$customer->email:''; ?>");
	if (email != null) {
		$.ajax({
			type: "post",
			url: "<?= site_url('reports/email_receipt') ?>",
			data: {<?= $this->security->get_csrf_token_name(); ?>: "<?= $this->security->get_csrf_hash(); ?>", email: email},
			dataType: "json",
			success: function (data) {
				alert(data.msg);
			},
			error: function () {
				alert('<?= lang('ajax_request_failed'); ?>');
				return false;
			}
		});
	}
	return false;
	}
</script>
