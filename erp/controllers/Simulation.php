<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Simulation extends MY_Controller
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
        $this->lang->load('simulation', $this->Settings->language);
        $this->load->library('form_validation');
		$this->load->model('companies_model');
		  if(!$this->Owner && !$this->Admin) {
            $gp = $this->site->checkPermissions();
            $this->permission = $gp[0];
            $this->permission[] = $gp[0];
        } else {
            $this->permission[] = NULL;
        }
    }
    function index()
    {
        $this->erp->checkPermissions('index', true, 'simulation');
		
		$this->load->model('interest_rate_model');
		$this->data['interest_rate'] = $this->interest_rate_model->getAllInterestRate();
        $this->data['terms'] = $this->interest_rate_model->getAllTerms();
		$this->data['services'] = $this->site->getServicesByStatus('1');
		$this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
		$this->data['advance_percentages'] = $this->site->getAllDownPercentage();
		$this->data['currencies'] = $this->site->getAllCurrencies();
               
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('simulation')));
        $meta = array('page_title' => lang('documents'), 'bc' => $bc);
        $this->page_construct('simulation/index', $meta, $this->data);
    }	
}

?>




