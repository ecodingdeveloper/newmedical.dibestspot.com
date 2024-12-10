<?php
defined('BASEPATH') or exit('No direct script access allowed');

class My_patients extends CI_Controller
{

  public $data;
  public $session;
  public $timezone;
  public $lang;
  public $language;
  public $my_patients;
  public $input;
  public $db;
  public $uri;
  public $upload;

  public function __construct()
  {

    parent::__construct();

    if ($this->session->userdata('user_id') == '' && $this->session->userdata('admin_id') == '') {

      redirect(base_url() . 'signin');
    }
    $this->data['module']    = 'doctor';
    $this->data['theme']     = 'web';
    $this->data['page']     = '';
    $this->data['base_url'] = base_url();

    $this->timezone = $this->session->userdata('time_zone');
    if (!empty($this->timezone)) {
      date_default_timezone_set($this->timezone);
    }

    // $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):'english';
    $lan = default_language();
    $lang = !empty($this->session->userdata('language')) ? strtolower($this->session->userdata('language')) : strtolower($lan['language']);
    $this->data['language'] = $this->lang->load('content', $lang, true);
    $this->language = $this->lang->load('content', $lang, true);

    $this->load->model('my_patients_model', 'my_patients');
  }

  public function index()
  {

    if ($this->session->userdata('role') == '1') {
      $this->data['page'] = 'my_patients';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
    } else if ($this->session->userdata('role') == '6') {
      $this->data['page'] = 'my_patients';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
    } else {
      redirect(base_url() . 'dashboard');
    }
  }

  public function patient_list()
  {

    $response = array();
    $result = array();
    $page = $this->input->post('page');
    $limit = 8;
    $user_id = $this->session->userdata('user_id');
    $response['count'] = $this->my_patients->patient_list($page, $limit, 1, $user_id);
    $patient_list = $this->my_patients->patient_list($page, $limit, 2, $user_id);

    if (!empty($patient_list)) {
      foreach ($patient_list as $rows) {

        $data['id'] = $rows['id'];
        $data['user_id'] = $rows['user_id'];
        // $data['userid'] = base64_encode($rows['user_id']);
        $data['userid'] =$rows['user_id'];
        $data['username'] = $rows['username'];
        $data['profileimage'] = (!empty($rows['profileimage'])) ? base_url() . $rows['profileimage'] : base_url() . 'assets/img/user.png';
        $data['first_name'] = ucfirst($rows['first_name']);
        $data['last_name'] = ucfirst($rows['last_name']);
        $data['mobileno'] = $rows['mobileno'];
        $data['dob'] = $rows['dob'];
        $data['age'] = age_calculate($rows['dob']);
        $data['blood_group'] = $rows['blood_group'];
        $data['gender'] = $rows['gender'];
        $data['cityname'] = $rows['cityname'];
        $data['countryname'] = $rows['countryname'];
        $result[] = $data;
      }
    }
    $response['current_page_no'] = $page;
    $response['total_page'] = ceil($response['count'] / $limit);
    $response['data'] = $result;

    echo json_encode($response);
  }

  public function mypatient_preview($patient_id)
  {
    $this->data['page'] = 'mypatient_preview';
    $this->data['patient'] = $this->my_patients->get_patient_details(base64_decode($patient_id));
    $this->data['last_booking'] = $this->my_patients->get_last_booking(base64_decode($patient_id));
    $this->data['prescription_status'] = $this->my_patients->get_booking_prescription_status(base64_decode($patient_id));

    $this->data['patient_id'] = base64_decode($patient_id);
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function appoinments_list()
  {
    $list = $this->my_patients->get_appoinments_datatables();
    $data = array();
    $no = $_POST['start'];
    $a = 1;
    $sno = $no + 1;

    foreach ($list as $appoinments) {



      $profile_image = (!empty($appoinments['profileimage'])) ? base_url() . $appoinments['profileimage'] : base_url() . 'assets/img/user.png';
      $no++;
      $row = array();
      $row[] = $sno++;

      if ($appoinments['hospital_id'] != "") {
        $row[] = '<h2 class="table-avatar">
                  ' . $img . '
                  <a href="' . base_url() . 'doctor-preview/' . $appoinments['clinic_username'] . '">' . ucfirst($appoinments['clinic_first_name'] . ' ' . $appoinments['clinic_last_name']) . ' </a>
                 </h2>
                  ';
      } else {

        if ($appoinments['role'] == 1) {
          $value = $this->language['lg_dr'];
          $img = '<a href="' . base_url() . 'doctor-preview/' . $appoinments['username'] . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                  </a>';
          $specialization = ucfirst($appoinments['specialization'] ?? '');
        } else {
          /*$value="";
          $img="";
          $specialization="";*/
          $value = $this->language['lg_dr'];
          $img = '<a href="' . base_url() . 'doctor-preview/' . $appoinments['username'] . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                  </a>';
          $specialization = ucfirst($appoinments['specialization'] ?? '');
        }

        $row[] = '<h2 class="table-avatar">
                  ' . $img . '
                  <a href="' . base_url() . 'doctor-preview/' . $appoinments['username'] . '">' . $value . ' ' . ucfirst($appoinments['first_name'] . ' ' . $appoinments['last_name']) . ' <span>' . $specialization . '</span></a>
                </h2>
                  ';
      }
      $from_date_time = '';
      if (!empty($appoinments['time_zone'])) {
        $from_timezone = $appoinments['time_zone'];
        $to_timezone = date_default_timezone_get();
        $from_date_time = $appoinments['from_date_time'];
        $from_date_time = converToTz($from_date_time, $to_timezone, $from_timezone);
        $to_date_time = $appoinments['to_date_time'];
        $to_date_time = converToTz($to_date_time, $to_timezone, $from_timezone);
        $row[] = date('d M Y', strtotime($from_date_time)) . ' <span class="d-block text-info">' . date('h:i A', strtotime($from_date_time)) . ' - ' . date('h:i A', strtotime($to_date_time)) . '</span>';
      } else {
        $row[] = '-';
      }
      $row[] = date('d M Y', strtotime($appoinments['created_date']));
      $row[] = ucfirst($appoinments['type']);

      // if($appoinments['approved']==1 && $appoinments['appointment_status']==0 && $appoinments['call_status']==0 && $appoinments['type']!='Online')
      // {


      //   $row[] ='<div class="actions">
      //              <select name="appointment_status" class="form-control appointment_status" id="'.$appoinments['id'].'">
      //                <option value="0">New</option>
      //                <option value="1">Completed</option>
      //              </select>
      //           </div>';
      // }
      // else
      // {
      //   // if($appoinments['appointment_status']==0)
      //   // {
      //   //   $app_status="New";
      //   // }
      //   if($appoinments['appointment_status']==1)
      //   {
      //     $app_status="Completed";
      //   }
      //   else
      //   {
      //     $app_status='-';
      //   }

      //   $row[]=$app_status;
      // }


      if ($appoinments['approved'] == 1 && $appoinments['call_status'] == 0 && $appoinments['type'] != 'Online') {

        $new_apt = '';
        $com_apt = '';
        $exp_apt = '';
        if ($appoinments['appointment_status'] == 0) {
          $new_apt = 'selected';
        } else if ($appoinments['appointment_status'] == 1) {
          $com_apt = 'selected';
        } else if ($appoinments['appointment_status'] == 2) {
          $exp_apt = 'selected';
        }

        $row[] = '<div class="actions">
                     <select name="appointment_status" class="form-control appointment_status" id="' . $appoinments['id'] . '">
                       <option value="0" ' . $new_apt . '>New</option>
                       <option value="1" ' . $com_apt . '>Completed</option>
                       <option value="2" ' . $exp_apt . '>Expired</option>
                     </select>
                  </div>';
      } else if ($appoinments['approved'] == 1 && $appoinments['call_status'] == 1 && $appoinments['type'] != 'Online') {


        if ($appoinments['appointment_status'] == 1) {
          $app_status = "Completed";
        } else if ($appoinments['appointment_status'] == 2) {
          $app_status = "Expired";
        } else {
          $app_status = '-';
        }

        $row[] = $app_status;
      } else {
        $app_status = '-';
        $row[] = $app_status;
      }




      $data[] = $row;
    }



    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->my_patients->appoinments_count_all(),
      "recordsFiltered" => $this->my_patients->appoinments_count_filtered(),
      "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

  public function add_prescription($patient_id)
  {
    $this->data['page'] = 'add_prescription';
    $this->data['patient_id'] = base64_decode($patient_id);
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function edit_prescription()
  {
    $prescription_id = $this->uri->segment(2);
    $patient_id = $this->uri->segment(3);
    $this->data['page'] = 'edit_prescription';
    $this->data['patient_id'] = base64_decode($patient_id);
    $this->data['prescription'] = $this->my_patients->get_prescription(base64_decode($prescription_id));
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function print_prescription()
  {
    $prescription_id = $this->uri->segment(2);
    $data['prescription'] = $this->my_patients->get_prescription_details(base64_decode($prescription_id));
    $data['language'] = $this->language;
    $this->load->view('web/modules/print/print_prescription', $data);
  }



  public function save_prescription()
  {

    $data = array(
      'doctor_id' => $this->session->userdata('user_id'),
      'patient_id' => $this->input->post('patient_id'),
      'signature_id' => $this->input->post('signature_id'),
      'created_at' => date('Y-m-d H:i:s')
    );
    $this->db->insert('prescription', $data);
    $prescription_id = $this->db->insert_id();

    $drug_name = $this->input->post('drug_name');
    $qty = $this->input->post('qty');
    $type = $this->input->post('type');
    $days = $this->input->post('days');
    $time = $this->input->post('time');
    $rowval = $this->input->post('rowValue');

    for ($i = 0; $i < count($drug_name); $i++) {
      $time = '';
      $j = $i + 1;
      /*if(!empty($_POST['time'.$j])){
        $time = implode(',',$_POST['time'.$j]);
      }*/

      $arrval = $rowval[$i];
      if (!empty($_POST['time'][$arrval])) {
        $time = implode(',', $_POST['time'][$arrval]);
      }

      $datas = array(
        'prescription_id' => $prescription_id,
        'drug_name' => $drug_name[$i],
        'qty' => $qty[$i],
        'type' => $type[$i],
        'days' => $days[$i],
        'time' => $time,
        'created_at'  => date('Y-m-d H:i:s')
      );
      $this->db->insert('prescription_item_details', $datas);
    }



    // Notification

    $notification = array(
      'user_id' => $this->session->userdata('user_id'),
      'to_user_id' => $this->input->post('patient_id'),
      'type' => "Prescription",
      'text' => "has prescription to",
      'created_at' => date("Y-m-d H:i:s"),
      'time_zone' => $this->session->userdata('time_zone')
    );
    $this->db->insert('notification', $notification);


    $result = ($this->db->affected_rows() != 1) ? false : true;
    if ($result == true) {

      $response['msg'] = $this->language['lg_prescription_ad1'];
      $response['status'] = 200;
      $response['patient_id'] = base64_encode($this->input->post('patient_id'));
    } else {
      $response['msg'] = $this->language['lg_prescription_ad'];
      $response['status'] = 500;
    }



    echo json_encode($response);
  }


  public function update_prescription()
  {

    $prescription_id = $this->input->post('prescription_id');

    $data = array(
      'signature_id' => $this->input->post('signature_id'),
    );
    $this->db->where('id', $prescription_id);
    $this->db->update('prescription', $data);

    $where = array('prescription_id' => $prescription_id);
    $this->db->delete('prescription_item_details', $where);

    $drug_name = $this->input->post('drug_name');
    $qty = $this->input->post('qty');
    $type = $this->input->post('type');
    $days = $this->input->post('days');
    $time = $this->input->post('time');
    $rowval = $this->input->post('rowValue');


    for ($i = 0; $i < count($drug_name); $i++) {
      $time = '';
      $j = $i + 1;
      /*if(!empty($_POST['time'.$j])){
        $time = implode(',',$_POST['time'.$j]);
      } */

      $arrval = $rowval[$i];
      if (!empty($_POST['time'][$arrval])) {
        $time = implode(',', $_POST['time'][$arrval]);
      }

      $datas = array(
        'prescription_id' => $prescription_id,
        'drug_name' => $drug_name[$i],
        'qty' => $qty[$i],
        'type' => $type[$i],
        'days' => $days[$i],
        'time' => $time,
        'created_at'  => date('Y-m-d H:i:s')
      );
      $this->db->insert('prescription_item_details', $datas);
    }

    $result = ($this->db->affected_rows() != 1) ? false : true;
    if ($result == true) {

      $response['msg'] = $this->language['lg_prescription_up1'];
      $response['status'] = 200;
      $response['patient_id'] = base64_encode($this->input->post('patient_id'));
    } else {
      $response['msg'] = $this->language['lg_prescription_up'];
      $response['status'] = 500;
    }


    $notification = array(
      'user_id' => $this->session->userdata('user_id'),
      'to_user_id' => $this->input->post('patient_id'),
      'type' => "Prescription",
      'text' => "has prescription updated to",
      'created_at' => date("Y-m-d H:i:s"),
      'time_zone' => $this->session->userdata('time_zone')
    );
    $this->db->insert('notification', $notification);


    echo json_encode($response);
  }

  public function insert_signature()
  {

    $img = $_POST['image'];
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    $file = './uploads/signature-image/' . uniqid() . '.png';
    $success = file_put_contents($file, $data);
    $image = str_replace('./', '', $file);
    $check = $this->get_single_signs();
    if ($check == 0) {
      $data = array('img' => $image, 'rowno' => $_POST['rowno']);
      $this->db->insert('signature', $data);
      $id = $this->db->insert_id();
    } else {
      $data = array('img' => $image);
      $this->db->where('rowno', $_POST['rowno'])->update('signature', $data);
      $id = $this->db->where('rowno', $_POST['rowno'])->get('signature')->row()->id;
    }
    echo '<div id="edit">
          <img src="' . base_url() . $image . '" style="width:180px;height:85px;">
        <div class="edit" style="position: absolute;">
          <i class="fa fa-edit" onclick="show_modal()" title="' . $this->language['lg_click_here_to_e'] . '"></i>
        </div>
        <input type="hidden" name="signature_id" value="' . $id . '" id="signature_id">
      </div>';
  }


  public function get_single_signs()
  {
    $datas = array('rowno' => $_POST['rowno']);
    return $this->db->get_where('signature', $datas)->num_rows();
  }


  public function prescriptions_list()
  {
    $list = $this->my_patients->get_prescription_datatables();
    $data = array();
    $no = $_POST['start'];
    $a = $no + 1;
    $b = $no + 1;
    foreach ($list as $prescriptions) {

      $profile_image = (!empty($prescriptions['profileimage'])) ? base_url() . $prescriptions['profileimage'] : base_url() . 'assets/img/user.png';
      $no++;
      $row = array();
      $row[] = $a++;
      $row[] = date('d M Y', strtotime($prescriptions['created_at']));
      $row[] = 'Prescription ' . $b++;
      $row[] = '<h2 class="table-avatar">
                  <a href="' . base_url() . 'doctor-preview/' . $prescriptions['username'] . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                  </a>
                  <a href="' . base_url() . 'doctor-preview/' . $prescriptions['username'] . '">Dr. ' . ucfirst($prescriptions['first_name'] . ' ' . $prescriptions['last_name']) . ' <span>' . ucfirst($prescriptions['specialization'] ?? '') . '</span></a>
                </h2>
                  ';

      $html = '<div class="table-action">
                  <a href="' . base_url() . 'print-prescription/' . base64_encode($prescriptions['id']) . '" target="_blank" download class="btn btn-sm bg-success-light"><i class="fas fa-download"></i> ' . $this->language['lg_download'] . '</a>

                  <a target="_blank" href="' . base_url() . 'print-prescription/' . base64_encode($prescriptions['id']) . '" class="btn btn-sm bg-primary-light">
                    <i class="fas fa-print"></i> ' . $this->language['lg_print'] . '
                  </a>
                  <a href="javascript:void(0);" onclick="view_prescription(' . $prescriptions['id'] . ')" class="btn btn-sm bg-info-light">
                    <i class="far fa-eye"></i> ' . $this->language['lg_view1'] . '
                  </a>';
      if (is_doctor()) {
        $html .= '<a href="' . base_url() . 'edit-prescription/' . base64_encode($prescriptions['id']) . '/' . base64_encode($prescriptions['patient_id']) . '" class="btn btn-sm bg-success-light">
                    <i class="fas fa-edit"></i> ' . $this->language['lg_edit2'] . '
                  </a>
                  <a href="javascript:void(0);" onclick="delete_prescription(' . $prescriptions['id'] . ')" class="btn btn-sm bg-danger-light">
                    <i class="far fa-trash-alt"></i> ' . $this->language['lg_delete'] . '
                  </a>';
      }
      $html .= '</div>';

      $row[] = $html;

      $data[] = $row;
    }



    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->my_patients->prescription_count_all(),
      "recordsFiltered" => $this->my_patients->prescription_count_filtered(),
      "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

  public function get_prescription_details()
  {

    $prescription_id = $this->input->post('pre_id');
    $result = $this->my_patients->get_prescription_details($prescription_id);
    echo json_encode($result);
  }


  public function upload_medical_records()
  {
    $data = array();
    //ob_flush();
    $doctor_id = $this->session->userdata('user_id');
    $patient_id = $this->input->post('patient_id');
    $description = $this->input->post('description');
    $medical_record_id = $this->input->post('medical_record_id');

    if (!empty($_FILES['user_file']['name'])) {

      $path = "uploads/medical_records/" . $patient_id;
      $upload_path = $path . '/' . date('d-m-Y');
      if (!is_dir($path)) {
        mkdir($path);
      }
      if (!is_dir($path . '/' . date('d-m-Y'))) {
        mkdir($path . '/' . date('d-m-Y'));
      }
      $path = $path . '/' . date('d-m-Y');

      $target_file = $path . basename($_FILES["user_file"]["name"]);
      $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

      if ($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg" && $file_type != "gif") {
        $type = 'others';
      } else {
        $type = 'image';
      }
      $config['upload_path']   = './' . $path;
      $config['allowed_types'] = '*';
      $config['file_name'] = date('d-m-Y_h_i_A') . '_medicalrecord';
      $this->load->library('upload', $config);
      if ($this->upload->do_upload('user_file')) {
        $file_name = $this->upload->data('file_name');
        $data += array('file_name' => $upload_path . '/' . $file_name);
      } else {
        $response['status'] = 500;
        $response['msg'] = $this->upload->display_errors();
        $result = array();
        echo json_encode($result);
        exit;
      }
    }

    $data += array(
      'date' =>  date('Y-m-d H:i:s'),
      'description' => $description,
      'doctor_id' => $doctor_id,
      'patient_id' => $patient_id
    );

    if ($medical_record_id == '') {
      $this->db->insert('medical_records', $data);
      $med_rec_updated = 0;
    } else if ($medical_record_id > 0) {
      $this->db->where('id', $medical_record_id);
      $this->db->update('medical_records', $data);
      $med_rec_updated = $this->db->affected_rows();
    }

    $notification = array(
      'user_id' => $doctor_id,
      'to_user_id' => $patient_id,
      'type' => "Medical-records",
      'text' => "has medical records to",
      'created_at' => date("Y-m-d H:i:s"),
      'time_zone' => $this->session->userdata('time_zone')
    );
    $this->db->insert('notification', $notification);


    $result = ($this->db->affected_rows() != 1) ? false : true;
    if ($result == true && $med_rec_updated == 0) {

      $response['msg'] = $this->language['lg_medical_records2'];
      $response['status'] = 200;
    } else if ($result == true && $med_rec_updated > 0) {

      $response['msg'] = $this->language['lg_medical_records3'];
      $response['status'] = 200;
    } else {
      $response['msg'] = $this->language['lg_medical_records1'];
      $response['status'] = 500;
    }

    echo json_encode($response);
  }



  public function view_dec()
  {
    $id = $this->input->post('id');
    $description = $this->db->select('description')->where('id', $id)->get('medical_records')->row_array();
    echo $description['description'];
    exit;
  }

  public function medical_records_list()
  {
    $list = $this->my_patients->get_medical_record_datatables();
    $data = array();
    $no = $_POST['start'];
    $a = $no + 1;
    $b = 1;
    foreach ($list as $medical_records) {

      $profile_image = (!empty($medical_records['profileimage'])) ? base_url() . $medical_records['profileimage'] : base_url() . 'assets/img/user.png';
      $no++;
      $row = array();
      $row[] = $a++;
      $row[] = date('d M Y', strtotime($medical_records['date']));
      // $row[] =$medical_records['description'];
      $row[] = '<a onclick="view_dec(' . $medical_records['id'] . ');"  class="btn btn-primary btn-sm">Description</a>';
      $row[] = '<a href="' . base_url() . $medical_records['file_name'] . '" target="_blank" title="' . $this->language['lg_download_attach'] . '" class="btn btn-primary btn-sm">' . $this->language['lg_download'] . '<i class="fa fa-download"></i></a>';

      if (is_doctor() || is_clinic()) {

        $row[] = '<h2 class="table-avatar">
                  <a href="' . base_url() . 'doctor-preview/' . $medical_records['username'] . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                  </a>
                  <a href="' . base_url() . 'doctor-preview/' . $medical_records['username'] . '">' . $this->language['lg_dr'] . ' ' . ucfirst($medical_records['first_name'] . ' ' . $medical_records['last_name']) . ' <span>' . ucfirst($medical_records['specialization']) . '</span></a>
                </h2>
                  ';

        $row[] = '<div class="table-action">
                <a href="' . base_url() . $medical_records['file_name'] . '" target="_blank"  class="btn btn-sm bg-info-light">
                    <i class="fas fa-eye"></i> ' . $this->language['lg_view1'] . '
                  </a>
                
			      <a href="#" class="btn btn-sm bg-success-light" onclick="edit_medi_rec(' . $medical_records['id'] . ')"  ><i class="fas fa-edit"></i> ' . $this->language['lg_edit2'] . '</a>
                  <a href="javascript:void(0);" onclick="delete_medical_records(' . $medical_records['id'] . ')" class="btn btn-sm bg-danger-light">
                    <i class="far fa-trash-alt"></i> ' . $this->language['lg_delete'] . '
                  </a>
				  <a target="_blank" href="' . base_url() . 'print-medical-records/' . base64_encode($medical_records['id']) . '" class="btn btn-sm bg-primary-light">
                    <i class="fas fa-print"></i> ' . $this->language['lg_print'] . '
                  </a>
                </div>';
      }

      if (is_patient()) {

        $row[] = '<h2 class="table-avatar">
                  <a href="' . base_url() . 'doctor-preview/' . $medical_records['username'] . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                  </a>
                  <a href="' . base_url() . 'doctor-preview/' . $medical_records['username'] . '">' . ucfirst($medical_records['first_name'] . ' ' . $medical_records['last_name']) . ' <span>' . ucfirst($medical_records['specialization']) . '</span></a>
                </h2>
                  ';

        $row[] = '<div class="table-action">
                <a href="' . base_url() . $medical_records['file_name'] . '" target="_blank"  class="btn btn-sm bg-info-light">
                    <i class="fas fa-eye"></i> ' . $this->language['lg_view1'] . '
                  </a>
                
                 
                </div>';
      }

      $data[] = $row;
    }



    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->my_patients->medical_record_count_all(),
      "recordsFiltered" => $this->my_patients->medical_record_count_filtered(),
      "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }


  public function add_billing($patient_id)
  {
    $this->data['page'] = 'add_billing';
    $this->data['patient_id'] = base64_decode($patient_id);
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function edit_billing()
  {
    $billing_id = $this->uri->segment(2);
    $patient_id = $this->uri->segment(3);
    $this->data['page'] = 'edit_billing';
    $this->data['patient_id'] = base64_decode($patient_id);
    $this->data['billing'] = $this->my_patients->get_billing(base64_decode($billing_id));
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function print_billing()
  {
    $billing_id = $this->uri->segment(2);
    $data['billing'] = $this->my_patients->get_billing_details(base64_decode($billing_id));
    $data['language'] = $this->language;
    $this->load->view('web/modules/print/print_billing', $data);
  }

  public function save_billing()
  {
    $bill = $this->db->order_by('id', 'desc')->limit(1)->get('billing')->row_array();
    if (empty($bill)) {
      $bill_id = 0;
    } else {
      $bill_id = $bill['id'];
    }
    $bill_id++;
    $bill_no = 'BI0000' . $bill_id;

    $data = array(
      'doctor_id' => $this->session->userdata('user_id'),
      'patient_id' => $this->input->post('patient_id'),
      'signature_id' => $this->input->post('signature_id'),
      'created_at' => date('Y-m-d H:i:s'),
      'bill_no' => $bill_no
    );
    $this->db->insert('billing', $data);
    $billing_id = $this->db->insert_id();

    $name = $this->input->post('name');
    $amount = $this->input->post('amount');
    $billamount = 0;
    for ($i = 0; $i < count($name); $i++) {

      $datas = array(
        'billing_id' => $billing_id,
        'name' => $name[$i],
        'amount' => $amount[$i],
        'created_at'  => date('Y-m-d H:i:s')
      );
      $billamount += $amount[$i];
      $this->db->insert('billing_item_details', $datas);
    }
    $user_currency = get_user_currency();
    $user_currency_code = $user_currency['user_currency_code'];
    $invoice = $this->db->order_by('id', 'desc')->limit(1)->get('payments')->row_array();
    if (empty($invoice)) {
      $invoice_id = 0;
    } else {
      $invoice_id = $invoice['id'];
    }
    $invoice_id++;
    $invoice_no = 'I0000' . $invoice_id;

    $tax = !empty(settings("tax")) ? settings("tax") : "0";
    $transcation_charge_amt = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";
    $transcation_charge_amt = doubleval($transcation_charge_amt);
    if ($transcation_charge_amt > 0) {
      $transcation_charge = ($billamount * ($transcation_charge_amt / 100));
      $transcation_charge = number_format($transcation_charge, 2, '.', '');
    } else {
      $transcation_charge = 0;
    }
    $total_amount = $billamount + $transcation_charge;
    $tax_amount = ($total_amount * $tax / 100);
    $tax_amount = number_format($tax_amount, 2, '.', '');

    $total_amount = $total_amount + $tax_amount;
    $total_amount = number_format($total_amount, 2, '.', '');

    $paymentdata = array(
      'doctor_id' => $this->session->userdata('user_id'),
      'user_id' => $this->input->post('patient_id'),
      'payment_status' => 0,
      'billing_id' => $billing_id,
      'invoice_no' => $invoice_no,
      'per_hour_charge' => $billamount,
      'total_amount' => $total_amount,
      'transcation_charge' => $transcation_charge,
      'transaction_charge_percentage' => $transcation_charge_amt,
      'tax_amount' => $tax_amount,
      'tax' => $tax,
      'currency_code' => $user_currency_code,
      'time_zone' => $this->timezone,
    );
    $this->db->insert('payments', $paymentdata);
    $payment_id = $this->db->insert_id();

    $result = ($this->db->affected_rows() != 1) ? false : true;
    if ($result == true) {

      $response['msg'] = $this->language['lg_billing_added_s'];
      $response['status'] = 200;
      $response['patient_id'] = base64_encode($this->input->post('patient_id'));

      $notification = array(
        'user_id' => $this->session->userdata('user_id'),
        'to_user_id' => $this->input->post('patient_id'),
        'type' => "Billing",
        'text' => "has added new bill-" . $bill_no . " to",
        'created_at' => date("Y-m-d H:i:s"),
        'time_zone' => $this->timezone,
      );
      $this->db->insert('notification', $notification);

      $notifications = array(
        'user_id' => $this->session->userdata('user_id'),
        'to_user_id' => $this->input->post('patient_id'),
        'type' => "Billing",
        'text' => "has raised new invoice-" . $invoice_no . " for the bill-" . $bill_no . " to",
        'created_at' => date("Y-m-d H:i:s"),
        'time_zone' => $this->timezone,
      );
      $this->db->insert('notification', $notifications);

      $this->send_bill_notification_mail($billing_id);
    } else {
      $response['msg'] = $this->language['lg_billing_added_f'];
      $response['status'] = 500;
    }



    echo json_encode($response);
  }

  public function send_bill_notification_mail($bill_id)
  {
    $billing_details = $this->my_patients->getBillingDetails($bill_id);
    $this->load->library('sendemail');
    $this->sendemail->send_bill_notify_email($billing_details);

    $notifydata['include_player_ids'] = $billing_details['patient_device_id'];
    $device_type = $billing_details['patient_device_type'];
    $nresponse['from_name'] = $billing_details['doctor_first_name'];

    $notifydata['message'] = $nresponse['from_name'] . ' has sent an new bill -' . $billing_details['bill_no'].". Please check and pay the bill";
    $notifydata['notifications_title'] = '';
    $notifydata['title'] = 'Booking';
    $nresponse['type'] = 'Booking';
    $notifydata['additional_data'] = $nresponse;
    if ($device_type == 'Android' && $billing_details['patient_device_id'] != '') {
      sendFCMNotification($notifydata);
    }
    if ($device_type == 'IOS'  && $billing_details['patient_device_id'] != '') {
      sendiosNotification($notifydata);
    }
  }



  public function update_billing()
  {

    $billing_id = $this->input->post('billing_id');
    $data = array(
      'signature_id' => $this->input->post('signature_id'),
    );
    $this->db->where('id', $billing_id);
    $this->db->update('billing', $data);
    $where = array('billing_id' => $billing_id);
    $this->db->delete('billing_item_details', $where);

    $name = $this->input->post('name');
    $amount = $this->input->post('amount');
    $billamount = 0;
    for ($i = 0; $i < count($name); $i++) {

      $datas = array(
        'billing_id' => $billing_id,
        'name' => $name[$i],
        'amount' => $amount[$i],
        'created_at'  => date('Y-m-d H:i:s')
      );
      $billamount += $amount[$i];
      $this->db->insert('billing_item_details', $datas);
    }
    $user_currency = get_user_currency();
    $user_currency_code = $user_currency['user_currency_code'];
    $tax = !empty(settings("tax")) ? settings("tax") : "0";
    $transcation_charge_amt = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";
    $transcation_charge_amt = doubleval($transcation_charge_amt);
    if ($transcation_charge_amt > 0) {
      $transcation_charge = ($billamount * ($transcation_charge_amt / 100));
      $transcation_charge = number_format($transcation_charge, 2, '.', '');
    } else {
      $transcation_charge = 0;
    }
    $total_amount = $billamount + $transcation_charge;
    $tax_amount = ($total_amount * $tax / 100);
    $tax_amount = number_format($tax_amount, 2, '.', '');

    $total_amount = $total_amount + $tax_amount;
    $total_amount = number_format($total_amount, 2, '.', '');
    $paymentdata = array(
      'payment_status' => 0,
      'billing_id' => $billing_id,
      'per_hour_charge' => $billamount,
      'currency_code' => $user_currency_code,
      'per_hour_charge' => $billamount,
      'total_amount' => $total_amount,
      'transcation_charge' => $transcation_charge,
      'tax_amount' => $tax_amount,
      'tax' => $tax,
    );
    $this->db->where('billing_id', $billing_id)->update('payments', $paymentdata);

    $result = ($this->db->affected_rows() != 1) ? false : true;
    if ($result == true) {

      $response['msg'] = $this->language['lg_billing_update_'];
      $response['status'] = 200;
      $response['patient_id'] = base64_encode($this->input->post('patient_id'));
    } else {
      $response['msg'] = $this->language['lg_billing_update_not'];
      $response['status'] = 500;
    }

    echo json_encode($response);
  }

  public function billing_list()
  {
    $list = $this->my_patients->get_billing_datatables();
    $data = array();
    $no = $_POST['start'];
    $a = $no + 1;
    $b = $no + 1;
    foreach ($list as $billings) {

      $profile_image = (!empty($billings['profileimage'])) ? base_url() . $billings['profileimage'] : base_url() . 'assets/img/user.png';
      $no++;
      $row = array();
      $row[] = $a++;
      $row[] = date('d M Y', strtotime($billings['created_at']));
      //$row[] = 'Billno ' . $b++;
      $row[] = $billings['bill_no'];
      $row[] = '<h2 class="table-avatar">
                  <a href="' . base_url() . 'doctor-preview/' . $billings['username'] . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                  </a>
                  <a href="' . base_url() . 'doctor-preview/' . $billings['username'] . '">' . $this->language['lg_dr'] . ' ' . ucfirst($billings['first_name'] . ' ' . $billings['last_name']) . ' <span>' . ucfirst($billings['specialization'] ?? '') . '</span></a>
                </h2>';

      $html = '<div class="table-action">
                  <a target="_blank" href="' . base_url() . 'print-billing/' . base64_encode($billings['id']) . '" class="btn btn-sm bg-primary-light">
                    <i class="fas fa-print"></i> ' . $this->language['lg_print'] . '
                  </a>
                  <a href="javascript:void(0);" onclick="view_billing(' . $billings['id'] . ')" class="btn btn-sm bg-info-light">
                    <i class="far fa-eye"></i> ' . $this->language['lg_view1'] . '
                  </a>';
      if (is_doctor()) {
        if($billings['billing_paid_status']==0) {
        $html .= '<a href="' . base_url() . 'edit-billing/' . base64_encode($billings['id']) . '/' . base64_encode($billings['patient_id']) . '" class="btn btn-sm bg-success-light ml-1">
                    <i class="fas fa-edit"></i> ' . $this->language['lg_edit2'] . '
                  </a>
                  <a href="javascript:void(0);" onclick="delete_billing(' . $billings['id'] . ')" class="btn btn-sm bg-danger-light ml-1">
                    <i class="far fa-trash-alt"></i> ' . $this->language['lg_delete'] . '
                  </a>';
        }
      }
      $html .= '</div>';

      $row[] = $html;


      $data[] = $row;
    }



    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->my_patients->billing_count_all(),
      "recordsFiltered" => $this->my_patients->billing_count_filtered(),
      "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }



  public function get_billing_details()
  {
    $billing_id = $this->input->post('id');
    $result = $this->my_patients->get_billing_details($billing_id);
    // convert amount to user currency
    foreach ($result as $key => $value) {
      $result[$key]['amount'] = convert_to_user_currency($value['amount']);
    }
    // convert amount to user currency
    echo json_encode($result);
  }

  public function delete_details()
  {
    $id = $this->input->post('id');
    $table_name = $this->input->post('delete_table');

    if ($table_name == 'prescription') {
      $this->db->delete('prescription', array('id' => $id));
      $this->db->delete('prescription_item_details', array('prescription_id' => $id));
      echo 1;
    }

    if ($table_name == 'billing') {
      $this->db->delete('billing', array('id' => $id));
      $this->db->delete('billing_item_details', array('billing_id' => $id));
      echo 1;
    }

    if ($table_name == 'medical_records') {
      $this->db->delete('medical_records', array('id' => $id));
      echo 1;
    }

    if ($table_name == 'users') {
      $where = array('id' => $id);
      $data = array('status' => 0);
      $this->db->update('users', $data, $where);
      echo 1;
    }
  }

  public function change_appointment_status()
  {
    $id = $this->input->post('id');
    $status = $this->input->post('status');
    if ($status == 1) {
      $this->db->where('id', $id)->update('appointments', array('call_status' => 1, 'appointment_status' => $status));
    } else {
      $this->db->where('id', $id)->update('appointments', array('appointment_status' => $status));
    }
    echo 'success';
  }

  public function print_medical_records()
  {
    $medical_records_id = $this->uri->segment(2);
    $medical_records = $this->my_patients->get_medical_records_details(base64_decode($medical_records_id));
    //$this->load->view('web/modules/print/print_medical_records',$data);
    redirect(base_url() . $medical_records[0][file_name]);
  }
  public function get_medical_records()
  {
    $medical_record_id = $this->input->post('medical_rec_id');
    $response['data'] = $this->my_patients->get_medical_record_row($medical_record_id);
    echo json_encode($response);
    //echo $medical_record_id;

  }
}
