<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }

    public function get_total_qty_alerts() {
        $this->db->where('quantity <= alert_quantity', NULL, FALSE)->where('track_quantity', 1);
        return $this->db->count_all_results('products');
    }
	
	public function getNewApplicant() {
        $this->db->where('status', 'applicant', 1);
        return $this->db->count_all_results('quotes');
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

	public function getBranchDName(){
		$this->db->select('id, name');
		$this->db->where('companies.group_name','biller');
		$q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;		
	}
	
	/*public function getJoinLeaseByID($id = NULL) {
		$q = $this->db->get_where('companies', array('id' => $id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return FALSE;
	}*/
	
	public function getJoinLeaseByID($id = NULL) {
		$this->db->select('companies.*,identify_types.name as ident_name');
		$this->db->where(array('companies.id' => $id));
		$this->db->join('identify_types','identify_types.id=companies.identify','left');		
        $q = $this->db->get('companies');
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return FALSE;
	}
	/*public function getJoinGuarantorByID($id = NULL) {
		$q = $this->db->get_where('companies', array('id' => $id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return FALSE;
	}*/
	public function getJoinGuarantorByID($id = NULL) {
		$this->db->select('companies.*,identify_types.name as ident_name');
		$this->db->where(array('companies.id' => $id));
		$this->db->join('identify_types','identify_types.id=companies.identify','left');		
        $q = $this->db->get('companies');
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return FALSE;
	}
	public function getApprovedApplicant() {
        $this->db->where('status', 'approved');
		$this->db->or_where('status', 'approved_condition');
        return $this->db->count_all_results('quotes');
    }
	
	public function getActivatedApplicant() {
        $this->db->where('sale_status', 'activated', 1);
        return $this->db->count_all_results('sales');
    }

    public function get_expiring_qty_alerts() {
        $date = date('Y-m-d', strtotime('+3 months'));
        $this->db->select('SUM(quantity_balance) as alert_num')
        ->where('expiry !=', NULL)->where('expiry !=', '0000-00-00')
        ->where('expiry <', $date);
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            $res = $q->row();
            return (INT) $res->alert_num;
        }
        return FALSE;
    }

	/* Alert Customer Payments */
	public function get_sale_suspend_alerts(){
        $q = $this->db->query('
				SELECT COUNT(n.date) AS alert_num, MIN(n.date) AS date
				FROM 
				(
					SELECT date
					FROM erp_suspended_bills 
				) AS n
				WHERE
				DATE_SUB(n.date, INTERVAL 1 DAY) <= CURDATE()
		');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

	
	/* Alert Customer Payments */
	public function get_customer_payments_alerts(){
        $q = $this->db->query('
				SELECT COUNT(n.date) AS alert_num, MIN(n.date) AS date
				FROM 
				(
					SELECT payment_term , date
					FROM erp_sales
					WHERE
					`payment_term` <> 0
					ORDER BY date DESC
				) AS n
				WHERE
				DATE_SUB(n.date, INTERVAL 1 DAY) <= CURDATE()
		');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	/* Alert Purchase Payments */
	public function get_purchase_payments_alerts(){
        $q = $this->db->query('
			SELECT COUNT(n.date) AS alert_num, MIN(n.date) AS date
				FROM 
				(
					SELECT payment_term , date
					FROM erp_purchases
					WHERE
					`payment_term` <> 0
					ORDER BY date DESC
				) AS n
				WHERE
				DATE_SUB(n.date, INTERVAL 1 DAY) <= CURDATE()
		');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getProducts()
    {
		$this->db->select('id, code, name');
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_deliveries_alert(){
        $q = $this->db->query('
			SELECT COUNT(n.date) AS alert_num, MIN(n.date) AS date
				FROM 
				(
					SELECT date
					FROM erp_deliveries
					WHERE
					delivery_status = "pending"
					ORDER BY date DESC
				) AS n
				WHERE
				DATE_SUB(n.date, INTERVAL 1 DAY) <= CURDATE()
		');
        if ($q->num_rows() > 0) {
            //$res = $q->row();
            return $q->row();
        }
        return FALSE;
	}
	
	/* Customer Alerts */
	public function get_customer_alerts(){
		$this->db->select('COUNT(*) AS count');
		$this->db->where('CURDATE() >= DATE_SUB(end_date , INTERVAL (SELECT alert_day FROM erp_settings) DAY)');
		$q = $this->db->get('companies');
		if($q->num_rows() > 0 ){
			$q = $q->row();
			return $q->count;
		}
		return false;
	}

    public function get_setting() {
        $q = $this->db->get('settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function get_acccount_setting() {
        $q = $this->db->get('account_settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getDateFormat($id) {
        $q = $this->db->get_where('date_format', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllCompanies($group_name) {
        $q = $this->db->get_where('companies', array('group_name' => $group_name));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getAllDealer($group_name) {
        $q = $this->db->get_where('companies', array('group_name' => $group_name));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getSupplierByArray($array){
		$this->db->select("id, CONCAT(company, ' (', name, ')') as text", FALSE)
				->from("erp_companies")
				->where_in('id', $array);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	/*public function getServicesByStatus($status=NULL) {
		$this->db->join('tax_rates','tax_rates.id = services.tax_id','INNER');
		$q = $this->db->get_where('services', array('status' => $status));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}*/
	public function getServicesByStatus($status=NULL) {
		$this->db->select('services.*,tax_rates.name');
		$this->db->join('tax_rates','tax_rates.id = services.tax_id','INNER');
		$this->db->where(array('services.status' => $status));
		$q = $this->db->get('services');
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getQuoteServicesByQuoteID($q_id=NULL) {
		$q = $this->db->get_where('quote_services', array('quote_id' => $q_id));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getQuoteRejectByQuoteID($q_id=NULL) {
		$q = $this->db->get_where('quote_reject', array('quote_id' => $q_id));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getProductSupplier($group_name) {
		//$this->db->select("id, name as text", FALSE);
        $q = $this->db->get_where('products', array('code' => $group_name));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getCompanyByID($id) {
		$this->db->select('companies.*,identify_types.name as ident_name,quotes.id as q_id, quotes.by_co as created_by,quotes.note, quotes.installment_date,users.id as u_id,users.branch_id as b_id, CONCAT(erp_users.first_name," ", erp_users.last_name) as cname,users.commission');
		$this->db->where(array('companies.id' => $id), 1);
		$this->db->join('identify_types','identify_types.id=companies.identify','left');
		$this->db->join('quotes','quotes.customer_id = companies.id', 'left');
		$this->db->join('users', 'quotes.by_co = users.id','left');
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getCompanyCOName($id) {
		$this->db->select('companies.*,identify_types.name as ident_name, quotes.by_co as created_by,quotes.note, quotes.installment_date,CONCAT(erp_users.first_name," ",erp_users.last_name) AS co_name');
		$this->db->where(array('companies.id' => $id), 1);
		$this->db->join('identify_types','identify_types.id=companies.identify','left');
		$this->db->join('quotes','companies.id = quotes.customer_id','left');
		$this->db->join('users','quotes.by_co=users.id','INNER');
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	/*public function getCompanyByID($id) {
        $q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }*/
	
	public function getFieldCheckByQuoteID($id = NULL) {
		$q = $this->db->get_where('field_check', array('quote_id' => $id),1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getSuppliers(){
		$this->db->select("id, name");
		$this->db->where('group_name', 'supplier');
		$q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getLoanGroups()
    {	$this->db->select('id, name');
        $q = $this->db->get('loan_groups');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getCustomers(){
		$this->db->select("id, name");
		$this->db->where('group_name', 'customer');
		$q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
    
    function getSupplierNameByID($sup_id = null)
	{
        $this->db->select('name, company');
		$this->db->where(array('id' => $sup_id));
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
    
      function getBillerNameByID($biller_id = null)
	{
		$this->db->select('company, name');
		$this->db->where(array('id' => $biller_id));
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getCompanyByArray($id) {
		$this->db->select();
		$this->db->where_in('id', $id);
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	
	public function getAccountByID($id) {
		$this->db->select("erp_gl_charts.accountcode, erp_gl_charts.accountname, erp_gl_charts.parent_acc, erp_gl_sections.sectionname")
				->from("erp_gl_charts")
				->join("erp_gl_sections","erp_gl_charts.sectionid=erp_gl_sections.sectionid","INNER")
				->where(array('erp_gl_charts.accountcode' => $id));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getTaxByID($id) {
		$this->db->select("gl_charts_tax.accountcode, gl_charts_tax.accountname, gl_charts_tax.accountname_kh, erp_gl_sections.sectionname")
				->from("gl_charts_tax")
				->join("erp_gl_sections","gl_charts_tax.sectionid=erp_gl_sections.sectionid","INNER")
				->where(array('gl_charts_tax.accountcode' => $id));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getJournalByID($id) {
		$this->db
				->select("gt.tran_no,gt.tran_no AS g_tran_no, gt.tran_type, gt.tran_date, 
							gt.reference_no, gt.account_code, 
							gt.narrative, gt.description, 
							(IF(gt.amount > 0, gt.amount, IF(gt.amount = 0, 0, null))) as debit, 
							(IF(gt.amount < 0, abs(gt.amount), null)) as credit")
				->from("erp_gl_trans gt")
				->where('gt.tran_id', $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getReceivableByID($id){
		$this->db
				->select("id, date, reference_no, biller, customer, sale_status, grand_total, paid, (grand_total-paid) as balance, payment_status")
				->from('sales')
				->where(array('payment_status !=' => 'Returned', 'payment_status !='=>'paid', '(grand_total-paid) <>' =>0, 'id' =>$id));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getRecieptByID($id){
		$this->db
				->select($this->db->dbprefix('payments') . ".id,
				" . $this->db->dbprefix('sales') . ".suspend_note AS noted,
				" . $this->db->dbprefix('payments') . ".date AS date,
				" . $this->db->dbprefix('payments') . ".reference_no as payment_ref, 
				" . $this->db->dbprefix('sales') . ".reference_no as sale_ref, customer,paid_by, amount, type", $this->db->dbprefix('payments') . ".id")
                ->from('payments')
                ->join('sales', 'payments.sale_id=sales.id', 'left')
                ->join('purchases', 'payments.purchase_id=purchases.id', 'left')
                ->group_by('payments.id')
				->order_by('payments.date desc')
				->where(array('payments.type !='=>"sent", 'sales.customer !='=>'', 'payments.id'=>$id));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getPayableByID($id){
		$this->db
				->select("id, date, reference_no, supplier, status, grand_total, paid, (grand_total-paid) as balance, payment_status")
                ->from('purchases')
				->where(array('payment_status !='=>'paid', 'id'=>$id));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    public function getCustomerGroupByID($id) {
        $q = $this->db->get_where('customer_groups', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	public function getCompanyWarehouseByID($id) {
        $q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
			$rs = $q->row();
			$warehouses = $rs->cf5;
	
			$query = $this->db->query('
				SELECT
					erp_companies.id,
					erp_companies.cf5,
					erp_users.warehouse_id,
					wh.`name`
				FROM
					erp_companiess

				INNER JOIN erp_users ON erp_users.biller_id = erp_companies.id
				INNER JOIN 
				(
					SELECT w.`name`,w.id
					FROM erp_warehouses w
				) AS wh
				WHERE
					wh.id IN ('.$warehouses.')
					AND erp_companies.id = '.$id.'
				GROUP BY wh.`name`
			');
			if ($query->num_rows() > 0) {
				foreach($query->result() as $row){
					$data[] = $row;
				}
				return $data;
			}
        }
		return FALSE;
    } 
	
	public function getWarehouseCompanyByID($id) {
        $q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
			$rs = $q->row();
			$warehouses = $rs->cf5;
	
			$query = $this->db->query('
				SELECT
					erp_companies.id AS company_id,
					erp_companies.cf5,
					wh.id,
					wh.`name`
				FROM
					erp_companies
				INNER JOIN erp_users
				ON erp_users.id = erp_companies.id
				INNER JOIN 
				(
					SELECT w.`name`,w.id
					FROM erp_warehouses w
				) AS wh
				WHERE
					wh.id IN ('.$warehouses.')
					/* AND erp_companies.id = '.$id.' */
					
				GROUP BY wh.`name`
			');
			if ($query->num_rows() > 0) {
				foreach($query->result() as $row){
					$data[] = $row;
				}
				return $data;
			}
        }
		return FALSE;
    } 
	
	public function getSuspendByID($id){
		$this->db->select("floor,erp_suspended.name as room_name, erp_suspended_bills.total as price, (SELECT deposit_amount FROM erp_companies WHERE erp_companies.id = erp_suspended_bills.customer_id) as deposite ,description, (SELECT MAX(customer) FROM erp_suspended_bills sb WHERE sb.suspend_id = erp_suspended.id ) as customer_name, (SELECT MAX(date) FROM erp_suspended_bills sb WHERE sb.suspend_id = erp_suspended.id ) as start_date, erp_companies.end_date as end_date, (12 * (YEAR (erp_companies.end_date) - YEAR (erp_suspended_bills.date)) + (MONTH (erp_companies.end_date) - MONTH (erp_suspended_bills.date))) as term_year, CASE WHEN erp_suspended.status = 0 THEN 'free' WHEN erp_suspended.status = 1 THEN 'busy' ELSE 'busy' END AS status")
		->join('erp_suspended_bills', 'erp_suspended.id = erp_suspended_bills.suspend_id', 'left')
		->join('erp_companies', 'erp_companies.id = erp_suspended_bills.customer_id', 'left')
		->from("erp_suspended")
		->where('erp_suspended.id',$id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	/*
	public function getCompanyWarehouseByID($id) {
        $q = $this->db->query('
				SELECT
					erp_companies.id,
					erp_companies.cf5,
					erp_users.warehouse_id,
					wh.`name`
				FROM
					erp_companies

				INNER JOIN erp_users ON erp_users.biller_id = erp_companies.id
				INNER JOIN 
				(
					SELECT w.`name`,w.id
					FROM erp_warehouses w
				) AS wh
				WHERE
					wh.id IN (cf5)
					AND erp_companies.id = 400
				GROUP BY wh.`name`
		');
        if ($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
        }
		return FALSE;
    }
	*/

    public function getUser($id = NULL) {
        if (!$id) {
            $id = $this->session->userdata('user_id');
        }
        $q = $this->db->get_where('users', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	/*Use to Export*/
	public function getUsers($id){
		$this->db
			->select($this->db->dbprefix('users').".id as id, first_name, last_name, email, company, award_points, " . $this->db->dbprefix('groups') . ".name, (CASE WHEN active = 0 THEN 'Inactive' ELSE 'Active' END) as astatus")
            ->from("users")
            ->join('groups', 'users.group_id=groups.id', 'left')
            ->group_by('users.id')
            ->where(array('company_id'=> NULL, 'users.id'=>$id));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	/*Use to Export*/
	public function getEmployees($id){
		$this->db
			->select($this->db->dbprefix('users').".id as id, first_name, last_name, email, company, award_points, " . $this->db->dbprefix('groups') . ".name, (CASE WHEN active = 0 THEN 'Inactive' ELSE 'Active' END) as astatus")
            ->from("users")
            ->join('groups', 'users.group_id=groups.id', 'left')
            ->group_by('users.id')
            ->where(array('company_id'=> NULL, 'users.id'=>$id));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getProductVariantByID($id, $uom = null) {
        if($uom) {
            $q = $this->db->get_where('product_variants', array('product_id' => $id, 'name' => $uom), 1);
        }else{
            $q = $this->db->get_where('product_variants', array('product_id' => $id), 1);
        }
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getProductVariantByOptionID($option_id){
		$q = $this->db->get_where('product_variants', array('id' => $option_id), 1);
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
    public function getProductByID($id) {
        $q = $this->db->get_where('products', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllCurrencies() {
        $q = $this->db->get('currencies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCurrencyByCode($code) {
        $q = $this->db->get_where('currencies', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
    public function getAllTaxRates() {
        $q = $this->db->get('tax_rates');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAllUsers() {
        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTaxRateByID($id) {
        $q = $this->db->get_where('tax_rates', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getTaxRates() {
        $q = $this->db->get('tax_rates');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllWarehouses() {
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getWarehouseByID($id) {
        $q = $this->db->get_where('warehouses', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getWarehouseByCode($code) {
        $q = $this->db->get_where('warehouses', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	 public function getChartByID($id) {
        $q = $this->db->get_where('gl_charts', array('accountcode' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getAllCategories() {
        $this->db->order_by('name');
        $q = $this->db->order_by('name')->get('categories');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_Acc_setting() 
	{
        $q = $this->db->get('account_settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getAllSuppliers() {
        $q = $this->db->get_where('companies', array('group_name' => 'supplier'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCategoryByID($id) {
        $q = $this->db->get_where('categories', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getModelByID($id) {
        $q = $this->db->get_where('type', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getGiftCardByID($id) {
        $q = $this->db->get_where('gift_cards', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getGiftCardByNO($no) {
        $q = $this->db->get_where('gift_cards', array('card_no' => $no), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getGiftCardHistoryByNo($no) {
        $q = $this->db->get_where('gift_cards', array('card_no' => $no), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getDepositByCompanyID($comapny_id) {
        $q = $this->db->get_where('companies', array('id' => $comapny_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateInvoiceStatus() {
        $date = date('Y-m-d');
        $q = $this->db->get_where('invoices', array('status' => 'unpaid'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                if ($row->due_date < $date) {
                    $this->db->update('invoices', array('status' => 'due'), array('id' => $row->id));
                }
            }
            $this->db->update('settings', array('update' => $date), array('setting_id' => '1'));
            return true;
        }
    }

    public function modal_js() {
        return '<script type="text/javascript">' . file_get_contents($this->data['assets'] . 'js/modal.js') . '</script>';
    }

    public function getReference($field, $code = '') {
        $q = $this->db->get('order_ref');
		
        if ($q->num_rows() > 0) {
            $ref = $q->row();
            switch ($field) {
                case 'so':
                    $prefix = $this->Settings->sales_prefix;
                    break;
                case 'qu':
                    $prefix = $this->Settings->quote_prefix;
                    break;
                case 'po':
                    $prefix = $this->Settings->purchase_prefix;
                    break;
                case 'to':
                    $prefix = $this->Settings->transfer_prefix;
                    break;
                case 'do':
                    $prefix = $this->Settings->delivery_prefix;
                    break;
                case 'pay':
                    $prefix = $this->Settings->payment_prefix;
                    break;
                case 'pos':
                    $prefix = isset($this->Settings->sales_prefix) ? $this->Settings->sales_prefix . '/POS' : '';
                    break;
                case 're':
                    $prefix = $this->Settings->return_prefix;
                    break;
                case 'ex':
                    $prefix = $this->Settings->expense_prefix;
                    break;
				case 'sp':
                    $prefix = $this->Settings->sale_payment_prefix;
                    break;
				case 'pp':
                    $prefix = $this->Settings->purchase_payment_prefix;
                    break;
				case 'sl':
                    $prefix = $this->Settings->sale_loan_prefix;
                    break;
				case 'tr':
                    $prefix = $this->Settings->transaction_prefix;
					break;
				case 'con':
                    $prefix = $this->Settings->convert_prefix;
					break;
                case 'rep':
                    $prefix = $this->Settings->returnp_prefix;
					break;
				case 'cl':
                    $prefix = $this->Settings->collateral_prefix;
					break;
				case 'cap':
                    $prefix = $this->Settings->capital_prefix;
					break;
				case 'sav':
                    $prefix = $this->Settings->saving_prefix;
					break;
				case 'jr':
                    $prefix = $this->Settings->journal_prefix;
					break;
                default:
                    $prefix = '';
            }

            $ref_no = (!empty($prefix)) ? $prefix . '/' : '';
			$ref_num = (!empty($prefix)) ? $prefix . '-' : '';
			
			if ($this->Settings->reference_format == 1) {
                $ref_no .= date('ym') . "/" . sprintf("%08s", $ref->{$field});
            }elseif ($this->Settings->reference_format == 2) {
                $ref_no .= date('Y') . "/" . sprintf("%08s", $ref->{$field});
            } elseif ($this->Settings->reference_format == 3) {
                $ref_no .= date('Y/m') . "/" . sprintf("%08s", $ref->{$field});
            } elseif ($this->Settings->reference_format == 4) {
                $ref_no .= sprintf("%08s", $ref->{$field});
            } elseif ($this->Settings->reference_format == 5) {
                $ref_no .= $this->getRandomReference();
            } else {
				if($code!=''){
					$ref_no = $ref_num . '' . $code . "-" . sprintf("%08s", $ref->{$field});
				}else{
				$ref_no = $ref_num . '' . sprintf("%08s", $ref->{$field});
				}
			}

            return $ref_no;
        }
        return FALSE;
    }

    public function getRandomReference($len = 12) {
        $result = '';
        for ($i = 0; $i < $len; $i++) {
            $result .= mt_rand(0, 9);
        }

        if ($this->getSaleByReference($result)) {
            $this->getRandomReference();
        }

        return $result;
    }

    public function getSaleByReference($ref) {
        $this->db->like('reference_no', $ref, 'before');
        $q = $this->db->get('sales', 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	/*
    public function updateReference($field) {
        $q = $this->db->get_where('order_ref', array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m')), 1);
        if ($q->num_rows() > 0) {
            $ref = $q->row();
            $this->db->update('order_ref', array($field => $ref->{$field} + 1), array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m')));
            return TRUE;
        }
        return FALSE;
    }
	*/
	
	public function updateReference($field) {
        $q = $this->db->get('order_ref');
        if ($q->num_rows() > 0) {
            $ref = $q->row();
            $this->db->update('order_ref', array($field => $ref->{$field} + 1));
            return TRUE;
        }
        return FALSE;
    }

    public function checkPermissions() {
        $q = $this->db->get_where('permissions', array('group_id' => $this->session->userdata('group_id')), 1);
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        return FALSE;
    }
    
    public function getPermission() {
        $q = $this->db->get_where('permissions', array('group_id' => $this->session->userdata('group_id')), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getNotifications() {
        $date = date('Y-m-d H:i:s', time());
        $this->db->where("from_date <=", $date);
        $this->db->where("till_date >=", $date);
        if (!$this->Owner) {
            if ($this->Supplier) {
                $this->db->where('scope', 4);
            } elseif ($this->Customer) {
                $this->db->where('scope', 1)->or_where('scope', 3);
            } elseif (!$this->Customer && !$this->Supplier) {
                $this->db->where('scope', 2)->or_where('scope', 3);
            }
        }
        $q = $this->db->get("notifications");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getUpcomingEvents() {
        $dt = date('Y-m-d');
        $this->db->where('start >=', $dt)->order_by('start')->limit(5);
        if ($this->Settings->restrict_calendar) {
            $this->db->where('user_id', $this->session->userdata('user_id'));
        }

        $q = $this->db->get('calendar');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getUserGroup($user_id = false) {
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $group_id = $this->getUserGroupID($user_id);
        $q = $this->db->get_where('groups', array('id' => $group_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getUserGroupID($user_id = false) {
        $user = $this->getUser($user_id);
        return $user->group_id;
    }

    public function getWarehouseProductsVariants($option_id, $warehouse_id = NULL) {
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getPurchasedItem($where_clause) {
        $orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->order_by('date', $orderby);
        $this->db->order_by('purchase_id', $orderby);
        $q = $this->db->get_where('purchase_items', $where_clause);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function syncVariantQty($variant_id, $warehouse_id, $product_id = NULL) {
        $balance_qty = $this->getBalanceVariantQuantity($variant_id);
        $wh_balance_qty = $this->getBalanceVariantQuantity($variant_id, $warehouse_id);		
		
        if ($this->db->update('product_variants', array('quantity' => $balance_qty), array('id' => $variant_id))) {
            if ($this->getWarehouseProductsVariants($variant_id, $warehouse_id)) {
                $this->db->update('warehouses_products_variants', array('quantity' => $wh_balance_qty), array('option_id' => $variant_id, 'warehouse_id' => $warehouse_id));
            } else {
                if($wh_balance_qty) {
					//$option = $this->getProductVariantByID($product_id);
					//$variant_qty = $option->qty_unit;
					//$quantity = $option->quantity;
                    $this->db->insert('warehouses_products_variants', array('quantity' => $wh_balance_qty, 'option_id' => $variant_id, 'warehouse_id' => $warehouse_id, 'product_id' => $product_id));
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    public function getWarehouseProducts($product_id, $warehouse_id = NULL) {
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
    public function getPurchaseBalanceQuantity($product_id, $warehouse_id = NULL) {
        $this->db->select('SUM(COALESCE(quantity_balance, 0)) as stock', False);
        $this->db->where('product_id', $product_id)->where('quantity_balance !=', 0);
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            $data = $q->row();
            return $data->stock;
        }
        return 0;
    }
	
	public function getProudctBalanceQuantity($product_id, $warehouse_id = NULL) {
        $this->db->select('SUM(COALESCE('.$this->db->dbprefix('product_variants').'.quantity, 0)) as stock', False);
		$this->db->join('warehouses_products_variant', 'warehouses_products_variants.product_id = product_variants.product_id');
        $this->db->where($this->db->dbprefix('product_variants').'.product_id', $product_id)->where($this->db->dbprefix('product_variants').'.quantity !=', 0);
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            $data = $q->row();
            return $data->stock;
        }
        return 0;
    }
	
	public function getProductQty($product_id){
		$this->db->select('SUM(COALESCE(quantity, 0)) as stock', False);
		$this->db->where('id',$product_id);
		$q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            $data = $q->row();
            return $data->stock;
        }
        return 0;
	}
	
    public function syncProductQty($product_id, $warehouse_id) {
        $balance_qty = $this->getBalanceQuantity($product_id);
        $wh_balance_qty = $this->getBalanceQuantity($product_id, $warehouse_id);

        if ($this->db->update('products', array('quantity' => $balance_qty), array('id' => $product_id))) {
            if ($this->getWarehouseProducts($product_id, $warehouse_id)) {
                $this->db->update('warehouses_products', array('quantity' => $wh_balance_qty), array('product_id' => $product_id, 'warehouse_id' => $warehouse_id));
            } else {
                if( ! $wh_balance_qty) { $wh_balance_qty = 0; }
                $this->db->insert('warehouses_products', array('quantity' => $wh_balance_qty, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id));
            }
            return TRUE;
        }
        return FALSE;
    }
    
    function getCustomerNameByID($cus_id = null)
	{
        $this->db->select('name, company');
		$this->db->where(array('id' => $cus_id));
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    public function getSaleByID($id) {
        $q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	function getSellingByID($cus_id = null)
	{
        $this->db->select("id, date, reference_no, biller, customer, sale_status, grand_total, paid, (grand_total-paid) as balance, payment_status");
		$this->db->where(array('id' => $cus_id));
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    public function getSalePayments($sale_id) {
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
    public function syncSalePaymentsCur($id) {
		
        $sale = $this->getSaleByID($id);
        $payments = $this->getSalePayments($id);
        $paid = 0;
        foreach ($payments as $payment) {
            if ($payment->type == 'returned') {
				$paid -= $sale->paid;
            } else {
				$paid += $sale->paid;
            }
        }
		$sale_status = $sale->sale_status;
        $payment_status = $paid <= 0 ? 'pending' : $sale->payment_status;
        if ($paid <= 0 && $sale->due_date <= date('Y-m-d')) {
            if ($payment->type == 'returned') {
				$payment_status = 'returned';
				$payment_term = 0;
				$paid = -1 * abs($paid);
			}else{
				if($sale->paid == 0 && $sale->grand_total == 0){
					$payment_status = 'paid';
					$sale_status = 'completed';
				}else{
					$payment_status = 'due';
				}
			}
        } elseif ($this->erp->formatDecimal($sale->grand_total) > $this->erp->formatDecimal($paid) && $paid > 0) {
            $payment_status = 'partial';
        } elseif ($this->erp->formatDecimal($sale->grand_total) <= $this->erp->formatDecimal($paid)) {
			if ($payment->type == 'returned') {
				$payment_status = 'returned';
				$paid = -1 * abs($paid);
			}else{
				$payment_status = 'paid';
				$sale_status = 'completed';
			}
			$payment_term = 0;
        }

        if ($this->db->update('sales', array('paid' => $paid, 'sale_status' => $sale_status ,'payment_status' => $payment_status,'payment_term'=>$payment_term), array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	
	public function syncSalePayments($id) {
		$payment_term = 0;
        $sale = $this->getSaleByID($id);
        $payments = $this->getSalePayments($id);
        $paid = 0;
		$principle_amount = 0;
		$interest_amount = 0;
		$penalty_amount = 0;
		if($payments) {
			foreach ($payments as $payment) {
				if ($payment->type == 'returned') {
					$paid -= ($payment->amount);
					//$paid -= $sale->paid;
				} else {
					$paid += ($payment->amount);
					$interest_amount += ($payment->interest_amount);
					$penalty_amount += ($payment->penalty_amount);
					$principle_amount += ($payment->amount - $payment->interest_amount - $payment->penalty_amount);
					//$paid += $sale->paid;
				}
			}
		}
		$sale_status = $sale->sale_status;
        $payment_status = $paid <= 0 ? 'pending' : $sale->payment_status;
        if ($principle_amount <= 0 && $sale->due_date <= date('Y-m-d')) {
            if ($payment->type == 'returned') {
				$payment_status = 'returned';
				$payment_term = 0;
				$paid = -1 * abs($paid);
			}else{
				if($sale->paid == 0 && $sale->grand_total == 0){
					$payment_status = 'paid';
					$sale_status = 'completed';
				}else{
					$payment_status = 'due';
				}
			}
        } elseif ($this->erp->formatDecimal($sale->grand_total) > $this->erp->formatDecimal($principle_amount) && $principle_amount > 0) {
            $payment_status = 'partial';
        } elseif ($this->erp->formatDecimal($sale->grand_total) <= $this->erp->formatDecimal($principle_amount)) {
			if ($payment->type == 'returned') {
				$payment_status = 'returned';
				$paid = -1 * abs($paid);
			}else{
				$payment_status = 'paid';
				$sale_status = 'completed';
			}
			$payment_term = 0;
        }
		if ($this->db->update('sales', array('paid' => $paid, 'interest_amount' => $interest_amount, 'penalty_amount' => $penalty_amount, 'sale_status' => $sale_status ,'payment_status' => $payment_status,'payment_term'=>$payment_term), array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	
	

    public function getPurchaseByID($id) {
        $q = $this->db->get_where('purchases', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPurchasePayments($purchase_id) {
        $q = $this->db->get_where('payments', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function syncPurchasePayments($id) {
        $purchase = $this->getPurchaseByID($id);
        $payments = $this->getPurchasePayments($id);
        $paid = 0;
        foreach ($payments as $payment) {
            $paid += $payment->amount;
        }

        $payment_status = $paid <= 0 ? 'pending' : $purchase->payment_status;
		$payment_term = $purchase->payment_term;
        if ($this->erp->formatDecimal($purchase->grand_total) > $this->erp->formatDecimal($paid) && $paid > 0) {
            $payment_status = 'partial';
        } elseif ($this->erp->formatDecimal($purchase->grand_total) <= $this->erp->formatDecimal($paid)) {
            $payment_status = 'paid';
			$payment_term = 0;
        }

        if ($this->db->update('purchases', array('paid' => $paid, 'payment_status' => $payment_status, 'payment_term' => $payment_term), array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    private function getBalanceQuantity($product_id, $warehouse_id = NULL) {
        $this->db->select("SUM(COALESCE(quantity_balance, 0)) as stock", False);
        $this->db->where('product_id', $product_id);
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            $data = $q->row();
            return $data->stock;
        }
        return 0;
    }
    
    public function getProductType($product_id){
        $this->db->select('type');
        $this->db->where('id', $product_id);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            $data = $q->row();
            return $data->type;
        }
        return FALSE;
    }

    private function getBalanceVariantQuantity($variant_id, $warehouse_id = NULL) {
        $this->db->select('SUM(COALESCE(quantity_balance, 0)) as stock', False);
        $this->db->where('option_id', $variant_id)->where('quantity_balance !=', 0);
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            $data = $q->row();
            return $data->stock;
        }
        return 0;
    }

    /*************/
	public function calculateAVCost($product_id, $warehouse_id, $net_unit_price, $unit_price, $quantity, $product_name, $option_id, $item_quantity) {
        $real_item_qty = $quantity;
        if ($pis = $this->getPurchasedItems($product_id, $warehouse_id, $option_id)) {
			//$this->erp->print_arrays($pis);
            $cost_row = array();
            $quantity = $item_quantity;
            $balance_qty = $quantity;
            $total_net_unit_cost = 0;
            $total_unit_cost = 0;
            foreach ($pis as $pi) {
				if($pi->quantity==0){
					$qty = 1;
				}
                $total_net_unit_cost += $pi->net_unit_cost;
                $total_unit_cost += ($pi->unit_cost ? $pi->unit_cost : $pi->net_unit_cost + ($pi->item_tax / $qty));
            }
            $as = sizeof($pis);
            $avg_net_unit_cost = $total_net_unit_cost / $as;
            $avg_unit_cost = $total_unit_cost / $as;
            $cost = array();

            foreach ($pis as $pi) {
                if (!empty($pi) && $pi->quantity > 0 && (($balance_qty <= $quantity && $quantity > 0) || $quantity < 0 ) ) {
                    if ( ($pi->quantity_balance >= $quantity && $quantity > 0 ) || $quantity < 0 ) {
                        $q = $this->db->query("SELECT qty_unit FROM erp_product_variants WHERE id = ? ", array($option_id));
                        $balance_qty = $pi->quantity_balance - $quantity;
                        if ($q->num_rows() > 0)
                        {
                            $unit_qty = '';
                            foreach ($q->result() as $val) {
                                $unit_qty = $val->qty_unit;
                            }
                            $balance_qty = $pi->quantity_balance - ( $quantity * ($unit_qty != 1 ? $unit_qty : 1) ) ;
                        }
                        $cost_row = array('date' => date('Y-m-d'), 'product_id' => $product_id, 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => $pi->id, 'quantity' => $real_item_qty, 'purchase_net_unit_cost' => $avg_net_unit_cost, 'purchase_unit_cost' => $avg_unit_cost, 'sale_net_unit_price' => $net_unit_price, 'sale_unit_price' => $unit_price, 'quantity_balance' => $balance_qty, 'inventory' => 1, 'option_id' => $option_id);

                        $quantity = 0;
                    } elseif ($quantity > 0) {
                        $quantity = $quantity - $pi->quantity_balance;
                        $balance_qty = $quantity;
                        $cost_row = array('date' => date('Y-m-d'), 'product_id' => $product_id, 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => $pi->id, 'quantity' => $pi->quantity_balance, 'purchase_net_unit_cost' => $avg_net_unit_cost, 'purchase_unit_cost' => $avg_unit_cost, 'sale_net_unit_price' => $net_unit_price, 'sale_unit_price' => $unit_price, 'quantity_balance' => 0, 'inventory' => 1, 'option_id' => $option_id);
                    }
                }
                if (empty($cost_row)) {
                    break;
                }
                $cost[] = $cost_row;
                if ($quantity == 0) {
                    break;
                }
            }
        }
        if ($quantity > 0 && !$this->Settings->overselling) {
            $this->session->set_flashdata('error', sprintf(lang("quantity_out_of_stock_for_%s"), ($pi->product_name ? $pi->product_name : $product_name)));
            redirect($_SERVER["HTTP_REFERER"]);
        } elseif ($quantity > 0) {
            $cost[] = array('date' => date('Y-m-d'), 'product_id' => $product_id, 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => NULL, 'quantity' => $real_item_qty, 'purchase_net_unit_cost' => NULL, 'purchase_unit_cost' => NULL, 'sale_net_unit_price' => $net_unit_price, 'sale_unit_price' => $unit_price, 'quantity_balance' => NULL, 'overselling' => 1, 'inventory' => 1);
            $cost[] = array('pi_overselling' => 1, 'product_id' => $product_id, 'quantity_balance' => (0 - $quantity), 'warehouse_id' => $warehouse_id, 'option_id' => $option_id);
        }
        return $cost;
    }
	
	public function calculateAVCosts($product_id, $warehouse_id, $net_unit_price, $unit_price, $quantity, $product_name, $option_id, $item_quantity, $shipping) {
        $real_item_qty = $quantity;
		$average_cost = 0;
        if ($pis = $this->getPurchasedItems($product_id, $warehouse_id, $option_id)) {
            $cost_row = array();
            $quantity = $item_quantity;
            $balance_qty = $quantity;
            $total_net_unit_cost = 0;
            $total_unit_cost = 0;
			$total_unit_costs = 0;
			$total_shipping = 0;

            foreach ($pis as $pi) {
				
				$oldcost = $this->getoldcost($product_id);
				$getoldcost = $oldcost->cost;
				$old_qty = $oldcost->quantity;

				if($getoldcost == 0 || $getoldcost == ''){
					if ($pi->item_discount || $shipping) {
						$percentage = '%';
						$purchase_discount = $pi->discount;
						$opos = strpos($purchase_discount, $percentage);
						if ($opos !== false) {
							$ods = explode("%", $purchase_discount);
							//$total_new_cost = ($unit_price * $quantity)-(($unit_price * $quantity)*($pi->discount/100));
							$total_new_cost = (($unit_price * $quantity) * (Float)($ods[0])) / 100;
						} else {
							$total_new_cost = (($unit_price * $quantity)) - $pi->item_discount;
						}
						$average_cost = ($total_new_cost/$quantity);
					} else {
						$average_cost = $unit_price;
					}
				}else{
					$total_old_cost = $old_qty * $getoldcost;
					$total_new_cost = ($unit_price * $quantity);
					
					if ($pi->item_discount) {
						$percentage = '%';
						$purchase_discount = $pi->discount;
						$opos = strpos($purchase_discount, $percentage);
						if ($opos !== false) {
							$ods = explode("%", $purchase_discount);
							//$total_new_cost = ($unit_price * $quantity)-(($unit_price * $quantity)*($pi->discount/100));
							$total_new_cost = (($unit_price * $quantity) * (Float)($ods[0])) / 100;
						} else {
							$total_new_cost = ($unit_price * $quantity) - $pi->item_discount;
						}
					}
					
					$total_qty = $quantity + $old_qty;
					$total_cost = $total_new_cost + $total_old_cost;
					
					$average_cost = ($total_cost/$total_qty);
				}
			}
		}
        return $average_cost;
    }
	
	public function getoldcost($product_id){
		$this->db->select('cost, quantity');
        $q = $this->db->get_where('products', array('id'=>$product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function calculateAverageCostShipping($product_id, $warehouse_id, $net_unit_cost, $quantity,$option_id, $shipping, $subtotal, $t_po_item_amount){	
		$costunit = 0;
		//if ($pis = $this->getPurchasedItems($product_id, $warehouse_id, $option_id)) {
		$freight_net = $shipping;
		$unit_cost = $net_unit_cost;
		$total_cost_line = $subtotal;
		$qty_new_receive = $quantity;
		
		$f_percents = ($total_cost_line / $t_po_item_amount) * 100;
		
		$f_atm = $freight_net * ($f_percents / 100);
		
		$f_cost = $f_atm / $qty_new_receive;
		
		$f_total_cost = $total_cost_line + $f_atm;
		
		$average_cost = $f_total_cost/$qty_new_receive;
		
		if ($pis = $this->getPurchasedItems($product_id, $warehouse_id, $option_id)) {

			$oldcost = $this->getoldcost($product_id);
			$old_cost = $oldcost->cost;
			$old_qty = $oldcost->quantity;

			if($option_id){
				$option = $this->getProductVariantByOptionID($option_id);
				$new_cost = ($unit_cost + $f_cost) / $option->qty_unit;
			} else {
				$new_cost = ($unit_cost + $f_cost);
			}

			$new_qty = $qty_new_receive;
			$total_old_cost = $old_qty * $old_cost;
			$total_new_cost = $new_cost * $new_qty;
			$total_qty = $new_qty + $old_qty;
			$total_cost = $total_new_cost + $total_old_cost;
			if($old_cost == 0 && $old_qty > 0 || $old_cost == ''){
				$average_cost = $total_new_cost/$total_qty;
			}else{
				$average_cost = $total_cost/$total_qty;
			}
		}
		return $average_cost;
	}
	
	public function updateQualityPro($SQLdata, $id){
		$this->db->where('code',$id);
		$this->db->update('products',$SQLdata);
		return $this->db->affected_rows();
	}
	
	public function updateCostPro($SQLdata, $id){
		$this->db->where_in('id',$id);
		$this->db->update('products',$SQLdata);
		return $this->db->affected_rows();
	}
	
	public function calculateCONAVCost($convert_id, $qty_to, $qty_from) {
		$QFrom = '';
		$QfromCost = '';
		$QTo ='';
		$QtoCost = '';
		$average_cost = '';
		$get_cost = '';
        if ($pis = $this->getConvertItemsById($convert_id)) {
			//$this->erp->print_arrays($pis);
            foreach ($pis as $pi) {
				if($pi->status == 'deduct'){
					$QFrom = $pi->c_quantity;
					$QfromCost = $pi->pcost;
				}else{
					if($pi->c_quantity > 1 and $pi->pcost >1){
						//NewCostTO = dbFromQtyCost * FromQtyInput / QtyToInput
						//dbOldQtyTO * dboldCostTO + NewQtyTOInput * NewCostTO / (dboldQtyTO + NewQtyTOInput)
						
						$old_cost = ($pi->c_quantity * $pi->pcost);
						
						$new_cost = $qty_to * (($QfromCost * $qty_from)/$qty_to);
						
						$qty_test = $pi->c_quantity + $qty_to;
						$average_cost = ($old_cost + $new_cost)/	$qty_test;
					}else{
						//check again
						$average_cost = ($qty_from * $QfromCost) / $qty_to;
					}
				}
			}
		}
        return $average_cost;
    }

	public function getConvertItemsById($convert_id){
		$this->db->select('convert_items.status,convert_items.convert_id,products.quantity AS c_quantity ,products.cost AS pcost');
		$this->db->join('products', 'products.id = convert_items.product_id', 'INNER');
		$this->db->where(array('convert_items.convert_id'=> $convert_id));
		$query = $this->db->get('convert_items');
		
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	}
	
	public function calculateCosts($unit_price, $item_quantity, $shipping){
		$new_unit_cost = ($unit_price*$item_quantity)+$shipping;
		$final_cost    = $new_unit_cost / $item_quantity;
		return $final_cost;
	}
	public function calculateCost($unit_price, $item_quantity, $shipping){
		$new_unit_cost = ($unit_price*$item_quantity);
		$final_cost    = $new_unit_cost / $item_quantity;
		return $final_cost;
	}
	/*
    public function getPurchasedItems($product_id, $warehouse_id, $option_id = NULL) {
		$orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->select('id, quantity, quantity_balance, net_unit_cost, unit_cost, item_tax, purchase_id, real_unit_cost');
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
	*/
	
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
	
	public function getShippingItems($id) {
        $this->db->select('shipping');
        $this->db->where('id', $id);
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductComboItems($pid, $warehouse_id = NULL)
    {
        $this->db->select('products.id as id, combo_items.item_code as code, combo_items.quantity as qty, products.name as name, products.type as type, combo_items.unit_price as unit_price, warehouses_products.quantity as quantity')
            ->join('products', 'products.code=combo_items.item_code', 'left')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('combo_items.id');
        if($warehouse_id) {
            $this->db->where('warehouses_products.warehouse_id', $warehouse_id);
        }
        $q = $this->db->get_where('combo_items', array('combo_items.product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }

    public function item_costing($item, $pi = NULL) {
        $item_quantity = $pi ? $item['aquantity'] : $item['quantity'];
        if (!isset($item['option_id']) || $item['option_id'] == 'null') {
            $item['option_id'] = NULL;
        }

        if ($this->Settings->accounting_method != 2 && !$this->Settings->overselling) {

            if ($this->site->getProductByID($item['product_id'])) {
                if ($item['product_type'] == 'standard') {
                    $cost = $this->site->calculateCost($item['product_id'], $item['warehouse_id'], $item['net_unit_price'], $item['unit_price'], $item['quantity'], $item['product_name'], $item['option_id'], $item_quantity);
                } elseif ($item['product_type'] == 'combo') {
                    $combo_items = $this->getProductComboItems($item['product_id'], $item['warehouse_id']);
                    foreach ($combo_items as $combo_item) {
                        $pr = $this->getProductByCode($combo_item->code);
                        if ($pr->tax_rate) {
                            $pr_tax = $this->site->getTaxRateByID($pr->tax_rate);
                            if ($pr->tax_method) {
                                $item_tax = $this->erp->formatDecimal((($combo_item->unit_price) * $pr_tax->rate) / (100 + $pr_tax->rate));
                                $net_unit_price = $combo_item->unit_price - $item_tax;
                                $unit_price = $combo_item->unit_price;
                            } else {
                                $item_tax = $this->erp->formatDecimal((($combo_item->unit_price) * $pr_tax->rate) / 100);
                                $net_unit_price = $combo_item->unit_price;
                                $unit_price = $combo_item->unit_price + $item_tax;
                            }
                        } else {
                            $net_unit_price = $combo_item->unit_price;
                            $unit_price = $combo_item->unit_price;
                        }
                        if ($pr->type == 'standard') {
                            $cost = $this->site->calculateCost($pr->id, $item['warehouse_id'], $net_unit_price, $unit_price, ($combo_item->qty * $item['quantity']), $pr->name, NULL, $item_quantity);
                        } else {
                            $cost = array(array('date' => date('Y-m-d'), 'product_id' => $pr->id, 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => NULL, 'quantity' => ($combo_item->qty * $item['quantity']), 'purchase_net_unit_cost' => 0, 'purchase_unit_cost' => 0, 'sale_net_unit_price' => $combo_item->unit_price, 'sale_unit_price' => $combo_item->unit_price, 'quantity_balance' => NULL, 'inventory' => NULL));
                        }
                    }
                } else {
                    $cost = array(array('date' => date('Y-m-d'), 'product_id' => $item['product_id'], 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => NULL, 'quantity' => $item['quantity'], 'purchase_net_unit_cost' => 0, 'purchase_unit_cost' => 0, 'sale_net_unit_price' => $item['net_unit_price'], 'sale_unit_price' => $item['unit_price'], 'quantity_balance' => NULL, 'inventory' => NULL));
                }
            } elseif ($item['product_type'] == 'manual') {
                $cost = array(array('date' => date('Y-m-d'), 'product_id' => $item['product_id'], 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => NULL, 'quantity' => $item['quantity'], 'purchase_net_unit_cost' => 0, 'purchase_unit_cost' => 0, 'sale_net_unit_price' => $item['net_unit_price'], 'sale_unit_price' => $item['unit_price'], 'quantity_balance' => NULL, 'inventory' => NULL));
            }

        } else {

            if ($this->site->getProductByID($item['product_id'])) {
                if ($item['product_type'] == 'standard') {
                    $cost = $this->site->calculateAVCost($item['product_id'], $item['warehouse_id'], $item['net_unit_price'], $item['unit_price'], $item['quantity'], $item['product_name'], $item['option_id'], $item_quantity);
                } elseif ($item['product_type'] == 'combo') {
                    $combo_items = $this->getProductComboItems($item['product_id'], $item['warehouse_id']);
                    foreach ($combo_items as $combo_item) {
                        $cost = $this->site->calculateAVCost($combo_item->id, $item['warehouse_id'], ($combo_item->qty * $item['quantity']), $item['unit_price'], $item['quantity'], $item['product_name'], $item['option_id'], $item_quantity);
                    }
                } else {
                    $cost = array(array('date' => date('Y-m-d'), 'product_id' => $item['product_id'], 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => NULL, 'quantity' => $item['quantity'], 'purchase_net_unit_cost' => 0, 'purchase_unit_cost' => 0, 'sale_net_unit_price' => $item['net_unit_price'], 'sale_unit_price' => $item['unit_price'], 'quantity_balance' => NULL, 'inventory' => NULL));
                }
            } elseif ($item['product_type'] == 'manual') {
                $cost = array(array('date' => date('Y-m-d'), 'product_id' => $item['product_id'], 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => NULL, 'quantity' => $item['quantity'], 'purchase_net_unit_cost' => 0, 'purchase_unit_cost' => 0, 'sale_net_unit_price' => $item['net_unit_price'], 'sale_unit_price' => $item['unit_price'], 'quantity_balance' => NULL, 'inventory' => NULL));
            }

        }
        return $cost;
    }

    public function costing($items) {
        $citems = array();
        foreach ($items as $item) {
            $pr = $this->getProductByID($item['product_id']);
            if ($pr->type == 'standard') {
                if (isset($citems['p' . $item['product_id'] . 'o' . $item['option_id']])) {
                    $citems['p' . $item['product_id'] . 'o' . $item['option_id']]['aquantity'] += $item['quantity'];
                } else {
                    $citems['p' . $item['product_id'] . 'o' . $item['option_id']] = $item;
                    $citems['p' . $item['product_id'] . 'o' . $item['option_id']]['aquantity'] = $item['quantity'];
                }
            } elseif ($pr->type == 'combo') {
                $combo_items = $this->getProductComboItems($item['product_id'], $item['warehouse_id']);
                foreach ($combo_items as $combo_item) {
                    if ($combo_item->type == 'standard') {
                        if (isset($citems['p' . $combo_item->id . 'o' . $item['option_id']])) {
                            $citems['p' . $combo_item->id . 'o' . $item['option_id']]['aquantity'] += ($combo_item->qty*$item['quantity']);
                        } else {
                            $cpr = $this->getProductByID($combo_item->id);
                            if ($cpr->tax_rate) {
                                $cpr_tax = $this->site->getTaxRateByID($cpr->tax_rate);
                                if ($cpr->tax_method) {
                                    $item_tax = $this->erp->formatDecimal((($combo_item->unit_price) * $cpr_tax->rate) / (100 + $cpr_tax->rate));
                                    $net_unit_price = $combo_item->unit_price - $item_tax;
                                    $unit_price = $combo_item->unit_price;
                                } else {
                                    $item_tax = $this->erp->formatDecimal((($combo_item->unit_price) * $cpr_tax->rate) / 100);
                                    $net_unit_price = $combo_item->unit_price;
                                    $unit_price = $combo_item->unit_price + $item_tax;
                                }
                            } else {
                                $net_unit_price = $combo_item->unit_price;
                                $unit_price = $combo_item->unit_price;
                            }
                            $cproduct = array('product_id' => $combo_item->id, 'product_name' => $cpr->name, 'product_type' => $combo_item->type, 'quantity' => ($combo_item->qty*$item['quantity']), 'net_unit_price' => $net_unit_price, 'unit_price' => $unit_price, 'warehouse_id' => $item['warehouse_id'], 'item_tax' => $item_tax, 'tax_rate_id' => $cpr->tax_rate, 'tax' => ($cpr_tax->type == 1 ? $cpr_tax->rate.'%' : $cpr_tax->rate), 'option_id' => NULL);
                            $citems['p' . $combo_item->id . 'o' . $item['option_id']] = $cproduct;
                            $citems['p' . $combo_item->id . 'o' . $item['option_id']]['aquantity'] = ($combo_item->qty*$item['quantity']);
                        }
                    }
                }
            }
        }
        // $this->erp->print_arrays($combo_items, $citems);
        $cost = array();
        foreach ($citems as $item) {
            $item['aquantity'] = $citems['p' . $item['product_id'] . 'o' . $item['option_id']]['aquantity'];
            $cost[] = $this->item_costing($item, TRUE);
        }
        return $cost;
    }

    public function syncQuantity($sale_id = NULL, $purchase_id = NULL, $oitems = NULL, $product_id = NULL) {
        if ($sale_id) {
            $sale_items = $this->getAllSaleItems($sale_id);
            foreach ($sale_items as $item) {
                if ($item->product_type == 'standard') {
                    $this->syncProductQty($item->product_id, $item->warehouse_id);
                    if (isset($item->option_id) && !empty($item->option_id)) {
                        $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                    }
                } elseif ($item->product_type == 'combo') {
                    $combo_items = $this->getProductComboItems($item->product_id, $item->warehouse_id);
                    foreach ($combo_items as $combo_item) {
                        if($combo_item->type == 'standard') {
                            $this->syncProductQty($combo_item->id, $item->warehouse_id);
                        }
                    }
                }
            }
        } elseif ($purchase_id) {
            $purchase_items = $this->getAllPurchaseItems($purchase_id);
         
			$var_option = 0;
            foreach ($purchase_items as $item) {
				
				if($item->option_id != 0) {
					$var_option = $item->option_id;
				}
                $type = $this->getProductType($item->product_id);
                if($type != 'service'){
                    $this->syncProductQty($item->product_id, $item->warehouse_id);
                    if (isset($item->option_id) && !empty($item->option_id)) {
                        $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                    }
                }
            }

        } elseif ($oitems) {

            foreach ($oitems as $item) {
                if (isset($item->product_type)) {
                    if ($item->product_type == 'standard') {
                        $this->syncProductQty($item->product_id, $item->warehouse_id);
                        if (isset($item->option_id) && !empty($item->option_id)) {
                            $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                        }
                    } elseif ($item->product_type == 'combo') {
                        $combo_items = $this->getProductComboItems($item->product_id, $item->warehouse_id);
                        foreach ($combo_items as $combo_item) {
                            if($combo_item->type == 'standard') {
                                $this->syncProductQty($combo_item->id, $item->warehouse_id);
                            }
                        }
                    }
                } else {
                    $this->syncProductQty($item->product_id, $item->warehouse_id);
                    if (isset($item->option_id) && !empty($item->option_id)) {
                        $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                    }
                }
            }

        } elseif ($product_id) {
            $warehouses = $this->getAllWarehouses();
            foreach ($warehouses as $warehouse) {
                $type = $this->getProductType($product_id);
                if($type != 'service'){
                    $this->syncProductQty($product_id, $warehouse->id);
                    if ($product_variants = $this->getProductVariants($product_id)) {
                        foreach ($product_variants as $pv) {
                            $this->syncVariantQty($pv->id, $warehouse->id, $product_id);
                        }
                    }
                }
            }
        }
    }

    public function getProductVariants($product_id)
    {
        $q = $this->db->get_where('product_variants', array('product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getProductVariantOptionIDPID($option_id, $product_id)
    {
        $q = $this->db->get_where('product_variants', array('id' => $option_id, 'product_id' => $product_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllSaleItems($sale_id) {
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllPurchaseItems($purchase_id) {
        $q = $this->db->get_where('purchase_items', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function syncPurchaseItems($data = array()) {
        if (!empty($data)) {
            foreach ($data as $items) {
                foreach ($items as $item) {
                    if (isset($item['pi_overselling'])) {
                        unset($item['pi_overselling']);
                        $option_id = (isset($item['option_id']) && !empty($item['option_id'])) ? $item['option_id'] : NULL;
						
                        $clause = array('purchase_id' => NULL, 'transfer_id' => NULL, 'product_id' => $item['product_id'], 'warehouse_id' => $item['warehouse_id'], 'option_id' => $option_id);
                        if ($pi = $this->getPurchasedItem($clause)) {
							if($option_id){
								$option = $this->getProductVariantOptionIDPID($option_id, $item['product_id']);
								$quantity_balance = $pi->quantity_balance + $item['quantity_balance'] * $option->qty_unit;
							}else{
								$quantity_balance = $pi->quantity_balance + $item['quantity_balance'];
							}
                            $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), $clause);
                        } else {
                            $clause['quantity'] = 0;
                            $clause['item_tax'] = 0;
							
							if($option_id){
								$option = $this->getProductVariantOptionIDPID($option_id, $item['product_id']);
								$clause['quantity_balance'] = $item['quantity_balance'] * $option->qty_unit;
							}else{
								$clause['quantity_balance'] = $item['quantity_balance'];
							}
                            
                            $this->db->insert('purchase_items', $clause);
                        }
                    } else {
                        if ($item['inventory']) {
                            if($option_id){
								$option = $this->getProductVariantOptionIDPID($option_id, $item['product_id']);
								$item['quantity_balance'] = $item['quantity_balance'] * $option->qty_unit;
							}
                            $this->db->update('purchase_items', array('quantity_balance' => $item['quantity_balance']), array('id' => $item['purchase_item_id']));
                        }
                    }
                }
            }
            return TRUE;
        }
        return FALSE;
    }
	
	public function getMakeupCostByCompanyID($customer_id){
		$this->db->select('percent, makeup_cost')
						->join('customer_groups', 'customer_groups.id = companies.customer_group_id')
						->where('companies.id', $customer_id);
		$q = $this->db->get('companies');
		if($q->num_rows() > 0){
			return $q->row();
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
	
	public function getPaymentBySaleID($sale_id)
    {
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getPaymentByPurchaseID($purchase_id)
    {
        $q = $this->db->get_where('payments', array('purchase_id' => $purchase_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getAllBom($id)
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        $q = $this->db->get('bom');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getBom_itemsTop($id)
    {
        $this->db->select('*');
        $this->db->where(array('bom_id'=> $id, 'status'=> 'deduct'));
        $q = $this->db->get('bom_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

	public function getBom_itemsBottom($id)
    {
        $this->db->select('*');
        $this->db->where(array('bom_id'=> $id, 'status'=> 'add'));
        $q = $this->db->get('bom_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function default_biller_id() {
        $this->db->select('default_biller');
        $q = $this->db->get('settings');
        if($q->num_rows() > 0){
            $q = $q->row();
            return $q->default_biller;
        }
        return false;
    }
	
	public function suspend_room(){
		$q = $this->db->get_where('suspended');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	public function month($month, $id){
		$start = '';
		$end   = '';
		if($month == 01){
			$date = date('Y');
			$dates = $date - 1;
			$years = $dates.'-'.$month.'-23';	
			$y = new DateTime( $years ); 
			$end  = $y->format( 'Y-m-t' );	
			$start = $dates.'-'.$month.'-01';	
		}elseif($month == '0-1'){
			$date = date('Y');
			$years = $date.'-01-23';	
			$y = new DateTime( $years ); 
			$end  = $y->format( 'Y-m-t' );	
			$start = $date.'-01-01';	
		}else{
			$date = date('Y');
			$years = $date.'-'.$month.'-23';	
			$y = new DateTime( $years ); 
			$end  = $y->format( 'Y-m-t' );	
			$start = $date.'-'.$month.'-01';	
		}
		
		$this->db->select('date')
					  ->from('purchase_items')
					  ->where('date >= "'.$start.'" and date <= "'.$end.'" and product_code = '.$id.' ')
					  ->order_by('date', 'desc')
					  ->limit(1);
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
           $result = $q->row();
		   return $result->date;
        }
        return FALSE;	
	}
	
	public function months($year,$month){
		$start = '';
		$end   = '';
		if($month == 01){
			$dates = $year - 1;
			$years = $dates.'-12-23';	
			$y = new DateTime( $years ); 
			$end  = $y->format( 'Y-m-t' );	
			$start = $dates.'-12-01';	
		}else{
			$months = $month - 1;
			$years = $year.'-'.$months.'-23';	
			$y = new DateTime( $years ); 
			$end  = $y->format( 'Y-m-t' );	
			$start = $date.'-'.$months.'-01';	
		}
		
		$this->db->select('date')
					  ->from('purchase_items')
					  ->where('date >= "'.$start.'" and date <= "'.$end.'" ')
					  ->order_by('date', 'desc')
					  ->limit(1);
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;	
	}
	
	public function getCurrency(){
		$this->db->select()
				 ->from('currencies')
				 ->order_by('id', 'ASC');
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	public function getAllTaxes(){
		$q = $this->db->query("SELECT * FROM erp_tax_rates ");
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	/* New Function */
	public function getAllBaseUnits()
    {
        $q = $this->db->get_where("units", array('base_unit' => NULL));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getUnitsByBUID($base_unit)
    {
        $this->db->where('id', $base_unit)->or_where('base_unit', $base_unit);
        $q = $this->db->get("units");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getUnitByID($id)
    {
        $q = $this->db->get_where("units", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPriceGroupByID($id)
    {
        $q = $this->db->get_where('price_groups', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductGroupPrice($product_id, $group_id)
    {
        $q = $this->db->get_where('product_prices', array('price_group_id' => $group_id, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllBrands()
    {
        $q = $this->db->get("brands");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getBrandByID($id)
    {
        $q = $this->db->get_where('brands', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getCompaniesByID($id)
    {
        $q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getAllDownPercentage()
	{
		$q = $this->db->get('down_persentages');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getAllInterestRate()
	{
		$q = $this->db->get('interest_rate');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	
	public function getAllTerm()
	{
		$q = $this->db->get('terms');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	
	public function getAllCustomerGroup()
	{
		$q = $this->db->get('customer_groups');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	
	public function getVariants()
	{
		$q = $this->db->get('variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	
	public function getCountries()
	{
		$q = $this->db->get_where('addresses', array('type' => 'country'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	
	public function getcitiesByCountyID($code = NULL)
	{
		$this->db->select('code as id, name as text');
		if($code) {
			$q = $this->db->get_where("addresses", array('parent_code' => $code, 'type' => 'city'));
		}else{
			$q = $this->db->get_where("addresses", array('type' => 'city'));
		}
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getProvincesByCountyID($code = NULL)
    {
        $this->db->select('code as id, name as text');
		if($code) {
			$q = $this->db->get_where("addresses", array('parent_code' => $code));
		}else{
			$q = $this->db->get_where("addresses", array('type' => 'province'));
		}
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getDistrictByProvinceID($code = NULL)
    {
        $this->db->select('code as id, name as text');
		if($code) {
			$q = $this->db->get_where("addresses", array('parent_code' => $code));
		}else{
			$q = $this->db->get("addresses", array('type' => 'district'));
		}
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getCommuncesByDistrictID($code = NULL)
    {
        $this->db->select('code as id, name as text');
		if($code) {
			$q = $this->db->get_where("addresses", array('parent_code' => $code));
		}else{
			$q = $this->db->get("addresses", array('type' => 'communce'));
		}
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getVillagesByCommunceID($code = NULL)
	{
		$this->db->select('code as id, name as text');
		if($code) {
			$q = $this->db->get_where("addresses", array('parent_code' => $code));
		}else{
			$q = $this->db->get("addresses", array('type' => 'village'));
		}
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function updatePhoneCollectionStatus($status, $id){
		$this->db->where_in('id',$id);
		$u=$this->db->update('sales',$status);
		if($u){
			return true;
		}
		return false;
		
	}
	
	public function getPlace($code = NULL) {
		$this->db->select('code, name, description');
		$q = $this->db->get_where('addresses', array('code' => $code));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	public function getAddress($ccode = NULL, $pcode = NULL, $dcode = NULL, $cmcode = NULL, $vcode = NULL, $type = 'EN') {
		$address_kh = array();
		$address_en = array();
		if($ccode) {
			if($country = $this->getPlace($ccode)) {
				$address_kh['country'] = $country->description;
				$address_en['country'] = $country->name;
			}
		}
		if($pcode) {
			if($province = $this->getPlace($pcode)) {
				$address_kh['province'] = $province->description;
				$address_en['province'] = $province->name;
			}
		}
		if($dcode) {
			if($district = $this->getPlace($dcode)) {
				$address_kh['district'] = $district->description;
				$address_en['district'] = $district->name;
			}
		}
		if($cmcode) {
			if($communce = $this->getPlace($cmcode)) {
				$address_kh['communce'] = $communce->description;
				$address_en['communce'] = $communce->name;
			}
		}
		if($vcode) {
			if($village = $this->getPlace($vcode)) {
				$address_kh['village'] = $village->description;
				$address_en['village'] = $village->name;
			}
		}
		if($type == 'EN') {
			return $address_en;
		}else if($type == 'KH') {
			return $address_kh;
		}else{
			return false;
		}
	}
	public function getAddressToString($ccode = NULL, $pcode = NULL, $dcode = NULL, $cmcode = NULL, $vcode = NULL, $type = 'EN') {
		$address_kh = '';
		$address_en = '';
		if($vcode) {
			if($village = $this->getPlace($vcode)) {
				$address_kh = $village->description;
				$address_en = $village->name;
			}
		}
		if($cmcode) {
			if($communce = $this->getPlace($cmcode)) {
				$address_kh .= $address_kh? ', '.$communce->description : $communce->description;
				$address_en .= $address_en? ', '.$communce->name : $communce->name;
			}
		}
		if($pcode) {
			if($province = $this->getPlace($pcode)) {
				$address_kh .= $address_kh? ', '.$province->description : $province->description;
				$address_en .= $address_en? ', '.$province->name : $province->name;
			}
		}
		if($ccode) {
			if($country = $this->getPlace($ccode)) {
				$address_kh .= $address_kh? ', '.$country->description : $country->description;
				$address_en .= $address_en? ', '.$country->name : $country->name;
			}
		}
		if($type == 'EN') {
			return $address_en;
		}else if($type == 'KH') {
			return $address_kh;
		}else{
			return false;
		}
	}
	function getBranchByUserID($id){
		$this->db->select('branch_id');
		$this->db->where('id',$id);
		$q=$this->db->get('users');
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getSendDate() {
		$q = $this->db->get_where('sms', 'send_date <> '. null);
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getBranchByUserIdForHead($id){
		$this->db->select('companies.name');
		$this->db->where('users.id',$id);
		$this->db->join('companies', 'companies.id = users.branch_id');
		$this->db->from('users');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getCustomersSMSByID($id = NULL) {
		$this->db->select('companies.phone, companies.phone1, companies.phone2, companies.spouse_phone, sms.message');
		$this->db->where('sms.id', $id);
		$this->db->where('sms.customer_type <> ', '');
		$this->db->join('quotes', 'companies.id = quotes.customer_id', 'INNER');
		$this->db->join('sms', 'sms.customer_type = quotes.status', 'INNER');
		$this->db->from('companies');
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getDealerSMSByID($id = NULL) {
		$this->db->select('companies.phone, companies.phone1, companies.phone2, companies.spouse_phone, sms.message');
		$this->db->where('sms.id', $id);
		$this->db->where('sms.dealer_id <> ', '');
		$this->db->join('sms', 'sms.dealer_id = companies.id', 'INNER');
		$this->db->from('companies');
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getAllTotalLoanBySaleID($id = NULL) {
		$this->db->select('SUM(payment) as tpayment, SUM(principle) as tprinciple, SUM(interest) as tinterest, SUM(paid_amount) as tpaid, total_service_charge as total_service');
		$q = $this->db->get_where('loans', array('sale_id' => $id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	public function getSysSetting() {
		$q = $this->db->get('settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getAllBranches() {
        $q = $this->db->get_where('companies', array('group_name' => 'biller'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAllTerms() {
        $q = $this->db->get('terms');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAllCustomers(){
		$this->db->select("id, name, family_name");
		$this->db->where('group_name', 'customer');
		$q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getCustomerIDByID($code) {
		$q = $this->db->select('id')
					->get_where('companies', array('code' => $code),1);
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	public function getIdentifyByGVID($gv_id) {
		$q = $this->db->select('id,gov_id')
					->get_where('companies', array('gov_id' => $gv_id),1);
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	public function getCompanyID($id) {
		$this->db->select("id, CONCAT(name,' ',family_name) As cutomerNmae");
		$q = $this->db->get_where('companies_', array('id' => $id),1);
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getIdentifyID($identify_name){
			$q = $this->db->select('id')
						->get_where('identify_types', array('name' => $identify_name),1);
			if($q->num_rows() > 0) {
				return $q->row();
			}
			return false;
	}
	
	public function getProductIDByCode($code){
		$q = $this->db->select('id')
					->get_where('products', array('code' => $code), 1);
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	public function getBranchByCode($code){
		$q = $this->db->select('id')
					->get_where('companies', array('branch_code' => $code), 1);
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	public function getLoanIDByReference($ref_no){
		$this->db->select('sales.id,sales.customer_id, sales.total, sales.branch_id, sales.term, sales.frequency, sale_items.currency_code');
		$this->db->join('sale_items','sale_items.sale_id = sales.id','left');
		$this->db->where(array('reference_no' => $ref_no), 1);
		$q = $this->db->get('sales');
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	public function getServices($sale_id){
		$q = $this->db->get_where('sale_services',array('sale_id' => $sale_id));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
		return FALSE;
	}
	
	public function getSaleByQouteID($qid){
		$q = $this->db->select('id,total,grand_total,branch_id,created_by,quote_id')
					->get_where('sales', array('quote_id' => $qid), 1);
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	public function getSaleItemsbyID($sid){
		$q = $this->db->get_where('sale_items', array('sale_id' => $sid), 1);
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	public function getContract(){
		$this->db->where('opening_ar',1);
		$q = $this->db->get('sales');
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
		return FALSE;
	}
	public function getQuoteIDByReference($ref_no){
		$q = $this->db->select('id')
					->get_where('quotes', array('reference_no' => $ref_no), 1);
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	public function getServiceByServiceCode($code){
		$q = $this->db->get_where('services', array('code' => $code), 1);
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	public function getRejectByStatus($status=NULL) {
		$q = $this->db->get_where('reject_reason', array('status' => $status));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	/*public function getCustomer_ID_Name(){
		$q = $this->db->select('customer_id,customer')->get('sales');
		if($q->num_rows() > 0) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;	
	}*/	
	
	public function getCustomer_ID_Name($id=NULL){
		$this->db->select('sales.id,companies.name,companies.family_name,companies.family_name_other,companies.name_other');
		//$this->db->where('sms.customer_type <> ', '');
		$this->db->join('companies', 'sales.customer_id = companies.id', 'INNER');
		$this->db->from('sales');
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getByCO()
    {
        if ($this->Admin) {
            $this->db->where('group_id !=', 1);
        }
        $this->db->where('group_id !=', 3)->where('group_id !=', 4);
        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getByCO_Name()
    {
        if ($this->Admin) {
            $this->db->where('group_id !=', 1);
        }
        $this->db->where('group_id =', 22);
        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAllBranch_Name($b_id=NULL){
		$this->db->select("users.id,users.branch_id,companies.name", FALSE);
		$this->db->join('users','quotes.created_by = users.id','INNER');
		$this->db->join('companies','companies.id=users.branch_id','INNER');
		$this->db->from('quotes');
		$this->db->group_by('companies.name');
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function get_policy_payments() {
        $q = $this->db->get('policy_payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getNoneHoliday($dateline = NULL) {
		$q = $this->db->get_where('holidays', array('holiday_date' => $dateline));
		$n = 0;
		if($q->num_rows() > 0) {
			$data = $q->row();
			$new_dateline = date('Y-m-d', strtotime("+1 days", strtotime($dateline)));
			$dateline = $this->getNoneHoliday($new_dateline);
		}
		return $dateline;
	}

	public function getWeekendPayments($days =NULL, $dateline = NULL) {		
		if($days == "Saturday") {
			$datelines = date('Y-m-d', strtotime("+2 days", strtotime($dateline)));
		}else if($days == "Sunday") {
			$datelines = date('Y-m-d', strtotime("+1 days", strtotime($dateline)));
		}else{
			$datelines = $dateline;
		}
		return $datelines;
	}
	
	public function SkipSunday($days =NULL, $dateline = NULL) {
		if($days == "Sunday") {
			$datelines = date('Y-m-d', strtotime("+1 days", strtotime($dateline)));
		}else{
			$datelines = $dateline;
		}
		return $datelines;
	}
	
	public function getCurrncyByCode($code){
		$q = $this->db->get_where('currencies', array('code' => $code),1);
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	
	public function getPaymentBySale_ID($sale_id){
		$this->db->where(array('sale_id' => $sale_id));
		$this->db->where(array('type' => 'disburse'));
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAddPaymentBySale_ID($sale_id) {
		$this->db->where(array('sale_id' => $sale_id));
		$this->db->where(array('type' => 'disburse'));
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
}	

