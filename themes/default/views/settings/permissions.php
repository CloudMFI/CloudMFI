<style>
    .table td:first-child {
        font-weight: bold;
    }

    label {
        margin-right: 10px;
    }
</style>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-folder-open"></i><?= lang('group_permissions'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang("set_permissions"); ?></p>

                <?php if (!empty($p)) {
                    if ($p->group_id != 1) {

                        echo form_open("system_settings/permissions/" . $id); ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">

                                <thead>
									<tr>
										<th colspan="7"
											class="text-center"><?php echo $group->description . ' ( ' . $group->name . ' ) ' . $this->lang->line("group_permissions"); ?></th>
									</tr>
									<tr>
										<th rowspan="2" class="text-center"><?= lang("module_name"); ?>
										</th>
										<th colspan="6" class="text-center"><?= lang("permissions"); ?></th>
									</tr>
									<tr>
										<th class="text-center"><?= lang("view"); ?></th>
										<th class="text-center"><?= lang("add"); ?></th>
										<th class="text-center"><?= lang("edit"); ?></th>
										<th class="text-center"><?= lang("delete"); ?></th>
										<th class="text-center"><?= lang("import"); ?></th>
										<th class="text-center"><?= lang("export"); ?></th>
										<th class="text-center"><?= lang("misc"); ?></th>
									</tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?= lang("products"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-index" <?php echo $p->{'products-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-add" <?php echo $p->{'products-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-edit" <?php echo $p->{'products-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-delete" <?php echo $p->{'products-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-import" <?php echo $p->{'products-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-export" <?php echo $p->{'products-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
									
                                    </td>
                                </tr>
					
                                <tr>
                                    <td><?= lang("loans_application"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-index" <?php echo $p->{'quotes-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-add" <?php echo $p->{'quotes-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-edit" <?php echo $p->{'quotes-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-delete" <?php echo $p->{'quotes-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-import" <?php echo $p->{'quotes-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-export" <?php echo $p->{'quotes-export'} ? "checked" : ''; ?>>
                                    </td>
									<td>
									   <div class="container-fluid">
											 <div class="col-md-6">
												<input type="checkbox" value="1" id="quotes-email" class="checkbox"
												   name="quotes-email" <?php echo $p->{'quotes-email'} ? "checked" : ''; ?>><label
												for="quotes-email" class="padding05"><?= lang('email') ?></label>
											</div>
											<!--<div class="col-md-6">
												<input type="checkbox" value="1" id="quotes-pdf" class="checkbox"
												   name="quotes-pdf" <?php echo $p->{'quotes-pdf'} ? "checked" : ''; ?>><label
												for="quotes-pdf" class="padding05"><?= lang('pdf') ?></label>
											</div>-->
											<div class="col-md-6">
													<input type="checkbox" value="1" class="checkbox"
												   name="simulation-index" <?php echo $p->{'simulation-index'} ? "checked" : ''; ?>><label
												for="simulation-index" class="padding05"><?= lang('simulation') ?></label>
											</div>
											<div class="col-md-6">
												<input type="checkbox" value="1" class="checkbox"
												   name="field_check-add" <?php echo $p->{'field_check-add'} ? "checked" : ''; ?>><label
												for="field_check-add" class="padding05"><?= lang('field_check') ?></label>
											</div>
											<div class="col-md-6">
												<input type="checkbox" value="1" class="checkbox"
												   name="quotes-approve" <?php echo $p->{'quotes-approve'} ? "checked" : ''; ?>><label
												for="quotes-approve" class="padding05"><?= lang('approve') ?></label>
											</div>
											<div class="col-md-6">
												<input type="checkbox" value="1" class="checkbox"
												   name="view-draft" <?php echo $p->{'view-draft'} ? "checked" : ''; ?>><label
												for="view-draft" class="padding05"><?= lang('view_draft') ?></label>
											</div>
											<div class="col-md-6">
												<input type="checkbox" value="1" class="checkbox"
												   name="advance-approve" <?php echo $p->{'advance-approve'} ? "checked" : ''; ?>><label
												for="advance-approve" class="padding05"><?= lang('advance_approve') ?></label>
											</div>
											<!--<div class="col-md-6">
												<input type="checkbox" value="1" class="checkbox"
												   name="quotes-rejected" <?php echo $p->{'quotes-rejected'} ? "checked" : ''; ?>><label
												for="quotes-rejected" class="padding05"><?= lang('rejected') ?></label>
											</div>
											<div class="col-md-6">
												<input type="checkbox" value="1" class="checkbox"
												   name="add-draft" <?php echo $p->{'add-draft'} ? "checked" : ''; ?>><label
												for="add-draft" class="padding05"><?= lang('add_draft') ?></label>
											</div>-->
											
											<div class="col-md-6">
												<input type="checkbox" value="1" class="checkbox"
												   name="quotes-transfer" <?php echo $p->{'quotes-transfer'} ? "checked" : ''; ?>><label
												for="quotes-transfer" class="padding05"><?= lang('loan_transfer') ?></label>
											</div>
											<div class="col-md-6">
												<input type="checkbox" value="1" class="checkbox"
												   name="quotes-pending_for_PO" <?php echo $p->{'quotes-pending_for_PO'} ? "checked" : ''; ?>><label
												for="quotes-pending_for_PO" class="padding05"><?= lang('pending_for_PO') ?></label>
											</div>
											<!--<div class="col-md-6">
												<input type="checkbox" value="1" class="checkbox"
												   name="quotes-edit_transfer" <?php echo $p->{'quotes-edit_transfer'} ? "checked" : ''; ?>><label
												for="quotes-edit_transfer" class="padding05"><?= lang('quotes_edit_transfer') ?></label>
											</div>-->
											<div class="col-md-6">
												<input type="checkbox" value="1" class="checkbox"
												   name="quotes-co_transfer" <?php echo $p->{'quotes-co_transfer'} ? "checked" : ''; ?>><label
												for="quotes-co_transfer" class="padding05"><?= lang('quotes_co_transfer') ?></label>
											</div>
									   </div>
									</td>
                                </tr>
								<!-----Loans Rejected-------------------->
								<tr>
                                    <td><?= lang("Loans_rejected"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="rejected-index" <?php echo $p->{'reject-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="rejected-add" <?php echo $p->{'reject-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="rejected-edit" <?php echo $p->{'reject-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="rejected-delete" <?php echo $p->{'reject-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="rejected-import" <?php echo $p->{'reject-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="rejected-export" <?php echo $p->{'reject-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td></td>
								</tr>
								<!-----Loans Approved-------------------->
								<tr>
                                    <td><?= lang("approved"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="contract-index" <?php echo $p->{'contract-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="contract-add" <?php echo $p->{'contract-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="contract-edit" <?php echo $p->{'contract-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="contract-delete" <?php echo $p->{'contract-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="contract-import" <?php echo $p->{'contract-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="contract-export" <?php echo $p->{'contract-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td></td>
								</tr>
								<!-------Loans completed-------->
								<tr>
                                    <td><?= lang("loans_completed"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="completed-index" <?php echo $p->{'completed-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="completed-add" <?php echo $p->{'completed-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="completed-edit" <?php echo $p->{'completed-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="completed-delete" <?php echo $p->{'completed-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="completed-import" <?php echo $p->{'completed-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="completed-export" <?php echo $p->{'completed-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td></td>
								</tr>
								
                                <tr>
                                    <td><?= lang("purchases"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-index" <?php echo $p->{'purchases-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-add" <?php echo $p->{'purchases-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-edit" <?php echo $p->{'purchases-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-delete" <?php echo $p->{'purchases-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-import" <?php echo $p->{'purchases-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-export" <?php echo $p->{'purchases-export'} ? "checked" : ''; ?>>
                                    </td>
									<td>
									   <div class="container-fluid">
											 <div class="col-md-6">
												<input type="checkbox" value="1" id="purchases-email" class="checkbox"
												   name="purchases-email" <?php echo $p->{'purchases-email'} ? "checked" : ''; ?>><label
												for="purchases-email" class="padding05"><?= lang('email') ?></label>
											 </div>
											 <!--<div class="col-md-6">
												<input type="checkbox" value="1" id="purchases-pdf" class="checkbox"
												   name="purchases-pdf" <?php echo $p->{'purchases-pdf'} ? "checked" : ''; ?>><label
												for="purchases-pdf" class="padding05"><?= lang('pdf') ?></label>
											 </div>-->
											 <div class="col-md-6">
												<input type="checkbox" value="1" id="purchases-payments" class="checkbox"
												   name="purchases-payments" <?php echo $p->{'purchases-payments'} ? "checked" : ''; ?>><label
												for="purchases-payments" class="padding05"><?= lang('payments') ?></label>
											 </div>
											 <div class="col-md-6">
												<input type="checkbox" value="1" id="purchases-expenses" class="checkbox"
												   name="purchases-expenses" <?php echo $p->{'purchases-expenses'} ? "checked" : ''; ?>><label
												for="purchases-expenses" class="padding05"><?= lang('expenses') ?></label>
											 </div>
										</div>
									 </td>
                                </tr>
								<!--Branch-->
								<tr>
                                    <td><?= lang("branch"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="branch-index" <?php echo $p->{'branch-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="branch-add" <?php echo $p->{'branch-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="branch-edit" <?php echo $p->{'branch-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="branch-delete" <?php echo $p->{'branch-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="branch-import" <?php echo $p->{'branch-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="branch-export" <?php echo $p->{'branch-export'} ? "checked" : ''; ?>>
                                    </td>
									<td>
									   <div class="container-fluid">
											 <div class="col-md-6">
												<input type="checkbox" value="1" id="branch-capital" class="checkbox"
												   name="branch-capital" <?php echo $p->{'branch-capital'} ? "checked" : ''; ?>><label
												for="branch-capital" class="padding05"><?= lang('capital') ?></label>
											 </div>
										</div>
									</td>
                                </tr>
								
								<!-- Permition ACC-->
								<tr>
                                    <td><?= lang("accounts"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="accounts-index" <?php echo $p->{'accounts-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="accounts-add" <?php echo $p->{'accounts-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="accounts-edit" <?php echo $p->{'accounts-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="accounts-delete" <?php echo $p->{'accounts-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="accounts-import" <?php echo $p->{'accounts-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="accounts-export" <?php echo $p->{'accounts-export'} ? "checked" : ''; ?>>
                                    </td>
									<td>
									   <div class="container-fluid">
										 <div class="col-md-6">
											<input type="checkbox" value="1" id="deposit-add" class="checkbox"
                                            name="deposit-add" <?php echo $p->{'deposit-add'} ? "checked" : ''; ?>><label
                                            for="deposit-add" class="padding05"><?= lang('add_deposit') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="disbursement-add" class="checkbox"
											name="disbursement-add" <?php echo $p->{'disbursement-add'} ? "checked" : ''; ?>><label
											for="disbursement-add" class="padding05"><?= lang('disbursement_add') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account-list_receivable" class="checkbox"
											name="account-list_receivable" <?php echo $p->{'account-list_receivable'} ? "checked" : ''; ?>><label
											for="account-list_receivable" class="padding05"><?= lang('account-list_ar_receivable') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account-list_ar_aging" class="checkbox"
											name="account-list_ar_aging" <?php echo $p->{'account-list_ar_aging'} ? "checked" : ''; ?>><label
											for="account-list_ar_aging" class="padding05"><?= lang('account-list_ar_aging') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account-bill_receipt" class="checkbox"
											name="account-bill_receipt" <?php echo $p->{'account-bill_receipt'} ? "checked" : ''; ?>><label
											for="account-bill_receipt" class="padding05"><?= lang('account-bill_receipt') ?></label>
										</div>
										<div class="col-md-6">	
											<input type="checkbox" value="1" id="account-list_ac_payable" class="checkbox"
											   name="account-list_ac_payable" <?php echo $p->{'account-list_ac_payable'} ? "checked" : ''; ?>><label
											for="account-list_ac_payable" class="padding05"><?= lang('account-list_ac_payable') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account-list_ap_aging" class="checkbox"
											name="account-list_ap_aging" <?php echo $p->{'account-list_ap_aging'} ? "checked" : ''; ?>><label
											for="account-list_ap_aging" class="padding05"><?= lang('account-list_ap_aging') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account-bill_payable" class="checkbox"
											name="account-bill_payable" <?php echo $p->{'account-bill_payable'} ? "checked" : ''; ?>><label
											for="account-bill_payable" class="padding05"><?= lang('account-bill_payable') ?></label>
										</div>
										<div class="col-md-6">
											<input type="checkbox" value="1" id="account-list_ac_head" class="checkbox"
											name="account-list_ac_head" <?php echo $p->{'account-list_ac_head'} ? "checked" : ''; ?>><label
											for="account-list_ac_head" class="padding05"><?= lang('account-list_ac_head') ?></label>
										</div>
										<div class="col-md-6">									   
											<input type="checkbox" value="1" id="account-add_ac_head" class="checkbox"
											   name="account-add_ac_head" <?php echo $p->{'account-add_ac_head'} ? "checked" : ''; ?>><label
											for="account-add_ac_head" class="padding05"><?= lang('account-add_ac_head') ?></label>
										</div>
										<div class="col-md-6">									  
											<input type="checkbox" value="1" id="account-settings" class="checkbox"
											   name="account-settings" <?php echo $p->{'account-settings'} ? "checked" : ''; ?>><label
											for="account-settings" class="padding05"><?= lang('account-settings') ?></label>
										</div>	
                                      </div>										
                                    </td>
                                </tr>
								
								<tr>
                                    <td><?= lang("re_payments"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="installment_payment-index" <?php echo $p->{'installment_payment-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="installment_payment-add" <?php echo $p->{'installment_payment-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="installment_payment-edit" <?php echo $p->{'installment_payment-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="installment_payment-delete" <?php echo $p->{'installment_payment-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="installment_payment-import" <?php echo $p->{'installment_payment-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="installment_payment-export" <?php echo $p->{'installment_payment-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td></td>
								</tr>
								<tr>
                                    <td><?= lang("bad_loans"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="collection-index" <?php echo $p->{'collection-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="collection-add" <?php echo $p->{'collection-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="collection-edit" <?php echo $p->{'collection-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="collection-delete" <?php echo $p->{'collection-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="collection-import" <?php echo $p->{'collection-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="collection-export" <?php echo $p->{'collection-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td></td>
								</tr>
								
								<!-------Payment-------->
								<tr>
                                    <td><?= lang("payment"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="payment-index" <?php echo $p->{'payment-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="payment-add" <?php echo $p->{'payment-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="payment-edit" <?php echo $p->{'payment-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="payment-delete" <?php echo $p->{'payment-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="payment-import" <?php echo $p->{'payment-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="payment-export" <?php echo $p->{'payment-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
									   <div class="container-fluid">
										 <div class="col-md-6">
											<input type="checkbox" value="1" id="installment-payment_voucher" class="checkbox"
											   name="installment-payment_voucher" <?php echo $p->{'installment-payment_voucher'} ? "checked" : ''; ?>><label
												for="installment-payment_voucher" class="padding05"><?= lang('print_payment_voucher') ?></label>
										 </div>
										</div>
									</td>
								</tr>
								<!-------Money Saving-------->
								<tr>
                                    <td><?= lang("money_saving"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="money_saving-index" <?php echo $p->{'money_saving-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="money_saving-add" <?php echo $p->{'money_saving-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="money_saving-edit" <?php echo $p->{'money_saving-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="money_saving-delete" <?php echo $p->{'money_saving-delete'} ? "checked" : ''; ?>>
                                    </td>
									<td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="money_saving-import" <?php echo $p->{'money_saving-import'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="money_saving-export" <?php echo $p->{'money_saving-export'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
										<div class="container-fluid">
										 <div class="col-md-6">
											<input type="checkbox" value="1" id="money_saving-withdrawal" class="checkbox"
											   name="money_saving-withdrawal" <?php echo $p->{'money_saving-withdrawal'} ? "checked" : ''; ?>>
											   <label for="money_saving-withdrawal" class="padding05"><?= lang('cash_withdrawal') ?></label>
										 </div>
										 <div class="col-md-6">
											<input type="checkbox" value="1" id="money_saving-compulsory" class="checkbox"
											   name="money_saving-compulsory" <?php echo $p->{'money_saving-compulsory'} ? "checked" : ''; ?>>
											   <label for="money_saving-compulsory" class="padding05"><?= lang('compulsory_saving') ?></label>
										 </div>
										</div>
									</td>
								</tr>
								
							<!--
								<tr>
                                    <td><?= lang("daily_cash_collection"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="daily_cash_collection-index" <?php echo $p->{'daily_cash_collection-index'} ? "checked" : ''; ?>>
                                    </td>
									<td colspan="5"></td>
								</tr>
							-->
                                </tbody>
                            </table>
                        </div>
                        <!--<div class="table-responsive">
                            <table cellpadding="0" cellspacing="0" border="0"
                                   class="table table-bordered table-hover table-striped" style="margin-bottom: 5px;">

                                <thead>
                                <tr>
                                    <th><?= lang("reports"); ?>
                                    </th>
                                </tr>
                                <tr>
                                    <td>
									
                                        <input type="checkbox" value="1" class="checkbox" id="products"
                                               name="reports-products" <?php echo $p->{'reports-products'} ? "checked" : ''; ?>><label
                                            for="products" class="padding05"><?= lang('products') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="sales"
                                               name="reports-sales" <?php echo $p->{'reports-sales'} ? "checked" : ''; ?>><label
                                            for="sales" class="padding05"><?= lang('sales') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="purchases"
                                               name="reports-purchases" <?php echo $p->{'reports-purchases'} ? "checked" : ''; ?>><label
                                            for="purchases" class="padding05"><?= lang('purchases') ?></label>
									
										<input type="checkbox" value="1" class="checkbox" id="quote"
											name="reports-quote" <?php echo $p->{'reports-quote'}? "checked" : '';?>>
											<label for="quote" class="padding05"><?= lang('quotes') ?></label>
										<input type="checkbox" value="1" class="checkbox" id="account"
											name="reports-account" <?php echo $p->{'reports-account'}? "checked" : '';?>>
											<label for="account" class="padding05"><?= lang('accounts') ?></label>
									
										<input type="checkbox" value="1" class="checkbox" id="operation"
											name="reports-operation" <?php echo $p->{'reports-operation'}? "checked" : '';?>>
											<label for="operation" class="padding05"><?= lang('operation') ?></label>
									
										<input type="checkbox" value="1" class="checkbox" id="underwriting"
											name="reports-underwriting" <?php echo $p->{'reports-underwriting'}? "checked" : '';?>>
											<label for="underwriting" class="padding05"><?= lang('underwriting') ?></label>
									<!--
										<input type="checkbox" value="1" class="checkbox" id="back_office"
											name="reports-back_office" <?php echo $p->{'reports-back_office'}? "checked" : '';?>>
											<label for="back_office" class="padding05"><?= lang('back_office') ?></label>
									-->
									<!--	<input type="checkbox" value="1" class="checkbox" id="contract"
											name="reports-contract" <?php echo $p->{'reports-contract'}? "checked" : '';?>>
											<label for="contract" class="padding05"><?= lang('contract') ?></label>
										<input type="checkbox" value="1" class="checkbox" id="installment"
											name="reports-installment" <?php echo $p->{'reports-installment'}? "checked" : '';?>>
											<label for="installment" class="padding05"><?= lang('installment') ?></label>
										<input type="checkbox" value="1" class="checkbox" id="collection"
											name="reports-collection" <?php echo $p->{'reports-collection'}? "checked" : '';?>>
											<label for="collection" class="padding05"><?= lang('collection') ?></label>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </div>-->
						<div class="table-responsive">
                            <table cellpadding="0" cellspacing="0" border="0"
                                   class="table table-bordered table-hover table-striped" style="margin-bottom: 5px;">
                                <thead>
                                <tr>
                                    <th>
										<!--<input type="checkbox" value="1" class="checkbox" id="reports-index" 
										name="reports-index" <?php echo $p->{'reports-index'} ? "checked" : ''; ?>>-->
										<label for="reports-index" class="padding05">
											<?= lang('reports') ?>
										</label>
                                    </th>
                                </tr>
                                <tr>
                                <td>
                                  <div class="row">
                                  <div class="col-md-12">
                                  <!-- Report Product -->
                                  <div class="col-md-4">                                    
										<div class="col-md-8" style="border-bottom: 2px solid #DDDDDD">
												<label for="quote" class="padding05"><?= lang('quotes') ?></label>
										</div><br/>
										<div class="col-md-12">
											<input type="checkbox" value="1" class="checkbox" id="quote"
												name="reports-quote" <?php echo $p->{'reports-quote'}? "checked" : '';?>>
												<label for="quote" class="padding05"><?= lang('quotes') ?></label><br/>
											<input type="checkbox" value="1" id="reports-summary_chart" class="checkbox"
												   name="reports-summary_chart" <?php echo $p->{'reports-summary_chart'} ? "checked" : ''; ?>><label
												for="reports-summary_chart" class="padding05"><?= lang('reports-summary_chart') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="reports-daily_loan"
												name="reports-daily_loan" <?php echo $p->{'reports-daily_loan'} ? "checked" : ''; ?>><label
												for="reports-daily_loan" class="padding05"><?= lang('reports-daily_loan') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="report-daily_applicant"
												name="report-daily_applicant" <?php echo $p->{'report-daily_applicant'} ? "checked" : ''; ?>><label
												for="report-daily_applicant" class="padding05"><?= lang('daily_applicant') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="reports-daily_register"
												name="reports-daily_register" <?php echo $p->{'reports-daily_register'} ? "checked" : ''; ?>><label
												for="reports-daily_register" class="padding05"><?= lang('reports-daily_register') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="reports-applicant"
												name="reports-applicant" <?php echo $p->{'reports-applicant'} ? "checked" : ''; ?>><label
												for="reports-applicant" class="padding05"><?= lang('reports_applicant') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="reports-nbc"
												name="reports-nbc" <?php echo $p->{'reports-nbc'} ? "checked" : ''; ?>><label
												for="reports-nbc" class="padding05"><?= lang('nbc_reports') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="reports-loans"
												name="reports-loans" <?php echo $p->{'reports-loans'} ? "checked" : ''; ?>><label
												for="reports-loans" class="padding05"><?= lang('loan_report') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="reports-daily_transaction"
												name="reports-daily_transaction" <?php echo $p->{'reports-daily_transaction'} ? "checked" : ''; ?>><label
												for="reports-daily_transaction" class="padding05"><?= lang('daily_transaction') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="reports-daily_cash"
												name="reports-daily_cash" <?php echo $p->{'reports-daily_cash'} ? "checked" : ''; ?>><label
												for="reports-daily_cash" class="padding05"><?= lang('daily_cash') ?></label><br/>
										</div>
                                  </div>
                                  <!-- /////////////// -->
                                  <div class="col-md-4">
                                    <div class="col-md-8" style="border-bottom: 2px solid #DDDDDD">
											<label for="account" class="padding05"><?= lang('accounting') ?></label>
                                    </div><br/>
                                    <div class="col-md-12">
									    <input type="checkbox" value="1" class="checkbox" id="account"
											name="reports-account" <?php echo $p->{'reports-account'}? "checked" : '';?>>
											<label for="account" class="padding05"><?= lang('accounts') ?></label><br/>
											
										<input type="checkbox" value="1" class="checkbox" id="reports-ledger"
											name="reports-ledger" <?php echo $p->{'reports-ledger'}? "checked" : '';?>>
											<label for="reports-ledger" class="padding05"><?= lang('report_ledger') ?></label><br/>
											
										<input type="checkbox" value="1" class="checkbox" id="reports-trial_balance"
											name="reports-trial_balance" <?php echo $p->{'reports-trial_balance'}? "checked" : '';?>>
											<label for="reports-trial_balance" class="padding05"><?= lang('trial_balance') ?></label><br/>
										<input type="checkbox" value="1" class="checkbox" id="reports-balance_sheet"
											name="reports-balance_sheet" <?php echo $p->{'reports-balance_sheet'}? "checked" : '';?>>
											<label for="reports-balance_sheet" class="padding05"><?= lang('balance_sheet') ?></label><br/>
										<input type="checkbox" value="1" class="checkbox" id="reports-income_statement"
											name="reports-income_statement" <?php echo $p->{'reports-income_statement'}? "checked" : '';?>>
											<label for="reports-income_statement" class="padding05"><?= lang('income_statement') ?></label><br/>
										<input type="checkbox" value="1" class="checkbox" id="reports-cash_books"
											name="reports-cash_books" <?php echo $p->{'reports-cash_books'}? "checked" : '';?>>
											<label for="reports-cash_books" class="padding05"><?= lang('cash_books') ?></label><br/>	
                                    </div>
                                  </div>
                                  <!-- ////////////// -->
                                  <div class="col-md-4">
                                    <div class="col-md-8" style="border-bottom: 2px solid #DDDDDD">
											<label for="underwriting" class="padding05"><?= lang('underwriting') ?></label>
                                    </div><br/>
                                    <div class="col-md-12">
                                           <input type="checkbox" value="1" class="checkbox" id="underwriting"
											name="reports-underwriting" <?php echo $p->{'reports-underwriting'}? "checked" : '';?>>
											<label for="underwriting" class="padding05"><?= lang('underwriting_performance') ?></label><br/>
                                      
                                    </div>
                                  </div>                         
                                  </div>

                                    <div class="col-md-12">
                                        <hr>
                                      <div class="col-md-4"> 
                                        <div class="col-md-8" style="border-bottom: 2px solid #DDDDDD">
											<label for="contract" class="padding05"><?= lang('contract') ?></label>
                                        </div><br/>
                                        <div class="col-md-12">
											<input type="checkbox" value="1" class="checkbox" id="contract"
												name="reports-contract" <?php echo $p->{'reports-contract'}? "checked" : '';?>>
												<label for="contract" class="padding05"><?= lang('end_contract') ?></label><br/>
											<input type="checkbox" value="1" class="checkbox" id="reports-contract_excel"
												name="reports-contract_excel" <?php echo $p->{'reports-contract_excel'}? "checked" : '';?>>
												<label for="reports-contract_excel" class="padding05"><?= lang('contract_reports') ?></label><br/>  
										</div>
									</div>
										
                                    <!-- Profit -->
                                        <div class="col-md-4">
                                        <div class="col-md-8" style="border-bottom: 2px solid #DDDDDD">
											<label for="installment" class="padding05"><?= lang('payments') ?></label>
                                        </div><br/>
                                        		
											<div class="col-md-12">
											  <input type="checkbox" value="1" class="checkbox" id="installment"
												name="reports-installment" <?php echo $p->{'reports-installment'}? "checked" : '';?>>
												<label for="installment" class="padding05"><?= lang('payments_report') ?></label><br/>
											  <input type="checkbox" value="1" class="checkbox" id="reports-installments"
												name="reports-installments" <?php echo $p->{'reports-installments'}? "checked" : '';?>>
												<label for="reports-installments" class="padding05"><?= lang('re_payments_report') ?></label><br/>
											</div>
											
										
                                        </div>
                                        <!-- Account -->
                                        <div class="col-md-4">
                                          <div class="col-md-8" style="border-bottom: 2px solid #DDDDDD">
											<label for="collection" class="padding05"><?= lang('collection') ?></label>
                                          </div><br/>
                                          <div class="col-md-12">
											<input type="checkbox" value="1" class="checkbox" id="collection"
											name="reports-collection" <?php echo $p->{'reports-collection'}? "checked" : '';?>>
											<label for="collection" class="padding05"><?= lang('phone_collection') ?></label><br/>
											 
                                          </div>  
                                        </div>                                      
                                    </div>
                                  </div>
                                  </td>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary"><?=lang('update')?></button>
                        </div>
                        <?php echo form_close();
                    } else {
                        echo $this->lang->line("group_x_allowed");
                    }
                } else {
                    echo $this->lang->line("group_x_allowed");
                } ?>


            </div>
        </div>
    </div>
</div>