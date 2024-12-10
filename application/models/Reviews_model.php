<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property object $load 
 * @property object $db
 */

class Reviews_model extends CI_Model {

// admin
  var $reviews = 'rating_reviews r'; 
  var $doctor ='users d';
  var $doctor_details ='users_details dd';
  var $patient ='users p';
  var $patient_details ='users_details pd';
  var $specialization ='specialization s';

  var $reviews_column_search = array('d.first_name','d.last_name','d.profileimage','p.first_name','p.last_name','p.profileimage','r.title','r.review','r.created_date'); 
  var $reviews_order = array('r.id' => 'DESC'); // default order 
  var $reviews_column_order = array('','p.first_name','d.username','r.rating','r.review','r.created_date');

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }



  


  //admin----------------------------

  private function _get_reviews__datatables_query()
  {
  
        $this->db->select('r.*, CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage, CONCAT(p.first_name," ", p.last_name) as patient_name,p.profileimage as patient_profileimage');
        $this->db->from($this->reviews);
        $this->db->join($this->doctor, 'd.id = r.doctor_id', 'left'); 
        $this->db->join($this->patient, 'p.id = r.user_id', 'left'); 
                       
   
    $i = 0;
  
    foreach ($this->reviews_column_search as $item) // loop column 
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

         if(count($this->reviews_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
            // $this->db->order_by('id', $_POST['order']['0']['dir']);

       $this->db->order_by($this->reviews_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->reviews_order))
    {
      $order = $this->reviews_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_reviews_datatables()
  {
    $this->_get_reviews__datatables_query();
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function reviews_count_filtered()
  {
    $this->_get_reviews__datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function reviews_count_all()
  {
    $this->db->from($this->reviews);
    return $this->db->count_all_results();
  }


  

}