<style type="text/css">
	.table table{
		border-collapse: collapse;
	}

	.table table {
		font-family: 'Khmer OS'; 
		color: #000000;
		font-size: 12px;
	}
	
	.put border td{border:1px solid black;}
	.text-info{
		margin-left:35px;
	}
	.t_c{text-align:center;}
	.t_r{text-align:right;}
	.td_dashed{border:1px dashed black;}
	.color_green{color:#009900;}
	.color_blue{color:#3366CC;}
	.row-title td{color:white;background-color:#009900;padding-top:5px;padding-bottom:5px;text-align:center;}
	.row-data td{text-align:center;}
	.padding_l_r_5{padding-left:5px;padding-right:5px;}
	
	@media print
	{    
		.no-print, .no-print *
		{
			display: none !important;
		}
	}
</style>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header no-print">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('payment_schedule'); ?></h4>
			
        </div>
        <div class="modal-body">
			<div class="row">
				<div class="container">
					<center><p style="font-weight:bold;">កាលវិភាគបង់ប្រាក់ប្រចាំខែ Monthly Payment Schedule</p></center>
					
						<table width="100%" style="font-size:11px;">
							<tr>
								<td width="">ឈ្មោះអតិថិជន </td>
								<td width="">: <span class="color_blue" id="name111">
								<?php
								if($mydata){
									echo $mydata->fname.' '.$mydata->lname;
										if(isset($mydata->khfname) && isset($mydata->khlname) ){
											echo '( '.$mydata->khfname.' '.$mydata->khlname.' )';
										}
								}else{echo '';}		
								?>	
								</span></td>
								<td>Dealer Name</td>
								<td><span class="color_blue">: 
								<?php
								if($mydata){
									echo $mydata->dfname.' '.$mydata->dlname;
										if(isset($mydata->dkhfname) && isset($mydata->dkhlname) ){
											echo '( '.$mydata->dkhfname.' '.$mydata->dkhlname.' )';
										}
								}else{echo '';}		
								?>
								</span></td>
							</tr>
							<tr>
								<td width="10%">អាសយដ្ឋាន </td>
								<td width="50%"><span class="color_blue">: <?php echo '#'.$mydata->house_no.','.$mydata->street.','.$address ?></span></td>
								<td width="10%">LID Number</td>
								<td>: <span class="color_blue"> <?=$mydata->lid;?></span></td>
							</tr>
							<tr>
								<td>លេខទួរស័ព្ទ​ </td>
								<td>: <span class="color_blue" id="phone"><?php 
								echo $mydata->phone;
								if($mydata->phone1){
									echo ' / '.$mydata->phone1;
								}
								?></span></td>
								<td>លេខអតិថិជន</td>
								<td>:  <span class="color_blue"><?=$mydata->cid;?></span></td>
							</tr>
							<tr>
								<td>លេខតូដក្រុមហ៊ុន </td>
								<td>:  <span class="color_blue">N/A</span> </td>
								<td></td>
								<td></td>
							</tr>
						</table>
						
						<table  style="margin-top:15px;font-size:11px;border:2px solid black;width:100%;">
							<tr><td height="10px" style="width: 250px;"></td><td class=" padding_l_r_5 "></td><td style=""></td><td class=" padding_l_r_5"></td><td style="width:20px;"></td></tr>
							<tr>
								<td class="t_r">ប្រភេទម៉ូតូ (Motorcycle model)</td>
								<td  class="t_c td_dashed color_blue" id="product"><?=$mydata->product_name;?></td>
								<td  class="t_r">ទឹកប្រាក់ភតិសន្យា (lease amount)</td>
								<td  class="t_c td_dashed color_green"><?=$this->erp->formatMoney(($mydata->product_price)-($mydata->ap)); ?></td><td></td>
							</tr>
							
							<tr>
								<td  class="t_r">ឆ្នាំផលិត​ (Year)</td>
								<td  class="t_c td_dashed color_blue" id="year111"><?=$mydata->product_year;?></td>
								<td  class="t_r">អត្រាការប្រាក់​ (Interest rate)</td>
								<td  class="t_c td_dashed color_blue" id="interest_rate111"><?php echo ($mydata->interest_rate*100).'%';?></td><td></td>
							</tr>
							
							<tr>
								<td  class="t_r">កំលាំងម៉ាស៊ីន(CC)</td>
								<td  class="t_c td_dashed color_blue" id="power111"><?=$mydata->product_power;?></td>
								<td  class="t_r">ចំនួនវិភាគសង(Number of payments)</td>
								<td  class="t_c td_dashed color_blue" id="term_in_month111"> <?=number_format($mydata->term);?></td><td></td>
							</tr>
							
							<tr>
								<td  class="t_r">តម្លៃម៉ូតូ(Price)</td>
								<td  class="t_c td_dashed color_green" id="price"><?=$this->erp->formatMoney($mydata->product_price);?></td>
									<td  class="t_r">ការិយបរិច្ខេទកិច្ចសន្យា(Contract date)</td>
								<td  class="t_c td_dashed color_blue"><?=$this->erp->hrsd($mydata->cdate);?></td><td></td>
							
							</tr>
							
							<tr>
								<td  class="t_r">អត្រាប្រាក់បង់មុន(Advance payment rate)</td>
								<td  class="t_c td_dashed color_blue" id="advance_payment_rate111"><?php echo ($mydata->apr*100).'%';?></td>
							</tr>
							
							<tr>
								<td  class="t_r">អត្រាប្រាក់បង់មុន(Advance payment)</td>
								<td  class="t_c td_dashed color_green"><?php echo $this->erp->formatMoney($mydata->ap)?></td>
							
							</tr>
							<tr><td height="10px;"></td><td></td><td></td></tr>
						</table>
					
					
					
						<table class="schedule" style="margin-top:15px;font-size:11px;border-collapse:collapse;width:100%;">
						
							<tr class="row-title">
								<td class="t_c"><div  style="background-color: white; width: 21px; margin-left: 5px;"><input type="checkbox" name="all_check" id="all_check"></div></td>
								<td>​លរ</td>
								<td>កាលបរិច្ឆេទ</td>
								<td>ប្រាក់នៅជំពាក់​</td>
								<td>ប្រាក់បានបង់​</td>
								<td>សរុបប្រាក់ត្រូវបង់</td>
								<td>ប្រាក់​ពិន័យ</td>
								<?php 
								if($services) {
									foreach($services as $service) { 
								?>
									<td><?= $service->description_other; ?></td>
								<?php 
									} 
								}
								?>
								<td>ប្រាក់ដើមត្រូវបង់</td>
								<td>ការប្រាក់ត្រូវបង់</td>
								<td>សមតុល្យ</td>
							</tr>
								
							
							<?php
							$penalty_days = $setting->penalty_days;
							$penalty_rate = $setting->penalty_amount;
							$k=1;
							if($pts) {
								foreach ($pts as $data) {
									$princ=$this->erp->formatMoney($data->principle);
									$interest=$this->erp->formatMoney($data->interest);
									
									$dateline = date('Y-m-d', strtotime($data->dateline));
									$final_dateline = date('Y-m-d', strtotime("+".$penalty_days." days", strtotime($dateline)));
									$current_date = date('Y-m-d');
									$ovamounts = 0;
									if($final_dateline < $current_date) {
										$ovdays = (strtotime($current_date) - strtotime($dateline))/(3600 * 24);
										$ovamounts = $ovdays * $penalty_rate;
									}
									
									$overdue_amt = (($data->paid_amount > 0)? $data->overdue_amount : $ovamounts);
									$payment = $data->payment + $overdue_amt;
									$paid = $data->paid_amount? $data->paid_amount : 0;
									$other_paid = $data->other_amount? $data->other_amount : 0;
									$services_charge = $data->total_service_charge? $data->total_service_charge : 0;
									$paid_amount = $paid + $other_paid + $services_charge + (($data->paid_amount > 0)? $overdue_amt : 0);
									$balance = $payment - $paid_amount;
									$balance_moeny=$this->erp->formatMoney($data->balance);
									if($balance_moeny<=0){
										$balance_moeny=$this->erp->formatMoney(0);
									}
									
									echo '<tr class="row-data" '.(($data->paid_amount > 0)? 'style="background-color:#B4D8E8;"':'').'>
											<td class="t_c"><input type="checkbox" name="ch_check[]" class="ch_check" value="'.(($this->erp->formatMoney($balance) > 0)? $data->id:'').'" '.(($data->paid_amount > 0)? 'checked':'').'></td>
											<td class="t_c">'.$k.'</td>
											<td class="t_c">'. $this->erp->hrsd($data->dateline).'</td>
											<td class="t_r">'.$this->erp->formatMoney(($balance > 0)? $balance : 0).'</td>
											<td class="t_r">'.$this->erp->formatMoney($paid_amount).'</td>
											<td class="t_r">'.$this->erp->formatMoney($payment).'</td>
											<td class="t_r">'.$this->erp->formatMoney($overdue_amt).'</td>';
										if($services) {
											foreach($services as $service) {
									echo 	'<td class="t_r">N/A</td>';
											}
										}
									echo'	<td class="t_r">'.$this->erp->formatMoney($data->principle).'</td>
											<td class="t_r">'.$this->erp->formatMoney($data->interest).'</td>
											<td class="t_r">'.$balance_moeny.'</td>
										</tr>';
									
									$k++;
									
								}
							}?>
							
						</table>
				</div>
			</div>		
        </div>
        <div class="buttons">
			<div class="btn-group btn-group-justified no-print">
				<div class="btn-group">
					<a href="#" data-toggle="modal" data-target="#myModal2" class="add_payment tip btn btn-primary" id="add_payment" title="<?= lang('add_payment') ?>">
						<i class="fa fa-money"></i>
						<span class="hidden-sm hidden-xs"><?= lang('add_payment') ?></span>
					</a>
				</div>
				<div class="btn-group">
					<a href="#" data-toggle="modal" data-target="#myModal2" class="pdf tip btn btn-primary" id="pdf" title="<?= lang('add_payment') ?>">
						<i class="fa fa-money"></i>
						<span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
					</a>
				</div>
				<div class="btn-group">
					<a href="#" data-toggle="modal" data-target="#myModal2" class="excel tip btn btn-primary" id="excel" title="<?= lang('add_payment') ?>">
						<i class="fa fa-money"></i>
						<span class="hidden-sm hidden-xs"><?= lang('excel') ?></span>
					</a>
				</div>
				<div class="btn-group">
					<a class="tip btn btn-warning" title="<?= lang('print') ?>" onclick="window.print();">
						<i class="fa fa-print"></i>
						<span class="hidden-sm hidden-xs"><?= lang('print') ?></span>
					</a>
				</div>
			</div>
        </div>
    </div>
</div>
<?= isset($modal_js) ?$modal_js  : ('') ?>
<script type="text/javascript">
	$(document).ready(function () {
		$('#all_check').on('ifChanged', function(){
			if($(this).is(':checked')) {
				$('.ch_check').each(function() {
					$(this).iCheck('check');
				});
			}else{
				$('.ch_check').each(function() {
					$(this).iCheck('uncheck');
				});
			}
		});
		$('#add_payment').on('click', function() {
			if($(".schedule .ch_check:checked").length > 0){
				var sale_id = <?= $sale_id; ?>;
				var i = 0;
				var loans_id = '';
				$(".schedule .ch_check:checked").each(function(){	
					if($(this).val()) {
						if(i == 0) {
							loans_id = $(this).val();
						}else{
							loans_id += '_'+$(this).val();
						}
						i += 1;
					}
				}); //alert(loans_id); return false;
				if(loans_id){
					$(this).attr('href', "<?= site_url('Installment_payment/add_payment/') ?>/" + sale_id +'/'+loans_id);
				}else{
					alert("Please check..");
					return false;
				}
			}else {
				alert("Please check..");
				return false;
			}
		});
	});	
</script>