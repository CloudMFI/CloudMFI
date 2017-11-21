<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
	//=============delete chart account===================
	public function deleteChartAccount($id){
		$q = $this->db->delete('gl_charts', array('accountcode' => $id));
		if($q){
			return true;
		}else{
			return false;
		}
	}
    public function getProductNames($term, $warehouse_id, $limit = 5)
    {
        $this->db->select('products.id, code, name, warehouses_products.quantity, cost, tax_rate, type, tax_method')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
        if ($this->Settings->overselling) {
            $this->db->where("type = 'standard' AND (name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        } else {
            $this->db->where("type = 'standard' AND warehouses_products.warehouse_id = '" . $warehouse_id . "' AND warehouses_products.quantity > 0 AND "
                . "(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        }
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getAlltypes(){
		$q = $this->db->query("SELECT * from erp_groups WHERE erp_groups.id IN (3)");		
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getpeoplebytype($company){		
		if($company == 'shareholder'){
			$this->db->select('CONCAT(first_name,"  ",last_name) as id, CONCAT(first_name,"  ",last_name) as text');
			$q = $this->db->get_where("users",array('shareholder' => '1'));
		}else{
			$this->db->select('name as id, CONCAT(family_name," ",name) as text');
			$q = $this->db->get_where("companies", array('group_id' => $company));
		}
		
		
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
	}
	
	/*
    public function getAllcharts() {
        $q = $this->db->select();
		$q = $this->db->from('erp_gl_charts');
		$query=$this->db->get();
		return $query->result_array();
    }*/
	
	public function getAllcharts(){
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
    public function getWHProduct($id){
        $this->db->select('products.id, code, name, warehouses_products.quantity, cost, tax_rate')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
        $q = $this->db->get_where('products', array('warehouses_products.product_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function addTransfer($data = array(), $items = array())
    {
        $status = $data['status'];
        if ($this->db->insert('transfers', $data)) {
            $transfer_id = $this->db->insert_id();
            //if ($this->site->getReference('to') == $data['transfer_no']) {
                $this->site->updateReference('to');
            //}
            foreach ($items as $item) {
                $item['transfer_id'] = $transfer_id;
                if ($status == 'completed') {
                    $item['date'] = date('Y-m-d');
                    $item['warehouse_id'] = $data['to_warehouse_id'];
                    $item['status'] = 'received';
                    $this->db->insert('purchase_items', $item);
                } else {
                    $this->db->insert('transfer_items', $item);
                }

                if ($status == 'sent' || $status == 'completed') {
                    $this->syncTransderdItem($item['product_id'], $data['from_warehouse_id'], $item['quantity'], $item['option_id']);
                }
            }

            return true;
        }
        return false;
    }

    public function updateTransfer($id, $data = array(), $items = array())
    {
        $ostatus = $this->resetTransferActions($id);
        $status = $data['status'];
        if ($this->db->update('transfers', $data, array('id' => $id))) {
            $tbl = $ostatus == 'completed' ? 'purchase_items' : 'transfer_items';
            $this->db->delete($tbl, array('transfer_id' => $id));

            foreach ($items as $item) {
                $item['transfer_id'] = $id;
                if ($status == 'completed') {
                    $item['date'] = date('Y-m-d');
                    $item['warehouse_id'] = $data['to_warehouse_id'];
                    $item['status'] = 'received';
                    $this->db->insert('purchase_items', $item);
                } else {
                    $this->db->insert('transfer_items', $item);
                }

                $status = $data['status'];
                if ($status == 'sent' || $status == 'completed') {
                    $this->syncTransderdItem($item['product_id'], $data['from_warehouse_id'], $item['quantity'], $item['option_id']);
                }

            }

            return true;
        }

        return false;
    }

    public function getProductWarehouseOptionQty($option_id, $warehouse_id)
    {
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id, 'warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductByCategoryID($id)
    {

        $q = $this->db->get_where('products', array('category_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return true;
        }

        return FALSE;
    }
	
	public function getAccountSections(){
		$this->db->select("sectionid,sectionname");
		$section = $this->db->get("gl_sections");
		if($section->num_rows() > 0){
			return $section->result_array();	
		}
		return false;
	}
	
	public function getSubAccounts($section_code){
		$this->db->select('accountcode as id, accountname as text');
        $q = $this->db->get_where("gl_charts", array('sectionid' => $section_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
	}
	
	public function addChartAccount($data){
		//$this->erp->print_arrays($data);
		if ($this->db->insert('gl_charts', $data)) {
            return true;
        }
        return false;
	}
	
	public function updateChartAccount($id,$data){
		//$this->erp->print_arrays($data);
		$this->db->where('accountcode', $id);
		$q=$this->db->update('gl_charts', $data);
        if ($q) {
            return true;
        }
        return false;
	}

    public function getProductQuantity($product_id, $warehouse = DEFAULT_WAREHOUSE)
    {
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse), 1);
        if ($q->num_rows() > 0) {
            return $q->row_array(); //$q->row();
        }
        return FALSE;
    }

    public function insertQuantity($product_id, $warehouse_id, $quantity)
    {
        if ($this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $quantity))) {
            $this->site->syncProductQty($product_id, $warehouse_id);
            return true;
        }
        return false;
    }

    public function updateQuantity($product_id, $warehouse_id, $quantity)
    {
        if ($this->db->update('warehouses_products', array('quantity' => $quantity), array('product_id' => $product_id, 'warehouse_id' => $warehouse_id))) {
            $this->site->syncProductQty($product_id, $warehouse_id);
            return true;
        }
        return false;
    }
	
	public function updateSetting($data){
		if ($this->db->update('account_settings', $data)) {
            return true;
        }
        return false;
	}

    public function getProductByCode($code)
    {

        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getProductByName($name)
    {

        $q = $this->db->get_where('products', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }
	
	public function getChartAccountByID($id){
		$this->db->select('gl_charts.accountcode,gl_charts.accountname,gl_charts.accountname_kh,gl_charts.parent_acc,gl_charts.sectionid,gl_sections.sectionname, bank ');
		$this->db->from('gl_charts');
		$this->db->join('gl_sections', 'gl_sections.sectionid=gl_charts.sectionid','INNER');
		$this->db->where('gl_charts.accountcode' , $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getAllChartAccount(){
		$this->db->select('gl_charts.accountcode,gl_charts.accountname,gl_charts.accountname_kh,gl_charts.parent_acc,gl_charts.sectionid');
		$this->db->from('gl_charts');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	public function getAllChartAccountIn($section_id){
		$q = $this->db->query("SELECT
									accountcode,
									accountname,
									parent_acc,
									sectionid
								FROM
									erp_gl_charts
								WHERE
									sectionid IN ($section_id)");
		
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	public function getCustomers()
    {
        $q = $this->db->query("SELECT
									id, company
								FROM
									erp_companies
								WHERE
									group_name = 'biller'
								");
		
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	
	public function getAllChartAccounts(){
		$q = $this->db->query("SELECT
									accountcode,
									accountname,
									parent_acc,
									sectionid
								FROM
									erp_gl_charts
								");
		
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	public function getBillers()
    {
		$this->db->select('company');
		$this->db->from('companies');
		$this->db->join('account_settings', 'account_settings.biller_id=companies.id');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getSalename()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_sale=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getPenaltyIncome()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_penalty_income=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getInterestIncome() {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_interest_income=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getsalediscount()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_sale_discount=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getsale_tax()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_sale_tax=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getreceivable()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_receivable=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getpurchases()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_purchase=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getGLYearMonth(){
		$query = $this->db->select("MIN(YEAR(tran_date)) AS min_year, MIN(MONTH(tran_date)) AS min_month")
				->get('gl_trans');
		if($query->num_rows() > 0){
			return $query->row();
		}
		return false;
	}
	
	
	public function getpurchase_tax()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_purchase_tax=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	
	public function getpurchasediscount()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_purchase_discount=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getpayable()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_payable=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_sale_freights()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_sale_freight=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_purchase_freights()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_purchase_freight=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
	public function getstocks()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_stock=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getstock_adjust()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_stock_adjust=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_cost()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_cost=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getpayrolls()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_payroll=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_cash()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_cash=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getcredit_card()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_credit_card=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_sale_deposit()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_sale_deposit=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function get_purchase_deposit()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_purchase_deposit=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getcheque()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_cheque=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_loan()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_loan=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_retained_earning()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_retained_earnings=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_other_income()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_other_income=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function get_default_capital()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_capital=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function get_accrued_interest()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.accrued_interest=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function get_default_transfer_money()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_transfer_money=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getmoney_transfer()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_money_transfer=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAllChartAccountBank(){
		$this->db->select('gl_charts.accountcode,gl_charts.accountname,gl_charts.parent_acc,gl_charts.sectionid');
		$this->db->from('gl_charts');
		$this->db->where('bank', 1);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }

        return FALSE;
	}
	
	public function updateJournal($rows, $old_reference_no = NULL) {
		foreach($rows as $data){
			$gl_chart = $this->getChartAccountByID($data['account_code']);	
			if($gl_chart > 0){
				$data['sectionid'] = $gl_chart->sectionid;
				$data['narrative'] = $gl_chart->accountname;
			}
			
			if($data['tran_id'] != 0){
				$this->db->where('tran_id' , $data['tran_id']);
				$q = $this->db->update('gl_trans', $data);
				if ($q) {
					if($gl_chart->bank == 1){
						$payment = array(
							'date' => $data['tran_date'],
							'transaction_id' => $data['tran_id'],
							'amount' => $data['amount'],
							'reference_no' => $data['reference_no'],
							'paid_by' => $data['narrative'],
							'note' => $data['description'],
							'bank_acc_code' => $data['account_code'],
							'type' => 'received',
							'created_by' => $this->session->userdata('user_id')
						);
						$this->db->update('payments', $payment, array('transaction_id' => $data['tran_id']));
					}
				}
			}else{
				if($this->db->insert('gl_trans', $data)) {
					$tran_id = $this->db->insert_id();
					if($gl_chart->bank == 1){
						$payment = array(
							'date' => $data['tran_date'],
							'transaction_id' => $tran_id,
							'amount' => $data['amount'],
							'reference_no' => $data['reference_no'],
							'paid_by' => $data['narrative'],
							'note' => $data['description'],
							'bank_acc_code' => $data['account_code'],
							'type' => 'received',
							'created_by' => $this->session->userdata('user_id')
						);
						$this->db->insert('payments', $payment);
					}
				}
			}
		} 
	}
	
	public function addJournal($rows) {
		foreach($rows as $data){
			$gl_chart = $this->getChartAccountByID($data['account_code']);
			if($gl_chart > 0){
				$data['sectionid'] = $gl_chart->sectionid;
				$data['narrative'] = $gl_chart->accountname;
			}
			if ($this->db->insert('gl_trans', $data)) {
				$tran_id = $this->db->insert_id();
				if($gl_chart->bank == 1){
					$payment = array(
						'date' => $data['tran_date'],
						'transaction_id' => $tran_id,
						'amount' => $data['amount'],
						'reference_no' => $data['reference_no'],
						'paid_by' => $data['narrative'],
						'note' => $data['description'],
						'bank_acc_code' => $data['account_code'],
						'type' => 'received',
						'created_by' => $this->session->userdata('user_id')
					);
					$this->db->insert('payments', $payment);
				}
				//if ($this->site->getReference('jr') == $data['reference_no']) {
					$this->site->updateReference('jr');
				//}
			}
		}
	}
	
	public function getJournalByTranNoTranID($tran_id, $tran_no){
		$q = $this->db->get_where('gl_trans', array('tran_id' => $tran_id, 'tran_no' => $tran_no), 1);
		if($q->num_rows() > 0){
			$row = $q->row();
			return $row->tr;
		}
		return FALSE;
	}
	
	public function getTranNo(){
		/*
		$this->db->query("UPDATE erp_order_ref
							SET tr = tr + 1
							WHERE
							DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')");
		*/
		/*
		$q = $this->db->query("SELECT tr FROM erp_order_ref
									WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')");
									*/

		$this->db->select('(COALESCE (MAX(tran_no), 0) + 1) AS tr');
		$q = $this->db->get('gl_trans');
		if($q->num_rows() > 0){
			$row = $q->row();
			return $row->tr;
		}
		return FALSE;
	}
	
	public function getTranNoByRef($ref){
		$this->db->select('tran_no');
		$this->db->where('reference_no', $ref);
		$q = $this->db->get('gl_trans');
		if($q->num_rows() > 0){
			$row = $q->row();
			return $row->tran_no;
		}
		return FALSE;
	}
	
	public function getTranTypeByRef($ref){
		$this->db->select('tran_type');
		$this->db->where('reference_no', $ref);
		$q = $this->db->get('gl_trans');
		if($q->num_rows() > 0){
			$row = $q->row();
			return $row->tran_type;
		}
		return FALSE;
	}
	
	public function deleteJournalByRef($ref){
		$q = $this->db->delete('gl_trans', array('reference_no' => $ref));
		if($q){
			return true;
		}
		return false;
	}
	
	public function getJournalByRef($ref){
		$this->db->select('gl_trans.*, (IF(erp_gl_trans.amount > 0, erp_gl_trans.amount, null)) as debit, 
							(IF(erp_gl_trans.amount < 0, abs(erp_gl_trans.amount), null)) as credit');
		$q = $this->db->get_where('gl_trans', array('reference_no' => $ref));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	
	public function getJournalByTranNo($tran_no){
		$this->db->select('gl_trans.*, (IF(erp_gl_trans.amount > 0, erp_gl_trans.amount, null)) as debit, 
							(IF(erp_gl_trans.amount < 0, abs(erp_gl_trans.amount), null)) as credit');
		$q = $this->db->get_where('gl_trans', array('tran_no' => $tran_no));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	
    public function getTransferByID($id)
    {

        $q = $this->db->get_where('transfers', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getAllTransferItems($transfer_id, $status)
    {
        if ($status == 'completed') {
            $this->db->select('purchase_items.*, product_variants.name as variant')
                ->from('purchase_items')
                ->join('product_variants', 'product_variants.id=purchase_items.option_id', 'left')
                ->group_by('purchase_items.id')
                ->where('transfer_id', $transfer_id);
        } else {
            $this->db->select('transfer_items.*, product_variants.name as variant')
                ->from('transfer_items')
                ->join('product_variants', 'product_variants.id=transfer_items.option_id', 'left')
                ->group_by('transfer_items.id')
                ->where('transfer_id', $transfer_id);
        }
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getWarehouseProduct($warehouse_id, $product_id, $variant_id)
    {
        if ($variant_id) {
            $data = $this->getProductWarehouseOptionQty($variant_id, $warehouse_id);
            return $data;
        } else {
            $data = $this->getWarehouseProductQuantity($warehouse_id, $product_id);
            return $data;
        }
        return FALSE;
    }

    public function getWarehouseProductQuantity($warehouse_id, $product_id)
    {
        $q = $this->db->get_where('warehouses_products', array('warehouse_id' => $warehouse_id, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function resetTransferActions($id)
    {
        $otransfer = $this->transfers_model->getTransferByID($id);
        $oitems = $this->transfers_model->getAllTransferItems($id, $otransfer->status);
        $ostatus = $otransfer->status;
        if ($ostatus == 'sent' ||$ostatus == 'completed') {
            // $this->db->update('purchase_items', array('warehouse_id' => $otransfer->from_warehouse_id, 'transfer_id' => NULL), array('transfer_id' => $otransfer->id));
            foreach ($oitems as $item) {
                $option_id = (isset($item->option_id) && ! empty($item->option_id)) ? $item->option_id : NULL;
                $clause = array('purchase_id' => NULL, 'transfer_id' => NULL, 'product_id' => $item->product_id, 'warehouse_id' => $otransfer->from_warehouse_id, 'option_id' => $option_id);
                $pi = $this->site->getPurchasedItem(array('id' => $item->id));
                if ($ppi = $this->site->getPurchasedItem($clause)) {
                    $quantity_balance = $ppi->quantity_balance + $item->quantity;
                    $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), $clause);
                } else {
                    $clause['quantity'] = $item->quantity;
                    $clause['item_tax'] = 0;
                    $clause['quantity_balance'] = $item->quantity;
                    $this->db->insert('purchase_items', $clause);
                }
            }
        }
        return $ostatus;
    }

    public function deleteTransfer($id)
    {
        $ostatus = $this->resetTransferActions($id);
        $oitems = $this->transfers_model->getAllTransferItems($id, $ostatus);
        $tbl = $ostatus == 'completed' ? 'purchase_items' : 'transfer_items';
        if ($this->db->delete('transfers', array('id' => $id)) && $this->db->delete($tbl, array('transfer_id' => $id))) {
            foreach ($oitems as $item) {
                $this->site->syncQuantity(NULL, NULL, NULL, $item->product_id);
            }
            return true;
        }
        return FALSE;
    }

    public function getProductOptions($product_id, $warehouse_id, $zero_check = TRUE)
    {
        $this->db->select('product_variants.id as id, product_variants.name as name, product_variants.cost as cost, product_variants.quantity as total_quantity, warehouses_products_variants.quantity as quantity')
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left')
            ->where('product_variants.product_id', $product_id)
            ->where('warehouses_products_variants.warehouse_id', $warehouse_id)
            ->group_by('product_variants.id');
        if ($zero_check) {
            $this->db->where('warehouses_products_variants.quantity >', 0);
        }
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductComboItems($pid, $warehouse_id)
    {
        $this->db->select('products.id as id, combo_items.item_code as code, combo_items.quantity as qty, products.name as name, warehouses_products.quantity as quantity')
            ->join('products', 'products.code=combo_items.item_code', 'left')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->where('warehouses_products.warehouse_id', $warehouse_id)
            ->group_by('combo_items.id');
        $q = $this->db->get_where('combo_items', array('combo_items.product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }

    public function getProductVariantByName($name, $product_id)
    {
        $q = $this->db->get_where('product_variants', array('name' => $name, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPurchasedItems($product_id, $warehouse_id, $option_id = NULL) {
        $orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->select('id, quantity, quantity_balance, net_unit_cost, unit_cost, item_tax');
        $this->db->where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->where('quantity_balance !=', 0);
        if ($option_id) {
            $this->db->where('option_id', $option_id);
        }
        $this->db->group_by('id');
        $this->db->order_by('date', $orderby);
        $this->db->order_by('purchase_id', $orderby);
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function syncTransderdItem($product_id, $warehouse_id, $quantity, $option_id = NULL)
    {
        if ($pis = $this->getPurchasedItems($product_id, $warehouse_id, $option_id)) {
            $balance_qty = $quantity;
            foreach ($pis as $pi) {
                if ($balance_qty <= $quantity && $quantity > 0) {
                    if ($pi->quantity_balance >= $quantity) {
                        $balance_qty = $pi->quantity_balance - $quantity;
                        $this->db->update('purchase_items', array('quantity_balance' => $balance_qty), array('id' => $pi->id));
                        $quantity = 0;
                    } elseif ($quantity > 0) {
                        $quantity = $quantity - $pi->quantity_balance;
                        $balance_qty = $quantity;
                        $this->db->update('purchase_items', array('quantity_balance' => 0), array('id' => $pi->id));
                    }
                }
                if ($quantity == 0) { break; }
            }
        } else {
            $clause = array('purchase_id' => NULL, 'transfer_id' => NULL, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'option_id' => $option_id);
            if ($pi = $this->site->getPurchasedItem($clause)) {
                $quantity_balance = $pi->quantity_balance - $quantity;
                $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), $clause);
            } else {
                $clause['quantity'] = 0;
                $clause['item_tax'] = 0;
                $clause['quantity_balance'] = (0 - $quantity);
                $this->db->insert('purchase_items', $clause);
            }
        }
        $this->site->syncQuantity(NULL, NULL, NULL, $product_id);
    }
	
	public function getStatementByDate($section = NULL,$from_date= NULL,$to_date = NULL,$biller_id = NULL){
		$where_biller = '';
		if($biller_id != NULL){
			$where_biller = " AND erp_gl_trans.biller_id IN($biller_id) "; 
		}
		$where_date = '';
		if($from_date && $to_date){
			$where_date = " AND erp_gl_trans.tran_date BETWEEN '$from_date'
			AND '$to_date' "; 
		}
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$query = $this->db->query("SELECT
			erp_gl_trans.account_code,
			erp_gl_trans.sectionid,
			erp_gl_charts.accountname,
			erp_gl_charts.parent_acc,
			sum(erp_gl_trans.amount) AS amount,
			erp_gl_trans.biller_id
		FROM
			erp_gl_trans
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		WHERE
			erp_gl_trans.sectionid IN ($section)
			$where_biller
			$where_date
		GROUP BY
			erp_gl_trans.account_code
		");

		return $query;
	}
	
	function getStatementDetailByAccCode($code = NULL, $section = NULL,$from_date= NULL,$to_date = NULL,$biller_id = NULL) {
		if($biller_id != NULL){
			$where_biller = " AND erp_gl_trans.biller_id IN($biller_id) "; 
		}
		$where_date = '';
		if($from_date && $to_date){
			$where_date = " AND erp_gl_trans.tran_date BETWEEN '$from_date'
			AND '$to_date' ";
		}
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$query = $this->db->query("SELECT
			erp_gl_trans.tran_type,
			erp_gl_trans.tran_date,
			erp_gl_trans.reference_no,
			(CASE WHEN erp_sales.customer THEN erp_sales.customer ELSE erp_purchases.supplier END) AS customer,
			(CASE WHEN erp_sales.note THEN erp_sales.note ELSE erp_purchases.note END) AS note,
			erp_companies.company,
			erp_gl_trans.account_code,
			erp_gl_charts.accountname,
			erp_gl_trans.amount,
			erp_gl_trans.biller_id
		FROM
			erp_gl_trans
		LEFT JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		LEFT JOIN erp_companies ON erp_gl_trans.biller_id = erp_companies.id
		LEFT JOIN erp_sales ON erp_sales.reference_no = erp_gl_trans.reference_no
		LEFT JOIN erp_purchases ON erp_purchases.reference_no = erp_gl_trans.reference_no
		WHERE
			erp_gl_trans.account_code = '$code'
			AND	erp_gl_trans.sectionid IN ($section)
			$where_biller 
			$where_date
		GROUP BY
			erp_sales.reference_no,
			erp_gl_trans.account_code
		HAVING amount <> 0
		");
		return $query;
	}
	
	public function getMonthlyIncomes($excep_acccode = NULL, $section = NULL,$from_date, $to_date, $biller_id = NULL)
	{
		$where_biller = '';
		$where_year = '';
		$where_date = '';
		$where_except_code = '';
		if($biller_id){
			$where_biller = " AND erp_gl_trans.biller_id IN($biller_id) "; 
		}
		if(!$year){
			$year = date('Y');
		}
		if($from_date && $to_date){
			$where_date = " AND gl.tran_date BETWEEN '$from_date'
			AND '$to_date' "; 
		}
		if($excep_acccode){
			$where_except_code = " AND gl.account_code NOT IN($excep_acccode) ";
		}
		/*erp_companies.period,
		erp_companies.start_date,
		erp_companies.end_date,
		erp_companies.begining_balance,*/
		//COALESCE(erp_companies.amount, 0) AS total_amount,
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$query = $this->db->query("SELECT
									DATE_FORMAT('$from_date','%Y') AS year,
									erp_gl_trans.biller_id,
									erp_companies.code,
									erp_companies.company,
									erp_companies.name,
									
									
									erp_gl_trans.account_code,
									erp_gl_trans.sectionid,
									erp_gl_charts.accountname,
									erp_gl_charts.parent_acc,
									COALESCE(january.amount, 0) AS jan,
									COALESCE(febuary.amount, 0) AS feb,
									COALESCE(march.amount, 0) AS mar,
									COALESCE(april.amount, 0) AS apr,
									COALESCE(may.amount, 0) AS may,
									COALESCE(june.amount, 0) AS jun,
									COALESCE(july.amount, 0) AS jul,
									COALESCE(august.amount, 0) AS aug,
									COALESCE(september.amount, 0) AS sep,
									COALESCE(october.amount, 0) AS oct,
									COALESCE(november.amount, 0) AS nov,
									COALESCE(december.amount, 0) AS dece,
									(
										COALESCE(january.amount,0) + COALESCE(febuary.amount,0) + COALESCE(march.amount,0) + COALESCE(april.amount,0) + COALESCE(may.amount,0) + COALESCE(june.amount,0) + COALESCE(july.amount,0) + COALESCE(august.amount,0) + COALESCE(september.amount,0) + COALESCE(october.amount,0) + COALESCE(november.amount,0) + COALESCE(december.amount,0)
									) AS total
								FROM
									erp_companies
								LEFT JOIN erp_gl_trans ON erp_companies.id = erp_gl_trans.biller_id
								LEFT JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '01'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS january ON january.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '02'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS febuary ON febuary.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '03'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS march ON march.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '04'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS april ON april.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '05'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS may ON may.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '06'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS june ON june.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '07'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS july ON july.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '08'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS august ON august.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '09'
									AND gl.sectionid IN (40, 70)
									AND gl.account_code = '$acc_code' 
									$where_date
									GROUP BY
										gl.biller_id
								) AS september ON september.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '10'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS october ON october.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '11'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS november ON november.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '12'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_dates
									GROUP BY
										gl.biller_id
								) AS december ON december.biller_id = erp_companies.id
								WHERE
									1 = 1
								AND erp_companies.group_name = 'biller'
								$where_biller
								GROUP BY
									erp_companies.id
								ORDER BY erp_companies.id
		");
		return $query;
	}
	
	public function getStatementByBalaneSheetDate($section = NULL,$from_date= NULL,$to_date = NULL,$biller_id = NULL){
		$where_biller = '';
		if($biller_id != NULL){
			$where_biller = " AND erp_gl_trans.biller_id IN($biller_id) "; 
		}
		$where_date = '';
		if($from_date && $to_date){
			$where_date = " AND date(erp_gl_trans.tran_date) BETWEEN '$from_date'
			AND '$to_date' "; 
		}
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$query = $this->db->query("SELECT
			erp_gl_trans.account_code,
			erp_gl_trans.sectionid,
			erp_gl_charts.accountname,
			erp_gl_charts.parent_acc,
			sum(erp_gl_trans.amount) AS amount,
			erp_gl_trans.biller_id
		FROM
			erp_gl_trans
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		WHERE 
			erp_gl_trans.sectionid IN ($section)
			$where_biller
			$where_date
		GROUP BY
			erp_gl_trans.account_code
		");

		return $query;
	}
	public function getStatementBalaneSheetByDateBill($section = NULL,$from_date= NULL,$to_date = NULL,$biller_id = NULL){
		$where_biller = '';
		if($biller_id != NULL){
			$where_biller = " AND erp_gl_trans.biller_id IN($biller_id) "; 
		}
		$where_date = '';
		if($from_date && $to_date){
			$where_date = " AND date(erp_gl_trans.tran_date) BETWEEN '$from_date'
			AND '$to_date' "; 
		}
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$query = $this->db->query("SELECT
			erp_gl_trans.account_code,
			erp_gl_trans.sectionid,
			erp_gl_charts.accountname,
			erp_gl_charts.parent_acc,
			sum(erp_gl_trans.amount) AS amount,
			erp_gl_trans.biller_id
		FROM
			erp_gl_trans
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		WHERE
			erp_gl_trans.sectionid IN ($section)
			$where_biller
			$where_date
		GROUP BY
			erp_gl_trans.account_code,
			biller_id
		");

		return $query;
	}
	
	function getBalanceSheetDetailByAccCode($code = NULL, $section = NULL,$from_date= NULL,$to_date = NULL,$biller_id = NULL) {
		if($biller_id != NULL){
			$where_biller = " AND erp_gl_trans.biller_id IN ($biller_id) "; 
		}
		$where_date = '';
		if($from_date && $to_date){
			$where_date = " AND erp_gl_trans.tran_date BETWEEN '$from_date'
			AND '$to_date' ";
		}
		$query = $this->db->query("SELECT
			erp_gl_trans.tran_type,
			erp_gl_trans.tran_date,
			erp_gl_trans.reference_no,
			(
				CASE
				WHEN erp_gl_trans.tran_type = 'SALES' THEN
					(
						SELECT
							erp_sales.customer
						FROM
							erp_sales
						WHERE
							erp_gl_trans.reference_no = erp_sales.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'PURCHASES' THEN
					(
						SELECT
							erp_purchases.supplier
						FROM
							erp_purchases
						WHERE
							erp_gl_trans.reference_no = erp_purchases.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'SALES-RETURN' THEN
					(
						SELECT
							erp_return_sales.customer
						FROM
							erp_return_sales
						WHERE
							erp_return_sales.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'PURCHASES-RETURN' THEN
					(
						SELECT
							erp_return_purchases.supplier
						FROM
							erp_return_purchases
						WHERE
							erp_return_purchases.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'DELIVERY' THEN
					(
						SELECT
							erp_deliveries.customer
						FROM
							erp_deliveries
						WHERE
							erp_deliveries.do_reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type != 'JOURNAL' THEN
					(
						SELECT
							erp_companies.name
						FROM
							erp_companies
						WHERE
							erp_gl_trans.biller_id = erp_companies.id
					)
				ELSE
					(
						erp_gl_trans.created_name
					)				
				END
			) AS customer,
			(
				CASE
				WHEN erp_gl_trans.tran_type = 'SALES' THEN
					(
						SELECT
							erp_sales.note
						FROM
							erp_sales
						WHERE
							erp_gl_trans.reference_no = erp_sales.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'PURCHASES' THEN
					(
						SELECT
							erp_purchases.note
						FROM
							erp_purchases
						WHERE
							erp_gl_trans.reference_no = erp_purchases.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'SALES-RETURN' THEN
					(
						SELECT
							erp_return_sales.note
						FROM
							erp_return_sales
						WHERE
							erp_return_sales.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'PURCHASES-RETURN' THEN
					(
						SELECT
							erp_return_purchases.note
						FROM
							erp_return_purchases
						WHERE
							erp_return_purchases.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'DELIVERY' THEN
					(
						SELECT
							erp_deliveries.note
						FROM
							erp_deliveries
						WHERE
							erp_deliveries.do_reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				ELSE
					''
				END
			) AS note,
			erp_gl_trans.description,
			erp_gl_trans.account_code,
			erp_gl_charts.accountname,
			SUM(erp_gl_trans.amount) AS amount,
			erp_gl_trans.biller_id
		FROM
			erp_gl_trans
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		WHERE
			erp_gl_trans.account_code = '$code'
			AND	erp_gl_trans.sectionid IN ($section)
			$where_biller 
			$where_date
		GROUP BY
			erp_gl_trans.reference_no,
			erp_gl_trans.account_code,
			erp_gl_trans.tran_id
		HAVING amount <> 0
		");
		return $query;
	}
	
	function getBalanceSheetDetailPurByAccCode($code = NULL, $section = NULL,$from_date= NULL,$to_date = NULL,$biller_id = NULL) {
		if($biller_id != NULL){
			$where_biller = " AND erp_gl_trans.biller_id IN($biller_id) "; 
		}
		$where_date = '';
		if($from_date && $to_date){
			$where_date = " AND erp_gl_trans.tran_date BETWEEN '$from_date'
			AND '$to_date' ";
		}
		$query = $this->db->query("SELECT
			erp_gl_trans.tran_type,
			erp_gl_trans.tran_date,
			erp_gl_trans.reference_no,
			(
				CASE
				WHEN erp_gl_trans.tran_type = 'SALES' THEN
					(
						SELECT
							erp_sales.customer
						FROM
							erp_sales
						WHERE
							erp_gl_trans.reference_no = erp_sales.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'PURCHASES' OR erp_gl_trans.tran_type = 'PURCHASE EXPENSE' THEN
					(
						SELECT
							erp_purchases.supplier
						FROM
							erp_purchases
						WHERE
							erp_gl_trans.reference_no = erp_purchases.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'SALES-RETURN' THEN
					(
						SELECT
							erp_return_sales.customer
						FROM
							erp_return_sales
						WHERE
							erp_return_sales.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'PURCHASES-RETURN' THEN
					(
						SELECT
							erp_return_purchases.supplier
						FROM
							erp_return_purchases
						WHERE
							erp_return_purchases.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'DELIVERY' THEN
					(
						SELECT
							erp_deliveries.customer
						FROM
							erp_deliveries
						WHERE
							erp_deliveries.do_reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'STOCK_ADJUST' THEN
					(
						SELECT
							'' AS customer
						FROM
							erp_adjustments
						WHERE
							erp_adjustments.id = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				ELSE
					''
				END
			) AS customer,
			(
				CASE
				WHEN erp_gl_trans.tran_type = 'SALES' THEN
					(
						SELECT
							erp_sales.note
						FROM
							erp_sales
						WHERE
							erp_gl_trans.reference_no = erp_sales.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'PURCHASES' OR erp_gl_trans.tran_type = 'PURCHASE EXPENSE' THEN
					(
						SELECT
							erp_purchases.note
						FROM
							erp_purchases
						WHERE
							erp_gl_trans.reference_no = erp_purchases.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'SALES-RETURN' THEN
					(
						SELECT
							erp_return_sales.note
						FROM
							erp_return_sales
						WHERE
							erp_return_sales.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'PURCHASES-RETURN' THEN
					(
						SELECT
							erp_return_purchases.note
						FROM
							erp_return_purchases
						WHERE
							erp_return_purchases.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'DELIVERY' THEN
					(
						SELECT
							erp_deliveries.note
						FROM
							erp_deliveries
						WHERE
							erp_deliveries.do_reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'STOCK_ADJUST' THEN
					(
						SELECT
							erp_adjustments.note
						FROM
							erp_adjustments
						WHERE
							erp_adjustments.id = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				ELSE
					''
				END
			) AS note,
			erp_gl_trans.account_code,
			erp_gl_charts.accountname,
			SUM(erp_gl_trans.amount) AS amount,
			erp_gl_trans.biller_id
		FROM
			erp_gl_trans
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		WHERE
			erp_gl_trans.account_code = '$code'
			AND	erp_gl_trans.sectionid IN ($section)
			$where_biller 
			$where_date
		GROUP BY
			erp_gl_trans.reference_no,
			erp_gl_trans.account_code,
			erp_gl_trans.biller_id
		HAVING amount <> 0
		");
		return $query;
	}
	
	public function addJournals($data = array())
    {
        if ($this->db->insert_batch('gl_trans', $data)) {
            return true;
        }
        return false;
    }
	public function addCharts($data = array())
    {
        if ($this->db->insert_batch('gl_charts', $data)) {
            return true;
        }
        return false;
    }
	public function getSectionIdByCode($code)
    {

        $q = $this->db->get_where('gl_charts', array('accountcode' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }
	
	public function getAccountCode($accountcode){
		$this->db->select('accountcode');
		$q = $this->db->get_where('gl_charts', array('accountcode' => $accountcode), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
	}
	public function getConditionTax(){
		$this->db->where('id','1');
		$q=$this->db->get('condition_tax');
		return $q->result();
	}
	public function getConditionTaxById($id){
		$this->db->where('id',$id);
		$q=$this->db->get('condition_tax');
		return $q->row();
	}
	public function update_exchange_tax_rate($id,$data){
		$this->db->where('id',$id);
		$update=$this->db->update('condition_tax',$data);
		if($update){
			return true;
		}
	} 
	
	public function getKHM(){
		$q = $this->db->get_where('currencies', array('code'=> 'KHM'), 1);
		if($q->num_rows() > 0){
			$q = $q->row();
            return $q->rate;
		}
	}
	
	public function addConditionTax($data){
		if ($this->db->insert('condition_tax', $data)) {
            return true;
        }
        return false;
	}
	
	public function deleteConditionTax($id){
		$q = $this->db->delete('condition_tax', array('id' => $id));
		if($q){
			return true;
		} else{
			return false;
		}
	}
	
	public function getCustomersDepositByCustomerID($customer_id){
		$q = $this->db
    		->select("deposits.id as dep_id, companies.id AS id , date,companies.name, companies.deposit_amount AS amount, paid_by, CONCAT(erp_users.first_name, ' ', erp_users.last_name) as created_by", false)
    		->from("deposits")
    		->join('users', 'users.id=deposits.created_by', 'inner')
    		->join('companies', 'deposits.company_id = companies.id', 'inner')
    		->where('deposits.amount <>', 0)
			->where('companies.id', $customer_id)
			->get();
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	public function getStatementByDateBill($section = NULL,$from_date= NULL,$to_date = NULL,$biller_id = NULL){
		$where_biller = '';
		if($biller_id != NULL){
			$where_biller = " AND erp_gl_trans.biller_id IN($biller_id) "; 
		}
		$where_date = '';
		if($from_date && $to_date){
			$where_date = " AND erp_gl_trans.tran_date BETWEEN '$from_date'
			AND '$to_date' "; 
		}
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$query = $this->db->query("SELECT
			erp_gl_trans.account_code,
			erp_gl_trans.sectionid,
			erp_gl_charts.accountname,
			erp_gl_charts.parent_acc,
			sum(erp_gl_trans.amount) AS amount,
			erp_gl_trans.biller_id
		FROM
			erp_gl_trans
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		WHERE
			erp_gl_trans.sectionid IN ($section)
			$where_biller
			$where_date
		GROUP BY
			erp_gl_trans.account_code,
			biller_id
		");

		return $query;
	}
	
	public function getBankAccount(){
		$this->db->select('accountcode, accountname');
		$this->db->where('gl_charts.bank','1');
		$q = $this->db->get('gl_charts');
        if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;		
	}
	/*public function getBankAccountByBranch($branch_id){
		$this->db->select('gl_charts.accountcode
							FROM
								gl_charts
							WHERE
								gl_charts.bank = 1,								
						   gl_charts.accountname
						   FROM
								gl_charts
							WHERE
								gl_charts.bank = 1,
								
							erp_branches.amount
							FROM
								erp_branches
							LEFT JOIN erp_branches ON erp_branches.account_code = erp_gl_charts.accountcode
							WHERE
								erp_branches.id = ($branch_id)
							
							');
		
		$this->db->where('gl_charts.bank','1');
		$q = $this->db->get('gl_charts');
        if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;		
	}*/
	
	 public function getBankAccountByBranch($branch_id){
		$this->db->select('gl_charts.accountcode, gl_charts.accountname, branches.amount');		
		$this->db->join('branches','gl_charts.accountcode = branches.account_code','left');
		$this->db->where('gl_charts.bank','1');
		$this->db->where('branches.id',$branch_id);
		$q = $this->db->get('gl_charts');
        if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;		
	} 
	public function getCurrncy(){
		$q = $this->db->get('currencies');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;
	}

	public function getSettingCurrncy(){
		$this->db->select('currencies.rate, currencies.code, currencies.name');
		$this->db->from('settings');
		$this->db->join('currencies', 'settings.default_currency = currencies.code');		
		$q = $this->db->get();       
		 if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getContract(){
		$this->db->select('id, reference_no, total');
		$this->db->where('sale_status','activated');
		$q = $this->db->get('sales');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;
	}
	public function getContractDiburse($bid){
		$this->db->select('id, reference_no, total');
		$this->db->where(array('sales.branch_id' => $bid));
		$this->db->where('grand_total < total');
		$q = $this->db->get('sales');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;
	}
	
	public function get_setting() {
        $q = $this->db->get('settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getContractBySaleId($ids){
		$this->db->select('companies.id');
		$this->db->join('companies','companies.id = sales.branch_id','left');
		$this->db->where('sales.id', $ids);
		$this->db->from('sales');
		$q = $this->db->get(); 
		 if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getSaleById($ids){
		$this->db->select('sales.*, CONCAT(erp_companies.family_name," ",erp_companies.name) AS customer_name');
		$this->db->join('companies','companies.id = sales.customer_id','left');
		$q = $this->db->get_where('sales', array('sales.id' => $ids));
		 if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getSaleItemBysaleID($ids){
		$q = $this->db->get_where('sale_items', array('sale_id' => $ids),1);
		 if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function addDisbursement($data = array() , $services= array() ){
        if($data) {
			if($this->db->insert('payments',$data)){
				$payment_id = $this->db->insert_id();
				$default_crrency = $this->getSettingCurrncy();
				$lease_amount = $data['amount'];				
				//$branch = $this->getContractBySaleId($data['sale_id']);
				$sales = $this->getSaleById($data['sale_id']);
				$total = $sales->total;
				//$branch_amount = $branch->amount;
				//$new_branchamount = ($branch_amount - $lease_amount) + $data['service_amount'];
				$amount_payment = $this->erp->syncDisbursPayment($data['sale_id']);
				$payment_status = '';
				$payment_amount = $sales->grand_total + $lease_amount;				
				if ( $payment_amount > 0){
					if($payment_amount < $sales->total){
						$payment_status = 'partial';
					} else if ($payment_amount == $sales->total){
						$payment_status = 'paid';
					}
				} else{
					$payment_status = 'due';
				}
				
				//$this->db->update('companies', array('amount' => $new_branchamount), array('id' => $branch->id));
				$this->db->update('sales',array('sale_status' => 'activated', 'paid'=> $data['service_amount'], 'grand_total'=> $amount_payment, 'payment_status'=>$payment_status ),array('id'=> $data['sale_id']));	
				$this->db->update('quotes', array('status' => 'activated'), array('id' => $sales->quote_id));
					
				//if ($this->site->getReference('pp') == $data['reference_no']) {
					$this->site->updateReference('pp');
				//}				
				if($services) {
					foreach($services as $service) {
						$service['payment_id'] = $payment_id;
						$this->db->insert('service_payments', $service);
					}
				}			
			}
			return true;
		}
		return false;
    }
	
	public function getLoanOwedBySaleId($id = NULL) {
		$this->db->select('SUM(owed) as owed');
		$q = $this->db->get_where('loans', array('sale_id' => $id));
		 if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getloanBySaleID($sale_id){
		$this->db->select('id,payment,dateline,principle,interest,balance');
		$this->db->where('sale_id', $sale_id);
		$this->db->where('paid_amount', 0);
		$q = $this->db->get('loans');
        if ($q->num_rows() > 0){
            return $q->row();
        }
		return FALSE;
	}

	public function getAjaxSaleById($ids){
		$this->db->select('sales.total,sales.grand_total, sale_items.currency_code, sales.id,CONCAT(erp_companies.family_name," ",erp_companies.name) AS customer_name,currencies.name as curr_name, ');
		$this->db->where('sales.id', $ids);
		$this->db->from('sales');
		$this->db->join('sale_items', 'sale_items.sale_id = sales.id','left');
		$this->db->join('currencies','currencies.code = sale_items.currency_code','left');
		$this->db->join('companies as branch','branch.id = sales.branch_id','left');
		$this->db->join('companies','sales.customer_id = companies.id','left');
		$q = $this->db->get();       
		 if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getLastLoanPayment($id) {
		$this->db->select('loans.*');
		$this->db->order_by('id','DESC');
		$this->db->where('paid_amount !=', 0);
		$this->db->where(array('sale_id' => $id),1);
		$q = $this->db->get('loans');
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getServicepayment($p_id){
		$this->db->select('amount, payment_id, service_id, owed');
		$q = $this->db->get_where('service_payments', array('payment_id' => $p_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
		return FALSE;
	}
	
	public function getLastOldPayment($id) {
		$this->db->select('payments.*');
		$this->db->order_by('id','DESC');
		$this->db->where('loan_id !=', null);
		$this->db->where(array('sale_id' => $id),1);
		$q = $this->db->get('payments');
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getMaxLoanBySaleID($sale_id){		
		$this->db->order_by('id', 'DESC');
		$this->db->where(array('sale_id' => $sale_id),1);
		$q = $this->db->get_where('loans');
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function addDeposit($data = array() , $services = array(), $old_payments = array(), $oldservices = array() ){
		$sale_id = $data['sale_id'];
		$loan_id = $data['loan_id'];
		
		$last_payment = $this->getLastOldPayment($sale_id);
		$last_loan_payment = $this->getLastLoanPayment($sale_id);
		$MaxLoan = $this->getMaxLoanBySaleID($sale_id);
					
        if($data) {
			if($this->db->insert('payments',$data)){
				$payment_id = $this->db->insert_id();
				$this->site->updateReference('sp');
				$setting = $this->get_setting();
				$sale_items = $this->getSaleItemBysaleID($data['sale_id']);	
				$sales = $this->getSaleById($data['sale_id']);
				//$branch = $this->getContractBySaleId($data['sale_id']);				
				//$new_branchamount = $branch->amount + $data['amount'];
				$new_paid = $sales->paid + $data['amount'];				
				$paid_amount = $this->erp->convertCurrency($sale_items->currency_code, $setting->default_currency, $data['amount']);
				$balance = $this->erp->convertCurrency($sale_items->currency_code, $setting->default_currency, $data['owed']);
				$total_sv = $this->erp->convertCurrency($sale_items->currency_code, $setting->default_currency, $data['service_amount']);
				$penalty = $this->erp->convertCurrency($sale_items->currency_code, $setting->default_currency, $data['penalty_amount']);
				$other = $this->erp->convertCurrency($sale_items->currency_code, $setting->default_currency, $data['other_paid']);
				
				$this->db->update('payments',$old_payments, array('id' => $last_payment->id));
				$this->db->update('sales',array('paid'=> $new_paid),array('id'=> $data['sale_id']));
				//$this->db->update('companies', array('amount' => $new_branchamount), array('id' => $branch->id));
				$this->db->update('loans',array('paid_amount' => $paid_amount , 'owed' => $balance, 'total_service_charge' => $total_sv, 'paid_date' => $data['date'], 'reference_no' => $data['reference_no'], 'created_by'=>$data['created_by'], 'biller_id'=>$data['biller_id'], 'overdue_amount'=>$penalty, 'other_amount'=>$other ),array('id'=> $data['loan_id']));
				$this->db->update('loans',array('paid_amount' => $paid_amount , 'owed' => 0),array('id'=> $last_loan_payment->id));
					
				if(is_array($oldservices)){
					if($this->db->delete('service_payments', array('payment_id' => $last_payment->id))) {
						foreach ($oldservices as $old_sv) {
							$this->db->insert('service_payments', $old_sv);
						}
					}
				}
				if($services){
					foreach($services as $service){
						$service['payment_id'] = $payment_id;
						$this->db->insert('service_payments', $service);
					}
				}
				if($loan_id == $MaxLoan->id){
					if($data['owed'] == 0){
						$this->db->update('sales', array('sale_status' => "completed"), array('id' => $sale_id));
						$this->db->update('quotes', array('status' => "completed"), array('id' => $sales->quote_id));
					}
				}
			}
			return true;
		}
		return false;
    }
	
	public function getServicesBySaleID($id = NULL){
		$this->db->select('services.id, services.description,sale_services.amount, sale_services.service_paid ,sale_services.type, sale_services.tax_rate, sale_services.charge_by');
        $this->db->join('services', 'services.id=sale_services.services_id', 'INNER');
		$q = $this->db->get_where('sale_services', array('sale_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
		return FALSE;
	}
	
	public function getServicesBidursSaleID($id = NULL){
		$this->db->select('services.id, services.description,sale_services.amount, sale_services.service_paid, sale_services.type, sale_services.tax_rate, sale_services.charge_by');
        $this->db->join('services', 'services.id=sale_services.services_id', 'INNER');
		$this->db->join('sales','sales.id = sale_services.sale_id ','left');
		$this->db->where('sales.grand_total',0);
		$q = $this->db->get_where('sale_services', array('sale_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
		return FALSE;
	}
	
	public function getOneServicesBySaleID($id = NULL){
		$this->db->select('services.id, services.description,sale_services.amount, sale_services.service_paid, sale_services.type, sale_services.tax_rate, sale_services.charge_by');
        $this->db->join('services', 'services.id=sale_services.services_id', 'INNER');
		$this->db->join('sales','sales.id = sale_services.sale_id ','left');
		$this->db->where('sale_services.service_paid',1);
		$this->db->where('sales.grand_total',0);
		$q = $this->db->get_where('sale_services', array('sale_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
		return FALSE;
	}
	
	public function getServicesPaymentBySaleID($id = NULL){
		$this->db->select('services.id, services.description,sale_services.amount, sale_services.service_paid, sale_services.type, sale_services.tax_rate, sale_services.charge_by');
        $this->db->join('services', 'services.id=sale_services.services_id', 'INNER');
		$this->db->where('sale_services.service_paid !=',1);
		$q = $this->db->get_where('sale_services', array('sale_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
		return FALSE;
	}
	
	public function getLoanperiodBySaleId($id = NULL) {
		$this->db->select('COUNT(period)');
		$q = $this->db->get_where('loans', array('sale_id' => $id));
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	} 
	
	public function deleteGltranByAccount($ids = false){
		if($ids){
			$result = $this->db->where_in("tran_id",$ids)->delete("gl_trans");
			if($result) {
				$this->db->where_in("transaction_id",$ids)->delete("payments");
			}
			return $result;
		}
		return false;
	}
	
	public function getDelJournalByTranNo($tran_no, $arr_tran_id = array()) {
		$this->db->select('tran_id');
		if($arr_tran_id) {
			$this->db->where_not_in('tran_id', $arr_tran_id);
		}
		$q = $this->db->get_where('gl_trans', array('tran_no' => $tran_no));
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}
}
