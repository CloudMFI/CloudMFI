<script>
    $(document).ready(function () {
        var oTable = $('#SupData').dataTable({
            "aaSorting": [[1, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('branch/getBranch') ?>',
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
            }, null, null,null,null, null,{"bSortable": false}],
			'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                /*
				var oSettings = oTable.fnSettings();
                if (aData[7] == 'sent') {
                    nRow.className = "warning";
                } else if (aData[7] == 'returned') {
                    nRow.className = "danger";
                }
				*/
				nRow.id = aData[0];
                nRow.className = "";
                return nRow;
            }
        }).dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('branch_code');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('branch_name');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('address');?>]", filter_type: "text", data: []},
			{column_number: 4, filter_default_label: "[<?=lang('tel');?>]", filter_type: "text", data: []},
			{column_number: 5, filter_default_label: "[<?=lang('e_mail');?>]", filter_type: "text", data: []},
           
        ], "footer");
    });
</script>
<?php if ($Owner) {
    echo form_open('branch/branch_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('branch'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks"> 
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"
                                                                                  data-placement="left"
                                                                                  title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?= site_url('branch/add'); ?>" data-toggle="modal" data-target="#myModal"
                               id="add"><i class="fa fa-plus-circle"></i> <?= lang("add_branch"); ?></a></li>
		
                        <!--<li><a href="#" class="bpo" title="<?= $this->lang->line("delete_chart_accounts") ?>"
                               data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>"
                               data-html="true" data-placement="left"><i
                                    class="fa fa-trash-o"></i> <?= lang('delete_branch') ?></a></li>-->
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('list_results'); ?></p>

                <div class="table-responsive">
                    <table id="SupData" cellpadding="0" cellspacing="0" border="0" width="100%"
                           class="table table-bordered table-condensed table-hover table-striped">
                        <thead>
                        <tr class="primary">
                            <th style="min-width:5%; width: 5%; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
                            <th style="min-width:10%;"><?= lang("branch_code"); ?></th>
                            <th style="min-width:20%;"><?= lang("branch_name"); ?></th>
							<th style="min-width:30%;"><?= lang("address"); ?></th>
							<th style="min-width:15%;"><?= lang("tel"); ?></th>
                            <th style="min-width:20%;"><?= lang("e_mail"); ?></th>
                            <th style="min-width:5%; text-align:center;"><?= lang("actions"); ?></th>
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
							<th></th>
                            <th><?= '['.lang("actions").']'; ?></th>
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



