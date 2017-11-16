<?php //echo $this->erp->print_arrays($applicants) ?>
<script type="text/javascript">
    function left_side(x){
		return '<div class="text-left">'+x+'</div>';
		}
		$(document).ready(function () {
			var oTable = $('#QUData').dataTable({
				"aaSorting": [[0, "desc"]],
				"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
				"iDisplayLength": <?= $Settings->rows_per_page ?>,
				"bJQueryUI":true,
				'bLengthChange': true,
				'bProcessing': true, 'bServerSide': true,
				'sAjaxSource': '<?= site_url('customers/getCustomerQuotes/'.$customer_loan->gov_id) ?>',
				'fnServerData': function (sSource, aoData, fnCallback) {
					aoData.push({
						"name": "<?= $this->security->get_csrf_token_name() ?>",
						"value": "<?= $this->security->get_csrf_hash() ?>"
					});
					$.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
				},
				'fnRowCallback': function (nRow, aData, iDisplayIndex) {
					var oSettings = oTable.fnSettings();
					nRow.id = aData[0];
					$(nRow).attr("status", aData[7]);
					//nRow.className = "payment_schedule_link";// make it to popup payment_schedule
					var action = $('td:eq(11)', nRow);
					
					if(aData[7] == 'draft' || aData[7] == 'applicant'){
						nRow.className = "warning";
					}
					return nRow;
				},
				
				"aoColumns": [{
					"bSortable": false,
					"mRender": checkbox
				}, null,null,null,null, null, {"bVisible": false}, {"mRender": row_status}, {"mRender": fld}, {"mRender": fld},null,null,{"mRender": currencyFormat}],
			});
            $('div.dataTables_length select').addClass('form-control');
            $('div.dataTables_length select').addClass('select2');
            $('div.dataTables_filter input').attr('placeholder', 'Seaching...');
            $('select.select2').select2({minimumResultsForSearch: 7});
        });
</script>

<style type="text/css">
	table tr td{
		vertical-align: text-top;
		padding:10px 10px 0 10px;
		//min-width:10%;
		width:auto;
	}
</style>
<div class="modal-dialog modal-lg" style="width:80%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('customer_details'); ?></h4>
        </div>
        <div class="modal-body">
			<div class="table-responsive" style="background-color: #d9edf7; border-radius:5px; color:black;">
				<table style="width:100%;">
					<tbody>																																		
						<tr>
							<td><?= lang("name"); ?></td>
							<td><b> : <?php echo $customer_loan->family_name . " " .$customer_loan->name; ?></b></td>
							<td><?php echo $customer_loan->ident_name; ?></td>
							<td><b> : <?php echo $customer_loan->gov_id; ?></b></td>
						</tr>
						<tr>
							<td><?= lang("gender"); ?></td>
							<td><b> : <?php echo $customer_loan->gender; ?></b></td>
							<td><?= lang("date_of_birth"); ?></td>
							<td><b> : <?php echo $this->erp->hrsd($customer_loan->date_of_birth); ?></b></td>
						</tr>
						<tr>
							<td><?= lang("phone"); ?></td>
							<td><b> : <?php echo $customer_loan->phone1; ?></b></td>
							<td><?= lang("status"); ?></td>
							<td><b> : <?php echo ucfirst($customer_loan->status); ?></b></td>
						</tr>
						<tr>
							<td style="padding-bottom:10px;"><?= lang("address"); ?></td>
							<td colspan="3"><b> : <span style="display:inline-block; "><?php echo $customer_loan->house_no; ?></span></b></td>
						</tr>
					</tbody>
				</table>
			</div>
			<br/>
			<div class="table-responsive">
				<table id="QUData" class="table table-bordered table-hover table-striped">
					<thead>
					<tr class="active">
						<th style="min-width:30px; width: 30px; text-align: center;">
							<input class="checkbox checkft" type="checkbox" name="check"/>
						</th>
						<th><?php echo $this->lang->line("reference_no"); ?></th>
						<th><?php echo $this->lang->line("group_loans"); ?></th>
						<th><?php echo $this->lang->line("customer_en"); ?></th>
						<th><?php echo $this->lang->line("customer_kh"); ?></th>
						<th><?php echo $this->lang->line("asset"); ?></th>
						<th><?php echo $this->lang->line("dealer"); ?></th>
						<th><?php echo $this->lang->line("status"); ?></th>
						<th><?php echo $this->lang->line("submit_date"); ?></th>
						<th><?php echo $this->lang->line("appr/rej_date"); ?></th>
						<th><?php echo $this->lang->line("c.o_name"); ?></th>
						<th><?php echo $this->lang->line("branch"); ?></th>
						<th><?php echo $this->lang->line("total"); ?></th>
						
					</tr>
					</thead>
				</table>
			</div>
        </div>
		
		<div class="modal-footer">
			<?php
				$applicant = anchor('quotes/add/'.$customer_loan->com_id, '<button type="button" class="btn btn-primary">'.lang("accept").'</button>');
				echo $applicant;
			?>
			<a class="submenu" href="<?= site_url('quotes/add'); ?>">
				<button type="button" class="btn btn-danger"><?= lang('reject') ?></button>
			</a>
	   </div>
    </div>
</div>
<?= $modal_js ?>

<script type="text/javascript">
/*$(document).ready(function(){
	$(".btn_accept").on("click",function(){
			alert("kk");
	});
});*/

</script> 

