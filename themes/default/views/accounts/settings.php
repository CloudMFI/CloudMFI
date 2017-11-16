<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-cogs"></i><?= lang('account_settings'); ?></h2>
        <?php if(isset($pos->purchase_code) && ! empty($pos->purchase_code) && $pos->purchase_code != 'purchase_code') { ?>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="<?= site_url('pos/updates') ?>" class="toggle_down"><i class="icon fa fa-upload"></i><span class="padding-right-10"><?= lang('updates'); ?></span></a>
                </li>
            </ul>
        </div>
        <?php }?>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('update_info'); ?></p>

                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'account_setting');
                echo form_open("account/settings", $attrib);
                ?>

                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?= lang('account_config') ?></legend>
                    <?php
					foreach($default as $data){
					?>
					<div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang("default_biller", "biller"); ?>
							<!--
                            <?= form_input('biller', (isset($_POST['biller']) ? $_POST['biller'] : $data->biller_id), 'class="form-control tip" id="biller1" data-placeholder="' . lang("select") . ' ' . lang("biller") . '" class="form-control" style="width:100%;"'); ?>
							-->
							<?php
								$acc_section = array(""=>"");
								$biller_name = "";
								foreach($get_biller_name as $getbiller){
									$biller_name = $getbiller->company;
								}
								if($get_biller){
								foreach($get_biller as $biller){
									$acc_section[$biller->id] = $biller->company;
								}}
								echo form_dropdown('biller', $acc_section, '' ,'id="biller" class="form-control" data-placeholder="' . $biller_name . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->biller_id;?>" name="biller_id" class="form-control" style="width:100%;"/>
                        </div>
                    </div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_purchase","default_purchase"); ?>
							<?php 
								$acc_section = array(""=>"");
								$purchase = "";
								foreach($purchases as $buy){
									$purchase = $buy->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_purchase', $acc_section, '' ,'id="default_purchase" class="form-control" data-placeholder="' . $data->default_purchase . ' | ' . $purchase . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_purchase;?>" name="dpurchase" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_purchase_tax","default_purchase_tax"); ?>
							<?php
								$acc_section = array(""=>"");
								$ptax = "";
								foreach($purchase_tax as $purchasetax){
									$ptax = $purchasetax->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_purchase_tax', $acc_section, '' ,'id="default_purchase_tax" class="form-control" data-placeholder="' . $data->default_purchase_tax . ' | ' . $ptax . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_purchase_tax;?>" name="dpurchase_tax" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_purchase_discount","default_purchase_discount"); ?>
							<?php
								$acc_section = array(""=>"");
								$purchase_discount = "";
								foreach($purchasediscount as $buydiscount){
									$purchase_discount = $buydiscount->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_purchase_discount', $acc_section, '' ,'id="default_purchase_discount" class="form-control" data-placeholder="' . $data->default_purchase_discount . ' | ' . $purchase_discount. '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_purchase_discount;?>" name="dpurchase_discount" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_purchase_deposit","default_purchase_deposit"); ?>
							<?php
								$acc_section = array(""=>"");
								$get_purchase_deposit = "";
								foreach($purchased_eposit as $purchase_deposits){
									$get_purchase_deposit = $purchase_deposits->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_purchase_deposit', $acc_section, '' ,'id="default_purchase_deposit" class="form-control" data-placeholder="' . $data->default_purchase_deposit . ' | ' . $get_purchase_deposit . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_purchase_deposit;?>" name="dpurchase_deposit" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_principle","default_sale"); ?>
							<?php 
								$acc_section = array(""=>"");
								$salename = "";
								foreach($sale_name as $getsale){
									$salename = $getsale->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_sale', $acc_section, '' ,'id="default_sale" class="form-control" data-placeholder="' . $data->default_sale . ' | ' . $salename . '" style="width:100%;" ');
								//echo form_input('default_sale', (isset($_POST['default_sale']) ? $_POST['default_sale'] : $data->default_sale), 'id="default_sale" class="form-control" style="width:100%"');
							?>
							<input type="hidden" value="<?= $data->default_sale;?>" name="sales" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_interest_income","default_interest_income"); ?>
							<?php 
								$acc_section = array(""=>"");
								$interest = "";
								foreach($interest_income as $ii){
									$interest = $ii->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_interest_income', $acc_section, '' ,'id="default_interest_income" class="form-control" data-placeholder="' . $data->default_interest_income . ' | ' . $interest. '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_interest_income;?>" name="interest_income" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_penalty_income","default_penalty_income"); ?>
							<?php 
								$acc_section = array(""=>"");
								$penalty = "";
								foreach($penalty_income as $pi){
									$penalty = $pi->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_penalty_income', $acc_section, '' ,'id="default_penalty_income" class="form-control" data-placeholder="' . $data->default_penalty_income . ' | ' . $penalty . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_penalty_income;?>" name="penalty_income" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_cost","default_cost"); ?>
							<?php
								$acc_section = array(""=>"");
								$cost = "";
								foreach($getcost as $getcosts){
									$cost = $getcosts->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_cost', $acc_section, '' ,'id="default_cost" class="form-control" data-placeholder="' . $data->default_cost . ' | ' . $cost . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_cost;?>" name="dcost" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang("default_cash", "default_cash"); ?>
                            <?php
								$acc_section = array(""=>"");
								$cash = "";
								foreach($get_cashs as $get_cash){
									$cash = $get_cash->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_cash', $acc_section, '' ,'id="default_cash" class="form-control" data-placeholder="' . $data->default_cash . ' | ' . $cash . '" style="width:100%;" ');
                            ?>
							<input type="hidden" value="<?= $data->default_cash;?>" name="dcash" class="form-control" style="width:100%;"/>
                        </div>
                    </div>
					<div class="col-md-4 col-sm-4" style="display:none;">
						<div class="form-group">
							<?= lang("default_open_balance","default_open_balance"); ?>
							<!--
							<?php
                            echo form_input('default_open_balance', (isset($_POST['default_open_balance']) ? $_POST['default_open_balance'] : $$data->default_open_balance), ' id="defaut_open_balance" data-placeholder="' . $data->default_open_balance . '" class="form-control tip" style="width:100%;"');
                            ?>
							-->
							<?php
								$acc_section = array(""=>"");
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_open_balance', $acc_section, '' ,'id="default_open_balance" class="form-control" data-placeholder="' . $data->default_open_balance . ' | ' . $data->accountname . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_open_balance;?>" name="open_balance" class="form-control" style="width:100%;"/>
						</div>
					</div>
					
					<div class="col-md-4 col-sm-4" style="display:none;">
						<div class="form-group">
							<?= lang("default_sale_discount","default_sale_discount"); ?>
							<?php 
								$acc_section = array(""=>"");
								$salediscount = "";
								foreach($sale_discount as $getdiscount){
									$salediscount = $getdiscount->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_sale_discount', $acc_section, '' ,'id="default_sale_discount" class="form-control" data-placeholder="' . $data->default_sale_discount . ' | ' . $salediscount . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_sale_discount;?>" name="sale_discount" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4" style="display:none;">
						<div class="form-group">
							<?= lang("default_sale_tax","default_sale_tax"); ?>
							<?php 
								$acc_section = array(""=>"");
								$stax = "";
								foreach($sale_tax as $saletax){
									$stax = $saletax->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_sale_tax', $acc_section, '' ,'id="default_sale_tax" class="form-control" data-placeholder="' . $data->default_sale_tax . ' | ' . $stax . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_sale_tax;?>" name="dsale_tax" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_receivable","default_receivable"); ?>
							<?php 
								$acc_section = array(""=>"");
								$dreceivable = "";
								foreach($receivable as $receive){
									$dreceivable = $receive->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_receivable', $acc_section, '' ,'id="default_receivable" class="form-control" data-placeholder="' . $data->default_receivable . ' | ' . $dreceivable . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_receivable;?>" name="receivable" class="form-control" style="width:100%;"/>
						</div>
					</div>
					
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_payable","default_payable"); ?>
							<?php 
								$acc_section = array(""=>"");
								$pay = "";
								foreach($payable as $payables){
									$pay = $payables->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_payable', $acc_section, '' ,'id="default_payable" class="form-control" data-placeholder="' . $data->default_payable . ' | ' . $pay . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_payable;?>" name="dpayable" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4" style="display:none;">
						<div class="form-group">
							<?= lang("default_sale_freight","default_sale_freight"); ?>
							<?php 
								$acc_section = array(""=>"");
								$sale_freight = "";
								foreach($get_sale_freight as $sale_freights){
									$sale_freight = $sale_freights->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_sale_freight', $acc_section, '' ,'id="default_sale_freight" class="form-control" data-placeholder="' . $data->default_sale_freight . ' | ' . $sale_freight . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_sale_freight;?>" name="dsale_freight" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4" style="display:none;">
						<div class="form-group">
							<?= lang("default_purchase_freight","default_purchase_freight"); ?>
							<?php 
								$acc_section = array(""=>"");
								$purchase_freight = "";
								foreach($get_purchase_freight as $purchase_freights){
									$purchase_freight = $purchase_freights->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_purchase_freight', $acc_section, '' ,'id="default_purchase_freight" class="form-control" data-placeholder="' . $data->default_purchase_freight . ' | ' . $purchase_freight . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_purchase_freight;?>" name="dpurchase_freight" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_stock","default_stock"); ?>
							<?php
								$acc_section = array(""=>"");
								$stock = "";
								foreach($getstock as $getstocks){
									$stock = $getstocks->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_stock', $acc_section, '' ,'id="default_stock" class="form-control" data-placeholder="' . $data->default_stock . ' | ' . $stock . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_stock;?>" name="dstock" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4" style="display:none;">
						<div class="form-group">
							<?= lang("default_stock_adjust","default_stock_adjust"); ?>
							<?php
								$acc_section = array(""=>"");
								$adjust = "";
								foreach($stock_adjust as $stockadjust){
									$adjust = $stockadjust->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_stock_adjust', $acc_section, '' ,'id="default_stock_adjust" class="form-control" data-placeholder="' . $data->default_stock_adjust . ' | ' . $adjust . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_stock_adjust;?>" name="dstock_adjust" class="form-control" style="width:100%;"/>
						</div>
					</div>
					
					<div class="col-md-4 col-sm-4" style="display:none;">
                        <div class="form-group">
                            <?= lang("default_payroll", "default_payroll"); ?>
                            <?php
								$acc_section = array(""=>"");
								$payroll = "";
								foreach($getpayroll as $payrolls){
									$payroll = $payrolls->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_payroll', $acc_section, '' ,'id="default_payroll" class="form-control" data-placeholder="' . $data->default_payroll . ' | ' . $payroll . '" style="width:100%;" ');
                            ?>
							<input type="hidden" value="<?= $data->default_payroll;?>" name="dpayroll" class="form-control" style="width:100%;"/>
                        </div>
                    </div>
					
					<div class="col-md-4 col-sm-4" style="display:none;">
                        <div class="form-group">
                            <?= lang("default_credit_card", "default_credit_card"); ?>
                            <?php
								$acc_section = array(""=>"");
								$ccard = "";
								foreach($credit_card as $creditcard){
									$ccard = $creditcard->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_credit_card', $acc_section, '' ,'id="default_credit_card" class="form-control" data-placeholder="' . $data->default_credit_card . ' | ' . $ccard . '" style="width:100%;" ');
                            ?>
							<input type="hidden" value="<?= $data->default_credit_card;?>" name="dcredit_card" class="form-control" style="width:100%;"/>
                        </div>
                    </div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_money_transfer","default_money_transfer"); ?>
							<?php
								$acc_section = array(""=>"");
								$moneytransfer = "";
								foreach($money_transfer as $moneytransfers){
									$moneytransfer = $moneytransfers->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_money_transfer', $acc_section, '' ,'id="default_money_transfer" class="form-control" data-placeholder="' . $data->default_money_transfer . ' | ' .$moneytransfer . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_money_transfer;?>" name="dmoney_transfer" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_sale_deposit","default_sale_deposit"); ?>
							<?php
								$acc_section = array(""=>"");
								$get_sale_deposit = "";
								foreach($sale_deposit as $sale_deposits){
									$get_sale_deposit = $sale_deposits->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_sale_deposit', $acc_section, '' ,'id="default_sale_deposit" class="form-control" data-placeholder="' . $data->default_sale_deposit . ' | ' . $get_sale_deposit . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_sale_deposit;?>" name="dsale_deposit" class="form-control" style="width:100%;"/>
						</div>
					</div>
					
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_cheque","default_cheque"); ?>
							<?php
								$acc_section = array(""=>"");
								$getcheque = "";
								foreach($cheque as $cheques){
									$getcheque = $cheques->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_cheque', $acc_section, '' ,'id="default_cheque" class="form-control" data-placeholder="' . $data->default_cheque . ' | ' . $getcheque . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_cheque;?>" name="dcheque" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4" style="display:none;">
						<div class="form-group">
							<?= lang("default_loan","default_loan"); ?>
							<?php
								$acc_section = array(""=>"");
								$getloans = "";
								foreach($loan as $loans){
									$getloans = $loans->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_loan', $acc_section, '' ,'id="default_loan" class="form-control" data-placeholder="' . $data->default_loan . ' | ' . $getloans . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_loan;?>" name="dloan" class="form-control" style="width:100%;"/>
						</div>
					</div>
					
					<div class="col-md-4 col-sm-4" style="display:none;">
						<div class="form-group">
							<?= lang("default_retained_earnings","default_retained_earnings"); ?>
							<?php
								$acc_section = array(""=>"");
								$get_retained_earning = "";
								foreach($retained_earning as $retained_earnings){
									$get_retained_earning = $retained_earnings->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_retained_earnings', $acc_section, '' ,'id="default_retained_earnings" class="form-control" data-placeholder="' . $data->default_retained_earnings . ' | ' . $get_retained_earning . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_retained_earnings;?>" name="dretained_earning" class="form-control" style="width:100%;"/>
						</div>
					</div>
					
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_other_income","default_other_income"); ?>
							<?php
								$acc_section = array(""=>"");
								$get_other_income = "";
								foreach($other_income as $income){
									$get_other_income = $income->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_other_income', $acc_section, '' ,'id="default_other_income" class="form-control" data-placeholder="' . $data->default_other_income . ' | ' . $get_other_income . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_other_income;?>" name="other_income" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_capital","default_capital"); ?>
							<?php
								$acc_section = array(""=>"");
								$get_default_capital = "";
								foreach($default_capital as $capital){
									$get_default_capital = $capital->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_capital', $acc_section, '' ,'id="default_capital" class="form-control" data-placeholder="' . $data->default_capital . ' | ' . $get_default_capital . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_capital;?>" name="capital" class="form-control" style="width:100%;"/>
						</div>
					</div>	
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("accrued_interest","accrued_interest"); ?>
							<?php
								$acc_section = array(""=>"");
								$get_accrued_interest = "";
								foreach($accrued_interest as $c_interest){
									$get_accrued_interest = $c_interest->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('accrued_interest', $acc_section, '' ,'id="accrued_interest" class="form-control" data-placeholder="' . $data->accrued_interest . ' | ' . $get_accrued_interest . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->accrued_interest;?>" name="c_interest" class="form-control" style="width:100%;"/>
						</div>
					</div>
					
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang("default_transfer_money","default_transfer_money"); ?>
							<?php
								$acc_section = array(""=>"");
								$get_default_transfer_money = "";
								foreach($transfer_money as $t_money){
									$get_default_transfer_money = $t_money->accountname;
								}
								foreach($chart_accounts as $section){
									$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
								}
								echo form_dropdown('default_transfer_money', $acc_section, '' ,'id="default_transfer_money" class="form-control" data-placeholder="' . $data->default_transfer_money . ' | ' . $get_default_transfer_money . '" style="width:100%;" ');
							?>
							<input type="hidden" value="<?= $data->default_transfer_money;?>" name="transfer_money" class="form-control" style="width:100%;"/>
						</div>
					</div>
					<?php
					}
					?>
                </fieldset>
                <?php echo form_submit('update_settings', lang('update_settings'), 'class="btn btn-primary"'); ?>

                <?php echo form_close(); ?>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function (e) {
        $('#account_setting').bootstrapValidator({
            feedbackIcons: {
                valid: 'fa fa-check',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            }, excluded: [':disabled']
        });
        $('select.select').select2({minimumResultsForSearch: 6});
        fields = $('.form-control');
        $.each(fields, function () {
            var id = $(this).attr('id');
            var iname = $(this).attr('name');
            var iid = '#' + id;
            if (!!$(this).attr('data-bv-notempty') || !!$(this).attr('required')) {
                $("label[for='" + id + "']").append(' *');
                $(document).on('change', iid, function () {
                    $('#account_setting').bootstrapValidator('revalidateField', iname);
                });
            }
        });
        $('input[type="checkbox"],[type="radio"]').not('.skip').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        $('#customer1').val('<?= $account->default_customer; ?>').select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url+"customerszz/getCustomer/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
		
		$('#biller1').val('<?= $account->default_biller; ?>').select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url+"customerszz/getCustomer/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
		
		$('#defaut_open_balance').val('<?= $account->default_open_balance; ?>').select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url+"customerszz/getCustomer/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "customers/balance_suggest",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
        
    });
</script>
