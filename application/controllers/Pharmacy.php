<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Pharmacy extends CI_Controller {

    public $data;
    public $session;
    public $timezone;
    public $lang;
    public $language;
    public $pharmacy;
    public $input;
    public $db;
    public $uri;
    public $upload;
    public $zip;
    public $my_patients;
    public $orders;

  public function __construct() {

        parent::__construct();

        if($this->session->userdata('user_id') ==''){
            redirect(base_url().'signin');
        }

        $this->data['theme']     = 'web';
        $this->data['module']    = 'pharmacy';
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
        $this->load->model('pharmacy_model','pharmacy');
        $this->load->model('my_patients_model','my_patients');
        $this->load->model('Orders_model','orders');

    }

    public function product_list()
    {
     
        if($this->session->userdata('role')=='5'){
          $this->data['page'] = 'product_list';
          $this->load->vars($this->data);
          $this->load->view($this->data['theme'].'/template');
        }
        else
        {
          redirect(base_url().'dashboard');
        }
    }


  public function new_product_list()
  {
    $list = $this->pharmacy->get_products();
    $data = array();
    $no = $_POST['start'];
    $a=1;
     
    foreach ($list as $products) {

      $no++;
      $row = array();
      $row[] = $a++;
      $row[] = '<h2 class="table-avatar">
                    <a href="javascript:void(0);" class="avatar avatar-sm mr-2">
                      <img class="avatar-img" src="'.base_url().$products['product_image'].'" alt="Speciality">
                    </a>
                    <a href="javascript:void(0);">'.decrypt($products['product_name']).'</a>
                 </h2>';
      $row[] = '<h2 class="table-avatar">
                    '.$products['product_stock'].'
                 </h2>';
      $row[] = '<h2 class="table-avatar">
                    '.$products['product_price'].'
                 </h2>';
      if($products['status'] == 1){
        $row[] = '<h2 class="actions">
                    <a class="btn btn-sm bg-success-light" onclick="status_product('.$products['id'].')" href="javascript:void(0)">
                      <i class="fe fe-check"></i> '.$this->language['lg_active'].'
                    </a>
                  </h2>';
      }else{
        $row[] = '<h2 class="actions">
                    <a class="btn btn-sm bg-danger-light" onclick="status_product('.$products['id'].')" href="javascript:void(0)">
                      <i class="fe fe-exclamation-triangle"></i> '.$this->language['lg_inactive'].'
                    </a>
                  </h2>';
      }

      $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light" onclick="edit_product('.$products['id'].')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> '.$this->language['lg_edit2'].'
                  </a>
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_product('.$products['id'].')">
                    <i class="fe fe-trash"></i> '.$this->language['lg_delete'].'
                  </a>
                </div>';
      
      $data[] = $row;
    }



    $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data,
        );
    //output to json format
    echo json_encode($output);
  }


    public function add_product()
    {
        if($this->session->userdata('role')=='5'){
          $this->data['page'] = 'add_product';
          $this->load->vars($this->data);
          $this->load->view($this->data['theme'].'/template');
        }
        else
        {
          redirect(base_url().'dashboard');
        }
    }
	 public function check_product_exists()
    {
		$product= trim($this->input->post('name'));
		$user_id = $this->session->userdata('user_id');
		$this->db->select('name');
		$this->db->from('products');
		$this->db->where('user_id',$user_id);
		$this->db->like('name',$product,'right');
		$this->db->get()->result(); 
		//echo $this->db->last_query();
		$affected=$this->db->affected_rows();
		if($affected>0) { echo 'false'; } else{  echo 'true';}  
		
    }

    public function edit_product($product_id)
    {
        if($this->session->userdata('role')=='5'){
          $this->data['page'] = 'edit_product';
          $this->data['products']=$this->pharmacy->get_products(base64_decode($product_id));
          $this->load->vars($this->data);
          $this->load->view($this->data['theme'].'/template');
        }
        else
        {
          redirect(base_url().'dashboard');
        }
    }



    public function image_upload() 

    { 
      /** @var string $max_execution_time */
      $max_execution_time=3000;
      ini_set('max_execution_time',$max_execution_time ); 

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

	public function image_resize($width = 0, $height = 0, $image_url = '', $filename = '', $upload_url = '')
{          
    // Construct the source path
    $source_path = FCPATH . $image_url;

    // Get image dimensions and type
    list($source_width, $source_height, $source_type) = getimagesize($source_path);

    // Initialize source image resource
    $source_gdim = "";
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
        default:
            // Handle unsupported image types or errors
            return false;
    }

    // Calculate aspect ratios
    $source_aspect_ratio = $source_width / $source_height;
    $desired_aspect_ratio = $width / $height;

    // Determine new dimensions
    if ($source_aspect_ratio > $desired_aspect_ratio) {
        // Source image is wider
        $temp_height = $height;
        $temp_width = (int) ($height * $source_aspect_ratio);
    } else {
        // Source image is taller or similar
        $temp_width = $width;
        $temp_height = (int) ($width / $source_aspect_ratio);
    }

    // Create temporary GD image for resizing
    $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
    imagecopyresampled(
        $temp_gdim,         // Destination image resource
        $source_gdim,       // Source image resource
        0, 0,               // Destination x, y coordinates
        0, 0,               // Source x, y coordinates
        $temp_width,        // Destination width
        $temp_height,       // Destination height
        $source_width,      // Source width
        $source_height      // Source height
    );

    // Create final resized image with desired dimensions
    $x0 = ($temp_width - $width) / 2;
    $y0 = ($temp_height - $height) / 2;
    $x0 = intval($x0);
    $y0 = intval($y0);
    $desired_gdim = imagecreatetruecolor($width, $height);
    imagecopy(
        $desired_gdim,      // Destination image resource
        $temp_gdim,         // Source image resource
        0, 0,               // Destination x, y coordinates
        $x0, $y0,           // Source x, y coordinates
        $width,             // Destination width
        $height             // Destination height
    );

    // Save or output the resized image
    $image_path = $upload_url . $filename;
    imagepng($desired_gdim, $image_path); // Save as PNG

    // Free up memory
    imagedestroy($source_gdim);
    imagedestroy($temp_gdim);
    imagedestroy($desired_gdim);

    return $image_path; // Return the saved image path
}


  public function image_resizee($width=0,$height=0,$image_url='',$filename='',$upload_url=''){          
        
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
	//echo $image_url;

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
    $html =0;
    if(unlink ($image_urls) && unlink ($preview_image_urls))
    {
      $html = 1;
    }
    echo json_encode(array('html'=>$html,'image_url'=>$image_url,'preview_image_url'=>$preview_image_url));

  } 


public function create_product()
{
  
            $data['user_id'] = $this->session->userdata('user_id');
            $data['name'] = $this->input->post('name');
            $data["slug"] = str_slug($data["name"]);
            $data['category'] = $this->input->post('category');
            $data['subcategory'] = $this->input->post('subcategory');
            $data['unit_value'] = $this->input->post('unit_value');
            $data['unit'] = $this->input->post('unit');
            $data['price'] = $this->input->post('price');
            $data['sale_price'] = $this->input->post('sale_price');
            $data['discount'] = $this->input->post('discount');
            $data['description'] = $this->input->post('description');
            
            $data['manufactured_by'] = $this->input->post('manufactured_by');
            $data['short_description'] = $this->input->post('short_description');

            $data['upload_image_url'] = $this->input->post('upload_image_url');
            $data['upload_preview_image_url'] = $this->input->post('upload_preview_image_url');
            $data['created_date']=date('Y-m-d H:i:s');

            // check if drug name is already added in products
            $this->_checkAndAddProductName($data['name'], $this->input->post('upload_image_url'));

            $result=$this->pharmacy->create_product($data);

            if($result==true)
            {
              

                 $this->session->set_flashdata('success_message',$this->language['lg_product_added_s']);    
                 $response['status']=200;  
                
            }
           else
            {
              

                $response['msg']=$this->language['lg_product_added_f'];
                $response['status']=500;   
            }

             echo json_encode($response); 
}

  public function _checkAndAddProductName($productName, $image)
  { 
    $this->load->model('my_patients_model','my_patients');

    // check drug name is available in products
    $getProduct = $this->my_patients->get_pharm_products($productName);

    if($getProduct)
    {
      if($getProduct[0]['status'] == 0)
      {
        $this->db->where('id', $getProduct[0]['id']);
        $this->db->update('pharmacy_products', array('status' => 1, 'product_image' => $image));
      }
    }
    else
    {
      $data['product_name']  = $productName;
      $data['status']        = 1;
      $data['product_image'] = $image; 
      $this->db->insert('pharmacy_products', $data);
    }
  }


public function update_product()
{
            $product_id = $this->input->post('product_id');
            $data['name'] = $this->input->post('name');
            $data["slug"] = str_slug($data["name"]);
            $data['category'] = $this->input->post('category');
            $data['subcategory'] = $this->input->post('subcategory');
            $data['unit_value'] = $this->input->post('unit_value');
            $data['unit'] = $this->input->post('unit');
            $data['price'] = $this->input->post('price');
            $data['sale_price'] = $this->input->post('sale_price');
            $data['discount'] = $this->input->post('discount');
            $data['description'] = $this->input->post('description');
            $data['upload_image_url'] = $this->input->post('upload_image_url');
            $data['upload_preview_image_url'] = $this->input->post('upload_preview_image_url');
               $data['manufactured_by'] = $this->input->post('manufactured_by');
            $data['short_description'] = $this->input->post('short_description');

            $result=$this->pharmacy->update_product($product_id,$data);

            if($result==true)
            {
                 
                 $this->session->set_flashdata('success_message',$this->language['lg_product_updated']);    
                 $response['status']=200;     
            }
           else
            {
                $response['msg']=$this->language['lg_product_updated1'];
                $response['status']=500;   
            }

             echo json_encode($response); 
}

  public function orders_list()
  {
      $this->data['page'] = 'orderlist';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
  }


  // public function orders_listDatatable()
  //  {
      
  //     $list = $this->orders->get_datatables();
      
  //     if($this->session->userdata('role')=='5'){
  //       $this->db->where('pharmacy_id',$this->session->userdata('user_id'))->update('orders',array('pharmacy_notify'=>1));
  //     }else{
  //       $this->db->where('user_id',$this->session->userdata('user_id'))->update('orders',array('user_notify'=>1));
  //     } 

  //     $data = array();
  //     $no = $_POST['start'];
  //     
       
  //     foreach ($list as $products) {

  //           $created_at = date("Y-m-d",strtotime($products['created_at']));

  //       $val='';

  //     if($products['status'] == '1')
  //       {
  //         $val = 'checked';
  //       }
               
  //       $no++;
  //       $row = array();
  //       $row[] = $a++;
  //       $row[] = $products['order_id'];

  //       if($this->session->userdata('role')=='5'){

  //       $row[] = $products['full_name'];

  //       }else{

  //         $row[] = $products['pharmacy_name'];

  //       }

  //       $row[] = $products['qty'];
  //       $row[] = currency_code_sign($products['product_currency']).$products['subtotal'];
  //       $row[] = $products['payment_type'];

  //       if($products['order_status']== 'pending'){
  //         $status = '<span class="badge badge-primary">'.$this->language['lg_order_placed'].'</span>';
  //       }
  //       if($products['order_status']== 'shipped'){
  //         $status = '<span class="badge badge-warning">'.$this->language['lg_shipped'].'</span>';
  //       }
  //       if($products['order_status']== 'completed'){
  //         $status = '<span class="badge badge-success">'.$this->language['lg_delivered'].'</span>';
  //       }

  //       $row[] = $status;

  //       if($this->session->userdata('role')=='5'){
  //         $id = $products['id'];

  //         $shipped ='';
  //         $completed ='';
  //         if($products['order_status']== 'shipped'){
  //           $shipped ='selected';
  //         }
  //         if($products['order_status']== 'completed'){
  //           $completed ='selected';
  //         }

  //         $row[] ='<div class="actions">
  //                   <select name="order_status" class="form-control order_status" id="'.$id.'">
  //                     <option value="" >'.$this->language['lg_change_status'].'</option>
  //                     <option value="shipped" '.$shipped.'>'.$this->language['lg_shipped'].'</option>
  //                     <option value="completed" '.$completed.'>'.$this->language['lg_delivered'].'</option>
  //                   </select>
  //                 </div>';
  //       }

  //       $row[] = $created_at;
                  
  //       $data[] = $row;
  //     }



  //     $output = array(
  //             "draw" => $_POST['draw'],
  //             "recordsTotal" => $this->orders->count_all(),
  //             "recordsFiltered" => $this->orders->count_filtered(),
  //             "data" => $data,
  //         );
  //     //output to json format
  //     echo json_encode($output);
  // }

  public function orders_listDatatable()
  {
     
     $list = $this->orders->get_datatables();
     
     if($this->session->userdata('role')=='5'){
       $this->db->where('pharmacy_id',$this->session->userdata('user_id'))->update('orders',array('pharmacy_notify'=>1));
     }else{
       $this->db->where('user_id',$this->session->userdata('user_id'))->update('orders',array('user_notify'=>1));
     } 

     $data = array();
     $no = $_POST['start'];
     $a=$no+1;
      
     foreach ($list as $products) { 
      //print_r($products);

      $created_at = date("Y-m-d",strtotime($products['created_at']));

       $val='';

     if($products['status'] == '1')
       {
         $val = 'checked';
       }
              
       $no++;
       $row = array();
       $row[] = $a++;
       $row[] = '<a href="'.base_url().'orders-details/'.base64_encode($products['order_id']).'">'.$products['order_id'].'</a>';

       if($this->session->userdata('role')=='5'){

       $row[] = $products['full_name'];

       }
       else{

         $row[] = $products['pharmacy_name'];

       }

       $user_currency=get_user_currency();
      $user_currency_code=$user_currency['user_currency_code'];
      $user_currency_rate=$user_currency['user_currency_rate'];
      $rate_symbol = currency_code_sign($products['pharmacy_currency']);

       $row[] = $products['qty'];
       //$row[] = convert_to_user_currency($products['subtotal'], $products['currency_code']);
       $row[] = $rate_symbol.get_doccure_currency($products['subtotal'], $products['pharmacy_currency'], $user_currency['user_currency_code']); 
       $row[] = $products['payment_type'];
       $status ="";

       if($products['order_status']== 'pending'){
         $status = '<span class="badge badge-primary">'.$this->language['lg_order_placed'].'</span>';
       }
       if($products['order_status']== 'shipped'){
         $status = '<span class="badge badge-warning">'.$this->language['lg_shipped'].'</span>';
       }
       if($products['order_status']== 'completed'){
         $status = '<span class="badge badge-success">'.$this->language['lg_delivered'].'</span>';
       }

       $row[] = $status;

       if($this->session->userdata('role')=='5'){
         $id = $products['order_id'];

         $shipped ='';
         $completed ='';
         if($products['order_status']== 'shipped'){
           $shipped ='selected';
         }
         if($products['order_status']== 'completed'){
           $completed ='selected';
         }

         $row[] ='<div class="actions">
                   <select name="order_status" class="form-control order_status" id="'.$products['id'].'">
                     <option value="" >'.$this->language['lg_change_status'].'</option>
                     <option value="shipped" '.$shipped.'>'.$this->language['lg_shipped'].'</option>
                     <option value="completed" '.$completed.'>'.$this->language['lg_delivered'].'</option>
                   </select>
                 </div>';
       }
       $invoice = '  <a href="'.base_url().'invoice-products-view/'.base64_encode($products['order_id']).'"  class="btn btn-sm bg-info-light ">
       View
       </a>
       <a href="'.base_url().'invoice-products-print/'.base64_encode($products['order_id']).'" class="btn btn-sm bg-primary-light " target="blank">
       Print
       </a>';

       $row[] = date('d M Y',strtotime($created_at));
       $row[] = $invoice;
                 
       $data[] = $row;
     }



     $output = array(
             "draw" => $_POST['draw'],
             "recordsTotal" => $this->orders->count_filtered(),
             "recordsFiltered" => $this->orders->count_filtered(),
             "data" => $data,
         );
     //output to json format
     echo json_encode($output);
 }

 public function orders_details($orderId)
 {
     $list = $this->orders->get_products_datatables($orderId);    
     $this->data['product_details'] = $list;
     $this->data['page'] = 'orderdetails';
     $this->load->vars($this->data);
     $this->load->view($this->data['theme'].'/template');
 }

  public function change_order_status(){

    $id = $this->input->post('id');
    $status = $this->input->post('status');

    $this->db->where('id',$id)->update('orders',array('order_status'=>$status,'user_notify'=>0,'pharmacy_notify'=>0));
    echo 'success';
  }

 public function products_list()
   {
      $list = $this->pharmacy->get_datatables();
      $data = array();
      $no = $_POST['start'];
      $a=1;
       
      foreach ($list as $products) {

        $val='';

      if($products['status'] == '1')
        {
          $val = 'checked';
        }
        

        $image_url=explode(',', $products['upload_image_url']);
        if(file_exists(FCPATH.$image_url[0])){
        $pimage = $image_url[0];
      } else {
         $pimage = 'assets/img/no-image.png';
      }
               
        $no++;
        $row = array();
        $row[] = $a++;

        $row[] = '<img width="100" height="75" src="'.base_url().$pimage.'" alt="" class="rounded">';
       
        $row[] = $products['name'];
        
        $row[] = '<span class="badge badge-primary">'.$products['category_name'].'</span>'; 
        
        $row[] = '<span class="badge badge-info">'.$products['subcategory_name'].'</span>'; 
       
        $row[] = $products['unit_value'].' '.$products['unit_name'];

        $row[] = date('d M Y',strtotime($products['created_date'])).'<br><small>'.date('h:i A',strtotime($products['created_date'])).'</small>'; 
        $row[] = '<div class="status-toggle">
                      <input type="checkbox" id="'.$products['id'].'" class="check product_status" '.$val.'>
                      <label for="'.$products['id'].'" class="checktoggle">checkbox</label>
                    </div>'; 
        $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light" href="'.base_url().'edit-product/'.base64_encode($products['id']).'">
                    <i class="fe fe-pencil"></i> '.$this->language['lg_edit2'].'
                  </a>
                  <a class="btn btn-sm bg-danger-light product_delete" id="delete'.$products['id'].'" href="javascript:void(0)">
                    <i class="fe fe-trash"></i> '.$this->language['lg_delete'].'
                  </a>
                </div>';          
        
        $data[] = $row;
      }



      $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->pharmacy->count_all(),
              "recordsFiltered" => $this->pharmacy->count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
  }


  public function change_status()
    {

      $id=$this->input->post('id');
      $status=$this->input->post('status');
      
      $data = array('status' =>$status);
    
        $this->pharmacy->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
    }

     public function product_delete($id)
    {

        $data = array(
                'status' =>0,
            );
        $this->pharmacy->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
    }
    
    public function pharmacy_quotation() {
            $this->data['page'] = 'pharmacy_quotation';
            $this->load->vars($this->data);
            $this->load->view($this->data['theme'].'/template');
    }
    
    public function new_quotation_list() {
        $list = $this->pharmacy->get_newquotations_datatables();
        $data = array();
        $no = $_POST['start'];
        $a=1;
        $sno = 1;
        foreach ($list as $quotations) {
            
            $profile_image=(!empty($quotations['profileimage']))?base_url().$quotations['profileimage']:base_url().'assets/img/user.png';
            $no++;
            
            $row = array();
            $row[] = $sno;
            $row[] = '<h2 class="table-avatar">
                  <a href="javascript:void(0);" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="'.$profile_image.'" alt="User Image">
                  </a>
                  <a href="javascript:void(0);">'.ucfirst($quotations['patient_name']).'</a>
                </h2>
                  ';
            
            $row[] = date('d-m-Y' , strtotime($quotations['quotation_request_date']));
            
            $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light prepare_quotation_modal_clk" href="javascript:void(0);" data-patient_quotation_id="'.$quotations['id'].'" data-toggle="modal" data-target="#quotation_modal">'.$this->language['lg_prepare_quotation'].'</a>
                  
                </div>';
            $data[] = $row;
            $sno++;
        }
            $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->pharmacy->newquotations_count_all(),
              "recordsFiltered" => $this->pharmacy->newquotations_count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
            
        
    }
    
    public function waiting_quotation_list(){
        $list = $this->pharmacy->get_waitingquotations_datatables();
        $data = array();
        $no = $_POST['start'];
        $a=1;
        $sno = 1;
        foreach ($list as $quotations) {
            
            $profile_image=(!empty($quotations['profileimage']))?base_url().$quotations['profileimage']:base_url().'assets/img/user.png';
            $no++;
            
            $row = array();
            $row[] = $sno;
            $row[] = '<h2 class="table-avatar">
                  <a href="javascript:void(0);" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="'.$profile_image.'" alt="User Image">
                  </a>
                  <a href="javascript:void(0);">'.ucfirst($quotations['patient_name']).'</a>
                </h2>
                  ';
            
            $row[] = date('d-m-Y' , strtotime($quotations['quotation_request_date']));
            
            $row[] = '<div class="actions">
                  
                  <a class="btn btn-sm bg-success-light" href="javascript:void(0)">'.$this->language['lg_waiting3'].'</a>
                </div>';
            $data[] = $row;
            $sno++;
        }
            $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->pharmacy->waitingquotations_count_all(),
              "recordsFiltered" => $this->pharmacy->waitingquotations_count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
            
    }
    
    
    public function completed_quotation_list(){
        $list = $this->pharmacy->get_completedquotations_datatables();
        $data = array();
        $no = $_POST['start'];
        $a=1;
        $sno = 1;
        foreach ($list as $quotations) {
            
            $profile_image=(!empty($quotations['profileimage']))?base_url().$quotations['profileimage']:base_url().'assets/img/user.png';
            $no++;
            
            $row = array();
            $row[] = $sno;
            $row[] = '<h2 class="table-avatar">
                  <a href="javascript:void(0);" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="'.$profile_image.'" alt="User Image">
                  </a>
                  <a href="javascript:void(0);">'.ucfirst($quotations['patient_name']).'</a>
                </h2>
                  ';
            
            $row[] = date('d-m-Y' , strtotime($quotations['quotation_request_date']));
            
            $row[] = '<div class="actions">
                  
                  <a class="btn btn-sm bg-success-light" href="javascript:void(0)">'.$this->language['lg_completed'].'</a>
                </div>';
            $data[] = $row;
            $sno++;
        }
            $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->pharmacy->completedquotations_count_all(),
              "recordsFiltered" => $this->pharmacy->completedquotations_count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
            
    }
    
    public function cancelled_quotation_list() {
        
        $list = $this->pharmacy->get_cancelledquotations_datatables();
        $data = array();
        $no = $_POST['start'];
        $a=1;
        $sno = 1;
        foreach ($list as $quotations) {
            
            $profile_image=(!empty($quotations['profileimage']))?base_url().$quotations['profileimage']:base_url().'assets/img/user.png';
            $no++;
            
            $row = array();
            $row[] = $sno;
            $row[] = '<h2 class="table-avatar">
                  <a href="javascript:void(0);" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="'.$profile_image.'" alt="User Image">
                  </a>
                  <a href="javascript:void(0);">'.ucfirst($quotations['patient_name']).'</a>
                </h2>
                  ';
            
            $row[] = date('d-m-Y' , strtotime($quotations['quotation_request_date']));
            
            $row[] = '<div class="actions">
                  
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)">'.$this->language['lg_cancelled'].'</a>
                </div>';
            $data[] = $row;
            $sno++;
        }
            $output = array(
              "draw" => $_POST['draw'],
              "recordsTotal" => $this->pharmacy->cancelledquotations_count_all(),
              "recordsFiltered" => $this->pharmacy->cancelledquotations_count_filtered(),
              "data" => $data,
          );
      //output to json format
      echo json_encode($output);
            
    }
    
    public function get_pre_quotation_details() {
        
        $quotation_id = $this->input->post('quotation_id');
        $pharmacy_id = $this->session->userdata('user_id');
        $quotation_details = $data['quotation_details'] = $this->pharmacy->get_patient_request_quotation_details($quotation_id);
        //echo "<pre>"; print_r($quotation_details); exit;
        if(!empty($quotation_details)) {
            $prescription_id = $quotation_details['prescription_id'];
            $patient_id = $quotation_details['patient_id'];
            $data['prescription_item_details'] = $prescription_item_details = $this->my_patients->get_prescription_details($prescription_id);
            $response['msg'] = $this->language['lg_quotation_detai'];
            $response['status']=200;
            $response['data'] = $data;
        } else {
            $response['msg'] = $this->language['lg_quotation_detai1'];
            $response['status']=500;
            $response['data'] = $data;
        }
        
        echo json_encode($response);
        
    }
    
    public function save_quotation() {
        $pharmacy_id = $this->session->userdata('user_id');
        $patient_id = $this->input->post('patient_id');
        $patient_req_quo_id = $this->input->post('paitent_req_quotation_id');
        $prescription_id = $this->input->post('prescription_id');
        $prescription_amount = $this->input->post('prescription_amount');
        $total_amount = $this->input->post('amount');
        $tax_amount = 0;
        // Get Prescription Item Details..
        $item_details = $this->my_patients->get_prescription_details($prescription_id);
        $state="";

        if(!empty($item_details)) {
            foreach ($item_details as $item_val) {
                $item_id = $item_val['id'];
                $quotation_data = array(
                    'patient_req_quotation_id' => $patient_req_quo_id,
                    'pharmacy_id' => $pharmacy_id,
                    'patient_id' => $patient_id,
                    'prescription_id' => $prescription_id,
                    'prescription_item_id' => $item_id,
                    'amount' => $prescription_amount[$item_id],
                    'tax_amount' => $tax_amount,
                    'total_amount' => $total_amount,
                    'created_date' => date('Y-m-d H:i:s')
                );
                
                $this->db->insert('quotation',$quotation_data);
                $state = ($this->db->affected_rows()!= 1)? false:true; 
            }
            if($state){
                // Change the quotation request status..
                $update_data = array(
                    'quotation_request_status' => 'waiting'
                );
                $this->db->where('id', $patient_req_quo_id);
                $this->db->update('patient_request_quotation',$update_data);
                //$response['msg'] = "Quotation successfully sent. Please wait for patient reply.";
                $response['msg'] = $this->language['lg_quotation_sent_success'];
                $response['status']=200;
            } else {
                //$response['msg'] = "Failed to send a quotation.";
                $response['msg'] = $this->language['lg_quotation_sent_fail'];
                $response['status']=500;
            }
        } else {
                //$response['msg'] = "Failed to send a quotation.";
                $response['msg'] = $this->language['lg_quotation_sent_fail'];
                $response['status']=500;
        }
        echo json_encode($response);
    }
    
    public function pharmacy_quotation_archive_details($pharmacy_id) {
        $encrypted_pharmacy_id = $pharmacy_id;
        $decrypted_pharmacy_id = base64_decode($pharmacy_id);
        $pharmacy_name = get_pharmacy_name($decrypted_pharmacy_id);
        $pharmacy_path = "uploads/archives/".$decrypted_pharmacy_id;
        $date = date('Y-m-d');
        $dtmnt = date('F Y', strtotime($date));
        $archive_date_path = $pharmacy_path.'/'.$dtmnt;
        
        if(!is_dir('uploads/archives')){
            mkdir('uploads/archives');         
        }
        if(!is_dir($pharmacy_path)){
            mkdir($pharmacy_path);         
        }
        if(!is_dir($archive_date_path)){
            mkdir($archive_date_path);         
        }
        $new_quotation['new_quotation_details'] = $this->pharmacy->pharmacy_new_quotation_details($decrypted_pharmacy_id);
        $wating_quotation['waiting_quotation_details'] = $this->pharmacy->pharmacy_waiting_quotation_details($decrypted_pharmacy_id);
        $completed_quotation['completed_quotation_details'] = $this->pharmacy->pharmacy_completed_quotation_details($decrypted_pharmacy_id);
        $cancelled_quotation['cancelled_quotation_details'] = $this->pharmacy->pharmacy_cancelled_quotation_details($decrypted_pharmacy_id);
        $new_quotation['pharmacy_name'] = $wating_quotation['pharmacy_name'] = $completed_quotation['pharmacy_name'] = $cancelled_quotation['pharmacy_name'] =  $pharmacy_name['pharmacy_name'];
        $new_quotation['path'] = $wating_quotation['path'] = $completed_quotation['path'] = $cancelled_quotation['path'] = $archive_date_path.'/';
        $this->load->view('web/modules/print/print_pharmacy_new_quotation',$new_quotation);
        $this->load->view('web/modules/print/print_pharmacy_waiting_quotation',$wating_quotation);
        $this->load->view('web/modules/print/print_pharmacy_completed_quotation',$completed_quotation);
        $this->load->view('web/modules/print/print_pharmacy_cancelled_quotation',$cancelled_quotation);
        
        // create zip file and download..
        $this->load->library('zip');
        $filename = trim($pharmacy_name['pharmacy_name']).strtotime(date('Y-m-d h:i:s')).".zip";
        $this->zip->read_dir($archive_date_path);
        // Save the zip file to archivefiles directory
        $this->zip->archive(base_url().'uploads/zip/'.$filename);
        // Download
        if($this->zip->download($filename)){
            $this->session->set_flashdata('success_message',$this->language['lg_zip_success']);
            redirect(base_url().'quotation-list');
        }  else {
            $this->session->set_flashdata('error_message',$this->language['lg_zip_fail']);
            redirect(base_url().'quotation-list');
        }
        
        
    }

}