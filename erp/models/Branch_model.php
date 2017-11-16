<?php defined('BASEPATH') OR exit('No direct script access allowed');

class branch_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
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
	public function insert($data){
		$i=$this->db->insert('companies',$data);
		if($i){
			return true;
		}
	}
	public function delete($id){
		$this->db->where('id',$id);
		$d=$this->db->delete('companies');
		if($d){
			return true;
		}
	}
	public function getBranchByID($id){
		$this->db->where('id',$id);
		$q=$this->db->get('companies');
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	public function update($id,$data){
		$this->db->where('id',$id);
		$u=$this->db->update('companies',$data);
		if($u){
			return true;
		}
		return false;
	}
	public function delete_branch($id)
	{
        if ($this->db->delete("companies", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
}
