<?php
   //$this->erp->print_arrays($branch);
$v = "";
/* if($this->input->post('name')){
  $v .= "&product=".$this->input->post('product');
} */

if ($this->input->post('account')) {
    $v .= "&account=" . $this->input->post('account');
}
if ($this->input->post('start_date')) {
    $v .= "&start_date=" . $this->input->post('start_date');
}
if ($this->input->post('end_date')) {
    $v .= "&end_date=" . $this->input->post('end_date');
}

?>
<style type="text/css">
    .topborder div { border-top: 1px solid #CCC; }
</style>

<style>.table td:nth-child(6) {
        text-align: center;
    }</style>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-th-large"></i><?= lang('cash_book_reports'); ?> <?php
            if ($this->input->post('start_date')) {
                echo " From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
            }
            ?>
		</h2>
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
                <li class="dropdown"><a href="#" id="pdf" class="tip" title="<?= lang('download_pdf') ?>"><i
                            class="icon fa fa-file-pdf-o"></i></a></li>
                <li class="dropdown"><a href="#" id="xls" class="tip" title="<?= lang('download_xls') ?>"><i
                            class="icon fa fa-file-excel-o"></i></a></li>
                <li class="dropdown"><a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i
                            class="icon fa fa-file-picture-o"></i></a></li>
				<li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i
                                    class="icon fa fa-building-o tip" data-placement="left"
                                    title="<?= lang("billers") ?>"></i></a>
                            <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu"
                                aria-labelledby="dLabel">
                                <li><a href="<?= site_url('reports/cash_books') ?>"><i
                                            class="fa fa-building-o"></i> <?= lang('billers') ?></a></li>
                                <li class="divider"></li>
                                <?php
                                foreach ($billers as $biller) {
                                    echo '<li ' . ($biller_id && $biller_id == $biller->id ? 'class="active"' : '') . '><a href="' . site_url('reports/cash_books/0/' . $biller->id) . '"><i class="fa fa-building"></i>' . $biller->company . '</a></li>';
                                }
                                ?>
                            </ul>
                        </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('customize_report'); ?></p>
                <div id="form">
                    <?php echo form_open("reports/cash_books"); ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="user"><?= lang("account_name"); ?></label>
                                <?php
                                $accounntCode = $this->db;
                                $accOption = $accounntCode->select('*')->from('gl_charts')->where('bank', 1)->get()->result();
                                $account_[""] = " ";
                                foreach ($accOption as $a) {
                                    $account_[$a->accountcode] = $a->accountcode . " " . $a->accountname;
                                }
                                echo form_dropdown('account', $account_, (isset($_POST['account']) ? $_POST['account'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("account") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
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
				<p style="font-size:20px; text-align:center;">  
					<B> <?= lang("cash_book_reports"); ?>  </B>
				</p>
                <div class="table-responsive">
                    <table id="registerTable" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped reports-table" style="width:100%;">
                        <thead>
							<tr>
								<th style="width:20%;"><?= lang('branches'); ?></th>
								<th style="width:40%;"><?= lang('accounts'); ?></th>
								<th style="width:40%;"><?= lang('balances'); ?></th>
								
							</tr>
                        </thead>
                        <tbody>
							<?php
								$total_amount = 0;
								foreach($branch as $br){
									if($br->account){
							?>
										<tr style="">
											<td colspan="3" style="font-size:16px; background-color:#def3fa;"> <b> <?= lang('branch'); ?> : <?= $br->name ?> <i class="fa fa-angle-double-right" aria-hidden="true"></i> </b> <?= $br->sangkat ?>  <?= $br->district ?> <?= $br->state ?></td>										
										</tr>
										<?php
											foreach($br->account as $account){
												$total_amount += $account->amount;
										?>
											<tr>
												<td colspan="2" style="padding-left:30%"><?= lang('account'); ?>: <?= $account->accountcode; ?> <?= $account->accountname; ?></td>
												<td style="text-align:right; padding-right:13%;"><?= $this->erp->formatMoney($account->amount); ?> <?= $df_currency->name; ?></td>
											</tr>
										<?php
											}
										?>
							<?php
									}
								}
								$total_amounts = $total_amount;
								
							?>
							
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <td colspan="2"></td>
							<td style="text-align:right; padding-right:12%; font-size:18px; color:red;"> <?= $this->erp->formatMoney($total_amounts); ?> <?= $df_currency->name; ?></td>								
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
        $('#form').hide();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
		/*
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getRrgisterlogs/pdf/?v=1'.$v)?>";
            return false;
        });
		*/
		$('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/cash_books/pdf')?>";
            return false;
        });
        $('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/cash_books/0/0/xls/?v=1'.$v)?>";
            return false;
        });
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