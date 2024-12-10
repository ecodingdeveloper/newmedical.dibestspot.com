<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commission_pat_model extends CI_Model {

    public function __construct() {
        // Load the database library
        $this->load->database();
    }

    // Fetch a single commission record based on comm_id
    /*public function get_commission($comm_id) {
        $query = $this->db->get_where('commission', array('comm_id' => $comm_id));
        return $query->row(); // Returns a single row object
    }*/
	
	public function get_commission_by_pat_id($pat_id) {
        $query = $this->db->get_where('commission_pat', array('pat_id' => $pat_id));
        return $query->row(); // Returns a single row object
    }

    // Fetch all commission records
   /* public function get_all_commissions() {
        $query = $this->db->get('commission');
        return $query->result(); // Returns an array of objects
    }*/

    // Insert a new commission record
    public function insert_commission($pat_id, $comm_rate) {
		$data = array(
        'pat_id' => $pat_id,
        'comm_rate' => $comm_rate
    );
        return $this->db->insert('commission_pat', $data);
    }

    // Update an existing commission record
    /*public function update_commission_($phar_id, $data) {
        $this->db->where('doc_id', $doc_id);
        return $this->db->update('commission', $data);
    }*/

	public function update_commission($pat_id, $comm_rate) {
    // Create an associative array with the column you want to update
    $data = array(
        'comm_rate' => $comm_rate
    );

    // Set the condition for the update
    $this->db->where('pat_id', $pat_id);

    // Perform the update on the 'commission' table
    return $this->db->update('commission_pat', $data);
}

    // Delete a commission record
   /* public function delete_commission($comm_id) {
        $this->db->where('comm_id', $comm_id);
        return $this->db->delete('commission');
    }*/
}
