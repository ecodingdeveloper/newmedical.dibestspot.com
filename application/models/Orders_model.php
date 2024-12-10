<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 * @property object $session
 * @property object $quoation_column_search
 */
class Orders_model extends CI_Model
{

  var $product ='products p'; 
  var $category ='product_categories c';
  var $subcategory ='product_subcategories s';
  var $unit ='unit u';

  var $users ='users u';
  
  var $column_search = array('od.full_name','od.email','CONCAT(us.first_name," ", us.last_name)','o.order_id','o.quantity','o.payment_type','o.subtotal','date_format(od.created_at,"%d %b %Y")','us.pharmacy_name'); 
  var $column_order = array(
    'od.order_user_details_id', // default order
    'o.order_id',
    'us.pharmacy_name',
    'qty',
    'LENGTH(o.subtotal)',
    'o.payment_type',
    'o.order_status',
    'od.created_at'
  ); // default order 
  
  
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

  /*private function _get_datatables_query()
  {
        
          $this->db->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty,o.payment_type,o.status,o.order_id,o.subtotal,o.order_status,o.user_notify,o.pharmacy_notify,o.id as id,ud.currency_code as product_currency, "CAST(o.subtotal AS INT)" as orderby_subtotoal, o.currency_code, o.currency_code as productcurrency, us.id as userids');
          $this->db->from('orders as o');
          $this->db->join('order_user_details as od','od.order_user_details_id = o.user_order_id','left');
          $this->db->join('users as us','us.id = o.pharmacy_id','left');
          $this->db->join('users_details as ud','ud.user_id = o.pharmacy_id','left');
         
          
          if($this->session->userdata('role')=='5'){
            $this->db->where('o.pharmacy_id',$this->session->userdata('user_id')); 
          }else{
            $this->db->where('od.user_id',$this->session->userdata('user_id'));
          }  

          $this->db->group_by('o.id', 'ud.currency_code');             

          // $this->db->select('*');
          // $this->db->from('order_user_details as od');
          // $this->db->join('orders o','o.user_order_id = od.order_user_details_id','left');
          // $this->db->where("od.pharmacy_id",$this->session->userdata('user_id'));
        
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
      $this->db->order_by($order[key($order)], 'DESC');
    }
  }

  public function get_datatables()
  {
    $this->_get_datatables_query();
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }*/
  
  private function _get_datatables_query()
{
    $this->db->select('
        od.*, 
        us.first_name as pharmacy_first_name,
        us.last_name as pharmacy_last_name,
        us.pharmacy_name as pharmacy_name,
        SUM(o.quantity) as qty,
        o.payment_type,
        o.status,
        o.order_id,
        o.subtotal,
        o.order_status,
        o.user_notify,
        o.pharmacy_notify,
        o.id as id,
        ud.currency_code as product_currency,
        CAST(o.subtotal AS UNSIGNED) as orderby_subtotal,
        o.currency_code as productcurrency,
        us.id as userid
    ');
    $this->db->from('orders as o');
    $this->db->join('order_user_details as od', 'od.order_user_details_id = o.user_order_id', 'left');
    $this->db->join('users as us', 'us.id = o.pharmacy_id', 'left');
    $this->db->join('users_details as ud', 'ud.user_id = o.pharmacy_id', 'left');
    
    if ($this->session->userdata('role') == '5') {
        $this->db->where('o.pharmacy_id', $this->session->userdata('user_id'));
    } else {
        $this->db->where('od.user_id', $this->session->userdata('user_id'));
    }

    $this->db->group_by(['o.id', 'ud.currency_code']);  // Include ud.currency_code in GROUP BY clause

    $i = 0;
    foreach ($this->column_search as $item) {
        if ($_POST['search']['value']) {
            if ($i === 0) {
                $this->db->group_start();
                $this->db->like($item, $_POST['search']['value']);
            } else {
                $this->db->or_like($item, $_POST['search']['value']);
            }
            if (count($this->column_search) - 1 == $i) {
                $this->db->group_end();
            }
        }
        $i++;
    }
    
    if (isset($_POST['order'])) {
        $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->column_order)) {
        $order = $this->column_order;
        $this->db->order_by($order[key($order)], 'DESC');
    }
}

public function get_datatables()
{
    $this->_get_datatables_query();
    if ($_POST['length'] != -1) {
        $this->db->limit($_POST['length'], $_POST['start']);
    }
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

public function get_products_datatables($orderId)
{
  $this->db->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,o.quantity as qty,o.payment_type,o.status,o.product_name,o.order_id,o.subtotal  subtotal ,o.order_status,o.user_notify,o.pharmacy_notify,o.id as id,ud.currency_code as product_currency,o.currency_code as order_currency, date_format(od.created_at,"%d %b %Y") created_at_formatted');
  $this->db->from('orders as o');
  $this->db->join('order_user_details as od','od.order_user_details_id = o.user_order_id','left');
  $this->db->join('users as us','us.id = o.pharmacy_id','left');
  $this->db->join('users_details as ud','ud.user_id = o.pharmacy_id','left');
  $this->db->where('o.order_id',base64_decode($orderId));
  if($this->session->userdata('role')=='5'){
    $this->db->where('o.pharmacy_id',$this->session->userdata('user_id')); 
  }else{
    $this->db->where('o.user_id',$this->session->userdata('user_id'));
  }  
  $query = $this->db->get();
  return $query->result_array();
}
          

}

