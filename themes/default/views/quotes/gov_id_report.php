<style>
.quote_linkE:hover{cursor:pointer;}
</style>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('government_id_report'); ?></h4>
        </div>
        <div class="modal-body">
			<div class="row">
			
				<div class="col-md-3">
					<p>Name</p>
				</div>	
				<div class="col-md-9">
				<p>: <?php echo $info->family_name.' '.$info->name;?></p>
				</div>
				
				<div class="col-md-3">
				<p>ឈ្មោះ</p>
				</div>
				<div class="col-md-9">
				<p>: <?php echo $info->family_name_other.' '.$info->name_other; ?></p>
				</div>
				
				<div class="col-md-3">
				<p>Tel</p>
				</div>
				<div class="col-md-9">
				<p>: <?php echo isset($info->phone1) ?$info->phone1  : (''); ?></p>
				</div>
				
				<div class="col-md-3">
				<p>Address</p>
				</div>
				<div class="col-md-9">
				<p>: <?php echo '#'.$info->house_no.' , St'.$info->street.' , '.$info->province_en.' , '.$info->district_en .' , '.$info->communce_en.' , '.$info->country_en; ?></p>
				</div>
				
				<div class="col-md-3">
				<p>Date Of Birth</p>
				</div>
				<div class="col-md-9">
				<p>: <?php echo $info->date_of_birth; ?></p>
				</div>
				<table class="table table-bordered table-hover table-striped dataTable">
					<thead>
                        <tr>
							<th>Reference No</th>
							<th>Asset</th>
							<th>Status</th>
							<th>Submit Date</th>
							<th>Gurantor</th>
							<th>Gurantor Phone</th>
							<th>Create By</th>
						</tr>
					</thead>
					<tbody>
					
					<?php
						if(is_array(isset($applicant) ?$applicant  : (''))){
							foreach($applicant as $at){
								echo '
								<tr class=quote_linkE id='.$at->quid .' >
								<td>'.$at->reference_no.'</td>
								<td>'.$at->product_name.'</td>
								<td>'.row_status($at->status).'</td>
								<td>'.$at->date.'</td>
								<td>'.$at->gfname.' '.$at->gname.'</td>
								<td>'.$at->gphone.'</td>
								<td>'.$at->creator.'</td>
								</tr>';
							}
						}
					?>	
					</tbody>
				</table>
			</div>
	
			<?php
				function row_status($x)
				{
					if ($x == null) {
						return '';
					} elseif ($x == 'pending' || $x == 'quotation') {
						return '<div class="text-center"><span class="label label-warning">' . lang($x) . '</span></div>';
					} elseif ($x == 'completed' || $x == 'paid' || $x == 'sent' || $x == 'received' || $x = 'approved') {
						return '<div class="text-center"><span class="label label-success">' . lang($x) . '</span></div>';
					} elseif ($x == 'partial' || $x == 'transferring') {
						return '<div class="text-center"><span class="label label-info">' . lang($x) . '</span></div>';
					} elseif ($x == 'due' || $x == 'cancelled' || $x == 'rejected') {
						return '<div class="text-center"><span class="label label-danger">' . lang($x) . '</span></div>';
					} else {
						return '<div class="text-center"><span class="label label-default">' . lang($x) . '</span></div>';
					}
				}
			?>
        </div>
        <div class="modal-footer">
           <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
        </div>
    </div>
</div>
<?= $modal_js ?>
