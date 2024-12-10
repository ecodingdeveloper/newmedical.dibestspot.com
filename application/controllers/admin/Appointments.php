<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appointments extends CI_Controller {

   public $data;
   public $session;
   public $timezone;
   public $appointments;

   public function __construct() {

        parent::__construct();

        if($this->session->userdata('admin_id') ==''){
            redirect(base_url().'admin/login');
        }
        
        $this->data['theme']     = 'admin';
        $this->data['module']    = 'appointments';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();

         $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }
        
        $this->load->model('appoinments_model','appointments');
        

    }


	public function index()
	{
	    $this->data['page'] = 'index';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
	   
	}

  public function appoinments_list()
   {
      $list = $this->appointments->get_appoinments_datatables();
      $data = array();
      $no = $_POST['start'];
      $a=1;
       
      foreach ($list as $appoinments) { 

         $val='';

        if($appoinments['appointment_status'] == '1')
        {
            $val = 'checked';
        }

        $doctor_profileimage=(!empty($appoinments['doctor_profileimage']) && file_exists(FCPATH.$appoinments['doctor_profileimage']))?base_url().$appoinments['doctor_profileimage']:base_url().'assets/img/user.png';
        $patient_profileimage=(!empty($appoinments['patient_profileimage']) && file_exists(FCPATH.$appoinments['patient_profileimage']))?base_url().$appoinments['patient_profileimage']:base_url().'assets/img/user.png';
        $no++;
        $row = array();
        $row[] = $no;
        // $row[] = '<h2 class="table-avatar">
        //           <a target="_blank" href="'.base_url().'doctor-preview/'.$appoinments['doctor_username'].'" class="avatar avatar-sm mr-2">
        //             <img class="avatar-img rounded-circle" src="'.$doctor_profileimage.'" alt="User Image">
        //           </a>
        //           <a target="_blank" href="'.base_url().'doctor-preview/'.$appoinments['doctor_username'].'">Dr. '.ucfirst($appoinments['doctor_name']).' <span>'.ucfirst($appoinments['doctor_specialization']).'</span></a>
        //         </h2>';


        if($appoinments['hospital_id']!="")
        {
        $row[] = '<h2 class="table-avatar">
                  <a href="'.base_url().'doctor-preview/'.$appoinments['clinic_username'].'">'.ucfirst($appoinments['clinic_first_name'].' '.$appoinments['clinic_last_name']).' </a>
                 </h2>
                  ';
        }
        else
        {
        

        if($appoinments['role']==1)
        {
          $value=$this->language['lg_dr'];
          $img='<a href="'.base_url().'doctor-preview/'.$appoinments['doctor_username'].'" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="'.$doctor_profileimage.'" alt="User Image">
                  </a>';
          $specialization=ucfirst($appoinments['doctor_specialization']);
        }
        else
        {
          $value="";
          $img="";
          $specialization="";
        }

        $row[] = '<h2 class="table-avatar">
                  '.$img.'
                  <a href="'.base_url().'doctor-preview/'.$appoinments['doctor_username'].'">'.$value.' '.ucfirst($appoinments['doctor_name'].' ').' <span>'.$specialization.'</span></a>
                </h2>
                  ';

        }

        $row[] = '<h2 class="table-avatar">
                  <a target="_blank" href="'.base_url().'patient-preview/'.base64_encode($appoinments['appointment_from']).'" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="'.$patient_profileimage.'" alt="User Image"></a>
                  <a target="_blank" href="'.base_url().'patient-preview/'.base64_encode($appoinments['appointment_from']).'">'.ucfirst($appoinments['patient_name']).' </a>
                </h2>';
         $from_date_time = '';
        if(!empty($appoinments['time_zone'])){
          $from_timezone=$appoinments['time_zone'];
          $to_timezone = date_default_timezone_get();
          $from_date_time = $appoinments['from_date_time'];
          $from_date_time = converToTz($from_date_time,$to_timezone,$from_timezone);
          $row[] = date('d M Y',strtotime($from_date_time)).' <span class="d-block text-info">'.date('h:i A',strtotime($from_date_time)).'</span>';
        }else{
          $row[]='-';
       } 
        $row[] = date('d M Y',strtotime($appoinments['created_date']));
        $row[] = ucfirst($appoinments['type']);
        $row[] = '<div class="status-toggle">
                      <input type="checkbox" disabled  id="status_'.$appoinments['id'].'" class="check" '.$val.'>
                      <label for="status_'.$appoinments['id'].'" class="checktoggle">checkbox</label>
                    </div>';
      $org_amount=0;
      if($appoinments['total_amount'])
      {
      $org_amount=get_doccure_currency($appoinments['total_amount'],$appoinments['currency_code'],default_currency_code());
      }

       $row[] = default_currency_symbol().' '.number_format($org_amount,2,'.',',');
        
        $data[] = $row;
      }



      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->appointments->appoinments_count_all(1),
              "recordsFiltered" => $this->appointments->appoinments_count_filtered(1),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }



  public function upappoinments_list()
   {
      $list = $this->appointments->get_upappoinments_datatables();
      $data = array();
      $no = $_POST['start'];
      $a=1;
       
      foreach ($list as $appoinments) {
         $val='';

        if($appoinments['appointment_status'] == '0')
        {
            $val = 'checked';
        }

        $doctor_profileimage=(!empty($appoinments['doctor_profileimage']) && file_exists(FCPATH.$appoinments['doctor_profileimage']))?base_url().$appoinments['doctor_profileimage']:base_url().'assets/img/user.png';
        $patient_profileimage=(!empty($appoinments['patient_profileimage']) && file_exists(FCPATH.$appoinments['patient_profileimage']))?base_url().$appoinments['patient_profileimage']:base_url().'assets/img/user.png';
        $no++;
        $row = array();
        $row[] = $a++;

        if($appoinments['hospital_id']!="")
        {
        $row[] = '<h2 class="table-avatar">
                  <a href="'.base_url().'doctor-preview/'.$appoinments['clinic_username'].'">'.ucfirst($appoinments['clinic_first_name'].' '.$appoinments['clinic_last_name']).' </a>
                 </h2>
                  ';
        }
        else
        {
        

        if($appoinments['role']==1)
        {
          $value=$this->language['lg_dr'];
          $img='<a href="'.base_url().'doctor-preview/'.$appoinments['doctor_username'].'" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="'.$doctor_profileimage.'" alt="User Image">
                  </a>';
          $specialization=ucfirst($appoinments['doctor_specialization']);
        }
        else
        {
          $value="";
          $img='<a href="#" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="'.$doctor_profileimage.'" alt="User Image">
                  </a>';
          $specialization="";
        }

        $row[] = '<h2 class="table-avatar">
                  '.$img.'
                  <a href="'.base_url().'doctor-preview/'.$appoinments['doctor_username'].'">'.$value.' '.ucfirst($appoinments['doctor_name'].' ').' <span>'.$specialization.'</span></a>
                </h2>
                  ';

        }

        $row[] = '<h2 class="table-avatar">
                  <a target="_blank" href="'.base_url().'patient-preview/'.base64_encode($appoinments['appointment_from']).'" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="'.$patient_profileimage.'" alt="User Image"></a>
                  <a target="_blank" href="'.base_url().'patient-preview/'.base64_encode($appoinments['appointment_from']).'">'.ucfirst($appoinments['patient_name']).' </a>
                </h2>';
         $from_date_time = '';
        if(!empty($appoinments['time_zone'])){
          $from_timezone=$appoinments['time_zone'];
          $to_timezone = date_default_timezone_get();
          $from_date_time = $appoinments['from_date_time'];
          $from_date_time = converToTz($from_date_time,$to_timezone,$from_timezone);
          $row[] = date('d M Y',strtotime($from_date_time)).' <span class="d-block text-info">'.date('h:i A',strtotime($from_date_time)).'</span>';
        }else{
          $row[]='-';
       } 
        $row[] = date('d M Y',strtotime($appoinments['created_date']));
        $row[] = ucfirst($appoinments['type']);
        $row[] = '<div class="status-toggle">
                      <input type="checkbox" disabled  id="status_'.$appoinments['id'].'" class="check" '.$val.'>
                      <label for="status_'.$appoinments['id'].'" class="checktoggle">checkbox</label>
                    </div>';
      $org_amount=0;
      if($appoinments['total_amount'])
      {
      $org_amount=get_doccure_currency($appoinments['total_amount'],$appoinments['currency_code'],default_currency_code());
      }

       $row[] = default_currency_symbol().' '.number_format($org_amount,2,'.',',');
        
        $data[] = $row;
      }



      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->appointments->appoinments_count_all(2),
              "recordsFiltered" => $this->appointments->appoinments_count_filtered(2),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }


  public function missedappoinments_list()
   {
      $list = $this->appointments->get_missedappoinments_datatables();
      $data = array();
      $no = $_POST['start'];
      $a=1;
       
      foreach ($list as $appoinments) {

         $val='';

        if($appoinments['appointment_status'] == '0')
        {
            $val = 'checked';
        }

        $doctor_profileimage=(!empty($appoinments['doctor_profileimage']) && file_exists(FCPATH.$appoinments['doctor_profileimage']))?base_url().$appoinments['doctor_profileimage']:base_url().'assets/img/user.png';
        $patient_profileimage=(!empty($appoinments['patient_profileimage']) && file_exists(FCPATH.$appoinments['patient_profileimage']))?base_url().$appoinments['patient_profileimage']:base_url().'assets/img/user.png';
        $no++;
        $row = array();
        $row[] = $no;
        // $row[] = '<h2 class="table-avatar">
        //           <a target="_blank" href="'.base_url().'doctor-preview/'.$appoinments['doctor_username'].'" class="avatar avatar-sm mr-2">
        //             <img class="avatar-img rounded-circle" src="'.$doctor_profileimage.'" alt="User Image">
        //           </a>
        //           <a target="_blank" href="'.base_url().'doctor-preview/'.$appoinments['doctor_username'].'">Dr. '.ucfirst($appoinments['doctor_name']).' <span>'.ucfirst($appoinments['doctor_specialization']).'</span></a>
        //         </h2>';


        if($appoinments['hospital_id']!="")
        {
        $row[] = '<h2 class="table-avatar">
                  <a href="'.base_url().'doctor-preview/'.$appoinments['clinic_username'].'">'.ucfirst($appoinments['clinic_first_name'].' '.$appoinments['clinic_last_name']).' </a>
                 </h2>
                  ';
        }
        else
        {
        

        if($appoinments['role']==1)
        {
          $value=$this->language['lg_dr'];
          $img='<a href="'.base_url().'doctor-preview/'.$appoinments['doctor_username'].'" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="'.$doctor_profileimage.'" alt="User Image">
                  </a>';
          $specialization=ucfirst($appoinments['doctor_specialization']);
        }
        else
        {
          $value="";
          $img="";
          $specialization="";
        }

        $row[] = '<h2 class="table-avatar">
                  '.$img.'
                  <a href="'.base_url().'doctor-preview/'.$appoinments['doctor_username'].'">'.$value.' '.ucfirst($appoinments['doctor_name'].' ').' <span>'.$specialization.'</span></a>
                </h2>
                  ';

        }
        
        $row[] = '<h2 class="table-avatar">
                  <a target="_blank" href="'.base_url().'patient-preview/'.base64_encode($appoinments['appointment_from']).'" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="'.$patient_profileimage.'" alt="User Image"></a>
                  <a target="_blank" href="'.base_url().'patient-preview/'.base64_encode($appoinments['appointment_from']).'">'.ucfirst($appoinments['patient_name']).' </a>
                </h2>';
         $from_date_time = '';
        if(!empty($appoinments['time_zone'])){
          $from_timezone=$appoinments['time_zone'];
          $to_timezone = date_default_timezone_get();
          $from_date_time = $appoinments['from_date_time'];
          $from_date_time = converToTz($from_date_time,$to_timezone,$from_timezone);
          $row[] = date('d M Y',strtotime($from_date_time)).' <span class="d-block text-info">'.date('h:i A',strtotime($from_date_time)).'</span>';
        }else{
          $row[]='-';
       } 
        $row[] = date('d M Y',strtotime($appoinments['created_date']));
        $row[] = ucfirst($appoinments['type']);
        $row[] = '<div class="status-toggle">
                      <input type="checkbox" disabled  id="status_'.$appoinments['id'].'" class="check" '.$val.'>
                      <label for="status_'.$appoinments['id'].'" class="checktoggle">checkbox</label>
                    </div>';
      $org_amount=0;
      if($appoinments['total_amount'])
      {
      $org_amount=get_doccure_currency($appoinments['total_amount'],$appoinments['currency_code'],default_currency_code());
      }

       $row[] = default_currency_symbol().' '.number_format($org_amount,2,'.',',');
        
        $data[] = $row;
      }



      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->appointments->appoinments_count_all(3),
              "recordsFiltered" => $this->appointments->appoinments_count_filtered(3),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }


}
