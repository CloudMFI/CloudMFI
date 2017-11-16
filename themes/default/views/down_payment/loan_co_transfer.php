<?php //$this->erp->print_arrays($users); ?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('transfer'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("down_payment/updateLoanToCO", $attrib); ?>
        <div class="modal-body">
				<div class="col-sm-12">
					<div class="panel panel-primary">
						<div class="panel-heading"><?= lang('co_transfer') ?></div>
						<div class="panel-body" style="padding: 5px;">            
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-6">
										<div class="form-group company">
											<?= lang("from_co", "from_co"); ?>
											<?php
												$us[(isset($_POST['from_co']) ? $_POST['from_co'] : '')] = (isset($_POST['from_co']) ? $_POST['from_co'] : '');
												if(array($users)) {
													foreach ($users as $user) {
														$us[$user->id] = $user->first_name . " " . $user->last_name;
													}
												}
												echo form_dropdown('from_co',$us,$applicant->u_id , 'class="form-control from_co" id="from_co_'.$applicant->u_id .'" data-item="'.$applicant->u_id .'" placeholder="' . lang("select_co") . '" style="pointer-events:none;"');
											?>
										</div>
										<input type="hidden" name="loan_id" id="loan_id" class="loan_id" value="<?= $applicant->q_id ?>">
										<input type="hidden" name="from_branch_id" id="from_branch_id_<?= $applicant->u_id ?>" class="from_branch_id" value="<?= $applicant->b_id ?>" />
									</div>
									<div class="col-md-6">
										<div class="form-group person">
											<?= lang("to_co", "to_co"); ?>
											<?php
												$co_transfer[(isset($_POST['to_co']) ? $_POST['to_co'] : '')] = (isset($_POST['to_co']) ? $_POST['to_co'] : '');
												if(array($all_co)) {
													foreach($all_co as $co){
														$co_transfer[$co->id .'#'. $co->branch_id] = $co->first_name ." ". $co->last_name;
													}
												}
												echo form_dropdown('to_co',$co_transfer,'', 'class="form-control to_co" id="to_co_'.$co_transfer->branch_id .'" data-item="'.$co_transfer->branch_id .'" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("C.O") . '" required="required" style="width:100%;"');	
											?>
										</div>
										<input type ="hidden" name="to_branch_id" id="to_branch_id_<?= $co_transfer->branch_id ?>"  class="to_branch_id" value="<?= $co_transfer->branch_id; ?>">
									</div>
								</div>
							</div>
						</div>
					</div>		
				</div>		
        </div>
        <div class="modal-footer">
            <?php echo form_submit('save', lang('transfer'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>



<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#biller_logo').change(function (event) {
            var biller_logo = $(this).val();
            $('#logo-con').html('<img src="<?=base_url('assets/uploads/logos')?>/' + biller_logo + '" alt="">');
        });
    });
	/*----Amount----*/
	$('#amount').live('change', function(e) {
		var price = $(this).val().toLowerCase();
		var amount = 0;
		var new_amount = 0; 
		if(price.search('k') > 0) {
			amount = price.split('k');
			new_amount = parseFloat(amount[0] * 1000);
		}else if(price.search('m') > 0) {
			amount = price.split('m');
			new_amount = parseFloat(amount[0] * 1000000);
		}else {
			amt = price - 0;
			if(!Number(amt)) {
				new_amount = 0;
			}else {
				new_amount = price;
			}
		}
		$(this).val(new_amount);
	});
	$(document).ready(function () {
		$('.from_co').live('change', function() {
			var from_bco = $(this).val();
			var _id = $(this).attr('data-item');
			var branch_id = from.split('#');
			var b_co = branch_id[1];
			$('#from_branch_id_'+ _id).val(b_co);
			$('#from_branch_id_'+ _id).trigger('change');
		});
		$('.to_co').on( "change", function() {
			var to_bco = $(this).val();
			var _id = $(this).attr('data-item');
			var branch_id = to_bco.split('#');
			var b_co = branch_id[1];
			$('#to_branch_id_'+ _id).val(b_co);
			$('#to_branch_id_'+ _id).trigger('change');
		});
	});
	
</script>
<?= $modal_js ?>
