<?php
	
	
	$v = "";
	 
	if ($this->input->post('user')) {
		$v .= "&user=" . $this->input->post('user');
	}
	 
	if ($this->input->post('start_date')) {
		$v .= "&start_date=" . $this->input->post('start_date');
	}
	if ($this->input->post('end_date')) {
		$v .= "&end_date=" . $this->input->post('end_date');
	} 
	if(isset($date)){
		$v .= "&d=" . $date;
	}

?>
<style>
	#QUData {
		overflow-x: scroll;
		max-width: 100%;
		min-height: 300px;
		display: block;
		cursor: pointer;
		white-space: nowrap;
		width:100%;
	}
	#QUData td:nth-child(6) {
    text-align: left;
	}
</style>
<script>
	function left_side(x){
		return '<div class="text-left">'+x+'</div>';
	}
    $(document).ready(function () {
        var oTable = $('#QUData').dataTable({
            "aaSorting": [[0, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('reports/getDailyCOSections'. ($warehouse_id ? '/' . $warehouse_id : '')).'/?v=1'.$v?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var total_disburse = 0;
				var total_received = 0;
				var total_bad_loan = 0;
				var total_bad_principle = 0;
				var total_bad_interest = 0;
				var total_bad_services = 0;
				var total_bad_penalty = 0;
				var total_good_loan = 0;
				var total_good_principle = 0;
				var total_good_interest = 0;
				var total_good_services = 0;
				var total_good_penalty = 0;
                for (var i = 0; i < aaData.length; i++) { 
                    total_disburse += parseFloat(aaData[aiDisplay[i]][2]);
					total_received += parseFloat(aaData[aiDisplay[i]][3]);
					total_bad_loan += parseFloat(aaData[aiDisplay[i]][4]);
					total_bad_principle += parseFloat(aaData[aiDisplay[i]][5]);
					total_bad_interest += parseFloat(aaData[aiDisplay[i]][6]);
					total_bad_services += parseFloat(aaData[aiDisplay[i]][7]);
					total_bad_penalty += parseFloat(aaData[aiDisplay[i]][8]);
					total_good_loan += parseFloat(aaData[aiDisplay[i]][9]);
					total_good_principle += parseFloat(aaData[aiDisplay[i]][10]);
					total_good_interest += parseFloat(aaData[aiDisplay[i]][11]);
					total_good_services += parseFloat(aaData[aiDisplay[i]][12]);
					total_good_penalty += parseFloat(aaData[aiDisplay[i]][13]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[2].innerHTML = currencyFormat(parseFloat(total_disburse));
				nCells[3].innerHTML = currencyFormat(parseFloat(total_received));
				nCells[4].innerHTML = currencyFormat(parseFloat(total_bad_loan));
				nCells[5].innerHTML = currencyFormat(parseFloat(total_bad_principle));
				nCells[6].innerHTML = currencyFormat(parseFloat(total_bad_interest));
				nCells[7].innerHTML = currencyFormat(parseFloat(total_bad_services));
				nCells[8].innerHTML = currencyFormat(parseFloat(total_bad_penalty));
				nCells[9].innerHTML = currencyFormat(parseFloat(total_good_loan));
				nCells[10].innerHTML = currencyFormat(parseFloat(total_good_principle));
				nCells[11].innerHTML = currencyFormat(parseFloat(total_good_interest));
				nCells[12].innerHTML = currencyFormat(parseFloat(total_good_services));
				nCells[13].innerHTML = currencyFormat(parseFloat(total_good_penalty));
            },
			'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
				nRow.id = aData[0];
				nRow.className = "contract_link";
				return nRow;
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            },null, {"mRender": currencyFormat} ,  {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat},{"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}]
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('c.o_name');?>]", filter_type: "text", data: []},  
        ], "footer");
        <?php if($this->session->userdata('remove_quls')) { ?>
         
			if (localStorage.getItem('qucurrency')) {
				localStorage.removeItem('qucurrency');
			}
         
        <?php $this->erp->unset_data('remove_quls'); } ?>
    });

</script>
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
        $("#product").autocomplete({
            source: '<?= site_url('reports/suggestions'); ?>',
            select: function (event, ui) {
                $('#product_id').val(ui.item.id);
                //$(this).val(ui.item.label);
            },
            minLength: 1,
            autoFocus: false,
            delay: 300,
        });
    });
</script>

<?php if ($Owner) {
    echo form_open('reports/getDailySections', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue">
			<i class="fa-fw fa fa-heart-o"></i><?= lang('daily_monitor_report'); ?>
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
						
                         
                        <li class="divider"></li>
                         
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
                    <?php echo form_open("reports/getDailySections"); ?>
                    <div class="row">
						  
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="user"><?= lang("created_by"); ?></label>
                                <?php
                                $us[""] = "";
								if(is_array(isset($users) ?$users  : (''))){
                                foreach ($users as $user) {
                                    $us[$user->id] = $user->first_name . " " . $user->last_name;
                                }}
                                echo form_dropdown('user', isset($us) ?$us  : (''), (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("user") . '"');
                                ?>
                            </div>
                        </div>
                         
                    </div>
                    <div class="form-group">
                        <div class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
				
				<div class="clearfix"></div>
				<p style="font-size:20px; text-align:center;"> 
					<B> <?= lang("daily_monitor_report"); ?>  <?= date('d/m/Y'); ?>   </B>
				</p>
                <div class="table-responsive">
                    <table id="QUData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th> 
                            <th><?php echo $this->lang->line("c.o_name"); ?></th>
                            <th><?php echo $this->lang->line("total_disburse"); ?></th>
                            <th><?php echo $this->lang->line("total_received"); ?></th>
                            <th><?php echo $this->lang->line("total_bad_loan"); ?></th>
                            <th><?php echo $this->lang->line("total_bad_principle"); ?></th>
                            <th><?php echo $this->lang->line("total_bad_interest"); ?></th>
                            <th><?php echo $this->lang->line("total_bad_services"); ?></th>
							<th><?php echo $this->lang->line("total_bad_penalty"); ?></th>
							<th><?php echo $this->lang->line("total_good_loan"); ?></th>
                            <th><?php echo $this->lang->line("total_good_principle"); ?></th>
							<th><?php echo $this->lang->line("total_good_interest"); ?></th>
							<th><?php echo $this->lang->line("total_good_services"); ?></th>
							<th><?php echo $this->lang->line("total_good_penalty"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="17"
                                class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?>
							</td>
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
                            <th></th>
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