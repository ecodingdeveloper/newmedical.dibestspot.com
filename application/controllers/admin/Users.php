<?php
defined('BASEPATH') OR exit('No direct script access allowed');

											
		

   
class Users extends CI_Controller {

   public $data;
   public $session;
   public $timezone;
   public $input;
   public $db;
   public $users;
   public $signin;
   

   public function __construct() {

        parent::__construct();

        if($this->session->userdata('admin_id') ==''){
            redirect(base_url().'admin/login');
        }

        $this->data['theme']     = 'admin';
        $this->data['module']    = 'users';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
         $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }
        $lan=default_language();
        $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):strtolower($lan['language']);
        $this->data['language'] = $this->lang->load('content', $lang, true);
        $this->language = $this->lang->load('content', $lang, true);
        $this->load->model('users_model','users');
        $this->load->model('signin_model','signin');
              $this->load->model('commission_model', 'commission');
              $this->load->model('commission_phar_model', 'commission_phar');
              $this->load->model('commission_clinic_model', 'commission_clinic');
              $this->load->model('commission_lab_model', 'commission_lab');
              $this->load->model('commission_pat_model', 'commission_pat');
              $this->load->model('packages_model','packages');
              $this->load->model('services_model','services');
              $this->load->model('pricing_plan_model','pricing');

    }


	public function doctors()
	{
	    $this->data['page'] = 'doctors';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
	   
	}

  public function doctors_list()
   {
	/*   $servername = "localhost";
$username = "kyprod_med";
$password = "DbOEsonTM0H^";
$database = "kyprod_medical";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}*/

      $list = $this->users->get_doctor_datatables();
      $data = array();
      $no = $_POST['start'];
      $a=1;
       
      foreach ($list as $doctor) {

        $val='';

      if($doctor['status'] == '1')
        {
          $val = 'checked';
        }

        $profileimage=(!empty($doctor['profileimage']))?base_url().$doctor['profileimage']:base_url().'assets/img/user.png';
        
        if($doctor['hospital_id']==0)
        {
          $clinic_doctor='';
        }
        else
        {
          $clinic_doctor='Clinic Doctor';
        }
       /* $sql="SELECT * from commission Where doc_id=$doctor[id]";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$res= $row['comm_rate'];*/
		$result = $this->commission->get_commission_by_doc_id($doctor['id']);
        $res=$result->comm_rate;
        
       // echo "<pre>";
       // print_r($res);
        //die;
	
        $no++;
        $row = array();
        $row[] = $no;
        $row[] ='#D00'.$doctor['id'];
       $row[] = '<h2 class="table-avatar">
                    <a target="_blank" href="'.base_url().'doctor-preview/'.$doctor['username'].'" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="'.$profileimage.'" alt="User Image"></a>
                    <a target="_blank" href="'.base_url().'doctor-preview/'.$doctor['username'].'">Dr. '.ucfirst($doctor['first_name'].' '.$doctor['last_name']).' <span>'.$clinic_doctor.'</span></a>


                  </h2>';
        $row[] = ucfirst($doctor['specialization']);  
        $row[] = $doctor['email'];
        $row[] = $doctor['mobileno'];       
        $row[] = date('d M Y',strtotime($doctor['created_date'])).'<br><small>'.date('h:i A',strtotime($doctor['created_date'])).'</small>';
        $row[] = get_earned($doctor['id']);
		//echo $doctor['id'];
		//die("asd");
		$row[] = '
		<form action="#" method=post>
		<input id="commission_input_' . $doctor['id'] . '" type="text" placeholder="Enter commission" value="'.$res.'" name="commission">
          <button onclick="updateCommission('.$doctor['id'].')">Update</button>
		</form>';
        $row[] = '<div class="status-toggle">
                      <input type="checkbox" onchange="change_usersStatus('.$doctor['id'].')" id="status_'.$doctor['id'].'" class="check" '.$val.'>
                      <label for="status_'.$doctor['id'].'" class="checktoggle">checkbox</label>
                    </div>';
        $row[] = '<div class="actions">
                    <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_doctor('.$doctor['id'].')">
                      <i class="fe fe-trash"></i> Delete
                    </a>
                  </div>';
        
        $data[] = $row;
      }



      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->users->doctor_count_all(),
              "recordsFiltered" => $this->users->doctor_count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }



  public function patients()
  {
      $this->data['page'] = 'patients';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
     
  }

  public function patients_list()
   {
      $list = $this->users->get_patient_datatables();
      $data = array();
      $no = $_POST['start'];
      $a=1;
       
      foreach ($list as $patient) {

        $val='';

      if($patient['status'] == '1')
        {
          $val = 'checked';
        }

        $result = $this->commission_pat->get_commission_by_pat_id($patient['id']);
        $res=$result->comm_rate;

        $profileimage=(!empty($patient['profileimage']))?base_url().$patient['profileimage']:base_url().'assets/img/user.png';
        
        $no++;
        $row = array();
        $row[] = $no;
        $row[] ='#PT00'.$patient['id'];
        $row[] = '<h2 class="table-avatar">
                    <a target="_blank" href="'.base_url().'patient-preview/'.base64_encode($patient['id']).'" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="'.$profileimage.'" alt="User Image"></a>
                    <a target="_blank" href="'.base_url().'patient-preview/'.base64_encode($patient['id']).'">'.ucfirst($patient['first_name'].' '.$patient['last_name']).'</a>
                  </h2>';
        $row[] = (!empty($patient['dob']))?age_calculate($patient['dob']):'';
        $row[] = $patient['blood_group']; 
        $row[] = $patient['email'];
        $row[] = $patient['mobileno'];      
        $row[] = date('d M Y',strtotime($patient['created_date'])).'<br><small>'.date('h:i A',strtotime($patient['created_date'])).'</small>';
        $row[] = '
		<form action="#" method=post>
		<input id="commission_input_' . $patient['id'] . '" type="text" placeholder="Enter commission" value="'.$res.'" name="commission">
          <button onclick="updateCommissionPat('.$patient['id'].')">Update</button>
		</form>';
        $row[] = '<div class="status-toggle">
                      <input type="checkbox" onchange="change_usersStatus('.$patient['id'].')" id="status_'.$patient['id'].'" class="check" '.$val.'>
                      <label for="status_'.$patient['id'].'" class="checktoggle">checkbox</label>
                    </div>';

         if(isset($patient['last_vist'])){ 

          $row[] = date('d M Y',strtotime($patient['last_vist'])); 

        }else{
          $row[]="";
        }

        $org_amount=0;
        $currency_option = default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);
                          if($patient['last_paid']){
                          $org_amount=get_doccure_currency($patient['last_paid'],$patient['currency_code'],default_currency_code());
                        }

        $row[] =$rate_symbol. $org_amount;
        $row[] = '<div class="actions">
        <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_patient('.$patient['id'].')">
          <i class="fe fe-trash"></i> Delete
        </a>
      </div>';

        
        $data[] = $row;
      }



      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->users->patient_count_all(),
              "recordsFiltered" => $this->users->patient_count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }


  public function change_usersStatus()
    {

      $id=$this->input->post('id');
      $status=$this->input->post('status');

       $data = array(
                'status' =>$status,
            );
        $this->users->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
    }


    public function check_email()
  {
        $email = $this->input->post('email');     
        $result = $this->signin->check_email($email);
         if ($result > 0) {
                   echo 'false';
           } else {
                   echo 'true';
           }
           
  }
public function check_mobileno()
  {
        $mobileno = $this->input->post('mobileno');  
        $result="";   
        $result = $this->signin->check_mobileno($mobileno);
         if ($result > 0) {
                   echo 'false';
           } else {
                   echo 'true';
           }
           
  }


public function signup()
  {
    $inputdata=array();
    $response=array();
    $result="";
      
      //print_r($_POST);  exit;

      $inputdata['first_name']=$this->input->post('first_name');
      $inputdata['last_name']=$this->input->post('last_name');
      $inputdata['email']=$this->input->post('email');
      $inputdata['mobileno']=$this->input->post('mobileno');
      $inputdata['country_code']=$this->input->post('country_code');
      $inputdata['username'] = generate_username($inputdata['first_name'].' '.$inputdata['last_name'].' '.$inputdata['mobileno']);
      $inputdata['role']=$this->input->post('role');
      $inputdata['password']=md5($this->input->post('password'));
      $inputdata['confirm_password']=md5($this->input->post('confirm_password'));
      $inputdata['created_date']=date('Y-m-d H:i:s');
      $inputdata['is_verified']=1;
      if($inputdata['role']==6){
        $inputdata['first_name']= $this->input->post('clinic_name'); 
        $inputdata['last_name']= ' ';
      }

      /*if($this->input->post('pharmacy_name'))
        $inputdata['pharmacy_name'] = $this->input->post('pharmacy_name');*/

      $already_exits=$this->db->where('email',$inputdata['email'])->get('users')->num_rows();
      $already_exits_mobile_no=$this->db->where('mobileno',$inputdata['mobileno'])->get('users')->num_rows();
  
      if($already_exits >=1)
      {
              $response['msg']='Email already exits';
              $response['status']=500;
      }
      else if($already_exits_mobile_no >=1)
      {
              $response['msg']='Mobile no already exits';
              $response['status']=500;
      }
      else
      {
          $new=0;  $newUserID=0;  
          if($inputdata['role'] == 5) 
          {
            $inputdata['pharmacy_user_type'] = 1;
            
            $get_pharmacy_details = $this->db->select('*')->from('users')->where('pharmacy_user_type',1)->get()->result_array();
            
            if(isset($get_pharmacy_details) && !empty($get_pharmacy_details)){
               // print_r($get_pharmacy_details); exit;
              $phar_id = $get_pharmacy_details[0]['id'];
              $this->db->update('users',$inputdata,array('id'=>$phar_id));

            }else{
              $result=$this->signin->signup($inputdata);
              $new=1;
              $newUserID=$this->db->insert_id();
            }


          }else{

            // Without Pharmacy
            //echo "New";
            $result=$this->signin->signup($inputdata);
            $new=1;
            $newUserID=$this->db->insert_id();
            
          }

          
          

          if($inputdata['role'] == 5) 
          {
            $pharmacy_id   = $this->db->insert_id();
            $home_delivery = $this->input->post('home_delivery');
            $pharmacy_opens_at = $this->input->post('pharmacy_opens_at');
            $hrsopen = $this->input->post('hrsopen');
            $pharmacydata = array(
                'home_delivery' => (!empty($home_delivery))? $home_delivery : 'no',
                'pharamcy_opens_at' => (!empty($pharmacy_opens_at)) ? $pharmacy_opens_at : '00:00:00',
                '24hrsopen' => (!empty($hrsopen)) ? $hrsopen : 'no',
                'pharmacy_id' => $pharmacy_id
            );
            // insert query
            $this->db->insert('pharmacy_specifications',$pharmacydata);   
          }

          if($result==true)
          {   
               $response['msg']='Registration success'; 
               $response['status']=200;  
               if($new==1){
                  //Admin send welcome message for new user
                  $data['recieved_id'] =$newUserID;
                  $data['sent_id'] = 1;
                  $data['is_admin'] = 1;
                  $data['time_zone'] = $this->session->userdata('time_zone');
                  $data['chatdate'] = date('Y-m-d H:i:s');
                  $sitename = (!empty(settings("website_name")))?settings("website_name"):"Dibest Spot";
                  $welcome = 'Welcome to DiBest Spot Medical—your trusted healthcare partner!<br><br>
                  Dear Member,<br><br>
                  To schedule appointments, purchase medications, pay your invoice,  billing queries or to ask any questions, reach out to Admin here via this chat.<br><br>
                  <span style="text-decoration-line: underline;">STEP1:</span>  Contact Admin to Book Your  “FREE Medical Consultation” with the <strong>DiBest Spot:(GTC)-General Telehealth Clinic</strong> to get started!<br><br>
                  Best Regards,<br>
                  DiBest Spot Medical<br><br>
                  PS… Check out our marketplace for other services and products at <a target="_blank" href="https://www.dibestspot.com">https://www.dibestspot.com</a>';
                  $data['msg']  = $welcome;
                  
                  $result = $this->db->insert('chat',$data);
                  $chat_id = $this->db->insert_id();
                  $users = array($data['recieved_id'],$data['sent_id']);
                  for ($i=0; $i <2 ; $i++) { 
                    $datas = array('chat_id' =>$chat_id ,'can_view'=>$users[$i]);
                    $this->db->insert('chat_deleted_details',$datas);
                  }
               }            
          }
         else
          {
               $response['msg']='Registration failed';
              $response['status']=500; 
          } 

      }
      
    echo json_encode($response);
  }

/*
public function signup()
  {
    $inputdata=array();
    $response=array();
      
      $inputdata['first_name']=$this->input->post('first_name');
      $inputdata['last_name']=$this->input->post('last_name');
      $inputdata['email']=$this->input->post('email');
      $inputdata['mobileno']=$this->input->post('mobileno');
      $inputdata['country_code']=$this->input->post('country_code');
      $inputdata['username'] = generate_username($inputdata['first_name'].' '.$inputdata['last_name'].' '.$inputdata['mobileno']);
      $inputdata['role']=$this->input->post('role');
      $inputdata['password']=md5($this->input->post('password'));
      $inputdata['confirm_password']=md5($this->input->post('confirm_password'));
      $inputdata['created_date']=date('Y-m-d H:i:s');
      $inputdata['is_verified']=1;
      $already_exits=$this->db->where('email',$inputdata['email'])->get('users')->num_rows();
      $already_exits_mobile_no=$this->db->where('mobileno',$inputdata['mobileno'])->get('users')->num_rows();
  
      if($already_exits >=1)
      {
              $response['msg']='Email already exits';
              $response['status']=500;
      }
      else if($already_exits_mobile_no >=1)
      {
              $response['msg']='Mobile no already exits';
              $response['status']=500;
      }
      else
      {
          $result=$this->signin->signup($inputdata);
          if($result==true)
          {   
               $response['msg']='Registration success'; 
               $response['status']=200;              
          }
         else
          {
               $response['msg']='Registration failed';
              $response['status']=500; 
          } 

      }
      
    echo json_encode($response);
  }  */


  public function pharmacies() {
        
    $this->data['get_pharmacy_details'] = $this->db->select('*')->from('users')->where('pharmacy_user_type',1)->get()->result_array();
    $admin_pharmacy = $this->db->select('id')->get_where('users',array('pharmacy_user_type'=>1))->row_array();
    $this->data['specification'] = $this->db->get_where('pharmacy_specifications',array('pharmacy_id'=>$admin_pharmacy['id']))->row_array();
    $this->data['page'] = 'pharmacies';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');

  }
  
  public function pharmacies_list()
  {
/*$servername = "localhost";
$username = "kyprod_med";
$password = "DbOEsonTM0H^";
$database = "kyprod_medical";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}*/
      $list = $this->users->get_pharmacy_datatables();


      $data = array();
      $no = $_POST['start'];
      $a=1;
       
      foreach ($list as $pharmacy) {

        $val='';

      if($pharmacy['status'] == '1')
        {
          $val = 'checked';
        }
        /*$sql="SELECT * from commission_phar Where phar_id=$pharmacy[id]";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$res= $row['comm_rate'];*/
		
		$result = $this->commission_phar->get_commission_by_phar_id($pharmacy['id']);
        $res=$result->comm_rate;
		
        $pharmacy_name = ($pharmacy['pharmacy_name'] != '') ? ucfirst($pharmacy['pharmacy_name']) : ucfirst($pharmacy['first_name']).' '.$pharmacy['last_name'];
        $profileimage=(!empty($pharmacy['profileimage']))?base_url().$pharmacy['profileimage']:base_url().'assets/img/user.png';
        $pharmacy_id = $pharmacy['id'];
        $no++;
        $row = array();
        $row[] = $no;
        $row[] = '<h2 class="table-avatar">
                    <a href="javascript:;" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="'.$profileimage.'" alt="User Image"></a>
                    <a href="javascript:;">'.$pharmacy_name.'</a>
                  </h2>';
        $row[] = $pharmacy['email'];  
        $row[] = $pharmacy['mobileno']; 
        $row[] = ($pharmacy['home_delivery'] != '') ? ucfirst($pharmacy['home_delivery']) : 'N/A'; 
        $row[] = ($pharmacy['hrs_open'] != '') ? ucfirst($pharmacy['hrs_open']) : 'N/A'; 
		$row[] = '<form action="#" method=post>
		<input id="commission_input_' . $pharmacy['id'] . '" type="text" placeholder="Enter commission" value="'.$res.'" name="commission">
          <button onclick="updateCommissionPhar('.$pharmacy['id'].')">Update</button>
		</form>';
        $row[] = ($pharmacy['pharamcy_opens_at'] != '') ? date('h:i A',strtotime($pharmacy['pharamcy_opens_at'])) : 'N/A'; 
        $row[] = date('d M Y',strtotime($pharmacy['created_date'])).'<br><small>'.date('h:i A',strtotime($pharmacy['created_date'])).'</small>';
        $row[] = '<div class="status-toggle">
                      <input type="checkbox" onchange="change_usersStatus('.$pharmacy['id'].')" id="status_'.$pharmacy['id'].'" class="check" '.$val.'>
                      <label for="status_'.$pharmacy['id'].'" class="checktoggle">checkbox</label>
                        
                    </div>';
                    $row[] = '<div class="actions">
                    <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_pharmacy('.$pharmacy['id'].')">
                      <i class="fe fe-trash"></i> Delete
                    </a>
                  </div>';
        
        $data[] = $row;
      }



      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->users->pharmacy_count_all(),
              "recordsFiltered" => $this->users->pharmacy_count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }
  
  public function pharmacy_edit($id) {
        $result = $this->users->get_selected_pharmacy_details($id);
        echo json_encode($result);
  }

  public function update_pharmacy() {
      
      //print_r($_POST);  exit;
      $response['status']=500;
        
      // echo "<pre>"; print_r($_POST); exit;
      $pharmacy_id = $this->input->post('pharmacy_id');
      $updatedata = array(
                        'first_name'  => $this->input->post('first_name'),
                        'last_name'  => $this->input->post('last_name'),
                        //'pharmacy_name' => $this->input->post('pharmacy_name'),
                        'email' => $this->input->post('email'),
                        'mobileno' => $this->input->post('mobileno'),
      );
      
        $this->db->where('id',$pharmacy_id);
        $this->db->update('users', $updatedata);
		$UpdateAfftectedRows=$this->db->affected_rows();  
		if($UpdateAfftectedRows>0) {
			$this->session->set_flashdata('success_message','Admin pharmacy updated successfully');
		}
		else{
			$this->session->set_flashdata('error_message','Edit Required!');
		}		
        $pharmacydata = array();

        if(isset($_POST['home_delivery']) && $_POST['home_delivery']!=''){
          $pharmacydata['home_delivery']=$this->input->post('home_delivery');
          $pharmacydata['pharmacy_id']=$pharmacy_id;      
        }

        if(isset($_POST['hrsopen']) && $_POST['hrsopen']!=''){
          $pharmacydata['24hrsopen']=$this->input->post('hrsopen');
          $pharmacydata['pharmacy_id']=$pharmacy_id;    
        }

        if(isset($_POST['pharmacy_opens_at']) && $_POST['pharmacy_opens_at']!=''){
          $pharmacydata['pharamcy_opens_at']=$this->input->post('pharmacy_opens_at');
          $pharmacydata['pharmacy_id']=$pharmacy_id;       
        }
        
        // save or update the pharmacy specifications..
        $already_exits_pharmacy_specifications=$this->db->where('pharmacy_id',$pharmacy_id)->get('pharmacy_specifications')->num_rows();
        if($already_exits_pharmacy_specifications >=1)
        {
            // update query
            $select_qry = $this->db->where('pharmacy_id',$pharmacy_id)->get('pharmacy_specifications')->row_array();
            if(!empty($select_qry)) {
                $phar_spec_id = $select_qry['id'];
                $this->db->where('id', $phar_spec_id);
                $result=$this->db->update('pharmacy_specifications',$pharmacydata);
                $response['status']=200;
            }
            else {
                // insert query
                $this->db->insert('pharmacy_specifications',$pharmacydata);  
                $response['status']=200;
            }
            
        } else {
            // insert query
            $this->db->insert('pharmacy_specifications',$pharmacydata);   
            $response['status']=200;
        }
        
          
        redirect('admin/users/pharmacies');
        //echo json_encode($response);
  }

  public function lab_list_data() {
	  
	  /*$servername = "localhost";
$username = "kyprod_med";
$password = "DbOEsonTM0H^";
$database = "kyprod_medical";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}*/
	  
    $list = $this->users->get_lab_datatables();
      $data = array();
      $no = $_POST['start'];
      $a=1;
       
      foreach ($list as $lab) {

        $val='';

      if($lab['status'] == '1')
        {
          $val = 'checked';
        }
		
		/* $sql="SELECT * from commission_lab Where lab_id=$lab[id]";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$res= $row['comm_rate'];*/
			
    $result = $this->commission_lab->get_commission_by_lab_id($lab['id']);
    $res=$result->comm_rate;

        $profileimage=(!empty($lab['profileimage']))?base_url().$lab['profileimage']:base_url().'assets/img/user.png';
        
        $no++;
        $row = array();
        $row[] = $no;
        $row[] ='#L00'.$lab['id'];
        $row[] = '<h2 class="table-avatar">
                    <span class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="'.$profileimage.'" alt="User Image"></span>
                    '.ucfirst($lab['first_name'].' '.$lab['last_name']).'
                  </h2>';
        $row[] = $lab['email'];
        $row[] = $lab['mobileno'];       
        $row[] = date('d M Y',strtotime($lab['created_date'])).'<br><small>'.date('h:i A',strtotime($lab['created_date'])).'</small>';
		$row[] = '<form action="#" method=post>
		<input id="commission_input_' . $lab['id'] . '" type="text" placeholder="Enter commission" value="'.$res.'" name="commission">
          <button onclick="updateCommissionLab('.$lab['id'].')">Update</button>
		</form>';
        $row[] = '<div class="status-toggle">
                      <input type="checkbox" onchange="change_usersStatus('.$lab['id'].')" id="status_'.$lab['id'].'" class="check" '.$val.'>
                      <label for="status_'.$lab['id'].'" class="checktoggle">checkbox</label>
                    </div>';
        
                    $row[] = '<div class="actions">
                    <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_lab('.$lab['id'].')">
                      <i class="fe fe-trash"></i> Delete
                    </a>
                  </div>';
        $data[] = $row;
      }



      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->users->lab_count_all(),
              "recordsFiltered" => $this->users->lab_count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }

  public function labs()
{
    $this->data['page'] = 'labs';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');
   
}

/*Labtest Booked*/
public function labtest_booked()
{
    $this->data['page'] = 'labtest_booked';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');
   
}
public function booked_labtest_list_data() { 
    $list = $this->users->get_booked_labtest_datatables();
//print_r($list);
      $data = array();
      $no = $_POST['start'];
      $a=1;
       
      foreach ($list as $lab) { 

      
  $currency_option = default_currency_code();
      $rate_symbol = currency_code_sign($currency_option);

       $pay_amount=get_doccure_currency($lab['total_amount'],$lab['currency_code'],default_currency_code());
   $testname = $this->users->get_lab_testname($lab['booking_ids']);
        
        $no++;
        $row = array();
        $row[] = $no;
    $row[] = $lab['order_id'];
        $row[] = $lab['patient_firstname']." ".$lab['patient_lastname'];
    $row[] = $lab['first_name']." ".$lab['last_name'];
    $row[] = ($testname)?$testname:'---';		 
    $row[] = date('d M Y',strtotime($lab['lab_test_date']));
    $row[] = $rate_symbol.number_format($pay_amount,2);
    $row[] = '<span class="badge badge-success">'.($lab['payment_type']).'</span>';
   
        $data[] = $row;
      }



      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->users->booked_labtest_count_all(),
              "recordsFiltered" => $this->users->booked_labtest_count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }
/*Labtest Booked*/

// migrating clinic module
public function pricing_plan()
  {
      $this->data['page'] = 'pricing_plan';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
     
  }

  public function pricing_list()
{


  $list = $this->pricing->get_datatables();
  // echo '<pre>';
  // print_r($list);
  // echo '</pre>';
  // die("dsfdf");
  $data = array();
  $no = $_POST['start'];
  $a=1;
   
  foreach ($list as $pricing) 
  {
  // echo '<pre>';
  // print_r($packages);
  // echo '</pre>';
  // die("dsfdf");
    $no++;
    $row = array();
    // $row[] = $no;
        // $row[] ='#PI00'.$pricing['plan_id'];
        $row[] = $pricing['feature_text'];
        $row[] = $pricing['role_1'];
        $row[] = $pricing['role_6'];   
        $row[] = $pricing['role_0'];
        $row[] = $pricing['role_2'];
        $row[] = $pricing['role_5'];
        $row[] = $pricing['role_4'];
        $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light" onclick="edit_pricing_admin('.$pricing['plan_id'].')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_pricing_admin('.$pricing['plan_id'].')">
                    <i class="fe fe-trash"></i> Delete
                  </a>
                </div>';
        
        $data[] = $row;
  }

  $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->pricing->count_all(),
          "recordsFiltered" => $this->pricing->count_filtered(),
          "data" => $data,
      );
  //output to json format
  echo json_encode($output);
}

  public function create_pricing_plan(){

  
    // $folder = base_url().'uploads/package_image/';
    $method = $this->input->post('method');
    // echo $method;
    // die("dfesryas");
    $data['feature_text']  = $this->input->post('plan_features');
  //  $data["package_image"] = $this->input->post('package_img');
    $data["role_1"] = $this->input->post('doctor_plan');
    $data["role_6"] = $this->input->post('clinic_plan');
    $data["role_0"] = $this->input->post('gtc_plan');
    $data["role_2"] = $this->input->post('patient_plan');
    $data["role_4"] = $this->input->post('lab_plan');
    $data["role_5"] = $this->input->post('pharmacy_plan');

  //   $roles_features = array(
  //     "plan_features" => $data['plan_features'],
  //     "doctor_plan" => $data["doctor_plan"],
  //     "clinic_plan" =>  $data["clinic_plan"],
  //     "gtc_plan" => $data["gtc_plan"],
  //     "patient_plan" =>  $data["patient_plan"],
  //     "lab_plan" => $data["lab_plan"],
  //     "pharmacy_plan" =>$data["pharmacy_plan"]
  // );
 

// Encode the updated features back to JSON
// $updated_features_json = json_encode($roles_features);

  //   echo '<pre>';
  //   print_r($data);
  //   echo '</pre>';
  //  die("asdd");
    

      if($method=='update')
      {
        

          $id = $this->input->post('id');
          // echo $id;
          // die("inside update");
          $this->db->where('plan_id',$id);
          $this->db->update('pricingplan', $data);
          $result = ($this->db->affected_rows()!= 1) ? false:true;

          $datas['result'] = 'true';
          $datas['status'] = 'Pricing Plan updated successfully';

             
      }
      else
      {
            
            $this->db->insert('pricingplan',$data);
          

          $result=($this->db->affected_rows()!= 1)? false:true;

          if(@$result==true) 
           {
              $datas['result'] = 'true';
              $datas['status'] = 'Pricing Plan added successfully';
           }  
           else
           {
              $datas['result'] = 'false';
              $datas['status'] = 'Pricing Plan added failed!';
           }
         
      }       

      //exit;        
        echo json_encode($datas);

  }


public function package()
  {
      $this->data['page'] = 'packages';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
     
  }

public function package_list()
{


  $list = $this->packages->get_datatables();
  // echo '<pre>';
  // print_r($list);
  // echo '</pre>';
  // die("dsfdf");
  $data = array();
  $no = $_POST['start'];
  $a=1;
   
  foreach ($list as $packages) 
  {
  // echo '<pre>';
  // print_r($packages);
  // echo '</pre>';
  // die("dsfdf");
  $pr_img = explode(",",$packages['upload_image_url']);
  if(file_exists(FCPATH.$pr_img[0])){
    $pimage = $pr_img[0];
  } else {
     $pimage = 'assets/img/no-image.png';
  }

  $months = $packages['package_months'].' Months, ';
  $weeks = $packages['package_weeks'].' Weeks, ';
  $days = $packages['package_days'].' Days';

  $package_img = $packages['package_image'];
  // echo $package_img;
  // die("ds");
  if($package_img=='uploads/package_image/') $package_img=$package_img."no-image.png";

  $commaExploded = explode(',', $packages['add_on']);

$addOns = [];

foreach ($commaExploded as $item) {
    // Split each item using '-'
    $parts = explode('-', trim($item));
    if (isset($parts[0])) {
        $addOns[] = $parts[0];
    }
}

                                // if(package_img=="uploads/package_image/"){
                                //     package_img+="no-image.png"
                                // }

  // $profileimage=(!empty($packages['package_image']))?base_url().$packages['package_image']:base_url().'assets/img/user.png';
    $no++;
    $row = array();
    $row[] = $no;
        $row[] ='#PI00'.$packages['id'];
        $row[] = '<h2 class="table-avatar">
                    <a href="javascript:void(0)" class="avatar avatar-lg mr-2"><img class="avatar-img rounded-circle" src="'.base_url().''.$package_img.'" alt="User Image"></a>
                    <a href="javascript:void(0)">'.ucfirst($packages['package_name']).'</a>
                  </h2>';
        $row[] = $packages['description'];
        $row[] = $packages['destination'];   
        $row[] = $packages['currency'];
        $row[] = $months.$weeks.$days;
        $row[] = $packages['not_included'];
        $row[] = $addOns;

        $row[] = $packages['speciality'];
        $row[] = $packages['cost'];
        $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light" onclick="edit_package_admin('.$packages['id'].')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_package_admin('.$packages['id'].')">
                    <i class="fe fe-trash"></i> Delete
                  </a>
                </div>';
        
        $data[] = $row;
  }

  $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->packages->count_all(),
          "recordsFiltered" => $this->packages->count_filtered(),
          "data" => $data,
      );
  //output to json format
  echo json_encode($output);
}
public function create_admin_packages()
  {   

  
    // $folder = base_url().'uploads/package_image/';
    $method = $this->input->post('method');
    // echo $method;
    // die("dfesryas");
    $data['package_name']  = $this->input->post('package_name');
  //  $data["package_image"] = $this->input->post('package_img');
    $data["cost"] = $this->input->post('package_price');
    $data["speciality"] = $this->input->post('package_speciality');
    $data["currency"] = $this->input->post('package_currency');
    $data["package_days"] = $this->input->post('package_days');
    $data["package_weeks"] = $this->input->post('package_weeks');
    $data["package_months"] = $this->input->post('package_months');
    $data["destination"] = $this->input->post('package_destination');
    $data["description"] = $this->input->post('package_description');
    // echo $notIncluded;
    // die("dasd");
    $data["not_included"] = $this->input->post('not_included');
    $data["add_on"] = $this->input->post('add_on');
    
    if (isset($_FILES["package_img"]) && $_FILES["package_img"]["error"] === UPLOAD_ERR_OK) {
      $filename = $_FILES["package_img"]["name"];
      $tempname = $_FILES["package_img"]["tmp_name"];
      $folder = "uploads/package_image/" . $filename;
      // echo $folder;
      // die("dsdsd");
  
      if (move_uploaded_file($tempname, $folder)) {
          // echo "Image uploaded successfully.";
		  $data["package_image"] = $folder;
      } else {
          $package_img_error = "Failed to upload image.";
          // $error = true;
      }
	  
	  }
   
  //  echo '<pre>';
  //   print_r($data);
  //   echo '</pre>';  
  //   die("Sdds");

    // $data['upload_image_url']=$_POST['upload_image_url'];
    // $data['upload_preview_image_url']=$_POST['upload_preview_image_url'];  

    //sale_price
          
      if($method=='update')
      {
        

          $id = $this->input->post('id');
          // echo $id;
          // die("inside update");
          $this->db->where('id',$id);
          $this->db->update('packages', $data);
          $result = ($this->db->affected_rows()!= 1) ? false:true;

          $datas['result'] = 'true';
          $datas['status'] = 'Pacakge updated successfully';

             
      }
      else
      {
            
            $this->db->insert('packages',$data);
          

          $result=($this->db->affected_rows()!= 1)? false:true;

          if(@$result==true) 
           {
              $datas['result'] = 'true';
              $datas['status'] = 'Package added successfully';
           }  
           else
           {
              $datas['result'] = 'false';
              $datas['status'] = 'Package added failed!';
           }
         
      }       

      //exit;        
        echo json_encode($datas);
    }

    public function service()
  {
      $this->data['page'] = 'services';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
     
  }

public function service_list()
{


  $list = $this->services->get_datatables();
  // echo '<pre>';
  // print_r($list);
  // echo '</pre>';
  // die("dsfdf");
  $data = array();
  $no = $_POST['start'];
  $a=1;
   
  foreach ($list as $services) 
  {
  // echo '<pre>';
  // print_r($packages);
  // echo '</pre>';
  // die("dsfdf");
  
    $no++;
    $row = array();
    $row[] = $no;
        $row[] ='#SI00'.$services['id'];
        $row[] = $services['specialization_list'];
        $row[] = $services['operation'];
        $row[] = $services['doctor_list'];   
        $row[] = $services['service_clinic'];
        $row[] = $services['country'];
        $row[] = $services['city'];
        $row[] = '$'.$services['service_price'];
        // $row[] = 'Insurance';
        $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light" onclick="edit_service_admin('.$services['id'].')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_service_admin('.$services['id'].')">
                    <i class="fe fe-trash"></i> Delete
                  </a>
                </div>';
        
        $data[] = $row;
  }

  $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->packages->count_all(),
          "recordsFiltered" => $this->packages->count_filtered(),
          "data" => $data,
      );
  //output to json format
  echo json_encode($output);
}
public function create_admin_services()
  {   

  
    // $folder = base_url().'uploads/package_image/';
    $method = $this->input->post('method');
    // echo $method;
    // die("dfesryas");
    $data['specialization_list']  = $this->input->post('specialization_list');
  //  $data["package_image"] = $this->input->post('package_img');
    $data["operation"] = $this->input->post('operation');
    $data["doctor_list"] = $this->input->post('doctor_list');
    $data["service_clinic"] = $this->input->post('service_clinic');
    $data["country"] = $this->input->post('country');
    $data["city"] = $this->input->post('city');
    $data["service_price"] = $this->input->post('service_price');
    
    // echo '<pre>';
    // print_r($data);
    // echo '</pre>';
    // die("sdfsd");
    
  //   if (isset($_FILES["package_img"])) {
  //     $filename = $_FILES["package_img"]["name"];
  //     $tempname = $_FILES["package_img"]["tmp_name"];
  //     $folder = "uploads/package_image/" . $filename;
  //     // echo $folder;
  //     // die("dsdsd");
  
  //     if (move_uploaded_file($tempname, $folder)) {
  //         // echo "Image uploaded successfully.";
  //     } else {
  //         $package_img_error = "Failed to upload image.";
  //         // $error = true;
  //     }}
  //  $data["package_image"] = $folder;
  //  echo '<pre>';
  //   print_r($data);
  //   echo '</pre>';  
  //   die("Sdds");

    // $data['upload_image_url']=$_POST['upload_image_url'];
    // $data['upload_preview_image_url']=$_POST['upload_preview_image_url'];  

    //sale_price
          
      if($method=='update')
      {
        

          $id = $this->input->post('id');
          // echo $id;
          // die("inside update");
          $this->db->where('id',$id);
          $this->db->update('services', $data);
          $result = ($this->db->affected_rows()!= 1) ? false:true;

          $datas['result'] = 'true';
          $datas['status'] = 'Pacakge updated successfully';

             
      }
      else
      {
            
            $this->db->insert('services',$data);
          

          $result=($this->db->affected_rows()!= 1)? false:true;

          if(@$result==true) 
           {
              $datas['result'] = 'true';
              $datas['status'] = 'Package added successfully';
           }  
           else
           {
              $datas['result'] = 'false';
              $datas['status'] = 'Package added failed!';
           }
         
      }       

      //exit;        
        echo json_encode($datas);
    }

 public function clinic()
  {
      $this->data['page'] = 'clinic';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
     
  }

  public function clinic_list()
   {
	  
	   //die("gjdt");
      $list = $this->users->get_clinic_datatables();
      $data = array();
      $no = $_POST['start'];
      $a=1;
     
      foreach ($list as $clinic) {
		//echo $clinic['id'];
		//die("sdsdsd");
        $val='';

      if($clinic['status'] == '1')
        {
          $val = 'checked';
        }
			/* $sql="SELECT * from commission_clinic Where clinic_id=$clinic[id]";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$res= $row['comm_rate'];*/

    $result = $this->commission_clinic->get_commission_by_clinic_id($clinic['id']);
    $res=$result->comm_rate;

        $profileimage=(!empty($clinic['profileimage']))?base_url().$clinic['profileimage']:base_url().'assets/img/user.png';
        
        $no++;
        $row = array();
        $row[] = $no;
        $row[] ='#C00'.$clinic['id'];
        $clinic_name = $clinic['clinic_name'] == "" ? $clinic['first_name'].' '.$clinic['last_name']:$clinic['clinic_name'];
        $row[] = '<h2 class="table-avatar">
                    <a href="javascript:void(0)" class="avatar avatar-sm mr-2" onclick="show_clinic_doctors('.$clinic['id'].')" ><img class="avatar-img rounded-circle" src="'.$profileimage.'" alt="User Image"></a>
                    <a href="javascript:void(0)" onclick="show_clinic_doctors('.$clinic['id'].')">'.ucfirst($clinic_name).'</a>
                  </h2>';
        $row[] = ucfirst($clinic['specialization']);  
        $row[] = $clinic['email'];
        $row[] = $clinic['mobileno'];       
        $row[] = date('d M Y',strtotime($clinic['created_date'])).'<br><small>'.date('h:i A',strtotime($clinic['created_date'])).'</small>';
		
		$row[] = '<form action="#" method=post>
		<input id="commission_input_' . $clinic['id'] . '" type="text" placeholder="Enter commission" value="'.$res.'" name="commission">
          <button onclick="updateCommissionClinic('.$clinic['id'].')">Update</button>
		</form>';

        $row[] = get_earned($clinic['id']);
        $row[] = '<div class="status-toggle">
                      <input type="checkbox" onchange="change_usersStatus('.$clinic['id'].')" id="status_'.$clinic['id'].'" class="check" '.$val.'>
                      <label for="status_'.$clinic['id'].'" class="checktoggle">checkbox</label>
                    </div>';
        $row[] = '<h2 class="table-avatar">
                    <a target="_blank" href="'.base_url().'clinic-preview/'.$clinic['username'].'" class="btn btn-primary">VIEW</a>
                  </h2>';
        /*$row[] = '<div class="actions" hidden>
                  <a class="btn btn-sm bg-success-light" onclick="edit_clinic('.$clinic['id'].')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_clinic('.$clinic['id'].')">
                    <i class="fe fe-trash"></i> Delete
                  </a>
                </div>'; */
        // $row[] = '';
        $row[] = '<div class="actions">
                    <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_clinic('.$clinic['id'].')">
                      <i class="fe fe-trash"></i> Delete
                    </a>
                  </div>';
        
        $data[] = $row;
      }
      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->users->clinic_count_all(),
              "recordsFiltered" => $this->users->clinic_count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }

  public function team()
  {
    // die("dsas");
      $this->data['page'] = 'team';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
     
  }
  
  public function team_list()
   {
	  
	   //die("gjdt");
      $list = $this->users->get_team_datatables();
      // echo '<pre>';
      // print_r($list);
      // echo '</pre>';
      // die("die here");
      $data = array();
      $no = $_POST['start'];
      $a=1;
     
      foreach ($list as $team) {
		//echo $clinic['id'];
		//die("sdsdsd");
        $val='';

      if($team['status'] == '1')
        {
          $val = 'checked';
        }
		

    

        $profileimage=(!empty($team['profileimage']))?base_url().$team['profileimage']:base_url().'assets/img/user.png';
        
        $no++;
        $row = array();
        $row[] = $no;
        $row[] ='#TM00'.$team['id'];
        $row[] = '<h2 class="table-avatar">
                    <a  class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="'.$profileimage.'" alt="User Image"></a>
                    <a >'.ucfirst($team['first_name'].' '.$team['last_name']).'</a>
                  </h2>';
        $row[] = $team['email'];
        $row[] = $team['mobileno'];      
        $row[] = date('d M Y',strtotime($team['created_date'])).'<br><small>'.date('h:i A',strtotime($team['created_date'])).'</small>';
        $row[] = '<div class="actions">
                  
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_team_member('.$team['id'].')">
                    <i class="fe fe-trash"></i> Delete
                  </a>
                </div>';
        
        $data[] = $row;
      }
      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->users->team_count_all(),
              "recordsFiltered" => $this->users->team_count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }
  public function staff()
  {
    // die("dsas");
      $this->data['page'] = 'staff';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
     
  }
  
  public function staff_list()
   {
	  
	   //die("gjdt");
      $list = $this->users->get_staff_datatables();
      // echo '<pre>';
      // print_r($list);
      // echo '</pre>';
      // die("die here");
      $data = array();
      $no = $_POST['start'];
      $a=1;
     
      foreach ($list as $staff) {
		//echo $clinic['id'];
		//die("sdsdsd");
        $val='';

      if($staff['status'] == '1')
        {
          $val = 'checked';
        }
		

    

        $profileimage=(!empty($staff['profileimage']))?base_url().$staff['profileimage']:base_url().'assets/img/user.png';
        
        $no++;
        $row = array();
        $row[] = $no;
        $row[] ='#SM00'.$staff['id'];
        $row[] = '<h2 class="table-avatar">
                    <a target="_blank" href="'.base_url().'team-preview/'.base64_encode($staff['id']).'" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="'.$profileimage.'" alt="User Image"></a>
                    <a target="_blank" href="'.base_url().'team-preview/'.base64_encode($staff['id']).'">'.ucfirst($staff['first_name'].' '.$staff['last_name']).'</a>
                  </h2>';
        $row[] = $staff['email'];
        $row[] = $staff['mobileno'];      
        $row[] = date('d M Y',strtotime($staff['created_date'])).'<br><small>'.date('h:i A',strtotime($staff['created_date'])).'</small>';
        $row[] = '<div class="actions">
                  
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_staff_member('.$staff['id'].')">
                    <i class="fe fe-trash"></i> Delete
                  </a>
                </div>';
        
        $data[] = $row;
      }
      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->users->staff_count_all(),
              "recordsFiltered" => $this->users->staff_count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }

  public function get_clinic_doctors($id)
  {
      $list = $this->users->get_clinic_doctor_datatables($id);
      $data = array();
      $no = $_POST['start'];
      $a=1;
       
      foreach ($list as $doctor) {

        $val='';

        if($doctor['status'] == '1')
        {
          $val = 'checked';
        }

        $profileimage=(!empty($doctor['profileimage']))?base_url().$doctor['profileimage']:base_url().'assets/img/user.png';
        
        $no++;
        $row = array();
        $row[] = '<h2 class="table-avatar">
        <a target="_blank" href="'.base_url().'clinic-preview/'.$doctor['username'].'" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="'.$profileimage.'" alt="User Image"></a>
        <a target="_blank" href="'.base_url().'clinic-preview/'.$doctor['username'].'">Dr. '.ucfirst($doctor['first_name'].' '.$doctor['last_name']).'</a>
      </h2>';
        // $row[] = '<h2 class="table-avatar">
        //             <img class="avatar-img avatar-sm rounded-circle mr-2" src="'.$profileimage.'" alt="User Image"> Dr. '.ucfirst($doctor['first_nam']).'
        //           </h2>';
        $row[] = ucfirst($doctor['specialization']);  
        // $row[] = '<div class="status-toggle">
        //               <input type="checkbox" onchange="change_clinic_doctor_status('.$doctor['clinic_doctor_id'].')" id="status_'.$doctor['clinic_doctor_id'].'" class="check" '.$val.'>
        //               <label for="status_'.$doctor['clinic_doctor_id'].'" class="checktoggle">checkbox</label>
        //             </div>';
        
        $data[] = $row;
      }



      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->users->clinic_doctor_count_all($id),
              "recordsFiltered" => $this->users->clinic_doctor_count_filtered($id),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }
  public function change_clinic_doctor_status()
  {
      $id       =   $this->input->post('id');
      $status   =   $this->input->post('status');
      $data     =   array('status' => $status);
      $this->users->update_clinic_doctors(array('clinic_details_id' => $id), $data);
      echo json_encode(array("status" => TRUE));
  }

  public function clinic_edit($id)
  {
      $clinic_name = '';
      $data = $this->users->get_by_id($id);
      if($this->db->where('user_id',$id)->get('users_details')->num_rows()>0)
      $clinic_name = $this->db->where('user_id',$id)->get('users_details')->row()->clinic_name;
      $data->clinic = $clinic_name;
      echo json_encode($data);
  }

  public function clinic_delete($id)
  {
      $data = array(
          'status' =>0,
      );
      $this->users->update(array('id' => $id), $data);
      echo json_encode(array("status" => TRUE));
  }

  // migrating clinic module



public function addClinicDoctor() {  

  $inputdata['first_name'] = $this->input->post('first_name');  
  $inputdata['last_name'] = $this->input->post('last_name');  
  $inputdata['email'] = $this->input->post('email');  
  $inputdata['mobileno'] = $this->input->post('mobileno');  
  $inputdata['country_code'] = $this->input->post('country_code');  
  $inputdata['username'] = generate_username($inputdata['first_name'] . ' ' . $inputdata['last_name'] . ' ' . $inputdata['mobileno']);  
  $inputdata['role'] = $this->input->post('role');  
  $inputdata['password']=md5($this->input->post('password'));
  $inputdata['confirm_password']=md5($this->input->post('confirm_password'));
  $inputdata['hospital_id'] = $this->input->post('hospital_id');  
  $inputdata['created_date'] = date('Y-m-d H:i:s');  
  $inputdata['is_verified'] = 1;  

  $already_exits=$this->db->where('email',$inputdata['email'])->get('users')->num_rows();
  $already_exits_mobile_no=$this->db->where('mobileno',$inputdata['mobileno'])->get('users')->num_rows();

  if ($already_exits >= 1) {   
    $response['msg'] = 'Email already exits';   
    $response['status'] = 500;  
  }
   else if ($already_exits_mobile_no >= 1) { 

    $response['msg'] = 'Mobile no already exits';   
    $response['status'] = 500;  } 
  else { 

    $result = $this->db->insert('users', $inputdata);   
    if ($result == true) {   
       $response['msg'] = 'Registration success';    
       $response['status'] = 200;   
    }
   else {    
    $response['msg'] = 'Registration failed';    
    $response['status'] = 500;   
  }  
  }  echo json_encode($response); 
}
}
