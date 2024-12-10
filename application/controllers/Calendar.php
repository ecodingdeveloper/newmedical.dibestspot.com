<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar extends CI_Controller {  
    public $data;
    public $session;
    public $timezone;
    public $lang;
    public $language;
    public $calendar;


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
        $this->load->model('calendar_model','calendar');
        
         
    }


    public function index()
    {

          $this->data['module']    = 'calendar';
          $this->data['page'] = 'calendar';
          $this->load->vars($this->data);
          $this->load->view($this->data['theme'].'/template');
       
       
    }

    public function calendar_view()
  {


     $id = $this->session->userdata('user_id');
     $role = $this->session->userdata('role');


     $result = $this->calendar->calendar_view($id,$role);

     foreach($result as $record){

      $from_date_time =  $record['appointment_date'].' '.$record['appointment_time'];
      $to_date_time =  $record['appointment_date'].' '.$record['appointment_end_time'];
      $from_timezone =$record['time_zone'];

      $to_timezone = $this->session->userdata('time_zone');



      $from_date_time  = converToTz($from_date_time,$to_timezone,$from_timezone);
      $to_date_time  = converToTz($to_date_time,$to_timezone,$from_timezone);

      $from_time  = date('h:i a',strtotime($from_date_time));
      $to_time  = date('h:i a',strtotime($to_date_time));



      $start_time = date('g:i a',strtotime($from_time));
      $end_time = date('g:i a',strtotime($to_time));
      
      $title = $record['first_name'].' '.$record['last_name'];
	
		$timestamp1 = strtotime($record['appointment_time']);
		$timestamp2 = strtotime($record['appointment_end_time']);
		$curdate = date('Y-m-d');
		$dateval = $record['appointment_date'];
		$cur_time= date('H:i');
		$curtime = strtotime($cur_time);

      
         // setting color here
		if($record['approved'] == 0 && date('Y-m-d') > $record['appointment_date']) {
            $color = '#d9534f'; // Cancelled
        }else{
            $color = '#5bc0de'; // Pending
        }
        if($record['approved'] == 1) {
			$color = '#5cb85c';  // Approved
        }

        if($record['approved'] == 2 || $record['approved'] == 0) {
          $color = '#ff0100'; // Cancelled
        }
		
		if($record['approved'] == 1 && $record['appointment_status'] == 1 && $record['call_status'] == 0) {
			if((strtotime($curdate) == strtotime($dateval) || strtotime($dateval) <= strtotime($curdate)) && $timestamp2 < $curtime) {
				$color = '#f4a460'; // Missed
			} else {
				$color = '#5bc0de'; // Upcoming Booking
			}
		}
		if($record['approved'] == 1 && $record['appointment_status'] == 0 && $record['call_status'] == 1) {
			$color = '#09e5ab'; // Booking not yet set as completed and no reviews by patient.
			
		}
		if($record['approved'] == 1 && $record['appointment_status'] == 1 && $record['call_status'] == 1){
			$color = '#008000'; // Completed
		}


        $event_array[] = array(
         'id' => $record['id'],
         'user_id' => $record['appointment_to'],
         'title' => $title,
         'start' =>  $from_date_time,
         'end' => $to_date_time,
         'color' => $color,
         'timezone' =>$from_timezone
       );
      }


if(!empty($event_array)){
       // $this->session->set_userdata(array('search_id'=>''));
    echo json_encode($event_array);
}

}

}