<style>
.error{
	color: #ef233c;
}
</style>
<script type="text/javascript">
	$("#reference").attr('disabled','disabled');
	$('#ref_st').on('ifChanged', function() {
	  if ($(this).is(':checked')) {
		$("#reference").prop('disabled', false);
		$("#reference").val("");
	  }else{
		$("#reference").prop('disabled', true);
		var temp = $("#temp_reference_no").val();
		$("#reference").val(temp);
		
	  }
	});
	
</script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_journal'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("account/save_journal", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
		<div class="row">
			<?php
			$description = '';
            if(isset($journals)){
                foreach($journals as $journal1){
                    if($journal1->description != ""){
                        $description = $journal1->description;
                    }
                }
            }
			?>
			<div class="col-md-12">
				<div class="col-md-4">
					<div class="form-group">
						<?= lang("date", "sldate"); ?>
						<input type="text" style="display:none;">
						<?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : date('d/m/Y h:i')), 'class="form-control input-tip datetime" id="sldate" required="required"'); ?>
					</div>
				</div>
				
				<div class="col-md-4">
					<div class="form-group">
						<?= lang('reference_no', 'reference_no'); ?>
						<div class="input-group">  
						<?php echo form_input('reference', $reference_no, 'class="form-control" id="reference"'); ?>
						<input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $reference_no ?>" />
						<div class="input-group-addon no-print">
								<input type="checkbox" name="ref_status" id="ref_st" value="1" style="margin-top:3px;">
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label class="control-label" for="biller"><?= lang("branch"); ?></label>
						<?php
						$bl[""] = "";
						if($billers){
						foreach ($billers as $biller) {
							$bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
						}}
						echo form_dropdown('biller_id', $bl, (isset($_POST['biller_id']) ? $_POST['biller_id'] : $Settings->default_biller), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("branch") . '"');
						?>
					</div>
				</div>
				
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label" for="type"><?= lang("type"); ?></label>
						<?php
						$type_l["0"] = "None";
						foreach ($type as $type_r) {
							$type_l[$type_r->id] = $type_r->name != '-' ? ucfirst($type_r->name) : $type_r->name;
						}
						$type_l['shareholder']       = 'Shareholder';
						echo form_dropdown('type', $type_l, (isset($_POST['biller_id']) ? $_POST['biller_id'] : ""), 'class="form-control" id="type" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
						?>
					</div>
				</div>
				
				 <div class="col-md-4">
					<div class="form-group">
                        <label class="control-label" for="name"><?= lang("name"); ?></label>
						<?php if ($Owner || $Admin) { ?>
						<div class="input-group">
						<?php } ?>
							<?php
							if ($Owner || $Admin ) { 
								echo form_input('name', '', 'class="form-control" id="name" style="width:250px"  placeholder="' . lang("select_name") . '"');
							?>
							<div id="none_" class="input-group-addon no-print" style="padding: 2px 5px;">
								<a href="#" id="clickMe" class="external">
									<i class="fa fa-2x fa-plus-circle" id="addIcon"></i>
								</a>
							</div>
						</div>
						<?php }else{
							echo form_input('name', '', 'class="form-control" id="name" style="width:250px" placeholder="' . lang("select_name") . '"');
						} ?>
					</div>
                 </div>
				 <script type="text/javascript">
					$(document).ready(function () {
						$('#type').change(function() {
							var type = $("#type").val();
							if (type ==3 || type==0) {
								$('#none_').hide();
							} else {
								$('#none_').show();
								$('#clickMe').click(function() {
									$(this).attr('href', "<?= site_url('auth/create_user') ?>");
								});
							} 
						});
					});
					$('#none_').hide();
				</script>
				<div class="col-md-3"></div>
			</div>
			<div class="col-md-12">
				<div class="col-md-11">
					<div class="form-group">
						<?= lang("description", "details") ?>
						<?= form_textarea('description', '', 'rows="5" class="form-control" id="details" required="required"'); ?>
					</div>
				</div>
				<div class="col-md-1"></div>
			</div>
			<div class="col-md-12">
				<div class="col-md-1">
					<div class="form-group">
						<button type="button" class="btn btn-primary" id="addDescription"><i class="fa fa-plus-circle"></i></button>
					</div>
				</div>
			</div>
			<div class="journalContainer">
				<div class="col-md-12 journal-list">
					<div class="col-md-4">
						<div class="form-group company">
							<?= lang("chart_account", "chart_account"); ?>
							<?php
							$acc_section = array(""=>"");
							foreach($sectionacc as $section){
								$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
							}
							echo form_dropdown('account_section[]', $acc_section, '', 'id="account_section" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("Account") . ' ' . $this->lang->line("Section") . '" required="required" style="width:100%;" ');
							?>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-group">
							<?= lang("debit", "debit"); ?>
							<?php echo form_input('debit[]', '', 'class="form-control debit1" id="debit"'); ?>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group">
							<?= lang("credit", "credit"); ?>
							<?php echo form_input('credit[]', '', 'class="form-control credit1" id="credit"'); ?>
						</div>
					</div>
				</div>
				
				
				<div class="col-md-12 journal-list">
					<div class="col-md-4">
						<div class="form-group company">
						<?php
						$acc_section = array(""=>"");
						foreach($sectionacc as $section){
							$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
						}
							echo form_dropdown('account_section[]', $acc_section, isset($journal->account_code)?$journal->account_code:'', 'id="account_section" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("Account") . ' ' . $this->lang->line("Section") . '" required="required" style="width:100%;" ');
						?>
						</div>
					</div>
				
					<div class="col-md-4">
						<div class="form-group">
							<?php echo form_input('debit[]', '', 'class="form-control debit2" id="debit"'); ?>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group">

							<?php echo form_input('credit[]', '', 'class="form-control credit2" id="credit"'); ?>
						</div>
					</div>
					<div class="col-md-1">
						
					</div>
				</div>
				
			</div>
				<div class="col-md-12 get-journal-list"></div>
				
				<div class="col-md-12" style="border-top:1px solid #CCC"></div>
				<div class="col-md-6">
					<div class="col-md-offset-9">
						<div class="form-group">
							<label id="calDebit"></label>
						</div>
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="col-md-offset-4">
						<div class="form-group">
							<label id="calCredit" style="margin-left:18px !important"></label>
						</div>
					</div>
				</div>
			</div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_journal', lang('add_journal'), 'class="btn btn-primary" id="checkSave"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>
<script type="text/javascript">
	
	$("#customer_invoice_no").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer_invoice') ?>").select2({
		placeholder: "<?= lang('select_customer_invoice') ?>", data: [
			{id: '', text: 'None'},
			<?php foreach($invoices as $invoice) { ?>
				{id: '<?= $invoice->id ?>', text: '<?= $invoice->text ?>'},
			<?php } ?>
		]
     });
	
	$("#name").select2("destroy").empty().attr("placeholder", "<?= lang('select_name') ?>").select2({
            placeholder: "<?= lang('select_cselect_name') ?>", data: [
                {id: '', text: '<?= lang('select_name') ?>'}
            ]
     });
	 
	$("#type").change(function()
	{
	 var v = $(this).val();
            $('#modal-loading').show();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url('account/getpeoplebytype') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
						
                        if (scdata != null) {
                            $("#name").select2("destroy").empty().attr("placeholder", "<?= lang('select_name') ?>").select2({
                                placeholder: "<?= lang('select_category_to_load') ?>",
                                data: scdata
                            });
                        }else{
							$("#name").select2("destroy").empty().attr("placeholder", "<?= lang('select_name') ?>").select2({
                                placeholder: "<?= lang('select_category_to_load') ?>",
                                data: 'not found'
                            });
						}
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                    }
                });
            } else {
                $("#name").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({
                    placeholder: "<?= lang('select_category_to_load') ?>",
                    data: [{id: '', text: '<?= lang('select_category_to_load') ?>'}]
                });
            }
       $('#modal-loading').hide();
	
	});

	$("#customer_invoice").change(function()
	{
	 var v = $(this).val();
            $('#modal-loading').show();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url('account/getCustomerInvoices') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
						
                        if (scdata != null) {
                            $("#customer_invoice_no").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer_invoice') ?>").select2({
                                placeholder: "<?= lang('select_customer_to_load') ?>",
                                data: scdata
                            });
                        }else{
							$("#customer_invoice_no").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer_invoice') ?>").select2({
                                placeholder: "<?= lang('select_customer_to_load') ?>",
                                data: 'not found'
                            });
						}
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                    }
                });
            } else {
                $("#customer_invoice_no").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({
                    placeholder: "<?= lang('select_customer_to_load') ?>",
                    data: [{id: '', text: '<?= lang('select_customer_to_load') ?>'}]
                });
            }
       $('#modal-loading').hide();
	
	});

	var MaxInputs       = 30;
	var InputsWrapper   = jQuery(".journalContainer");
	var AddButton       = jQuery("#addDescription");
	
	var InputCount = jQuery(".journal-list");
	var x = InputCount.length;
	
	var FieldCount=2;

	$(AddButton).click(function (e)
	{     
		if(x <= MaxInputs) 
		{ 
			FieldCount++; 
			var div = '<div class="col-md-12 journal-list divwrap'+FieldCount+'">';
			div += '	<div class="col-md-4">';
			div += '			<div class="form-group company">';
			div += '				<select class="form-control input-tip select" id="select" name="account_section[]" style="width:100%;" required="required">';
			div += '				<?php foreach($sectionacc as $section){ ?>';
			div += '					<option value="<?=$section->accountcode?>"><?=$section->accountcode . " | " . $section->accountname; ?></option>';
			div += '				<?php } ?>';
			div += '				</select>';
			div += '			</div>';
			div += '		</div>';
			
			div += '		<div class="col-md-4">';
			div += '			<div class="form-group">';
			div += '				<input type="text" name="debit[]" value="" class="form-control debit'+FieldCount+'" id="debit"> ';
			div += '			</div>';
			div += '		</div>';
					
			div += '		<div class="col-md-3">';
			div += '			<div class="form-group">';
			div += '				<input type="text" name="credit[]" value="" class="form-control credit'+FieldCount+'" id="credit"> ';
			div += '			</div>';
			div += '		</div>';
			div += '		<div class="col-md-1">';
			div += '			<label><label><button type="button" data="'+FieldCount+'" class="removefile btn btn-danger" style="position:relative;top:-10px;">&times;</button></label></label>';
			div += '		</div>';
			div += '	</div>';

			$(InputsWrapper).append(div);
			$("select").select2();
			x++;
		}
		return false;
	});
	
	function AutoDebit(){
		var v_debit = 0;
		var i = 1;
		$('[name^=debit]').each(function(i, item) {
			v_debit +=  $(item).val()-0 || 0;
		});
		$("#calDebit").text(v_debit);
	}
	function AutoCredit(){
		var v_credit = 0;
		var j = 1;
		$('[name^=credit]').each(function(i, item) {
			v_credit +=  $(item).val()-0 || 0;
		});
		$("#calCredit").text(v_credit);
	}
	
	$(document).ready(function () {
		$('.removefile').live('click', function(){
			var divId 	= $(this).attr('data');
			if( FieldCount == 2 ) {
				bootbox.alert('Journal must be at least two transaction!');
				return false;
			}else{
				$('.divwrap'+divId+'').remove();
			}
			AutoDebit();
			AutoCredit();

			if($("#calDebit").text() != $("#calCredit").text()){
				$("#calDebit").addClass('error');
				$("#calCredit").addClass('error');
			}else{
				$("#calDebit").removeClass('error');
				$("#calCredit").removeClass('error');
			}
		});
		
		$('input[name="debit[]"], input[name="credit[]"]').live('change keyup paste',function(){	
			AutoDebit();
			AutoCredit();

			if($("#calDebit").text() != $("#calCredit").text()){
				$("#calDebit").addClass('error');
				$("#calCredit").addClass('error');
			}else{
				$("#calDebit").removeClass('error');
				$("#calCredit").removeClass('error');
			}
		});
		
		$("#checkSave").click(function(){
			
			var help = true;
			$('[name^=account_section]').each(function(i, item) {
				if(!$(item).val() || $(item).val() == '') {
					help = false;
				}
			});
			if(!help) {
				alert('Chart Account is required!');
				return false;
			}

			if($("#calDebit").text() != $("#calCredit").text()){
				alert('Your Debit Credit is difference ! \nPlease check your amount');
				return false;
			}
			
			if($("#calDebit").text() <= 0 && $("#calCredit").text() <= 0){
				alert('Your Debit Credit is difference ! \nPlease check your amount');
				return false;
			}
			
			if($("#biller option:selected").val() <= 0){
				alert('Project is required');
				return false;
			}
		});
        
        $(".datetime1").datetimepicker({
            format: site.dateFormats.js_ldate,
            fontAwesome: true,
			language: 'erp',
			weekStart: 1, 
			todayBtn: 1, 
			autoclose: 1, 
			todayHighlight: 1,
			forceParse: 0,
			minView: 2
        }).datetimepicker('update', new Date());
		
		function chart_account(){
			$('#account_section').bind("change", function(){
				$(".sub_textbox").show();
				$(".sub_combobox").hide();
				var v = $(this).val();
				$('#modal-loading').show();
				if (v) {
					$.ajax({
						type: "get",
						async: false,
						url: "<?= site_url('account/getSubAccount') ?>/" + v,
						dataType: "json",
						success: function (scdata) {
							if (scdata != null) {
								$("#sub_account").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
									placeholder: "<?= lang('select_category_to_load') ?>",
									data: scdata
								});
							}
						},
						error: function () {
							bootbox.alert('<?= lang('ajax_error') ?>');
							$('#modal-loading').hide();
						}
					});
				}
				$('#modal-loading').hide();
			});		
		}

		$('#biller').change(function(){
			var id = $(this).val();
			$.ajax({
				url: '<?= base_url() ?>account/getReferenceByBranch/jr/'+id,
				dataType: 'json',
				success: function(data){
					$("#reference").val(data);
					$("#temp_reference_no").val(data);
				}
			});
		});
		chart_account();
	});
	/*----Set M&K----*/
	$('#debit,#credit').live('change', function(e) {
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
	$('#calDebit').trigger('change');
	$('#calCredit').trigger('change');
</script>
