<?php
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
<style>
	#QUData {
		overflow-x: scroll;
		max-width: 100%;
		min-height: 300px;
		display: block;
		white-space: nowrap;
	}
</style>

<script>
	function left_side(x){
		return '<div class="text-left">'+x+'</div>';
	}
    $(document).ready(function () {
        var oTable = $('#QUData').dataTable({
            "aaSorting": [[7, "desc"]],
            "aLengthMenu": [[10, 50, 100, 250, 500], [10, 50, 100, 250, 500]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
			"bJQueryUI":true,
			'bLengthChange': false,
			'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('reports/getLoanReports'. ($warehouse_id ? '/' . $warehouse_id : '')).'/?v=1'.$v?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{"mRender": center}, null, null, null, {"mRender": gender}, null, {"mRender": fld}, {"mRender": terest_in_percent}, {"mRender": term_in_days}, null, {"mRender": currencyFormat}, {"mRender": currencyFormat}],
			'fnFooterCallback': function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var total_amount = 0, gbalance = 0;
                for (var i = 0; i < aaData.length; i++) {
					total_amount += parseFloat(aaData[aiDisplay[i]][10]);
					gbalance += parseFloat(aaData[aiDisplay[i]][11]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[10].innerHTML = currencyFormat(parseFloat(total_amount));
                nCells[11].innerHTML = currencyFormat(parseFloat(gbalance));
            }
        }).fnSetFilteringDelay().dtFilter([
			{column_number: 0, filter_default_label: "[<?=lang('no');?>]", filter_type: "text", data: []},
            {column_number: 1, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('customer_kh');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('gender');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('phone');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('disburse_date');?>]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[<?=lang('interest');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            {column_number: 8, filter_default_label: "[<?=lang('term');?>]", filter_type: "text", data: []},
            {column_number: 9, filter_default_label: "[<?=lang('co');?>]", filter_type: "text", data: []},
			
        ], "footer");
        <?php if($this->session->userdata('remove_quls')) { ?>
        if (localStorage.getItem('quitems')) {
            localStorage.removeItem('quitems');
        }
        if (localStorage.getItem('qudiscount')) {
            localStorage.removeItem('qudiscount');
        }
        if (localStorage.getItem('qutax2')) {
            localStorage.removeItem('qutax2');
        }
        if (localStorage.getItem('qushipping')) {
            localStorage.removeItem('qushipping');
        }
        if (localStorage.getItem('quref')) {
            localStorage.removeItem('quref');
        }
        if (localStorage.getItem('quwarehouse')) {
            localStorage.removeItem('quwarehouse');
        }
        if (localStorage.getItem('qunote')) {
            localStorage.removeItem('qunote');
        }
        if (localStorage.getItem('qucustomer')) {
            localStorage.removeItem('qucustomer');
        }
        if (localStorage.getItem('qubiller')) {
            localStorage.removeItem('qubiller');
        }
        if (localStorage.getItem('qucurrency')) {
            localStorage.removeItem('qucurrency');
        }
        if (localStorage.getItem('qudate')) {
            localStorage.removeItem('qudate');
        }
        if (localStorage.getItem('qustatus')) {
            localStorage.removeItem('qustatus');
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
    });
</script>

<?php if ($Owner) {
    echo form_open('quotes/quote_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue">
			<i class="fa-fw fa fa-heart-o"></i><?= lang('loan_report'); ?>
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
							<?php if (isset($this->permission['reports-back_office'])?($this->permission['reports-back_office']):''){ ?>
								<a href="<?= site_url('quotes/add') ?>"><i class="fa fa-plus-circle"></i> <?= lang('add_contract') ?>
                            </a>
							<?php }else{ ?>
								<a href="<?= site_url('quotes/add') ?>"><i class="fa fa-plus-circle"></i> <?= lang('add_quote') ?>
                            </a>
							<?php } ?>
                        </li>
						<li>
							<a href="<?= site_url('reports/print_loan_reports/'.((isset($_POST['start_date']))? ($this->erp->fsd($_POST['start_date']).'/'):'').((isset($_POST['end_date']))? ($this->erp->fsd($_POST['end_date']).'/'):'').(isset($_POST['customer'])? $_POST['customer'].'/':'0').(isset($_POST['user'])? $_POST['user'].'/':'0').(isset($_POST['branch'])? $_POST['branch'].'/':'0').(isset($_POST['loan_type'])? $_POST['loan_type'].'/':'0').(isset($_POST['loan_term'])? $_POST['loan_term'].'/':'0').(isset($_POST['reference_no'])? $_POST['reference_no'].'/':'0')) ?>" target="_blank"><i class="fa fa-print"></i> <?= lang('print') ?></a>
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
                        <li class="divider"></li>
                        <li>
							<?php if (isset($this->permission['reports-back_office'])?($this->permission['reports-back_office']):''){ ?>
							<a href="#" class="bpo" title="<?= $this->lang->line("delete_contracts") ?>" 
                                data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>" 
                                data-html="true" data-placement="left"><i class="fa fa-trash-o"></i> <?= lang('delete_contracts') ?>
                            </a>
							<?php }else{ ?>
                            <a href="#" class="bpo" title="<?= $this->lang->line("delete_quotes") ?>" 
                                data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>" 
                                data-html="true" data-placement="left"><i class="fa fa-trash-o"></i> <?= lang('delete_quotes') ?>
                            </a>
							<?php } ?>
                        </li>
						<li>
                            <a href="<?= site_url('customers/send_sms'); ?>" data-toggle="modal" data-target="#myModal" id="SMS">
                                <i class="fa fa-send"></i> <?= lang("Send SMS"); ?>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php if (!empty($warehouses)) { ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("warehouses") ?>"></i></a>
                        <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?= site_url('quotes') ?>"><i class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a></li>
                            <li class="divider"></li>
                            <?php
                            foreach ($warehouses as $warehouse) {
                                echo '<li><a href="' . site_url('quotes/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                <?php } ?>
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

                    <?php echo form_open("reports/loan_report"); ?>
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
						<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="scustomer"><?= lang("customer"); ?></label>
                                <?php
                                $cust["0"] = "All";
								if(isset($customers)){
									foreach ($customers as $customer) {
										$cust[$customer->id] = $customer->family_name .' '. $customer->name;
									}
								}
                                echo form_dropdown('customer', $cust, (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="scustomer" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="user"><?= lang("co"); ?></label>
                                <?php
                                $us["0"] = "All";
								if(isset($users)){
									foreach ($users as $user) {
										$us[$user->id] = $user->first_name . " " . $user->last_name;
									}
								}
                                echo form_dropdown('user', $us, (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("user") . '"');
                                ?>
                            </div>
                        </div>
						<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="branch"><?= lang("branch"); ?></label>
                                <?php
                                $bch["0"] = "All";
								if(isset($branches)){
									foreach ($branches as $branch) {
										$bch[$branch->id] = $branch->name;
									}
								}
                                echo form_dropdown('branch', $bch, (isset($_POST['branch']) ? $_POST['branch'] : ""), 'class="form-control" id="branch" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("branch") . '"');
                                ?>
                            </div>
                        </div>
						<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="loan_type"><?= lang("loan_type"); ?></label>
                                <?php
                                $lt["0"] = "All";
								if(isset($loan_types)){
									foreach ($loan_types as $loan_type) {
										$lt[$loan_type->id] = $loan_type->name;
									}
								}
                                echo form_dropdown('loan_type', $lt, (isset($_POST['loan_type']) ? $_POST['loan_type'] : ""), 'class="form-control" id="loan_type" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("loan_type") . '"');
                                ?>
                            </div>
                        </div>
						<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="loan_term"><?= lang("loan_term"); ?></label>
                                <?php
                                $ltm["0"] = "All";
								if(isset($loan_terms)) {
									foreach ($loan_terms as $loan_term) {
										$ltm[$loan_term->amount] = $loan_term->amount ." Day";
									}
								}
                                echo form_dropdown('loan_term', $ltm, (isset($_POST['loan_term']) ? $_POST['loan_term'] : ""), 'class="form-control" id="loan_term" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("loan_term") . '"');
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
                <div class="table-responsive">
                    <table id="QUData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr class="active">
                            <th><?php echo $this->lang->line("no"); ?></th>
							<th><?php echo $this->lang->line("reference_no"); ?></th>
							<th><?php echo $this->lang->line("customer"); ?></th>
							<th><?php echo $this->lang->line("customer_kh"); ?></th>
							<th><?php echo $this->lang->line("gender"); ?></th>
                            <th><?php echo $this->lang->line("phone"); ?></th>
							<th><?php echo $this->lang->line("disburse_date"); ?></th>
							<th><?php echo $this->lang->line("interest"); ?></th>
							<th><?php echo $this->lang->line("term"); ?></th>
							<th><?php echo $this->lang->line("co"); ?></th>
							<th><?php echo $this->lang->line("loan_request"); ?></th>
                            <th><?php echo $this->lang->line("disburse"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="12"
                                class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?>
							</td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
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