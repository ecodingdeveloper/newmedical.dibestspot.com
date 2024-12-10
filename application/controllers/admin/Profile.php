<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

   public $data;
   public $session;
   public $timezone;
   public $input;
   public $db;
  

   public function __construct() {

        parent::__construct();

        if($this->session->userdata('admin_id') ==''){
            redirect(base_url().'admin/login');
        }
        
        $this->data['theme']     = 'admin';
        $this->data['module']    = 'profile';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
         $this->timezone = $this->session->userdata('time_zone');
         
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }

    }


	public function index()
	{
	    $this->data['page'] = 'index';
      $this->data['profile'] = $this->db->where('id',$this->session->userdata('admin_id'))->get('administrators')->row_array();
      $this->data['profile1'] = $this->db->where('role',7)->get('users')->row_array();

	  $result = $this->db->where('id',$this->session->userdata('admin_id'))->get('administrators')->result_array();
	  foreach($result as $rows) {
        $country=$rows['country'];      
        $countryid=get_countryid($country);  
	    $city_of_country = get_city_of_country($countryid); 
		$this->data['city_of_country']=$city_of_country;
	    
    }
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
	   
	}

  public function update_profile()
  {
      $inputdata=array();
      $response=array();
    
      $inputdata['name']=$this->input->post('name');
      $inputdata['email']=$this->input->post('email');
      $inputdata['country']=$this->input->post('country');
      $inputdata['city']=$this->input->post('city');
      $inputdata['biography']=$this->input->post('biography');
        
         $this->db->where('id',$this->session->userdata('admin_id')); 
         $this->db->update('administrators',$inputdata);
       $result= ($this->db->affected_rows()!= 1)? false:true;
          if($result==true)
          {   
               
               $response['msg']='Profile update successfully'; 
               $response['status']=200;              
          }
         else
          {
              $response['msg']='Profile update failed';
              $response['status']=500; 
          } 

     echo json_encode($response);

  }

  public function team_member_profile()
  {
      $inputdata = array();
      $response = array();
      
      // Get input data from POST request
      $inputdata['first_name'] = $this->input->post('firstname');
      $inputdata['last_name'] = $this->input->post('lastname');
      $inputdata['email'] = $this->input->post('email');
      $inputdata['password']=trim(md5($this->input->post('password1')));
      $inputdata['role']= 7;
      
      $email = $this->input->post('email');
    
      if ($email) {
          // Check if a user with the given email exists
          $this->db->where('email', $email);
          $query = $this->db->get('users');
          
          if ($query->num_rows() > 0) {
              // Update the existing record
              $this->db->where('email', $email);
              $this->db->update('users', $inputdata);
  
              if ($this->db->affected_rows() > 0) {
                  $response['msg'] = 'Profile updated successfully';
                  $response['status'] = 200;
              } else {
                  // Handle case where no rows were updated
                  $response['msg'] = 'No changes made or update failed';
                  $response['status'] = 500;
              }
          } else {
              // Insert a new record
              $this->db->insert('users', $inputdata);
  
              if ($this->db->affected_rows() > 0) {
                  $response['msg'] = 'Profile created successfully';
                  $response['status'] = 201; // Created
              } else {
                  $response['msg'] = 'Profile creation failed';
                  $response['status'] = 500;
              }
          }
      } else {
          $response['msg'] = 'Email is required';
          $response['status'] = 400; // Bad Request
      }
  
      echo json_encode($response);
  }
  

  public function change_password()
  {
      $inputdata=array();
      $response=array();
    
      $currentpassword=trim($this->input->post('currentpassword'));
      $inputdata['password']=trim(md5($this->input->post('password')));

      if($currentpassword!='')
      {
          $this->db->where('id',$this->session->userdata('admin_id')); 
          $this->db->update('administrators',$inputdata);
          $result= ($this->db->affected_rows()!= 1)? false:true;
            if($result==true)
            {   
                 
                 $response['msg']='Password update successfully'; 
                 $response['status']=200;              
            }
           else
            {
                $response['msg']='Password update failed';
                $response['status']=500; 
            } 
      }
      else
      {
                $response['msg']='Please enter current password';
                $response['status']=500; 
      }
         
         

     echo json_encode($response);

  }

  public function check_currentpassword()
    {
        $id = $this->session->userdata['admin_id'];
        $password = $this->input->post('currentpassword');     
        $result = $this->is_valid_password($id,$password);
         if ($result > 0) {
                   echo 'true';
           } else {
                   echo 'false';
           }
           
           
    }

  public function check_newpassword()
    {
        $id = $this->session->userdata['admin_id'];
        $password = $this->input->post('password');     
        $result = $this->is_valid_password($id,$password);
         if ($result > 0) {
                   echo 'false';
           } else {
                   echo 'true';
           }
           
           
    }

    public function is_valid_password($id,$password){


        $this->db->select('id,email');
        $this->db->from('administrators');
        $this->db->where('id', $id);
        $this->db->where('password', md5($password));
        $result = $this->db->get()->row_array();
        
       return $result;        
    }

      public function crop_profile_img($prev_img='') {
         $max_execution_time=3000;
         /** @var string $max_execution_time */
        ini_set('max_execution_time', $max_execution_time);

        ini_set('memory_limit', '-1');

    if(!empty($prev_img))

    {

      $file_path = FCPATH.$prev_img;

                    if(!file_exists($file_path)){

                        unlink(FCPATH.$prev_img);

                    }

    }

    $error_msg       = '';

        $av_src          = $this->input->post('avatar_src');

        $av_data         = json_decode($this->input->post('avatar_data'),true);

        $av_file         = $_FILES['avatar_file'];

        $src             = 'uploads/profileimage/'.$av_file['name'];

        $imageFileType   = pathinfo($src,PATHINFO_EXTENSION);

        $image_name     = time().'.'.$imageFileType;

        $src2            = 'uploads/profileimage/temp/'.$image_name;

        move_uploaded_file($av_file['tmp_name'], $src2);
		

        $ref_path = '/uploads/profileimage/temp/';

        $image1          = $this->crop_images($image_name,$av_data,200,200,"/uploads/profileimage/",$ref_path);

        $rand = rand(100,999);

        $inputdata=array();
        $inputdata['profileimage'] ='uploads/profileimage/'.$image_name;
         $this->db->where('id',$this->session->userdata('admin_id')); 
          $this->db->update('administrators',$inputdata);

        $response = array(

            'state'  => 200,

            'message' => $error_msg,

            'result' => 'uploads/profileimage/'.$image_name,

            'img_name1' => $image_name

        );
       //redirect($_SERVER['REQUEST_URI'], 'refresh');
        echo json_encode($response);
        //redirect(base_url().'admin/profile');
    }

    public function crop_images($image_name,$av_data,$t_width,$t_height,$path,$ref_path) {

        $w                 = $av_data['width'];

        $h                 = $av_data['height'];

        $x1                = $av_data['x'];

        $y1                = $av_data['y'];
        $source ="";

        list($imagewidth, $imageheight, $imageType) = getimagesize(FCPATH.$ref_path.$image_name);

        $imageType                                  = image_type_to_mime_type($imageType);

        $ratio             = ($t_width/$w);

        $nw                = ceil($w * $ratio);

        $nh                = ceil($h * $ratio);

        $newImage          = imagecreatetruecolor($nw,$nh);



        $backgroundColor = imagecolorallocate($newImage, 0, 0, 0);

        imagefill($newImage, 0, 0, $backgroundColor);

        $black = imagecolorallocate($newImage, 0, 0, 0);



        // Make the background transparent

        imagecolortransparent($newImage, $black);







        switch($imageType) {

            case "image/gif"  : $source = imagecreatefromgif(FCPATH.$ref_path.$image_name);

            break;

            case "image/pjpeg":

            case "image/jpeg" :

            case "image/jpg"  : $source = imagecreatefromjpeg(FCPATH.$ref_path.$image_name);

            break;

            case "image/png"  :

            case "image/x-png": $source = imagecreatefrompng(FCPATH.$ref_path.$image_name);

            break;

        }
        $x1 = intval($x1);
        $y1 = intval($x1);
        $nw = intval($x1);
        $nh = intval($x1);
        $w  = intval($x1);
        $h  = intval($x1);
        imagecopyresampled($newImage,$source,0,0,$x1,$y1,$nw,$nh,$w,$h);

            switch($imageType) {

            case "image/gif"  : imagegif($newImage,FCPATH.$path.$image_name);

            break;

            case "image/pjpeg":

            case "image/jpeg" :

            case "image/jpg"  : imagejpeg($newImage,FCPATH.$path.$image_name,100);

            break;

            case "image/png"  :

            case "image/x-png": imagepng($newImage,FCPATH.$path.$image_name);

            break;

        }

    } 
 


}
