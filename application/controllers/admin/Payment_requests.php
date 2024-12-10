<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_requests extends CI_Controller {

   public $data;
   public $session;
   public $timezone;
   public $input;
   public $db;
   public $payment_requests;

   public function __construct() {

        parent::__construct();

        if($this->session->userdata('admin_id') ==''){
            redirect(base_url().'admin/login');
        }
        
        $this->data['theme']     = 'admin';
        $this->data['module']    = 'payment_requests';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();

         $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }
        
        $this->load->model('payment_requests_model','payment_requests');
        

    }


	public function index()
	{
	    $this->data['page'] = 'index';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
	   
	}

  public function payment_requests_list()
   {
      $list = $this->payment_requests->get_datatables();
      $data = array();
      $no = $_POST['start'];
      $a=1;
       
      foreach ($list as $payments) {

        $profileimage=(!empty($payments['profileimage']))?base_url().$payments['profileimage']:base_url().'assets/img/user.png';
        if($payments['role']==1)
        {
          $url=base_url().'doctor-preview/'.$payments['username'];
          $role='Doctor';
        }
        else
        {
          $url=base_url().'patient-preview/'.base64_encode($payments['user_id']);
          $role='Patient';
        }

        switch ($payments['status']) {
          case '1':
           $status='<div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"> Action </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" onclick="payment_status(\''.$payments['id'].'\',\'2\')" href="javascript:void(0);">Pay</a>
                      <a class="dropdown-item" onclick="payment_status(\''.$payments['id'].'\',\'3\')" href="javascript:void(0);">Reject</a>
                    </div>
                  </div>';
            break;
           case '2':
           $status='<span class="badge badge-pill bg-success inv-badge">Paid</span>';
            break;
             case '3':
           $status='<span class="badge badge-pill bg-danger inv-badge">Rejected</span>';
            break;
           default:
             $status='';
            break;
        }

        $currency_option = default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);

        $org_amount=get_doccure_currency($payments['request_amount'],$payments['currency_code'],default_currency_code());
       
        $no++;
        $row = array();
        $row[] = $no;
        $row[] = date('d M Y',strtotime($payments['request_date']));
        $row[] = $rate_symbol.number_format($org_amount,2);
        $row[] = $payments['description'];
        $row[] = '<h2 class="table-avatar">
                  <a target="_blank" href="'.$url.'" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="'.$profileimage.'" alt="User Image">
                  </a>
                  <a target="_blank" href="'.$url.'">'.ucfirst($payments['first_name'].' '.$payments['last_name']).' <span>'.$role.'</span></a>
                </h2>';
        
       $row[] = ($payments['payment_type']==1)?'Appoinments':'Refund';
       $row[]='<a href="javascript:void(0);" onclick="view_bankdetails(\''.$payments['bank_name'].'\',\''.$payments['branch_name'].'\',\''.$payments['account_no'].'\',\''.$payments['account_name'].'\')">View Bank Details</a>';

       $row[]=$status;
        
        $data[] = $row;
      }



      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->payment_requests->count_all(),
              "recordsFiltered" => $this->payment_requests->count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }


  public function payment_status()
    {

      $id=$this->input->post('id');
      $status=$this->input->post('status');

		$data = array(
			'status' =>$status,
		);
        $this->payment_requests->update(array('id' => $id), $data);
		
		$touserid = $this->payment_requests->get_touserid($id);
		if($status == 3){
			$text = "has rejected payment request of";
		} else {
			$text = "has accepted payment request and paid";
		}
		$notification=array(
			'user_id'=>0,
			'to_user_id'=>$touserid,
			'type'=>"Payment Request",
			'text'=>$text,
			'created_at'=>date("Y-m-d H:i:s"),
			'time_zone'=>$this->timezone
		);
        $this->db->insert('notification',$notification);
		
        echo json_encode(array("status" => TRUE));
    }


}
