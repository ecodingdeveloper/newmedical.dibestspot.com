<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property object $load 
 * @property object $db
 */

class Users_model extends CI_Model {

var $doctor ='users d';
  var $doctor_details ='users_details dd';
  var $specialization ='specialization s';

  var $patient ='users p';
  var $patient_details ='users_details pd';

  var $clinic = 'users c';
  var $clinic_details ='users_details cd'; 
  var $clinic_doctors = 'clinic_details cdo'; 
  //var $clinic_doctors_order=array('cdo.id' => 'ASC');

  var $pharmacy = 'users ph';
  var $pharmacy_details ='users_details phd'; 
  
  var $pharmacy_specifications = 'pharmacy_specifications phs';

  var $lab ='users l';
  var $lab_details ='users_details ld';

  var $team='users t';
  var $team_details='users_details td';

  var $staff='users st';
  var $staff_details='users_details std';

  var $hospital ='users h';
  var $hospital_details ='users_details hd';
  
  var $lab_payments ='lab_payments lp';
  var $lab_tests ='lab_tests lt'; 

  var $doctor_column_search = array('CONCAT(d.first_name," ",d.last_name)','d.profileimage','d.email','d.mobileno','date_format(d.created_date,"%d %b %Y")','s.specialization'); 
 var $doctor_default_column_order = array('d.id' => 'DESC'); // default order 
var $doctor_order=array('CONCAT(d.first_name," ",d.last_name)','d.profileimage','d.email','d.mobileno','date_format(d.created_date,"%d %b %Y")','s.specialization');

  var $patient_column_search = array('CONCAT(p.first_name," ",p.last_name)','p.profileimage','p.email','p.mobileno','pd.dob','pd.blood_group','date_format(p.created_date,"%d %b %Y")'); 
 var $patient_default_order = array('p.id' => 'DESC'); // default order
   var $patient_column_order = array('','p.id','p.first_name','pd.dob','pd.blood_group','p.email','p.mobileno','p.created_date','p.status','last_vist','last_paid');

   var $team_column_search = array('CONCAT(t.first_name," ",t.last_name)','t.profileimage','t.email','t.mobileno','td.dob','td.blood_group','date_format(t.created_date,"%d %b %Y")'); 
   var $team_default_order = array('t.id' => 'DESC'); // default order
   var $team_column_order = array('','t.id','t.first_name','td.dob','td.blood_group','t.email','t.mobileno','t.created_date','t.status','last_vist','last_paid');

   var $staff_column_search = array('CONCAT(st.first_name," ",st.last_name)','st.profileimage','st.email','st.mobileno','std.dob','std.blood_group','date_format(t.created_date,"%d %b %Y")'); 
   var $staff_default_order = array('st.id' => 'DESC'); // default order
   var $staff_column_order = array('','st.id','st.first_name','std.dob','std.blood_group','st.email','st.mobileno','st.created_date','st.status','last_vist','last_paid');

  var $hospital_column_search = array('h.first_name','h.last_name','h.profileimage','h.email','h.mobileno','h.created_date'); 
  var $hospital_order = array('h.id' => 'ASC'); // default order

  var $lab_column_search = array('l.first_name','l.last_name','l.profileimage','l.email','l.mobileno','l.created_date'); 
  var $lab_order = array('l.id' => 'DESC'); // default order
  var $lab_column_order = array('','l.id','l.first_name','l.email','l.mobileno','l.created_date','');

  var $pharmacy_column_search = array('ph.pharmacy_name', 'CONCAT(ph.first_name," ",ph.last_name)','ph.profileimage','ph.email','ph.mobileno', 'phs.home_delivery', 'phs.24hrsopen', 'phs.pharamcy_opens_at','date_format(ph.created_date,"%d %b %Y")'); 
  var $pharmacy_default_order = array('ph.id' => 'DESC'); // default order
	var $pharmacy_order = array('','ph.pharmacy_name','ph.email','ph.mobileno', 'phs.home_delivery', 'phs.24hrsopen', 'phs.pharamcy_opens_at','ph.created_date','ph.status'); 

  var $labtest_column_search = array('p.first_name', 'p.last_name', 'l.first_name','l.last_name','lp.lab_test_date', 'lp.order_id', 'lp.total_amount', 'lp.payment_type'); 
  var $labtest_order = array('lp.id' => 'DESC'); // default order
  var $labtest_column_order = array('','lp.order_id','p.first_name','l.first_name','lt.lab_test_name','lp.lab_test_date','','lp.payment_type');

  var $clinic_column_search = array('cd.clinic_name','c.profileimage','c.email','c.mobileno','c.created_date','s.specialization');
  var $clinic_order = array('c.profileimage' => 'ASC','c.id' => 'ASC','cd.clinic_name' => 'ASC','s.specialization' => 'ASC','c.email' => 'ASC','c.mobileno' => 'ASC','c.created_date' => 'ASC','cd.amount' => 'ASC','c.status' => 'ASC'); // default order 

  var $clinic_doctor_search = array('cdo.clinic_name','s.specialization'); 
  var $clinic_doctors_column_search = array('cd.clinic_name','CONCAT(c.first_name," ",c.last_name)','c.email','c.mobileno','date_format(c.created_date,"%d %b %Y")','s.specialization');
 // var $clinic_doctor_order = array('cdo.clinic_details_id' => 'ASC', 's.specialization'=>'ASC','cdo.status'=>'ASC'); 
 var $clinic_doctors_order = array('cd.clinic_name','CONCAT(c.first_name," ",c.last_name)','c.email','c.mobileno','date_format(d.created_date,"%d %b %Y")','s.specialization');
  
  var $table='users';



  // var $pharmacy_column_search = array('ph.pharmacy_name', 'ph.first_name','ph.last_name','ph.profileimage','ph.email','ph.mobileno', 'phs.home_delivery', 'phs.24hrsopen', 'phs.pharamcy_opens_at','ph.created_date'); 
  // var $pharmacy_order = array('ph.id' => 'ASC'); // default order


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	

  private function _get_doctor__datatables_query()
  {
  
        $this->db->select('d.*,s.specialization');
        $this->db->from($this->doctor); 
        $this->db->join($this->doctor_details,'dd.user_id = d.id','left'); 
        $this->db->join($this->specialization,'dd.specialization = s.id','left');
        $this->db->where('d.role','1'); 
        //$qu=  $this->db->get_compiled_select();
		//$query = $this->db->get();
		//if ($query) {
    //$result = $query->result_array(); // Fetch result as array

    // Print or process the result array
    //print_r($result);
//}
		//else{
		//	echo "hi";
		//}
      //print_r($result);
       //echo $qu.'<br>';
         //die('test');
   
    $i = 0;
  
    foreach ($this->doctor_column_search as $item) // loop column 
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

         if(count($this->doctor_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
            $this->db->order_by('id', $_POST['order']['0']['dir']);

       //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->doctor_default_column_order))
    {
      $order = $this->doctor_default_column_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_doctor_datatables()
  {
    $this->_get_doctor__datatables_query();
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function doctor_count_filtered()
  {
    $this->_get_doctor__datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function doctor_count_all()
  {
    $this->db->from($this->doctor);
    $this->db->where('d.role','1');
    return $this->db->count_all_results();
  }



private function _get_patient__datatables_query()
  {
  
        $this->db->select('p.*,pd.dob,pd.blood_group,pd.currency_code,(select appointment_date from appointments where appointment_from=p.id order by id desc limit 1) as last_vist,(select TRUNCATE(total_amount,2) from payments where user_id=p.id order by id desc limit 1) as last_paid');
        $this->db->from($this->patient); 
        $this->db->join($this->patient_details,'pd.user_id = p.id','left'); 
        $this->db->where('p.role','2'); 
      //   $query = $this->db->get();
      // echo '<pre>';
      // print_r($query->result());
      // echo '</pre>';
      // die("here");
   
    $i = 0;
  
    foreach ($this->patient_column_search as $item) // loop column 
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

         if(count($this->patient_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
            // $this->db->order_by('id', $_POST['order']['0']['dir']);

       $this->db->order_by($this->patient_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->patient_default_order))
    {
      $order = $this->patient_default_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_patient_datatables()
  {
    $result=$this->_get_patient__datatables_query();
    // echo '<pre>';
    //   print_r($result);
    //   echo '</pre>';
    //   die("hersse");
    if($_POST['length'] != -1){
      // die("hersse");
    $this->db->limit($_POST['length'], $_POST['start']);
    // $query = $this->db->get('users u');

  }
    $query = $this->db->get();
    // echo '<pre>';
    // print_r($query->result());
    // echo '</pre>';
    // die("please");
    return $query->result_array();
  }

  public function patient_count_filtered()
  {
    $this->_get_patient__datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function patient_count_all()
  {
    $this->db->from($this->patient);
    $this->db->where('p.role','2');
    return $this->db->count_all_results();
  }

  
private function _get_team__datatables_query()
{
// die('poiuyt');
$this->db->select('t.*,td.dob,td.blood_group,td.currency_code,(select appointment_date from appointments where appointment_from=t.id order by id desc limit 1) as last_vist,(select TRUNCATE(total_amount,2) from payments where user_id=t.id order by id desc limit 1) as last_paid');
$this->db->from($this->team); 
$this->db->join($this->team_details,'td.user_id = t.id','left'); 
$this->db->where('t.role','7'); 
      // echo '<pre>';
      // print_r($query->result());
      // echo '</pre>';
      // die("here");

  $i = 0;

  foreach ($this->team_column_search as $item) // loop column 
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

       if(count($this->team_column_search) - 1 == $i) //last loop
        $this->db->group_end(); //close bracket
    }
    $i++;
  }
  
  if(isset($_POST['order'])) // here order processing
  {
          // $this->db->order_by('id', $_POST['order']['0']['dir']);

     $this->db->order_by($this->team_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
  } 
  else if(isset($this->team_default_order))
  {
    $order = $this->team_default_order;
    $this->db->order_by(key($order), $order[key($order)]);
  }
}

public function get_team_datatables()
{
  $result = $this->_get_team__datatables_query();
      // echo '<pre>';
      // print_r($result);
      // echo '</pre>';
      // die("hersse");

  if($_POST['length'] != -1){
    // die("hersse");

  $this->db->limit($_POST['length'], $_POST['start']);
  // $query = $this->db->get('users t');
}
  $query = $this->db->get();

    return $query->result_array();

}

public function team_count_filtered()
{
  $this->_get_team__datatables_query();
  $query = $this->db->get();
  return $query->num_rows();
}

public function team_count_all()
{
  $this->db->from($this->team);
  $this->db->where('t.role','7');
  return $this->db->count_all_results();
}

private function _get_staff__datatables_query()
{
// die('poiuyt');
$this->db->select('st.*,std.dob,std.blood_group,std.currency_code,(select appointment_date from appointments where appointment_from=st.id order by id desc limit 1) as last_vist,(select TRUNCATE(total_amount,2) from payments where user_id=st.id order by id desc limit 1) as last_paid');
$this->db->from($this->staff); 
$this->db->join($this->staff_details,'std.user_id = st.id','left'); 
$this->db->where('st.role','8'); 
      // echo '<pre>';
      // print_r($query->result());
      // echo '</pre>';
      // die("here");

  $i = 0;

  foreach ($this->staff_column_search as $item) // loop column 
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

       if(count($this->staff_column_search) - 1 == $i) //last loop
        $this->db->group_end(); //close bracket
    }
    $i++;
  }
  
  if(isset($_POST['order'])) // here order processing
  {
          // $this->db->order_by('id', $_POST['order']['0']['dir']);

     $this->db->order_by($this->staff_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
  } 
  else if(isset($this->staff_default_order))
  {
    $order = $this->staff_default_order;
    $this->db->order_by(key($order), $order[key($order)]);
  }
}

public function get_staff_datatables()
{
  $result = $this->_get_staff__datatables_query();
      // echo '<pre>';
      // print_r($result);
      // echo '</pre>';
      // die("hersse");

  if($_POST['length'] != -1){
    // die("hersse");

  $this->db->limit($_POST['length'], $_POST['start']);
  // $query = $this->db->get('users t');
}
  $query = $this->db->get();

    return $query->result_array();

}

public function staff_count_filtered()
{
  $this->_get_staff__datatables_query();
  $query = $this->db->get();
  return $query->num_rows();
}

public function staff_count_all()
{
  $this->db->from($this->staff);
  $this->db->where('st.role','8');
  return $this->db->count_all_results();
}

  public function update($where, $data)
  {
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }

	private function _get_pharmacy_datatables_query()
  {
  
        $this->db->select('ph.*,phs.home_delivery, phs.24hrsopen as hrs_open, phs.pharamcy_opens_at');
        $this->db->from($this->pharmacy); 
        $this->db->join($this->pharmacy_details,'phd.user_id = ph.id','left'); 
        $this->db->join($this->pharmacy_specifications,'phs.pharmacy_id = ph.id','left'); 
        $this->db->where('ph.role','5'); 
        
   
    $i = 0;
  
    foreach ($this->pharmacy_column_search as $item) // loop column 
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

         if(count($this->pharmacy_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
            // $this->db->order_by('id', $_POST['order']['0']['dir']);

      if ($_POST['order']['0']['column'] == 1) { // custom sorting for pharmacy column name
        $this->db->order_by('ph.first_name, ph.pharmacy_name', $_POST['order']['0']['dir']);
      } else {
        $this->db->order_by($this->pharmacy_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
      }
    } 
    else if(isset($this->pharmacy_default_order))
    {
      $order = $this->pharmacy_default_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }
  
  public function get_pharmacy_datatables()
  {
    $this->_get_pharmacy_datatables_query();
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function pharmacy_count_filtered()
  {
    $this->_get_pharmacy_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function pharmacy_count_all()
  {
    $this->db->from($this->patient);
    $this->db->where('p.role','5');
    return $this->db->count_all_results();
  }

  public function get_selected_pharmacy_details($pharmacy_id = NULL){
      $this->db->select('p.id as pharmacy_id, p.first_name,p.last_name,p.pharmacy_name, p.mobileno, p.email');
      $this->db->select('c.country, c.phonecode,s.statename, ci.city');
      $this->db->select('ps.home_delivery, ps.24hrsopen as hrsopen, ps.pharamcy_opens_at');
      $this->db->from('users p');
      $this->db->join('users_details pd', 'p.id = pd.user_id', 'left'); 
      $this->db->join('pharmacy_specifications ps', 'p.id = ps.pharmacy_id', 'left'); 
      $this->db->join('state s', 's.id = pd.state', 'left'); 
      $this->db->join('city ci', 'ci.id = pd.city', 'left'); 
      $this->db->join('country c', 'c.countryid = pd.country', 'left'); 
      $this->db->where_in('p.id',$pharmacy_id);
      $this->db->where('p.role',5);
      $this->db->where('p.status',1);
      return $this->db->get()->result_array();
  }
  
  private function _get_lab__datatables_query()
  {
  
    
        $this->db->select('l.*');
        $this->db->from($this->lab); 
        $this->db->join($this->lab_details,'ld.user_id = l.id','left'); 
        $this->db->where('l.role','4'); 
        
   
    $i = 0;
  
    foreach ($this->lab_column_search as $item) // loop column 
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

         if(count($this->lab_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
            // $this->db->order_by('id', $_POST['order']['0']['dir']);

       $this->db->order_by($this->lab_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->lab_order))
    {
      $order = $this->lab_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

    public function get_lab_datatables() {
        $this->_get_lab__datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function lab_count_filtered()
    {
        $this->_get_lab__datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function lab_count_all()
    {
        $this->db->from($this->lab);
        $this->db->where('l.role','4');
        return $this->db->count_all_results();
    }
  

	/*Booked Labtest*/
	private function _get_labtest__datatables_query()
	{

		$this->db->select('lp.id as lp_id, lp.lab_id as lp_labid, lp.patient_id, lp.booking_ids, lp.order_id, lp.lab_test_date, lp.total_amount, lp.currency_code, lp.payment_type, lp.status as payment_status, p.first_name as patient_firstname, p.last_name as patient_lastname, l.first_name, l.last_name');
		$this->db->from($this->lab_payments); 
		$this->db->join($this->lab_tests,'lt.id = lp.booking_ids','left'); 
		$this->db->join($this->patient,'p.id = lp.patient_id','left'); 
		$this->db->join($this->lab,'l.id = lp.lab_id','left'); 
		
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
	
	public function get_booked_labtest_datatables() {
        $this->_get_labtest__datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
		//echo $this->db->last_query();
        return $query->result_array();
    }
	public function get_lab_testname($ids){
		$exp = explode(",",$ids);
		$this->db->select('GROUP_CONCAT(lab_test_name) as testname');
		$this->db->from($this->lab_tests);
        $this->db->where_in('id',$exp);
        $res = $this->db->get()->row()-> testname;
		return $res;
	}
  public function booked_labtest_count_filtered()
    {
        $this->_get_labtest__datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function booked_labtest_count_all()
    {
        $this->db->from($this->lab_payments);
        return $this->db->count_all_results();
    }
	/*Booked Labtest*/

private function _get_clinic_datatables_query()
  {
  
        $this->db->select('c.*,s.specialization, cd.clinicname, cd.clinic_name');
        $this->db->from($this->clinic); 
        $this->db->join($this->clinic_details,'cd.user_id = c.id','left'); 
        $this->db->join($this->specialization,'cd.specialization = s.id','left');
        $this->db->where('c.role','6');
        $this->db->where('c.status !=',0);         
   
    $i = 0;
  
    foreach ($this->clinic_column_search as $item) // loop column 
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

         if(count($this->clinic_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
      $clinic_order = $this->clinic_order;
      $column = $_POST['order']['0']['column'];
      $dir = $_POST['order']['0']['dir'];
      $this->db->order_by(array_keys($clinic_order)[$column], $dir);
    } 
    else if(isset($this->clinic_order))
    {
      $this->db->order_by('id', 'ASC');
    }
  }
  
  public function get_clinic_datatables()
  {
    $this->_get_clinic_datatables_query();
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    // print_r($this->db->last_query());exit;
    return $query->result_array();
  }
  

  public function clinic_count_filtered()
  {
    $this->_get_clinic_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function clinic_count_all()
  {
    $this->db->from($this->clinic);
    $this->db->where('c.role','6');
    $this->db->where('c.status !=',0);
    return $this->db->count_all_results();
  }

 /*vijay
 var $clinic = 'users c';
 var $clinic_details ='users_details cd'; 
 var $clinic_doctors = 'clinic_details cdo'; 
 /**/
  /*clinic doctors*/
  private function _get_clinic_doctor_datatables_query($id)
  {
  
    $this->db->select('c.*,s.specialization');
    $this->db->from($this->clinic); 
    $this->db->join($this->clinic_details,'cd.user_id = c.id','left'); 
    $this->db->join($this->specialization,'cd.specialization = s.id','left');
    $this->db->where('c.status !=',0); 
    $this->db->where('c.hospital_id',$id); 
    

    $i = 0;


    foreach ($this->clinic_doctors_column_search as $item) // loop column 
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

         if(count($this->clinic_doctors_column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
      $clinic_order = $this->clinic_doctors_order;
      $column = $_POST['order']['0']['column'];
      $dir = $_POST['order']['0']['dir'];
      $this->db->order_by(array_keys($clinic_order)[$column], $dir);
    } 
    else if(isset($this->clinic_doctors_order))
    {
      $this->db->order_by('c.id', 'ASC');
    }
  
   
  }
  
  public function get_clinic_doctor_datatables($id)
  {
    $this->_get_clinic_doctor_datatables_query($id);
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function clinic_doctor_count_filtered($id)
  {
    $this->_get_clinic_doctor_datatables_query($id);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function clinic_doctor_count_all($id)
  {
    $this->db->from($this->clinic_doctors);
    $this->db->where('doctor_id',$id);
    return $this->db->count_all_results();
  }
  public function update_clinic_doctors($where, $data)
  {
    $this->db->update($this->clinic_doctors, $data, $where);
    return $this->db->affected_rows();
  }

  public function get_by_id($id)
  {
    $this->db->from($this->table);
    $this->db->where('id',$id);
    $query = $this->db->get();

    return $query->row();
  }


  public function delete($id) {
    return $this->db->delete('users', array('id' => $id)); // Replace with your actual table name
}
}