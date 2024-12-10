<?php
/** @property object $session */
     /** @var string $module */
	 /** @var string $page */
	 /** @var string $theme */
if(isset($this->session->userdata['role'])==1)
{
	$this->load->view('web/includes/header');
	$this->load->view('web/includes/sidebar');
	$this->load->view($theme . '/modules/' . $module . '/' . $page);
	$this->load->view('web/includes/footer');
}
else
{
	if (isset($this->session->userdata['admin_id'])) {   

    $this->load->view('admin/includes/header');
    $this->load->view('admin/includes/navbar');
    $this->load->view('admin/includes/sidebar');
    $this->load->view($theme . '/modules/admin/' . $module . '/' . $page);
    $this->load->view('admin/includes/footer');
	} else {     
	 
	   redirect(base_url().'admin/login');

	}

}
