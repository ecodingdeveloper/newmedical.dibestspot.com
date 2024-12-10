<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_payment_update_model extends CI_Model {

    public function __construct() {
        // Load the database library
        $this->load->database();
    }

   

	public function update_payment($invoice_id, $payment_status) {
    // Create an associative array with the column you want to update
    $data = array(
        'payment_status' => $payment_status
    );

    // Set the condition for the update
    $this->db->where('id', $invoice_id);

    // Perform the update on the 'commission' table
    return $this->db->update('payments', $data);
}

    // Delete a commission record
   /* public function delete_commission($comm_id) {
        $this->db->where('comm_id', $comm_id);
        return $this->db->delete('commission');
    }*/
}
