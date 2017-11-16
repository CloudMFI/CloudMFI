<?php defined('BASEPATH') OR exit('No direct script access allowed');

class capital_model extends CI_Model
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
	public function getBranchByID($id){
		$q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getCapital($ids){
		$q = $this->db->get_where('capital', array('id' => $ids),1);
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	public function insert($data){
		if($data) {
			if($this->db->insert('capital',$data)){
				$getBranch = $this->getBranchByID($data['branch_id']);
				$this->db->update('companies', array('amount' => ($getBranch->amount + $data['amount'])), array('id' => $data['branch_id']));
				if ($this->site->getReference('cap') == $data['reference']) {
					$this->site->updateReference('cap');
				}
			}
			return true;
		}
		return false;
	}
	
	/*public function update($id,$data){
		if($data){
			$getCapital = $this->getCapital($id); 	/// Get old Data form Capital
			if($this->db->update('capital',$data, array('id' => $id))){
				if($data['branch_id']== $getCapital->branch_id){
					$this->db->update('companiess', array('amount' => 'amount -'. $getCapital->amount .' + '. $data['amount']), array('id' => $data['branch_id']));					
				}else{
					$this->db->update('companiess', array('amount' => 'amount -'. $getCapital->amount), array('id' => $getCapital->branch_id));
					$this->db->update('companiess', array('amount' => 'amount +'. $data['amount']), array('id' => $data['branch_id']));
				}
			}
			return true;
		}
		return false;
	}*/
	
	public function update($id,$data){
		if($data){
			$getCapital = $this->getCapital($id);
			$getBranch = $this->getBranchByID($getCapital->branch_id);
			if($this->db->update('capital',$data, array('id' => $id))){	
				$other_branch = $this->getBranchByID($data['branch_id']);
				$branchAmount = $getBranch->amount;
				$capitalAmount = $getCapital->amount;
				$amount = $branchAmount - $capitalAmount;
				if($data['branch_id']== $getCapital->branch_id){
					$this->db->update('companies', array('amount' => ($amount + $data['amount'])), array('id' => $data['branch_id']));	
				}else{
					$this->db->update('companies', array('amount' => ($other_branch->amount + $data['amount'])), array('id' => $data['branch_id']));
					$this->db->update('companies', array('amount' => $amount), array('id' => $getBranch->id));
				}
			}
			return true;
		}
		return false;
	}
	
	public function delete($id){
		$this->db->where('id',$id);
		$d=$this->db->delete('companies');
		if($d){
			return true;
		}
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
	public function getBankAccount(){
		$this->db->select('accountcode, accountname');
		$this->db->where('gl_charts.bank','1');
		$q = $this->db->get('gl_charts');
        if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;		
	}
	public function getShareholder(){
		$this->db->select('id, name');
		$this->db->where('companies.group_name','shareholder');
		$q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
        return FALSE;		
	}
	public function getCapitals($id){
		$this->db->select('capital.id,companies.name,c_branch.name as cname,gl_charts.accountname,currencies.name as c_name');
		$this->db->join('companies','companies.id=capital.shareholder_id','LEFT');
		$this->db->join('companies as c_branch','c_branch.id=capital.branch_id','LEFT');
		$this->db->join('currencies','currencies.code=capital.currency_code','LEFT');
		$this->db->join('gl_charts','gl_charts.accountcode=capital.bank_account','LEFT');
		$this->db->where('capital.id',$id);
		$q=$this->db->get('capital');
		 if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function delete_capital($id)
	{
        if ($this->db->delete("companies", array('id' => $id))) {
            return true;
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
	public function getCurrncyByCode($code){
		$q = $this->db->get_where('currencies', array('code' => $code),1);
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	
	public function getBranchAddress($id = NULL) {
		$this->db->query("SET SQL_BIG_SELECTS=1");
		 $this->db->select("branch_name.phone,branch_name.email,
		 CONCAT(fr_village.description , ' ',fr_commun.description , ' ',fr_district.description , ' ',fr_province.description) AS br_address");
		$this->db->join('companies as branch_name','branch_name.id = capital.branch_id', 'left');	
		$this->db->join('addresses as fr_province', 'fr_province.code = branch_name.state', 'left');
		$this->db->join('addresses as fr_district', 'fr_district.code = branch_name.district', 'left');
		$this->db->join('addresses as fr_commun', 'fr_commun.code = branch_name.sangkat', 'left');
		$this->db->join('addresses as fr_village', 'fr_village.code = branch_name.village', 'left');
        $this->db->where('capital.id',$id);
		$q = $this->db->get('capital');       
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
}
