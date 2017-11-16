<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function insertConvert($data)
    {
        if ($this->db->insert('convert', $data)) {
            $convert_id = $this->db->insert_id();
			
			if ($this->site->getReference('con') == $data['reference_no']) {
				$this->site->updateReference('con');
			}
            return $convert_id;
        }
    }
	
	public function updateConvert($id, $data) {
        if ($this->db->update('convert', $data, array('id' => $id))) {
            return true;
        }
        return false;
	}
	
	public function getConvertByID($id)
    {
		$this->db->select("convert.id, convert.date, convert.reference_no, SUM(".$this->db->dbprefix('convert_items').".quantity) AS Quantity, products.cost, convert_items.product_name, convert.noted, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ', " . $this->db->dbprefix('users') . ".last_name) as user, convert.created_by, convert.warehouse_id")
             ->join('users', 'users.id=convert.created_by', 'left')
			 ->join('convert_items', 'convert.id=convert_items.convert_id', 'left')
			 ->join('products', 'convert_items.product_id = products.id');
        $q = $this->db->get_where('convert', array('convert.id' => $id,'convert_items.status ='=>'add'), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function ConvertDeduct($id)
    {
        $this->db->select('product_name,'.$this->db->dbprefix('convert_items').'.quantity AS Cquantity,'.$this->db->dbprefix('convert_items').'.cost AS Ccost,'.$this->db->dbprefix('products').'.cost AS Pcost')
				->join('products', 'products.id=convert_items.product_id');
		$q = $this->db->get_where('convert_items', array('convert_id' => $id, 'status' => 'deduct'));
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	
	public function ConvertAdd($id)
    {
       $this->db->select('product_name,'.$this->db->dbprefix('convert_items').'.quantity AS Cquantity,'.$this->db->dbprefix('convert_items').'.cost AS Ccost,'.$this->db->dbprefix('products').'.cost AS Pcost')
				->join('products', 'products.id=convert_items.product_id');
		$q = $this->db->get_where('convert_items', array('convert_id' => $id, 'status' => 'add'));
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	
	public function getConvert_ItemByID($id)
    {
		$this->db->select('convert_items.id, convert_items.convert_id, 
							convert_items.product_id, convert_items.product_code, 
							convert_items.product_name, convert_items.quantity, convert_items.cost, convert_items.status, products.unit')
							->join('products', 'products.id = convert_items.product_id', 'left');
		$q = $this->db->get_where("convert_items", array('convert_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function deleteConvert($id)
    {
        if ($this->db->delete('convert', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	
	public function deleteConvert_items($id)
    {
        if ($this->db->delete('convert_items', array('convert_id' => $id))) {
            return true;
        }
        return FALSE;
    }
	public function deleteConvert_itemsByPID($id, $product_id)
    {
        if ($this->db->delete('convert_items', array('convert_id' => $id, 'product_id' => $product_id))) {
            return true;
        }
        return FALSE;
    }

    public function getAllProducts()
    {
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }
	public function getCategoryProducts($category_id)
    {
        $q = $this->db->get_where('products', array('category_id' => $category_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getSubCategoryProducts($subcategory_id)
    {
        $q = $this->db->get_where('products', array('subcategory_id' => $subcategory_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
	public function getProductWithCategory($id)
    {
        $this->db->select($this->db->dbprefix('products') . '.*, ' . $this->db->dbprefix('categories') . '.name as category')
        ->join('categories', 'categories.id=products.category_id', 'left');
        $q = $this->db->get_where('products', array('products.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductOptions($pid)
    {
        $q = $this->db->get_where('product_variants', array('product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductOptionsWithWH($pid)
    {
        $this->db->select($this->db->dbprefix('product_variants') . '.*, ' . $this->db->dbprefix('warehouses') . '.name as wh_name, ' . $this->db->dbprefix('warehouses') . '.id as warehouse_id, ' . $this->db->dbprefix('warehouses_products_variants') . '.quantity as wh_qty, ' . $this->db->dbprefix('product_variants') . '.qty_unit, ('.$this->db->dbprefix('product_variants').'.cost * '.$this->db->dbprefix('product_variants').'.qty_unit) AS variant_cost ')
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left')
            ->join('warehouses', 'warehouses.id=warehouses_products_variants.warehouse_id', 'left')
            ->group_by(array('' . $this->db->dbprefix('product_variants') . '.id', '' . $this->db->dbprefix('warehouses_products_variants') . '.warehouse_id'))
            ->order_by('product_variants.qty_unit DESC');
        $q = $this->db->get_where('product_variants', array('product_variants.product_id' => $pid, 'warehouses_products_variants.quantity !=' => NULL));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getProductComboItems($pid)
    {
        $this->db->select($this->db->dbprefix('products') . '.id as id, ' . $this->db->dbprefix('products') . '.code as code, ' . $this->db->dbprefix('combo_items') . '.quantity as qty, ' . $this->db->dbprefix('products') . '.name as name, ' . $this->db->dbprefix('combo_items') . '.unit_price as price')->join('products', 'products.code=combo_items.item_code', 'left')->group_by('combo_items.id');
        $q = $this->db->get_where('combo_items', array('product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }

    public function getProductByID($id)
    {
        $q = $this->db->get_where('products', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function has_purchase($product_id, $warehouse_id = NULL)
    {
        if($warehouse_id) { $this->db->where('warehouse_id', $warehouse_id); }
        $q = $this->db->get_where('purchase_items', array('product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    public function getProductDetails($id)
    {
        $this->db->select($this->db->dbprefix('products') . '.code, ' . $this->db->dbprefix('products') . '.name, ' . $this->db->dbprefix('categories') . '.code as category_code, cost, price, quantity, alert_quantity')
            ->join('categories', 'categories.id=products.category_id', 'left');
        $q = $this->db->get_where('products', array('products.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductDetail($id)
    {
        $this->db->select($this->db->dbprefix('products') . '.*, ' . $this->db->dbprefix('tax_rates') . '.code as tax_rate_code, ' . $this->db->dbprefix('categories') . '.name as category_name, ' . $this->db->dbprefix('subcategories') . '.code as subcategory_code, ' . $this->db->dbprefix('warehouses') . '.name as wname')
            ->join('tax_rates', 'tax_rates.id=products.tax_rate', 'left')
            ->join('categories', 'categories.id=products.category_id', 'left')
            ->join('subcategories', 'subcategories.id=products.subcategory_id', 'left')
			->join('warehouses', 'warehouses.id=products.warehouse', 'left')
			->group_by("products.id")->order_by('products.id desc');
        $q = $this->db->get_where('products', array('products.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductByCategoryID($id)
    {

        $q = $this->db->get_where('products', array('category_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return true;
        }

        return FALSE;
    }

    public function getAllWarehousesWithPQ($product_id)
    {
        $this->db->select('' . $this->db->dbprefix('warehouses') . '.*, ' . $this->db->dbprefix('warehouses_products') . '.quantity, ' . $this->db->dbprefix('warehouses_products') . '.rack')
            ->join('warehouses_products', 'warehouses_products.warehouse_id=warehouses.id', 'left')
            ->where('warehouses_products.product_id', $product_id)
            ->group_by('warehouses.id');
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductPhotos($id)
    {
        $q = $this->db->get_where("product_photos", array('product_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }
	public function getOptionId($product_id,$name)
    {

        $q = $this->db->get_where('product_variants', array('product_id' => $product_id, 'name'=>$name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }
	
	public function getProductVariantByOptionID($option_id)
    {
        $q = $this->db->get_where('product_variants', array('id' => $option_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
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

    public function addProduct($data, $items, $warehouse_qty, $product_attributes, $photos)
    {
		//$this->erp->print_arrays($data, $items, $warehouse_qty, $product_attributes, $photos);
        if ($this->db->insert('products', $data)) {
            $product_id = $this->db->insert_id();

            if ($items) {
                foreach ($items as $item) {
                    $item['product_id'] = $product_id;
                    $this->db->insert('combo_items', $item);
                }
            }

            if ($data['type'] == 'combo' || $data['type'] == 'service') {
                $warehouses = $this->site->getAllWarehouses();
                foreach ($warehouses as $warehouse) {
                    $this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse->id, 'quantity' => 0));
                }
            }

            $tax_rate = $this->site->getTaxRateByID($data['tax_rate']);

            if ($warehouse_qty && !empty($warehouse_qty)) {
                foreach ($warehouse_qty as $wh_qty) {
                    if (isset($wh_qty['quantity']) && ! empty($wh_qty['quantity'])) {
                        $this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $wh_qty['warehouse_id'], 'quantity' => $wh_qty['quantity'], 'rack' => $wh_qty['rack']));

                        if (!$product_attributes) {
                            $tax_rate_id = $tax_rate ? $tax_rate->id : NULL;
                            $tax = $tax_rate ? (($tax_rate->type == 1) ? $tax_rate->rate . "%" : $tax_rate->rate) : NULL;
                            $unit_cost = $data['cost'];
                            if ($tax_rate) {
                                if ($tax_rate->type == 1 && $tax_rate->rate != 0) {
                                    if ($data['tax_method'] == '0') {
                                        $pr_tax_val = ($data['cost'] * $tax_rate->rate) / (100 + $tax_rate->rate);
                                        $net_item_cost = $data['cost'] - $pr_tax_val;
                                        $item_tax = $pr_tax_val * $wh_qty['quantity'];
                                    } else {
                                        $net_item_cost = $data['cost'];
                                        $pr_tax_val = ($data['cost'] * $tax_rate->rate) / 100;
                                        $unit_cost = $data['cost'] + $pr_tax_val;
                                        $item_tax = $pr_tax_val * $wh_qty['quantity'];
                                    }
                                } else {
                                    $net_item_cost = $data['cost'];
                                    $item_tax = $tax_rate->rate;
                                }
                            } else {
                                $net_item_cost = $data['cost'];
                                $item_tax = 0;
                            }

                            $subtotal = (($net_item_cost * $wh_qty['quantity']) + $item_tax);

                            $item = array(
                                'product_id' => $product_id,
                                'product_code' => $data['code'],
                                'product_name' => $data['name'],
                                'net_unit_cost' => $net_item_cost,
                                'unit_cost' => $unit_cost,
                                'quantity' => $wh_qty['quantity'],
                                'quantity_balance' => $wh_qty['quantity'],
                                'item_tax' => $item_tax,
                                'tax_rate_id' => $tax_rate_id,
                                'tax' => $tax,
                                'subtotal' => $subtotal,
                                'warehouse_id' => $wh_qty['warehouse_id'],
                                'date' => date('Y-m-d'),
                                'status' => 'received',
                            );
                            $this->db->insert('purchase_items', $item);
                            $this->site->syncProductQty($product_id, $wh_qty['warehouse_id']);
                        }
                    }
                }
            }

            if ($product_attributes) {
                foreach ($product_attributes as $pr_attr) {
                    $pr_attr_details = $this->getPrductVariantByPIDandName($product_id, $pr_attr['name']);

                    $pr_attr['product_id'] = $product_id;
                    $variant_warehouse_id = $pr_attr['warehouse_id'];
                    unset($pr_attr['warehouse_id']);
                    if ($pr_attr_details) {
                        $option_id = $pr_attr_details->id;
                    } else {
                        $this->db->insert('product_variants', $pr_attr);
                        $option_id = $this->db->insert_id();
                    }
                    if ($pr_attr['quantity'] != 0) {
                        $this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $variant_warehouse_id, 'quantity' => $pr_attr['quantity']));

                        $tax_rate_id = $tax_rate ? $tax_rate->id : NULL;
                        $tax = $tax_rate ? (($tax_rate->type == 1) ? $tax_rate->rate . "%" : $tax_rate->rate) : NULL;
                        $unit_cost = $data['cost'];
                        if ($tax_rate) {
                            if ($tax_rate->type == 1 && $tax_rate->rate != 0) {
                                if ($data['tax_method'] == '0') {
                                    $pr_tax_val = ($data['cost'] * $tax_rate->rate) / (100 + $tax_rate->rate);
                                    $net_item_cost = $data['cost'] - $pr_tax_val;
                                    $item_tax = $pr_tax_val * $pr_attr['quantity'];
                                } else {
                                    $net_item_cost = $data['cost'];
                                    $pr_tax_val = ($data['cost'] * $tax_rate->rate) / 100;
                                    $unit_cost = $data['cost'] + $pr_tax_val;
                                    $item_tax = $pr_tax_val * $pr_attr['quantity'];
                                }
                            } else {
                                $net_item_cost = $data['cost'];
                                $item_tax = $tax_rate->rate;
                            }
                        } else {
                            $net_item_cost = $data['cost'];
                            $item_tax = 0;
                        }

                        $subtotal = (($net_item_cost * $pr_attr['quantity']) + $item_tax);
                        $item = array(
                            'product_id' => $product_id,
                            'product_code' => $data['code'],
                            'product_name' => $data['name'],
                            'net_unit_cost' => $net_item_cost,
                            'unit_cost' => $unit_cost,
                            'quantity' => $pr_attr['quantity'],
                            'option_id' => $option_id,
                            'quantity_balance' => $pr_attr['quantity'],
                            'item_tax' => $item_tax,
                            'tax_rate_id' => $tax_rate_id,
                            'tax' => $tax,
                            'subtotal' => $subtotal,
                            'warehouse_id' => $variant_warehouse_id,
                            'date' => date('Y-m-d'),
                            'status' => 'received',
                        );
                        $this->db->insert('purchase_items', $item);

                    }

                    $this->site->syncVariantQty($option_id, $variant_warehouse_id);
                }
            }

            if ($photos) {
                foreach ($photos as $photo) {
                    $this->db->insert('product_photos', array('product_id' => $product_id, 'photo' => $photo));
                }
            }

            return true;
        }

        return false;

    }

    public function getPrductVariantByPIDandName($product_id, $name)
    {
        $q = $this->db->get_where('product_variants', array('product_id' => $product_id, 'name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function addAjaxProduct($data)
    {

        if ($this->db->insert('products', $data)) {
            $product_id = $this->db->insert_id();
            return $this->getProductByID($product_id);
        }

        return false;

    }

    public function add_products($products = array())
    {
        if (!empty($products)) {
            foreach ($products as $product) {
                $variants = explode('|', $product['variants']);
                unset($product['variants']);
                if ($this->db->insert('products', $product)) {
                    $product_id = $this->db->insert_id();
                    foreach ($variants as $variant) {
                        if ($variant && trim($variant) != '') {
                            $vat = array('product_id' => $product_id, 'name' => trim($variant), 'qty_unit' => 1);
                            $this->db->insert('product_variants', $vat);
                        }
                    }
                }
            }
            return true;
        }
        return false;
    }

    public function getProductNames($term, $limit = 5)
    {
        $this->db->where("type = 'standard' AND (name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getProductNumber($term, $limit = 5)
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
			$this->db->where("type = 'standard' AND (code LIKE '%" . $term . "%')");
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
	
	public function getProductCode($term)
    {
        $this->db->select('code');
		$q = $this->db->get_where('products', array('code' => $term), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
    }

    public function updateProduct($id, $data, $items, $warehouse_qty, $product_attributes, $photos, $update_variants)
    {
		//$this->erp->print_arrays($id, $data, $items, $warehouse_qty, $product_attributes, $photos, $update_variants);
        if ($this->db->update('products', $data, array('id' => $id))) {

            if ($items) {
                $this->db->delete('combo_items', array('product_id' => $id));
                foreach ($items as $item) {
                    $item['product_id'] = $id;
                    $this->db->insert('combo_items', $item);
                }
            }

            $tax_rate = $this->site->getTaxRateByID($data['tax_rate']);

            if ($warehouse_qty && !empty($warehouse_qty)) {
                foreach ($warehouse_qty as $wh_qty) {
                    $this->db->update('warehouses_products', array('rack' => $wh_qty['rack']), array('product_id' => $id, 'warehouse_id' => $wh_qty['warehouse_id']));
                }
            }

            if ($update_variants) {
                $this->db->update_batch('product_variants', $update_variants, 'id');
            }

            if ($photos) {
                foreach ($photos as $photo) {
                    $this->db->insert('product_photos', array('product_id' => $id, 'photo' => $photo));
                }
            }

            if ($product_attributes) {
                foreach ($product_attributes as $pr_attr) {

                    $pr_attr['product_id'] = $id;
                    $variant_warehouse_id = $pr_attr['warehouse_id'];
                    unset($pr_attr['warehouse_id']);
                    $this->db->insert('product_variants', $pr_attr);
                    $option_id = $this->db->insert_id();

                    if ($pr_attr['quantity'] != 0) {
                        $this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $id, 'warehouse_id' => $variant_warehouse_id, 'quantity' => $pr_attr['quantity']));

                        $tax_rate_id = $tax_rate ? $tax_rate->id : NULL;
                        $tax = $tax_rate ? (($tax_rate->type == 1) ? $tax_rate->rate . "%" : $tax_rate->rate) : NULL;
                        $unit_cost = $data['cost'];
                        if ($tax_rate) {
                            if ($tax_rate->type == 1 && $tax_rate->rate != 0) {
                                if ($data['tax_method'] == '0') {
                                    $pr_tax_val = ($data['cost'] * $tax_rate->rate) / (100 + $tax_rate->rate);
                                    $net_item_cost = $data['cost'] - $pr_tax_val;
                                    $item_tax = $pr_tax_val * $pr_attr['quantity'];
                                } else {
                                    $net_item_cost = $data['cost'];
                                    $pr_tax_val = ($data['cost'] * $tax_rate->rate) / 100;
                                    $unit_cost = $data['cost'] + $pr_tax_val;
                                    $item_tax = $pr_tax_val * $pr_attr['quantity'];
                                }
                            } else {
                                $net_item_cost = $data['cost'];
                                $item_tax = $tax_rate->rate;
                            }
                        } else {
                            $net_item_cost = $data['cost'];
                            $item_tax = 0;
                        }

                        $subtotal = (($net_item_cost * $pr_attr['quantity']) + $item_tax);
                        $item = array(
                            'product_id' => $id,
                            'product_code' => $data['code'],
                            'product_name' => $data['name'],
                            'net_unit_cost' => $net_item_cost,
                            'unit_cost' => $unit_cost,
                            'quantity' => $pr_attr['quantity'],
                            'option_id' => $option_id,
                            'quantity_balance' => $pr_attr['quantity'],
                            'item_tax' => $item_tax,
                            'tax_rate_id' => $tax_rate_id,
                            'tax' => $tax,
                            'subtotal' => $subtotal,
                            'warehouse_id' => $variant_warehouse_id,
                            'date' => date('Y-m-d'),
                            'status' => 'received',
                        );
                        $this->db->insert('purchase_items', $item);

                    }
                }
            }

            $this->site->syncQuantity(NULL, NULL, NULL, $id);
            return true;
        } else {
            return false;
        }
    }

    public function updateProductOptionQuantity($option_id, $warehouse_id, $quantity, $product_id)
    {
        if ($option = $this->getProductWarehouseOptionQty($option_id, $warehouse_id)) {
            if ($this->db->update('warehouses_products_variants', array('quantity' => $quantity), array('option_id' => $option_id, 'warehouse_id' => $warehouse_id))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        } else {
            if ($this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $quantity))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getPurchasedItemDetails($product_id, $warehouse_id, $option_id = NULL)
    {
        $q = $this->db->get_where('purchase_items', array('product_id' => $product_id, 'option_id' => $option_id, 'warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPurchasedItemDetailsWithOption($product_id, $warehouse_id, $option_id)
    {
        $q = $this->db->get_where('purchase_items', array('product_id' => $product_id, 'purchase_id' => NULL, 'transfer_id' => NULL, 'warehouse_id' => $warehouse_id, 'option_id' => $option_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updatePrice($data = array())
    {

        if ($this->db->update_batch('products', $data, 'code')) {
            return true;
        } else {
            return false;
        }
    }
	
	public function updateQuantityExcel($data = array())
    {

        if ($this->db->update_batch('products', $data, 'code')) {
            return true;
        } else {
            return false;
        }
    }
	public function updateQuantityExcelWarehouse($data = array())
    {
		foreach($data as $value){
			$que_data=array('quantity'=>$value['quantity']);
			$where = array(
			'product_id'=>$value['product_id'],
			'warehouse_id'=>$value['warehouse_id']
			);
			$this->db->update('warehouses_products',$que_data,$where);
		}
        /*if ($this->db->update_batch('warehouses_products', $data, 'product_id')) {
            return true;
        } else {
            return false;
        }*/
		return true;
    }
	public function updateQuantityExcelVar($data = array())
    {
		foreach($data as $value){
			$que_data=array('quantity'=>$value['quantity'],'option_id'=>$value['option_id']);
			$where = array(
			'product_id'=>$value['product_id'],
			'warehouse_id'=>$value['warehouse_id']
			);
			$this->db->update('warehouses_products_variants',$que_data,$where);
		}
		return true;
        /*if ($this->db->update_batch('warehouses_products_variants', $data, 'product_id')) {
            return true;
        } else {
            return false;
        }*/
    }
	public function updateQuantityExcelPurchase($data = array())
    {
		foreach($data as $value){
			$where = array(
				'product_id'=>$value['product_id'],
				'warehouse_id'=>$value['warehouse_id'],
				'option_id'=>$value['option_id']
			);
			$que_data=array('quantity_balance'=>$value['quantity_balance']);
			
			$this->db->select('id');
			$this->db->from('purchase_items');
			$this->db->where($where);
			$this->db->order_by('id','DESC');
			$this->db->limit(1);
			$res=$this->db->get();
			
			
			if($res->num_rows()>0){
				$this->db->update('purchase_items',$que_data,array('id'=>$res->row()->id));
				//if we open code below it will got error when we update secord time;
				//$this->db->where($where);
			}else{
				$this->db->select('*');
				$this->db->from('products');
				$this->db->where(array('id'=>$value['product_id']));
				$prod=$this->db->get();
				
				$pur_data = array(
					'product_id'=>$value['product_id'],
					'product_code'=>$prod->row()->code,
					'product_name'=>$prod->row()->name,
					'warehouse_id'=>$value['warehouse_id'],
					'option_id'=>$value['option_id'],
				    'quantity'=>$value['quantity_balance'],
					'quantity_balance'=>$value['quantity_balance'],
                    'quantity_received'=>$value['quantity_balance'],
                    'net_unit_cost' => $value['cost'],
                    'unit_cost' => $value['cost'],
                    'real_unit_cost' => $value['cost'],
					'subtotal' => $value['cost'] * $value['quantity_balance'],
					'status' => 'received',
					'date' => date('Y-m-d')
				);
				$this->db->insert('purchase_items',$pur_data);
			}
			$this->site->syncQuantity(NULL, NULL, NULL, $value['product_id']);
		}
		return true;
    }
	
    public function deleteProduct($id)
    {
        if ($this->db->delete('products', array('id' => $id)) && $this->db->delete('warehouses_products', array('product_id' => $id)) && $this->db->delete('warehouses_products_variants', array('product_id' => $id))) {
            return true;
        }
        return FALSE;
    }


    public function totalCategoryProducts($category_id)
    {
        $q = $this->db->get_where('products', array('category_id' => $category_id));

        return $q->num_rows();
    }

    public function getSubcategoryByID($id)
    {
        $q = $this->db->get_where('subcategories', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getCategoryByCode($code)
    {
        $q = $this->db->get_where('categories', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getSubcategoryByCode($code)
    {

        $q = $this->db->get_where('subcategories', array('code' => $code), 1);
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

    public function getSubCategories()
    {
        $this->db->select('id as id, name as text');
        $q = $this->db->get("subcategories");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    public function getSubCategoriesForCategoryID($category_id)
    {
        $this->db->select('id as id, name as text');
        $q = $this->db->get_where("subcategories", array('category_id' => $category_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getTypeID($subcategory_id)
    {
        $this->db->select('id as id, name as text');
        $q = $this->db->get_where("type", array('subcategory_id' => $subcategory_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
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

    public function getAdjustmentByID($id)
    {
        $q = $this->db->get_where('adjustments', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function syncAdjustment($data = array())
    {
        if(! empty($data)) {

            if ($purchase_item = $this->getPurchasedItemDetails($data['product_id'], $data['warehouse_id'], $data['option_id'])) {
                $quantity_balance = $data['type'] == 'subtraction' ? $purchase_item->quantity_balance - $data['quantity'] : $purchase_item->quantity_balance + $data['quantity'];
                $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), array('id' => $purchase_item->id));
            } else {
                $pr = $this->site->getProductByID($data['product_id']);
                $item = array(
                    'product_id' => $data['product_id'],
                    'product_code' => $pr->code,
                    'product_name' => $pr->name,
                    'net_unit_cost' => 0,
                    'unit_cost' => 0,
                    'quantity' => 0,
                    'option_id' => $data['option_id'],
                    'quantity_balance' => $data['type'] == 'subtraction' ? (0 - $data['quantity']) : $data['quantity'],
                    'item_tax' => 0,
                    'tax_rate_id' => 0,
                    'tax' => '',
                    'subtotal' => 0,
                    'warehouse_id' => $data['warehouse_id'],
                    'date' => date('Y-m-d'),
                    'status' => 'received',
                );
                $this->db->insert('purchase_items', $item);
            }

            $this->site->syncProductQty($data['product_id'], $data['warehouse_id']);
            if ($data['option_id']) {
                $this->site->syncVariantQty($data['option_id'], $data['warehouse_id'], $data['product_id']);
            }
        }
    }

    public function reverseAdjustment($id)
    {
        if ($adjustment = $this->getAdjustmentByID($id)) {

            if ($purchase_item = $this->getPurchasedItemDetails($adjustment->product_id, $adjustment->warehouse_id, $adjustment->option_id)) {
                $quantity_balance = $adjustment->type == 'subtraction' ? $purchase_item->quantity_balance + $adjustment->quantity : $purchase_item->quantity_balance - $adjustment->quantity;
                $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), array('id' => $purchase_item->id));
            }

            $this->site->syncProductQty($adjustment->product_id, $adjustment->warehouse_id);
            if ($adjustment->option_id) {
                $this->site->syncVariantQty($adjustment->option_id, $adjustment->warehouse_id, $adjustment->product_id);
            }
        }
    }

    public function addAdjustment($data)
    {
		$p = $this->getProductByID($data['product_id']);
		$cost = $p->cost;
		$total_cost = 0;
		if($data['option_id']){
			$option = $this->getProductVariantByOptionID($data['option_id']);
			$total_cost = $cost * ($data['quantity'] * $option->qty_unit);
		}else{
			$total_cost = $cost * $data['quantity'];
		}
		
		$data['cost'] = $cost;
		$data['total_cost'] = $total_cost;
        if ($this->db->insert('adjustments', $data)) {
            $this->syncAdjustment($data);
            return true;
        }
        return false;
    }

    public function updateAdjustment($id, $data)
    {
        $this->reverseAdjustment($id);
        if ($this->db->update('adjustments', $data, array('id' => $id))) {
            $this->syncAdjustment($data);
            return true;
        }
        return false;
    }

    public function deleteAdjustment($id)
    {
        $this->reverseAdjustment($id);
        if ( $this->db->delete('adjustments', array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function getProductQuantity($product_id, $warehouse)
    {
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse), 1);
        if ($q->num_rows() > 0) {
            return $q->row_array(); //$q->row();
        }
        return FALSE;
    }

    public function addQuantity($product_id, $warehouse_id, $quantity, $rack = NULL)
    {

        if ($this->getProductQuantity($product_id, $warehouse_id)) {
            if ($this->updateQuantity($product_id, $warehouse_id, $quantity, $rack)) {
                return TRUE;
            }
        } else {
            if ($this->insertQuantity($product_id, $warehouse_id, $quantity, $rack)) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function insertQuantity($product_id, $warehouse_id, $quantity, $rack = NULL)
    {
        if ($this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $quantity, 'rack' => $rack))) {
            $this->site->syncProductQty($product_id, $warehouse_id);
            return true;
        }
        return false;
    }

    public function updateQuantity($product_id, $warehouse_id, $quantity, $rack = NULL)
    {
        $data = $rack ? array('quantity' => $quantity, 'rack' => $rack) : $data = array('quantity' => $quantity);
        if ($this->db->update('warehouses_products', $data, array('product_id' => $product_id, 'warehouse_id' => $warehouse_id))) {
            $this->site->syncProductQty($product_id, $warehouse_id);
            return true;
        }
        return false;
    }

    public function products_count($category_id, $subcategory_id = NULL)
    {
        if ($category_id) {
            $this->db->where('category_id', $category_id);
        }
        if ($subcategory_id) {
            $this->db->where('subcategory_id', $subcategory_id);
        }
        $this->db->from('products');
        return $this->db->count_all_results();
    }

    public function fetch_products($category_id, $limit, $start, $subcategory_id = NULL)
    {

        $this->db->limit($limit, $start);
        if ($category_id) {
            $this->db->where('category_id', $category_id);
        }
        if ($subcategory_id) {
            $this->db->where('subcategory_id', $subcategory_id);
        }
        $this->db->order_by("id", "asc");
        $query = $this->db->get("products");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getProductWarehouseOptionQty($option_id, $warehouse_id)
    {
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id, 'warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function syncVariantQty($option_id)
    {
        $wh_pr_vars = $this->getProductWarehouseOptions($option_id);
        $qty = 0;
        foreach ($wh_pr_vars as $row) {
            $qty += $row->quantity;
        }
        if ($this->db->update('product_variants', array('quantity' => $qty), array('id' => $option_id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function getProductWarehouseOptions($option_id)
    {
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function setRack($data)
    {
        if ($this->db->update('warehouses_products', array('rack' => $data['rack']), array('product_id' => $data['product_id'], 'warehouse_id' => $data['warehouse_id']))) {
            return TRUE;
        }
        return FALSE;
    }

    public function getSoldQty($id)
    {
        $this->db->select("date_format(" . $this->db->dbprefix('sales') . ".date, '%Y-%M') month, SUM( " . $this->db->dbprefix('sale_items') . ".quantity ) as sold, SUM( " . $this->db->dbprefix('sale_items') . ".subtotal ) as amount")
            ->from('sales')
            ->join('sale_items', 'sales.id=sale_items.sale_id', 'left')
            ->group_by("date_format(" . $this->db->dbprefix('sales') . ".date, '%Y-%m')")
            ->where($this->db->dbprefix('sale_items') . '.product_id', $id)
            //->where('DATE(NOW()) - INTERVAL 1 MONTH')
            ->where('DATE_ADD(curdate(), INTERVAL 1 MONTH)')
            ->order_by("date_format(" . $this->db->dbprefix('sales') . ".date, '%Y-%m') desc")->limit(3);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getPurchasedQty($id)
    {
        $this->db->select("date_format(" . $this->db->dbprefix('purchases') . ".date, '%Y-%M') month, SUM( " . $this->db->dbprefix('purchase_items') . ".quantity ) as purchased, SUM( " . $this->db->dbprefix('purchase_items') . ".subtotal ) as amount")
            ->from('purchases')
            ->join('purchase_items', 'purchases.id=purchase_items.purchase_id', 'left')
            ->group_by("date_format(" . $this->db->dbprefix('purchases') . ".date, '%Y-%m')")
            ->where($this->db->dbprefix('purchase_items') . '.product_id', $id)
            //->where('DATE(NOW()) - INTERVAL 1 MONTH')
            ->where('DATE_ADD(curdate(), INTERVAL 1 MONTH)')
            ->order_by("date_format(" . $this->db->dbprefix('purchases') . ".date, '%Y-%m') desc")->limit(3);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
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
	
	/* ------ Project ----- */
	public function getProjects($id = null){
		$q = $this->db->get('variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	/* ------ Print Barcode Label ------ */
    /* Error
	public function getProductsForPrinting($term){
		$this->db->where('code', $term);
		$query = $this->db->get('products');
		
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
		
	}
    */
    
    public function getProductsForPrinting($term, $limit = 5)
    {
        $this->db->where("(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getProducts(){
		$q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	
	public function getConvertItemsById($convert_id){
		$this->db->select('convert_items.product_id,convert_items.convert_id,convert_items.quantity AS c_quantity ,(erp_products.cost * erp_convert_items.quantity) AS tcost, convert_items.status, products.cost AS p_cost');
		$this->db->join('products', 'products.id = convert_items.product_id', 'INNER');
		$this->db->where('convert_items.convert_id', $convert_id);
		$query = $this->db->get('convert_items');
		
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	}
	
	public function getConvertItemsByIDPID($convert_id, $product_id){
		$this->db->where('convert_id', $convert_id);
		$this->db->where('product_id', $product_id);
		$query = $this->db->get('convert_items');
		if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
	}
	
	public function getConvertItemsAdd($convert_id){
		$this->db->select('convert_items.product_id,convert_items.convert_id, convert_items.quantity AS c_quantity ,(erp_products.cost * erp_convert_items.quantity) AS tcost, convert_items.status');
		$this->db->join('products', 'products.id = convert_items.product_id', 'INNER');
		$this->db->where('convert_items.convert_id', $convert_id);
		$this->db->where('convert_items.status', 'add');
		$query = $this->db->get('convert_items');
		
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	}
	
	public function getConvertItemsDeduct($convert_id){
		$this->db->select('SUM(erp_products.cost * erp_convert_items.quantity) AS tcost, convert_items.status');
		$this->db->join('products', 'products.id = convert_items.product_id', 'INNER');
		$this->db->where('convert_items.convert_id', $convert_id);
		$this->db->where('convert_items.status', 'deduct');
		$query = $this->db->get('convert_items');
		
		if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
	}
	
	public function getAllBoms(){
		$q = $this->db->get('bom');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	public function getAllBom_id($id){
		$this->db->select()
				 ->join('bom_items', 'bom.id = bom_items.bom_id')
				 ->where('bom.id',$id);
		$q = $this->db->get('bom');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}

	public function getReference(){
		$this->db->select('reference_no')
				 ->order_by('date', 'desc')
				 ->limit(1);
		$q = $this->db->get('convert');
        if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
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
	
	public function getWarehouseQty($product_id, $warehouse_id){
        $this->db->select('SUM(quantity) as quantity')
                 ->from('warehouses_products')
                 ->where(array('product_id'=>$product_id, 'warehouse_id'=>$warehouse_id));
        $q = $this->db->get();
        if($q->num_rows() > 0){
            return $q->row();
        }
        return false;
    }
	public function getEditProductByID($id)
    {
        $q = $this->db->get_where('products', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	 public function getpType()
    {
        $this->db->select('*');
        $q = $this->db->get("type");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }
	public function update_product($id,$data){
		$this->db->where('id', $id);
		$u=$this->db->update('products', $data);
		if($u){
			return TRUE;
		}
	}
	
	public function ImportProduct($data = array()){
		if($this->db->insert_batch('products',$data)){
			return true;
		}else{
			return false;
		}
	}
	
}
