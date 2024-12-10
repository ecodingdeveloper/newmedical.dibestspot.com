<?php
error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

class Cms extends CI_Controller
{
  public $data;
  public $session;
  public $input;
  public $upload;
  public $db;

  public function __construct()
  {

    parent::__construct();

    if ($this->session->userdata('admin_id') == '') {
      redirect(base_url() . 'admin/login');
    }

    $this->data['theme']     = 'admin';
    $this->data['module']    = 'cms';
    $this->data['page']     = '';
    $this->data['base_url'] = base_url();
    date_default_timezone_set('Asia/Kolkata');
  }

  public function index()
  {


    if ($this->input->post('form_submit')) {
      $data = $this->input->post();


      $table_data['system']           = 1;
      $table_data['groups']           = 'config';
      $table_data['update_date']      = date('Y-m-d');
      $table_data['status']           = 1;
      //banner image
      if ($_FILES['banner_image']['name']) {


        $file = $_FILES["banner_image"]['tmp_name'];
        list($width, $height) = getimagesize($file);

        if ($width < "1600" || $height < "210") {

          $message = 'Please upload above 1600x210 banner image size';
          $this->session->set_flashdata('error_message', $message);

          redirect(base_url('admin/' . $this->data['module']));
        }

        $configfile['upload_path']   = FCPATH . 'uploads/banner';
        $configfile['allowed_types'] = 'gif|jpg|png|jpeg';
        $configfile['overwrite']     = FALSE;
        $configfile['remove_spaces'] = TRUE;
        $file_name                  = trim($_FILES['banner_image']['name']);
        //$file_name          = preg_replace("/[^A-Za-z0-9]/", "_", $file_name);
        $configfile['file_name']    = time() . '_' . $file_name;
        $image_name = $configfile['file_name'];
        $image_url = 'uploads/banner/' . $image_name;
        $this->load->library('upload');
        $this->upload->initialize($configfile);
        if ($this->upload->do_upload('banner_image')) {
          $img_uploadurl      = 'uploads/banner/' . $_FILES['banner_image']['name'];
          $key = 'banner_image';
          // $val = $this->image_resize(447,268,$image_url,$image_name);  
          $val =   'uploads/banner/' . $this->upload->data('file_name');
          $select_logo = $this->db->query("SELECT * FROM `system_settings` WHERE `key` = '$key' ");
          $select_logo_result = $select_logo->row_array();
          if (count($select_logo_result) > 0) {
            $this->db->where('key', $key);
            $this->db->update('system_settings', array('value' => $val));
          } else {
            $table_data['key']        = $key;
            $table_data['value']      = $val;
            $this->db->insert('system_settings', $table_data);
          }
        }
      }

      //feature image
      if ($_FILES['feature_image']['name']) {

        $file = $_FILES["feature_image"]['tmp_name']; echo "<pre>";  print_r($_FILES["feature_image"]);
        list($width, $height) = getimagesize($file);

        if ($width < "421" || $height < "376") {
          $message = 'Please upload above 421x376 features image size';
          $this->session->set_flashdata('error_message', $message);
          redirect(base_url('admin/' . $this->data['module']));
        }

        $configfile['upload_path']   = FCPATH . 'uploads/availabe_feature_image';
        $configfile['allowed_types'] = 'gif|jpg|png|jpeg';
        $configfile['overwrite']     = FALSE;
        $configfile['remove_spaces'] = TRUE;
        $file_name                  = trim($_FILES['feature_image']['name']);
        //$file_name          = preg_replace("/[^A-Za-z0-9]/", "_", $file_name);
        $configfile['file_name']    = time() . '_' . $file_name;
        $image_name = $configfile['file_name'];
        $image_url = 'uploads/availabe_feature_image/' . $image_name;
        $this->load->library('upload');
        $this->upload->initialize($configfile);
        if ($this->upload->do_upload('feature_image')) {
          $img_uploadurl      = 'uploads/availabe_feature_image/' . $_FILES['feature_image']['name'];
          $key = 'feature_image';
          // $val = $this->image_resize(447,268,$image_url,$image_name);  
          $val =   'uploads/availabe_feature_image/' . $this->upload->data('file_name');
          $select_logo = $this->db->query("SELECT * FROM `system_settings` WHERE `key` = '$key' ");
          $select_logo_result = $select_logo->row_array();
          if (count($select_logo_result) > 0) {
            $this->db->where('key', $key);
            $this->db->update('system_settings', array('value' => $val));
          } else {
            $table_data['key']        = $key;
            $table_data['value']      = $val;
            $this->db->insert('system_settings', $table_data);
          }
        } else {
          $this->session->set_flashdata('error_message', $this->upload->display_errors());
          redirect(base_url('admin/' . $this->data['module']));
        }
      }

      //login image
      if ($_FILES['login_image']['name']) {

        $file = $_FILES["login_image"]['tmp_name'];
        list($width, $height) = getimagesize($file);

        if ($width < "1000" || $height < "650") {
          $message = 'Please upload above 1000x650 banner image size';
          $this->session->set_flashdata('error_message', $message);
          redirect(base_url('admin/' . $this->data['module']));
        }


        $configfile['upload_path']   = FCPATH . 'uploads/login_image';
        $configfile['allowed_types'] = 'gif|jpg|png|jpeg';
        $configfile['overwrite']     = FALSE;
        $configfile['remove_spaces'] = TRUE;
        $file_name                  = trim($_FILES['login_image']['name']);
        //$file_name          = preg_replace("/[^A-Za-z0-9]/", "_", $file_name);
        $configfile['file_name']    = time() . '_' . $file_name;
        $image_name = $configfile['file_name'];
        $image_url = 'uploads/login_image/' . $image_name;
        $this->load->library('upload');
        $this->upload->initialize($configfile);
        if ($this->upload->do_upload('login_image')) {
          $img_uploadurl      = 'uploads/login_image/' . $_FILES['login_image']['name'];
          $key = 'login_image';
          // $val = $this->image_resize(447,268,$image_url,$image_name);  
          $val =   'uploads/login_image/' . $this->upload->data('file_name');
          $select_logo = $this->db->query("SELECT * FROM `system_settings` WHERE `key` = '$key' ");
          $select_logo_result = $select_logo->row_array();
          if (count($select_logo_result) > 0) {
            $this->db->where('key', $key);
            $this->db->update('system_settings', array('value' => $val));
          } else {
            $table_data['key']        = $key;
            $table_data['value']      = $val;
            $this->db->insert('system_settings', $table_data);
          }
        }
      }


      if ($data) {
        $table_data = [];
        foreach ($data as $key => $val) {
          if ($key != 'form_submit' || $key != 'banner_image' || $key != 'feature_image' || $key != 'login_image') {
            $this->db->where('key', $key);
            $this->db->delete('system_settings');
            $table_data['key']        = $key;
            $table_data['value']      = $val;
            $table_data['system']      = 1;
            $table_data['groups']      = 'config';
            $table_data['update_date']  = date('Y-m-d');
            $table_data['status']       = 1;
            $this->db->insert('system_settings', $table_data);
          }
        }
      }


      $message = 'Update successfully.';
      $this->session->set_flashdata('success_message', $message);

      redirect(base_url('admin/' . $this->data['module']));
    }
    $results = $this->get_setting_list();
    foreach ($results as $config) {
      $this->data[$config['key']] = $config['value'];
    }

    $this->data['page'] = 'index';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }


  public function get_setting_list()
  {
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
