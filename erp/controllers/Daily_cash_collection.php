<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Daily_Cash_Collection extends MY_Controller
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
		$this->load->model('sales_model');
		$this->load->model('settings_model');
		$this->load->model('pos_model');
		$this->load->model('down_payment_model');
        $this->lang->load('daily_cash_collection', $this->Settings->language);
        $this->load->library('form_validation');
		$this->load->model('Daily_Cash_Collection_model');
        $this->digital_upload_path = 'files/';
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '10240';
        $this->data['logo'] = true;
		
		$this->load->helper('text');
        
        if(!$this->Owner && !$this->Admin) {
            $gp = $this->site->checkPermissions();
            $this->permission = $gp[0];
            $this->permission[] = $gp[0];
        } else {
            $this->permission[] = NULL;
        }
        $this->default_biller_id = $this->site->default_biller_id();
    }

    function index()
    {
		$this->erp->checkPermissions();
		$this->data['billers'] = $this->site->getAllCompanies('biller');
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('daily_cash_collection')));
        $meta = array('page_title' => lang('daily_cash_collection'), 'bc' => $bc);
        $this->page_construct('daily_cash_collection/index', $meta, $this->data);
    }
	
	function getPayments(){
		 $this->erp->checkPermissions('index');
		
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
        if ($this->input->get('biller')) {
            $biller = $this->input->get('biller');
        } else {
            $biller = NULL;
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

        if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }

        $detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
        $payments_link = anchor('sales/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        //$email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('sales/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
        //$pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $pdf_link = anchor('sales/certify_latter', '<i class="fa fa-file-pdf-o"></i> ' . lang('CertifyLetter'));
        $email_link = anchor('sales/anex_contract', '<i class="fa fa-file-pdf-o"></i> ' . lang('Anex contract'));
        $return_link = anchor('sales/return_sale/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_sale'));
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_sale") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_sale') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>
			<li>' . $cabon_print . '</li>
            <li>' . $payments_link . '</li>
            <li>' . $add_payment_link . '</li>
            <li>' . $add_delivery_link . '</li>
            <li>' . $edit_link . '</li>
            <li>' . $pdf_link . '</li>
            <li>' . $email_link . '</li>
            <li>' . $return_link . '</li>
            <li>' . $delete_link . '</li>
        </ul>
		</div></div>';
		$setting = $this->settings_model->getSettings();
		$penalty_days = $setting->penalty_days?$setting->penalty_days:'';
		$penalty_amount = $setting->penalty_amount? $setting->penalty_amount:0;
        $this->load->library('datatables');
        
		$this->datatables
			->select($this->db->dbprefix('payments').".id as idd,".$this->db->dbprefix('payments').".reference_no as not, CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name, CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other, IF(".$this->db->dbprefix('companies').".phone2<>'', CONCAT(".$this->db->dbprefix('companies').".phone1, ' / ', ".$this->db->dbprefix('companies').".phone2), ".$this->db->dbprefix('companies').".phone1) as phone,".$this->db->dbprefix('sales').".biller, ".$this->db->dbprefix('loans').".dateline,".$this->db->dbprefix('payments').".date as payment_date,".$this->db->dbprefix('loans').".payment as install_payment,(SELECT SUM(amount) FROM ".$this->db->dbprefix('sale_services')." WHERE sale_id = ".$this->db->dbprefix('sales').".id) as total_service_charge, (".$this->db->dbprefix('loans').".payment + ".$this->db->dbprefix('loans').".total_service_charge + ".$this->db->dbprefix('loans').".other_amount +  ".$this->db->dbprefix('loans').".overdue_amount) as total_amount,".$this->db->dbprefix('payments').".amount as total_paid")
			->from('payments')
			->join('sales', 'erp_sales.id = erp_payments.sale_id', 'left')
			->join('loans','erp_sales.id = erp_loans.sale_id','RIGHT')
			->join('companies', 'sales.customer_id = companies.id', 'LEFT')
			->where('loans.reference_no != "" and loans.paid_amount > 0')
			->group_by('payments.loan_id')
			->order_by('payments.reference_no', 'asc');
		
		if ($reference_no) {
			$this->datatables->where('payments.reference_no', $reference_no);
		}
		if ($biller) {
			$this->datatables->where('sales.biller_id', $biller);
		}
		if ($customer) {
			$this->datatables->where('sales.customer_id', $customer);
		}
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
		
        $this->datatables->add_column("Actions", $action,$this->db->dbprefix('sales').".id");
        echo $this->datatables->generate();
	}
	
	function view_cash($id = null){
		$this->data['PaymentByRef']=$this->Daily_Cash_Collection_model->getPaymentByRef($id);
		$this->data['service']=$this->Daily_Cash_Collection_model->getServiceByID($id);
		$this->data['product']=$this->Daily_Cash_Collection_model->getItemByIDs($id);
		$this->data['exchange_rate_kh_c'] = $this->pos_model->getExchange_rate('KHM');
		
		$this->load->view($this->theme . 'Daily_Cash_Collection/view_cash', $this->data);
	}
}
