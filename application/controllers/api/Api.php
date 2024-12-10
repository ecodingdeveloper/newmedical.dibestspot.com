<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
$application_folder="";
define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);
require_once(APPPATH . '../vendor/stripe/stripe-php/init.php');
require_once(APPPATH . '../vendor/autoload.php');

use Twilio\Rest\Client;
use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;

require APPPATH . '/libraries/REST_Controller.php';

class Api extends REST_Controller {
   public $language_content;
   public $tokbox_apiKey;
   public $tokbox_apiSecret;
   public $input;
   public $default_token;
   public $api_token;
   public $time_zone;
   public $api;
   public $user_details;
   public $user_id;
   public $role;
   public $db;
   public $sendemail;
   public $email;
   public $upload;
   public $session;

public function __construct() {

        parent::__construct();

        $this->load->model('api_model','api');

$this->load->model('accounts_model','accounts');
$this->load->model('doctor_model','doctor'); 
$this->load->model('signin_model','signin');
           $header =  $this->input->request_headers(); // Get Header Data
            $token = (!empty($header['token']))?$header['token']:'';
          if(empty($token)){
            $token = (!empty($header['Token']))?$header['Token']:'';
          }

          $time_zone = (!empty($header['timezone']))?$header['timezone']:'';
          if(empty($time_zone)){
            $time_zone = (!empty($header['Timezone']))?$header['Timezone']:'';
          }

          $lang = (!empty($header['language']))?$header['language']:'';
          if(empty($lang)){
            $lang = (!empty($header['Language']))?$header['Language']:'en';
          }
            $language = get_languages($lang);
            $language = (!empty($language['language']['api']))?$language['language']['api']:'';
            $this->language_content = $language;
 
         
          date_default_timezone_set($time_zone);

          $this->tokbox_apiKey=!empty(settings("apiKey"))?settings("apiKey"):"";
          $this->tokbox_apiSecret=!empty(settings("apiSecret"))?settings("apiSecret"):"";
 
          $this->default_token = md5('Dreams99');
           $this->api_token = $token;
          
          $this->time_zone = $time_zone;
          $this->user_details = $this->api->get_user_id_using_token($token);/*patient or doctor*/
          $this->user_id=$this->user_details['id'];
          $this->role=$this->user_details['role'];
          $this->email=$this->user_details['email'];
        
    }

	public function config_list_get()
	{
      if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

        $response=array();
        $result=array();

        $stripe_mode='';
        $stripe_api_key='';
        $stripe_secert_key='';

        $razorpay_mode='';
        $razorpay_api_key='';
        $razorpay_secert_key='';

        $stripe_option=!empty(settings("stripe_option"))?settings("stripe_option"):"1";
        if($stripe_option=='1'){
          $stripe_mode='test';
          $stripe_api_key=!empty(settings("sandbox_api_key"))?settings("sandbox_api_key"):"";
          $stripe_secert_key=!empty(settings("sandbox_rest_key"))?settings("sandbox_rest_key"):"";
        }
        if($stripe_option=='2'){
          $stripe_mode='live';
          $stripe_api_key=!empty(settings("live_api_key"))?settings("live_api_key"):"";
          $stripe_secert_key=!empty(settings("live_rest_key"))?settings("live_rest_key"):"";
        }

        $razorpay_option=!empty(settings("razorpay_option"))?settings("razorpay_option"):"1";
        if($razorpay_option=='1'){
          $razorpay_mode='test';
          $razorpay_api_key=!empty(settings("sandbox_key_id"))?settings("sandbox_key_id"):"";
          $razorpay_secert_key=!empty(settings("sandbox_key_secret"))?settings("sandbox_key_secret"):"";
        }
        if($razorpay_option=='2'){
          $razorpay_mode='live';
          $razorpay_api_key=!empty(settings("live_key_id"))?settings("live_key_id"):"";
          $razorpay_secert_key=!empty(settings("live_key_secret"))?settings("live_key_secret"):"";
        }

        $stripe = array(
          'mode' => $stripe_mode,
          'api_key' => $stripe_api_key,
          'secert_key' => $stripe_secert_key,
        );

        $razorpay = array(
          'mode' => $razorpay_mode,
          'api_key' => $razorpay_api_key,
          'secert_key' => $razorpay_secert_key,
        );
		
		//Paypal
		$paypal_mode='';
        $paypal_email='';
		$braintree_key='';
		$braintree_merchant='';
		$braintree_publickey='';
		$braintree_privatekey='';
		$paypal_appid='';
		$paypal_appkey='';
		$paypal_option=!empty(settings("paypal_option"))?settings("paypal_option"):"1";
        if($paypal_option=='1'){
			$paypal_mode='test';          
			$paypal_email=!empty(settings("sandbox_email"))?settings("sandbox_email"):"";
			$braintree_key=!empty(settings("braintree_key"))?settings("braintree_key"):"";
			$braintree_merchant=!empty(settings("braintree_merchant"))?settings("braintree_merchant"):"";
			$braintree_publickey=!empty(settings("braintree_publickey"))?settings("braintree_publickey"):"";
			$braintree_privatekey=!empty(settings("braintree_privatekey"))?settings("braintree_privatekey"):"";
			$paypal_appid=!empty(settings("paypal_appid"))?settings("paypal_appid"):"";
			$paypal_appkey=!empty(settings("paypal_appkey"))?settings("paypal_appkey"):"";
        }
        if($paypal_option=='2'){
			$paypal_mode='live';
			$paypal_email=!empty(settings("live_email"))?settings("live_email"):"";
			$braintree_key=!empty(settings("live_braintree_key"))?settings("live_braintree_key"):"";
			$braintree_merchant=!empty(settings("live_braintree_merchant"))?settings("live_braintree_merchant"):"";
			$braintree_publickey=!empty(settings("live_braintree_publickey"))?settings("live_braintree_publickey"):"";
			$braintree_privatekey=!empty(settings("live_braintree_privatekey"))?settings("live_braintree_privatekey"):"";
			$paypal_appid=!empty(settings("live_paypal_appid"))?settings("live_paypal_appid"):"";
			$paypal_appkey=!empty(settings("live_paypal_appkey"))?settings("live_paypal_appkey"):"";		  
        }
		$paypay = array(
          'mode' => $paypal_mode,
          'email' => $paypal_email,
          'braintree_key' => $braintree_key,
		  'braintree_merchant' => $braintree_merchant,
		  'braintree_publickey' => $braintree_publickey,
		  'braintree_privatekey' => $braintree_privatekey,
		  'paypal_appid' => $paypal_appid,
		  'paypal_appkey' => $paypal_appkey,
        );
		
        
        $response['stripe']=$stripe;
        $response['razorpay']=$razorpay;
        $response['paypay']=$paypay;  
		
        $response_code = '200';
        $response_message = "";
        $response_data=$response;
        $result = $this->data_format($response_code,$response_message,$response_data);
        $this->response($result, REST_Controller::HTTP_OK);

      }
      else
      {
        $this->token_error();
      }
    }


    public function language_list_get()
       {
          if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $result=array();
                
                 $response['language_list']=$this->api->language_list();
                
                 $response_code = '200';
                 $response_message = "";
                 $response_data=$response;
                 $result = $this->data_format($response_code,$response_message,$response_data);
                 $this->response($result, REST_Controller::HTTP_OK);

              }
            else
            {
              $this->token_error();
            }
       }


       public function language_keywords_post()
       {
          if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
                $user_data = array();
                $user_data = $this->post();

                $response=array();
                $result=array();

                 if(!empty($user_data['language']))
                 {
                
                     $response['language_keywords']=$this->api->language_keywords($user_data['language']);

                     if(empty($response['language_keywords']))
                      {
                           $response_code = '201';
                           $response_message = "No Results found";
                      }
                      else
                      {
                           $response_code = '200';
                           $response_message = "";
                      }
                    
                     

                 }
                 else
                 {

                     $response_code='500';
                     $response_message='Inputs field missing';
                 }

                 $response_data=$response;
                 $result = $this->data_format($response_code,$response_message,$response_data);
                 $this->response($result, REST_Controller::HTTP_OK);

              }
            else
            {
              $this->token_error();
            }
       }


     public function home_get()
    {
       if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
        
           $doctor_list = $this->api->doctor_list();
           $specialization_list =$this->api->specialization_list();
		       $lab_list =$this->api->lab_list();
           $cliniclist =$this->api->read_clinic_list();

            $response=array();
            $result=array();
            $sresult=array();
			$lresult=array();

            $response['doctor_list']=$result;
            $response['specialization_list']=$sresult;
		      	$response['lab_list']=$lresult;
            $response['clinic_list']=$cliniclist;
			
			 $user_currency=get_user_currency_api($this->user_id);
          
          if (!empty($cliniclist)) {
            foreach ($cliniclist as $r => $crows) {
              $response['clinic_list'][$r]['currency']=currency_code_sign($crows['currency_code']);
            }		  
          }
          if (!empty($doctor_list)) {
            foreach ($doctor_list as $rows) {

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
              $data['is_favourite']=$this->api->is_favourite($rows['user_id'],$this->user_id);
              $data['currency']='₹';
              $data['price_type']=($rows['price_type']=='Custom Price')?'Paid':'Free';
              $data['slot_type']='per slot';
              $data['amount']=($rows['price_type']=='Custom Price')?$rows['amount']:'0';
              $result[]=$data;
            }

            $response['doctor_list']=$result;
        }
		
		if (!empty($lab_list)) {
          foreach ($lab_list as $rows) {

            $ldata['id']=$rows['user_id'];
            $ldata['username']=$rows['username'];
            $ldata['profileimage']=(!empty($rows['profileimage']))?$rows['profileimage']:'assets/img/user.png';
            $ldata['first_name']=ucfirst($rows['first_name']);
            $ldata['last_name']=ucfirst($rows['last_name']);
            $ldata['cityname']=$rows['cityname'];
            $ldata['countryname']=$rows['countryname'];
            $ldata['services']=$rows['services'];
            $ldata['rating_value']=$rows['rating_value'];
            $ldata['rating_count']=$rows['rating_count'];
            $ldata['is_favourite']=$this->api->is_favourite($rows['user_id'],$this->user_id);
           
            $lresult[]=$ldata;
          }

          $response['lab_list']=$lresult;
        }
       
       if (!empty($specialization_list)) {
            foreach ($specialization_list as $srows) {
              $sdata['id']=$srows['id'];
              $sdata['specialization_img']=base_url().$srows['specialization_img'];
              $sdata['specialization']=ucfirst($srows['specialization']);
              $sdata['sequence']=ucfirst($srows['sequence']);
              $sresult[]=$sdata;
            }

            $response['specialization_list']=$sresult;
        }

        if(empty($response['specialization_list']) && empty($response['doctor_list']) && empty($response['lab_list']))
        {
             $response_code = '201';
               $response_message = "No Results found";
        }
        else
        {
             $response_code = '200';
               $response_message = "";
        }

        $response_data=$response;
                     
        $result = $this->data_format($response_code,$response_message,$response_data);

        $this->response($result, REST_Controller::HTTP_OK);

       }
       else
        {
          $this->token_error();
        }



    }

     public function signin_post()
    {
         if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();
              $device_id='';
              $device_type='';

      
            if(!empty($user_data['email']) && !empty($user_data['password']))
            { 
              if(!empty($user_data['device_id']))
              {
                $device_id=$user_data['device_id'];
              }

              if(!empty($user_data['device_type']))
              {
                $device_type=$user_data['device_type'];
              }
                  $user_result = $this->api->is_valid_login($user_data['email'],$user_data['password'],$device_id,$device_type);
                  if(!empty($user_result['status']==1))
                  {
                      $response_code='200';
                      $response_message='';
                      $response['user_details']=$user_result;
                  }
                  else if(!empty($user_result['status']==2))
                  {

                      $response_code='500';
                      $response_message='Your account has been inactive.';
                  }
                  else
                  {
                      $response_code='500';
                      $response_message='Wrong login credentials.';
                  }
            }
            else
            {
                   $response_code='500';
                   $response_message='Inputs field missings'.$user_data['email'];
            }

             
             
             $result = $this->data_format($response_code,$response_message,$response);
             $this->response($result, REST_Controller::HTTP_OK);
         
         }
         else
          {
            $this->token_error();
          }
     }


     public function signup_post()
    {
         if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();
        
      if(!empty($user_data['first_name']) && !empty($user_data['last_name']) && !empty($user_data['email']) && !empty($user_data['mobileno']) && !empty($user_data['role']) && !empty($user_data['password']) && !empty($user_data['confirm_password']))
      {   

        $inputdata['first_name']=$user_data['first_name'];
        $inputdata['last_name']=$user_data['last_name'];
        $inputdata['email']=$user_data['email'];
        $inputdata['mobileno']=$user_data['mobileno'];
        $inputdata['username'] = generate_username($inputdata['first_name'].' '.$inputdata['last_name'].' '.$inputdata['mobileno']);
        $inputdata['role']=$user_data['role'];
        $inputdata['password']=md5($user_data['password']);
        $inputdata['confirm_password']=md5($user_data['confirm_password']);
        $inputdata['created_date']=date('Y-m-d H:i:s');


          $already_exits=$this->db->where('email',$inputdata['email'])->get('users')->num_rows();
          $already_exits_mobile_no=$this->db->where('mobileno',$inputdata['mobileno'])->get('users')->num_rows();
          if($already_exits >=1)
          {
                  $response_code='500';
                  $response_message='Your email address already exits';
          }
          else if($already_exits_mobile_no >=1)
          {
                  $response_code='500';
                  $response_message='Your mobileno already exits';
          }
          else
          {
              $results=$this->api->signup($inputdata);
              if($results==true)
              {   
                   $inputdata['id']=$this->db->insert_id();
                   $this->load->library('sendemail');
                   $this->sendemail->send_email_verification($inputdata);
                   $response_code='200';
                   $response_message='Registration success';
              }
             else
              {

                   $response_code='500';
                   $response_message='Registration failed';
                  
              } 

          }
      
            
        }
        else
        {
               $response_code='500';
               $response_message='Inputs field missing';
        }

             
           $result = $this->data_array_format($response_code,$response_message,$response);
           $this->response($result, REST_Controller::HTTP_OK);
         
         }
         else
          {
            $this->token_array_error();
          }
     }

     public function doctor_list_post()
     {
            if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $docresult=array();
                $user_data = array();
                $user_data = $this->post();

                $page = $user_data['page'];
                $limit = $user_data['limit'];

               
            
               $doctor_list_count = $this->api->doctor_lists($page,$limit,1);
               $doctor_list = $this->api->doctor_lists($page,$limit,2); 
  
                              
              if (!empty($doctor_list)) {
                foreach ($doctor_list as $rows) {
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
                  $data['currency']='₹';
                  $data['is_favourite']=$this->api->is_favourite($rows['user_id'],$this->user_id);
                  $data['price_type']=($rows['price_type']=='Custom Price')?'Paid':'Free';
                  $data['slot_type']='per slot';
                  $data['amount']=($rows['price_type']=='Custom Price')?$rows['amount']:'0';
                  $docresult[]=$data;
                }
            }

                $pages = !empty($page)?$page:1;
                $doctor_list_count = ceil($doctor_list_count/$limit);
                $next_page    = $pages + 1;
                $next_page    = ($next_page <=$doctor_list_count)?$next_page:-1;

                $response['doctor_list']=$docresult;
                $response['next_page']=$next_page;
                $response['current_page']=$page;
            
           
          
            if(empty($response['doctor_list']))
            {
                 $response_code = '201';
                 $response_message = "No Results found";
            }
            else
            {
                 $response_code = '200';
                 $response_message = "";
            }

            $response_data=$response;
                         
            $result = $this->data_format($response_code,$response_message,$response_data);

            $this->response($result, REST_Controller::HTTP_OK);

           }
           else
            {
              $this->token_error();
            }
     }

     public function doctor_search_post()
     {
            if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $docresult=array();
                $user_data = array();
                $user_data = $this->post();
                $response_data=array();

               if(!empty($user_data['page']) && !empty($user_data['limit'])){ 
                      $page = $user_data['page'];
                      $limit = $user_data['limit'];
					  $roles = $this->role; 
					  
                     $doctor_list_count = $this->api->doctor_search($user_data,$page,$limit,1,$roles);
                     $doctor_list = $this->api->doctor_search($user_data,$page,$limit,2,$roles); 
        
                                    
                    if (!empty($doctor_list)) {
                      foreach ($doctor_list as $rows) {
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
                        $data['currency']='₹';
                        $data['is_favourite']=$this->api->is_favourite($rows['user_id'],$this->user_id);
                        $data['price_type']=($rows['price_type']=='Custom Price')?'Paid':'Free';
                        $data['slot_type']='per slot';
                        $data['amount']=($rows['price_type']=='Custom Price')?$rows['amount']:'0';
						$data['role']=$rows['role'];
						if($rows['role']==1) { $role_txt = 'Doctor'; }
						else if($rows['role']==6) { $role_txt = 'Clinic'; }
						else { $role_txt = ''; }
						$data['role_txt']=$role_txt;
                        $docresult[]=$data;
                      }
                  }

                      $pages = !empty($page)?$page:1;
                      $doctor_list_count = ceil($doctor_list_count/$limit);
                      $next_page    = $pages + 1;
                      $next_page    = ($next_page <=$doctor_list_count)?$next_page:-1;
					
					 if($user_data['role'] == 6){
						$response['clinic_list']=$docresult;
						$listname = 'clinic_list';
					 } else {
						$response['doctor_list']=$docresult;
						$listname = 'doctor_list';
					 }
                      //$response['doctor_list']=$docresult;
                      $response['next_page']=$next_page;
                      $response['current_page']=$page;
                                  
                
                  //if(empty($response['doctor_list']))
					if(empty($response[$listname]))
					{
                       $response_code = '201';
                       $response_message = "No Results found";
					}
					else
					{
						   $response_code = '200';
						   $response_message = "";
					}

                  $response_data=$response;
                               
                  

                }
                else
                {
                  $response_code='500';
                  $response_message='Inputs field missing';
                }

                $result = $this->data_format($response_code,$response_message,$response_data);



                  $this->response($result, REST_Controller::HTTP_OK);

           }
           else
            {
              $this->token_error();
            }
     }

     public function specialization_list_post()
     {
            if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $result=array();
                $user_data = array();
                $user_data = $this->post();

                $page = $user_data['page'];
                $limit = $user_data['limit'];

                $response['specialization_list']=$result;
            
               $specialization_list_count = $this->api->specialization_lists($page,$limit,1);
               $specialization_list = $this->api->specialization_lists($page,$limit,2); 
  
                              
              if (!empty($specialization_list)) {
                foreach ($specialization_list as $rows) {
                  $data['id']=$rows['id'];
                  $data['specialization_img']=$rows['specialization_img'];
                  $data['specialization']=ucfirst($rows['specialization']);
                  $result[]=$data;
                }

                $pages = !empty($page)?$page:1;
                $specialization_list_count = ceil($specialization_list_count/$limit);
                $next_page    = $pages + 1;
                $next_page    = ($next_page <=$specialization_list_count)?$next_page:-1;

                $response['specialization_list']=$result;
                $response['next_page']=$next_page;
                $response['current_page']=$page;
            }
           
          
            if(empty($response['specialization_list']))
            {
                 $response_code = '201';
                 $response_message = "No Results found";
            }
            else
            {
                 $response_code = '200';
                 $response_message = "";
            }

            $response_data=$response;
                         
            $result = $this->data_format($response_code,$response_message,$response_data);

            $this->response($result, REST_Controller::HTTP_OK);

           }
           else
            {
              $this->token_error();
            }
     }

      public function doctor_preview_post()
      {
            if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $result=array();
                $user_data = array();
                $user_data = $this->post();
                $res=array();
                $doctor_details=$this->api->get_doctor_details($user_data['doctor_id']);
                $user_currency=get_user_currency_api($user_data['doctor_id']);
                $currency_code=!empty($user_currency['user_currency_code'])?$user_currency['user_currency_code']:'INR';
                $currency=currency_code_sign($currency_code);
                foreach ($doctor_details as $key => $value) {
                  $res[$key]=$value;
                  $res['currency']=$currency;
                  $res['currency_code']=$currency_code;
                  $res['is_favourite']=$this->api->is_favourite($user_data['doctor_id'],$this->user_id);
                }
                $response['doctor_details'] =$res;
                $response['education'] = $this->api->get_education_details($user_data['doctor_id']);
                $response['experience'] = $this->api->get_experience_details($user_data['doctor_id']);
                $response['awards'] = $this->api->get_awards_details($user_data['doctor_id']);
                $response['memberships'] = $this->api->get_memberships_details($user_data['doctor_id']);
                $response['registrations'] = $this->api->get_registrations_details($user_data['doctor_id']);
                $response['business_hours'] = $this->api->get_business_hours($user_data['doctor_id']);
                $response['reviews'] = $this->api->review_list_view($user_data['doctor_id']);

                 $response_code = '200';
                 $response_message = "";
                 $response_data=$response;
                 $result = $this->data_format($response_code,$response_message,$response_data);
                 $this->response($result, REST_Controller::HTTP_OK);

              }
            else
            {
              $this->token_error();
            }

       }

        public function clinic_preview_post()
      {
            if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $result=array();
                $user_data = array();
                $user_data = $this->post();
                $res=array();
               
                $doctor_details=$this->api->get_clinic_doctor_details($user_data['doctor_id']);
                
                $user_currency=get_user_currency_api($user_data['doctor_id']);
                $currency_code=!empty($user_currency['user_currency_code'])?$user_currency['user_currency_code']:'INR';
                $currency=currency_code_sign($currency_code);
                foreach ($doctor_details as $key => $value) {
                  $res[$key]=$value;
                  $res['currency']=$currency;
                  $res['currency_code']=$currency_code;
                  $res['is_favourite']=$this->api->is_favourite($user_data['doctor_id'],$this->user_id);
                }
                $response['doctor_details'] =$res;
                $response['education'] = $this->api->get_education_details($user_data['doctor_id']);
                $response['experience'] = $this->api->get_experience_details($user_data['doctor_id']);
                $response['awards'] = $this->api->get_awards_details($user_data['doctor_id']);
                $response['memberships'] = $this->api->get_memberships_details($user_data['doctor_id']);
                $response['registrations'] = $this->api->get_registrations_details($user_data['doctor_id']);
                $response['business_hours'] = $this->api->get_business_hours($user_data['doctor_id']);
                $response['reviews'] = $this->api->review_list_view($user_data['doctor_id']);

                 $response_code = '200';
                 $response_message = "";
                 $response_data=$response;
                 $result = $this->data_format($response_code,$response_message,$response_data);
                 $this->response($result, REST_Controller::HTTP_OK);

              }
            else
            {
              $this->token_error();
            }

       }

           public function doctor_details_post()
      {
            if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $result=array();
                $user_data = array();
                $user_data = $this->post();
                $res=array();
                $doctor_details=$this->api->get_clinic_doctor_details($user_data['doctor_id']);
                $user_currency=get_user_currency_api($user_data['doctor_id']);
                $currency_code=!empty($user_currency['user_currency_code'])?$user_currency['user_currency_code']:'INR';
                $currency=currency_code_sign($currency_code);
                foreach ($doctor_details as $key => $value) {
                  $res[$key]=strval($value);
                  $res['currency']=$currency;
                  $res['currency_code']=$currency_code;
                  $res['is_favourite']=$this->api->is_favourite($user_data['doctor_id'],$this->user_id);
                }
                
                $response['doctor_details'] =$res;
                $response['education'] = $this->api->get_education_details($user_data['doctor_id']);
                $response['experience'] = $this->api->get_experience_details($user_data['doctor_id']);
                $response['awards'] = $this->api->get_awards_details($user_data['doctor_id']);
                $response['memberships'] = $this->api->get_memberships_details($user_data['doctor_id']);
                $response['registrations'] = $this->api->get_registrations_details($user_data['doctor_id']);
                $response['business_hours'] = $this->api->get_business_hours($user_data['doctor_id']);
                $response['reviews'] = $this->api->review_list_view($user_data['doctor_id']);

                 $response_code = '200';
                 $response_message = "";
                 $response_data=$response;
                 $result = $this->data_format($response_code,$response_message,$response_data);
                 $this->response($result, REST_Controller::HTTP_OK);

              }
            else
            {
              $this->token_error();
            }

       }

       public function patient_profile_get()
       {
          if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $result=array();
                $user_data = array();
                $user_data = $this->post();

                 $response['patient_details']=$this->api->get_patient_details($this->user_id);
                 if($this->role == 5){
					$swhere=array('pharmacy_id' =>$this->user_id); 
					$response['pharmacy_details'] = $this->db->get_where('pharmacy_specifications',$swhere)->row_array();
				 }
                 $response_code = '200';
                 $response_message = "";
                 $response_data=$response;
                 $result = $this->data_format($response_code,$response_message,$response_data);
                 $this->response($result, REST_Controller::HTTP_OK);

              }
            else
            {
              $this->token_error();
            }
       }



      public function update_patient_profile_post()
     {
        if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();



        
          if(!empty($user_data['first_name']) && !empty($user_data['last_name']) && !empty($user_data['gender']) && !empty($user_data['dob']) && !empty($user_data['blood_group']) && !empty($user_data['address1']) && !empty($user_data['address2']) && !empty($user_data['country'])&& !empty($user_data['state'])&& !empty($user_data['city'])&& !empty($user_data['postal_code']))
          {   



                if($_FILES["profile_image"]["name"] != '')
               {
                   $config["upload_path"] = './uploads/profileimage/temp/';
                   $config["allowed_types"] = '*';
                   $this->load->library('upload', $config);
                   $this->upload->initialize($config);

					$_FILES["file"]["name"] = 'img_'.time().'.png';
					$_FILES["file"]["type"] = $_FILES["profile_image"]["type"];
					$_FILES["file"]["tmp_name"] = $_FILES["profile_image"]["tmp_name"];
					$_FILES["file"]["error"] = $_FILES["profile_image"]["error"];
					$_FILES["file"]["size"] = $_FILES["profile_image"]["size"];
				  
				  
					$base64string = str_replace('data:image/png;base64,', '', $_FILES["file"]["name"]);
					$base64string = str_replace(' ', '+', $base64string);
					$imgdata = base64_decode($base64string);
					$img_name = $i.time();
					// $img_name = $_FILES["user_file"]["name"][$i];
					$file_name_final=$_FILES["file"]["name"];
					move_uploaded_file($_FILES["file"]["tmp_name"],'uploads/profileimage/temp/'.$file_name_final);
					$source_image= 'uploads/profileimage/temp/'.$file_name_final; 
					$upload_url='uploads/profileimage/temp/';
					$inputdata['profileimage'] = $this->image_resize1(200,200,$source_image,'200x200_'.$file_name_final,$upload_url);
					
					// if($this->upload->do_upload('file'))
					// {
					   // $upload_data = $this->upload->data();
					  
						// $profile_img='uploads/profileimage/temp/'.$upload_data["file_name"];
						// $inputdata['profileimage']= $this->image_resize(200, 200, $profile_img, $upload_data["file_name"]);                                              
					// }
                  }

                $inputdata['first_name']=$user_data['first_name'];
                $inputdata['last_name']=$user_data['last_name'];
                $inputdata['is_updated']=1;

                $userdata['user_id']=$this->user_id;
                $userdata['gender']=$user_data['gender'];
                $userdata['dob']=date('Y-m-d',strtotime(str_replace('/', '-', $user_data['dob'])));
                $userdata['blood_group']=$user_data['blood_group'];
                $userdata['address1']=$user_data['address1'];
                $userdata['address2']=$user_data['address2'];
                $userdata['country']=$user_data['country'];
                $userdata['state']=$user_data['state'];
                $userdata['city']=$user_data['city'];
                $userdata['postal_code']=$user_data['postal_code'];
                $userdata['update_at']=date('Y-m-d H:i:s');

                $results=$this->api->update_patient_profile($inputdata,$userdata,$this->user_id);

            if($results==true)
            {
                 $response_code='200';
                 $response_message='Profile successfully updated';            
            }
           else
            {
                $response_code='500';
                $response_message='Profile update failed';  
            } 
      
            
          }
          else
          {
                 $response_code='500';
                 $response_message='Inputs field missing';
          }

          $response['patient_details']=$this->api->get_patient_details($this->user_id);

             
           $result = $this->data_format($response_code,$response_message,$response);
           $this->response($result, REST_Controller::HTTP_OK);
         
         }
         else
          {
            $this->token_error();
          }
     }


     public function update_doctor_profile_post()
     {
        if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();

              $educations=json_decode($user_data['educations']);
              $experiences=json_decode($user_data['experiences']);
              $awards=json_decode($user_data['awards']);
              $memberships=json_decode($user_data['memberships']);
              $registrations=json_decode($user_data['registrations']);

               

        
          if(!empty($user_data['first_name']) && !empty($user_data['last_name']) && !empty($user_data['gender']) && !empty($user_data['dob'])  && !empty($user_data['address1']) && !empty($user_data['address2']) && !empty($user_data['country'])&& !empty($user_data['state'])&& !empty($user_data['city'])&& !empty($user_data['postal_code']))
          {   



                if($_FILES["profile_image"]["name"] != '')
               {
                   $config["upload_path"] = './uploads/profileimage/temp/';
                   $config["allowed_types"] = '*';
                   $this->load->library('upload', $config);
                   $this->upload->initialize($config);

                          $_FILES["file"]["name"] = 'img_'.time().'.png';
                          $_FILES["file"]["type"] = $_FILES["profile_image"]["type"];
                          $_FILES["file"]["tmp_name"] = $_FILES["profile_image"]["tmp_name"];
                          $_FILES["file"]["error"] = $_FILES["profile_image"]["error"];
                          $_FILES["file"]["size"] = $_FILES["profile_image"]["size"];
                        if($this->upload->do_upload('file'))
                        {
                           $upload_data = $this->upload->data();
                          
                            $profile_img='uploads/profileimage/temp/'.$upload_data["file_name"];
                            $inputdata['profileimage']= $this->image_resize(200, 200, $profile_img, $upload_data["file_name"]);
                            
                            
                                                                         
                        }
                  }

                $inputdata['first_name']=$user_data['first_name'];
                $inputdata['last_name']=$user_data['last_name'];
                $inputdata['is_updated']=1;

                $userdata['user_id']=$this->user_id;
                $userdata['gender']=$user_data['gender'];
                $userdata['dob']=date('Y-m-d',strtotime(str_replace('/', '-', $user_data['dob'])));
                $userdata['biography']=$user_data['biography'];
                $userdata['blood_group']=$user_data['blood_group'];
               // $userdata['online']=$user_data['online'];
               // $userdata['clinic']=$user_data['clinic'];
                $userdata['clinic_name']=$user_data['clinic_name'];
                $userdata['clinic_address']=$user_data['clinic_address'];
                $userdata['address1']=$user_data['address1'];
                $userdata['address2']=$user_data['address2'];
                $userdata['country']=$user_data['country'];
                $userdata['state']=$user_data['state'];
                $userdata['city']=$user_data['city'];
                $userdata['postal_code']=$user_data['postal_code'];
                $userdata['register_no']=$user_data['register_no'];

                $userdata['price_type']=$user_data['price_type'];
                $userdata['amount']=$user_data['amount'];
                $userdata['services']=$user_data['services'];
                $userdata['specialization']=$user_data['specialization'];

				if (isset($user_data['clinicname']) && $user_data['clinicname'] != '') {
				  $userdata['clinicname'] = $user_data['clinicname'];
				} else {
				  $userdata['clinicname'] = '';
				}


                $userdata['update_at']=date('Y-m-d H:i:s');



                $results=$this->api->update_patient_profile($inputdata,$userdata,$this->user_id);
				
            if($results==true)
            {

                 $where = array('user_id' => $this->user_id);
                 $this->db->delete('education_details',$where); 

                 foreach ($educations as $row) {
                  
                  $edudata = array('user_id' => $this->user_id,
                                        'degree' => $row->degree,
                                        'institute' => $row->institute,
                                        'year_of_completion'=>$row->year_of_completion);
                       $this->db->insert('education_details', $edudata); 

                    
               }

                $where = array('user_id' => $this->user_id);
                $this->db->delete('experience_details',$where);

                foreach ($experiences as $row) {
                  
                 $expdata = array('user_id' => $this->user_id,
                                        'hospital_name' => $row->hospital_name,
                                        'from' => $row->from,
                                        'to' => $row->to,
                                        'designation'=>$row->designation);
                       $this->db->insert('experience_details', $expdata);

                    
               }

               $where = array('user_id' => $this->user_id);
               $this->db->delete('awards_details',$where); 
                    
                

                foreach ($awards as $row) {
                       $awadata = array('user_id' =>  $this->user_id,
                                        'awards' => $row->awards,
                                        'awards_year' => $row->awards_year);
                       $this->db->insert('awards_details', $awadata); 
                    }


              $where = array('user_id' => $this->user_id);
              $this->db->delete('memberships_details',$where); 
                    
                  
                    
                 foreach ($memberships as $row) {
                       $memdata = array('user_id' => $this->user_id,
                                        'memberships' => $row->memberships);
                       $this->db->insert('memberships_details', $memdata); 
                    }

               $where = array('user_id' => $this->user_id);
               $this->db->delete('registrations_details',$where); 
                    
                    
                   
                    foreach ($registrations as $row) {
                       $regdata = array('user_id' => $this->user_id,
                                        'registrations' => $row->registrations,
                                        'registrations_year' => $row->registrations_year);
                       $this->db->insert('registrations_details', $regdata); 
                    }



                


                 $response_code='200';
                 $response_message='Profile successfully updated';            
            }
           else
            {
                $response_code='500';
                $response_message='Profile update failed';  
            } 
      
            
          }
          else
          {
                 $response_code='500';
                 $response_message='Inputs field missing';
          }
			$get_role=$this->api->doctor_role($user_data['doctor_id']);
          $response['doctor_details']=$this->api->doctor_details_profile($this->user_id,$get_role['role']);

             
           $result = $this->data_format($response_code,$response_message,$response);
           $this->response($result, REST_Controller::HTTP_OK);
         
         }
         else
          {
            $this->token_error();
          }
     }

     public function image_resize($width=0,$height=0,$image_url,$filename)

    {

      $source_path = $image_url;

      list($source_width, $source_height, $source_type) = getimagesize($source_path);

      switch ($source_type) {

        case IMAGETYPE_GIF:
          $source_gdim ="";
          $source_gdim = imagecreatefromgif($source_path);

          break;

        case IMAGETYPE_JPEG:

          $source_gdim = imagecreatefromjpeg($source_path);

          break;

        case IMAGETYPE_PNG:

          $source_gdim = imagecreatefrompng($source_path);

          break;

      }

      $source_aspect_ratio = $source_width / $source_height;



       $desired_aspect_ratio = $width / $height;



      if ($source_aspect_ratio > $desired_aspect_ratio) {

        /*

         * Triggered when source image is wider

         */



        $temp_height = $height;

        $temp_width = ( int ) ($height * $source_aspect_ratio);

      } else {

        /*

         * Triggered otherwise (i.e. source image is similar or taller)

         */

        $temp_width = $width;

        $temp_height = ( int ) ($width / $source_aspect_ratio);

      }



      /*

       * Resize the image into a temporary GD image

       */

      $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
$source_gdim ="";
      imagecopyresampled(

        $temp_gdim,

        $source_gdim,

        0, 0,

        0, 0,

        $temp_width, $temp_height,

        $source_width, $source_height

      );
      $x0 = ($temp_width - $width) / 2;

      $y0 = ($temp_height - $height) / 2;

      $desired_gdim = imagecreatetruecolor($width, $height);

      imagecopy(

        $desired_gdim,

        $temp_gdim,

        0, 0,

        $x0, $y0,

        $width, $height

      );
      $image_url =  "uploads/profileimage/".$width."_".$height."_".$filename."";

      if($source_type ==IMAGETYPE_PNG){
        imagepng($desired_gdim,$image_url);
      }
      if ($source_type ==IMAGETYPE_JPEG) {
        imagejpeg($desired_gdim,$image_url);
      }
      return $image_url;
    }

     public function check_currentpassword($user_id,$current_password)
  {
        $result = $this->api->check_currentpassword($current_password,$user_id);
         if ($result > 0) {
                   return '1';
           } else {
                   return '2';
           }
           
         
  }

     public function change_password_post()
    {

       if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();

        
          if(!empty($user_data['current_password']) && !empty($user_data['password']) && !empty($user_data['confirm_password']))
          {   

              $valid=$this->check_currentpassword($this->user_id,$user_data['current_password']);
              if($valid=='1')
              {
                  if($user_data['password']==$user_data['confirm_password'])
                  {
                        $inputdata['password']=md5($user_data['password']);
                        $inputdata['confirm_password']=md5($user_data['confirm_password']);
                        $result=$this->api->update($inputdata,$this->user_id);
                            if($result==true)
                            {
                                 $response_code='200';
                                 $response_message='Password successfully updated';                   
                            }
                           else
                            {
                                $response_code='500';
                                $response_message='Password changed failed';
                            } 
                  }
                  else
                  {
                      $response_code='500';
                      $response_message='Your password does not match';
                  }
              }
              else
              {
                  $response_code='500';
                  $response_message='Your current password is invalid';  
              }
    
          }
          else
          {
                 $response_code='500';
                 $response_message='Inputs field missing';
          }

             
           $result = $this->data_array_format($response_code,$response_message,$response);
           $this->response($result, REST_Controller::HTTP_OK);
         
         }
         else
          {
            $this->token_array_error();
          }
            
    }

    public function forgot_password_post()
    {
     if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();

        if(!empty($user_data['email'])){

      $query=$this->db->where('email',$user_data['email'])->get('users');
      $user_details=$query->row_array();
      $already_exits=$query->num_rows();
     
            if($already_exits >=1)
            {
                    $inputdata['email']=$user_data['email'];
                    $inputdata['expired_reset']=date('Y-m-d H:i:s', strtotime("+3 hours"));
                    $inputdata['forget']=urlencode($this->encryptor('encrypt',$user_details['email'].time()));
                    $this->api->update($inputdata,$user_details['id']);
                    $user_details['url']=$inputdata['forget'];
                    $this->load->library('sendemail');
                    $this->sendemail->send_resetpassword_email($user_details);
                    $response_code='200';
                    $response_message='Your reset password email sent successfully';
            }
            
            else
            {
                    $response_code='200';
                    $response_message='Your email address not registered';
                              

            }
      

         }
        else
        {
               $response_code='500';
               $response_message='Inputs field missing';
        }

           $result = $this->data_array_format($response_code,$response_message,$response);
           $this->response($result, REST_Controller::HTTP_OK);
         
     }
     else
      {
        $this->token_array_error();
      }
    } 


    public function master_post()
    {
      if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();

              if($user_data['type']==1)
              {
                 $country=$this->db->get('country')->result_array();
                  $country_data=array();
                 foreach ($country as $rows) {
                    $cdata['value']=$rows['countryid'];
                    $cdata['label']=$rows['country'];
                    $cdata['phonecode']=$rows['phonecode'];
					
                    $country_data[]=$cdata;
                 }

                 $response['list']=$country_data;
               }

               if($user_data['type']==2)
              {
                 $swhere=array('countryid' =>$user_data['id']);
                 $state=$this->db->get_where('state',$swhere)->result_array();
                 $state_data=array();
                 foreach ($state as $srows) {
                    $sdata['value']=$srows['id'];
                    $sdata['label']=$srows['statename'];
                    $state_data[]=$sdata;
                 }

                 $response['list']=$state_data;
               }

               if($user_data['type']==3)
              {
                    $cwhere=array('stateid' =>$user_data['id']);
                   $city=$this->db->get_where('city',$cwhere)->result_array();
                   $city_data=array();
                   foreach ($city as $cirows) {
                      $cidata['value']=$cirows['id'];
                      $cidata['label']=$cirows['city'];
                      $city_data[]=$cidata;
                   }

                  $response['list']=$city_data;
               }


                if($user_data['type']==4)
              {
                    $spwhere=array('status' =>1);
                   $specialization=$this->db->get_where('specialization',$spwhere)->result_array();
                   $specialization_data=array();
                   foreach ($specialization as $sprows) {
                      $spdata['value']=$sprows['id'];
                      $spdata['label']=$sprows['specialization'];
                      $specialization_data[]=$spdata;
                   }

                  $response['list']=$specialization_data;
               }

              

               $response_code='200';
               $response_message='';    

               $result = $this->data_format($response_code,$response_message,$response);
               $this->response($result, REST_Controller::HTTP_OK);

         }
       else
        {
          $this->token_error();
        }
                 
    }




Public function available_time_slots_post(){

  if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
    $data=array();
    $user_data = array();
    $response_data=array();
    $user_data = $this->post();
    $token='0';
    if(!empty($user_data['slot']) && !empty($user_data['day_id']))
    {  

      if(!empty($user_data['start_times']) && !empty($user_data['end_time']))
      {
        $start=strtotime($user_data['start_times']);
        $end=strtotime($user_data['end_time']);
        $slots=$user_data['slot'];
         if($slots >= 5){
            for ($i=$start;$i<=$end;$i = $i + $slots*60){
             $tdatas[]=date('H:i:s',$i);   
           }   
         }else{
           for ($i=$start;$i<=$end;$i = $i + 60*60){
             $tdatas[]=date('H:i:s',$i);   
           }       
         }

         $token= count($tdatas)-1;
      }

       $slot =$user_data['slot'];  
      @$day_id =$user_data['day_id'];  
      $start=strtotime('00:00');
      $end=strtotime('24:00');
      $user_id = $this->user_id;
      if($slot >= 5){
        for ($i=$start;$i<=$end;$i = $i + $slot*60){
          $res['label']= date('g:i A',$i);
          $res['value']= date('H:i:s',$i);
          $res['added'] = 'false';
          
          $where = array('start_time'=>$res['value'],'user_id'=>$user_id,'day_id' =>$day_id);
          $result =$this->db->get_where('schedule_timings',$where)->row_array();


          if($result['slot'] != $slot){

            $wh = array('slot'=>$result['slot'],'user_id'=>$user_id);
            $this->db->delete('schedule_timings',$wh);
          }


          $where = array('end_time'=>$res['value'],'user_id'=>$user_id ,'day_id' =>$day_id);
          $result =$this->db->get_where('schedule_timings',$where)->row_array();


          if(!empty($user_data['start_time']) ){
            if($user_data['start_time'] == $res['value'] || strtotime($user_data['start_time']) > strtotime($res['value'])){
              $res['added'] = 'true';
            }
          }

          if(!empty($user_data['end_time']) ){
            $end_times=strtotime($user_data['end_time']);
            $end_time=($end_times - (($slot*60)));
            $end_time=date('H:i:s',$end_time);

            if($end_time == $res['value'] || strtotime($end_time) > strtotime($res['value'])){
              $res['added'] = 'true';
            }
          }


          $datas[]=$res;
        }   
      }else{

        for ($i=$start;$i<=$end;$i = $i + 60*60){
          $res['label']= date('g:i A',$i);
          $res['value']= date('H:i:s',$i);
          $res['added'] = 'false';
          
          $where = array('start_time'=>$res['value'],'day_id'=>$user_data['day_id'],'user_id'=>$user_id);
          $result =$this->db->get_where('schedule_timings',$where)->row_array();

          $where = array('end_time'=>$res['value'],'day_id'=>$user_data['day_id'],'user_id'=>$user_id);
          $result =$this->db->get_where('schedule_timings',$where)->row_array();


          if(!empty($user_data['start_time']) ){
            if($user_data['start_time'] == $res['value'] || strtotime($user_data['start_time']) > strtotime($res['value']) ){
              $res['added'] = 'true';
            }
          }

          if(!empty($user_data['end_time']) ){
            $end_times=strtotime($user_data['end_time']);
            $end_time=($end_times - ((60*60)));
            $end_time=date('H:i:s',$end_time);

            if($end_time == $res['value'] || strtotime($end_time) > strtotime($res['value'])){
              $res['added'] = 'true';
            }
          }

          $datas[]=$res;
        }       
      }
      $resdatas=array();
      if(!empty($datas)){
       foreach ($datas as $arows) {
        if($arows['added']=='false')
        {
          $respo['label']= $arows['label'];
          $respo['value']= $arows['value'];

          $resdatas[]=$respo;
        }
       }
      }else{}

      $response_schedule['schedule_list']=$resdatas;
      $response_schedule['token']=strval($token);

       $response_code = '200';
       $response_message = "";
       $response_data=$response_schedule;  

      
    }
    else
    {
      $response_code='500';
      $response_message='Inputs field missing';
    }


    $result = $this->data_format($response_code,$response_message,$response_data);
    $this->response($result, REST_Controller::HTTP_OK);

  }
  else
  {
    $this->token_error();
  }
}


public function add_schedule_post()
{
   if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

     $data=array();
     $user_data = array();
     $response=array();
     $user_data = $this->post();
     $inputdata=array();
     $results=false;
 

if(!empty($user_data['booking_details']) && !empty($user_data['day_id']) && !empty($this->time_zone)){

    $day_id=json_decode($user_data['day_id'],true);
    $booking_details=json_decode($user_data['booking_details'],true);
    
    $inputdata['user_id'] =$this->user_id;
    $inputdata['time_zone'] =$this->time_zone;
    $day_name='';
  for ($j=0; $j < count($day_id) ; $j++) {

    $this->db->where('user_id',$this->user_id)->where('day_id',$day_id[$j]);
    $this->db->delete('schedule_timings');


    switch ($day_id[$j]) {
       case '1':
        $day_name='Sunday';
         break;
       case '2':
        $day_name='Monday';
         break;
         case '3':
        $day_name='Tuesday';
         break;
         case '4':
        $day_name='Wednesday';
         break;
         case '5':
        $day_name='Thursday';
         break;
         case '6':
        $day_name='Friday';
         break;
         case '7':
        $day_name='Saturday';
         break;
       default:
        $day_name='';
         break;
     } 
   
   $inputdata['day_id']=$day_id[$j];
   $inputdata['day_name']=$day_name;

    
 
  for ($i=0; $i < count($booking_details) ; $i++) { 

   $inputdata['start_time'] = date('H:i:s',strtotime($booking_details[$i]['start_time_label'])); 
   $inputdata['end_time'] = date('H:i:s',strtotime($booking_details[$i]['end_time_label'])); 
   $inputdata['sessions']=$booking_details[$i]['session_id']; 
   $inputdata['token']=$booking_details[$i]['token'];  
   $inputdata['slot']=$booking_details[$i]['slot'];

  
   
      $count = $this->db->get_where('schedule_timings',array('user_id'=>$inputdata['user_id'],'start_time'=>$inputdata['start_time'],'end_time'=>$inputdata['end_time'],'sessions'=>$inputdata['sessions'],'token'=>$inputdata['token'],'day_id'=>$inputdata['day_id']))->num_rows();
      if($count == 0){
        $this->db->insert('schedule_timings',$inputdata);
        $results=($this->db->affected_rows()!= 1)?false:true;
      }   
   }

 }

     
     if($results==true)
    {
         $response_code='200';
         $response_message='Schedule timings added successfully';           
    }
    else
    {
        $response_code='500';
        $response_message='Schedule timings added failed';  
    } 

    

 }
  else
  {     
    if(empty($this->time_zone) && !empty($user_data['day_id']))
    {
        $day_id=json_decode($user_data['day_id'],true);
        for ($j=0; $j < count($day_id) ; $j++) {
            $this->db->where('user_id',$this->user_id)->where('day_id',$day_id[$j]);
            $this->db->delete('schedule_timings');
        }

         $response_code='200';
         $response_message='Schedule timings updated successfully';     
    }
    else
    {
          $response_code='500';
          $response_message='Inputs field missing';
    }
         
  }

     $result = $this->data_array_format($response_code,$response_message,$response);
     $this->response($result, REST_Controller::HTTP_OK);
              
  }
 else
  {
    $this->token_array_error();
  }

}



public function get_schedule_post()
{
 $resdata=array();
 if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

         $data=array();
         $user_data = array();
         $response=array();
         $user_data = $this->post();
         $inputdata=array();

      if(!empty($user_data['day_id'])){

              $where = array('day_id'=>$user_data['day_id'],'user_id'=>$this->user_id);
              $data = $this->db->get_where('schedule_timings',$where)->result_array();
              $slot='';
              if(!empty($data))
              {
                foreach ($data as $rows) {
                  $res['id']=$rows['id'];
                  $res['user_id']=$rows['user_id'];
                  $res['session_id']=$rows['sessions'];
                  $res['token']=$rows['token'];
                  $res['start_time_label']=date('h:i A',strtotime($rows['start_time']));
                  $res['start_time_value']=$rows['start_time'];
                  $res['end_time_label']=date('h:i A',strtotime($rows['end_time']));
                  $res['end_time_value']=$rows['end_time'];
                  $res['day_id']=$rows['day_id'];
                  $res['day_name']=$rows['day_name'];
                  $res['time_zone']=$rows['time_zone'];
                  $res['slot']=$rows['slot'];
                  $slot=$rows['slot'];

                  $resdata[]=$res;
                }
                $response['schedule_list']=$resdata;
                $response_code = '200';
                 $response_message = "";
              }
              else
              {
                 $response['schedule_list']=array();
                 $response_code = '201';
                 $response_message = "No schedule found";
              }

              $response['already_day']=array();
              $already_day_id=$this->db->select('day_id')->where('user_id',$this->user_id)->group_by('day_id')->get('schedule_timings')->result_array();
               $response['already_day']='[]';
               if($already_day_id){
                $already_day=$this->multi_to_single($already_day_id);
                $response['already_day']='['.implode(',', $already_day).']';
               }
              $response['slot']=$slot;
              
        }
        else
        {
               $response_code='500';
               $response_message='Inputs field missing';
        }

     $result = $this->data_format($response_code,$response_message,$response);
     $this->response($result, REST_Controller::HTTP_OK);
              
  }
 else
  {
    $this->token_error();
  }
  

}


public function get_token_post()
{
        $response_code="";
        $response_message="";
       if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();
      if(!empty($user_data['schedule_date']) && !empty($user_data['doctor_id']) && !empty($this->time_zone))
      {      

            $schedule_date = $user_data['schedule_date'];
            $doctor_id = $user_data['doctor_id'];
            $day=date('D',strtotime(str_replace('/', '-', $schedule_date)));
            $day_id=0;
           switch ($day) {
              case 'Sun':
               $day_id=1;
                break;
              case 'Mon':
               $day_id=2;
                break;  
               case 'Tue':
               $day_id=3;
                break;
                 case 'Wed':
               $day_id=4;
                break;
                 case 'Thu':
               $day_id=5;
                break;
                 case 'Fri':
               $day_id=6;
                break;
                 case 'Sat':
               $day_id=7;
                break;
              default:
                $day_id=0;
                break;
            }

           $schedule_date=date('Y-m-d',strtotime(str_replace('/', '-', $schedule_date)));

            $schedule =  $this->api->get_schedule_timings($doctor_id,$day_id);
            $token_response=array();
           if(!empty($schedule)){ 
            $i=1; 
            foreach ($schedule as $rows) { 

                 $time_zone = $rows['time_zone'];                         
                 $current_timezone = $this->time_zone;

                
                 $start=strtotime($rows['start_time']);
                 $end=strtotime($rows['end_time']);
                 $datas=array();
                 if($rows['slot'] >= 5){
                    for ($j=$start;$j<= $end;$j = $j + $rows['slot']*60){
                     $datas[]=date('H:i:s',$j);   
                   }   
                 }else{
                   for ($j=$start;$j<= $end;$j = $j + 60*60){
                     $datas[]=date('H:i:s',$j);   
                   }       
                 }
                  $token_details=array();
                 for ($k=0; $k < $rows['token'] ; $k++) {
                  $l = $k+1;
                        $start_time =  converToTz($schedule_date.' '.$datas[$k],$current_timezone,$time_zone);
                       
                        if(date('Y-m-d H:i:s') < $start_time){
                            $token=$k+1;
                            $booked_session=get_booked_session($i,$token,$start_time,$doctor_id);
                            $tok_details['token_schedule_date']=date('Y-m-d',strtotime(str_replace('/', '-', $schedule_date)));
                            $tok_details['token_time_zone']=$rows['time_zone'];
                            $tok_details['token_start_time']=date('h:i A',strtotime($datas[$k]));
                            $tok_details['token_end_time']=date('h:i A',strtotime($datas[$l]));
                            $tok_details['token_session']=strval($i);
                            $tok_details['token_no']=strval($token);
                            $tok_details['is_selected']="0";
                            
                            
                            if($booked_session >= 1){
                              $tok_details['token_type']="1";
                            }
                            else{         
                         
                              $tok_details['token_type']="0";        
                            }

                            $token_details[]=$tok_details; 
                       }
                       

                     }

                    
                    $tokenresponse['session']=strval($i);
                    $tokenresponse['session_start_time']=date('h:i A',strtotime(converToTz($rows['start_time'],$current_timezone,$time_zone)));
                    $tokenresponse['session_end_time']=date('h:i A',strtotime(converToTz($rows['end_time'],$current_timezone,$time_zone)));
                    $tokenresponse['session_slot']=$rows['slot'];
                    $tokenresponse['token_details']=$token_details;
                  

                    $response[]=$tokenresponse;
                    $response_code = '200';
                    $response_message = "";

               $i++; }
            }
            else
            {
               $response_code = '201';
               $response_message = "No Token found";
            }

        }
        else
        {
               $response_code='500';
               $response_message='Inputs field missing';
        }


        $response_data=$response;
                     
        $result = $this->data_array_format($response_code,$response_message,$response_data);

        $this->response($result, REST_Controller::HTTP_OK);

      }
     else
      {
        $this->token_array_error();
      }


 }

 public function appoinments_calculation_post()
{
    if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
        $data=array();
        $user_data = array();
        $response=array();
        $user_data = $this->post();

        if(!empty($user_data['hourly_rate']))
        {
              $tax = !empty(settings("tax"))?settings("tax"):"0";
              $hourly_rate = $user_data['hourly_rate'];
                            
              $amount = $hourly_rate;
              $transcation_charge = ($amount * (2/100));
              $total_amount = $amount + $transcation_charge;
              $total_amount = $total_amount;
              $transcation_charge = $transcation_charge;


              $tax_amount = ($total_amount * $tax/100);
              $total_amount = $total_amount + $tax_amount;

              $user_currency=get_user_currency_api($this->user_id);

              $currency_code=!empty($user_currency['user_currency_code'])?$user_currency['user_currency_code']:'INR';
              $currency=currency_code_sign($currency_code);
                
               
              $response = array(
                'transcation_charge' => strval(round($transcation_charge,2)),
                'tax_amount' => strval(round($tax_amount,2)),
                'total_amount' => strval(round($total_amount,2)),
                'hourly_rate'=>strval(round($hourly_rate,2)),
                'currency_code'=>$currency_code,
                'currency'=>$currency,           
              ); 

              $response_code='200';  
              $response_message=''; 
              
             
        }
        else
        {
               $response_code='500';
               $response_message='Inputs field missing';
        }

        $response_data=$response;
                     
        $result = $this->data_format($response_code,$response_message,$response_data);

        $this->response($result, REST_Controller::HTTP_OK);

    }
    else
    {
      $this->token_error();
    }          
}


public function check_coupon_post(){

    
        $response_code="";
        $response_message="";
    if( (!empty($this->post('user_id')) || $this->user_id !=0 )  || ($this->default_token ==$this->api_token)) {
    $data=array();
    $user_data = array();
    $response=array();
    $user_data = $this->post();

     if(empty($user_data['user_id'])){
        $user_id=$this->user_id;
      }else{
        $user_id=$user_data['user_id'];
      }


    if(!empty($user_data['hourly_rate']) && !empty($user_data['coupon_code'])  && !empty($user_data['doctor_id']))
    {


    $coupon_code=$this->post('coupon_code');
     if(empty($user_data['user_id'])){
        $role=$this->role;
      }else{
        $role=$user_data['role'];
      }

     $doctor_id = $this->post('doctor_id');
     $doctor=$this->api->get_user_details($doctor_id);
     $speclization=$doctor['specialization'];


    
    $this->db->select('*');
    $this->db->where('coupon_code like binary ',$coupon_code);
    $this->db->where('coupon_status',1);
    $this->db->where('user_email',$this->email);
    $this->db->where('coupon_specialization',$speclization);
    $this->db->where('coupon_start_date <=',date('Y-m-d'));
    $this->db->where('coupon_expire_date >=',date('Y-m-d'));
    $query=$this->db->get('coupon');
    $coupon_details=$query->row_array();
    $already_exits=$query->num_rows();
    $user_id=$this->session->userdata('user_id');

    if($already_exits >0){

     $this->db->where('coupon_id',$coupon_details['coupon_id']);
     $used_coupon=$this->db->get('user_coupon')->num_rows();

     if($used_coupon >= $coupon_details['coupon_no_of_time_use'] ){

      $response['status']=500;
      $response['message']='This coupon validity expired'; 

     }else{

         $hourly_rate = $user_data['hourly_rate'];

       if($hourly_rate >=$coupon_details['coupon_min_order_amt']){

         $tax = !empty(settings("tax"))?settings("tax"):"0";
    
      
      $amount = $hourly_rate;
      $transcation_charge = ($amount * (2/100));
      $total_amount = $amount + $transcation_charge;
      $total_amount = $total_amount;
      $transcation_charge = $transcation_charge;


      $tax_amount = ($total_amount * $tax/100);
      $total_amount = $total_amount + $tax_amount;
     
      $percentage=$coupon_details['coupon_discount'];

      $discount_amount = ($percentage / 100) * $amount;

      $total_amount2=$total_amount - $discount_amount;

      $total_subscribe=$amount - $discount_amount;

      $user_currency=get_user_currency_api($this->user_id);

      $currency_code=!empty($user_currency['user_currency_code'])?$user_currency['user_currency_code']:'INR';
      $currency=currency_code_sign($currency_code);
      
      $order_id=time().rand();
      $response = array(
        'transcation_charge' => strval(round($transcation_charge,2)),
        'tax_amount' => strval(round($tax_amount,2)),
        'total_amount' => strval(round($total_amount2,2)),
        'hourly_rate'=>strval(round($hourly_rate,2)),
        'currency_code'=>$currency_code,
        'currency'=>$currency,
        'percentage' =>strval($percentage),
        'discount'=>strval($discount_amount),
        'coupon_id'=>strval($coupon_details['coupon_id']),
        'subscription_total'=>strval(round($total_subscribe,2)),  

      );
     
      $response_code='200';
      $response_message='This coupon code applied successfully';
    }else{
      $response_code='500';
      $response_message='This coupon is not applicable for this order';

    }
    }

    }else{

       $response_code='500';
       $response_message='Invalid coupon code';

    }


  }
    else
    {
     $response_code='500';
     $response_message='Inputs field missing';
   }

   $response_data=$response;
   
   $result = $this->data_format($response_code,$response_message,$response_data);

   $this->response($result, REST_Controller::HTTP_OK);

 }
 else
 {
  $this->token_error();
}  


  }

   

public function razorpay_lab_pay($user_data)
  {

      $data=array();
     $response=array();
     $nresponse=array();
     $device_type="";

     if(!empty($user_data['lab_id']) &&  !empty($user_data['amount']) && !empty($user_data['hourly_rate']) &&  !empty($user_data['appoinment_date'])  &&  !empty($user_data['booking_ids']) )
    {

        $lab_id = $user_data['lab_id']; 
      $amount = $user_data['amount']; 
        $token=$user_data['access_token'];

        
         

           $status='success';
        
            if($status == 'success'){

               $opentok = new OpenTok($this->tokbox_apiKey,$this->tokbox_apiSecret);
            // An automatically archived session:
              $sessionOptions = array(
                      // 'archiveMode' => ArchiveMode::ALWAYS,
                'mediaMode' => MediaMode::ROUTED
              );
              $new_session = $opentok->createSession($sessionOptions);
                     // Store this sessionId in the database for later use
              $tokboxsessionId= $new_session->getSessionId();

              $tokboxtoken=$opentok->generateToken($tokboxsessionId);

              /* Get Invoice id */

              $invoice = $this->db->order_by('id','desc')->limit(1)->get('payments')->row_array();
              if(empty($invoice)){
                $invoice_id = 1;   
              }else{
                $invoice_id = $invoice['id'];    
              }
              $invoice_id++;
              $invoice_no = 'I0000'.$invoice_id;

           // Store the Payment details

              $payments_data = array(
                'lab_id' => $lab_id,
        'patient_id' => $this->user_id,
        'booking_ids' => $user_data['booking_ids'],
               'invoice_no' => $invoice_no,
               'lab_test_date' => $user_data['appoinment_date'],
               'total_amount' => $amount,
               'currency_code' => 'INR',
               'txn_id' => $token,
               'order_id' => $orderIds,
               'transaction_status' => $token,  
               'payment_type' =>'Razor Pay',
               'tax'=>!empty(settings("tax"))?settings("tax"):"0",
               'tax_amount' => !empty($user_data['tax_amount'])?$user_data['tax_amount']:"0",
               'transcation_charge' => !empty($user_data['transcation_charge'])?$user_data['transcation_charge']:"0",
               'payment_status' => 1,
               'payment_date' => date('Y-m-d H:i:s'),
               );
              $this->db->insert('lab_payments',$payments_data);
              $payment_id = $this->db->insert_id();
              

              //Notification Starts Here
              $d_type=$this->db->query('SELECT * FROM users WHERE id='.$lab_id.'')->row();

              $patient_name=$this->db->query('SELECT * FROM users WHERE id='.$this->user_id.'')->row();

              $notifydata['message']='Lab Appoinment booked by '.$patient_name->first_name;
              $notifydata['notifications_title']='';
              $nresponse['type']='Booking';
              $notifydata['additional_data'] = $nresponse;
                      
              $notifydata['include_player_ids']=$d_type->device_id;

              if(!empty($notifydata['include_player_ids']))
              {
                if($d_type->device_type=='Android')
                {         
                  sendFCMNotification($notifydata);
                }
                if($d_type->device_type=='IOS')
                {
                  sendiosNotification($notifydata);
                }
              }
              //Notification Ends Here
             
               
               $response['code']='200';
               $response['message']='Transaction success';

           }else{

               $response['code']='500';
               $response['message']='Transcation failuresss';
               
          }

      }
      else
      {
             $response['code']='500';
             $response['message']='Inputs field missing';
      }


      

       return json_encode($response);

  }



public function braintree_lab_pay($user_data)
  {

    $data=array();
    $response=array();

    if(!empty($user_data['lab_id']) &&  !empty($user_data['amount']) && !empty($user_data['hourly_rate']) &&  !empty($user_data['appoinment_date'])  &&  !empty($user_data['booking_ids']) )
    {

      $lab_id = $user_data['lab_id']; 
      $amount = $user_data['amount']; 
      $user_currency=get_user_currency_api($this->user_id); 
      $currency_code=!empty($user_currency['user_currency_code'])?$user_currency['user_currency_code']:'INR';
  
      $payload_nonce = $user_data['payload_nonce'];
      
      $user_currency=get_user_currency_api($this->user_id);
      $currency_code=$user_currency['user_currency_code'];
        
      $amount= get_doccure_currency($user_data['amount'],$currency_code,default_currency_code());
      $amount=number_format($amount,2, '.', '');
      
      require_once(APPPATH . '../vendor/autoload.php');
      require_once(APPPATH . '../vendor/braintree/braintree_php/lib/Braintree.php');
  
  $paypal_option=!empty(settings("paypal_option"))?settings("paypal_option"):"1";
  if($paypal_option=='1'){
    $paypal_mode='sandbox';  
    $braintree_merchant=!empty(settings("braintree_merchant"))?settings("braintree_merchant"):"";
    $braintree_publickey=!empty(settings("braintree_publickey"))?settings("braintree_publickey"):"";
    $braintree_privatekey=!empty(settings("braintree_privatekey"))?settings("braintree_privatekey"):"";
  }
  if($paypal_option=='2'){
    $paypal_mode='live';
    $braintree_merchant=!empty(settings("live_braintree_merchant"))?settings("live_braintree_merchant"):"";
    $braintree_publickey=!empty(settings("live_braintree_publickey"))?settings("live_braintree_publickey"):"";
    $braintree_privatekey=!empty(settings("live_braintree_privatekey"))?settings("live_braintree_privatekey"):"";
  }
  //echo $paypal_mode."-".$braintree_merchant."-".$braintree_publickey."-".$braintree_privatekey;
  
  $gateway = new Braintree\Gateway([
    'environment' => $paypal_mode,
    'merchantId' => $braintree_merchant,
    'publicKey' => $braintree_publickey,
    'privateKey' => $braintree_privatekey
  ]);				
  
  //$merchantAccount = $gateway->merchantAccount()->find('3d58sy3grs86hmyz');
  //echo $merchantAccount; exit;
  
  //echo "<pre>"; print_r($gateway);
  
          if ($gateway) { 
        $orderIds = 'OD'.time().rand();
        
        //echo "Inside Gateway";
                  $result = $gateway->transaction()->sale([
                      'amount' => $amount,
                      'paymentMethodNonce' => $payload_nonce,
                      'orderId' => $orderIds,
                      'options' => [
                      'submitForSettlement' => True
                      ],
                  ]);
        
      //print_r($result);  echo "Success - ".$result->success; echo $result->message; exit;

      if ($result->success==1) {
                      $transaction_id = $result->transaction->id;
      
          //echo "\n\n transaction_id - ".$transaction_id."\n";
      
         $opentok = new OpenTok($this->tokbox_apiKey,$this->tokbox_apiSecret);
      // An automatically archived session:
        $sessionOptions = array(
            // 'archiveMode' => ArchiveMode::ALWAYS,
        'mediaMode' => MediaMode::ROUTED
        );
        $new_session = $opentok->createSession($sessionOptions);
           // Store this sessionId in the database for later use
        $tokboxsessionId= $new_session->getSessionId();

        $tokboxtoken=$opentok->generateToken($tokboxsessionId);

        /* Get Invoice id */

        $invoice = $this->db->order_by('id','desc')->limit(1)->get('payments')->row_array();
        if(empty($invoice)){
        $invoice_id = 1;   
        }else{
        $invoice_id = $invoice['id'];    
        }
        $invoice_id++;
        $invoice_no = 'I0000'.$invoice_id;

       // Store the Payment details

       $payments_data = array(
        'lab_id' => $lab_id,
        'patient_id' => $this->user_id,
        'booking_ids' => $user_data['booking_ids'],
        'invoice_no' => $invoice_no,
        'lab_test_date' => $user_data['appoinment_date'],
        'total_amount' => $amount,
        'currency_code' => $currency_code,
        'txn_id' => $user_data['txn_id'],
        'order_id' =>$orderIds,
        'transaction_status' => "success",  
        'payment_type' =>'CCavenue',
        'tax'=>!empty(settings("tax"))?settings("tax"):"0",
        'tax_amount' => !empty($user_data['tax_amount'])?$user_data['tax_amount']:"0",
        'transcation_charge' => !empty($user_data['transcation_charge'])?$user_data['transcation_charge']:"0",
        'payment_status' => 1,
        'payment_date' => date('Y-m-d H:i:s'),
    );
    $this->db->insert('lab_payments',$payments_data);
    $appointment_id = $this->db->insert_id(); 
    

    //Notification Starts Here
    $d_type=$this->db->query('SELECT * FROM users WHERE id='.$lab_id.'')->row();

    $patient_name=$this->db->query('SELECT * FROM users WHERE id='.$this->user_id.'')->row();

    $notifydata['message']='Lab Appointment booked by '.$patient_name->first_name;
    $notifydata['notifications_title']='';
    $nresponse['type']='Booking';
    $notifydata['additional_data'] = $nresponse;
    
    $notifydata['include_player_ids']=$d_type->device_id;

    if(!empty($notifydata['include_player_ids']))
    {
      if($d_type->device_type=='Android')
      {         
        sendFCMNotification($notifydata);
      }
      if($d_type->device_type=='IOS')
      {
        sendiosNotification($notifydata);
      }
    }
    //Notification Ends Here 
         
         $response['code']='200';
         $response['message']='Transaction success';
       } else {
        $response['code'] = '404';
        $response['message'] ='Transcation Failed... Please Try Again....';						
      }
    } else {
      $response['code'] = '404';
      $response['message'] ='Transcation Failed... Please Try Again....';;
    }		
 
}else
    {
           $response['code']='500';
           $response['message']='Inputs field missing';
    } 
     return json_encode($response);

}

public function checkout_post()
{
    if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
        $data=array();
        $user_data = array();
        $response=array();
        $user_data = $this->post();
 
        if(!empty($user_data['payment_type']))
        {
            if($user_data['payment_type']=='Free')
            {
                $details=$this->book_free_appoinment($user_data);
                $details=json_decode($details);
                $response_code=$details->code;
                $response_message=$details->message;
                
            }
            else
            {
              if($user_data['payment_method']=='1'){

                $details=$this->razor_pay($user_data);
                $details=json_decode($details);
                $response_code=$details->code;
                $response_message=$details->message;

              }elseif($user_data['payment_method']=='3'){

                $details=$this->stripe_pay($user_data);

                $details=json_decode($details);
                
                $response_code=$details->code;
                $response_message=$details->message;
                
              }elseif($user_data['payment_method']=='4'){

                $details=$this->braintree_pay($user_data);
                $details=json_decode($details);
                $response_code=$details->code;
                $response_message=$details->message;
                
              }
			  else
              {
                $details=$this->clinic_pay($user_data);
                $details=json_decode($details);
                $response_code=$details->code;
                $response_message=$details->message;
              }
            }
        }
        else
        {
               $response_code='500';
               $response_message='Inputs field missing';
        }

        $response_data=$response;
                     
        $result = $this->data_format($response_code,$response_message,$response_data);

        $this->response($result, REST_Controller::HTTP_OK);

    }
    else
    {
      $this->token_error();
    }          
}

public function checkout_lab_post()
{
  if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
    $data=array();
    $user_data = array();
    $response=array();
    $user_data = $this->post();
    
    if(!empty($user_data['payment_method']))
    {
         
          if($user_data['payment_method']=='1'){

            $details=$this->razorpay_lab_pay($user_data);
            $details=json_decode($details);
            $response_code=$details->code;
            $response_message=$details->message;

          }elseif($user_data['payment_method']=='3'){

            $details=$this->stripepay_lab_pay($user_data);

            $details=json_decode($details);
            
            $response_code=$details->code;
            $response_message=$details->message;
            
          }elseif($user_data['payment_method']=='4'){

            $details=$this->braintree_lab_pay($user_data);
            $details=json_decode($details);
            $response_code=$details->code;
            $response_message=$details->message;
            
          }
    else
          {
            $details=$this->clinic_lab_pay($user_data);
            $details=json_decode($details);
            $response_code=$details->code;
            $response_message=$details->message;
          }
        
    }
    else
    {
           $response_code='500';
           $response_message='Inputs field missing';
    }
      // $details=$this->lab_pay($user_data);
      // $details=json_decode($details);
      // $response_code=$details->code;
      // $response_message=$details->message;

   $response_data=$response;
   
   $result = $this->data_array_format($response_code,$response_message,$response_data);

   $this->response($result, REST_Controller::HTTP_OK);

 }
 else
 {
  $this->token_array_error();
}          
}

  public function book_free_appoinment($user_data)
  {


     $data=array();
     $response=array();
     $nresponse=array();
     $device_type ="";

    if(!empty($user_data['doctor_id']) && !empty($user_data['appoinment_date']) && !empty($user_data['appoinment_start_time']) && !empty($user_data['appoinment_end_time']) && !empty($user_data['appoinment_token']) && !empty($user_data['appoinment_session']) && !empty($user_data['appoinment_timezone']) && !empty($user_data['appointment_type']))
    {

         $paymentdata = array(
           'user_id' => $this->user_id,
           'doctor_id' => $user_data['doctor_id'],
           'payment_status' => 0,
           'payment_date' => date('Y-m-d H:i:s'),
           'currency_code' => 'USD'
         );
         $this->db->insert('payments',$paymentdata);
         $payment_id = $this->db->insert_id();

         
          $opentok = new OpenTok($this->tokbox_apiKey,$this->tokbox_apiSecret);
            // An automatically archived session:
          $sessionOptions = array(
                  // 'archiveMode' => ArchiveMode::ALWAYS,
            'mediaMode' => MediaMode::ROUTED
          );
          $new_session = $opentok->createSession($sessionOptions);
                 // Store this sessionId in the database for later use
          $tokboxsessionId= $new_session->getSessionId();

          $tokboxtoken=$opentok->generateToken($tokboxsessionId);

             
               $appointmentdata['payment_id'] =  $payment_id;   
               $appointmentdata['appointment_from'] = $this->user_id;
               $appointmentdata['appointment_to'] = $user_data['doctor_id'];
               $appointmentdata['from_date_time'] = $user_data['appoinment_date'].' '.date('H:i:s',strtotime($user_data['appoinment_start_time']));
               $appointmentdata['to_date_time'] = $user_data['appoinment_date'].' '.date('H:i:s',strtotime($user_data['appoinment_end_time']));
               $appointmentdata['appointment_date'] = $user_data['appoinment_date'];
               $appointmentdata['appointment_time'] = date('H:i:s',strtotime($user_data['appoinment_start_time']));
               $appointmentdata['appointment_end_time'] = date('H:i:s',strtotime($user_data['appoinment_end_time']));
               $appointmentdata['appoinment_token'] = $user_data['appoinment_token'];
               $appointmentdata['appoinment_session'] = $user_data['appoinment_session'];
               $appointmentdata['payment_method'] = '1';
               $appointmentdata['tokboxsessionId'] = $tokboxsessionId;
               $appointmentdata['tokboxtoken'] = $tokboxtoken;
               $appointmentdata['paid'] = 1;
               $appointmentdata['approved'] = 1;
                $appointmentdata['type'] = $user_data['appointment_type'];
               $appointmentdata['time_zone'] = $user_data['appoinment_timezone'];
               $appointmentdata['created_date'] = date('Y-m-d H:i:s');
               $this->db->insert('appointments',$appointmentdata);
               $appointment_id = $this->db->insert_id();
               $appoinments_details = $this->api->get_appoinment_call_details($appointment_id);
              if($this->role==1)
              {
                $notifydata['include_player_ids'] = $appoinments_details['patient_device_id'];
                $device_type = $appoinments_details['patient_device_type'];
                $nresponse['from_name']=$appoinments_details['doctor_name'];
              }
              if($this->role==2)
              {
                $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
                $device_type = $appoinments_details['doctor_device_type'];
                $nresponse['from_name']=$appoinments_details['patient_name'];
              }

              $notifydata['message']=$nresponse['from_name'].' has has booked appointment on '.date('d M Y',strtotime($user_data['appoinment_date']));
              $notifydata['notifications_title']='';
              $nresponse['type']='Booking';
              $notifydata['additional_data'] = $nresponse;
              if($device_type=='Android')
              {
                sendFCMNotification($notifydata);
              }
              if($device_type=='IOS')
              {
                sendiosNotification($notifydata);
              }
              
               
               $response['code']='200';
               $response['message']='Appoinments added successfully';

    }
    else
    {
           $response['code']='500';
           $response['message']='Inputs field missing';
    }

    return json_encode($response);



         
  }

  public function stripepay_lab_pay($user_data)
  {

      $data=array();
     $response=array();
     $stripe_secert_key="";
     $txnid="";
     $transaction_status="";
     $nresponse=array();
     $device_type="";
     $message="";

   
     if(!empty($user_data['lab_id']) &&  !empty($user_data['amount']) && !empty($user_data['hourly_rate']) &&  !empty($user_data['appoinment_date'])  &&  !empty($user_data['booking_ids']) )
    {

        $lab_id = $user_data['lab_id']; 
   
         $amount = $user_data['amount'];
         $token=$user_data['access_token'];
         $user_currency=get_user_currency_api($this->user_id); 
         $currency_code=!empty($user_currency['user_currency_code'])?$user_currency['user_currency_code']:'INR';

         $stripe_option=!empty(settings("stripe_option"))?settings("stripe_option"):"";
         if($stripe_option=='1'){
            $stripe_secert_key=!empty(settings("sandbox_rest_key"))?settings("sandbox_rest_key"):"";
         }
         if($stripe_option=='2'){
            $stripe_secert_key=!empty(settings("live_rest_key"))?settings("live_rest_key"):"";
         }
        
         

         try {

          \Stripe\Stripe::setApiKey($stripe_secert_key);
          $striperesponse = \Stripe\Charge::create([
              'amount' => ($amount * 100),
              'currency' => 'USD',
              'description' => 'Exam charge',
              'source' => $token,
          ]);

          $txnid = "-";
          $status='failure';

          if(!empty($striperesponse)){
           $transaction_status = $striperesponse; 
 
            if(!empty($striperesponse->id)){
              $txnid = $striperesponse->id;
              $status='success';
            }else{
              $txnid = '-';
              $status='failure';
              $message='Transaction failure!.Please try again';
            }
          }


          } catch (Exception $e) {
                    
                 $status='failure';
                $body = $e->getJsonBody();
                $err  = $body['error'];
                $message  ='Transaction failure!.Please try again';
                 
                    
          }
          
        
            if($status == 'success'){
        $orderIds = 'OD'.time().rand();

               $opentok = new OpenTok($this->tokbox_apiKey,$this->tokbox_apiSecret);
            // An automatically archived session:
              $sessionOptions = array(
                      // 'archiveMode' => ArchiveMode::ALWAYS,
                'mediaMode' => MediaMode::ROUTED
              );
              $new_session = $opentok->createSession($sessionOptions);
                     // Store this sessionId in the database for later use
              $tokboxsessionId= $new_session->getSessionId();

              $tokboxtoken=$opentok->generateToken($tokboxsessionId);

              /* Get Invoice id */

              $invoice = $this->db->order_by('id','desc')->limit(1)->get('payments')->row_array();
              if(empty($invoice)){
                $invoice_id = 1;   
              }else{
                $invoice_id = $invoice['id'];    
              }
              $invoice_id++;
              $invoice_no = 'I0000'.$invoice_id;

           // // Store the Payment details

           //    $payments_data = array(
           //      'lab_id' => $lab_id,
           //      'patient_id' => $this->user_id,
           //      'booking_ids' => $user_data['booking_ids'],
           //      'lab_test_date' => $user_data['appoinment_date'],
           //      'total_amount' => $amount,
           //      'currency_code' => $currency_code,
           //      'txn_id' => $txnid,
           //      'order_id' => $orderIds,
           //      'transaction_status' => "success",   
           //     'payment_type' =>'Stripe',
           //     'tax'=>!empty(settings("tax"))?settings("tax"):"0",
           //     'tax_amount' => !empty($user_data['tax_amount'])?$user_data['tax_amount']:"0",
           //     'transcation_charge' => !empty($user_data['transcation_charge'])?$user_data['transcation_charge']:"0",
           //     'payment_status' => 1,
           //     'payment_date' => date('Y-m-d H:i:s'),
           //     );
           //    $this->db->insert('lab_payments',$payments_data);
           //    $payment_id = $this->db->insert_id();


              // Store the Payment details

              $payments_data = array(
                'lab_id' => $lab_id,
                'patient_id' => $this->user_id,
                'booking_ids' => $user_data['booking_ids'],
                'lab_test_date' => $user_data['appoinment_date'],
                'total_amount' => $amount,
                'currency_code' => $currency_code,
                'txn_id' => $txnid,
                'order_id' => $orderIds,
                'transaction_status' => "success",   
               'payment_type' =>'Stripe',
               'tax'=>!empty(settings("tax"))?settings("tax"):"0",
               'tax_amount' => !empty($user_data['tax_amount'])?$user_data['tax_amount']:"0",
               'transcation_charge' => !empty($user_data['transcation_charge'])?$user_data['transcation_charge']:"0",
               'payment_status' => 1,
               'payment_date' => date('Y-m-d H:i:s'),
               );
              $this->db->insert('lab_payments',$payments_data);
              $payment_id = $this->db->insert_id();
        
        
          //Notification Starts Here
        $d_type=$this->db->query('SELECT * FROM users WHERE id='.$lab_id.'')->row();

        $patient_name=$this->db->query('SELECT * FROM users WHERE id='.$this->user_id.'')->row();

        $notifydata['message']='Lab Appoinment booked by '.$patient_name->first_name;
        $notifydata['notifications_title']='';
        $nresponse['type']='Booking';
        $notifydata['additional_data'] = $nresponse;
                
        $notifydata['include_player_ids']=$d_type->device_id;

        if(!empty($notifydata['include_player_ids']))
        {
          if($d_type->device_type=='Android')
          {         
            sendFCMNotification($notifydata);
          }
          if($d_type->device_type=='IOS')
          {
            sendiosNotification($notifydata);
          }
        }
        //Notification Ends Here
           
              

              
             
               $response['code']='200';
               $response['message']='Transaction success';

           }else{

               $response['code']='500';
               $response['message']=$message;
               
          }

      }
      else
      {
             $response['code']='500';
             $response['message']='Inputs field missing';
      }


      

       return json_encode($response);

  }


  public function razor_lab_pay($user_data)
  {

      $data=array();
     $response=array();
     $nresponse=array();
     $device_type="";

     if(!empty($user_data['lab_id']) &&  !empty($user_data['amount']) && !empty($user_data['hourly_rate']) &&  !empty($user_data['appoinment_date'])  &&  !empty($user_data['booking_ids']) )
    {

        $lab_id = $user_data['lab_id']; 
      $amount = $user_data['amount']; 
        $token=$user_data['access_token'];

        
         

           $status='success';
        
            if($status == 'success'){

               $opentok = new OpenTok($this->tokbox_apiKey,$this->tokbox_apiSecret);
            // An automatically archived session:
              $sessionOptions = array(
                      // 'archiveMode' => ArchiveMode::ALWAYS,
                'mediaMode' => MediaMode::ROUTED
              );
              $new_session = $opentok->createSession($sessionOptions);
                     // Store this sessionId in the database for later use
              $tokboxsessionId= $new_session->getSessionId();

              $tokboxtoken=$opentok->generateToken($tokboxsessionId);

              /* Get Invoice id */

              $invoice = $this->db->order_by('id','desc')->limit(1)->get('payments')->row_array();
              if(empty($invoice)){
                $invoice_id = 1;   
              }else{
                $invoice_id = $invoice['id'];    
              }
              $invoice_id++;
              $invoice_no = 'I0000'.$invoice_id;

           // Store the Payment details

              $payments_data = array(
                'lab_id' => $lab_id,
        'patient_id' => $this->user_id,
        'booking_ids' => $user_data['booking_ids'],
               'invoice_no' => $invoice_no,
               'lab_test_date' => $user_data['appoinment_date'],
               'total_amount' => $amount,
               'currency_code' => 'INR',
               'txn_id' => $token,
               'order_id' => $orderIds,
               'transaction_status' => $token,  
               'payment_type' =>'Razor Pay',
               'tax'=>!empty(settings("tax"))?settings("tax"):"0",
               'tax_amount' => !empty($user_data['tax_amount'])?$user_data['tax_amount']:"0",
               'transcation_charge' => !empty($user_data['transcation_charge'])?$user_data['transcation_charge']:"0",
               'payment_status' => 1,
               'payment_date' => date('Y-m-d H:i:s'),
               );
              $this->db->insert('lab_payments',$payments_data);
              $payment_id = $this->db->insert_id();
              

              
             
               
               $response['code']='200';
               $response['message']='Transaction success';

           }else{

               $response['code']='500';
               $response['message']='Transcation failuresss';
               
          }

      }
      else
      {
             $response['code']='500';
             $response['message']='Inputs field missing';
      }


      

       return json_encode($response);

  }
  

  public function stripe_pay_for_pharmacy($user_data)
  {

    $data=array();
     $response=array();
     $stripe_secert_key="";
     $device_type ="";
    //print_r($user_data);  
        
        //echo $user_data['amount']."AMOUT";
        //echo $ser_data['access_token']."ACESS";
        //echo $user_data['payment_method']."PAYMENT METHOD";
   
        //  exit;
    if(!empty($user_data['payment_method']) && !empty($user_data['amount']) && !empty($user_data['access_token']))
    {


         $amount = round($user_data['amount']);
         $token=$user_data['access_token'];

         $cartItems =  $user_data['cartItems'];   
      
         $stripe_option=!empty(settings("stripe_option"))?settings("stripe_option"):"";
         if($stripe_option=='1'){
            $stripe_secert_key=!empty(settings("sandbox_rest_key"))?settings("sandbox_rest_key"):"";
         }
         if($stripe_option=='2'){
            $stripe_secert_key=!empty(settings("live_rest_key"))?settings("live_rest_key"):"";
         }

         try {

          \Stripe\Stripe::setApiKey($stripe_secert_key);
          $striperesponse = \Stripe\Charge::create([
              'amount' => ($amount * 100),
              'currency' => 'USD',
              'description' => 'Exam charge',
              'source' => $token,
          ]);

          $txnid = "-";
          $status='failure';

          if(!empty($striperesponse)){
           $transaction_status = $striperesponse; 
 
            if(!empty($striperesponse->id)){
              $txnid = $striperesponse->id;
              $status='success';
            }else{
              $txnid = '-';
              $status='failure';
              $message='Transaction failure!.Please try again';
            }
          }


           if($status == 'success'){

               $opentok = new OpenTok($this->tokbox_apiKey,$this->tokbox_apiSecret);
            // An automatically archived session:
              $sessionOptions = array(
                      // 'archiveMode' => ArchiveMode::ALWAYS,
                'mediaMode' => MediaMode::ROUTED
              );
              $new_session = $opentok->createSession($sessionOptions);
                     // Store this sessionId in the database for later use
              $tokboxsessionId= $new_session->getSessionId();

              $tokboxtoken=$opentok->generateToken($tokboxsessionId);

              /* Get Invoice id */

             /*  $invoice = $this->db->order_by('id','desc')->limit(1)->get('payment')->row_array();
              if(empty($invoice)){
                $invoice_id = 1;   
              }else{
                $invoice_id = $invoice['id'];    
              }
              $invoice_id++;
              $invoice_no = 'I0000'.$invoice_id;    */ 

                $ordItemDetails['full_name']     = $user_data['full_name'];
                $ordItemDetails['address1'] = $user_data['address1'];
                
                $ordItemDetails['address2']     = $user_data['address2'];
                $ordItemDetails['state']     = $user_data['state'];
                $ordItemDetails['postal_code']     = $user_data['zipcode'];
              $ordItemDetails['city']     = $user_data['city'];
        
              $ordItemDetails['country']     = $user_data['country'];
                $ordItemDetails['payment_method']     = $user_data['payment_method'];
              $ordItemDetails['phoneno']     = $user_data['phoneno'];
              $ordItemDetails['total_amount']     = $user_data['amount'];
              
              $ordItemDetails['user_id']     = $user_data['user_id'];
              $ordItemDetails['pharmacy_id']     = $user_data['pharmacy_id'];
              $ordItemDetails['created_at']     = date('Y-m-d H:i:s');
              $ordItemDetails['currency']     = '$';
        
              
                $ordItemDetails['status'] = 1;

                $this->db->insert('order_user_details',$ordItemDetails);
                $orderId = $this->db->insert_id();
              foreach($cartItems as $item){  
              
                $ordItemData['user_order_id']     = $orderId;
                $ordItemData['user_id']     = $item['user_id'];
                $ordItemData['order_id']     = 'OD'.time().rand();
                $ordItemData['product_id']     = $item['product_id'];
                $ordItemData['product_name']     = $item['product_name'];
                $ordItemData['quantity']     = $item['qty'];
                $ordItemData['price']     = $item["price"];
                $ordItemData['subtotal']     = $item["subtotal"];
                $ordItemData['transaction_status'] = $transaction_status;
                $ordItemData['payment_type']  ='Stripe';
                $ordItemData['ordered_at']     =date('Y-m-d H:i:s');
                
                $this->db->insert('orders',$ordItemData);

                //$i++;
              }


                    if($this->role==1)
              {
                //$notifydata['include_player_ids'] = $appoinments_details['patient_device_id'];
                $device_type = $appoinments_details['patient_device_type'];
                //$nresponse['from_name']=$appoinments_details['doctor_name'];
              }
              if($this->role==2)
              {
                //$notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
                $device_type = $appoinments_details['doctor_device_type'];
                //$nresponse['from_name']=$appoinments_details['patient_name'];
              }

              $notifydata['message']='Your Order Confirm';
              $notifydata['notifications_title']='';
              $nresponse['type']='Booking';
              $notifydata['additional_data'] = $nresponse;
              if($device_type=='Android')
              {
                sendFCMNotification($notifydata);
              }
              if($device_type=='IOS')
              {
                sendiosNotification($notifydata);
              }
               
               $response['code']='200';
               $response['message']='Transaction success';


                  }


          } catch (Exception $e) {
                    
                
            $status='failure';
                $body = $e->getJsonBody();
                print_r($body);
            $err  = $body['error'];
                $message  ='Transaction failure!.Please try again';
                 
                    
          }


    }else
      {
             $response['code']='500';
             $response['message']='Inputs field missing';
      }

       return json_encode($response);



  }




  public function stripe_pay($user_data)
  {

      $data=array();
     $response=array();
     $stripe_secert_key="";
     $txnid="";
     $transaction_status="";
     $nresponse=array();
     $device_type="";
     $message="";

    if(!empty($user_data['doctor_id']) && !empty($user_data['payment_method']) && !empty($user_data['amount']) && !empty($user_data['access_token']) && !empty($user_data['hourly_rate'])&&  !empty($user_data['appoinment_date']) && !empty($user_data['appoinment_start_time']) && !empty($user_data['appoinment_end_time']) && !empty($user_data['appoinment_token']) && !empty($user_data['appoinment_session']) && !empty($user_data['appoinment_timezone']) && !empty($user_data['appointment_type']))
    {

        $doctor_id = $user_data['doctor_id'];
   
         $amount = $user_data['amount'];
         $token=$user_data['access_token'];

         $stripe_option=!empty(settings("stripe_option"))?settings("stripe_option"):"";
         if($stripe_option=='1'){
            $stripe_secert_key=!empty(settings("sandbox_rest_key"))?settings("sandbox_rest_key"):"";
         }
         if($stripe_option=='2'){
            $stripe_secert_key=!empty(settings("live_rest_key"))?settings("live_rest_key"):"";
         }
        
         

         try {

          \Stripe\Stripe::setApiKey($stripe_secert_key);
          $striperesponse = \Stripe\Charge::create([
              'amount' => ($amount * 100),
              'currency' => 'USD',
              'description' => 'Exam charge',
              'source' => $token,
          ]);

          $txnid = "-";
          $status='failure';

          if(!empty($striperesponse)){
           $transaction_status = $striperesponse; 
 
            if(!empty($striperesponse->id)){
              $txnid = $striperesponse->id;
              $status='success';
            }else{
              $txnid = '-';
              $status='failure';
              $message='Transaction failure!.Please try again';
            }
          }


          } catch (Exception $e) {
                    
                 $status='failure';
                $body = $e->getJsonBody();
                $err  = $body['error'];
                $message  ='Transaction failure!.Please try again';
                 
                    
          }
          
        
            if($status == 'success'){

               $opentok = new OpenTok($this->tokbox_apiKey,$this->tokbox_apiSecret);
            // An automatically archived session:
              $sessionOptions = array(
                      // 'archiveMode' => ArchiveMode::ALWAYS,
                'mediaMode' => MediaMode::ROUTED
              );
              $new_session = $opentok->createSession($sessionOptions);
                     // Store this sessionId in the database for later use
              $tokboxsessionId= $new_session->getSessionId();

              $tokboxtoken=$opentok->generateToken($tokboxsessionId);

              /* Get Invoice id */

              $invoice = $this->db->order_by('id','desc')->limit(1)->get('payments')->row_array();
              if(empty($invoice)){
                $invoice_id = 1;   
              }else{
                $invoice_id = $invoice['id'];    
              }
              $invoice_id++;
              $invoice_no = 'I0000'.$invoice_id;

           // Store the Payment details

              $payments_data = array(
               'user_id' => $this->user_id,
               'doctor_id' => $doctor_id,
               'invoice_no' => $invoice_no,
               'per_hour_charge' => $user_data['hourly_rate'],
               'total_amount' => $amount,
               'currency_code' => 'INR',
               'txn_id' => $txnid,
               'order_id' => 'OD'.time().rand(),
               'transaction_status' => $transaction_status,  
               'payment_type' =>'Stripe',
               'tax'=>!empty(settings("tax"))?settings("tax"):"0",
               'tax_amount' => !empty($user_data['tax_amount'])?$user_data['tax_amount']:"0",
               'transcation_charge' => !empty($user_data['transcation_charge'])?$user_data['transcation_charge']:"0",
               'payment_status' => 1,
               'payment_date' => date('Y-m-d H:i:s'),
               );
              $this->db->insert('payments',$payments_data);
              $payment_id = $this->db->insert_id();
              

              
               $appointmentdata['payment_id'] =  $payment_id;   
               $appointmentdata['appointment_from'] = $this->user_id;
               $appointmentdata['appointment_to'] = $user_data['doctor_id'];
               $appointmentdata['from_date_time'] = $user_data['appoinment_date'].' '.date('H:i:s',strtotime($user_data['appoinment_start_time']));
               $appointmentdata['to_date_time'] = $user_data['appoinment_date'].' '.date('H:i:s',strtotime($user_data['appoinment_end_time']));
               $appointmentdata['appointment_date'] = $user_data['appoinment_date'];
               $appointmentdata['appointment_time'] = date('H:i:s',strtotime($user_data['appoinment_start_time']));
               $appointmentdata['appointment_end_time'] = date('H:i:s',strtotime($user_data['appoinment_end_time']));
               $appointmentdata['appoinment_token'] = $user_data['appoinment_token'];
               $appointmentdata['appoinment_session'] = $user_data['appoinment_session'];
               $appointmentdata['payment_method'] = $user_data['payment_method'];
               $appointmentdata['tokboxsessionId'] = $tokboxsessionId;
               $appointmentdata['tokboxtoken'] = $tokboxtoken;
               $appointmentdata['paid'] = 1;
               $appointmentdata['approved'] = 1;
               $appointmentdata['time_zone'] = $user_data['appoinment_timezone'];
               $appointmentdata['type'] = $user_data['appointment_type'];
               $appointmentdata['created_date'] = date('Y-m-d H:i:s');
               $this->db->insert('appointments',$appointmentdata);
               $appointment_id = $this->db->insert_id();
               $appoinments_details = $this->api->get_appoinment_call_details($appointment_id);
              if($this->role==1)
              {
                $notifydata['include_player_ids'] = $appoinments_details['patient_device_id'];
                $device_type = $appoinments_details['patient_device_type'];
                $nresponse['from_name']=$appoinments_details['doctor_name'];
              }
              if($this->role==2)
              {
                $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
                $device_type = $appoinments_details['doctor_device_type'];
                $nresponse['from_name']=$appoinments_details['patient_name'];
              }

              $notifydata['message']=$nresponse['from_name'].' has has booked appointment on '.date('d M Y',strtotime($user_data['appoinment_date']));
              $notifydata['notifications_title']='';
              $nresponse['type']='Booking';
              $notifydata['additional_data'] = $nresponse;
              if($device_type=='Android')
              {
                sendFCMNotification($notifydata);
              }
              if($device_type=='IOS')
              {
                sendiosNotification($notifydata);
              }
               
               $response['code']='200';
               $response['message']='Transaction success';

           }else{

               $response['code']='500';
               $response['message']=$message;
               
          }

      }
      else
      {
             $response['code']='500';
             $response['message']='Inputs field missing';
      }


      

       return json_encode($response);

  }


   public function razor_pay($user_data)
  {

      $data=array();
     $response=array();
     $nresponse=array();
     $device_type="";

    if(!empty($user_data['doctor_id']) && !empty($user_data['payment_method']) && !empty($user_data['amount']) && !empty($user_data['access_token']) && !empty($user_data['hourly_rate'])&&  !empty($user_data['appoinment_date']) && !empty($user_data['appoinment_start_time']) && !empty($user_data['appoinment_end_time']) && !empty($user_data['appoinment_token']) && !empty($user_data['appoinment_session']) && !empty($user_data['appoinment_timezone']) && !empty($user_data['appointment_type']))
    {

        $doctor_id = $user_data['doctor_id'];
   
         $amount = $user_data['amount'];
         $token=$user_data['access_token'];

        
         

           $status='success';
        
            if($status == 'success'){

               $opentok = new OpenTok($this->tokbox_apiKey,$this->tokbox_apiSecret);
            // An automatically archived session:
              $sessionOptions = array(
                      // 'archiveMode' => ArchiveMode::ALWAYS,
                'mediaMode' => MediaMode::ROUTED
              );
              $new_session = $opentok->createSession($sessionOptions);
                     // Store this sessionId in the database for later use
              $tokboxsessionId= $new_session->getSessionId();

              $tokboxtoken=$opentok->generateToken($tokboxsessionId);

              /* Get Invoice id */

              $invoice = $this->db->order_by('id','desc')->limit(1)->get('payments')->row_array();
              if(empty($invoice)){
                $invoice_id = 1;   
              }else{
                $invoice_id = $invoice['id'];    
              }
              $invoice_id++;
              $invoice_no = 'I0000'.$invoice_id;

           // Store the Payment details

              $payments_data = array(
               'user_id' => $this->user_id,
               'doctor_id' => $doctor_id,
               'invoice_no' => $invoice_no,
               'per_hour_charge' => $user_data['hourly_rate'],
               'total_amount' => $amount,
               'currency_code' => 'INR',
               'txn_id' => $token,
               'order_id' => 'OD'.time().rand(),
               'transaction_status' => $token,  
               'payment_type' =>'Razor Pay',
               'tax'=>!empty(settings("tax"))?settings("tax"):"0",
               'tax_amount' => !empty($user_data['tax_amount'])?$user_data['tax_amount']:"0",
               'transcation_charge' => !empty($user_data['transcation_charge'])?$user_data['transcation_charge']:"0",
               'payment_status' => 1,
               'payment_date' => date('Y-m-d H:i:s'),
               );
              $this->db->insert('payments',$payments_data);
              $payment_id = $this->db->insert_id();
              

              
               $appointmentdata['payment_id'] =  $payment_id;   
               $appointmentdata['appointment_from'] = $this->user_id;
               $appointmentdata['appointment_to'] = $user_data['doctor_id'];
               $appointmentdata['from_date_time'] = $user_data['appoinment_date'].' '.date('H:i:s',strtotime($user_data['appoinment_start_time']));
               $appointmentdata['to_date_time'] = $user_data['appoinment_date'].' '.date('H:i:s',strtotime($user_data['appoinment_end_time']));
               $appointmentdata['appointment_date'] = $user_data['appoinment_date'];
               $appointmentdata['appointment_time'] = date('H:i:s',strtotime($user_data['appoinment_start_time']));
               $appointmentdata['appointment_end_time'] = date('H:i:s',strtotime($user_data['appoinment_end_time']));
               $appointmentdata['appoinment_token'] = $user_data['appoinment_token'];
               $appointmentdata['appoinment_session'] = $user_data['appoinment_session'];
               $appointmentdata['payment_method'] = $user_data['payment_method'];
               $appointmentdata['tokboxsessionId'] = $tokboxsessionId;
               $appointmentdata['tokboxtoken'] = $tokboxtoken;
               $appointmentdata['paid'] = 1;
               $appointmentdata['approved'] = 1;
               $appointmentdata['time_zone'] = $user_data['appoinment_timezone'];
               $appointmentdata['type'] = $user_data['appointment_type'];
               $appointmentdata['created_date'] = date('Y-m-d H:i:s');
               $this->db->insert('appointments',$appointmentdata);
               $appointment_id = $this->db->insert_id();
               $appoinments_details = $this->api->get_appoinment_call_details($appointment_id);
              if($this->role==1)
              {
                $notifydata['include_player_ids'] = $appoinments_details['patient_device_id'];
                $device_type = $appoinments_details['patient_device_type'];
                $nresponse['from_name']=$appoinments_details['doctor_name'];
              }
              if($this->role==2)
              {
                $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
                $device_type = $appoinments_details['doctor_device_type'];
                $nresponse['from_name']=$appoinments_details['patient_name'];
              }

              $notifydata['message']=$nresponse['from_name'].' has has booked appointment on '.date('d M Y',strtotime($user_data['appoinment_date']));
              $notifydata['notifications_title']='';
              $nresponse['type']='Booking';
              $notifydata['additional_data'] = $nresponse;
              if($device_type=='Android')
              {
                sendFCMNotification($notifydata);
              }
              if($device_type=='IOS')
              {
                sendiosNotification($notifydata);
              }
               
               $response['code']='200';
               $response['message']='Transaction success';

           }else{

               $response['code']='500';
               $response['message']='Transcation failuresss';
               
          }

      }
      else
      {
             $response['code']='500';
             $response['message']='Inputs field missing';
      }


      

       return json_encode($response);

  }
  


  public function clinic_pay($user_data)
  {

      $data=array();
     $response=array();
     $device_type="";
     $nresponse=array();

    if(!empty($user_data['doctor_id']) && !empty($user_data['payment_method']) && !empty($user_data['amount'])  && !empty($user_data['hourly_rate'])&&  !empty($user_data['appoinment_date']) && !empty($user_data['appoinment_start_time']) && !empty($user_data['appoinment_end_time']) && !empty($user_data['appoinment_token']) && !empty($user_data['appoinment_session']) && !empty($user_data['appoinment_timezone']) && !empty($user_data['appointment_type']))
    {

        $doctor_id = $user_data['doctor_id'];
   
         $amount = $user_data['amount'];
        
           

           $paymentdata = array(
           'user_id' => $this->user_id,
           'doctor_id' => $user_data['doctor_id'],
           'payment_status' => 0,
           'payment_date' => date('Y-m-d H:i:s'),
           'currency_code' => 'USD'
         );
         $this->db->insert('payments',$paymentdata);
             $payment_id = $this->db->insert_id();  

              
               $appointmentdata['payment_id'] =  $payment_id;   
               $appointmentdata['appointment_from'] = $this->user_id;
               $appointmentdata['appointment_to'] = $user_data['doctor_id'];
               $appointmentdata['from_date_time'] = $user_data['appoinment_date'].' '.date('H:i:s',strtotime($user_data['appoinment_start_time']));
               $appointmentdata['to_date_time'] = $user_data['appoinment_date'].' '.date('H:i:s',strtotime($user_data['appoinment_end_time']));
               $appointmentdata['appointment_date'] = $user_data['appoinment_date'];
               $appointmentdata['appointment_time'] = date('H:i:s',strtotime($user_data['appoinment_start_time']));
               $appointmentdata['appointment_end_time'] = date('H:i:s',strtotime($user_data['appoinment_end_time']));
               $appointmentdata['appoinment_token'] = $user_data['appoinment_token'];
               $appointmentdata['appoinment_session'] = $user_data['appoinment_session'];
               $appointmentdata['payment_method'] = $user_data['payment_method'];
               $appointmentdata['tokboxsessionId'] = '';
               $appointmentdata['tokboxtoken'] = '';
               $appointmentdata['paid'] = 1;
               $appointmentdata['approved'] = 1;
               $appointmentdata['type'] = $user_data['appointment_type'];
               $appointmentdata['time_zone'] = $user_data['appoinment_timezone'];
               $appointmentdata['created_date'] = date('Y-m-d H:i:s');
               $this->db->insert('appointments',$appointmentdata);
               $appointment_id = $this->db->insert_id();
               $appoinments_details = $this->api->get_appoinment_call_details($appointment_id);
              if($this->role==1)
              {
                $notifydata['include_player_ids'] = $appoinments_details['patient_device_id'];
                $device_type = $appoinments_details['patient_device_type'];
                $nresponse['from_name']=$appoinments_details['doctor_name'];
              }
              if($this->role==2)
              {
                $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
                $device_type = $appoinments_details['doctor_device_type'];
                $nresponse['from_name']=$appoinments_details['patient_name'];
              }

              $notifydata['message']=$nresponse['from_name'].' has has booked appointment on '.date('d M Y',strtotime($user_data['appoinment_date']));
              $notifydata['notifications_title']='';
              $nresponse['type']='Booking';
              $notifydata['additional_data'] = $nresponse;
              if($device_type=='Android')
              {
                sendFCMNotification($notifydata);
              }
              if($device_type=='IOS')
              {
                sendiosNotification($notifydata);
              }
               
               $response['code']='200';
               $response['message']='Transaction success';

           

      }
      else
      {
             $response['code']='500';
             $response['message']='Inputs field missing';
      }


      

       return json_encode($response);

  }

  // public function clinic_lab_pay($user_data)
  // {
  //    $data=array();
  //    $response=array();
  //    $device_type="";
  //    $nresponse=array();

  //   if(!empty($user_data['lab_id']) &&  !empty($user_data['amount']) && !empty($user_data['hourly_rate']) &&  !empty($user_data['appoinment_date'])  &&  !empty($user_data['booking_ids']) )
  //   {

  //       $lab_id = $user_data['lab_id']; 
  //       $amount = $user_data['amount']; 
        
  //        $paymentdata = array(
  //          'lab_id' =>$lab_id,
  //          'patient_id' => $this->user_id,          
  //          'payment_status' => 0,
  //          'payment_date' => date('Y-m-d H:i:s'),
  //          'currency_code' => 'USD'
  //        );
  //       $this->db->insert('payments',$paymentdata);       
               
  //       $response['code']='200';
  //       $response['message']='Transaction success';          

  //     }
  //     else
  //     {
  //            $response['code']='500';
  //            $response['message']='Inputs field missing';
  //     }
  //      return json_encode($response);
  // }


  public function clinic_lab_pay($user_data)
  {

    //   $data=array();
    //  $response=array();
    //  $device_type="";
    //  $nresponse=array();

    // if(!empty($user_data['lab_id']) &&  !empty($user_data['amount']) && !empty($user_data['hourly_rate']) &&  !empty($user_data['appoinment_date'])  &&  !empty($user_data['booking_ids']) )
    // {

    //     $lab_id = $user_data['lab_id']; 
    //     $amount = $user_data['amount']; 
        
    //      $paymentdata = array(
    //        'lab_id' =>$lab_id,
    //        'patient_id' => $this->user_id,          
    //        'payment_status' => 0,
    //        'payment_date' => date('Y-m-d H:i:s'),
    //        'currency_code' => 'USD'
    //      );
    //     $this->db->insert('payments',$paymentdata);       
               
    //     $response['code']='200';
    //     $response['message']='Transaction success';          

    //   }
    //   else
    //   {
    //          $response['code']='500';
    //          $response['message']='Inputs field missing';
    //   }

    $data=array();
    $response=array();

    if(!empty($user_data['lab_id']) &&  !empty($user_data['amount']) && !empty($user_data['hourly_rate']) &&  !empty($user_data['appoinment_date'])  &&  !empty($user_data['booking_ids']) )
    {

      $lab_id = $user_data['lab_id']; 
      $amount = $user_data['amount']; 
      $user_currency=get_user_currency_api($this->user_id); 
      $currency_code=!empty($user_currency['user_currency_code'])?$user_currency['user_currency_code']:'INR';
         
        $orderIds = 'OD'.time().rand();
        
      
        $invoice = $this->db->order_by('id','desc')->limit(1)->get('lab_payments')->row_array();
        if(empty($invoice)){
        $invoice_id = 1;   
        }else{
        $invoice_id = $invoice['id'];    
        }
        $invoice_id++;
        $invoice_no = 'I0000'.$invoice_id;

       // Store the Payment details
       $payments_data = array(
        'lab_id' => $lab_id,
        'patient_id' => $this->user_id,
        'booking_ids' => $user_data['booking_ids'],
        'invoice_no' => $invoice_no,
        'lab_test_date' => $user_data['appoinment_date'],
        'total_amount' => $amount,
        'currency_code' => $currency_code,
        'txn_id' => $user_data['txn_id'],
        'order_id' =>$orderIds,
        'transaction_status' => "success",  
        'payment_type' =>'Clinic',
        'tax'=>!empty(settings("tax"))?settings("tax"):"0",
        'tax_amount' => !empty($user_data['tax_amount'])?$user_data['tax_amount']:"0",
        'transcation_charge' => !empty($user_data['transcation_charge'])?$user_data['transcation_charge']:"0",
        'payment_status' => 1,
        'payment_date' => date('Y-m-d H:i:s'),
    );

    $this->db->insert('lab_payments',$payments_data);
    $appointment_id = $this->db->insert_id(); 
    // print_r($appointments_id);exit();
  
    
    //Notification Starts Here
    $d_type=$this->db->query('SELECT * FROM users WHERE id='.$lab_id.'')->row();

    $patient_name=$this->db->query('SELECT * FROM users WHERE id='.$this->user_id.'')->row();

    $notifydata['message']='Lab Appointment booked by '.$patient_name->first_name;
    $notifydata['notifications_title']='';
        $nresponse['type']='Booking';
        $notifydata['additional_data'] = $nresponse;
  
    $notifydata['include_player_ids']=$d_type->device_id;

    if(!empty($notifydata['include_player_ids']))
    {
      if($d_type->device_type=='Android')
      {         
        sendFCMNotification($notifydata);
      }
      if($d_type->device_type=='IOS')
      {
        sendiosNotification($notifydata);
      }
    }
    //Notification Ends Here
         
        $response['code']='200';
        $response['message']='Transaction success';
      
     
 
    }else
    {
           $response['code']='500';
           $response['message']='Inputs field missing';
    } 
     return json_encode($response);



  }


  public function appointments_list_post()
     {
            if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $docresult=array();
                $user_data = array();
                $user_data = $this->post();
                $appresult= array();
                $page = $user_data['page'];
                $limit = $user_data['limit'];

               
            
               $appointments_list_count = $this->api->appointments_lists($page,$limit,1,$user_data,$this->user_id,$this->role);
               $appointments_list = $this->api->appointments_lists($page,$limit,2,$user_data,$this->user_id,$this->role); 
               
                              
              if (!empty($appointments_list)) {
                foreach ($appointments_list as $rows) {

                

              $current_timezone = $rows['time_zone'];               
              $old_timezone = $this->time_zone; 
                 

            $appointment_date=date('d M Y',strtotime(converToTz($rows['appointment_date'],$old_timezone,$current_timezone)));
            $appointment_time=converToTz($rows['from_date_time'],$old_timezone,$current_timezone);
            $appointment_end_time=converToTz($rows['to_date_time'],$old_timezone,$current_timezone);
            $created_date=date('d M Y',strtotime(converToTz($rows['created_date'],$old_timezone,$current_timezone)));
            
                  $data['id']=$rows['id'];
                  $data['profileimage']=(!empty($rows['profileimage']))?$rows['profileimage']:'assets/img/user.png';
                  $data['first_name']=ucfirst($rows['first_name']);
                  $data['last_name']=ucfirst($rows['last_name']);
                  $data['patient_id']='#PT00'.$rows['appointment_from'];
                  $data['patient_user_id']=$rows['appointment_from'];
                  $data['doctor_user_id']=$rows['appointment_to'];
                  $data['date']=$appointment_date;
                  $data['start_time']=date('h:i A',strtotime($appointment_time));
                  $data['end_time']=date('h:i A',strtotime($appointment_end_time));
                  $data['appoinment_start_time']=$appointment_time;
                  $data['appoinment_end_time']=$appointment_end_time;
                  $data['created_date']=$created_date;
                  $data['type']=ucfirst($rows['type']);
                  $data['payment_method']=$rows['payment_method'];
                  $data['amount']=!empty($rows['per_hour_charge'])?$rows['per_hour_charge']:'0';
                  $data['approved']=$rows['approved'];
                  $data['user_role']=$rows['role'];
                  $data['login_user_role']=$this->role;
                  $appresult[]=$data;
                }
            }

                $pages = !empty($page)?$page:1;
                $appointments_list_count = ceil($appointments_list_count/$limit);
                $next_page    = $pages + 1;
                $next_page    = ($next_page <=$appointments_list_count)?$next_page:-1;

                $response['appointments_list']=$appresult;
                $response['next_page']=$next_page;
                $response['current_page']=$page;
            
           
          
            if(empty($response['appointments_list']))
            {
                 $response_code = '201';
                 $response_message = "No Results found";
            }
            else
            {
                 $response_code = '200';
                 $response_message = "";
            }

            $response_data=$response;
                         
            $result = $this->data_format($response_code,$response_message,$response_data);

            $this->response($result, REST_Controller::HTTP_OK);

           }
           else
            {
              $this->token_error();
            }
     }


     public function appointments_history_post()
     {      $patient_id="";
            if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $docresult=array();
                $user_data = array();
                $user_data = $this->post();
                $appresult= array();
                $page = $user_data['page'];
                $limit = $user_data['limit'];

               if($this->role==1 && empty($user_data['patient_id']))
               {
                  $response_code='500';
                  $response_message='Inputs field missing';
               }
               else
               {

                  if($this->role==1)
                  {
                    $patient_id=$user_data['patient_id'];
                  }
                  if($this->role==2)
                  {
                    $patient_id=$this->user_id;
                  }

                     $appointments_list_count = $this->api->appointments_history($page,$limit,1,$patient_id,$this->user_id,$this->role,$user_data);
                     $appointments_list = $this->api->appointments_history($page,$limit,2,$patient_id,$this->user_id,$this->role,$user_data); 
        
                                    
                    if (!empty($appointments_list)) {
                      foreach ($appointments_list as $rows) {

                      

                    $current_timezone = $rows['time_zone'];               
                    $old_timezone = $this->time_zone; 
                       

                  $appointment_date=date('d M Y',strtotime(converToTz($rows['appointment_date'],$old_timezone,$current_timezone)));
                  $appointment_time=converToTz($rows['from_date_time'],$old_timezone,$current_timezone);
                  $appointment_end_time=converToTz($rows['to_date_time'],$old_timezone,$current_timezone);
                  $created_date=date('d M Y',strtotime(converToTz($rows['created_date'],$old_timezone,$current_timezone)));
                  
                        $data['id']=$rows['id'];
                        $data['profileimage']=(!empty($rows['profileimage']))?$rows['profileimage']:'assets/img/user.png';
                        $data['first_name']=ucfirst($rows['first_name']);
                        $data['last_name']=ucfirst($rows['last_name']);
                        $data['patient_id']=($rows['role']=='2')?'#PT00'.$rows['appointment_from']:'';
                        $data['date']=$appointment_date;
                        $data['patient_user_id']=$rows['appointment_from'];
                        $data['doctor_user_id']=$rows['appointment_to'];
                        $data['start_time']=date('h:i A',strtotime($appointment_time));
                        $data['end_time']=date('h:i A',strtotime($appointment_end_time));
                        $data['appoinment_start_time']=$appointment_time;
                        $data['appoinment_end_time']=$appointment_end_time;
                        $data['created_date']=$created_date;
                        $data['type']=ucfirst($rows['type']);
                        $data['payment_method']=$rows['payment_method'];
                        $data['amount']=!empty($rows['per_hour_charge'])?$rows['per_hour_charge']:'0';
                        $data['approved']=$rows['approved'];
                        $data['user_role']=$rows['role'];
                        $data['login_user_role']=$this->role;
                        $appresult[]=$data;
                      }
                  }

                      $pages = !empty($page)?$page:1;
                      $appointments_list_count = ceil($appointments_list_count/$limit);
                      $next_page    = $pages + 1;
                      $next_page    = ($next_page <=$appointments_list_count)?$next_page:-1;

                      $response['appointments_list']=$appresult;
                      $response['next_page']=$next_page;
                      $response['current_page']=$page;
                  
                 
                
                  if(empty($response['appointments_list']))
                  {
                       $response_code = '201';
                       $response_message = "No Results found";
                  }
                  else
                  {
                       $response_code = '200';
                       $response_message = "";
                  }
              }

                         

            $response_data=$response;
                         
            $result = $this->data_format($response_code,$response_message,$response_data);

            $this->response($result, REST_Controller::HTTP_OK);

           }
           else
            {
              $this->token_error();
            }
     }


     public function prescription_list_post()
     {
            if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $docresult=array();
                $user_data = array();
                $user_data = $this->post();
                $preresult= array();
                $patient_id="";
                $page = $user_data['page'];
                $limit = $user_data['limit'];

               if($this->role==1 && empty($user_data['patient_id']))
               {
                  $response_code='500';
                  $response_message='Inputs field missing';
               }
               else
               {

                  if($this->role==1)
                  {
                    $patient_id=$user_data['patient_id'];
                  }
                  if($this->role==2)
                  {
                    $patient_id=$this->user_id;
                  }

                     $prescription_list_count = $this->api->prescription_list($page,$limit,1,$patient_id,$this->user_id,$this->role);
                     $prescription_list = $this->api->prescription_list($page,$limit,2,$patient_id,$this->user_id,$this->role); 
        
                                    
                    if (!empty($prescription_list)) {
                      foreach ($prescription_list as $rows) {
                 
                  
                        $data['id']=$rows['id'];
                        $data['patient_image']=(!empty($rows['patient_image']))?$rows['patient_image']:'assets/img/user.png';
                        $data['doctor_image']=(!empty($rows['doctor_image']))?$rows['doctor_image']:'assets/img/user.png';
                        $data['patient_name']=ucfirst($rows['patient_name']);
                        $data['doctor_name']=ucfirst($rows['doctor_name']);
                        $data['specialization']=ucfirst($rows['specialization']);
                        $data['signature_image']=$rows['signature_image'];
                        $data['created_date']=date('d M Y',strtotime($rows['created_at']));
                        $data['prescription_details']=$this->api->prescription_details($rows['id']);
                        $data['pdf_link']=base_url().'print-prescription/'.base64_encode($rows['id']);
                        $preresult[]=$data;
                      }
                  }

                      $pages = !empty($page)?$page:1;
                      $prescription_list_count = ceil($prescription_list_count/$limit);
                      $next_page    = $pages + 1;
                      $next_page    = ($next_page <=$prescription_list_count)?$next_page:-1;

                      $response['prescription_list']=$preresult;
                      $response['next_page']=$next_page;
                      $response['current_page']=$page;
                  
                 
                
                  if(empty($response['prescription_list']))
                  {
                       $response_code = '201';
                       $response_message = "No Results found";
                  }
                  else
                  {
                       $response_code = '200';
                       $response_message = "";
                  }
              }

                         

            $response_data=$response;
                         
            $result = $this->data_format($response_code,$response_message,$response_data);

            $this->response($result, REST_Controller::HTTP_OK);

           }
           else
            {
              $this->token_error();
            }
     }


       public function prescription_detail_post()
       {
          if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $result=array();
                $user_data = array();
                $user_data = $this->post();
                $id=$user_data['prescription_id'];

                 $response['prescription_view']=$this->api->get_prescription_view($id);
                 $response['item_details']=$this->api->prescription_details($id);
                
                 $response_code = '200';
                 $response_message = "";
                 $response_data=$response;
                 $result = $this->data_format($response_code,$response_message,$response_data);
                 $this->response($result, REST_Controller::HTTP_OK);

              }
            else
            {
              $this->token_error();
            }
       }


     public function prescription_insert_post()
     {

        $sign_id="";
        if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();

              
               $drug_details=json_decode($user_data['drug_details']);
               

        
          if(!empty($user_data['patient_id']) )
          {   


              if($_FILES["signature_image"]["name"] != '')
               {
                   $config["upload_path"] = './uploads/signature-image/';
                   $config["allowed_types"] = '*';
                   $this->load->library('upload', $config);
                   $this->upload->initialize($config);

                          $_FILES["file"]["name"] = 'img_'.time().'.png';
                          $_FILES["file"]["type"] = $_FILES["signature_image"]["type"];
                          $_FILES["file"]["tmp_name"] = $_FILES["signature_image"]["tmp_name"];
                          $_FILES["file"]["error"] = $_FILES["signature_image"]["error"];
                          $_FILES["file"]["size"] = $_FILES["signature_image"]["size"];
                        if($this->upload->do_upload('file'))
                        {
                           $upload_data = $this->upload->data();
                          
                            $profile_img='uploads/signature-image/'.$upload_data["file_name"];
                            

                            $data=array('img'=>$profile_img,'rowno'=>rand());
                            $this->db->insert('signature', $data);
                            $sign_id= $this->db->insert_id();
                            
                            
                                                                         
                        }
                  }


          $data = array(
            'doctor_id' => $this->user_id,
            'patient_id' => $this->post('patient_id'),
            'signature_id' => $sign_id,
            'created_at' => date('Y-m-d H:i:s')
          );
        $this->db->insert('prescription', $data);
        $prescription_id = $this->db->insert_id();  



            if($prescription_id)
            {

               foreach ($drug_details as $row) {
                  
                 $datas = array(
                    'prescription_id' => $prescription_id,
                    'drug_name' => $row->drug_name,
                    'days'=>$row->days,
                    'time'=>$row->time,
                    'qty' => $row->qty,
                    'type'=>$row->type,
                    'created_at'  => date('Y-m-d H:i:s')
                  );

                  $this->db->insert('prescription_item_details', $datas); 
               }



                


                 $response_code='200';
                 $response_message='Prescription successfully created';            
            }
           else
            {
                $response_code='500';
                $response_message='Prescription creation failed';  
            } 
      
            
          }
          else
          {
                 $response_code='500';
                 $response_message='Inputs field missing';
          }

          $response['patient_details']=$this->api->get_patient_details($this->user_id);

             
           $result = $this->data_format($response_code,$response_message,$response);
           $this->response($result, REST_Controller::HTTP_OK);
         
         }
         else
          {
            $this->token_error();
          }
     }


          public function prescription_update_post()
     {
        if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();

              
               $drug_details=json_decode($user_data['drug_details']);
               

        
          if(!empty($user_data['patient_id']) && !empty($user_data['prescription_id']))
          {   

               if($_FILES["signature_image"]["name"] != '')
               {
                   $config["upload_path"] = './uploads/signature-image/';
                   $config["allowed_types"] = '*';
                   $this->load->library('upload', $config);
                   $this->upload->initialize($config);

                          $_FILES["file"]["name"] = 'img_'.time().'.png';
                          $_FILES["file"]["type"] = $_FILES["signature_image"]["type"];
                          $_FILES["file"]["tmp_name"] = $_FILES["signature_image"]["tmp_name"];
                          $_FILES["file"]["error"] = $_FILES["signature_image"]["error"];
                          $_FILES["file"]["size"] = $_FILES["signature_image"]["size"];
                        if($this->upload->do_upload('file'))
                        {
                           $upload_data = $this->upload->data();
                          
                            $profile_img='uploads/signature-image/'.$upload_data["file_name"];
                            

                            $data=array('img'=>$profile_img);
                            $this->db->where('id',$user_data['signature_id']);
                            $this->db->update('signature', $data);
                            
                            
                            
                                                                         
                        }
                  }


          $data = array(
            'doctor_id' => $this->user_id,
            'patient_id' => $this->post('patient_id'),
            'created_at' => date('Y-m-d H:i:s')
          );
          $this->db->where('id',$user_data['prescription_id']);
          $this->db->update('prescription', $data);
        



            if($user_data['prescription_id'])
            {

               $where = array('prescription_id' => $user_data['prescription_id']);
               $this->db->delete('prescription_item_details',$where); 



               foreach ($drug_details as $row) {
                  
                 $datas = array(
        'prescription_id' => $user_data['prescription_id'],
        'drug_name' => $row->drug_name,
        'days'=>$row->days,
        'time'=>$row->time,
        'qty' => $row->qty,
        'type'=>$row->type,
        'created_at'  => date('Y-m-d H:i:s')
      );
                       $this->db->insert('prescription_item_details', $datas); 

                    
               }



                


                 $response_code='200';
                 $response_message='Prescription successfully updated';            
            }
           else
            {
                $response_code='500';
                $response_message='Prescription updation failed';  
            } 
      
            
          }
          else
          {
                 $response_code='500';
                 $response_message='Inputs field missing';
          }

          $response['patient_details']=$this->api->get_patient_details($this->user_id);

             
           $result = $this->data_format($response_code,$response_message,$response);
           $this->response($result, REST_Controller::HTTP_OK);
         
         }
         else
          {
            $this->token_error();
          }
     }


      public function prescription_delete_post()
       {
          if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $result=array();
                $user_data = array();
                $user_data = $this->post();
                $id=$user_data['prescription_id'];

                 $where = array('id' => $user_data['prescription_id']);
                 $this->db->delete('prescription',$where); 

                 $where = array('prescription_id' => $user_data['prescription_id']);
                 $this->db->delete('prescription_item_details',$where); 
                 
                
                 $response_code = '200';
                 $response_message = "Prescription deleted successfully";
                 $response_data=$response;
                 $result = $this->data_format($response_code,$response_message,$response_data);
                 $this->response($result, REST_Controller::HTTP_OK);

              }
            else
            {
              $this->token_error();
            }
       }



      public function medical_records_list_post()
     {
            $patient_id="";
            if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $docresult=array();
                $user_data = array();
                $user_data = $this->post();
                $mrresult= array();
                $page = $user_data['page'];
                $limit = $user_data['limit'];

               if($this->role==1 && empty($user_data['patient_id']))
               {
                  $response_code='500';
                  $response_message='Inputs field missing';
               }
               else
               {

                  if($this->role==1)
                  {
                    $patient_id=$user_data['patient_id'];
                  }
                  if($this->role==2)
                  {
                    $patient_id=$this->user_id;
                  }

                     $medical_records_list_count = $this->api->medical_records_list($page,$limit,1,$patient_id,$this->user_id,$this->role);
                     $medical_records_list = $this->api->medical_records_list($page,$limit,2,$patient_id,$this->user_id,$this->role); 
        
                                    
                    if (!empty($medical_records_list)) {
                      foreach ($medical_records_list as $rows) {
                 
                  
                        $data['id']=$rows['id'];
                        $data['patient_image']=(!empty($rows['patient_image']))?$rows['patient_image']:'assets/img/user.png';
                        $data['doctor_image']=(!empty($rows['doctor_image']))?$rows['doctor_image']:'assets/img/user.png';
                        $data['patient_name']=ucfirst($rows['patient_name']);
                        $data['doctor_name']=ucfirst($rows['doctor_name']);
                        $data['specialization']=ucfirst($rows['specialization']);
                        $data['filename']=$rows['file_name'];
                        $data['description']=$rows['description'];
                        $data['created_date']=date('d M Y',strtotime($rows['date']));
                        
                        $mrresult[]=$data;
                      }
                  }

                      $pages = !empty($page)?$page:1;
                      $medical_records_list_count = ceil($medical_records_list_count/$limit);
                      $next_page    = $pages + 1;
                      $next_page    = ($next_page <=$medical_records_list_count)?$next_page:-1;

                      $response['medical_records_list']=$mrresult;
                      $response['next_page']=$next_page;
                      $response['current_page']=$page;
                  
                 
                
                  if(empty($response['medical_records_list']))
                  {
                       $response_code = '201';
                       $response_message = "No Results found";
                  }
                  else
                  {
                       $response_code = '200';
                       $response_message = "";
                  }
              }

                         

            $response_data=$response;
                         
            $result = $this->data_format($response_code,$response_message,$response_data);

            $this->response($result, REST_Controller::HTTP_OK);

           }
           else
            {
              $this->token_error();
            }
     }

     public function upload_medical_record_post(){

      if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $docresult=array();
                $user_data = array();
                $user_data = $this->post();
                

               if($this->role==1 && empty($user_data['patient_id']))
               {
                  $response_code='500';
                  $response_message='Inputs field missing';
               }
               else
               {


                  $data = array();
    //ob_flush();
    if($this->role==1){
    $doctor_id = $this->user_id;
    $patient_id=$this->post('patient_id');
  }else{
    
     $patient_id =$this->user_id;
     $doctor_id = 0;
     

  }
    $description=$this->post('description');
       
    if(!empty($_FILES['user_file']['name'])){

      $path = "uploads/medical_records/".$patient_id;
      $upload_path = $path.'/'.date('d-m-Y');
      if(!is_dir($path)){
        mkdir($path);         
      }
      if(!is_dir($path.'/'.date('d-m-Y'))){
        mkdir($path.'/'.date('d-m-Y')); 
      }
      $path = $path.'/'.date('d-m-Y');
      
      $target_file =$path . basename($_FILES["user_file"]["name"]);
      $file_type = pathinfo($target_file,PATHINFO_EXTENSION);

      if($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg" && $file_type != "gif" ){
        $type = 'others';
      }else{
        $type = 'image';
      }
      $config['upload_path']   = './'.$path;
      $config['allowed_types'] = '*';   
      $config['file_name'] = date('d-m-Y_h_i_A').'_prescription';   
      $this->load->library('upload',$config);
      if($this->upload->do_upload('user_file')){  
        $file_name=$this->upload->data('file_name');
        $data +=array('file_name' => $upload_path.'/'.$file_name);
      }else{

        $response_code = '500';
        $response_message = "File upload error";
        
        echo json_encode($result);
        exit;
      }
    }

    $data +=array(
      'date' =>  date('Y-m-d H:i:s'),
      'description' => $description,     
      'doctor_id' => $doctor_id,
      'patient_id' => $patient_id
    );

    
       $this->db->insert('medical_records',$data);
    
     $result= ($this->db->affected_rows()!= 1)? false:true;
     if($result==true)
      {   
          
           $response_code = '200';
        $response_message = "Medical record updated successfuly";
          
      }
     else
      {
          $response_code = '500';
        $response_message = "Something went wrong try again later";
      } 

         
              }

                         

            $response_data=$response;
                         
            $result = $this->data_format($response_code,$response_message,$response_data);

            $this->response($result, REST_Controller::HTTP_OK);

           }
           else
            {
              $this->token_error();
            }



     }

     public function medicalrecord_delete_post(){

       if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $result=array();
                $user_data = array();
                $user_data = $this->post();
                $id=$user_data['medical_record_id'];

                 $where = array('id' => $user_data['medical_record_id']);
                 $this->db->delete('medical_records',$where); 

                
                 $response_code = '200';
                 $response_message = "Medical record deleted successfully";
                 $response_data=$response;
                 $result = $this->data_format($response_code,$response_message,$response_data);
                 $this->response($result, REST_Controller::HTTP_OK);

              }
            else
            {
              $this->token_error();
            }

     }

     public function billing_list_post()
     {
            if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $docresult=array();
                $user_data = array();
                $user_data = $this->post();
                $preresult= array();
                $page = $user_data['page'];
                $limit = $user_data['limit'];
                $patient_id="";

               if($this->role==1 && empty($user_data['patient_id']))
               {
                  $response_code='500';
                  $response_message='Inputs field missing';
               }
               else
               {

                  if($this->role==1)
                  {
                    $patient_id=$user_data['patient_id'];
                  }
                  if($this->role==2)
                  {
                    $patient_id=$this->user_id;
                  }

                     $billing_list_count = $this->api->billing_list($page,$limit,1,$patient_id,$this->user_id,$this->role);
                     $billing_list = $this->api->billing_list($page,$limit,2,$patient_id,$this->user_id,$this->role); 
        
                                    
                    if (!empty($billing_list)) {
                      foreach ($billing_list as $rows) {
                 
                  
                        $data['id']=$rows['id'];
                        $data['patient_image']=(!empty($rows['patient_image']))?$rows['patient_image']:'assets/img/user.png';
                        $data['doctor_image']=(!empty($rows['doctor_image']))?$rows['doctor_image']:'assets/img/user.png';
                        $data['patient_name']=ucfirst($rows['patient_name']);
                        $data['doctor_name']=ucfirst($rows['doctor_name']);
                        $data['specialization']=ucfirst($rows['specialization']);
                        $data['signature_image']=$rows['signature_image'];
                        $data['created_date']=date('d M Y',strtotime($rows['created_at']));
                        $data['billing_details']=$this->api->billing_details($rows['id']);
                        $preresult[]=$data;
                      }
                  }

                      $pages = !empty($page)?$page:1;
                      $billing_list_count = ceil($billing_list_count/$limit);
                      $next_page    = $pages + 1;
                      $next_page    = ($next_page <=$billing_list_count)?$next_page:-1;

                      $response['billing_list']=$preresult;
                      $response['next_page']=$next_page;
                      $response['current_page']=$page;
                  
                 
                
                  if(empty($response['billing_list']))
                  {
                       $response_code = '201';
                       $response_message = "No Results found";
                  }
                  else
                  {
                       $response_code = '200';
                       $response_message = "";
                  }
              }

                         

            $response_data=$response;
                         
            $result = $this->data_format($response_code,$response_message,$response_data);

            $this->response($result, REST_Controller::HTTP_OK);

           }
           else
            {
              $this->token_error();
            }
     }

     public function make_outgoing_call_post()
     {      $device_type="";
            if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();
                   
            if(!empty($user_data['appoinment_id']) && !empty($user_data['call_type']) )
            { 

                  $appoinments_details = $this->api->get_appoinment_call_details($user_data['appoinment_id']);

                  if($this->role==1)
                  {
                    $response['from_user_id']=$appoinments_details['appointment_to'];
                    $response['from_name']=$appoinments_details['doctor_name'];
                    $response['to']=$appoinments_details['appointment_from'];
                    $notifydata['include_player_ids'] = $appoinments_details['patient_device_id'];
                    $device_type = $appoinments_details['patient_device_type'];
                    $notifydata['message']='Incoming call from '.$appoinments_details['doctor_name'];
                  }
                  if($this->role==2)
                  {
                    $response['from_user_id']=$appoinments_details['appointment_from'];
                    $response['from_name']=$appoinments_details['patient_name'];
                    $response['to']=$appoinments_details['appointment_to'];
                    $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
                    $device_type = $appoinments_details['doctor_device_type'];
                    $notifydata['message']='Incoming call from '.$appoinments_details['patient_name'];
                  }
                  $response['invite_id'] = $user_data['appoinment_id'];
                  $response['type'] = $user_data['call_type'];
                  $response['sessionId'] = $appoinments_details['tokboxsessionId'];
                  $response['token'] = $appoinments_details['tokboxtoken'];
                  $response['tokbox_apiKey'] =$this->tokbox_apiKey;
                  $response['tokbox_apiSecret'] =$this->tokbox_apiSecret;
                  $notifydata['notifications_title']='Incoming call';
                  $notifydata['additional_data'] = $response;


                  if($device_type=='Android')
                  {
                    sendFCMNotification($notifydata);
                  }
                  if($device_type=='IOS')
                  {
                    sendiosNotification($notifydata);
                  }
                  $this->call_details($response['invite_id'],$response['from_user_id'],$response['to'],$user_data['call_type']);

                  $response_data=$response;
                  $response_code='200';
                  $response_message='';
            }
            else
            {
                   $response_code='500';
                   $response_message='Inputs field missing';
            }

           
             
             $result = $this->data_format($response_code,$response_message,$response);
             $this->response($result, REST_Controller::HTTP_OK);
         
         }
         else
          {
            $this->token_error();
          }
 }

 public function make_incoming_call_post()
{
    if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();
      
            if(!empty($user_data['appoinment_id']) && !empty($user_data['call_type']))
            { 


                  $appoinments_details = $this->api->get_appoinment_call_details($user_data['appoinment_id']);
                  if($this->role==1)
                  {
                    $response['from_user_id']=$appoinments_details['appointment_to'];
                    $response['from_name']=$appoinments_details['doctor_name'];
                    $response['to']=$appoinments_details['appointment_from'];
                    $notifydata['include_player_ids'] = $appoinments_details['patient_device_id'];
                    $notifydata['message']='Incoming call from '.$appoinments_details['doctor_name'];
                  }
                  if($this->role==2)
                  {
                    $response['from_user_id']=$appoinments_details['appointment_from'];
                    $response['from_name']=$appoinments_details['patient_name'];
                    $response['to']=$appoinments_details['appointment_to'];
                    $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
                    $notifydata['message']='Incoming call from '.$appoinments_details['patient_name'];
                  }
                  $response['invite_id'] = $user_data['appoinment_id'];
                  $response['type'] = $user_data['call_type'];
                  $response['sessionId'] = $appoinments_details['tokboxsessionId'];
                  $response['token'] = $appoinments_details['tokboxtoken'];
                  $response['tokbox_apiKey'] =$this->tokbox_apiKey;
                  $response['tokbox_apiSecret'] =$this->tokbox_apiSecret;
                  $notifydata['notifications_title']='Incoming call';
                  $notifydata['additional_data'] = $response;
                  
                  $this->remove_call_details($response['invite_id']);
                  $this->call_accept($response['invite_id']);

                  $response_data=$response;
                  $response_code='200';
                  $response_message='';
            }
            else
            {
                   $response_code='500';
                   $response_message='Inputs field missing';
            }

           
             
             $result = $this->data_format($response_code,$response_message,$response);
             $this->response($result, REST_Controller::HTTP_OK);
         
         }
         else
          {
            $this->token_error();
          }
}

 public function end_call_post()
{
    if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();
              $device_type="";
      
            if(!empty($user_data['appoinment_id']) )
            { 
                  $appoinments_details = $this->api->get_appoinment_call_details($user_data['appoinment_id']);

                  if($this->role==1)
                  {
                    $notifydata['include_player_ids'] = $appoinments_details['patient_device_id'];
                    $response['from_name']=$appoinments_details['doctor_name'];
                    $device_type = $appoinments_details['patient_device_type'];
                  }
                  if($this->role==2)
                  {
                    $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
                    $response['from_name']=$appoinments_details['patient_name'];
                    $device_type = $appoinments_details['doctor_device_type'];
                  }
                  $response['type']='Decline';
                  $notifydata['message']=$response['from_name'].' has declined your call';
                  $notifydata['notifications_title']='';
                  $notifydata['additional_data'] = $response;


                  if($device_type=='Android')
                  {
                    sendFCMNotification($notifydata);
                  }
                  if($device_type=='IOS')
                  {
                    sendiosNotification($notifydata);
                  }

                  $this->remove_call_details($response['appointments_id']);
                  
                  $response_data=$response;
                  $response_code='200';
                  $response_message='';
            }
            else
            {
                   $response_code='500';
                   $response_message='Inputs field missing';
            }

           
             
             $result = $this->data_format($response_code,$response_message,$response);
             $this->response($result, REST_Controller::HTTP_OK);
         
         }
         else
          {
            $this->token_error();
          }
}

  private function call_details($appointments_id,$from,$to,$call_type)
    {
        
        $data['appointments_id']=$appointments_id;
        $data['call_from']=$from;
        $data['call_to']=$to;
        $data['call_type']=$call_type;
        $this->db->insert('call_details',$data);
        
    }

    private function remove_call_details($appointments_id)
    {
      $this->db->where('appointments_id',$appointments_id);
      $this->db->delete('call_details');
    }

     private function call_accept($appointments_id)
    {
      $this->db->where('id',$appointments_id);
      $this->db->update('appointments',array('call_status' =>1));
    }

private function multi_to_single($array) { 
      $days_id=array();
      foreach ($array as $value) {
       $days_id[]=$value['day_id'];
      }
       return $days_id; 
  } 



function encryptor($action, $string) 
{

  $output = false;
  $encrypt_method = "AES-256-CBC";
  $secret_key = 'bookotv';
  $secret_iv = 'bookotv123';
  $key = hash('sha256', $secret_key);
  $iv = substr(hash('sha256', $secret_iv), 0, 16);
  if( $action == 'encrypt' ) {

    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);

    $output = base64_encode($output);

  }
  else if( $action == 'decrypt' ){

//decrypt the given text/string/number

    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

  }



  return $output;

}


 public function my_patients_post()
  {
            if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $patresult=array();
                $user_data = array();
                $user_data = $this->post();

                $page = $user_data['page'];
                $limit = $user_data['limit'];

               
            
               $patient_list_count = $this->api->patients_lists($page,$limit,1,$this->user_id);
               $patient_list = $this->api->patients_lists($page,$limit,2,$this->user_id); 
  
                              
              if (!empty($patient_list)) {
                foreach ($patient_list as $rows) {
                  $data['id']=$rows['id'];
                  $data['patient_id']=$rows['user_id'];
                  $data['username']=$rows['username'];
                  $data['profileimage']=(!empty($rows['profileimage']))?base_url().$rows['profileimage']:base_url().'assets/img/user.png';
                  $data['first_name']=ucfirst($rows['first_name']);
                  $data['last_name']=ucfirst($rows['last_name']);
                  $data['mobileno']=$rows['mobileno'];
                  $data['dob']=$rows['dob'];
                  $data['age']=age_calculate($rows['dob']);
                  $data['blood_group']=$rows['blood_group'];
                  $data['gender']=$rows['gender'];
                  $data['cityname']=$rows['cityname'];
                  $data['countryname']=$rows['countryname'];
                  $data['patient_list_count']=strval($patient_list_count);
                  $patresult[]=$data;
                }
            }

                $pages = !empty($page)?$page:1;
                $patient_list_count = ceil($patient_list_count/$limit);
                $next_page    = $pages + 1;
                $next_page    = ($next_page <=$patient_list_count)?$next_page:-1;

                $response['patient_list']=$patresult;
                $response['patient_count']=strval($patient_list_count);
                $response['next_page']=$next_page;
                $response['current_page']=$page;
            
           
          
            if(empty($response['patient_list']))
            {
                 $response_code = '201';
                 $response_message = "No Results found";
            }
            else
            {
                 $response_code = '200';
                 $response_message = "";
            }

            $response_data=$response;
                         
            $result = $this->data_format($response_code,$response_message,$response_data);

            $this->response($result, REST_Controller::HTTP_OK);

           }
           else
            {
              $this->token_error();
            }
     }

     public function my_doctors_post()
     {
           if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $docresult=array();
                $user_data = array();
                $user_data = $this->post();

                $page = $user_data['page'];
                $limit = $user_data['limit'];

               
            
               $doctor_list_count = $this->api->my_doctor_lists($page,$limit,1,$this->user_id);
               $doctor_list = $this->api->my_doctor_lists($page,$limit,2,$this->user_id); 
  
                              
              if (!empty($doctor_list)) {
                foreach ($doctor_list as $rows) {
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
                  $docresult[]=$data;
                }
            }

                $pages = !empty($page)?$page:1;
                $doctor_list_count = ceil($doctor_list_count/$limit);
                $next_page    = $pages + 1;
                $next_page    = ($next_page <=$doctor_list_count)?$next_page:-1;

                $response['doctor_list']=$docresult;
                $response['next_page']=$next_page;
                $response['current_page']=$page;
            
           
          
            if(empty($response['doctor_list']))
            {
                 $response_code = '201';
                 $response_message = "No Results found";
            }
            else
            {
                 $response_code = '200';
                 $response_message = "";
            }

            $response_data=$response;
                         
            $result = $this->data_format($response_code,$response_message,$response_data);

            $this->response($result, REST_Controller::HTTP_OK);

           }
           else
            {
              $this->token_error();
            }
     }

     public function add_favourities_post()
    {
         if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();

        
      if(!empty($user_data['doctor_id']))
      {   

        if($this->role==2)
        {
            $inputdata['doctor_id']=$user_data['doctor_id'];
            $inputdata['patient_id']=$this->user_id;

            $where=array('doctor_id' =>$inputdata['doctor_id'],'patient_id'=>$inputdata['patient_id']);
            $already_favourities=$this->db->get_where('favourities',$where)->result_array();
            if(count($already_favourities) > 0 )
            {
               $this->db->where('doctor_id',$inputdata['doctor_id']);
               $this->db->where('patient_id',$inputdata['patient_id']);
               $this->db->delete('favourities');

               $response_code='200';
               $response_message='Favorites Removed Successfully';
            }
            else
            {
                $this->db->insert('favourities',$inputdata);
                $response_code='200';
               $response_message='Favorites Added Successfully';
            }
        }
        else
        {
              $response_code='500';
              $response_message='Invalid login';
        }

         
            
        }
        else
        {
               $response_code='500';
               $response_message='Inputs field missing';
        }

             
           $result = $this->data_array_format($response_code,$response_message,$response);
           $this->response($result, REST_Controller::HTTP_OK);
         
         }
         else
          {
            $this->token_array_error();
          }
}

// public function change_appoinments_status_post()
// {
//          if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
//               $data=array();
//               $user_data = array();
//               $response=array();
//               $user_data = $this->post();
//               $nresponse=array();
//               $device_type="";

        
//       if(!empty($user_data['appoinments_id']) && !empty($user_data['appoinments_status']) )
//       {   
//         $appoinments_status=$user_data['appoinments_status'];
//         if($user_data['appoinments_status']==2)
//         {
//           $appoinments_status=0;
//         }

//            $this->db->where('id',$user_data['appoinments_id'])->update('appointments',array('approved' =>$user_data['appoinments_status']));

//              $appoinments_details = $this->api->get_appoinment_call_details($user_data['appoinments_id']);
//              // print_r($appoinments_details);exit();
//               if($this->role==1)
//               {
//                 $notifydata['include_player_ids'] = $appoinments_details['patient_device_id'];
//                 $device_type = $appoinments_details['patient_device_type'];
//                 $nresponse['from_name']=$appoinments_details['doctor_name'];
//               }
//               if($this->role==2)
//               {
//                 $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
//                 $device_type = $appoinments_details['doctor_device_type'];
//                 $nresponse['from_name']=$appoinments_details['patient_name'];
//               }



//            if($user_data['appoinments_status']=='1')
//            {
//                  $response_code='200';
//                  $response_message='Appoinments approved Successfully';
//                  $notifydata['message']=$nresponse['from_name'].' has accepted the appointment';
//            }else{
//                  $response_code='200';
//                  $response_message='Appoinments cancelled Successfully';
//                  $notifydata['message']=$nresponse['from_name'].' has cancelled the appointment';
//            }


//               $notifydata['notifications_title']='';
//               $notifydata['additional_data'] = $nresponse;
//               if($device_type=='Android')
//               {
//                 sendFCMNotification($notifydata);
//               }
//               if($device_type=='IOS')
//               {
//                 sendiosNotification($notifydata);
//               }

         
            
//         }
//         else
//         {
//                $response_code='500';
//                $response_message='Inputs field missing';
//         }

             
//            $result = $this->data_array_format($response_code,$response_message,$response);
//            $this->response($result, REST_Controller::HTTP_OK);
         
//          }
//          else
//           {
//             $this->token_array_error();
//           }
// }






public function change_appoinments_status_post()
{
         if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();

        
      if(!empty($user_data['appoinments_id']) && !empty($user_data['appoinments_status']) )
      {   
        $appoinments_status=$user_data['appoinments_status'];
        if($user_data['appoinments_status']==2)
        {
          $appoinments_status=0;
        }

           $this->db->where('id',$user_data['appoinments_id'])->update('appointments',array('approved' =>$user_data['appoinments_status']));

             $appoinments_details = $this->api->get_appoinment_call_details($user_data['appoinments_id']);
              if($this->role==1)
              {
                $notifydata['include_player_ids'] = $appoinments_details['patient_device_id'];
                $device_type = $appoinments_details['patient_device_type'];
                $nresponse['from_name']=$appoinments_details['doctor_name'];
              }
              if($this->role==2)
              {
                $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
                $device_type = $appoinments_details['doctor_device_type'];
                $nresponse['from_name']=$appoinments_details['patient_name'];
              }



           if($user_data['appoinments_status']=='1')
           {
                 $response_code='200';
                 $response_message='Appoinments approved Successfully';
                 $notifydata['message']=$nresponse['from_name'].' has accepted the appointment';
         
        // Web Notification
        $notification=array(
                     'user_id'=>$appoinments_details['doctor_id'],
                     'to_user_id'=>$appoinments_details['patient_id'],
                     'type'=>"Appointment Accept",
                     'text'=>"has accepted the appointment of",
                     'created_at'=>date("Y-m-d H:i:s"),
                     'time_zone'=>$appoinments_details['time_zone']
        );
        
        $this->db->insert('notification',$notification);
         
         
           }else{
                 $response_code='200';
                 $response_message='Appoinments cancelled Successfully';
                 $notifydata['message']=$nresponse['from_name'].' has cancelled the appointment';
         
        //Web notification 
        $notification=array(
                     'user_id'=>$appoinments_details['doctor_id'],
                     'to_user_id'=>$appoinments_details['patient_id'],
                     'type'=>"Appointment Cancel",
                     'text'=>"has cancelled the appointment of",
                     'created_at'=>date("Y-m-d H:i:s"),
                     'time_zone'=>$appoinments_details['time_zone']
         );
         
         $this->db->insert('notification',$notification);
         
           }


              $notifydata['notifications_title']='';
              $notifydata['additional_data'] = $nresponse;

              if(!empty($notifydata['include_player_ids']))
              {
                if($device_type=='Android')
                {
                  sendFCMNotification($notifydata);
                }
                if($device_type=='IOS')
                {
                  sendiosNotification($notifydata);
                }
              }
         
            
        }
        else
        {
               $response_code='500';
               $response_message='Inputs field missing';
        }

             
           $result = $this->data_array_format($response_code,$response_message,$response);
           $this->response($result, REST_Controller::HTTP_OK);
         
         }
         else
          {
            $this->token_array_error();
          }
}

public function add_reviews_post()
    {
         if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();

      if(!empty($user_data['doctor_id']) && !empty($user_data['rating']) && !empty($user_data['title'])&& !empty($user_data['review']))
      {   

              $inputdata['doctor_id']=$user_data['doctor_id'];
              $inputdata['user_id']=$this->user_id;
              $inputdata['rating']=$user_data['rating'];
              $inputdata['title']=$user_data['title'];
              $inputdata['review']=$user_data['review'];
              $inputdata['created_date'] = date('Y-m-d H:i:s');
              $inputdata['time_zone'] = $this->time_zone;

             $this->db->insert('rating_reviews',$inputdata);

                $response_code='200';
                $response_message='Reviews Added Successfully';
           
        
            
        }
        else
        {
               $response_code='500';
               $response_message='Inputs field missing';
        }

             
           $result = $this->data_array_format($response_code,$response_message,$response);
           $this->response($result, REST_Controller::HTTP_OK);
         
         }
         else
          {
            $this->token_array_error();
          }
}

 public function reviews_list_post()
{
            if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $user_data = array();
                $user_data = $this->post();

                $page = $user_data['page'];
                $limit = $user_data['limit'];

            
               $reviews_list_count = $this->api->review_list($page,$limit,1,$this->user_id,$this->role);
               $reviews_list = $this->api->review_list($page,$limit,2,$this->user_id,$this->role);                              

                $pages = !empty($page)?$page:1;
                $reviews_list_count = ceil($reviews_list_count/$limit);
                $next_page    = $pages + 1;
                $next_page    = ($next_page <=$reviews_list_count)?$next_page:-1;

                $response['reviews_list']=$reviews_list;
                $response['next_page']=$next_page;
                $response['current_page']=$page;
            
                          
            if(empty($reviews_list))
            {
                 $response_code = '201';
                 $response_message = "No Results found";
            }
            else
            {
                 $response_code = '200';
                 $response_message = "";
            }

            $response_data=$response;
                         
            $result = $this->data_format($response_code,$response_message,$response_data);

            $this->response($result, REST_Controller::HTTP_OK);

           }
           else
            {
              $this->token_error();
            }
     }

     public function dashboard_count_post()
     {
          if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $user_data = array();
                $user_data = $this->post();
                
               if(empty($user_data['patient_id']))
               {
                 $patient_id='0';
               }
               else
               {
                 $patient_id=$user_data['patient_id'];
               }

                  if($this->role==1)
                  {
                    $patient_id=$user_data['patient_id'];
                    
                  }
                  if($this->role==2)
                  {
                    $patient_id=$this->user_id;
                    
                  }

                  $user_id=$this->user_id;

                    

                      $response['doctors']['my_patients_count']=strval($this->api->patients_lists(1,1,1,$this->user_id));
                      $response['doctors']['appoinments_count']=strval($this->api->appointments_count('',$user_id,$this->role));
                      $response['doctors']['today_appoinments_count']=strval($this->api->appointments_count('1',$user_id,$this->role));
                      $response['doctors']['upcoming_appoinments_count']=strval($this->api->appointments_count('2',$user_id,$this->role));

                      $response['doctors']['doctor_details']=$this->api->doctor_details($user_id);

                       $response['patients']['appoinments_count']=strval($this->api->appointments_count('',$user_id,$this->role));
                      $response['patients']['today_appoinments_count']=strval($this->api->appointments_count('1',$user_id,$this->role));
                      $response['patients']['upcoming_appoinments_count']=strval($this->api->appointments_count('2',$user_id,$this->role));
                      $response['patients']['prescriptions_count']=strval($this->api->prescription_list(1,1,1,$patient_id,$this->user_id,$this->role));
                      $response['patients']['medical_count']=strval($this->api->medical_records_list(1,1,1,$patient_id,$this->user_id,$this->role));
                      $response['patients']['billing_count']=strval($this->api->billing_list(1,1,1,$patient_id,$this->user_id,$this->role));
                      $response['patients']['patient_details']=$this->api->patient_details($patient_id);
                      
                        $response_code='200';
                        $response_message='';               
                 
              

                         

            $response_data=$response;
                         
            $result = $this->data_format($response_code,$response_message,$response_data);

            $this->response($result, REST_Controller::HTTP_OK);

           }
           else
            {
              $this->token_error();
            }
     }


     public function favourities_list_post()
     {
            if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $favresult=array();
                $user_data = array();
                $user_data = $this->post();

                $page = $user_data['page'];
                $limit = $user_data['limit'];

               
            
               $favourities_list_count = $this->api->get_favourites($page,$limit,1,$this->user_id);
               $favourities_list = $this->api->get_favourites($page,$limit,2,$this->user_id); 
  
                              
              if (!empty($favourities_list)) {
                foreach ($favourities_list as $rows) {
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
                  $favresult[]=$data;
                }
            }

                $pages = !empty($page)?$page:1;
                $favourities_list_count = ceil($favourities_list_count/$limit);
                $next_page    = $pages + 1;
                $next_page    = ($next_page <=$favourities_list_count)?$next_page:-1;

                $response['doctor_list']=$favresult;
                $response['next_page']=$next_page;
                $response['current_page']=$page;
            
           
          
            if(empty($response['doctor_list']))
            {
                 $response_code = '201';
                 $response_message = "No Results found";
            }
            else
            {
                 $response_code = '200';
                 $response_message = "";
            }

            $response_data=$response;
                         
            $result = $this->data_format($response_code,$response_message,$response_data);

            $this->response($result, REST_Controller::HTTP_OK);

           }
           else
            {
              $this->token_error();
            }
}

public function chat_users_get()
{
  if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
               
                $response=array();
                $result=array();
                $users_list=array();
                     if($this->role=='1')
                     {
                       $users_list=$this->api->get_patients($this->user_id);
                     }
                       
                     if($this->role=='2')
                     {
                       $users_list=$this->api->get_doctors($this->user_id);  
                     }

                     

                     foreach ($users_list as $rows) {

                      $last_chat=$this->db->query('SELECT * FROM chat WHERE (recieved_id='.$this->user_id.' AND sent_id='.$rows['userid'].') OR (recieved_id='.$rows['userid'].' AND sent_id='.$this->user_id.') ORDER BY id DESC LIMIT 1')->row_array();
                    

                       $row['userid']=$rows['userid'];
                       $row['role']=$rows['role'];
                       $row['first_name']=$rows['first_name'];
                       $row['last_name']=$rows['last_name'];
                       $row['username']=$rows['username'];
                       $row['profileimage']=$rows['profileimage'];
                       $row['chatdate']=($last_chat['chatdate']==null)?'':$last_chat['chatdate'];
                       $row['lastchat']=($last_chat['msg']==null)?'Welcome to doccure':$last_chat['msg'];
                       $response[]=$row;
                     }

                     $response_data['chat_list']=$response;



                     if(empty($users_list))
                      {
                           $response_code = '201';
                           $response_message = "No Results found";
                      }
                      else
                      {
                           $response_code = '200';
                           $response_message = "";
                      }
                    
                                     

                
                 $result = $this->data_format($response_code,$response_message,$response_data);
                 $this->response($result, REST_Controller::HTTP_OK);

              }
            else
            {
              $this->token_error();
            }
} 


     public function conversation_post()
    {
          $datas=array();
          if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();
              
      
            if(!empty($user_data['user_id']))
            { 
              
                  $user_id = $this->user_id;
                  $selected_user = $user_data['user_id']; /* Selected user  id */
                  $time_zone = $this->time_zone;


              $latest_chat= $this->api->get_latest_chat($selected_user,$user_id); 
              if(!empty($latest_chat)){
                foreach($latest_chat as $key => $currentuser) { 

                  $from_timezone = $currentuser['time_zone'];
                  $date_time = $currentuser['chatdate'];
                  $date_time  = converToTz($date_time,$time_zone,$from_timezone);

                  $msgdata['chat_time'] = date('Y-m-d H:i:s',strtotime($date_time));
                  $type = $currentuser['type'];        
                  $attachment_file = ($currentuser['file_path'])?($currentuser['file_path'].'/'.$currentuser['file_name']):'';        
                  $message = $currentuser['msg'];      

                 $msgdata['type']=$currentuser['type'];
                 $msgdata['file_name']=($currentuser['file_path'])?($currentuser['file_path'].'/'.$currentuser['file_name']):''; 
                  $msgdata['msg_type'] = ($currentuser['sender_id'] != $user_id) ? 'received' : 'sent';
                  $msgdata['chat_from']= $currentuser['sender_id'];  
                  $msgdata['chat_to']= $currentuser['receiver_id'];          
                  $msgdata['from_user_name']= $currentuser['senderName'];  
                  $msgdata['to_user_name']= $currentuser['receiverName'];  
                  $msgdata['profile_from_image']= (!empty($currentuser['senderImage']))?base_url().$currentuser['senderImage']:base_url().'assets/img/user.png';
                  $msgdata['profile_to_image']= (!empty($currentuser['receiverImage']))?base_url().$currentuser['receiverImage']:base_url().'assets/img/user.png';

                    $msgdata['content']= ($message)?$message:'';
                    $datas[]=$msgdata;    
                 
                      }
                      $response_data['chat_details']=$datas;
                     $response=$response_data; 
                     $response_code='200';
                     $response_message='';

                  }
                  else
                  {
                         $response_code='201';
                         $response_message='No message found';
                  }

           }
            
            else
            {
                   $response_code='500';
                   $response_message='Inputs field missing';
            }

             
             
             $result = $this->data_format($response_code,$response_message,$response);
             $this->response($result, REST_Controller::HTTP_OK);
         
         }
         else
          {
            $this->token_error();
          }
}

   public function send_message_post()
  {   $user_id=0;
      $message="";
      $msgdata=array();
      $response_code="";
      $currentuser=array();
      $response_message="";
      if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();
              
      
            if(!empty($user_data['user_id']) && !empty($user_data['message']))
            {

                $data['recieved_id'] =$user_data['user_id'];
                $data['sent_id'] = $this->user_id;
                $data['time_zone'] = $this->time_zone;
                $data['chatdate'] = date('Y-m-d H:i:s');
                $data['msg'] = $user_data['message'];

                if(!empty($_FILES['userfile']['name'])){

                  $path = "uploads/msg_uploads/".$user_id;
      if(!is_dir($path)){
        mkdir($path,0777,true);
      }

      $target_file =$path . basename($_FILES["userfile"]["name"]);
      $file_type = pathinfo($target_file,PATHINFO_EXTENSION);

      if($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg" && $file_type != "gif" ){
        $type = 'others';
      }else{
        $type = 'image';
      }


      $config['upload_path']   = './'.$path;
      $config['allowed_types'] = '*';   
      $this->load->library('upload',$config);
      if($this->upload->do_upload('userfile')){ 

        $file_name=$this->upload->data('file_name');    
        $data['type'] = $type;
        $data['file_name'] = $file_name;
        $data['file_path'] = $path;
        $data['time_zone'] = $this->time_zone;

      }else{
        $response_code='500';
        $response_message=$this->upload->display_errors();
        
      }


            }
            
            $result = $this->db->insert('chat',$data);
            $chat_id = $this->db->insert_id();
            $users = array($data['recieved_id'],$data['sent_id']);
            for ($i=0; $i <2 ; $i++) { 
              $datas = array('chat_id' =>$chat_id ,'can_view'=>$users[$i]);
              $this->db->insert('chat_deleted_details',$datas);
            }




              $user_id = $this->user_id;
              $selected_user = $user_data['user_id']; /* Selected user  id */
              $time_zone = $this->time_zone;

          $latest_chat= $this->api->get_latest_chat($selected_user,$user_id); 
          if(!empty($latest_chat)){
            foreach($latest_chat as $key => $currentuser) { 

              $from_timezone = $currentuser['time_zone'];
              $date_time = $currentuser['chatdate'];
              $date_time  = converToTz($date_time,$time_zone,$from_timezone);

              $msgdata['chat_time'] = date('Y-m-d H:i:s',strtotime($date_time));
              $type = $currentuser['type'];        
              $attachment_file = ($currentuser['file_path'])?($currentuser['file_path'].'/'.$currentuser['file_name']):'';        
              $message = $currentuser['msg'];      

             // $msgdata['type']=$currentuser['type'];
            $msgdata['type']='message';
             $msgdata['file_name']=($currentuser['file_path'])?($currentuser['file_path'].'/'.$currentuser['file_name']):'';  
             
              $msgdata['msg_type'] = ($currentuser['sender_id'] != $user_id) ? 'received' : 'sent';
              $msgdata['chat_from']= $currentuser['sender_id'];  
              $msgdata['chat_to']= $currentuser['receiver_id'];          
              $msgdata['from_user_name']= $currentuser['senderName'];  
              $msgdata['to_user_name']= $currentuser['receiverName'];  
              $msgdata['profile_from_image']= (!empty($currentuser['senderImage']))?base_url().$currentuser['senderImage']:base_url().'assets/img/user.png';
              $msgdata['profile_to_image']= (!empty($currentuser['receiverImage']))?base_url().$currentuser['receiverImage']:base_url().'assets/img/user.png';

                $msgdata['content']= ($message)?$message:'';
                
               
          }

              $notifydata['include_player_ids'] = $currentuser['receiver_device_id'];
              $device_type = $currentuser['receiver_device_type'];
              $notifydata['message']=$message;
              $notifydata['notifications_title']=$msgdata['from_user_name'];
              $notifydata['additional_data'] = $msgdata;


              if($device_type=='Android')
              {
                sendFCMNotification($notifydata);
              }
              if($device_type=='IOS')
              {
                sendiosNotification($notifydata);
              }


          $response=$msgdata;
          $response_code='200';
          $response_message='';

        }


      }
       
      else
      {
             $response_code='500';
             $response_message='Inputs field missing';
      }

         
         
         $result = $this->data_format($response_code,$response_message,$response);
         $this->response($result, REST_Controller::HTTP_OK);
     
     }
     else
      {
        $this->token_error();
      }

  }

  public function coupon_list_post()
  { 
      if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {


        $response=array();
        $result=array();
        $user_data = array();
        $user_data = $this->post();
        $page ="";


       
       

         if(!empty($user_data['page']) && !empty($user_data['limit'])){

        $page = $user_data['page'];
        $limit = $user_data['limit'];
       
        

        

        $response['coupon_list']=$result;
        
        $coupon_list_count = $this->api->coupon_list($page,$limit,1);
        $coupon_list = $this->api->coupon_list($page,$limit,2); 
        

        
        if (!empty($coupon_list)) {
          

          $pages = !empty($page)?$page:1;
          $coupon_list_count = ceil($coupon_list_count/$limit);
          $next_page    = $pages + 1;
          $next_page    = ($next_page <= $coupon_list_count)?$next_page:-1;

          $response['coupon_list']=$coupon_list;
          $response['next_page']=$next_page;
          $response['current_page']=$page;
        }
      }else{

          $coupon_list = $this->api->coupon_dropdown(); 
          $response['coupon_list']=$coupon_list;
      }
        
        
        if(empty($response['coupon_list']))
        {
         $response_code = '201';
         $response_message = "No Results found";
       }
       else
       {
         $response_code = '200';
         $response_message = "Coupon list";
       }


      $response_data=$response;

      $result = $this->data_format($response_code,$response_message,$response_data);

       $this->response($result, REST_Controller::HTTP_OK);

    }
    else
    {
      $this->token_error();
    }
  }

  public function logout_get()
  {
          if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

                $response=array();
                $result=array();
                
                 $this->api->logout($this->user_id);
                
                 $response_code = '200';
                 $response_message = "Logout successfully";
                 $response_data=$response;
                 $result = $this->data_format($response_code,$response_message,$response_data);
                 $this->response($result, REST_Controller::HTTP_OK);

              }
            else
            {
              $this->token_error();
            }
  }


  public function data_format($response_code,$response_message,$data)
  {
    $final_result = array();
    $response = array();
    $response['response_code']    = $response_code;
    $response['response_message'] = $response_message;
    $response['base_url'] = base_url();
    $final_result['response'] = $response;
    if(!empty($data))
    {
      $final_result['data'] = $data;
    }
    else
    {
      $final_result['data'] = new stdClass();
    }
    

    return $final_result;
  }


  public function data_array_format($response_code,$response_message,$data)
  {
    $final_result = array();
    $response = array();
    $response['response_code']    = $response_code;
    $response['response_message'] = $response_message;
    $response['base_url'] = base_url();
    $final_result['response'] = $response;
    if(!empty($data))
    {
      $final_result['data'] = $data;
    }
    else
    {
      $final_result['data'] = array();
    }
    

    return $final_result;
  }

  public function token_error(){
    $response_code = '498';
    $response_message = "Invalid token or token missing";
    $data=new stdClass();
    $result = $this->data_format($response_code,$response_message,$data);

    $this->response($result, REST_Controller::HTTP_OK);
  }

  public function token_array_error(){
    $response_code = '498';
    $response_message = "Invalid token or token missing";
    $data=array();
    $result = $this->data_format($response_code,$response_message,$data);

    $this->response($result, REST_Controller::HTTP_OK);
  }

  public function search_pharmacy_post(){
    $response=array();
    $result=array();
    $response_code="";
    $response_message="";
    $page = 1;
    $limit=10;        
    $user_data = $this->post();
    $response['count'] =$this->api->search_pharmacy_new($page,$limit,1,$user_data);
    $pharmacy_list = $this->api->search_pharmacy_new($page,$limit,2,$user_data);

    if (!empty($pharmacy_list)) {
      foreach ($pharmacy_list as $rows) {

        $data['id']=$rows['pharmacy_id'];
        $data['pharmacy_name'] = (!empty($rows['pharmacy_name'])) ? ucfirst($rows['pharmacy_name']) : ucfirst($rows['first_name']).' '.$rows['last_name'];
        $data['profileimage']=(!empty($rows['profileimage']))?base_url().$rows['profileimage']:base_url().'assets/img/user.png';
        $data['phonecode']=$rows['phonecode'];
        $data['mobileno']=$rows['mobileno'];
        $data['address1']=$rows['address1'];
        $data['address2']=$rows['address2'];
        $data['city']=$rows['city'];
        $data['statename']=$rows['statename'];
        $data['country']=$rows['country'];
        $data['pharmacy_opens_at'] = date('g:iA', strtotime($rows['pharamcy_opens_at']));
        $result[]=$data;
        $response_code = '200';
        $response_message = "";
      }
    }else{
      $response_code = '201';
      $response_message = "No Results found";
    }
    $response['current_page_no']= $page;
    $response['total_page']= ceil($response['count']/$limit);
    $response['data']= $result;
    
    $result = $this->data_format($response_code,$response_message,$response);
    $this->response($result, REST_Controller::HTTP_OK);
  }

  public function get_phamacy_details_post() {
    $response = array();
    $user_data = $this->post();
    $pharmacy_id = $user_data['pharmacy_id'];
    $data = $get_pharmacy_details = $this->api->get_selected_pharmacy_details($pharmacy_id);
    if(!empty($get_pharmacy_details)) {
      $data = $data[0];
      $response_code = '200';
      $response_message = "";
      $response = $data;
    } else {
      $response_code = '500';
      $response_message = "Pharmacy Not Available";
    }
    $result = $this->data_format($response_code,$response_message,$response);
    $this->response($result, REST_Controller::HTTP_OK);
  }

  public function pharmacy_product_and_category_list_post(){
      
    $response = array();
    $user_data = $this->post();
    $pharmacy_id = $user_data['pharmacy_id'];
    $categorys = $this->api->get_pharmacy_category();
	
    $catedata=array();
    foreach($categorys as $cate){
      $cate_id =  $cate['id'];
      $subcategorys  = $this->api->get_pharmacy_subcategory($cate_id);
      $sub = array();
      $pr = array();
      $product_data = array();
      if(isset($subcategorys) && !empty($subcategorys)){

        foreach($subcategorys as $subcategory){  
          $sub_category_id = $subcategory['id'];
          $sub_category_name = $subcategory['subcategory_name'];
          $productsData  = $this->api->get_pharmacy_product($subcategory['id'],$pharmacy_id);            
          foreach($productsData as $products){
            $products_curency = array('currency'=>'$');
            $productsData = array_merge($products,$products_curency);
            $product_data[] = $productsData;
          }
          $sub[] = array('sub_category_id'=>$subcategory['id'],'sub_category_name'=>$subcategory['subcategory_name'],'products'=>$product_data);
        }

      }

      $catedata['main_product'][] = array('category_id'=>$cate_id,'category_name'=>$cate['category_name'],'sub_cateogory'=>$sub);
    }
$catedata['unit'] = $this->api->get_pharmacy_unit();
    $response = $catedata;
    $response_code = '200';
    $response_message = "";
    $result = $this->data_array_format($response_code,$response_message,$response);
    $this->response($result, REST_Controller::HTTP_OK);

  }

  public function placeOrder_post(){
    if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
      $data=array();
      $user_data = array();
      $response=array();
      $user_data = $this->post();
      if(!empty($user_data['payment_method']))
      {
        if($user_data['payment_method']=='Free')
        {
          $details=$this->book_free_appoinment($user_data);
          $details=json_decode($details);
          $response_code=$details->code;
          $response_message=$details->message;      
        }
        else
        {
          if($user_data['payment_method']=='1'){
            $details=$this->stripe_pay_for_pharmacy($user_data);
            $details=json_decode($details);
            $response_code=$details->code;
            $response_message=$details->message;
          }
		  else if($user_data['payment_method']=='2'){
            $details=$this->braintree_pay_for_pharmacy($user_data);
            $details=json_decode($details);
            $response_code=$details->code;
            $response_message=$details->message;
          }
          else
          {
            $details=$this->clinic_pay($user_data);
            $details=json_decode($details);
            $response_code=$details->code;
            $response_message=$details->message;
          }
        }
      }
      else
      {
        $response_code='500';
        $response_message='Inputs field missing';
      }
      $response_data=$response;                   
      $result = $this->data_array_format($response_code,$response_message,$response_data);
      $this->response($result, REST_Controller::HTTP_OK);
    }
    else
    {
      $this->token_array_error();
    } 
  }

  public function order_list_post()
  {
    $response=array();
    $result=array();
    $user_data = array();
    $user_data = $this->post();
    $res=array(); 
    $user_data = $this->post();
    $user_id = $user_data['user_id'];                 
    if($this->role != 5){
      $res = $this->api->user_order_list($user_id);
    }else{
      $res = $this->api->pharmacy_order_list($user_id);
    }
      
    if($res){
      $response['order_list'] = $res;
      $response_code='200';
      $response_message='';                      
    }else{
      $response_code='500';
      $response['order_list'] = $res;
      $response_message='Order list empty';
    }
    $result = $this->data_array_format($response_code,$response_message,$response);
    $this->response($result, REST_Controller::HTTP_OK);            
  }

  public function dashboard_order_list_post()
  {
    $response=array();
    $result=array();
    $res=array();
    $user_data = $this->post();
    $user_id = $user_data['user_id'];
    $response['upcoming_order_list'] = array();
    $response['today_order_list'] = array();
    if($this->role != 5){
      $res = $this->api->user_order_list($user_id);
    }else{
      $res = $this->api->pharmacy_order_list($user_id);
    }
    $response['order_list'] = $res;

    if($this->role != 5){
      $upcoming_order_list = $this->api->user_order_list_upcoming($user_id);
      $today_order_list = $this->api->user_order_list_today($user_id);
      $total_order = $this->api->user_order_list($user_id);
    }else{
      $upcoming_order_list = $this->api->pharmacy_order_list_upcoming($user_id);
      $today_order_list = $this->api->pharmacy_order_list_today($user_id);
      $total_order = $this->api->pharmacy_order_list($user_id);
    }
    $response['total_order'] = count($total_order);
    $response['today_total_order'] = count($today_order_list);
    $upcoming_order_list = $upcoming_order_list;
    $today_order_list = $today_order_list;                     
    if($upcoming_order_list){
      $response['upcoming_order_list'] = $upcoming_order_list;
      $response['today_order_list'] = $today_order_list;
      $response_code='200';
      $response_message='';        
    }else{
      $response_code='200';
      $response_message='';
    }
    $result = $this->data_array_format($response_code,$response_message,$response);
    $this->response($result, REST_Controller::HTTP_OK);                
  }

  public function order_details_post()
  {
    $response=array();
    $result=array();
    $user_data = $this->post();
    $res=array();
    $user_data = $this->post();
    $order_id = $user_data['order_id'];
    $user_details = $this->api->user_order_list_based_orderID($order_id);
    $res = $this->api->order_details($order_id);                  
    if($res){
      $response['user_details'] = $user_details[0];
      $response['user_details']['currency']='$';

      $response['order_details'] = $res;


      $response_code='200';
      $response_message='';          
    }else{
      $response_code='500';
      $response_message='Order list empty';
    }
    $result = $this->data_array_format($response_code,$response_message,$response);
    $this->response($result, REST_Controller::HTTP_OK);                
  }
  
  
  
  //paramesh
  public function pharmacy_products_list_post(){
      
    $response = array();
    $user_data = $this->post();
    $pharmacy_id = $user_data['pharmacy_id'];
    $products_list['products'] = $this->api->get_pharmacy_productlist($pharmacy_id);
    
// echo $this->db->last_query();exit;
    $response = $products_list;
    $response_code = '200';
    $response_message = "";
    $result = $this->data_array_format($response_code,$response_message,$response);
    $this->response($result, REST_Controller::HTTP_OK);

  }
  
  function get_single_product_post(){		 
		if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
			$data=array();
			$user_data = array();
			$response=array();
			$user_data = $this->post();
			$product_id = $user_data['id'];
			if($product_id > 0){   
				$results = $this->api->get_single_pharmacy_product($product_id);
				if(!empty($results)){
					$response_code='200';
					$response_message='Edit Product Details';    
					$response['products']=$results;
				} else {
					$response_code='200';
					$response_message='No Product Details Found.';
					$response['products']=new stdClass();
				}
            }else {
                $response_code='500';
                $response_message='Invalid Product ID';
			}
			$result = $this->data_format($response_code,$response_message,$response);
			$this->response($result, REST_Controller::HTTP_OK);
         
        } else {
            $this->token_error();
        }
		 
	}
	
	
	public function create_product_post()
	{
		if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
			$data=array();
			$user_data = array();
			$response=array();
			$user_data = $this->post();
			$response_data=array();
			$user_id=$this->user_id;
			
			if(!empty($user_data['name']) && !empty($user_data['category']) && !empty($user_data['subcategory']) && !empty($user_data['unit_value']) && !empty($user_data['unit']) && !empty($user_data['price']) && !empty($user_data['sale_price']) && !empty($user_data['description']) && !empty($user_data['manufactured_by']) && !empty($user_data['short_description']) && !empty($_FILES['user_file']['name']) )
			{
				$countfiles = count($_FILES['user_file']['name']);
				
				for($i=0;$i<$countfiles;$i++){
				if(!empty($_FILES['user_file']['name'][$i])){
					$path = "uploads/product_image";
					if(!is_dir($path)){
						mkdir($path);         
					}
					$path = $path."/";
					$target_file =$path . basename($_FILES["user_file"]["name"][$i]);
					$file_type = pathinfo($target_file,PATHINFO_EXTENSION);
					if ($_FILES["user_file"]["error"][$i] > 0) {
						echo "Error: " . $_FILES["user_file"]["error"][$i] . "<br>";
					} else {
						
						// if (file_exists($path. $_FILES["user_file"]["name"][$i])) {
						// } else {
						  // move_uploaded_file($_FILES["user_file"]["tmp_name"][$i],$path.$_FILES['user_file']['name'][$i]);
						// }
						
						$base64string = str_replace('data:image/png;base64,', '', $_FILES["user_file"]["name"][$i]);
						
						$base64string = str_replace(' ', '+', $base64string);
						$imgdata = base64_decode($base64string);
						$img_name = $i.time();
						// $img_name = $_FILES["user_file"]["name"][$i];
						$file_name_final=$img_name.'.png';
						move_uploaded_file($_FILES["user_file"]["tmp_name"][$i],'uploads/product_image/'.$file_name_final);
						// file_put_contents('uploads/product_image/'.$file_name_final, $imgdata); 
						
						$source_image= 'uploads/product_image/'.$file_name_final; 
						$upload_url='uploads/product_image/';

						$image_url[] = $this->image_resize1(150,150,$source_image,'150x150_'.$file_name_final,$upload_url);

						$preview_image_url[] = $this->image_resize1(450,300,$source_image,'450x300_'.$file_name_final,$upload_url);
						
						
					  }
					}
				
				}
				
				
				 // $tags = implode(',', $preview_image_url);

				// echo '<pre>';print_r($tags);
				// exit;
				// check if drug name is already added in products
				// $this->_checkAndAddProductName($data['name'], $this->input->post('upload_image_url'));
				$user_data['upload_image_url'] = implode(',', $image_url);
				$user_data['upload_preview_image_url'] = implode(',', $preview_image_url);
				$user_data['user_id']=$user_id;
				$user_data["slug"] = str_slug($user_data["name"]);
				$user_data['created_date']=date('Y-m-d H:i:s');
				$result=$this->api->create_product($user_data);
				$response_code = '200';
				$response_message = "Product added sucessfully";
				$response_data['user_details']="";
			}
			else
			{
			 $response_code='500';
			 $response_message='Inputs field missing';
			 $response_data['user_details']="";
			}
		$result = $this->data_array_format($response_code,$response_message,$response_data);
		$this->response($result, REST_Controller::HTTP_OK);
		}
		else
		{
		  $this->token_error();
		}
}


public function edit_product_post()
	{
		if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
			$data=array();
			$user_data = array();
			$response=array();
			$user_data = $this->post();
			$response_data=array();
			$user_id=$this->user_id;
						
			$config["upload_path"] = './uploads/product_image/';
			$config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF';
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			$image_url = array();
			$preview_image_url = array();
			
			if(!empty($user_data['name']) && !empty($user_data['category']) && !empty($user_data['subcategory']) && !empty($user_data['unit_value']) && !empty($user_data['unit']) && !empty($user_data['price']) && !empty($user_data['sale_price']) && !empty($user_data['description']) && !empty($user_data['manufactured_by']) && !empty($user_data['short_description']) && (!empty($user_data['id']) && $user_data['id'] > 0))
			{
				$countfiles = count($_FILES['user_file']['name']);
				
				for($i=0;$i<$countfiles;$i++){ 
				if(!empty($_FILES['user_file']['name'][$i])){
					$path = "uploads/product_image";
					if(!is_dir($path)){
						mkdir($path);         
					}
					$path = $path."/";
					$target_file =$path . basename($_FILES["user_file"]["name"][$i]);
					$file_type = pathinfo($target_file,PATHINFO_EXTENSION);
					if ($_FILES["user_file"]["error"][$i] > 0) {
						echo "Error: " . $_FILES["user_file"]["error"][$i] . "<br>";
					} else {	
						$_FILES["file"]["name"] = $_FILES["user_file"]["name"][$i];
						$_FILES["file"]["type"] = $_FILES["user_file"]["type"][$i];
						$_FILES["file"]["tmp_name"] = $_FILES["user_file"]["tmp_name"][$i];
						$_FILES["file"]["error"] = $_FILES["user_file"]["error"][$i];
						$_FILES["file"]["size"] = $_FILES["user_file"]["size"][$i];
						
						
						$base64string = str_replace('data:image/png;base64,', '', $_FILES["user_file"]["name"][$i]);
						
						$base64string = str_replace(' ', '+', $base64string);
						$imgdata = base64_decode($base64string);
						$img_name = $i.time();
						// $img_name = $_FILES["user_file"]["name"][$i];
						$file_name_final=$img_name.'.png';
						move_uploaded_file($_FILES["user_file"]["tmp_name"][$i],'uploads/product_image/'.$file_name_final);
						// file_put_contents('uploads/product_image/'.$file_name_final, $imgdata); 
						

						// if ($this->upload->do_upload('file')) {
							// $data = $this->upload->data(); 
							$source_image= 'uploads/product_image/'.$file_name_final; 
							$upload_url='uploads/product_image/';
							$image_url[] = $this->image_resize1(150,150,$source_image,'150x150_'.$file_name_final,$upload_url);
							$preview_image_url[] = $this->image_resize1(450,300,$source_image,'450x300_'.$file_name_final,$upload_url);
							
							// $imageurl = 'uploads/product_image/' . $data["file_name"];
							// $upload_url = 'uploads/product_image/';
							// $image_url[] = $this->image_resize1(150, 150, $imageurl, $data["file_name"], $upload_url);
							// $preview_image_url[] = $this->image_resize1(450, 300, $imageurl, $data["file_name"], $upload_url);
							
						// }
					  }
					}				
				}
				
				if(!empty($image_url)){
					$prdt=$this->db->select('id, upload_image_url, upload_preview_image_url')->get_where('products',array("id"=>$user_data['id']))->row_array();
					$img1=$prdt['upload_image_url']; $img2=$prdt['upload_preview_image_url'];
					if(!empty($img1)) {
						$img1arr=explode(",",$img1); $img2arr=explode(",",$img2);						
						foreach($img1arr as $i1){
							if (file_exists($i1)){
								 unlink($i1);
							}
						}
						foreach($img2arr as $i2){
							if (file_exists($i2)){
								 unlink($i2);
							}
						}
					}
					$user_data['upload_image_url'] = implode(',', $image_url);
					$user_data['upload_preview_image_url'] = implode(',', $preview_image_url);
				}
				
				
				$result=$this->api->update_product($user_data, $user_data['id']);				
				
				$response_code = '200';
				$response_message = "Product Updated sucessfully";				
				//$resdata = $this->api->get_single_pharmacy_product($user_data['id']);
				//$response_data['product_details']=(!empty($resdata))?$resdata:'';
			}
			else
			{
			 $response_code='500';
			 $response_message='Inputs field missing';
			 //$response_data['product_details']="";
			}
			$datas = new stdClass();					
			$result = $this->data_array_format($response_code,$response_message,$datas);
			$this->response($result, REST_Controller::HTTP_OK);
		}
		else
		{
		  $this->token_error();
		}
}


public function pharmacy_accounts_list_post()
    {
		if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
			$data=array();
			$user_data = array();
			$response=array();
			$result=array();
			$user_data = $this->post();
			$user_id = $this->user_id;
			
			$page = $user_data['page'];
            $limit = $user_data['limit'];

			$account_list_count = $this->api->get_pharmacy_acclist($user_id,$page,$limit,1);
            $account_list = $this->api->get_pharmacy_acclist($user_id,$page,$limit,2);
			
			
			if(!empty($account_list)) {				
				foreach ($account_list as $account) {
					$patient_profileimage=(!empty($account['patient_profileimage']))?base_url().$account['patient_profileimage']:base_url().'assets/img/user.png';        
					
					$amount=$account['price'];
					$commission = !empty(settings("commission"))?settings("commission"):"0";
					$commission_charge = ($amount * ($commission/100));
					$total_amount= $amount - $commission_charge;
					$user_currency=get_user_currency_api($this->user_id);
					$user_currency_code=$user_currency['user_currency_code'];
					$user_currency_rate=$user_currency['user_currency_rate'];
					$patient_currency=$account['currency_code'];

					$currency_option = (!empty($user_currency_code))?$user_currency_code:default_currency_code();
					$rate_symbol = currency_code_sign($currency_option);

					$org_amount=get_doccure_currency($total_amount,$patient_currency,$user_currency_code);

					switch ($account['status']) {
						case '0':
							$status=$this->language['lg_new1']='New';
						break;
						case '1':
							$status=$this->language['lg_approved']='Approved';				
						break;
						case '2':
							$status=$this->language['lg_approved']='Approved';	
						break;
						case '3':
							$status=$this->language['lg_payment_request']='Payment Request';
						break; 
						case '4':
							$status=$this->language['lg_payment_receive']='Payment Received';
						break; 
						case '5':
							$status=$this->language['lg_cancelled']='Cancelled';	
						break;  
						case '6':
							$status=$this->language['lg_waiting_for_app']='Waiting for Approval';					
						break;
						case '7':
							$status=$this->language['lg_refund']='Refund';	
						break;

						default:
							$status=$this->language['lg_new1']='New';	
						break;
					}
					$data['id'] = $account['id'];
					$data['date'] = date('d M Y',strtotime($account['payment_date']));
					$data['patient_profileimage']=$patient_profileimage;
					$data['patient_id'] = $account['patient_id'];
					$data['patient_name'] = $account['patient_name'];
					$data['amount'] = $rate_symbol.number_format($org_amount,2,'.',',');
					$data['status'] =$account['status']; 
					$data['statustxt'] =$status; 
					$result[]=$data;
				}
				
				$pages = !empty($page)?$page:1;
                $doctor_list_count = ceil($account_list_count/$limit);
                $next_page    = $pages + 1;
                $next_page    = ($next_page <=$account_list_count)?$next_page:-1;

                
                $response['next_page']=$next_page;
                $response['current_page']=$page;
				
				$response['accounts']=$result;
				$response_code='200';
				$response_message='Pharmacy Account List';    
			} else {
				$response['accounts']=array();
				$response_code='200';
				$response_message='No Details Found';    
			}
			$result = $this->data_format($response_code,$response_message,$response);
			$this->response($result, REST_Controller::HTTP_OK);

		} else {
            $this->token_error();
        }
	}
	
	public function add_account_details_post()
    {
		if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
			$data=array();
			$user_data = array();
			$response=array();
			$user_data = $this->post();
			$inputdata=array();
			$inputdata['user_id']=$user_data['user_id'];
			$inputdata['bank_name']=$user_data['bank_name'];
			$inputdata['branch_name']=$user_data['branch_name'];
			$inputdata['account_no']=$user_data['account_no'];
			$inputdata['account_name']=$user_data['account_name'];
			$inputdata['ifsc_code']=$user_data['ifsc_code'];
			$already_exits=$this->db->where('user_id',$inputdata['user_id'])->get('account_details')->num_rows();
			if($already_exits==0){
				$this->db->insert('account_details',$inputdata);
			} else {
				$this->db->where('user_id',$inputdata['user_id']);
				$this->db->update('account_details',$inputdata);
			}

			$result=($this->db->affected_rows()!= 1)? false:true;
			if($result==true) {
				$response_code='200';
				$response_message='Account Details Updated';    
			} else {
				$response_code='500';
				$response_message='Account Details Not  Updated';    
			}
			$result = $this->data_format($response_code,$response_message,$response);
			$this->response($result, REST_Controller::HTTP_OK);

		} else {
            $this->token_error();
        }
    }
	
	public function get_account_details_post()
    {
		if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
			$data=array();
			$user_data = array();
			$response=array();
			$user_data = $this->post();
			
			$user_id = $this->user_id;
			
			$user_currency=get_user_currency();
			$user_currency_code=$user_currency['user_currency_code'];
			$user_currency_rate=$user_currency['user_currency_rate'];

			$currency_option = (!empty($user_currency_code))?$user_currency_code:default_currency_code();
			$rate_symbol = currency_code_sign($currency_option);

			$response['currency_symbol']=$rate_symbol;
			if($this->role == 5){
				$response['balance'] = (string)round($this->accounts->get_balance_pharmacy($user_id));
			} else if($this->role == 2){
				$response['balance'] = (string)$this->accounts->get_patient_balance($user_id);
			} else {
				$response['balance'] = (string)$this->accounts->get_balance($user_id);
			}
			$response['requested'] = (string)$this->accounts->get_requested($user_id);
			$response['earned'] = (string)$this->accounts->get_earned($user_id);
			$acc=$this->accounts->get_account_details($user_id);
			$response['account_details'] = !(empty($acc))?$acc:new stdClass();
			  
			$response_code='200';
			$response_message='Account Details.';
			$result = $this->data_format($response_code,$response_message,$response);
			$this->response($result, REST_Controller::HTTP_OK);
         
		} else {
            $this->token_error();
        }
	}
	
	public function update_pharamcy_profile_post()
     {
        if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
			$data=array();
			$user_data = array();
			$response=array();
			$user_data = $this->post();
        
			if(!empty($user_data['pharmacy_name']) && !empty($user_data['first_name']) && !empty($user_data['last_name']) && !empty($user_data['gender']) && !empty($user_data['dob']) && !empty($user_data['blood_group']) && !empty($user_data['address1']) && !empty($user_data['address2']) && !empty($user_data['country'])&& !empty($user_data['state'])&&    !empty($user_data['city'])&& !empty($user_data['postal_code']))
			{   
                if($_FILES["profile_image"]["name"] != '')
                {
					$config["upload_path"] = './uploads/profileimage/temp/';
					$config["allowed_types"] = '*';
					$this->load->library('upload', $config);
					$this->upload->initialize($config);

					$_FILES["file"]["name"] = 'img_'.time().'.png';
					$_FILES["file"]["type"] = $_FILES["profile_image"]["type"];
					$_FILES["file"]["tmp_name"] = $_FILES["profile_image"]["tmp_name"];
					$_FILES["file"]["error"] = $_FILES["profile_image"]["error"];
					$_FILES["file"]["size"] = $_FILES["profile_image"]["size"];
					// if($this->upload->do_upload('file'))
					// {
						
						// $upload_data = $this->upload->data();
						
						$base64string = str_replace('data:image/png;base64,', '', $_FILES["profile_image"]["name"]);
						
						$base64string = str_replace(' ', '+', $base64string);
						$imgdata = base64_decode($base64string);
						$img_name = $_FILES["profile_image"]["name"];
						// $img_name = $_FILES["user_file"]["name"][$i];
						// $file_name_final=$img_name.'.png';
						$file_name_final=$img_name;
						move_uploaded_file($_FILES["profile_image"]["tmp_name"],'uploads/profileimage/temp/'.$file_name_final);
						// file_put_contents('uploads/product_image/'.$file_name_final, $imgdata); 
						

						// if ($this->upload->do_upload('file')) {
							// $data = $this->upload->data(); 
							$source_image= 'uploads/profileimage/temp/'.$file_name_final; 
							$upload_url='uploads/profileimage/temp/';
							$inputdata['profileimage'] = $this->image_resize1(200,200,$source_image,'200x200_'.$file_name_final,$upload_url);
							
						// $profile_img='uploads/profileimage/temp/'.$upload_data["file_name"];
						// $inputdata['profileimage']= $this->image_resize(200, 200, $profile_img, $upload_data["file_name"]);
					// }
                }
				
				$inputdata['pharmacy_name']=$user_data['pharmacy_name'];
                $inputdata['first_name']=$user_data['first_name'];
                $inputdata['last_name']=$user_data['last_name'];
                $inputdata['is_updated']=1;

                $userdata['user_id']=$this->user_id;
                $userdata['gender']=$user_data['gender'];
                $userdata['dob']=date('Y-m-d',strtotime(str_replace('/', '-', $user_data['dob'])));
                $userdata['blood_group']=$user_data['blood_group'];
                $userdata['address1']=$user_data['address1'];
                $userdata['address2']=$user_data['address2'];
                $userdata['country']=$user_data['country'];
                $userdata['state']=$user_data['state'];
                $userdata['city']=$user_data['city'];
                $userdata['postal_code']=$user_data['postal_code'];
                $userdata['update_at']=date('Y-m-d H:i:s');
				
				$specificdata['pharmacy_id']=$this->user_id;
				$specificdata['home_delivery']=$user_data['home_delivery'];
				$specificdata['24hrsopen']=$user_data['24hrsopen'];
				$specificdata['pharamcy_opens_at']=$user_data['pharamcy_opens_at'];
				$specificdata['status']=1;

                $results=$this->api->update_pharamcy_profile($inputdata,$userdata,$specificdata,$this->user_id);

				$response_code='200';
                $response_message='Profile successfully updated';  
      
            
          }
          else
          {
                 $response_code='500';
                 $response_message='Inputs field missing';
          }

          $response['pharmacy_profile_details']=$this->api->get_pharmacy_profile_details($this->user_id);

             
           $result = $this->data_format($response_code,$response_message,$response);
           $this->response($result, REST_Controller::HTTP_OK);
         
         }
         else
          {
            $this->token_error();
          }
     }

 
public function image_resize1($width=0,$height=0,$image_url,$filename,$upload_url){          
        
    $source_path = FCPATH.$image_url;
    list($source_width, $source_height, $source_type) = getimagesize($source_path);
    switch ($source_type) {
        case IMAGETYPE_GIF:
            $source_gdim = imagecreatefromgif($source_path);
            break;
        case IMAGETYPE_JPEG:
            $source_gdim = imagecreatefromjpeg($source_path);
            break;
        case IMAGETYPE_PNG:
            $source_gdim = imagecreatefrompng($source_path);
            break;
    }

    $source_aspect_ratio = $source_width / $source_height;
    $desired_aspect_ratio = $width / $height;

    if ($source_aspect_ratio > $desired_aspect_ratio) {
        /*
         * Triggered when source image is wider
         */
        $temp_height = $height;
        $temp_width = ( int ) ($height * $source_aspect_ratio);
    } else {
        /*
         * Triggered otherwise (i.e. source image is similar or taller)
         */
        $temp_width = $width;
        $temp_height = ( int ) ($width / $source_aspect_ratio);
    }

    /*
     * Resize the image into a temporary GD image
     */

    $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
    imagecopyresampled(
        $temp_gdim,
        $source_gdim,
        0, 0,
        0, 0,
        $temp_width, $temp_height,
        $source_width, $source_height
    );

    /*
     * Copy cropped region from temporary image into the desired GD image
     */

    $x0 = ($temp_width - $width) / 2;
    $y0 = ($temp_height - $height) / 2;
    $desired_gdim = imagecreatetruecolor($width, $height);
    imagecopy(
        $desired_gdim,
        $temp_gdim,
        0, 0,
        $x0, $y0,
        $width, $height
    );

    /*
     * Render the image
     * Alternatively, you can save the image in file-system or database
     */

    $image_url =  $upload_url.$filename;    

    imagepng($desired_gdim,$image_url);

    return $image_url;

    /*
     * Add clean-up code here
     */
  }
  
  


public function lab_dashboard_post()
{
  if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

    $response=array();
    $user_data = array();
    $ldata=array();
    $lresult = array();
    $user_data = $this->post();
   
    $user_id=$this->user_id;

    $lab_list =$this->api->lab_info($user_id);

      if (!empty($lab_list)) {
          foreach ($lab_list as $rows) {

            $ldata['id']="".$rows['lab_id'];
            $ldata['username']=$rows['username'];
            $ldata['profileimage']=(!empty($rows['profileimage']))?$rows['profileimage']:'assets/img/user.png';
            $ldata['first_name']=ucfirst($rows['first_name']);
            $ldata['last_name']=ucfirst($rows['last_name']);
            $ldata['cityname']="".$rows['cityname'];
            $ldata['countryname']="".$rows['countryname'];
            $ldata['services']="".$rows['services'];
            $ldata['rating_value']=$rows['rating_value'];
            $ldata['rating_count']=$rows['rating_count'];
            $ldata['is_favourite']=$this->api->is_favourite($rows['user_id'],$this->user_id);
           
            $ldata['currency_code']=($rows['currency_code']== null )?'INR':$rows['currency_code'];
           
            
          }

          $response['lab_info']=$ldata;
        }


  

    //$response['total_test'] = strval($this->api->get_total_test($user_id));  
    $response['total_test'] = strval($this->api->get_total_test_new($user_id)); 
  
        
    $response['my_lab_couunt'] = count($lab_list);  
    $response['today_patient'] = strval($this->api->get_today_labpatient($user_id));  
    $response['total_apointments'] =  strval($this->api->get_recent_labbooking($user_id));
    //$response['upcoming_apointments'] =  strval($this->api->get_upcoming_labbooking($user_id));
    $response['upcoming_apointments'] =  strval($this->api->get_upcoming_labbookingnew($user_id));
	
  
  //Patient count
    $response['patient_appoinments_count'] = strval($this->api->get_today_patient_labpatient($user_id));  
    $response['patient_total_apointments'] =  strval($this->api->get_recent_patient_labbooking($user_id));
    $response['patient_upcoming_apointments'] =  strval($this->api->get_upcoming_patient_labbooking($user_id));
	
  
  	  //$response['my_lab_couunt_patient'] = strval($this->api->get_total_labpatient($user_id)); 
    	
   //$response['total_test'] = strval($this->api->get_total_test($user_id));  
    
  	//$response['my_lab_count_patient'] = strval($this->api->get_total_labpatient($user_id)); 
  	
     //get_recent_patient_labbooking
   // my_lab_couunt
//patient_total_apointments 
//- lab appintment
//patient_appoinments_count

//patient_upcoming_apointments
  	
  
  
  
//    $response['total_test'] = strval($this->api->get_total_test($user_id));  
//     $response['my_lab_couunt'] = strval(count($lab_list));  
//     $response['today_patient'] = strval($this->api->get_today_labpatient($user_id));  
//     $response['total_apointments'] =  strval($this->api->get_recent_labbooking($user_id));
//     $response['upcoming_apointments'] =  strval($this->api->get_upcoming_labbooking($user_id));
	
  
  
  /* 
   * my_lab_couunt
patient_total_apointments
patient_appoinments_count
patient_upcoming_apointments
*/
  
  	/* my_lab_couunt" = 1;
        "today_patient" = 0;
        "total_apointments" = 18;
        "total_test" = 1;
        "upcoming_apointments" = 1; */
  
  
  
    $response_code='200';
    $response_message='';               
  
  

  

  $response_data=$response;
  
  $result = $this->data_format($response_code,$response_message,$response_data);

  $this->response($result, REST_Controller::HTTP_OK);

}
else
{
  $this->token_error();
}
}

public function add_labtest_post()
{
 if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
  $data=array();
  $user_data = array();
  $response=array();
  $user_data = $this->post();
  $response_data=array();
 
    $user_data['lab_id'] = $this->user_id;
 
  if(!empty($user_data['lab_test_name']) && !empty($user_data['amount']) && !empty($user_data['lab_id']) )
  {   

    $inputdata['lab_test_name']=$user_data['lab_test_name'];
    $inputdata['amount']=$user_data['amount'];
    $inputdata['lab_id']=$user_data['lab_id'];
    $inputdata['duration']=$user_data['duration'];
    $inputdata['description']=$user_data['description'];
    $inputdata['status']=1;
    $user_currency=get_user_currency_api($inputdata['lab_id']);
    $user_currency_code=$user_currency['user_currency_code'];
    $inputdata['currency_code'] = $user_currency_code; 
    
    $inputdata['created_date']=date('Y-m-d H:i:s');

    $this->db->where('lab_test_name',$inputdata['lab_test_name']);
    $this->db->where('lab_id',$inputdata['lab_id']);

    $already_exits=$this->db->get('lab_tests')->num_rows();
    
    if($already_exits >=1)
    {
      $response_code='500';
      $response_message='This test already exits';
    }
    
    else
    {
      $results=$this->api->labtest_insert($inputdata);
      if($results==true)
      {
       $test_id=$this->db->insert_id();
       $inputdata['id']=$test_id;
       
       
       $response_code='200';
       $response_message='Lab Test added success';
       $resdata['id']=''.$test_id;
       
       $resdata['created_date']=date('Y-m-d H:i:s');
       $response_data['test_details']=$resdata;
     }
     else
     {

       $response_code='500';
       $response_message=' failed';
       $response_data['test_details']="";
       
     } 

   }
   
   
 }
 else
 {
   $response_code='500';
   $response_message='Inputs field missing';
   $response_data['test_details']="";
 }

 

 
 $result = $this->data_array_format($response_code,$response_message,$response_data);
 $this->response($result, REST_Controller::HTTP_OK);
 
}
else
{
  $this->token_error();
}
}


public function edit_labtest_post()
{
 if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
  $data=array();
  $user_data = array();
  $response=array();
  $user_data = $this->post();
  $response_data=array();

  
  if(!empty($user_data['id']) && !empty($user_data['lab_test_name']) && !empty($user_data['amount']) && !empty($user_data['lab_id']) )
  {   


    $inputdata['lab_test_name']=$user_data['lab_test_name'];
    $inputdata['amount']=$user_data['amount'];
    $inputdata['lab_id']=$user_data['lab_id'];
    $inputdata['duration']=$user_data['duration'];
    $inputdata['description']=$user_data['description'];
    $inputdata['status']=$user_data['status'];;
    $user_currency=get_user_currency_api($inputdata['lab_id']);
    $user_currency_code=$user_currency['user_currency_code'];
    $inputdata['currency_code'] = $user_currency_code; 
    
    
    $this->db->where('id !=',$user_data['id']);
    $this->db->where('lab_test_name',$inputdata['lab_test_name']);
    $this->db->where('lab_id',$inputdata['lab_id']);

    $already_exits=$this->db->get('lab_tests')->num_rows();
    
    if($already_exits >=1)
    {
      $response_code='500';
      $response_message='This test already exits';
    }
    
    else
    {
      $this->db->where('id',$user_data['id']);
      $this->db->update('lab_tests',$inputdata);
      $results=($this->db->affected_rows()!= 1)? false:true;
      if($results==true)
      {



       $response_code='200';
       $response_message='Lab Test updated success';
       $resdata['id']=''.$user_data['id'];
       
       
       $response_data['test_details']=$resdata;
     }
     else
     {

       $response_code='500';
       $response_message=' failed';
       $response_data['test_details']="";
       
     } 

   }
   
   
 }
 else
 {
   $response_code='500';
   $response_message='Inputs field missing';
   $response_data['test_details']="";
 }

 

 
 $result = $this->data_array_format($response_code,$response_message,$response_data);
 $this->response($result, REST_Controller::HTTP_OK);
 
}
else
{
  $this->token_error();
}
}


public function lab_testlist_post()
{
  if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

    $response=array();
    $result=array();
    $user_data = array();
    $user_data = $this->post();

    $page = $user_data['page'];
    $limit = $user_data['limit'];
    $lab_id=$user_data['lab_id'];

    $response['test_list']=$result;
    
    $lab_list_count = $this->api->lab_test_lists($page,$limit,1,$lab_id);
    $lab_list = $this->api->lab_test_lists($page,$limit,2,$lab_id); 

    $user_currency=get_user_currency_api($this->user_id);
    
    
    if (!empty($lab_list)) {
      foreach ($lab_list as $rows) {
        $data['id']=$rows['id'];
        $data['lab_id']=$rows['lab_id'];
        $data['profileimage']=(!empty($rows['profileimage']))?$rows['profileimage']:'assets/img/user.png';
        $data['lab_test_name']=ucfirst($rows['lab_test_name']);
        $data['amount']=strval(get_doccure_currency($rows['amount'],$rows['currency_code'],$user_currency['user_currency_code']));
        $data['currency_code']=!empty($user_currency['user_currency_code'])?$user_currency['user_currency_code']:'INR';
        $data['currency_symbol']=$user_currency['user_currency_sign'];
        $data['status']=$rows['status'];
        $data['duration']="".$rows['duration'];
        $data['description']="".$rows['description'];
        $data['created_date']=$rows['created_date'];
        


        
        $result[]=$data;
      }

      $pages = !empty($page)?$page:1;
      $lab_list_count = ceil($lab_list_count/$limit);
      $next_page    = $pages + 1;
      $next_page    = ($next_page <=$lab_list_count)?$next_page:-1;

      $response['test_list']=$result;
      $response['next_page']=$next_page;
      $response['current_page']=$page;
    }
    
    
    if(empty($response['test_list']))
    {
     $response_code = '201';
     $response_message = "No Results found";
   }
   else
   {
     $response_code = '200';
     $response_message = "Lab Test list";
   }

   $response_data=$response;
   
   $result = $this->data_format($response_code,$response_message,$response_data);

   $this->response($result, REST_Controller::HTTP_OK);

 }
 else
 {
  $this->token_error();
}
}

public function my_labs_post()
{
  if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

    $response=array();
    $patresult=array();
    $user_data = array();
    $user_data = $this->post();

    $page = $user_data['page'];
    $limit = $user_data['limit'];

    
    
    $patient_list_count = $this->api->mylabs_lists($page,$limit,1,$this->user_id);
    $patient_list = $this->api->mylabs_lists($page,$limit,2,$this->user_id); 
    
    $lab_count=$patient_list_count;
    if (!empty($patient_list)) {
      foreach ($patient_list as $rows) {
        $data['id']=$rows['user_id'];
        $data['lab_id']=$rows['user_id'];
        $data['username']=$rows['username'];
        $data['profileimage']=(!empty($rows['profileimage']))?base_url().$rows['profileimage']:base_url().'assets/img/user.png';
        $data['first_name']=ucfirst($rows['first_name']);
        $data['last_name']=ucfirst($rows['last_name']);
        $data['mobileno']=$rows['mobileno'];
        
        
        $data['cityname']=$rows['cityname'];
        $data['countryname']=$rows['countryname'];
        $data['lab_list_count']=strval($patient_list_count);
        $patresult[]=$data;
      }
    }

    $pages = !empty($page)?$page:1;
    $patient_list_count = ceil($patient_list_count/$limit);
    $next_page    = $pages + 1;
    $next_page    = ($next_page <=$patient_list_count)?$next_page:-1;

    $response['lab_list']=$patresult;
    $response['mylab_count']=strval($lab_count);
    $response['next_page']=$next_page;
    $response['current_page']=$page;
    
    
    
    if(empty($response['lab_list']))
    {
     $response_code = '201';
     $response_message = "No Results found";
   }
   else
   {
     $response_code = '200';
     $response_message = "";
   }

   $response_data=$response;
   
   $result = $this->data_format($response_code,$response_message,$response_data);

   $this->response($result, REST_Controller::HTTP_OK);

 }
 else
 {
  $this->token_error();
}
}

public function lab_appointments_post()
{
   if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {



    $response=array();
    $docresult=array();
    $user_data = array();
    $user_data = $this->post();
    $appresult= array();
    $page = $user_data['page'];
    $limit = $user_data['limit'];

    
    
    $appointments_list_count = $this->api->lab_appointments_lists($page,$limit,1,$user_data,$this->user_id,$this->role);
    $appointments_list = $this->api->lab_appointments_lists($page,$limit,2,$user_data,$this->user_id,$this->role); 
    $user_currency=get_user_currency_api($this->user_id);
    
    if (!empty($appointments_list)) {
      foreach ($appointments_list as $rows) {

        $data['id']=$rows['id'];
        $data['profileimage']=(!empty($rows['profileimage']))?$rows['profileimage']:'assets/img/user.png';
        $data['first_name']=ucfirst($rows['first_name']);
        $data['last_name']=ucfirst($rows['last_name']);
        
        $data['patient_id']=$rows['patient_id'];
        $data['lab_id']=$rows['lab_id'];
        $data['date']=$rows['lab_test_date'];
        $data['status']=$rows['status'];

        $data['amount']=strval(get_doccure_currency($rows['total_amount'],$rows['currency_code'],$user_currency['user_currency_code']));
        $data['currency_code']=!empty($user_currency['user_currency_code'])?$user_currency['user_currency_code']:'INR';
        $data['currency_symbol']=$user_currency['user_currency_sign'];
        
             $test_name="";
            $array_ids=explode(',', $rows['booking_ids']);
            foreach ($array_ids as $key => $value) {
                $this->db->select('*');
                $this->db->where('id',$value);
                $query = $this->db->get('lab_tests');
                $result = $query->row_array();
                if($key > 0){
                    $test_name .=",";
                }
                $test_name .=$result['lab_test_name'];


                
            }
         $data['test_name']=$test_name;

         $path="uploads/lab_result/".$rows['id'];

         $file_array=array();

         foreach(glob($path.'/*.*') as $file) {

          $ext = pathinfo($file, PATHINFO_EXTENSION);

           if($ext == "png" || $ext == "jpg" || $ext == "jpeg"){

            $type='1';

        }else if($ext == "pdf"){
             $type='2';

        }else{
          $type='3';
        }
            $value=array("type"=>$type,"file"=>$file);
            

       
          array_push($file_array,$value);
    
            }

            

        $data['documents']=$file_array;
        $appresult[]=$data;
      }
    }

    $pages = !empty($page)?$page:1;
    $appointments_list_count = ceil($appointments_list_count/$limit);
    $next_page    = $pages + 1;
    $next_page    = ($next_page <=$appointments_list_count)?$next_page:-1;

    $response['appointments_list']=$appresult;
    $response['next_page']=$next_page;
    $response['current_page']=$page;
    
    
    
    if(empty($response['appointments_list']))
    {
     $response_code = '201';
     $response_message = "No Results found";
   }
   else
   {
     $response_code = '200';
     $response_message = "";
   }

   $response_data=$response;
   
   $result = $this->data_format($response_code,$response_message,$response_data);

   $this->response($result, REST_Controller::HTTP_OK);

 }
 else
 {
  $this->token_error();
}
}

public function labresult_upload_post()
  {

  if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

   $data=array();
   $user_data = array();
   $response=array();
   $user_data = $this->post();
   $response_data=array();
  
    
   
    $appointment_id=$this->input->post('appointment_id');

    if(!empty($user_data['appointment_id'])  )
  {
       
     $countfiles = count($_FILES['user_file']['name']);

     if($countfiles > 0){
     
      for($i=0;$i<$countfiles;$i++){
       if(!empty($_FILES['user_file']['name'][$i])){
        

      $path = "uploads/lab_result/".$appointment_id;
    
      if(!is_dir($path)){
        mkdir($path);         
      }
      
      $path = $path."/";
      
      $target_file =$path . basename($_FILES["user_file"]["name"][$i]);
      $file_type = pathinfo($target_file,PATHINFO_EXTENSION);

     
         if ($_FILES["user_file"]["error"][$i] > 0) {
                    echo "Error: " . $_FILES["user_file"]["error"][$i] . "<br>";
                } else {
                    if (file_exists($path. $_FILES["user_file"]["name"][$i])) {
                    } else {
						
						$base64string = str_replace('data:image/png;base64,', '', $_FILES["user_file"]["name"][$i]);
						
						$base64string = str_replace(' ', '+', $base64string);
						$imgdata = base64_decode($base64string);
						$img_name = $i.time();
						// $img_name = $_FILES["user_file"]["name"][$i];
						$file_name_final=$img_name.'.png';
						move_uploaded_file($_FILES["user_file"]["tmp_name"][$i],$path.$file_name_final);
						
                        // move_uploaded_file($_FILES["user_file"]["tmp_name"][$i],$path.$_FILES['user_file']['name'][$i]);
                       
                    }
                }
            
      
    }
    }
        $response_code = '200';
        $response_message = "Uploaded Successfully";
      }else{
        $response_code = '500';
        $response_message = "File missing";
      }
    }else{

         $response_code = '500';
        $response_message = "Appointment id Input field missing";
    }

    
         $result = $this->data_format($response_code,$response_message,$response_data);

        $this->response($result, REST_Controller::HTTP_OK);

   }else{
    $this->token_error();
   }

}


public function lab_appointment_testlist_post()
{
  if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

    $response=array();
    $result=array();
    $user_data = array();
    $user_data = $this->post();

   
    $lab_id=$user_data['lab_id'];

    $response['test_list']=$result;

    $user_currency=get_user_currency_api($this->user_id);
    
    
    $where=array('lab_id' =>$lab_id);
    $lab_list=$this->db->get_where('lab_tests',$where)->result_array();
    
    if (!empty($lab_list)) {
      foreach ($lab_list as $rows) {
        $data['id']=$rows['id'];
        $data['lab_id']=$rows['lab_id'];
        $data['profileimage']=(!empty($rows['profileimage']))?$rows['profileimage']:'assets/img/user.png';
        $data['lab_test_name']=ucfirst($rows['lab_test_name']);
        $data['amount']=strval(get_doccure_currency($rows['amount'],$rows['currency_code'],$user_currency['user_currency_code']));
        $data['currency_code']=!empty($user_currency['user_currency_code'])?$user_currency['user_currency_code']:'INR';
        $data['currency_symbol']=$user_currency['user_currency_sign'];
        $data['status']=$rows['status'];
        $data['duration']="".$rows['duration'];
        $data['description']="".$rows['description'];
        $data['created_date']=$rows['created_date'];
        $data['is_selected']="0";
        


        
        $result[]=$data;
      }

      

      $response['test_list']=$result;
     
    }
    
    
    if(empty($response['test_list']))
    {
     $response_code = '201';
     $response_message = "No Results found";
   }
   else
   {
     $response_code = '200';
     $response_message = "Lab Test list";
   }

   $response_data=$response;
   
   $result = $this->data_format($response_code,$response_message,$response_data);

   $this->response($result, REST_Controller::HTTP_OK);

 }
 else
 {
  $this->token_error();
}
}



public function lab_pay($user_data)
{

  $data=array();
  $response=array();

  if(!empty($user_data['lab_id']) &&  !empty($user_data['amount']) && !empty($user_data['hourly_rate']) &&  !empty($user_data['appoinment_date'])  &&  !empty($user_data['booking_ids']) )
  {

    $lab_id = $user_data['lab_id'];
    
    $amount = $user_data['amount'];
   
   $user_currency=get_user_currency_api($this->user_id);

$currency_code=!empty($user_currency['user_currency_code'])?$user_currency['user_currency_code']:'INR';

  

   /* Get Invoice id */

   $invoice = $this->db->order_by('id','desc')->limit(1)->get('payments')->row_array();
   if(empty($invoice)){
    $invoice_id = 1;   
  }else{
    $invoice_id = $invoice['id'];    
  }
  $invoice_id++;
  $invoice_no = 'I0000'.$invoice_id;

           // Store the Payment details

  $payments_data = array(
                'lab_id' => $lab_id,
                'patient_id' => $this->user_id,
                'booking_ids' => $user_data['booking_ids'],
                'invoice_no' => $invoice_no,
                'lab_test_date' => $user_data['appoinment_date'],
                'total_amount' => $amount,
                'currency_code' => $currency_code,
                'txn_id' => $user_data['txn_id'],
                'order_id' => $user_data['order_id'],
                'transaction_status' => "success",  
                'payment_type' =>'CCavenue',
                'tax'=>!empty(settings("tax"))?settings("tax"):"0",
                'tax_amount' => !empty($user_data['tax_amount'])?$user_data['tax_amount']:"0",
                'transcation_charge' => !empty($user_data['transcation_charge'])?$user_data['transcation_charge']:"0",
                'payment_status' => 1,
                'payment_date' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('lab_payments',$payments_data);
        $appointment_id = $this->db->insert_id();
  
  
  $response['code']='200';
  $response['message']='Transaction success';


}
else
{
 $response['code']='500';
 $response['message']='Inputs field missing';
}




return json_encode($response);

}

public function lab_list_post(){

  if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

    $response=array();
    $result=array();
    $user_data = array();
    $user_data = $this->post();

    $page = $user_data['page'];
    $limit = $user_data['limit'];

    $response['lab_list']=$result;
    
    $lab_list_count = $this->api->lab_lists($page,$limit,1,$user_data);
    $lab_list = $this->api->lab_lists($page,$limit,2,$user_data); 
    
    
    if (!empty($lab_list)) {
      foreach ($lab_list as $rows) {
        $data['id']=$rows['user_id'];
        $data['username']=$rows['username'];
        $data['profileimage']=(!empty($rows['profileimage']))?$rows['profileimage']:'assets/img/user.png';
        $data['first_name']=ucfirst($rows['first_name']);
        $data['last_name']=ucfirst($rows['last_name']);
        $data['specialization_img']="".$rows['specialization_img'];
        $data['speciality']=ucfirst($rows['speciality']);
        $data['cityname']=$rows['cityname'];
        $data['countryname']=$rows['countryname'];
        $data['services']=$rows['services'];
        $data['rating_value']=$rows['rating_value'];
        $data['rating_count']=$rows['rating_count'];
        
        
        $result[]=$data;
      }

      $pages = !empty($page)?$page:1;
      $lab_list_count = ceil($lab_list_count/$limit);
      $next_page    = $pages + 1;
      $next_page    = ($next_page <=$lab_list_count)?$next_page:-1;

      $response['lab_list']=$result;
      $response['next_page']=$next_page;
      $response['current_page']=$page;
    }
    
    
    if(empty($response['lab_list']))
    {
     $response_code = '201';
     $response_message = "No Results found";
   }
   else
   {
     $response_code = '200';
     $response_message = "Lab list";
   }

   $response_data=$response;
   
   $result = $this->data_format($response_code,$response_message,$response_data);

   $this->response($result, REST_Controller::HTTP_OK);

 }
 else
 {
  $this->token_error();
}

}


public function check_otp_post(){
	
  $user_data = array();
  $user_data = $this->post();
  $mobile=$user_data['mobileno'];
  $is_available_otp = $this->api->check_otp($user_data);
  $user_result = $this->api->is_valid_loginwithotp($device_id,$device_type,$mobile);

  if(!empty($user_result['status']==1) && $is_available_otp > 0)
  {
	  $response_code='200';
	  $response_message='OTP verified successfully';
	  $data=$user_result;
  }
  else if(!empty($user_result['status']==2))
  {

	  $response_code='500';
	  $response_message='Your account has been inactive.';
  }
  else
  {
	  $response_code='500';
	  $response_message='Wrong login credentials.';
  }
  $result = $this->data_format($response_code, $response_message, $data);
  $this->response($result, REST_Controller::HTTP_OK);
}


public function otpsign_post()
{
         if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
              $data=array();
              $user_data = array();
              $response=array();
              $user_data = $this->post();
              $device_id='';
              $device_type='';
    // print_r($user_data);exit;
      $mobile_number = $user_data['mobileno'];
      $country_code= $user_data['country_code'];
      $haskey= $user_data['haskey'];
            if(!empty($user_data['mobileno']))
            { 
              if(!empty($user_data['device_id']))
              {
                $device_id=$user_data['device_id'];
              }

              if(!empty($user_data['device_type']))
              {
                $device_type=$user_data['device_type'];
              }
          $already_exits_mobile_no=$this->db->where('mobileno',$user_data['mobileno'])->get('users')->num_rows();
  if($already_exits_mobile_no >=1)
  {
    $otp_checking=$this->db->where('mobileno ',$user_data['mobileno'])->get('otp_history')->num_rows();
    $AccountSid = settings("tiwilio_apiKey");
    $AuthToken = settings("tiwilio_apiSecret");
    $from = settings("tiwilio_from_no");
    $twilio = new Client($AccountSid, $AuthToken);
    // if($otp_checking >=1 )
    // {
    //   $this->db->select('otpno,mobileno');
    //   $this->db->from('otp_history');
    //   $this->db->where('mobileno ',$user_data['mobileno']);
    //   $otpdata=$this->db->get()->row_array();
      
      
    //   $otp=$otpdata['otpno'];
    //   $msg = "Hello, Welcome to ".settings("website_name").'. '."Your one time password (OTP):".$otp." .".$haskey;

    //   $mobileno="+".$country_code.$mobile_number;

    //   try {
    //     $message = $twilio->messages
    //             ->create($mobileno, // to
    //              ["body" => $msg, "from" => $from]
    //            );
    //             $response = array('status' => true);
    //             $status=0;
    //           } catch (Exception $error) {
    //            $status=500;
    //          }

    //          if($status==0)
    //          {
    //           $response_message="OTP send successfully"; 
    //           $response_code=200;
    //         }
    //         else
    //         {
    //          $response_message="OTP send failed"; 
    //          $response_code=500; 
    //        }

    // }else{
          $otp = rand(10000, 99999);
          $mobileno="+".$country_code.$mobile_number;
          $inputdata['otpno']=$otp;
          $inputdata['status']=0;
          $inputdata['mobileno']=$mobile_number;
          $inputdata['created_date']=date('Y-m-d H:i:s');
          $this->signin->saveotp($inputdata);
          
          /*Mobile number validation otp send starts*/
          
          $msg = "Hello, Welcome to ".' '.settings("website_name").'.  '."Your one time password (OTP):".$otp.".".$haskey;
          
          
          try {
            $message = $twilio->messages
          ->create($mobileno, // to
           ["body" => $msg, "from" => $from]
         );
          $response = array('status' => true);
          $status=0;
        } catch (Exception $error) {
        //echo $error;
          $status=500;
        }

        if($status==0)
             {
              $response_message="OTP send successfully"; 
              $response_code=200;
            }
            else
            {
             $response_message="OTP send failed"; 
             $response_code=500; 
           }
    // }
  }else{
                   $response_code='500';
                   $response_message='This Number is not registered';
  }

            }
            else
            {
                   $response_code='500';
                   $response_message='Inputs field missings';
            }

             
             
             $result = $this->data_format($response_code,$response_message,$response);
             $this->response($result, REST_Controller::HTTP_OK);
         
         }
         else
          {
            $this->token_error();
          }
}



public function paymentrequest_post(){
		if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
			$data=array();
			$user_data = array();
			$response=array();
			$result=array();
			$user_data = $this->post();
			$user_id = $this->user_id;
			
			$inputdata=array();

			$user_currency=get_user_currency_api($this->user_id);
			$user_currency_code=$user_currency['user_currency_code'];
			$user_currency_rate=$user_currency['user_currency_rate'];
			
			if(!empty($user_data['payment_type']) && !empty($user_data['request_amount'])){
				$inputdata['user_id']=$user_id;
				$inputdata['payment_type']=$user_data['payment_type'];
				$inputdata['request_amount']=$user_data['request_amount'];
				$inputdata['currency_code']=$user_currency_code;
				$inputdata['description']=($user_data['description'])?$user_data['description']:'';
				$inputdata['request_date']=date('Y-m-d H:i:s');

				$already_exits=$this->db->where('user_id',$inputdata['user_id'])->get('account_details')->num_rows();
				if($already_exits==0){
					$response_code = '201';
					$response_message = 'Please enter account details';
					$datas = new stdClass();
					$result = $this->data_format($response_code, $response_message, $datas);
					$this->response($result, REST_Controller::HTTP_OK);		
				} else {

					if($inputdata['payment_type']=='1'){ 
						if($this->role=='1' || $this->role=='6'){
						  $balance=$this->accounts->get_balance($inputdata['user_id']);
						}else if($this->role=='5'){
						  $balance=$this->accounts->get_balance_pharmacy($inputdata['user_id']);
						}else if($this->role=='4'){
						  $balance=$this->accounts->get_balance_lab($inputdata['user_id']);
						}
						//echo $this->db->last_query();
					}
					if($inputdata['payment_type']=='2'){
						$balance=$this->accounts->get_patient_balance($inputdata['user_id']);
					}

					$requested = $this->accounts->get_requested($inputdata['user_id']);
					$earned = $this->accounts->get_earned($inputdata['user_id']);

					$balance=$balance-($earned+$requested);

					if(floatval($balance) < floatval($inputdata['request_amount'])){ 
						$response_code = '201';
						$response_message = 'Please enter valid amount';
						$datas = new stdClass();
						$result = $this->data_format($response_code, $response_message, $datas);
						$this->response($result, REST_Controller::HTTP_OK);	
					} else {
						$this->db->insert('payment_request',$inputdata);
						//echo $this->db->last_query();
						$result=($this->db->affected_rows()!= 1)? false:true;
						if($result==true){
							$notification=array(
							'user_id'=>$user_id,
							'to_user_id'=>0,
							'type'=>"Payment Request",
							'text'=>"has raised payment request ",
							'created_at'=>date("Y-m-d H:i:s"),
							'time_zone'=>$this->time_zone
							);
							$this->db->insert('notification',$notification);

							$datas=new stdClass();
							$response_code = '200';
							$response_message = 'Payment request send successfully';
							$result = $this->data_format($response_code, $response_message, $datas);
							$this->response($result, REST_Controller::HTTP_OK);		
						} else {
							$datas=new stdClass();
							$response_code = '201';
							$response_message = 'Payment request send failed!';
							$result = $this->data_format($response_code, $response_message, $datas);
							$this->response($result, REST_Controller::HTTP_OK);		
						}
					}
				}
			} else {
				$datas=new stdClass();
				$response_code='500';
				$response_message='Input field(s) are missing or empty';
				$result = $this->data_format($response_code, $response_message, $datas);
				$this->response($result, REST_Controller::HTTP_OK);		
			}
			
			
		} else {
            $this->token_error();
        }
	}
	
	
	public function doctor_accounts_list_post()
	{ 
    if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
			$data=array();
			$user_data = array();
			$response=array();
			$result=array();
			$user_data = $this->post();
			$user_id = $this->user_id;
			
			$page = $user_data['page'];
            $limit = $user_data['limit'];

			$account_list_count = $this->api->get_doctor_acclist($user_id,$page,$limit,1);
            $account_list = $this->api->get_doctor_acclist($user_id,$page,$limit,2);
			if(!empty($account_list)) {		
				foreach ($account_list as $account) {

					$patient_profileimage=(!empty($account['patient_profileimage']))?base_url().$account['patient_profileimage']:base_url().'assets/img/user.png';

					$patient_currency=$account['currency_code'];
					
					$tax_amount=$account['tax_amount']+$account['transcation_charge'];
					
					$amount=($account['total_amount']) - ($tax_amount);
					
					$commission = !empty(settings("commission"))?settings("commission"):"0";
					$commission_charge = ($amount * ($commission/100));

					if($account['request_status'] == '6'){
					  $total_amount = ($amount );
					}else{
					  $total_amount = ($amount - $commission_charge);
					}
					$user_currency=get_user_currency_api($this->user_id);
					$user_currency_code=$user_currency['user_currency_code'];
					$user_currency_rate=$user_currency['user_currency_rate'];

					$currency_option = (!empty($user_currency_code))?$user_currency_code:default_currency_code();
					$rate_symbol = currency_code_sign($currency_option);

					$org_amount=get_doccure_currency($total_amount,$patient_currency,$user_currency_code);

					switch ($account['request_status']) {
					  case '0':
					  $status='New';
					  break;
					  case '1':
					  $status='Waiting for Patient Approval';					 
					  break;
					  case '2':
					  $status='Approved';
					  break;
					  case '3':
					  $status='Payment Request';
					  break; 
					  case '4':
					  $status='Payment Received';
					  break; 
					  case '5':
					  $status='Cancelled';
					  break;  
					  case '6':
					  $status='Waiting for Approval';
					  break;
					  case '7':
					  $status='Refund';
					  break;					 
					  default:
					  $status='New';
					  break;
					}	  
	  
	  				$data['id'] = $account['id'];
					$data['date'] = date('d M Y',strtotime($account['payment_date']));
					$data['patient_profileimage']=$patient_profileimage;
					$data['patient_id'] = $account['patient_id'];
					$data['patient_name'] = $account['patient_name'];
					$data['amount'] = $rate_symbol.number_format($org_amount,2,'.',',');
					$data['status'] =$account['status']; 
					$data['request_status'] =$account['request_status']; 
					$data['statustxt'] =$status; 
					$result[]=$data;
				}
				$pages = !empty($page)?$page:1;
                $doctor_list_count = ceil($account_list_count/$limit);
                $next_page    = $pages + 1;
                $next_page    = ($next_page <=$account_list_count)?$next_page:-1;

                
                $response['next_page']=$next_page;
                $response['current_page']=$page;
				
				$response['accounts']=$result;
				$response_code='200';
				$response_message='Doctor Account List';    
			} else {
				$response['accounts']=array();
				$response_code='200';
				$response_message='No Details Found';    
			}
			$result = $this->data_format($response_code,$response_message,$response);
			$this->response($result, REST_Controller::HTTP_OK);

		} else {
            $this->token_error();
        }
   
    }
	
	public function doctor_refund_request_post()
	{ 
		if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
			$data=array();
			$user_data = array();
			$response=array();
			$result=array();
			$user_data = $this->post();
			$user_id = $this->user_id;
			
			$page = $user_data['page'];
            $limit = $user_data['limit'];

			$account_list_count = $this->api->get_doctor_reflist($user_id,$page,$limit,1);
            $account_list = $this->api->get_doctor_reflist($user_id,$page,$limit,2);
			
			
			if(!empty($account_list)) {				
				foreach ($account_list as $account) {
					
					$doctor_profileimage=(!empty($account['doctor_profileimage']))?base_url().$account['doctor_profileimage']:base_url().'assets/img/user.png';        
					
					$tax_amount=$account['tax_amount']+$account['transcation_charge'];
        
					$amount=($account['total_amount']) - ($tax_amount);
					$patient_currency=$account['currency_code'];
					$commission = !empty(settings("commission"))?settings("commission"):"0";
					$commission_charge = ($amount * ($commission/100));
					$total_amount = ($amount - $commission_charge);
					
					$user_currency=get_user_currency_api($this->user_id);
					$user_currency_code=$user_currency['user_currency_code'];
					$user_currency_rate=$user_currency['user_currency_rate'];
					$patient_currency=$account['currency_code'];

					$currency_option = (!empty($user_currency_code))?$user_currency_code:default_currency_code();
					$rate_symbol = currency_code_sign($currency_option);

					// $org_amount=get_doccure_currency($total_amount,$patient_currency,$user_currency_code);
					
					$org_amount=get_doccure_currency($total_amount,$patient_currency,$user_currency_code);

					switch ($account['request_status']) {
						case '0':
							$status=$this->language['lg_new1']='New';
						break;
						case '1':
							$status=$this->language['lg_waiting_for_app']='Waiting for Approval';				
						break;
						case '2':
							$status=$this->language['lg_appointment_com']='Appointment Completed';	
						break;
						case '3':
							$status=$this->language['lg_appointment_com']='Appointment Completed';
						break; 
						case '4':
							$status=$this->language['lg_appointment_com']='Appointment Completed';
						break; 
						case '5':
							$status=$this->language['lg_new1']='New';	
						break;  
						case '6':
							$status=$this->language['lg_waiting_for_doc']='Waiting for Approval';					
						break;
						case '7':
							$status=$this->language['lg_refund_approved']='Refund Approved';	
						break;
						case '8':
							$status=$this->language['lg_cancelled']='Cancelled';	
						break;
						default:
							$status=$this->language['lg_new1']='New';	
						break;
					}
					$data['id'] = $account['id'];
					$data['date'] = date('d M Y',strtotime($account['payment_date']));
					$data['doctor_profileimage']=$doctor_profileimage;
					$data['doctor_id'] = $account['doctor_id'];
					$data['doctor_name'] = $account['doctor_name'];
					$data['doctor_username']=$account['doctor_username'];
					$data['role'] =$account['role']; 
					$data['amount'] = $rate_symbol.number_format($org_amount,2,'.',',');
					$data['status'] =$account['status']; 
					$data['request_status'] =$account['request_status']; 
					$data['statustxt'] =$status; 
					$result[]=$data;
				}
				
				$pages = !empty($page)?$page:1;
                $doctor_list_count = ceil($account_list_count/$limit);
                $next_page    = $pages + 1;
                $next_page    = ($next_page <=$account_list_count)?$next_page:-1;

                
                $response['next_page']=$next_page;
                $response['current_page']=$page;
				
				$response['accounts']=$result;
				$response_code='200';
				$response_message='Patient Refund Account List';    
			} else {
				$response['accounts']=array();
				$response_code='200';
				$response_message='No Details Found';    
			}
			$result = $this->data_format($response_code,$response_message,$response);
			$this->response($result, REST_Controller::HTTP_OK);
		} else {
            $this->token_error();
        }
	}
	
	public function patient_refund_request_post()
	{ 
		if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
			$data=array();
			$user_data = array();
			$response=array();
			$result=array();
			$user_data = $this->post();
			$user_id = $this->user_id;
			
			$page = $user_data['page'];
            $limit = $user_data['limit'];

			$account_list_count = $this->api->get_patient_reflist($user_id,$page,$limit,1);
            $account_list = $this->api->get_patient_reflist($user_id,$page,$limit,2);
			
			
			if(!empty($account_list)) {				
				foreach ($account_list as $account) {
					$patient_profileimage=(!empty($account['patient_profileimage']))?base_url().$account['patient_profileimage']:base_url().'assets/img/user.png';        
					
					$tax_amount=$account['tax_amount']+$account['transcation_charge'];
					$amount=($account['total_amount']) - ($tax_amount);
					$commission = !empty(settings("commission"))?settings("commission"):"0";
					$commission_charge = ($amount * ($commission/100));
					$total_amount = ($amount );
					
					$user_currency=get_user_currency_api($this->user_id);
					$user_currency_code=$user_currency['user_currency_code'];
					$user_currency_rate=$user_currency['user_currency_rate'];
					$patient_currency=$account['currency_code'];

					$currency_option = (!empty($user_currency_code))?$user_currency_code:default_currency_code();
					$rate_symbol = currency_code_sign($currency_option);

					$org_amount=get_doccure_currency($total_amount,$patient_currency,$user_currency_code);

					switch ($account['request_status']) {						
						case '6':
							$status=$this->language['lg_waiting_for_app']='Waiting for approval';	
						break;

						default:
							$status=$this->language['lg_new1']='New';	
						break;
					}
					$data['id'] = $account['id'];
					$data['date'] = date('d M Y',strtotime($account['payment_date']));
					$data['patient_profileimage']=$patient_profileimage;
					$data['patient_id'] = $account['patient_id'];
					$data['patient_name'] = $account['patient_name'];
					$data['amount'] = $rate_symbol.number_format($org_amount,2,'.',',');
					$data['status'] =$account['status']; 
					$data['request_status'] =$account['request_status']; 
					$data['statustxt'] =$status; 
					$result[]=$data;
				}
				
				$pages = !empty($page)?$page:1;
                $doctor_list_count = ceil($account_list_count/$limit);
                $next_page    = $pages + 1;
                $next_page    = ($next_page <=$account_list_count)?$next_page:-1;

                
                $response['next_page']=$next_page;
                $response['current_page']=$page;
				
				$response['accounts']=$result;
				$response_code='200';
				$response_message='Patient Refund Account List';    
			} else {
				$response['accounts']=array();
				$response_code='200';
				$response_message='No Details Found';    
			}
			$result = $this->data_format($response_code,$response_message,$response);
			$this->response($result, REST_Controller::HTTP_OK);
		} else {
            $this->token_error();
        }
	}
	
	public function account_send_request_post(){
		if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
			$data=array();
			$user_data = array();
			$response=array();
			$result=array();
			$user_data = $this->post();
			$user_id = $this->user_id;

			$user_currency=get_user_currency_api($this->user_id);
			$user_currency_code=$user_currency['user_currency_code'];
			$user_currency_rate=$user_currency['user_currency_rate'];
			
			if(!empty($user_data['payment_id']) && !empty($user_data['status'])){
		
				$payment_id=$user_data['payment_id'];
				$status=$user_data['status'];
				$this->db->where('id',$payment_id);
				$this->db->update('payments',array('request_status' =>$status));
				$datas=new stdClass();
				$response_code='200';
				$response_message='Request Status Updated Succesfuuly.';
				$result = $this->data_format($response_code, $response_message, $datas);
				$this->response($result, REST_Controller::HTTP_OK);	
			} else {
				$datas=new stdClass();
				$response_code='500';
				$response_message='Input field(s) are missing or empty';
				$result = $this->data_format($response_code, $response_message, $datas);
				$this->response($result, REST_Controller::HTTP_OK);		
			}			
			
		} else {
            $this->token_error();
        }
	}
	
	public function product_delete_post()
	{
		if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {			
			$user_data = array();
			$user_data = $this->post(); 			
			if($user_data['product_id'] >= 0 && !empty($user_data['product_id'])){ 				
				$id = $user_data['product_id'];	
				$isproduct = $this->api->productdetails($id);
				if(!empty($isproduct)){
					$inputdata['status']=$user_data['status'];
					$this->db->update('products',$inputdata, array('id'=>$id));	
					$response_code='200';
					$response_message='Product Status Updated Successfully';          
				}else{
					$response_code='500';
					$response_message='Invalid ID';
				}
			}else{
			   $response_code='500';
			   $response_message='Input field(s) are missing or empty';
			}
			$data = new stdClass();
			$result = $this->data_array_format($response_code,$response_message,$data);
			$this->response($result, REST_Controller::HTTP_OK);  
		} else {
		  $this->token_error();
		}
	}
	
	
	public function pharmacy_invoice_details_post()
  {
	if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
		
		$response=array();
		$result=array();
		$user_data = array();
		$user_data = $this->post();
		$res=array(); $data=array();
		$user_id = $this->user_id;
		$id = $user_data['id'];
		
		$res = $this->api->pharmacy_invoice_details($id);		
		
		if($res){
		  $response['invoice_list'] = $res;
		  
			$inv = $res[0];
		    $user_currency=get_user_currency_api($this->user_id);
            $user_currency_code=$user_currency['user_currency_code'];
            $user_currency_rate=$user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code))?$user_currency_code:$inv['currency_code'];
            $rate_symbol = currency_code_sign($currency_option);

                      
            $rate=get_doccure_currency($inv['per_hour_charge'],$inv['currency_code'],$user_currency_code);
            
            $rate=number_format((float)$rate,2,'.',',');
                    
            $amount=$rate_symbol.''.$rate;
              	
            $transcation_charge=get_doccure_currency($inv['transcation_charge'],$inv['currency_code'],$user_currency_code);
    
            $tax_amount=get_doccure_currency($inv['tax_amount'],$inv['currency_code'],$user_currency_code);

            $total_amount=get_doccure_currency($inv['total_amount'],$inv['currency_code'],$user_currency_code);
			
			$response['currency']=$rate_symbol;
			$response['user_transcation_charge'] = $transcation_charge;
			$response['user_tax_amount'] = $tax_amount;
			$response['user_total_amount'] = $total_amount;
		  
		    $payment_id=$inv['id'];			
			$this->db->where('payment_id',$payment_id);
			$appointments_res= $this->db->where("appointment_from",$inv['user_id'])->get('appointments')->result_array();

			$sno=1; 
		   foreach ($appointments_res as $key => $value) {
		   
			   if($value['type']="online"){
					$mode='Video/Audio call Booking';
			   }else{
					$mode='Clinic Booking';
			   }
			   $data['sno']=$sno;
			   $data['type']=$value['type'];
			   $data['type_text']=$mode;
			   $data['amount']=$amount;
            
			   $patresult[]=$data;
		   }
		   
		   $response['appoinment_details'] = $patresult;
		   
		  $response_code='200';
		  $response_message='';                      
		}else{
		  $response_code='500';
		  $response['invoice_list'] = $res;
		  $response_message='Invoice list empty';
		}
		$result = $this->data_array_format($response_code,$response_message,$response);
		$this->response($result, REST_Controller::HTTP_OK);
	}
	else
	{
		$this->token_error();
	}            
  }
  
  
  public function braintree_pay($user_data)
    {

      $data=array();
     $response=array();

    if(!empty($user_data['doctor_id']) && !empty($user_data['payment_method']) && !empty($user_data['amount']) && !empty($user_data['hourly_rate'])&&  !empty($user_data['appoinment_date']) && !empty($user_data['appoinment_start_time']) && !empty($user_data['appoinment_end_time']) && !empty($user_data['appoinment_token']) && !empty($user_data['appoinment_session']) && !empty($user_data['appoinment_timezone']) && !empty($user_data['appointment_type']) && !empty($user_data['payload_nonce']))
    {

        $doctor_id = $user_data['doctor_id'];
   
        $amount = $user_data['amount'];
        //$token=$user_data['access_token'];
		$payload_nonce = $user_data['payload_nonce'];
		
		$user_currency=get_user_currency_api($this->user_id);
		$currency_code=$user_currency['user_currency_code'];
			
		$amount= get_doccure_currency($user_data['amount'],$currency_code,default_currency_code());
		$amount=number_format($amount,2, '.', '');
				
		require_once(APPPATH . '../vendor/autoload.php');
		require_once(APPPATH . '../vendor/braintree/braintree_php/lib/Braintree.php');
		
		$paypal_option=!empty(settings("paypal_option"))?settings("paypal_option"):"1";
		if($paypal_option=='1'){
			$paypal_mode='sandbox';  
			$braintree_merchant=!empty(settings("braintree_merchant"))?settings("braintree_merchant"):"";
			$braintree_publickey=!empty(settings("braintree_publickey"))?settings("braintree_publickey"):"";
			$braintree_privatekey=!empty(settings("braintree_privatekey"))?settings("braintree_privatekey"):"";
		}
		if($paypal_option=='2'){
			$paypal_mode='live';
			$braintree_merchant=!empty(settings("live_braintree_merchant"))?settings("live_braintree_merchant"):"";
			$braintree_publickey=!empty(settings("live_braintree_publickey"))?settings("live_braintree_publickey"):"";
			$braintree_privatekey=!empty(settings("live_braintree_privatekey"))?settings("live_braintree_privatekey"):"";
		}
		//echo $paypal_mode."-".$braintree_merchant."-".$braintree_publickey."-".$braintree_privatekey;
		
		$gateway = new Braintree\Gateway([
			'environment' => $paypal_mode,
			'merchantId' => $braintree_merchant,
			'publicKey' => $braintree_publickey,
			'privateKey' => $braintree_privatekey
		]);				
		
		//$merchantAccount = $gateway->merchantAccount()->find('3d58sy3grs86hmyz');
		//echo $merchantAccount; exit;
		
		//echo "<pre>"; print_r($gateway);
		
            if ($gateway) { 
					$orderIds = 'OD'.time().rand();
					
					//echo "Inside Gateway";
                    $result = $gateway->transaction()->sale([
                        'amount' => $amount,
                        'paymentMethodNonce' => $payload_nonce,
                        'orderId' => $orderIds,
                        'options' => [
                        'submitForSettlement' => True
                        ],
                    ]);
					
				//print_r($result);  echo "Success - ".$result->success; echo $result->message; exit;

				if ($result->success==1) {
                        $transaction_id = $result->transaction->id;
        
						//echo "\n\n transaction_id - ".$transaction_id."\n";
				
				   $opentok = new OpenTok($this->tokbox_apiKey,$this->tokbox_apiSecret);
				// An automatically archived session:
				  $sessionOptions = array(
						  // 'archiveMode' => ArchiveMode::ALWAYS,
					'mediaMode' => MediaMode::ROUTED
				  );
				  $new_session = $opentok->createSession($sessionOptions);
						 // Store this sessionId in the database for later use
				  $tokboxsessionId= $new_session->getSessionId();

				  $tokboxtoken=$opentok->generateToken($tokboxsessionId);

				  /* Get Invoice id */

				  $invoice = $this->db->order_by('id','desc')->limit(1)->get('payments')->row_array();
				  if(empty($invoice)){
					$invoice_id = 1;   
				  }else{
					$invoice_id = $invoice['id'];    
				  }
				  $invoice_id++;
				  $invoice_no = 'I0000'.$invoice_id;

			   // Store the Payment details

				  $payments_data = array(
				   'user_id' => $this->user_id,
				   'doctor_id' => $doctor_id,
				   'invoice_no' => $invoice_no,
				   'per_hour_charge' => $user_data['hourly_rate'],
				   'total_amount' => $amount,
				   'currency_code' => 'INR',
				   'txn_id' => $transaction_id,
				   'order_id' => 'OD'.time().rand(),
				   'transaction_status' => json_encode($result),  
				   'payment_type' =>'Paypal',
				   'tax'=>!empty(settings("tax"))?settings("tax"):"0",
				   'tax_amount' => !empty($user_data['tax_amount'])?$user_data['tax_amount']:"0",
				   'transcation_charge' => !empty($user_data['transcation_charge'])?$user_data['transcation_charge']:"0",
				   'payment_status' => 1,
				   'payment_date' => date('Y-m-d H:i:s'),
				   );
				   
				  $this->db->insert('payments',$payments_data);
				  $payment_id = $this->db->insert_id();
				  
				   $appointmentdata['payment_id'] =  $payment_id;   
				   $appointmentdata['appointment_from'] = $this->user_id;
				   $appointmentdata['appointment_to'] = $user_data['doctor_id'];
				   $appointmentdata['from_date_time'] = $user_data['appoinment_date'].' '.date('H:i:s',strtotime($user_data['appoinment_start_time']));
				   $appointmentdata['to_date_time'] = $user_data['appoinment_date'].' '.date('H:i:s',strtotime($user_data['appoinment_end_time']));
				   $appointmentdata['appointment_date'] = $user_data['appoinment_date'];
				   $appointmentdata['appointment_time'] = date('H:i:s',strtotime($user_data['appoinment_start_time']));
				   $appointmentdata['appointment_end_time'] = date('H:i:s',strtotime($user_data['appoinment_end_time']));
				   $appointmentdata['appoinment_token'] = $user_data['appoinment_token'];
				   $appointmentdata['appoinment_session'] = $user_data['appoinment_session'];
				   $appointmentdata['payment_method'] = $user_data['payment_method'];
				   $appointmentdata['tokboxsessionId'] = $tokboxsessionId;
				   $appointmentdata['tokboxtoken'] = $tokboxtoken;
				   $appointmentdata['paid'] = 1;
				   $appointmentdata['approved'] = 1;
				   $appointmentdata['time_zone'] = $user_data['appoinment_timezone'];
				   $appointmentdata['type'] = $user_data['appointment_type'];
				   $appointmentdata['created_date'] = date('Y-m-d H:i:s');
				   $this->db->insert('appointments',$appointmentdata);
				   $appointment_id = $this->db->insert_id();
				   $appoinments_details = $this->api->get_appoinment_call_details($appointment_id);
				  if($this->role==1)
				  {
					$notifydata['include_player_ids'] = $appoinments_details['patient_device_id'];
					$device_type = $appoinments_details['patient_device_type'];
					$nresponse['from_name']=$appoinments_details['doctor_name'];
				  }
				  if($this->role==2)
				  {
					$notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
					$device_type = $appoinments_details['doctor_device_type'];
					$nresponse['from_name']=$appoinments_details['patient_name'];
				  }

				  $notifydata['message']=$nresponse['from_name'].' has has booked appointment on '.date('d M Y',strtotime($user_data['appoinment_date']));
				  $notifydata['notifications_title']='';
				  $nresponse['type']='Booking';
				  $notifydata['additional_data'] = $nresponse;
				  if($device_type=='Android')
				  {
					sendFCMNotification($notifydata);
				  }
				  if($device_type=='IOS')
				  {
					sendiosNotification($notifydata);
				  }
				   
				   $response['code']='200';
				   $response['message']='Transaction success';
			   } else {
					$response['code'] = '404';
					$response['message'] ='Transcation Failed... Please Try Again....';						
				}
			} else {
				$response['code'] = '404';
				$response['message'] ='Transcation Failed... Please Try Again....';;
			}		


           
      
	}else
      {
             $response['code']='500';
             $response['message']='Inputs field missing';
      }


      

       return json_encode($response);

  }
  
  
  public function braintree_pay_for_pharmacy($user_data)
	{

		$data=array();
		$response=array();
		//print_r($user_data);  

		//echo $user_data['amount']."AMOUT";
		//echo $ser_data['access_token']."ACESS";
		//echo $user_data['payment_method']."PAYMENT METHOD";

		//  exit;
		if(!empty($user_data['payment_method']) && !empty($user_data['amount']) && !empty($user_data['payload_nonce'])) {

			$amount = round($user_data['amount']);
			$token=$user_data['access_token'];
			$payload_nonce = $user_data['payload_nonce'];

			$cartItems =  $user_data['cartItems'];   
			
			$user_currency=get_user_currency_api($user_data['user_id']);
			$currency_code=$user_currency['user_currency_code'];
				
			$amount= get_doccure_currency($user_data['amount'],$currency_code,default_currency_code());
			$amount=number_format($amount,2, '.', '');
				
			require_once(APPPATH . '../vendor/autoload.php');
			require_once(APPPATH . '../vendor/braintree/braintree_php/lib/Braintree.php');
			
			$paypal_option=!empty(settings("paypal_option"))?settings("paypal_option"):"1";
			if($paypal_option=='1'){
				$paypal_mode='sandbox';  
				$braintree_merchant=!empty(settings("braintree_merchant"))?settings("braintree_merchant"):"";
				$braintree_publickey=!empty(settings("braintree_publickey"))?settings("braintree_publickey"):"";
				$braintree_privatekey=!empty(settings("braintree_privatekey"))?settings("braintree_privatekey"):"";
			}
			if($paypal_option=='2'){
				$paypal_mode='live';
				$braintree_merchant=!empty(settings("live_braintree_merchant"))?settings("live_braintree_merchant"):"";
				$braintree_publickey=!empty(settings("live_braintree_publickey"))?settings("live_braintree_publickey"):"";
				$braintree_privatekey=!empty(settings("live_braintree_privatekey"))?settings("live_braintree_privatekey"):"";
			}

			$gateway = new Braintree\Gateway([
				'environment' => $paypal_mode,
				'merchantId' => $braintree_merchant,
				'publicKey' => $braintree_publickey,
				'privateKey' => $braintree_privatekey
			]);				
			
			//$merchantAccount = $gateway->merchantAccount()->find('3d58sy3grs86hmyz');
			//echo $merchantAccount; exit;
			
			if ($gateway) { 

				$orderIds = 'OD'.time().rand();
				//echo "Inside Gateway";
				$result = $gateway->transaction()->sale([
					'amount' => $amount,
					'paymentMethodNonce' => $payload_nonce,
					'orderId' => $orderIds,
					'options' => [
					'submitForSettlement' => True
					],
				]);
				
				// print_r($result);  echo "Success - ".$result->success; echo $result->message; exit;

				if ($result->success) {
					
					$ordItemDetails['full_name']     	  = $user_data['full_name'];
					$ordItemDetails['address1'] 		  = $user_data['address1'];

					$ordItemDetails['address2']     	  = $user_data['address2'];
					$ordItemDetails['state']     		  = $user_data['state'];
					$ordItemDetails['postal_code']  	  = $user_data['zipcode'];
					$ordItemDetails['city']     		  = $user_data['city'];
					$ordItemDetails['email']     		  = $user_data['emailid'];

					$ordItemDetails['country']     		  = $user_data['country'];
					$ordItemDetails['payment_method']     = $user_data['payment_method'];
					$ordItemDetails['phoneno']    		  = $user_data['phoneno'];
					$ordItemDetails['total_amount']    	  = $user_data['amount'];

					$ordItemDetails['user_id']     		   = $user_data['user_id'];
					$ordItemDetails['pharmacy_id']     	   = $user_data['pharmacy_id'];
					$ordItemDetails['created_at']     	   = date('Y-m-d H:i:s');
					$ordItemDetails['currency']     	   = '$';

					$ordItemDetails['status'] = 1;

					$this->db->insert('order_user_details',$ordItemDetails);
					$orderId = $this->db->insert_id();
					
					foreach($cartItems as $item){  

						$ordItemData['user_order_id']      = $orderId;
						$ordItemData['user_id']     	   = $item['user_id'];
						$ordItemData['order_id']           = 'OD'.time().rand();
						$ordItemData['product_id']         = $item['product_id'];
						$ordItemData['product_name']       = $item['product_name'];
						$ordItemData['quantity']           = $item['qty'];
						$ordItemData['price']              = $item["price"];
						$ordItemData['subtotal']           = $item["subtotal"];
						$ordItemData['transaction_status'] = json_encode($result);
						$ordItemData['payment_type']       = 'Paypal';
						$ordItemData['ordered_at']         = date('Y-m-d H:i:s');

						$this->db->insert('orders',$ordItemData);
					
					}

					$notifydata['message']='Your Order Confirm';
					$notifydata['notifications_title']='';
					$nresponse['type']='Booking';
					$notifydata['additional_data'] = '';

					sendFCMNotification($notifydata);

					$response['code']='200';
					$response['message']='Transaction success';

				} else {
					$response['code'] = '404';
					$response['message'] ='Transcation Failed... Please Try Again....';						
				}
			} else {
				$response['code'] = '404';
				$response['message'] ='Transcation Failed... Please Try Again....';;
			}		

		} else {
			$response['code']='500';
			$response['message']='Inputs field missing';
		}

		return json_encode($response);
	}
	
	
	public function patient_accounts_list_post()  
	{
		if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
			$data=array();
			$user_data = array();
			$response=array();
			$result=array();
			$user_data = $this->post();
			$user_id = $this->user_id;
			
			$page = $user_data['page'];
            $limit = $user_data['limit'];

			$account_list_count = $this->api->get_patient_acclist($user_id,$page,$limit,1);
            $account_list = $this->api->get_patient_acclist($user_id,$page,$limit,2);
			
			
			if(!empty($account_list)) {				
				foreach ($account_list as $account) {
					$doctor_profileimage=(!empty($account['doctor_profileimage']))?base_url().$account['doctor_profileimage']:base_url().'assets/img/user.png';        
					
					$tax_amount=$account['tax_amount']+$account['transcation_charge'];        
					$amount=($account['total_amount']) - ($tax_amount);
					
					$commission = !empty(settings("commission"))?settings("commission"):"0";
					$commission_charge = ($amount * ($commission/100));
					$total_amount = ($amount );
					
					$user_currency=get_user_currency_api($this->user_id);
					$user_currency_code=$user_currency['user_currency_code'];
					$user_currency_rate=$user_currency['user_currency_rate'];
					$patient_currency=$account['currency_code'];

					$currency_option = (!empty($user_currency_code))?$user_currency_code:default_currency_code();
					$rate_symbol = currency_code_sign($currency_option);

					$org_amount=get_doccure_currency($total_amount,$patient_currency,$user_currency_code);

					switch ($account['request_status']) {
						case '0':
							$status=$this->language['lg_new1']='New';
						break;
						case '1':
							$status=$this->language['lg_waiting_for_app']='Waiting for Approval';				
						break;
						case '2':
							$status=$this->language['lg_appointment_com']='Appointment Completed';	
						break;
						case '3':
							$status=$this->language['lg_appointment_com']='Appointment Completed';
						break; 
						case '4':
							$status=$this->language['lg_appointment_com']='Appointment Completed';
						break; 
						case '5':
							$status=$this->language['lg_new1']='New';	
						break;  
						case '6':
							$status=$this->language['lg_waiting_for_doc']='Waiting for Approval';					
						break;
						case '7':
							$status=$this->language['lg_refund_approved']='Refund Approved';	
						break;
						case '8':
							$status=$this->language['lg_cancelled']='Cancelled';	
						break;
						default:
							$status=$this->language['lg_new1']='New';	
						break;
					}
					$data['id'] = $account['id'];
					$data['date'] = date('d M Y',strtotime($account['payment_date']));
					$data['doctor_profileimage']=$doctor_profileimage;
					$data['doctor_id'] = $account['doctor_id'];
					$data['doctor_name'] = $account['doctor_name'];
					$data['doctor_username']=$account['doctor_username'];
					$data['role'] =$account['role']; 
					$data['amount'] = $rate_symbol.number_format($org_amount,2,'.',',');
					$data['status'] =$account['status']; 
					$data['request_status'] =$account['request_status']; 
					$data['statustxt'] =$status; 
					$result[]=$data;
				}
				
				$pages = !empty($page)?$page:1;
                $doctor_list_count = ceil($account_list_count/$limit);
                $next_page    = $pages + 1;
                $next_page    = ($next_page <=$account_list_count)?$next_page:-1;

                
                $response['next_page']=$next_page;
                $response['current_page']=$page;
				
				$response['accounts']=$result;
				$response_code='200';
				$response_message='Patient Account List';    
			} else {
				$response['accounts']=array();
				$response_code='200';
				$response_message='No Details Found';    
			}
			$result = $this->data_format($response_code,$response_message,$response);
			$this->response($result, REST_Controller::HTTP_OK);

		} else {
            $this->token_error();
        }
	}



public function invoice_list_post()
  { 
    if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

      $response=array();
      $patresult=array();
      $user_data = array();
      $user_data = $this->post();
      $page = $user_data['page'];
      $limit = $user_data['limit'];
      $user_id = $this->user_id;
	  $role_id = $this->role;
      
	  //$list = $this->invoice->get_datatables($user_id);	 
      //$patient_list_count = $this->invoice->count_all($user_id);
	  
      $data = array();
      
	  $list = $this->api->read_invoice_list($user_id,$role_id,$page,$limit,2);
	  $patient_list_count = $this->api->read_invoice_list($user_id,$role_id,$page,$limit,1);
	  
      if (!empty($list)) { 
      foreach ($list as $invoices) {
        $user_currency=get_user_currency_api($this->user_id);
        $user_currency_code=$user_currency['user_currency_code'];
        $user_currency_rate=$user_currency['user_currency_rate'];
        $currency_option = (!empty($user_currency_code))?$user_currency_code:default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);
        $org_amount=get_doccure_currency($invoices['total_amount'],$invoices['currency_code'],$user_currency_code);
        $doctor_profileimage=(!empty($invoices['doctor_profileimage']))?base_url().$invoices['doctor_profileimage']:base_url().'assets/img/user.png';
        $patient_profileimage=(!empty($invoices['patient_profileimage']))?base_url().$invoices['patient_profileimage']:base_url().'assets/img/user.png';
          $data['id']=$invoices['id'];
          $data['invoice_no']=$invoices['invoice_no'];
          $data['profileimage']=(!empty($doctor['profile']))?base_url().$doctor['profile']:base_url().'assets/img/user.png';
          $data['amount']=$rate_symbol.number_format((float)$org_amount,2,'.',',');;
          $data['date']=date('d M Y',strtotime($invoices['payment_date']));
          
		  if($role_id=='1' || $role_id=='4' || $role_id=='5' || $role_id=='6') {
          $data['name']=ucfirst($invoices['patient_name']);
          $data['profile']=$patient_profileimage;
          }else {
            $data['name']=ucfirst($invoices['doctor_name']);
            $data['profile']=$doctor_profileimage;
            }
            $data['role']=$invoices['role'];
			      $data['patient_role']=$invoices['patient_role'];
          $patresult[]=$data;      
        }
      }
      $pages = !empty($page)?$page:1;
      $patient_list_counts = $patient_list_count;
      $patient_list_count = ceil($patient_list_count/$limit);
      $next_page    = $pages + 1;
      $next_page    = ($next_page <=$patient_list_count)?$next_page:-1;
  
      $response['inv_list']=$patresult;
      $response['inv_count']=strval($patient_list_counts);
      $response['next_page']=$next_page;
      $response['current_page']=$page;
      if(empty($response['inv_list']))
      {
       $response_code = '201';
       $response_message = "No Results found";
     }
     else
     {
       $response_code = '200';
       $response_message = "";
     }
     $response_data=$response;
     $result = $this->data_format($response_code,$response_message,$response_data);
     $this->response($result, REST_Controller::HTTP_OK);
  
   }
   else
   {
    $this->token_error();
  }

  }

public function hospital_add_doctor_post()
{

 if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
  $data=array();
  $user_data = array();
  $response=array();
  $user_data = $this->post();
  $response_data=array();

  
  if(!empty($user_data['first_name']) && !empty($user_data['last_name']) && !empty($user_data['email']) && !empty($user_data['mobileno']) )
  {  
    
    
    $inputdata['first_name']=$user_data['first_name'];
    $inputdata['last_name']=$user_data['last_name'];
    $inputdata['email']=strtolower(trim($user_data['email']));
    $inputdata['mobileno']=$user_data['mobileno'];
    $inputdata['country_code']=$user_data['country_code'];
    $inputdata['username'] = generate_username($inputdata['first_name'].' '.$inputdata['last_name'].' '.$inputdata['mobileno']);
    $inputdata['role']=1;
    $inputdata['status'] = 1;
    $inputdata['hospital_id']=$this->user_id;
    $inputdata['password']=md5($user_data['password']);
    $inputdata['confirm_password']=md5($user_data['confirm_password']);
    $inputdata['created_date']=date('Y-m-d H:i:s');

    if($user_data['user_id'] != ""){
      $this->db->where('id',$user_data['user_id']);
      $this->db->update('users',$inputdata);
      $response_message="Updated Successfully";
      $response_code=200; 
      //echo json_encode($response);
      //return;
    }else{
    $result=$this->signin->signup($inputdata);
    
    if($result==true)
    {   
    $inputdata['id']=$this->db->insert_id();
    $response_message = base64_encode($inputdata['id']);
    //$this->load->library('sendemail');
    //$this->sendemail->send_email_verification($inputdata);
    $response['msg']=$this->language['lg_registration_su']; 
    $response_code=200;              
  }
  else
  {
    $response_message = $this->language['lg_registration_fa'] = 'Registration Failed';
    $response_code=500; 
  } 
}
   
 }
 else
 {
   $response_code='500';
   $response_message='Inputs field missing';
   $response_data['test_details']="";
 }
 
 
 $result = $this->data_array_format($response_code,$response_message,$response_data);
 $this->response($result, REST_Controller::HTTP_OK);
 
}
else
{
  $this->token_error();
}
}

public function hospital_doctor_delete_post() {
  if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
    $response=array();
    $result=array();
    $user_data = array();
    $user_data = $this->post();
    $res=array();			
    $id = $user_data['doctor_id'];
    $this->db->update('users',array('status'=>0),array('id'=>$id));
    $response_code = '200';
    $response_message = "Doctor deleted from clinic."; 
    $response_data=new stdClass();
    $result = $this->data_format($response_code,$response_message,$response_data);
    $this->response($result, REST_Controller::HTTP_OK);
  } else {
    $this->token_error();
  }
}

public function hospital_doctor_list_get()
{
  if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {

    $response=array();
    $patresult=array();
    $user_data = array();
    $user_data = $this->post();

    $page = $user_data['page'];
    $limit = $user_data['limit'];
    $user_id = $this->user_id;  
    $list = $this->doctor->get_datatables($this->user_id);
   
    $patient_list_count = $this->doctor->get_datatables($this->user_id,1);
    $data = array();
    $no = $_POST['start'];
    $a = 1;
    if (!empty($list)) {

    foreach ($list as $doctor) {
      
    
  
     
        $data['id']=$doctor['id'];
        $data['username']=$doctor['username'];
        $data['profileimage']=(!empty($doctor['profile']))?base_url().$doctor['profile']:base_url().'assets/img/user.png';
        $data['first_name']=ucfirst($doctor['first_name']);
        $data['last_name']=ucfirst($doctor['last_name']);
        $data['mobileno']=$doctor['mobile'];
        $data['country_code']=$doctor['country_code'];
		$data['email']=$doctor['email'];
        $data['doc_count_list']=strval($patient_list_count);
        $patresult[]=$data;
      
   
    
  }
    }
    $pages = !empty($page)?$page:1;
    $patient_list_counts = $patient_list_count;

    $patient_list_count = ceil($patient_list_count/$limit);
    $next_page    = $pages + 1;
    $next_page    = ($next_page <=$patient_list_count)?$next_page:-1;

    $response['doc_list']=$patresult;
    $response['doc_count']=strval($patient_list_counts);
    $response['next_page']=$next_page;
    $response['current_page']=$page;
    if(empty($response['doc_list']))
    {
     $response_code = '201';
     $response_message = "No Results found";
   }
   else
   {
     $response_code = '200';
     $response_message = "";
   }
   $response_data=$response;
   $result = $this->data_format($response_code,$response_message,$response_data);
   $this->response($result, REST_Controller::HTTP_OK);

 }
 else
 {
  $this->token_error();
}
}


public function pharmacy_invoice_get()
  {
	if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
		
		$response=array();
		$result=array();
		$user_data = array();
		$user_data = $this->post();
		$res=array(); 
		$user_id = $this->user_id;
		
		$res = $this->api->pharmacy_invoice_list($user_id);		
		
		if($res){
		  $response['invoice_list'] = $res;
		  $response_code='200';
		  $response_message='';                      
		}else{
		  $response_code='500';
		  $response['invoice_list'] = $res;
		  $response_message='Invoice list empty';
		}
		$result = $this->data_array_format($response_code,$response_message,$response);
		$this->response($result, REST_Controller::HTTP_OK);
	}
	else
	{
		$this->token_error();
	}            
  }

  public function assigned_to_doctor_post() {
		if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
			$response=array();
			$result=array();
			$user_data = array();
			$user_data = $this->post();
			$res=array();
			
			$id = $user_data['doctor_id'];
			$app_id = $user_data['appointment_id'];
			$this->api->assign_doc($id, $app_id, $this->user_id);
			$response_code = '200';
			$response_message = "Doctor assigned  to the appoinment."; 
			$response_data=new stdClass();
			$result = $this->data_format($response_code,$response_message,$response_data);
			$this->response($result, REST_Controller::HTTP_OK);
		}
		else
		{
			$this->token_error();
		} 
  }
  
  public function change_order_status_post(){
		if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
			$user_data = $this->post();
			if(!empty($user_data['id']) && !empty($user_data['status'])){
				$this->db->where('order_id',$user_data['id'])->update('orders',array('order_status'=>$user_data['status'],'user_notify'=>0,'pharmacy_notify'=>0));
				$response_code='200';
				$response_message='Order Status Changed Succesfuuly';
				$response['user_details']='';
			}
			else
			{
				$response_code='500';
				$response_message='Inputs field missing';
			}
			$result = $this->data_format($response_code,$response_message,$response);
			$this->response($result, REST_Controller::HTTP_OK);
		}
		else
		{
		  $this->token_error();
		}
	}



  public function pharmacy_product_search_post(){
      
    $response = array();
    $user_data = $this->post();
    
    if(!empty($user_data['pharmacy_id']) || !empty($user_data['keywords']) || !empty($user_data['category']) || !empty($user_data['subcategory']))
    {
      $productsData = $this->api->read_pharmacy_productlist_search($user_data['pharmacy_id'],$user_data['keywords'],$user_data['category'],$user_data['subcategory']);
    }
    else
    {
      $productsData = $this->api->all_pharmacy_products();
    }

    foreach($productsData as $products){ 
      $products['currency'] = '$';  
      $product_data[] = $products;
    }
      $catedata['products'] = $product_data;
      $catedata['unit'] = $this->api->get_pharmacy_unit();
      $response = $catedata;
      $response_code = '200';
      $response_message = "";
      $result = $this->data_array_format($response_code,$response_message,$response);
      $this->response($result, REST_Controller::HTTP_OK);

  }



  //Mari
  public function generateotp_post()
  {
    if($this->user_id !=0  || ($this->default_token ==$this->api_token)) {
      $data=array();
      $user_data = array();
      $response=array();
      $user_data = $this->post();
      $device_id='';
      $device_type='';
      // print_r($user_data);exit;
      $mobile_number = $user_data['mobileno'];
      $country_code= $user_data['country_code'];
      $haskey= $user_data['haskey'];
      if(!empty($user_data['mobileno']) && !empty($user_data['country_code']))
      { 
        if(!empty($user_data['device_id'])){
          $device_id=$user_data['device_id'];
        } 
        if(!empty($user_data['device_type'])){
          $device_type=$user_data['device_type'];
        }
        $already_exits_mobile_no=$this->db->where('mobileno',$user_data['mobileno'])->get('users')->num_rows();
        if($already_exits_mobile_no >=1){
          $response_code='500';
          $response_message='Mobileno already exits';
        }else{
          $otp_checking=$this->db->where('mobileno ',$user_data['mobileno'])->get('otp_history')->num_rows();
          //$otp_checking =1;
          $AccountSid = settings("tiwilio_apiKey");
          $AuthToken = settings("tiwilio_apiSecret");
          $from = settings("tiwilio_from_no");
          $twilio = new Client($AccountSid, $AuthToken);
          // if($otp_checking >=1 )
          // {
          //   $this->db->select('otpno,mobileno');
          //   $this->db->from('otp_history');
          //   $this->db->where('mobileno ',$user_data['mobileno']);
          //   $otpdata=$this->db->get()->row_array();

          //   $otp=$otpdata['otpno'];
          //   $msg = "Hello, Welcome to ".settings("website_name").'. '."Your one time password (OTP):".$otp." .".$haskey;

          //   $mobileno="+".$country_code.$mobile_number;

          //   try {
          //     $message = $twilio->messages
          //     ->create($mobileno, // to
          //     ["body" => $msg, "from" => $from]
          //     );
          //     //print_r($message);
          //     $response = array('status' => true);
          //     $status=0;
          //   } catch (Exception $error) {
          //     $status=500;
          //   }

          //   if($status==0){
          //     $response_message="OTP send successfully"; 
          //     $response_code='200';
          //   } else {
          //     $response_message="OTP send failed"; 
          //     $response_code='500'; 
          //   }

          // } else{
            $otp = rand(10000, 99999);
            $mobileno="+".$country_code.$mobile_number;
            $inputdata['otpno']=$otp;
            $inputdata['status']=0;
            $inputdata['mobileno']=$mobile_number;
            $inputdata['created_date']=date('Y-m-d H:i:s');
            $this->load->model('signin_model','signin');
            $this->signin->saveotp($inputdata);

            /*Mobile number validation otp send starts*/

            $msg = "Hello, Welcome to ".' '.settings("website_name").'.  '."Your one time password (OTP):".$otp.".".$haskey;


            try {
              $message = $twilio->messages
              ->create($mobileno, // to
              ["body" => $msg, "from" => $from]
              );
              //print_r($message);
              $response = array('status' => true);
              $status=0;
            } catch (Exception $error) {
              //echo $error;
              $status=500;
            }

            if($status==0){
              $response_message="OTP send successfully"; 
              $response_code='200';
            } else  {
              $response_message="OTP send failed"; 
              $response_code='500'; 
            }
          // }
        }
      }
      else
      {
        $response_code='500';
        $response_message='Inputs field missings';
      }
      
      $result = $this->data_format($response_code,$response_message,$response);
      $this->response($result, REST_Controller::HTTP_OK);

    }
    else
    {
      $this->token_error();
    }
  }
 
}
