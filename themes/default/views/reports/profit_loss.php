<!--
<script>$(document).ready(function () {
        CURI = '<?= site_url('reports/profit_loss'); ?>';
    });</script>
<style>@media print {
        .fa {
            color: #EEE;
            display: none;
        }

        .small-box {
            border: 1px solid #CCC;
        }
    }</style>
<?php
	$start_date=date('Y-m-d',strtotime($start));
	$rep_space_end=str_replace(' ','_',$end);
	$end_date=str_replace(':','-',$rep_space_end);
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-bars"></i><?= lang('profit_loss'); ?></h2>

        <div class="box-icon">
            <div class="form-group choose-date hidden-xs">
                <div class="controls">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text"
                               value="<?= ($start ? $this->erp->hrld($start) : '') . ' - ' . ($end ? $this->erp->hrld($end) : ''); ?>"
                               id="daterange" class="form-control">
                        <span class="input-group-addon"><i class="fa fa-chevron-down"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-icon">
            <ul class="btn-tasks">
					
					<li class="dropdown"><a href="#" id="pdf" class="tip" title="<?= lang('download_pdf') ?>"><i
								class="icon fa fa-file-pdf-o"></i></a></li>
					<li class="dropdown"><a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i
								class="icon fa fa-file-picture-o"></i></a></li>
					<li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i
                                    class="icon fa fa-building-o tip" data-placement="left"
                                    title="<?= lang("billers") ?>"></i></a>
                            <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu"
                                aria-labelledby="dLabel">
                                <li><a href="<?= site_url('reports/profit_loss') ?>"><i
                                            class="fa fa-building-o"></i> <?= lang('billers') ?></a></li>
                                <li class="divider"></li>
                                <?php
                                foreach ($billers as $biller) {
                                    echo '<li ' . ($biller_id && $biller_id == $biller->id ? 'class="active"' : '') . '><a href="' . site_url('reports/profit_loss/'.$start.'/'.$end.'/' . $biller->id) . '"><i class="fa fa-building"></i>' . $biller->company . '</a></li>';
                                }
                                ?>
                            </ul>
                        </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('view_pl_report'); ?></p>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <div class="small-box padding1010 borange">
                                <h4 class="bold">
								<?php
									
									echo anchor("reports/view_profit_lost_purchase/$start_date/$end_date",'<i class="fa fa-money"></i>'. lang('purchases'), 'data-toggle="modal" data-target="#myModal"');
								?>
								</h4>
                                <i class="fa fa-star"></i>
                                <h3 class="bold"><?= $this->erp->formatMoney($total_purchases->total_amount) ?></h3>

                                <p class="bold"><?= $total_purchases->total . ' ' . lang('purchases') ?> </p>

                                <p><?= $this->erp->formatMoney($total_purchases->total) . ' ' . lang('purchases') ?>
                                    & <?= $this->erp->formatMoney($total_purchases->paid) . ' ' . lang('paid') ?>
                                    & <?= $this->erp->formatMoney($total_purchases->tax) . ' ' . lang('tax') ?></p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="small-box padding1010 bdarkGreen">
                                <h4 class="bold">
								<?php
									
									echo anchor("reports/view_profit_lost_sale/$start_date/$end_date",lang('sales'),'data-toggle="modal" data-target="#myModal"');
								?>
								</h4>
                                <i class="fa fa-heart"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney($total_sales->total_amount) ?></h3>

                                <p class="bold"><?= $total_sales->total . ' ' . lang('sales') ?> </p>

                                <p><?= $this->erp->formatMoney($total_sales->total) . ' ' . lang('sales') ?>
                                    & <?= $this->erp->formatMoney($total_sales->paid) . ' ' . lang('paid') ?>
                                    & <?= $this->erp->formatMoney($total_sales->tax) . ' ' . lang('tax') ?> </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <div class="small-box padding1010 bdarkGreen">
                                <h4 class="bold">
								<?php
									
									echo anchor("reports/view_profit_payments_received/$start_date/$end_date",lang('payment_received'),'data-toggle="modal" data-target="#myModal"');
								?>
                                <i class="fa fa-usd"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney($total_received->total_amount) ?></h3>

                                <p class="bold"><?= $total_received->total . ' ' . lang('received') ?> </p>

                                <p><?= $this->erp->formatMoney($total_received_cash->total_amount) . ' ' . lang('cash') ?>
                                    , <?= $this->erp->formatMoney($total_received_cc->total_amount) . ' ' . lang('CC') ?>
                                    , <?= $this->erp->formatMoney($total_received_cheque->total_amount) . ' ' . lang('cheque') ?>
                                    , <?= $this->erp->formatMoney($total_received_ppp->total_amount) . ' ' . lang('paypal_pro') ?>
                                    , <?= $this->erp->formatMoney($total_received_stripe->total_amount) . ' ' . lang('stripe') ?> </p>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="small-box padding1010 bgrey">
                                <h4 class="bold">
								<?php
									
									echo anchor("reports/view_profit_payment_return/$start_date/$end_date",lang('payments_return'),'data-toggle="modal" data-target="#myModal"');
								?>
								</h4>
                                <i class="fa fa-usd"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney($total_returned->total_amount) ?></h3>

                                <p><?= $total_returned->total . ' ' . lang('returned') ?></p>

                                <p>&nbsp;</p>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="small-box padding1010 borange">
                                <h4 class="bold">
								<?php
									
									echo anchor("reports/view_profit_payment_sent/$start_date/$end_date",lang('payments_sent'),'data-toggle="modal" data-target="#myModal"');
								?>
								</h4>
                                <i class="fa fa-usd"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney($total_paid->total_amount) ?></h3>

                                <p><?= $total_paid->total . ' ' . lang('sent') ?></p>

                                <p>&nbsp;</p>
                            </div>
                        </div>
                        <div class="col-sm-2">
							<a href="#">
                            <div class="small-box padding1010 bpurple">
                                <h4 class="bold">
									<?php
									
									echo anchor("reports/view_expense/$start_date/$end_date",lang('expanses'),'data-toggle="modal" data-target="#myModal"');
								?>
								</h4>
                                <i class="fa fa-usd"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney($total_expenses->total_amount) ?></h3>

                                <p class="bold"><?= $total_expenses->total . ' ' . lang('expenses') ?></p>

                                <p>&nbsp;</p>
                            </div>
							</a>
                        </div>
                    </div>
                </div>
				<div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <div class="small-box padding1010 bmGreen">
                                <h4 class="bold"><?= lang('payments') ?></h4>
                                <i class="fa fa-pie-chart"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney($total_received->total_amount - $total_returned->total_amount - $total_paid->total_amount - $total_expenses->total_amount) ?></h3>

                                <p class="bold"><?= $this->erp->formatMoney($total_received->total_amount) . ' ' . lang('received') ?>
                                    - <?= $this->erp->formatMoney($total_returned->total_amount) . ' ' . lang('returned') ?>
                                    - <?= $this->erp->formatMoney($total_paid->total_amount) . ' ' . lang('sent') ?>
                                    -<?= $this->erp->formatMoney($total_expenses->total_amount) . ' ' . lang('expenses') ?></p>
                            </div>
                        </div>
						
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <div class="small-box padding1010 bred">
                                <h4 class="bold"><?= lang('profit_loss') ?></h4>
                                <i class="fa fa-money"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney($total_sales->total_amount - $total_purchases->total_amount) ?></h3>

                                <p><?= $this->erp->formatMoney($total_sales->total_amount) . ' ' . lang('sales') ?>
                                    - <?= $this->erp->formatMoney($total_purchases->total_amount) . ' ' . lang('purchases') ?></p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="small-box padding1010 bpink">
                                <h4 class="bold"><?= lang('profit_loss') ?></h4>
                                <i class="fa fa-money"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney($total_sales->total_amount - $total_purchases->total_amount - $total_sales->tax) ?></h3>

                                <p><?= $this->erp->formatMoney($total_sales->total_amount) . ' ' . lang('sales') ?>
                                    - <?= $this->erp->formatMoney($total_sales->tax) . ' ' . lang('tax') ?>
                                    - <?= $this->erp->formatMoney($total_purchases->total_amount) . ' ' . lang('purchases') ?> </p>
                            </div>
                        </div>
                        
                    </div>
                </div>
				<div class="row">
                    <div class="col-sm-12">
                        
                        <div class="col-sm-6">
                            <div class="small-box padding1010 bblue">
                                <h4 class="bold"><?= lang('net_profit_loss') ?></h4>
                                <i class="fa fa-money"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney(($total_sales->total_amount - $total_sales->tax) - ($total_purchases->total_amount - $total_purchases->tax)) ?></h3>

                                <p>(<?= $this->erp->formatMoney($total_sales->total_amount) . ' ' . lang('sales') ?>
                                    - <?= $this->erp->formatMoney($total_sales->tax) . ' ' . lang('tax') ?>) -
                                    (<?= $this->erp->formatMoney($total_purchases->total_amount) . ' ' . lang('purchases') ?>
                                    - <?= $this->erp->formatMoney($total_purchases->tax) . ' ' . lang('tax') ?>)</p>
                            </div>
                        </div>
						<div class="col-sm-6">
                            <div class="small-box padding1010 bpurple">
                                <h4 class="bold"><?= lang('net_profit_loss') ?></h4>
                                <i class="fa fa-usd"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney(($total_sales->total_amount - $total_sales->tax) - ($total_costs->cost)) ?></h3>

                                <p>(<?= $this->erp->formatMoney($total_sales->total_amount) . ' ' . lang('sales') ?>
                                    - <?= $this->erp->formatMoney($total_sales->tax) . ' ' . lang('tax') ?>) -
                                    (<?= $this->erp->formatMoney($total_costs->cost) . ' ' . lang('costs') ?>
                                    )</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/profit_loss_pdf')?>/" + encodeURIComponent('<?=$start?>') + "/" + encodeURIComponent('<?=$end?>');
            return false;
        });
        $('#image').click(function (event) {
            event.preventDefault();
            html2canvas($('.box'), {
                onrendered: function (canvas) {
                    var img = canvas.toDataURL()
                    window.open(img);
                }
            });
            return false;
        });	
	
    });
</script>    -->
                                                        <!-- Myat su moe edit start   -->
<link href="https://fonts.googleapis.com/css?family=Moul|Battambang" rel="stylesheet"> 
<style type="text/css">
    .a_four_page,.header_report{
        width:21cm;
        min-height:height:29.7cm;
        height:auto;
        margin:0 auto;
        font-size:13px;
        font-family:'Zawgyi-One' ,'Time New Roman';
    }
    .header_report{
        height:100px;
        //border:1px solid red;
    }
    .title{
        text-align: center;
        font-size:16px;
        font-family: 'Zawgyi-One', cursive;
    }
    #logo{
        width: 150px;
        opacity: 0.8;
    }
    .tableone thead{
        width: 100%;
        font-family: 'Zawgyi-One','Time New Roman';
        font-size: 12px;
        background:#92CDDC;
        font-weight:bold;

    }
    table tr td{
        border:1px solid black;
        
    }
    h4{
        text-align: center;
        line-height:20px !important;
    }
    td{
        padding:5px;
    }
    
</style>

<div class="body-overflow">
    <div class="a_four_page" >
        <div class="header_report">
            <div class="title">
                <span id="logo">
                    <?php if ($Settings->logo2) {echo '<img src="' . site_url() . 'assets/uploads/logos/' . $Settings->logo2 . '" />';} ?><br><br><br>
                </span>
           </div>
        </div>
          <h4> <b>အရွုံးအျမတ္စာရင္း<br> Profit and Loss</b></h4>
         <h4> ေငြပမာဏ (က်ပ္သန္းေပါင္း )<br>Amounts in millions of Kyats </h4>
       <br>
    </div>
</div>
         <p style="text-align: right;"> ရက္စဲြ (:dd-mmm-yyyy: )  ..…………………………………… <br>
              စာရင္းေပးပို ့သည့္ကာလ (month )………………………………<p>
        <table class="tableone">
          <thead>
            <tr>
                <td style="text-align:center;width: 3%;padding: 20px" rowspan="2">စဥ္<br>No</td>
                <td style="text-align:center;font-family: 'Zawgyi-One'; width:25%;" rowspan="2">အေၾကာင္းအရာ<br>Description</td>
                <td style="text-align:center;font-family: 'Zawgyi-One'; width:25%;" colspan="3">ယခုလ</td>
                <td width="18%" style="text-align:center;" rowspan="2">စတင္နွစ္မွ ယေန့ထိက်ပ္<br>Year to Date in Kyats</td>
            </tr>
            <tr>
                 <td width="18%" style="text-align:center;">က်ပ္<br>Kyats</td>
                  <td width="18%" style="text-align:center;">အျခားနိုင္ငံျခားေငြမ်ားကို က်ပ္ေျပာင္းလဲ<br/>Other foreign   currencies into Kyats
                </td>
                <td width="18%" style="text-align:center;">စုစုေပါင္းက်ပ္<br>Total kyats</td>
            </tr>
          </thead>
          <tbody>
            <tr>
                    <td>၁<br>1</td>
                    <td>
                       အတိုးဝင္ေငြ<br>Interest Income
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၁.၁<br>1.1</td>
                    <td>
                        အသင္းသားမ်ားသို ့ေခ်းေငြမွအတိုးရေငြ<br>Interest Income from Loan to Customers
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၁.၂<br>1.2</td>
                    <td>
                        ဘဏ္နွင့္ေငြေၾကးအဖဲြ ့အစည္းမ်ားတြင္အပ္နွံေသာေငြမွ အတိုးရေငြ   <br>Accounts with Banks and Financial Institutions
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
             <tr>
                    <td>၁.၃  <br>1.3</td>
                    <td>
                        စာခ်ဳပ္မ်ားႏွင့္ ရင္းႏွီးျမွဳပ္နွံမႈမ်ားမွ အတိုးရေငြ  <br>Securities and  Investments
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
             <tr>
                    <td>၁.၄<br>1.4</td>
                    <td>
                      အျခားကိစၥမ်ား  <br>Others
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၂<br>2</td>
                    <td>အတိုးကုန္က်ေငြမ်ား<br>Interest Expenses
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၂.၁<br>2.1</td>
                    <td>အသင္းသားမ်ား၏ အပ္ေငြေပၚ အတိုးေပးရေငြ<br></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၂.၂ <br>2.2</td>
                    <td>ဘဏ္မ်ားနွင့္အျခားေငြေၾကးအဖဲြ ့အစည္းမ်ားမွအပ္နွံ ထားေသာေငြမ်ားအေပၚ အတိုးေပးရေငြ <br>Interest on Deposists from Banks and Other 
Financial Institutions </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၂.၃ <br>2.3  </td>
                    <td>ေခ်းယူထားေသာေငြမ်ားအေပၚ အတိုးေပးရေငြ<br>Interest Expense on Borrowings</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                <td>၂.၄<br>2.4</td>
                    <td>အျခား<br>Others</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                    <td>၃ <br>3  </td>
                    <td>အသားတင္ အတိုးဝင္ေငြ (၃=၁-၂)<br>Net Interest Income (3=1-2)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၄<br>4</td>
                    <td>အတိုးမဟုတ္ေသာဝင္ေငြ(အသားတင္)<br>Non-interest Income ( net )</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                   <td>၄<br>4</td>
                    <td>အတိုးမဟုတ္ေသာဝင္ေငြ(အသားတင္)<br>Non-interest Income ( net )</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၄.၁<br>4.1</td>
                    <td>ေကာ္မရွင္နွင့္အျခားအခေၾကးဝင္ေငြ<br>Commission and Fees Income</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၄.၂<br>4.2</td>
                    <td>ေကာ္မရွင္နွင့္အျခားအခေၾကးဝင္ေငြ<br>Commission and Fees Income</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၅<br>5</td>
                    <td>နိုင္ငံျခားေငြရရွိမႈ/ဆံုးရွဳံးမႈ<br>Foreign Exchange Gain/Loss</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၆<br>6</td>
                    <td>အျခားဝင္ေငြ<br>Other Income</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၇<br>7</td>
                    <td>လုပ္ငန္းဝင္ေငြ(၃+၄+၅+၆)<br>Operating Income (3+4+5+6)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၈<br>8</td>
                    <td>ဝန္ထမ္းကုန္က်ေငြ<br>Staff Expenses</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၉<br>9</td>
                    <td>စီမံခန္ ့ခဲြမႈနွင့္ အေထြေထြကုန္က်ေငြ<br>Admin and General Expenses</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၁၀<br>10</td>
                    <td>တန္ဖိုးေလ်ွာ့က်ျခင္း<br>Depreciation</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၁၁<br>11</td>
                    <td>ေခ်းေငြဆံုးရႈံးမႈလ်ာထားေငြ<br>Loan Loss Provisions</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၁၂<br>12</td>
                    <td>လုပ္ငန္းအျမတ္ေငြ(၇+၈+၉+၁၀+၁၁)<br>Profit form Operations (7+8+9+10+11)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၁၃<br>13</td>
                    <td>ေထာက္ပံံံ့ဝင္ေငြ<br>Grant Income</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၁၄<br>14</td>
                    <td>ေထာက္ပံ့ဝင္ေငြအတြက္ထိန္းညွိျခင္းမ်ား<br>Adjustments for Subsidies</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၁၅<br>15</td>
                    <td>အခြန္မေဆာင္မီအျမတ္ေငြ(၁၂+၁၃+၁၄)<br>Profit Before Tax (12+13+14)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၁၆<br>16</td>
                    <td>အျမတ္ေပၚအခြန္<br>Tax on Profit</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
            <tr>
                    <td>၁၇<br>17</td>
                    <td>အခ်ိန္တစ္ခုအတြက္ အသားတင္အျမတ္ေငြ ( ၁၅-၁၆)<br>Net Profit for the period (15-16)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
            </tr>
          </tbody>
        </table>
        <table border="0 " style="width:100% ;margin:50px 0px 20px 0px;">
            <tr>
                 <td style="border:none; width:33%;">ျပဳစုသူ (Prepared by) <br><br>အမည္ (Name )…………………</td>
                 <td style="border:none; width:33%;">စစ္ေဆးသူ ( Checked by )<br><br>အမည္ (Name )…………………</td>
                <td style="border:none; width:33%;">အတည္ျပဳသူ (Approved by ) <br><br>အမည္ (Name )…………………</td>
            </tr>
        </table>