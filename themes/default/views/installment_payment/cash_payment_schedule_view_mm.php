<link href="https://fonts.googleapis.com/css?family=Battambang" rel="stylesheet"> 
 <?php
	$sale->total = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $sale->total);
	$service->amount = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $service->amount);
	
?>
 <style type="text/css">
    .container {
        width: 800px;
        margin-left: auto;
        margin-right: auto;
	}	
	.t_c{text-align:center;}
	.t_r{text-align:center;}
    @media print
	{    
		.no-print, .no-print *
		{
			display: none !important;
		}
		#hidden_print{display:none;}
		#hidden-total{display:none;}
		#hide_action{display:none;}
		#hide_action2{display:none;}
		#hide_action3{display:none;}
	}
	.kh_m{
		font-family: "Khmer OS Muol";
	}
	.b_top{border-top:1px solid black;}
	.b_bottom{border-bottom:1px solid black}
	.b_left{border-left:1px solid black;}
	.b_right{border-right:1px solid black;}
	.text-bold td{font-weight:bold;}
	.p_l_r td{padding-left:5px;padding-right:5px;}
	.top_info tr td{
		height:25px;
	}
	.color_blue{color:#3366cc;}
	#logo img{
		width:110px;
	}
	.border table{
		border:1px solid gray;
	}
	.border table tr td{
		border-bottom:1px solid gray;
	}
</style>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('payment_schedule'); ?></h4>
        </div>
        <div class="modal-body">
			<div class="row">
				<div class="container">
					<div>
						<div style="float:left;width:25%;" id="logo">
							<span> <?php if ($Settings->logo2) {
                            echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" style="margin-bottom:10px;" />';
							} ?> </span> 
						</div>
						
						<div style="float:left;width:50%; font-family:Battambang">	
							<center><b>
								<span class="kh_m"><b> <?php echo $setting->site_name ?></span><br/>
								<span>လိုင္စင္ရေငြေရးေၾကးေရးလုပ္ငန္း</span><br/>
								<span style="font-size:18px;"> Repayment Schedule for Group Loan</span><br/>
							</center></b>
						</div>
						<!-- <div style="float:left;width:25%;">
								<center><span style="line-height:140%; font-size:12px; font-weight:normal;"><?=lang("agree_to_pay_by_schedule")?> <br/><?=lang("date") ?>: <?= $this->erp->hrsd(date('Y-m-d')); ?></span></center>
						</div> -->
					</div>
					<table style="width:100%;border-top: 1px solid black;border-bottom: 1px solid black;margin-top:5px;"> <!-- MSM add 1/12/2017-->
						<tr>
							<td width="32%">Group ID : </td>
							<td width="32%">Account ID : <?=$sale->reference_no;?> </td>
							<td width="32%">Name : <b><?php echo $customer->family_name_other.' '.$customer->name_other; ?></b></td>
						</tr>
					</table>
					<table style="width:90%;font-size:11px;margin-top:5px;"> 
						<tr>
							<td width="5%">ထုတ္ေခ်းသည့္ရက္စြဲ </td>
							<td width="10%">: <?=lang("date") ?>: <?= $this->erp->hrsd(date('Y-m-d')); ?></td>
							<td width="1%">ေခ်းေငြသက္တမ္း</td>
							<td width="10%">:<?= number_format($sale->term,0); ?>  <?= lang("days") ?> </td>
							<td width="5%">ေပးေခ်သည့္ပံုစံ</b></td>
							<td width="5%">:</td>
						</tr>
						<tr>
							<td width="5%">အတိုးႏွဳန္း</td>
							<td width="10%">:</td>
							<td width="1%">ေခ်းေငြတာ၀န္ခံအမွတ္</td>
							<td width="10%">:</td>
							<td width="5%">စတင္ေပးေခ်းရမည့္ရက္စြဲ</b></td>
							<td width="5%">:</td>
						</tr>
						<tr>
							<td width="5%">ရံုးလိပ္စာ	</td>
							<td colspan="3">:</td>
							
						</tr>
						
					</table>

					<div>	
						<table style="font-size:11px;width:100%;margin-top: 10px;"  border="1">
							<tr class="p_l_r" style="background-color:#009900;color:white;width:100%; height:30px;">
								<td style="text-align: center;" colspan="2">Due Date</td>
								<td style="text-align: center;">Balance</td>
								<td style="text-align: center;">Principle</td>
								<td style="text-align: center;">Interest</td>
								<td style="text-align: center;">Total Due</td>
								<td style="text-align: center;">Signature</td>
							</tr>
							<tr>
								<td style="text-align: center;">1</td>
								<td style="text-align: center;">01-12-2018</td>
								<td style="text-align: center;">500,00</td>
								<td style="text-align: center;">500,000</td>
								<td style="text-align: center;">12,500</td>
								<td style="text-align: center;">62,500</td>
								<td style="text-align: center;"></td>
							</tr>
							<tr>
								<td style="text-align: center;">1</td>
								<td style="text-align: center;">01-12-2018</td>
								<td style="text-align: center;">500,00</td>
								<td style="text-align: center;">500,000</td>
								<td style="text-align: center;">12,500</td>
								<td style="text-align: center;">62,500</td>
								<td style="text-align: center;"></td>
							</tr>
							<tr>
								<td style="text-align: center;">1</td>
								<td style="text-align: center;">01-12-2018</td>
								<td style="text-align: center;">500,00</td>
								<td style="text-align: center;">500,000</td>
								<td style="text-align: center;">12,500</td>
								<td style="text-align: center;">62,500</td>
								<td style="text-align: center;"></td>
							</tr>
							<tr>
								<td style="text-align: center;">1</td>
								<td style="text-align: center;">01-12-2018</td>
								<td style="text-align: center;">500,00</td>
								<td style="text-align: center;">500,000</td>
								<td style="text-align: center;">12,500</td>
								<td style="text-align: center;">62,500</td>
								<td style="text-align: center;"></td>
							</tr>
							<tr>
								<td style="text-align: center;">1</td>
								<td style="text-align: center;">01-12-2018</td>
								<td style="text-align: center;">500,00</td>
								<td style="text-align: center;">500,000</td>
								<td style="text-align: center;">12,500</td>
								<td style="text-align: center;">62,500</td>
								<td style="text-align: center;"></td>
							</tr>
							<tr>
								<td style="text-align: center;">1</td>
								<td style="text-align: center;">01-12-2018</td>
								<td style="text-align: center;">500,00</td>
								<td style="text-align: center;">500,000</td>
								<td style="text-align: center;">12,500</td>
								<td style="text-align: center;">62,500</td>
								<td style="text-align: center;"></td>
							</tr>
							<tr>
								<td style="text-align: center;" colspan="2">1</td>
								<td style="text-align: center;"></td>
								<td style="text-align: center;">500,00</td>
								<td style="text-align: center;">500,000</td>
								<td style="text-align: center;">12,500</td>
								<td style="text-align: center;"></td>
							</tr>
						</table>
					</div>


					<!-- MSM end-->		

					
					
					<div style="margin-top: 10px; margin-bottom: 10px; font-weight:normal">
						<table style="font-size:11px;">
							<tr valign="top">
								<td style="width:110px;"> <b> <?= lang("note") ?> :</b> <td>
								<td height="30%"><?= lang("payment_note")?><td>
							</tr>
							<tr>
								<td><td>
								<td>- <?=lang("the_contract_does_not_comply")?> <b><?php echo $setting->site_name ?> </b> &nbsp <?= lang("company_will_take_legal_action") ?><td>
							</tr>
						</table>
					</div>

				</div>
			</div>
        </div>
        
		<div class="modal-footer no-print">
			<a href="<?=base_url().'Installment_payment/export_loan/0/1/'.$sale_id; ?>">
				<div class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;">
					<i class="fa fa-file-excel-o "></i> <?= lang('export_excel'); ?>
				</div>
			</a>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
			
        </div>
		<!--<div class="buttons">
		 
			<div class="btn-group btn-group-justified no-print">
				<div class="btn-group">
					<a href="<?php echo base_url().'Installment_payment/export_loan/0/1/'.$sale_id; ?>"  class="tip btn btn-primary" id="" title="<?= lang('export') ?>">
						<i class="fa fa-money"></i>
						<span class="hidden-sm hidden-xs"><?= lang('excel') ?></span>
					</a>
				</div>
			</div>
        </div>-->
    </div>
</div>
<?= isset($modal_js) ?$modal_js  : ('') ?>
<script>

	$(document).ready(function() {
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
				var sale_id = <?= $sale->id; ?>;
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
				});
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