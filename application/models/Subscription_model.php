<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 */

class Subscription_model extends CI_Model
{
  

  public function __construct()
  {
    parent::__construct();
  }

  public function get_features() {
    $this->db->select('feature_text');
    $this->db->from("pricingplan");
    $query = $this->db->get(); 
    return $query->result(); 
}
public function role_0() {
  $this->db->select('role_0');
  $this->db->from("pricingplan");
  $query = $this->db->get(); 
  return $query->result(); 
}
public function role_1() {
  $this->db->select('role_1');
  $this->db->from("pricingplan");
  $query = $this->db->get();
  return $query->result(); 
}
public function role_2() {
  $this->db->select('role_2');
  $this->db->from("pricingplan");
  $query = $this->db->get(); 
  return $query->result(); 
}
public function role_4() {
  $this->db->select('role_4');
  $this->db->from("pricingplan");
  $query = $this->db->get();
  return $query->result(); 
}
public function role_5() {
  $this->db->select('role_5');
  $this->db->from("pricingplan");
  $query = $this->db->get(); 
  return $query->result();
}
public function role_6() {
  $this->db->select('role_6');
  $this->db->from("pricingplan");
  $query = $this->db->get(); 
  return $query->result(); 
}


}