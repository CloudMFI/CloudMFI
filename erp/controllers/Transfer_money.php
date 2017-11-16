<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer_money extends MY_Controller
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
        $this->lang->load('transfer_money', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('companies_model');
		$this->load->model('accounts_model');
		$this->load->model('documents_model');
		$this->load->model('settings_model');
		$this->load->model('Installment_payment_model');
		$this->load->model('site');
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
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('transfer')));
        $meta = array('page_title' => lang('transfer'), 'bc' => $bc);
        $this->page_construct('transfer_money/index', $meta, $this->data);
    }
	function add()
    {
			$this->erp->checkPermissions(false, true);
			$this->load->model('transfer_money_model');
			$this->load->model('site');
			$this->data['chart_accounts'] = $this->transfer_money_model->getAllChartAccounts();
			$this->data['banks'] = $this->transfer_money_model->getBankAccount();
			$this->data['purchases'] = $this->transfer_money_model->getpurchases();
			$this->data['branchs'] = $this->transfer_money_model->getBranchDName();
			$this->data['defualt_currency'] = $this->transfer_money_model->getSettingCurrncy();
			$this->data['reference_to'] = $this->site->getReference('to');
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'transfer_money/add', $this->data);
    }
	function insert(){
		$this->load->model('transfer_money_model');
		$this->load->model('site');
		//$defualt_currency = $this->transfer_money_model->getSettingCurrncy();
		//$amount = str_replace(',', '', $this->input->post('amount'));
		//$usd_amount = $amount / $defualt_currency->rate;
		
		$data = array(
				'reference'			=> $this->input->post('reference'),
				'date'				=> $this->erp->fld(trim($this->input->post('date'))),
				'from_branch_id'    => $this->input->post('from_branch'),
				'to_branch_id'		=> $this->input->post('to_branch'),
				'amount'			=> str_replace(',', '', $this->input->post('amount')),
				'bank_account'		=> $this->input->post('bank_account'),
				'created_by'		=> $this->session->userdata('user_id'),
            );
		//$branchid = $this->input->post('branch');
		
		$this->load->model('transfer_money_model');
		$i=$this->transfer_money_model->insert($data);
		if($i){
			$this->session->set_flashdata('Branch add successful.');
			redirect('transfer_money');
		}
	}
	public function get_money_transfer(){
		$this->erp->checkPermissions('index'); 
		$this->load->model('transfer_money_model');
		$defualt_currency = $this->transfer_money_model->getSettingCurrncy(); 		////// * ".$defualt_rate." as defualt_amount
		$defualt_rate = $defualt_currency->rate? $defualt_currency->rate : 0;
		
        $this->load->library('datatables');	
        $this->datatables
             ->select($this->db->dbprefix('money_transfers').".id, reference ,date, ".$this->db->dbprefix('fr_branch').".company as company1, ".$this->db->dbprefix('to_branch').".company, (".$this->db->dbprefix('money_transfers').".amount) , ".$this->db->dbprefix('gl_charts').".accountname as  bank")
			->join('companies as erp_fr_branch','erp_fr_branch.id = money_transfers.from_branch_id', 'left')
			->join('companies as erp_to_branch','erp_to_branch.id = money_transfers.to_branch_id','left')
			->join('gl_charts', 'gl_charts.accountcode = money_transfers.bank_account', 'left')
            ->from("money_transfers")
			->order_by('money_transfers.id','DESC')
            ->add_column("Actions", "<div class=\"text-center\">
										<a class=\"tip\" title='" . $this->lang->line("edit_transfer") . "' href='" . site_url('transfer_money/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a>
										<a class=\"tip\" title='" . $this->lang->line("receipt") . "' href='" . site_url('transfer_money/print_receipt/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-print\"></i></a> 		
									</div>", $this->db->dbprefix('money_transfers').".id");   
        //->unset_column('id');
        echo $this->datatables->generate();
	}
	function print_receipt($id)
    {
			$this->erp->checkPermissions(false, true);
			$this->load->model('transfer_money_model'); 
			$setting = $this->settings_model->getSettings();
			$this->data['setting'] = $setting;
			$branch_id=$this->transfer_money_model->getBranchByID($id);
			$b_id = $branch_id->id;
			$this->data['branch_info'] = $this->transfer_money_model->getBranchAddress($b_id);
			$this->data['branch_transfer'] = $this->transfer_money_model->getBranchinfo($id);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'transfer_money/print_receipt', $this->data);
    }
	public function delete($id){
		$this->load->model('transfer_money_model');
		$d=$this->transfer_money_model->delete($id);
		if($d){
			$this->session->set_flashdata('message', $this->lang->line("transfer_delete_successful"));      
			redirect('transfer_money', 'refresh');	
		}
	}
	function edit($id){
		$this->erp->checkPermissions(false, true);
		$this->load->model('transfer_money_model'); 
		if ($this->input->get('id')) {
            $quote_id = $this->input->get('id');
        }
		$g=$this->transfer_money_model->getMoneyTransfer($id);
		$this->data['transfer']=$g;
			$this->data['chart_accounts'] = $this->transfer_money_model->getAllChartAccounts();
			$this->data['banks'] = $this->transfer_money_model->getBankAccount();
			$this->data['defualt_currency'] = $this->transfer_money_model->getSettingCurrncy();
			$this->data['purchases'] = $this->transfer_money_model->getpurchases();
			$this->data['branchs'] = $this->transfer_money_model->getBranchDName();
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'transfer_money/edit', $this->data);
	}
	
	function update($id){
		$this->load->model('transfer_money_model');
		$this->load->model('site');
		$data = array(
					'reference'			=> $this->input->post('reference'),
					'date'				=> $this->erp->fld(trim($this->input->post('date'))),
					'from_branch_id'    => $this->input->post('from_branch'),
					'to_branch_id'		=> $this->input->post('to_branch'),
					'amount'			=> str_replace(',', '', $this->input->post('amount')),
					'bank_account'		=> $this->input->post('bank_account'),
					'updated_by'		=> $this->session->userdata('user_id'),
            );
		$this->load->model('transfer_money_model');
		$i=$this->transfer_money_model->update($id,$data);
		if($i){
			$this->session->set_flashdata('Branch update successful.');
			redirect('transfer_money');
		}
	}
	
	public function transfer_actions(){
		$this->load->model('transfer_money_model');
		if ($this->input->post('action-form') == 'delete') {
                    foreach ($_POST['val'] as $id) {
					    $this->transfer_money_model->delete_transfer($id);
                    }
					$this->session->set_flashdata('message', lang("transfer_deleted"));
						redirect("transfer_money");
		}
		else{echo 'function not work!';}
	}

	public function ajaxBranchBalance($branch_id, $bank_code){
		$this->load->model('transfer_money_model');
		if ($rows = $this->transfer_money_model->getAjaxBranchBalance($branch_id, $bank_code)) {
            echo json_encode(array('amount'=>$rows->amount));
        } else {
            echo json_encode(false);
        }        
	}
	
}

?>