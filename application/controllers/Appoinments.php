<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . '../vendor/autoload.php');
use Twilio\Rest\Client;

class Appoinments extends CI_Controller {
   public $data;
   public $session;
   public $timezone;
   public $input;
   public $db;
   public $lang;
   public $language;
   public $appoinment;
   public $book;
   public $sendemail;

  public function __construct() {

        parent::__construct();

        if($this->session->userdata('user_id') ==''){
          if($this->session->userdata('admin_id'))
            {
              redirect(base_url().'home');
            }
            else
            {
              redirect(base_url().'signin');
            }
        }

        $this->data['theme']     = 'web';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
        $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }
        // $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):'english';
        $lan=default_language();
        $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):strtolower($lan['language']);
        $this->data['language'] = $this->lang->load('content', $lang, true);
        $this->language = $this->lang->load('content', $lang, true);
        $this->load->model('appoinments_model','appoinment');
        $this->load->model('book_appoinments_model','book');

        $this->tokbox_apiKey=!empty(settings("apiKey"))?settings("apiKey"):"";
        $this->tokbox_apiSecret=!empty(settings("apiSecret"))?settings("apiSecret"):"";
        
         
    }

    public function index()
    {
        
        if($this->session->userdata('role')=='1'){
          $this->data['module']    = 'doctor';
          $this->data['page'] = 'appoinments';
          $this->load->vars($this->data);
          $this->load->view($this->data['theme'].'/template');
        }else if($this->session->userdata('role')=='6'){
          $this->data['module']    = 'doctor';
          $this->data['page'] = 'appoinments';
          
          $this->load->vars($this->data);
          $this->load->view($this->data['theme'].'/template');
        }
        else
        {
          $this->data['module']    = 'patient';
          $this->data['page'] = 'appoinments';
          $this->load->vars($this->data);
          $this->load->view($this->data['theme'].'/template');
        }
       
    }

    public function doctor_appoinments_list()
    {
      // die("appointment_list");
    //     $response=array();
    //     $result=array();
    //     $pageMul=$this->input->post('page');
    //     $page=1;
    //     $limit=$pageMul*8;
    //     $user_id=$this->session->userdata('user_id');
    //     $response['count'] =$this->appoinment->doctor_appoinments_list($page,$limit,1,$user_id);
    //     $data['appoinments_list'] = $this->appoinment->doctor_appoinments_list($page,$limit,2,$user_id);
    //     //echo $this->db->last_query(); exit;
    //     $data['app_type']=$this->session->userdata('role')==6?'hospital':'doctor';
    //     $data['language']=$this->language;
    //     $result= $this->load->view('web/modules/appoinments/appoinments_view',$data,TRUE);
    //     $response['current_page_no']= $page;
    //     $response['total_page']= ceil($response['count']/$limit);
    //     $response['data']= $result;

    //  echo json_encode($response);
    $response = array();
$result = array();

// Fetch page number from POST request
$pageMul = $this->input->post('page');
$page = max(1, (int)$pageMul); // Ensure page is at least 1
$limit = $page * 8; // Set limit based on page number

$user_id = $this->session->userdata('user_id');

if ($limit > 0) {
    $response['count'] = $this->appoinment->doctor_appoinments_list($page, $limit, 1, $user_id);
    $data['appoinments_list'] = $this->appoinment->doctor_appoinments_list($page, $limit, 2, $user_id);
    
    $data['app_type'] = $this->session->userdata('role') == 6 ? 'hospital' : 'doctor';
    $data['language'] = $this->language;
    $this->data['schedule_date'] = date('d/m/Y');
    $this->data['selected_date'] = date('Y-m-d');
    // echo "<pre>";
    //     // echo $this->session->userdata('role');
    //     print_r ($this->data);
    //     echo '</pre>';
    //     die("h"); 
    $this->load->vars($this->data);
    $result = $this->load->view('web/modules/appoinments/appoinments_view', $data, TRUE);
    // $this->load->view($this->data['theme'] . '/template');
    
    $response['current_page_no'] = $page;
    $response['total_page'] = ceil($response['count'] / $limit);
    $response['data'] = $result;
} else {
    $response['error'] = 'Invalid page number or limit.';
}

echo json_encode($response);


    }


    public function patient_appoinments_list()
    {
        $response=array();
        $result=array();
        $page=$this->input->post('page');
        $limit=8;
        $user_id=$this->session->userdata('user_id');
		$this->appoinment->update_appointment_lists($user_id);
        $response['count'] =$this->appoinment->patient_appoinments_list($page,$limit,1,$user_id);
        $data['appoinments_list'] = $this->appoinment->patient_appoinments_list($page,$limit,2,$user_id);
        $data['app_type']='patient';
        $data['language']=$this->language;
        $result= $this->load->view('web/modules/appoinments/appoinments_view',$data,TRUE);
        $response['current_page_no']= $page;
        $response['total_page']= ceil($response['count']/$limit);
        $response['data']= $result;

     echo json_encode($response);

    }

    
    public function outgoingvideocall($appoinment_id)
    {
      //$this->db->where('md5(id)',$appoinment_id)->update('appointments', array('call_end'=>1));
       if($this->session->userdata('role')=='1'){

             $data['appoinments_details']=$this->appoinment->get_appoinment_call_details($appoinment_id);
             $appoinments_details=$this->appoinment->get_appoinment_call_details($appoinment_id);
             $data['role']='patient';
             // Notification
             $response['from_user_id']=$appoinments_details['appointment_from'];
              $response['from_name']=($appoinments_details['doctor_firstname']);
              $response['to']=$appoinments_details['appointment_to'];
              $notifydata['include_player_ids'] = $appoinments_details['patient_device_id'];
              $device_type = $appoinments_details['patient_device_type'];
              $notifydata['message']='Incoming call from '.($appoinments_details['doctor_firstname']);
              $response['invite_id'] = $appoinments_details['id'];
              $response['type'] = 'video';
              $response['sessionId'] = $appoinments_details['tokboxsessionId'];
              $response['token'] = $appoinments_details['tokboxtoken'];
              $response['tokbox_apiKey'] =$this->tokbox_apiKey;
              $response['tokbox_apiSecret'] =$this->tokbox_apiSecret;
              $notifydata['notifications_title']='Incoming call';
              $notifydata['additional_data'] = $response;
              
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
              $data['type']= 1; //Outgoing Call
             // echo "<pre>";print_r($data['appoinments_details']);die;
             $this->call_details($data['appoinments_details'],'patient','Video');
             $this->load->view('web/modules/call/videocall',$data);
        }
        else
        {
            $appoinments_details=$this->appoinment->get_appoinment_call_details($appoinment_id);
            $data['appoinments_details']=$this->appoinment->get_appoinment_call_details($appoinment_id);
            $data['role']='doctor';
            // Notification
            $response['from_user_id']=$appoinments_details['appointment_from'];
            $response['from_name']=($appoinments_details['patient_firstname']);
            $response['to']=$appoinments_details['appointment_to'];
            $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
            $device_type = $appoinments_details['doctor_device_type'];
            $notifydata['message']='Incoming call from '.($appoinments_details['patient_firstname']);
            $response['invite_id'] = $appoinments_details['id'];
            $response['type'] = 'video';
            $response['sessionId'] = $appoinments_details['tokboxsessionId'];
            $response['token'] = $appoinments_details['tokboxtoken'];
            $response['tokbox_apiKey'] =$this->tokbox_apiKey;
            $response['tokbox_apiSecret'] =$this->tokbox_apiSecret;
            $notifydata['notifications_title']='Incoming call';
            $notifydata['additional_data'] = $response;
            

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
            $data['type']= 1; //Outgoing Call
            $this->call_details($data['appoinments_details'],'doctor','Video');
            $this->load->view('web/modules/call/videocall',$data);
        }
    }


    public function outgoingcall($appoinment_id)
    {
      //$this->db->where('md5(id)',$appoinment_id)->update('appointments', array('call_end'=>1));
       if($this->session->userdata('role')=='1'){

             $data['appoinments_details']=$this->appoinment->get_appoinment_call_details($appoinment_id);
             $appoinments_details=$this->appoinment->get_appoinment_call_details($appoinment_id);
             $data['role']='patient';
             // Notification
            $response['from_user_id']=$appoinments_details['appointment_from'];
            $response['from_name']=$appoinments_details['doctor_firstname'];
            $response['to']=$appoinments_details['appointment_to'];
            $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
            $device_type = $appoinments_details['doctor_device_type'];
            $notifydata['message']='Incoming call from '.$appoinments_details['doctor_firstname'];
            $response['invite_id'] = $appoinments_details['id'];
            $response['type'] = 'audio';
            $response['sessionId'] = $appoinments_details['tokboxsessionId'];
            $response['token'] = $appoinments_details['tokboxtoken'];
            $response['tokbox_apiKey'] =$this->tokbox_apiKey;
            $response['tokbox_apiSecret'] =$this->tokbox_apiSecret;
            $notifydata['notifications_title']='Incoming call';
            $notifydata['additional_data'] = $response;

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

            $data['type']= 1; //Outgoing Call
             $this->call_details($data['appoinments_details'],'patient','Audio');
             $this->load->view('web/modules/call/audiocall',$data);
        }
        else
        {
           
            $data['appoinments_details']=$this->appoinment->get_appoinment_call_details($appoinment_id);
            $appoinments_details=$this->appoinment->get_appoinment_call_details($appoinment_id);
            $data['role']='doctor';

            // Notification
            $response['from_user_id']=$appoinments_details['appointment_from'];
            $response['from_name']=$appoinments_details['patient_firstname'];
            $response['to']=$appoinments_details['appointment_to'];
            $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
            $device_type = $appoinments_details['doctor_device_type'];
            $notifydata['message']='Incoming call from '.$appoinments_details['patient_firstname'];
            $response['invite_id'] = $appoinments_details['id'];
            $response['type'] = 'audio';
            $response['sessionId'] = $appoinments_details['tokboxsessionId'];
            $response['token'] = $appoinments_details['tokboxtoken'];
            $response['tokbox_apiKey'] =$this->tokbox_apiKey;
            $response['tokbox_apiSecret'] =$this->tokbox_apiSecret;
            $notifydata['notifications_title']='Incoming call';
            $notifydata['additional_data'] = $response;
            
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

            $data['type']= 1; //Outgoing Call
            $this->call_details($data['appoinments_details'],'doctor','Audio');
            $this->load->view('web/modules/call/audiocall',$data);
        }
    }

    public function incomingvideocall($appoinment_id)
    {
       if($this->session->userdata('role')=='1'){
             $data['appoinments_details']=$this->appoinment->get_appoinment_call_details($appoinment_id);
             $data['role']='patient';
             $data['type']= 2; //Incoming Call
             $this->remove_call_details($data['appoinments_details']['id']);
             $this->call_accept($data['appoinments_details']['id']);
             $this->load->view('web/modules/call/videocall',$data);
        }
        else
        {
           
            $data['appoinments_details']=$this->appoinment->get_appoinment_call_details($appoinment_id);
            $data['role']='doctor';
            $data['type']= 2; //Incoming Call
            $this->remove_call_details($data['appoinments_details']['id']);
            $this->call_accept($data['appoinments_details']['id']);
            $this->load->view('web/modules/call/videocall',$data);
        }
    }

    public function incomingcall($appoinment_id)
    {
       if($this->session->userdata('role')=='1'){
             $data['appoinments_details']=$this->appoinment->get_appoinment_call_details($appoinment_id);
             $data['role']='patient';
             $data['type']= 2; //Incoming Call
             $this->remove_call_details($data['appoinments_details']['id']);
             $this->call_accept($data['appoinments_details']['id']);
             $this->load->view('web/modules/call/audiocall',$data);
        }
        else
        {
           
            $data['appoinments_details']=$this->appoinment->get_appoinment_call_details($appoinment_id);
            $data['role']='doctor';
            $data['type']= 2; //Incoming Call
            $this->remove_call_details($data['appoinments_details']['id']);
            $this->call_accept($data['appoinments_details']['id']);
            $this->load->view('web/modules/call/audiocall',$data);
        }
    }

    
  

    private function call_details($appoinments_details,$to,$call_type)
    {
        $call_from="";
        $call_to="";
        if($to=='doctor')
        {
          $call_from=$this->session->userdata('user_id');
          $call_to=$appoinments_details['appointment_to'];
        }
        if($to=='patient')
        {
          $call_from=$this->session->userdata('user_id');
          $call_to=$appoinments_details['appointment_from'];
        }
        $this->send_appoinment_sms($appoinments_details,$to,$call_type);

        $data['appointments_id']=$appoinments_details['id'];
        $data['call_from']=$call_from;
        $data['call_to']=$call_to;
        $data['call_type']=$call_type;
        $this->db->insert('call_details',$data);
        
    }

    private function remove_call_details($appointments_id)
    {
      $this->db->where('appointments_id',$appointments_id);
      $this->db->delete('call_details');
    }

    public function remove_calldetails()
    {
    $appointment_id = $this->input->post('appointment_id');
      $this->db->where('md5(appointments_id)',$appointment_id);
      $this->db->delete('call_details');
    //echo $this->db->last_query();
    }

     private function call_accept($appointments_id)
    {
      $this->db->where('id',$appointments_id);
      $this->db->update('appointments',array('call_status' =>1));
    }

    public function get_call()
    {
      //   $response['status']=500;
        
      // $user_id=$this->session->userdata('user_id');
      // $result=$this->appoinment->get_call($user_id);
      // if(!empty($result))
      // {
      //   $response['status']=200;
      //   $response['name']=$result['name'];
      //   $response['profileimage']=(!empty($result['profileimage']))?base_url().$result['profileimage']:base_url().'assets/img/user.png';
      //   $response['role']=($result['role']=='1')?'Dr.':'';
      //   $response['appointment_id']=md5($result['appointments_id']);
      //   $response['call_type']=$result['call_type'];
      // }
      // echo json_encode($response);

      $response['status']=500;
        
      $user_id=$this->session->userdata('user_id');
      $result=$this->appoinment->get_call($user_id);
      // print_r($result);
      // print_r($result['appointments_id']);
      if(!empty($result))
      {
        $appointmentData = $this->db->from('appointments')->where('id', $result['appointments_id'])->get()->row_array();
        // print_r($appointmentData);
        $app_time_zone = $appointmentData['time_zone'];                     
        $app_current_timezone = $this->session->userdata('time_zone');

        $app_end_time =  converToTz($appointmentData['appointment_date'].' '.$appointmentData['appointment_end_time'],$app_current_timezone,$app_time_zone);    
        // echo $app_end_time;
        if(date('Y-m-d H:i:s') < $app_end_time){  
        
          $response['status']=200;
          $response['name']=$result['name'];
          $response['profileimage']=(!empty($result['profileimage']))?base_url().$result['profileimage']:base_url().'assets/img/user.png';
          $response['role']=($result['role']=='1')?'Dr.':'';
          $response['appointment_id']=md5($result['appointments_id']);
          $response['call_type']=$result['call_type'];
          
        }
    
      }
      echo json_encode($response);
    }

    public function end_call()
    {
      $appointment_id=$this->input->post('appointment_id');
      // $this->db->where('md5(appointments_id)',$appointment_id);
      // $this->db->delete('call_details');
	  
    	  $callStatus=$this->db->select('call_status')->where('md5(id)',$appointment_id)->get('appointments')->row()->call_status;
    	  if($callStatus == 1){
    		$this->db->where('md5(id)',$appointment_id);
    		$this->db->update('appointments',array('call_end_status' =>1));
    	  }

      echo $doctor_id=$this->db->select('appointment_to')->where('md5(id)',$appointment_id)->get('appointments')->row()->appointment_to;
      
    }

    public function update_expire_status()
    {
      $appointment_id=$this->input->post('appoinment_id');
      $this->db->where('id',$appointment_id)->update('appointments',array('appointment_status' =>1));
    }

    public function change_status()
    {
      $appoinments_id=$this->input->post('appoinments_id');
      $appoinments_status=$this->input->post('appoinments_status');
      $this->db->where('id',$appoinments_id)->update('appointments',array('approved' =>$appoinments_status));

      $appointment_details=$this->appoinment->get_appoinment_call_details(md5($appoinments_id));

      if($appoinments_status == 0){

        $notification=array(
                    'user_id'=>$this->session->userdata('user_id'),
                     'to_user_id'=>$appointment_details['patient_id'],
                     'type'=>"Appointment Cancel",
                     'text'=>"has cancelled the appointment of",
                     'created_at'=>date("Y-m-d H:i:s"),
                     'time_zone'=>$this->timezone
           );
           $this->db->insert('notification',$notification);
        //Cancel the appointment 
        $this->send_appoinment_cancelmail($appoinments_id);
          if(settings('tiwilio_option')=='1') {
            $this->send_appoinment_cancelsms($appoinments_id);
          }
          $this->session->set_flashdata('success_message',$this->language['lg_your_appointmen']);
          redirect(base_url().'appoinments');
      }elseif($appoinments_status == 1){

        $notification=array(
                     'user_id'=>$this->session->userdata('user_id'),
                     'to_user_id'=>$appointment_details['patient_id'],
                     'type'=>"Appointment Accept",
                     'text'=>"has accepted the appointment of",
                     'created_at'=>date("Y-m-d H:i:s"),
                     'time_zone'=>$this->timezone
           );
           $this->db->insert('notification',$notification);
        //accept the appointment
        $this->send_appoinment_acceptmail($appoinments_id);
        if(settings('tiwilio_option')=='1') {
          $this->send_appoinment_acceptsms($appoinments_id);
        }
        $this->session->set_flashdata('success_message',$this->language['lg_your_appointmen1']);
        redirect(base_url().'appoinments');
      }
    }

    public function send_appoinment_cancelmail($appointment_id)
  {
      $inputdata=$this->book->get_appoinments_details($appointment_id);
      $this->load->library('sendemail');
      $this->sendemail->send_appoinment_cancelemail($inputdata);
  }

  public function send_appoinment_acceptmail($appointment_id)
  {
      $inputdata=$this->book->get_appoinments_details($appointment_id);
      $this->load->library('sendemail');
      $this->sendemail->send_appoinment_acceptemail($inputdata);
  }

  public function send_appoinment_cancelsms($appointment_id)
  {

    $inputdata=$this->book->get_appoinments_details($appointment_id);

    $AccountSid = settings("tiwilio_apiKey");
    $AuthToken = settings("tiwilio_apiSecret");
    $from = settings("tiwilio_from_no");
    $twilio = new Client($AccountSid, $AuthToken);

    $msg = $this->language['lg_your_appointmen2'].' '.$inputdata["doctor_name"];

    $mobileno="+".$inputdata['patient_mobile'];

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


  }

  public function send_appoinment_acceptsms($appointment_id)
  {

    $inputdata=$this->book->get_appoinments_details($appointment_id);

    $AccountSid = settings("tiwilio_apiKey");
    $AuthToken = settings("tiwilio_apiSecret");
    $from = settings("tiwilio_from_no");
    $twilio = new Client($AccountSid, $AuthToken);

    $msg = $this->language['lg_your_appointmen3'].' '.$inputdata["doctor_name"];

    $mobileno="+".$inputdata['patient_mobile'];

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


  }

  public function send_appoinment_sms($appoinments_details,$to,$call_type)
  {

    $inputdata=$this->book->get_appoinments_details($appoinments_details['id']);

    $AccountSid = settings("tiwilio_apiKey");
    $AuthToken = settings("tiwilio_apiSecret");
    $from = settings("tiwilio_from_no");
    $twilio = new Client($AccountSid, $AuthToken);

    $website_name = !empty(settings("website_name"))?settings("website_name"):"Doccure";

    // $msg = $this->language['lg_your_appointmen3'].' '.$inputdata["doctor_name"];

    if($to=='patient'){
      $msg = 'Hello '.ucwords($inputdata["patient_name"]).', '.ucwords($inputdata["doctor_name"]).' has started the video call. Please log into your portal and join the '.strtolower($call_type).' call. Thank you '.$website_name;
      $mobileno="+".$inputdata['patient_mobile'];
    }else{
      $msg = 'Hello '.ucwords($inputdata["doctor_name"]).', '.ucwords($inputdata["patient_name"]).' is currently in the video call waiting room. Please log into your portal and join the '.strtolower($call_type).' call. Thank you '.$website_name;
      $mobileno="+".$inputdata['doctor_mobile'];
    }

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

  }

    public function get_doctor_details()
    {
       $doctor_id=$this->input->post('doctor_id');
       $appointment_id=$this->input->post('appointment_id');
       $user_detail=user_detail($doctor_id);
       $response['status']=$this->db->select('call_status,review_status')->where('md5(id)',$appointment_id)->get('appointments')->row_array();
       $response['name']=ucfirst($user_detail['first_name'].' '.$user_detail['last_name']);

       echo json_encode($response);
    }

    public function add_reviews()
  {

       $this->db->where('md5(id)',$this->input->post('appointment_id'));
       $this->db->update('appointments',array('review_status' =>1, 'appointment_status' =>1));


      $review_data['rating']=$this->input->post('rating');
      $review_data['doctor_id']=$this->input->post('doctor_id');
      $review_data['title']=$this->input->post('title');
      $review_data['review']=$this->input->post('review');
      $review_data['user_id']=$this->session->userdata('user_id');
      $review_data['created_date'] = date('Y-m-d H:i:s');
      $review_data['time_zone'] = date_default_timezone_get();

      $this->db->insert('rating_reviews',$review_data);
      echo 'success';


      
  }

  public function lab_appoinments()
    {
        
       
          $this->data['module']    = 'patient';
          $this->data['page'] = 'lab_appoinments';
          $this->load->vars($this->data);
          $this->load->view($this->data['theme'].'/template');
        
       
    }

    public function lab_appointment_list() {
        $user_id=$this->session->userdata('user_id');
        $list = $this->appoinment->get_labappointment_details($user_id);
        $data = array();
        $no = $_POST['start'];
        $a=$no+1;
        foreach ($list as $lab_payments) {   
            $val='Failed';
            $cls = '';
            if($lab_payments['payment_status'] == '1')
            {
                $val = 'Success';
            }
            $profileimage=(!empty($lab_payments['profileimage']))?base_url().$lab_payments['profileimage']:base_url().'assets/img/user.png';

            $no++;
            $row   = array();
            $row[] = $a++;
            $row[] ='<h2 class="table-avatar">
                        <a target="_blank" href="#" class="avatar avatar-sm mr-2">
                        <img class="avatar-img rounded-circle" src="'.$profileimage.'" alt="User Image">
                        </a>
                        <a target="_blank" href="#">'.ucfirst($lab_payments['patient_name']).'</a>
                    </h2>';
            /*$test_name="";
            $array_ids=explode(',', $lab_payments['test_ids']);
            foreach ($array_ids as $key => $value) {
                $this->db->select('*');
                $this->db->where('id',$value);
                $query = $this->db->get('lab_tests');
                $result = $query->row_array();
                if($key > 0){
                    $test_name .=",";
                }
                $test_name .=$result['lab_test_name'];


                
            }*/


            $user_currency=get_user_currency();
            $user_currency_code=$user_currency['user_currency_code'];
            $user_currency_rate=$user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code))?$user_currency_code:$lab_payments['currency_code'];

            $rows   = array();
             /**
              * @var array<array{currency_code:string}> $rows
            */
            $currency_option = (!empty($user_currency_code))?$user_currency_code:$rows['currency_code'];

            $rate_symbol = currency_code_sign($currency_option);

            $amount=get_doccure_currency($lab_payments['total_amount'],$lab_payments['currency_code'],$user_currency_code);

            // $row[] =$test_name;
            $row[] =$lab_payments['lab_test_names'];

            $row[]=date('d M Y',strtotime($lab_payments['lab_test_date']));
            $row[] = $rate_symbol.$amount;
            $row[]=date('d M Y',strtotime($lab_payments['payment_date']));
            $row[] = $val;
            $row[] = $lab_payments['cancel_status'];
            $row[] = '
                        <a class="btn btn-sm bg-success-light" onclick="view_docs('.$lab_payments['id'].')" href="javascript:void(0)">
                            <i class="fe fe-eye"></i> View Document
                        </a>
                        
                        ';

            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->appoinment->labappointments_count_all($user_id),
            "recordsFiltered" => $this->appoinment->labappointments_count_filtered($user_id),
            "data" => $data,
            );
        //output to json format
        echo json_encode($output);
    }

    public function get_docs($id){

    $path="uploads/lab_result/".$id;

    $file_array=array();

    foreach(glob($path.'/*.*') as $file) {
      array_push($file_array, $file);
    }
    echo json_encode($file_array); 

  }
  
  public function change_complete_status(){
	 $appoinments_id=$this->input->post('complete_appoinments_id');
	 expired_appoinments($appoinments_id);
	 $this->session->set_flashdata('success_message',$this->language['lg_your_appointmen4']);
     redirect(base_url().'appoinments');
  }

}
