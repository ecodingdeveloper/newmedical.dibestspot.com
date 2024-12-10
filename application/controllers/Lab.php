<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . '../vendor/stripe/stripe-php/init.php');
require_once(APPPATH . '../vendor/autoload.php');

class Lab extends CI_Controller
{
  public $data;
  public $session;
  public $timezone;
  public $lang;
  public $language;
  public $lab;
  public $input;
  public $book;
  public $db;
  public $paypal_lib;
  public $upload;
  public $home;
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
    $this->data['module']    = 'lab';
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

    $this->load->model('lab_model', 'lab');
  }
  public function index()
  {
    $this->data['page'] = 'lab_tests';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function lab_list()
  {
    $user_id = $this->session->userdata('user_id');
    $list = $this->lab->get_datatables($user_id);
    // print_r($this->db->last_query());exit();
    $data = array();
    $no = $_POST['start'];
    $a = 1;
    foreach ($list as $lab_tests) {
      $val = '';
      $cls = '';
      if ($lab_tests['status'] == '1') {
        $val = 'Active';
        $cls = 'bg-success-light';
      }
      if ($lab_tests['status'] == '0') {
        $val = 'Inactive';
        $cls = 'bg-danger-light';
      }
      $profileimage = (!empty($lab_tests['profileimage'])) ? base_url() . $lab_tests['profileimage'] : base_url() . 'assets/img/user.png';

      $no++;
      $row   = array();
      $row[] = $no;
      $row[] = '<h2 class="table-avatar">
                        <a href="#" class="avatar avatar-sm mr-2">
                        <img class="avatar-img rounded-circle" src="' . $profileimage . '" alt="User Image">
                        </a>
                        <a href="#">' . ucfirst($lab_tests['lab_name']) . '</a>
                    </h2>';
      $row[] = $lab_tests['lab_test_name'];
      $row[] = $lab_tests['duration'];
      $row[] = $lab_tests['currency_code'];
      $row[] = $lab_tests['amount'];
      $row[] = $lab_tests['description'];
      $row[] = date('d M Y', strtotime($lab_tests['created_date']));
      $row[] = '
                        <a class="btn btn-sm bg-success-light" onclick="edit_lab_test(' . $lab_tests['id'] . ')" href="javascript:void(0)">
                            <i class="fe fe-pencil"></i> Edit
                        </a>
                        <a class="btn btn-sm ' . $cls . '" href="javascript:void(0)" onclick="delete_lab_test(' . $lab_tests['id'] . ', ' . $lab_tests['status'] . ')">
                        ' . $val . '
                      </a>
                        ';

      $data[] = $row;
    }


    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->lab->count_all($user_id),
      "recordsFiltered" => $this->lab->count_filtered($user_id),
      "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

  public function lab_test_save()
  {
    $id = $this->input->post('id');
    $method = $this->input->post('method');
    $data['lab_id'] = $this->session->userdata('user_id');
    $data['lab_test_name'] = $this->input->post('lab_test_name');
    $data['amount'] = $this->input->post('amount');
    $data['duration'] = $this->input->post('duration');
    $data['description'] = $this->input->post('description');
    $data['created_date'] = date('Y-m-d H:i:s');
    $data['status'] = 1;
    $user_currency = get_user_currency();
    $user_currency_code = (!empty($user_currency['user_currency_code'])) ? $user_currency['user_currency_code'] : default_currency_code();
    $data['currency_code'] = $user_currency_code;

    if ($method == 'update') {
      $this->db->where('lab_test_name', $data['lab_test_name']);
      $this->db->where('id !=', $id);
      $this->db->where('status', 1);
      $this->db->where('lab_id', $this->session->userdata('user_id'));
      $query = $this->db->get('lab_tests');
      if ($query->num_rows() > 0) {
        $datas['status'] = 500;
        $datas['msg'] = 'Lab test name already exists!';
      } else {
        $this->db->where('id', $id);
        $this->db->update('lab_tests', $data);
        if ($this->db->affected_rows() > 0) {
          $datas['status'] = 200;
          $datas['msg'] = 'Lab test updated successfully';
        } else {
          $datas['status'] = 500;
          $datas['msg'] = 'Lab test update failed!';
        }
      }
    } else {
      $this->db->where('lab_test_name', $data['lab_test_name']);
      $this->db->where('status', 1);
      $this->db->where('lab_id', $this->session->userdata('user_id'));
      $query = $this->db->get('lab_tests');
      if ($query->num_rows() > 0) {
        $datas['status'] = 500;
        $datas['msg'] = 'Lab test already exits!';
      } else {
        $this->db->insert('lab_tests', $data);
        $result = ($this->db->affected_rows() != 1) ? false : true;

        if (@$result == true) {
          $datas['status'] = 200;
          $datas['msg'] = 'Lab test added successfully';
        } else {
          $datas['status'] = 200;
          $datas['msg'] = 'Lab test added failed!';
        }
      }
    }
    echo json_encode($datas);
  }

  public function lab_test_edit($id)
  {
    $data = $this->lab->get_lab_test_by_id($id);
    echo json_encode($data);
  }

  public function lab_test_delete()
  {
    $id = $this->input->post('id');
    $status = $this->input->post('status');
    if ($status == 1) {
      $status = 0;
    } else {
      $status = 1;
    }
    $data = array(
      'status' => $status,
    );
    $this->db->where('id', $id);
    $this->db->update('lab_tests', $data);
    $datas['status'] = 200;
    $datas['msg'] = 'Lab test deleted successfully';
    echo json_encode($datas);
  }

  public function set_booked_session_lab_test()
  {
    $response = array();
    $this->load->model('home_model', 'home');
    $tax = !empty(settings("tax")) ? settings("tax") : "0";
    $lab_username = $this->input->post('lab_username');
    $booking_details = json_decode($this->input->post('booking_ids'));
    $lab_test_date = $this->input->post('lab_test_date');
    $lab_id = $this->input->post('lab_id');
    $lab_details = $this->home->get_lab_details(urldecode($lab_username));
    $booking_ids = implode(',', $booking_details);
    $amount_det = $this->lab->get_lab_test_amount($booking_ids);

    $amount = $amount_det['total_amt'];
    $transcation_charge_amt = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";
    if ($transcation_charge_amt > 0) {
      $transcation_charge = ($amount * ($transcation_charge_amt / 100));
    } else {
      $transcation_charge = 0;
    }
    $total_amount = $amount + $transcation_charge;
    $tax_amount = (round($total_amount) * $tax / 100);
    $total_amount = $total_amount + $tax_amount;
    $total_amount = $total_amount;
    $transcation_charge = $transcation_charge;
    $tax_amount = $tax_amount;

    $user_currency = get_user_currency();
    $user_currency_code = $user_currency['user_currency_code'];
    $user_currency_rate = $user_currency['user_currency_rate'];

    $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $user_currency_code;

    $rate_symbol = currency_code_sign($currency_option);

    $total_amount = get_doccure_currency($total_amount, $lab_details['currency_code'], $user_currency_code);
    $amount = get_doccure_currency($amount, $lab_details['currency_code'], $user_currency_code);
    $transcation_charge = get_doccure_currency($transcation_charge, $lab_details['currency_code'], $user_currency_code);
    $tax_amount = get_doccure_currency($tax_amount, $lab_details['currency_code'], $user_currency_code);
    $booking_ids_price = implode(',', json_decode($this->input->post('booking_ids_price')));

    $new_data = array(
      'lab_details' => $lab_details,
      'lab_id' => $lab_id,
      'amount' => $amount,
      'transcation_charge' => $transcation_charge,
      'tax_amount' => $tax_amount,
      'total_amount' => $total_amount,
      'booking_ids' => $booking_ids,
      'lab_test_date' => $lab_test_date,
      'currency_code' => $currency_option,
      'currency_symbol' => $rate_symbol,
      'booking_ids_price' => $booking_ids_price,
      'tax_value' => $tax,
      'transcation_charge_percent' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
    );
    $this->session->set_userdata('lab_test_book_details', $new_data);

    $this->db->insert('session_details', array('session_data' => json_encode($this->session->userdata)));
    $session_id    = $this->db->insert_id();

    $new_data['custom_value'] = $session_id;
    $this->session->set_userdata($new_data);
    $this->db->where('id', $session_id);
    $this->db->update('session_details', array('session_data' => json_encode($this->session->userdata)));

    $response['status'] = 200;
    echo json_encode($response);
  }

  public function checkout()
  {

    if ($this->session->userdata('role') == '2') {
      $this->load->model('book_appoinments_model', 'book');
      $this->data['page'] = 'checkout';
      $lab_test_booking_details = $this->session->userdata('lab_test_book_details');
      $this->data['lab_booking_details'] = $lab_test_booking_details['lab_details'];
      $this->data['patients'] = $this->book->get_user_details($this->session->userdata('user_id'));
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
    } else {
      redirect(base_url() . 'dashboard');
    }
  }

  public function stripe_payment()
  {

    $stripe_option = !empty(settings("stripe_option")) ? settings("stripe_option") : "";
    $stripe_secert_key = "";
    if ($stripe_option == '1') {
      $stripe_secert_key = !empty(settings("sandbox_rest_key")) ? settings("sandbox_rest_key") : "";
    }
    if ($stripe_option == '2') {
      $stripe_secert_key = !empty(settings("live_rest_key")) ? settings("live_rest_key") : "";
    }

    $booking_details_session = $this->session->userdata('lab_test_book_details');
    $amount = $booking_details_session['total_amount'];
    $currency_code = $booking_details_session['currency_code'];

    $amount = get_doccure_currency($amount, $currency_code, 'USD');

    $amount = number_format($amount, 2, '.', '');

    \Stripe\Stripe::setApiKey($stripe_secert_key);

    $intent = null;

    try {
      if (isset($_POST['payment_method_id'])) {
        # Create the PaymentIntent
        $intent = \Stripe\PaymentIntent::create([
          'payment_method' => $_POST['payment_method_id'],
          'amount' => ($amount * 100),
          'currency' => 'USD',
          'confirmation_method' => 'manual',
          'confirm' => true,
        ]);
      }

      if (isset($_POST['payment_intent_id'])) {
        $intent = \Stripe\PaymentIntent::retrieve(
          $_POST['payment_intent_id']
        );
        $intent->confirm();
      }

      $this->generateResponse($intent);
    } catch (\Stripe\Exception\ApiErrorException $e) {

      $results = array('status' => 500, 'message' => $e->getMessage());
      echo json_encode($results);
    }
  }

  private function generateResponse($intent)
  {

    if (($intent->status == 'requires_action' || $intent->status == 'requires_source_action') && $intent->next_action->type == 'use_stripe_sdk') {

      $results = array('status' => 201, 'requires_action' => true, 'payment_intent_client_secret' => $intent->client_secret);
      echo json_encode($results);
    } else if ($intent->status == 'succeeded') {

      $this->stripe_pay($intent);
    } else {

      $results = array('status' => 500, 'message' => 'Transaction failure!.Please try again', 'error' => $intent);
      echo json_encode($results);
    }
  }

  public function stripe_pay($intent)
  {

    $booking_details_session = $this->session->userdata('lab_test_book_details');

    $lab_id = $booking_details_session['lab_id'];
    $booking_ids = $booking_details_session['booking_ids'];
    $lab_test_date = $booking_details_session['lab_test_date'];
    $tax_amount = $booking_details_session['tax_amount'];
    $transcation_charge = $booking_details_session['transcation_charge'];
    $currency_code = $booking_details_session['currency_code'];

    $amount = $booking_details_session['total_amount'];
    $amount = number_format($amount, 2, '.', '');

    $transaction_status = json_encode($intent);
    $txnid = time() . rand();

    /* Get Invoice id */
    $invoice = $this->db->order_by('id', 'desc')->limit(1)->get('lab_payments')->row_array();
    if (empty($invoice)) {
      $invoice_id = 1;
    } else {
      $invoice_id = $invoice['id'];
    }
    $invoice_id++;
    $invoice_no = 'I0000' . $invoice_id;

    $order_id = 'OD' . time() . rand();
    // Store the Payment details
    $payments_data = array(
      'lab_id' => $lab_id,
      'patient_id' => $this->session->userdata('user_id'),
      'booking_ids' => $booking_ids,
      'invoice_no' => $invoice_no,
      'lab_test_date' => $lab_test_date,
      'total_amount' => $amount,
      'currency_code' => $currency_code,
      'txn_id' => $txnid,
      'order_id' => $order_id,
      'transaction_status' => $transaction_status,
      'payment_type' => 'Stripe',
      'tax' => !empty(settings("tax")) ? settings("tax") : "0",
      'tax_amount' => $tax_amount,
      'transcation_charge' => $transcation_charge,
      'payment_status' => 1,
      'payment_date' => date('Y-m-d H:i:s'),
    );
    $this->db->insert('lab_payments', $payments_data);
    $payment_id = $this->db->insert_id();

    $payments_data = array(
      'user_id' => $this->session->userdata('user_id'),
      'doctor_id' => $lab_id,
      'invoice_no' => $invoice_no,
      'per_hour_charge' => $booking_details_session['amount'],
      'total_amount' => $amount,
      'currency_code' => $currency_code,
      'txn_id' => $txnid,
      'order_id' => $order_id,
      'transaction_status' => $transaction_status,
      'payment_type' => 'Stripe',
      'tax' => !empty(settings("tax")) ? settings("tax") : "0",
      'tax_amount' => $tax_amount,
      'transcation_charge' => $transcation_charge,
      'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
      'payment_status' => 1,
      'payment_date' => date('Y-m-d H:i:s'),
    );
    $this->db->insert('payments', $payments_data);

    $notification = array(
      'user_id' => $this->session->userdata('user_id'),
      'to_user_id' => $lab_id,
      'type' => "Lab Appointment",
      'text' => "has booked lab appointment to",
      'created_at' => date("Y-m-d H:i:s"),
      'time_zone' => $this->timezone
    );
    $this->db->insert('notification', $notification);

    // Unset all sessions..
    $this->session->unset_userdata('lab_test_book_details');

    $results = array('status' => 200);
    echo json_encode($results);
  }

  public function paypal_pay()
  {
    $booking_details_session = $this->session->userdata('lab_test_book_details');
    $lab_id = $booking_details_session['lab_id'];
    $this->load->library('paypal_lib');
    // Set variables for paypal form
    $returnURL = base_url() . 'lab/success';
    $cancelURL = base_url() . 'lab/failure';
    $notifyURL = base_url() . 'lab/ipn';
    $paypal_email = '';
    $paypal_option = !empty(settings("paypal_option")) ? settings("paypal_option") : "";
    if ($paypal_option == '1') {
      $paypal_email = !empty(settings("sandbox_email")) ? settings("sandbox_email") : "";
    }
    if ($paypal_option == '2') {
      $paypal_email = !empty(settings("live_email")) ? settings("live_email") : "";
    }

    $name = $this->input->post('name');
    $amount = $this->input->post('amount');
    $currency_code = $this->input->post('currency_code');
    $productinfo = $this->input->post('productinfo');
    $patient_id = $this->session->userdata('user_id');

    $this->db->insert('session_details', array('session_data' => json_encode($this->session->userdata)));
    $session_id    = $this->db->insert_id();

    $amount = get_doccure_currency($amount, $currency_code, 'USD');

    $amount = number_format($amount, 2, '.', '');

    // Add fields to paypal form
    $this->paypal_lib->add_field('return', $returnURL);
    $this->paypal_lib->add_field('cancel_return', $cancelURL);
    $this->paypal_lib->add_field('notify_url', $notifyURL);
    $this->paypal_lib->add_field('item_name', $productinfo);
    $this->paypal_lib->add_field('custom', $patient_id);
    $this->paypal_lib->add_field('item_number',  $session_id);
    $this->paypal_lib->add_field('amount',  $amount);
    $this->paypal_lib->add_field('currency_code', 'USD');
    $this->paypal_lib->add_field('business', $paypal_email);
    $this->paypal_lib->paypal_auto_form();
  }

  public function success()
  {
    $booking_details_session = $this->session->userdata('lab_test_book_details');
    $lab_id = $booking_details_session['lab_id'];
    $booking_ids = $booking_details_session['booking_ids'];
    $lab_test_date = $booking_details_session['lab_test_date'];
    $tax_amount = $booking_details_session['tax_amount'];
    $transcation_charge = $booking_details_session['transcation_charge'];
    $currency_code = $booking_details_session['currency_code'];
    /** @var float $amount */
    $amount = $booking_details_session['total_amount'];
    $amount = number_format($amount, 2, '.', '');

    if (isset($_POST["txn_id"]) && !empty($_POST["txn_id"])) {
      $paypalInfo =  $this->input->post();
      // $txnid= $paypalInfo['txn_id']; 
      // $doctor_id=$paypalInfo['item_number']; 
      // $patient_id=$paypalInfo['custom'];
      // $amount=$paypalInfo['payment_gross'];
    } else {
      $paypalInfo =  $this->input->get();
      // $txnid= $paypalInfo['tx']; 
      // $doctor_id=$paypalInfo['item_number'];
      // $patient_id=$paypalInfo['custom'];
      // $amount=$paypalInfo['payment_gross'];
    }

    $transaction_status = json_encode($paypalInfo);
    $txnid = $paypalInfo['txn_id'];
    $status = 'success';

    if ($status == 'success') {


      /* Get Invoice id */
      $invoice = $this->db->order_by('id', 'desc')->limit(1)->get('lab_payments')->row_array();
      if (empty($invoice)) {
        $invoice_id = 1;
      } else {
        $invoice_id = $invoice['id'];
      }
      $invoice_id++;
      $invoice_no = 'I0000' . $invoice_id;
      /** @var float $amount */
      $amount = number_format($amount, 2, '.', '');

      $order_id = 'OD' . time() . rand();

      // Store the Payment details
      $payments_data = array(
        'lab_id' => $lab_id,
        'patient_id' => $this->session->userdata('user_id'),
        'booking_ids' => $booking_ids,
        'invoice_no' => $invoice_no,
        'lab_test_date' => $lab_test_date,
        'total_amount' => $amount,
        'currency_code' => $currency_code,
        'txn_id' => $txnid,
        'order_id' => $order_id,
        'transaction_status' => $transaction_status,
        'payment_type' => 'PayPal',
        'tax' => !empty(settings("tax")) ? settings("tax") : "0",
        'tax_amount' => $tax_amount,
        'transcation_charge' => $transcation_charge,
        'payment_status' => 1,
        'payment_date' => date('Y-m-d H:i:s'),
      );
      $this->db->insert('lab_payments', $payments_data);


      $payment_data = array(
        'user_id' => $this->session->userdata('user_id'),
        'doctor_id' => $lab_id,
        'invoice_no' => $invoice_no,
        'per_hour_charge' => $booking_details_session['amount'],
        'total_amount' => $amount,
        'currency_code' => $currency_code,
        'txn_id' => $txnid,
        'order_id' => $order_id,
        'transaction_status' => $transaction_status,
        'payment_type' => 'PayPal',
        'tax' => !empty(settings("tax")) ? settings("tax") : "0",
        'tax_amount' => $tax_amount,
        'transcation_charge' => $transcation_charge,
        'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
        'payment_status' => 1,
        'payment_date' => date('Y-m-d H:i:s'),
      );

      $this->db->insert('payments', $payment_data);
      // print_r($this->db->last_query());exit();

      // Notification
      $notification = array(
        'user_id' => $this->session->userdata('user_id'),
        'to_user_id' => $lab_id,
        'type' => "Lab Appointment",
        'text' => "has booked lab appointment to",
        'created_at' => date("Y-m-d H:i:s"),
        'time_zone' => $this->timezone
      );
      $this->db->insert('notification', $notification);


      // Unset all sessions..
      $this->session->unset_userdata('lab_test_book_details');
      $this->session->set_flashdata('success_message', 'Your lab test booked successfully.');
      redirect(base_url() . 'dashboard');
    } else {
      $this->session->set_flashdata('error_message', 'Lab booking Failled.');
      redirect(base_url() . 'dashboard');
    }
  }

  public function failure()
  {
    $this->session->set_flashdata('error_message', 'Lab booking Failled');
    redirect(base_url() . 'dashboard');
  }

  public function appointments()
  {
    if ($this->session->userdata('role') == '4') {
      $this->data['page'] = 'appointments';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
    } else {
      redirect(base_url() . 'dashboard');
    }
  }

  public function get_appointments($id)
  {
    $data = $this->lab->get_appointments($id);
    echo json_encode($data);
  }

  public function lab_appointment_list()
  {
    $lab_id = $this->session->userdata('user_id');
    $list = $this->lab->get_appointment_details($lab_id);
    $data = array();
    $no = $_POST['start'];
    $a = 1;
    foreach ($list as $lab_payments) {
      $val = 'Failled';
      $cls = '';
      if ($lab_payments['payment_status'] == '1') {
        $val = 'Success';
      }
      $profileimage = (!empty($lab_payments['profileimage'])) ? base_url() . $lab_payments['profileimage'] : base_url() . 'assets/img/user.png';

      $no++;
      $row   = array();
      $row[] = $a++;
      $row[] = '<h2 class="table-avatar">
                        <a href="#" class="avatar avatar-sm mr-2">
                        <img class="avatar-img rounded-circle" src="' . $profileimage . '" alt="User Image">
                        </a>
                        <a href="#">' . ucfirst($lab_payments['patient_name']) . '</a>
                    </h2>';
      $test_name = "";
      $array_ids = explode(',', $lab_payments['test_ids']);
      foreach ($array_ids as $key => $value) {
        $this->db->select('*');
        $this->db->where('id', $value);
        $query = $this->db->get('lab_tests');
        $result = $query->row_array();
        if ($key > 0) {
          $test_name .= ",";
        }
        $test_name .= $result['lab_test_name'];
      }

      $row[] = $test_name;

      $row[] = date('d M Y', strtotime($lab_payments['lab_test_date']));
      $row[] = convert_to_user_currency($lab_payments['total_amount']);
      $row[] = date('d M Y', strtotime($lab_payments['payment_date']));
      $row[] = $val;
      $new_orders = '';
      $new = '';
      $approved = '';
      $cancelled = '';
      if ($lab_payments['cancel_status'] == 'New') {
        $new = 'selected';
      }
      if ($lab_payments['cancel_status'] == 'Cancelled') {
        $cancelled = 'selected';
      }
      if ($lab_payments['cancel_status'] == 'Approved') {
        $approved = 'selected';
      }
      $row[] = '<div class="actions">
                   <select name="lab_appointment_status" class="lab_appointment_status" id="' . $lab_payments['id'] . '">
                     <option value="New" ' . $new . '>New</option>
                     <option value="Cancelled" ' . $cancelled . '>Cancel</option>
                     <option value="Approved" ' . $approved . '>Approve</option>
                   </select>
                 </div>';
      $row[] = '
                        <a class="btn btn-sm bg-success-light" onclick="upload_lab_docs(' . $lab_payments['id'] . ')" href="javascript:void(0)">
                            <i class="fe fe-pencil"></i> Upload
                        </a>
                        
                        ';

      $data[] = $row;
    }
    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->lab->appointments_count_all($lab_id),
      "recordsFiltered" => $this->lab->appointments_count_filtered($lab_id),
      "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }


  // public function lab_upload_docs(){

  //   $data = array();
  //   $appointment_id=$this->input->post('appointment_id');
  //   $countfiles = count($_FILES['user_file']['name']);

  //   for($i=0;$i<$countfiles;$i++){

  //     if(!empty($_FILES['user_file']['name'][$i])){

  //       $path = "uploads/lab_result/".$appointment_id;

  //       if(!is_dir($path)){
  //         mkdir($path,0777,true);        
  //       }

  //       $path = $path."/";

  //       $target_file =$path . basename($_FILES["user_file"]["name"][$i]);
  //       $file_type = pathinfo($target_file,PATHINFO_EXTENSION);

  //       $config['upload_path']   = './'.$path;
  //       $config['allowed_types'] = '*';   
  //       $this->load->library('upload',$config);
  //       $this->upload->initialize($config);

  //       $_FILES["files"]["name"] = $_FILES["user_file"]["name"][$i];
  //       $_FILES["files"]["type"] = $_FILES["user_file"]["type"][$i];
  //       $_FILES["files"]["tmp_name"] = $_FILES["user_file"]["tmp_name"][$i];
  //       $_FILES["files"]["error"] = $_FILES["user_file"]["error"][$i];
  //       $_FILES["files"]["size"] = $_FILES["user_file"]["size"][$i];

  //       if($this->upload->do_upload('files')){ 
  //         echo $this->upload->data('file_name');  
  //       }else{
  //         echo json_encode(array('error'=>$this->upload->display_errors()));
  //       }
  //     }
  //   }

  //   $response['msg']="Uploaded successfully";
  //   $response['status']=200; 
  //   echo json_encode($response);

  // }


  public function lab_upload_docs()
  {

    $data = array();
    $appointment_id = $this->input->post('appointment_id');
    $countfiles = count($_FILES['user_file']['name']);

    for ($i = 0; $i < $countfiles; $i++) {

      if (!empty($_FILES['user_file']['name'][$i])) {

        $path = "uploads/lab_result/" . $appointment_id;

        if (!is_dir($path)) {
          mkdir($path, 0777, true);
        }

        $path = $path . "/";

        $target_file = $path . basename($_FILES["user_file"]["name"][$i]);
        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

        $config['upload_path']   = './' . $path;
        $config['allowed_types'] = '*';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $_FILES["files"]["name"] = $_FILES["user_file"]["name"][$i];
        $_FILES["files"]["type"] = $_FILES["user_file"]["type"][$i];
        $_FILES["files"]["tmp_name"] = $_FILES["user_file"]["tmp_name"][$i];
        $_FILES["files"]["error"] = $_FILES["user_file"]["error"][$i];
        $_FILES["files"]["size"] = $_FILES["user_file"]["size"][$i];

        if ($this->upload->do_upload('files')) {
          //echo $this->upload->data('file_name');  
          $where = array('id' => $appointment_id);
          $touser = $this->db->select('patient_id')->get_where('lab_payments', $where)->row()->patient_id;
          $notification = array(
            'user_id' => $this->session->userdata('user_id'),
            'to_user_id' => $touser,
            'type' => "Lab Appointment",
            'text' => "has uploaded lab reports of",
            'created_at' => date("Y-m-d H:i:s"),
            'time_zone' => $this->timezone
          );
          $this->db->insert('notification', $notification);

          $response['msg'] = "Reports uploaded successfully";
          $response['status'] = 200;
          echo json_encode($response);
        } else {
          //echo json_encode(array('error'=>$this->upload->display_errors()));

          $response['msg'] = $this->upload->display_errors();
          $response['status'] = 500;
          echo json_encode($response);
        }
      }
    }

    // $response['msg']="Uploaded successfully";
    // $response['status']=200; 
    // echo json_encode($response);

  }

  public function change_appointment_status()
  {

    $id = $this->input->post('id');
    $status = $this->input->post('status');

    $appointmentDetails = $this->db->select('*')->from('lab_payments')->where('id', $id)->get()->row_array();

    $notification = array(
      'user_id' => $this->session->userdata('user_id'),
      'to_user_id' => $appointmentDetails['patient_id'],
      'type' => "Appointment",
      'text' => "Appointment status updated to",
      'created_at' => date("Y-m-d H:i:s"),
      'time_zone' => $this->session->userdata('time_zone')
    );

    $this->db->insert('notification', $notification);


    $this->db->where('id', $id)->update('lab_payments', array('cancel_status' => $status));
    echo 'success';
  }
}
