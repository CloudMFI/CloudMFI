<?php 
	//$this->erp->print_arrays($applicant);
?>

<?php
function row_status($x)
{
    if ($x == null) {
        return '';
    } elseif ($x == 'pending' || $x == 'quotation') {
        return '<div class="text-center"><span class="label label-warning">' . lang($x) . '</span></div>';
    } elseif ($x == 'completed' || $x == 'paid' || $x == 'sent' || $x == 'received' || $x == 'activated' || $x == 'approved_condition' || $x == 'pending_po') {
        return '<div class="text-center"><span class="label label-success">' . lang($x) . '</span></div>';
    } elseif ($x == 'partial' || $x == 'transferring') {
        return '<div class="text-center"><span class="label label-info">' . lang($x) . '</span></div>';
    } elseif ($x == 'due' || $x == 'cancelled' || $x == 'rejected') {
        return '<div class="text-center"><span class="label label-danger">' . lang($x) . '</span></div>';
    } elseif ($x == 'applicant') {
        return '<div class="text-center"><span class="label label-warning">' . lang($x) . '</span></div>';
    }elseif($x == 'draft'){ 
		return '<div class="text-center"><span class="label label-default">' . lang($x) . '</span></div>';
	}elseif($x == 'approved'){ 
		return '<div class="text-center"><span class="label label-info">' . lang($x) . '</span></div>';
	}else {
		return '<div class="text-center"><span class="label label-default">' . lang($x) . '</span></div>';
	}
}
?>
<?php if (($Owner || $Admin) && $chatData) {
    foreach ($chatData as $month_sale) {
        $months[] = date('M-Y', strtotime($month_sale->month));
        $msales[] = $month_sale->sales;
        $mtax1[] = $month_sale->tax1;
        $mtax2[] = $month_sale->tax2;
        $mpurchases[] = $month_sale->purchases;
        $mtax3[] = $month_sale->ptax;
    }
    ?>
    <!--<div class="box" style="margin-bottom: 15px;">
        <div class="box-header">
            <h2 class="blue"><i class="fa-fw fa fa-bar-chart-o"></i><?= lang('overview_chart'); ?></h2>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-md-12">
                    <p class="introtext"><?php echo lang('overview_chart_heading'); ?></p>

                    <div id="ov-chart" style="width:100%; height:450px;"></div>
                    <p class="text-center"><?= lang("chart_lable_toggle"); ?></p>
                </div>
            </div>
        </div>
    </div>-->
<?php } ?>
<?php if ($Owner || $Admin) { ?>
<!---<div class="row" style="margin-bottom: 15px;">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
                <h2 class="blue"><i class="fa fa-th"></i><span class="break"></span><?= lang('quick_links') ?></h2>
            </div>
            <div class="box-content">
                <!--
				<div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="bblue white quick-button small" href="<?= site_url('products') ?>">
                        <i class="fa fa-barcode"></i>

                        <p><?= lang('assets') ?></p>
                    </a>
                </div>
				-->
				<!--
				<div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="blightOrange white quick-button small" href="<?= site_url('quotes') ?>">
                        <i class="fa fa-file-archive-o"></i>

                        <p><?= lang('applicant') ?></p>
                    </a>
                </div>
                <div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="bdarkGreen white quick-button small" href="<?= site_url('down_payment/contract_list') ?>">
                        <i class="fa fa-files-o"></i>

                        <p><?= lang('contract') ?></p>
                    </a>
                </div>-->

                
<!--
                <div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="bred white quick-button small" href="<?= site_url('purchases') ?>">
                        <i class="fa fa-star"></i>

                        <p><?= lang('purchases') ?></p>
                    </a>
                </div>

                <div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="bpink white quick-button small" href="<?= site_url('transfers') ?>">
                        <i class="fa fa-star-o"></i>

                        <p><?= lang('transfers') ?></p>
                    </a>
                </div>

                <div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="bgrey white quick-button small" href="<?= site_url('customers') ?>">
                        <i class="fa fa-users"></i>

                        <p><?= lang('customers') ?></p>
                    </a>
                </div>
-->
                <!--<div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="bgrey white quick-button small" href="<?= site_url('billers') ?>">
                        <i class="fa fa-users"></i>

                        <p><?= lang('dealer') ?></p>
                    </a>
                </div>-->
<!--
                <div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="blightBlue white quick-button small" href="<?= site_url('notifications') ?>">
                        <i class="fa fa-comments"></i>

                        <p><?= lang('notifications') ?></p>
                        <span class="notification green">4</span>
                    </a>
                </div>

                <?php if ($Owner) { ?>
                    <div class="col-lg-1 col-md-2 col-xs-6">
                        <a class="bblue white quick-button small" href="<?= site_url('auth/users') ?>">
                            <i class="fa fa-group"></i>
                            <p><?= lang('users') ?></p>
                        </a>
                    </div>
                    <div class="col-lg-1 col-md-2 col-xs-6">
                        <a class="bblue white quick-button small" href="<?= site_url('system_settings') ?>">
                            <i class="fa fa-cogs"></i>

                            <p><?= lang('settings') ?></p>
                        </a>
                    </div>
                <?php } ?>
					<div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="borange white quick-button small" href="<?= site_url('quotes/fields_check') ?>">
                        <i class="fa fa-map"></i>

                        <p><?= lang('fields_check') ?></p>
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>-->
<?php } else { ?>
<!--<div class="row" style="margin-bottom: 15px;">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
                <h2 class="blue"><i class="fa fa-th"></i><span class="break"></span><?= lang('quick_links') ?></h2>
            </div>
            <div class="box-content">
            <?php if ($GP['products-index']) { ?>
                <div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="bblue white quick-button small" href="<?= site_url('products') ?>">
                        <i class="fa fa-barcode"></i>
                        <p><?= lang('products') ?></p>
                    </a>
                </div>
            <?php } if ($GP['sales-index']) { ?>
                <div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="bdarkGreen white quick-button small" href="<?= site_url('sales') ?>">
                        <i class="fa fa-heart"></i>
                        <p><?= lang('sales') ?></p>
                    </a>
                </div>



			<?php } if ($GP['installment_payment-index']) { ?>
                <div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="blightBlue white quick-button small" href="<?= site_url('Installment_payment') ?>">
                        <i class="fa fa-heart-o"></i>
                        <p><?= lang('installment') ?></p>
                    </a>
                </div>

			<?php } if ($GP['simulation-index']) { ?>
                <div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="bgrey white quick-button small" href="<?= site_url('simulation') ?>">
                        <i class="fa fa-heart-o"></i>
                        <p><?= lang('simulation') ?></p>
                    </a>
                </div>



            <?php } if ($GP['quotes-index']) { ?>
                <div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="blightOrange white quick-button small" href="<?= site_url('quotes') ?>">
                        <i class="fa fa-heart-o"></i>
                        <p><?= lang('quotes') ?></p>
                    </a>
                </div>
            <?php } if ($GP['purchases-index']) { ?>
                <div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="bred white quick-button small" href="<?= site_url('purchases') ?>">
                        <i class="fa fa-star"></i>
                        <p><?= lang('purchases') ?></p>
                    </a>
                </div>
            <?php } if ($GP['transfers-index']) { ?>
                <div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="bpink white quick-button small" href="<?= site_url('transfers') ?>">
                        <i class="fa fa-star-o"></i>
                        <p><?= lang('transfers') ?></p>
                    </a>
                </div>
            <?php } if ($GP['customers-index']) { ?>
                <div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="bgrey white quick-button small" href="<?= site_url('customers') ?>">
                        <i class="fa fa-users"></i>
                        <p><?= lang('customers') ?></p>
                    </a>
                </div>
				<?php } if ($GP['quotes-index']) { ?>
                <div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="borange white quick-button small" href="<?= site_url('quotes/fields_check') ?>">
                        <i class="fa fa-map"></i>
                        <p><?= lang('fields_check') ?></p>
                    </a>
                </div>
            <?php } if ($GP['suppliers-index']) { ?>
                <div class="col-lg-1 col-md-2 col-xs-6">
                    <a class="bgrey white quick-button small" href="<?= site_url('suppliers') ?>">
                        <i class="fa fa-users"></i>

                        <p><?= lang('suppliers') ?></p>
                    </a>
                </div>
            <?php } ?>
			
			
            <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>-->
<?php } ?>

<div class="row" style="margin-bottom: 15px;">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h2 class="blue"><i class="fa-fw fa fa-tasks"></i> <?= lang('latest_twenty') ?></h2>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-md-12">

                        <ul id="dbTab" class="nav nav-tabs">
							<?php  if ($Owner || $Admin || $GP['quotes-index']) { ?>
                            <li class=""><a href="#quotes"><?= lang('all_applicants') ?></a></li>
							<?php } if ($Owner || $Admin || $GP['quotes-index']) { ?>
                            <li class=""><a href="#quotes_group"><?= lang('group_loans_applicant') ?></a></li>
                            <?php }if ($Owner || $Admin || $GP['sales-index']) { ?>
                            <li class=""><a href="#sales"><?= lang('loan_approved') ?></a></li>
                            <?php }if ($Owner || $Admin || $GP['purchases-index']) { ?>
                            <!--<li class=""><a href="#purchases"><?= lang('purchases') ?></a></li>-->
                            <?php } if ($Owner || $Admin || $GP['transfers-index']) { ?>
                            <!--<li class=""><a href="#transfers"><?= lang('transfers') ?></a></li>-->
                            <?php } if ($Owner || $Admin || $GP['customers-index']) { ?>
                            <!--<li class=""><a href="#customers"><?= lang('customers') ?></a></li>-->
                            <?php } if ($Owner || $Admin || $GP['suppliers-index']) { ?>
                            <!--<li class=""><a href="#suppliers"><?= lang('dealer') ?></a></li>-->
                            <?php } ?>
                        </ul>

                        <div class="tab-content">
                       

                            <?php  if ($Owner || $Admin || $GP['quotes-index']) { ?>

                            <div id="quotes" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-12">
                                      <!-- CO quotes table -->
                                        <div class="table-responsive">
                                          
                                          <style>
                                          	#QUData {
                                          		overflow-x: scroll;
												overflow-y: scroll;
                                          		max-width: 100%;
												max-height: 375px;
                                          		min-height: 375px;
                                          		display: block;
                                          		white-space: nowrap;
                                          	}
                                            #QUData_wrapper .row{
                                              display: none;
                                            }
                                          </style>
										  <!--sethy-->
                                          <table id="QUData" class="table table-bordered table-hover table-striped">
                                              <thead>
                                              <tr class="active">
                                           
                                                  <th><?php echo $this->lang->line("#"); ?></th>
                                                  <th><?php echo $this->lang->line("reference_no"); ?></th>
												   <th><?php echo $this->lang->line("group_loans"); ?></th>
                                                  <th><?php echo $this->lang->line("customer_en"); ?></th>
                                                  <th><?php echo $this->lang->line("customer_kh"); ?></th>
                                                  <th><?php echo $this->lang->line("asset"); ?></th>
                                                  <!--<th><?php echo $this->lang->line("dealer"); ?></th>-->
                                                  <th><?php echo $this->lang->line("status"); ?></th>
                                                  <th><?php echo $this->lang->line("submit_date"); ?></th>
												  <th><?php echo $this->lang->line("approved_date"); ?></th>
												  <!--<th><?php echo $this->lang->line("acceptation_date"); ?></th>
												  <th><?php echo $this->lang->line("underwriter"); ?></th>-->
                                                  <th><?php echo $this->lang->line("c.o_name"); ?></th>
                                                  <th><?php echo $this->lang->line("branch"); ?></th>
                                                  <th><?php echo $this->lang->line("total"); ?></th>
												  <th><?php echo $this->lang->line("currency"); ?></th>
                                                  <th style="width:115px; text-align:center;display:;"><?php echo $this->lang->line("actions"); ?></th>
                                              </tr>
                                              </thead>
                                              <tbody>
											  <?php if (!empty($applicant)) {
                                                    $r = 1;
                                                    foreach ($applicant as $applicants) {
														$approve_link = anchor('quotes/approvedApplicant/'.$applicants->id, '<i class="fa fa-file-text-o"></i> ' . lang('approved_applicant'));	
														$view_details = anchor('quotes/approvedApplicant/'.$applicants->id, '<i class="fa fa-file-text-o"></i> ' . lang('view_details'));																
														$add_applicant = anchor('quotes/add_applicant/'.$applicants->id, '<i class="fa fa-file-text-o"></i> ' . lang('add_applicant'));
														$add_link = anchor('quotes/add_collateral/id', '<i class="fa fa-plus-circle"></i> ' . lang('add_collateral'),'data-toggle="modal" data-target="#myModal"');
														$edit_link = anchor('quotes/edit/'.$applicants->id, '<i class="fa fa-edit"></i> ' . lang('edit_applicant'));													  
														$pdf_link = anchor('quotes/pdf/id', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
														
														$delete_link = '<a href="#" class="po" title="<b>' . $this->lang->line('delete_applicant') . '</b>" data-content=\'<p>'
														. lang("r_u_sure") . '</p><a class="btn btn-danger po-delete" href="' . site_url('quotes/delete/'.$applicants->id) . '">'
														. lang("i_am_sure") . '</a>   <button class="btn po-close">' . lang("no") . '</button>\'><i class=\'fa fa-trash-o\'></i> '
														. lang("delete_quote") . '</a>';
														
														$action = '<div class="text-center"><div class="btn-group text-left">'
														. '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
														. lang('actions') . ' <span class="caret"></span></button>
																	<ul class="dropdown-menu pull-right" role="menu">';
																		if($applicants->status == 'draft'){
																			$action .= '<li style="display:none;" class="app_draft">' . $approve_link . '</li>';
																		}else{
																			if($applicants->status == 'applicant'){
																				$action .= '<li>' . $approve_link . '</li>';
																				if($applicants->glname != ''){
																					$action .= '<li>' . $add_applicant . '</li>';
																				}
																				$action .= '<li>' . $add_link . '</li>';
																				$action .= '<li>' . $edit_link . '</li>';
																			}else{
																				$action .= '<li>' . $view_details . '</li>';
																			}
																		}
																		
																		//$action .= '<li>' . $delete_link . '</li>';
																	$action .= '</ul>
																</div></div>';																
																
                                                        echo '
																<tr class="'.(($applicants->status == "draft")? "warning":"quote_link").'" id="'.$applicants->id .'" status="'. $applicants->status .'">
																<td>' . $r . '</td>
																<td>' . $applicants->reference_no . '</td>
																<td>' . $applicants->glname . '</td>
																<td>' . $applicants->customer_name_en . '</td>
																<td>' . $applicants->customer_name_kh . '</td>
																<td>' . $applicants->asset . '</td>														
																<td>' . row_status($applicants->status) . '</td>														
																<td>' . $applicants->date . '</td>
																<td>' . $applicants->approved_date . '</td>	
																<td>' . $applicants->co_name . '</td>
																<td>' . $applicants->branchName . '</td>
																<td>' . $this->erp->formatMoney($applicants->total) . '</td>
																<td>' . $applicants->crname . '</td>
																<td>' . $action . '</td>
                                                           </tr>';
                                                        $r++;
                                                    }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="14"
                                                            class="dataTables_empty"><?= lang('no_data_available') ?></td>
                                                    </tr>
                                                <?php } ?>
											</tbody>
                                          </table>
                                        </div>
                                    </div>
                                </div>
								<span><?= lang('total_applicant') ?> :<strong> <?= ($num_Id ? $num_Id->id:0) ?></strong></span>
                            </div>
							
							<?php } if ($Owner || $Admin || $GP['sales-index']) { ?>
							<!--
                            <div id="sales" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="sales-tbl" cellpadding="0" cellspacing="0" border="0"
                                                   class="table table-bordered table-hover table-striped"
                                                   style="margin-bottom: 0;">
                                                <thead>
                                                <tr>
                                                    <th style="width:30px !important;">#</th>
                                                    <th><?= $this->lang->line("date"); ?></th>
                                                    <th><?= $this->lang->line("reference_no"); ?></th>
                                                    <th><?= $this->lang->line("customer"); ?></th>
                                                    <th><?= $this->lang->line("status"); ?></th>
                                                    <th><?= $this->lang->line("total"); ?></th>
                                                    <th><?= $this->lang->line("payment_status"); ?></th>
                                                    <th><?= $this->lang->line("paid"); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if (!empty($sales)) {
                                                    $r = 1;
                                                    foreach ($sales as $order) {
                                                        echo '<tr id="' . $order->id . '" class="' . ($order->pos ? "receipt_link" : "invoice_link") . '"><td>' . $r . '</td>
                                                            <td>' . $this->erp->hrld($order->date) . '</td>
                                                            <td>' . $order->reference_no . '</td>
                                                            <td>' . $order->customer . '</td>
                                                            <td>' . row_status($order->sale_status) . '</td>
                                                            <td class="text-right">' . $this->erp->formatMoney($order->grand_total) . '</td>
                                                            <td>' . row_status($order->payment_status) . '</td>
                                                            <td class="text-right">' . $this->erp->formatMoney($order->paid) . '</td>
                                                        </tr>';
                                                        $r++;
                                                    }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="7"
                                                            class="dataTables_empty"><?= lang('no_data_available') ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
							-->
							<?php } if ($Owner || $Admin || $GP['sales-index']) { ?>

                            <div id="sales" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                             <table id="QUData" class="table table-bordered table-hover table-striped">
                                                <thead>
												<tr class="active">
													<th><?php echo $this->lang->line("#"); ?></th>
													<th><?php echo $this->lang->line("reference_no"); ?></th>
													<th><?php echo $this->lang->line("group_loans"); ?></th>
													<th><?php echo $this->lang->line("customer"); ?></th>
													<th><?php echo $this->lang->line("customer_kh"); ?></th>
													<!--<th><?php echo $this->lang->line("dealer"); ?></th>-->
													<th><?php echo $this->lang->line("created_by"); ?></th>
													<th><?php echo $this->lang->line("loan_type"); ?></th>
													<th><?php echo $this->lang->line("rate"); ?></th>
													<th><?php echo $this->lang->line("term"); ?></th>
													<th><?php echo $this->lang->line("pay_term"); ?></th>
													<th><?php echo $this->lang->line("total"); ?></th>
													<!--<th><?php echo $this->lang->line("down_%"); ?></th>-->
													<!--<th><?php echo $this->lang->line("advance_payment"); ?></th>-->
													<th><?php echo $this->lang->line("disburse"); ?></th>
													<!--<th><?php echo $this->lang->line("second_payment"); ?></th>-->
													<th><?php echo $this->lang->line("remaining"); ?></th>
													<th><?php echo $this->lang->line("currency"); ?></th>
													<th><?php echo $this->lang->line("status"); ?></th>
												</tr>
												</thead>
                                                <tbody>
                                                <?php if (!empty($sales)) {
                                                    $r = 1;
													if(is_array(isset($contract) ?$contract  : (''))){ /////' . ($order->pos ? "receipt_link" : "invoice_link") . '
                                                    foreach ($contract as $cont) {
                                                        echo '<tr id="' . $order->id . '" class="' . ($order->pos ? "//receipt_link" : "//invoice_link") . '"><td>' . $r . '</td>
                                                            <td>' . $cont->reference_no . '</td>
															<td>' . $cont->glname . '</td>
                                                            <td>' . $cont->customer_name . '</td>
                                                            <td>' . $cont->customer_name_other . '</td>                                                            
                                                            <td>' . $cont->username . '</td>
                                                            <td>' . $cont->product_name . '</td> 
                                                            <td>' . $cont->interest . '</td> 
                                                            <td>' . $cont->term . '</td> 
															<td>' . $cont->pay_term . '</td> 
                                                            <td>' . $this->erp->formatMoney($cont->total_amount). '</td> 
                                                            <td>' . $this->erp->formatMoney($cont->disburse). '</td>                                                            
                                                            <td>' . $this->erp->formatMoney($cont->remaining). '</td>
															 <td>' . $cont->crname . '</td> 
                                                            <td>' . row_status($cont->sale_status). '</td>
                                                            
                                                        </tr>';
                                                        $r++;
                                                    }}
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="15"
                                                            class="dataTables_empty"><?= lang('no_data_available') ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
												<!--
													4<td>' . $cont->biller. '</td>
													7<td>' . $cont->product_year. '</td>
                                                    8<td>' . $cont->name. '</td>
													10<td>' . $cont->percentage. '</td>
                                                    11<td>' . $this->erp->formatMoney($cont->advance_payment). '</td>
													12<td>' . $this->erp->formatMoney($cont->second_payment). '</td>
													
												-->
                                            </table>
                                        </div>
                                    </div>
                                </div>
								<span><?= lang('total_loan_approved') ?> :<strong> <?= ($sales_id ? $sales_id->id:0) ?></strong></span>
                            </div>
							
							<!----group_loan-->
							<?php } if ($Owner || $Admin || $GP['quotes_group-index']) { ?>
							<div id="quotes_group" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-12">
                                      <!-- CO quotes table -->
                                        <div class="table-responsive">
                                          
                                          <style>
                                          	#QUData {
                                          		overflow-x: scroll;
												overflow-y: scroll;
                                          		max-width: 100%;
												max-height: 375px;
                                          		min-height: 375px;
                                          		display: block;
                                          		white-space: nowrap;
                                          	}
                                            #QUData_wrapper .row{
                                              display: none;
                                            }
                                          </style>
                                          <table id="QUData" class="table table-bordered table-hover table-striped">
                                              <thead>
                                              <tr class="active">
                                           
                                                  <th><?php echo $this->lang->line("#"); ?></th>
                                                  <th><?php echo $this->lang->line("reference_no"); ?></th>
												  <th><?php echo $this->lang->line("group_loan"); ?></th>
                                                  <th><?php echo $this->lang->line("customer_en"); ?></th>
                                                  <th><?php echo $this->lang->line("customer_kh"); ?></th>
                                                  <th><?php echo $this->lang->line("asset"); ?></th>
                                                  <th><?php echo $this->lang->line("status"); ?></th>
                                                  <th><?php echo $this->lang->line("submit_date"); ?></th>
												  <th><?php echo $this->lang->line("approved_date"); ?></th>
                                                  <th><?php echo $this->lang->line("c.o_name"); ?></th>
                                                  <th><?php echo $this->lang->line("branch"); ?></th>
                                                  <th><?php echo $this->lang->line("total"); ?></th>
												  <th><?php echo $this->lang->line("currency"); ?></th>
                                                  <th style="width:115px; text-align:center;display:;"><?php echo $this->lang->line("actions"); ?></th>
                                              </tr>
                                              </thead>
                                              <tbody>
											  <?php if (!empty($applicant_group)) {
                                                    $r = 1;
                                                    foreach ($applicant_group as $applicant_groups) {
														$approve_link = anchor('quotes/approvedApplicant/'.$applicant_groups->id, '<i class="fa fa-file-text-o"></i>' . lang('approved_applicant'));
														$view_details = anchor('quotes/approvedApplicant/'.$applicant_groups->id, '<i class="fa fa-file-text-o"></i>' . lang('view_details'));		
														$add_applicant = anchor('quotes/add_applicant/'.$applicants->id, '<i class="fa fa-file-text-o"></i> ' . lang('add_applicant'));
														$add_link = anchor('quotes/add_collateral/id', '<i class="fa fa-plus-circle"></i> ' . lang('add_collateral'),'data-toggle="modal" data-target="#myModal"');
														$edit_link = anchor('quotes/edit/'.$applicant_groups->id, '<i class="fa fa-edit"></i> ' . lang('edit_applicant'));													  
														$pdf_link = anchor('quotes/pdf/id', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
														
														$delete_link = '<a href="#" class="po" title="<b>' . $this->lang->line('delete_applicant') . '</b>" data-content=\'<p>'
														. lang("r_u_sure") . '</p><a class="btn btn-danger po-delete" href="' . site_url('quotes/delete/'.$applicant_groups->id) . '">'
														. lang("i_am_sure") . '</a>   <button class="btn po-close">' . lang("no") . '</button>\'><i class=\'fa fa-trash-o\'></i> '
														. lang("delete_quote") . '</a>';
														$action = '<div class="text-center"><div class="btn-group text-left">'
														. '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
														. lang('actions') . ' <span class="caret"></span></button>
																	<ul class="dropdown-menu pull-right" role="menu">';
																		if($applicant_groups->status == 'draft'){
																			$action .= '<li style="display:none;" class="app_draft">' . $approve_link . '</li>';
																		}else{
																			if($applicant_groups->status == 'applicant'){
																				$action .= '<li>' . $approve_link . '</li>';
																				$action .= '<li>' . $add_applicant . '</li>';
																				$action .= '<li>' . $add_link . '</li>';
																				$action .= '<li>' . $edit_link . '</li>';
																			}else{
																				$action .= '<li>' . $view_details . '</li>';
																			}	
																		}
																		//$action .= '<li>' . $delete_link . '</li>';
																	$action .= '</ul>
																</div></div>';																
																
                                                        echo '<tr class="'.(($applicant_groups->status == "draft")? "warning":"quote_link").'" id="'.$applicant_groups->id .'" status="'. $applicant_groups->status .'">
														<td>' . $r . '</td>
														<td>' . $applicant_groups->reference_no . '</td>
														<td>' . $applicant_groups->group_name . '</td>
														<td>' . $applicant_groups->customer_name_en . '</td>
														<td>' . $applicant_groups->customer_name_kh . '</td>
														<td>' . $applicant_groups->asset . '</td>														
														<td>' . row_status($applicant_groups->status) . '</td>														
														<td>' . $applicant_groups->date . '</td>
														<td>' . $applicant_groups->approved_date . '</td>	
														<td>' . $applicant_groups->co_name . '</td>
														<td>' . $applicant_groups->branchName . '</td>
														<td>' . $this->erp->formatMoney($applicant_groups->grand_total) . '</td>
														<td>' . $applicant_groups->crname . '</td>
														<td>' . $action . '</td>
                                                           </tr>';
                                                        $r++;
                                                    }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="14"
                                                            class="dataTables_empty"><?= lang('no_data_available') ?></td>
                                                    </tr>
                                                <?php } ?>
											  
											</tbody>
                                          </table>
                                        </div>
                                    </div>
                                </div>
								<span><?= lang('total_group_laon') ?> :<strong> <?= ($loan_group_id ? $loan_group_id->id:0) ?></strong></span>
                            </div>
							<!------------------->
							<?php } if ($Owner || $Admin || $GP['purchases-index']) { ?>

                            <div id="purchases" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="purchases-tbl" cellpadding="0" cellspacing="0" border="0"
                                                   class="table table-bordered table-hover table-striped"
                                                   style="margin-bottom: 0;">
                                                <thead>
                                                <tr>
                                                    <th style="width:30px !important;">#</th>
                                                    <th><?= $this->lang->line("date"); ?></th>
                                                    <th><?= $this->lang->line("reference_no"); ?></th>
                                                    <th><?= $this->lang->line("supplier"); ?></th>
                                                    <th><?= $this->lang->line("status"); ?></th>
                                                    <th><?= $this->lang->line("amount"); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if (!empty($purchases)) {
                                                    $r = 1;
                                                    foreach ($purchases as $purchase) {
                                                        echo '<tr id="' . $purchase->id . '" class="purchase_link"><td>' . $r . '</td>
                                                    <td>' . $this->erp->hrld($purchase->date) . '</td>
                                                    <td>' . $purchase->reference_no . '</td>
                                                    <td>' . $purchase->supplier . '</td>
                                                    <td>' . row_status($purchase->status) . '</td>
                                                    <td class="text-right">' . $this->erp->formatMoney($purchase->grand_total) . '</td>
                                                </tr>';
                                                        $r++;
                                                    }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="5"
                                                            class="dataTables_empty"><?= lang('no_data_available') ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php } if ($Owner || $Admin || $GP['transfers-index']) { ?>

                            <div id="transfers" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="transfers-tbl" cellpadding="0" cellspacing="0" border="0"
                                                   class="table table-bordered table-hover table-striped"
                                                   style="margin-bottom: 0;">
                                                <thead>
                                                <tr>
                                                    <th style="width:30px !important;">#</th>
                                                    <th><?= $this->lang->line("date"); ?></th>
                                                    <th><?= $this->lang->line("reference_no"); ?></th>
                                                    <th><?= $this->lang->line("from"); ?></th>
                                                    <th><?= $this->lang->line("to"); ?></th>
                                                    <th><?= $this->lang->line("status"); ?></th>
                                                    <th><?= $this->lang->line("amount"); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if (!empty($transfers)) {
                                                    $r = 1;
                                                    foreach ($transfers as $transfer) {
                                                        echo '<tr id="' . $transfer->id . '" class="transfer_link"><td>' . $r . '</td>
                                                <td>' . $this->erp->hrld($transfer->date) . '</td>
                                                <td>' . $transfer->transfer_no . '</td>
                                                <td>' . $transfer->from_warehouse_name . '</td>
                                                <td>' . $transfer->to_warehouse_name . '</td>
                                                <td>' . row_status($transfer->status) . '</td>
                                                <td class="text-right">' . $this->erp->formatMoney($transfer->grand_total) . '</td>
                                            </tr>';
                                                        $r++;
                                                    }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="6"
                                                            class="dataTables_empty"><?= lang('no_data_available') ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
                            <?php } if ($Owner || $Admin || $GP['suppliers-index']) { ?>

                            <div id="suppliers" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                           <table id="QUData" class="table table-bordered table-hover table-striped">
                                                 <thead>
													<tr class="primary">
														
														<th><?= lang("#"); ?></th>
														<th><?= lang("company"); ?></th>
														<th><?= lang("name"); ?></th>
														<th><?= lang("vat_no"); ?></th>
														<th><?= lang("phone"); ?></th>
														<th><?= lang("email_address"); ?></th>
														<th><?= lang("province"); ?></th>
														<th><?= lang("city"); ?></th>
														<th><?= lang("bank_name"); ?></th>
														<th><?= lang("account_number"); ?></th>
														<th><?= lang("account_name"); ?></th>
													</tr>
													</thead>
                                                <tbody>
                                                <?php if (!empty($dealers)) {
                                                    $r = 1;
                                                    foreach ($five_dealer as $fdealer) {
                                                        echo '<tr id="' . $fdealer->id . '" class=""><td>' . $r . '</td>														
																	<td>' . $fdealer->company . '</td>
																	<td>' . $fdealer->name . '</td>
																	<td>' . $fdealer->vat_no . '</td>
																	<td>' . $fdealer->phone . '</td>
																	<td>' . $fdealer->email . '</td>
																	<td>' . $fdealer->province . '</td>
																	<td>' . $fdealer->city . '</td>
																	<td>' . $fdealer->bank_name . '</td>
																	<td>' . $fdealer->account_number . '</td>
																	<td>' . $fdealer->account_name . '</td>
																</tr>';
                                                        $r++;
                                                    }
                                                } else { ?>
                                                    <tr>
                                                        <td colspan="10"
                                                            class="dataTables_empty"><?= lang('no_data_available') ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<!--Start my task--->
<div class="row" style="margin-bottom: 15px;">
    <div class="col-lg-12">
        <div class="box">		
            <div class="box-content">				
				<div class="row">
						<div class="col-sm-12">
							<div class="col-sm-4">
								<div class="small-box padding1010 bmGreen">
									<h4 class="bold" style="color:white;"><?= lang('total_applicant') ?></h4>
									<i class="fa fa-pie-chart"></i>

									<h3 class="bold"><?= ($app_d? $app_d->app_num:0) ?></h3>

									<p class="bold">
										<?= lang('total_amount') ?>
										<?= ($app_d? $this->erp->formatMoney($app_d->app_amount):$this->erp->formatMoney(0)) ?>
										<!--<?= ($app_d? $this->erp->convertCurrency($setting->default_currency,$currency->currency_code,$app_d->app_amount):$this->erp->formatMoney(0)) ?>-->
										
									</p>
								</div>
							</div>	
							<div class="col-sm-4">
								<div class="small-box padding1010 bmGreen">
									<h4 class="bold" style="color:white;"><?= lang('total_rejected') ?></h4>
									<i class="fa fa-pie-chart"></i>

									<h3 class="bold"><?=$rejceted_d? $rejceted_d->reject_num:'' ?></h3>

									<p class="bold"><?= lang('loan_reject') ?>
										<?=$rejceted_d? $this->erp->formatMoney($rejceted_d->total_rejectd):$this->erp->formatMoney('') ?>
									</p>
								</div>
							</div>	
							<div class="col-sm-4">
								<div class="small-box padding1010 bmGreen">
									<h4 class="bold" style="color:white;"><?= lang('total_contract') ?></h4>
									<i class="fa fa-pie-chart"></i>

									<h3 class="bold"><?=$contract_d? $contract_d->sale_num:'' ?></h3>

									<p class="bold"><?= lang('total_loans') ?>
										<?=$contract_d? $this->erp->formatMoney($contract_d->loans_amt):$this->erp->formatMoney('') ?>
									</p>
								</div>
							</div>							
							<div class="col-sm-4">
								<div class="small-box padding1010 bred">
									<h4 class="bold"><?= lang('total_disburoment_amount') ?></h4>
									<i class="fa fa-money"></i>

									<h3 class="bold">
										<?=$disbursement? $this->erp->formatMoney($disbursement->disbursement_amt):$this->erp->formatMoney('') ?>
									</h3>

									<p><?= lang('total_collection') ?>
										<?=$payment? $this->erp->formatMoney($payment->total_collection):$this->erp->formatMoney('') ?>
									</p>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="small-box padding1010 bred" style="height:134px;">
									<h4 class="bold"><?= lang('total_expense') ?></h4>
									<i class="fa fa-money"></i>

									<h3 class="bold">
										<?=$expanse? $this->erp->formatMoney($expanse->total_expanse):$this->erp->formatMoney('') ?>
									</h3>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="small-box padding1010 bred" style="height:134px;">
									<h4 class="bold"><?= lang('total_income') ?></h4>
									<i class="fa fa-money"></i>

									<h3 class="bold">
										<?php echo number_format((-1)*$total_income-($total_cost+$total_expense),2);?>
									</h3>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="small-box padding1010 bred" style="height:134px;">
									<h4 class="bold"><?= lang('total_outstanding_amount') ?></h4>
									<i class="fa fa-money"></i>
									<h3 class="bold">
										<?=$grandtotal_paid? $this->erp->formatMoney($grandtotal_paid->outstanding_amt):$this->erp->formatMoney('') ?>
									</h3>
									
								</div>
							</div>
							
                        </div>
					</div>
				</div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<!--End my task-->
<script type="text/javascript">
    $(document).ready(function () {
        $('.order').click(function () {
            window.location.href = '<?=site_url()?>orders/view/' + $(this).attr('id') + '#comments';
        });
        $('.invoice').click(function () {
            window.location.href = '<?=site_url()?>orders/view/' + $(this).attr('id');
        });
        $('.quote').click(function () {
            window.location.href = '<?=site_url()?>quotes/view/' + $(this).attr('id');
        });
    });
</script>