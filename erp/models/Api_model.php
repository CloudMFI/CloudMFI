<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
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
	 
	
	public function GetquotesLists(){
		$this->db->select('quotes.id, quotes.reference_no, loan_groups.name AS glname,  
		CONCAT(erp_companies.family_name," ",erp_companies.name) AS customer_name_en,
		CONCAT(erp_companies.family_name_other," ",erp_companies.name_other) AS customer_name_other,
		quote_items.product_name,  quotes.quote_status, quotes.date, quotes.approved_date,
		CONCAT(erp_users.first_name," ",erp_users.last_name) AS coname, quote_items.currency_code,
		myBranch.name as branch_name, currencies.name as currency_name, quotes.total
		 
		');		 
			$this->db->from('quotes');
			$this->db->join('users','quotes.by_co=users.id','INNER'); 
			$this->db->join('companies','quotes.customer_id=companies.id','INNER');
			$this->db->join('companies as myBranch', 'quotes.branch_id = myBranch.id', 'left');
			$this->db->join('quote_items', 'quotes.id = quote_items.quote_id', 'left');
			$this->db->join('currencies','currencies.code = quote_items.currency_code','left');
			$this->db->join('loan_groups','loan_groups.id = quotes.loan_group_id','left');
			$this->db->where('quotes.status', 'loans');
			$this->db->order_by('quotes.id','DESC');
			$q = $this->db->get();   
			if ($q->num_rows() > 0) {
				foreach (($q->result()) as $row) {
					$data[] = $row;
				}
				return $data;
			}
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
}

	


	