<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Installment_Payment extends MY_Controller
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
        $this->lang->load('installment_payment', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('sales_model');
		$this->load->model('settings_model');
		$this->load->model('pos_model');
		$this->load->model('site');
		$this->load->model('down_payment_model');
		$this->load->model('installment_payment_model');
        $this->digital_upload_path = 'files/';
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '10240';
        $this->data['logo'] = true;

		$this->load->helper('text');
        $this->pos_settings = $this->pos_model->getSetting();
        $this->pos_settings->pin_code = $this->pos_settings->pin_code ? md5($this->pos_settings->pin_code) : NULL;
        $this->data['pos_settings'] = $this->pos_settings;

        if(!$this->Owner && !$this->Admin) {
            $gp = $this->site->checkPermissions();
            $this->permission = $gp[0];
            $this->permission[] = $gp[0];
        } else {
            $this->permission[] = NULL;
        }
        $this->default_biller_id = $this->site->default_biller_id();
    }

    function index($warehouse_id = NULL)
    {
		$this->erp->checkPermissions();
		$this->load->model('reports_model');
		$this->erp->load->model('reports_model');
		$this->data['products'] = $this->site->getProducts();
		$this->data['group_Loan'] = $this->site->getLoanGroups();
		$this->data['dealer'] = $this->site->getAllDealer('supplier');
		if(isset($_GET['d']) != ""){
			$date = $_GET['d'];
			$this->data['date'] = $date;
		}
        $this->data['warehouses'] = $this->site->getAllWarehouses();
		//getcustomerIDName
		//$customers = $this->installment_payment_model->getCustomerIDName(); 
		//$this->data['customers'] = $customers;
		
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner || $this->Admin) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
        } else {
            $this->data['warehouses'] = NULL;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : NULL;
        }
		$this->data['customers'] = $this->site->getCustomerIDName();
		$this->data['branches'] = $this->site->getAllBranches();
		$this->data['users'] = $this->reports_model->getStaff();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('repayments')));
        $meta = array('page_title' => lang('repayments'), 'bc' => $bc);
        $this->page_construct('installment_payment/index', $meta, $this->data);
    }
	
	function frequency_payments($warehouse_id = NULL)
    {
		$this->erp->checkPermissions();
		$this->load->model('reports_model');
		$this->erp->load->model('reports_model');
		$this->data['products'] = $this->site->getProducts();
		$this->data['group_Loan'] = $this->site->getLoanGroups();
		$this->data['dealer'] = $this->site->getAllDealer('supplier');
		if(isset($_GET['d']) != ""){
			$date = $_GET['d'];
			$this->data['date'] = $date;
		}
        $this->data['warehouses'] = $this->site->getAllWarehouses();
		//getcustomerIDName
		//$customers = $this->installment_payment_model->getCustomerIDName(); 
		//$this->data['customers'] = $customers;
		
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner || $this->Admin) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
        } else {
            $this->data['warehouses'] = NULL;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : NULL;
        }
		$this->data['customers'] = $this->site->getCustomerIDName();
		$this->data['branches'] = $this->site->getAllBranches();
		$this->data['users'] = $this->reports_model->getStaff();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('re_payments')));
        $meta = array('page_title' => lang('frequency_payments'), 'bc' => $bc);
        $this->page_construct('installment_payment/frequency_payments', $meta, $this->data);
    }

	function sales_loans(){
		//$this->erp->checkPermissions('loan', true, 'sales');
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('list_loans')));
        $meta = array('page_title' => lang('list_loans'), 'bc' => $bc);
        $this->page_construct('sales/loans', $meta, $this->data);
	}

	function loan_actions(){
		if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->sales_model->deleteSale($id);
                    }
                }

                if ($this->input->post('form_action') == 'combine_pay') {
                    //$html = $this->combine_pdf($_POST['val']);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('sales'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('shop'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('sale_status'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('grand_total'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('paid'));
					$this->excel->getActiveSheet()->SetCellValue('H1', lang('balance'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('payment_status'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sale = $this->sales_model->getExportLoans($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($sale->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sale->ref_no);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sale->biller);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $sale->customer);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $sale->sale_status);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $sale->grand_total);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $sale->paid);
						$this->excel->getActiveSheet()->SetCellValue('H' . $row, $sale->balance);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $sale->payment_status);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'sales_loans_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
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
                $this->session->set_flashdata('error', lang("no_sale_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
	}

	function update_loan($id){
		 $payids=explode(':', $id);
		 foreach($payids as $payid){
			 echo $payid;
		 }

	}

	function getCustomerInfo(){
		$cus_id = $this->input->get('customer_id');
		$customer_info = $this->sales_model->getCustomerByID($cus_id);
        exit(json_encode($customer_info));
	}

	
	
	function getDailyPayments($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('index');
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
		
        if ($this->input->get('applicant')) {
            $applicant = $this->input->get('applicant');
        } else {
            $applicant = NULL;
        }
		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('branch')){
            $branch_query = $this->input->get('branch');
        } else {
            $branch_query = NULL;
        }
        if ($this->input->get('gr_loan')) {
            $gr_loan = $this->input->get('gr_loan');
        } else {
            $gr_loan = NULL;
        }
		
        if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
		
		$approve_link = anchor('quotes/approvedApplicant/$2', '<i class="fa fa-file-text-o"></i> ' . lang('view_details'));
		$reschedule =anchor('Installment_payment/reschedule/$1/$2', '<i class="fa fa-money"></i> ' . lang('reschedule'), 'data-toggle="modal" data-target="#myModal"');
		$detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
        $payments_link = anchor('Installment_payment/view_Allpayments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        //$email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        
        $pdf_link = anchor('sales/certify_latter', '<i class="fa fa-file-pdf-o"></i> ' . lang('certify_letter'));
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
			<li>' . $approve_link . '</li>
			<!--<li>' . $reschedule . '</li>-->
			<!--<li>' . $detail_link . '</li>-->
            <li>' . $payments_link . '</li>
            <!--<li>' . $add_payment_link . '</li>-->
            <!--<li>' . $pdf_link . '</li>-->';
			/*if ($this->Owner || $this->Admin) {
			$action .= '<li>' . $delete_link . '</li>
				</ul>
				</div></div>';
			}*/
		$setting = $this->settings_model->getSettings();
		$loans = $this->installment_payment_model->getBalanceBysale($id);		
		$penalty_days = $setting->penalty_days?$setting->penalty_days:'';
		$penalty_amount = $setting->penalty_amount? $setting->penalty_amount:0;
		$penalty_types = $setting->penalty_types;
		$this->load->library('datatables');
		$setting = $this->down_payment_model->getSettingCurrncy();
        
            $this->datatables
                ->select($this->db->dbprefix('loans').".sale_id AS sale_id,
						".$this->db->dbprefix('loans').".sale_id as id, ".
						$this->db->dbprefix('sales').".quote_id as qi, ".
						$this->db->dbprefix('sales').".reference_no, ".
						$this->db->dbprefix('loan_groups').".name AS glname,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other,
						IF(".$this->db->dbprefix('companies').".phone2<>'', CONCAT(".$this->db->dbprefix('companies').".phone1, ' / ', ".$this->db->dbprefix('companies').".phone2), ".$this->db->dbprefix('companies').".phone1) as phone, 						
						DATE_FORMAT(".$this->db->dbprefix('loans').".dateline,'%d-%m-%Y'),
						DATE_FORMAT(NOW(),'%d-%m-%Y %h:%i:%s'),myBranch.name,
						CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) as co_name,
						".$this->db->dbprefix('loans').".payment,
						(SELECT SUM(payment) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id AND paid_amount = 0) as remaining,
						".$this->db->dbprefix('currencies').".name as cname,
						".$this->db->dbprefix('sales').".mfi as mfi")
                ->from('sales')
				->join('loans','sales.id=loans.sale_id','INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('loan_groups','loan_groups.id = sales.loan_group_id','left')
				->join('quotes','quotes.id = sales.quote_id','left')
				->join('quote_items','quote_items.quote_id = quotes.id','left')
				->join('users','sales.by_co=users.id','INNER')
				->join('companies AS myBranch', 'sales.branch_id= myBranch.id', 'left')
				->join('currencies','currencies.code = quote_items.currency_code','left')
				->where($this->db->dbprefix('sales').'.status =', 'loans')
				->where("(erp_sales.sale_status = 'activated' OR erp_sales.sale_status = 'completed')")
				//->where('loans.dateline =', date('Y-m-d'))
				->where('sales.frequency =', 1)
				->where('loans.paid_amount', 0)
				//->or_where('loans.owed >',0)
				->group_by('sales.id')
				->order_by('sales.id','DESC');
        
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->datatables->where('sales.branch_id', $this->session->branch_id);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            //$this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.contract_date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
		if ($applicant) {
			$this->datatables->where('sales.customer_id', $applicant);
		}
		if ($user_query) {
			$this->datatables->where('quotes.created_by', $user_query);
		}
		if ($branch_query) {
			$this->datatables->where('sales.branch_id', $branch_query);
		}
		if ($gr_loan) {
			$this->datatables->where('sales.loan_group_id', $gr_loan);
		}
        $this->datatables->add_column("Actions", $action,"sale_id,qi");
        $this->datatables->unset_column('sale_id');
		 $this->datatables->unset_column('qi');
		//$this->datatables->unset_column('mfi');
        echo $this->datatables->generate();
    }
	//====Weekly Payment====/
	function getWeeklyPayment()
    {
        $this->erp->checkPermissions('index');
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
		
        if ($this->input->get('applicant')) {
            $applicant = $this->input->get('applicant');
        } else {
            $applicant = NULL;
        }
		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('branch')){
            $branch_query = $this->input->get('branch');
        } else {
            $branch_query = NULL;
        }
        if ($this->input->get('gr_loan')) {
            $gr_loan = $this->input->get('gr_loan');
        } else {
            $gr_loan = NULL;
        }
		
        if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
		
		$approve_link = anchor('quotes/approvedApplicant/$2', '<i class="fa fa-file-text-o"></i> ' . lang('view_details'));
		$reschedule =anchor('Installment_payment/reschedule/$1/$2', '<i class="fa fa-money"></i> ' . lang('reschedule'), 'data-toggle="modal" data-target="#myModal"');
		$detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
        $payments_link = anchor('Installment_payment/view_Allpayments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
       
        $pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $pdf_link = anchor('sales/certify_latter', '<i class="fa fa-file-pdf-o"></i> ' . lang('certify_letter'));
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
			<li>' . $approve_link . '</li>
			<!--<li>' . $reschedule . '</li>-->
			<!--<li>' . $detail_link . '</li>-->
            <li>' . $payments_link . '</li>
            <!--<li>' . $add_payment_link . '</li>-->
            <!--<li>' . $pdf_link . '</li>-->';
			/*if ($this->Owner || $this->Admin) {
			$action .= '<li>' . $delete_link . '</li>
				</ul>
				</div></div>';
			}*/
		$setting = $this->settings_model->getSettings();
		$loans = $this->installment_payment_model->getBalanceBysale($id);		
		$penalty_days = $setting->penalty_days?$setting->penalty_days:'';
		$penalty_amount = $setting->penalty_amount? $setting->penalty_amount:0;
		$penalty_types = $setting->penalty_types;
		$this->load->library('datatables');
		$setting = $this->down_payment_model->getSettingCurrncy();
		
		$this->datatables
			->select($this->db->dbprefix('loans').".sale_id AS sale_id,
					".$this->db->dbprefix('loans').".sale_id as id, ".
					$this->db->dbprefix('sales').".quote_id as qi, ".
					$this->db->dbprefix('sales').".reference_no, ".
					$this->db->dbprefix('loan_groups').".name AS glname,
					CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
					CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other,
					IF(".$this->db->dbprefix('companies').".phone2<>'', CONCAT(".$this->db->dbprefix('companies').".phone1, ' / ', ".$this->db->dbprefix('companies').".phone2), ".$this->db->dbprefix('companies').".phone1) as phone, 						
					DATE_FORMAT(".$this->db->dbprefix('loans').".dateline,'%d-%m-%Y'),
					DATE_FORMAT(NOW(),'%d-%m-%Y %h:%i:%s'),myBranch.name,
					CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) as co_name,
					".$this->db->dbprefix('loans').".payment,
					(SELECT SUM(payment) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id AND paid_amount = 0) as remaining,
					".$this->db->dbprefix('currencies').".name as cname,
					".$this->db->dbprefix('sales').".mfi as mfi")
			->from('sales')
			->join('loans','sales.id=loans.sale_id','INNER')
			->join('companies', 'sales.customer_id = companies.id', 'INNER')
			->join('loan_groups','loan_groups.id = sales.loan_group_id','left')
			->join('quotes','quotes.id = sales.quote_id','left')
			->join('quote_items','quote_items.quote_id = quotes.id','left')
			->join('users','sales.by_co=users.id','INNER')
			->join('companies AS myBranch', 'sales.branch_id= myBranch.id', 'left')
			->join('currencies','currencies.code = quote_items.currency_code','left')
			->where($this->db->dbprefix('sales').'.status =', 'loans')
			->where("(erp_sales.sale_status = 'activated' OR erp_sales.sale_status = 'completed')")
			//->where('loans.dateline =', date('Y-m-d'))
			->where('sales.frequency =', 7)
			->where('loans.paid_amount', 0)
			//->or_where('loans.owed >',0)
			->group_by('sales.id')
			->order_by('sales.id','DESC');
				
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->datatables->where('sales.branch_id', $this->session->branch_id);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            //$this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }		
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.contract_date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
		if ($applicant) {
			$this->datatables->where('sales.customer_id', $applicant);
		}
		if ($user_query) {
			$this->datatables->where('quotes.created_by', $user_query);
		}
		if ($branch_query) {
			$this->datatables->where('sales.branch_id', $branch_query);
		}
		if ($gr_loan) {
			$this->datatables->where('sales.loan_group_id', $gr_loan);
		}												
        $this->datatables->add_column("Actions", $action,"sale_id,qi");
        $this->datatables->unset_column('sale_id');
		 $this->datatables->unset_column('qi');
        echo $this->datatables->generate();
    }
	
	
	//====Two Week Payment====/
	function getTwoWeekPayment()
    {
        $this->erp->checkPermissions('index');
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
		
        if ($this->input->get('applicant')) {
            $applicant = $this->input->get('applicant');
        } else {
            $applicant = NULL;
        }
		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('branch')){
            $branch_query = $this->input->get('branch');
        } else {
            $branch_query = NULL;
        }
        if ($this->input->get('gr_loan')) {
            $gr_loan = $this->input->get('gr_loan');
        } else {
            $gr_loan = NULL;
        }
		
        if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
		
		$approve_link = anchor('quotes/approvedApplicant/$2', '<i class="fa fa-file-text-o"></i> ' . lang('view_details'));
		$reschedule =anchor('Installment_payment/reschedule/$1/$2', '<i class="fa fa-money"></i> ' . lang('reschedule'), 'data-toggle="modal" data-target="#myModal"');
		$detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
        $payments_link = anchor('Installment_payment/view_Allpayments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
       
        $pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $pdf_link = anchor('sales/certify_latter', '<i class="fa fa-file-pdf-o"></i> ' . lang('certify_letter'));
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
			<li>' . $approve_link . '</li>
			<!--<li>' . $reschedule . '</li>-->
			<!--<li>' . $detail_link . '</li>-->
            <li>' . $payments_link . '</li>
            <!--<li>' . $add_payment_link . '</li>-->
            <!--<li>' . $pdf_link . '</li>-->';
			/*if ($this->Owner || $this->Admin) {
			$action .= '<li>' . $delete_link . '</li>
				</ul>
				</div></div>';
			}*/
		$setting = $this->settings_model->getSettings();
		$loans = $this->installment_payment_model->getBalanceBysale($id);		
		$penalty_days = $setting->penalty_days?$setting->penalty_days:'';
		$penalty_amount = $setting->penalty_amount? $setting->penalty_amount:0;
		$penalty_types = $setting->penalty_types;
		$this->load->library('datatables');
		$setting = $this->down_payment_model->getSettingCurrncy();
		
		$this->datatables
			->select($this->db->dbprefix('loans').".sale_id AS sale_id,
					".$this->db->dbprefix('loans').".sale_id as id, ".
					$this->db->dbprefix('sales').".quote_id as qi, ".
					$this->db->dbprefix('sales').".reference_no, ".
					$this->db->dbprefix('loan_groups').".name AS glname,
					CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
					CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other,
					IF(".$this->db->dbprefix('companies').".phone2<>'', CONCAT(".$this->db->dbprefix('companies').".phone1, ' / ', ".$this->db->dbprefix('companies').".phone2), ".$this->db->dbprefix('companies').".phone1) as phone, 						
					DATE_FORMAT(".$this->db->dbprefix('loans').".dateline,'%d-%m-%Y'),
					DATE_FORMAT(NOW(),'%d-%m-%Y %h:%i:%s'),myBranch.name,
					CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) as co_name,
					".$this->db->dbprefix('loans').".payment,
					(SELECT SUM(payment) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id AND paid_amount = 0) as remaining,
					".$this->db->dbprefix('currencies').".name as cname,
					".$this->db->dbprefix('sales').".mfi as mfi")
			->from('sales')
			->join('loans','sales.id=loans.sale_id','INNER')
			->join('companies', 'sales.customer_id = companies.id', 'INNER')
			->join('loan_groups','loan_groups.id = sales.loan_group_id','left')
			->join('quotes','quotes.id = sales.quote_id','left')
			->join('quote_items','quote_items.quote_id = quotes.id','left')
			->join('users','sales.by_co=users.id','INNER')
			->join('companies AS myBranch', 'sales.branch_id= myBranch.id', 'left')
			->join('currencies','currencies.code = quote_items.currency_code','left')
			->where($this->db->dbprefix('sales').'.status =', 'loans')
			->where("(erp_sales.sale_status = 'activated' OR erp_sales.sale_status = 'completed')")
			//->where('loans.dateline =', date('Y-m-d'))
			->where('sales.frequency =', 14)
			->where('loans.paid_amount', 0)
			//->or_where('loans.owed >',0)
			->group_by('sales.id')
			->order_by('sales.id','DESC');
				
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->datatables->where('sales.branch_id', $this->session->branch_id);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            //$this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }		
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.contract_date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
		if ($applicant) {
			$this->datatables->where('sales.customer_id', $applicant);
		}
		if ($user_query) {
			$this->datatables->where('quotes.created_by', $user_query);
		}
		if ($branch_query) {
			$this->datatables->where('sales.branch_id', $branch_query);
		}
		if ($gr_loan) {
			$this->datatables->where('sales.loan_group_id', $gr_loan);
		}												
        $this->datatables->add_column("Actions", $action,"sale_id,qi");
        $this->datatables->unset_column('sale_id');
		 $this->datatables->unset_column('qi');
        echo $this->datatables->generate();
    }
	
	//====Monthly Payment====/
	function getMonthlyPayment()
    {
        $this->erp->checkPermissions('index');
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
		
        if ($this->input->get('applicant')) {
            $applicant = $this->input->get('applicant');
        } else {
            $applicant = NULL;
        }
		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('branch')){
            $branch_query = $this->input->get('branch');
        } else {
            $branch_query = NULL;
        }
        if ($this->input->get('gr_loan')) {
            $gr_loan = $this->input->get('gr_loan');
        } else {
            $gr_loan = NULL;
        }
		
        if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
		
		$approve_link = anchor('quotes/approvedApplicant/$2', '<i class="fa fa-file-text-o"></i> ' . lang('view_details'));
		$reschedule =anchor('Installment_payment/reschedule/$1/$2', '<i class="fa fa-money"></i> ' . lang('reschedule'), 'data-toggle="modal" data-target="#myModal"');
		$detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
        $payments_link = anchor('Installment_payment/view_Allpayments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
       
        $pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $pdf_link = anchor('sales/certify_latter', '<i class="fa fa-file-pdf-o"></i> ' . lang('certify_letter'));
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
			<li>' . $approve_link . '</li>
			<!--<li>' . $reschedule . '</li>-->
			<!--<li>' . $detail_link . '</li>-->
            <li>' . $payments_link . '</li>
            <!--<li>' . $add_payment_link . '</li>-->
            <!--<li>' . $pdf_link . '</li>-->';
			/*if ($this->Owner || $this->Admin) {
			$action .= '<li>' . $delete_link . '</li>
				</ul>
				</div></div>';
			}*/
		$setting = $this->settings_model->getSettings();
		$loans = $this->installment_payment_model->getBalanceBysale($id);		
		$penalty_days = $setting->penalty_days?$setting->penalty_days:'';
		$penalty_amount = $setting->penalty_amount? $setting->penalty_amount:0;
		$penalty_types = $setting->penalty_types;
		$this->load->library('datatables');
		$setting = $this->down_payment_model->getSettingCurrncy();
		
		$this->datatables
			->select($this->db->dbprefix('loans').".sale_id AS sale_id,
					".$this->db->dbprefix('loans').".sale_id as id, ".
					$this->db->dbprefix('sales').".quote_id as qi, ".
					$this->db->dbprefix('sales').".reference_no, ".
					$this->db->dbprefix('loan_groups').".name AS glname,
					CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
					CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other,
					IF(".$this->db->dbprefix('companies').".phone2<>'', CONCAT(".$this->db->dbprefix('companies').".phone1, ' / ', ".$this->db->dbprefix('companies').".phone2), ".$this->db->dbprefix('companies').".phone1) as phone, 						
					DATE_FORMAT(".$this->db->dbprefix('loans').".dateline,'%d-%m-%Y'),
					DATE_FORMAT(NOW(),'%d-%m-%Y %h:%i:%s'),myBranch.name,
					CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) as co_name,
					".$this->db->dbprefix('loans').".payment,
					(SELECT SUM(payment) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id AND paid_amount = 0) as remaining,
					".$this->db->dbprefix('currencies').".name as cname,
					".$this->db->dbprefix('sales').".mfi as mfi")
			->from('sales')
			->join('loans','sales.id=loans.sale_id','INNER')
			->join('companies', 'sales.customer_id = companies.id', 'INNER')
			->join('loan_groups','loan_groups.id = sales.loan_group_id','left')
			->join('quotes','quotes.id = sales.quote_id','left')
			->join('quote_items','quote_items.quote_id = quotes.id','left')
			->join('users','sales.by_co=users.id','INNER')
			->join('companies AS myBranch', 'sales.branch_id= myBranch.id', 'left')
			->join('currencies','currencies.code = quote_items.currency_code','left')
			->where($this->db->dbprefix('sales').'.status =', 'loans')
			->where("(erp_sales.sale_status = 'activated' OR erp_sales.sale_status = 'completed')")
			//->where('loans.dateline =', date('Y-m-d'))
			->where('sales.frequency =', 30)
			->where('loans.paid_amount', 0)
			//->or_where('loans.owed >',0)
			->group_by('sales.id')
			->order_by('sales.id','DESC');
				
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->datatables->where('sales.branch_id', $this->session->branch_id);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            //$this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }		
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.contract_date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
		if ($applicant) {
			$this->datatables->where('sales.customer_id', $applicant);
		}
		if ($user_query) {
			$this->datatables->where('quotes.created_by', $user_query);
		}
		if ($branch_query) {
			$this->datatables->where('sales.branch_id', $branch_query);
		}
		if ($gr_loan) {
			$this->datatables->where('sales.loan_group_id', $gr_loan);
		}												
        $this->datatables->add_column("Actions", $action,"sale_id,qi");
        $this->datatables->unset_column('sale_id');
		 $this->datatables->unset_column('qi');
        echo $this->datatables->generate();
    }
	
	//====Yearly Payment====/
	function getYearlyPayment()
    {
        $this->erp->checkPermissions('index');
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
		
        if ($this->input->get('applicant')) {
            $applicant = $this->input->get('applicant');
        } else {
            $applicant = NULL;
        }
		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('branch')){
            $branch_query = $this->input->get('branch');
        } else {
            $branch_query = NULL;
        }
        if ($this->input->get('gr_loan')) {
            $gr_loan = $this->input->get('gr_loan');
        } else {
            $gr_loan = NULL;
        }
		
        if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
		
		$approve_link = anchor('quotes/approvedApplicant/$2', '<i class="fa fa-file-text-o"></i> ' . lang('view_details'));
		$reschedule =anchor('Installment_payment/reschedule/$1/$2', '<i class="fa fa-money"></i> ' . lang('reschedule'), 'data-toggle="modal" data-target="#myModal"');
		$detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
        $payments_link = anchor('Installment_payment/view_Allpayments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
       
        $pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $pdf_link = anchor('sales/certify_latter', '<i class="fa fa-file-pdf-o"></i> ' . lang('certify_letter'));
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
			<li>' . $approve_link . '</li>
			<!--<li>' . $reschedule . '</li>-->
			<!--<li>' . $detail_link . '</li>-->
            <li>' . $payments_link . '</li>
            <!--<li>' . $add_payment_link . '</li>-->
            <!--<li>' . $pdf_link . '</li>-->';
			/*if ($this->Owner || $this->Admin) {
			$action .= '<li>' . $delete_link . '</li>
				</ul>
				</div></div>';
			}*/
		$setting = $this->settings_model->getSettings();
		$loans = $this->installment_payment_model->getBalanceBysale($id);		
		$penalty_days = $setting->penalty_days?$setting->penalty_days:'';
		$penalty_amount = $setting->penalty_amount? $setting->penalty_amount:0;
		$penalty_types = $setting->penalty_types;
		$this->load->library('datatables');
		$setting = $this->down_payment_model->getSettingCurrncy();
		
		$this->datatables
			->select($this->db->dbprefix('loans').".sale_id AS sale_id,
					".$this->db->dbprefix('loans').".sale_id as id, ".
					$this->db->dbprefix('sales').".quote_id as qi, ".
					$this->db->dbprefix('sales').".reference_no, ".
					$this->db->dbprefix('loan_groups').".name AS glname,
					CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
					CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other,
					IF(".$this->db->dbprefix('companies').".phone2<>'', CONCAT(".$this->db->dbprefix('companies').".phone1, ' / ', ".$this->db->dbprefix('companies').".phone2), ".$this->db->dbprefix('companies').".phone1) as phone, 						
					DATE_FORMAT(".$this->db->dbprefix('loans').".dateline,'%d-%m-%Y'),
					DATE_FORMAT(NOW(),'%d-%m-%Y %h:%i:%s'),myBranch.name,
					CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) as co_name,
					".$this->db->dbprefix('loans').".payment,
					(SELECT SUM(payment) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id AND paid_amount = 0) as remaining,
					".$this->db->dbprefix('currencies').".name as cname,
					".$this->db->dbprefix('sales').".mfi as mfi")
			->from('sales')
			->join('loans','sales.id=loans.sale_id','INNER')
			->join('companies', 'sales.customer_id = companies.id', 'INNER')
			->join('loan_groups','loan_groups.id = sales.loan_group_id','left')
			->join('quotes','quotes.id = sales.quote_id','left')
			->join('quote_items','quote_items.quote_id = quotes.id','left')
			->join('users','sales.by_co=users.id','INNER')
			->join('companies AS myBranch', 'sales.branch_id= myBranch.id', 'left')
			->join('currencies','currencies.code = quote_items.currency_code','left')
			->where($this->db->dbprefix('sales').'.status =', 'loans')
			->where("(erp_sales.sale_status = 'activated' OR erp_sales.sale_status = 'completed')")
			//->where('loans.dateline =', date('Y-m-d'))
			->where('sales.frequency =', 360)
			->where('loans.paid_amount', 0)
			//->or_where('loans.owed >',0)
			->group_by('sales.id')
			->order_by('sales.id','DESC');
				
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->datatables->where('sales.branch_id', $this->session->branch_id);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            //$this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }		
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.contract_date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
		if ($applicant) {
			$this->datatables->where('sales.customer_id', $applicant);
		}
		if ($user_query) {
			$this->datatables->where('quotes.created_by', $user_query);
		}
		if ($branch_query) {
			$this->datatables->where('sales.branch_id', $branch_query);
		}
		if ($gr_loan) {
			$this->datatables->where('sales.loan_group_id', $gr_loan);
		}												
        $this->datatables->add_column("Actions", $action,"sale_id,qi");
        $this->datatables->unset_column('sale_id');
		 $this->datatables->unset_column('qi');
        echo $this->datatables->generate();
    }
	
	
	
	function getSales($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('index');
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
		
        if ($this->input->get('applicant')) {
            $applicant = $this->input->get('applicant');
        } else {
            $applicant = NULL;
        }
		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('branch')){
            $branch_query = $this->input->get('branch');
        } else {
            $branch_query = NULL;
        }
        if ($this->input->get('gr_loan')) {
            $gr_loan = $this->input->get('gr_loan');
        } else {
            $gr_loan = NULL;
        }
		
        if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
		
		$approve_link = anchor('quotes/approvedApplicant/$2', '<i class="fa fa-file-text-o"></i> ' . lang('view_details'));
		$reschedule =anchor('Installment_payment/reschedule/$1/$2', '<i class="fa fa-money"></i> ' . lang('reschedule'), 'data-toggle="modal" data-target="#myModal"');
		$detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
        $payments_link = anchor('Installment_payment/view_Allpayments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        //$email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $pdf_link = anchor('sales/certify_latter', '<i class="fa fa-file-pdf-o"></i> ' . lang('certify_letter'));
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
			<li>' . $approve_link . '</li>
			<!--<li>' . $reschedule . '</li>-->
			<!--<li>' . $detail_link . '</li>-->
            <li>' . $payments_link . '</li>
            <!--<li>' . $add_payment_link . '</li>-->
            <!--<li>' . $pdf_link . '</li>-->';
			/*if ($this->Owner || $this->Admin) {
			$action .= '<li>' . $delete_link . '</li>
				</ul>
				</div></div>';
			}*/
		$setting = $this->settings_model->getSettings();
		$loans = $this->installment_payment_model->getBalanceBysale($id);		
		$penalty_days = $setting->penalty_days?$setting->penalty_days:'';
		$penalty_amount = $setting->penalty_amount? $setting->penalty_amount:0;
		$penalty_types = $setting->penalty_types;
		$this->load->library('datatables');
		$setting = $this->down_payment_model->getSettingCurrncy();
        
            $this->datatables
                ->select($this->db->dbprefix('loans').".sale_id AS sale_id,
						".$this->db->dbprefix('loans').".sale_id as id, ".
						$this->db->dbprefix('sales').".quote_id as qi, ".
						$this->db->dbprefix('sales').".reference_no, ".
						$this->db->dbprefix('loan_groups').".name AS glname,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other,
						IF(".$this->db->dbprefix('companies').".phone2<>'', CONCAT(".$this->db->dbprefix('companies').".phone1, ' / ', ".$this->db->dbprefix('companies').".phone2), ".$this->db->dbprefix('companies').".phone1) as phone, 						
						DATE_FORMAT(".$this->db->dbprefix('loans').".dateline,'%d-%m-%Y'),
						DATE_FORMAT(NOW(),'%d-%m-%Y %h:%i:%s'),myBranch.name,
						CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) as co_name,
						".$this->db->dbprefix('loans').".payment,
						(SELECT SUM(payment) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id AND paid_amount = 0) as remaining,
						".$this->db->dbprefix('currencies').".name as cname,
						".$this->db->dbprefix('sales').".mfi as mfi")
                ->from('sales')
				->join('loans','sales.id=loans.sale_id','INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('loan_groups','loan_groups.id = sales.loan_group_id','left')
				->join('quotes','quotes.id = sales.quote_id','left')
				->join('quote_items','quote_items.quote_id = quotes.id','left')
				->join('users','sales.by_co=users.id','INNER')
				->join('companies AS myBranch', 'sales.branch_id= myBranch.id', 'left')
				->join('currencies','currencies.code = quote_items.currency_code','left')
				->where($this->db->dbprefix('sales').'.status =', 'loans')
				->where("(erp_sales.sale_status = 'activated' OR erp_sales.sale_status = 'completed')")
				->where('loans.dateline =', date('Y-m-d'))
				->where('loans.paid_amount', 0)
				//->or_where('loans.owed >',0)
				->group_by('sales.id')
				->order_by('sales.id','DESC');
				
        
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->datatables->where('sales.branch_id', $this->session->branch_id);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            //$this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.contract_date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
		if ($applicant) {
			$this->datatables->where('sales.customer_id', $applicant);
		}
		if ($user_query) {
			$this->datatables->where('quotes.created_by', $user_query);
		}
		if ($branch_query) {
			$this->datatables->where('sales.branch_id', $branch_query);
		}
		if ($gr_loan) {
			$this->datatables->where('sales.loan_group_id', $gr_loan);
		}												
        $this->datatables->add_column("Actions", $action,"sale_id,qi");
        $this->datatables->unset_column('sale_id');
		 $this->datatables->unset_column('qi');
		//$this->datatables->unset_column('mfi');
        echo $this->datatables->generate();
    }
	function getLateSales($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('index');
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
		
        if ($this->input->get('applicant')) {
            $applicant = $this->input->get('applicant');
        } else {
            $applicant = NULL;
        }
		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('branch')){
            $branch_query = $this->input->get('branch');
        } else {
            $branch_query = NULL;
        }
        if ($this->input->get('gr_loan')) {
            $gr_loan = $this->input->get('gr_loan');
        } else {
            $gr_loan = NULL;
        }
		
        if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
		
		$approve_link = anchor('quotes/approvedApplicant/$2', '<i class="fa fa-file-text-o"></i> ' . lang('view_details'));
		$reschedule =anchor('Installment_payment/reschedule/$1/$2', '<i class="fa fa-money"></i> ' . lang('reschedule'), 'data-toggle="modal" data-target="#myModal"');
		$detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
        $payments_link = anchor('Installment_payment/view_Allpayments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        //$email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        
        $pdf_link = anchor('sales/certify_latter', '<i class="fa fa-file-pdf-o"></i> ' . lang('certify_letter'));
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
			<li>' . $approve_link . '</li>
			<!--<li>' . $reschedule . '</li>-->
			<!--<li>' . $detail_link . '</li>-->
            <li>' . $payments_link . '</li>
            <!--<li>' . $add_payment_link . '</li>-->
            <!--<li>' . $pdf_link . '</li>-->';
			/*if ($this->Owner || $this->Admin) {
			$action .= '<li>' . $delete_link . '</li>
				</ul>
				</div></div>';
			}*/
		$setting = $this->settings_model->getSettings();
		$loans = $this->installment_payment_model->getBalanceBysale($id);		
		$penalty_days = $setting->penalty_days?$setting->penalty_days:'';
		$penalty_amount = $setting->penalty_amount? $setting->penalty_amount:0;
		$penalty_types = $setting->penalty_types;
		$this->load->library('datatables');
		$setting = $this->down_payment_model->getSettingCurrncy();
        
            $this->datatables
                ->select($this->db->dbprefix('loans').".sale_id AS sale_id,
						".$this->db->dbprefix('loans').".sale_id as id, ".
						$this->db->dbprefix('sales').".quote_id as qi, ".
						$this->db->dbprefix('sales').".reference_no, ".
						$this->db->dbprefix('loan_groups').".name AS glname,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other,
						IF(".$this->db->dbprefix('companies').".phone2<>'', CONCAT(".$this->db->dbprefix('companies').".phone1, ' / ', ".$this->db->dbprefix('companies').".phone2), ".$this->db->dbprefix('companies').".phone1) as phone, 						
						DATE_FORMAT(".$this->db->dbprefix('loans').".dateline,'%d-%m-%Y'),
						DATE_FORMAT(NOW(),'%d-%m-%Y %h:%i:%s'),".
						$penalty_days .",
						IF(DATE_ADD(".$this->db->dbprefix('loans').".dateline, INTERVAL ".$penalty_days." DAY) <= CURDATE(), DATEDIFF(CURDATE(), ".$this->db->dbprefix('loans').".dateline), '') as due_days, 
						".$this->db->dbprefix('loans').".payment,
						((IF(DATE_ADD(".$this->db->dbprefix('loans').".dateline, INTERVAL ".$penalty_days." DAY) <= CURDATE(), (DATEDIFF(CURDATE(), ".($this->db->dbprefix('loans').".dateline)) * ".(($penalty_types=="Percentage")? $penalty_amount ." * (SELECT SUM(principle) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id)":($penalty_amount))).", ''))* (".$this->db->dbprefix('currencies').".rate / ".$setting->rate .")) as penalty_amount,
						
						(SELECT SUM(payment) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id AND paid_amount = 0) as remaining,
						".$this->db->dbprefix('currencies').".name as cname,
						".$this->db->dbprefix('sales').".mfi as mfi")
                ->from('sales')
				->join('loans','sales.id=loans.sale_id','INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('loan_groups','loan_groups.id = sales.loan_group_id','left')
				->join('quotes','quotes.id = sales.quote_id','left')
				->join('quote_items','quote_items.quote_id = quotes.id','left')
				->join('users','sales.by_co=users.id','INNER')
				->join('currencies','currencies.code = quote_items.currency_code','left')
				->where($this->db->dbprefix('sales').'.status =', 'loans')
				->where("(erp_sales.sale_status = 'activated' OR erp_sales.sale_status = 'completed')")
				->where('loans.dateline <', date('Y-m-d'))
				->where('loans.paid_amount', 0)
				//->or_where('loans.owed >',0)
				->group_by('sales.id')
				->order_by('sales.id','DESC');
        
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->datatables->where('sales.branch_id', $this->session->branch_id);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            //$this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }		
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.contract_date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
		if ($applicant) {
			$this->datatables->where('sales.customer_id', $applicant);
		}
		if ($user_query) {
			$this->datatables->where('quotes.created_by', $user_query);
		}
		if ($branch_query) {
			$this->datatables->where('sales.branch_id', $branch_query);
		}
		if ($gr_loan) {
			$this->datatables->where('sales.loan_group_id', $gr_loan);
		}												
        $this->datatables->add_column("Actions", $action,"sale_id,qi");
        $this->datatables->unset_column('sale_id');
		 $this->datatables->unset_column('qi');
		//$this->datatables->unset_column('mfi');
        echo $this->datatables->generate();
    }
	
	
	function getAllSales($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('index');
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
		
        if ($this->input->get('applicant')) {
            $applicant = $this->input->get('applicant');
        } else {
            $applicant = NULL;
        }
		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('branch')){
            $branch_query = $this->input->get('branch');
        } else {
            $branch_query = NULL;
        }
        if ($this->input->get('gr_loan')) {
            $gr_loan = $this->input->get('gr_loan');
        } else {
            $gr_loan = NULL;
        }
		
        if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
		
		$approve_link = anchor('quotes/approvedApplicant/$2', '<i class="fa fa-file-text-o"></i> ' . lang('view_details'));
		$reschedule =anchor('Installment_payment/reschedule/$1/$2', '<i class="fa fa-money"></i> ' . lang('reschedule'), 'data-toggle="modal" data-target="#myModal"');
		$detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
        $payments_link = anchor('Installment_payment/view_Allpayments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        //$email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        
        $pdf_link = anchor('sales/certify_latter', '<i class="fa fa-file-pdf-o"></i> ' . lang('certify_letter'));
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
			<li>' . $approve_link . '</li>
			<!--<li>' . $reschedule . '</li>-->
			<!--<li>' . $detail_link . '</li>-->
            <li>' . $payments_link . '</li>
            <!--<li>' . $add_payment_link . '</li>-->
            <!--<li>' . $pdf_link . '</li>-->';
			/*if ($this->Owner || $this->Admin) {
			$action .= '<li>' . $delete_link . '</li>
				</ul>
				</div></div>';
			}*/
		$setting = $this->settings_model->getSettings();
		$loans = $this->installment_payment_model->getBalanceBysale($id);		
		$penalty_days = $setting->penalty_days?$setting->penalty_days:'';
		$penalty_amount = $setting->penalty_amount? $setting->penalty_amount:0;
		$penalty_types = $setting->penalty_types;
		$this->load->library('datatables');
		$setting = $this->down_payment_model->getSettingCurrncy();
        
            $this->datatables
                ->select($this->db->dbprefix('loans').".sale_id AS sale_id,
						".$this->db->dbprefix('loans').".sale_id as id, ".
						$this->db->dbprefix('sales').".quote_id as qi, ".
						$this->db->dbprefix('sales').".reference_no, ".
						$this->db->dbprefix('loan_groups').".name AS glname,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other,
						IF(".$this->db->dbprefix('companies').".phone2<>'', CONCAT(".$this->db->dbprefix('companies').".phone1, ' / ', ".$this->db->dbprefix('companies').".phone2), ".$this->db->dbprefix('companies').".phone1) as phone, 						
						DATE_FORMAT(".$this->db->dbprefix('loans').".dateline,'%d-%m-%Y'),
						DATE_FORMAT(NOW(),'%d-%m-%Y %h:%i:%s'),".
						$penalty_days .",
						IF(DATE_ADD(".$this->db->dbprefix('loans').".dateline, INTERVAL ".$penalty_days." DAY) <= CURDATE(), DATEDIFF(CURDATE(), ".$this->db->dbprefix('loans').".dateline), '') as due_days, 
						".$this->db->dbprefix('loans').".payment,
						((IF(DATE_ADD(".$this->db->dbprefix('loans').".dateline, INTERVAL ".$penalty_days." DAY) <= CURDATE(), (DATEDIFF(CURDATE(), ".($this->db->dbprefix('loans').".dateline)) * ".(($penalty_types=="Percentage")? $penalty_amount ." * (SELECT SUM(principle) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id)":($penalty_amount))).", ''))* (".$this->db->dbprefix('currencies').".rate / ".$setting->rate .")) as penalty_amount,
						
						(SELECT SUM(payment) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id AND paid_amount = 0) as remaining,
						".$this->db->dbprefix('currencies').".name as cname,
						".$this->db->dbprefix('sales').".mfi as mfi")
                ->from('sales')
				->join('loans','sales.id=loans.sale_id','INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('loan_groups','loan_groups.id = sales.loan_group_id','left')
				->join('quotes','quotes.id = sales.quote_id','left')
				->join('quote_items','quote_items.quote_id = quotes.id','left')
				->join('users','sales.by_co=users.id','INNER')
				->join('currencies','currencies.code = quote_items.currency_code','left')
				->where($this->db->dbprefix('sales').'.status =', 'loans')
				->where("(erp_sales.sale_status = 'activated' OR erp_sales.sale_status = 'completed')")
				->where('erp_sales.status','loans')
				->where('loans.paid_amount', 0)
				//->or_where('loans.owed >',0)
				->group_by('sales.id')
				->order_by('sales.id','DESC',10);
        
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->datatables->where('sales.branch_id', $this->session->branch_id);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && (!$this->session->userdata('view_right'))) {
            //$this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }		
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.contract_date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
		if ($applicant) {
			$this->datatables->where('sales.customer_id', $applicant);
		}
		if ($user_query) {
			$this->datatables->where('quotes.created_by', $user_query);
		}
		if ($branch_query) {
			$this->datatables->where('sales.branch_id', $branch_query);
		}
		if ($gr_loan) {
			$this->datatables->where('sales.loan_group_id', $gr_loan);
		}												
        $this->datatables->add_column("Actions", $action,"sale_id,qi");
        $this->datatables->unset_column('sale_id');
		 $this->datatables->unset_column('qi');
		//$this->datatables->unset_column('mfi');
        echo $this->datatables->generate();
    }
	//------------get pending sale && Pos partial--------	
	//$penalty_amount = $setting->penalty_amount? $setting->penalty_amount:0;
	function getSales_pending($warehouse_id = NULL, $dt = NULL)
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

        if ($this->input->get('search_id')) {
            $search_id = $this->input->get('search_id');
        } else {
            $search_id = NULL;
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
        $email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('sales/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
        $pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
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
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';



        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables
                ->select("id, date, reference_no, biller, customer, sale_status, grand_total, paid, (grand_total-paid) as balance, payment_status")
                ->from('sales')
				->where('payment_status !=', 'paid')
				->where('payment_status !=', 'Returned')
                ->where('warehouse_id', $warehouse_id);
        } else {
			$this->datatables
			->select("id, date, reference_no, biller, customer, sale_status, grand_total, paid, (grand_total-paid) as balance, payment_status")
			->from('sales')
			->where('payment_status !=', 'Returned')
			->where('payment_status !=', 'paid')
			->where('(grand_total-paid) <> ', 0);
			if(isset($_REQUEST['d'])){
				$date = $_GET['d'];
				$date1 = str_replace("/", "-", $date);
				$date =  date('Y-m-d', strtotime($date1));

				$this->datatables
				->where("date >=", $date)
				->where('DATE_SUB(date, INTERVAL 1 DAY) <= CURDATE()')
				->where('sales.payment_term <>', 0);
			}
        }
        //$this->datatables->where('pos !=', 1);
        if ($this->permission['sales-index'] = ''){
            if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin) {
                $this->datatables->where('created_by', $this->session->userdata('user_id'));
            } elseif ($this->Customer) {
                $this->datatables->where('customer_id', $this->session->userdata('user_id'));
            }
        }

        if ($search_id) {
            $this->datatables->where('sales.id', $search_id);
        }

		if ($user_query) {
			$this->datatables->where('sales.created_by', $user_query);
		}/*
		if ($customer) {
			$this->datatables->where('sales.id', $customer);
		}*/
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

		if($dt == 30){
			$this->datatables->where('date('. $this->db->dbprefix('sales') .'.date) > CURDATE() AND date('. $this->db->dbprefix('sales') .'.date) <= DATE_ADD(now(), INTERVAL + 30 DAY)');
		}elseif($dt == 60){
			$this->datatables->where('date('. $this->db->dbprefix('sales') .'.date) > DATE_ADD(now(), INTERVAL + 30 DAY) AND date('. $this->db->dbprefix('sales') .'.date) <= DATE_ADD(now(), INTERVAL + 60 DAY)');
		}elseif($dt == 90){
			$this->datatables->where('date('. $this->db->dbprefix('sales') .'.date) > DATE_ADD(now(), INTERVAL + 60 DAY) AND date('. $this->db->dbprefix('sales') .'.date) <= DATE_ADD(now(), INTERVAL + 90 DAY)');
		}elseif($dt == 91){
			$this->datatables->where('date('. $this->db->dbprefix('sales') .'.date) >= DATE_ADD(now(), INTERVAL + 90 DAY)');
		}

        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }

	function return_sales($warehouse_id = NULL)
    {
        $this->erp->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
        } else {
            $user = $this->site->getUser();
            $this->data['warehouses'] = NULL;
            $this->data['warehouse_id'] = $user->warehouse_id;
            $this->data['warehouse'] = $user->warehouse_id ? $this->site->getWarehouseByID($user->warehouse_id) : NULL;
        }

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('return_sales')));
        $meta = array('page_title' => lang('return_sales'), 'bc' => $bc);
        $this->page_construct('sales/return_sales', $meta, $this->data);
    }

    function getReturns($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('return_sales');

        if (!$this->Owner && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        $detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i>');
        $edit_link = ''; //anchor('sales/edit/$1', '<i class="fa fa-edit"></i>', 'class="reedit"');
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_return_sale") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete_return/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a>";
        $action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $delete_link . '</div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables
                ->select($this->db->dbprefix('return_sales') . ".date as date, " . $this->db->dbprefix('return_sales') . ".reference_no as ref, (
				CASE
				WHEN erp_return_sales.sale_id > 0 THEN
					erp_sales.reference_no
				ELSE
					(
						SELECT
							GROUP_CONCAT(s.reference_no SEPARATOR '\r\n')
						FROM
							erp_return_items ri
						INNER JOIN erp_return_sales rs ON rs.id = ri.return_id
						LEFT JOIN erp_sales s ON s.id = ri.sale_id
						WHERE
							ri.return_id = erp_return_sales.id
					)
				END
			) AS sale_ref," . $this->db->dbprefix('return_sales') . ".biller, " . $this->db->dbprefix('return_sales') . ".customer, " . $this->db->dbprefix('return_sales') . ".surcharge, " . $this->db->dbprefix('return_sales') . ".grand_total, " . $this->db->dbprefix('return_sales') . ".id as id")
                ->join('sales', 'sales.id=return_sales.sale_id', 'left')
				->join('return_items', 'return_items.return_id = return_sales.id', 'left')
                ->from('return_sales')
                ->group_by('return_sales.id')
                ->where('return_sales.warehouse_id', $warehouse_id);
        } else {
			/*
            $this->datatables
                ->select($this->db->dbprefix('return_sales') . ".date as date, " . $this->db->dbprefix('return_sales') . ".reference_no as ref, " . $this->db->dbprefix('sales') . ".reference_no as sal_ref, " . $this->db->dbprefix('return_sales') . ".biller, " . $this->db->dbprefix('return_sales') . ".customer, " . $this->db->dbprefix('return_sales') . ".surcharge, " . $this->db->dbprefix('return_sales') . ".grand_total, " . $this->db->dbprefix('return_sales') . ".id as id")
                ->join('sales', 'sales.id=return_sales.sale_id', 'left')
                ->from('return_sales')
                ->group_by('return_sales.id');
			*/
			$this->datatables
                ->select($this->db->dbprefix('return_sales') . ".date as date, " . $this->db->dbprefix('return_sales') . ".reference_no as ref,
							(
									CASE
									WHEN erp_return_sales.sale_id > 0 THEN
										erp_sales.reference_no
									ELSE
										(
											SELECT
												GROUP_CONCAT(s.reference_no SEPARATOR '\r\n')
											FROM
												erp_return_items ri
											INNER JOIN erp_return_sales rs ON rs.id = ri.return_id
											LEFT JOIN erp_sales s ON s.id = ri.sale_id
											WHERE
												ri.return_id = erp_return_sales.id
										)
									END
							) AS sale_ref,
						" . $this->db->dbprefix('return_sales') . ".biller, " . $this->db->dbprefix('return_sales') . ".customer, " . $this->db->dbprefix('return_sales') . ".surcharge, " . $this->db->dbprefix('return_sales') . ".grand_total, " . $this->db->dbprefix('return_sales') . ".id as id")
                ->join('sales', 'sales.id=return_sales.sale_id', 'left')
				->join('return_items', 'return_items.return_id = return_sales.id', 'left')
                ->from('return_sales')
                ->group_by('return_sales.id');
        }
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin) {
            $this->datatables->where('return_sales.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('return_sales.customer_id', $this->session->userdata('customer_id'));
        }
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }

    function checkReturn($id){
        if($id){
            $isReturn = $this->sales_model->getReturnSaleBySaleID($id);
            if($isReturn){
                echo true;
            }else{
                echo false;
            }
        }
    }

    function modal_view_ar($id = NULL, $type = NULL){
        $this->erp->checkPermissions('index', TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->load->model('pos_model');
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getInvoiceByID($id);
        $this->erp->view_rights($inv->created_by, TRUE);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
        $this->data['cust_id'] = $inv->customer_id;
        $this->data['type_view'] = $type;

        $this->load->view($this->theme.'sales/modal_view_ar_aping', $this->data);
    }

    function modal_view($id = NULL)
    {
        $this->erp->checkPermissions('index', TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getInvoiceByID($id);
        $this->erp->view_rights($inv->created_by, TRUE);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);

        $this->load->view($this->theme.'sales/modal_view', $this->data);
    }

	function loan_view($id = NULL)
    {
        $this->erp->checkPermissions('index', TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        //$list_loans = $this->sales_model->getLoansByID($id);
		$list_items = $this->sales_model->getItemsByID($id);
		$sale_info = $this->sales_model->getSaleInfoByID($id);
		$loan_view1 = $this->sales_model->getLoanView($id);
		$month_ = $this->sales_model->getMonths($id);
		$balance = $loan_view1->balance + $loan_view1->principle;
		$this->data['list_items'] = $list_items;
		$this->data['sale_info'] = $sale_info;
		$this->data['sale_id'] = $id;
		$this->data['balance'] = $balance;
		$this->data['loan_row'] = $loan_view1;
		$this->data['month'] = $month_;
		$this->data['cust_info'] = $this->sales_model->getCustomerByID($sale_info->customer_id);
        $this->load->view($this->theme.'sales/loan_view', $this->data);
    }

	function list_loan_data($id = NULL)
	{
		$this->erp->checkPermissions('index');

		if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->load->library('datatables');
		$this->datatables
			->select("loans.id, loans.period,
					 loans.interest, loans.principle, loans.payment,
					 loans.balance, loans.dateline,loans.note,users.username,paid_date
					 ")
			->from('loans')
			->join('users','users.id=loans.created_by','LEFT')
			->where('sale_id=', $id);


        echo $this->datatables->generate();
	}

	function tax_invoice($id = NULL)
    {
		$this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();

		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

		$inv = $this->sales_model->getInvoiceByID($id);
        $this->erp->view_rights($inv->created_by, TRUE);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
		$this->data['vattin'] = $this->site->getTaxRateByID($inv->order_tax_id);
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
		$this->data['logo'] = true;
        $this->load->view($this->theme . 'sales/tax_invoice', $this->data);
    }

	function invoice($id = NULL)
    {
		$this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();

		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

		$inv = $this->sales_model->getInvoiceByID($id);
        $this->erp->view_rights($inv->created_by, TRUE);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
		$this->data['seller'] = $this->site->getUser($inv->saleman_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
		$this->data['vattin'] = $this->site->getTaxRateByID($inv->order_tax_id);
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
		$this->data['logo'] = true;
        $this->load->view($this->theme . 'sales/invoice', $this->data);
    }

	function print_receipt($id = NULL)
    {
		$this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();

		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

		$inv = $this->sales_model->getInvoiceByID($id);
        $this->erp->view_rights($inv->created_by, TRUE);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
		$this->data['cashier'] = $this->site->getUser($inv->saleman_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
		$this->data['vattin'] = $this->site->getTaxRateByID($inv->order_tax_id);
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
		$this->data['payment'] = $this->sales_model->getPaymentBySaleID($id);
		$this->data['logo'] = true;
        $this->load->view($this->theme . 'sales/print_receipt', $this->data);
    }

	function cash_receipt($id = NULL)
    {
		$this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();

		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

		$payment = $this->sales_model->getPaymentByID($id);
        $inv = $this->sales_model->getInvoiceByID($payment->sale_id);
		$payments = $this->sales_model->getCurrentBalance($inv->id);
		$current_balance = $inv->grand_total;
		foreach($payments as $curr_pay) {
			if ($curr_pay->id < $id) {
				$current_balance -= $curr_pay->amount;
			}
		}

		$this->data['curr_balance'] = $current_balance;
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['inv'] = $inv;
        $this->data['payment'] = $payment;
        $this->data['page_title'] = $this->lang->line("payment_note");

		//$this->erp->print_arrays($payment);

        $this->load->view($this->theme . 'sales/cash_receipt', $this->data);
    }

	function invoice_landscap_a5($id = NULL)
    {
		$this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();

		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

		$inv = $this->sales_model->getInvoiceByID($id);
        $this->erp->view_rights($inv->created_by, TRUE);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
		$this->data['cashier'] = $this->site->getUser($inv->saleman_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
		$this->data['vattin'] = $this->site->getTaxRateByID($inv->order_tax_id);
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
		$this->data['payment'] = $this->sales_model->getPaymentBySaleID($id);
		$this->data['logo'] = true;
        $this->load->view($this->theme . 'sales/invoice_landscap_a5', $this->data);
    }

	function invoice_poto($id = NULL)
    {
		$this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();

		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

		$inv = $this->sales_model->getInvoiceByID($id);
        $this->erp->view_rights($inv->created_by, TRUE);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
		$this->data['cashier'] = $this->site->getUser($inv->saleman_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
		$this->data['vattin'] = $this->site->getTaxRateByID($inv->order_tax_id);
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
		$this->data['payment'] = $this->sales_model->getPaymentBySaleID($id);
		$this->data['logo'] = true;
        $this->load->view($this->theme . 'sales/invoice_poto', $this->data);
    }

    function view($id = NULL)
    {
        $this->erp->checkPermissions('index');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getInvoiceByID($id);
        $this->erp->view_rights($inv->created_by);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;

        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
        //$this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
        $this->data['paypal'] = $this->sales_model->getPaypalSettings();
        $this->data['skrill'] = $this->sales_model->getSkrillSettings();

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('view')));
        $meta = array('page_title' => lang('view_sales_details'), 'bc' => $bc);
        $this->page_construct('sales/view', $meta, $this->data);
    }

    function view_return($id = NULL)
    {
        $this->erp->checkPermissions('return_sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getReturnByID($id);
        $this->erp->view_rights($inv->created_by);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $this->data['rows'] = $this->sales_model->getAllReturnItems($id);
        $this->data['sale'] = $this->sales_model->getInvoiceByID($inv->sale_id);
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('view_return')));
        $meta = array('page_title' => lang('view_return_details'), 'bc' => $bc);
        $this->page_construct('sales/view_return', $meta, $this->data);
    }

    function pdf($id = NULL, $view = NULL, $save_bufffer = NULL)
    {
        $this->erp->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getInvoiceByID($id);
        $this->erp->view_rights($inv->created_by);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
        $this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
        //$this->data['paypal'] = $this->sales_model->getPaypalSettings();
        //$this->data['skrill'] = $this->sales_model->getSkrillSettings();

        $name = lang("sale") . "_" . str_replace('/', '_', $inv->reference_no) . ".pdf";
        $html = $this->load->view($this->theme . 'sales/pdf', $this->data, TRUE);
        if ($view) {
            $this->load->view($this->theme . 'sales/pdf', $this->data);
        } elseif ($save_bufffer) {
            return $this->erp->generate_pdf($html, $name, $save_bufffer, $this->data['biller']->invoice_footer);
        } else {
            $this->erp->generate_pdf($html, $name, FALSE, $this->data['biller']->invoice_footer);
        }
    }

    function email($id = NULL)
    {
        $this->erp->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $inv = $this->sales_model->getInvoiceByID($id);
        $this->form_validation->set_rules('to', lang("to") . " " . lang("email"), 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', lang("subject"), 'trim|required');
        $this->form_validation->set_rules('cc', lang("cc"), 'trim');
        $this->form_validation->set_rules('bcc', lang("bcc"), 'trim');
        $this->form_validation->set_rules('note', lang("message"), 'trim');

        if ($this->form_validation->run() == true) {
            $this->erp->view_rights($inv->created_by);
            $to = $this->input->post('to');
            $subject = $this->input->post('subject');
            if ($this->input->post('cc')) {
                $cc = $this->input->post('cc');
            } else {
                $cc = NULL;
            }
            if ($this->input->post('bcc')) {
                $bcc = $this->input->post('bcc');
            } else {
                $bcc = NULL;
            }
            $customer = $this->site->getCompanyByID($inv->customer_id);
            $this->load->library('parser');
            $parse_data = array(
                'reference_number' => $inv->reference_no,
                'contact_person' => $customer->name,
                'company' => $customer->company,
                'site_link' => base_url(),
                'site_name' => $this->Settings->site_name,
                'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
            );
            $msg = $this->input->post('note');
            $message = $this->parser->parse_string($msg, $parse_data);

            $biller = $this->site->getCompanyByID($inv->biller_id);
            $paypal = $this->sales_model->getPaypalSettings();
            $skrill = $this->sales_model->getSkrillSettings();
            $btn_code = '<div id="payment_buttons" class="text-center margin010">';
            if ($paypal->active == "1" && $inv->grand_total != "0.00") {
                if (trim(strtolower($customer->country)) == $biller->country) {
                    $paypal_fee = $paypal->fixed_charges + ($inv->grand_total * $paypal->extra_charges_my / 100);
                } else {
                    $paypal_fee = $paypal->fixed_charges + ($inv->grand_total * $paypal->extra_charges_other / 100);
                }
                $btn_code .= '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=' . $paypal->account_email . '&item_name=' . $inv->reference_no . '&item_number=' . $inv->id . '&image_url=' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '&amount=' . (($inv->grand_total - $inv->paid) + $paypal_fee) . '&no_shipping=1&no_note=1&currency_code=' . $this->default_currency->code . '&bn=FC-BuyNow&rm=2&return=' . site_url('sales/view/' . $inv->id) . '&cancel_return=' . site_url('sales/view/' . $inv->id) . '&notify_url=' . site_url('payments/paypalipn') . '&custom=' . $inv->reference_no . '__' . ($inv->grand_total - $inv->paid) . '__' . $paypal_fee . '"><img src="' . base_url('assets/images/btn-paypal.png') . '" alt="Pay by PayPal"></a> ';

            }
            if ($skrill->active == "1" && $inv->grand_total != "0.00") {
                if (trim(strtolower($customer->country)) == $biller->country) {
                    $skrill_fee = $skrill->fixed_charges + ($inv->grand_total * $skrill->extra_charges_my / 100);
                } else {
                    $skrill_fee = $skrill->fixed_charges + ($inv->grand_total * $skrill->extra_charges_other / 100);
                }
                $btn_code .= ' <a href="https://www.moneybookers.com/app/payment.pl?method=get&pay_to_email=' . $skrill->account_email . '&language=EN&merchant_fields=item_name,item_number&item_name=' . $inv->reference_no . '&item_number=' . $inv->id . '&logo_url=' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '&amount=' . (($inv->grand_total - $inv->paid) + $skrill_fee) . '&return_url=' . site_url('sales/view/' . $inv->id) . '&cancel_url=' . site_url('sales/view/' . $inv->id) . '&detail1_description=' . $inv->reference_no . '&detail1_text=Payment for the sale invoice ' . $inv->reference_no . ': ' . $inv->grand_total . '(+ fee: ' . $skrill_fee . ') = ' . $this->erp->formatMoney($inv->grand_total + $skrill_fee) . '&currency=' . $this->default_currency->code . '&status_url=' . site_url('payments/skrillipn') . '"><img src="' . base_url('assets/images/btn-skrill.png') . '" alt="Pay by Skrill"></a>';
            }

            $btn_code .= '<div class="clearfix"></div>
    </div>';
            $message = $message . $btn_code;

            $attachment = $this->pdf($id, NULL, 'S');
        } elseif ($this->input->post('send_email')) {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->session->set_flashdata('error', $this->data['error']);
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->erp->send_email($to, $subject, $message, NULL, NULL, $attachment, $cc, $bcc)) {
            delete_files($attachment);
            $this->session->set_flashdata('message', lang("email_sent"));
            redirect("sales");
        } else {

            if (file_exists('./themes/' . $this->theme . '/views/email_templates/sale.html')) {
                $sale_temp = file_get_contents('themes/' . $this->theme . '/views/email_templates/sale.html');
            } else {
                $sale_temp = file_get_contents('./themes/default/views/email_templates/sale.html');
            }

            $this->data['subject'] = array('name' => 'subject',
                'id' => 'subject',
                'type' => 'text',
                'value' => $this->form_validation->set_value('subject', lang('invoice').' (' . $inv->reference_no . ') '.lang('from').' ' . $this->Settings->site_name),
            );
            $this->data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'text',
                'value' => $this->form_validation->set_value('note', $sale_temp),
            );
            $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);

            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'sales/email', $this->data);
        }
    }

    /* ------------------------------------------------------------------ */

    function add($quote_id = NULL)
    {
        $this->erp->checkPermissions('add', true, 'sales');

        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        //$this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('customer', lang("customer"), 'required');
        $this->form_validation->set_rules('biller', lang("biller"), 'required');
        $this->form_validation->set_rules('sale_status', lang("sale_status"), 'required');
        $this->form_validation->set_rules('payment_status', lang("payment_status"), 'required');

		if($this->input->post('payment_status') == 'paid'){
			$this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
		}

        if ($this->form_validation->run() == true) {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";

			$dealer_code = $this->input->post('biller');

            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('so', $dealer_code);

            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld($this->input->post('date'));
            } else {
                $date = date('Y-m-d H:i:s');
            }


            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer_1');
			$amout_paid = $this->input->post('amount-paid');
            $biller_id = $this->input->post('biller');
			$saleman_by = $this->input->post('saleman');
            $total_items = $this->input->post('total_items');
            $sale_status = $this->input->post('sale_status');
            $payment_status = $this->input->post('payment_status');
            $payment_term = $this->input->post('payment_term');
            $delivery_by    = $this->input->post('delivery_by');
            $due_date = $payment_term ? date('Y-m-d', strtotime('+' . $payment_term . ' days')) : NULL;
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
            $customer = $customer_details->company ? $customer_details->company : $customer_details->name;
            $biller_details = $this->site->getCompanyByID($biller_id);
            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $note = $this->erp->clear_tags($this->input->post('note'));
            $staff_note = $this->erp->clear_tags($this->input->post('staff_note'));
            $quote_id = $this->input->post('quote_id') ? $this->input->post('quote_id') : NULL;

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
			$g_total_txt1 = 0;
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_type = $_POST['product_type'][$r];
                $item_code = $_POST['product_code'][$r];
				$item_note = $_POST['product_note'][$r];
                $item_name = $_POST['product_name'][$r];
                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : NULL;
                //$option_details = $this->sales_model->getProductOptionByID($item_option);
                $real_unit_price = $this->erp->formatDecimal($_POST['real_unit_price'][$r]);
                $unit_price = $this->erp->formatDecimal($_POST['unit_price'][$r]);
				$net_price = $this->erp->formatDecimal($_POST['net_price'][$r]);
                $item_quantity = $_POST['quantity'][$r];
                $item_serial = isset($_POST['serial'][$r]) ? $_POST['serial'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : NULL;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : NULL;

                //$g_total_txt = $_POST['grand_total'][$r];


                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->sales_model->getProductByCode($item_code) : NULL;
                    $unit_price = $real_unit_price;
                    $pr_discount = 0;

					if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = ($unit_price * (Float)($pds[0])) / 100;
                        } else {
                            $pr_discount = $this->erp->formatDecimal($discount);
                        }
                    }

                    //$unit_price = $this->erp->formatDecimal($unit_price - $this->erp->floorFigure($pr_discount));
					$unit_price =$unit_price - $pr_discount;
                    $item_net_price = $unit_price;
                    $pr_item_discount = $this->erp->floorFigure($pr_discount * $item_quantity);
                    $product_discount += $this->erp->floorFigure($pr_item_discount);
                    $pr_tax = 0; $pr_item_tax = 0; $item_tax = 0; $tax = "";

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
								$item_tax = $item_tax * $item_quantity;
                            } else {
                                //$item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
								$item_tax = $this->erp->formatDecimal(($unit_price * $item_quantity ) * ($tax_details->rate / 100));
                                $tax = $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }
                        } elseif ($tax_details->type == 2) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }

                            $item_tax = $this->erp->formatDecimal($tax_details->rate);
                            $tax = $tax_details->rate;
							$item_tax = $item_tax * $item_quantity;
                        }

                        $pr_item_tax = $this->erp->formatDecimal($item_tax);
                    }

                    $product_tax += $pr_item_tax;
                    //$subtotal = (($item_net_price * $item_quantity) + $pr_item_tax);
                    //$subtotal = ($g_total_txt + $pr_item_tax);
					$subtotal = (($unit_price * $item_quantity) + $pr_item_tax);

                    $products[] = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_price' => $this->erp->formatDecimal($net_price),
                        'unit_price' => $this->erp->formatDecimal($item_net_price + $this->erp->floorFigure($pr_discount) + $item_tax),
                        'quantity' => $item_quantity,
                        'warehouse_id' => $warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
                        'tax' => $tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $this->erp->formatDecimal($subtotal),
                        'serial_no' => $item_serial,
                        'real_unit_price' => $real_unit_price,
						'product_noted' => $item_note
                    );
					//$this->erp->print_arrays($products);
                    //$total += $item_net_price * $item_quantity;
					$total += $unit_price * $item_quantity + $pr_item_tax;
					$g_total_txt1 += $subtotal;
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

            if ($this->input->post('order_discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->erp->formatDecimal((($total + $product_tax) * (Float)($ods[0])) / 100);
                } else {
                    $order_discount = $this->erp->formatDecimal($order_discount_id);
                }
            } else {
                $order_discount_id = NULL;
            }
            $total_discount = $this->erp->formatDecimal($order_discount + $product_discount);
            //echo $this->erp->floorFigure($product_discount);die();
            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->erp->formatDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->erp->formatDecimal((($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100);
                    }
                }
            } else {
                $order_tax_id = NULL;
            }
            $total_tax = $this->erp->formatDecimal($product_tax + $order_tax);
            //$grand_total = $this->erp->formatDecimal($this->erp->formatDecimal($total) + $total_tax + $this->erp->formatDecimal($shipping) - $order_discount);
            $grand_total = $this->erp->formatDecimal($this->erp->formatDecimal($total) + $this->erp->formatDecimal($shipping) - $order_discount);

			$data = array('date' => $date,
                'reference_no' => $reference,
                'customer_id' => $customer_id,
                'customer' => $customer,
                'biller_id' => $biller_id,
                'biller' => $biller,
                'warehouse_id' => $warehouse_id,
                'note' => $note,
                'staff_note' => $staff_note,
                'total' => $this->erp->formatDecimal($total),
                'product_discount' => $this->erp->formatDecimal($product_discount),
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $this->erp->formatDecimal($product_tax),
                'order_tax_id' => $order_tax_id,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'shipping' => $this->erp->formatDecimal($shipping),
                'grand_total' => $grand_total,
                'total_items' => $total_items,
                'sale_status' => $sale_status,
                'payment_status' => $payment_status,
                'payment_term' => $payment_term,
                'due_date' => $due_date,
                'paid' => ($amout_paid != '' || $amout_paid != 0 || $amout_paid != null)? $amout_paid : 0,
                'created_by' => $this->session->userdata('user_id'),
				'saleman_by' => $saleman_by,
				'deposit_customer_id' => $this->input->post('customer')
            );

            if ($payment_status == 'partial' || $payment_status == 'paid') {
                if ($this->input->post('paid_by') == 'gift_card') {
                    $gc = $this->site->getGiftCardByNO($this->input->post('gift_card_no'));
                    $amount_paying = $grand_total >= $gc->balance ? $gc->balance : $grand_total;
                    $gc_balance = $gc->balance - $amount_paying;

					$payment = array(
						'date' => $date,
						'reference_no' => $this->input->post('payment_reference_no'),
						'amount' => $this->erp->formatDecimal($amount_paying),
						'paid_by' => $this->input->post('paid_by'),
						'cheque_no' => $this->input->post('cheque_no'),
						'cc_no' => $this->input->post('gift_card_no'),
						'cc_holder' => $this->input->post('pcc_holder'),
						'cc_month' => $this->input->post('pcc_month'),
						'cc_year' => $this->input->post('pcc_year'),
						'cc_type' => $this->input->post('pcc_type'),
						'created_by' => $this->session->userdata('user_id'),
						'note' => $this->input->post('payment_note'),
						'type' => 'received',
						'gc_balance' => $gc_balance,
						'biller_id' => $biller_id
					);
                } else {
					$payment = array(
						'date' => $date,
						'reference_no' => $this->input->post('payment_reference_no'),
						'amount' => $this->erp->formatDecimal($this->input->post('amount-paid')),
						'paid_by' => $this->input->post('paid_by'),
						'cheque_no' => $this->input->post('cheque_no'),
						'cc_no' => $this->input->post('pcc_no'),
						'cc_holder' => $this->input->post('pcc_holder'),
						'cc_month' => $this->input->post('pcc_month'),
						'cc_year' => $this->input->post('pcc_year'),
						'cc_type' => $this->input->post('pcc_type'),
						'created_by' => $this->session->userdata('user_id'),
						'note' => $this->input->post('payment_note'),
						'type' => 'received',
						'biller_id' => $biller_id
					);
                }
				if($_POST['paid_by'] == 'depreciation'){
					$no = sizeof($_POST['no']);
					$period = 1;
					for($m = 0; $m < $no; $m++){
						$dateline = date('Y-m-d', strtotime($_POST['dateline'][$m]));
						$loans[] = array(
							'period' => $period,
							'sale_id' => '',
							'interest' => $_POST['interest'][$m],
							'principle' => $_POST['principle'][$m],
							'payment' => $_POST['payment_amt'][$m],
							'balance' => $_POST['balance'][$m],
							'type' => $_POST['depreciation_type'],
							'rated' => $_POST['depreciation_rate1'],
							'note' => $_POST['note_1'][$m],
							'dateline' => $dateline,
							'biller_id' => $biller_id
						);
						$period++;
					}
					//$this->erp->print_arrays($loans);
				}else{
					$loans = array();
				}

            } else {
                $payment = array();
            }
			//$this->erp->print_arrays($loans);
            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }
			// $this->erp->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true) {
			$sale_id = $this->sales_model->addSale($data, $products, $payment, $loans);
            $this->session->set_userdata('remove_slls', 1);
            if ($quote_id) {
                $this->db->update('quotes', array('status' => 'completed'), array('id' => $quote_id));
            }
            $this->session->set_flashdata('message', lang("sale_added"));
            $this->db->select_max('id');
            $s = $this->db->get_where('erp_sales', array('created_by' => $this->session->userdata('user_id')), 1);
            //$this->print_($s->row()->id);

			$sale = $this->sales_model->getInvoiceByID($sale_id);
			$address = $customer_details->address . " " . $customer_details->city . " " . $customer_details->state . " " . $customer_details->postal_code . " " . $customer_details->country . "<br>Tel: " . $customer_details->phone . " Email: " . $customer_details->email;
			$dlDetails = array(
				'date' => $date,
				'sale_id' => $sale_id,
				'do_reference_no' => $this->site->getReference('do'),
				'sale_reference_no' => $sale->reference_no,
				'customer' => $customer_details->name,
				'address' => $address,
				//'note' => ' ',
				'created_by' => $this->session->userdata('user_id'),
				'delivery_status' => 'pending',
                'delivery_by' => $delivery_by
			);
			//$this->erp->print_arrays($dlDetails);
			$pos = $this->sales_model->getSetting();
			if($pos->auto_delivery == 1){
				$this->sales_model->addDelivery($dlDetails);
			}


			$invoice_view=$this->Settings->invoice_view;

			if($invoice_view==0){
				redirect("sales/print_/".$s->row()->id);
			}
			else if($invoice_view==1){
				redirect("sales/invoice/".$s->row()->id);
			}
			else if($invoice_view==2){
				redirect("sales/tax_invoice/".$s->row()->id);
			}
			else if($invoice_view==3){
				redirect("sales/print_/".$s->row()->id);
			}
			else if($invoice_view==4){
				redirect("sales/invoice_landscap_a5/".$s->row()->id);
			}

            redirect("sales/print_/".$s->row()->id);
        } else {

            if ($quote_id) {
                $this->data['quote'] = $this->sales_model->getQuoteByID($quote_id);
                $items = $this->sales_model->getAllQuoteItems($quote_id);
                $c = rand(100000, 9999999);
                foreach ($items as $item) {
                    $row = $this->site->getProductByID($item->product_id);
                    if (!$row) {
                        $row = json_decode('{}');
                        $row->tax_method = 0;
                    } else {
                        unset($row->details, $row->product_details, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                    }
                    $row->quantity = 0;
                    $pis = $this->sales_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                    if($pis){
                        foreach ($pis as $pi) {
                            $row->quantity += $pi->quantity_balance;
                        }
                    }
                    $row->id = $item->product_id;
                    $row->code = $item->product_code;
                    //$row->name = $item->product_name;
                    $row->type = $item->product_type;
                    $row->qty = $item->quantity;
                    $row->discount = $item->discount ? $item->discount : '0';
                    $row->price = $this->erp->formatDecimal($item->net_unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity));
                    $row->unit_price = $row->tax_method ? $item->unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity)+$this->erp->formatDecimal($item->item_tax/$item->quantity) : $item->unit_price+($item->item_discount/$item->quantity);
                    $row->real_unit_price = $item->real_unit_price;
                    $row->tax_rate = $item->tax_rate_id;
                    $row->serial = '';
                    $row->option = $item->option_id;

                    $options = $this->sales_model->getProductOptions($row->id, $item->warehouse_id);
                    if ($options) {
                        $option_quantity = 0;
                        foreach ($options as $option) {
                            $pis = $this->sales_model->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
                            if($pis){
                                foreach ($pis as $pi) {
                                    $option_quantity += $pi->quantity_balance;
                                }
                            }
                            if($option->quantity > $option_quantity) {
                                $option->quantity = $option_quantity;
                            }
                        }
                    }
                    $combo_items = FALSE;
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $item->warehouse_id);
                    }
                    $ri = $this->Settings->item_addition ? $row->id : $c;
                    if ($row->tax_rate) {
                        $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options, 'makeup_cost' => 0);
                    } else {
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options, 'makeup_cost' => 0);
                    }
                    $c++;
                }
                $this->data['quote_items'] = json_encode($pr);
            }

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['quote_id'] = $quote_id;
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
			$this->data['agencies'] = $this->site->getAllUsers();
			$this->data['customers'] = $this->site->getCustomers();
			$this->data['currency'] = $this->site->getCurrency();
            //$this->data['currencies'] = $this->sales_model->getAllCurrencies();
            $this->data['slnumber'] = ''; //$this->site->getReference('so');
            $this->data['payment_ref'] = $this->site->getReference('sp');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('add_sale')));
            $meta = array('page_title' => lang('add_sale'), 'bc' => $bc);
            $this->page_construct('sales/add', $meta, $this->data);
        }
    }

    function print_($id = NULL, $view = NULL, $save_bufffer = NULL)
    {
        $this->erp->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getInvoiceByID($id);
        $this->erp->view_rights($inv->created_by);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
        $this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
        $this->data['title'] = "2";
		$this->data['sid'] = $id;
        $this->load->view($this->theme.'sales/print',$this->data);
    }

	function print_jewwel($id = NULL, $view = NULL, $save_bufffer = NULL)
    {
        $this->erp->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getInvoiceByID($id);
        $this->erp->view_rights($inv->created_by);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
        $this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
        $this->data['title'] = "2";
		$this->data['sid'] = $id;
        $this->load->view($this->theme.'sales/print_jewwel',$this->data);
    }

	function print_hch($id = NULL, $view = NULL, $save_bufffer = NULL)
    {
        $this->erp->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getInvoiceByID($id);
        $this->erp->view_rights($inv->created_by);
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $inv->reference_no) . "' alt='" . $inv->reference_no . "' class='pull-left' />";
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['user'] = $this->site->getUser($inv->created_by);
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($id);
        $this->data['return_items'] = $return ? $this->sales_model->getAllReturnItems($return->id) : NULL;
		$this->data['sid'] = $id;
        $this->load->view($this->theme.'sales/print_hch',$this->data);
    }

	/* ------------------------------ Sochin -------------------------------------------------------------------------------------------- */
	function cabon_print($sale_id = NULL, $modal = NULL)
    {
        $this->erp->checkPermissions('index');
        if ($this->input->get('id')) {
            $sale_id = $this->input->get('id');
        }
        $this->load->helper('text');
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['rows'] = $this->pos_model->getAllInvoiceItems($sale_id);
        $inv = $this->sales_model->getInvoiceByID($sale_id);
        $biller_id = $inv->biller_id;
        $customer_id = $inv->customer_id;
        $this->data['biller'] = $this->pos_model->getCompanyByID($biller_id);
        $this->data['customer'] = $this->pos_model->getCompanyByID($customer_id);
        $this->data['payments'] = $this->sales_model->getPaymentsForSale($sale_id);
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['barcode'] = $this->barcode($inv->reference_no, 'code39', 30);
        $this->data['inv'] = $inv;
        $this->data['sid'] = $sale_id;
		$this->data['exchange_rate'] = $this->pos_model->getExchange_rate();
		$this->data['exchange_rate_th'] = $this->pos_model->getExchange_rate('THA');
		$this->data['exchange_rate_kh_c'] = $this->pos_model->getExchange_rate('KHM');
        $this->data['modal'] = $modal;
        $this->data['page_title'] = $this->lang->line("invoice");
        $this->load->view($this->theme . 'sales/cabon_print', $this->data);
    }

    function barcode($text = NULL, $bcs = 'code39', $height = 50)
    {
        return site_url('products/gen_barcode/' . $text . '/' . $bcs . '/' . $height);
    }

	/* ------------------------------End----------------------------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------------------------------------------------------------- */

    function edit($id = NULL)
    {
        $this->erp->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('customer', lang("customer"), 'required');
        $this->form_validation->set_rules('biller', lang("biller"), 'required');
        $this->form_validation->set_rules('sale_status', lang("sale_status"), 'required');
        $this->form_validation->set_rules('payment_status', lang("payment_status"), 'required');
        //$this->form_validation->set_rules('note', lang("note"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
            $reference = $this->input->post('reference_no');
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
            $biller_id = $this->input->post('biller');
			$saleman_by = $this->input->post('saleman');
            $total_items = $this->input->post('total_items');
            $sale_status = $this->input->post('sale_status');
            $payment_status = $this->input->post('payment_status');
            $payment_term = $this->input->post('payment_term');
			$delivery_by = $this->input->post('delivery_by');
			$delivery_id = $this->input->post('delivery_id');
            $due_date = $payment_term ? date('Y-m-d', strtotime('+' . $payment_term . ' days')) : NULL;
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
            $customer = $customer_details->company ? $customer_details->company : $customer_details->name;
            $biller_details = $this->site->getCompanyByID($biller_id);
            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $note = $this->erp->clear_tags($this->input->post('note'));
            $staff_note = $this->erp->clear_tags($this->input->post('staff_note'));

			$amout_paid = $this->input->post('amount-paid');

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_type = $_POST['product_type'][$r];
                $item_code = $_POST['product_code'][$r];
                $item_name = $_POST['product_name'][$r];
                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : NULL;
                //$option_details = $this->sales_model->getProductOptionByID($item_option);
                $real_unit_price = $this->erp->formatDecimal($_POST['real_unit_price'][$r]);
                $unit_price = $this->erp->formatDecimal($_POST['unit_price'][$r]);
				$net_price = $this->erp->formatDecimal($_POST['net_price'][$r]);
                $item_quantity = $_POST['quantity'][$r];
                $item_serial = isset($_POST['serial'][$r]) ? $_POST['serial'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : NULL;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : NULL;

                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->sales_model->getProductByCode($item_code) : NULL;
                    $unit_price = $real_unit_price;
                    $pr_discount = 0;

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = ($unit_price * (Float)($pds[0])) / 100;
                        } else {
                            $pr_discount = $this->erp->formatDecimal($discount);
                        }
                    }

                    //$unit_price = $this->erp->formatDecimal($unit_price - $this->erp->floorFigure($pr_discount));
					$unit_price = $unit_price - $pr_discount;
                    $item_net_price = $unit_price;
                    $pr_item_discount = $pr_discount * $item_quantity;
                    $product_discount += $pr_item_discount;
                    $pr_tax = 0; $pr_item_tax = 0; $item_tax = 0; $tax = "";

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
								$item_tax = $item_tax * $item_quantity;
                            } else {
                              //  $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
								$item_tax = $this->erp->formatDecimal(($unit_price * $item_quantity ) * ($tax_details->rate / 100));
                                $tax = $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }

                        } elseif ($tax_details->type == 2) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }

                            $item_tax = $this->erp->formatDecimal($tax_details->rate);
                            $tax = $tax_details->rate;
							$item_tax = $item_tax * $item_quantity;
                        }
                        $pr_item_tax = $this->erp->formatDecimal($item_tax);
                    }
                    $product_tax += $pr_item_tax;
                    $subtotal = (($unit_price * $item_quantity) + $pr_item_tax);

                    $products[] = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_price' => $this->erp->formatDecimal($net_price),
                        'unit_price' => $this->erp->formatDecimal($item_net_price + $this->erp->floorFigure($pr_discount) + $item_tax),
                        'quantity' => $item_quantity,
                        'warehouse_id' => $warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
                        'tax' => $tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $this->erp->formatDecimal($subtotal),
                        'serial_no' => $item_serial,
                        'real_unit_price' => $real_unit_price
                    );
                    $total += $unit_price * $item_quantity + $pr_item_tax;
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }
            if ($this->input->post('order_discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->erp->formatDecimal((($total + $product_tax) * (Float)($ods[0])) / 100);
                } else {
                    $order_discount = $this->erp->formatDecimal($order_discount_id);
                }
            } else {
                $order_discount_id = NULL;
            }
            $total_discount = $this->erp->formatDecimal($order_discount + $product_discount);

            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->erp->formatDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->erp->formatDecimal((($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100);
                    }
                }
            } else {
                $order_tax_id = NULL;
            }

            $total_tax = $this->erp->formatDecimal($product_tax + $order_tax);
            $grand_total = $this->erp->formatDecimal($this->erp->formatDecimal($total) + $this->erp->formatDecimal($shipping) - $order_discount);
            $data = array('date' => $date,
                'reference_no' => $reference,
                'customer_id' => $customer_id,
                'customer' => $customer,
                'biller_id' => $biller_id,
                'biller' => $biller,
                'warehouse_id' => $warehouse_id,
                'note' => $note,
                'staff_note' => $staff_note,
                'total' => $this->erp->formatDecimal($total),
                'product_discount' => $this->erp->formatDecimal($product_discount),
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $this->erp->formatDecimal($product_tax),
                'order_tax_id' => $order_tax_id,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'shipping' => $this->erp->formatDecimal($shipping),
                'grand_total' => $grand_total,
                'total_items' => $total_items,
                'sale_status' => $sale_status,
                'payment_status' => $payment_status,
                'payment_term' => $payment_term,
				'paid' => ($amout_paid != '' || $amout_paid != 0 || $amout_paid != null)? $amout_paid : 0,
                'due_date' => $due_date,
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date('Y-m-d H:i:s'),
				'saleman_by' => $saleman_by,
				'deposit_customer_id' => $this->input->post('customer')
            );

			//$this->erp->print_arrays($data);
            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }

			$sale = $this->sales_model->getInvoiceByID($sale_id);
			$address = $customer_details->address . " " . $customer_details->city . " " . $customer_details->state . " " . $customer_details->postal_code . " " . $customer_details->country . "<br>Tel: " . $customer_details->phone . " Email: " . $customer_details->email;
			$dlDetails = array(
				'date' => $date,
				'sale_id' => $id,
				'sale_reference_no' => $reference,
				'customer' => $customer_details->name,
				'address' => $address,
				//'note' => ' ',
				'created_by' => $this->session->userdata('user_id'),
				'delivery_status' => 'pending',
                'delivery_by' => $delivery_by
			);
			//$this->erp->print_arrays($dlDetails);
			$pos = $this->sales_model->getSetting();
			if($pos->auto_delivery == 1){
				$this->sales_model->updateDelivery($delivery_id, $dlDetails);
			}

			/*
			if ($payment_status == 'partial' || $payment_status == 'paid') {
                if ($this->input->post('paid_by') == 'gift_card') {
                    $gc = $this->site->getGiftCardByNO($this->input->post('gift_card_no'));
                    $amount_paying = $grand_total >= $gc->balance ? $gc->balance : $grand_total;
                    $gc_balance = $gc->balance - $amount_paying;

					$payment = array(
						'date' => $date,
						'reference_no' => $this->input->post('payment_reference_no'),
						'amount' => $this->erp->formatDecimal($amount_paying),
						'paid_by' => $this->input->post('paid_by'),
						'cheque_no' => $this->input->post('cheque_no'),
						'cc_no' => $this->input->post('gift_card_no'),
						'cc_holder' => $this->input->post('pcc_holder'),
						'cc_month' => $this->input->post('pcc_month'),
						'cc_year' => $this->input->post('pcc_year'),
						'cc_type' => $this->input->post('pcc_type'),
						'created_by' => $this->session->userdata('user_id'),
						'note' => $this->input->post('payment_note'),
						'type' => 'received',
						'gc_balance' => $gc_balance
					);
                } else {
					$payment = array(
						'date' => $date,
						'reference_no' => $this->input->post('payment_reference_no'),
						'amount' => $this->erp->formatDecimal($this->input->post('amount-paid')),
						'paid_by' => $this->input->post('paid_by'),
						'cheque_no' => $this->input->post('cheque_no'),
						'cc_no' => $this->input->post('pcc_no'),
						'cc_holder' => $this->input->post('pcc_holder'),
						'cc_month' => $this->input->post('pcc_month'),
						'cc_year' => $this->input->post('pcc_year'),
						'cc_type' => $this->input->post('pcc_type'),
						'created_by' => $this->session->userdata('user_id'),
						'note' => $this->input->post('payment_note'),
						'type' => 'received'
					);
                }
				if($_POST['paid_by'] == 'depreciation'){
					$no = sizeof($_POST['no']);
					$period = 1;
					for($m = 0; $m < $no; $m++){
						$dateline = date('Y-m-d', strtotime($_POST['dateline'][$m]));
						$loans[] = array(
							'period' => $period,
							'sale_id' => '',
							'interest' => $_POST['interest'][$m],
							'principle' => $_POST['principle'][$m],
							'payment' => $_POST['payment_amt'][$m],
							'balance' => $_POST['balance'][$m],
							'type' => $_POST['depreciation_type'],
							'rated' => $_POST['depreciation_rate1'],
							'note' => $_POST['note_1'][$m],
							'dateline' => $dateline
						);
						$period++;
					}
					//$this->erp->print_arrays($loans);
				}else{
					$loans = array();
				}

            } else {
                $payment = array();
            }
			*/

            // $this->erp->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true && $this->sales_model->updateSale($id, $data, $products)) {
            $this->session->set_userdata('remove_slls', 1);
            $this->session->set_flashdata('message', lang("sale_updated"));
            redirect("sales");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$sale = $this->sales_model->getInvoiceByID($id);
            $this->data['inv'] = $sale;

            if ($this->data['inv']->date <= date('Y-m-d', strtotime('-3 months'))) {
                $this->session->set_flashdata('error', lang("sale_x_edited_older_than_3_months"));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            $inv_items = $this->sales_model->getAllInvoiceItems($id);
            $c = rand(100000, 9999999);
            foreach ($inv_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
                if (!$row) {
                    $row = json_decode('{}');
                    $row->tax_method = 0;
                    $row->quantity = 0;
                } else {
                    unset($row->details, $row->product_details, $row->cost, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                }
                $pis = $this->sales_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                $row->id = $item->product_id;
                $row->code = $item->product_code;
                $row->name = $item->product_name;
                $row->type = $item->product_type;
                $row->qty = $item->quantity;
                $row->quantity += $item->quantity;
				$row->cost += $item->cost;
                $row->discount = $item->discount ? $item->discount : '0';
                $row->price = $this->erp->formatDecimal($item->net_unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity));
                $row->unit_price = $row->tax_method ? $item->unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity)+$this->erp->formatDecimal($item->item_tax/$item->quantity) : $item->unit_price+($item->item_discount/$item->quantity);
                $row->real_unit_price = $item->real_unit_price;
                $row->tax_rate = $item->tax_rate_id;
                $row->serial = $item->serial_no;
                $row->option = $item->option_id;
				$row->unit = $row->unit;
                $options = $this->sales_model->getProductOptions($row->id, $item->warehouse_id);

                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->sales_model->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
                        if($pis){
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        $option_quantity += $item->quantity;
                        if($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }

                $combo_items = FALSE;
                if ($row->type == 'combo') {
                    $combo_items = $this->sales_model->getProductComboItems($row->id, $item->warehouse_id);
                    $te = $combo_items;
                    foreach ($combo_items as $combo_item) {
                        $combo_item->quantity =  $combo_item->qty*$item->quantity;
                    }
                }
                $ri = $this->Settings->item_addition ? $row->id : $c;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options, 'makeup_cost' => 0);
                } else {
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options, 'makeup_cost' => 0);
                }
                $c++;
            }

            $this->data['inv_items'] = json_encode($pr);
            $this->data['id'] = $id;
            //$this->data['currencies'] = $this->site->getAllCurrencies();
            $this->data['billers'] = ($this->Owner || $this->Admin) ? $this->site->getAllCompanies('biller') : NULL;
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
			$this->data['agencies'] = $this->site->getAllUsers();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
			$this->data['payment'] = $this->site->getPaymentBySaleID($id);
			$this->data['delivery'] = $this->sales_model->getDeliveryBySaleID($sale->id);

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('edit_sale')));
            $meta = array('page_title' => lang('edit_sale'), 'bc' => $bc);
            $this->page_construct('sales/edit', $meta, $this->data);
        }
    }

    /* ------------------------------- */

    function return_sale($id = NULL)
    {
        $this->erp->checkPermissions('return_sales');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        // $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paying_by"), 'required');

        if ($this->form_validation->run() == true) {
            $sale = $this->sales_model->getInvoiceByID($id);
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('re');
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }

            $return_surcharge = $this->input->post('return_surcharge') ? $this->input->post('return_surcharge') : 0;
            $note = $this->erp->clear_tags($this->input->post('note'));

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_type = $_POST['product_type'][$r];
                $item_code = $_POST['product_code'][$r];
                $item_name = $_POST['product_name'][$r];
                $sale_item_id = $_POST['sale_item_id'][$r];
                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : NULL;
                //$option_details = $this->sales_model->getProductOptionByID($item_option);
                $real_unit_price = $this->erp->formatDecimal($_POST['real_unit_price'][$r]);
                $unit_price = $this->erp->formatDecimal($_POST['unit_price'][$r]);
                $item_quantity = $_POST['quantity'][$r];
                $item_serial = isset($_POST['serial'][$r]) ? $_POST['serial'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : NULL;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : NULL;

                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->sales_model->getProductByCode($item_code) : NULL;

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = (($this->erp->formatDecimal($unit_price)) * (Float)($pds[0])) / 100;
                        } else {
                            $pr_discount = $this->erp->formatDecimal($discount);
                        }
                    } else {
                        $pr_discount = 0;
                    }
                    $unit_price = $this->erp->formatDecimal($unit_price - $pr_discount);
                    $pr_item_discount = $this->erp->formatDecimal($pr_discount * $item_quantity);
                    $product_discount += $pr_item_discount;

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if (!$product_details->tax_method) {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            }

                        } elseif ($tax_details->type == 2) {

                            $item_tax = $this->erp->formatDecimal($tax_details->rate);
                            $tax = $tax_details->rate;

                        }
                        $pr_item_tax = $this->erp->formatDecimal($item_tax * $item_quantity);

                    } else {
                        $pr_tax = 0;
                        $pr_item_tax = 0;
                        $tax = "";
                    }

                    $item_net_price = $product_details->tax_method ? $this->erp->formatDecimal($unit_price-$pr_discount) : $this->erp->formatDecimal($unit_price-$item_tax-$pr_discount);
                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_price * $item_quantity) + $pr_item_tax);

                    $products[] = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_price' => $item_net_price,
                        // 'unit_price' => $this->erp->formatDecimal($item_net_price + $item_tax),
                        'quantity' => $item_quantity,
                        'warehouse_id' => $sale->warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
                        'tax' => $tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $this->erp->formatDecimal($subtotal)?$this->erp->formatDecimal($subtotal):0,
                        'serial_no' => $item_serial,
                        'real_unit_price' => $real_unit_price,
                        'sale_item_id' => $sale_item_id
                    );

                    $total += $item_net_price * $item_quantity;
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

			$paid_amount = $this->input->post('amount-paid');

            if ($this->input->post('discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->erp->formatDecimal((($paid_amount + $product_tax) * (Float)($ods[0])) / 100);
                } else {
                    $order_discount = $this->erp->formatDecimal($order_discount_id);
                }
            } else {
                $order_discount_id = NULL;
            }
            $total_discount = $order_discount + $product_discount;

            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->erp->formatDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->erp->formatDecimal((($paid_amount + $product_tax - $order_discount) * $order_tax_details->rate) / 100);
                    }
                }
            } else {
                $order_tax_id = NULL;
            }

            $total_tax = $this->erp->formatDecimal($product_tax + $order_tax);
            $grand_total = $this->erp->formatDecimal($paid_amount);
            $data = array('date' => $date,
                'sale_id' => $id,
                'reference_no' => $reference,
                'customer_id' => $sale->customer_id,
                'customer' => $sale->customer,
                'biller_id' => $sale->biller_id,
                'biller' => $sale->biller,
                'warehouse_id' => $sale->warehouse_id,
                'note' => $note,
                'total' => $this->input->post('amount-paid')?$this->input->post('amount-paid'):0,
                'product_discount' => $this->erp->formatDecimal($product_discount),
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $this->erp->formatDecimal($product_tax),
                'order_tax_id' => $order_tax_id,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'surcharge' => $this->erp->formatDecimal($return_surcharge),
                'grand_total' => $grand_total?$grand_total:0,
                'created_by' => $this->session->userdata('user_id')
            );
            if ($this->input->post('amount-paid') && $this->input->post('amount-paid') != 0) {
                $payment = array(
                    'date' => $date,
                    'reference_no' => $this->input->post('payment_reference_no'),
                    'amount' => $this->erp->formatDecimal($this->input->post('amount-paid')),
                    'paid_by' => $this->input->post('paid_by'),
                    'cheque_no' => $this->input->post('cheque_no'),
                    'cc_no' => $this->input->post('pcc_no'),
                    'cc_holder' => $this->input->post('pcc_holder'),
                    'cc_month' => $this->input->post('pcc_month'),
                    'cc_year' => $this->input->post('pcc_year'),
                    'cc_type' => $this->input->post('pcc_type'),
                    'created_by' => $this->session->userdata('user_id'),
                    'type' => 'returned',
                    'biller_id' => $sale->biller_id ? $sale->biller_id : $this->default_biller_id
                );
            } else {
                $payment = array();
            }

            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }
            //$this->erp->print_arrays($data, $products, $payment);
        }

        if ($this->form_validation->run() == true && $this->sales_model->returnSale($data, $products, $payment)) {
            $this->session->set_flashdata('message', lang("return_sale_added"));
            redirect("sales/return_sales");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['inv'] = $this->sales_model->getInvoiceByID($id);
            if ($this->data['inv']->sale_status != 'completed') {
                $this->session->set_flashdata('error', lang("sale_status_x_competed"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
            $inv_items = $this->sales_model->getAllInvoiceItems($id);
            $c = rand(100000, 9999999);
            foreach ($inv_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
                if (!$row) {
                    $row = json_decode('{}');
                    $row->tax_method = 0;
                    $row->quantity = 0;
                } else {
                    unset($row->details, $row->product_details, $row->cost, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                }
                $pis = $this->sales_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                $row->id = $item->product_id;
                $row->sale_item_id = $item->id;
                $row->code = $item->product_code;
                $row->name = $item->product_name;
                $row->type = $item->product_type;
                $row->qty = $item->quantity;
                $row->oqty = $item->quantity;
                $row->discount = $item->discount ? $item->discount : '0';
                $row->price = $this->erp->formatDecimal($item->net_unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity));
                $row->unit_price = $row->tax_method ? $item->unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity)+$this->erp->formatDecimal($item->item_tax/$item->quantity) : $item->unit_price+($item->item_discount/$item->quantity);
                $row->real_unit_price = $item->real_unit_price;
                $row->tax_rate = $item->tax_rate_id;
                $row->serial = $item->serial_no;
                $row->option = $item->option_id;
                $options = $this->sales_model->getProductOptions($row->id, $item->warehouse_id, TRUE);
                $ri = $this->Settings->item_addition ? $row->id : $c;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => $tax_rate, 'options' => $options);
                } else {
                    $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'tax_rate' => false, 'options' => $options);
                }
                $c++;
            }
            $this->data['inv_items'] = json_encode($pr);
            $this->data['id'] = $id;
            $this->data['payment_ref'] = $this->site->getReference('sp');
            $this->data['reference'] = ''; // $this->site->getReference('re');
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('return_sale')));
            $meta = array('page_title' => lang('return_sale'), 'bc' => $bc);
            $this->page_construct('sales/return_sale', $meta, $this->data);
        }
    }

    /* ------------------------------- */

	/* Add Return Sale  */
	function add_return($quote_id = NULL)
    {
        $this->erp->checkPermissions();

        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        //$this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('customer', lang("customer"), 'required');
        $this->form_validation->set_rules('biller', lang("biller"), 'required');
        //$this->form_validation->set_rules('sale_status', lang("sale_status"), 'required');
        //$this->form_validation->set_rules('payment_status', lang("payment_status"), 'required');

		if($this->input->post('payment_status') == 'paid'){
			$this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
		}

        if ($this->form_validation->run() == true) {
            $sale = $this->sales_model->getInvoiceByRef($quote_id);

			$warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
			$biller_id = $this->input->post('biller');

			$customer_details = $this->site->getCompanyByID($customer_id);
			$customer = $customer_details->company ? $customer_details->company : $customer_details->name;
			$biller_details = $this->site->getCompanyByID($biller_id);
            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;

            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";
            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('re');
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }

            $return_surcharge = $this->input->post('return_surcharge') ? $this->input->post('return_surcharge') : 0;
            $note = $this->erp->clear_tags($this->input->post('note'));

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_type = $_POST['product_type'][$r];
                $item_code = $_POST['product_code'][$r];
                $item_name = $_POST['product_name'][$r];
                $sale_ref = $_POST['sale_reference'][$r];
				if(!$sale_ref){
					$sample_sale_ref = $this->sales_model->getSampleSaleRefByProductID($item_id);
					$sale_ref = $sample_sale_ref;
				}

                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : NULL;
                //$option_details = $this->sales_model->getProductOptionByID($item_option);
                $real_unit_price = $this->erp->formatDecimal($_POST['real_unit_price'][$r]);
                $unit_price = $this->erp->formatDecimal($_POST['unit_price'][$r]);
                $item_quantity = $_POST['quantity'][$r];
                $item_serial = isset($_POST['serial'][$r]) ? $_POST['serial'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : NULL;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : NULL;

				$sale_r = $this->sales_model->getSaleItemByRefPID($sale_ref, $item_id);
				if(!$sale_r) {
					$sale_r = $this->sales_model->getSaleItemByProductID($item_id);
				}
				$sale_item_id = $sale_r->sale_item_id;
                $sale_id = $sale_r->sale_id?$sale_r->sale_id:0;

                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->sales_model->getProductByCode($item_code) : NULL;
                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== false) {
                            $pds = explode("%", $discount);
                            $pr_discount = (($this->erp->formatDecimal($unit_price)) * (Float)($pds[0])) / 100;
                        } else {
                            $pr_discount = $this->erp->formatDecimal($discount);
                        }
                    } else {
                        $pr_discount = 0;
                    }
                    $unit_price = $this->erp->formatDecimal($unit_price - $pr_discount);
                    $pr_item_discount = $this->erp->formatDecimal($pr_discount * $item_quantity);
                    $product_discount += $pr_item_discount;

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {
                            if (!$product_details->tax_method) {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->erp->formatDecimal((($unit_price) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            }
                        } elseif ($tax_details->type == 2) {
                            $item_tax = $this->erp->formatDecimal($tax_details->rate);
                            $tax = $tax_details->rate;
                        }
                        $pr_item_tax = $this->erp->formatDecimal($item_tax * $item_quantity);
                    } else {
                        $pr_tax = 0;
                        $pr_item_tax = 0;
                        $tax = "";
                    }

                    $item_net_price = $product_details->tax_method ? $this->erp->formatDecimal($unit_price-$pr_discount) : $this->erp->formatDecimal($unit_price-$item_tax-$pr_discount);
                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_price * $item_quantity) + $pr_item_tax);
                    $products[] = array(
                        'product_id' => $item_id,
                        'product_code' => $item_code,
                        'product_name' => $item_name,
                        'product_type' => $item_type,
                        'option_id' => $item_option,
                        'net_unit_price' => $item_net_price,
                        // 'unit_price' => $this->erp->formatDecimal($item_net_price + $item_tax),
                        'quantity' => $item_quantity,
                        'warehouse_id' => $warehouse_id,
                        'item_tax' => $pr_item_tax,
                        'tax_rate_id' => $pr_tax,
                        'tax' => $tax,
                        'discount' => $item_discount,
                        'item_discount' => $pr_item_discount,
                        'subtotal' => $this->erp->formatDecimal($subtotal),
                        'serial_no' => $item_serial,
                        'real_unit_price' => $real_unit_price,
                        'sale_item_id' => $sale_item_id,
                        'sale_id' => $sale_id
                    );
                    $total += $item_net_price * $item_quantity;
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

			$paid_amount = $this->input->post('amount-paid');

            if ($this->input->post('discount')) {
                $order_discount_id = $this->input->post('order_discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->erp->formatDecimal((($paid_amount + $product_tax) * (Float)($ods[0])) / 100);
                } else {
                    $order_discount = $this->erp->formatDecimal($order_discount_id);
                }
            } else {
                $order_discount_id = NULL;
            }
            $total_discount = $order_discount + $product_discount;

            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->erp->formatDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->erp->formatDecimal((($paid_amount + $product_tax - $order_discount) * $order_tax_details->rate) / 100);
                    }
                }
            } else {
                $order_tax_id = NULL;
            }

			$references = sizeof($_POST['sale_reference']);

            $total_tax = $this->erp->formatDecimal($product_tax + $order_tax);
            $grand_total = $this->erp->formatDecimal($paid_amount);
            $data = array('date' => $date,
                'sale_id' => $quote_id,
                'reference_no' => $reference,
                'customer_id' => $customer_id,
                'customer' => $customer,
                'biller_id' => $biller_id,
                'biller' => $biller,
                'warehouse_id' => $warehouse_id,
                'note' => $note,
                'total' => $this->input->post('amount-paid'),
                'product_discount' => $this->erp->formatDecimal($product_discount),
                'order_discount_id' => $order_discount_id,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $this->erp->formatDecimal($product_tax),
                'order_tax_id' => $order_tax_id,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'surcharge' => $this->erp->formatDecimal($return_surcharge),
                'grand_total' => $grand_total,
                'created_by' => $this->session->userdata('user_id')
            );
            if ($this->input->post('amount-paid') && $this->input->post('amount-paid') != 0) {
                $payment = array(
                    'date' => $date,
                    'reference_no' => $this->input->post('payment_reference_no'),
                    'amount' => $this->erp->formatDecimal($this->input->post('amount-paid')),
                    'paid_by' => $this->input->post('paid_by'),
                    'cheque_no' => $this->input->post('cheque_no'),
                    'cc_no' => $this->input->post('pcc_no'),
                    'cc_holder' => $this->input->post('pcc_holder'),
                    'cc_month' => $this->input->post('pcc_month'),
                    'cc_year' => $this->input->post('pcc_year'),
                    'cc_type' => $this->input->post('pcc_type'),
                    'created_by' => $this->session->userdata('user_id'),
                    'type' => 'returned',
                    'biller_id' => $sale->biller_id ? $sale->biller_id : $this->default_biller_id
                );
            } else {
                $payment = array();
            }

            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }
            //$this->erp->print_arrays($data, $products, $payment);
        }

        if ($this->form_validation->run() == true && $this->sales_model->returnSales($data, $products, $payment)) {
            $this->session->set_flashdata('message', lang("return_sale_added"));
            redirect("sales/return_sales");
        } else {

            if ($quote_id) {
                $this->data['quote'] = $this->sales_model->getQuoteByID($quote_id);
                $items = $this->sales_model->getAllQuoteItems($quote_id);
                $c = rand(100000, 9999999);
                foreach ($items as $item) {
                    $row = $this->site->getProductByID($item->product_id);
                    if (!$row) {
                        $row = json_decode('{}');
                        $row->tax_method = 0;
                    } else {
                        unset($row->cost, $row->details, $row->product_details, $row->supplier1price, $row->supplier2price, $row->supplier3price, $row->supplier4price, $row->supplier5price);
                    }
                    $row->quantity = 0;
                    $pis = $this->sales_model->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                    if($pis){
                        foreach ($pis as $pi) {
                            $row->quantity += $pi->quantity_balance;
                        }
                    }
                    $row->id = $item->product_id;
                    $row->code = $item->product_code;
                    //$row->name = $item->product_name;
                    $row->type = $item->product_type;
                    $row->qty = $item->quantity;
                    $row->discount = $item->discount ? $item->discount : '0';
                    $row->price = $this->erp->formatDecimal($item->net_unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity));
                    $row->unit_price = $row->tax_method ? $item->unit_price+$this->erp->formatDecimal($item->item_discount/$item->quantity)+$this->erp->formatDecimal($item->item_tax/$item->quantity) : $item->unit_price+($item->item_discount/$item->quantity);
                    $row->real_unit_price = $item->real_unit_price;
                    $row->tax_rate = $item->tax_rate_id;
                    $row->serial = '';
                    $row->option = $item->option_id;

                    $options = $this->sales_model->getProductOptions($row->id, $item->warehouse_id);

                    if ($options) {
                        $option_quantity = 0;
                        foreach ($options as $option) {
                            $pis = $this->sales_model->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
                            if($pis){
                                foreach ($pis as $pi) {
                                    $option_quantity += $pi->quantity_balance;
                                }
                            }
                            if($option->quantity > $option_quantity) {
                                $option->quantity = $option_quantity;
                            }
                        }
                    }
                    $combo_items = FALSE;
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $item->warehouse_id);
                    }
                    $ri = $this->Settings->item_addition ? $row->id : $c;
                    if ($row->tax_rate) {
                        $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'options' => $options, 'sale_ref' => '', 'quantity_received' => 0);
                    } else {
                        $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => false, 'options' => $options, 'sale_ref' => '', 'quantity_received' => 0);
                    }
                    $c++;
                }
                $this->data['quote_items'] = json_encode($pr);
            }

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['quote_id'] = $quote_id;
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['warehouses'] = $this->site->getAllWarehouses();

            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            //$this->data['currencies'] = $this->sales_model->getAllCurrencies();
            $this->data['slnumber'] = ''; //$this->site->getReference('so');
            $this->data['payment_ref'] = $this->site->getReference('sp');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('add_sale_return')));
            $meta = array('page_title' => lang('add_sale_return'), 'bc' => $bc);
            $this->page_construct('sales/add_return', $meta, $this->data);
        }
    }

	function getReferences($term = NULL, $limit = NULL)
    {
        // $this->erp->checkPermissions('index');
        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        if (strlen($term) < 1) {
            return FALSE;
        }
        $limit = $this->input->get('limit', TRUE);

        $rows['results'] = $this->sales_model->getSalesReferences($term, $limit);
        echo json_encode($rows);
    }

    function delete($id = NULL)
    {
        $this->erp->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->sales_model->deleteSale($id) && $this->sales_model->deleteDelivery($id)) {
            if($this->input->is_ajax_request()) {
                echo lang("sale_deleted"); die();
            }
            $this->session->set_flashdata('message', lang('sale_deleted'));
            redirect('welcome');
        }
    }

    function delete_return($id = NULL)
    {
        $this->erp->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->sales_model->deleteReturn($id)) {
            if($this->input->is_ajax_request()) {
                echo lang("return_sale_deleted"); die();
            }
            $this->session->set_flashdata('message', lang('return_sale_deleted'));
            redirect('welcome');
        }
    }

    function sale_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->sales_model->deleteSale($id);
                    }
					$this->session->set_flashdata('message', lang('sale_deleted'));
					redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'combine_pay') {
                    //$html = $this->combine_pdf($_POST['val']);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('sales'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('shop'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('sale_status'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('grand_total'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('paid'));
					$this->excel->getActiveSheet()->SetCellValue('H1', lang('balance'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('payment_status'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sale = $this->sales_model->getInvoiceByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($sale->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sale->reference_no);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sale->biller);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $sale->customer);
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $sale->sale_status);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $sale->grand_total);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $sale->paid);
						$this->excel->getActiveSheet()->SetCellValue('H' . $row, ($sale->grand_total - $sale->paid));
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $sale->payment_status);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'sales_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
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
                $this->session->set_flashdata('error', lang("no_sale_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

	function suspend_actions()
    {

        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {
            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->sales_model->deleteSuspend($id);
                    }
                    $this->session->set_flashdata('message', lang("sales_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('sales'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('biller'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('grand_total'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('paid'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('payment_status'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sale = $this->sales_model->getInvoiceByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($sale->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sale->reference_no);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sale->biller);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $sale->customer);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $sale->grand_total);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $sale->paid);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $sale->payment_status);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'sales_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
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
                $this->session->set_flashdata('error', lang("no_sale_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    /* ------------------------------- */

    function deliveries($start_date = NULL, $end_date = NULL)
    {
        $this->erp->checkPermissions();

		if (!$start_date) {
            //$start = $this->db->escape(date('Y-m') . '-1');
           // $start_date = date('Y-m') . '-1';
        } else {
            $start = $this->db->escape(urldecode($start_date));
        }
        if (!$end_date) {
            //$end = $this->db->escape(date('Y-m-d H:i'));
            //$end_date = date('Y-m-d H:i');
        } else {
            $end = $this->db->escape(urldecode($end_date));
        }

        $data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

		$this->data['start'] = urldecode($start_date);
        $this->data['end'] = urldecode($end_date);

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('deliveries')));
        $meta = array('page_title' => lang('deliveries'), 'bc' => $bc);
        $this->page_construct('sales/deliveries', $meta, $this->data);
    }

	function deliveries_alerts($date = NULL, $start_date = NULL, $end_date = NULL)
    {
        $this->erp->checkPermissions();

		$date = $date;

		if (!$start_date) {
            //$start = $this->db->escape(date('Y-m') . '-1');
            //$start_date = date('Y-m') . '-1';
        } else {
            $start = $this->db->escape(urldecode($start_date));
        }
        if (!$end_date) {
            //$end = $this->db->escape(date('Y-m-d H:i'));
           // $end_date = date('Y-m-d H:i');
        } else {
            $end = $this->db->escape(urldecode($end_date));
        }


		$this->data['date'] = $date;
        $data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

		$this->data['start'] = urldecode($start_date);
        $this->data['end'] = urldecode($end_date);

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('deliveries_alerts')));
        $meta = array('page_title' => lang('deliveries_alerts'), 'bc' => $bc);
        $this->page_construct('sales/deliveries_alerts', $meta, $this->data);

    }

    function getDeliveries($start = NULL, $end = NULL)
    {
        $this->erp->checkPermissions('deliveries');

		$print_cabon_link = anchor('sales/view_delivery_cabon/$1', '<i class="fa fa-file-text-o"></i> ' . lang('print_cabon'), 'data-toggle="modal" data-target="#myModal"');
        $detail_link = anchor('sales/view_delivery/$1', '<i class="fa fa-file-text-o"></i> ' . lang('delivery_details'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('sales/email_delivery/$1', '<i class="fa fa-envelope"></i> ' . lang('email_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('sales/edit_delivery/$1', '<i class="fa fa-edit"></i> ' . lang('edit_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $pdf_link = anchor('sales/pdf_delivery/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_delivery") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete_delivery/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_delivery') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
    <ul class="dropdown-menu pull-right" role="menu">
        <li>' . $print_cabon_link . '</li>
		<li>' . $detail_link . '</li>'

		.(($this->Owner || $this->Admin) ? '<li>'.$edit_link.'</li>' : ($this->GP['sales-edit_delivery'] ? '<li>'.$edit_link.'</li>' : '')).

		'<li>' . $pdf_link . '</li>
        <li>' . $delete_link . '</li>
    </ul>
</div></div>';

        $this->load->library('datatables');
        //GROUP_CONCAT(CONCAT('Name: ', sale_items.product_name, ' Qty: ', sale_items.quantity ) SEPARATOR '<br>')

		$this->datatables
            ->select("deliveries.id as id, date, do_reference_no, sale_reference_no, customer, address, COALESCE(SUM(erp_sale_items.quantity),0) as qty, delivery_status")
            ->from('deliveries')
            ->join('sale_items', 'sale_items.sale_id=deliveries.sale_id', 'left')
            ->group_by('deliveries.id');

		if($start && $end){
			$this->datatables->where('date BETWEEN "' . $start . '" AND "' . $end . '"');
		}

        $this->datatables->add_column("Actions", $action, "id");

        echo $this->datatables->generate();
    }

	function getDeliveriesAlert($date = NULL,$start = NULL, $end = NULL)
    {
        $this->erp->checkPermissions('deliveries_alerts');

        $detail_link = anchor('sales/view_delivery/$1', '<i class="fa fa-file-text-o"></i> ' . lang('delivery_details'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('sales/email_delivery/$1', '<i class="fa fa-envelope"></i> ' . lang('email_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('sales/edit_delivery/$1', '<i class="fa fa-edit"></i> ' . lang('edit_delivery'), 'data-toggle="modal" data-target="#myModal"');
        $pdf_link = anchor('sales/pdf_delivery/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_delivery") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete_delivery/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_delivery') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
    <ul class="dropdown-menu pull-right" role="menu">
        <li>' . $detail_link . '</li>
        <li>' . $edit_link . '</li>
        <li>' . $pdf_link . '</li>
        <li>' . $delete_link . '</li>
    </ul>
</div></div>';

        $this->load->library('datatables');
        //GROUP_CONCAT(CONCAT('Name: ', sale_items.product_name, ' Qty: ', sale_items.quantity ) SEPARATOR '<br>')

		$this->datatables
            ->select("deliveries.id as id, date, do_reference_no, sale_reference_no, customer, address, COALESCE(SUM(erp_sale_items.quantity),0) as qty, delivery_status")
            ->from('deliveries')
            ->join('sale_items', 'sale_items.sale_id=deliveries.sale_id', 'left')
            ->group_by('deliveries.id');

		if($date){
			$this->datatables->where('date >=', $date)
				->where('delivery_status =', 'pending');
		}

		if($start && $end){
			$this->datatables->where('date BETWEEN "' . $start . '" AND "' . $end . '"');
		}

        $this->datatables->add_column("Actions", $action, "id");

        echo $this->datatables->generate();
    }

    function view_delivery_combine($id = NULL)
    {
        $this->erp->checkPermissions('deliveries');

        $arr = array();
        if ($this->input->get('data'))
        {
            $arr = explode(',', $this->input->get('data'));
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $deli = $this->sales_model->getDeliveryByID($id);

        $this->data['delivery'] = $deli;
        $sale = $this->sales_model->getInvoiceByID($deli->sale_id);
        $this->data['biller'] = $this->site->getCompanyByID($sale->biller_id);

        $data = array();
        for( $i = 0 ; $i < count($arr); $i ++){
            $deliv = $this->sales_model->getDeliveryByID($arr[$i]);
            $data[] = $deliv->sale_id;
        }

        $this->data['rows'] = $this->sales_model->getAllInvoiceItemsWithDetails($data);
		$this->data['combo_details'] = $this->sales_model->getProductComboItemsCode($data);
        $this->data['user'] = $this->site->getUser($deli->created_by);
        $this->data['page_title'] = lang("delivery_order");

        $this->load->view($this->theme . 'sales/view_delivery_combine', $this->data);
    }

	function pdf_delivery($id = NULL, $view = NULL, $save_bufffer = NULL)
    {
        $this->erp->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $deli = $this->sales_model->getDeliveryByID($id);

        $this->data['delivery'] = $deli;
        $sale = $this->sales_model->getInvoiceByID($deli->sale_id);
        $this->data['biller'] = $this->site->getCompanyByID($sale->biller_id);
        $this->data['rows'] = $this->sales_model->getAllInvoiceItemsWithDetails($deli->sale_id);
        $this->data['user'] = $this->site->getUser($deli->created_by);


        $name = lang("delivery") . "_" . str_replace('/', '_', $deli->do_reference_no) . ".pdf";
        $html = $this->load->view($this->theme . 'sales/pdf_delivery', $this->data, TRUE);
        if ($view) {
            $this->load->view($this->theme . 'sales/pdf_delivery', $this->data);
        } elseif ($save_bufffer) {
            return $this->erp->generate_pdf($html, $name, $save_bufffer);
        } else {
            $this->erp->generate_pdf($html, $name);
        }
    }

	function view_delivery_cabon($id = NULL)
    {
        $this->erp->checkPermissions('deliveries');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $deli = $this->sales_model->getDeliveryByID($id);

        $this->data['delivery'] = $deli;
        $sale = $this->sales_model->getInvoiceByID($deli->sale_id);
        $this->data['biller'] = $this->site->getCompanyByID($sale->biller_id);
        $this->data['rows'] = $this->sales_model->getAllInvoiceItemsWithDetails($deli->sale_id);
        $this->data['user'] = $this->site->getUser($deli->created_by);
        $this->data['page_title'] = lang("delivery_order");
        $this->load->view($this->theme . 'sales/view_delivery_cabon', $this->data);
    }

	function view_delivery($id = NULL)
    {
        $this->erp->checkPermissions('deliveries');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $deli = $this->sales_model->getDeliveryByID($id);

        $this->data['delivery'] = $deli;
        $sale = $this->sales_model->getInvoiceByID($deli->sale_id);
        $this->data['biller'] = $this->site->getCompanyByID($sale->biller_id);
        $this->data['rows'] = $this->sales_model->getAllInvoiceItemsWithDetails($deli->sale_id);
        $this->data['user'] = $this->site->getUser($deli->created_by);
        $this->data['page_title'] = lang("delivery_order");
        $this->load->view($this->theme . 'sales/view_delivery', $this->data);
    }

    function add_delivery($id = NULL)
    {
        $this->erp->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        //$this->form_validation->set_rules('do_reference_no', lang("do_reference_no"), 'required');
        $this->form_validation->set_rules('sale_reference_no', lang("sale_reference_no"), 'required');
        $this->form_validation->set_rules('customer', lang("customer"), 'required');
        $this->form_validation->set_rules('address', lang("address"), 'required');

        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $dlDetails = array(
                'date' => $date,
                'sale_id' => $this->input->post('sale_id'),
                'do_reference_no' => $this->input->post('do_reference_no') ? $this->input->post('do_reference_no') : $this->site->getReference('do'),
                'sale_reference_no' => $this->input->post('sale_reference_no'),
                'customer' => $this->input->post('customer'),
                'address' => $this->input->post('address'),
                'note' => $this->erp->clear_tags($this->input->post('note')),
                'created_by' => $this->session->userdata('user_id'),
				'delivery_status' => $this->input->post('sale_delivery_status')
            );
        } elseif ($this->input->post('add_delivery')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }


        if ($this->form_validation->run() == true && $this->sales_model->addDelivery($dlDetails)) {
            $this->session->set_flashdata('message', lang("delivery_added"));
            redirect("sales/deliveries");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $sale = $this->sales_model->getInvoiceByID($id);
            $this->data['customer'] = $this->site->getCompanyByID($sale->customer_id);
            $this->data['inv'] = $sale;
            $this->data['do_reference_no'] = ''; //$this->site->getReference('do');
            $this->data['modal_js'] = $this->site->modal_js();

            $this->load->view($this->theme . 'sales/add_delivery', $this->data);
        }
    }

    function edit_delivery($id = NULL)
    {
        $this->erp->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('do_reference_no', lang("do_reference_no"), 'required');
        $this->form_validation->set_rules('sale_reference_no', lang("sale_reference_no"), 'required');
        $this->form_validation->set_rules('customer', lang("customer"), 'required');
        $this->form_validation->set_rules('address', lang("address"), 'required');
        //$this->form_validation->set_rules('note', lang("note"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            $dlDetails = array(
                'sale_id' => $this->input->post('sale_id'),
                'do_reference_no' => $this->input->post('do_reference_no'),
                'sale_reference_no' => $this->input->post('sale_reference_no'),
                'customer' => $this->input->post('customer'),
                'address' => $this->input->post('address'),
                'note' => $this->erp->clear_tags($this->input->post('note')),
                'created_by' => $this->session->userdata('user_id'),
				'delivery_status' => $this->input->post('sale_delivery_status')
            );

            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
                $dlDetails['date'] = $date;
            }
        } elseif ($this->input->post('edit_delivery')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }


        if ($this->form_validation->run() == true && $this->sales_model->updateDelivery($id, $dlDetails)) {
            $this->session->set_flashdata('message', lang("delivery_updated"));
            redirect("sales/deliveries");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));


            $this->data['delivery'] = $this->sales_model->getDeliveryByID($id);
            $this->data['modal_js'] = $this->site->modal_js();

            $this->load->view($this->theme . 'sales/edit_delivery', $this->data);
        }
    }

    function delete_delivery($id = NULL)
    {
        $this->erp->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->sales_model->deleteDelivery($id)) {
            echo lang("delivery_deleted");
        }

    }

    function delivery_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->sales_model->deleteDelivery($id);
                    }
                    $this->session->set_flashdata('message', lang("deliveries_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

				if ($this->input->post('form_action') == 'completed_delivery') {
                    foreach ($_POST['val'] as $id) {
                        $this->sales_model->completedDeliveries($id);
                    }
                    $this->session->set_flashdata('message', lang("deliveries_completed"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('deliveries'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('do_reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('sale_reference_no'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('address'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $delivery = $this->sales_model->getDeliveryByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($delivery->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $delivery->do_reference_no);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $delivery->sale_reference_no);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $delivery->customer);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $delivery->address);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);

                    $filename = 'deliveries_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
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
                $this->session->set_flashdata('error', lang("no_delivery_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    /* -------------------------------------------------------------------------------- */

    function payments($id = NULL)
    {
        $this->erp->checkPermissions(false, true);

		$inv = $this->sales_model->getInvoiceByID($id);
		$payments = $this->sales_model->getCurrentBalance($inv->id);
		$current_balance = $inv->grand_total;
		foreach($payments as $curr_pay) {
			//if ($curr_pay->id < $id) {
				$current_balance -= $curr_pay->amount;
			//}
		}
		$this->data['curr_balance'] = $current_balance;
        $this->data['payments'] = $this->sales_model->getInvoicePayments($id);
        $this->load->view($this->theme . 'sales/payments', $this->data);
    }

    function payment_note($id = NULL)
    {
        $payment = $this->sales_model->getPaymentByID($id);
        $inv = $this->sales_model->getInvoiceByID($payment->sale_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['inv'] = $inv;

		$payments = $this->sales_model->getCurrentBalance($inv->id);
		$current_balance = $inv->grand_total;
		foreach($payments as $curr_pay) {
			if ($curr_pay->id < $id) {
				$current_balance -= ($curr_pay->amount-$curr_pay->extra_paid);
			}
		}
		$this->data['curr_balance'] = $current_balance;

		/* Apartment */
		$this->data['rows'] = $this->sales_model->getAllInvoiceItems($inv->id);
		$this->data['exchange_rate_kh_c'] = $this->pos_model->getExchange_rate('KHM');
		/* / */

        $this->data['payment'] = $payment;
        $this->data['page_title'] = $this->lang->line("payment_note");

        $this->load->view($this->theme . 'sales/payment_note', $this->data);
    }

   
	function add_payment($id = NULL, $loans_id = NULL) {
		//$this->erp->print_arrays($loans_id);
		$this->erp->checkPermissions('add', null,'installment_payment');
        $this->load->helper('security');
		$this->load->model('installment_payment_model');
		$this->load->model('accounts_model');
		$ids = explode('_', $loans_id);
		$loan_id = array();
		foreach ($ids as $index)
		{
			$loan_id[] = $index;
		}		
		$last_loan_id = end($loan_id);
		
		
		$this->form_validation->set_rules('applicant', lang("applicant"), 'required');
		$this->form_validation->set_rules('pay_date', lang("pay_date"), 'required');
		$this->form_validation->set_rules('payments', lang("payments"), 'required');
		
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $pay_date = $this->erp->fld(trim($this->input->post('pay_date')));
            } else {
                $pay_date = date('Y-m-d H:i:s');
            }
			
			$saleItem = $this->installment_payment_model->getSaleItemBysaleID($id);	
			$sale = $this->installment_payment_model->getSaleById($id);			
			$setting = $this->site->get_setting();
			
			$df_currency = $setting->default_currency;
			$loan_currency = $saleItem->currency_code;
			$discount = $setting->interest_discount;
						
			$interest_amounts = str_replace(',', '', $this->input->post('interest'));
			$interest_amt = $this->erp->convertCurrency($df_currency, $loan_currency, $interest_amounts);
			$interest_pay = str_replace(',', '', $this->input->post('interest_payment'));
			$interest_payment = $this->erp->convertCurrency($df_currency, $loan_currency, $interest_pay);
			
			if($discount == "enable"){
				$interest_amount = $interest_payment;
			}else{
				$interest_amount = $interest_amt;
			}
			
			$dealer = $this->input->post('dealer');
			$applicant = $this->input->post('applicant');
			$penalty_days = $this->input->post('penalty_days');
			$penalty_amounts = str_replace(',', '', $this->input->post('penalty_amount'));
			$penalty_amount = $paid_amount = $this->erp->convertCurrency($df_currency, $loan_currency, $penalty_amounts);
			
			$principles = str_replace(',', '', $this->input->post('principle'));
			$principle = $this->erp->convertCurrency($df_currency, $loan_currency, $principles);
			
			$total_service_charges = str_replace(',', '', $this->input->post('total_services'));
			$total_service_charge = $this->erp->convertCurrency($df_currency, $loan_currency, $total_service_charges);
			
			$installment_amount = str_replace(',', '', $this->input->post('installment_amount'));
			
			
			
			$other_amounts = str_replace(',', '', $this->input->post('other_paid'));
			$other_amount = $this->erp->convertCurrency($df_currency, $loan_currency, $other_amounts);
			
			$total_payment = str_replace(',', '', $this->input->post('total_payments'));
			$total_payments = $this->erp->convertCurrency($df_currency, $loan_currency, $total_payment);
			
			$payment_amount = str_replace(',', '', $this->input->post('payments'));
			$payments = $this->erp->convertCurrency($df_currency, $loan_currency, $payment_amount);
			
			$owed_paid = str_replace(',', '', $this->input->post('owed_paid'));
			$owed_paids = $this->erp->convertCurrency($df_currency, $loan_currency, $owed_paid);
			
			$balances = str_replace(',', '', $this->input->post('balance'));
			$owed_balance = $this->erp->convertCurrency($df_currency, $loan_currency, $balances);
			
			$services = str_replace(',', '', $this->input->post('services'));
			$service_id = $this->input->post('service_id');			
			
			$pay_method = $this->input->post('pay_method');
			$due_date = $this->input->post('due_date');
			$paid = str_replace(',', '', ($this->input->post('paid')? $this->input->post('paid') : 0));
			$owed = str_replace(',', '', ($this->input->post('owed')? $this->input->post('owed') : 0));
			$amount = str_replace(',', '', ($this->input->post('total_amount')? $this->input->post('total_amount') : 0));
			$total_amount = $amount + $other_amount;
			$pay = $this->input->post('pay');
			$bl = $this->input->post('bl');
			$intergrate_rate = $this->input->post('intergrate_rate');
			$reference_no = $this->site->getReference('sp');
			$total_amt = 0;
			$balance = 0;
			/*if($payment_status == 'partial') {
				$total_amt = $amount_partial;
				$balance = $partial_owed_bl;
			}else if ($payment_status == 'received'){
				//$total_amt = $penalty_amount + $total_service_charge + $principle + $interest_amount + $other_amount + $owed - $paid ;
				
				$total_amt = $total_amount - $paid ;
				$balance = 0;
			}
			$total_amts = $this->erp->convertCurrency($df_currency, $loan_currency, $total_amt);
			$balances = $this->erp->convertCurrency($df_currency, $loan_currency, $balance);
			$depositID = null;
			if ($pay_method == 'deposit'){
				$depositID = $deposit_id;
			}*/
			
			$payment_status = "";
			if($payments < $total_payments){
				$payment_status	= "partial";		
			}else{
				$payment_status	= "received";
			}						
			/////owed_paid			
			if($payments > $owed_paids){
				$owed_paid = $owed_paids;
			}else{
				$owed_paid = $payments;
			}			
			$bl_from_owedPaid = $payments - $owed_paid;	
			
			/////interest_paid			
			if($bl_from_owedPaid > $interest_amount){
				$interest_paid = $interest_amount;
			}else{
				$interest_paid = $bl_from_owedPaid;
			}
			$owed_interest = $interest_amount - $interest_paid;
			$bl_from_interestPaid = $bl_from_owedPaid - $interest_paid;
			/////total_services_paid						
			if($bl_from_interestPaid > $total_service_charge){
				$total_services_paid = $total_service_charge;
			}else{
				$total_services_paid = $bl_from_interestPaid;
			}
			$owed_services = $total_service_charge - $total_services_paid;
			$bl_from_servicePaid = $bl_from_interestPaid - $total_services_paid;
			$loan_total_balance = $this->erp->convertCurrency($loan_currency, $df_currency, $total_services_paid);
			/////penalty_paid			
			if($bl_from_servicePaid > $penalty_amount){
				$penalty_paid = $penalty_amount;
			}else{
				$penalty_paid = $bl_from_servicePaid;
			}
			$owed_penalty = $penalty_amount - $penalty_paid;
			$bl_from_penaltyPaid = $bl_from_servicePaid - $penalty_paid;
			
			/////other_paids			
			if($bl_from_penaltyPaid > $other_amount){
				$other_paids = $other_amount;
			}else{
				$other_paids = $bl_from_penaltyPaid;
			}
			$owed_other_paid = $other_amount - $other_paids;
			$bl_from_otherPaid = $bl_from_penaltyPaid - $other_paids;		
			
			/////priciple_paid			
			if($bl_from_otherPaid >= $principle){
				$priciple_paid = $principle;
			}else{
				$priciple_paid = $bl_from_otherPaid;
			}
			$owed_principle = $principle - $priciple_paid;
			
			$saving_interest = str_replace(',', '', $this->input->post('saving_interest'));
			$saving_interest_amt = $this->erp->convertCurrency($df_currency, $loan_currency, $saving_interest);
			$payment = array(
								'date' 					=> $pay_date,
								'biller_id'				=> $sale->branch_id,
								'sale_id' 				=> $id,
								'loan_id' 				=> $loans_id,
								'deposit_id' 			=> $depositID,
								'reference_no' 			=> $reference_no, //$this->input->post('reference'),
								'amount' 				=> $payments,
								'interest_discount'		=> $this->input->post('discount_rate'),
								'total_interest'		=> $interest_amt,
								'interest_amount' 		=> $interest_paid,
								'penalty_amount' 		=> $penalty_paid,
								'principle_amount' 		=> $priciple_paid,
								'service_amount' 		=> $total_services_paid,
								'owed_paid'				=> $owed_paid,
								'other_paid' 			=> $other_paids,
								'paid_by' 				=> $pay_method,								
								'bank_acc_code'			=> $this->input->post('bank_account'),
								'created_by' 			=> $this->session->userdata('user_id'), 
								'type'					=> 'received', //$payment_status,		
								'extra_paid'			=> $penalty_paid,
								'paid_type' 			=> 'Loans Received',
								'note' 					=> 'Loans Received',
								'group_payment_id' 		=> $id,
								'invoce_no'				=> $this->input->post('invoce_no'),
								'owed' 					=> $owed_balance,
								'owed_interest'			=> $owed_interest,
								'owed_services'			=> $owed_services,
								'owed_penalty'			=> $owed_penalty,
								'owed_other_paid'		=> $owed_other_paid,
								'owed_principle'		=> $owed_principle,
								'saving_balance'		=> $saving_interest_amt,
								
							);
							
							if ($_FILES['document']['size'] > 0){
								$this->load->library('upload');
								$config['upload_path'] = "./assets/uploads/documents/";
								$config['allowed_types'] = 'gif|jpg|jpeg|png|pdf|csv';
								$config['max_size'] = $this->allowed_file_size;
								$config['overwrite'] = FALSE;
								$config['encrypt_name'] = TRUE;
								$this->upload->initialize($config);
								if (!$this->upload->do_upload('document')) {
									$error = $this->upload->display_errors();
									$this->session->set_flashdata('error', $error);
									redirect($_SERVER["HTTP_REFERER"]);
								}
								$file = $this->upload->file_name;
								$payment['document'] = $file;
							}
			//$this->erp->print_arrays($payment);
			//$paid = $total_services_paid ;
			$paid = str_replace(',', '', $this->erp->roundUpMoney($total_services_paid, $df_currency));
			$arr_services = array();			
			if($services){
				$n = sizeof($services);
				for($i = 0; $i < $n; $i++) {					
					$service = $this->erp->convertCurrency($df_currency, $loan_currency,  $services[$i]);
					if ($service > $paid){
						$service_am = $paid;
						$owed = $service  - $paid;
						$service_ow = $owed;
						$paid = 0;
					} else {
						$service_am = $service;
						$paid = $paid - $service;
						$service_ow = 0;
					}
					$arr_services[] = array(
						'sale_id' 		=> $id,
						'loan_id' 		=> $loans_id,
						'service_id' 	=> $service_id[$i],
						'amount'	 	=> $service_am,
						'owed'			=> $service_ow,
					);
				}
			}			
			//$this->erp->print_arrays($arr_services);
			$laon = array();
			$paid_amount = 0;
			$owed = 0;
			$spaid = 0;
			for($i=0; $i<sizeof($loan_id); $i++) {
				if($payment_status == 'partial') {
					if($total_amt > $_POST['installment'][$i] || ($total_amt > $_POST['owed'][$i] && $_POST['owed'][$i] > 0)) {
						$paid_amount = $_POST['installment'][$i];
						$total_amt -= (($_POST['owed'][$i] > 0)? $_POST['owed'][$i] : ($_POST['installment'][$i] + $_POST['ovamounts'][$i]));
						$owed = 0;
					}else {
						$paid_amount = (($_POST['paid_amount'][$i] > 0)? ($_POST['paid_amount'][$i] + $total_amt) : $total_amt);
						if($_POST['owed'][$i] > 0) {
							$owed -= $total_amt;
						}else {
							$owed = ($_POST['installment'][$i]+$_POST['ovamounts'][$i]+$other_amounts) - $total_amt;
						}
						$total_amt = 0;
					}
					$balance = $partial_owed_bl;
				}				
				if($payments > 0) {
					$loan[] = array(
									'id'				 	=> $loan_id[$i],
									'reference_no'		 	=> $reference_no, //$this->input->post('reference'),
									'created_by'		 	=> $this->session->userdata('user_id'),
									'paid_by'			 	=> $pay_method,
									'paid_amount' 		 	=> $payment_amount,
									'paid_date' 		 	=> $pay_date,
									'biller_id'			 	=> $sale->branch_id,
									'other_amount' 		 	=> $this->erp->convertCurrency($loan_currency, $df_currency, $other_paids),
									'total_service_charge' 	=> $loan_total_balance,
									'overdue_days' 		 	=> $_POST['ovdays'][$i],
									'overdue_amount'	 	=> $_POST['ovamounts'][$i],
									'owed'				 	=> $balances,
								 );
					$spaid += $paid_amount;
				}
			}
			//$this->erp->print_arrays($loan);
			$old_payment = $this->installment_payment_model->getLastOldPayment($id);
			$old_Ref_payment = $old_payment->reference_no;
			
			$interest	= $old_payment->interest_amount + $old_payment->owed_interest;
			$service = $old_payment->service_amount + $old_payment->owed_services;
			$penalty = $old_payment->penalty_amount + $old_payment->owed_penalty;
			$other_paid = $old_payment->other_paid + $old_payment->owed_other_paid;
			$principle = $old_payment->principle_amount + $old_payment->owed_principle;			
			
			if($owed_paid > 0){
				$old_payments = array(
													
									'interest_amount' 		=> $interest,
									'service_amount' 		=> $service,
									'penalty_amount' 		=> $penalty,
									'other_paid' 			=> $other_paid,
									'principle_amount' 		=> $principle,
									'owed'					=> 0,
									'owed_interest'			=> 0,
									'owed_services'			=> 0,
									'owed_penalty'			=> 0,
									'owed_other_paid'		=> 0,
									'owed_principle'		=> 0,
									
			
								);			
						
				$old_services = $this->installment_payment_model->getServicepayment($old_payment->id);
				$owed_services = $this->installment_payment_model->getOwedServicepayment($old_payment->id);
				
				if($old_payment->owed_services > 0){
					foreach($old_services as $old_service){
						$old_sv_amount = $old_service->amount;
						$owed_sv_amount = $old_service->owed;
						$amount = $old_sv_amount + $owed_sv_amount;
						$sv_id = $old_service->service_id;
						$n = sizeof($sv_id);
						for($i = 0; $i < $n; $i++) {
							$arr_oldservices[] = array(
								'sale_id' 		=> $id,
								'payment_id'	=> $old_service->payment_id,
								'loan_id' 		=> $old_payment->loan_id,
								'service_id' 	=> $sv_id[$i],
								'amount'	 	=> $amount,
								'owed'			=> 0,
							);
						}
					}
				}
				
			}
			//$this->erp->print_arrays($old_payments);
			
        } elseif ($this->input->post('add_payment')) {			
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->installment_payment_model->addPayment($payment, $arr_services, $old_payments, $arr_oldservices, $last_loan_id) && $this->installment_payment_model->updateLoan($loan, $id, $old_Ref_payment)) {
          $this->data['PaymentByRef']=$this->installment_payment_model->getPaymentByRef($reference_no);
		  $this->data['service']=$this->installment_payment_model->getServiceByID($id);

		  $this->data['modal_js'] = $this->site->modal_js();
		  $this->data['product']=$this->installment_payment_model->getItemByIDs($id);
			$this->data['exchange_rate_kh_c'] = $this->pos_model->getExchange_rate('KHM');
			$this->session->set_flashdata('message', lang("payment_added"));
			redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['billers'] = ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) ? $this->site->getAllCompanies('supplier') : null;
            $default_currency = $this->site->get_setting();
			$sale = $this->down_payment_model->getSaleByID($id);
			if($sale){
				$sale_item = $this->installment_payment_model->getSaleItemBySaleID($sale->id);
				$this->data['sale'] = $sale;
				$this->data['sale_item'] = $sale_item;
				if($sale_item) {
					$this->data['loan_currency'] = $this->site->getCurrencyByCode($sale_item->currency_code);
				}
				$this->data['services'] = $this->installment_payment_model->getServicesBySaleID($sale->id);
				$this->data['count'] = $this->installment_payment_model->getLoanBySaleId($sale->id);
			}			
			$setting = $this->settings_model->getSettings();
			$this->data['reference_sp'] = $this->site->getReference('sp');
			$this->data['sale_item'] = $this->installment_payment_model->getSaleItemBySaleID($sale->id);
			$this->data['loan_owed'] = $this->installment_payment_model->getLoanOwedBySaleId($sale->id);
			$this->data['defaultcurrency'] = $this->installment_payment_model->getSettingCurrncy();
			$this->data['getLoan'] = $this->installment_payment_model->getLoanCurrency($loan_id);
			$this->data['deposits'] = $this->installment_payment_model->getCusDeposit($loan_id);
			$this->data['customers'] = $this->down_payment_model->getAllCustomerCompanies();
			$this->data['loans'] = $this->installment_payment_model->getLoanByID($loan_id);
			$this->data['setting'] = $setting;
			$this->data['banks'] = $this->accounts_model->getBankAccount();
			$this->data['default_currency'] = $this->site->getCurrencyByCode($setting->default_currency);
			$this->data['down_percentages'] = $this->site->getAllDownPercentage();
			$this->data['finacal_products'] = $this->site->getAllCustomerGroup();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'installment_payment/add_payment', $this->data);
        }
	}
	
	public function edit_payments($loan_id = NULL, $sale_id = NULL, $pay_id = NULL)
    {
		$this->erp->checkPermissions('edit', true,'installment_payment');
        $this->load->helper('security');
        $this->load->model('installment_payment_model');
		$this->load->model('accounts_model');
			
        $this->form_validation->set_rules('payments', lang("payments"), 'required');
        $this->form_validation->set_rules('pay_method', lang("pay_method"), 'required');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('pay_date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
			
			$loan_payments = $this->installment_payment_model->getFirstPaymentByloanID($loan_id);
			$pay_Ref = $loan_payments->reference_no;
			//$payments = $this->installment_payment_model->getPaymentByloanID($loan_id);
			
			$payment = $this->installment_payment_model->getLastPaymentByloanID($loan_id);
			$payment_id = $payment->id;
			$saleItem = $this->installment_payment_model->getSaleItemSaleByID($sale_id);
			$sale = $this->installment_payment_model->getSaleById($sale_id);	
			$setting = $this->installment_payment_model->get_setting();	
			$discount = $setting->interest_discount;
			
			
			$pay_method = $this->input->post('pay_method');
			
			$penalty_amounts = str_replace(',', '', $this->input->post('penalty_amount'));
			$penalty_amount = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $penalty_amounts);
			
			$principles = str_replace(',', '', $this->input->post('principle'));
			$principle = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $principles);
						
			$interest_amounts = str_replace(',', '', $this->input->post('interest'));
			$interest_amt = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $interest_amounts);
			$interest_pay = str_replace(',', '', $this->input->post('interest_payment'));
			$interest_payment = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $interest_pay);
			if($discount == "enable"){
				$interest_amount = $interest_payment;
			}else{
				$interest_amount = $interest_amt;
			}
			
			$total_servive = str_replace(',', '', $this->input->post('total_services'));
			$total_service_charge = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $total_servive);
			
			$other_paid = str_replace(',', '', $this->input->post('other_paid'));
			$other_amount = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $other_paid);
			
			$total_payment = str_replace(',', '', $this->input->post('total_payments'));
			$total_payments = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $total_payment);
			
			$payment_ = str_replace(',', '', $this->input->post('payments'));
			$payments = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $payment_);
			
			$owed_paid_ = str_replace(',', '', $this->input->post('owed_paid'));
			$owed_paids = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $owed_paid_);
			
			$balance = str_replace(',', '', $this->input->post('balance'));
			$balances = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $balance);
			
			$services = str_replace(',', '', $this->input->post('services'));
			$service_id = $this->input->post('service_id');
			
			$payment_status = "";
			if($payments < $total_payments){
				$payment_status	= "partial";		
			}else{
				$payment_status	= "received";
			}
			
			/////	
			if($payments > $owed_paids){
				$owed_paid = $owed_paids;
			}else{
				$owed_paid = $payments;
			}
			$bl_from_owedPaid = $payments - $owed_paid;	
			/////	
			if($bl_from_owedPaid > $interest_amount){
				$interest_paid = $interest_amount;
			}else{
				$interest_paid = $bl_from_owedPaid;
			}
			$owed_interest = $interest_amount - $interest_paid;
			$bl_from_interestPaid = $bl_from_owedPaid - $interest_paid;	
			/////					
			if($bl_from_interestPaid > $total_service_charge){
				$total_services_paid = $total_service_charge;
			}else{
				$total_services_paid = $bl_from_interestPaid;
			}
			$owed_services = $total_service_charge - $total_services_paid;
			$bl_from_servicePaid = $bl_from_interestPaid - $total_services_paid;			
			/////	
			if($bl_from_servicePaid > $penalty_amount){
				$penalty_paid = $penalty_amount;
			}else{
				$penalty_paid = $bl_from_servicePaid;
			}
			$owed_penalty = $penalty_amount - $penalty_paid;
			$bl_from_penaltyPaid = $bl_from_servicePaid - $penalty_paid;			
			/////		
			if($bl_from_penaltyPaid > $other_amount){
				$other_paids = $other_amount;
			}else{
				$other_paids = $bl_from_penaltyPaid;
			}
			$owed_other_paid = $other_amount - $other_paids;
			$bl_from_otherPaid = $bl_from_penaltyPaid - $other_paids;		
			
			/////
			if($bl_from_otherPaid > $principle){
				$priciple_paid = $principle;
			}else{
				$priciple_paid = $bl_from_otherPaid;
			}
			$owed_principle = $principle - $priciple_paid;	
			
			/*$arr_services = array();
			if($services){
				$n = sizeof($services);
				for($i = 0; $i < $n; $i++) {
					$arr_services[] = array(
						'sale_id' => $sale_id,
						'loan_id' => $loan_id,
						'service_id' => $service_id[$i],
						'amount' => $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $services[$i]),
					);
				}
			}*/
			
            $edit_payment = array(
										'date' 					=> $date,
										'biller_id'				=> $sale->branch_id,
										'sale_id'				=> $sale_id,
										'loan_id'				=> $loan_id,
										'amount' 				=> $payments,
										'total_interest'		=> $interest_amt,
										'interest_amount'		=> $interest_paid,
										'penalty_amount' 		=> $penalty_paid,
										'principle_amount'  	=> $priciple_paid,
										'service_amount' 		=> $total_services_paid,
										'owed_paid'				=> $owed_paid,
										'other_paid' 			=> $other_paids,
										'paid_by' 				=> $pay_method,
										'owed' 					=> $balances,
										'type' 					=> 'received', //$payment_status,
										'paid_type'				=> 'Loans Received',
										'note'					=> 'Loans Received',
										'extra_paid'			=> $penalty_paid,
										'interest_discount'		=> $this->input->post('discount_rate'),
										'bank_acc_code'			=> $this->input->post('bank_account'),
										'updated_by' 			=> $this->session->userdata('user_id'), 
										'invoce_no'				=> $this->input->post('invoce_no'),
										'owed_interest'			=> $owed_interest,
										'owed_services'			=> $owed_services,
										'owed_penalty'			=> $owed_penalty,
										'owed_other_paid'		=> $owed_other_paid,
										'owed_principle'		=> $owed_principle,
               
								);
							
		
			//$this->erp->print_arrays($edit_payment);
			$paid = str_replace(',', '', $this->erp->roundUpMoney($total_services_paid, $setting->default_currency));
			$arr_services = array();			
			if($services){
				$n = sizeof($services);
				for($i = 0; $i < $n; $i++) {					
					$service = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $services[$i]);
					if ($service > $paid){
						$service_am = $paid;
						$owed = $service  - $paid;
						$service_ow = $owed;
						$paid = 0;
					} else {
						$service_am = $service;
						$paid = $paid - $service;
						$service_ow = 0;
					}
					$arr_services[] = array(
						'sale_id' 		=> $sale_id,
						'loan_id' 		=> $loan_id,
						'service_id' 	=> $service_id[$i],
						'amount'	 	=> $service_am,
						'owed'			=> $service_ow,
					);
				}
			}
			//$this->erp->print_arrays($arr_services);
			
			$payment = $this->installment_payment_model->getLastPaymentByloanID($loan_id);
			$old_paid = $this->erp->convertCurrency($saleItem->currency_code, $setting->default_currency, $payment->amount);
			$old_services = $this->erp->convertCurrency($saleItem->currency_code, $setting->default_currency, $payment->service_amount);
			$old_other = $this->erp->convertCurrency($saleItem->currency_code, $setting->default_currency, $payment->other_paid);
			$old_penalty = $this->erp->convertCurrency($saleItem->currency_code, $setting->default_currency, $payment->penalty_amount);
			
			$loan = $this->installment_payment_model->getLoanByReference($pay_Ref);
			$loan_paid_amount = ($loan->paid_amount - $old_paid) + $payment_ ;
			$loan_services = ($loan->total_service_charge - $old_services) + $total_servive ;
			$loan_other_paids = ($loan->other_amount - $old_other) + $other_paid;
			$loan_penalty = ($loan->penalty_amount - $old_penalty) + $penalty_amounts ;
			
			$updateloan = array(
				
								'paid_amount' 			=> $loan_paid_amount,
								'biller_id'				=> $sale->branch_id,
								'total_service_charge'	=> $loan_services,
								'other_amount'			=> $loan_other_paids,
								'overdue_amount'		=> $loan_penalty,
								'owed'					=> $balance,
							);
							
			//$this->erp->print_arrays($updateloan);		
			
            if ($_FILES['document']['size'] > 0) {
				$this->load->library('upload');
				$config['upload_path'] = "./assets/uploads/documents/";
				$config['allowed_types'] = 'gif|jpg|jpeg|png|pdf|csv';
				$config['max_size'] = $this->allowed_file_size;
				$config['overwrite'] = FALSE;
				$config['encrypt_name'] = TRUE;
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('document')) {
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error', $error);
					redirect($_SERVER["HTTP_REFERER"]);
				}
				$file = $this->upload->file_name;
				$edit_payment['document'] = $file;
			}
            //$this->erp->print_arrays($loan_payment);

        } elseif ($this->input->post('edit_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->installment_payment_model->updatePayments($payment_id , $edit_payment , $arr_services, $updateloan, $pay_Ref)) {
            $this->session->set_flashdata('message', lang("payment_updated"));
            redirect($_SERVER["HTTP_REFERER"]);
			//redirect('Installment_payment');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['loan_id'] = $loan_id;
			$this->data['sale_id'] = $sale_id;			
            $this->data['payment'] = $this->installment_payment_model->getPaymentByloanID($pay_id);
			$this->data['sale_item'] = $this->installment_payment_model->getSaleItemSaleByID($sale_id);
			$this->data['service_payment'] = $this->installment_payment_model->getServiceByPaymentID($pay_id);
			$this->data['setting'] = $this->installment_payment_model->get_setting();
			$this->data['banks'] = $this->accounts_model->getBankAccount();
			$this->data['modal_js'] = $this->site->modal_js();
			//$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('edit_payment')));
			//$meta = array('page_title' => lang('edit_payment'), 'bc' => $bc);		   
			//$this->page_construct('installment_payment/edit_payment', $meta, $this->data);			
			$this->load->view($this->theme . 'installment_payment/edit_payment', $this->data);
        }
		
    }
	
	
	
	function re_payments($loan_id = NULL, $sale_id = NULL) {
        $this->erp->checkPermissions('edit', true);
        $this->load->helper('security');
        $this->load->model('installment_payment_model');
		$this->load->model('accounts_model');
		
		$payment = $this->installment_payment_model->getFirstPaymentByloanID($loan_id);
		$payment_id = $payment->id;
		
		$last_payment = $this->installment_payment_model->getLastPaymentByloanID($loan_id);
		//$this->erp->print_arrays($last_payment);
		$last_payment_id = $last_payment->id;
		//$this->erp->print_arrays($last_payment_id);
		$saleItem = $this->installment_payment_model->getSaleItemSaleByID($sale_id);
		$sale = $this->installment_payment_model->getSaleById($sale_id);	
		$setting = $this->installment_payment_model->get_setting();
		
        $this->form_validation->set_rules('payments', lang("payments"), 'required');
        $this->form_validation->set_rules('pay_method', lang("pay_method"), 'required');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('pay_date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
			$reference_no = $this->site->getReference('sp');
								
			$payments = $this->installment_payment_model->getLastPaymentByloanID($loan_id);			
			$owed_interest = $payments->owed_interest;
			$owed_service = $payments->owed_services;
			$owed_penalty = $payments->owed_penalty;
			$owed_other_paid = $payments->owed_other_paid;
			$owed_principle = $payments->owed_principle;
			//$this->erp->print_arrays($payments);
			$re_payment = str_replace(',', '', $this->input->post('re_payments'));
			$re_payments = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $re_payment);
			
			$owed_balance = str_replace(',', '', $this->input->post('new_owed_balance'));
			$owed_balances = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $owed_balance);
			
			////////////
			if($re_payments >= $owed_interest){
				$interest_paid = $owed_interest;
			}else{
				$interest_paid = $re_payments;
			}
			$owed_interests = $owed_interest - $interest_paid;
			$bl_from_interestPaid = $re_payments - $interest_paid;	
			
			///////
			if($bl_from_interestPaid >= $owed_service){
				$services_paid = $owed_service;
			}else{
				$services_paid = $bl_from_interestPaid;
			}
			$owed_services = $owed_service - $services_paid;
			$bl_from_servicePaid = $bl_from_interestPaid - $services_paid;
			//$this->erp->print_arrays($interest_paid);
			/////	
			if($bl_from_servicePaid >= $owed_penalty){
				$penalty_paid = $owed_penalty;
			}else{
				$penalty_paid = $bl_from_servicePaid;
			}
			$owed_penaltys = $owed_penalty - $penalty_paid;
			$bl_from_penaltyPaid = $bl_from_servicePaid - $penalty_paid;
			
			/////		
			if($bl_from_penaltyPaid >= $owed_other_paid){
				$other_paids = $owed_other_paid;
			}else{
				$other_paids = $bl_from_penaltyPaid;
			}
			$owed_other_paids = $owed_other_paid - $other_paids;
			$bl_from_otherPaid = $bl_from_penaltyPaid - $other_paids;		
			
			/////
			if($bl_from_otherPaid >= $owed_principle){
				$priciple_paid = $owed_principle;
			}else{
				$priciple_paid = $bl_from_otherPaid;
			}
			$owed_principles = $owed_principle - $priciple_paid;			
			
			if($re_payments > 0){
			
				$RePayments = array(
										'date' 					=> $this->erp->fld(trim($this->input->post('pay_date'))),
										'reference_no' 			=> $reference_no, //$this->input->post('reference'),
										'biller_id'				=> $sale->branch_id,
										'sale_id'				=> $sale_id,
										'loan_id'				=> $loan_id,
										'amount' 				=> $re_payments,
										'interest_amount'		=> $interest_paid,
										'penalty_amount' 		=> $penalty_paid,
										'principle_amount'  	=> $priciple_paid,
										'service_amount' 		=> $services_paid,
										'other_paid' 			=> $other_paids,
										'paid_by' 				=> $this->input->post('pay_method'),
										'owed' 					=> $owed_balances,
										'type' 					=> 'received',
										'paid_type'				=> 'Loans Received',
										'note'					=> 'Loans Received',
										'extra_paid'			=> $penalty_paid,
										'bank_acc_code'			=> $this->input->post('bank_account'),
										'created_by' 			=> $this->session->userdata('user_id'), 
										'invoce_no'				=> $this->input->post('invoce_no'),
										'owed_interest'			=> $owed_interests,
										'owed_services'			=> $owed_services,
										'owed_penalty'			=> $owed_penaltys,
										'owed_other_paid'		=> $owed_other_paids,
										'owed_principle'		=> $owed_principles,
               
							);
				//$this->erp->print_arrays($RePayments);

				$loan_payments = $this->installment_payment_model->getFirstPaymentByloanID($loan_id);
				$pay_Ref = $loan_payments->reference_no;
				$loan_payment = $this->installment_payment_model->getLoanByReference($pay_Ref);
				$loan_paid_amount = $re_payment + $loan_payment->paid_amount;
				$loan_owed = $owed_balance;
				$service_paid = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $services_paid);
				$loan_services = $loan_payment->total_service_charge + $service_paid;
				$other_paids = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $other_paids);
				$loan_other_paids = $loan_payment->other_amount + $other_paids;
				$penalty_amount = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $penalty_paid);
				$loan_penalty = $loan_payment->overdue_amount + $penalty_amount;
				$updateloan = array(
				
								'paid_amount' 			=> $loan_paid_amount,
								'biller_id'				=> $sale->branch_id,
								'total_service_charge'	=> $loan_services,
								'other_amount'			=> $loan_other_paids,
								'overdue_amount'		=> $loan_penalty,
								'owed'					=> $loan_owed,
							);
							
				
				
				$paid = str_replace(',', '', $this->erp->roundUpMoney($services_paid, $setting->default_currency));
				$services = str_replace(',', '', $this->input->post('owed_services'));
				$service_id = $this->input->post('owed_service_id');
				$arr_services = array();			
				if($services){
					$n = sizeof($services);
					for($i = 0; $i < $n; $i++) {					
						$service = $this->erp->convertCurrency($setting->default_currency, $saleItem->currency_code, $services[$i]);
						if ($service > $paid){
							$service_am = $paid;
							$owed = $service  - $paid;
							$service_ow = $owed;
							$paid = 0;
						} else {
							$service_am = $service;
							$paid = $paid - $service;
							$service_ow = 0;
						}
						$arr_services[] = array(
							'sale_id' 		=> $sale_id,
							'loan_id' 		=> $loan_id,
							'service_id' 	=> $service_id[$i],
							'amount'	 	=> $service_am,
							'owed'			=> $service_ow,
						);
					}
				}
				
				
				
			}
			
			//$this->erp->print_arrays($pay_Ref);			
			/////////////
        } elseif ($this->input->post('edit_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }


        if ($this->form_validation->run() == true && $this->installment_payment_model->addRePayment($RePayments , $updateloan , $arr_services, $pay_Ref)) {
            $this->session->set_flashdata('message', lang("re_payment_added"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
			
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['loan_id'] = $loan_id;
			$this->data['sale_id'] = $sale_id;	
			$this->data['reference_sp'] = $this->site->getReference('sp');
            $this->data['payment'] = $this->installment_payment_model->getFirstPaymentByloanID($loan_id);
			$this->data['re_payment'] = $this->installment_payment_model->getLastPaymentByloanID($loan_id);
			$this->data['sale_item'] = $saleItem;
			$this->data['service_payment'] = $this->installment_payment_model->getServiceByPaymentID($payment_id);
			$this->data['last_service_payment'] = $this->installment_payment_model->getLastServiceByPaymentID($last_payment_id);
			$this->data['setting'] = $this->installment_payment_model->get_setting();
			$this->data['banks'] = $this->accounts_model->getBankAccount();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'installment_payment/re_payments', $this->data);
        }
    }
	
	function combine_payment(){
        $this->erp->checkPermissions('payments', true);
        $this->load->helper('security');
        $arr = array();

        if ($this->input->get('data'))
        {
            $arr = explode(',', $this->input->get('data'));
        }
        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
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
                //$payment['attachment'] = $photo;
            }
			$sale_id_arr = $this->input->post('sale_id');
			$amount_paid_arr = $this->input->post('amount_paid_line');
			$i = 0;
			$reference_no = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('sp');
			foreach($sale_id_arr as $sale_id){
				$payment = array(
					'date' => $date,
					'sale_id' => $sale_id,
					'reference_no' => $reference_no,
					'amount' => $amount_paid_arr[$i],
					'paid_by' => $this->input->post('paid_by'),
					'cheque_no' => $this->input->post('cheque_no'),
					'cc_no' => $this->input->post('paid_by') == 'gift_card' ? $this->input->post('gift_card_no') : $this->input->post('pcc_no'),
					'cc_holder' => $this->input->post('pcc_holder'),
					'cc_month' => $this->input->post('pcc_month'),
					'cc_year' => $this->input->post('pcc_year'),
					'cc_type' => $this->input->post('pcc_type'),
					'note' => $this->input->post('note'),
					'created_by' => $this->session->userdata('user_id'),
					'type' => 'received',
					'biller_id'	=> $this->input->post('biller'),
					'attachment' =>$photo
				);
				$this->sales_model->addPayment($payment);
				$i++;
			}

            //$this->erp->print_arrays($payment);
			$this->session->set_flashdata('message', lang("payment_added"));
            redirect('account/list_ac_recevable');

        } else{
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['billers'] = $this->site->getAllCompanies('biller');
            $combine_payment = $this->sales_model->getCombinePaymentById($arr);
            $this->data['combine_sales'] = $combine_payment;
            $this->data['payment_ref'] = ''; //$this->site->getReference('sp');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'sales/combine_payment', $this->data);
		}
    }

	function combine_payment_old()
    {
        $this->erp->checkPermissions('payments', true);
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        //$this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
		$this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
		   if (!empty($_POST['val'])) {
				if ($this->input->post('form_action') == 'delete') {
					foreach ($_POST['val'] as $id) {
						$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
						$this->data['billers'] = $this->site->getAllCompanies('biller');
						$this->data['get_minv'] = $this->sales_model->getmulti_InvoiceByID($id);
						$this->data['payment_ref'] = ''; //$this->site->getReference('sp');
				   ///  $this->data['modal_js'] = $this->site->modal_js();
					}
					$this->session->set_flashdata('message', lang("sales_deleted"));
					redirect($_SERVER["HTTP_REFERER"]);
				}
		   }
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
        } elseif ($this->input->post('combine_pay')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->load->view($this->theme . 'sales/combine_payment', $this->data);
    }

	

    function delete_payment($id = NULL)
    {
        $this->erp->checkPermissions('delete');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->sales_model->deletePayment($id)) {
            //echo lang("payment_deleted");
            $this->session->set_flashdata('message', lang("payment_deleted"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

	function add_payment_loan($data = NULL,$id = NULL,$paid_amount = NULL,$principle = NULL)
    {

        $this->erp->checkPermissions('payments', true);
        $this->load->helper('security');

        //$this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
		$this->form_validation->set_rules('date', lang("date"), 'required');
        if ($this->form_validation->run() == true) {
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
			$loan_ids = $this->input->post('loan_id');
			$paid_amounts = $this->input->post('paid_amount');
			$amount = $this->input->post('amount-paid');
			$extra_rate = $this->input->post('extra_amt');
			$principles = $this->input->post('principle');
			$arr_id = explode("_",$loan_ids);
			$arr_paid = explode("_",$paid_amounts);
			$arr_principle = explode("_",$principles);
			$curr_paid = 0;
			$help = false;
			for($i=0; $i<sizeof($arr_id)-1; $i++){
				$loans = array(
					'paid_date' => $date,
					'id' => $arr_id[$i],
					'reference_no' => $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('sp'),
					'paid_amount' => $amount,
					'paid_by' => $this->input->post('paid_by'),
					'note' => $this->input->post('note'),
					'created_by' => $this->session->userdata('user_id')
				);
				$curr_paid += $arr_principle[$i];
				if($this->sales_model->addPaymentLoan($loans)) {
					$sale_loan = $this->sales_model->getSaleId($arr_id[$i]);
					$help = true;
				}
			}
			if($help) {
				$payments = array(
					'biller_id' => $this->session->userdata('user_id'),
					'date' => $date,
					'sale_id' => $sale_loan->sale_id,
					'reference_no' => $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('sp'),
					'amount' => ($amount+$extra_rate),
					'paid_by' => $this->input->post('paid_by'),
					'created_by' => $this->session->userdata('user_id'),
					'note' => $this->input->post('note'),
					'type' => 'received',
					'extra_paid' => $extra_rate
				);
				$this->sales_model->addLoanPayment($payments);
			}
        } elseif ($this->input->post('add_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true) {
            $this->session->set_flashdata('message', lang("payment_loan_added"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
			$val = array();
			if(isset($_GET['ids']) || isset($_GET['values'])){
				$ids = $_GET['ids'];
				$values = $_GET['values'];
				foreach (array_combine($ids, $values) as $id => $value){
					$val =  array(
						'id' => $id,
						'value' => $value
					);
				}
			}

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['values'] = $val;
            $this->data['loan'] = $this->sales_model->getSingleLoanById($id);
            $this->data['payment_ref'] = ''; //$this->site->getReference('sp');
            $this->data['modal_js'] = $this->site->modal_js();
			$this->data['total_payment'] = $data;
			$this->data['id'] = $id;
			$this->data['paid_amount'] = $paid_amount;
			$this->data['principle'] = $principle;
            $this->load->view($this->theme . 'sales/add_payment_loan', $this->data);
        }

    }

    /* --------------------------------------------------------------------------------------------- */

    function suggestions()
    {
        $term = $this->input->get('term', TRUE);
        $warehouse_id = $this->input->get('warehouse_id', TRUE);
        $customer_id = $this->input->get('customer_id', TRUE);

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
        $rows = $this->sales_model->getProductNames($sr, $warehouse_id);
        if ($rows) {
            foreach ($rows as $row) {
                $option = FALSE;
                $row->quantity = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty = 1;
                $row->discount = '0';
                $row->serial = '';
                $options = $this->sales_model->getProductOptions($row->id, $warehouse_id);
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
                $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                        if($pis){
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }

                if ($opt->price != 0) {
					if($customer_group->makeup_cost == 1){
						$row->price = $row->cost + (($row->cost * $customer_group->percent) / 100);
					}else{
						$row->price = $opt->price + (($opt->price * $customer_group->percent) / 100);
					}
                } else {
					if($customer_group->makeup_cost == 1){
						$row->price = $row->cost + (($row->cost * $customer_group->percent) / 100);
					}else{
						$row->price = $row->price + (($row->price * $customer_group->percent) / 100);
					}
                }

                $row->real_unit_price = $row->price;
                $combo_items = FALSE;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $warehouse_id);
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

    function suggestionsSale()
    {
        $term = $this->input->get('term', TRUE);
        $warehouse_id = $this->input->get('warehouse_id', TRUE);
        $customer_id = $this->input->get('customer_id', TRUE);

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
        $rows = $this->sales_model->getProductNames($sr, $warehouse_id);
        if ($rows) {
            foreach ($rows as $row) {
                $option = FALSE;
                $row->quantity = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty = 1;
                $row->discount = '0';
                $row->serial = '';
                $options = $this->sales_model->getProductOptions($row->id, $warehouse_id);
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
                $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                        if($pis){
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }
				if ($opt->price != 0) {
					if($customer_group->makeup_cost == 1){
						$row->price = $row->cost + (($row->cost * $customer_group->percent) / 100);
					}else{
						$row->price = $opt->price + (($opt->price * $customer_group->percent) / 100);
					}
                } else {
					if($customer_group->makeup_cost == 1){
						$row->price = $row->cost + (($row->cost * $customer_group->percent) / 100);
					}else{
						$row->price = $row->price + (($row->price * $customer_group->percent) / 100);
					}
                }

                $row->real_unit_price = $row->price;
                $combo_items = FALSE;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $warehouse_id);
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

	function suggests()
    {
        $term = $this->input->get('term', TRUE);
        $warehouse_id = $this->input->get('warehouse_id', TRUE);
        $customer_id = $this->input->get('customer_id', TRUE);

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
		//$customer_group = $this->site->getMakeupCostByCompanyID($customer_id);
        $rows = $this->sales_model->getProductNumber($sr, $warehouse_id);
        if ($rows) {
            foreach ($rows as $row) {
                $option = FALSE;
                $row->quantity = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty = 1;
                $row->discount = '0';
                $row->serial = '';
                $options = $this->sales_model->getProductOptions($row->id, $warehouse_id);
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
                $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                        if($pis){
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }
                if ($opt->price != 0) {
					if($customer_group->makeup_cost == 1){
						$row->price = $row->cost + (($row->cost * $customer_group->percent) / 100);
					}else{
						$row->price = $opt->price + (($opt->price * $customer_group->percent) / 100);
					}
                } else {
					if($customer_group->makeup_cost == 1){
						$row->price = $row->cost + (($row->cost * $customer_group->percent) / 100);
					}else{
						$row->price = $row->price + (($row->price * $customer_group->percent) / 100);
					}
                }
                $row->real_unit_price = $row->price;
                $combo_items = FALSE;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $warehouse_id);
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
	/* --------------------------------------------------------------------------------------------- */

    function Pcode()
    {
        $term = $this->input->get('term', TRUE);
        $warehouse_id = $this->input->get('warehouse_id', TRUE);
        $customer_id = $this->input->get('customer_id', TRUE);

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
        $rows = $this->sales_model->getProductCodes($sr, $warehouse_id);
        if ($rows) {
            foreach ($rows as $row) {
                $option = FALSE;
                $row->quantity = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty = 1;
                $row->discount = '0';
                $row->serial = '';
                $options = $this->sales_model->getProductOptions($row->id, $warehouse_id);
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
                $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                        if($pis){
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if($option->quantity > $option_quantity) {
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
                $combo_items = FALSE;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $warehouse_id);
                    }
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'code' => $row->code, 'label' => $row->name, 'cate_id' => $row->cate_name, 'detail' => $row->details, 'tax_rate' => $tax_rate, 'price' => $row->price);
                } else {
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'code' => $row->code, 'label' => $row->name, 'cate_id' => $row->cate_name, 'detail' => $row->details, 'tax_rate' => false, 'price' => $row->price);
                }
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

	function Pname()
    {
		$code = $this->input->get('code', TRUE);
        $term = $this->input->get('term', TRUE);
        $warehouse_id = $this->input->get('warehouse_id', TRUE);
        $customer_id = $this->input->get('customer_id', TRUE);

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
        $rows = $this->sales_model->getPname($sr, $warehouse_id, $code);
        if ($rows) {
            foreach ($rows as $row) {
                $option = FALSE;
                $row->quantity = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty = 1;
                $row->discount = '0';
                $row->serial = '';
                $options = $this->sales_model->getProductOptions($row->id, $warehouse_id);
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
                $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                        if($pis){
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if($option->quantity > $option_quantity) {
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
                $combo_items = FALSE;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $warehouse_id);
                    }
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'code' => $row->code, 'label' => $row->name, 'cate_id' => $row->cate_name, 'detail' => $row->details, 'tax_rate' => $tax_rate, 'price' => $row->price);
                } else {
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'code' => $row->code, 'label' => $row->name, 'cate_id' => $row->cate_name, 'detail' => $row->details, 'tax_rate' => false, 'price' => $row->price);
                }
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

	function Pdescription()
    {
		$code = $this->input->get('code', TRUE);
		$named = $this->input->get('named', TRUE);
        $term = $this->input->get('term', TRUE);
        $warehouse_id = $this->input->get('warehouse_id', TRUE);
        $customer_id = $this->input->get('customer_id', TRUE);

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
        $rows = $this->sales_model->getPdescription($sr, $warehouse_id, $named, $code);
        if ($rows) {
            foreach ($rows as $row) {
                $option = FALSE;
                $row->quantity = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty = 1;
                $row->discount = '0';
                $row->serial = '';
                $options = $this->sales_model->getProductOptions($row->id, $warehouse_id);
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
                $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                        if($pis){
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if($option->quantity > $option_quantity) {
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
                $combo_items = FALSE;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $warehouse_id);
                    }
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'code' => $row->code, 'label' => $row->name, 'cate_id' => $row->cate_name, 'detail' => $row->details, 'tax_rate' => $tax_rate, 'price' => $row->price);
                } else {
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'code' => $row->code, 'label' => $row->name, 'cate_id' => $row->cate_name, 'detail' => $row->details, 'tax_rate' => false, 'price' => $row->price);
                }
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

	function Pcategory()
    {
		//$code = $this->input->get('code', TRUE);
		//$named = $this->input->get('named', TRUE);
		//$descripted = $this->input->get('descripted', TRUE);
        $term = $this->input->get('term', TRUE);
        $warehouse_id = $this->input->get('warehouse_id', TRUE);
        $customer_id = $this->input->get('customer_id', TRUE);

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
        $rows = $this->sales_model->getPcategory($sr, $warehouse_id);
        if ($rows) {
            foreach ($rows as $row) {
                $option = FALSE;
                $row->quantity = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty = 1;
                $row->discount = '0';
                $row->serial = '';
                $options = $this->sales_model->getProductOptions($row->id, $warehouse_id);
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
                $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                        if($pis){
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if($option->quantity > $option_quantity) {
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
                $combo_items = FALSE;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $warehouse_id);
                    }
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'code' => $row->code, 'label' => $row->name, 'cate_id' => $row->category_id, 'detail' => $row->details, 'tax_rate' => $tax_rate, 'price' => $row->price);
                } else {
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'code' => $row->code, 'label' => $row->name, 'cate_id' => $row->cate_name, 'detail' => $row->details, 'tax_rate' => false, 'price' => $row->price);
                }
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

	function Pprice()
    {
		//$code = $this->input->get('code', TRUE);
		//$named = $this->input->get('named', TRUE);
		//$descript = $this->input->get('descript', TRUE);
		//$categories = $this->input->get('categories', TRUE);
        $term = $this->input->get('term', TRUE);
        $warehouse_id = $this->input->get('warehouse_id', TRUE);
        $customer_id = $this->input->get('customer_id', TRUE);

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
        $rows = $this->sales_model->getPprice($sr, $warehouse_id);
        if ($rows) {
            foreach ($rows as $row) {
                $option = FALSE;
                $row->quantity = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty = 1;
                $row->discount = '0';
                $row->serial = '';
                $options = $this->sales_model->getProductOptions($row->id, $warehouse_id);
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
                $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                if($pis){
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = $this->sales_model->getPurchasedItems($row->id, $warehouse_id, $row->option);
                        if($pis){
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if($option->quantity > $option_quantity) {
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
                $combo_items = FALSE;
                if ($row->tax_rate) {
                    $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                    if ($row->type == 'combo') {
                        $combo_items = $this->sales_model->getProductComboItems($row->id, $warehouse_id);
                    }
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'code' => $row->code, 'label' => $row->name, 'cate_id' => $row->cate_name, 'detail' => $row->details, 'tax_rate' => $tax_rate, 'price' => $row->price);
                } else {
                    $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'code' => $row->code, 'label' => $row->name, 'cate_id' => $row->cate_name, 'detail' => $row->details, 'tax_rate' => false, 'price' => $row->price);
                }
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }



	function fcode()
    {
        $term = $this->input->get('term', TRUE);

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
        $rows = $this->sales_model->getfcode($sr);
        if ($rows) {
            foreach ($rows as $row) {
                $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'code' => $row->name, 'label' => $row->description, 'floor' => $row->floor);
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

	function fdescription()
    {
        $term = $this->input->get('term', TRUE);

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
        $rows = $this->sales_model->getfdescription($sr);
        if ($rows) {
            foreach ($rows as $row) {
                $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'code' => $row->name, 'label' => $row->description, 'floor' => $row->floor);
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

	function ffloor()
    {
        $term = $this->input->get('term', TRUE);
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
        $rows = $this->sales_model->getffloor($sr);
        if ($rows) {
            foreach ($rows as $row) {
                $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'code' => $row->name, 'label' => $row->description, 'floor' => $row->floor);
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

    function floor_de()
    {
        $term = $this->input->get('term', TRUE);
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
        $rows = $this->sales_model->getfdescription($sr);
        if ($rows) {
            foreach ($rows as $row) {
                $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'code' => $row->name, 'label' => $row->description, 'floor' => $row->floor);
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

	function getfloor()
    {
        $term = $this->input->get('term', TRUE);
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
        $rows = $this->sales_model->getfloors($sr);
        if ($rows) {
            foreach ($rows as $row) {
                $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'code' => $row->name, 'label' => $row->description, 'floor' => $row->floor);
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

    /* ------------------------------------ Gift Cards ---------------------------------- */

    function gift_cards()
    {
        $this->erp->checkPermissions();

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('gift_cards')));
        $meta = array('page_title' => lang('gift_cards'), 'bc' => $bc);
        $this->page_construct('sales/gift_cards', $meta, $this->data);
    }

    function getGiftCards()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select($this->db->dbprefix('gift_cards') . ".id as id, card_no, value, balance, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ', " . $this->db->dbprefix('users') . ".last_name) as created_by, customer, expiry", FALSE)
            ->join('users', 'users.id=gift_cards.created_by', 'left')
            ->from("gift_cards")
            ->add_column("Actions", "<center><a href='" . site_url('sales/view_gift_card_history/$2') . "' class='tip' title='" . lang("view_gift_card_history") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-file-text-o\"></i></a> <a href='" . site_url('sales/view_gift_card/$1') . "' class='tip' title='" . lang("view_gift_card") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-eye\"></i></a> <a href='" . site_url('sales/edit_gift_card/$1') . "' class='tip' title='" . lang("edit_gift_card") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_gift_card") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete_gift_card/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id,card_no");
        //->unset_column('id');

        echo $this->datatables->generate();
    }

	function getLoans(){

        $this->erp->checkPermissions('index');

        $detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
        $payments_link = anchor('sales/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
        $email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('sales/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
        $pdf_link_backup = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));

        $pdf_link = anchor('sales/payment_schedule/0/1', '<i class="fa fa-file-pdf-o"></i> ' . lang('payment_schedule'));
        $delete_link = "<a href='#' class='loan' title='<b>" . lang("delete_sale") . "</b>' data-content=\"<p>"
					. lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete_loan/$1') . "'>"
					. lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
					. lang('delete_sale') . "</a>";
		$action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>
            <li>' . $payments_link . '</li>
            <li>' . $add_payment_link . '</li>
            <li>' . $edit_link . '</li>
            <li>' . $pdf_link . '</li>
            <li>' . $email_link . '</li>
        </ul>
    </div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

        $this->load->library('datatables');
		$this->datatables
			->select($this->db->dbprefix('loans').".sale_id, sales.date,
					 sales.reference_no as ref_no, sales.biller, sales.customer,
					 sales.sale_status, (SUM(".$this->db->dbprefix('loans').".payment) + ".$this->db->dbprefix('payments').".amount) as grand_total,
					 IF(".$this->db->dbprefix('loans').".type <> 0,(".$this->db->dbprefix('sales').".paid + (COALESCE(".$this->db->dbprefix('sales').".other_cur_paid / ".$this->db->dbprefix('sales').".other_cur_paid_rate,0))),SUM(IF(".$this->db->dbprefix('loans').".paid_amount > 0,".$this->db->dbprefix('loans').".principle,0))) as paid,
					 IF(".$this->db->dbprefix('loans').".type <> 0,ROUND(((SUM(".$this->db->dbprefix('loans').".payment) + ".$this->db->dbprefix('payments').".amount) - ((IF(".$this->db->dbprefix('loans').".type <> 0,".$this->db->dbprefix('sales').".paid, 0) + (COALESCE(".$this->db->dbprefix('sales').".other_cur_paid / ".$this->db->dbprefix('sales').".other_cur_paid_rate,0))))),3),ROUND(((SUM(".$this->db->dbprefix('loans').".payment) + ".$this->db->dbprefix('payments').".amount) - SUM(IF(".$this->db->dbprefix('loans').".paid_amount > 0,".$this->db->dbprefix('loans').".principle,0)))))  as balance,
					 IF(".$this->db->dbprefix('loans').".type = 0 AND ".$this->db->dbprefix('loans').".paid_amount < 0,'due',".$this->db->dbprefix('sales').".payment_status) as payment_status")
			->from('sales')
			->join('loans','sales.id=loans.sale_id','INNER')
			->join('payments','sales.id=payments.sale_id AND payments.paid_by="depreciation"','INNER')
			->group_by('loans.sale_id');
			if ($this->permission['sales-loan'] = ''){
				if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin) {
					if(!$this->session->userdata('edit_right') == 0){
						$this->datatables->where('sales.created_by', $this->session->userdata('user_id'));
					}
				} elseif ($this->Customer) {
					$this->datatables->where('customer_id', $this->session->userdata('user_id'));
				}
			}

        $this->datatables->add_column("Actions", '<div class="text-center"><div class="btn-group text-left">'  . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'. lang('actions') . ' <span class="caret"></span></button><ul class="dropdown-menu pull-right" role="menu"><li>' . $detail_link . '</li><li>' . $payments_link . '</li><li>' . $add_payment_link . '</li><li>' . $edit_link . '</li><li>' . $pdf_link . '</li><li>' . $email_link . '</li><li>' . $delete_link . '</li></ul></div></div>', $this->db->dbprefix('loans').".sale_id");
        echo $this->datatables->generate();

	}

    function view_gift_card($id = NULL)
    {
        $this->data['page_title'] =lang('gift_card');
        $gift_card = $this->site->getGiftCardByID($id);
        $this->data['gift_card'] = $this->site->getGiftCardByID($id);
        $this->data['customer'] = $this->site->getCompanyByID($gift_card->customer_id);
        $this->load->view($this->theme . 'sales/view_gift_card', $this->data);
    }

	function view_gift_card_history($no = NULL, $start = NULL, $end = NULL)
    {
        if(isset($_POST['start'])){
            $start = $_POST['start'];
        }
		if(isset($_POST['end'])){
            $end = $_POST['end'];
        }

		if (!$start) {
            $start = $this->db->escape(date('Y-m') . '-1');
            $start_date = date('Y-m') . '-1';
        } else {
            $start = $this->db->escape(urldecode($start_date));
        }
        if (!$end) {
            $end = $this->db->escape(date('Y-m-d H:i'));
            $end_date = date('Y-m-d H:i');
        } else {
            $end = $this->db->escape(urldecode($end_date));
        }

		if(isset($_GET['d']) != ""){
			$date = $_GET['d'];
            $this->data['date'] = $date;
		}

        $data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

		$this->data['start'] = urldecode($start_date);
        $this->data['end'] = urldecode($end_date);

		$this->data['card_no'] = $no;
		$this->data['page_title'] =lang('gift_card');
        //$gift_card = $this->site->getGiftCardByID($no);
        //$this->data['gift_card'] = $this->site->getGiftCardHistoryByNo($no);
        //$this->data['customer'] = $this->site->getCompanyByID($gift_card->customer_id);
        $this->load->view($this->theme . 'sales/view_gift_card_history', $this->data);
    }

	function getGiftCardsHistory()
    {
        if(isset($_GET['start'])){
            $start = $_GET['start'];
        }
		if(isset($_GET['end'])){
            $end = $_GET['end'];
        }
        if(isset($_GET['no'])){
            $no = $_GET['no'];
        }

		$this->load->library('datatables');
        $this->datatables
            ->select($this->db->dbprefix('payments') . ".date as date, card_no,". $this->db->dbprefix('payments') . ".reference_no as payment_ref, " . $this->db->dbprefix('sales') . ".reference_no as sale_ref, amount, type", FALSE)
			->from("payments")
            ->join('sales', 'payments.sale_id=sales.id', 'inner')
			->join('gift_cards', 'gift_cards.card_no=payments.cc_no', 'inner')
			->where($this->db->dbprefix('gift_cards') . '.card_no', $no);
			if (isset($start)) {
				$this->datatables->where($this->db->dbprefix('sales') . '.date', '2016-02-18 15:31:10');
			}
        //->unset_column('id');

        echo $this->datatables->generate();

    }

	function getMakeupCost($customer_id)
    {
        if ($dp = $this->site->getMakeupCostByCompanyID($customer_id)) {
                echo json_encode($dp);
        } else {
            echo json_encode(false);
        }
    }

	function validate_deposit($customer_id)
    {
        //$this->erp->checkPermissions();
        if ($dp = $this->site->getDepositByCompanyID($customer_id)) {
                echo json_encode($dp);
        } else {
            echo json_encode(false);
        }
    }

    function validate_gift_card($no)
    {
        //$this->erp->checkPermissions();
        if ($gc = $this->site->getGiftCardByNO($no)) {
            if ($gc->expiry) {
                if ($gc->expiry >= date('Y-m-d')) {
                    echo json_encode($gc);
                } else {
                    echo json_encode(false);
                }
            } else {
                echo json_encode($gc);
            }
        } else {
            echo json_encode(false);
        }
    }

    function add_gift_card()
    {
        $this->erp->checkPermissions();

        $this->form_validation->set_rules('card_no', lang("card_no"), 'trim|is_unique[gift_cards.card_no]|required');
        $this->form_validation->set_rules('value', lang("value"), 'required');

        if ($this->form_validation->run() == true) {
            $customer_details = $this->input->post('customer') ? $this->site->getCompanyByID($this->input->post('customer')) : NULL;
            $customer = $customer_details ? $customer_details->company : NULL;
            $data = array('card_no' => $this->input->post('card_no'),
                'value' => $this->input->post('value'),
                'customer_id' => $this->input->post('customer') ? $this->input->post('customer') : NULL,
                'customer' => $customer,
                'balance' => $this->input->post('value'),
                'expiry' => $this->input->post('expiry') ? $this->erp->fsd($this->input->post('expiry')) : NULL,
                'created_by' => $this->session->userdata('user_id')
            );
            $sa_data = array();
            $ca_data = array();
            if ($this->input->post('staff_points')) {
                $sa_points = $this->input->post('sa_points');
                $user = $this->site->getUser($this->input->post('user'));
                if ($user->award_points < $sa_points) {
                    $this->session->set_flashdata('error', lang("award_points_wrong"));
                    redirect("sales/gift_cards");
                }
                $sa_data = array('user' => $user->id, 'points' => ($user->award_points - $sa_points));
            } elseif ($customer_details && $this->input->post('use_points')) {
                $ca_points = $this->input->post('ca_points');
                if ($customer_details->award_points < $ca_points) {
                    $this->session->set_flashdata('error', lang("award_points_wrong"));
                    redirect("sales/gift_cards");
                }
                $ca_data = array('customer' => $customer->id, 'points' => ($customer_details->award_points - $ca_points));
            }
        } elseif ($this->input->post('add_gift_card')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("sales/gift_cards");
        }

        if ($this->form_validation->run() == true && $this->sales_model->addGiftCard($data, $ca_data, $sa_data)) {
            $this->session->set_flashdata('message', lang("gift_card_added"));
            redirect("sales/gift_cards");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['users'] = $this->sales_model->getStaff();
            $this->data['page_title'] = lang("new_gift_card");
            $this->load->view($this->theme . 'sales/add_gift_card', $this->data);
        }
    }

    function edit_gift_card($id = NULL)
    {
        $this->erp->checkPermissions(false, true);

        $this->form_validation->set_rules('card_no', lang("card_no"), 'trim|required');
        $gc_details = $this->site->getGiftCardByID($id);
        if ($this->input->post('card_no') != $gc_details->card_no) {
            $this->form_validation->set_rules('card_no', lang("card_no"), 'is_unique[gift_cards.card_no]');
        }
        $this->form_validation->set_rules('value', lang("value"), 'required');
        //$this->form_validation->set_rules('customer', lang("customer"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $gift_card = $this->site->getGiftCardByID($id);
            $customer_details = $this->input->post('customer') ? $this->site->getCompanyByID($this->input->post('customer')) : NULL;
            $customer = $customer_details ? $customer_details->company : NULL;
            $data = array('card_no' => $this->input->post('card_no'),
                'value' => $this->input->post('value'),
                'customer_id' => $this->input->post('customer') ? $this->input->post('customer') : NULL,
                'customer' => $customer,
                'balance' => ($this->input->post('value') - $gift_card->value) + $gift_card->balance,
                'expiry' => $this->input->post('expiry') ? $this->erp->fsd($this->input->post('expiry')) : NULL,
            );
        } elseif ($this->input->post('edit_gift_card')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("sales/gift_cards");
        }

        if ($this->form_validation->run() == true && $this->sales_model->updateGiftCard($id, $data)) {
            $this->session->set_flashdata('message', lang("gift_card_updated"));
            redirect("sales/gift_cards");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['gift_card'] = $this->site->getGiftCardByID($id);
            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'sales/edit_gift_card', $this->data);
        }
    }

    function sell_gift_card()
    {
        $this->erp->checkPermissions('gift_cards', true);
        $error = NULL;
        $gcData = $this->input->get('gcdata');
        if (empty($gcData[0])) {
            $error = lang("value") . " " . lang("is_required");
        }
        if (empty($gcData[1])) {
            $error = lang("card_no") . " " . lang("is_required");
        }


        $customer_details = (!empty($gcData[2])) ? $this->site->getCompanyByID($gcData[2]) : NULL;
        $customer = $customer_details ? $customer_details->company : NULL;
        $data = array('card_no' => $gcData[0],
            'value' => $gcData[1],
            'customer_id' => (!empty($gcData[2])) ? $gcData[2] : NULL,
            'customer' => $customer,
            'balance' => $gcData[1],
            'expiry' => (!empty($gcData[3])) ? $this->erp->fsd($gcData[3]) : NULL,
            'created_by' => $this->session->userdata('user_id')
        );

        if (!$error) {
            if ($this->sales_model->addGiftCard($data)) {
                echo json_encode(array('result' => 'success', 'message' => lang("gift_card_added")));
            }
        } else {
            echo json_encode(array('result' => 'failed', 'message' => $error));
        }

    }

    function delete_gift_card($id = NULL)
    {
        $this->erp->checkPermissions();

        if ($this->sales_model->deleteGiftCard($id)) {
            echo lang("gift_card_deleted");
        }
    }

    function gift_card_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->sales_model->deleteGiftCard($id);
                    }
                    $this->session->set_flashdata('message', lang("gift_cards_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('gift_cards'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('card_no'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('value'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('customer'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sc = $this->site->getGiftCardByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $sc->card_no);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sc->value);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sc->customer);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'gift_cards_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
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
                $this->session->set_flashdata('error', lang("no_gift_card_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function get_award_points($id = NULL)
    {
        $this->erp->checkPermissions('index');

        $row = $this->site->getUser($id);
        echo json_encode(array('sa_points' => $row->award_points));
    }

    /* -------------------------------------------------------------------------------------- */

    function sale_by_csv()
    {
        $this->erp->checkPermissions('csv');
        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', $this->lang->line("upload_file"), 'xss_clean');
        $this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));

        if ($this->form_validation->run() == true) {
            $quantity = "quantity";
            $product = "product";
            $unit_cost = "unit_cost";
            $tax_rate = "tax_rate";

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');

                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("sales/sale_by_csv");
                }
                $csv = $this->upload->file_name;
                $arrResult = array();
                $handle = fopen($this->digital_upload_path . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                //$keys = array('code', 'net_unit_price', 'quantity', 'variant', 'item_tax_rate', 'discount', 'serial');
                $keys = array('code', 'net_unit_price', 'quantity', 'customer', 'warehouse_code' ,'reference_no', 'date', 'biller_id', 'sale_status', 'payment_term', 'payment_status', 'shipping', 'order_discount', 'order_tax', 'opening_ar');
                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
                $rw = 2;
                $bak_ref = '';
                $old_reference = '';
                foreach ($final as $csv_pr) {
					if (!empty($csv_pr['code']) && !empty($csv_pr['net_unit_price']) && !empty($csv_pr['quantity'])) {
                        if ($product_details = $this->site->getProductByCode(trim($csv_pr['code']))) {
                            if($bak_ref == $csv_pr['reference_no']){
                                $old_reference = $csv_pr['reference_no'];
                            }else{
								$old_reference = '';
                                $total = 0;
                            }
                            $item_id = $product_details->id;
                            $item_type = $product_details->type;
                            $item_code = $product_details->code;
                            $item_name = $product_details->name;
                            $item_net_price = $csv_pr['net_unit_price'];
                            $item_quantity = $csv_pr['quantity'];
                            $item_tax_rate = $csv_pr['item_tax_rate'];
                            $item_discount = $csv_pr['discount'];

                            $date = strtr($csv_pr['date'], '/', '-');
                            $date = date('Y-m-d h:m:i', strtotime($date));
                            $reference = $csv_pr['reference_no'];
                            $sale_status = $csv_pr['sale_status'];
                            $payment_term = $csv_pr['payment_term'];
                            $payment_status = $csv_pr['payment_status'];
                            $shipping = $csv_pr['shipping'];
                            $order_discount = $csv_pr['order_discount'];
							$opening_ar = $csv_pr['opening_ar']?$csv_pr['opening_ar']:0;

							$warehouse = $this->site->getWarehouseByCode(trim($csv_pr['warehouse_code']));
							$warehouse_id = $warehouse->id;

                            $bak_ref = $csv_pr['reference_no'];

                            if($csv_pr['reference_no'] != $old_reference){
                                $customer_id = $csv_pr['customer'];
                                $biller_id = $csv_pr['biller_id'];
                                $customer_details = $this->site->getCustomerNameByID($customer_id);
                                $customer = $customer_details->company ? $customer_details->company : $customer_details->name;
                                $biller_details = $this->site->getBillerNameByID($biller_id);
                                $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
                            }

                            if (isset($item_code) && isset($item_net_price) && isset($item_quantity)) {
                                $product_details = $this->sales_model->getProductByCode($item_code);

                                if (isset($item_discount)) {
                                    $discount = $item_discount;
                                    $dpos = strpos($discount, $percentage);
                                    if ($dpos !== false) {
                                        $pds = explode("%", $discount);
                                        $pr_discount = (($this->erp->formatDecimal($item_net_price)) * (Float)($pds[0])) / 100;
                                    } else {
                                        $pr_discount = $this->erp->formatDecimal($discount);
                                    }
                                } else {
                                    $pr_discount = 0;
                                }
                                $item_net_price = $this->erp->formatDecimal($item_net_price - $pr_discount);
                                $pr_item_discount = $this->erp->formatDecimal($pr_discount * $item_quantity);
                                $product_discount += $pr_item_discount;

                                if (isset($item_tax_rate) && $item_tax_rate != 0) {

                                    if($tax_details = $this->sales_model->getTaxRateByName($item_tax_rate)) {
                                        $pr_tax = $tax_details->id;
                                        if ($tax_details->type == 1) {

                                            $item_tax = $this->erp->formatDecimal((($item_net_price) * $tax_details->rate) / 100);
                                            $tax = $tax_details->rate . "%";

                                        } elseif ($tax_details->type == 2) {
                                            $item_tax = $this->erp->formatDecimal($tax_details->rate);
                                            $tax = $tax_details->rate;
                                        }
                                        $pr_item_tax = $this->erp->formatDecimal($item_tax * $item_quantity);
                                    } else {
                                        $this->session->set_flashdata('error', lang("tax_not_found") . " ( " . $item_tax_rate . " ). " . lang("line_no") . " " . $rw);
                                        redirect($_SERVER["HTTP_REFERER"]);
                                    }
                                } elseif ($product_details->tax_rate) {
                                    $pr_tax = $product_details->tax_rate;
                                    $tax_details = $this->site->getTaxRateByID($pr_tax);
                                    if ($tax_details->type == 1) {
                                        $item_tax = $this->erp->formatDecimal((($item_net_price) * $tax_details->rate) / 100);
                                        $tax = $tax_details->rate . "%";
                                    } elseif ($tax_details->type == 2) {

                                        $item_tax = $this->erp->formatDecimal($tax_details->rate);
                                        $tax = $tax_details->rate;

                                    }
                                    $pr_item_tax = $this->erp->formatDecimal($item_tax * $item_quantity);

                                } else {
                                    $item_tax = 0;
                                    $pr_tax = 0;
                                    $pr_item_tax = 0;
                                    $tax = "";
                                }
                                $product_tax += $pr_item_tax;

                                $subtotal = (($item_net_price * $item_quantity) + $pr_item_tax);
                                $products[] = array(
                                    'product_id' => $item_id,
                                    'product_code' => $item_code,
                                    'product_name' => $item_name,
                                    'product_type' => $item_type,
                                    'net_unit_price' => $item_net_price,
                                    'unit_price' => $this->erp->formatDecimal($item_net_price + $item_tax),
                                    'quantity' => $item_quantity,
                                    'item_tax' => $pr_item_tax,
                                    'tax_rate_id' => $pr_tax,
                                    'tax' => $tax,
                                    'discount' => $item_discount,
                                    'item_discount' => $pr_item_discount,
                                    'subtotal' => $this->erp->formatDecimal($subtotal),
                                    'unit_price' => $this->erp->formatDecimal($item_net_price + $item_tax + $pr_discount),
									'warehouse_id' => $warehouse_id
                                );
                                $total += $item_net_price * $item_quantity;
                            }

                            if ($order_discount) {
								$order_discount_id = $order_discount;
								$opos = strpos($order_discount_id, $percentage);
								if ($opos !== false) {
									$ods = explode("%", $order_discount_id);
									$order_discount = $this->erp->formatDecimal((($total + $product_tax) * (Float)($ods[0])) / 100);
								} else {
									$order_discount = $this->erp->formatDecimal($order_discount_id);
								}
							} else {
								$order_discount_id = NULL;
							}
							$total_discount = $this->erp->formatDecimal($order_discount + $product_discount);

							if ($this->Settings->tax2) {
								$order_tax_id = $this->input->post('order_tax');
								if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
									if ($order_tax_details->type == 2) {
										$order_tax = $this->erp->formatDecimal($order_tax_details->rate);
									}
									if ($order_tax_details->type == 1) {
										$order_tax = $this->erp->formatDecimal((($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100);
									}
								}
							} else {
								$order_tax_id = NULL;
							}

							if($old_reference != $csv_pr['reference_no']){
								$total_tax = $this->erp->formatDecimal($product_tax + $order_tax);
								$grand_total = $this->erp->formatDecimal($this->erp->formatDecimal($total) + $total_tax + $this->erp->formatDecimal($shipping) - $order_discount);
								$data = array('date' => $date,
									'reference_no' => $reference?$reference:$this->site->getReference('so'),
									'customer_id' => $customer_id,
									'customer' => $customer,
									'biller_id' => $biller_id,
									'biller' => $biller,
									'note' => $note,
									'staff_note' => $staff_note,
									'total' => $this->erp->formatDecimal($total),
									'product_discount' => $this->erp->formatDecimal($product_discount),
									'order_discount_id' => $order_discount_id,
									'order_discount' => $order_discount,
									'warehouse_id' => $warehouse_id,
									'total_discount' => $total_discount,
									'product_tax' => $this->erp->formatDecimal($product_tax),
									'order_tax_id' => $order_tax_id,
									'order_tax' => $order_tax,
									'total_tax' => $total_tax,
									'shipping' => $this->erp->formatDecimal($shipping),
									'grand_total' => $grand_total,
									'total_items' => $total_items,
									'sale_status' => $sale_status,
									'payment_status' => $payment_status,
									'payment_term' => $payment_term,
									'due_date' => $due_date,
									'paid' => 0,
									'created_by' => $this->session->userdata('user_id'),
									'opening_ar' => $opening_ar
								);

								if ($_FILES['document']['size'] > 0) {
									$this->load->library('upload');
									$config['upload_path'] = $this->digital_upload_path;
									$config['allowed_types'] = $this->digital_file_types;
									$config['max_size'] = $this->allowed_file_size;
									$config['overwrite'] = FALSE;
									$config['encrypt_name'] = TRUE;
									$this->upload->initialize($config);
									if (!$this->upload->do_upload('document')) {
										$error = $this->upload->display_errors();
										$this->session->set_flashdata('error', $error);
										redirect($_SERVER["HTTP_REFERER"]);
									}
									$photo = $this->upload->file_name;
									$data['attachment'] = $photo;
								}
								$this->sales_model->addSaleImport($data, $products);
								unset($products);
								$products = array();
							} else {
								$this->sales_model->addSaleItemImport($products, $old_reference);
								unset($products);
								$products = array();
							}
                        } else {
                            $this->session->set_flashdata('error', $this->lang->line("pr_not_found") . " ( " . $csv_pr['code'] . " ). " . $this->lang->line("line_no") . " " . $rw);
                            redirect($_SERVER["HTTP_REFERER"]);
                        }
                        $rw++;
                    }
                }
            }
        }

        if ($this->form_validation->run() == true) {
            $this->session->set_userdata('remove_slls', 1);
            $this->session->set_flashdata('message', $this->lang->line("sale_added"));
            redirect("sales");
        } else {
            $data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['slnumber'] = $this->site->getReference('so');

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('add_sale_by_csv')));
            $meta = array('page_title' => lang('add_sale_by_csv'), 'bc' => $bc);
            $this->page_construct('sales/sale_by_csv', $meta, $this->data);
        }
    }

    /**********suspend**********/
    function suspends_calendar($warehouse_id = NULL){
        $this->load->model('reports_model');
        $this->data['warehouse_id'] = $warehouse_id;
        $this->data['users'] = $this->reports_model->getStaff();
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['billers'] = $this->site->getAllCompanies('biller');
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('suspend_calendar')));
        $meta = array('page_title' => lang('suspend_calendar'), 'bc' => $bc);
        $this->page_construct('sales/suspends_calendar', $meta, $this->data);
    }

    function getSuspends_calendar()
    {
        $this->erp->checkPermissions('index');

        $this->load->library('datatables');

        //$detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('Room_details'));
        $payments_link = anchor('customers/view/$1', '<i class="fa fa-money"></i> ' . lang('customer_details'), 'data-toggle="modal" data-target="#myModal"');
        //$add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('Document'), 'data-toggle="modal" data-target="#myModal"');

        /*
        $this->datatables
            ->select("(SELECT id FROM erp_suspended_bills sb WHERE sb.suspend_id = erp_suspended.id ) as id,floor,name,description, (SELECT date FROM erp_suspended_bills sb WHERE sb.suspend_id = erp_suspended.id ) as start_date, (SELECT end_date FROM erp_suspended_bills sb WHERE sb.suspend_id = erp_suspended.id ) as end_date, CASE WHEN status = 0 THEN 'Free' WHEN status = 1 THEN 'Booking' ELSE 'Busy' END AS status, (SELECT attachment FROM erp_suspended_bills sb LEFT JOIN erp_companies c ON c.id = sb.customer_id WHERE sb.suspend_id = erp_suspended.id ) as attachment")
            ->from("erp_suspended")
            */

        /*$this->datatables
            ->select("(SELECT MAX(id) FROM erp_suspended_bills sb WHERE sb.suspend_id = erp_suspended.id ) as id,floor,name,description, (SELECT MAX(customer) FROM erp_suspended_bills sb WHERE sb.suspend_id = erp_suspended.id ) as customer_name, (SELECT MAX(date) FROM erp_suspended_bills sb WHERE sb.suspend_id = erp_suspended.id ) as start_date, (SELECT MAX(end_date) FROM erp_suspended_bills sb WHERE sb.suspend_id = erp_suspended.id ) as end_date, CASE WHEN status = 0 THEN 'free' WHEN status = 1 THEN 'busy' ELSE 'busy' END AS status, (SELECT MAX(attachment) FROM erp_suspended_bills sb LEFT JOIN erp_companies c ON c.id = sb.customer_id WHERE sb.suspend_id = erp_suspended.id ) as attachment")
            ->from("erp_suspended")
            ->where('(SELECT date FROM erp_suspended_bills sb WHERE sb.suspend_id = erp_suspended.id ) IS NOT NULL', null, false)
            ->where('(SELECT end_date FROM erp_suspended_bills sb WHERE sb.suspend_id = erp_suspended.id ) IS NOT NULL', null, false)*/
		$this->datatables
            ->select("erp_suspended.id as id,floor,erp_suspended.name, (CASE WHEN erp_suspended.note != '' THEN erp_suspended.note ELSE (SELECT MAX(customer) FROM erp_suspended_bills sb WHERE sb.suspend_id = erp_suspended.id ) END) as customer_name, (SELECT total FROM erp_suspended_bills WHERE erp_suspended_bills.suspend_id = erp_suspended.id) as price, (SELECT deposit_amount FROM erp_companies WHERE erp_companies.id = erp_suspended_bills.customer_id) as deposite, description, erp_companies.start_date as start_date, erp_companies.end_date as end_date, (12 * (YEAR (erp_companies.end_date) - YEAR (erp_companies.start_date)) + (MONTH (erp_companies.end_date) - MONTH (erp_companies.start_date))) as term_year, CASE WHEN erp_suspended.status = 0 THEN 'free' WHEN erp_suspended.status = 1 THEN 'busy' WHEN erp_suspended.status = 2 THEN 'book' ELSE 'busy' END AS status, (SELECT MAX(attachment) FROM erp_suspended_bills sb LEFT JOIN erp_companies c ON c.id = sb.customer_id WHERE sb.suspend_id = erp_suspended.id ) as attachment")
			->join('erp_suspended_bills', 'erp_suspended.id = erp_suspended_bills.suspend_id', 'left')
			->join('erp_companies', 'erp_companies.id = erp_suspended_bills.customer_id', 'left')
            ->from("erp_suspended")
            //->where('(SELECT date FROM erp_suspended_bills sb WHERE sb.suspend_id = erp_suspended.id ) IS NOT NULL', null, false)
            //->where('(SELECT end_date FROM erp_suspended_bills sb WHERE sb.suspend_id = erp_suspended.id ) IS NOT NULL', null, false)
            ->add_column("Actions", '<center>
                    <div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $payments_link . '</li>
        </ul>
		</div>
                    </center>', "id");
        echo $this->datatables->generate();
    }

	function suppend_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {

                    $error = false;
                    foreach ($_POST['val'] as $id) {
                        if (!$this->settings_model->deleteSuppend($id)) {
                            $error = true;
                        }
                    }
                    if ($error) {
                        $this->session->set_flashdata('warning', lang('suppliers_x_deleted_have_purchases'));
                    } else {
                        $this->session->set_flashdata('message', $this->lang->line("account_deleted_successfully"));
                    }
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('suspend'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('floor'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('room|table name'));
					$this->excel->getActiveSheet()->SetCellValue('C1', lang('price'));
					$this->excel->getActiveSheet()->SetCellValue('D1', lang('deposite'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('description'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('customer_name'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('end_date'));
					$this->excel->getActiveSheet()->SetCellValue('I1', lang('term_of_rents_months'));
                    $this->excel->getActiveSheet()->SetCellValue('J1', lang('status'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $suspend = $this->site->getSuspendByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $suspend->floor);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $suspend->room_name);
						$this->excel->getActiveSheet()->SetCellValue('C' . $row, $suspend->price);
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $suspend->deposite);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $suspend->description);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $suspend->customer_name);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $suspend->start_date);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $suspend->end_date);
						$this->excel->getActiveSheet()->SetCellValue('I' . $row, $suspend->term_year);
                        $this->excel->getActiveSheet()->SetCellValue('J' . $row, $suspend->status);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'suspend_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
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
                $this->session->set_flashdata('error', $this->lang->line("no_supplier_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

	function listSaleRoom_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {
            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->sales_model->deleteSuspend($id);
                    }
                    $this->session->set_flashdata('message', lang("sales_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('sales'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('suspend'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('shop'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('sale_status'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('grand_total'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('paid'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('balance'));
					$this->excel->getActiveSheet()->SetCellValue('I1', lang('payment_status'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sale = $this->sales_model->getSuspendByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($sale->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sale->suspend);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sale->biller);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $sale->customer);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $sale->sale_status);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $sale->grand_total);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $sale->paid);
						$this->excel->getActiveSheet()->SetCellValue('H' . $row, $sale->balance);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $sale->payment_status);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'list_sales_room_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
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
                $this->session->set_flashdata('error', lang("no_sale_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

	function show_attachments($id){
		$this->data['file'] = $id;
		$this->load->view($this->theme . 'sales/show_attachment', $this->data);
	}

    function view_room_report($room_id = NULL, $year = NULL, $month = NULL, $pdf = NULL, $cal = 0)
    {

        $q_suspend = $this->db->query('SELECT * FROM erp_suspended_bills WHERE id = ? ', array($room_id))->row();

        $q_suspend_bill = $this->db->query('SELECT * FROM erp_suspended_bills WHERE id = ? ', array($room_id))->result();
        $total_ = 0;
        foreach($q_suspend_bill as $rows)
        {
            $total_ += $rows->total;
        }

        $this->data['total_']           = $total_;
        $this->data['room']             = $q_suspend->suspend_name;
        $this->data['suspended_bills']  = $q_suspend_bill;
        $this->data['error']            = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('reports'), 'page' => lang('reports')), array('link' => '#', 'page' => lang('View_Room_Report')));
        $meta = array('page_title' => lang('view_report'), 'bc' => $bc);
        $this->page_construct('reports/view_room_report', $meta, $this->data);
    }

    /**********suspend**********/
    function suspend($warehouse_id = NULL){
		$this->load->model('reports_model');
		$this->data['warehouse_id'] = $warehouse_id;
		$this->data['users'] = $this->reports_model->getStaff();
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['billers'] = $this->site->getAllCompanies('biller');
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('list_sales_suspend')));
        $meta = array('page_title' => lang('list_sales_suspend'), 'bc' => $bc);
        $this->page_construct('sales/suspends', $meta, $this->data);
	}

	function getSuspend($warehouse_id = NULL){

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

        if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }

        $add_payment_link = anchor('pos/index/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), '');
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
	        <ul class="dropdown-menu pull-right" role="menu">
	            <li>' . $add_payment_link . '</li>
	        </ul>
	    </div></div>';

        $this->load->library('datatables');
		if($warehouse_id){
			$this->datatables
                ->select($this->db->dbprefix('suspended_bills').".id as idd,".$this->db->dbprefix('sales').".date, ".$this->db->dbprefix('sales').".suspend_note as suspend, (select company from ".$this->db->dbprefix('companies')." where id= ".$this->db->dbprefix('sales').".biller_id) as biller,".$this->db->dbprefix('sales').".customer,
            	case when DATE(".$this->db->dbprefix('suspended_bills').".date)+ INTERVAL (SELECT show_suspend_bar-1 from ".$this->db->dbprefix('pos_settings')." where ".$this->db->dbprefix('pos_settings').".default_biller=".$this->db->dbprefix('suspended_bills').".biller_id) DAY <= DATE(SYSDATE()) then 'completed' else 'pending' end AS sale_status,
            	".$this->db->dbprefix('sales').".grand_total as grand_total, ".$this->db->dbprefix('sales').".paid as paid, (CASE WHEN ".$this->db->dbprefix('sales').".paid IS NULL THEN ".$this->db->dbprefix('sales').".grand_total ELSE ".$this->db->dbprefix('sales').".grand_total - ".$this->db->dbprefix('sales').".paid END) as balance, CASE WHEN ".$this->db->dbprefix('sales').".paid = 0 THEN 'pending' WHEN ".$this->db->dbprefix('sales').".grand_total = ".$this->db->dbprefix('sales').".paid THEN 'completed' WHEN ".$this->db->dbprefix('sales').".grand_total > ".$this->db->dbprefix('sales').".paid THEN 'partial' ELSE 'pending' END as payment_status")
				->join($this->db->dbprefix('sales'), $this->db->dbprefix('sales').'.suspend_note = '.$this->db->dbprefix('suspended_bills').'.suspend_name', 'left')
                ->from('suspended_bills')
				->where($this->db->dbprefix('sales').'.warehouse_id', $warehouse_id)
				->where('sales.suspend_note !=', " ");
		}else{
			$this->datatables
                ->select($this->db->dbprefix('suspended_bills').".id as idd,".$this->db->dbprefix('sales').".date, ".$this->db->dbprefix('sales').".suspend_note as suspend, (select company from ".$this->db->dbprefix('companies')." where id= ".$this->db->dbprefix('sales').".biller_id) as biller,".$this->db->dbprefix('sales').".customer,
            	case when DATE(".$this->db->dbprefix('suspended_bills').".date)+ INTERVAL (SELECT show_suspend_bar-1 from ".$this->db->dbprefix('pos_settings')." where ".$this->db->dbprefix('pos_settings').".default_biller=".$this->db->dbprefix('suspended_bills').".biller_id) DAY <= DATE(SYSDATE()) then 'completed' else 'pending' end AS sale_status,
            	".$this->db->dbprefix('sales').".grand_total as grand_total, ".$this->db->dbprefix('sales').".paid as paid, (CASE WHEN ".$this->db->dbprefix('sales').".paid IS NULL THEN ".$this->db->dbprefix('sales').".grand_total ELSE ".$this->db->dbprefix('sales').".grand_total - ".$this->db->dbprefix('sales').".paid END) as balance, CASE WHEN ".$this->db->dbprefix('sales').".paid = 0 THEN 'pending' WHEN ".$this->db->dbprefix('sales').".grand_total = ".$this->db->dbprefix('sales').".paid THEN 'completed' WHEN ".$this->db->dbprefix('sales').".grand_total > ".$this->db->dbprefix('sales').".paid THEN 'partial' ELSE 'pending' END as payment_status")
				->join($this->db->dbprefix('sales'), $this->db->dbprefix('sales').'.suspend_note = '.$this->db->dbprefix('suspended_bills').'.suspend_name', 'left')
				->where('sales.suspend_note !=', " ")
                ->from('suspended_bills');
		}
		if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }

		if ($user_query) {
			$this->datatables->where('suspended_bills.created_by', $user_query);
		}
		if ($reference_no) {
			$this->datatables->where('suspended_bills.suspend_name', $reference_no);
		}
		if ($biller) {
			$this->datatables->where('suspended_bills.biller_id', $biller);
		}
		if ($customer) {
			$this->datatables->where('suspended_bills.customer_id', $customer);
		}
		if ($warehouse) {
			$this->datatables->where('suspended_bills.warehouse_id', $warehouse);
		}

		if ($start_date || $end_date) {
			$this->datatables->where($this->db->dbprefix('suspended_bills').'.date >= "' . $start_date . '" AND ' . $this->db->dbprefix('suspended_bills').'.date < "' . $end_date . '"');
		}

        $this->datatables->add_column("Actions", $action, "idd");
        echo $this->datatables->generate();
	}

	function modal_view_suspend($id = NULL)
    {
        $this->erp->checkPermissions('index', TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $inv = $this->sales_model->getInvoiceByID($id);
		//$susin = $this->sales_model->getInvoiceByIDs($id);
		$susin = $this->sales_model->getInvoiceBySuspendIDs($id);
        if(isset($susin)){
            foreach($susin as $test){

            }
        }
		//$this->erp->print_arrays($susin);
        $this->erp->view_rights($inv->created_by, TRUE);

        //$detail= $this->sales_model->getAllSuspendDetail($id);
		$detail= $this->sales_model->getAllSuspendBySupendID($id);

        $this->data['customer'] = $this->site->getCompanyByID($detail->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($detail->biller_id);
        $this->data['created_by'] = $this->site->getUser($detail->created_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($detail->warehouse_id);
        $this->data['inv'] = $inv;
		$this->data['susin'] = $test;
        $this->data['detail'] =$detail;
        $this->data['suspend'] = $this->sales_model->getAllRoomDetail($detail->suspend_id);
        $return = $this->sales_model->getReturnBySID($id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllsuspendItem($id);

        $this->load->view($this->theme.'sales/suspend_modal_view', $this->data);
    }

    /***********suspend end*********/

	/*************Book**************/
	function modal_book($id = NULL)
    {
		if ($this->input->get('id')) {
			$id = $this->input->get('id');
		}
		$this->data['id'] = $id;
        $this->erp->checkPermissions('index', TRUE);
		$this->form_validation->set_rules('start_date', lang("start_date"), 'required');
		if ($this->form_validation->run() == true) {
			if($this->input->post('start_date')){
				$start_date = $this->erp->fld($this->input->post('start_date'));
			}else{
				$start_date = '';
			}
			if($this->input->post('end_date')){
				$end_date   = $this->erp->fld($this->input->post('end_date'));
			}else{
				$end_date   = '';
			}
			$SQLdata = array(
				'status'    => 2,
				'startdate' => $start_date,
				'enddate'   => $end_date,
				'customer_id' => $this->input->post('customer'),
				'note'      => $this->input->post('note')
			);
			//$this->erp->print_arrays($SQLdata);
			$room = $this->input->post('room_id');
			$this->sales_model->add_booking($room, $SQLdata);
			$this->session->set_flashdata('message', lang("suspend_booked"));
			redirect('sales/suspends_calendar');
		}else{
			$this->data['modal_js'] = $this->site->modal_js();
			$this->data['pos']      = $this->sales_model->getSetting();
			$this->load->view($this->theme.'sales/modal_book', $this->data);
		}
    }
	/*************Book**************/

	/**********suspend**********/
    function customers_alerts($warehouse_id = NULL){
		$this->load->model('reports_model');
		$this->data['warehouse_id'] = $warehouse_id;
		$this->data['users'] = $this->reports_model->getStaff();
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['billers'] = $this->site->getAllCompanies('biller');
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('list_customers_alerts')));
        $meta = array('page_title' => lang('list_customers_alerts'), 'bc' => $bc);
        $this->page_construct('sales/customers_alerts', $meta, $this->data);
	}

	function getCustomersAlerts($warehouse_id = NULL){

        $this->erp->checkPermissions('index');

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

        if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }

        $add_payment_link = anchor('pos/index/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), '');
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
	        <ul class="dropdown-menu pull-right" role="menu">
	            <li>' . $add_payment_link . '</li>
	        </ul>
	    </div></div>';

        $this->load->library('datatables');

			$this->datatables
					->select("id, id AS cus_no, name, gender, phone, email, address, end_date, COALESCE((SELECT paid FROM erp_sales WHERE customer_id = erp_companies.id  ORDER BY erp_sales.id DESC LIMIT 1 ), 0) AS balance")
					->from('companies');
					$this->datatables->where('CURDATE() >= DATE_SUB(end_date , INTERVAL (SELECT alert_day FROM ' . $this->db->dbprefix('settings').') DAY)');

		if ($customer) {
			$this->datatables->where('companies.id', $customer);
		}
		if ($start_date || $end_date) {
			$this->datatables->where($this->db->dbprefix('companies').'.start_date >= "' . $start_date . '" AND ' . $this->db->dbprefix('companies').'.end_date < "' . $end_date . '"');
		}

        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
	}

	//------------------- Sale export as Excel and pdf -----------------------
	function getSalesAll($pdf = NULL, $excel = NULL)
    {
		if($pdf || $excel){
			$this->erp->checkPermissions('pdf', 'sales');
		}else{
			$this->erp->checkPermissions('sales');
		}

        $sales = $this->input->get('sales') ? $this->input->get('sales') : NULL;

        if ($pdf || $excel) {

            $this->db
                ->select($this->db->dbprefix('sales') . ".date as dates, " . $this->db->dbprefix('sales') . ".reference_no as reference_nos,". $this->db->dbprefix('sales') .".biller as billers,
				" . $this->db->dbprefix('sales') . ".customer as customers, " . $this->db->dbprefix('sales') . ".sale_status as sale_statuses,
				" . $this->db->dbprefix('sales') . ".grand_total as grand_totals, " . $this->db->dbprefix('sales') . ".paid as paids,
				(" . $this->db->dbprefix('sales') . ". grand_total - paid) as balances,
				" . $this->db->dbprefix('sales') . ".payment_status as payment_statuses");
				//" . $this->db->dbprefix('warehouses') . ".name as wname");
            $this->db->from('sales');
            //$this->db->join('categories', 'categories.id=products.category_id', 'left');
            //$this->db->join('warehouses', 'warehouses.id=products.warehouse', 'left');
            $this->db->group_by("sales.id")->order_by('sales.date desc');
			$this->db->where('sales.reference_no NOT LIKE "SALE/POS%"');
            if ($sales) {
                $this->db->where('sales.id', $sales);
            }

            $q = $this->db->get();
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
            } else {
                $data = NULL;
            }

            if (!empty($data)) {

                $this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                $this->excel->getActiveSheet()->setTitle(lang('Sales List'));
				$this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                $this->excel->getActiveSheet()->SetCellValue('C1', lang('biller'));
                $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
                $this->excel->getActiveSheet()->SetCellValue('E1', lang('sale_status'));
                $this->excel->getActiveSheet()->SetCellValue('F1', lang('grand_total'));
                $this->excel->getActiveSheet()->SetCellValue('G1', lang('paid'));
                $this->excel->getActiveSheet()->SetCellValue('H1', lang('balance'));
				$this->excel->getActiveSheet()->SetCellValue('I1', lang('payment_status'));

                $row = 2;

                foreach ($data as $data_row) {
                    //$this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($data_row->id));
                    $this->excel->getActiveSheet()->SetCellValue('A' . $row, $data_row->dates);
					$this->excel->getActiveSheet()->SetCellValue('B' . $row, $data_row->reference_nos);
                    $this->excel->getActiveSheet()->SetCellValue('C' . $row, $data_row->billers);
                    $this->excel->getActiveSheet()->SetCellValue('D' . $row, $data_row->customers);
                    $this->excel->getActiveSheet()->SetCellValue('E' . $row, $data_row->sale_statuses);
                    $this->excel->getActiveSheet()->SetCellValue('F' . $row, lang($data_row->grand_totals));
					$this->excel->getActiveSheet()->SetCellValue('G' . $row, lang($data_row->paids));
					$this->excel->getActiveSheet()->SetCellValue('H' . $row, lang($data_row->balances));
					$this->excel->getActiveSheet()->SetCellValue('I' . $row, lang($data_row->payment_statuses));
                    //$this->excel->getActiveSheet()->SetCellValue('G' . $row, $data_row->wh);
                    $row++;
                }

                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
				$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $filename = lang('Sales List');
                $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                if ($pdf) {
                    $styleArray = array(
                        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                    );
                    $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                    $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                    require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
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
                    $objWriter->save('php://output');
                    exit();
                }
                if ($excel) {
                    ob_clean();
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                    header('Cache-Control: max-age=0');
                    ob_clean();
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    $objWriter->save('php://output');
                    exit();
                }

            }

            $this->session->set_flashdata('error', lang('nothing_found'));
            redirect($_SERVER["HTTP_REFERER"]);

        }
    }
	//-------------------End Sale export -------------------------------------

	//-------------------Loan export as Excel and PDF-------------------------
	function getLoansAll($pdf = NULL, $excel = NULL)
    {
        $this->erp->checkPermissions('Sales');

        $sales = $this->input->get('sales') ? $this->input->get('sales') : NULL;

        if ($pdf || $excel) {

			/*
			$this->datatables
			->select($this->db->dbprefix('loans').".reference_no, sales.date, sales.reference_no as ref_no, sales.biller, sales.customer, sales.sale_status,
			".$this->db->dbprefix('sales').".grand_total, sales.paid, (".$this->db->dbprefix('sales').".grand_total- ".$this->db->dbprefix('sales').".paid) as balance, sales.payment_status")
			->from('sales')
			->join('loans','sales.id=loans.reference_no','INNER')
			->group_by('loans.reference_no');
			*/

            $this->db
                ->select($this->db->dbprefix('sales') . ".date as dates, " . $this->db->dbprefix('sales') . ".reference_no as reference_nos,". $this->db->dbprefix('sales') .".biller as billers,
				" . $this->db->dbprefix('sales') . ".customer as customers, " . $this->db->dbprefix('sales') . ".sale_status as sale_statuses,
				" . $this->db->dbprefix('sales') . ".grand_total as grand_totals, (".$this->db->dbprefix('sales').".paid + (".$this->db->dbprefix('sales').".other_cur_paid / ".$this->db->dbprefix('sales').".other_cur_paid_rate)) as paids,
				(" . $this->db->dbprefix('sales') . ". grand_total - (".$this->db->dbprefix('sales').".paid + (".$this->db->dbprefix('sales').".other_cur_paid / ".$this->db->dbprefix('sales').".other_cur_paid_rate))) as balances,
				" . $this->db->dbprefix('sales') . ".payment_status as payment_statuses");
            $this->db->from('sales');
			$this->db->join('loans','sales.id=loans.reference_no','INNER');
            $this->db->group_by("loans.reference_no");
            if ($sales) {
                $this->db->where('sales.id', $sales);
            }

            $q = $this->db->get();
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
            } else {
                $data = NULL;
            }

            if (!empty($data)) {

                $this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                $this->excel->getActiveSheet()->setTitle(lang('Sales List'));
				$this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                $this->excel->getActiveSheet()->SetCellValue('C1', lang('biller'));
                $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
                $this->excel->getActiveSheet()->SetCellValue('E1', lang('sale_status'));
                $this->excel->getActiveSheet()->SetCellValue('F1', lang('grand_total'));
                $this->excel->getActiveSheet()->SetCellValue('G1', lang('paid'));
                $this->excel->getActiveSheet()->SetCellValue('H1', lang('balance'));
				$this->excel->getActiveSheet()->SetCellValue('I1', lang('payment_status'));

                $row = 2;

                foreach ($data as $data_row) {
                    //$this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($data_row->id));
                    $this->excel->getActiveSheet()->SetCellValue('A' . $row, $data_row->dates);
					$this->excel->getActiveSheet()->SetCellValue('B' . $row, $data_row->reference_nos);
                    $this->excel->getActiveSheet()->SetCellValue('C' . $row, $data_row->billers);
                    $this->excel->getActiveSheet()->SetCellValue('D' . $row, $data_row->customers);
                    $this->excel->getActiveSheet()->SetCellValue('E' . $row, $data_row->sale_statuses);
                    $this->excel->getActiveSheet()->SetCellValue('F' . $row, lang($data_row->grand_totals));
					$this->excel->getActiveSheet()->SetCellValue('G' . $row, lang($data_row->paids));
					$this->excel->getActiveSheet()->SetCellValue('H' . $row, lang($data_row->balances));
					$this->excel->getActiveSheet()->SetCellValue('I' . $row, lang($data_row->payment_statuses));
                    //$this->excel->getActiveSheet()->SetCellValue('G' . $row, $data_row->wh);
                    $row++;
                }

                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
				$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $filename = lang('Loans List');
                $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                if ($pdf) {
                    $styleArray = array(
                        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                    );
                    $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                    $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                    require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
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
                    $objWriter->save('php://output');
                    exit();
                }
                if ($excel) {
                    ob_clean();
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                    header('Cache-Control: max-age=0');
                    ob_clean();
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    $objWriter->save('php://output');
                    exit();
                }

            }

            $this->session->set_flashdata('error', lang('nothing_found'));
            redirect($_SERVER["HTTP_REFERER"]);

        }
    }
	//-------------------End Loan export--------------------------------------

	//------------------- Sale export as Excel and pdf -----------------------
	function getReturnsAll($pdf = NULL, $excel = NULL)
    {
        $this->erp->checkPermissions('Sales');

        $sales = $this->input->get('sales') ? $this->input->get('sales') : NULL;

        if ($pdf || $excel) {

            $this->db
                ->select($this->db->dbprefix('return_sales') . ".date as date, " . $this->db->dbprefix('return_sales') . ".reference_no as ref,
							erp_sales.reference_no AS `sal_ref`,
						" . $this->db->dbprefix('return_sales') . ".biller, " . $this->db->dbprefix('return_sales') . ".customer, " . $this->db->dbprefix('return_sales') . ".surcharge, " . $this->db->dbprefix('return_sales') . ".grand_total, " . $this->db->dbprefix('return_sales') . ".id as id")
                ->join('sales', 'sales.id=return_sales.sale_id', 'left')
                ->from('return_sales')
                ->group_by('return_sales.id');
            if ($sales) {
                $this->db->where('sales.id', $sales);
            }

            $q = $this->db->get();
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
            } else {
                $data = NULL;
            }

            if (!empty($data)) {

                $this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                $this->excel->getActiveSheet()->setTitle(lang('return_sales'));
				$this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                $this->excel->getActiveSheet()->SetCellValue('C1', lang('sale_reference'));
                $this->excel->getActiveSheet()->SetCellValue('D1', lang('shop'));
                $this->excel->getActiveSheet()->SetCellValue('E1', lang('customer'));
                $this->excel->getActiveSheet()->SetCellValue('F1', lang('surcharge'));
                $this->excel->getActiveSheet()->SetCellValue('G1', lang('grand_total'));

                $row = 2;
				$k=1;
                foreach ($data as $data_row) {
                    $this->excel->getActiveSheet()->SetCellValue('A' . $row, $data_row->date);
					$this->excel->getActiveSheet()->SetCellValue('B' . $row, $data_row->ref);
                    $this->excel->getActiveSheet()->SetCellValue('C' . $row, $data_row->sal_ref);
                    $this->excel->getActiveSheet()->SetCellValue('D' . $row, $data_row->biller);
                    $this->excel->getActiveSheet()->SetCellValue('E' . $row, $data_row->customer);
                    $this->excel->getActiveSheet()->SetCellValue('F' . $row, lang($data_row->surcharge));
					$this->excel->getActiveSheet()->SetCellValue('G' . $row, lang($data_row->grand_total));
                    $row++;
                }

                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
				$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $filename = lang('return_sales');
                $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                if ($pdf) {
                    $styleArray = array(
                        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                    );
                    $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                    $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                    require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
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
                    $objWriter->save('php://output');
                    exit();
                }
                if ($excel) {
                    ob_clean();
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                    header('Cache-Control: max-age=0');
                    ob_clean();
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    $objWriter->save('php://output');
                    exit();
                }

            }

            $this->session->set_flashdata('error', lang('nothing_found'));
            redirect($_SERVER["HTTP_REFERER"]);

        }
    }
	//-------------------End Sale export -------------------------------------

    public function getSaleReturnQuantity() {
        if ($this->input->get('sale_ref')) {
            $sale_ref = $this->input->get('sale_ref', TRUE);
        }
        if ($this->input->get('product_id')) {
            $product_id = $this->input->get('product_id', TRUE);
        }

        $quantity = $this->sales_model->getSaleItemByRefPIDReturn($sale_ref, $product_id);
        $quantity = $quantity->quantity;
        echo json_encode($quantity);
    }

	public function sale_edit(){
		$id   = $_REQUEST['id'];
		$qty  = $_REQUEST['qty'];
		$edit = $_REQUEST['edit_id'];
		$warehouse = $_REQUEST['ware'];
		$this->sales_model->saleEdit($id, $qty, $edit, $warehouse);
	}
	function export_payment_schedule_to_excel($pdf = NULL, $excel = 1,$sale_id= NULL){
		
		$this->data['mydata'] = $this->installment_payment_model->getPaymentListBysaleId($sale_id);
		$myinfo= $this->installment_payment_model->getPaymentListBysaleId($sale_id);
		$this->data['address'] = $this->site->getAddressToString($myinfo->country, $myinfo->state, $myinfo->district, $myinfo->sangkat, $myinfo->village);
		$address= $this->site->getAddressToString($myinfo->country, $myinfo->state, $myinfo->district, $myinfo->sangkat, $myinfo->village);
		$lease_amount=($myinfo->product_price)-($myinfo->ap);
		$c_date=$myinfo->cdate;
		$this->data['pts']=$this->erp->getPaymentSchedule($myinfo->sale_id, $lease_amount, $myinfo->rate_type, $myinfo->interest_rate, $myinfo->term, $myinfo->frequency, $c_date);
		
		
		$pts=$this->erp->getPaymentSchedule($myinfo->sale_id, $lease_amount, $myinfo->rate_type, $myinfo->interest_rate, $myinfo->term, $myinfo->frequency, $c_date);
	
		
		
		
        $sales = $this->input->get('sales') ? $this->input->get('sales') : NULL;

        if ($pdf || $excel) {
			$contract = $this->installment_payment_model->getContractByID($sale_id);
			$lease_amount = $contract->subtotal - $contract->advance_percentage_payment;
			$interest = $lease_amount*$contract->interest_rate;
			$principle = $lease_amount/$contract->term;
			$installment_amount = $interest + $principle;
			//$this->erp->print_arrays($contract->family_name);
            if (!empty($contract)) {

                $this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                $this->excel->getActiveSheet()->setTitle(lang('return_sales'));

                $this->excel->getActiveSheet()->SetCellValue('D3', lang('កាលវិភាកបង់ប្រាក់ប្រចំខែ Monthly Payment Schedule'));


				$this->excel->getActiveSheet()->SetCellValue('A7', lang('ឈ្មោះអតិថិជនៈ'));
				$this->excel->getActiveSheet()->SetCellValue('C7', (($contract->family_name_other && $contract->name_other)? ($contract->family_name_other.' '.$contract->name_other):($contract->family_name.' '.$contract->name)));

				$this->excel->getActiveSheet()->SetCellValue('A8', lang('អាសយដ្ឋាន'));
				$this->excel->getActiveSheet()->SetCellValue('C8', '#'.$myinfo->house_no.','.$myinfo->street.','.$address);
				$this->excel->getActiveSheet()->SetCellValue('C9', lang());

				$this->excel->getActiveSheet()->SetCellValue('F9', lang('Dealer:'));
				$this->excel->getActiveSheet()->SetCellValue('G9', $contract->biller);

				$phone=' '.$contract->phone1;
				if(($contract->phone2)!=''){
					$phone.=' / '.$contract->phone2;
				}
					
				$this->excel->getActiveSheet()->SetCellValue('A9', lang('លេខទូរស័ព្ទ'));
				$this->excel->getActiveSheet()->SetCellValue('C9', $phone);
				$this->excel->getActiveSheet()->SetCellValue('F10', lang('LID Number:'));
				$this->excel->getActiveSheet()->SetCellValue('G10', $contract->reference_no);

				//$this->excel->getActiveSheet()->SetCellValue('A11', lang('លេខកូដក្រុមហ៊ុន:'));
				//$this->excel->getActiveSheet()->SetCellValue('C11', lang('6777(បង់តាមវីង)'));
				$this->excel->getActiveSheet()->SetCellValue('A10', lang('លេសអតិថិជន:'));
				$this->excel->getActiveSheet()->SetCellValue('C10', $contract->id.' ');

				$this->excel->getActiveSheet()->SetCellValue('A15', lang('ប្រភេទ(Model):'));
				$this->excel->getActiveSheet()->SetCellValue('D15', $contract->assets);
				$this->excel->getActiveSheet()->SetCellValue('E15', lang('ទឹកប្រាក់ភតិសន្យា (Lease amount):'));
				$this->excel->getActiveSheet()->SetCellValue('H15', '$ '.$this->erp->formatMoney($lease_amount));

				$this->excel->getActiveSheet()->SetCellValue('A16', lang('ឆ្នាំផលិត (Year):'));
				$this->excel->getActiveSheet()->SetCellValue('D16', $contract->product_year);
				$this->excel->getActiveSheet()->SetCellValue('E16', lang('អត្រាការប្រាក់​(Interest rate):'));
				$this->excel->getActiveSheet()->SetCellValue('H16', ($contract->interest_rate*100).' %');

				$this->excel->getActiveSheet()->SetCellValue('A17', lang('កំលាំងម៉ាស៊ីន (CC):'));
				$this->excel->getActiveSheet()->SetCellValue('D17', $contract->engine);
				$this->excel->getActiveSheet()->SetCellValue('E17', lang('ចំនួនវិភាគសង(Number of payments):'));
				$this->excel->getActiveSheet()->SetCellValue('H17', $contract->term);

				$this->excel->getActiveSheet()->SetCellValue('A18', lang('តម្លៃម៉ូតូ (Price):'));
				$this->excel->getActiveSheet()->SetCellValue('D18', '$ '.$this->erp->formatMoney($contract->subtotal));
				$this->excel->getActiveSheet()->SetCellValue('E18', lang('ប្រាក់ត្រូវចង់ប្រចាំខែ (Monthly instalment amount):'));
				$this->excel->getActiveSheet()->SetCellValue('H18', '$ '.$this->erp->formatMoney($installment_amount));

				$this->excel->getActiveSheet()->SetCellValue('A19', lang('អត្រាប្រាក់បង់មុន(Advance payment rate):'));
				$this->excel->getActiveSheet()->SetCellValue('D19', ($contract->advance_percentage_payment*100).' %');
				$this->excel->getActiveSheet()->SetCellValue('E19', lang('ការិយបរិច្ឆេទកិច្ចសន្យា (Contract date):'));
				$this->excel->getActiveSheet()->SetCellValue('H19', $this->erp->hrsd($contract->contract_date));


				$this->excel->getActiveSheet()->SetCellValue('A20', lang('ប្រាក់បង់មុន(Advance payment ):'));
				$this->excel->getActiveSheet()->SetCellValue('D20', '$ '.$this->erp->formatMoney($contract->advance_payment));
				$this->excel->getActiveSheet()->SetCellValue('E20', lang('ការិយបរិច្ឆេទបង់ប្រាក់ដំបូង (First due date):'));
				$this->excel->getActiveSheet()->SetCellValue('H20', $this->erp->hrsd($contract->due_date));
        // Style ///
        $smallfont_green = array(
        'font'  => array(
          'bold'  => false,
          'color' => array('rgb' => '2ecc71'),
          'size'  => 8,
          'name'  => ''
        ));
        $smallfont_blue = array(
        'font'  => array(
          'bold'  => false,
          'color' => array('rgb' => '3498db'),
          'size'  => 8,
          'name'  => ''
        ));
        $smallfont_black= array(
        'font'  => array(
          'bold'  => false,
          'color' => array('rgb' => '000000'),
          'size'  => 8,
          'name'  => ''
        ));
        $color_white= array(
        'font'  => array(
          'bold'  => false,
          'color' => array('rgb' => 'FFFFFF'),
          'name'  => ''
        ));
        $smallfont= array(
        'font'  => array(
          'size'  => 9,
        ));

		
		
		
	
		$data = $this->installment_payment_model->getSheduleBySaleID($sale_id);
		$services = $this->installment_payment_model->getServiceByID($sale_id);
		
		$rownum=23;
		$lr=1;
        $k=0;
        foreach ($pts as $dt) {
		if($rownum == 23){
          $this->excel->getActiveSheet()->SetCellValue('A'.$rownum, lang('លរ'));
          $this->excel->getActiveSheet()->SetCellValue('B'.$rownum, lang('កាលបរិច្ឆេទ'));
          $this->excel->getActiveSheet()->SetCellValue('C'.$rownum, lang('សរុបប្រាក់ត្រូវបង់'));
		  $c = 'C';
		  //foreach($services as $service) {
          //$this->excel->getActiveSheet()->SetCellValue($c++.''.$rownum, lang($service->description_other));
		  //}
          $this->excel->getActiveSheet()->SetCellValue('D'.$rownum, lang('ប្រាក់ផ្ទេកម្មសិទ្ធិ'));
          $this->excel->getActiveSheet()->SetCellValue('E'.$rownum, lang('ប្រាក់សេវាកម្ម'));
          $this->excel->getActiveSheet()->SetCellValue('F'.$rownum, lang('ប្រាក់ធានារ៉ាប់រង'));
		  
          $this->excel->getActiveSheet()->SetCellValue('G'.$rownum, lang('ប្រាក់ដើមត្រូវបង់'));
          $this->excel->getActiveSheet()->SetCellValue('H'.$rownum, lang('ការប្រាក់ត្រូវបង់'));
          $this->excel->getActiveSheet()->SetCellValue('I'.$rownum, lang('សមតុល្យប្រាក់ដើមនៅសល់'));
          // Color background

            $this->excel->getActiveSheet()->getStyle('A'.$rownum)
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('27ae60');
            $this->excel->getActiveSheet()->getStyle('A'.$rownum)->applyFromArray($color_white);
            $this->excel->getActiveSheet()->getStyle('B'.$rownum)
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('27ae60');
            $this->excel->getActiveSheet()->getStyle('B'.$rownum)->applyFromArray($color_white);
            $this->excel->getActiveSheet()->getStyle('C'.$rownum)
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('27ae60');
            $this->excel->getActiveSheet()->getStyle('C'.$rownum)->applyFromArray($color_white);
            $this->excel->getActiveSheet()->getStyle('D'.$rownum)
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('27ae60');
            $this->excel->getActiveSheet()->getStyle('D'.$rownum)->applyFromArray($color_white);
            $this->excel->getActiveSheet()->getStyle('E'.$rownum)
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('27ae60');
            $this->excel->getActiveSheet()->getStyle('E'.$rownum)->applyFromArray($color_white);
            $this->excel->getActiveSheet()->getStyle('F'.$rownum)
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('27ae60');
            $this->excel->getActiveSheet()->getStyle('F'.$rownum)->applyFromArray($color_white);
            $this->excel->getActiveSheet()->getStyle('G'.$rownum)
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('27ae60');
            $this->excel->getActiveSheet()->getStyle('G'.$rownum)->applyFromArray($color_white);
            $this->excel->getActiveSheet()->getStyle('H'.$rownum)
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('27ae60');
            $this->excel->getActiveSheet()->getStyle('H'.$rownum)->applyFromArray($color_white);
            $this->excel->getActiveSheet()->getStyle('I'.$rownum)
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('27ae60');
            $this->excel->getActiveSheet()->getStyle('I'.$rownum)->applyFromArray($color_white);

			$rownum++;
          }
		  
		  
		  	$princ=$this->erp->formatMoney($dt["principle"]);
				$interest=$this->erp->formatMoney($dt["interest"]);
				$total=$princ+$interest;
				$balance_moeny=$this->erp->formatMoney($dt["balance"]);
				if($balance_moeny<=0){
					$balance_moeny=$this->erp->formatMoney(0);
				}
		  
				$this->excel->getActiveSheet()->SetCellValue('A'.$rownum.'', $lr);
  				$this->excel->getActiveSheet()->SetCellValue('B'.$rownum.'', $this->erp->hrsd($dt["dateline"]));
  				$this->excel->getActiveSheet()->SetCellValue('C'.$rownum.'', $total);
  				$this->excel->getActiveSheet()->SetCellValue('D'.$rownum.'', lang('N/A'));
  				$this->excel->getActiveSheet()->SetCellValue('E'.$rownum.'', lang('N/A'));
  				$this->excel->getActiveSheet()->SetCellValue('F'.$rownum.'', lang('N/A'));
  				$this->excel->getActiveSheet()->SetCellValue('G'.$rownum.'', $this->erp->formatMoney($dt["principle"]));
  				$this->excel->getActiveSheet()->SetCellValue('H'.$rownum.'', $this->erp->formatMoney($dt["interest"]));
  				$this->excel->getActiveSheet()->SetCellValue('I'.$rownum.'', $balance_moeny);
					$lr++;
					$k++;
  				//A1
  				$gdImage = imagecreatefromjpeg(base_url().'assets/uploads/other/gl_logo.jpg');
  				$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
  				$objDrawing->setName('Sample image');
  				$objDrawing->setDescription('Sample image');
  				$objDrawing->setImageResource($gdImage);
  				$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
  				$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
  				$objDrawing->setHeight(70);
  				$objDrawing->setCoordinates('A3');
  				$objDrawing->setWorksheet($this->excel->getActiveSheet());

  				$this->excel->getActiveSheet()->getStyle('A'.$rownum.'')->getAlignment()->applyFromArray(
  					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
  				);
  						$this->excel->getActiveSheet()->getStyle('B'.$rownum.'')->getAlignment()->applyFromArray(
  					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
  				);
  						$this->excel->getActiveSheet()->getStyle('C'.$rownum.'')->getAlignment()->applyFromArray(
  					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
  				);
  						$this->excel->getActiveSheet()->getStyle('D'.$rownum.'')->getAlignment()->applyFromArray(
  					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
  				);		$this->excel->getActiveSheet()->getStyle('E'.$rownum.'')->getAlignment()->applyFromArray(
  					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
  				);$this->excel->getActiveSheet()->getStyle('F'.$rownum.'')->getAlignment()->applyFromArray(
  					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
  				);
  				$this->excel->getActiveSheet()->getStyle('G'.$rownum.'')->getAlignment()->applyFromArray(
  					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
  				);
  				$this->excel->getActiveSheet()->getStyle('H'.$rownum.'')->getAlignment()->applyFromArray(
  					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
  				);
  				$this->excel->getActiveSheet()->getStyle('I'.$rownum.'')->getAlignment()->applyFromArray(
  					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
  				);
          $k++;
          $rownum++;
        }



				$rownum=$rownum+1;

				$this->excel->getActiveSheet()->getStyle('A'.$rownum.'')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('27ae60');
				$this->excel->getActiveSheet()->getStyle('B'.$rownum.'')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('27ae60');

				$this->excel->getActiveSheet()->getStyle('C'.$rownum.'')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('27ae60');

				$this->excel->getActiveSheet()->getStyle('D'.$rownum.'')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('27ae60');

				$this->excel->getActiveSheet()->getStyle('E'.$rownum.'')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('27ae60');

				$this->excel->getActiveSheet()->getStyle('F'.$rownum.'')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('27ae60');

				$this->excel->getActiveSheet()->getStyle('G'.$rownum.'')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('27ae60');

				$this->excel->getActiveSheet()->getStyle('H'.$rownum.'')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('27ae60');

				$this->excel->getActiveSheet()->getStyle('I'.$rownum.'')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('27ae60');

				$smallfont_plus= array(
				'font'  => array(
					'bold'  => false,
					'color' => array('rgb' => '000000'),
					'size'  => 10,
					'name'  => ''
				));
				$smallfont_last= array(
				'font'  => array(
					'bold'  => false,
					'color' => array('rgb' => '000000'),
					'size'  => 8,
					'name'  => ''
				));
				$rownum=$rownum+2;
				$this->excel->getActiveSheet()->getStyle('A'.$rownum.':G'.$rownum.'')->applyFromArray($smallfont_plus);

				$this->excel->getActiveSheet()->SetCellValue('B'.$rownum.'', lang('GL FINANCE PLC'));
				$this->excel->getActiveSheet()->SetCellValue('G'.$rownum.'', lang('ស្នាមមេដៃស្តាំរបស់អតិថិជន/Lessee'));

				$rownum=$rownum+7;
				$this->excel->getActiveSheet()->getStyle('A'.$rownum.':G'.$rownum.'')->applyFromArray($smallfont_plus);

				$this->excel->getActiveSheet()->SetCellValue('B'.$rownum.'', lang('ឈ្មោះ/Name....................'));
				$this->excel->getActiveSheet()->SetCellValue('G'.$rownum.'', lang('ឈ្មោះ/Name រ៉ាត សុម៉ៅ'));
				$rownum=$rownum+1;
				$this->excel->getActiveSheet()->getStyle('A'.$rownum.':G'.$rownum.'')->applyFromArray($smallfont_plus);

				$this->excel->getActiveSheet()->SetCellValue('B'.$rownum.'', lang('ថ្ងៃខែឆ្នាំ/Date 29/07/2016'));
				$this->excel->getActiveSheet()->SetCellValue('G'.$rownum.'', lang('ថ្ងៃខែឆ្នាំ/Date 29/07/2016'));
				$rownum=$rownum+1;
				$this->excel->getActiveSheet()->getStyle('A'.$rownum.':I'.$rownum.'')->applyFromArray($smallfont_last);

				$this->excel->getActiveSheet()->SetCellValue('A'.$rownum.'', lang('សម្គាល់ :ថ្លៃសេវាបន្ថែម 0.55 ដុល្លារ នឹងបូកបញ្ចូលរាល់ការបង់ប្រាក់ប្រចាំខែនៅតាមបញ្ជីរដែលជាដៃគូសហការទទួល ប្រាក់របស់ ជីអិល ហ្វាយនែន ភីអិលស៊ី។ '));



				$this->excel->getActiveSheet()->getStyle('B17')->applyFromArray($smallfont)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
				);
				$border_style= array('borders' => array('allborders' => array('style' =>
					PHPExcel_Style_Border::BORDER_DASHED ,'color' => array('argb' => '000000'),)));
				$this->excel->getActiveSheet()->getStyle('D17')->applyFromArray($border_style)->applyFromArray($smallfont_blue)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
				);
				$this->excel->getActiveSheet()->getStyle('F17')->applyFromArray($smallfont_black)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
				);
				$this->excel->getActiveSheet()->getStyle('A20')->applyFromArray($smallfont_black)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
				);
				$border_style= array('borders' => array('allborders' => array('style' =>
					PHPExcel_Style_Border::BORDER_DASHED ,'color' => array('argb' => '000000'),)));
				$this->excel->getActiveSheet()->getStyle('H17')->applyFromArray($border_style)->applyFromArray($smallfont_blue)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
				);

				//big border
				$big_border_style_top= array('borders' => array('top' => array('style' =>
					PHPExcel_Style_Border::BORDER_THICK ,'color' => array('argb' => '000000'),)));
				$this->excel->getActiveSheet()->getStyle('A14:I14')->applyFromArray($big_border_style_top);
				$this->excel->getActiveSheet()->getStyle('A22:I22')->applyFromArray($big_border_style_top);
				$big_border_style_right= array('borders' => array('right' => array('style' =>
					PHPExcel_Style_Border::BORDER_THICK ,'color' => array('argb' => '000000'),)));
					$this->excel->getActiveSheet()->getStyle('I14:I21')->applyFromArray($big_border_style_right);

				// end big border
				// center in box
				// end center in box

				$this->excel->getActiveSheet()->getStyle('A15')->applyFromArray($smallfont);
				$border_style= array('borders' => array('allborders' => array('style' =>
					PHPExcel_Style_Border::BORDER_DASHED ,'color' => array('argb' => '000000'),)));
				$this->excel->getActiveSheet()->getStyle('D15')->applyFromArray($border_style)->applyFromArray($smallfont_blue)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
				);
				$this->excel->getActiveSheet()->getStyle('D19')->applyFromArray($border_style)->applyFromArray($smallfont_blue)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
				);
				$this->excel->getActiveSheet()->getStyle('H19')->applyFromArray($border_style)->applyFromArray($smallfont_blue)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
				);
				$this->excel->getActiveSheet()->getStyle('H20')->applyFromArray($border_style)->applyFromArray($smallfont_blue)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
				);
				$this->excel->getActiveSheet()->getStyle('F15')->applyFromArray($smallfont);
				$border_style= array('borders' => array('allborders' => array('style' =>
					PHPExcel_Style_Border::BORDER_DASHED ,'color' => array('argb' => '000000'),)));
				$this->excel->getActiveSheet()->getStyle('H15')->applyFromArray($border_style)->applyFromArray($smallfont_green)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
				);
				$this->excel->getActiveSheet()->getStyle('D18')->applyFromArray($border_style)->applyFromArray($smallfont_green)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
				);
				$this->excel->getActiveSheet()->getStyle('D20')->applyFromArray($border_style)->applyFromArray($smallfont_green)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
				);
				$this->excel->getActiveSheet()->getStyle('H18')->applyFromArray($border_style)->applyFromArray($smallfont_green)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
				);
				$this->excel->getActiveSheet()->getStyle('A16')->applyFromArray($smallfont)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
				);
				$border_style= array('borders' => array('allborders' => array('style' =>
					PHPExcel_Style_Border::BORDER_DASHED ,'color' => array('argb' => '000000'),)));
				$this->excel->getActiveSheet()->getStyle('D16')->applyFromArray($border_style)->applyFromArray($smallfont_blue)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
				);
				$this->excel->getActiveSheet()->getStyle('F16')->applyFromArray($smallfont)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
				);
				$border_style= array('borders' => array('allborders' => array('style' =>
					PHPExcel_Style_Border::BORDER_DASHED ,'color' => array('argb' => '000000'),)));
				$this->excel->getActiveSheet()->getStyle('H16')->applyFromArray($border_style)->applyFromArray($smallfont_blue)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
				);
				$this->excel->getActiveSheet()->getStyle('A15')->applyFromArray($smallfont);
				$border_style= array('borders' => array('allborders' => array('style' =>
					PHPExcel_Style_Border::BORDER_DASHED ,'color' => array('argb' => '000000'),)));

				$this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);

				$this->excel->getActiveSheet()->mergeCells("E20:G20");
				$this->excel->getActiveSheet()->mergeCells("E19:G19");
				$this->excel->getActiveSheet()->mergeCells("E18:G18");
				$this->excel->getActiveSheet()->mergeCells("E17:G17");
				$this->excel->getActiveSheet()->mergeCells("E16:G16");
				$this->excel->getActiveSheet()->mergeCells("E15:G15");

				$this->excel->getActiveSheet()->mergeCells("A15:C15");
				$this->excel->getActiveSheet()->mergeCells("A16:C16");
				$this->excel->getActiveSheet()->mergeCells("A17:C17");
				$this->excel->getActiveSheet()->mergeCells("A18:C18");
				$this->excel->getActiveSheet()->mergeCells("A19:C19");
				$this->excel->getActiveSheet()->mergeCells("A20:C20");

				$this->excel->getActiveSheet()->getStyle('A15')->getAlignment()->applyFromArray(
							array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
						);
				$this->excel->getActiveSheet()->getStyle('A17')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
);
				$this->excel->getActiveSheet()->getStyle('A18')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
);
				$this->excel->getActiveSheet()->getStyle('C7')->getFont()->getColor()->setRGB('3498db');
				$this->excel->getActiveSheet()->getStyle('C8')->getFont()->getColor()->setRGB('3498db');
				$this->excel->getActiveSheet()->getStyle('C9')->getFont()->getColor()->setRGB('3498db');
				$this->excel->getActiveSheet()->getStyle('C10')->getFont()->getColor()->setRGB('3498db');
				$this->excel->getActiveSheet()->getStyle('C11')->getFont()->getColor()->setRGB('3498db');


				$this->excel->getActiveSheet()->getStyle('G9')->getFont()->getColor()->setRGB('3498db');
				$this->excel->getActiveSheet()->getStyle('G10')->getFont()->getColor()->setRGB('3498db');
				$this->excel->getActiveSheet()->getStyle('G11')->getFont()->getColor()->setRGB('3498db');




                    for($f=7;$f<=$rownum-12;$f++){
						$this->excel->getActiveSheet()->getStyle('A'.$f.'')->applyFromArray($smallfont);
						$this->excel->getActiveSheet()->getStyle('B'.$f.'')->applyFromArray($smallfont);
						$this->excel->getActiveSheet()->getStyle('C'.$f.'')->applyFromArray($smallfont);
						$this->excel->getActiveSheet()->getStyle('D'.$f.'')->applyFromArray($smallfont);
						$this->excel->getActiveSheet()->getStyle('E'.$f.'')->applyFromArray($smallfont);
						$this->excel->getActiveSheet()->getStyle('F'.$f.'')->applyFromArray($smallfont);
						$this->excel->getActiveSheet()->getStyle('G'.$f.'')->applyFromArray($smallfont);
						$this->excel->getActiveSheet()->getStyle('H'.$f.'')->applyFromArray($smallfont);
						$this->excel->getActiveSheet()->getStyle('I'.$f.'')->applyFromArray($smallfont);

					}

				$this->excel->getActiveSheet()->getStyle('E15:E20')->applyFromArray($smallfont)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
				);

				// logo

                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
				$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
				$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
                $filename = lang('payment_schedule');
                $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                if ($pdf) {
                    $styleArray = array(
                        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                    );
                    $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                    $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                    require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
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
                    $objWriter->save('php://output');
                    exit();
                }
                if ($excel) {
                    ob_clean();
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                    header('Cache-Control: max-age=0');
                    ob_clean();
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    $objWriter->save('php://output');
                    exit();
                }

            }

            $this->session->set_flashdata('error', lang('nothing_found'));
            redirect($_SERVER["HTTP_REFERER"]);

        }
	}
	/*function payment_schedule($pdf = NULL, $excel = 1,$sale_id= NULL,$id = NULL)
    {
		$this->erp->checkPermissions('Sales');	
		$check_mfi=$this->installment_payment_model->checkMFI($sale_id);
		
		if($check_mfi->mfi==1){			
			$this->data['setting'] = $this->site->get_setting();
			$sale = $this->installment_payment_model->getMfiBySaleId($sale_id);
			$customer_id = $sale->customer_id;
			$created_by = $sale->created_by;
			$customer = $this->installment_payment_model->getMfiCustomer($customer_id);
			$sale_item = $this->installment_payment_model->getSaleItemBySaleID($sale->id);
			$loans = $this->installment_payment_model->getLoanBySaleId($sale_id);
			$dollar = $this->installment_payment_model->getDollarRate();
			$countloans = $this->installment_payment_model->getLoanBySaleId($sale_id);
			$this->data['sale'] = $sale;
			$this->data['creator'] = $this->installment_payment_model->getMfiCreator($created_by);
			$this->data['stcurrency'] = $this->installment_payment_model->getSettingCurrncy();
			$this->data['cuscurrency'] = $this->installment_payment_model->getCustomerCurrency($customer_id);
			$this->data['customer'] = $customer;
			$this->data['loan'] = $loans;
			$this->data['dollar'] = $dollar;
			$this->data['countloans'] = $countloans;
			$this->data['sale_id'] = $sale_id;
			$this->data['sale_item'] = $sale_item;
			$this->data['collateraltype'] = $this->installment_payment_model->getCollateralBySale($sale_id);
			$this->data['total_service'] = $this->installment_payment_model->getservicefee($sale_id);
			$this->data['services'] = $this->installment_payment_model->getServices($sale_id);
			$this->data['currency'] = $this->site->getCurrencyByCode($sale_item->currency_code);
			$this->data['tloans'] = $this->site->getAllTotalLoanBySaleID($sale->id);
			$this->data['address'] = $this->site->getAddressToString($customer->country, $customer->state, $customer->district, $customer->sangkat, $customer->village,'KH');
			$this->data['setting']= $this->site->get_setting();
			$this->data['modal_js'] = $this->site->modal_js();
			$this->load->view($this->theme.'installment_payment/cash_payment_schedule_view',$this->data);			
		
		}else{
			$this->data['mydata'] = $this->installment_payment_model->getPaymentListBysaleId($sale_id);
			$myinfo= $this->installment_payment_model->getPaymentListBysaleId($sale_id);
			//echo $myinfo->mfi.'________________________';
			if($myinfo->mfi==1){
				echo 'MFI-----------';
			}else{
				$this->data['address'] = $this->site->getAddressToString($myinfo->country, $myinfo->state, $myinfo->district, $myinfo->sangkat, $myinfo->village);
				$address= $this->site->getAddressToString($myinfo->country, $myinfo->state, $myinfo->district, $myinfo->sangkat, $myinfo->village);
				$lease_amount=($myinfo->product_price)-($myinfo->ap);
				$c_date=$myinfo->cdate;
				$this->data['pts']=$this->erp->getPaymentSchedule($myinfo->sale_id, $lease_amount, $myinfo->rate_type, $myinfo->interest_rate, $myinfo->term, $myinfo->frequency, $c_date);
				$this->data['sale_id']=$sale_id;
				$pts=$this->erp->getPaymentSchedule($myinfo->sale_id, $lease_amount, $myinfo->rate_type, $myinfo->interest_rate, $myinfo->term, $myinfo->frequency, $c_date);
				$this->erp->checkPermissions();
				$this->data['modal_js'] = $this->site->modal_js();
				$this->load->view($this->theme.'installment_payment/payment_schedule',$this->data);
			}
		}
    }*/
	
	function payment_schedule($pdf = NULL, $excel = 1,$sale_id= NULL,$id = NULL)
    {
		$this->erp->checkPermissions('index',null,'installment_payment');	
		$check_mfi=$this->installment_payment_model->checkMFI($sale_id);
		
		//if($check_mfi->mfi==1){			
			$this->data['setting'] = $this->site->get_setting();
			$sale = $this->installment_payment_model->getMfiBySaleId($sale_id);
			$customer_id = $sale->customer_id;
			//$created_by = $sale->created_by;
			$created_by = $sale->co;
			$customer = $this->installment_payment_model->getMfiCustomer($customer_id);
			$sale_item = $this->installment_payment_model->getSaleItemBySaleID($sale->id);
			$loans = $this->installment_payment_model->getLoanBySaleId($sale_id);
			$dollar = $this->installment_payment_model->getDollarRate();
			$countloans = $this->installment_payment_model->getLoanBySaleId($sale_id);
			$recipt_voucher = $this->installment_payment_model->getPaymentsByID($sale_id);
			$this->data['sale'] = $sale;
			$this->data['creator'] = $this->installment_payment_model->getMfiCreator($created_by);
			$this->data['last_payment'] = $this->installment_payment_model->getLastPayment($sale_id);
			$this->data['stcurrency'] = $this->installment_payment_model->getSettingCurrncy();
			$this->data['cuscurrency'] = $this->installment_payment_model->getCustomerCurrency($customer_id);
			$this->data['customer'] = $customer;
			$this->data['loan'] = $loans;
			$this->data['dollar'] = $dollar;
			$this->data['countloans'] = $countloans;
			$this->data['sale_id'] = $sale_id;
			$this->data['sale_item'] = $sale_item;
			$this->data['saving'] = $this->installment_payment_model->getSaleSavingSaleID($sale_id);
			$this->data['collateraltype'] = $this->installment_payment_model->getCollateralBySale($sale_id);
			$this->data['total_service'] = $this->installment_payment_model->getservicefee($sale_id);
			$this->data['services'] = $this->installment_payment_model->getServices($sale_id);
			$this->data['currency'] = $this->site->getCurrencyByCode($sale_item->currency_code);
			$this->data['tloans'] = $this->site->getAllTotalLoanBySaleID($sale->id);
			$this->data['address'] = $this->site->getAddressToString($customer->country, $customer->state, $customer->district, $customer->sangkat, $customer->village,'KH');
			$this->data['setting']= $this->site->get_setting();
			$this->data['sale_id']= $recipt_voucher;
			$this->data['modal_js'] = $this->site->modal_js();
			//$this->load->view($this->theme.'installment_payment/cash_payment_schedule_view',$this->data);			
			$this->load->view($this->theme.'installment_payment/cash_payment_schedule_view_mm',$this->data);
		//}
    }
	
	function schedule_view($id = NULL, $sale_id=null)
    {
        $this->erp->checkPermissions();
		$sale_id = $id;
		$this->data['setting'] = $this->site->get_setting();
			$sale = $this->installment_payment_model->getMfiBySaleId($sale_id);
			$customer_id = $sale->customer_id;
			$created_by = $sale->created_by;
			$customer = $this->installment_payment_model->getMfiCustomer($customer_id);
			$sale_item = $this->installment_payment_model->getSaleItemBySaleID($sale->id);
			$loans = $this->installment_payment_model->getLoanBySaleId($sale_id);
			$dollar = $this->installment_payment_model->getDollarRate();
			$countloans = $this->installment_payment_model->getLoanBySaleId($sale_id);
			$this->data['sale'] = $sale;
			$this->data['creator'] = $this->installment_payment_model->getMfiCreator($created_by);
			$this->data['stcurrency'] = $this->installment_payment_model->getSettingCurrncy();
			$this->data['cuscurrency'] = $this->installment_payment_model->getCustomerCurrency($customer_id);
			$this->data['customer'] = $customer;
			$this->data['loan'] = $loans;
			$this->data['dollar'] = $dollar;
			$this->data['countloans'] = $countloans;
			$this->data['sale_id'] = $sale_id;
			$this->data['sale_item'] = $sale_item;
			$this->data['collateraltype'] = $this->installment_payment_model->getCollateralBySale($sale_id);
			$this->data['total_service'] = $this->installment_payment_model->getservicefee($sale_id);
			$this->data['services'] = $this->installment_payment_model->getServices($sale_id);
			$this->data['currency'] = $this->site->getCurrencyByCode($sale_item->currency_code);
			$this->data['tloans'] = $this->site->getAllTotalLoanBySaleID($sale->id);
			$this->data['address'] = $this->site->getAddressToString($customer->country, $customer->state, $customer->district, $customer->sangkat, $customer->village,'KH');
			$this->data['setting']= $this->site->get_setting();
			$this->data['modal_js'] = $this->site->modal_js();
			//$this->load->view($this->theme.'installment_payment/cash_payment_schedule_view',$this->data);			
			$this->load->view($this->theme.'installment_payment/cash_payment_schedule_view_mm',$this->data);
    }
	public function certify_latter(){
		$this->erp->checkPermissions();

        $this->load->view($this->theme.'sales/certify_latter');
		//$this->erp->print_arrays($this->data);
	}
	public function anex_contract(){
		$this->erp->checkPermissions();

        $this->load->view($this->theme.'sales/anex_contract');

	}
	public function report(){
		$this->erp->checkPermissions();

        $this->load->view($this->theme.'installment_payment/report');
	}
	public function payment_schedule_preview($lease_amount = NULL, $rate_type = NULL, $interest_rate = NULL, $term_in_days = NULL, $frequency = NULL){

		//$this->erp->print_arrays($lease_amount .'###'. $rate_type .'###'. $interest_rate .'###'. $term_in_days .'###'. $frequency);
		$this->data['pts']=$this->erp->getPaymentSchedule('1', $lease_amount, $rate_type, $interest_rate, $term_in_days, $frequency, date('Y-m-d'));
	

		//echo $lease_amount.'/ '.$rate_type.' /'.$interest_rate.' /'.$term_in_month;
		//print_r($this->erp->getPaymentSchedule('1',$lease_amount,$rate_type,$interest_rate,$term_in_month,'2016-09-02 16:31:56' ));
		//exit;
		$this->erp->checkPermissions();
		$this->data['modal_js'] = $this->site->modal_js();
        $this->load->view($this->theme.'installment_payment/payment_schedule_preview',$this->data);
	}
	
	public function payment_list($id=NULL){

		$this->erp->checkPermissions();
		//echo $lease_amount.'/ '.$rate_type.' /'.$interest_rate.' /'.$term_in_month;
		//print_r($this->erp->getPaymentSchedule('1',$lease_amount,$rate_type,$interest_rate,$term_in_month,'2016-09-02 16:31:56' ));
		//exit;
		$this->data['mydata'] = $this->installment_payment_model->getPaymentListBysaleId($id);
		$myinfo= $this->installment_payment_model->getPaymentListBysaleId($id);
		$this->data['address'] = $this->site->getAddressToString($myinfo->country, $myinfo->state, $myinfo->district, $myinfo->sangkat, $myinfo->village);
		$address= $this->site->getAddressToString($myinfo->country, $myinfo->state, $myinfo->district, $myinfo->sangkat, $myinfo->village);
		$lease_amount=($myinfo->product_price)-($myinfo->ap);
		$c_date=$this->erp->hrsd($myinfo->cdate);
		$this->data['services'] = $this->installment_payment_model->getServicesBySaleID($id);
		$this->data['pts'] = $this->installment_payment_model->getLoansBySaleID($id);
		$this->data['sale_id'] = $id;
		$this->data['setting'] = $this->settings_model->getSettings();
		
		$this->data['modal_js'] = $this->site->modal_js();
        $this->load->view($this->theme.'installment_payment/payment_list',$this->data);
	}
	
	function register_form($id = NULL)
    {
        $this->erp->checkPermissions(false, true);

        $this->load->view($this->theme . 'installment_payment/register_form', $this->data);
    }
	
	public function cash_payment_schedule_preview_by_id($id){
		$this->erp->checkPermissions('index');		
		$this->data['setting'] = $this->site->get_setting();
		$sale = $this->installment_payment_model->getMfiBySaleId($id);
		$customer_id = $sale->customer_id;
		//$created_by = $sale->created_by;
		$created_by = $sale->co;	
		$setting_policy_payment = $this->site->get_policy_payments();
		$customer = $this->installment_payment_model->getMfiCustomer($customer_id);
		$sale_item = $this->installment_payment_model->getSaleItemBySaleID($sale->id);
		$loans = $this->installment_payment_model->getLoanBySaleId($id);
		$recipt_voucher = $this->installment_payment_model->getPaymentsByID($id);
		$this->data['countloans'] = $this->installment_payment_model->getLoanBySaleId($id);
		$this->data['dollar']  = $this->installment_payment_model->getDollarRate();
		$this->data['stdays'] = $setting_policy_payment;
		$this->data['diffday'] = $this->installment_payment_model->getDayPayments($id);
		//$diffday = $this->installment_payment_model->getDayPayments($id);
		//$this->erp->print_arrays($diffday);
		
		$this->data['last_payment'] = $this->installment_payment_model->getLastPayment($id);
		$this->data['services'] = $this->installment_payment_model->getServices($id);
		$this->data['sale'] = $sale;
		$this->data['stcurrency'] = $this->installment_payment_model->getSettingCurrncy();
		$this->data['cuscurrency'] = $this->installment_payment_model->getCustomerCurrency($customer_id);
		$this->data['creator'] = $this->installment_payment_model->getMfiCreator($created_by);
		$this->data['customer'] = $customer;
		$this->data['loan'] = $loans;
		$this->data['sale_item'] = $sale_item;
		$this->data['saving'] = $this->installment_payment_model->getSaleSavingSaleID($id);
		$this->data['loans'] = $this->installment_payment_model->getBalanceBysale($id);
		$this->data['currency'] = $this->site->getCurrencyByCode($sale_item->currency_code);
		$this->data['tloans'] = $this->site->getAllTotalLoanBySaleID($sale->id);
		$this->data['collateraltype'] = $this->installment_payment_model->getCollateralBySale($id);
		$this->data['total_service'] = $this->installment_payment_model->getservicefee($id);
		$this->data['balance'] = $this->installment_payment_model->getBalanceBysale($id);
		$this->data['address'] = $this->site->getAddressToString($customer->country, $customer->state, $customer->district, $customer->sangkat, $customer->village,'KH');
		$this->data['setting']= $this->site->get_setting();
		$this->data['sale_id']= $recipt_voucher;
		$pic_payment_schedule  = $this->installment_payment_model->getQoutePhotoId($id);
		$this->data['qphoto'] = $pic_payment_schedule;
		$this->data['modal_js'] = $this->site->modal_js();
       // $this->load->view($this->theme.'installment_payment/cash_payment_schedule_process',$this->data);
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('payments')));
        $meta = array('page_title' => lang('payments'), 'bc' => $bc);
        $this->page_construct('installment_payment/cash_payment_schedule_process', $meta, $this->data);
	}
	
	public function export_loan($pdf = NULL, $excel = 1,$id= NULL){
		$setting = $this->site->get_setting();
		$sale = $this->installment_payment_model->getMfiBySaleId($id);
		$customer_id = $sale->customer_id;
		$created_by = $sale->created_by;
		$customer = $this->installment_payment_model->getMfiCustomer($customer_id);
		$sale_item = $this->installment_payment_model->getSaleItemBySaleID($sale->id);
		$loans = $this->installment_payment_model->getLoanBySaleId($id);
		$sale = $sale;
		$creator = $this->installment_payment_model->getMfiCreator($created_by);
		$customer= $customer;

		$currency= $this->site->getCurrencyByCode($sale_item->currency_code);
		$tloans= $this->site->getAllTotalLoanBySaleID($sale->id);
		$address = $this->site->getAddressToString($customer->country, $customer->state, $customer->district, $customer->sangkat, $customer->village,'KH');
		
		
		
        if ($pdf || $excel) {
	
            if (1==1) {

                $this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                $this->excel->getActiveSheet()->setTitle(lang('loan'));
				//Image
				// Set text
				
				
				$gdImage = imagecreatefromjpeg(base_url().'/themes/default/assets/images/logo.jpg');
  				$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
  				$objDrawing->setName('Sample image');
  				$objDrawing->setDescription('Sample image');
  				$objDrawing->setImageResource($gdImage);
  				$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
  				$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
  				$objDrawing->setHeight(100);
  				$objDrawing->setCoordinates('B2');
  				$objDrawing->setWorksheet($this->excel->getActiveSheet());
				
				
				
				
				$setText=array(
				"D3"=>"គ្រឹះស្ថានមីក្រូហិរញ្ញវត្ថុ",
				"D4"=>"ឥណទាន លើកស្ទួយ សមត្ថភាព គ្រួសារ",
				"D5"=>"ការិយាល័យសាខា : ". $this->session->branchName,
				"D6"=>"កាលវិភាគសងប្រាក់",
				"H3"=>"ឯភាពបង់ប្រាក់តាមតារាង",
				"H4"=>"សងប្រាក់នេះ ",
				"H5"=>"ថ្ងៃទី: ".$this->erp->hrsd(date('Y-m-d')),
				"H6"=>"ស្នាមមេដៃស្តាំ",
				"A8"=>"ឈ្មោះអតិថិជន",
				"A9"=>"លេខគណនីកម្ចី",
				"A10"=>"ទូស័ព្ទលេខ",
				"A11"=>"ថ្ងៃបើកប្រាក់",
				"A12"=>"រយៈពេលខ្ចូ",
				"A13"=>"ចំនួនទឹកប្រាក់បានខ្ចី",
				"A14"=>"ការប្រាក់សរុប(១)",
				"A15"=>"សរុបការប្រាក់​ នឹងកម្រៃសេវា(១+២+៣)",
				"A16"=>"អាសលដ្ឋាន",
				"A16"=>"អាសលដ្ឋាន",
				"D8"=>$customer->family_name_other.' '.$customer->name_other,
				"D9"=>$sale->reference_no,
				"D10"=>' '.$creator->phone,
				"D11"=>$this->erp->hrsd($sale->due_date),
				"D12"=>number_format($sale->term,0).' ថ្ងៃ',
				"D13"=>$this->erp->formatMoney($sale->grand_total) .' '. $currency->name_other,
				"D14"=>$this->erp->formatMoney($tloans->tinterest) .' '. $currency->name_other,
				"D15"=>$this->erp->formatMoney($tloans->tinterest) .' '. $currency->name_other,
				"D16"=>'#'.$customer->house_no.','.$customer->street.','.$address,
				"E8"=>"ឈ្មោះជាអក្សរឡាតាំង",
				"E9"=>"លេខគណនីសន្សំ",
				"E10"=>"ឈ្មោះមន្ត្រីឥណទាន",
				"E11"=>"ថ្ងៃសងចុងក្រោយ",
				"E12"=>"លើកទី",
				"E13"=>"អត្រាការប្រាក់",
				"E14"=>"អត្រាពិន័យ",
				"E15"=>"វត្ថុដាក់បញ្ចាំ",
				"F8"=>"N/A",
				"F9"=>"N/A",
				"F10"=>$creator->username,
				"F11"=>$this->erp->hrsd($this->erp->getLastPaymentDate($sale->term, $sale->frequency, $sale->due_date)),
				"F12"=>" 1",
				"F13"=>$this->erp->formatDecimal($sale->interest_rate * 100)."% ក្នុងមួយខែ ឫ ".$this->erp->formatDecimal(($sale->interest_rate * 100) * 12)."% ក្នុងមួយឆ្នាំ",
				"F14"=>$this->erp->formatMoney($setting->penalty_amount)." ដុល្លារក្នុងមួយថ្ងៃ",
				"F15"=>"N/A",
				"A18"=>"ល.រ",
				"B18"=>"ថ្ងៃសងប្រាក់",
				"C18"=>"ចំនួនថ្ងៃ",
				"D18"=>"ប្រាក់ដើមនៅជំពាក់",
				"E18"=>"ប្រាក់ដើមត្រូវសង",
				"F18"=>"ការប្រាក់ត្រូវសង",
				"G18"=>"ប្រាក់ត្រូវសងសរុប",
				"H18"=>"សំគាល់",
				);
				$row_number= 19;
				
				$mergArray=array();
				$total_principle = 0;
				$total_interest = 0;
				$total_payment = 0;
				if(array($loans)) {
					foreach($loans as $pt){
					$setText["A".$row_number] =$pt->period;
					$setText["B".$row_number] =$this->erp->hrsd($pt->dateline);
					$setText["C".$row_number] ="N/A";
					$setText["D".$row_number] =$this->erp->formatMoney((($pt->balance > 0)? $pt->balance : 0)) ;
					$setText["E".$row_number] =$this->erp->formatMoney($pt->principle);
					$setText["F".$row_number] =$this->erp->formatMoney($pt->interest) ;
					$setText["G".$row_number] =$this->erp->formatMoney($pt->payment);
					$setText["H".$row_number] ="";
					array_push($mergArray,'H'.$row_number.':I'.$row_number);
					$total_principle += $pt->principle;
					$total_interest += $pt->interest;
					$total_payment += $pt->payment;
					$row_number++;
					$no_line_num=$row_number;
					}
				}
					$total_line_row_num=$row_number;
					$setText["B".$row_number] ="សរុប";
					$setText["C".$row_number] ="N/A";
					$setText["E".$row_number] =$total_principle;
					$setText["F".$row_number] =$total_interest;
					$setText["G".$row_number] =$total_payment;
			
					
				
				
				
				
				
				
				
				$row_number+=2;
				$setText["A".$row_number] ="កំណត់សំគាល់:";
				$setText["C".$row_number] ="អតិថិជនត្រូវបង់ប្រាក់អោយបានទៀងទាត់តាមកាលវិភាគសងប្រាក់";
				$row_number++;
				$setText["C".$row_number] ="អតិថិជនត្រូវបង់ប្រាក់អោយបានទៀងទាត់តាមកាលវិភាគសងប្រាក់";
				$row_number++;
				$setText["C".$row_number] ="ការមិនគោរបតាមកិច្ចសន្យា គ្រឹះស្ងានមីក្រូហិរញវត្ថុប្រាសាក់នឹងចាត់វិធានការណ៌តាមផ្លូវច្បាប់";

				// Controll by array
					array_push($mergArray,
					"D3:F3",
					"D4:F4",
					"D5:F5",
					"D6:F6",
					"H3:I3",
					"H4:I4",
					"H5:I5",
					"H6:I6",
					"A8:C8",
					"A9:C9",
					"A10:C10",
					"A11:C11",
					"A12:C12",
					"A13:C13",
					"A14:C14",
					"A15:C15",
					"A16:C16",
					"H18:I18"
					);
					$centerArray=array(
					"A18:I18",
					"D3:F3",
					"D4:F4",
					"D5:F5",
					"D6:F6",
					"H3:I3",
					"H4:I4",
					"H5:I5",
					"H6:I6",
					"A19:A".$no_line_num,
					"B19:B".$no_line_num,
					"C19:C".$no_line_num,
					);
					$borderOutArray=array(
					"H7:H12",
					"I7:I12",
					);
					$fontSizeArray=array(
					"A1:K42"=>9,
					);
					$boldArray=array(
					"D8",
					"D10",
					"D3:D6",
					'B'.$total_line_row_num.':G'.$total_line_row_num,
					);
					$fontColorArray=array(
					"D8:D16"=>'41, 128, 185',
					"F8:F15"=>'41, 128, 185',
					"A18:I18"=>'236, 240, 241',
					);
				$this->erp->getExcel($mergArray,$centerArray,$borderOutArray,$setText,$fontSizeArray,$boldArray,$fontColorArray);
				
                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
				 
				$this->excel->getActiveSheet()->getStyle('A18:I18')
								->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()
								->setRGB('95a5a6');
				
				
				
				
				
				
                $filename = lang('loan');
                $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                if ($pdf) {
                    $styleArray = array(
                        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                    );
                    $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                    $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                    require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
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
                    $objWriter->save('php://output');
                    exit();
                }
                if ($excel) {
                    ob_clean();
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                    header('Cache-Control: max-age=0');
                    ob_clean();
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    $objWriter->save('php://output');
                    exit();
                }

            }

            $this->session->set_flashdata('error', lang('nothing_found'));
            redirect($_SERVER["HTTP_REFERER"]);
		}
	}
	
	////_guareentee_contract_controller===============
	function guareentee_contract($sale_id){
		$this->load->model('Installment_payment_model');
		$this->data['guareentee_info'] = $this->Installment_payment_model->guareentee_contract_ByID($sale_id); 		
		$this->load->view($this->theme.'installment_payment/guareentee_contract',$this->data);		
		
	}		
	////_collateral_contract_land_controller===============
	function collateral_contract_land($sale_id){
		$this->load->model('Installment_payment_model');
		$this->data['contract_info'] = $this->Installment_payment_model->collateral_contract_land_ByID($sale_id); 				
		$this->load->view($this->theme.'installment_payment/collateral_contract_land',$this->data);
	}
	////_collateral_contract_identification_controller===============
	function collateral_contract_identification($sale_id){
		$this->load->model('Installment_payment_model');
		$this->data['contract_info'] = $this->Installment_payment_model->collateral_contract_identification_ByID($sale_id); 		
		$this->load->view($this->theme.'installment_payment/collateral_contract_identification',$this->data);		
	}
	
	//collateral_contract
	public function collateral_contract($sale_id) {		
		$this->load->model('Installment_payment_model');
		$this->data['setting'] = $this->settings_model->getSettings();
		$this->data['contract_info'] = $this->Installment_payment_model->loan_agreement_ByID($sale_id);
		$this->data['contract_gr_info'] = $this->Installment_payment_model->guarantor_loan_agreement_ByID($sale_id);
		$this->data['contract_join_gr_info'] = $this->Installment_payment_model->join_guarantor_loan_agreement_ByID($sale_id);
		$this->data['village'] = $this->Installment_payment_model->loan_agreemen_Cus_village($sale_id);
		$this->data['sangkat'] = $this->Installment_payment_model->loan_agreemen_Cus_sangkat($sale_id);
		$this->data['district'] = $this->Installment_payment_model->loan_agreemen_Cus_district($sale_id);
		$this->data['state'] = $this->Installment_payment_model->loan_agreemen_Cus_state($sale_id);		
		$this->data['collateral'] = $this->Installment_payment_model->loan_agreement_collateral($sale_id);
		$this->data['collaterals'] = $this->Installment_payment_model->loan_agreement_collaterals($sale_id);
		$this->data['join_lease'] = $this->Installment_payment_model->join_lease_loan_agreement($sale_id);
		$this->data['currency'] = $this->Installment_payment_model->currency_ByID($sale_id);
		$this->load->view($this->theme.'installment_payment/collateral_contract',$this->data);
		
	}
	
	//loan_agreement
	public function mfi_contract($sale_id) {
		$this->load->model('Installment_payment_model');
		$sale = $this->Installment_payment_model->getSaleById($sale_id);
		$this->data['setting'] = $this->settings_model->getSettings();
		$this->data['contract_info'] = $this->Installment_payment_model->loan_agreement_ByID($sale_id);
		$this->data['contract_gr_info'] = $this->Installment_payment_model->guarantor_loan_agreement_ByID($sale_id);
		$this->data['contract_join_gr_info'] = $this->Installment_payment_model->join_guarantor_loan_agreement_ByID($sale_id);
		$this->data['village'] = $this->Installment_payment_model->loan_agreemen_Cus_village($sale_id);
		$this->data['sangkat'] = $this->Installment_payment_model->loan_agreemen_Cus_sangkat($sale_id);
		$this->data['district'] = $this->Installment_payment_model->loan_agreemen_Cus_district($sale_id);
		$this->data['state'] = $this->Installment_payment_model->loan_agreemen_Cus_state($sale_id);	
		
		$this->data['br_village'] = $this->Installment_payment_model->branch_village($sale_id);
		$this->data['br_sangkat'] = $this->Installment_payment_model->branch_sangkat($sale_id);
		$this->data['br_district'] = $this->Installment_payment_model->branch_district($sale_id);
		$this->data['br_state'] = $this->Installment_payment_model->branch_state($sale_id);	
		
		$this->data['collateral'] = $this->Installment_payment_model->loan_agreement_collateral($sale_id);
		$this->data['collaterals'] = $this->Installment_payment_model->loan_agreement_collaterals($sale_id);
		$this->data['join_lease'] = $this->Installment_payment_model->join_lease_loan_agreement($sale_id);
		$this->data['guarantor'] = $this->Installment_payment_model->getGuarantorInfo($sale_id);
		$this->data['currency'] = $this->Installment_payment_model->currency_ByID($sale_id);
		$this->load->view($this->theme.'installment_payment/loan_agreement',$this->data);
		
	}
	//group_agreement
	public function group_agreement($sale_id, $group) {
		$this->load->model('Installment_payment_model');
		$this->data['setting'] = $this->settings_model->getSettings();
		$this->data['contract_info'] = $this->Installment_payment_model->group_agreement_ByID($group);
		$this->data['loan_amount'] = $this->Installment_payment_model->LoanGroupAmount($group);		
		$this->data['currency'] = $this->Installment_payment_model->currency_ByID($sale_id);
		$this->data['group'] = $this->Installment_payment_model->group_applicant($group);
		$this->data['contract_gr_info'] = $this->Installment_payment_model->guarantor_loan_agreement_ByID($sale_id);
		$this->data['contract_join_gr_info'] = $this->Installment_payment_model->join_guarantor_loan_agreement_ByID($sale_id);
		$this->data['village'] = $this->Installment_payment_model->loan_agreemen_Cus_village($sale_id);
		$this->data['sangkat'] = $this->Installment_payment_model->loan_agreemen_Cus_sangkat($sale_id);
		$this->data['district'] = $this->Installment_payment_model->loan_agreemen_Cus_district($sale_id);
		$this->data['state'] = $this->Installment_payment_model->loan_agreemen_Cus_state($sale_id);
		$this->data['br_village'] = $this->Installment_payment_model->branch_village($sale_id);
		$this->data['br_sangkat'] = $this->Installment_payment_model->branch_sangkat($sale_id);
		$this->data['br_district'] = $this->Installment_payment_model->branch_district($sale_id);
		$this->data['br_state'] = $this->Installment_payment_model->branch_state($sale_id);			
		$this->data['collateral'] = $this->Installment_payment_model->loan_agreement_collateral($sale_id);
		$this->data['join_lease'] = $this->Installment_payment_model->join_lease_loan_agreement($sale_id);
		$this->load->view($this->theme.'installment_payment/group_agreement',$this->data);
	}
	
		//loan_agreement
	public function guarantor_contract($sale_id) {
		$this->load->model('Installment_payment_model');
		$sale = $this->Installment_payment_model->getSaleById($sale_id);
		$this->data['setting'] = $this->settings_model->getSettings();
		$this->data['contract_info'] = $this->Installment_payment_model->loan_agreement_ByID($sale_id);
		$this->data['contract_gr_info'] = $this->Installment_payment_model->guarantor_loan_agreement_ByID($sale_id);
		$this->data['br_village'] = $this->Installment_payment_model->branch_village($sale_id);
		$this->data['br_sangkat'] = $this->Installment_payment_model->branch_sangkat($sale_id);
		$this->data['br_district'] = $this->Installment_payment_model->branch_district($sale_id);
		$this->data['br_state'] = $this->Installment_payment_model->branch_state($sale_id);
		$this->data['guarantor'] = $this->Installment_payment_model->getGuarantorInfo($sale_id);
		$this->data['join_guarantor'] = $this->Installment_payment_model->getJoinGuarantorInfo($sale_id);
		$this->data['currency'] = $this->Installment_payment_model->currency_ByID($sale_id);
		$this->load->view($this->theme.'installment_payment/guarantor_contract',$this->data);
	}
	
	public function Received_loans($sale_id) {		
		$this->load->model('Installment_payment_model');
		$this->data['setting'] = $this->settings_model->getSettings();
		$this->data['contract_info'] = $this->Installment_payment_model->loan_agreement_ByID($sale_id);	
		$this->data['join_lease'] = $this->Installment_payment_model->join_lease_loan_agreement($sale_id);
		$this->data['village'] = $this->Installment_payment_model->loan_agreemen_Cus_village($sale_id);
		$this->data['sangkat'] = $this->Installment_payment_model->loan_agreemen_Cus_sangkat($sale_id);
		$this->data['district'] = $this->Installment_payment_model->loan_agreemen_Cus_district($sale_id);
		$this->data['state'] = $this->Installment_payment_model->loan_agreemen_Cus_state($sale_id);	
		$this->load->view($this->theme.'installment_payment/Received_laons',$this->data);
	}
	
	function getSalesReport($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('index');
		
		
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
		
        if ($this->input->get('applicant')) {
            $applicant = $this->input->get('applicant');
        } else {
            $applicant = NULL;
        }
		if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('branch')){
            $branch_query = $this->input->get('branch');
        } else {
            $branch_query = NULL;
        }
        if ($this->input->get('gr_loan')) {
            $gr_loan = $this->input->get('gr_loan');
        } else {
            $gr_loan = NULL;
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
        $pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        
        $pdf_link = anchor('sales/certify_latter', '<i class="fa fa-file-pdf-o"></i> ' . lang('certify_letter'));
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
            <li>' . $payments_link . '</li>
            <li>' . $add_payment_link . '</li>
            <li>' . $pdf_link . '</li>
            <li>' . $delete_link . '</li>
        </ul>
		</div></div>';
		$setting = $this->settings_model->getSettings();
		$loans = $this->installment_payment_model->getBalanceBysale($id);		
		$penalty_days = $setting->penalty_days?$setting->penalty_days:'';
		//$penalty_amount = (($setting->penalty_types=="Percentage")? $setting->penalty_amount * $loans->princ :($setting->penalty_amount));
		$penalty_amount = $setting->penalty_amount? $setting->penalty_amount:0;
		$penalty_types = $setting->penalty_types? $setting->penalty_types:'';
		//IF(DATE_ADD(".$this->db->dbprefix('loans').".dateline, INTERVAL ".$penalty_days." DAY) < CURDATE(), (DATEDIFF(CURDATE(), ".$this->db->dbprefix('loans').".dateline)) * ".$penalty_amount.", '') as penalty_amount,
		//IF(DATE_ADD(".$this->db->dbprefix('loans').".dateline, INTERVAL ".$penalty_days." DAY) < CURDATE(), (DATEDIFF(CURDATE(), ".($this->db->dbprefix('loans').".dateline)) * ".(($penalty_types=="Percentage")? $penalty_amount * ".(SELECT SUM(principle) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id)." :($penalty_amount))).", '') as penalty_amount,
		//(($penalty_types=="Percentage"? $penalty_amount * ". (SELECT SUM(principle) FROM ". $this->db->dbprefix('loans')." WHERE sale_id = ". $this->db->dbprefix('sales') ." .id).") :$penalty_amount)   
		$this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables
                ->select($this->db->dbprefix('sales').".id,
						".$this->db->dbprefix('sales').".reference_no,
						".$this->db->dbprefix('loan_groups').".name AS glname,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other,
						IF(".$this->db->dbprefix('companies').".phone2<>'', CONCAT(".$this->db->dbprefix('companies').".phone1, ' / ', ".$this->db->dbprefix('companies').".phone2), ".$this->db->dbprefix('companies').".phone1) as phone, ".						
						$this->db->dbprefix('loans').".dateline,
						NOW(), ".
						$penalty_days .",
						IF(DATE_ADD(".$this->db->dbprefix('loans').".dateline, INTERVAL ".$penalty_days." DAY) < CURDATE(), DATEDIFF(CURDATE(), ".$this->db->dbprefix('loans').".dateline), '') as due_days, 
						".$this->db->dbprefix('loans').".payment,					
						(SELECT SUM(amount) FROM ".$this->db->dbprefix('sale_services')." WHERE sale_id = ".$this->db->dbprefix('sales').".id) as total_service_charge,	
						IF(DATE_ADD(".$this->db->dbprefix('loans').".dateline, INTERVAL ".$penalty_days." DAY) < CURDATE(), (DATEDIFF(CURDATE(), ".$this->db->dbprefix('loans').".dateline)) * ".$penalty_amount.", '') as penalty_amount,	
						(".$this->db->dbprefix('loans').".payment + IF(DATE_ADD(".$this->db->dbprefix('loans').".dateline, INTERVAL ".$penalty_days." DAY) < CURDATE(), (DATEDIFF(".$this->db->dbprefix('loans').".dateline, CURDATE())) * ".$penalty_amount.", '')) as total_amount, 
						".$this->db->dbprefix('sales').".mfi as mfi,")
						
                ->from('sales')
				->join('loans','sales.id=loans.sale_id','INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('loan_groups','loan_groups.id = sales.loan_group_id','left')
                ->where('warehouse_id', $warehouse_id)
				->where("(erp_sales.sale_status = 'activated' OR erp_sales.sale_status = 'registered')")
				->where('loans.dateline <=', date('Y-m-d'))
				->where('loans.paid_amount', 0)
				->group_by('sales.id','DESC');
				
        } else {
            $this->datatables
                ->select($this->db->dbprefix('loans').".sale_id AS sale_id,
						".$this->db->dbprefix('loans').".sale_id as id, ".
						$this->db->dbprefix('sales').".reference_no, ".
						$this->db->dbprefix('loan_groups').".name AS glname,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other,
						IF(".$this->db->dbprefix('companies').".phone2<>'', CONCAT(".$this->db->dbprefix('companies').".phone1, ' / ', ".$this->db->dbprefix('companies').".phone2), ".$this->db->dbprefix('companies').".phone1) as phone, ".						
						$this->db->dbprefix('loans').".dateline,
						IF(DATE_ADD(".$this->db->dbprefix('loans').".dateline, INTERVAL ".$penalty_days." DAY) < CURDATE(), DATEDIFF(CURDATE(), ".$this->db->dbprefix('loans').".dateline), '') as due_days, 
						CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) AS coname,
						myBranch.name,
						
						".$this->db->dbprefix('sales').".mfi as mfi")
                ->from('sales')
				->join('users','sales.created_by=users.id','INNER')
				->join('companies as myBranch', 'users.branch_id = myBranch.id')
				->join('loans','sales.id=loans.sale_id','INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('loan_groups','loan_groups.id = sales.loan_group_id','left')
				->where("(erp_sales.sale_status = 'activated' OR erp_sales.sale_status = 'registered')")
				->where('loans.dateline <=', date('Y-m-d'))
				->where('loans.paid_amount', 0)
				->group_by('sales.id','DESC');
        }
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->datatables->where('sales.branch_id', $this->session->branch_id);
		}
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            //$this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }		
		if ($start_date) {
			$this->datatables->where($this->db->dbprefix('sales').'.contract_date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		}
		if ($applicant) {
			$this->datatables->where('sales.customer_id', $applicant);
		}
		if ($user_query) {
			$this->datatables->where('sales.created_by', $user_query);
		}
		if ($branch_query) {
			$this->datatables->where('sales.created_by', $branch_query);
		}
		if ($gr_loan) {
			$this->datatables->where('sales.loan_group_id', $gr_loan);
		}
        $this->datatables->add_column("Actions", $action,"sale_id");
        $this->datatables->unset_column('sale_id');
		//$this->datatables->unset_column('mfi');
        echo $this->datatables->generate();
    }
	
	
	function changePaymentDate($ids = NULL) {
		
        $this->erp->checkPermissions('payments', true);
        $this->load->helper('security');
		$this->load->model('Installment_payment_model');
        $this->form_validation->set_rules('val', lang("data"), 'required');
        if ($this->form_validation->run() == true) {
			$lid = $this->input->post('lid');
			$payment_date = $this->input->post('payment_date');
			$old_payment_date = $this->input->post('old_payment_date');
			$n = sizeof($lid);
			$data = array();
			for($i=0; $i<$n; $i++) {
				$data[] = array(
									'id' => $lid[$i],
									'dateline' => $this->erp->fld($payment_date[$i]),
									'old_date' => $this->erp->fld($old_payment_date[$i])
								);
			}
			//$this->erp->print_arrays($data);
        } elseif ($this->input->post('save')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
		
        if ($this->form_validation->run() == true && $this->Installment_payment_model->updatePaymentDate($data)) {
            $this->session->set_flashdata('message', lang("payment_date_changed"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
			$arr_id = explode('_', $ids);
			$loans = $this->Installment_payment_model->getLoansByIDs($arr_id);
			$this->data['loans'] = $loans;
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'installment_payment/update_payment_date', $this->data);
        }
		
    }
	
	
	
    
	function reschedule($id = NULL, $q_id = NULL){		
        
		$this->erp->checkPermissions('payments', true);
		
        $this->load->helper('security');
		$this->load->model('quotes_model');
		$this->load->model('Installment_payment_model');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }if ($this->input->get('id')) {
            $q_id = $this->input->get('id');
        }
		$this->form_validation->set_rules('paid_amount', lang("paid_amount"), 'required');
		$this->form_validation->set_rules('owed', lang("owed"), 'required');
		
        if ($this->form_validation->run() == true) { //$this->erp->print_arrays("Hello");
			$q_service  = $this->input->post('ch_services');
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
				$Services[] = array(
										'sale_id'     	=> $id,
										'services_id' 	=> $service,
										'amount' 		=> $service_amount,
										'type' 			=> $service_types,
										'service_paid' 	=> $this->input->post('service_paid_'.$service),
										
									);
			}
			
			//$this->erp->print_arrays($Services);
			$data = '';
				$data = array(
					'customer_group' 		=>  $this->input->post('customer_type'),
					're_startintallment' 	=>  $this->erp->fld(trim($this->input->post('st_inst_date'))),
					'interrest_rate' 		=>  $this->input->post('interest_rate_cash'),
					'term' 					=>  $this->input->post('term_cash'),
					'frequency'				=>  $this->input->post('frequency_cash'),
					'rate_type' 			=>  $this->input->post('rate_type_cash'),
					'principle_balnce' 		=>  str_replace(',', '', $this->input->post('principle_balnce')),
					'paid_amount' 			=>  str_replace(',', '', $this->input->post('paid_amount')),
					'owed' 					=>  str_replace(',', '', $this->input->post('owed')),
			);
			//$this->erp->print_arrays($data);
        } elseif ($this->input->post('reschedule')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
		
        if ($this->form_validation->run() == true && $this->Installment_payment_model->addReschedule($data, isset($Services) ?$Services  : ('') )) {
            $this->session->set_flashdata('message', lang("renew_added"));
			redirect("Installment_payment");
        } else {
			$services = $this->site->getServicesByStatus('1');
			$quote_services = $this->site->getQuoteServicesByQuoteID($q_id);
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
			
			$quote = $this->quotes_model->getQuoteByID($q_id);
            $this->data['inv'] = $quote;
			
			$sale = $this->sales_model->getInvoiceByID($id);
			$this->data['id'] = $id;
            $this->data['s_inv'] = $sale;
			$this->data['balances'] = $this->Installment_payment_model->LoanBalanceBySaleId($id);
			$this->data['sale_item'] = $this->Installment_payment_model->getSaleItemByID($id);
			$this->data['finacal_products'] = $this->site->getAllCustomerGroup();
			$this->data['terms'] = $this->site->getAllTerm();
            $this->data['modal_js'] = $this->site->modal_js();
			$this->data['customers'] = $this->site->getCustomers();
			$this->load->view($this->theme . 'installment_payment/reschedule', $this->data);
        }
    }
	public function payment_voucher($id, $sale_id, $payment_id){
		$this->erp->checkPermissions('payment_voucher',null,'installment');
		$this->load->model('Installment_payment_model');
		$this->data['setting'] = $this->site->get_setting();
		$this->data['currency'] = $this->Installment_payment_model->currency_ByID($sale_id);		
		$s_id = $this->installment_payment_model->getPaymentsByID($sale_id);
		$branch_id = $s_id->branch_id;
		$rv =$this->installment_payment_model->getReceipttVoucherByID($payment_id);
		$this->data['receipt_voucher'] = $rv;
		$this->data['service_payment'] = $this->installment_payment_model->getServiceByPaymentID($payment_id);
		$this->data['branch_info'] = $this->Installment_payment_model->getBranchInfo($branch_id);
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['modal_js'] = $this->site->modal_js();
		$this->load->view($this->theme . 'installment_payment/payment_voucher', $this->data);
	}
	
	public function ajaxGetUserByBranchID1($branch_id = NULL){
		if ($rows = $this->Installment_payment_model->getSubCatByCatID($branch_id)) {
            $data = json_encode($rows);
        } else {
            $data = false;
        }
        echo $data;
	}
	
	public function ajaxGetUserByBranchID($branch_id = NULL){
		if($branch_id){
			$branch_id = $branch_id;
		}
		$user = $this->Installment_payment_model->getSubCatByCatID($branch_id);
		if($user){
			die(json_encode($user));
		}else{
			die(json_encode(FALSE));
		}
	}
	
	function view_payments($loan_id = NULL, $sale_id = NULL)
    {
        $this->erp->checkPermissions('index',null,'installment_payment');
		$this->load->model('Installment_payment_model');
		$this->load->model('site');	
		$this->data['setting'] = $this->installment_payment_model->get_setting();
		$this->data['sale_item'] = $this->installment_payment_model->getSaleItemByID($sale_id);		
		$this->data['modal_js'] = $this->site->modal_js();
		$this->data['loan'] = $this->installment_payment_model->getLoanByLoanID($loan_id);
		$this->data['last_payment'] = $this->installment_payment_model->getLastOldPayment($sale_id);
        $this->data['payments'] = $this->Installment_payment_model->getAllPayments($loan_id);
        $this->load->view($this->theme . 'installment_payment/view_payments', $this->data);
    }
	
	function view_Allpayments( $sale_id = NULL)
    {
        $this->erp->checkPermissions('index',null,'installment_payment');
		$this->load->model('Installment_payment_model');
		$this->load->model('site');	
		$this->data['setting'] = $this->installment_payment_model->get_setting();
		$this->data['sale_item'] = $this->installment_payment_model->getSaleItemByID($sale_id);		
		$this->data['modal_js'] = $this->site->modal_js();
		//$this->data['loan'] = $this->installment_payment_model->getLoanByLoanID($loan_id);
		$this->data['last_payment'] = $this->installment_payment_model->getLastOldPayment($sale_id);
        $this->data['payments'] = $this->Installment_payment_model->getAllPaymentsBySale($sale_id);
        $this->load->view($this->theme . 'installment_payment/view_allpayments', $this->data);
    }
	
	
}
