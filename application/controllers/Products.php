<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

    public $data;
    public $session;
    public $timezone;
    public $lang;
    public $language;
    public $product;
    public $input;
    public $db;

public function __construct() {

        parent::__construct();
        $this->data['theme']     = 'web';
        $this->data['module']    = 'ecommerce';
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

        $this->load->model('product_model','product');
        
                 
    }

    public function index()
    {
      

      $this->data['page'] = 'index';
      $subcategory = $_GET['subcategory'];
      
      $category_data=$this->product->get_particular_categories($subcategory);
      
      $categoryId = $category_data[0]['category'];
      $this->data['pharmacy_id'] = $this->session->userdata('pharmacy_id');
      $this->data['categoryId'] = $category_data[0]['category'];
      $this->data['subCategoryId'] = $category_data[0]['id'];
      $this->data['subcategory_list']=$this->product->get_sub_categories($categoryId);
      $this->data['subcategory_name'] = $category_data[0]['subcategory_name'];

      $this->data['categories']=$this->product->get_categories();

      $this->data['categorie_name'] = $this->data['categories'][0]['category_name'];

      $this->data['popular_products']=$this->product->get_popular_products();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
     
    }

    public function products_list()
    {
        $this->data['page'] = 'products_list';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
       
    }


    public function get_products()
    {
        $response=array();
         $result=array();
          $page=$this->input->post('page');
          $limit=16;
          $response['count'] =$this->product->get_products($page,$limit,1);
          $product_list = $this->product->get_products($page,$limit,2);

          if (!empty($product_list)) {
            foreach ($product_list as $rows) {
              $image_url=explode(',', $rows['upload_image_url']);

              $data['id']=$rows['id'];
              $data['productid']=md5($rows['id']);
              $data['name']=$rows['name'];
              $data['slug']=$rows['slug'];
              $data['product_image']=base_url().$image_url[0];
              $data['sale_price']=number_format($rows['sale_price'],2);
              $data['unit']=$rows['unit_value'].$rows['unit_name'];
              $result[]=$data;
            }
          }
          $response['current_page_no']= $page;
          $response['total_page']= ceil($response['count']/$limit);
          $response['data']= $result;

       echo json_encode($response);
    }

    public function get_search_key_products(){

          $page=1;
          $limit=16;

          $product_list = $this->product->get_products_by_search($page,$limit,2);
            $products = array();

            if(isset($product_list) && !empty($product_list)){
            foreach($product_list as $product_li){
                $products[] = $product_li['name'];
            } }

          echo json_encode($products);

    }

    public function product_details($slug)
    {
        
      $user_currency=get_user_currency();

      $this->data['user_currency_code'] = $user_currency['user_currency_code'];
      $this->data['user_currency_sign'] = $user_currency['user_currency_sign'];

      $this->data['page'] = 'product_details';
      $this->data['products']=$this->product->get_product_details($slug);
      $pharmacy_currency_code = $this->data['products']['currency_code'];

      $this->data['sale_price']=get_doccure_currency(round($this->data['products']['price']),$pharmacy_currency_code,$user_currency['user_currency_code']);

      $this->data['price']=get_doccure_currency(round($this->data['products']['sale_price']),$pharmacy_currency_code,$user_currency['user_currency_code']);

      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }

    public function search_products()
    {
      $data     = array();
      $response = array();
      $result   = array();
      $search_product = $this->input->post('search_product');
      
      if(!empty($search_product))
      {
        $location_list= $this->product->search_products($search_product); 

        foreach ($location_list as $rows) 
        {
          $data['products'] = $rows['product_name'];
          $result[] = $data;
        }
   
      }
       $response['products'] = $result;
       
      echo json_encode($response);
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

  public function product_delete(){

    $id = $this->input->post('id');
    $data = array(
            'status' =>0,
        );
    $this->db->where('id',$id);
    $this->db->update('products', $data);
    echo json_encode(array("status" => TRUE));
  }
}