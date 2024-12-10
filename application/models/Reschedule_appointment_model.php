<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reschedule_appointment_model extends CI_Model {

    public function __construct() {
        // Load the database library
        $this->load->database();
    }

    public function get_appointment_by_id($appointment_id) {
        $query = $this->db->get_where('appointments', array('id' => $appointment_id));
        return $query->row(); // Returns a single row object
    }


    public function get_payment_method_by_appointment_id($appointment_id) {
        $this->db->select('payment_method');
        $this->db->from('appointments');
        $this->db->where('id', $appointment_id);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->row()->payment_method; // Returns the payment_method value
        } else {
            return null; // Or handle the case where no appointment is found
        }
    }

	public function update_approved_status($appointment_id) {
    // Create an associative array with the column you want to update
    $data = array(
        'approved' => 3
    );

    // Set the condition for the update
    $this->db->where('id', $appointment_id);

    // Perform the update on the 'commission' table
    return $this->db->update('appointments', $data);
}

    // Delete a commission record
    public function delete_appointment($appointment_id) {
         $this->db->where('id', $appointment_id);
       return $this->db->delete('appointments');
     }
}
