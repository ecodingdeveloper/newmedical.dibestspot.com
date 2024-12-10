<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subcategories extends CI_Controller {

   public $data;
   public $session;
   public $timezone;
   public $input;
   public $db;
   public $subcategories;

   public function __construct() {

        parent::__construct();

        if($this->session->userdata('admin_id') ==''){
            redirect(base_url().'admin/login');
        }
        
        $this->data['theme']     = 'admin';
        $this->data['module']    = 'subcategories';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
         $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }
        $this->load->model('subcategories_model','subcategories');
        

    }


    public function index()
    {
        $this->data['page'] = 'index';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
       
    }

    public function create_subcategories()
    {   
            $id=$this->input->post('id');
            $method=$this->input->post('method');
            $data['subcategory_name']=$this->input->post('subcategory_name');
            $data['category']=$this->input->post('category');
            $data["slug"] = str_slug($data["subcategory_name"]);
           
                
            if($method=='update')
            {
              $this->db->where('category',$data['category']);    
              $this->db->where('subcategory_name',$data['subcategory_name']);
              $this->db->where('id !=',$id);
              $this->db->where('status',1);
              $query = $this->db->get('product_subcategories');

            
              if ($query->num_rows() > 0)
              {
                $datas['result']='exe';
                $datas['status']='Subcategory name already exits!';
              }
              else
              {
                    $this->db->where('id',$id);
                    $this->db->update('product_subcategories',$data);
                    $result=($this->db->affected_rows()!= 1)? false:true;

                    if(@$result==true) 
                     {
                        $datas['result']='true';
                        $datas['status']='Subcategories update successfully';
                     }  
                     else
                     {
                        $datas['result']='false';
                        $datas['status']='Subcategories update failed!';
                     }
              }       

            }
            else
            {

              $this->db->where('category',$data['category']);    
              $this->db->where('subcategory_name',$data['subcategory_name']);
              $this->db->where('status',1);
              $query = $this->db->get('product_subcategories');

            
              if ($query->num_rows() > 0)
              {
                $datas['result']='exe';
                $datas['status']='Subcategory name already exits!';
              }
              else
              {
                $this->db->insert('product_subcategories',$data);
                $result=($this->db->affected_rows()!= 1)? false:true;

                if(@$result==true) 
                 {
                    $datas['result']='true';
                    $datas['status']='Subcategories added successfully';
                 }  
                 else
                 {
                    $datas['result']='false';
                    $datas['status']='Subcategories added failed!';
                 }

               }  

            }               
            
            echo json_encode($datas);
    
    }
    
    
    public function subcategories_list()
  {
    $list = $this->subcategories->get_datatables();
    $data = array();
    $no = $_POST['start'];
    $a=1;
     
    foreach ($list as $subcategoriess) {

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $subcategoriess['category_name'];
      $row[] = $subcategoriess['subcategory_name'];
      $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light" onclick="edit_subcategories('.$subcategoriess['id'].')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_subcategories('.$subcategoriess['id'].')">
                    <i class="fe fe-trash"></i> Delete
                  </a>
                </div>';
      
      $data[] = $row;
    }



    $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->subcategories->count_all(),
            "recordsFiltered" => $this->subcategories->count_filtered(),
            "data" => $data,
        );
    //output to json format
    echo json_encode($output);
  }


    public function subcategories_edit($id)
    {
        $data = $this->subcategories->get_by_id($id);
        
        echo json_encode($data);
    }

   public function subcategories_delete($id)
    {
        $data = array(
                'status' =>0,
            );
        $this->subcategories->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
    }


}
