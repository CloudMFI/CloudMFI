
	

<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Saving_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
	
	public function getBranchByID($id){
		$q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function addSavingAccount($data = array(), $items = array(), $documentsArray = array(), $customers = array(), $payment = array())
    {	
        
		if($guarantor_) {
			if($guarantor->insert('companies', $guarantor_))
			{
				$data['guarantor_id'] = $guarantor->insert_id();
			}
		}		
		
		if($customers) {				
			if ($this->db->insert('companies', $customers)) {
				$cid = $this->db->insert_id();
				
			}
		}
		
		if($data) {
				$data['customer_id'] = $cid;
			if ($this->db->insert('sales', $data)) {				
				$sale_id = $this->db->insert_id();
				$branch = $this->getBranchByID($data['branch_id']);
				$newBranchAmount = $branch->amount + $data['grand_total'];
				$this->db->update('companies', array('amount' => $newBranchAmount ), array('id' => $data['branch_id']));
				
				//$reference = $this->site->getReference('sp');
				$payment['sale_id'] = $sale_id;
				$this->db->insert('payments', $payment);
				if ($this->site->getReference('sav')) {
					$this->site->updateReference('sav');
				}
				if ($this->site->getReference('sp')) {
					$this->site->updateReference('sp');
				}

				foreach($documentsArray as $docs)
				{
					$this->db->insert('sale_photos',array('sale_id' => $sale_id, 'name' => $docs['name'], 'type' => $docs['type']));
				}
				
				if($items) {
					$items['sale_id'] = $sale_id;
					if ($this->db->insert('sale_items', $items)) {
						
					}
				}
				return $sale_id;				
			}
		}
		
        return false;
    }
	
	public function getDocumentsBySaleID($sale_id){
		$q = $this->db->get_where('sale_photos', array('sale_id' => $sale_id));		
		foreach($q->result() as $row){
			$data[] = $row;
		}
		return isset($data) ?$data  : ('');
		
		return false;
	}
	
	public function getSaleByID($id=NULL)    {
        $q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getSaleItemBysaleID($ids){
		$q = $this->db->get_where('sale_items', array('sale_id' => $ids),1);
		 if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getContractBySaleId($ids){
		$this->db->select('companies.id, companies.amount');
		$this->db->join('companies','companies.id = sales.branch_id','left');
		$this->db->where('sales.id', $ids);
		$this->db->from('sales');
		$q = $this->db->get(); 
		 if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function addSaving($data = array()){
        if($data) {
			if($this->db->insert('payments',$data)){			
				$branch = $this->getContractBySaleId($data['sale_id']);
				$branch_amount = $branch->amount;
				$new_branchamount = ($branch_amount + $data['amount']);
				
				$sales = $this->getSaleById($data['sale_id']);	
				$savingAmount = $sales->grand_total;
				$NewsavingAmount = ($savingAmount + $data['amount']);
				
				$this->db->update('companies', array('amount' => $new_branchamount), array('id' => $branch->id));
				$this->db->update('sales',array('grand_total' => $NewsavingAmount,),array('id'=> $data['sale_id']));	
					
				if ($this->site->getReference('sp')) {
					$this->site->updateReference('sp');
				}
				
			}
			return true;
		}
		return false;
    }
	
}

	