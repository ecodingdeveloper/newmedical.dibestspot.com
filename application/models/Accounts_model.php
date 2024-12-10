<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property object $load 
 * @property object $db
 * @property object $session
 */

class Accounts_model extends CI_Model {

    var $table = 'payments p';
    var $doctor ='users d';
    var $doctor_details ='users_details dd';
    var $patient ='users pi';
    var $patient_details ='users_details pd';
    var $users =
    'users u';
    var $appoinments ='appointments a';
	var $column_search = array('p.invoice_no','u.first_name','u.last_name','u.profileimage','p.total_amount','p.payment_date'); //set column field database for datatable searchable 
	var $column_search1 = array('CONCAT(pi.first_name," ", pi.last_name)','p.total_amount','date_format(p.payment_date,"%d %b %Y")');
	var $column_search2 = array('p.invoice_no','CONCAT(d.first_name," ", d.last_name)','d.profileimage','p.total_amount','p.payment_date');
   var $order1 = array('p.request_status' => 'ASC'); // default order
   var $order = array('p.id' => 'DESC'); // default order
    var $column_order = [
      '',
      'p.payment_date',
      'd.first_name',
      'p.total_amount',
      'p.request_status',
      ''
    ]; // accounts column search

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($user_id)
  {
  
     $this->db->distinct();
    $this->db->select('p.*,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.id as patient_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count,pi.role');
    $this->db->from($this->table);
    $this->db->join($this->patient, 'pi.id = p.user_id', 'left'); 
    $this->db->join($this->patient_details,'pd.user_id = pi.id','left');
	//Newly added left join by nandakumar 
	// $this->db->join($this->appoinments,'a.appointment_to=p.doctor_id and a.appointment_from=p.user_id','left');
	// $this->db->where('a.appointment_status',1);	 
	//End 
    $this->db->where('p.doctor_id',$user_id);
    $this->db->where('p.payment_status',1);

        $i = 0;
  
    foreach ($this->column_search1 as $item) // loop column 
    {
      if($_POST['search']['value']) // if datatable send POST for search
      {
        
        if($i===0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        }
        else
        {
          $this->db->or_like($item, $_POST['search']['value']);
        }

         if(count($this->column_search1) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
            $this->db->order_by('id', $_POST['order']['0']['dir']);

       //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->order))
    {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_datatables($user_id)
  {
    $this->_get_datatables_query($user_id);
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function count_filtered($user_id)
  {
    $this->_get_datatables_query($user_id);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all($user_id)
  {
    $this->db->where('p.payment_status',1);
    $this->db->where('p.doctor_id',$user_id);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }



  private function _get_refund_datatables_query($user_id)
  {
  
    $this->db->select('p.*,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.id as patient_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
    $this->db->from($this->table);
    $this->db->join($this->patient, 'pi.id = p.user_id', 'left'); 
    $this->db->join($this->patient_details,'pd.user_id = pi.id','left');
    $this->db->where('p.doctor_id',$user_id);
    $this->db->where('p.payment_status',1);
    $this->db->where('p.request_status',6);

        $i = 0;
  
    foreach ($this->column_search1 as $item) // loop column 
    {
      if($_POST['search']['value']) // if datatable send POST for search
      {
        
        if($i===0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        }
        else
        {
          $this->db->or_like($item, $_POST['search']['value']);
        }

         if(count($this->column_search1) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
            $this->db->order_by('id', $_POST['order']['0']['dir']);

       //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->order))
    {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_refund_datatables($user_id)
  {
    $this->_get_refund_datatables_query($user_id);
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function refund_count_filtered($user_id)
  {
    $this->_get_refund_datatables_query($user_id);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function refund_count_all($user_id)
  {
    $this->db->where('p.payment_status',1);
    $this->db->where('p.request_status',6);
    $this->db->where('p.doctor_id',$user_id);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }



	


  private function _get_doctor_request_datatables_query($user_id)
  {
  
    $this->db->select('p.*,CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage,d.id as doctor_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
    $this->db->from($this->table);
    $this->db->join($this->doctor, 'd.id = p.doctor_id', 'left'); 
    $this->db->join($this->doctor_details,'dd.user_id = d.id','left');
    $this->db->where('p.user_id',$user_id);
    $this->db->where('p.payment_status',1);
    $this->db->where('p.request_status',1);

        $i = 0;
  
    foreach ($this->column_search2 as $item) // loop column 
    {
      if($_POST['search']['value']) // if datatable send POST for search
      {
        
        if($i===0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        }
        else
        {
          $this->db->or_like($item, $_POST['search']['value']);
        }

         if(count($this->column_search2) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
            $this->db->order_by('id', $_POST['order']['0']['dir']);

       //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->order))
    {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_doctor_request_datatables($user_id)
  {
    $this->_get_doctor_request_datatables_query($user_id);
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function doctor_request_filtered($user_id)
  {
    $this->_get_doctor_request_datatables_query($user_id);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function doctor_request_count_all($user_id)
  {
    $this->db->where('p.payment_status',1);
    $this->db->where('p.request_status',1);
    $this->db->where('p.user_id',$user_id);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }


  private function _get_patient_accounts_datatables_query($user_id)
  {
  
    $this->db->select('p.*,CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage,d.id as doctor_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count,d.role');
    $this->db->from($this->table);
    $this->db->join($this->doctor, 'd.id = p.doctor_id', 'left'); 
    $this->db->join($this->doctor_details,'dd.user_id = d.id','left');
    $this->db->where('p.user_id',$user_id);
    $this->db->where('p.payment_status',1);
    

        $i = 0;
  
    foreach ($this->column_search2 as $item) // loop column 
    {
      if($_POST['search']['value']) // if datatable send POST for search
      {
        
        if($i===0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        }
        else
        {
          $this->db->or_like($item, $_POST['search']['value']);
        }

         if(count($this->column_search2) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
            // $this->db->order_by('id', $_POST['order']['0']['dir']);

       $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->order))
    {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_patient_accounts_datatables($user_id)
  {
    $this->_get_patient_accounts_datatables_query($user_id);
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
  
    return $query->result_array();
  }

  public function patient_accounts_filtered($user_id)
  {
    $this->_get_patient_accounts_datatables_query($user_id);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function patient_accounts_count_all($user_id)
  {
    $this->db->where('p.payment_status',1);
    $this->db->where('p.user_id',$user_id);
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  public function get_balance($user_id)
  {
      $this->db->select('p.*,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
    $this->db->from($this->table);
    $this->db->where('p.doctor_id',$user_id);
    $this->db->where('p.payment_status',1);
    $this->db->where('p.request_status',2);
    $result=$this->db->get()->result_array();

    $balance=0;
    if(!empty($result))
    {
      foreach ($result as $rows) {
        
        $tax_amount=$rows['tax_amount']+$rows['transcation_charge'];
        
        $amount=($rows['total_amount']) - ($tax_amount);

        $commission = !empty(settings("commission"))?settings("commission"):"0";
        $commission_charge = ($amount * ($commission/100));
        $balance_temp= $amount - $commission_charge;

        $user_currency=get_user_currency();
        $user_currency_code=$user_currency['user_currency_code'];
        $user_currency_rate=$user_currency['user_currency_rate'];

        $currency_option = (!empty($user_currency_code))?$user_currency_code:default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);

        $org_amount=get_doccure_currency($balance_temp,$rows['currency_code'],$user_currency_code);
        
        $balance +=$org_amount;

      }
    }

    return $balance;
  }

  public function get_requested($user_id)
  {
    $this->db->select('*');
    $this->db->from('payment_request');
    $this->db->where('user_id',$user_id);
    $this->db->where('status',1);
    $result= $this->db->get()->result_array();

    $reuested=0;
    if(!empty($result))
    {
      foreach ($result as $rows) {
        
        
        
        $amount=$rows['request_amount'];

       

        $user_currency=get_user_currency();
        $user_currency_code=$user_currency['user_currency_code'];
        $user_currency_rate=$user_currency['user_currency_rate'];

        $currency_option = (!empty($user_currency_code))?$user_currency_code:default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);

        $org_amount=get_doccure_currency($amount,$rows['currency_code'],$user_currency_code);
        
        $reuested +=$org_amount;

      }
    }

    
    return  $reuested;;
 }

 public function get_up_requested($user_id)
  {
    $this->db->select('*');
    $this->db->from('payment_request');
    $this->db->where('user_id',$user_id);
    $this->db->where('status',1);
    $result= $this->db->get()->result_array();


    // die("die here");
    // $this->db->where('user_id', $user_id);
    // $this->db->update('payment_request', array('doctor_id' => $pharmacy_id));

    // $result= $this->db->get()->result_array();

    // echo '<pre>';
    // print_r($result);
    // echo '</pre>';  
    // die("hgfhg");

    $reuested=0;
    if(!empty($result))
    {
      foreach ($result as $rows) {
        
        
        
        $amount=$rows['request_amount'];

       

        $user_currency=get_user_currency();
        $user_currency_code=$user_currency['user_currency_code'];
        $user_currency_rate=$user_currency['user_currency_rate'];

        $currency_option = (!empty($user_currency_code))?$user_currency_code:default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);

        $org_amount=get_doccure_currency($amount,$rows['currency_code'],$user_currency_code);
        
        $reuested +=$org_amount;

      }
    }

    
    return  $reuested;;
 }

 public function get_earned($user_id)
  {
    $this->db->select('*');
    $this->db->from('payment_request');
    $this->db->where('user_id',$user_id);
    $this->db->where('status',2);
    $result= $this->db->get()->result_array();
     $reuested=0;
    if(!empty($result))
    {
      foreach ($result as $rows) {
        
        
        
        $amount=$rows['request_amount'];

       

        $user_currency=get_user_currency();
        $user_currency_code=$user_currency['user_currency_code'];
        $user_currency_rate=$user_currency['user_currency_rate'];

        $currency_option = (!empty($user_currency_code))?$user_currency_code:default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);

        $org_amount=get_doccure_currency($amount,$rows['currency_code'],$user_currency_code);
        
        $reuested +=$org_amount;

      }
    }

    
    return  $reuested;;
 }

  public function get_patient_balance($user_id)
  {
      $this->db->select('*');
    $this->db->from($this->table);
    $this->db->where('p.user_id',$user_id);
    $this->db->where('p.payment_status',1);
    $this->db->where('p.request_status',7);
    $result= $this->db->get()->result_array();
    

    $balance=0;
    if(!empty($result))
    {
      foreach ($result as $rows) {
        
        $tax_amount=$rows['tax_amount']+$rows['transcation_charge'];
        
        $amount=($rows['total_amount']) - ($tax_amount);

        $commission = !empty(settings("commission"))?settings("commission"):"0";
        $commission_charge = ($amount * ($commission/100));
        $balance_temp= $amount ;

        $user_currency=get_user_currency();
        $user_currency_code=$user_currency['user_currency_code'];
        $user_currency_rate=$user_currency['user_currency_rate'];

        $currency_option = (!empty($user_currency_code))?$user_currency_code:default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);

        $org_amount=get_doccure_currency($balance_temp,$rows['currency_code'],$user_currency_code);
        
        $balance +=$org_amount;

      }
    }

    return $balance;
 }

 public function get_account_details($user_id)
  {
    $this->db->from('account_details');
    $this->db->where('user_id',$user_id);
    $query = $this->db->get();

    return $query->row();
  }
  public function get_insurance_details($user_id)
  {
    $this->db->from('insurance_details');
    $this->db->where('user_id',$user_id);
    $query = $this->db->get();
   
    return $query->row();
  }
	public function notify_touserid($id){
    
		if($this->session->userdata('role')=='1'){
			return $this->db->select('p.user_id')->get_where($this->table,array('p.id'=>$id))->row()->user_id;  
		} else {
			return $this->db->select('p.doctor_id')->get_where($this->table,array('p.id'=>$id))->row()->doctor_id;  
		}
	}
	
	public function apptDetails($id){		
		return $this->db->select('from_date_time')->get_where('appointments',array('payment_id'=>$id))->row()->from_date_time;  		
	}

  public function get_balance_pharmacy($user_id)
  {
    $this->db->select('p.id,p.status as status,p.ordered_at as payment_date,Sum(p.subtotal) as price,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.id as patient_id,pd.currency_code as currency_code');
    $this->db->from('orders p');
    $this->db->join($this->patient, 'pi.id = p.user_id', 'left'); 
    $this->db->join($this->patient_details,'pd.user_id = pi.id','left');
    $this->db->where('p.pharmacy_id',$user_id);
    $this->db->where('p.status',1);
    $this->db->where('transaction_status !=','Pay on arrive');

    $result=$this->db->get()->result_array();
    $balance=0;
    if(!empty($result))
    {
      foreach ($result as $rows) {
        
        
        $amount=$rows['price'];
        $commission = !empty(settings("commission"))?settings("commission"):"0";
        $commission_charge = ($amount * ($commission/100));
        $balance_temp= $amount - $commission_charge;

        $user_currency=get_user_currency();
        $user_currency_code=$user_currency['user_currency_code'];
        $user_currency_rate=$user_currency['user_currency_rate'];


        $org_amount=get_doccure_currency($balance_temp,$rows['currency_code'],$user_currency_code);
     
        $balance += $org_amount;
      }
    }

    return $balance;
  }
  
  
  public function get_balance_lab($user_id)
  {
    $this->db->select('p.*,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.id as patient_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
    $this->db->from('lab_payments p');
    $this->db->join($this->patient, 'pi.id = p.patient_id', 'left'); 
    $this->db->join($this->patient_details,'pd.user_id = pi.id','left');
    $this->db->where('p.lab_id',$user_id);
    $this->db->where('p.status',1);
    $this->db->where('payment_type !=','Pay on arrive');
     

    $result=$this->db->get()->result_array();

    $balance=0;
    if(!empty($result))
    {
      foreach ($result as $rows) {
        
        $tax_amount=$rows['tax_amount']+$rows['transcation_charge'];
        
        $amount=($rows['total_amount']) - ($tax_amount);

        $commission = !empty(settings("commission"))?settings("commission"):"0";
        $commission_charge = ($amount * ($commission/100));
        $balance_temp= $amount - $commission_charge;

        $user_currency=get_user_currency();
        $user_currency_code=$user_currency['user_currency_code'];
        $user_currency_rate=$user_currency['user_currency_rate'];

        $currency_option = (!empty($user_currency_code))?$user_currency_code:default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);

        $org_amount=get_doccure_currency($balance_temp,$rows['currency_code'],$user_currency_code);
        
        $balance += (float)$org_amount;

      }
    }

    return $balance;
  }



	


}
