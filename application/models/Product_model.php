<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 */

class Product_model extends CI_Model
{
  
  var $table         = 'products';
  var $column_search = array('name');
  var $products_column_order = array(
    '',
    'products.name',
    ''
  );
  var $products_default_column_order = array('products.id'=>'DESC');

  public function __construct()
  {
    parent::__construct();
  }

  public function get_categories()
  {
    $this->db->select('*');
    $this->db->from('product_categories');
    $this->db->where('status','1');
    $this->db->order_by('id','RANDOM');
    $this->db->limit('25');
    return $result = $this->db->get()->result_array();
  }

  public function get_particular_categories($slug)
  {
    $this->db->select('ps.*');
    
    $this->db->from('product_subcategories ps');
    $this->db->join('product_categories as pc','pc.id = ps.id','left');
    
    $this->db->like('ps.slug',$slug);

    return $result = $this->db->get()->result_array();
  }

  public function get_sub_categories($category_id)
  {
    
    $this->db->select('ps.*');
    $this->db->from('product_subcategories ps');
    //$this->db->join('product_categories as pc','pc.id = ps.id','left');
    
    //$this->db->where('pc.status','1');
    $this->db->where('ps.category',$category_id);
    //$this->db->group_by('ps.category');
    //$this->db->order_by('id','RANDOM');
    // $this->db->limit('25');
    return $result = $this->db->get()->result_array();
  }

  public function get_popular_products()
  {
       $this->db->select('*');
        $this->db->from('products');
        $this->db->where('status','1');
        $this->db->order_by('id','RANDOM'); 
        $this->db->limit('25');
        return $result = $this->db->get()->result_array();
        
  }
  
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
      $this->db->where('status',1);
      $this->db->from($this->table);
      $i = 0;
    
      foreach ($this->column_search as $item) // loop column 
      {
        if($_POST['search']['value']) // if datatable send POST for search
        {
          if($i===0) // first loop
          {
            $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
            $this->db->like($item, $_POST['search']['value']);
          }
          else
          {
            $this->db->or_like($item, $_POST['search']['value']);
          }

           if(count($this->column_search) - 1 == $i) //last loop
            $this->db->group_end(); //close bracket
        }
        $i++;
      }
      
      if(isset($_POST['order'])) // here order processing
      {
          // $this->db->order_by('id', $_POST['order']['0']['dir']);
         $this->db->order_by($this->products_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
      } 
      else if(isset($this->products_default_column_order))
      {
        $order = $this->products_default_column_order;
        $this->db->order_by(key($order), $order[key($order)]);
      }
    }

    public function get_datatables()
    {
      $this->_get_datatables_query();
      $this->db->where('status',1);
      if($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
      $query = $this->db->get();
      return $query->result_array();
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

    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
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