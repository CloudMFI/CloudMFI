<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_dealer'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("suppliers/add", $attrib); ?>
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
                        echo form_dropdown('logo', $dealer_logos, '', 'class="form-control select" id="dealer_logo"'); ?>
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
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("&#6016;&#6098;&#6042;&#6075;&#6040;&#6048;&#6090;&#6075;&#6035;", "cf1"); ?>
                        <?php echo form_input('cf1', (isset($_POST['cf1'])? $_POST['cf1'] : ''), 'class="form-control" id="cf1"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("&#6024;&#6098;&#6040;&#6084;&#6087;", "cf2"); ?>
                        <?php echo form_input('cf2', (isset($_POST['cf2'])? $_POST['cf2'] : ''), 'class="form-control" id="cf2"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("&#6050;&#6070;&#6047;&#6096;&#6041;&#6026;&#6098;&#6027;&#6070;&#6035;", "cf4"); ?>
                        <?php echo form_input('cf4', (isset($_POST['cf4'])? $_POST['cf4'] : ''), 'class="form-control" id="cf4"'); ?>
                    </div>                    
                    <div class="form-group company">
                    <?= lang("contact_person", "contact_person"); ?>
                    <?php echo form_input('contact_person', (isset($_POST['contact_person'])? $_POST['contact_person'] : ''), 'class="form-control" id="contact_person" data-bv-notempty="false"'); ?>
                	</div>
                    <div class="form-group">
                        <?= lang("mobile_phone", "cf3"); ?>
                        <?php echo form_input('cf3', (isset($_POST['cf3'])? $_POST['cf3'] : ''), 'class="form-control" id="cf3"'); ?>
                    </div> 
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
									<?= lang("district", "district"); ?>
									<?php echo form_input('district', (isset($_POST['district']) ? $_POST['district'] : ''), 'class="form-control" id="district"  placeholder="' . lang("select_district_to_load") . '"');?>
								</div>
								<div class="form-group">
									<?= lang("village", "village"); ?>
									<?php echo form_input('village', (isset($_POST['village']) ? $_POST['village'] : ''), 'class="form-control" id="village"  placeholder="' . lang("select_village_to_load") . '"');?>
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
									<?= lang("commune", "commune"); ?>
									<?php echo form_input('commune', (isset($_POST['commune']) ? $_POST['commune'] : ''), 'class="form-control" id="commune"  placeholder="' . lang("select_communce_to_load") . '"');?>
								</div>
								<div class="form-group">
									<?= lang("street", "street"); ?>
									<?php echo form_input('street', (isset($_POST['street']) ? $_POST['street'] : ''), 'class="form-control" id="street"'); ?>
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
            <?php echo form_submit('add_dealer', lang('add_dealer'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<!-- Filter Address --->
<script type="text/javascript">
	$(document).ready(function() {
		/*  ---------- Select all Provinces When Form Load ----------- */
		$(window).load(function() {
			var url = "<?= site_url('quotes/getProvinces') ?>";
			var child_obj = 'cus_province';
			var child_emp_province = 'emp_province';
			var child_province = 'province';
			var lang = '<?= lang('select_province') ?>';
			var pholder = '<?= lang('select_province_to_load') ?>';
			getSelected2By3Child(child_obj, child_emp_province, child_province, url, lang, pholder);
		});
		
		/* --------------- Filter Provinces By Country -------------- */
		$('#cus_country').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getProvinces') ?>/" + v;
			var child_obj = 'cus_province';
			var lang = '<?= lang('select_province') ?>';
			var pholder = '<?= lang('select_province_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		$('#emp_country').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getProvinces') ?>/" + v;
			var child_obj = 'emp_province';
			var lang = '<?= lang('select_province') ?>';
			var pholder = '<?= lang('select_province_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		$('#country').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getProvinces') ?>/" + v;
			var child_obj = 'province';
			var lang = '<?= lang('select_province') ?>';
			var pholder = '<?= lang('select_province_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		
		/* --------------- Filter Districts By Province -------------- */
		$('#cus_province').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getDistricts') ?>/" + v;
			var child_obj = 'cus_district';
			var lang = '<?= lang('select_district') ?>';
			var pholder = '<?= lang('select_district_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		$('#emp_province').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getDistricts') ?>/" + v;
			var child_obj = 'emp_district';
			var lang = '<?= lang('select_district') ?>';
			var pholder = '<?= lang('select_district_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		$('#province').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getDistricts') ?>/" + v;
			var child_obj = 'district';
			var lang = '<?= lang('select_district') ?>';
			var pholder = '<?= lang('select_district_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		
		/* --------------- Filter Communces By District -------------- */
		$('#cus_district').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getCommunces') ?>/" + v;
			var child_obj = 'cus_communce';
			var lang = '<?= lang('select_communce') ?>';
			var pholder = '<?= lang('select_communce_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		$('#emp_district').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getCommunces') ?>/" + v;
			var child_obj = 'emp_communce';
			var lang = '<?= lang('select_communce') ?>';
			var pholder = '<?= lang('select_communce_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		$('#district').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getCommunces') ?>/" + v;
			var child_obj = 'communce';
			var lang = '<?= lang('select_communce') ?>';
			var pholder = '<?= lang('select_communce_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		
		/* --------------- Filter Villages By Communce -------------- */
		$('#cus_communce').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getVillages') ?>/" + v;
			var child_obj = 'cus_village';
			var lang = '<?= lang('select_village') ?>';
			var pholder = '<?= lang('select_village_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		$('#emp_communce').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getVillages') ?>/" + v;
			var child_obj = 'emp_village';
			var lang = '<?= lang('select_village') ?>';
			var pholder = '<?= lang('select_village_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
		$('#communce').change(function () {
			var v = $(this).val();
			var url = "<?= site_url('quotes/getVillages') ?>/" + v;
			var child_obj = 'village';
			var lang = '<?= lang('select_village') ?>';
			var pholder = '<?= lang('select_village_to_load') ?>';
			getSelected2By1Child(url, child_obj, lang, pholder);
		});
	});
</script>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#dealer_logo').change(function (event) {
            var dealer_logo = $(this).val();
            $('#logo-con').html('<img src="<?=base_url('assets/uploads/logos')?>/' + dealer_logo + '" alt="">');
        });
		
		/* --------------- Get All Province ------------------ */
		var url = "<?= site_url('suppliers/getProvinces') ?>";
		var child_province = 'province';
		var lang = '<?= lang('select_province') ?>';
		var pholder = '<?= lang('select_province_to_load') ?>';
		getSelected2By1Child(url, child_province, lang, pholder);
    });
</script>
<?= $modal_js ?>
