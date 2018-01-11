<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getProductNames($term, $limit = 5)
    {
        $this->db->select('id, code, name')
            ->like('name', $term, 'both')->or_like('code', $term, 'both');
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStaff()
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

    public function getSalesTotals($customer_id)
    {
        $this->db->select('SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('customer_id', $customer_id);
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getCustomerSales($customer_id)
    {
        $this->db->from('sales')->where('customer_id', $customer_id);
        return $this->db->count_all_results();
    }

    public function getCustomerQuotes($customer_id)
    {
        $this->db->from('quotes')->where('customer_id', $customer_id);
        return $this->db->count_all_results();
    }

    public function getCustomerReturns($customer_id)
    {
        $this->db->from('return_sales')->where('customer_id', $customer_id);
        return $this->db->count_all_results();
    }
	
	public function getCustomerDeposits($company_id)
    {
        $this->db
                ->from('deposits')
                ->join('users', 'users.id=deposits.created_by', 'left')
				->where($this->db->dbprefix('deposits') . ".company_id", $company_id);
        return $this->db->count_all_results();
    }

    public function getStockValue()
    {
        $q = $this->db->query("SELECT SUM(by_price) as stock_by_price, SUM(by_cost) as stock_by_cost FROM ( Select COALESCE(sum(" . $this->db->dbprefix('warehouses_products') . ".quantity), 0)*price as by_price, COALESCE(sum(" . $this->db->dbprefix('warehouses_products') . ".quantity), 0)*cost as by_cost FROM " . $this->db->dbprefix('products') . " JOIN " . $this->db->dbprefix('warehouses_products') . " ON " . $this->db->dbprefix('warehouses_products') . ".product_id=" . $this->db->dbprefix('products') . ".id GROUP BY " . $this->db->dbprefix('products') . ".id )a");
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	 public function getWarehouseStockValue($id)
    {
        $q = $this->db->query("SELECT SUM(by_price) as stock_by_price, SUM(by_cost) as stock_by_cost FROM ( Select sum(COALESCE(" . $this->db->dbprefix('warehouses_products') . ".quantity, 0))*price as by_price, sum(COALESCE(" . $this->db->dbprefix('warehouses_products') . ".quantity, 0))*cost as by_cost FROM " . $this->db->dbprefix('products') . " JOIN " . $this->db->dbprefix('warehouses_products') . " ON " . $this->db->dbprefix('warehouses_products') . ".product_id=" . $this->db->dbprefix('products') . ".id WHERE " . $this->db->dbprefix('warehouses_products') . ".warehouse_id = ? GROUP BY " . $this->db->dbprefix('products') . ".id )a", array($id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	//chivorn chart stock
	
		
	public function getCategoryStockValue($biller= NULL,$customer= NULL,$start_date= NULL,$end_date= NULL)
    {
		if($biller != NULL){
			$where_biller = " AND erp_sales.biller_id=".$biller;
		}else{
			$where_biller = "";
		}
		if($customer != NULL){
			$where_customer = " AND erp_sales.customer_id=".$customer;
		}else{
			$where_customer = "";
		}
		if($start_date != NULL && $end_date != NULL){
			$where_between_date = " AND erp_sales.date between '$start_date' AND '$end_date'";
		}else{
			$where_between_date = "";
		}
		
		$q = $this->db->query("
			SELECT
				COALESCE (
					sum(
						erp_sale_items.subtotal
					),
					0
				) AS by_price,
				erp_categories.name AS category_name
			FROM
				erp_products
			JOIN erp_warehouses_products ON erp_warehouses_products.product_id = erp_products.id
			JOIN erp_categories ON erp_categories.id = erp_products.category_id
			JOIN erp_sale_items ON erp_sale_items.product_id = erp_products.id
			JOIN erp_sales ON erp_sales.id = erp_sale_items.sale_id WHERE 1=1 $where_biller $where_customer $where_between_date
			GROUP BY
				erp_categories.id");
        
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	public function getChartValue()
    {
		$q = $this->db->query("
			SELECT
				accountcode,
				accountname,
				COALESCE (
					sum(
						amount
					),
					0
				) AS total_amount
			FROM
				erp_gl_charts
			LEFT JOIN erp_gl_trans ON erp_gl_trans.account_code = erp_gl_charts.accountcode
			WHERE
				erp_gl_charts.bank = 1
			GROUP BY
				accountcode;");
        
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	public function getCategoryStockValueById($id, $biller= NULL, $customer= NULL, $start_date= NULL, $end_date= NULL)
    {
		if($biller != NULL){
			$where_biller = " AND erp_sales.biller_id=".$biller;
		}else{
			$where_biller = "";
		}
		if($customer != NULL){
			$where_customer = " AND erp_sales.customer_id=".$customer;
		}else{
			$where_customer = "";
		}
		
		if($start_date != NULL && $end_date != NULL){
			$where_between_date = " AND erp_sales.date between '$start_date' AND '$end_date'";
		}else{
			$where_between_date = "";
		}
		
        $q = $this->db->query("
			SELECT
				COALESCE (
					sum(
						erp_sale_items.subtotal
					),
					0
				) AS by_price,
				erp_categories.name AS category_name
			FROM
				erp_products
			JOIN erp_warehouses_products ON erp_warehouses_products.product_id = erp_products.id
			JOIN erp_categories ON erp_categories.id = erp_products.category_id
			JOIN erp_sale_items ON erp_sale_items.product_id = erp_products.id
			JOIN erp_sales ON erp_sales.id = erp_sale_items.sale_id
			WHERE erp_sale_items.warehouse_id = $id $where_biller $where_customer $where_between_date
			GROUP BY
				erp_categories.id");
        
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	public function getChartValueById($id)
    {
        $q = $this->db->query("
SELECT
				accountcode,
				accountname,
				COALESCE (
					sum(
						amount
					),
					0
				) AS total_amount
			FROM
				erp_gl_charts
			LEFT JOIN erp_gl_trans ON erp_gl_trans.account_code = erp_gl_charts.accountcode
			WHERE
				erp_gl_charts.bank = 1 and erp_gl_trans.account_code= $id
			GROUP BY
				accountcode;");
        
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	public function getChartDataProfit($biller_id = null, $year = null)
    {
		
	if($biller_id != null){
		$where_biller_id = "AND erp_gl_trans.biller_id = ".$biller_id;
	}else{
		$where_biller_id = "";
	}
	if($year != null){
		$where_year = "AND YEAR(erp_gl_trans.tran_date) = ".$year;
	}else{
		$where_year = "";
	}
        $myQuery = "SELECT
	I. MONTH,
	COALESCE (I.income, 0) AS income,
	COALESCE (C.cost, 0) AS cost,
	COALESCE (O.operation, 0) AS operation
FROM
	(
		SELECT
			date_format(tran_date, '%Y-%m') MONTH,
			erp_gl_trans.account_code,
			erp_gl_trans.sectionid,
			erp_gl_charts.accountname,
			erp_gl_charts.parent_acc,
			sum(erp_gl_trans.amount) AS income
		FROM
			erp_gl_trans
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		WHERE
			erp_gl_trans.tran_date >= date_sub(now(), INTERVAL 12 MONTH)
		AND erp_gl_trans.sectionid IN (40, 70) $where_biller_id $where_year
	
			GROUP BY date_format(tran_date, '%Y-%m'),
			erp_gl_trans.account_code
	) I
LEFT JOIN (
	SELECT
		date_format(tran_date, '%Y-%m') MONTH,
		erp_gl_trans.account_code,
		erp_gl_trans.sectionid,
		erp_gl_charts.accountname,
		erp_gl_charts.parent_acc,
		sum(erp_gl_trans.amount) AS cost
	FROM
		erp_gl_trans
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
	WHERE
		erp_gl_trans.tran_date >= date_sub(now(), INTERVAL 12 MONTH)
	AND erp_gl_trans.sectionid IN (50) $where_biller_id $where_year

		GROUP BY date_format(tran_date, '%Y-%m'),
		erp_gl_trans.account_code
) C ON I. MONTH = C. MONTH
LEFT JOIN (
	SELECT
		date_format(tran_date, '%Y-%m') MONTH,
		erp_gl_trans.account_code,
		erp_gl_trans.sectionid,
		erp_gl_charts.accountname,
		erp_gl_charts.parent_acc,
		sum(erp_gl_trans.amount) AS operation
	FROM
		erp_gl_trans
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
	WHERE
		erp_gl_trans.tran_date >= date_sub(now(), INTERVAL 12 MONTH)
	AND erp_gl_trans.sectionid IN (60,80,90) $where_biller_id $where_year
		GROUP BY date_format(tran_date, '%Y-%m'),
		erp_gl_trans.account_code
) O ON O. MONTH = I. MONTH
GROUP BY
	I. MONTH
ORDER BY
	I. MONTH";
        $q = $this->db->query($myQuery);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	//kp
	public function getChartsummary_report($year = null)  {
		if($year != null){
			$where_year = "AND YEAR(erp_quotes.date) = ".$year;
		}else{
			$where_year = "";
		}
        $myQuery = "SELECT grand_total,advance_payment,total,date_format(date, '%Y-%m') MONTH 
		from erp_quotes $where_year
		
		";
        $q = $this->db->query($myQuery);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	// end kp
	
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

    public function getAllWarehouses()
    {
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	 public function getAllCharts()
    {
        $q = $this->db->get('gl_charts');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllCustomers()
    {
        $q = $this->db->get('customers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllBillers()
    {
        $q = $this->db->get('billers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllSuppliers()
    {
        $q = $this->db->get('suppliers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
     public function getDailySales($year, $month)
    {
        $myQuery = "SELECT DATE_FORMAT( erp_quotes.date,  '%e' ) AS date
			FROM " . $this->db->dbprefix('quotes') . " 
			LEFT JOIN erp_sales ON erp_sales.quote_id=erp_quotes.id
			WHERE DATE_FORMAT( erp_quotes.date,  '%Y-%m' ) =  '{$year}-{$month}'
			GROUP BY DATE_FORMAT( erp_quotes.date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getMonthlyApplicantion($year)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date,
					SUM(COALESCE( total, 0 )) AS tt_applicantion
			FROM " . $this->db->dbprefix('quotes') . "
			WHERE DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}'
			AND status='applicant'
			GROUP BY date_format( date, '%c' )
			ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if($q->num_rows()>0){
			return $q->row();
		}
		return false;
    }
	public function getMonthlyRejected($year)
    {
        $myQuery = "SELECT DATE_FORMAT(approved_date,  '%c') AS date,
					SUM(COALESCE(total, 0 )) AS tt_rejected
			FROM " . $this->db->dbprefix('quotes') . "
			WHERE DATE_FORMAT(approved_date,  '%Y-%m') =  '{$year}'
			AND status='rejected'
			GROUP BY date_format(approved_date, '%c')
			ORDER BY date_format(approved_date, '%c') ASC";
        $q = $this->db->query($myQuery, false);
        if($q->num_rows()>0){
			return $q->row();
		}
		return false;
    }
	public function getMonthlyContract($year)
    {
        $myQuery = "SELECT DATE_FORMAT( approved_date,  '%c' ) AS date,
					SUM( COALESCE( total, 0 ) ) AS tt_contract
			FROM " . $this->db->dbprefix('sales') . "
			WHERE DATE_FORMAT( approved_date,  '%Y-%m' ) =  '{$year}'
			AND sale_status='approved'
			GROUP BY date_format( approved_date, '%c' ) 
			ORDER BY date_format( approved_date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
    public function getMonthlySales($year)
    {
        $myQuery = "SELECT DATE_FORMAT( approved_date,  '%c' ) AS date,
					SUM( COALESCE( grand_total, 0 ) ) AS tt_disbursement
			FROM " . $this->db->dbprefix('sales') . "
			WHERE DATE_FORMAT( approved_date,  '%Y-%m' ) =  '{$year}'
			AND sale_status='activated'
			GROUP BY date_format( approved_date, '%c' ) 
			ORDER BY date_format( approved_date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	
    public function getRoomDailySales($room_id, $year, $month)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('sales') . "
            WHERE suspend_note = {$room_id} AND DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getStaffDailySaleman($user_id, $year, $month)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('sales') . "
            WHERE (CASE WHEN saleman_by <> '' THEN saleman_by ELSE created_by END) = {$user_id} AND DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
    public function getStaffDailySales($user_id, $year, $month)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('sales') . "
            WHERE created_by = {$user_id} AND DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getRoomMonthlySales($room_id, $year)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('sales') . "
            WHERE suspend_note = {$room_id} AND DATE_FORMAT( date,  '%Y' ) =  '{$year}'
            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	/*public function getStaffMonthlySaleman($user_id, $year)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('sales') . "
            WHERE (CASE WHEN saleman_by <> '' THEN saleman_by ELSE created_by END) = {$user_id} AND DATE_FORMAT( date,  '%Y' ) =  '{$year}'
            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }*/
	
	public function getStaffMonthlySaleman($user_id, $year)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('sales') . "
            WHERE (CASE WHEN saleman_by <> '' THEN saleman_by ELSE created_by END) = {$user_id} AND DATE_FORMAT( date,  '%Y' ) =  '{$year}'
            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
    public function getStaffMonthlySales($user_id, $year)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('sales') . "
            WHERE created_by = {$user_id} AND DATE_FORMAT( date,  '%Y' ) =  '{$year}'
            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getPurchasesTotals($supplier_id)
    {
        $this->db->select('SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('supplier_id', $supplier_id);
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSupplierPurchases($supplier_id)
    {
        $this->db->from('purchases')->where('supplier_id', $supplier_id);
        return $this->db->count_all_results();
    }


    public function getRoomPurchases($room_id)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('suspend_note', $room_id);
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getStaffPurchases($user_id)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('created_by', $user_id);
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getStaffSales($user_id)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('created_by', $user_id);
        $q = $this->db->get('saless');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getStaffSaleman($user_id)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('saleman_by', $user_id);
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getRoomSales($room_id)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('suspend_note', $room_id);
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getTotalSales($start, $end, $biller_id = NULL)
    {
        $this->db->select('count(id) as total, sum(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid, SUM(COALESCE(total_tax, 0)) as tax', FALSE)
            ->where('sale_status !=', 'pending')
            ->where('date BETWEEN ' . $start . ' and ' . $end);
			if($biller_id != NULL){
				$this->db->where('biller_id', $biller_id);
			}
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalPurchases($start, $end, $biller_id = NULL)
    {
        $this->db->select('count(id) as total, sum(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid, SUM(COALESCE(total_tax, 0)) as tax', FALSE)
            ->where('status', 'received')
			->where('date BETWEEN ' . $start . ' and ' . $end);
			if($biller_id != NULL){
				$this->db->where('biller_id', $biller_id);
			}
        $q = $this->db->get('purchases');
		
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalExpenses($start, $end, $biller_id = NULL)
    {
        $this->db->select('count(id) as total, sum(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('date BETWEEN ' . $start . ' and ' . $end);
			if($biller_id != NULL){
				$this->db->where('biller_id', $biller_id);
			}
        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalPaidAmount($start, $end, $biller_id = NULL)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('type', 'sent')
            ->where('date BETWEEN ' . $start . ' and ' . $end);
			if($biller_id != NULL){
				$this->db->where('biller_id', $biller_id);
			}
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalReceivedAmount($start, $end, $biller_id = NULL)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('type', 'received')
            ->where('date BETWEEN ' . $start . ' and ' . $end);
			if($biller_id != NULL){
				$this->db->where('biller_id', $biller_id);
			}
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalReceivedCashAmount($start, $end, $biller_id = NULL)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('type', 'received')->where('paid_by', 'cash')
            ->where('date BETWEEN ' . $start . ' and ' . $end);
			if($biller_id != NULL){
				$this->db->where('biller_id', $biller_id);
			}
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalReceivedCCAmount($start, $end, $biller_id = NULL)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('type', 'received')->where('paid_by', 'CC')
            ->where('date BETWEEN ' . $start . ' and ' . $end);
			if($biller_id != NULL){
				$this->db->where('biller_id', $biller_id);
			}
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalReceivedChequeAmount($start, $end, $biller_id = NULL)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('type', 'received')->where('paid_by', 'Cheque')
            ->where('date BETWEEN ' . $start . ' and ' . $end);
			if($biller_id != NULL){
				$this->db->where('biller_id', $biller_id);
			}
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalReceivedPPPAmount($start, $end, $biller_id = NULL)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('type', 'received')->where('paid_by', 'ppp')
            ->where('date BETWEEN ' . $start . ' and ' . $end);
			if($biller_id != NULL){
				$this->db->where('biller_id', $biller_id);
			}
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalReceivedStripeAmount($start, $end, $biller_id = NULL)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('type', 'received')->where('paid_by', 'stripe')
            ->where('date BETWEEN ' . $start . ' and ' . $end);
			if($biller_id != NULL){
				$this->db->where('biller_id', $biller_id);
			}
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalReturnedAmount($start, $end, $biller_id = NULL)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('type', 'returned')
            ->where('date BETWEEN ' . $start . ' and ' . $end);
			if($biller_id != NULL){
				$this->db->where('biller_id', $biller_id);
			}
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getWarehouseTotals($warehouse_id = NULL)
    {
        $this->db->select('sum(quantity) as total_quantity, count(id) as total_items', FALSE);
        $this->db->where('quantity !=', 0);
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('warehouses_products');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getDailySaleRevenues($date)
    {
        $myQuery = "SELECT 
                        SUM( COALESCE( grand_total, 0 ) ) AS total, 
                        SUM( COALESCE( total_discount, 0 ) ) AS discount
			FROM " . $this->db->dbprefix('sales') . "
			WHERE DATE_FORMAT( date,  '%Y-%m-%d' ) =  '{$date}'
			GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getCosting($date)
    {
        $this->db->select('SUM( COALESCE( total_cost, 0 ) ) AS cost, SUM( COALESCE( grand_total, 0 ) ) AS sales, SUM( total_tax + shipping + total_cost ) AS net_cost, SUM( total_tax + shipping + grand_total ) AS net_sales', FALSE)
			->where("date >=", $date.' 00:00:00')
			->where("date <=", $date.' 23:55:00')
			->where('pos !=', 1);

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	
	public function getSaleDaily($date){
		$settings = $this->getSettingCurrncy();
		$this->db->select("quotes.id,quotes.date,quotes.approved_date, sales.reference_no,quotes.reference_no as reference,
						   CONCAT(erp_companies.family_name,' ',erp_companies.name) AS name_en,
						   CONCAT(erp_companies.family_name_other,' ',erp_companies.name_other) AS name_kh,
						   quotes.quote_status status,CONCAT(erp_users.first_name,' ',erp_users.last_name) AS coname,
						   myBranch.name as branches,(erp_quotes.total*erp_currencies.rate/".$settings->rate .") as total,
						   (erp_sales.grand_total*erp_currencies.rate/".$settings->rate .") as grand_total,currencies.name as crname")
			->join('users','users.id=quotes.by_co','INNER')
			->join('sales','quotes.id=sales.quote_id','LEFT')
			->join('companies','companies.id=quotes.customer_id','INNER')
			->join('companies AS myBranch','myBranch.id=quotes.branch_id','LEFT')
			->join('quote_items','quotes.id=quote_items.quote_id','LEFT')
			->join('currencies','currencies.code=quote_items.currency_code')
			
			->where("quotes.date >=", $date.' 00:00:00')
			->where("quotes.date <=", $date.' 23:55:00')
			->where("quotes.quote_status","applicant")
			->where("quotes.status","loans")
			->or_where("quotes.approved_date >=", $date.' 00:00:00')
			->where("quotes.approved_date <=", $date.' 23:55:00');
        $q = $this->db->get('quotes');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
	}
	public function getApplicantionQuotes($date){
		$this->db->select('date,SUM(COALESCE(total,0)) as t_applicantion',FALSE)
				 ->where("quote_status",'applicant')
				 ->where("status",'loans')
				 ->where("date >=", $date.' 00:00:00')
				 ->where("date <=", $date.' 23:55:00');
				$q = $this->db->get('quotes');
				if ($q->num_rows() > 0) {
					return $q->row();
				}
				return false;
	}
	function getDailyRejected($date){
		$this->db->select('approved_date,SUM(COALESCE(total,0)) as t_rejected',FALSE)
				 ->where("quote_status",'rejected')
				 ->where("status",'loans')
				 ->where("approved_date >=", $date.' 00:00:00')
				 ->where("approved_date <=", $date.' 23:55:00');
				$q = $this->db->get('quotes');
				if ($q->num_rows() > 0) {
					return $q->row();
				}
				return false;
	}
	function getDailyApproved($date){
		$this->db->select('approved_date,SUM(COALESCE(total,0)) as t_contract',FALSE)
				 ->where("sale_status",'approved')
				  ->where("status",'loans')
				 ->where("approved_date >=", $date.' 00:00:00')
				 ->where("approved_date <=", $date.' 23:55:00');
				$q = $this->db->get('sales');
				if ($q->num_rows() > 0) {
					return $q->row();
				}
				return false;
	}
	
	function getDailyDisburse($date){
		$this->db->select('approved_date,SUM(COALESCE(grand_total,0)) as t_disburse',FALSE)
				 ->where("sale_status",'activated')
				  ->where("status",'loans')
				 ->where("approved_date >=", $date.' 00:00:00')
				 ->where("approved_date <=", $date.' 23:55:00');
				$q = $this->db->get('sales');
				if ($q->num_rows() > 0) {
					return $q->row();
				}
				return false;
	}
	public function getDailyQuotes($date)
    {
		$settings = $this->getSettingCurrncy();
		$this->db->select('date,SUM(COALESCE(total,0)) as app_amount',FALSE);
		$this->db->where('DATE(date)',$date);
		$this->db->where("status",'loans');
		//$this->db->or_where(date('Y-m-d'),$date);
		$q = $this->db->get('quotes');
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
    }	
	
	public function getPurchaseing($date)
    {
        $this->db->select("date, reference_no, supplier, status, grand_total, paid, (grand_total-paid) as balance, payment_status")
			->where("date >=", $date.' 00:00:00')
			->where("date <=", $date.' 23:55:00');

        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }
	
	public function getMonthCosting($date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        $this->db->select('SUM( COALESCE( total_cost, 0 ) ) AS cost, SUM( COALESCE( grand_total, 0 ) ) AS sales, SUM( total_tax + shipping + total_cost ) AS net_cost, SUM( total_tax + shipping + grand_total ) AS net_sales', FALSE);
		
		if($date) {
            $this->db->where('sales.date', $date);
        }elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('sales.date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('sales.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }

        if ($warehouse_id) {
            //$this->db->join('sales', 'sales.id=costing.sale_id')
            $this->db->where('sales.warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	
	public function getMonthPurchaseing($date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        $this->db->select("date, reference_no, supplier, status, grand_total, paid, (grand_total-paid) as balance, payment_status");
		
		if($date) {
            $this->db->where('purchases.date', $date);
        }elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('purchases.date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('purchases.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }
		
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }
	
	public function getMonthSales($date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
		$settings 		= $this->getSettingCurrncy();
        $this->db->select("quotes.id,quotes.date,quotes.approved_date, sales.reference_no,quotes.reference_no as reference,
						   CONCAT(erp_companies.family_name,' ',erp_companies.name) AS name_en,
						   CONCAT(erp_companies.family_name_other,' ',erp_companies.name_other) AS name_kh,
						   quotes.quote_status as status,CONCAT(erp_users.first_name,' ',erp_users.last_name) AS coname,
						   myBranch.name as branches,(erp_quotes.total*erp_currencies.rate/".$settings->rate .") as total,
						   (erp_sales.grand_total*erp_currencies.rate/".$settings->rate .") as grand_total,currencies.name as crname");
		$this->db->join('users','users.id=quotes.by_co','INNER');
		$this->db->join('sales','quotes.id=sales.quote_id','LEFT');
		$this->db->join('companies','companies.id=quotes.customer_id','INNER');
		$this->db->join('companies AS myBranch','myBranch.id=quotes.branch_id','LEFT');
		$this->db->join('quote_items','quotes.id=quote_items.quote_id','LEFT');
		$this->db->join('currencies','currencies.code=quote_items.currency_code');
		if($date) {
            $this->db->where('quotes.date', $date);
			$this->db->where('quotes.status', 'loans');
        }elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('quotes.date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('quotes.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
			$this->db->where('quotes.status', 'loans');
        }
        $q = $this->db->get('quotes');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }
	
	public function getOrderDiscount($date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        $sdate = $date.' 00:00:00';
        $edate = $date.' 23:59:59';
        $this->db->select('SUM( COALESCE( order_discount, 0 ) ) AS order_discount', FALSE);
        if ($date) {
            $this->db->where('date >=', $sdate)->where('date <=', $edate);
        } elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getExpenses($date)
    {
        $sdate = $date.' 00:00:00';
        $edate = $date.' 23:59:59';
        $this->db->select('SUM( COALESCE( amount, 0 ) ) AS total', FALSE)
        ->where('date >=', $sdate)->where('date <=', $edate);

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	
	public function getExpense($date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        $sdate = $date.' 00:00:00';
        $edate = $date.' 23:59:59';
        $this->db->select('SUM( COALESCE( amount, 0 ) ) AS total', FALSE);
        if ($date) {
            $this->db->where('date >=', $sdate)->where('date <=', $edate);
        } elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }
        

        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	
	public function getReturns($date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        $sdate = $date.' 00:00:00';
        $edate = $date.' 23:59:59';
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total', FALSE)
        ->where('sale_status', 'returned');
        if ($date) {
            $this->db->where('date >=', $sdate)->where('date <=', $edate);
        } elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
    
	public function getSaleDetail($product_code)
    {
        $this->db->order_by('sale_items.id', 'asc');
		$this->db->join('sales', 'sales.id = sale_items.sale_id', 'left');
        $q = $this->db->get_where('sale_items', array('product_code' => $product_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getPurchaseDetail($product_code)
    {
		$this->db->select('*');
		$this->db->from('purchase_items');
		$this->db->join('purchases', 'purchase_items.purchase_id = purchases.id');
		$this->db->where('purchase_items.product_code', $product_code);
		$this->db->where('purchase_items.status <>', 'ordered');
        //$this->db->order_by('id', 'asc');
		$q = $this->db->get();
        //$q = $this->db->get_where('purchase_items', array('product_code' => $product_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getPurchaseDetailSupplier($product_code, $supplier_id)
    {	
		$this->db->select('*');
		$this->db->from('purchase_items');
		$this->db->join('purchases', 'purchase_items.purchase_id = purchases.id');
		$this->db->where('purchase_items.product_code', $product_code);
		$this->db->where('purchases.supplier_id', $supplier_id);
		$this->db->where('purchase_items.status <>', 'ordered');
        //$this->db->order_by('id', 'asc');
		$q = $this->db->get();
        //$q = $this->db->get_where('purchase_items', array('product_code' => $product_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getSalesReturnDate($date)
    {
		$this->db->select("SUM( COALESCE( ABS({$this->db->dbprefix('return_sales')}.grand_total), 0 ) ) AS paid, SUM( ( COALESCE( quantity, 0 ) ) ) AS quantity, SUM(( COALESCE( {$this->db->dbprefix('sales')}.order_discount, 0 ) ) ) AS order_discount", FALSE)
			->join('return_sales', 'sales.return_id=return_sales.id', 'left')
			->join('return_items', 'return_items.return_id=return_sales.id', 'left')
			//->like('quantity', '-')
			->where("DATE({$this->db->dbprefix('sales')}.date)", $date);
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
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
	
	public function getTotalDiscountDate($date)
    {
		 $this->db->select('SUM( COALESCE( total_discount, 0 ) ) AS discount', FALSE)
        ->where('DATE(date)', $date);

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	
	public function getTotalCosts($start, $end, $biller_id = NULL)
    {
        $this->db->select('SUM( COALESCE( purchase_unit_cost, 0 ) * quantity ) AS cost', FALSE)
        ->where('date BETWEEN ' . $start . ' and ' . $end);
        $q = $this->db->get('costing');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	
	public function getDailyPurchases($year, $month, $warehouse_id = NULL)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('purchases') . " WHERE ";
        if ($warehouse_id) {
            $myQuery .= " warehouse_id = {$warehouse_id} AND ";
        }
        $myQuery .= " DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
/*
    public function getmonthlyPurchases()
    {
        $myQuery = "SELECT (CASE WHEN date_format( date, '%b' ) Is Null THEN 0 ELSE date_format( date, '%b' ) END) as month, SUM( COALESCE( total, 0 ) ) AS purchases FROM purchases WHERE date >= date_sub( now( ) , INTERVAL 12 MONTH ) GROUP BY date_format( date, '%b' ) ORDER BY date_format( date, '%m' ) ASC";
        $q = $this->db->query($myQuery);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
*/
    public function getMonthlyPurchases($year, $warehouse_id = NULL)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('purchases') . " WHERE ";
        if ($warehouse_id) {
            $myQuery .= " warehouse_id = {$warehouse_id} AND ";
        }
        $myQuery .= " DATE_FORMAT( date,  '%Y' ) =  '{$year}'
            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStaffDailyPurchases($user_id, $year, $month, $warehouse_id = NULL)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('purchases')." WHERE ";
        if ($warehouse_id) {
            $myQuery .= " warehouse_id = {$warehouse_id} AND ";
        }
        $myQuery .= " created_by = {$user_id} AND DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStaffMonthlyPurchases($user_id, $year, $warehouse_id = NULL)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('purchases') . " WHERE ";
        if ($warehouse_id) {
            $myQuery .= " warehouse_id = {$warehouse_id} AND ";
        }
        $myQuery .= " created_by = {$user_id} AND DATE_FORMAT( date,  '%Y' ) =  '{$year}'
            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getReportW($product = NULL, $category = NULL, $supplier = NULL, $start_date = NULL, $end_date = NULL){
		$where_purchase = "where 1=1 AND {$this->db->dbprefix('purchase_items')}.status <> 'ordered' AND {$this->db->dbprefix('purchase_items')}.purchase_id != ''";
		$where_sale='where 1=1';
		if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date = $end_date ? $this->erp->fld($end_date) : date('Y-m-d');

            $pp = "( SELECT pi.product_id, 
						SUM( pi.quantity * (CASE WHEN pi.option_id <> 0 THEN pi.vqty_unit ELSE 1 END) ) purchasedQty, 
						SUM( tpi.quantity_balance ) balacneQty, 
						SUM((CASE WHEN pi.option_id <> 0 THEN pi.vcost ELSE pi.unit_cost END) *  tpi.quantity_balance ) balacneValue, 
						SUM( pi.unit_cost * pi.quantity ) totalPurchase, 
                        SUM(pi.unit_cost) AS totalCost,
						SUM(pi.quantity) AS Pquantity,
						pi.date as pdate 
						FROM ( SELECT {$this->db->dbprefix('purchase_items')}.date as date, 
									{$this->db->dbprefix('purchase_items')}.product_id, 
									purchase_id, 
									SUM({$this->db->dbprefix('purchase_items')}.quantity) as quantity, 
									unit_cost,
									option_id,
									ppv.qty_unit AS vqty_unit,
									ppv.cost AS vcost,
									ppv.quantity AS vquantity 
									FROM erp_purchase_items 
									JOIN {$this->db->dbprefix('products')} p 
									ON p.id = {$this->db->dbprefix('purchase_items')}.product_id 
									LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
									ON ppv.id={$this->db->dbprefix('purchase_items')}.option_id  
									WHERE {$this->db->dbprefix('purchase_items')}.date >= '{$start_date}' AND {$this->db->dbprefix('purchase_items')}.date < '{$end_date}' 
									GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi 
						LEFT JOIN ( SELECT product_id, 
										SUM(quantity_balance) as quantity_balance 
										FROM {$this->db->dbprefix('purchase_items')} 
										GROUP BY product_id ) tpi on tpi.product_id = pi.product_id 
						GROUP BY pi.product_id ) PCosts";

			$sp = "( SELECT si.product_id, 
						SUM( si.quantity*(CASE WHEN si.option_id <> 0 THEN spv.qty_unit ELSE 1 END)) soldQty, 
						SUM( si.subtotal ) totalSale, 
						SUM( si.quantity) AS Squantity,
						s.date as sdate
						FROM " . $this->db->dbprefix('sales') . " s 
						JOIN " . $this->db->dbprefix('sale_items') . " si 
						ON s.id = si.sale_id 
						LEFT JOIN " . $this->db->dbprefix('product_variants') . " spv 
						ON spv.id=si.option_id
						WHERE s.date >= '{$start_date}' AND s.date < '{$end_date}' 
						GROUP BY si.product_id ) PSales";

			$ppb = "( SELECT pi.product_id, 
						SUM( pi.quantity ) purchasedQty, 
						SUM( tpi.quantity_balance ) balacneQty, 
						SUM( (CASE WHEN pi.option_id <> 0 THEN pi.vcost ELSE pi.unit_cost END) *  tpi.quantity_balance ) balacneValue, 
						SUM( pi.unit_cost * pi.quantity ) totalPurchase, 
						pi.date as pdate 
						FROM ( SELECT {$this->db->dbprefix('purchase_items')}.date as date, 
									{$this->db->dbprefix('purchase_items')}.product_id, 
									purchase_id, 
									SUM({$this->db->dbprefix('purchase_items')}.quantity) as quantity, 
									unit_cost,
									option_id,
									ppv.qty_unit AS vqty_unit,
									ppv.cost AS vcost,
									ppv.quantity AS vquantity 
									FROM erp_purchase_items 
									JOIN {$this->db->dbprefix('products')} p 
									ON p.id = {$this->db->dbprefix('purchase_items')}.product_id 
									LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
									ON ppv.id={$this->db->dbprefix('purchase_items')}.option_id  
									WHERE {$this->db->dbprefix('purchase_items')}.date < '{$start_date}'
									GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi 
						LEFT JOIN ( SELECT product_id, 
										SUM(quantity_balance) as quantity_balance 
										FROM {$this->db->dbprefix('purchase_items')} 
										GROUP BY product_id ) tpi on tpi.product_id = pi.product_id GROUP BY pi.product_id ) PCostsBegin";
            
			$spb = "( SELECT si.product_id, 
						SUM( si.quantity*(CASE WHEN si.option_id <> 0 THEN spv.qty_unit ELSE 1 END)) saleQty, 
						SUM( si.subtotal ) totalSale, 
						SUM( si.quantity) AS Squantity,
						s.date as sdate
						FROM " . $this->db->dbprefix('sales') . " s 
						JOIN " . $this->db->dbprefix('sale_items') . " si 
						ON s.id = si.sale_id 
						LEFT JOIN " . $this->db->dbprefix('product_variants') . " spv 
						ON spv.id=si.option_id
						WHERE s.date < '{$start_date}'
						GROUP BY si.product_id ) PSalesBegin";
        } 
		else {
			$current_date = date('Y-m-d');
			$prevouse_date = date('Y').'-'.date('m').'-'.'01';
			$pp = "( SELECT pi.product_id, 
						SUM( pi.quantity * (CASE WHEN pi.option_id <> 0 THEN pi.vqty_unit ELSE 1 END) ) purchasedQty, 
						SUM( tpi.quantity_balance ) balacneQty, 
						SUM( (CASE WHEN pi.option_id <> 0 THEN pi.vcost ELSE pi.unit_cost END) *  tpi.quantity_balance ) balacneValue, 
						SUM( pi.unit_cost * pi.quantity ) totalPurchase, 
                        SUM(pi.unit_cost) AS totalCost,
						SUM(pi.quantity) AS Pquantity,
						pi.date as pdate 
						FROM ( SELECT {$this->db->dbprefix('purchase_items')}.date as date, 
									{$this->db->dbprefix('purchase_items')}.product_id, 
									purchase_id, 
									SUM({$this->db->dbprefix('purchase_items')}.quantity) as quantity, 
									unit_cost ,
									option_id,
									ppv.qty_unit AS vqty_unit,
									ppv.cost AS vcost,
									ppv.quantity AS vquantity
									FROM {$this->db->dbprefix('purchase_items')} 
									JOIN {$this->db->dbprefix('products')} p 
									ON p.id = {$this->db->dbprefix('purchase_items')}.product_id 
									LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
									ON ppv.id={$this->db->dbprefix('purchase_items')}.option_id  
									".$where_purchase." 
									GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi 			
						LEFT JOIN ( SELECT product_id, 
										SUM(quantity_balance) as quantity_balance 
										FROM {$this->db->dbprefix('purchase_items')} GROUP BY product_id 
									) tpi on tpi.product_id = pi.product_id GROUP BY pi.product_id ) PCosts";

			$sp = "( SELECT si.product_id, 
						COALESCE(SUM( si.quantity*(CASE WHEN si.option_id <> 0 THEN spv.qty_unit ELSE 1 END)),0) soldQty, 
						SUM( si.subtotal ) totalSale, 
						SUM( si.quantity) AS Squantity,
						s.date as sdate
						FROM " . $this->db->dbprefix('sales') . " s 
						JOIN " . $this->db->dbprefix('sale_items') . " si 
						ON s.id = si.sale_id 
						LEFT JOIN " . $this->db->dbprefix('product_variants') . " spv 
						ON spv.id=si.option_id
						".$where_sale."
						GROUP BY si.product_id ) PSales";

			
			$ppb = "( SELECT pi.product_id, 
						SUM(pi.quantity) AS purchasedQty, 
						SUM( tpi.quantity_balance ) balacneQty, 
						SUM( (CASE WHEN pi.option_id <> 0 THEN pi.vcost ELSE pi.unit_cost END) * tpi.quantity_balance ) balacneValue, 
						SUM(pi.unit_cost * pi.quantity) totalPurchase, 
						pi.date as pdate 
						FROM ( SELECT {$this->db->dbprefix('purchase_items')}.date as date, 
									{$this->db->dbprefix('purchase_items')}.product_id, 
									purchase_id, 
									SUM({$this->db->dbprefix('purchase_items')}.quantity) as quantity, 
									unit_cost ,
									option_id,
									ppv.qty_unit AS vqty_unit,
									ppv.cost AS vcost,
									ppv.quantity AS vquantity
									FROM {$this->db->dbprefix('purchase_items')} 
									JOIN {$this->db->dbprefix('products')} p 
									ON p.id = {$this->db->dbprefix('purchase_items')}.product_id 
									LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
									ON ppv.id={$this->db->dbprefix('purchase_items')}.option_id  
									".$where_purchase." 
									AND {$this->db->dbprefix('purchase_items')}.date < '{$prevouse_date}' 
									GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi 			
						LEFT JOIN ( SELECT product_id, 
										SUM(quantity_balance) as quantity_balance 
										FROM {$this->db->dbprefix('purchase_items')} 
										GROUP BY product_id ) tpi on tpi.product_id = pi.product_id GROUP BY pi.product_id ) PCostsBegin";
			
            $spb = "( SELECT si.product_id, 
						COALESCE(SUM( si.quantity*(CASE WHEN si.option_id <> 0 THEN spv.qty_unit ELSE 1 END)),0) saleQty, 
						SUM( si.subtotal ) totalSale, 
						SUM( si.quantity) AS Squantity,
						s.date as sdate
						FROM " . $this->db->dbprefix('sales') . " s 
						JOIN " . $this->db->dbprefix('sale_items') . " si 
						ON s.id = si.sale_id 
						LEFT JOIN " . $this->db->dbprefix('product_variants') . " spv 
						ON spv.id=si.option_id
						".$where_sale."
						AND s.date < '{$prevouse_date}'
						GROUP BY si.product_id ) PSalesBegin";
			
        }
						
		$this->db->select($this->db->dbprefix('products') . ".id as product_id, 
				" . $this->db->dbprefix('products') . ".code as product_code, 
				" . $this->db->dbprefix('products') . ".name,
				COALESCE( PCostsBegin.purchasedQty-PSalesBegin.saleQty, 0 ) as BeginPS,
				CONCAT(COALESCE (PCosts.Pquantity, 0)) AS purchased,
				COALESCE( PSales.Squantity, 0 ) + COALESCE (
                        (
                            SELECT
                                SUM(si.quantity * ci.quantity)
                            FROM
                                ".$this->db->dbprefix('combo_items') . " ci
                            INNER JOIN erp_sale_items si ON si.product_id = ci.product_id
                            WHERE
                                ci.item_code = ".$this->db->dbprefix('products') . ".code
                        ),
                        0
                    ) as sold,
				COALESCE (COALESCE (
						PCostsBegin.purchasedQty-PSalesBegin.saleQty,
						0
					)+COALESCE (PCosts.Pquantity, 0) - COALESCE( PSales.Squantity , 0 ) -  COALESCE (
                        (
                            SELECT
                                SUM(si.quantity * ci.quantity)
                            FROM
								".$this->db->dbprefix('combo_items') . " ci
                            INNER JOIN erp_sale_items si ON si.product_id = ci.product_id
                            WHERE
                                ci.item_code = ".$this->db->dbprefix('products') . ".code
                        ),
                        0
                    ) ) AS balance", 
				FALSE)
				 ->from('products')
				 ->join($sp, 'products.id = PSales.product_id', 'left')
				 ->join($pp, 'products.id = PCosts.product_id', 'left')
				 ->join($spb, 'products.id = PSalesBegin.product_id', 'left')
                 ->join($ppb, 'products.id = PCostsBegin.product_id', 'left')
				 ->join('warehouses_products wp', 'products.id=wp.product_id', 'left')
				 ->join('categories', 'products.category_id=categories.id', 'left')
				 ->group_by("products.id");
		if($product){
			$this->db->where($this->db->dbprefix('products') . ".id", $product);
		}
		if ($category) {
			$this->db->where($this->db->dbprefix('products') . ".category_id", $category);
		}
		if ($supplier) {
			$this->db->where("products.supplier1 = '".$supplier."' or products.supplier2 = '".$supplier."' or products.supplier3 = '".$supplier."' or products.supplier4 = '".$supplier."' or products.supplier5 = '".$supplier."'");
		}
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;		
	}
	
	public function getInOutByID($id){
		if ($this->input->get('product')) {
            $product = $this->input->get('product');
        } else {
            $product = NULL;
        }
        if ($this->input->get('category')) {
            $category = $this->input->get('category');
        } else {
            $category = NULL;
        }
        if ($this->input->get('in_out')) {
            $in_out = $this->input->get('in_out');
        } else {
            $in_out = NULL;
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
		if ($this->input->get('supplier')) {
            $supplier = $this->input->get('supplier');
        } else {
            $supplier = NULL;
        }
		if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
			$where_sale='where si.warehouse_id='.$warehouse;
			$where_purchase="where {$this->db->dbprefix('purchase_items')}.warehouse_id=".$warehouse . "AND {$this->db->dbprefix('purchase_items')}.status <> 'ordered'";
        } else {
            $warehouse = NULL;
			$where_purchase = "where 1=1 AND {$this->db->dbprefix('purchase_items')}.status <> 'ordered' AND {$this->db->dbprefix('purchase_items')}.purchase_id != ''";
			//$where_purchase = "where 1=1 AND {$this->db->dbprefix('purchase_items')}.status <> 'ordered'";
			$where_sale='where 1=1';
        }
        if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date = $end_date ? $this->erp->fld($end_date) : date('Y-m-d');

            $pp = "( SELECT pi.product_id, 
						SUM( pi.quantity * (CASE WHEN pi.option_id <> 0 THEN pi.vqty_unit ELSE 1 END) ) purchasedQty, 
						SUM( tpi.quantity_balance ) balacneQty, 
						SUM((CASE WHEN pi.option_id <> 0 THEN pi.vcost ELSE pi.unit_cost END) *  tpi.quantity_balance ) balacneValue, 
						SUM( pi.unit_cost * pi.quantity ) totalPurchase, 
                        SUM(pi.unit_cost) AS totalCost,
						SUM(pi.quantity) AS Pquantity,
						pi.date as pdate 
						FROM ( SELECT {$this->db->dbprefix('purchase_items')}.date as date, 
									{$this->db->dbprefix('purchase_items')}.product_id, 
									purchase_id, 
									SUM({$this->db->dbprefix('purchase_items')}.quantity) as quantity, 
									unit_cost,
									option_id,
									ppv.qty_unit AS vqty_unit,
									ppv.cost AS vcost,
									ppv.quantity AS vquantity 
									FROM erp_purchase_items 
									JOIN {$this->db->dbprefix('products')} p 
									ON p.id = {$this->db->dbprefix('purchase_items')}.product_id 
									LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
									ON ppv.id={$this->db->dbprefix('purchase_items')}.option_id  
									WHERE {$this->db->dbprefix('purchase_items')}.date >= '{$start_date}' AND {$this->db->dbprefix('purchase_items')}.date < '{$end_date}' 
									GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi 
						LEFT JOIN ( SELECT product_id, 
										SUM(quantity_balance) as quantity_balance 
										FROM {$this->db->dbprefix('purchase_items')} 
										GROUP BY product_id ) tpi on tpi.product_id = pi.product_id 
						GROUP BY pi.product_id ) PCosts";

			$sp = "( SELECT si.product_id, 
						SUM( si.quantity*(CASE WHEN si.option_id <> 0 THEN spv.qty_unit ELSE 1 END)) soldQty, 
						SUM( si.subtotal ) totalSale, 
						SUM( si.quantity) AS Squantity,
						s.date as sdate
						FROM " . $this->db->dbprefix('sales') . " s 
						JOIN " . $this->db->dbprefix('sale_items') . " si 
						ON s.id = si.sale_id 
						LEFT JOIN " . $this->db->dbprefix('product_variants') . " spv 
						ON spv.id=si.option_id
						WHERE s.date >= '{$start_date}' AND s.date < '{$end_date}' 
						GROUP BY si.product_id ) PSales";

			$ppb = "( SELECT pi.product_id, 
						SUM( pi.quantity ) purchasedQty, 
						SUM( tpi.quantity_balance ) balacneQty, 
						SUM( (CASE WHEN pi.option_id <> 0 THEN pi.vcost ELSE pi.unit_cost END) *  tpi.quantity_balance ) balacneValue, 
						SUM( pi.unit_cost * pi.quantity ) totalPurchase, 
						pi.date as pdate 
						FROM ( SELECT {$this->db->dbprefix('purchase_items')}.date as date, 
									{$this->db->dbprefix('purchase_items')}.product_id, 
									purchase_id, 
									SUM({$this->db->dbprefix('purchase_items')}.quantity) as quantity, 
									unit_cost,
									option_id,
									ppv.qty_unit AS vqty_unit,
									ppv.cost AS vcost,
									ppv.quantity AS vquantity 
									FROM erp_purchase_items 
									JOIN {$this->db->dbprefix('products')} p 
									ON p.id = {$this->db->dbprefix('purchase_items')}.product_id 
									LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
									ON ppv.id={$this->db->dbprefix('purchase_items')}.option_id  
									WHERE {$this->db->dbprefix('purchase_items')}.date < '{$start_date}'
									GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi 
						LEFT JOIN ( SELECT product_id, 
										SUM(quantity_balance) as quantity_balance 
										FROM {$this->db->dbprefix('purchase_items')} 
										GROUP BY product_id ) tpi on tpi.product_id = pi.product_id GROUP BY pi.product_id ) PCostsBegin";
            
			$spb = "( SELECT si.product_id, 
						SUM( si.quantity*(CASE WHEN si.option_id <> 0 THEN spv.qty_unit ELSE 1 END)) saleQty, 
						SUM( si.subtotal ) totalSale, 
						SUM( si.quantity) AS Squantity,
						s.date as sdate
						FROM " . $this->db->dbprefix('sales') . " s 
						JOIN " . $this->db->dbprefix('sale_items') . " si 
						ON s.id = si.sale_id 
						LEFT JOIN " . $this->db->dbprefix('product_variants') . " spv 
						ON spv.id=si.option_id
						WHERE s.date < '{$start_date}'
						GROUP BY si.product_id ) PSalesBegin";
        } else {
			$current_date = date('Y-m-d');
			$prevouse_date = date('Y').'-'.date('m').'-'.'01';
            //$pp = "( SELECT pi.product_id, SUM( pi.quantity ) purchasedQty, SUM( tpi.quantity_balance ) balacneQty, SUM( pi.unit_cost * tpi.quantity_balance ) balacneValue, SUM( pi.unit_cost * pi.quantity ) totalPurchase, pi.date as pdate from ( SELECT p.date as date, product_id, purchase_id, SUM(quantity) as quantity, unit_cost from erp_purchase_items JOIN {$this->db->dbprefix('purchases')} p on p.id = {$this->db->dbprefix('purchase_items')}.purchase_id GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi LEFT JOIN ( SELECT product_id, SUM(quantity_balance) as quantity_balance from {$this->db->dbprefix('purchase_items')} GROUP BY product_id ) tpi on tpi.product_id = pi.product_id GROUP BY pi.product_id ) PCosts";
            //$sp = "( SELECT si.product_id, SUM( si.quantity ) soldQty, SUM( si.subtotal ) totalSale, s.date as sdate from " . $this->db->dbprefix('sales') . " s JOIN " . $this->db->dbprefix('sale_items') . " si on s.id = si.sale_id GROUP BY si.product_id ) PSales";
			$pp = "( SELECT pi.product_id, 
						SUM( pi.quantity * (CASE WHEN pi.option_id <> 0 THEN pi.vqty_unit ELSE 1 END) ) purchasedQty, 
						SUM( tpi.quantity_balance ) balacneQty, 
						SUM( (CASE WHEN pi.option_id <> 0 THEN pi.vcost ELSE pi.unit_cost END) *  tpi.quantity_balance ) balacneValue, 
						SUM( pi.unit_cost * pi.quantity ) totalPurchase, 
                        SUM(pi.unit_cost) AS totalCost,
						SUM(pi.quantity) AS Pquantity,
						pi.date as pdate 
						FROM ( SELECT {$this->db->dbprefix('purchase_items')}.date as date, 
									{$this->db->dbprefix('purchase_items')}.product_id, 
									purchase_id, 
									SUM({$this->db->dbprefix('purchase_items')}.quantity) as quantity, 
									unit_cost ,
									option_id,
									ppv.qty_unit AS vqty_unit,
									ppv.cost AS vcost,
									ppv.quantity AS vquantity
									FROM {$this->db->dbprefix('purchase_items')} 
									JOIN {$this->db->dbprefix('products')} p 
									ON p.id = {$this->db->dbprefix('purchase_items')}.product_id 
									LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
									ON ppv.id={$this->db->dbprefix('purchase_items')}.option_id  
									".$where_purchase." 
									GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi 			
						LEFT JOIN ( SELECT product_id, 
										SUM(quantity_balance) as quantity_balance 
										FROM {$this->db->dbprefix('purchase_items')} GROUP BY product_id 
									) tpi on tpi.product_id = pi.product_id GROUP BY pi.product_id ) PCosts";

			$sp = "( SELECT si.product_id, 
						COALESCE(SUM( si.quantity*(CASE WHEN si.option_id <> 0 THEN spv.qty_unit ELSE 1 END)),0) soldQty, 
						SUM( si.subtotal ) totalSale, 
						SUM( si.quantity) AS Squantity,
						s.date as sdate
						FROM " . $this->db->dbprefix('sales') . " s 
						JOIN " . $this->db->dbprefix('sale_items') . " si 
						ON s.id = si.sale_id 
						LEFT JOIN " . $this->db->dbprefix('product_variants') . " spv 
						ON spv.id=si.option_id
						".$where_sale."
						GROUP BY si.product_id ) PSales";

			
			$ppb = "( SELECT pi.product_id, 
						SUM(pi.quantity) AS purchasedQty, 
						SUM( tpi.quantity_balance ) balacneQty, 
						SUM( (CASE WHEN pi.option_id <> 0 THEN pi.vcost ELSE pi.unit_cost END) * tpi.quantity_balance ) balacneValue, 
						SUM(pi.unit_cost * pi.quantity) totalPurchase, 
						pi.date as pdate 
						FROM ( SELECT {$this->db->dbprefix('purchase_items')}.date as date, 
									{$this->db->dbprefix('purchase_items')}.product_id, 
									purchase_id, 
									SUM({$this->db->dbprefix('purchase_items')}.quantity) as quantity, 
									unit_cost ,
									option_id,
									ppv.qty_unit AS vqty_unit,
									ppv.cost AS vcost,
									ppv.quantity AS vquantity
									FROM {$this->db->dbprefix('purchase_items')} 
									JOIN {$this->db->dbprefix('products')} p 
									ON p.id = {$this->db->dbprefix('purchase_items')}.product_id 
									LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
									ON ppv.id={$this->db->dbprefix('purchase_items')}.option_id  
									".$where_purchase." 
									AND {$this->db->dbprefix('purchase_items')}.date < '{$prevouse_date}' 
									GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi 			
						LEFT JOIN ( SELECT product_id, 
										SUM(quantity_balance) as quantity_balance 
										FROM {$this->db->dbprefix('purchase_items')} 
										GROUP BY product_id ) tpi on tpi.product_id = pi.product_id GROUP BY pi.product_id ) PCostsBegin";
			
            $spb = "( SELECT si.product_id, 
						COALESCE(SUM( si.quantity*(CASE WHEN si.option_id <> 0 THEN spv.qty_unit ELSE 1 END)),0) saleQty, 
						SUM( si.subtotal ) totalSale, 
						SUM( si.quantity) AS Squantity,
						s.date as sdate
						FROM " . $this->db->dbprefix('sales') . " s 
						JOIN " . $this->db->dbprefix('sale_items') . " si 
						ON s.id = si.sale_id 
						LEFT JOIN " . $this->db->dbprefix('product_variants') . " spv 
						ON spv.id=si.option_id
						".$where_sale."
						AND s.date < '{$prevouse_date}'
						GROUP BY si.product_id ) PSalesBegin";
        }
		
		$this->db->select($this->db->dbprefix('products') . ".id as product_id, 
				" . $this->db->dbprefix('products') . ".code as product_code, 
				" . $this->db->dbprefix('products') . ".name,
				COALESCE( PCostsBegin.purchasedQty-PSalesBegin.saleQty, 0 ) as BeginPS,
				CONCAT(COALESCE (PCosts.Pquantity, 0)) AS purchased,
				COALESCE( PSales.Squantity, 0 ) + COALESCE (
                        (
                            SELECT
                                SUM(si.quantity * ci.quantity)
                            FROM
                                ".$this->db->dbprefix('combo_items') . " ci
                            INNER JOIN erp_sale_items si ON si.product_id = ci.product_id
                            WHERE
                                ci.item_code = ".$this->db->dbprefix('products') . ".code
                        ),
                        0
                    ) as sold,
				COALESCE (COALESCE (
						PCostsBegin.purchasedQty-PSalesBegin.saleQty,
						0
					)+COALESCE (PCosts.Pquantity, 0) - COALESCE( PSales.Squantity , 0 ) -  COALESCE (
                        (
                            SELECT
                                SUM(si.quantity * ci.quantity)
                            FROM
								".$this->db->dbprefix('combo_items') . " ci
                            INNER JOIN erp_sale_items si ON si.product_id = ci.product_id
                            WHERE
                                ci.item_code = ".$this->db->dbprefix('products') . ".code
                        ),
                        0
                    ) ) AS balance", 
				FALSE)
				 ->from('products')
				 ->join($sp, 'products.id = PSales.product_id', 'left')
				 ->join($pp, 'products.id = PCosts.product_id', 'left')
				 ->join($spb, 'products.id = PSalesBegin.product_id', 'left')
                 ->join($ppb, 'products.id = PCostsBegin.product_id', 'left')
				 ->join('warehouses_products wp', 'products.id=wp.product_id', 'left')
				 ->join('categories', 'products.category_id=categories.id', 'left')
				 ->where('products.id', $id)
				 ->group_by("products.id");
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;		
	}
	
	public function getRoomByID($id){
		$this->db
			->select("id,floor,name,ppl_number,description, CASE WHEN status = 0 THEN 'Active' ELSE 'Close' END AS status")
            ->from("erp_suspended")
			->where("id", $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getSalemanByID($id){
		$this->db
				->select('username, phone, sum(erp_sales.total) as sale_amount, sum(erp_sales.paid) as sale_paid, (sum(erp_sales.total) - sum(erp_sales.paid)) as balance')
				->from('users')
				->join('sales', 'sales.saleman_by = users.id')
				->where('users.id', $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getPurchasesByID($id){
		$this->db
				->select($this->db->dbprefix('purchases') . ".date, reference_no, " . $this->db->dbprefix('warehouses') . ".name as wname, supplier, GROUP_CONCAT(" . $this->db->dbprefix('purchase_items') . ".product_name SEPARATOR '___') as iname, GROUP_CONCAT(ROUND(" . $this->db->dbprefix('purchase_items') . ".quantity) SEPARATOR '___') as iqty, grand_total, paid, (grand_total-paid) as balance, " . $this->db->dbprefix('purchases') . ".status", FALSE)
				->from('purchases')
				->join('purchase_items', 'purchase_items.purchase_id=purchases.id', 'left')
				->join('warehouses', 'warehouses.id=purchases.warehouse_id', 'left')
				->where('purchases.id', $id)
                ->group_by('purchases.id')
                ->order_by('purchases.date desc');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getPaymentsByID($id){
		$this->db
				->select($this->db->dbprefix('payments') . ".id as idd, ". $this->db->dbprefix('sales') . ".suspend_note as noted, ". $this->db->dbprefix('payments'). ".date, " . $this->db->dbprefix('payments') . ".reference_no as payment_ref, " . $this->db->dbprefix('sales') . ".reference_no as sale_ref, " . $this->db->dbprefix('purchases') . ".reference_no as purchase_ref, " . $this->db->dbprefix('payments') . ".note,paid_by,amount, type")
                ->from('payments')
                ->join('sales', 'payments.sale_id=sales.id', 'left')
                ->join('purchases', 'payments.purchase_id=purchases.id', 'left')
				->where('payments.id', $id)
                ->group_by('payments.id');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getProjectsByID($id){
		$this->db
				->select($this->db->dbprefix('companies') . ".id as idd, company, name, phone, email, count(" . $this->db->dbprefix('sales') . ".id) as total, COALESCE(sum(" . $this->db->dbprefix('sales') . ".grand_total), 0) as total_amount, (COALESCE(sum(" . $this->db->dbprefix('sales') . ".grand_total), 0) * (" . $this->db->dbprefix('companies') . ".cf6/100)) as total_earned, COALESCE(sum(paid), 0) as paid, ( COALESCE(sum(grand_total), 0) - COALESCE(sum(paid), 0)) as balance", FALSE)
                ->from("companies")
                ->join('sales', 'sales.biller_id=companies.id')
                ->where('companies.group_name', 'biller')
                ->group_by('companies.id');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getSupplierByID($id){
		$this->db
				->select($this->db->dbprefix('companies') . ".id as idd, company, name, phone, email, count(" . $this->db->dbprefix('purchases') . ".id) as total, COALESCE(sum(grand_total), 0) as total_amount, COALESCE(sum(paid), 0) as paid, ( COALESCE(sum(grand_total), 0) - COALESCE(sum(paid), 0)) as balance", FALSE)
                ->from("companies")
                ->join('purchases', 'purchases.supplier_id=companies.id')
                ->where(array('companies.group_name'=> 'supplier', 'companies.id'=> $id))
                ->group_by('companies.id');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getCustomersByID($id){
		$this->db
				->select($this->db->dbprefix('companies') . ".id as idd, company, name, phone, email, count(" . $this->db->dbprefix('sales') . ".id) as total, COALESCE(sum(grand_total), 0) as total_amount, COALESCE(sum(paid), 0) as paid, ( COALESCE(sum(grand_total), 0) - COALESCE(sum(paid), 0)) as balance", FALSE)
                ->from("companies")
                ->join('sales', 'sales.customer_id=companies.id')
                ->where('companies.id', $id)
                ->group_by('companies.id');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getProfitByID($id){
		$this->db
				->select("erp_sales.id, date, reference_no, suspend_note, biller, customer, grand_total, paid, (grand_total-paid) as balance,
				COALESCE (
					(
						SELECT
							SUM(cost * " . $this->db->dbprefix('sale_items') . ".quantity)
						FROM
							erp_sale_items
						INNER JOIN erp_products ON erp_products.id = erp_sale_items.product_id
						WHERE
							erp_sale_items.sale_id = erp_sales.id
					),
					0
				) AS total_cost,
				COALESCE (
					COALESCE (
						(
							grand_total
						),
						0
					) - COALESCE (
						(
							SELECT
								SUM(cost * " . $this->db->dbprefix('sale_items') . ".quantity)
							FROM
								" . $this->db->dbprefix('sale_items') . "
							INNER JOIN " . $this->db->dbprefix('products') . " ON " . $this->db->dbprefix('products') . ".id = " . $this->db->dbprefix('sale_items') . ".product_id
							WHERE
								" . $this->db->dbprefix('sale_items') . ".sale_id = " . $this->db->dbprefix('sales') . ".id
						),
						0
					)
				) AS profit, payment_status", FALSE)
				->from('sales')
				->join('sale_items', 'sale_items.sale_id=sales.id', 'left')
				->join('warehouses', 'warehouses.id=sales.warehouse_id', 'left')
				->join('companies', 'companies.id=sales.customer_id','left')                
				->join('customer_groups','customer_groups.id=companies.customer_group_id','left')
				->where('erp_sales.id', $id)
				->group_by('sales.id');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getSalesByID($id){
		$this->db
				->select("erp_sales.id, date, reference_no, biller, customer,GROUP_CONCAT(" . $this->db->dbprefix('sale_items') . ".product_name SEPARATOR '___') as iname, GROUP_CONCAT(ROUND(".$this->db->dbprefix('sale_items') . ".quantity) SEPARATOR '___') as iqty, grand_total, paid, (grand_total-paid) as balance, payment_status", FALSE)
				->from('sales')
				->join('sale_items', 'sale_items.sale_id=sales.id', 'left')
				->join('warehouses', 'warehouses.id=sales.warehouse_id', 'left')
				->join('companies', 'companies.id=sales.customer_id','left')                
				->join('customer_groups','customer_groups.id=companies.customer_group_id','left')
				->where('erp_sales.id', $id)
				->group_by('sales.id');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getCategoryByID($id){
		$pp = "( SELECT pp.category_id as category, pi.product_id, SUM( pi.quantity ) purchasedQty, SUM( pi.subtotal ) totalPurchase from " . $this->db->dbprefix('products') . " pp
                left JOIN " . $this->db->dbprefix('purchase_items') . " pi on pp.id = pi.product_id
                group by pp.category_id
                ) PCosts";
		$sp = "( SELECT sp.category_id as category, si.product_id, SUM( si.quantity ) soldQty, SUM( si.subtotal ) totalSale from " . $this->db->dbprefix('products') . " sp
			left JOIN " . $this->db->dbprefix('sale_items') . " si on sp.id = si.product_id
			group by sp.category_id
			) PSales";
				
		$this->db
                ->select($this->db->dbprefix('categories') . ".id as cidd, " .$this->db->dbprefix('categories') . ".code, " . $this->db->dbprefix('categories') . ".name,
                    SUM( COALESCE( PCosts.purchasedQty, 0 ) ) as PurchasedQty,
                    SUM( COALESCE( PSales.soldQty, 0 ) ) as SoldQty,
                    SUM( COALESCE( PCosts.totalPurchase, 0 ) ) as TotalPurchase,
                    SUM( COALESCE( PSales.totalSale, 0 ) ) as TotalSales,
                    (SUM( COALESCE( PSales.totalSale, 0 ) )- SUM( COALESCE( PCosts.totalPurchase, 0 ) ) ) as Profit", FALSE)
                ->from('categories')
                ->join($sp, 'categories.id = PSales.category', 'left')
                ->join($pp, 'categories.id = PCosts.category', 'left')
				->where('categories.id', $id)
				->group_by('categories.id');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getWarehouseByID($id){
		$this->db->select('id, code, name, quantity');
		$this->db->from('products');
		$this->db->where('id', $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;		
	}
	
	public function getProductByID($id){
		$pp = "( SELECT 
					pi.date as date, 
					pi.product_id, 
					pi.purchase_id, 
					COALESCE(SUM(CASE WHEN pi.purchase_id <> 0 THEN (pi.quantity*(CASE WHEN ppv.qty_unit <> 0 THEN ppv.qty_unit ELSE 1 END)) ELSE 0 END),0) as purchasedQty, 
					SUM(pi.quantity_balance) as balacneQty, 
					SUM((CASE WHEN pi.option_id <> 0 THEN ppv.cost ELSE pi.net_unit_cost END) * pi.quantity_balance ) balacneValue, 
					SUM( pi.unit_cost * (CASE WHEN pi.purchase_id <> 0 THEN pi.quantity ELSE 0 END) ) totalPurchase
					FROM {$this->db->dbprefix('purchase_items')} pi 
					LEFT JOIN {$this->db->dbprefix('purchases')} p 
					ON p.id = pi.purchase_id
					LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
					ON ppv.id=pi.option_id ".$where_purchase." 
					WHERE pi.status <> 'ordered'
					GROUP BY pi.product_id ) PCosts";
		$sp = "( SELECT 
					si.product_id, 
					SUM( si.quantity*(CASE WHEN pv.qty_unit <> 0 THEN pv.qty_unit ELSE 1 END)) soldQty, 
					SUM( si.subtotal ) totalSale, 
					s.date as sdate FROM " . $this->db->dbprefix('sales') . " s 
					INNER JOIN " . $this->db->dbprefix('sale_items') . " si 
					ON s.id = si.sale_id 
					LEFT JOIN " . $this->db->dbprefix('product_variants') . " pv 
					ON pv.id=si.option_id ".$where_sale." 
					GROUP BY si.product_id ) PSales";
		$this->db
                ->select($this->db->dbprefix('products') . ".id AS idd, " . $this->db->dbprefix('products') . ".code, " . $this->db->dbprefix('products') . ".name,
				COALESCE( PCosts.purchasedQty, 0 ) AS qpurchase, COALESCE( PCosts.totalPurchase, 0 ) AS ppurchased,
				COALESCE (PSales.soldQty, 0) + COALESCE (
                        (
                            SELECT
                                SUM(si.quantity * ci.quantity)
                            FROM
                                erp_combo_items ci
                            INNER JOIN erp_sale_items si ON si.product_id = ci.product_id
                            WHERE
                                ci.item_code = ".$this->db->dbprefix('products') . ".code
                        ),
                        0
                ) AS qsale,
                COALESCE (PSales.totalSale, 0) AS psold,
                (COALESCE( PSales.totalSale, 0 ) - COALESCE( PCosts.totalPurchase, 0 )) as Profit,
				COALESCE( PCosts.balacneQty, 0 ) as qbalance, COALESCE( PCosts.balacneValue, 0 ) as pbalance", FALSE)
                ->from('products')
                ->join($sp, 'products.id = PSales.product_id', 'left')
                ->join($pp, 'products.id = PCosts.product_id', 'left')				
				->join('warehouses_products wp', 'products.id=wp.product_id', 'left')
				->join('categories', 'products.category_id=categories.id', 'left')
				->where('products.id', $id)
				->group_by("products.id");
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;	
	}

    function getQuantityByID($id){
        $this->db
             ->select('code, name, quantity, alert_quantity')
             ->from('products')
             ->where('alert_quantity > quantity', NULL)
             ->where(array('track_quantity'=> 1, 'products.id' => $id));
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;    
    }

    function getRegisterByID($id){
        $this->db
             ->select("date, closed_at, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ', " . $this->db->dbprefix('users') . ".last_name, '<br>', " . $this->db->dbprefix('users') . ".email) as user, cash_in_hand, CONCAT(total_cc_slips, ' (', total_cc_slips_submitted, ')') as c_slips, CONCAT(total_cheques, ' (', total_cheques_submitted, ')') as cheques, CONCAT(total_cash, ' (', total_cash_submitted, ')') as cash, note", FALSE)
             ->from("pos_register")
             ->where("pos_register.id", $id)
             ->join('users', 'users.id=pos_register.user_id', 'left');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false; 
    }
	function getSales(){
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false; 
    }
	function getLoansBySaleId($id){
		$this->db->where('sale_id',$id);
        $q = $this->db->get('loans');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false; 
    }
	
	function getAllSales($start_date, $end_date) {
		if($start_date) {
			$this->db->where('contract_date >=', $this->erp->fld($start_date));
		}
		if($end_date) {	
			$this->db->where('contract_date <=', $this->erp->fld($end_date));
		}
		$q = $this->db->get('sales');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
		}
		return false;
	}
	
	function getPLoanReports($start_date, $end_date, $customer, $reference_no, $user, $branch, $loan_type, $loan_term) {
		//$this->erp->print_arrays($customer);
		$this->db->select($this->db->dbprefix('sales').".id,".
						$this->db->dbprefix('sales').".reference_no,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) AS customer_name_en,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_kh, ".	
						$this->db->dbprefix('companies').".gender, ".
						$this->db->dbprefix('companies').".phone1, ".
						
						$this->db->dbprefix('sales').".contract_date,
						(".$this->db->dbprefix('sales').".interest_rate*100) as interest, ".
						$this->db->dbprefix('sales').".term, ".
						"COALESCE((SELECT u.username FROM erp_users u WHERE ".$this->db->dbprefix("sales").".by_co = u.id), '') AS co,".
						$this->db->dbprefix('sales').".grand_total,	
						(".$this->db->dbprefix('sales').".grand_total - ".$this->db->dbprefix("sales").".paid) as balance")
                ->from('sales')
				->join('users','sales.by_co=users.id','INNER')
				->join('companies','sales.customer_id=companies.id','INNER')
				->join('companies as myBranch', 'users.branch_id = myBranch.id');
		if ($start_date || $end_date) {
			$this->db->where($this->db->dbprefix('sales').'.contract_date BETWEEN "' . $this->erp->fld($start_date) . '" and "' . $this->erp->fld($end_date) . '"');
		}
		if ($customer) {
			$this->db->where('sales.customer_id', $customer);
		}
		if ($reference_no) {
			$this->db->like('sales.reference_no', $reference_no);
		}
		if ($user) {
			$this->db->where('sales.by_co', $user);
		}
		if ($branch) {
			$this->db->where('users.branch_id', $branch);
		}
		if ($loan_type) {
			//$this->db->where('sales.created_by', $loan_type);
		}
		if ($loan_term) {
			$this->db->where('sales.term', $loan_term);
		}
		$q = $this->db->get();
		
		if($q->num_rows() > 0) {
			foreach($q->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getBrancherSuggestions($term, $limit = 10)
    {
        $this->db->select("sales.customer_id,sales.customer", FALSE);
		$this->db->join('users','users.id=sales.created_by','LEFT');
		$this->db->join('companies as erp_cm','erp_cm.id=users.branch_id','LEFT');
		//$this->db->from('sales');
        $this->db->where(" (id LIKE '%" . $term . "%' OR customer LIKE '%" . $term . "%') ");
		$q = $this->db->get_where('sales', array('customer_id' => 'customer'), $limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getInstallmentReports($id)
    {
		$this->db
                ->select($this->db->dbprefix('sales').".id,
						".$this->db->dbprefix('sales').".reference_no,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other,					
						(SELECT Max(paid_date) FROM ".$this->db->dbprefix('loans')." WHERE sale_id=".$this->db->dbprefix('sales').".id) as p_date,
						SUM(".$this->db->dbprefix('loans').".paid_amount),
						".$this->db->dbprefix('cm').".name as branch,")
                ->from('sales')
				->join('loans','sales.id=loans.sale_id','INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('users','users.id=sales.created_by','LEFT')
				->join('companies as erp_cm','erp_cm.id=users.branch_id','LEFT')
				->where(array('sales.id'=>$id));
			$q = $this->db->get();
			if ($q->num_rows() > 0) {
				return $q->row();
			}
			return FALSE;
    }
	public function getContractByID($id)
    {
		$this->db
                ->select($this->db->dbprefix('sales').".id,".
						$this->db->dbprefix('sales').".reference_no,".
						$this->db->dbprefix('loan_groups').".name AS glname,
						CONCAT(".$this->db->dbprefix('companies').".family_name, 
						' ', 
						".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, 
						' ', 
						".$this->db->dbprefix('companies').".name_other) as customer_name_other, ".						
						$this->db->dbprefix('users').".username,myBranch.name,".		
						$this->db->dbprefix('sale_items').".product_name,
						CONCAT(TRUNCATE((".$this->db->dbprefix('sales').".interest_rate*100), 2), '', '%') as interest, 						
						CONCAT(TRUNCATE(".$this->db->dbprefix('sales').".term, 0), ' ', 'ថ្ងៃ') as term, 
						IF(".$this->db->dbprefix('sales').".frequency = 7, 'Weekly', IF(".$this->db->dbprefix('sales').".frequency = 14, 'Two Week', IF(".$this->db->dbprefix('sales').".frequency = 30, 'Monthly',''))), 						
						
						COALESCE(".$this->db->dbprefix('sales').".grand_total,0) AS grand_total,". 
						$this->db->dbprefix('sales').".mfi as mfi, ".
						$this->db->dbprefix('companies').".id as com_id")
                ->from('sales')
				->join('users','sales.created_by=users.id','INNER')
				->join('sale_items', 'sales.id = sale_items.sale_id', 'INNER')
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('companies as myBranch', 'users.branch_id= myBranch.id')
				->join('products', 'sale_items.product_id = products.id', 'INNER')
				->join('sale_services', 'sales.id = sale_services.sale_id', 'left')
				->join('variants', 'variants.id = sale_items.color', 'left')
				->join('quotes','quotes.id = sales.quote_id','left')
				->join('quote_items','quote_items.quote_id = quotes.id','left')
				->join('currencies','currencies.code = quote_items.currency_code','left')
				->join('loan_groups','loan_groups.id = sales.loan_group_id','left')
				->where($this->db->dbprefix('sales').'.payment_status','paid')
				->where($this->db->dbprefix('sales').'.id', $id)		
				->group_by('sales.id')
				->order_by('sales.id','DESC');
				$q = $this->db->get();
				if ($q->num_rows() > 0) {
					return $q->row();
				}
				return FALSE;	
    }
	
	/*=========installment Reports=================*/
	
	public function getSettings()
    {
        $q = $this->db->get('settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getInstallments($start_date, $end_date, $user_query, $branch_query){
		$settings 		= $this->getSettingCurrncy();		
		$setting 		= $this->getSettings();
		$penalty_days   = $setting->penalty_days?$setting->penalty_days:'';
		$penalty_amount = $setting->penalty_amount? $setting->penalty_amount:0;
		$penalty_types  = $setting->penalty_types;
		$rate    	    = $settings->rate;
		$this->db                
				->select($this->db->dbprefix('sales').".id,".
						$this->db->dbprefix('sales').".reference_no,".
						$this->db->dbprefix('loan_groups').".name AS glname,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other, 
						".$this->db->dbprefix('companies').".phone1,".$this->db->dbprefix('companies').".phone2,
						CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) as co_name,myBranch.name,
						".$this->db->dbprefix('companies').".house_no,
						".$this->db->dbprefix('companies').".state AS Cus_address, 
						DATE_FORMAT(".$this->db->dbprefix('l_date').".dateline,'%d-%m-%Y') as ins_date,
						DATE_FORMAT(NOW(),'%d-%m-%Y %h:%i:%s'),".
						$penalty_days .", 
						IF(DATE_ADD(".$this->db->dbprefix('loans').".dateline, INTERVAL ".$penalty_days." DAY) < CURDATE(), DATEDIFF(CURDATE(), ".$this->db->dbprefix('loans').".dateline), '') as due_days, 
						".$this->db->dbprefix('loans').".principle * (".$settings->rate ." / ".$this->db->dbprefix('currencies').".rate ) AS principle,
						".$this->db->dbprefix('loans').".balance * (".$settings->rate ." / ".$this->db->dbprefix('currencies').".rate ) AS balance,
						".$this->db->dbprefix('loans').".interest * (".$settings->rate ." / ".$this->db->dbprefix('currencies').".rate ) AS interest,
						".$this->db->dbprefix('sales').".term / (".$this->db->dbprefix('sales').".frequency) As term,
						".$this->db->dbprefix('loans').".period As period,
						".$this->db->dbprefix('loans').".principle As principles,
						".$this->db->dbprefix('loans').".balance AS balances,
						".$this->db->dbprefix('loans').".interest AS interest_rate,
						".$this->db->dbprefix('currencies').".name as cname,
						".$this->db->dbprefix('sales').".total as total,
						".$this->db->dbprefix('sale_items').".currency_code as currency_code,
						((IF(DATE_ADD(".$this->db->dbprefix('loans').".dateline, INTERVAL ".$penalty_days." DAY) < CURDATE(), (DATEDIFF(CURDATE(), ".($this->db->dbprefix('loans').".dateline)) * ".(($penalty_types=="Percentage")? $penalty_amount ." * (SELECT SUM(principle) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id)":($penalty_amount))).", ''))* (".$this->db->dbprefix('currencies').".rate / ".$rate .")) as penalty_amounts,
						(SELECT SUM(payment) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id AND paid_amount = 0) as remaining,
						(SELECT SUM(owed) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id ) as owed,
						(SELECT SUM(amount) FROM ".$this->db->dbprefix('sale_services')." WHERE sale_id = ".$this->db->dbprefix('sales').".id ) AS services
						");
        $this->db->from('sales');
		$this->db->join('loans','sales.id=loans.sale_id','INNER');
		$this->db->join('loans as erp_l_date','sales.id=erp_l_date.sale_id','INNER');
		$this->db->join('sale_items', 'sales.id = sale_items.sale_id', 'INNER');
		$this->db->join('companies', 'sales.customer_id = companies.id', 'INNER');
		//$this->db->join('addresses AS erp_villages','companies.village = erp_villages.code', 'left');
		//$this->db->join('addresses AS erp_sangkats','companies.sangkat = erp_sangkats.code', 'left');
		$this->db->join('sale_services', 'sales.id = sale_services.sale_id', 'LEFT');
		$this->db->join('quotes','quotes.id = sales.quote_id','left');
		$this->db->join('users','sales.by_co = users.id','INNER');
		$this->db->join('companies AS myBranch', 'sales.branch_id= myBranch.id', 'left');
		$this->db->join('quote_items','quote_items.quote_id = quotes.id','left');
		$this->db->join('currencies','currencies.code = quote_items.currency_code','left');
		$this->db->join('loan_groups','loan_groups.id = sales.loan_group_id','left');
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->db->where('sales.branch_id', $this->session->branch_id);
		}
		if ($start_date || $end_date) {
			$this->db->where($this->db->dbprefix('l_date').'.dateline BETWEEN "' . $this->erp->fld($start_date) . '" and "' . $this->erp->fld($end_date) . '"');
		}
		if ($user_query) {
			$this->db->where('sales.by_co', $user_query);
		}
		if ($branch_query) {
			$this->db->where('sales.branch_id', $branch_query);
		}
		$this->db->where('erp_sales.sale_status','activated');
		$this->db->where('sales.status','loans');
		$this->db->where('loans.dateline =', date('Y-m-d'));
		$this->db->where('loans.paid_amount', 0);
		$this->db->group_by('sales.id');
		$this->db->order_by('sales.id','DESC');
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}
	
	public function LateInstallments($start_date, $end_date, $user_query, $branch_query){
		$settings 		= $this->getSettingCurrncy();		
		$setting 		= $this->getSettings();
		$penalty_days   = $setting->penalty_days?$setting->penalty_days:'';
		$penalty_amount = $setting->penalty_amount? $setting->penalty_amount:0;
		$penalty_types  = $setting->penalty_types;
		$rate    	    = $settings->rate;
		$this->db
			->select($this->db->dbprefix('sales').".id,".
					$this->db->dbprefix('sales').".reference_no,".
					$this->db->dbprefix('loan_groups').".name AS glname,
					CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
					CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other, 
					".$this->db->dbprefix('companies').".phone1,".$this->db->dbprefix('companies').".phone2,
					CONCAT(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) as co_name,myBranch.name,
					".$this->db->dbprefix('companies').".house_no,
					".$this->db->dbprefix('companies').".state AS Cus_address, 
					DATE_FORMAT(".$this->db->dbprefix('l_date').".dateline,'%d-%m-%Y') as ins_date,
					DATE_FORMAT(NOW(),'%d-%m-%Y %h:%i:%s'),".
					$penalty_days .", 
					IF(DATE_ADD(".$this->db->dbprefix('loans').".dateline, INTERVAL ".$penalty_days." DAY) < CURDATE(), DATEDIFF(CURDATE(), ".$this->db->dbprefix('loans').".dateline), '') as due_days, 
					".$this->db->dbprefix('loans').".principle * (".$settings->rate ." / ".$this->db->dbprefix('currencies').".rate ) AS principle,
					".$this->db->dbprefix('loans').".balance * (".$settings->rate ." / ".$this->db->dbprefix('currencies').".rate ) AS balance,
					".$this->db->dbprefix('loans').".interest * (".$settings->rate ." / ".$this->db->dbprefix('currencies').".rate ) AS interest,
					".$this->db->dbprefix('sales').".term / (".$this->db->dbprefix('sales').".frequency) As term,
					".$this->db->dbprefix('loans').".period As period,
					".$this->db->dbprefix('loans').".principle As principles,
					".$this->db->dbprefix('loans').".balance AS balances,
					".$this->db->dbprefix('loans').".interest AS interest_rate,
					".$this->db->dbprefix('currencies').".name as cname,
					".$this->db->dbprefix('sales').".total as total,
					".$this->db->dbprefix('sale_items').".currency_code as currency_code,
					((IF(DATE_ADD(".$this->db->dbprefix('loans').".dateline, INTERVAL ".$penalty_days." DAY) < CURDATE(), (DATEDIFF(CURDATE(), ".($this->db->dbprefix('loans').".dateline)) * ".(($penalty_types=="Percentage")? $penalty_amount ." * (SELECT SUM(principle) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id)":($penalty_amount))).", ''))* (".$this->db->dbprefix('currencies').".rate / ".$rate .")) as penalty_amount,
					(SELECT SUM(payment) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id AND paid_amount = 0) as remaining,
					(SELECT SUM(owed) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id ) as owed,
					(SELECT SUM(amount) FROM ".$this->db->dbprefix('sale_services')." WHERE sale_id = ".$this->db->dbprefix('sales').".id ) AS services
					");
        $this->db->from('sales');
		$this->db->join('loans','sales.id=loans.sale_id','INNER');
		$this->db->join('loans as erp_l_date','sales.id=erp_l_date.sale_id','INNER');
		$this->db->join('sale_items', 'sales.id = sale_items.sale_id', 'INNER');
		$this->db->join('companies', 'sales.customer_id = companies.id', 'INNER');
		//$this->db->join('addresses AS erp_villages','companies.village = erp_villages.code', 'left');
		//$this->db->join('addresses AS erp_sangkats','companies.sangkat = erp_sangkats.code', 'left');
		$this->db->join('sale_services', 'sales.id = sale_services.sale_id', 'LEFT');
		$this->db->join('quotes','quotes.id = sales.quote_id','left');
		$this->db->join('users','sales.by_co = users.id','INNER');
		$this->db->join('companies AS myBranch', 'sales.branch_id= myBranch.id', 'left');
		$this->db->join('quote_items','quote_items.quote_id = quotes.id','left');
		$this->db->join('currencies','currencies.code = quote_items.currency_code','left');
		$this->db->join('loan_groups','loan_groups.id = sales.loan_group_id','left');
		if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
			$this->db->where('sales.branch_id', $this->session->branch_id);
		}
		if ($start_date || $end_date) {
			$this->db->where($this->db->dbprefix('l_date').'.dateline BETWEEN "' . $this->erp->fld($start_date) . '" and "' . $this->erp->fld($end_date) . '"');
		}
		if ($user_query) {
			$this->db->where('sales.by_co', $user_query);
		}
		if ($branch_query) {
			$this->db->where('sales.branch_id', $branch_query);
		}
		$this->db->where('erp_sales.sale_status','activated');
		$this->db->where('sales.status','loans');
		$this->db->where('loans.dateline <', date('Y-m-d'));
		$this->db->where('loans.paid_amount', 0);
		$this->db->group_by('sales.id');
		$this->db->order_by('sales.id','DESC');
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}

	
	public function getServicesBySaleID($id = NULL)
	{
		$q = $this->db->get_where('sale_services', array('sale_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
		return FALSE;
	}
	public function getLoanOwedBySaleId($id = NULL) {
		$this->db->select('owed');
		$this->db->order_by('period','DESC');
		$this->db->where('paid_amount !=', 0);
		$this->db->where(array('sale_id' => $id),1);
		$q = $this->db->get('loans');
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	/*public function getLoanBySaleId($id = NULL) {
		$this->db->select('SUM(balance)');
		$this->db->where('paid_amount =', 0);
		$this->db->where(array('sale_id' => $id),1);
		$this->db->group_by('sale_id');
		$q = $this->db->get('loans');
		
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}*/
	
	public function getStaffPerformance($user_query, $branch_query){
		$settings = $this->getSettingCurrncy();
		$rate = $settings->rate;
		$setting = $this->settings_model->getSettings();		
		$penalty_days = $setting->penalty_days?$setting->penalty_days:'';
		$penalty_amount = $setting->penalty_amount? $setting->penalty_amount:0;
		$penalty_types = $setting->penalty_types;
		$this->db
                ->select($this->db->dbprefix('sales').".id,
						".$this->db->dbprefix('quotes').".created_by,
						".$this->db->dbprefix('quotes').".branch_id,
						COUNT(".$this->db->dbprefix('quotes').".created_by) as no_client,
						CONCAT(".$this->db->dbprefix('users').".first_name,' ',".$this->db->dbprefix('users').".last_name) AS account,
						(SELECT COUNT(".$this->db->dbprefix('quotes').".created_by) FROM ".$this->db->dbprefix('sales')." WHERE quote_id = ".$this->db->dbprefix('quotes').".id AND grand_total <> 0 AND sale_status='activated') as client_disburse,
						SUM(".$this->db->dbprefix('sales').".grand_total) AS loan_disburse,
						SUM((SELECT SUM(principle) FROM ".$this->db->dbprefix('loans')." WHERE sale_id = ".$this->db->dbprefix('sales').".id AND paid_amount = 0)) as outstanding,
						SUM((SELECT SUM(principle_amount) FROM ".$this->db->dbprefix('payments')." WHERE sale_id = ".$this->db->dbprefix('sales').".id)) as principles,
						SUM((SELECT SUM(interest_amount) FROM ".$this->db->dbprefix('payments')." WHERE sale_id = ".$this->db->dbprefix('sales').".id )) as interest,
						SUM((SELECT SUM(service_amount) FROM ".$this->db->dbprefix('payments')." WHERE sale_id = ".$this->db->dbprefix('sales').".id )) as service_fee,
						SUM((SELECT SUM(penalty_amount) FROM ".$this->db->dbprefix('payments')." WHERE sale_id = ".$this->db->dbprefix('sales').".id )) as penalty")
                ->from('sales')
				->join('quotes','quotes.id = sales.quote_id','INNER')
				->join('users','quotes.created_by=users.id','INNER')
				->where('erp_sales.status','loans')
				->group_by('quotes.created_by');
				
				if($this->GP && !($this->Owner || $this->Admin) && $this->session->view_right == 0) {
					$this->db->where('sales.branch_id', $this->session->branch_id);
				}
				if ($user_query) {
					$this->db->where('quotes.created_by', $user_query);
				}
				if ($branch_query) {
					$this->db->where('quotes.branch_id', $branch_query);
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
	
	public function getBranches(){	
		$this->db->where('group_name','biller');
		//$this->db->where('id !=',1);
		$q = $this->db->get('companies');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getDailyBranches(){	
		$this->db->where('group_name','biller');
		//$this->db->join('payments',' companies.id = payments.biller_id','left');
		$q = $this->db->get('companies');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getCoDisburse($branch_id){
		$this->db->select('users.id,sales.branch_id,users.first_name,users.last_name'); 
		$this->db->join('sales','sales.by_co =users.id','INNER');
		$this->db->join('payments','sales.id =payments.sale_id','INNER');
		$this->db->where('sales.branch_id',$branch_id);
		$this->db->where('payments.date =', date('Y-m-d'));
		$this->db->group_by('users.id');
		$this->db->order_by('users.id','DESC');
		$q = $this->db->get('users');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getCoAllDisburse($branch_id){
		$this->db->select('users.id,sales.branch_id,users.first_name,users.last_name'); 
		$this->db->join('sales','sales.by_co =users.id','INNER');
		$this->db->join('payments','sales.id =payments.sale_id','INNER');
		$this->db->where('sales.branch_id',$branch_id);
		$this->db->group_by('users.id');
		$this->db->order_by('users.id','DESC');
		$q = $this->db->get('users');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getUser($branch_id){
		$this->db->select('users.id,sales.branch_id,users.first_name,users.last_name'); 
		$this->db->join('sales','sales.by_co =users.id','INNER');
		$this->db->where('sales.branch_id',$branch_id);
		$this->db->group_by('users.id');
		$this->db->order_by('users.id','DESC');
		$q = $this->db->get('users');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getCoReportByBranch($branch_id){
        $this->db->select('id,first_name,last_name');
		$this->db->where('branch_id',$branch_id);
		//$this->db->where('id !=',1);
		$q = $this->db->get('users');
        if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;
	}

	public function getSaleByUserID($user_id,$start_date,$end_date,$user,$branch_query){	
		$this->db->select('sales.id, sales.by_co as co_id,sales.branch_id,CONCAT(erp_companies.family_name_other," ",erp_companies.name_other) as cus_name,
						   erp_sales.date as date,
						   erp_sales.approved_date as approved_date,
						   erp_sales.grand_total as l_disburse,
						   CONCAT(TRUNCATE((erp_sales.interest_rate*100), 2)," ", "%") AS interest,
						   CONCAT(TRUNCATE(erp_sales.term, 0)," ", "Days")  AS term,
						   (SELECT SUM(principle_amount) FROM erp_payments WHERE sale_id = erp_sales.id AND type = "received") AS principle_collection,
						   (SELECT SUM(interest_amount) FROM erp_payments WHERE sale_id = erp_sales.id AND type = "received") AS interest_collection,
						   (SELECT SUM(service_amount) FROM erp_payments WHERE sale_id = erp_sales.id AND type = "received") AS service_collection,
						   (SELECT SUM(penalty_amount) FROM erp_payments WHERE sale_id = erp_sales.id AND type = "received") AS penalty_collection');
		$this->db->where('sales.by_co',$user_id);
		$this->db->order_by('sales.id','DESC'); 
		$this->db->join('users','sales.by_co = users.id','INNER');
		$this->db->join('companies', 'sales.customer_id = companies.id', 'INNER');
		
		if ($start_date || $end_date) {
			$this->db->where('erp_sales.approved_date BETWEEN "' . $this->erp->fld($start_date) .' 00.00 " and "' . $this->erp->fld($end_date).' 23.59 "');
		}
		if ($user) {
			$this->db->where(' sales.by_co', $user);
		}
		if ($branch_query) {
			$this->db->where('sales.branch_id', $branch_query);
		}
		$q = $this->db->get('sales');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getDailyDisburseByCO($user_id,$start_date,$end_date,$user,$branch_query){
		$this->db->select('sales.id, sales.by_co as co_id,sales.branch_id,CONCAT(erp_companies.family_name_other," ",erp_companies.name_other) as cus_name,
						   erp_sales.date as date,
						   erp_sales.approved_date as approved_date,
						   erp_sales.grand_total as l_disburse,
						   CONCAT(TRUNCATE((erp_sales.interest_rate*100), 2)," ", "%") AS interest,
						   CONCAT(TRUNCATE(erp_sales.term, 0)," ", "Days")  AS term,
						   sales.reference_no,
						   sales.frequency,
						   payments.amount as disburse_amount,
						   payments.service_amount,
						   payments.date as disburse_date,
						');
		$this->db->where('sales.by_co',$user_id);
		$this->db->where('payments.paid_type','Disburse');
		$this->db->where('payments.date =', date('Y-m-d'));
		$this->db->order_by('sales.id','DESC'); 
		$this->db->join('users','sales.by_co = users.id','INNER');
		$this->db->join('payments','sales.id = payments.sale_id','left');
		$this->db->join('companies', 'sales.customer_id = companies.id', 'INNER');
		
		if ($start_date || $end_date) {
			$this->db->where('erp_sales.approved_date BETWEEN "' . $this->erp->fld($start_date) .' 00.00 " and "' . $this->erp->fld($end_date).' 23.59 "');
		}
		if ($user) {
			$this->db->where('sales.by_co', $user);
		}
		if ($branch_query) {
			$this->db->where('sales.branch_id', $branch_query);
		}
		$q = $this->db->get('sales');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getDisburseByCO($user_id,$start_date,$end_date,$user,$branch_query){
		$this->db->select('sales.id, sales.by_co as co_id,sales.branch_id,CONCAT(erp_companies.family_name_other," ",erp_companies.name_other) as cus_name,
						   erp_sales.date as date,
						   erp_sales.approved_date as approved_date,
						   erp_sales.grand_total as l_disburse,
						   CONCAT(TRUNCATE((erp_sales.interest_rate*100), 2)," ", "%") AS interest,
						   CONCAT(TRUNCATE(erp_sales.term, 0)," ", "Days")  AS term,
						   sales.reference_no,
						   sales.frequency,
						   payments.amount as disburse_amount,
						   payments.service_amount,
						   payments.date as disburse_date,
						');
		$this->db->where('sales.by_co',$user_id);
		$this->db->where('payments.paid_type','Disburse'); 
		$this->db->order_by('sales.id','DESC'); 
		$this->db->join('users','sales.by_co = users.id','INNER');
		$this->db->join('payments','sales.id = payments.sale_id','left');
		$this->db->join('companies', 'sales.customer_id = companies.id', 'INNER');
		
		if ($start_date || $end_date) {
			$this->db->where('erp_sales.approved_date BETWEEN "' . $this->erp->fld($start_date) .' 00.00 " and "' . $this->erp->fld($end_date).' 23.59 "');
		}
		if ($user) {
			$this->db->where('sales.by_co', $user);
		}
		if ($branch_query) {
			$this->db->where('sales.branch_id', $branch_query);
		}
		$q = $this->db->get('sales');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getAllCreditOfficer(){
		$q = $this->db->get_where('users');
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	public function getAllLoansByCreditOfficer(){
		$q = $this->db->get_where('sales');
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getBranchIDName(){
		$this->db->query('SET SQL_BIG_SELECTS=1'); 
		$this->db->select('companies.id, companies.name,sangkat.description as sangkat, district.description as district, state.description as state');
		$this->db->join('addresses as sangkat','sangkat.code = companies.sangkat','left');
		$this->db->join('addresses as district','district.code = companies.district','left');
		$this->db->join('addresses as state','state.code = companies.state','left');
		$this->db->where('companies.group_name','biller');
		$this->db->order_by('companies.id','ASC');
		$q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;		
	}
	
	public function getViewBranchByID($id){
		$this->db->select('branches.id, branches.amount, gl_charts.accountcode,gl_charts.accountname');
		$this->db->join('gl_charts','gl_charts.accountcode = branches.account_code','left');
		$this->db->where(array('branches.id' => $id));
		$this->db->where('gl_charts.bank', 1);
		$q = $this->db->get('branches');
        if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;
	}
	
	public function getPaymentBySaleID($user_id,$start_date,$end_date,$user,$branch_query){
		$this->db->select('sales.by_co AS co_id,sales.branch_id,CONCAT(erp_companies.family_name_other," ",erp_companies.name_other) as cus_name,
						   erp_payments.date AS date,
						   SUM(erp_payments.principle_amount) AS principle_collection,
						   SUM(erp_payments.interest_amount) AS interest_collection,
						   SUM(erp_payments.service_amount) AS service_collection,
						   SUM(erp_payments.penalty_amount) AS penalty_collection,
						   sales.frequency');
		$this->db->where('sales.by_co',$user_id);
		$this->db->where('payments.paid_type','Loans Received');
		$this->db->where('payments.type','Received'); 
		$this->db->group_by('payments.sale_id');
		$this->db->order_by('sales.id','DESC');
		$this->db->join('sales','sales.id = payments.sale_id','INNER'); 
		$this->db->join('companies','companies.id = sales.customer_id','INNER');
		/*if($start_date && $end_date){
			$this->db->where('payments.date >="'.$start_date.' 00.00" AND payments.date <="'.$end_date.' 23.59"');
		}*/
		
		if ($start_date || $end_date) {
			$this->db->where('payments.date BETWEEN "' . $this->erp->fld($start_date) .' 00.00 " and "' . $this->erp->fld($end_date).' 23.59 "');
		}
		if ($user) {
			$this->db->where('sales.by_co', $user);
		}
		if ($branch_query) {
			$this->db->where('sales.branch_id', $branch_query);
		}
		
		$q = $this->db->get('payments');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getLastDate($table,$field){
		$this->db->select("MAX(date_format($field,'%Y-%m-%d')) as datt");
		$q = $this->db->get("$table");
		if($q->num_rows()>0){
			return $q->row()->datt;
		}
		return false;
	}
	public function getShareholderByID($id){
		$this->db->where(array('id' => $id));
		$q=$this->db->get('erp_capitals');
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	public function getPaidDate(){
		$this->db->select('MAX(date) as p_date');
		$q=$this->db->get('payments');
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	public function getReference($reference=null){
		$this->db->select('id,reference_no');
		$q = $this->db->get('sales');
		if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
		return FALSE;
	}
	public function getMaxPeriod(){
		$this->db->select('MIN(period) as period, MIN(dateline) as date');
		$this->db->where('paid_amount','0');
		$this->db->group_by('sale_id');
		$q=$this->db->get('loans');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getOutstanding($customer,$reference_query,$user_query,$branch_query)
	{
		$period = $this->getMaxPeriod();
		//$arr_peroid = array();
		for($i=0;$i<=count($period);$i++)
		{
			if(!is_null($period[$i]->period)){
				$arr_peroid[] = $period[$i]->period; 
			}
		}
		$this->db
                ->select($this->db->dbprefix('loans').".sale_id,
						".$this->db->dbprefix('sales').".reference_no,
						".$this->db->dbprefix('sale_items').".currency_code,
						MIN(DATE_FORMAT(".$this->db->dbprefix('loans').".dateline,'%d-%m-%Y')) as date,
						CONCAT(".$this->db->dbprefix('companies').".family_name,' ',".$this->db->dbprefix('companies').".name) as customer,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other,' ',".$this->db->dbprefix('companies').".name_other) as other_name,
						myBranch.name as branch,
						CONCAT(".$this->db->dbprefix('users').".first_name,' ',".$this->db->dbprefix('users').".last_name) AS cname,
						SUM(COALESCE(".$this->db->dbprefix('loans').".principle,0)) as outstanding_amt,
						".$this->db->dbprefix('currencies').".name as cur_name")
                ->from('loans')
				->join('sales','loans.sale_id = sales.id','LEFT')
				->join('companies','sales.customer_id=companies.id','LEFT')
				->join('companies AS myBranch', 'sales.branch_id= myBranch.id', 'left')
				->join('quotes','sales.quote_id=quotes.id','LEFT')
				->join('users','sales.by_co=users.id','INNER')
				->join('sale_items','sale_items.sale_id = sales.id','left')
				->join('currencies','currencies.code = sale_items.currency_code','left')
				->where('loans.paid_amount','0')
				->order_by('loans.id','DESC')
				->group_by('loans.sale_id');
				 
				if ($customer) {
					$this->db->where('sales.customer_id', $customer);
				}
				if ($reference_query) {
					$this->db->where('sales.reference_no', $reference_query);
				}
				if ($user_query) {
					$this->db->where('sales.by_co', $user_query);
				}
				if ($branch_query) {
					$this->db->where('sales.branch_id', $branch_query);
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
	public function getCompanyByID($id)
    {
        $q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getCustomerSuggestions($term, $limit = 10)
    {
        $this->db->select("id, CONCAT(family_name, ' ', name) as text", FALSE);
        $this->db->where(" (id LIKE '%" . $term . "%' OR name LIKE '%" . $term . "%' OR company LIKE '%" . $term . "%' OR family_name LIKE '%" . $term . "%') ");
        $q = $this->db->get_where('companies', array('group_name' => 'customer'), $limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	 
	public function getOutstandingByCO($co_id, $loans_term) { 
		$this->db->select('SUM(principle) as total_outstanding ');
        $this->db->where('loans.by_co', $co_id);
		$this->db->where('loans.paid_amount',0);
		//$this->db->where('sales.frequency',$loans_term);
		$this->db->join('sales','sales.id = loans.sale_id','left');
		$q=$this->db->get('loans');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getNewDisburse($co_id){
		//$this->db->select('SUM(service_amount) as service_amount');
		$this->db->where('by_co', $co_id);
		//$this->db->where('paid_type','Loans Received');
		$this->db->where('date', date('Y-m-d'));		
		$q = $this->db->get('payments');
        if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	function getAllPaymentBySaleID($sale_id){
		$this->db->where('paid_type','Loans Received');
		$this->db->where('sale_id', $sale_id);
		$q = $this->db->get('payments');
        if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	public function getSaleItemByID($id)
    {
        $q = $this->db->get_where('sale_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
}
