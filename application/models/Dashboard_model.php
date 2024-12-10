<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 */
class Dashboard_model extends CI_Model
{
	var $appoinments = 'appointments a'; 
  var $doctor ='users d';
  var $doctor_details ='users_details dd';
  var $patient ='users p';
  var $patient_details ='users_details pd';
  var $specialization ='specialization s';
  var $payment ='payments pa';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_doctors()
	{
		$this->db->select('u.*,s.specialization,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=u.id) as rating_value');
        $this->db->from('users u'); 
        $this->db->join('users_details ud','ud.user_id = u.id','left'); 
        $this->db->join('specialization s','ud.specialization = s.id','left');
        $this->db->where('u.status','1');
        $this->db->where('u.role','1');
        $this->db->order_by('u.id','DESC');
        $this->db->limit('10');
        return $this->db->get()->result_array();
	}

	public function get_patients()
	{
		$this->db->select('u.*,ud.dob,ud.blood_group,ud.currency_code,(select appointment_date from appointments where appointment_from=u.id order by id desc limit 1) as last_vist,(select total_amount from payments where user_id=u.id order by id desc limit 1) as last_paid');
        $this->db->from('users u'); 
        $this->db->join('users_details ud','ud.user_id = u.id','left'); 
        $this->db->where('u.status','1');
        $this->db->where('u.role','2');
        $this->db->order_by('u.id','DESC');
        $this->db->limit('10');
        return $this->db->get()->result_array();
	}

	public function users_count($role)
	{
		$this->db->where('role',$role);
		$this->db->where('status',1);
		return $this->db->get('users')->num_rows();
	}

	Public function appointments_count()
	{
		$this->db->where('status',1);
		return $this->db->get('appointments')->num_rows();
	}

	Public function get_appointments()
	{
		$this->db->select(' a.id,MAX(a.payment_id) AS payment_id,MAX(a.hospital_id) AS hospital_id,MAX(a.appointment_from) AS appointment_from,
    MAX(a.appointment_to) AS appointment_to,
    MAX(a.from_date_time) AS from_date_time,
    MAX(a.to_date_time) AS to_date_time,
    MAX(a.appointment_date) AS appointment_date,
    MAX(a.appointment_time) AS appointment_time,
    MAX(a.appointment_end_time) AS appointment_end_time,
    MAX(a.appoinment_token) AS appoinment_token,
    MAX(a.appoinment_session) AS appoinment_session,
    MAX(a.myself) AS myself,
    MAX(a.dependent) AS dependent,
    MAX(a.reason) AS reason,
    MAX(a.payment_method) AS payment_method,
    MAX(a.tokboxsessionId) AS tokboxsessionId,
    MAX(a.tokboxtoken) AS tokboxtoken,
    MAX(a.paid) AS paid,
    MAX(a.approved) AS approved,
    MAX(a.created_date) AS created_date,
    MAX(a.time_zone) AS time_zone,
    MAX(a.status) AS status,
    MAX(a.appointment_status) AS appointment_status,
    MAX(a.cancel_reason) AS cancel_reason,
    MAX(a.call_status) AS call_status,
    MAX(a.review_status) AS review_status,
    MAX(a.call_end_status) AS call_end_status,
    MAX(a.type) AS type,
    MAX(a.change_date) AS change_date,
    MAX(CONCAT(d.first_name, " ", d.last_name)) AS doctor_name,
    MAX(d.username) AS doctor_username,
    MAX(d.profileimage) AS doctor_profileimage,
    MAX(CONCAT(p.first_name, " ", p.last_name)) AS patient_name,
    MAX(p.profileimage) AS patient_profileimage,
    MAX(s.specialization) AS doctor_specialization,
    MAX(pa.total_amount) AS total_amount,
    MAX(pd.currency_code) AS currency_code,
    MAX(cliu.first_name) AS clinic_first_name,
    MAX(cliu.last_name) AS clinic_last_name,
    MAX(d.role) AS role,
    MAX(cliu.username) AS clinic_username');
        $this->db->from($this->appoinments);
        $this->db->join($this->doctor, 'd.id = a.appointment_to', 'left'); 
        $this->db->join($this->doctor_details,'dd.user_id = d.id','left'); 
        $this->db->join($this->patient, 'p.id = a.appointment_from', 'left'); 
        $this->db->join($this->patient_details,'pd.user_id = p.id','left'); 
        $this->db->join($this->specialization,'dd.specialization = s.id','left');
        $this->db->join($this->payment,'a.payment_id = pa.id','left');
        $this->db->join('users cliu', 'cliu.id = a.hospital_id', 'left'); 
        $this->db->join('users_details clud','clud.user_id = cliu.id','left');
        $this->db->order_by('a.id','DESC');
        $this->db->group_by('a.id');
        $this->db->limit('10');
      //   $qu=  $this->db->get_compiled_select();
      //  echo $qu.'<br>';
         //die('test');
        return $this->db->get()->result_array();
	}

    Public function get_revenue()
    {
           $this->db->select('p.*,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
            $this->db->from('payments p');
            $this->db->where('p.payment_status',1);
            $this->db->where('p.request_status !=',7);
            $result=$this->db->get()->result_array();

            $revenue=0;
               if(!empty($result))
            {
              foreach ($result as $rows) {

                    $tax_amount=$rows['tax_amount']+$rows['transcation_charge'];
        
                    $amount=($rows['total_amount']) - ($tax_amount);

                    $commission = !empty(settings("commission"))?settings("commission"):"0";
                    $commission_charge = ($amount * ($commission/100));
                    $balance_temp= $commission_charge;

                    
                    $user_currency_code=default_currency_code();
                    

                    $currency_option = $user_currency_code;
                    $rate_symbol = currency_code_sign($currency_option);

                    $org_amount=get_doccure_currency($balance_temp,$rows['currency_code'],$user_currency_code);
                    
                    $revenue +=$org_amount;
                
              }
            }

            if($revenue<=0) $revenue=0;

            return $revenue;
    }
/** 
 * @param integer $page 
 * @param integer $limit 
 * @param string $type 
 * @param array $user_data
 */
      public function search_doctor($page,$limit,$type,$user_data)
  {
        $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=u.id) as rating_value');
        $this->db->from('users u');
        $this->db->join('users_details ud','ud.user_id = u.id','left');
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left');
        $this->db->join('specialization sp','ud.specialization = sp.id','left');
        $this->db->where('u.role','1');
        
        /** @var array $user_data|null */

        if(!empty($user_data['cities'])){
           $this->db->where("(s.statename = '".$user_data['cities']."' OR ci.city = '".$user_data['cities']."')");
          }else{}
        
       if(!empty($_POST['city'])){
       $this->db->where("(ud.state = '".$_POST['city']."' OR ud.city = '".$_POST['city']."')");
       }
        if(!empty($_POST['state'])){
         $this->db->where("(ud.state = '".$_POST['state']."' OR ud.city = '".$_POST['state']."')");
       }
        if(!empty($_POST['country'])){
          $this->db->where('ud.country',$_POST['country']);
        }
        if(!empty($_POST['specialization'])) {   
            $spec_array=explode(",",$_POST['specialization']);
           foreach ($spec_array as $key => $value) {
             if($key == 0){
           $this->db->where('ud.specialization',$value);
         }else{
          $this->db->or_where('ud.specialization',$value);
         }
         }
        }

         if(!empty($_POST['keywords'])) {  
          $this->db->group_start();
          $this->db->like('u.first_name',$_POST['keywords'],'after');
          $this->db->or_like('u.last_name',$_POST['keywords'],'after');
          $this->db->or_like('CONCAT( u.first_name, " ", u.last_name)',$_POST['keywords'],'after');
          $this->db->or_like('sp.specialization',$_POST['keywords'],'after');
          $this->db->group_end();
        }

        if(!empty($_POST['gender'])) {   
          //$this->db->where("find_in_set($_POST['gender'], ud.gender)");
          
           $gender_array=explode(",",$_POST['gender']);
           foreach ($gender_array as $key => $value) {
             if($key == 0){
           $this->db->where('ud.gender',$value);
         }else{
          $this->db->or_where('ud.gender',$value);
         }
         }
        

        }
        if($_POST['order_by'] == 'Free'){
          $this->db->where('ud.price_type','Free');
        }
        $this->db->group_by('ud.id');
        // if(!empty($_POST['order_by'])){

         if($_POST['order_by'] == 'Rating'){
           $this->db->order_by('rating_value','DESC');
        }
        // if($_POST['order_by'] == 'Popular'){
        //   $query .=" ORDER BY rating_count DESC ";
        // }
        if($_POST['order_by'] == 'Latest'){
         $this->db->order_by('u.id','DESC');
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

  public function search_patient($page,$limit,$type)
  {
        $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname');
        $this->db->from('users u');
        $this->db->join('users_details ud','ud.user_id = u.id','left');
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left');
        $this->db->where('u.role','2');
        
        
       if(!empty($_POST['city'])){
          $this->db->where('ud.city',$_POST['city']);
        }
        if(!empty($_POST['state'])){
         $this->db->where('ud.state',$_POST['state']);
        }
        if(!empty($_POST['country'])){
          $this->db->where('ud.country',$_POST['country']);
        }
        if(!empty($_POST['gender'])) {   
          $this->db->where('ud.gender',$_POST['gender']);
        }
        if(!empty($_POST['blood_group'])) {   
          $this->db->where('ud.blood_group',$_POST['blood_group']);
        }

         if(!empty($_POST['keywords'])) {  
          $this->db->group_start();
          $this->db->like('u.first_name',$_POST['keywords'],'after');
          $this->db->or_like('u.last_name',$_POST['keywords'],'after');
          $this->db->or_like('CONCAT( u.first_name, " ", u.last_name)',$_POST['keywords'],'after');
          $this->db->group_end();
        }
        $this->db->group_by('ud.id');

        if($_POST['order_by'] == 'Latest'){
         $this->db->order_by('u.id','DESC');
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


  public function get_notification($page,$limit,$type,$id=''){
     
     $this->db->select('n.*,IF(n.user_id>0,CONCAT(from.first_name," ", from.last_name),"Admin") as from_name,IF(n.to_user_id>0,CONCAT(to.first_name," ", to.last_name),"Admin") as to_name,from.profileimage as profile_image,to.profileimage as to_profile_image,n.created_at as notification_date');
        $this->db->from('notification n');
        $this->db->join('users from','n.user_id = from.id','left');
        $this->db->join('users to','n.to_user_id = to.id','left');
        if($id!=''){
          $this->db->group_start();
          $this->db->where('n.user_id',$id);
          $this->db->or_where('n.to_user_id',$id);
          $this->db->group_end();
        }
        $this->db->order_by('n.id','DESC');
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

  

  
}
?>
