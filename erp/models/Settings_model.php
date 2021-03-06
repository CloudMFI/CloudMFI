<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
	
	
	public function get_service_income()
    {
		$this->db->select('gl_charts.accountname');
		$this->db->from('services');		
		$this->db->join('gl_charts', 'services.acc_service=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function get_accrued_service()
    {
		$this->db->select('gl_charts.accountname');
		$this->db->from('services');		
		$this->db->join('gl_charts', 'services.accrue_service=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function get_serviceIncome($id)
    {
		$this->db->select('gl_charts.accountname');
		$this->db->from('services');
		$this->db->where(array('id' => $id));
		$this->db->join('gl_charts', 'services.acc_service=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
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
	public function getAllTaxes(){
		$q = $this->db->query("SELECT * FROM erp_tax_rates ");
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
    public function updateLogo($photo)
    {
        $logo = array('logo' => $photo);
        if ($this->db->update('settings', $logo)) {
            return true;
        }
        return false;
    }

    public function updateLoginLogo($photo)
    {
        $logo = array('logo2' => $photo);
        if ($this->db->update('settings', $logo)) {
            return true;
        }
        return false;
    }

    public function getSettings()
    {
        $q = $this->db->get('settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getAccountSettings()
    {
        $q = $this->db->get('account_settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getDateFormats()
    {
        $q = $this->db->get('date_format');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function updateSetting($data)
    {
        $this->db->where('setting_id', '1');
        if ($this->db->update('settings', $data)) {
            return true;
        }
        return false;
    }

    public function addTaxRate($data)
    {
        if ($this->db->insert('tax_rates', $data)) {
            return true;
        }
        return false;
    }
	
	public function addFinancial($data)
    {
        if ($this->db->insert('services', $data)) {
            return true;
        }
        return false;
    }

    public function updateTaxRate($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('tax_rates', $data)) {
			$this->db->update('services', array('tax_rate' => $data['rate']/100), array('tax_id' => $id));
            return true;
        }
        return false;
    }

    public function getAllTaxRates()
    {
        $q = $this->db->get('tax_rates');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row){
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getTaxRateByID($id)
    {
        $q = $this->db->get_where('tax_rates', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addWarehouse($data)
    {
        if ($this->db->insert('warehouses', $data)) {
            return true;
        }
        return false;
    }
	
    public function updateWarehouse($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('warehouses', $data)) {
            return true;
        }
        return false;
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

    public function getWarehouseByID($id)
    {
        $q = $this->db->get_where('warehouses', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function deleteTaxRate($id)
    {
        if ($this->db->delete('tax_rates', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteInvoiceType($id)
    {
        if ($this->db->delete('invoice_types', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteWarehouse($id)
    {
        if ($this->db->delete('warehouses', array('id' => $id)) && $this->db->delete('warehouses_products', array('warehouse_id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function addCustomerGroup($data)
    {
        if ($this->db->insert('customer_groups', $data)) {
            return true;
        }
        return false;
    }

    public function updateCustomerGroup($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('customer_groups', $data)) {
            return true;
        }
        return false;
    }

    public function getAllCustomerGroups()
    {
        $q = $this->db->get('customer_groups');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCustomerGroupByID($id)
    {
        $q = $this->db->get_where('customer_groups', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function deleteCustomerGroup($id)
    {
        if ($this->db->delete('customer_groups', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getGroups()
    {
        $this->db->where('id >', 4);
        $q = $this->db->get('groups');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getGroupByID($id)
    {
        $q = $this->db->get_where('groups', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getGroupPermissions($id)
    {
        $q = $this->db->get_where('permissions', array('group_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function GroupPermissions($id)
    {
        $q = $this->db->get_where('permissions', array('group_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        return FALSE;
    }

    public function updatePermissions($id, $data = array())
    {
        if ($this->db->update('permissions', $data, array('group_id' => $id)) && $this->db->update('users', array('show_price' => $data['products-price'], 'show_cost' => $data['products-cost']), array('group_id' => $id))) {
            return true;
        }
        return false;
    }

    public function addGroup($data)
    {
        if ($this->db->insert("groups", $data)) {
            $gid = $this->db->insert_id();
            $this->db->insert('permissions', array('group_id' => $gid));
            return $gid;
        }
        return false;
    }

    public function updateGroup($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update("groups", $data)) {
            return true;
        }
        return false;
    }


    public function getAllCurrencies()
    {
        $q = $this->db->get('currencies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCurrencyByID($id)
    {
        $q = $this->db->get_where('currencies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addCurrency($data)
    {
        if ($this->db->insert("currencies", $data)) {
            return true;
        }
        return false;
    }

    public function updateCurrency($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update("currencies", $data)) {
            return true;
        }
        return false;
    }

    public function deleteCurrency($id)
    {
        if ($this->db->delete("currencies", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getAllCategories()
    {
        $q = $this->db->get("categories");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllSubCategories()
    {
        $q = $this->db->get("subcategories");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getSubcategoryDetails($id)
    {
        $this->db->select("subcategories.code as code, subcategories.name as name, categories.name as parent")
            ->join('categories', 'categories.id = subcategories.category_id', 'left')
            ->group_by('subcategories.id');
        $q = $this->db->get_where("subcategories", array('subcategories.id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSubCategoriesByCategoryID($category_id)
    {
        $q = $this->db->get_where("subcategories", array('category_id' => $category_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCategoryByID($id)
    {
        $q = $this->db->get_where("categories", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSubCategoryByID($id)
    {
        $q = $this->db->get_where("subcategories", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addCategory($name, $code, $photo, $mfi, $group_loan)
    {
        if ($this->db->insert("categories", array('code' => $code, 'name' => $name, 'image' => $photo, 'mfi' => $mfi, 'group_loan' => $group_loan))) {
            return true;
        }
        return false;
    }

    public function addSubCategory($category, $name, $code, $photo)
    {
        if ($this->db->insert("subcategories", array('category_id' => $category, 'code' => $code, 'name' => $name, 'image' => $photo))) {
            return true;
        }
        return false;
    }
	
	public function addType($data)
    {
        if ($this->db->insert("type", $data)) {
            return true;
        }
        return false;
    }

    public function updateCategory($id, $data = array(), $photo)
    {
        $categoryData = array('code' => $data['code'], 'name' => $data['name'], 'mfi' => $data['mfi'], 'group_loan'=>$data['group_loan']);
        if ($photo) {
            $categoryData['image'] = $photo;
        }
        $this->db->where('id', $id);
        if ($this->db->update("categories", $categoryData)) {
            return true;
        }
        return false;
    }

    public function updateSubCategory($id, $data = array(), $photo)
    {
        $categoryData = array(
            'category_id' => $data['category'],
            'code' => $data['code'],
            'name' => $data['name'],
        );
        if ($photo) {
            $categoryData['image'] = $photo;
        }
        $this->db->where('id', $id);
        if ($this->db->update("subcategories", $categoryData)) {
            return true;
        }
        return false;
    }

    public function deleteCategory($id)
    {
        if ($this->db->delete("categories", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteSubCategory($id)
    {
        if ($this->db->delete("subcategories", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getPaypalSettings()
    {
        $q = $this->db->get('paypal');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updatePaypal($data)
    {
        $this->db->where('id', '1');
        if ($this->db->update('paypal', $data)) {
            return true;
        }
        return FALSE;
    }

    public function getSkrillSettings()
    {
        $q = $this->db->get('skrill');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateSkrill($data)
    {
        $this->db->where('id', '1');
        if ($this->db->update('skrill', $data)) {
            return true;
        }
        return FALSE;
    }

    public function checkGroupUsers($id)
    {
        $q = $this->db->get_where("users", array('group_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function deleteGroup($id)
    {
        if ($this->db->delete('groups', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function addVariant($data)
    {
        if ($this->db->insert('variants', $data)) {
            return true;
        }
        return false;
    }

    public function updateVariant($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('variants', $data)) {
            return true;
        }
        return false;
    }

    public function getAllVariants()
    {
        $q = $this->db->get('variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getVariantByID($id)
    {
        $q = $this->db->get_where('variants', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function deleteVariant($id)
    {
        if ($this->db->delete('variants', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	
	public function getProductNames($term, $limit = 5)
    {
        $this->db->select('' . $this->db->dbprefix('products') . '.id, code, ' . $this->db->dbprefix('products') . '.name as name, ' . $this->db->dbprefix('products') . '.price as price, ' . $this->db->dbprefix('product_variants') . '.name as vname')
            ->where("type != 'combo' AND "
                . "(" . $this->db->dbprefix('products') . ".name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR
                concat(" . $this->db->dbprefix('products') . ".name, ' (', code, ')') LIKE '%" . $term . "%')");
        $this->db->join('product_variants', 'product_variants.product_id=products.id', 'left')
            ->where('' . $this->db->dbprefix('product_variants') . '.name', NULL)
            ->group_by('products.id')->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function insertBom($data)
    {
        if ($this->db->insert('bom', $data)) {
            $convert_id = $this->db->insert_id();
            return $convert_id;
        }
    }

	public function getConvertItemsById($bom_id){
		$this->db->select('bom_items.product_id,bom_items.bom_id,bom_items.quantity AS c_quantity ,(erp_products.cost * erp_bom_items.quantity) AS tcost, bom_items.status, products.cost AS p_cost');
		$this->db->join('products', 'products.id = bom_items.product_id', 'INNER');
		$this->db->where('bom_items.bom_id', $bom_id);
		$query = $this->db->get('bom_items');
		
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	}
	
	public function getConvertItemsDeduct($bom_id){
		$this->db->select('SUM(erp_products.cost * erp_bom_items.quantity) AS tcost, bom_items.status');
		$this->db->join('products', 'products.id = bom_items.product_id', 'INNER');
		$this->db->where('bom_items.bom_id', $bom_id);
		$this->db->where('bom_items.status', 'deduct');
		$query = $this->db->get('bom_items');
		
		if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
	}
	
	public function getConvertItemsAdd($bom_id){
		$this->db->select('bom_items.product_id,bom_items.bom_id, bom_items.quantity AS c_quantity ,(erp_products.cost * erp_bom_items.quantity) AS tcost, bom_items.status');
		$this->db->join('products', 'products.id = bom_items.product_id', 'INNER');
		$this->db->where('bom_items.bom_id', $bom_id);
		$this->db->where('bom_items.status', 'add');
		$query = $this->db->get('bom_items');
		
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	}
	
	public function getBOmByID($id)
    {
        $this->db->select('date, name, sum(erp_bom_items.quantity) as qty, cost, noted, created_by');
		$this->db->from('bom');
		$this->db->join('bom_items', 'bom_items.bom_id = bom.id');
		$this->db->where(array('bom.id'=> $id, 'bom_items.status'=>'add'));
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
             return $query->row();
        }
        return false;
    }
	
	public function getBOmByIDs($id)
    {
        $this->db->select('date, name, quantity, cost, noted, created_by, status, product_name');
		$this->db->from('bom');
		$this->db->join('bom_items', 'bom_items.bom_id = bom.id');
		$this->db->where('bom.id',$id);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
             return $query->result_array();
        }
        return false;
    }
	
	public function deleteBom($id)
    {
        if ($this->db->delete('bom', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	
	public function deleteBom_items($id)
    {
        if ($this->db->delete('bom_items', array('bom_id' => $id))) {
            return true;
        }
        return FALSE;
    }
	
	public function updateBom($id, $data)
    {
        $this->db->where('id', $id);
        if ($this->db->update('bom', $data)) {
            return true;
        }
        return FALSE;
    }
	
	public function updateBom_items($id, $data)
    {
        $this->db->where('product_id', $id);
        if ($this->db->update('bom_items', $data)) {
            return true;
        }
        return FALSE;
    }
	
	public function getRoomByID($id){
		$this->db->select('id,floor,name,ppl_number,description,inactive');
		$this->db->from('suspended');
		$this->db->where('id' , $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
	}
	//=============Insert Suppend===================
	public function addSuppend($data){
		//$this->erp->print_arrays($data);
		if ($this->db->insert('suspended', $data)) {
            return true;
        }
        return false;
	}
	//=============delete Suppend===================
	public function deleteSuppend($id){
		$q = $this->db->delete('suspended', array('id' => $id));
		if($q){
			return true;
		}else{
			return false;
		}
	}
	
	public function updateRooms($id,$data){
		//$this->erp->print_arrays($data);
		$this->db->where('id', $id);
		$q=$this->db->update('suspended', $data);
        if ($q) {
            return true;
        }
        return false;
	}
	
	
	/* New Function */
	public function getExpenseCategoryByID($id)
    {
        $q = $this->db->get_where("expense_categories", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getExpenseCategoryByCode($code)
    {
        $q = $this->db->get_where("expense_categories", array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addExpenseCategory($data)
    {
        if ($this->db->insert("expense_categories", $data)) {
            return true;
        }
        return false;
    }

    public function addExpenseCategories($data)
    {
        if ($this->db->insert_batch("expense_categories", $data)) {
            return true;
        }
        return false;
    }

    public function updateExpenseCategory($id, $data = array())
    {
        if ($this->db->update("expense_categories", $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function hasExpenseCategoryRecord($id)
    {
        $this->db->where('category_id', $id);
        return $this->db->count_all_results('expenses');
    }

    public function deleteExpenseCategory($id)
    {
        if ($this->db->delete("expense_categories", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function addUnit($data)
    {
        if ($this->db->insert("units", $data)) {
            return true;
        }
        return false;
    }

    public function updateUnit($id, $data = array())
    {
        if ($this->db->update("units", $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function deleteUnit($id)
    {
        if ($this->db->delete("units", array('id' => $id))) {
            $this->db->delete("units", array('base_unit' => $id));
            return true;
        }
        return FALSE;
    }

    public function addPriceGroup($data)
    {
        if ($this->db->insert('price_groups', $data)) {
            return true;
        }
        return false;
    }

    public function updatePriceGroup($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('price_groups', $data)) {
            return true;
        }
        return false;
    }

    public function getAllPriceGroups()
    {
        $q = $this->db->get('price_groups');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
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

    public function deletePriceGroup($id)
    {
        if ($this->db->delete('price_groups', array('id' => $id)) && $this->db->delete('product_prices', array('price_group_id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function setProductPriceForPriceGroup($product_id, $group_id, $price)
    {
        if ($this->getGroupPrice($group_id, $product_id)) {
            if ($this->db->update('product_prices', array('price' => $price), array('price_group_id' => $group_id, 'product_id' => $product_id))) {
                return true;
            }
        } else {
            if ($this->db->insert('product_prices', array('price' => $price, 'price_group_id' => $group_id, 'product_id' => $product_id))) {
                return true;
            }
        }
        return FALSE;
    }

    public function getGroupPrice($group_id, $product_id)
    {
        $q = $this->db->get_where('product_prices', array('price_group_id' => $group_id, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductGroupPriceByPID($product_id, $group_id)
    {
        $pg = "(SELECT {$this->db->dbprefix('product_prices')}.price as price, {$this->db->dbprefix('product_prices')}.product_id as product_id FROM {$this->db->dbprefix('product_prices')} WHERE {$this->db->dbprefix('product_prices')}.product_id = {$product_id} AND {$this->db->dbprefix('product_prices')}.price_group_id = {$group_id}) GP";
		$this->db->select("{$this->db->dbprefix('products')}.id as id, {$this->db->dbprefix('products')}.code as code, {$this->db->dbprefix('products')}.name as name, GP.price", FALSE)
        //->join('products', 'products.id=product_prices.product_id', 'left')
        ->join($pg, 'GP.product_id=products.id', 'left');
        $q = $this->db->get_where('products', array('products.id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateGroupPrices($data = array())
    {
        foreach ($data as $row) {
            if ($this->getGroupPrice($row['price_group_id'], $row['product_id'])) {
                $this->db->update('product_prices', array('price' => $row['price']), array('product_id' => $row['product_id'], 'price_group_id' => $row['price_group_id']));
            } else {
                $this->db->insert('product_prices', $row);
            }
        }
        return true;
    }

    public function deleteProductGroupPrice($product_id, $group_id)
    {
        if ($this->db->delete('product_prices', array('price_group_id' => $group_id, 'product_id' => $product_id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function getBrandByName($name)
    {
        $q = $this->db->get_where('brands', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addBrand($data)
    {
        if ($this->db->insert("brands", $data)) {
            return true;
        }
        return false;
    }

    public function addBrands($data)
    {
        if ($this->db->insert_batch('brands', $data)) {
            return true;
        }
        return false;
    }

    public function updateBrand($id, $data = array())
    {
        if ($this->db->update("brands", $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function deleteBrand($id)
    {
        if ($this->db->delete("brands", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	public function delete_down_persentages($id)
	{
        if ($this->db->delete("erp_down_persentages", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	public function add_down_persentages($data)
    {
        if ($this->db->insert('down_persentages', $data)) {
            return true;
        }
        return false;
    }
	public function getOne_down_persentages($id){
		$q = $this->db->get_where('down_persentages', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function update_down_persentages($id,$data){

		$this->db->where('id', $id);
        if ($this->db->update('down_persentages', $data)) {
            return true;
        }
        return false;
	}
	//// Interest rate ///
	
	public function delete_interest_rate($id)
	{
        if ($this->db->delete("erp_interest_rate", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	public function add_interest_rate($data)
    {
        if ($this->db->insert('interest_rate', $data)) {
            return true;
        }
        return false;
    }
	public function getOne_interest_rate($id){
		$q = $this->db->get_where('interest_rate', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function update_interest_rate($id,$data){

		$this->db->where('id', $id);
        if ($this->db->update('interest_rate', $data)) {
            return true;
        }
        return false;
	}
	public function getInterest_rate_byID($id)
    {
        $q = $this->db->get_where('interest_rate', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	//// term in month ///
	
	public function delete_terms($id)
	{
        if ($this->db->delete("terms", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	public function add_term($data)
    {
        if ($this->db->insert('terms', $data)) {
            return true;
        }
        return false;
    }
	public function getOne_term($id){
		$q = $this->db->get_where('terms', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function update_term($id,$data){

		$this->db->where('id', $id);
        if ($this->db->update('terms', $data)) {
            return true;
        }
        return false;
	}
	public function getterm_byID($id)
    {
        $q = $this->db->get_where('terms', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	// type but call model
	

	public function delete_type($id)
	{
        if ($this->db->delete("type", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	public function add_type($data)
    {
        if ($this->db->insert('type', $data)) {
            return true;
        }
        return false;
    }
	public function getOne_type($id){
		$q = $this->db->get_where('type', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function update_type($id,$data){

		$this->db->where('id', $id);
        if ($this->db->update('type', $data)) {
            return true;
        }
        return false;
	}
	public function gettype_byID($id)
    {
        $q = $this->db->get_where('type', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	
	// insurances but call model
	

	public function delete_insurances($id)
	{
        if ($this->db->delete("insurances", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	public function add_insurances($data)
    {
        if ($this->db->insert('insurances', $data)) {
            return true;
        }
        return false;
    }
	public function getOne_insurances($id){
		$q = $this->db->get_where('insurances', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function update_insurances($id,$data){

		$this->db->where('id', $id);
        if ($this->db->update('insurances', $data)) {
            return true;
        }
        return false;
	}
	public function getinsurances_byID($id)
    {
        $q = $this->db->get_where('insurances', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getFinancial($id)
    {
        $q = $this->db->get_where('services', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	public function updateFinancial($data,$id)
    {
        $this->db->where('id', $id);
        if ($this->db->update('services', $data)) {
            return true;
        }
        return false;
    }
	public function deleteFinancial($id){
		$this->db->where('id',$id);
		$d=$this->db->delete('services');
		if($d){
			return true;
		}else{return false;}
	}
	public function addSMS($data = array()) {
		if($this->db->insert('sms', $data)) {
			return true;
		}
		return false;
	}
	public function getSMSByID($id = NULL)
    {
        $q = $this->db->get_where('sms', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function updateSMS($id = NULL, $data = array())
    {
        if($id && $data) {
			if($this->db->update('sms', $data, array('id' => $id))) {
				return true;
			}
		}
		return false;
    }
	public function deleteSMS($id = NULL)
    {
        if ($this->db->delete('sms', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	public function add_collateral_types($data)
    {
        if ($this->db->insert('collateral_types', $data)) {
            return true;
        }
        return false;
    }
	public function delete_collateral_types($id)
	{
        if ($this->db->delete("collateral_types", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	public function getOne_collateral_types($id){
		$q = $this->db->get_where('collateral_types', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function update_collateral_types($id,$data){

		$this->db->where('id', $id);
        if ($this->db->update('collateral_types', $data)) {
            return true;
        }
        return false;
	}
	
	public function add_identify_types($data)
    {
        if ($this->db->insert('identify_types', $data)) {
            return true;
        }
        return false;
    }
	
	public function getOne_identify_types($id){
		$q = $this->db->get_where('identify_types', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function update_identify_types($id,$data){
		$this->db->where('id', $id);
        if ($this->db->update('identify_types', $data)) {
            return true;
        }
        return false;
	}
	
	public function delete_identify_types($id)
	{
        if ($this->db->delete("identify_types", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	public function add_reject_reason($data)
    {
        if ($this->db->insert('reject_reason', $data)) {
            return true;
        }
        return false;
    }
	public function delete_reject_reason($id)
	{
        if ($this->db->delete("reject_reason", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	public function getOne_reject_reason($id){
		$q = $this->db->get_where('reject_reason', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function update_reject_reason($id,$data){
		$this->db->where('id', $id);
        if ($this->db->update('reject_reason', $data)) {
            return true;
        }
        return false;
	}
	
	/////////////////////////////
	public function add_holidays($data)
    {
        if ($this->db->insert('holidays', $data)) {
            return true;
        }
        return false;
    }
	public function delete_holidays($id)
	{
        if ($this->db->delete("holidays", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	public function getOne_holidays($id){
		$q = $this->db->get_where('holidays', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function update_holidays($id,$data){
		$this->db->where('id', $id);
        if ($this->db->update('holidays', $data)) {
            return true;
        }
        return false;
	}
	public function importHolidays($data){
		$this->db->trans_start();
		if($this->db->insert_batch('erp_holidays',$data)){
			$this->db->trans_complete();
			return true;
		}else{
			return false;
		}
		
	}
	
	
	/////////////////////////////
	public function add_policy_payments($data)
    {
        if ($this->db->insert('policy_payments', $data)) {
            return true;
        }
        return false;
    }
	public function delete_policy_payments($id)
	{
        if ($this->db->delete("policy_payments", array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	public function getOne_policy_payments($id){
		$q = $this->db->get_where('policy_payments', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function update_policy_payments($id,$data){
		$this->db->where('id', $id);
        if ($this->db->update('policy_payments', $data)) {
            return true;
        }
        return false;
	}
}
