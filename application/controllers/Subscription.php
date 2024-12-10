<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . '../vendor/autoload.php');

class Subscription extends CI_Controller {
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
        $this->data['module'] = 'subscription';
        $this->data['page'] = '';

        $lan=default_language();
  $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):strtolower($lan['language']);
  $this->data['language'] = $this->lang->load('content', $lang, true);
  $this->language = $this->lang->load('content', $lang, true);
  $this->load->model('subscription_model','subscription');
    }

    public function index()
    {
        
        $this->data['page'] = 'subscription_plan';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
        //$this->load->view('web/modules/warmembrace/warmembrace_home.php');
    }
    public function plans()
    {
        $this->data['page'] = 'subscription_plan';
        $features = $this->subscription->get_features();
        $gtc = $this->subscription->role_0();
        $doctor = $this->subscription->role_1();
        $patient = $this->subscription->role_2();
        $lab = $this->subscription->role_4();
        $pharmacy = $this->subscription->role_5();
        $clinic = $this->subscription->role_6();
        
        $this->data['features'] = $features;
        $this->data['gtc'] = $gtc;
        $this->data['doctor'] = $doctor;
        $this->data['patient'] = $patient;
        $this->data['lab'] = $lab;
        $this->data['pharmacy'] = $pharmacy;
        $this->data['clinic'] = $clinic;

        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
        //$this->load->view('web/modules/warmembrace/warmembrace_home.php');
    }   
}

