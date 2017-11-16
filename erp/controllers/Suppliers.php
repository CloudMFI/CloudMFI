<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Suppliers extends MY_Controller
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
        $this->lang->load('suppliers', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('companies_model');
    }

    function index($action = NULL)
    {
        $this->erp->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('suppliers')));
        $meta = array('page_title' => lang('suppliers'), 'bc' => $bc);
        $this->page_construct('suppliers/index', $meta, $this->data);
    }

    function getSuppliers()
    {
        $this->erp->checkPermissions('index');

        $this->load->library('datatables');
        $this->datatables
            ->select($this->db->dbprefix('companies').".id, company, ".$this->db->dbprefix('companies').".name, vat_no, phone, email, provinces.name as province, cities.name as city, bank_name, account_number, account_name")
            ->join('addresses as provinces', 'companies.state = provinces.id', 'left')
			->join('addresses as cities', 'companies.city = cities.id', 'left')
			->from("companies")
            ->where('group_name', 'supplier')
            ->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("edit_dealer") . "' href='" . site_url('suppliers/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_dealer") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('suppliers/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", $this->db->dbprefix('companies').".id");
        echo $this->datatables->generate();
    }

    function view($id = NULL)
    {
        $this->erp->checkPermissions('index', true);
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['supplier'] = $this->companies_model->getCompanyByID($id);
        $this->load->view($this->theme.'suppliers/view',$this->data);
    }
	
	function add()
    {
        $this->erp->checkPermissions(false, true);

        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'is_unique[companies.email]');

        if ($this->form_validation->run('companies/add') == true) {
			
			$whs = $this->input->post('cf5');
			$warehouses = '';
			$i = 1;
			foreach($whs as $wh){
				if(count($whs)==$i){
					$warehouses .= $wh;
				}else{
					$warehouses .= $wh.',';
				}
				$i++;
			}
			
            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'group_id' => NULL,
                'group_name' => 'supplier',
                'company' => $this->input->post('company'),
                'vat_no' => $this->input->post('vat_no'),
				'house_no' => $this->input->post('house_no'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('province'),
                'country' => $this->input->post('country'),
                'phone' => $this->input->post('phone'),
                'logo' => $this->input->post('logo'),
				'group'=>$this->input->post('group'),
				'village'=>$this->input->post('village'),
				'street'=>$this->input->post('street'),
				'sangkat'=>$this->input->post('communce'),
				'district'=>$this->input->post('district'),
                'cf1' => $this->input->post('cf1'),
                'cf2' => $this->input->post('cf2'),
                'cf3' => $this->input->post('cf3'),
                'cf4' => $this->input->post('cf4'),
                'cf5' => $warehouses,
                'cf6' => $this->input->post('cf6'),
                'bank_name' => $this->input->post('bank_name'),
                'account_number' => $this->input->post('account_number'),
                'account_name' => $this->input->post('account_name'),
            );
			//$this->erp->print_arrays($data);
        } elseif ($this->input->post('add_dealer')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('suppliers');
        }

        if ($this->form_validation->run() == true && $this->companies_model->addCompany($data)) {
            $this->session->set_flashdata('message', $this->lang->line("dealer_added"));
            redirect("suppliers");
        } else {
            $this->data['logos'] = $this->getLogoList();
			$this->data['countries'] = $this->site->getCountries();
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
			$this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->load->view($this->theme . 'suppliers/add', $this->data);
        }
    }
	
	public function getLogoList()
    {
        $this->load->helper('directory');
        $dirname = "assets/uploads/logos";
        $ext = array("jpg", "png", "jpeg", "gif");
        $files = array();
        if ($handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle)))
                for ($i = 0; $i < sizeof($ext); $i++)
                    if (stristr($file, "." . $ext[$i])) //NOT case sensitive: OK with JpeG, JPG, ecc.
                        $files[] = $file;
            closedir($handle);
        }
        sort($files);
        return $files;
    }

    function edit($id = NULL)
    {
        $this->erp->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $company_details = $this->companies_model->getCompanyByID($id);
        if ($this->input->post('email') != $company_details->email) {
            $this->form_validation->set_rules('email', lang("email_address"), 'is_unique[companies.email]');
        }

        if ($this->form_validation->run('companies/add') == true) {
			
			$whs = $this->input->post('cf5');
			$warehouses = '';
			$i = 1;
			foreach($whs as $wh){
				if(count($whs)==$i){
					$warehouses .= $wh;
				}else{
					$warehouses .= $wh.',';
				}
				$i++;
			}
			
            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'group_id' => NULL,
                'group_name' => 'supplier',
                'company' => $this->input->post('company'),
                'address' => $this->input->post('address'),
                'vat_no' => $this->input->post('vat_no'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
				'business_activity'=>$this->input->post('business'),
				'group'=>$this->input->post('group'),
				'village'=>$this->input->post('village'),
				'street'=>$this->input->post('Street'),
				'sangkat'=>$this->input->post('Commune'),
				'district'=>$this->input->post('District'),
                'phone' => $this->input->post('phone'),
                'phone1' => $this->input->post('contact_person'),
                'logo' => $this->input->post('logo'),
                'cf1' => $this->input->post('cf1'),
                'cf2' => $this->input->post('cf2'),
                'cf3' => $this->input->post('cf3'),
                'cf4' => $this->input->post('cf4'),
                'cf5' => $warehouses,
                'cf6' => $this->input->post('cf6'),
                'invoice_footer' => $this->input->post('invoice_footer'),
            );
				
        } elseif ($this->input->post('edit_dealer')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('suppliers');
        }

        if ($this->form_validation->run() == true && $this->companies_model->updateCompany($id, $data)) {
            $this->session->set_flashdata('message', $this->lang->line("dealer_updated"));
            redirect("suppliers");
        } else {
            $this->data['dealer'] = $company_details;
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['logos'] = $this->getLogoList();
			$this->data['countries'] = $this->site->getCountries();	
            $this->data['modal_js'] = $this->site->modal_js();
			$this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->load->view($this->theme . 'suppliers/edit', $this->data);
        }
    }
	
	public function update($id){
		$data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'group_id' => NULL,
                'group_name' => 'supplier',
                'company' => $this->input->post('company'),
                'vat_no' => $this->input->post('vat_no'),
				'house_no' => $this->input->post('house_no'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('province'),
                'country' => $this->input->post('country'),
                'phone' => $this->input->post('phone'),
                'phone1' => $this->input->post('contact_person'),
                'logo' => $this->input->post('logo'),
				'group'=>$this->input->post('group'),
				'village'=>$this->input->post('village'),
				'street'=>$this->input->post('street'),
				'sangkat'=>$this->input->post('communce'),
				'district'=>$this->input->post('district'),
                'cf1' => $this->input->post('cf1'),
                'cf2' => $this->input->post('cf2'),
                'cf3' => $this->input->post('cf3'),
                'cf4' => $this->input->post('cf4'),
             
                'cf6' => $this->input->post('cf6'),
                'bank_name' => $this->input->post('bank_name'),
                'account_number' => $this->input->post('account_number'),
                'account_name' => $this->input->post('account_name'),
            );
			
				$u=$this->companies_model->update_company($id, $data);
				if($u){
					 $this->session->set_flashdata('Update successful.');
					redirect('suppliers');
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
        $this->load->view($this->theme . 'suppliers/users', $this->data);

    }

    function add_user($company_id = NULL)
    {
        $this->erp->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }
        $company = $this->companies_model->getCompanyByID($company_id);

        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'is_unique[users.email]');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'required|min_length[8]|max_length[20]|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('confirm_password'), 'required');

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
            redirect('suppliers');
        }

        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $active, $notify)) {
            $this->session->set_flashdata('message', $this->lang->line("user_added"));
            redirect("suppliers");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['company'] = $company;
            $this->load->view($this->theme . 'suppliers/add_user', $this->data);
        }
    }

    function import_csv()
    {
        $this->erp->checkPermissions();
        $this->load->helper('security');
        $this->form_validation->set_rules('csv_file', $this->lang->line("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (DEMO) {
                $this->session->set_flashdata('warning', $this->lang->line("disabled_in_demo"));
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
                    redirect("suppliers");
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
                        $this->session->set_flashdata('error', $this->lang->line("check_supplier_email") . " (" . $csv['email'] . "). " . $this->lang->line("supplier_already_exist") . " (" . $this->lang->line("line_no") . " " . $rw . ")");
                        redirect("suppliers");
                    }
                    $rw++;
                }
                foreach ($final as $record) {
                    $record['group_id'] = 4;
                    $record['group_name'] = 'supplier';
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
                $this->session->set_flashdata('message', $this->lang->line("suppliers_added"));
                redirect('suppliers');
            }
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'suppliers/import', $this->data);
        }
    }

    function delete($id = NULL)
    {
        $this->erp->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->companies_model->deleteSupplier($id)) {
            echo $this->lang->line("dealer_deleted");
        } else {
            $this->session->set_flashdata('warning', lang('dealer_x_deleted_have_sales'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }
    }

    function suggestions($term = NULL, $limit = NULL)
    {
        // $this->erp->checkPermissions('index');
        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        $limit = $this->input->get('limit', TRUE);
        $rows['results'] = $this->companies_model->getSupplierSuggestions($term, $limit);
        echo json_encode($rows);
    }

    function getSupplier($id = NULL)
    {
        // $this->erp->checkPermissions('index');
        $row = $this->companies_model->getCompanyByID($id);
        echo json_encode(array(array('id' => $row->id, 'text' => $row->company)));
    }

    function supplier_actions()
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
                        if (!$this->companies_model->deleteSupplier($id)) {
                            $error = true;
                        }
                    }
                    if ($error) {
                        $this->session->set_flashdata('warning', lang('dealers_x_deleted_have_sales'));
                    } else {
                        $this->session->set_flashdata('message', $this->lang->line("dealers_deleted"));
                    }
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('dealer'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('no'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('company'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('phone'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('email'));
					$this->excel->getActiveSheet()->SetCellValue('F1', lang('city'));
					$this->excel->getActiveSheet()->SetCellValue('G1', lang('country'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $customer = $this->site->getCompanyByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $customer->id);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $customer->company);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $customer->name);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $customer->phone);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $customer->email);
						$this->excel->getActiveSheet()->SetCellValue('F' . $row, $customer->city);
						$this->excel->getActiveSheet()->SetCellValue('G' . $row, $customer->country);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'dealers_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', $this->lang->line("no_dealer_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
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
