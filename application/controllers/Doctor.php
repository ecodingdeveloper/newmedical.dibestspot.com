<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor extends CI_Controller {

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
        $this->load->model('doctor_model','doctor');
        $this->load->model('signin_model','signin');

        
         
    }


    public function index()
    {

          $this->data['module']    = 'doctor';
          $this->data['page'] = 'add_doctor';
          $this->load->vars($this->data);
          $this->load->view($this->data['theme'].'/template');
       
       
    }

    public function list_doctors(){
      $user_id = $this->session->userdata('user_id');
      $list = $this->doctor->get_datatables($user_id);
      $data = array();
      $no = $_POST['start'];
      $a = 1;

      foreach ($list as $doctor) {
        
        $profile_image=(!empty($doctor['profile']))?base_url().$doctor['profile']:base_url().'assets/img/user.png';
      
        $row = array();
        $row[] =$a;
        $row[] = '<h2 class="table-avatar">
        <a href="#" class="avatar avatar-sm mr-2">
          <img class="avatar-img rounded-circle" src="'.$profile_image.'" alt="User Image">
        </a>
        <a href="'.base_url().'doctor-preview/'.$doctor['username'].'">'.$this->language['lg_dr'].' '.$doctor['name'].' </a>
      </h2>
        ';
        $html='<div class="table-action">
        <a  href="javascript:void(0);" onclick="edit_doctor('.$doctor['id'].')"  class="btn btn-sm bg-primary-light">
        <i class="fas fa-edit"></i> Edit
        </a>
        <a href="javascript:void(0);" onclick="delete_doctor('.$doctor['id'].')" class="btn btn-sm bg-info-light">
        <i class="far fa-trash-alt"></i> Delete
        </a></div>';

        $row[] =$doctor['email'];

        $row[] =($doctor['is_updated']==1)?'Yes':'No';

        $row[] =($doctor['is_verified']==1)?'Yes':'No';
     
        $row[] =$html;

        $data[] = $row;
        $a++;
    }
  
  
  
    $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->doctor->get_datatables($user_id,1),
        "recordsFiltered" => $this->doctor->get_datatables($user_id,1),
        "data" => $data,
    );
    //output to json format
    echo json_encode($output);

    }

    public function get_doctor_details(){
     $pre_id = $this->input->post('pre_id');
     $det =  $this->doctor->get_datatables($pre_id,2);
     $result['first_name'] =$det[0]['first_name'];
     $result['last_name'] =$det[0]['last_name'];
     $result['email'] =$det[0]['email'];
     $result['mobile'] =$det[0]['mobile'];
     $result['country_code'] =$det[0]['country_code'];
     echo json_encode($result);

    }

    public function add_doctor()
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