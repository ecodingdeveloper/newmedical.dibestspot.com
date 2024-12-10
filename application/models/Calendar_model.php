<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 */
class Calendar_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}


	public function calendar_view($user_id,$role)
{
      $this->db->select('a.*,u.id as userid,u.first_name,u.last_name,u.username,u.profileimage,u.email,u.mobileno,c.country as countryname,s.statename,ci.city as cityname,p.per_hour_charge');
        $this->db->from('appointments a');
         if($role==1) //doctor
        {
             $this->db->join('users u', 'u.id = a.appointment_from', 'left');
        }

         if($role==2) //patient
        {
             $this->db->join('users u', 'u.id = a.appointment_to', 'left');
        }
        
        $this->db->join('users_details ud', 'u.id = ud.user_id', 'left');
        $this->db->join('payments p','p.id = a.payment_id','left');   
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left'); 
        if($role==1) //doctor
        {
             $this->db->where('a.appointment_to',$user_id);
        }

         if($role==2) //patient
        {
             $this->db->where('a.appointment_from',$user_id);
        }
       
        $query = $this->db->get();
	    $result = $query->result_array();
	    return $result;
       
}
  
}

