<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . '../vendor/autoload.php');

class Book_service extends CI_Controller {
    public $data;
    public $lang;
    public $language;
    public function __construct()
    {
        parent::__construct();
        // $this->load->library('email');
        // $this->load->helper('url');
        // $this->load->library('form_validation'); 
        // Load models, libraries, etc. here if needed
        $this->data['theme'] = 'web';
        $this->data['module'] = 'ecommerce';
        $this->data['page'] = '';

        $lan=default_language();
  $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):strtolower($lan['language']);
  $this->data['language'] = $this->lang->load('content', $lang, true);
  $this->language = $this->lang->load('content', $lang, true);
  $this->load->model('services_model','services');
    }

    public function index()
    {
        //die("jg");
        // Load the 'example_view.php' view
        $this->data['_id'] = $this->session->userdata('_id');
        $this->data['page'] = 'checkout_service';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
        //$this->load->view('web/modules/warmembrace/warmembrace_home.php');
    }
    public function book($id)
    {
      
        // Load the 'example_view.php' view
        // $this->data['_id'] = $this->session->userdata('_id');
        $this->data['service_id']=$id;
        $this->data['page'] = 'checkout_service';
        $service_data = $this->services->get_service($id);
        
        $this->data['service_data'] = $service_data;

        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
        //$this->load->view('web/modules/warmembrace/warmembrace_home.php');
    }

   
}

