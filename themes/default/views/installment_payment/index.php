<?php
	$v = "";
	/* if($this->input->post('name')){
	  $v .= "&product=".$this->input->post('product');
	  } */
	if ($this->input->post('reference_no')) {
		$v .= "&reference_no=" . $this->input->post('reference_no');
	}
	if ($this->input->post('applicant')) {
		$v .= "&applicant=" . $this->input->post('applicant');
	}
	if ($this->input->post('biller')) {
		$v .= "&biller=" . $this->input->post('biller');
	}
	if ($this->input->post('warehouse')) {
		$v .= "&warehouse=" . $this->input->post('warehouse');
	}
	if ($this->input->post('user')) {
		$v .= "&user=" . $this->input->post('user');
	}
	if ($this->input->post('branch')) {
		$v .= "&branch=" . $this->input->post('branch');
	}
	if ($this->input->post('gr_loan')) {
		$v .= "&gr_loan=" . $this->input->post('gr_loan');
	}
	if ($this->input->post('serial')) {
		$v .= "&serial=" . $this->input->post('serial');
	}
	if ($this->input->post('start_date')) {
		$v .= "&start_date=" . $this->input->post('start_date');
	}
	if ($this->input->post('end_date')) {
		$v .= "&end_date=" . $this->input->post('end_date');
	}
	if ($this->input->post('product_id')) {
		$v .= "&product_id=" . $this->input->post('product_id');
	}
	if(isset($date)){
		$v .= "&d=" . $date;
	}

?>
<style>
	#QUData {
		overflow-x: scroll;
		max-width: 100%;
		min-height: 300px;
		display: block;
		white-space: nowrap;
		cursor: pointer;
	}
	.hide_column{
		display:none;
	}
	
	#QUData2 {
		overflow-x: scroll;
		max-width: 100%;
		min-height: 300px;
		display: block;
		white-space: nowrap;
		cursor: pointer;
	}
	.hide_column{
		display:none;
	}
	
	#QUData3 {
		overflow-x: scroll;
		max-width: 100%;
		min-height: 300px;
		display: block;
		white-space: nowrap;
		cursor: pointer;
	}
	.hide_column{
		display:none;
	}
</style>
<script>
	function left_side(x){
		return '<div class="text-left">'+x+'</div>';
	}
    $(document).ready(function () {
        var oTable = $('#QUData').dataTable({
            "aaSorting": [[0, "desc"]],
            //"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
			"aLengthMenu": [[10, 50, 100, 250, 500], [10,50, 100, 250, 500]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('Installment_payment/getSales'. ($warehouse_id ? '/' . $warehouse_id : '')).'/?v=1'.$v?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                //var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
                nRow.className = "installment_link";
				$(nRow).attr("mfi",aData[13]);
                return nRow;
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, null,null, null, null, null, {"mRender": fld}, {"mRender": fld}, null, null, {"mRender": currencyFormat}, {"mRender": currencyFormat},null,
				{"bVisible": false}, {"bSortable": false}]
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('group_loans');?>]", filter_type: "text", data: []},
			{column_number: 3, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('customer_kh');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('phone');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('installment_date');?>]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[<?=lang('due_date');?>]", filter_type: "text", data: []},
            {column_number: 8, filter_default_label: "[<?=lang('branch');?>]", filter_type: "text", data: []},
            {column_number: 9, filter_default_label: "[<?=lang('co_name');?>]", filter_type: "text", data: []},
            {column_number: 10, filter_default_label: "[<?=lang('installment_amount');?>]", filter_type: "text", data: []},
            //{column_number: 11, filter_default_label: "[<?=lang('services_fee');?>]", filter_type: "text", data: []},
			{column_number: 11, filter_default_label: "[<?=lang('currency');?>]", filter_type: "text", data: []},
            {column_number: 12, filter_default_label: "[<?=lang('remaining');?>]", filter_type: "text", data: []},
            {column_number: 13, filter_default_label: "[<?=lang('mfi');?>]", filter_type: "text", data: []},
        ], "footer");
        if (localStorage.getItem('remove_slls')) {
            if (localStorage.getItem('slitems')) {
                localStorage.removeItem('slitems');
            }
            if (localStorage.getItem('sldiscount')) {
                localStorage.removeItem('sldiscount');
            }
            if (localStorage.getItem('sltax2')) {
                localStorage.removeItem('sltax2');
            }
            if (localStorage.getItem('slref')) {
                localStorage.removeItem('slref');
            }
            if (localStorage.getItem('slshipping')) {
                localStorage.removeItem('slshipping');
            }
            if (localStorage.getItem('slwarehouse')) {
                localStorage.removeItem('slwarehouse');
            }
            if (localStorage.getItem('slnote')) {
                localStorage.removeItem('slnote');
            }
            if (localStorage.getItem('slinnote')) {
                localStorage.removeItem('slinnote');
            }
            if (localStorage.getItem('slcustomer')) {
                localStorage.removeItem('slcustomer');
            }
            if (localStorage.getItem('slbiller')) {
                localStorage.removeItem('slbiller');
            }
            if (localStorage.getItem('slcurrency')) {
                localStorage.removeItem('slcurrency');
            }
            if (localStorage.getItem('sldate')) {
                localStorage.removeItem('sldate');
            }
            if (localStorage.getItem('slsale_status')) {
                localStorage.removeItem('slsale_status');
            }
            if (localStorage.getItem('slpayment_status')) {
                localStorage.removeItem('slpayment_status');
            }
            if (localStorage.getItem('paid_by')) {
                localStorage.removeItem('paid_by');
            }
            if (localStorage.getItem('amount_1')) {
                localStorage.removeItem('amount_1');
            }
            if (localStorage.getItem('paid_by_1')) {
                localStorage.removeItem('paid_by_1');
            }
            if (localStorage.getItem('pcc_holder_1')) {
                localStorage.removeItem('pcc_holder_1');
            }
            if (localStorage.getItem('pcc_type_1')) {
                localStorage.removeItem('pcc_type_1');
            }
            if (localStorage.getItem('pcc_month_1')) {
                localStorage.removeItem('pcc_month_1');
            }
            if (localStorage.getItem('pcc_year_1')) {
                localStorage.removeItem('pcc_year_1');
            }
            if (localStorage.getItem('pcc_no_1')) {
                localStorage.removeItem('pcc_no_1');
            }
            if (localStorage.getItem('cheque_no_1')) {
                localStorage.removeItem('cheque_no_1');
            }
            if (localStorage.getItem('slpayment_term')) {
                localStorage.removeItem('slpayment_term');
            }
            localStorage.removeItem('remove_slls');
        }

        <?php if ($this->session->userdata('remove_slls')) {?>
        if (localStorage.getItem('slitems')) {
            localStorage.removeItem('slitems');
        }
        if (localStorage.getItem('sldiscount')) {
            localStorage.removeItem('sldiscount');
        }
        if (localStorage.getItem('sltax2')) {
            localStorage.removeItem('sltax2');
        }
        if (localStorage.getItem('slref')) {
            localStorage.removeItem('slref');
        }
        if (localStorage.getItem('slshipping')) {
            localStorage.removeItem('slshipping');
        }
        if (localStorage.getItem('slwarehouse')) {
            localStorage.removeItem('slwarehouse');
        }
        if (localStorage.getItem('slnote')) {
            localStorage.removeItem('slnote');
        }
        if (localStorage.getItem('slinnote')) {
            localStorage.removeItem('slinnote');
        }
        if (localStorage.getItem('slcustomer')) {
            localStorage.removeItem('slcustomer');
        }
        if (localStorage.getItem('slbiller')) {
            localStorage.removeItem('slbiller');
        }
        if (localStorage.getItem('slcurrency')) {
            localStorage.removeItem('slcurrency');
        }
        if (localStorage.getItem('sldate')) {
            localStorage.removeItem('sldate');
        }
        if (localStorage.getItem('slsale_status')) {
            localStorage.removeItem('slsale_status');
        }
        if (localStorage.getItem('slpayment_status')) {
            localStorage.removeItem('slpayment_status');
        }
        if (localStorage.getItem('paid_by')) {
            localStorage.removeItem('paid_by');
        }
        if (localStorage.getItem('amount_1')) {
            localStorage.removeItem('amount_1');
        }
        if (localStorage.getItem('paid_by_1')) {
            localStorage.removeItem('paid_by_1');
        }
        if (localStorage.getItem('pcc_holder_1')) {
            localStorage.removeItem('pcc_holder_1');
        }
        if (localStorage.getItem('pcc_type_1')) {
            localStorage.removeItem('pcc_type_1');
        }
        if (localStorage.getItem('pcc_month_1')) {
            localStorage.removeItem('pcc_month_1');
        }
        if (localStorage.getItem('pcc_year_1')) {
            localStorage.removeItem('pcc_year_1');
        }
        if (localStorage.getItem('pcc_no_1')) {
            localStorage.removeItem('pcc_no_1');
        }
        if (localStorage.getItem('cheque_no_1')) {
            localStorage.removeItem('cheque_no_1');
        }
        if (localStorage.getItem('slpayment_term')) {
            localStorage.removeItem('slpayment_term');
        }
        <?php $this->erp->unset_data('remove_slls');}
        ?>

        $(document).on('click', '.sledit', function (e) {
            if (localStorage.getItem('slitems')) {
                e.preventDefault();
                var href = $(this).attr('href');
                bootbox.confirm("<?=lang('you_will_loss_sale_data')?>", function (result) {
                    if (result) {
                        window.location.href = href;
                    }
                });
            }
        });
		
		
		
    });

</script>
<!---------------------------------------------------------------------------------------------->
<script>
	function left_side(x){
		return '<div class="text-left">'+x+'</div>';
	}
    $(document).ready(function () {
        var oTable = $('#QUData2').dataTable({
            "aaSorting": [[0, "desc"]],
            "aLengthMenu": [[10, 50, 100, 250, 500], [10,50, 100, 250, 500]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('Installment_payment/getLateSales'. ($warehouse_id ? '/' . $warehouse_id : '')).'/?v=1'.$v?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                //var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
                nRow.className = "installment_link";
				$(nRow).attr("mfi",aData[14]);
                return nRow;
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, null,null, null, null, null, {"mRender": fld}, {"mRender": fld}, null, null, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat},null,
				{"bVisible": false}, {"bSortable": false}]
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('group_loans');?>]", filter_type: "text", data: []},
			{column_number: 3, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('customer_kh');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('phone');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('installment_date');?>]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[<?=lang('due_date');?>]", filter_type: "text", data: []},
            {column_number: 8, filter_default_label: "[<?=lang('penalty_days');?>]", filter_type: "text", data: []},
            {column_number: 9, filter_default_label: "[<?=lang('over_due_days');?>]", filter_type: "text", data: []},
            {column_number: 10, filter_default_label: "[<?=lang('installment_amount');?>]", filter_type: "text", data: []},
            //{column_number: 11, filter_default_label: "[<?=lang('services_fee');?>]", filter_type: "text", data: []},
            {column_number: 11, filter_default_label: "[<?=lang('penalty_amount');?>]", filter_type: "text", data: []},
			{column_number: 12, filter_default_label: "[<?=lang('currency');?>]", filter_type: "text", data: []},
            {column_number: 13, filter_default_label: "[<?=lang('remaining');?>]", filter_type: "text", data: []},
            {column_number: 14, filter_default_label: "[<?=lang('mfi');?>]", filter_type: "text", data: []},
        ], "footer");
        if (localStorage.getItem('remove_slls')) {
            if (localStorage.getItem('slitems')) {
                localStorage.removeItem('slitems');
            }
            if (localStorage.getItem('sldiscount')) {
                localStorage.removeItem('sldiscount');
            }
            if (localStorage.getItem('sltax2')) {
                localStorage.removeItem('sltax2');
            }
            if (localStorage.getItem('slref')) {
                localStorage.removeItem('slref');
            }
            if (localStorage.getItem('slshipping')) {
                localStorage.removeItem('slshipping');
            }
            if (localStorage.getItem('slwarehouse')) {
                localStorage.removeItem('slwarehouse');
            }
            if (localStorage.getItem('slnote')) {
                localStorage.removeItem('slnote');
            }
            if (localStorage.getItem('slinnote')) {
                localStorage.removeItem('slinnote');
            }
            if (localStorage.getItem('slcustomer')) {
                localStorage.removeItem('slcustomer');
            }
            if (localStorage.getItem('slbiller')) {
                localStorage.removeItem('slbiller');
            }
            if (localStorage.getItem('slcurrency')) {
                localStorage.removeItem('slcurrency');
            }
            if (localStorage.getItem('sldate')) {
                localStorage.removeItem('sldate');
            }
            if (localStorage.getItem('slsale_status')) {
                localStorage.removeItem('slsale_status');
            }
            if (localStorage.getItem('slpayment_status')) {
                localStorage.removeItem('slpayment_status');
            }
            if (localStorage.getItem('paid_by')) {
                localStorage.removeItem('paid_by');
            }
            if (localStorage.getItem('amount_1')) {
                localStorage.removeItem('amount_1');
            }
            if (localStorage.getItem('paid_by_1')) {
                localStorage.removeItem('paid_by_1');
            }
            if (localStorage.getItem('pcc_holder_1')) {
                localStorage.removeItem('pcc_holder_1');
            }
            if (localStorage.getItem('pcc_type_1')) {
                localStorage.removeItem('pcc_type_1');
            }
            if (localStorage.getItem('pcc_month_1')) {
                localStorage.removeItem('pcc_month_1');
            }
            if (localStorage.getItem('pcc_year_1')) {
                localStorage.removeItem('pcc_year_1');
            }
            if (localStorage.getItem('pcc_no_1')) {
                localStorage.removeItem('pcc_no_1');
            }
            if (localStorage.getItem('cheque_no_1')) {
                localStorage.removeItem('cheque_no_1');
            }
            if (localStorage.getItem('slpayment_term')) {
                localStorage.removeItem('slpayment_term');
            }
            localStorage.removeItem('remove_slls');
        }

        <?php if ($this->session->userdata('remove_slls')) {?>
        if (localStorage.getItem('slitems')) {
            localStorage.removeItem('slitems');
        }
        if (localStorage.getItem('sldiscount')) {
            localStorage.removeItem('sldiscount');
        }
        if (localStorage.getItem('sltax2')) {
            localStorage.removeItem('sltax2');
        }
        if (localStorage.getItem('slref')) {
            localStorage.removeItem('slref');
        }
        if (localStorage.getItem('slshipping')) {
            localStorage.removeItem('slshipping');
        }
        if (localStorage.getItem('slwarehouse')) {
            localStorage.removeItem('slwarehouse');
        }
        if (localStorage.getItem('slnote')) {
            localStorage.removeItem('slnote');
        }
        if (localStorage.getItem('slinnote')) {
            localStorage.removeItem('slinnote');
        }
        if (localStorage.getItem('slcustomer')) {
            localStorage.removeItem('slcustomer');
        }
        if (localStorage.getItem('slbiller')) {
            localStorage.removeItem('slbiller');
        }
        if (localStorage.getItem('slcurrency')) {
            localStorage.removeItem('slcurrency');
        }
        if (localStorage.getItem('sldate')) {
            localStorage.removeItem('sldate');
        }
        if (localStorage.getItem('slsale_status')) {
            localStorage.removeItem('slsale_status');
        }
        if (localStorage.getItem('slpayment_status')) {
            localStorage.removeItem('slpayment_status');
        }
        if (localStorage.getItem('paid_by')) {
            localStorage.removeItem('paid_by');
        }
        if (localStorage.getItem('amount_1')) {
            localStorage.removeItem('amount_1');
        }
        if (localStorage.getItem('paid_by_1')) {
            localStorage.removeItem('paid_by_1');
        }
        if (localStorage.getItem('pcc_holder_1')) {
            localStorage.removeItem('pcc_holder_1');
        }
        if (localStorage.getItem('pcc_type_1')) {
            localStorage.removeItem('pcc_type_1');
        }
        if (localStorage.getItem('pcc_month_1')) {
            localStorage.removeItem('pcc_month_1');
        }
        if (localStorage.getItem('pcc_year_1')) {
            localStorage.removeItem('pcc_year_1');
        }
        if (localStorage.getItem('pcc_no_1')) {
            localStorage.removeItem('pcc_no_1');
        }
        if (localStorage.getItem('cheque_no_1')) {
            localStorage.removeItem('cheque_no_1');
        }
        if (localStorage.getItem('slpayment_term')) {
            localStorage.removeItem('slpayment_term');
        }
        <?php $this->erp->unset_data('remove_slls');}
        ?>

        $(document).on('click', '.sledit', function (e) {
            if (localStorage.getItem('slitems')) {
                e.preventDefault();
                var href = $(this).attr('href');
                bootbox.confirm("<?=lang('you_will_loss_sale_data')?>", function (result) {
                    if (result) {
                        window.location.href = href;
                    }
                });
            }
        });
		
		
		
    });

</script>


<!---------------------------------------------------------------------------------------------->
<script>
	function left_side(x){
		return '<div class="text-left">'+x+'</div>';
	}
    $(document).ready(function () {
        var oTable = $('#QUData3').dataTable({
            "aaSorting": [[0, "desc"]],
            "aLengthMenu": [[10, 50, 100, 250, 500], [10,50, 100, 250, 500]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('Installment_payment/getAllSales'. ($warehouse_id ? '/' . $warehouse_id : '')).'/?v=1'.$v?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                //var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
                nRow.className = "installment_link";
				$(nRow).attr("mfi",aData[14]);
                return nRow;
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, null,null, null, null, null, {"mRender": fld}, {"mRender": fld}, null, null, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat},null,
				{"bVisible": false}, {"bSortable": false}]
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('group_loans');?>]", filter_type: "text", data: []},
			{column_number: 3, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('customer_kh');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('phone');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('installment_date');?>]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[<?=lang('due_date');?>]", filter_type: "text", data: []},
            {column_number: 8, filter_default_label: "[<?=lang('penalty_days');?>]", filter_type: "text", data: []},
            {column_number: 9, filter_default_label: "[<?=lang('over_due_days');?>]", filter_type: "text", data: []},
            {column_number: 10, filter_default_label: "[<?=lang('installment_amount');?>]", filter_type: "text", data: []},
            //{column_number: 11, filter_default_label: "[<?=lang('services_fee');?>]", filter_type: "text", data: []},
            {column_number: 11, filter_default_label: "[<?=lang('penalty_amount');?>]", filter_type: "text", data: []},
			{column_number: 12, filter_default_label: "[<?=lang('currency');?>]", filter_type: "text", data: []},
            {column_number: 13, filter_default_label: "[<?=lang('remaining');?>]", filter_type: "text", data: []},
            {column_number: 14, filter_default_label: "[<?=lang('mfi');?>]", filter_type: "text", data: []},
        ], "footer");
        if (localStorage.getItem('remove_slls')) {
            if (localStorage.getItem('slitems')) {
                localStorage.removeItem('slitems');
            }
            if (localStorage.getItem('sldiscount')) {
                localStorage.removeItem('sldiscount');
            }
            if (localStorage.getItem('sltax2')) {
                localStorage.removeItem('sltax2');
            }
            if (localStorage.getItem('slref')) {
                localStorage.removeItem('slref');
            }
            if (localStorage.getItem('slshipping')) {
                localStorage.removeItem('slshipping');
            }
            if (localStorage.getItem('slwarehouse')) {
                localStorage.removeItem('slwarehouse');
            }
            if (localStorage.getItem('slnote')) {
                localStorage.removeItem('slnote');
            }
            if (localStorage.getItem('slinnote')) {
                localStorage.removeItem('slinnote');
            }
            if (localStorage.getItem('slcustomer')) {
                localStorage.removeItem('slcustomer');
            }
            if (localStorage.getItem('slbiller')) {
                localStorage.removeItem('slbiller');
            }
            if (localStorage.getItem('slcurrency')) {
                localStorage.removeItem('slcurrency');
            }
            if (localStorage.getItem('sldate')) {
                localStorage.removeItem('sldate');
            }
            if (localStorage.getItem('slsale_status')) {
                localStorage.removeItem('slsale_status');
            }
            if (localStorage.getItem('slpayment_status')) {
                localStorage.removeItem('slpayment_status');
            }
            if (localStorage.getItem('paid_by')) {
                localStorage.removeItem('paid_by');
            }
            if (localStorage.getItem('amount_1')) {
                localStorage.removeItem('amount_1');
            }
            if (localStorage.getItem('paid_by_1')) {
                localStorage.removeItem('paid_by_1');
            }
            if (localStorage.getItem('pcc_holder_1')) {
                localStorage.removeItem('pcc_holder_1');
            }
            if (localStorage.getItem('pcc_type_1')) {
                localStorage.removeItem('pcc_type_1');
            }
            if (localStorage.getItem('pcc_month_1')) {
                localStorage.removeItem('pcc_month_1');
            }
            if (localStorage.getItem('pcc_year_1')) {
                localStorage.removeItem('pcc_year_1');
            }
            if (localStorage.getItem('pcc_no_1')) {
                localStorage.removeItem('pcc_no_1');
            }
            if (localStorage.getItem('cheque_no_1')) {
                localStorage.removeItem('cheque_no_1');
            }
            if (localStorage.getItem('slpayment_term')) {
                localStorage.removeItem('slpayment_term');
            }
            localStorage.removeItem('remove_slls');
        }

        <?php if ($this->session->userdata('remove_slls')) {?>
        if (localStorage.getItem('slitems')) {
            localStorage.removeItem('slitems');
        }
        if (localStorage.getItem('sldiscount')) {
            localStorage.removeItem('sldiscount');
        }
        if (localStorage.getItem('sltax2')) {
            localStorage.removeItem('sltax2');
        }
        if (localStorage.getItem('slref')) {
            localStorage.removeItem('slref');
        }
        if (localStorage.getItem('slshipping')) {
            localStorage.removeItem('slshipping');
        }
        if (localStorage.getItem('slwarehouse')) {
            localStorage.removeItem('slwarehouse');
        }
        if (localStorage.getItem('slnote')) {
            localStorage.removeItem('slnote');
        }
        if (localStorage.getItem('slinnote')) {
            localStorage.removeItem('slinnote');
        }
        if (localStorage.getItem('slcustomer')) {
            localStorage.removeItem('slcustomer');
        }
        if (localStorage.getItem('slbiller')) {
            localStorage.removeItem('slbiller');
        }
        if (localStorage.getItem('slcurrency')) {
            localStorage.removeItem('slcurrency');
        }
        if (localStorage.getItem('sldate')) {
            localStorage.removeItem('sldate');
        }
        if (localStorage.getItem('slsale_status')) {
            localStorage.removeItem('slsale_status');
        }
        if (localStorage.getItem('slpayment_status')) {
            localStorage.removeItem('slpayment_status');
        }
        if (localStorage.getItem('paid_by')) {
            localStorage.removeItem('paid_by');
        }
        if (localStorage.getItem('amount_1')) {
            localStorage.removeItem('amount_1');
        }
        if (localStorage.getItem('paid_by_1')) {
            localStorage.removeItem('paid_by_1');
        }
        if (localStorage.getItem('pcc_holder_1')) {
            localStorage.removeItem('pcc_holder_1');
        }
        if (localStorage.getItem('pcc_type_1')) {
            localStorage.removeItem('pcc_type_1');
        }
        if (localStorage.getItem('pcc_month_1')) {
            localStorage.removeItem('pcc_month_1');
        }
        if (localStorage.getItem('pcc_year_1')) {
            localStorage.removeItem('pcc_year_1');
        }
        if (localStorage.getItem('pcc_no_1')) {
            localStorage.removeItem('pcc_no_1');
        }
        if (localStorage.getItem('cheque_no_1')) {
            localStorage.removeItem('cheque_no_1');
        }
        if (localStorage.getItem('slpayment_term')) {
            localStorage.removeItem('slpayment_term');
        }
        <?php $this->erp->unset_data('remove_slls');}
        ?>

        $(document).on('click', '.sledit', function (e) {
            if (localStorage.getItem('slitems')) {
                e.preventDefault();
                var href = $(this).attr('href');
                bootbox.confirm("<?=lang('you_will_loss_sale_data')?>", function (result) {
                    if (result) {
                        window.location.href = href;
                    }
                });
            }
        });
		
		
		
    });

</script>




<script type="text/javascript">
    $(document).ready(function () {
        $('#form').hide();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
        $("#product").autocomplete({
            source: '<?= site_url('reports/suggestions'); ?>',
            select: function (event, ui) {
                $('#product_id').val(ui.item.id);
                //$(this).val(ui.item.label);
            },
            minLength: 1,
            autoFocus: false,
            delay: 300,
        });
    });
</script>
<!----------------------------------------------------------------------------------------------------------->
	<?php if ($Owner) {
			echo form_open('sales/sale_actions', 'id="action-form"');
		}
	?>
	<div class="box">
		<div class="box-header">
			<h2 class="blue"><i
					class="fa-fw fa fa-heart"></i><?=lang('repayments') . ' ' . ($warehouse_id ? $warehouse->name : lang('')) . '';?>
			</h2>
			<div class="box-icon">
				<ul class="btn-tasks">
					<li class="dropdown">
						<a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>">
							<i class="icon fa fa-toggle-up"></i>
						</a>
					</li>
					<li class="dropdown">
						<a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>">
							<i class="icon fa fa-toggle-down"></i>
						</a>
					</li>
				</ul>
			</div>
			<div class="box-icon">
				<ul class="btn-tasks">
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#">
							<i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i>
						</a>
						<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
							<li>
								<a href="<?=site_url('sales/add')?>">
									<i class="fa fa-plus-circle"></i> <?=lang('add_sale')?>
								</a>
							</li>
							<?php if ($Owner || $Admin) { ?>
								<li>
									<a href="#" id="excel" data-action="export_excel">
										<i class="fa fa-file-excel-o"></i> <?=lang('export_to_excel')?>
									</a>
								</li>
								<li>
									<a href="#" id="pdf" data-action="export_pdf">
										<i class="fa fa-file-pdf-o"></i> <?=lang('export_to_pdf')?>
									</a>
								</li>
								
								<li>
									<a href="<?= site_url('sales/sale_by_csv'); ?>">
										<i class="fa fa-plus-circle"></i>
										<span class="text"> <?= lang('add_sale_by_csv'); ?></span>
									</a>
								</li>
							<?php }else{ ?>
								<?php if($GP['sales-export']) { ?>
									<li>
										<a href="#" id="excel" data-action="export_excel">
											<i class="fa fa-file-excel-o"></i> <?=lang('export_to_excel')?>
										</a>
									</li>
									<li>
										<a href="#" id="pdf" data-action="export_pdf">
											<i class="fa fa-file-pdf-o"></i> <?=lang('export_to_pdf')?>
										</a>
									</li>
								<?php }?>
								
								<?php if($GP['sales-import']) { ?>
									<li>
										<a href="<?= site_url('sales/sale_by_csv'); ?>">
											<i class="fa fa-plus-circle"></i>
											<span class="text"> <?= lang('add_sale_by_csv'); ?></span>
										</a>
									</li>
								<?php }?>
							<?php }?>
							<li>
								<a href="#" id="combine" data-action="combine">
									<i class="fa fa-file-pdf-o"></i> <?=lang('combine_to_pdf')?>
								</a>
							</li>
							<li class="divider"></li>
							<!--<li>
								<a href="#" class="bpo"
								title="<?=$this->lang->line("delete_sales")?>"
								data-content="<p><?=lang('r_u_sure')?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button>"
								data-html="true" data-placement="left">
								<i class="fa fa-trash-o"></i> <?=lang('delete_sales')?>
							</a>-->
						</li>
						</ul>
					</li>
					<?php if (!empty($warehouses)) {
						?>
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?=lang("warehouses")?>"></i></a>
							<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
								<li><a href="<?=site_url('sales')?>"><i class="fa fa-building-o"></i> <?=lang('all_warehouses')?></a></li>
								<li class="divider"></li>
								<?php
									foreach ($warehouses as $warehouse) {
											echo '<li><a href="' . site_url('sales/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
										}
									?>
							</ul>
						</li>
					<?php }
					?>
				</ul>
			</div>
		</div>
		<?php if ($Owner) {?>
		<div style="display: none;">
			<input type="hidden" name="form_action" value="" id="form_action"/>
			<?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
		</div>
		<?= form_close()?>
		<?php }
		?>  
		<!--Search-->
		<div class="box-content">
			<div class="row">
				<div class="col-lg-12">

					<p class="introtext"><?=lang('list_results');?></p>
					<div id="form">

						<?php echo form_open("Installment_payment"); ?>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<?= lang("start_date", "start_date"); ?>
									<?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date" '); ?>
								</div>
							</div>
							
							<div class="col-sm-6">
								
								<div class="form-group">
									<?= lang("end_date", "end_date"); ?>
									<?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date" ' ); ?>
								</div>
							</div>
							<!--<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
									<?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>

								</div>
							</div>-->	

							<!--<div class="col-sm-4">                            
								<div class="form-group">
									<label class="control-label" for="applicant"><?= lang("applicant"); ?></label>                               
									<?php
										$all_cust[''] = '';
										if($customers) {
											foreach($customers as $cust) {
												
												$all_cust[$cust->id] = $cust->name;
											}
										}
										echo form_dropdown('applicant', $all_cust, (isset($_POST['applicant'])? $_POST['applicant'] : ''), 'class="form-control" id="applicant" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("applicant") .'"');
									?>							
								</div>				
							</div>-->           
							
							<div class="col-sm-6">                            
								<div class="form-group">
									<label class="control-label" for="by_branch"><?= lang("by_branch"); ?></label>
									<?php
									$br[""] = "";
									if(is_array(isset($branches) ?$branches  : (''))){
									foreach ($branches as $branch) {
										$br[$branch->id] = $branch->name;
									}}
									echo form_dropdown('branch', isset($br) ?$br  : (''), (isset($_POST['branch']) ? $_POST['branch'] : ""), 'class="form-control by_branch" id="by_branch" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("branch") . '"');
									?>
								</div>
							</div>
							
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="by_co"><?= lang("by_co"); ?></label>
									<?php
									$us[""] = "";
									if(is_array(isset($users) ?$users  : (''))){
									foreach ($users as $user) {
										$us[$user->id] = $user->first_name . " " . $user->last_name;
									}}
									echo form_dropdown('user', isset($us) ?$us  : (''), (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("user") . '"');
									?>
								</div>
							</div>
							
							<!--<div class="col-md-6">
								<div class="form-group">
									<?php echo lang('by_co', 'by_co') ?>
									<?php
									echo form_input('by_co', (isset($_POST['by_co']) ? $_POST['by_co'] : ''), 'class="form-control by_co" id="by_co"  placeholder="' . lang("select_branch_to_load") . '" ');
									?>
								</div>
							</div>-->
							
							<!--<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label" for="group_loans"><?= lang("group_loans"); ?></label>
									<?php
									$gl[""] = "";
									if(is_array(isset($group_Loan) ?$group_Loan  : (''))){
									foreach ($group_Loan as $gr_loan) {
										$gl[$gr_loan->id] = $gr_loan->name;
									}}
									echo form_dropdown('gr_loan', isset($gl) ?$gl  : (''), (isset($_POST['gr_loan']) ? $_POST['gr_loan'] : ""), 'class="form-control" id="gr_loan" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("group_loans") . '"');
									?>
								</div>
							</div>-->
						</div>
						<div class="form-group">
							<div class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
						</div>
						<?php echo form_close(); ?>

					</div>

					<div class="clearfix"></div>
				
				</div>
			</div>
		</div>
	</div>
<!------------------------------------------------------------------------------------------------------------------->
		<div class="box">
			<div class="box-content">
				<div class="row">
					<div class="col-md-12">
						<ul id="dbTab" class="nav nav-tabs">
							<li class="" id="today_installment_tap"><a href="#today_installment"><?= lang('daily_payments') ?></a></li>	
							<li class="" id="late_installment_tap"><a href="#late_installment"><?= lang('late_payments') ?></a></li>
							<li class="" id="all_installment_tap"><a href="#all_installment"><?= lang('all_payments') ?></a></li>							
						</ul>
						<!------Daily Installment Payment------>
						<div class="tab-content">								
							<div id="today_installment" class="tab-pane fade in">
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive">
											<div class="row">
												<div class="col-sm-12">
												<!-------->
													<div class="table-responsive">
														<table id="QUData" class="table table-bordered table-hover table-striped">
															<thead>
															<tr class="active">
																<th style="min-width:30px; width: 30px; text-align: center;">
																	<input class="checkbox checkft" type="checkbox" name="check"/>
																</th>
																<th><?php echo $this->lang->line("reference_no"); ?></th>
																<th><?php echo $this->lang->line("group_loans"); ?></th>
																<th><?php echo $this->lang->line("customer"); ?></th>
																<th><?php echo $this->lang->line("customer_kh"); ?></th>
																<th><?php echo $this->lang->line("phone"); ?></th>
																<!--<th><?php echo $this->lang->line("dealer"); ?></th>-->
																<th><?php echo $this->lang->line("installment_date"); ?></th>
																<th><?php echo $this->lang->line("due_date"); ?></th>
																<th><?php echo $this->lang->line("branch"); ?></th>
																<th><?php echo $this->lang->line("co_name"); ?></th>
																<th><?php echo $this->lang->line("installment_amount"); ?></th>
																<!--<th><?php echo $this->lang->line("services_fee"); ?></th>-->
																<th><?php echo $this->lang->line("remaining"); ?></th>
																<th><?php echo $this->lang->line("currency"); ?></th>
																<th><?php echo $this->lang->line("type"); ?></th>
																<th style="width:115px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
															</tr>
															</thead>
															<tbody>
															<tr>
																<td colspan="8"
																	class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?>
																</td>
															</tr>
															</tbody>
															<tfoot class="dtFilter">
															<tr class="active">
																<th style="min-width:30px; width: 30px; text-align: center;">
																	<input class="checkbox checkft" type="checkbox" name="check"/>
																</th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th style="width:115px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
															</tr>
															</tfoot>
														</table>
													</div>
												<!--------->
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!------End Installment Payment ------->
						
						<!------End Monthly Installment Payment ------->
						
						<div class="tab-content">								
							<div id="late_installment" class="tab-pane fade in">
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive">
											<div class="row">
												<div class="col-sm-12">
												<!-------->
													<div class="table-responsive">
														<table id="QUData2" class="table table-bordered table-hover table-striped">
															<thead>
															<tr class="active">
																<th style="min-width:30px; width: 30px; text-align: center;">
																	<input class="checkbox checkft" type="checkbox" name="check"/>
																</th>
																<th><?php echo $this->lang->line("reference_no"); ?></th>
																 <th><?php echo $this->lang->line("group_loans"); ?></th>
																<th><?php echo $this->lang->line("customer"); ?></th>
																<th><?php echo $this->lang->line("customer_kh"); ?></th>
																<th><?php echo $this->lang->line("phone"); ?></th>
																<!--<th><?php echo $this->lang->line("dealer"); ?></th>-->
																<th><?php echo $this->lang->line("installment_date"); ?></th>
																<th><?php echo $this->lang->line("due_date"); ?></th>
																<th><?php echo $this->lang->line("penalty_days"); ?></th>
																<th><?php echo $this->lang->line("over_due_days"); ?></th>
																<th><?php echo $this->lang->line("installment_amount"); ?></th>
																<!--<th><?php echo $this->lang->line("services_fee"); ?></th>-->
																<th><?php echo $this->lang->line("penalty_amount"); ?></th>
																<th><?php echo $this->lang->line("remaining"); ?></th>
																<th><?php echo $this->lang->line("currency"); ?></th>
																<th><?php echo $this->lang->line("type"); ?></th>
																<th style="width:115px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
															</tr>
															</thead>
															<tbody>
															<tr>
																<td colspan="8"
																	class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?>
																</td>
															</tr>
															</tbody>
															<tfoot class="dtFilter">
															<tr class="active">
																<th style="min-width:30px; width: 30px; text-align: center;">
																	<input class="checkbox checkft" type="checkbox" name="check"/>
																</th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th style="width:115px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
															</tr>
															</tfoot>
														</table>
													</div>
												
												<!--------->
													
												</div>
											
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!---------------------------------------------------------------->
						<div class="tab-content">								
							<div id="all_installment" class="tab-pane fade in">
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive">
											<div class="row">
												<div class="col-sm-12">
												<!-------->
													<div class="table-responsive">
														<table id="QUData3" class="table table-bordered table-hover table-striped">
															<thead>
															<tr class="active">
																<th style="min-width:30px; width: 30px; text-align: center;">
																	<input class="checkbox checkft" type="checkbox" name="check"/>
																</th>
																<th><?php echo $this->lang->line("reference_no"); ?></th>
																 <th><?php echo $this->lang->line("group_loans"); ?></th>
																<th><?php echo $this->lang->line("customer"); ?></th>
																<th><?php echo $this->lang->line("customer_kh"); ?></th>
																<th><?php echo $this->lang->line("phone"); ?></th>
																<!--<th><?php echo $this->lang->line("dealer"); ?></th>-->
																<th><?php echo $this->lang->line("installment_date"); ?></th>
																<th><?php echo $this->lang->line("due_date"); ?></th>
																<th><?php echo $this->lang->line("penalty_days"); ?></th>
																<th><?php echo $this->lang->line("over_due_days"); ?></th>
																<th><?php echo $this->lang->line("installment_amount"); ?></th>
																<!--<th><?php echo $this->lang->line("services_fee"); ?></th>-->
																<th><?php echo $this->lang->line("penalty_amount"); ?></th>
																<th><?php echo $this->lang->line("remaining"); ?></th>
																<th><?php echo $this->lang->line("currency"); ?></th>
																<th><?php echo $this->lang->line("type"); ?></th>
																<th style="width:115px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
															</tr>
															</thead>
															<tbody>
															<tr>
																<td colspan="8"
																	class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?>
																</td>
															</tr>
															</tbody>
															<tfoot class="dtFilter">
															<tr class="active">
																<th style="min-width:30px; width: 30px; text-align: center;">
																	<input class="checkbox checkft" type="checkbox" name="check"/>
																</th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th></th>
																<th style="width:115px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
															</tr>
															</tfoot>
														</table>
													</div>
												
												<!--------->
													
												</div>
											
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--------------------------------------------------------------------->
					</div>
				</div>
			</div>
		</div>



<script>
	$(document).ready(function(){
		$('.by_branch').on('change', function(){
				var branch = $(this).val();
				var branch_val = branch.split('#');
				var branch_id = branch_val[0];
				alert(branch_id);
				$.ajax({
					url: site.base_url + 'installment_payment/ajaxGetUserByBranchID/'+branch_id,
					dataType: 'json',
					success: function(scdata){
						if (scdata != null) {
							$(".by_co").select2("destroy").empty().attr("placeholder", "<?= lang('select_co') ?>").select2({
								placeholder: "<?= lang('select_co') ?>",
								data: scdata
							});
						}
					}
				},
					error: function () {
						bootbox.alert('<?= lang('ajax_error') ?>');
						$('#modal-loading').hide();
					}
			});
		});
	});
</script>

<script>		
	$(document).ready(function(){
			$("#excel").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url('Sales/getSalesAll/0/xls/')?>";
			return false;
		});
		$('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('Sales/getSalesAll/pdf/?v=1'.$v)?>";
            return false;
        });

	});
</script>