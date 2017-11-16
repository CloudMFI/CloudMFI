<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Shareholder extends MY_Controller
{

    function __construct()
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
        $this->lang->load('shareholder', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('quotes_model');
		$this->load->model('companies_model');
		$this->load->model('reports_model');
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

    function index($warehouse_id = NULL)
    {
       $this->erp->checkPermissions();
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
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('list_shareholder')));
		$meta = array('page_title' => lang('list_shareholder'), 'bc' => $bc);
        $this->page_construct('shareholder/index', $meta, $this->data);
    }
	public function getShareholder()
    {
        $this->erp->checkPermissions('index'); 

        $this->load->library('datatables');	
        $this->datatables
            ->select($this->db->dbprefix('companies').".id, ".
			$this->db->dbprefix('identify_types').".name AS s_name,". 
			$this->db->dbprefix('companies').".gov_id,".
			$this->db->dbprefix('companies').".name AS c_name,".
			$this->db->dbprefix('companies').".phone,email,". 
			$this->db->dbprefix('companies').".date_of_birth,".
			$this->db->dbprefix('companies').".address,". 
			$this->db->dbprefix('companies').".house_no")
			->join('identify_types','companies.identify = identify_types.id','LEFT')
            ->from("companies")
			->where("group_name","shareholder")
			->order_by('companies.id','DESC')
            ->add_column("Actions", "<div class=\"text-center\">
										<a class=\"tip\" title='" . $this->lang->line("edit_shareholder") . "' href='" . site_url('shareholder/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> 
										<a class=\"tip\" title='" . $this->lang->line("view_shareholder") . "' href='" . site_url('shareholder/view_details/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-eye\"></i></a>
									</div>", $this->db->dbprefix('companies').".id");   
        //->unset_column('id');
        echo $this->datatables->generate();
    }
	public function add()
    {
		$this->erp->checkPermissions();
		$this->load->model('quotes_model');
		$this->data['identify_type'] = $this->quotes_model->getIdentifyType();
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['modal_js'] = $this->site->modal_js();
		$this->load->view($this->theme . 'shareholder/add', $this->data);
    }
	public function insert(){
		$data = array(
				'group_name' => 'shareholder',
				'identify'	=>$this->input->post('jl_identify_id'),
				'gov_id'   	=> $this->input->post('jl_gov_id'),
                'name'   	=> $this->input->post('shareholder_name'),
				'date_of_birth'  => $this->erp->fld(trim($this->input->post('jl_dob'))),
                'phone'	    => $this->input->post('phone'),
                'age'   	=> $this->input->post('jl_age'),
				'email'	    =>$this->input->post('email'),
				'address'   =>$this->input->post('cus_pob'),
				'house_no'   =>$this->input->post('ldescription')
            );
		$this->load->model('shareholder_model');
		$i=$this->shareholder_model->insert($data);
		if($i){
			$this->session->set_flashdata('message',lang('shareholder_add_sucessful'));
			redirect('shareholder');
		}
	}
	public function edit($id){
		$this->erp->checkPermissions(false, true);
		$this->load->model('shareholder_model');
		$g=$this->shareholder_model->getShareholderByID($id);
		$this->data['shareholder']=$g;
		
		$this->data['identify_type'] = $this->shareholder_model->getIdentifyType();
		$this->data['shareholder_info'] = $this->shareholder_model->getIdentifyTypeByID($id);
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['modal_js'] = $this->site->modal_js();
		$this->load->view($this->theme . 'shareholder/edit', $this->data);
	}
	public function update($id){
		$data = array(
				'group_name' => 'shareholder',
				'identify'	=>$this->input->post('jl_identify_id'),
				'gov_id'   	=> $this->input->post('jl_gov_id'),
                'name'   	=> $this->input->post('shareholder_name'),
				'date_of_birth'  => $this->erp->fld(trim($this->input->post('jl_dob'))),
                'phone'	    => $this->input->post('phone'),
                'age'   	=> $this->input->post('jl_age'),
				'email'	    =>$this->input->post('email'),
				'address'   =>$this->input->post('cus_pob'),
				'house_no'   =>$this->input->post('ldescription')
            );
		$this->load->model('shareholder_model');
		$i=$this->shareholder_model->update($id,$data);
		if($i){
			$this->session->set_flashdata('message',lang('shareholder_updated_sucessful'));
			redirect('shareholder');
		}
	}
	public function shareholder_actions(){
		$this->load->model('shareholder_model');
		if ($this->input->post('action-form') == 'delete') {
			foreach ($_POST['val'] as $id) {   
				$this->shareholder_model->delete_shareholder($id);	
				 //echo lang("shareholder_deleted");die();
			}
			$this->session->set_flashdata('message', lang("shareholder_deleted"));
				redirect("shareholder");
		}else{
			$this->session->set_flashdata('error', lang("please_select_field_first"));
				redirect("shareholder");
			}
	}
	public function view_details($id){
		$this->erp->checkPermissions(false, true);
		$this->load->model('shareholder_model');
		$g=$this->shareholder_model->getShareholderByID($id);
		$this->data['shareholder']=$g;
		
		$this->data['identify_type'] = $this->shareholder_model->getIdentifyType();
		$this->data['shareholder_info'] = $this->shareholder_model->getIdentifyTypeByID($id);
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['modal_js'] = $this->site->modal_js();
		$this->load->view($this->theme . 'shareholder/view_details', $this->data);
	}
	public function getCapitals($id=NULL)
    {
        $this->erp->checkPermissions('index'); 
        $this->load->library('datatables');	
        $this->datatables
            ->select($this->db->dbprefix('capital').".id,reference,".$this->db->dbprefix('companies').".company,".$this->db->dbprefix('capital').".amount,".$this->db->dbprefix('capital').".bank_account,".$this->db->dbprefix('capital').".note")
			->join('companies','companies.id = capital.branch_id', 'left')
            ->from("capital")
			->where("capital.shareholder_id",$id)
			->order_by('capital.id','DESC')
            ->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("edit_capital") . "' href='" . site_url('capital/edit/$1') . "' data-toggle='modal' data-target='#myModal2'><i class=\"fa fa-edit\"></i></a> </div>", $this->db->dbprefix('capital').".id");   
        //->unset_column('id');
        echo $this->datatables->generate();
    }	
}

?>