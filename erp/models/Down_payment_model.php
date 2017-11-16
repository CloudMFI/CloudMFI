<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Down_payment_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getSaleByID($id=NULL)
    {
        $q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
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
	
	public function ImportCustomers($data = array()){
		if(is_array($data)){
			foreach ($data as $d) {
				//$this->erp->print_arrays($this->site->getIdentifyID($d['identify']));
				$identify = $this->site->getIdentifyID($d['identify']);
				if($identify) {
					$d['identify'] = $identify->id;
					$this->db->insert('companies', $d);
				}else {
					$this->db->insert('identify_types', array('name' => $d['identify']));
					$d['identify'] = $this->db->insert_id();
					$this->db->insert('companies', $d);
				}	
			}
			return true;
		}
		return false;
	}

	public function ImportContracts($data = array()){
		if(is_array($data)){
			foreach ($data as $d) {
				$quote = $d;
				$quote['acceptation_date'] = $quote['date'];
				$quote['status'] = 'activated';
				$quote['installment_date'] = $quote['due_date'];
				$quote['rate_text'] = ($quote['interest_rate'] * 100).'%';
				$quote_item = $quote['item'];
				unset($quote['item']);
				unset($quote['contract_date']);
				unset($quote['register_date']);
				unset($quote['sale_status']);
				unset($quote['paid']);
				unset($quote['due_date']);
				$this->db->insert('quotes', $quote);
				$quote_id = $this->db->insert_id();
				$quote_item['quote_id'] = $quote_id;
				if($quote_item && $quote_id) {
					$this->db->insert('quote_items', $quote_item);
					$d['quote_id'] = $quote_id;
				}				
				$item = $d['item'];
				unset($d['item']);
				$d['opening_ar'] = 1;
				$this->db->insert('sales', $d);
				$sale_id = $this->db->insert_id();
				if($item && $sale_id) {
					$item['sale_id'] = $sale_id;
					$this->db->insert('sale_items', $item);
				}
			}
			return true;
		}
		return false;
	}
	
	public function getSaleServices($id=NULL)
	{
		$this->db->select($this->db->dbprefix('services').".id,".$this->db->dbprefix('services').".code, ".$this->db->dbprefix('services').".description, ".$this->db->dbprefix('services').".description_other, ".$this->db->dbprefix('sale_services').".amount")
		->join($this->db->dbprefix('services'), $this->db->dbprefix('services').'.id = '.$this->db->dbprefix('sale_services').'.services_id', 'right')
		->from('sale_services')
		->where('sale_services.sale_id', $id );
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
		return FALSE;
	}
	
	public function getQuoteByID($id)
    {
        $q = $this->db->get_where('quotes', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function updateQuotationDetails($id, $data, $items, $employee = array(), $guarantor = array(), $documentsArray = array(), $customers = array(), $field_check = array())
    {
		
		$quote           = $this->getQuoteByID($id);
		$emp_id          = $quote->employee_id;
		$guarantor_id    = $quote->guarantor_id;
		$customer_id 	 = $quote->customer_id;
		$employ_id       = $emp_id;
        $garant_id       = $guarantor_id;

        foreach($documentsArray as $docs)
        {
            if(!$this->db->update('quote_photos', array('name' => $docs['name']), array('quote_id' => $id))){
				$this->db->insert('quote_photos',array('quote_id' => $id, 'name' => $docs['name'], 'type' => $docs['type']));
			}
        }

		if(!$this->db->update('qoute_employee', $employee, array('id' => $emp_id))) {
            $this->db->insert('qoute_employee', $employee);
			$employ_id = $this->db->insert_id();
		}

		if(!$this->db->update('companies', $guarantor, array('id' => $guarantor_id))) {
            $this->db->insert('companies', $guarantor);
			$garant_id = $this->db->insert_id();
		}
        if(!$this->db->update('companies', $customers, array('id' => $customer_id))) {
            $this->db->insert('companies', $customers);
        }
		if(!$this->db->update('field_check', $field_check, array('quote_id' => $id))) {
			$this->db->insert('field_check', $field_check);
		}
		
		$data['employee_id'] = $employ_id;
		$data['guarantor_id'] = $garant_id;
        $data['customer_id'] = $customer_id;
		/*
		if($data['status'] == 'approved' || $data['status'] == 'approved_condition' || $data['status'] == 'rejected'){
			$data['approved_date'] = date('Y-m-d H:i:s');
		}
		*/
		if ($this->db->update('quotes', $data, array('id' => $id))) {
			$this->db->delete('quote_items', array('quote_id' => $id));
            foreach ($items as $item) {
                $item['quote_id'] = $id;
                $this->db->insert('quote_items', $item);
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
			
        return false;
    }
	
	public function getAllCustomerCompanies()
    {
        $q = $this->db->get_where('companies', array('group_name' => 'customer'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function addPayment($data = array())
    {
        if ($this->db->insert('payments', $data)) {
            //if ($this->site->getReference('sp', $data['biller_id']) == $data['reference_no']) {
                $this->site->updateReference('sp');
            //}
            $this->site->syncSalePayments($data['sale_id']);
            return true;
        }
        return false;
    }
	
	public function addLoan($data = array()) {
		$help = false;
		if($data) {
			foreach($data as $dt) {
				if($this->db->insert('loans', $dt)) {
					$help = true;
				}
			}
		}
		return $help;
	}
	
	public function updateDownPayment($data = array(), $id = NULL)
	{
		if ($this->db->update('sales', $data, array('id' => $id))) {
			$quote_field = $this->getSaleByID($id);
			$this->db->update('quotes', array('status' => $data['sale_status'], 'acceptation_date' => date('Y-m-d H:i:s')), array('id' => $quote_field->quote_id));
            return true;
        }
        return FALSE;
	}
	
	public function getInvoiceByID($id)
    {
        $q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getCurrentBalance($sale_id)
	{
		$this->db->select('id, amount, extra_paid')
				 ->order_by('id', 'asc');
		$q = $this->db->get_where('payments', array('sale_id' => $sale_id));
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}
	
	public function getInvoicePayments($sale_id)
    {
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getPaymentByID($id)
    {
        $q = $this->db->get_where('payments', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getAllInvoiceItems($sale_id)
    {
        $this->db->select('sale_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, products.unit, products.details as details, product_variants.name as variant, products.unit, products.promotion, categories.name AS category_name')
            ->join('products', 'products.id=sale_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=sale_items.tax_rate_id', 'left')
			->join('categories', 'categories.id = products.category_id', 'left')
            ->group_by('sale_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function updatePayment($id, $data = array())
    {
        if ($this->db->update('payments', $data, array('id' => $id))) {
            $this->site->syncSalePayments($data['sale_id']);
            return true;
        }
        return false;
    }
	
	public function deletePayment($id)
    {
        $opay = $this->getPaymentByID($id);
        if ($this->db->delete('payments', array('id' => $id))) {
            $this->site->syncSalePayments($opay->sale_id);
            return true;
        }
        return FALSE;
    }
	
	function getExchange_rate($code = "KHM")
    {	
		$this->db->where(array('code' => $code));
        $q = $this->db->get('currencies');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getApplicants($id){
		$this->db->select('companies.*, districts.name as dname, provinces.name as pname, villages.name as vname, communces.name as cname, countries.name_other as coname, customer_groups.name as gr_name')
				 ->join('companies', 'companies.id = quotes.biller_id', 'left')
				 ->join('districts', 'districts.id = companies.district', 'left')
				 ->join('provinces', 'provinces.id = companies.state', 'left')
				 ->join('villages', 'villages.id = companies.village', 'left')
				 ->join('communces', 'communces.id = companies.sangkat', 'left')
				 ->join('countries', 'countries.id = companies.country', 'left')
				 ->join('customer_groups', 'customer_groups.id = companies.customer_group_id', 'left')
				 ->where('quotes.id', $id);
        $q = $this->db->get('quotes');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getProductByQuoteID($id){
		$this->db->select('quote_items.*, categories.name as caname, subcategories.name as subname, products.name as pname, variants.name as vname')
				 ->join('products', 'products.id = quote_items.product_id', 'left')
				 ->join('categories', 'products.category_id = categories.id', 'left')
				 ->join('subcategories', 'categories.id = subcategories.category_id', 'left')
				 ->join('variants', 'variants.id = quote_items.color', 'left');
		$q = $this->db->get_where('quote_items', array('quote_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getSaleItemBySaleID($id = NULL)
    {
        $q = $this->db->get_where('sale_items' , array('sale_id' => $id));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function setRegistration($id = NULL, $data = array(), $items = array()) {
		if($data && $items) {
			if($this->db->update('sale_items', $data, array('sale_id' => $id))) {
				$cost = $this->site->costing($items);
				foreach($items as $g){
					$totalCostProducts = $this->getTotalCostProducts($g['product_id'], $g['quantity']);
					$data['total_cost'] += $totalCostProducts->total_cost;
					$Proqty = $this->getProductQty($g['product_id']);
					$WareQty = $this->getWarehouseQty($g['product_id'], $g['warehouse_id']);
					$qty = $Proqty->quantity - $g['quantity'];
					$ware = $WareQty->quantity - $g['quantity'];
					$this->db->update('warehouses_products', array('quantity' => $ware), array('product_id' => $g['product_id'], 'warehouse_id' => $g['warehouse_id']));
					$this->db->update('products', array('quantity' => $qty), array('id' => $g['product_id']));
				}
				$this->db->update('sales', array('sale_status' => 'registered'), array('id' => $id));
				return true;
			}
		}
		return false;
	}

	public function getTotalCostProducts($product_id, $quantity){
		$this->db->select("SUM(cost* CASE WHEN $quantity <> 0 THEN $quantity ELSE 0 END ) AS total_cost ");
		$q = $this->db->get_where('products', array('id' => $product_id));
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getProductQty($id){
		$this->db->select('quantity, price');
        $q = $this->db->get_where('products', array('id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getWarehouseQty($id, $warehouse){
		$this->db->select('quantity');
        $q = $this->db->get_where('warehouses_products', array('product_id' => $id, 'warehouse_id'=>$warehouse));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function ImportContractDetail($quote_services = array(),$sale_services = array(), $services_payments = array(), $disbure_payments){
		if($disbure_payments){
			$this->db->insert('payments',$disbure_payments);
			$payment_id = $this->db->insert_id();
		}		
		if($quote_services){
			foreach($quote_services as $quote_service){
				$this->db->insert('quote_services',$quote_service);
			}
			foreach($sale_services as $sale_service){
				$this->db->insert('sale_services',$sale_service);
			}
			if($services_payments){
				foreach($services_payments as $services_payment){
					$services_payment['payment_id'] = $payment_id;
					$this->db->insert('service_payments',$services_payment);
				}
			}
			return true;
		}
		else{
			return false;
		}		
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

	
	public function ImportSchedule($data, $payment, $services = array()){		
		if($data){
			if($this->db->insert('loans', $data)) {
				$loan_id = $this->db->insert_id();				
				if($data['paid_amount'] > 0){
					//if($payment){
						$payment['loan_id'] = $loan_id;
						if($this->db->insert('payments', $payment)) {
							$payment_id = $this->db->insert_id();
							if($services){
								foreach($services as $service){
									$service['payment_id'] = $payment_id;
									$service['loan_id'] = $loan_id;
									$this->db->insert('service_payments', $service);
								}
							}
						}
					//}
				}
			}			
			return true;
		}else{
			return false;
		}		
	}
	
	/*==============CO Transfer===========================*/
	public function getQuoteCO(){
		$q = $this->db->get('users');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}
	public function getLoansByCO(){
		$this->db->select('users.id, users.branch_id,quotes.id as q_id,quotes.by_co as created_by,users.first_name,users.last_name');
		$this->db->join('users','users.id = quotes.by_co','INNER');
		$this->db->group_by('quotes.by_co');
		$q = $this->db->get('quotes');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;	
	}
	public function getLoansByQuoteID($id){
		$this->db->select('users.id, users.branch_id,quotes.id as q_id,quotes.by_co as created_by,users.first_name,users.last_name');
		$this->db->join('users','users.id = quotes.by_co','inner');
		$this->db->where('quotes.id', $id);
		$q = $this->db->get('quotes');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;	
	}
	public function updateDetails($from_co,$from_bid,$to_co,$to_bid){
		$query = "UPDATE erp_quotes
					INNER JOIN erp_users ON erp_quotes.by_co = erp_users.id
					LEFT JOIN erp_sales ON erp_quotes.id = erp_sales.quote_id
					SET erp_quotes.by_co = $to_co ,
						erp_quotes.branch_id = $to_bid,
						erp_sales.by_co = $to_co,
						erp_sales.branch_id = $to_bid
				  WHERE
					erp_quotes.by_co = $from_co 
					OR erp_sales.by_co = $from_co
					AND erp_users.id = $from_co 
					AND erp_users.branch_id = $from_bid";
		$result = $this->db->query($query);
		return $result;
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
	
	public function updateLoanTransferCo($from_co,$loan_id,$from_bid,$to_co,$to_bid){
		
		$query = "UPDATE erp_quotes
					INNER JOIN erp_users ON erp_quotes.by_co = erp_users.id
					LEFT JOIN erp_sales ON erp_quotes.id = erp_sales.quote_id
					SET erp_quotes.by_co = $to_co ,
						erp_quotes.branch_id = $to_bid,
						erp_sales.by_co = $to_co,
						erp_sales.branch_id = $to_bid
					WHERE
						(erp_quotes.id = $loan_id 
					AND erp_sales.quote_id = $loan_id)";
		$result = $this->db->query($query);
		return $result;
	}
	public function updateQuoteTransferCo($from_co,$loan_id,$from_bid,$to_co,$to_bid){
		$query = "UPDATE erp_quotes
					INNER JOIN erp_users ON erp_quotes.by_co = erp_users.id
					LEFT JOIN erp_sales ON erp_quotes.id = erp_sales.quote_id
					SET erp_quotes.by_co = $to_co ,
						erp_quotes.branch_id = $to_bid,
						erp_sales.by_co = $to_co,
						erp_sales.branch_id = $to_bid
				  WHERE
					(erp_quotes.id = $loan_id 
					OR erp_sales.quote_id = $loan_id)";
		$result = $this->db->query($query);
		return $result;
	}
	/*===============End CO Transfer==========================*/
	
}
