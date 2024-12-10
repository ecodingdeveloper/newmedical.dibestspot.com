<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 * @property object $session
 */
class Book_appoinments_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

    public function get_schedule_timings($id,$day_id)
	{
	    $this->db->where('user_id',$id);
	    $this->db->where('day_id',$day_id);
	    $query = $this->db->get('schedule_timings');
	    $result = $query->result_array();

	    return $result;
	}
	public function get_user_details($id)
   {
       $this->db->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=u.id) as rating_value,u.role, c.sortname,  s.state_code');
        $this->db->from('users u');
        $this->db->join('users_details ud','ud.user_id = u.id','left');
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left');
        $this->db->join('specialization sp','ud.specialization = sp.id','left');
        $this->db->where('u.status','1');
        $this->db->where('u.is_verified','1');
        $this->db->where('u.is_updated','1');
        $this->db->where('u.id',$id);
        return $result = $this->db->get()->row_array();
        
  }

    public function get_available_appoinments($id,$selected_date)
	{
		if($selected_date=='')
		{
			$dt = date('Y-m-d', strtotime("+0 day"));
	        $end = date('Y-m-d', strtotime("+6 day"));
		}
		else
		{
			$dt = $selected_date;
	        $end = date('Y-m-d', strtotime($dt. '+6 day'));
		}

	    $this->db->select('*');
	    $this->db->from('appointments');
	    $this->db->where('appointment_to',$id);
	    $this->db->where('appointment_date BETWEEN "'.$dt.'" AND "'.$end.'"');
	    $this->db->where('approved',1);
	    $query = $this->db->get();
	    $result = $query->result_array();

	    return $result;
	}


	public function get_appoinments_details($appointment_id)
	{
		 $this->db->select('a.*, CONCAT(d.first_name," ", d.last_name) as doctor_name,d.email as doctor_email,p.email as patient_email,CONCAT(d.country_code,"", d.mobileno) as doctor_mobile,CONCAT(p.country_code,"", p.mobileno) as patient_mobile, CONCAT(p.first_name," ", p.last_name) as patient_name,d.role,d.device_type as doctor_device_type,d.device_id as doctor_device_id,p.first_name as patient_first_name');
        $this->db->from('appointments a');
        $this->db->join('users d', 'd.id = a.appointment_to', 'left'); 
        $this->db->join('users_details dd','dd.user_id = d.id','left'); 
        $this->db->join('users p', 'p.id = a.appointment_from', 'left'); 
        $this->db->join('users_details pd','pd.user_id = p.id','left'); 
        $this->db->where('a.id',$appointment_id);
        return $this->db->get()->row_array();
	}
	
	public function patient_booking_time($book_date){
		$user_id = $this->session->userdata('user_id');
		//return $this->db->select('appointment_time, appointment_end_time')->where(array('appointment_from'=>$user_id,'appointment_date'=>$book_date, 'approved'=>1, 'status'=>1, 'appointment_status'=>0, 'call_status'=>0))->where('appointment_time >= CURTIME()')->get('appointments')->result_array();
		return $this->db->select('appointment_time, appointment_end_time')->where(array('appointment_from'=>$user_id,'appointment_date'=>$book_date, 'approved'=>1, 'status'=>1, 'appointment_status'=>0, 'call_status'=>0))->get('appointments')->result_array();
		//echo $this->db->last_query();
    } 




}