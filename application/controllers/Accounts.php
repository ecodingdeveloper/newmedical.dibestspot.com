<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Accounts extends CI_Controller
{

  public $data;
  public $session;
  public $timezone;
  public $lang;
  public $input;
  public $db;
  public $language;
  public $accounts;
  public $uri;

  public function __construct()
  {

    parent::__construct();
    if ($this->session->userdata('user_id') == '') {
      if ($this->session->userdata('admin_id')) {
        redirect(base_url() . 'home');
      } else {
        redirect(base_url() . 'signin');
      }
    }

    $this->data['theme']     = 'web';
    $this->data['module']    = 'invoice';
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
    $this->load->model('accounts_model', 'accounts');
    $this->load->model('commission_model','commission');//role=1//doctor;
    $this->load->model('commission_phar_model','commission_phar');//role=5
     $this->load->model('commission_clinic_model','commission_clinic');//role=6;
     $this->load->model('commission_lab_model','commission_lab');//role=4;
     $this->load->model('commission_pat_model','commission_pat');//role=2;
  }

  public function index()
  {
    $user_id = $this->session->userdata('user_id');
    if ($this->session->userdata('role') == '1') {
      $this->data['module']    = 'doctor';
      $this->data['page'] = 'accounts';

      $this->data['bill']  = $this->input->get('bill');

      $user_currency = get_user_currency();
      $user_currency_code = $user_currency['user_currency_code'];
      $user_currency_rate = $user_currency['user_currency_rate'];

      $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
      $rate_symbol = currency_code_sign($currency_option);

      $this->data['currency_symbol'] = $rate_symbol;

      $this->data['balance'] = $this->accounts->get_balance($user_id);
      $this->data['requested'] = $this->accounts->get_requested($user_id);
      $this->data['earned'] = $this->accounts->get_earned($user_id);
      $this->data['account_details'] = $this->accounts->get_account_details($user_id);
      $this->data['insurance_details'] = $this->accounts->get_insurance_details($user_id);

      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
    } elseif ($this->session->userdata('role') == '4') {
      $this->data['module']    = 'lab';
      $this->data['page'] = 'accounts';

      $user_currency = get_user_currency();
      $user_currency_code = $user_currency['user_currency_code'];
      $user_currency_rate = $user_currency['user_currency_rate'];

      $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
      $rate_symbol = currency_code_sign($currency_option);

      $this->data['currency_symbol'] = $rate_symbol;

      $this->data['balance'] = $this->accounts->get_balance($user_id);
      $this->data['requested'] = $this->accounts->get_requested($user_id);
      $this->data['up_requested'] = $this->accounts->get_up_requested($user_id);
      $this->data['earned'] = $this->accounts->get_earned($user_id);
      $this->data['account_details'] = $this->accounts->get_account_details($user_id);
      $this->data['insurance_details'] = $this->accounts->get_insurance_details($user_id);

      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
    } elseif ($this->session->userdata('role') == '5') {
      $this->data['module']    = 'pharmacy';
      $this->data['page'] = 'accounts';

      $user_currency = get_user_currency();
      $user_currency_code = $user_currency['user_currency_code'];
      $user_currency_rate = $user_currency['user_currency_rate'];

      $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
      $rate_symbol = currency_code_sign($currency_option);

      $this->data['currency_symbol'] = $rate_symbol;

      $this->data['balance'] = $this->accounts->get_balance($user_id);
      // print_r($this->data['balance']);exit();
      $this->data['requested'] = $this->accounts->get_requested($user_id);
      $this->data['earned'] = $this->accounts->get_earned($user_id);
      $this->data['account_details'] = $this->accounts->get_account_details($user_id);
      $this->data['insurance_details'] = $this->accounts->get_insurance_details($user_id);

      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
    } else if ($this->session->userdata('role') == '6') {
      $this->data['module']    = 'doctor';
      $this->data['page'] = 'accounts';

      $user_currency = get_user_currency();
      $user_currency_code = $user_currency['user_currency_code'];
      $user_currency_rate = $user_currency['user_currency_rate'];

      $currency_option = (!empty($user_currency_code)) ? $user_currency_code : settings('default_currency');
      $rate_symbol = currency_code_sign($currency_option);
      $this->data['currency_symbol'] = $rate_symbol;
      $this->data['balance'] =  round($this->accounts->get_balance($user_id));
      $this->data['requested'] = $this->accounts->get_requested($user_id);
      $this->data['earned'] = $this->accounts->get_earned($user_id);
      $this->data['account_details'] = $this->accounts->get_account_details($user_id);
      $this->data['insurance_details'] = $this->accounts->get_insurance_details($user_id);

      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
    } else {
      $this->data['module']    = 'patient';
      $this->data['page'] = 'accounts';

      $user_currency = get_user_currency();
      $user_currency_code = $user_currency['user_currency_code'];
      $user_currency_rate = $user_currency['user_currency_rate'];

      $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
      // print_r($user_id);exit();
      $rate_symbol = currency_code_sign($currency_option);

      $this->data['currency_symbol'] = $rate_symbol;

      $this->data['balance'] = $this->accounts->get_patient_balance($user_id);
      $this->data['requested'] = $this->accounts->get_requested($user_id);
      // print_r($this->db->last_query());exit();

      // print_r($this->data['requested']);exit();
      $this->data['earned'] = $this->accounts->get_earned($user_id);
      $this->data['account_details'] = $this->accounts->get_account_details($user_id);
      $this->data['insurance_details'] = $this->accounts->get_insurance_details($user_id);

      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
    }
  }

  // public function index()
  // { 
  //    $user_id=$this->session->userdata('user_id');
  //        if($this->session->userdata('role')=='1'){
  //          $this->data['module']    = 'doctor';
  //          $this->data['page'] = 'accounts';

  //          $user_currency=get_user_currency();
  //          $user_currency_code=$user_currency['user_currency_code'];
  //          $user_currency_rate=$user_currency['user_currency_rate'];

  //          $currency_option = (!empty($user_currency_code))?$user_currency_code:default_currency_code();
  //          $rate_symbol = currency_code_sign($currency_option);

  //          $this->data['currency_symbol']=$rate_symbol;

  //          $this->data['balance'] = $this->accounts->get_balance($user_id);
  //          $this->data['requested'] = $this->accounts->get_requested($user_id);
  //          $this->data['earned'] = $this->accounts->get_earned($user_id);
  //          $this->data['account_details'] = $this->accounts->get_account_details($user_id);
  //          $this->load->vars($this->data);
  //          $this->load->view($this->data['theme'].'/template');
  //        }else if($this->session->userdata('role')=='6'){
  //          $this->data['module']    = 'doctor';
  //          $this->data['page'] = 'accounts';

  //          $user_currency=get_user_currency();
  //          $user_currency_code=$user_currency['user_currency_code'];
  //          $user_currency_rate=$user_currency['user_currency_rate'];

  //          $currency_option = (!empty($user_currency_code))?$user_currency_code:settings('default_currency');
  //          $rate_symbol = currency_code_sign($currency_option);
  //          $this->data['currency_symbol']=$rate_symbol;
  //          $this->data['balance'] =  round($this->accounts->get_balance($user_id));
  //          $this->data['requested'] = $this->accounts->get_requested($user_id);
  //          $this->data['earned'] = $this->accounts->get_earned($user_id);
  //          $this->data['account_details'] = $this->accounts->get_account_details($user_id);
  //          $this->load->vars($this->data);
  //          $this->load->view($this->data['theme'].'/template');
  //        }
  //        else if($this->session->userdata('role')=='5'){
  //          $this->data['module']    = 'pharmacy';
  //          $this->data['page'] = 'accounts';

  //          $user_currency=get_user_currency();
  //          $user_currency_code=$user_currency['user_currency_code'];
  //          $user_currency_rate=$user_currency['user_currency_rate'];

  //          $currency_option = (!empty($user_currency_code))?$user_currency_code:settings('default_currency');
  //          $rate_symbol = currency_code_sign($currency_option);

  //          $this->data['currency_symbol']=$rate_symbol;

  //          $this->data['balance'] =  round($this->accounts->get_balance_pharmacy($user_id));
  //          $this->data['requested'] = $this->accounts->get_requested($user_id);
  //          $this->data['earned'] = $this->accounts->get_earned($user_id);
  //          $this->data['account_details'] = $this->accounts->get_account_details($user_id);
  //          $this->load->vars($this->data);
  //          $this->load->view($this->data['theme'].'/template');
  //        }
  //        else if($this->session->userdata('role')=='4'){
  //          $this->data['module']    = 'lab';
  //          $this->data['page'] = 'accounts';

  //          $user_currency=get_user_currency();
  //          $user_currency_code=$user_currency['user_currency_code'];
  //          $user_currency_rate=$user_currency['user_currency_rate'];

  //          $currency_option = (!empty($user_currency_code))?$user_currency_code:settings('default_currency');
  //          $rate_symbol = currency_code_sign($currency_option);

  //          $this->data['currency_symbol']=$rate_symbol;

  //          $this->data['balance'] = round($this->accounts->get_balance_lab($user_id));
  //          $this->data['requested'] = $this->accounts->get_requested($user_id);
  //          $this->data['earned'] = $this->accounts->get_earned($user_id);
  //          $this->data['account_details'] = $this->accounts->get_account_details($user_id);
  //          $this->load->vars($this->data);
  //          $this->load->view($this->data['theme'].'/template');
  //        }
  //        else
  //        {
  //          $this->data['module']    = 'patient';
  //          $this->data['page'] = 'accounts';

  //          $user_currency=get_user_currency();
  //          $user_currency_code=$user_currency['user_currency_code'];
  //          $user_currency_rate=$user_currency['user_currency_rate'];

  //          $currency_option = (!empty($user_currency_code))?$user_currency_code:default_currency_code();
  //          $rate_symbol = currency_code_sign($currency_option);

  //          $this->data['currency_symbol']=$rate_symbol;

  //          $this->data['balance'] = $this->accounts->get_patient_balance($user_id);
  //          $this->data['requested'] = $this->accounts->get_requested($user_id);
  //          $this->data['earned'] = $this->accounts->get_earned($user_id);
  //          $this->data['account_details'] = $this->accounts->get_account_details($user_id);
  //          $this->load->vars($this->data);
  //          $this->load->view($this->data['theme'].'/template');
  //        }
  // }

  public function doctor_accounts_list()
  {
    $user_id = $this->session->userdata('user_id');
    $list = $this->accounts->get_datatables($user_id);
    $data = array();
    $no = $_POST['start'];
    $a = 1;

    foreach ($list as $ll =>  $account) {

      $patient_profileimage = (!empty($account['patient_profileimage']) && file_exists($account['patient_profileimage'])) ? base_url() . $account['patient_profileimage'] : base_url() . 'assets/img/user.png';

      $tax_amount = $account['tax_amount'] + $account['transcation_charge'];

      $amount = ($account['total_amount']) - ($tax_amount);
      // print_r($account['total_amount']);
      $patient_currency = $account['currency_code'];
      $commission = !empty(settings("commission")) ? settings("commission") : "0";
      if ($this->session->userdata('role') == '4')
        $commission = !empty(settings("lab_commission")) ? settings("lab_commission") : "0";
      // print_r($commission);exit();
      if ($this->session->userdata('role') == '5')
        $commission = !empty(settings("pharmacy_commission")) ? settings("pharmacy_commission") : "0";
      $commission_charge = ($amount * ($commission / 100));

      if ($account['request_status'] == '6') {

        // print_r($amount);

        $total_amount = ($amount);
      } else {
        $total_amount = ($amount - $commission_charge);
      }

      // print_r($commission_charge);

      $user_currency = get_user_currency();
      $user_currency_code = $user_currency['user_currency_code'];
      $user_currency_rate = $user_currency['user_currency_rate'];

      $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
      $rate_symbol = currency_code_sign($currency_option);

      $org_amount = get_doccure_currency($total_amount, $patient_currency, $user_currency_code);

      $cls = '';
      $appt = $this->db->get_where('appointments', array('payment_id' => $account['id']))->row_array();
      /*if($appt['approved']==1 && $appt['appointment_status']==0 && $appt['call_end_status']==0 && $appt['review_status']==0) {
$cls = 'd-none';
}
if($appt['approved']==1 && $appt['appointment_status']==1 && $appt['call_end_status']==1) {
$cls = 'd-none';
}*/

      $fromdatetime = '';
      $can_send_request = 0;
      $can_add_wallet = 0;
      $appoint_status = '';
      if ($this->session->userdata('role') == 1) {
        if ($appt && $appt['time_zone']) {
          $current_timezone = $appt['time_zone'];
          $old_timezone = $this->session->userdata('time_zone');
          $fromdatetime = converToTz($appt["from_date_time"], $old_timezone, $current_timezone);
        } else {
          if ($appt && $appt["from_date_time"]) {
            $fromdatetime = $appt["from_date_time"];
          }
        }
        if ($appt &&  $appt['approved'] == 1 && $appt['appointment_status'] == '1' && $appt['call_status'] == 1) {
          $appoint_status = '<span class="badge badge-success">' . $this->language['lg_completed'] . '</span>';
          $can_add_wallet = 1;
        }
        if ($appt && $appt['appointment_status'] == '0' && $appt['call_status'] == 0 && ($fromdatetime > date('Y-m-d H:i:s'))) {
          $appoint_status = '<span class="badge badge-warning">' . $this->language['lg_new1'] . '</span>';
        }
        if ($appt && $appt['call_status'] == 0 && $appt['approved'] == 1 &&  (($fromdatetime < date('Y-m-d H:i:s')))) {
          $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_missed'] . '</span>';
          $can_send_request = 1;
        }
        if ($appt && $appt['appointment_status'] == '0' && $appt['approved'] == 1 && $appt['call_status'] == 0 && empty($fromdatetime)) {
          $appoint_status = '<span class="badge badge-primary">' . $this->language['lg_pending2'] . '</span>';
        }
        if ($appt && $appt['approved'] == 0) {
            // die("cancelled");
          $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_cancelled'] . ' </span>';
          $can_send_request = 1;
        }
        if ($appt && $appt['approved'] == 3) {
            //  die("approved");
         $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_reschedule'] . ' </span>';
         $can_send_request = 1;
       }
         
      }


      if ($this->session->userdata('role') == 5) {
        $order_details = $this->db->get_where('orders', array('order_id' => $account['order_id']))->row_array();

        if ($order_details && $order_details['order_status'] == 'completed') {
          $appoint_status = '<span class="badge badge-success">' . $this->language['lg_completed'] . '</span>';
          $can_add_wallet = 1;
        } else if ($order_details && $order_details['order_status'] == 'shipped') {
          $appoint_status = '<span class="badge badge-primary">' . $this->language['lg_shipped'] . '</span>';
        } else if ($order_details && $order_details['order_status'] == 'accepted') {
          $appoint_status = '<span class="badge badge-success">' . $this->language['lg_accepted'] . '</span>';
        } else if ($order_details && $order_details['order_status'] == 'rejected') {
          $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_rejected'] . ' </span>';
        } else if ($order_details && $order_details['order_status'] == 'pending') {
          $appoint_status = '<span class="badge badge-primary">' . $this->language['lg_pending2'] . ' </span>';
        }
      }
      if ($this->session->userdata('role') == 4) {
        $lab_details = $this->db->get_where('lab_payments', array('order_id' => $account['order_id']))->row_array();

        if ($lab_details && $lab_details['cancel_status'] == 'Approved') {
          $appoint_status = '<span class="badge badge-success">' . $this->language['lg_completed'] . '</span>';
          $can_add_wallet = 1;
        } else if ($lab_details && $lab_details['cancel_status'] == 'New') {
          $appoint_status = '<span class="badge badge-primary">' . $this->language['lg_new1'] . '</span>';
        } else if ($lab_details && $lab_details['cancel_status'] == 'Cancelled') {
          $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_cancelled'] . ' </span>';
        }
      }

      switch ($account['request_status']) {
        case '0':
          /*$status='<span class="badge badge-primary">'.$this->language['lg_new1'].'</span>';
          // $action='<a href="javascript:void(0)" onclick="send_request(\''.$account['id'].'\',\'1\')" class="btn btn-sm bg-info-light ">'.$this->language['lg_send_request'].'</a>';
          // $action='<a href="javascript:void(0)" onclick="send_request(\''.$account['id'].'\',\'2\')" class="btn btn-sm bg-info-light">'.$this->language['lg_send_request'].'</a>';

          if($this->session->userdata('role')=='1')
          {
            $action='<a href="javascript:void(0)" onclick="send_request(\''.$account['id'].'\',\'2\')" class="btn btn-sm bg-info-light">'.$this->language['lg_payment_receive'].'</a>';
          }
          else
          {
           $action='<a href="javascript:void(0)" onclick="send_request(\''.$account['id'].'\',\'1\')" class="btn btn-sm bg-info-light">'.$this->language['lg_send_request'].'</a>'; 
          }*/

          $status = $appoint_status;
          $action = '';
          if ($can_add_wallet == 1) {
            $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'2\')" class="btn btn-sm bg-info-light">' . $this->language['lg_add_to_balance'] . '</a>';
          }

          break;
        case '1':
          $status = '<span class="badge badge-warning">' . $this->language['lg_waiting_for_pat'] . '</span>';
          $action = '';
          break;
        case '2':
          // $status='<span class="badge badge-success">'.$this->language['lg_approved'].'</span>';
          $status = '<span class="badge badge-success">' . $this->language['lg_payment_receive'] . '</span>';
          $action = '';
          break;
        case '3':
          $status = '<span class="badge badge-warning">' . $this->language['lg_payment_request'] . '</span>';
          $action = '';
          break;
        case '4':
          $status = '<span class="badge badge-success">' . $this->language['lg_payment_receive'] . '</span>';
          $action = '';
          break;
        case '5':
          $status = '<span class="badge badge-danger">' . $this->language['lg_cancelled'] . '</span>';
          // $action='<a href="javascript:void(0)" onclick="send_request(\''.$account['id'].'\',\'2\')" class="btn btn-sm bg-info-light">'.$this->language['lg_send_request'].'</a>';
          $action = '';
          break;

        case '6':
          $status = '<span class="badge badge-warning">' . $this->language['lg_waiting_for_app'] . '</span>';
          $action = '';
          break;

        case '7':
          $status = '<span class="badge badge-info">' . $this->language['lg_refund'] . '</span>';
          $action = '';
          break;

        case '8':
          $status = '<span class="badge badge-danger">' . $this->language['lg_cancelled'] . '</span>';
          $action = '';
          break;

        default:
          //$status='<span class="badge badge-primary">'.$this->language['lg_new1'].'</span>';
          //$action='<a href="javascript:void(0)" onclick="send_request(\''.$account['id'].'\',\'1\')" class="btn btn-sm bg-info-light">'.$this->language['lg_send_request'].'</a>';
          $status = $appoint_status;
          $action = '';
          break;
      }


      $no++;
      $row = array();
      $row[] = $no;
      $row[] = date('d M Y', strtotime($account['payment_date']));
      $row[] = '<h2 class="table-avatar">
                  <a href="#" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $patient_profileimage . '" alt="User Image"></a>
                  <a href="#">' . ucfirst($account['patient_name']) . ' </a>
                </h2>';




      $row[] = $rate_symbol . number_format($org_amount, 2, '.', ',');
      $row[] = $status;
      $row[] = $action;


      $data[] = $row;
    }


    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->accounts->count_all($user_id),
      "recordsFiltered" => $this->accounts->count_filtered($user_id),
      "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

  public function patient_refund_request()
  {
    $user_id = $this->session->userdata('user_id');
    $list = $this->accounts->get_refund_datatables($user_id);
    $data = array();
    $no = $_POST['start'];
    $a = 1;

    foreach ($list as $account) {

      $patient_profileimage = (!empty($account['patient_profileimage']) && file_exists($account['patient_profileimage'])) ? base_url() . $account['patient_profileimage'] : base_url() . 'assets/img/user.png';

      $tax_amount = $account['tax_amount'] + $account['transcation_charge'];

      $amount = ($account['total_amount']) - ($tax_amount);
      $patient_currency = $account['currency_code'];
      $commission = !empty(settings("commission")) ? settings("commission") : "0";
      $commission_charge = ($amount * ($commission / 100));
      $total_amount = ($amount);

      $user_currency = get_user_currency();
      $user_currency_code = $user_currency['user_currency_code'];
      $user_currency_rate = $user_currency['user_currency_rate'];

      $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
      $rate_symbol = currency_code_sign($currency_option);

      $org_amount = get_doccure_currency($total_amount, $patient_currency, $user_currency_code);



      switch ($account['request_status']) {
        case '6':
          $status = '<span class="badge badge-warning">' . $this->language['lg_waiting_for_app'] . '</span>';
          $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'7\')" class="btn btn-sm bg-info-light">' . $this->language['lg_approve1'] . '</a> <a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'8\')" class="btn btn-sm bg-info-light">' . $this->language['lg_cancel'] . '</a>';
          break;

        default:
          $status = '<span class="badge badge-primary">' . $this->language['lg_new1'] . '</span>';
          $action = '';
          break;
      }


      $no++;
      $row = array();
      $row[] = $no;
      $row[] = date('d M Y', strtotime($account['payment_date']));
      $row[] = '<h2 class="table-avatar">
                  <a href="#" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $patient_profileimage . '" alt="User Image"></a>
                  <a href="#">' . ucfirst($account['patient_name']) . ' </a>
                </h2>';




      $row[] = $rate_symbol . number_format($org_amount, 2, '.', ',');
      $row[] = $status;
      $row[] = $action;


      $data[] = $row;
    }


    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->accounts->refund_count_all($user_id),
      "recordsFiltered" => $this->accounts->refund_count_filtered($user_id),
      "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

  public function send_request()
  {
    $text = '';
    $payment_id = $this->input->post('id');
    $status = $this->input->post('status');
    $this->db->where('id', $payment_id);
    $this->db->update('payments', array('request_status' => $status));

    $touserid = $this->accounts->notify_touserid($payment_id);
    $appoitAt = $this->accounts->apptDetails($payment_id);

    if ($status == 7) {
      $this->db->where('payment_id', $payment_id)->update('appointments', array('appointment_status' => 1));
    }

    if ($status == 2 || $status == 7) {
      $text = "has approved payment refund request of <i style='color:#6495ed'>'" . $appoitAt . "'</i> appointment of";
    } else if ($status == 5 || $status == 8) {
      $text = "has rejected payment refund request of <i style='color:#6495ed'>'" . $appoitAt . "'</i> appointment of";
    } else if ($status == 1 || $status == 6) {
      $text = "has sent payment refund request for <i style='color:#6495ed'>'" . $appoitAt . "'</i> appointment to";
    }
    $notification = array(
      'user_id' => $this->session->userdata('user_id'),
      'to_user_id' => $touserid,
      'type' => "Payment Request",
      'text' => $text,
      'created_at' => date("Y-m-d H:i:s"),
      'time_zone' => $this->timezone
    );
    //print_r($notification);
    $this->db->insert('notification', $notification);
  }

  public function doctor_request()
  {
    $user_id = $this->session->userdata('user_id');
    $list = $this->accounts->get_doctor_request_datatables($user_id);
    $data = array();
    $no = $_POST['start'];
    $a = 1;

    foreach ($list as $account) {

      $doctor_profileimage = (!empty($account['doctor_profileimage']) && file_exists($account['doctor_profileimage'])) ? base_url() . $account['doctor_profileimage'] : base_url() . 'assets/img/user.png';

      $tax_amount = $account['tax_amount'] + $account['transcation_charge'];

      $amount = ($account['total_amount']) - ($tax_amount);
      $patient_currency = $account['currency_code'];
      $commission = !empty(settings("commission")) ? settings("commission") : "0";
      $commission_charge = ($amount * ($commission / 100));
      $total_amount = ($amount - $commission_charge);

      $user_currency = get_user_currency();
      $user_currency_code = $user_currency['user_currency_code'];
      $user_currency_rate = $user_currency['user_currency_rate'];

      $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
      $rate_symbol = currency_code_sign($currency_option);

      $org_amount = get_doccure_currency($total_amount, $patient_currency, $user_currency_code);

      switch ($account['request_status']) {

        case '1':
          $status = '<span class="badge badge-warning">' . $this->language['lg_waiting_for_app'] . '</span>';
          $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'2\')" class="btn btn-sm bg-info-light">' . $this->language['lg_approve1'] . '</a> <a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'5\')" class="btn btn-sm bg-info-light">' . $this->language['lg_cancel'] . '</a>';
          break;
        case '5':
          $status = '<span class="badge badge-danger">' . $this->language['lg_cancelled'] . '</span>';
          $action = '';
          break;

        default:
          $status = '';
          $action = '';
          break;
      }


      $no++;
      $row = array();
      $row[] = date('d M Y', strtotime($account['payment_date']));
      $row[] = '<h2 class="table-avatar">
                  <a target="_blank" href="' . base_url() . 'doctor-preview/' . $account['doctor_username'] . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="User Image">
                  </a>
                  <a target="_blank" href="' . base_url() . 'doctor-preview/' . $account['doctor_username'] . '">' . $this->language['lg_dr'] . ' ' . ucfirst($account['doctor_name']) . '</a>
                </h2>';




      $row[] = $rate_symbol . number_format($org_amount, 2, '.', ',');
      $row[] = $status;
      $row[] = $action;


      $data[] = $row;
    }


    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->accounts->doctor_request_count_all($user_id),
      "recordsFiltered" => $this->accounts->doctor_request_filtered($user_id),
      "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

  public function patient_accounts_list()
  {
    $user_id = $this->session->userdata('user_id');
    $list = $this->accounts->get_patient_accounts_datatables($user_id);
    $data = array();
    $no = $_POST['start'];
    $a = 1;

    foreach ($list as $account) {

      $doctor_profileimage = (!empty($account['doctor_profileimage']) && file_exists($account['doctor_profileimage'])) ? base_url() . $account['doctor_profileimage'] : base_url() . 'assets/img/user.png';

      $tax_amount = $account['tax_amount'] + $account['transcation_charge'];

      $amount = ($account['total_amount']) - ($tax_amount);
      $patient_currency = $account['currency_code'];
      $commission = !empty(settings("commission")) ? settings("commission") : "0";
      if ($account['role'] == '4')
        $commission = !empty(settings("lab_commission")) ? settings("lab_commission") : "0";
      if ($account['role'] == '5')
        $commission = !empty(settings("pharmacy_commission")) ? settings("pharmacy_commission") : "0";

      $commission_charge = ($amount * ($commission / 100));
      $total_amount = ($amount);

      $user_currency = get_user_currency();
      $user_currency_code = $user_currency['user_currency_code'];
      $user_currency_rate = $user_currency['user_currency_rate'];

      $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
      $rate_symbol = currency_code_sign($currency_option);

      $org_amount = get_doccure_currency($total_amount, $patient_currency, $user_currency_code);

      //$cls = '';
      $appt = $this->db->get_where('appointments', array('payment_id' => $account['id']))->row_array();

      /*if($appt['approved']==1 && $appt['appointment_status']==0 && $appt['call_end_status']==0 && $appt['review_status']==0) {
$cls = 'd-none';
}
if($appt['approved']==1 && $appt['appointment_status']==1 && $appt['call_end_status']==1) {
$cls = 'd-none';
}*/

      /*$curdate = date('Y-m-d');
		$dateval = $appt['appointment_date'];
		
		$titStatus = 'lg_new1';
		$badgetxt = 'primary';
		$adminrequest = 0;
		if($appt['approved']==1 && $appt['call_end_status']==1 && ($appt['appointment_status']==0 || $appt['appointment_status']==1 )) {
			$titStatus = 'lg_appointment_com';
			$badgetxt = 'info';
			$adminrequest = 1;
		}
		if($appt['approved']==1 && $appt['call_end_status']==0 && $appt['appointment_status']==1  && strtotime($dateval) <= strtotime($curdate)) {
			$titStatus = 'lg_missed';
			$badgetxt = 'secondary';
		}
		if($appt['approved']==0) {
			$titStatus = 'lg_cancelled';
			$badgetxt = 'danger';
			$cls = 'd-none';
		}*/

      $appoint_status = '';
      $can_send_request = 0;

      if ($appt && $appt['time_zone']) {
        $current_timezone = $appt['time_zone'];
        $old_timezone = $this->timezone;
        $fromdatetime = converToTz($appt["from_date_time"], $old_timezone, $current_timezone);
      } else {
        if ($appt && $appt["from_date_time"]) {
          $fromdatetime = $appt["from_date_time"];
        }
      }
      if ($appt && $appt['appointment_status'] == '1' && $appt['call_status'] == 1) {
        $appoint_status = '<span class="badge badge-success">' . $this->language['lg_completed'] . '</span>';
      }
      if ($appt && $appt['appointment_status'] == '0' && $appt['call_status'] == 0 && ($fromdatetime > date('Y-m-d H:i:s'))) {
        $appoint_status = '<span class="badge badge-warning">' . $this->language['lg_new1'] . '</span>';
      }
      if ($appt && $appt['call_status'] == 0 && $appt['approved'] == 1 && ($fromdatetime < date('Y-m-d H:i:s'))) {
        $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_missed'] . '</span>';
        $can_send_request = 1;
      }
      if ($appt && $appt['appointment_status'] == '0' && $appt['approved'] == 1 && $appt['call_status'] == 0 && empty($fromdatetime)) {
        $appoint_status = '<span class="badge badge-primary">' . $this->language['lg_pending2'] . '</span>';
      }
      if ($appt && $appt['approved'] == 0) {
        $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_cancelled'] . ' </span>';
        $can_send_request = 1;
      }
      if ($appt && $appt['approved'] == 3) {
            //  die("approved");
         $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_reschedule'] . ' </span>';
         $can_send_request = 1;
       }

      if ($account['role'] == 4) {
        $lab_details = $this->db->get_where('lab_payments', array('order_id' => $account['order_id']))->row_array();
        if ($lab_details && $lab_details['cancel_status'] == 'Approved') {
          $appoint_status = '<span class="badge badge-success">' . $this->language['lg_completed'] . '</span>';
        } else if ($lab_details && $lab_details['cancel_status'] == 'New') {
          $appoint_status = '<span class="badge badge-primary">' . $this->language['lg_new1'] . '</span>';
        } else if ($lab_details && $lab_details['cancel_status'] == 'Cancelled') {
          $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_cancelled'] . ' </span>';
          $can_send_request = 1;
        }
      }

      if ($account['role'] == 5) {
        $order_details = $this->db->get_where('orders', array('order_id' => $account['order_id']))->row_array();

        if ($order_details && $order_details['order_status'] == 'completed') {
          $appoint_status = '<span class="badge badge-success">' . $this->language['lg_completed'] . '</span>';
        } else if ($order_details && $order_details['order_status'] == 'shipped') {
          $appoint_status = '<span class="badge badge-primary">' . $this->language['lg_shipped'] . '</span>';
        } else if ($order_details && $order_details['order_status'] == 'accepted') {
          $appoint_status = '<span class="badge badge-success">' . $this->language['lg_accepted'] . '</span>';
        } else if ($order_details && $order_details['order_status'] == 'rejected') {
          $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_rejected'] . ' </span>';
        } else if ($order_details && $order_details['order_status'] == 'pending') {
          $appoint_status = '<span class="badge badge-primary">' . $this->language['lg_pending2'] . ' </span>';
        }
      }


      switch ($account['request_status']) {

        case '0':
          //$status='<span class="badge badge-primary">'.$this->language['lg_new1'].'</span>';
          $status = $appoint_status;
          $action = '';
          if ($can_send_request == 1) {
            $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'6\')" class="btn btn-sm bg-info-light">' . $this->language['lg_send_request'] . '</a>';
          }
          break;
        case '1':
          $status = '<span class="badge badge-warning">' . $this->language['lg_waiting_for_app'] . '</span>';
          $action = '';
          break;
        case '2':
          if ($account['role'] == '4' || $account['role'] == '5')
            $status = '<span class="badge badge-success">' . $this->language['lg_payment_receive'] . '</span>';
          else
            $status = '<span class="badge badge-success">' . $this->language['lg_appointment_com'] . '</span>';
          $action = '';
          break;
        case '3':
          // $status='<span class="badge badge-warning">'.$this->language['lg_appointment_com'].'</span>';
          if ($account['role'] == '4' || $account['role'] == '5')
            $status = '<span class="badge badge-success">' . $this->language['lg_payment_receive'] . '</span>';
          else
            $status = '<span class="badge badge-warning">' . $this->language['lg_appointment_com'] . '</span>';
          $action = '';
          break;
        case '4':
          // $status='<span class="badge badge-success">'.$this->language['lg_appointment_com'].'</span>';
          if ($account['role'] == '4' || $account['role'] == '5')
            $status = '<span class="badge badge-success">' . $this->language['lg_payment_receive'] . '</span>';
          else
            $status = '<span class="badge badge-success">' . $this->language['lg_appointment_com'] . '</span>';
          $action = '';
          break;
        case '5':
          //$status='<span class="badge badge-primary">'.$this->language['lg_new1'].'</span>';
          $status = $appoint_status;
          $action = '';
          if ($can_send_request == 1) {
            $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'6\')" class="btn btn-sm bg-info-light">' . $this->language['lg_send_request'] . '</a>';
          }
          break;

        case '6':
          // $status='<span class="badge badge-warning">'.$this->language['lg_waiting_for_doc'].'</span>';
          if ($account['role'] == '4')
            $status = '<span class="badge badge-warning">' . $this->language['lg_waiting_for_lab'] . '</span>';
          elseif ($account['role'] == '5')
            $status = '<span class="badge badge-warning">' . $this->language['lg_waiting_for_pha'] . '</span>';
          else
            $status = '<span class="badge badge-warning">' . $this->language['lg_waiting_for_doc'] . '</span>';
          $action = '';
          $action = '';
          break;

        case '7':
          $status = '<span class="badge badge-success">' . $this->language['lg_refund_approved'] . '</span>';
          $action = '';
          break;

        case '8':
          $status = '<span class="badge badge-danger">' . $this->language['lg_cancelled'] . '</span>';
          $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'6\')" class="btn btn-sm bg-info-light">' . $this->language['lg_send_request'] . '</a>';
          break;


        default:
          //$status='<span class="badge badge-primary">'.$this->language['lg_new1'].'</span>';
          $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'1\')" class="btn btn-sm bg-info-light">' . $this->language['lg_send_request'] . '</a>';
          break;
      }


      $no++;
      $row = array();
      $row[] = $no;
      $row[] = date('d M Y', strtotime($account['payment_date']));

      $user_role = '';
      $img = '';

      if ($account['role'] == '1') {
        $user_role = $this->language['lg_dr'];
        $img = '<a target="_blank" href="' . base_url() . 'doctor-preview/' . $account['doctor_username'] . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="User Image">
                  </a>';
      }

      $row[] = '<h2 class="table-avatar">
                  ' . $img . '
                  <a target="_blank" href="' . base_url() . 'doctor-preview/' . $account['doctor_username'] . '">' . $user_role . ' ' . ucfirst($account['doctor_name']) . '</a>
                </h2>';




      $row[] = $rate_symbol . number_format($org_amount, 2, '.', ',');
      // $row[]=$account['total_amount'];
      $row[] = $status;
      $row[] = $action;


      $data[] = $row;
    }


    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->accounts->patient_accounts_count_all($user_id),
      "recordsFiltered" => $this->accounts->patient_accounts_filtered($user_id),
      "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }


  public function get_account_details()
  {
    $user_id = $this->session->userdata('user_id');
    $data = $this->accounts->get_account_details($user_id);
    echo json_encode($data);
  }


  public function add_account_details()
  {
    $inputdata = array();

    $inputdata['user_id'] = $this->session->userdata('user_id');
    $inputdata['bank_name'] = $this->input->post('bank_name');
    $inputdata['branch_name'] = $this->input->post('branch_name');
    $inputdata['account_no'] = $this->input->post('account_no');
    $inputdata['account_name'] = $this->input->post('account_name');
    $inputdata['account_currency'] = $this->input->post('account_currency');
    $inputdata['routing_number'] = $this->input->post('routing_number');
    $inputdata['ach_number'] = $this->input->post('ach_number');
    $inputdata['swift'] = $this->input->post('swift');
    $inputdata['bank_address'] =  $this->input->post('bank_address');
    $inputdata['bank_country'] =  $this->input->post('bank_country');
    $inputdata['account_type'] =  $this->input->post('account_type');


    $already_exits = $this->db->where('user_id', $inputdata['user_id'])->get('account_details')->num_rows();
    // print_r($already_exits);exit();
    if ($already_exits == 0) {
      // print_r($inputdata);exit();
      $this->db->insert('account_details', $inputdata);
      $result = ($this->db->affected_rows() != 1) ? false : true;
    } else {
      // check user is modified any data
      $check_edit_status = $this->db->where($inputdata)->get('account_details')->num_rows();
      if ($check_edit_status) {
        $datas['result'] = 'false';
        $datas['status'] = $this->language['lg_accounts_update'];
        goto OUTPUT;
      }

      $this->db->where('user_id', $inputdata['user_id']);
      $this->db->update('account_details', $inputdata);
      $result1 = ($this->db->affected_rows() != 1) ? false : true;
    }


    if (@$result == true && $result1 == false) {
      $datas['result'] = 'true';
      $datas['status'] = $this->language['lg_account_details'];
    } else if (@$result == false && $result1 == true) {
      $datas['result'] = 'true';
      $datas['status'] = $this->language['lg_account_details_up'];
    } else {
      $datas['result'] = 'false';
      $datas['status'] = $this->language['lg_edit_req'];
    }
    OUTPUT:
    echo json_encode($datas);
  }

  public function get_insurance_details()
  {
    $user_id = $this->session->userdata('user_id');
    $data = $this->accounts->get_insurance_details($user_id);
    // echo '<pre>';
    // print_r($data);
    // echo '</pre>';
    // die("fasdf");
    echo json_encode($data);
  }

  public function add_insurance_details()
  {
    $inputdata = array();

    $inputdata['user_id'] = $this->session->userdata('user_id');
    $inputdata['insurance_company'] = $this->input->post('insurance_company');
    $inputdata['insurance_card_number'] = $this->input->post('insurance_card_number');
    $inputdata['insurance_type'] = $this->input->post('insurance_type');
    $inputdata['insurance_expiration'] = $this->input->post('insurance_expiration');
    $inputdata['benefits'] = $this->input->post('benefits');
    $inputdata['phone_number'] = $this->input->post('phone_number');
    $inputdata['dependants'] = $this->input->post('dependants');
    $inputdata['dob'] = $this->input->post('dob');
    


    $already_exits = $this->db->where('user_id', $inputdata['user_id'])->get('insurance_details')->num_rows();
    // print_r($already_exits);exit();
    if ($already_exits == 0) {
      // print_r($inputdata);exit();
      $this->db->insert('insurance_details', $inputdata);
      $result = ($this->db->affected_rows() != 1) ? false : true;
    } else {
      // check user is modified any data
      $check_edit_status = $this->db->where($inputdata)->get('insurance_details')->num_rows();
      if ($check_edit_status) {
        $datas['result'] = 'false';
        $datas['status'] = $this->language['lg_accounts_update'];
        goto OUTPUT;
      }

      $this->db->where('user_id', $inputdata['user_id']);
      $this->db->update('insurance_details', $inputdata);
      $result1 = ($this->db->affected_rows() != 1) ? false : true;
    }


    if (@$result == true && $result1 == false) {
      $datas['result'] = 'true';
      $datas['status'] = $this->language['lg_account_details'];
    } else if (@$result == false && $result1 == true) {
      $datas['result'] = 'true';
      $datas['status'] = $this->language['lg_account_details_up'];
    } else {
      $datas['result'] = 'false';
      $datas['status'] = $this->language['lg_edit_req'];
    }
    OUTPUT:
    echo json_encode($datas);
  }

  public function payment_request()
  {
    $id=$this->session->userdata('user_id');
    $role=$this->session->userdata('role');

     if($role==1){
    $result=$this->commission->get_commission_by_doc_id($id);}
    if($role==2){
      $result=$this->commission_pat->get_commission_by_pat_id($id);}
    
    if($role==4){
      $result=$this->commission_lab->get_commission_by_lab_id($id);
    }
    if($role==5){
      $result=$this->commission_phar->get_commission_by_phar_id($id);
    }
    if($role==6){
      $result=$this->commission_clinic->get_commission_by_clinic_id($id);
    }

   
    $commison_to_be_substracted =$result->comm_rate;
    //echo $commison_to_be_substracted;
    //die("checking role");
    
    $inputdata = array();
    $balance = 0;
    $user_currency = get_user_currency();
    $user_currency_code = $user_currency['user_currency_code'];
    $user_currency_rate = $user_currency['user_currency_rate'];


    $inputdata['user_id'] = $this->session->userdata('user_id');
    $inputdata['payment_type'] = $this->input->post('payment_type');
    $inputdata['request_amount'] = $this->input->post('request_amount');
    $inputdata['request_amount']=$inputdata['request_amount']-($inputdata['request_amount']*$commison_to_be_substracted*0.01);
    //echo $inputdata['request_amount'];
   // die("di9e ");
    $inputdata['currency_code'] = $user_currency_code;
    $inputdata['description'] = $this->input->post('description');
    $inputdata['request_date'] = date('Y-m-d H:i:s');


    $already_exits = $this->db->where('user_id', $inputdata['user_id'])->get('account_details')->num_rows();
    if ($already_exits == 0) {
      $datas['result'] = 'false';
      $datas['status'] = $this->language['lg_please_enter_ac'];
      echo json_encode($datas);
      return false;
    }

    if ($inputdata['payment_type'] == '1') {
      $balance = $this->accounts->get_balance($inputdata['user_id']);
    }

    if ($inputdata['payment_type'] == '2') {
      /** @var int $balance */
      $balance = $this->accounts->get_patient_balance($inputdata['user_id']);
    }

    $requested = $this->accounts->get_requested($inputdata['user_id']);
    $earned = $this->accounts->get_earned($inputdata['user_id']);

    $balance = $balance - ($earned + $requested);

    if ($balance < $inputdata['request_amount']) {
      $datas['result'] = 'false';
      $datas['status'] = $this->language['lg_please_enter_va3'];
      echo json_encode($datas);
      return false;
    }

    $this->db->insert('payment_request', $inputdata);
    $result = ($this->db->affected_rows() != 1) ? false : true;
    if (@$result == true) {

      $notification = array(
        'user_id' => $this->session->userdata('user_id'),
        'to_user_id' => 0,
        'type' => "Payment Request",
        'text' => "has raised payment request ",
        'created_at' => date("Y-m-d H:i:s"),
        'time_zone' => $this->timezone
      );
      $this->db->insert('notification', $notification);

      $datas['result'] = 'true';
      $datas['status'] = $this->language['lg_payment_request1'];
    } else {
      $datas['result'] = 'false';
      $datas['status'] = $this->language['lg_payment_request2'];
    }

    echo json_encode($datas);
  }
}