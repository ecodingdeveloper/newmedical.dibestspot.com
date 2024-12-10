<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

   public $data;
   public $session;
   public $timezone;
   public $input;
   public $db;
   public $dashboard;

   public function __construct() {

        parent::__construct();

        if($this->session->userdata('admin_id') ==''){
            redirect(base_url().'admin/login');
        }
        
        $this->data['theme']     = 'admin';
        $this->data['module']    = 'dashboard';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
         $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }
        $this->load->model('dashboard_model','dashboard');
        $this->load->model('home_model','home');
        

    }


	public function index()
	{
	    $this->data['page'] = 'index';
      $this->data['doctors'] = $this->dashboard->get_doctors();
      $this->data['patients'] = $this->dashboard->get_patients();
      $this->data['doctors_count'] = $this->dashboard->users_count(1);
      $this->data['patients_count'] = $this->dashboard->users_count(2);
      $this->data['appointments_count'] = $this->dashboard->appointments_count();
      $this->data['appointments'] = $this->dashboard->get_appointments();
      $this->data['revenue'] = $this->dashboard->get_revenue();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
	   
	}

  public function search(){

      $this->data['page'] = 'search';
      $this->data['doctors'] = $this->dashboard->get_doctors();
      $this->data['patients'] = $this->dashboard->get_patients();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');

  }
   public function searchpatient(){

      $this->data['page'] = 'searchpatient';
      $this->data['doctors'] = $this->dashboard->get_doctors();
      $this->data['patients'] = $this->dashboard->get_patients();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');

  }


   public function search_patient()
  {

       $response=array();
       $result=array();
        $page=$this->input->post('page');
        $limit=5;
        $response['count'] =$this->dashboard->search_patient($page,$limit,1);
        $doctor_list = $this->dashboard->search_patient($page,$limit,2);

        if (!empty($doctor_list)) {
          foreach ($doctor_list as $rows) {

            $data['id']=$rows['id'];
            $data['username']=$rows['username'];
            $data['profileimage']=(!empty($rows['profileimage']))?base_url().$rows['profileimage']:base_url().'assets/img/user.png';
            $data['first_name']=ucfirst($rows['first_name']);
            $data['last_name']=ucfirst($rows['last_name']);
            $data['cityname']=$rows['cityname'];
            $data['countryname']=$rows['countryname'];
            $data['patient_id']="#PT00".$rows['user_id'];

            $today = date("Y-m-d");
            $diff = date_diff(date_create($rows['dob']), date_create($today));
            $data['age']=$diff->format('%y');
            $data['gender']=$rows['gender'];
            $data['blood_group']=$rows['blood_group'];
            $data['mobileno']=$rows['mobileno'];
          
            
      

            
            $result[]=$data;
          }
        }
        $response['current_page_no']= $page;
        $response['total_page']= ceil($response['count']/$limit);
        $response['data']= $result;

     echo json_encode($response);

  }

   public function search_doctor()
  {

       $response=array();
       $result=array();
        $page=$this->input->post('page');
        $limit=5;
        $response['count'] =$this->dashboard->search_doctor($page,$limit,1,$usedata=array());
        $doctor_list = $this->dashboard->search_doctor($page,$limit,2,$usedata=array());

        if (!empty($doctor_list)) {
          foreach ($doctor_list as $rows) {

            $data['id']=$rows['id'];
            $data['username']=$rows['username'];
            $data['profileimage']=(!empty($rows['profileimage']))?base_url().$rows['profileimage']:base_url().'assets/img/user.png';
            $data['first_name']=ucfirst($rows['first_name']);
            $data['last_name']=ucfirst($rows['last_name']);
            $data['specialization_img']=base_url().$rows['specialization_img'];
            $data['speciality']=ucfirst($rows['speciality']);
            $data['cityname']=$rows['cityname'];
            $data['countryname']=$rows['countryname'];
            $data['services']=$rows['services'];
            $data['rating_value']=$rows['rating_value'];
            $data['rating_count']=$rows['rating_count'];
            //$data['latitude']=$this->latitude($rows['cityname'].' '.$rows['countryname']);
           // $data['longitude']=$this->longitude($rows['cityname'].' '.$rows['countryname']);
            
            $data['clinic_images']= json_encode($this->clinic_images($rows['user_id']));
            
            
            
            if($rows['price_type']=='Custom Price'){

            $user_currency=get_user_currency();
            $user_currency_code=$user_currency['user_currency_code'];
            $user_currency_rate=$user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code))?$user_currency_code:$rows['currency_code'];
            $rate_symbol = currency_code_sign($currency_option);

                      if(!empty($this->session->userdata('user_id'))){
                        $rate=get_doccure_currency($rows['amount'],$rows['currency_code'],$user_currency_code);
                      }else{
                           $rate= $rows['amount'];
                        }
            $data['amount']=$rate_symbol.''.$rate;

            }else{

              $data['amount']="Free";
            }

            
            $result[]=$data;
          }
        }
        $response['current_page_no']= $page;
        $response['total_page']= ceil($response['count']/$limit);
        $response['data']= $result;

     echo json_encode($response);

  }

  public function clinic_images($id){
  
    $this->db->select('clinic_image,user_id');
    $this->db->where('user_id',$id);
    $result=$this->db->get('clinic_images')->result_array();
    return $result;

  }


  public function revenue_graph(){


     $response=array();
     $result=array();
     $month_array=array(1,2,3,4,5,6,7,8,9,10,11,12);
     foreach ($month_array as $value) {

     

    $query = $this->db->query("SELECT currency_code,IFNULL((payments.total_amount),0) as total_amount,IFNULL((payments.tax_amount),0) as tax_amount,IFNULL((payments.transcation_charge),0) as transcation_charge,MONTHNAME(payments.payment_date) as rev_month FROM ( SELECT $value AS MONTH  ) AS rev_month LEFT JOIN payments ON rev_month.month = MONTH(payments.payment_date) where payments.status =1 and payments.request_status !=7 and YEAR(payments.payment_date) = YEAR(CURDATE())
");
       $result_array= $query->result_array();
      $revenue=0;
     
      $rev_month="";
      $user_currency_code="";
    
       foreach ($result_array as $rows) {

        if($rows['total_amount'] > 0){

             $tax_amount=$rows['tax_amount']+$rows['transcation_charge'];
        
                    $amount=intval(($rows['total_amount']) - ($tax_amount));

                    $commission = !empty(settings("commission"))?settings("commission"):"0";
                    $commission_charge = ($amount * ($commission/100));
                    $balance_temp= $commission_charge;

                    $currency_option = default_currency_code();
                    $rate_symbol = currency_code_sign($currency_option);

                    $org_amount=get_doccure_currency($balance_temp,$rows['currency_code'],$user_currency_code);
                    
                    
                    $revenue +=$org_amount;
                    $rev_month=substr($rows['rev_month'],0,3);

        }

         
       }

          $data['revenue']=number_format($revenue,2);
          $data['month']=(empty($rev_month)) ? "" : $rev_month;

          $result[]=$data;
      
  }

       $response['data']= $result;

      echo json_encode($response);
   


  }


  public function status_graph(){


     $response=array();
     $result=array();

     $month_array=array(1,2,3,4,5,6,7,8,9,10,11,12);
     foreach ($month_array as $value) {

     

    $query = $this->db->query("SELECT id,role,MONTHNAME(users.created_date) as rev_month   FROM ( SELECT $value AS MONTH  ) AS rev_month LEFT JOIN users ON rev_month.month = MONTH(users.created_date)  where  YEAR(users.created_date) = YEAR(CURDATE())
");
       $result_array= $query->result_array();
      $patient=0;
      $doctor=0;
      $rev_month="";
       foreach ($result_array as $rows) {

       
                   if($rows['role'] == 1){
                    $doctor=$doctor+1;
                   }else if($rows['role'] == 2){
                    $patient=$patient+1;
                   }
             
                    
                    $doctor =$doctor;
                    $patient=$patient;
                    $rev_month=substr($rows['rev_month'],0,3);

        

         
       }

          $data['doctor']=round($doctor);
          $data['patient']=round($patient);
          $data['month']=strval($rev_month);

          $result[]=$data;
      
  }

       $response['data']= $result;

      echo json_encode($response);
   


  }


  public function notification(){

      $this->data['page'] = 'notification';
      $this->notification_update();
      $this->data['count'] =$this->dashboard->get_notification(1,5,1);
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
  }

  public function search_notification()
  {

       $response=array();
       $result=array();
        $page=$this->input->post('page');
        $limit=5;
        $response['count'] =$this->dashboard->get_notification($page,$limit,1);
        $notification_list = $this->dashboard->get_notification($page,$limit,2);

        if (!empty($notification_list)) {
          foreach ($notification_list as $rows) {

            $data['id']=$rows['id'];
            $data['from_name']=$rows['from_name'];
            $data['profile_image']=(!empty($rows['profile_image']))?base_url().$rows['profile_image']:base_url().'assets/img/user.png';
            $data['to_name']=ucfirst($rows['to_name']);
            $data['text']=ucfirst($rows['text']);
            $data['type']=$rows['type'];
            $data['notification_date']=time_elapsed_string($rows['notification_date']);
          
            
           

            
            $result[]=$data;
          }
        }
        $response['current_page_no']= $page;
        $response['total_page']= ceil($response['count']/$limit);
        $response['data']= $result;

     echo json_encode($response);

  }

  public function delete_notification(){

    $id=$this->input->post('id');

    if($id == 0){
       $this->db->where('is_viewed',1);
      $this->db->delete('notification');
      $response['status']=200;
      $response['msg']="Deleted successfully";

    }else{

      $this->db->where('id',$id);
      $this->db->delete('notification');
      $response['status']=200;
      $response['msg']="Deleted successfully";
            
    }

    echo json_encode($response);

  }

  public function notification_update(){

    $data=array('is_viewed'=>1);
    $this->db->where('is_viewed',0);
    $this->db->update('notification',$data);
     $response['status']=200;
      $response['msg']="Updated successfully";
      // echo json_encode($response);
  }

  public function admin_chat(){

    $this->data['page'] = 'admin_chat';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');
}


}
