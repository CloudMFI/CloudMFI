<style type="text/css">
    @media print{
        .modal-dialog{
            width: 95% !important;
        }
        .modal-content{
            border: none !important;
        }
		#none_{
			display:none !important;
		}
    }
</style>
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
<div class="modal-dialog modal-lg" style="width:1150px;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('monthly_laons').' ('.$date.')'; ?></h4>
        </div>
        <div class="modal-body">
			<p style="font-size:20px; text-align:center;">  
				<?= lang("monthly_laons"); ?>  <?= date('d/m/Y'); ?>  
			</p>
            <div class="table-responsive">
				<table id="POData" cellpadding="0" cellspacing="0" border="0" class="table table-condensed table-bordered table-hover table-striped">
                    <thead>
                        <tr class="active">
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
							$grand_total = 0;
							foreach($costing as $sales){
								$total += $sales->total;
								$grand_total+=$sales->grand_total;
						?>
							<tr>
								<td><?= $this->erp->hrld($sales->date); ?></td>
								<td><?= ($sales->approved_date?$this->erp->hrld($sales->approved_date):''); ?></td>
								<td><?= (($sales->reference_no)?$sales->reference_no:$sales->reference); ?></td>
								<td><?= $sales->name_en; ?></td>
								<td><?= $sales->name_kh; ?></td>
								<td><?= row_status($sales->status); ?></td>
								<td><?= $sales->coname; ?></td>
								<td><?= $sales->branches; ?></td>
								<td><?= number_format($sales->total,2); ?></td>
								<td><?= number_format($sales->grand_total,2); ?></td>
								<td><?= $sales->crname; ?></td>
								
							</tr>
						<?php
							}
						?>
                    </tbody>
                    <tfoot class="dtFilter" id="none_">
                        <tr class="active">
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
</div>
<script>
	$(function(){
		$("#POData").dataTable({
			"aaSorting": [[0, "desc"]],
			"iDisplayLength": 20,
		});
	})
</script>
<style type="text/css">
	table { 
		white-space: nowrap;
	}
</style>
