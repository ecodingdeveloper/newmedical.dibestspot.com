<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_template extends CI_Controller {
    
    public $data;
    public $session;
    public $input;
    public $db;
    public $emailtemplate;
    public $uri;

    public function __construct() {

        parent::__construct();

        if($this->session->userdata('admin_id') ==''){
            redirect(base_url().'admin/login');
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
        $this->data['theme']     = 'admin';
        $this->data['module']    = 'email_template';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
        $this->load->model('emailtemplate_model','emailtemplate');
        date_default_timezone_set('Asia/Kolkata'); 

    }


    public function index()
    {
        $this->data['page'] = 'index';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
       
    }

    public function email_template_list()
    {
        $list = $this->emailtemplate->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $a=1;
         
        foreach ($list as $email_template) {

          $no++;
          $row = array();
          $row[] = $a++;
          $row[] = $email_template['template_title'];
          $edit_url=base_url().'admin/edit_email_template/'.base64_encode($email_template['template_id']);
          $row[] = '<div class="actions">
                      <a  class="btn btn-sm bg-success-light" href="'.$edit_url.'" >
                        <i class="fe fe-pencil"></i> Edit
                      </a>
                      
                    </div>';
          $data[] = $row;
        }



        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->emailtemplate->count_all(),
                "recordsFiltered" => $this->emailtemplate->count_filtered(),
                "data" => $data,
            );
        //output to json format
        echo json_encode($output);
    }

    public function add_email_template(){
        $this->data['page'] = 'create';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }

    public function create(){

      if($this->input->post('form_submit'))
        {
          $data['template_title'] = $this->input->post('template_title');
          $data['template_content'] = $this->input->post('template_content');
          $data['template_subject'] = $this->input->post('template_subject');
          $data['template_type'] = 5;
          $data['template_created'] = date('Y-m-d H:i:s');
          $data['template_status'] = 1;

          if($this->db->insert('email_templates',$data))
          {
            $this->session->set_flashdata('success_message','Email Template Added Successfully');
            redirect(base_url().'admin/email_template');
          }
     
        }  

    }

    public function edit_email_template(){
        $id=$this->uri->segment('3');
        if(!empty($id)){
          $this->data['page'] = 'edit';
          $this->data['edit_data'] = $this->emailtemplate->edit_template(base64_decode($id));
          $this->load->vars($this->data);
          $this->load->view($this->data['theme'].'/template');
        }
    }

    public function edit($id) 
    {
        if($this->input->post('form_submit'))
        {
            $data['template_content'] = $this->input->post('template_content');
            $data['template_subject'] = $this->input->post('template_subject');
   
            $this->db->where('template_id',$id);
            if($this->db->update('email_templates',$data))
            {
              $this->session->set_flashdata('success_message','Email Template Update Successfully');
              redirect(base_url().'admin/email_template');
            }
       
            
        }
        
    }

}

?>