<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . '../vendor/stripe/stripe-php/init.php');
require_once(APPPATH . '../vendor/autoload.php');

use Twilio\Rest\Client;
use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;
use Razorpay\Api\Api;

class Book_appoinments extends CI_Controller
{

  public $data;
  public $session;
  public $timezone;
  public $input;
  public $db;
  public $lang;
  public $language;
  public $appoinment;
  public $book;
  public $tokbox_apiKey;
  public $home;
  public $tokbox_apiSecret;
  public $sendemail;

  public function __construct()
  {

    parent::__construct();

    //print_r($_SESSION);exit;
    if ($this->session->userdata('admin_id')) {
      redirect(base_url() . 'home');
    }


    $this->data['theme']     = 'web';
    $this->data['module']    = 'patient';
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

    $this->load->model('home_model', 'home');
    $this->load->model('book_appoinments_model', 'book');

    $this->tokbox_apiKey = !empty(settings("apiKey")) ? settings("apiKey") : "";
    $this->tokbox_apiSecret = !empty(settings("apiSecret")) ? settings("apiSecret") : "";
  }

  public function book($username)
  {
    //  die("sd");
    
    if (empty($this->session->userdata('user_id'))) {
      redirect(base_url() . 'signin');
    }
   
    if ($this->session->userdata('role') != '2') {
      redirect(base_url() . 'dashboard');
     
    } 
    else {
      $user_detail = user_detail($this->session->userdata('user_id'));
      // echo "<pre>";
      // echo $this->session->userdata('role');
      // print_r ($user_detail);
      // die("h");  
      if ($user_detail['is_updated'] == '0' || $user_detail['is_verified'] == '0') {
        $this->session->set_flashdata('error_message', $this->language['lg_please_update_p']);
        redirect(base_url() . 'dashboard');
      } 
    else {
        $this->data['page'] = 'book_appoinments';
        $this->data['doctors'] = $this->home->get_doctor_details(urldecode($username));
        $this->session->set_userdata('doctor_id', $this->data['doctors']['userid']);
        $this->data['schedule_date'] = date('d/m/Y');
        $this->data['selected_date'] = date('Y-m-d');
        // echo "<pre>";
        // // echo $this->session->userdata('role');
        // print_r ($this->data);
        // echo '</pre>';
        // die("h"); 
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
      }
    }
  }

  public function get_schedule_from_date()
  {

    $schedule_date = $this->input->post('schedule_date');
    $doctor_id = $this->input->post('doctor_id');
    $day = date('D', strtotime(str_replace('/', '-', $schedule_date)));
    $day_id = 0;
    switch ($day) {
      case 'Sun':
        $day_id = 1;
        break;
      case 'Mon':
        $day_id = 2;
        break;
      case 'Tue':
        $day_id = 3;
        break;
      case 'Wed':
        $day_id = 4;
        break;
      case 'Thu':
        $day_id = 5;
        break;
      case 'Fri':
        $day_id = 6;
        break;
      case 'Sat':
        $day_id = 7;
        break;
      default:
        $day_id = 0;
        break;
    }

    $data['schedule'] =  $this->book->get_schedule_timings($doctor_id, $day_id);
    $data['schedule_date'] = $schedule_date;
    $data['language'] = language();
    echo $this->load->view('web/modules/patient/book_appoinments_view', $data, TRUE);

  }

  function checkIfExist($fromTime, $toTime, $input)
  {
    //echo $fromTime." - ".$toTime." - ".$input."\n";
    $fromDateTime = DateTime::createFromFormat("!H:i", $fromTime);
    $toDateTime = DateTime::createFromFormat('!H:i', $toTime);
    $inputDateTime = DateTime::createFromFormat('!H:i', $input);
    if ($fromDateTime > $toDateTime) $toDateTime->modify('+1 day');
    return ($fromDateTime <= $inputDateTime && $inputDateTime < $toDateTime) || ($fromDateTime <= $inputDateTime->modify('+1 day') && $inputDateTime <= $toDateTime);
  }

  public function set_booked_session()
  {

    $response = array();
    $appointment_details =  json_decode($this->input->post('appointment_details'));

    $starttime  = $appointment_details[0]->appoinment_start_time;
    $endtime    = $appointment_details[0]->appoinment_end_time;
    $selectdate = $appointment_details[0]->appoinment_date;

    $from_rh = date('H:i', strtotime($starttime));
    $to_rh   = date('H:i', strtotime($endtime));

    $patient_bookings = $this->book->patient_booking_time($selectdate);
    //print_r($patient_bookings);

    $user_noslot = '';
    if (count($patient_bookings) > 0) {
      foreach ($patient_bookings as  $b => $bookedtime) {
        $ufromTime = date('H:i', strtotime($bookedtime['appointment_time']));
        $utoTime   = date('H:i', strtotime($bookedtime['appointment_end_time']));
        $user_noslot   = $this->checkIfExist($ufromTime, $utoTime, $from_rh);
        if ($user_noslot != 1) {
          $user_noslot = $this->checkIfExist($ufromTime, $utoTime, $to_rh);
          if ($user_noslot == 1) break;
        } else {
          break;
        }
      }
    }
    if ($user_noslot == 1) {
      $response['status'] = 500;
      $response['message'] = $this->language['lg_another_booking'];
      echo json_encode($response);
    } else {

      if (date('Y-m-d H:i:s') < $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_start_time) {

        $booked_session = get_booked_session($appointment_details[0]->appoinment_session, $appointment_details[0]->appoinment_token, $appointment_details[0]->appoinment_date.' '.$appointment_details[0]->appoinment_start_time, $this->session->userdata('doctor_id'));


        if ($booked_session >= 1) {
          $response['status'] = 500;
          $response['message'] = $this->language['lg_this_token_alre'];
          echo json_encode($response);
        } else {

          if ($_POST['price_type'] == 'Free' || trim($_POST['hourly_rate']) == '') {
            $appointment_details =  json_decode($this->input->post('appointment_details'));
            $this->session->set_userdata('appointment_details', $appointment_details);
            $appointment_id = $this->book_free_appoinment();
            $response['status'] = 202;
            $response['appoinment_id'] = $appointment_id;
            echo json_encode($response);
          } else {

            $tax = !empty(settings("tax")) ? settings("tax") : "0";
            $appointment_details =  json_decode($this->input->post('appointment_details'));
            $hourly_rate = $this->input->post('hourly_rate');
            $doctor_username = $this->input->post('doctor_username');

            $doctor_details = user_detail($this->session->userdata('doctor_id'));

            $user_currency = get_user_currency();
            $user_currency_code = $user_currency['user_currency_code'];
            $user_currency_rate = $user_currency['user_currency_rate'];
            $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $doctor_details['currency_code'];
            $rate_symbol = currency_code_sign($currency_option);
            if (!empty($this->session->userdata('user_id'))) {
              $rate = get_doccure_currency($doctor_details['amount'], $doctor_details['currency_code'], $user_currency_code);
            } else {
              $rate = $doctor_details['amount'];
            }
            $rate = number_format($rate, 2, '.', '');
            $this->data['amount'] = $rate_symbol . '' . $rate;


            $amount = $rate;
            $amount = doubleval($amount);

            $transcation_charge_amt = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";
            $transcation_charge_amt = doubleval($transcation_charge_amt);
            if ($transcation_charge_amt > 0) {
              $transcation_charge = ($amount * ($transcation_charge_amt / 100));
              $transcation_charge = number_format($transcation_charge, 2, '.', '');
            } else {
              $transcation_charge = 0;
            }

            $total_amount = $amount + $transcation_charge;

            $tax_amount = ($total_amount * $tax / 100);
            $tax_amount = number_format($tax_amount, 2, '.', '');

            $total_amount = $total_amount + $tax_amount;
            $total_amount = number_format($total_amount, 2, '.', '');

            $new_data = array(
              'amount' => $amount,
              'transcation_charge' => $transcation_charge,
              'tax' => $tax,
              'tax_amount' => $tax_amount,
              'total_amount' => $total_amount,
              'hourly_rate' => $rate,
              'currency_code' => $currency_option,
              'currency_symbol' => $rate_symbol,
              'discount' => 0,
              'doctor_role_id' => $_POST['doctor_role_id'],
              'doctor_id' => $this->session->userdata('doctor_id')
            );
            $this->session->set_userdata($new_data);
            $this->session->set_userdata('appointment_details', $appointment_details);

            $this->db->insert('session_details', array('session_data' => json_encode($this->session->userdata)));
            $session_id    = $this->db->insert_id();

            $new_data['custom_value'] = $session_id;
            $this->session->set_userdata($new_data);
            $this->db->where('id', $session_id);
            $this->db->update('session_details', array('session_data' => json_encode($this->session->userdata)));

            $response['status'] = 200;
            echo json_encode($response);
          }
        }
      } else {
        $response['status'] = 500;
        $response['message'] = $this->language['lg_this_token_alre1'];
        echo json_encode($response);
      }
    }
  }

  public function checkout()
  {
    if ($this->session->userdata('role') == '1') {
      redirect(base_url() . 'dashboard');
    } else if (!empty($this->session->userdata('appointment_details'))) {
      $doctor_id = $this->session->userdata('doctor_id');
      $this->data['page'] = 'checkout';
      $this->data['doctor_id'] = $doctor_id;
      $this->data['doctors'] = $this->book->get_user_details($doctor_id);
      $this->data['patients'] = $this->book->get_user_details($this->session->userdata('user_id'));
      $this->data['appointment_details'] = $this->session->userdata('appointment_details');
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
    } else {
      redirect(base_url() . 'signin');
    }
  }

  public function book_free_appoinment()
  {

    // $paymentdata = array(
    //   'user_id' => $this->session->userdata('user_id'),
    //   'doctor_id' => $this->session->userdata('doctor_id'),
    //   'payment_status' => 0,
    //   'payment_date' => date('Y-m-d H:i:s'),
    //   'currency_code' => 'USD'
    // );
    // $this->db->insert('payments',$paymentdata);
    // $payment_id = $this->db->insert_id();

    /* Get Invoice id */

    $invoice = $this->db->order_by('id', 'desc')->limit(1)->get('payments')->row_array();
    if (empty($invoice)) {
      $invoice_id = 0;
    } else {
      $invoice_id = $invoice['id'];
    }
    $invoice_id++;
    $invoice_no = 'I0000' . $invoice_id;
    $appointment_id = "";

    // Store the Payment details

    $payments_data = array(
      'user_id' => $this->session->userdata('user_id'),
      'doctor_id' => $this->session->userdata('doctor_id'),
      'invoice_no' => $invoice_no,
      'per_hour_charge' => 0,
      'total_amount' => 0,
      'currency_code' => "USD",
      'txn_id' => "",
      'order_id' => 'OD' . time() . rand(),
      'transaction_status' => "success",
      'payment_type' => 'Free Booking',
      'tax' => 0,
      'tax_amount' => 0,
      'transcation_charge' => 0,
      'payment_status' => 0,
      'payment_date' => date('Y-m-d H:i:s'),
    );
    $this->db->insert('payments', $payments_data);
    $payment_id = $this->db->insert_id();

    // Sending notification to mentor
    $doctor_id = $this->session->userdata('doctor_id');
    $appointment_details = $this->session->userdata('appointment_details');

    $opentok = new OpenTok($this->tokbox_apiKey, $this->tokbox_apiSecret);
    // An automatically archived session:
    $sessionOptions = array(
      // 'archiveMode' => ArchiveMode::ALWAYS,
      'mediaMode' => MediaMode::ROUTED
    );
    $new_session = $opentok->createSession($sessionOptions);
    // Store this sessionId in the database for later use
    $tokboxsessionId = $new_session->getSessionId();

    $tokboxtoken = $opentok->generateToken($tokboxsessionId);

    foreach ($appointment_details as $key => $value) {
      $appointmentdata['payment_id'] =  $payment_id;
      $appointmentdata['appointment_from'] = $this->session->userdata('user_id');
      $appointmentdata['appointment_to'] = $this->session->userdata('doctor_id');
      $appointmentdata['from_date_time'] = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_start_time;
      $appointmentdata['to_date_time'] = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_end_time;
      $appointmentdata['appointment_date'] = $appointment_details[0]->appoinment_date;
      $appointmentdata['appointment_time'] = $appointment_details[0]->appoinment_start_time;
      $appointmentdata['appointment_end_time'] = $appointment_details[0]->appoinment_end_time;
      $appointmentdata['appoinment_token'] = $appointment_details[0]->appoinment_token;
      $appointmentdata['appoinment_session'] = $appointment_details[0]->appoinment_session;
      $appointmentdata['type'] = $appointment_details[0]->type;
      $appointmentdata['payment_method'] = 'Online';
      $appointmentdata['tokboxsessionId'] = $tokboxsessionId;
      $appointmentdata['tokboxtoken'] = $tokboxtoken;
      $appointmentdata['paid'] = 1;
      $appointmentdata['approved'] = 1;
      $appointmentdata['time_zone'] = $appointment_details[0]->appoinment_timezone;
      $appointmentdata['created_date'] = date('Y-m-d H:i:s');
      $this->db->insert('appointments', $appointmentdata);
      $appointment_id = $this->db->insert_id();
      // $this->send_appoinment_mail($appointment_id);

      $notification = array(
        'user_id' => $this->session->userdata('user_id'),
        'to_user_id' => $this->session->userdata('doctor_id'),
        'type' => "Appointment",
        'text' => "has booked appointment to",
        'created_at' => date("Y-m-d H:i:s"),
        'time_zone' => $appointment_details[0]->appoinment_timezone
      );
      $this->db->insert('notification', $notification);
      if (settings('tiwilio_option') == '1') {
        $this->send_appoinment_sms($appointment_id);
      }
    }

    return $appointment_id;
  }

  public function stripe_payment()
  {
    $appointment_details = $this->session->userdata('appointment_details');

    $booked_session = get_booked_session($appointment_details[0]->appoinment_session, $appointment_details[0]->appoinment_token, $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_start_time, $this->session->userdata('doctor_id'));
    $stripe_secert_key = "";
    if ($booked_session >= 1) {
      $response['status'] = 500;
      $response['message'] = 'This token already booked';
      echo json_encode($response);
    } else {
      $stripe_option = !empty(settings("stripe_option")) ? settings("stripe_option") : "";
      if ($stripe_option == '1') {
        $stripe_secert_key = !empty(settings("sandbox_rest_key")) ? settings("sandbox_rest_key") : "";
      }
      if ($stripe_option == '2') {
        $stripe_secert_key = !empty(settings("live_rest_key")) ? settings("live_rest_key") : "";
      }

      $currency_code = $this->session->userdata('currency_code');

      $amount = get_doccure_currency($this->session->userdata('total_amount'), $currency_code, 'USD');

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
        # Display error on client
        $results = array('status' => 500, 'message' => $e->getMessage());
        echo json_encode($results);
      }
    }
  }

  private function generateResponse($intent)
  {
    # Note that if your API version is before 2019-02-11, 'requires_action'
    # appears as 'requires_source_action'.
    if (($intent->status == 'requires_action' || $intent->status == 'requires_source_action') && $intent->next_action->type == 'use_stripe_sdk') {
      # Tell the client to handle the action
      $results = array('status' => 201, 'requires_action' => true, 'payment_intent_client_secret' => $intent->client_secret);
      echo json_encode($results);
    } else if ($intent->status == 'succeeded') {
      # The payment didn’t need any additional actions and completed!
      # Handle post-payment fulfillment
      $this->stripe_pay($intent);
    } else {
      # Invalid status
      $results = array('status' => 500, 'message' => 'Transaction failure!.Please try again', 'error' => $intent);
      echo json_encode($results);
    }
  }

  public function stripe_pay($intent)
  {

    // print($intent);exit();

    $doctor_id = $this->session->userdata('doctor_id');

    $amount = $this->session->userdata('total_amount');
    $currency_code = $this->session->userdata('currency_code');

    $transaction_status = json_encode($intent);
    $txnid = time() . rand();
    $appointment_id = "";

    $opentok = new OpenTok($this->tokbox_apiKey, $this->tokbox_apiSecret);
    // An automatically archived session:
    $sessionOptions = array(
      // 'archiveMode' => ArchiveMode::ALWAYS,
      'mediaMode' => MediaMode::ROUTED
    );
    $new_session = $opentok->createSession($sessionOptions);
    // Store this sessionId in the database for later use
    $tokboxsessionId = $new_session->getSessionId();

    $tokboxtoken = $opentok->generateToken($tokboxsessionId);

    /* Get Invoice id */

    $invoice = $this->db->order_by('id', 'desc')->limit(1)->get('payments')->row_array();
    if (empty($invoice)) {
      $invoice_id = 0;
    } else {
      $invoice_id = $invoice['id'];
    }
    $invoice_id++;
    $invoice_no = 'I0000' . $invoice_id;

    // Store the Payment details

    // $amount=get_doccure_currency($amount,'USD',$currency_code);

    $amount = number_format($amount, 2, '.', '');

    $payments_data = array(
      'user_id' => $this->session->userdata('user_id'),
      'doctor_id' => $doctor_id,
      'invoice_no' => $invoice_no,
      'per_hour_charge' => $this->session->userdata('hourly_rate'),
      'total_amount' => $amount,
      'currency_code' => $currency_code,
      'txn_id' => $txnid,
      'order_id' => 'OD' . time() . rand(),
      'transaction_status' => $transaction_status,
      'payment_type' => 'Stripe',
      'tax' => !empty(settings("tax")) ? settings("tax") : "0",
      'tax_amount' => $this->session->userdata('tax_amount'),
      'transcation_charge' => $this->session->userdata('transcation_charge'),
      'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
      'payment_status' => 1,
      'payment_date' => date('Y-m-d H:i:s'),
    );
    $this->db->insert('payments', $payments_data);
    $payment_id = $this->db->insert_id();

    $appointment_details = $this->session->userdata('appointment_details');

    foreach ($appointment_details as $key => $value) {
      $appointmentdata['payment_id'] =  $payment_id;
      $appointmentdata['appointment_from'] = $this->session->userdata('user_id');
      $appointmentdata['appointment_to'] = $this->session->userdata('doctor_id');
      $appointmentdata['from_date_time'] = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_start_time;
      $appointmentdata['to_date_time'] = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_end_time;
      $appointmentdata['appointment_date'] = $appointment_details[0]->appoinment_date;
      $appointmentdata['appointment_time'] = $appointment_details[0]->appoinment_start_time;
      $appointmentdata['appointment_end_time'] = $appointment_details[0]->appoinment_end_time;
      $appointmentdata['appoinment_token'] = $appointment_details[0]->appoinment_token;
      $appointmentdata['appoinment_session'] = $appointment_details[0]->appoinment_session;
      // $appointmentdata['type'] = $appointment_details[0]->type;
      $appointmentdata['type'] = $this->language['lg_online'];
      $appointmentdata['payment_method'] = 'Stripe';
      $appointmentdata['tokboxsessionId'] = $tokboxsessionId;
      $appointmentdata['tokboxtoken'] = $tokboxtoken;
      $appointmentdata['paid'] = 1;
      $appointmentdata['approved'] = 1;
      $appointmentdata['time_zone'] = $appointment_details[0]->appoinment_timezone;
      $appointmentdata['created_date'] = date('Y-m-d H:i:s');
      $this->db->insert('appointments', $appointmentdata);
      $appointment_id = $this->db->insert_id();
      $notification = array(
        'user_id' => $this->session->userdata('user_id'),
        'to_user_id' => $this->session->userdata('doctor_id'),
        'type' => "Appointment",
        'text' => "has booked appointment to",
        'created_at' => date("Y-m-d H:i:s"),
        'time_zone' => $appointment_details[0]->appoinment_timezone
      );
      $this->db->insert('notification', $notification);
      //  $this->send_appoinment_mail($appointment_id);
      if (settings('tiwilio_option') == '1') {
        //  $this->send_appoinment_sms($appointment_id);
      }
    }

    $results = array('status' => 200, 'appointment_id' => base64_encode($appointment_id));
    echo json_encode($results);
  }

  public function create_razorpay_orders()
  {

    $amount = $this->input->post('amount');

    $currency_code = $this->input->post('currency_code');

    $amount = get_doccure_currency($amount, $currency_code, 'USD');

    $amount = number_format($amount, 2, '.', '');
    $api_key = "";
    $api_secret = "";



    $razorpay_option = !empty(settings("razorpay_option")) ? settings("razorpay_option") : "";
    if ($razorpay_option == '1') {
      $api_key = !empty(settings("sandbox_key_id")) ? settings("sandbox_key_id") : "";
      $api_secret = !empty(settings("sandbox_key_secret")) ? settings("sandbox_key_secret") : "";
    }
    if ($razorpay_option == '2') {
      $api_key = !empty(settings("live_key_id")) ? settings("live_key_id") : "";
      $api_secret = !empty(settings("live_key_secret")) ? settings("live_key_secret") : "";
    }
    /** @var Api $api */
    $api = new Api($api_key, $api_secret);
    /** @var array $order */
    $order  = $api->order->create(array('receipt' => time(), 'amount' => ($amount * 100), 'currency' => 'USD'));

    $doctor_id = $this->session->userdata('doctor_id');
    $patient_id = $this->session->userdata('user_id');

    $user_detail = user_detail($this->session->userdata('user_id'));


    $response['order_id'] = $order['id'];
    $response['key_id'] = $api_key;
    $response['amount'] = ($amount * 100);
    $response['currency'] = 'USD';
    $response['sitename'] = !empty(settings("meta_title")) ? settings("meta_title") : "Doccure";
    $response['siteimage'] = !empty(base_url() . settings("logo_front")) ? base_url() . settings("logo_front") : base_url() . "assets/img/logo.png";
    $response['patientname'] = ucfirst($user_detail['first_name'] . ' ' . $user_detail['last_name']);
    $response['email'] = $user_detail['email'];
    $response['mobileno'] = $user_detail['mobileno'];

    echo json_encode($response);
  }

  public function razorpay_appoinments()
  {
    $doctor_id = $this->session->userdata('doctor_id');

    $name = $this->input->post('firstname');
    $amount = $this->input->post('amount');
    $productinfo = $this->input->post('productinfo');
    $email = $this->input->post('email');
    $payment_id = $this->input->post('payment_id');
    $order_id = $this->input->post('order_id');
    $signature = $this->input->post('signature');
    $currency_code = $this->input->post('currency_code');
    $api_key = "";
    $api_secret = "";
    $appointment_id = "";

    $razorpay_option = !empty(settings("razorpay_option")) ? settings("razorpay_option") : "";
    if ($razorpay_option == '1') {
      $api_key = !empty(settings("sandbox_key_id")) ? settings("sandbox_key_id") : "";
      $api_secret = !empty(settings("sandbox_key_secret")) ? settings("sandbox_key_secret") : "";
    }
    if ($razorpay_option == '2') {
      $api_key = !empty(settings("live_key_id")) ? settings("live_key_id") : "";
      $api_secret = !empty(settings("live_key_secret")) ? settings("live_key_secret") : "";
    }

    $api = new Api($api_key, $api_secret);

    $attributes  = array('razorpay_signature'  => $signature,  'razorpay_payment_id'  => $payment_id,  'razorpay_order_id' => $order_id);
    $order  = $api->utility->verifyPaymentSignature($attributes);
    $response['payment_id'] = $payment_id;
    $response['order_id'] = $order_id;
    $response['signature'] = $signature;

    $transaction_status = json_encode($response);

    $txnid = $payment_id;
    $status = 'success';

    $opentok = new OpenTok($this->tokbox_apiKey, $this->tokbox_apiSecret);
    // An automatically archived session:
    $sessionOptions = array(
      // 'archiveMode' => ArchiveMode::ALWAYS,
      'mediaMode' => MediaMode::ROUTED
    );
    $new_session = $opentok->createSession($sessionOptions);
    // Store this sessionId in the database for later use
    $tokboxsessionId = $new_session->getSessionId();

    $tokboxtoken = $opentok->generateToken($tokboxsessionId);

    /* Get Invoice id */

    $invoice = $this->db->order_by('id', 'desc')->limit(1)->get('payments')->row_array();
    if (empty($invoice)) {
      $invoice_id = 0;
    } else {
      $invoice_id = $invoice['id'];
    }
    $invoice_id++;
    $invoice_no = 'I0000' . $invoice_id;

    // Store the Payment details

    $payments_data = array(
      'user_id' => $this->session->userdata('user_id'),
      'doctor_id' => $doctor_id,
      'invoice_no' => $invoice_no,
      'per_hour_charge' => $this->session->userdata('hourly_rate'),
      'total_amount' => $amount,
      'currency_code' => $currency_code,
      'txn_id' => $txnid,
      'order_id' => 'OD' . time() . rand(),
      'transaction_status' => $transaction_status,
      'payment_type' => 'Razorpay',
      'tax' => !empty(settings("tax")) ? settings("tax") : "0",
      'tax_amount' => $this->session->userdata('tax_amount'),
      'transcation_charge' => $this->session->userdata('transcation_charge'),
      'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
      'payment_status' => 1,
      'payment_date' => date('Y-m-d H:i:s'),
    );
    $this->db->insert('payments', $payments_data);
    $payment_id = $this->db->insert_id();

    $appointment_details = $this->session->userdata('appointment_details');

    foreach ($appointment_details as $key => $value) {
      $appointmentdata['payment_id'] =  $payment_id;
      $appointmentdata['appointment_from'] = $this->session->userdata('user_id');
      $appointmentdata['appointment_to'] = $this->session->userdata('doctor_id');
      $appointmentdata['from_date_time'] = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_start_time;
      $appointmentdata['to_date_time'] = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_end_time;
      $appointmentdata['appointment_date'] = $appointment_details[0]->appoinment_date;
      $appointmentdata['appointment_time'] = $appointment_details[0]->appoinment_start_time;
      $appointmentdata['appointment_end_time'] = $appointment_details[0]->appoinment_end_time;
      $appointmentdata['appoinment_token'] = $appointment_details[0]->appoinment_token;
      $appointmentdata['appoinment_session'] = $appointment_details[0]->appoinment_session;
      // $appointmentdata['type'] = $appointment_details[0]->type;
      $appointmentdata['type'] = $this->language['lg_online'];
      $appointmentdata['payment_method'] = $this->input->post('payment_method');
      $appointmentdata['tokboxsessionId'] = $tokboxsessionId;
      $appointmentdata['tokboxtoken'] = $tokboxtoken;
      $appointmentdata['paid'] = 1;
      $appointmentdata['approved'] = 1;
      $appointmentdata['time_zone'] = $appointment_details[0]->appoinment_timezone;
      $appointmentdata['created_date'] = date('Y-m-d H:i:s');
      $this->db->insert('appointments', $appointmentdata);
      $appointment_id = $this->db->insert_id();
      $notification = array(
        'user_id' => $this->session->userdata('user_id'),
        'to_user_id' => $this->session->userdata('doctor_id'),
        'type' => "Appointment",
        'text' => "has booked appointment to",
        'created_at' => date("Y-m-d H:i:s"),
        'time_zone' => $appointment_details[0]->appoinment_timezone
      );
      $this->db->insert('notification', $notification);
      // $this->send_appoinment_mail($appointment_id);
      if (settings('tiwilio_option') == '1') {
        $this->send_appoinment_sms($appointment_id);
      }
    }

    $results = array('status' => 200, 'appointment_id' => base64_encode($appointment_id));

    echo json_encode($results);
  }

  public function paypal_pay()
  {
    // echo "Hai";exit();


    // Set variables for paypal form
    $returnURL = base_url() . 'book_appoinments/success';
    $cancelURL = base_url() . 'book_appoinments/failure';
    $notifyURL = base_url() . 'book_appoinments/ipn';
    $payment_url = "";
    $client_id = "";
    $secret_key = "";
    $paypal_option = !empty(settings("paypal_option")) ? settings("paypal_option") : "";

    // print_r($paypal_option);exit();

    if ($paypal_option == '1') {
      $client_id = !empty(settings("sandbox_client_id")) ? settings("sandbox_client_id") : "";
      $secret_key = !empty(settings("sandbox_secret_key")) ? settings("sandbox_secret_key") : "";
      $payment_url = 'https://api-m.sandbox.paypal.com';
    }
    if ($paypal_option == '2') {
      $client_id = !empty(settings("live_client_id")) ? settings("live_client_id") : "";
      $secret_key = !empty(settings("live_secret_key")) ? settings("live_secret_key") : "";
      $payment_url = 'https://api-m.paypal.com';
    }



    $name = $this->input->post('name');
    $amount = $this->input->post('amount');
    $currency_code = $this->input->post('currency_code');
    $productinfo = $this->input->post('productinfo');
    $doctor_id = $this->session->userdata('doctor_id');
    $patient_id = $this->session->userdata('user_id');



    $this->db->insert('session_details', array('session_data' => json_encode($this->session->userdata)));
    $session_id    = $this->db->insert_id();

    $amount = get_doccure_currency($amount, $currency_code, 'USD');

    $amount = number_format($amount, 2, '.', '');

    // token create

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $payment_url . '/v1/oauth2/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_USERPWD, $client_id . ':' . $secret_key);

    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Accept-Language: en_US';
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $token_result = curl_exec($ch);
    if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    $token_response = json_decode($token_result, true);

    // payment create 

    $invoiceval = time() . rand();

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $payment_url . '/v1/payments/payment');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"intent\": \"sale\",\n  \"payer\": {\n    \"payment_method\": \"paypal\"\n  },\n  \"transactions\": [\n    {\n      \"amount\": {\n        \"total\": \"" . $amount . "\",\n        \"currency\": \"USD\"\n      },\n      \"description\": \"" . $productinfo . "\",\n      \"custom\": \"" . $session_id . "\",\n      \"invoice_number\": \"" . $invoiceval . "\",\n      \"payment_options\": {\n        \"allowed_payment_method\": \"INSTANT_FUNDING_SOURCE\"\n      }\n  }\n  ],\n  \"note_to_payer\": \"Contact us for any questions on your order.\",\n  \"redirect_urls\": {\n    \"return_url\": \"" . $returnURL . "\",\n    \"cancel_url\": \"" . $cancelURL . "\"\n  }\n}");

    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: Bearer ' . $token_response['access_token'] . '';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $payment_result = curl_exec($ch);
    if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    $payment_response = json_decode($payment_result, true);

    // print_r($payment_response);exit();

    redirect($payment_response['links'][1]['href']);
  }

  public function success()
  {

    $paypal_option = !empty(settings("paypal_option")) ? settings("paypal_option") : "";
    $payment_url = "";
    $secret_key = "";
    $client_id = "";
    if ($paypal_option == '1') {
      $client_id = !empty(settings("sandbox_client_id")) ? settings("sandbox_client_id") : "";
      $secret_key = !empty(settings("sandbox_secret_key")) ? settings("sandbox_secret_key") : "";
      $payment_url = 'https://api-m.sandbox.paypal.com';
    }
    if ($paypal_option == '2') {
      $client_id = !empty(settings("live_client_id")) ? settings("live_client_id") : "";
      $secret_key = !empty(settings("live_secret_key")) ? settings("live_secret_key") : "";
      $payment_url = 'https://api-m.paypal.com';
    }

    if (!empty($this->input->get())) {
      $paypalInfo =  $this->input->get();
      $paymentId = $paypalInfo['paymentId'];
      $token = $paypalInfo['token'];
      $PayerID = $paypalInfo['PayerID'];
    } else {
      $paypalInfo =  $this->input->post();
      $paymentId = $paypalInfo['paymentId'];
      $token = $paypalInfo['token'];
      $PayerID = $paypalInfo['PayerID'];
    }


    // token create

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $payment_url . '/v1/oauth2/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_USERPWD, $client_id . ':' . $secret_key);

    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Accept-Language: en_US';
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $token_result = curl_exec($ch);
    if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    $token_response = json_decode($token_result, true);

    // payment execute 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $payment_url . '/v1/payments/payment/' . $paymentId . '/execute');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"payer_id\": \"" . $PayerID . "\"\n}");

    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: Bearer ' . $token_response['access_token'] . '';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $execute_result = curl_exec($ch);
    if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    $execute_response = json_decode($execute_result, true);




    $sessionDetails = $this->db->where('id', $execute_response['transactions'][0]['custom'])->get('session_details')->row_array();
    $session        = (array) json_decode($sessionDetails['session_data']);

    $this->session->set_userdata($session);

    if ($execute_response['state'] == 'approved' && $execute_response['payer']['status'] == 'VERIFIED') {

      // echo "<pre>";print_r($paypalInfo);die;
      $patient_id = $this->session->userdata('user_id');

      $transaction_status = json_encode($execute_response);

      $status = 'success';



      $opentok = new OpenTok($this->tokbox_apiKey, $this->tokbox_apiSecret);
      // An automatically archived session:
      $sessionOptions = array(
        // 'archiveMode' => ArchiveMode::ALWAYS,
        'mediaMode' => MediaMode::ROUTED
      );
      $new_session = $opentok->createSession($sessionOptions);
      // Store this sessionId in the database for later use
      $tokboxsessionId = $new_session->getSessionId();

      $tokboxtoken = $opentok->generateToken($tokboxsessionId);

      /* Get Invoice id */

      $invoice = $this->db->order_by('id', 'desc')->limit(1)->get('payments')->row_array();
      if (empty($invoice)) {
        $invoice_id = 0;
      } else {
        $invoice_id = $invoice['id'];
      }
      $invoice_id++;
      $invoice_no = 'I0000' . $invoice_id;

      // Store the Payment details
      // $amount=$this->session->userdata('total_amount');
      // $amount=get_doccure_currency($amount,'USD',$this->session->userdata('currency_code'));

      // $amount=number_format($amount,2, '.', '');

      $amount = $this->session->userdata('total_amount');
      $amount = number_format($amount, 2, '.', '');

      $payments_data = array(
        'user_id' => $this->session->userdata('user_id'),
        'doctor_id' => $this->session->userdata('doctor_id'),
        'invoice_no' => $invoice_no,
        'per_hour_charge' => $this->session->userdata('hourly_rate'),
        'total_amount' => $amount,
        'currency_code' => $this->session->userdata('currency_code'),
        'txn_id' => $execute_response['id'],
        'order_id' => 'OD' . time() . rand(),
        'transaction_status' => $transaction_status,
        'payment_type' => 'Paypal',
        'tax' => !empty(settings("tax")) ? settings("tax") : "0",
        'tax_amount' => $this->session->userdata('tax_amount'),
        'transcation_charge' => $this->session->userdata('transcation_charge'),
        'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
        'payment_status' => 1,
        'payment_date' => date('Y-m-d H:i:s'),
      );
      $this->db->insert('payments', $payments_data);
      $payment_id = $this->db->insert_id();

      $appointment_details = $this->session->userdata('appointment_details');

      // foreach ($appointment_details as $key => $value) {
      $appointmentdata['payment_id'] =  $payment_id;
      $appointmentdata['appointment_from'] = $this->session->userdata('user_id');
      $appointmentdata['appointment_to'] = $this->session->userdata('doctor_id');
      $appointmentdata['from_date_time'] = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_start_time;
      $appointmentdata['to_date_time'] = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_end_time;
      $appointmentdata['appointment_date'] = $appointment_details[0]->appoinment_date;
      $appointmentdata['appointment_time'] = $appointment_details[0]->appoinment_start_time;
      $appointmentdata['appointment_end_time'] = $appointment_details[0]->appoinment_end_time;
      $appointmentdata['appoinment_token'] = $appointment_details[0]->appoinment_token;
      $appointmentdata['appoinment_session'] = $appointment_details[0]->appoinment_session;
      // $appointmentdata['type'] = $appointment_details[0]->type;
      $appointmentdata['type'] = $this->language['lg_online'];
      $appointmentdata['payment_method'] = 'Paypal';
      $appointmentdata['tokboxsessionId'] = $tokboxsessionId;
      $appointmentdata['tokboxtoken'] = $tokboxtoken;
      $appointmentdata['paid'] = 1;
      $appointmentdata['approved'] = 1;
      $appointmentdata['time_zone'] = $appointment_details[0]->appoinment_timezone;
      $appointmentdata['created_date'] = date('Y-m-d H:i:s');
      $this->db->insert('appointments', $appointmentdata);
      $appointment_id = $this->db->insert_id();
      $notification = array(
        'user_id' => $this->session->userdata('user_id'),
        'to_user_id' => $this->session->userdata('doctor_id'),
        'type' => "Appointment",
        'text' => "has booked appointment to",
        'created_at' => date("Y-m-d H:i:s"),
        'time_zone' => $appointment_details[0]->appoinment_timezone
      );
      $this->db->insert('notification', $notification);
      $this->send_appoinment_mail($appointment_id);
      if (settings('tiwilio_option') == '1') {
        $this->send_appoinment_sms($appointment_id);
      }
      // }


      if ($this->session->userdata('doctor_role_id') == 6) {
        $this->session->set_flashdata('success_message', $this->language['lg_clinic_transaction_suc']);
      } else {
        $this->session->set_flashdata('success_message', $this->language['lg_transaction_suc']);
      }
      redirect(base_url() . 'appoinment-success/' . base64_encode($appointment_id));
    } else {

      $this->session->set_flashdata('error_message', $this->language['lg_transaction_fai']);
      redirect(base_url() . 'dashboard');
    }
  }

  public function failure()
  {

    $this->session->set_flashdata('error_message', $this->language['lg_transaction_fai']);
    redirect(base_url() . 'dashboard');
  }

  public function clinic_appoinments()
  {
    // die("dsfa"); doctor gtc booking comes here
    $invoice = $this->db->order_by('id', 'desc')->limit(1)->get('payments')->row_array();
    if (empty($invoice)) {
      $invoice_id = 0;
    } else {
      $invoice_id = $invoice['id'];
    }
    $invoice_id++;
    $invoice_no = 'I0000' . $invoice_id;
    $appointment_id = "";

    $paymentdata = array(
      'user_id' => $this->session->userdata('user_id'),
      'doctor_id' => $this->session->userdata('doctor_id'),
      'invoice_no' => $invoice_no,
      'per_hour_charge' => $this->session->userdata('hourly_rate'),
      'total_amount' => $this->session->userdata('total_amount'),
      'currency_code' => $this->session->userdata('currency_code'),
      'txn_id' => '',
      'order_id' => 'OD' . time() . rand(),
      'transaction_status' => '',
      'payment_type' => 'Pay on Arrive',
      'tax' => !empty(settings("tax")) ? settings("tax") : "0",
      'tax_amount' => $this->session->userdata('tax_amount'),
      'transcation_charge' => $this->session->userdata('transcation_charge'),
      'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
      'payment_status' => 0,
      'payment_date' => date('Y-m-d H:i:s'),
    );
    $this->db->insert('payments', $paymentdata);
    $payment_id = $this->db->insert_id();

    // Sending notification to mentor
    $doctor_id = $this->session->userdata('doctor_id');
    $appointment_details = $this->session->userdata('appointment_details');

    foreach ($appointment_details as $key => $value) {
      $appointmentdata['payment_id'] =  $payment_id;
      $appointmentdata['appointment_from'] = $this->session->userdata('user_id');
      $appointmentdata['appointment_to'] = $this->session->userdata('doctor_id');
      $appointmentdata['from_date_time'] = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_start_time;
      $appointmentdata['to_date_time'] = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_end_time;
      $appointmentdata['appointment_date'] = $appointment_details[0]->appoinment_date;
      $appointmentdata['appointment_time'] = $appointment_details[0]->appoinment_start_time;
      $appointmentdata['appointment_end_time'] = $appointment_details[0]->appoinment_end_time;
      $appointmentdata['appoinment_token'] = $appointment_details[0]->appoinment_token;
      $appointmentdata['appoinment_session'] = $appointment_details[0]->appoinment_session;
      $appointmentdata['type'] = "Clinic";
      $appointmentdata['payment_method'] = $this->input->post('payment_method');
      $appointmentdata['tokboxsessionId'] = '';
      $appointmentdata['tokboxtoken'] = '';
      $appointmentdata['paid'] = 1;
      $appointmentdata['approved'] = 1;
      $appointmentdata['time_zone'] = $appointment_details[0]->appoinment_timezone;
      $appointmentdata['created_date'] = date('Y-m-d H:i:s');
      $this->db->insert('appointments', $appointmentdata);
      $appointment_id = $this->db->insert_id();
      $notification = array(
        'user_id' => $this->session->userdata('user_id'),
        'to_user_id' => $this->session->userdata('doctor_id'),
        'type' => "Appointment",
        'text' => "has booked appointment to",
        'created_at' => date("Y-m-d H:i:s"),
        'time_zone' => $appointment_details[0]->appoinment_timezone
      );
      $this->db->insert('notification', $notification);
      // $this->send_appoinment_mail($appointment_id);
      if (settings('tiwilio_option') == '1') {
        $this->send_appoinment_sms($appointment_id);
      }
    }


    $results = array('status' => 200, 'appointment_id' => base64_encode($appointment_id));
    echo json_encode($results);
  }

  public function send_appoinment_mail($appointment_id)
  {
    $appoinments_details = $this->book->get_appoinments_details($appointment_id);
    $this->load->library('sendemail');
    $this->sendemail->send_appoinment_email($appoinments_details);
    // Notification Code (New Appoinments)
    if ($this->session->userdata('role') == '2') {
      $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
      $device_type = $appoinments_details['doctor_device_type'];
      $nresponse['from_name'] = $appoinments_details['patient_first_name'];
    }
    $notifydata['message'] = $nresponse['from_name'] . ' has booked appointment on ' . date('d M Y', strtotime($appoinments_details['created_date']));
    $notifydata['notifications_title'] = '';
    $notifydata['title'] = 'Booking';
    $nresponse['type'] = 'Booking';
    $notifydata['additional_data'] = $nresponse;
    if ($device_type == 'Android') {
      sendFCMNotification($notifydata);
    }
    if ($device_type == 'IOS') {
      sendiosNotification($notifydata);
    }
  }

  public function send_appoinment_sms($appointment_id)
  {

    $inputdata = $this->book->get_appoinments_details($appointment_id);

    $AccountSid = settings("tiwilio_apiKey");
    $AuthToken = settings("tiwilio_apiSecret");
    $from = settings("tiwilio_from_no");
    $twilio = new Client($AccountSid, $AuthToken);

    $msg = $this->language['lg_you_have_new_ap'] . ' ' . $inputdata["patient_name"];

    $mobileno = "+" . $inputdata['doctor_mobile'];

    try {
      $message = $twilio->messages
        ->create(
          $mobileno, // to
          ["body" => $msg, "from" => $from]
        );
      $response = array('status' => true);
      $status = 0;
    } catch (Exception $error) {
      //echo $error;
      $status = 500;
    }
  }

  /*Booking success page after Payment success */

  public function payment_sucess($appointment_id)
  {

    $this->data['appointment_details'] = $this->book->get_appoinments_details(base64_decode($appointment_id));
    $this->data['page'] = 'payment_success';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }


  public function cybersource_payment_form()
  {
    if ($this->session->userdata('role') == '1') {
      redirect(base_url() . 'dashboard');
    } else {
      $this->data['page'] = 'payment_form';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
    }
  }
  public function payment_confirmation()
  {
    if ($this->session->userdata('role') == '1') {
      redirect(base_url() . 'dashboard');
    } else {
      $this->data['page'] = 'payment_confirmation';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
    }
  }

  public function send_invoice_paid_mail($bill_id)
  {
    $this->load->model('my_patients_model', 'my_patients');
    $billing_details = $this->my_patients->getBillingDetails($bill_id);
    $this->load->library('sendemail');
    $this->sendemail->send_bill_paid_notify_email($billing_details);

    $notifydata['include_player_ids'] = $billing_details['doctor_device_id'];
    $device_type = $billing_details['doctor_device_type'];
    $nresponse['from_name'] = $billing_details['patient_first_name'];

    $notifydata['message'] = $nresponse['from_name'] . ' has paid an invoice -' . $billing_details['bill_no']. " of the billing -".$billing_details['billno'];
    $notifydata['notifications_title'] = '';
    $notifydata['title'] = 'Booking';
    $nresponse['type'] = 'Booking';
    $notifydata['additional_data'] = $nresponse;
    if ($device_type == 'Android' && $billing_details['doctor_device_id'] != '') {
      sendFCMNotification($notifydata);
    }
    if ($device_type == 'IOS'  && $billing_details['doctor_device_id'] != '') {
      sendiosNotification($notifydata);
    }
  }


  public function cybersource_checkout()
  {
    // echo "<pre>";
    // print_r($_REQUEST);
    // echo "</pre>";
    // die("die here"); 

    $transaction_uuid = $_REQUEST['req_transaction_uuid'];
    $status =  explode("_", $transaction_uuid); //string into an array
    $session_data = encryptor_decryptor('decrypt', $status[1]);
    $sessionval = explode("_", $session_data); 
    $session_id = $sessionval[0]; 
    if($sessionval[1]!='Invoice') {
      $sessionDetails = $this->db->where('id', $session_id)->get('session_details')->row_array();
      $session        = (array) json_decode($sessionDetails['session_data']);
      $session_user_id = $session['user_id'];
      $timezone = $session['time_zone'];
    }  else {
      $billing = $this->db->select('id,user_id,doctor_id,invoice_no,billing_id,time_zone')->get_where('payments',array('id'=>$sessionval[0]))->row_array();
      $session_user_id = $billing['user_id'];
      $timezone = $billing['time_zone'];
    }

    
    $this->session->set_userdata('user_id', $session_user_id);
    $this->session->set_userdata('role', 2);
    $this->session->set_userdata('time_zone', $timezone);


    if ($_REQUEST['decision'] == 'ACCEPT') {

      if($sessionval[1]=='Pharmacy') {
        
        $this->load->model('cart_model', 'carts');
        $this->carts->pharmacyPlaceorder($session_id, $_REQUEST['transaction_id']);

        $this->session->set_flashdata('success_message', $this->language['lg_payment_paid_su']);
        redirect(base_url().'pharmacy/orders_list');

      } else if($sessionval[1]=='Lab') {
          $this->load->model('lab_model', 'lab');
          $this->lab->lab_cybersource_pay($session_id, $_REQUEST['transaction_id']);

        $this->session->set_flashdata('success_message', $this->language['lg_payment_paid_su']);
        redirect(base_url() . 'appoinments/lab_appoinments');
        
      } else if($sessionval[1]=='Invoice') {
        
        $payments_data = array(          
          'txn_id' => $_REQUEST['transaction_id'],
          'order_id' => 'OD' . time() . rand(),
          'transaction_status' => $_REQUEST['transaction_id'],
          'payment_type' => 'Cybersource',
          'payment_status' => 1,
          'payment_date' => date('Y-m-d H:i:s'),
          'billing_status' => 1
        );
        $this->db->where('id', $sessionval[0]);
        $this->db->update('payments', $payments_data);

        
        $billing_data = array(     
          'bill_paid_on' => date('Y-m-d H:i:s'),
          'billing_paid_status' => 1
        );
        $this->db->where('id', $billing['billing_id']);
        $this->db->update('billing', $billing_data);

        $notification = array(
          'user_id' => $billing['user_id'],
          'to_user_id' => $billing['doctor_id'],
          'type' => "Billing",
          'text' => "paid the invoice - ".$billing['invoice_no'],
          'created_at' => date("Y-m-d H:i:s"),
          'time_zone' => $timezone
        );
        $this->db->insert('notification', $notification);
        $this->send_invoice_paid_mail($billing['billing_id']);

        $this->session->set_flashdata('success_message', $this->language['lg_payment_paid_su']);
        redirect(base_url() . 'invoice');



      } else {

        $opentok = new OpenTok($this->tokbox_apiKey, $this->tokbox_apiSecret);
        // An automatically archived session:
        $sessionOptions = array(
          // 'archiveMode' => ArchiveMode::ALWAYS,
          'mediaMode' => MediaMode::ROUTED
        );
        $new_session = $opentok->createSession($sessionOptions);
        // Store this sessionId in the database for later use
        $tokboxsessionId = $new_session->getSessionId();

        $tokboxtoken = $opentok->generateToken($tokboxsessionId);

        /* Get Invoice id */

        $invoice = $this->db->order_by('id', 'desc')->limit(1)->get('payments')->row_array();
        if (empty($invoice)) {
          $invoice_id = 0;
        } else {
          $invoice_id = $invoice['id'];
        }
        $invoice_id++;
        $invoice_no = 'I0000' . $invoice_id;


        $amount = $session['total_amount'];
        $amount = number_format($amount, 2, '.', '');

        $appointment_details = $session['appointment_details'];

        $payments_data = array(
          'user_id' => $session['user_id'],
          'doctor_id' => $session['doctor_id'],
          'invoice_no' => $invoice_no,
          'per_hour_charge' => $session['hourly_rate'],
          'total_amount' => $amount,
          'currency_code' => $session['currency_code'],
          'txn_id' => $_REQUEST['transaction_id'],
          'order_id' => 'OD' . time() . rand(),
          'transaction_status' => $_REQUEST['transaction_id'],
          'payment_type' => 'Cybersource',
          'tax' => !empty(settings("tax")) ? settings("tax") : "0",
          'tax_amount' => $session['tax_amount'],
          'transcation_charge' => $session['transcation_charge'],
          'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
          'payment_status' => 1,
          'payment_date' => date('Y-m-d H:i:s'),
          'time_zone' => $appointment_details[0]->appoinment_timezone
        );

        $this->db->insert('payments', $payments_data);
        $payment_id = $this->db->insert_id();
       

        $appointmentdata['payment_id'] =  $payment_id;
        $appointmentdata['appointment_from'] = $session['user_id'];
        $appointmentdata['appointment_to'] = $session['doctor_id'];
        $appointmentdata['from_date_time'] = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_start_time;
        $appointmentdata['to_date_time'] = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_end_time;
        $appointmentdata['appointment_date'] = $appointment_details[0]->appoinment_date;
        $appointmentdata['appointment_time'] = $appointment_details[0]->appoinment_start_time;
        $appointmentdata['appointment_end_time'] = $appointment_details[0]->appoinment_end_time;
        $appointmentdata['appoinment_token'] = $appointment_details[0]->appoinment_token;
        $appointmentdata['appoinment_session'] = $appointment_details[0]->appoinment_session;
        $appointmentdata['type'] = $this->language['lg_online'];
        $appointmentdata['payment_method'] = 'Cybersource';
        $appointmentdata['tokboxsessionId'] = $tokboxsessionId;
        $appointmentdata['tokboxtoken'] = $tokboxtoken;
        $appointmentdata['paid'] = 1;
        $appointmentdata['approved'] = 1;
        $appointmentdata['time_zone'] = $appointment_details[0]->appoinment_timezone;
        $appointmentdata['created_date'] = date('Y-m-d H:i:s');
        $this->db->insert('appointments', $appointmentdata);
        $appointment_id = $this->db->insert_id();
        $notification = array(
          'user_id' => $session['user_id'],
          'to_user_id' => $session['doctor_id'],
          'type' => "Appointment",
          'text' => "has booked appointment to",
          'created_at' => date("Y-m-d H:i:s"),
          'time_zone' => $appointment_details[0]->appoinment_timezone
        );
        $this->db->insert('notification', $notification);
        $this->send_appoinment_mail($appointment_id);
        if (settings('tiwilio_option') == '1') {
          $this->send_appoinment_sms($appointment_id);
        }


        if ($this->session->userdata('doctor_role_id') == 6) {
          $this->session->set_flashdata('success_message', $this->language['lg_clinic_transaction_suc']);
        } else {
          $this->session->set_flashdata('success_message', $this->language['lg_transaction_suc']);
        }

        redirect(base_url() . 'appoinment-success/' . base64_encode($appointment_id));
      } 
    } else {
        $this->session->set_flashdata('error_message', $this->language['lg_transaction_fai'] . " " . $_REQUEST['message']);
        redirect(base_url() . 'dashboard');
   }
  
  }

  /*public function cybersource_signeddatafields()
  {
        if($this->session->userdata('role')=='1'){
          redirect(base_url().'dashboard'); 
        } else {          
          $this->data['page'] = 'signeddatafields';          
          $this->load->vars($this->data);
          $this->load->view($this->data['theme'].'/template');
        }
  }

  public function cybersource_unsigneddatafields()
  {

    $this->data['page'] = 'unsigneddatafields';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }*/
}
