<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Quotes_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
	public function AddCollateral($data) {
		if($data) {
			if($this->db->insert('collateral',$data)){
				//if ($this->site->getReference('cl') == $data['code']) {
					$this->site->updateReference('cl');
				//}
			}
			return true;
		}
		return false;					
	}		
    public function getProductNames($term, $warehouse_id, $limit = 5)
    {
        $this->db->select('products.id, code, name, type, warehouses_products.quantity, price, tax_rate, tax_method')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
        // if ($this->Settings->overselling) {
            $this->db->where("(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        // } else {
        //     $this->db->where("(products.track_quantity = 0 OR warehouses_products.quantity > 0) AND warehouses_products.warehouse_id = '" . $warehouse_id . "' AND "
        //         . "(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        // }
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
    public function getProductByCode($code)
    {
        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }	
	public function getProducts(){
		$q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}	
	public function getCategories(){
		$q = $this->db->get('categories');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	public function getSubCatByCatID($category_id){
		$this->db->select('id as id, name as text');
		$this->db->where('category_id', $category_id);
		$q = $this->db->get('subcategories');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}	
	public function getProductBySubCatID($sub_category_id){
		$this->db->select('id as id, name as text');
		$this->db->where('subcategory_id', $sub_category_id);
		$q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	public function getProductBySubCatID2($id){
		$this->db->select('group_loan');
		$this->db->where('id', $id);
		$q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            return $q->row()->group_loan;
        }
		return false;
	}
    public function getWHProduct($id)
    {
        $this->db->select('products.id, code, name, warehouses_products.quantity, cost, tax_rate')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
        $q = $this->db->get_where('products', array('warehouses_products.product_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getItemByID($id)
    {
        $q = $this->db->get_where('quote_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getAllQuoteItemsWithDetails($quote_id)
    {
        $this->db->select('quote_items.id, quote_items.product_name, quote_items.product_code, quote_items.quantity, quote_items.serial_no, quote_items.tax, quote_items.unit_price, quote_items.val_tax, quote_items.discount_val, quote_items.gross_total, products.details');
        $this->db->join('products', 'products.id=quote_items.product_id', 'left');
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('quotes_items', array('quote_id' => $quote_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	public function GetquotesList(){
		$setting = $this->getSettingCurrncies();
		$this->db->select($this->db->dbprefix('quotes').".id,".
					$this->db->dbprefix('quotes').".reference_no,".
					$this->db->dbprefix('loan_groups').".name AS glname,
					CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) AS customer_name_en,
					CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_kh, ".	
					$this->db->dbprefix('quote_items').".product_name AS asset,".
					"((SELECT erp_companies.name FROM erp_companies WHERE erp_quotes.biller_id = erp_companies.id)) AS dealer_name, ".
					
					$this->db->dbprefix('quotes').".quote_status as status, 
					DATE_FORMAT(".$this->db->dbprefix('quotes').".date),
					DATE_FORMAT(".$this->db->dbprefix('quotes').".approved_date),
					CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) AS coname,
					myBranch.name,".
					$this->db->dbprefix('quotes').".total * (".$this->db->dbprefix('currencies').".rate / ".$setting->rate ."),".
					$this->db->dbprefix('currencies').".name as crname ")
			->from('quotes')
			->join('users','quotes.by_co=users.id','INNER') 
			->join('companies','quotes.customer_id=companies.id','INNER')
			->join('companies as myBranch', 'quotes.branch_id = myBranch.id', 'left')
			->join('quote_items', 'quotes.id = quote_items.quote_id', 'left')
			->join('currencies','currencies.code = quote_items.currency_code','left')
			->join('loan_groups','loan_groups.id = quotes.loan_group_id','left')
			->where('erp_quotes.status', 'loans')
			->order_by('quotes.id','DESC');
	}
	public function getAllQuote()
    {
        $this->db->from('quotes'); 
		$q = $this->db->get();       
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getQuoteByID($id)
    {
        $this->db->select('quotes.*,users.first_name,users.last_name');
		$this->db->where('quotes.id',$id);
		$this->db->join('users','quotes.by_co = users.id');
		$this->db->from('quotes');		
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
    }
	
	public function getUserQuoteByID($id)
    {
        $this->db->select('users.*');
		$this->db->where('quotes.id',$id);
		$this->db->join('users','quotes.by_co = users.id');
		$this->db->from('quotes');		
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
    }
	public function getContractByID($id)
    { 
		$this->db
                ->select($this->db->dbprefix('sales').".id,".
						$this->db->dbprefix('sales').".reference_no,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other, 
						CONCAT(".$this->db->dbprefix('users').".first_name,' ',".$this->db->dbprefix('users').".last_name) as co_name , ".
						$this->db->dbprefix('users').".username,
						myBranch.name as branch_name,".		
						$this->db->dbprefix('sale_items').".product_name, 
						CONCAT(TRUNCATE((".$this->db->dbprefix('sales').".interest_rate*100), 2), '', '%') as interest, 
						CONCAT(TRUNCATE(".$this->db->dbprefix('sales').".term, 0), ' ', 'days') as term, ".						
						$this->db->dbprefix('sales').".total,						
						IF(".$this->db->dbprefix('sales').".frequency = 1, 'daily', IF(".$this->db->dbprefix('sales').".frequency = 7, 'Weekly', IF(".$this->db->dbprefix('sales').".frequency = 14, 'Two Week', IF(".$this->db->dbprefix('sales').".frequency = 30, 'Monthly','')))) as pay_term,
						((COALESCE(".$this->db->dbprefix('sales').".grand_total, 0) - COALESCE(".$this->db->dbprefix('sales').".advance_payment, 0))) as total_amount,".
						$this->db->dbprefix('sales').".sale_status, ".
						$this->db->dbprefix('sales').".approved_date, ".
						$this->db->dbprefix('sales').".grand_total, ".
						$this->db->dbprefix('sales').".frequency, ".
						$this->db->dbprefix('currencies').".name as currency_name, ".
						$this->db->dbprefix('sale_items').".currency_code, ".
						$this->db->dbprefix('sales').".mfi as mfi")
                ->from('sales')
				->join('users','sales.by_co=users.id','INNER')
				->join('sale_items', 'sales.id = sale_items.sale_id', 'INNER')
				->join('currencies', 'sale_items.currency_code = currencies.code','left')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('companies as myBranch', 'sales.branch_id= myBranch.id')
				->join('products', 'sale_items.product_id = products.id', 'INNER')
				->where($this->db->dbprefix('sales').'.id', $id)
				//->where($this->db->dbprefix('sales').'.sale_status', 'activated')
				//->or_where($this->db->dbprefix('sales').'.sale_status', 'registered')
				->group_by('sales.id');
				$q = $this->db->get();
				if ($q->num_rows() > 0) {
					return $q->row();
				}
				return FALSE;	
    }
	public function getApplicantByID($id)
    {
		$this->db
				->select($this->db->dbprefix('quotes').".id,".
						$this->db->dbprefix('quotes').".reference_no,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) AS customer_name_en,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_kh, ".	
						$this->db->dbprefix('quote_items').".product_name AS asset,".
						"((SELECT erp_companies.name FROM erp_companies WHERE erp_quotes.biller_id = erp_companies.id)) AS dealer_name, ".
						
						$this->db->dbprefix('quotes').".quote_status as status,".
						$this->db->dbprefix('quotes').".date as submit_date,".
						$this->db->dbprefix('quotes').".approved_date, 
						CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) AS co_name,
						,myBranch.name,".	
						$this->db->dbprefix('quotes').".grand_total")
                ->from('quotes')
				->join('users','quotes.created_by=users.id','INNER')
				->join('sales', 'sales.quote_id = quotes.id', 'left')
				->join('companies','quotes.customer_id=companies.id','INNER')
				->join('companies as myBranch', 'users.branch_id = myBranch.id')
				->join('quote_items', 'quotes.id = quote_items.quote_id', 'left')
				->where(array('quotes.id'=>$id));
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			return $q->row();
		}
		return FALSE;
				
    }
	/*public function getApplicantByID($id)
    {
		$this->db-select('quotes.reference_no,CONCAT(erp_companies.family_name,'.',erp_companies.name) AS customer_name_en,
						CONCAT(erp_companies.family_name_other,'.',erp_companies.name_other) AS customer_name_kh,
						quote_items.product_name,quotes.status, quotes.date, quotes.approved_date,
						CONCAT(erp_users.first_name,'.',erp_users.last_name) AS co_name,users.myBranch.name, quotes.grand_total						
		');	
		$this->db->where('quotes.id',$id);
        $this->db->from('quotes');
		$this->db->join('users','users.id=quotes.created_by');
		$this->db->join('sales', 'sales.quote_id = quotes.id');
		$this->db->join('companies','companies.id=quotes.customer_id');
		$this->db->join('companies as myBranch', 'users.branch_id = myBranch.id');
		$this->db->join('quote_items', 'quotes.id = quote_items.quote_id');
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			return $q->row();
		}
		return FALSE;
				
    }*/
	
    public function get_QuoteByID($id)
    {
        $q = $this->db->get_where('quotes', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getAllQuoteItems($quote_id)
    {
        $this->db->select('quote_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, products.unit, products.details as details, product_variants.name as variant')
            ->join('products', 'products.id=quote_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=quote_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=quote_items.tax_rate_id', 'left')
            ->group_by('quote_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('quote_items', array('quote_id' => $quote_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	/*public function ImportCustomers($data = array()){
		if($this->db->insert_batch('companies',$data)){
			return true;
		}else{
			return false;
		}
		
	}*/
    public function addQuote($data = array(), $items = array(), $services = array(), $guarantor_ = array(), $employee_ = array(), $documentsArray = array(), $customers = array(), $field_check = array(),$collateral_= array(), $group_loan = array(),  $join_lease = array(), $join_guarantor = array(), $saving = array(), $saving_item = array())
    {	
        $guarantor  = $this->db;
        $employee   = $this->db;
		$jl = $this->db;
		$jg = $this->db;
		$grl_id = null;
		
		
		if($guarantor_) {
			if($guarantor->insert('companies', $guarantor_))
			{
				$data['guarantor_id'] = $guarantor->insert_id();
			}
		}
		
		if($employee_) {
			if($employee->insert('qoute_employee', $employee_))
			{
				$data['employee_id']  = $employee->insert_id();
			}
		}
		
		if($join_lease) {
			if($jl->insert('companies', $join_lease)) {
				$data['join_lease_id'] = $jl->insert_id();
			}
		}
		
		if($join_guarantor) {
			if($jg->insert('companies', $join_guarantor)) {
				$data['join_guarantor_id'] = $jg->insert_id();
			}
		}
		
		if($group_loan) {
			if ($this->db->insert('loan_groups', $group_loan)) {
				$grl_id = $this->db->insert_id();
			}
		}
		/*if($customers) {
			$customers['loan_group_id']=$grl_id;
			if ($this->db->insert('companies', $customers)) {
				$cid = $this->db->insert_id();
				$data['customer_id'] = $cid;
			}
		}*/
		
		$identify = $this->site->getIdentifyByGVID($customers['gov_id']);
		$gv = $identify->gov_id;
		$com_id = $identify->id;

		if($gv){
				$customer_field = $this->db->get_where('companies', array('id' => $com_id));
				if($customer_field->row()) {
					$this->db->update('companies', $customers, array('id' => $com_id));
				}
			}else{
				if($customers) {
				$customers['loan_group_id']=$grl_id;
				if ($this->db->insert('companies', $customers)) {
					$com_id = $this->db->insert_id();
					$data['customer_id'] = $com_id;
				}
			}
		}
		if($data) {
				$data['loan_group_id']=$grl_id;
				$data['customer_id']= $com_id;
			if ($this->db->insert('quotes', $data)) {
				$quote_id = $this->db->insert_id();
				
				if(is_array($items)){
					foreach ($items as $item) {
						$item['quote_id'] = $quote_id;
						$this->db->insert('quote_items', $item);
					}
				}
				
				if($collateral_) {
					$collateral_['quote_id']=$quote_id;
					$this->db->insert('collateral', $collateral_);
					$this->site->updateReference('cl');
				}
				
				if($saving){
					$saving['quotes_id']= $quote_id;
					$saving['customer_id'] = $com_id;
					$this->db->insert('quotes', $saving);
					$saving_id = $this->db->insert_id();
					
					$saving_item['quote_id']=$saving_id;
					$this->db->insert('quote_items', $saving_item);
				}
				
				$this->site->updateReference('qu');
				
				foreach($documentsArray as $docs)
				{
					$this->db->insert('quote_photos',array('quote_id' => $quote_id, 'name' => $docs['name'], 'type' => $docs['type']));
				}

				#insert Service into table qoute_service
				if(is_array($services)){
					foreach ($services as $sv) {
						$sv['quote_id'] = $quote_id;
						$this->db->insert('quote_services', $sv);
					}
				}
				
				if($field_check) {
					$field_check['quote_id'] = $quote_id;
					$this->db->insert('field_check', $field_check);
				}
				
				return $quote_id;
			}
		}
		
        return false;
    }
	public function addApplicant($data = array(), $items = array(), $services = array(), $guarantor_ = array(), $employee_ = array(), $documentsArray = array(), $customers = array(), $field_check = array(),$collateral_= array(), $join_lease = array(), $join_guarantor = array(), $saving = array(), $saving_item =array())
    {	
		
        $guarantor  = $this->db;
        $employee   = $this->db;
		$jl = $this->db;
		$jg = $this->db;
		//$quote        		 = $this->getQuoteByID($id);		
		//$loan_group_id 		 = $quote->loan_group_id;
		
		if($customers) {
			if ($this->db->insert('companies', $customers)) {
				$cid = $this->db->insert_id();
				$data['customer_id'] = $cid;
			}
		}
		if($guarantor_) {
			if($guarantor->insert('companies', $guarantor_))
			{
				$data['guarantor_id'] = $guarantor->insert_id();
			}
		}
		
		if($employee_) {
			if($employee->insert('qoute_employee', $employee_))
			{
				$data['employee_id']  = $employee->insert_id();
			}
		}
		
		if($join_lease) {
			if($jl->insert('companies', $join_lease)) {
				$data['join_lease_id'] = $jl->insert_id();
			}
		}
		
		if($join_guarantor) {
			if($jg->insert('companies', $join_guarantor)) {
				$data['join_guarantor_id'] = $jg->insert_id();
			}
		}
		//$data['loan_group_id']	= $loan_group_id;
		if($data) {
			if ($this->db->insert('quotes', $data)) {
				$quote_id = $this->db->insert_id();
				
				if($collateral_) {
					$collateral_['quote_id']=$quote_id;
					$this->db->insert('collateral', $collateral_);
					$this->site->updateReference('cl');
				}
				$this->site->updateReference('qu');
				
				
				if($saving){
					$saving['quotes_id']= $quote_id;
					$saving['customer_id'] = $cid;
					$this->db->insert('quotes', $saving);	
					$saving_id = $this->db->insert_id();
					
					$saving_item['quote_id']=$saving_id;
					$this->db->insert('quote_items', $saving_item);
				}

				foreach($documentsArray as $docs)
				{
					$this->db->insert('quote_photos',array('quote_id' => $quote_id, 'name' => $docs['name'], 'type' => $docs['type']));
				}

				#insert Service into table qoute_service
				if(is_array($services)){
				foreach ($services as $sv) {
					$sv['quote_id'] = $quote_id;
					$this->db->insert('quote_services', $sv);
					}
				}
				if(is_array($items)){
				foreach ($items as $item) {
					$item['quote_id'] = $quote_id;
					$this->db->insert('quote_items', $item);
				}
				}
				if($field_check) {
					$field_check['quote_id'] = $quote_id;
					$this->db->insert('field_check', $field_check);
				}
				return $quote_id;
			}
		}
		
        return false;
    }
    public function updateQuote($id, $data, $items = array())
    {
        if ($this->db->update('quotes', $data, array('id' => $id)) && $this->db->delete('quote_items', array('quote_id' => $id))) {
            foreach ($items as $item) {
                $item['quote_id'] = $id;
                $this->db->insert('quote_items', $item);
            }
            return true;
        }
        return false;
    }
	
	/*
	public function updateQuotationDetails($id, $data, $items, $employee = array(), $guarantor = array(), $documentsArray = array())
    {
		// $this->erp->print_arrays($data, $items, $employee, $guarantor, $documentsArray);
        if ($this->db->update('quotes', $data, array('id' => $id)) && $this->db->delete('quote_items', array('quote_id' => $id))) {
            foreach ($items as $item) {
                $item['quote_id'] = $id;
                $this->db->insert('quote_items', $item);
            }
			$quote           = $this->getQuoteByID($id);
			$emp_id          = $quote->employee_id;
			$guarantor_id    = $quote->guarantor_id;
			
            foreach($documentsArray as $docs)
            {
                if($this->db->update('quote_photos', array('name' => $docs['name']), array('quote_id' => $id))){
					$this->db->insert('quote_photos',array('quote_id' => $id, 'name' => $docs['name'], 'type' => $docs['type']));
				}
            }
			$this->db->update('qoute_employee', $employee, array('id' => $emp_id));
			$this->db->update('companies', $guarantor, array('id' => $guarantor_id));
			
			if($data['status'] == 'rejected'){
				$this->db->update('sales', array('sale_status' => $data['status']), array('id' => $sale->id));
			}
			if($data['status'] == 'approved'){
				
				$data['sale_status'] = 'approved';
				$data['payment_status'] = 'due';
				unset($data['status']);

				$data['total_cost'] = 0;
				$data['pos'] = 0;
				$data['surcharge'] = 0;
				$data['reference_no_tax'] = '';
				
				if($sale = $this->getSaleByQuoteID($id)){
					if($this->db->update('sales', $data, array('id' => $sale->id))){
						foreach ($items as $u_sale) {
							$this->db->update('sale_items', $u_sale, array('sale_id' => $sale->id));
							
						}
					}
				}else{
					$data['quote_id'] = $id;
					$issue_date = date('Y-m-d H:i:s');
					$data['approved_date'] = $issue_date;
					$data['reference_no'] = $this->site->getReference('so', $data['biller_id']);
					if($this->db->insert('sales', $data)){
						$sale_id = $this->db->insert_id();
						foreach ($items as $sale) {
							$sale['sale_id'] = $sale_id;
							$this->db->insert('sale_items', $sale);
						}
						$this->site->updateReference('so');
					}
				}
			}
			
            return true;
        }
        return false;
    }
	*/
	
	public function updateQuotationDetails($id, $data, $services = array(), $items, $employee = array(), $guarantor = array(), $documentsArray = array(), $customers = array(), $field_check = array(),$collaterals= array(), $group_loan= array(), $join_lease = array(), $join_guarantor = array(), $saving = array(), $saving_item = array())
    {	
		
		$quote           = $this->getQuoteByID($id);
		$emp_id          = $quote->employee_id;
		$guarantor_id    = $quote->guarantor_id;
		$join_lease_id = $quote->join_lease_id;
		$join_guarantor_id = $quote->join_guarantor_id;
		$customer_id 	 = $quote->customer_id;
		$employ_id       = $emp_id;
        $garant_id       = $guarantor_id;
		$loan_group_id 		 = $quote->loan_group_id;
		
		
		//$join_guarantor_id = $quote->join_guarantor_id;
		//$this->erp->print_arrays($id);
		
		if($documentsArray) {
			foreach($documentsArray as $docs)
			{
				$quote_service_field = $this->db->get_where('quote_photos', array('quote_id' => $id, 'type' => $docs['type']));
				if($quote_service_field->row()) {
					$this->db->update('quote_photos', array('name' => $docs['name']), array('quote_id' => $id, 'type' => $docs['type']));
				}else {
					$this->db->insert('quote_photos', array('quote_id' => $id, 'name' => $docs['name'], 'type' => $docs['type']));
				}
			}
		}
		
		
		if($employee) {
			$employee_field = $this->db->get_where('qoute_employee', array('id' => $emp_id));
			if($employee_field->row()) {
				$this->db->update('qoute_employee', $employee, array('id' => $emp_id));
			}else {
				$this->db->insert('qoute_employee', $employee);
				$employ_id = $this->db->insert_id();
			}
		}
		
		if($guarantor) {
			$guarantor_field = $this->db->get_where('companies', array('id' => $guarantor_id));
			if($guarantor_field->row()) {
				$this->db->update('companies', $guarantor, array('id' => $guarantor_id));
			}else {
				$this->db->insert('companies', $guarantor);
				$garant_id = $this->db->insert_id();
			}
		}
	
		if($collaterals) {
			foreach($collaterals as $collateral) {
				$col_id = $collateral["id"];
				unset($collateral["id"]);
				$coll = $this->db->get_where('collateral', array('id' => $col_id, 'quote_id' => $id));
				if($coll->row()) {
					
					$this->db->update('collateral', $collateral, array( 'id' => $col_id, 'quote_id' => $id));
				}else {
					$this->db->insert('collateral', $collateral);
				}
			}
		}
		/*if($collateral){
			$coll = $this->db->get_where('collateral', array('quote_id' => $id));
			if($coll->row()) {
				$this->db->update('collateral', $collateral, array('quote_id' => $id));
			}else {
				$this->db->insert('collateral', $collateral);
			}
		}
		if($collateral) {
			$coll = $this->db->get_where('collateral', array('id' => $cl_id));
			if($coll->row()) {
				$this->db->update('collateral', $collateral, array('id' => $cl_id));
			}else {
				$this->db->insert('collateral', $collateral);	
				$cl_id  = $this->db->insert_id();
			}
		}
		*/
	
		if($join_lease && $join_lease_id) {
			$join_lease_field = $this->db->get_where('companies', array('id' => $join_lease_id));
			if($join_lease_field->row()) {
				$this->db->update('companies', $join_lease, array('id' => $join_lease_id));
			}else {
				$this->db->insert('companies', $join_lease);
				$join_lease_id = $this->db->insert_id();
			}
			
		}
		
		if($join_guarantor && $join_guarantor_id) {
			$join_guarantor_field = $this->db->get_where('companies', array('id' => $join_guarantor_id));
			if($join_guarantor_field->row()) {
				$this->db->update('companies', $join_guarantor, array('id' => $join_guarantor_id));
			}else {
				$this->db->insert('companies', $join_guarantor);
				$join_guarantor_id = $this->db->insert_id();
			}
			
		}
		
		if($field_check) {
			$field_check_field = $this->db->get_where('field_check', array('quote_id' => $id));
			if($field_check_field->row()) {
				$this->db->update('field_check', $field_check, array('quote_id' => $id));
			}else {
				$field_check['quote_id'] = $id;
				$this->db->insert('field_check', $field_check);
			}
		}
		if($group_loan) {
			$group_loans_field = $this->db->get_where('loan_groups', array('id' => $loan_group_id));
			if ($group_loans_field->row()){
				$this->db->update('loan_groups', $group_loan, array('id' => $loan_group_id));
			}else {
				$this->db->insert('loan_groups', $group_loan);
				$loan_group_id = $this->db->insert_id();
			}
		}
		$customers['loan_group_id']	= $loan_group_id;
		if($customers) {
			$customer_field = $this->db->get_where('companies', array('id' => $customer_id));
			if($customer_field->row()) {
				$this->db->update('companies', $customers, array('id' => $customer_id));
			}else {
				$this->db->insert('companies', $customers);
				$customer_id = $this->db->insert_id();
			}
		}
		$data['employee_id'] = $employ_id;
		$data['guarantor_id'] = $garant_id;
        $data['customer_id'] = $customer_id;
		$data['join_lease_id'] = $join_lease_id;
		$data['join_guarantor_id'] = $join_guarantor_id;
		$data['loan_group_id']	= $loan_group_id;
		/*
		if($data['status'] == 'approved' || $data['status'] == 'approved_condition' || $data['status'] == 'rejected'){
			$data['approved_date'] = date('Y-m-d H:i:s');
		}
		*/
		if($data) {
			if ($this->db->update('quotes', $data, array('id' => $id))) {
				
				#insert Service into table qoute_service
				if(is_array($services)){
					if($this->db->delete('quote_services', array('quote_id' => $id))) {
						foreach ($services as $sv) {
							$sv['quote_id'] = $id;
							$this->db->insert('quote_services', $sv);
							}
					}
				}
				
				$this->db->delete('quote_items', array('quote_id' => $id));
				if(is_array($items)){
					foreach ($items as $item) {
						$item['quote_id'] = $id;
						$this->db->insert('quote_items', $item);
					}
				}
				
				$saving_ = $this->getQuoteSavingQuoteID($id);
				if($saving){
					if($saving_){
						$saving['quotes_id']= $id;
						$saving['customer_id'] = $customer_id;
						$this->db->update('quotes', $saving , array('quotes_id' => $id));						
						$this->db->update('quote_items', $saving_item , array('quote_id' => $saving_->id));
					}else {
						$saving['quotes_id']= $id;
						$saving['customer_id'] = $customer_id;
						$this->db->insert('quotes', $saving);
						$saving_id = $this->db->insert_id();
					
						$saving_item['quote_id']=$saving_id;
						$this->db->insert('quote_items', $saving_item);
					}

				}else{
					$this->db->delete('quotes', array('quotes_id' => $id));
					$this->db->delete('quote_items', array('quote_id' => $saving_->id));
				} 
				
				$quote           = $this->getQuoteByID($id);
				$emp_id          = $quote->employee_id;
				$guarantor_id    = $quote->guarantor_id;
				//$this->erp->print_arrays($data);
				/*
				if($data['status'] == 'rejected'){
					$this->db->update('sales', array('sale_status' => $data['status']), array('id' => $sale->id));
				}
				if($data['status'] == 'pending_po'){
					
					$data['sale_status'] = 'approved';
					$data['payment_status'] = 'due';
					unset($data['status']);
					
					$data['total_cost'] = 0;
					$data['pos'] = 0;
					$data['surcharge'] = 0;
					$data['reference_no_tax'] = '';
					
					if($sale = $this->getSaleByQuoteID($id)){
						if($this->db->update('sales', $data, array('id' => $sale->id))){
							foreach ($items as $u_sale) {
								$this->db->update('sale_items', $u_sale, array('sale_id' => $sale->id));
								
							}
						}
					}else{
						$data['quote_id'] = $id;
						$issue_date = date('Y-m-d H:i:s');
						$data['approved_date'] = $issue_date;
						$data['reference_no'] = $this->site->getReference('so', $data['biller_id']);
						$data['created_by'] = $this->session->userdata('user_id');
						if($this->db->insert('sales', $data)){
							$sale_id = $this->db->insert_id();
							foreach ($items as $sale) {
								$sale['sale_id'] = $sale_id;
								$this->db->insert('sale_items', $sale);
							}
							$this->site->updateReference('so');
						}
					}
				}
				*/
				return true;
			}
		}
			
        return false;
    }
	public function getHolidays(){
		$this->db->select('holiday_date');
		$q = $this->db->get('holidays');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;
	}
	public function getBranchByQuoteID($id){
		$this->db->select('users.commission_amount, quotes.created_by');
		$this->db->join('users','quotes.created_by=users.id','INNER');
		$q = $this->db->get_where('quotes', array('quotes.id' => $id),1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getApprovedApplicant($id = NULL, $data = array(), $items = array(), $quote_reject=array(), $agency=array(), $saving = array(), $saving_item = array())
	{
		//$this->erp->print_arrays($companies);
		$due_date = $data['due_date'];
		if($data['mfi']) {
			if($data['status'] == 'approved') {
				$quote['approved_date'] = $data['approved_date'];
				$quote['acceptation_date'] = date('Y-m-d H:i:s');
				$quote['quote_status'] = 'approved';
			} elseif($data['status'] == 'rejected') {
				$quote['approved_date'] = $data['approved_date'];;
				unset($data['due_date']);
			}else {
				unset($data['due_date']);
			}
		}else {
			if($data['status'] == 'pending_po') {
				$quote['acceptation_date'] = date('Y-m-d H:i:s');
			} else {
				$quote['approved_date'] = $data['approved_date'];;
			}
			$quote['status'] = $data['status'];
			unset($data['due_date']);
	
		}
		
		$quotesSaving = $this->getQuoteSavingQuoteID($id);
		$quote['updated_by'] = $this->session->userdata('user_id');
		if ($this->db->update('quotes', $quote, array('id' => $id))) {	
			if($data['status'] == 'rejected'){
				$this->db->update('quotes',array('quote_status'=>$data['status']),array('id'=>$id));

				$this->db->update('quotes',array('quote_status'=>$data['status']),array('id'=>$quotesSaving->id));
				
				if($contract = $this->getSaleByQuoteID($id)){
					$data['status']='loans';
					$this->db->update('sales', array('sale_status' => $data['status']), array('id' => $contract->id));
				}
			}	
			if($quote_reject){
				for($i=0;$i<sizeof($quote_reject['reject_id']);$i++){
					$quote_rej = array('quote_id'=>$quote_reject['quote_id'],'reject_id'=>$quote_reject['reject_id'][$i]);
					$this->db->insert('quote_reject',$quote_rej);
				}
			}	
			if($agency){
					$getBranch = $this->getBranchByQuoteID($id);
					$this->db->update('users', array('commission_amount' => ($getBranch->commission_amount + $agency['commission_amount'])), array('id' => $getBranch->created_by));	
			}		
			
			if($data['status'] == 'pending_po' || ($data['status'] == 'approved' && $data['mfi'])){
				$date_now = $data['approved_date'];
				if($data['mfi']){
					$data['status']='loans';
					$data['sale_status'] = 'approved';
					$data['contract_date'] = $date_now;
				}else {
					$data['status']='loans';
					$data['sale_status'] = 'pending_po';
				}
				$data['payment_status'] = 'due';
				
				$data['date'] = $date_now;
				
				unset($data['status']);
				
				$data['total_cost'] = 0;
				$data['pos'] = 0;
				$data['surcharge'] = 0;
				$data['reference_no_tax'] = '';
				
				if($sale = $this->getSaleByQuoteID($id)){
						$data['status']='loans';
					if($this->db->update('sales', $data, array('id' => $sale->id))){
						foreach ($items as $u_sale) {
							$this->db->update('sale_items', $u_sale, array('sale_id' => $sale->id));
						}
					}
				}else{
					$data['quote_id'] = $id;
					$data['status']='loans';
					$data['approved_date'] = $date_now;
					$sale_ref = $this->site->getReference('so');
					$data['reference_no'] = $sale_ref;
					$data['updated_by'] = $this->session->userdata('user_id');
					if($this->db->insert('sales', $data)){
						$sale_id = $this->db->insert_id();
						$default_currency = $this->get_setting();
						$quote_services = $this->getServicesByQuoteID($id);

						foreach ($items as $sale) {
							$sale['sale_id'] = $sale_id;
							$this->db->insert('sale_items', $sale);
						}

						if($saving){
							$saving['sales_id'] = $sale_id;
							$saving['reference_no'] = $sale_ref;
							$saving['sale_status'] = 'approved';
							$this->db->insert('sales', $saving);
							$save_id = $this->db->insert_id();

							$saving_item['sale_id'] = $save_id;
							$this->db->insert('sale_items', $saving_item);

							$this->db->update('quotes',array('quote_status'=>'approved'),array('id'=>$quotesSaving->id));
						}
						
						$total = $this->erp->convertCurrency($sale['currency_code'], $default_currency->default_currency, $data['total']); /////convertCurrencyBack
						
						$saving_rate = $saving['saving_rate'] ? $saving['saving_rate'] : 0;
						$saving_amt = $this->erp->convertCurrency($sale['currency_code'], $default_currency->default_currency, $saving['saving_amount']);
						$saving_amount = $saving_amt ? $saving_amt : 0;
						$saving_interest_rate = $saving['saving_interest_rate'] ? $saving['saving_interest_rate'] : 0;
						$saving_type = $saving['saving_type'] ? $saving['saving_type'] : 0;

						if($quote_services) {
							foreach($quote_services as $qs) {
								if ($qs->type=='Percentage'){
									$service_amount = $qs->amount; // * $total;
								}else{
									$service_amount =  $qs->amount ;
								}
								$sv_amount =  $service_amount ; //// 'type'=>$qs->type,
								$this->db->insert('sale_services', array('sale_id' => $sale_id, 'services_id' => $qs->services_id, 'amount' => $sv_amount, 'service_paid'=>$qs->service_paid, 'type'=>$qs->type, 'charge_by'=>$qs->charge_by, 'tax_rate'=>$qs->tax_rate ));
							}
						}
						
						$holiday = $this->getHolidays();
						if($data['mfi']) {
							$loan = $this->erp->getPaymentSchedule($sale_id, $total, $data['rate_type'], $data['interest_rate'], $data['term'], $data['frequency'], $due_date, $date_now, $sale['currency_code'], $data['principle_frequency'], $saving_amount, $saving_interest_rate, $saving_type );
							//$this->erp->print_arrays($loan);
							if($loan) {
								$this->addLoan($loan);
							}
						}
						$this->site->updateReference('so');
						$this->db->update('collateral',array('sale_id'=>$sale_id), array('quote_id' => $id));
					}
				}
			}
			return true;
		}
        return false;
	}

	public function addLoan($data = array()) {
		if($data) {
			foreach($data as $dt) { 
				$this->db->insert('loans', $dt);
			}
		}
	}
	
	public function getServicesByQuoteID($id = NULL)
	{
		$q = $this->db->get_where('quote_services' , array('quote_id' => $id));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
	}
	public function getQuoteItemByQuoteID($id = NULL)
	{
		$q = $this->db->get_where('quote_items' , array('quote_id' => $id));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
	}
	
	public function getQuoteSavingQuoteID($id = NULL)
	{
		$q = $this->db->get_where('quotes' , array('quotes_id' => $id));
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getSaveItemBySaveID($id = NULL)
	{
		$q = $this->db->get_where('quote_items' , array('quote_id' => $id));
		if ($q->num_rows() > 0) {
            return $q->row();
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
	
	public function allQuoteRef()
	{	
		$q = $this->db->query("SELECT
												id,
												erp_quotes.reference_no
											FROM
												erp_quotes
											WHERE
												ID NOT IN (
													SELECT
														quote_id
													FROM
														erp_field_check
												)
											AND `status` = 'quotation'");
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
	}
	
	public function insertFieldCheck($data = array())
	{
		if($this->db->insert('field_check', $data)) {
			return true;
		}
		return false;
	}
	
	public function getSaleByQuoteID($quote_id){
		$q = $this->db->get_where('sales', array('quote_id' => $quote_id), 1);
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	
    public function deleteQuote($id)
    {
        if ($this->db->delete('quote_items', array('quote_id' => $id)) && $this->db->delete('quotes', array('id' => $id))) {
            return true;
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
	
	public function getProductByQuoteID($quote_id){
		$this->db->select('quotes.total,subcategories.name as sname,products.name as pname,quote_items.*, products.category_id, products.subcategory_id,loan_groups.name as group_name, loan_groups.id as grlid');
		$this->db->join('products', 'products.id = quote_items.product_id', 'left');
		$this->db->join('subcategories', 'subcategories.id = products.subcategory_id', 'left');
		$this->db->join('quotes','quotes.id = quote_items.quote_id', 'left');
		$this->db->join('loan_groups','loan_groups.id = quotes.loan_group_id', 'left');
		$q = $this->db->get_where('quote_items', array('quote_id' => $quote_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
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

    public function getProductComboItems($pid, $warehouse_id)
    {
        $this->db->select('products.id as id, combo_items.item_code as code, combo_items.quantity as qty, products.name as name, products.type as type, warehouses_products.quantity as quantity')
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

    public function getProductOptions($product_id, $warehouse_id)
    {
        $this->db->select('product_variants.id as id, product_variants.name as name, product_variants.price as price, product_variants.quantity as total_quantity, warehouses_products_variants.quantity as quantity')
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left')
            //->join('warehouses', 'warehouses.id=product_variants.warehouse_id', 'left')
            ->where('product_variants.product_id', $product_id)
            ->where('warehouses_products_variants.warehouse_id', $warehouse_id)
            ->where('warehouses_products_variants.quantity >', 0)
            ->group_by('product_variants.id');
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getPurchasedItems($product_id, $warehouse_id, $option_id = NULL)
    {
        $orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->select('id, quantity, quantity_balance, net_unit_cost, item_tax');
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
	
	public function addEmployee($data){
		if($data){
			$this->db->insert('companies' ,$data);
			return TRUE;
		}
		return FALSE;
	}
	
	/* Employee Quote */
	public function getEmployeeQuoteByQuoteID($quote_id){
		$q = $this->db->select('qoute_employee.*')
				->from('qoute_employee')
				->join('quotes', 'quotes.employee_id = qoute_employee.id', 'inner')
				->where('quotes.id', $quote_id)
				->get();
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	
	public function getGuarantorByQuoteID($quote_id){
		$this->db->select('companies.*, identify_types.name as ident_name,quotes.installment_date')
				->join('identify_types','identify_types.id=companies.identify','left')
				->join('quotes', 'quotes.guarantor_id = companies.id', 'inner')
				->where('quotes.id', $quote_id);
		$q = $this->db->get('companies');
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	
	/*public function getGuarantorByQuoteID($quote_id){
		$q = $this->db->select('companies.*')
				->from('companies')
				->join('quotes', 'quotes.guarantor_id = companies.id', 'inner')
				->where('quotes.id', $quote_id)
				->get();
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}*/
	
	public function getQuoteIdByCusID($cus_id){
		$q = $this->db->select('quotes.*')
				->from('quotes')
				->where('quotes.customer_id', $cus_id)
				->get();
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	public function getDocumentsByQuoteID($quote_id){
		$q = $this->db->get_where('quote_photos', array('quote_id' => $quote_id));
		
		foreach($q->result() as $row){
			$data[] = $row;
		}
		return isset($data) ?$data  : ('');
		
		return false;
	}

	public function addComment($quote_id, $data){
		if($quote_id){
			$this->db->insert('quote_comments', $data);
			return true;
		}
		return false;
	}
	
	public function getCommentsByQuoteID($quote_id){
		$this->db->select('quote_comments.*, CONCAT(first_name, " ", last_name) AS user_name');
		$this->db->order_by('date', 'DESC');
		$this->db->join('users', 'users.id = quote_comments.comment_by', 'left');
		$q = $this->db->get_where('quote_comments', array('quote_id' => $quote_id));
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getCustomerByGovID($id = NULL) {
		$this->db->select('companies.id, companies.family_name, companies.name');
		$q = $this->db->get_where('companies', array('gov_id' => $id), 1);
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	/*==========Show Group Loans======================================*/
	public function getGroupLoanID($id = NULL) {
		$this->db->select('loan_groups.id,loan_groups.name');
		$q = $this->db->get_where('loan_groups', array('name' => $id), 1);
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getCustomerInfoByGovId($id=NULL){
			$this->db->select('
			companies.id as id,
			companies.date_of_birth as date_of_birth,
			companies.house_no as house_no,
			companies.street as street,
			companies.family_name as family_name,
			companies.name as name,
			companies.family_name_other as family_name_other,
			companies.name_other as name_other,
			companies.phone1 as phone1,
			country.description as country_kh,
			country.name as country_en,
			province.description as province_kh,
			province.name as province_en,
			district.description as district_kh,
			district.name as district_en,
			commune.description as communce_kh,
			commune.name as communce_en,
			village.description as village_kh,
			village.name as village_en,
			');
			$this->db->from('companies');
			$this->db->join('addresses AS country', 'country.code = companies.country');
			$this->db->join('addresses AS province', 'province.code = companies.state');
			$this->db->join('addresses AS district', 'district.code = companies.district');
			$this->db->join('addresses AS commune', 'commune.code = companies.sangkat');
			$this->db->join('addresses AS village', 'village.code = companies.village');
			
			$this->db->where('companies.id',$id);
		$q = $this->db->get();
		if ($q) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getApplicantInfoByGovId($id){
		$this->db->select('
		users.username as creator,
		companies.phone1 as gphone,
		companies.name as gname,
		companies.family_name as gfname,
		quotes.id as quid,
		quotes.reference_no as reference_no,
		quotes.date as date,
		quotes.status as status,
		quote_items.product_name as product_name');
		$this->db->from('quotes');
		$this->db->join('quote_items', 'quote_items.quote_id = quotes.id');
		$this->db->join('companies', 'companies.id = quotes.guarantor_id');
		$this->db->join('users', 'users.id = quotes.created_by');
		$this->db->where('customer_id',$id);		
			$q = $this->db->get();
				if ($q->num_rows() > 0) {
					foreach (($q->result()) as $row) {
						$data[] = $row;
					}
					return $data;
				}
				return FALSE;
	
	}
	
	public function getTotalServicesAmount($id = NULL) {
		$q = $this->db->select('SUM(amount) as amt')
					  ->get_where('quote_services', array('quote_id' => $id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return FALSE;
	}
	
	public function getCollateralType(){
		
			$q = $this->db->get('collateral_types');
				if ($q->num_rows() > 0) {
					foreach (($q->result()) as $row) {
						$data[] = $row;
					}
					return $data;
				}
				return FALSE;
	
	}
	public function getIdentifyType(){
		
			$q = $this->db->get('identify_types');
				if ($q->num_rows() > 0) {
					foreach (($q->result()) as $row) {
						$data[] = $row;
					}
					return $data;
				}
				return FALSE;
	
	}
	public function getIdentifyTypeName($companies_id){
		$this->db->select('identify_types.name');
		$this->db->from('companies');
		$this->db->join('identify_types', 'companies.identify = identify_types.id');
		$q = $this->db->get();
		if ($q) {
            return $q->row();
        }
        return FALSE;
	}
	public function insert_collecteral($data = array(),$quote_id) {
		$data['quote_id']=$quote_id;
		$i=$this->db->insert('collateral', $data);
		if($i){
			$u=$this->site->updateReference('cl');
		}				
	}
	
	
	public function getCollateralQuoteID($quote_id){
		$q = $this->db->get_where('collateral', array('quote_id' => $quote_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function get_CollateralQuoteID($quote_id){
		$q = $this->db->get_where('collateral', array('quote_id' => $quote_id));
        if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;
	}

	public function getCollateralQuoteID_land($quote_id){
		$this->db->where('quote_id', $quote_id);
		$this->db->where('cl_type', '1');
		$q = $this->db->get('collateral');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getCollateralQuoteID_home($quote_id){
		$q = $this->db->where('quote_id', $quote_id);
		$q = $this->db->where('cl_type', '2');
		$q = $this->db->get('collateral');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getCollateralQuoteID_vehicles($quote_id){
		$q = $this->db->where('quote_id', $quote_id);
		$q = $this->db->where('cl_type', '3');
		$q = $this->db->get('collateral');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getGovID(){
		$this->db->select('id,gov_id');
		$q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				
					$data[] = $row;				
			}
			return $data;
		}
        return FALSE;		
	}
	
	public function getCustomerIDName(){
		$this->db->select('id, name');
		$this->db->where('companies.group_id=3');
		$q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;		
	}
	public function getUsers(){
        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;
	}
	
	public function getOwnerUsers(){
		$this->db->where_in('users.group_id', array('1','2'));
		$this->db->order_by('users.id','DESC');
        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;
	}
	public function getUser($ids){
		$q = $this->db->get_where('users', array('id' => $ids), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getco($branch_id){
		$this->db->select('id,first_name,last_name');
		$this->db->where('active', 1);
		$this->db->where(array('branch_id' => $branch_id));
		$q = $this->db->get('users');
        if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
					$data[] = $row;				
			}
			return $data;
		}
        return FALSE;		
	}
	public function get_reject_reason(){
		$this->db->select('id,code,description,status');
		$q = $this->db->get('reject_reason');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getQoutePhoto($quote_id){
		$q = $this->db->get_where('quote_photos', array('quote_id' => $quote_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}	
	public function get_QoutePhoto($id=null){
		if($quote_id){
		$this->db->select('quote_photos.name');
		$this->db->where('quotes.id',$quote_id);
		$this->db->from('quotes');
		$this->db->join('quote_photos','quote_photos.quote_id=quotes.id');	
		$q = $this->db->get();
			if($q->num_rows()>0){
				return $q->row();
			}
		}else{
			return false;
		}		
	}
	public function getCategory() {
		$q = $this->db->get('categories');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function addGroupLoan($data)
    {
        if ($this->db->insert('loan_groups', $data)) {
            return true;
        }
        return false;
    }
	public function getServices($ids) {
		$this->db->where_in('id', $ids);
		$q = $this->db->get('services');
        if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
					$data[] = $row;				
			}
			return $data;
		}
        return FALSE;	
	}
	
	public function getQuoteServices($id) {
		$this->db->select('quote_services.*,services.description ');
		$this->db->join('services','services.id = quote_services.services_id','left');
		$q = $this->db->get_where('quote_services', array('quote_id' => $id));
        if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				
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
	
	public function getNumOfApp($group_id = NULL){
		$this->db->select('COALESCE(COUNT(id), 0) as app_num');
		$q = $this->db->get_where('quotes', array('loan_group_id' => $group_id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	public function getSettingCurrncies(){
		$this->db->select('currencies.rate, currencies.code, currencies.name');
		$this->db->from('settings');
		$this->db->join('currencies', 'settings.default_currency = currencies.code');		
		$q = $this->db->get();       
		 if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getBranchById($bid=NULL) {
		$q = $this->db->get_where('companies', array('id' => $bid),1);
		 if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getTaxeRateByID($id){
		$this->db->where("id",$id);
		$q = $this->db->query("SELECT * FROM erp_tax_rates ");
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	
	/*public function getCollateralTypes($id=NULL) {
		if ($quote_id){
		$this->db->select('collateraltypes.type');
		$this->db->where('quotes.id', $quote_id );
		$this->db->from('quotes');
		$this->db->join('collateral','collateral.quote_id = quotes.id');	
		$this->db->join('collateral_types','collateral_types.id = collateral.cl_type','left');
		$q = $this->db->get();
			if($q->num_rows() > 0) {
				return $q->row();
			}
		}else{
			return FALSE;
		}
	}
	public function collateraltype($quote_id){
		$this->db->select('collateral_types.type');
		$this->db->where('quote_id', $quote_id);
		$this->db-from('collateral');
		$this->db->join('collateral_types','collateral_types.id = collateral.cl_type');
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function get_CollateralType($quote_id){
		$q = $this->db->get_where('collateral', array('quote_id' => $quote_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}*/
}

	


	