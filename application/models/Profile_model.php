<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 */
class Profile_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

  public function get_profile_details($id)
  {
  	    $this->db->select('u.pharmacy_name,u.first_name,u.last_name,u.email,u.mobileno,u.country_code,u.profileimage,ud.*');
        $this->db->from('users u');
        $this->db->join('users_details ud','ud.user_id = u.id','left');
        $this->db->where('u.id', $id);
        $result = $this->db->get()->row_array();
        return $result;
        
  }

  public function get_education_details($id)
  {
        $this->db->select('*');
        $this->db->from('education_details');
        $this->db->where('user_id', $id);
        $result = $this->db->get()->result_array();
        return $result;
  }

  // New 
  public function get_socialmedia_details($id)
  {
        $this->db->select('*');
        $this->db->from('social_media');
        $this->db->where('doctor_id', $id);
        $result = $this->db->get()->result_array();
        return $result;
  }

  public function get_experience_details($id)
  {
        $this->db->select('*');
        $this->db->from('experience_details');
        $this->db->where('user_id', $id);
        $result = $this->db->get()->result_array();
        return $result;
  }

  public function get_awards_details($id)
  {
        $this->db->select('*');
        $this->db->from('awards_details');
        $this->db->where('user_id', $id);
        $result = $this->db->get()->result_array();
        return $result;
  }

  public function get_memberships_details($id)
  {
        $this->db->select('*');
        $this->db->from('memberships_details');
        $this->db->where('user_id', $id);
        $result = $this->db->get()->result_array();
        return $result;
  }

  public function get_registrations_details($id)
  {
        $this->db->select('*');
        $this->db->from('registrations_details');
        $this->db->where('user_id', $id);
        $result = $this->db->get()->result_array();
        return $result;
  }

  public function get_business_hours($id)
  {
        $this->db->select('*');
        $this->db->from('business_hours');
        $this->db->where('user_id', $id);
        $result = $this->db->get()->row_array();
        return $result;
  }

  public function get_monday_hours($id)
  {
        $this->db->select('*');
        $this->db->from('schedule_timings');
        $this->db->where('day_id',2);
        $this->db->where('user_id', $id);
        $result = $this->db->get()->result_array();
        return $result;
  }

  public function get_sunday_hours($id)
  {
        $this->db->select('*');
        $this->db->from('schedule_timings');
        $this->db->where('day_id',1);
        $this->db->where('user_id', $id);
        $result = $this->db->get()->result_array();
        return $result;
  }

  public function get_tue_hours($id)
  {
        $this->db->select('*');
        $this->db->from('schedule_timings');
        $this->db->where('day_id',3);
        $this->db->where('user_id', $id);
        $result = $this->db->get()->result_array();
        return $result;
  }

  public function get_wed_hours($id)
  {
        $this->db->select('*');
        $this->db->from('schedule_timings');
        $this->db->where('day_id',4);
        $this->db->where('user_id', $id);
        $result = $this->db->get()->result_array();
        return $result;
  }

  public function get_thu_hours($id)
  {
        $this->db->select('*');
        $this->db->from('schedule_timings');
        $this->db->where('day_id',5);
        $this->db->where('user_id', $id);
        $result = $this->db->get()->result_array();
        return $result;
  }

  public function get_fri_hours($id)
  {
        $this->db->select('*');
        $this->db->from('schedule_timings');
        $this->db->where('day_id',6);
        $this->db->where('user_id', $id);
        $result = $this->db->get()->result_array();
        return $result;
  }

  public function get_sat_hours($id)
  {
        $this->db->select('*');
        $this->db->from('schedule_timings');
        $this->db->where('day_id',7);
        $this->db->where('user_id', $id);
        $result = $this->db->get()->result_array();
        return $result;
  }

    
  public function check_mobileno($mobileno,$user_id)
  {
        $this->db->select('id,mobileno');
        $this->db->from('users');
        $this->db->where('mobileno', $mobileno);
        $this->db->where('id !=', $user_id);
        $result = $this->db->get()->row_array();
        
       return $result;
        
  }

  Public function get_clinic_images($user_id){
    
    return $this->db->get_where('clinic_images',array('user_id'=>$user_id))->result_array();
  } 

  public function update($data,$id)
  {
        $this->db->where('id',$id);
      $this->db->update('users',$data);
        return ($this->db->affected_rows()!= 1)? false:true;
  }

  public function updates($inputdata,$userdata,$id)
  {
        $this->db->where('id',$id);
        $this->db->update('users',$inputdata);

        $this->db->where('user_id',$id);
        $exits=$this->db->get('users_details')->result_array();
        if(!empty($exits))
        {
          $this->db->where('user_id',$id);
          $this->db->update('users_details',$userdata);
        }
        else
        {
          $this->db->insert('users_details',$userdata);
        }
        

        return ($this->db->affected_rows()!= 1)? false:true;
  }

  public function specupdates($inputdata,$id)
  {

    $this->db->where('pharmacy_id',$id);
    $exits=$this->db->get('pharmacy_specifications')->result_array();
    if(!empty($exits))
    {
      $this->db->where('pharmacy_id',$id);
      $this->db->update('pharmacy_specifications',$inputdata);
    }
    else
    {
      $this->db->insert('pharmacy_specifications',$inputdata);
    }
    

    return ($this->db->affected_rows()!= 1)? false:true;
  }

  public function check_currentpassword($password,$id)
  {
        $this->db->select('id,email');
        $this->db->from('users');
        $this->db->where('id', $id);
        $this->db->where('password', md5($password));
        $result = $this->db->get()->row_array();
        
       return $result;
           
         
  }
  Public function get_clinic_details_by_doctor($user_id){
    
      $this->db->select('*');
      $this->db->from('doctor_assign_clinic as ad');
      $this->db->join('clinic_details cd','cd.clinic_details_id = ad.clinic_details_id','left');
      $this->db->where('ad.doctor_id',$user_id);
       $result = $this->db->get()->result_array();
          return $result;
      
      //return $this->db->get_where('clinic_details',array('status'=>1))->result_array();
    } 
  // ClinincModule
  Public function get_clinic_doctor_details($user_id){
    
      //  return $this->db->get_where('clinic_details',array('doctor_id'=>$user_id))->result_array();
    
        $this->db->select('*');
        $this->db->from('clinic_details cd');
        $this->db->join('specialization sp','sp.id = cd.specialization_id','left');
        $this->db->where('cd.doctor_id',$user_id);
         $result = $this->db->get()->result_array();
            return $result;
    
    
    
      } 
      Public function get_insyrance_details($user_id){
    
      return $this->db->get_where('insurance_company',array('status'=>1))->result_array();
      }
      
      Public function get_doctor_images($user_id){
      
            return $this->db->get_where('doctor_photos',array('user_id'=>$user_id))->result_array();
      } 

      public function get_language()
  {
        $this->db->select('*');
        $this->db->from('language_spoken');
       // $this->db->where('user_id',$id);
        return $result = $this->db->get()->result_array();
  }

  public function get_language_known($id)
  {
        $this->db->select('*');
        $this->db->from('user_language_known');
        $this->db->where('user_id',$id);
        $result = $this->db->get()->result_array();
        return $result;
  }

      public function get_language_known_byuser($id)
  {
        $this->db->select('*');
        $this->db->from('user_language_known as ul');
        $this->db->join('language_spoken ls','ls.language_id = ul.language_id','left');
        $this->db->where('ul.user_id',$id);
        $result = $this->db->get()->result_array();
        return $result;
  }
  public function get_clinic_doctor($id){
      $this->db->select('*');
      $this->db->from('clinic_details cd');
      $this->db->join('specialization sp','sp.id = cd.specialization_id','left');
      $this->db->where('clinic_details_id',$id);
      $result = $this->db->get()->row_array();
      return $result;
  }
  
  
}
?>
