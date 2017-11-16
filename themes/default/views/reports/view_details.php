<script>
    $(document).ready(function () {
        var oTable = $('#DepData').dataTable({
            "aaSorting": [[1, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('reports/getCapitalReportsByID/'.$shareholder->id) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, null, {"mRender": fld}, null,{"mRender": currencyFormat}],
			'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                
				nRow.id = aData[0];
                nRow.className = "";
                return nRow;
            }
        }).dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('reference');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('date');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('shareholder');?>]", filter_type: "text", data: []},
			{column_number: 4, filter_default_label: "[<?=lang('amount');?>]", filter_type: "text", data: []},
           
        ], "footer");
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
<div class="modal-dialog modal-lg">
   <div class="modal-content"> 
	<div class="box">
		<div class="box-header">
			<h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('capital'); ?></h2>
		</div>
		<div class="box-content">
			<div class="row">
				<div class="col-lg-12">

					<p class="introtext"><?= lang('list_results'); ?></p>

					<div class="table-responsive">
						<table id="DepData" cellpadding="0" cellspacing="0" border="0" width="100%"
							   class="table table-bordered table-condensed table-hover table-striped">
							<thead>
							<tr class="primary">
								<th style="min-width:5%; width: 5%; text-align: center;">
									<input class="checkbox checkth" type="checkbox" name="check"/>
								</th>
								<th style="min-width:5%;"><?= lang("reference"); ?></th>
								<th style="min-width:15%;"><?= lang("date"); ?></th>
								<th style="min-width:15%;"><?= lang("shareholder"); ?></th>
								<th style="min-width:15%;"><?= lang("amount"); ?></th>
								
							</tr>
							</thead>
							<tbody>
							<tr>
								<td colspan="6" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
							</tr>
							</tbody>
							<tfoot class="dtFilter">
							<tr class="active">
								<th style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkft" type="checkbox" name="check"/>
								</th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
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
			<input type="hidden" name="action-form" value="" id="form_action"/>
			<?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
		</div>
		<?= form_close() ?>
	<?php } ?>
	<?php if ($action && $action == 'add') {
		echo '<script>$(document).ready(function(){$("#add").trigger("click");});</script>';
	}
	?>
	<script language="javascript">
		$(document).ready(function () {

			$('#delete').click(function (e) {
				e.preventDefault();
				$('#form_action').val($(this).attr('data-action'));
				$('#action-form-submit').trigger('click');
			});

			$('#excel').click(function (e) {
				e.preventDefault();
				$('#form_action').val($(this).attr('data-action'));
				$('#action-form-submit').trigger('click');
			});

			$('#pdf').click(function (e) {
				e.preventDefault();
				$('#form_action').val($(this).attr('data-action'));
				$('#action-form-submit').trigger('click');
			});
		});
	</script>




    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>



