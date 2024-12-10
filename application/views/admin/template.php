<?php

if (isset($this->session->userdata['admin_id'])) {   
   /** @var string  $theme */
   /** @var string  $module */
   /** @var string  $page */
   // echo ($theme . '/modules/' . $module . '/' . $page);
    $this->load->view($theme . '/includes/header');
    $this->load->view($theme . '/includes/navbar');
    $this->load->view($theme . '/includes/sidebar');
    $this->load->view($theme . '/modules/' . $module . '/' . $page);
    $this->load->view($theme . '/includes/footer');
} else {     
 
   redirect(base_url().'admin/login');

}