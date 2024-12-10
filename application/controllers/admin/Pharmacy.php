<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pharmacy extends CI_Controller {

  public $data;
  public $session;
  public $timezone;
  public $input;
  public $upload;
  public $db;
  public $pharmacy;
  public $order;

  public function __construct() {

    parent::__construct();

    if($this->session->userdata('admin_id') ==''){
      redirect(base_url().'admin/login');
    }
        
    $this->data['theme']     = 'admin';
    $this->data['module']    = 'pharmacy';
    $this->data['page']     = '';
    $this->data['base_url'] = base_url();
    $this->timezone = $this->session->userdata('time_zone');
    if(!empty($this->timezone)){
      date_default_timezone_set($this->timezone);
    }
    $this->load->model('pharmacy_model','pharmacy');
    $this->load->model('order_model','order');     

   }

  public function index()
  {

    
    $this->data['page'] = 'index';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');       
  }

  public function product_list()
  {
    

    $list = $this->pharmacy->get_product();
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
                    <a href="javascript:void(0);">'.($products['product_name']).'</a>
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
                      <i class="fe fe-check"></i> Active
                    </a>
                  </h2>';
      }else{
        $row[] = '<h2 class="actions">
                    <a class="btn btn-sm bg-danger-light" onclick="status_product('.$products['id'].')" href="javascript:void(0)">
                      <i class="fe fe-exclamation-triangle"></i> InActive
                    </a>
                  </h2>';
      }

      $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light" onclick="edit_product('.$products['id'].')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_product('.$products['id'].')">
                    <i class="fe fe-trash"></i> Delete
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

  public function bulk_upload(){

    //upload folder path defined here
    $config['upload_path'] =  './uploads/';
    //Only allow this type of extensions 
    $config['allowed_types'] = 'xlsx|csv';

    $this->load->library('upload', $config);

    if(!$this->upload->do_upload('products'))
    {

      $error = array('error' => $this->upload->display_errors());

      $this->session->set_flashdata('tokbox_error', $error['error']);
      redirect(base_url().'admin/pharmacy');

    }else{

      $upload_data = $this->upload->data();
      $file_name = $upload_data['file_name'];

      $this->load->library("Excel");

      $objReader = PHPExcel_IOFactory::createReader('Excel2007');
      $objReader->setReadDataOnly(true);

      $objPHPExcel = $objReader->load('uploads/'.$file_name);
      $sheetnumber = 0;
      $log_msg = '';

      foreach ($objPHPExcel->getWorksheetIterator() as $sheet)
      {

        $s = $sheet->getTitle();    // get the sheet name 

        $sheet= str_replace(' ', '', $s); // remove the spaces between sheet name 
        $sheet= strtolower($sheet); 
        $objWorksheet = $objPHPExcel->getSheetByName($s);

        $lastRow = $objPHPExcel->setActiveSheetIndex($sheetnumber)->getHighestRow(); 
        $sheetnumber++;
        //loop from first data until last data

        $data = array();

        for($j=2; $j<=$lastRow; $j++)
        {

          $n = $j-2;
          $k = $j-1;
          $product_name = $objWorksheet->getCellByColumnAndRow(0,$j)->getValue();
          $category_id = $objWorksheet->getCellByColumnAndRow(1,$j)->getValue();
          $sub_category_id = $objWorksheet->getCellByColumnAndRow(2,$j)->getValue();
          $product_stock = $objWorksheet->getCellByColumnAndRow(3,$j)->getValue();
          $unit_value = $objWorksheet->getCellByColumnAndRow(4,$j)->getValue();
          $unit_id = $objWorksheet->getCellByColumnAndRow(5,$j)->getValue();
          $product_price = $objWorksheet->getCellByColumnAndRow(6,$j)->getValue();
          $discount_value = $objWorksheet->getCellByColumnAndRow(7,$j)->getValue();
          $discount_type = $objWorksheet->getCellByColumnAndRow(8,$j)->getValue();
          $product_description = $objWorksheet->getCellByColumnAndRow(9,$j)->getValue();

          if($product_name !='' && $category_id !='' && $sub_category_id !='' && $product_stock !='' && $unit_value !='' && $unit_id !='' && $product_price !='' && $product_description !=''){

            if($discount_type == ''){
              $discount_type = 'flat'; 
            }

            $image = 'uploads/products/no_product_photo.jpg';

            $data[$n] = array(
                       'product_name' => ($product_name),
                       'slug' => (str_slug($product_name)),
                       'product_image' => $image,
                       'category_id' => $category_id,
                       'sub_category_id' => $sub_category_id,
                       'product_stock' => $product_stock,
                       'unit_value' => $unit_value,
                       'unit_id' => $unit_id,
                       'product_price' => $product_price,
                       'discount_value' => $discount_value,
                       'discount_type' => ($discount_type),
                       'product_description' => ($product_description),
                       'status' => 1,
                       );

            $log_msg .= $k.'. '.$product_name.' Inserted.'.PHP_EOL;

          }else{

            if($product_name ==''){
              $log_msg .= $k.'. '.$product_name.' Required.'.PHP_EOL;
            }

            if($category_id ==''){
              $log_msg .= $k.'. Category Required.'.PHP_EOL;
            }

            if($sub_category_id ==''){
              $log_msg .= $k.'. Subcategory Required.'.PHP_EOL;
            }

            if($product_stock ==''){
              $log_msg .= $k.'. Stock Required.'.PHP_EOL;
            }

            if($unit_value ==''){
              $log_msg .= $k.'. Unit Required.'.PHP_EOL;
            }

            if($unit_id ==''){
              $log_msg .= $k.'. Unit Type Required.'.PHP_EOL;
            }

            if($product_price ==''){
              $log_msg .= $k.'. Product Price Required.'.PHP_EOL;
            }

            if($product_name ==''){
              $log_msg .= $k.'. Description Required.'.PHP_EOL;
            }

          }
        }

        // echo "<pre>";print_r($data);
        $this->db->insert_batch('product', $data);

        if($log_msg !='')
        {
          $file_names = 'log_msg.txt';
            $file_path = 'uploads/'.$file_names;
            $handle = fopen($file_path, "w");
            fwrite($handle, $log_msg);
            fclose($handle);

            // $this->load->helper('download');
            // ob_end_clean();
            // force_download($file_names, file_get_contents($file_path));
        }

      }

      $this->session->set_flashdata('tokbox_success', 'Products uploded successfully');
      redirect(base_url().'admin/pharmacy');

    }
  }

  public function create_product(){
    $id=$this->input->post('id');
    $method=$this->input->post('method');
    $data['product_name']=($this->input->post('product_name'));
    $data["slug"] = (str_slug($data["product_name"]));
    $data["product_image"] =$this->input->post('product_img');
    $data['category_id']=$this->input->post('category_id');
    $data['sub_category_id']=$this->input->post('sub_category_id');
    $data['product_stock']=$this->input->post('product_stock');
    $data['unit_value']=$this->input->post('unit_value');
    $data['unit_id']=$this->input->post('unit_id');
    $data['product_price']=$this->input->post('product_price');
    $data['discount_value']=$this->input->post('discount_value');
    $data['product_description']=($this->input->post('product_description'));
    $data['discount_type']=($this->input->post('discount_type'));
    if($_FILES["product_image"]["name"] != '')
    {
         $config["upload_path"] = './uploads/products/';
         $config["allowed_types"] = '*';
         $this->load->library('upload', $config);
         $this->upload->initialize($config);


        $_FILES["file"]["name"] = 'img_'.time().'.png';
        $_FILES["file"]["type"] = $_FILES["product_image"]["type"];
        $_FILES["file"]["tmp_name"] = $_FILES["product_image"]["tmp_name"];
        $_FILES["file"]["error"] = $_FILES["product_image"]["error"];
        $_FILES["file"]["size"] = $_FILES["product_image"]["size"];
        if($this->upload->do_upload('file'))
        {
          $upload_data = $this->upload->data();
          
          $product_image='uploads/products/'.$upload_data["file_name"];
            
          $data['product_image']=$product_image;
                                                         
        }
    }
    if($method=='update')
    {
      $this->db->where('product_name',$data['product_name']);
      $this->db->where('id !=',$id);
      $query = $this->db->get('product');

    
      if ($query->num_rows() > 0)
      {
        $datas['result']='exe';
        $datas['status']='Product name already exits!';
      }
      else
      {
        $data['updated_at']=date('Y-m-d H:i:s');
        $this->db->where('id',$id);
        $this->db->update('product',$data);
        $result=($this->db->affected_rows()!= 1)? false:true;

        if(@$result==true) 
        {
          $datas['result']='true';
          $datas['status']='Product update successfully';
        }  
        else
        {
          $datas['result']='false';
          $datas['status']='Product update failed!';
        }
      }  
    }else{
      $this->db->where('product_name',$data['product_name']);
      $query = $this->db->get('product');

      if ($query->num_rows() > 0)
      {
        $datas['result']='exe';
        $datas['status']='Product name already exits!';
      }
      else
      {
        $data['status']=1;
        $data['created_at']=date('Y-m-d H:i:s');
        $this->db->insert('product',$data);
        $result=($this->db->affected_rows()!= 1)? false:true;

        if(@$result==true) 
        {
            $datas['result']='true';
            $datas['status']='Product added successfully';
        }  
        else
        {
            $datas['result']='false';
            $datas['status']='Product added failed!';
        }

      }  
    }
    echo json_encode($datas);
  }

  public function product_edit($id){
    $result = $this->pharmacy->get_product_by_id($id);

    $result['product_name']=($result['product_name']);
    $result['product_description']=($result['product_description']);
    $where=array('status' =>1,'category'=>$result['category_id']);

    $result_2=$this->db->get_where('product_subcategories',$where)->result_array();
    echo json_encode(array('data'=>$result,'sub_cat'=>$result_2));
  }

  public function product_delete($id){
    $this->pharmacy->delete_product($id);
    echo json_encode(array("status" => TRUE));
  }

  public function product_status($id){
    $result = $this->pharmacy->get_product_by_id($id);
    if($result['status']){
      $status = 0;
    }else{
      $status = 1;
    }
    $this->pharmacy->status_product($id,$status);
    echo json_encode(array("status" => TRUE));
  }

  public function categories(){
    $this->data['page'] = 'categories';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');
  }

  public function categories_list()
  {
    $list = $this->pharmacy->get_categories();
    $data = array();
    $no = $_POST['start'];
    $a=1;
     
    foreach ($list as $categoriess) {

      $no++;
      $row = array();
      $row[] = $a++;
      $row[] = '<h2 class="table-avatar">
                    <a href="javascript:void(0);" class="avatar avatar-sm mr-2">
                      <img class="avatar-img" src="'.base_url().$categoriess['category_image'].'" alt="'.$categoriess['category_name'].'">
                    </a>
                    <a href="javascript:void(0);">'.($categoriess['category_name']).'</a>
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
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data,
        );
    //output to json format
    echo json_encode($output);
  }

  public function create_categories()
    {   
        $id=$this->input->post('id');
        $method=$this->input->post('method');
        $data['category_name']=($this->input->post('category_name'));
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
              $datas['result']='false';
              $datas['status']='Categories update failed!';
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

    public function categories_edit($id)
    {
        $data = $this->pharmacy->get_category_by_id($id); 
        $data['category_name']=($data['category_name']);    
        echo json_encode($data);
    }

   public function categories_delete($id)
    {
        $this->pharmacy->delete_category($id);
        echo json_encode(array("status" => TRUE));
    }

    public function subcategories()
    {
        $this->data['page'] = 'subcategories';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
       
    }

    public function create_subcategories()
    {   
            $id=$this->input->post('id');
            $method=$this->input->post('method');
            $data['subcategory_name']=($this->input->post('subcategory_name'));
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
    $list = $this->pharmacy->get_subcategories();
    $data = array();
    $no = $_POST['start'];
    $a=1;
     
    foreach ($list as $subcategoriess) {

      $no++;
      $row = array();
      $row[] = $a++;
      $row[] = ($subcategoriess['category_name']);
      $row[] = ($subcategoriess['subcategory_name']);
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
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data,
        );
    //output to json format
    echo json_encode($output);
  }

  public function get_product_category()
  {
      $where=array('status' =>1);
      $result=$this->db->get_where('product_categories',$where)->result_array();
      $json=array();
     foreach ($result as $rows) {
        $data['value']=$rows['id'];
        $data['label']=($rows['category_name']);
        $json[]=$data;
     }

     echo json_encode($json);
  }

  public function get_product_subcategory()
  {
      $where=array('status' =>1,'category'=>$this->input->post('id'));
      $result=$this->db->get_where('product_subcategories',$where)->result_array();
      $json=array();
     foreach ($result as $rows) {
        $data['value']=$rows['id'];
        $data['label']=($rows['subcategory_name']);
        $json[]=$data;
     }

     echo json_encode($json);
  }

  public function get_product_subcategoryy()
  {
      $where=array('status' =>1);
      $result=$this->db->get_where('product_subcategories',$where)->result_array();
      $json=array();
     foreach ($result as $rows) {
        $data['value']=$rows['id'];
        $data['label']=($rows['subcategory_name']);
        $json[]=$data;
     }

     echo json_encode($json);
  }

  public function subcategories_edit($id)
    {
        $data = $this->pharmacy->get_subcategory_by_id($id);
        $data['subcategory_name']=($data['subcategory_name']);       
        echo json_encode($data);
    }

   public function subcategories_delete($id)
    {
        $this->pharmacy->delete_subcategory($id);
        echo json_encode(array("status" => TRUE));
    }

  public function units()
  {
      $this->data['page'] = 'units';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');  
  }

  public function create_unit()
  {   
      $id=$this->input->post('id');
      $method=$this->input->post('method');
      $data['unit_name']=($this->input->post('unit_name'));
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
    $list = $this->pharmacy->get_units();
    $data = array();
    $no = $_POST['start'];
    $a=1;
     
    foreach ($list as $units) {

      $no++;
      $row = array();
      $row[] = $a++;
      $row[] = ($units['unit_name']);
               
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
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data,
        );
    //output to json format
    echo json_encode($output);
  }


    public function unit_edit($id)
    {
        $data = $this->pharmacy->get_unit_by_id($id);
        $data['unit_name']=($data['unit_name']);
        
        echo json_encode($data);
    }

   public function unit_delete($id)
    {
        $this->pharmacy->delete_unit($id);
        echo json_encode(array("status" => TRUE));
    }

  public function get_product_unit()
  {
      $where=array('status' =>1);
      $result=$this->db->get_where('unit',$where)->result_array();
      $json=array();
     foreach ($result as $rows) {
        $data['value']=$rows['id'];
        $data['label']=($rows['unit_name']);
        $json[]=$data;
     }

     echo json_encode($json);
  }

  public function orders()
  {
      $this->data['page'] = 'orders';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');  
  }

  public function orders_list(){
    $list = $this->order->get_order();
    // echo "<pre>";print_r($list);die;
    $data = array();
    $no = $_POST['start'];
    $a=1;
     
    foreach ($list as $rows) {
      $no++;
      $row = array();
      $row[] = $a++;
      $row[] = '<a href="'.base_url().'admin/pharmacy/order_details/'.base64_encode($rows['order_id']).'">'.$rows['order_id'].'</a>';
      $row[] = $rows['pharmacy_name'];
      $row[] = $rows['quantity'];
      $row[] = number_format($rows['subtotal'],2);
        $row[] = '<span class="badge badge-success">'.($rows['payment_type']).'</span>';
      

   
        if(($rows['order_status']) =='pending' && (($rows['order_status'])!='completed' || ($rows['order_status'])!='canceled')){
          $row[] = '<select name="order_status" id="order_status" onchange="order_status('.$rows['order_id'].')">
                    <option value="">Order Status</option>
                    <option value="accepted" >Accepted</option>
                    <option value="shipped" >Shipped</option>
                    <option value="completed" >Delivered</option>
                  </select>';
        }else{
          $row[] = '<select name="order_status" id="order_status" disabled="disabled">
                    <option value="">'.($rows['order_status']).'</option>>
                  </select>';
        }
      

      if(($rows['order_status'])=='completed'){
        $row[] = '<span class="badge badge-success">'.($rows['order_status']).'</span>';
      }else if(($rows['order_status'])=='deleted'){
        $row[] = '<span class="badge badge-danger">'.($rows['order_status']).'</span>';
      }else if(($rows['order_status'])=='canceled'){
        $row[] = '<span class="badge badge-danger">'.($rows['order_status']).'</span>';
      }else if(($rows['order_status'])=='pending'){
        $row[] = '<span class="badge badge-info">order placed</span>';
      }else{
        $row[] = '<span class="badge badge-warning">'.($rows['order_status']).'</span>';
      }
      
      $my_row = '<div class="actions">
                  <a class="btn btn-sm bg-danger-light" href="'.base_url().'admin/pharmacy/order_details/'.base64_encode($rows['order_id']).'">
                    <i class="fas fa-eye"></i>
                  </a>';
                  // if(($rows['status'])!='canceled' && ($rows['status'])!='completed'){
                  // $my_row .= '<a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="cancel_order('.$rows['order_id'].')">
                  //   <i class="fas fa-ban"></i>
                  // </a>';
                  // }
                  // if(($rows['status'])!='deleted'){
                  // $my_row .= '<a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_order('.$rows['order_id'].')">
                  //   <i class="fas fa-trash"></i>
                  // </a>';
                  // }
                $my_row .= '</div>';
      $row[] = $my_row;
      
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

  public function order_description(){
    $id = $this->input->post('id');
    $status = $this->input->post('status');
    $description = $this->input->post('description');
    $this->db->select('order_desc');
    $this->db->where('id',$id);
    $result = $this->db->get('orders')->row_array();
    $arrGet = (array) json_decode(($result['order_desc']));
    if(!empty($arrGet)){
      $arrGet[$status]=$description;
    }else{
      $arrGet = array($status=>$description);
    }
    $data = array('order_desc'=> (json_encode($arrGet)),'status'=>($status));
    $this->db->where('id',$id);
    $this->db->update('orders',$data);
  }

  public function order_details($id)
  {
      $this->data['page'] = 'order_details';
      $this->data['list'] = $this->order->get_order_details(array('o.order_id' => base64_decode($id)));
      $this->load->vars($this->data);
      // echo "<pre>";print_r($this->data['list']);die;
      $this->load->view($this->data['theme'].'/template',$this->data);
  }

  public function cancel_order(){
    $id = $this->input->post('id');
    $reason = $this->input->post('reason');
    $cancel_by = $this->input->post('cancel_by');
    $data = array(
      'status' => ('canceled'),
      'cancel_by' => ($cancel_by),
      'cancel_reason' => ($reason)
    );
    $this->db->where('id',$id);
    $this->db->update('orders',$data);
    $updateItems=($this->db->affected_rows()== 0)? false:true;

    $order = $this->order->get_order(array('o.id' => $id));

    if(($order[0]['payment_status']) == 'paid'){
      $val = $order[0]['quantity'];
      $update = $this->db->where('id',$order[0]['product_id'])->set('product_stock', 'product_stock+'.(int)$val,FALSE)->update('product');
    }

    if($updateItems){
      $results = array('status'=>200,'msg'=>'Your order has been canceled');
      echo json_encode($results);
    }
    else
    {
      $results = array('status'=>500);
      echo json_encode($results);
    }
  }

  public function order_delete($id){
    $data = array('status'=>('deleted'));
    $this->db->where('id',$id);
    $this->db->update('orders',$data);
    echo json_encode(array("status" => TRUE));
  }

}