<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_dealer'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("billers/add", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("logo", "biller_logo"); ?>
                        <?php
                        $biller_logos[''] = '';
                        foreach ($logos as $key => $value) {
                            $biller_logos[$value] = $value;
                        }
                        echo form_dropdown('logo', $biller_logos, '', 'class="form-control select" id="biller_logo"'); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="logo-con" class="text-center"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group company">
                        <?= lang("company", "company"); ?>
                        <?php echo form_input('company', '', 'class="form-control tip" id="company" data-bv-notempty="true"'); ?>
                    </div>
                    <div class="form-group person">
                        <?= lang("name", "name"); ?>
                        <?php echo form_input('name', '', 'class="form-control tip" id="name" data-bv-notempty="true"'); ?>
                    </div>
					<!--
					<div class="form-group">
                        <?= lang("Business", "Business"); ?>
                        <?php echo form_input('business', $biller->postal_code, 'class="form-control" id="business"'); ?>
                    </div>
					-->
                    <div class="form-group">
                        <?= lang("vat_no", "vat_no"); ?>
                        <?php echo form_input('vat_no', '', 'class="form-control" id="vat_no"'); ?>
                    </div>                    
                    <div class="form-group">
                        <?= lang("email_address", "email_address"); ?>
                        <input type="email" name="email" class="form-control" required="required" id="email_address"/>
                    </div>
                    <div class="form-group">
                        <?= lang("office_phone", "phone"); ?>
                        <input type="tel" name="phone" class="form-control" id="phone"/>
                    </div>
					<!--
                    <div class="form-group">
                        <?= lang("address", "address"); ?>
						<?php echo form_textarea('address', '', 'class="form-control skip" id="address" style="height:115px;" required="required"'); ?>
					</div> 
					<div class="form-group">
                        <?= lang("Street", "Street"); ?>
                        <?php echo form_input('Street', '', 'class="form-control" id="Street"'); ?>
                    </div>
					
					<div class="form-group">
                        <?= lang("Group", "Group"); ?>
                        <?php echo form_input('group', '', 'class="form-control" id="group"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("Village", "Village"); ?>
                        <?php echo form_input('village', '', 'class="form-control" id="village"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("Commune", "Commune"); ?>
                        <?php echo form_input('Commune', '', 'class="form-control" id="Commune"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("District", "District"); ?>
                        <?php echo form_input('District', '', 'class="form-control" id="District"'); ?>
                    </div>
                   
                    <div class="form-group">
                        <?= lang("city", "city"); ?>
                        <?php echo form_input('city', '', 'class="form-control" id="city"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("state", "state"); ?>
                        <?php echo form_input('state', '', 'class="form-control" id="state"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("country", "country"); ?>
                        <?php echo form_input('country', $biller->country, 'class="form-control" id="country"'); ?>
                    </div>
					 <div class="form-group">
                        <?= lang("postal_code", "postal_code"); ?>
                        <?php echo form_input('postal_code', $biller->postal_code, 'class="form-control" id="postal_code"'); ?>
                    </div>
					-->
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("&#6016;&#6098;&#6042;&#6075;&#6040;&#6048;&#6090;&#6075;&#6035;", "cf1"); ?>
                        <?php echo form_input('cf1', $biller->cf1, 'class="form-control" id="cf1"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("&#6024;&#6098;&#6040;&#6084;&#6087;", "cf2"); ?>
                        <?php echo form_input('cf2', $biller->cf2, 'class="form-control" id="cf2"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("&#6050;&#6070;&#6047;&#6096;&#6041;&#6026;&#6098;&#6027;&#6070;&#6035;", "cf4"); ?>
                        <?php echo form_input('cf4', $biller->cf4, 'class="form-control" id="cf4"'); ?>
                    </div>                    
                    <div class="form-group company">
                    <?= lang("contact_person", "contact_person"); ?>
                    <?php echo form_input('contact_person', '', 'class="form-control" id="contact_person" data-bv-notempty="false"'); ?>
                	</div>
                    <div class="form-group">
                        <?= lang("mobile_phone", "cf3"); ?>
                        <?php echo form_input('cf3', $biller->cf3, 'class="form-control" id="cf3"'); ?>
                    </div> 
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
                </div>
				<div class="col-sm-12">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= lang('address') ?></div>
						<div class="panel-body" style="padding: 5px;">
							<div class="col-md-6">
								<div class="form-group">
										<?= lang("country", "country"); ?>
										<?php
										foreach ($countries as $ct) {
											$country[$ct->id] = $ct->name;
										}
										echo form_dropdown('country', $country, (isset($_POST['country']) ? $_POST['country'] : ''), 'class="form-control select" id="country" placeholder="' . lang("select") . ' ' . lang("country") . '" style="width:100%" data-bv-notempty="true"');
										?>
								</div>
								<div class="form-group">
									<?= lang("city", "city"); ?>
									<?php echo form_input('city', (isset($_POST['city']) ? $_POST['city'] : ''), 'class="form-control" id="city"  placeholder="' . lang("select_city_to_load") . '"');?>
								</div>
								<div class="form-group">
									<?= lang("communce", "communce"); ?>
									<?php echo form_input('communce', (isset($_POST['communce']) ? $_POST['communce'] : ''), 'class="form-control" id="communce"  placeholder="' . lang("select_communce_to_load") . '"');?>
								</div>
								<div class="form-group">
									<?= lang("street", "street"); ?>
									<?php echo form_input('street', (isset($_POST['street']) ? $_POST['street'] : ''), 'class="form-control" id="street"'); ?>
								</div>
								<div class="form-group">
									<?= lang("house_no", "house_no"); ?>
									<?php echo form_input('house_no', (isset($_POST['house_no']) ? $_POST['house_no'] : ''), 'class="form-control" id="house_no"'); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<?= lang("province", "province"); ?>
									<?php echo form_input('province', (isset($_POST['province']) ? $_POST['province'] : ''), 'class="form-control" id="province"  placeholder="' . lang("select_province_to_load") . '"');?>
								</div>
								<div class="form-group">
									<?= lang("district", "district"); ?>
									<?php echo form_input('district', (isset($_POST['district']) ? $_POST['district'] : ''), 'class="form-control" id="district"  placeholder="' . lang("select_district_to_load") . '"');?>
								</div>
								<div class="form-group">
									<?= lang("village", "village"); ?>
									<?php echo form_input('village', (isset($_POST['village']) ? $_POST['village'] : ''), 'class="form-control" id="village"  placeholder="' . lang("select_village_to_load") . '"');?>
								</div>
								<div class="form-group">
									<?= lang("group", "group"); ?>
									<?php echo form_input('group', (isset($_POST['group']) ? $_POST['group'] : ''), 'class="form-control" id="group"'); ?>
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
									<?php echo form_input('bank_name', '', 'class="form-control" id="bank_name"'); ?>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<?= lang("account_number", "account_number"); ?>
									<?php echo form_input('account_number', '', 'class="form-control" id="account_number"'); ?>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<?= lang("account_name", "account_name"); ?>
									<?php echo form_input('account_name', '', 'class="form-control" id="account_name"'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_biller', lang('add_dealer'), 'class="btn btn-primary"'); ?>
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
			var url = "<?= site_url('billers/getProvinces') ?>/" + v;
			var child_obj = 'province';
			var lang = '<?= lang('select_province') ?>';
			var pholder = '<?= lang('select_country_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		
		/* --------------- Filter Districts By Province -------------- */
		$('#province').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('billers/getDistricts') ?>/" + v;
			var url_city = "<?= site_url('billers/getCities') ?>/" + v;
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
			var url = "<?= site_url('billers/getCities') ?>/" + v;
			var child_obj = 'district';
			var lang = '<?= lang('select_district') ?>';
			var pholder = '<?= lang('') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		
		/* --------------- Filter Communces By District -------------- */
		$('#district').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('billers/getCommunces') ?>/" + v;
			var child_obj = 'communce';
			var lang = '<?= lang('select_communce') ?>';
			var pholder = '<?= lang('select_communce_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
		
		/* --------------- Filter Villages By Communce -------------- */
		$('#communce').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('billers/getVillages') ?>/" + v;
			var child_obj = 'village';
			var lang = '<?= lang('select_village') ?>';
			var pholder = '<?= lang('select_village_to_load') ?>';
			getOrderSelect(url, child_obj, lang, pholder);
		});
	});
</script>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#biller_logo').change(function (event) {
            var biller_logo = $(this).val();
            $('#logo-con').html('<img src="<?=base_url('assets/uploads/logos')?>/' + biller_logo + '" alt="">');
        });
		
		/* --------------- Get All Province ------------------ */
		var url = "<?= site_url('billers/getProvinces') ?>";
		var child_province = 'province';
		var lang = '<?= lang('select_province') ?>';
		var pholder = '<?= lang('select_province_to_load') ?>';
		getOrderSelect(url, child_province, lang, pholder);
    });
</script>
<?= $modal_js ?>
