<?php defined('BASEPATH') OR exit('No direct script access allowed');

class shareholder_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
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
	public function getShareholderByID($id){
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
	public function delete_shareholder($id)
	{
        if ($this->db->delete("companies", array('id' => $id))) {
            return true;
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
	public function getIdentifyTypeByID($id = NULL) {
		$this->db->select('companies.*,identify_types.name as ident_name');
		$this->db->where(array('companies.id' => $id));
		$this->db->join('identify_types','identify_types.id=companies.identify','left');		
        $q = $this->db->get('companies');
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return FALSE;
	}
	
	
}
