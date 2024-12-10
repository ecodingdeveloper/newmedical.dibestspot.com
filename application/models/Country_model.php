<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property object $load 
 * @property object $db
 */

class Country_model extends CI_Model {


   var $country ='country c';
   var $state ='state s';
   var $city ='city cc';
  
   var $column_search = array('s.statename'); //set column field database for datatable searchable 
   var $order = array('s.statename' => 'ASC'); // default order
   var $column_order=array("","s.statename");

   var $column_searchcity = array('cc.city'); //set column field database for datatable searchable 
   var $ordercity = array('cc.city' => 'ASC'); // default order
   var $city_column_order = array('','cc.city','');

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  private function _get_datatables_query($country_id)
  {
  
       
        $this->db->select('s.*');
        $this->db->from($this->state);
         
        $this->db->where('s.countryid',$country_id);

      
   
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
    else if(isset($this->order))
    {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_state_datatables($country_id)
  {
    $this->_get_datatables_query($country_id);
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function state_count_filtered($country_id)
  {
    $this->_get_datatables_query($country_id);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function state_count_all($country_id)
  {
    $this->db->where('s.countryid',$country_id);
    $this->db->from($this->state);
    return $this->db->count_all_results();
  }

private function _get_datatablescity_query($country_id)
  {
  
       
        $this->db->select('cc.*');
        $this->db->from($this->city);
         
        $this->db->where('cc.stateid',$country_id);

      
   
    $i = 0;
  
    foreach ($this->column_searchcity as $item) // loop column 
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

         if(count($this->column_searchcity) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
            // $this->db->order_by('id', $_POST['order']['0']['dir']);

       $this->db->order_by($this->city_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->ordercity))
    {
      $order = $this->ordercity;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_city_datatables($country_id)
  {
    $this->_get_datatablescity_query($country_id);
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function city_count_filtered($country_id)
  {
    $this->_get_datatablescity_query($country_id);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function city_count_all($country_id)
  {
    $this->db->where('cc.stateid',$country_id);
    $this->db->from($this->city);
    return $this->db->count_all_results();
  }


  

  


}