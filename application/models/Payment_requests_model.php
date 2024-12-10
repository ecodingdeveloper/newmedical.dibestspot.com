<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property object $load 
 * @property object $db
 */

class Payment_requests_model extends CI_Model {

  var $table = 'payment_request p';
  var $users ='users u';
  var $account_details ='account_details a';
  var $column_search = array('CONCAT(u.first_name," ",u.last_name)','u.profileimage','date_format(p.request_date,"%d %b %Y")','p.description'); //set column field database for datatable searchable 
  var $order = array('p.request_date' => 'DESC'); // default order
  var $payment_request_column_order = array('','p.request_date','request_amount_decimal','p.description','u.first_name','p.payment_type','','p.status');



  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  private function _get_datatables_query()
  {
  
        $this->db->select('p.*,u.first_name,u.role,u.last_name,u.username,u.profileimage,a.bank_name,a.branch_name,a.account_no,a.account_name, TRUNCATE(p.request_amount,2) as request_amount_decimal');
        $this->db->from($this->table);
        $this->db->join($this->users, 'u.id = p.user_id', 'left');
        $this->db->join($this->account_details, 'a.user_id = p.user_id', 'left'); 
                 
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

       $this->db->order_by($this->payment_request_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->order))
    {
      $order = $this->order;
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
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  public function update($where, $data)
  {
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }
  
  public function get_touserid($id){
    return $this->db->select('user_id')->get_where($this->table,array('id'=>$id))->row()->user_id;    
  }

}
