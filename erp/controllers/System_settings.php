<?php defined('BASEPATH') OR exit('No direct script access allowed');

class system_settings extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }

        if (!$this->Owner && !$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('welcome');
        }
        $this->lang->load('settings', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('settings_model');
		$this->load->model('products_model');
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif';
        $this->allowed_file_size = '1024';
    }

    function index()
    {
        $this->form_validation->set_rules('site_name', lang('site_name'), 'trim|required');
		$this->form_validation->set_rules('business_type', lang('business_type'), 'required');
        $this->form_validation->set_rules('dateformat', lang('dateformat'), 'trim|required');
        $this->form_validation->set_rules('timezone', lang('timezone'), 'trim|required');
        $this->form_validation->set_rules('mmode', lang('maintenance_mode'), 'trim|required');
        //$this->form_validation->set_rules('logo', lang('logo'), 'trim');
        $this->form_validation->set_rules('iwidth', lang('image_width'), 'trim|numeric|required');
        $this->form_validation->set_rules('iheight', lang('image_height'), 'trim|numeric|required');
        $this->form_validation->set_rules('twidth', lang('thumbnail_width'), 'trim|numeric|required');
        $this->form_validation->set_rules('theight', lang('thumbnail_height'), 'trim|numeric|required');
        $this->form_validation->set_rules('display_all_products', lang('display_all_products'), 'trim|numeric|required');
        $this->form_validation->set_rules('watermark', lang('watermark'), 'trim|required');
        $this->form_validation->set_rules('reg_ver', lang('reg_ver'), 'trim|required');
        $this->form_validation->set_rules('allow_reg', lang('allow_reg'), 'trim|required');
        $this->form_validation->set_rules('reg_notification', lang('reg_notification'), 'trim|required');
        $this->form_validation->set_rules('currency', lang('default_currency'), 'trim|required');
        $this->form_validation->set_rules('email', lang('default_email'), 'trim|required');
        $this->form_validation->set_rules('language', lang('language'), 'trim|required');
        $this->form_validation->set_rules('warehouse', lang('default_warehouse'), 'trim|required');
        $this->form_validation->set_rules('biller', lang('default_biller'), 'trim|required');
        $this->form_validation->set_rules('tax_rate', lang('product_tax'), 'trim|required');
        $this->form_validation->set_rules('tax_rate2', lang('invoice_tax'), 'trim|required');
        $this->form_validation->set_rules('sales_prefix', lang('sales_prefix'), 'trim');
        $this->form_validation->set_rules('quote_prefix', lang('quote_prefix'), 'trim');
        $this->form_validation->set_rules('purchase_prefix', lang('purchase_prefix'), 'trim');
        $this->form_validation->set_rules('transfer_prefix', lang('transfer_prefix'), 'trim');
        $this->form_validation->set_rules('delivery_prefix', lang('delivery_prefix'), 'trim');
        $this->form_validation->set_rules('payment_prefix', lang('payment_prefix'), 'trim');
        $this->form_validation->set_rules('return_prefix', lang('return_prefix'), 'trim');
        $this->form_validation->set_rules('expense_prefix', lang('expense_prefix'), 'trim');
        $this->form_validation->set_rules('detect_barcode', lang('detect_barcode'), 'trim|required');
        $this->form_validation->set_rules('theme', lang('theme'), 'trim|required');
        $this->form_validation->set_rules('rows_per_page', lang('rows_per_page'), 'trim|required|greater_than[9]|less_than[501]');
        $this->form_validation->set_rules('accounting_method', lang('accounting_method'), 'trim|required');
        $this->form_validation->set_rules('product_serial', lang('product_serial'), 'trim|required');
        $this->form_validation->set_rules('product_discount', lang('product_discount'), 'trim|required');
        $this->form_validation->set_rules('bc_fix', lang('bc_fix'), 'trim|numeric|required');
        $this->form_validation->set_rules('protocol', lang('email_protocol'), 'trim|required');
        if ($this->input->post('protocol') == 'smtp') {
            $this->form_validation->set_rules('smtp_host', lang('smtp_host'), 'required');
            $this->form_validation->set_rules('smtp_user', lang('smtp_user'), 'required');
            $this->form_validation->set_rules('smtp_pass', lang('smtp_pass'), 'required');
            $this->form_validation->set_rules('smtp_port', lang('smtp_port'), 'required');
        }
        if ($this->input->post('protocol') == 'sendmail') {
            $this->form_validation->set_rules('mailpath', lang('mailpath'), 'required');
        }
        $this->form_validation->set_rules('decimals', lang('decimals'), 'trim|required');
        $this->form_validation->set_rules('purchase_decimals', lang('purchase_decimals'), 'trim|required');
        $this->form_validation->set_rules('decimals_sep', lang('decimals_sep'), 'trim|required');
        $this->form_validation->set_rules('thousands_sep', lang('thousands_sep'), 'trim|required');
        $this->load->library('encrypt');

        if ($this->form_validation->run() == true) {

            $language = $this->input->post('language');

            if ((file_exists('erp/language/' . $language . '/erp_lang.php') && is_dir('erp/language/' . $language)) || $language == 'english') {
                $lang = $language;
            } else {
                $this->session->set_flashdata('error', lang('language_x_found'));
                redirect("system_settings");
                $lang = 'english';
            }

            $tax1 = ($this->input->post('tax_rate') != 0) ? 1 : 0;
            $tax2 = ($this->input->post('tax_rate2') != 0) ? 1 : 0;
			
			
			$penalty_types = '';
			$penalty_amount = 0;
			$tmp_amount = $this->input->post('penalty_amount');
			if(strpos($tmp_amount, '%') != false) {
				$penalty_types = 'Percentage';
				$tmp_amount = str_replace('%', '', $tmp_amount);
				$penalty_amount = ($tmp_amount/100);
			}else {
				$penalty_types = 'Fixed Amount';
				$penalty_amount = abs($this->input->post('penalty_amount'));
			}
			
			
            $data = array('site_name' => DEMO ? 'iCloudERP - POS' : $this->input->post('site_name'),
				'business_type' => $this->input->post('business_type'),
                'rows_per_page' => $this->input->post('rows_per_page'),
                'dateformat' => $this->input->post('dateformat'),
                'timezone' => DEMO ? 'Asia/Phnom_Penh' : $this->input->post('timezone'),
                'mmode' => trim($this->input->post('mmode')),
                'iwidth' => $this->input->post('iwidth'),
                'iheight' => $this->input->post('iheight'),
                'twidth' => $this->input->post('twidth'),
                'theight' => $this->input->post('theight'),
                'watermark' => $this->input->post('watermark'),
                'reg_ver' => $this->input->post('reg_ver'),
                'allow_reg' => $this->input->post('allow_reg'),
                'reg_notification' => $this->input->post('reg_notification'),
                'accounting_method' => $this->input->post('accounting_method'),
                'default_email' => DEMO ? 'icloud.erp@gmail.com' : $this->input->post('email'),
                'language' => $lang,
                'default_warehouse' => $this->input->post('warehouse'),
                'default_tax_rate' => $this->input->post('tax_rate'),
                'default_tax_rate2' => $this->input->post('tax_rate2'),
                'sales_prefix' => $this->input->post('sales_prefix'),
                'quote_prefix' => $this->input->post('quote_prefix'),
                'purchase_prefix' => $this->input->post('purchase_prefix'),
                'transfer_prefix' => $this->input->post('transfer_prefix'),
                'delivery_prefix' => $this->input->post('delivery_prefix'),
                'payment_prefix' => $this->input->post('payment_prefix'),
                'return_prefix' => $this->input->post('return_prefix'),
                'expense_prefix' => $this->input->post('expense_prefix'),
                'collateral_prefix' => $this->input->post('collateral_prefix'),
                'auto_detect_barcode' => trim($this->input->post('detect_barcode')),
                'theme' => trim($this->input->post('theme')),
                'product_serial' => $this->input->post('product_serial'),
                'customer_group' => $this->input->post('customer_group'),
                'product_expiry' => $this->input->post('product_expiry'),
                'product_discount' => $this->input->post('product_discount'),
                'default_currency' => $this->input->post('currency'),
                'bc_fix' => $this->input->post('bc_fix'),
                'tax1' => $tax1,
                'tax2' => $tax2,
                'overselling' => $this->input->post('restrict_sale'),
                'reference_format' => $this->input->post('reference_format'),
                'racks' => $this->input->post('racks'),
                'attributes' => $this->input->post('attributes'),
                'restrict_calendar' => $this->input->post('restrict_calendar'),
                'captcha' => $this->input->post('captcha'),
                'item_addition' => $this->input->post('item_addition'),
                'protocol' => DEMO ? 'mail' : $this->input->post('protocol'),
                'mailpath' => $this->input->post('mailpath'),
                'smtp_host' => $this->input->post('smtp_host'),
                'smtp_user' => $this->input->post('smtp_user'),
                'smtp_port' => $this->input->post('smtp_port'),
                'smtp_crypto' => $this->input->post('smtp_crypto') ? $this->input->post('smtp_crypto') : NULL,
                'decimals' => $this->input->post('decimals'),
                'purchase_decimals' => $this->input->post('purchase_decimals'),
                'decimals_sep' => $this->input->post('decimals_sep'),
                'thousands_sep' => $this->input->post('thousands_sep'),
                'default_biller' => $this->input->post('biller'),
                'invoice_view' => $this->input->post('invoice_view'),
                'rtl' => $this->input->post('rtl'),
                'each_spent' => $this->input->post('each_spent') ? $this->input->post('each_spent') : NULL,
                'ca_point' => $this->input->post('ca_point') ? $this->input->post('ca_point') : NULL,
                'each_sale' => $this->input->post('each_sale') ? $this->input->post('each_sale') : NULL,
                'sa_point' => $this->input->post('sa_point') ? $this->input->post('sa_point') : NULL,
                'sac' => $this->input->post('sac'),
                'qty_decimals' => $this->input->post('qty_decimals'),
                'display_symbol' => $this->input->post('display_symbol'),
				'symbol' => $this->input->post('symbol'),
				'auto_print' => trim($this->input->post('auto_print')),
				'alert_day' => trim($this->input->post('alert_day')),
				'sms_auth_id' => $this->input->post('auth_id'),
				'sms_auth_taken' => $this->input->post('auth_taken'),
				'sms_link' => $this->input->post('sms_link'),
				//'penalty_amount' => $this->input->post('penalty_amount'),
				'penalty_amount' => $penalty_amount,
				'penalty_types'  => $penalty_types,
				'penalty_days' => $this->input->post('penalty_days'),
				'interest_discount' => $this->input->post('interest_discount'),
				'compulsory_saving' => $this->input->post('compulsory_saving'),
				'adv_app_amount' => trim(str_replace(',', '', $this->input->post('ad_app_amount'))),
            );
			//$this->erp->print_arrays($data);
            if ($this->input->post('smtp_pass')) {
                $data['smtp_pass'] = $this->encrypt->encode($this->input->post('smtp_pass'));
            }
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateSetting($data)) {
            if ($this->write_index($data['timezone']) == false) {
                $this->session->set_flashdata('error', lang('setting_updated_timezone_failed'));
                redirect('system_settings');
            }

            $this->session->set_flashdata('message', lang('setting_updated'));
            redirect("system_settings");
        } else {
            $this->data['error'] = validation_errors();
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['settings'] = $this->settings_model->getSettings();
            $this->data['currencies'] = $this->settings_model->getAllCurrencies();
            $this->data['date_formats'] = $this->settings_model->getDateFormats();
            $this->data['tax_rates'] = $this->settings_model->getAllTaxRates();
            $this->data['customer_groups'] = $this->settings_model->getAllCustomerGroups();
            $this->data['warehouses'] = $this->settings_model->getAllWarehouses();
            $this->data['smtp_pass'] = $this->encrypt->decode($this->data['settings']->smtp_pass);
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('system_settings')));
            $meta = array('page_title' => lang('system_settings'), 'bc' => $bc);
            $this->page_construct('settings/index', $meta, $this->data);
        }
    }

    function paypal()
    {

        $this->form_validation->set_rules('active', $this->lang->line('activate'), 'trim');
        $this->form_validation->set_rules('account_email', $this->lang->line('paypal_account_email'), 'trim|valid_email');
        if ($this->input->post('active')) {
            $this->form_validation->set_rules('account_email', $this->lang->line('paypal_account_email'), 'required');
        }
        $this->form_validation->set_rules('fixed_charges', $this->lang->line('fixed_charges'), 'trim');
        $this->form_validation->set_rules('extra_charges_my', $this->lang->line('extra_charges_my'), 'trim');
        $this->form_validation->set_rules('extra_charges_other', $this->lang->line('extra_charges_others'), 'trim');

        if ($this->form_validation->run() == true) {

            $data = array('active' => $this->input->post('active'),
                'account_email' => $this->input->post('account_email'),
                'fixed_charges' => $this->input->post('fixed_charges'),
                'extra_charges_my' => $this->input->post('extra_charges_my'),
                'extra_charges_other' => $this->input->post('extra_charges_other')
            );
        }

        if ($this->form_validation->run() == true && $this->settings_model->updatePaypal($data)) {
            $this->session->set_flashdata('message', $this->lang->line('paypal_setting_updated'));
            redirect("system_settings/paypal");
        } else {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

            $this->data['paypal'] = $this->settings_model->getPaypalSettings();

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('paypal_settings')));
            $meta = array('page_title' => lang('paypal_settings'), 'bc' => $bc);
            $this->page_construct('settings/paypal', $meta, $this->data);
        }
    }

    function skrill()
    {

        $this->form_validation->set_rules('active', $this->lang->line('activate'), 'trim');
        $this->form_validation->set_rules('account_email', $this->lang->line('paypal_account_email'), 'trim|valid_email');
        if ($this->input->post('active')) {
            $this->form_validation->set_rules('account_email', $this->lang->line('paypal_account_email'), 'required');
        }
        $this->form_validation->set_rules('fixed_charges', $this->lang->line('fixed_charges'), 'trim');
        $this->form_validation->set_rules('extra_charges_my', $this->lang->line('extra_charges_my'), 'trim');
        $this->form_validation->set_rules('extra_charges_other', $this->lang->line('extra_charges_others'), 'trim');

        if ($this->form_validation->run() == true) {

            $data = array('active' => $this->input->post('active'),
                'account_email' => $this->input->post('account_email'),
                'fixed_charges' => $this->input->post('fixed_charges'),
                'extra_charges_my' => $this->input->post('extra_charges_my'),
                'extra_charges_other' => $this->input->post('extra_charges_other')
            );
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateSkrill($data)) {
            $this->session->set_flashdata('message', $this->lang->line('skrill_setting_updated'));
            redirect("system_settings/skrill");
        } else {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

            $this->data['skrill'] = $this->settings_model->getSkrillSettings();

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('skrill_settings')));
            $meta = array('page_title' => lang('skrill_settings'), 'bc' => $bc);
            $this->page_construct('settings/skrill', $meta, $this->data);
        }
    }

    function change_logo()
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->load->helper('security');
        $this->form_validation->set_rules('site_logo', lang("site_logo"), 'xss_clean');
        $this->form_validation->set_rules('biller_logo', lang("biller_logo"), 'xss_clean');
        if ($this->form_validation->run() == true) {

            if ($_FILES['site_logo']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path . 'logos/';
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = 300;
                $config['max_height'] = 80;
                $config['overwrite'] = FALSE;
                $config['max_filename'] = 25;
                //$config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('site_logo')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;

                $this->db->update('settings', array('logo2' => $photo), array('setting_id' => 1));

                $this->session->set_flashdata('message', lang('logo_uploaded'));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            if ($_FILES['biller_logo']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path . 'logos/';
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = 300;
                $config['max_height'] = 80;
                $config['overwrite'] = FALSE;
                $config['max_filename'] = 25;
                //$config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('biller_logo')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;

                $this->session->set_flashdata('message', lang('logo_uploaded'));
                redirect($_SERVER["HTTP_REFERER"]);

            }

            $this->session->set_flashdata('error', lang('attempt_failed'));
            redirect($_SERVER["HTTP_REFERER"]);
            die();
        } elseif ($this->input->post('upload_logo')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/change_logo', $this->data);
        }
    }

    public function write_index($timezone)
    {

        $template_path = './assets/config_dumps/index.php';
        $output_path = SELF;
        $index_file = file_get_contents($template_path);
        $new = str_replace("%TIMEZONE%", $timezone, $index_file);
        $handle = fopen($output_path, 'w+');
        @chmod($output_path, 0777);

        if (is_writable($output_path)) {
            if (fwrite($handle, $new)) {
                @chmod($output_path, 0644);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function updates()
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->form_validation->set_rules('purchase_code', lang("purchase_code"), 'required');
        $this->form_validation->set_rules('envato_username', lang("envato_username"), 'required');
        if ($this->form_validation->run() == true) {
            $this->db->update('settings', array('purchase_code' => $this->input->post('purchase_code', TRUE), 'envato_username' => $this->input->post('envato_username', TRUE)), array('setting_id' => 1));
            redirect('system_settings/updates');
        } else {
            $fields = array('version' => $this->Settings->version, 'code' => $this->Settings->purchase_code, 'username' => $this->Settings->envato_username, 'site' => base_url());
            $this->load->helper('update');
            $protocol = is_https() ? 'https://' : 'http://';
            $updates = get_remote_contents($protocol.'cloudnet.com.kh/api/v1/update/', $fields);
            $this->data['updates'] = json_decode($updates);
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('updates')));
            $meta = array('page_title' => lang('updates'), 'bc' => $bc);
            $this->page_construct('settings/updates', $meta, $this->data);
        }
    }

    function install_update($file, $m_version, $version)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->load->helper('update');
        save_remote_file($file . '.zip');
        $this->erp->unzip('./files/updates/' . $file . '.zip');
        if ($m_version) {
            $this->load->library('migration');
            if (!$this->migration->latest()) {
                $this->session->set_flashdata('error', $this->migration->error_string());
                redirect("system_settings/updates");
            }
        }
        $this->db->update('settings', array('version' => $version, 'update' => 0), array('setting_id' => 1));
        unlink('./files/updates/' . $file . '.zip');
        $this->session->set_flashdata('success', lang('update_done'));
        redirect("system_settings/updates");
    }

    function backups()
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->data['files'] = glob('./files/backups/*.zip', GLOB_BRACE);
        $this->data['dbs'] = glob('./files/backups/*.txt', GLOB_BRACE);
        krsort($this->data['files']); krsort($this->data['dbs']);
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('backups')));
        $meta = array('page_title' => lang('backups'), 'bc' => $bc);
        $this->page_construct('settings/backups', $meta, $this->data);
    }

    function backup_database()
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->load->dbutil();
        $prefs = array(
            'format' => 'txt',
            'filename' => 'erp_db_backup.sql'
        );
        $back = $this->dbutil->backup($prefs);
        $backup =& $back;
        $db_name = 'db-backup-on-' . date("Y-m-d-H-i-s") . '.txt';
        $save = './files/backups/' . $db_name;
        $this->load->helper('file');
        write_file($save, $backup);
        $this->session->set_flashdata('messgae', lang('db_saved'));
        redirect("system_settings/backups");
    }

    function backup_files()
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $name = 'file-backup-' . date("Y-m-d-H-i-s");
        $this->erp->zip("./", './files/backups/', $name);
        $this->session->set_flashdata('messgae', lang('backup_saved'));
        redirect("system_settings/backups");
        exit();
    }

    function restore_database($dbfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }

		$backup = read_file('./files/backups/' . $dbfile . '.txt');
		$trigger = file_get_contents('./files/iclouderp_trigger.sql');

		if($this->executeQueryFile($backup)){
			$this->db->conn_id->multi_query($trigger);
			$this->db->conn_id->close();
			echo json_encode(TRUE);
		}else{
			echo json_encode(FALSE);
		}
		
		/*
		$backup_trigger = file_get_contents('./files/iclouderp_trigger.sql');
		if(!$backup_trigger){
			$this->session->set_flashdata('warning', 'Trigger not found!');
            redirect($_SERVER["HTTP_REFERER"]);
		} else {
			$this->db->query($backup_trigger);
		}
		*/

		/*
        $file = file_get_contents('./files/backups/' . $dbfile . '.txt');
        $this->db->conn_id->multi_query($file);        
        $this->db->conn_id->close();
        
        $trigger = file_get_contents('./files/iclouderp_trigger.txt');
        $this->db->conn_id->multi_query($trigger);
        $this->db->conn_id->close();
		*/
        //redirect('logout/db');
    }
	
	function executeQueryFile($sql_file) {
		if (!$sql_file) {
		  return false;
		}
		$sql_clean = '';
		foreach (explode("\n", $sql_file) as $line){
			if(isset($line[0]) && $line[0] != "#"){
				$sql_clean .= $line."\n";
			}
		}
		//echo $sql_clean;
		foreach (explode(";\n", $sql_clean) as $sql){
			$sql = trim($sql);
			//echo  $sql.'<br/>============<br/>';
			if($sql)
			{
				$this->db->query($sql);
			}
		}
		return true;
	}

    function download_database($dbfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->load->library('zip');
        $this->zip->read_file('./files/backups/' . $dbfile . '.txt');
        $name = $dbfile . '.zip';
        $this->zip->download($name);
        exit();
    }

    function download_backup($zipfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->load->helper('download');
        force_download('./files/backups/' . $zipfile . '.zip', NULL);
        exit();
    }

    function restore_backup($zipfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $file = './files/backups/' . $zipfile . '.zip';
        $this->erp->unzip($file, './');
        $this->session->set_flashdata('success', lang('files_restored'));
        redirect("system_settings/backups");
        exit();
    }

    function delete_database($dbfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        unlink('./files/backups/' . $dbfile . '.txt');
        $this->session->set_flashdata('messgae', lang('db_deleted'));
        redirect("system_settings/backups");
    }

    function delete_backup($zipfile)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        unlink('./files/backups/' . $zipfile . '.zip');
        $this->session->set_flashdata('messgae', lang('backup_deleted'));
        redirect("system_settings/backups");
    }

    function email_templates($template = "credentials")
    {

        $this->form_validation->set_rules('mail_body', lang('mail_message'), 'trim|required');
        $this->load->helper('file');
        $temp_path = is_dir('./themes/' . $this->theme . 'email_templates/');
        $theme = $temp_path ? $this->theme : 'default';
        if ($this->form_validation->run() == true) {
            $data = $_POST["mail_body"];
            if (write_file('./themes/' . $this->theme . 'email_templates/' . $template . '.html', $data)) {
                $this->session->set_flashdata('message', lang('message_successfully_saved'));
                redirect('system_settings/email_templates#' . $template);
            } else {
                $this->session->set_flashdata('error', lang('failed_to_save_message'));
                redirect('system_settings/email_templates#' . $template);
            }
        } else {

            $this->data['credentials'] = file_get_contents('./themes/' . $this->theme . 'email_templates/credentials.html');
            $this->data['sale'] = file_get_contents('./themes/' . $this->theme . 'email_templates/sale.html');
            $this->data['quote'] = file_get_contents('./themes/' . $this->theme . 'email_templates/quote.html');
            $this->data['purchase'] = file_get_contents('./themes/' . $this->theme . 'email_templates/purchase.html');
            $this->data['transfer'] = file_get_contents('./themes/' . $this->theme . 'email_templates/transfer.html');
            $this->data['payment'] = file_get_contents('./themes/' . $this->theme . 'email_templates/payment.html');
            $this->data['forgot_password'] = file_get_contents('./themes/' . $this->theme . 'email_templates/forgot_password.html');
            $this->data['activate_email'] = file_get_contents('./themes/' . $this->theme . 'email_templates/activate_email.html');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('email_templates')));
            $meta = array('page_title' => lang('email_templates'), 'bc' => $bc);
            $this->page_construct('settings/email_templates', $meta, $this->data);
        }
    }

    function create_group()
    {
        $this->form_validation->set_rules('group_name', lang('group_name'), 'required|alpha_dash');
        //$this->form_validation->set_rules('description', lang('group_description'), 'xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $data = array('name' => strtolower($this->input->post('group_name')), 'description' => $this->input->post('description'));
            $new_group_id = $this->settings_model->addGroup($data);
            if ($new_group_id) {
                $this->session->set_flashdata('message', lang('group_added'));
                redirect("system_settings/permissions/" . $new_group_id);
            }
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['group_name'] = array(
                'name' => 'group_name',
                'id' => 'group_name',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('group_name'),
            );
            $this->data['description'] = array(
                'name' => 'description',
                'id' => 'description',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('description'),
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/create_group', $this->data);
        }
    }

    function edit_group($id)
    {
        if (!$id || empty($id)) {
            redirect('system_settings/user_groups');
        }

        $group = $this->settings_model->getGroupByID($id);

        $this->form_validation->set_rules('group_name', lang('group_name'), 'required|alpha_dash');

        if ($this->form_validation->run() === TRUE) {
            $data = array('name' => strtolower($this->input->post('group_name')), 'description' => $this->input->post('description'));
            $group_update = $this->settings_model->updateGroup($id, $data);

            if ($group_update) {
                $this->session->set_flashdata('message', lang('group_udpated'));
            } else {
                $this->session->set_flashdata('error', lang('attempt_failed'));
            }
            redirect("system_settings/user_groups");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['group'] = $group;

            $this->data['group_name'] = array(
                'name' => 'group_name',
                'id' => 'group_name',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('group_name', $group->name),
            );
            $this->data['group_description'] = array(
                'name' => 'group_description',
                'id' => 'group_description',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('group_description', $group->description),
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_group', $this->data);
        }
    }

    function permissions($id = NULL)
    {
        $this->form_validation->set_rules('group', lang("group"), 'is_natural_no_zero');
        if ($this->form_validation->run() == true) {

            $data = array(
                'products-index' => $this->input->post('products-index'),
                'products-edit' => $this->input->post('products-edit'),
                'products-add' => $this->input->post('products-add'),
                'products-delete' => $this->input->post('products-delete'),
                'products-cost' => $this->input->post('products-cost'),
                'products-price' => $this->input->post('products-price'),
				'products-import' => $this->input->post('products-import'),
                'products-export' => $this->input->post('products-export'),
                'customers-index' => $this->input->post('customers-index'),
                'customers-edit' => $this->input->post('customers-edit'),
                'customers-add' => $this->input->post('customers-add'),
                'customers-delete' => $this->input->post('customers-delete'),
				'customers-import' => $this->input->post('customers-import'),
                'customers-export' => $this->input->post('customers-export'),
                'suppliers-index' => $this->input->post('suppliers-index'),
                'suppliers-edit' => $this->input->post('suppliers-edit'),
                'suppliers-add' => $this->input->post('suppliers-add'),
                'suppliers-delete' => $this->input->post('suppliers-delete'),
				'suppliers-import' => $this->input->post('suppliers-import'),
                'suppliers-export' => $this->input->post('suppliers-export'),
                'sales-index' => $this->input->post('sales-index'),
                'sales-edit' => $this->input->post('sales-edit'),
                'sales-add' => $this->input->post('sales-add'),
                'sales-delete' => $this->input->post('sales-delete'),
                'sales-email' => $this->input->post('sales-email'),
                'sales-pdf' => $this->input->post('sales-pdf'),
				'sales-import' => $this->input->post('sales-import'),
                'sales-export' => $this->input->post('sales-export'),
                'sales-deliveries' => $this->input->post('sales-deliveries'),
                'sales-edit_delivery' => $this->input->post('sales-edit_delivery'),
                'sales-add_delivery' => $this->input->post('sales-add_delivery'),
                'sales-delete_delivery' => $this->input->post('sales-delete_delivery'),
                'sales-email_delivery' => $this->input->post('sales-email_delivery'),
                'sales-pdf_delivery' => $this->input->post('sales-pdf_delivery'),
				'sales-import_delivery' => $this->input->post('sales-import_delivery'),
                'sales-export_delivery' => $this->input->post('sales-export_delivery'),
                'sales-gift_cards' => $this->input->post('sales-gift_cards'),
                'sales-edit_gift_card' => $this->input->post('sales-edit_gift_card'),
                'sales-add_gift_card' => $this->input->post('sales-add_gift_card'),
                'sales-delete_gift_card' => $this->input->post('sales-delete_gift_card'),
				'sales-import_gift_card' => $this->input->post('sales-import_gift_card'),
                'sales-export_gift_card' => $this->input->post('sales-export_gift_card'),
				'sales-loan' => $this->input->post('sales-loan'),
				'sales-return_sales' => $this->input->post('sales-return_sales'),
                'quotes-index' => $this->input->post('quotes-index'),
                'quotes-edit' => $this->input->post('quotes-edit'),
                'quotes-add' => $this->input->post('quotes-add'),
                'quotes-delete' => $this->input->post('quotes-delete'),
                'quotes-email' => $this->input->post('quotes-email'),
                'quotes-pdf' => $this->input->post('quotes-pdf'),
				'quotes-import' => $this->input->post('quotes-import'),
                'quotes-export' => $this->input->post('quotes-export'),
				'quotes-approve' => $this->input->post('quotes-approve'),
				'quotes-pending_for_PO' => $this->input->post('quotes-pending_for_PO'),
				'quotes-rejected' => $this->input->post('quotes-rejected'),
				'quotes-transfer' => $this->input->post('quotes-transfer'),
				'quotes-edit_transfer' => $this->input->post('quotes-edit_transfer'),
				'quotes-co_transfer' => $this->input->post('quotes-co_transfer'),
				
				
                'purchases-index' => $this->input->post('purchases-index'),
                'purchases-edit' => $this->input->post('purchases-edit'),
                'purchases-add' => $this->input->post('purchases-add'),
                'purchases-delete' => $this->input->post('purchases-delete'),
                'purchases-email' => $this->input->post('purchases-email'),
                'purchases-pdf' => $this->input->post('purchases-pdf'),
				'purchases-import' => $this->input->post('purchases-import'),
                'purchases-export' => $this->input->post('purchases-export'),
                'transfers-index' => $this->input->post('transfers-index'),
                'transfers-edit' => $this->input->post('transfers-edit'),
                'transfers-add' => $this->input->post('transfers-add'),
                'transfers-delete' => $this->input->post('transfers-delete'),
                'transfers-email' => $this->input->post('transfers-email'),
                'transfers-pdf' => $this->input->post('transfers-pdf'),
				'transfers-import' => $this->input->post('transfers-import'),
                'transfers-export' => $this->input->post('transfers-export'),
				
                'reports-quantity_alerts' => $this->input->post('reports-quantity_alerts'),
                'reports-expiry_alerts' => $this->input->post('reports-expiry_alerts'),
                'reports-products' => $this->input->post('reports-products'),
                'reports-daily_sales' => $this->input->post('reports-daily_sales'),
                'reports-monthly_sales' => $this->input->post('reports-monthly_sales'),
                'reports-payments' => $this->input->post('reports-payments'),
                'reports-sales' => $this->input->post('reports-sales'),
                'reports-purchases' => $this->input->post('reports-purchases'),
                'reports-customers' => $this->input->post('reports-customers'),
                'reports-suppliers' => $this->input->post('reports-suppliers'),
				
                
				'sales-payments' => $this->input->post('sales-payments'),
                'purchases-payments' => $this->input->post('purchases-payments'),
                'purchases-expenses' => $this->input->post('purchases-expenses'),
                'products-adjustments' => $this->input->post('products-adjustments'),
                'bulk_actions' => $this->input->post('bulk_actions'),
                'customers-deposits' => $this->input->post('customers-deposits'),
                'customers-delete_deposit' => $this->input->post('customers-delete_deposit'),
				'reports-profit_loss' => $this->input->post('reports-profit_loss'),
				
				'accounts-index' => $this->input->post('accounts-index'),
				'accounts-add' => $this->input->post('accounts-add'),
				'accounts-edit' => $this->input->post('accounts-edit'),
				'accounts-delete' => $this->input->post('accounts-delete'),
				'accounts-import' => $this->input->post('accounts-import'),
				'accounts-export' => $this->input->post('accounts-export'),
				'deposit-add' => $this->input->post('deposit-add'),
				'disbursement-add' => $this->input->post('disbursement-add'),
				'account-list_receivable' => $this->input->post('account-list_receivable'),
				'account-list_ar_aging' => $this->input->post('account-list_ar_aging'),
				'account-bill_receipt' => $this->input->post('account-bill_receipt'),
				'account-list_ac_payable' => $this->input->post('account-list_ac_payable'),
				'account-list_ap_aging' => $this->input->post('account-list_ap_aging'),
				'account-bill_payable' => $this->input->post('account-bill_payable'),
				'account-list_ac_head' => $this->input->post('account-list_ac_head'),
				'account-add_ac_head' => $this->input->post('account-add_ac_head'),
				'account-settings' => $this->input->post('account-settings'),
				
				'reports-account'=>$this->input->post('reports-account'),
				'reports-index'=>$this->input->post('overview-chart'),
                'reports-operation'=>$this->input->post('reports-operation'),
                'reports-underwriting'=>$this->input->post('reports-underwriting'),
                'reports-back_office'=>$this->input->post('reports-back_office'),
                'reports-collection'=>$this->input->post('reports-collection'),
				'simulation-index'=>$this->input->post('simulation-index'),
				'field_check-add'=>$this->input->post('field_check-add'),
				'advance-approve'=>$this->input->post('advance-approve'),
				
				'down_payment-index'=>$this->input->post('down_payment-index'),
				'down_payment-add'=>$this->input->post('down_payment-add'),
				'down_payment-edit'=>$this->input->post('down_payment-edit'),
				'down_payment-delete'=>$this->input->post('down_payment-delete'),
				'down_payment-import'=>$this->input->post('down_payment-import'),
				'down_payment-export'=>$this->input->post('down_payment-export'),
				
				'contract-index'=>$this->input->post('contract-index'),
				'contract-add'=>$this->input->post('contract-add'),
				'contract-edit'=>$this->input->post('contract-edit'),
				'contract-delete'=>$this->input->post('contract-delete'),
				'contract-import'=>$this->input->post('contract-import'),
				'contract-export'=>$this->input->post('contract-export'),
				
				'collection-index'=>$this->input->post('collection-index'),
				'collection-add'=>$this->input->post('collection-add'),
				'collection-edit'=>$this->input->post('collection-edit'),
				'collection-delete'=>$this->input->post('collection-delete'),
				'collection-import'=>$this->input->post('collection-import'),
				'collection-export'=>$this->input->post('collection-export'),
				
				'installment_payment-index'=>$this->input->post('installment_payment-index'),
				'installment_payment-add'=>$this->input->post('installment_payment-add'),
				'installment_payment-edit'=>$this->input->post('installment_payment-edit'),
				'installment_payment-delete'=>$this->input->post('installment_payment-delete'),
				'installment_payment-import'=>$this->input->post('installment_payment-import'),
				'installment_payment-export'=>$this->input->post('installment_payment-export'),
				'daily_cash_collection-index'=>$this->input->post('daily_cash_collection-index'),
				'installment-payment_voucher'=>$this->input->post('installment-payment_voucher'),
				
				'reject-index'=>$this->input->post('rejected-index'),
				'reject-add'=>$this->input->post('rejected-add'),
				'reject-edit'=>$this->input->post('rejected-edit'),
				'reject-delete'=>$this->input->post('rejected-delete'),
				'reject-import'=>$this->input->post('rejected-import'),
				'reject-export'=>$this->input->post('rejected-export'),
				
				'reports-quote'=>$this->input->post('reports-quote'),
				'reports-contract'=>$this->input->post('reports-contract'),
				'reports-installment'=>$this->input->post('reports-installment'),
				'reports-daily_loan'=>$this->input->post('reports-daily_loan'),
				'reports-summary_chart'=>$this->input->post('reports-summary_chart'),
				'report-daily_applicant'=>$this->input->post('report-daily_applicant'),
				'reports-daily_register'=>$this->input->post('reports-daily_register'),
				'reports-applicant'=>$this->input->post('reports-applicant'),
				'reports-contract_excel'=>$this->input->post('reports-contract_excel'),
				'reports-installments'=>$this->input->post('reports-installments'),
				'reports-ledger'=>$this->input->post('reports-ledger'),
				'reports-trial_balance'=>$this->input->post('reports-trial_balance'),
				'reports-balance_sheet'=>$this->input->post('reports-balance_sheet'),
				'reports-income_statement'=>$this->input->post('reports-income_statement'),
				'reports-cash_books'=>$this->input->post('reports-cash_books'),
				'reports-nbc'=>$this->input->post('reports-nbc'),
				'reports-loans'=>$this->input->post('reports-loans'),
				'reports-daily_transaction'=>$this->input->post('reports-daily_transaction'),
				'reports-daily_cash'=>$this->input->post('reports-daily_cash'),
				
				'add-draft' => $this->input->post('add-draft'),
				'view-draft' => $this->input->post('view-draft'),
				
				'branch-index'=>$this->input->post('branch-index'),
				'branch-add'=>$this->input->post('branch-add'),
				'branch-edit'=>$this->input->post('branch-edit'),
				'branch-delete'=>$this->input->post('branch-delete'),
				'branch-import'=>$this->input->post('branch-import'),
				'branch-export'=>$this->input->post('branch-export'),
				'branch-capital'=>$this->input->post('branch-capital'),
				
				'payment-index'=>$this->input->post('payment-index'),
				'payment-add'=>$this->input->post('payment-add'),
				'payment-edit'=>$this->input->post('payment-edit'),
				'payment-delete'=>$this->input->post('payment-delete'),
				'payment-import'=>$this->input->post('payment-import'),
				'payment-export'=>$this->input->post('payment-export'),
				
				'money_saving-index'=>$this->input->post('money_saving-index'),
				'money_saving-add'=>$this->input->post('money_saving-add'),
				'money_saving-edit'=>$this->input->post('money_saving-edit'),
				'money_saving-delete'=>$this->input->post('money_saving-delete'),
				'money_saving-import'=>$this->input->post('money_saving-import'),
				'money_saving-export'=>$this->input->post('money_saving-export'),
				'money_saving-withdrawal'=>$this->input->post('money_saving-withdrawal'),
				'money_saving-compulsory'=>$this->input->post('money_saving-compulsory'),
				
				'completed-index'=>$this->input->post('completed-index'),
            );
			//$this->erp->print_arrays($data);
            if (POS) {
                $data['pos-index'] = $this->input->post('pos-index');
            }
            //$this->erp->print_arrays($data);
        }

        if ($this->form_validation->run() == true && $this->settings_model->updatePermissions($id, $data)) {
            $this->session->set_flashdata('message', lang("group_permissions_updated"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

            $this->data['id'] = $id;
            $this->data['p'] = $this->settings_model->getGroupPermissions($id);
            $this->data['group'] = $this->settings_model->getGroupByID($id);

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('group_permissions')));
            $meta = array('page_title' => lang('group_permissions'), 'bc' => $bc);
            $this->page_construct('settings/permissions', $meta, $this->data);
        }
    }

    function user_groups()
    {

        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect('auth');
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $this->data['groups'] = $this->settings_model->getGroups();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('groups')));
        $meta = array('page_title' => lang('groups'), 'bc' => $bc);
        $this->page_construct('settings/user_groups', $meta, $this->data);
    }

    function delete_group($id = NULL)
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect('welcome', 'refresh');
        }

        if ($this->settings_model->checkGroupUsers($id)) {
            $this->session->set_flashdata('error', lang("group_x_b_deleted"));
            redirect("system_settings/user_groups");
        }

        if ($this->settings_model->deleteGroup($id)) {
            $this->session->set_flashdata('message', lang("group_deleted"));
            redirect("system_settings/user_groups");
        }
    }

    function currencies()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('currencies')));
        $meta = array('page_title' => lang('currencies'), 'bc' => $bc);
        $this->page_construct('settings/currencies', $meta, $this->data);
    }

    function getCurrencies()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, code, name, name_other, rate")
            ->from("currencies")
			->add_column("Actions", "<center><a href='" . site_url('system_settings/edit_currency/$1') . "' class='tip' title='" . lang("edit_currency") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> </center>", "id");
            //->add_column("Actions", "<center><a href='" . site_url('system_settings/edit_currency/$1') . "' class='tip' title='" . lang("edit_currency") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_currency") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_currency/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id");
        //->unset_column('id');

        echo $this->datatables->generate();
    }

    function add_currency()
    {
        $this->form_validation->set_rules('code', lang("currency_code"), 'trim|is_unique[currencies.code]|required');
        $this->form_validation->set_rules('name', lang("name"), 'required');
        $this->form_validation->set_rules('rate', lang("exchange_rate"), 'required|numeric');

        if ($this->form_validation->run() == true) {
            $data = array('code'   => $this->input->post('code'),
                'name' 			   => $this->input->post('name'),
				'name_other' 	   => $this->input->post('name_other'),
                'rate'			   => $this->input->post('rate'),
				'currency_type'    => ($this->input->post('hundred')?$this->input->post('hundred'):$this->input->post('no_decimal'))?:$this->input->post('decimal')?:$this->input->post('kyat_round'),
				'currency_status'  => (($this->input->post('hundred')? 'Hundred Round':'')?($this->input->post('hundred')? 'Hundred Round':''):($this->input->post('no_decimal')? 'No Decimal':''))?:($this->input->post('decimal')? 'Decimal Round':'')?:($this->input->post('kyat_round')? 'Kyat Round':''),
            );
			 //$this->erp->print_arrays($data);
        } elseif ($this->input->post('add_currency')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/currencies");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addCurrency($data)) { //check to see if we are creating the customer
            $this->session->set_flashdata('message', lang("currency_added"));
            redirect("system_settings/currencies");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['page_title'] = lang("new_currency");
            $this->load->view($this->theme . 'settings/add_currency', $this->data);
        }
    }

    function edit_currency($id = NULL)
    {

        $this->form_validation->set_rules('code', lang("currency_code"), 'trim|required');
        $cur_details = $this->settings_model->getCurrencyByID($id);
        if ($this->input->post('code') != $cur_details->code) {
            $this->form_validation->set_rules('code', lang("currency_code"), 'is_unique[currencies.code]');
        }
        $this->form_validation->set_rules('name', lang("currency_name"), 'required');
        $this->form_validation->set_rules('rate', lang("exchange_rate"), 'required|numeric');

        if ($this->form_validation->run() == true) {

            $data = array('code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
				'name_other' => $this->input->post('name_other'),
                'rate' => $this->input->post('rate'),
				//'currency_type'    => ($this->input->post('hundred')?$this->input->post('hundred'):$this->input->post('no_decimal'))?:$this->input->post('decimal'),
				//'currency_status'  => (($this->input->post('hundred')? 'Hundred Round':'')?($this->input->post('hundred')? 'Hundred Round':''):($this->input->post('no_decimal')? 'No Decimal':''))?:($this->input->post('decimal')? 'Decimal Round':''),
				'currency_type'    => ($this->input->post('hundred')?$this->input->post('hundred'):$this->input->post('no_decimal'))?:$this->input->post('decimal')?:$this->input->post('kyat_round'),
				'currency_status'  => (($this->input->post('hundred')? 'Hundred Round':'')?($this->input->post('hundred')? 'Hundred Round':''):($this->input->post('no_decimal')? 'No Decimal':''))?:($this->input->post('decimal')? 'Decimal Round':'')?:($this->input->post('kyat_round')? 'Kyat Round':''),
		   );
		  
        } elseif ($this->input->post('edit_currency')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/currencies");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateCurrency($id, $data)) { //check to see if we are updateing the customer
            $this->session->set_flashdata('message', lang("currency_updated"));
            redirect("system_settings/currencies");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['currency'] = $this->settings_model->getCurrencyByID($id);
            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_currency', $this->data);
        }
    }

    function delete_currency($id = NULL)
    {

        if ($this->settings_model->deleteCurrency($id)) {
            echo lang("currency_deleted");
        }
    }

    function currency_actions()
    {
        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteCurrency($id);
                    }
                    $this->session->set_flashdata('message', lang("currencies_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('currencies'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('currency_code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('currency_name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('exchange_rate'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sc = $this->settings_model->getCurrencyByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $sc->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sc->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sc->rate);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'currencies_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', lang("no_tax_rate_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    
	}

    function categories()
    {
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('categories')));
        $meta = array('page_title' => lang('categories'), 'bc' => $bc);
        $this->page_construct('settings/categories', $meta, $this->data);
    }

    function getCategories()
    {

        $print_barcode = anchor('products/print_barcodes/?category=$1', '<i class="fa fa-print"></i>', 'title="'.lang('print_barcodes').'" class="tip"');

        $this->load->library('datatables');
        $this->datatables
            ->select("id, image, code, name")
            ->from("categories")
			->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/subcategories/$1') . "' class='tip' title='" . lang("list_subcategories") . "'><i class=\"fa fa-list\"></i></a> ".$print_barcode." <a href='" . site_url('system_settings/edit_category/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_category") . "'><i class=\"fa fa-edit\"></i></a> </div>", "id");
            //->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/subcategories/$1') . "' class='tip' title='" . lang("list_subcategories") . "'><i class=\"fa fa-list\"></i></a> ".$print_barcode." <a href='" . site_url('system_settings/edit_category/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_category") . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_category") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_category/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");

        echo $this->datatables->generate();
    }

    function add_category()
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("category_code"), 'trim|is_unique[categories.code]|required');
        $this->form_validation->set_rules('name', lang("name"), 'required|min_length[3]');
        $this->form_validation->set_rules('userfile', lang("category_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $name = $this->input->post('name');
            $code = $this->input->post('code');
			$mfi = $this->input->post('mfi');
			//$group_loan = $this->input->post('group_loan');
			
            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                //$data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                if ($this->Settings->watermark) {
                    $this->image_lib->clear();
                    $wm['source_image'] = $this->upload_path . $photo;
                    $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                    $wm['wm_type'] = 'text';
                    $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                    $wm['quality'] = '100';
                    $wm['wm_font_size'] = '16';
                    $wm['wm_font_color'] = '999999';
                    $wm['wm_shadow_color'] = 'CCCCCC';
                    $wm['wm_vrt_alignment'] = 'top';
                    $wm['wm_hor_alignment'] = 'right';
                    $wm['wm_padding'] = '10';
                    $this->image_lib->initialize($wm);
                    $this->image_lib->watermark();
                }
                $this->image_lib->clear();
                $config = NULL;
            } else {
                $photo = NULL;
            }
        } elseif ($this->input->post('add_category')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/categories");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addCategory($name, $code, $photo, $mfi)) {
            $this->session->set_flashdata('message', lang("category_added"));
				redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('code'),
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_category', $this->data);
        }
    }

    function edit_category($id = NULL)
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("category_code"), 'trim|required');
        $pr_details = $this->settings_model->getCategoryByID($id);
        if ($this->input->post('code') != $pr_details->code) {
            $this->form_validation->set_rules('code', lang("category_code"), 'is_unique[categories.code]');
        }
        $this->form_validation->set_rules('name', lang("category_name"), 'required|min_length[3]');
        $this->form_validation->set_rules('userfile', lang("category_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            $data = array('code' => $this->input->post('code'),
                'name' 		 => $this->input->post('name'),
				'mfi'		 => $this->input->post('mfi'),
				//'group_loan' => $this->input->post('group_loan'),
            );
            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                //$data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                if ($this->Settings->watermark) {
                    $this->image_lib->clear();
                    $wm['source_image'] = $this->upload_path . $photo;
                    $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                    $wm['wm_type'] = 'text';
                    $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                    $wm['quality'] = '100';
                    $wm['wm_font_size'] = '16';
                    $wm['wm_font_color'] = '999999';
                    $wm['wm_shadow_color'] = 'CCCCCC';
                    $wm['wm_vrt_alignment'] = 'top';
                    $wm['wm_hor_alignment'] = 'right';
                    $wm['wm_padding'] = '10';
                    $this->image_lib->initialize($wm);
                    $this->image_lib->watermark();
                }
                $this->image_lib->clear();
                $config = NULL;
            } else {
                $photo = NULL;
            }
			
        } elseif ($this->input->post('edit_category')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/categories");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateCategory($id, $data, $photo)) {
            $this->session->set_flashdata('message', lang("category_updated"));
            redirect("system_settings/categories");
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $category = $this->settings_model->getCategoryByID($id);
            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('name', $category->name),
            );
            $this->data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('code', $category->code),
            );
			$this->data['mfi'] = $category->mfi;
			$this->data['group_loan'] = $category->group_loan;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['id'] = $id;
            $this->load->view($this->theme . 'settings/edit_category', $this->data);
        }
    }

    function delete_category($id = NULL)
    {

        if ($this->settings_model->getSubCategoriesByCategoryID($id)) {
            $this->session->set_flashdata('error', lang("category_has_subcategory"));
            redirect("system_settings/categories");
        }

        if ($this->settings_model->deleteCategory($id)) {
            echo lang("category_deleted");
        }
    }

    function category_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {
            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteCategory($id);
                    }
                    $this->session->set_flashdata('message', lang("categories_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('categories'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sc = $this->settings_model->getCategoryByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $sc->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sc->name);
                        $row++;
                    }
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'categories_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', lang("no_tax_rate_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function subcategories($parent_id = NULL)
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $this->data['parent_id'] = $parent_id;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => site_url('system_settings/categories'), 'page' => lang('categories')), array('link' => '#', 'page' => lang('subcategories')));
        $meta = array('page_title' => lang('subcategories'), 'bc' => $bc);
        $this->page_construct('settings/subcategories', $meta, $this->data);
    }

    function getSubcategories($parent_id = NULL)
    {
        $list_type = anchor('system_settings/type/$1', '<i class="fa fa-list"></i>', 'title="'.lang('list_model').'" class="tip"');
        $print_barcode = anchor('products/print_barcodes/?subcategory=$1', '<i class="fa fa-print"></i>', 'title="'.lang('print_barcodes').'" class="tip"');

        $this->load->library('datatables');
        $this->datatables
            ->select("subcategories.id as id, subcategories.image as image, subcategories.code as scode, subcategories.name as sname, categories.name as cname")
            ->from("subcategories")
            ->join('categories', 'categories.id = subcategories.category_id', 'left')
            ->group_by('subcategories.id');

        if ($parent_id) {
            $this->datatables->where('category_id', $parent_id);
        }
		$this->datatables->add_column("Actions", "<div class=\"text-center\">".$list_type.' '.$print_barcode." <a href='" . site_url('system_settings/edit_subcategory/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_subcategory") . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_subcategory") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p></div>", "id");
        //$this->datatables->add_column("Actions", "<div class=\"text-center\">".$list_type.' '.$print_barcode." <a href='" . site_url('system_settings/edit_subcategory/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_subcategory") . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_subcategory") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_subcategory/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        // ->unset_column('id');
        echo $this->datatables->generate();
    }

    function add_subcategory($parent_id = NULL)
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('category', lang("main_category"), 'required');
        $this->form_validation->set_rules('code', lang("subcategory_code"), 'trim|is_unique[categories.code]|is_unique[subcategories.code]|required');
        $this->form_validation->set_rules('name', lang("subcategory_name"), 'required|min_length[2]');
        $this->form_validation->set_rules('userfile', lang("category_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $name = $this->input->post('name');
            $code = $this->input->post('code');
            $category = $this->input->post('category');
            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                //$data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                if ($this->Settings->watermark) {
                    $this->image_lib->clear();
                    $wm['source_image'] = $this->upload_path . $photo;
                    $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                    $wm['wm_type'] = 'text';
                    $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                    $wm['quality'] = '100';
                    $wm['wm_font_size'] = '16';
                    $wm['wm_font_color'] = '999999';
                    $wm['wm_shadow_color'] = 'CCCCCC';
                    $wm['wm_vrt_alignment'] = 'top';
                    $wm['wm_hor_alignment'] = 'right';
                    $wm['wm_padding'] = '10';
                    $this->image_lib->initialize($wm);
                    $this->image_lib->watermark();
                }
                $this->image_lib->clear();
                $config = NULL;
            } else {
                $photo = NULL;
            }
        } elseif ($this->input->post('add_subcategory')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/subcategories");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addSubCategory($category, $name, $code, $photo)) {
            $this->session->set_flashdata('message', lang("subcategory_added"));
			if (strpos($_SERVER['HTTP_REFERER'], 'system_settings/add_category') !== false) {
				 redirect("system_settings/add_subcategory", 'refresh');
			}else{
				 redirect("system_settings/subcategories");
			}
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text', 'class' => 'form-control',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('code'),
            );
            $this->data['parent_id'] = $parent_id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['categories'] = $this->settings_model->getAllCategories();
            $this->load->view($this->theme . 'settings/add_subcategory', $this->data);
        }
    }

    function add_type($parent_id = NULL)
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('subcategory', lang("main_category"), 'required');
        $this->form_validation->set_rules('code', lang("code"), 'trim|is_unique[subcategories.code]|is_unique[type.code]|required');
        $this->form_validation->set_rules('name', lang("subcategory_name"), 'required|min_length[2]');

        if ($this->form_validation->run() == true) {
            $name = $this->input->post('name');
            $code = $this->input->post('code');
            $subcategory = $this->input->post('subcategory');
            $data = array(
				'subcategory_id' => $subcategory,
				'code'           => $code,
				'name'           => $name
			);
			//$this->erp->print_arrays($data);
        } elseif ($this->input->post('add_subcategory')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/type");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addType($data)) {
            $this->session->set_flashdata('message', lang("type_added"));
			if (strpos($_SERVER['HTTP_REFERER'], 'system_settings/add_category') !== false) {
				 redirect("system_settings/add_subcategory", 'refresh');
			}else{
				 redirect("system_settings/type/".$subcategory );
			}
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text', 'class' => 'form-control',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('code'),
            );
            $this->data['parent_id'] = $parent_id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['subcategories'] = $this->settings_model->getAllSubCategories();
            $this->load->view($this->theme . 'settings/add_type', $this->data);
        }
    }
	
    function edit_subcategory($id = NULL)
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('category', lang("main_category"), 'required');
        $this->form_validation->set_rules('code', lang("subcategory_code"), 'trim|required');
        $pr_details = $this->settings_model->getSubCategoryByID($id);
        if ($this->input->post('code') != $pr_details->code) {
            $this->form_validation->set_rules('code', lang("subcategory_code"), 'is_unique[categories.code]');
        }
        $this->form_validation->set_rules('name', lang("subcategory_name"), 'required|min_length[2]');
        $this->form_validation->set_rules('userfile', lang("category_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            $data = array(
                'category' => $this->input->post('category'),
                'code' => $this->input->post('code'),
                'name' => $this->input->post('name')
            );
            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                //$data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                if ($this->Settings->watermark) {
                    $this->image_lib->clear();
                    $wm['source_image'] = $this->upload_path . $photo;
                    $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                    $wm['wm_type'] = 'text';
                    $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                    $wm['quality'] = '100';
                    $wm['wm_font_size'] = '16';
                    $wm['wm_font_color'] = '999999';
                    $wm['wm_shadow_color'] = 'CCCCCC';
                    $wm['wm_vrt_alignment'] = 'top';
                    $wm['wm_hor_alignment'] = 'right';
                    $wm['wm_padding'] = '10';
                    $this->image_lib->initialize($wm);
                    $this->image_lib->watermark();
                }
                $this->image_lib->clear();
                $config = NULL;
            } else {
                $photo = NULL;
            }
        } elseif ($this->input->post('edit_subcategory')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/subcategories");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateSubCategory($id, $data, $photo)) {
            $this->session->set_flashdata('message', lang("subcategory_updated"));
            redirect("system_settings/subcategories");
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['subcategory'] = $this->settings_model->getSubCategoryByID($id);
            $this->data['categories'] = $this->settings_model->getAllCategories();
            $this->data['id'] = $id;
            $this->load->view($this->theme . 'settings/edit_subcategory', $this->data);
        }
    }

    function delete_subcategory($id = NULL)
    {

        if ($this->settings_model->deleteSubCategory($id)) {
            echo lang("subcategory_deleted");
        }
    }

    function subcategory_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteSubcategory($id);
                    }
                    $this->session->set_flashdata('message', lang("subcategories_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('subcategories'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('main_category'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sc = $this->settings_model->getSubcategoryDetails($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $sc->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sc->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sc->parent);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'subcategories_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', lang("no_tax_rate_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function tax_rates()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('tax_rates')));
        $meta = array('page_title' => lang('tax_rates'), 'bc' => $bc);
        $this->page_construct('settings/tax_rates', $meta, $this->data);
    }

    function getTaxRates()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, name, code, rate, type")
            ->from("tax_rates")
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/edit_tax_rate/$1') . "' class='tip' title='" . lang("edit_tax_rate") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_tax_rate") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_tax_rate/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id');

        echo $this->datatables->generate();
    }

    function add_tax_rate()
    {

        $this->form_validation->set_rules('name', lang("name"), 'trim|is_unique[tax_rates.name]|required');
        $this->form_validation->set_rules('code', lang("code"), 'required');
        $this->form_validation->set_rules('type', lang("type"), 'required');
        $this->form_validation->set_rules('rate', lang("tax_rate"), 'required|numeric');

        if ($this->form_validation->run() == true) {
            $data = array('name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'type' => $this->input->post('type'),
                'rate' => $this->input->post('rate'),
            );
        } elseif ($this->input->post('add_tax_rate')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/tax_rates");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addTaxRate($data)) {
            $this->session->set_flashdata('message', lang("tax_rate_added"));
            redirect("system_settings/tax_rates");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_tax_rate', $this->data);
        }
    }

    function edit_tax_rate($id = NULL)
    {

        $this->form_validation->set_rules('name', lang("name"), 'trim|required');
        $tax_details = $this->settings_model->getTaxRateByID($id);
        if ($this->input->post('name') != $tax_details->name) {
            $this->form_validation->set_rules('name', lang("name"), 'is_unique[tax_rates.name]');
        }
        $this->form_validation->set_rules('code', lang("code"), 'required');
        $this->form_validation->set_rules('type', lang("type"), 'required');
        $this->form_validation->set_rules('rate', lang("tax_rate"), 'required|numeric');

        if ($this->form_validation->run() == true) {

            $data = array('name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'type' => $this->input->post('type'),
                'rate' => $this->input->post('rate'),
            );
        } elseif ($this->input->post('edit_tax_rate')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/tax_rates");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateTaxRate($id, $data)) { //check to see if we are updateing the customer
            $this->session->set_flashdata('message', lang("tax_rate_updated"));
            redirect("system_settings/tax_rates");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['tax_rate'] = $this->settings_model->getTaxRateByID($id);

            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_tax_rate', $this->data);
        }
    }

    function delete_tax_rate($id = NULL)
    {
        if ($this->settings_model->deleteTaxRate($id)) {
            echo lang("tax_rate_deleted");
        }
    }

    function tax_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteTaxRate($id);
                    }
                    $this->session->set_flashdata('message', lang("tax_rates_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('tax_rates'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('tax_rate'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('type'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $tax = $this->settings_model->getTaxRateByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $tax->name);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $tax->code);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $tax->rate);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, ($tax->type == 1) ? lang('percentage') : lang('fixed'));
                        $row++;
                    }
                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'tax_rates_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', lang("no_tax_rate_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function customer_groups()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('customer_groups')));
        $meta = array('page_title' => lang('customer_groups'), 'bc' => $bc);
        $this->page_construct('settings/customer_groups', $meta, $this->data);
    }

    function getCustomerGroups()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, name, percent")
            ->from("customer_groups")
            ->add_column("Actions", "<center><a href='" . site_url('system_settings/edit_customer_group/$1') . "' class='tip' title='" . lang("edit_customer_group") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_customer_group") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_customer_group/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id");
        //->unset_column('id');

        echo $this->datatables->generate();
    }

    function add_customer_group()
    {

        $this->form_validation->set_rules('name', lang("group_name"), 'trim|is_unique[customer_groups.name]|required');
        $this->form_validation->set_rules('percent', lang("group_percentage"), 'required|numeric');

        if ($this->form_validation->run() == true) {
            $data = array('name' => $this->input->post('name'),
                'percent' => $this->input->post('percent'),
				'makeup_cost' => ($this->input->post('makeup_cost')? $this->input->post('makeup_cost'):0)
            );
        } elseif ($this->input->post('add_customer_group')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/customer_groups");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addCustomerGroup($data)) {
            $this->session->set_flashdata('message', lang("customer_group_added"));
            redirect("system_settings/customer_groups");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_customer_group', $this->data);
        }
    }

    function edit_customer_group($id = NULL)
    {

        $this->form_validation->set_rules('name', lang("group_name"), 'trim|required');
        $pg_details = $this->settings_model->getCustomerGroupByID($id);
        if ($this->input->post('name') != $pg_details->name) {
            $this->form_validation->set_rules('name', lang("group_name"), 'is_unique[tax_rates.name]');
        }
        $this->form_validation->set_rules('percent', lang("group_percentage"), 'required|numeric');

        if ($this->form_validation->run() == true) {

            $data = array('name' => $this->input->post('name'),
                'percent' => $this->input->post('percent'),
				'makeup_cost' => $this->input->post('makeup_cost')
            );
        } elseif ($this->input->post('edit_customer_group')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/customer_groups");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateCustomerGroup($id, $data)) {
            $this->session->set_flashdata('message', lang("customer_group_updated"));
            redirect("system_settings/customer_groups");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['customer_group'] = $this->settings_model->getCustomerGroupByID($id);

            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_customer_group', $this->data);
        }
    }

    function delete_customer_group($id = NULL)
    {
        if ($this->settings_model->deleteCustomerGroup($id)) {
            echo lang("customer_group_deleted");
        }
    }

    function customer_group_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteCustomerGroup($id);
                    }
                    $this->session->set_flashdata('message', lang("customer_groups_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('tax_rates'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('group_name'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('group_percentage'));
                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $pg = $this->settings_model->getCustomerGroupByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $pg->name);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $pg->percent);
                        $row++;
                    }
                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'customer_groups_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', lang("no_customer_group_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function warehouses()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('warehouses')));
        $meta = array('page_title' => lang('warehouses'), 'bc' => $bc);
        $this->page_construct('settings/warehouses', $meta, $this->data);
    }

    function getWarehouses()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, map, code, name, phone, email, address")
            ->from("warehouses")
            //->edit_column("map", base_url().'assets/uploads/$1', 'map')
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/edit_warehouse/$1') . "' class='tip' title='" . lang("edit_warehouse") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_warehouse") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_warehouse/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id')
        //->unset_column('map');

        echo $this->datatables->generate();
    }

    function add_warehouse()
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("code"), 'trim|is_unique[warehouses.code]|required');
        $this->form_validation->set_rules('name', lang("name"), 'required');
        $this->form_validation->set_rules('address', lang("address"), 'required');
        $this->form_validation->set_rules('userfile', lang("map_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '1024';
                $config['max_width'] = '2000';
                $config['max_height'] = '2000';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('message', $error);
                    redirect("system_settings/warehouses");
                }

                $map = $this->upload->file_name;

                $this->load->helper('file');
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'assets/uploads/' . $map;
                $config['new_image'] = 'assets/uploads/thumbs/' . $map;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 76;
                $config['height'] = 76;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
            } else {
                $map = NULL;
            }
            $data = array('code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'map' => $map,
            );
        } elseif ($this->input->post('add_warehouse')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/warehouses");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addWarehouse($data)) {
            $this->session->set_flashdata('message', lang("warehouse_added"));
            redirect("system_settings/warehouses");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_warehouse', $this->data);
        }
    }

    function edit_warehouse($id = NULL)
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("code"), 'trim|required');
        $wh_details = $this->settings_model->getWarehouseByID($id);
        if ($this->input->post('code') != $wh_details->code) {
            $this->form_validation->set_rules('code', lang("code"), 'is_unique[warehouses.code]');
        }
        $this->form_validation->set_rules('address', lang("address"), 'required');
        $this->form_validation->set_rules('map', lang("map_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $data = array('code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
            );

            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = '1024';
                $config['max_width'] = '2000';
                $config['max_height'] = '2000';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('message', $error);
                    redirect("system_settings/warehouses");
                }

                $data['map'] = $this->upload->file_name;

                $this->load->helper('file');
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'assets/uploads/' . $data['map'];
                $config['new_image'] = 'assets/uploads/thumbs/' . $data['map'];
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 76;
                $config['height'] = 76;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
            }
        } elseif ($this->input->post('edit_warehouse')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/warehouses");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateWarehouse($id, $data)) { //check to see if we are updateing the customer
            $this->session->set_flashdata('message', lang("warehouse_updated"));
            redirect("system_settings/warehouses");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['warehouse'] = $this->settings_model->getWarehouseByID($id);

            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_warehouse', $this->data);
        }
    }

    function delete_warehouse($id = NULL)
    {
        if ($this->settings_model->deleteWarehouse($id)) {
            echo lang("warehouse_deleted");
        }
    }

    function warehouse_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteWarehouse($id);
                    }
                    $this->session->set_flashdata('message', lang("warehouses_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('warehouses'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('phone'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('email'));
					$this->excel->getActiveSheet()->SetCellValue('E1', lang('address'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $wh = $this->settings_model->getWarehouseByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $wh->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $wh->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $wh->phone);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $wh->email);
						$this->excel->getActiveSheet()->SetCellValue('E' . $row, $wh->address);
                        $row++;
                    }
                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'warehouses_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', lang("no_warehouse_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function variants()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('variants')));
        $meta = array('page_title' => lang('variants'), 'bc' => $bc);
        $this->page_construct('settings/variants', $meta, $this->data);
    }

    function getVariants()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, name")
            ->from("variants")
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/edit_variant/$1') . "' class='tip' title='" . lang("edit_variant") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_variant") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_variant/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id');

        echo $this->datatables->generate();
    }

    function add_variant()
    {

        $this->form_validation->set_rules('name', lang("name"), 'trim|is_unique[variants.name]|required');

        if ($this->form_validation->run() == true) {
            $data = array('name' => $this->input->post('name'));
        } elseif ($this->input->post('add_variant')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/variants");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addVariant($data)) {
            $this->session->set_flashdata('message', lang("variant_added"));
            redirect("system_settings/variants");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_variant', $this->data);
        }
    }

    function edit_variant($id = NULL)
    {

        $this->form_validation->set_rules('name', lang("name"), 'trim|required');
        $tax_details = $this->settings_model->getVariantByID($id);
        if ($this->input->post('name') != $tax_details->name) {
            $this->form_validation->set_rules('name', lang("name"), 'is_unique[variants.name]');
        }

        if ($this->form_validation->run() == true) {
            $data = array('name' => $this->input->post('name'));
        } elseif ($this->input->post('edit_variant')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/variants");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateVariant($id, $data)) {
            $this->session->set_flashdata('message', lang("variant_updated"));
            redirect("system_settings/variants");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['variant'] = $tax_details;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_variant', $this->data);
        }
    }

    function delete_variant($id = NULL)
    {
        if ($this->settings_model->deleteVariant($id)) {
            echo lang("variant_deleted");
        }
    }

	public function edit_bom($id = null)
    {
		$this->erp->checkPermissions();
		$this->form_validation->set_rules('name', lang("name"), 'required');
		$id_convert_item = 0;
        if ($this->form_validation->run() == true) {
			$warehouse_id        = $_POST['warehouse'];
            // list bom item from
            $cIterm_from_id     = $_POST['bom_from_items_id'];
            $cIterm_from_code   = $_POST['bom_from_items_code'];
            $cIterm_from_name   = $_POST['bom_from_items_name'];
            $cIterm_from_uom    = $_POST['bom_from_items_uom'];
            $cIterm_from_qty    = $_POST['bom_from_items_qty'];
            // list convert item to
            $iterm_to_id        = $_POST['convert_to_items_id'];
            $iterm_to_code      = $_POST['convert_to_items_code'];
            $iterm_to_name      = $_POST['convert_to_items_name'];
            $iterm_to_uom      	= $_POST['convert_to_items_uom'];
            $iterm_to_qty       = $_POST['convert_to_items_qty'];
			//echo '<pre>';print_r($cIterm_from_id);echo '</pre>';exit;
			$date = date("Y-m-d H:i:s", strtotime($_POST['date']));
            $data               = array(
                                        'name' => $_POST['name'],
                                        'date' => $date,
										'noted' => $_POST['note'],
                                        'created_by' => $this->session->userdata('user_id')
                                    );
			//echo '<pre>';print_r($data);echo '</pre>';exit;
            $idConvert          = $this->settings_model->updateBom($id,$data);
			$id_convert_item = $idConvert;
				
            $items = array();
            $i = isset($_POST['bom_from_items_code']) ? sizeof($_POST['bom_from_items_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $products   = $this->site->getProductByID($cIterm_from_id[$r]);
                if(!empty($cIterm_from_uom[$r])){
                    $product_variant    	= $this->site->getProductVariantByID($cIterm_from_id[$r], $cIterm_from_uom[$r]);
                }else{
                    $product_variant        = $this->site->getProductVariantByID($cIterm_from_id[$r]);
                }
                $PurchaseItemsQtyBalance    =  $this->site->getPurchaseBalanceQuantity($cIterm_from_id[$r], $warehouse_id);
				
				$unit_qty = ( !empty($product_variant->qty_unit) && $product_variant->qty_unit > 0 ? $product_variant->qty_unit : 1 );
                $PurchaseItemsQtyBalance    = $PurchaseItemsQtyBalance - ($unit_qty  * $cIterm_from_qty[$r]);
                $qtyBalace                  = $product_variant->quantity - $cIterm_from_qty[$r];
				
				$purchase_items_id = 0;
				$pis = $this->site->getPurchasedItems($cIterm_from_id[$r], $warehouse_id, $option_id = NULL);
				foreach ($pis as $pi) {
					$purchase_items_id = $pi->id;
					break;
				}

				$clause = array('purchase_id' => NULL, 'product_code' => $cIterm_from_code[$r], 'product_id' => $cIterm_from_id[$r], 'warehouse_id' => $warehouse_id);
				if ($pis) {
					$this->db->update('purchase_items', array('quantity_balance' => $PurchaseItemsQtyBalance), array('id' => $purchase_items_id));
				} else {
					$clause['quantity'] = 0;
					$clause['item_tax'] = 0;
					$clause['option_id'] = null;
					$clause['transfer_id'] = null;
					$clause['product_name'] = $cIterm_from_name[$r];
					$clause['quantity_balance'] = $PurchaseItemsQtyBalance;
					$this->db->insert('purchase_items', $clause);
				}
                // UPDATE PRODUCT QUANTITY
				
                if($this->db->update('products', array('quantity' => $PurchaseItemsQtyBalance), array('code' => $cIterm_from_code[$r])))
				{
					// UPDATE WAREHOUSE_PRODUCT QUANTITY
					if ($this->site->getWarehouseProducts( $cIterm_from_id[$r], $warehouse_id)) {
						$this->db->update('warehouses_products', array('quantity' => $PurchaseItemsQtyBalance), array('product_id' => $cIterm_from_id[$r], 'warehouse_id' => $warehouse_id));
					} else {
						$this->db->insert('warehouses_products', array('quantity' => $PurchaseItemsQtyBalance, 'product_id' => $cIterm_from_id[$r], 'warehouse_id' => $warehouse_id));
					}
					// UPDATE PRODUCT_VARIANT quantity
					if(!empty($cIterm_from_uom[$r])){
						$this->db->update('product_variants', array('quantity' => $qtyBalace), array('product_id' => $cIterm_from_id[$r], 'name' => $cIterm_from_uom[$r]));
					}else{
						$this->db->update('product_variants', array('quantity' => $qtyBalace), array('product_id' => $cIterm_from_id[$r]));
					}
				} else {
					exit('error - product');
				}

                $bomitem = array(
							'product_id' => $cIterm_from_id[$r],
							'product_code' => $cIterm_from_code[$r],
							'product_name' => $cIterm_from_name[$r],
							'quantity' => $cIterm_from_qty[$r]);
				//echo '<pre>';print_r($bomitem);echo '</pre>';exit;		
				$this->settings_model->updateBom_items($cIterm_from_id[$r],$bomitem);
								
				//$this->site->syncQuantity(NULL, $purchase_items_id);
				$this->site->syncQuantity(NULL, NULL, NULL, $cIterm_from_id[$r]);
            }
            $j = isset($_POST['convert_to_items_code']) ? sizeof($_POST['convert_to_items_code']) : 0;
            for ($r = 0; $r < $j; $r++) {
                $products   = $this->site->getProductByID($iterm_to_id[$r]);
                if(!empty($cIterm_from_uom[$r])){
                    $product_variant        = $this->site->getProductVariantByID($iterm_to_id[$r], $iterm_to_uom[$r]);
                }else{
                    $product_variant        = $this->site->getProductVariantByID($iterm_to_id[$r]);
                }

                $PurchaseItemsQtyBalance    =  $this->site->getPurchaseBalanceQuantity($iterm_to_id[$r], $warehouse_id);
                $unit_qty = ( !empty($product_variant->qty_unit) && $product_variant->qty_unit > 0 ? $product_variant->qty_unit : 1 );
                $PurchaseItemsQtyBalance    = $PurchaseItemsQtyBalance + ($unit_qty  * $iterm_to_qty[$r]);
                $qtyBalace                  = $product_variant->quantity + $iterm_to_qty[$r];
				
                $purchase_items_id = 0;
				$pis = $this->site->getPurchasedItems($iterm_to_id[$r], $warehouse_id, $option_id = NULL);
				foreach ($pis as $pi) {
					$purchase_items_id = $pi->id;
					break;
				}
				$clause = array('purchase_id' => NULL, 'product_code' => $iterm_to_code[$r], 'product_id' => $iterm_to_id[$r], 'warehouse_id' => $warehouse_id);
				if ($pis) {
					$this->db->update('purchase_items', array('quantity_balance' => $PurchaseItemsQtyBalance), array('id' => $purchase_items_id));
				} else {
					$clause['quantity'] = 0;
					$clause['item_tax'] = 0;
					$clause['option_id'] = null;
					$clause['transfer_id'] = null;
					$clause['product_name'] = $iterm_to_name[$r];
					$clause['quantity_balance'] = $PurchaseItemsQtyBalance;
					$this->db->insert('purchase_items', $clause);
				}
                // UPDATE PRODUCT QUANTITY
				
                if($this->db->update('products', array('quantity' => $PurchaseItemsQtyBalance), array('code' => $iterm_to_code[$r])))
				{
					// UPDATE WAREHOUSE_PRODUCT QUANTITY
					if ($this->site->getWarehouseProducts($iterm_to_id[$r], $warehouse_id)) {
						$this->db->update('warehouses_products', array('quantity' => $PurchaseItemsQtyBalance), array('product_id' => $iterm_to_id[$r], 'warehouse_id' => $warehouse_id));
					} else {
						$this->db->insert('warehouses_products', array('quantity' => $PurchaseItemsQtyBalance, 'product_id' => $iterm_to_id[$r], 'warehouse_id' => $warehouse_id));
					}
					// UPDATE PRODUCT_VARIANT quantity
					if(!empty($cIterm_from_uom[$r])){
						$this->db->update('product_variants', array('quantity' => $qtyBalace), array('product_id' => $iterm_to_id[$r], 'name' => $iterm_to_uom[$r]));
					}else{
						$this->db->update('product_variants', array('quantity' => $qtyBalace), array('product_id' => $iterm_to_id[$r]));
					}
				} else {
					exit('error increase product ');
				}
				$bomitems = array(
							'product_id' => $iterm_to_id[$r],
							'product_code' => $iterm_to_code[$r],
							'product_name' => $iterm_to_name[$r],
							'quantity' => $iterm_to_qty[$r]);
				//echo '<pre>';print_r($bomitems);echo '</pre>';exit;
				$this->settings_model->updateBom_items($iterm_to_id[$r],$bomitems);
				
				//$this->site->syncQuantity(NULL, $purchase_items_id);
				$this->site->syncQuantity(NULL, NULL, NULL, $cIterm_from_id[$r]);
            }
			if($id_convert_item != 0){
				$items = $this->settings_model->getConvertItemsById($id_convert_item);
				$deduct = $this->settings_model->getConvertItemsDeduct($id_convert_item);
				$adds = $this->settings_model->getConvertItemsAdd($id_convert_item);
				$each_cost = 0;
				$total_item = count($adds);
				
				foreach($items as $item){
					//echo $item->tcost;exit;
					if($item->status == 'deduct'){
						$this->db->update('bom_items', array('cost' => $item->tcost), array('product_id' => $item->product_id, 'bom_id' => $item->bom_id));
					}else{
						$each_cost = $deduct->tcost / $total_item;
						if($this->db->update('bom_items', array('cost' => $each_cost), array('product_id' => $item->product_id, 'bom_id' => $item->bom_id))){
							
							//foreach($adds as $add){
								$total_net_unit_cost = $each_cost / $item->c_quantity;
								//$total_quantity += $each_cost;
								//$total_unit_cost += ($pi->unit_cost ? ($pi->unit_cost *  $pi->quantity_balance) : ($pi->net_unit_cost + ($pi->item_tax / $pi->quantity) *  $pi->quantity_balance));
							//}
							//$avg_net_unit_cost = $total_net_unit_cost / $total_quantity;
							//$avg_unit_cost = $total_unit_cost / $total_quantity;

							//$cost2 = $each_cost * $item->p_cost;
							
							//$product_cost = ($total_net_unit_cost + $cost2) / $total_quantity;
							$this->db->update('products', array('cost' => $total_net_unit_cost), array('id' => $item->product_id));
						}
					}
				}
			}
			
            $this->session->set_flashdata('message', lang("item_conitem_convert_success"));
            redirect('system_settings/bom');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['tax_rates'] = $this->site->getAllTaxRates();
		$this->data['all_bom'] = $this->site->getAllBom($id);
		$this->data['top_bom'] = $this->site->getBom_itemsTop($id);
		$this->data['bottom_bom'] = $this->site->getBom_itemsBottom($id);
		$this->data['id'] = $id;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('Edit_Bom')));
        $meta = array('page_title' => lang('Edit_Bom'), 'bc' => $bc);
        $this->page_construct('settings/edit_bom', $meta, $this->data);
	}
	
	public function delete_bom($id = null)
    {
        $this->erp->checkPermissions('delete', true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        if ($this->settings_model->deleteBom($id) && $this->settings_model->deleteBom_items($id)) {
            echo lang("bom_deleted");
        }
    }
	
	function bom_convert()
    {
        $this->erp->checkPermissions();
        $this->form_validation->set_rules('name', lang("name"), 'required');
		$id_convert_item = 0;
        if ($this->form_validation->run() == true) {
			$warehouse_id        = $_POST['warehouse'];
            // list convert item from
            $cIterm_from_id     = $_POST['bom_from_items_id'];
            $cIterm_from_code   = $_POST['bom_from_items_code'];
            $cIterm_from_name   = $_POST['bom_from_items_name'];
            $cIterm_from_uom    = $_POST['bom_from_items_uom'];
            $cIterm_from_qty    = $_POST['bom_from_items_qty'];
            // list convert item to
            $iterm_to_id        = $_POST['convert_to_items_id'];
            $iterm_to_code      = $_POST['convert_to_items_code'];
            $iterm_to_name      = $_POST['convert_to_items_name'];
            $iterm_to_uom      = $_POST['convert_to_items_uom'];
            $iterm_to_qty       = $_POST['convert_to_items_qty'];
			$date = $this->erp->fld(trim($_POST['date']));
            $data               = array(
                                        'name' => $_POST['name'],
                                        'date' => $date,
										'noted' => $_POST['note'],
                                        'created_by' => $this->session->userdata('user_id')
                                    );
			$idConvert          = $this->settings_model->insertBom($data);
			$id_convert_item = $idConvert;
				
            $items = array();
            $i = isset($_POST['bom_from_items_code']) ? sizeof($_POST['bom_from_items_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $products   = $this->site->getProductByID($cIterm_from_id[$r]);
                if(!empty($cIterm_from_uom[$r])){
                    $product_variant    	= $this->site->getProductVariantByID($cIterm_from_id[$r], $cIterm_from_uom[$r]);
                }else{
                    $product_variant        = $this->site->getProductVariantByID($cIterm_from_id[$r]);
					
                }
                $PurchaseItemsQtyBalance    =  $this->site->getPurchaseBalanceQuantity($cIterm_from_id[$r], $warehouse_id);
				if(empty($product_variant)){
					$unit_qty = 1;
				}else{
					$unit_qty = ( !empty($product_variant->qty_unit) && $product_variant->qty_unit > 0 ? $product_variant->qty_unit : 1 );
				}
                $PurchaseItemsQtyBalance    = $PurchaseItemsQtyBalance - ($unit_qty  * $cIterm_from_qty[$r]);
                $qtyBalace                  = $product_variant->quantity - $cIterm_from_qty[$r];
				
				$purchase_items_id = 0;
				$pis = $this->site->getPurchasedItems($cIterm_from_id[$r], $warehouse_id, $option_id = NULL);
				foreach ($pis as $pi) {
					$purchase_items_id = $pi->id;
					break;
				}

				$clause = array('purchase_id' => NULL, 'product_code' => $cIterm_from_code[$r], 'product_id' => $cIterm_from_id[$r], 'warehouse_id' => $warehouse_id);
				if ($pis) {
					$this->db->update('purchase_items', array('quantity_balance' => $PurchaseItemsQtyBalance), array('id' => $purchase_items_id));
				} else {
					$clause['quantity'] = 0;
					$clause['item_tax'] = 0;
					$clause['option_id'] = null;
					$clause['transfer_id'] = null;
					$clause['product_name'] = $cIterm_from_name[$r];
					$clause['quantity_balance'] = $PurchaseItemsQtyBalance;
					$this->db->insert('purchase_items', $clause);
				}
                // UPDATE PRODUCT QUANTITY
				
                if($this->db->update('products', array('quantity' => $PurchaseItemsQtyBalance), array('code' => $cIterm_from_code[$r])))
				{
					// UPDATE WAREHOUSE_PRODUCT QUANTITY
					if ($this->site->getWarehouseProducts( $cIterm_from_id[$r], $warehouse_id)) {
						$this->db->update('warehouses_products', array('quantity' => $PurchaseItemsQtyBalance), array('product_id' => $cIterm_from_id[$r], 'warehouse_id' => $warehouse_id));
					} else {
						$this->db->insert('warehouses_products', array('quantity' => $PurchaseItemsQtyBalance, 'product_id' => $cIterm_from_id[$r], 'warehouse_id' => $warehouse_id));
					}
					// UPDATE PRODUCT_VARIANT quantity
					if(!empty($cIterm_from_uom[$r])){
						$this->db->update('product_variants', array('quantity' => $qtyBalace), array('product_id' => $cIterm_from_id[$r], 'name' => $cIterm_from_uom[$r]));
					}else{
						$this->db->update('product_variants', array('quantity' => $qtyBalace), array('product_id' => $cIterm_from_id[$r]));
					}
				} else {
					exit('error - product');
				}
			    
				//echo '<pre>';print_r($arry);echo '</pre>';exit;			
                $this->db->insert('erp_bom_items',  array(
                                                        'bom_id' => $idConvert,
                                                        'product_id' => $cIterm_from_id[$r],
                                                        'product_code' => $cIterm_from_code[$r],
                                                        'product_name' => $cIterm_from_name[$r],
                                                        'quantity' => $cIterm_from_qty[$r],
                                                        'status' => 'deduct'));
								
				//$this->site->syncQuantity(NULL, $purchase_items_id);
				$this->site->syncQuantity(NULL, NULL, NULL, $cIterm_from_id[$r]);
            }
            $j = isset($_POST['convert_to_items_code']) ? sizeof($_POST['convert_to_items_code']) : 0;
            for ($r = 0; $r < $j; $r++) {
                $products   = $this->site->getProductByID($iterm_to_id[$r]);
                if(!empty($cIterm_from_uom[$r])){
                    $product_variant        = $this->site->getProductVariantByID($iterm_to_id[$r], $iterm_to_uom[$r]);
                }else{
                    $product_variant        = $this->site->getProductVariantByID($iterm_to_id[$r]);
                }

                $PurchaseItemsQtyBalance    =  $this->site->getPurchaseBalanceQuantity($iterm_to_id[$r], $warehouse_id);
                $unit_qty = ( !empty($product_variant->qty_unit) && $product_variant->qty_unit > 0 ? $product_variant->qty_unit : 1 );
                $PurchaseItemsQtyBalance    = $PurchaseItemsQtyBalance + ($unit_qty  * $iterm_to_qty[$r]);
                $qtyBalace                  = $product_variant->quantity + $iterm_to_qty[$r];
				
                $purchase_items_id = 0;
				$pis = $this->site->getPurchasedItems($iterm_to_id[$r], $warehouse_id, $option_id = NULL);
				foreach ($pis as $pi) {
					$purchase_items_id = $pi->id;
					break;
				}
				$clause = array('purchase_id' => NULL, 'product_code' => $iterm_to_code[$r], 'product_id' => $iterm_to_id[$r], 'warehouse_id' => $warehouse_id);
				if ($pis) {
					$this->db->update('purchase_items', array('quantity_balance' => $PurchaseItemsQtyBalance), array('id' => $purchase_items_id));
				} else {
					$clause['quantity'] = 0;
					$clause['item_tax'] = 0;
					$clause['option_id'] = null;
					$clause['transfer_id'] = null;
					$clause['product_name'] = $iterm_to_name[$r];
					$clause['quantity_balance'] = $PurchaseItemsQtyBalance;
					$this->db->insert('purchase_items', $clause);
				}
                // UPDATE PRODUCT QUANTITY
				
                if($this->db->update('products', array('quantity' => $PurchaseItemsQtyBalance), array('code' => $iterm_to_code[$r])))
				{
					// UPDATE WAREHOUSE_PRODUCT QUANTITY
					if ($this->site->getWarehouseProducts($iterm_to_id[$r], $warehouse_id)) {
						$this->db->update('warehouses_products', array('quantity' => $PurchaseItemsQtyBalance), array('product_id' => $iterm_to_id[$r], 'warehouse_id' => $warehouse_id));
					} else {
						$this->db->insert('warehouses_products', array('quantity' => $PurchaseItemsQtyBalance, 'product_id' => $iterm_to_id[$r], 'warehouse_id' => $warehouse_id));
					}
					// UPDATE PRODUCT_VARIANT quantity
					if(!empty($cIterm_from_uom[$r])){
						$this->db->update('product_variants', array('quantity' => $qtyBalace), array('product_id' => $iterm_to_id[$r], 'name' => $iterm_to_uom[$r]));
					}else{
						$this->db->update('product_variants', array('quantity' => $qtyBalace), array('product_id' => $iterm_to_id[$r]));
					}
				} else {
					exit('error increase product ');
				}
				
                $this->db->insert('erp_bom_items', array(
                                                        'bom_id' => $idConvert,
                                                        'product_id' => $iterm_to_id[$r],
                                                        'product_code' => $iterm_to_code[$r],
                                                        'product_name' => $iterm_to_name[$r],
                                                        'quantity' => $iterm_to_qty[$r],
                                                        'status' => 'add'));
				
				//$this->site->syncQuantity(NULL, $purchase_items_id);
				$this->site->syncQuantity(NULL, NULL, NULL, $cIterm_from_id[$r]);
            }
			if($id_convert_item != 0){
				$items = $this->settings_model->getConvertItemsById($id_convert_item);
				$deduct = $this->settings_model->getConvertItemsDeduct($id_convert_item);
				$adds = $this->settings_model->getConvertItemsAdd($id_convert_item);
				$each_cost = 0;
				$total_item = count($adds);
				
				foreach($items as $item){
					if($item->status == 'deduct'){
						$this->db->update('bom_items', array('cost' => $item->tcost), array('product_id' => $item->product_id, 'bom_id' => $item->bom_id));
					}else{
						$each_cost = $deduct->tcost / $total_item;
						if($this->db->update('bom_items', array('cost' => $each_cost), array('product_id' => $item->product_id, 'bom_id' => $item->bom_id))){
							
							//foreach($adds as $add){
								$total_net_unit_cost = $each_cost / $item->c_quantity;
								//$total_quantity += $each_cost;
								//$total_unit_cost += ($pi->unit_cost ? ($pi->unit_cost *  $pi->quantity_balance) : ($pi->net_unit_cost + ($pi->item_tax / $pi->quantity) *  $pi->quantity_balance));
							//}
							//$avg_net_unit_cost = $total_net_unit_cost / $total_quantity;
							//$avg_unit_cost = $total_unit_cost / $total_quantity;

							//$cost2 = $each_cost * $item->p_cost;
							
							//$product_cost = ($total_net_unit_cost + $cost2) / $total_quantity;
							$this->db->update('products', array('cost' => $total_net_unit_cost), array('id' => $item->product_id));
						}
					}
				}
			}
			
            $this->session->set_flashdata('message', lang("item_conitem_convert_success"));
            redirect('system_settings/bom');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['tax_rates'] = $this->site->getAllTaxRates();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('system_settings')));
        $meta = array('page_title' => lang('bom'), 'bc' => $bc);
        $this->page_construct('system_settings/bom', $meta, $this->data);
    }
	
	function bom(){
		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('list_bom')));
        $meta = array('page_title' => lang('list_bom'), 'bc' => $bc);
        $this->page_construct('settings/list_bom', $meta, $this->data);
	}
	
	function add_bom(){
		$this->erp->checkPermissions();
        $this->form_validation->set_rules('name', lang("name"), 'required');
		$id_convert_item = 0;
        if ($this->form_validation->run() == true) {
			$warehouse_id        = $_POST['warehouse'];
            // list convert item from
            $cIterm_from_id     = $_POST['bom_from_items_id'];
            $cIterm_from_code   = $_POST['bom_from_items_code'];
            $cIterm_from_name   = $_POST['bom_from_items_name'];
            $cIterm_from_uom    = $_POST['bom_from_items_uom'];
            $cIterm_from_qty    = $_POST['bom_from_items_qty'];
            // list convert item to
            $iterm_to_id        = $_POST['convert_to_items_id'];
            $iterm_to_code      = $_POST['convert_to_items_code'];
            $iterm_to_name      = $_POST['convert_to_items_name'];
            $iterm_to_uom      = $_POST['convert_to_items_uom'];
            $iterm_to_qty       = $_POST['convert_to_items_qty'];
			$date = $this->erp->fld(trim($_POST['date']));
            $data               = array(
                                        'name' => $_POST['name'],
                                        'date' => $date,
										'noted' => $_POST['note'],
                                        'created_by' => $this->session->userdata('user_id')
                                    );
			$idConvert          = $this->settings_model->insertBom($data);
			$id_convert_item = $idConvert;
				
            $items = array();
            $i = isset($_POST['bom_from_items_code']) ? sizeof($_POST['bom_from_items_code']) : 0;
            for ($r = 0; $r < $i; $r++) {			    			
                $this->db->insert('erp_bom_items',  array(
                                                        'bom_id' => $idConvert,
                                                        'product_id' => $cIterm_from_id[$r],
                                                        'product_code' => $cIterm_from_code[$r],
                                                        'product_name' => $cIterm_from_name[$r],
                                                        'quantity' => $cIterm_from_qty[$r],
                                                        'status' => 'deduct'));
								
				//$this->site->syncQuantity(NULL, $purchase_items_id);
				$this->site->syncQuantity(NULL, NULL, NULL, $cIterm_from_id[$r]);
            }
            $j = isset($_POST['convert_to_items_code']) ? sizeof($_POST['convert_to_items_code']) : 0;
            for ($r = 0; $r < $j; $r++) {
                $this->db->insert('erp_bom_items', array(
                                                        'bom_id' => $idConvert,
                                                        'product_id' => $iterm_to_id[$r],
                                                        'product_code' => $iterm_to_code[$r],
                                                        'product_name' => $iterm_to_name[$r],
                                                        'quantity' => $iterm_to_qty[$r],
                                                        'status' => 'add'));
				
				//$this->site->syncQuantity(NULL, $purchase_items_id);
				$this->site->syncQuantity(NULL, NULL, NULL, $cIterm_from_id[$r]);
            }
			if($id_convert_item != 0){
				$items = $this->settings_model->getConvertItemsById($id_convert_item);
				$deduct = $this->settings_model->getConvertItemsDeduct($id_convert_item);
				$adds = $this->settings_model->getConvertItemsAdd($id_convert_item);
				$each_cost = 0;
				$total_item = count($adds);
				
				foreach($items as $item){
					if($item->status == 'deduct'){
						$this->db->update('bom_items', array('cost' => $item->tcost), array('product_id' => $item->product_id, 'bom_id' => $item->bom_id));
					}else{
						$each_cost = $deduct->tcost / $total_item;
						if($this->db->update('bom_items', array('cost' => $each_cost), array('product_id' => $item->product_id, 'bom_id' => $item->bom_id))){
							
							//foreach($adds as $add){
								$total_net_unit_cost = $each_cost / $item->c_quantity;
								//$total_quantity += $each_cost;
								//$total_unit_cost += ($pi->unit_cost ? ($pi->unit_cost *  $pi->quantity_balance) : ($pi->net_unit_cost + ($pi->item_tax / $pi->quantity) *  $pi->quantity_balance));
							//}
							//$avg_net_unit_cost = $total_net_unit_cost / $total_quantity;
							//$avg_unit_cost = $total_unit_cost / $total_quantity;

							//$cost2 = $each_cost * $item->p_cost;
							
							//$product_cost = ($total_net_unit_cost + $cost2) / $total_quantity;
							$this->db->update('products', array('cost' => $total_net_unit_cost), array('id' => $item->product_id));
						}
					}
				}
			}
			
            $this->session->set_flashdata('message', lang("item_conitem_convert_success"));
            redirect('system_settings/bom');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['tax_rates'] = $this->site->getAllTaxRates();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('system_settings')));
        $meta = array('page_title' => lang('bom'), 'bc' => $bc);
        $this->page_construct('settings/bom', $meta, $this->data);
	}
	
	function suggestions()
    {
        $term = $this->input->get('term', TRUE);
        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . site_url('welcome') . "'; }, 10);</script>");
        }

        $rows = $this->settings_model->getProductNames($term);
        if ($rows) {
            $uom = "";
            foreach ($rows as $row) {
                $this->db->select('id, name');
                $this->db->from('product_variants');
                $this->db->where('product_id', $row->id);
                $q = $this->db->get()->result();
                foreach ($q as $rw) {
                    $uom .= $rw->name . "#";
                }

                $pr[] = array('id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'uom' => $uom, 'code' => $row->code, 'name' => $row->name, 'price' => $row->price, 'qty' => 1);
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

	public function bom_note($id = null)
    {
        $bom = $this->settings_model->getBOmByIDs($id);
        foreach($bom as $b){
            $this->data['user'] = $this->site->getUser($b['created_by']);
        }
        $this->data['bom'] = $bom;
        $this->data['page_title'] = $this->lang->line("expense_note");
        $this->load->view($this->theme . 'settings/bom_note', $this->data);
    }
	
	public function getListBom()
    {
        $this->erp->checkPermissions();

        $detail_link = anchor('system_settings/bom_note/$1', '<i class="fa fa-file-text-o"></i> ' . lang('expense_note'), 'data-toggle="modal" data-target="#myModal2"');
        $edit_link = anchor('system_settings/edit_bom/$1', '<i class="fa fa-edit"></i> ' . lang('edit_bom'));
        //$attachment_link = '<a href="'.base_url('assets/uploads/$1').'" target="_blank"><i class="fa fa-chain"></i></a>';
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_bom") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_bom/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('delete_bom') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>
            <li>' . $edit_link . '</li>
            <li>' . $delete_link . '</li>
        </ul>
    </div></div>';

        $this->load->library('datatables');

        $this->datatables
            ->select($this->db->dbprefix('bom') . ".id as id,
					".$this->db->dbprefix('bom').".date AS Date, 
					".$this->db->dbprefix('bom').".name AS Name, 
					SUM(".$this->db->dbprefix('bom_items').".quantity) AS Quantity, 
					".$this->db->dbprefix('bom_items').".cost AS Cost,
					".$this->db->dbprefix('bom').".noted AS Note, 
					CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ', " . $this->db->dbprefix('users') . ".last_name) as user", false)
            ->from('bom')
            ->join('users', 'users.id=bom.created_by', 'left')
			->join('bom_items', 'bom_items.bom_id = bom.id')
			->where('bom_items.status','add')
            ->group_by('bom_items.bom_id');
        if (!$this->Owner && !$this->Admin) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        }
        //$this->datatables->edit_column("attachment", $attachment_link, "attachment");
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }
	
	public function expense_actions()
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
                        $this->settings_model->deleteBom($id);
						$this->settings_model->deleteBom_items($id);
                    }
                    $this->session->set_flashdata('message', $this->lang->line("expenses_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
				
                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('Bom'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('quantity'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('cost'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('noted'));
					$this->excel->getActiveSheet()->SetCellValue('F1', lang('created_by'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $bom = $this->settings_model->getBomByID($id);
                        $user = $this->site->getUser($bom->created_by);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($bom->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $bom->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $this->erp->formatMoneyPurchase($bom->qty));
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $this->erp->formatMoneyPurchase($bom->cost));
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $bom->noted);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $user->first_name . ' ' . $user->last_name);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
					$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'Bom_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php";
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
                $this->session->set_flashdata('error', $this->lang->line("no_expense_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    
	}
	
	public function updateRoom(){
		$id = $this->input->post('id_suspend');
		$data = array('floor' => $this->input->post('floor'),
					  'name' => $this->input->post('name'),
					  'ppl_number' => $this->input->post('people'),
					  'description' => $this->input->post('description'),
					  'inactive' => $this->input->post('inactive')
					);
		//$this->erp->print_arrays($data);
		$this->settings_model->updateRooms($id, $data);
		$this->session->set_flashdata('message', $this->lang->line("accound_updated"));
        redirect('system_settings/suspend');	
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
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('people'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('description'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $customer = $this->settings_model->getRoomByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $customer->floor);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $customer->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $customer->ppl_number);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $customer->description);
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
	
	function delete($id = NULL)
    {
        $this->erp->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->settings_model->deleteSuppend($id)) {
            echo $this->lang->line("deleted_suspend");
        } else {
            $this->session->set_flashdata('warning', lang('chart_account_x_deleted_have_account'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }
    }
	
	function addSuppend()
    {
        $this->erp->checkPermissions(false, true);

        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'is_unique[companies.email]');

        if ($this->form_validation->run('system_settings/addSuppend') == true) {
			
            $data = array(
				'floor' => $this->input->post('floor'),
                'name' => $this->input->post('name'),
                'ppl_number' => $this->input->post('people'),
                'description' => $this->input->post('description'),
				'inactive' => $this->input->post('inactive')
            );
			//$this->erp->print_arrays($data);
        }
        if ($this->form_validation->run() == true && $sid = $this->settings_model->addSuppend($data)) {
            $this->session->set_flashdata('message', $this->lang->line("suppend_added"));
            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
            redirect($ref[0] . '?system_settings/suppend=' . $sid);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/addSuppend', $this->data);
        }
    }
	
	function edit($id = NULL)
    {
        $this->erp->checkPermissions(false, true);
		$this->data['id'] = $id;
        $this->data['suspend'] = $this->settings_model->getRoomByID($id);
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['modal_js'] = $this->site->modal_js();
		$this->load->view($this->theme . 'settings/edit', $this->data);
    }
	
	public function suspend(){
		$this->erp->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        // $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('settings')));
        $meta = array('page_title' => lang('suppend'), 'bc' => $bc);
        $this->page_construct('settings/suspend', $meta, $this->data);
	}
	
	function getRoom()
    {
        $this->erp->checkPermissions('index');

        $this->load->library('datatables');
        $this->datatables
            ->select("id,floor,name,ppl_number,description, COALESCE(inactive,0)")
            ->from("erp_suspended")
			->add_column("Actions", "<center><a class=\"tip\" title='" . $this->lang->line("edit_suspend") . "' href='" . site_url('system_settings/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a>  <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_suspend") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id");
        echo $this->datatables->generate();
    }
    
    function suspend_layout(){
        $this->erp->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        // $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('settings')));
        $meta = array('page_title' => lang('suppend'), 'bc' => $bc);

        $this->data['suspend'] = $this->db->select("*")->from("suspended")->get()->result();
        $this->page_construct('settings/suspend_layout', $meta, $this->data);
    }

    function save_suspend_layout(){
        $RoomArray  = $this->input->post("data");
        exit($RoomArray);
    }

    function import_subcategories()
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');
                $config['upload_path'] = 'files/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '1024';
                $config['overwrite'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("system_settings/categories");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen('files/' . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);
                $keys = array('code', 'name', 'category_code', 'image');
                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                $rw = 2;
                foreach ($final as $csv_ct) {
                    if ( ! $this->settings_model->getSubcategoryByCode(trim($csv_ct['code']))) {
                        if ($parent_actegory = $this->settings_model->getCategoryByCode(trim($csv_ct['category_code']))) {
                            $data[] = array(
                                'code' => trim($csv_ct['code']),
                                'name' => trim($csv_ct['name']),
                                'image' => trim($csv_ct['image']),
                                'category_id' => $parent_actegory->id,
                                );
                        } else {
                            $this->session->set_flashdata('error', lang("check_category_code") . " (" . $csv_ct['category_code'] . "). " . lang("category_code_x_exist") . " " . lang("line_no") . " " . $rw);
                            redirect("system_settings/categories");
                        }
                    }
                    $rw++;
                }
            }

            // $this->erp->print_arrays($data);
        }

        if ($this->form_validation->run() == true && $this->settings_model->addSubCategories($data)) {
            $this->session->set_flashdata('message', lang("subcategories_added"));
            redirect('system_settings/categories');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme.'settings/import_subcategories', $this->data);

        }
    }

    function import_expense_categories()
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');
                $config['upload_path'] = 'files/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '1024';
                $config['overwrite'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("system_settings/expense_categories");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen('files/' . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);
                $keys = array('code', 'name');
                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                foreach ($final as $csv_ct) {
                    if ( ! $this->settings_model->getExpenseCategoryByCode(trim($csv_ct['code']))) {
                        $data[] = array(
                            'code' => trim($csv_ct['code']),
                            'name' => trim($csv_ct['name']),
                            );
                    }
                }
            }

            // $this->erp->print_arrays($data);
        }

        if ($this->form_validation->run() == true && $this->settings_model->addExpenseCategories($data)) {
            $this->session->set_flashdata('message', lang("categories_added"));
            redirect('system_settings/expense_categories');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme.'settings/import_expense_categories', $this->data);

        }
    }
	function audit_trail($pdf = null, $xls = null)
    {
		
        if ($this->input->post('module')) {
            $module = $this->input->post('module');
        } else {
            $module = NULL;
        }
		
        if ($this->input->post('start_date')) {
            $start_date = $this->input->post('start_date');
        } else {
            $start_date = NULL;
        }
		
        if ($this->input->post('end_date')) {
            $end_date = $this->input->post('end_date');
        } else {
            $end_date = NULL;
        }
		if($xls != ""){
			
			$this->data['xls'] = 1;
		}
		
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('categories')));
        $meta = array('page_title' => lang('audit_trail'), 'bc' => $bc);
        $this->page_construct('settings/audit_trail', $meta, $this->data);
    }

    function getAuditTrail()
    {

        if ($this->input->get('module')) {
            $module = $this->input->get('module');
        } else {
            $module = NULL;
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
		$xls = $this->input->get('xls');
        
		if($xls == 1){
			
			
			$styleArray = array(
				'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => '000000'),
					'size'  => 10,
					'name'  => 'Verdana'
				)
			);
			$bold = array(
				'font' => array(
					'bold' => true
				)
			);
			
			$this->load->library('excel');
			$this->excel->setActiveSheetIndex(0);
			//$this->excel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleArray);
			$this->excel->getActiveSheet()->setTitle(lang('products_report'));
			$this->excel->getActiveSheet()->SetCellValue('A1', lang('created_date'));
			$this->excel->getActiveSheet()->SetCellValue('B1', lang('created_by'));
			$this->excel->getActiveSheet()->SetCellValue('C1', lang('biller'));
			$this->excel->getActiveSheet()->SetCellValue('D1', lang('warehouse'));
			$this->excel->getActiveSheet()->SetCellValue('E1', lang('reference_no'));
			$this->excel->getActiveSheet()->SetCellValue('F1', lang('Type'));
			$this->excel->getActiveSheet()->SetCellValue('G1', lang('note'));
			$this->excel->getActiveSheet()->SetCellValue('H1', lang('updated_date'));
			$this->excel->getActiveSheet()->SetCellValue('I1', lang('updated_by'));
			
			//$this->excel->getActiveSheet()->getStyle('E2:F2')->applyFromArray($bold);
			//$this->excel->getActiveSheet()->getStyle('G2:H2')->applyFromArray($bold);
					
				if($module == 1){
					$this->db
					->select("sales.date as test1 , u1.first_name AS created_by1, companies.company, warehouses.name, reference_no,'Sale', customer,sales.updated_at as test2, u2.first_name AS updated_by2")
					->from("sales")
					->join('warehouses', 'warehouses.id = sales.warehouse_id', 'left')
					->join('companies', 'companies.id = sales.biller_id', 'left')
					->join('users AS u1', 'u1.id = sales.created_by', 'left')
					->join('users AS u2', 'u2.id = sales.updated_by', 'left');
					if ($start_date) {
						
						$this->db->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
					}
				}else if ($module == 2){
					$this->db
					->select("quotes.date as test1 , u1.first_name AS created_by1, companies.company, warehouses.name, reference_no,'Quote', supplier,quotes.updated_at as test2, u2.first_name AS updated_by2")
					->from("quotes")
					->join('warehouses', 'warehouses.id = quotes.warehouse_id', 'left')
					->join('companies', 'companies.id = quotes.biller_id', 'left')
					->join('users AS u1', 'u1.id = quotes.created_by', 'left')
					->join('users AS u2', 'u2.id = quotes.updated_by', 'left');
					if ($start_date) {
						
						$this->db->where($this->db->dbprefix('quotes').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
					}	
				}else if ($module == 3){
					$this->db
					->select("purchases.date as test1 , u1.first_name AS created_by1, companies.company, warehouses.name, reference_no,'Purchase', supplier,purchases.updated_at as test2, u2.first_name AS updated_by2")
					->from("purchases")
					->join('warehouses', 'warehouses.id = purchases.warehouse_id', 'left')
					->join('companies', 'companies.id = purchases.biller_id', 'left')
					->join('users AS u1', 'u1.id = purchases.created_by', 'left')
					->join('users AS u2', 'u2.id = purchases.updated_by', 'left');
					if ($start_date) {
						
						$this->db->where($this->db->dbprefix('purchases').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
					}	
				}else{
					
					$this->db
					->select("sales.date as test1 , u1.first_name AS created_by1, companies.company, warehouses.name, reference_no,'Sale', customer,sales.updated_at as test2, u2.first_name AS updated_by2")
					->from("sales")
					->join('warehouses', 'warehouses.id = sales.warehouse_id', 'left')
					->join('companies', 'companies.id = sales.biller_id', 'left')
					->join('users AS u1', 'u1.id = sales.created_by', 'left')
					->join('users AS u2', 'u2.id = sales.updated_by', 'left');
					if ($start_date) {
						
						$this->db->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
					}
					
				}
				$auditData = $this->db->get();
				$row = 2;
				
				
				
				foreach($auditData->result() as $rw){
						
						$this->excel->getActiveSheet()->SetCellValue('A'.$row, $rw->test1);
						$this->excel->getActiveSheet()->SetCellValue('B'.$row, $rw->created_by1);
						$this->excel->getActiveSheet()->SetCellValue('C'.$row, $rw->company);
						$this->excel->getActiveSheet()->SetCellValue('D'.$row, $rw->name);
						$this->excel->getActiveSheet()->SetCellValue('E'.$row, $rw->reference_no);
						$this->excel->getActiveSheet()->SetCellValue('F'.$row, $rw->sale);
						$this->excel->getActiveSheet()->SetCellValue('G'.$row, $rw->customer);
						$this->excel->getActiveSheet()->SetCellValue('H'.$row, $rw->test2);
						$this->excel->getActiveSheet()->SetCellValue('I'.$row, $rw->updated_by2);
						$row++;
						
				}
				
				$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
				$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
				
				$this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$filename = 'Audit_Trail_Report' . date('Y_m_d_H_i_s');
				if ($xls) {
					header('Content-Type: application/vnd.ms-excel');
					header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
					header('Cache-Control: max-age=0');

					$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
					return $objWriter->save('php://output');
					
				}

				redirect($_SERVER["HTTP_REFERER"]);	
						
				
		}else{
			
			$this->load->library('datatables');
			if($module == 1){
				$this->datatables
				->select("sales.date as test1 , u1.first_name AS created_by1, companies.company, warehouses.name, reference_no,'Sale', customer,sales.updated_at as test2, u2.first_name AS updated_by2")
				->from("sales")
				->join('warehouses', 'warehouses.id = sales.warehouse_id', 'left')
				->join('companies', 'companies.id = sales.biller_id', 'left')
				->join('users AS u1', 'u1.id = sales.created_by', 'left')
				->join('users AS u2', 'u2.id = sales.updated_by', 'left');
				if ($start_date) {
					
					$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
				}
			}else if ($module == 2){
				$this->datatables
				->select("quotes.date as test1 , u1.first_name AS created_by1, companies.company, warehouses.name, reference_no,'Quote', supplier,quotes.updated_at as test2, u2.first_name AS updated_by2")
				->from("quotes")
				->join('warehouses', 'warehouses.id = quotes.warehouse_id', 'left')
				->join('companies', 'companies.id = quotes.biller_id', 'left')
				->join('users AS u1', 'u1.id = quotes.created_by', 'left')
				->join('users AS u2', 'u2.id = quotes.updated_by', 'left');
				if ($start_date) {
					
					$this->datatables->where($this->db->dbprefix('quotes').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
				}	
			}else if ($module == 3){
				$this->datatables
				->select("purchases.date as test1 , u1.first_name AS created_by1, companies.company, warehouses.name, reference_no,'Purchase', supplier,purchases.updated_at as test2, u2.first_name AS updated_by2")
				->from("purchases")
				->join('warehouses', 'warehouses.id = purchases.warehouse_id', 'left')
				->join('companies', 'companies.id = purchases.biller_id', 'left')
				->join('users AS u1', 'u1.id = purchases.created_by', 'left')
				->join('users AS u2', 'u2.id = purchases.updated_by', 'left');
				if ($start_date) {
					
					$this->datatables->where($this->db->dbprefix('purchases').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
				}	
			}else{
				
				$this->datatables
				->select("sales.date as test1 , u1.first_name AS created_by1, companies.company, warehouses.name, reference_no,'Sale', customer,sales.updated_at as test2, u2.first_name AS updated_by2")
				->from("sales")
				->join('warehouses', 'warehouses.id = sales.warehouse_id', 'left')
				->join('companies', 'companies.id = sales.biller_id', 'left')
				->join('users AS u1', 'u1.id = sales.created_by', 'left')
				->join('users AS u2', 'u2.id = sales.updated_by', 'left');
				if ($start_date) {
					
					$this->datatables->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
				}
				
			}
			
			echo $this->datatables->generate();
			
		}
    }
	
	function expense_categories()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('expense_categories')));
        $meta = array('page_title' => lang('categories'), 'bc' => $bc);
        $this->page_construct('settings/expense_categories', $meta, $this->data);
    }

    function getExpenseCategories()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, code, name")
            ->from("expense_categories")
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/edit_expense_category/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_expense_category") . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_expense_category") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_expense_category/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");

        echo $this->datatables->generate();
    }

    function add_expense_category()
    {

        $this->form_validation->set_rules('code', lang("category_code"), 'trim|is_unique[categories.code]|required');
        $this->form_validation->set_rules('name', lang("name"), 'required|min_length[3]');

        if ($this->form_validation->run() == true) {

            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
            );

        } elseif ($this->input->post('add_expense_category')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/expense_categories");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addExpenseCategory($data)) {
            $this->session->set_flashdata('message', lang("expense_category_added"));
            redirect("system_settings/expense_categories");
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_expense_category', $this->data);
        }
    }

    function edit_expense_category($id = NULL)
    {
        $this->form_validation->set_rules('code', lang("category_code"), 'trim|required');
        $category = $this->settings_model->getExpenseCategoryByID($id);
        if ($this->input->post('code') != $category->code) {
            $this->form_validation->set_rules('code', lang("category_code"), 'is_unique[expense_categories.code]');
        }
        $this->form_validation->set_rules('name', lang("category_name"), 'required|min_length[3]');

        if ($this->form_validation->run() == true) {

            $data = array(
                'code' => $this->input->post('code'),
                'name' => $this->input->post('name')
            );

        } elseif ($this->input->post('edit_expense_category')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/expense_categories");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateExpenseCategory($id, $data, $photo)) {
            $this->session->set_flashdata('message', lang("expense_category_updated"));
            redirect("system_settings/expense_categories");
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['category'] = $category;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_expense_category', $this->data);
        }
    }

    function delete_expense_category($id = NULL)
    {

        if ($this->settings_model->hasExpenseCategoryRecord($id)) {
            $this->session->set_flashdata('error', lang("category_has_expenses"));
            redirect("system_settings/expense_categories", 'refresh');
        }

        if ($this->settings_model->deleteExpenseCategory($id)) {
            echo lang("expense_category_deleted");
        }
    }

    function expense_category_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteCategory($id);
                    }
                    $this->session->set_flashdata('message', lang("categories_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('categories'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sc = $this->settings_model->getCategoryByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $sc->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sc->name);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'categories_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', lang("no_record_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function import_categories()
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');
                $config['upload_path'] = 'files/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("system_settings/categories");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen('files/' . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);
                $keys = array('code', 'name', 'image', 'pcode');
                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                foreach ($final as $csv_ct) {
                    if ( ! $this->settings_model->getCategoryByCode(trim($csv_ct['code']))) {
                        $pcat = NULL;
						$p_code = trim($csv_ct['pcode']);
                        if (!empty($p_code)) {
                            if ($pcategory = $this->settings_model->getCategoryByCode(trim($csv_ct['pcode']))) {
                                $data[] = array(
                                    'code' => trim($csv_ct['code']),
                                    'name' => trim($csv_ct['name']),
                                    'image' => trim($csv_ct['image']),
                                    'parent_id' => $pcategory->id,
                                    );
                            }
                        } else {
                            $data[] = array(
                                'code' => trim($csv_ct['code']),
                                'name' => trim($csv_ct['name']),
                                'image' => trim($csv_ct['image']),
                                );
                        }
                    }
                }
            }

            // $this->erp->print_arrays($data);
        }

        if ($this->form_validation->run() == true && $this->settings_model->addCategories($data)) {
            $this->session->set_flashdata('message', lang("categories_added"));
            redirect('system_settings/categories');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme.'settings/import_categories', $this->data);

        }
    }

    function units()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('units')));
        $meta = array('page_title' => lang('units'), 'bc' => $bc);
        $this->page_construct('settings/units', $meta, $this->data);
    }

    function getUnits()
    {


        $this->load->library('datatables');
        $this->datatables
            ->select("{$this->db->dbprefix('units')}.id as id, {$this->db->dbprefix('units')}.code, {$this->db->dbprefix('units')}.name, b.name as base_unit, {$this->db->dbprefix('units')}.operator, {$this->db->dbprefix('units')}.operation_value", FALSE)
            ->from("units")
            ->join("units b", 'b.id=units.base_unit', 'left')
            ->group_by('units.id')
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/subunits/$1') . "' class='tip' title='" . lang("list_subunits") . "'><i class=\"fa fa-list\"></i></a> <a href='" . site_url('system_settings/edit_unit/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_unit") . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_unit") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_unit/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");

        echo $this->datatables->generate();
    }

    function add_unit()
    {

        $this->form_validation->set_rules('code', lang("unit_code"), 'trim|is_unique[units.code]|required');
        $this->form_validation->set_rules('name', lang("unit_name"), 'trim|required');
        if ($this->input->post('base_unit')) {
            $this->form_validation->set_rules('operator', lang("operator"), 'required');
            $this->form_validation->set_rules('operation_value', lang("operation_value"), 'trim|required');
        }

        if ($this->form_validation->run() == true) {

            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'base_unit' => $this->input->post('base_unit') ? $this->input->post('base_unit') : NULL,
                'operator' => $this->input->post('base_unit') ? $this->input->post('operator') : NULL,
                'operation_value' => $this->input->post('operation_value') ? $this->input->post('operation_value') : NULL,
                );

        } elseif ($this->input->post('add_unit')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/units");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addUnit($data)) {
            $this->session->set_flashdata('message', lang("unit_added"));
            redirect("system_settings/units");
        } else {

            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['base_units'] = $this->site->getAllBaseUnits();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_unit', $this->data);

        }
    }

    function edit_unit($id = NULL)
    {

        $this->form_validation->set_rules('code', lang("code"), 'trim|required');
        $unit_details = $this->site->getUnitByID($id);
        if ($this->input->post('code') != $unit_details->code) {
            $this->form_validation->set_rules('code', lang("code"), 'is_unique[units.code]');
        }
        $this->form_validation->set_rules('name', lang("name"), 'trim|required');
        if ($this->input->post('base_unit')) {
            $this->form_validation->set_rules('operator', lang("operator"), 'required');
            $this->form_validation->set_rules('operation_value', lang("operation_value"), 'trim|required');
        }

        if ($this->form_validation->run() == true) {

            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'base_unit' => $this->input->post('base_unit') ? $this->input->post('base_unit') : NULL,
                'operator' => $this->input->post('base_unit') ? $this->input->post('operator') : NULL,
                'operation_value' => $this->input->post('operation_value') ? $this->input->post('operation_value') : NULL,
                );

        } elseif ($this->input->post('edit_unit')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/units");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateUnit($id, $data)) {
            $this->session->set_flashdata('message', lang("unit_updated"));
            redirect("system_settings/units");
        } else {

            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['unit'] = $unit_details;
            $this->data['base_units'] = $this->site->getAllBaseUnits();
            $this->load->view($this->theme . 'settings/edit_unit', $this->data);

        }
    }

    function delete_unit($id = NULL)
    {

        if ($this->site->getUnitsByBUID($id)) {
            $this->session->set_flashdata('error', lang("unit_has_subunit"));
            redirect("system_settings/units");
        }

        if ($this->settings_model->deleteUnit($id)) {
            echo lang("unit_deleted");
        }
    }

    function unit_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteUnit($id);
                    }
                    $this->session->set_flashdata('message', lang("units_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('categories'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('base_unit'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('operator'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('operation_value'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $unit = $this->site->getUnitByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $unit->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $unit->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $unit->base_unit);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $unit->operator);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $unit->operation_value);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'categories_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', lang("no_record_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function price_groups()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('price_groups')));
        $meta = array('page_title' => lang('price_groups'), 'bc' => $bc);
        $this->page_construct('settings/price_groups', $meta, $this->data);
    }

    function getPriceGroups()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, name")
            ->from("price_groups")
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/group_product_prices/$1') . "' class='tip' title='" . lang("group_product_prices") . "'><i class=\"fa fa-eye\"></i></a>  <a href='" . site_url('system_settings/edit_price_group/$1') . "' class='tip' title='" . lang("edit_price_group") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_price_group") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_price_group/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id');

        echo $this->datatables->generate();
    }

    function add_price_group()
    {

        $this->form_validation->set_rules('name', lang("group_name"), 'trim|is_unique[price_groups.name]|required|alpha_numeric_spaces');

        if ($this->form_validation->run() == true) {
            $data = array('name' => $this->input->post('name'));
        } elseif ($this->input->post('add_price_group')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/price_groups");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addPriceGroup($data)) {
            $this->session->set_flashdata('message', lang("price_group_added"));
            redirect("system_settings/price_groups");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_price_group', $this->data);
        }
    }

    function edit_price_group($id = NULL)
    {

        $this->form_validation->set_rules('name', lang("group_name"), 'trim|required|alpha_numeric_spaces');
        $pg_details = $this->settings_model->getPriceGroupByID($id);
        if ($this->input->post('name') != $pg_details->name) {
            $this->form_validation->set_rules('name', lang("group_name"), 'is_unique[price_groups.name]');
        }

        if ($this->form_validation->run() == true) {
            $data = array('name' => $this->input->post('name'));
        } elseif ($this->input->post('edit_price_group')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/price_groups");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updatePriceGroup($id, $data)) {
            $this->session->set_flashdata('message', lang("price_group_updated"));
            redirect("system_settings/price_groups");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['price_group'] = $pg_details;
            $this->data['id'] = $id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_price_group', $this->data);
        }
    }

    function delete_price_group($id = NULL)
    {
        if ($this->settings_model->deletePriceGroup($id)) {
            echo lang("price_group_deleted");
        }
    }

    function product_group_price_actions($group_id)
    {
        if (!$group_id) {
            $this->session->set_flashdata('error', lang('no_price_group_selected'));
            redirect('system_settings/price_groups');
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'update_price') {

                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->setProductPriceForPriceGroup($id, $group_id, $this->input->post('price'.$id));
                    }
                    $this->session->set_flashdata('message', lang("products_group_price_updated"));
                    redirect($_SERVER["HTTP_REFERER"]);

                } elseif ($this->input->post('form_action') == 'delete') {

                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteProductGroupPrice($id, $group_id);
                    }
                    $this->session->set_flashdata('message', lang("products_group_price_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);

                } elseif ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('tax_rates'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('product_code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('product_name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('price'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('group_name'));
                    $row = 2;
                    $group = $this->settings_model->getPriceGroupByID($group_id);
                    foreach ($_POST['val'] as $id) {
                        $pgp = $this->settings_model->getProductGroupPriceByPID($id, $group_id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $pgp->code);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $pgp->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $pgp->price);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $group->name);
                        $row++;
                    }
                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'price_groups_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', lang("no_price_group_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function group_product_prices($group_id = NULL)
    {

        if (!$group_id) {
            $this->session->set_flashdata('error', lang('no_price_group_selected'));
            redirect('system_settings/price_groups');
        }

        $this->data['price_group'] = $this->settings_model->getPriceGroupByID($group_id);
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')),  array('link' => site_url('system_settings/price_groups'), 'page' => lang('price_groups')), array('link' => '#', 'page' => lang('group_product_prices')));
        $meta = array('page_title' => lang('group_product_prices'), 'bc' => $bc);
        $this->page_construct('settings/group_product_prices', $meta, $this->data);
    }

    function getProductPrices($group_id = NULL)
    {
        if (!$group_id) {
            $this->session->set_flashdata('error', lang('no_price_group_selected'));
            redirect('system_settings/price_groups');
        }

        $pp = "( SELECT {$this->db->dbprefix('product_prices')}.product_id as product_id, {$this->db->dbprefix('product_prices')}.price as price FROM {$this->db->dbprefix('product_prices')} WHERE price_group_id = {$group_id} ) PP";

        $this->load->library('datatables');
        $this->datatables
            ->select("{$this->db->dbprefix('products')}.id as id, {$this->db->dbprefix('products')}.code as product_code, {$this->db->dbprefix('products')}.name as product_name, PP.price as price ")
            ->from("products")
            ->join($pp, 'PP.product_id=products.id', 'left')
            ->edit_column("price", "$1__$2", 'id, price')
            ->add_column("Actions", "<div class=\"text-center\"><button class=\"btn btn-primary btn-xs form-submit\" type=\"button\"><i class=\"fa fa-check\"></i></button></div>", "id");

        echo $this->datatables->generate();
    }

    function update_product_group_price($group_id = NULL)
    {
        if (!$group_id) {
            $this->erp->send_json(array('status' => 0));
        }

        $product_id = $this->input->post('product_id', TRUE);
        $price = $this->input->post('price', TRUE);
        if (!empty($product_id) && !empty($price)) {
            if ($this->settings_model->setProductPriceForPriceGroup($product_id, $group_id, $price)) {
                $this->erp->send_json(array('status' => 1));
            }
        }

        $this->erp->send_json(array('status' => 0));
    }

    function update_prices_csv($group_id = NULL)
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (DEMO) {
                $this->session->set_flashdata('message', lang("disabled_in_demo"));
                redirect('welcome');
            }

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');
                $config['upload_path'] = 'files/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = TRUE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("system_settings/group_product_prices/".$group_id);
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen('files/' . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                $keys = array('code', 'price');

                $final = array();

                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
                $rw = 2;
                foreach ($final as $csv_pr) {
                    if ($product = $this->site->getProductByCode(trim($csv_pr['code']))) {
                    $data[] = array(
                        'product_id' => $product->id,
                        'price' => $csv_pr['price'],
                        'price_group_id' => $group_id
                        );
                    } else {
                        $this->session->set_flashdata('message', lang("check_product_code") . " (" . $csv_pr['code'] . "). " . lang("code_x_exist") . " " . lang("line_no") . " " . $rw);
                        redirect("system_settings/group_product_prices/".$group_id);
                    }
                    $rw++;
                }
            }

        } elseif ($this->input->post('update_price')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/group_product_prices/".$group_id);
        }

        if ($this->form_validation->run() == true && !empty($data)) {
            $this->settings_model->updateGroupPrices($data);
            $this->session->set_flashdata('message', lang("price_updated"));
            redirect("system_settings/group_product_prices/".$group_id);
        } else {

            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );
            $this->data['group'] = $this->site->getPriceGroupByID($group_id);
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme.'settings/update_price', $this->data);

        }
    }

    function brands()
    {
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('brands')));
        $meta = array('page_title' => lang('brands'), 'bc' => $bc);
        $this->page_construct('settings/brands', $meta, $this->data);
    }

    function getBrands()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, image, code, name", FALSE)
            ->from("brands")
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/edit_brand/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='" . lang("edit_brand") . "'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_brand") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_brand/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");

        echo $this->datatables->generate();
    }

    function add_brand()
    {

        $this->form_validation->set_rules('name', lang("brand_name"), 'trim|required|is_unique[brands.name]|alpha_numeric_spaces');

        if ($this->form_validation->run() == true) {

            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                $this->image_lib->clear();
            }

        } elseif ($this->input->post('add_brand')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/brands");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addBrand($data)) {
            $this->session->set_flashdata('message', lang("brand_added"));
            redirect("system_settings/brands");
        } else {

            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_brand', $this->data);

        }
    }

    function edit_brand($id = NULL)
    {

        $this->form_validation->set_rules('name', lang("brand_name"), 'trim|required|alpha_numeric_spaces');
        $brand_details = $this->site->getBrandByID($id);
        if ($this->input->post('name') != $brand_details->name) {
            $this->form_validation->set_rules('name', lang("brand_name"), 'is_unique[brands.name]');
        }

        if ($this->form_validation->run() == true) {

            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                $this->image_lib->clear();
            }

        } elseif ($this->input->post('edit_brand')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/brands");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateBrand($id, $data)) {
            $this->session->set_flashdata('message', lang("brand_updated"));
            redirect("system_settings/brands");
        } else {

            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['brand'] = $brand_details;
            $this->load->view($this->theme . 'settings/edit_brand', $this->data);

        }
    }

    function delete_brand($id = NULL)
    {

        if ($this->settings_model->brandHasProducts($id)) {
            $this->session->set_flashdata('error', lang("brand_has_products"));
            redirect("system_settings/brands");
        }

        if ($this->settings_model->deleteBrand($id)) {
            echo lang("brand_deleted");
        }
    }

    function import_brands()
    {

        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');
                $config['upload_path'] = 'files/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("system_settings/brands");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen('files/' . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);
                $keys = array('name', 'code', 'image');
                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                foreach ($final as $csv_ct) {
                    if ( ! $this->settings_model->getBrandByName(trim($csv_ct['name']))) {
                        $data[] = array(
                            'code' => trim($csv_ct['code']),
                            'name' => trim($csv_ct['name']),
                            'image' => trim($csv_ct['image']),
                            );
                    }
                }
            }

            // $this->erp->print_arrays($data);
        }

        if ($this->form_validation->run() == true && !empty($data) && $this->settings_model->addBrands($data)) {
            $this->session->set_flashdata('message', lang("brands_added"));
            redirect('system_settings/brands');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme.'settings/import_brands', $this->data);

        }
    }

    function brand_actions()
    {

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->settings_model->deleteBrand($id);
                    }
                    $this->session->set_flashdata('message', lang("brands_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('brands'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('image'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $brand = $this->site->getBrandByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $brand->name);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $brand->code);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $brand->image);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'categories_' . date('Y_m_d_H_i_s');
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
                $this->session->set_flashdata('error', lang("no_record_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
	
	public function financial_product()
	{
		$this->erp->checkPermissions(false, true);
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('financial_product')));
        $meta = array('page_title' => lang('financial_product'), 'bc' => $bc);
        $this->page_construct('settings/financial_product', $meta, $this->data);
		
	}
	
	public function getFinancial(){
		$this->load->library('datatables');
        $this->datatables
            ->select("services.id, services.code, services.description, services.description_other, IF(method = 'Percentage', CONCAT((amount*100),'%'),amount) as amount, services.method, services.charge_status,  services.paid_status,tax_rates.name, services.status")
            ->from("services")
			->join('tax_rates','services.tax_id = tax_rates.id','INNER')
			->order_by('id', 'asc')
			->where('service_paid != 4')
			->add_column("Actions", "<center><a href='" . site_url('system_settings/edit_financial/$1') . "' class='tip' title='" . lang("edit_financial") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> </center>", "services.id");
            //->add_column("Actions", "<center><a href='" . site_url('system_settings/edit_financial/$1') . "' class='tip' title='" . lang("edit_financial") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_financial") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_financial/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "services.id");

        echo $this->datatables->generate();
	}
	
	public function add_financial()
    {
        $this->erp->checkPermissions('expenses', true);
        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("code"), 'required');
        if ($this->form_validation->run() == true) {
            
			$method = '';
			$amount = 0;
			$tmp_amount = $this->input->post('amount');
			if(strpos($tmp_amount, '%') != false) {
				$method = 'Percentage';
				$tmp_amount = str_replace('%', '', $tmp_amount);
				$amount = ($tmp_amount/100);
			}else {
				$method = 'Fixed_Amount';
				$amount = abs($this->input->post('amount'));
			}			
			if($this->input->post('default_service_income') == null){
				$service_income = $this->input->post('service_income');
			}else{
				$service_income = $this->input->post('default_service_income');
			}			
			if($this->input->post('accrued_service_income') == null){
				$accrued_service = $this->input->post('accrued_service');
			}else{
				$accrued_service = $this->input->post('accrued_service_income');
			}
			
			$tax_id = $this->input->post('state_tax');
			$tax = $this->site->getTaxRateByID($tax_id);
            $data_financial = array(
				'code'              		=> $this->input->post('code'),
				'amount'            		=> $amount,
				'method'            		=> $method,
				'status'          			=> $this->input->post('status'),
				'paid_status'   		    => (($this->input->post('one_time')? 'One Time':0)?($this->input->post('one_time')? 'One Time':0):($this->input->post('haft_term')? 'Haft Terms':0))?:($this->input->post('all_time')? 'All Terms':0),
				'service_paid'              => (($this->input->post('one_time')? 1:0)?($this->input->post('one_time')? 1:0):($this->input->post('haft_term')? 2:0))?:($this->input->post('all_time')? 3:0),
				'charge_status'             => (($this->input->post('loan_amount')? 'Loan Amount':0)?($this->input->post('loan_amount')? 'Loan Amount':0):($this->input->post('remaing_balance')? 'Remaing Balance':0))?:($this->input->post('payment_amount')? 'Payment Amount':0),				//($this->input->post('loan_balance')? 'Loan Amount':'')?($this->input->post('loan_balance')? 'Loan Amount':''):($this->input->post('remaing_balance')? 'Remaing Balance':''),
				'charge_by'             	=> (($this->input->post('loan_amount')? 1:0)?($this->input->post('loan_amount')? 1:0):($this->input->post('remaing_balance')? 2:0))?:($this->input->post('payment_amount')? 3:0),				//($this->input->post('loan_balance')? 1:0 )?($this->input->post('loan_balance')? 1 : 0):($this->input->post('remaing_balance')? 2 : 0),
				'acc_service'      			=> $service_income,
				'accrue_service'      		=> $accrued_service,
				'tax_id'      				=> $this->input->post('state_tax'),
				'tax_rate'					=> $tax->rate /100,
				'description'	    => $this->input->post('description'),
				'description_other' => $this->input->post('description_other'),
				
				//($this->input->post('one_time')​?​ 1:0)
				//($this->input->post('haft_term')? 2:0)
				//($this->input->post('all_time')? 3:0)
			);
			//$this->erp->print_arrays($data_financial);
        } elseif ($this->input->post('add_expense')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('system_settings/financial_product');
        }

        if ($this->form_validation->run() == true && $this->settings_model->addFinancial($data_financial)) {
            $this->session->set_flashdata('message', lang("financial_added"));
            redirect('system_settings/financial_product');
        } else {
            $this->data['modal_js'] = $this->site->modal_js();
			$this->data['chart_accounts'] = $this->settings_model->getAllChartAccounts();
			$this->data['state_taxes'] = $this->settings_model->getAllTaxes();
			$this->data['service_income'] = $this->settings_model->get_service_income();
			$this->data['accrued_service_income'] = $this->settings_model->get_accrued_service();
            $this->load->view($this->theme . 'settings/add_financial', $this->data);
        }
    }
	
	function edit_financial($id=NULL){
		
        $this->erp->checkPermissions();
        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("code"), 'required');
		$financial_detail=$this->settings_model->getFinancial($id);
        if ($this->form_validation->run() == true) {
			
			$method = '';
			$amount = 0;
			$tmp_amount = $this->input->post('amount');
			if(strpos($tmp_amount, '%') != false) {
				$method = 'Percentage';
				$tmp_amount = str_replace('%', '', $tmp_amount);
				$amount = ($tmp_amount/100);
			}else {
				$method = 'Fixed_Amount';
				$amount = abs($this->input->post('amount'));
			}
			
			if($this->input->post('default_service_income') == null){
				$service_income = $this->input->post('service_income');
			}else{
				$service_income = $this->input->post('default_service_income');
			}
			
			if($this->input->post('accrued_service_income') == null){
				$accrued_service = $this->input->post('accrued_service');
			}else{
				$accrued_service = $this->input->post('accrued_service_income');
			}
			
			$tax_id = $this->input->post('state_tax');
			$tax = $this->site->getTaxRateByID($tax_id);
            $data_financial = array(
				'code'              => $this->input->post('code'),
				'amount'            => $amount,
				'method'            => $method,
				'status'            => $this->input->post('status'),
				'paid_status'       => (($this->input->post('one_time')? 'One Time':0)?($this->input->post('one_time')? 'One Time':0):($this->input->post('haft_term')? 'Haft Terms':0))?:($this->input->post('all_time')? 'All Terms':0),
				'service_paid'      => (($this->input->post('one_time')? 1:0)?($this->input->post('one_time')? 1:0):($this->input->post('haft_term')? 2:0))?:($this->input->post('all_time')? 3:0),
				'charge_status'     => (($this->input->post('loan_amount')? 'Loan Amount':0)?($this->input->post('loan_amount')? 'Loan Amount':0):($this->input->post('remaing_balance')? 'Remaing Balance':0))?:($this->input->post('payment_amount')? 'Payment Amount':0),
				'charge_by'         => (($this->input->post('loan_amount')? 1:0)?($this->input->post('loan_amount')? 1:0):($this->input->post('remaing_balance')? 2:0))?:($this->input->post('payment_amount')? 3:0),
				'acc_service'      	=> $service_income,
				'accrue_service'    => $accrued_service,
				'tax_id'      		=> $this->input->post('state_tax'),
				'tax_rate'			=> $tax->rate /100,
				'description'	    => $this->input->post('description'),
				'description_other' => $this->input->post('description_other'),
			);
	
		//$this->erp->print_arrays($data_financial);
        } elseif ($this->input->post('add_expense')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('system_settings/financial_product');
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateFinancial($data_financial,$id)) {
            $this->session->set_flashdata('message', lang("financial_updated"));
            redirect('system_settings/financial_product');
        } else {
            $this->data['modal_js'] = $this->site->modal_js();
			$this->data['financial_detail'] = $this->settings_model->getFinancial($id);
			$this->data['chart_accounts'] = $this->settings_model->getAllChartAccounts();
			$this->data['service_income'] = $this->settings_model->get_serviceIncome($id);
			$this->data['accrued_service_income'] = $this->settings_model->get_accrued_service($id);
			$this->data['state_taxes'] = $this->settings_model->getAllTaxes();
            $this->load->view($this->theme . 'settings/edit_financial', $this->data);
        }
	}
	function delete_financial($id=null){
		$d=$this->settings_model->deleteFinancial($id);
		if($d){
            redirect('system_settings/financial_product' ,'refresh');
		
		}
	}
	 function down_persentages()
    {
		$this->erp->checkPermissions();
		$this->load->model('Settings_model');
		 
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('down_persentages')));
        $meta = array('page_title' => lang('down_persentages'), 'bc' => $bc);
        $this->page_construct('settings/down_persentages', $meta, $this->data);
    }
	function getDown_persentages()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, description, amount")
            ->from("down_persentages")
			->order_by('id','desc')
            //->edit_column("map", base_url().'assets/uploads/$1', 'map')
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/edit_down_persentages/$1') . "' class='tip' title='" . lang("edit_down_persentages") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_down_persentages") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_down_persentages/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id')
        //->unset_column('map');

        echo $this->datatables->generate();
    }
	function delete_down_persentages($id=NULL){
		if ($this->settings_model->delete_down_persentages($id)) {
            echo lang("down_persentages_deleted");
        }
	}
	function add_down_persentages()
    {
		 $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_down_persentages', $this->data);
	}
	function addnew_down_persentages()
    {		
			$data = array(
			'description' => $this->input->post('description'),
			'amount' => $this->input->post('amount')
             
            );	
		if( $this->settings_model->add_down_persentages($data)){
            $this->session->set_flashdata('message', lang("down_persentages_added"));
            redirect("system_settings/down_persentages");
		 } 
    }
	function edit_down_persentages($id)
    {
		if ($dp_details=$this->settings_model->getOne_down_persentages($id)) {
			//$this->erp->print_arrays($this->settings_model->getOne_down_persentages($id));
			$this->data['row']=$dp_details;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_down_persentages', $this->data);
        }
		 
	}
	function update_down_persentages($id)
    {		
			$data = array(
			'description' => $this->input->post('description'),
			'description' => $this->input->post('description'),
			'amount' => $this->input->post('amount')
             
            );	
		
			
		if( $this->settings_model->update_down_persentages($id,$data)){
            $this->session->set_flashdata('message', lang("down_persentages_updated"));
            redirect("system_settings/down_persentages");
		 } 
    }
	///// Interest rate ///////
	
	

	 function interest_rate()
    {
		$this->erp->checkPermissions();
		$this->load->model('Settings_model');
		 
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('interest_rate')));
        $meta = array('page_title' => lang('interest_rate'), 'bc' => $bc);
        $this->page_construct('settings/interest_rate', $meta, $this->data);
    }
	function getinterest_rate()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, description, amount")
            ->from("interest_rate")
			->order_by('id','desc')
            //->edit_column("map", base_url().'assets/uploads/$1', 'map')
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/edit_interest_rate/$1') . "' class='tip' title='" . lang("edit_interest_rate") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_interest_rate") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_interest_rate/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id')
        //->unset_column('map');

        echo $this->datatables->generate();
    }
	function delete_interest_rate($id=NULL){
		if ($this->settings_model->delete_interest_rate($id)) {
            echo lang("interest_rate_deleted");
        }
	}
	function add_interest_rate()
    {
		 $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_interest_rate', $this->data);
	}
	function addnew_interest_rate()
    {		
			$data = array(
			'description' => $this->input->post('description'),
			'amount' => $this->input->post('amount')
             
            );	
		if( $this->settings_model->add_interest_rate($data)){
            $this->session->set_flashdata('message', lang("interest_rate_added"));
            redirect("system_settings/interest_rate");
		 } 
    }
	function edit_interest_rate($id)
    {
		if ($dp_details=$this->settings_model->getOne_interest_rate($id)) {
			//$this->erp->print_arrays($this->settings_model->getOne_interest_rate($id));
			$this->data['row']=$dp_details;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_interest_rate', $this->data);
        } 
	}
	function update_interest_rate($id)
    {		
			$data = array(
			'description' => $this->input->post('description'),
			'description' => $this->input->post('description'),
			'amount' => $this->input->post('amount')
             
            );	
		
			
		if( $this->settings_model->update_interest_rate($id,$data)){
            $this->session->set_flashdata('message', lang("interest_rate_updated"));
            redirect("system_settings/interest_rate");
		 } 
    }
	
	
	////// term in months //////
	
	
	
	 function term()
    {
		$this->erp->checkPermissions();
		$this->load->model('Settings_model');
		 
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('define_term')));
        $meta = array('page_title' => lang('define_term'), 'bc' => $bc);
        $this->page_construct('settings/term', $meta, $this->data);
    }
	function getterms()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id, description, amount")
            ->from("terms")
			->order_by('id','desc')
            //->edit_column("map", base_url().'assets/uploads/$1', 'map')
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/edit_term/$1') . "' class='tip' title='" . lang("edit_term") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_term") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_terms/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id')
        //->unset_column('map');

        echo $this->datatables->generate();
    }
	function delete_terms($id=NULL){
		if ($this->settings_model->delete_terms($id)) {
            echo lang("term_deleted");
        }
	}
	function add_term()
    {
		 $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_term', $this->data);
	}
	function addnew_term()
    {		
			$data = array(
			'description' => $this->input->post('description'),
			'amount' => $this->input->post('amount')
             
            );	
		if( $this->settings_model->add_term($data)){
            $this->session->set_flashdata('message', lang("term_added"));
            redirect("system_settings/term");
		 } 
    }
	function edit_term($id)
    {
		if ($dp_details=$this->settings_model->getOne_term($id)) {
			//$this->erp->print_arrays($this->settings_model->getOne_term($id));
			$this->data['row']=$dp_details;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_term', $this->data);
        }
		 
	}
	function update_term($id)
    {		
			$data = array(
			'description' => $this->input->post('description'),
			'description' => $this->input->post('description'),
			'amount' => $this->input->post('amount')
             
            );	
		
			
		if( $this->settings_model->update_term($id,$data)){
            $this->session->set_flashdata('message', lang("term_updated"));
            redirect("system_settings/term");
		 } 
    }
	public function down_persentages_action(){
		if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {   
					    $this->settings_model->delete_down_persentages($id);
                    }
					$this->session->set_flashdata('message', lang("down_persentages_deleted"));
						redirect("system_settings/down_persentages");
		}
	}
	public function interest_rate_actions(){
		if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {   
					    $this->settings_model->delete_interest_rate($id);	
                    }
					$this->session->set_flashdata('message', lang("interest_rate_deleted"));
						redirect("system_settings/interest_rate");
		}
	}
	public function term_actions(){
		if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {   
					    $this->settings_model->delete_terms($id);	
                    }
					$this->session->set_flashdata('message', lang("term_deleted"));
						redirect("system_settings/term");
		}
	}
	
	/// type but call model
	
	 function type($id=NULL)
    {
		$this->erp->checkPermissions();
		$this->load->model('Settings_model');
		$this->data['id']=$id;
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('model')));
        $meta = array('page_title' => lang('model'), 'bc' => $bc);
        $this->page_construct('settings/type', $meta, $this->data);
    }
	function gettype($id=NULL)
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("type.id as id, type.code as code,type.name as name,subcategories.name as type")
            ->from("type")
			->join('subcategories', 'subcategories.id = type.subcategory_id', 'left')
			->order_by('type.id','desc')
            //->edit_column("map", base_url().'assets/uploads/$1', 'map')
            ->add_column("Actions", "<div class=\"text-center\">
			<a href='" . site_url('system_settings/edit_type/$1') . "' class='tip' title='" . lang("edit_model") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> 
			<a href='#' class='tip po' title='<b>" . lang("delete_model") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p>
			<a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_type/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id')
        //->unset_column('map');
		  if ($id) {
            $this->datatables->where('type.subcategory_id', $id);
        }

        echo $this->datatables->generate();
    }
	function delete_type($id=NULL){
		if ($this->settings_model->delete_type($id)) {
            echo lang("type_deleted");
        }
	}
	function edit_type($id)
    {
		if ($dp_details=$this->settings_model->getOne_type($id)) {
			//$this->erp->print_arrays($this->settings_model->getOne_type($id));
			$this->data['subcategories'] = $this->settings_model->getAllSubCategories();
			$this->data['row']=$dp_details;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_type', $this->data);
        }
		 
	}
	function update_type($id)
    {		
			$data = array(
			'subcategory_id' => $this->input->post('subcategory'),
			'code' => $this->input->post('code'),
			'name' => $this->input->post('name')
             
            );	
		
			
		if( $this->settings_model->update_type($id,$data)){
            $this->session->set_flashdata('message', lang("type_updated"));
            redirect("system_settings/type");
		 } 
    }
	public function type_actions(){
		if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {   
					    $this->settings_model->delete_type($id);	
                    }
					$this->session->set_flashdata('message', lang("type_deleted"));
						redirect("system_settings/type");
		}
	}
	/// insurances but call model
	
	 function insurances($id=NULL)
    {
		$this->erp->checkPermissions();
		$this->load->model('Settings_model');
		$this->data['id']=$id;
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('insurance_company')));
        $meta = array('page_title' => lang('insurance_company'), 'bc' => $bc);
        $this->page_construct('settings/insurances', $meta, $this->data);
    }
	function getinsurances($id=NULL)
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("id ,insurance_companies")
            ->from("insurances")
			->order_by('id','desc')
            //->edit_column("map", base_url().'assets/uploads/$1', 'map')
            ->add_column("Actions", "<div class=\"text-center\">
			<a href='" . site_url('system_settings/edit_insurances/$1') . "' class='tip' title='" . lang("edit_company") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> 
			<a href='#' class='tip po' title='<b>" . lang("delete_company") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p>
			<a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_insurances/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id')
        //->unset_column('map');
		  if ($id) {
            $this->datatables->where('id', $id);
        }

        echo $this->datatables->generate();
    }
	function delete_insurances($id=NULL){
		if ($this->settings_model->delete_insurances($id)) {
            echo lang("insurances_deleted");
        }
	}
	function edit_insurances($id)
    {
		if ($dp_details=$this->settings_model->getOne_insurances($id)) {
			//$this->erp->print_arrays($this->settings_model->getOne_insurances($id));
			$this->data['row']=$dp_details;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_insurances', $this->data);
        }
		 
	}
	function update_insurances($id)
    {		
			$data = array(
			'insurance_companies' => $this->input->post('company')
            );	
		
			
		if( $this->settings_model->update_insurances($id,$data)){
            $this->session->set_flashdata('message', lang("insurances_updated"));
            redirect("system_settings/insurances");
		 } 
    }
	public function insurances_actions(){
		if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {   
					    $this->settings_model->delete_insurances($id);	
                    }
					$this->session->set_flashdata('message', lang("insurances_company_deleted"));
						redirect("system_settings/insurances");
		}
	}
	function add_insurances($id=NULL)
    {
		 $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_insurances', $this->data);
	}
	function insert_insurances(){
		$data = array(
				'insurance_companies' =>  $this->input->post('company_name')
            );
		$this->load->model('Settings_model');
		$i=$this->Settings_model->add_insurances($data);
		if($i){
			$this->session->set_flashdata('company add successful.');
			redirect('system_settings/insurances');
		}
	}
	public function cbc(){
		$this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                                        $this->excel->getActiveSheet()->SetCellValue("A1",  "Record \nNumber");
                    $this->excel->getActiveSheet()->SetCellValue("B1",  "ID \nType-1");
                    $this->excel->getActiveSheet()->SetCellValue("C1",  "ID \nNumber-1");
                    $this->excel->getActiveSheet()->SetCellValue("D1",  "ID \nExpiry Date-1");
                    $this->excel->getActiveSheet()->SetCellValue("E1",  "ID \nType-2");
                    $this->excel->getActiveSheet()->SetCellValue("F1",  "ID \nNumber-2");
                    $this->excel->getActiveSheet()->SetCellValue("G1",  "ID Expiry \nDate-2");
                    $this->excel->getActiveSheet()->SetCellValue("H1",  "ID \nType-3");
                    $this->excel->getActiveSheet()->SetCellValue("I1",  "ID \nNumber-3");
                    $this->excel->getActiveSheet()->SetCellValue("J1",  "ID Expiry \nDate-3");
                    $this->excel->getActiveSheet()->SetCellValue("K1",  "Date of \nBirth");
                    $this->excel->getActiveSheet()->SetCellValue("L1",  "Family \nName \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("M1",  "First \nName \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("N1",  "Second \nName \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("O1",  "Third \nName \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("P1",  "Unformatted \nName \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("Q1",  "Mothers \nName \nUnformatted \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("R1",  "Family \nName \n(Khmer)");
                    $this->excel->getActiveSheet()->SetCellValue("S1",  "First \nName \n(Khmer)");
                    $this->excel->getActiveSheet()->SetCellValue("T1",  "Second \nName \n(Khmer)");
                    $this->excel->getActiveSheet()->SetCellValue("U1",  "Third \nName \n(Khmer)");
                    $this->excel->getActiveSheet()->SetCellValue("V1",  "Unformatted \nName \n(Khmer)");
                    $this->excel->getActiveSheet()->SetCellValue("W1",  "Mothers Name Unformatted (Khmer)");
                    $this->excel->getActiveSheet()->SetCellValue("X1",  "Gender");
                    $this->excel->getActiveSheet()->SetCellValue("Y1",  "Marital \nStatus");
                    $this->excel->getActiveSheet()->SetCellValue("Z1",  "Nationality \nCode");
                    $this->excel->getActiveSheet()->SetCellValue("AA1",  "Taxpayer \nRegistration \nNumber");
                    $this->excel->getActiveSheet()->SetCellValue("AB1",  "Applicant \nType");
                    $this->excel->getActiveSheet()->SetCellValue("AC1",  "Address \nType -1 ");
                    $this->excel->getActiveSheet()->SetCellValue("AD1",  "Province-\n1");
                    $this->excel->getActiveSheet()->SetCellValue("AE1",  "District-1");
                    $this->excel->getActiveSheet()->SetCellValue("AF1",  "Commune\n-1");
                    $this->excel->getActiveSheet()->SetCellValue("AG1",  "Village-1");
                    $this->excel->getActiveSheet()->SetCellValue("AH1",  "Address-\n1 Field 1 \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("AI1",  "Address-\n1 Field 2 \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("AJ1",  "Address-\n1 Field 1 \n(Khmer)");
                    $this->excel->getActiveSheet()->SetCellValue("AK1",  "Address-\n1 Field 2 \n(Khmer)");
                    $this->excel->getActiveSheet()->SetCellValue("AL1",  "City-1 \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("AM1",  "City-1 \n(Khmer)");
                    $this->excel->getActiveSheet()->SetCellValue("AN1",  "Country-1");
                    $this->excel->getActiveSheet()->SetCellValue("AO1",  "Postal \nCode-1");
                    $this->excel->getActiveSheet()->SetCellValue("AP1",  "Address \nType-2");
                    $this->excel->getActiveSheet()->SetCellValue("AQ1",  "Province-\n2");
                    $this->excel->getActiveSheet()->SetCellValue("AR1",  "District-2");
                    $this->excel->getActiveSheet()->SetCellValue("AS1",  "Commune-\n2");
                    $this->excel->getActiveSheet()->SetCellValue("AT1",  "Village-2");
                    $this->excel->getActiveSheet()->SetCellValue("AU1",  "Address-\n2 Field 1 \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("AV1",  "Address-\n2 Field 2 \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("AW1",  "Address-\n2 Field 1 \n(Khmer)");
                    $this->excel->getActiveSheet()->SetCellValue("AX1",  "Address-\n2 Field 2 \n(Khmer)");
                    $this->excel->getActiveSheet()->SetCellValue("AY1",  "City-2 \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("AZ1",  "City-2  \n(Khmer)");
					$this->excel->getActiveSheet()->SetCellValue("BA1",  "Country-2");
                    $this->excel->getActiveSheet()->SetCellValue("BB1",  "Postal \nCode-2");
                    $this->excel->getActiveSheet()->SetCellValue("BC1",  "Address-\n3 Type ");
                    $this->excel->getActiveSheet()->SetCellValue("BD1",  "Province-\n3");
                    $this->excel->getActiveSheet()->SetCellValue("BE1",  "0");
                    $this->excel->getActiveSheet()->SetCellValue("BF1",  "Commune-\n3");
                    $this->excel->getActiveSheet()->SetCellValue("BG1",  "Village-3");
                    $this->excel->getActiveSheet()->SetCellValue("BH1",  "Address-\n3 Field 1 \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("BI1",  "Address-\n3 Field 2 \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("BJ1",  "Address-\n3 Field 1 \n(Khmer)");
                    $this->excel->getActiveSheet()->SetCellValue("BK1",  "Address-\n3 Field 2 \n(Khmer)");
                    $this->excel->getActiveSheet()->SetCellValue("BL1",  "City-3 \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("BM1",  "City-3 \n(Khmer)");
                    $this->excel->getActiveSheet()->SetCellValue("BN1",  "Country-3");
                    $this->excel->getActiveSheet()->SetCellValue("BO1",  "Postal \nCode-3");
                    $this->excel->getActiveSheet()->SetCellValue("BP1",  "Email \nAddress");
                    $this->excel->getActiveSheet()->SetCellValue("BQ1",  "Contact \nNumber \nType 1");
                    $this->excel->getActiveSheet()->SetCellValue("BR1",  "Contact \nNumber\n – \nCountry Code 1");
                    $this->excel->getActiveSheet()->SetCellValue("BS1",  "Contact \nNumber \n– Area 1");
                    $this->excel->getActiveSheet()->SetCellValue("BT1",  "Contact \nNumber \n– \nNumber 1");
                    $this->excel->getActiveSheet()->SetCellValue("BU1",  "Contact \nNumber \n– \nExtension 1");
                    $this->excel->getActiveSheet()->SetCellValue("BV1",  "Contact \nNumber \nType 2");
                    $this->excel->getActiveSheet()->SetCellValue("BW1",  "Contact \nNumber \n– \nCountry \nCode 2");
                    $this->excel->getActiveSheet()->SetCellValue("BX1",  "Contact \nNumber \n– Area 2");
                    $this->excel->getActiveSheet()->SetCellValue("BY1",  "Contact \nNumber \n–\n Number 2");
                    $this->excel->getActiveSheet()->SetCellValue("BZ1",  "Contact \nNumber \n– Extension 2");
					$this->excel->getActiveSheet()->SetCellValue("CA1",  "Contact \nNumber \nType 3");
                    $this->excel->getActiveSheet()->SetCellValue("CB1",  "Contact \nNumber \n– \nCountry \nCode 3");
                    $this->excel->getActiveSheet()->SetCellValue("CC1",  "Contact \nNumber \n– Area 3");
                    $this->excel->getActiveSheet()->SetCellValue("CD1",  "Contact \nNumber \n– \nNumber 3");
                    $this->excel->getActiveSheet()->SetCellValue("CE1",  "Contact \nNumber \n– \nExtension 3");
                    $this->excel->getActiveSheet()->SetCellValue("CF1",  "Employer-\n1 Type");
                    $this->excel->getActiveSheet()->SetCellValue("CG1",  "Self \nEmployed\n-1");
                    $this->excel->getActiveSheet()->SetCellValue("CH1",  "Employer-\n1 Name \n(English) ");
                    $this->excel->getActiveSheet()->SetCellValue("CI1",  "Employer-\n1 Name \n(Khmer) ");
                    $this->excel->getActiveSheet()->SetCellValue("CJ1",  "Economic \nSector-1");
                    $this->excel->getActiveSheet()->SetCellValue("CK1",  "Business \nType-1");
                    $this->excel->getActiveSheet()->SetCellValue("CL1",  "Employer-\n1’s \nAddress \n(English) ");
                    $this->excel->getActiveSheet()->SetCellValue("CM1",  "Employer-\n1’s \nAddress \n(Khmer) ");
                    $this->excel->getActiveSheet()->SetCellValue("CN1",  "Employer-\n1’s \nProvince");
                    $this->excel->getActiveSheet()->SetCellValue("CO1",  "Employer-\n1’s \nDistrict");
                    $this->excel->getActiveSheet()->SetCellValue("CP1",  "Employer-\n1’s \nCommune");
                    $this->excel->getActiveSheet()->SetCellValue("CQ1",  "Employer-\n1’s \nVillage");
                    $this->excel->getActiveSheet()->SetCellValue("CR1",  "Employer-\n1’s \nAddress \nCity \n(English) ");
                    $this->excel->getActiveSheet()->SetCellValue("CS1",  "Employer-\n1’s \nAddress \nCity \n(Khmer) ");
                    $this->excel->getActiveSheet()->SetCellValue("CT1",  "Country-1");
                    $this->excel->getActiveSheet()->SetCellValue("CU1",  "Postal \nCode-1");
                    $this->excel->getActiveSheet()->SetCellValue("CV1",  "Occupation\n-1 \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("CW1",  "Occupation\n-1 \n(Khmer)");
                    $this->excel->getActiveSheet()->SetCellValue("CX1",  "Date of \nEmploym\nent-1 ");
                    $this->excel->getActiveSheet()->SetCellValue("CY1",  "Length of \nService-1 \n(Months)");
                    $this->excel->getActiveSheet()->SetCellValue("CZ1",  "Contract \nExpiry \nDate-1");
					$this->excel->getActiveSheet()->SetCellValue("DA1",  "Currency \n- 1");
                    $this->excel->getActiveSheet()->SetCellValue("DB1",  "Monthly \nBasic \nSalary / \nIncome-1 ");
                    $this->excel->getActiveSheet()->SetCellValue("DC1",  "Total \nMonthly \nSalary / \nIncome-1 ");
                    $this->excel->getActiveSheet()->SetCellValue("DD1",  "Employer-\n2 Type ");
                    $this->excel->getActiveSheet()->SetCellValue("DE1",  "Self \nEmployed-\n2 ");
                    $this->excel->getActiveSheet()->SetCellValue("DF1",  "Employer-\n2 Name \n(English) ");
                    $this->excel->getActiveSheet()->SetCellValue("DG1",  "Employer-\n2 Name \n(Khmer) ");
                    $this->excel->getActiveSheet()->SetCellValue("DH1",  "Economic \nSector-2");
                    $this->excel->getActiveSheet()->SetCellValue("DI1",  "Business \nType-2 ");
                    $this->excel->getActiveSheet()->SetCellValue("DJ1",  "Employer-\n2’s \nAddress \n(English) ");
                    $this->excel->getActiveSheet()->SetCellValue("DK1",  "Employer-\n2’s \nAddress \n(Khmer) ");
                    $this->excel->getActiveSheet()->SetCellValue("DL1",  "Employer-\n2’s \nProvince");
                    $this->excel->getActiveSheet()->SetCellValue("DM1",  "Employer-\n2’s \nDistrict");
                    $this->excel->getActiveSheet()->SetCellValue("DN1",  "Employer-\n2’s \nCommune");
                    $this->excel->getActiveSheet()->SetCellValue("DO1",  "Employer-\n2’s \nVillage");
                    $this->excel->getActiveSheet()->SetCellValue("DP1",  "Employer-\n2’s \nAddress \nCity \n(English) ");
                    $this->excel->getActiveSheet()->SetCellValue("DQ1",  "Employer-\n2’s \nAddress \nCity \n(Khmer) ");
                    $this->excel->getActiveSheet()->SetCellValue("DR1",  "Country-2 ");
                    $this->excel->getActiveSheet()->SetCellValue("DS1",  "Postal \nCode-2");
                    $this->excel->getActiveSheet()->SetCellValue("DT1",  "Occupation\n n-2 \n(English) ");
                    $this->excel->getActiveSheet()->SetCellValue("DU1",  "Occupation\n n-2 \n(Khmer) ");
                    $this->excel->getActiveSheet()->SetCellValue("DV1",  "Date of \nEmploym\nent-2 ");
                    $this->excel->getActiveSheet()->SetCellValue("DW1",  "Length of \nService-2 \n(Months) ");
                    $this->excel->getActiveSheet()->SetCellValue("DX1",  "Contract \nExpiry \nDate-2");
                    $this->excel->getActiveSheet()->SetCellValue("DY1",  "Currency \n– 2");
                    $this->excel->getActiveSheet()->SetCellValue("DZ1",  "Monthly \nBasic \nSalary / \nIncome-2 ");
					$this->excel->getActiveSheet()->SetCellValue("EA1",  "Total \nMonthly \nSalary / \nIncome-2 ");
                    $this->excel->getActiveSheet()->SetCellValue("EB1",  "Employer \nType-3");
                    $this->excel->getActiveSheet()->SetCellValue("EC1",  "Self \nEmployed\n-3");
                    $this->excel->getActiveSheet()->SetCellValue("ED1",  "Employer-\n3 Name \n(English) ");
                    $this->excel->getActiveSheet()->SetCellValue("EE1",  "Employer-\n3 Name \n(Khmer) ");
                    $this->excel->getActiveSheet()->SetCellValue("EF1",  "Economic \nSector-3");
                    $this->excel->getActiveSheet()->SetCellValue("EG1",  "Business \nType-3");
                    $this->excel->getActiveSheet()->SetCellValue("EH1",  "Employer-\n3’s \nAddress \n(English) ");
                    $this->excel->getActiveSheet()->SetCellValue("EI1",  "Employer-\n3’s \nAddress \n(Khmer) ");
                    $this->excel->getActiveSheet()->SetCellValue("EJ1",  "Employer-\n3’s \nProvince");
                    $this->excel->getActiveSheet()->SetCellValue("EK1",  "Employer-\n3’s \nDistrict");
                    $this->excel->getActiveSheet()->SetCellValue("EL1",  "Employer-\n3’s \nCommune");
                    $this->excel->getActiveSheet()->SetCellValue("EM1",  "Employer-\n3’s \nVillage");
                    $this->excel->getActiveSheet()->SetCellValue("EN1",  "Employer-\n3’s \nAddress \nCity \n(English) ");
                    $this->excel->getActiveSheet()->SetCellValue("EO1",  "Employer-\n3’s \nAddress \nCity \n(Khmer) ");
                    $this->excel->getActiveSheet()->SetCellValue("EP1",  "Country-3");
                    $this->excel->getActiveSheet()->SetCellValue("EQ1",  "Postal \nCode-3");
                    $this->excel->getActiveSheet()->SetCellValue("ER1",  "Occupatio\nn-3 \n(English)");
                    $this->excel->getActiveSheet()->SetCellValue("ES1",  "Occupatio\nn-3 \n(Khmer)");
                    $this->excel->getActiveSheet()->SetCellValue("ET1",  "Date of \nEmployme\nnt-3 ");
                    $this->excel->getActiveSheet()->SetCellValue("EU1",  "Length of \nService-3 \n(Months) ");
                    $this->excel->getActiveSheet()->SetCellValue("EV1",  "Contract \nExpiry \nDate-3");
                    $this->excel->getActiveSheet()->SetCellValue("EW1",  "Currency \n- 3");
                    $this->excel->getActiveSheet()->SetCellValue("EX1",  "Monthly \nBasic \nSalary / \nIncome-3 ");
                    $this->excel->getActiveSheet()->SetCellValue("EY1",  "Total \nMonthly \nSalary / \nIncome-3 ");
                    $this->excel->getActiveSheet()->SetCellValue("EZ1",  "Creditor \nID");
					$this->excel->getActiveSheet()->SetCellValue("FA1",  "Account \nType");
                    $this->excel->getActiveSheet()->SetCellValue("FB1",  "Group \nAccount \nReference");
                    $this->excel->getActiveSheet()->SetCellValue("FC1",  "Account \nNumber");
                    $this->excel->getActiveSheet()->SetCellValue("FD1",  "Date \nIssued");
                    $this->excel->getActiveSheet()->SetCellValue("FE1",  "Product \nType");
                    $this->excel->getActiveSheet()->SetCellValue("FF1",  "Currency");
                    $this->excel->getActiveSheet()->SetCellValue("FG1",  "Product \nLimit / \nOriginal \nAmount");
                    $this->excel->getActiveSheet()->SetCellValue("FH1",  "Product \nExpiry \nDate");
                    $this->excel->getActiveSheet()->SetCellValue("FI1",  "Product \nStatus");
                    $this->excel->getActiveSheet()->SetCellValue("FJ1",  "Restructu\nred Loan");
                    $this->excel->getActiveSheet()->SetCellValue("FK1",  "Instalmen\nt Amount");
                    $this->excel->getActiveSheet()->SetCellValue("FL1",  "Payment \nFrequenc\ny");
                    $this->excel->getActiveSheet()->SetCellValue("FM1",  "Tenure");
                    $this->excel->getActiveSheet()->SetCellValue("FN1",  "Last \nPayment \nDate");
                    $this->excel->getActiveSheet()->SetCellValue("FO1",  "Last \nAmount \nPaid");
                    $this->excel->getActiveSheet()->SetCellValue("FP1",  "Security \nType - \nPrimary");
                    $this->excel->getActiveSheet()->SetCellValue("FQ1",  "Outstandi\nng \nBalance");
                    $this->excel->getActiveSheet()->SetCellValue("FR1",  "Past Due");
                    $this->excel->getActiveSheet()->SetCellValue("FS1",  "Next \nPayment \nDate");
                    $this->excel->getActiveSheet()->SetCellValue("FT1",  "Payment \nStatus \nCode");
                    $this->excel->getActiveSheet()->SetCellValue("FU1",  "As of \nDate");
                    $this->excel->getActiveSheet()->SetCellValue("FV1",  "Loss \nStatus");
                    $this->excel->getActiveSheet()->SetCellValue("FW1",  "Loss \nStatus \nDate");
                    $this->excel->getActiveSheet()->SetCellValue("FX1",  "Original \nAmount \nas at \nLoad Date");
                    $this->excel->getActiveSheet()->SetCellValue("FY1",  "Outstandi\nng \nBalance");
                    
					
					 $stylend = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        ),
    );
					$ndcolor=array("y","y","y","w","w","g","w","w","g","w","y","g","g","g","g","g","w","g","g","g","g","g","w","y","y","w","w","y","y","g","g","g","g","g","g","g","g","g","g","g","w","w","g","g","g","g","g","g","g","g","g","g","g","w","w","g","g","g","g","g","g","g","g","g","g","g","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","w","y","y","g","y","y","y","y","y","w","y","g","g","y","w","g","g","y","y","y","w","y","y","w","w","w","w",);
					$arraysrows=array("Man","	Man","	Man","	Opt","	Opt",	"See\n Notes",	"Opt","	Opt",	"See\n Notes",	"Opt","	Man","See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"Opt",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"Opt","	Man","	Man","	Opt","	Opt","	Man","	Man","See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"Opt","	Opt",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes","	Opt","	Opt",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes",	"See\n Notes","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Opt","	Man","	Man","See\n Notes",	"Man","	Man","	Man","	Man","	Man","	Opt","	Man","	Man","	Man","	Man","	Opt","	Man","	Man","	Man","	Man","	Man","	Opt","	Man","	Man","	Opt","	Opt","	Opt","	Opt",);
						$varnd='A';
					for($i=0;$i<=180;$i++){
						 $this->excel->getActiveSheet()->SetCellValue($varnd.'2', $arraysrows[$i]);
						 $color;
						 if($ndcolor[$i]=="y"){
							 $color="FFC000";
						 }elseif($ndcolor[$i]=="w"){
							  $color="FFFFFF";
						 }
						 elseif($ndcolor[$i]=="g"){
							  $color="00B050";
						 }
						 $this->excel->getActiveSheet()->getStyle($varnd.'2')->getAlignment()->setWrapText(true);
						 $this->excel->getActiveSheet()->getStyle($varnd.'2')->applyFromArray($stylend);
						  $this->excel->getActiveSheet()->getStyle($varnd.'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($color);

                    $varnd++;
					}

					
					
					$var='A';
					$border_style= array('borders' => array(
														'outline' => array(
														  'style' => PHPExcel_Style_Border::BORDER_THIN
														)
													  ),
													  'font' => array(
															'name' => 'Arial',
															'color' => array('rgb' => '000000'),
															'size' => 10
														),
													);				
						for($i=1;$i<=181;$i++){
							$this->excel->getActiveSheet()->getStyle($var.'1')->applyFromArray($border_style);
						 $this->excel->getActiveSheet()->getStyle($var.'1')->getAlignment()->setWrapText(true);
						 $this->excel->getActiveSheet()->getStyle($var.'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						 $this->excel->getActiveSheet()->getColumnDimension($var)->setWidth(10);
						
						$var++;
						}
						
						
						$this->excel->getActiveSheet()->getStyle('FB1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00B050');
						$this->excel->getActiveSheet()->getStyle('FJ1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00B050');

					
					
             
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'CBC_' . date('Y_m_d_H_i_s');               
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');
                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
             
	}
	
	function audit_trial_details($sale_ref){
		$this->erp->checkPermissions('index');
		$this->load->model('sales_model');
		$sale = $this->site->getSaleByReference($sale_ref);
		
		$sale_id = $sale->id;
		
        if ($this->input->get('id')) {
            $sale_id = $this->input->get('id');
        }
		
		$this->load->model('pos_model');
		$this->data['pos'] = $this->pos_model->getSetting();
		
		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
		$inv = $this->sales_model->getInvoiceByID($sale_id);
        $this->erp->view_rights($inv->created_by, TRUE);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
        $this->data['created_by'] = $this->site->getUser($inv->created_by);
		$this->data['cashier'] = $this->site->getUser($inv->saleman_by);
        $this->data['updated_by'] = $inv->updated_by ? $this->site->getUser($inv->updated_by) : NULL;
        $this->data['warehouse'] = $this->site->getWarehouseByID($inv->warehouse_id);
        $this->data['inv'] = $inv;
		$this->data['vattin'] = $this->site->getTaxRateByID($inv->order_tax_id);
        $return = $this->sales_model->getReturnBySID($sale_id);
        $this->data['return_sale'] = $return;
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($sale_id);
		$this->data['payment'] = $this->sales_model->getPaymentBySaleID($sale_id);
		$this->data['logo'] = true;
        $this->load->view($this->theme . 'settings/audit_modal', $this->data);
	}
	
	
	function sms()
    {

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('system_settings'), 'page' => lang('system_settings')), array('link' => '#', 'page' => lang('sms')));
        $meta = array('page_title' => lang('sms'), 'bc' => $bc);
        $this->page_construct('settings/sms', $meta, $this->data);
    }

    function getSMS()
    {

        $this->load->library('datatables');
        $this->datatables
            ->select("sms.id, customer_type, companies.name as dealer, send_date, overdue_days, message")
            ->from("sms")
			->join('companies', $this->db->dbprefix('companies').'.id = '.$this->db->dbprefix('sms').'.dealer_id', 'left')
            ->add_column("Actions", "<div class=\"text-center\"><a href='" . site_url('system_settings/editSMS/$1') . "' class='tip' title='" . lang("edit_sms") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_sms") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_sms/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "sms.id");
        echo $this->datatables->generate();
    }
	
	function define_sms()
    {
        $this->load->helper('security');
 
		$this->form_validation->set_rules('message', lang("message"), 'required');
		if ($this->form_validation->run() == true) {
            $data = array('customer_type' => $this->input->post('cust_type'),
                'dealer_id' => $this->input->post('by_dealer'),
                'send_date' => $this->erp->fld($this->input->post('send_date')),
                'overdue_days' => $this->input->post('due_days'),
                'message' => $this->input->post('message'),
            );
			//$this->erp->print_arrays($data);
        } elseif ($this->input->post('define_sms')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/sms");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addSMS($data)) {
            $this->session->set_flashdata('message', lang("sms_added"));
            redirect("system_settings/sms");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

			$this->data['dealers'] = $this->site->getSuppliers();
			
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/define_sms', $this->data);
        }
    }
	
	function editSMS($id = NULL)
    {
        $this->load->helper('security');
		
		if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
 
		$this->form_validation->set_rules('message', lang("message"), 'required');
		if ($this->form_validation->run() == true) {
            $data = array('customer_type' => $this->input->post('cust_type'),
                'dealer_id' => $this->input->post('by_dealer'),
                'send_date' => $this->erp->fld($this->input->post('send_date')),
                'overdue_days' => $this->input->post('due_days'),
                'message' => $this->input->post('message'),
            );
			//$this->erp->print_arrays($data);
        } elseif ($this->input->post('define_sms')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("system_settings/sms");
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateSMS($id, $data)) {
            $this->session->set_flashdata('message', lang("sms_added"));
            redirect("system_settings/sms");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

			$this->data['dealers'] = $this->site->getSuppliers();
			$this->data['sms'] = $this->settings_model->getSMSByID($id);
			
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_sms', $this->data);
        }
    }
	
	function delete_sms($id = NULL)
    {
        if ($this->settings_model->deleteSMS($id)) {
            echo lang("sms_deleted");
        }
    }
	
	public function send_sms($ids = NULL)
	{
		$this->load->library('plivo');
		
		$arr_ids = explode('___', $ids);
		foreach($arr_ids as $idd) {
			$customer_type_sms = $this->site->getCustomersSMSByID($idd);
			$dealer_sms = $this->site->getDealerSMSByID($idd);
			if($customer_type_sms) {
				$message = '';
				$multi_number = '';
				foreach($customer_type_sms as $sm) {
					$string = str_replace(' ', '', $sm->phone);
					$string1 = str_replace(' ', '', $sm->phone1);
					$string2 = str_replace(' ', '', $sm->phone2);
					$string3 = str_replace(' ', '', $sm->spouse_phone);
					$pn = substr($string, 1);
					$pn1 = substr($string1, 1);
					$pn2 = substr($string2, 1);
					$pn3 = substr($string3, 1);
					if($sm->phone) {
						$multi_number .= '+855'. $pn . '<';
					}
					if($sm->phone1) {
						$multi_number .= '+855'. $pn1 . '<';
					}
					if($sm->phone2) {
						$multi_number .= '+855'. $pn2 . '<';
					}
					if($sm->spouse_phone) {
						$multi_number .= '+855'. $pn3 . '<';
					}
					$message = $sm->message;
					$message = strip_tags($message);
				}
				$multi_number = rtrim($multi_number, '<');
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
			}
			if($dealer_sms) {
				$dealer_message = '';
				$dealer_phone = '';
				foreach($dealer_sms as $dsm) {
					$string = str_replace(' ', '', $dsm->phone);
					$string1 = str_replace(' ', '', $dsm->phone1);
					$string2 = str_replace(' ', '', $dsm->phone2);
					$string3 = str_replace(' ', '', $dsm->spouse_phone);
					$pn = substr($string, 1);
					$pn1 = substr($string1, 1);
					$pn2 = substr($string2, 1);
					$pn3 = substr($string3, 1);
					if($dsm->phone) {
						$dealer_phone .= '+855'. $pn . '<';
					}
					if($dsm->phone1) {
						$dealer_phone .= '+855'. $pn1 . '<';
					}
					if($dsm->phone2) {
						$dealer_phone .= '+855'. $pn2 . '<';
					}
					if($dsm->spouse_phone) {
						$dealer_phone .= '+855'. $pn3 . '<';
					}
					$dealer_message = $dsm->message;
					$dealer_message = strip_tags($dealer_message);
				}
				$dealer_phone = rtrim($dealer_phone, '<');
				$dealer_sms_data = array(
					'src' => 'CloudNET', //The phone number to use as the caller id (with the country code). E.g. For USA 15671234567
					'dst' => $dealer_phone, // The number to which the message needs to be send (regular phone numbers must be prefixed with country code but without the ‘+’ sign) E.g., For USA 15677654321.
					'text' => $dealer_message, // The text to send
					'type' => 'sms', //The type of message. Should be 'sms' for a text message. Defaults to 'sms'
					'url' => base_url() . 'customers/receive_sms', // The URL which will be called with the status of the message.
					'method' => 'POST', // The method used to call the URL. Defaults to. POST
				);
				//$this->erp->print_arrays($sms_data);
				/*
				 * look up available number groups
				 */
				$response_array = $this->plivo->send_sms($dealer_sms_data);
			}
		}
	}
	
	 function collateral_types($id=NULL)
    {
		$this->erp->checkPermissions();
		$this->load->model('Settings_model');
		$this->data['id']=$id;
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('collateral_types')));
        $meta = array('page_title' => lang('collateral_types'), 'bc' => $bc);
        $this->page_construct('settings/collateral_types', $meta, $this->data);
    }
	function getCollateralTypes()
    {
        $this->load->library('datatables');
        $this->datatables
            ->select("id ,type")
            ->from("collateral_types")
			->order_by('id','desc')
            //->edit_column("map", base_url().'assets/uploads/$1', 'map')
            ->add_column("Actions", "<div class=\"text-center\">
			<a href='" . site_url('system_settings/edit_collateral_types/$1') . "' class='tip' title='" . lang("edit_collateral_types") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> 
			<a href='#' class='tip po' title='<b>" . lang("delete_collateral_types") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p>
			<a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_collateral_types/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id')
        //->unset_column('map');
        echo $this->datatables->generate();
    }
	function add_collateral_types()
    {
		 $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_collateral_types', $this->data);
	}
	function insert_collateral_types(){
		$data = array(
				'type' =>  $this->input->post('type')
            );
		$this->load->model('Settings_model');
		$i=$this->Settings_model->add_collateral_types($data);
		if($i){
			$this->session->set_flashdata('company add successful.');
			redirect('system_settings/collateral_types');
		}
	}
	function delete_collateral_types($id=NULL){
		if ($this->settings_model->delete_collateral_types($id)) {
            echo lang("collateral_types_deleted");
        }
	}
	function edit_collateral_types($id)
    {
		if ($collateral_types=$this->settings_model->getOne_collateral_types($id)) {
			//$this->erp->print_arrays($this->settings_model->getOne_insurances($id));
			$this->data['row']=$collateral_types;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_collateral_types', $this->data);
        }
		 
	}
	function update_collateral_type($id)
    {		
			$data = array(
			'type' => $this->input->post('type')
            );	
		
			
		if( $this->settings_model->update_collateral_types($id,$data)){
            $this->session->set_flashdata('message', lang("collateral_types_updated"));
            redirect("system_settings/collateral_types");
		 } 
    }
	public function collateral_types_actions(){
		if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {   
					    $this->settings_model->delete_collateral_types($id);	
                    }
					$this->session->set_flashdata('message', lang("collateral_types_deleted"));
						redirect("system_settings/collateral_types");
		}
	}
	
	
	function identify_type($id=NULL)
    {
		$this->erp->checkPermissions();
		$this->load->model('Settings_model');
		$this->data['id']=$id;
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('identify_type')));
        $meta = array('page_title' => lang('identify_type'), 'bc' => $bc);
        $this->page_construct('settings/identify_type', $meta, $this->data);
    }
	
	function getidentifyTypes()
    {
        $this->load->library('datatables');
        $this->datatables
            ->select("id ,name")
            ->from("identify_types")
			->order_by('id','desc')
            //->edit_column("map", base_url().'assets/uploads/$1', 'map')
            ->add_column("Actions", "<div class=\"text-center\">
			<a href='" . site_url('system_settings/edit_identify_types/$1') . "' class='tip' title='" . lang("edit_identify_types") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> 
			<a href='#' class='tip po' title='<b>" . lang("delete_identify_types") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p>
			<a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_identify_types/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id')
        //->unset_column('map');
        echo $this->datatables->generate();
    }
	
	function add_identify_types()
    {
		 $this->data['modal_js'] = $this->site->modal_js();
         $this->load->view($this->theme . 'settings/add_identify_types', $this->data);
	}
	
	function insert_identify_types(){
		$data = array(
				'name' =>  $this->input->post('name')
            );
		$this->load->model('Settings_model');
		$i=$this->Settings_model->add_identify_types($data);
		if($i){
			$this->session->set_flashdata('Identify added successful.');
			redirect('system_settings/identify_type');
		}
	}
	
	function edit_identify_types($id)
    {
		if ($identify_types=$this->settings_model->getOne_identify_types($id)) {
			//$this->erp->print_arrays($this->settings_model->getOne_identify_types($id));
			$this->data['row']=$identify_types;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_identify_types', $this->data);
        }	 
	}
	
	function update_identify_types($id)
    {		
		$data = array(
		'name' => $this->input->post('name')
		);		
		
		if( $this->settings_model->update_identify_types($id,$data)){
            $this->session->set_flashdata('message', lang("identify_types_updated"));
            redirect("system_settings/identify_type");
		 } 
    }
	
	function delete_identify_types($id=NULL){
		if ($this->settings_model->delete_identify_types($id)) {
            echo lang("identify_types_deleted");
        }
	}
	
	public function identify_types_actions(){
		if ($this->input->post('form_action') == 'delete') {
			foreach ($_POST['val'] as $id) {   
			//$this->erp->print_arrays($_POST['val']);
				$this->settings_model->delete_identify_types($id);	
			}
			$this->session->set_flashdata('message', lang("identify_types_deleted"));
				redirect("system_settings/identify_type");
		}
	}
	
	function reject_reasons($id=NULL)
    {
		$this->erp->checkPermissions();
		$this->load->model('Settings_model');
		$this->data['id']=$id;
		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('reject_reason')));
        $meta = array('page_title' => lang('reject_reason'), 'bc' => $bc);
        $this->page_construct('settings/reject_reason', $meta, $this->data);
    }
	function get_reject_reason()
    {
        $this->load->library('datatables');
        $this->datatables
            ->select("id ,code,description,status")
            ->from("reject_reason")
			->order_by('id','desc')
            ->add_column("Actions", "<div class=\"text-center\">
			<a href='" . site_url('system_settings/edit_reject_reason/$1') . "' class='tip' title='" . lang("edit_reject_reason") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> 
			<a href='#' class='tip po' title='<b>" . lang("delete_reject_reason") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p>
			<a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_reject_reason/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        
        echo $this->datatables->generate();
    }
	function add_reject_reason()
    {
		 $this->data['modal_js'] = $this->site->modal_js();
         $this->load->view($this->theme . 'settings/add_reject_reason', $this->data);
	}
	function insert_reject_reason(){
		$data = array(
				'code'			 =>  $this->input->post('code'),
				'description'	 =>  $this->input->post('description'),
				'status'		 =>  $this->input->post('status'),
            );
		$this->load->model('Settings_model');
		$i=$this->Settings_model->add_reject_reason($data);
		if($i){
			$this->session->set_flashdata('Reject reason added successful.');
			redirect('system_settings/reject_reasons');
		}
	}
	function delete_reject_reason($id=NULL){
		if ($this->settings_model->delete_reject_reason($id)) {
            echo lang("delete_reject_reason");
        }
	}
	public function reject_reason_actions(){
		if ($this->input->post('form_action') == 'delete') {
			foreach ($_POST['val'] as $id) {
				$this->settings_model->delete_reject_reason($id);	
			}
			$this->session->set_flashdata('message', lang("reject_reason_deleted"));
			redirect("system_settings/reject_reasons");
		}
	}
	function edit_reject_reason($id)
    {
		if ($reject_reason=$this->settings_model->getOne_reject_reason($id)) {
			//$this->erp->print_arrays($this->settings_model->getOne_identify_types($id));
			$this->data['row']=$reject_reason;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_reject_reason', $this->data);
        }	 
	}
	
	function update_reject_reasons($id)
    {		
		$data = array(
			'code'			 =>  $this->input->post('code'),
			'description'	 =>  $this->input->post('description'),
			'status'		 =>  $this->input->post('status'),
		);		
		
		if( $this->settings_model->update_reject_reason($id,$data)){
            $this->session->set_flashdata('message', lang("reject_reason_updated"));
            redirect("system_settings/reject_reasons");
		 } 
    }
	
	/////////holidays
	function holidays($id=NULL)
    {
		$this->erp->checkPermissions();
		$this->load->model('Settings_model');
		$this->data['id']=$id;		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('holidays')));
        $meta = array('page_title' => lang('holidays'), 'bc' => $bc);
        $this->page_construct('settings/holidays', $meta, $this->data);
    }
	function get_holidays()
    {
        $this->load->library('datatables');
        $this->datatables
            ->select("id ,holiday_date,descriptions")
            ->from("holidays")
			->order_by('id','desc')            
			 ->add_column("Actions", "<div class=\"text-center\">
			<a href='" . site_url('system_settings/edit_holidays/$1') . "' class='tip' title='" . lang("edit_holidays") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> 
			<a href='#' class='tip po' title='<b>" . lang("delete_holidays") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p>
			<a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_holidays/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
			
        echo $this->datatables->generate();
    }
	function add_holidays()
    {
		 $this->data['modal_js'] = $this->site->modal_js();
         $this->load->view($this->theme . 'settings/add_holidays', $this->data);
	}
	function insert_holidays(){
		$data = array(
				'holiday_date'          	 => $this->erp->fld(trim($this->input->post('holiday_date'))),
				'descriptions'				 =>  $this->input->post('description'),
				
            );
		$this->load->model('Settings_model');
		$i=$this->Settings_model->add_holidays($data);
		if($i){
			$this->session->set_flashdata('Holidays added successful.');
			redirect('system_settings/holidays');
		}
	}
	function delete_holidays($id=NULL){
		if ($this->settings_model->delete_holidays($id)) {
            echo lang("delete_holidays_actions");
        }
	}
	public function holidays_actions(){
		if ($this->input->post('form_action') == 'delete') {
			foreach ($_POST['val'] as $id) {
				$this->settings_model->delete_holidays($id);	
			}
			$this->session->set_flashdata('message', lang("holidays_deleted"));
			redirect("system_settings/holidays");
		}
	}
	function edit_holidays($id)
    {
		if ($holidays=$this->settings_model->getOne_holidays($id)) {
			$this->data['row']=$holidays;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_holidays', $this->data);
        }	 
	}
	
	function update_holidays($id)
    {		
		$data = array(
			'holiday_date'          	 =>  $this->erp->fld(trim($this->input->post('holiday_date'))),
			'descriptions'				 =>  $this->input->post('description'),
		);		
		//$this->erp->print_arrays($data);
		if( $this->settings_model->update_holidays($id,$data)){
            $this->session->set_flashdata('message', lang("holidays_actions_updated"));
            redirect("system_settings/holidays");
		 } 
    }
	function importholiday()
    {
        $this->erp->checkPermissions();
        $this->load->helper('security');
        $this->load->library('erp');

        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');
        if ($this->form_validation->run() == true) {

            if (isset($_FILES["userfile"]))
            {
				$this->load->library('upload');
				$config['upload_path'] = 'assets/uploads/csv/';
				$config['allowed_types'] = 'csv';
				$config['max_size'] = '2000';
				$config['overwrite'] = TRUE;
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('userfile'))
				{
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error', $error);
					redirect("System_setting/holidays");
				}
				$csv = $this->upload->file_name;
				$arrResult = array();
				$handle = fopen("assets/uploads/csv/" . $csv, "r");
				if ($handle) {
					while (($row = fgetcsv($handle,",")) !== FALSE) {
						$arrResult[] = $row;
					}
					fclose($handle);
				}
				$titles = array_shift($arrResult);
				$keys = array('datetime','descriptions');
				$final = array();
				foreach ($arrResult as $key => $value) {
					$final[] = array_combine($keys, $value);
				}

				foreach ($final as $key => $value)
				{
					$data[] = array(
							'holiday_date'              => $this->erp->fld($value['datetime']),
							'descriptions'              => $value['descriptions']
					);									
				}
				//$this->erp->print_arrays($data);
				if ($this->form_validation->run() == true && $this->settings_model->importHolidays($data)) {
					$this->session->set_userdata('remove_quls', 1);
					$this->session->set_flashdata('message', $this->lang->line("holidays_added"));
					
					redirect('system_settings/holidays');
				}else {
					$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
					$this->data['modal_js'] = $this->site->modal_js();
					$this->load->view($this->theme . 'settings/importholiday', $this->data);
				}
            }
        }else {
			$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('System_setting/holidays'), 'page' => lang('holidays')), array('link' => '#', 'page' => lang('import_holidays')));
			$meta = array('page_title' => lang('import_holiday'), 'bc' => $bc);
			$this->page_construct('settings/importholiday', $meta, $this->data);
		}
    }
	
	
	/////////policy_completly_payment
	function policy_payments($id=NULL)
    {
		$this->erp->checkPermissions();
		$this->load->model('Settings_model');
		$this->data['id']=$id;		
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('policy_payments')));
        $meta = array('page_title' => lang('policy_payments'), 'bc' => $bc);
        $this->page_construct('settings/policy_payments', $meta, $this->data);
    }
	function get_policy_payments()
    {
        $this->load->library('datatables');
        $this->datatables
            ->select("id ,days")
            ->from("policy_payments")
			->order_by('id','desc')            
			 ->add_column("Actions", "<div class=\"text-center\">
			<a href='" . site_url('system_settings/edit_policy_payments/$1') . "' class='tip' title='" . lang("edit_days") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> 
			<a href='#' class='tip po' title='<b>" . lang("delete_days") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p>
			<a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_policy_payments/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
			
        echo $this->datatables->generate();
    }
	function add_policy_payments()
    {
		 $this->data['modal_js'] = $this->site->modal_js();
         $this->load->view($this->theme . 'settings/add_policy_payments', $this->data);
	}
	function insert_policy_payments(){
		$data = array(
				'days'				 =>  $this->input->post('days'),
            );
		$this->load->model('Settings_model');
		$i=$this->Settings_model->add_policy_payments($data);
		if($i){
			$this->session->set_flashdata('policy_payments added successful.');
			redirect('system_settings/policy_payments');
		}
	}
	function delete_policy_payments($id=NULL){
		if ($this->settings_model->delete_policy_payments($id)) {
            echo lang("delete_policy_payments_actions");
        }
	}
	public function policy_payments_actions(){
		if ($this->input->post('form_action') == 'delete') {
			foreach ($_POST['val'] as $id) {
				$this->settings_model->delete_policy_payments($id);	
			}
			$this->session->set_flashdata('message', lang("policy_payments_deleted"));
			redirect("system_settings/policy_payments");
		}
	}
	function edit_policy_payments($id)
    {
		if ($policy_payments=$this->settings_model->getOne_policy_payments($id)) {
			$this->data['row']=$policy_payments;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/edit_policy_payments', $this->data);
        }	 
	}	
	function update_policy_payments($id)
    {		
		$data = array(
			'days'				 =>  $this->input->post('days'),
		);		
		//$this->erp->print_arrays($data);
		if( $this->settings_model->update_policy_payments($id,$data)){
            $this->session->set_flashdata('message', lang("policy_payments_actions_updated"));
            redirect("system_settings/policy_payments");
		 } 
    }
	/////
	public function insurence()
	{
		$this->erp->checkPermissions(false, true);
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('insurance')));
        $meta = array('page_title' => lang('insurance'), 'bc' => $bc);
        $this->page_construct('settings/insurence', $meta, $this->data);
		
	}
	
	public function getinsurence(){
		$this->load->library('datatables');
        $this->datatables
            ->select("services.id, services.code, services.description, services.description_other, IF(method = 'Percentage', CONCAT((amount*100),'%'),amount) as amount, services.method,tax_rates.name, services.status")
            ->from("services")
			->join('tax_rates','services.tax_id = tax_rates.id','INNER')
			->order_by('id', 'asc')
			->where('service_paid',4)
			->add_column("Actions", "<center><a href='" . site_url('system_settings/edit_insurence/$1') . "' class='tip' title='" . lang("edit_insurence") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a></center>", "services.id");
            //->add_column("Actions", "<center><a href='" . site_url('system_settings/edit_insurence/$1') . "' class='tip' title='" . lang("edit_insurence") . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_insurence") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_insurence/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "services.id");

        echo $this->datatables->generate();
	}
	
	public function add_insurence()
    {
        $this->erp->checkPermissions('expenses', true);
        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("code"), 'required');

        if ($this->form_validation->run() == true) {
            
			$method = '';
			$amount = 0;
			$tmp_amount = $this->input->post('amount');
			if(strpos($tmp_amount, '%') != false) {
				$method = 'Percentage';
				$tmp_amount = str_replace('%', '', $tmp_amount);
				$amount = ($tmp_amount/100);
			}else {
				$method = 'Fixed Amount';
				$amount = abs($this->input->post('amount'));
			}
			
			
			if($this->input->post('default_service_income') == null){
				$service_income = $this->input->post('service_income');
			}else{
				$service_income = $this->input->post('default_service_income');
			}
			$tax_id = $this->input->post('state_tax');
			$tax = $this->site->getTaxRateByID($tax_id);
            $data_financial = array(
				'code'              		=> $this->input->post('code'),
				'description'	    		=> $this->input->post('description'),
				'amount'            		=> $amount,
				'method'            		=> $method,
				'description_other'		    => $this->input->post('description_other'),
				'status'          			=> $this->input->post('status'),
				'paid_status'   		    => $this->input->post('paid_status'),
				'service_paid'              => $this->input->post('service_paid'),
				'acc_service'      			=> $service_income,
				'tax_id'      				=> $this->input->post('state_tax'), 
				'tax_rate'					=> $tax->rate /100,
				'charge_by' 				=> 0,
				
				//($this->input->post('one_time')​?​ 1:0)
				//($this->input->post('haft_term')? 2:0)
				//($this->input->post('all_time')? 3:0) 
			);
			//$this->erp->print_arrays($data_financial);
        } elseif ($this->input->post('add_expense')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('system_settings/insurence');
        }

        if ($this->form_validation->run() == true && $this->settings_model->addFinancial($data_financial)) {
            $this->session->set_flashdata('message', lang("insurence_added"));
            redirect('system_settings/insurence');
        } else {
            $this->data['modal_js'] = $this->site->modal_js();
			$this->data['chart_accounts'] = $this->settings_model->getAllChartAccounts();
			$this->data['service_income'] = $this->settings_model->get_service_income();
			$this->data['state_taxes'] = $this->settings_model->getAllTaxes();
            $this->load->view($this->theme . 'settings/add_insurence', $this->data);
        }
    }
	
	function edit_insurence($id=NULL){
		
        $this->erp->checkPermissions();
        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("code"), 'required');

		$this->data['financial_detail']=$this->settings_model->getFinancial($id);
		$financial_detail=$this->settings_model->getFinancial($id);
        if ($this->form_validation->run() == true) {
			
			$method = '';
			$amount = 0;
			$tmp_amount = $this->input->post('amount');
			if(strpos($tmp_amount, '%') != false) {
				$method = 'Percentage';
				$tmp_amount = str_replace('%', '', $tmp_amount);
				$amount = ($tmp_amount/100);
			}else {
				$method = 'Fixed Amount';
				$amount = abs($this->input->post('amount'));
			}
			
			if($this->input->post('default_service_income') == null){
				$service_income = $this->input->post('service_income');
			}else{
				$service_income = $this->input->post('default_service_income');
			}
			$tax_id = $this->input->post('state_tax');
			$tax = $this->site->getTaxRateByID($tax_id);
			
            $data_financial = array(
				'code'              		=> $this->input->post('code'),
				'description'	    		=> $this->input->post('description'),
				'amount'            		=> $amount,
				'method'            		=> $method,
				'description_other' 		=> $this->input->post('description_other'),
				'status'            		=> $this->input->post('status'),
				'acc_service'      			=> $service_income,
				'tax_id'      				=> $this->input->post('state_tax'),
				'tax_rate'					=> $tax->rate /100,
				'charge_by' 				=> 0,
				
			);
	
			//$this->erp->print_arrays($data_financial);
        } elseif ($this->input->post('add_expense')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('system_settings/insurence');
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateFinancial($data_financial,$id)) {
            $this->session->set_flashdata('message', lang("insurence_updated"));
            redirect('system_settings/insurence');

		 //$this->erp->print_arrays($data_financial);
        } else {
            $this->data['modal_js'] = $this->site->modal_js();
			$this->data['chart_accounts'] = $this->settings_model->getAllChartAccounts();
			$this->data['service_income'] = $this->settings_model->get_serviceIncome($id);
			$this->data['state_taxes'] = $this->settings_model->getAllTaxes();
            $this->load->view($this->theme . 'settings/edit_insurence', $this->data);
        }
	}
	function delete_insurence($id=null){
		$d=$this->settings_model->deleteFinancial($id);
		if($d){
            redirect('system_settings/insurence' ,'refresh');
		
		}
	}
}
