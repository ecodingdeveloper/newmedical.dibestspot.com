<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 * @property object $session
 * @property object $quoation_column_search
 */
class Pharmacy_model extends CI_Model
{

  var $product ='products p'; 
  var $category ='product_categories c';
  var $subcategory ='product_subcategories s';
  var $unit ='unit u';

  var $users ='users u';
  
   var $column_search = array('p.name','c.category_name','s.subcategory_name','u.unit_name');
   var $column_order = array('','','p.name','c.category_name','s.subcategory_name','u.unit_name'); 
  // var $column_order = array('p.id' => 'DESC'); // default order 
  
  
  var $quotations = 'patient_request_quotation q';
  var $quotation_column_search = array('u.first_name','u.last_name'); 
  var $quotation_order = array('q.id' => 'ASC'); // default order 
  

  public function __construct()
  {
    parent::__construct();
  }


  public function create_product($input_data)
  {
     $this->db->insert('products',$input_data);
     return ($this->db->affected_rows()!= 1)? false:true;
  }

  public function update_product($product_id,$input_data)
  {
     $this->db->where('id',$product_id);
     $this->db->update('products',$input_data);
     return ($this->db->affected_rows()!= 1)? false:true;
  }

  private function _get_datatables_query()
  {
  
        $this->db->select('p.*,c.category_name,s.subcategory_name,u.unit_name');
        $this->db->from($this->product); 
        $this->db->join($this->category,'p.category = c.id','left');
        $this->db->join($this->subcategory,'p.subcategory = s.id','left');
        $this->db->join($this->unit,'p.unit = u.id','left'); 
        $this->db->where("(p.status = '1' OR p.status = '2')");
        $this->db->where("p.user_id",$this->session->userdata('user_id'));
        
        
   
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

       $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->column_order))
    {
      $order = $this->column_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_datatables()
  {
    $this->_get_datatables_query();
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function count_filtered()
  {
    $this->_get_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all()
  {
    $this->db->from($this->product);
    $this->db->where("p.user_id",$this->session->userdata('user_id'));
    $this->db->where("(p.status = '1' OR p.status = '2')");
    return $this->db->count_all_results();
  }

  public function update($where, $data)
  {
    $this->db->update($this->product, $data, $where);
    return $this->db->affected_rows();
  }

  public function get_product(){
      return $this->db->get($this->product)->result_array();
  }

  public function get_products($product_id)
  {
        $this->db->select('p.*');
        $this->db->from($this->product);
        $this->db->where('p.id',$product_id);
        return $this->db->get()->row_array(); 
  }
  
    
  private function _get_newquotations_datatables_query()
  {
  
        $this->db->select('q.*, CONCAT(u.first_name," ", u.last_name) as patient_name,u.profileimage');
        $this->db->from($this->quotations);
        $this->db->join($this->users, 'u.id = q.patient_id', 'left'); 
        $this->db->where('q.quotation_request_status','pending');
        $this->db->where('q.pharmacy_id',$this->session->userdata('user_id'));
   
    $i = 0;
  
    foreach ($this->quotation_column_search as $item) // loop column 
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

         if(count($this->quoation_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
            $this->db->order_by('id', $_POST['order']['0']['dir']);

       //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->quotation_order))
    {
      $order = $this->quotation_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }
  
  
  private function _get_completedquotations_datatables_query()
  {
  
        $this->db->select('q.*, CONCAT(u.first_name," ", u.last_name) as patient_name,u.profileimage');
        $this->db->from($this->quotations);
        $this->db->join($this->users, 'u.id = q.patient_id', 'left'); 
        $this->db->where('q.quotation_request_status','completed');
        $this->db->where('q.pharmacy_id',$this->session->userdata('user_id'));
   
    $i = 0;
  
    foreach ($this->quotation_column_search as $item) // loop column 
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

         if(count($this->quoation_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
            $this->db->order_by('id', $_POST['order']['0']['dir']);

       //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->quotation_order))
    {
      $order = $this->quotation_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }
  
  
  private function _get_cancelledquotations_datatables_query()
  {
  
        $this->db->select('q.*, CONCAT(u.first_name," ", u.last_name) as patient_name,u.profileimage');
        $this->db->from($this->quotations);
        $this->db->join($this->users, 'u.id = q.patient_id', 'left'); 
        $this->db->where('q.quotation_request_status','cancelled');
        $this->db->where('q.pharmacy_id',$this->session->userdata('user_id'));
   
    $i = 0;
  
    foreach ($this->quotation_column_search as $item) // loop column 
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

         if(count($this->quoation_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
            $this->db->order_by('id', $_POST['order']['0']['dir']);

       //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->quotation_order))
    {
      $order = $this->quotation_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }
  
  private function _get_waitingquotations_datatables_query()
  {
  
        $this->db->select('q.*, CONCAT(u.first_name," ", u.last_name) as patient_name,u.profileimage');
        $this->db->from($this->quotations);
        $this->db->join($this->users, 'u.id = q.patient_id', 'left'); 
        $this->db->where('q.quotation_request_status','waiting');
        $this->db->where('q.pharmacy_id',$this->session->userdata('user_id'));
   
    $i = 0;
  
    foreach ($this->quotation_column_search as $item) // loop column 
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

         if(count($this->quoation_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
            $this->db->order_by('id', $_POST['order']['0']['dir']);

       //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->quotation_order))
    {
      $order = $this->quotation_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_newquotations_datatables() {
        $this->_get_newquotations_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
  }
  
  public function newquotations_count_filtered()
  {
    $this->_get_newquotations_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function newquotations_count_all()
  {
    $this->db->from($this->quotations);
    return $this->db->count_all_results();
  }
  
  public function get_completedquotations_datatables() {
        $this->_get_completedquotations_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
  }
  
  public function completedquotations_count_filtered()
  {
    $this->_get_completedquotations_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function completedquotations_count_all()
  {
    $this->db->from($this->quotations);
    return $this->db->count_all_results();
  }
  
  public function get_cancelledquotations_datatables() {
   $this->_get_cancelledquotations_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
  }
  
  public function cancelledquotations_count_filtered()
  {
    $this->_get_cancelledquotations_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function cancelledquotations_count_all()
  {
    $this->db->from($this->quotations);
    return $this->db->count_all_results();
  }
  
  public function get_waitingquotations_datatables() {
   $this->_get_waitingquotations_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
  }
  
  public function waitingquotations_count_filtered()
  {
    $this->_get_waitingquotations_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function waitingquotations_count_all()
  {
    $this->db->from($this->quotations);
    return $this->db->count_all_results();
  }

  public function get_patient_request_quotation_details($quotation_id = NULL) {
      $this->db->select('q.*');
      $this->db->where('q.id', $quotation_id);
      $this->db->from($this->quotations);
      $query = $this->db->get();
      return $query->row_array();
  }
  
public function get_new_quotation_count($user_id){
    $this->db->select('COUNT(q.id) as new_quotation_count');
    $this->db->from($this->quotations);
    $this->db->join($this->users, 'u.id = q.patient_id', 'left'); 
    $this->db->where('q.quotation_request_status','pending');
    $this->db->where('q.pharmacy_id',$this->session->userdata('user_id'));
    $query = $this->db->get();
    return $query->row_array();
}
          

}

