<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

   public $data;
   public $session;
   public $input;
   public $admin_login;

   public function __construct() {

        parent::__construct();
        $this->load->model('admin_login_model','admin_login');
        $this->data['theme']     = 'admin';
        $this->data['module']    = 'login';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
        date_default_timezone_set('Asia/Kolkata'); 
        $this->session->set_userdata('team_member_id', 7);
    }


	public function index()
	{
	    if (empty($this->session->userdata['admin_id']))
	    {
	  		
	  		$this->load->view($this->data['theme'].'/modules/'.$this->data['module'].'/login');
	    }
	    else
	    {
	      redirect(base_url().$this->data['theme']."/dashboard");
	    }
	}

  public function is_valid_login()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$result = $this->admin_login->is_valid_login($email,$password);
		if(!empty($result) && $result!=1 && $result!=2)
		{
			$this->session->set_userdata('admin_id',$result['id']);
			  $this->session->unset_userdata('user_id');
              $this->session->unset_userdata('role');
  		    redirect(base_url().$this->data['theme']."/dashboard");
		}
		  else if($result==1) {
		  //$this->session->set_flashdata('error_message','Wrong login credentials.');
		   $this->session->set_flashdata('error_message','Invalid Email');
			}
			else if($result==2) {
		   $this->session->set_flashdata('error_message','Invalid Password');
			}
			/*else if( $result!=1 && $result!=2 ) {
		   $this->session->set_flashdata('error_message','Wrong login credentials.');
			}*/
			redirect(base_url().$this->data['theme']."/login");
		 
	}

	public function forgot_password()
	{

		$this->data['page'] = 'forgot_password';
		$this->load->vars($this->data);
		$this->load->view($this->data['theme'].'/template');
	}

 	public function logout()
	{
	    
	     $this->session->sess_destroy();
	   
	    $this->session->set_flashdata('success_message','Logged out successfully');
		redirect(base_url()."admin");
    }

}
