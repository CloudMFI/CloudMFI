<?php defined('BASEPATH') or exit('No direct script access allowed');

class Quotes extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        if ($this->Supplier) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->lang->load('quotations', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('quotes_model');
		$this->load->model('companies_model');
        $this->digital_upload_path = 'assets/uploads/documents/';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '1024';
        $this->data['logo'] = true;
		
		if(!$this->Owner && !$this->Admin) {
            $gp = $this->site->checkPermissions();
            $this->permission = $gp[0];
            $this->permission[] = $gp[0];
        } else {
            $this->permission[] = NULL;
        }

    }
    public function index($warehouse_id = null)
    {
        $this->erp->checkPermissions();
		$this->permission['reports-back_office']='';
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->erp->load->model('reports_model');
		$this->data['users'] = $this->reports_model->getStaff();
		$this->data['products'] = $this->site->getProducts();
		$this->data['group_Loan'] = $this->site->getLoanGroups();
		$this->data['dealer'] = $this->site->getAllDealer('supplier');
        if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : null;
        } else {
            $this->data['warehouses'] = null;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : null;
        }
		if ($this->permission['reports-back_office']){
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('contracts')));
			$meta = array('page_title' => lang('contracts'), 'bc' => $bc);
		}else{
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('applicant')));
			$meta = array('page_title' => lang('quotes'), 'bc' => $bc);
		}     
        $this->page_construct('quotes/index', $meta, $this->data);
    }

    public function getQuotes($view_draft = null)
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

		$approve_link = anchor('quotes/approvedApplicant/$1', '<i class="fa fa-file-text-o"></i> ' . lang('view_details'));
		
		$add_link = anchor('quotes/add_collateral/$1', '<i class="fa fa-plus-circle"></i> ' . lang('add_collateral'),'data-toggle="modal" data-target="#myModal"');
		$add_applicant = anchor('quotes/add_applicant/$1', '<i class="fa fa-file-text-o"></i> ' . lang('add_applicant'));
		$edit_link = anchor('quotes/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_applicant'));
        $pdf_link = anchor('quotes/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_applicant") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('quotes/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_quote') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
                    <ul class="dropdown-menu pull-right" role="menu">';
						$action .= '<li class ="aa">' . $approve_link . '</li>';
						$action .= '<li class ="app">' . $add_applicant . '</li>';
						$action .= '<li class ="al">' . $add_link . '</li>';
                        $action .= '<li class="ed">' . $edit_link . '</li>';
                        $action .= '<li class="de">' . $delete_link . '</li>';
                    $action .= '</ul>
                </div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div
		$setting = $this->quotes_model->getSettingCurrncies();
        $this->load->library('datatables');
		$this->datatables
			->select($this->db->dbprefix('quotes').".id,".
					$this->db->dbprefix('quotes').".reference_no,".
					$this->db->dbprefix('loan_groups').".name AS glname,
					CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) AS customer_name_en,
					CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_kh, ".	
					$this->db->dbprefix('quote_items').".product_name AS asset,".
					"((SELECT erp_companies.name FROM erp_companies WHERE erp_quotes.biller_id = erp_companies.id)) AS dealer_name, ".
					
					$this->db->dbprefix('quotes').".quote_status as status, 
					DATE_FORMAT(".$this->db->dbprefix('quotes').".date,'%d-%m-%Y %h:%i:%s'),
					DATE_FORMAT(".$this->db->dbprefix('quotes').".approved_date,'%d-%m-%Y %h:%i:%s'),
					CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) AS coname,
					myBranch.name,".
					$this->db->dbprefix('quotes').".total * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate ."),".
					$this->db->dbprefix('currencies').".name as crname ")
			->from('quotes')
			->join('users','quotes.by_co=users.id','INNER')
			//->join('sales', 'sales.quote_id = quotes.id', 'left')
			->join('companies','quotes.customer_id=companies.id','INNER')
			->join('companies as myBranch', 'quotes.branch_id = myBranch.id', 'left')
			->join('quote_items', 'quotes.id = quote_items.quote_id', 'left')
			->join('currencies','currencies.code = quote_items.currency_code','left')
			->join('loan_groups','loan_groups.id = quotes.loan_group_id','left')
			->where('erp_quotes.status', 'loans')
			->order_by('quotes.id','DESC');
		
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->datatables->where('quotes.branch_id', $this->session->branch_id);
		}
		if(!$view_draft && !($this->Owner || $this->Admin)) {
			$this->datatables->where('erp_quotes.quote_status <>', 'draft');
		}
		if ($product_id) {
			$this->datatables->join('quote_items as qi', 'qi.quote_id = quotes.id', 'left');
			$this->datatables->where('qi.product_id', $product_id);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            //$this->datatables->where('quotes.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			//$this->datatables->where('quotes.created_by', $this->session->userdata('user_id'));
		}
		if ($user_query) {
			$this->datatables->where('quotes.by_co', $user_query);
		}
		if ($reference_no) {
			$this->datatables->like('quotes.reference_no', $reference_no);
		}
		if ($biller) {
			$this->datatables->where('quotes.biller_id', $biller);
		}
		if ($customer) {
			$this->datatables->where('quotes.customer_id', $customer);
		}
		
		if ($gr_loan) {
			$this->datatables->where('quotes.loan_group_id', $gr_loan);
		}
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('quotes').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
        $this->datatables->add_column("Actions", $action,$this->db->dbprefix('quotes').".id");
        echo $this->datatables->generate();
    }

    public function modal_view($quote_id = null)
    {
        $this->erp->checkPermissions('index', true);

        if ($this->input->get('id')) {
            $quote_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->quotes_model->getQuoteByID($quote_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by, true);
        }
        $this->data['rows'] = $this->quotes_model->getAllQuoteItems($quote_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['modal_js'] = $this->site->modal_js();

        $this->load->view($this->theme . 'quotes/modal_view', $this->data);

    }

    public function view($quote_id = null)
    {
        $this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $quote_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->quotes_model->getQuoteByID($quote_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->data['rows'] = $this->quotes_model->getAllQuoteItems($quote_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('quotes'), 'page' => lang('quotes')), array('link' => '#', 'page' => lang('view')));
        $meta = array('page_title' => lang('view_quote_details'), 'bc' => $bc);
        $this->page_construct('quotes/view', $meta, $this->data);

    }

    public function pdf($quote_id = null, $view = null, $save_bufffer = null)
    {
        $this->erp->checkPermissions();

        if ($this->input->get('id')) {
            $quote_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->quotes_model->getQuoteByID($quote_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->data['rows'] = $this->quotes_model->getAllQuoteItems($quote_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $name = $this->lang->line("quote") . "_" . str_replace('/', '_', $inv->reference_no) . ".pdf";
        $html = $this->load->view($this->theme . 'quotes/pdf', $this->data, true);
        if ($view) {
            $this->load->view($this->theme . 'quotes/pdf', $this->data);
        } elseif ($save_bufffer) {
            return $this->erp->generate_pdf($html, $name, $save_bufffer);
        } else {
            $this->erp->generate_pdf($html, $name);
        }
    }

    public function combine_pdf($quotes_id)
    {
        $this->erp->checkPermissions('pdf');

        foreach ($quotes_id as $quote_id) {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $inv = $this->quotes_model->getQuoteByID($quote_id);
            if (!$this->session->userdata('view_right')) {
                $this->erp->view_rights($inv->created_by);
            }
            $this->data['rows'] = $this->quotes_model->getAllQuoteItems($quote_id);
            $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
            $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
            $this->data['user'] = $this->site->getUser($inv->created_by);
            $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
            $this->data['inv'] = $inv;

            $html[] = array(
                'content' => $this->load->view($this->theme . 'quotes/pdf', $this->data, true),
                'footer' => '',
            );
        }

        $name = lang("quotes") . ".pdf";
        $this->erp->generate_pdf($html, $name);

    }

    public function email($quote_id = null)
    {
		$this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $quote_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->quotes_model->getQuoteByID($quote_id);
        if (!$this->session->userdata('view_right')) {
            $this->erp->view_rights($inv->created_by);
        }
        $this->data['rows'] = $this->quotes_model->getAllQuoteItems($quote_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : null;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('quotes'), 'page' => lang('quotes')), array('link' => '#', 'page' => lang('view')));
        $meta = array('page_title' => lang('view_quote_details'), 'bc' => $bc);
        $this->page_construct('quotes/email', $meta, $this->data);
    }
	
	public function field_check($quote_id = null)
    {

        if ($this->input->get('id')) {
            $quote_id = $this->input->get('id');
        }
        $inv = $this->quotes_model->getQuoteByID($quote_id);
        $this->form_validation->set_rules('to', $this->lang->line("to") . " " . $this->lang->line("email"), 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', $this->lang->line("subject"), 'trim|required');
        $this->form_validation->set_rules('cc', $this->lang->line("cc"), 'trim');
        $this->form_validation->set_rules('bcc', $this->lang->line("bcc"), 'trim');
        $this->form_validation->set_rules('note', $this->lang->line("message"), 'trim');

        if ($this->form_validation->run() == true) {
            if (!$this->session->userdata('view_right')) {
                $this->erp->view_rights($inv->created_by);
            }
            $to = $this->input->post('to');
            $subject = $this->input->post('subject');
            if ($this->input->post('cc')) {
                $cc = $this->input->post('cc');
            } else {
                $cc = null;
            }
            if ($this->input->post('bcc')) {
                $bcc = $this->input->post('bcc');
            } else {
                $bcc = null;
            }
            $customer = $this->site->getCompanyByID($inv->customer_id);
            $this->load->library('parser');
            $parse_data = array(
                'reference_number' => $inv->reference_no,
                'contact_person' => $customer->name,
                'company' => $customer->company,
                'site_link' => base_url(),
                'site_name' => $this->Settings->site_name,
                'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',
            );
            $msg = $this->input->post('note');
            $message = $this->parser->parse_string($msg, $parse_data);
            $attachment = $this->pdf($quote_id, null, 'S'); //delete_files($attachment);
        } elseif ($this->input->post('send_email')) {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->session->set_flashdata('error', $this->data['error']);
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->erp->send_email($to, $subject, $message, null, null, $attachment, $cc, $bcc)) {
            delete_files($attachment);
            $this->db->update('quotes', array('status' => 'sent'), array('id' => $quote_id));
            $this->session->set_flashdata('message', $this->lang->line("email_sent"));
            redirect("quotes");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            if (file_exists('./themes/' . $this->theme . '/views/email_templates/quote.html')) {
                $quote_temp = file_get_contents('themes/' . $this->theme . '/views/email_templates/quote.html');
            } else {
                $quote_temp = file_get_contents('./themes/default/views/email_templates/quote.html');
            }

            $this->data['subject'] = array('name' => 'subject',
                'id' => 'subject',
                'type' => 'text',
                'value' => $this->form_validation->set_value('subject', lang('quote').' (' . $inv->reference_no . ') '.lang('from').' '.$this->Settings->site_name),
            );
            $this->data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'text',
                'value' => $this->form_validation->set_value('note', $quote_temp),
            );
            $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);

            $this->data['id'] = $quote_id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'quotes/field_check', $this->data);

        }
    }

    public function add($id=null)
    {		
        $this->erp->checkPermissions('index');
        //$this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        //$this->form_validation->set_rules('reference_no', $this->lang->line("reference_no"), 'required');
        $this->form_validation->set_rules('cus_gov_id', $this->lang->line("cus_gov_id"), 'required');
		$this->form_validation->set_rules('price', $this->lang->line("amount"), 'required');
		$this->form_validation->set_rules('currency', $this->lang->line("currency"), 'required');
		$this->form_validation->set_rules('st_inst_date', $this->lang->line("start_installment_date"), 'required');
		$this->form_validation->set_rules('interest_rate_cash_2', $this->lang->line("interest_rate"), 'required');
		$this->form_validation->set_rules('term_cash', $this->lang->line("term"), 'required');
		$this->form_validation->set_rules('frequency_cash', $this->lang->line("payment_frequency"), 'required');
		$this->form_validation->set_rules('rate_type_cash', $this->lang->line("rate_type"), 'required');
		//$this->form_validation->set_rules('principle_frequency', $this->lang->line("principle_frequency"), 'required');
		
		//$data = array(
		//		'name' =>  $this->input->post('group_loans')
		//		);
		//$this->load->model('Quotes_model');
		//$this->Quotes_model->addGroupLoan($data);
		
        if ($this->form_validation->run() == true) {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
			$pr_item_tax = 0;
			
			$dealer_code = $this->input->post('biller');		
            
            if ($this->Owner || $this->Admin) {
                $date = date('Y-m-d H:i:s');
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
            $biller_id = $this->input->post('biller');
            $status = $this->input->post('status');
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
            $customer = isset($customer_details->family_name) ? $customer_details->family_name  : (('') . ' ' . (isset($customer_details->name) ? $customer_details->name  : ''));
            $biller_details = $this->site->getCompanyByID($biller_id);
			//$biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            
			$note = $this->erp->clear_tags($this->input->post('note'));
			/////////////service1
            $q_service  = $this->input->post('ch_services');
			
			//$services_amount  = str_replace(',', '', $this->input->post('service'));
			//$services_type = $this->input->post('h_type');
			//$services_paid  = $this->input->post('service_paid');			
			
			$mfi = $this->input->post('mfi');
			if($this->input->post('customer_type')) {
				$customer_group = $this->input->post('customer_type');
			}else {
				$customer_group = $this->input->post('financial_product');
			}
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

            # get services1
            /*for($sv = 0; $sv < count($q_service); $sv ++) {
                $QouteServices[] = array(
											'services_id' => $q_service[$sv],
											'amount' => $services_amount[$sv],
											'type' => $services_type[$sv],
											'service_paid' => $services_paid[$sv],
											
										);
			/*foreach($q_service as $service) {
				$QouteServices[] = array(
											'services_id' => $service,
											'amount' => str_replace(',', '', $this->input->post('service_'.$service)),
											'type' => $this->input->post('h_type_'.$service),
											'service_paid' => $this->input->post('service_paid_'.$service),
											
										);
			}*/
			
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
			//$this->erp->print_arrays($QouteServices);
			
            $i = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;
            if($_POST['product_id']){
                $item_id = $_POST['product_id'];
				$product = $this->site->getProductByID($item_id);
                $item_type = $product->type;
                $item_code = $product->code;
                $item_name = $product->name;
                
                $real_unit_price = $this->erp->formatDecimal($_POST['price']);
				
                //$unit_price = $this->erp->formatDecimal(str_replace(',', '', $_POST['price']));
				$unit_price = $this->erp->formatDecimal($_POST['price']);
                $item_quantity = 1;
				
                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->quotes_model->getProductByCode($item_code) : null;
                    $unit_price = $real_unit_price;
                    $product_tax += $pr_item_tax;
                    $subtotal = ($unit_price * $item_quantity);

                    $products[] = array(
                        'product_id' 		=> $item_id,
                        'product_code' 		=> $item_code,
                        'product_name' 		=> $item_name,
                        'product_type' 		=> $item_type,
                        'net_unit_price' 	=> $unit_price,
                        'unit_price' 		=> $this->erp->formatDecimal($unit_price),
						'currency_code' 	=> $this->input->post('currency'),
                        'quantity' 			=> $item_quantity,
                        'subtotal' 			=> $this->erp->formatDecimal($subtotal),
                        'real_unit_price' 	=> $real_unit_price,
						'color' 			=> $this->input->post('color'),
						'product_year' 		=> $this->input->post('year'),
						'description' 		=> $this->input->post('ldescription')
                    );
                    $total += $unit_price * $item_quantity;
                }
            }
			/*
            if (empty(!$products)) {
                //$this->form_validation->set_rules('product', lang("order_items"), 'required');
            //} else {
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
							//'civility'               => $this->input->post('cus_civility'),
							'identify'				 =>$this->input->post('identify_id'),
							'gov_id'                 => $this->input->post('cus_gov_id'),
							'name'                   => $this->input->post('cus_first_name'),
							'name_other'             => $this->input->post('cus_first_name_other'),
							'family_name'            => $this->input->post('cus_family_name'),
							'family_name_other'      => $this->input->post('cus_family_name_other'),
							'father_name'            => $this->input->post('father_name'),
							'nickname'               => $this->input->post('cus_nick_name'),
                            'spouse_name'            => $this->input->post('cus_sp_fname'),
 							'spouse_family_name'     => $this->input->post('cus_sp_fam_name'),
							'gender'                 => $this->input->post('cus_gender'),
							'status'                 => ($this->input->post('cus_marital_status')?$this->input->post('cus_marital_status'):$this->input->post('g_status')),
							//'status'       			 => $this->input->post('g_status'),
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
							'created_by'			 => $this->session->userdata('user_id'),
							
							
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
			}
			//$this->erp->print_arrays($join_lease);
			$currency = $this->input->post('currency');
			$default_currency = $this->site->get_setting();
			$qtotal = $this->erp->convertCurrency($default_currency->default_currency, $currency, $total);
            $total_tax = $product_tax + $order_tax;
            $grand_total = $this->erp->formatDecimal($total + $total_tax + $shipping - $order_discount);
			$grand_totals = $this->erp->convertCurrency($default_currency->default_currency, $currency, $grand_total);
			
			$user_id = ($this->input->post('cus_by_co') ? $this->input->post('cus_by_co') : $this->session->userdata('user_id'));
			$user = $this->quotes_model->getUser($user_id);
			
			//$rate_types = $this->input->post('rate_type');
			$reference = $this->site->getReference('qu');
			$term_days = $frequency * $term;
            $data = array('date'        => $date,
                'reference_no'          => $reference,
				'customer_group'        => $customer_group,
                'customer'              => $this->input->post('cus_family_name') .' '. $this->input->post('cus_first_name'),
                'biller_id'             => $biller_id,
                'biller'                => $biller,
                'note'                  => ($note ? $note : $this->input->post('purpose')),
                'total'                 => $qtotal,
                'product_discount'      => $product_discount,
                'order_discount_id'     => $order_discount_id,
                'order_discount'        => $order_discount,
                'total_discount'        => $total_discount,
                'product_tax'           => $product_tax,
                'order_tax_id'          => $order_tax_id,
                'order_tax'             => $order_tax,
                'total_tax'             => $total_tax,
                'shipping'              => $shipping,
                'grand_total'           => $grand_totals,
				'quote_status'          => $this->input->post('status'),
				'status'          		=> 'loans',
				'created_by'			=> $this->session->userdata('user_id'),
				'by_co'			 		=> $user_id,
				'installment_date'   	=> $this->erp->fld(trim($this->input->post('st_inst_date'))),
				'advance_percentage_payment' => $advance_percentage_payment,
				'advance_payment'       => str_replace(',', '', $advance_payment),
				'frequency'             => $frequency,
				'rate_type'				=> $rate_type,
				'interest_rate'         => $interest_rate,
				'rate_text'             => $rate_text,
				'term'                  => $term_days,
				'principle_frequency'	=> ($this->input->post('principle_frequency')? $this->input->post('principle_frequency') : 1),
				'branch_id'				=> $user->branch_id,
				'mfi'					=> $mfi,
				
            );
			
			$saving_ = $this->input->post('saving_rate');
			
			$saving_rate = str_replace(',', '', $this->input->post('saving_rate'));				
			$saving_rate = str_replace('%', '', $saving_rate);
			$saving_rate_ = ($saving_rate/100);
			
			$saving_interest_rate = str_replace(',', '', $this->input->post('saving_interest_rate'));				
			$saving_interest = str_replace('%', '', $saving_interest_rate);
			$saving_interest_ = ($saving_interest/100);
			
			$saving_amt = str_replace(',', '', $this->input->post('saving_amount'));
			$saving_amount = $this->erp->convertCurrency($default_currency->default_currency, $currency, $saving_amt);
			
				
			if($saving_){
				$saving = array(
					'date'        				=> $date,
					'saving_rate'               => $saving_rate_ ,
					'saving_amount'             => $saving_amount,
					'saving_type'               => $this->input->post('saving_type'),
					'saving_interest_rate'      => $saving_interest_ ,
					'reference_no'          	=> $reference,
					'customer'              	=> $this->input->post('cus_family_name') .' '. $this->input->post('cus_first_name'),
					'status'          			=> 'saving',
					'quote_status'          	=> $this->input->post('status'),
					'created_by'				=> $this->session->userdata('user_id'),
					'by_co'			 			=> $user_id,
					'branch_id'					=> $user->branch_id,
				);
				
				$saving_item = array(
                        
						'currency_code' 	=> $this->input->post('currency'),
                        'subtotal' 			=> str_replace(',', '', $this->input->post('saving_amount')),
						'unit_price' 		=> str_replace(',', '', $this->input->post('saving_amount')),
						'net_unit_price' 	=> str_replace(',', '', $this->input->post('saving_amount')),
						'real_unit_price' 	=> str_replace(',', '', $this->input->post('saving_amount')),
                );
					
			}
			//$this->erp->print_arrays($saving);
			//$this->erp->print_arrays($data);
            #data of employee
			$employee_ = '';
			if($this->input->post('position') || $this->input->post('work_place_name') || $this->input->post('basic_salary') || $this->input->post('emp_province')) {
				$employee_   = array(
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
					/*'house_no'                  => $this->input->post('emp_house_no'),
					'street'                    => $this->input->post('emp_street'),
					'country'                   => $this->input->post('emp_country'),
					'province'                 => $this->input->post('emp_province'),
					'district'                  => $this->input->post('emp_district'),
					'communce'                  => $this->input->post('emp_communce'),
					'village'                   => $this->input->post('emp_village'),*/
				);
			}
            #data of guarantor for loaner
			$guarantor_ = '';
			if($this->input->post('gov_id') || $this->input->post('first_name') || $this->input->post('family_name') || $this->input->post('dob') || $this->input->post('province') || $this->input->post('g_status')) {
				$guarantor_ = array( 'group_name'	 =>'guarantor',
					
					'identify'					=>$this->input->post('gr_identify_id'),
					'gov_id'					=>$this->input->post('gov_id'),
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
			if($this->input->post('gov_id2') || $this->input->post('first_name2') || $this->input->post('family_name2') || $this->input->post('gr2_issue_by')) {
				$join_guarantor = array('group_name'             => 'join_guarantor',
									
									'identify'					=>$this->input->post('gr_identify_id_2'),
									'gov_id'					=>$this->input->post('gov_id2'),
									'name'                      => $this->input->post('gt_name2'),
									'gender'                    => $this->input->post('gender2'),
									'date_of_birth'             => $this->erp->fld(trim($this->input->post('dob2'))),
									'age'						=> $this->input->post('age2'),
									'address'                   => $this->input->post('gl_2_address'),
									'phone1'                    => $this->input->post('phone_2'),
									'job'						=> $this->input->post('j_job_2'),
									'issue_by'         		 	=> $this->input->post('gr2_issue_by'),
									'status'         		 	=> $this->input->post('g_status_2'),
									'issue_date'          		=> $this->erp->fld(trim($this->input->post('gr2_issue_date'))),
								);
			}
			$group_loan = '';
			if ($this->input->post('group_loans')){
				$group_loan = array(
					'name' =>  $this->input->post('group_loans')
				);
			}
			
			$collateral = '';
			if($this->input->post('cl_type')) {
				$collateral= array(
					'code'                  	=> $this->input->post('cl_code'),
					'card_no'					=> $this->input->post('cl_card_number'),
					'cl_type'                  	=> $this->input->post('cl_type'),
					'type'                      => ($this->input->post('cl_home_type')?$this->input->post('cl_home_type'):$this->input->post('cl_land_type'))?:$this->input->post('vcl_vehicles_type'),
					'size'						=> ($this->input->post('cl_land_size')? $this->input->post('cl_land_size') : $this->input->post('cl_home_size')),
					'owner_name'                => ($this->input->post('home_owner_name')?$this->input->post('home_owner_name'):$this->input->post('land_owner_name'))?:$this->input->post('vcl_owner_name'),					
					'adj_north'					=> $this->input->post('cl_north'),
					'adj_south'					=> $this->input->post('cl_south'),
					'adj_east'					=> $this->input->post('cl_east'),
					'adj_west'					=> $this->input->post('cl_west'),
					'roof'						=> $this->input->post('cl_roof'),
					'wall'						=> $this->input->post('cl_wall'),
					'address'					=> ($this->input->post('cl_land_address')? $this->input->post('cl_land_address') : $this->input->post('cl_home_address')),
					'color'						=> $this->input->post('vcl_color'),
					'power'						=> $this->input->post('vcl_power'),
					'engine_no'					=> $this->input->post('vcl_engine_no'),
					'plaque_no'					=> $this->input->post('vcl_plaque_no'),					
					'brand'						=> $this->input->post('vcl_brand'),
					'frame_no'					=> $this->input->post('vcl_frame_no'),					
					'issue_date'          	 => $this->erp->fld(trim(($this->input->post('home_issue_date')?$this->input->post('home_issue_date'):$this->input->post('land_issue_date'))?:$this->input->post('vcl_issue_date'))),
					
				);
			}
			//$group_loan = '';
			//if($this->input->post('group_loan')) {
			//	$group_loan= array(
			//		'name'	=>$this->input->post('group_loan'), 
			//	);
			//}
			//$this->erp->print_arrays($collateral);
			/*if($this->input->post('cl_home_type')){
				echo "yes";
			}else{
				echo "no";
			}
			echo $this->input->post('cl_land_type'); echo "cl_land_type|||||";
			echo $this->input->post('vcl_vehicles_type'); echo "vcl_vehicles_type|||||";*/
			//'type'						=> ($this->input->post('cl_home_type')? $this->input->post('cl_home_type') :($this->input->post('cl_land_type')) || ($this->input->post('cl_land_type')? $this->input->post('cl_land_type'): $this->input->post('vcl_vehicles_type'))),
			//'type'						=> ($this->input->post('cl_land_type')? $this->input->post('cl_land_type') :($this->input->post('cl_home_type'))|| ($this->input->post('cl_home_type')? $this->input->post('cl_home_type'): ($this->input->post('vcl_vehicles_type')))),
			//$check_field = $this->input->post('fc_id_card') || $this->input->post('fc_electricity_invoice') || $this->input->post('fc_check_landlord') || $this->input->post('fc_family_book') || $this->input->post('fc_water_invoice') || $this->input->post('fc_staying_book') || $this->input->post('fc_check_property') || $this->input->post('fc_other') || $this->input->post('fc_other_textbox') || $this->input->post('fc_current_address') || $this->input->post('fc_phone_number') || $this->input->post('fc_business1') || $this->input->post('fc_business2') || $this->input->post('fc_business3') || $this->input->post('fc_business4') || $this->input->post('fc_company1') || $this->input->post('fc_company2') || $this->input->post('fc_company3') || $this->input->post('fc_company4') || $this->input->post('fc_other1') || $this->input->post('fc_other2') || $this->input->post('fc_other3') || $this->input->post('fc_other4') || $this->input->post('fc_name') || $this->input->post('fc_phone') || $this->input->post('fc_address') || $this->input->post('fc_start_time') || $this->input->post('fc_end_time') || $this->input->post('hours') || $this->input->post('fc_evaluate') || $this->input->post('fc_none_evaluate')
			
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
									'latitude'              => $this->input->post('latitude_'),
									'longitude'             => $this->input->post('longtitute_'),
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
			
			if(isset($_POST['print_payment_schedule']) && $_POST['print_payment_schedule'] == lang('print_payment_schedule')){
	    echo "Code for print_payment_schedule button"; 
				}
			
			
			//$this->erp->print_arrays($data, $join_lease, $join_guarantor, $guarantor_);
            // documents menu
            $documentsArray = [];
            if ( $_FILES['current_address']|| 
                $_FILES['family_book'] || 
                $_FILES['ganervment_id'] || 
                $_FILES['house_photo'] || 
                $_FILES['store_photo'] || 
                $_FILES['employment_certificate'] || 
                $_FILES['applicant_photo'] || 
                $_FILES['spouse_photo'] || 
                $_FILES['gurantors_photo'] || 
                $_FILES['birth_registration_letter'] || 
                $_FILES['passport'] || 
                $_FILES['marriage_certificate'] || 
                $_FILES['driver_license'] || 
                $_FILES['working_contract'] || 
                $_FILES['invoice_salary'] || 
                $_FILES['business_certificate'] || 
                $_FILES['profit_for_the_last_3_month'] || 
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
			//$this->erp->print_arrays($join_lease, $join_guarantor, $collateral);
        }

        if ($this->form_validation->run() == true && $q_id=$this->quotes_model->addQuote($data, isset($products) ?$products  : (''), isset($QouteServices) ?$QouteServices  : (''), $guarantor_, $employee_, $documentsArray, $customers, $field_check, $collateral, $group_loan , $join_lease, $join_guarantor, $saving, $saving_item)) {
            $this->session->set_userdata('remove_quls', 1);
            $this->session->set_flashdata('message', $this->lang->line("quote_added"));
			
            redirect('quotes/edit/'.$q_id);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            //$this->data['customers'] = $this->site->getAllCompanies('customer');
            $this->data['billers'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllCompanies('supplier') : null;
            //$this->data['currencies'] = $this->site->getAllCurrencies();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['warehouses'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllWarehouses() : null;
            $this->data['qunumber'] = ''; //$this->site->getReference('qu');
			$this->data['services'] = $this->site->getServicesByStatus('1');
			//$this->erp->print_arrays($this->site->getServicesByStatus('1'));
			//$this->data['group_loan'] = $this->quotes_model->getGroupLoan('1');
			$this->data['finacal_products'] = $this->site->getAllCustomerGroup();
			$this->data['advance_percentages'] = $this->site->getAllDownPercentage();
			$this->data['interest_rates'] = $this->site->getAllInterestRate();
			$this->data['terms'] = $this->site->getAllTerm();
			$this->data['variants'] = $this->site->getVariants();
			$this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
			$this->data['countries'] = $this->site->getCountries();			
			$userid = $this->session->userdata('user_id');
			$user = $this->quotes_model->getUser($userid);
			$this->data['branch'] = $this->quotes_model->getBranchById($user->branch_id);
			$this->data['users'] = $this->quotes_model->getco($user->branch_id);
			//$this->data['gov_id'] = $this->quotes_model->GetGovID();
			$this->data['customers'] = $this->quotes_model->getGovID();			
			$this->data['products'] = $this->quotes_model->getProducts();
			$this->data['categories'] = $this->quotes_model->getCategories();
			$this->data['currencies'] = $this->site->getCurrency();
			$this->data['tax_rate'] = $this->site->getAllTaxes();
			$this->data['collateral_type'] = $this->quotes_model->getCollateralType();
			$this->data['identify_type'] = $this->quotes_model->getIdentifyType();
			$this->data['identify_name'] = $this->quotes_model->getIdentifyTypeName(companies_id);
			$this->data['category'] = $this->quotes_model->getCategory();
			$this->data['setting'] = $this->quotes_model->get_setting();
			$quote = $this->quotes_model->getQuoteByID($id);
			$this->data['applicant'] = $this->site->getCompanyByID($id);
			
			$this->data['qphoto'] = $this->quotes_model->getQoutePhoto($id);
			
			$info= $this->companies_model->getCustomerLoans($id);
			if($info){
				$this->data['customer_loan']=$info;
			}
			
			$this->data['reference_cl'] = $this->site->getReference('cl');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('quotes'), 'page' => lang('quotes')), array('link' => '#', 'page' => lang('add_quote')));
            $meta = array('page_title' => lang('add_quote'), 'bc' => $bc);
            $this->page_construct('quotes/add', $meta, $this->data);		
		
        }
    }
	
	public function ajaxGetSubCategoryByCatID($category_id = NULL){
		if ($rows = $this->quotes_model->getSubCatByCatID($category_id)) {
            $data = json_encode($rows);
        } else {
            $data = false;
        }
        echo $data;
	}
	
	public function ajaxGetProductBySubCategoryID($sub_category_id = NULL){
		if($sub_category_id){
			$sub_category_id = $sub_category_id;
		}
		$product = $this->quotes_model->getProductBySubCatID($sub_category_id);
		if($product){
			die(json_encode($product));
		}else{
			die(json_encode(FALSE));
		}
	}
	public function ajaxGetProductBySubCategoryID2($id = NULL){
		if($id){
			$idd = $id;
		}
		$product = $this->quotes_model->getProductBySubCatID2($idd);
		if($product){
			die (json_encode($product));
			
		}else{
			die(json_encode(FALSE));
		}
	}
	
	
	public function approvedApplicant($id = NULL)
	{	
		$this->erp->checkPermissions('index', null, 'quotes');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $inv = $this->quotes_model->getQuoteByID($id);
		
        $this->form_validation->set_rules('status', $this->lang->line("status"), 'required');
		$q_service  = $this->input->post('ch_services');
		
        if ($this->form_validation->run() == true) {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
			$q_reject  = $this->input->post('cus_reject');
			
			$quote_rejects = array(
				'quote_id' => $id,
				'reject_id' => $q_reject
			);
			
			$reference = $this->site->getReference('qu');
            if ($this->Owner || $this->Admin) {
                $date = date('Y-m-d H:i:s');
            } else {
                $date = date('Y-m-d H:i:s');
            }			
            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
			for($qr = 0; $qr < count($q_reject); $qr ++) {
                $QouteReject[] = array(
										'reject_id' => $q_reject[$qr],
									);
            }			
			/*
            # get services
            for($sv = 0; $sv < count($q_service); $sv ++) {
                $QouteServices[] = array(
											'services_id' => $q_service[$sv],
											'amount' => $services_amount[$sv],
										);
            }
			*/
           // $i = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;		   
			$quote_items = $this->quotes_model->getQuoteItemByQuoteID($id);			
			$products = '';
            if($quote_items) {
				foreach($quote_items as $quote_item) {					
					$product = $this->site->getProductByCode($quote_item->product_code);					
					$item_type = $product? $product->type:'';
					$item_code = $product? $product->code:'';
					$item_name = $product->name;						
					$real_unit_price = $quote_item->real_unit_price;
					$unit_price = $quote_item->unit_price;					
					$item_quantity = $quote_item->quantity;
					
					if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {						
						$products[] = array(
											'product_id' => $quote_item->product_id,
											'product_code' => $quote_item->product_code,
											'product_name' => $quote_item->product_name,
											'product_type' => $quote_item->product_type,
											'net_unit_price' => $quote_item->net_unit_price,
											'unit_price' => $quote_item->unit_price,
											'currency_code' => $quote_item->currency_code,
											'quantity' => $quote_item->quantity,
											'subtotal' => $quote_item->subtotal,
											'real_unit_price' => $quote_item->real_unit_price,
											'color' => $quote_item->color,
											'product_year' => $quote_item->product_year,
											'engine' => $quote_item->engine,
											'frame' => $quote_item->frame,
											'power' => $quote_item->power,
											'distance_mile' => $quote_item->distance_mile,
											'description' => $quote_item->description
										);
					}
				}
            }else {
				$this->form_validation->set_rules('product', lang("product"), 'required');
			}
			/*
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }
			*/
			$quotes = $this->quotes_model->getQuoteByID($id);
			$default_currency = $this->site->get_setting();
			$approve_by = ($this->input->post('approve_by') ? $this->input->post('approve_by') : $this->session->userdata('user_id'));
			$user = $this->quotes_model->getUser($approve_by);
			//$total = $this->erp->convertCurrency($default_currency->default_currency, $quote_item->currency_code, $quotes->total);
			//$grand_totals = $this->erp->convertCurrency($default_currency->default_currency, $quote_item->currency_code, $quotes->grand_total);
			//$this->erp->print_arrays($grand_totals);
            $data = array(
							'reference_no'              => $reference,
							'customer_id'               => $quotes->customer_id,
							'customer'                  => $quotes->customer,
							'approved_date'				=> $this->erp->fld($this->input->post('app_date')),
							'biller_id'                 => $quotes->biller_id,
							'biller'                    => $quotes->biller,
							'note'                      => $quotes->note,
							'total'                     => $quotes->total,
							'product_discount'          => $quotes->product_discount,
							'order_discount_id'         => $quotes->order_discount_id,
							'order_discount'            => $quotes->order_discount,
							'total_discount'            => $quotes->total_discount,
							'product_tax'               => $quotes->product_tax,
							'order_tax_id'              => $quotes->order_tax_id,
							'order_tax'                 => $quotes->order_tax,
							'total_tax'                 => $quotes->total_tax,
							'shipping'                  => $quotes->shipping,
							'grand_total'               => '0',
							'created_by'				=> $this->session->userdata('user_id'),
							'approved_by'				=> $approve_by,
							'by_co'						=> $quotes->by_co,
							'status'              		=> $this->input->post('status'),
							'advance_percentage_payment'=> $quotes->advance_percentage_payment,
							'advance_payment'           => str_replace(',', '', $quotes->advance_payment),
							'frequency'                 => $quotes->frequency,
							'rate_type'					=> $quotes->rate_type,
							'interest_rate'             => $quotes->interest_rate,
							'rate_text'                 => $quotes->rate_text,
							'term'                      => $quotes->term,
							'principle_frequency'		=> $quotes->principle_frequency,
							'employee_id'				=> $quotes->employee_id,
							'guarantor_id'				=> $quotes->guarantor_id,
							'join_lease_id'				=> $quotes->join_lease_id,
							'join_guarantor_id'			=> $quotes->join_guarantor_id,
							'due_date'					=> $this->erp->fld($this->input->post('st_inst_date')),
							'mfi'						=> $quotes->mfi,
							'loan_group_id'				=> $quotes->loan_group_id,
							'branch_id'					=> $quotes->branch_id,
							'commission'				=> $this->input->post('commission'),
						);
			

			$quotesSaving = $this->quotes_model->getQuoteSavingQuoteID($id);
			$SaveItems = $this->quotes_model->getSaveItemBySaveID($quotesSaving->id);
			
			if($quotesSaving){
				$saving = array(
					'date'        				=> $date,
					'approved_date'				=> $this->erp->fld($this->input->post('app_date')),
					'customer_id'               => $quotesSaving->customer_id,
					'saving_rate'               => $quotesSaving->saving_rate ,
					'saving_amount'             => $quotesSaving->saving_amount,
					'saving_type'               => $quotesSaving->saving_type,
					'saving_interest_rate'      => $quotesSaving->saving_interest_rate, 
					'reference_no'          	=> $reference,
					'customer'              	=> $quotesSaving->customer,
					'status'          			=> 'saving',
					'sale_status'              	=> $this->input->post('status'),
					'created_by'				=> $this->session->userdata('user_id'),
					'by_co'						=> $quotesSaving->by_co,
					'approved_by'				=> $approve_by,
					'branch_id'					=> $quotesSaving->branch_id,
				);
				
				$saving_item = array(
						'currency_code' 	=> $SaveItems->currency_code,
						'unit_price'		=> $SaveItems->unit_price,						
						'subtotal' 			=> $SaveItems->subtotal,
						'net_unit_price'	=> $SaveItems->net_unit_price,
						'real_unit_price' 	=> $SaveItems->real_unit_price,
                );				
			}
			
			$agency = array(
				'commission_amount'	=> $this->input->post('commission'),	
			);				
			//$this->erp->print_arrays($companies);exit();
        }
        if ($this->form_validation->run() == true && $this->quotes_model->getApprovedApplicant($id, $data, $products,$quote_rejects, $agency, $saving, $saving_item)) {
            $this->session->set_userdata('remove_quls', 1);
            $this->session->set_flashdata('message', $this->lang->line("applicant") .''. $data['status']);
            redirect('quotes');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$quote = $this->quotes_model->getQuoteByID($id); 
			//$this->erp->print_arrays($quote);
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
			$product = $this->quotes_model->getProductByQuoteID($id);
			if($product){
				$this->data['currency'] = $this->site->getCurrencyByCode($product->currency_code);
			}
            $this->data['inv_items'] = json_encode(isset($pr) ?$pr  : (''));
            $this->data['id'] = $id;
			$applicant = $this->site->getCompanyByID($quote->customer_id);
			$this->data['applicants'] = $this->site->getCompanyByID($quote->customer_id);			
            //$this->data['currencies'] = $this->site->getAllCurrencies();
            $this->data['billers'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllCompanies('supplier') : null;
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['warehouses'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllWarehouses() : null;
			//$this->data['services'] = $this->site->getServicesByStatus('1');
			$services = $this->site->getServicesByStatus('1');
			$quote_services = $this->site->getQuoteServicesByQuoteID($id);
			$tax_rates = $this->site->getTaxRates();
			foreach($quote_services as $service) {
				$help = false;
				$amount = 0;
				$type = '';
				$tax_name ='';
				$service_name;
				foreach($services as $sv) {
					if($sv->id == $service->services_id) {
						$help = true;
						$amount = $service->amount;
						$type = $service->type;
						$service_name = $sv->description;
					}
				}
				foreach($tax_rates as $tax){
					if($service->tax_id == $tax->id){
						$tax_name = $tax->name;
					}
				}
				$service->description = $service_name;
				$service->amount =$amount;
				$service->checked = $help;
				$service->method = $type;
				$service->tax_name =$tax_name;
			}
			
			
			$this->data['services'] = $quote_services;
			$this->data['sale'] = $this->quotes_model->getSaleByQuoteID($id);
			$quote_employee = $this->quotes_model->getEmployeeQuoteByQuoteID($id);
			$this->data['quote_employee'] = $quote_employee;
			$guarantor = $this->quotes_model->getGuarantorByQuoteID($id);
			$this->data['guarantor'] = $guarantor;
			$this->data['quote_service'] = $this->site->getQuoteServicesByQuoteID($id);
			$join_lease = $this->site->getJoinLeaseByID($quote->join_lease_id);
			$this->data['join_lease'] = $join_lease;
			$this->data['join_guarantor'] = $this->site->getJoinGuarantorByID($quote->join_guarantor_id);			
			/* Document */
			$this->data['documents'] = $this->quotes_model->getDocumentsByQuoteID($id);
			$this->data['collateral_type'] = $this->quotes_model->getCollateralType();
			$this->data['finacal_products'] = $this->site->getAllCustomerGroup();
			$this->data['advance_percentages'] = $this->site->getAllDownPercentage();
			$this->data['interest_rates'] = $this->site->getAllInterestRate();
			$this->data['terms'] = $this->site->getAllTerm();
			$this->load->model('settings_model');
			$this->data['settings'] = $this->settings_model->getSettings($id);
			$this->data['collateral'] = $this->quotes_model->getCollateralQuoteID($id);			
			$this->data['getcollateral'] = $this->quotes_model->get_CollateralQuoteID($id);	
			$this->data['reject'] = $this->quotes_model->get_reject_reason();
			//$this->data['state_taxes'] = $this->site->getStateTaxes();
			$this->data['reject_rs'] = $this->site->getRejectByStatus('1');
			$this->data['quote_reject'] = $this->site->getQuoteRejectByQuoteID($id);
			
			$this->data['product'] = $product;
			if($product){
				$this->data['cate_detail'] = $this->site->getCategoryByID($product->category_id);
			}
			$this->data['ggggg'] = $this->quotes_model->getServicesByQuoteID($id);
			
			$this->data['qphoto'] = $this->quotes_model->getQoutePhoto($id);
			$this->data['categories'] = $this->quotes_model->getCategories();
			$this->data['variants'] = $this->site->getVariants();
			$this->data['applicant'] = $applicant;
			$this->data['field_check'] = $this->site->getFieldCheckByQuoteID($id);
			$this->data['countries'] = $this->site->getCountries();
			$this->data['quote'] = $quote;
			$this->data['total_services_charge'] = $this->quotes_model->getTotalServicesAmount($quote->id);
			if($applicant){
			$this->data['address'] = $this->site->getAddress($applicant->country, $applicant->state, $applicant->district, $applicant->sangkat, $applicant->village);
			}else{$this->data['address']='';}
			if($guarantor){
				$address_guarantor = $this->site->getAddress($guarantor->country, $guarantor->state, $guarantor->district, $guarantor->sangkat, $guarantor->village);
				if($address_guarantor) {
					$this->data['address_guarantor'] = $address_guarantor;
				}else{
					$this->data['address_guarantor'] ='';
				}
			}
			if($quote_employee){
				$emp_addr = $this->site->getAddress($quote_employee->country, $quote_employee->province, $quote_employee->district, $quote_employee->communce, $quote_employee->village);
				if($emp_addr) {	
					$this->data['address_employee'] = $emp_addr;
				}else{
					$this->data['address_employee'] ='';
				}
			}
			if($join_lease){
				$jl_addr = $this->site->getAddress($join_lease->country, $join_lease->state, $join_lease->district, $join_lease->sangkat, $join_lease->village);
				if($jl_addr) {	
					$this->data['address_join_lease'] = $jl_addr;
				}else{
					$this->data['address_join_lease'] ='';
				}
			}
			$this->data['qu_saving'] = $this->quotes_model->getQuoteSavingQuoteID($id);
			$this->data['setting'] = $this->site->get_setting();
			$this->data['users'] = $this->quotes_model->getOwnerUsers();
			$this->data['user_id'] = $this->session->userdata('user_id');
			//$this->erp->print_arrays($this->site->getAddressToString($applicant->country, $applicant->state, $applicant->district, $applicant->sangkat, $applicant->village));
			
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('quotes'), 'page' => lang('quotes')), array('link' => '#', 'page' => lang('approved_applicant')));
            $meta = array('page_title' => lang('approved_applicant'), 'bc' => $bc);
            $this->page_construct('quotes/approvedApplicant', $meta, $this->data);
        }
	}
	
    public function edit($id = null)
    {
       $this->erp->checkPermissions();
		$this->load->model('quotes_model');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $inv = $this->quotes_model->getQuoteByID($id);
		
		/*
        if (!$this->session->userdata('edit_right')) {
            $this->erp->view_rights($inv->created_by);
        }
		*/
        
        $this->form_validation->set_rules('cus_first_name', $this->lang->line("custocus_first_namemer"), 'required');
        //$this->form_validation->set_rules('note', $this->lang->line("note"), 'xss_clean');

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
            //$biller_details = $this->site->getCompanyByID($biller_id);
            //$biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
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

            # get_services
			/*$QouteServices = '';
            for($sv = 0; $sv < count($q_service); $sv ++) {
                $QouteServices[] = array(
											'services_id' => $q_service[$sv],
											'amount' => $services_amount[$sv],
											'type' => $services_type[$sv],
											'service_paid' => $services_paid[$sv],
										);
            }*/
			
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
			
			//$this->erp->print_arrays($QouteServices);
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
                            'identify'               => $this->input->post('identify_id'),
                            'gov_id'                 => $this->input->post('cus_gov_id'),
                            'name'                   => $this->input->post('cus_first_name'),
                            'name_other'             => $this->input->post('cus_first_name_other'),
                            'family_name'            => $this->input->post('cus_family_name'),
                            'family_name_other'      => $this->input->post('cus_family_name_other'),
							'father_name'            => $this->input->post('father_name'),
                            'nickname'               => $this->input->post('cus_nick_name'),
                            'spouse_name'            => $this->input->post('cus_sp_fname'),
                            'spouse_family_name'     => $this->input->post('cus_sp_fam_name'),
                            'gender'                 => $this->input->post('cus_gender'),
                            'status'                 => ($this->input->post('cus_marital_status')? $this->input->post('cus_marital_status'):$this->input->post('g_status')),
                            //'status'       			 => $this->input->post('g_status'),
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
							'created_by'			 => $this->session->userdata('user_id'),
                        );
			//$this->erp->print_arrays($customers);
			
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
           
		   $term_days = $frequency * $term;
		   
		   $data = array(
				
				
                'reference_no'              => $reference,
                'customer_id'               => $customer_id,
                'customer'                  => isset($customer) ?$customer  : (''),
                //'biller_id'                 => $biller_id,
                //'biller'                    => $biller,
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
				'status'                    => 'loans',
				'quote_status'              => $status,
				'updated_by'				=> $this->session->userdata('user_id'),
				'by_co'						=> $user_id,
				'advance_percentage_payment' => $advance_percentage_payment,
				'advance_payment'           => str_replace(',', '',$advance_payment),
				'installment_date'   		=> $this->erp->fld(trim($this->input->post('st_inst_date'))),
				'frequency'                 => $frequency,
				'rate_type'					=> $rate_type,
				'interest_rate'             => $interest_rate,
				'rate_text'					=> $rate_text,
				'term'                      => $term_days,
				'principle_frequency'		=> ($this->input->post('principle_frequency')? $this->input->post('principle_frequency') : 1),
				'branch_id'					=> $user->branch_id ,
				'mfi'						=> $mfi,
				'loan_group_id'    			=> $this->input->post('groupid'),
            );
			
			 //$this->erp->print_arrays($data);
			
			
			$saving_ = $this->input->post('saving_rate');
			
			$saving_rate = str_replace(',', '', $this->input->post('saving_rate'));				
			$saving_rate = str_replace('%', '', $saving_rate);
			$saving_rate_ = ($saving_rate/100);
			
			$saving_interest_rate = str_replace(',', '', $this->input->post('saving_interest_rate'));				
			$saving_interest = str_replace('%', '', $saving_interest_rate);
			$saving_interest_ = ($saving_interest/100);
				
			$saving_amt = str_replace(',', '', $this->input->post('saving_amount'));
			$saving_amount = $this->erp->convertCurrency($default_currency->default_currency, $currency, $saving_amt);
			
			if($saving_){
				$saving = array(
					'date'        				=> $date,
					'saving_rate'               => $saving_rate_ ,
					'saving_amount'             => $saving_amount,
					'saving_type'               => $this->input->post('saving_type'),
					'saving_interest_rate'      => $saving_interest_ ,
					'reference_no'          	=> $reference,
					'customer'              	=> $this->input->post('cus_family_name') .' '. $this->input->post('cus_first_name'),
					'status'          			=> 'saving',
					'quote_status'              => $status,
					'updated_by'				=> $this->session->userdata('user_id'),
					'by_co'			 			=> $user_id,
					'branch_id'					=> $user->branch_id,
				);
				
				$saving_item = array(
                        
						'currency_code' 	=> $this->input->post('currency'),
                        'subtotal' 			=> str_replace(',', '', $this->input->post('saving_amount')),
						'unit_price' 		=> str_replace(',', '', $this->input->post('saving_amount')),
						'net_unit_price' 	=> str_replace(',', '', $this->input->post('saving_amount')),
						'real_unit_price' 	=> str_replace(',', '', $this->input->post('saving_amount')),
                );
				
			}
			//$this->erp->print_arrays($saving);
			
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
					/*'house_no'                  => $this->input->post('emp_house_no'),
					'street'                    => $this->input->post('emp_street'),
					'country'                   => $this->input->post('emp_country'),
					'province'                 => $this->input->post('emp_province'),
					'district'                  => $this->input->post('emp_district'),
					'communce'                  => $this->input->post('emp_communce'),
					'village'                   => $this->input->post('emp_village'),*/
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
			//$this->erp->print_arrays($this->input->post('cl_code'));
			//$this->erp->print_arrays(sizeof($cl_id));
			
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
			//$this->erp->print_arrays($documentsArray);

			//$this->erp->print_arrays($join_lease, $guarantor_, $join_guarantor, $collateral);
        }
				
        if ($this->form_validation->run() == true && $this->quotes_model->updateQuotationDetails($id, $data, $QouteServices, isset($products)? $products  : (''), $employee_, $guarantor_, $documentsArray, $customers, $field_check, $collateral, $group_loan , $join_lease, $join_guarantor, $saving, $saving_item)) {
			
		 $this->session->set_userdata('remove_quls', 1);
         $this->session->set_flashdata('message', $this->lang->line("quote_saved"));
            //redirect($_SERVER['HTTP_REFERER']);
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
			$userid = $this->session->userdata('user_id');
			$user = $this->quotes_model->getUser($userid);
			$this->data['branch'] = $this->quotes_model->getBranchById($user->branch_id);
			$this->data['users'] = $this->quotes_model->getco($user->branch_id);
			$this->data['sale'] = $this->quotes_model->getSaleByQuoteID($id);
			$this->data['quote_employee'] = $this->quotes_model->getEmployeeQuoteByQuoteID($id);
			$this->data['guarantor'] = $this->quotes_model->getGuarantorByQuoteID($id);
			$this->data['quote_service'] = $quote_services;
			$this->data['join_lease'] = $this->site->getJoinLeaseByID($quote->join_lease_id);
			//$this->erp->print_arrays($this->site->getJoinLeaseByID($quote->join_lease_id));
			$this->data['join_guarantor'] = $this->site->getJoinGuarantorByID($quote->join_guarantor_id);
			//$this->erp->print_arrays($this->site->getJoinGuarantorByID($quote->join_guarantor_id));			
			/* Document */
			$this->data['documents'] = $this->quotes_model->getDocumentsByQuoteID($id);			
			$this->data['finacal_products'] = $this->site->getAllCustomerGroup();
			$this->data['advance_percentages'] = $this->site->getAllDownPercentage();
			$this->data['interest_rates'] = $this->site->getAllInterestRate();
			$this->data['terms'] = $this->site->getAllTerm();
						
			$this->data['category'] = $this->quotes_model->getCategory();
			$this->data['setting'] = $this->quotes_model->get_setting();
			$this->data['qu_saving'] = $this->quotes_model->getQuoteSavingQuoteID($id);
			
			//$this->data['collateral'] = $this->quotes_model->getCollateralQuoteID($id);
			$this->data['collaterals'] = $this->quotes_model->get_CollateralQuoteID($id);
			$this->data['qphoto'] = $this->quotes_model->getQoutePhoto($id);			
			/*$this->data['land'] = $this->quotes_model->getCollateralQuoteID_land($id);
			$this->data['home'] = $this->quotes_model->getCollateralQuoteID_home($id);
			$this->data['vehicles'] = $this->quotes_model->getCollateralQuoteID_vehicles($id);*/
			$this->data['product'] = $this->quotes_model->getProductByQuoteID($id);
			//$this->data['loan_group'] = $this->quotes_model->getProductByQuoteID($id);			
			//$this->erp->print_arrays($loan_group);
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
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('quotes'), 'page' => lang('quotes')), array('link' => '#', 'page' => lang('edit_quote')));
            $meta = array('page_title' => lang('edit_quote'), 'bc' => $bc);
            $this->page_construct('quotes/edit', $meta, $this->data);
        }
    }
    public function delete($id = null)
    {
        $this->erp->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->quotes_model->deleteQuote($id)) {
            if ($this->input->is_ajax_request()) {
                echo lang("quote_deleted");die();
            }
            $this->session->set_flashdata('message', lang('quote_deleted'));
            redirect('welcome');
        }
    }

    public function suggestions()
    {
        $term = $this->input->get('term', true);
        $warehouse_id = $this->input->get('warehouse_id', true);
        $customer_id = $this->input->get('customer_id', true);

        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . site_url('welcome') . "'; }, 10);</script>");
        }

        $spos = strpos($term, '%');
        if ($spos !== false) {
            $st = explode("%", $term);
            $sr = trim($st[0]);
            $option = trim($st[1]);
        } else {
            $sr = $term;
            $option = '';
        }
        $customer = $this->site->getCompanyByID($customer_id);
        $customer_group = $this->site->getCustomerGroupByID($customer->customer_group_id);
        $rows = $this->quotes_model->getProductNames($sr, $warehouse_id);
        if ($rows) {
            foreach ($rows as $row) {
                $option = false;
                $row->quantity = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty = 1;
                $row->discount = '0';
                $options = $this->quotes_model->getProductOptions($row->id, $warehouse_id);
                if ($options) {
                    $opt = $options[0];
                    if (!$option) {
                        $option = $opt->id;
                    }
                } else {
                    $opt = json_decode('{}');
                    $opt->price = 0;
                }
                $row->option = $option;
                $pis = $this->quotes_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if ($pis) {
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->quotes_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
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
                if ($opt->price != 0) {
                    $row->price = $opt->price + (($opt->price * $customer_group->percent) / 100);
                } else {
                    $row->price = $row->price + (($row->price * $customer_group->percent) / 100);
                }
                $row->real_unit_price = $row->price;
                $combo_items = false;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    if ($row->type == 'combo') {
                        $combo_items = $this->quotes_model->getProductComboItems($row->id, $warehouse_id);
                    }
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options);
                } else {
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options);
                }
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }
	
   
	
	public function quote_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true){
			//condition check or no check export file
            if (!empty($_POST['val']) || empty($_POST['val'])) {
				
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                       $this->quotes_model->deleteQuote($id);
						
                    }
                    $this->session->set_flashdata('message', $this->lang->line("convert_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
				if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {
					
                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('quotes'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('customer_name_(en)'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('customer_name_(kh)'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('asset'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('status'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('submit_date'));
					$this->excel->getActiveSheet()->SetCellValue('G1', lang('approved_date'));
					$this->excel->getActiveSheet()->SetCellValue('H1', lang('c.o_name'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('branch'));
					$this->excel->getActiveSheet()->SetCellValue('J1', lang('total'));
                    $row = 2;
					
                    foreach ($_POST['val'] as $id) {
                        $qu = $this->quotes_model->getApplicantByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $qu->reference_no);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $qu->customer_name_en);
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $qu->customer_name_kh);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $qu->asset);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $qu->status);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $qu->submit_date);
						$this->excel->getActiveSheet()->SetCellValue('G' . $row, $qu->approved_date);
						$this->excel->getActiveSheet()->SetCellValue('H' . $row, $qu->co_name);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $qu->name);
						$this->excel->getActiveSheet()->SetCellValue('J' . $row, $qu->grand_total);							
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
					$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
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
	
	
	public function add_employee(){
		$this->erp->checkPermissions();

        $this->form_validation->set_rules('position', $this->lang->line("position"), 'required');
        $this->form_validation->set_rules('employment_status', $this->lang->line("employment_status"), 'required');
        //$this->form_validation->set_rules('note', $this->lang->line("note"), 'xss_clean');

        if ($this->form_validation->run() == true) {
			$data = array(
				'group_id' => '10',
				'group_name' => 'employee',
				'position' => $this->input->post('position'),
				'emp_status' => $this->input->post('position'),
				'emp_industry' => $this->input->post('employment_industry'),
				'siniority_level' => $this->input->post('seniorities_level'),
				'phone1' => $this->input->post('work_phone'),
				'allow_call' => $this->input->post('allow_call_to_work_place'),
				'years' => $this->input->post('years'),
				'months' => $this->input->post('months'),
				'salary' => $this->input->post('basic_salary'),
				'allowance' => $this->input->post('allowance_etc'),
				'exspense' => $this->input->post('business_expense'),
				'house_no' => $this->input->post('house_no'),
				'street' => $this->input->post('street'),
				'country' => $this->input->post('country'),
				'state' => $this->input->post('province'),
				'district' => $this->input->post('district'),
				'sangkat' => $this->input->post('commune'),
				'village' => $this->input->post('village'),
			);
			
			//$this->erp->print_arrays($data);
			
			if($this->quotes_model->addEmployee($data)){
				redirect($_SERVER['HTTP_REFERER']);
			}
		}else{
			
		}
	}
	
	public function addComment(){
		$quote_id = '';
		$comment = '';
		
		if($this->input->get('quote_id') && $this->input->get('comment')){
			$quote_id = $this->input->get('quote_id');
			$comment = $this->input->get('comment');
			$comment_by = $this->session->userdata('user_id');
			
			$data = array(
				'quote_id' => $quote_id,
				'comment'	=> $comment,
				'comment_by' => $comment_by,
				'date' => date('Y-m-d H:i:s')
			);

			$result = $this->quotes_model->addComment($quote_id, $data);
			if($result){
				echo true;
			}else{
				echo FALSE;
			}
		}else{
			echo FALSE;
		}
	}
	
	public function ajaxGetComments($quote_id){
		if($quote_id){
			$result = $this->quotes_model->getCommentsByQuoteID($quote_id);
			if($result){
				echo json_encode($result);
			}else{
				die(FALSE);
			}
		}
	}
	 public function fields_check()
    {
        $this->erp->checkPermissions('index', true);
		$ref = $this->quotes_model->allQuoteRef();
		$this->data['ref']=$ref;
	
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('fields_check'), 'page' => lang('fields_check')), array('link' => '#', 'page' => lang('fields_check')));
            $meta = array('page_title' => lang('fields_check'), 'bc' => $bc);
            $this->page_construct('quotes/fields_check', $meta, $this->data);
        
    }
	
	
	public function getQuotesFive($warehouse_id = null,$limit=5)
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
        $detail_link = anchor('quotes/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('quote_details'));
        $email_link = anchor('quotes/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_quote'), 'data-toggle="modal" data-target="#myModal"');
        //$edit_link = anchor('quotes/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_quote'));
        $convert_link = anchor('sales/add/$1', '<i class="fa fa-heart"></i> ' . lang('create_sale'));
        $pc_link = anchor('purchases/add/$1', '<i class="fa fa-star"></i> ' . lang('create_purchase'));
        $pdf_link = anchor('quotes/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_quote") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('quotes/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_quote') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>' . $detail_link . '</li>
                        <li>' . $edit_link . '</li>
                        <li>' . $convert_link . '</li>
                        <li>' . $pc_link . '</li>
                        <li>' . $pdf_link . '</li>
                        <li>' . $email_link . '</li>
                        <li>' . $delete_link . '</li>
                    </ul>
                </div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

        $this->load->library('datatables');
         if ($warehouse_id) {
            $this->datatables
                ->select($this->db->dbprefix('quotes').".id,".
						$this->db->dbprefix('quotes').".reference_no,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name_en,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_kh, ".
						$this->db->dbprefix('quote_items').".product_name AS asset, ".
						$this->db->dbprefix('quotes').".biller,".
						$this->db->dbprefix('quotes').".quote_status,".
						$this->db->dbprefix('quotes').".date,".
						"(SELECT u.username FROM erp_users u WHERE quotes.updated_by = u.id) AS underwriter,".
						$this->db->dbprefix('sales').".issue_date,".
						$this->db->dbprefix('users').".username,".
						$this->db->dbprefix('quotes').".grand_total,")
                ->from('quotes')
				
				->join('users','quotes.created_by=users.id','INNER')
				->join('sales', 'sales.quote_id = quotes.id', 'left')
				->join('companies','quotes.customer_id=companies.id','INNER')
				->join('quote_items', 'quotes.id = quote_items.quote_id', 'left')
				
				->where('erp_quotes.status', 'loans');
				$this->datatables->order_by('quotes.date', 'DESC');
				
        } else {
            $this->datatables
                ->select($this->db->dbprefix('quotes').".id,".
						$this->db->dbprefix('quotes').".reference_no,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) AS customer_name_en,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_kh, ".	
						$this->db->dbprefix('quote_items').".product_name AS asset,".
						$this->db->dbprefix('quotes').".biller,".
						
						$this->db->dbprefix('quotes').".quote_status,".
						$this->db->dbprefix('quotes').".date,".
						"COALESCE((SELECT u.username FROM erp_users u WHERE erp_quotes.updated_by = u.id), '') AS underwriter,".
						$this->db->dbprefix('sales').".issue_date,".
						$this->db->dbprefix('users').".username,".	
						
						$this->db->dbprefix('quotes').".grand_total,")
                ->from('quotes')
				
				->join('users','quotes.created_by=users.id','INNER')
				->join('sales', 'sales.quote_id = quotes.id', 'left')
				->join('companies','quotes.customer_id=companies.id','INNER')
				->join('quote_items', 'quotes.id = quote_items.quote_id', 'left');
				$this->datatables->order_by('quotes.date DESC');
				
        }
		
		if ($product_id) {
			$this->datatables->join('quote_items', 'quote_items.quote_id = quotes.id', 'left');
			$this->datatables->where('quote_items.product_code', $product_id);
		}
		
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            //$this->datatables->where('quotes.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			//$this->datatables->where('quotes.created_by', $this->session->userdata('user_id'));
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
	
	function getProvinces($id = NULL)
    {
        if ($rows = $this->site->getProvincesByCountyID($id)) {
            $data = json_encode($rows);
        } else {
            $data = false;
        }
        echo $data;
    }
	
	function getDistricts($id = NULL)
    {
        if ($rows = $this->site->getDistrictByProvinceID($id)) {
            $data = json_encode($rows);
        } else {
            $data = false;
        }
        echo $data;
    }
	
	function getCommunces($id = NULL)
    {
        if ($rows = $this->site->getCommuncesByDistrictID($id)) {
            $data = json_encode($rows);
        } else {
            $data = false;
        }
        echo $data;
    }
	
	function getVillages($id = NULL)
	{
		if ($rows = $this->site->getVillagesByCommunceID($id)) {
            $data = json_encode($rows);
        } else {
            $data = false;
        }
        echo $data;
	}
	public function insert_fields_check(){
		$field_check = array(
								'quote_id'                 => ($this->input->post('applicant')),
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
								'latitude'              => $this->input->post('latitude_'),
								'longitude'             => $this->input->post('longtitute_'),
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
								
								$i=$this->quotes_model->insertFieldCheck($field_check);
								if($i){
									redirect('quotes');
								}					
	}
	
	function getExistingGovIDInfo()
	{
		$gov_id = $this->input->get('gov_id', true);
		$customer_info = $this->quotes_model->getCustomerByGovID($gov_id);
		if($customer_info) {
			echo json_encode(array('id' => $customer_info->id, 'family_name' => $customer_info->family_name, 'name' => $customer_info->name));
		} else {
			echo json_encode(array('id' => false));
		}
	}
	function gov_id_report($id=NULL){
			$this->erp->checkPermissions();
			$this->load->model('companies_model');
			$info= $this->companies_model->getCustomerLoans($id);
			if($info){
				$this->data['customer_loan']=$info;
			}
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['modal_js'] = $this->site->modal_js();
			$this->load->view($this->theme . 'customers/view_details', $this->data);
	}

	/*function gov_id_report($id=NULL){
	
			$this->erp->checkPermissions();
			$info= $this->quotes_model->getCustomerInfoByGovId($id);
			if($info){
				$this->data['info']=$info;
			}				
			$applicant= $this->quotes_model->getApplicantInfoByGovId($info->id);
			if($applicant){
				$this->data['applicant']=$applicant;
			}		
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'quotes/gov_id_report', $this->data); 
	}*/
	public function cash_payment_schedule_preview($lease_amount=NULL,$rate_type=NULL,$interest_rate=NULL,$term=NULL, $frequency = NULL, $currency = NULL, $cdate = NULL, $principle_fq = NULL, $services = NULL, $saving_amount = NULL, $saving_interest_rate = NULL, $saving_type = NULL){
		//$this->erp->print_arrays($saving_rate, $saving_amount, $saving_interest_rate, $saving_type);
		$this->erp->checkPermissions('index',true,'quotes');
		$this->load->model('quotes_model');
		
		$arr_services = explode('___', $services);
		$arr_service = array();
		$str_ids = '';
		 
		foreach($arr_services as $service) {  
			$sv = explode('__', $service);
			if($str_ids == '') {
				$str_ids = $sv[0];
			}else {
				$str_ids .= '###'. $sv[0];
			}
			$arr_service[] = array(
								'id'	 => $sv[0],
								'amount' => $sv[1],
								'status' => $sv[2],
								'service_paid' => $sv[3],
								'charge_by' => $sv[4],
								'tax_rate' => $sv[5]
							);		
		}
		
		//$this->erp->print_arrays($arr_service);
		$ids = explode('###', $str_ids);
		$this->data['services'] = $arr_service;	
		$this->data['amount'] = $lease_amount;
		$this->data['stcurrency'] = $this->quotes_model->getSettingCurrncy();
		$this->data['currency'] = $this->site->getCurrencyByCode($currency);
		$this->data['get_service']  = $this->quotes_model->getServices($ids);		
		$app_date = date('m/d/Y h:i:s a', time());
		$this->data['rate_type'] = $rate_type;
		$this->data['interest_rate'] = $interest_rate;
		$this->data['term'] = $term;
		$this->data['frequency'] = $frequency;
		$this->data['currency_id'] = $currency;
		$this->data['principle_fq'] = $principle_fq;
		$date = str_replace('___', '/', $cdate);
		$new_date = $this->erp->fld($date); 
		//$this->data['collateraltype'] = $this->quotes_model->get_CollateralType($id);	
		$this->data['setting'] = $this->site->get_setting();		
		$this->data['pts'] = $this->erp->getPaymentSchedule('1', $lease_amount, $rate_type, $interest_rate, $term, $frequency, $new_date ,$app_date, $currency, $principle_fq, $saving_amount, $saving_interest_rate, $saving_type);
		$this->data['all'] = $this->erp->getAllTotal($lease_amount, $rate_type, $interest_rate, $term, $frequency, $principle_fq);	
		$this->data['modal_js'] = $this->site->modal_js();
        $this->load->view($this->theme.'installment_payment/cash_payment_schedule_preview',$this->data);
	}	
	
	public function cash_payment_schedule_applicant($lease_amount=NULL,$rate_type=NULL,$interest_rate=NULL,$term=NULL, $frequency = NULL, $currency = NULL,$id = NULL, $cdate = NULL,$app_date = NULL, $principle_fq = NULL, $services = NULL, $saving_amount = NULL, $saving_interest_rate = NULL, $saving_type = NULL){
		$this->erp->checkPermissions('index',true,'quotes');
		$this->load->model('quotes_model');
		
		$this->data['services'] = $this->quotes_model->getQuoteServices($id);
		//$this->erp->print_arrays($id);
		$this->data['amount'] = $lease_amount;
		$this->data['stcurrency'] = $this->quotes_model->getSettingCurrncy();
		$this->data['cuscurrency'] = $this->site->getCurrencyByCode($currency);
		$this->data['rate_type'] = $rate_type;
		$this->data['interest_rate'] = $interest_rate;
		$this->data['term'] = $term;
		$this->data['frequency'] = $frequency;
		$this->data['currency_id'] = $currency;
		$this->data['setting'] = $this->site->get_setting();
		$this->data['currency'] = $this->site->getCurrencyByCode($currency);
		$this->data['product'] = $this->quotes_model->getProductByQuoteID($id);
		//$this->data['idd'] = $id;
		$this->data['principle_fq'] = $principle_fq;
		$date = str_replace('___', '/', $cdate);
		$new_date = $this->erp->fld($date);
		//$this->erp->print_arrays($id);
		$quote = $this->quotes_model->getQuoteByID($id);			
        $this->data['inv'] = $quote;
		$applicant = $this->site->getCompanyCOName($quote->customer_id);
		$this->data['applicant'] = $applicant;

		$this->data['users'] = $this->quotes_model->getUserQuoteByID($id);
		$this->data['pts'] = $this->erp->getPaymentSchedule('1', $lease_amount, $rate_type, $interest_rate, $term, $frequency, $cdate ,$app_date, $currency, $principle_fq, $saving_amount, $saving_interest_rate, $saving_type);
		$this->data['all'] = $this->erp->getAllTotal($lease_amount, $rate_type, $interest_rate, $term, $frequency, $principle_fq);		
		$this->data['modal_js'] = $this->site->modal_js();
        $this->load->view($this->theme.'installment_payment/cash_payment_schedule_applicant',$this->data);
	}
	
	
	function add_collateral($id = NULL)
    {	$this->load->model('quotes_model');
        $this->erp->checkPermissions('payments', true);
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('cl_code', lang("cl_code"), 'required');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else{
                $date = date('Y-m-d H:i:s');
            }			
			$code = $this->site->getReference('cl');
			
            $data = array(
				'quote_id'					=> $id,
				'code'          			=> $code,
				'card_no'					=> $this->input->post('cl_card_number'),
				'cl_type'                  	=> $this->input->post('cl_type'),
				'type'                      => ($this->input->post('cl_home_type')?$this->input->post('cl_home_type'):$this->input->post('cl_land_type'))?:$this->input->post('vcl_vehicles_type'),
				'size'						=> ($this->input->post('cl_land_size')? $this->input->post('cl_land_size') : $this->input->post('cl_home_size')),
				'owner_name'                => ($this->input->post('home_owner_name')?$this->input->post('home_owner_name'):$this->input->post('land_owner_name'))?:$this->input->post('vcl_owner_name'),
				'adj_north'					=> $this->input->post('cl_north'),
				'adj_south'					=> $this->input->post('cl_south'),
				'adj_east'					=> $this->input->post('cl_east'),
				'adj_west'					=> $this->input->post('cl_west'),
				'roof'						=> $this->input->post('cl_roof'),
				'wall'						=> $this->input->post('cl_wall'),
				'address'					=> ($this->input->post('cl_land_address')? $this->input->post('cl_land_address') : $this->input->post('cl_home_address')),
				'color'						=> $this->input->post('vcl_color'),
				'power'						=> $this->input->post('vcl_power'),
				'engine_no'					=> $this->input->post('vcl_engine_no'),
				'plaque_no'					=> $this->input->post('vcl_plaque_no'),					
				'brand'						=> $this->input->post('vcl_brand'),
				'frame_no'					=> $this->input->post('vcl_frame_no'),    
				'issue_date'          	 => $this->erp->fld(trim(($this->input->post('home_issue_date')?$this->input->post('home_issue_date'):$this->input->post('land_issue_date'))?:$this->input->post('vcl_issue_date'))),
            );
			//$this->erp->print_arrays($data);
        } elseif ($this->input->post('add_collateral')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }


        if ($this->form_validation->run() == true && $this->quotes_model->AddCollateral($data)) {
            $this->session->set_flashdata('message', lang("collateral_added"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['collateral_type'] = $this->quotes_model->getCollateralType();
			$this->data['reference_cl'] = $this->site->getReference('cl');
			$this->data['id'] = $id;
            $this->load->view($this->theme . 'quotes/add_collateral', $this->data);
        }
    }
	
	public function add_applicant($id = NULL)
    {	//echo ($id); die();
        $this->erp->checkPermissions();
		if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }        
        $this->form_validation->set_rules('cus_gov_id', $this->lang->line("cus_gov_id"), 'required');
		$this->form_validation->set_rules('price', $this->lang->line("amount"), 'required');
		$this->form_validation->set_rules('currency', $this->lang->line("currency"), 'required');
        if ($this->form_validation->run() == true) {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
			$pr_item_tax = 0;
			
			$dealer_code = $this->input->post('biller');
			
            $reference = $this->site->getReference('qu');
            if ($this->Owner || $this->Admin) {
                $date = date('Y-m-d H:i:s');
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
            $biller_id = $this->input->post('biller');
            $status = $this->input->post('status');
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
            $customer = isset($customer_details->family_name) ? $customer_details->family_name  : (('') . ' ' . (isset($customer_details->name) ? $customer_details->name  : ''));
            $biller_details = $this->site->getCompanyByID($biller_id);
            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $note = $this->erp->clear_tags($this->input->post('note'));
            $q_service  = $this->input->post('ch_services');
			//$services_amount  = $this->input->post('h_service');
			$mfi = $this->input->post('mfi');
			if($this->input->post('customer_type')) {
				$customer_group = $this->input->post('customer_type');
			}else {
				$customer_group = $this->input->post('financial_product');
			}
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

            # get services
            /*for($sv = 0; $sv < count($q_service); $sv ++) {
                $QouteServices[] = array(
											'services_id' => $q_service[$sv],
											'amount' => $services_amount[$sv],
										);
            }*/
			
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
			
			//$this->erp->print_arrays($QouteServices);
            $i = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;
			
            if($_POST['product_id']){
                $item_id = $_POST['product_id'];
				
				$product = $this->site->getProductByID($item_id);
				
                $item_type = $product->type;
                $item_code = $product->code;
                $item_name = $product->name;
                
                $real_unit_price = $this->erp->formatDecimal($_POST['price']);
				
                //$unit_price = $this->erp->formatDecimal(str_replace(',', '', $_POST['price']));
				$unit_price = $this->erp->formatDecimal($_POST['price']);
                $item_quantity = 1;
				
                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->quotes_model->getProductByCode($item_code) : null;
                    $unit_price = $real_unit_price;

                    $product_tax += $pr_item_tax;
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
						'description' => $this->input->post('ldescription')
                    );
                    $total += $unit_price * $item_quantity;
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
			//$quotes = $this->quotes_model->getQuoteByID($id);
            $customers = array('group_id'            => '3',
							'group_name'             => 'customer',
							'customer_group_id'      => $customer_group,
							'customer_group_name'    => isset($cg->name) ?$cg->name  : (''),
							'identify'				 =>$this->input->post('identify_id'),
							'gov_id'                 => $this->input->post('cus_gov_id'),
							'name'                   => $this->input->post('cus_first_name'),
							'name_other'             => $this->input->post('cus_first_name_other'),
							'family_name'            => $this->input->post('cus_family_name'),
							'family_name_other'      => $this->input->post('cus_family_name_other'),
							'father_name'            => $this->input->post('father_name'),
							'nickname'               => $this->input->post('cus_nick_name'),
                            'spouse_name'            => $this->input->post('cus_sp_fname'),
 							'spouse_family_name'     => $this->input->post('cus_sp_fam_name'),
							'gender'                 => $this->input->post('cus_gender'),
							'status'                 => ($this->input->post('cus_marital_status')?$this->input->post('cus_marital_status'):$this->input->post('g_status')),
							//'status'       			 => $this->input->post('g_status'),
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
							'spouse_birthdate' 		 => $this->erp->fld(trim($this->input->post('sp_date_of_birth'))),
							'loan_group_id'    		 => $this->input->post('groupid'),
							'created_by'			 => $this->session->userdata('user_id'),
						);
			//$this->erp->print_arrays($customers);
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
			}
			//$this->erp->print_arrays($join_lease);
			
			//$quot = $this->quotes_model->getProductByQuoteID($id);
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
			
			$term_days = $frequency * $term;
            $data = array('date'        => $date,
                'reference_no'          => $reference,
				'customer_group'        => $customer_group,
                'customer'              => $this->input->post('cus_family_name') .' '. $this->input->post('cus_first_name'),
                'biller_id'             => $biller_id,
                'biller'                => $biller,
                'note'                  => ($note ? $note : $this->input->post('purpose')),
                'total'                 => $qtotal,
                'product_discount'      => $product_discount,
                'order_discount_id'     => $order_discount_id,
                'order_discount'        => $order_discount,
                'total_discount'        => $total_discount,
                'product_tax'           => $product_tax,
                'order_tax_id'          => $order_tax_id,
                'order_tax'             => $order_tax,
                'total_tax'             => $total_tax,
                'shipping'              => $shipping,
                'grand_total'           => $grand_totals,
				'quote_status'          => $this->input->post('status'),
				'status'          		=> 'loans',
				'created_by'			=> $this->session->userdata('user_id') ,
				'by_co'					=> $user_id,
				'installment_date'   	=> $this->erp->fld(trim($this->input->post('st_inst_date'))),
				'advance_percentage_payment' => $advance_percentage_payment,
				'advance_payment'       => str_replace(',', '', $advance_payment),
				'frequency'             => $frequency,
				'rate_type'				=> $rate_type,
				'interest_rate'         => $interest_rate,
				'rate_text'				=> $rate_text,
				'term'                  => $term_days, 
				'principle_frequency'	=> ($this->input->post('principle_frequency')? $this->input->post('principle_frequency') : 1),
				'mfi'					=> $mfi,
				'branch_id'				=> $user->branch_id,
				'loan_group_id'    		=> $this->input->post('groupid'),
            );
			//$this->erp->print_arrays($data);
			
			$saving_ = $this->input->post('saving_rate');			
			$saving_rate = str_replace(',', '', $this->input->post('saving_rate'));				
			$saving_rate = str_replace('%', '', $saving_rate);
			$saving_rate_ = ($saving_rate/100);
			
			$saving_interest_rate = str_replace(',', '', $this->input->post('saving_interest_rate'));				
			$saving_interest = str_replace('%', '', $saving_interest_rate);
			$saving_interest_ = ($saving_interest/100);
				
			$saving_amt = str_replace(',', '', $this->input->post('saving_amount'));
			$saving_amount = $this->erp->convertCurrency($default_currency->default_currency, $currency, $saving_amt);
			if($saving_){
				$saving = array(
					'date'        				=> $date,
					'saving_rate'               => $saving_rate_ ,
					'saving_amount'             => $saving_amount,
					'saving_type'               => $this->input->post('saving_type'),
					'saving_interest_rate'      => $saving_interest_ ,
					'reference_no'          	=> $reference,
					'customer'              	=> $this->input->post('cus_family_name') .' '. $this->input->post('cus_first_name'),
					'status'          			=> 'saving',
					'quote_status'          	=> $this->input->post('status'),
					'created_by'				=> $this->session->userdata('user_id'),
					'by_co'			 			=> $user_id,
					'branch_id'					=> $user->branch_id,
				);
				
				$saving_item = array(
                        
						'currency_code' 	=> $this->input->post('currency'),
                        'subtotal' 			=> str_replace(',', '', $this->input->post('saving_amount')),
						'unit_price' 		=> str_replace(',', '', $this->input->post('saving_amount')),
						'net_unit_price' 	=> str_replace(',', '', $this->input->post('saving_amount')),
						'real_unit_price' 	=> str_replace(',', '', $this->input->post('saving_amount')),
                );
				
			}
			//$this->erp->print_arrays($saving);
			
            #data of employee
			$employee_ = '';
			if($this->input->post('position') || $this->input->post('work_place_name') || $this->input->post('basic_salary') || $this->input->post('emp_province')) {
				$employee_   = array(
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
            #data of guarantor for loaner
			$guarantor_ = '';
			if($this->input->post('gov_id') || $this->input->post('first_name') || $this->input->post('family_name') || $this->input->post('dob') || $this->input->post('province')) {
				$guarantor_ = array( 'group_name'	 =>'guarantor',					
					'identify'					=>$this->input->post('gr_identify_id'),
					'gov_id'					=>$this->input->post('gov_id'),
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
									'gov_id'					=>$this->input->post('gov_id2'),
									'name'                      => $this->input->post('gt_name2'),
									'gender'                    => $this->input->post('gender2'),
									'date_of_birth'             => $this->erp->fld(trim($this->input->post('dob2'))),
									'age'						=> $this->input->post('age2'),
									'address'                   => $this->input->post('gl_2_address'),
									'phone1'                    => $this->input->post('phone_2'),
									'job'						=> $this->input->post('j_job_2'),
									'issue_by'         			=> $this->input->post('gr2_issue_by'),
									'status'         		 => $this->input->post('g_status_2'),
									'issue_date'          	 	=> $this->erp->fld(trim($this->input->post('gr2_issue_date'))),
								);
			}
			/*$group_loan = '';
				$group_loan = array(
					'name' =>  $this->input->post('group_loans')
			);*/
			
			$group_loan = '';
				if ($this->input->post('group_loans')){
					$group_loan = array(
						'name' =>  $this->input->post('group_loans')
					);
				}
				
			$collateral = '';			
			if($this->input->post('cl_type')) {
				$collateral= array(
					'code'                  	=> $this->input->post('cl_code'),
					'card_no'					=> $this->input->post('cl_card_number'),
					'cl_type'                  	=> $this->input->post('cl_type'),
					'type'                      => ($this->input->post('cl_home_type')?$this->input->post('cl_home_type'):$this->input->post('cl_land_type'))?:$this->input->post('vcl_vehicles_type'),
					'size'						=> ($this->input->post('cl_land_size')? $this->input->post('cl_land_size') : $this->input->post('cl_home_size')),
					'owner_name'                => ($this->input->post('home_owner_name')?$this->input->post('home_owner_name'):$this->input->post('land_owner_name'))?:$this->input->post('vcl_owner_name'),					
					'adj_north'					=> $this->input->post('cl_north'),
					'adj_south'					=> $this->input->post('cl_south'),
					'adj_east'					=> $this->input->post('cl_east'),
					'adj_west'					=> $this->input->post('cl_west'),
					'roof'						=> $this->input->post('cl_roof'),
					'wall'						=> $this->input->post('cl_wall'),
					'address'					=> ($this->input->post('cl_land_address')? $this->input->post('cl_land_address') : $this->input->post('cl_home_address')),
					'color'						=> $this->input->post('vcl_color'),
					'power'						=> $this->input->post('vcl_power'),
					'engine_no'					=> $this->input->post('vcl_engine_no'),
					'plaque_no'					=> $this->input->post('vcl_plaque_no'),					
					'brand'						=> $this->input->post('vcl_brand'),
					'frame_no'					=> $this->input->post('vcl_frame_no'),					
					'issue_date'          	 => $this->erp->fld(trim(($this->input->post('home_issue_date')?$this->input->post('home_issue_date'):$this->input->post('land_issue_date'))?:$this->input->post('vcl_issue_date'))),
					
				);
			}
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
									'latitude'              => $this->input->post('latitude_'),
									'longitude'             => $this->input->post('longtitute_'),
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
			
			if(isset($_POST['print_payment_schedule']) && $_POST['print_payment_schedule'] == lang('print_payment_schedule')){
			echo "Code for print_payment_schedule button"; 
				}
			
			
			//$this->erp->print_arrays($data, $join_lease, $join_guarantor, $guarantor_);
            // documents menu
            $documentsArray = [];
            if ( $_FILES['current_address']|| 
                $_FILES['family_book'] || 
                $_FILES['ganervment_id'] || 
                $_FILES['house_photo'] || 
                $_FILES['store_photo'] || 
                $_FILES['employment_certificate'] || 
                $_FILES['applicant_photo'] || 
                $_FILES['spouse_photo'] || 
                $_FILES['gurantors_photo'] || 
                $_FILES['birth_registration_letter'] || 
                $_FILES['passport'] || 
                $_FILES['marriage_certificate'] || 
                $_FILES['driver_license'] || 
                $_FILES['working_contract'] || 
                $_FILES['invoice_salary'] || 
                $_FILES['business_certificate'] || 
                $_FILES['profit_for_the_last_3_month'] || 
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

			//$this->erp->print_arrays($join_lease, $join_guarantor, $collateral);
        }

        if ($this->form_validation->run() == true && $q_id=$this->quotes_model->addApplicant($data, isset($products) ?$products  : (''), isset($QouteServices) ?$QouteServices  : (''), $guarantor_, $employee_, $documentsArray, $customers, $field_check, $collateral, $join_lease, $join_guarantor, $saving, $saving_item)) {
            $this->session->set_userdata('remove_quls', 1);
            $this->session->set_flashdata('message', $this->lang->line("quote_added"));
			redirect('quotes/edit/'.$q_id);
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$quote = $this->quotes_model->getQuoteByID($id);			
            $this->data['inv'] = $quote;
			//$this->erp->print_arrays($inv);
            //$this->data['customers'] = $this->site->getAllCompanies('customer');
			
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
			$this->data['services'] = $services;
			$userid = $this->session->userdata('user_id');
			$user = $this->quotes_model->getUser($userid);
			$this->data['branch'] = $this->quotes_model->getBranchById($user->branch_id);
			$this->data['users'] = $this->quotes_model->getco($user->branch_id);
            $this->data['billers'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllCompanies('supplier') : null;
            //$this->data['currencies'] = $this->site->getAllCurrencies();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['warehouses'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllWarehouses() : null;
            $this->data['qunumber'] = ''; //$this->site->getReference('qu');
			//$this->data['services'] = $this->site->getServicesByStatus('1');
			//$this->data['group_loan'] = $this->quotes_model->getGroupLoan('1');
			$this->data['finacal_products'] = $this->site->getAllCustomerGroup();
			$this->data['advance_percentages'] = $this->site->getAllDownPercentage();
			$this->data['interest_rates'] = $this->site->getAllInterestRate();
			$this->data['terms'] = $this->site->getAllTerm();
			$this->data['variants'] = $this->site->getVariants();
			$this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
			$this->data['countries'] = $this->site->getCountries();
			$this->data['applicant'] = $this->site->getCompanyByID($id);
			$this->data['setting']	= $this->quotes_model->get_setting();
			$this->data['qu_saving'] = $this->quotes_model->getQuoteSavingQuoteID($id);
			//$this->data['gov_id'] = $this->quotes_model->GetGovID();
			$this->data['customers'] = $this->quotes_model->getGovID();
			$this->data['products'] = $this->quotes_model->getProducts();
			$this->data['product'] = $this->quotes_model->getProductByQuoteID($id);
			$this->data['categories'] = $this->quotes_model->getCategories();
			$this->data['currencies'] = $this->site->getCurrency();
			$this->data['collateral_type'] = $this->quotes_model->getCollateralType();
			$this->data['identify_type'] = $this->quotes_model->getIdentifyType();
			$this->data['identify_name'] = $this->quotes_model->getIdentifyTypeName(companies_id);
			$this->data['category'] = $this->quotes_model->getCategory();
			$this->data['cus'] = $this->quotes_model->getQuoteByID($id);
			$this->data['tax_rate'] = $this->site->getAllTaxes();
			$this->data['reference_cl'] = $this->site->getReference('cl');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('quotes'), 'page' => lang('quotes')), array('link' => '#', 'page' => lang('add_group_applicant')));
            $meta = array('page_title' => lang('add_group_applicant'), 'bc' => $bc);
            $this->page_construct('quotes/add_applicant', $meta, $this->data);
			
		
        }
    }
	
	public function loan_rejected($warehouse_id = null)
    {
        $this->erp->checkPermissions('index',true);
		//$this->permission['reports-back_office']='';
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->erp->load->model('reports_model');
		$this->data['users'] = $this->reports_model->getStaff();
		$this->data['products'] = $this->site->getProducts();
		$this->data['dealer'] = $this->site->getAllDealer('supplier');
        if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : null;
        } else {
            $this->data['warehouses'] = null;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : null;
        }
		if ($this->permission['reports-back_office']){
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('contracts')));
			$meta = array('page_title' => lang('contracts'), 'bc' => $bc);
		}else{
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('loan_rejected')));
			$meta = array('page_title' => lang('loan_rejected'), 'bc' => $bc);
		}     
        $this->page_construct('quotes/loan_rejected', $meta, $this->data);
		
    }
	public function getQuotesRejected($warehouse_id = null)
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
        $approve_link = anchor('quotes/approvedApplicant/$1', '<i class="fa fa-file-text-o"></i> ' . lang('approved_applicant'));
        //$email_link = anchor('quotes/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_quote'), 'data-toggle="modal" data-target="#myModal"');
        $add_link = anchor('quotes/add_collateral/$1', '<i class="fa fa-plus-circle"></i> ' . lang('add_collateral'),'data-toggle="modal" data-target="#myModal"');
		$edit_link = anchor('quotes/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_applicant'));
       // $convert_link = anchor('sales/add/$1', '<i class="fa fa-heart"></i> ' . lang('create_sale'));
        //$pc_link = anchor('purchases/add/$1', '<i class="fa fa-star"></i> ' . lang('create_purchase'));
        $pdf_link = anchor('quotes/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_applicant") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('quotes/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_quote') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
                    <ul class="dropdown-menu pull-right" role="menu">';
                        $action .= '<li>' . $approve_link . '</li>';
						$action .= '<li>' . $add_link . '</li>';
                        $action .= '<li>' . $edit_link . '</li>';
                       // $action .= '<li>' . $pdf_link . '</li>';
                        $action .= '<li>' . $delete_link . '</li>';
                    $action .= '</ul>
                </div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';
		
		$setting = $this->quotes_model->getSettingCurrncies();
        $this->load->library('datatables');
        
            $this->datatables
                ->select($this->db->dbprefix('quotes').".id,".
						$this->db->dbprefix('quotes').".reference_no,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) AS customer_name_en,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_kh, ".	
						$this->db->dbprefix('quote_items').".product_name AS asset,".
						"((SELECT erp_companies.name FROM erp_companies WHERE erp_quotes.biller_id = erp_companies.id)) AS dealer_name, ".
						$this->db->dbprefix('quotes').".quote_status as status,
						
						DATE_FORMAT(".$this->db->dbprefix('quotes').".date,'%d-%m-%Y %h:%i:%s'),
						DATE_FORMAT(".$this->db->dbprefix('quotes').".approved_date,'%d-%m-%Y %h:%i:%s'), 
						
						CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) as coname,
						myBranch.name,".	
						$this->db->dbprefix('quotes').".total * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate ."),".
					    $this->db->dbprefix('currencies').".name as crname ")
                ->from('quotes')
				->join('users','quotes.by_co=users.id','INNER')
				->join('sales', 'sales.quote_id = quotes.id', 'left')
				->join('companies','quotes.customer_id=companies.id','INNER')
				->join('companies as myBranch', 'users.branch_id = myBranch.id')
				->join('quote_items', 'quotes.id = quote_items.quote_id', 'left')
				->join('currencies','currencies.code = quote_items.currency_code','left')
				->where('quotes.quote_status','rejected')
				->where('erp_quotes.status ', 'loans')
				->order_by('quotes.id','DESC');
        
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->datatables->where('quotes.branch_id', $this->session->branch_id);
		}
		if ($product_id) {
			$this->datatables->join('quote_items as qi', 'qi.quote_id = quotes.id', 'left');
			$this->datatables->where('qi.product_id', $product_id);
		}
		
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            //$this->datatables->where('quotes.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			//$this->datatables->where('quotes.created_by', $this->session->userdata('user_id'));
		}
		
		if ($user_query) {
			$this->datatables->where('quotes.by_co', $user_query);
		}

		if ($reference_no) {
			$this->datatables->like('quotes.reference_no', $reference_no);
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
	
	/*==============show msg name group Loans==============*/
	function getExistingGroupLoanIDInfo()
	{
		$gl_id = $this->input->get('gl_id', true);
		$gl_info = $this->quotes_model->getGroupLoanID($gl_id);
		if($gl_info) {
			echo json_encode(array('id' => $gl_info->id,'name' => $gl_info->name));
		} else {
			echo json_encode(array('id' => false));
		}
	}
	
	/*function getTaxeRateByID($id){
		$rate = $this->quotes_model->getTaxeRateByID($id);
		if($rate){
			echo json_encode($rate);
		}
	}*/
	
	
}

