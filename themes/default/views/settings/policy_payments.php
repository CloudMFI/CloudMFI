<script>
    $(document).ready(function () {
		var lang = {'1': '<?=lang('active');?>', '0': '<?=lang('inactive');?>'}
        function inactives(x) {
			if(x == null || x == 0) {
				return '<div class="text-center"><span class="label label-danger">'+lang[x]+'</span></div>';
			} 
			else {
				return '<div class="text-center"><span class="label label-info">'+lang[x]+'</span></div>';
			}
		}

        $('#CURData').dataTable({
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('system_settings/get_policy_payments/'.$id) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                 $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{"bSortable": false, "mRender": checkbox},null,{"bSortable": false}]
        });
    });
</script>

<?= form_open('system_settings/policy_payments_actions', 'id="action-form"') ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-building-o"></i><?= $page_title ?></h2>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"
                                                                                  data-placement="left"
                                                                                  title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?php echo site_url('system_settings/add_policy_payments'); ?>" data-toggle="modal"
                               data-target="#myModal"><i class="fa fa-plus"></i> <?= lang('add_policy_payments') ?></a></li>
                        <li class="divider"></li>
						
						<!--<li><a href="<?php echo site_url('System_settings/importholiday'); ?>" data-toggle="modal"><i class="fa fa-plus"></i> <?= lang('import_holidays') ?></a></li>-->
                        <li class="divider"></li>
						
                        <li><a href="#" id="delete" data-action="delete">
								<i class="fa fa-trash-o"></i> <?= lang('delete_policy_payments') ?>
							</a>
						</li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo $this->lang->line("list_results"); ?></p>

                <div class="table-responsive">
                    <table id="CURData" class="table table-bordered table-hover table-striped table-condensed">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
                            <th><?php echo $this->lang->line("policy_payments_days"); ?></th>							
                            <th style="width:65px;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="8" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="display: none;">
    <input insurances="hidden" name="form_action" value="" id="form_action"/>
    <?= form_submit('submit', 'submit', 'id="action-form-submit"') ?>
</div>
<?= form_close() ?>
<script language="javascript">
    $(document).ready(function () {

        $('#delete').click(function (e) {
            e.preventDefault();
            $('#form_action').val($(this).attr('data-action'));
			//console.log($(this).attr('data-action'));return;
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