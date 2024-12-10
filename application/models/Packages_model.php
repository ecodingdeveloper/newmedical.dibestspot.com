<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 */

class Packages_model extends CI_Model
{
  
  var $table         = 'packages';
  var $column_search = array('name');
  var $packages_column_order = array(
    '',
    'packages.name',
    ''
  );
  var $packages_default_column_order = array('packages.id'=>'DESC');

  public function __construct()
  {
    parent::__construct();
  }

  // public function get_categories()
  // {
  //   $this->db->select('*');
  //   $this->db->from('product_categories');
  //   $this->db->where('status','1');
  //   $this->db->order_by('id','RANDOM');
  //   $this->db->limit('25');
  //   return $result = $this->db->get()->result_array();
  // }

  // public function get_particular_categories($slug)
  // {
  //   $this->db->select('ps.*');
    
  //   $this->db->from('product_subcategories ps');
  //   $this->db->join('product_categories as pc','pc.id = ps.id','left');
    
  //   $this->db->like('ps.slug',$slug);

  //   return $result = $this->db->get()->result_array();
  // }

  // public function get_sub_categories($category_id)
  // {
    
  //   $this->db->select('ps.*');
  //   $this->db->from('product_subcategories ps');
  //   //$this->db->join('product_categories as pc','pc.id = ps.id','left');
    
  //   //$this->db->where('pc.status','1');
  //   $this->db->where('ps.category',$category_id);
  //   //$this->db->group_by('ps.category');
  //   //$this->db->order_by('id','RANDOM');
  //   // $this->db->limit('25');
  //   return $result = $this->db->get()->result_array();
  // }

  // public function get_popular_products()
  // {
  //      $this->db->select('*');
  //       $this->db->from('products');
  //       $this->db->where('status','1');
  //       $this->db->order_by('id','RANDOM'); 
  //       $this->db->limit('25');
  //       return $result = $this->db->get()->result_array();
        
  // }
  
  public function get_products($page,$limit,$type)
  {
        $this->db->select('p.*,u.unit_name');
        $this->db->from('products p');
        $this->db->join('unit u','u.id = p.unit','left');
        $this->db->join('product_categories c','c.id = p.category','left');
        $this->db->join('product_subcategories s','s.id = p.subcategory','left');
        $this->db->where('p.status','1');
        $this->db->group_by('p.id');
        $this->db->order_by('p.id','DESC');

        if(!empty($_POST['category'])) {   
          $this->db->where('c.slug',$_POST['category']);
        }

        if(!empty($_POST['subcategory'])) {   
          $this->db->where('s.slug',$_POST['subcategory']);
        }

        if(!empty($_POST['keywords'])) {  
          $this->db->group_start();
          $this->db->like('c.category_name',$_POST['keywords']);
          $this->db->or_like('s.subcategory_name',$_POST['keywords']);
          $this->db->or_like('p.name',$_POST['keywords']);
          $this->db->group_end();
        }
        
          if($type == 1){
         return $this->db->count_all_results(); 
        }else{

          $page = !empty($page)?$page:'';
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
        }

  }

  public function get_products_by_search($page,$limit,$type)
  {

      
        $this->db->select('p.name');
        $this->db->from('products p');
        $this->db->join('unit u','u.id = p.unit','left');
        $this->db->join('product_categories c','c.id = p.category','left');
        $this->db->join('product_subcategories s','s.id = p.subcategory','left');
        $this->db->where('p.status','1');
        $this->db->group_by('p.id');
        $this->db->order_by('p.id','DESC');
         $this->db->where('p.user_id',$_POST['pharmacy_id']);

        
        if(!empty($_POST['category'])) {   
          $this->db->where('c.id',$_POST['category']);
        }

        if(!empty($_POST['subcategory'])) {   
          $this->db->where_in('s.id',$_POST['subcategory']);
        }

        if(isset($_POST['keywords']) && $_POST['keywords'] !='') {  
           

          $this->db->like('p.name',$_POST['keywords']);
          
        }
        
          if($type == 1){
         return $this->db->count_all_results(); 
        }else{

          $page = !empty($page)?$page:'';
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
        }

  }

  public function get_product_details($slug)
  {
        $this->db->select('p.*,u.unit_name,ud.currency_code');
        $this->db->from('products p');
        $this->db->join('unit u','u.id = p.unit','left');
        $this->db->join('users_details ud','ud.user_id = p.user_id','left');
        $this->db->where('p.slug',$slug);
        return $this->db->get()->row_array();
        

  }
  
   public function get_products_by_pharmacy_filter($pharmacy_id, $page,$limit,$type){
     

        $this->db->select('p.*,u.unit_name,c.category_name as category_name,ph.currency_code as pharmacy_currency');
        $this->db->from('products p');
        $this->db->join('unit u','u.id = p.unit','left');
        $this->db->join('product_categories c','c.id = p.category','left');
        $this->db->join('product_subcategories s','s.id = p.subcategory','left');
        $this->db->join('users_details ph','ph.user_id = p.user_id');
        $this->db->where('p.status','1');
        if(!empty($pharmacy_id)){
          $this->db->where('p.user_id',$pharmacy_id);
        }
        //$this->db->group_by('p.id');
		$this->db->group_by('p.id, p.name, p.unit, p.category, p.subcategory, p.user_id, p.status, u.unit_name, c.category_name, ph.currency_code');
        $this->db->order_by('p.id','DESC');

        if(!empty($_POST['category'])) {   
          $this->db->where('c.id',$_POST['category']);
        }

        if(!empty($_POST['subcategory'])) {   
          $this->db->where_in('s.id',$_POST['subcategory']);
        }

        if(!empty($_POST['keywords'])) {  
          $this->db->group_start();
          //$this->db->like('c.category_name',$_POST['keywords']);
          //$this->db->or_like('s.subcategory_name',$_POST['keywords']);
          $this->db->or_like('p.name',$_POST['keywords']);
          $this->db->group_end();
        }
        
          if($type == 1){
         return $this->db->count_all_results(); 
        }else{

          $page = !empty($page)?$page:'';
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ((int)$page * $limit);  
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
        }
  }

  public function get_products_by_pharmacy($pharmacy_id, $page,$limit,$type){
      
        $this->db->select('p.*,u.unit_name');
        $this->db->from('products p');
        $this->db->join('unit u','u.id = p.unit','left');
        $this->db->join('product_categories c','c.id = p.category','left');
        $this->db->join('product_subcategories s','s.id = p.subcategory','left');
        $this->db->where('p.status','1');
        $this->db->where('p.user_id',$pharmacy_id);
        $this->db->group_by('p.id');
        $this->db->order_by('p.id','DESC');

        if(!empty($_POST['category'])) {   
          $this->db->where('c.slug',$_POST['category']);
        }

        if(!empty($_POST['subcategory'])) {   
          $this->db->where('s.slug',$_POST['subcategory']);
        }

        if(!empty($_POST['keywords'])) {  
          $this->db->group_start();
          $this->db->like('c.category_name',$_POST['keywords']);
          $this->db->or_like('s.subcategory_name',$_POST['keywords']);
          $this->db->or_like('p.name',$_POST['keywords']);
          $this->db->group_end();
        }
        
          if($type == 1){
         return $this->db->count_all_results(); 
        }else{

          $page = !empty($page)?$page:'';
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
        }
  }

  private function _get_datatables_query()
{
    $this->db->select('*');
    $this->db->from($this->table);
    $this->db->where('status', 1);

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
        $this->db->order_by($this->packages_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if (isset($this->packages_default_column_order))
    {
        $order = $this->packages_default_column_order;
        $this->db->order_by(key($order), $order[key($order)]);
    }

    // Return the query object
    return $this->db;
}

public function get_datatables()
{
    $this->_get_datatables_query(); // Build the query

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
      $this->db->where('id',$id);
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
      return $this->db->delete('packages', array('id' => $id)); 
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