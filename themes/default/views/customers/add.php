<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_customer'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        echo form_open_multipart("customers/add", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <label class="control-label"
                       for="customer_group"><?php echo $this->lang->line("default_customer_group"); ?></label>

                <div class="controls"> <?php
                    foreach ($customer_groups as $customer_group) {
                        $cgs[$customer_group->id] = $customer_group->name;
                    }
                    echo form_dropdown('customer_group', $cgs, $this->Settings->customer_group, 'class="form-control tip select" id="customer_group" style="width:100%;" required="required"');
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
					<div class="form-group">
                        <?= lang("civility", "civility"); ?>
                        <?php
                        $civility[""] = "";
                        $civility['male'] = "Mr.";
                        $civility['female'] = "Mrs.";
                        echo form_dropdown('civility', $civility, isset($customer->civility)?$customer->civility:'', 'class="form-control select" id="civility" placeholder="' . lang("select") . ' ' . lang("civility") . '" style="width:100%" data-bv-notempty="true"')
                        ?>
                    </div>
                    <div class="form-group">
                        <?= lang("family_name", "family_name"); ?>
                        <?php echo form_input('family_name', '', 'class="form-control tip" id="family_name" data-bv-notempty="true"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("first_name", "first_name"); ?>
                       <?php echo form_input('first_name', '', 'class="form-control" id="first_name" required="required"'); ?>
                    </div>
                    <div class="form-group person" style="display:none;">
                        <?= lang("nick_name", "nick_name"); ?>
                        <?php echo form_input('nick_name', '', 'class="form-control tip" id="nick_name"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("spouse_first_name", "sp_fname"); ?>
                        <?php echo form_input('sp_fname', '', 'class="form-control" id="sp_fname" required="required"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("gender", "gender"); ?>
                        <?php
                        $gender[""] = "";
                        $gender['male'] = "Male";
                        $gender['female'] = "Female";
                        echo form_dropdown('gender', $gender, isset($customer->gender)?$customer->gender:'', 'class="form-control select" id="gender" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%" data-bv-notempty="true"')
                        ?>
                    </div>
                    <div class="form-group">
                        <?= lang("place_of_birth", "pob"); ?>
                        <?php echo form_input('pob', '', 'class="form-control" id="pob"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("age", "age"); ?>
                        <?php echo form_input('age', '', 'class="form-control" id="age" required="required"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("whose_income", "whose_income"); ?>
                        <?php echo form_input('whose_income', '', 'class="form-control" id="whose_income" required="required"'); ?>
                    </div>
                    <div class="form-group">
						<?= lang("nationality", "nationality"); ?>
						<?php
                        $nationality[""] = "";
                        $nationality['cam'] = "Cambodia";
                        $nationality['tha'] = "Thailand";
                        echo form_dropdown('nationality', $nationality, isset($customer->nationality)?$customer->nationality:'', 'class="form-control select" id="nationality" placeholder="' . lang("select") . ' ' . lang("nationality") . '" style="width:100%" data-bv-notempty="true"')
                        ?>
					</div>
                    <div class="form-group">
                        <?= lang("phone_2", "phone_2"); ?>
                        <input type="tel" name="phone_2" class="form-control" id="phone_2"/>
                    </div>
                    <div class="form-group">
                        <?= lang("house_no", "house_no"); ?>
                        <?php echo form_input('house_no', '', 'class="form-control" id="house_no"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("housing", "housing"); ?>
                        <?php
                        $housing[""] = "";
						$housing["0"] = "105";
                        echo form_dropdown('housing', $housing, isset($customer->housing)?$customer->housing:'', 'class="form-control select" id="housing" placeholder="' . lang("select") . ' ' . lang("housing") . '" style="width:100%" data-bv-notempty="true"');
                        ?>
                    </div>
					<div class="form-group">
                        <?= lang("district", "district"); ?>
                        <?php
                        $district[""] = "";
                        $district["0"] = "Tek Thlar";
                        echo form_dropdown('district', $district, isset($customer->district)?$customer->district:'', 'class="form-control select" id="district" placeholder="' . lang("select") . ' ' . lang("district") . '" style="width:100%" data-bv-notempty="true"');
                        ?>
                    </div>
					<div class="form-group">
                        <?= lang("province", "province"); ?>
                        <?php
                        $province[""] = "";
                        $province["0"] = "Kandal";
                        echo form_dropdown('province', $province, isset($customer->province)?$customer->province:'', 'class="form-control select" id="province" placeholder="' . lang("select") . ' ' . lang("province") . '" style="width:100%" data-bv-notempty="true"');
                        ?>
                    </div>
					<div class="form-group">
                        <b style="padding-bottom:5px; display:block;"><?= lang("time_at_this_address"); ?></b>
						<?php echo form_input('years', '', 'class="form-control" id="years" placeholder="' . lang("years") . '" style="display:inline !important; width:35% !important;"'); ?>
						<?= lang("years", "years"); ?>
						<?php echo form_input('months', '', 'class="form-control" id="months" placeholder="' . lang("months") . '" style="display:inline !important; width:35% !important;"'); ?>
						<?= lang("months", "months"); ?>
					</div>
                </div>
				
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("government_id", "gov_id"); ?>
                        <?php echo form_input('gov_id', '', 'class="form-control" id="gov_id" data-bv-notempty="true"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("family_name_other", "family_name_other"); ?>
                        <?php echo form_input('family_name_other', '', 'class="form-control" id="family_name_other" data-bv-notempty="true"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("first_name_other", "first_name_other"); ?>
                        <?php echo form_input('first_name_other', '', 'class="form-control" id="first_name_other" required="required"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("spouse_family_name", "sp_fam_name"); ?>
                        <?php echo form_input('sp_fam_name', '', 'class="form-control" id="sp_fam_name"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("number_of_children", "num_of_child"); ?>
                        <?php echo form_input('num_of_child', '', 'class="form-control" id="num_of_child"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("marital_status", "marital_status"); ?>
                        <?php
                        $marital_status[""] = "";
						$marital_status['Married '] = "Married ";
						$marital_status['Divorced'] = "Divorced";
                        $marital_status['Single'] = "Single";
                        $marital_status['Widow/Widower'] = "Widow/Widower";
                        $marital_status['Unknown'] = "Unknown";
                        
                        echo form_dropdown('marital_status', $marital_status, isset($customer->marital_status)?$customer->marital_status:'', 'class="form-control select" id="marital_status" placeholder="' . lang("select") . ' ' . lang("marital_status") . '" style="width:100%" data-bv-notempty="true"');
                        ?>
                    </div>
					<div class="form-group">
                        <?= lang("date_of_birth", "dob"); ?>
                        <?php echo form_input('dob', '', 'class="form-control date" id="dob"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("income_combination", "inc_comb"); ?>
                        <?php
                        $inc_comb['0'] = "No";
                        $inc_comb['1'] = "Yes";
                        echo form_dropdown('inc_comb', $inc_comb, isset($customer->inc_comb)?$customer->inc_comb:'', 'class="form-control select" id="inc_comb" style="width:100%"');
                        ?>
                    </div>
                    <div class="form-group" style="display:none;">
                        <?= lang("black_list_customer", "black_list"); ?>
                        <?php
                        $black_list['0'] = "No";
                        $black_list['1'] = "Yes";
                        echo form_dropdown('black_list', $black_list, isset($customer->black_list)?$customer->black_list:'', 'class="form-control select" id="black_list" style="width:100%"');
                        ?>
                    </div>
                    <div class="form-group">
                        <?= lang("phone_1", "phone_1"); ?>
                        <input type="tel" name="phone_1" class="form-control" id="phone_1" required="required"/>

                    </div>
                    <div class="form-group">
                        <?= lang("spouse_mobile_phone", "sp_phone"); ?>
                        <input type="tel" name="sp_phone" class="form-control" id="sp_phone"/>
                    </div>
                    <div class="form-group">
                        <?= lang("street", "street"); ?>
                        <?php echo form_input('street', '', 'class="form-control" id="street"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("village", "village"); ?>
                        <?php
                        $village[""] = "";
                        $village["0"] = "PCP";
                        echo form_dropdown('village', $village, isset($customer->village)?$customer->village:'', 'class="form-control select" id="village" placeholder="' . lang("select") . ' ' . lang("village") . '" style="width:100%" data-bv-notempty="true"');
                        ?>
                    </div>
					<div class="form-group">
                        <?= lang("communce", "communce"); ?>
                        <?php
                        $communce[""] = "";
                        $communce["0"] = "Sen Sok";
                        echo form_dropdown('communce', $communce, isset($customer->communce)?$customer->communce:'', 'class="form-control select" id="communce" placeholder="' . lang("select") . ' ' . lang("communce") . '" style="width:100%" data-bv-notempty="true"');
                        ?>
                    </div>
					<div class="form-group">
                        <?= lang("country", "country"); ?>
                        <?php
                        $country[""] = "";
                        $country["Cam"] = "Cambodia";
                        echo form_dropdown('country', $country, isset($customer->country)?$customer->country:'', 'class="form-control select" id="country" placeholder="' . lang("select") . ' ' . lang("country") . '" style="width:100%" data-bv-notempty="true"');
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang('add_customer'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>