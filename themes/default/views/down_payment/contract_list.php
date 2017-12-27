<?php
	
	
	$v = "";
	/* if($this->input->post('name')){
	  $v .= "&product=".$this->input->post('product');
	  } */
	if ($this->input->post('reference_no')) {
		$v .= "&reference_no=" . $this->input->post('reference_no');
	}
	if ($this->input->post('customer')) {
		$v .= "&customer=" . $this->input->post('customer');
	}
	if ($this->input->post('biller')) {
		$v .= "&biller=" . $this->input->post('biller');
	}
	if ($this->input->post('gr_loan')) {
		$v .= "&gr_loan=" . $this->input->post('gr_loan');
	}
	if ($this->input->post('warehouse')) {
		$v .= "&warehouse=" . $this->input->post('warehouse');
	}
	if ($this->input->post('user')) {
		$v .= "&user=" . $this->input->post('user');
	}
	if ($this->input->post('serial')) {
		$v .= "&serial=" . $this->input->post('serial');
	}
	if ($this->input->post('start_date')) {
		$v .= "&start_date=" . $this->input->post('start_date');
	}
	if ($this->input->post('end_date')) {
		$v .= "&end_date=" . $this->input->post('end_date');
	}
	if ($this->input->post('product_id')) {
		$v .= "&product_id=" . $this->input->post('product_id');
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
		//cursor: pointer;
		white-space: nowrap;
		width:100%;
	}
</style>
<script>
	function left_side(x){
		return '<div class="text-left">'+x+'</div>';
	}
    $(document).ready(function () {
        var oTable = $('#QUData').dataTable({
            "aaSorting": [[0, "desc"]],
            "aLengthMenu": [[10, 50, 100, 250, 500], [10,50, 100, 250, 500]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('down_payment/getContracts'. ($warehouse_id ? '/' . $warehouse_id : '')).'/?v=1'.$v?>',
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
				//nRow.className = "contract_link";
				$(nRow).attr("status", aData[17]);
				$(nRow).attr("mfi", aData[18]);
				$(nRow).attr("loan_groups", aData[3]);
				var action = $('td:eq(18)', nRow);
				//nRow.className = "contract_link";
				if(aData[3] == null) {
					action.find('.group_a').remove();
				}
				if(aData[17] == 'approved'){
					action.find('.de').remove();
				}
				///17 feild action
				//// feild mfi		
				if(aData[18] == 1) {
					action.find('.cl').remove();
					action.find('.ga').remove();
					action.find('.gf').remove();
					action.find('.lc').remove();
					action.find('.el').remove();
				}
				return nRow;
            },
			"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var request_loan = 0, disburse = 0, remaining = 0;
                for (var i = 0; i < aaData.length; i++) {
					if(isNaN(parseFloat(aaData[aiDisplay[i]][13]))){
						request_loan += parseFloat(0);
					}else{
						request_loan += parseFloat(aaData[aiDisplay[i]][13]);
					}
					
					if(isNaN(parseFloat(aaData[aiDisplay[i]][14]))){
						disburse += parseFloat(0);
					}else{
						disburse += parseFloat(aaData[aiDisplay[i]][14]);
					}
					if(isNaN(parseFloat(aaData[aiDisplay[i]][15]))){
						remaining += parseFloat(0);
					}else{
						remaining += parseFloat(aaData[aiDisplay[i]][15]);
					}
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[13].innerHTML = currencyFormat(parseFloat(request_loan));
                nCells[14].innerHTML = currencyFormat(parseFloat(disburse));
				nCells[15].innerHTML = currencyFormat(parseFloat(remaining));
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            },null, null, null, null, null,{"mRender": fld}, null, null, null, null, null, null, {"mRender": currencyFormat}, {"mRender": currencyFormat},{"mRender": currencyFormat}, null, {"mRender": row_status_down}, {"bVisible": false}, {"bSortable": false}]
        }).fnSetFilteringDelay().dtFilter([
			{column_number: 1, filter_default_label: "[<?=lang('customer_id');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('group_loans');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('customer_kh');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('approved_date');?>]", filter_type: "text", data: []},
			{column_number: 7, filter_default_label: "[<?=lang('created_by');?>]", filter_type: "text", data: []},
            {column_number: 8, filter_default_label: "[<?=lang('branch');?>]", filter_type: "text", data: []},            
			{column_number: 9, filter_default_label: "[<?=lang('model');?>]", filter_type: "text", data: []},
            {column_number: 10, filter_default_label: "[<?=lang('rate');?>]", filter_type: "text", data: []},
			{column_number: 11, filter_default_label: "[<?=lang('term');?>]", filter_type: "text", data: []},
            {column_number: 12, filter_default_label: "[<?=lang('pay_term');?>]", filter_type: "text", data: []},			
            {column_number: 16, filter_default_label: "[<?=lang('currency');?>]", filter_type: "text", data: []},
            {column_number: 17, filter_default_label: "[<?=lang('status');?>]", filter_type: "text", data: []},
            {column_number: 18, filter_default_label: "[<?=lang('mfi');?>]", filter_type: "text", data: []},
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
    echo form_open('down_payment/Down_Payment_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue">
		<?php if (isset($this->permission['reports-back_office']) ?$this->permission['reports-back_office']  : ('')){ ?>
			<i class="fa-fw fa fa-heart-o"></i><?= lang('contracts') . ' (' . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . ')'; ?>
		<?php }else{ ?>
			<i class="fa-fw fa fa-heart-o"></i><?= lang('loan_approved'); ?>
		<?php } ?>
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
							<?php if (isset($this->permission['reports-back_office']) ?$this->permission['reports-back_office']  : ('')){ ?>
								<a href="<?= site_url('quotes/add') ?>"><i class="fa fa-plus-circle"></i> <?= lang('add_contract') ?>
                            </a>
							<?php }else{ ?>
								<a href="<?= site_url('quotes/add') ?>"><i class="fa fa-plus-circle"></i> <?= lang('add_quote') ?>
                            </a>
							<?php } ?>
                        </li>
						
						<li>
							<a href="<?= site_url('down_payment/impApplicants') ?>"><i class="fa fa-plus-circle"></i> 
								<?= lang('import_applicants') ?>
                            </a>
                        </li>
						<li>
							<a href="<?= site_url('down_payment/impContracts') ?>"><i class="fa fa-plus-circle"></i> 
								<?= lang('import_contracts') ?>
                            </a>
                        </li>
						<li>
							<a href="<?= site_url('down_payment/impContractsDetail') ?>"><i class="fa fa-plus-circle"></i> 
								<?= lang('import_contract_detail') ?>
                            </a>
                        </li>
						<li>
							<a href="<?= site_url('down_payment/impContractsDetail') ?>"><i class="fa fa-plus-circle"></i> 
								<?= lang('sample_import_servcies') ?>
                            </a>
                        </li>
						<li>
							<a href="<?= site_url('down_payment/impSchedule') ?>"><i class="fa fa-plus-circle"></i> 
								<?= lang('import_schedule') ?>
                            </a>
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
							<?php if (isset($this->permission['reports-back_office']) ?$this->permission['reports-back_office']  : ('')){ ?>
							<a href="#" class="bpo" title="<?= $this->lang->line("delete_contracts") ?>" 
                                data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>" 
                                data-html="true" data-placement="left"><i class="fa fa-trash-o"></i> <?= lang('delete_contracts') ?>
                            </a>
							<?php }else{ ?>
                           <!-- <a href="#" class="bpo" title="<?= $this->lang->line("delete_quotes") ?>" 
                                data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>" 
                                data-html="true" data-placement="left"><i class="fa fa-trash-o"></i> <?= lang('delete_quotes') ?>
                            </a>-->
							<?php } ?>
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
                    <?php echo form_open("Down_Payment/contract_list"); ?>
                    <div class="row">
						<!--<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="product_id"><?= lang("product"); ?></label>
                                <?php
                                $pr[""] = "";
								if(is_array(isset($products) ?$products  : (''))){
                                foreach ($products as $product) {
                                    $pr[$product->id] = $product->name . " | " . $product->code ;	
                                }}
                                echo form_dropdown('product_id',isset($pr) ?$pr  : (''), (isset($_POST['product_id']) ? $_POST['product_id'] : ""), 'class="form-control" id="product_id" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("product") . '"');
                                ?>
                            </div>
                        </div>-->						
						<!--<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="customer"><?= lang("customer"); ?></label>
                                <?php echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="customer" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer") . '"'); ?>
                            </div>
                        </div>-->
                        <!--<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>

                            </div>
                        </div>-->
						
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
                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="biller"><?= lang("dealer"); ?></label>
                                <?php
                                $bl[""] = "";
								if(is_array(isset($dealer) ?$dealer  : (''))){
                                foreach ($dealer as $dealer) {
                                    $bl[$dealer->id] = $dealer->company != '-' ? $dealer->company : $dealer->name;
                                }}
                                echo form_dropdown('dealer', isset($bl) ?$bl  : (''), (isset($_POST['dealer']) ? $_POST['dealer'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("dealer") . '"');
                                ?>
                            </div>
                        </div>
						
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="group_loans"><?= lang("group_loans"); ?></label>
                                <?php
                                $gl[""] = "";
								if(is_array(isset($group_Loan) ?$group_Loan  : (''))){
                                foreach ($group_Loan as $gr_loan) {
                                    $gl[$gr_loan->id] = $gr_loan->name;
                                }}
                                echo form_dropdown('gr_loan', isset($gl) ?$gl  : (''), (isset($_POST['gr_loan']) ? $_POST['gr_loan'] : ""), 'class="form-control" id="gr_loan" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("group_loans") . '"');
                                ?>
                            </div>
                        </div>
						<div class="col-sm-4">
							<div class="form-group">
								<?= lang("start_date", "start_date"); ?>
								<?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date" '); ?>
							</div>
						</div>
						
						<div class="col-sm-4">
							<div class="form-group">
								<?= lang("end_date", "end_date"); ?>
								<?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date" ' ); ?>
							</div>
						</div>
                        <?php if($this->Settings->product_serial) { ?>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?= lang('serial_no', 'serial'); ?>
                                    <?= form_input('serial', '', 'class="form-control tip" id="serial"'); ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <div class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
				
				<div class="clearfix"></div>
				<p style="font-size:20px; text-align:center;">  
					<B> <?= lang("loan_approved"); ?>  </B>
				</p>
                <div class="table-responsive">
                    <table id="QUData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
							<th><?php echo $this->lang->line("customer_id"); ?></th>
                            <th><?php echo $this->lang->line("reference_no"); ?></th>
							<th><?php echo $this->lang->line("group_loans"); ?></th>
                            <th><?php echo $this->lang->line("customer_en"); ?></th>
                            <th><?php echo $this->lang->line("customer_kh"); ?></th>
                            <th><?php echo $this->lang->line("approved_date"); ?></th>
                            <th><?php echo $this->lang->line("c.o_name"); ?></th>
                            <th><?php echo $this->lang->line("branch"); ?></th>
							<th><?php echo $this->lang->line("loan_type"); ?></th>
							<th><?php echo $this->lang->line("rate"); ?></th>
							<th><?php echo $this->lang->line("term"); ?></th>
							<th><?php echo $this->lang->line("pay_term"); ?></th>
                            <th><?php echo $this->lang->line("loan_request"); ?></th>                           
							<th><?php echo $this->lang->line("disburse"); ?></th>
							<th><?php echo $this->lang->line("remaining"); ?></th>
							<th><?php echo $this->lang->line("currency"); ?></th>
                            <th><?php echo $this->lang->line("status"); ?></th>
                            <th><?php echo $this->lang->line("mfi"); ?></th>
                            <th style="width:115px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
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
							<th></th>
                            <th></th>
                            <th></th>
							<th></th>
							<th></th>
                            <th style="width:115px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
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