<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Interest_rate_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
	 public function getAllInterestRate()
    {
		$this->db->order_by("id", "desc");

        $q = $this->db->get('interest_rate');
								
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getAllTerms()
    {
		$this->db->order_by("id", "asc");

        $q = $this->db->get('terms');
								
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
}
