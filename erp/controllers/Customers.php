<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends MY_Controller
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
        $this->lang->load('customers', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('companies_model');
        $this->load->model('quotes_model');
    }

    function index($action = NULL)
    {
        $this->erp->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('customers')));
        $meta = array('page_title' => lang('customers'), 'bc' => $bc);
        $this->page_construct('customers/index', $meta, $this->data);
    }

    function getCustomers()
    {
        $this->erp->checkPermissions('index');
        $this->load->library('datatables');
        $this->datatables
            ->select("id, id AS cus_no,CONCAT(family_name,' ',name) AS F_Name, phone1, date_of_birth, address")
            ->from("companies")
            ->where('group_name', 'customer')
			->order_by('companies.id','DESC')
            //->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . lang("list_users") . "' href='" . site_url('customers/users/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-users\"></i></a> <a class=\"tip\" title='" . lang("add_user") . "' href='" . site_url('customers/add_user/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-user-plus\"></i></a> <a class=\"tip\" title='" . lang("list_deposits") . "' href='" . site_url('customers/deposits/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-money\"></i></a> <a class=\"tip\" title='" . lang("add_deposit") . "' href='" . site_url('customers/add_deposit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-plus\"></i></a> <a class=\"tip\" title='" . lang("edit_customer") . "' href='" . site_url('customers/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_customer") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('customers/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
			//->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . lang("list_deposits") . "' href='" . site_url('customers/deposits/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-money\"></i></a> <a class=\"tip\" title='" . lang("add_deposit") . "' href='" . site_url('customers/add_deposit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-plus\"></i></a></div>", "id");
			->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . lang("view_details") . "' href='" . site_url('customers/view_details/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-eye\"></i></a> </div>", "id");
		//->unset_column('id');
        echo $this->datatables->generate();
    }

    function view($id = NULL)
    {
        $this->erp->checkPermissions('index', true);
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['customer'] = $this->companies_model->getCompanyByID($id);
        $this->load->view($this->theme.'customers/view',$this->data);
    }
	
	function add()
    {
        $this->erp->checkPermissions(false, true);

        $this->form_validation->set_rules('email', lang("email_address"), 'is_unique[companies.email]');

        if ($this->form_validation->run() == true) {
            $cg = $this->site->getCustomerGroupByID($this->input->post('customer_group'));
            $data = array('group_id' => '3',
							'group_name' => 'customer',
							'customer_group_id' => $this->input->post('customer_group'),
							'customer_group_name' => $cg->name,
							'civility' => $this->input->post('civility'),
							'gov_id' => $this->input->post('gov_id'),
							'name' => $this->input->post('first_name'),
							'name_other' => $this->input->post('first_name_other'),
							'family_name' => $this->input->post('family_name'),
							'family_name_other' => $this->input->post('family_name_other'),
							'nickname' => $this->input->post('nick_name'),
							'spouse_family_name' => $this->input->post('sp_fam_name'),
							'gender' => $this->input->post('gender'),
							'status' => $this->input->post('marital_status'),
							'date_of_birth' => $this->erp->fld(trim($this->input->post('dob'))),
							'age' => $this->input->post('age'),
							'black_list' => $this->input->post('black_list'),
							'whose_income' => $this->input->post('whose_income'),
							'income_combination' => $this->input->post('inc_comb'),
							'state' => $this->input->post('state'),
							'address' => $this->input->post('pob'),
							'phone1' => $this->input->post('phone_1'),
							'phone2' => $this->input->post('phone_2'),
							'spouse_phone' => $this->input->post('sp_phone'),
							'house_no' => $this->input->post('house_no'),
							'months' => $this->input->post('months'),
							'years' => $this->input->post('years'),
							'housing' => $this->input->post('housing'),
							'street' => $this->input->post('street'),
							'village' => $this->input->post('village'),
							'district' => $this->input->post('district'),
							'sangkat' => $this->input->post('communce'),
							'state' => $this->input->post('province'),
							'country' => $this->input->post('country'),
							'nationality' => $this->input->post('nationality'),
							'num_of_child' => $this->input->post('num_of_child'),
						);
			//$this->erp->print_arrays($data);
            // attachment
            /*if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = 'assets/uploads/';
                $config['allowed_types'] = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
                $config['max_size'] = '1024';
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }*/
        } elseif ($this->input->post('add_customer')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }

        if ($this->form_validation->run() == true && $cid = $this->companies_model->addCompany($data)) {
            $this->session->set_flashdata('message', lang("customer_added"));
            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
            redirect($ref[0] . '?customer=' . $cid);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->load->view($this->theme . 'customers/add', $this->data);
        }
    }
	
    function edit($id = NULL)
    {
        $this->erp->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
		$this->form_validation->set_rules('company', lang("company"), 'trim|required');
        $company_details = $this->companies_model->getCompanyByID($id);
        if ($this->input->post('email') != $company_details->email) {
            $this->form_validation->set_rules('email', lang("email_address"), 'is_unique[companies.email]');
        }

        if ($this->form_validation->run('companies/edit') == true) {
            $cg = $this->site->getCustomerGroupByID($this->input->post('customer_group'));
            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'group_id' => '3',
                'group_name' => 'customer',
                'customer_group_id' => $this->input->post('customer_group'),
                'customer_group_name' => $cg->name,
                'company' => $this->input->post('company'),
                'address' => $this->input->post('address'),	
                'vat_no' => $this->input->post('vat_no'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
                'phone1' => $this->input->post('phone'),
                'cf1' => $this->input->post('cf1'),
                'gender' => $this->input->post('gender'),
				'status' => $this->input->post('status'),
                'date_of_birth' => $this->erp->fld($this->input->post('date_of_birth')),
                'status' => $this->input->post('status'),
                'award_points' => $this->input->post('award_points'),
				'end_date' => $this->erp->fld(trim($this->input->post('end_date'))),
				'start_date' => $this->erp->fld(trim($this->input->post('start_date')))
            );
			//$this->erp->print_arrays($data);
            // attachment
            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = 'assets/uploads/';
                $config['allowed_types'] = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
                $config['max_size'] = '1024';
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }


        } elseif ($this->input->post('edit_customer')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->companies_model->updateCompany($id, $data)) {
            $this->session->set_flashdata('message', lang("customer_updated"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['customer'] = $company_details;
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->load->view($this->theme . 'customers/edit', $this->data);
        }
    }

	
    function users($company_id = NULL)
    {
        $this->erp->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }


        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['company'] = $this->companies_model->getCompanyByID($company_id);
        $this->data['users'] = $this->companies_model->getCompanyUsers($company_id);
        $this->load->view($this->theme . 'customers/users', $this->data);

    }

    function add_user($company_id = NULL)
    {
        $this->erp->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }
        $company = $this->companies_model->getCompanyByID($company_id);

        $this->form_validation->set_rules('email', lang("email_address"), 'is_unique[users.email]');
        $this->form_validation->set_rules('password', lang('password'), 'required|min_length[8]|max_length[20]|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', lang('confirm_password'), 'required');

        if ($this->form_validation->run('companies/add_user') == true) {
            $active = $this->input->post('status');
            $notify = $this->input->post('notify');
            list($username, $domain) = explode("@", $this->input->post('email'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
                'company_id' => $company->id,
                'company' => $company->company,
                'group_id' => 3
            );
            $this->load->library('ion_auth');
        } elseif ($this->input->post('add_user')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }

        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $active, $notify)) {
            $this->session->set_flashdata('message', lang("user_added"));
            redirect("customers");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['company'] = $company;
            $this->load->view($this->theme . 'customers/add_user', $this->data);
        }
    }

    function import_csv()
    {
        $this->erp->checkPermissions();
        $this->load->helper('security');
        $this->form_validation->set_rules('csv_file', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (DEMO) {
                $this->session->set_flashdata('warning', lang("disabled_in_demo"));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            if (isset($_FILES["csv_file"])) /* if($_FILES['userfile']['size'] > 0) */ {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/csv/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '2000';
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('csv_file')) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("customers");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen("assets/uploads/csv/" . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5001, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                $keys = array('company', 'name', 'email', 'phone', 'address', 'city', 'state', 'postal_code', 'country', 'vat_no', 'cf1', 'cf2', 'cf3', 'cf4', 'cf5', 'cf6');

                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
                $rw = 2;
                foreach ($final as $csv) {
                    if ($this->companies_model->getCompanyByEmail($csv['email'])) {
                        $this->session->set_flashdata('error', lang("check_customer_email") . " (" . $csv['email'] . "). " . lang("customer_already_exist") . " (" . lang("line_no") . " " . $rw . ")");
                        redirect("customers");
                    }
                    $rw++;
                }
                foreach ($final as $record) {
                    $record['group_id'] = 3;
                    $record['group_name'] = 'customer';
                    $record['customer_group_id'] = 1;
                    $record['customer_group_name'] = 'General';
                    $data[] = $record;
                }
                //$this->erp->print_arrays($data);
            }

        } elseif ($this->input->post('import')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }

        if ($this->form_validation->run() == true && !empty($data)) {
            if ($this->companies_model->addCompanies($data)) {
                $this->session->set_flashdata('message', lang("customers_added"));
                redirect('customers');
            }
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'customers/import', $this->data);
        }
    }

    function delete($id = NULL)
    {
        $this->erp->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->input->get('id') == 1) {
            $this->session->set_flashdata('error', lang('customer_x_deleted'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }

        if ($this->companies_model->deleteCustomer($id)) {
            echo lang("customer_deleted");
        } else {
            $this->session->set_flashdata('warning', lang('customer_x_deleted_have_sales'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }
    }

    function suggestions($term = NULL, $limit = NULL)
    {
        // $this->erp->checkPermissions('index');
        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        if (strlen($term) < 1) {
            return FALSE;
        }

        $limit = $this->input->get('limit', TRUE);
        $rows['results'] = $this->companies_model->getCustomerSuggestions($term, $limit);
        
        echo json_encode($rows);
    }
	
	function balance_suggest($term = NULL, $limit = NULL)
    {
        // $this->erp->checkPermissions('index');
        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        if (strlen($term) < 1) {
            return FALSE;
        }
        $limit = $this->input->get('limit', TRUE);
        $rows['result'] = $this->companies_model->getBalanceSuggestions($term, $limit);
        echo json_encode($rows);
    }

    function getCustomer($id = NULL)
    {
        // $this->erp->checkPermissions('index');
        $row = $this->companies_model->getCompanyByID($id);
        echo json_encode(array(array('id' => $row->id, 'text' => ($row->family_name .' '. $row->name ))));
    }

    function get_award_points($id = NULL)
    {
        $this->erp->checkPermissions('index');
        $row = $this->companies_model->getCompanyByID($id);
        echo json_encode(array('ca_points' => $row->award_points));
    }

    function customer_actions()
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
                        if (!$this->companies_model->deleteCustomer($id)) {
                            $error = true;
                        }
                    }
                    if ($error) {
                        $this->session->set_flashdata('warning', lang('customers_x_deleted_have_sales'));
                    } else {
                        $this->session->set_flashdata('message', lang("customers_deleted"));
                    }
                    redirect($_SERVER["HTTP_REFERER"]);
                }
				
				if($this->input->post('form_action') == 'send_sms'){
					$this->load->view($this->theme . 'customers/send_sms');
				}

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('company'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('email_address'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('phone'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('city'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('customer_group'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('vat_no'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('deposit'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('award_points'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $customer = $this->site->getCompanyByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $customer->company);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $customer->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $customer->email);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $customer->phone);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $customer->city);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $customer->customer_group_name);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $customer->vat_no);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $customer->deposit_amount);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $customer->award_points);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'customers_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', lang("no_customer_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function deposits($company_id = NULL)
    {
        $this->erp->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['company'] = $this->companies_model->getCompanyByID($company_id);
        $this->load->view($this->theme . 'customers/deposits', $this->data);
    }

    function get_deposits($id)
    {
        $this->erp->checkPermissions('deposits');
        $this->load->library('datatables');
        $this->datatables
            ->select("deposits.id as id, date, amount, paid_by, CONCAT({$this->db->dbprefix('users')}.first_name, ' ', {$this->db->dbprefix('users')}.last_name) as created_by", false)
            ->from("deposits")
            ->join('users', 'users.id=deposits.created_by', 'left')
			->where('deposits.company_id', $id)
            ->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . lang("deposit_note") . "' href='" . site_url('customers/deposit_note/$1') . "' data-toggle='modal' data-target='#myModal2'><i class=\"fa fa-file-text-o\"></i></a> <a class=\"tip\" title='" . lang("edit_deposit") . "' href='" . site_url('customers/edit_deposit/$1') . "' data-toggle='modal' data-target='#myModal2'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_deposit") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('customers/delete_deposit/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id")
        ->unset_column('id');
        echo $this->datatables->generate();
    }

    function add_deposit($company_id = NULL)
    {
        $this->erp->checkPermissions('deposits', true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }
        $company = $this->companies_model->getCompanyByID($company_id);

        if ($this->Owner || $this->Admin) {
            $this->form_validation->set_rules('date', lang("date"), 'required');
        }
        $this->form_validation->set_rules('amount', lang("amount"), 'required|numeric');
        
        if ($this->form_validation->run() == true) {

            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $data = array(
                'date' => $date,
                'amount' => $this->input->post('amount'),
                'paid_by' => $this->input->post('paid_by'),
                'note' => $this->input->post('note') ? $this->input->post('note') : $company->name,
                'company_id' => $company->id,
                'created_by' => $this->session->userdata('user_id'),
				'biller_id' => $this->input->post('biller')
            );
			$payment = array(
				'date' => $date,
				'sale_id' => $sale_id,
				'reference_no' => $this->site->getReference('sp'),
				'amount' => $this->input->post('amount'),
				'paid_by' => $this->input->post('paid_by'),
				'cheque_no' => $this->input->post('cheque_no'),
				'cc_no' => $this->input->post('paid_by') == 'gift_card' ? $this->input->post('gift_card_no') : $this->input->post('pcc_no'),
				'cc_holder' => $this->input->post('pcc_holder'),
				'cc_month' => $this->input->post('pcc_month'),
				'cc_year' => $this->input->post('pcc_year'),
				'cc_type' => $this->input->post('pcc_type'),
				'note' => $this->input->post('note') ? $this->input->post('note') : $company->name,
				'created_by' => $company->id,
				'type' => 'received',
				'biller_id'	=> $this->input->post('biller')
			);
            $cdata = array(
                'deposit_amount' => ($company->deposit_amount+$this->input->post('amount'))
            );
        } elseif ($this->input->post('add_deposit')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }

        if ($this->form_validation->run() == true && $this->companies_model->addDeposit($data, $cdata, $payment)) {
            $this->session->set_flashdata('message', lang("deposit_added"));
            redirect("customers");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['company'] = $company;
            $this->load->view($this->theme . 'customers/add_deposit', $this->data);
        }
    }

    function edit_deposit($id = NULL)
    {
        $this->erp->checkPermissions('deposits', true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $deposit = $this->companies_model->getDepositByID($id);
        $company = $this->companies_model->getCompanyByID($deposit->company_id);

        if ($this->Owner || $this->Admin) {
            $this->form_validation->set_rules('date', lang("date"), 'required');
        }
        $this->form_validation->set_rules('amount', lang("amount"), 'required|numeric');
        
        if ($this->form_validation->run() == true) {

            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = $deposit->date;
            }
            $data = array(
                'date' => $date,
                'amount' => $this->input->post('amount'),
                'paid_by' => $this->input->post('paid_by'),
                'note' => $this->input->post('note'),
                'company_id' => $deposit->company_id,
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => $date = date('Y-m-d H:i:s'),
				'biller_id' => $this->input->post('biller')
            );
			
			$payment = array(
				'date' => $date,
				'deposit_id' => $id,
				'reference_no' => $this->site->getReference('sp'),
				'amount' => $this->input->post('amount'),
				'paid_by' => $this->input->post('paid_by'),
				'cheque_no' => $this->input->post('cheque_no'),
				'cc_no' => $this->input->post('paid_by') == 'gift_card' ? $this->input->post('gift_card_no') : $this->input->post('pcc_no'),
				'cc_holder' => $this->input->post('pcc_holder'),
				'cc_month' => $this->input->post('pcc_month'),
				'cc_year' => $this->input->post('pcc_year'),
				'cc_type' => $this->input->post('pcc_type'),
				'note' => $this->input->post('note') ? $this->input->post('note') : $company->name,
				'type' => 'received',
				'biller_id'	=> $this->input->post('biller')
			);

            $cdata = array(
                'deposit_amount' => (($company->deposit_amount-$deposit->amount)+$this->input->post('amount'))
            );

        } elseif ($this->input->post('edit_deposit')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }

        if ($this->form_validation->run() == true && $this->companies_model->updateDeposit($id, $data, $cdata, $payment)) {
            $this->session->set_flashdata('message', lang("deposit_updated"));
            redirect("customers");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['company'] = $company;
            $this->data['deposit'] = $deposit;
            $this->load->view($this->theme . 'customers/edit_deposit', $this->data);
        }
    }

    public function delete_deposit($id)
    {
        $this->erp->checkPermissions(NULL, TRUE);

        if ($this->companies_model->deleteDeposit($id)) {
            echo lang("deposit_deleted");
        }
    }

    public function deposit_note($id = null)
    {
        $this->erp->checkPermissions('deposits', true);
        $deposit = $this->companies_model->getDepositByID($id);
        $this->data['customer'] = $this->companies_model->getCompanyByID($deposit->company_id);
        $this->data['deposit'] = $deposit;
        $this->data['page_title'] = $this->lang->line("deposit_note");
        $this->load->view($this->theme . 'customers/deposit_note', $this->data);
    }
	
	public function return_deposit($id){
		$this->erp->checkPermissions('deposits', true);
		if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $deposit = $this->companies_model->getDepositByID($id);
        $company = $this->companies_model->getCompanyByID($deposit->company_id);
		if ($this->Owner || $this->Admin) {
            $this->form_validation->set_rules('date', lang("date"), 'required');
        }
        $this->form_validation->set_rules('amount', lang("amount"), 'required|numeric');
		if($this->form_validation->run() == true){
			if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = $deposit->date;
            }
            $data = array(
                'amount' => ($deposit->amount - $this->input->post('amount')),
                'note' => $this->input->post('note'),
                'company_id' => $deposit->company_id,
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => $date = date('Y-m-d H:i:s'),
				'biller_id' => $this->input->post('biller')
            );
			
			$payment = array(
				'date' => $date,
				'deposit_id' => $id,
				'reference_no' => $this->site->getReference('pp'),
				'amount' => $this->input->post('amount'),
				'paid_by' => 'cash',
				'note' => $this->input->post('note') ? $this->input->post('note') : $company->name,
				'type' => 'received',
				'biller_id'	=> $this->input->post('biller')
			);

            $cdata = array(
                'deposit_amount' => (($deposit->amount - $this->input->post('amount')))
            );
		} elseif ($this->input->post('return_deposit')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('account/deposits');
        }
		if ($this->form_validation->run() == true && $this->companies_model->ReturnDeposit($id, $data, $cdata, $payment)) {
            $this->session->set_flashdata('message', lang("deposit_returned"));
            redirect("account/deposits");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['company'] = $company;
            $this->data['deposit'] = $deposit;
            $this->load->view($this->theme . 'customers/return_deposit', $this->data);
        }
	}
	
	function customer_view($company_id=null){
		$this->erp->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['company'] = $this->companies_model->getCompanyByID($company_id);
		$this->load->library('datatables');
		$this->data['customer_info']=$this->db
			->select('id,group_name,name,company,address,city,state,country,phone,email,gender,DATE_FORMAT(date_of_birth,"%d/%b/%Y") AS dob,status')
			->from('companies')
			->where('group_name','customer')
			->Where('id',$company_id)
			->get();
        //$this->data['users'] = $this->companies_model->getCompanyUsers($company_id);
		$this->load->view($this->theme.'customers/customer_views',$this->data);
	}
	
	public function send_sms()
	{
		$this->erp->checkPermissions();
        $this->load->library('plivo');
		$this->form_validation->set_rules('sms_content', lang("sms_content"), 'required');
		//if($this->form_validation->run() == true){
        if($this->input->post()){
            $phone   = $this->input->post('phone_number');
            $message = $this->input->post('sms_content');
			$message = strip_tags($message);
			$multi_number = '';
            foreach ($phone as $number) {
                $string = str_replace(' ', '', $number);
                $pn = substr($string, 1);
				$multi_number .= '+855'. $pn . '<';
			}
			
			$multi_number = rtrim($multi_number, '<');
			
			//echo $multi_number;die();

			$sms_data = array(
				'src' => 'CloudNET', //The phone number to use as the caller id (with the country code). E.g. For USA 15671234567
				'dst' => $multi_number, // The number to which the message needs to be send (regular phone numbers must be prefixed with country code but without the ‘+’ sign) E.g., For USA 15677654321.
				'text' => $message, // The text to send
				'type' => 'sms', //The type of message. Should be 'sms' for a text message. Defaults to 'sms'
				'url' => base_url() . 'customers/receive_sms', // The URL which will be called with the status of the message.
				'method' => 'POST', // The method used to call the URL. Defaults to. POST
			);
			//$this->erp->print_arrays($sms_data);
			/*
			 * look up available number groups
			 */
			$response_array = $this->plivo->send_sms($sms_data);

			if ($response_array[0] == '200')
			{
				$this->session->set_flashdata('message', lang("sms_sent"));
				redirect("customers");
			}else {
				$this->session->set_flashdata('error', lang("error_while_sending_sms"));
				redirect("customers");
			}
        }else{
            $arr = array();
            if ($this->input->get('data'))
            {
                $arr = explode(',', $this->input->get('data'));
            }
            $this->data['phone'] = $this->companies_model->getPhoneNumber($arr);
            $this->load->view($this->theme . 'customers/send_sms', $this->data);
        }
	}
	function view_details($id = NULL)
    {
        $this->erp->checkPermissions(false, true);

        /*if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }*/
		
		$this->load->model('companies_model');
		$this->data['customer_loan'] = $this->companies_model->getCustomerLoans($id);
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js'] = $this->site->modal_js();
        $this->load->view($this->theme . 'customers/view_details', $this->data);
    }
	public function getCustomerQuotes($id = null, $view_draft = null)
    {
        $this->erp->checkPermissions('index');
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
					
					$this->db->dbprefix('quotes').".status as status, 
					DATE_FORMAT(".$this->db->dbprefix('quotes').".date,'%d-%m-%Y %h:%i:%s'),
					DATE_FORMAT(".$this->db->dbprefix('quotes').".approved_date,'%d-%m-%Y %h:%i:%s'),
					CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) AS coname,
					myBranch.name,".
					$this->db->dbprefix('quotes').".total * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate .")")
			->from('quotes')
			->join('users','quotes.created_by=users.id','INNER')
			->join('sales', 'sales.quote_id = quotes.id', 'left')
			->join('companies','quotes.customer_id=companies.id','INNER')
			->join('companies as myBranch', 'users.branch_id = myBranch.id', 'left')
			->join('quote_items', 'quotes.id = quote_items.quote_id', 'left')
			->join('currencies','currencies.code = quote_items.currency_code','left')
			->join('loan_groups','loan_groups.id = quotes.loan_group_id','left')
			//->where('quotes.customer_id', $id)
			->where('companies.gov_id', $id)
			->order_by('quotes.id','DESC');
		
		if(!$view_draft && !($this->Owner || $this->Admin)) {
			$this->datatables->where('erp_quotes.status <>', 'draft');
		}
        $this->datatables->add_column("Actions", $action,$this->db->dbprefix('quotes').".id");
        echo $this->datatables->generate();
    }
	
	function add_customers()
    {
        $this->erp->checkPermissions(false, true);
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['modal_js'] = $this->site->modal_js();
		$this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
		$this->load->view($this->theme . 'customers/add_customers', $this->data);
    }
	
}
