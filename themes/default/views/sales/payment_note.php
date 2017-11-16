<style type="text/css">
    @media print{
        .modal-dialog{
            width: 95% !important;
        }
        .modal-content{
            border: none !important;
        }
    }
	.kh_m{
		font-family: "Khmer OS Muol";
	}
</style>
<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-body print">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
			<?php
			if($inv->mfi != '1') { ?>
				<?php if ($logo) { ?>
					<div class="text-center" style="margin-bottom:20px;">
					<img src="<?= base_url() . 'assets/uploads/logos/' . (($biller)? $biller->logo:''); ?>"
						 alt="<?= (($biller)? ($biller->company != '-' ? $biller->company : $biller->name) : ''); ?>">
					</div>
				<?php } ?>
				<div class="clearfix"></div>
				<div class="row padding10">
					<div class="col-xs-12 text-center">
						<?php
						if($biller) {
							echo $biller->address . " " . $biller->city . " " . $biller->postal_code . " " . $biller->state . " " . $biller->country;

							echo "<br/>";
							echo '<b>' . lang("tel") . ": " . $biller->phone . "</b><br />" . lang("email") . ": " . $biller->email;
						} else {
							echo '';
							echo "<br/>";
							echo '<b>' . lang("tel") . ": '' </b><br />" . lang("email") . ": ''";
						}
						?>
						<div class="clearfix"></div>
					</div>
			<?php } else { ?>
				<div class="clearfix"></div>
				<div class="row padding10">
					<div>
						<div style="float:left;width:25%;">
							<?php if ($Settings->logo2) {
								echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" alt="' . $Settings->site_name . '" style="margin-bottom:10px; width:120px; margin-left:10px;" />';
							} ?>
						</div>
						<div style="float:left;width:50%;">	
							<center><b>
								<span class="kh_m"> <?php echo $setting->site_name ?></span><br/>
								<span><?= lang("branch_name") ?> : <?= $this->session->branchName; ?></span><br/>
								<span class="kh_m"><u><?=lang("payment_invoice") ?></u></span><br/>
							</center></b>
						</div>
						<div style="float:left;width:25%;">
								
						</div>
					</div>
			<?php } ?>
				<!--
                <div class="col-xs-6">
                    <h2 class=""><?= $customer->company ? $customer->company : $customer->name; ?></h2>
                    <?= $customer->company ? "" : "Attn: " . $customer->name ?>
                    <?php
                    echo $customer->address . " " . $customer->city . " " . $customer->postal_code . " " . $customer->state . " " . $customer->country;
/*                    echo "<p>";
                    if ($customer->cf1 != "-" && $customer->cf1 != "") {
                        echo "<br>" . lang("ccf1") . ": " . $customer->cf1;
                    }
                    if ($customer->cf2 != "-" && $customer->cf2 != "") {
                        echo "<br>" . lang("ccf2") . ": " . $customer->cf2;
                    }
                    if ($customer->cf3 != "-" && $customer->cf3 != "") {
                        echo "<br>" . lang("ccf3") . ": " . $customer->cf3;
                    }
                    if ($customer->cf4 != "-" && $customer->cf4 != "") {
                        echo "<br>" . lang("ccf4") . ": " . $customer->cf4;
                    }
                    if ($customer->cf5 != "-" && $customer->cf5 != "") {
                        echo "<br>" . lang("ccf5") . ": " . $customer->cf5;
                    }
                    if ($customer->cf6 != "-" && $customer->cf6 != "") {
                        echo "<br>" . lang("ccf6") . ": " . $customer->cf6;
                    }
                    echo "</p>";
*/
					echo "<br/>";
                    echo lang("tel") . ": " . $customer->phone . "<br />" . lang("email") . ": " . $customer->email;
                    ?>

                </div>
				-->
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <p><b><?= lang("payment_reference"); ?></b>: <?= $payment->reference_no; ?></p>
					<p><b><?= lang("date_invoice"); ?></b>: <?= $this->erp->hrld($inv->date); ?></p>
					<p><b><?= lang("date_received"); ?></b>: <?= $this->erp->hrld($payment->date); ?></p>
                </div>
				<div class="col-sm-6 text-left">
					<div class="pull-right">
						<p><b><?= lang("contract_id"); ?></b>: <?= $inv->reference_no; ?></p>
						<!--<p><b><?= lang("co_name"); ?></b>: <?= $this->session->userdata('username'); ?></p>-->
						<p><b><?= lang("co_name"); ?></b>: <?= $payment->co_name; ?></p>
						<p><b><?= lang("customer"); ?></b>: <?= $payment->c_name; ?></p>
					</div>
                </div>
            </div>
            <div class="well">
				<table class="table receipt">
					<thead>
						<tr>
							<th><?= lang("no"); ?></th>
							<th><?= lang("description"); ?></th>
							<th><?= lang("qty"); ?></th>
							<th><?= lang("unit"); ?></th>
							<?php if ($inv->total_discount != 0 || isset($total_disc)?$total_disc:'' != '') {
								echo '<th>'.lang('discount').'</th>';
							} ?>
							<th style="padding-left:10px;padding-right:10px;"><?= lang("amount"); ?> </th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$m_us = 0;
						$total_quantity = 0;
						foreach($rows as $row){
							$free = lang('free');
							//$this->erp->print_arrays($row);
							echo '<tr class="item"><td class="text-left">#' . $no . "</td>";
							echo '<td class="text-left">' . $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '') . '</td>';
							echo '<td class="text-center">' . $this->erp->formatQuantity($row->quantity);
							
							echo '<td class="text-center">' .$this->erp->formatMoney($row->real_unit_price) .' '.$paid->name.'</td>';
							$colspan = 5;
							if ($inv->order_discount != 0 || $row->item_discount != 0) {
								echo '<td class="text-center">';
								echo '<span>' ;
									if(strpos($row->discount, '%') !== false){
										echo $row->discount;
									}else{
										echo $row->discount;
									}
									/*
										if(strpos($inv->order_discount_id, '%') !== false){
											echo $inv->order_discount_id;
										}else{
											echo $inv->order_discount_id . '%';
										}
										*/
									
								echo '</span> ';
								$colspan = 5;
								$total_col = 3;
								echo '</td>';
							}else{
								if(isset($total_disc)?$total_disc:'' != ''){
									echo '<td class="text-center"></td>';
									$colspan = 5;
									$total_col = 3;
								}else{
									$colspan = 4;
									$total_col = 4;
								}
							}
							echo '<td class="text-right">' . ($this->erp->formatMoney($row->subtotal) == 0 ? $free:' '. $this->erp->formatMoney($row->subtotal)) .' '.$paid->name.'</td>';
							$no++;
							$total_quantity += $row->quantity;
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<!--<th colspan="2"><?= lang("rate"); ?> : <?php echo $exchange_rate_kh_c? number_format($exchange_rate_kh_c->rate):0 ?> ៛ | <?= lang("qty"); ?>= (<?=$this->erp->formatQuantity($total_quantity)?>)</th>-->
							<th style="text-align:left""><u><?= lang("description"); ?></u></th>
							<th colspan="<?=$total_col?>" class="text-right"></th>
							<th class="text-right"></th>
							<!--<th class="text-right"><?= ''. $this->erp->formatMoney($inv->total + $inv->product_tax); ?> <?= $paid->name ?></th>-->
						</tr>
						<tr colspan="5">
							<table class="table table-striped">
							
								<tbody>
									<!--<tr>
										<td class="text-left" width="25%">
											<strong><?= lang("current_balance"); ?></strong>
										</td>
										<td><strong><?php echo '$ '. $this->erp->formatMoney($curr_balance); ?></strong></td>
									</tr>-->
									
									<?php
										if($paid->principle_amount != 0) { 
										echo '<tr>
											<td class="text-left" width="25%">
												<strong>'. lang("principle") .'</strong>
											</td>
											<td><strong>: '.$this->erp->formatMoney($this->erp->convertCurrency($paid->currency_code,$setting->default_currency,$paid->principle_amount)).' '.$paid->name.'</strong></td>
										</tr>';
										}
										if($paid->interest_amount != 0) { 
										echo '<tr>
											<td class="text-left" width="25%">
												<strong>'.lang("interest") .'</strong>
											</td>
											<td><strong>: '. $i_amt = $this->erp->formatMoney($this->erp->convertCurrency($paid->currency_code,$setting->default_currency,$paid->interest_amount)).' '.$paid->name.'</strong></td>
										</tr>';
										}
										if($paid->penalty_amount != 0) { 
											echo'<tr>
												<td class="text-left" width="25%">
													<strong>'.lang("penalty") .'</strong>
												</td>
												<td><strong>: '. $p_amt = $this->erp->formatMoney($this->erp->convertCurrency($paid->currency_code,$setting->default_currency,$paid->penalty_amount)) .' '.$paid->name.'</strong></td>
											</tr>';
										} 
										if($paid->other_paid != 0) { 
											echo'<tr>
												<td class="text-left" width="25%">
													<strong>'.lang("other_paid") .'</strong>
												</td>
												<td><strong>: '. $o_p = $this->erp->formatMoney($this->erp->convertCurrency($paid->currency_code,$setting->default_currency,$paid->other_paid)) .' '.$paid->name.'</strong></td>
											</tr>';
										} 
										
										foreach($service as $s) {
											if($s->amt !=0){
												echo '<tr>
														<td class="text-left" width="25%">
															<strong>'. $s->description .'</strong>
														</td>
														<td><strong>: '. $amt = $this->erp->formatMoney($this->erp->convertCurrency($paid->currency_code,$setting->default_currency,$s->amt)) .' '.$paid->name.'</strong></td>
													</tr>';
											}
										}	
										$amount = 0;
										$amount = $paid->principle_amount + $paid->interest_amount + $paid->penalty_amount + $paid->other_paid + $s->amt;
										//echo '<tr><td>'.$this->erp->formatMoney($amount).'</tr></td>';
										echo'<tr>
											<td class="text-left" width="25%">
												<strong>'. ($payment->type == 'returned' ? lang("payment_returned") : lang("payment_received")) .'</strong>
											</td>
											<td>
												<strong>: 
													'//.$this->erp->formatMoney($amount) .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ( '. $this->erp->convert_number_to_words(($this->erp->fraction($amount) > 0)? $this->erp->formatMoney($amount) : number_format($amount)) .' dolar )' .'
													.$this->erp->formatMoney($this->erp->convertCurrency($paid->currency_code,$setting->default_currency,$paid->amount)).' '.$paid->name.'
												</strong>
											</td>
										</tr>
										<tr>
											<td class="text-left" width="25%">
												<strong>'. lang("balance") .'</strong>
											</td>
											<td><strong>: '.$this->erp->formatMoney($this->erp->convertCurrency($paid->currency_code,$setting->default_currency,$paid->owed)) .' '.$paid->name.'</strong></td>
										</tr>
										<tr>
											<td><strong> '.lang("paid_by").'</strong></td>
											<td>
												<strong> : 
													'.lang($payment->paid_by);
														if ($payment->paid_by == 'gift_card' || $payment->paid_by == 'CC') {
															echo ' (' . $payment->cc_no . ')';
														} elseif ($payment->paid_by == 'Cheque') {
															echo ' (' . $payment->cheque_no . ')';
														}
													'
												</strong>
											</td>
										</tr>
										<tr>
											<td>
												<strong>'.lang("note") .'</strong>
											</td>
											<td><strong>: '.$payment->note .'</strong></td>
										</tr>';
									?>
								</tbody>
							
							</table>
						</tr>
					</tfoot>
				</table>
			<!--
                <table class="table table-borderless" style="margin-bottom:0;">
                    <tbody>
                    <tr>
						<tr>
							<td>
								<strong><?= lang("current_balance"); ?></strong>
							</td>
							<td><strong> : <?php echo $this->erp->formatMoney($curr_balance); ?></strong></td>
						</tr>
						
						<?php if($payment->extra_paid != 0) { ?>
						<tr>
							<td>
								<strong><?= lang("paid"); ?></strong>
							</td>
							<td><strong> : <?php echo $this->erp->formatMoney(($payment->amount-$payment->extra_paid)); ?></strong></td>
						</tr>
						<tr>
							<td>
								<strong><?= lang("extra_paid"); ?></strong>
							</td>
							<td><strong> : <?php echo $this->erp->formatMoney($payment->extra_paid); ?></strong></td>
						</tr>
						<?php } ?>
                        <td width="25%">
                            <strong><?= $payment->type == 'returned' ? lang("payment_returned") : lang("payment_received"); ?></strong>
                        </td>
                        <td>
							<strong> : 
								<?php echo '$ '. $this->erp->formatMoney($payment->amount) .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ( '. $this->erp->convert_number_to_words(($this->erp->fraction($payment->amount) > 0)? $this->erp->formatMoney($payment->amount) : number_format($payment->amount)) .' dolar )'; ?>
							</strong>
                        </td>
						<tr>
							<td>
								<strong><?= lang("balance"); ?></strong>
							</td>
							<td><strong> : <?php echo $this->erp->formatMoney($curr_balance - ($payment->amount - $payment->extra_paid)); ?></strong></td>
						</tr>
                    </tr>
                    <tr>
                        <td><strong><?= lang("paid_by"); ?></strong></td>
                        <td>
							<strong> : 
								<?php echo lang($payment->paid_by);
									if ($payment->paid_by == 'gift_card' || $payment->paid_by == 'CC') {
										echo ' (' . $payment->cc_no . ')';
									} elseif ($payment->paid_by == 'Cheque') {
										echo ' (' . $payment->cheque_no . ')';
									}
                                ?>
							</strong>
						</td>
                    </tr>

					 <tr>
                        <td>
                            <strong><?= lang("note"); ?></strong>
                        </td>
                        <td><strong><?php echo $payment->note; ?></strong></td>
                    </tr>
                    </tbody>
                </table>
				-->
            </div>
			<!--<p class="alert text-center"><?= $this->erp->decode_html($biller->invoice_footer); ?></p>-->
            <div style="clear: both;"></div>
            <div class="row">
				<div class="col-sm-4 pull-left">
                    <p>&nbsp;</p>

                    <p>&nbsp;</p>

                    <p>&nbsp;</p>

                    <p style="border-bottom: 1px solid #666;">&nbsp;</p>

                    <p><?= lang("customer_signature"); ?></p>
                </div>
				<div class="col-sm-4 pull-left">
                </div>
                <div class="col-sm-4 pull-left">
                    <p>&nbsp;</p>

                    <p>&nbsp;</p>

                    <p>&nbsp;</p>

                    <p style="border-bottom: 1px solid #666;">&nbsp;</p>

                    <p><?= lang("receiver_signature"); ?></p>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>