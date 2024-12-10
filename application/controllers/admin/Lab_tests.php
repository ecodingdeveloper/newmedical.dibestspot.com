<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lab_tests extends CI_Controller {

  public $data;
  public $session;
  public $timezone;
  public $lab;

  public function __construct() {

    parent::__construct();

    if($this->session->userdata('admin_id') ==''){
      redirect(base_url().'admin/login');
    }
        
    $this->data['theme']     = 'admin';
    $this->data['module']    = 'lab_tests';
    $this->data['page']     = '';
    $this->data['base_url'] = base_url();
    $this->timezone = $this->session->userdata('time_zone');
    if(!empty($this->timezone)){
      date_default_timezone_set($this->timezone);
    }
    $this->load->model('lab_model','lab');

  }

  public function index()
  {
    $this->data['page'] = 'index';
    $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');       
  }

  public function lab_tests_list() {
    $list = $this->lab->get_labtest_datatables();
 
        $data = array();
        $no = $_POST['start'];
        $a=1;
         
        foreach ($list as $lab) {
  
          $val='';
  
        if($lab['status'] == '1')
          {
            $val = 'checked';
          }
  
          
          $no++;
          $row = array();
          $row[] = $no;
          $row[] = ucfirst($lab['first_name'].' '.$lab['last_name']);
          $row[] = ucfirst($lab['lab_test_name']);
          $amount=0;
          if($lab['amount']){
            $amount=get_doccure_currency($lab['amount'],$lab['currency_code'],default_currency_code());
          }
          $row[] = currency_code_sign($lab['currency_code']).''. number_format($amount,2);
          $row[] = $lab['duration'];
          $row[] = $lab['description'];
          $row[] = date('d M Y',strtotime($lab['created_date'])).'<br><small>'.date('h:i A',strtotime($lab['created_date'])).'</small>';
          
          $data[] = $row;
        }
  
  
  
        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->lab->labtest_count_all(),
                "recordsFiltered" => $this->lab->labtest_count_filtered(),
                "data" => $data,
            );
        //output to json format
        echo json_encode($output);
  }

}

  