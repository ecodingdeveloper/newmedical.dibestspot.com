<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team extends CI_Controller {

    public $data;
    public $session;
    public $timezone;
    public $lang;
    public $language;
    public $signin;
    public $input;
    public $doctor;
    public $db;
    public $home;

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
        $this->load->model('team_model','team');
        $this->load->model('signin_model','signin');
        $this->load->model('users_model','user');


        
         
    }


    public function index()
    {

          $this->data['module']    = 'team';
          $this->data['page'] = 'add_team';
          $this->load->vars($this->data);
          $this->load->view($this->data['theme'].'/template');
       
       
    }

    public function list_team(){
      $user_id = $this->session->userdata('user_id');
      $list = $this->team->get_datatables($user_id);
//       echo '<pre>';
// print_r($list);
// echo '</pre>';
// die("dada");
      $data = array();
      $no = $_POST['start'];
      $a = 1;

      foreach ($list as $team) {
        
        $profileimage=(!empty($team['profileimage']))?base_url().$team['profileimage']:base_url().'assets/img/user.png';
      
        $row = array();
        $row[] =$a;
        // $row[] ='#TM00'.$team['id'];
        $row[] = '<h2 class="table-avatar">
                    <a class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="'.$profileimage.'" alt="User Image"></a>
                    <a >'.ucfirst($team['first_name'].' '.$team['last_name']).'</a>
                  </h2>';
        $row[] = $team['email'];
        $row[] = $team['mobileno'];      
        $row[] = date('d M Y',strtotime($team['created_date'])).'<br><small>'.date('h:i A',strtotime($team['created_date'])).'</small>';
        $row[] = '<div class="table-action">
        <a  href="javascript:void(0);" onclick="edit_team('.$team['id'].')"  class="btn btn-sm bg-primary-light">
        <i class="fas fa-edit"></i> Edit
        </a>
        <a href="javascript:void(0);" onclick="delete_team('.$team['id'].')" class="btn btn-sm bg-info-light">
        <i class="far fa-trash-alt"></i> Delete
        </a></div>';

        $data[] = $row;
        $a++;
    }
  
  
  
    $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->team->get_datatables($user_id,1),
        "recordsFiltered" => $this->team->get_datatables($user_id,1),
        "data" => $data,
    );
    //output to json format
    echo json_encode($output);

    }

    public function get_team_details(){
     $pre_id = $this->input->post('pre_id');
     $det =  $this->team->get_datatables($pre_id,2);
    //  echo '<pre>';
    //  print_r($det);
    //  echo '</pre>';
    //  die("dadasd");
     $result['first_name'] =$det[0]['first_name'];
     $result['last_name'] =$det[0]['last_name'];
     $result['email'] =$det[0]['email'];
     $result['mobile'] =$det[0]['mobileno'];
     $result['country_code'] =$det[0]['country_code'];
     echo json_encode($result);

    }
    public function team_delete($id) {
      // Check if the ID is valid
      if (empty($id)) {
          echo json_encode(array("status" => FALSE, "message" => "Invalid ID."));
          return;
      }

      // Attempt to delete the record based on the provided ID
      $deleted = $this->team->delete($id); // Pass the ID directly

      // Return a JSON response
      if ($deleted) {
          echo json_encode(array("status" => TRUE));
      } else {
          echo json_encode(array("status" => FALSE, "message" => "Delete failed."));
      }

    }

    public function add_team()
      {

        $inputdata=array();
        $response=array();
        $user_id = $this->session->userdata('user_id');
        $inputdata['first_name']=$this->input->post('first_name');
        $inputdata['last_name']=$this->input->post('last_name');
        $inputdata['email']=strtolower(trim($this->input->post('email')));
        $inputdata['mobileno']=$this->input->post('mobileno');
        $inputdata['country_code']=$this->input->post('country_code');
        $inputdata['username'] = generate_username($inputdata['first_name'].' '.$inputdata['last_name'].' '.$inputdata['mobileno']);
        $inputdata['role']=$this->input->post('role');
        $inputdata['hospital_id']=$user_id;
        $inputdata['role'] = $this->input->post('role');
        $inputdata['status'] = 1;
        $inputdata['password']=md5($this->input->post('password'));
        $inputdata['confirm_password']=md5($this->input->post('confirm_password'));
        $inputdata['created_date']=date('Y-m-d H:i:s');
        if($this->input->post('user_id') != ""){
          $this->db->where('id',$this->input->post('user_id'));
          $this->db->update('users',$inputdata);
          $response['msg']="Updated Successfully";
          $response['status']=200; 
          echo json_encode($response);
          return;
        }else{
        $result=$this->signin->signup($inputdata);
        }
        if($result==true)
        {   
        $inputdata['id']=$this->db->insert_id();
        $response['id'] = base64_encode($inputdata['id']);
        $this->load->library('sendemail');
        $this->sendemail->send_email_verification($inputdata);
        $response['msg']=$this->language['lg_registration_su']; 
        $this->session->set_flashdata('success_message',$this->language['lg_registration_su']);
        $response['status']=200;              
      }
      else
      {
        $response['msg']=$this->language['lg_registration_fa'];
        $response['status']=500; 
      } 
      echo json_encode($response);
      }
      public function assign_doc(){
        $id = $this->input->post('id');
        $app_id = $this->input->post('app_id');
        $this->doctor->assign_doc($id,$app_id);
        $response['msg']="Doctor added"; 
        $this->session->set_flashdata('success_message','Doctor assigned successfully');
        $response['status']=200;     
        echo json_encode($response); 
      }

      public function check_email() {
        $email = strtolower(trim($this->input->post('email'))); 
        $id = $this->input->post('id');    
        $result = $this->doctor->check_email($email,$id);
        if ($result > 0) {
        echo 'false';
        } else {
          echo 'true';
        }
      }

      public function check_mobileno()
      {
        $mobileno = $this->input->post('mobileno');     
        $id = $this->input->post('id'); 
        $result = $this->doctor->check_mobileno($mobileno,$id);
        if ($result > 0) {
        echo 'false';
      } else {
        echo 'true';
      }
      
      }

}