<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Db_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getLatestSales()
    {
        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $this->db->order_by('id', 'desc');
        $q = $this->db->get("sales", 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
    public function getLastestQuotes()
    {
      // CO
      /*
        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        */
        $this->db->order_by('id', 'desc');
        $q = $this->db->get("quotes", 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getLatestPurchases()
    {
        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $this->db->order_by('id', 'desc');
        $q = $this->db->get("purchases", 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLatestTransfers()
    {
        if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $this->db->order_by('id', 'desc');
        $q = $this->db->get("transfers", 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLatestCustomers()
    {
        $this->db->order_by('id', 'desc');
        $q = $this->db->get_where("companies", array('group_name' => 'customer'), 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getLatestSuppliers()
    {
        $this->db->order_by('id', 'desc');
        $q = $this->db->get_where("companies", array('group_name' => 'supplier'), 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	public function getLatestDealers()
    {
        $this->db->order_by('id', 'desc');
        $q = $this->db->get_where("companies", array('group_name' => 'supplier'), 5);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getChartData()
    {
        $myQuery = "SELECT S.month,
        COALESCE(S.sales, 0) as sales,
        COALESCE( P.purchases, 0 ) as purchases,
        COALESCE(S.tax1, 0) as tax1,
        COALESCE(S.tax2, 0) as tax2,
        COALESCE( P.ptax, 0 ) as ptax
        FROM (  SELECT  date_format(date, '%Y-%m') Month,
                SUM(total) Sales,
                SUM(product_tax) tax1,
                SUM(order_tax) tax2
                FROM " . $this->db->dbprefix('sales') . "
                WHERE date >= date_sub( now( ) , INTERVAL 12 MONTH )
                GROUP BY date_format(date, '%Y-%m')) S
            LEFT JOIN ( SELECT  date_format(date, '%Y-%m') Month,
                        SUM(product_tax) ptax,
                        SUM(order_tax) otax,
                        SUM(total) purchases
                        FROM " . $this->db->dbprefix('purchases') . "
                        GROUP BY date_format(date, '%Y-%m')) P
            ON S.Month = P.Month
            GROUP BY S.Month
            ORDER BY S.Month";
        $q = $this->db->query($myQuery);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStockValue()
    {
        $q = $this->db->query("SELECT SUM(qty*price) as stock_by_price, SUM(qty*cost) as stock_by_cost
        FROM (
            Select sum(COALESCE(" . $this->db->dbprefix('warehouses_products') . ".quantity, 0)) as qty, price, cost
            FROM " . $this->db->dbprefix('products') . "
            JOIN " . $this->db->dbprefix('warehouses_products') . " ON " . $this->db->dbprefix('warehouses_products') . ".product_id=" . $this->db->dbprefix('products') . ".id
            GROUP BY " . $this->db->dbprefix('warehouses_products') . ".id ) a");
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getBestSeller($start_date = NULL, $end_date = NULL)
    {
        if (!$start_date) {
            $start_date = date('Y-m-d', strtotime('first day of this month')) . ' 00:00:00';
        }
        if (!$end_date) {
            $end_date = date('Y-m-d', strtotime('last day of this month')) . ' 23:59:59';
        }
        $sp = "(SELECT si.product_id, SUM( si.quantity ) soldQty, s.date as sdate from " . $this->db->dbprefix('sales') . " s JOIN " . $this->db->dbprefix('sale_items') . " si on s.id = si.sale_id where s.date >= '{$start_date}' and s.date < '{$end_date}' group by si.product_id ) PSales";
        $this->db
            ->select("CONCAT(" . $this->db->dbprefix('products') . ".name, ' (', " . $this->db->dbprefix('products') . ".code, ')') as name, COALESCE( PSales.soldQty, 0 ) as SoldQty", FALSE)
            ->from('products', FALSE)
            ->join($sp, 'products.id = PSales.product_id', 'left')
            ->order_by('PSales.soldQty desc')
            ->limit(10);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

	public function getLastFiveQuoat($view_draft = null){
		$settings = $this->getSettingCurrncy();
		$this->db->select($this->db->dbprefix('quotes').".id,".
					$this->db->dbprefix('quotes').".reference_no,
					".$this->db->dbprefix('loan_groups').".name AS glname,
					CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) AS customer_name_en,
					CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_kh, ".	
					$this->db->dbprefix('quote_items').".product_name AS asset,".
					$this->db->dbprefix('quotes').".biller,".						
					$this->db->dbprefix('quotes').".quote_status as status,".
					$this->db->dbprefix('quotes').".date,".
					$this->db->dbprefix('quotes').".approved_date,".
					$this->db->dbprefix('sales').".issue_date, ".
					"COALESCE((SELECT u.username FROM erp_users u WHERE erp_quotes.updated_by = u.id), '') AS underwriter,
					CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) as co_name, ".
					$this->db->dbprefix('users').".username,myBranch.name as branchName,".							
					$this->db->dbprefix('quotes').".total * (".$this->db->dbprefix('currencies').".rate / ".$settings->rate .")as total,
					".$this->db->dbprefix('currencies').".name as crname");
					$this->db->from('quotes');
					$this->db->join('users','quotes.by_co=users.id','INNER');
					$this->db->join('sales', 'sales.quote_id = quotes.id', 'LEFT');
					$this->db->join('companies','quotes.customer_id=companies.id','INNER');
					$this->db->join('companies myBranch','users.branch_id=myBranch.id','LEFT');
					$this->db->join('quote_items', 'quotes.id = quote_items.quote_id', 'LEFT');
					$this->db->join('currencies','currencies.code = quote_items.currency_code','left');
					$this->db->join('loan_groups','loan_groups.id = quotes.loan_group_id','left');
					
					if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
						$this->db->where('quotes.branch_id', $this->session->branch_id);
					}
					if(!$view_draft && !($this->Owner || $this->Admin)) {
						$this->db->where('erp_quotes.quote_status <>', 'draft');
						$this->db->where('erp_quotes.quote_status <>', 'activated');
						$this->db->where('erp_quotes.quote_status <>', 'approved');
						$this->db->where('erp_quotes.quote_status <>', 'completed');
						$this->db->where('erp_quotes.status', 'loans');
					}
					$this->db->where('erp_quotes.quote_status <>', 'activated');
					$this->db->where('erp_quotes.quote_status <>', 'approved');
					$this->db->where('erp_quotes.quote_status <>', 'completed');
					$this->db->where('erp_quotes.status', 'loans');
					$this->db->order_by('quotes.date DESC');
					$this->db->limit(20);
					$q = $this->db->get();
					if ($q->num_rows() > 0) {
						foreach (($q->result()) as $row) {
							$data[] = $row;
						}
						return $data;
					}
					return FALSE;
					
	}
	
	/*public function getLastFiveQuoat($view_draft = null){
		$settings = $this->getSettingCurrncy();
		$this->db->select($this->db->dbprefix('v_quotes').".id,".
					$this->db->dbprefix('v_quotes').".reference_no,".
					$this->db->dbprefix('v_quotes').".glname,".
					$this->db->dbprefix('v_quotes').".customer_name_en,".
					$this->db->dbprefix('v_quotes').".customer_name_kh,".
					$this->db->dbprefix('v_quotes').".asset,".
					$this->db->dbprefix('v_quotes').".status,".
					$this->db->dbprefix('v_quotes').".date,".
					$this->db->dbprefix('v_quotes').".approved_date,".
					$this->db->dbprefix('v_quotes').".co_name,".
					$this->db->dbprefix('v_quotes').".branchName,".
					$this->db->dbprefix('v_quotes').".total * (".$this->db->dbprefix('v_quotes').".rate / ".$settings->rate .") as total,".
					$this->db->dbprefix('v_quotes').".crname");
					$this->db->from('v_quotes');
					if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
						$this->db->where('v_quotes.branch_id', $this->session->branch_id);
					}
					if(!$view_draft && !($this->Owner || $this->Admin)) {
						$this->db->where('v_quotes.status <>', 'draft');
						$this->db->where('v_quotes.status <>', 'activated');
					}
					$this->db->where('v_quotes.status <>', 'activated');
					$this->db->group_by('v_quotes.id');
					$this->db->order_by('v_quotes.date DESC');
					$this->db->limit(20);
					$q = $this->db->get();
					if ($q->num_rows() > 0) {
						foreach (($q->result()) as $row) {
							$data[] = $row;
						}
						return $data;
					}
					return FALSE;
					
	}*/
	
	public function getQuotesGroup(){
		$settings = $this->getSettingCurrncy();
		$this->db->select($this->db->dbprefix('quotes').".id,".
					$this->db->dbprefix('quotes').".reference_no,
					CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) AS customer_name_en,
					CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_kh, ".	
					$this->db->dbprefix('quote_items').".product_name AS asset,".
					$this->db->dbprefix('quotes').".biller,".						
					$this->db->dbprefix('quotes').".quote_status as status,".
					$this->db->dbprefix('quotes').".date,".
					$this->db->dbprefix('quotes').".approved_date,".
					$this->db->dbprefix('sales').".issue_date, ".
					$this->db->dbprefix('loan_groups').".name AS group_name, ".
					"COALESCE((SELECT u.username FROM erp_users u WHERE erp_quotes.updated_by = u.id), '') AS underwriter,
					CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) as co_name, ".
					$this->db->dbprefix('users').".username,myBranch.name as branchName,".							
					$this->db->dbprefix('quotes').".total * (".$this->db->dbprefix('currencies').".rate / ".$settings->rate .")as total,
					".$this->db->dbprefix('currencies').".name as crname");
					$this->db->from('quotes');				
					$this->db->join('users','quotes.by_co=users.id','INNER');
					$this->db->join('sales', 'sales.quote_id = quotes.id', 'left');
					$this->db->join('companies','quotes.customer_id=companies.id','INNER');
					$this->db->join('companies myBranch','users.branch_id=myBranch.id','left');
					$this->db->join('quote_items', 'quotes.id = quote_items.quote_id', 'left');
					$this->db->join('currencies','currencies.code = quote_items.currency_code','left');
					$this->db->join('loan_groups','quotes.loan_group_id = loan_groups.id','left');
					if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
						$this->db->where('quotes.branch_id', $this->session->branch_id);
					}
					if(!$view_draft && !($this->Owner || $this->Admin)){
						$this->db->where('erp_quotes.quote_status <>', 'draft');
						$this->db->where('quotes.quote_status <>', 'activated');
						$this->db->where('quotes.quote_status <>', 'approved');
						$this->db->where('quotes.quote_status <>', 'completed');
						$this->db->where('erp_quotes.status', 'loans');
					}
					$this->db->where('quotes.loan_group_id !=',null);
					$this->db->where('quotes.quote_status <>', 'activated');
					$this->db->where('quotes.quote_status <>', 'approved');
					$this->db->where('quotes.quote_status <>', 'completed');
					$this->db->where('erp_quotes.status', 'loans');
					$this->db->order_by('quotes.date DESC');
					$this->db->limit(20);
					$q = $this->db->get();
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
	
	public function getLastFiveContract(){
		$settings = $this->getSettingCurrncy();
		$this->db
                ->select($this->db->dbprefix('sales').".id,".
						$this->db->dbprefix('sales').".reference_no,".
						$this->db->dbprefix('loan_groups').".name AS glname,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other, ".
						$this->db->dbprefix('sales').".biller, ".
						$this->db->dbprefix('users').".username, ".		
						$this->db->dbprefix('sale_items').".product_name, ".
						$this->db->dbprefix('sale_items').".product_year, ".
						$this->db->dbprefix('variants').".name, 
						CONCAT(TRUNCATE((".$this->db->dbprefix('sales').".interest_rate*100), 2), '', '%') as interest, 
						CONCAT(TRUNCATE(".$this->db->dbprefix('sales').".term, 0), ' ', 'ថ្ងៃ') as term, 
						IF(".$this->db->dbprefix('sales').".frequency = 7, 'Weekly', IF(".$this->db->dbprefix('sales').".frequency = 14, 'Two Week', IF(".$this->db->dbprefix('sales').".frequency = 30, 'Monthly',''))) as pay_term, 						
						((COALESCE(".$this->db->dbprefix('sales').".total, 0))) * (".$this->db->dbprefix('currencies').".rate / ".$settings->rate .") as total_amount,
						".$this->db->dbprefix('sales').".grand_total * (".$this->db->dbprefix('currencies').".rate / ".$settings->rate .")as disburse,
						((((COALESCE(".$this->db->dbprefix('sales').".total, 0))) * (".$this->db->dbprefix('currencies').".rate / ".$settings->rate ."))- (".$this->db->dbprefix('sales').".grand_total * (".$this->db->dbprefix('currencies').".rate / ".$settings->rate ."))) as remaining,".
						$this->db->dbprefix('currencies').".name AS crname, ".
						$this->db->dbprefix('sales').".sale_status")
                ->from('sales')
				->join('users','sales.by_co=users.id','INNER')
				->join('sale_items', 'sales.id = sale_items.sale_id', 'INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('products', 'sale_items.product_id = products.id', 'INNER')
				->join('sale_services', 'sales.id = sale_services.sale_id', 'LEFT')
				->join('variants', 'variants.id = sale_items.color', 'LEFT')
				->join('quotes','quotes.id = sales.quote_id','left')
				->join('quote_items','quote_items.quote_id = quotes.id','left')
				->join('currencies','currencies.code = quote_items.currency_code','left')
				->join('loan_groups','loan_groups.id = sales.loan_group_id','left')
				->where($this->db->dbprefix('sales').'.sale_status !=', 'registered')
				->where($this->db->dbprefix('sales').'.status', 'loans')
				->group_by('sales.id')
				->order_by('sales.id','DESC');
				$this->db->limit(20);
				if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
						$this->db->where('sales.branch_id', $this->session->branch_id);
					}
				$q = $this->db->get();
				if ($q->num_rows() > 0) {
					foreach (($q->result()) as $row) {
						$data[] = $row;
					}
					return $data;
				}
				return FALSE;
	}
	public function getLastFiveDealer(){
		$this->db
            ->select($this->db->dbprefix('companies').".id, company, ".$this->db->dbprefix('companies').".name, vat_no, phone, email, (SELECT ".$this->db->dbprefix('addresses').".name FROM ".$this->db->dbprefix('addresses')." WHERE ".$this->db->dbprefix('addresses').".code = ".$this->db->dbprefix('companies').".state) as province, (SELECT ".$this->db->dbprefix('addresses').".name FROM ".$this->db->dbprefix('addresses')." WHERE ".$this->db->dbprefix('addresses').".code = ".$this->db->dbprefix('companies').".city) as city, bank_name, account_number, account_name")
            ->from("companies")
            ->where('group_name', 'supplier');
		$this->db->limit(5);
					$q = $this->db->get();
					if ($q->num_rows() > 0) {
						foreach (($q->result()) as $row) {
							$data[] = $row;
						}
						return $data;
					}
					return FALSE;
	}
	public function getBranchByUserId($id){
		$this->db->select('companies.id, companies.name');
		$this->db->where('users.id',$id);
		//$this->db->where("companies.group_name","biller");
		$this->db->join('companies', 'companies.id = users.branch_id');
		$this->db->from('users');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	//my function using total at dashboard
	/*function getAllApplicant() {
		$settings = $this->getSettingCurrncy();
		$this->db->select('COUNT(erp_quotes.id) as app_num, 
		SUM('.$this->db->dbprefix('quotes').'.total * ('.$this->db->dbprefix('currencies').'.rate / '.$settings->rate .')) as app_amount');
		$this->db->join('quote_items', 'quotes.id = quote_items.quote_id', 'left');
		$this->db->join('currencies','currencies.code = quote_items.currency_code','left');
		$q = $this->db->get('quotes', 1);
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}*/
	function getAllApplicant($month, $year, $last_day) {
		$settings = $this->getSettingCurrncy();
		$this->db->select('COUNT(erp_quotes.id) as app_num, 
		SUM('.$this->db->dbprefix('quotes').'.total) as app_amount');
		$this->db->where('status','loans');
		$this->db->where('quotes.date >=', $year.'-'.$month.'-01 00:00:00');
		$this->db->where('quotes.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
		$q = $this->db->get('quotes', 1);
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	function getAllContract($month, $year, $last_day){
		$this->db->select('COUNT(erp_sales.id) as sale_num, 
		SUM('.$this->db->dbprefix('sales').'.total) as loans_amt');
		$this->db->where('sales.sale_status','activated');
		//$this->db->or_where('sales.sale_status','approved');
		$this->db->where('sales.status','loans');
		//$this->db->where('sales.date BETWEEN "'.  ($year.'-'.$month.'-01 00:00:00') . '" and "'.  ($year.'-'.$month.'-'.$last_day.' 23:59:59').'"');
		$this->db->where('sales.date >=', $year.'-'.$month.'-01 00:00:00');
		$this->db->where('sales.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
		$q=$this->db->get('sales',1);
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	function getDisbursementAmount($month, $year, $last_day){
		$this->db->select('COUNT(erp_sales.id) as sale_num,
		SUM('.$this->db->dbprefix('sales').'.grand_total) as disbursement_amt');
		$this->db->where('sales.status','loans');
		$this->db->where('sales.date >=', $year.'-'.$month.'-01 00:00:00');
		$this->db->where('sales.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
		$q=$this->db->get('sales',1);
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	function getPaymentAmount(){
		$this->db->select('COUNT(erp_sales.id) as sale_num,
		SUM('.$this->db->dbprefix('sales').'.paid) as total_collection');
		$this->db->where('sales.status','loans'); 
		$q=$this->db->get('sales',1);
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
		/*$this->db->select('SUM(erp_payments.amount) AS total_collection');
		$this->db->join('sales','payments.sale_id=sales.id','LEFT');
		$this->db->where('payments.type <>','saving');
		$q = $this->db->get('payments');
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;*/
	}
	function getAllRejected($month, $year, $last_day){
		$this->db->select('COUNT(erp_quotes.id) as reject_num, 
		SUM('.$this->db->dbprefix('quotes').'.total) as total_rejectd');
		$this->db->where('quote_status','rejected');
		$this->db->where('status','loans');
		$this->db->where('quotes.date >=', $year.'-'.$month.'-01 00:00:00');
		$this->db->where('quotes.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
		$q = $this->db->get('quotes', 1);
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	function CountSaleID(){
		$this->db->select('COUNT(erp_sales.id) as id');
		$this->db->where('erp_sales.sale_status =','activated');
		$this->db->or_where('erp_sales.sale_status =','approved');
		$q = $this->db->get('erp_sales');
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	function CountID_Quotes(){
		$this->db->select('COUNT(erp_quotes.id) as id');
		$this->db->where('erp_quotes.quote_status','applicant');
		$this->db->where('erp_quotes.status','loans');
		$q = $this->db->get('quotes');
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	function CountGroupLoan_Quotes(){
		$this->db->select('COUNT(erp_quotes.loan_group_id) as id');
		$this->db->where('erp_quotes.quote_status','applicant');
		$this->db->where('erp_quotes.status','loans');
		$q = $this->db->get('quotes');
		
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	public function getAllBranch_Name(){
		$this->db->select("erp_companies.id,erp_companies.name");
		$this->db->from('erp_companies');
		$this->db->where('erp_companies.group_name =','biller');
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	function getSaleTotal(){
		$this->db->select('SUM(erp_sales.total) as s_total');
		$this->db->where('status','loans');
		$this->db->where('sale_status','activated');
		$q=$this->db->get('sales',1);
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	
	function getPayment_amount(){
		$this->db->select('SUM(erp_payments.principle_amount) as p_amt');
		$q=$this->db->get('erp_payments',1);
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	
	function getExpanse($month, $year, $last_day)
	{
		$this->db->select('SUM(erp_expenses.amount) AS total_expanse');  
		$this->db->where('expenses.date >=', $year.'-'.$month.'-01 00:00:00');
		$this->db->where('expenses.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
		$q=$this->db->get('expenses',1);
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
}
