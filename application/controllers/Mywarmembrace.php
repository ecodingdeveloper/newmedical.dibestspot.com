<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . '../vendor/autoload.php');

class Mywarmembrace extends CI_Controller {
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
        $this->data['module'] = 'mywarmembrace';
        $this->data['page'] = '';

        $lan=default_language();
  $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):strtolower($lan['language']);
  $this->data['language'] = $this->lang->load('content', $lang, true);
  $this->language = $this->lang->load('content', $lang, true);
    }

    public function index()
    {
        //die("jg");
        // Load the 'example_view.php' view
        $this->data['_id'] = $this->session->userdata('_id');
        $this->data['page'] = 'warmembrace_home';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
        //$this->load->view('web/modules/warmembrace/warmembrace_home.php');
    }

    

    public function registration()
    {
        $this->data['_id'] = $this->session->userdata('_id');
        $this->data['page'] = 'warmembrace_registration';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function intended_parents()
    {
        $this->data['_id'] = $this->session->userdata('_id');
        $this->data['page'] = 'warmembrace_intended_parents';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function sprogram()
    {
        $this->data['_id'] = $this->session->userdata('_id');
        $this->data['page'] = 'warmembrace_sprogram';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function waiting_room()
    {
        $this->data['_id'] = $this->session->userdata('_id');
        $this->data['page'] = 'warmembrace_waiting_room';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    
    public function ip_live_webinar(){
        $this->data['_id'] = $this->session->userdata('_id');
        $this->data['page'] = 'warmembrace_ip_live_webinar';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function sprogram_video(){
        $this->data['_id'] = $this->session->userdata('_id');
        $this->data['page'] = 'warmembrace_sprogram_video';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function handle_form_submission()
{
   //Get POST data
            $fullName = $this->input->post('fullName');
            $email = $this->input->post('email');

            // echo $email;
            // die("SAdf");
           
            $this->load->library('sendemail');
            $result=$this->sendemail->new_email($fullName,$email);
            // echo $result;
            // die("die here");
            // $this->data['page'] = 'warmembrace_sprogram';
            // $this->load->vars($this->data);
            // $this->load->view($this->data['theme'] . '/template');
            $response = array('status' => 'success', 'message' => 'Form submitted successfully');
            echo json_encode($response);
            exit; 
           
    }
    public function handle_form_submission1()
{
 
    $fullName = $this->input->post('fullName');
    $email = $this->input->post('email');

   
    $this->load->library('sendemail');
    $result=$this->sendemail->new_email($fullName,$email);
    // echo $fullName;
    // echo '<br>';
    // echo $email;
    // die("sdf");
   
    $response = array('status' => 'success', 'message' => 'Form submitted successfully');
    echo json_encode($response);
    exit;
        
    
        
    
}

public function insertPlan(){
    
    $profile    = $this->input->post('profile');   
    $time_period= $this->input->post('time_period');

   
    $this->load->database();

    
    $this->db->where('profile', $profile); 
    $query = $this->db->get('plan'); 

   
    if ($query->num_rows() > 0) {
      
        $data = array(
            'plan' => $time_period
        );
        
        
        $this->db->where('profile', $profile);
        $updated = $this->db->update('plan', $data);

        if ($updated) {
            // echo "Plan updated successfully!";
            $response = array('status' => 'success', 'message' => 'Plan updated successfully');
    
        } else {
            // echo "Failed to update the plan.";
            $response = array('status' => 'success', 'message' => 'plan update failed');
    
        }
    } else {
        
        $data = array(
            'profile'     => $profile,
            'plan' => $time_period
        );

        
        $inserted = $this->db->insert('plan', $data);

        if ($inserted) {
            $response = array('status' => 'success', 'message' => 'Plan inserted successfully');
            
        } else {
            $response = array('status' => 'fail', 'message' => 'Plan insertion failed');
   
        }
    }

    echo json_encode($response);
}
}

