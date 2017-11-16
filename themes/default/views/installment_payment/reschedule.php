<script>
	
</script>
<div class="modal-dialog" style="width:70%">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('renew_schedule'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
			echo form_open_multipart("Installment_payment/reschedule/". $id, $attrib);
		?>
        <div class="modal-body">
            <div class="row">
                <div class="well well-sm well_1">
				<!-------->
					<div class="col-sm-12">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= lang('payment') ?></div>
						<div class="panel-body" style="padding: 5px;">
							<div class="col-md-12">
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<?= lang("total_principle", "principle_balnce"); ?>
											<?php echo form_input('principle_balnce',$this->erp->roundUpMoney($balances->balance ? $balances->balance : 0, $sale_item->currency_code), 'class="form-control balance" id="principle_balnce" style="pointer-events: none;" readonly required'); ?>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<?= lang("paid_amount", "total_amount"); ?>
											<?php echo form_input('paid_amount', '', 'class="form-control number_only total_amount" id="total_amount" required'); ?>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<?= lang("owed", "owed"); ?>
											<?php echo form_input('owed', '', 'class="form-control number_only" id="owed" '); ?>
										</div>
									</div>
									
								</div>
							</div>	
						</div>
					</div>
					</div>
					<!--------->
					<div class="col-sm-12">
						<div class="panel panel-primary">
							<div class="panel-heading"><?= lang('services_fee') ?></div>
							<div class="panel-body" style="padding: 5px;">
								<div class="col-sm-12">
									<div class="col-md-4">
										<div class="form-group">
										
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?= lang('amount'); ?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?= lang('split_with_installment'); ?>
										</div>
									</div>
									<div class="col-md-3" style="display:none;">
										<div class="form-group">
											<?= lang('total_services_fee'); ?>
										</div>
									</div>
								</div>
								<?php
								$k = 0;
								foreach($services as $service) {
								?>
								<div class="col-sm-12">
									<div class="col-md-4">
										<div class="form-group">
											<?= $service->description; ?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php echo form_input('service_'.$service->id, (($service->method == 'Percentage')? $this->erp->formatNumber($service->amount) * 100 .'%' : $this->erp->formatMoney($service->amount)), 'class="form-control input-tip services" id="services_'.$k.'" '); ?>																		
											<input type="hidden" name="h_type_<?= $service->id ?>" class="h_type" id="h_type<?= $k ?>" value="<?= $service->method ?>" />
											<input type="hidden" name="service_paid_<?= $service->id ?>" class="service_paid" id="service_paid<?= $k ?>" value="<?= $service->service_paid ?>" />																	
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php 
												echo form_checkbox(['name' => 'ch_services[]', 'id' => $k, 'class' => 'ch_services','service_paid' => $service->service_paid, 'title' => $service->description, 'amount' => $service->amount, 'status' => $service->method, 'value' => $service->id]); 
											?>
										</div>
									</div>
									<?php
									if($k == 0) {
									?>
									<div class="col-md-3" style="display:none;">
										<div class="form-group">
											<?php echo form_input('total_inst', 0, 'class="form-control input-tip" id="total_inst" readonly'); ?>
										</div>
									</div>
									<?php
									} else {
									?>
									<div class="col-md-3">
										<div class="form-group">
										
										</div>
									</div>
									<?php } ?>
								</div>
								<?php
									$k++;
								}
								?>
							</div>
						</div>
					</div>
					<!------>
					<div class="col-sm-12 hide_cash-show">
						<div class="panel panel-primary">
							<div class="panel-heading"><?= lang('financial_products') ?></div>
							<div class="panel-body" style="padding: 5px;">
								<div class="col-lg-6">
									<div class="form-group">
										<?= lang("customer_type", "customer_type"); ?>
										<?php
										$customer_type[(isset($_POST['customer_type']) ? $_POST['customer_type'] : '')] = (isset($_POST['customer_type']) ? $_POST['customer_type'] : '');
										if(array($finacal_products)) {
											foreach ($finacal_products as $cust_type) {
												$customer_type[$cust_type->id] = $cust_type->name;
											}
										}
										echo form_dropdown('customer_type', $customer_type, (isset($_POST['customer_type']) ? $_POST['customer_type'] : ''), 'id="customer_type" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("finacal_product") . '"  class="form-control input-tip select" style="width:100%;"');
										?>
									</div>
									
									<div class="form-group">
										<?= lang("interest_rate", "interest_rate_cash"); ?>
										<?php
										
										?>
										<input type="hidden" name="interest_rate_cash" id="interest_rate_cash" class="interest_rate_cash"/>
										<?= form_input('interest_rate_cash_2', (isset($_POST['interest_rate_cash_2']) ? $_POST['interest_rate_cash_2'] : ''), ' class="form-control" id="interest_rate_cash_2" style="font-size:14px;" ') ?>
									</div>
									<div class="form-group">
										<?= lang("payment_frequency", "frequency_cash"); ?>
										<?php
										$frequency_cash[""] = "";
										$frequency_cash[1] = "Daily";
										$frequency_cash[7] = "Weekly";
										$frequency_cash[14] = "Two Week";
										$frequency_cash[30] = "Monthly";
										//$frequency_cash[90] = "Quarterly";
										//$frequency_cash[180] = "Haft Year";
										$frequency_cash[360] = "Yearly";
										echo form_dropdown('frequency_cash', $frequency_cash, (isset($_POST['frequency_cash']) ? $_POST['frequency_cash'] : ''), 'id="frequency_cash" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("frequency") . '"  class="form-control input-tip select" style="width:100%;"');
										?>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<?= lang("start_installment_date", "st_inst_date"); ?>
										<?php echo form_input('st_inst_date', (isset($_POST['st_inst_date']) ? $_POST['st_inst_date'] : ''), 'class="form-control date" id="st_inst_date"'); ?>
									</div>
									
									<div class="form-group">
										<?= lang("term", "term_cash"); ?>
										<?php
										$term[""] = "";
										if(array($terms)) {
											foreach ($terms as $tm) {
												$term[$tm->amount] = $tm->description;
											}
										}
										echo form_dropdown('term_cash', $term, (isset($_POST['term_cash']) ? $_POST['term_cash'] : ''), 'id="term_cash" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("term") . '" class="form-control input-tip select" style="width:100%;"');
										?>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<?= lang("rate_type", "rate_type_cash"); ?>
										<?php
										$rate_type[""] = "";
										$rate_type["1"] = "Normal";
										$rate_type["2"] = "Fixed";
										$rate_type["3"] = "Normal_Fixed";
										$rate_type["4"] = "Custom";
										echo form_dropdown('rate_type_cash', $rate_type, (isset($_POST['rate_type_cash']) ? $_POST['rate_type_cash'] : ''), 'id="rate_type_cash" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("rate_type") . '"  class="form-control input-tip select" style="width:100%;"');
										?>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										
										<div class="col-lg-6">
											<div class="form-group">
												<?= lang("total_interest_rate", "total_interest_rate"); ?>
												<?php echo form_input('total_interest_rate', (isset($_POST['total_interest_rate']) ? $_POST['total_interest_rate'] : 0), 'class="form-control input-tip" id="total_interest_rate" readonly'); ?>
											</div>
										</div>
										<div class="col-lg-6 btn_print_payment_schedule_cash" style=" padding: 2.5% 0% 1% 1.3%;">
											<input type="button" class="btn btn-primary" value="<?=lang('print_payment_schedule')?>" name="print_payment_schedule_cash" id="print_payment_schedule_cash" />
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					<!----->
                    
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('reschedule', lang('submit'), 'class="btn btn-primary" id="reschedule"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>

<script type="text/javascript">
	$(window).load(function() {
		$("#customer_type").trigger('change');
		$("#interest_rate_cash_2").trigger('change');
		$("#term_cash").trigger('change');
		$("#frequency_cash").trigger('change');
		$("#rate_type_cash").trigger('change');
		$("#interest_rate_cash").trigger('change');
	});
	
	//$('#principle_balnce').val()? parseFloat($('#principle_balnce').val()) : 0; str.replace(',','');
	$(document).ready(function() {		
		$('#total_amount').keyup(function(){
			var total = $('#total_amount').val()? parseFloat($('#total_amount').val()) : 0; 
			var principle = ($('#principle_balnce').val()).replace(/,/g, '');
			if(total > principle){				
				$('#total_amount').val( principle );
			}
			var bl = principle - total ;
			$('#owed').val(bl);
			$("#owed").trigger('change');
		});
	});
	
</script>

<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {
		$('.services').on('change', function() {
			var service_amount = $(this).val();
			var ch_id = $(this).attr('id');
			var c_id = ch_id.split('_');
			var s_amount = service_amount.split('%');
			if(service_amount.search('%') != -1) {
				$('#'+c_id[1]).attr('amount', (s_amount[0]/100));
				$('#'+c_id[1]).attr('status', 'Percentage');
			}else {
				$('#'+c_id[1]).attr('amount', s_amount[0]);
				$('#'+c_id[1]).attr('status', 'Fixed_Amount');
			}
			$('.ch_services').trigger('ifChanged');
		});
	});
	
	$(document).ready(function () {
		
	});
	
	$(document).ready(function () {
		$(' #total_amount, #interest_rate_cash_2').live('change', function(e) {
			var interest_rate_cash = $('#interest_rate_cash_2').val().toLowerCase();
			var interest_rate = 0;
			if(interest_rate_cash.search('%') > 0) {
				interest_rate_cash = interest_rate_cash.replace('%', '');
				interest_rate = (interest_rate_cash/100);
			}else {
				rate = interest_rate_cash - 0;
				if(!Number(rate)) {
					interest_rate = 0;
				}else {
					interest_rate = interest_rate_cash;
				}
			}
			$('#interest_rate_cash').val(interest_rate);
			$('#interest_rate_cash').trigger('change');
		});
		
		$('#interest_rate_cash, #term_cash, #owed, #rate_type_cash, #frequency_cash').on('change', function() {
			var interest = parseFloat($('#interest_rate_cash').val());
			var term = Number($('#term_cash').val());
			var lease_amount = parseFloat($('#owed').val());
			var rate_type = $('#rate_type_cash').val();
			var frequency_cash = (($('#frequency_cash').val() > 0)? $('#frequency_cash').val() : '');
			//alert(interest +"###"+ term +"###"+ lease_amount +"###"+ rate_type +"###"+ frequency_cash);
			if(lease_amount > 0 && interest > 0 && term > 0 && rate_type != '' && frequency_cash > 0) {
				var all_total = getAllTotal(lease_amount, rate_type, interest, term, frequency_cash);
				$('#total_interest_rate').val(formatMoney(all_total['total_interest']));
				$('.btn_print_payment_schedule_cash').show();
			} else {
				$('#total_interest_rate').val(formatMoney(0));
				$('.btn_print_payment_schedule_cash').hide();
			}		
		});
		
		var count_link1=0;
		$('#print_payment_schedule_cash').live( "click", function() {
			
			var i = 0;
			$('.ch_services').each(function() {
				if($(this).is(':checked')) {
					if(i == 0) {
						services = $(this).val();
					}else {
						services = '___'+ $(this).val();
					}
					i += 1;
				}
			});
			
			var lease_amount = $('#owed').val();
			var interest_rate = $('#interest_rate_cash').val();
			var term_cash = $('#term_cash').val();
			var rate_type = $('#rate_type_cash').val();
			var frequency_cash = $('#frequency_cash').val();
			var currency = $('#currency').val();
			var cdate = ($('#st_inst_date').val()).split('/');
			var new_date = '';
			for(var i = 0; i < cdate.length; i++) {
				if(i == 0) {
					new_date = cdate[i];
				}else {
					new_date += '___'+cdate[i];
				}
			}
			
			//alert(lease_amount +'###'+ interest_rate +'###'+ term_cash +'###'+ rate_type +'###'+ frequency_cash);
			
			var services = '';
			$(".ch_services:checked").each(function(){	
				var s_id = $(this).val();
				var amount = $(this).attr('amount');
				var type = $(this).attr('status');
				var service_paid = $(this).attr('service_paid');
				if(services == '') {
					services = s_id +"__" + amount + "__" + type +"__" + service_paid ;
				}else {
					services += "___" + s_id +"__" + amount + "__" + type +"__" + service_paid ;
				}
			});
			//alert(services)
			if(services == '') {
				var link1= $('<a href="Quotes/cash_payment_schedule_preview/'+lease_amount+'/'+rate_type+'/'+interest_rate+'/'+term_cash+'/'+frequency_cash+'/'+currency+'/'+new_date+'" rel="lightbox" id="print_payment'+count_link1+'" data-toggle="modal" data-target="#myModal"></a>');
			}else {
				var link1= $('<a href="Quotes/cash_payment_schedule_preview/'+lease_amount+'/'+rate_type+'/'+interest_rate+'/'+term_cash+'/'+frequency_cash+'/'+currency+'/'+new_date+'/'+services+'" rel="lightbox" id="print_payment'+count_link1+'" data-toggle="modal" data-target="#myModal"></a>');
			}
				$("body").append(link1);
				$('#print_payment'+count_link1).click();
			count_link1++;
		});
	});
	
	$(document).ready(function () {
		/*----Amount----*/
		$('#total_amount').live('change', function(e) {
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
			$('.ch_services').trigger('ifChanged');
			$('#interest_rate_cash').trigger('change');
		});		
	});
	
	
</script>