<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 * @property object $session
 * @property object $quoation_column_search
 */
class My_patients_model extends CI_Model
{

  var $appoinments = 'appointments a';
  var $prescription = 'prescription p';
  var $medical_records = 'medical_records m';
  var $billing = 'billing b';

  var $users = 'users u';
  var $users_details = 'users_details ud';
  var $specialization = 'specialization s';

  var $appoinments_column_search = array('u.first_name', 'u.last_name', 'CONCAT(u.first_name," ", u.last_name)', 'u.profileimage', 'a.appointment_date', 'a.created_date', 'date_format(a.appointment_date,"%d %b %Y")', 'date_format(a.created_date,"%d %b %Y")', 'a.type');
  var $appoinments_order = array('a.id' => 'DESC'); // default order 
  var $appoinments_column_order = array('', 'u.first_name', 'a.appointment_date', 'a.created_date', 'a.type'); // datatable column order 

  var $prescription_column_search = array('u.first_name', 'u.last_name', 'CONCAT(u.first_name," ", u.last_name)', 'u.profileimage', 'p.created_at', 'date_format(p.created_at,"%d %b %Y")');
  var $prescription_order = array('p.id' => 'DESC'); // default order 
  var $prescription_column_order = array('u.first_name', 'p.created_at');


  var $medical_records_column_search = array('u.first_name', 'u.last_name', 'CONCAT(u.first_name," ", u.last_name)', 'u.profileimage', 'm.date', 'date_format(m.date,"%d %b %Y")');
  var $medical_records_order = array('m.id' => 'DESC'); // default order 

  var $billing_column_search = array('CONCAT(u.first_name," ", u.last_name)', 'b.bill_no','u.profileimage', 'date_format(b.created_at,"%d %b %Y")');
  var $billing_records_order = array('b.id' => 'DESC'); // default order 


  var $quotations = 'patient_request_quotation q';
  var $quotation_column_search = array('u.first_name', 'u.last_name');
  var $quotation_order = array('q.id' => 'DESC'); // default order
  var $pharmacy = 'users p';


  public function __construct()
  {
    parent::__construct();
  }

  public function patient_list($page, $limit, $type, $user_id)
  {
    $this->db->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname');
    $this->db->from('appointments a');
    $this->db->join('users u', 'a.appointment_from = u.id', 'left');
    $this->db->join('users_details ud', 'ud.user_id = u.id', 'left');
    $this->db->join('country c', 'ud.country = c.countryid', 'left');
    $this->db->join('state s', 'ud.state = s.id', 'left');
    $this->db->join('city ci', 'ud.city = ci.id', 'left');
    $this->db->where('u.role', '2');
    $this->db->where('a.appointment_to', $user_id);
    $this->db->where('u.status', '1');
    // $this->db->group_by('a.appointment_from');
    $this->db->group_by('a.appointment_from, u.id, ud.id, ud.user_id, ud.country, ud.state, ud.city');
    //  $pageMul = $this->input->post('page');
    //  $page = (int)$pageMul;
    if ($type == 1) {
      return $this->db->count_all_results();
    } else {

      $page = !empty($page) ? $page : '';
      if ($page >= 1) {
        $page = $page - 1;
      }
      $page =  ((int)$page * $limit);//added type casting
      $this->db->limit($limit, $page);
      return $this->db->get()->result_array();
    }
  }

  public function get_patient_details($userid)
  {
    $this->db->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname');
    $this->db->from('users u');
    $this->db->join('users_details ud', 'ud.user_id = u.id', 'left');
    $this->db->join('country c', 'ud.country = c.countryid', 'left');
    $this->db->join('state s', 'ud.state = s.id', 'left');
    $this->db->join('city ci', 'ud.city = ci.id', 'left');
    $this->db->where('u.id', $userid);
    return $result = $this->db->get()->row_array();
  }

  private function _get_appoinments__datatables_query()
  {

    $this->db->select('a.*, u.first_name,u.last_name,u.username,u.profileimage,s.specialization,cliu.first_name as clinic_first_name,cliu.last_name as clinic_last_name,u.role,cliu.username as clinic_username');
    $this->db->from($this->appoinments);
    $this->db->join($this->users, 'u.id = a.appointment_to', 'left');
    $this->db->join($this->users_details, 'ud.user_id = u.id', 'left');
    $this->db->join($this->specialization, 'ud.specialization = s.id', 'left');
    $this->db->where('a.appointment_from', $_POST['patient_id']);
    if (is_doctor()) {
      $this->db->where('a.appointment_to', $this->session->userdata('user_id'));
    }
    $this->db->join('users cliu', 'cliu.id = a.hospital_id', 'left');
    $this->db->join('users_details clud', 'clud.user_id = cliu.id', 'left');
    // $this->db->order_by('a.from_date_time','DESC');

    $i = 0;

    foreach ($this->appoinments_column_search as $item) // loop column 
    {
      if ($_POST['search']['value']) // if datatable send POST for search
      {

        if ($item == 'created_date') {
          $_POST['search']['value'] = date('d M Y', $_POST['search']['value']);
          // date('d M Y',strtotime($lab_tests['created_date']))
          // $item = 
        }
        if ($item == 'appointment_date') {
          $_POST['search']['value'] =  date('d M Y', $_POST['search']['value']);
        }
        if ($item == 'type') {
          $_POST['search']['value'] = $_POST['search']['value'];
        }
        if ($item == 'first_name') {
          $_POST['search']['value'] = $_POST['search']['value'];
        }

        if ($i === 0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if (count($this->appoinments_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }

    if (isset($_POST['order'])) // here order processing
    {
      // $this->db->order_by('id', $_POST['order']['0']['dir']);

      $this->db->order_by($this->appoinments_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->appoinments_order)) {
      $order = $this->appoinments_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_appoinments_datatables()
  {
    $this->_get_appoinments__datatables_query();
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function appoinments_count_filtered()
  {
    $this->_get_appoinments__datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function appoinments_count_all()
  {
    $this->db->from($this->appoinments);
    $this->db->where('a.appointment_from', $_POST['patient_id']);
    if (is_doctor()) {
      $this->db->where('a.appointment_to', $this->session->userdata('user_id'));
    }
    return $this->db->count_all_results();
  }

  public function get_last_booking($userid)
  {

    $this->db->select('a.*, u.first_name,u.last_name,u.username,u.profileimage,s.specialization');
    $this->db->from($this->appoinments);
    $this->db->join($this->users, 'u.id = a.appointment_to', 'left');
    $this->db->join($this->users_details, 'ud.user_id = u.id', 'left');
    $this->db->join($this->specialization, 'ud.specialization = s.id', 'left');
    $this->db->where('a.appointment_from', $userid);
    if (is_doctor()) {
      $this->db->where('a.appointment_to', $this->session->userdata('user_id'));
    }

    $this->db->order_by('id', 'DESC');
    $this->db->limit('2');
    $query = $this->db->get();
    return $query->result_array();
  }


  /* prescription*/

  private function _get_prescription__datatables_query()
  {

    $this->db->select('p.*, u.first_name,u.last_name,u.username,u.profileimage,s.specialization');
    $this->db->from($this->prescription);
    $this->db->join($this->users, 'u.id = p.doctor_id', 'left');
    $this->db->join($this->users_details, 'ud.user_id = u.id', 'left');
    $this->db->join($this->specialization, 'ud.specialization = s.id', 'left');
    $this->db->join($this->quotations, 'p.id = q.prescription_id', 'left');
    $this->db->where('p.patient_id', $_POST['patient_id']);
    $this->db->where('p.status', 1);
    if (is_doctor()) {
      $this->db->where('p.doctor_id', $this->session->userdata('user_id'));
    }



    $i = 0;

    foreach ($this->prescription_column_search as $item) // loop column 
    {
      if ($_POST['search']['value']) // if datatable send POST for search
      {
        if ($item == 'created_date') {
          $_POST['search']['value'] = date('d M Y', $_POST['search']['value']);
          // date('d M Y',strtotime($lab_tests['created_date']))
          // $item = 
        }

        if ($i === 0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if (count($this->prescription_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }

    if (isset($_POST['order'])) // here order processing
    {
      //  $this->db->order_by('id', $_POST['order']['0']['dir']);

      $this->db->order_by($this->prescription_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->prescription_order)) {
      $order = $this->prescription_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  private function _get_newquotations_datatables_query()
  {

    $this->db->select('q.*, CONCAT(u.first_name," ", u.last_name) as patient_name,u.profileimage, CONCAT(p.first_name," ",p.last_name) as pharmacy_name, p.pharmacy_name as pharmacy');
    $this->db->from($this->quotations);
    $this->db->join($this->users, 'u.id = q.patient_id', 'left');
    $this->db->join($this->pharmacy, 'p.id = q.pharmacy_id', 'left');
    $this->db->where('q.quotation_request_status', 'pending');
    $this->db->where('q.patient_id', $this->session->userdata('user_id'));

    $i = 0;
    foreach ($this->quotation_column_search as $item) // loop column 
    {
      if ($_POST['search']['value']) // if datatable send POST for search
      {

        if ($i === 0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if (count($this->quoation_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }

    if (isset($_POST['order'])) // here order processing
    {
      $this->db->order_by('id', $_POST['order']['0']['dir']);

      //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->quotation_order)) {
      $order = $this->quotation_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_prescription_datatables()
  {
    $this->_get_prescription__datatables_query();
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function prescription_count_filtered()
  {
    $this->_get_prescription__datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function prescription_count_all()
  {
    $this->db->from($this->prescription);
    $this->db->where('p.patient_id', $_POST['patient_id']);
    $this->db->where('p.status', 1);
    if (is_doctor()) {
      $this->db->where('p.doctor_id', $this->session->userdata('user_id'));
    }
    return $this->db->count_all_results();
  }

  public function get_prescription($prescription_id)
  {
    $this->db->select('pd.*,p.signature_id,s.img,s.rowno');
    $this->db->from('prescription_item_details pd');
    $this->db->join('prescription p', 'pd.prescription_id=p.id', 'left');
    $this->db->join('signature s', 'p.signature_id=s.id', 'left');
    $this->db->where('p.id', $prescription_id);
    return $this->db->get()->result_array();
  }

  public function get_prescription_details($prescription_id)
  {
    $this->db->select('CONCAT(u.first_name," ", u.last_name) as doctor_name,CONCAT(u1.first_name," ", u1.last_name) as patient_name,pd.*,p.signature_id,s.img,s.rowno,DATE_FORMAT(pd.created_at, "%d-%m-%Y") as prescription_date');
    $this->db->from('prescription_item_details pd');
    $this->db->join('prescription p', 'pd.prescription_id=p.id', 'left');
    $this->db->join('signature s', 'p.signature_id=s.id', 'left');
    $this->db->join('users u', 'u.id = p.doctor_id', 'left');
    $this->db->join('users u1', 'u1.id = p.patient_id', 'left');
    $this->db->where('p.id', $prescription_id);
    return $this->db->get()->result_array();
  }


  /* medical_record*/

  private function _get_medical_record__datatables_query()
  {

    $this->db->select('m.*, u.first_name,u.last_name,u.username,u.profileimage,s.specialization');
    $this->db->from($this->medical_records);
    $this->db->join($this->users, 'u.id = m.doctor_id', 'left');
    $this->db->join($this->users_details, 'ud.user_id = u.id', 'left');
    $this->db->join($this->specialization, 'ud.specialization = s.id', 'left');
    $this->db->where('m.patient_id', $_POST['patient_id']);
    $this->db->where('m.status', 1);
    if (is_doctor()) {
      $this->db->where('m.doctor_id', $this->session->userdata('user_id'));
    }

    $i = 0;

    foreach ($this->medical_records_column_search as $item) // loop column 
    {
      if ($_POST['search']['value']) // if datatable send POST for search
      {

        if ($item == 'date') {
          $_POST['search']['value'] = date('d M Y', $_POST['search']['value']);
          // date('d M Y',strtotime($lab_tests['created_date']))
          // $item = 
        }
        if ($item == 'first_name') {
          $_POST['search']['value'] = $_POST['search']['value'];
        }

        if ($i === 0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if (count($this->medical_records_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }

    if (isset($_POST['order'])) // here order processing
    {
      $this->db->order_by('id', $_POST['order']['0']['dir']);

      //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->medical_records_order)) {
      $order = $this->medical_records_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_medical_record_datatables()
  {
    $this->_get_medical_record__datatables_query();
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function medical_record_count_filtered()
  {
    $this->_get_medical_record__datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function medical_record_count_all()
  {
    $this->db->from($this->medical_records);
    $this->db->where('m.patient_id', $_POST['patient_id']);
    $this->db->where('m.status', 1);
    if (is_doctor()) {
      $this->db->where('m.doctor_id', $this->session->userdata('user_id'));
    }
    return $this->db->count_all_results();
  }
  public function get_medical_records_details($mrid)
  {
    $this->db->select('file_name');
    $this->db->from('medical_records');
    $this->db->where('id', $mrid);
    return $this->db->get()->result_array();
  }
  public function get_medical_record_row($mrid)
  {
    $this->db->select('*');
    $this->db->from('medical_records');
    $this->db->where('id', $mrid);
    return $this->db->get()->result_array();
  }

  /* billing*/

  private function _get_billing__datatables_query()
  {

    $this->db->select('b.*, u.first_name,u.last_name,u.username,u.profileimage,s.specialization');
    $this->db->from($this->billing);
    $this->db->join($this->users, 'u.id = b.doctor_id', 'left');
    $this->db->join($this->users_details, 'ud.user_id = u.id', 'left');
    $this->db->join($this->specialization, 'ud.specialization = s.id', 'left');
    $this->db->where('b.patient_id', $_POST['patient_id']);
    $this->db->where('b.status', 1);
    if (is_doctor()) {
      $this->db->where('b.doctor_id', $this->session->userdata('user_id'));
    }

    $i = 0;

    foreach ($this->billing_column_search as $item) // loop column 
    {
      if ($_POST['search']['value']) // if datatable send POST for search
      {

        if ($item == 'created_at') {
          $_POST['search']['value'] = date('d M Y', $_POST['search']['value']);
          // date('d M Y',strtotime($lab_tests['created_date']))
          // $item = 
        }



        if ($i === 0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if (count($this->billing_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }

    if (isset($_POST['order'])) // here order processing
    {
      $this->db->order_by('id', $_POST['order']['0']['dir']);

      //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->billing_records_order)) {
      $order = $this->billing_records_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_billing_datatables()
  {
    $this->_get_billing__datatables_query();
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function billing_count_filtered()
  {
    $this->_get_billing__datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function billing_count_all()
  {
    $this->db->from($this->billing);
    $this->db->where('b.patient_id', $_POST['patient_id']);
    $this->db->where('b.status', 1);
    if (is_doctor()) {
      $this->db->where('b.doctor_id', $this->session->userdata('user_id'));
    }
    return $this->db->count_all_results();
  }

  public function get_billing($billing_id)
  {
    $this->db->select('bd.*,b.signature_id,s.img,s.rowno');
    $this->db->from('billing_item_details bd');
    $this->db->join('billing b', 'bd.billing_id=b.id', 'left');
    $this->db->join('signature s', 'b.signature_id=s.id', 'left');
    $this->db->where('b.id', $billing_id);
    return $this->db->get()->result_array();
  }

  public function get_billing_details($billing_id)
  {
    $this->db->select('CONCAT(u.first_name," ", u.last_name) as doctor_name,CONCAT(u1.first_name," ", u1.last_name) as patient_name,bd.*,b.signature_id,s.img,s.rowno,DATE_FORMAT(bd.created_at, "%d-%m-%Y") as billing_date');
    $this->db->from('billing_item_details bd');
    $this->db->join('billing b', 'bd.billing_id=b.id', 'left');
    $this->db->join('signature s', 'b.signature_id=s.id', 'left');
    $this->db->join('users u', 'u.id = b.doctor_id', 'left');
    $this->db->join('users u1', 'u1.id = b.patient_id', 'left');
    $this->db->where('b.id', $billing_id);
    return $this->db->get()->result_array();
  }

  public function search_pharmacy($page, $limit, $type)
  {

    $this->db->select('p.id as pharmacy_id, p.first_name,p.last_name,p.pharmacy_name,p.profileimage, p.mobileno');
    $this->db->select('pd.address1,pd.address2,c.country, c.phonecode,s.statename, ci.city, pd.postal_code');
    $this->db->select('ps.home_delivery, ps.24hrsopen, ps.pharamcy_opens_at');
    $this->db->from('users p');
    $this->db->join('users_details pd', 'p.id = pd.user_id', 'left');
    $this->db->join('pharmacy_specifications ps', 'p.id = ps.pharmacy_id', 'left');
    $this->db->join('state s', 's.id = pd.state', 'left');
    $this->db->join('city ci', 'ci.id = pd.city', 'left');
    $this->db->join('country c', 'c.countryid = pd.country', 'left');
    $this->db->where('p.role', 5);
    $this->db->where('p.status', 1);
    $this->db->where('p.is_verified', 1);
    $this->db->where('p.is_updated', 1);

    if (!empty($_POST['city'])) {
      $this->db->where('pd.city', $_POST['city']);
    }
    if (!empty($_POST['state'])) {
      $this->db->where('pd.state', $_POST['state']);
    }
    if (!empty($_POST['country'])) {
      $this->db->where('pd.country', $_POST['country']);
    }
    // services filter
    if (!empty($_POST['hrsopen'])) {
      $this->db->where('ps.24hrsopen', $_POST['hrsopen']);
    }
    if (!empty($_POST['home_delivery'])) {
      $this->db->where('ps.home_delivery', $_POST['home_delivery']);
    }
    // services filter end
    if ($_POST['order_by'] == 'Latest') {
      $this->db->order_by('p.id', 'DESC');
    }

    if ($type == 1) {
      return $this->db->count_all_results();
    } else {

      $page = !empty($page) ? $page : '';
      if ($page >= 1) {
        $page = $page - 1;
      }
      $page =  ($page * $limit);
      $this->db->limit($limit, $page);
      return $this->db->get()->result_array();
    }
  }

  public function get_selected_pharmacy_details($pharmacy_id = NULL)
  {
    $this->db->select('p.id as pharmacy_id, p.first_name,p.last_name,p.pharmacy_name,p.profileimage, p.mobileno');
    $this->db->select('pd.address1,pd.address2,c.country, c.phonecode,s.statename, ci.city, pd.postal_code');
    $this->db->select('ps.home_delivery, ps.24hrsopen,ps.24hrsopen as hrsopen, ps.pharamcy_opens_at');
    $this->db->from('users p');
    $this->db->join('users_details pd', 'p.id = pd.user_id', 'left');
    $this->db->join('pharmacy_specifications ps', 'p.id = ps.pharmacy_id', 'left');
    $this->db->join('state s', 's.id = pd.state', 'left');
    $this->db->join('city ci', 'ci.id = pd.city', 'left');
    $this->db->join('country c', 'c.countryid = pd.country', 'left');
    $this->db->where_in('p.id', $pharmacy_id);
    $this->db->where('p.role', 5);
    $this->db->where('p.status', 1);
    return $this->db->get()->row_array();
  }

  private function _get_completedquotations_datatables_query()
  {

    $this->db->select('q.*, CONCAT(u.first_name," ", u.last_name) as patient_name,u.profileimage, CONCAT(p.first_name," ",p.last_name) as pharmacy_name, p.pharmacy_name as pharmacy');
    $this->db->select('q.*, CONCAT(u.first_name," ", u.last_name) as patient_name,u.profileimage');
    $this->db->from($this->quotations);
    $this->db->join($this->users, 'u.id = q.patient_id', 'left');
    $this->db->join($this->pharmacy, 'p.id = q.pharmacy_id', 'left');
    $this->db->where('q.quotation_request_status', 'completed');
    $this->db->where('q.patient_id', $this->session->userdata('user_id'));

    $i = 0;

    foreach ($this->quotation_column_search as $item) // loop column 
    {
      if ($_POST['search']['value']) // if datatable send POST for search
      {

        if ($i === 0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if (count($this->quoation_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }

    if (isset($_POST['order'])) // here order processing
    {
      $this->db->order_by('id', $_POST['order']['0']['dir']);

      //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->quotation_order)) {
      $order = $this->quotation_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }


  private function _get_cancelledquotations_datatables_query()
  {

    $this->db->select('q.*, CONCAT(u.first_name," ", u.last_name) as patient_name,u.profileimage, CONCAT(p.first_name," ",p.last_name) as pharmacy_name, p.pharmacy_name as pharmacy');
    $this->db->select('q.*, CONCAT(u.first_name," ", u.last_name) as patient_name,u.profileimage');
    $this->db->from($this->quotations);
    $this->db->join($this->users, 'u.id = q.patient_id', 'left');
    $this->db->join($this->pharmacy, 'p.id = q.pharmacy_id', 'left');
    $this->db->where('q.quotation_request_status', 'cancelled');
    $this->db->where('q.patient_id', $this->session->userdata('user_id'));

    $i = 0;

    foreach ($this->quotation_column_search as $item) // loop column 
    {
      if ($_POST['search']['value']) // if datatable send POST for search
      {

        if ($i === 0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if (count($this->quoation_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }

    if (isset($_POST['order'])) // here order processing
    {
      $this->db->order_by('id', $_POST['order']['0']['dir']);

      //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->quotation_order)) {
      $order = $this->quotation_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  private function _get_waitingquotations_datatables_query()
  {

    $this->db->select('q.*, CONCAT(u.first_name," ", u.last_name) as patient_name,u.profileimage, CONCAT(p.first_name," ",p.last_name) as pharmacy_name, p.pharmacy_name as pharmacy');
    $this->db->from($this->quotations);
    $this->db->join($this->users, 'u.id = q.patient_id', 'left');
    $this->db->join($this->pharmacy, 'p.id = q.pharmacy_id', 'left');
    $this->db->where('q.quotation_request_status', 'waiting');
    $this->db->where('q.patient_id', $this->session->userdata('user_id'));

    $i = 0;

    foreach ($this->quotation_column_search as $item) // loop column 
    {
      if ($_POST['search']['value']) // if datatable send POST for search
      {

        if ($i === 0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        } else {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if (count($this->quoation_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }

    if (isset($_POST['order'])) // here order processing
    {
      $this->db->order_by('id', $_POST['order']['0']['dir']);

      //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->quotation_order)) {
      $order = $this->quotation_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_newquotations_datatables()
  {
    $this->_get_newquotations_datatables_query();
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function newquotations_count_filtered()
  {
    $this->_get_newquotations_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function newquotations_count_all()
  {
    $this->db->from($this->quotations);
    return $this->db->count_all_results();
  }

  public function get_completedquotations_datatables()
  {
    $this->_get_completedquotations_datatables_query();
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function completedquotations_count_filtered()
  {
    $this->_get_completedquotations_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function completedquotations_count_all()
  {
    $this->db->from($this->quotations);
    return $this->db->count_all_results();
  }

  public function get_cancelledquotations_datatables()
  {
    $this->_get_cancelledquotations_datatables_query();
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function cancelledquotations_count_filtered()
  {
    $this->_get_cancelledquotations_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function cancelledquotations_count_all()
  {
    $this->db->from($this->quotations);
    return $this->db->count_all_results();
  }

  public function get_waitingquotations_datatables()
  {
    $this->_get_waitingquotations_datatables_query();
    if ($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function waitingquotations_count_filtered()
  {
    $this->_get_waitingquotations_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function waitingquotations_count_all()
  {
    $this->db->from($this->quotations);
    return $this->db->count_all_results();
  }

  public function get_quotation_details($prescription_id = NULL, $pharmacy_id = NULL, $patient_id = NULL, $quotation_id = NULL)
  {

    $this->db->select('q.id as quotation_table_id, q.amount, q.tax_amount,q.total_amount, q.created_date as quotation_date, CONCAT(u.first_name," ", u.last_name) as doctor_name,CONCAT(u1.first_name," ", u1.last_name) as patient_name,pd.*,p.signature_id,s.img,s.rowno,DATE_FORMAT(pd.created_at, "%d-%m-%Y") as prescription_date');
    $this->db->from('prescription_item_details pd');
    $this->db->join('prescription p', 'pd.prescription_id=p.id', 'left');
    $this->db->join('signature s', 'p.signature_id=s.id', 'left');
    $this->db->join('users u', 'u.id = p.doctor_id', 'left');
    $this->db->join('users u1', 'u1.id = p.patient_id', 'left');
    $this->db->join('quotation q', 'q.prescription_item_id = pd.id', 'left');
    $this->db->where('p.id', $prescription_id);
    $this->db->where('q.pharmacy_id', $pharmacy_id);
    $this->db->where('q.patient_id', $patient_id);
    $this->db->where('q.patient_req_quotation_id', $quotation_id);
    return $this->db->get()->result_array();
  }

  public function quotation_status_change($quotation_request_id, $status)
  {
    $update_data = array('quotation_request_status' => $status);
    $this->db->where('id', $quotation_request_id);
    $this->db->update('patient_request_quotation', $update_data);
    return $this->db->affected_rows();
  }

  public function get_patient_appointments($patient_id = NULL)
  {
    if ($patient_id != NULL) {
      $this->db->select('a.*, u.first_name,u.last_name,u.username,u.profileimage,s.specialization');
      $this->db->from($this->appoinments);
      $this->db->join($this->users, 'u.id = a.appointment_to', 'left');
      $this->db->join($this->users_details, 'ud.user_id = u.id', 'left');
      $this->db->join($this->specialization, 'ud.specialization = s.id', 'left');
      $this->db->where('a.appointment_from', $patient_id);
      if (is_doctor()) {
        $this->db->where('a.appointment_to', $this->session->userdata('user_id'));
      }

      return $this->db->get()->result_array();
    }
  }

  public function get_prescription_patient($patient_id = NULL)
  {
    $this->db->select('p.*, u.first_name,u.last_name,u.username,u.profileimage,s.specialization, CONCAT(u.first_name," ", u.last_name) as doctor_name, DATE_FORMAT(p.created_at, "%d-%m-%Y") as prescription_date');
    $this->db->from($this->prescription);
    $this->db->join($this->users, 'u.id = p.doctor_id', 'left');
    $this->db->join($this->users_details, 'ud.user_id = u.id', 'left');
    $this->db->join($this->specialization, 'ud.specialization = s.id', 'left');
    $this->db->where('p.patient_id', $patient_id);
    $this->db->where('p.status', 1);
    if (is_doctor()) {
      $this->db->where('p.doctor_id', $this->session->userdata('user_id'));
    }
    $prescription = $this->db->get()->result_array();
    if (!empty($prescription)) {
      foreach ($prescription as $prescription_key => $prescription_val) {
        $prescription_id = $prescription_val['id'];
        $prescription_item_details = $this->get_prescription_details($prescription_id);
        //echo "<pre>"; print_r($prescription_item_details); exit;
        if (!empty($prescription_item_details)) {
          $prescription[$prescription_key]['prescription_item_details'] = $prescription_item_details;
        }
      }
    }
    if (!empty($prescription)) {
      return $prescription;
    } else {
      return array();
    }
  }

  public function get_medical_record_patient($patient_id = NULL)
  {
    if ($patient_id != NULL) {
      $this->db->select('m.*, u.first_name,u.last_name,u.username,u.profileimage,s.specialization');
      $this->db->from($this->medical_records);
      $this->db->join($this->users, 'u.id = m.doctor_id', 'left');
      $this->db->join($this->users_details, 'ud.user_id = u.id', 'left');
      $this->db->join($this->specialization, 'ud.specialization = s.id', 'left');
      $this->db->where('m.patient_id', $patient_id);
      $this->db->where('m.status', 1);
      if (is_doctor()) {
        $this->db->where('m.doctor_id', $this->session->userdata('user_id'));
      }

      return $this->db->get()->result_array();
    }

    return array();
  }

  public function get_all_pharmacy_details($limit = NULL, $offset = NULL)
  {
    $this->db->select('p.id as pharmacy_id, p.first_name,p.last_name,p.pharmacy_name,p.profileimage, p.mobileno');
    $this->db->select('pd.address1,pd.address2,c.country, c.phonecode,s.statename, ci.city, pd.postal_code');
    $this->db->select('ps.home_delivery, ps.24hrsopen, ps.pharamcy_opens_at');
    $this->db->from('users p');
    $this->db->join('users_details pd', 'p.id = pd.user_id', 'left');
    $this->db->join('pharmacy_specifications ps', 'p.id = ps.pharmacy_id', 'left');
    $this->db->join('state s', 's.id = pd.state', 'left');
    $this->db->join('city ci', 'ci.id = pd.city', 'left');
    $this->db->join('country c', 'c.countryid = pd.country', 'left');
    $this->db->where('p.role', 5);
    $this->db->where('p.status', 1);

    $this->db->limit($limit, $offset);
    return $this->db->get()->result_array();
  }

  public function get_pharm_products($productName)
  {
    $this->db->select('product_name, status, id');
    $this->db->from('pharmacy_products');
    $this->db->where('product_name', $productName);

    return $this->db->get()->result_array();
  }

  public function get_booking_prescription_status($userid)
  {
    $this->db->select("count('a.*') as total, sum(case when a.appointment_status = '0' then 1 else 0 end) AS new, sum(case when a.appointment_status = '1' and a.call_status = 1 then 1 else 0 end) AS completed, sum(case when a.appointment_status = '2' then 1 else 0 end) AS expired");
    $this->db->from($this->appoinments);
    $this->db->where('a.appointment_from', $userid);
    if (is_doctor()) {
      $this->db->where('a.appointment_to', $this->session->userdata('user_id'));
    }
    $this->db->order_by('id', 'DESC');
    $query = $this->db->get();
    $returnVal = $query->row_array();
    //echo $this->db->last_query();		
    if (!empty($returnVal)) {
      if ($returnVal['completed'] == 0) {
        $statusVal = 0;
      } else {
        $statusVal = 1;
      }
    } else {
      $statusVal = 0;
    }
    return $statusVal;
  }
  public function getBillingDetails($bill_id)
	{
		 $this->db->select('b.id as bid, b.bill_no as billno, b.bill_paid_on, CONCAT(d.first_name," ", d.last_name) as doctor_name,d.email as doctor_email,p.email as patient_email,CONCAT(d.country_code,"", d.mobileno) as doctor_mobile,CONCAT(p.country_code,"", p.mobileno) as patient_mobile, CONCAT(p.first_name," ", p.last_name) as patient_name,d.role,d.device_type as doctor_device_type,d.device_id as doctor_device_id,p.first_name as patient_first_name, d.first_name  as doctor_first_name, p.device_type as patient_device_type,p.device_id as patient_device_id, pt.invoice_no as bill_no');
        $this->db->from('billing b');
        $this->db->join('users d', 'd.id = b.doctor_id', 'left'); 
        $this->db->join('users_details dd','dd.user_id = d.id','left'); 
        $this->db->join('users p', 'p.id = b.patient_id', 'left'); 
        $this->db->join('users_details pd','pd.user_id = p.id','left'); 
        $this->db->join('payments pt','pt.billing_id = b.id','left'); 
        $this->db->where('b.id',$bill_id);
        $result = $this->db->get()->row_array();
        return $result;
	}
  public function delete($id) {
    return $this->db->delete('users', array('id' => $id)); 
}
}