<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 */

class Pricing_plan_model extends CI_Model
{
  
  var $table         = 'pricingplan';
  var $column_search = array('feature_text');
  var $pricingplan_column_order = array(
    '',
    'pricingplan.feature_text',
    ''
  );
  var $packages_default_column_order = array('pricingplan.plan_id'=>'DESC');

  public function __construct()
  {
    parent::__construct();
  }


  public function _get_datatables_query()
{
    $this->db->select('*');
    $this->db->from($this->table);
    

    // Implement search functionality
    $i = 0;
    foreach ($this->column_search as $item) // Loop column
    {
        if ($_POST['search']['value']) // If datatable sends POST for search
        {
            if ($i === 0) // First loop
            {
                $this->db->group_start(); // Open bracket
                $this->db->like($item, $_POST['search']['value']);
            }
            else
            {
                $this->db->or_like($item, $_POST['search']['value']);
            }

            if (count($this->column_search) - 1 == $i) // Last loop
                $this->db->group_end(); // Close bracket
        }
        $i++;
    }

    // Order processing
    if (isset($_POST['order'])) // If ordering is present
    {
        $this->db->order_by($this->pricingplan_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if (isset($this->pricingplan_default_column_order))
    {
        $order = $this->pricingplan_default_column_order;
        $this->db->order_by(key($order), $order[key($order)]);
    }

    // Return the query object
    return $this->db;
}

public function get_datatables()
{
  $query_result=$this->_get_datatables_query(); // Build the query
    // echo '<pre>'; // Optional: for better formatting in HTML
    // print_r($query_result);
    // echo '</pre>';
    // die("hg");
    // Handle pagination
    if ($_POST['length'] != -1) {
        $this->db->limit($_POST['length'], $_POST['start']);
    }

    $query = $this->db->get(); // Execute the query
    return $query->result_array(); // Return the results as an array
}
public function get_package($id) {
  $this->db->where('id', $id);
  $query = $this->db->get('packages');

  return $query->row(); // Return the found package
}



    public function count_filtered()
    {
      $this->_get_datatables_query();
      $this->db->where('status',1);
      $query = $this->db->get();
      return $query->num_rows();
    }

    public function count_all()
    {
      $this->db->where('status',1);
      $this->db->from($this->table);
      return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
      $this->db->from($this->table);
      $this->db->where('plan_id',$id);
      $query = $this->db->get();

      return $query->row();
    }

    public function get_by_id_with_pharmacy($id)
    {
      $this->db->select('*,pr.id as product_id');
      $this->db->from('products as pr');
      
      $this->db->join('users as u','u.id = pr.user_id','left');
      $this->db->where('pr.id',$id);

      $query = $this->db->get();

      return $query->row();
    }

    public function save($data)
    {
      $this->db->insert($this->table, $data);
      return $this->db->insert_id();
    }

    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete($id) {
      return $this->db->delete('pricingplan', array('plan_id' => $id)); 
  }
  

    public function search_products($search_products)
    {
        return $this->db
              ->select('product_name')
              ->like('product_name',$search_products,'after')
              ->limit(5)
              ->order_by('product_name','asc')
              ->get($this->table)
              ->result_array();
    }

}