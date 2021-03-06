<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getProductNames($term, $warehouse_id, $limit = 15)
    {
        $this->db->select('products.id, code, name, type, cost, warehouses_products.quantity, price, tax_rate, tax_method, image, promotion, promo_price')
						->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
						->group_by('products.id');
        if ($this->Settings->overselling) {
            $this->db->where("(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%') AND inactived <> 1");
        } else {
            $this->db->where("(products.track_quantity = 0 OR warehouses_products.quantity > 0) AND warehouses_products.warehouse_id = '" . $warehouse_id . "' AND "
                . "(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%') AND inactived <> 1");
        }
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

	public function getProductNumber($term, $warehouse_id, $limit = 5)
    {
		if(preg_match('/\s/', $term))
		{
			$name = explode(" ", $term);
			$first = $name[0];
			$this->db->select('*')
            ->group_by('products.id');
			$this->db->where('code', $first);
			$this->db->limit($limit);
			$q = $this->db->get('products');
			if ($q->num_rows() > 0) {
				foreach (($q->result()) as $row) {
					$data[] = $row;
				}
				return $data;
			}
		}else
		{
			/* --v_pos : View in Database
			$this->db->select();
			$this->db->from('v_pos');
			$this->db->where("(code LIKE '%" . $term . "%')");
			 ENd VIew */
			
			$this->db->select('products.id, code, name, type, cost,warehouses_products.quantity, price, tax_rate, tax_method')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
			$this->db->where("(code LIKE '%" . $term . "%')");
			
			$this->db->limit($limit);
			$q = $this->db->get('products');
			if ($q->num_rows() > 0) {
				foreach (($q->result()) as $row) {
					$data[] = $row;
				}
				return $data;
			}
		}
	}
	
	public function getProductCodes($term, $warehouse_id, $limit = 5)
    {
		$this->db->select($this->db->dbprefix('products').'.id,'.$this->db->dbprefix('products').'.code,'.$this->db->dbprefix('products').'.name, details, category_id, price, '. $this->db->dbprefix('categories').'.name as cate_name')
		->join('categories', 'categories.id=products.category_id', 'left')
		->group_by('products.id');
		$this->db->where("(".$this->db->dbprefix('products').".code LIKE '%" . $term . "%' )");
		$this->db->limit($limit);
		$q = $this->db->get('products');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }

	public function getPname($term, $warehouse_id, $code, $limit = 5)
    {
		$this->db->select($this->db->dbprefix('products').'.id,'.$this->db->dbprefix('products').'.code,'.$this->db->dbprefix('products').'.name, details, category_id, price, '. $this->db->dbprefix('categories').'.name as cate_name')
		->join('categories', 'categories.id=products.category_id', 'left')
		->group_by('products.id');
		if($code == NULL){
			$this->db->where("(".$this->db->dbprefix('products').".name LIKE '%" . $term . "%' )");
		}else{
			$this->db->where("(".$this->db->dbprefix('products').".name LIKE '%" . $term . "%' and ".$this->db->dbprefix('products').".code LIKE '%" . $term . "%' )");
		}
		$this->db->limit($limit);
		$q = $this->db->get('products');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }
	
	public function getPdescription($term, $warehouse_id, $name, $code, $limit = 5)
    {
		$this->db->select($this->db->dbprefix('products').'.id,'.$this->db->dbprefix('products').'.code,'.$this->db->dbprefix('products').'.name, details, category_id, price, '. $this->db->dbprefix('categories').'.name as cate_name')
		->join('categories', 'categories.id=products.category_id', 'left')
		->group_by('products.id');
		if($name == null and $code == null){
			$this->db->where("(".$this->db->dbprefix('products').".details LIKE '%" . $term . "%' )");
		}elseif($name == null){
			$this->db->where("(".$this->db->dbprefix('products').".details LIKE '%" . $term . "%' and ".$this->db->dbprefix('products').".code LIKE '%" . $code . "%' )");
		}elseif($code == null){
			$this->db->where("(".$this->db->dbprefix('products').".details LIKE '%" . $term . "%' and ".$this->db->dbprefix('products').".name LIKE '%" . $name . "%' )");
		}else{
			$this->db->where("(".$this->db->dbprefix('products').".details LIKE '%" . $term . "%' and ".$this->db->dbprefix('products').".name LIKE '%" . $name . "%' and ".$this->db->dbprefix('products').".code LIKE '%" . $code . "%' )");
		}
		$this->db->limit($limit);
		$q = $this->db->get('products');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }
	
	public function getPcategory($term, $warehouse_id, $limit = 5)
    {
		$this->db->select($this->db->dbprefix('products').'.id,'.$this->db->dbprefix('products').'.code,'.$this->db->dbprefix('products').'.name, details, category_id, price, '. $this->db->dbprefix('categories').'.name as cate_name')
		->join('categories', 'categories.id=products.category_id', 'left')
		->group_by('products.id');
		$this->db->where("(".$this->db->dbprefix('categories').".name LIKE '%" . $term . "%' )");
		$this->db->limit($limit);
		$q = $this->db->get('products');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }
	
	public function getPprice($term, $warehouse_id, $limit = 5)
    {
		$this->db->select($this->db->dbprefix('products').'.id,'.$this->db->dbprefix('products').'.code,'.$this->db->dbprefix('products').'.name, details, category_id, price, '. $this->db->dbprefix('categories').'.name as cate_name')
		->join('categories', 'categories.id=products.category_id', 'left')
		->group_by('products.id');
		$this->db->where("(".$this->db->dbprefix('products').".price LIKE '%" . $term . "%' )");
		$this->db->limit($limit);
		$q = $this->db->get('products');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }
    
	public function getfcode($term, $limit = 5)
    {
		$this->db->select($this->db->dbprefix('suspended').'.id,'.$this->db->dbprefix('suspended').'.name, description, floor');
		$this->db->where("(".$this->db->dbprefix('suspended').".name LIKE '%" . $term . "%' )");
		$this->db->limit($limit);
		$q = $this->db->get('suspended');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }
	
	public function getfdescription($term, $limit = 5)
    {
		$this->db->select($this->db->dbprefix('suspended').'.id,'.$this->db->dbprefix('suspended').'.name, description, floor');
		$this->db->where("(".$this->db->dbprefix('suspended').".description LIKE '%" . $term . "%' )");
		$this->db->limit($limit);
		$q = $this->db->get('suspended');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }
	
	public function getffloor($term, $limit = 5)
    {
		$this->db->select($this->db->dbprefix('suspended').'.id,'.$this->db->dbprefix('suspended').'.name, description, floor');
		$this->db->where("(".$this->db->dbprefix('suspended').".floor LIKE '%" . $term . "%' )");
		$this->db->limit($limit);
		$q = $this->db->get('suspended');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }    
	
    public function getProductComboItems($pid, $warehouse_id = NULL)
    {
        $this->db->select('products.id as id, combo_items.item_code as code, combo_items.quantity as qty, products.name as name,products.type as type, warehouses_products.quantity as quantity')
            ->join('products', 'products.code=combo_items.item_code', 'left')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('combo_items.id');
        if($warehouse_id) {
            $this->db->where('warehouses_products.warehouse_id', $warehouse_id);
        }
        $q = $this->db->get_where('combo_items', array('combo_items.product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }

    public function getProductByCode($code)
    {
        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function syncQuantity($sale_id)
    {
        if ($sale_items = $this->getAllInvoiceItems($sale_id)) {
            foreach ($sale_items as $item) {
                $this->site->syncProductQty($item->product_id, $item->warehouse_id);
                if (isset($item->option_id) && !empty($item->option_id)) {
                    $this->site->syncVariantQty($item->option_id, $item->warehouse_id);
                }
            }
        }
    }

    public function getProductQuantity($product_id, $warehouse)
    {
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse), 1);
        if ($q->num_rows() > 0) {
            return $q->row_array(); //$q->row();
        }
        return FALSE;
    }
	/* POS Option */
    public function getProductOptions($product_id, $warehouse_id, $all = NULL)
    {
        $this->db->select('product_variants.id as id, product_variants.name as name, product_variants.price as price, product_variants.quantity as total_quantity, warehouses_products_variants.quantity as quantity,product_variants.qty_unit as qty_unit')
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left')
            //->join('warehouses', 'warehouses.id=product_variants.warehouse_id', 'left')
            ->where('product_variants.product_id', $product_id)
			->where('product_variants.product_id !=', 0)
            //->where('warehouses_products_variants.warehouse_id', $warehouse_id)
            ->group_by('product_variants.id');
            if( ! $this->Settings->overselling && ! $all) {
                $this->db->where('warehouses_products_variants.quantity >', 0);
            }
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductVariants($product_id)
    {
        $q = $this->db->get_where('product_variants', array('product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getItemByID($id)
    {

        $q = $this->db->get_where('sale_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }
    
    function getBillerNameByID($biller_id = null)
	{
		$this->db->select('company, name');
		$this->db->where(array('id' => $biller_id));
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	function getCustomerByID($cus_id = null)
	{
		$this->db->where(array('id' => $cus_id));
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
    
    function getCustomerNameByID($cus_id = null)
	{
        $this->db->select('name, company');
		$this->db->where(array('id' => $cus_id));
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
    
    public function getSalesReferences($term, $limit = 10)
    {
        $this->db->select('reference_no');
        $this->db->where("(reference_no LIKE '%" . $term . "%')");
        $this->db->limit($limit);
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getAllInvoiceItems($sale_id)
    {
        $this->db->select('sale_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, products.unit, products.details as details, product_variants.name as variant, products.unit, products.promotion, categories.name AS category_name')
            ->join('products', 'products.id=sale_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=sale_items.tax_rate_id', 'left')
			->join('categories', 'categories.id = products.category_id', 'left')
            ->group_by('sale_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	function getPaymentBySaleID($sale_id){
		$q = $this->db->get_where('payments', array('sale_id' => $sale_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
    
    
    public function getAllsuspendItem($sale_id)
    {
        $this->db->select('suspended_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, products.unit, products.details as details, product_variants.name as variant')
            ->join('products', 'products.id=suspended_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=suspended_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=suspended_items.tax_rate_id', 'left')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('suspended_items', array('suspend_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getAllSuspendDetail($id){
    	
    	$q = $this->db->get_where('suspended_bills', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;        
    }
	
	public function getAllSuspendBySupendID($id){
    	
    	$q = $this->db->get_where('suspended_bills', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;        
    }
    
    public function getAllRoomDetail($id){
    	
    	$q = $this->db->get_where('suspended', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;        
    }

    public function getAllReturnItems($return_id)
    {
        $this->db->select('return_items.*, products.details as details, product_variants.name as variant')
            ->join('products', 'products.id=return_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=return_items.option_id', 'left')
            ->group_by('return_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('return_items', array('return_id' => $return_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getAllInvoiceItemsWithDetails($sale_id)
    {
        $this->db->select('sale_items.id, sale_items.product_name, sale_items.product_code,products.price, sale_items.quantity, sale_items.serial_no, sale_items.tax, sale_items.net_unit_price, sale_items.item_tax, sale_items.item_discount, sale_items.subtotal, products.details');
        $this->db->join('products', 'products.id=sale_items.product_id', 'left');
        $this->db->order_by('id', 'asc');
        if(is_array($sale_id)){
            $this->db->or_where_in('sale_id', $sale_id);
            $q = $this->db->get('sale_items');
        }else{
            $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        }
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getProductComboItemsCode($sale_id){
		$this->db->select('sale_items.id, combo_items.item_code, combo_items.quantity, sale_items.product_code ');
        $this->db->join('products', 'products.id=sale_items.product_id', 'left');
		$this->db->join('combo_items', 'combo_items.product_id=products.id', 'left');
		$this->db->group_by('combo_items.item_code');
        $this->db->order_by('id', 'asc');
        if(is_array($sale_id)){
            $this->db->or_where_in('sale_id', $sale_id);
            $q = $this->db->get('sale_items');
        }else{
            $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        }
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
    public function getInvoiceByID($id)
    {
        $q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getmulti_InvoiceByID($id)
    {
        $q = $this->db->get_where('sales', array('id' => $id), 1);
         if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	public function getInvoiceByRef($ref)
    {
        $q = $this->db->get_where('sales', array('reference_no' => $ref), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getInvoiceByIDs($id)
    {
       $this->db->select($this->db->dbprefix('suspended_bills').".id, date, (select name from ".$this->db->dbprefix('suspended')." where id= ".$this->db->dbprefix('suspended_bills').".suspend_id) as suspend, (select company from ".$this->db->dbprefix('companies')." where id= ".$this->db->dbprefix('suspended_bills').".biller_id) as biller, customer, 
            	case when DATE(date)+ INTERVAL (SELECT show_suspend_bar-1 from ".$this->db->dbprefix('pos_settings')." where ".$this->db->dbprefix('pos_settings').".default_biller=biller_id) DAY <= DATE(SYSDATE()) then 'completed' else 'pending' end AS sale_status,
            	total as grand_total, '' as paid, '' as balance, 'pending' as payment_status");
        $q = $this->db->get_where('suspended_bills', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
			//$this->erp->print_arrays($data);
            return $data;
        }
		return FALSE;
    }
	
	public function getInvoiceBySuspendIDs($id)
    {
       $this->db->select($this->db->dbprefix('suspended_bills').".id, date, (select name from ".$this->db->dbprefix('suspended')." where id= ".$this->db->dbprefix('suspended_bills').".suspend_id) as suspend, (select company from ".$this->db->dbprefix('companies')." where id= ".$this->db->dbprefix('suspended_bills').".biller_id) as biller, customer, 
            	case when DATE(date)+ INTERVAL (SELECT show_suspend_bar-1 from ".$this->db->dbprefix('pos_settings')." where ".$this->db->dbprefix('pos_settings').".default_biller=biller_id) DAY <= DATE(SYSDATE()) then 'completed' else 'pending' end AS sale_status,
            	total as grand_total, '' as paid, '' as balance, 'pending' as payment_status");
        $q = $this->db->get_where('suspended_bills', array('suspend_id' => $id), 1);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
			//$this->erp->print_arrays($data);
            return $data;
        }
		return FALSE;
    }
	
	public function getSuspendByID($id){
		$this->db->select($this->db->dbprefix('sales').".id,".$this->db->dbprefix('sales').".date, ".$this->db->dbprefix('sales').".suspend_note as suspend, (select company from ".$this->db->dbprefix('companies')." where id= ".$this->db->dbprefix('sales').".biller_id) as biller,".$this->db->dbprefix('sales').".customer, case when DATE(".$this->db->dbprefix('suspended_bills').".date)+ INTERVAL (SELECT show_suspend_bar-1 from ".$this->db->dbprefix('pos_settings')." where ".$this->db->dbprefix('pos_settings').".default_biller=".$this->db->dbprefix('suspended_bills').".biller_id) DAY <= DATE(SYSDATE()) then 'completed' else 'pending' end AS sale_status, ".$this->db->dbprefix('sales').".grand_total as grand_total, ".$this->db->dbprefix('sales').".paid as paid, (CASE WHEN ".$this->db->dbprefix('sales').".paid IS NULL THEN ".$this->db->dbprefix('sales').".grand_total ELSE ".$this->db->dbprefix('sales').".grand_total - ".$this->db->dbprefix('sales').".paid END) as balance, CASE WHEN ".$this->db->dbprefix('sales').".paid = 0 THEN 'pending' WHEN ".$this->db->dbprefix('sales').".grand_total = ".$this->db->dbprefix('sales').".paid THEN 'completed' WHEN ".$this->db->dbprefix('sales').".grand_total > ".$this->db->dbprefix('sales').".paid THEN 'partial' ELSE 'pending' END as payment_status")
		->join($this->db->dbprefix('sales'), $this->db->dbprefix('sales').'.suspend_note = '.$this->db->dbprefix('suspended_bills').'.suspend_name', 'right')
		->from('suspended_bills')
		->where('sales.id', $id );
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getLoansByID($id)
    {
        $this->db->select('loans.*,sales.reference_no,
							sales.customer_id,sales.customer,sales.biller_id,sales.biller,
							sales.total,sales.paid
						');
        $this->db->join('sales', 'loans.sale_id=sales.id', 'INNER');
        $q = $this->db->get_where('loans', array('loans.sale_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
			//$this->erp->print_arrays($data);
            return $data;
        }
		return FALSE;
    }
	
	public function getExportLoans($id){
		$this->db->select($this->db->dbprefix('loans').".sale_id, sales.date, 
					 sales.reference_no as ref_no, sales.biller, sales.customer, 
					 sales.sale_status, ".$this->db->dbprefix('sales').".grand_total, 
					 IF(".$this->db->dbprefix('loans').".type <> 0,(".$this->db->dbprefix('sales').".paid + (COALESCE(".$this->db->dbprefix('sales').".other_cur_paid / ".$this->db->dbprefix('sales').".other_cur_paid_rate,0))),SUM(IF(".$this->db->dbprefix('loans').".paid_amount > 0,".$this->db->dbprefix('loans').".principle,0))) as paid,
					 IF(".$this->db->dbprefix('loans').".type <> 0,ROUND((".$this->db->dbprefix('sales').".grand_total- ((IF(".$this->db->dbprefix('loans').".type <> 0,".$this->db->dbprefix('sales').".paid, 0) + (COALESCE(".$this->db->dbprefix('sales').".other_cur_paid / ".$this->db->dbprefix('sales').".other_cur_paid_rate,0))))),3),ROUND((".$this->db->dbprefix('sales').".grand_total- SUM(IF(".$this->db->dbprefix('loans').".paid_amount > 0,".$this->db->dbprefix('loans').".principle,0)))))  as balance, 
					 IF(".$this->db->dbprefix('loans').".type = 0 AND ".$this->db->dbprefix('loans').".paid_amount < 0,'due',".$this->db->dbprefix('sales').".payment_status) as payment_status")
				 ->from('sales')
				 ->join('loans','sales.id=loans.sale_id','INNER')
				 ->where('sales.id', $id)
				 ->group_by('loans.sale_id');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
				if ($q->num_rows() > 0) {
				return $q->row();
			}
        }
		return FALSE;
	}
	
	public function getSingleLoanById($id){

		$this->db->select('loans.*,sales.reference_no,
							sales.customer_id,sales.customer,sales.biller_id,sales.biller,
							sales.total,sales.paid
						');
        $this->db->join('sales', 'loans.sale_id=sales.id', 'INNER');
        $q = $this->db->get_where('loans', array('loans.id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
			//$this->erp->print_arrays($data);
            return $data;
        }
		return FALSE;
	}
	
	public function getItemsByID($id)
    {
        $this->db->select('sale_items.product_code,sale_items.product_name,sale_items.unit_price,
							sale_items.quantity
						');
        $q = $this->db->get_where('sale_items', array('sale_items.sale_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
			//$this->erp->print_arrays($data);
            return $data;
        }
		return FALSE;
    }
	
	public function getSaleInfoByID($id){
		$this->db->select('sales.id,sales.reference_no,sales.paid,sales.other_cur_paid,sales.other_cur_paid_rate,customer_id
						');
        $q = $this->db->get_where('sales', array('sales.id' => $id));
        if ($q->num_rows() > 0) {
            
			//$this->erp->print_arrays($data);
           return $q->row();
        }
		return FALSE;
	}

    public function getReturnByID($id)
    {
        $q = $this->db->get_where('return_sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getReturnBySID($sale_id)
    {
        $q = $this->db->get_where('return_sales', array('sale_id' => $sale_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getReturnSaleBySaleID($sale_id)
    {
        $this->db->select('sale_id');
        $q = $this->db->get_where('return_sales', array('sale_id' => $sale_id), 1);
        if ($q->num_rows() > 0) {
            return true;
        }
        return FALSE;
    }
    
    public function getReturnItemByReturnID($return_id){
        $q = $this->db->get_where('return_sale_item', array('sale_item_id' => $return_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductOptionByID($id)
    {
        $q = $this->db->get_where('product_variants', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPurchasedItems($product_id, $warehouse_id, $option_id = NULL)
    {
        $orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->select('id, quantity, quantity_balance, net_unit_cost, item_tax');
        $this->db->where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->where('quantity_balance !=', 0);
        if ($option_id) {
            $this->db->where('option_id', $option_id);
        }
        $this->db->group_by('id');
        $this->db->order_by('date', $orderby);
        $this->db->order_by('purchase_id', $orderby);
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function updateOptionQuantity($option_id, $quantity)
    {
        if ($option = $this->getProductOptionByID($option_id)) {
            $nq = $option->quantity - $quantity;
            if ($this->db->update('product_variants', array('quantity' => $nq), array('id' => $option_id))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function addOptionQuantity($option_id, $quantity)
    {
        if ($option = $this->getProductOptionByID($option_id)) {
            $nq = $option->quantity + $quantity;
            if ($this->db->update('product_variants', array('quantity' => $nq), array('id' => $option_id))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getProductWarehouseOptionQty($option_id, $warehouse_id)
    {
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id, 'warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateProductOptionQuantity($option_id, $warehouse_id, $quantity, $product_id)
    {
        if ($option = $this->getProductWarehouseOptionQty($option_id, $warehouse_id)) {
            $nq = $option->quantity - $quantity;
            if ($this->db->update('warehouses_products_variants', array('quantity' => $nq), array('option_id' => $option_id, 'warehouse_id' => $warehouse_id))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        } else {
            $nq = 0 - $quantity;
            if ($this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $nq))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        }
        return FALSE;
    }
	public function addSale($data = array(), $items = array(), $payment = array(), $loans = array())
	{
		$deposit_customer_id = $data['deposit_customer_id'];
		unset($data['deposit_customer_id']);
		
		$cost = $this->site->costing($items);
		foreach($items as $g){
			$totalCostProducts = $this->getTotalCostProducts($g['product_id'], $g['quantity']);
			$data['total_cost'] += $totalCostProducts->total_cost;
			$Proqty = $this->getProductQty($g['product_id']);
			$WareQty = $this->getWarehouseQty($g['product_id'], $g['warehouse_id']);
			$qty = $Proqty->quantity - $g['quantity'];
			$ware = $WareQty->quantity - $g['quantity'];
			$this->db->update('warehouses_products', array('quantity' => $ware), array('product_id' => $g['product_id'], 'warehouse_id' => $g['warehouse_id']));
			$this->db->update('products', array('quantity' => $qty), array('id' => $g['product_id']));
		}
		
		if($loans) {
			$data['grand_total'] = $data['paid'];
			foreach ($loans as $loan) {
				$data['grand_total'] += $loan['payment'];
			}
		}
			
		if ($this->db->insert('sales', $data)) {
			$sale_id = $this->db->insert_id();
			if ($this->site->getReference('so') == $data['reference_no']) {
				$this->site->updateReference('so');
			}
			foreach ($items as $item) {
				$item['sale_id'] = $sale_id;
				$this->db->insert('sale_items', $item);
				$sale_item_id = $this->db->insert_id();
				if ($data['sale_status'] == 'completed' && $this->site->getProductByID($item['product_id'])) {
					$item_costs = $this->site->item_costing($item);
					foreach ($item_costs as $item_cost) {
						$item_cost['sale_item_id'] = $sale_item_id;
						$item_cost['sale_id'] = $sale_id;
						if(isset($data['date'])){
							$item_cost['date'] = $data['date'];
						}
						//$option_id = $item_cost['option_id'];
						
						if(! isset($item_cost['pi_overselling'])) {
							$this->db->insert('costing', $item_cost);
						}
					}
				}
			}
			
			if($loans){
				foreach($loans as $loan){
					$loan['sale_id'] = $sale_id;
					$this->db->insert('loans', $loan);
				}
			}
			if ($data['sale_status'] == 'completed') {
				$this->site->syncPurchaseItems($cost);
			}
			
			if(strpos($data['paid'], '-') !== true){
				if ($data['payment_status'] == 'partial' || $data['payment_status'] == 'paid' && !empty($payment)) {
					$payment['sale_id'] = $sale_id;
					//if($payment['amount'] == $payment['amount'])
					if ($payment['paid_by'] == 'gift_card') {
						$this->db->update('gift_cards', array('balance' => $payment['gc_balance']), array('card_no' => $payment['cc_no']));
						unset($payment['gc_balance']);
						$this->db->insert('payments', $payment);
					} else {
						$this->db->insert('payments', $payment);
					}
					if ($this->site->getReference('sp') == $payment['reference_no']) {
						$this->site->updateReference('sp');
					}
					
					if($payment['paid_by'] == 'deposit'){
						$deposit = $this->site->getDepositByCompanyID($deposit_customer_id);
						$deposit_balance = $deposit->deposit_amount;
						$deposit_balance = $deposit_balance - abs($payment['amount']);
						if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $deposit_customer_id))){
							$this->db->update('deposits', array('amount' => $deposit_balance), array('company_id' => $deposit_customer_id));
						}
					}
					$this->site->syncSalePayments($sale_id);
				}
			}else{
				$sale_items = $this->site->getAllSaleItems($sale_id);			
				$returns = array(
					'date' => $data['date'],
					'sale_id' => $sale_id,
					'reference_no' => $this->site->getReference('re'),
					'customer_id' => $data['customer_id'],
					'customer' => $data['customer'],
					'biller_id' => $data['biller_id'],
					'biller' => $data['biller'],
					'warehouse_id' => $data['warehouse_id'],
					'note' => $data['note'],
					'total' => $data['paid'],
					'product_discount' => $data['product_discount'],
					'order_discount_id' => $data['order_discount_id'],
					'order_discount' => $data['order_discount'],
					'total_discount' => $data['total_discount'],
					'product_tax' => $data['product_tax'],
					'order_tax_id' => $data['order_tax_id'],
					'order_tax' => $data['order_tax'],
					'total_tax' => $data['total_tax'],
					'grand_total' => $data['grand_total'],
					'created_by' => $this->session->userdata('user_id'),
				);
				if ($this->db->insert('return_sales', $returns)) {
					$return_id = $this->db->insert_id();
					if ($this->site->getReference('re') == $returns['reference_no']){
						$this->site->updateReference('re');
					}
				
					foreach ($items as &$return_item){
						unset($return_item['unit_price']);
						$return_item['return_id'] = $return_id;
						$sale_item_id = $this->db->insert('return_items', $return_item);
						
						if ($sale_item = $this->sales_model->getSaleItemByID($sale_item_id)) {
								//$this->db->delete('sale_items', array('id' => $item['sale_item_id']));
							if ($sale_item->quantity == $return_item['quantity']) {
							} else {
								$nqty = $sale_item->quantity - $item['quantity'];
								$tax = $sale_item->unit_price - $sale_item->net_unit_price;
								$discount = $sale_item->item_discount / $sale_item->quantity;
								$item_tax = $tax * $nqty;
								$item_discount = $discount * $nqty;
								$subtotal = $sale_item->unit_price * $nqty;
								$this->db->update('sale_items', array('quantity' => $nqty, 'item_tax' => $item_tax, 'item_discount' => $item_discount, 'subtotal' => $subtotal), array('id' => $item['sale_item_id']));
							}
						}
					}
					//$this->calculateSaleTotals($returns['sale_id'], $return_id);

					if ($data['payment_status'] == 'partial' || $data['payment_status'] == 'paid' && !empty($payment)) {
						$payment['sale_id'] = $sale_id;
						//if($payment['amount'] == $payment['amount'])
						if ($payment['paid_by'] == 'gift_card') {
							$this->db->update('gift_cards', array('balance' => $payment['gc_balance']), array('card_no' => $payment['cc_no']));
							unset($payment['gc_balance']);
							$payment['reference_no'] = $this->site->getReference('re');
							$payment['type'] = 'returned';
							$payment['return_id'] = $return_id;
							$this->db->insert('payments', $payment);
						} else {
							$payment['reference_no'] = $this->site->getReference('re');
							$payment['type'] = 'returned';
							$payment['return_id'] = $return_id;
							$this->db->insert('payments', $payment);
						}
						if ($this->site->getReference('sp') == $payment['reference_no']) {
							$this->site->updateReference('sp');
						}
						//$this->site->syncSalePayments($sale_id);

						$sale = $this->site->getSaleByID($sale_id);
						$payments = $this->site->getSalePayments($sale_id);
						$paid = 0;
						foreach ($payments as $payment) {
							if ($payment->type == 'returned') {
								$paid -= $payment->amount;
								//$paid -= $sale->paid;
							} else {
								$paid += $payment->amount;
								//$paid += $sale->paid;
							}
						}

						$payment_status = $paid <= 0 ? 'pending' : $sale->payment_status;
						if ($paid <= 0 && $sale->due_date <= date('Y-m-d')) {
							if ($payment->type == 'returned') {
								$payment_status = 'returned';
								$payment_term = 0;
								$paid = -1 * abs($paid);
							}else{
								$payment_status = 'due';
							}
						} elseif ($this->erp->formatDecimal($sale->grand_total) > $this->erp->formatDecimal($paid) && $paid > 0) {
							$payment_status = 'partial';
						} elseif ($this->erp->formatDecimal($sale->grand_total) <= $this->erp->formatDecimal($paid)) {
							if ($payment->type == 'returned') {
								$payment_status = 'returned';
								$paid = -1 * abs($paid);
							}else{
								$payment_status = 'paid';
							}
							$payment_term = 0;
						}
						
						if($payment['paid_by'] == 'deposit'){
						$deposit = $this->site->getDepositByCompanyID($data['customer_id']);
						$deposit_balance = $deposit->deposit_amount;
						$deposit_balance = $deposit_balance + abs($payment['amount']);
						if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $deposit_customer_id))){
							$this->db->update('deposits', array('amount' => $deposit_balance), array('company_id' => $deposit_customer_id));
						}
					}
						
						//$this->calculateSaleTotals($data['sale_id'], $return_id, $data['surcharge'], $payment_status);
						$this->calculateSaleTotals($sale_id, $return_id, NULL, $payment_status);
					}
					$this->site->syncQuantity(NULL, NULL, $sale_items);
				}
			}

			//$this->site->syncQuantity($sale_id);
			$this->erp->update_award_points($data['grand_total'], $data['customer_id'], $data['created_by'], NULL ,$data['saleman_by']);
			//return true;
			return $sale_id;
		}
		return false;
	}
    
	public function saleEdit($id, $qty, $sale_id, $ware){
		$Proqty = $this->getProductQty($id);
		$WareQty = $this->getWarehouseQty($id, $ware);
		$payprice = $this->getPaymentBySaleID($sale_id);
		if($Proqty){
			$quantity = $Proqty->quantity + $qty;
			$price = $payprice->amount - $Proqty->price;
			$this->db->update('products', array('quantity' => $quantity), array('id' => $id));
			$this->db->update('payments', array('amount'=>$price), array('sale_id'=>$sale_id));
		}
		if($WareQty){
			$warehouse = $WareQty->quantity + $qty;
			$this->db->update('warehouses_products', array('quantity' => $warehouse), array('product_id' => $id, 'warehouse_id' => $ware));
		}
		$this->db->delete('sale_items', array('sale_id' => $sale_id, 'product_id' => $id));
		$this->db->delete('costing', array('sale_id' => $sale_id, 'product_id' => $id));
		return false;
	}
	
	public function getProductQty($id){
		$this->db->select('quantity, price');
        $q = $this->db->get_where('products', array('id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getWarehouseQty($id, $warehouse){
		$this->db->select('quantity');
        $q = $this->db->get_where('warehouses_products', array('product_id' => $id, 'warehouse_id'=>$warehouse));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function addSaleImport($data = array(), $items = array())
	{
		$cost = $this->site->costing($items);
		foreach($items as $g) {
			$totalCostProducts = $this->getTotalCostProducts($g['product_id'], $g['quantity']);
			$data['total_cost'] += $totalCostProducts->total_cost;
		}
		if ($this->db->insert('sales', $data)) {
			$sale_id = $this->db->insert_id();
			if ($this->site->getReference('so') == $data['reference_no']) {
				$this->site->updateReference('so');
			}
			foreach ($items as $item) {
				$item['sale_id'] = $sale_id;
				$this->db->insert('sale_items', $item);
				$sale_item_id = $this->db->insert_id();
				if ($data['sale_status'] == 'completed' && $this->site->getProductByID($item['product_id'])) {
					$item_costs = $this->site->item_costing($item);
					foreach ($item_costs as $item_cost) {
						$item_cost['sale_item_id'] = $sale_item_id;
						$item_cost['sale_id'] = $sale_id;
						if(isset($data['date'])){
							$item_cost['date'] = $data['date'];
						}
						//$option_id = $item_cost['option_id'];
						if(!isset($item_cost['pi_overselling'])) {
							$this->db->insert('costing', $item_cost);
						}
					}
				}
			}
			if ($data['sale_status'] == 'completed') {
				$this->site->syncPurchaseItems($cost);
			}
			$this->site->syncQuantity($sale_id);
			return $sale_id;
		}
		return false;
	}
	
	public function addSaleItemImport($items = array(), $old_ref)
	{
		$sale = $this->getSaleItemByRef($old_ref);
		$cost = $this->site->costing($items);
		foreach($items as $g){
			$totalCostProducts = $this->getTotalCostProducts($g['product_id'], $g['quantity']);
			$sale->total_cost += $totalCostProducts->total_cost;
		}

		$sale_id = $sale->sale_id;
		if ($this->site->getReference('so') == $sale->reference_no) {
			$this->site->updateReference('so');
		}
		foreach ($items as $item) {
			if($item['product_id'] != $sale->product_id){
				$item['sale_id'] = $sale_id;
				$this->db->insert('sale_items', $item);
				$sale_item_id = $this->db->insert_id();
				
				$sale_update = array(
					'total' => $item['subtotal'] + $sale->total,
					'grand_total' => $item['subtotal'] + $sale->grand_total
				);
				$this->db->update('sales', $sale_update, array('id' => $item['sale_id']));
				
				/* 
				if ($sale->sale_status == 'completed' && $this->site->getProductByID($item['product_id'])) {

					$item_costs = $this->site->item_costing($item);
					foreach ($item_costs as $item_cost) {
						$item_cost['sale_item_id'] = $sale_item_id;
						$item_cost['sale_id'] = $sale_id;
						if(isset($sale->date)){
							$item_cost['date'] = $sale->date;
						}
						//$option_id = $item_cost['option_id'];

						if(! isset($item_cost['pi_overselling'])) {
							$this->db->insert('costing', $item_cost);
						}
					}
				}
				*/
			}
		}
		
		/*
		if ($sale->sale_status == 'completed') {
			$this->site->syncPurchaseItems($cost);
		}
		*/
		
	   $this->site->syncQuantity($sale_id);
		//$this->erp->update_award_points($sale->grand_total, $sale->customer_id, $sale->created_by, NULL ,$sale->saleman_by);
		return false;
	}
	
	public function getSaleItemByRef($sale_ref)
    {
        $this->db->select('sale_items.id AS sale_item_id, sale_items.product_id ,sales.id AS sale_id, sales.reference_no AS sale_reference, sales.total, sales.grand_total');
        $this->db->join('sale_items', 'sale_items.sale_id = sales.id', 'inner');
        $q = $this->db->get_where('sales', array('sales.reference_no' => $sale_ref));
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateSale($id, $data, $items = array())
    {
		$deposit_customer_id = $data['deposit_customer_id'];
		unset($data['deposit_customer_id']);
        $this->resetSaleActions($id);

        if ($data['sale_status'] == 'completed') {
            $cost = $this->site->costing($items);
        }
		
		foreach($items as $g){
			$totalCostProducts = $this->getTotalCostProducts($g['product_id'], $g['quantity']);
			$data['total_cost'] += $totalCostProducts->total_cost;
		}

        if ($this->db->update('sales', $data, array('id' => $id)) && $this->db->delete('sale_items', array('sale_id' => $id))) {

            foreach ($items as $item) {
                $item['sale_id'] = $id;
                $this->db->insert('sale_items', $item);
                $sale_item_id = $this->db->insert_id();
                if ($data['sale_status'] == 'completed' && $this->site->getProductByID($item['product_id'])) {
                    $item_costs = $this->site->item_costing($item);
                    foreach ($item_costs as $item_cost) {
                        $item_cost['sale_item_id'] = $sale_item_id;
                        $item_cost['sale_id'] = $id;
                        if(! isset($item_cost['pi_overselling'])) {
                            $this->db->insert('costing', $item_cost);
                        }
                    }
                }
            }
			
			if($data['payment_status'] == 'paid' || $data['payment_status'] == 'partial'){
				$this->db->update('payments', array('amount' => $data['paid']), array('sale_id' => $id));
				$total_balance = $data['grand_total'] - $data['paid'];
				if($total_balance != 0){
					$this->db->update('sales', array('payment_status' => 'partial'), array('id' => $id));
				}else{
					$this->db->update('sales', array('payment_status' => 'paid'), array('id' => $id));
				}
				
				/*
                $payment['sale_id'] = $id;
				if($payment['amount'] == $data['paid']){
					if ($payment['paid_by'] == 'gift_card') {
						$this->db->update('gift_cards', array('balance' => $payment['gc_balance']), array('card_no' => $payment['cc_no']));
						unset($payment['gc_balance']);
						$this->db->insert('payments', $payment);
					} else {
						$this->db->insert('payments', $payment);
					}
					if ($this->site->getReference('sp') == $payment['reference_no']) {
						$this->site->updateReference('sp');
					}
					
					if($payment['paid_by'] == 'deposit'){
						$deposit = $this->site->getDepositByCompanyID($deposit_customer_id);
						$deposit_balance = $deposit->deposit_amount;
						$deposit_balance = $deposit_balance - abs($payment['amount']);
						if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $deposit_customer_id))){
							$this->db->update('deposits', array('amount' => $deposit_balance), array('company_id' => $deposit_customer_id));
						}
					}
				}
				*/
				$this->site->syncSalePayments($sale_id);

				
			}
			//if($data['payment_status'] == 'paid')

            if ($data['sale_status'] == 'completed') {
                $this->site->syncPurchaseItems($cost);
            }

            $this->site->syncQuantity($id);
            $this->erp->update_award_points($data['grand_total'], $data['customer_id'], $data['created_by']);
            return true;
        }
        return false;
    }
	
    public function deleteSale($id)
    {
        $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('payments', array('sale_id' => $id)) &&
        $this->db->delete('sale_items', array('sale_id' => $id)) &&
        $this->db->delete('sales', array('id' => $id))) {
            if ($return = $this->getReturnBySID($id)) {
                $this->deleteReturn($return->id);
            }
            $this->site->syncQuantity(NULL, NULL, $sale_items);
            return true;
        }
        return FALSE;
    }
	
	public function deleteSuspend($id)
    {
        if ($this->db->delete('suspended_bills', array('id' => $id)) &&
        $this->db->delete('suspended_items', array('suspend_id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function resetSaleActions($id)
    {
        $sale = $this->getInvoiceByID($id);
        $items = $this->getAllInvoiceItems($id);
        foreach ($items as $item) {

            if ($sale->sale_status == 'completed') {
                if ($costings = $this->getCostingLines($item->id, $item->product_id)) {
                    $quantity = $item->quantity;
                    foreach ($costings as $cost) {
                        if ($cost->quantity >= $quantity) {
                            $qty = $cost->quantity - $quantity;
                            $bln = $cost->quantity_balance ? $cost->quantity_balance + $quantity : $quantity;
                            $this->db->update('costing', array('quantity' => $qty, 'quantity_balance' => $bln), array('id' => $cost->id));
                            $quantity = 0;
                        } elseif ($cost->quantity < $quantity) {
                            $qty = $quantity - $cost->quantity;
                            $this->db->delete('costing', array('id' => $cost->id));
                            $quantity -= $qty;
                        }
                        if ($quantity == 0) {
                            break;
                        }
                    }
                }
                if ($item->product_type == 'combo') {
                    $combo_items = $this->site->getProductComboItems($item->product_id, $item->warehouse_id);
                    foreach ($combo_items as $combo_item) {
                        if($combo_item->type == 'standard') {
                            $qty = ($item->quantity*$combo_item->qty);
                            $this->updatePurchaseItem(NULL, $qty, NULL, $combo_item->id, $item->warehouse_id);
                        }
                    }
                } else {
                    $option_id = isset($item->option_id) && !empty($item->option_id) ? $item->option_id : NULL;
                    $this->updatePurchaseItem(NULL, $item->quantity, $item->id, $item->product_id, $item->warehouse_id, $option_id);
                }
            }

        }
        $this->erp->update_award_points($sale->grand_total, $sale->customer_id, $sale->created_by, TRUE);
        return $items;
    }

    public function deleteReturn($id)
    {
        if ($this->db->delete('return_items', array('return_id' => $id)) && $this->db->delete('return_sales', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function updatePurchaseItem($id, $qty, $sale_item_id, $product_id = NULL, $warehouse_id = NULL, $option_id = NULL)
    {
        if ($id) {
            if($pi = $this->getPurchaseItemByID($id)) {
                $pr = $this->site->getProductByID($pi->product_id);
                if ($pr->type == 'combo') {
                    $combo_items = $this->site->getProductComboItems($pr->id, $pi->warehouse_id);
                    foreach ($combo_items as $combo_item) {
                        if($combo_item->type == 'standard') {
                            $cpi = $this->site->getPurchasedItem(array('product_id' => $combo_item->id, 'warehouse_id' => $pi->warehouse_id, 'option_id' => NULL));
                            $bln = $pi->quantity_balance + ($qty*$combo_item->qty);
                            $this->db->update('purchase_items', array('quantity_balance' => $bln), array('id' => $combo_item->id));
                        }
                    }
                } else {
                    $bln = $pi->quantity_balance + $qty;
                    $this->db->update('purchase_items', array('quantity_balance' => $bln), array('id' => $id));
                }
            }
        } else {
            if ($sale_item = $this->getSaleItemByID($sale_item_id)) {
				
                $option_id = isset($sale_item->option_id) && !empty($sale_item->option_id) ? $sale_item->option_id : NULL;
				if($option_id){
					$clause = array('product_id' => $sale_item->product_id, 'warehouse_id' => $sale_item->warehouse_id, 'option_id' => $option_id);
				}else{
					$clause = array('product_id' => $sale_item->product_id, 'warehouse_id' => $sale_item->warehouse_id);
				}
				
                if ($pi = $this->site->getPurchasedItem($clause)) {
                    $quantity_balance = $pi->quantity_balance+$qty;
                    $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), array('id' => $pi->id));
                } else {
                    $clause['purchase_id'] = NULL;
                    $clause['transfer_id'] = NULL;
                    $clause['quantity'] = 0;
                    $clause['quantity_balance'] = $qty;
                    $this->db->insert('purchase_items', $clause);
                }
            }
            if (! $sale_item && $product_id) {
                $pr = $this->site->getProductByID($product_id);
                $clause = array('product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'option_id' => $option_id);
                if ($pr->type == 'standard') {
                    if ($pi = $this->site->getPurchasedItem($clause)) {
                        $quantity_balance = $pi->quantity_balance+$qty;
                        $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), array('id' => $pi->id));
                    } else {
                        $clause['purchase_id'] = NULL;
                        $clause['transfer_id'] = NULL;
                        $clause['quantity'] = 0;
                        $clause['quantity_balance'] = $qty;
                        $this->db->insert('purchase_items', $clause);
                    }
                } elseif ($pr->type == 'combo') {
                    $combo_items = $this->site->getProductComboItems($pr->id, $warehouse_id);
                    foreach ($combo_items as $combo_item) {
                        $clause = array('product_id' => $combo_item->id, 'warehouse_id' => $warehouse_id, 'option_id' => NULL);
                        if($combo_item->type == 'standard') {
                            if ($pi = $this->site->getPurchasedItem($clause)) {
                                $quantity_balance = $pi->quantity_balance+($qty*$combo_item->qty);
                                $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), $clause);
                            } else {
                                $clause['transfer_id'] = NULL;
                                $clause['purchase_id'] = NULL;
                                $clause['quantity'] = 0;
                                $clause['quantity_balance'] = $qty;
                                $this->db->insert('purchase_items', $clause);
                            }
                        }
                    }
                }
            }
        }
    }
	
	public function getTotalCostProducts($product_id, $quantity){
		$this->db->select("SUM(cost* CASE WHEN $quantity <> 0 THEN $quantity ELSE 0 END ) AS total_cost ");
		$q = $this->db->get_where('products', array('id' => $product_id));
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    public function getPurchaseItemByID($id)
    {
        $q = $this->db->get_where('purchase_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function returnSale($data = array(), $items = array(), $payment = array())
    {
        foreach ($items as $item) {
            if ($item['product_type'] == 'combo') {
                $combo_items = $this->site->getProductComboItems($item['product_id'], $item['warehouse_id']);
                foreach ($combo_items as $combo_item) {
                    if ($costings = $this->getCostingLines($item['sale_item_id'], $combo_item->id)) {
                        $quantity = $item['quantity']*$combo_item->qty;
                        foreach ($costings as $cost) {
                            if ($cost->quantity >= $quantity) {
                                $qty = $cost->quantity - $quantity;
                                $bln = $cost->quantity_balance && $cost->quantity_balance >= $quantity ? $cost->quantity_balance - $quantity : 0;
                                $this->db->update('costing', array('quantity' => $qty, 'quantity_balance' => $bln), array('id' => $cost->id));
                                $quantity = 0;
                            } elseif ($cost->quantity < $quantity) {
                                $qty = $quantity - $cost->quantity;
                                $this->db->delete('costing', array('id' => $cost->id));
                                $quantity = $qty;
                            }
                        }
                    }
                    $this->updatePurchaseItem(NULL,($item['quantity']*$combo_item->qty), NULL, $combo_item->id, $item['warehouse_id']);
                }
            } else {
                if ($costings = $this->getCostingLines($item['sale_item_id'], $item['product_id'])) {
                    $quantity = $item['quantity'];
                    foreach ($costings as $cost) {
                        if($cost->option_id != 0 || $cost->option_id != NULL){
							$quantity = $quantity * $cost->qty_unit;
							if (($cost->quantity* $cost->qty_unit) > $quantity) {
								$qty = ($cost->quantity * $cost->qty_unit) - $quantity;
								$bln = $cost->quantity_balance && $cost->quantity_balance >= $quantity ? $cost->quantity_balance - $quantity : 0;
								$this->db->set('quantity',$qty/$cost->qty_unit);
								$this->db->update('costing', array('quantity_balance' => $bln), array('id' => $cost->id));
								$quantity = 0;
							} elseif (($cost->quantity*$cost->qty_unit) <= $quantity) {
								$qty = $quantity - ($cost->quantity*$cost->qty_unit);
								$this->db->delete('costing', array('id' => $cost->id));
								$quantity = $qty;
							}
						}else{
							if ($cost->quantity >= $quantity) {
								$qty = $cost->quantity - $quantity;
								$bln = $cost->quantity_balance && $cost->quantity_balance >= $quantity ? $cost->quantity_balance - $quantity : 0;
								$this->db->update('costing', array('quantity' => $qty, 'quantity_balance' => $bln), array('id' => $cost->id));
								$quantity = 0;
							} elseif ($cost->quantity < $quantity) {
								$qty = $quantity - $cost->quantity;
								$this->db->delete('costing', array('id' => $cost->id));
								$quantity = $qty;
							}
						}
                    }
                }
                //$this->updatePurchaseItem(NULL, $item['quantity']*$cost->qty_unit, $item['sale_item_id'], $item['product_id'], $item['warehouse_id'], $item['option_id']);
				$this->updatePurchaseItem(NULL, $item['quantity']*($cost->qty_unit?$cost->qty_unit:1), $item['sale_item_id'], $item['product_id'], $item['warehouse_id'], $item['option_id']);
            }
        }
		//$this->erp->print_arrays($items);
        $sale_items = $this->site->getAllSaleItems($data['sale_id']);
		
        if ($this->db->insert('return_sales', $data)) {
            $return_id = $this->db->insert_id();
            if ($this->site->getReference('re') == $data['reference_no']) {
                $this->site->updateReference('re');
            }
			$total_amount_to_dep = 0;
            foreach ($items as $item) {
                $item['return_id'] = $return_id;
                $this->db->insert('return_items', $item);

                if ($sale_item = $this->getSaleItemByID($item['sale_item_id'])) {
                    if ($sale_item->quantity == $item['quantity']) {
                        //$this->db->delete('sale_items', array('id' => $item['sale_item_id']));
                    } else {
                        $nqty = $sale_item->quantity - $item['quantity'];
                        $tax = $sale_item->unit_price - $sale_item->net_unit_price;
                        $discount = $sale_item->item_discount / $sale_item->quantity;
                        $item_tax = $tax * $nqty;
                        $item_discount = $discount * $nqty;
                        $subtotal = $sale_item->unit_price * $nqty;
                        //$this->db->update('sale_items', array('quantity' => $nqty, 'item_tax' => $item_tax, 'item_discount' => $item_discount, 'subtotal' => $subtotal), array('id' => $item['sale_item_id']));
						//$this->db->where('id', $data['sale_id']);
						//$this->db->update('sales', array('return_id' => $item['return_id']));
                    }
                }
				$total_amount_to_dep += $item['subtotal'];
                $this->site->syncQuantity(NULL, NULL, NULL, $item['product_id']);
            }
            if (!empty($payment)) {
                $payment['sale_id'] = $data['sale_id'];
                $payment['return_id'] = $return_id;
                $payment['pos_paid'] = $payment['amount'];
                $this->db->insert('payments', $payment);
                if ($this->site->getReference('sp') == $data['reference_no']) {
                    $this->site->updateReference('sp');
                }

				if($dep_sale = $this->getSaleById($data['sale_id'])){
					if($dep_sale->payment_status == 'paid' || $dep_sale->payment_status == 'partial'){
						$total_amount_to_dep = $dep_sale->paid - $payment['amount'];
						$dep_arr = array(
							'date' => $payment['date'],
							'company_id' => $data['customer_id'],
							'amount' => $total_amount_to_dep,
							'paid_by' => $payment['paid_by'],
							'note' => $payment['note'],
							'created_by' => $payment['created_by'],
							'biller_id' => $payment['biller_id']
						);

						if($this->db->insert('deposits', $dep_arr)){
							$deposit_id = $this->db->insert_id();
							$customer_dep = $this->site->getDepositByCompanyID($data['customer_id']);
							if($customer_dep->deposit_amount){
								$total_amount_to_dep = $customer_dep->deposit_amount + $total_amount_to_dep;
							}
							if($this->db->update('companies', array('deposit_amount' => $total_amount_to_dep), array('id' => $data['customer_id']))){
								if($total_amount_to_dep > 0){
									$payment_dep = array(
										'date' => $payment['date'],
										'deposit_id' => $deposit_id,
										'reference_no' => $data['reference_no'],
										'amount' => $total_amount_to_dep,
										'paid_by' => $payment['paid_by'],
										'note' => 'Deposit',
										'created_by' => $payment['created_by'],
										'type' => 'received',
										'biller_id'	=> $payment['biller_id']
									);
									$this->db->insert('payments', $payment_dep);
								}
							}
						}
					}
				}
				
                //$this->site->syncSalePayments($data['sale_id']);
				/*
				$sale = $this->site->getSaleByID($data['sale_id']);
				$payments = $this->site->getSalePayments($data['sale_id']);
				$paid = 0;
				foreach ($payments as $payment) {
					if ($payment->type == 'returned') {
						$paid -= $payment->amount;
						//$paid -= $sale->paid;
					} else {
						$paid += $payment->amount;
						//$paid += $sale->paid;
					}
				}

				$payment_status = $paid <= 0 ? 'pending' : $sale->payment_status;
				if ($paid <= 0 && $sale->due_date <= date('Y-m-d')) {
					if ($payment->type == 'returned') {
						$payment_status = 'returned';
						$payment_term = 0;
						$paid = -1 * abs($paid);
					}else{
						$payment_status = 'due';
					}
				} elseif ($this->erp->formatDecimal($sale->grand_total) > $this->erp->formatDecimal($paid) && $paid > 0) {
					$payment_status = 'partial';
				} elseif ($this->erp->formatDecimal($sale->grand_total) <= $this->erp->formatDecimal($paid)) {
					if ($payment->type == 'returned') {
						$payment_status = 'returned';
						$paid = -1 * abs($paid);
					}else{
						$payment_status = 'paid';
					}
					$payment_term = 0;
				}
				*/
				
            }
			$this->calculateSaleTotalsReturn($data['sale_id'], $return_id, $data['surcharge']);
            $this->site->syncQuantity(NULL, NULL, $sale_items);
            return true;
        }
        return false;
    }
	
	/* Return Sales */
	public function returnSales($data = array(), $items = array(), $payment = array())
    {
        //$this->erp->print_arrays($data, $items, $payment);
        foreach ($items as $item) {
            if ($item['product_type'] == 'combo') {
                $combo_items = $this->site->getProductComboItems($item['product_id'], $item['warehouse_id']);
                foreach ($combo_items as $combo_item) {
                    if ($costings = $this->getCostingLines($item['sale_item_id'], $combo_item->id)) {
                        $quantity = $item['quantity']*$combo_item->qty;
                        foreach ($costings as $cost) {
                            if ($cost->quantity >= $quantity) {
                                $qty = $cost->quantity - $quantity;
                                $bln = $cost->quantity_balance && $cost->quantity_balance >= $quantity ? $cost->quantity_balance - $quantity : 0;
                                $this->db->update('costing', array('quantity' => $qty, 'quantity_balance' => $bln), array('id' => $cost->id));
                                $quantity = 0;
                            } elseif ($cost->quantity < $quantity) {
                                $qty = $quantity - $cost->quantity;
                                $this->db->delete('costing', array('id' => $cost->id));
                                $quantity = $qty;
                            }
                        }
                    }
                    $this->updatePurchaseItem(NULL,($item['quantity']*$combo_item->qty), NULL, $combo_item->id, $item['warehouse_id']);
                }
            } else {
                if ($costings = $this->getCostingLines($item['sale_item_id'], $item['product_id'])) {
                    $quantity = $item['quantity'];
                    foreach ($costings as $cost) {
                        if($cost->option_id != 0 || $cost->option_id != NULL) {
							$quantity = $quantity * $cost->qty_unit;
							if (($cost->quantity* $cost->qty_unit) > $quantity) {
								$qty = ($cost->quantity * $cost->qty_unit) - $quantity;
								$bln = $cost->quantity_balance && $cost->quantity_balance >= $quantity ? $cost->quantity_balance - $quantity : 0;
								$this->db->set('quantity',$qty/$cost->qty_unit);
								$this->db->update('costing', array('quantity_balance' => $bln), array('id' => $cost->id));
								$quantity = 0;
							} elseif (($cost->quantity*$cost->qty_unit) <= $quantity) {
								$qty = $quantity - ($cost->quantity*$cost->qty_unit);
								$this->db->delete('costing', array('id' => $cost->id));
								$quantity = $qty;
							}
						} else {
							if ($cost->quantity >= $quantity) {
								$qty = $cost->quantity - $quantity;
								$bln = $cost->quantity_balance && $cost->quantity_balance >= $quantity ? $cost->quantity_balance - $quantity : 0;
								$this->db->update('costing', array('quantity' => $qty, 'quantity_balance' => $bln), array('id' => $cost->id));
								$quantity = 0;
							} elseif ($cost->quantity < $quantity) {
								$qty = $quantity - $cost->quantity;
								$this->db->delete('costing', array('id' => $cost->id));
								$quantity = $qty;
							}
						}
                    }
                }
                //$this->updatePurchaseItem(NULL, $item['quantity']*$cost->qty_unit, $item['sale_item_id'], $item['product_id'], $item['warehouse_id'], $item['option_id']);
				$this->updatePurchaseItem(NULL, $item['quantity']*($cost->qty_unit?$cost->qty_unit:1), $item['sale_item_id'], $item['product_id'], $item['warehouse_id'], $item['option_id']);
            }
        }
		//$this->erp->print_arrays($items);
        //$sale_items = $this->site->getAllSaleItems($data['sale_id']);
		
        if ($this->db->insert('return_sales', $data)) {
            $return_id = $this->db->insert_id();
            //$return_sale_item = $this->getReturnItemByReturnID($return_id);
            if ($this->site->getReference('re') == $data['reference_no']) {
                $this->site->updateReference('re');
            }
            $sale_items = array();
            $sale_id = 0;
            foreach ($items as $item) {
                $sale_id = $item['sale_id'];
                
				$sale_items = $this->site->getAllSaleItems($sale_id);
                $item['return_id'] = $return_id;
                $this->db->insert('return_items', $item);
				
				if($sale_id){
					$this->calculateSaleTotalsReturn($sale_id, $return_id, $data['surcharge']);
				}
                
                if ($item['sale_item_id']) {
                    if ($sale_item = $this->getSaleItemByID($item['sale_item_id'])) {
                        if ($sale_item->quantity == $item['quantity']) {
                            //$this->db->delete('sale_items', array('id' => $item['sale_item_id']));
                        } else {
                            $nqty = $sale_item->quantity - $item['quantity'];
                            $tax = $sale_item->unit_price - $sale_item->net_unit_price;
                            $discount = $sale_item->item_discount / $sale_item->quantity;
                            $item_tax = $tax * $nqty;
                            $item_discount = $discount * $nqty;
                            $subtotal = $sale_item->unit_price * $nqty;
                            //$this->db->update('sale_items', array('quantity' => $nqty, 'item_tax' => $item_tax, 'item_discount' => $item_discount, 'subtotal' => $subtotal), array('id' => $item['sale_item_id']));
                        }
                    }
                }
                $this->site->syncQuantity(NULL, NULL, NULL, $item['product_id']);
				$this->site->syncQuantity(NULL, NULL, $sale_items);
            }
            if (!empty($payment)) {
                $data['sale_id'] = $sale_id;
                if($data['sale_id']){
                    $payment['sale_id'] = $data['sale_id'];
                    $payment['return_id'] = $return_id;
                    $payment['pos_paid'] = $payment['amount'];
                    $this->db->insert('payments', $payment);
                    if ($this->site->getReference('pay') == $data['reference_no']) {
                        $this->site->updateReference('pay');
                    }
                    $this->calculateSaleTotalsReturn($data['sale_id'], $return_id, $data['surcharge']);
                } else {
                    $payment['return_id'] = $return_id;
                    $this->db->insert('payments', $payment);
                    if ($this->site->getReference('pay') == $data['reference_no']) {
                        $this->site->updateReference('pay');
                    }
                    $this->calculateSaleTotalsReturn($data['sale_id'], $return_id, $data['surcharge']);
                }
            }
          //  $this->site->syncQuantity(NULL, NULL, $sale_items);
            return true;
        }
        return false;
    }

    public function getCostingLines($sale_item_id, $product_id)
    {
		$this->db->select('costing.*, product_variants.qty_unit');
		$this->db->join('product_variants', 'product_variants.id=costing.option_id','left');
        $this->db->order_by('costing.id', 'asc');
        $q = $this->db->get_where('costing', array('costing.sale_item_id' => $sale_item_id, 'costing.product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getSaleItemByRefPID($sale_ref, $product_id)
    {
        $this->db->select('sale_items.id AS sale_item_id, sales.id AS sale_id');
        $this->db->join('sale_items', 'sale_items.sale_id = sales.id', 'inner');
        $q = $this->db->get_where('sales', array('sales.reference_no' => $sale_ref, 'sale_items.product_id' => $product_id));
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getSaleItemByRefPIDReturn($sale_ref, $product_id)
    {
        $this->db->select('sale_items.quantity');
        $this->db->join('sale_items', 'sale_items.sale_id = sales.id', 'inner');
        $q = $this->db->get_where('sales', array('sales.reference_no' => $sale_ref, 'sale_items.product_id' => $product_id));
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSaleItemByID($id)
    {
        $q = $this->db->get_where('sale_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getSaleItemByProductID($product_id)
    {
        $q = $this->db->get_where('sale_items', array('product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	function getSalesById($id){
		$q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    public function calculateSaleTotals($id, $return_id, $surcharge,$payment_status =NULL)
    {
        $sale = $this->getInvoiceByID($id);
        $items = $this->getAllInvoiceItems($id);

        if (!empty($items)) {
            $this->erp->update_award_points($sale->grand_total, $sale->customer_id, $sale->created_by, TRUE);
            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $total_items = 0;
            foreach ($items as $item) {
                $total_items += $item->quantity;
                $product_tax += $item->item_tax;
                $product_discount += $item->item_discount;
                $total += $item->net_unit_price * $item->quantity;
            }
            if ($sale->order_discount_id) {
                $percentage = '%';
                $order_discount_id = $sale->order_discount_id;
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = (($total + $product_tax) * (Float)($ods[0])) / 100;
                } else {
                    $order_discount = $order_discount_id;
                }
            }
            if ($sale->order_tax_id) {
                $order_tax_id = $sale->order_tax_id;
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $order_tax_details->rate;
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = (($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100;
                    }
                }
            }
            $total_discount = $order_discount + $product_discount;
            $total_tax = $product_tax + $order_tax;
            $grand_total = $total + $total_tax + $sale->shipping - $order_discount + $surcharge;
			if($payment_status){
				$data = array(
					'total' => $total,
					'product_discount' => $product_discount,
					'order_discount' => $order_discount,
					'total_discount' => $total_discount,
					'product_tax' => $product_tax,
					'order_tax' => $order_tax,
					'total_tax' => $total_tax,
					'grand_total' => $grand_total,
					'total_items' => $total_items,
					'return_id' => $return_id,
					'surcharge' => $surcharge,
					'payment_status' => $payment_status
				);
			}else{
				$data = array(
					'total' => $total,
					'product_discount' => $product_discount,
					'order_discount' => $order_discount,
					'total_discount' => $total_discount,
					'product_tax' => $product_tax,
					'order_tax' => $order_tax,
					'total_tax' => $total_tax,
					'grand_total' => $grand_total,
					'total_items' => $total_items,
					'return_id' => $return_id,
					'surcharge' => $surcharge
				);
			}
            
            if ($this->db->update('sales', $data, array('id' => $id))) {
                $this->erp->update_award_points($data['grand_total'], $sale->customer_id, $sale->created_by);
                return true;
            }
        } else {
            //$this->db->delete('sales', array('id' => $id));
            //$this->db->delete('payments', array('sale_id' => $id, 'return_id !=' => $return_id));
        }
        return FALSE;
    }
	
	public function calculateSaleTotalsReturn($id, $return_id, $surcharge = NULL,$payment_status =NULL)
    {
        $sale = $this->getInvoiceByID($id);
        $items = $this->getAllInvoiceItems($id);

        if (!empty($items)) {
            $this->erp->update_award_points($sale->grand_total, $sale->customer_id, $sale->created_by, TRUE);
            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $total_items = 0;
            foreach ($items as $item) {
                $total_items += $item->quantity;
                $product_tax += $item->item_tax;
                $product_discount += $item->item_discount;
                $total += $item->net_unit_price * $item->quantity;
            }
            if ($sale->order_discount_id) {
                $percentage = '%';
                $order_discount_id = $sale->order_discount_id;
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = (($total + $product_tax) * (Float)($ods[0])) / 100;
                } else {
                    $order_discount = $order_discount_id;
                }
            }
            if ($sale->order_tax_id) {
                $order_tax_id = $sale->order_tax_id;
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $order_tax_details->rate;
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = (($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100;
                    }
                }
            }
            $total_discount = $order_discount + $product_discount;
            $total_tax = $product_tax + $order_tax;
            $grand_total = $total + $total_tax + $sale->shipping - $order_discount + $surcharge;
			if($payment_status){
				$data = array(
					//'total' => $total,
					//'product_discount' => $product_discount,
					//'order_discount' => $order_discount,
					//'total_discount' => $total_discount,
					//'product_tax' => $product_tax,
					//'order_tax' => $order_tax,
					//'total_tax' => $total_tax,
					//'grand_total' => $grand_total,
					//'total_items' => $total_items,
					'return_id' => $return_id,
					//'surcharge' => $surcharge,
					'payment_status' => $payment_status
				);
			}else{
				$data = array(
					//'total' => $total,
					//'product_discount' => $product_discount,
					//'order_discount' => $order_discount,
					//'total_discount' => $total_discount,
					//'product_tax' => $product_tax,
					//'order_tax' => $order_tax,
					//'total_tax' => $total_tax,
					//'grand_total' => $grand_total,
					//'total_items' => $total_items,
					'return_id' => $return_id,
					'sale_status' => 'returned',
					//'surcharge' => $surcharge
				);
			}
            
            if ($this->db->update('sales', $data, array('id' => $id))) {
                $this->erp->update_award_points($data['grand_total'], $sale->customer_id, $sale->created_by);
                return true;
            }
        } else {
            //$this->db->delete('sales', array('id' => $id));
            //$this->db->delete('payments', array('sale_id' => $id, 'return_id !=' => $return_id));
        }
        return FALSE;
    }

    public function getProductByName($name)
    {
        $q = $this->db->get_where('products', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addDelivery($data = array())
    {
        if ($this->db->insert('deliveries', $data)) {
            if ($this->site->getReference('do') == $data['do_reference_no']) {
                $this->site->updateReference('do');
            }
            return true;
        }
        return false;
    }

    public function updateDelivery($id, $data = array())
    {
        if ($this->db->update('deliveries', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }
	
	public function completedDeliveries($id)
    {
        if ($this->db->update('deliveries', array('delivery_status' => 'completed'), array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function getDeliveryByID($id)
    {
        $q = $this->db->get_where('deliveries', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getDeliveryBySaleID($sale_id)
    {
        $q = $this->db->get_where('deliveries', array('sale_id' => $sale_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function deleteDelivery($id)
    {
        if ($this->db->delete('deliveries', array('sale_id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getInvoicePayments($sale_id)
    {
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getPaymentByID($id)
    {	$this->db->select('payments.*, sales.customer, companies.deposit_amount,
						  CONCAT(erp_companies.family_name," ",erp_companies.name) as c_name,
						  CONCAT(erp_users.first_name," ",erp_users.last_name) as co_name');
		$this->db->join('sales','payments.sale_id = sales.id','left');
		$this->db->join('users','users.id=sales.created_by','inner');	
		$this->db->join('companies','companies.id = sales.customer_id','left');
        $q = $this->db->get_where('payments', array('payments.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	/*public function getPaymentByID($id)
    {	$this->db->select('payments.*, sales.customer, companies.deposit_amount,service_payments.payment_id,service_payments.amount as amt,payments.principle_amount,payments.interest_amount,payments.penalty_amount,payments.other_paid');
		$this->db->join('sales','payments.sale_id = sales.id','left');
		$this->db->join('companies','companies.id = sales.customer_id','left');
		$this->db->join('service_payments','service_payments.payment_id = payments.id','left');
        $q = $this->db->get_where('payments', array('payments.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }*/
	
	public function getPaymentTypes($id)
    {	$this->db->select('payments.amount,payments.principle_amount,payments.interest_amount,payments.penalty_amount,payments.other_paid,payments.owed, currencies.name,sale_items.currency_code');
		$this->db->join('sale_items','sale_items.sale_id = payments.sale_id','LEFT');
		$this->db->join('currencies','currencies.code = sale_items.currency_code','LEFT');
        $this->db->where(array('payments.id' => $id));
		$q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getServicePayment($id)
    {	$this->db->select('service_payments.amount as amt, services.description,
						   (SELECT description FROM erp_services WHERE id = erp_service_payments.service_id)AS description');
		$this->db->join('services','services.id = service_payments.id','left');
        $this->db->where(array('service_payments.payment_id' => $id));
		$q = $this->db->get('service_payments');
        if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
    }
	public function getCurrentBalance($sale_id)
	{
		$this->db->select('id, amount, extra_paid')
				 ->order_by('id', 'asc');
		$this->db->where(array('group_payment_id' => $sale_id))->or_where(array('sale_id' => $sale_id));
		$q = $this->db->get_where('payments');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}
	
	public function getPurchaseByID($id)
	{
		$this->db->select('purchases.date,purchases.reference_no,purchases.paid,purchases.biller_id,purchases.supplier_id,payments.paid_by')
            ->join('payments','purchases.id=payments.purchase_id','left');
        $q = $this->db->get_where('purchases', array('purchases.id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    public function getPaymentsForSale($sale_id)
    {
        $this->db->select('payments.date, payments.paid_by, payments.amount,payments.pos_paid, payments.cc_no, payments.cheque_no, payments.reference_no, users.first_name, users.last_name, type')
            ->join('users', 'users.id=payments.created_by', 'left');
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function addPayment($data = array())
    {
		$deposit_customer_id = $data['deposit_customer_id'];
		unset($data['deposit_customer_id']);
        if ($this->db->insert('payments', $data)) {
            if ($this->site->getReference('sp') == $data['reference_no']) {
                $this->site->updateReference('sp');
            }
            $this->site->syncSalePayments($data['sale_id']);
            if ($data['paid_by'] == 'gift_card') {
                $gc = $this->site->getGiftCardByNO($data['cc_no']);
                $this->db->update('gift_cards', array('balance' => ($gc->balance - $data['amount'])), array('card_no' => $data['cc_no']));
            }
			 if($data['paid_by'] == 'deposit'){
				$deposit = $this->site->getDepositByCompanyID($deposit_customer_id);
				$deposit_balance = $deposit->deposit_amount;
				$deposit_balance = $deposit_balance - abs($data['amount']);
				if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $deposit_customer_id))){
					$this->db->update('deposits', array('amount' => $deposit_balance), array('company_id' => $deposit_customer_id));
				}
			}
            return true;
        }
        return false;
    }
	
	public function addSalePaymentLoan($data = array())
	{
		$id = $data['id'];

        if ($this->db->update('sales', $data, array('id' => $id))) {
            return true;
        }
        return false;
	}
	
	public function addPaymentLoan($data = array())
    {
		//$this->erp->print_arrays($data);
		$id = $data['id'];
		$sale_loan = $this->sales_model->getSaleId($id);
        if ($this->db->update('loans', $data, array('id' => $id))) {
			return true;
        }
        return false;
    }
	
	public function addLoanPayment($payments = array())
	{
		if ($this->db->insert('payments', $payments)) {
				if ($this->site->getReference('sp') == $payments['reference_no']) {
					$this->site->updateReference('sp');
				}
				$this->site->syncSalePayments($payments['sale_id']);
				if ($payments['paid_by'] == 'gift_card') {
					$gc = $this->site->getGiftCardByNO($payments['cc_no']);
					$this->db->update('gift_cards', array('balance' => ($gc->balance - $payments['amount'])), array('card_no' => $payments['cc_no']));
				}
				return true;
			}
	}

    public function updatePayment($id, $data = array())
    {
        if ($this->db->update('payments', $data, array('id' => $id))) {
            $this->site->syncSalePayments($data['sale_id']);
            return true;
        }
        return false;
    }
	
	public function getSaleId($id)
	{
		$q = $this->db->get_where('loans', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getSaleById($id)
	{
		$q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
    
    public function getSaleByRef($ref)
	{
		$q = $this->db->get_where('sales', array('reference_no' => $ref), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getLoanView($id)
	{
		$this->db->order_by('period','DESC');
		$q = $this->db->get_where('loans', array('sale_id' => $id, 'period' => '1'), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getMonths($id)
	{
		$this->db->order_by('period','DESC');
		$q = $this->db->get_where('loans', array('sale_id' => $id), 1);
		
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    public function deletePayment($id)
    {
        $opay = $this->getPaymentByID($id);
        if ($this->db->delete('payments', array('id' => $id))) {
            $this->site->syncSalePayments($opay->sale_id);
            return true;
        }
        return FALSE;
    }

    public function getWarehouseProductQuantity($warehouse_id, $product_id)
    {
        $q = $this->db->get_where('warehouses_products', array('warehouse_id' => $warehouse_id, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    /* ----------------- Gift Cards --------------------- */

    public function addGiftCard($data = array(), $ca_data = array(), $sa_data = array())
    {
        if ($this->db->insert('gift_cards', $data)) {
            if (!empty($ca_data)) {
                $this->db->update('companies', array('award_points' => $ca_data['points']), array('id' => $ca_data['customer']));
            } elseif (!empty($sa_data)) {
                $this->db->update('users', array('award_points' => $sa_data['points']), array('id' => $sa_data['user']));
            }
            return true;
        }
        return false;
    }

    public function updateGiftCard($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('gift_cards', $data)) {
            return true;
        }
        return false;
    }

    public function deleteGiftCard($id)
    {
        if ($this->db->delete('gift_cards', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getPaypalSettings()
    {
        $q = $this->db->get_where('paypal', array('id' => 1));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSkrillSettings()
    {
        $q = $this->db->get_where('skrill', array('id' => 1));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getQuoteByID($id)
    {
        $q = $this->db->get_where('quotes', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllQuoteItems($quote_id)
    {
        $q = $this->db->get_where('quote_items', array('quote_id' => $quote_id));
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
        if (!$this->Owner) {
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

    public function getProductVariantByName($name, $product_id)
    {
        $q = $this->db->get_where('product_variants', array('name' => $name, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTaxRateByName($name)
    {
        $q = $this->db->get_where('tax_rates', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getCombinePaymentById($id)
    {
		$this->db->select('id, date, reference_no, biller, customer, sale_status, grand_total, paid, (grand_total-paid) as balance, payment_status');
		$this->db->from('sales');
		$this->db->where_in('id', $id);
        $q = $this->db->get();
         if ($q->num_rows() > 0) {
            return $q;
        }
		return FALSE;
    }
	
	public function getSampleSaleRefByProductID($product_id){
		$q = $this->db->select('MAX(reference_no) AS reference_no')
					->join('sale_items', 'sale_items.sale_id = sales.id', 'left')
					->where('sale_items.product_id', $product_id)
					->get('sales');
		if($q->num_rows() > 0){
			return $q->row()->reference_no;
		}
	}
	
	function getSetting()
    {
        $q = $this->db->get('pos_settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	function add_booking($id, $data){
		$this->db->where('id', $id);
		$this->db->update('erp_suspended',$data);
		return $this->db->affected_rows();
	}
	
	function certify_latter_report($id)
    {	
		$this->db
                ->select($this->db->dbprefix('sales').".id,".
						$this->db->dbprefix('sales').".reference_no,
						CONCAT(".$this->db->dbprefix('users').".last_name, ' ', ".$this->db->dbprefix('users').".first_name) as creater_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name, ' ', ".$this->db->dbprefix('companies').".name) as customer_name,
						CONCAT(".$this->db->dbprefix('companies').".family_name_other, ' ', ".$this->db->dbprefix('companies').".name_other) as customer_name_other, ".
						$this->db->dbprefix('sales').".biller, ".
						$this->db->dbprefix('sales').".date, ".
						$this->db->dbprefix('sales').".term, ".
						$this->db->dbprefix('sales').".due_date, ".
						$this->db->dbprefix('sales').".approved_date, ".
						$this->db->dbprefix('users').".username, ".
						$this->db->dbprefix('companies').".gov_id, ".
						$this->db->dbprefix('companies').".account_number, ".
						$this->db->dbprefix('companies').".phone1, ".
						$this->db->dbprefix('sale_items').".product_name, ".
						$this->db->dbprefix('sale_items').".product_year, ".
						$this->db->dbprefix('sale_items').".engine, ".
						$this->db->dbprefix('sale_items').".frame, ".
						$this->db->dbprefix('sale_items').".power, ".
						$this->db->dbprefix('sale_items').".quantity, ".
						$this->db->dbprefix('variants').".name, ".
						$this->db->dbprefix('variants').".name_other, ".
						$this->db->dbprefix('type').".name as type_name, ".
						$this->db->dbprefix('subcategories').".name as subcategory_name, ".
						$this->db->dbprefix('sales').".total,
						CONCAT((".$this->db->dbprefix('sales').".advance_percentage_payment * 100), '%') as percentage,".
						$this->db->dbprefix('sales').".advance_payment,
						SUM(".$this->db->dbprefix('sale_services').".amount) as total_service_charge,".
						$this->db->dbprefix('sales').".second_payment,
						((COALESCE(".$this->db->dbprefix('sales').".grand_total, 0) - COALESCE(".$this->db->dbprefix('sales').".advance_payment, 0))) as total_amount,".
						$this->db->dbprefix('sales').".sale_status")
                ->from('sales')
				->join('users','sales.created_by=users.id','INNER')
				->join('sale_items', 'sales.id = sale_items.sale_id', 'INNER')
				
				->join('companies', 'sales.customer_id = companies.id', 'INNER')
				->join('products', 'sale_items.product_id = products.id', 'INNER')
				->join('type', 'sale_items.product_type = type.id', 'INNER')
				->join('subcategories', 'type.subcategory_id = subcategories.id', 'INNER')
				->join('sale_services', 'sales.id = sale_services.sale_id', 'left')
				->join('variants', 'variants.id = sale_items.color', 'left')
				->where($this->db->dbprefix('sales').'.id =', $id)
				->group_by('sales.id');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getSaleIDByLoanID($id = NULL) {
		$ids = '';
		if($id) {
			$ids = explode('_', $id);
		}
		if(count($ids)) {
			$q = $this->db->get_where('loans', array('id' => $ids[0]));
			if ($q->num_rows() > 0) {
				return $q->row();
			}
		}
        return FALSE;
	}
	public function getSaleServicesBySaleID($sale_id = null) {
		$q = $this->db->get_where('sale_services', array('sale_id' => $sale_id));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getSaleItemsById($id = null) {
		$q = $this->db->get_where('sale_items', array('sale_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
}
