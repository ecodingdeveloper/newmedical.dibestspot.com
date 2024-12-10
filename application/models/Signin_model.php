<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 */
class Signin_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

  public function check_email($email)
  {
  	    $this->db->select('id,email');
        $this->db->from('users');
        $this->db->where('email', $email);
        $result = $this->db->get()->row_array();
        
       return $result;
        
  }

  public function check_mobileno($mobileno)
  {
        $this->db->select('id,mobileno');
        $this->db->from('users');
        $this->db->where('mobileno', $mobileno);
        $result = $this->db->get()->row_array();
        
       return $result;
        
  }

  public function signup($inputdata)
  {
       $this->db->insert('users',$inputdata);
       return ($this->db->affected_rows()!= 1)? false:true;
  }

  public function saveotp($inputdata)
  {
       $this->db->insert('otp_history',$inputdata);
       return ($this->db->affected_rows()!= 1)? false:true;
  }

  public function update($inputdata,$id)
  {
       $this->db->where('md5(id)',$id);
       $this->db->update('users',$inputdata);
       return ($this->db->affected_rows()!= 1)? false:true;
  }

  public function is_valid_login($email,$password)
  {
    $password = md5($password);
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where("(email = '".$email."' OR mobileno = '".$email."')");
    $this->db->where('password',$password);
    $result = $this->db->get()->row_array();
      return $result;
  }

  public function social_login($email){

    $this->db->select('*');
    $this->db->from('users');
    $this->db->where("(email = '".$email."' OR mobileno = '".$email."')");
    $result = $this->db->get()->row_array();
      return $result;


  }
  
}
?>
