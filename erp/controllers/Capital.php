<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Capital extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        if ($this->Customer || $this->Supplier) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->lang->load('capital', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('companies_model');
		$this->load->model('accounts_model');
		$this->load->model('documents_model');
		$this->load->model('site');
		$this->load->model('settings_model'); 
		  if(!$this->Owner && !$this->Admin) {
            $gp = $this->site->checkPermissions();
            $this->permission = $gp[0];
            $this->permission[] = $gp[0];
        } else {
            $this->permission[] = NULL;
        }
    }

    function index($action = NULL)
    {
        $this->erp->checkPermissions('index', true, 'documents');

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('capital')));
        $meta = array('page_title' => lang('capital'), 'bc' => $bc);
        $this->page_construct('capital/index', $meta, $this->data);
    }
	function add()
    {
			$this->erp->checkPermissions(false, true);
			$this->load->model('capital_model');
			$this->load->model('site');
			$this->data['chart_accounts'] = $this->capital_model->getAllChartAccounts();
			$this->data['banks'] = $this->capital_model->getBankAccount();
			$this->data['currencies'] = $this->site->getCurrency();
			$this->data['purchases'] = $this->capital_model->getpurchases();
			$this->data['branchs'] = $this->capital_model->getBranchDName();
			$this->data['shareholder'] = $this->capital_model->getShareholder();
			$this->data['reference_cap'] = $this->site->getReference('cap');
		
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'capital/add', $this->data);
    }
	function insert(){
		$this->load->model('capital_model');
		$this->load->model('site');
		
		$cap_currency = $this->input->post('currency');
		$amount = str_replace(',', '', $this->input->post('amount'));
		$default_currency = $this->site->get_setting();
		$defaultAmount = $this->erp->convertCurrency($default_currency->default_currency, $cap_currency, $amount);
		//$this->erp->print_arrays($defaultAmount);
		
		$data = array(
				'reference'  		=>$this->input->post('reference'),
				'date'  			=>$this->erp->fld(trim($this->input->post('date'))),
				'shareholder_id'  	=>$this->input->post('shareholder'),
				'branch_id'  		=>$this->input->post('branch'),
				'amount'  			=>$defaultAmount,
				'currency_amount'  	=>str_replace(',', '', $this->input->post('amount')),
				'created_by'		=> $this->session->userdata('user_id'),
				'currency_code'		=>$this->input->post('currency'),
				'bank_account'  	=>$this->input->post('bank_account'),
				'note'  			=>$this->input->post('note'),
            );
			
		//$this->erp->print_arrays($data);
		$branchid = $this->input->post('branch');
		
		$this->load->model('capital_model');
		$i=$this->capital_model->insert($data);
		if($i){
			$this->session->set_flashdata('Branch add successful.');
			redirect('capital');
		}
	}
	public function getCapital(){
		$this->erp->checkPermissions('index'); 

        $this->load->library('datatables');	
        $this->datatables
             ->select($this->db->dbprefix('capital').".id, reference ,date, ".$this->db->dbprefix('holder').".name, ".
			 $this->db->dbprefix('companies').".company, ".
			 $this->db->dbprefix('capital').".currency_amount, ".
			 $this->db->dbprefix('currencies').".name as currency ,".
			 $this->db->dbprefix('gl_charts').".accountname as bank
			 ")
			->join('companies','companies.id = capital.branch_id', 'left')
			->join('currencies','currencies.code = capital.currency_code', 'left')
			->join('companies as erp_holder','erp_holder.id = capital.shareholder_id','left')
			->join('gl_charts', 'gl_charts.accountcode = capital.bank_account', 'left')
            ->from("capital")
			->order_by('capital.id','DESC')
            ->add_column("Actions", "<div class=\"text-center\">
										<a class=\"tip\" title='" . $this->lang->line("edit_capital") . "' href='" . site_url('capital/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a>
										<a class=\"tip\" title='" . $this->lang->line("receipt") . "' href='" . site_url('capital/print_receipt/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-print\"></i></a>
									</div>", $this->db->dbprefix('capital').".id");   ////<a href='#' class='tip po' title='<b>" . $this->lang->line("delete_capital") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('capital/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a>
        //->unset_column('id');
        echo $this->datatables->generate();
	}
	function print_receipt($id)
    {
			$this->erp->checkPermissions(false, true);
			$this->load->model('capital_model'); 
			$setting = $this->settings_model->getSettings();
			$this->data['setting'] = $setting;
			$g=$this->capital_model->getCapital($id);
			$this->data['capital']=$g;
			$this->data['defualt_currency'] = $this->site->get_setting();
			$this->data['currencies'] = $this->site->getCurrency();
			$this->data['capital_info'] = $this->capital_model->getCapitals($id);
			$branch_id=$this->capital_model->getBranchByID($id);
			$b_id = $branch_id->id;
			$this->data['branch_info'] = $this->capital_model->getBranchAddress($b_id);
			
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'capital/print_receipt', $this->data);
    }
	public function delete($id){
		$this->load->model('capital_model');
		$d=$this->capital_model->delete($id);
		if($d){
			$this->session->set_flashdata('message', $this->lang->line("capital_delete_successful"));      
			redirect('capital', 'refresh');	
		}
	}
	function edit($id){
		$this->erp->checkPermissions(false, true);
		$this->load->model('capital_model');
		if ($this->input->get('id')) {
            $quote_id = $this->input->get('id');
        }
		$g=$this->capital_model->getCapital($id);
		$this->data['capital']=$g;
		
			//$cap_currency= $this->capital_model->getCurrncyByCode($capital->currency_code);
			
			$this->data['defualt_currency'] = $this->site->get_setting();
			$this->data['currencies'] = $this->site->getCurrency();
			$this->data['chart_accounts'] = $this->capital_model->getAllChartAccounts();
			$this->data['purchases'] = $this->capital_model->getpurchases();
			$this->data['banks'] = $this->capital_model->getBankAccount();
			$this->data['branchs'] = $this->capital_model->getBranchDName();
			$this->data['shareholder'] = $this->capital_model->getShareholder();
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'capital/edit', $this->data);
	}
	function update($id){
		$this->load->model('capital_model');
		$this->load->model('site');
		
		$cap_currency = $this->input->post('currency');
		$amount = str_replace(',', '', $this->input->post('amount'));
		$default_currency = $this->site->get_setting();
		$defaultAmount = $this->erp->convertCurrency($default_currency->default_currency, $cap_currency, $amount);
		
		$data = array(
				'reference'  		=>$this->input->post('reference'),
				'date'  			=>$this->erp->fld(trim($this->input->post('date'))),
				'shareholder_id'  	=>$this->input->post('shareholder'),
				'branch_id'  		=>$this->input->post('branch'),
				'amount'  			=>$defaultAmount,
				'currency_amount'  	=>str_replace(',', '', $this->input->post('amount')),
				'updated_by'		=> $this->session->userdata('user_id'),
				'currency_code'		=>$this->input->post('currency'),
				'bank_account'  	=>$this->input->post('bank_account'),
				'note'  			=>$this->input->post('note'),
            );
		//$this->erp->print_arrays($data);
		$this->load->model('capital_model');
		$i=$this->capital_model->update($id,$data);
		if($i){
			$this->session->set_flashdata('Branch update successful.');
			redirect('capital');
		}
	}
	public function capital_actions(){
		$this->load->model('capital_model');
		if ($this->input->post('action-form') == 'delete') {
                    foreach ($_POST['val'] as $id) {   
					    $this->capital_model->delete_capital($id);	
                    }
					$this->session->set_flashdata('message', lang("capital_deleted"));
						redirect("capital");
		}
		else{echo 'function not work!';}
	}					
}

?>