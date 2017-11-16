<?php
$v = "";

if ($this->input->post('sproduct')) {
    $v .= "&product=" . $this->input->post('sproduct');
}
if ($this->input->post('category')) {
    $v .= "&category=" . $this->input->post('category');
}
if ($this->input->post('start_date')) {
    $v .= "&start_date=" . $this->input->post('start_date');
}
if ($this->input->post('end_date')) {
    $v .= "&end_date=" . $this->input->post('end_date');
}
if ($this->input->post('cf1')) {
    $v .= "&cf1=" . $this->input->post('cf1');
}
if ($this->input->post('cf2')) {
    $v .= "&cf2=" . $this->input->post('cf2');
}
if ($this->input->post('cf3')) {
    $v .= "&cf3=" . $this->input->post('cf3');
}
if ($this->input->post('cf4')) {
    $v .= "&cf4=" . $this->input->post('cf4');
}
if ($this->input->post('cf5')) {
    $v .= "&cf5=" . $this->input->post('cf5');
}
if ($this->input->post('cf6')) {
    $v .= "&cf6=" . $this->input->post('cf6');
}
?>
<style type="text/css" media="screen">
    #PRData td:nth-child(6), #PRData td:nth-child(7) {
        text-align: left;
    }
    <?php if($Owner || $Admin || $this->session->userdata('show_cost')) { ?>
    #PRData td:nth-child(8) {
        text-align: right;
    }
    <?php } ?>
</style>
<script>
    var oTable;
    $(document).ready(function () {
        oTable = $('#PRData').dataTable({
            "aaSorting": [[2, "asc"], [3, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
			
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('products/getProducts'.($warehouse_id ? '/'.$warehouse_id : '').'/?v=1'.$v) ?>',
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
                nRow.className = "product_link";
                return nRow;
            },
            "aoColumns": [
                {"bSortable": false, "mRender": checkbox}, {
                    "bSortable": false,
                    "mRender": img_hl
                }, null, null, null /*{"mRender": formatQuantity2}*/, {"bSortable": false}
            ]
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('product_code');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('product_name');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('type');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('category');?>]", filter_type: "text", data: []}
            //{column_number: 6, filter_default_label: "[<?=lang('type');?>]", filter_type: "text", data: []},
			//{column_number: 5, filter_default_label: "[<?=lang('quantity');?>]", filter_type: "text", data: []}
        ], "footer");

    });
</script>
<?php 
if ($Owner) {
    echo form_open('products/product_actions'.($warehouse_id ? '/'.$warehouse_id : ''), 'id="action-form"');
} 
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('products') . ' (' . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . ')'; ?>
        </h2>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li>
							<a href="<?= site_url('products/add') ?>">
								<i class="fa fa-plus-circle"></i> <?= lang('add_product') ?>
							</a>
						</li>

                        <li>
							<a href="#" id="barcodeProducts" data-action="barcodes">
								<i class="fa fa-print"></i> <?= lang('print_barcodes') ?>
							</a>
						</li>
                        <li>
                            <a href="#" id="sync_quantity" data-action="sync_quantity">
                                <i class="fa fa-arrows-v"></i> <?= lang('sync_quantity') ?>
                            </a>
                        </li>
						<?php if ($Owner || $Admin) {?>
							<li>
								<a href="#" id="excel" data-action="export_excel">
									<i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?>
								</a>
							</li>
							
							<li>
								<a href="#" id="pdf" data-action="export_pdf">
									<i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?>
								</a>
							</li>
							
							<li>
								<a href="<?= site_url('products/import_csv'); ?>">
									<i class="fa fa-file-text-o"></i>
									<span class="text"> <?= lang('import_products'); ?></span>
								</a>
							</li>
							
							<li>
								<a href="<?= site_url('products/update_quantity'); ?>">
									<i class="fa fa-file-text-o"></i>
									<span class="text"> <?= lang('update_quantity'); ?></span>
								</a>
							</li>
							
							<li>
								<a href="<?= site_url('products/update_price'); ?>">
									<i class="fa fa-file-text-o"></i>
									<span class="text"> <?= lang('update_price'); ?></span>
								</a>
							</li>
						<?php }else{ ?>
							<?php if($GP['products-export']) { ?>
								<li>
									<a href="#" id="excel" data-action="export_excel">
										<i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?>
									</a>
								</li>
								
								<li>
									<a href="#" id="pdf" data-action="export_pdf">
										<i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?>
									</a>
								</li>
							<?php }?>
							
							<?php if($GP['products-import']) { ?>
								<li>
									<a href="<?= site_url('products/import_csv'); ?>">
										<i class="fa fa-file-text-o"></i>
										<span class="text"> <?= lang('import_products'); ?></span>
									</a>
								</li>
								
								<li>
									<a href="<?= site_url('products/update_quantity'); ?>">
										<i class="fa fa-file-text-o"></i>
										<span class="text"> <?= lang('update_quantity'); ?></span>
									</a>
								</li>
								
								<li>
									<a href="<?= site_url('products/update_price'); ?>">
										<i class="fa fa-file-text-o"></i>
										<span class="text"> <?= lang('update_price'); ?></span>
									</a>
								</li>
							<?php }?>
						<?php }?>					
						
                        <li class="divider"></li>
                        <!--<li><a href="#" class="bpo" title="<?= $this->lang->line("delete_products") ?>"
                               data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>" data-html="true" data-placement="left"><i class="fa fa-trash-o"></i> <?= lang('delete_products') ?></a></li>-->
                    </ul>
                </li>
                <?php if (!empty($warehouses)) { ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("warehouses") ?>"></i></a>
                        <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?= site_url('products') ?>"><i class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a></li>
                            <li class="divider"></li>
                            <?php
                            foreach ($warehouses as $warehouse) {
                               echo '<li><a href="' . site_url('products/index/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </div>

    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('list_results'); ?></p>
                <div id="form">
					
                </div>

                <div class="clearfix"></div>

                <div class="table-responsive">
                    <table id="PRData" class="scroll table table-bordered table-condensed table-hover table-striped">
                        <thead>
                        <tr class="primary">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
                            <!--<th style="min-width:40px; width: 40px; text-align: center;"><?php echo $this->lang->line("image"); ?></th>-->
                            <th style="min-width:20%; width: 20%;"><?= lang("product_code") ?></th>
                            <th style="min-width:20%; width: 20%;"><?= lang("product_name") ?></th>
                            <th style="min-width:20%; width: 20%;"><?= lang("type") ?></th>
                            <th style="min-width:20%; width: 20%;"><?= lang("category")?></th>
                            <!--<th><?= lang("type") ?></th>-->
							<!--<th style="min-width:20%; width: 20%;"><?= lang("quantity")?></th>-->
                            <th style="min-width:65px; text-align:center;"><?= lang("actions") ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="10" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                        </tr>
                        </tbody>
						<tfoot class="dtFilter">
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <!--<th style="min-width:40px; width: 40px; text-align: center;"><?php echo $this->lang->line("image"); ?></th>-->
                            <th></th>
							<th></th>
							<th></th>
                            <th></th>
                            <th style="width:65px; text-align:center;"><?= lang("actions") ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#form').hide();
    $('.toggle_down').click(function () {
        $("#form").slideDown();
        return false;
    });
    $('.toggle_up').click(function () {
        $("#form").slideUp();
        return false;
    });
	$(document).ready(function(){
		//$('#PRData_wrapper').css('width', '100%');
		//$('#PRData_wrapper').css('overflow-x', 'scroll');
		//$('#PRData_wrapper').css('white-space', 'nowrap');
	});
</script>
<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
<?php } ?>
