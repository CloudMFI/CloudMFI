<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_dealer'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("suppliers/update/" . $dealer->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("logo", "dealer_logo"); ?>
                        <?php
                        $dealer_logos[''] = '';
                        foreach ($logos as $key => $value) {
                            $dealer_logos[$value] = $value;
                        }
                        echo form_dropdown('logo', $dealer_logos, $dealer->logo, 'class="form-control select" id="dealer_logo" required="required" '); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="logo-con" class="text-center"><img
                            src="<?= base_url('assets/uploads/logos/' . $dealer->logo) ?>" alt=""></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group company">
                        <?= lang("company", "company"); ?>
                        <?php echo form_input('company', $dealer->company, 'class="form-control tip" id="company" required="required"'); ?>
                    </div>
                    <div class="form-group person">
                        <?= lang("name", "name"); ?>
                        <?php echo form_input('name', $dealer->name, 'class="form-control tip" id="name" required="required"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("vat_no", "vat_no"); ?>
                        <?php echo form_input('vat_no', $dealer->vat_no, 'class="form-control" id="vat_no"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("email_address", "email_address"); ?>
                        <input type="email" name="email" class="form-control" required="required" id="email_address"
                               value="<?= $dealer->email ?>"/>
                    </div>
                    <div class="form-group">
                        <?= lang("office_phone", "phone"); ?>
                        <input type="tel" name="phone" class="form-control" id="phone"
                               value="<?= $dealer->phone ?>"/>
                    </div>
                </div>
                <div class="col-md-6">
					<div class="form-group">
                        <?= lang("ក្រុមហ៊ុន", "cf1"); ?>
                        <?php echo form_input('cf1', $dealer->cf1, 'class="form-control" id="cf1"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("ឈ្មោះ", "cf2"); ?>
                        <?php echo form_input('cf2', $dealer->cf2, 'class="form-control" id="cf2"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("អាស័យដ្ឋាន", "cf4"); ?>
                        <?php echo form_input('cf4', $dealer->cf4, 'class="form-control" id="cf4"'); ?>
                    </div>
                    <div class="form-group company">
                    <?= lang("contact_person", "contact_person"); ?>
                    <?php echo form_input('contact_person', (isset($dealer->phone1) ? $dealer->phone1 : ''), 'class="form-control" id="contact_person" data-bv-notempty="false"'); ?>
                	</div>
                    <div class="form-group">
                        <?= lang("mobile_phone", "cf3"); ?>
                        <?php echo form_input('cf3', $dealer->cf3, 'class="form-control" id="cf3"'); ?>
                    </div> 
                </div>
            </div>
			<!--- add more row-->
			<!--
                    <div class="form-group">
						<?php
                            foreach ($warehouses as $warehouse) {
                                $wh[$warehouse->id] = $warehouse->name;
                            }
							echo lang("warehouse", "cf5");
                            echo form_dropdown('cf5[]', $wh, '', 'id="cf5" class="form-control" multiple="multiple"');
						?>
                    </div>
                    <div class="form-group">
                        <?= lang("benefit", "cf6"); ?>
                        <?php echo form_input('cf6', '', 'class="form-control" id="cf6"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("invoice_footer", "invoice_footer"); ?>
                        <?php echo form_textarea('invoice_footer', '', 'class="form-control skip" id="invoice_footer" style="height:115px;"'); ?>
                    </div>
					-->
				<div class="col-sm-12">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= lang('address') ?></div>
						<div class="panel-body" style="padding: 5px;">
							<div class="col-md-6">
								<div class="form-group">
										<?= lang("country", "country"); ?>
										<?php
										foreach ($countries as $ct) {
											$country[$ct->code] = $ct->name;
										}
										echo form_dropdown('country', $country, (isset($dealer->country) ? $dealer->country : ''), 'class="form-control select" id="country" placeholder="' . lang("select") . ' ' . lang("country") . '" style="width:100%" data-bv-notempty="true"');
										?>
								</div>
								<div class="form-group">
									<?= lang("district", "district"); ?>
									<?php echo form_input('district', (isset($dealer->district) ? $dealer->district : ''), 'class="form-control" id="district"  placeholder="' . lang("select_district_to_load") . '"');?>
								</div>
								<div class="form-group">
									<?= lang("village", "village"); ?>
									<?php echo form_input('village', (isset($dealer->village) ?$dealer->village : ''), 'class="form-control" id="village"  placeholder="' . lang("select_village_to_load") . '"');?>
								</div>
								<div class="form-group">
									<?= lang("house_no", "house_no"); ?>
									<?php echo form_input('house_no', (isset($dealer->house_no) ? $dealer->house_no : ''), 'class="form-control" id="house_no"'); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?= lang("province", "province"); ?>
									<?php echo form_input('province', (isset($dealer->state) ? $dealer->state : ''), 'class="form-control" id="province"  placeholder="' . lang("select_province_to_load") . '"');?>
								</div>
								<div class="form-group">
									<?= lang("communce", "communce"); ?>
									<?php echo form_input('communce', (isset($dealer->sangkat) ? $dealer->sangkat : ''), 'class="form-control" id="communce"  placeholder="' . lang("select_communce_to_load") . '"');?>
								</div>
								<div class="form-group">
									<?= lang("street", "street"); ?>
									<?php echo form_input('street', (isset($dealer->street) ? $dealer->street : ''), 'class="form-control" id="street"'); ?>
								</div>
								<div class="form-group">
									<?= lang("group", "group"); ?>
									<?php echo form_input('group', (isset($dealer->group) ? $dealer->group : ''), 'class="form-control" id="group"'); ?>
								</div>								
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= lang('bank_information') ?></div>
						<div class="panel-body" style="padding: 5px;">
							<div class="col-sm-4">
								<div class="form-group">
									<?= lang("bank_name", "bank_name"); ?>
									<?php echo form_input('bank_name', (isset($dealer->bank_name) ? $dealer->bank_name : ''), 'class="form-control" id="bank_name"'); ?>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<?= lang("account_number", "account_number"); ?>
									<?php echo form_input('account_number', (isset($dealer->account_number) ? $dealer->account_number : ''), 'class="form-control" id="account_number"'); ?>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<?= lang("account_name", "account_name"); ?>
									<?php echo form_input('account_name', (isset($dealer->account_name) ? $dealer->account_name : ''), 'class="form-control" id="account_name"'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_dealer', lang('edit_dealer'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<!-- Filter Address --->
<script type="text/javascript">
	function getOrderSelect(url, child_obj, lang, pholder) {
		$('#modal-loading').show();
		$.ajax({
			type: "get",
			async: false,
			url: url,
			dataType: "json",
			success: function (scdata) {
				if (scdata != null) {
					$("#"+child_obj).select2("destroy").empty().attr("placeholder", lang).select2({
						placeholder: pholder,
						data: scdata
					});
				}
			},
			error: function () {
				bootbox.alert('<?= lang('ajax_error') ?>');
				$('#modal-loading').hide();
			}
		});
		$('#modal-loading').hide();
	}
	$(document).ready(function() {
		
		/* --------------- Filter Provinces By Country -------------- */
		$('#country').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('branch/getProvinces') ?>/" + v;
			var child_obj = 'province';
			var lang = '<?= lang('select_province') ?>';
			var pholder = '<?= lang('select_country_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		
		/* --------------- Filter Districts By Province -------------- */
		$('#province').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('branch/getDistricts') ?>/" + v;
			var url_city = "<?= site_url('branch/getCities') ?>/" + v;
			var child_obj = 'district';
			var child_city = 'city';
			var lang = '<?= lang('select_district') ?>';
			var lang_city = '<?= lang('select_city') ?>';
			var pholder = '<?= lang('select_district_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
			getOrderSelect(url_city, child_city, lang_city, pholder);
		});
		
		/* --------------- Filter Districts By City -------------- */
		$('#city').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('branch/getCities') ?>/" + v;
			var child_obj = 'district';
			var lang = '<?= lang('select_district') ?>';
			var pholder = '<?= lang('') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		
		/* --------------- Filter Communces By District -------------- */
		$('#district').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('branch/getCommunces') ?>/" + v;
			var child_obj = 'communce';
			var lang = '<?= lang('select_communce') ?>';
			var pholder = '<?= lang('select_communce_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		
		/* --------------- Filter Villages By Communce -------------- */
		$('#communce').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('branch/getVillages') ?>/" + v;
			var child_obj = 'village';
			var lang = '<?= lang('select_village') ?>';
			var pholder = '<?= lang('select_village_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
	});
</script>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
		var warehouses = <?php echo json_encode($warehouses); ?>;
		var warehouse_id = new Array();
		var v = 0;
		$.each(warehouses, function(){
			warehouse_id[v] = this.id;
			v++;
		});
		$("#cf5").val(warehouse_id);


        $('#dealer_logo').change(function (event) {
            var dealer_logo = $(this).val();
            $('#logo-con').html('<img src="<?=base_url('assets/uploads/logos')?>/' + dealer_logo + '" alt="">');
        });
		
		$('#country').trigger('change');
		$('#province').trigger('change');
		$('#city').trigger('change');
		$('#district').trigger('change');
		$('#communce').trigger('change');
    });
</script>
<?= $modal_js ?>

