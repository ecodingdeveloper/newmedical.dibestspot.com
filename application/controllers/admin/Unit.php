<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit extends CI_Controller {

   public $data;
   public $session;
   public $timezone;
   public $input;
   public $db;
   public $units;

   public function __construct() {

        parent::__construct();

        if($this->session->userdata('admin_id') ==''){
            redirect(base_url().'admin/login');
        }
        
        $this->data['theme']     = 'admin';
        $this->data['module']    = 'unit';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
         $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }
        $this->load->model('unit_model','units');
        

    }


	public function index()
	{
	    $this->data['page'] = 'index';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
	   
	}

    public function create_unit()
    {   
            $id=$this->input->post('id');
            $method=$this->input->post('method');
            $data['unit_name']=$this->input->post('unit_name');
            $data['status'] = 1; 

            if($method=='update')
            {
                  
              $this->db->where('unit_name',$data['unit_name']);
              $this->db->where('id !=',$id);
              $this->db->where('status',1);
              $query = $this->db->get('unit');

            
              if ($query->num_rows() > 0)
              {
                $datas['result']='exe';
                $datas['status']='Unit already exits!';
              }
              else
              {
                    $this->db->where('id',$id);
                    $this->db->update('unit',$data);
                    $result=($this->db->affected_rows()!= 1)? false:true;

                    if(@$result==true) 
                     {
                        $datas['result']='true';
                        $datas['status']='Unit update successfully';
                     }  
                     else
                     {
                        $datas['result']='false';
                        $datas['status']='Unit update failed!';
                     }
              }       

            }
            else
            {

              $this->db->where('unit_name',$data['unit_name']);
              $this->db->where('status',1);
              $query = $this->db->get('unit');

            
              if ($query->num_rows() > 0)
              {
                $datas['result']='exe';
                $datas['status']='Unit already exits!';
              }
              else
              {
                $this->db->insert('unit',$data);
                $result=($this->db->affected_rows()!= 1)? false:true;

                if(@$result==true) 
                 {
                    $datas['result']='true';
                    $datas['status']='Unit added successfully';
                 }  
                 else
                 {
                    $datas['result']='false';
                    $datas['status']='Unit added failed!';
                 }

               }  

            }               
            
            echo json_encode($datas);
    
    }
    
    
    public function unit_list()
  {
    $list = $this->units->get_datatables();
    $data = array();
    $no = $_POST['start'];
    $a=1;
     
    foreach ($list as $units) {

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $units['unit_name'];
               
      $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light" onclick="edit_unit('.$units['id'].')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_unit('.$units['id'].')">
                    <i class="fe fe-trash"></i> Delete
                  </a>
                </div>';
      
      $data[] = $row;
    }



    $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->units->count_all(),
            "recordsFiltered" => $this->units->count_filtered(),
            "data" => $data,
        );
    //output to json format
    echo json_encode($output);
  }


    public function unit_edit($id)
    {
        $data = $this->units->get_by_id($id);
        
        echo json_encode($data);
    }

   public function unit_delete($id)
    {
        $data = array(
                'status' =>0,
            );
        $this->units->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
    }


}
