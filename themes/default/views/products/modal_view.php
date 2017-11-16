<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?= $product->name; ?></h4>
        </div>
        <div class="modal-body">

            <div class="row">
                <div class="col-xs-5">
                    <img id="pr-image" src="<?= base_url() ?>assets/uploads/<?= $product->image ?>"
                    alt="<?= $product->name ?>" class="img-responsive img-thumbnail"/>

                    <div id="multiimages" class="padding10">
                        <?php if (!empty($images)) {
                            echo '<a class="img-thumbnail change_img" href="' . base_url() . 'assets/uploads/' . $product->image . '" style="margin-right:5px;"><img class="img-responsive" src="' . base_url() . 'assets/uploads/thumbs/' . $product->image . '" alt="' . $product->image . '" style="width:' . $Settings->twidth . 'px; height:' . $Settings->theight . 'px;" /></a>';
                            foreach ($images as $ph) {
                                echo '<div class="gallery-image" style="display:inline;"><a class="img-thumbnail change_img" href="' . base_url() . 'assets/uploads/' . $ph->photo . '" style="margin-right:5px;"><img class="img-responsive" src="' . base_url() . 'assets/uploads/thumbs/' . $ph->photo . '" alt="' . $ph->photo . '" style="width:' . $Settings->twidth . 'px; height:' . $Settings->theight . 'px;" /></a>';
                                echo '</div>';
                            }
                        }
                        ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="col-xs-7">
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped dfTable table-right-left">
                            <tbody>
                                <tr>
                                    <td colspan="2" style="background-color:#FFF;"></td>
                                </tr>
                                <?php
                                foreach ($supplier as $supp) {
                                    ?>
                                <tr>
                                    <td><?= lang("Supplier"); ?></td>
                                    <td><?=$supp->name?></td>
                                </tr>
                                    <?php
                                }
                                ?>
                                <tr>
                                    <td style="width:30%;"><?= lang("barcode_qrcode"); ?></td>
                                    <td style="width:70%;"><?= $barcode ?>
                                        <?php $this->erp->qrcode('link', urlencode(site_url('products/view/' . $product->id)), 1); ?>
                                        <img
                                        src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                                        alt="<?= $product->name ?>" class="pull-right"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= lang("product_name"); ?></td>
                                    <td><?= $product->name; ?></td>
                                </tr>
                                <tr>
                                    <td><?= lang("product_code"); ?></td>
                                    <td><?= $product->code; ?></td>
                                </tr>
								<?php if ($category->name) { ?>
                                <tr>
                                    <td><?= lang("category"); ?></td>
                                    <td><?= $category->name; ?></td>
                                </tr>
								<?php } ?>
                                <?php if ($product->subcategory_id) { ?>
                                <tr>
                                    <td><?= lang("type"); ?></td>
                                    <td><?= $subcategory->name; ?></td>
                                </tr>
                                <?php } ?>
								<?php if ($product->type) { ?>
                                <tr>
                                    <td><?= lang("model"); ?></td>
                                    <td><?= lang($model->name); ?></td>
                                </tr>
								<?php } ?>
								<?php if ($product->type) { ?>
                                <tr>
                                    <td><?= lang("product_details"); ?></td>
                                    <td><?= lang($product->product_details); ?></td>
                                </tr>
								<?php } ?>
								
								
								
								
								
								
								
								<!---- me add -------->
								
								
								
								
								

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="clearfix"></div>
			</div>
			<?php if (!$Supplier || !$Customer) { ?>
			<div class="buttons">
				<div class="btn-group btn-group-justified">
					<div class="btn-group">
						<a onclick="window.open('<?= site_url('products/single_barcode/' . $product->id) ?>', 'erp_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;"
							href="#" class="tip btn btn-primary" title="<?= lang('barcode') ?>">
							<i class="fa fa-print"></i>
							<span class="hidden-sm hidden-xs"><?= lang('print_barcode') ?></span>
						</a>
					</div>
					<div class="btn-group">
						<a onclick="window.open('<?= site_url('products/single_label/' . $product->id) ?>', 'erp_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;"
							href="#" class="tip btn btn-primary" title="<?= lang('label') ?>">
							<i class="fa fa-print"></i>
							<span class="hidden-sm hidden-xs"><?= lang('print_label') ?></span>
						</a>
					</div>
					<div class="btn-group">
						<a onclick="window.open('<?= site_url('products/single_label2/' . $product->id) ?>', 'erp_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;"
							href="#" class="tip btn btn-primary" title="<?= lang('label_printer') ?>">
							<i class="fa fa-print"></i>
							<span class="hidden-sm hidden-xs"><?= lang('label_printer') ?></span>
						</a>
					</div>
					<div class="btn-group">
						<a href="<?= site_url('products/pdf/' . $product->id) ?>" class="tip btn btn-primary" title="<?= lang('pdf') ?>">
							<i class="fa fa-download"></i>
							<span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
						</a>
					</div>
					<?php if($product->type == 'standard') { ?>
					<div class="btn-group"><a data-target="#myModal2" data-toggle="modal"
						href="<?= site_url('products/add_adjustment/' . $product->id) ?>"
						class="tip btn btn-warning" title="<?= lang('adjust_quantity') ?>"><i
						class="fa fa-filter"></i> <span
						class="hidden-sm hidden-xs"><?= lang('adjust_quantity') ?></span>
					</a>
				</div>
				<?php } ?>
				<div class="btn-group">
					<a href="<?= site_url('products/edit/' . $product->id) ?>" class="tip btn btn-warning tip" title="<?= lang('edit_product') ?>">
						<i class="fa fa-edit"></i>
						<span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>
					</a>
				</div>
				<div class="btn-group">
					<a href="#" class="tip btn btn-danger bpo" title="<b><?= $this->lang->line("delete_product") ?></b>"
						data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= site_url('products/delete/' . $product->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"
						data-html="true" data-placement="top">
						<i class="fa fa-trash-o"></i>
						<span class="hidden-sm hidden-xs"><?= lang('delete') ?></span>
					</a>
				</div>
			</div>
			</div>
			<script type="text/javascript">
				$(document).ready(function () {
					$('.tip').tooltip();
				});
			</script>
			<?php } ?>
		</div>
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.change_img').click(function(event) {
            event.preventDefault();
            var img_src = $(this).attr('href');
            $('#pr-image').attr('src', img_src);
            return false;
        });
    });
</script>
