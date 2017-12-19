<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->load->library('form_validation');
        $this->load->model('db_model');
        $this->load->model('accounts_model');
    }

    public function index($start_date = NULL, $end_date = NULL)
    {
		
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
		$getBranch =$this->db_model->getBranchByUserId($this->session->userdata('user_id'));
		$branchName= $getBranch?$getBranch->name:'';
		$branch_id= $getBranch?$getBranch->id:'';
		$_SESSION["branchName"] = $branchName;
		$_SESSION["branch_id"] = $branch_id;
		
		$month = date('m', time());
		$year = date('Y', time());
		$last_day = days_in_month($month, $year);
		// $this->erp->print_arrays($last_day);
		
		$this->data['sales'] = $this->db_model->getLatestSales();
        $this->data['quotes'] = $this->db_model->getLastestQuotes();
        $this->data['applicant'] = $this->db_model->getLastFiveQuoat();
		
		$this->data['applicant_group'] = $this->db_model->getQuotesGroup();
        $this->data['contract'] = $this->db_model->getLastFiveContract();
        $this->data['five_dealer'] = $this->db_model->getLastFiveDealer();
		
        $this->data['purchases'] = $this->db_model->getLatestPurchases();
        $this->data['transfers'] = $this->db_model->getLatestTransfers();
        $this->data['customers'] = $this->db_model->getLatestCustomers();
        $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
		$this->data['dealers'] = $this->db_model->getLatestDealers(); 
		
        $this->data['chatData'] = $this->db_model->getChartData();
        $this->data['stock'] = $this->db_model->getStockValue();
        $this->data['bs'] = $this->db_model->getBestSeller();
		
		$this->data['app_d'] = $this->db_model->getAllApplicant($month, $year, $last_day);
		$this->data['contract_d'] = $this->db_model->getAllContract($month, $year, $last_day);
		$this->data['disbursement'] = $this->db_model->getDisbursementAmount($month, $year, $last_day);
		$this->data['rejceted_d'] = $this->db_model->getAllRejected($month, $year, $last_day);
		$this->data['payment']=$this->db_model->getPaymentAmount();
		$this->data['sales_id'] = $this->db_model->CountSaleID();
		$this->data['num_Id'] = $this->db_model->CountID_Quotes();
		$this->data['branch_name'] = $this->db_model->getAllBranch_Name();
		$this->data['loan_group_id'] = $this->db_model->CountGroupLoan_Quotes();
		$this->data['s_total'] = $this->db_model->getSaleTotal();
		$this->data['p_amount'] = $this->db_model->getPayment_amount();
		$this->data['expanse']	= $this->db_model->getExpanse($month, $year, $last_day);	
		
        $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
        $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
		
	 /*============Income Statement================================================================*/
		if (!$start_date) {
            $start = $this->db->escape(date('Y-m') . '-1');
            $start_date = date('Y-m') . '-1';
        } else {
            $start = $this->db->escape(urldecode($start_date));
        }
        if (!$end_date) {
            $end = $this->db->escape(date('Y-m-d H:i'));
            $end_date = date('Y-m-d H:i');
        } else {
            $end = $this->db->escape(urldecode($end_date));
        }
		$this->data['start'] = urldecode($start_date);
        $this->data['end'] = urldecode($end_date);
		
        $totalBeforeAyear = date('Y', strtotime($this->data['start'])) - 1;
		
		$from_date = date('Y-m-d H:m',strtotime(urldecode($start_date)));//'2014-08-01';
		$to_date = date('Y-m-d H:m',strtotime(urldecode($end_date. ' +1 day')));//'2015-09-01';
		
        $this->data['totalBeforeAyear'] = $totalBeforeAyear;
		$dataIncome = $this->accounts_model->getStatementByDate('40,70',$from_date,$to_date);
		$this->data['dataIncome'] = $dataIncome;
		
		$IncomeData = $this->accounts_model->getStatementByDate('40,70',$from_date,$to_date);
		$dataCost = $this->accounts_model->getStatementByDate('50',$from_date,$to_date);
		$this->data['dataCost'] = $dataCost;
	 /*============End Income Statement==================================================================*/
		
        $bc = array(array('link' => '#', 'page' => lang('dashboard')));
        $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);
        $this->page_construct('dashboard', $meta, $this->data);
    }
    function promotions()
    {
        $this->load->view($this->theme . 'promotions', $this->data);
    }
	public function test(){
		$this->data['applicant'] = $this->db_model->getLastFiveQuoat();
		print_r($this->db_model->getLastFiveQuoat());
	}
    function image_upload()
    {
        if (DEMO) {
            $error = array('error' => $this->lang->line('disabled_in_demo'));
            echo json_encode($error);
            exit;
        }
        $this->security->csrf_verify();
        if (isset($_FILES['file'])) {
            $this->load->library('upload');
            $config['upload_path'] = 'assets/uploads/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '500';
            $config['max_width'] = $this->Settings->iwidth;
            $config['max_height'] = $this->Settings->iheight;
            $config['encrypt_name'] = TRUE;
            $config['overwrite'] = FALSE;
            $config['max_filename'] = 25;
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('file')) {
                $error = $this->upload->display_errors();
                $error = array('error' => $error);
                echo json_encode($error);
                exit;
            }
            $photo = $this->upload->file_name;
            $array = array(
                'filelink' => base_url() . 'assets/uploads/images/' . $photo
            );
            echo stripslashes(json_encode($array));
            exit;

        } else {
            $error = array('error' => 'No file selected to upload!');
            echo json_encode($error);
            exit;
        }
    }

    function set_data($ud, $value)
    {
        $this->session->set_userdata($ud, $value);
        echo true;
    }

    function hideNotification($id = NULL)
    {
        $this->session->set_userdata('hidden' . $id, 1);
        echo true;
    }

    function language($lang = false)
    {
        if ($this->input->get('lang')) {
            $lang = $this->input->get('lang');
        }
        //$this->load->helper('cookie');
        $folder = 'erp/language/';
        $languagefiles = scandir($folder);
        if (in_array($lang, $languagefiles)) {
            $cookie = array(
                'name' => 'language',
                'value' => $lang,
                'expire' => '31536000',
                'prefix' => 'erp_',
                'secure' => false
            );

            $this->input->set_cookie($cookie);
        }
        redirect($_SERVER["HTTP_REFERER"]);
    }

    function download($file)
    {
        $this->load->helper('download');
        force_download('./files/'.$file, NULL);
        exit();
    }

	function screen(){
		if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['sales'] = $this->db_model->getLatestSales();
        $this->data['quotes'] = $this->db_model->getQuoteFive();
        $this->data['purchases'] = $this->db_model->getLatestPurchases();
        $this->data['transfers'] = $this->db_model->getLatestTransfers();
        $this->data['customers'] = $this->db_model->getLatestCustomers();
        $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
        $this->data['chatData'] = $this->db_model->getChartData();
        $this->data['stock'] = $this->db_model->getStockValue();
        $this->data['bs'] = $this->db_model->getBestSeller();
        $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
        $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
        $bc = array(array('link' => '#', 'page' => lang('screen')));
        $meta = array('page_title' => lang('screen'), 'bc' => $bc);
        $this->page_construct('screen', $meta, $this->data);
	}
}
