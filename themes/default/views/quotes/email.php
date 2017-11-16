<style>
	.stick-button {
		position: -webkit-sticky;
		position: sticky;
		top: 15px;
	}
</style>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-file"></i><?= lang("quote_no") . '. ' . $inv->id; ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"
                                                                                  data-placement="left"
                                                                                  title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <?php if ($inv->attachment) { ?>
                            <li>
                                <a href="<?= site_url('welcome/download/' . $inv->attachment) ?>">
                                    <i class="fa fa-chain"></i> <?= lang('attachment') ?>
                                </a>
                            </li>
                        <?php } ?>
                        <li><a href="<?= site_url('quotes/edit/' . $inv->id) ?>"><i
                                    class="fa fa-edit"></i> <?= lang('edit_quote') ?></a></li>
                        <li><a href="<?= site_url('sales/add/' . $inv->id) ?>"><i
                                    class="fa fa-plus-circle"></i> <?= lang('create_invoice') ?></a></li>
                        <li><a href="<?= site_url('quotes/email/' . $inv->id) ?>" data-target="#myModal"
                               data-toggle="modal"><i class="fa fa-envelope-o"></i> <?= lang('send_email') ?></a></li>
                        <li><a href="<?= site_url('quotes/pdf/' . $inv->id) ?>"><i
                                    class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
                        <!--<li><a href="<?= site_url('quotes/excel/' . $inv->id) ?>"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>-->
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
				<h1> Waiting for API with credit boureau Cambodia (CBC) !!! <h1>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {

		$('.checkbox.apv').on('ifChanged', function(){
			$(".rej").iCheck('uncheck');
		});
		
		$('.checkbox.rej').on('ifChanged', function(){
			$(".apv").iCheck('uncheck');
		});
	});
</script>
