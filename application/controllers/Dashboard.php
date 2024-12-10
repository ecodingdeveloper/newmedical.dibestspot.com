<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {


	public $data;
    public $session;
    public $timezone;
    public $lang;
    public $language;
    public $appoinment;
    public $input;
    public $dashboard;
    public $db;
    public $home;
    public $pharmacy;
    public $sendemail;

    public function __construct() {

	parent::__construct();

	if ($this->session->userdata('user_id') == '') {
	    if ($this->session->userdata('admin_id')) {
		redirect(base_url() . 'home');
	    } else {
		redirect(base_url() . 'signin');
	    }
	}

	$this->data['theme'] = 'web';
	$this->data['page'] = '';
	$this->data['base_url'] = base_url();

	$this->timezone = $this->session->userdata('time_zone');
	if (!empty($this->timezone)) {
	    date_default_timezone_set($this->timezone);
	}

	// $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):'english';
    $lan=default_language();
    $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):strtolower($lan['language']);
  	$this->data['language'] = $this->lang->load('content', $lang, true);
  	$this->language = $this->lang->load('content', $lang, true);

	$this->load->model('appoinments_model', 'appoinment');
	$this->load->model('home_model', 'home');
	$this->load->model('dashboard_model','dashboard');
	$this->load->model('lab_model','lab');
    }

		public function index() {

			$user_id = $this->session->userdata('user_id');
		
			if ($this->session->userdata('role') == '1') { //doctor
					$this->data['module'] = 'doctor';
					$this->data['page'] = 'doctor_dashboard';
				 $this->data['total_patient'] = $this->appoinment->get_total_patient($user_id);
				 $this->data['today_patient'] = $this->appoinment->get_today_patient($user_id);
					$this->data['recent'] = $this->appoinment->get_recent_booking($user_id);
					$this->load->vars($this->data);
					$this->load->view($this->data['theme'] . '/template');
			} else if ($this->session->userdata('role') == '2') {  // patient
					$this->data['module'] = 'patient';
					$this->data['page'] = 'patient_dashboard';
					$this->data['patient_id'] = $user_id;
					$this->load->vars($this->data);
					$this->load->view($this->data['theme'] . '/template');
			} else if ($this->session->userdata('role') == '3') {  // Hospital/Clinic
					$this->data['module'] = 'hospital';
					$this->data['page'] = 'hospital_dashboard';
					$this->data['total_branch'] = $this->appoinment->get_total_branch($user_id);
					$this->data['total_doctor'] = $this->appoinment->get_total_docotor($user_id);
					$this->data['lab_id'] = $user_id;
					$this->load->vars($this->data);
					$this->load->view($this->data['theme'] . '/template');
			} else if ($this->session->userdata('role') == '4') {  // Labs
					$this->data['module'] = 'lab';
					$this->data['page'] = 'lab_dashboard';
					$this->data['lab_id'] = $user_id;
					$this->data['total_test'] = $this->appoinment->get_total_test($user_id);
			   $this->data['today_patient'] = $this->appoinment->get_today_labpatient($user_id);
					$this->data['recent'] = $this->appoinment->get_recent_labbooking($user_id);
					$this->load->vars($this->data);
					$this->load->view($this->data['theme'] . '/template');
			}else if($this->session->userdata('role') == '6'){
		
				$this->data['module'] = 'doctor';
					$this->data['page'] = 'doctor_dashboard';
			    $this->data['total_patient'] = $this->appoinment->get_total_patient($user_id);
			    $this->data['today_patient'] = $this->appoinment->get_today_patient($user_id);
					$this->data['recent'] = $this->appoinment->get_recent_booking($user_id);
					$this->load->vars($this->data);
					$this->load->view($this->data['theme'] . '/template');
					
			} else {
		
		
					$this->data['upcoming_order_list'] = $this->home->pharmacy_order_list_upcoming($user_id);
					$this->data['today_order_list'] = $this->home->pharmacy_order_list_today($user_id);
					$this->data['total_order'] = $this->home->pharmacy_order_list($user_id);
		
		
					$this->load->model('pharmacy_model', 'pharmacy');
					$this->data['module'] = 'pharmacy';
					$this->data['page'] = 'pharmacy_dashboard';
					$this->data['new_quotation_count'] = $this->pharmacy->get_new_quotation_count($user_id);
					$this->load->vars($this->data);
					$this->load->view($this->data['theme'] . '/template');
			}
				}

    public function appoinments_list() {
	$user_id = $this->session->userdata('user_id');
	$list = $this->appoinment->get_datatables($user_id);
	$data = array();
	$no = $_POST['start'];
	$a = 1;

	foreach ($list as $appoinments) {

	    if ($appoinments['payment_method'] == 'Pay on Arrive') {
		$hourly_rate = 'Pay on Arrive';
	    } else {
		$hourly_rate = !empty($appoinments['per_hour_charge']) ? $appoinments['per_hour_charge'] : 'Free';
	    }

	    $current_timezone = $appoinments['time_zone'];
	    $old_timezone = $this->session->userdata('time_zone');

	    $appointment_date = date('d M Y', strtotime(converToTz($appoinments['appointment_date'], $old_timezone, $current_timezone)));
	    $appointment_time = date('h:i A', strtotime(converToTz($appoinments['from_date_time'], $old_timezone, $current_timezone)));
	    $appointment_end_time = date('h:i A', strtotime(converToTz($appoinments['to_date_time'], $old_timezone, $current_timezone)));
	    $created_date = date('d M Y', strtotime(converToTz($appoinments['created_date'], $old_timezone, $current_timezone)));
	    $hourly_rate = $hourly_rate;
	    $type = $appoinments['type'];
		

	    if ($appoinments['approved'] == 1 && $appoinments['appointment_status'] == 0) {

		$status = '<a href="javascript:void(0);" onclick="conversation_status(\'' . $appoinments['id'] . '\',\'0\')"  class="btn btn-sm bg-danger-light"><i class="fas fa-times"></i>'.$this->language['lg_cancel'].'</a>';
	    } elseif ($appoinments['approved'] == 0) {
		$status = '<a href="javascript:void(0);" onclick="conversation_status(\'' . $appoinments['id'] . '\',\'1\')" class="btn btn-sm bg-success-light"><i class="fas fa-check"></i>'.$this->language['lg_accept'].'</a>';
	    }

	    $profile_image = (!empty($appoinments['profileimage'])) ? base_url() . $appoinments['profileimage'] : base_url() . 'assets/img/user.png';
	    $no++;
	    $row = array();
	    $row[] = $no;
	    $row[] = '<h2 class="table-avatar">
                  <a href="' . base_url() . 'mypatient-preview/' . base64_encode($appoinments['appointment_from']) . '" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image"></a>
                  <a href="' . base_url() . 'mypatient-preview/' . base64_encode($appoinments['appointment_from']) . '">' . ucfirst($appoinments['first_name'] . ' ' . $appoinments['last_name']) . ' <span>#PT00' . $appoinments['appointment_from'] . '</span></a>
                </h2>';

	    $from_date_time = '';
	    if (!empty($appoinments['time_zone'])) {
		$from_timezone = $appoinments['time_zone'];
		$to_timezone = date_default_timezone_get();
		$from_date_time = $appoinments['from_date_time'];
		$from_date_time = converToTz($from_date_time, $to_timezone, $from_timezone);
		$to_date_time = $appoinments['to_date_time'];
		$to_date_time = converToTz($to_date_time, $to_timezone, $from_timezone);
		$row[] = date('d M Y', strtotime($from_date_time)) . ' <span class="d-block text-info">' . date('h:i A', strtotime($from_date_time)).' - '.date('h:i A', strtotime($to_date_time)) . '</span>';
	    } else {
		$row[] = '-';
	    }

	    $hourly_rate = ($type=='Clinic')?$hourly_rate:default_currency_symbol().$hourly_rate;
	    $hourly_rate = convert_to_user_currency($hourly_rate);
		$payment_method=$appoinments['payment_method'];
		if($payment_method=='Pay on Arrive'){
			$hourly_rate='Pay on Arrive';
		}
		
	    $row[] = ucfirst($appoinments['type']);

	    
	    if($this->session->userdata('role')==6)
	    {
	    	if($appoinments['role']!=6)
	    	{
	    		$row[] = $appoinments['doctor_name'];
	    	}
	    	else
	    	{
	    		$row[]="-";
	    	}
		}

	    $row[] = '<div class="table-action">
                  <a href="javascript:void(0);" onclick="show_appoinments_modal(\'' . $appointment_date . '\',\'' . $appointment_time . ' - ' . $appointment_end_time . '\',\'' . $hourly_rate . '\',\'' . $type . '\')" class="btn btn-sm bg-info-light">
                    <i class="far fa-eye"></i> View
                  </a>

                </div>';

	    $data[] = $row;
	}



	$output = array(
	    "draw" => $_POST['draw'],
	    "recordsTotal" => $this->appoinment->count_all($user_id),
	    "recordsFiltered" => $this->appoinment->count_filtered($user_id),
	    "data" => $data,
	);
	//output to json format
	echo json_encode($output);
    }


    // Lab Appoinment (Today & Upcoming)
     public function lab_appointment_list() {
        $lab_id=$this->session->userdata('user_id');
        $list = $this->lab->get_labappointment_details($lab_id);
        $data = array();
        $no = $_POST['start'];
        $a=1;
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
                        <a href="#" class="avatar avatar-sm mr-2">
                        <img class="avatar-img rounded-circle" src="'.$profileimage.'" alt="User Image">
                        </a>
                        <a href="#">'.ucfirst($lab_payments['patient_name']).'</a>
                    </h2>';
            $test_name="";
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


                
            }

            $row[] =$test_name;

            $row[]=date('d M Y',strtotime($lab_payments['lab_test_date']));
            $row[] = convert_to_user_currency($lab_payments['total_amount']);
            $row[]=date('d M Y',strtotime($lab_payments['payment_date']));
            $row[] = $val;
            $row[] = '
                        <a class="btn btn-sm bg-success-light" onclick="upload_lab_docs('.$lab_payments['id'].')" href="javascript:void(0)">
                            <i class="fe fe-pencil"></i> Upload
                        </a>
                        
                        ';

            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->lab->labappointments_count_all($lab_id),
            "recordsFiltered" => $this->lab->labappointments_count_filtered($lab_id),
            "data" => $data,
            );
        //output to json format
        echo json_encode($output);
    }
    // Lab Appoinment (Today & Upcoming)

    public function favourites() {
	$user_id = $this->session->userdata('user_id');

	if ($this->session->userdata('role') == '1') {
	    redirect(base_url() . 'dashboard');
	} else {
	    $this->data['module'] = 'patient';
	    $this->data['page'] = 'favourites';
	    $this->data['favourites'] = $this->appoinment->get_favourites($user_id);
	    $this->load->vars($this->data);
	    $this->load->view($this->data['theme'] . '/template');
	}
    }

    public function reviews() {
	$user_id = $this->session->userdata('user_id');

	if ($this->session->userdata('role') == '1') {

	    $this->data['module'] = 'doctor';
	    $this->data['page'] = 'reviews';
	    $this->data['reviews'] = $this->home->review_list_view($user_id);
	    $this->load->vars($this->data);
	    $this->load->view($this->data['theme'] . '/template');
	}else if ($this->session->userdata('role') == '6') {

	    $this->data['module'] = 'doctor';
	    $this->data['page'] = 'reviews';
	    $this->data['reviews'] = $this->home->review_list_view($user_id);
	    $this->load->vars($this->data);
	    $this->load->view($this->data['theme'] . '/template');
	}  else {
	    redirect(base_url() . 'dashboard');
	}
    }

    public function add_review_reply() {

	$user_id = $this->session->userdata('user_id');
	if ($this->session->userdata('role') == '1') {

	    $data['review_id'] = $this->input->post('review_id');
	    $data['reply'] = $this->input->post('reply');
	    $data['created_date'] = date('Y-m-d H:i:s');
	    $data['time_zone'] = date_default_timezone_get();

	    $this->db->insert('review_reply', $data);

	    $result = ($this->db->affected_rows() != 1) ? false : true;
	    if ($result == true) {

		$response['msg'] = $this->language['lg_reply_added_suc1'];
		$response['status'] = 200;
	    } else {
		$response['msg'] = $this->language['lg_something_went_'];
		$response['status'] = 500;
	    }
	    echo json_encode($response);
	} else {
	    redirect(base_url() . 'dashboard');
	}
    }

    public function delete_reply() {

	$user_id = $this->session->userdata('user_id');
	if ($this->session->userdata('role') == '1' || $this->session->userdata('role')=='2') {

	    $this->db->where('id', $this->input->post('id'));

	    $this->db->delete('review_reply');

	    $result = ($this->db->affected_rows() != 1) ? false : true;
	    if ($result == true) {

		$response['msg'] = $this->language['lg_reply_deleted_s'];
		$response['status'] = 200;
	    } else {
		$response['msg'] = $this->language['lg_something_went_'];
		$response['status'] = 500;
	    }
	    echo json_encode($response);
	} else {
	    redirect(base_url() . 'dashboard');
	}
    }

    public function send_verification_email() {
	$user_detail = user_detail($this->session->userdata('user_id'));
	$user_detail['id'] = $this->session->userdata('user_id');
	$this->load->library('sendemail');
	$this->sendemail->send_email_verification($user_detail);
    }

    public function maps() {
	$user_id = $this->session->userdata('user_id');

	
	    $this->data['module'] = 'patient';
	    $this->data['page'] = 'maps';
	    $this->load->vars($this->data);
	    $this->load->view($this->data['theme'] . '/template');
	
    }

    public function get_direction($id = NULL) {
	$id = isset($id) ? base64_decode($id) : '';
	$user_id = $this->session->userdata('user_id');

	
	    if ($id > 0) {
		$doctor_details =user_detail($id);
		$this->data['to_address'] = $doctor_details['address1'] . "," . $doctor_details['cityname'] . ', ' . $doctor_details['statename'] . ', ' . $doctor_details['postal_code'] . ' ' . $doctor_details['countryname'];
		
	    }
	    $patient_details = user_detail($this->session->userdata('user_id'));
	    //print_r($patient_details);
	    //exit;

	    $this->data['from_address'] = $patient_details['address1'] . "," . $patient_details['cityname'] . ', ' . $patient_details['statename'] . ', ' . $patient_details['postal_code'] . ' ' . $patient_details['countryname'];
	    $this->data['module'] = 'patient';
	    $this->data['page'] = 'maps';
	    $this->load->vars($this->data);
	    $this->load->view($this->data['theme'] . '/template');
	
    }

    public function notification(){

      $this->data['page'] = 'notification';
      if ($this->session->userdata('role') == '1') {
      	$this->data['module'] = 'doctor';
      }else{
	    $this->data['module'] = 'patient';
	  }
      $this->notification_update();
      $this->data['count'] =$this->dashboard->get_notification(1,5,1,$this->session->userdata('user_id'));
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
  }

  public function search_notification()
  {

       $response=array();
       $result=array();
        $page=$this->input->post('page');
        $limit=5;
        $response['count'] =$this->dashboard->get_notification($page,$limit,1,$this->session->userdata('user_id'));
        $notification_list = $this->dashboard->get_notification($page,$limit,2,$this->session->userdata('user_id'));

        if (!empty($notification_list)) {
          foreach ($notification_list as $rows) {

            $data['id']=$rows['id'];
			if($rows['user_id'] == 0){
				$data['from_name']='Admin';
			} else {
				$data['from_name']=ucfirst(($this->session->userdata('user_id')==$rows['user_id'])?'You':$rows['from_name']);
			}
            if($this->session->userdata('user_id')==$rows['user_id'])
                $data['profile_image']=(!empty($rows['to_profile_image']))?base_url().$rows['to_profile_image']:base_url().'assets/img/user.png';
            else
             	$data['profile_image']=(!empty($rows['profile_image']))?base_url().$rows['profile_image']:base_url().'assets/img/user.png';
            $data['to_name']=ucfirst(($this->session->userdata('user_id')==$rows['to_user_id'])?'You':$rows['to_name']);
            $data['text']=ucfirst(($this->session->userdata('user_id')==$rows['user_id'])?str_replace('has', 'have', $rows['text']):$rows['text']);
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
    	if($this->session->userdata('role')==1){
    		$this->db->where('is_viewed_doc',1);
    		$this->db->group_start();
     		$this->db->where('user_id',$this->session->userdata('user_id'));
        	$this->db->or_where('to_user_id',$this->session->userdata('user_id'));
        	$this->db->group_end();
        }else{
        	$this->db->where('is_viewed_pat',1);
    		$this->db->group_start();
     		$this->db->where('user_id',$this->session->userdata('user_id'));
        	$this->db->or_where('to_user_id',$this->session->userdata('user_id'));
        	$this->db->group_end();
        }
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
	$notify_id = $this->input->post('id');
  	/*if($this->session->userdata('role')==1){ */
	if($this->session->userdata('role')!=2){
    	$data=array('is_viewed_doc'=>1);
    	$this->db->where('is_viewed_doc',0);
    	$this->db->group_start();
     	$this->db->where('user_id',$this->session->userdata('user_id'));
        $this->db->or_where('to_user_id',$this->session->userdata('user_id'));
        $this->db->group_end();
  	}
	else{
		$data=array('is_viewed_pat'=>1);
		$this->db->where('is_viewed_pat',0);
		$this->db->group_start();
     	$this->db->where('user_id',$this->session->userdata('user_id'));
        $this->db->or_where('to_user_id',$this->session->userdata('user_id'));
        $this->db->group_end();
	}
    if(!empty($notify_id))
	$this->db->where('id',$notify_id);
    $this->db->update('notification',$data);
    $response['status']=200;
    $response['msg']="Updated successfully";
    // echo json_encode($response);
  }
  
  public function patient_reviews() {
	$user_id = $this->session->userdata('user_id');


	if ($this->session->userdata('role') == '2') {
        // print_r($user_id);exit();
	    $this->data['module'] = 'patient';
	    $this->data['page'] = 'patient_reviews';
	    $this->data['reviews'] = $this->home->patient_review_listview($user_id);
	    $this->load->vars($this->data);
	    $this->load->view($this->data['theme'] . '/template');
	} else {
	    redirect(base_url() . 'dashboard');
	}
    }

}
