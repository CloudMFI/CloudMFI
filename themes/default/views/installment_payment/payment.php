<style type="text/css">
	.font_bold{
		font-weight:bold;
	}
</style>
<?php
	$lease_amount = $contract->subtotal - $contract->advance_percentage_payment;
	$interest = $lease_amount*$contract->interest_rate;
	$principle = $lease_amount/$contract->term;
	$installment_amount = $interest + $principle;
?>
<script>
    $(document).ready(function (e) {
		
		
        var oTable = $('#Loan_List').dataTable({
            "aaSorting": [[1, "asc"], [0, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": 100,
            'bProcessing': true, 'bServerSide': true,
			'bFilter': false,
            'sAjaxSource': '<?=site_url('Installment_payment/list_loan_data/'.$id)?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?=$this->security->get_csrf_token_name()?>",
                    "value": "<?=$this->security->get_csrf_hash()?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
				
                return nRow;
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, null, {"mRender": fld}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
               
			   var total_payment_amount = 0, principle_amount = 0, interest_rate_amount = 0, total_paid_amount = 0, balance_amount = 0;
                for (var i = 0; i < aaData.length; i++) {
                    total_payment_amount += parseFloat(aaData[aiDisplay[i]][3]);
                    principle_amount += parseFloat(aaData[aiDisplay[i]][4]);
                    interest_rate_amount += parseFloat(aaData[aiDisplay[i]][5]);
                    total_paid_amount += parseFloat(aaData[aiDisplay[i]][6]);
                    balance_amount += parseFloat(aaData[aiDisplay[i]][7]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[3].innerHTML = currencyFormat(parseFloat(total_payment_amount));
                nCells[4].innerHTML = currencyFormat(parseFloat(principle_amount));
                nCells[5].innerHTML = currencyFormat(parseFloat(interest_rate_amount));
                nCells[6].innerHTML = currencyFormat(parseFloat(total_paid_amount));
                nCells[7].innerHTML = currencyFormat(parseFloat(balance_amount));
            },
			"fnInitComplete": function (oSettings, json) {
				alerts();
			}
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('Pmt No.');?>] ", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('dateline');?>]", filter_type: "text", data: []},
        ], "footer");

    });
	
	function alerts(){
		$('.bb .checkbox').each(function(){		
			var parent = $(this).parent().parent().parent().parent();
			var help = parent.children("td:nth-child(7)").text()-0;
			
			if(help < 0){
				parent.css('background-color', '#d7edeb !important');
				$(this).attr('disabled',true);
			}
		});
	}
	
</script>	
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-edit"></i><?= lang('payment_schedule'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
				<div class="row">
					<div class="col-md-12">
						<div class="col-sm-3">
							<?= lang('customer'); ?>
						</div>
						<div class="col-sm-3 font_bold">
							<?= (($contract->family_name_other && $contract->name_other)? ($contract->family_name_other.' '.$contract->name_other):($contract->family_name.' '.$contract->name)); ?>
						</div>
						<div class="col-sm-6">
						
						</div>
					</div>
					<div class="col-md-12">
						<div class="col-sm-3">
							<?= lang('address'); ?>
						</div>
						<div class="col-sm-6">
							<?= $contract->house_no.', '.$contract->street.', '.$contract->housing.', '.$contract->village.', '.$contract->district.', '.$contract->sangkat.', '.$contract->state.', '.$contract->country; ?>
						</div>
						<div class="col-sm-3">
							
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="col-sm-3">
							<?= lang('phone'); ?>
						</div>
						<div class="col-sm-3">
							<?= ($contract->phone2? $contract->phone1.'/'.$contract->phone2 : $contract->phone1); ?>
						</div>
						<div class="col-sm-3">
							<?= lang('reference'); ?>
						</div>
						<div class="col-sm-3 font_bold">
							<?= $contract->reference_no; ?>
						</div>
					</div>
					<div class="col-md-12">
						<div class="col-sm-3">
							<?= lang('customer_id'); ?>
						</div>
						<div class="col-sm-3">
							<?= $contract->id; ?>
						</div>
						<div class="col-sm-3">
							
						</div>
						<div class="col-sm-3">
							
						</div>
					</div>
				</div>
				<div class="row"> <br/> </div>
				<div class="row" style="border:1px solid black; padding:10px;">
					<div class="col-md-12">
						<div class="col-sm-3">
							<?= lang('model'); ?>
						</div>
						<div class="col-sm-3">
							<?= $contract->assets; ?>
						</div>
						<div class="col-sm-3">
							<?= lang('lease_amount'); ?>
						</div>
						<div class="col-sm-3">
							<?= $this->erp->formatMoney($lease_amount); ?>
						</div>
					</div>
					<div class="col-md-12">
						<div class="col-sm-3">
							<?= lang('year'); ?>
						</div>
						<div class="col-sm-3">
							<?= $contract->product_year; ?>
						</div>
						<div class="col-sm-3">
							<?= lang('interest_rate'); ?>
						</div>
						<div class="col-sm-3">
							<?= ($contract->interest_rate*100).' %'; ?>
						</div>
					</div>
					<div class="col-md-12">
						<div class="col-sm-3">
							<?= lang('power_engine'); ?>
						</div>
						<div class="col-sm-3">
							<?= $contract->engine; ?>
						</div>
						<div class="col-sm-3">
							<?= lang('term'); ?>
						</div>
						<div class="col-sm-3">
							<?= number_format($contract->term).' Months'; ?>
						</div>
					</div>
					<div class="col-md-12">
						<div class="col-sm-3">
							<?= lang('price'); ?>
						</div>
						<div class="col-sm-3">
							<?= '$ '.$this->erp->formatMoney($contract->subtotal); ?>
						</div>
						<div class="col-sm-3">
							<?= lang('monthly_installment_amount'); ?>
						</div>
						<div class="col-sm-3">
							<?= '$ '.$this->erp->formatMoney($installment_amount); ?>
						</div>
					</div>
					<div class="col-md-12">
						<div class="col-sm-3">
							<?= lang('advance_payment_rate'); ?>
						</div>
						<div class="col-sm-3">
							<?= ($contract->advance_percentage_payment*100).' %'; ?>
						</div>
						<div class="col-sm-3">
							<?= lang('contract_date'); ?>
						</div>
						<div class="col-sm-3">
							<?= $this->erp->hrsd($contract->contract_date); ?>
						</div>
					</div>
					<div class="col-md-12">
						<div class="col-sm-3">
							<?= lang('advance_payment'); ?>
						</div>
						<div class="col-sm-3">
							<?= '$ '.$this->erp->formatMoney($contract->advance_payment); ?>
						</div>
						<div class="col-sm-3">
							<?= lang('first_due_date'); ?>
						</div>
						<div class="col-sm-3">
							<?= $this->erp->hrsd($contract->due_date); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="table-responsive">
						<table id="Loan_List" class="table table-bordered">
							<thead>
							<tr>
								<th style="min-width:30px; width: 30px; text-align: center;">
									<!--<input class="checkbox checkft" type="checkbox" name="check"/>-->
								</th>
								<th><?php echo $this->lang->line("Pmt No."); ?></th>
								<th><?php echo $this->lang->line("dateline"); ?></th>
								<th><?php echo $this->lang->line("total_payment"); ?></th>
								<th><?php echo $this->lang->line("principle"); ?></th>
								<th><?php echo $this->lang->line("interest_rate"); ?></th>
								<th><?php echo $this->lang->line("payment_amount"); ?></th>
								<th><?php echo $this->lang->line("balance"); ?></th>
							</tr>
							</thead>
							<tbody class="bb">
							<tr>
								<td colspan="8" class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?></td>
							</tr>
							</tbody>
							<tfoot class="dtFilter">
							<tr class="active">
								<th style="min-width:30px; width: 30px; text-align: center;">
									<!--<input class="checkbox checkft" type="checkbox" name="check"/>-->
								</th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="buttons">
						<div class="btn-group btn-group-justified">
							<div class="col-md-3">
								<!-- Add Payment -->
								<div class="btn-group">
									<input type="hidden" name="sale_id" id="sale_id" value="<?= $id; ?>" />
									<a href="#" data-toggle="modal" data-target="#myModal2" class="add_payment_list tip btn btn-primary pay" title="<?= lang('add_payment') ?>">
										<i class="fa fa-money"></i>
										<span class="hidden-sm hidden-xs"><?= lang('add_payment') ?></span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready( function() {
	
	$(".add_payment_list").bind('click',function(){
		var sale_id = $('#sale_id').val();
		var id = '';
		if($(".bb .checkbox:checked").length > 0){
			
			var i = 0;
			$(".bb .checkbox:checked").each(function(){
				if(i == 0){
					id = $(this).val();
				} else {
					id += '_'+$(this).val();
				}
				i++;
			});
			
			$(this).attr('href', "<?= site_url('Installment_payment/add_payment') ?>/"+id+"/"+sale_id);
		}else {
			
			alert("Please check..");
			return false;
		}
		
	});
	
});
</script>

