<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('update_quantity'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <?php
                $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("products/update_quantity", $attrib)
                ?>
                <div class="row">
                    <div class="col-md-12">


                        <div class="well well-small">
                            <a href="<?php echo base_url(); ?>assets/csv/sample_product_quantity.csv"
                               class="btn btn-primary pull-right"><i
                                    class="fa fa-download"></i> <?= lang("download_sample_file") ?></a>
                            <span class="text-warning"><?= lang("csv1"); ?></span><br/><?= lang("csv2"); ?> <span
                                class="text-info">(<?= lang("code") . ', ' . lang("quantity") . ', ' . lang("option_id") . ', ' . lang("warehouse_id") . ', ' . lang("cost"); ?>
                                )</span> <?= lang("csv3"); ?>

                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="csv_file"><?= lang("upload_file"); ?></label>
                                <input type="file" name="userfile" class="form-control file" data-show-upload="false"
                                       data-show-preview="false" id="csv_file" required="required"/>
                            </div>

                            <div class="form-group">
                                <?php echo form_submit('update', $this->lang->line("update_quantity"), 'class="btn btn-primary"'); ?>
                            </div>
                        </div>
                    </div>
                    <?= form_close(); ?>

                </div>

            </div>
        </div>
    </div>
</div>

