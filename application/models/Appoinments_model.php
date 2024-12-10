<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property object $load 
 * @property object $db
 * @property object $session
 */

class Appoinments_model extends CI_Model {

  var $table = 'appointments a';
  var $users ='users u';
 // var $column_search = array('u.first_name','u.last_name','u.profileimage','a.appointment_date','a.from_date_time'); //set column field database for datatable searchable 
  var $column_search = array('CONCAT(u.first_name," ", u.last_name)','date_format(a.appointment_date,"%d %b %Y")','a.type');
  var $order = array('a.id' => 'ASC'); // default order
	var $column_order = array('','CONCAT(u.first_name," ", u.last_name)','a.appointment_date','a.type');	 	

// admin
  var $appoinments = 'appointments a'; 
  var $doctor ='users d';
  var $doctor_details ='users_details dd';
  var $patient ='users p';
  var $patient_details ='users_details pd';
  var $specialization ='specialization s';
  var $payment ='payments pa';

  var $appoinments_column_search = array('CONCAT(d.first_name," ",d.last_name)','d.profileimage','CONCAT(p.first_name," ",p.last_name)','p.profileimage','date_format(a.appointment_date,"%d %b %Y")','date_format(a.created_date,"%d %b %Y")','a.type'); 
  var $appoinments_default_order = array('a.id' => 'DESC'); // upcoming appointments default order   
  var $appointments_column_order = array('','cliu.first_name, d.first_name','p.first_name','a.from_date_time','a.created_date','a.type','a.appointment_status','total_amount_decimal'); // upcoming appointments column order 
   var $appoinments_order = array('CONCAT(d.first_name," ",d.last_name)','d.profileimage','CONCAT(p.first_name," ",p.last_name)','p.profileimage','date_format(a.appointment_date,"%d %b %Y")','a.created_date','a.type');

  var $lab_payments = 'lab_payments lp';

  var $labappoinments_column_search = array('CONCAT(p.first_name," ", p.last_name)','date_format(lp.lab_test_date,"%d %b %Y")','lp.total_amount','date_format(lp.payment_date,"%d %b %Y")','lp.cancel_status','lt.lab_test_name');
  var $labappoinments_order = array('lp.id' => 'DESC'); // default order 

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function get_total_test($user_id){
    $where =array('lab_id' => $user_id);
    return $this->db->get_where('lab_tests',$where)->num_rows();
  }  
  public function get_today_labpatient($user_id){
    
     $where =array('lab_id' => $user_id,'lab_test_date'=>date('Y-m-d'));
    //$where =array('lab_id' => 58,'lab_test_date'=>'2023-07-27');
   // echo $user_id.'<br>';
    $this->db->select('id,lab_id,patient_id,booking_ids,invoice_no,lab_test_date,total_amount,currency_code,txn_id,order_id	,transaction_status	,payment_type	,tax,tax_amount,transcation_charge,payment_status,payment_date,status,cancel_status,booking_ids_price');
    //$this->db->from('lab_payments');
    
   // $this->db->where($where);
   // $this->db->group_by('lab_id');
    
  //  $a=$this->db->get_where('lab_payments',$where)->num_rows();
    //$qu=  $this->db->get_compiled_select();
    //  echo $qu.'<br>';
      // echo $a.'<br>';
      //die("test");
    return $this->db->get_where('lab_payments',$where)->num_rows();
    //return $this->db->group_by('lab_id')->get_where('lab_payments',$where);
    // 
    
   
    //$this->db->get()->result_array();
    //return $qry->num_rows();
    //return $this->db->get()->num_rows();
  }
  public function get_recent_labbooking($user_id){
    $where =array('lab_id' => $user_id);
    return $this->db->get_where('lab_payments',$where)->num_rows();
  }

  private function _get_datatables_query($user_id)
  {
    $current_date = date('Y-m-d');
    $from_date_time = date('Y-m-d H:i:s');
   $this->db->select('a.*,u.first_name,u.last_name,u.username,u.profileimage,p.per_hour_charge,ud.first_name as doctor_name,ud.role');
    $this->db->from($this->table);
    $this->db->join($this->users, 'u.id = a.appointment_from', 'left'); 
    $this->db->join('payments p','p.id = a.payment_id','left');  
    $this->db->join('users ud', 'ud.id = a.appointment_to', 'left'); 
	
    if($_POST['type'] == 1){
      $this->db->where('a.appointment_date',$current_date);
    }

    if($_POST['type'] == 2){
      $this->db->where('a.from_date_time > ',$from_date_time);
    }
	$this->db->group_start();
    $this->db->where('a.appointment_to',$user_id);
    $this->db->or_where('a.hospital_id',$user_id); 
    $this->db->group_end();
   // $this->db->order_by('a.id','DESC');
   
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

  public function get_datatables($user_id)
  {
    $this->_get_datatables_query($user_id);
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function count_filtered($user_id)
  {
    $this->_get_datatables_query($user_id);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all($user_id)
  {
    $current_date = date('Y-m-d');
    $from_date_time = date('Y-m-d H:i:s');

    if($_POST['type'] == 1){
      $this->db->where('a.appointment_date',$current_date);
    }

    if($_POST['type'] == 2){
      $this->db->where('a.from_date_time > ',$from_date_time);
    }
    $this->db->group_start();
    $this->db->where('a.appointment_to',$user_id);
    $this->db->or_where('a.hospital_id',$user_id); 
    $this->db->group_end();

    $this->db->from($this->table);
    return $this->db->count_all_results();
  }



  public function doctor_appoinments_list($page,$limit,$type,$user_id)
  {
    $from_date_time = date('Y-m-d H:i:s');
         $this->db->select('a.*,u.id as userid,u.first_name,u.last_name,u.username,u.profileimage,u.email,u.mobileno,c.country as countryname,s.statename,ci.city as cityname,p.per_hour_charge');
        $this->db->from('appointments a');
        $this->db->join('users u', 'u.id = a.appointment_from', 'left');
        $this->db->join('users_details ud', 'u.id = ud.user_id', 'left');
        $this->db->join('payments p','p.id = a.payment_id','left');   
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left'); 
        $this->db->where('a.appointment_to',$user_id);
        //$this->db->or_where('a.hospital_id ',$user_id);
        
        $this->db->where('a.to_date_time > ',$from_date_time);
        //$this->db->where('a.appointment_date >',date('Y-m-d'));
        //$this->db->or_where('a.appointment_date =',date('Y-m-d'));

        $this->db->where('a.appointment_status',0);
        $this->db->order_by('a.from_date_time','ASC');
        // $this->db->group_by('a.id,');
        $this->db->group_by('a.id, u.id, u.first_name, u.last_name, u.username, u.profileimage, u.email, u.mobileno, c.country, s.statename, ci.city, p.per_hour_charge');
        if($type == 1){
         return $this->db->count_all_results(); 
        }else{

          $page = !empty($page)?$page:'';
          if($page>=1){
          $page = $page - 1 ;
          }
          $page =  ($page * $limit);  
          $this->db->limit($limit,$page);
          $query =$this->db->get();
          return $query->result_array();
        }
  }


  public function patient_appoinments_list($page,$limit,$type,$user_id)
  {
    // $this->db->select('a.*,u.id as userid,u.first_name,u.last_name,u.username,u.profileimage,u.email,u.mobileno,c.country as countryname,s.statename,ci.city as cityname,p.per_hour_charge');
    // $this->db->from('appointments a');
    // $this->db->join('users u', 'u.id = a.appointment_to', 'left');
    // $this->db->join('users_details ud', 'u.id = ud.user_id', 'left');
    // $this->db->join('payments p','p.id = a.payment_id','left');   
    // $this->db->join('country c','ud.country = c.countryid','left');
    // $this->db->join('state s','ud.state = s.id','left');
    // $this->db->join('city ci','ud.city = ci.id','left'); 
    // $this->db->where('a.appointment_from',$user_id);
    // $this->db->where('a.appointment_status',0);
    // $this->db->order_by('a.from_date_time','ASC');
    // $this->db->group_by('a.id');
    $this->db->select('
    a.*,
    u.id as userid,
    u.first_name,
    u.last_name,
    u.username,
    u.profileimage,
    u.email,
    u.mobileno,
    c.country as countryname,
    s.statename,
    ci.city as cityname,
    p.per_hour_charge
');
$this->db->from('appointments a');
$this->db->join('users u', 'u.id = a.appointment_to', 'left');
$this->db->join('users_details ud', 'u.id = ud.user_id', 'left');
$this->db->join('payments p', 'p.id = a.payment_id', 'left');   
$this->db->join('country c', 'ud.country = c.countryid', 'left');
$this->db->join('state s', 'ud.state = s.id', 'left');
$this->db->join('city ci', 'ud.city = ci.id', 'left'); 
$this->db->where('a.appointment_from', $user_id);
$this->db->where('a.appointment_status', 0);
$this->db->group_by('
    a.id,
    u.id,
    u.first_name,
    u.last_name,
    u.username,
    u.profileimage,
    u.email,
    u.mobileno,
    c.country,
    s.statename,
    ci.city,
    p.per_hour_charge
');
$this->db->order_by('a.from_date_time', 'ASC');
// $result = $this->db->get()->result_array();

    if($type == 1){
     return $this->db->count_all_results(); 
    }else{

      $page = !empty($page)?$page:'';
      if($page>=1){
      $page = $page - 1;
      }
      $page =  (intval($page) * intval($limit));  
      $this->db->limit($limit,$page);
      return $this->db->get()->result_array();
    }
  }

  Public function get_total_patient($user_id){
    // echo $user_id;
    $where =array('appointment_to' => $user_id);
    // die("dsfg");
    return $this->db->get_where('appointments',$where)->num_rows();
   // return $this->db->group_by('appointment_from')->get_where('appointments',$where)->num_rows();
  }  
  Public function get_today_patient($user_id){
    $where =array('appointment_to' => $user_id,'appointment_date'=>date('Y-m-d'));
    return $this->db->get_where('appointments',$where)->num_rows();
    //return $this->db->group_by('appointment_from')->get_where('appointments',$where)->num_rows();
  }
    Public function get_recent_booking($user_id){
    //$where =array('doctor_id' => $user_id,'payment_date >='=>date('Y-m-d'));
    $where =array('doctor_id' => $user_id);
	return $this->db->get_where('payments',$where)->num_rows();
  }


  //admin----------------------------

  private function _get_appoinments__datatables_query($type)
  {
  
    // $this->db->select('a.*, CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage, CONCAT(p.first_name," ", p.last_name) as patient_name,p.profileimage as patient_profileimage,s.specialization as doctor_specialization,pa.total_amount,pd.currency_code,cliu.first_name as clinic_first_name,cliu.last_name as clinic_last_name,d.role,cliu.username as clinic_username, TRUNCATE(pa.total_amount,2) as total_amount_decimal');
    // $this->db->from($this->appoinments);
    // $this->db->join($this->doctor, 'd.id = a.appointment_to', 'left'); 
    // $this->db->join($this->doctor_details,'dd.user_id = d.id','left'); 
    // $this->db->join($this->patient, 'p.id = a.appointment_from', 'left'); 
    // $this->db->join($this->patient_details,'pd.user_id = p.id','left'); 
    // $this->db->join($this->specialization,'dd.specialization = s.id','left');
    // $this->db->join($this->payment,'a.payment_id = pa.id','left');
    // $this->db->join('users cliu', 'cliu.id = a.hospital_id', 'left'); 
    // $this->db->join('users_details clud','clud.user_id = cliu.id','left'); 
    $this->db->select('
    a.*, 
    CONCAT(d.first_name," ", d.last_name) as doctor_name,
    d.username as doctor_username,
    d.profileimage as doctor_profileimage,
    CONCAT(p.first_name," ", p.last_name) as patient_name,
    p.profileimage as patient_profileimage,
    s.specialization as doctor_specialization,
    pa.total_amount,
    pd.currency_code,
    cliu.first_name as clinic_first_name,
    cliu.last_name as clinic_last_name,
    d.role,
    cliu.username as clinic_username, 
    TRUNCATE(pa.total_amount,2) as total_amount_decimal
');
$this->db->from($this->appoinments);
$this->db->join($this->doctor, 'd.id = a.appointment_to', 'left'); 
$this->db->join($this->doctor_details, 'dd.user_id = d.id', 'left'); 
$this->db->join($this->patient, 'p.id = a.appointment_from', 'left'); 
$this->db->join($this->patient_details, 'pd.user_id = p.id', 'left'); 
$this->db->join($this->specialization, 'dd.specialization = s.id', 'left');
$this->db->join($this->payment, 'a.payment_id = pa.id', 'left');
$this->db->join('users cliu', 'cliu.id = a.hospital_id', 'left'); 
$this->db->join('users_details clud', 'clud.user_id = cliu.id', 'left');

    if($type == 1){
        //entering here
        // die("1st if");
      //Get completed appointmets
     $this->db->where('a.appointment_status',1);
     $this->db->where('a.call_status',1);
    


    }elseif($type == 2){
       
      //Upcoming appointments
      $from_date_time = date('Y-m-d H:i:s');
     $this->db->where('a.from_date_time >',$from_date_time); 
     $this->db->where('a.appointment_status',0);
     $this->db->where('a.call_status',0);


    }elseif($type == 3){
        
      //missed apppointments
       $from_date_time = date('Y-m-d H:i:s');
     $this->db->where('a.from_date_time <',$from_date_time); 
     $this->db->where('a.appointment_status',1);
     $this->db->where('a.call_status',0);

    }

   // $this->db->order_by('a.from_date_time','ASC');
    $this->db->group_by('a.id');
        
   
    $i = 0;
  
    foreach ($this->appoinments_column_search as $item) // loop column 
    {
      if($_POST['search']['value']) // if datatable send POST for search
      {
        
        if($i===0) // first loop
        {
            
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
		  //$this->db->group_end(); //close bracket
        }
        else
        {
            
          $this->db->or_like($item, $_POST['search']['value']);
        }
            
         if(count($this->appoinments_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
            // $this->db->order_by('id', $_POST['order']['0']['dir']);

       $this->db->order_by($this->appointments_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->appoinments_default_order))
    {
        //entering here also
        // die("2 if");
      $order = $this->appoinments_default_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_appoinments_datatables()
  {
    //   die("sd");
    $this->_get_appoinments__datatables_query(1);
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    // $qu=  $this->db->get_compiled_select();
    //  echo $qu.'<br>';
    //   die("test");
    $this->db->group_by('
    a.id,
    d.id,
    d.first_name,
    d.last_name,
    d.username,
    d.profileimage,
    p.id,
    p.first_name,
    p.last_name,
    p.profileimage,
    s.specialization,
    pa.total_amount,
    pd.currency_code,
    cliu.id,
    cliu.first_name,
    cliu.last_name,
    d.role,
    cliu.username
');
    $query = $this->db->get();

    return $query->result_array();
  }

  public function get_upappoinments_datatables()
  {
    $this->_get_appoinments__datatables_query(2);
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $this->db->group_by('
    a.id,
    d.id,
    d.first_name,
    d.last_name,
    d.username,
    d.profileimage,
    p.id,
    p.first_name,
    p.last_name,
    p.profileimage,
    s.specialization,
    pa.total_amount,
    pd.currency_code,
    cliu.id,
    cliu.first_name,
    cliu.last_name,
    d.role,
    cliu.username
');
    $query = $this->db->get();
    return $query->result_array();
  }

  public function get_missedappoinments_datatables()
  {
    $this->_get_appoinments__datatables_query(3);
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $this->db->group_by('
    a.id,
    d.id,
    d.first_name,
    d.last_name,
    d.username,
    d.profileimage,
    p.id,
    p.first_name,
    p.last_name,
    p.profileimage,
    s.specialization,
    pa.total_amount,
    pd.currency_code,
    cliu.id,
    cliu.first_name,
    cliu.last_name,
    d.role,
    cliu.username
');
    $query = $this->db->get();
    return $query->result_array();
  }

  public function appoinments_count_filtered($type)
  {
    $this->_get_appoinments__datatables_query($type);
    $this->db->group_by('
    a.id,
    d.id,
    d.first_name,
    d.last_name,
    d.username,
    d.profileimage,
    p.id,
    p.first_name,
    p.last_name,
    p.profileimage,
    s.specialization,
    pa.total_amount,
    pd.currency_code,
    cliu.id,
    cliu.first_name,
    cliu.last_name,
    d.role,
    cliu.username
');
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function appoinments_count_all($type)
  {
    $this->db->from($this->appoinments);
    if($type == 1){
      //Get completed appointmets
      $this->db->where('a.appointment_status',1);
      $this->db->where('a.call_status',1);


    }elseif($type == 2){
      //Upcoming appointments
      $from_date_time = date('Y-m-d H:i:s');
      $this->db->where('a.from_date_time >',$from_date_time); 
      $this->db->where('a.appointment_status',0);
      $this->db->where('a.call_status',0);


    }elseif($type == 3){
      //missed apppointments
      $from_date_time = date('Y-m-d H:i:s');
      $this->db->where('a.from_date_time <',$from_date_time); 
      $this->db->where('a.appointment_status',1);
      $this->db->where('a.call_status',0);

    }
    $this->db->order_by('a.from_date_time','ASC');
    return $this->db->count_all_results();
  }


  public function get_favourites($user_id)
  {
    $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=u.id) as rating_value');
    $this->db->from('favourities f');
    $this->db->join('users u','u.id = f.doctor_id','left');
    $this->db->join('users_details ud','ud.user_id = u.id','left');
    $this->db->join('country c','ud.country = c.countryid','left');
    $this->db->join('state s','ud.state = s.id','left');
    $this->db->join('city ci','ud.city = ci.id','left');
    $this->db->join('specialization sp','ud.specialization = sp.id','left');
    $this->db->where('f.patient_id',$user_id);
    return $result = $this->db->get()->result_array();
  }

  public function get_appoinment_call_details($appoinment_id)
  {
    $this->db->select('a.*, CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage, CONCAT(p.first_name," ", p.last_name) as patient_name,p.profileimage as patient_profileimage,p.id as patient_id,d.id as doctor_id,d.first_name as doctor_firstname,d.last_name as doctor_lastname,p.first_name as patient_firstname,p.last_name as patient_lastname,d.device_id as doctor_device_id,d.device_type as doctor_device_type,p.device_id as patient_device_id,p.device_type as patient_device_type');
    $this->db->from($this->appoinments);
    $this->db->join($this->doctor, 'd.id = a.appointment_to', 'left'); 
    $this->db->join($this->doctor_details,'dd.user_id = d.id','left'); 
    $this->db->join($this->patient, 'p.id = a.appointment_from', 'left'); 
    $this->db->join($this->patient_details,'pd.user_id = p.id','left'); 
    $this->db->where('md5(a.id)',$appoinment_id);
    return $this->db->get()->row_array();
  }

  public function get_call($user_id)
  {
    $this->db->select('c.call_type,c.id,c.appointments_id,CONCAT(u.first_name," ", u.last_name) as name,u.profileimage,u.role');
    $this->db->from('call_details c');
    $this->db->join('users u','u.id = c.call_from','left');
    $this->db->where('c.call_to',$user_id);
    return $this->db->get()->row_array();

  }

  private function _get_labappointment_datatables_query($user_id) {
        $this->db->select('lp.*, CONCAT(p.first_name," ", p.last_name) as patient_name,lp.booking_ids as test_ids, GROUP_CONCAT(lt.lab_test_name) as lab_test_names');
        $this->db->from($this->lab_payments);
        $this->db->join($this->patient, 'p.id = lp.lab_id', 'left');
        $this->db->join('lab_tests lt', 'FIND_IN_SET(lt.id, lp.booking_ids)', 'left');
       // $this->db->join($this->table,'lt.lab_id = u.id','left');
        $this->db->where('lp.patient_id',$user_id);
        
        $i = 0;
        foreach ($this->labappoinments_column_search as $item) 
        {
            if($_POST['search']['value']) 
            {

                if($i===0) 
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                    if(count($this->labappoinments_column_search) - 1 == $i) {
                      // booking status search
                      if ($_POST['search']['value']) {
                        $this->db->or_group_start();
                        $this->db->where('
                          CASE WHEN 
                            "Success" LIKE "%'.$_POST['search']['value'].'%"
                          THEN 
                            lp.payment_status = 1
                          ELSE 
                            CASE WHEN 
                              "Failed" LIKE "%'.$_POST['search']['value'].'%"
                            THEN 
                              lp.payment_status = 0
                            ELSE 
                              FALSE
                            END
                          END
                        ', NULL, FALSE);
                        $this->db->group_end();
                      }

                      $this->db->group_end();
                    }
            }
            $i++;
        }
        if(isset($_POST['order']))
        {
            $this->db->order_by('id', $_POST['order']['0']['dir']);
        } 
        else if(isset($this->labappoinments_order))
        {
            $order = $this->labappoinments_order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

        // group_by lab payments
        $this->db->group_by('lp.id');
    }




  public function get_labappointment_details($user_id) {
        $this->_get_labappointment_datatables_query($user_id);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function labappointments_count_filtered($user_id)
    {
        $this->_get_labappointment_datatables_query($user_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function labappointments_count_all($user_id)
    {
        $this->db->where('lp.patient_id',$user_id);
        $this->db->from($this->lab_payments);
        return $this->db->count_all_results();
    }
  public function update_appointment_lists($user_id){     
        if($this->session->userdata('role')=='1'){
      $this->db->where('appointment_to',$user_id);
    } else {
      $this->db->where('appointment_from',$user_id);
    }
    $this->db->where('approved',0);
    $this->db->where('appointment_date <= CURDATE() AND appointment_end_time < CURTIME()');
    $this->db->update('appointments',array('appointment_status' =>1));
  }

}