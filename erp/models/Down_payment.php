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
        if (!$this->Owner && !$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
		
        $this->lang->load('down_payment', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('cmt_model');
		$this->load->model('quotes_model');
		$this->load->model('site');
		$this->load->model('down_payment_model');
		$this->load->model('sales_model');
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
		$this->erp->checkPermissions();

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
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('contract_list')));
			$meta = array('page_title' => lang('contract_list'), 'bc' => $bc);
		}
        
        $this->page_construct('down_payment/tranfer_contract', $meta, $this->data);
	}
	
	function contract_list()
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
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('contract_list')));
			$meta = array('page_title' => lang('contract_list'), 'bc' => $bc);
		}
        
        $this->page_construct('down_payment/contract_list', $meta, $this->data);
	}
	
	public function getContracts($warehouse_id = null)
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
		$payment_schedule = anchor('Installment_payment/payment_schedule/0/1/$1', '<i class="fa fa-file-text-o"></i> ' . lang('payment_schedule'), 'data-toggle="modal" data-target="#myModal"');
		$collateral_contract_identification = anchor('Installment_payment/collateral_contract_identification/$1', '<i class="fa fa-file-text-o"></i> ' . lang('collateral_contract'),'target="_blank"');
		$collateral_contract_land = anchor('Installment_payment/collateral_contract_land/$1', '<i class="fa fa-file-text-o"></i> ' . lang('collateral_contract'),'target="_blank"');
		$guareentee_contract = anchor('Installment_payment/guareentee_contract/$1', '<i class="fa fa-file-text-o"></i> ' . lang('guareentee_contract'),'target="_blank"');
		$mcontract = anchor('Installment_payment/mfi_contract/$1', '<i class="fa fa-file-text-o"></i> ' . lang('contract'),'target="_blank"');
		//$payment_schedule_loan = anchor('Installment_Payment/export_loan/0/1/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('loan_payment_schedule'));
		$add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        //$email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('sales/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
        //$pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $guarantor_annex = anchor('down_payment/guarantor_annex/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('guarantor_annex'),'target="_blank"');
        $guarantor_form = anchor('down_payment/guarantor_form/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('guarantor_form'),'target="_blank"');
        $leasing_contract = anchor('down_payment/leasing_contract/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('leasing_contract'),'target="_blank"');
        $pdf_link = anchor('sales/certify_latter/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('Certify_Letter'),'target="_blank"');
        $email_link = anchor('sales/anex_contract/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('Anex_contract'),'target="_blank"');
        $return_link = anchor('sales/return_sale/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_sale'));
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_contract") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_contract') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
           
            <li class="ps">' . $payment_schedule . '</li>
			<li class="cci">' . $collateral_contract_identification . '</li>
			<li class="ccl">' . $collateral_contract_land . '</li>
			<li class="gc">' . $guareentee_contract . '</li>
			<li class="mc">' . $mcontract . '</li>
            <li class="cl">' . $pdf_link . '</li>
            <li class="ga">' . $guarantor_annex . '</li>
            <li class="gf">' . $guarantor_form . '</li>
            <li class="lc">' . $leasing_contract . '</li>
            <li class="el">' . $email_link . '</li>
            <li class="dl">' . $delete_link . '</li>
        </ul>
		</div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables
                ->select($this->db->dbprefix('sales').".id,
						".$this->db->dbprefix('sales').".reference_no,
						CONCAT(".$this->db->dbprefix('companies').".family_name,
						' ',
						".$this->db->dbprefix('companies').".name) as customer_name,CONCAT(".$this->db->dbprefix('companies').".family_name_other,
						' ',
						".$this->db->dbprefix('companies').".name_other) as customer_name_other,
						".$this->db->dbprefix('users').".username,
						".$this->db->dbprefix('sale_items').".product_name,
						".$this->db->dbprefix('sales').".total,
						SUM(".$this->db->dbprefix('sale_services').".amount) as total_service_charge,						
						((COALESCE(".$this->db->dbprefix('sales').".grand_total, 0) - COALESCE(".$this->db->dbprefix('sales').".advance_payment, 0))) as total_amount,
						".$this->db->dbprefix('sales').".sale_status
						".$this->db->dbprefix('sales').".mfi")
                ->from('sales')
				->join('users','sales.created_by=users.id','INNER')
				->join('sale_items', 'sales.id = sale_items.sale_id', 'INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
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
						CONCAT(".$this->db->dbprefix('companies').".family_name, 
						' ', 
						".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, 
						' ', 
						".$this->db->dbprefix('companies').".name_other) as customer_name_other, ".						
						$this->db->dbprefix('users').".username,myBranch.name,".		
						$this->db->dbprefix('sale_items').".product_name, ".						
						$this->db->dbprefix('sales').".total,						
						SUM(".$this->db->dbprefix('sale_services').".amount) as total_service_charge,
						((COALESCE(".$this->db->dbprefix('sales').".grand_total, 0) - COALESCE(".$this->db->dbprefix('sales').".advance_payment, 0))) as total_amount,".
						$this->db->dbprefix('sales').".sale_status, ".
						$this->db->dbprefix('sales').".mfi as mfi")
                ->from('sales')
				->join('users','sales.created_by=users.id','INNER')
				->join('sale_items', 'sales.id = sale_items.sale_id', 'INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('companies as myBranch', 'users.branch_id= myBranch.id')
				->join('products', 'sale_items.product_id = products.id', 'INNER')
				->join('sale_services', 'sales.id = sale_services.sale_id', 'left')
				->join('variants', 'variants.id = sale_items.color', 'left')
				->where($this->db->dbprefix('sales').'.sale_status', 'activated')
				->or_where($this->db->dbprefix('sales').'.sale_status', 'registered')
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
	
	public function getTransferContracts($warehouse_id = null)
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
        if ($warehouse_id) {
            $this->datatables
                ->select($this->db->dbprefix('sales').".id,".
						$this->db->dbprefix('sales').".reference_no,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other, ".
						//$this->db->dbprefix('sales').".biller,".
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
				->where($this->db->dbprefix('sales').'.sale_status =', 'activated')
				->group_by('sales.id');
        }
		
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
			$this->datatables->where('sales.created_by', $user_query);
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
	
	
	
}
