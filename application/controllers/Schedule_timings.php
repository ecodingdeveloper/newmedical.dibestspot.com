<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule_timings extends CI_Controller {

    public $data;
    public $session;
    public $timezone;
    public $lang;
    public $language;
    public $input;
    public $db;

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
        $this->data['module']    = 'doctor';
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
         
    }

    public function index()
    {
        
        if($this->session->userdata('role')=='1'){
          $this->data['page'] = 'schedule_timings';
          $where = array('user_id'=>$this->session->userdata('user_id'));
          $this->data['slots']=$this->db->get_where('schedule_timings',$where)->row_array();
          $this->load->vars($this->data);
          $this->load->view($this->data['theme'].'/template');
        }else if($this->session->userdata('role')=='6'){
          $this->data['page'] = 'schedule_timings';
          $where = array('user_id'=>$this->session->userdata('user_id'));
          $this->data['slots']=$this->db->get_where('schedule_timings',$where)->row_array();

         // echo $this->db->last_query();  exit;


          $this->load->vars($this->data);
          $this->load->view($this->data['theme'].'/template');
        }
        else
        {
          redirect(base_url().'dashboard');
        }
       
    }

    function schedule_list()
    {

       $id = $this->session->userdata('user_id');
       $day_id = $this->input->post('day_id');
       $day_name = $this->input->post('day_name');
       $append_html = $this->input->post('append_html');
       $this->db->where('user_id',$id);
       $this->db->where('day_id',$day_id); 
       $result = $this->db->get('schedule_timings')->result_array();
       $data['available_time'] = $result;
       $data['day_id'] = $day_id;
       $data['day_name'] = $day_name;
       $data['append_html'] = $append_html;
       $data['language']=language();
       echo  $this->load->view('web/modules/doctor/schedule_timings_view',$data,TRUE);
     
    }

    public function get_day_slots()
    {
      $response=array();
       $id = $this->session->userdata('user_id');
       $day_id = $this->input->post('day_id');
       $this->db->where('user_id',$id);
       $this->db->where('day_id',$day_id); 
       $result = $this->db->get('schedule_timings')->result_array();
       if(!empty($result))
       {
          $data['edit'] = $result;
          $data['language']=language();
          $response['details']= $this->load->view('web/modules/doctor/schedule_timings_edit',$data,TRUE);
          $response['count']=count($result);
       }

       echo json_encode($response);
       
    }

     public function get_slots()
    {
       $id = $this->session->userdata('user_id');
       $data['day_id']=$this->input->post('day_id');
       $data['append_html']=$this->input->post('append_html');
       $data['day_name']=$this->input->post('day_name');
       $data['slot']=$this->input->post('slot');
       $already_day_id=$this->db->select('day_id')->where('user_id',$id)->get('schedule_timings')->result_array();
       $data['already_day_id']=array();
       if($already_day_id){
        $data['already_day_id']=$this->multi_to_single($already_day_id);
       }
       $data['language']=language();
       echo  $this->load->view('web/modules/doctor/schedule_timings_add',$data,TRUE);
    }

    private function multi_to_single($array) { 
      $days_id=array();
      foreach ($array as $value) {
       $days_id[]=$value['day_id'];
      }
       return $days_id; 
  } 

    public function get_tokens()
    {
      $start=strtotime($this->input->post('start_time'));
      $end=strtotime($this->input->post('end_time'));
      $slots=$this->input->post('slots');
       if($slots >= 5){
          for ($i=$start;$i<=$end;$i = $i + $slots*60){
           $datas[]=date('H:i:s',$i);   
         }   
       }else{
         for ($i=$start;$i<=$end;$i = $i + 60*60){
           $datas[]=date('H:i:s',$i);   
         }       
       }

       echo count($datas)-1;
    
    }

    
      
    Public function get_available_time_slots(){

      if(!empty($_POST['slot'])){
    
        $slot =$_POST['slot'];  
        @$day_id =$_POST['day_id'];  
        $start=strtotime('00:00');
        $end=strtotime('24:00');
        $user_id = $this->session->userdata('user_id');
        if($slot >= 5){
    
          $where = array('user_id'=>$user_id,'day_id' =>$day_id);
          $result =$this->db->get_where('schedule_timings',$where)->row_array();
          if( !empty($result) && $result['slot'] != $slot){
           $wh = array('slot'=>$result['slot'],'user_id'=>$user_id);
           $this->db->delete('schedule_timings',$wh);
          }
        for ($i=$start;$i<=$end;$i = $i + $slot*60){
         $res['label']= date('g:i A',$i);
         $res['value']= date('H:i:s',$i);
    
    
         /* Disabling already added timeslots */
    
        $res['added'] = false;
        $res['start_time'] = 0;
        $res['end_time'] = 0; 
        
    
         // if($result['start_time'] == $res['value']){ // already added or not       
         //  $res['added'] = true;
         //  $res['start_time'] = $result['start_time'];
         //  $res['end_time'] = $result['end_time'];
         // }
          
    
         $where = array('end_time'=>$res['value'],'user_id'=>$user_id ,'day_id' =>$day_id);
         $result =$this->db->get_where('schedule_timings',$where)->row_array();
    
          //  if($result['end_time'] == $res['value']){ // already added or not       
          // $res['added'] = true;
          // $res['start_time'] = $result['start_time'];
          // $res['end_time'] = $result['end_time'];
          // }
    
    
          if(!empty($_POST['start_time']) ){
              if($_POST['start_time'] == $res['value'] || strtotime($_POST['start_time']) > strtotime($res['value'])){
                $res['added'] = true;
                $res['start_time'] = 0;
                $res['end_time'] = 0;
              }
          }
    
          if(!empty($_POST['end_time']) ){
            $end_times=strtotime($_POST['end_time']);
            $end_time=($end_times - (($slot*60)));
            $end_time=date('H:i:s',$end_time);
            
           if($end_time == $res['value'] || strtotime($end_time) > strtotime($res['value'])){
                $res['added'] = true;
                $res['start_time'] = 0;
                $res['end_time'] = 0;
              }
          }
    
    
         $datas[]=$res;
       }   
     }else{
      
       for ($i=$start;$i<=$end;$i = $i + 60*60){
         $res['label']= date('g:i A',$i);
         $res['value']= date('H:i:s',$i);
    
    
         /* Disabling already added timeslots */
    
        $res['added'] = false;
        $res['start_time'] = 0;
        $res['end_time'] = 0;
        @$day_id =$_POST['day_id']; 
         
         $where = array('start_time'=>$res['value'],'day_id'=>$day_id,'user_id'=>$user_id);
         $result =$this->db->get_where('schedule_timings',$where)->row_array();
    
         // if($result['start_time'] == $res['value']){ // already added or not       
         //  $res['added'] = true;
         //  $res['start_time'] = $result['start_time'];
         //  $res['end_time'] = $result['end_time'];
         // }
          
    
         $where = array('end_time'=>$res['value'],'day_id'=>$day_id,'user_id'=>$user_id);
         $result =$this->db->get_where('schedule_timings',$where)->row_array();
    
          //  if($result['end_time'] == $res['value']){ // already added or not       
          // $res['added'] = true;
          // $res['start_time'] = $result['start_time'];
          // $res['end_time'] = $result['end_time'];
          // }
    
          if(!empty($_POST['start_time']) ){
              if($_POST['start_time'] == $res['value'] || strtotime($_POST['start_time']) > strtotime($res['value']) ){
                $res['added'] = true;
                $res['start_time'] = 0;
                $res['end_time'] = 0;
              }
          }
    
          if(!empty($_POST['end_time']) ){
            $end_times=strtotime($_POST['end_time']);
            $end_time=($end_times - ((60*60)));
            $end_time=date('H:i:s',$end_time);
            
           if($end_time == $res['value'] || strtotime($end_time) > strtotime($res['value'])){
                $res['added'] = true;
                $res['start_time'] = 0;
                $res['end_time'] = 0;
              }
          }
    
         $datas[]=$res;
       }       
     }
     echo json_encode($datas);   
    }
    }
    

    Public function get_available_time_slots1(){
      $datas="";

  if(!empty($_POST['slot'])){

    $slot =$_POST['slot'];  
    @$day_id =$_POST['day_id'];  
    $start=strtotime('00:00');
    $end=strtotime('24:00');
    $user_id = $this->session->userdata('user_id');
    if($slot >= 5){
    for ($i=$start;$i<=$end;$i = $i + $slot*60){
     $res['label']= date('g:i A',$i);
     $res['value']= date('H:i:s',$i);


     /* Disabling already added timeslots */

    $res['added'] = false;
    $res['start_time'] = 0;
    $res['end_time'] = 0;


    
     $where = array('start_time'=>$res['value'],'user_id'=>$user_id,'day_id' =>$day_id);
     $result =$this->db->get_where('schedule_timings',$where)->row_array();


     if(!empty($result) && $result['slot'] != $slot){ 
   
      $wh = array('slot'=>$result['slot'],'user_id'=>$user_id);
      $this->db->delete('schedule_timings',$wh);
     }

     // if($result['start_time'] == $res['value']){ // already added or not       
     //  $res['added'] = true;
     //  $res['start_time'] = $result['start_time'];
     //  $res['end_time'] = $result['end_time'];
     // }
      

     $where = array('end_time'=>$res['value'],'user_id'=>$user_id ,'day_id' =>$day_id);
     $result =$this->db->get_where('schedule_timings',$where)->row_array();

      //  if($result['end_time'] == $res['value']){ // already added or not       
      // $res['added'] = true;
      // $res['start_time'] = $result['start_time'];
      // $res['end_time'] = $result['end_time'];
      // }


      if(!empty($_POST['start_time']) ){
          if($_POST['start_time'] == $res['value'] || strtotime($_POST['start_time']) > strtotime($res['value'])){
            $res['added'] = true;
            $res['start_time'] = 0;
            $res['end_time'] = 0;
          }
      }

      if(!empty($_POST['end_time']) ){
        $end_times=strtotime($_POST['end_time']);
        $end_time=($end_times - (($slot*60)));
        $end_time=date('H:i:s',$end_time);
        
       if($end_time == $res['value'] || strtotime($end_time) > strtotime($res['value'])){
            $res['added'] = true;
            $res['start_time'] = 0;
            $res['end_time'] = 0;
          }
      }


     $datas[]=$res;
   }   
 }else{
  
   for ($i=$start;$i<=$end;$i = $i + 60*60){
     $res['label']= date('g:i A',$i);
     $res['value']= date('H:i:s',$i);


     /* Disabling already added timeslots */

    $res['added'] = false;
    $res['start_time'] = 0;
    $res['end_time'] = 0;
    @$day_id =$_POST['day_id']; 
     
     $where = array('start_time'=>$res['value'],'day_id'=>$day_id,'user_id'=>$user_id);
     $result =$this->db->get_where('schedule_timings',$where)->row_array();

     // if($result['start_time'] == $res['value']){ // already added or not       
     //  $res['added'] = true;
     //  $res['start_time'] = $result['start_time'];
     //  $res['end_time'] = $result['end_time'];
     // }
      

     $where = array('end_time'=>$res['value'],'day_id'=>$day_id,'user_id'=>$user_id);
     $result =$this->db->get_where('schedule_timings',$where)->row_array();

      //  if($result['end_time'] == $res['value']){ // already added or not       
      // $res['added'] = true;
      // $res['start_time'] = $result['start_time'];
      // $res['end_time'] = $result['end_time'];
      // }

      if(!empty($_POST['start_time']) ){
          if($_POST['start_time'] == $res['value'] || strtotime($_POST['start_time']) > strtotime($res['value']) ){
            $res['added'] = true;
            $res['start_time'] = 0;
            $res['end_time'] = 0;
          }
      }

      if(!empty($_POST['end_time']) ){
        $end_times=strtotime($_POST['end_time']);
        $end_time=($end_times - ((60*60)));
        $end_time=date('H:i:s',$end_time);
        
       if($end_time == $res['value'] || strtotime($end_time) > strtotime($res['value'])){
            $res['added'] = true;
            $res['start_time'] = 0;
            $res['end_time'] = 0;
          }
      }

     $datas[]=$res;
   }       
 }
 echo json_encode($datas);   
}
}

public function add_schedule()
{
  $inputdata=array();
  $response=array();
  $id=$this->session->userdata('user_id');
  $inputdata['slot']=$this->input->post('slot');
  $day_id=$this->input->post('day_id');
  $inputdata['user_id'] =$id;
  $inputdata['time_zone'] =$this->session->userdata('time_zone');
  $start_time=$this->input->post('start_time');
  $end_time=$this->input->post('end_time');
  $token=$this->input->post('token');
  $sessions=$this->input->post('sessions');

$day_name='';
  for ($j=0; $j <count($day_id) ; $j++) {
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
 
  for ($i=1; $i <= count($sessions) ; $i++) { 

   $inputdata['start_time'] = date('H:i:s',strtotime($start_time[$i])); 
   $inputdata['end_time'] = date('H:i:s',strtotime($end_time[$i])); 
   $inputdata['sessions']=$i; 
   $inputdata['token']=$token[$i];  

      $count = $this->db->get_where('schedule_timings',array('user_id'=>$inputdata['user_id'],'start_time'=>$inputdata['start_time'],'end_time'=>$inputdata['end_time'],'sessions'=>$inputdata['sessions'],'token'=>$inputdata['token'],'day_id'=>$inputdata['day_id']))->num_rows();
      if($count == 0){
        $this->db->insert('schedule_timings',$inputdata);
      }   
 
  }

}

$result=($this->db->affected_rows()!= 1)?false:true;
     if($result==true)
    {
         $response['msg']=$this->language['lg_schedule_timing1']; 
         $response['status']=200;              
    }
   else
    {
        $response['msg']=$this->language['lg_schedule_timing2'];
        $response['status']=500; 
    } 

 echo json_encode($response);

}


public function update_schedule()
{
  $inputdata=array();
  $response=array();
  $id=$this->session->userdata('user_id');
  $day_id=$this->input->post('day_id');

  $this->db->where('user_id',$id)->where('day_id',$day_id);
  $this->db->delete('schedule_timings');


  $inputdata['slot']=$this->input->post('slot');
  $inputdata['user_id'] =$id;
  $inputdata['time_zone'] =$this->session->userdata('time_zone');
  $start_time=$this->input->post('start_time');
  $end_time=$this->input->post('end_time');
  $token=$this->input->post('token');
  $sessions=$this->input->post('sessions');
  $day_name='';
 
    switch ($day_id) {
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
   
   $inputdata['day_id']=$day_id;
   $inputdata['day_name']=$day_name;
   $insert_slot='';
 if(!empty($sessions)){
  for ($i=1; $i <= count($sessions) ; $i++) { 

   $inputdata['start_time'] = date('H:i:s',strtotime($start_time[$i])); 
   $inputdata['end_time'] = date('H:i:s',strtotime($end_time[$i])); 
   $inputdata['sessions']=$i; 
   $inputdata['token']=$token[$i];  

      $count = $this->db->get_where('schedule_timings',array('user_id'=>$inputdata['user_id'],'start_time'=>$inputdata['start_time'],'end_time'=>$inputdata['end_time'],'sessions'=>$inputdata['sessions'],'token'=>$inputdata['token'],'day_id'=>$inputdata['day_id']))->num_rows();
      if($count == 0){
        $this->db->insert('schedule_timings',$inputdata);
		$insert_slot=$this->db->affected_rows();
      }   
 
  }

 }

$result=($this->db->affected_rows()!= 1)?false:true;
     if($result==true)
    {
         $response['msg']=$this->language['lg_schedule_timing1']; 
         $response['status']=200;              
    }
   else
    {
        $response['msg']=$this->language['lg_schedule_timing2'];
        $response['status']=500; 
    } 

 echo json_encode($response);

}

public function delete_schedule_time($value='')
{
  $sts = 0;
  $id = $this->input->post('delete_value');


  $where = array('id'=>$id);
  $data = $this->db->get_where('schedule_timings',$where)->row_array();

  $this->db->where('day_id',$data['day_id']);
  $this->db->where('time_start',$data['time_start']);
  $this->db->where('user_id',$data['user_id']);  
  if($this->db->delete('schedule_timings')){
    $sts = 1;
  }
  echo $sts;
}


}

