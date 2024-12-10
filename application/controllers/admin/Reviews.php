<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reviews extends CI_Controller {

   public $data;
   public $session;
   public $timezone;
   public $input;
   public $db;
   public $reviews;


   public function __construct() {

        parent::__construct();

        if($this->session->userdata('admin_id') ==''){
            redirect(base_url().'admin/login');
        }
        
        $this->data['theme']     = 'admin';
        $this->data['module']    = 'reviews';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
         $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }
        $this->load->model('reviews_model','reviews');
        

    }


  public function index()
  {
      $this->data['page'] = 'index';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
     
  }

  public function reviews_list()
   {
      $list = $this->reviews->get_reviews_datatables();
      $data = array();
      $no = $_POST['start'];
      $a=1;
       
      foreach ($list as $reviews) {

        $rating=$reviews['rating'];
        $ratings='';
        for( $i=1; $i<=5 ; $i++) {
          if($i <= $rating){                                        
          $ratings .='<i class="fe fe-star text-warning"></i>';
          }else { 
          $ratings .='<i class="fe fe-star-o text-secondary"></i>';
          } 
        } 

        $doctor_profileimage=(!empty($reviews['doctor_profileimage']))?base_url().$reviews['doctor_profileimage']:base_url().'assets/img/user.png';
        $patient_profileimage=(!empty($reviews['patient_profileimage']))?base_url().$reviews['patient_profileimage']:base_url().'assets/img/user.png';
        $no++;
        $row = array();
        $row[] = $no;
        
        $row[] = '<h2 class="table-avatar">
                  <a href="javascript:void(0)" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="'.$patient_profileimage.'" alt="User Image"></a>
                  <a href="javascript:void(0)">'.ucfirst($reviews['patient_name']).' </a>
                </h2>';
        $row[] = '<h2 class="table-avatar">
                  <a target="_blank" href="'.base_url().'doctor-preview/'.$reviews['doctor_username'].'" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="'.$doctor_profileimage.'" alt="User Image">
                  </a>
                  <a target="_blank" href="'.base_url().'doctor-preview/'.$reviews['doctor_username'].'">Dr. '.ucfirst($reviews['doctor_name']).'</a>
                </h2>';
         $row[]=$ratings;
         $row[] = $reviews['review'];              

        $row[] = date('d M Y',strtotime($reviews['created_date']));
        $row[] = '<div class="actions text-right">
                              <a class="btn btn-sm bg-danger-light" onclick="delete_reviews('.$reviews['id'].')">
                                <i class="fe fe-trash"></i> Delete
                              </a>
                              
                  </div>';
        
        $data[] = $row;
      }



      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->reviews->reviews_count_all(),
              "recordsFiltered" => $this->reviews->reviews_count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }

  public function reviews_delete()
  {
    $id=$this->input->post('reviews_id');
    $this->db->where('id',$id);
    $this->db->delete('rating_reviews');
  }


}
