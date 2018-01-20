 
 <style type="text/css">
    
    @media print{ 
		#title {
			background-color:#ccc !important; 
		}
		
		#total {
			background-color:#ccc !important; 
		}		
	}
</style>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('list_group_approval'); ?></h4>
        </div>
        <div class="modal-body"> 
			<div class="box-content">
				<div class="row">
					<div class="col-lg-12">
						<div class="contain-wrapper" style="width: 100%;height: 100%;margin: 20px auto; padding:20px;">
							<div class="row">
								<div class="container">
									<div>
										<div style="padding:0 auto; font-family:Battambang">	
											<center><b>
												<span style="font-family:Zawgyi-One"></span><br/>
												<span style="font-size:20px;"> (Cash Approal Form)</span><br/>
											</center></b>
										</div>		
									</div>					
									<div class="row"> 
										<div class="col-sm-4">
											<center>
												<p style="font-size: 15px; margin-top: 10px !important;">Group No.&nbsp;&nbsp; <?=  $loan_groups->name; ?></p>
											</center>
										</div>
										<div class="col-sm-4">
										</div>
										<div class="col-sm-4">					
											<center>
												<p style="font-size: 15px; margin-top: 10px !important;">Date:&nbsp;&nbsp;................................<br></p>
											</center>
										</div> 
									</div>
									<div>
										<div class=" col-sm-12">
											<table class="table table-bordered table-hover"border="1">
												<thead>
												<tr>
													<th class="text-center" style="font-size:15px !important;">No.</th>
													<th class="text-center" style="font-size:15px !important;">Clients ID</th>
													<th class="text-center" style="font-size:15px !important;">Borrower Name</th>
													<th class="text-center" style="font-size:15px !important;">NRC No.</th>
													<th class="text-center" style="font-size:15px !important;">Amount</th> 
													<th class="text-center" style="font-size:15px !important;">Sign</th> 
													<th class="text-center" style="font-size:15px !important;">Finger Print</th>
												</tr>	 
												</thead>
											<tbody>
											
												<?php
													$i = 1;
													foreach($group_members as $group_member){
													$amount = $this->erp->convertCurrency($group_member->currency_code, $setting->default_currency,$group_member->total);
												?>
												<tr>
													<td style="text-align:center; vertical-align:middle;"><?= $i ?> </td>
													<td style="text-align:center; vertical-align:middle;"><?= $group_member->reference_no; ?> </td>
													<td style="text-align:center; vertical-align:middle;"><?= $group_member->cus_name; ?> </td>
													<td style="text-align:center; vertical-align:middle;"><?= $group_member->gov_id; ?> </td>
													<td style="text-align:center; vertical-align:middle;"><?= $this->erp->formatMoney($amount); ?> </td>
													<td style="text-align:center; vertical-align:middle;"> </td>
													<td style="text-align:center; vertical-align:middle;"> </td>
												</tr> 
												<?php
													$i++;
													}
												?>
													
													
												</tbody>
											</table>
										</div>
									</div><!--div col sm 6 -->
									<div class="row"> 
											<div class="col-sm-4">
												<center>
													<p style="font-size: 15px; margin-top: 10px !important;">  (Approal of)<br></p>
											<form style="font-size: 15px; margin-top: 10px !important;">		
													<input type="checkbox" name="vehicle" value="Operation Manager">(Operation Manager)
													<input type="checkbox" name="vehicle" value="Brach Manager">(Brach Manager)
											</form>		
												</center>
											</div>
											<div class="col-sm-4">
												
											<form style="font-size: 15px;text-align:center; margin-top: 10px !important;">
													<input type="checkbox" name="vehicle" value="Approal"> Approal&nbsp;&nbsp;&nbsp;
													<input type="checkbox" name="vehicle" value="Not pproal"> Not Approal<br>
											</form>
											</div>
											<div class="col-sm-4">
												<center>
													<p style="font-size: 15px;float:right; margin-top: 10px !important"> Sign & seal:&nbsp;&nbsp;.........................<br><br>Name:&nbsp;&nbsp;....................................</p>
												</center>
											</div> 
									</div>
									<div class="row"> 
											<div class="col-sm-4">
												<center>
													<p style="font-size: 15px; margin-top: 25px !important;"><b>Credit Officer</b><br><br><br>Sign & seal:&nbsp;&nbsp;................................<br><br>
													Name:&nbsp;&nbsp;..........................................</p>
												</center>
											</div>
											<div class="col-sm-4">
											</div>
											<div class="col-sm-4">
												<center>
													<p style="font-size: 15px; float:right;margin-top: 25px !important;"><b>Teller</b><br><br><br>Sign & seal:&nbsp;&nbsp;...................................<br><br>
													Name:&nbsp;&nbsp;.............................................</p>
												</center>
											</div> 
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> 
        </div>  
    </div>
</div>
<?= isset($modal_js) ?$modal_js  : ('') ?>
 