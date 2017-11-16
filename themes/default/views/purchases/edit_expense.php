

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_expense'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("purchases/edit_expense/" . $expense->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			
			<div class="form-group company">
				<?= lang("branch", "branch"); ?>
				<?php
					$all_branch[(isset($_POST['branch']) ? $_POST['branch'] : '')] = (isset($_POST['branch']) ? $_POST['branch'] : '');
					if(array($branchs)) {
						foreach($branchs as $branch) {
							$all_branch[$branch->id] = $branch->name;
						}
					}
					echo form_dropdown('branch', $all_branch, (isset($_POST['branch']) ? $_POST['branch'] : $expense->biller_id), 'id="branch" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("branch") . '" required="required" style="width:100%;" ');
				?>
			</div>

			<div class="form-group">
				<?= lang("date", "date"); ?>
				<?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : $this->erp->hrld($expense->date)), 'class="form-control datetime" id="date" required="required"'); ?>
			</div>
			
            <div class="form-group">
                <?= lang("reference", "reference"); ?>
                <?= form_input('reference', (isset($_POST['reference']) ? $_POST['reference'] : $expense->reference), 'class="form-control tip" id="reference" required="required" readonly'); ?>
            </div>
			
			<div class="form-group">
				<?= lang("chart_account", "chart_account"); ?>
				<?php
				$acc_section = array(""=>"");
				foreach($chart_accounts as $section){
					$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
				}
					echo form_dropdown('account_code', $acc_section, $expense->account_code ,'id="account_section" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("Account") . ' ' . $this->lang->line("Section") . '" required="required" style="width:100%;" ');
				?>
			</div>
			
			<div class="form-group">
				<?= lang("paid_by", "paid_by"); ?>
				<?php
				
				$acc_section = array(""=>"");
				foreach($paid_by as $section){
					$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
				}
					echo form_dropdown('paid_by', $acc_section, $expense->bank_code ,'id="paid_by" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("paid_by") . '" required="required" style="width:100%;" ');
				?>
			</div>
			<div class="form-group">
				<?php echo lang('currency', 'currency') ?>
				<?php
				$crr[(isset($_POST['currency']) ? $_POST['currency'] : '')] = (isset($_POST['currency']) ? $_POST['currency'] : '');
				if(array($currencies)) {
					foreach($currencies as $currency){
						$crr[$currency->code] = $currency->name;
					}
				}
				echo form_dropdown('currency', $crr,(isset($expense->currency_code) ? $expense->currency_code  : ''), 'class="form-control currency" id="currency" placeholder="' . lang("select_currency") . '"');
				?>
			</div>
			<div class="form-group">
				<?= lang("amount", "amount"); ?>
				<?php  
					$amount = $this->erp->convertCurrency($expense->currency_code, $setting->default_currency, $expense->amount )
				?>
				<input name="amount" type="text" id="amount" value="<?= $this->erp->formatDecimal($amount); ?>" class="pa form-control kb-pad amount"/>
				<input type="hidden" name="bramount" id="bramount" />
			    <input type="hidden" name="branch_id" id="branch_id" />
			    <input type="hidden" name="bank_code" id="bank_code" />
			</div>
			
			<?php
				foreach($currency as $money){
				if($money->code == 'USD'){

					}else{
			?>
				<!--<div class="form-group">
					<?= lang("amount", "amount").($money->code == 'USD' ? ' (USD)' : ' (Rate: USD1 = '.$money->code.' '.number_format($money->rate).')'); ?>
					<input name="other_amount[]" type="text" id="<?=$money->code;?>" value="<?= $this->erp->formatDecimal(($expense->amount) * ($money->rate)); ?>" rate="<?=$money->rate?>" class="pa form-control kb-pad amount_other"/>
				</div>-->
			<?php
					}
				}
			?>

            <div class="form-group">
                <?= lang("attachment", "attachment") ?>
                <input id="attachment" type="file" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="form-control file">
            </div>

            <div class="form-group">
                <?= lang("note", "note"); ?>
                <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : $expense->note), 'class="form-control" id="note"'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_expense', lang('edit_expense'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>
<script type="text/javascript" charset="UTF-8">

	$(document).ready(function () {	
		$('#paid_by, #branch').change(function(){
			var branch_id = $('#branch').val();
			var bank_code = $('#paid_by').val();
			$('#branch_id').val(branch_id);
			$('#bank_code').val(bank_code);
			$.ajax({
				url: site.base_url + 'purchases/ajaxBranchBalance/'+ branch_id +'/' + bank_code,
				dataType: 'json',
				success: function(scdata){
					$('#bramount').val(scdata.amount);
				},
				error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                }
			});
		}).trigger('change');
		
		$('#amount').keyup(function(){
			var amount = $('#amount').val()? parseFloat($('#amount').val()) : 0;
			var bramount = $('#bramount').val()? parseFloat($('#bramount').val()) : 0;
			if (amount > bramount){
				$('#amount').val(formatDecimal(bramount));	
			}					
		});
		
    });

    $(document).ready(function () {
		var rate = '<?=$KHM;?>';
		var code = 0;
		var value = 0;
		var rate = 0;
        $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
		var array = <?php echo json_encode($currency) ?>;
		function formatDecimals(x) {
			return parseFloat(parseFloat(x).toFixed(7));
		}
		
		function autotherMoney(value){
            $(".amount_other").each(function(){
                var rate = $(this).attr('rate');
				if(value != 0){
					$(this).val(formatDecimals(value*rate));
				}else{
					$(this).val('0');
				}
            });
        }
        function autoMoney(value, rate){
        	$(".amount_other").each(function(){
				if(value != 0){
					$('input[name="amount"]').val(formatDecimals(value / rate));
				}else{
					$('input[name="amount"]').val('0');
				}
            });
        }
		$('input[name="amount"]').live('change keyup paste',function(){
			value = $(this).val();
			autotherMoney(value);
		});

		$('input[name="other_amount[]"]').live('change keyup paste',function(){
			value = $(this).val();
			rate = $(this).attr('rate');
			//alert(rate);
			var val = value / rate;
			autoMoney(value, rate);
			autotherMoney(val);
		});
    });
</script>
