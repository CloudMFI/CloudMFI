
<style>
	td:hover {  cursor: pointer; }
	#PayRData td:nth-child(6),  #PayRData tfoot th:nth-child(6) { text-align: left; }

	#PayRData {
		overflow-x: scroll;
		max-width: 100%;
		min-height: 300px;
		display: block;
		white-space: nowrap;
		cursor: pointer;
	}
	.hide_column{
		display:none;
	}
	
	@media print{
		#PayRData_length{
			display:none;
		}
		#PayRData_filter{
			display:none;
		}
		#PayRData_info{
			display:none;
		}
		#dtFilter-filter--PayRData-1, #dtFilter-filter--PayRData-2, #dtFilter-filter--PayRData-3, #dtFilter-filter--PayRData-4, #dtFilter-filter--PayRData-5, #dtFilter-filter--PayRData-6, #dtFilter-filter--PayRData-13{
			display:none;
		}
		.headerss{
			background-color: yellow;
		}
	}
	
</style>
<?php
$v = "";
/* if($this->input->post('name')){
  $v .= "&name=".$this->input->post('name');
} */
if ($this->input->post('payment_ref')) {
    $v .= "&payment_ref=" . $this->input->post('payment_ref');
}
if ($this->input->post('sale_ref')) {
    $v .= "&sale_ref=" . $this->input->post('sale_ref');
}

if ($this->input->post('supplier')) {
    $v .= "&supplier=" . $this->input->post('supplier');
}
if ($this->input->post('warehouse')) {
    $v .= "&biller=" . $this->input->post('biller');
}
if ($this->input->post('customer')) {
    $v .= "&customer=" . $this->input->post('customer');
}
if ($this->input->post('user')) {
    $v .= "&user=" . $this->input->post('user');
}
if ($this->input->post('start_date')) {
    $v .= "&start_date=" . $this->input->post('start_date');
}
if ($this->input->post('end_date')) {
    $v .= "&end_date=" . $this->input->post('end_date');
}
if (isset($biller_id)) {
    $v .= "&biller_id=" . $biller_id;
}

?>
<script>
    $(document).ready(function () {
        var pb = ['<?=lang('cash')?>', '<?=lang('CC')?>', '<?=lang('Cheque')?>', '<?=lang('paypal_pro')?>', '<?=lang('stripe')?>', '<?=lang('gift_card')?>'];

        function paid_by(x) {
            if (x == 'cash') {
                return pb[0];
            } else if (x == 'CC') {
                return pb[1];
            } else if (x == 'Cheque') {
                return pb[2];
            } else if (x == 'ppp') {
                return pb[3];
            } else if (x == 'stripe') {
                return pb[4];
            } else if (x == 'gift_card') {
                return pb[5];
            } else {
                return x;
            }
        }

        function ref(x) {
            return (x != null) ? x : ' ';
        }
		function alginT(){
			
		}

        var oTable = $('#PayRData').dataTable({
            //"aaSorting": [[2, "desc"]],
            //"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "aLengthMenu": [[10, 50, 100, 250, 500], [10, 50, 100, 250, 500]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('reports/getDailyLoanReportDetaills/?v=1' . $v) ?>',
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
            }, null,null, {"mRender": ref} ,{"mRender": ref} ,null, null , {"mRender": currencyFormat},{"mRender": currencyFormat}, {"mRender": currencyFormat} , {"mRender": currencyFormat}, {"mRender": currencyFormat} ,{"mRender": currencyFormat},null],
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                
            },
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var total = 0;
				var owed = 0;
				var princple = 0;
				var interest = 0;
				var penalty = 0;
				var services = 0;
                for (var i = 0; i < aaData.length; i++) {
					owed += parseFloat(aaData[aiDisplay[i]][7]);
					princple += parseFloat(aaData[aiDisplay[i]][8]);
					interest += parseFloat(aaData[aiDisplay[i]][9]);
					penalty += parseFloat(aaData[aiDisplay[i]][10]);
					services += parseFloat(aaData[aiDisplay[i]][11]);
					total += parseFloat(aaData[aiDisplay[i]][12]);
                }
                var nCells = nRow.getElementsByTagName('th');
				nCells[7].innerHTML = currencyFormat(parseFloat(owed));
				nCells[8].innerHTML = currencyFormat(parseFloat(princple));
				nCells[9].innerHTML = currencyFormat(parseFloat(interest));
				nCells[10].innerHTML = currencyFormat(parseFloat(penalty));
				nCells[11].innerHTML = currencyFormat(parseFloat(services));
                nCells[12].innerHTML = currencyFormat(parseFloat(total));
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('date');?> (dd-mm-yyyy)]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('loan_term');?>]", filter_type: "text", data: []},
			{column_number: 3, filter_default_label: "[<?=lang('payment_ref');?>]", filter_type: "text", data: []},
			{column_number: 4, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
			{column_number: 5, filter_default_label: "[<?=lang('branch');?>]", filter_type: "text", data: []},
			{column_number: 6, filter_default_label: "[<?=lang('by_co');?>]", filter_type: "text", data: []},
			{column_number: 7, filter_default_label: "[<?=lang('owed');?>]", filter_type: "text", data: []},
			{column_number: 8, filter_default_label: "[<?=lang('princple');?>]", filter_type: "text", data: []},
			{column_number: 9, filter_default_label: "[<?=lang('interest');?>]", filter_type: "text", data: []},
			{column_number: 10, filter_default_label: "[<?=lang('penalty_days');?>]", filter_type: "text", data: []},
			{column_number: 11, filter_default_label: "[<?=lang('services');?>]", filter_type: "text", data: []},
			{column_number: 12, filter_default_label: "[<?=lang('total_amount');?>]", filter_type: "text", data: []},
			{column_number: 13, filter_default_label: "[<?=lang('paid_by');?>]", filter_type: "text", data: []},
        ], "footer");

    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form').hide();
        <?php if ($this->input->post('biller')) { ?>
        $('#rbiller').select2({ allowClear: true });
        <?php } ?>
        <?php if ($this->input->post('supplier')) { ?>
        $('#rsupplier').val(<?= $this->input->post('supplier') ?>).select2({
            minimumInputLength: 1,
            allowClear: true,
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: "<?= site_url('suppliers/getSupplier') ?>/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "suppliers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
        $('#rsupplier').val(<?= $this->input->post('supplier') ?>);
        <?php } ?>
        <?php if ($this->input->post('customer')) { ?>
        $('#rcustomer').val(<?= $this->input->post('customer') ?>).select2({
            minimumInputLength: 1,
            allowClear: true,
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: "<?= site_url('customers/getCustomer') ?>/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
        <?php } ?>
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
    echo form_open('reports/payments_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-money"></i><?= lang('daily_loan_collection_details'); ?> <?php
            if ($this->input->post('start_date')) {
                echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
            }
            ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>"><i
                            class="icon fa fa-toggle-up"></i></a></li>
                <li class="dropdown"><a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>"><i
                            class="icon fa fa-toggle-down"></i></a></li>
            </ul>
        </div>
        <div class="box-icon">
            <ul class="btn-tasks">
                <!--<li class="dropdown"><a href="#" id="pdf" data-action="export_pdf" class="tip" title="<?= lang('download_pdf') ?>"><i
                            class="icon fa fa-file-pdf-o"></i></a></li>-->
                <!--<li class="dropdown"><a href="#" id="excel" data-action="export_excel" class="tip" title="<?= lang('download_xls') ?>"><i
                            class="icon fa fa-file-excel-o"></i></a></li>-->
                <!--<li class="dropdown"><a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i
                            class="icon fa fa-file-picture-o"></i></a></li>-->
				<li class="dropdown">
						<!--<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i
								class="icon fa fa-building-o tip" data-placement="left"
								title="<?= lang("billers") ?>"></i></a>-->
						<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu"
							aria-labelledby="dLabel">
							<li><a href="<?= site_url('reports/payments') ?>"><i
										class="fa fa-building-o"></i> <?= lang('billers') ?></a></li>
							<li class="divider"></li>
							<?php
							foreach ($billers as $biller) {
								echo '<li ' . ($biller_id && $biller_id == $biller->id ? 'class="active"' : '') . '><a href="' . site_url('reports/payments/'. $biller->id) . '"><i class="fa fa-building"></i>' . $biller->company . '</a></li>';
							}
							?>
						</ul>
					</li>
            </ul>
        </div>
    </div>
<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?php form_close(); ?>
<?php } ?>
<?php 
?> 
	<div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('customize_report'); ?></p>

                <div id="form">

                    <?php echo form_open("reports/getDailyLoanReportDetaills"); ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("payment_ref", "payment_ref"); ?>
                                <?php echo form_input('payment_ref', (isset($_POST['payment_ref']) ? $_POST['payment_ref'] : ""), 'class="form-control tip" id="payment_ref"'); ?>

                            </div>
                        </div> 
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="rbiller"><?= lang("branch"); ?></label>
                                <?php
                                $bl[''] = '';
                                foreach ($billers as $biller) {
                                    $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                }
                                echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="form-control" id="rbiller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
                                ?>
                            </div>
                        </div> 
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="user"><?= lang("by_co"); ?></label>
                                <?php
                                $us[""] = "";
                                foreach ($users as $user) {
                                    $us[$user->id] = $user->first_name . " " . $user->last_name;
                                }
                                echo form_dropdown('user', $us, (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("user") . '"');
                                ?>
                            </div>
                        </div>
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
                    </div>
                    <div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
                <div class="clearfix"></div>

				<!--<div class="table-responsive">-->
                <div>
				<!--<p style="font-size:20px; text-align:center;"> 
					<b> <?= $setting->site_name; ?> </b> <br>
					<?= lang("loans_collection_details"); ?>  
				</p>-->
				<p style="font-size:20px; text-align:center;">  
					 <B> <?= lang("loans_collection_details"); ?>  <?= date('d/m/Y'); ?>   </B>
				</p>
                    <table id="PayRData"
                           class="table table-bordered table-hover table-striped table-condensed reports-table">
                        <thead>
                        <tr>
							<th style="min-width:5%; width: 5%; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
                            <th><?= lang("date"); ?></th>
							<th><?= lang("loan_term"); ?></th>
                            <th><?= lang("payment_ref"); ?></th>
							<th><?= lang("customer"); ?></th>
                            <th><?= lang("branch"); ?></th>
							<th><?= lang("by_co"); ?></th>
							<th><?= lang("owed"); ?></th>
							<th><?= lang("princple"); ?></th>
							<th><?= lang("interest"); ?></th>
							<th><?= lang("penalty"); ?></th>
							<th><?= lang("services"); ?></th>
                            <th><?= lang("total_amount"); ?></th>
                            <th><?= lang("paid_by"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="9" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
							<th style="min-width:5%; width: 5%; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
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
                            <th><?= lang("amount"); ?></th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
		/*
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getPaymentsReport/pdf/?v=1'.$v)?>";
            return false;
        });
        $('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getPaymentsReport/0/xls/?v=1'.$v)?>";
            return false;
        });
		*/
        $('#image').click(function (event) {
            event.preventDefault();
            html2canvas($('.box'), {
                onrendered: function (canvas) {
                    var img = canvas.toDataURL()
                    window.open(img);
                }
            });
            return false;
        });
    });
</script>