<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 * @property object $api
 * @property object $user_id
 */
class Api_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();

      
  }
 
  public function read_clinic_list()
    {
        $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value, "" as currency');
          $this->db->from('users u');
          $this->db->join('users_details ud','ud.user_id = u.id','left');
          $this->db->join('country c','ud.country = c.countryid','left');
          $this->db->join('state s','ud.state = s.id','left');
          $this->db->join('city ci','ud.city = ci.id','left');
          $this->db->join('specialization sp','ud.specialization = sp.id','left');
          $this->db->where('u.role','6');
          $this->db->where('u.status','1');
          $this->db->where('u.is_verified','1');
          $this->db->where('u.is_updated','1');
          $this->db->group_by('ud.id');
          $this->db->order_by('rand()');
          $this->db->limit(5);
          return $this->db->get()->result_array();
    }

  public function read_pharmacy_productlist_search($pharmacy_id,$keywords,$category,$subcategory){
    
    
  $this->db->select('p.*,c.category_name,s.subcategory_name,u.unit_name');
  $this->db->from('products as p'); 
  $this->db->join('product_categories as c','p.category = c.id','left');
  $this->db->join('product_subcategories as s','p.subcategory = s.id','left');
  $this->db->join('unit as u','p.unit = u.id','left'); 
  $this->db->where("(p.status = '1' OR p.status = '2')");

    if(!empty($pharmacy_id)) {   
      $this->db->where("p.user_id",$pharmacy_id);
    }

    if(!empty($category)) {   
      $this->db->where('c.id',$category);
    }

    if(!empty($subcategory)) {   
      $this->db->where('s.id',$subcategory);
    }

    if(!empty($keywords)) {  
      $this->db->group_start();
      $this->db->like('c.category_name',$keywords);
      $this->db->or_like('s.subcategory_name',$keywords);
      $this->db->or_like('p.name',$keywords);
      $this->db->group_end();
    }

  $this->db->order_by('p.id', 'DESC');
    return $this->db->get()->result_array();
  }


  public function all_pharmacy_products(){
  $this->db->select('p.*,c.category_name,s.subcategory_name,u.unit_name');
  $this->db->from('products as p'); 
  $this->db->join('product_categories as c','p.category = c.id','left');
  $this->db->join('product_subcategories as s','p.subcategory = s.id','left');
  $this->db->join('unit as u','p.unit = u.id','left'); 
  $this->db->where("(p.status = '1' OR p.status = '2')");
  $this->db->order_by('p.id', 'DESC');
    return $this->db->get()->result_array();
  }


  public function doctor_role($doctor_id)
  {
       $query="select * from users where id='".$doctor_id."'";
       return $result = $this->db->query($query)->row_array(); 
  }

  public function assign_doc($id,$app_id,$user_id){
		$inputdata =[];		
		$inputdata['appointment_to'] = $id;
		$inputdata['hospital_id'] = $user_id;
		$this->db->where('id',$app_id);
		$this->db->update('appointments',$inputdata);
		//echo $this->db->last_query();	  
	} 
  public function get_clinic_doctor_details($doctor_id)
  {      
     $profileimage='assets/img/user.png';
     $this->db->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,IF(u.profileimage IS NULL or u.profileimage = "", "'.$profileimage.'", u.profileimage) as profileimage,ud.user_id,ud.gender,ud.dob,ud.blood_group,ud.biography,ud.clinic_name,ud.clinic_address,ud.address1,ud.address2,ud.postal_code,IF(ud.price_type = "Custom Price", "Paid", "Free") as price_type,IF(ud.amount IS NULL or ud.amount = "", "0", ud.amount) as amount,ud.services,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value,ud.*');
      $this->db->from('users u');
      $this->db->join('users_details ud','ud.user_id = u.id','left');
      $this->db->join('country c','ud.country = c.countryid','left');
      $this->db->join('state s','ud.state = s.id','left');
      $this->db->join('city ci','ud.city = ci.id','left');
      $this->db->join('specialization sp','ud.specialization = sp.id','left');
      // $this->db->where('u.role','6');
      $this->db->where("(u.status = '1' OR u.status = '2')");
      $this->db->where('u.is_verified','1');
      $this->db->where('u.is_updated','1');
      $this->db->where('u.id',$doctor_id);
      $query = $this->db->get();
  //echo $this->db->last_query();
   
  return $query->row_array();  
}
  public function pharmacy_invoice_list($user_id)
  {
	$this->db->select('p.*, CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage,d.id as doctor_id,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.id as patient_id,d.role');
    $this->db->from('payments p');
    $this->db->join('users d', 'd.id = p.doctor_id', 'left'); 
    $this->db->join('users_details dd','dd.user_id = d.id','left'); 
    $this->db->join('users pi', 'pi.id = p.user_id', 'left'); 
    $this->db->join('users_details pd','pd.user_id = pi.id','left');
	$this->db->where('p.doctor_id',$user_id);
    $this->db->where('p.payment_status',1);
	$query = $this->db->get();
    return $query->result_array();
  }

   public function get_user_id_using_token($token)
    {
      if($token!=''){
        $this->db->select('*');
        $records = $this->db->get_where('users', array('token' => $token))->row_array();
        if(!empty($records)){
          return $records;
        }
      }
      return 0;
    }

    public function language_list()
   {
        $this->db->select('language,language_value,tag');
        $this->db->from('language');
        $this->db->where('status', '1');
        $records = $this->db->get()->result_array();
        return $records;
   }

    public function language_keywords($languages)
   {
        $this->db->select('lang_key,lang_value,language,placeholder,validation1,validation2,validation3,type,page_key');
    $this->db->from('app_language_management');
    $this->db->where('language', 'en');
    $this->db->where('type', 'App');
   $records = $this->db->get()->result_array();


    $language = array();
    if(!empty($records)){
      foreach ($records as $record) {
        $this->db->select('lang_key,lang_value,language,placeholder,validation1,validation2,validation3,type,page_key');
        $this->db->from('app_language_management');
        $this->db->where('language', $languages);
        $this->db->where('type', 'App');
        $this->db->where('page_key', $record['page_key']);
        $this->db->where('lang_key', $record['lang_key']);
        $eng_records = $this->db->get()->row_array();
            if(!empty($eng_records['lang_value'])){

            $language['language'][$record['page_key']][$record['lang_key']] = $eng_records['lang_value'];
            

          }
          else {
            $language['language'][$record['page_key']][$record['lang_key']] = $record['lang_value'];
            
          }
          
        }
    }
    return $language;
   }

    public function doctor_list()
    {
        $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value');
          $this->db->from('users u');
          $this->db->join('users_details ud','ud.user_id = u.id','left');
          $this->db->join('country c','ud.country = c.countryid','left');
          $this->db->join('state s','ud.state = s.id','left');
          $this->db->join('city ci','ud.city = ci.id','left');
          $this->db->join('specialization sp','ud.specialization = sp.id','left');
          $this->db->where('u.role','1');
          $this->db->where('u.status','1');
          $this->db->where('u.is_verified','1');
          $this->db->where('u.is_updated','1');
      $this->db->group_by('ud.id');
      $this->db->order_by('rand()');
      $this->db->limit(5);
          return $this->db->get()->result_array();
    }

    public function specialization_list()
    {
        $this->db->where('status', 1);
        $this->db->order_by('sequence','ASC');
      $this->db->limit(5);
        $query = $this->db->get('specialization');
        return $query->result_array();
    }

    public function doctor_lists($pages,$limits,$type=1)
    {
        $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value');
          $this->db->from('users u');
          $this->db->join('users_details ud','ud.user_id = u.id','left');
          $this->db->join('country c','ud.country = c.countryid','left');
          $this->db->join('state s','ud.state = s.id','left');
          $this->db->join('city ci','ud.city = ci.id','left');
          $this->db->join('specialization sp','ud.specialization = sp.id','left');
          $this->db->where('u.role','1');
          $this->db->where('u.status','1');
          $this->db->where('u.is_verified','1');
          $this->db->where('u.is_updated','1');
      $this->db->group_by('ud.id');
      if($type == 1){
                return $this->db->count_all_results(); 
          }else{
          $page = !empty($pages)?$pages:'';
          $limit = $limits;
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->order_by('u.id', 'DESC');
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
          }
    }

    public function patients_lists($pages='',$limits='',$type=1,$user_id='')
    {
        $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname');
        $this->db->from('appointments a');
        $this->db->join('users u','a.appointment_from = u.id','left');
        $this->db->join('users_details ud','ud.user_id = u.id','left');
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left');
        $this->db->where('u.role','2');
        $this->db->where('a.appointment_to',$user_id);
        $this->db->group_by('a.appointment_from');
        
      if($type == 1){
                return $this->db->count_all_results(); 
          }else{
          $page = !empty($pages)?$pages:'';
          $limit = $limits;
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
          }
    }

     public function my_doctor_lists($pages='',$limits='',$type=1,$user_id='')
    {
        $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value');
        $this->db->from('appointments a');
        $this->db->join('users u','a.appointment_to = u.id','left');
        $this->db->join('users_details ud','ud.user_id = u.id','left');
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left');
        $this->db->join('specialization sp','ud.specialization = sp.id','left');
        $this->db->where('u.role','1');
        $this->db->where('a.appointment_from',$user_id);
        $this->db->group_by('a.appointment_to');
        
      if($type == 1){
                return $this->db->count_all_results(); 
          }else{
          $page = !empty($pages)?$pages:'';
          $limit = $limits;
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
          }
    }

    
    public function doctor_search($user_data=array(),$pages='',$limits='',$type=1,$roles='')
    {
			$this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value, u.role');
			$this->db->from('users u');
			$this->db->join('users_details ud','ud.user_id = u.id','left');
			$this->db->join('country c','ud.country = c.countryid','left');
			$this->db->join('state s','ud.state = s.id','left');
			$this->db->join('city ci','ud.city = ci.id','left');
			$this->db->join('specialization sp','ud.specialization = sp.id','left');
		  
			if(!empty($user_data['role'])){
				$this->db->where_in('u.role',$user_data['role']);
			} else {
				$this->db->where_in('u.role',1);
			}
		  
		  /*if(!empty($roles)){
			  $this->db->where_in('u.role',[1,6]);
		  } else {
			$this->db->where_in('u.role', 1);  
		  }
          //$this->db->where('u.role','1');*/
          $this->db->where('u.status','1');
          $this->db->where('u.is_verified','1');
          $this->db->where('u.is_updated','1');
          if(!empty($user_data['cities'])){
           $this->db->where("(s.statename = '".$user_data['cities']."' OR ci.city = '".$user_data['cities']."')");
          }
        
         if(!empty($user_data['city'])){
         $this->db->where("(ud.state = '".$user_data['city']."' OR ud.city = '".$user_data['city']."')");
         }
        if(!empty($user_data['state'])){
         $this->db->where("(ud.state = '".$user_data['state']."' OR ud.city = '".$user_data['state']."')");
        }
        if(!empty($user_data['country'])){
          $this->db->where('ud.country',$user_data['country']);
        }
        if(!empty($user_data['specialization'])) {   
         $this->db->where('ud.specialization',$user_data['specialization']);
        }

         if(!empty($user_data['keywords'])) {  
          $this->db->group_start();
          $this->db->like('u.first_name',$user_data['keywords'],'after');
          $this->db->or_like('u.last_name',$user_data['keywords'],'after');
          $this->db->or_like('CONCAT( u.first_name, " ", u.last_name)',$user_data['keywords'],'after');
          $this->db->or_like('sp.specialization',$user_data['keywords']);
          $this->db->group_end();
        }

        if(!empty($user_data['gender'])) {   
          $this->db->where('ud.gender',$user_data['gender']);
        }
        if(!empty($user_data['order_by'])) {   
        if($user_data['order_by'] == 'Free'){
          $this->db->where('ud.price_type','Free');
        }
      }
        $this->db->group_by('ud.id');
        $this->db->order_by('u.id','DESC');
       
      if($type == 1){
                return $this->db->count_all_results(); 
          }else{
          $page = !empty($pages)?$pages:'';
          $limit = $limits;
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->limit($limit,$page);
          $query = $this->db->get(); 
		  //echo $this->db->last_query();
		  return $query->result_array();
          }
    }


    public function specialization_lists($pages,$limits,$type=1)
    {
        $this->db->select('*');
        $this->db->from('specialization');
        $this->db->where('status', 1);
        if($type == 1){
                return $this->db->count_all_results(); 
          }else{
          $page = !empty($pages)?$pages:'';
          $limit = $limits;
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->order_by('id', 'DESC');
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
          }
    }

    public function is_valid_login($email,$password,$device_id,$device_type)
     {
      $password = md5($password);
      $profileimage='assets/img/user.png';
      $this->db->select('id,email,first_name,last_name,username,mobileno,role,token,status,IF(profileimage IS NULL or profileimage = "", "'.$profileimage.'", profileimage) as profileimage');
      $this->db->from('users');
      $this->db->where("email",$email);
      $this->db->where('password',$password);
      $result = $this->db->get()->row_array();
      if(!empty($result))
      {
        $user_id = $result['id'];
            $token = $this->getToken(14,$user_id);
            $result['token'] = $token;
            $this->db->where('id', $user_id);
            $this->db->update('users', array('token' => $token,'device_id'=>$device_id,'device_type'=>$device_type));
      }

      return $result;
    }

    public function signup($inputdata)
  {
       $this->db->insert('users',$inputdata);
       return ($this->db->affected_rows()!= 1)? false:true;
  }

    public function getToken($length,$user_id)
    {
 
       $token = $user_id;
       $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
       $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
       $codeAlphabet.= "0123456789";
       $max = strlen($codeAlphabet); // edited
       for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
       }
       return $token;

    }

    function crypto_rand_secure($min, $max) {

        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;

  }

  public function get_doctor_details($doctor_id)
  {

       $profileimage='assets/img/user.png';
       $this->db->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,IF(u.profileimage IS NULL or u.profileimage = "", "'.$profileimage.'", u.profileimage) as profileimage,ud.user_id,ud.gender,ud.dob,ud.blood_group,ud.biography,ud.clinic_name,ud.clinic_address,ud.address1,ud.address2,ud.postal_code,IF(ud.price_type = "Custom Price", "Paid", "Free") as price_type,IF(ud.amount IS NULL or ud.amount = "", "0", ud.amount) as amount,ud.services,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value,ud.*');
        $this->db->from('users u');
        $this->db->join('users_details ud','ud.user_id = u.id','left');
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left');
        $this->db->join('specialization sp','ud.specialization = sp.id','left');
        $this->db->where('u.role','1');
        $this->db->where("(u.status = '1' OR u.status = '2')");
        $this->db->where('u.is_verified','1');
        $this->db->where('u.is_updated','1');
        $this->db->where('u.id',$doctor_id); 
        return $result = $this->db->get()->row_array();
        
  }

  public function doctor_details_profile($doctor_id,$role)
  {

       $profileimage='assets/img/user.png';
       $this->db->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.country_code,u.mobileno,IF(u.profileimage IS NULL or u.profileimage = "", "'.$profileimage.'", u.profileimage) as profileimage,ud.user_id,ud.gender,ud.dob,ud.blood_group,ud.register_no,ud.biography,ud.clinic_name,ud.clinic_address,ud.address1,ud.address2,ud.postal_code,IF(ud.price_type = "Custom Price", "Paid", "Free") as price_type,IF(ud.amount IS NULL or ud.amount = "", "0", ud.amount) as amount,ud.services,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value,ud.*');
        $this->db->from('users u');
        $this->db->join('users_details ud','ud.user_id = u.id','left');
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left');
        $this->db->join('specialization sp','ud.specialization = sp.id','left');
        // $this->db->where('u.role','1');
        // $this->db->where('u.role','6');
        if($role==1)
        {
          $this->db->where('u.role','1');
        }
        if($role==6)
        {
          $this->db->where('u.role','6');
        }
       
        $this->db->where('u.id',$doctor_id);
        return $result = $this->db->get()->row_array();
        
  }
  public function get_patient_details($patient_id)
  {

       $profileimage='assets/img/user.png';
       $this->db->select('u.id as userid,u.insurance_company_name as insurance_name,u.insurance_number as policy_number,u.first_name,u.last_name,u.email,u.username,u.mobileno,IF(u.profileimage IS NULL or u.profileimage = "", "'.$profileimage.'", u.profileimage) as profileimage,
        IF(ud.user_id IS NULL,"",ud.user_id) as user_id,
        IF(ud.gender IS NULL,"",ud.gender) as gender,
        IF(ud.dob IS NULL,"",ud.dob) as dob,
        IF(ud.blood_group IS NULL,"",ud.blood_group) as blood_group,
        IF(ud.address1 IS NULL,"",ud.address1) as address1,
        IF(ud.address2 IS NULL,"",ud.address2) as address2,
        IF(ud.postal_code IS NULL,"",ud.postal_code) as postal_code,
        IF(ud.country IS NULL,"",ud.country) as country,
        IF(ud.state IS NULL,"",ud.state) as state,
        IF(ud.city IS NULL,"",ud.city) as city,
        IF(c.country IS NULL,"",c.country) as countryname,
        IF(s.statename IS NULL,"",s.statename) as statename,
        IF(ci.city IS NULL,"",ci.city) as cityname, u.pharmacy_name');
        $this->db->from('users u');
        $this->db->join('users_details ud','ud.user_id = u.id','left');
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left');
        $this->db->where('u.id',$patient_id);
        return $result = $this->db->get()->row_array();
        
  }
  
  public function create_product($inputdata)
	{
	   $this->db->insert('products',$inputdata);
	   return ($this->db->affected_rows()!= 1)? false:true;
	}
	
	public function update_product($data,$id){
		$this->db->where('id',$id);
		$this->db->update('products',$data);
		return ($this->db->affected_rows()!= 1)? false:true;
	}
	
	public function get_pharmacy_acclist($user_id,$pages,$limits,$type=1){
		$this->db->select('p.id,p.status as status,p.ordered_at as payment_date,p.subtotal as price,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.id as patient_id,pd.currency_code as currency_code');
		$this->db->from('orders p');
		$this->db->join('users pi', 'pi.id = p.user_id', 'left'); 
		$this->db->join('users_details pd','pd.user_id = pi.id','left');
		$this->db->where('p.pharmacy_id',$user_id);
		$this->db->where('p.status',1);
		$this->db->where('transaction_status !=','Pay on arrive');
		$this->db->order_by('p.id','desc');
		
		if($type == 1){
			return $this->db->count_all_results(); 
		}else{
			$page = !empty($pages)?$pages:'';
			$limit = $limits;
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);  
			
			$this->db->limit($limit,$page); 
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result_array();
		}
	}
	
	
	public function update_pharamcy_profile($inputdata,$userdata,$specificdata,$id)
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
		
		$this->db->where('pharmacy_id',$id);
        $pharexits=$this->db->get('pharmacy_specifications')->result_array();
        if(!empty($pharexits))
        {
          $this->db->where('pharmacy_id',$id);
          $this->db->update('pharmacy_specifications',$specificdata);
        }
        else
        {
          $this->db->insert('pharmacy_specifications',$specificdata);
        }

        return ($this->db->affected_rows()!= 1)? false:true;
	}

  public function get_education_details($id)
  {
        $this->db->select('*');
        $this->db->from('education_details');
        $this->db->where('user_id', $id);
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

   public function review_list_view($id)
{
  
    $profileimage='assets/img/user.png';
    $where  = array('r.doctor_id'=>$id);
    return  $this->db
    ->select('IF(u.profileimage IS NULL or u.profileimage = "", "'.$profileimage.'", u.profileimage) as profileimage,u.first_name,u.last_name,r.*')
    ->join('users u ','u.id = r.user_id')
    ->get_where('rating_reviews r',$where)
    ->result_array();
}

   public function review_list($pages='',$limits='',$type=1,$user_id='',$role_id='')
{
  
    $profileimage='assets/img/user.png';
    
            $this->db->select('IF(u.profileimage IS NULL or u.profileimage = "", "'.$profileimage.'", u.profileimage) as profileimage,u.first_name,u.last_name,r.*');
        $this->db->from('rating_reviews r');
        // $this->db->join('users u ','u.id = r.user_id');
        if($role_id == 1){
          $this->db->join('users u ','r.user_id = u.id');
          $this->db->where('r.doctor_id',$user_id);
        }else{
          $this->db->join('users u ','r.doctor_id = u.id');
          $this->db->where('r.user_id',$user_id);
        }
        

          if($type == 1){
                return $this->db->count_all_results(); 
          }else{
          $page = !empty($pages)?$pages:'';
          $limit = $limits;
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
          }

}

public function update_patient_profile($inputdata,$userdata,$id)
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

  public function check_currentpassword($password,$id)
  {
        $this->db->select('id,email');
        $this->db->from('users');
        $this->db->where('id', $id);
        $this->db->where('password', md5($password));
        $result = $this->db->get()->row_array();
        
       return $result;
           
         
  }

  public function update($data,$id)
  {
        $this->db->where('id',$id);
      $this->db->update('users',$data);
        return ($this->db->affected_rows()!= 1)? false:true;
  }

   public function get_schedule_timings($id,$day_id)
  {
      $this->db->where('user_id',$id);
      $this->db->where('day_id',$day_id);
      $query = $this->db->get('schedule_timings');
      $result = $query->result_array();

      return $result;
  }

  public function appointments_count($type,$user_id,$role)
    {

        $current_date = date('Y-m-d');
        $from_date_time = date('Y-m-d H:i:s');
        $this->db->select('a.*,u.first_name,u.last_name,u.username,u.profileimage,p.per_hour_charge,u.role');
        $this->db->from('appointments a');
        $this->db->join('payments p','p.id = a.payment_id','left');   
        if($role==1||$role==6)
        {
          $this->db->join('users u', 'u.id = a.appointment_from', 'left');
          $this->db->where('a.appointment_to',$user_id);
        }
        if($role==2)
        {
          $this->db->join('users u', 'u.id = a.appointment_to', 'left');
          $this->db->where('a.appointment_from',$user_id);
        }

            
          if($type == 1){
          $this->db->where('a.appointment_date',$current_date);
          }

          if($type == 2){
          $this->db->where('a.from_date_time > ',$from_date_time);
         }
      
      
                return $this->db->count_all_results(); 
         
    } 

        public function appointments_lists($pages='',$limits='',$type=1,$user_data=array(),$user_id='',$role='')
    {

        $current_date = date('Y-m-d');
        $from_date_time = date('Y-m-d H:i:s');
        $this->db->select('a.*,u.first_name,u.last_name,u.username,u.profileimage,p.per_hour_charge,u.role');
        $this->db->from('appointments a');
        $this->db->join('payments p','p.id = a.payment_id','left');   
        if($role==1||$role==6)
        {
          $this->db->join('users u', 'u.id = a.appointment_from', 'left');
          $this->db->where('a.appointment_to',$user_id);
        }
        if($role==2)
        {
          $this->db->join('users u', 'u.id = a.appointment_to', 'left');
          $this->db->where('a.appointment_from',$user_id);
        }

        if(!empty($user_data['payment_method']))
        {
            $this->db->where('a.payment_method',$user_data['payment_method']);
        }else{
			$this->db->group_start();
            $this->db->where('a.payment_method',1);
			// $this->db->or_where('a.payment_method',2);
            $this->db->or_where('a.payment_method',3);
			$this->db->or_where('a.payment_method',4);
			$this->db->or_where('a.payment_method','Paypal');
			$this->db->or_where('a.payment_method','Online');
			$this->db->group_end();
        }
       
       if(!empty($user_data['type']))
       {
          if($user_data['type'] == 1){
          $this->db->where('a.appointment_date',$current_date);
          }

          if($user_data['type'] == 2){
          $this->db->where('a.from_date_time > ',$from_date_time);
         }
       } 
    
      if($type == 1){
                return $this->db->count_all_results(); 
          }else{
          $page = !empty($pages)?$pages:'';
          $limit = $limits;
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          //$this->db->order_by('a.from_date_time', 'DESC');
		  $this->db->order_by('a.id', 'DESC');
          $this->db->limit($limit,$page);
          $query = $this->db->get(); 
		  //echo $this->db->last_query();
		  return $query->result_array();
          }
    }

 
    public function appointments_history($pages='',$limits='',$type=1,$patient_id='',$user_id='',$role='',$user_data=array())
    {

       
        $this->db->select('a.*,u.first_name,u.last_name,u.username,u.profileimage,p.per_hour_charge,u.role,,s.specialization');
        $this->db->from('appointments a');
        $this->db->join('payments p','p.id = a.payment_id','left'); 
        $this->db->join('users u', 'u.id = a.appointment_to', 'left'); 
        $this->db->join('users_details ud','ud.user_id = u.id','left'); 
        $this->db->join('specialization s','ud.specialization = s.id','left');  
        $this->db->where('a.appointment_from',$patient_id);
        if($role==1){
          $this->db->where('a.appointment_to',$user_id);
        }
        if(!empty($user_data['payment_method']))
        {
            $this->db->where('a.payment_method',$user_data['payment_method']);
        }{
          $this->db->group_start();
                $this->db->where('a.payment_method',1);
          // $this->db->or_where('a.payment_method',2);
                $this->db->or_where('a.payment_method',3);
          $this->db->or_where('a.payment_method',4);
          $this->db->or_where('a.payment_method','Paypal');
          $this->db->or_where('a.payment_method','Online');
          $this->db->group_end();
            }
       $this->db->group_by('a.id');
    
      if($type == 1){
                return $this->db->count_all_results(); 
          }else{
          $page = !empty($pages)?$pages:'';
          $limit = $limits;
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->order_by('a.from_date_time', 'DESC');
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
          }
    }

    public function prescription_list($pages='',$limits='',$type=1,$patient_id='',$user_id='',$role='')
    {

        $this->db->select('p.*,CONCAT(u.first_name," ", u.last_name) as doctor_name,CONCAT(u1.first_name," ", u1.last_name) as patient_name ,u.profileimage as doctor_image,u1.profileimage as patient_image,s.specialization,si.img as signature_image');
        $this->db->from('prescription p');
        $this->db->join('prescription_item_details pd', 'pd.prescription_id=p.id', 'left'); 
        $this->db->join('users u', 'u.id = p.doctor_id', 'left');
        $this->db->join('users u1', 'u1.id = p.patient_id', 'left');
        $this->db->join('users_details ud','ud.user_id = u.id','left'); 
        $this->db->join('specialization s','ud.specialization = s.id','left');
        $this->db->join('signature si','p.signature_id=si.id','left');
        $this->db->where('p.patient_id',$patient_id);
        $this->db->where('p.status',1);
        if($role==1){
          $this->db->where('p.doctor_id',$user_id);
        }
        $this->db->group_by('p.id');

       
      if($type == 1){
                return $this->db->count_all_results(); 
          }else{
          $page = !empty($pages)?$pages:'';
          $limit = $limits;
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->order_by('p.id', 'ASC');
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
          }
    }

    public function prescription_details($prescription_id){
      $this->db->select('pd.*');
      $this->db->from('prescription_item_details pd');
      $this->db->where('pd.prescription_id',$prescription_id);
      return $this->db->get()->result_array();
  }
public function get_prescription_view($prescription_id){
      $this->db->select('p.*,si.img as signature_image');
      $this->db->from('prescription p');
      $this->db->join('signature si','p.signature_id=si.id','left');
      $this->db->where('p.id',$prescription_id);

      return $this->db->get()->row_array();
  }


    public function medical_records_list($pages='',$limits='',$type=1,$patient_id='',$user_id='',$role='')
    {

        $this->db->select('m.*,CONCAT(u.first_name," ", u.last_name) as doctor_name,CONCAT(u1.first_name," ", u1.last_name) as patient_name ,u.profileimage as doctor_image,u1.profileimage as patient_image,s.specialization');
        $this->db->from('medical_records m');
        $this->db->join('users u', 'u.id = m.doctor_id', 'left');
        $this->db->join('users u1', 'u1.id = m.patient_id', 'left');
        $this->db->join('users_details ud','ud.user_id = u.id','left'); 
        $this->db->join('specialization s','ud.specialization = s.id','left');
        $this->db->where('m.patient_id',$patient_id);
        $this->db->where('m.status',1);
        if($role==1){
          $this->db->where('m.doctor_id',$user_id);
          $this->db->or_where('m.doctor_id',0);
        }
        $this->db->group_by('m.id');

       
      if($type == 1){
                return $this->db->count_all_results(); 
          }else{
          $page = !empty($pages)?$pages:'';
          $limit = $limits;
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->order_by('m.id', 'ASC');
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
          }
    }


    public function billing_list($pages='',$limits='',$type=1,$patient_id='',$user_id='',$role='')
    {

        $this->db->select('b.*,CONCAT(u.first_name," ", u.last_name) as doctor_name,CONCAT(u1.first_name," ", u1.last_name) as patient_name ,u.profileimage as doctor_image,u1.profileimage as patient_image,s.specialization,si.img as signature_image');
        $this->db->from('billing b');
        $this->db->join('billing_item_details bd', 'bd.billing_id=b.id', 'left'); 
        $this->db->join('users u', 'u.id = b.doctor_id', 'left');
        $this->db->join('users u1', 'u1.id = b.patient_id', 'left');
        $this->db->join('users_details ud','ud.user_id = u.id','left'); 
        $this->db->join('specialization s','ud.specialization = s.id','left');
        $this->db->join('signature si','b.signature_id=si.id','left');
        $this->db->where('b.patient_id',$patient_id);
        $this->db->where('b.status',1);
        if($role==1){
          $this->db->where('b.doctor_id',$user_id);
        }
        $this->db->group_by('b.id');

       
      if($type == 1){
                return $this->db->count_all_results(); 
          }else{
          $page = !empty($pages)?$pages:'';
          $limit = $limits;
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->order_by('b.id', 'ASC');
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
          }
    }

    public function billing_details($billing_id){
      $this->db->select('bd.*');
      $this->db->from('billing_item_details bd');
      $this->db->where('bd.billing_id',$billing_id);
      return $this->db->get()->result_array();
  }

  public function get_appoinment_call_details($appoinment_id)
  {
       $this->db->select('a.*, CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage,d.device_id as doctor_device_id,d.device_type as doctor_device_type, CONCAT(p.first_name," ", p.last_name) as patient_name,p.profileimage as patient_profileimage,p.id as patient_id,p.device_id as patient_device_id,p.device_type as patient_device_type,d.id as doctor_id');
        $this->db->from('appointments a');
        $this->db->join('users d', 'd.id = a.appointment_to', 'left'); 
        $this->db->join('users_details dd','dd.user_id = d.id','left'); 
        $this->db->join('users p', 'p.id = a.appointment_from', 'left'); 
        $this->db->join('users_details pd','pd.user_id = p.id','left'); 
        $this->db->where('a.id',$appoinment_id);
        return $this->db->get()->row_array();
  }

  public function get_favourites($pages='',$limits='',$type=1,$user_id='')
  {
        $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value');
        $this->db->from('favourities f');
        $this->db->join('users u','u.id = f.doctor_id','left');
        $this->db->join('users_details ud','ud.user_id = u.id','left');
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left');
        $this->db->join('specialization sp','ud.specialization = sp.id','left');
        $this->db->where('f.patient_id',$user_id);
      if($type == 1){
                return $this->db->count_all_results(); 
          }else{
          $page = !empty($pages)?$pages:'';
          $limit = $limits;
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
          }
  }

  public function is_favourite($doctor_id,$patient_id)
  { 
         $favourites='0';
         $where=array('patient_id' =>$patient_id,'doctor_id'=>$doctor_id);
         $is_favourite=$this->db->get_where('favourities',$where)->result_array();
         if(count($is_favourite) > 0 )
          {
            $favourites='1';
          }
            return $favourites;
  }


  public function get_patients($user_id)
  {
     $profileimage='assets/img/user.png';


    $this->db->select('u.id as userid,u.role,u.first_name,u.last_name,u.username,IF(u.profileimage IS NULL or u.profileimage = "", "'.$profileimage.'", u.profileimage) as profileimage,(select chatdate from chat where sent_id = a.appointment_to GROUP BY a.appointment_to ORDER BY chatdate DESC LIMIT 1) as chatdate,(select msg from chat where sent_id = a.appointment_to GROUP BY a.appointment_to ORDER BY id DESC LIMIT 1) as lastchat');
        $this->db->from('appointments a');
        $this->db->join('users u', 'u.id = a.appointment_from', 'left');
        $this->db->where('a.appointment_to',$user_id);
        $this->db->group_by('a.appointment_from');
        $this->db->order_by('chatdate','DESC');
        return $this->db->get()->result_array();
    // $this->db->select('u.id as userid,u.role,u.first_name,u.last_name,u.username,IF(u.profileimage IS NULL or u.profileimage = "", "'.$profileimage.'", u.profileimage) as profileimage,c.chatdate,c.msg as lastchat');
    //     $this->db->from('chat c');
    //     $this->db->join('users u', 'u.id = c.recieved_id', 'left');
    //     $this->db->where('c.sent_id',$user_id);
    //     $this->db->group_by('c.recieved_id');
    //     $this->db->order_by('c.chatdate','DESC');
    //     return $this->db->get()->result_array();
        
  }

  public function get_doctors($user_id)
  {
     $profileimage='assets/img/user.png';

    $this->db->select('u.id as userid,u.role,u.first_name,u.last_name,u.username,IF(u.profileimage IS NULL or u.profileimage = "", "'.$profileimage.'", u.profileimage) as profileimage,(select chatdate from chat where recieved_id = "'.$user_id.'" GROUP BY a.appointment_from ORDER BY chatdate DESC LIMIT 1) as chatdate,(select msg from chat where recieved_id = "'.$user_id.'" GROUP BY a.appointment_from ORDER BY id DESC LIMIT 1) as lastchat');
        $this->db->from('appointments a');
        $this->db->join('users u', 'u.id = a.appointment_to', 'left');
        $this->db->where('a.appointment_from',$user_id);
        $this->db->group_by('a.appointment_to');
         $this->db->order_by('chatdate','DESC');
        return $this->db->get()->result_array();
   
        
  }

 

    public function get_latest_chat($selected_user,$user_id)
{

  
    $this->update_counts($selected_user,$user_id);

    $query = $this->db->query("SELECT DISTINCT CONCAT(sender.first_name,' ',sender.last_name) as senderName, sender.profileimage as senderImage, sender.id as sender_id,CONCAT(receiver.first_name,' ',receiver.last_name) as receiverName, receiver.profileimage as receiverImage, receiver.id as receiver_id,receiver.device_id as receiver_device_id,receiver.device_type as receiver_device_type,msg.msg, msg.chatdate,msg.id,msg.type,msg.file_name,msg.file_path,msg.time_zone,msg.id,sender.first_name as sender_from_firstusername,sender.last_name as sender_from_lastusername,receiver.first_name as reciever_first_username,receiver.last_name as reciever_last_username
        from chat msg
        LEFT  join users sender on msg.sent_id = sender.id
        LEFT  join users receiver on msg.recieved_id = receiver.id
        left join chat_deleted_details cd on cd.chat_id  = msg.id
        where cd.can_view = $user_id AND ((msg.recieved_id = $selected_user AND msg.sent_id = $user_id) or  (msg.recieved_id = $user_id AND msg.sent_id =  $selected_user))   ORDER BY msg.id ASC ");
    $result = $query->result_array();
    return $result;

}

  Public function update_counts($selected_user,$user_id)
  {

     $sql = "SELECT msg.id  from chat msg
    left join chat_deleted_details cd on cd.chat_id  = msg.id
    where  cd.can_view = $user_id AND ((msg.recieved_id = $selected_user AND msg.sent_id = $user_id) or  (msg.recieved_id = $user_id AND msg.sent_id =  $selected_user))   ORDER BY msg.id DESC ";

    return  $this->db->query($sql)->num_rows();

  }

  public function logout($user_id)
  {
      $this->db->where('id',$user_id);
      $this->db->update('users',array('token' =>'','device_id' =>''));
  }


  public function doctor_details($user_id)
    {
        $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value');
        $this->db->from('users u');
        $this->db->join('users_details ud','ud.user_id = u.id','left');
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left');
        $this->db->join('specialization sp','ud.specialization = sp.id','left');
        // $this->db->where('u.role','1');
        $this->db->where('u.id',$user_id);
       $rows= $this->db->get()->row_array();
       $data=array();
       if(!empty($rows))
       {
          $data['id']=$rows['user_id'];
          $data['username']=$rows['username'];
          $data['profileimage']=(!empty($rows['profileimage']))?$rows['profileimage']:'assets/img/user.png';
          $data['first_name']=ucfirst($rows['first_name']);
          $data['last_name']=ucfirst($rows['last_name']);
          $data['specialization_img']=$rows['specialization_img'];
          $data['speciality']=ucfirst($rows['speciality']);
          $data['cityname']=$rows['cityname'];
          $data['countryname']=$rows['countryname'];
          $data['services']=$rows['services'];
          $data['rating_value']=$rows['rating_value'];
          $data['rating_count']=$rows['rating_count'];
          $data['currency']='$';
          $data['is_favourite']=$this->api->is_favourite($rows['user_id'],$this->user_id);
          $data['price_type']=($rows['price_type']=='Custom Price')?'Paid':'Free';
          $data['slot_type']='per slot';
          $data['amount']=($rows['price_type']=='Custom Price')?$rows['amount']:'0';
       }
       if(!empty($data))
       {
          return $data;
       }
       else
       {
        return new stdClass();
       }


      

      
         
    }


    public function patient_details($user_id)
    {
        $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname');
        $this->db->from('users u');
        $this->db->join('users_details ud','ud.user_id = u.id','left');
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left');
        $this->db->where('u.role','2');
        $this->db->where('u.id',$user_id);
       $rows= $this->db->get()->row_array();

       $data=array();
       if(!empty($rows))
       {
          $data['id']=$rows['id'];
          $data['patient_id']=$rows['user_id'];
          $data['username']=$rows['username'];
          $data['profileimage']=(!empty($rows['profileimage']))?base_url().$rows['profileimage']:base_url().'assets/img/user.png';
          $data['first_name']=ucfirst($rows['first_name']);
          $data['last_name']=ucfirst($rows['last_name']);
          $data['mobileno']=$rows['mobileno'];
          $data['dob']=!empty($rows['dob'])?$rows['dob']:'';
          $data['age']=age_calculate($rows['dob']);
          $data['blood_group']=!empty($rows['blood_group'])?$rows['blood_group']:'';
          $data['gender']=!empty($rows['gender'])?$rows['gender']:'';
          $data['cityname']=!empty($rows['cityname'])?$rows['cityname']:'';
          $data['countryname']=!empty($rows['countryname'])?$rows['countryname']:'';
       }

      if(!empty($data))
       {
          return $data;
       }
       else
       {
        return new stdClass();
       }

        
     
    }


    public function coupon_list($page,$limit,$type)
  {
       $this->db->select('*,sp.specialization as speciality,sp.specialization_img');
       $this->db->from('coupon'); 
       $this->db->join('specialization sp','coupon.coupon_specialization = sp.id','left');
       $this->db->where('coupon_status',1);
       $this->db->where('coupon_start_date <=',date('Y-m-d'));
       $this->db->where('coupon_expire_date >=',date('Y-m-d'));

        if($type == 1){
         return $this->db->count_all_results(); 
        }else{

          $page = !empty($page)?$page:'';
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  intval(($page * $limit));  
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
        }
        
  }

  public function coupon_dropdown()
  {
       $this->db->select('*,sp.specialization as speciality,sp.specialization_img');
       $this->db->from('coupon'); 
       $this->db->join('specialization sp','coupon.coupon_specialization = sp.id','left');
       $this->db->where('coupon_status',1);
       $this->db->where('coupon_start_date <=',date('Y-m-d'));
       $this->db->where('coupon_expire_date >=',date('Y-m-d'));

       
        return $this->db->get()->result_array();
        
        
  }

  public function get_user_details($id)
   {
       $this->db->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=u.id) as rating_value');
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

  public function search_pharmacy($page,$limit,$type) {
      
    $this->db->select('p.id as pharmacy_id, p.first_name,p.last_name,p.pharmacy_name,p.profileimage, p.mobileno');
    $this->db->select('pd.address1,pd.address2,c.country, c.phonecode,s.statename, ci.city, pd.postal_code');
    $this->db->select('ps.home_delivery, ps.24hrsopen, ps.pharamcy_opens_at');
    $this->db->from('users p');
    $this->db->join('users_details pd', 'p.id = pd.user_id', 'left'); 
    $this->db->join('pharmacy_specifications ps', 'p.id = ps.pharmacy_id', 'left'); 
    $this->db->join('state s', 's.id = pd.state', 'left'); 
    $this->db->join('city ci', 'ci.id = pd.city', 'left'); 
    $this->db->join('country c', 'c.countryid = pd.country', 'left'); 
    $this->db->where('p.role', 5);
    $this->db->where('p.status',1);
        
    if(!empty($_POST['city'])){
      $this->db->where('pd.city',$_POST['city']);
    }
    if(!empty($_POST['state'])){
     $this->db->where('pd.state',$_POST['state']);
    }
    if(!empty($_POST['country'])){
      $this->db->where('pd.country',$_POST['country']);
    }
    if($_POST['order_by'] == 'Latest'){
     $this->db->order_by('p.id','DESC');
    }

    if($type == 1){
      return $this->db->count_all_results(); 
    }else{

      $page = !empty($page)?$page:'';
      if($page>=1){
      $page = $page - 1 ;
      }
      $page =  ($page * $limit);  
      $this->db->limit($limit,$page);
      return $this->db->get()->result_array();
    }
  }

  public function search_pharmacy_new($page,$limit,$type,$userdata) {

    $this->db->select('p.id as pharmacy_id, p.first_name,p.last_name,p.pharmacy_name,p.profileimage, p.mobileno');
    $this->db->select('pd.address1,pd.address2,c.country, c.phonecode,s.statename, ci.city, pd.postal_code');
    $this->db->select('ps.home_delivery, ps.24hrsopen, ps.pharamcy_opens_at');
    $this->db->from('users p');
    $this->db->join('users_details pd', 'p.id = pd.user_id', 'left'); 
    $this->db->join('pharmacy_specifications ps', 'p.id = ps.pharmacy_id', 'left'); 
    $this->db->join('state s', 's.id = pd.state', 'left'); 
    $this->db->join('city ci', 'ci.id = pd.city', 'left'); 
    $this->db->join('country c', 'c.countryid = pd.country', 'left'); 
    $this->db->where('p.role', 5);
    $this->db->where('p.status',1);
    
    if(!empty($userdata['city'])){
      $this->db->where('pd.city',$userdata['city']);
    }
    if(!empty($userdata['state'])){
     $this->db->where('pd.state',$userdata['state']);
    }
    if(!empty($userdata['country'])){
      $this->db->where('pd.country',$userdata['country']);
    }
    if($userdata['order_by'] == 'Latest'){
     $this->db->order_by('p.id','DESC');
    }

    if($type == 1){
      return $this->db->count_all_results(); 
    }else{
      $page = !empty($page)?$page:'';
      if($page>=1){
      $page = $page - 1 ;
      }
      $page =  ($page * $limit);  
      $this->db->limit($limit,$page);
      return $this->db->get()->result_array();
    }
  }

  public function get_selected_pharmacy_details($pharmacy_id = NULL){
    $this->db->select('p.id as pharmacy_id, p.first_name,p.last_name,p.pharmacy_name,p.profileimage, p.mobileno');
    $this->db->select('pd.address1,pd.address2,c.country, c.phonecode,s.statename, ci.city, pd.postal_code');
    $this->db->select('ps.home_delivery, ps.24hrsopen,ps.24hrsopen as hrsopen, ps.pharamcy_opens_at');
    $this->db->from('users p');
    $this->db->join('users_details pd', 'p.id = pd.user_id', 'left'); 
    $this->db->join('pharmacy_specifications ps', 'p.id = ps.pharmacy_id', 'left'); 
    $this->db->join('state s', 's.id = pd.state', 'left'); 
    $this->db->join('city ci', 'ci.id = pd.city', 'left'); 
    $this->db->join('country c', 'c.countryid = pd.country', 'left'); 
    $this->db->where_in('p.id',$pharmacy_id);
    $this->db->where('p.role',5);
    $this->db->where('p.status',1);
    return $this->db->get()->result_array();
  }

  public function get_pharmacy_category(){
    $this->db->select('*');
    $this->db->from('product_categories');
    $this->db->where('status',1);
    return $this->db->get()->result_array();
  }

  public function get_pharmacy_subcategory($cat_id){
    $this->db->select('*');
    $this->db->from('product_subcategories');
    $this->db->where('status',1);
    $this->db->where('category',$cat_id);
    return $this->db->get()->result_array();
  }

  public function get_pharmacy_product($sub_cat_id,$pharmacy_id){
    $this->db->select('*');
    $this->db->from('products');
    $this->db->where('status',1);
    $this->db->where('subcategory',$sub_cat_id);
    $this->db->where('user_id',$pharmacy_id);
    return $this->db->get()->result_array();
  }

  public function user_order_list($user_id){          
    $this->db->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty');  
    $this->db->from('order_user_details as od');
    $this->db->join('orders as o','o.user_order_id = od.order_user_details_id','left');
    $this->db->join('users as us','us.id = od.pharmacy_id','left');
    $this->db->where('od.user_id',$user_id);
    $this->db->group_by('od.order_user_details_id');          
    $this->db->order_by('od.order_user_details_id', 'DESC');
    return $this->db->get()->result_array();          
  }
  
  public function user_order_list_upcoming($user_id){          
    $date = date('Y:m:d H:i:s');
    $this->db->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty'); 
    $this->db->from('order_user_details as od');
    $this->db->join('orders as o','o.user_order_id = od.order_user_details_id','left');
    $this->db->join('users as us','us.id = od.pharmacy_id','left');
    $this->db->where('od.user_id',$user_id);
    $this->db->where('od.created_at >',$date);    
    $this->db->group_by('od.order_user_details_id');          
    return $this->db->get()->result_array();          
  }
  
  public function user_order_list_today($user_id){          
    $date = date('Y:m:d');
    $this->db->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty');  
    $this->db->from('order_user_details as od');
    $this->db->join('orders as o','o.user_order_id = od.order_user_details_id','left');
    $this->db->join('users as us','us.id = od.pharmacy_id','left');
    $this->db->where('od.user_id',$user_id);
    $this->db->where('date(od.created_at)',$date);    
    $this->db->group_by('od.order_user_details_id');          
    return $this->db->get()->result_array();          
  }
  

  public function pharmacy_order_list_upcoming($pharmacy_id){          
    $date = date('Y:m:d H:i:s');
    $this->db->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty');  
    $this->db->from('order_user_details as od');
    $this->db->join('orders as o','o.user_order_id = od.order_user_details_id','left');
    $this->db->join('users as us','us.id = od.pharmacy_id','left');
     $this->db->where('od.pharmacy_id',$pharmacy_id);
    $this->db->where('od.created_at >',$date);    
    $this->db->group_by('od.order_user_details_id');          
    return $this->db->get()->result_array();          
  }


  public function pharmacy_order_list_today($pharmacy_id){          
    $date = date('Y:m:d');
    $this->db->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty');  
    $this->db->from('order_user_details as od');
    $this->db->join('orders as o','o.user_order_id = od.order_user_details_id','left');
    $this->db->join('users as us','us.id = od.pharmacy_id','left');
     $this->db->where('od.pharmacy_id',$pharmacy_id);
    $this->db->where('date(od.created_at)',$date);  
    $this->db->group_by('od.order_user_details_id');          
    return $this->db->get()->result_array();          
  }


  public function user_order_list_based_orderID($order_user_details_id){        
    // $this->db->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty');
    $this->db->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name, us.mobileno as user_mobileno, SUM(o.quantity) as qty, oc.country as phar_countryname, os.statename as phar_statename, oci.city as phar_cityname, ud.address1 as user_address1, ud.address2 as user_address2, c.country as user_countryname, s.statename as user_statename, ci.city as user_cityname, ud.postal_code as user_postal_code');

    $this->db->from('order_user_details as od');
    $this->db->join('orders as o','o.user_order_id = od.order_user_details_id','left');
    $this->db->join('country oc','od.country = oc.countryid','left');
	  $this->db->join('state os','od.state = os.id','left');
    $this->db->join('city oci','od.city = oci.id','left');
    $this->db->join('users as us','us.id = od.pharmacy_id','left');
    $this->db->join('users_details as ud','us.id = ud.user_id','left');
	  $this->db->join('country c','ud.country = c.countryid','left');
    $this->db->join('state s','ud.state = s.id','left');
    $this->db->join('city ci','ud.city = ci.id','left');
    $this->db->where('od.order_user_details_id',$order_user_details_id);
    $this->db->group_by('od.order_user_details_id');          
    return $this->db->get()->result_array();        
  }


  public function pharmacy_order_list($pharmacy_id){    
    $this->db->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty');  
    $this->db->from('order_user_details as od');
    $this->db->join('orders as o','o.user_order_id = od.order_user_details_id','left');
    $this->db->join('users as us','us.id = od.pharmacy_id','left');
    $this->db->where('od.pharmacy_id',$pharmacy_id); 
    $this->db->group_by('od.order_user_details_id');             
    $this->db->order_by('od.order_user_details_id', 'DESC');
    return $this->db->get()->result_array();

  }

  public function order_details($order_id){
    $this->db->select('p.*,o.*');
    $this->db->from('order_user_details as od');
    $this->db->join('orders as o','o.user_order_id = od.order_user_details_id','left');
    $this->db->join('products as p','p.id = o.product_id','left');  
    $this->db->where('od.order_user_details_id',$order_id);
    return $this->db->get()->result_array();
  } 
  
  
  public function get_pharmacy_productlist($pharmacy_id){
		
		
	$this->db->select('p.*,c.category_name,s.subcategory_name,u.unit_name');
	$this->db->from('products as p'); 
	$this->db->join('product_categories as c','p.category = c.id','left');
	$this->db->join('product_subcategories as s','p.subcategory = s.id','left');
	$this->db->join('unit as u','p.unit = u.id','left'); 
	$this->db->where("(p.status = '1' OR p.status = '2')");
	$this->db->where("p.user_id",$pharmacy_id);
	$this->db->order_by('p.id', 'DESC');
    return $this->db->get()->result_array();
  }
  
  public function get_pharmacy_unit(){
    $this->db->select('*');
    $this->db->from('unit');
    $this->db->where('status',1);
    return $this->db->get()->result_array();
  }
  
  public function get_pharmacy_profile_details($id)
	{

       $profileimage='assets/img/user.png';
       $this->db->select('u.id as userid,u.insurance_company_name as insurance_name,u.insurance_number as policy_number,u.pharmacy_name, u.first_name,u.last_name,u.email,u.username,u.mobileno,IF(u.profileimage IS NULL or u.profileimage = "", "'.$profileimage.'", u.profileimage) as profileimage, ps.home_delivery, ps.24hrsopen, ps.pharamcy_opens_at,
        IF(ud.user_id IS NULL,"",ud.user_id) as user_id,
        IF(ud.gender IS NULL,"",ud.gender) as gender,
        IF(ud.dob IS NULL,"",ud.dob) as dob,
        IF(ud.blood_group IS NULL,"",ud.blood_group) as blood_group,
        IF(ud.address1 IS NULL,"",ud.address1) as address1,
        IF(ud.address2 IS NULL,"",ud.address2) as address2,
        IF(ud.postal_code IS NULL,"",ud.postal_code) as postal_code,
        IF(ud.country IS NULL,"",ud.country) as country,
        IF(ud.state IS NULL,"",ud.state) as state,
        IF(ud.city IS NULL,"",ud.city) as city,
        IF(c.country IS NULL,"",c.country) as countryname,
        IF(s.statename IS NULL,"",s.statename) as statename,
        IF(ci.city IS NULL,"",ci.city) as cityname');
        $this->db->from('users u');
        $this->db->join('users_details ud','ud.user_id = u.id','left');
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left');
		$this->db->join('pharmacy_specifications ps','ps.pharmacy_id = u.id','left');
		$this->db->where('u.role', 5);
        $this->db->where('u.id',$id);
        return $result = $this->db->get()->row_array();
        
	}
	
	public function lab_info($lab_id)
    {

      $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname');
        $this->db->from('lab_payments lp');
        $this->db->join('users u','lp.lab_id = u.id','left');
        $this->db->join('users_details ud','ud.user_id = u.id','left');
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left');
        $this->db->where('u.role','4');
        $this->db->where('u.id',$lab_id);
        $this->db->group_by('lp.lab_id');
      
        // $this->db->select('u.id as lab_id,u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value,ud.currency_code as currency_code');
        //   $this->db->from('users u');
        //   $this->db->join('users_details ud','ud.user_id = u.id','left');
        //   $this->db->join('country c','ud.country = c.countryid','left');
        //   $this->db->join('state s','ud.state = s.id','left');
        //   $this->db->join('city ci','ud.city = ci.id','left');
        //   $this->db->join('specialization sp','ud.specialization = sp.id','left');
        //   $this->db->where('u.id',$lab_id);
          return $this->db->get()->result_array();
    }
  
  public function get_single_pharmacy_product($id){
		$this->db->select('p.*,c.category_name,s.subcategory_name,u.unit_name');
		$this->db->from('products as p'); 
		$this->db->join('product_categories as c','p.category = c.id','left');
		$this->db->join('product_subcategories as s','p.subcategory = s.id','left');
		$this->db->join('unit as u','p.unit = u.id','left'); 
		$this->db->where("(p.status = '1' OR p.status = '2')");
		$this->db->where("p.id",$id);
		$query = $this->db->get();
		return $query->row_array();
	}
	
	
	Public function get_total_test($user_id){
    $where =array('lab_id' => $user_id);
    return $this->db->group_by('lab_id')->get_where('lab_tests',$where)->num_rows();
  }  
  Public function get_today_labpatient($user_id){
    $where =array('lab_id' => $user_id,'lab_test_date'=>date('Y-m-d'));
    return $this->db->group_by('lab_id')->get_where('lab_payments',$where)->num_rows();
  }
    Public function get_recent_labbooking($user_id){
    $where =array('lab_id' => $user_id);
    return $this->db->get_where('lab_payments',$where)->num_rows();
  }

Public function get_upcoming_labbookingnew($user_id){
    $where =array('lab_id' => $user_id,'lab_test_date >'=>date('Y-m-d'));
    return $this->db->get_where('lab_payments',$where)->num_rows();
  }
  
  Public function get_today_patient_labpatient($user_id){
    $where =array('patient_id' => $user_id,'lab_test_date'=>date('Y-m-d'));
    return $this->db->get_where('lab_payments',$where)->num_rows();
  }
    Public function get_recent_patient_labbooking($user_id){
    $where =array('patient_id' => $user_id);
    return $this->db->get_where('lab_payments',$where)->num_rows();
  }
  
   Public function get_upcoming_patient_labbooking($user_id){
    $where =array('patient_id' => $user_id,'lab_test_date >'=>date('Y-m-d'));
    return $this->db->get_where('lab_payments',$where)->num_rows();
  }
  
  Public function get_upcoming_labbooking($user_id){
    $where =array('lab_id' => $user_id,'lab_test_date >'=>date('Y-m-d'));
    return $this->db->group_by('lab_id')->get_where('lab_payments',$where)->num_rows();
  }
  
      public function check_otp($inputs = '') {
        $mobile = $inputs['mobileno'];
        $otp    = $inputs['otp'];

        $this->db->where('mobileno', $mobile);
        $this->db->where('otpno', $otp);
        $this->db->where('status=0');
        $this->db->order_by('otp_history.id', 'DESC');
        $this->db->limit(1);


        return $this->db->count_all_results('otp_history');
    }
	
	public function is_valid_loginwithotp($device_id,$device_type,$mobile)
    {

      $profileimage='assets/img/user.png';
      $this->db->select('id,email,first_name,last_name,username,mobileno,role,token,status,IF(profileimage IS NULL or profileimage = "", "'.$profileimage.'", profileimage) as profileimage');
      $this->db->from('users');
      $this->db->where("mobileno",$mobile);
      $result = $this->db->get()->row_array();
       
      if(!empty($result))
      {
        $user_id = $result['id'];
            $token = $this->getToken(14,$user_id);
            $result['token'] = $token;
            $this->db->where('id', $user_id);
            $this->db->update('users', array('token' => $token,'device_id'=>$device_id,'device_type'=>$device_type));
      }

      return $result;
    }
  
public function labtest_insert($inputdata)
  {
       $this->db->insert('lab_tests',$inputdata);
       return ($this->db->affected_rows()!= 1)? false:true;
  }
  
  public function lab_test_lists($pages='',$limits='',$type=1,$lab_id='')
    {
        $this->db->select('*');
        $this->db->from('lab_tests');
        $this->db->where('lab_id',$lab_id);
        if($type == 1){
                return $this->db->count_all_results(); 
          }else{
          $page = !empty($pages)?$pages:'';
          $limit = $limits;
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->order_by('id', 'DESC');
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
          }
    }
	
	public function mylabs_lists($pages='',$limits='',$type=1,$user_id='')
    {
        $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname');
        $this->db->from('lab_payments lp');
        $this->db->join('users u','lp.lab_id = u.id','left');
        $this->db->join('users_details ud','ud.user_id = u.id','left');
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left');
        $this->db->where('u.role','4');
        $this->db->where('lp.patient_id',$user_id);
        $this->db->group_by('lp.lab_id');

        
      if($type == 1){
                return $this->db->count_all_results(); 
          }else{
          $page = !empty($pages)?$pages:'';
          $limit = $limits;
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
          }
    }
	
	public function lab_list()
    {
        $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value');
          $this->db->from('users u');
          $this->db->join('users_details ud','ud.user_id = u.id','left');
          $this->db->join('country c','ud.country = c.countryid','left');
          $this->db->join('state s','ud.state = s.id','left');
          $this->db->join('city ci','ud.city = ci.id','left');
          $this->db->join('specialization sp','ud.specialization = sp.id','left');
          $this->db->where('u.role','4');
          $this->db->where('u.status','1');
          $this->db->where('u.is_verified','1');
          $this->db->where('u.is_updated','1');
      $this->db->group_by('ud.id');
      $this->db->order_by('rand()');
      $this->db->limit(5);
          return $this->db->get()->result_array();
    }
	
	public function lab_lists($pages='',$limits='',$type=1,$user_data=array())
    {
        $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value');
          $this->db->from('users u');
          $this->db->join('users_details ud','ud.user_id = u.id','left');
          $this->db->join('country c','ud.country = c.countryid','left');
          $this->db->join('state s','ud.state = s.id','left');
          $this->db->join('city ci','ud.city = ci.id','left');
          $this->db->join('specialization sp','ud.specialization = sp.id','left');
          $this->db->where('u.role','4');
          $this->db->where('u.status','1');
          $this->db->where('u.is_verified','1');
          $this->db->where('u.is_updated','1');


          if(!empty($user_data['city'])){
         $this->db->where("(ud.state = '".$user_data['city']."' OR ud.city = '".$user_data['city']."')");
          }
         if(!empty($user_data['state'])){
         $this->db->where("(ud.state = '".$user_data['state']."' OR ud.city = '".$user_data['state']."')");
           }
         if(!empty($user_data['country'])){
          $this->db->where('ud.country',$user_data['country']);
          }

          if(!empty($user_data['keywords'])) {  
          $this->db->group_start();
          $this->db->like('u.first_name',$user_data['keywords']);
          $this->db->or_like('u.last_name',$user_data['keywords']);
          $this->db->or_like('sp.specialization',$user_data['keywords']);
          $this->db->group_end();
        }

          $this->db->group_by('ud.id');
      if($type == 1){
                return $this->db->count_all_results(); 
          }else{
          $page = !empty($pages)?$pages:'';
          $limit = $limits;
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->order_by('u.id', 'DESC');
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
          }
    }
	
	public function lab_appointments_lists($pages='',$limits='',$type=1,$user_data=array(),$user_id='',$role='')
    {


        $current_date = date('Y-m-d');
        $from_date_time = date('Y-m-d H:i:s');
        $this->db->select('lp.*,u.first_name,u.last_name,u.username,u.profileimage,u.role');
        $this->db->from('lab_payments lp');
         
        if($role==4)
        {
          $this->db->join('users u', 'u.id = lp.patient_id', 'left');
          $this->db->where('lp.lab_id',$user_id);
        }
        if($role==2)
        {
          $this->db->join('users u', 'u.id = lp.lab_id', 'left');
          $this->db->where('lp.patient_id',$user_id);
        }

      
       
       if(!empty($user_data['type']))
       {
          if($user_data['type'] == 1){
          $this->db->where('lp.lab_test_date',$current_date);
          }

          if($user_data['type'] == 2){
          $this->db->where('lp.lab_test_date > ',$from_date_time);
         }
       } 
    
      if($type == 1){
                return $this->db->count_all_results(); 
          }else{
          $page = !empty($pages)?$pages:'';
          $limit = $limits;
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->order_by('lp.lab_test_date', 'DESC');
          $this->db->limit($limit,$page);
          return $this->db->get()->result_array();
          }
    }
	
	Public function get_total_test_new($user_id){
    $where =array('lab_id' => $user_id);
    return $this->db->get_where('lab_tests',$where)->num_rows();
  } 
  
  public function get_doctor_acclist($user_id='',$pages='',$limits='',$type=1){
		$this->db->select('p.*,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.id as patient_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
		$this->db->from('payments p');
		$this->db->join('users pi', 'pi.id = p.user_id', 'left'); 
		$this->db->join('users_details pd','pd.user_id = pi.id','left');
		$this->db->where('p.doctor_id',$user_id);
		$this->db->where('p.payment_status',1);
		$this->db->order_by('p.id','desc');
		if($type == 1){
			return $this->db->count_all_results(); 
		}else{
			$page = !empty($pages)?$pages:'';
			$limit = $limits;
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);  
			
			$this->db->limit($limit,$page); 
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result_array();
		}
	}
  
  
  public function get_doctor_reflist($user_id,$pages,$limits,$type=1){
		
		// $this->db->select('p.*,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.id as patient_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
		$this->db->select('p.*,CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage,d.id as doctor_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count,d.role');
		$this->db->from('payments p');
		$this->db->join('users d', 'd.id = p.doctor_id', 'left'); 
		$this->db->join('users_details dd','dd.user_id = d.id','left');
		$this->db->where('p.user_id',$user_id);
		$this->db->where('p.payment_status',1);
		$this->db->where('p.request_status',1);
		$this->db->order_by('p.id','desc');
		if($type == 1){
			return $this->db->count_all_results(); 
		}else{
			$page = !empty($pages)?$pages:'';
			$limit = $limits;
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);  
			
			$this->db->limit($limit,$page); 
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result_array();
		}	
	}
	
  public function get_patient_reflist($user_id,$pages,$limits,$type=1){
		$this->db->select('p.*,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.id as patient_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
		$this->db->from('payments p');
		$this->db->join('users pi', 'pi.id = p.user_id', 'left'); 
		$this->db->join('users_details pd','pd.user_id = pi.id','left');
		$this->db->where('p.doctor_id',$user_id);
		$this->db->where('p.payment_status',1);
		$this->db->where('p.request_status',6);
		$this->db->order_by('p.id','desc');
		if($type == 1){
			return $this->db->count_all_results(); 
		}else{
			$page = !empty($pages)?$pages:'';
			$limit = $limits;
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);  
			
			$this->db->limit($limit,$page); 
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result_array();
		}	
	}
	
	public function productdetails($id){
      $this->db->select('p.*');
      $this->db->from('products p');     
      $this->db->where('p.status !=',0);
      $this->db->where('p.id',$id);
      return $this->db->get()->row_array();
	}
	
	public function pharmacy_invoice_details($id)
  {
	$this->db->select('p.*, CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage,d.id as doctor_id,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.id as patient_id, dc.country as phar_countryname,ds.statename as phar_statename,dci.city as phar_cityname,pc.country as user_countryname,ps.statename as user_statename,pci.city as user_cityname,dd.address1 as address1,dd.address2 as address2,pd.address1 as user_address1,pd.address2 as user_address2,d.role');
    $this->db->from('payments p');
    $this->db->join('users d', 'd.id = p.doctor_id', 'left'); 
    $this->db->join('users_details dd','dd.user_id = d.id','left'); 
    $this->db->join('users pi', 'pi.id = p.user_id', 'left'); 
    $this->db->join('users_details pd','pd.user_id = pi.id','left');
	
	$this->db->join('country dc','dd.country = dc.countryid','left');
	$this->db->join('state ds','dd.state = ds.id','left');
	$this->db->join('city dci','dd.city = dci.id','left');
	$this->db->join('country pc','pd.country = pc.countryid','left');
	$this->db->join('state ps','pd.state = ps.id','left');
	$this->db->join('city pci','pd.city = pci.id','left');
	
	$this->db->where('p.id',$id);
    $this->db->where('p.payment_status',1);
	$query = $this->db->get();
    return $query->result_array();
  }
  
  public function read_invoice_list($user_id,$role,$pages,$limits,$type=1){
		$this->db->select('p.*, CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage,d.id as doctor_id,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.id as patient_id,d.role');
		$this->db->from('payments p');
		$this->db->join('users d', 'd.id = p.doctor_id', 'left'); 
		$this->db->join('users_details dd','dd.user_id = d.id','left'); 
		$this->db->join('users pi', 'pi.id = p.user_id', 'left'); 
		$this->db->join('users_details pd','pd.user_id = pi.id','left');
		
		if($role=='1' || $role=='4' || $role=='5' || $role=='6')
		{
		  $this->db->where('p.doctor_id',$user_id);
		} 
		if($role=='2')
		{
		  $this->db->where('p.user_id',$user_id);
		}
		$this->db->where('p.payment_status',1);
		$this->db->order_by('p.id','desc');
		if($type == 1){
			return $this->db->count_all_results(); 
		}else{
			$page = !empty($pages)?$pages:'';
			$limit = $limits;
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);  
			
			$this->db->limit($limit,$page); 
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result_array();
		}
	}
  
  public function get_patient_acclist($user_id,$pages,$limits,$type=1){
		
		$this->db->select('p.*,CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage,d.id as doctor_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count,d.role');
		$this->db->from('payments p');
		$this->db->join('users d', 'd.id = p.doctor_id', 'left'); 
		$this->db->join('users_details dd','dd.user_id = d.id','left');
		$this->db->where('p.user_id',$user_id);
		$this->db->where('p.payment_status',1);
		$this->db->order_by('p.id','desc');
		
		if($type == 1){
			return $this->db->count_all_results(); 
		}else{
			$page = !empty($pages)?$pages:'';
			$limit = $limits;
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);  
			
			$this->db->limit($limit,$page); 
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result_array();
		}			
	}
  
}

