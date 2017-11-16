

<script type="text/javascript">
    <?php if ($this->session->userdata('remove_tols')) { ?>
    if (localStorage.getItem('toitems')) {
        localStorage.removeItem('toitems');
    }
    if (localStorage.getItem('toshipping')) {
        localStorage.removeItem('toshipping');
    }
    if (localStorage.getItem('toref')) {
        localStorage.removeItem('toref');
    }
    if (localStorage.getItem('to_warehouse')) {
        localStorage.removeItem('to_warehouse');
    }
    if (localStorage.getItem('tonote')) {
        localStorage.removeItem('tonote');
    }
    if (localStorage.getItem('from_warehouse')) {
        localStorage.removeItem('from_warehouse');
    }
    if (localStorage.getItem('todate')) {
        localStorage.removeItem('todate');
    }
    if (localStorage.getItem('tostatus')) {
        localStorage.removeItem('tostatus');
    }
    <?php $this->erp->unset_data('remove_tols');
} ?>

    var count = 1, an = 1, product_variant = 0, shipping = 0,
        product_tax = 0, total = 0,
        tax_rates = <?php echo json_encode($tax_rates); ?>, toitems = {},
        audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3'),
        audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
    $(document).ready(function () {
        <?php if ($Owner || $Admin) { ?>
        if (!localStorage.getItem('todate')) {
            $("#todate").datetimepicker({
                format: site.dateFormats.js_ldate,
                fontAwesome: true,
                language: 'erp',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', new Date());
        }
        $(document).on('change', '#todate', function (e) {
            localStorage.setItem('todate', $(this).val());
        });
        if (todate = localStorage.getItem('todate')) {
            $('#todate').val(todate);
        }
        <?php } ?>
        ItemnTotals();
        $("#add_item").autocomplete({
            //source: '<?= site_url('transfers/suggestions'); ?>',
            source: function (request, response) {
                if (!$('#from_warehouse').val()) {
                    $('#add_item').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    $('#add_item').focus();
                    return false;
                }
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('transfers/suggestions'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        warehouse_id: $("#from_warehouse").val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    if ($('#from_warehouse').val()) {
                        //bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        //    $('#add_item').focus();
                        //});
                    } else {
                        bootbox.alert('<?= lang('please_select_warehouse') ?>', function () {
                            $('#add_item').focus();
                        });
                    }
                    $(this).removeClass('ui-autocomplete-loading');
                    // $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    //audio_error.play();
                    //bootbox.alert('<?= lang('no_match_found') ?>', function () {
                    //$('#add_item').focus();
                    //});
                    $(this).removeClass('ui-autocomplete-loading');
                    // $(this).val('');
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_transfer_item(ui.item);
                    if (row)
                        $(this).val('');
                /*} else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>'); */
                }
            }
        });
        $('#add_item').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

        var to_warehouse;
        $('#to_warehouse').on("select2-focus", function (e) {
            to_warehouse = $(this).val();
        }).on("select2-close", function (e) {
            if ($(this).val() != '' && $(this).val() == $('#from_warehouse').val()) {
                $(this).select2('val', to_warehouse);
                bootbox.alert('<?= lang('please_select_different_warehouse') ?>');
            }
        });
        var from_warehouse;
        $('#from_warehouse').on("select2-focus", function (e) {
            from_warehouse = $(this).val();
        }).on("select2-close", function (e) {
            if ($(this).val() != '' && $(this).val() == $('#to_warehouse').val()) {
                $(this).select2('val', from_warehouse);
                bootbox.alert('<?= lang('please_select_different_warehouse') ?>');
            }
        });

    });
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_transfer'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("transfers/add", $attrib)
                ?>


                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-12">
                           <div class="panel panel-primary">
								<div class="panel-heading"><?= lang('add_transfer') ?></div>
                                <div class="panel-body" style="padding: 5px;">
								
									<?php if ($Owner || $Admin) { ?>
										
									<?php } ?>
						
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?= lang("from_branch", "from_branch"); ?>
                                            <?php 
											$wh[''] = '';
											foreach ($branch as $warehouse) {
												$wh[$warehouse->id] = $warehouse->name;
											}
											echo form_dropdown('from_warehouse', $wh, (isset($_POST['from_warehouse']) ? $_POST['from_warehouse'] : ''), 'id="from_warehouse" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("from_warehouse") . '" required="required" style="width:100%;" ');
                                            ?>
                                        </div>
                                    </div>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("to_branch", "to_branch"); ?>
											<?php
											
											echo form_dropdown('to_warehouse', $wh, (isset($_POST['to_warehouse']) ? $_POST['to_warehouse'] : ''), 'id="to_warehouse" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("to_warehouse") . '" required="required" style="width:100%;" ');
											?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("date", "date"); ?>
											<?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="todate" required="required"'); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("amount", "amount"); ?>
											<?php echo form_input('amount', (isset($_POST['amount']) ? $_POST['amount'] : ''), 'class="form-control" id="amount"');  ?>
										</div>
									</div>
									
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" id="sticker"> </div>

						
						<div class="from-group"><?php echo form_submit('add_transfer', $this->lang->line("submit"), 'class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
							<button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></button>
						</div>

                    </div>
					
                </div>
                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>
