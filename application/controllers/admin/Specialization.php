<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Specialization extends CI_Controller {

   public $data;
   public $session;
   public $timezone;
   public $input;
   public $db;
   public $upload;
   public $specializations;
   

   public function __construct() {

        parent::__construct();

        if($this->session->userdata('admin_id') ==''){
            redirect(base_url().'admin/login');
        }
        
        $this->data['theme']     = 'admin';
        $this->data['module']    = 'specialization';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
         $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }
        $this->load->model('specialization_model','specializations');
        $this->load->model('users_model','users');
        $this->load->model('packages_model','packages');
        $this->load->model('services_model','services');
        $this->load->model('my_patients_model','patient');
        $this->load->model('doctor_model','doctor');
        $this->load->model('Pricing_plan_model','pricing');
       


    }


	public function index()
	{
	    $this->data['page'] = 'index';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
	   
	}

    public function create_specialization()
    {   
            $id=$this->input->post('id');
            $method=$this->input->post('method');
            // echo $method;
            // die("specialization");
            $data['specialization']=$this->input->post('specialization');
          
    
             if($_FILES["specialization_image"]["name"] != '')
             {

                $data['specialization_img']=$this->input->post('specialization_imgs');
                 //$config["upload_path"] = './uploads/specialization/';
                 /*$config["upload_path"] = 'uploads/specialization';
                 $config["allowed_types"] = '*';*/
                 $configfile['upload_path']   = FCPATH . 'uploads/specialization';
                 $configfile['allowed_types'] = 'gif|jpg|png|jpeg';
                 $this->load->library('upload', $configfile);
                 $this->upload->initialize($configfile);


                        $_FILES["file"]["name"] = 'img_'.time().'.png';
                        $_FILES["file"]["type"] = $_FILES["specialization_image"]["type"];
                        $_FILES["file"]["tmp_name"] = $_FILES["specialization_image"]["tmp_name"];
                        $_FILES["file"]["error"] = $_FILES["specialization_image"]["error"];
                        $_FILES["file"]["size"] = $_FILES["specialization_image"]["size"];
                        if($this->upload->do_upload('file'))
                        {
                           $upload_data = $this->upload->data();
                          
                            $specialization_img='uploads/specialization/'.$upload_data["file_name"];
                            
                            $data['specialization_img']=$specialization_img;
                                                                         
                        } else {
                          $response['status']=500;
                          $response['msg'] = $this->upload->display_errors();

                          $datas['result']='false';
                          $datas['msg']=strip_tags($this->upload->display_errors());
                          
                          echo json_encode($datas); exit;
                        }
                }
              
               $data['status'] = 1; 

            if($method=='update')
            {
                  
              $this->db->where('specialization',$data['specialization']);
              $this->db->where('id !=',$id);
              $this->db->where('status',1);
              $query = $this->db->get('specialization');

            
              if ($query->num_rows() > 0)
              {
                $datas['result']='exe';
                $datas['status']='Specialization already exits!';
              }
              else
              {
                    $this->db->where('id',$id);
                    $this->db->update('specialization',$data);
                    $result=($this->db->affected_rows()!= 1)? false:true;

                    if(@$result==true) 
                     {
                        $datas['result']='true';
                        $datas['status']='Specialization update successfully';
                     }  
                     else
                     {
                        $datas['result']='true';
                        $datas['status']='Specialization update successfully';
                     }
              }       

            }
            else
            {

              $this->db->where('specialization',$data['specialization']);
              $this->db->where('status',1);
              $query = $this->db->get('specialization');

            
              if ($query->num_rows() > 0)
              {
                $datas['result']='exe';
                $datas['status']='Specialization already exits!';
              }
              else
              {
                $this->db->insert('specialization',$data);
                $result=($this->db->affected_rows()!= 1)? false:true;

                if(@$result==true) 
                 {
                    $datas['result']='true';
                    $datas['status']='Specialization added successfully';
                 }  
                 else
                 {
                    $datas['result']='false';
                    $datas['status']='Specialization added failed!';
                 }

               }  

            }               
            
            echo json_encode($datas);
    
    }
    
    
    public function specialization_list()
  {
    $list = $this->specializations->get_datatables();
    $data = array();
    $no = $_POST['start'];
    $a=1;
     
    foreach ($list as $specializations) {

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = '<h2 class="table-avatar">
                    <a href="javascript:void(0);" class="avatar avatar-sm mr-2">
                      <img class="avatar-img" src="'.base_url().$specializations['specialization_img'].'" alt="Speciality">
                    </a>
                    <a href="javascript:void(0);">'.$specializations['specialization'].'</a>
                 </h2>';
               
      $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light" onclick="edit_specialization('.$specializations['id'].')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_specialization('.$specializations['id'].')">
                    <i class="fe fe-trash"></i> Delete
                  </a>
                </div>';
      
      $data[] = $row;
    }



    $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->specializations->count_all(),
            "recordsFiltered" => $this->specializations->count_filtered(),
            "data" => $data,
        );
    //output to json format
    echo json_encode($output);
  }


    public function specialization_edit($id)
    {
        $data = $this->specializations->get_by_id($id);
        
        echo json_encode($data);
    }
    public function team_member_edit($id)
    {
        $data = $this->specializations->get_by_id($id);
        
        echo json_encode($data);
    }

   public function specialization_delete($id)
    {
        $data = array(
                'status' =>0,
            );
        $this->specializations->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
    }
    public function team_member_delete($id) {
      // Check if the ID is valid
      if (empty($id)) {
          echo json_encode(array("status" => FALSE, "message" => "Invalid ID."));
          return;
      }

      // Attempt to delete the record based on the provided ID
      $deleted = $this->users->delete($id); // Pass the ID directly

      // Return a JSON response
      if ($deleted) {
          echo json_encode(array("status" => TRUE));
      } else {
          echo json_encode(array("status" => FALSE, "message" => "Delete failed."));
      }

    }
    public function staff_member_delete($id) {
      // Check if the ID is valid
      if (empty($id)) {
          echo json_encode(array("status" => FALSE, "message" => "Invalid ID."));
          return;
      }


      // Attempt to delete the record based on the provided ID
      $deleted = $this->users->delete($id); // Pass the ID directly

      // Return a JSON response
      if ($deleted) {
          echo json_encode(array("status" => TRUE));
      } else {
          echo json_encode(array("status" => FALSE, "message" => "Delete failed."));
      }

    }


    public function package_delete($id) {
      // Check if the ID is valid
      if (empty($id)) {
          echo json_encode(array("status" => FALSE, "message" => "Invalid ID."));
          return;
      }

      // Attempt to delete the record based on the provided ID
      $deleted = $this->packages->delete($id); // Pass the ID directly

      // Return a JSON response
      if ($deleted) {
          echo json_encode(array("status" => TRUE));
      } else {
          echo json_encode(array("status" => FALSE, "message" => "Delete failed."));
      }

    }


    public function pricing_delete($id) {
      // Check if the ID is valid
      if (empty($id)) {
          echo json_encode(array("status" => FALSE, "message" => "Invalid ID."));
          return;
      }

      // Attempt to delete the record based on the provided ID
      $deleted = $this->pricing->delete($id); // Pass the ID directly

      // Return a JSON response
      if ($deleted) {
          echo json_encode(array("status" => TRUE));
      } else {
          echo json_encode(array("status" => FALSE, "message" => "Delete failed."));
      }

    }


    public function service_delete($id) {
      // Check if the ID is valid
      if (empty($id)) {
          echo json_encode(array("status" => FALSE, "message" => "Invalid ID."));
          return;
      }

      // Attempt to delete the record based on the provided ID
      $deleted = $this->services->delete($id); // Pass the ID directly

      // Return a JSON response
      if ($deleted) {
          echo json_encode(array("status" => TRUE));
      } else {
          echo json_encode(array("status" => FALSE, "message" => "Delete failed."));
      }

    }
    

    
    public function patient_delete($id) {
      // Check if the ID is valid
      if (empty($id)) {
          echo json_encode(array("status" => FALSE, "message" => "Invalid ID."));
          return;
      }

      // Attempt to delete the record based on the provided ID
      $deleted = $this->patient->delete($id); // Pass the ID directly

      // Return a JSON response
      if ($deleted) {
          echo json_encode(array("status" => TRUE));
      } else {
          echo json_encode(array("status" => FALSE, "message" => "Delete failed."));
      }

    }
    public function doctor_delete($id) {
      // Check if the ID is valid
      if (empty($id)) {
          echo json_encode(array("status" => FALSE, "message" => "Invalid ID."));
          return;
      }

      // Attempt to delete the record based on the provided ID
      $deleted = $this->doctor->delete($id); // Pass the ID directly

      // Return a JSON response
      if ($deleted) {
          echo json_encode(array("status" => TRUE));
      } else {
          echo json_encode(array("status" => FALSE, "message" => "Delete failed."));
      }

    }
    public function clinic_delete($id) {
      // Check if the ID is valid
      if (empty($id)) {
          echo json_encode(array("status" => FALSE, "message" => "Invalid ID."));
          return;
      }

      // Attempt to delete the record based on the provided ID
      $deleted = $this->specializations->deleteClinic($id); // Pass the ID directly

      // Return a JSON response
      if ($deleted) {
          echo json_encode(array("status" => TRUE));
      } else {
          echo json_encode(array("status" => FALSE, "message" => "Delete failed."));
      }

    }
    public function pharmacy_delete($id) {
      // Check if the ID is valid
      if (empty($id)) {
          echo json_encode(array("status" => FALSE, "message" => "Invalid ID."));
          return;
      }

      // Attempt to delete the record based on the provided ID
      $deleted = $this->specializations->deletePharmacy($id); // Pass the ID directly

      // Return a JSON response
      if ($deleted) {
          echo json_encode(array("status" => TRUE));
      } else {
          echo json_encode(array("status" => FALSE, "message" => "Delete failed."));
      }

    }
    public function lab_delete($id) {
      // Check if the ID is valid
      if (empty($id)) {
          echo json_encode(array("status" => FALSE, "message" => "Invalid ID."));
          return;
      }

      // Attempt to delete the record based on the provided ID
      $deleted = $this->specializations->deleteLab($id); // Pass the ID directly

      // Return a JSON response
      if ($deleted) {
          echo json_encode(array("status" => TRUE));
      } else {
          echo json_encode(array("status" => FALSE, "message" => "Delete failed."));
      }

    }
    public function  edit_package($id)
    {
        $data = $this->packages->get_by_id($id);
        
        echo json_encode($data);
    }
    public function  edit_service($id)
    {
        $data = $this->services->get_by_id($id);
        
        echo json_encode($data);
    }
    public function  edit_pricing($id)
    {
        $data = $this->pricing->get_by_id($id);
        
        echo json_encode($data);
    }

}
