<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Daily_Cash_Collection_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getPaymentByRef($id){
		$this->db->select('sale_services.amount as samount,companies.email as cemail,companies.phone1 as cphone,companies.address as caddr,payments.date,payments.reference_no,payments.note,payments.paid_by,companies.name as cname, loans.payment as installl_payment, loans.overdue_amount as penalty_amount, loans.other_amount as other_amount, payments.amount as total_paid
		');
		$this->db->from('payments');
		$this->db->join('loans', 'payments.loan_id = loans.id');
		$this->db->join('companies', 'companies.id = payments.biller_id');
		$this->db->join('sale_services', 'sale_services.sale_id = payments.sale_id');
		$this->db->where('payments.id', $id);
		
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getServiceByID($id){
		$this->db->select('services.description, sale_services.amount');
		$this->db->from('sale_services');
		$this->db->join('services', 'sale_services.services_id = services.id');
		$this->db->join('payments', 'payments.sale_id = sale_services.sale_id');
		$this->db->where('payments.id', $id);
		
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	public function getItemByIDs($id){
		$this->db->select('sale_items.product_name, sale_items.unit_price, sale_items.quantity');
		$this->db->from('sale_items');
		$this->db->join('payments', 'payments.sale_id = sale_items.sale_id');
		$this->db->where('payments.id', $id);
		
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
}
