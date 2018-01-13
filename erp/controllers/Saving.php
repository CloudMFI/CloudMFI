<?php defined('BASEPATH') or exit('No direct script access allowed');

class Saving extends MY_Controller
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
        $this->lang->load('saving', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('quotes_model');
		$this->load->model('companies_model');
		$this->load->model('site');
		$this->load->model('saving_model');
		$this->load->model('down_payment_model');
		$this->load->model('accounts_model');
		$this->load->model('installment_payment_model');
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
		$this->erp->checkPermissions('index',false,'money_saving');
		//$this->permission['reports-back_office']='';
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
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('saving')));
			$meta = array('page_title' => lang('quotes'), 'bc' => $bc);
		}   
		 $this->page_construct('saving/index', $meta, $this->data);
    }

    public function getSaving()
    {
        $this->erp->checkPermissions('index',false,'money_saving');
		
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
		$add_saving = anchor('saving/add_saving/$1', '<i class="fa fa-file-text-o"></i> ' . lang('add_saving'), 'data-toggle="modal" data-target="#myModal"');
		$cash_widrawal = anchor('saving/cash_widrawal/$1', '<i class="fa fa-file-text-o"></i> ' . lang('cash_widrawal'), 'data-toggle="modal" data-target="#myModal"');
		
		$delete_link = "<a href='#' class='po' title='<b>" . lang("delete_contract") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_account') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
						. '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
						. lang('actions') . ' <span class="caret"></span></button>
					<ul class="dropdown-menu pull-right" role="menu">
						<li class="ps">' . $approve_link . '</li>
						<li class="ps">' . $add_saving . '</li>
						<li class="ps">' . $cash_widrawal . '</li>
						<li class="dl">' . $delete_link . '</li>
					</ul>
					</div></div>';
		$setting = $this->down_payment_model->getSettingCurrncy();
        $this->load->library('datatables');
            $this->datatables
                ->select($this->db->dbprefix('sales').".id,".
						$this->db->dbprefix('sales').".reference_no, 
						CONCAT(".$this->db->dbprefix('companies').".family_name,' ',".$this->db->dbprefix('companies').".name) AS customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other,' ',".$this->db->dbprefix('companies').".name_other) as customer_name_other, ".						
						$this->db->dbprefix('companies').".phone1,".
						$this->db->dbprefix('sales').".date as save_date,
						IF(".$this->db->dbprefix('sales').".frequency = 7, 'Weekly', IF(".$this->db->dbprefix('sales').".frequency = 14, 'Two Week', IF(".$this->db->dbprefix('sales').".frequency = 30, 'Monthly',''))),". 						
						$this->db->dbprefix('users').".username,myBranch.name,
						CONCAT(TRUNCATE((".$this->db->dbprefix('sales').".interest_rate*100), 2),' ', '%') AS rate,
						((COALESCE(".$this->db->dbprefix('sales').".grand_total, 0))) * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate .") as principle,
						((((COALESCE(".$this->db->dbprefix('sales').".grand_total, 0))) * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate ."))* (".$this->db->dbprefix('sales').".interest_rate*100/100)) AS interest,
						((COALESCE(".$this->db->dbprefix('sales').".total, 0))) * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate .") as total,".
						$this->db->dbprefix('currencies').".name AS crname, ".
						$this->db->dbprefix('sales').".sale_status")
                ->from('sales')
				->join('users','sales.created_by=users.id','INNER')
				->join('sale_items', 'sales.id = sale_items.sale_id', 'INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('companies AS myBranch', 'users.branch_id= myBranch.id', 'left')
				->join('sale_services', 'sales.id = sale_services.sale_id', 'left')
				->join('currencies','currencies.code = sale_items.currency_code','left')
				->where($this->db->dbprefix('sales').'.status ', 'saving')				
				->group_by('sales.id')
				->order_by('sales.id','DESC');
		
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
        $this->datatables->add_column("Actions", $action,$this->db->dbprefix('sales').".id");
		echo $this->datatables->generate();
    }
	public function view_details($quote_id = null)
    {
        $this->erp->checkPermissions();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('view_details')));
        $meta = array('page_title' => lang('view_saving_details'), 'bc' => $bc);
        $this->page_construct('saving/view_details', $meta, $this->data);

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

    
    public function open_account()
    {		
        $this->erp->checkPermissions('index',false,'money_saving');
        
        $this->form_validation->set_rules('cus_gov_id', $this->lang->line("cus_gov_id"), 'required');
		$this->form_validation->set_rules('price', $this->lang->line("price"), 'required');
		$this->form_validation->set_rules('bank_account', $this->lang->line("bank_account"), 'required');
		
        if ($this->form_validation->run() == true) {
			
			
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
			
			if($this->input->post('interest_rate')) {
				$rate_text = $this->input->post('interest_rate');
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

			$amount  =  str_replace(',', '', ($this->input->post('price')? $this->input->post('price') : 0));
			$currency = $this->input->post('currency');
			$default_currency = $this->site->get_setting();
			$qtotal = $this->erp->convertCurrency($default_currency->default_currency, $currency, $amount);    
			
         
                
            			
			
			$products = array(                        
                        'net_unit_price' => $amount,
                        'unit_price' => $amount,
						'subtotal' => $amount,
						'currency_code' => $this->input->post('currency'),
                        'real_unit_price' => $amount,
						'description' => $this->input->post('ldescription')
                    );
                    $total += $unit_price * $item_quantity;
					
			//$this->erp->print_arrays($products);
			
			
			$cg = $this->site->getCustomerGroupByID($customer_group);
            $customers = array('group_id'            => '3',
							'group_name'             => 'saving',
							//'customer_group_id'      => $customer_group,
							//'customer_group_name'    => 'saving',
							'identify'				 => $this->input->post('identify_id'),
							'gov_id'                 => $this->input->post('cus_gov_id'),
							'name'                   => $this->input->post('cus_first_name'),
							'name_other'             => $this->input->post('cus_first_name_other'),
							'family_name'            => $this->input->post('cus_family_name'),
							'family_name_other'      => $this->input->post('cus_family_name_other'),
							'gender'                 => $this->input->post('cus_gender'),
							'date_of_birth'          => $this->erp->fld(trim($this->input->post('cus_dob'))),
							'age'                    => $this->input->post('cus_age'),							
							'income_combination'     => $this->input->post('cus_inc_comb'),
							'state'                  => $this->input->post('cus_state'),
							'address'                => $this->input->post('cus_pob'),
							'phone1'                 => $this->input->post('cus_phone_1'),
							'phone2'                 => $this->input->post('cus_phone_2'),							
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
							'issue_date'          	 => $this->erp->fld(trim($this->input->post('cus_issue_date'))),
							
						);
			
			
			//$this->erp->print_arrays($customers);
			       
			$grand_totals = $qtotal ;
			
			$user_id = ($this->input->post('cus_by_co') ? $this->input->post('cus_by_co') : $this->session->userdata('user_id'));
			$user = $this->quotes_model->getUser($user_id);
			$reference = $this->site->getReference('sav');
			$date = date('Y-m-d H:i:s');
            $data = array('date'        => $date,
                'reference_no'          => $reference,
				'customer_group'        => $customer_group,
                'customer'              => $this->input->post('cus_family_name') .' '. $this->input->post('cus_first_name'),                   
                'total'                 => $qtotal,               
                'grand_total'           => $grand_totals,
				'status'         		=> 'saving',
				'sale_status'           => 'active',
				'created_by'			=> $user_id,
				'start_payment'   		=> $this->erp->fld(trim($this->input->post('st_inst_date'))),
				'frequency'             => $frequency,
				'rate_type'				=> $rate_type,
				'interest_rate'         => $interest_rate,
				'rate_text'             => $rate_text,
				'term'                  => $term,
				'principle_frequency'	=> $this->input->post('principle_frequency'),
				'branch_id'				=> $user->branch_id,
				'mfi'					=> $mfi,
				
            );
			//$this->erp->print_arrays($data);
			
			$bank = $this->input->post('bank_account');
			$reference_sp = $this->site->getReference('sp');
			$paid_by = $this->input->post('paid_by');
			$pay_date = date('Y-m-d H:i:s');					
			$payment = array(
							
							'date' 				=> $pay_date,
							'amount'  			=> $grand_totals,
							'type' 	  			=> 'saving',
							'reference_no'  	=> $reference_sp,
							'biller_id'			=> $user->branch_id,
							'bank_acc_code'		=> $bank,
							'paid_by' 			=> $paid_by,
			
							);
           // $this->erp->print_arrays($payment);
			
			if(isset($_POST['print_payment_schedule']) && $_POST['print_payment_schedule'] == lang('print_payment_schedule')){
				echo "Code for print_payment_schedule button"; 
			}
		
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
		
		}
        if ($this->form_validation->run() == true && $this->saving_model->addSavingAccount($data, $products, $documentsArray, $customers, $payment)) {
            $this->session->set_userdata('remove_quls', 1);
            $this->session->set_flashdata('message', $this->lang->line("saving_registered"));
			
            redirect('saving');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

           
            $this->data['billers'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllCompanies('supplier') : null;
        
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['warehouses'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllWarehouses() : null;
            $this->data['qunumber'] = ''; 
			$this->data['services'] = $this->site->getServicesByStatus('1');
		
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
			$this->data['customers'] = $this->quotes_model->getGovID();
			$this->data['users'] = $this->quotes_model->getco();
			$this->data['currencies'] = $this->site->getCurrency();
			$this->data['identify_type'] = $this->quotes_model->getIdentifyType();
			$this->data['identify_name'] = $this->quotes_model->getIdentifyTypeName(companies_id);
			$this->data['banks'] = $this->accounts_model->getBankAccount();
			$this->data['reference_cl'] = $this->site->getReference('cl');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('quotes'), 'page' => lang('quotes')), array('link' => '#', 'page' => lang('add_quote')));
            $meta = array('page_title' => lang('add_quote'), 'bc' => $bc);
            $this->page_construct('saving/register', $meta, $this->data);
			
		
        }
    }
	
	function add_saving($id) {
		
		$this->erp->checkPermissions('payments', true);
        $this->load->helper('security');
		
		$this->form_validation->set_rules('payment', lang("payment"), 'required');
		$this->form_validation->set_rules('bank_account', lang("bank_account"), 'required');
		$this->form_validation->set_rules('pay_date', lang("pay_date"), 'required');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $pay_date = $this->erp->fld(trim($this->input->post('pay_date')));
            } else {
                $pay_date = date('Y-m-d H:i:s');
            }
			
			$sale = $this->saving_model->getSaleByID($id);
			$saleItem = $this->saving_model->getSaleItemBysaleID($id);	
			$default_currency = $this->site->get_setting();
			
			$reference_no = $this->input->post('reference');
			$paid_by = $this->input->post('paid_by');
			$payment = str_replace(',', '', $this->input->post('payment'));
			$bank = $this->input->post('bank_account');
			$payments = $this->erp->convertCurrency($default_currency->default_currency, $saleItem->currency_code, $payment);
			
			$payment = array(	
								'date' 				=> $pay_date,
								'sale_id' 			=> $id,
								'reference_no' 		=> $reference_no,
								'amount' 			=> $payments,
								'paid_by' 			=> $paid_by,
								'created_by' 		=> $this->session->userdata('user_id'),
								'biller_id'			=> $sale->branch_id,
								'bank_acc_code'		=> $bank,
								'type'				=> 'saving',
							);			
			//$this->erp->print_arrays($payment);

        } elseif ($this->input->post('add_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->saving_model->addSaving($payment)) {
            $this->session->set_flashdata('message', lang("saving_added"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['billers'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllCompanies('supplier') : null;
            $this->data['sales'] = $this->saving_model->getSaleByID($id);
			$this->data['reference'] = $this->site->getReference('sp');
			$this->data['banks'] = $this->accounts_model->getBankAccount();            			
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'saving/add_saving', $this->data);
        }
	}
	
	function cash_widrawal($id) {
		
		$this->erp->checkPermissions('payments', true);
        $this->load->helper('security');
		
		$this->form_validation->set_rules('payment', lang("payment"), 'required');
		$this->form_validation->set_rules('bank_account', lang("bank_account"), 'required');
		$this->form_validation->set_rules('pay_date', lang("pay_date"), 'required');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $pay_date = $this->erp->fld(trim($this->input->post('pay_date')));
            } else {
                $pay_date = date('Y-m-d H:i:s');
            }
			
			$sale = $this->saving_model->getSaleByID($id);
			$saleItem = $this->saving_model->getSaleItemBysaleID($id);	
			$default_currency = $this->site->get_setting();
			
			$payments = $this->erp->convertCurrency($default_currency->default_currency, $saleItem->currency_code, $payment);
			
			$payment = array(	
								
							);			
			//$this->erp->print_arrays($payment);

        } elseif ($this->input->post('add_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->saving_model->addSaving($payment)) {
            $this->session->set_flashdata('message', lang("saving_added"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['billers'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllCompanies('supplier') : null;
            $this->data['sales'] = $this->saving_model->getSaleByID($id);
			$this->data['reference_pp'] = $this->site->getReference('pp');
			$this->data['banks'] = $this->accounts_model->getBankAccount();            			
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'saving/cash_widrawal', $this->data);
        }
	}
	
    public function edit_register($id = null){
       $this->erp->checkPermissions();
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
			
            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('qu', $dealer_code);
            if ($this->Owner || $this->Admin) {
                $date = date('Y-m-d H:i:s');
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
			$customer_group = $this->input->post('financial_product');
            $status = $this->input->post('status');
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
			if(is_array($customer_details)){
            $customer = $customer_details->family_name . ' ' .$customer_details->name;
			}
			$mfi = $this->input->post('mfi');
            
            $note = $this->erp->clear_tags($this->input->post('note'));
            
			$q_service  = $this->input->post('ch_services');
			
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
            $customers = array	('group_id'            => '3',
			
									'group_name'             => 'customer',
									'customer_group_id'      => $customer_group,
									'customer_group_name'    => isset($cg->name) ?$cg->name  : (''),
									'identify'               => $this->input->post('identify_id'),
									'gov_id'                 => $this->input->post('cus_gov_id'),
									'name'                   => $this->input->post('cus_first_name'),
									'name_other'             => $this->input->post('cus_first_name_other'),
									'family_name'            => $this->input->post('cus_family_name'),
									'family_name_other'      => $this->input->post('cus_family_name_other'),
									'gender'                 => $this->input->post('cus_gender'),
									'status'                 => $this->input->post('cus_marital_status'),
									'date_of_birth'          => $this->erp->fld(trim($this->input->post('cus_dob'))),
									'age'                    => $this->input->post('cus_age'),                          
									'income_combination'     => $this->input->post('cus_inc_comb'),
									'state'                  => $this->input->post('cus_state'),
									'address'                => $this->input->post('cus_pob'),
									'phone1'                 => $this->input->post('cus_phone_1'),
									'phone2'                 => $this->input->post('cus_phone_2'),                           
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
									'issue_date'          	 => $this->erp->fld(trim($this->input->post('cus_issue_date'))),						

								);
			
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
                'customer_id'               => $customer_id,
                'customer'                  => isset($customer) ?$customer  : (''),
                'note'                 	    => ($note ? $note : $this->input->post('purpose')),
                'total'                     => $qtotal,
                'grand_total'               => $grand_totals,
				'created_by'				=> $user_id,				
				'installment_date'   		=> $this->erp->fld(trim($this->input->post('st_inst_date'))),
				'frequency'                 => $frequency,
				'rate_type'					=> $rate_type,
				'interest_rate'             => $interest_rate,
				'rate_text'					=> $rate_text,
				'term'                      => $term,
				'principle_frequency'		=> $this->input->post('principle_frequency'),
				'branch_id'					=> $user->branch_id ,
				'mfi'						=> $mfi,				
            );			
			
            if($this->input->post('latitude_') != null or $this->input->post('latitude_') != ""){
                $field_check['latitude']    = $this->input->post('latitude_');
                $field_check['longitude']   = $this->input->post('longtitute_');
            }
			
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
			
				
        if ($this->form_validation->run() == true && $this->quotes_model->updateQuotationDetails($id, $data, $QouteServices, isset($products)? $products  : (''), $employee_, $guarantor_, $documentsArray, $customers, $field_check, $collateral, $group_loan , $join_lease, $join_guarantor)) {
			
		 $this->session->set_userdata('remove_quls', 1);
         $this->session->set_flashdata('message', $this->lang->line("quote_saved"));
            //redirect($_SERVER['HTTP_REFERER']);
			redirect('saving');
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
			$this->data['sale'] = $this->quotes_model->getSaleByQuoteID($id);			
			$this->data['documents'] = $this->quotes_model->getDocumentsByQuoteID($id);			
			$this->data['finacal_products'] = $this->site->getAllCustomerGroup();
			$this->data['advance_percentages'] = $this->site->getAllDownPercentage();
			$this->data['interest_rates'] = $this->site->getAllInterestRate();
			$this->data['terms'] = $this->site->getAllTerm();
			$this->data['category'] = $this->quotes_model->getCategory();
			$this->data['users'] = $this->quotes_model->getco();			
			$this->data['qphoto'] = $this->quotes_model->getQoutePhoto($id);
			$this->data['applicant'] = $this->site->getCompanyByID($quote?$quote->customer_id:'');
			$this->data['countries'] = $this->site->getCountries();
			$this->data['currencies'] = $this->site->getCurrency();
			$this->data['identify_type'] = $this->quotes_model->getIdentifyType();
			$this->data['identify_name'] = $this->quotes_model->getIdentifyTypeName(companies_id);
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('quotes'), 'page' => lang('quotes')), array('link' => '#', 'page' => lang('edit_quote')));
            $meta = array('page_title' => lang('edit_quote'), 'bc' => $bc);
            $this->page_construct('saving/edit_register', $meta, $this->data);
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
	
	
	function compulsory_saving()
	{
		//$this->erp->print_arrays(savingList);
		//$this->erp->checkPermissions('index', true, 'saving');
		$this->erp->load->model('reports_model');
		$this->data['users'] = $this->reports_model->getStaff();
		$this->data['products'] = $this->site->getProducts(); 
		$this->data['dealer'] = $this->site->getAllDealer('supplier');
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
		if (isset($this->permission['reports-back_office']) ?$this->permission['reports-back_office']  : ('')){
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('contracts')));
			$meta = array('page_title' => lang('contracts'), 'bc' => $bc);
		}else{
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('compulsory_saving')));
			$meta = array('page_title' => lang('compulsory_saving'), 'bc' => $bc);
		}		
        $this->page_construct('saving/compulsory_saving', $meta, $this->data);
	}
	
	public function getCompulsorySaving( )
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
		$saving_list = anchor('saving/saving_list/$1', '<i class="fa fa-file-text-o"></i> ' . lang('saving_list'), 'data-toggle="modal" data-target="#myModal"');
		$delete_link = "<a href='#' class='po' title='<b>" . lang("delete_contract") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_contract') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
			 <li class="ps">' . $saving_list . '</li> 
			 
        </ul>
		</div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';
		$setting = $this->down_payment_model->getSettingCurrncy();
        $this->load->library('datatables');
        
            $this->datatables
                ->select($this->db->dbprefix('sales').".id,".
						$this->db->dbprefix('sales').".reference_no,".
						$this->db->dbprefix('sales').".quote_id as qi, 
						CONCAT(".$this->db->dbprefix('companies').".family_name,' ',".$this->db->dbprefix('companies').".name) AS customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other,' ',".$this->db->dbprefix('companies').".name_other) as customer_name_other,  
						myBranch.name,
						".$this->db->dbprefix('sales').".approved_date,
						((COALESCE(".$this->db->dbprefix('loan').".total, 0))) * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate .") as total,
						CONCAT(TRUNCATE((".$this->db->dbprefix('sales').".saving_rate*100), 2),' ', '%') AS interest, 						
						".$this->db->dbprefix('sales').".saving_amount * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate .") as saving_amount,
						CONCAT(TRUNCATE((".$this->db->dbprefix('sales').".saving_interest_rate*100), 2),' ', '%') AS saving_interest_rate, 
						". $this->db->dbprefix('sales').".saving_balance * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate .") as saving_balance,
						". $this->db->dbprefix('sales').".cash_withdrawal * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate .") as cash_withdrawal,".
						$this->db->dbprefix('currencies').".name AS crname, ".
						$this->db->dbprefix('sales').".sale_status, ")
                ->from('sales')
				->join('sale_items', 'sales.id = sale_items.sale_id', 'left')
				->join('sales as erp_loan', 'erp_loan.id = sales.sales_id', 'left')
				->join('companies', 'sales.customer_id = companies.id', 'left')
				->join('companies AS myBranch', 'sales.branch_id= myBranch.id', 'left')
				->join('currencies','currencies.code = sale_items.currency_code','left')
				->where($this->db->dbprefix('sales').'.status =', 'saving')			
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
		 
		if ($customer) {
			$this->datatables->where('sales.customer_id', $customer);
		}
		 
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
        $this->datatables->add_column("Actions", $action,$this->db->dbprefix('sales').".id,qi");
		$this->datatables->unset_column("qi");
		echo $this->datatables->generate();
    }
	
	function cash_withdrawal()
	{

		$this->load->model('quotes_model');
		$this->load->model('accounts_model');
		$this->load->model('saving_model');
		$this->load->model('site');
		
		$this->form_validation->set_rules('cash_withdrawal', $this->lang->line("cash_withdrawal"), 'required'); 
		$this->form_validation->set_rules('bank_account', $this->lang->line("cash_out"), 'required');
		$this->form_validation->set_rules('contract_id', $this->lang->line("saving_reference"), 'required');
		
		if ($this->form_validation->run() == true) {
			$reference_no = $this->site->getReference('pp');
			$setting = $this->saving_model->get_setting();
			
			$contracts = $this->input->post('contract_id');
			$contract = explode('#', $contracts); 
			$sale_id = $contract[0];
			
			$bank_account = $this->input->post('bank_account');
			$bank_act = explode('#', $bank_account); 
			$account_code = $bank_act[0];
			
			$sale = $this->saving_model->getSaleById($sale_id);
			$saleItem = $this->saving_model->getSaleItemBysaleID($sale_id);
			
			$withdrawal = $this->input->post('cash_withdrawal');
			$withdrawals= $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $withdrawal);
			
			$payment = array(
								'sale_id'				=> $sale_id,
								'biller_id'				=> $sale->branch_id,
								'type'					=> 'withdrawal',
								'paid_type' 			=> 'Withdrawal',
								'date' 					=> $this->erp->fld(trim($this->input->post('date'))),
								'reference_no'  		=> $reference_no,								
								'amount'				=> $withdrawals, 
								'paid_by'				=> $this->input->post('paid_by'),
								'bank_acc_code'			=> $account_code,
								'created_by'			=> $this->session->userdata('user_id'),
								
			
							);
			// $this->erp->print_arrays($payment);
			
			$saving_balance = $sale->saving_balance - $withdrawals;
			$cash_withdrawal= $sale->cash_withdrawal+ $withdrawals;
			$sale_status = "activated";
			if($saving_balance == 0){
				$sale_status = "completed";
			}
			$update_saving = array(
			
								'saving_balance'		=> $saving_balance,
								'cash_withdrawal'		=> $cash_withdrawal,
								'sale_status'			=> $sale_status,
								
			);
			//$this->erp->print_arrays($update_saving);
		}

		if ($this->form_validation->run() == true && $sid = $this->saving_model->addCashWithdrawal($payment, $update_saving )) {
			$this->session->set_flashdata('message', $this->lang->line("withdrawal_added")); 	 
            redirect('saving/compulsory_saving');
			
		} else {
			$user_id = $this->session->userdata('user_id');
			$user = $this->quotes_model->getUser($user_id);
			 
			$this->data['reference_pp'] = $this->site->getReference('pp');
			$this->data['banks'] = $this->accounts_model->getBankAccountByBranch($user->branch_id);
			$this->data['currencies'] = $this->accounts_model->getCurrncy();
			$this->data['contracts'] = $this->saving_model->getSavingCustomer($user->branch_id);
			//$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['modal_js'] = $this->site->modal_js();
			$this->load->view($this->theme . 'saving/cash_withdrawal', $this->data);
		}
	}
	
	public function ajaxGetSavingBysaleID($sale_id = NULL){
		$setting = $this->saving_model->getSettingCurrncy();
		$def_currency = $setting->code;		
		$def_rate = $setting->rate;
		if ($rows = $this->saving_model->getAjaxSavingById($sale_id)) {
			$currency = $rows->currency_code;
			$currency_name = $rows->curr_name;
			$save_currency = $this->site->getCurrencyByCode($currency);
			$save_rate = $save_currency->rate;
			
			$saving_balance = $this->erp->convertCurrency($currency, $def_currency, $rows->saving_balance);			 
			$saving_balances = str_replace(',', '', $this->erp->roundUpMoney($saving_balance,$currency));						
			$balances = $this->erp->roundUpMoney($saving_balances,$currency) .' '. $rows->curr_name;
			$customer = $rows->customer_name;
			
            echo json_encode(array( 'balance'=> $saving_balances , 'customer'=>$customer, 'balances'=>$balances, 'def_rate'=>$def_rate, 'save_rate'=>$save_rate ));
        } else {
            echo json_encode(false);
        }
	}
	
	
	function saving_list($sale_id)
    {
		$this->load->model('saving_model');  
		$this->data['settings'] = $this->saving_model->get_setting();
		$sales  = $this->saving_model->getSaleById($sale_id);		
		$customer_id = $sales->customer_id;
		$created_by = $sales->by_co;
		$this->data['sales'] = $sales;
		$this->data['sale_iterm'] =$this->saving_model->getSaleItemBysaleID($sales->sales_id);
		$this->data['saving_iterm'] =$this->saving_model->getSaleItemBysaleID($sale_id);
		$this->data['customer'] = $this->saving_model->getMfiCustomer($customer_id);
		$this->data['by_co'] = $this->saving_model->getMfiCreator($created_by);
		$this->data['savings'] = $this->saving_model->getsavingBySaleId($sales->sales_id); 
		$this->data['modal_js'] = $this->site->modal_js();
		$this->load->view($this->theme.'saving/saving_list',$this->data);	 
		 
    }
	
}

