<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @property object $db
 */
class Admin_login_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
  public function is_valid_login($email,$password)
  {
    $password = md5($password);
    $this->db->select('id');
    $this->db->from('administrators');
	$this->db->where('email',$email);
	$result = $this->db->get()->row_array();
	if(!empty($result)){
	        $this->db->select('id');
    		$this->db->from('administrators');
			$this->db->where('email',$email);
	        $this->db->where('password',$password);
			$result_set = $this->db->get()->row_array();
		 	if(!empty($result_set)){
			$result=$result_set;
			// If result value is assumed as zero, Username & Password is correct 
			}			
			else{
				$result = 2;
				// If result value is assumed as 2, password is incorrect
			}
		 }
		 else{ 				
			 	$result = 1;
				// If result value is assumed as 1, username is incorrect
			}
			
    return $result;
  }
}
?>
