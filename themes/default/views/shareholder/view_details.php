<?php //echo $this->erp->print_arrays($shareholder_info) ?>
<script type="text/javascript">
    $(document).ready(function () {
            $('.tip').tooltip();
            var oTable = $('#DepData').dataTable({
                "aaSorting": [[1, "asc"]],
                "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                "iDisplayLength": <?= $Settings->rows_per_page ?>,
                'bProcessing': true, 'bServerSide': true,
                'sAjaxSource': '<?= site_url('shareholder/getCapitals/'.$shareholder_info->id) ?>',
                //'sAjaxSource': '<?= site_url('shareholder/getCapitals') ?>',
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
            }, null, null, {"mRender": currencyFormat}, null,null, {"bSortable": false}],
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
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('shareholder_details'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("shareholder/update/".$shareholder_info->id, $attrib); ?>
        <div class="modal-body">
			<div class="table-responsive" style="background-color: #d9edf7; border-radius:5px; color:black;">
				<table style="width:100%;">
					<tbody>																																		
						<tr>
							<td><?php echo $shareholder_info->ident_name; ?></td>
							<td><b> : <?php echo $shareholder_info->gov_id; ?></b></td>
							<td><?= lang("name"); ?></td>
							<td><b> : <?php echo $shareholder_info->name; ?></b></td>
						</tr>
						<tr>
							<td><?= lang("date_of_birth"); ?></td>
							<td><b> : <?php echo $this->erp->hrsd($shareholder_info->date_of_birth); ?></b></td>
							<td><?= lang("phone"); ?></td>
							<td><b> : <?php echo $shareholder_info->phone; ?></b></td>
						</tr>
						<tr>
							<td><?= lang("email"); ?></td>
							<td><b> : <?php echo $shareholder_info->email; ?></b></td>
							<td><?= lang("place_of_birth"); ?></td>
							<td><b> : <?php echo $shareholder_info->address; ?></b></td>
						</tr>
						<tr>
							<td><?= lang("address"); ?></td>
							<td colspan="3"><b> : <span style="display:inline-block; "><?php echo $shareholder_info->house_no; ?></span></b></td>
							
						</tr>
					</tbody>
				</table>
			</div>
			
			<br/>
            <p><?= lang('capital_info'); ?></p>
			<div class="table-responsive">
                <table id="DepData" cellpadding="0" cellspacing="0" border="0" width="100%"
                           class="table table-bordered table-condensed table-hover table-striped">
                    <thead>
                    <tr class="primary">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
							<th><?php echo $this->lang->line("reference"); ?></th>
							<th><?php echo $this->lang->line("branch"); ?></th>
							<th><?php echo $this->lang->line("amount"); ?></th>
							<th><?php echo $this->lang->line("bank_account"); ?></th>
							<th><?php echo $this->lang->line("note"); ?></th>
                            <th style="min-width:5%; text-align:center;"><?= lang("actions"); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
		<div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
        </div>
        <!--<div class="modal-footer">
            <?php echo form_submit('edit_shareholder', lang('edit_shareholder'), 'class="btn btn-primary"'); ?>
        </div>-->
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>



