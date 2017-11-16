<?php defined('BASEPATH') OR exit('No direct script access allowed');

class transfer_money_model extends CI_Model
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
	public function getMoneyTransfer($ids){
		$q = $this->db->get_where('money_transfers', array('id' => $ids),1);
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	public function insert($data){
		if($data) {
			if($this->db->insert('money_transfers',$data)){			
				$getBranch1 = $this->getBranchByID($data['from_branch_id']);
				$getBranch2 = $this->getBranchByID($data['to_branch_id']);
				//$this->db->update('companies', array('amount' => ($getBranch1->amount - $data['amount'])), array('id' => $data['from_branch_id']));
				//$this->db->update('companies', array('amount' => ($getBranch2->amount + $data['amount'])), array('id' => $data['to_branch_id']));				
				if ($this->site->getReference('to') == $data['reference']) {
					$this->site->updateReference('to');
				}
			}
			return true;
		}
		return false;
	}
	
	public function update($id,$data){
		if($data){
			$getTransfer = $this->getMoneyTransfer($id); //get old tb transfer_money_model
			$getoldBranch1 = $this->getBranchByID($getTransfer->from_branch_id); // get old from_branch
			$getoldBranch2 = $this->getBranchByID($getTransfer->to_branch_id);	// get old to branch
			$oldbranch2_amount = $getoldBranch2->amount - $getTransfer->amount;
			if($this->db->update('money_transfers',$data, array('id' => $id))){
				//$this->db->update('companies', array('amount'=> ($getoldBranch1->amount + $getTransfer->amount)),array('id'=>$getoldBranch1->id));
				//$this->db->update('companies', array('amount'=> ($oldbranch2_amount)),array('id'=>$getoldBranch2->id));				
				$getBranch1 = $this->getBranchByID($data['from_branch_id']);		// get new from branch
				$getBranch2 = $this->getBranchByID($data['to_branch_id']);			// get new to branch
				$newbranch1amount = $getBranch1->amount - $data['amount'] ;
				//$this->db->update('companies', array('amount' => ($newbranch1amount)), array('id' => $data['from_branch_id']));
				//$this->db->update('companies', array('amount' => ($getBranch2->amount + $data['amount'])), array('id' => $data['to_branch_id']));
			}
			return true;
		}
		return false;
	}
	
	public function delete($id){
		$this->db->where('id',$id);
		$d=$this->db->delete('money_transfers');
		if($d){
			return true;
		}
	}
	
	public function getBranchDName(){
		$this->db->select('id, name ');
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
	
	public function delete_transfer($id)
	{
        if ($this->db->delete("money_transfers", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	////$defualt_currency = $this->transfer_money_model->getSettingCurrncy();
	////$defualt_rate = $defualt_currency;
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
	
	public function getBranchinfo($id){
		$this->db->query("SET SQL_BIG_SELECTS=1");
        $this->db->select("money_transfers.id,fr_branch.company as fb,to_branch.company as tb,fr_branch.phone as fr_phone,to_branch.phone as to_phone,money_transfers.date,money_transfers.amount,gl_charts.accountname as  bank,
		CONCAT(fr_village.description , ' ',fr_commun.description , ' ',fr_district.description , ' ',fr_province.description) AS fr_address,
		CONCAT(to_village.description , ' ',to_commun.description , ' ',to_district.description , ' ',to_province.description) AS to_address");
		$this->db->join('companies as erp_fr_branch','erp_fr_branch.id = money_transfers.from_branch_id', 'left');
		$this->db->join('companies as erp_to_branch','erp_to_branch.id = money_transfers.to_branch_id','left');	
		$this->db->join('addresses as fr_province', 'fr_province.code = erp_fr_branch.state', 'left');
		$this->db->join('addresses as fr_district', 'fr_district.code = erp_fr_branch.district', 'left');
		$this->db->join('addresses as fr_commun', 'fr_commun.code = erp_fr_branch.sangkat', 'left');
		$this->db->join('addresses as fr_village', 'fr_village.code = erp_fr_branch.village', 'left');
		$this->db->join('addresses as to_province', 'to_province.code = erp_to_branch.state', 'left');
		$this->db->join('addresses as to_district', 'to_district.code = erp_to_branch.district', 'left');
		$this->db->join('addresses as to_commun', 'to_commun.code = erp_to_branch.sangkat', 'left');
		$this->db->join('addresses as to_village', 'to_village.code = erp_to_branch.village', 'left');
		$this->db->join('gl_charts', 'gl_charts.accountcode = money_transfers.bank_account', 'left');
        $this->db->where('money_transfers.id',$id);
		$q = $this->db->get('money_transfers');       
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getBranchAddress($id = NULL) {
		$this->db->query("SET SQL_BIG_SELECTS=1");
		 $this->db->select("money_transfers.id,tr_branch.phone,tr_branch.email,tr_branch.amount as fr_amount,to_branch.amount as to_amount,
		 CONCAT(fr_village.description , ' ',fr_commun.description , ' ',fr_district.description , ' ',fr_province.description) AS br_address");
		$this->db->join('companies as tr_branch','tr_branch.id = money_transfers.from_branch_id', 'left');
		$this->db->join('companies as to_branch','to_branch.id = money_transfers.to_branch_id', 'left');	
		$this->db->join('addresses as fr_province', 'fr_province.code = tr_branch.state', 'left');
		$this->db->join('addresses as fr_district', 'fr_district.code = tr_branch.district', 'left');
		$this->db->join('addresses as fr_commun', 'fr_commun.code = tr_branch.sangkat', 'left');
		$this->db->join('addresses as fr_village', 'fr_village.code = tr_branch.village', 'left');
        $this->db->where('money_transfers.id',$id);
		$q = $this->db->get('money_transfers');       
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getAjaxBranchBalance($branch_id, $bank_code){
		$this->db->select('amount');
		$this->db->where('id', $branch_id);
		$this->db->where('account_code', $bank_code);
		$this->db->from('branches');		
		$q = $this->db->get();       
		 if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
}
