<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property object $load 
 * @property object $db
 * @property object $session
 */

class Invoice_model extends CI_Model {

    var $table = 'payments p';
    var $doctor ='users d';
    var $doctor_details ='users_details dd';
    var $patient ='users pi';
    var $patient_details ='users_details pd';
    var $users ='users u';
    var $column_search = array('p.invoice_no','CONCAT(d.first_name," ",d.last_name)','p.total_amount','date_format(p.payment_date,"%d %b %Y")');

  

  var $order = array('p.id' => 'DESC'); // default order
  var $column_order=array('','p.invoice_no','d.first_name','p.total_amount','p.payment_date','');
	public function __construct()
	{
		parent::__construct();
		$this->load->database();

    if($this->session->userdata('role')=='4')  //Lab Login
    {
       $this->column_search = array('p.invoice_no','CONCAT(d.first_name," ",d.last_name)','p.total_amount','date_format(p.payment_date,"%d %b %Y")'); //set column field database for datatable searchable 
    }
    else
    {
       $this->column_search = array('p.invoice_no','CONCAT(d.first_name," ", d.last_name)','p.total_amount','date_format(p.payment_date,"%d %b %Y")');
    }

	}

	private function _get_datatables_query($user_id)
  {
  
    $this->db->select('p.*,CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage,d.id as doctor_id,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.id as patient_id,d.role');
    $this->db->from($this->table);
    $this->db->join($this->doctor, 'd.id = p.doctor_id', 'left'); 
    $this->db->join($this->doctor_details,'dd.user_id = d.id','left'); 
    $this->db->join($this->patient, 'pi.id = p.user_id', 'left'); 
    $this->db->join($this->patient_details,'pd.user_id = pi.id','left');
    if($this->session->userdata('role')=='1' || $this->session->userdata('role')=='4' || $this->session->userdata('role')=='5' || $this->session->userdata('role')=='6')
    {
      $this->db->where('p.doctor_id',$user_id);
    } 
    if($this->session->userdata('role')=='2')
    {
      $this->db->where('p.user_id',$user_id);
    }
    $this->db->group_start(); 
    $this->db->where('p.payment_status',1);
    $this->db->or_where('p.billing_id > 0');
    $this->db->or_where('p.billing_status',0);
    $this->db->group_end(); 

        $i = 0;
  
    foreach ($this->column_search as $item) // loop column 
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

         if(count($this->column_search) - 1 == $i) //last loop
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
    // if($this->session->userdata('role')=='1')
    // {
    //   $this->db->where('p.doctor_id',$user_id);
    // } 
    // if($this->session->userdata('role')=='2')
    // {
    //   $this->db->where('p.user_id',$user_id);
    // }

    if($this->session->userdata('role')=='1' || $this->session->userdata('role')=='4' || $this->session->userdata('role')=='5' || $this->session->userdata('role')=='6')
    {
      $this->db->where('p.doctor_id',$user_id);
    } 
    if($this->session->userdata('role')=='2')
    {
      $this->db->where('p.user_id',$user_id);
    }

    $this->db->from($this->table);
    return $this->db->count_all_results();
  }


	public function get_invoice_details($invoice_id)
  {
    $this->db->select('p.*, CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage,d.mobileno as doctormobile,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.mobileno as patientmobile,dc.country as doctorcountryname,ds.statename as doctorstatename,dci.city as doctorcityname,pc.country as patientcountryname,ps.statename as patientstatename,pci.city as patientcityname,dd.address1 as doctoraddress1,dd.address2 as doctoraddress2,pd.address1 as patientaddress1,pd.address2 as patientaddress2,d.role, pd.postal_code as patientpostalcode, dd.postal_code as doctorpostalcode');
        $this->db->from($this->table);
        $this->db->join($this->doctor, 'd.id = p.doctor_id', 'left'); 
        $this->db->join($this->doctor_details,'dd.user_id = d.id','left'); 
        $this->db->join($this->patient, 'pi.id = p.user_id', 'left'); 
        $this->db->join($this->patient_details,'pd.user_id = pi.id','left'); 
        $this->db->join('country dc','dd.country = dc.countryid','left');
        $this->db->join('state ds','dd.state = ds.id','left');
        $this->db->join('city dci','dd.city = dci.id','left');
        $this->db->join('country pc','pd.country = pc.countryid','left');
        $this->db->join('state ps','pd.state = ps.id','left');
        $this->db->join('city pci','pd.city = pci.id','left');
        $this->db->where('p.id',$invoice_id);;
        return $this->db->get()->row_array();
  }


  public function get_products_datatables($orderId)
  {
    $this->db->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,o.quantity as qty,o.payment_type,o.status,o.product_name,o.order_id,o.subtotal  subtotal ,o.order_status,o.user_notify,o.pharmacy_notify,o.id as id,ud.currency_code as product_currency,dc.country as doctorcountryname,ds.statename as doctorstatename,dci.city as doctorcityname,pc.country as patientcountryname,ps.statename as patientstatename,pci.city as patientcityname,ud.address1 as doctoraddress1,ud.address2 as doctoraddress2,o.pharmacy_id,o.currency_code as order_currency,ud.postal_code as patient_postal_code,us.mobileno as mob_no, payments.transaction_charge_percentage');
    $this->db->from('orders as o');
    $this->db->join('order_user_details as od','od.order_user_details_id = o.user_order_id','left');
    $this->db->join('users as us','us.id = o.pharmacy_id','left');
    $this->db->join('users_details as ud','ud.user_id = o.pharmacy_id','left');
    $this->db->join('country dc','od.country = dc.countryid','left');
    $this->db->join('state ds','od.state = ds.id','left');
    $this->db->join('city dci','od.city = dci.id','left');
    $this->db->join('country pc','ud.country = pc.countryid','left');
    $this->db->join('state ps','ud.state = ps.id','left');
    $this->db->join('city pci','ud.city = pci.id','left');
    $this->db->join('payments','payments.id = o.payment_id','left');
    $this->db->where('o.order_id',base64_decode($orderId));
    if($this->session->userdata('role')=='5'){
      $this->db->where('o.pharmacy_id',$this->session->userdata('user_id')); 
    }else{
      $this->db->where('o.user_id',$this->session->userdata('user_id'));
    }  
    $query = $this->db->get();
    return $query->result_array();
  }
	
  public function getUserDetails($id)
   {
       $this->db->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=u.id) as rating_value,u.role, c.sortname,  s.state_code');
        $this->db->from('users u');
        $this->db->join('users_details ud','ud.user_id = u.id','left');
        $this->db->join('country c','ud.country = c.countryid','left');
        $this->db->join('state s','ud.state = s.id','left');
        $this->db->join('city ci','ud.city = ci.id','left');
        $this->db->join('specialization sp','ud.specialization = sp.id','left');
        $this->db->where('u.status','1');
        $this->db->where('u.is_verified','1');
        $this->db->where('u.is_updated','1');
        $this->db->where('u.id',$id);
        return $result = $this->db->get()->row_array();
        
  }


}
