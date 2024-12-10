<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
  /**
 * @property object $db
 * @property object $session
 */

class Home_model extends CI_Model {

  public function __construct() {
    parent::__construct();
  }

  public function get_doctors() {
    $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=u.id) as rating_value');
    $this->db->from('users u');
    $this->db->join('users_details ud', 'ud.user_id = u.id', 'left');
    $this->db->join('country c', 'ud.country = c.countryid', 'left');
    $this->db->join('state s', 'ud.state = s.id', 'left');
    $this->db->join('city ci', 'ud.city = ci.id', 'left');
    $this->db->join('specialization sp', 'ud.specialization = sp.id', 'left');
    $this->db->where('u.role', '1');
    $this->db->where('u.status', '1');
    $this->db->where('u.is_verified', '1');
    $this->db->where('u.is_updated', '1');
    $this->db->order_by('u.id', 'DESC');
    return $result = $this->db->get()->result_array();
  }

  public function get_blogs() {
    $this->db->select('p.*,IF(p.post_by="Admin",a.profileimage, d.profileimage) as profileimage,IF(p.post_by="Admin","Admin", CONCAT(d.first_name," ", d.last_name)) as name,d.username,c.category_name,s.subcategory_name');
    $this->db->from('posts p');
    $this->db->join('users d', 'p.user_id = d.id', 'left');
    $this->db->join('categories c', 'p.category = c.id', 'left');
    $this->db->join('subcategories s', 'p.subcategory = s.id', 'left');
    $this->db->join('tags t', 'p.id = t.post_id', 'left');
    $this->db->join('administrators a', '1 = a.id', 'left');
    $this->db->where('p.status', '1');
    $this->db->where('p.is_verified', '1');
    $this->db->where('p.is_viewed', '1');
    $this->db->order_by('rand()');
    $this->db->group_by('p.id');
    $this->db->limit('4');
    return $this->db->get()->result_array();
  }

  public function get_doctor_details($username) {
    $this->db->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=u.id) as rating_value,u.role');
    $this->db->from('users u');
    $this->db->join('users_details ud', 'ud.user_id = u.id', 'left');
    $this->db->join('country c', 'ud.country = c.countryid', 'left');
    $this->db->join('state s', 'ud.state = s.id', 'left');
    $this->db->join('city ci', 'ud.city = ci.id', 'left');
    $this->db->join('specialization sp', 'ud.specialization = sp.id', 'left');
    $this->db->where('u.role != 2');
    $this->db->where("(u.status = '1' OR u.status = '2')");
    
    if(empty($this->session->userdata('admin_id')))
    {
      $this->db->where('u.is_verified', '1');
      $this->db->where('u.is_updated', '1');
    }
    $this->db->where('u.username', $username);
    return $result = $this->db->get()->row_array();
  }
   public function get_clinic_details($username) {
    $this->db->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,clinicimage.clinic_image,sp.specialization as speciality,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=u.id) as rating_value, sp.specialization_img');
    $this->db->from('users u');
    $this->db->join('users_details ud', 'ud.user_id = u.id', 'left');
    $this->db->join('country c', 'ud.country = c.countryid', 'left');
    $this->db->join('state s', 'ud.state = s.id', 'left');
    $this->db->join('city ci', 'ud.city = ci.id', 'left');
     $this->db->join('clinic_images clinicimage', 'u.id = clinicimage.user_id', 'left');
      $this->db->join('specialization sp', 'ud.specialization = sp.id', 'left');
    $this->db->where('u.role', '6');
    $this->db->where('u.username', $username);
    return $result = $this->db->get()->row_array();
  }

public function search_package($page,$limit,$type){
  $this->db->select('*');
  $this->db->from('packages');
  
  if ($type == 1) {
    return $this->db->count_all_results();
} else {

    $page = !empty($page) ? $page : '';
    if ($page >= 1) {
  $page = $page - 1;
    }
    $page = ($page * $limit);
    $this->db->limit($limit, $page);
    return $this->db->get()->result_array();
}

}
public function search_service($page,$limit,$type){
  $this->db->select('*');
  $this->db->from('services');
  
  if ($type == 1) {
    return $this->db->count_all_results();
} else {

    $page = !empty($page) ? $page : '';
    if ($page >= 1) {
  $page = $page - 1;
    }
    $page = ($page * $limit);
    $this->db->limit($limit, $page);
    return $this->db->get()->result_array();
}

}

  public function search_doctor($page, $limit, $type) {
      

    $this->db->select('u.id, 
    u.first_name, 
    u.last_name, 
    u.email, 
    u.username, 
    u.mobileno, 
    u.profileimage, 
    ud.id AS ud_id,
    ud.user_id,
    ud.address1,
    ud.country,
    ud.state,
    ud.city,
    ud.specialization,
    ud.services,
    ud.amount,
    ud.currency_code,
    ud.price_type,
    ud.register_no,
    c.country AS countryname,
    s.statename,
    ci.city AS cityname,
    sp.specialization AS speciality,
    sp.specialization_img,
    (SELECT COUNT(rating) FROM rating_reviews WHERE doctor_id = u.id) AS rating_count,
    (SELECT ROUND(AVG(rating)) FROM rating_reviews WHERE doctor_id = u.id) AS rating_value,
    u.role');
    $this->db->from('users u');
    $this->db->join('users_details ud', 'ud.user_id = u.id', 'left');
    $this->db->join('country c', 'ud.country = c.countryid', 'left');
    $this->db->join('state s', 'ud.state = s.id', 'left');
    $this->db->join('city ci', 'ud.city = ci.id', 'left');
    $this->db->join('specialization sp', 'ud.specialization = sp.id', 'left');
    //Shenbagam
    if(!empty($_POST['role'])) {
    $this->db->where('u.role', $_POST['role']);
    } else {
      if (!empty($_POST['keywords'])) {
      $this->db->where_in('u.role', [1,6]);
      } else {
      $this->db->where('u.role', 1);  
      }
    }
    $this->db->where('u.status', '1');
    $this->db->where('u.is_verified', '1');
    $this->db->where('u.is_updated', '1');
    $this->db->where("ud.id is  NOT NULL"); //Shenbagam
    $this->db->where('u.hospital_id',0);
  
   
     
     if (!empty($user_data['cities'])) {
     // $this->db->where("(s.statename = '" . $user_data['cities'] . "' OR ci.city = '" . $user_data['cities'] . "')");
      }
  
      if (!empty($_POST['city'])) {
        
        $this->db->where('ud.city',$_POST['city']);
  
      
      }
      if (!empty($_POST['state'])) {
        $this->db->where('ud.state',$_POST['state']);
    //  $this->db->where("(ud.state = '" . $_POST['state'] . "' OR ud.city = '" . $_POST['state'] . "')");
      }
      if (!empty($_POST['country'])) {
      $this->db->where('ud.country', $_POST['country']);
      }
    

      
        if($_POST['role']==6){
          if(!empty($_POST['get_id']) && empty($_POST['cities']) && empty($_POST['city']) && empty($_POST['state']) && empty($_POST['country']) && empty($_POST['postal_code']) && empty($_POST['s_city']) && empty($_POST['s_state']) && empty($_POST['s_country']) && empty($_POST['specialization']) && empty($_POST['keywords']) && empty($_POST['citys']) && empty($_POST['gender'])) {
            $this->db->where('u.id', $_POST['get_id']);
          }
          // else{
          //   $this->db->or_where('u.id', $_POST['get_id']);
          // }
        }
      
  
  
    // if ((empty($_POST['s_unit']) || empty($_POST['s_radius'])) && !empty($_POST['s_location'])) {
  
    //     if (!empty($_POST['postal_code'])) {
    //   $this->db->where("(ud.postal_code = '" . $_POST['postal_code'] . "')");
    //     }
    // } if (!empty($_POST['s_city'])) {
    //     $this->db->where("(ud.state = '" . $_POST['city'] . "' OR ud.city = '" . $_POST['city'] . "')");
    // }
    // if (!empty($_POST['state'])) {
    //     $this->db->where("(ud.state = '" . $_POST['state'] . "' OR ud.city = '" . $_POST['state'] . "')");
    // }
    // if (!empty($_POST['country'])) {
    //     $this->db->where('ud.country', $_POST['country']);
    // }
  
        if (!empty($_POST['postal_code'])) {
      $this->db->where("(ud.postal_code = '" . $_POST['postal_code'] . "')");
        }
       
  
   if (!empty($_POST['s_city'])) {
      
      $cityName = $_POST['s_city'];
  
        //$this->db->like("(s.statename = '" . $_POST['city'] . "' OR ci.city = '" . $_POST['city'] . "')");
        //$this->db->like('ci.city',$_POST['city']);
        $this->db->like('ci.city',$cityName);
  
    }
    if (!empty($_POST['s_state'])) {
        $this->db->like("(s.statename = '" . $_POST['state'] . "' OR ci.city = '" . $_POST['state'] . "')");
    }
    if (!empty($_POST['s_country'])) {
        $this->db->like('c.country', $_POST['country']);
    }
  
  
    // if (!empty($_POST['specialization'])) {
    //     $spec_array = explode(",", $_POST['specialization']);
    //     foreach ($spec_array as $key => $value) {
    //   if ($key == 0) {
    //       $this->db->where('ud.specialization', $value);
    //   } else {
    //     if($value !=''){
    //       $this->db->or_where('ud.specialization', $value);
    //     }
    //   }
    //     }
    // }
  
    if (!empty($_POST['specialization'])) {
        $spec_array = explode(",", $_POST['specialization']);
        $spec_array = array_filter($spec_array);
  
  
      //   foreach ($spec_array as $key => $value) {
      // if ($key == 0) {
      //     $this->db->where('ud.specialization', $value);
      // } else {
      //   if($value !=''){
      //     $this->db->or_where('ud.specialization', $value);
      //   }
      // }
      //   }
  
        $this->db->where_in('ud.specialization', $spec_array);
    }
  
    if (!empty($_POST['keywords'])) {
        $this->db->group_start();
        // $this->db->like('u.first_name', $_POST['keywords'], 'after');
        // $this->db->or_like('u.last_name', $_POST['keywords'], 'after');
        $this->db->or_like('CONCAT( u.first_name, " ", u.last_name)', $_POST['keywords'], 'after');
        $this->db->or_like('sp.specialization', $_POST['keywords'], 'after');
      $this->db->or_like('ud.clinicname', $_POST['keywords']);
      $this->db->or_like('ud.gender', $_POST['keywords']);
        $this->db->group_end();
    }
  
    if(!empty($_POST['citys'])){
          
              // $this->db->group_start();
              // $this->db->like('s.statename',$_POST['citys'],'after');
              // $this->db->or_like('ci.city',$_POST['citys'],'after');
              // $this->db->group_end();
            }
  
    if (!empty($_POST['gender'])) {
  
        $gender_array = explode(",", $_POST['gender']);
        $gender_array = array_filter($gender_array);
        $this->db->where_in('ud.gender', $gender_array);
  
    }
    if ($_POST['order_by'] == 'Free') {
        $this->db->where('ud.price_type', 'Free');
    }
    if ($_POST['order_by'] == 'Clinic') {
        $this->db->where('sub.payment_plan!=', '3');
    }
    if ($_POST['order_by'] == 'Online') {
        $this->db->where('sub.payment_plan', '3');
    }
    // $this->db->group_by('ud.id');
    $this->db->group_by(' u.id, 
    u.first_name, 
    u.last_name, 
    u.email, 
    u.username, 
    u.mobileno, 
    u.profileimage, 
    ud.id, 
    ud.user_id, 
    ud.address1, 
    ud.country, 
    ud.state, 
    ud.city, 
    ud.services,
     ud.amount,
    ud.currency_code,
    ud.price_type,
    ud.specialization, 
    ud.register_no, 
    c.country, 
    s.statename, 
    ci.city, 
    sp.specialization, 
    sp.specialization_img, 
    u.role');
  
    if ($_POST['order_by'] == 'Rating') {
        $this->db->order_by('rating_value', 'DESC');
    }
    if ($_POST['order_by'] == 'Price') {
      $this->db->where('ud.price_type !=', 'Free');
        $this->db->order_by('ud.amount', 'DESC');
    }
    // if($_POST['order_by'] == 'Popular'){
    //   $query .=" ORDER BY rating_count DESC ";
    // }
    if ($_POST['order_by'] == 'Latest') {
        $this->db->order_by('u.id', 'DESC');
    }
  
    // $qu=  $this->db->get_compiled_select();
    // echo $qu.'<br>';
    // die('query');
    if ($type == 1) {
        return $this->db->count_all_results();
    } else {
  
        $page = !empty($page) ? $page : '';
        if ($page >= 1) {
      $page = $page - 1;
        }
        $page = ($page * $limit);
        $this->db->limit($limit, $page);
        return $this->db->get()->result_array();
    }
      }

  public function search_patient($page, $limit, $type) {
    $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname');
    $this->db->from('users u');
    $this->db->join('users_details ud', 'ud.user_id = u.id', 'left');
    $this->db->join('country c', 'ud.country = c.countryid', 'left');
    $this->db->join('state s', 'ud.state = s.id', 'left');
    $this->db->join('city ci', 'ud.city = ci.id', 'left');
	
	if(!empty($this->session->userdata('role')) && $this->session->userdata('role') == 1){
		$doctor_id = $this->session->userdata('user_id');
		$this->db->join('appointments ai', 'ai.appointment_from = u.id', 'left');
		$this->db->where('ai.appointment_to', $doctor_id);
	}
	
    $this->db->where('u.role', '2');
    $this->db->where('u.status', '1');
    $this->db->where('u.is_verified', '1');
    $this->db->where('u.is_updated', '1');

    if (!empty($_POST['city'])) {
        $this->db->where('ud.city', $_POST['city']);
    }
    if (!empty($_POST['state'])) {
        $this->db->where('ud.state', $_POST['state']);
    }
    if (!empty($_POST['country'])) {
        $this->db->where('ud.country', $_POST['country']);
    }
    if (!empty($_POST['gender'])) {
        $this->db->where('ud.gender', $_POST['gender']);
    }
    if (!empty($_POST['blood_group'])) {
        $this->db->where('ud.blood_group', $_POST['blood_group']);
    }
    $this->db->group_by('ud.id, u.first_name, u.last_name, u.email, u.username, u.mobileno, u.profileimage, c.country, s.statename, ci.city');


    if ($_POST['order_by'] == 'Latest') {
        $this->db->order_by('u.id', 'DESC');
    }

    if ($type == 1) {
        return $this->db->count_all_results();
    } else {

        $page = !empty($page) ? $page : '';
        if ($page >= 1) {
      $page = $page - 1;
        }
        $page = (int)((int)$page * $limit);
        $this->db->limit($limit, $page);
        return $this->db->get()->result_array();
    }
  }

  public function get_specialization() {
    $this->db->where('status', 1);
    $this->db->order_by('id', 'DESC');
    $query = $this->db->get('specialization');
    return $query->result_array();
  }

  public function pharmacy_order_list($pharmacy_id) {

    $this->db->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty');

    $this->db->from('order_user_details as od');
    $this->db->join('orders as o', 'o.user_order_id = od.order_user_details_id', 'left');
    $this->db->join('users as us', 'us.id = od.pharmacy_id', 'left');
    $this->db->where('o.pharmacy_id', $pharmacy_id);
    $this->db->group_by('od.order_user_details_id');
    $this->db->order_by('od.order_user_details_id', 'DESC');

    return $this->db->get()->result_array();
  }

  public function pharmacy_order_list_upcoming($pharmacy_id) {

    $date = date('Y:m:d H:i:s');
    $this->db->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty');

    $this->db->from('order_user_details as od');
    $this->db->join('orders as o', 'o.user_order_id = od.order_user_details_id', 'left');
    $this->db->join('users as us', 'us.id = od.pharmacy_id', 'left');
    $this->db->where('o.pharmacy_id', $pharmacy_id);
    $this->db->where('od.created_at >', $date);

    $this->db->group_by('od.order_user_details_id');

    return $this->db->get()->result_array();
  }

  public function pharmacy_order_list_today($pharmacy_id) {

    $date = date('Y:m:d');
    $this->db->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty');

    $this->db->from('order_user_details as od');
    $this->db->join('orders as o', 'o.user_order_id = od.order_user_details_id', 'left');
    $this->db->join('users as us', 'us.id = od.pharmacy_id', 'left');
    $this->db->where('o.pharmacy_id', $pharmacy_id);
    $this->db->where('date(od.created_at)', $date);

    $this->db->group_by('od.order_user_details_id');

    return $this->db->get()->result_array();
  }

  public function review_list_view($id) {

    $where = array('r.doctor_id' => $id);
    return $this->db
          ->select('u.profileimage,u.first_name,u.last_name,d.profileimage as doctor_image,d.first_name as doctor_firstname,d.last_name as doctor_lastname,r.*,rr.id as reply_id,rr.reply as reply,rr.created_date as reply_date')
          ->join('users u ', 'u.id = r.user_id')
          ->join('users d ', 'd.id = r.doctor_id', 'left')
          ->join('review_reply rr', 'r.id = rr.review_id', 'left')
          ->get_where('rating_reviews r', $where)
          ->result_array();
  }

  public function search_specialization($search_keywords) {
    $where = array('status' => 1);

    return $this->db
          ->select('specialization')
          ->like('specialization', $search_keywords)
          ->limit(5)
          ->order_by('specialization', 'asc')
          ->get_where('specialization', $where)
          ->result_array();
  }

  public function search_location($search_location) {

    return $this->db
          ->select('city')
          ->like('city', $search_location, 'after')
          ->limit(5)
          ->order_by('city', 'asc')
          ->get('city')
          ->result_array();
  }

  public function search_doctors($search_keywords) {
    $this->db->select('u.first_name,u.last_name,u.username,u.profileimage,sp.specialization as speciality');
    $this->db->from('users u');
    $this->db->join('users_details ud', 'ud.user_id = u.id', 'left');
    $this->db->join('specialization sp', 'ud.specialization = sp.id', 'left');
    $this->db->where("(u.role = '1' OR u.role = '6')");
    $this->db->where('u.status', '1');
    $this->db->where('u.is_verified', '1');
    $this->db->where('u.is_updated', '1');
    $this->db->group_start();
    $this->db->like('u.first_name', $search_keywords, 'after');
    $this->db->or_like('u.last_name', $search_keywords, 'after');
    $this->db->or_like('CONCAT( u.first_name, " ", u.last_name)', $search_keywords, 'after');
    $this->db->or_like('sp.specialization', $search_keywords, 'after');
    $this->db->or_like('ud.services', $search_keywords, 'after');
    $this->db->group_end();
    $this->db->group_by('ud.id');
    $this->db->limit('5');
    return $this->db->get()->result_array();
  }

  public function get_lab_details($username) {
    $this->db->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname');
    $this->db->from('users u');
    $this->db->join('users_details ud','ud.user_id = u.id','left');
    $this->db->join('country c','ud.country = c.countryid','left');
    $this->db->join('state s','ud.state = s.id','left');
    $this->db->join('city ci','ud.city = ci.id','left');
    $this->db->where('u.role','4');
    $this->db->where("(u.status = '1' OR u.status = '2')");
    $this->db->where('u.is_verified','1');
    $this->db->where('u.is_updated','1');
    $this->db->where('u.username',$username);
    return $result = $this->db->get()->row_array();
  }


  public function search_lab($page,$limit,$type) {
      
    $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname');
    $this->db->from('users u');
    $this->db->join('users_details ud','ud.user_id = u.id','left');
    $this->db->join('country c','ud.country = c.countryid','left');
    $this->db->join('state s','ud.state = s.id','left');
    $this->db->join('city ci','ud.city = ci.id','left');
    $this->db->where('u.role','4');
    $this->db->where('u.status','1');
    $this->db->where('u.is_verified','1');
    $this->db->where('u.is_updated','1');
    
    if(!empty($_POST['city'])){
      $this->db->where('ud.city',$_POST['city']);
    }
    if(!empty($_POST['state'])){
     $this->db->where('ud.state',$_POST['state']);
    }
    if(!empty($_POST['country'])){
      $this->db->where('ud.country',$_POST['country']);
    }

     if(!empty($_POST['keywords'])) {  
      $this->db->group_start();
      $this->db->like('u.first_name',$_POST['keywords']);
      $this->db->or_like('u.last_name',$_POST['keywords']);
      $this->db->or_like('CONCAT(u.first_name," ", u.last_name)',$_POST['keywords']);
      $this->db->group_end();
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
  
  public function patient_review_listview($id) {

    $where = array('r.user_id' => $id);
    return $this->db
          ->select('u.profileimage,u.first_name,u.last_name,d.profileimage as doctor_image,d.first_name as doctor_firstname,d.last_name as doctor_lastname,r.*,rr.id as reply_id,rr.reply as reply,rr.created_date as reply_date')
          ->join('users u ', 'u.id = r.user_id')
          ->join('users d ', 'd.id = r.doctor_id', 'left')
          ->join('review_reply rr', 'r.id = rr.review_id', 'left')
          ->get_where('rating_reviews r', $where)
          ->result_array();
  }

}
