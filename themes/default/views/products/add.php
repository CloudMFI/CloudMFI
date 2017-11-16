<?php
if (!empty($variants)) {
    foreach ($variants as $variant) {
        $vars[] = addslashes($variant->name);
    }
} else {
    $vars = array();
}
?>
<script type="text/javascript">
    $(document).ready(function () {
		$("#type").select2("destroy").empty().attr("placeholder", "<?= lang('select_type') ?>").select2({
            placeholder: "<?= lang('select_type') ?>", data: [
                {id: '', text: '<?= lang('select_type') ?>'}
            ]
        });
        $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({
            placeholder: "<?= lang('select_category_to_load') ?>", data: [
                {id: '', text: '<?= lang('select_category_to_load') ?>'}
            ]
        });
        $('#category').change(function () {
            var v = $(this).val();
            $('#modal-loading').show();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url('products/getSubCategories') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
                        if (scdata != null) {
                            $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
                                placeholder: "<?= lang('select_category_to_load') ?>",
                                data: scdata
                            });
                        }
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                    }
                });
            } else {
                $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({
                    placeholder: "<?= lang('select_category_to_load') ?>",
                    data: [{id: '', text: '<?= lang('select_category_to_load') ?>'}]
                });
            }
            $('#modal-loading').hide();
        });
		$('#subcategory').change(function () {
            var v = $(this).val();
            $('#modal-loading').show();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url('products/getTypes') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
                        if (scdata != null) {
                            $("#type").select2("destroy").empty().attr("placeholder", "<?= lang('select_type') ?>").select2({
                                placeholder: "<?= lang('select_type') ?>",
                                data: scdata
                            });
                        }
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                    }
                });
            } else {
                $("#type").select2("destroy").empty().attr("placeholder", "<?= lang('select_type') ?>").select2({
                    placeholder: "<?= lang('select_type') ?>",
                    data: [{id: '', text: '<?= lang('select_type') ?>'}]
                });
            }
            $('#modal-loading').hide();
        });
		
        $('#code').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
		$('#code').bind('change', function (e) {
            var code = $(this).val();
			$.ajax({
				type: 'GET',
				url: '<?= site_url('products/check_product_available'); ?>',
				data: {term:code},
				cache: false,
				success: function (data) {
					if(data == 1){
						alert('Product code already exists...');						
						$('#code').bootstrapValidator({
							feedbackIcons: {
								valid: 'fa fa-check',
								invalid: 'fa fa-times',
								validating: 'fa fa-refresh'
							}, excluded: [':disabled']
						});
						//document.getElementById("code").value = "";
					}
				},
				error: function(){
					alert('error ajax');
				}
			});
        });
		$("#code").on('keyup', function(){
			var code = $(this).val();
			$.ajax({
				type: 'GET',
				url: '<?= site_url('products/check_product_available'); ?>',
				data: {term:code},
				cache: false,
				success: function (data) {
					var parent = $(this).closest('.form-control');
					if(data == 0){
						parent.removeClass('error');
					}else{
						parent.addClass('error');
					}
				},
				error: function(){
					alert('error ajax');
				}
			});
			
		});
    });
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_product'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>

                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' =>'product-form', 'class' => 'form-horizontal');
                echo form_open_multipart("products/add", $attrib)
                ?>

                <div class="col-md-8">
					<div class="form-group all">
						<label class="control-label col-sm-3"><?= lang("product_name") ?></label>
						<div class="col-sm-9">
							<?= form_input('name', (isset($_POST['name']) ? $_POST['name'] : ($product ? $product->name : '')), 'class="form-control" id="name" required="required"'); ?>
						</div>
					</div>
					
					<div class="form-group all">
						<label class="control-label col-sm-3"><?= lang("product_code") ?></label>
						<div class="col-sm-9">
							<?= form_input('code', (isset($_POST['code']) ? $_POST['code'] : ($product ? $product->code : '')), 'class="form-control" id="code"  required="required"') ?>
						</div>
					</div>
					
					<div class="form-group all">
						<label class="control-label col-sm-3"><?= lang("category") ?></label>
						<div class="col-sm-9">
							<?php if ($Owner || $Admin) { ?><div class="input-group"><?php } ?>
								<?php
								if ($Owner || $Admin ) { 
								$cat[''] = "";
								foreach ($categories as $category) {
									$cat[$category->id] = $category->name;
								}
								echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ($product ? $product->category_id : '')), 'class="form-control select" id="category" placeholder="' . lang("select") . " " . lang("category") . '" required="required" style="width:100%"')
								?>
							
								<div class="input-group-addon no-print" style="padding: 2px 5px;"><a
										href="<?= site_url('system_settings/add_category'); ?>" id="add-supplier"
										class="external" data-toggle="modal" data-target="#myModal"><i
											class="fa fa-2x fa-plus-circle" id="addIcon"></i></a></div>
							</div>
							<?php }else{
								$cat[''] = "";
							foreach ($categories as $category) {
								$cat[$category->id] = $category->name;
							}
							echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ($product ? $product->category_id : '')), 'class="form-control select" id="category" placeholder="' . lang("select") . " " . lang("category") . '" required="required" style="width:100%"')
							?>
						<?php
						} 
						?>
						</div>
					</div>
					
					<div class="form-group all">
						<label class="control-label col-sm-3"><?= lang("type") ?></label>
						<div class="col-sm-9">
							<?php if ($Owner || $Admin) { ?><div class="input-group"><?php } ?>
							<?php
							if ($Owner || $Admin ) { 
								echo form_input('subcategory', ($product ? $product->subcategory_id : ''), 'class="form-control" id="subcategory"  placeholder="' . lang("select_category_to_load") . '"');
							?>
						
							<div class="input-group-addon no-print" style="padding: 2px 5px;"><a
									href="<?= site_url('system_settings/add_subcategory'); ?>" id="add-supplier"
									class="external" data-toggle="modal" data-target="#myModal"><i
										class="fa fa-2x fa-plus-circle" id="addIcon"></i></a></div>
						</div>
						<?php }else{
							echo form_input('subcategory', ($product ? $product->subcategory_id : ''), 'class="form-control" id="subcategory"  placeholder="' . lang("select_category_to_load") . '"');
						} ?>
						</div>
					</div>
					<!--
					<div class="form-group all">
						<label class="control-label col-sm-3"> <?= lang("type") ?></label>
						<div class="col-sm-9">
							<?php
								echo form_input('type', ($product ? $product->subcategory_id : ''), 'class="form-control" id="type"  placeholder="' . lang("select_type") . '"');
							?>
						
							<div class="input-group-addon no-print" style="padding: 2px 5px;"><a href="<?= site_url('system_settings/add_type'); ?>" id="add-supplier" class="external" data-toggle="modal" data-target="#myModal"><i class="fa fa-2x fa-plus-circle" id="addIcon"></i></a></div>
						</div>
					</div>
					-->
					<!--<div class="form-group all">
						<label class="control-label col-sm-3"><?= lang("model") ?></label>
						<div class="col-sm-9">
							<?php if ($Owner || $Admin) { ?><div class="input-group"><?php } ?>
							<?php
							if ($Owner || $Admin ) { 
								echo form_input('type', ($product ? $product->subcategory_id : ''), 'class="form-control" id="type"  placeholder="' . lang("select_type") . '"');
							?>
						
							<div class="input-group-addon no-print" style="padding: 2px 5px;"><a
									href="<?= site_url('system_settings/add_type'); ?>" id="add-supplier"
									class="external" data-toggle="modal" data-target="#myModal"><i
										class="fa fa-2x fa-plus-circle" id="addIcon"></i></a></div>
						</div>
						<?php }else{
							echo form_input('type', ($product ? $product->subcategory_id : ''), 'class="form-control" id="type"  placeholder="' . lang("select_type") . '"');
						} ?>
						</div>
					</div>

					
					<div class="form-group all">
                        <?= lang("color", "color") ?>
						<?= form_input('color', (isset($_POST['color']) ? $_POST['color'] : ($product ? $product->color : '')), 'class="form-control" id="color"  required="required"') ?>
                    </div>
					
					
					<div class="form-group all">
						<label class="control-label col-sm-3"><?= lang("product_image") ?></label>
						<div class="col-sm-9">
							<input id="product_image" type="file" name="product_image" data-show-upload="false"
                               data-show-preview="false" accept="image/*" class="form-control file">
						</div>
					</div>
					
					<div class="form-group all">
						<label class="control-label col-sm-3"><?= lang("product_gallery_images") ?></label>
						<div class="col-sm-9">
							<input id="images" type="file" name="userfile[]" multiple="true" data-show-upload="false"
                               data-show-preview="false" class="form-control file" accept="image/*">
						</div>
					</div>-->

                    <div id="img-details"></div>
					
					<div class="form-group all">
						<label class="control-label col-sm-3"></label>
						<div class="col-sm-9">
							<input type="checkbox" class="checkbox" value="1" name="inactive" id="inactive" <?= $this->input->post('inactive') ? 'checked="checked"' : ''; ?>>
							<label for="inactive" class="padding05">
								<?= lang('inactive'); ?>
							</label>
						</div>
					</div>
					<div class="form-group all">
						<label class="control-label col-sm-3"></label>
						<div class="col-sm-9">
							<input type="checkbox" value="1" name="group_loan" id="group_loan" />
							<label><?= lang("group_loans") ?></label>
						</div>
					</div>
					
					<div class="form-group all">
						<label class="control-label col-sm-3"><?= lang("product_details") ?></label>
						<div class="col-sm-9">
							<?= form_textarea('product_details', (isset($_POST['product_details']) ? $_POST['product_details'] : ($product ? $product->product_details : '')), 'class="form-control" id="details"'); ?>
						</div>
					</div>

					
					<div class="form-group all">
						<label class="control-label col-sm-3"></label>
						<div class="col-sm-9">
							<?php echo form_submit('add_product', $this->lang->line("add_product"), 'class="btn btn-primary"'); ?>
						</div>
					</div>

                </div>
				<!--
				<div class="col-md-6 col-md-offset-1">
					<div class="form-group all">
                        <?= lang("engline_number", "engline") ?>
                        <?= form_input('engline', (isset($_POST['name']) ? $_POST['engline'] : ($product ? $product->engline : '')), 'class="form-control" id="engline" required="required"'); ?>
                    </div>
					<div class="form-group all">
                        <?= lang("frame_number", "frame") ?>
                        <?= form_input('frame', (isset($_POST['name']) ? $_POST['frame'] : ($product ? $product->frame : '')), 'class="form-control" id="frame" required="required"'); ?>
                    </div>
					<div class="form-group all">
                        <?= lang("chassis_no", "chassis") ?>
                        <?= form_input('chassis', (isset($_POST['name']) ? $_POST['chassis'] : ($product ? $product->chassis : '')), 'class="form-control" id="chassis" required="required"'); ?>
                    </div>
					<div class="form-group all">
                        <?= lang("power", "power") ?>
						<?= form_input('power', (isset($_POST['name']) ? $_POST['power'] : ($product ? $product->power : '')), 'class="form-control" id="power" required="required"'); ?>
                    </div>
					<div class="form-group all">
                        <?= lang("product_year", "product_year") ?>
						<?= form_input('product_year', (isset($_POST['name']) ? $_POST['product_year'] : ($product ? $product->product_year : '')), 'class="form-control" id="product_year" required="required"'); ?>
                    </div>
					<div class="form-group all">
                        <?= lang("distance_mile", "distance") ?>
                        <?= form_input('distance', (isset($_POST['distance']) ? $_POST['distance'] : ($product ? $product->distance : '')), 'class="form-control" id="distance" required="required"'); ?>
                    </div>
                </div>
				-->
                <div class="col-md-12">
					
					
                    

                </div>
                <?= form_close(); ?>

            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3');
        var audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
        var items = {};
        <?php
        if($combo_items) {
            foreach($combo_items as $item) {
            //echo 'ietms['.$item->id.'] = '.$item.';';
                if($item->code) {
                    echo 'add_product_item('.  json_encode($item).');';
                }
            }
        }
        ?>
        <?=isset($_POST['cf']) ? '$("#extras").iCheck("check");': '' ?>
        $('#extras').on('ifChecked', function () {
            $('#extras-con').slideDown();
        });
        $('#extras').on('ifUnchecked', function () {
            $('#extras-con').slideUp();
        });
		<?= isset($_POST['inactive']) ? '$("#inactive").iCheck("check");': '' ?>
		<?= isset($_POST['promotion']) ? '$("#promotion").iCheck("check");': '' ?>
        $('#promotion').on('ifChecked', function (e) {
            $('#promo').slideDown();
        });
        $('#promotion').on('ifUnchecked', function (e) {
            $('#promo').slideUp();
        });
		
        $('.attributes').on('ifChecked', function (event) {
            $('#options_' + $(this).attr('id')).slideDown();
        });
        $('.attributes').on('ifUnchecked', function (event) {
            $('#options_' + $(this).attr('id')).slideUp();
        });
        //$('#cost').removeAttr('required');
        $('#type').change(function () {
            var t = $(this).val();
            if (t !== 'standard') {
                $('.standard').slideUp();
                $('#cost').attr('required', 'required');
                $('#track_quantity').iCheck('uncheck');
                $('form[data-toggle="validator"]').bootstrapValidator('addField', 'cost');
            } else {
                $('.standard').slideDown();
                $('#track_quantity').iCheck('check');
                $('#cost').removeAttr('required');
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'cost');
            }
            if (t !== 'digital') {
                $('.digital').slideUp();
                $('#digital_file').removeAttr('required');
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'digital_file');
            } else {
                $('.digital').slideDown();
                $('#digital_file').attr('required', 'required');
                $('form[data-toggle="validator"]').bootstrapValidator('addField', 'digital_file');
            }
            if (t !== 'combo') {
                $('.combo').slideUp();
            } else {
                $('.combo').slideDown();
            }
        });

        var t = $('#type').val();
        if (t !== 'standard') {
            $('.standard').slideUp();
            $('#cost').attr('required', 'required');
            $('#track_quantity').iCheck('uncheck');
            $('form[data-toggle="validator"]').bootstrapValidator('addField', 'cost');
        } else {
            $('.standard').slideDown();
            $('#track_quantity').iCheck('check');
            $('#cost').removeAttr('required');
            $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'cost');
        }
        if (t !== 'digital') {
            $('.digital').slideUp();
            $('#digital_file').removeAttr('required');
            $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'digital_file');
        } else {
            $('.digital').slideDown();
            $('#digital_file').attr('required', 'required');
            $('form[data-toggle="validator"]').bootstrapValidator('addField', 'digital_file');
        }
        if (t !== 'combo') {
            $('.combo').slideUp();
        } else {
            $('.combo').slideDown();
        }

        $("#add_item").autocomplete({
            source: '<?= site_url('products/suggestions'); ?>',
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_product_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_product_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_product_item(ui.item);
                    if (row) {
                        $(this).val('');
                    }
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_product_found') ?>');
                }
            }
        });
        $('#add_item').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });
        <?php
        if($this->input->post('type') == 'combo') {
            $c = sizeof($_POST['combo_item_code']);
            for ($r = 0; $r <= $c; $r++) {
                if(isset($_POST['combo_item_code'][$r]) && isset($_POST['combo_item_quantity'][$r]) && isset($_POST['combo_item_price'][$r])) {
                    $items[] = array('id' => $_POST['combo_item_id'][$r], 'name' => $_POST['combo_item_name'][$r], 'code' => $_POST['combo_item_code'][$r], 'qty' => $_POST['combo_item_quantity'][$r], 'price' => $_POST['combo_item_price'][$r]);
                }
            }
            echo '
            var ci = '.json_encode($items).';
            $.each(ci, function() { add_product_item(this); });
            ';
        }
        ?>
        function add_product_item(item) {
            if (item == null) {
                return false;
            }
            item_id = item.id;
            if (items[item_id]) {
                items[item_id].qty = (parseFloat(items[item_id].qty) + 1).toFixed(2);
            } else {
                items[item_id] = item;
            }

            $("#prTable tbody").empty();
            $.each(items, function () {
                var row_no = this.id;
                var newTr = $('<tr id="row_' + row_no + '" class="item_' + this.id + '"></tr>');
                tr_html = '<td><input name="combo_item_id[]" type="hidden" value="' + this.id + '"><input name="combo_item_name[]" type="hidden" value="' + this.name + '"><input name="combo_item_code[]" type="hidden" value="' + this.code + '"><span id="name_' + row_no + '">' + this.name + ' (' + this.code + ')</span></td>';
				tr_html += '<td><input class="form-control text-center" name="combo_item_quantity_unit[]" type="text" value="' + formatDecimal(this.qty) + '" data-id="' + row_no + '" data-item="' + this.id + '" id="quantity_unit_' + row_no + '" onClick="this.select();"></td>';
                tr_html += '<td><input class="form-control text-center" name="combo_item_price[]" type="text" value="' + formatDecimal(this.price) + '" data-id="' + row_no + '" data-item="' + this.id + '" id="combo_item_price_' + row_no + '" onClick="this.select();"></td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip del" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#prTable");
            });
            $('.item_' + item_id).addClass('warning');
            //audio_success.play();
            return true;

        }

        $(document).on('click', '.del', function () {
            var id = $(this).attr('id');
            delete items[id];
            $(this).closest('#row_' + id).remove();
        });
        var su = 2;
        $('#addSupplier').click(function () {
            if (su <= 5) {
                $('#supplier_1').select2('destroy');
                var html = '<div style="clear:both;height:15px;"></div><div class="row"><div class="col-md-8 col-sm-8 col-xs-8"><input type="hidden" name="supplier_' + su + '", class="form-control" id="supplier_' + su + '" placeholder="<?= lang("select") . ' ' . lang("supplier") ?>" style="width:100%;display: block !important;" /></div><div class="col-md-4 col-sm-4 col-xs-4"><input type="text" name="supplier_' + su + '_price" class="form-control tip" id="supplier_' + su + '_price" placeholder="<?= lang('supplier_price') ?>" /></div></div>';
                $('#ex-suppliers').append(html);
                var sup = $('#supplier_' + su);
                suppliers(sup);
                su++;
            } else {
                bootbox.alert('<?= lang('max_reached') ?>');
                return false;
            }
        });

        var _URL = window.URL || window.webkitURL;
        $("input#images").on('change.bs.fileinput', function () {
            var ele = document.getElementById($(this).attr('id'));
            var result = ele.files;
            $('#img-details').empty();
            for (var x = 0; x < result.length; x++) {
                var fle = result[x];
                for (var i = 0; i <= result.length; i++) {
                    var img = new Image();
                    img.onload = (function (value) {
                        return function () {
                            ctx[value].drawImage(result[value], 0, 0);
                        }
                    })(i);
                    img.src = 'images/' + result[i];
                }
            }
        });
        var variants = <?=json_encode($vars);?>;
        $(".select-tags").select2({
            tags: variants,
            tokenSeparators: [","],
            multiple: true
        });
        $(document).on('ifChecked', '#attributes', function (e) {
            $('#attr-con').slideDown();
        });
        $(document).on('ifUnchecked', '#attributes', function (e) {
            $(".select-tags").select2("val", "");
            $('.attr-remove-all').trigger('click');
            $('#attr-con').slideUp();
        });
        $('#addAttributes').click(function (e) {
            e.preventDefault();
            var attrs_val = $('#attributesInput').val(), attrs;
            attrs = attrs_val.split(',');
            console.log(attrs);
            for (var i in attrs) {
                if (attrs[i] !== '') {
				   $('#attrTable').show().append('<tr class="attr"><td><input type="hidden" name="attr_name[]" value="' + attrs[i] + '"><span>' + attrs[i] + '</span></td><td class="quantity_unit text-center"><input type="hidden" name="attr_quantity_unit[]" value=""><span></span></td><td class="price text-right"><input type="hidden" name="attr_price[]" value="0"><span>0</span></span></td><td class="text-center"><i class="fa fa-times delAttr"></i></td></tr>');
                }
            }
        });
        $(document).on('click', '.delAttr', function () {
            $(this).closest("tr").remove();
        });
        $(document).on('click', '.attr-remove-all', function () {
            $('#attrTable tbody').empty();
            $('#attrTable').hide();
        });
        var row, warehouses = <?= json_encode($warehouses); ?>;
        $(document).on('click', '.attr td:not(:last-child)', function () {
            row = $(this).closest("tr");
            $('#aModalLabel').text(row.children().eq(0).find('span').text());
			$('#aquantity_unit').val(row.children().eq(1).find('input').val());
            //$('#aquantity').val(row.children().eq(3).find('input').val());
           // $('#acost').val(row.children().eq(4).find('span').text());
            $('#aprice').val(row.children().eq(2).find('span').text());
            $('#aModal').appendTo('body').modal('show');
        });

        $(document).on('click', '#updateAttr', function () {
            var wh = $('#awarehouse').val(), wh_name;
            $.each(warehouses, function () {
                if (this.id == wh) {
                    wh_name = this.name;
                }
            });
            //row.children().eq(1).html('<input type="hidden" name="attr_warehouse[]" value="' + wh + '"><input type="hidden" name="attr_wh_name[]" value="' + wh_name + '"><span>' + wh_name + '</span>');
			row.children().eq(1).html('<input type="hidden" name="attr_quantity_unit[]" value="' + $('#aquantity_unit').val() + '"><span>' + decimalFormat($('#aquantity_unit').val()) + '</span>');
            //row.children().eq(3).html('<input type="hidden" name="attr_quantity[]" value="' + $('#aquantity').val() + '"><span>' + decimalFormat($('#aquantity').val()) + '</span>');
            //row.children().eq(4).html('<input type="hidden" name="attr_cost[]" value="' + $('#acost').val() + '"><span>' + currencyFormat($('#acost').val()) + '</span>');
            row.children().eq(2).html('<input type="hidden" name="attr_price[]" value="' + $('#aprice').val() + '"><span>' + currencyFormat($('#aprice').val()) + '</span>');
            $('#aModal').modal('hide');
        });
    });

    <?php if ($product) { ?>
    $(document).ready(function () {
        var t = "<?=$product->type?>";
        if (t !== 'standard') {
            $('.standard').slideUp();
            $('#cost').attr('required', 'required');
            $('#track_quantity').iCheck('uncheck');
            $('form[data-toggle="validator"]').bootstrapValidator('addField', 'cost');
        } else {
            $('.standard').slideDown();
            $('#track_quantity').iCheck('check');
            $('#cost').removeAttr('required');
            $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'cost');
        }
        if (t !== 'digital') {
            $('.digital').slideUp();
            $('#digital_file').removeAttr('required');
            $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'digital_file');
        } else {
            $('.digital').slideDown();
            $('#digital_file').attr('required', 'required');
            $('form[data-toggle="validator"]').bootstrapValidator('addField', 'digital_file');
        }
        if (t !== 'combo') {
            $('.combo').slideUp();
            //$('#add_item').removeAttr('required');
            //$('form[data-toggle="validator"]').bootstrapValidator('removeField', 'add_item');
        } else {
            $('.combo').slideDown();
            //$('#add_item').attr('required', 'required');
            //$('form[data-toggle="validator"]').bootstrapValidator('addField', 'add_item');
        }
        $("#code").parent('.form-group').addClass("has-error");
        $("#code").focus();
        $("#product_image").parent('.form-group').addClass("text-warning");
        $("#images").parent('.form-group').addClass("text-warning");
        $.ajax({
            type: "get", async: false,
            url: "<?= site_url('products/getSubCategories') ?>/" + <?= $product->category_id ?>,
            dataType: "json",
            success: function (scdata) {
                if (scdata != null) {
                    $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
                        placeholder: "<?= lang('select_category_to_load') ?>",
                        data: scdata
                    });
                }
            }
        });
        <?php if ($product->supplier1) { ?>
        select_supplier('supplier1', "<?= $product->supplier1; ?>");
        $('#supplier_price').val("<?= $product->supplier1price == 0 ? '' : $this->erp->formatDecimal($product->supplier1price); ?>");
        <?php } ?>
        <?php if ($product->supplier2) { ?>
        $('#addSupplier').click();
        select_supplier('supplier_2', "<?= $product->supplier2; ?>");
        $('#supplier_2_price').val("<?= $product->supplier2price == 0 ? '' : $this->erp->formatDecimal($product->supplier2price); ?>");
        <?php } ?>
        <?php if ($product->supplier3) { ?>
        $('#addSupplier').click();
        select_supplier('supplier_3', "<?= $product->supplier3; ?>");
        $('#supplier_3_price').val("<?= $product->supplier3price == 0 ? '' : $this->erp->formatDecimal($product->supplier3price); ?>");
        <?php } ?>
        <?php if ($product->supplier4) { ?>
        $('#addSupplier').click();
        select_supplier('supplier_4', "<?= $product->supplier4; ?>");
        $('#supplier_4_price').val("<?= $product->supplier4price == 0 ? '' : $this->erp->formatDecimal($product->supplier4price); ?>");
        <?php } ?>
        <?php if ($product->supplier5) { ?>
        $('#addSupplier').click();
        select_supplier('supplier_5', "<?= $product->supplier5; ?>");
        $('#supplier_5_price').val("<?= $product->supplier5price == 0 ? '' : $this->erp->formatDecimal($product->supplier5price); ?>");
        <?php } ?>
        function select_supplier(id, v) {
            $('#' + id).val(v).select2({
                minimumInputLength: 1,
                data: [],
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
            });//.select2("val", "<?= $product->supplier1; ?>");
        }

        var whs = $('.wh');
        $.each(whs, function () {
            $(this).val($('#r' + $(this).attr('id')).text());
        });
    });
    <?php } ?>
</script>

<div class="modal" id="aModal" tabindex="-1" role="dialog" aria-labelledby="aModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="aModalLabel"><?= lang('add_product_manually') ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
					
					<div class="form-group">
							<label for="aquantity_unit" class="col-sm-4 control-label"><?= lang('quantity_unit') ?></label>

							<div class="col-sm-8">
								<input type="text" class="form-control" id="aquantity_unit">
							</div>
					</div>
                    <div class="form-group">
                        <label for="aprice" class="col-sm-4 control-label"><?= lang('price') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="aprice">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="updateAttr"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>