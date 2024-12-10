<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . '../vendor/autoload.php');

class Book_package extends CI_Controller {
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
  $this->load->model('packages_model','packages');
    }

    public function index()
    {
        //die("jg");
        // Load the 'example_view.php' view
        $this->data['_id'] = $this->session->userdata('_id');
        $this->data['page'] = 'checkout_package';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
        //$this->load->view('web/modules/warmembrace/warmembrace_home.php');
    }
    public function book($id)
    {
      
        // Load the 'example_view.php' view
        // $this->data['_id'] = $this->session->userdata('_id');
        $this->data['package_id']=$id;
        $this->data['page'] = 'checkout_package';
        $package_data = $this->packages->get_package($id);
        
        $this->data['package_data'] = $package_data;

        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
        //$this->load->view('web/modules/warmembrace/warmembrace_home.php');
    }

   
}

