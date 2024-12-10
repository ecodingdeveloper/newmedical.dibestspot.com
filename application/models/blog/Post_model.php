<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $session 
 * @property object $db
 */
class Post_model extends CI_Model
{

  var $post ='posts p';	
  var $doctor ='users d';
  var $category ='categories c';
  var $subcategory ='subcategories s';

   var $column_search = array('p.title','c.category_name','s.subcategory_name','d.first_name','d.last_name'); 
 var $default_column_order = array('p.id' => 'DESC'); // default order 
 var $column_order = array('p.title','c.category_name','s.subcategory_name','d.first_name','d.last_name');  // default order 
	public function __construct()
	{
		parent::__construct();
	}


  public function create_post($input_data)
  {
     $this->db->insert('posts',$input_data);
     return ($this->db->affected_rows()!= 1)? false:true;
  }

  public function update_post($post_id,$input_data)
  {
     $this->db->where('id',$post_id);
     $this->db->update('posts',$input_data);
     return ($this->db->affected_rows()!= 1)? false:true;
  }

  private function _get_datatables_query()
  {
  
        $this->db->select('p.*,CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username,c.category_name,s.subcategory_name');
        $this->db->from($this->post); 
        $this->db->join($this->doctor,'p.user_id = d.id','left'); 
        $this->db->join($this->category,'p.category = c.id','left');
        $this->db->join($this->subcategory,'p.subcategory = s.id','left');
        $this->db->where('p.status','1'); 
        if($_POST['posts_type'] == 1){
        $this->db->where('p.is_verified','1');
        }
        if($_POST['posts_type'] == 2){
        $this->db->where('p.is_verified','0');
        }
        if(!empty($this->session->userdata('user_id')))
        {
          $this->db->where('p.user_id',$this->session->userdata('user_id'));
        }
        
   
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
    else if(isset($this->default_column_order))
    {
      $order = $this->default_column_order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_datatables()
  {
    $this->_get_datatables_query();
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result_array();
  }

  public function count_filtered()
  {
    $this->_get_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all()
  {
    $this->db->from($this->post);
    $this->db->where('p.status','1');
    if($_POST['posts_type'] == 1){
    $this->db->where('p.is_verified','1');
    }
    if($_POST['posts_type'] == 2){
    $this->db->where('p.is_verified','0');
    }
  if(!empty($this->session->userdata('user_id')))
      {
        $this->db->where('p.user_id',$this->session->userdata('user_id'));
      }
    return $this->db->count_all_results();
  }

  public function update($where, $data)
  {
    $this->db->update($this->post, $data, $where);
    return $this->db->affected_rows();
  }


  public function get_posts($post_id)
  {
        $this->db->select('p.*');
        $this->db->from($this->post);
        $this->db->where('p.id',$post_id);
        return $this->db->get()->row_array(); 
  }

  public function get_tags($post_id)
  {
        $this->db->select('*');
        $this->db->from('tags');
        $this->db->where('post_id',$post_id);
        return $this->db->get()->result_array(); 
  }



}

