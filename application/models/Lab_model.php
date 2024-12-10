<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property object $load 
 * @property object $db
 * @property object $session
 */

class Lab_model extends CI_Model {
    var $table = 'lab_tests lt';
    var $users ='users u';
    var $user_details ='users_details ud';
    var $lab_payments = 'lab_payments lp';
    var $patient ='users p';
    var $patient_details ='users_details pd';
    var $column_search = array('CONCAT(u.first_name," ", u.last_name)','lt.lab_test_name','lt.duration','lt.amount','lt.description','date_format(lt.created_date,"%d %b %Y")');
    var $order = array('lt.id' => 'DESC');
    var $appoinments_column_search = array('CONCAT(p.first_name," ", p.last_name)','date_format(lp.lab_test_date,"%d %b %Y")','lp.total_amount','date_format(lp.payment_date,"%d %b %Y")'); 
    var $appoinments_order = array('lp.lab_test_date' => 'DESC'); // default order 
    var $labtest_column_search = array('u.first_name','u.last_name','lt.lab_test_name','lt.amount','lt.duration','lt.description','lt.created_date'); 
    var $labtest_order = array('lt.id' => 'DESC'); // default order 
    var $labtest_column_order = array('','u.first_name','lt.lab_test_name','','lt.duration','lt.description','lt.created_date');
    var $labappoinments_column_search = array('p.first_name','p.last_name','lp.amount','lp.lab_test_date','lp.payment_date'); 
    var $labappoinments_order = array('lp.id' => 'DESC'); // default order 
    
    public function __construct()
    {
        parent::__construct();
            $this->load->database();
    }
    
    private function _get_datatables_query($user_id)
    {
  
        $this->db->select('lt.*, CONCAT(u.first_name," ", u.last_name) as lab_name, u.profileimage,u.profileimage as lab_profileimage');
        $this->db->from($this->table);
        $this->db->join($this->users, 'u.id = lt.lab_id', 'left'); 
        //$this->db->join($this->user_details,'ud.user_id = lt.lab_id','left');
        $this->db->where('lt.lab_id',$user_id);
        $i = 0;
        foreach ($this->column_search as $item) 
        {
            if($_POST['search']['value']) 
            {

                if ($item == 'created_date') {
                    $_POST['search']['value'] = date('d M Y',$_POST['search']['value']);
                    // date('d M Y',strtotime($lab_tests['created_date']))
                    // $item = 
                }

                if ($item == 'lab_name') {
                    $_POST['search']['value'] = $_POST['search']['value'];
                }                

                if($i===0) 
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                    if(count($this->column_search) - 1 == $i)
                        $this->db->group_end();
            }
            $i++;
        }
        if(isset($_POST['order']))
        {
            $this->db->order_by('id', $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    
    private function _get_appointment_datatables_query($lab_id) {
        $this->db->select('lp.*, CONCAT(p.first_name," ", p.last_name) as patient_name,lp.booking_ids as test_ids');
        $this->db->from($this->lab_payments);
        $this->db->join($this->patient, 'p.id = lp.patient_id', 'left'); 
       // $this->db->join($this->table,'lt.lab_id = u.id','left');
        if($this->session->userdata('role')==4)
            $this->db->where('lp.lab_id',$this->session->userdata('user_id'));
        if($this->session->userdata('role')==2)
            $this->db->where('lp.patient_id',$this->session->userdata('user_id'));
        
        $i = 0;
        foreach ($this->appoinments_column_search as $item) 
        {
            if($_POST['search']['value']) 
            {

                if($i===0) 
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                    if(count($this->appoinments_column_search) - 1 == $i)
                        $this->db->group_end();
            }
            $i++;
        }
        if(isset($_POST['order']))
        {
            $this->db->order_by('id', $_POST['order']['0']['dir']);
        } 
        else if(isset($this->appoinments_order))
        {
            $order = $this->appoinments_order;
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
        $this->db->where('lt.lab_id',$user_id);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    
    public function get_lab_test_by_id($id) {
        $this->db->from($this->table);
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_appointments($id) {
        $this->db->from($this->table);
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->row();
    }
    
    public function get_lab_test_by_lab_id($lab_id) {
        $this->db->from($this->table);
        $this->db->where('lab_id',$lab_id);
        // New Code
        $this->db->where('status',1);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function get_lab_test_amount($booking_ids) {
        $this->db->select('SUM(lt.amount) as total_amt');
        $this->db->from($this->table);
         $this->db->where('id IN(' .$booking_ids.')');
        $this->db->group_by('lab_id');
        $query = $this->db->get();
        return $result = $query->row_array();
    }
    
    public function get_appointment_details($lab_id) {
        $this->_get_appointment_datatables_query($lab_id);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
    }

     private function _get_labappointment_datatables_query($user_id) {
        $current_date = date('Y-m-d');
        $this->db->select('lp.*, CONCAT(p.first_name," ", p.last_name) as patient_name,lp.booking_ids as test_ids');
        $this->db->from($this->lab_payments);
        $this->db->join($this->patient, 'p.id = lp.patient_id', 'left'); 
       // $this->db->join($this->table,'lt.lab_id = u.id','left');
        // if($this->session->userdata('role')==4)
        $this->db->where('lp.lab_id',$this->session->userdata('user_id'));
        // if($this->session->userdata('role')==2)
        //     $this->db->where('lp.patient_id',$this->session->userdata('user_id'));
        if($_POST['type'] == 1){
          $this->db->where('lp.lab_test_date',$current_date);
        }

        if($_POST['type'] == 2){
          $this->db->where('lp.lab_test_date > ',$current_date);
        }

        $i = 0;
        foreach ($this->appoinments_column_search as $item) 
        {
            if($_POST['search']['value']) 
            {

                if($i===0) 
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                    if(count($this->appoinments_column_search) - 1 == $i)
                        $this->db->group_end();
            }
            $i++;
        }
        if(isset($_POST['order']))
        {
            $this->db->order_by('id', $_POST['order']['0']['dir']);
        } 
        else if(isset($this->appoinments_order))
        {
            $order = $this->appoinments_order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_labappointment_details($user_id) {
        $this->_get_labappointment_datatables_query($user_id);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function labappointments_count_filtered($user_id)
    {
        $this->_get_labappointment_datatables_query($user_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function labappointments_count_all($user_id)
    {
        $this->db->where('lp.lab_id',$user_id);
        $this->db->from($this->lab_payments);
        return $this->db->count_all_results();
    }
    
    public function appointments_count_filtered($user_id)
    {
        $this->_get_appointment_datatables_query($user_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function appointments_count_all($user_id)
    {
        $this->db->where('lp.lab_id',$user_id);
        $this->db->from($this->lab_payments);
        return $this->db->count_all_results();
    }

    private function _get_labtest__datatables_query()
    {
    
          $this->db->select('lt.*,u.first_name,u.last_name');
          $this->db->from($this->table); 
          $this->db->join($this->users,'u.id = lt.lab_id','left'); 
          
     
      $i = 0;
    
      foreach ($this->labtest_column_search as $item) // loop column 
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
  
           if(count($this->labtest_column_search) - 1 == $i) //last loop
            $this->db->group_end(); //close bracket
        }
        $i++;
      }
      
      if(isset($_POST['order'])) // here order processing
      {
              // $this->db->order_by('id', $_POST['order']['0']['dir']);
  
         $this->db->order_by($this->labtest_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
      } 
      else if(isset($this->labtest_order))
      {
        $order = $this->labtest_order;
        $this->db->order_by(key($order), $order[key($order)]);
      }
    }
    
    public function get_labtest_datatables() {
        $this->_get_labtest__datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function labtest_count_filtered()
    {
        $this->_get_labtest__datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function labtest_count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function lab_cybersource_pay($session_id, $intent)
  {
    $sessionDetails = $this->db->where('id', $session_id)->get('session_details')->row_array();  
    $booking_details_session        = (array) json_decode($sessionDetails['session_data']); 
    

    $lab_id = $booking_details_session['lab_id'];
    $booking_ids = $booking_details_session['booking_ids'];
    $lab_test_date = $booking_details_session['lab_test_date'];
    $tax_amount = $booking_details_session['tax_amount'];
    $transcation_charge = $booking_details_session['transcation_charge'];
    $transcation_charge_percent = $booking_details_session['transcation_charge_percent'];
    $currency_code = $booking_details_session['currency_code'];
    $lab_time_zone = $booking_details_session['time_zone'];
    $tax_value = $booking_details_session['tax_value'];
    $booking_ids_price = $booking_details_session['booking_ids_price'];

    $amount = $booking_details_session['total_amount'];
    $amount = number_format($amount, 2, '.', '');

    $transaction_status = $intent;
    $txnid = time() . rand();

    /* Get Invoice id */
    $invoice = $this->db->order_by('id', 'desc')->limit(1)->get('payments')->row_array();
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
      'payment_type' => 'Cybersource',
      'tax' => !empty($tax_value) ? $tax_value : "0",
      'tax_amount' => $tax_amount,
      'transcation_charge' => $transcation_charge,
      'payment_status' => 1,
      'payment_date' => date('Y-m-d H:i:s'),
      'booking_ids_price' => $booking_ids_price
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
      'tax' => !empty($tax_value) ? $tax_value : "0",
      'tax_amount' => $tax_amount,
      'transcation_charge' => $transcation_charge,
      'transaction_charge_percentage' => !empty($transcation_charge_percent) ? $transcation_charge_percent : "0",
      'payment_status' => 1,
      'payment_date' => date('Y-m-d H:i:s'),
      'time_zone' => $lab_time_zone,
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
    return true;
  }
    
}

