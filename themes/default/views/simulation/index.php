<style>
.form-show {
  background-color: white;
  font-size: 15px;
  border:none;
  color: white;
}
</style>
<div class="box">
    <div class="box-header">
		<h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('simulation'); ?></h2>

		<?php
			$product =array();
		?>
    </div>
    <div class="box-content">
		<div class="row">
			<div class="col-lg-12 ">
				<div class="col-sm-5">
					<!--<div class="form-group all">
                        <?= lang("type", "type") ?>
                        <?php
                        $type['leasing'] = 'Leasing';
						$type['mfi'] = 'MFI';
                        echo form_dropdown('type', $type, (isset($_POST['type']) ? $_POST['type'] : ''), 'class="form-control select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("type_of_businesse") . '" id="type" style="width:100%;"');
                        ?>
                    </div>-->
				</div>
			</div>
		</div>
        <div class="row" id="leasing" style=""display:none;">
            <div class="col-lg-12 ">
                <div class="col-md-5">
                    <div class="form-group all">
                        <?= lang("financial_product", "financial_product") ?>
                        <?php
                        $fin_pro[''] = '';
						if(array($customer_groups)) {
							foreach ($customer_groups as $financial_product) {
								$fin_pro[$financial_product->id] = $financial_product->name;
							}
						}
                        echo form_dropdown('financial_product', $fin_pro, (isset($_POST['financial_product']) ? $_POST['financial_product'] : ''), 'class="form-control select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("financial_product") . '" id="financial_product" style="width:100%;"');
                        ?>
                    </div>
					<div class="form-group all">
                        <?= lang("asset_price", "asset_price") ?>
                        <?= form_input('asset_price', (isset($_POST['name']) ? $_POST['asset_price'] : ($product ? $product->asset_price : '')), 'class="form-control" id="asset_price"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("%_advance_payment", "p_advance_payment") ?>
                        <?php
                        $percentage[""] = "";
						if($advance_percentages) {
							foreach ($advance_percentages as $advance_percentage) {
								$percentage[$advance_percentage->amount] = $advance_percentage->description;
							}
						}
                        echo form_dropdown('p_advance_payment', $percentage, (isset($_POST['p_advance_payment']) ? $_POST['p_advance_payment'] : ''), 'class="form-control" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("down_payment_by_percentage") . '" id="p_advance_payment"');
                        ?>
                    </div>
                    <div class="form-group">
                        <?= lang("frequency", "frequency") ?>
                        <?php
						$frequency[""] = "";
						$frequency[1] = "Daily";
						$frequency[7] = "Weekly";
						$frequency[14] = "Two Week";
						$frequency[30] = "Monthly";
						$frequency[90] = "Quarterly";
						$frequency[180] = "Haft Year";
						$frequency[360] = "Yearly";
                        echo form_dropdown('frequency', $frequency, (isset($_POST['frequency']) ? $_POST['frequency'] : ''), 'class="form-control" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("frequency") . '" id="frequency"');
                        ?>
                    </div>
					
					<div class="form-group">
						<?= lang("rate_type", "rate_type"); ?>
						<?php
						$rate_type[""] = "";
						$rate_type["1"] = "Normal";
						$rate_type["2"] = "Fixed";
						$rate_type["3"] = "Normal_Fixed";
						$rate_type["4"] = "Custom";
						echo form_dropdown('rate_type', $rate_type, (isset($_POST['rate_type']) ? $_POST['rate_type'] : ''), 'id="rate_type" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("rate_type") . '"  class="form-control input-tip select" style="width:100%;"');
						?>
					</div>

					<div class="controls">
						<?= lang("rate", "rate") ?>
						<?php
						$itr[''] = '';
						if($interest_rate) {
							foreach ($interest_rate as $inter_rate) {
								$itr[$inter_rate->amount] = $inter_rate->description;
							}
						}
						echo form_dropdown('interest_rate', $itr, $this->Settings->customer_group, 'class="form-control tip select" id="interest_rate" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("interest_rate") . '" style="width:100%;" required="required"');
						?>
						
					</div>
					
					<div class="controls">
                        <?= lang("terms", "terms") ?>
                       <?php
					   $ts[''] = '';
					   if($terms) {
							foreach ($terms as $trs) {
								$ts[$trs->amount] = $trs->description;
							}
					   }
						echo form_dropdown('term_in_month', $ts, $this->Settings->customer_group, 'class="form-control tip select" id="term_in_month" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("payment_term") . '" style="width:100%;" required="required"');
						?>
					</div><br/>
                </div>
				<div class="col-md-5" style="padding-top: 25px;">
          <div>
              <div style="font-weight:bold;"><p style="float: left; margin-right: 15px; margin-top: 10px;">Advance Payment&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</p><p id="ap-show" style="font-size:25px !important;"><?= $this->erp->formatMoney(0); ?></p></div>
          </div>
          <div>
              <div style="font-weight:bold;"><p style="float: left; margin-right: 15px; margin-top: 10px;">Leaser Amount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</p><p id="la-show" style="font-size:25px !important;"><?= $this->erp->formatMoney(0); ?></p><input type="hidden" name="lease_amt" id="lease_amt" /></div>
          </div>
                    <div>
                        <div style="font-weight:bold;"><p style="float: left; margin-right: 15px; margin-top: 10px;">Installment Amount&nbsp;:</p><p id="ia-show" style="font-size:25px !important;"><?= $this->erp->formatMoney(0); ?></p></div>
                    </div>
                    <div class="modal-footer">
						<button class="btn btn-primary" style="display:none;" id="simulate"><?php echo lang('simulate');?></button>
						<div class=" btn_print_payment_schedule" style="display:none;">
							<div class="form-group" style="padding-top:7.5%;">	
								<input type="button" class="btn btn-primary" value="<?=lang('print_payment_schedule')?>" name="print_payment_schedule" id="print_payment_schedule" />							
							</div>
						</div>
					</div>
				</div>


			<p id="advance_payment_r" style="display:none"></p>
				<p id="lease_amount_r" " style="display:none"></p>
        </div>
    </div>
	<div class="row" id="mfi">
		<div class="col-lg-12 ">
			<div class="col-md-5">
				<!--<div class="form-group all">
					<?= lang("customer_type", "customer_type") ?>
					<?php
					$fin_pro[''] = '';
					if(array($customer_groups)) {
						foreach ($customer_groups as $financial_product) {
							$fin_pro[$financial_product->id] = $financial_product->name;
						}
					}
					echo form_dropdown('customer_type', $fin_pro, (isset($_POST['customer_type']) ? $_POST['customer_type'] : ''), 'class="form-control select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("customer_type") . '" id="customer_type" style="width:100%;"');
					?>
				</div>-->
				
				<div class="form-group all">
					<?php echo lang('currency', 'currency') ?>
					<?php
					$crr[(isset($_POST['currency']) ? $_POST['currency'] : '')] = (isset($_POST['currency']) ? $_POST['currency'] : '');
					if(array($currencies)) {
						foreach($currencies as $currency){
							$crr[$currency->code] = $currency->name;
						}
					}
					echo form_dropdown('currency', $crr, '', 'class="form-control currency" id="currency" placeholder="' . lang("select_currency") . '"');
					?>
				</div>
				
				<div class="form-group all">
					<?= lang("amount", "c_amount") ?>
					<?= form_input('c_amount', (isset($_POST['c_amount']) ? $_POST['c_amount'] : ''), 'class="form-control" id="c_amount" style="font-size:20px"'); ?>
				</div>
				
				<div class="controls">
					<?= lang("interest", "c_interest") ?>
					<?php
					/*$itr[''] = '';
					if($interest_rate) {
						foreach ($interest_rate as $inter_rate) {
							$itr[$inter_rate->amount] = $inter_rate->description;
						}
					}
					echo form_dropdown('c_interest', $itr, $this->Settings->customer_group, 'class="form-control tip select" id="c_interest" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("interest_rate") . '" style="width:100%;" required="required"');
					*/
					?>
					
					<input type="hidden" name="c_interest" id="c_interest" class="c_interest"/>
					<?= form_input('c_interest_2', (isset($_POST['c_interest_2']) ? $_POST['c_interest_2'] : ''), ' class="form-control" id="c_interest_2" style="font-size:14px;" ') ?>
					
				</div>
				
				<div class="controls">
					<?= lang("terms", "c_terms") ?>
				   <?php
				   $ts[''] = '';
				   if($terms) {
						foreach ($terms as $trs) {
							$ts[$trs->amount] = $trs->description;
						}
				   }
					echo form_dropdown('c_terms', $ts, $this->Settings->customer_group, 'class="form-control tip select" id="c_terms" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("payment_term") . '" style="width:100%;" required="required"');
					?>
				</div>
				
				<div class="form-group">
					<?= lang("frequency", "c_frequency") ?>
					<?php
					$c_frequency[""] = "";
					$c_frequency[1] = "Daily";
					$c_frequency[7] = "Weekly";
					$c_frequency[14] = "Two Week";
					$c_frequency[30] = "Monthly";
					$c_frequency[90] = "Quarterly";
					$c_frequency[180] = "Haft Year";
					$c_frequency[360] = "Yearly";
					echo form_dropdown('c_frequency', $c_frequency, (isset($_POST['c_frequency']) ? $_POST['c_frequency'] : ''), 'class="form-control" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("frequency") . '" id="c_frequency"');
					?>
				</div>
				
				<div class="form-group">
					<?= lang("rate_type", "c_rate_type"); ?>
					<?php
					$rate_type[""] = "";
					$rate_type["1"] = "Normal";
					$rate_type["2"] = "Fixed";
					$rate_type["3"] = "Normal_Fixed";
					$rate_type["4"] = "Custom";
					echo form_dropdown('c_rate_type', $rate_type, (isset($_POST['c_rate_type']) ? $_POST['c_rate_type'] : ''), 'id="c_rate_type" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("rate_type") . '"  class="form-control input-tip select" style="width:100%;"');
					?>
				</div>
				<br/>
			</div>
			<div class="col-md-5" style="padding-top: 25px;">
				<div>
					<div style="font-weight:bold;">
						<p style="float: left; margin-right: 15px; margin-top: 10px;" id="daily_paid"> <?php echo lang('daily_installment');?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</p> 
						<p style="float: left; margin-right: 15px; margin-top: 10px;" id="weely_paid"> <?php echo lang('weekly_installment');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</p>
						<p style="float: left; margin-right: 15px; margin-top: 10px;" id="two_weeks_paid"> <?php echo lang('two_week_installment');?> &nbsp;&nbsp;:</p>
						<p style="float: left; margin-right: 15px; margin-top: 10px;" id="monthly_paid"> <?php echo lang('monthly_installment');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</p> 
						<p style="float: left; margin-right: 15px; margin-top: 10px;" id="quarterly_paid"> <?php echo lang('quarterly_installment');?> &nbsp;&nbsp;&nbsp;&nbsp;:</p>
						<p style="float: left; margin-right: 15px; margin-top: 10px;" id="haft_year_paid"> <?php echo lang('haft_year_installment');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</p> 
						<p style="float: left; margin-right: 15px; margin-top: 10px;" id="yearly_paid"> <?php echo lang('yearly_installment');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</p>
						<p id="c_schedule-show" style="font-size:25px !important;"><?= $this->erp->formatMoney(0); ?></p>
					</div>
				</div>
				<div>
					<div style="font-weight:bold;"><p style="float: left; margin-right: 15px; margin-top: 10px;"><?php echo lang('total_interest');?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</p><p id="c_ap-show" style="font-size:25px !important;"><?= $this->erp->formatMoney(0); ?></p></div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" style="display:none;" id="simulate"><?php echo lang('simulate');?></button>
					<div class=" btn_print_payment_schedule_cash" style="display:none;">
						<div class="form-group" style="padding-top:7.5%;">	
							<input type="button" class="btn btn-primary" value="<?=lang('print_payment_schedule')?>" name="print_payment_schedule_cash" id="print_payment_schedule_cash" />							
						</div>
					</div>
				</div>
			</div>

	</div>
		<!--
		<br/>
		<div class="col-sm-12">
			<div class="panel panel-primary">
				<div class="panel-heading"><?= lang('services') ?></div>
				<div class="panel-body" style="padding: 5px;">
					<div class="col-md-5">
						<div class="form-group standard">
							<?php
								$k = 0;
								if(array($services)) {
									foreach($services as $service) {
							?>
										<?= lang($service->description, "service[]") ?>
										<div
											class="input-group"> <?= form_input('service[]', $this->erp->formatMoney($service->amount), 'class="form-control tip services" readonly') ?>
											<span class="input-group-addon">
												<input type="checkbox" name="ch_services[]" class="ch_services"
													   value="<?= $service->amount; ?>">
											</span>
										</div>
							<?php 
									}
								}
							?>
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group all" style="padding-top:2.5%;">
							<?= lang("total_installment_amount", "total_installment_amount") ?>
							<?= form_input('total_installment_amount', (isset($_POST['total_installment_amount']) ? $this->erp->formatMoney($_POST['total_installment_amount']) : ($product ? $this->erp->formatMoney($product->installment_amount) : $this->erp->formatMoney(0))), 'class="form-control" readonly="ture" id="total_installment_amount" '); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		-->
	</div>

<script>

$(document).ready(function(){	
	$('#daily_paid').hide();
	$('#weely_paid').hide();
	$('#two_weeks_paid').hide();
	$('#monthly_paid').show();
	$('#quarterly_paid').hide();
	$('#haft_year_paid').hide();
	$('#yearly_paid').hide();	
	$('#c_frequency').on('change', function() {	
		var c_frequency = $(this).val();		
		if(c_frequency == '1') {		
			$('#daily_paid').show();
			$('#weely_paid').hide();
			$('#two_weeks_paid').hide();
			$('#monthly_paid').hide();
			$('#quarterly_paid').hide();
			$('#haft_year_paid').hide();
			$('#yearly_paid').hide();			
		}
		else if(c_frequency == '7') {		
			$('#daily_paid').hide();
			$('#weely_paid').show();
			$('#two_weeks_paid').hide();
			$('#monthly_paid').hide();
			$('#quarterly_paid').hide();
			$('#haft_year_paid').hide();
			$('#yearly_paid').hide();			
		}
		else if(c_frequency == '14') {		
			$('#daily_paid').hide();
			$('#weely_paid').hide();
			$('#two_weeks_paid').show();
			$('#monthly_paid').hide();
			$('#quarterly_paid').hide();
			$('#haft_year_paid').hide();
			$('#yearly_paid').hide();				
		}
		else if(c_frequency == '30') {		
			$('#daily_paid').hide();
			$('#weely_paid').hide();
			$('#two_weeks_paid').hide();
			$('#monthly_paid').show();
			$('#quarterly_paid').hide();
			$('#haft_year_paid').hide();
			$('#yearly_paid').hide();			
		}
		else if(c_frequency == '90') {		
			$('#daily_paid').hide();
			$('#weely_paid').hide();
			$('#two_weeks_paid').hide();
			$('#monthly_paid').hide();
			$('#quarterly_paid').show();
			$('#haft_year_paid').hide();
			$('#yearly_paid').hide();			
		}
		else if(c_frequency == '180') {		
			$('#daily_paid').hide();
			$('#weely_paid').hide();
			$('#two_weeks_paid').hide();
			$('#monthly_paid').hide();
			$('#quarterly_paid').hide();
			$('#haft_year_paid').show();
			$('#yearly_paid').hide();			
		}
		else if(c_frequency == '360') {		
			$('#daily_paid').hide();
			$('#weely_paid').hide();
			$('#two_weeks_paid').hide();
			$('#monthly_paid').hide();
			$('#quarterly_paid').hide();
			$('#haft_year_paid').hide();
			$('#yearly_paid').show();			
		}	
	});
});

/*$(document).ready(function(){
	
	$('#monthly_paid').show();;
	$('#c_frequency').on('change', function() {
		var c_frequency = $(this).val();
		if(c_frequency == 'Monthly') {
			$('#monthly_paid').show();			
		}else {
			$('#monthly_paid').hide();
		}
});*/


/*----interest_rate----*/
$('#c_interest_2').live('change', function(e) {
	var c_interest = $(this).val().toLowerCase();
	var interest_rate = 0;
	if(c_interest.search('%') > 0) {
		c_interest = c_interest.replace('%', '');
		interest_rate = (c_interest/100);
	}else {
		rate = c_interest - 0;
		if(!Number(rate)) {
			interest_rate = 0;
		}else {
			interest_rate = c_interest;
		}
	}
	$('#c_interest').val(interest_rate);
	$('#c_interest').trigger('change');
});
/*----Amount
$('#c_amount').live('change', function(e) {
	var price = $(this).val().toLowerCase();
	var amount = 0;
	var new_amount = 0; 
	if(price.search('k') > 0) {
		amount = price.split('k');
		new_amount = parseFloat(amount[0] * 1000);
	}else if(price.search('m') > 0) {
		amount = price.split('m');
		new_amount = parseFloat(amount[0] * 1000000);
	}else {
		amt = price - 0;
		if(!Number(amt)) {
			new_amount = 0;
		}else {
			new_amount = price;
		}
	}
	$(this).val(new_amount);
	$('#c_schedule-show').trigger('change');
});
----*/

$(document).ready(function(){
	var lease_amount=0;
	var check1=0;
	var check2=0;
	var check3=0;
	$('#mfi').show();
	$('#leasing').hide();
	$('#type').on('change', function() {
		var type = $(this).val();
		if(type == 'leasing') {
			$('#leasing').show();
			$('#mfi').hide();
		}else {
			$('#mfi').show();
			$('#leasing').hide();
		}
	});
	
	$('.ch_services').on('ifChanged', function(){
		var total = 0;
		$('.ch_services').each(function() {
			if($(this).is(':checked')) {
				total += $(this).val()-0;
			}
		});
		$('#total_installment_amount').val(formatMoney(total));
	});
	
	$('#asset_price').on('change', function() {
			check1=1;
		var price = $(this).val();
		if(price > 0) {
			$('#p_advance_payment').trigger('change');
			$('#interest_rate').trigger('change');
		}
		$('#p_advance_payment').trigger('change');
		$('#interest_rate').trigger('change');

	});
	
	$('#p_advance_payment').on('change', function() {
			check2=1;
		var total_amount = $('#asset_price').val()-0;
		if(total_amount > 0) {
			var advance_percent = $('#p_advance_payment').val()-0;
			var advance_payment = total_amount * advance_percent;
			lease_amount = total_amount - advance_payment;
			$('#ap-show').text(formatMoney(advance_payment));
			$('#la-show').text(formatMoney(lease_amount));
			$('#lease_amt').val(lease_amount);
			$('#interest_rate').trigger('change');
			
		} else {
			$('#ap-show').text(formatMoney(0));
			$('#la-show').text(formatMoney(0));
			$('#lease_amt').val(0);
		}
		
		
	});
	$('#interest_rate, #term_in_month, #rate_type').on('change', function() {
		check3=1;
		var interest = parseFloat($('#interest_rate').val());
		var term = Number($('#term_in_month').val());
		lease_amount = $('#lease_amt').val()-0;
		var rate_type = $('#rate_type').val();
		if(interest > 0 && term > 0 && lease_amount > 0 && rate_type != '') {
			var installment_amount = getInstallmentAmount(lease_amount, rate_type, interest, term);
			$('#ia-show').text(formatMoney(installment_amount));
			$('.btn_print_payment_schedule').show();
		} else {
			$('#ia-show').text(formatMoney(0));
			$('.btn_print_payment_schedule').hide();
		}	
		
	});
	
	
	
	var count_link=0;
		$('#print_payment_schedule').click(function() {
				var product = 'N/A';
				localStorage.setItem('product', product);	
				
				var dealer = 'N/A';
				localStorage.setItem('dealer', dealer);
				
				var year=	'N/A';
				localStorage.setItem('year', year);	
				var power=	'N/A';
				localStorage.setItem('power', power);
				
				var Advance_payment_rate=$('#p_advance_payment').val();
				localStorage.setItem('Advance_payment_rate', Advance_payment_rate);
				
				var phone='N/A';
				localStorage.setItem('phone', phone);
				var price=$('#asset_price').val();
				localStorage.setItem('price', price);
				
				
				var name='N/A';
				
		
				var rate_type=$('#rate_type').val();
				
				var term_in_month=$('#term_in_month').val();
				localStorage.setItem('term_in_month', term_in_month);
				var interest_rate=$('#interest_rate').val();
				localStorage.setItem('interest_rate', interest_rate);
				var frequency = $('#frequency').val();
				localStorage.setItem('frequency', frequency);
				
				
				localStorage.setItem('name', name);
				
				var leaseamount=lease_amount;
				
				var link= $('<a href="Installment_payment/payment_schedule_preview/'+leaseamount+'/'+rate_type+'/'+interest_rate+'/'+term_in_month+'/'+frequency+'" rel="lightbox" id="print_payment'+count_link+'" data-toggle="modal" data-target="#myModal"></a>');
			
						$("body").append(link);
						  $('#print_payment'+count_link).click();
			count_link++;
			
		});
		<!---mfi-->
		$('#c_interest, #c_terms, #c_rate_type, #c_amount, #c_frequency').on('change', function() {
			var c_interest = parseFloat($('#c_interest').val());
			var c_terms = Number($('#c_terms').val());
			var c_amount = $('#c_amount').val()-0;
			var c_rate_type = $('#c_rate_type').val();
			var c_frequency = $('#c_frequency').val();
			var currency = $('#currency').text();
			if(c_interest > 0 && c_terms > 0 && c_amount > 0 && c_rate_type != '' && c_frequency != '') {				
				var all_total = getAllTotal(c_amount, c_rate_type, c_interest, c_terms, c_frequency);	
				var schedule = getPaymentSchedule(null, c_amount, c_rate_type, c_interest, c_terms, c_frequency, new Date());
				
				$('#c_schedule-show').text(formatMoney(schedule[0]["payment"]));
				$('#c_ap-show').text(formatMoney(all_total["total_interest"]));
				$('.btn_print_payment_schedule_cash').show();
			} else {
				$('#c_monthly-show').text(formatMoney(0));
				$('#ia-show').text(formatMoney(0));
				$('.btn_print_payment_schedule_cash').hide();
			}	
			
		});
	
	
	
	var count_link=0;
		$('#print_payment_schedule').click(function() {
				var product = 'N/A';
				localStorage.setItem('product', product);	
				
				var dealer = 'N/A';
				localStorage.setItem('dealer', dealer);
				
				var year=	'N/A';
				localStorage.setItem('year', year);	
				var power=	'N/A';
				localStorage.setItem('power', power);
				
				var Advance_payment_rate=$('#p_advance_payment').val();
				localStorage.setItem('Advance_payment_rate', Advance_payment_rate);
				
				var phone='N/A';
				localStorage.setItem('phone', phone);
				var price=$('#asset_price').val();
				localStorage.setItem('price', price);
				
				
				var name='N/A';
				
		
				var rate_type=$('#rate_type').val();
				
				var term_in_month=$('#term_in_month').val();
				localStorage.setItem('term_in_month', term_in_month);
				var interest_rate=$('#interest_rate').val();
				localStorage.setItem('interest_rate', interest_rate);
				var frequency = $('#frequency').val();
				localStorage.setItem('frequency', frequency);
				
				
				localStorage.setItem('name', name);
				
				var leaseamount=lease_amount;
				
				var link= $('<a href="Installment_payment/payment_schedule_preview/'+leaseamount+'/'+rate_type+'/'+interest_rate+'/'+term_in_month+'/'+frequency+'" rel="lightbox" id="print_payment'+count_link+'" data-toggle="modal" data-target="#myModal"></a>');
			
						$("body").append(link);
						  $('#print_payment'+count_link).click();
			count_link++;
			
		});
		
		var count_link1=0;
		$('#print_payment_schedule_cash').click(function() {
			var c_interest = parseFloat($('#c_interest').val());
			var c_terms = Number($('#c_terms').val());
			var c_amount = $('#c_amount').val()-0;
			var c_rate_type = $('#c_rate_type').val();
			var c_frequency = $('#c_frequency').val();
			var currency = $('#currency').val();
				
				var link1= $('<a href="quotes/cash_payment_schedule_preview/'+c_amount+'/'+c_rate_type+'/'+c_interest+'/'+c_terms+'/'+c_frequency+'/'+currency+'" rel="lightbox" id="print_payment'+count_link1+'" data-toggle="modal" data-target="#myModal"></a>');
			
			$("body").append(link1);
			$('#print_payment'+count_link1).click();
			count_link1++;
			
		});
	
});
</script>
