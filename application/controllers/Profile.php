<?php

defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
class Profile extends CI_Controller {

    public $data;
    public $session;
    public $timezone;
    public $lang;
    public $language;
    public $profile;
    public $input;
    public $db;
    public $upload;

  public function __construct() {

        parent::__construct();

        if($this->session->userdata('user_id') ==''){
          if($this->session->userdata('admin_id'))
            {
              redirect(base_url().'home');
            }
            else
            {
              redirect(base_url().'signin');
            }
        }

        $this->data['theme']     = 'web';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
        $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }

        // $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):'english';
        $lan=default_language();
        $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):strtolower($lan['language']);
        $this->data['language'] = $this->lang->load('content', $lang, true);
        $this->language = $this->lang->load('content', $lang, true);
        $this->load->model('profile_model','profile');
        
         
    }

    public function index()
  {
      
      if($this->session->userdata('role')=='1'){
        $this->data['module']    = 'doctor';
        $this->data['page'] = 'doctor_profile';
        $this->data['profile'] = $this->profile->get_profile_details($this->session->userdata('user_id'));
        $this->data['education'] = $this->profile->get_education_details($this->session->userdata('user_id'));
        $this->data['experience'] = $this->profile->get_experience_details($this->session->userdata('user_id'));
        $this->data['awards'] = $this->profile->get_awards_details($this->session->userdata('user_id'));
        $this->data['memberships'] = $this->profile->get_memberships_details($this->session->userdata('user_id'));
        $this->data['registrations'] = $this->profile->get_registrations_details($this->session->userdata('user_id'));
        $this->data['clinic_images'] = $this->profile->get_clinic_images($this->session->userdata('user_id'));
        $this->data['business_hours'] = $this->profile->get_business_hours($this->session->userdata('user_id'));
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
      }else if($this->session->userdata('role')=='4') {
        $this->data['module']    = 'lab';
        $this->data['page'] = 'lab_profile';
        $this->data['profile'] = $this->profile->get_profile_details($this->session->userdata('user_id'));
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
      }else if($this->session->userdata('role')=='5'){
        $this->data['module']    = 'pharmacy';
        $this->data['page'] = 'pharmacy_profile';
        $this->data['profile'] = $this->profile->get_profile_details($this->session->userdata('user_id'));
        $this->data['specification'] = $this->db->get_where('pharmacy_specifications',array('pharmacy_id'=>$this->session->userdata('user_id')))->row_array();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
      }
      else if ($this->session->userdata('role') == '6') {
        $this->data['module']    = 'doctor';
        $this->data['page'] = 'doctor_profile';
        $this->data['profile'] = $this->profile->get_profile_details($this->session->userdata('user_id'));
        $this->data['education'] = $this->profile->get_education_details($this->session->userdata('user_id'));
        $this->data['experience'] = $this->profile->get_experience_details($this->session->userdata('user_id'));
        $this->data['awards'] = $this->profile->get_awards_details($this->session->userdata('user_id'));
        $this->data['memberships'] = $this->profile->get_memberships_details($this->session->userdata('user_id'));
        $this->data['registrations'] = $this->profile->get_registrations_details($this->session->userdata('user_id'));
        $this->data['clinic_images'] = $this->profile->get_clinic_images($this->session->userdata('user_id'));
        $this->data['business_hours'] = $this->profile->get_business_hours($this->session->userdata('user_id'));
        
        $this->data['clinic_details'] = $this->profile->get_clinic_doctor_details($this->session->userdata('user_id'));
  
       /* $this->data['insurance_list'] = $this->profile->get_insyrance_details($this->session->userdata('user_id'));
  
  
        $this->data['doctor_photos'] = $this->profile->get_doctor_images($this->session->userdata('user_id'));
  
        $this->data['all_language'] = $this->profile->get_language();
  
        $languages_known_array = [];
        $languages_known = $this->profile->get_language_known($this->session->userdata('user_id'));
        if (isset($languages_known) && !empty($languages_known)) {
          foreach ($languages_known as $languages_kn) {
            $languages_known_array[] = $languages_kn['language_id'];
          }
        }
        $this->data['languages_known'] = $languages_known_array;*/
  
  
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
      }else
      {
        $this->data['module']    = 'patient';
        $this->data['page'] = 'patient_profile';
        $this->data['profile'] = $this->profile->get_profile_details($this->session->userdata('user_id'));
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
      }
     
  }

   public function check_mobileno()
  {
        $mobileno = $this->input->post('mobileno');     
        $result = $this->profile->check_mobileno($mobileno,$this->session->userdata('user_id'));
         if ($result > 0) {
                   echo 'false';
           } else {
                   echo 'true';
           }
           
  }


 public function crop_profile_img($prev_img='') {
      // @var string $max_execution_time 
      $max_execution_time=3000;
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

       //$imageFileType   = pathinfo($src,PATHINFO_EXTENSION);

        //$image_name     = time().'.'.$imageFileType;
		
		$targetDir = 'uploads/profileimage/';
		$targetFile = $targetDir . basename($av_file['name']);
		$imageFileType = pathinfo($targetFile, PATHINFO_EXTENSION);
		// Optionally, you can rename the file
		$image_name = time() . '.' . $imageFileType;
		$targetFile = $targetDir . $image_name;

        //$src2            = 'uploads/profileimage/temp/'.$image_name;
		
        move_uploaded_file($av_file['tmp_name'], $targetFile);

        $ref_path = '/uploads/profileimage/';


        $image1          = $this->crop_images($image_name,$av_data,200,200,"/uploads/profileimage/",$ref_path);

        $rand = rand(100,999);

        $inputdata=array();
        $inputdata['profileimage'] ='uploads/profileimage/'.$image_name;
        
         $id=$this->session->userdata('user_id');
         $this->profile->update($inputdata,$id);

        $response = array(

            'state'  => 200,

            'message' => $error_msg,

            'result' => 'uploads/profileimage/'.$image_name,

            'img_name1' => $image_name

        );

        echo json_encode($response);

    }
	
	public function crop_images($image_name, $av_data, $t_width, $t_height, $path, $ref_path) {
    $w = $av_data['width'];
    $h = $av_data['height'];
    $x1 = $av_data['x'];
    $y1 = $av_data['y'];

    $imagePath = FCPATH . $ref_path . $image_name;

    // Check if the file exists
    if (!file_exists($imagePath)) {
        throw new Exception("File does not exist: " . $imagePath);
    }

    // Get image dimensions and type
    list($imagewidth, $imageheight, $imageType) = getimagesize($imagePath);
    if ($imageType === false) {
        throw new Exception("Invalid image file: " . $imagePath);
    }

    $imageType = image_type_to_mime_type($imageType);

    $ratio = $t_width / $w;
    $nw = ceil($w * $ratio);
    $nh = ceil($h * $ratio);

    $newImage = imagecreatetruecolor($nw, $nh);
    $backgroundColor = imagecolorallocate($newImage, 0, 0, 0);
    imagefill($newImage, 0, 0, $backgroundColor);
    $black = imagecolorallocate($newImage, 0, 0, 0);

    // Make the background transparent
    imagecolortransparent($newImage, $black);

    $source = null;

    switch ($imageType) {
        case "image/gif":
            $source = imagecreatefromgif($imagePath);
            break;
        case "image/jpeg":
        case "image/pjpeg":
        case "image/jpg":
            $source = imagecreatefromjpeg($imagePath);
            break;
        case "image/png":
        case "image/x-png":
            $source = imagecreatefrompng($imagePath);
            break;
        default:
            throw new Exception("Unsupported image type: " . $imageType);
    }

    if ($source !== null) {
        imagecopyresampled($newImage, $source, 0, 0, $x1, $y1, $nw, $nh, $w, $h);

        switch ($imageType) {
            case "image/gif":
                imagegif($newImage, FCPATH . $path . $image_name);
                break;
            case "image/jpeg":
            case "image/pjpeg":
            case "image/jpg":
                imagejpeg($newImage, FCPATH . $path . $image_name, 100);
                break;
            case "image/png":
            case "image/x-png":
                imagepng($newImage, FCPATH . $path . $image_name);
                break;
        }
    } else {
        throw new Exception("Failed to create image resource.");
    }

    imagedestroy($newImage);
    imagedestroy($source);
}



   /*public function crop_images($image_name,$av_data,$t_width,$t_height,$path,$ref_path) {
		//dd('gfgf');

        $w                 = $av_data['width'];

        $h                 = $av_data['height'];

        $x1                = $av_data['x'];

        $y1                = $av_data['y'];

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





        $source="";

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

    }*/

  public function update_doctor_profile()
  {
	  //die('df');

    $id=$this->session->userdata('user_id');

    $inputdata['first_name']=$this->input->post('first_name');
    $inputdata['last_name']=$this->input->post('last_name');
    $inputdata['mobileno']=$this->input->post('mobileno');
    $inputdata['country_code']=$this->input->post('country_code');
    $inputdata['is_updated']=1;

    $userdata['user_id']=$id;
    $userdata['gender']=$this->input->post('gender');
    $userdata['dob']=date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('dob'))));
    $userdata['biography']=$this->input->post('biography');
    $userdata['clinic_name']=$this->input->post('clinic_name');
    $userdata['clinic_address']=$this->input->post('clinic_address');
    $userdata['clinic_address2']=$this->input->post('clinic_address2');
    $userdata['clinic_city']=$this->input->post('clinic_city');
    $userdata['clinic_state']=$this->input->post('clinic_state');
    $userdata['clinic_country']=$this->input->post('clinic_country');
    $userdata['clinic_postal']=$this->input->post('clinic_postal');
    $userdata['address1']=$this->input->post('address1');
    $userdata['address2']=$this->input->post('address2');
    $userdata['country']=$this->input->post('country');
    $userdata['state']=$this->input->post('state');
    $userdata['city']=$this->input->post('city');
    $userdata['postal_code']=$this->input->post('postal_code');
    $userdata['price_type']=$this->input->post('price_type');
    $userdata['amount']=$this->input->post('amount');
    $userdata['services']=$this->input->post('services');
    $userdata['specialization']=$this->input->post('specialization');
    $userdata['update_at']=date('Y-m-d H:i:s');
    if (isset($_POST['clinic_name']) && $_POST['clinic_name'] != '') {
      $userdata['clinic_name'] = $_POST['clinic_name'];
    } else {
      $userdata['clinic_name'] = '';
    }
    $result=$this->profile->updates($inputdata,$userdata,$id);
        if($result==true)
        {
                
               if(!empty($this->input->post('degree')))
               {
                    $where = array('user_id' => $id);
                    $this->db->delete('education_details',$where); 

                    $degree=array_filter($this->input->post('degree'));
                    $institute=array_filter($this->input->post('institute'));
                    $year_of_completion=array_filter($this->input->post('year_of_completion'));

                      for ($i=0; $i <count($degree) ; $i++) { 
                       $edudata = array('user_id' => $id,
                                        'degree' => $degree[$i],
                                        'institute' => $institute[$i],
                                        'year_of_completion'=>$year_of_completion[$i]);
                       $this->db->insert('education_details', $edudata); 
                    }
               }

               if(!empty($this->input->post('hospital_name')))
               {
                    $where = array('user_id' => $id);
                    $this->db->delete('experience_details',$where); 
                    
                    $hospital_name=array_filter($this->input->post('hospital_name'));
                    $from=array_filter($this->input->post('from'));
                    $to=array_filter($this->input->post('to'));
                    $designation=array_filter($this->input->post('designation'));

                      for ($j=0; $j <count($hospital_name) ; $j++) { 
                       $expdata = array('user_id' => $id,
                                        'hospital_name' => $hospital_name[$j],
                                        'from' => $from[$j],
                                        'to' => $to[$j],
                                        'designation'=>$designation[$j]);
                       $this->db->insert('experience_details', $expdata); 
                    }
               }

               if(!empty($this->input->post('awards')))
               {
                    $where = array('user_id' => $id);
                    $this->db->delete('awards_details',$where); 
                    
                    $awards=array_filter($this->input->post('awards'));
                    $awards_year=array_filter($this->input->post('awards_year'));

                      for ($k=0; $k <count($awards) ; $k++) { 
                       $awadata = array('user_id' => $id,
                                        'awards' => $awards[$k],
                                        'awards_year' => $awards_year[$k]);
                       $this->db->insert('awards_details', $awadata); 
                    }
               }

               if(!empty($this->input->post('memberships')))
               {
                    $where = array('user_id' => $id);
                    $this->db->delete('memberships_details',$where); 
                    
                    $memberships=array_filter($this->input->post('memberships'));
                    
                      for ($l=0; $l <count($memberships) ; $l++) { 
                       $memdata = array('user_id' => $id,
                                        'memberships' => $memberships[$l]);
                       $this->db->insert('memberships_details', $memdata); 
                    }
               }

               if(!empty($this->input->post('registrations')))
               {
                    $where = array('user_id' => $id);
                    $this->db->delete('registrations_details',$where); 
                    
                    $registrations=array_filter($this->input->post('registrations'));
                    $registrations_year=array_filter($this->input->post('registrations_year'));
                   
                      for ($m=0; $m <count($registrations) ; $m++) { 
                       $regdata = array('user_id' => $id,
                                        'registrations' => $registrations[$m],
                                        'registrations_year' => $registrations_year[$m]);
                       $this->db->insert('registrations_details', $regdata); 
                    }
               } 

                /* Reg */
          if(!empty($this->input->post('availability'))){

            $where = array('user_id' => $id);
            $this->db->delete('business_hours',$where);

            $business_hours_array = array();

            if(!empty($_POST['availability'][0]['day'])){
              $from = $_POST['availability'][0]['from_time'];
              $to = $_POST['availability'][0]['to_time'];
              for ($i=1; $i <= 7; $i++) {
                $business_hours_array[$i] = array('day'=>$i,'from_time'=>$from,'to_time'=>$to);
              }

            }else{
              if(!empty($_POST['availability'][0])){
                unset($_POST['availability'][0]);
              }
              $business_hours_array = array_map('array_filter', $_POST['availability']);
          $business_hours_array = array_filter($business_hours_array);
            }
            if(!empty($business_hours_array)){
              $business_hours_array = array_values($business_hours_array);
            }
           
              $business_hours_data = array(
                            'business_hours'=>json_encode($business_hours_array),
                            'user_id' => $id
                              );
                $this->db->insert('business_hours',$business_hours_data);  
             
              
           
          }
              
             $response['msg']=$this->language['lg_profile_success']; 
             $response['status']=200;              
        }
       else
        {
            $response['msg']=$this->language['lg_profile_update_'];
            $response['status']=500; 
        } 
   
     echo json_encode($response);
  }

  public function upload_files()
  {

    try {

      $user_id = $this->session->userdata('user_id');    

      $path = "uploads/clinic_uploads/".$user_id;
      if(!is_dir($path)){
        mkdir($path,0777,true);
      }

      $target_file =$path . basename($_FILES["file"]["name"]);
      $file_type = pathinfo($target_file,PATHINFO_EXTENSION);

      if($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg" && $file_type != "gif" ){
        $type = 'others';
      }else{
        $type = 'image';
      }


      $config['upload_path']   = './'.$path;
      $config['allowed_types'] = 'jpeg|jpg|png|gif';   
      $this->load->library('upload',$config);

      if($this->upload->do_upload('file')){ 

        $file_name=$this->upload->data('file_name');  

        $data=array(
          'user_id'=>$user_id,
          'clinic_image'=>$file_name,
          'token'=>rand(),
          'status'=>'1'
        );
        $this->db->insert('clinic_images',$data);
      }else{
        echo  json_encode(array('error'=>$this->upload->display_errors()));
      }

    }  catch (Exception $e) {
      /*echo '<pre>';
      print_r($e->getMessages());
      die(); */
	  echo '<script>toastr.error('.$e->getMessages().');</script>';

    }
  }

  public function delete_clinic_image(){

    $id=$this->input->post('id');

    $this->db->where('id',$id);
    $this->db->delete('clinic_images');
     $response['msg']=$this->language['lg_image_deleted_s']; 
     $response['status']=200;  
     echo json_encode($response);
  }

  public function update_patient_profile()
  {

    $id=$this->session->userdata('user_id');

    if(isset($_POST['pharmacy_name']) && $_POST['pharmacy_name']!=''){
    $inputdata['pharmacy_name']=$this->input->post('pharmacy_name');        
    }



    $inputdata['first_name']=$this->input->post('first_name');
    $inputdata['last_name']=$this->input->post('last_name');
    $inputdata['mobileno']=$this->input->post('mobileno');
    $inputdata['country_code']=$this->input->post('country_code');
    $inputdata['is_updated']=1;

    $userdata['user_id']=$id;
    $userdata['gender']=$this->input->post('gender');
    $userdata['dob']=date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('dob'))));
    // $userdata['blood_group']=$this->input->post('blood_group');
    $userdata['address1']=$this->input->post('address1');
    $userdata['address2']=$this->input->post('address2');
    $userdata['country']=$this->input->post('country');
    $userdata['state']=$this->input->post('state');
    $userdata['city']=$this->input->post('city');
    $userdata['postal_code']=$this->input->post('postal_code');
    $userdata['update_at']=date('Y-m-d H:i:s');

    $result=$this->profile->updates($inputdata,$userdata,$id);

    
    if($result==true)
    {
         $response['msg']=$this->language['lg_profile_success']; 
         $response['status']=200;              
    }
   else
    {
        $response['msg']=$this->language['lg_profile_update_'];
        $response['status']=500; 
    } 
   
    echo json_encode($response);
  }

  public function update_pharmacy_profile()
  {
    

    $id=$this->session->userdata('user_id');

    if(isset($_POST['home_delivery']) && $_POST['home_delivery']!=''){
      $specdata['home_delivery']=$this->input->post('home_delivery');
      $specdata['pharmacy_id']=$id;      
    }

    if(isset($_POST['hrsopen']) && $_POST['hrsopen']!=''){
      $specdata['24hrsopen']=$this->input->post('hrsopen');
      $specdata['pharmacy_id']=$id;    
    }

    if(isset($_POST['pharmacy_opens_at']) && $_POST['pharmacy_opens_at']!=''){
      $specdata['pharamcy_opens_at']=$this->input->post('pharmacy_opens_at');
      $specdata['pharmacy_id']=$id;       
    }

    $inputdata['pharmacy_name']=$this->input->post('pharmacy_name');
    $inputdata['first_name']=$this->input->post('first_name');
    $inputdata['last_name']=$this->input->post('last_name');
    $inputdata['mobileno']=$this->input->post('mobileno');
    $inputdata['country_code']=$this->input->post('country_code');
    $inputdata['is_updated']=1;

    $userdata['user_id']=$id;
    $userdata['gender']=$this->input->post('gender');
    $userdata['dob']=date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('dob'))));
    // $userdata['blood_group']=$this->input->post('blood_group');
    $userdata['address1']=$this->input->post('address1');
    // $userdata['address2']=$this->input->post('address2');
    $userdata['country']=$this->input->post('country');
    $userdata['state']=$this->input->post('state');
    $userdata['city']=$this->input->post('city');
    $userdata['postal_code']=$this->input->post('postal_code');
    $userdata['update_at']=date('Y-m-d H:i:s');

    $result=$this->profile->updates($inputdata,$userdata,$id);
    if(!empty($specdata)){
      $specresult=$this->profile->specupdates($specdata,$id);
    }

    if($result==true)
    {
         $response['msg']=$this->language['lg_profile_success']; 
         $response['status']=200;              
    }
    else
    {
        $response['msg']=$this->language['lg_profile_update_'];
        $response['status']=500; 
    } 
   
    echo json_encode($response);
  }

  public function change_password()
  {
    if($this->session->userdata('role')=='1' || $this->session->userdata('role') == '6'){
      $this->data['module']    = 'doctor';
      $this->data['page'] = 'change_password';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }else if($this->session->userdata('role')=='4') {
      $this->data['module']    = 'lab';
      $this->data['page'] = 'change_password';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }else if($this->session->userdata('role')=='5'){
      $this->data['module']    = 'pharmacy';
      $this->data['page'] = 'change_password';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }
    else
    {
      $this->data['module']    = 'patient';
      $this->data['page'] = 'change_password';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }
  }

  public function social_media(){

    if($this->session->userdata('role')=='1' || $this->session->userdata('role') == '6'){
      $id=$this->session->userdata('user_id');
      $this->db->where('doctor_id',$id);
      $result=$this->db->get('social_media')->row_array();
      
      $this->data['module']    = 'doctor';
      $this->data['page'] = 'social_media';
      $this->data['social_media']=$result;
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }

  }

  public function update_social_media()
  {
    

    $id=$this->session->userdata('user_id');

    $inputdata['facebook']=$this->input->post('facebook');
    $inputdata['twitter']=$this->input->post('twitter');
    $inputdata['instagram']=$this->input->post('instagram');
    $inputdata['pinterest']=$this->input->post('pinterest');
    $inputdata['linkedin']=$this->input->post('linkedin');
    $inputdata['youtube']=$this->input->post('youtube');
    $inputdata['doctor_id']=$id;
    $inputdata['updated_at']=date('Y-m-d H:i:s');

    $this->db->where('doctor_id',$id);
    $exits=$this->db->get('social_media')->result_array();
    if(!empty($exits))
    {
      $this->db->where('doctor_id',$id);
      $this->db->update('social_media',$inputdata);
    }
    else
    {
      $this->db->insert('social_media',$inputdata);
    }
    

    $result=($this->db->affected_rows()!= 1)? false:true;
    if($result==true)
    {
         $response['msg']=$this->language['lg_profile_success_up_sm']; 
         $response['status']=200;              
    }
   else
    {
        $response['msg']=$this->language['lg_profile_success_up_sm_not'];
        $response['status']=500; 
    } 
   
    echo json_encode($response);
  }

  public function check_currentpassword()
  {
    $currentpassword = $this->input->post('currentpassword'); 
    $id=$this->session->userdata('user_id');    
    $result = $this->profile->check_currentpassword($currentpassword,$id);
    if ($result > 0) {
      echo 'true';
    } else {
      echo 'false';
    }  
  }

  public function password_update()
  {
    $inputdata=array();
    $response=array();
    $currentpassword = $this->input->post('currentpassword');
    $id=$this->session->userdata('user_id');
    $result = $this->profile->check_currentpassword($currentpassword,$id);
    if ($result > 0) {
      $inputdata['password']=md5($this->input->post('password'));
      $inputdata['confirm_password']=md5($this->input->post('confirm_password'));
      $id=$this->session->userdata('user_id');
      $result=$this->profile->update($inputdata,$id);
      if($result==true)
      {
           $response['msg']=$this->language['lg_password_succes']; 
           $response['status']=200;              
      }
     else
      {
          $response['msg']=$this->language['lg_password_change'];
          $response['status']=500; 
      }
    }else{
      $response['msg']=$this->language['lg_current_passwor1'];
      $response['status']=500;
    }

    echo json_encode($response);
  }

  public function update_lab_profile() {
    $inputdata=array();
    $userdata=array();
    $response=array();
    $id=$this->session->userdata('user_id');

    $inputdata['first_name']=$this->input->post('first_name');
    $inputdata['last_name']=$this->input->post('last_name');
    $inputdata['mobileno']=$this->input->post('mobileno');
    $inputdata['country_code']=$this->input->post('country_code');
    $inputdata['is_updated']=1;

    $userdata['user_id']=$id;
    $userdata['gender']=$this->input->post('gender');
    $userdata['dob']=date('Y-m-d',strtotime(str_replace('/', '-', $this->input->post('dob'))));
    // $userdata['blood_group']=$this->input->post('blood_group');
    $userdata['address1']=$this->input->post('address1');
    // $userdata['address2']=$this->input->post('address2');
    $userdata['country']=$this->input->post('country');
    $userdata['state']=$this->input->post('state');
    $userdata['city']=$this->input->post('city');
    $userdata['postal_code']=$this->input->post('postal_code');
    $userdata['update_at']=date('Y-m-d H:i:s');

    $result=$this->profile->updates($inputdata,$userdata,$id);
    if($result==true)
    {
        $response['msg']=$this->language['lg_profile_success']; 
        $response['status']=200;              
    }
    else
    {
        $response['msg']=$this->language['lg_profile_update_'];
        $response['status']=500; 
    } 

    echo json_encode($response);
  }

}
