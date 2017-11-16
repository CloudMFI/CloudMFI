<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_payment'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("sales/edit_payment/" . $payment->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
                <?php if ($Owner || $Admin) { ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?= lang("date", "date"); ?>
                            <?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : $this->erp->hrld($payment->date)), 'class="form-control datetime" id="date" required="required"'); ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("reference_no", "reference_no"); ?>
                        <?= form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $payment->reference_no), 'class="form-control tip" id="reference_no" required="required" style="pointer-events: none;"'); ?>
                    </div>
                </div>

                <input type="hidden" value="<?php echo $payment->sale_id; ?>" name="sale_id"/>
            </div>
            <div class="clearfix"></div>
            <div id="payments">

                <div class="well well-sm well_1">
                    <div class="col-md-12">
                        <div class="row">
							<div class="col-sm-6">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= lang("applicant", "applicant"); ?>
                                        <input value=" <?= $payment->customer ?> " type="text"
                                               id="applicant" class=" form-control kb-pad " style="pointer-events: none;"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= lang("principle", "principle"); ?>
                                        <input value="<?= $this->erp->formatDecimal($payment->principle_amount); ?>" type="text"
                                               id="principle" class="pa form-control kb-pad " name="principle"/>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-sm-6">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= lang("interest", "interest"); ?>
                                        <input value="<?= $this->erp->formatDecimal($payment->interest_amount); ?>" type="text"
                                               id="interest" class="pa form-control kb-pad " name="interest"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= lang("services", "services"); ?>
                                        <input value="<?= $this->erp->formatDecimal($payment->service_amount); ?>" type="text"
                                               id="services" class="pa form-control kb-pad " name="services"/>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-sm-6">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= lang("penalty", "penalty"); ?>
                                        <input  value="<?= $this->erp->formatDecimal($payment->penalty_amount); ?>" type="text"
                                               id="penalty" class="pa form-control kb-pad " name="penalty"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= lang("other_paid", "other_paid"); ?>
                                        <input  value="<?= $this->erp->formatDecimal($payment->other_paid); ?>" type="text"
                                               id="other_paid" class="pa form-control kb-pad amount" name="other_paid"/>
                                    </div>
                                </div>
                            </div>
							
                            <div class="col-sm-6">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= lang("loan_requests", "amount"); ?>
                                        <input  value="<?= $this->erp->formatDecimal($payment->amount); ?>" type="text"
                                               id="amount_1" class="pa form-control kb-pad " name="amount"/>
                                    </div>
                                </div>
                            </div>
							<div class="col-sm-6">
                                <div class="form-group">
                                    <?= lang("paying_by", "paid_by_1"); ?>
                                    <select name="paid_by" id="paid_by_1" class="form-control paid_by">
                                        <option
                                            value="cash"<?= $payment->paid_by == 'cash' ? ' checked="checcked"' : '' ?>><?= lang("cash"); ?></option>
                                        <option
                                            value="wing"<?= $payment->paid_by == 'wing' ? ' checked="checcked"' : '' ?>><?= lang("wing"); ?></option>
                                        <option
                                            value="visa card"<?= $payment->paid_by == 'visa_card' ? ' checked="checcked"' : '' ?>><?= lang("visa_card"); ?></option>
                                        <option
                                            value="other"<?= $payment->paid_by == 'other' ? ' checked="checcked"' : '' ?>><?= lang("other"); ?></option>
                                    </select>
                                </div>
                            </div>
							<div class="col-md-6">
								<div class="form-group">
									<?= lang("payment_status", "payment_status"); ?>
									<?php
									$payment_status[""] = "";
									$payment_status["received"] = lang("paid");
									$payment_status["partial"]  = lang("partial");
									$payment_status["deposit"]  = lang("deposit");
									echo form_dropdown('payment_status', $payment_status, '', 'class="form-control select" id="payment_status" placeholder="' . lang("select") . ' ' . lang("payment_status") . '"  data-bv-notempty="true"');
									?>
								</div>
							</div>
                        </div>
						
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-6" id="payment" style="display:none;">
									<div class="form-group" id="pay_deposit">
										<?= lang("payment", "pay"); ?>
										<?php echo form_input('pay', '', 'class="form-control pay_deposit" id="pay"'); ?>
									</div>
								</div>
								<div class="col-md-6" id="balance" style="display:none;">
									<div class="form-group">
										<?= lang("balance", "bl"); ?>
										<?php echo form_input('bl', $this->erp->formatMoney($total_amount), 'class="form-control" id="bl" style="pointer-events: none;"'); ?>
									</div>
								</div>
							</div>
						</div>
						
                        <div class="clearfix"></div>
                        <div class="pcc_1" style="display:none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input name="pcc_no" value="<?= $payment->cc_no; ?>" type="text" id="pcc_no_1"
                                               class="form-control" placeholder="<?= lang('cc_no') ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <input name="pcc_holder" value="<?= $payment->cc_holder; ?>" type="text"
                                               id="pcc_holder_1" class="form-control"
                                               placeholder="<?= lang('cc_holder') ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="pcc_type" id="pcc_type_1" class="form-control pcc_type"
                                                placeholder="<?= lang('card_type') ?>">
                                            <option
                                                value="Visa"<?= $payment->cc_type == 'Visa' ? ' checked="checcked"' : '' ?>><?= lang("Visa"); ?></option>
                                            <option
                                                value="MasterCard"<?= $payment->cc_type == 'MasterCard' ? ' checked="checcked"' : '' ?>><?= lang("MasterCard"); ?></option>
                                            <option
                                                value="Amex"<?= $payment->cc_type == 'Amex' ? ' checked="checcked"' : '' ?>><?= lang("Amex"); ?></option>
                                            <option
                                                value="Discover"<?= $payment->cc_type == 'Discover' ? ' checked="checcked"' : '' ?>><?= lang("Discover"); ?></option>
                                        </select>
                                        <!-- <input type="text" id="pcc_type_1" class="form-control" placeholder="<?= lang('card_type') ?>" />-->
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input name="pcc_month" value="<?= $payment->cc_month; ?>" type="text"
                                               id="pcc_month_1" class="form-control"
                                               placeholder="<?= lang('month') ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">

                                        <input name="pcc_year" value="<?= $payment->cc_year; ?>" type="text"
                                               id="pcc_year_1" class="form-control" placeholder="<?= lang('year') ?>"/>
                                    </div>
                                </div>
                                <!--<div class="col-md-3">
                                                        <div class="form-group">
                                                            <input name="pcc_ccv" type="text" id="pcc_cvv2_1" class="form-control" placeholder="<?= lang('cvv2') ?>" />
                                                        </div>
                                                    </div>-->
                            </div>
                        </div>
                        <div class="pcheque_1" style="display:none;">
                            <div class="form-group"><?= lang("cheque_no", "cheque_no_1"); ?>
                                <input name="cheque_no" value="<?= $payment->cheque_no; ?>" type="text" id="cheque_no_1"
                                       class="form-control cheque_no"/>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>

            <div class="form-group">
				<?= lang("document", "document"); ?>
				<input type="file" class="file" data-show-preview=" false" data-show-upload="false" name="document" id="document" value="<?= $payment->document ?>">
			</div>

            <div class="form-group" style="display:none;">
                <?= lang("note", "note"); ?>
                <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : $payment->note), 'class="form-control" id="note"'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_payment', lang('edit_payment'), 'class="btn btn-primary"'); ?>
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
        $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
        $(document).on('change', '.paid_by', function () {
            var p_val = $(this).val();
            localStorage.setItem('paid_by', p_val);
            if (p_val == 'cash') {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').show();
                $('#amount_1').focus();
            } else if (p_val == 'CC') {
                $('.pcheque_1').hide();
                $('.pcash_1').hide();
                $('.pcc_1').show();
                $('#pcc_no_1').focus();
            } else if (p_val == 'Cheque') {
                $('.pcc_1').hide();
                $('.pcash_1').hide();
                $('.pcheque_1').show();
                $('#cheque_no_1').focus();
            } else {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').hide();
            }
        });
        var p_val = '<?=$payment->paid_by?>';
        localStorage.setItem('paid_by', p_val);
        if (p_val == 'cash') {
            $('.pcheque_1').hide();
            $('.pcc_1').hide();
            $('.pcash_1').show();
            $('#amount_1').focus();
        } else if (p_val == 'CC') {
            $('.pcheque_1').hide();
            $('.pcash_1').hide();
            $('.pcc_1').show();
            $('#pcc_no_1').focus();
        } else if (p_val == 'Cheque') {
            $('.pcc_1').hide();
            $('.pcash_1').hide();
            $('.pcheque_1').show();
            $('#cheque_no_1').focus();
        } else {
            $('.pcheque_1').hide();
            $('.pcc_1').hide();
            $('.pcash_1').hide();
        }
        $('#pcc_no_1').change(function (e) {
            var pcc_no = $(this).val();
            localStorage.setItem('pcc_no_1', pcc_no);
            var CardType = null;
            var ccn1 = pcc_no.charAt(0);
            if (ccn1 == 4)
                CardType = 'Visa';
            else if (ccn1 == 5)
                CardType = 'MasterCard';
            else if (ccn1 == 3)
                CardType = 'Amex';
            else if (ccn1 == 6)
                CardType = 'Discover';
            else
                CardType = 'Visa';

            $('#pcc_type_1').select2("val", CardType);
        });
        $('#paid_by_1').select2("val", '<?=$payment->paid_by?>');
		
		
		$('#payment_status').on('change', function(){
			var status = $(this).val();
			if(status == "partial"){
				$('#payment').slideDown();
				$('#balance').slideDown();
			}else if(status == "received"){
				$('#payment').slideUp();
				$('#balance').slideUp();
			} else if (status == "deposit"){
				$('#payment').slideDown();
				$('#balance').slideUp();
				/*$('#pay').keyup(function(){
					var pay_deposits = $(this).val();
					var deposits = <?=$payment->deposit_amount?> ;
					if( pay_deposits > deposits){
						alert("You have only <?=$payment->deposit_amount?> on Deposit ! ! ! ");
					}
				});	*/
			} 
		});
		
		
    });
</script>
