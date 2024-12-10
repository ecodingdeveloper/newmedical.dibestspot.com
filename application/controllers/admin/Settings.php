<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
define('FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
class Settings extends CI_Controller {

   public $data;
   public $session;
   public $timezone;
   public $input;
   public $db;
   public $upload;

   public function __construct() {

        parent::__construct();

        if($this->session->userdata('admin_id') ==''){
            redirect(base_url().'admin/login');
        }
        
        $this->data['theme']     = 'admin';
        $this->data['module']    = 'settings';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
         $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }

    }


  public function index ()
    {     
        if ($this->input->post('form_submit')) {
          // die("dsadf");
      $data = $this->input->post();
      
     
      
      $table_data['system']           = 1;
      $table_data['groups']           = 'config';
      $table_data['update_date']      = date('Y-m-d');
      $table_data['status']           = 1;
      if($_FILES['site_logo']['name'])
      {     

        $configfile['upload_path']   = FCPATH . 'uploads/logo';
        $configfile['allowed_types'] = 'gif|jpg|png|jpeg';
        $configfile['overwrite']     = FALSE;
        $configfile['remove_spaces'] = TRUE;
        $file_name                  = trim($_FILES['site_logo']['name']);
        //$file_name          = preg_replace("/[^A-Za-z0-9]/", "_", $file_name);
        $configfile['file_name']    = time().'_'.$file_name;    
        $image_name = $configfile['file_name'];
        $image_url = 'uploads/logo/'.$image_name;
        $this->load->library('upload');
        $this->upload->initialize($configfile);                                
        if ($this->upload->do_upload('site_logo')) 
        {    
         $img_uploadurl      = 'uploads/logo/'.$_FILES['site_logo']['name'];      
            $key = 'logo_front';
           // $val = $this->image_resize(447,268,$image_url,$image_name);  
            $val=   'uploads/logo/'.$this->upload->data('file_name');
            $select_logo = $this->db->query("SELECT * FROM `system_settings` WHERE `key` = '$key' ");
            $select_logo_result = $select_logo->row_array();
            if(count($select_logo_result)>0)
            {       
                $this->db->where('key',$key);
                $this->db->update('system_settings',array('value'=>$val));
            }
            else 
            {
                $table_data['key']        = $key;
                $table_data['value']      = $val; 
                $this->db->insert('system_settings', $table_data);
            }
        } 
                                        
                                        
      }
            if($_FILES['footer_logo']['name'])
      {     

        $configfile['upload_path']   = FCPATH . 'uploads/logo';
        $configfile['allowed_types'] = 'gif|jpg|png|jpeg';
        $configfile['overwrite']     = FALSE;
        $configfile['remove_spaces'] = TRUE;
        $file_name                  = trim($_FILES['footer_logo']['name']);
        //$file_name          = preg_replace("/[^A-Za-z0-9]/", "_", $file_name);
        $configfile['file_name']    = time().'_'.$file_name;    
                                $image_name = $configfile['file_name'];
                                $image_url = 'uploads/logo/'.$image_name;
        $this->load->library('upload');
        $this->upload->initialize($configfile);                                
        if ($this->upload->do_upload('footer_logo')) 
        {    
         $img_uploadurl      = 'uploads/logo/'.$_FILES['footer_logo']['name'];      
            $key = 'logo_footer';
           // $val = $this->image_resize(447,268,$image_url,$image_name);  
            $val=   'uploads/logo/'.$this->upload->data('file_name');
            $select_logo = $this->db->query("SELECT * FROM `system_settings` WHERE `key` = '$key' ");
            $select_logo_result = $select_logo->row_array();
            if(count($select_logo_result)>0)
            {       
                $this->db->where('key',$key);
                $this->db->update('system_settings',array('value'=>$val));
            }
            else 
            {
                $table_data['key']        = $key;
                $table_data['value']      = $val; 
                $this->db->insert('system_settings', $table_data);
            }
        } 
                                        
                                        
      }
      if($_FILES['apns_pem_file']['name'])
      {     

        $configfile['upload_path']   = FCPATH . 'uploads/apns_pem_file';
        $configfile['allowed_types'] = '*';
        $configfile['overwrite']     = FALSE;
        $configfile['remove_spaces'] = TRUE;
        $file_name                  = trim($_FILES['apns_pem_file']['name']);
        //$file_name          = preg_replace("/[^A-Za-z0-9]/", "_", $file_name);
        $configfile['file_name']    = time().'_'.$file_name;    
                                $image_name = $configfile['file_name'];
                                $image_url = 'uploads/apns_pem_file/'.$image_name;
        $this->load->library('upload');
        $this->upload->initialize($configfile);                                
        if ($this->upload->do_upload('apns_pem_file')) 
        {    
         $img_uploadurl      = 'uploads/apns_pem_file/'.$_FILES['apns_pem_file']['name'];      
            $key = 'apns_pem_file';
           // $val = $this->image_resize(447,268,$image_url,$image_name);  
            $val=   'uploads/apns_pem_file/'.$this->upload->data('file_name');
            $select_logo = $this->db->query("SELECT * FROM `system_settings` WHERE `key` = '$key' ");
            $select_logo_result = $select_logo->row_array();
            if(count($select_logo_result)>0)
            {       
                $this->db->where('key',$key);
                $this->db->update('system_settings',array('value'=>$val));
            }
            else 
            {
                $table_data['key']        = $key;
                $table_data['value']      = $val; 
                $this->db->insert('system_settings', $table_data);
            }
        } 
                                        
                                        
      }
      if($_FILES['favicon']['name'])
      {  
        $img_uploadurl1 ='';
        $table_data=array();
        $configfile['upload_path']   = FCPATH . 'uploads/logo';
        $configfile['allowed_types'] = 'gif|jpg|png|ico|avi|flv|wmv|mp3';
        $configfile['overwrite']     = FALSE;
        $configfile['remove_spaces'] = TRUE;
        $file_name                  = trim($_FILES['favicon']['name']);
        $configfile['file_name']    = $file_name;
        $this->load->library('upload');
        $this->upload->initialize($configfile);                                
        if ($this->upload->do_upload('favicon')) 
            {                                    
                $img_uploadurl1      = 'uploads/logo/'.$_FILES['favicon']['name'];  
                $key                 = 'favicon';
                $val                 = 'uploads/logo/'.$this->upload->data('file_name');
                $select_fav_icon     = $this->db->query("SELECT * FROM `system_settings` WHERE `key` = '$key' ");
                $fav_icon_result     = $select_fav_icon->row_array();
                
                if(count($fav_icon_result)>0)
                {
                    $this->db->where('key',$key);
                    $this->db->update('system_settings',array('value'=>$val));
                }
                else 
                {
                    $table_data['key']        = $key;
                    $table_data['value']      = $val;           
                    $this->db->insert('system_settings', $table_data);
                }
            }
      }
      if($data){
        $table_data=[];
        foreach ($data AS $key => $val) {
          if($key!='form_submit' || $key!='favicon' || $key!='logo_front' ){
            $this->db->where('key', $key);
                                                $this->db->delete('system_settings');
            $table_data['key']        = $key;
            $table_data['value']      = $val;
            $table_data['system']      = 1;
            $table_data['groups']      = 'config';
            $table_data['update_date']  = date('Y-m-d');
            $table_data['status']       = 1;
            $this->db->insert('system_settings', $table_data);
            
          }else{}
        }
      } else{}                        
      
    
      $message ='Settings are saved successfully.'; 
            $this->session->set_flashdata('success_message', $message);

      redirect(base_url('admin/' . $this->data['module']));
    }
    $results = $this->get_setting_list();
    foreach ($results AS $config) {
      $this->data[$config['key']] = $config['value'];
    }
     
    $this->data['page'] = 'index';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
    }


    public function get_setting_list() {
        $data = array();
        $stmt = "SELECT a.*"
                . " FROM system_settings AS a"
                . " ORDER BY a.`id` ASC";
        $query = $this->db->query($stmt);
        if ($query->num_rows()) {
            $data = $query->result_array();
        }
        return $data;
    }

}
