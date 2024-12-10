<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . '../vendor/autoload.php');
use Twilio\Rest\Client;


class Signin extends CI_Controller {

    public $data;
    public $session;
    public $timezone;
    public $lang;
    public $language;
    public $input;
    public $db;
    public $sendemail;
    public $signin;
	public $data1;
  public function __construct() {

    parent::__construct();
    


    $this->data['theme']     = 'web';
    $this->data['module']    = 'signin';
    $this->data['page']     = '';
    $this->data['base_url'] = base_url();
    $this->timezone = $this->session->userdata('time_zone');
    // $this->session->set_userdata('team_member_id', 7);
    // $this->session->set_userdata('role',7);
    if(!empty($this->timezone)){
      date_default_timezone_set($this->timezone);
    }

    // $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):'english';
    $lan=default_language();
    $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):strtolower($lan['language']);
    $this->data['language'] = $this->lang->load('content', $lang, true);
    $this->language = $this->lang->load('content', $lang, true);
    $this->load->model('signin_model','signin');

    
    
  }

  public function index()
  {
    $this->verifytokenmiddleware->handle();

    if($this->session->userdata('user_id') || $this->session->userdata('admin_id')){
      
      redirect(base_url().'home');
      
    }

    $this->data['page'] = 'index';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');
  
}


public function check_already_register(){
  $data=$this->input->post();
   $already_exits=$this->db->where('email',$data['email'])->get('users')->num_rows();
      if($already_exits >=1)
      {
          $response['msg']=$this->language['lg_your_email_addr1'];
          $response['status']=500;

      }else{ 
            $response['msg']='';
            $response['status']=200;
      }

      echo json_encode($response);
         return false;
}

public function social_signin(){
  $data=$this->input->post();
    $email = $data['email'];
    $result = $this->signin->social_login($email);
    if(!empty($result['status']==1))
    {
      $session_data=array('user_id' =>$result['id'],'role'=>$result['role']);
      $this->session->set_userdata($session_data);
      $this->session->unset_userdata('admin_id');
      $response['msg']='';
      $response['status']=200;
    }
    else if(!empty($result['status']==2))
    {
     $response['status']=500;
     $response['msg']=$this->language['lg_your_account_ha1'];
   }
   else
   {
     $response['status']=500;
     $response['msg']=$this->language['lg_wrong_login_cre'];
   }

    echo json_encode($response);
    return false;
}

//doctor signin from Google api above two function only making authurl for redirecting to google
public function social_register(){
     //die("dsf");
    $data=$this->input->post();
     
      $userData['first_name'] = $data['first_name'];
      $userData['last_name'] = $data['last_name'];
      $userData['email'] = $data['email'];
      $userData['username'] = generate_username($data['first_name'].' '.$data['last_name']);
      $userData['mobileno'] = 0;
      $userData['country_code'] = 0;
      $userData['password'] = 0;
      $userData['confirm_password '] = 0;
      $userData['role']=$data['user_role'];
      $userData['status']=1;
      $userData['is_verified']=1;
      $userData['created_date']=date('Y-m-d H:i:s');
      $already_exits=$this->db->where('email',$data['email'])->get('users')->num_rows();
      $response=array();
      if($already_exits >=1)
      {
          $response['msg']=$this->language['lg_your_email_addr1'];
          $response['status']=500;

      }else{

        $result=$this->signin->signup($userData);
		
		//echo "<pre>";
		//print_r($result);
		//die("ech");
        if($result==true)
        {   
	          $user_id=$this->db->insert_id();
	          $email = $userData['email'];
          $result_login = $this->signin->social_login($email);
		//  print_r($result_login);
		//  die("uu");
	            //$user_id = $result_login['id'];
				//$data['first_name'] = $userData['first_name'];
				//$data['last_name'] = $userData['last_name'];
				//$data['email'] = $email;
				//$data['username'] = $userData['username'];
			
                $data1['recieved_id'] = $user_id;
                $data1['sent_id'] = 1;
                $data1['is_admin'] = 1;
                $data1['time_zone'] = $this->session->userdata('time_zone');
                $data1['chatdate'] = date('Y-m-d H:i:s');
                $sitename = (!empty(settings("website_name"))) ? settings("website_name") : "Dibest Spot";
                $welcome = 'Welcome to DiBest Spot Medical—your trusted healthcare partner!<br><br>
                            Dear Member,<br><br>
                            To schedule appointments, purchase medications, pay your invoice, billing queries or to ask any questions, reach out to Admin here via this chat.<br><br>
                            <span style="text-decoration-line: underline;">STEP1:</span> Contact Admin to Book Your “FREE Medical Consultation” with the <strong>DiBest Spot:(GTC)-General Telehealth Clinic</strong> to get started!<br><br>
                            Best Regards,<br>
                            DiBest Spot Medical<br><br>
                            PS… Check out our marketplace for other services and products at <a target="_blank" href="https://www.dibestspot.com">https://www.dibestspot.com</a>';
                $data1['msg'] = $welcome;
				//echo "<pre>";
				//print_r($data1);
				//die;
                $result = $this->db->insert('chat', $data1);
                $chat_id = $this->db->insert_id();
                $users = array($data1['recieved_id'], $data1['sent_id']);
                for ($i = 0; $i < 2; $i++) {
                    $datas = array('chat_id' => $chat_id, 'can_view' => $users[$i]);
                    $this->db->insert('chat_deleted_details', $datas);
                }
				
				

		  
          if(!empty($result_login['status']==1))
          {
            $session_data=array('user_id' =>$result_login['id'],'role'=>$result_login['role']);
            $this->session->set_userdata($session_data);
            $this->session->unset_userdata('admin_id');
			
			
			
			
			
			
            $response['msg']='';
            $response['status']=200;
            
          }  
		  
		  
        }
      }
	  
	 

      echo json_encode($response);
         return false;

}


public function register()
{
  
  $this->verifytokenmiddleware->handle();
  
  if($this->session->userdata('user_id') || $this->session->userdata('admin_id')){
    
    redirect(base_url().'home');
    
  }
  
  // $this->data['role']='doctor';
  $this->data['page'] = 'register';
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'].'/template');
  
}



public function check_email()
{
  $email = strtolower(trim($this->input->post('email')));     
  $result = $this->signin->check_email($email);
  if ($result > 0) {
   echo 'false';
 } else {
   echo 'true';
 }
 
}

public function check_resetemail()
{
  $email = strtolower(trim($this->input->post('resetemail')));     
  $result = $this->signin->check_email($email);
  if ($result > 0) {
   echo 'true';
 } else {
   echo 'false';
 }
 
}

public function check_mobileno()
{
  $mobileno = $this->input->post('mobileno');     
  $result = $this->signin->check_mobileno($mobileno);
  if ($result > 0) {
   echo 'false';
 } else {
   echo 'true';
 }
 
}

public function sendotp()
{
 
  $mobile_number ='';
  $mobile_number = $this->input->post('mobileno');
  $country_code= $this->input->post('country_code');
  $otpcount=$this->input->post('otpcount');
  $inputdata=array();
  $inputdata['mobileno']=$mobile_number;
  $already_exits_mobile_no=$this->db->where('mobileno',$inputdata['mobileno'])->get('users')->num_rows();
  if($already_exits_mobile_no >=1)
  {
    $response['msg']=$this->language['lg_your_mobileno_a'];
    $response['status']=500;
    echo json_encode($response);
  }
  else
  {
    $otp_checking=$this->db->where('mobileno ',$inputdata['mobileno'])->get('otp_history')->num_rows();
    $AccountSid = settings("tiwilio_apiKey");
    $AuthToken = settings("tiwilio_apiSecret");
    $from = settings("tiwilio_from_no");
    $twilio = new Client($AccountSid, $AuthToken);
    // if($otp_checking >=1 && $otpcount==2)
    // {
    //   $this->db->select('otpno,mobileno');
    //   $this->db->from('otp_history');
    //   $this->db->where('mobileno ',$inputdata['mobileno']);
    //   $otpdata=$this->db->get()->row_array();
      
      
    //   $otp=$otpdata['otpno'];
    //   $msg = $this->language['lg_hello_welcome_t'].' '.settings("website_name").'. '.$this->language['lg_your_one_time_p'].$otp;

    //   $mobileno="+".$country_code.$mobile_number;

    //   try {
    //     $message = $twilio->messages
    //             ->create($mobileno, // to
    //              ["body" => $msg, "from" => $from]
    //            );
    //             $response = array('status' => true);
    //             $status=0;
    //           } catch (Exception $error) {
    //           //echo $error;
    //            $status=500;
    //          }

    //          if($status==0)
    //          {
    //           $response['msg']=$this->language['lg_otp_send_succes']; 
    //           $response['status']=200;
    //         }
    //         else
    //         {
    //          $response['msg']=$this->language['lg_otp_send_failed']; 
    //          $response['status']=500; 
    //        }
           
    //        echo json_encode($response);
    //      }
    //      else{
          // Generate random verification code
          $otp = rand(10000, 99999);
          
          $inputdata['otpno']=$otp;
          $inputdata['status']=0;
          $inputdata['created_date']=date('Y-m-d H:i:s');
          $this->signin->saveotp($inputdata);
          
          $mobileno="+".$country_code.$mobile_number;
          /*Mobile number validation otp send starts*/
          
          $msg = $this->language['lg_hello_welcome_t'].' '.settings("website_name").'.  '.$this->language['lg_your_one_time_p'].$otp;
          
          
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
        
              //print($message->sid);exit;
        if($status==0)
        {
          $response['msg']=$this->language['lg_otp_send_succes']; 
          $response['status']=200;
        }
        else
        {
         $response['msg']=$this->language['lg_otp_send_failed']; 
         $response['status']=500; 
       }
       
       echo json_encode($response);
       
     // }
   }
 }

 public function signup()
 {
    

  $inputdata=array();
  $response=array();
  
  $otpno=$this->input->post('otpno');
  $inputdata['first_name']=$this->input->post('first_name');
  $inputdata['last_name']=$this->input->post('last_name');
  $inputdata['email']=strtolower(trim($this->input->post('email')));
  $inputdata['mobileno']=$this->input->post('mobileno');
  $inputdata['country_code']=$this->input->post('country_code');
  $inputdata['username'] = generate_username($inputdata['first_name'].' '.$inputdata['last_name'].' '.$inputdata['mobileno']);
  $inputdata['role']=$this->input->post('role');
  $inputdata['password']=md5($this->input->post('password'));
  $inputdata['confirm_password']=md5($this->input->post('confirm_password'));
  $inputdata['created_date']=date('Y-m-d H:i:s');
  $already_exits=$this->db->where('email',$inputdata['email'])->get('users')->num_rows();
  $already_exits_mobile_no=$this->db->where('mobileno',$inputdata['mobileno'])->get('users')->num_rows();
  if(settings('tiwilio_option')=='1') {
   $otp_checking =$this->db->select('otpno,mobileno')->from('otp_history')->where('otpno', $otpno)->where('mobileno',$this->input->post('mobileno'))->get()->num_rows();
   if($otp_checking ==0)
   {
    $response['msg']=$this->language['lg_your_otp_is_inv'];
    $response['status']=500;
    echo json_encode($response);
    return false;
  }
}
if($already_exits >=1)
{
  $response['msg']=$this->language['lg_your_email_addr1'];
  $response['status']=500;
}
else if($already_exits_mobile_no >=1)
{
  $response['msg']=$this->language['lg_your_mobileno_a'];
  $response['status']=500;
}
else
{
  $result=$this->signin->signup($inputdata);
  if($result==true)
  {   
   $inputdata['id']=$this->db->insert_id();
   $this->load->library('sendemail');
   $this->sendemail->send_email_verification($inputdata);
   $response['msg']=$this->language['lg_registration_su']; 
   $this->session->set_flashdata('success_message',$this->language['lg_registration_su']);

  // echo "<pre>";
		//		print_r($this->session->userdata());
			//	echo '</pre>';
			//	die("dddddd");
   //Admin send welcome message for new user
   $data['recieved_id'] =$inputdata['id'];
   $data['sent_id'] = 1;
   $data['is_admin'] = 1;
   $data['time_zone'] = $this->session->userdata('time_zone');
   $a=$data['time_zone'];
   $data['chatdate'] = date('Y-m-d H:i:s');
   $sitename = (!empty(settings("website_name")))?settings("website_name"):"Dibest Spot";
   //coomment
   $dataString = '<pre>' . print_r($data, true) . '</pre>';
   $welcome = 'Welcome to DiBest Spot Medical—your trusted healthcare partner!<br><br>
    Dear Member,<br><br>
    To schedule appointments, purchase medications, pay your invoice,  billing queries or to ask any questions, reach out to Admin here via this chat.<br><br>
    <span style="text-decoration-line: underline;">STEP1:</span>  Contact Admin to Book Your  “FREE Medical Consultation” with the <strong>DiBest Spot:(GTC)-General Telehealth Clinic</strong> to get started!<br><br>
    Best Regards,<br>' . $a . '
    
    DiBest Spot Medical<br><br>
    PS… Check out our marketplace for other services and products at <a target="_blank" href="https://www.dibestspot.com">https://www.dibestspot.com</a>';
    $data['msg']  = $welcome;
   
   //echo "<pre>";
			//	print_r($data);
			//	die;
   
   $result = $this->db->insert('chat',$data);
   $chat_id = $this->db->insert_id();
   $users = array($data['recieved_id'],$data['sent_id']);
   for ($i=0; $i <2 ; $i++) { 
     $datas = array('chat_id' =>$chat_id ,'can_view'=>$users[$i]);
     $this->db->insert('chat_deleted_details',$datas);
   }


   $response['status']=200;              
 }
 else
 {
   $response['msg']=$this->language['lg_registration_fa'];
   $response['status']=500; 
 } 

}

echo json_encode($response);
}

public function is_valid_login()
{
  $response=array();
  $email = strtolower(trim($this->input->post('email')));
  $password = $this->input->post('password');
  $result = $this->signin->is_valid_login($email,$password);
  //if(!empty($result['status']==1))
  if(isset($result['status']) && !empty($result['status']==1))
  {
    $session_data=array('user_id' =>$result['id'],'role'=>$result['role']);
    $this->session->set_userdata($session_data);
    $this->session->unset_userdata('admin_id');
    $response['msg']='';
    $response['status']=200;
  }
  else if(isset($result['status']) && !empty($result['status']==2))
  {
   $response['status']=500;
   $response['msg']=$this->language['lg_your_account_ha1'];
 }
 else
 {
   $response['status']=500;
   $response['msg']=$this->language['lg_wrong_login_cre'];
 }
 
 

 echo json_encode($response); 
}

public function sign_out()
{
    // Destroy session
    $this->session->sess_destroy();
    
    // Unset token cookie
    setcookie('token', '', time() - 3600, '/'); // Expires in the past
    
    // Set flash message
    $this->session->set_flashdata('success_message', $this->language['lg_logged_out_succ']);
    
    // Redirect to base URL
    redirect(base_url());
}

public function activate($id)
{
 
  $user_details=$this->db->where('md5(id)',$id)->get('users')->row_array();
  if($user_details['is_verified']=='1')
  {
   $this->session->set_flashdata('error_message',$this->language['lg_your_account_al']);
   redirect(base_url());
 }
 
 $inputdata['is_verified']=1;
 $result=$this->signin->update($inputdata,$id);
 if($result==true)
 {   
  $this->session->set_flashdata('success_message',$this->language['lg_your_account_ha']);
  redirect(base_url());
  
}
else
{
 $this->session->set_flashdata('error_message',$this->language['lg_your_account_ve']);
 redirect(base_url()); 
} 

}

public function update_password()
{
  $inputdata=array();
  $response=array();
  $id=$this->input->post('id');
  $user_details=$this->db->where('md5(id)',$id)->get('users')->row_array();
  $inputdata['password']=md5($this->input->post('password'));
  $inputdata['confirm_password']=md5($this->input->post('confirm_password'));
  $result=$this->signin->update($inputdata,$id);
  if($result==true)
  {
   $response['msg']=$this->language['lg_password_change1']; 
   $response['status']=200;              
 }
 else
 {
  $response['msg']=$this->language['lg_password_change'];
  $response['status']=500; 
} 

echo json_encode($response);
}

public function reset($id)
{
  

  $user_details=$this->db->where('forget',$id)->get('users')->row_array();
  if(!empty($user_details))
  {
    $currenttime=date('Y-m-d H:i:s');

    if ($user_details['expired_reset'] >= $currenttime){

      $inputdata['forget']='';
      $this->signin->update($inputdata,md5($user_details['id']));

      $this->data['id']=md5($user_details['id']);

      $this->data['page'] = 'change_password';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }
    else
    {
     $this->session->set_flashdata('error_message',$this->language['lg_your_reset_link']);
     redirect(base_url().'home');
   }
 }
 else
 {
  $this->session->set_flashdata('error_message',$this->language['lg_your_reset_link']);
  redirect(base_url().'home');
}



}

public function reset_password()
{
  $inputdata=array();
  $response=array();
  $inputdata['email']=strtolower(trim($this->input->post('resetemail')));
  $query=$this->db->where('email',$inputdata['email'])->get('users');
  $user_details=$query->row_array();
  $already_exits=$query->num_rows();
  
  if($already_exits >=1)
  {
    $inputdata['expired_reset']=date('Y-m-d H:i:s', strtotime("+3 hours"));
    $inputdata['forget']=urlencode($this->encryptor('encrypt',$user_details['email'].time()));
    $this->signin->update($inputdata,md5($user_details['id']));
    $user_details['url']=$inputdata['forget'];
    $this->load->library('sendemail');
    $this->sendemail->send_resetpassword_email($user_details);
    $response['msg']=$this->language['lg_your_reset_pass'];
    $response['status']=200;
  }
  
  else
  {
    
   $response['msg']=$this->language['lg_your_email_addr'];
   $response['status']=500; 
   

 }
 

 echo json_encode($response);
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

public function forgot_password()
{
  $this->data['page'] = 'forgot_password';
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'].'/template');
}  


}