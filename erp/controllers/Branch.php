<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Branch extends MY_Controller
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
        $this->lang->load('branch', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('companies_model');
		$this->load->model('accounts_model');
		$this->load->model('documents_model');
		 
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
        $this->erp->checkPermissions('index', false, 'documents');

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('branch')));
        $meta = array('page_title' => lang('branch'), 'bc' => $bc);
        $this->page_construct('branch/index', $meta, $this->data);
    }
	function add()
    {
			$this->erp->checkPermissions(false, true);
			$this->load->model('branch_model');
			$this->data['countries'] = $this->site->getCountries();
			$this->data['chart_accounts'] = $this->branch_model->getAllChartAccounts();
			$this->data['purchases'] = $this->branch_model->getpurchases();
			//$this->erp->print_arrays($chart_accounts);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'branch/add', $this->data);
    }
	function insert(){
		$data = array(
				'group_name'  => 'biller',
				'branch_code' => $this->input->post('branch_code'),
                'name' 		  => $this->input->post('branch_name'),
				'phone'		  => $this->input->post('tel'),
				'email'		  => $this->input->post('e_mail'),
                'company'     => $this->input->post('branch_name'),
				'house_no'    => $this->input->post('house_no'),
                'state' 	  => $this->input->post('province'),
                'country'     => $this->input->post('country'),
				'group'		  => $this->input->post('group'),
				'village'	  => $this->input->post('village'),
				'street'	  => $this->input->post('street'),
				'sangkat'	  => $this->input->post('communce'),
				'district'    => $this->input->post('district')
            );
		$this->load->model('branch_model');
		$i=$this->branch_model->insert($data);
		if($i){
			$this->session->set_flashdata('Branch add successful.');
			redirect('branch');
		}
	}
	public function getBranch(){
		$this->erp->checkPermissions('index'); 
		$this->load->model('transfer_money_model');
		$defualt_currency = $this->transfer_money_model->getSettingCurrncy(); 		
		$defualt_rate = $defualt_currency->rate? $defualt_currency->rate : 0;	
        $this->load->library('datatables');	
		$this->db->query("SET SQL_BIG_SELECTS=1");//protect error max join table
        $this->datatables
            ->select($this->db->dbprefix('companies').".id, branch_code ,company, 
			CONCAT(".$this->db->dbprefix('village').".description , ' ', 
			".$this->db->dbprefix('communce').".description , ' ', 
			".$this->db->dbprefix('district').".description , ' ', 
			".$this->db->dbprefix('province').".description) AS name,phone,email")
			->join('addresses as erp_province', 'companies.state = erp_province.code', 'left')
			->join('addresses as erp_district','companies.district = erp_district.code', 'left')
			->join('addresses as erp_communce','companies.sangkat = erp_communce.code', 'left')
			->join('addresses as erp_village','companies.village = erp_village.code', 'left')
            ->from("companies")
			->where("group_name","biller")
            ->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("edit_branch") . "' href='" . site_url('branch/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> </div>", $this->db->dbprefix('companies').".id");   ////<a href='#' class='tip po' title='<b>" . $this->lang->line("delete_branch") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('branch/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a>
        //->unset_column('id');
        echo $this->datatables->generate();
	}
	public function delete($id){
		$this->load->model('branch_model');
		$d=$this->branch_model->delete($id);
		if($d){
			$this->session->set_flashdata('message', $this->lang->line("branch_delete_successful"));      
			redirect('branch', 'refresh');	
		}
	}
	function edit($id){
		$this->erp->checkPermissions(false, true);
		$this->load->model('branch_model');
		$g=$this->branch_model->getBranchByID($id);
		$this->data['branch']=$g;
			$this->data['countries'] = $this->site->getCountries();
			$this->data['chart_accounts'] = $this->branch_model->getAllChartAccounts();
			$this->data['purchases'] = $this->branch_model->getpurchases();
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'branch/edit', $this->data);
	}
	function update($id){
		$data = array(
				'group_name'  => 'biller',
				'branch_code' => $this->input->post('branch_code'),
                'name'        => $this->input->post('branch_name'),
                'company' 	  => $this->input->post('branch_name'),
				'phone'		  => $this->input->post('tel'),
				'email'		  => $this->input->post('e_mail'),
				'house_no'	  => $this->input->post('house_no'),
                'city' 		  => $this->input->post('city'),
                'state' 	  => $this->input->post('province'),
                'country'	  => $this->input->post('country'),
				'group'	      =>$this->input->post('group'),
				'village'	  =>$this->input->post('village'),
				'street'	  =>$this->input->post('street'),
				'sangkat'	  =>$this->input->post('communce'),
				'district'    =>$this->input->post('district')
            );
		$this->load->model('branch_model');
		$i=$this->branch_model->update($id,$data);
		if($i){
			$this->session->set_flashdata('Branch update successful.');
			redirect('branch');
		}
	}
	public function branch_actions(){
		$this->load->model('branch_model');
		if ($this->input->post('action-form') == 'delete') {
                    foreach ($_POST['val'] as $id) {   
					    $this->branch_model->delete_branch($id);	
                    }
					$this->session->set_flashdata('message', lang("branch_deleted"));
						redirect("branch");
		}
		else{echo 'function not work!';}
	}
	
	function getCities($id = NULL)
	{
		if ($rows = $this->site->getcitiesByCountyID($id)) {
            $data = json_encode($rows);
        } else {
            $data = false;
        }
        echo $data;
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
}

?>