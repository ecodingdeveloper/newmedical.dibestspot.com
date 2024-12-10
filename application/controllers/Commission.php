<?php
/*
defined('BASEPATH') OR exit('No direct script access allowed');

class Commission extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load the Commission model
        $this->load->model('Commission_model');
    }

    // Display a single commission record
    public function view($comm_id) {
        $data['commission'] = $this->Commission_model->get_commission($comm_id);
        
        // Check if commission exists
        if (empty($data['commission'])) {
            show_404(); // Show a 404 error page if commission not found
        }
        
        // Load the view
        $this->load->view('commission_view', $data);
    }

    // Display all commission records
    public function index() {
        $data['commissions'] = $this->Commission_model->get_all_commissions();
        
        // Load the view
        $this->load->view('commissions_list', $data);
    }

    // Load the view to create a new commission record
    public function create() {
        $this->load->view('create_commission');
    }

    // Insert a new commission record into the database
    public function store() {
        $data = array(
            'doc_id' => $this->input->post('doc_id'),
            'comm_rate' => $this->input->post('comm_rate')
        );

        $this->Commission_model->insert_commission($data);
        redirect('commission/index'); // Redirect to the list of commissions
    }

    // Load the view to edit an existing commission record
    public function edit($comm_id) {
        $data['commission'] = $this->Commission_model->get_commission($comm_id);

        if (empty($data['commission'])) {
            show_404();
        }

        $this->load->view('edit_commission', $data);
    }

    // Update the commission record in the database
    public function update() {
        $comm_id = $this->input->post('comm_id');
        $data = array(
            'doc_id' => $this->input->post('doc_id'),
            'comm_rate' => $this->input->post('comm_rate')
        );

        $this->Commission_model->update_commission($comm_id, $data);
        redirect('commission/index');
    }

    // Delete a commission record
    public function delete($comm_id) {
        $this->Commission_model->delete_commission($comm_id);
        redirect('commission/index');
    }
}*/
