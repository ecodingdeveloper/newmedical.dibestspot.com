<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller {

   public $data;
   public $session;
   public $timezone;
   public $input;
   public $upload;
   public $db;
   public $categories;

   public function __construct() {

        parent::__construct();

        if($this->session->userdata('admin_id') ==''){
            redirect(base_url().'admin/login');
        }
        
        $this->data['theme']     = 'admin';
        $this->data['module']    = 'categories';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
         $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }
        $this->load->model('categories_model','categories');
        

    }


    public function index()
    {
        $this->data['page'] = 'index';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
       
    }

    public function create_categories()
    {   
            $id=$this->input->post('id');
            $method=$this->input->post('method');
            $data['category_name']=$this->input->post('category_name');
            $data["slug"] = str_slug($data["category_name"]);
            $data["category_image"] =$this->input->post('category_img');
            if($_FILES["category_image"]["name"] != '')
             {
                 $config["upload_path"] = './uploads/categories/';
                 $config["allowed_types"] = '*';
                 $this->load->library('upload', $config);
                 $this->upload->initialize($config);


                        $_FILES["file"]["name"] = 'img_'.time().'.png';
                        $_FILES["file"]["type"] = $_FILES["category_image"]["type"];
                        $_FILES["file"]["tmp_name"] = $_FILES["category_image"]["tmp_name"];
                        $_FILES["file"]["error"] = $_FILES["category_image"]["error"];
                        $_FILES["file"]["size"] = $_FILES["category_image"]["size"];
                        if($this->upload->do_upload('file'))
                        {
                           $upload_data = $this->upload->data();
                          
                            $category_image='uploads/categories/'.$upload_data["file_name"];
                            
                            $data['category_image']=$category_image;
                                                                         
                        }
                }
            
                
            if($method=='update')
            {
                  
              $this->db->where('category_name',$data['category_name']);
              $this->db->where('id !=',$id);
              $this->db->where('status',1);
              $query = $this->db->get('product_categories');

            
              if ($query->num_rows() > 0)
              {
                $datas['result']='exe';
                $datas['status']='Category name already exits!';
              }
              else
              {
                    $this->db->where('id',$id);
                    $this->db->update('product_categories',$data);
                    $result=($this->db->affected_rows()!= 1)? false:true;

                    if(@$result==true) 
                     {
                        $datas['result']='true';
                        $datas['status']='Category update successfully';
                     }  
                     else
                     {
                        $datas['result']='true';
                        $datas['status']='Category update successfully';
                     }
              }       

            }
            else
            {

              $this->db->where('category_name',$data['category_name']);
              $this->db->where('status',1);
              $query = $this->db->get('product_categories');

            
              if ($query->num_rows() > 0)
              {
                $datas['result']='exe';
                $datas['status']='Category name already exits!';
              }
              else
              {
                $this->db->insert('product_categories',$data);
                $result=($this->db->affected_rows()!= 1)? false:true;

                if(@$result==true) 
                 {
                    $datas['result']='true';
                    $datas['status']='Categories added successfully';
                 }  
                 else
                 {
                    $datas['result']='false';
                    $datas['status']='Categories added failed!';
                 }

               }  

            }               
            
            echo json_encode($datas);
    
    }
    
    
  public function categories_list()
  {
    $list = $this->categories->get_datatables();
    $data = array();
    $no = $_POST['start'];
    $a=1;
     
    foreach ($list as $categoriess) {

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = '<h2 class="table-avatar">
                    <a href="javascript:void(0);" class="avatar avatar-sm mr-2">
                      <img class="avatar-img" src="'.base_url().$categoriess['category_image'].'" alt="Speciality">
                    </a>
                    <a href="javascript:void(0);">'.$categoriess['category_name'].'</a>
                 </h2>';
      $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light" onclick="edit_categories('.$categoriess['id'].')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_categories('.$categoriess['id'].')">
                    <i class="fe fe-trash"></i> Delete
                  </a>
                </div>';
      
      $data[] = $row;
    }



    $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->categories->count_all(),
            "recordsFiltered" => $this->categories->count_filtered(),
            "data" => $data,
        );
    //output to json format
    echo json_encode($output);
  }


    public function categories_edit($id)
    {
        $data = $this->categories->get_by_id($id);
        
        echo json_encode($data);
    }

   public function categories_delete($id)
    {
        $data = array(
                'status' =>0,
            );
        $this->categories->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
    }


}
