<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice extends CI_Controller
{

  public $data;
  public $session;
  public $timezone;
  public $lang;
  public $input;
  public $db;
  public $language;
  public $invoice;
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
    $this->load->model('invoice_model', 'invoice');
  }


  public function index()
  {
    $this->data['module']    = 'invoice';
    
    $this->data['page'] = 'invoice';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function invoice_list()
  {
    $user_id = $this->session->userdata('user_id');
    $list = $this->invoice->get_datatables($user_id);
    // print_r($this->db->last_query());exit();
    // echo '<pre>';
    // print_r($list);
    // echo '</pre>';
    // die("die here");
    $data = array();
    $no = $_POST['start'];
    $a = 1;

    
    

    foreach ($list as $invoices) {

     
      $invoideID=$invoices['id'];

      $user_currency = get_user_currency();
      $user_currency_code = $user_currency['user_currency_code'];
      $user_currency_rate = $user_currency['user_currency_rate'];

      $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
      $rate_symbol = currency_code_sign($currency_option);
      $org_amount = get_doccure_currency($invoices['total_amount'], $invoices['currency_code'], $user_currency_code);


      $bill=($org_amount+settings('fixed_value_2'))*(settings('percentage_value_2')/100)+($org_amount+settings('fixed_value_2'));
      // echo $bill;
      // die("sdsdsdds");


      $doctor_profileimage = (!empty($invoices['doctor_profileimage']) && file_exists(FCPATH . $invoices['doctor_profileimage'])) ? base_url() . $invoices['doctor_profileimage'] : base_url() . 'assets/img/user.png';
      $patient_profileimage = (!empty($invoices['patient_profileimage']) && file_exists(FCPATH . $invoices['patient_profileimage'])) ? base_url() . $invoices['patient_profileimage'] : base_url() . 'assets/img/user.png';

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = '<a href="' . base_url() . 'invoice-view/' . base64_encode($invoices['id']) . '">' . $invoices['invoice_no'] . '</a>';

      if ($this->session->userdata('role') == '1' || $this->session->userdata('role') == '4' || $this->session->userdata('role') == '5' || $this->session->userdata('role') == '6') {
        $href_link = 'javascript:;';
        $target = '';
        if ($this->session->userdata('role') == '1') {
          $href_link = base_url() . 'patient-preview/' . base64_encode($invoices['patient_id']);
          $target = 'target="_blank"';
        }
        $row[] = '<h2 class="table-avatar">
                  <a ' . $target . ' href="' . $href_link . '" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $patient_profileimage . '" alt="User Image"></a>
                  <a ' . $target . ' href="' . $href_link . '">' . ucfirst($invoices['patient_name']) . ' </a>
                </h2>';
      }
      if ($this->session->userdata('role') == '2'||$this->session->userdata('role')=='7') {
        $user_role = '';
        $img = '';
        if ($invoices['role'] == '1') {
          $user_role = $this->language['lg_dr'];
          $img = '<a target="_blank" href="' . base_url() . 'doctor-preview/' . $invoices['doctor_username'] . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="User Image">
                  </a>';
        } else {
          $img = '<a target="_blank" href="' . base_url() . 'doctor-preview/' . $invoices['doctor_username'] . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="User Image">
                  </a>';
        }
        $row[] = '<h2 class="table-avatar">
                  ' . $img . '
                  <a target="_blank" href="' . base_url() . 'doctor-preview/' . $invoices['doctor_username'] . '">' . $user_role . ' ' . ucfirst($invoices['doctor_name']??'') . '</a>
                </h2>';
      }
      $row[] = $rate_symbol . number_format($org_amount, 2, '.', ',');
      

	  if($this->session->userdata('role')=='7'&&$invoices['payment_status'] == 1){
        continue;
    }else{
      if ($invoices['payment_status'] == 1) {
        $row[] = $this->language['lg_payment_paid'] ;
        
  
      } elseif($this->session->userdata('role') == '1') {
        // $row[] = $this->language['lg_payment_not_paid'];
        $row[] = ''.$this->language['lg_payment_not_paid'] .' <a href="#" onclick="updatePayment('.$invoices['id'].')"; return false;">(click if paid</a>)';
      }
      else {
        $row[] = $this->language['lg_payment_not_paid'];
      }
    }
    
    if($this->session->userdata('role') == '2'){
	  $row[] =$rate_symbol . settings('fixed_value_2');
    $row[] = settings('percentage_value_2').'%';
    if($invoices['payment_status']==1){
      $row[] = $this->language['lg_payment_paid'] ;
    }
    else{
      
      $row[] = '<a target="_blank" href="'. base_url() . 'accounts/?bill=' . $bill . '" class="btn btn-sm bg-info-light">
                Pay '.$rate_symbol . number_format($bill, 2, '.', '') . ' 
              </a>';

    }
  }
    // $row[]= $invoices['payment_status'];

    
	  //`p`.`payment_status` = 1 OR `
      if (($invoices['billing_id'] > 0 && $invoices['billing_status'] == 0) || ($invoices['payment_status'] == 0)) {
        $row[] = '-';
      } else {
        $row[] = date('d M Y', strtotime($invoices['payment_date']));
      }

      if ($invoices['billing_id'] > 0 && $invoices['billing_status'] == 0) {
        if (is_patient()) {
          $row[] = '<div class="table-action">
        <a target="_blank" href="' . base_url() . 'invoice/checkout/' . base64_encode($invoices['id']) . '" class="btn btn-sm bg-warning"> <i class="fa fa-angle-double-right"></i> ' . $this->language['lg_pay_invoice'] . '
        </a>
       
      </div>';
        } else {
          $row[] = '<div class="table-action">
        <a  class="btn btn-sm bg-danger-light" href="javascript:void(0)">
           ' . $this->language['lg_payment_pending'] . '
        </a>
       
      </div>';
        }
      } else {
        $row[] = '<div class="table-action">
                  <a target="_blank" href="' . base_url() . 'invoice-view/' . base64_encode($invoices['id']) . '" class="btn btn-sm bg-info-light">
                    <i class="far fa-eye"></i> ' . $this->language['lg_view1'] . '
                  </a>
                  <a target="_blank" href="' . base_url() . 'invoice-print/' . base64_encode($invoices['id']) . '" class="btn btn-sm bg-primary-light" target="blank">
                                <i class="fas fa-print"></i> ' . $this->language['lg_print'] . '
                  </a>
                </div>';
      }
      $data[] = $row;
    }


    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->invoice->count_all($user_id),
      "recordsFiltered" => $this->invoice->count_filtered($user_id),
      "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }

  // public function view()
  // {
  //     $invoice_id=$this->uri->segment(2);
  //     $this->data['page'] = 'invoice_view';
  //     $this->data['invoices']=$this->invoice->get_invoice_details(base64_decode($invoice_id));
  //     $this->load->vars($this->data);
  //     $this->load->view($this->data['theme'].'/template');
  // }

  public function view()
  {
    $invoice_id = $this->uri->segment(2);
    $this->data['invoices'] = $this->invoice->get_invoice_details(base64_decode($invoice_id));
    $this->data['role'] = $this->data['invoices']['role'];
    // echo $this->data['invoices']['role'];exit();
    if ($this->data['invoices']['role'] == '5') {
      $this->data['invoice_no'] = $this->data['invoices']['invoice_no'];
      $this->data['page'] = 'invoice_pharmacy_view';
      $this->data['invoices'] = $this->invoice->get_products_datatables(base64_encode($this->data['invoices']['order_id']));
      // print_r($this->db->last_query());exit();
      $this->data['language'] = $this->language;
    } else {
      $this->data['page'] = 'invoice_view';
    }

    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  // public function invoice_print()
  // {
  //     $invoice_id=$this->uri->segment(2);
  //     $data['invoices']=$this->invoice->get_invoice_details(base64_decode($invoice_id));
  //     $data['language']=$this->language;
  //     $this->load->view('web/modules/invoice/invoice_print',$data);
  // }

  public function invoice_print()
  {
    $invoice_id = $this->uri->segment(2);
    $data['invoices'] = $this->invoice->get_invoice_details(base64_decode($invoice_id));
    $data['role'] = $data['invoices']['role'];
    $data['language'] = $this->language;
    if ($data['invoices']['role'] == '5') {
      $this->data['invoice_no'] = $this->data['invoices']['invoice_no'];
      $data['invoices'] = $this->invoice->get_products_datatables(base64_encode($data['invoices']['order_id']));
      $this->load->view('web/modules/invoice/product_invoice_print', $data);
    } else {
      $this->load->view('web/modules/invoice/invoice_print', $data);
    }
  }


  public function product_view($orderId)
  {
    $invoice_id = $this->uri->segment(2);
    $this->data['page'] = 'invoice_pharmacy_view';
    $this->data['invoices'] = $this->invoice->get_products_datatables($orderId);
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }
  public function product_invoice_print($orderId)
  {
    $invoice_id = $this->uri->segment(2);
    $data['invoices'] = $this->invoice->get_products_datatables($orderId);
    // print_r($this->data['invoices']);die();  
    $data['language'] = $this->language;
    $this->load->view('web/modules/invoice/product_invoice_print', $data);
  }
  public function invoice_checkout($invoiceId)
  {
    if($this->session->userdata('role')==1) {
      redirect(base_url() . 'dashboard');
    }
    $this->data['page'] = 'invoice_checkout';
    $this->data['invoice_id'] = base64_decode($invoiceId); 
    $this->data['invoice_details'] = $invoiceDetails = $this->invoice->get_invoice_details(base64_decode($invoiceId));
    if($invoiceDetails['billing_status'] ==0) {
    
    $this->data['doctors'] = $this->invoice->getUserDetails($invoiceDetails['doctor_id']);
    $this->data['patients'] = $this->invoice->getUserDetails($this->session->userdata('user_id'));
    
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
    } else {
      $this->session->set_flashdata('error_message', $this->language['lg_invoice_paid_al']);
      redirect(base_url() . 'invoice');
    }
  }
}
