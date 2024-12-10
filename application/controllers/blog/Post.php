<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//define('FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

class Post extends CI_Controller {

   public $data;
   public $session;
   public $timezone;
   public $lang;
   public $input;
   public $db;
   public $post;
   public $language;

   public function __construct() {

        parent::__construct();

        if($this->session->userdata('admin_id') =='' && $this->session->userdata('user_id') ==''){
            redirect(base_url().'home');
        }
        else{
          if($this->session->userdata('admin_id')!='')
          {

          }
          elseif($this->session->userdata('user_id')!='')
          {
            if($this->session->userdata('role')=='1' || $this->session->userdata('role')=='6')
            {

            }
            else
            {
              redirect(base_url().'home');
            }
          }
          else
          {
            redirect(base_url().'home');
          }
        }

        $this->load->helper('ckeditor'); 
    // Array with the settings for this instance of CKEditor (you can have more than one)
        $this->data['ckeditor_editor1'] = array
        (
          //id of the textarea being replaced by CKEditor
          'id'   => 'ck_editor_textarea_id',
          // CKEditor path from the folder on the root folder of CodeIgniter
          'path' => 'assets/js/ckeditor',
          // optional settings
          'config' => array
          (
            'toolbar' => "Full",
            'filebrowserBrowseUrl'      => base_url().'assets/js/ckfinder/ckfinder.html',
            'filebrowserImageBrowseUrl' => base_url().'assets/js/ckfinder/ckfinder.html?Type=Images',
            'filebrowserFlashBrowseUrl' => base_url().'assets/js/ckfinder/ckfinder.html?Type=Flash',
            'filebrowserUploadUrl'      => base_url().'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            'filebrowserImageUploadUrl' => base_url().'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            'filebrowserFlashUploadUrl' => base_url().'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
          )
        );  
        
        $this->data['theme']     = 'blog';
        $this->data['module']    = 'post';
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
        $this->load->model('blog/post_model','post');
        

    }


    public function index()
    {
       $this->data['page'] = 'index';
       $this->load->vars($this->data);
       $this->load->view($this->data['theme'].'/template');
       
    }

    public function pending_post()
    {
       $this->data['page'] = 'pending_post';
       $this->load->vars($this->data);
       $this->load->view($this->data['theme'].'/template');
       
    }

    public function add_post()
    {
      
      $this->data['page'] = 'add_post';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
       
    }

    public function edit_post($post_id)
    {
      
      $this->data['page'] = 'edit_post';
      $this->data['posts']=$this->post->get_posts(base64_decode($post_id));
      $this->data['tags']=$this->post->get_tags(base64_decode($post_id));
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
       
    }

    public function image_upload_product() 

    { 

      ini_set('max_execution_time', 3000); 

      ini_set('memory_limit', '-1');

      $html=$error_msg= $shop_ad_id='';

      $error_sts=0;

      $row_id =$this->input->post('row_id');        
      $image_data = $this->input->post('img_data');


      $base64string = str_replace('data:image/png;base64,', '', $image_data);

      $base64string = str_replace(' ', '+', $base64string);

      $data = base64_decode($base64string);

      $img_name = time();

      $file_name_final=$img_name.".png";

      file_put_contents('uploads/product_image/'.$file_name_final, $data); 

      $source_image= 'uploads/product_image/'.$file_name_final; 
      $upload_url='uploads/product_image/';

      $image_url = $this->image_resize(308,206,$source_image,'308x206_'.$file_name_final,$upload_url);

      $preview_image_url = $this->image_resize(680,454,$source_image,'680x454_'.$file_name_final,$upload_url);

      
         $html = '<div id="remove_image_div_'.$row_id.'" class="upload-images">
               <img src="'.base_url().$image_url.'" alt="" height="42" width="42">
               <a href="javascript:;" onclick="remove_image(\''.$image_url.'\',\''.$preview_image_url.'\',\''.$row_id.'\')"  class="uploaded-remove btn btn-icon btn-danger btn-sm"><i class="far fa-trash-alt"></i></a>
               </div>';  

      $row_id=$row_id+1;

      $response = array(
        'state'  => 200,
        'message' => $error_msg,
        'result' => $html,
        'image_url' => $image_url,
        'preview_image_url' => $preview_image_url,
        'sts' => $error_sts,
        'row_id' => $row_id,
      );

      echo json_encode($response); 

    }

    public function image_upload() 

    { 

      ini_set('max_execution_time', 3000); 

      ini_set('memory_limit', '-1');

      $html=$error_msg= $shop_ad_id='';

      $error_sts=0;

      $row_id =$this->input->post('row_id');        
      $image_data = $this->input->post('img_data');


      $base64string = str_replace('data:image/png;base64,', '', $image_data);

      $base64string = str_replace(' ', '+', $base64string);

      $data = base64_decode($base64string);

      $img_name = time();

      $file_name_final=$img_name.".png";

      file_put_contents('uploads/post_image/'.$file_name_final, $data); 

      $source_image= 'uploads/post_image/'.$file_name_final; 
      $upload_url='uploads/post_image/';

      $image_url = $this->image_resize(308,206,$source_image,'308x206_'.$file_name_final,$upload_url);

      $preview_image_url = $this->image_resize(680,454,$source_image,'680x454_'.$file_name_final,$upload_url);

      
         $html = '<div id="remove_image_div_'.$row_id.'" class="upload-images">
               <img src="'.base_url().$image_url.'" alt="" height="42" width="42">
               <a href="javascript:;" onclick="remove_image(\''.$image_url.'\',\''.$preview_image_url.'\',\''.$row_id.'\')"  class="uploaded-remove btn btn-icon btn-danger btn-sm"><i class="far fa-trash-alt"></i></a>
               </div>';  

      $row_id=$row_id+1;

      $response = array(
        'state'  => 200,
        'message' => $error_msg,
        'result' => $html,
        'image_url' => $image_url,
        'preview_image_url' => $preview_image_url,
        'sts' => $error_sts,
        'row_id' => $row_id,
      );

      echo json_encode($response); 

    }

  public function image_resize($width=0,$height=0,$image_url='',$filename='',$upload_url=''){          
        
    $source_path = FCPATH.$image_url;
    $source_gdim ="";
    list($source_width, $source_height, $source_type) = getimagesize($source_path);
    switch ($source_type) {
    case IMAGETYPE_GIF:
        $source_gdim = imagecreatefromgif($source_path);
        break;
    case IMAGETYPE_JPEG:
        $source_gdim = imagecreatefromjpeg($source_path);
        break;
    case IMAGETYPE_PNG:
        $source_gdim = imagecreatefrompng($source_path);
        break;
    }

    $source_aspect_ratio = $source_width / $source_height;
    $desired_aspect_ratio = $width / $height;

    if ($source_aspect_ratio > $desired_aspect_ratio) {
        /*
         * Triggered when source image is wider
         */
        $temp_height = $height;
        $temp_width = ( int ) ($height * $source_aspect_ratio);
    } else {
        /*
         * Triggered otherwise (i.e. source image is similar or taller)
         */
        $temp_width = $width;
        $temp_height = ( int ) ($width / $source_aspect_ratio);
    }

    /*
     * Resize the image into a temporary GD image
     */

    $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
    imagecopyresampled(
        $temp_gdim,
        $source_gdim,
        0, 0,
        0, 0,
        $temp_width, $temp_height,
        $source_width, $source_height
    );

    /*
     * Copy cropped region from temporary image into the desired GD image
     */

    $x0 = ($temp_width - $width) / 2;
    $y0 = ($temp_height - $height) / 2;
    $x0 = intval($x0);
    $y0 = intval($y0);
    $desired_gdim = imagecreatetruecolor($width, $height);
    imagecopy(
        $desired_gdim,
        $temp_gdim,
        0, 0,
        $x0, $y0,
        $width, $height
    );

    /*
     * Render the image
     * Alternatively, you can save the image in file-system or database
     */

    $image_url =  $upload_url.$filename;    

    imagepng($desired_gdim,$image_url);

    return $image_url;

    /*
     * Add clean-up code here
     */
  }

  public function delete_image()
  {

    $image_url = $this->input->post('image_url');
    $preview_image_url = $this->input->post('preview_image_url');
    $image_urls = FCPATH.$image_url;
    $preview_image_urls = FCPATH.$preview_image_url;
    $html=0;
    if(unlink ($image_urls) && unlink ($preview_image_urls))
    {
      $html = 1;
    }
    echo json_encode(array('html'=>$html,'image_url'=>$image_url,'preview_image_url'=>$preview_image_url));

  } 


  public function create_post()
  {
    $data['user_id'] = !empty($this->session->userdata('user_id'))?$this->session->userdata('user_id'):'';
    $data['title'] = $this->input->post('title');
    $data['slug'] = $this->input->post('slug');
    $data['description'] = $this->input->post('description');
    $data['keywords'] = $this->input->post('keywords');
    $data['category'] = $this->input->post('category');
    $data['subcategory'] = $this->input->post('subcategory');
    $data['optional_url'] = $this->input->post('optional_url');
    $data['upload_image_url'] = $this->input->post('upload_image_url');
    $data['upload_preview_image_url'] = $this->input->post('upload_preview_image_url');
    $data['content'] = $this->input->post('content');
    $data['post_by'] = $this->input->post('post_by');
     if (empty($data["slug"])) {
      $data["slug"] = str_slug($data["title"]);
     }
      $data['created_date']=date('Y-m-d H:i:s');
    $result=$this->post->create_post($data);
    if($result==true)
    {
         $post_id=$this->db->insert_id();
         $tags=explode(',', $this->input->post('tags'));
         for ($i=0; $i <count($tags); $i++) { 
           $tag_data['post_id']=$post_id;
           $tag_data['tag']=$tags[$i];
           $tag_data['slug']=str_slug($tags[$i]);
           $tag_data['created_at']=date('Y-m-d H:i:s');
           $this->db->insert('tags',$tag_data);
         }

         $this->session->set_flashdata('success_message',$this->language['lg_post_added_succ']);    
         $response['status']=200;     
    }
   else
    {
        $response['msg']=$this->language['lg_post_added_fail'];
        $response['status']=500;   
    }

     echo json_encode($response); 
  }


  public function update_post()
  {
    $post_id = $this->input->post('post_id');
    $data['title'] = $this->input->post('title');
    $data['slug'] = $this->input->post('slug');
    $data['description'] = $this->input->post('description');
    $data['keywords'] = $this->input->post('keywords');
    $data['category'] = $this->input->post('category');
    $data['subcategory'] = $this->input->post('subcategory');
    $data['optional_url'] = $this->input->post('optional_url');
    $data['upload_image_url'] = $this->input->post('upload_image_url');
    $data['upload_preview_image_url'] = $this->input->post('upload_preview_image_url');
    $data['content'] = $this->input->post('content_val');
    $data['is_verified'] = 0;
     if (empty($data["slug"])) {
      $data["slug"] = str_slug($data["title"]);
     }
      
    $result=$this->post->update_post($post_id,$data);
	$result1=($this->db->affected_rows()!= 1)? false:true;
    if($result1==true)
    {
         $this->db->where('post_id',$post_id);
         $this->db->delete('tags');

         $tags=explode(',', $this->input->post('tags'));
         for ($i=0; $i <count($tags); $i++) { 
           $tag_data['post_id']=$post_id;
           $tag_data['tag']=$tags[$i];
           $tag_data['slug']=str_slug($tags[$i]);
           $tag_data['created_at']=date('Y-m-d H:i:s');
           $this->db->insert('tags',$tag_data);
         }

         $this->session->set_flashdata('success_message',$this->language['lg_post_update_suc']);    
         $response['status']=200;     
    }
   else
    {
        $response['msg']=$this->language['lg_post_update_fai'];
        $response['status']=500;   
    }

     echo json_encode($response); 
  }


  public function posts_list()
   {
      $list = $this->post->get_datatables();
      $data = array();
      $no = $_POST['start'];
      $a=1;
       
      foreach ($list as $posts) {

        $val='';
        $type=0;
        $view_status="";

        if($_POST['posts_type'] == 1){
          $type=1;
          $view_status='<span class="badge badge-danger">'.$this->language['lg_declined'].'</span>';
          if($posts['is_viewed'] == '1')
            {
              $val = 'checked';
              $view_status='<span class="badge badge-success">'.$this->language['lg_accept'].'</span>';
            }
        }
        if($_POST['posts_type'] == 2){
          $type=2;
          if($posts['is_verified'] == '1')
            {
              $val = 'checked';
              $view_status='<span class="badge badge-success">'.$this->language['lg_accept'].'</span>';
            }
        }

        $image_url=explode(',', $posts['upload_image_url']);
               
        $no++;
        $row = array();
        $row[] = $no;
        $row[] = '<img width="100" height="75" src="'.base_url().$image_url[0].'" alt="" class="rounded">';
        $row[] = $posts['title'];
        $row[] = '<span class="badge badge-primary">'.$posts['category_name'].'</span>&nbsp; &nbsp;<span class="badge badge-info">'.$posts['subcategory_name'].'</span>'; 

        if(is_admin())
        {
          
        $row[] =($posts['post_by']=='Admin')?'Admin':'<a target="_blank" href="'.base_url().'doctor-preview/'.$posts['username'].'">Dr. '.ucfirst($posts['doctor_name']).'</a>';
        $row[] = '<div class="status-toggle">
                      <input type="checkbox" onchange="change_status(\''.$posts['id'].'\',\''.$type.'\')" id="status_'.$posts['id'].'" class="check" '.$val.'>
                      <label for="status_'.$posts['id'].'" class="checktoggle">checkbox</label>
                    </div>';
        }
        else
        {
          if($_POST['posts_type'] == 1){

             $row[] = $view_status;
          }
        }

        $row[] = date('d M Y',strtotime($posts['created_date'])).'<br><small>'.date('h:i A',strtotime($posts['created_date'])).'</small>';  
        $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light" href="'.base_url().'blog/edit-post/'.base64_encode($posts['id']).'">
                    <i class="fe fe-pencil"></i> '.$this->language['lg_edit2'].'
                  </a>
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_posts('.$posts['id'].')">
                    <i class="fe fe-trash"></i> '.$this->language['lg_delete'].'
                  </a>
                </div>';          
        
        $data[] = $row;
      }



      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->post->count_all(),
              "recordsFiltered" => $this->post->count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }


  public function change_status()
    {

      $id=$this->input->post('id');
      $status=$this->input->post('status');
      $type=$this->input->post('type');
      $data ="";
      if($type==1)
      {
         $data = array('is_viewed' =>$status);
      }
      
      if($type==2)
      {
       $data = array('is_verified' =>$status);
      }
        $this->post->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
    }

     public function post_delete($id)
    {

      $this->db->where('post_id',$id);
      $this->db->delete('tags');
        $data = array(
                'status' =>0,
            );
        $this->post->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
    }

   


}
