<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends CI_Controller {

  public $data;
  public $session;
  public $timezone;
  public $input;
  public $db;
  public $product;
  public $pharmacy;
  public $lang;
  public $language;

  public function __construct() {

    parent::__construct();

    if($this->session->userdata('admin_id') ==''){
      redirect(base_url().'admin/login');
    }
        
    $this->data['theme']     = 'admin';
    $this->data['module']    = 'medical_store';
    $this->data['page']     = '';
    $this->data['base_url'] = base_url();
    $this->timezone = $this->session->userdata('time_zone');
    if(!empty($this->timezone)){
      date_default_timezone_set($this->timezone);
    }
    $lan=default_language();
    $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):strtolower($lan['language']);
    $this->data['language'] = $this->lang->load('content', $lang, true);
    $this->language = $this->lang->load('content', $lang, true);
        
    $this->load->model('services_model','services');

  }

  public function index()
  {
    $this->data['page'] = 'services';

    // $data['pharmacy_list'] = $this->db->select('*')->from('users')->where('role',5)->get()->result_array();

    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template',$data);       
  }

  
  public function add_package()
  {   

    $method = $this->input->post('method');

    $data['name']  = $this->input->post('product_name');
   // $data["product_image"] = $this->input->post('product_img');
    $data["category"] = $this->input->post('category_id');

    $data['subcategory']  = $this->input->post('sub_category_id');
    $data["unit_value"] = $this->input->post('unit_value');
    $data["unit"] = $this->input->post('unit_id');
    $data["price"] = $this->input->post('product_price');
    $data["sale_price"] = $this->input->post('sale_price');
    $data["manufactured_by"] = $this->input->post('manufatured_by');
    $data["short_description"] = $this->input->post('short_description');

    $data["discount"] = 0;
    $data["description"] = $this->input->post('product_description');

    $data['upload_image_url']=$_POST['upload_image_url'];
    $data['upload_preview_image_url']=$_POST['upload_preview_image_url'];  
    
    $data["slug"] = str_replace(' ', '', $this->input->post('product_name'));
    $phar_id ="";
    
    //sale_price
          
      if($method=='update')
      {

        $id = $this->input->post('id');


        $this->db->where('name',$data['name']);
        $this->db->where('id !=',$id);
        $this->db->where('status',1);
        $query = $this->db->get('products');
      
        if ($query->num_rows() > 0)
        {
          $datas['result'] = 'exe';
          $datas['status'] = 'Product name already exits!';
        }
        else
        {
          $this->db->where('id',$id);
          $this->db->update('products', $data);
          $result = ($this->db->affected_rows()!= 1) ? false:true;

          $datas['result'] = 'true';
          $datas['status'] = 'Product updated successfully';

        }       
      }
      else
      {
       
        $get_pharmacy_details = $this->db->select('id')->from('users')->where('pharmacy_user_type',1)->get()->row_array();
            
        if(!empty($get_pharmacy_details)){
          $phar_id = $get_pharmacy_details['id'];
        }

        $data["user_id"] = $phar_id;

        $this->db->where('name',$data['name']);
        $this->db->where('status',1);
        $query = $this->db->get('products');
      
        if ($query->num_rows() > 0)
        {

          $datas['result'] = 'exe';
          $datas['status'] = 'Product name already exits!';
        }
        else
        {
            
            $this->db->insert('products',$data);
          

          $result=($this->db->affected_rows()!= 1)? false:true;

          if(@$result==true) 
           {
              $datas['result'] = 'true';
              $datas['status'] = 'Product added successfully';
           }  
           else
           {
              $datas['result'] = 'false';
              $datas['status'] = 'Product added failed!';
           }
         }  
      }       

      //exit;        
        echo json_encode($datas);
    }
    
    public function product_list()
    {


      $list = $this->product->get_datatables();
      $data = array();
      $no = $_POST['start'];
      $a=1;
       
      foreach ($list as $products) 
      {
      
      $pr_img = explode(",",$products['upload_image_url']);
      if(file_exists(FCPATH.$pr_img[0])){
        $pimage = $pr_img[0];
      } else {
         $pimage = 'assets/img/no-image.png';
      }

        $no++;
        $row = array();
        $row[] = $no;
        $row[] = '<h2 class="table-avatar">
                      <a href="javascript:void(0);" class="avatar avatar-sm mr-2">
                        <img class="avatar-img" src="'.base_url().$pimage.'" alt="Products">
                      </a>
                      <a href="javascript:void(0);">'.$products['name'].'</a>
                   </h2>';
        $row[] = '<div class="actions">
                    <a class="btn btn-sm bg-success-light" onclick="edit_product_admin('.$products['id'].')" href="javascript:void(0)">
                      <i class="fe fe-pencil"></i> Edit
                    </a>
                    <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_product_admin('.$products['id'].')">
                      <i class="fe fe-trash"></i> Delete
                    </a>
                  </div>';
        
        $data[] = $row;
      }

      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->product->count_all(),
              "recordsFiltered" => $this->product->count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }

  public function edit_product_admin($id)
  {


    $data = $this->product->get_by_id_with_pharmacy($id);
    //echo $this->db->last_query(); 
    

    //$data = $this->product->get_by_id($id);
    echo json_encode($data);

  }

  public function delete_product_admin($id)
  {
    $data = array(
            'status' =>0,
        );
    $this->product->update(array('id' => $id), $data);
    echo json_encode(array("status" => TRUE));
  }

  public function change_status(){

    $id = $this->input->post('id');
    $status = $this->input->post('status');
    $data = array(
            'status' =>$status,
        );
    $this->db->where('id',$id);
    $this->db->update('products', $data);
    echo json_encode(array("status" => TRUE));
  }

  public function delete_image()
  {


    $image_url = $this->input->post('image_url');
    $preview_image_url = $this->input->post('preview_image_url');
    $image_urls = FCPATH.$image_url;
    $preview_image_urls = FCPATH.$preview_image_url;
     $html =0;
     if($image_url !='' && file_exists($image_urls) && $preview_image_url !='' && file_exists($preview_image_urls)) { 
      if(unlink ($image_urls) && unlink ($preview_image_urls))
      {
        $html = 1;
      }
    }
    echo json_encode(array('html'=>$html,'image_url'=>$image_url,'preview_image_url'=>$preview_image_url));

  } 


  public function image_upload() 

    { 
      /** @var string $max_execution_time */
      $max_execution_time=3000;
      ini_set('max_execution_time', $max_execution_time); 

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

      $image_url = $this->image_resize(150,150,$source_image,'150x150_'.$file_name_final,$upload_url);

      $preview_image_url = $this->image_resize(450,300,$source_image,'450x300_'.$file_name_final,$upload_url);

      
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
    list($source_width, $source_height, $source_type) = getimagesize($source_path);
    $source_gdim ="";
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

}

  