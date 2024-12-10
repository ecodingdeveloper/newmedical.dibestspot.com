<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 * @property object $session
 */
class Doctor_model extends CI_Model
{
	var $column_search = array('u.first_name','u.last_name','u.email','u.mobileno'); //set column field database for datatable searchable 

	public function __construct()
	{
		parent::__construct();
	}

	public function get_datatables($user_id,$type="")
	{
		$this->db->select('u.id as id,CONCAT(u.first_name," ",u.last_name) as name,u.first_name,u.last_name ,u.email,u.country_code, u.mobileno as mobile,u.profileimage as profile,u.username as  username,u.is_verified,u.is_updated');
		$this->db->from('users u');
		$this->db->join('users_details ud', 'u.id = ud.user_id', 'left');
		
		if($type==2 ){
			$this->db->where('u.id',$user_id);
			 /* $query = $this->db->get();
			$result = $query->result_array();
			return $result; */
		}
		
		$this->db->where('u.status',1);
		$this->db->where('u.role',1);
		$this->db->where('u.hospital_id',$user_id);

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
			$this->db->order_by('id', $_POST['order']['0']['dir']);

			//$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
		else 
		{         
			$this->db->order_by('u.id', 'DESC');
		}
		$query = $this->db->get();

		if($type==1){
			$result = $query->num_rows();
		} else {
			//echo $this->db->last_query();
			$result = $query->result_array();
		}
		return $result;

	}

	public function get_datatables_verfied_doctors($user_id,$type="")
	{
		$this->db->select('u.id as id,CONCAT(u.first_name,u.last_name) as name,u.first_name,u.last_name ,u.email,u.country_code, u.mobileno as mobile,u.profileimage as profile,u.username as  username');
		$this->db->from('users u');


		$this->db->join('users_details ud', 'u.id = ud.user_id', 'left');
		if($type==2 ){
			$this->db->where('u.id',$user_id);
			$query = $this->db->get();
			$result = $query->result_array();
			return $result;
		}
		$this->db->where('u.status',1);
		$this->db->where('u.hospital_id',$user_id);
		$this->db->where('u.is_verified',1);
		$this->db->where('u.is_updated',1);

		$query = $this->db->get();
		$result = $query->result_array();
		if($type==1){
			$result = $query->num_rows();
		}
		return $result;

	}
	public function assign_doc($id,$app_id){
		$inputdata =[];
		$user_id = $this->session->userdata('user_id');
		$inputdata['appointment_to'] = $id;
		$inputdata['hospital_id'] = $user_id;
		$this->db->where('id',$app_id);
		$this->db->update('appointments',$inputdata);
	} 

	public function check_email($email,$id)
	{
	$this->db->select('id,email');
	$this->db->from('users');
	$this->db->where('email', $email);
	if(!empty($id)) {
	$this->db->where('id !=', $id);
	}
	$result = $this->db->get()->row_array();
	return $result;

	}

	public function check_mobileno($mobileno,$id)
	{
		$this->db->select('id,mobileno');
		$this->db->from('users');
		$this->db->where('mobileno', $mobileno);
		if(!empty($id)) {
			$this->db->where('id !=', $id);
		}
		$result = $this->db->get()->row_array();

		return $result;

	}
	public function delete($id) {
    return $this->db->delete('users', array('id' => $id)); 
}
  
}

