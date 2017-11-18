<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Down_Payment extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        /*if (!$this->Owner && !$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }*/
		
        $this->lang->load('down_payment', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('cmt_model');
		$this->load->model('quotes_model');
		$this->load->model('site');
		$this->load->model('down_payment_model');
		$this->load->model('sales_model');
		$this->load->model('companies_model');
		$this->load->model('reports_model');
    }

    function index()
    {
        $this->erp->checkPermissions();
		$this->erp->load->model('reports_model');
		$this->data['users'] = $this->reports_model->getStaff();
		$this->data['products'] = $this->site->getProducts();
		$this->data['dealer'] = $this->site->getAllDealer('supplier');
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = isset($warehouse_id) ?$warehouse_id  : ('');
            $this->data['warehouse'] = isset($warehouse_id) ? $this->site->getWarehouseByID($warehouse_id) : null;
        } else {
            $this->data['warehouses'] = null;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : null;
        }
		if (isset($this->permission['reports-back_office']) ?$this->permission['reports-back_office']  : ('')){
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('contracts')));
			$meta = array('page_title' => lang('contracts'), 'bc' => $bc);
		}else{
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('down_payment')));
			$meta = array('page_title' => lang('quotes'), 'bc' => $bc);
		}
        
        $this->page_construct('down_payment/index', $meta, $this->data);
    }
	
	public function getSales($warehouse_id = null)
    {
        $this->erp->checkPermissions('index');

		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
        }
        if ($this->input->get('customer')) {
            $customer = $this->input->get('customer');
        } else {
            $customer = NULL;
        }
		if ($this->input->get('product_id')) {
            $product_id = $this->input->get('product_id');
        } else {
            $product_id = NULL;
        }
        if ($this->input->get('dealer')) {
            $dealer = $this->input->get('dealer');
        } else {
            $dealer = NULL;
        }
		if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
        }
        if ($this->input->get('start_date')) {
            $start_date = $this->input->get('start_date');
        } else {
            $start_date = NULL;
        }
        if ($this->input->get('end_date')) {
            $end_date = $this->input->get('end_date');
        } else {
            $end_date = NULL;
        }
		
        if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date = $this->erp->fld($end_date);
        }

		
        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        $detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
        $payments_link = anchor('down_payment/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        //$email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('sales/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
        //$pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $pdf_link = anchor('sales/certify_latter', '<i class="fa fa-file-pdf-o"></i> ' . lang('CertifyLetter'));
        $email_link = anchor('sales/anex_contract', '<i class="fa fa-file-pdf-o"></i> ' . lang('Anex contract'));
        $return_link = anchor('sales/return_sale/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_sale'));
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_down_payment") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_down_payment') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            
            <li>' . $payments_link . '</li>
            <li>' . $add_payment_link . '</li>
            <li>' . $edit_link . '</li>
            <li>' . $delete_link . '</li>
        </ul>
		</div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables
                ->select($this->db->dbprefix('sales').".id,".
						$this->db->dbprefix('sales').".reference_no,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other, ".
						$this->db->dbprefix('sales').".biller,".
						$this->db->dbprefix('users').".username, myBranch.name as branchName,".
						$this->db->dbprefix('sale_items').".product_name, ".
						$this->db->dbprefix('sale_items').".product_year, ".
						$this->db->dbprefix('variants').".name, ".
						$this->db->dbprefix('sales').".total,
						CONCAT((".$this->db->dbprefix('sales').".advance_percentage_payment * 100), '%') as percentage,".
						$this->db->dbprefix('sales').".advance_payment,
						SUM(".$this->db->dbprefix('sale_services').".amount) as total_service_charge,".
						$this->db->dbprefix('sales').".second_payment,
						((COALESCE(".$this->db->dbprefix('sales').".grand_total, 0) - COALESCE(".$this->db->dbprefix('sales').".advance_payment, 0))) as total_amount,".
						$this->db->dbprefix('sales').".sale_status")
                ->from('sales')
				->join('users','sales.created_by=users.id','INNER')
				->join('sale_items', 'sales.id = sale_items.sale_id', 'INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('companies as myBranch', 'users.branch_id = myBranch.id')
				->join('products', 'sale_items.product_id = products.id', 'INNER')
				->join('sale_services', 'sales.id = sale_services.quote_id', 'left')
				->join('variants', 'variants.id = sale_items.color', 'left')
				->group_by('sales.id')
				->where($this->db->dbprefix('sales').'.sale_status =', 'activated')
                ->where('warehouse_id', $warehouse_id);
        } else {
            $this->datatables
                ->select($this->db->dbprefix('sales').".id,".
						$this->db->dbprefix('sales').".reference_no,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other, ".
						//$this->db->dbprefix('sales').".biller, ".
						$this->db->dbprefix('users').".username, myBranch.name as branchName,".
						$this->db->dbprefix('sale_items').".product_name, ".
						$this->db->dbprefix('sale_items').".product_year, ".
						$this->db->dbprefix('variants').".name, ".
						$this->db->dbprefix('sales').".total,
						CONCAT((".$this->db->dbprefix('sales').".advance_percentage_payment * 100), '%') as percentage,".
						$this->db->dbprefix('sales').".advance_payment,
						SUM(".$this->db->dbprefix('sale_services').".amount) as total_service_charge,".
						$this->db->dbprefix('sales').".second_payment,
						((COALESCE(".$this->db->dbprefix('sales').".grand_total, 0) - COALESCE(".$this->db->dbprefix('sales').".advance_payment, 0))) as total_amount,".
						$this->db->dbprefix('sales').".sale_status")
                ->from('sales')
				->join('users','sales.created_by=users.id','INNER')
				->join('sale_items', 'sales.id = sale_items.sale_id', 'INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('companies as myBranch', 'users.branch_id = myBranch.id')
				->join('products', 'sale_items.product_id = products.id', 'INNER')
				->join('sale_services', 'sales.id = sale_services.sale_id', 'left')
				->join('variants', 'variants.id = sale_items.color', 'left')
				->where($this->db->dbprefix('sales').'.sale_status <>', 'activated')
				->group_by('sales.id');
        }
		
		if ($product_id) {
			$this->datatables->join('sale_items as si', 'si.sale_id = sales.id', 'left');
			$this->datatables->where('si.product_id', $product_id);
		}
		
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			$this->datatables->where('sales.created_by', $this->session->userdata('user_id'));
		}		
		if ($user_query) {
			$this->datatables->where('sales.created_by', $user_query);
		}
		if ($reference_no) {
			$this->datatables->like('sales.reference_no', $reference_no);
		}
		if ($dealer) {
			$this->datatables->where('sales.biller_id', $dealer);
		}
		if ($customer) {
			$this->datatables->where('sales.customer_id', $customer);
		}
		
		if ($warehouse) {
			$this->datatables->where('sales.warehouse_id', $warehouse);
		}

		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
		
        $this->datatables->add_column("Actions", $action,$this->db->dbprefix('sales').".id");
        echo $this->datatables->generate();
    }
	
	
	
	public function Down_Payment_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {

                    foreach ($_POST['val'] as $id) {
                        $this->quotes_model->deleteQuote($id);
                    }
                    $this->session->set_flashdata('message', $this->lang->line("quotes_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);

                } elseif ($this->input->post('form_action') == 'combine') {

                    $html = $this->combine_pdf($_POST['val']);

                } elseif ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

					$this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('quotes'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('customer_name_(en)'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('customer_name_(other)'));
					$this->excel->getActiveSheet()->SetCellValue('D1', lang('approved_date'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('c.o_name'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('branch'));
					$this->excel->getActiveSheet()->SetCellValue('G1', lang('interest_rate'));
					$this->excel->getActiveSheet()->SetCellValue('H1', lang('pay_term'));
					$this->excel->getActiveSheet()->SetCellValue('I1', lang('term'));
                    $this->excel->getActiveSheet()->SetCellValue('J1', lang('loan_request'));
					$this->excel->getActiveSheet()->SetCellValue('K1', lang('disburse'));
					$this->excel->getActiveSheet()->SetCellValue('L1', lang('remaining'));
					$this->excel->getActiveSheet()->SetCellValue('M1', lang('currency'));
                    $row = 2;
					//$this->erp->print_arrays($_POST['val']);//=var_dump();=print_r();
					//print_r($_POST['val']);

                    foreach ($_POST['val'] as $id) {
                        $qu = $this->quotes_model->getContractByID($id);
						//$this->erp->print_arrays($qu);
						$setting = $this->quotes_model->get_setting();
						$total = $this->erp->convertCurrency($qu->currency_code, $setting->default_currency, $qu->total);
						$grand_total = $this->erp->convertCurrency($qu->currency_code, $setting->default_currency, $qu->grand_total);
						$approved_date = date('d/m/Y', strtotime( $qu->approved_date ));
						 
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $qu->reference_no);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $qu->customer_name);
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $qu->customer_name_other);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $approved_date);   
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $qu->co_name);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $qu->branch_name);
						$this->excel->getActiveSheet()->SetCellValue('G' . $row, $qu->interest);
						$this->excel->getActiveSheet()->SetCellValue('H' . $row, $qu->pay_term);
						$this->excel->getActiveSheet()->SetCellValue('I' . $row, $qu->term);
                        $this->excel->getActiveSheet()->SetCellValue('J' . $row, $total);
						$this->excel->getActiveSheet()->SetCellValue('K' . $row, $grand_total);
                        $this->excel->getActiveSheet()->SetCellValue('L' . $row, $total - $grand_total);
						$this->excel->getActiveSheet()->SetCellValue('M' . $row, $qu->currency_name);
                        $row++;
                    }

                   
					$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
					
					
					
					
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'quotations_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php";
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', $this->lang->line("no_quote_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
	
	
	function add_payment($id=NULL) {
		
		$this->erp->checkPermissions('payments', true);
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        
        $this->form_validation->set_rules('dealer', lang("dealer"), 'required');
		$this->form_validation->set_rules('pay_date', lang("pay_date"), 'required');
		$this->form_validation->set_rules('payment_status', lang("payment_status"), 'required');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $pay_date = $this->erp->fld(trim($this->input->post('pay_date')));
            } else {
                $pay_date = date('Y-m-d H:i:s');
            }
			
			$dealer = $this->input->post('dealer');
			$applicant = $this->input->post('applicant');
			$down_percentage = $this->input->post('down_percentage');
			$advance_payment = str_replace(',', '', $this->input->post('advance_payment'));
			$other_amount = str_replace(',', '', $this->input->post('other_amount'));
			$due_date = $this->erp->fld(trim($this->input->post('due_date')));
			$pay_method = $this->input->post('pay_method');
			$pay_status = $this->input->post('payment_status');
			$sale_id = $this->input->post('sale_id');
			$reference_no = $this->site->getReference('sp',$dealer);
			$sal = $this->down_payment_model->getSaleByID($sale_id);
			$services_fee = $this->down_payment_model->getSaleServices($sale_id);
			
			$total_services_fee = 0;
			foreach($services_fee as $service_fee) {
				$total_services_fee += $service_fee->amount;
			}
			
			$total_amt = $advance_payment + $other_amount + $total_services_fee;
			$lease_amt = $sal->grand_total - $advance_payment;
			$payment = array(
								'date' => $pay_date,
								'sale_id' => $sale_id,
								'reference_no' => $reference_no,
								'amount' => $total_amt,
								'pos_balance' => $lease_amt,
								'paid_by' => $pay_method,
								'created_by' => $this->session->userdata('user_id'),
								'type' => $pay_status,
								'biller_id'	=> $dealer,
								'paid_type' => 'down_payment',
							);
			//$this->erp->print_arrays($payment);				
			$down_payment = array(
									'sale_status' => 'activated',
									'other_amount' => $other_amount,
									'due_date' => $due_date,
									'paid' => $advance_payment,
									'contract_date' => date('Y-m-d H:i:s'),
								 );
			//$this->erp->print_arrays($down_payment);					
			$rate_type = $sal->rate_type;
			$term = $sal->term;
			$interest = $sal->interest_rate;
			$frequency = $sal->frequency;
			$loan = $this->erp->getPaymentSchedule($sale_id, $lease_amt, $rate_type, $interest, $term, $frequency, $due_date);
			
			//$this->erp->print_arrays($loan);

        } elseif ($this->input->post('add_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->down_payment_model->addPayment($payment) && $this->down_payment_model->addLoan($loan) && $this->down_payment_model->updateDownPayment($down_payment, $sale_id)) {
            $this->session->set_flashdata('message', lang("payment_added"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['billers'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllCompanies('supplier') : null;
            $sale = $this->down_payment_model->getSaleByID($id);
            $this->data['sale'] = $sale;
			$this->data['services'] = $this->down_payment_model->getSaleServices($sale->id);
			$this->data['customers'] = $this->down_payment_model->getAllCustomerCompanies();
			$this->data['down_percentages'] = $this->site->getAllDownPercentage();
			$this->data['finacal_products'] = $this->site->getAllCustomerGroup();
            $this->data['modal_js'] = $this->site->modal_js();

            $this->load->view($this->theme . 'down_payment/add_payment', $this->data);
        }
	}
	
	function payments($id = NULL)
    {
        $this->erp->checkPermissions(false, true);
		
		$inv = $this->down_payment_model->getInvoiceByID($id);
		$payments = $this->down_payment_model->getCurrentBalance($inv->id);
		$current_balance = $inv->grand_total;
		foreach($payments as $curr_pay) {
			//if ($curr_pay->id < $id) {
				$current_balance -= $curr_pay->amount;
			//}
		}
		$this->data['curr_balance'] = $current_balance;
        $this->data['payments'] = $this->down_payment_model->getInvoicePayments($id);
        $this->load->view($this->theme . 'down_payment/payments', $this->data);
    }
	
	function payment_note($id = NULL)
    {
        $payment = $this->down_payment_model->getPaymentByID($id);
        $inv = $this->down_payment_model->getInvoiceByID($payment->sale_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['inv'] = $inv;
		
		$payments = $this->down_payment_model->getCurrentBalance($inv->id);
		$current_balance = $inv->grand_total;
		foreach($payments as $curr_pay) {
			if ($curr_pay->id < $id) {
				$current_balance -= ($curr_pay->amount-$curr_pay->extra_paid);
			}
		}
		$this->data['curr_balance'] = $current_balance;
		
		/* Apartment */
		$this->data['rows'] = $this->down_payment_model->getAllInvoiceItems($inv->id);
		$this->data['exchange_rate_kh_c'] = $this->down_payment_model->getExchange_rate('KHM');
		/* / */
		
        $this->data['payment'] = $payment;
        $this->data['page_title'] = $this->lang->line("payment_note");
		
        $this->load->view($this->theme . 'down_payment/payment_note', $this->data);
    }
	
	function edit_payment($id = NULL)
    {
        $this->erp->checkPermissions('edit', true);
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        //$this->form_validation->set_rules('note', lang("note"), 'xss_clean');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $payment = array(
                'date' => $date,
                'sale_id' => $this->input->post('sale_id'),
                'reference_no' => $this->input->post('reference_no'),
                'amount' => $this->input->post('amount-paid'),
                'paid_by' => $this->input->post('paid_by'),
                'cheque_no' => $this->input->post('cheque_no'),
                'cc_no' => $this->input->post('pcc_no'),
                'cc_holder' => $this->input->post('pcc_holder'),
                'cc_month' => $this->input->post('pcc_month'),
                'cc_year' => $this->input->post('pcc_year'),
                'cc_type' => $this->input->post('pcc_type'),
                'note' => $this->input->post('note'),
                'created_by' => $this->session->userdata('user_id')
            );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $payment['attachment'] = $photo;
            }

            //$this->erp->print_arrays($payment);

        } elseif ($this->input->post('edit_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }


        if ($this->form_validation->run() == true && $this->down_payment_model->updatePayment($id, $payment)) {
            $this->session->set_flashdata('message', lang("payment_updated"));
            redirect("down_payment");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['payment'] = $this->down_payment_model->getPaymentByID($id);
            $this->data['modal_js'] = $this->site->modal_js();

            $this->load->view($this->theme . 'down_payment/edit_payment', $this->data);
        }
    }
	
	function delete_payment($id = NULL)
    {
        $this->erp->checkPermissions('delete');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->down_payment_model->deletePayment($id)) {
            //echo lang("payment_deleted");
            $this->session->set_flashdata('message', lang("payment_deleted"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
	
	function tranfer_contract()
	{
		$this->erp->checkPermissions('index',false,'contract');

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = isset($warehouse_id) ?$warehouse_id  : ('');
            $this->data['warehouse'] = isset($warehouse_id) ? $this->site->getWarehouseByID($warehouse_id) : null;
        } else {
            $this->data['warehouses'] = null;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : null;
        }
		if (isset($this->permission['reports-back_office']) ?$this->permission['reports-back_office']  : ('')){
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('contracts')));
			$meta = array('page_title' => lang('contracts'), 'bc' => $bc);
		}else{
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('loan_transfer')));
			$meta = array('page_title' => lang('loan_transfer'), 'bc' => $bc);
		}
        
        $this->page_construct('down_payment/tranfer_contract', $meta, $this->data);
	}
	
	function contract_list()
	{
		$this->erp->checkPermissions('index', true, 'contract');
		$this->erp->load->model('reports_model');
		$this->data['users'] = $this->reports_model->getStaff();
		$this->data['products'] = $this->site->getProducts();
		$this->data['group_Loan'] = $this->site->getLoanGroups();
		$this->data['dealer'] = $this->site->getAllDealer('supplier');
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = isset($warehouse_id) ?$warehouse_id  : ('');
            $this->data['warehouse'] = isset($warehouse_id) ? $this->site->getWarehouseByID($warehouse_id) : null;
        } else {
            $this->data['warehouses'] = null;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : null;
        }
		if (isset($this->permission['reports-back_office']) ?$this->permission['reports-back_office']  : ('')){
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('contracts')));
			$meta = array('page_title' => lang('contracts'), 'bc' => $bc);
		}else{
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('loan_approved')));
			$meta = array('page_title' => lang('loan_approved'), 'bc' => $bc);
		}
		//session
        //$this->data['setting'] = $this->site->get_setting();
		
        $this->page_construct('down_payment/contract_list', $meta, $this->data);
	}
	
	public function getContracts($warehouse_id = null)
    {
		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
        }
        if ($this->input->get('customer')) {
            $customer = $this->input->get('customer');
        } else {
            $customer = NULL;
        }
		if ($this->input->get('product_id')) {
            $product_id = $this->input->get('product_id');
        } else {
            $product_id = NULL;
        }
        if ($this->input->get('dealer')) {
            $dealer = $this->input->get('dealer');
        } else {
            $dealer = NULL;
        }
		if ($this->input->get('gr_loan')) {
            $gr_loan = $this->input->get('gr_loan');
        } else {
            $gr_loan = NULL;
        }
		if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
        }
        if ($this->input->get('start_date')) {
            $start_date = $this->input->get('start_date');
        } else {
            $start_date = NULL;
        }
        if ($this->input->get('end_date')) {
            $end_date = $this->input->get('end_date');
        } else {
            $end_date = NULL;
        }
		
        if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date = $this->erp->fld($end_date);
        }
        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
		$approve_link = anchor('quotes/approvedApplicant/$4', '<i class="fa fa-file-text-o"></i> ' . lang('view_details'));
        $detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
        $payments_link = anchor('down_payment/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
		$payment_schedule = anchor('Installment_payment/payment_schedule/0/1/$1', '<i class="fa fa-file-text-o"></i> ' . lang('payment_schedule'), 'data-toggle="modal" data-target="#myModal"');
		$deposit = anchor('Account/add_deposit/$1', '<i class="fa fa-file-text-o"></i> ' . lang('add_deposit'), 'data-toggle="modal" data-target="#myModal"');
		$collateral_contract_identification = anchor('Installment_payment/collateral_contract_identification/$1', '<i class="fa fa-file-text-o"></i> ' . lang('collateral_contract_identification'),'target="_blank"');
		$collateral_contract_land = anchor('Installment_payment/collateral_contract_land/$1', '<i class="fa fa-file-text-o"></i> ' . lang('collateral_contract_land'),'target="_blank"');
		$guareentee_contract = anchor('Installment_payment/guareentee_contract/$1', '<i class="fa fa-file-text-o"></i> ' . lang('guareentee_contract'),'target="_blank"');		
		$collateral_contract = anchor('Installment_payment/collateral_contract/$1', '<i class="fa fa-file-text-o"></i> ' . lang('collateral_contract'),'target="_blank"');		
		$mcontract = anchor('Installment_payment/mfi_contract/$1', '<i class="fa fa-file-text-o"></i> ' . lang('loan_agreement'),'target="_blank"');
		$guarantor = anchor('Installment_payment/guarantor_contract/$1', '<i class="fa fa-file-text-o"></i> ' . lang('guarantor_contract'),'target="_blank"');
		$group_agreement = anchor('Installment_payment/group_agreement/$1/$3', '<i class="fa fa-file-text-o"></i> ' . lang('group_agreement'),'target="_blank"');
		$received = anchor('Installment_payment/Received_loans/$1', '<i class="fa fa-file-text-o"></i> ' . lang('received_loan_form'),'target="_blank"');
		//$payment_schedule_loan = anchor('Installment_Payment/export_loan/0/1/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('loan_payment_schedule'));
		$add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        //$email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('sales/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
        //$pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        //$guarantor_annex = anchor('down_payment/guarantor_annex/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('guarantor_annex'),'target="_blank"');
        //$guarantor_form = anchor('down_payment/guarantor_form/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('guarantor_form'),'target="_blank"');
        //$leasing_contract = anchor('down_payment/leasing_contract/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('leasing_contract'),'target="_blank"');
        //$pdf_link = anchor('sales/certify_latter/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('Certify_Letter'),'target="_blank"');
        //$email_link = anchor('sales/anex_contract/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('Anex_contract'),'target="_blank"');
        $return_link = anchor('sales/return_sale/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_sale'));
        $add_customer_deposit = anchor('down_payment/add_deposit/$2', '<i class="fa fa-file-text-o"></i> ' . lang('add_customer_deposit'), 'data-toggle="modal" data-target="#myModal"');
		$list_customer_deposit = anchor('customers/deposits/$2', '<i class="fa fa-file-text-o"></i> ' . lang('list_customer_deposit'), 'data-toggle="modal" data-target="#myModal"');
		
		$delete_link = "<a href='#' class='po' title='<b>" . lang("delete_contract") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_contract') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
			 <li class="ps">' . $approve_link . '</li>
            <li class="ps">' . $payment_schedule . '</li>
			<li class="de">' . $deposit . '</li>
			<!--<li class="cci">' . $collateral_contract_identification . '</li>
			<li class="ccl">' . $collateral_contract_land . '</li>
			<li class="gc">' . $guareentee_contract . '</li>-->
			<li class="cc">' . $collateral_contract . '</li>
			<li class="gr">' . $mcontract . '</li>
			<li class="mc">' . $guarantor . '</li>
			<li class="group_a">' . $group_agreement . '</li>
			<li class="mc">' . $received . '</li>
			<!--<li class="ps">' . $add_customer_deposit . '</li>
			<li class="ps">' . $list_customer_deposit . '</li>-->
			<li class="dl">' . $delete_link . '</li>
        </ul>
		</div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';
		$setting = $this->down_payment_model->getSettingCurrncy();
        $this->load->library('datatables');
        
            $this->datatables
                ->select($this->db->dbprefix('sales').".id,".
						$this->db->dbprefix('sales').".customer_id,".
						$this->db->dbprefix('sales').".reference_no,".
						$this->db->dbprefix('sales').".quote_id as qi, ". 
						$this->db->dbprefix('loan_groups').".name AS glname,
						CONCAT(".$this->db->dbprefix('companies').".family_name,' ',".$this->db->dbprefix('companies').".name) AS customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other,' ',".$this->db->dbprefix('companies').".name_other) as customer_name_other, ".
						$this->db->dbprefix('sales').".approved_date,
						CONCAT(".$this->db->dbprefix('users').".first_name,' ',".$this->db->dbprefix('users').".last_name) as co_name ,myBranch.name,".	
						$this->db->dbprefix('sale_items').".product_name,
						CONCAT(TRUNCATE((".$this->db->dbprefix('sales').".interest_rate*100), 2),' ', '%') AS interest,
						CONCAT(TRUNCATE(".$this->db->dbprefix('sales').".term, 0), ' ', 'Days')  AS term, 
						IF(".$this->db->dbprefix('sales').".frequency = 7, 'Weekly', IF(".$this->db->dbprefix('sales').".frequency = 14, 'Two Week', IF(".$this->db->dbprefix('sales').".frequency = 30, 'Monthly',''))), 						
						
						((COALESCE(".$this->db->dbprefix('sales').".total, 0))) * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate .") as total,
						".$this->db->dbprefix('sales').".grand_total * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate .") as disburse,
						((((COALESCE(".$this->db->dbprefix('sales').".total, 0))) * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate ."))- (".$this->db->dbprefix('sales').".grand_total * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate ."))) as remaining,".
							
						$this->db->dbprefix('currencies').".name AS crname, ".
						$this->db->dbprefix('sales').".sale_status, ".						
						$this->db->dbprefix('sales').".mfi AS mfi, ".
						$this->db->dbprefix('sales').".loan_group_id AS loan_g_id, ".
						$this->db->dbprefix('companies').".id AS com_id")
                ->from('sales')
				->join('sale_items', 'sales.id = sale_items.sale_id', 'INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('products', 'sale_items.product_id = products.id', 'INNER')
				->join('sale_services', 'sales.id = sale_services.sale_id', 'left')
				->join('variants', 'variants.id = sale_items.color', 'left')
				->join('quotes','quotes.id = sales.quote_id','left')
				->join('quote_items','quote_items.quote_id = quotes.id','left')
				->join('users','sales.by_co = users.id','INNER')
				->join('companies AS myBranch', 'sales.branch_id= myBranch.id', 'left')
				->join('currencies','currencies.code = sale_items.currency_code','left')
				->join('loan_groups','loan_groups.id = sales.loan_group_id','left')
				->where($this->db->dbprefix('sales').'.sale_status =', 'approved')			
				->group_by('sales.id')
				->order_by('sales.id','DESC');
				//(SELECT SUM(amount) FROM ".$this->db->dbprefix('sale_services')." WHERE sale_id = ".$this->db->dbprefix('sales').".id) as total_service_charge,
        
		
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->datatables->where('sales.branch_id', $this->session->branch_id);
		}
		if ($product_id) {
			$this->datatables->join('sale_items as si', 'si.sale_id = sales.id', 'left');
			$this->datatables->where('si.product_id', $product_id);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            //$this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		/*if($this->GP &&!($this->Owner && $this->Admin) && $this->session->userdata('view_right') == 0){
			$this->datatables->where('sales.created_by', $this->session->userdata('user_id'));
		}*/
		if ($user_query) {
			$this->datatables->where('sales.by_co', $user_query);
		}
		if ($reference_no) {
			$this->datatables->like('sales.reference_no', $reference_no);
		}
		if ($dealer) {
			$this->datatables->where('sales.biller_id', $dealer);
		}
		if ($gr_loan) {
			$this->datatables->where('sales.loan_group_id', $gr_loan);
		}
		if ($customer) {
			$this->datatables->where('sales.customer_id', $customer);
		}
		if ($warehouse) {
			$this->datatables->where('sales.warehouse_id', $warehouse);
		}
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
        $this->datatables->add_column("Actions", $action,$this->db->dbprefix('sales').".id, com_id, loan_g_id, qi");
        $this->datatables->unset_column("com_id");
		$this->datatables->unset_column("loan_g_id");
		$this->datatables->unset_column("qi");
		echo $this->datatables->generate();
    }
	
	function loan_activated()
	{
		$this->erp->checkPermissions();
		$this->load->model('reports_model');
		$this->erp->load->model('reports_model');
		$this->data['products'] = $this->site->getProducts();
		$this->data['group_Loan'] = $this->site->getLoanGroups();
		$this->data['dealer'] = $this->site->getAllDealer('supplier');
		if(isset($_GET['d']) != ""){
			$date = $_GET['d'];
			$this->data['date'] = $date;
		}
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
		$this->data['customers'] = $this->site->getCustomerIDName();
		$this->data['branches'] = $this->site->getAllBranches();
		$this->data['users'] = $this->reports_model->getStaff();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('loan_activated')));
        $meta = array('page_title' => lang('loan_activated'), 'bc' => $bc);
        $this->page_construct('down_payment/loan_activated', $meta, $this->data);
	}
	public function getLoanActivated()
	{
		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
        }
        if ($this->input->get('customer')) {
            $customer = $this->input->get('customer');
        } else {
            $customer = NULL;
        }
		if ($this->input->get('product_id')) {
            $product_id = $this->input->get('product_id');
        } else {
            $product_id = NULL;
        }
        if ($this->input->get('dealer')) {
            $dealer = $this->input->get('dealer');
        } else {
            $dealer = NULL;
        }
		if ($this->input->get('gr_loan')) {
            $gr_loan = $this->input->get('gr_loan');
        } else {
            $gr_loan = NULL;
        }
		if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
        }
        if ($this->input->get('start_date')) {
            $start_date = $this->input->get('start_date');
        } else {
            $start_date = NULL;
        }
        if ($this->input->get('end_date')) {
            $end_date = $this->input->get('end_date');
        } else {
            $end_date = NULL;
        }
		
        if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date = $this->erp->fld($end_date);
        }
        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
		$approve_link = anchor('quotes/approvedApplicant/$4', '<i class="fa fa-file-text-o"></i> ' . lang('view_details'));
        $detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
        $payments_link = anchor('down_payment/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
		$payment_schedule = anchor('Installment_payment/payment_schedule/0/1/$1', '<i class="fa fa-file-text-o"></i> ' . lang('payment_schedule'), 'data-toggle="modal" data-target="#myModal"');
		$deposit = anchor('Account/add_deposit/$1', '<i class="fa fa-file-text-o"></i> ' . lang('add_deposit'), 'data-toggle="modal" data-target="#myModal"');
		$collateral_contract_identification = anchor('Installment_payment/collateral_contract_identification/$1', '<i class="fa fa-file-text-o"></i> ' . lang('collateral_contract_identification'),'target="_blank"');
		$collateral_contract_land = anchor('Installment_payment/collateral_contract_land/$1', '<i class="fa fa-file-text-o"></i> ' . lang('collateral_contract_land'),'target="_blank"');
		$guareentee_contract = anchor('Installment_payment/guareentee_contract/$1', '<i class="fa fa-file-text-o"></i> ' . lang('guareentee_contract'),'target="_blank"');		
		$collateral_contract = anchor('Installment_payment/collateral_contract/$1', '<i class="fa fa-file-text-o"></i> ' . lang('collateral_contract'),'target="_blank"');		
		$mcontract = anchor('Installment_payment/mfi_contract/$1', '<i class="fa fa-file-text-o"></i> ' . lang('loan_agreement'),'target="_blank"');
		$guarantor = anchor('Installment_payment/guarantor_contract/$1', '<i class="fa fa-file-text-o"></i> ' . lang('guarantor_contract'),'target="_blank"');
		$group_agreement = anchor('Installment_payment/group_agreement/$1/$3', '<i class="fa fa-file-text-o"></i> ' . lang('group_agreement'),'target="_blank"');
		$received = anchor('Installment_payment/Received_loans/$1', '<i class="fa fa-file-text-o"></i> ' . lang('received_loan_form'),'target="_blank"');
		//$payment_schedule_loan = anchor('Installment_Payment/export_loan/0/1/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('loan_payment_schedule'));
		$add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        //$email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('sales/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
        //$pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        //$guarantor_annex = anchor('down_payment/guarantor_annex/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('guarantor_annex'),'target="_blank"');
        //$guarantor_form = anchor('down_payment/guarantor_form/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('guarantor_form'),'target="_blank"');
        //$leasing_contract = anchor('down_payment/leasing_contract/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('leasing_contract'),'target="_blank"');
        //$pdf_link = anchor('sales/certify_latter/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('Certify_Letter'),'target="_blank"');
        //$email_link = anchor('sales/anex_contract/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('Anex_contract'),'target="_blank"');
        $return_link = anchor('sales/return_sale/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_sale'));
        $add_customer_deposit = anchor('down_payment/add_deposit/$2', '<i class="fa fa-file-text-o"></i> ' . lang('add_customer_deposit'), 'data-toggle="modal" data-target="#myModal"');
		$list_customer_deposit = anchor('customers/deposits/$2', '<i class="fa fa-file-text-o"></i> ' . lang('list_customer_deposit'), 'data-toggle="modal" data-target="#myModal"');
		
		$delete_link = "<a href='#' class='po' title='<b>" . lang("delete_contract") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_contract') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
			 <li class="ps">' . $approve_link . '</li>
            <li class="ps">' . $payment_schedule . '</li>
			<li class="de">' . $deposit . '</li>
			<!--<li class="cci">' . $collateral_contract_identification . '</li>
			<li class="ccl">' . $collateral_contract_land . '</li>
			<li class="gc">' . $guareentee_contract . '</li>-->
			<li class="cc">' . $collateral_contract . '</li>
			<li class="gr">' . $mcontract . '</li>
			<li class="mc">' . $guarantor . '</li>
			<li class="group_a">' . $group_agreement . '</li>
			<li class="mc">' . $received . '</li>
			<!--<li class="ps">' . $add_customer_deposit . '</li>
			<li class="ps">' . $list_customer_deposit . '</li>-->
			<!--<li class="dl">' . $delete_link . '</li>-->
        </ul>
		</div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';
		$setting = $this->down_payment_model->getSettingCurrncy();
        $this->load->library('datatables');
        
            $this->datatables
                ->select($this->db->dbprefix('sales').".id,".
						$this->db->dbprefix('sales').".customer_id,".
						$this->db->dbprefix('sales').".reference_no,".
						$this->db->dbprefix('sales').".quote_id as qi, ". 
						$this->db->dbprefix('loan_groups').".name AS glname,
						CONCAT(".$this->db->dbprefix('companies').".family_name,' ',".$this->db->dbprefix('companies').".name) AS customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other,' ',".$this->db->dbprefix('companies').".name_other) as customer_name_other, ".
						$this->db->dbprefix('sales').".approved_date,
						CONCAT(".$this->db->dbprefix('users').".first_name,' ',".$this->db->dbprefix('users').".last_name) as co_name ,myBranch.name,".	
						$this->db->dbprefix('sale_items').".product_name,
						CONCAT(TRUNCATE((".$this->db->dbprefix('sales').".interest_rate*100), 2),' ', '%') AS interest,
						CONCAT(TRUNCATE(".$this->db->dbprefix('sales').".term, 0), ' ', 'Days')  AS term, 
						IF(".$this->db->dbprefix('sales').".frequency = 1, 'daily', IF(".$this->db->dbprefix('sales').".frequency = 7, 'Weekly', IF(".$this->db->dbprefix('sales').".frequency = 14, 'Two Week', IF(".$this->db->dbprefix('sales').".frequency = 30, 'Monthly','')))), 						
						
						((COALESCE(".$this->db->dbprefix('sales').".total, 0))) * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate .") as total,
						".$this->db->dbprefix('sales').".grand_total * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate .") as disburse,
						((((COALESCE(".$this->db->dbprefix('sales').".total, 0))) * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate ."))- (".$this->db->dbprefix('sales').".grand_total * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate ."))) as remaining,".
							
						$this->db->dbprefix('currencies').".name AS crname, ".
						$this->db->dbprefix('sales').".sale_status, ".						
						$this->db->dbprefix('sales').".mfi AS mfi, ".
						$this->db->dbprefix('sales').".loan_group_id AS loan_g_id, ".
						$this->db->dbprefix('companies').".id AS com_id")
                ->from('sales')
				->join('loans','sales.id=loans.sale_id','INNER')
				->join('sale_items', 'sales.id = sale_items.sale_id', 'INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('products', 'sale_items.product_id = products.id', 'INNER')
				->join('sale_services', 'sales.id = sale_services.sale_id', 'left')
				->join('quotes','quotes.id = sales.quote_id','left')
				->join('quote_items','quote_items.quote_id = quotes.id','left')
				->join('users','sales.by_co = users.id','INNER')
				->join('companies AS myBranch', 'sales.branch_id= myBranch.id', 'left')
				->join('currencies','currencies.code = sale_items.currency_code','left')
				->join('loan_groups','loan_groups.id = sales.loan_group_id','left')
				->where($this->db->dbprefix('sales').'.sale_status =', 'activated')
				//->where('loans.paid_amount', 0)
				->group_by('sales.id')
				->order_by('sales.id','DESC');
				//(SELECT SUM(amount) FROM ".$this->db->dbprefix('sale_services')." WHERE sale_id = ".$this->db->dbprefix('sales').".id) as total_service_charge,
        
		
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->datatables->where('sales.branch_id', $this->session->branch_id);
		}
		if ($product_id) {
			$this->datatables->join('sale_items as si', 'si.sale_id = sales.id', 'left');
			$this->datatables->where('si.product_id', $product_id);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            //$this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		if ($user_query) {
			$this->datatables->where('sales.by_co', $user_query);
		}
		if ($reference_no) {
			$this->datatables->like('sales.reference_no', $reference_no);
		}
		if ($dealer) {
			$this->datatables->where('sales.biller_id', $dealer);
		}
		if ($gr_loan) {
			$this->datatables->where('sales.loan_group_id', $gr_loan);
		}
		if ($customer) {
			$this->datatables->where('sales.customer_id', $customer);
		}
		if ($warehouse) {
			$this->datatables->where('sales.warehouse_id', $warehouse);
		}
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
        $this->datatables->add_column("Actions", $action,$this->db->dbprefix('sales').".id, com_id, loan_g_id, qi");
        $this->datatables->unset_column("com_id");
		$this->datatables->unset_column("loan_g_id");
		$this->datatables->unset_column("qi");
		echo $this->datatables->generate();
    }
	
	function loans_completed()
	{
		$this->erp->checkPermissions();
		$this->load->model('reports_model');
		$this->erp->load->model('reports_model');
		$this->data['products'] = $this->site->getProducts();
		$this->data['group_Loan'] = $this->site->getLoanGroups();
		$this->data['dealer'] = $this->site->getAllDealer('supplier');
		if(isset($_GET['d']) != ""){
			$date = $_GET['d'];
			$this->data['date'] = $date;
		}
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
		$this->data['customers'] = $this->site->getCustomerIDName();
		$this->data['branches'] = $this->site->getAllBranches();
		$this->data['users'] = $this->reports_model->getStaff();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('loans_pay_off')));
        $meta = array('page_title' => lang('loans_pay_off'), 'bc' => $bc);
        $this->page_construct('down_payment/loans_completed', $meta, $this->data);
	}
	public function getLoansCompleted()
	{
		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
        }
        if ($this->input->get('customer')) {
            $customer = $this->input->get('customer');
        } else {
            $customer = NULL;
        }
		if ($this->input->get('product_id')) {
            $product_id = $this->input->get('product_id');
        } else {
            $product_id = NULL;
        }
        if ($this->input->get('dealer')) {
            $dealer = $this->input->get('dealer');
        } else {
            $dealer = NULL;
        }
		if ($this->input->get('gr_loan')) {
            $gr_loan = $this->input->get('gr_loan');
        } else {
            $gr_loan = NULL;
        }
		if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
        }
        if ($this->input->get('start_date')) {
            $start_date = $this->input->get('start_date');
        } else {
            $start_date = NULL;
        }
        if ($this->input->get('end_date')) {
            $end_date = $this->input->get('end_date');
        } else {
            $end_date = NULL;
        }
		
        if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date = $this->erp->fld($end_date);
        }
        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
		$approve_link = anchor('quotes/approvedApplicant/$4', '<i class="fa fa-file-text-o"></i> ' . lang('view_details'));
        $detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
        $payments_link = anchor('down_payment/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
		$payment_schedule = anchor('Installment_payment/payment_schedule/0/1/$1', '<i class="fa fa-file-text-o"></i> ' . lang('payment_schedule'), 'data-toggle="modal" data-target="#myModal"');
		$deposit = anchor('Account/add_deposit/$1', '<i class="fa fa-file-text-o"></i> ' . lang('add_deposit'), 'data-toggle="modal" data-target="#myModal"');
		$collateral_contract_identification = anchor('Installment_payment/collateral_contract_identification/$1', '<i class="fa fa-file-text-o"></i> ' . lang('collateral_contract_identification'),'target="_blank"');
		$collateral_contract_land = anchor('Installment_payment/collateral_contract_land/$1', '<i class="fa fa-file-text-o"></i> ' . lang('collateral_contract_land'),'target="_blank"');
		$guareentee_contract = anchor('Installment_payment/guareentee_contract/$1', '<i class="fa fa-file-text-o"></i> ' . lang('guareentee_contract'),'target="_blank"');		
		$collateral_contract = anchor('Installment_payment/collateral_contract/$1', '<i class="fa fa-file-text-o"></i> ' . lang('collateral_contract'),'target="_blank"');		
		$mcontract = anchor('Installment_payment/mfi_contract/$1', '<i class="fa fa-file-text-o"></i> ' . lang('loan_agreement'),'target="_blank"');
		$guarantor = anchor('Installment_payment/guarantor_contract/$1', '<i class="fa fa-file-text-o"></i> ' . lang('guarantor_contract'),'target="_blank"');
		$group_agreement = anchor('Installment_payment/group_agreement/$1/$3', '<i class="fa fa-file-text-o"></i> ' . lang('group_agreement'),'target="_blank"');
		$received = anchor('Installment_payment/Received_loans/$1', '<i class="fa fa-file-text-o"></i> ' . lang('received_loan_form'),'target="_blank"');
		//$payment_schedule_loan = anchor('Installment_Payment/export_loan/0/1/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('loan_payment_schedule'));
		$add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        //$email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('sales/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
       $return_link = anchor('sales/return_sale/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_sale'));
        $add_customer_deposit = anchor('down_payment/add_deposit/$2', '<i class="fa fa-file-text-o"></i> ' . lang('add_customer_deposit'), 'data-toggle="modal" data-target="#myModal"');
		$list_customer_deposit = anchor('customers/deposits/$2', '<i class="fa fa-file-text-o"></i> ' . lang('list_customer_deposit'), 'data-toggle="modal" data-target="#myModal"');
		
		$delete_link = "<a href='#' class='po' title='<b>" . lang("delete_contract") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_contract') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
			 <li class="ps">' . $approve_link . '</li>
            <li class="ps">' . $payment_schedule . '</li>
			<!--<li class="cci">' . $collateral_contract_identification . '</li>
			<li class="ccl">' . $collateral_contract_land . '</li>
			<li class="gc">' . $guareentee_contract . '</li>-->
			<li class="cc">' . $collateral_contract . '</li>
			<li class="gr">' . $mcontract . '</li>
			<li class="mc">' . $guarantor . '</li>
			<li class="group_a">' . $group_agreement . '</li>
			<li class="mc">' . $received . '</li>
			<!--<li class="dl">' . $delete_link . '</li>-->
        </ul>
		</div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';
		$setting = $this->down_payment_model->getSettingCurrncy();
        $this->load->library('datatables');
        
            $this->datatables
                ->select($this->db->dbprefix('sales').".id,".
						$this->db->dbprefix('sales').".customer_id,".
						$this->db->dbprefix('sales').".reference_no,".
						$this->db->dbprefix('sales').".quote_id as qi, ". 
						$this->db->dbprefix('loan_groups').".name AS glname,
						CONCAT(".$this->db->dbprefix('companies').".family_name,' ',".$this->db->dbprefix('companies').".name) AS customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other,' ',".$this->db->dbprefix('companies').".name_other) as customer_name_other, ".
						$this->db->dbprefix('sales').".approved_date,
						CONCAT(".$this->db->dbprefix('users').".first_name,' ',".$this->db->dbprefix('users').".last_name) as co_name ,myBranch.name,".	
						$this->db->dbprefix('sale_items').".product_name,
						CONCAT(TRUNCATE((".$this->db->dbprefix('sales').".interest_rate*100), 2),' ', '%') AS interest,
						CONCAT(TRUNCATE(".$this->db->dbprefix('sales').".term, 0), ' ', 'Days')  AS term, 
						IF(".$this->db->dbprefix('sales').".frequency = 1, 'daily', IF(".$this->db->dbprefix('sales').".frequency = 7, 'Weekly', IF(".$this->db->dbprefix('sales').".frequency = 14, 'Two Week', IF(".$this->db->dbprefix('sales').".frequency = 30, 'Monthly','')))), 						
						
						((COALESCE(".$this->db->dbprefix('sales').".total, 0))) * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate .") as total,
						".$this->db->dbprefix('sales').".grand_total * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate .") as disburse,
						((((COALESCE(".$this->db->dbprefix('sales').".total, 0))) * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate ."))- (".$this->db->dbprefix('sales').".grand_total * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate ."))) as remaining,".
							
						$this->db->dbprefix('currencies').".name AS crname, ".
						$this->db->dbprefix('sales').".sale_status, ".						
						$this->db->dbprefix('sales').".mfi AS mfi, ".
						$this->db->dbprefix('sales').".loan_group_id AS loan_g_id, ".
						$this->db->dbprefix('companies').".id AS com_id")
                ->from('sales')
				->join('loans','sales.id=loans.sale_id','INNER')
				->join('sale_items', 'sales.id = sale_items.sale_id', 'INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('products', 'sale_items.product_id = products.id', 'INNER')
				->join('sale_services', 'sales.id = sale_services.sale_id', 'left')
				->join('quotes','quotes.id = sales.quote_id','left')
				->join('quote_items','quote_items.quote_id = quotes.id','left')
				->join('users','sales.by_co = users.id','INNER')
				->join('companies AS myBranch', 'sales.branch_id= myBranch.id', 'left')
				->join('currencies','currencies.code = sale_items.currency_code','left')
				->join('loan_groups','loan_groups.id = sales.loan_group_id','left')
				->where($this->db->dbprefix('sales').'.sale_status =', 'completed')
				->group_by('sales.id')
				->order_by('sales.id','DESC');
				//(SELECT SUM(amount) FROM ".$this->db->dbprefix('sale_services')." WHERE sale_id = ".$this->db->dbprefix('sales').".id) as total_service_charge,
        
		
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->datatables->where('sales.branch_id', $this->session->branch_id);
		}
		if ($product_id) {
			$this->datatables->join('sale_items as si', 'si.sale_id = sales.id', 'left');
			$this->datatables->where('si.product_id', $product_id);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            //$this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		if ($user_query) {
			$this->datatables->where('sales.by_co', $user_query);
		}
		if ($reference_no) {
			$this->datatables->like('sales.reference_no', $reference_no);
		}
		if ($dealer) {
			$this->datatables->where('sales.biller_id', $dealer);
		}
		if ($gr_loan) {
			$this->datatables->where('sales.loan_group_id', $gr_loan);
		}
		if ($customer) {
			$this->datatables->where('sales.customer_id', $customer);
		}
		if ($warehouse) {
			$this->datatables->where('sales.warehouse_id', $warehouse);
		}
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
        $this->datatables->add_column("Actions", $action,$this->db->dbprefix('sales').".id, com_id, loan_g_id, qi");
        $this->datatables->unset_column("com_id");
		$this->datatables->unset_column("loan_g_id");
		$this->datatables->unset_column("qi");
		echo $this->datatables->generate();
    }
	public function loans_completed_paid(){
		
		$this->erp->checkPermissions('contract',false,'reports');
		$this->erp->load->model('down_payment_model');
		$this->erp->load->model('reports_model');
		$this->data['users'] = $this->reports_model->getStaff();
		$this->data['products'] = $this->site->getProducts();
		$this->data['group_Loan'] = $this->site->getLoanGroups();
		$this->data['dealer'] = $this->site->getAllDealer('supplier');
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');        
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('loans_completed')));
        $meta = array('page_title' => lang('loans_completed'), 'bc' => $bc);
        $this->page_construct('down_payment/loans_completed_paid', $meta, $this->data);
	}
	
	public function getLoansCompleted_paid()
    {
		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
        }
        if ($this->input->get('customer')) {
            $customer = $this->input->get('customer');
        } else {
            $customer = NULL;
        }
		
        if ($this->input->get('dealer')) {
            $dealer = $this->input->get('dealer');
        } else {
            $dealer = NULL;
        }
		if ($this->input->get('gr_loan')) {
            $gr_loan = $this->input->get('gr_loan');
        } else {
            $gr_loan = NULL;
        }
		
        if ($this->input->get('start_date')) {
            $start_date = $this->input->get('start_date');
        } else {
            $start_date = NULL;
        }
        if ($this->input->get('end_date')) {
            $end_date = $this->input->get('end_date');
        } else {
            $end_date = NULL;
        }
        if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date = $this->erp->fld($end_date);
        }
        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        $detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
        $payment_schedule = anchor('Installment_payment/payment_schedule/0/1/$1', '<i class="fa fa-file-text-o"></i> ' . lang('payment_schedule'), 'data-toggle="modal" data-target="#myModal"');
		$list_customer_deposit = anchor('customers/deposits/$2', '<i class="fa fa-file-text-o"></i> ' . lang('list_customer_deposit'), 'data-toggle="modal" data-target="#myModal"');
		
		$delete_link = "<a href='#' class='po' title='<b>" . lang("delete_contract") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_contract') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li class="ps">' . $payment_schedule . '</li>
		</div></div>';
		
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';
		
        $this->load->library('datatables');
		$setting = $this->down_payment_model->getSettingCurrncy();
		$paid_date = $this->reports_model->getPaidDate();
			$loan = "( 	SELECT
								erp_loans.sale_id,
								GROUP_CONCAT(IF(erp_loans.reference_no <> '' AND erp_loans.paid_amount > 0, 1, 0)) AS con_paid
							FROM
								erp_loans 
							GROUP BY
								erp_loans.sale_id
						) as erp_tmploan ";
            $this->datatables
                ->select($this->db->dbprefix('sales').".id,".
						$this->db->dbprefix('sales').".reference_no,".
						$this->db->dbprefix('loan_groups').".name AS glname,
						CONCAT(".$this->db->dbprefix('companies').".family_name,' ',".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other,' ',".$this->db->dbprefix('companies').".name_other) as customer_name_other, ".						
						$this->db->dbprefix('users').".username,myBranch.name,".		
						$this->db->dbprefix('sale_items').".product_name,
						CONCAT(TRUNCATE((".$this->db->dbprefix('sales').".interest_rate*100), 2), '', '%') as interest, 						
						CONCAT(TRUNCATE(".$this->db->dbprefix('sales').".term, 0), ' ', 'Days') as term, 
						IF(".$this->db->dbprefix('sales').".frequency = 1, 'Daily',IF(".$this->db->dbprefix('sales').".frequency = 7, 'Weekly', IF(".$this->db->dbprefix('sales').".frequency = 14, 'Two Week', IF(".$this->db->dbprefix('sales').".frequency = 30, 'Monthly','')))), 						
						(SELECT amount FROM ".$this->db->dbprefix('payments')." WHERE sale_id = ".$this->db->dbprefix('sales').".id AND date='".$paid_date->p_date ."' GROUP BY sale_id)AS total,".
						$this->db->dbprefix('currencies').".name as cname,".
						$this->db->dbprefix('sales').".mfi as mfi,".
						$this->db->dbprefix('companies').".id as com_id")
                ->from('sales')
				->join('users','sales.by_co = users.id','INNER')
				->join('sale_items', 'sales.id = sale_items.sale_id', 'INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('companies as myBranch', 'sales.branch_id= myBranch.id','LEFT')
				->join('sale_services', 'sales.id = sale_services.sale_id', 'left')
				->join('currencies','currencies.code = sale_items.currency_code','left')
				->join('loan_groups','loan_groups.id = sales.loan_group_id','left')
				->join($loan, 'sales.id= tmploan.sale_id', 'INNER')
				->where('erp_sales.payment_status','paid')
				->where('erp_tmploan.con_paid not like', '%0%')				
				->group_by('sales.id')
				->order_by('sales.id','DESC');
				
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->datatables->where('sales.branch_id', $this->session->branch_id);
		}
		if ($product_id) {
			$this->datatables->join('sale_items as si', 'si.sale_id = sales.id', 'left');
			$this->datatables->where('si.product_id', $product_id);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            //$this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			$this->datatables->where('sales.created_by', $this->session->userdata('user_id'));
		}
		if ($user_query) {
			$this->datatables->where('sales.created_by', $user_query);
		}
		if ($reference_no) {
			$this->datatables->like('sales.reference_no', $reference_no);
		}
		if ($dealer) {
			$this->datatables->where('sales.biller_id', $dealer);
		}
		if ($gr_loan) {
			$this->datatables->where('sales.loan_group_id', $gr_loan);
		}
		if ($customer) {
			$this->datatables->where('sales.customer_id', $customer);
		}
		
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
        $this->datatables->add_column("Actions", $action,$this->db->dbprefix('sales').".id, com_id");
        $this->datatables->unset_column("com_id");
		echo $this->datatables->generate();
    }
	
	function add_deposit($company_id = NULL)
    {
        $this->erp->checkPermissions('deposits', true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }
        $company = $this->companies_model->getCompanyByID($company_id);

        if ($this->Owner || $this->Admin) {
            $this->form_validation->set_rules('date', lang("date"), 'required');
        }
        $this->form_validation->set_rules('amount', lang("amount"), 'required|numeric');
        
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $data = array(
                'date' => $date,
                'amount' => $this->input->post('amount'),
                'paid_by' => $this->input->post('paid_by'),
                'note' => $this->input->post('note') ? $this->input->post('note') : $company->name,
                'company_id' => $company->id,
                'created_by' => $this->session->userdata('user_id'),
				'biller_id' => $this->input->post('biller')
            );
			$payment = array(
				'date' => $date,
				'sale_id' => $sale_id,
				'reference_no' => $this->site->getReference('sp'),
				'amount' => $this->input->post('amount'),
				'paid_by' => $this->input->post('paid_by'),
				'cheque_no' => $this->input->post('cheque_no'),
				'cc_no' => $this->input->post('paid_by') == 'gift_card' ? $this->input->post('gift_card_no') : $this->input->post('pcc_no'),
				'cc_holder' => $this->input->post('pcc_holder'),
				'cc_month' => $this->input->post('pcc_month'),
				'cc_year' => $this->input->post('pcc_year'),
				'cc_type' => $this->input->post('pcc_type'),
				'note' => $this->input->post('note') ? $this->input->post('note') : $company->name,
				'created_by' => $company->id,
				'type' => 'received',
				'biller_id'	=> $this->input->post('biller')
			);
            $cdata = array(
                'deposit_amount' => ($company->deposit_amount+$this->input->post('amount'))
            );
        } elseif ($this->input->post('add_deposit')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('down_payment/contract_list');
        }

        if ($this->form_validation->run() == true && $this->companies_model->addDeposit($data, $cdata, $payment)) {
            $this->session->set_flashdata('message', lang("deposit_added"));
            redirect("down_payment/contract_list");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['company'] = $company;
            $this->load->view($this->theme . 'down_payment/add_deposit', $this->data);
        }
    }
	
	
	public function getTransferContracts($warehouse_id = null)
    {

		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
        }
        if ($this->input->get('customer')) {
            $customer = $this->input->get('customer');
        } else {
            $customer = NULL;
        }
		if ($this->input->get('product_id')) {
            $product_id = $this->input->get('product_id');
        } else {
            $product_id = NULL;
        }
        if ($this->input->get('biller')) {
            $biller = $this->input->get('biller');
        } else {
            $biller = NULL;
        }
		if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
        }
        if ($this->input->get('start_date')) {
            $start_date = $this->input->get('start_date');
        } else {
            $start_date = NULL;
        }
        if ($this->input->get('end_date')) {
            $end_date = $this->input->get('end_date');
        } else {
            $end_date = NULL;
        }
		
        if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date = $this->erp->fld($end_date);
        }

		
        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
		$transfer_btn = anchor('down_payment/transfer_contract/$1', '<i class="fa fa-exchange"></i> ' . lang('transfer'), 'class="btn btn-info"');
        $action = $transfer_btn;
		

        $this->load->library('datatables');
        
            $this->datatables
                ->select($this->db->dbprefix('sales').".id,".
						$this->db->dbprefix('sales').".reference_no,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other, ".
						//$this->db->dbprefix('sales').".biller, ".
						$this->db->dbprefix('users').".username, myBranch.name as branchName,".		
						$this->db->dbprefix('sale_items').".product_name, ".
						$this->db->dbprefix('sale_items').".product_year, ".
						$this->db->dbprefix('variants').".name, ".
						$this->db->dbprefix('sales').".total,
						CONCAT((".$this->db->dbprefix('sales').".advance_percentage_payment * 100), '%') as percentage,".
						$this->db->dbprefix('sales').".advance_payment,
						SUM(".$this->db->dbprefix('sale_services').".amount) as total_service_charge,".
						$this->db->dbprefix('sales').".second_payment,
						((COALESCE(".$this->db->dbprefix('sales').".grand_total, 0) - COALESCE(".$this->db->dbprefix('sales').".advance_payment, 0))) as total_amount,".
						$this->db->dbprefix('sales').".sale_status")
                ->from('sales')
				->join('users','sales.by_co=users.id','INNER')
				->join('sale_items', 'sales.id = sale_items.sale_id', 'INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('companies as myBranch', 'users.branch_id = myBranch.id')
				->join('products', 'sale_items.product_id = products.id', 'INNER')
				->join('sale_services', 'sales.id = sale_services.sale_id', 'left')
				->join('variants', 'variants.id = sale_items.color', 'left')
				->where($this->db->dbprefix('sales').'.sale_status =', 'activated')
				->group_by('sales.id');
        
		
		if ($product_id) {
			$this->datatables->join('sale_items', 'sale_items.sale_id = sales.id', 'left');
			$this->datatables->where('sale_items.product_code', $product_id);
		}
		
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			$this->datatables->where('sales.created_by', $this->session->userdata('user_id'));
		}
		
		if ($user_query) {
			$this->datatables->where('sales.by_co', $user_query);
		}

		if ($reference_no) {
			$this->datatables->where('sales.reference_no', $reference_no);
		}
		if ($biller) {
			$this->datatables->where('sales.biller_id', $biller);
		}
		if ($customer) {
			$this->datatables->where('sales.customer_id', $customer);
		}
		
		if ($warehouse) {
			$this->datatables->where('sales.warehouse_id', $warehouse);
		}

		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
		
        $this->datatables->add_column("Actions", $action,$this->db->dbprefix('sales').".id");
        echo $this->datatables->generate();
    }
	public function guarantor_annex($id=NULL){
		$this->erp->checkPermissions();
		
        $this->load->view($this->theme.'down_payment/guarantor_annex',$this->data);
	}
	public function guarantor_form($id=NULL){
		$this->erp->checkPermissions();
		
        $this->load->view($this->theme.'down_payment/guarantor_form',$this->data);
	}
	public function leasing_contract($id=NULL){
		$this->erp->checkPermissions();

        $this->load->view($this->theme.'down_payment/leasing_contract',$this->data);
	}
	public function contract_link($id){
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$quote = $this->quotes_model->getQuoteByID($id);
		
		$this->data['inv'] = $quote;
		$inv_items = $this->quotes_model->getAllQuoteItems($id);
		$c = rand(100000, 9999999);
		foreach ($inv_items as $item) {
			$row = $this->site->getProductByID($item->product_id);
			if (!$row) {
				$row = json_decode('{}');
				$row->tax_method = 0;
			} else {
				unset($row->details, $row->product_details, $row->cost, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
			}
			$row->quantity = 0;
			$pis = $this->quotes_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
			if ($pis) {
				foreach ($pis as $pi) {
					$row->quantity += $pi->quantity_balance;
				}
			}
			$row->id = $item->product_id;
			$row->code = $item->product_code;
			$row->name = $item->product_name;
			$row->type = $item->product_type;
			$row->qty = $item->quantity;
			$row->discount = $item->discount ? $item->discount : '0';
			$row->price = $this->erp->formatDecimal($item->net_unit_price + $this->erp->formatDecimal($item->item_discount / $item->quantity));
			$row->unit_price = $row->tax_method ? $item->unit_price + $this->erp->formatDecimal($item->item_discount / $item->quantity) + $this->erp->formatDecimal($item->item_tax / $item->quantity) : $item->unit_price + ($item->item_discount / $item->quantity);
			$row->real_unit_price = $item->real_unit_price;
			$row->tax_rate = $item->tax_rate_id;
			$row->option = $item->option_id;
			$options = $this->quotes_model->getProductOptions($row->id, $item->warehouse_id);

			if ($options) {
				$option_quantity = 0;
				foreach ($options as $option) {
					$pis = $this->quotes_model->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
					if ($pis) {
						foreach ($pis as $pi) {
							$option_quantity += $pi->quantity_balance;
						}
					}
					if ($option->quantity > $option_quantity) {
						$option->quantity = $option_quantity;
					}
				}
			}

			$combo_items = false;
			if ($row->type == 'combo') {
				$combo_items = $this->quotes_model->getProductComboItems($row->id, $item->warehouse_id);
				$te = $combo_items;
				foreach ($combo_items as $combo_item) {
					$combo_item->quantity = $combo_item->qty * $item->quantity;
				}
			}
			$ri = $this->Settings->item_addition ? $row->id : $c;
			if ($row->tax_rate) {
				$tax_rate = $this->site->getTaxRateByID($row->tax_rate);
				$pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options);
			} else {
				$pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options);
			}
			$c++;
		}
		$this->data['inv_items'] = json_encode($pr);
		$this->data['id'] = $id;
		//$this->data['currencies'] = $this->site->getAllCurrencies();
		$this->data['billers'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllCompanies('supplier') : null;
		$this->data['tax_rates'] = $this->site->getAllTaxRates();
		$this->data['warehouses'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllWarehouses() : null;
		$this->data['services'] = $this->site->getServicesByStatus('1');
		
		$this->data['sale'] = $this->quotes_model->getSaleByQuoteID($id);
		$this->data['quote_employee'] = $this->quotes_model->getEmployeeQuoteByQuoteID($id);
		$this->data['guarantor'] = $this->quotes_model->getGuarantorByQuoteID($id);
		$this->data['quote_service'] = $this->site->getQuoteServicesByQuoteID($id);
		
		/* Document */
		$this->data['documents'] = $this->quotes_model->getDocumentsByQuoteID($id);
		
		$this->data['finacal_products'] = $this->site->getAllCustomerGroup();
		$this->data['advance_percentages'] = $this->site->getAllDownPercentage();
		$this->data['interest_rates'] = $this->site->getAllInterestRate();
		$this->data['term_in_months'] = $this->site->getAllTerm();
		
		$this->data['product'] = $this->quotes_model->getProductByQuoteID($id);
		$this->data['categories'] = $this->quotes_model->getCategories();
		$this->data['variants'] = $this->site->getVariants();
		$this->data['applicant'] = $this->site->getCompanyByID($quote->customer_id);
		$this->data['field_check'] = $this->site->getFieldCheckByQuoteID($id);
		$this->data['countries'] = $this->site->getCountries();
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('contract'), 'page' => lang('contract')), array('link' => '#', 'page' => lang('contract_detail')));
		$meta = array('page_title' => lang('contract_detail'), 'bc' => $bc);
		$this->page_construct('down_payment/contract_link', $meta, $this->data);
	}
	
	/* Transfer Contract */
	public function transfer_contract($id){
       $this->erp->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $inv = $this->quotes_model->getQuoteByID($id);
		
		/*
        if (!$this->session->userdata('edit_right')) {
            $this->erp->view_rights($inv->created_by);
        }
		*/
        //$this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        //$this->form_validation->set_rules('reference_no', $this->lang->line("reference_no"), 'required');
        $this->form_validation->set_rules('cus_first_name', $this->lang->line("custocus_first_namemer"), 'required');
        //$this->form_validation->set_rules('note', $this->lang->line("note"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
			
			$dealer_code = $this->input->post('biller');
			
            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('qu', $dealer_code);
            if ($this->Owner || $this->Admin) {
                $date = date('Y-m-d H:i:s');
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
			$customer_group = $this->input->post('financial_product');
			$frequency = $this->input->post('frequency');
            $biller_id = $this->input->post('biller');
            $status = $this->input->post('status');
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
			if(is_array($customer_details)){
            $customer = $customer_details->family_name . ' ' .$customer_details->name;
			}
            $biller_details = $this->site->getCompanyByID($biller_id);
            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $note = $this->erp->clear_tags($this->input->post('note'));
            $q_service  = $this->input->post('ch_services');
			$services_amount  = $this->input->post('service');
			
			$rate_type = $this->input->post('rate_type');
			$interest_rate = $this->input->post('interest_rate');
			$term = $this->input->post('term_in_month');
			$advance_percentage_payment = $this->input->post('advance_percentage');
			$advance_payment = $this->input->post('advance_payment');
			$status = $this->input->post('status');

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';

            # get services
			$QouteServices = '';
            for($sv = 0; $sv < count($q_service); $sv ++) {
                $QouteServices[] = array(
											'services_id' => $q_service[$sv],
											'amount' => $services_amount[$sv],
										);
            }

           // $i = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;
			
            if($_POST['product_id']){
                $item_id = $_POST['product_id'];
				
				$product = $this->site->getProductByID($item_id);
				
                $item_type = $product->type;
                $item_code = $product->code;
                $item_name = $product->name;
                
                $real_unit_price = $this->erp->formatDecimal($_POST['price']);
				
                $unit_price = $this->erp->formatDecimal($_POST['price']);
                $item_quantity = 1;

                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->quotes_model->getProductByCode($item_code) : null;
                    $unit_price = $real_unit_price;

                    $unit_price = $this->erp->formatDecimal($unit_price);
                    $item_net_price = $unit_price;

                    $subtotal = ($unit_price * $item_quantity);

                    $products[] = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'net_unit_price' => $unit_price,
                        'unit_price' => $this->erp->formatDecimal($unit_price),
                        'quantity' => $item_quantity,
                        'subtotal' => $this->erp->formatDecimal($subtotal),
                        'real_unit_price' => $real_unit_price,
						'color' => $this->input->post('color'),
						'product_year' => $this->input->post('year'),
						'engine' => $this->input->post('engine'),
						'frame' => $this->input->post('frame'),
						'power' => $this->input->post('power'),
						'distance_mile' => $this->input->post('distance')
                    );

                    $total += $item_net_price * $item_quantity;
                }
            }
			/*
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }
			*/
            if ($this->input->post('discount')) {
                $order_discount_id = $this->input->post('discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = (($total + $product_tax) * (Float) ($ods[0])) / 100;

                } else {
                    $order_discount = $order_discount_id;
                }
            } else {
                $order_discount_id = null;
            }
            $total_discount = $order_discount + $product_discount;

            if ($this->Settings->tax2 != 0) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $order_tax_details->rate;
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = (($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100;
                    }
                }
            } else {
                $order_tax_id = null;
            }
			
			$cg = $this->site->getCustomerGroupByID($customer_group);
            $customers = array('group_id'            => '3',
                            'group_name'             => 'customer',
                            'customer_group_id'      => $customer_group,
                            'customer_group_name'    => isset($cg->name) ?$cg->name  : (''),
                            'civility'               => $this->input->post('cus_civility'),
                            'gov_id'                 => $this->input->post('cus_gov_id'),
                            'name'                   => $this->input->post('cus_first_name'),
                            'name_other'             => $this->input->post('cus_first_name_other'),
                            'family_name'            => $this->input->post('cus_family_name'),
                            'family_name_other'      => $this->input->post('cus_family_name_other'),
                            'nickname'               => $this->input->post('cus_nick_name'),
                            'spouse_name'            => $this->input->post('cus_sp_fname'),
                            'spouse_family_name'     => $this->input->post('cus_sp_fam_name'),
                            'gender'                 => $this->input->post('cus_gender'),
                            'status'                 => $this->input->post('cus_marital_status'),
                            'date_of_birth'          => $this->erp->fld(trim($this->input->post('cus_dob'))),
                            'age'                    => $this->input->post('cus_age'),
                            'black_list'             => $this->input->post('cus_black_list'),
                            'whose_income'           => $this->input->post('cus_whose_income'),
                            'income_combination'     => $this->input->post('cus_inc_comb'),
                            'state'                  => $this->input->post('cus_state'),
                            'address'                => $this->input->post('cus_pob'),
                            'phone1'                 => $this->input->post('cus_phone_1'),
                            'phone2'                 => $this->input->post('cus_phone_2'),
                            'spouse_phone'           => $this->input->post('cus_sp_phone'),
                            'house_no'               => $this->input->post('cus_house_no'),
                            'months'                 => $this->input->post('cus_months'),
                            'years'                  => $this->input->post('cus_years'),
                            'housing'                => $this->input->post('cus_housing'),
                            'street'                 => $this->input->post('cus_street'),
                            'village'                => $this->input->post('cus_village'),
                            'district'               => $this->input->post('cus_district'),
                            'sangkat'                => $this->input->post('cus_communce'),
                            'state'                  => $this->input->post('cus_province'),
                            'country'                => $this->input->post('cus_country'),
                            'nationality'            => $this->input->post('cus_nationality'),
                            'num_of_child'           => $this->input->post('cus_num_of_child'),
                        );
						
						
			//$this->erp->print_arrays($customers);
            $total_tax = $product_tax + $order_tax;
            $grand_total = $this->erp->formatDecimal($total + $total_tax + $shipping - $order_discount);
            $data = array(
                'reference_no'              => $reference,
                'customer_id'               => $customer_id,
                'customer'                  => isset($customer) ?$customer  : (''),
                'biller_id'                 => $biller_id,
                'biller'                    => $biller,
                'note'                      => $note,
                'total'                     => $total,
                'product_discount'          => $product_discount,
                'order_discount_id'         => $order_discount_id,
                'order_discount'            => $order_discount,
                'total_discount'            => $total_discount,
                'product_tax'               => $product_tax,
                'order_tax_id'              => $order_tax_id,
                'order_tax'                 => $order_tax,
                'total_tax'                 => $total_tax,
                'shipping'                  => $shipping,
                'grand_total'               => $grand_total,
				'status'                    => $status,
				'advance_percentage_payment' => $advance_percentage_payment,
				'advance_payment'           => $advance_payment,
				'frequency'                 => $frequency,
				'rate_type'					=> $rate_type,
				'interest_rate'             => $interest_rate,
				'term'                      => $term
            );
			
			#data of employee
			$employee_ = '';
			if($this->input->post('position') || $this->input->post('work_place_name') || $this->input->post('basic_salary') || $this->input->post('emp_province')) {
				$employee_ = array(
					'position'                  => $this->input->post('position'),
					'emp_status'                => $this->input->post('employment_status'),
					'emp_industry'              => $this->input->post('employment_industry'),
					'senior_level'              => $this->input->post('seniorities_level'),
					'workplace_name'            => $this->input->post('work_place_name'),
					'work_phone'                => $this->input->post('work_phone'),
					'allow_call_to_work_place'  => $this->input->post('allow_call_to_work_place'),
					'years'                     => $this->input->post('emp_years'),
					'months'                    => $this->input->post('emp_months'),
					'basic_salary'              => $this->input->post('basic_salary'),
					'allowance_etc'             => $this->input->post('allowance_etc'),
					'business_expense'          => $this->input->post('business_expense'),
					'house_no'                  => $this->input->post('emp_house_no'),
					'street'                    => $this->input->post('emp_street'),
					'country'                   => $this->input->post('emp_country'),
					'province'                 => $this->input->post('emp_province'),
					'district'                  => $this->input->post('emp_district'),
					'communce'                  => $this->input->post('emp_communce'),
					'village'                   => $this->input->post('emp_village'),
				);
			}
			//$this->erp->print_arrays($employee_);

            #data of guarantor for loaner
			$guarantor_ = '';
			if($this->input->post('gov_id') || $this->input->post('first_name') || $this->input->post('family_name') || $this->input->post('dob') || $this->input->post('province')) {
				$guarantor_ = array(
					'civility'                  => $this->input->post('civility'),
					'gov_id'                    => $this->input->post('gov_id'),
					'name'                      => $this->input->post('first_name'),
					'name_other'                => $this->input->post('first_name_other'),
					'family_name'               => $this->input->post('family_name'),
					'family_name_other'         => $this->input->post('family_name_other'),
					'nickname'                  => $this->input->post('nick_name'),
					'spouse_family_name'        => $this->input->post('sp_fam_name'),
					'gender'                    => $this->input->post('gender'),
					'status'                    => $this->input->post('marital_status'),
					'date_of_birth'             => $this->erp->fld(trim($this->input->post('dob'))),
					'state'                     => $this->input->post('province'),
					'address'                   => $this->input->post('pob'),
					'phone1'                    => $this->input->post('phone_1'),
					'phone2'                    => $this->input->post('phone_2'),
					'spouse_phone'              => $this->input->post('sp_phone'),
					'house_no'                  => $this->input->post('house_no'),
					'months'                    => $this->input->post('months'),
					'years'                     => $this->input->post('years'),
					'housing'                   => $this->input->post('housing'),
					'street'                    => $this->input->post('street'),
					'village'                   => $this->input->post('village'),
					'district'                  => $this->input->post('district'),
					'sangkat'                   => $this->input->post('communce'),
					'country'                   => $this->input->post('country'),
					'nationality'               => $this->input->post('nationality'),
					'num_of_child'              => $this->input->post('num_of_child'),
				);
			}
			
            //$this->erp->print_arrays($guarantor_);
			$field_check = '';
			if($this->input->post('fc_id_card') || $this->input->post('fc_electricity_invoice') || $this->input->post('fc_check_landlord') || $this->input->post('fc_family_book') || $this->input->post('fc_water_invoice') || $this->input->post('fc_staying_book') || $this->input->post('fc_check_property') || $this->input->post('fc_other') || $this->input->post('fc_other_textbox') || $this->input->post('fc_current_address') || $this->input->post('fc_phone_number') || $this->input->post('fc_business1') || $this->input->post('fc_business2') || $this->input->post('fc_business3') || $this->input->post('fc_business4') || $this->input->post('fc_company1') || $this->input->post('fc_company2') || $this->input->post('fc_company3') || $this->input->post('fc_company4') || $this->input->post('fc_other1') || $this->input->post('fc_other2') || $this->input->post('fc_other3') || $this->input->post('fc_other4') || $this->input->post('fc_name') || $this->input->post('fc_phone') || $this->input->post('fc_address') || $this->input->post('fc_start_time') || $this->input->post('fc_end_time') || $this->input->post('hours') || $this->input->post('fc_evaluate') || $this->input->post('fc_none_evaluate')) {
				$field_check = array(
									'govid'                 => ($this->input->post('fc_id_card')? 1:0),
									'edc_letter'            => ($this->input->post('fc_electricity_invoice')? 1:0),
									'claim_letter'          => ($this->input->post('fc_check_landlord')? 1:0),
									'family_book'           => ($this->input->post('fc_family_book')? 1:0),
									'water_letter'          => ($this->input->post('fc_water_invoice')? 1:0),
									'place_book'            => ($this->input->post('fc_staying_book')? 1:0),
									'property_check_letter' => ($this->input->post('fc_check_property')? 1:0),
									'other'                 => ($this->input->post('fc_other')? 1:0),
									'other_note'            => $this->input->post('fc_other_textbox'),
									'requestor_curr_address'=> $this->input->post('fc_current_address'),
									'requestor_phone'       => $this->input->post('fc_phone_number'),
									'business1'             => ($this->input->post('fc_business1')? 1:0),
									'business2'             => ($this->input->post('fc_business2')? 1:0),
									'business3'             => ($this->input->post('fc_business3')? 1:0),
									'business4'             => ($this->input->post('fc_business4')? 1:0),
									'company1'              => ($this->input->post('fc_company1')? 1:0),
									'company2'              => ($this->input->post('fc_company2')? 1:0),
									'company3'              => ($this->input->post('fc_company3')? 1:0),
									'company4'              => ($this->input->post('fc_company4')? 1:0),
									'other1'                => ($this->input->post('fc_other1')? 1:0),
									'other2'                => ($this->input->post('fc_other2')? 1:0),
									'other3'                => ($this->input->post('fc_other3')? 1:0),
									'other4'                => ($this->input->post('fc_other4')? 1:0),
									'name'                  => $this->input->post('fc_name'),
									'phone'                 => $this->input->post('fc_phone'),
									'address'               => $this->input->post('fc_address'),
									'start_work'            => $this->input->post('fc_start_time'),
									'end_work'              => $this->input->post('fc_end_time'),
									'hours'                 => $this->input->post('hours'),
									'go_there'              => ($this->input->post('fc_evaluate')? 1:0),
									'not_go_there'          => ($this->input->post('fc_none_evaluate')? 1:0),
									);
			}
            
            if($this->input->post('latitude_') != null or $this->input->post('latitude_') != "")
            {
                $field_check['latitude']    = $this->input->post('latitude_');
                $field_check['longitude']   = $this->input->post('longtitute_');
            }
			//$this->erp->print_arrays($field_check);
           // documents menu
            $documentsArray = [];
            if ( $_FILES['current_address']|| 
                $_FILES['family_book'] || 
                $_FILES['ganervment_id'] || 
                $_FILES['house_photo'] || 
                $_FILES['house_photo'] || 
                $_FILES['employment_certificate'] || 
                $_FILES['other_document']) {

                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                foreach ($_FILES as $key => $value) {
                    
                    if($value['size'] > 0){
                        if (!$this->upload->do_upload($key)) {
                            $error = $this->upload->display_errors();
                            $this->session->set_flashdata('error', $error);
                            redirect($_SERVER["HTTP_REFERER"]);
                        }
                        $photo = array('name' => $this->upload->file_name, 'type' => $key);
                        array_push($documentsArray, $photo);
                    }
                }
            }

            //$this->erp->print_arrays($guarantor_);
            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }
			//$this->erp->print_arrays($documentsArray);
			//$this->erp->print_arrays($id, $data, $products, $employee_, $guarantor_, $documentsArray, $customers, $field_check);
        }
				
        if ($this->form_validation->run() == true && $this->quotes_model->updateQuotationDetails($id, $data, $QouteServices, isset($products) ?$products  : (''), $employee_, $guarantor_, $documentsArray, $customers, $field_check)) {
		 $this->session->set_userdata('remove_quls', 1);
            $this->session->set_flashdata('message', $this->lang->line("quote_saved"));
            redirect('quotes');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$quote = $this->quotes_model->getQuoteByID($id);
			
            $this->data['inv'] = $quote;
            $inv_items = $this->quotes_model->getAllQuoteItems($id);
            $c = rand(100000, 9999999);
			if(is_array($inv_items)){
            foreach ($inv_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
                if (!$row) {
                    $row = json_decode('{}');
                    $row->tax_method = 0;
                } else {
                    unset($row->details, $row->product_details, $row->cost, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                }
                $row->quantity = 0;
                $pis = $this->quotes_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                if ($pis) {
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                $row->id = $item->product_id;
                $row->code = $item->product_code;
                $row->name = $item->product_name;
                $row->type = $item->product_type;
                $row->qty = $item->quantity;
                $row->discount = $item->discount ? $item->discount : '0';
                $row->price = $this->erp->formatDecimal($item->net_unit_price + $this->erp->formatDecimal($item->item_discount / $item->quantity));
                $row->unit_price = $row->tax_method ? $item->unit_price + $this->erp->formatDecimal($item->item_discount / $item->quantity) + $this->erp->formatDecimal($item->item_tax / $item->quantity) : $item->unit_price + ($item->item_discount / $item->quantity);
                $row->real_unit_price = $item->real_unit_price;
                $row->tax_rate = $item->tax_rate_id;
                $row->option = $item->option_id;
                $options = $this->quotes_model->getProductOptions($row->id, $item->warehouse_id);

                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->quotes_model->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
                        if ($pis) {
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if ($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }

                $combo_items = false;
                if ($row->type == 'combo') {
                    $combo_items = $this->quotes_model->getProductComboItems($row->id, $item->warehouse_id);
                    $te = $combo_items;
                    foreach ($combo_items as $combo_item) {
                        $combo_item->quantity = $combo_item->qty * $item->quantity;
                    }
                }
                $ri = $this->Settings->item_addition ? $row->id : $c;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options);
                } else {
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options);
                }
                $c++;
				}
			}
            $this->data['inv_items'] = json_encode(isset($pr) ?$pr  : (''));
            $this->data['id'] = $id;
            //$this->data['currencies'] = $this->site->getAllCurrencies();
            $this->data['billers'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllCompanies('supplier') : null;
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['warehouses'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllWarehouses() : null;
			$this->data['services'] = $this->site->getServicesByStatus('1');
			
			$this->data['sale'] = $this->quotes_model->getSaleByQuoteID($id);
			$this->data['quote_employee'] = $this->quotes_model->getEmployeeQuoteByQuoteID($id);
			$this->data['guarantor'] = $this->quotes_model->getGuarantorByQuoteID($id);
			$this->data['quote_service'] = $this->site->getQuoteServicesByQuoteID($id);
			
			/* Document */
			$this->data['documents'] = $this->quotes_model->getDocumentsByQuoteID($id);
			
			$this->data['finacal_products'] = $this->site->getAllCustomerGroup();
			$this->data['advance_percentages'] = $this->site->getAllDownPercentage();
			$this->data['interest_rates'] = $this->site->getAllInterestRate();
			$this->data['term_in_months'] = $this->site->getAllTerm();
			
			$this->data['product'] = $this->quotes_model->getProductByQuoteID($id);
			$this->data['categories'] = $this->quotes_model->getCategories();
			$this->data['variants'] = $this->site->getVariants();
			$this->data['applicant'] = $this->site->getCompanyByID($quote?$quote->customer_id:'');
			$this->data['field_check'] = $this->site->getFieldCheckByQuoteID($id);
			$this->data['countries'] = $this->site->getCountries();
			
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('quotes'), 'page' => lang('Contract')), array('link' => '#', 'page' => lang('transfer_contract')));
            $meta = array('page_title' => lang('transfer_contract'), 'bc' => $bc);
            $this->page_construct('down_payment/edit_transfer_contract', $meta, $this->data);
        }
	}
	
	function register_form($id = NULL)
	{
		
		if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('engine_number', $this->lang->line("engine_number"), 'required');
		$this->form_validation->set_rules('frame_number', $this->lang->line("frame_number"), 'required');

        if ($this->form_validation->run() == true) {
			$id = $this->input->post('id');
            $data = array(
							'engine' => $this->input->post('engine_number'),
							'frame' => $this->input->post('frame_number'),
							'flate' => $this->input->post('flate_number'),
						);
			
			$quote_items = $this->down_payment_model->getSaleItemBySaleID($id);
			
			$products = '';
            if($quote_items) {
				foreach($quote_items as $quote_item) {
					
					$products[] = array(
										'product_id' => $quote_item->product_id,
										'product_code' => $quote_item->product_code,
										'product_name' => $quote_item->product_name,
										'product_type' => $quote_item->product_type,
										'net_unit_price' => $quote_item->net_unit_price,
										'unit_price' => $quote_item->unit_price,
										'quantity' => $quote_item->quantity,
										'subtotal' => $quote_item->subtotal,
										'real_unit_price' => $quote_item->real_unit_price,
										'color' => $quote_item->color,
										'product_year' => $quote_item->product_year,
										'engine' => $quote_item->engine,
										'frame' => $quote_item->frame,
										'power' => $quote_item->power,
										'distance_mile' => $quote_item->distance_mile
									);
				}
            }
			//$this->erp->print_arrays($quote_items);
        }

        if ($this->form_validation->run() == true && $this->down_payment_model->setRegistration($id, $data, $products)) {
            $this->session->set_flashdata('message', lang("contract_registered"));
            redirect("down_payment/contract_list");
        } else {
		
			$this->data['id'] = $id;
            $this->data['error'] = validation_errors();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'down_payment/register_form', $this->data);

        }
    }
	
	function impApplicants()
    {
        $this->erp->checkPermissions();
        $this->load->helper('security');
        $this->load->library('erp');

        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');
        if ($this->form_validation->run() == true) {

            if (isset($_FILES["userfile"]))
            {	
				$config['upload_path'] = 'assets/uploads/csv/';
				$config['allowed_types'] = 'csv';
				
				$config['max_size'] = '5000000 ';
				$config['overwrite'] = TRUE;
				$this->load->library('upload',$config);
				$this->upload->initialize($config);
				
				if (!$this->upload->do_upload('userfile'))
				{
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error', $error);
					//redirect("sales/customer_opening_balance");
					redirect("down_payment/impapplicants");
				}
				$csv = $this->upload->file_name;
				$arrResult = array();
				$handle = fopen("assets/uploads/csv/" . $csv, "r");
				if ($handle) {
					while (($row = fgetcsv($handle,",")) !== FALSE) {
						$arrResult[] = $row;
					}
					fclose($handle);
				}
				$titles = array_shift($arrResult);
				$keys = array('identify','gov_id', 'family_name', 'family_name_other','name', 'name_other','gender','date_of_birth', 'age', 'phone1','phone2','status','address','nationality','country','state','district','sangkat','village','house_no','housing','years','months','issue_by','issue_date','created_by');
				$final = array();
				foreach ($arrResult as $key => $value) {
					$final[] = array_combine($keys, $value);
				}
				$data_deposit = array();
				$data_insert = array();
				$data_payment = array();
				$deposit_gl = array();
				$balance_gl = array();

				$customer_num = 0;
				$payments = 0;
				//$this->erp->print_arrays($final);
				foreach ($final as $key => $value)
				{
					//$identify_id = $this->site->getIdentifyID($value['identify']);
					$customers[] = array('group_id'              => '3',
										'group_name'			 => 'customer',
										'identify'				 => $value['identify'],
										//'identify'				 => (($identify_id->id)?$identify_id->id:$value['identify']),
										'gov_id'                 => $value['gov_id'],										
										'family_name'            => $value['family_name'],
										'family_name_other'      => $value['family_name_other'],
										'name'                   => $value['name'],
										'name_other'             => $value['name_other'],
										'gender'                 => $value['gender'],
										'date_of_birth'          => $this->erp->fld($value['date_of_birth']),
										'age'                    => $value['age'],
										'phone1'                 => $value['phone1'],
										'phone2'                 => $value['phone2'],
										'status'           		 => $value['status'],
										'address'                => $value['address'],
										'nationality'            => $value['nationality'],
										'country'                => $value['country'],
										//'city'           		 => $value['city'],
										'state'           		 => $value['state'],
										'district'               => $value['district'],										
										'sangkat'                => $value['sangkat'],
										'village'         		 => $value['village'],										
										'house_no'               => $value['house_no'],
										'housing'                => $value['housing'],
										'years'                  => $value['years'],										
										'months'                 => $value['months'],
										'issue_by'				 => $value['issue_by'],
										'issue_date'			 => $this->erp->fld($value['issue_date']),
										//'created_by'			 => $value['created_by'],
									);	
				}
						
				$this->erp->print_arrays($customers);
				if ($this->form_validation->run() == true && $q_id=$this->down_payment_model->ImportCustomers($customers)) {
					$this->session->set_userdata('remove_quls', 1);
					$this->session->set_flashdata('message', $this->lang->line("applicant_added"));
					
					redirect('down_payment/contract_list');
				}else {
					$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
					$this->data['modal_js'] = $this->site->modal_js();
					$this->load->view($this->theme . 'down_payment/impApplicants', $this->data);
				}
            }
        }else {
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('down_payment/contract_list'), 'page' => lang('contract_list')), array('link' => '#', 'page' => lang('import_applicants')));
			$meta = array('page_title' => lang('import_applicants'), 'bc' => $bc);
			$this->page_construct('down_payment/impapplicants', $meta, $this->data);
		}
    }
	function impContracts()
    {
        $this->erp->checkPermissions();
        $this->load->helper('security');
        $this->load->library('erp');

        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');
        if ($this->form_validation->run() == true) {

            if (isset($_FILES["userfile"]))
            {
				
				$config['upload_path'] = 'assets/uploads/csv/';
				$config['allowed_types'] = 'csv';
				$config['max_size'] = '2000';
				$config['overwrite'] = TRUE;
				$this->load->library('upload',$config);
				$this->upload->initialize($config);
				
				if (!$this->upload->do_upload('userfile'))
				{
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error', $error);
					redirect("sales/customer_opening_balance");
				}
				$csv = $this->upload->file_name;
				$arrResult = array();
				$handle = fopen("assets/uploads/csv/" . $csv, "r");
				if ($handle) {
					while (($row = fgetcsv($handle,",")) !== FALSE) {
						$arrResult[] = $row;
					}
					fclose($handle);
				}
				$titles = array_shift($arrResult);
				$keys = array('value_date','reference','branch_code','group_name','customer_id', 'customer_name','product_code','product_type','currency', 'amount','disburse','paid', 'created_by','status', 'frequency','principle_frequency','rate_type','interest_rate','term','installment_date','emp_id','gua_id','jl_id','jg_id','note','product_details');
				$final = array();
				foreach ($arrResult as $key => $value) {
					$final[] = array_combine($keys, $value);
				}
			
				$data_deposit = array();
				$data_insert = array();
				$data_payment = array();
				$deposit_gl = array();
				$balance_gl = array();

				$customer_num = 0;
				$payments = 0;
				$data = array();
				$products = array();
				
				//$this->erp->print_arrays($final);
				foreach ($final as $key => $value)
				{
					//$customer_id = $this->site->getCustomerIDByID($value['customer_id']);
					/*$paid = $value['disburse'] - $value['outstanding_amount'];
					if($paid >= $value['disburse']) {
						$payment_status = 'paid';
					}else if($paid > 0) {
						$payment_status = 'partial';
					}else {
						$payment_status = 'due';
					}*/
					//$com_name = $this->site->getCompanyID($value['customer_id']);
					$product_id = $this->site->getProductIDByCode($value['product_code']);
					$branch_code = $this->site->getBranchByCode($value['branch_code']);
					
					$default_currency = $this->site->get_setting();
					$currency = $value['currency'];
					$total = $value['amount'];
					$disburse = $value['disburse'];
					$amount = $this->erp->convertCurrency($default_currency->default_currency, $currency, $total);
					$disbure_amount = $this->erp->convertCurrency($default_currency->default_currency, $currency, $disburse);
										
					$rate_amount = 0;
					$tmp_amount = $value['interest_rate'];
					$tmp_amounts = str_replace('%', '', $tmp_amount);
					$rate_amount = ($tmp_amounts/100);
					
					$term = $value['term'] * $value['frequency'];
								
					if($value['customer_id']) {
						$data[] = array(
									'reference_no'              => $value['reference'],
									//'customer_group'			=> '',
									'branch_id'              	=> $branch_code->id,
									'customer_group'            => (($value['group_name'])?$value['group_name']:''),
									'customer_id'               => $value['customer_id'],
									'customer'                  => $value['customer_name'],
									'biller'					=> '',
									'date'						=> $this->erp->fld($value['value_date']),
									'approved_date'				=> $this->erp->fld($value['value_date']),
									'contract_date'				=> $this->erp->fld($value['value_date']),
									'register_date'				=> $this->erp->fld($value['value_date']),
									'note'                      => $value['note'],
									'total'                     => str_replace(',', '',$amount),
									'order_discount_id'			=> '',
									'grand_total'               => str_replace(',', '',$disbure_amount),
									//'disburse'              	 => $value['disburse'],
									'status'					=> 'loans',
									'sale_status'				=> $value['status'],
									//'payment_status'			=> $payment_status,
									//'paid'					=> $paid,
									'paid'						=> $this->erp->formatDecimal($value['paid']),
									'created_by'				=> $value['created_by'],
									'frequency'                 => $value['frequency'],
									'principle_frequency'		=> $value['principle_frequency'],
									'rate_type'					=> $value['rate_type'],
									'interest_rate'             => $rate_amount,
									'rate_text' 				=> $value['interest_rate'],
									'term'                      => $term,
									'employee_id'				=> $value['emp_id'],
									'guarantor_id'				=> $value['gua_id'],
									'join_lease_id'				=> $value['jl_id'],
									'join_guarantor_id'			=> $value['jg_id'],
									'due_date'					=> $this->erp->fld($value['installment_date']),
									'mfi'						=> 1,
									'item'						=> array(
																			'product_id' 		=> (($product_id && $product_id->id)? $product_id->id:''),
																			'product_code' 		=> $value['product_code'],
																			'product_type' 		=> 'standard',
																			'quantity' 			=> '1',
																			'unit_price'		=> str_replace(',', '',$value['amount']),
																			'currency_code' 	=> $value['currency'],
																			'description' 		=> $value['product_details'],
																			'subtotal' 			=> str_replace(',', '',$value['amount'])
																		)
								);
					}
				}
				//$this->erp->print_arrays($data);
				
				if ($this->form_validation->run() == true && $this->down_payment_model->ImportContracts($data)) {
					$this->session->set_userdata('remove_quls', 1);
					$this->session->set_flashdata('message', $this->lang->line("contract_added"));
					
					redirect('down_payment/contract_list');
				}else {
					$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
					$this->data['modal_js'] = $this->site->modal_js();
					$this->load->view($this->theme . 'down_payment/impContracts', $this->data);
				}
            }
        }else {
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('down_payment/contract_list'), 'page' => lang('contract_list')), array('link' => '#', 'page' => lang('import_contracts')));
			$meta = array('page_title' => lang('import_contracts'), 'bc' => $bc);
			$this->page_construct('down_payment/impcontracts', $meta, $this->data);
		}
    }
	function impContractsDetail()
    { 
        $this->erp->checkPermissions();
        $this->load->helper('security');
        $this->load->library('erp');

        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if (isset($_FILES["userfile"]))
            {
				$this->load->library('upload');
				$config['upload_path'] = 'assets/uploads/csv/';
				$config['allowed_types'] = 'csv';
				$config['max_size'] = '2000';
				$config['overwrite'] = TRUE;
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('userfile'))
				{
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error', $error);
					redirect("sales/customer_opening_balance");
				}
				$csv = $this->upload->file_name;
				$arrResult = array();
				$handle = fopen("assets/uploads/csv/" . $csv, "r");
				if ($handle) {
					while (($row = fgetcsv($handle,",")) !== FALSE) {
						$arrResult[] = $row;
					}
					fclose($handle);
				}
				
				$titles = array_shift($arrResult);
				$keys = array('loan_ref', 'service_code', 'amount','type');
				$final = array();
				foreach ($arrResult as $key => $value) {
					$final[] = array_combine($keys, $value);
				}
				$data_deposit = array();
				$data_insert = array();
				$data_payment = array();
				$deposit_gl = array();
				$balance_gl = array();
				//$services_payment = array();
				$disbure_payment = array();
				//$sale_service = array();
				$customer_num = 0;
				$payments = 0;
				$data = array();
				$services = array();
				$contracts = $this->site->getContract();
				foreach($contracts as $contract){
					$sv_amount = 0;
					$quote_services = array();
					$sale_service = array();
					$services_payment = array();
					foreach ($final as $key => $value)
					{	
						//$loan_id = $this->site->getLoanIDByReference($value['loan_ref']);	//for refer sale
						$quote_id = $this->site->getQuoteIDByReference($value['loan_ref']);	
						$sv = $this->site->getServiceByServiceCode($value['service_code']);
						$sale = $this->site->getSaleByQouteID($quote_id->id);
						$saleItem =  $this->site->getSaleItemsbyID($sale->id);
						$setting = $this->site->get_setting();
						$acc_setting = $this->site->get_acccount_setting();
						$disburse = $sale->grand_total;
						
						$sv_amount = $value['amount'];
						$sv_amounts = str_replace('%', '', $sv_amount);
						$sv_amounts = ($sv_amounts/100);
						
						if($contract->id == $sale->id){
							/////_Quotes_Servives							
							$quote_services[] = array(
											'quote_id' 		=> $quote_id->id,
											'services_id' 	=> $sv->id,
											'amount' 		=> $sv_amounts,
											'type'     		=>$value['type'],
											'service_paid' 	=> $sv->service_paid,
											'charge_by'		=> $sv->charge_by,
											'tax_rate'		=> 0,
										);
							
							/////_Sales_Servives	
							$sale_service[] = array(
												'sale_id' 		=> $sale->id,
												'services_id' 	=> $sv->id,
												'amount' 		=> $sv_amounts,
												'type'     		=>$value['type'],
												'service_paid' 	=> $sv->service_paid,
												'charge_by'		=> $sv->charge_by,
												'tax_rate'		=> 0,
											);
							
							/////_Servives_Payments							
							if($disburse > 0){																
								if($sv->service_paid == '1'){
									$sv_amount  += $amount;
									$services_payment[] = array(
													'sale_id' 		=> $sale->id,
													'service_id' 	=> $sv->id,
													'amount'	 	=> $amount,
												);
								}
							}
						}
					}
					if($contract->grand_total > 0){
							$disbure_payment = array(
										'biller_id' 		=> $contract->branch_id,
										'date' 				=> $contract->approved_date,
										'sale_id' 			=> $contract->id,
										'amount'			=> $contract->grand_total,											
										'service_amount' 	=> $sv_amount,
										'created_by'		=> $contract->created_by,										
										'bank_acc_code'		=> $acc_setting->default_open_balance,
										'paid_type' 		=> 'Disburse',
										'type'				=> 'disburse',
									);
					}
					//$this->erp->print_arrays($quote_services);
					$this->down_payment_model->ImportContractDetail($quote_services, $sale_service, $services_payment, $disbure_payment);
				}
				
				//$this->erp->print_arrays($disbure_payment);
				if ($this->form_validation->run() == true ) {
					$this->session->set_userdata('remove_quls', 1);
					$this->session->set_flashdata('message', $this->lang->line("contract_details_added"));
					
					redirect('down_payment/contract_list');
				}else {
					$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
					$this->data['modal_js'] = $this->site->modal_js();
					$this->load->view($this->theme . 'down_payment/impContractsDetail', $this->data);
				}
            }
        }else {
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('down_payment/contract_list'), 'page' => lang('contract_list')), array('link' => '#', 'page' => lang('import_service_details')));
			$meta = array('page_title' => lang('import_contract_detail'), 'bc' => $bc);
			$this->page_construct('down_payment/impcontractdetail', $meta, $this->data);
		}
    }
	function impSchedule(){ 
        $this->erp->checkPermissions();
        $this->load->helper('security');
        $this->load->library('erp');

        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if (isset($_FILES["userfile"]))
            {
				$this->load->library('upload');
				$config['upload_path'] = 'assets/uploads/csv/';
				$config['allowed_types'] = 'csv';
				$config['max_size'] = '2000';
				$config['overwrite'] = TRUE;
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('userfile'))
				{
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error', $error);
					redirect("sales/customer_opening_balance");
				}
				$csv = $this->upload->file_name;
				$arrResult = array();
				$handle = fopen("assets/uploads/csv/" . $csv, "r");
				if ($handle){
					while(($row = fgetcsv($handle,",")) !== FALSE){
						$arrResult[] = $row;
					}
					fclose($handle);
				}				
				$titles = array_shift($arrResult);
				$keys = array('loan_ref','no','collection_date','reference_no','principle', 'interest','payment', 'balance','total_service_charge','over_due_days','penalty_amount','paid_amount','discount_rate','interest_payment','owed','paid_by','paid_date','note','created_by');
				$final = array();
				
				//$this->erp->print_arrays($arrResult,$keys);				
				foreach ($arrResult as $key => $value) {
					$final[] = array_combine($keys, $value);
				}
				$data_deposit = array();
				$data_insert = array();
				$data_payment = array();
				$deposit_gl = array();
				$balance_gl = array();

				$customer_num = 0;
				$data = array();
				
				foreach ($final as $key => $value){
				
					$loan_id = $this->site->getLoanIDByReference($value['loan_ref']);					
					$acc_setting = $this->site->get_acccount_setting();
					$setting = $this->site->get_setting();		
					
					if($loan_id){
						$payment_schedule  = array(
														'sale_id' 				=> $loan_id->id,
														'period' 				=> $value['no'],
														'reference_no' 			=> $value['reference_no'],
														'customer_id'			=>$loan_id->customer_id,												
														'dateline' 				=> $this->erp->fld($value['collection_date']),
														//'type' 				=> $value['type'],
														'principle' 			=> str_replace(',', '', $value['principle']),
														'interest' 				=> str_replace(',', '', $value['interest']),
														'payment' 				=> str_replace(',', '', $value['payment']),
														'balance' 				=> str_replace(',', '', $value['balance']),
														'total_service_charge'  => str_replace(',', '', $value['total_service_charge']),
														'overdue_days' 			=> $value['over_due_days'],
														'overdue_amount' 		=> str_replace(',', '', $value['penalty_amount']),
														'paid_amount' 			=> str_replace(',', '', $value['paid_amount']),
														'owed' 					=> str_replace(',', '', $value['owed']),
														'paid_by' 				=> $value['paid_by'],
														'paid_date' 			=> $this->erp->fld($value['paid_date']),
														'note' 					=> $value['note'],
														'created_by' 			=> $value['created_by'],
													);	
						
						$paid_amount	= str_replace(',', '', $value['paid_amount']);
						if($paid_amount != NULL){
							$currency_code = $loan_id->currency_code;
							$default_currency = $setting->default_currency;
							$amount = $paid_amount ;								
							
							$interest = str_replace(',', '', $value['interest']);
							if($amount > $interest){
								$interest_paid = $interest ;
							}else{
								$interest_paid = $amount;
							}
							$owed_interest = $interest - $interest_paid ;
							$payments2 = $amount - $interest_paid;
							
							$total_service_charge = str_replace(',', '', $value['total_service_charge']);
							if($payments2 > $total_service_charge){
								$total_services_paid = $total_service_charge;
							}else{
								$total_services_paid = $payments2;
							}
							$owed_services = $total_service_charge -  $total_services_paid ;
							$payments3 = $payments2 - $total_services_paid;								
								
							$penalty_amount = str_replace(',', '', $value['penalty_amount']);
							if($payments3 > $penalty_amount){
								$penalty_paid = $penalty_amount;
							}else{
								$penalty_paid = $payments3;
							}
							$owed_penalty = $penalty_amount - $penalty_paid ;
							$payments4 = $payments3 - $penalty_paid;								
							
							$principle = str_replace(',', '', $value['principle']);
							if($payments4 >= $principle ){
								$priciple_paid = $principle ;
							}else{
								$priciple_paid = $payments4;
							}
							
							$owed_principle = $principle -  $priciple_paid ;
							$payments = array(
												'biller_id' 		=> $loan_id->branch_id,
												'date' 				=> $this->erp->fld($value['paid_date']),
												'sale_id' 			=> $loan_id->id,
												'reference_no' 		=> $value['reference_no'],
												'amount'			=> $paid_amount,
												//'interest_amount' 	=> $this->erp->convertCurrency($default_currency, $currency_code, $interest_paid ),
												'interest_amount'	=> $this->erp->convertCurrency($default_currency, $currency_code, $value['interest_payment'] ),
												'principle_amount' 	=> $this->erp->convertCurrency($default_currency, $currency_code, $priciple_paid ),
												'service_amount' 	=> $this->erp->convertCurrency($default_currency, $currency_code, $total_services_paid),
												'created_by'		=> $value['created_by'],	
												'paid_by' 			=> $value['paid_by'],											
												'bank_acc_code'		=> $acc_setting->default_open_balance,
												'paid_type' 		=> 'loans receive',
												'owed' 				=> $this->erp->convertCurrency($default_currency, $currency_code, $value['owed']),
												'owed_interest'		=> $this->erp->convertCurrency($default_currency, $currency_code, $owed_interest),
												'owed_services'		=> $this->erp->convertCurrency($default_currency, $currency_code, $owed_services),
												'owed_penalty'		=> $this->erp->convertCurrency($default_currency, $currency_code, $owed_penalty),
												'owed_principle'	=> $this->erp->convertCurrency($default_currency, $currency_code, $owed_principle),
											);	
										
							$services = $this->site->getServices($loan_id->id);
							$period = $value['no'];
							$haftTerm = (round($loan_id->term / $loan_id->frequency)) /2;
							$paid = $this->erp->convertCurrency($default_currency, $currency_code, $total_services_paid);
							$total = $this->erp->convertCurrency($currency_code, $default_currency, $loan_id->total);
							$loan_balance = str_replace(',', '', $value['principle']) + str_replace(',', '', $value['balance']);
							$amount = 0;
							$service_id = 0;
							$service_amount = 0;
							$arr_services = array();
							
							//$this->erp->print_arrays($loan_balance);	
							foreach($services as $service){
								if($service->service_paid == 2 && $period <= $haftTerm){						
									if($service->type == "Percentage"){
										$amount = ($service->charge_by == 1)? ($service->amount * $total): (($service->charge_by == 2)? ($service->amount * $loan_balance ) : 0 ) ;	
										$service_amount = $amount + ($amount * $service->tax_rate);
									}else{
										$amount = $this->erp->convertCurrency($default_currency, $currency_code, $service->amount);
										$service_amount = $amount + ($amount * $service->tax_rate);
									}
									$service_id = $service->services_id;
								}
								if($service->service_paid == 3){
									if($service->type == "Percentage"){
										$amount = ($service->charge_by == 1)? ($service->amount * $total): (($service->charge_by == 2)? ($service->amount * $loan_balance ) : 0 ) ;	
										$service_amount = $amount + ($amount * $service->tax_rate);
									}else{
										$amount = $this->erp->convertCurrency($default_currency, $currency_code, $service->amount);
										$service_amount = $amount + ($amount * $service->tax_rate);
									}
								}
								if($service->service_paid == 4 && $period <= $haftTerm){
									if($service->type == "Percentage"){
										$amount = ($service->amount * $total) / $haftTerm;	
										$service_amount = $amount + ($amount * $service->tax_rate);  //(($period <= $haftTerm)? ($service_amount) : 0 );
										
									}else{
										$amount = $this->erp->convertCurrency($default_currency, $currency_code, $service->amount) / $haftTerm ;
										$service_amount = $amount + ($amount * $service->tax_rate);
									}
								}
								$service_amount = $this->erp->roundUpMoney($service_amount, $currency_code);
								$service_amounts = str_replace(',', '', $service_amount);	
														
								if($service_amounts > 0 ){
									$service =  $service_amounts;
									if ($service > $paid){
										$service_am = $paid;
										$owed = $service  - $paid;
										$service_ow = $owed;
										$paid = 0;
									} else {
										$service_am = $service;
										$paid = $paid - $service;
										$service_ow = 0;
									}
									$arr_services[] = array(
																'sale_id' 		=> $loan_id->id,
																'service_id' 	=> $service_id,
																'amount'	 	=> $service_am,
																'owed'			=> $service_ow,
															);
								}
							}
							//$this->erp->print_arrays($arr_services);
						}
					}
					
					//$this->erp->print_arrays($payment_schedule, $payments, $arr_services);
					$this->down_payment_model->ImportSchedule($payment_schedule, $payments, $arr_services);						
				}
				
				if ($this->form_validation->run() == true ) {
					$this->session->set_userdata('remove_quls', 1);
					$this->session->set_flashdata('message', $this->lang->line("schedule_added"));
					redirect('down_payment/contract_list');
				}else {
					$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
					$this->data['modal_js'] = $this->site->modal_js();
					$this->load->view($this->theme . 'down_payment/impSchedule', $this->data);
				}
            }
        }else {
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('down_payment/contract_list'), 'page' => lang('contract_list')), array('link' => '#', 'page' => lang('import_contract_detail')));
			$meta = array('page_title' => lang('import_schedule'), 'bc' => $bc);
			$this->page_construct('down_payment/impSchedule', $meta, $this->data);
		}
    }
	
	/*===========================Co Transfer ================================*/
	function transfer_co()
	{
		$this->erp->checkPermissions('transfer',false,'quotes');
		$this->data['users'] = $this->down_payment_model->getUsers();
		if (isset($this->permission['reports-back_office']) ?$this->permission['reports-back_office']  : ('')){
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('contracts')));
			$meta = array('page_title' => lang('contracts'), 'bc' => $bc);
		}else{
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('loan_transfer')));
			$meta = array('page_title' => lang('loan_transfer'), 'bc' => $bc);
		}
        $this->page_construct('down_payment/transfer_co', $meta, $this->data);
	}
	public function edit_transfer($id)
    {
		$this->load->model('down_payment_model');
		$this->data['quote_co'] = $this->down_payment_model->getLoansByCO();
		$this->data['all_co'] = $this->down_payment_model->getQuoteCO();
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['modal_js'] = $this->site->modal_js();
		$this->load->view($this->theme . 'down_payment/edit_transfer', $this->data);
    }
	public function update($from_co,$from_bid){
		$from_coid = explode("#",$this->input->post('from_co'));
		$from_co   = $from_coid[0];
		$from_bid  = $this->input->post('from_branch_id');
		$to_coid   = explode("#", $this->input->post('to_co'));	
		$to_co     = $to_coid[0]; 
		$to_bid    = $this->input->post('to_branch_id');
		$i=$this->down_payment_model->updateDetails($from_co,$from_bid,$to_co,$to_bid);
		if($i){
			$this->session->set_flashdata('message', $this->lang->line("co_transferred_successful"));
			redirect('down_payment/transfer_co');
		}
	}
	public function loan_transfer($id=null)
    {
		$this->load->model('down_payment_model');
		
		$this->data['users'] = $this->down_payment_model->getLoansByQuoteID($id);
		$this->data['all_co'] = $this->down_payment_model->getQuoteCO();

		$quote = $this->down_payment_model->getQuoteByID($id);
		$this->data['quote_co'] = $quote;
		$applicant = $this->site->getCompanyByID($quote->customer_id);
		$this->data['applicant'] = $applicant;
		
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['modal_js'] = $this->site->modal_js();
		$this->load->view($this->theme . 'down_payment/loan_co_transfer', $this->data);
    }
	public function updateLoanToCO($from_co,$loan_id,$from_bid){
		$from_coid = explode("#",$this->input->post('from_co'));
		$from_co   = $from_coid[0];
		$loan_id = $this->input->post('loan_id');
		$from_bid  = $this->input->post('from_branch_id');
		$to_coid   = explode("#", $this->input->post('to_co'));	
		$to_co     = $to_coid[0]; 
		$to_bid    = $this->input->post('to_branch_id');
		$i = $this->down_payment_model->updateLoanTransferCo($from_co,$loan_id,$from_bid,$to_co,$to_bid);
		$loan_co = $this->down_payment_model->updateQuoteTransferCo($from_co,$loan_id,$from_bid,$to_co,$to_bid);
		//exit();
		if($i == $loan_co){
			$this->session->set_flashdata('message', $this->lang->line("co_transferred_successful"));
			redirect('down_payment/transfer_co');
		}else{
			$this->down_payment_model->updateQuoteTransferCo($from_co,$loan_id,$from_bid,$to_co,$to_bid);
			$this->session->set_flashdata('message', $this->lang->line("co_transferred_successful"));
			redirect('down_payment/transfer_co');
		}
	}
	public function getTransferLoanByCO($warehouse_id = null)
    {
		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
        }
        if ($this->input->get('customer')) {
            $customer = $this->input->get('customer');
        } else {
            $customer = NULL;
        }
		if ($this->input->get('product_id')) {
            $product_id = $this->input->get('product_id');
        } else {
            $product_id = NULL;
        }
        if ($this->input->get('biller')) {
            $biller = $this->input->get('biller');
        } else {
            $biller = NULL;
        }
		if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
        }
        if ($this->input->get('start_date')) {
            $start_date = $this->input->get('start_date');
        } else {
            $start_date = NULL;
        }
        if ($this->input->get('end_date')) {
            $end_date = $this->input->get('end_date');
        } else {
            $end_date = NULL;
        }
		
        if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date = $this->erp->fld($end_date);
        }
        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
		$transfer_btn = anchor('down_payment/loan_transfer/$1', '<i class="fa fa-exchange"></i> ' . lang('transfer'), 'class="btn btn-info" data-toggle="modal" data-target="#myModal"');
        $action = $transfer_btn;		
		$setting = $this->quotes_model->getSettingCurrncies();
		$this->load->library('datatables');
		$this->datatables
			->select($this->db->dbprefix('quotes').".id,".
					$this->db->dbprefix('quotes').".reference_no,
					CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) AS customer_name_en,
					CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_kh, 	
					CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) AS coname, myBranch.name as branchName,".
					
					$this->db->dbprefix('loan_groups').".name AS glname,
					DATE_FORMAT(".$this->db->dbprefix('quotes').".date,'%d-%m-%Y %h:%i:%s'),
					DATE_FORMAT(".$this->db->dbprefix('quotes').".approved_date,'%d-%m-%Y %h:%i:%s'),".
					$this->db->dbprefix('quotes').".total * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate ."),
					CONCAT((".$this->db->dbprefix('sales').".advance_percentage_payment * 100), '%') as percentage,". 
					$this->db->dbprefix('sales').".advance_payment,
					".$this->db->dbprefix('currencies').".name as crname,
					".$this->db->dbprefix('sales').".second_payment,".
					$this->db->dbprefix('quotes').".status")
			->from('quotes')
			->join('users','quotes.by_co=users.id','INNER')
			->join('sales', 'sales.quote_id = quotes.id', 'left')
			->join('companies','quotes.customer_id=companies.id','INNER')
			->join('companies as myBranch', 'quotes.branch_id = myBranch.id', 'left')
			->join('quote_items', 'quotes.id = quote_items.quote_id', 'left')
			->join('currencies','currencies.code = quote_items.currency_code','left')
			->join('loan_groups','loan_groups.id = quotes.loan_group_id','left')
			->order_by('quotes.id','DESC');
		
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->datatables->where('quotes.branch_id', $this->session->branch_id);
		}
		if(!$view_draft && !($this->Owner || $this->Admin)) {
			$this->datatables->where('erp_quotes.status <>', 'draft');
		}			
		if ($product_id) {
			$this->datatables->join('quote_items', 'quote_items.sale_id = quotes.id', 'left');
			$this->datatables->where('quote_items.product_code', $product_id);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            //$this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			$this->datatables->where('quotes.created_by', $this->session->userdata('user_id'));
		}
		if ($user_query) {
			$this->datatables->where('quotes.created_by', $user_query);
		}
		if ($reference_no) {
			$this->datatables->where('quotes.reference_no', $reference_no);
		}
		if ($biller) {
			$this->datatables->where('quotes.biller_id', $biller);
		}
		if ($customer) {
			$this->datatables->where('quotes.customer_id', $customer);
		}
		if ($warehouse) {
			$this->datatables->where('quotes.warehouse_id', $warehouse);
		}
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('quotes').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
        $this->datatables->add_column("Actions", $action,$this->db->dbprefix('quotes').".id");
        echo $this->datatables->generate();
    }
	/* Co Transfer */
	public function co_transfer($id){
       $this->erp->checkPermissions('edit_transfer',false,'quotes');
		$this->load->model('quotes_model');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $inv = $this->quotes_model->getQuoteByID($id);
        $this->form_validation->set_rules('cus_first_name', $this->lang->line("custocus_first_namemer"), 'required');
   
        if ($this->form_validation->run() == true) {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
			
			$dealer_code = $this->input->post('biller');
			
            $reference = $this->input->post('reference_no');
            if ($this->Owner || $this->Admin) {
                $date = date('Y-m-d H:i:s');
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
			$customer_group = $this->input->post('financial_product');
            //$biller_id = $this->input->post('biller');
            $status = $this->input->post('status');
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
			if(is_array($customer_details)){
            $customer = $customer_details->family_name . ' ' .$customer_details->name;
			}
			$mfi = $this->input->post('mfi');
            $note = $this->erp->clear_tags($this->input->post('note'));
            
			$q_service  = $this->input->post('ch_services');
			
			//collateral_edit
			$cl_id = $this->input->post('cl_id');			
			if($this->input->post('rate_type_cash')) {
				$rate_type = $this->input->post('rate_type_cash');
			}else {
				$rate_type = $this->input->post('rate_type');
			}
			if($this->input->post('interest_rate_cash')) {
				$interest_rate = $this->input->post('interest_rate_cash');
			}else {
				$interest_rate = $this->input->post('interest_rate');
			}
			
			if($this->input->post('interest_rate_cash_2')) {
				$rate_text = $this->input->post('interest_rate_cash_2');
			}else {
				$rate_text = $this->input->post('rate_text');
			}
			
			if($this->input->post('term_cash')) {
				$term = $this->input->post('term_cash');
			}else {
				$term = $this->input->post('term');
			}
			if($this->input->post('frequency_cash')) {
				$frequency = $this->input->post('frequency_cash');
			}else {
				$frequency = $this->input->post('frequency');
			}
			$advance_percentage_payment = $this->input->post('advance_percentage');
			$advance_payment = $this->input->post('advance_payment');
			$status = $this->input->post('status');

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
			
			if($q_service){
				foreach($q_service as $service) {
					$service_types = '';
					$penalty_amount = 0;
					$tmp_amount = str_replace(',', '', $this->input->post('service_'.$service));
					if(strpos($tmp_amount, '%') != false) {
						$service_types = 'Percentage';
						$tmp_amount = str_replace('%', '', $tmp_amount);
						$service_amount = ($tmp_amount/100);
					}else {
						$service_types = 'Fixed_Amount';
						$service_amount = abs($tmp_amount);
					}
					$QouteServices[] = array(
												'services_id' => $service,
												'amount' => $service_amount,
												'type' => $service_types,
												'service_paid' => $this->input->post('service_paid_'.$service),
												'charge_by' => $this->input->post('charge_by_'.$service),
												'tax_rate' => $this->input->post('tax_rate_'.$service),
												'tax_id' => $this->input->post('tax_rateid_'.$service)
											);
				}
			}else{
				foreach($q_service as $service) {
					$service_types = '';
					$penalty_amount = 0;
					$tmp_amount = str_replace(',', '', $this->input->post('service_'.$service));
					if(strpos($tmp_amount, '%') != false) {
						$service_types = 'Percentage';
						$tmp_amount = str_replace('%', '', $tmp_amount);
						$service_amount = ($tmp_amount/100);
					}else {
						$service_types = 'Fixed_Amount';
						$service_amount = abs($tmp_amount);
					}
					$QouteServices[] = array(
												'services_id' => $service,
												'amount' => $service_amount,
												'type' => $service_types,
												'service_paid' => $this->input->post('service_paid_'.$service),
												'charge_by' => $this->input->post('charge_by_'.$service),
												'tax_rate' => $this->input->post('tax_rate_'.$service),
												'tax_id' => $this->input->post('tax_rateid_'.$service)
											);
				}
			}
            if($_POST['product_id']){
                $item_id = $_POST['product_id'];				
				$product = $this->site->getProductByID($item_id);				
                $item_type = $product->type;
                $item_code = $product->code;
                $item_name = $product->name;                
                $real_unit_price = $this->erp->formatDecimal($_POST['price']);				
                $unit_price = $this->erp->formatDecimal($_POST['price']);
                $item_quantity = 1;

                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->quotes_model->getProductByCode($item_code) : null;
                    $unit_price = $real_unit_price;
                    $unit_price = $this->erp->formatDecimal($unit_price);
                    $item_net_price = $unit_price;
                    $subtotal = ($unit_price * $item_quantity);

                    $products[] = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'net_unit_price' => $unit_price,
                        'unit_price' => $this->erp->formatDecimal($unit_price),
						'currency_code' => $this->input->post('currency'),
                        'quantity' => $item_quantity,
                        'subtotal' => $this->erp->formatDecimal($subtotal),
                        'real_unit_price' => $real_unit_price,
						'color' => $this->input->post('color'),
						'product_year' => $this->input->post('year'),
						'engine' => $this->input->post('engine'),
						'frame' => $this->input->post('frame'),
						'power' => $this->input->post('power'),
						'distance_mile' => $this->input->post('distance'),
						'description' => $this->input->post('ldescription')
                    );
                    $total += $item_net_price * $item_quantity;
                }
            }
            if ($this->input->post('discount')) {
                $order_discount_id = $this->input->post('discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = (($total + $product_tax) * (Float) ($ods[0])) / 100;

                } else {
                    $order_discount = $order_discount_id;
                }
            } else {
                $order_discount_id = null;
            }
            $total_discount = $order_discount + $product_discount;

            if ($this->Settings->tax2 != 0) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $order_tax_details->rate;
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = (($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100;
                    }
                }
            } else {
                $order_tax_id = null;
            }
			
			$cg = $this->site->getCustomerGroupByID($customer_group);
            $customers = array('group_id'            => '3',
                            'group_name'             => 'customer',
                            'customer_group_id'      => $customer_group,
                            'customer_group_name'    => isset($cg->name) ?$cg->name  : (''),
                            'identify'               => $this->input->post('identify_id'),
                            'gov_id'                 => $this->input->post('cus_gov_id'),
                            'name'                   => $this->input->post('cus_first_name'),
                            'name_other'             => $this->input->post('cus_first_name_other'),
                            'family_name'            => $this->input->post('cus_family_name'),
                            'family_name_other'      => $this->input->post('cus_family_name_other'),
                            'nickname'               => $this->input->post('cus_nick_name'),
                            'spouse_name'            => $this->input->post('cus_sp_fname'),
                            'spouse_family_name'     => $this->input->post('cus_sp_fam_name'),
                            'gender'                 => $this->input->post('cus_gender'),
                            'status'                 => $this->input->post('cus_marital_status'),
                            'status'                 => $this->input->post('g_status'),
                            'date_of_birth'          => $this->erp->fld(trim($this->input->post('cus_dob'))),
                            'age'                    => $this->input->post('cus_age'),
                            'black_list'             => $this->input->post('cus_black_list'),
                            'whose_income'           => $this->input->post('cus_whose_income'),
                            'income_combination'     => $this->input->post('cus_inc_comb'),
                            'state'                  => $this->input->post('cus_state'),
                            'address'                => $this->input->post('cus_pob'),
                            'phone1'                 => $this->input->post('cus_phone_1'),
                            'phone2'                 => $this->input->post('cus_phone_2'),
                            'spouse_phone'           => $this->input->post('cus_sp_phone'),
                            'house_no'               => $this->input->post('cus_house_no'),
                            'months'                 => $this->input->post('cus_months'),
                            'years'                  => $this->input->post('cus_years'),
                            'housing'                => $this->input->post('cus_housing'),
                            'street'                 => $this->input->post('cus_street'),
                            'village'                => $this->input->post('cus_village'),
                            'district'               => $this->input->post('cus_district'),
                            'sangkat'                => $this->input->post('cus_communce'),
                            'state'                  => $this->input->post('cus_province'),
                            'country'                => $this->input->post('cus_country'),
                            'nationality'            => $this->input->post('cus_nationality'),
                            'num_of_child'           => $this->input->post('cus_num_of_child'),
							'issue_by'         		 => $this->input->post('cus_issue_by'),
							//'created_by'			 => $this->input->post('by_co'),
							'issue_date'          	 => $this->erp->fld(trim($this->input->post('cus_issue_date'))),
							'spouse_gender'          => $this->input->post('sp_gender'),
							'spouse_status'          => $this->input->post('sp_status'),
							'spouse_birthdate'   	 => $this->erp->fld(trim($this->input->post('sp_date_of_birth'))),
							'loan_group_id'    		 => $this->input->post('groupid'),

                        );
			$join_lease = '';
			if($this->input->post('jl_gov_id') || $this->input->post('jl_first_name') || $this->input->post('jl_family_name')) {
				$join_lease = array('group_name'             => 'join_lease',
								'identify'			     =>$this->input->post('jl_identify_id'),
								'gov_id'                 => $this->input->post('jl_gov_id'),
								'name'                   => $this->input->post('jl_name'),
								'gender'                 => $this->input->post('jl_gender'),
								'date_of_birth'          => $this->erp->fld(trim($this->input->post('jl_dob'))),
								'age'                    => $this->input->post('jl_age'),
								'address'                => $this->input->post('jl_address'),
								'phone1'                 => $this->input->post('jl_phone_1'),
								'status'				 => $this->input->post('jl_status'),
								'num_of_child'           => $this->input->post('jl_dependent_children'),
								'family_member'          => $this->input->post('jl_family_member'),
							);
				//	$this->erp->print_arrays($join_lease);
			}
			
			$currency = $this->input->post('currency');
			$default_currency = $this->site->get_setting();
			$qtotal = $this->erp->convertCurrency($default_currency->default_currency, $currency, $total);
            $total_tax = $product_tax + $order_tax;
            $grand_total = $this->erp->formatDecimal($total + $total_tax + $shipping - $order_discount);
			$grand_totals = $this->erp->convertCurrency($default_currency->default_currency, $currency, $grand_total);
			
            $total_tax = $product_tax + $order_tax;
            $grand_total = $this->erp->formatDecimal($total + $total_tax + $shipping - $order_discount);
			
			$user_id = ($this->input->post('cus_by_co') ? $this->input->post('cus_by_co') : $this->session->userdata('user_id'));
			$user = $this->quotes_model->getUser($user_id);
           
		   $data = array(
                'reference_no'              => $reference,
                'customer_id'               => $customer_id,
                'customer'                  => isset($customer) ?$customer  : (''),
                'note'                 	    => ($note ? $note : $this->input->post('purpose')),
                'total'                     => $qtotal,
                'product_discount'          => $product_discount,
                'order_discount_id'         => $order_discount_id,
                'order_discount'            => $order_discount,
                'total_discount'            => $total_discount,
                'product_tax'               => $product_tax,
                'order_tax_id'              => $order_tax_id,
                'order_tax'                 => $order_tax,
                'total_tax'                 => $total_tax,
                'shipping'                  => $shipping,
                'grand_total'               => $grand_totals,
				'status'                    => $status,
				'created_by'				=> $user_id,
				'advance_percentage_payment' => $advance_percentage_payment,
				'advance_payment'           => str_replace(',', '',$advance_payment),
				'installment_date'   		=> $this->erp->fld(trim($this->input->post('st_inst_date'))),
				'frequency'                 => $frequency,
				'rate_type'					=> $rate_type,
				'interest_rate'             => $interest_rate,
				'rate_text'					=> $rate_text,
				'term'                      => $term,
				'principle_frequency'		=> ($this->input->post('principle_frequency')? $this->input->post('principle_frequency') : 1),
				'branch_id'					=> $user->branch_id ,
				'branch_id'					=> $this->input->post('branch_coid'),
				'mfi'						=> $mfi,
				'loan_group_id'    			=> $this->input->post('groupid'),
            );
			
			//$this->erp->print_arrays($data);
			#data of employee
			$employee_ = '';
			if($this->input->post('position') || $this->input->post('work_place_name') || $this->input->post('basic_salary') || $this->input->post('emp_province')) {
				$employee_ = array(
					'position'                  => $this->input->post('position'),
					'emp_status'                => $this->input->post('employment_status'),
					'emp_industry'              => $this->input->post('employment_industry'),
					'senior_level'              => $this->input->post('seniorities_level'),
					'workplace_name'            => $this->input->post('work_place_name'),
					'work_phone'                => $this->input->post('work_phone'),
					'allow_call_to_work_place'  => $this->input->post('allow_call_to_work_place'),
					'years'                     => $this->input->post('emp_years'),
					'months'                    => $this->input->post('emp_months'),
					'basic_salary'              => $this->input->post('basic_salary'),
					'allowance_etc'             => $this->input->post('allowance_etc'),
					'business_expense'          => $this->input->post('business_expense'),
					'address'       		    => $this->input->post('emp_address'),
				);
			}
			//$this->erp->print_arrays($employee_);

            #data of guarantor for loaner
			$guarantor_ = '';
			if($this->input->post('gov_id') || $this->input->post('first_name') || $this->input->post('family_name') || $this->input->post('dob') || $this->input->post('province')) {
				$guarantor_ = array(
					'identify'					=>$this->input->post('gr_identify_id'),
					'gov_id'                    => $this->input->post('gov_id'),
					'name'                      => $this->input->post('gt_name'),
					'gender'                    => $this->input->post('gender'),
					'date_of_birth'             => $this->erp->fld(trim($this->input->post('dob'))),
					'age'						=> $this->input->post('age'),
					'address'                   => $this->input->post('gl_1_address'),
					'phone1'                    => $this->input->post('phone_1'),
					'job'						=> $this->input->post('j_job_1'),
					'issue_by'         		 => $this->input->post('gr_issue_by'),
					'status'         		 => $this->input->post('g_status'),
					'issue_date'          	 => $this->erp->fld(trim($this->input->post('gr_issue_date'))),
				);
			}
			
			$join_guarantor = '';
			if($this->input->post('gov_id2') || $this->input->post('first_name2') || $this->input->post('family_name2')) {
				$join_guarantor = array('group_name'             => 'join_guarantor',
									'identify'					=>$this->input->post('gr_identify_id_2'),
									'gov_id'                    => $this->input->post('gov_id2'),
									'name'                      => $this->input->post('gt_name2'),
									'gender'                    => $this->input->post('gender2'),
									'date_of_birth'             => $this->erp->fld(trim($this->input->post('dob2'))),
									'age'						=> $this->input->post('age2'),
									'address'                   => $this->input->post('gl_2_address'),
									'phone1'                    => $this->input->post('phone_2'),
									'job'						=> $this->input->post('j_job_2'),
									'issue_by'         		 => $this->input->post('gr2_issue_by'),
									'status'         		 => $this->input->post('g_status_2'),
									'issue_date'          	 => $this->erp->fld(trim($this->input->post('gr2_issue_date'))),
								);
			}
			$group_loan = '';
				if ($this->input->post('group_loans')){
					$group_loan = array(
						'name' =>  $this->input->post('group_loans')
					);
				}
				
			
			$collateral = '';
			if($cl_id) {
				$n = sizeof($cl_id);
				for($i = 0; $i < $n; $i++) {
					$collateral[] = array(
						'id'						=> $cl_id[$i],
						'code'                  	=> $this->input->post('cl_code')[$i],
						'card_no'					=> $this->input->post('cl_card_number')[$i],
						'cl_type'                  	=> $this->input->post('cl_type')[$i],
						'type'						=> $this->input->post('type')[$i],
						'size'						=> $this->input->post('size')[$i],
						'adj_north'					=> $this->input->post('cl_north')[$i],
						'adj_south'					=> $this->input->post('cl_south')[$i],
						'adj_east'					=> $this->input->post('cl_east')[$i],
						'adj_west'					=> $this->input->post('cl_west')[$i],
						'roof'						=> $this->input->post('cl_roof')[$i],
						'wall'						=> $this->input->post('cl_wall')[$i],
						'address'					=> $this->input->post('address')[$i],
						'owner_name'				=> $this->input->post('owner_name')[$i],
						'issue_date'				=> $this->input->post('issue_date')[$i],
						'color'						=> $this->input->post('vcl_color')[$i],
						'power'						=> $this->input->post('vcl_power')[$i],
						'engine_no'					=> $this->input->post('vcl_engine_no')[$i],
						'plaque_no'					=> $this->input->post('vcl_plaque_no')[$i],
						'brand'						=> $this->input->post('vcl_brand')[$i],
						'frame_no'					=> $this->input->post('vcl_frame_no')[$i],
					);										
				}				
			}
			
			//print_r($collateral);		
            //$this->erp->print_arrays($collateral);
			$field_check = '';
			if($this->input->post('fc_id_card') || $this->input->post('fc_electricity_invoice') || $this->input->post('fc_check_landlord') || $this->input->post('fc_family_book') || $this->input->post('fc_water_invoice') || $this->input->post('fc_staying_book') || $this->input->post('fc_check_property') || $this->input->post('fc_other') || $this->input->post('fc_other_textbox') || $this->input->post('fc_current_address') || $this->input->post('fc_phone_number') || $this->input->post('fc_business1') || $this->input->post('fc_business2') || $this->input->post('fc_business3') || $this->input->post('fc_business4') || $this->input->post('fc_company1') || $this->input->post('fc_company2') || $this->input->post('fc_company3') || $this->input->post('fc_company4') || $this->input->post('fc_other1') || $this->input->post('fc_other2') || $this->input->post('fc_other3') || $this->input->post('fc_other4') || $this->input->post('fc_name') || $this->input->post('fc_phone') || $this->input->post('fc_address') || $this->input->post('fc_start_time') || $this->input->post('fc_end_time') || $this->input->post('hours') || $this->input->post('fc_evaluate') || $this->input->post('fc_none_evaluate')) {
				$field_check = array(
									'govid'                 => ($this->input->post('fc_id_card')? 1:0),
									'edc_letter'            => ($this->input->post('fc_electricity_invoice')? 1:0),
									'claim_letter'          => ($this->input->post('fc_check_landlord')? 1:0),
									'family_book'           => ($this->input->post('fc_family_book')? 1:0),
									'water_letter'          => ($this->input->post('fc_water_invoice')? 1:0),
									'place_book'            => ($this->input->post('fc_staying_book')? 1:0),
									'property_check_letter' => ($this->input->post('fc_check_property')? 1:0),
									'other'                 => ($this->input->post('fc_other')? 1:0),
									'other_note'            => $this->input->post('fc_other_textbox'),
									'requestor_curr_address'=> $this->input->post('fc_current_address'),
									'requestor_phone'       => $this->input->post('fc_phone_number'),
									'business1'             => ($this->input->post('fc_business1')? 1:0),
									'business2'             => ($this->input->post('fc_business2')? 1:0),
									'business3'             => ($this->input->post('fc_business3')? 1:0),
									'business4'             => ($this->input->post('fc_business4')? 1:0),
									'company1'              => ($this->input->post('fc_company1')? 1:0),
									'company2'              => ($this->input->post('fc_company2')? 1:0),
									'company3'              => ($this->input->post('fc_company3')? 1:0),
									'company4'              => ($this->input->post('fc_company4')? 1:0),
									'other1'                => ($this->input->post('fc_other1')? 1:0),
									'other2'                => ($this->input->post('fc_other2')? 1:0),
									'other3'                => ($this->input->post('fc_other3')? 1:0),
									'other4'                => ($this->input->post('fc_other4')? 1:0),
									'name'                  => $this->input->post('fc_name'),
									'phone'                 => $this->input->post('fc_phone'),
									'address'               => $this->input->post('fc_address'),
									'start_work'            => $this->input->post('fc_start_time'),
									'end_work'              => $this->input->post('fc_end_time'),
									'hours'                 => $this->input->post('hours'),
									'go_there'              => ($this->input->post('fc_evaluate')? 1:0),
									'not_go_there'          => ($this->input->post('fc_none_evaluate')? 1:0),
									'official_name'			=> ($this->input->post('official_evaluate')),
									'official_phone'		=> ($this->input->post('official_num')),
									);
						
			}
            
            if($this->input->post('latitude_') != null or $this->input->post('latitude_') != "")
            {
                $field_check['latitude']    = $this->input->post('latitude_');
                $field_check['longitude']   = $this->input->post('longtitute_');
            }
			//$this->erp->print_arrays($field_check);
           // documents menu
            $documentsArray = [];
            if ( $_FILES['current_address']|| 
                $_FILES['family_book'] || 
                $_FILES['ganervment_id'] || 
                $_FILES['house_photo'] || 
                $_FILES['house_photo'] || 
                $_FILES['employment_certificate'] || 
                $_FILES['other_document']) {

                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                foreach ($_FILES as $key => $value) {
                    
                    if($value['size'] > 0){
                        if (!$this->upload->do_upload($key)) {
                            $error = $this->upload->display_errors();
                            $this->session->set_flashdata('error', $error);
                            redirect($_SERVER["HTTP_REFERER"]);
                        }
                        $photo = array('name' => $this->upload->file_name, 'type' => $key);
                        array_push($documentsArray, $photo);
                    }
                }
            }
            //$this->erp->print_arrays($guarantor_);
            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }
        }
				
        if ($this->form_validation->run() == true && $this->quotes_model->updateQuotationDetails($id, $data, $QouteServices, isset($products)? $products  : (''), $employee_, $guarantor_, $documentsArray, $customers, $field_check, $collateral, $group_loan , $join_lease, $join_guarantor)) {
			
		 $this->session->set_userdata('remove_quls', 1);
         $this->session->set_flashdata('message', $this->lang->line("co_transferred_successful"));
			redirect('down_payment/transfer_co');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$quote = $this->quotes_model->getQuoteByID($id);
			
            $this->data['inv'] = $quote;
            $inv_items = $this->quotes_model->getAllQuoteItems($id);
            $c = rand(100000, 9999999);
			if(is_array($inv_items)){
            foreach ($inv_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
                if (!$row) {
                    $row = json_decode('{}');
                    $row->tax_method = 0;
                } else {
                    unset($row->details, $row->product_details, $row->cost, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                }
                $row->quantity = 0;
                $pis = $this->quotes_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                if ($pis) {
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                $row->id = $item->product_id;
                $row->code = $item->product_code;
                $row->name = $item->product_name;
                $row->type = $item->product_type;
                $row->qty = $item->quantity;
                $row->discount = $item->discount ? $item->discount : '0';
                $row->price = $this->erp->formatDecimal($item->net_unit_price + $this->erp->formatDecimal($item->item_discount / $item->quantity));
                $row->unit_price = $row->tax_method ? $item->unit_price + $this->erp->formatDecimal($item->item_discount / $item->quantity) + $this->erp->formatDecimal($item->item_tax / $item->quantity) : $item->unit_price + ($item->item_discount / $item->quantity);
                $row->real_unit_price = $item->real_unit_price;
                $row->tax_rate = $item->tax_rate_id;
                $row->option = $item->option_id;
                $options = $this->quotes_model->getProductOptions($row->id, $item->warehouse_id);

                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->quotes_model->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
                        if ($pis) {
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if ($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }

                $combo_items = false;
                if ($row->type == 'combo') {
                    $combo_items = $this->quotes_model->getProductComboItems($row->id, $item->warehouse_id);
                    $te = $combo_items;
                    foreach ($combo_items as $combo_item) {
                        $combo_item->quantity = $combo_item->qty * $item->quantity;
                    }
                }
                $ri = $this->Settings->item_addition ? $row->id : $c;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options);
                } else {
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options);
                }
                $c++;
				}
			}
            $this->data['inv_items'] = json_encode(isset($pr) ?$pr  : (''));
            $this->data['id'] = $id;
            //$this->data['currencies'] = $this->site->getAllCurrencies();
            $this->data['billers'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllCompanies('supplier') : null;
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['warehouses'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllWarehouses() : null;
			
			$services = $this->site->getServicesByStatus('1');
			$quote_services = $this->site->getQuoteServicesByQuoteID($id);
			foreach($services as $service) {
				$help = false;
				$amount = 0;
				$type = "";
				foreach($quote_services as $qs) {
					if($service->id == $qs->services_id) {
						$help = true;
						$amount = $qs->amount;
						$type = $qs->type;
					}
				}
				$service->amount =$amount;
				$service->checked = $help;
				$service->method = $type;
			}
			foreach($services as $service) {
				$tax_id = 0;
				foreach($quote_services as $qs) {
					if($service->tax_id == $qs->tax_id) {
						$tax_id = $qs->tax_id;
					}
				}
				$service->tax_id = $tax_id;
			}
			
			$this->data['services'] = $services;
			/*$userid = $this->session->userdata('user_id');
			$user = $this->quotes_model->getUser($userid);
			$this->data['branch'] = $this->quotes_model->getBranchById($user->branch_id);
			$this->data['users'] = $this->quotes_model->getco($user->branch_id);*/
			$this->data['users'] = $this->down_payment_model->getUsers();
			
			$this->data['sale'] = $this->quotes_model->getSaleByQuoteID($id);
			$this->data['quote_employee'] = $this->quotes_model->getEmployeeQuoteByQuoteID($id);
			$this->data['guarantor'] = $this->quotes_model->getGuarantorByQuoteID($id);
			$this->data['quote_service'] = $quote_services;
			$this->data['join_lease'] = $this->site->getJoinLeaseByID($quote->join_lease_id);
			$this->data['join_guarantor'] = $this->site->getJoinGuarantorByID($quote->join_guarantor_id);			
			/* Document */
			$this->data['documents'] = $this->quotes_model->getDocumentsByQuoteID($id);			
			$this->data['finacal_products'] = $this->site->getAllCustomerGroup();
			$this->data['advance_percentages'] = $this->site->getAllDownPercentage();
			$this->data['interest_rates'] = $this->site->getAllInterestRate();
			$this->data['terms'] = $this->site->getAllTerm();
						
			$this->data['category'] = $this->quotes_model->getCategory();
			$this->data['collaterals'] = $this->quotes_model->get_CollateralQuoteID($id);
			$this->data['qphoto'] = $this->quotes_model->getQoutePhoto($id);
			$this->data['product'] = $this->quotes_model->getProductByQuoteID($id);
			$this->data['categories'] = $this->quotes_model->getCategories();
			$this->data['variants'] = $this->site->getVariants();
			$this->data['applicant'] = $this->site->getCompanyByID($quote?$quote->customer_id:'');
			$this->data['field_check'] = $this->site->getFieldCheckByQuoteID($id);
			$this->data['countries'] = $this->site->getCountries();
			$this->data['app_num'] = $this->quotes_model->getNumOfApp($quote->loan_group_id ? $quote->loan_group_id : '');
			$this->data['currencies'] = $this->site->getCurrency();
			$this->data['collateral_type'] = $this->quotes_model->getCollateralType();
			$this->data['identify_type'] = $this->quotes_model->getIdentifyType();
			$this->data['identify_name'] = $this->quotes_model->getIdentifyTypeName(companies_id);
			$this->data['tax_rate'] = $this->site->getAllTaxes();
			$this->data['reference_cl'] = $this->site->getReference('cl');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('down_payment/transfer_co'), 'page' => lang('loan_transfer')), array('link' => '#', 'page' => lang('co_transfer')));
            $meta = array('page_title' => lang('transfer_contract'), 'bc' => $bc);
            $this->page_construct('down_payment/edit_co_transfer', $meta, $this->data);
        }
	}
	/*============End co transfer==============================*/
	
}
