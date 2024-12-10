<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VerifyTokenMiddleware {

    protected $CI;
    protected $db;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('session');
        $this->CI->load->database(); // Load database library for querying

        // Optionally load your user model if needed
        // $this->CI->load->model('User_model');
    }

    public function handle() { 

        // Check if user is already logged in via session
        if ($this->CI->session->userdata('user_id')) {
            // User is already logged in, no need to continue
            return;
        }
    
        // If not logged in via session, proceed with token handling
        $token = $this->CI->input->cookie('token');

        // Check if the token exists and the role is set
        if ($token && $this->CI->input->get('role')) {
            // Replace with your actual API endpoint for token verification
            $response = $this->verifyToken($token);

            // Check if the request was successful (status code 200)
            if ($response !== false) {
                $userData = json_decode($response, true); // Decode JSON response

                // Extract the user's email from the response
                $userEmail = $userData['email'];

                // Query the database to find the user based on email
                $user = $this->CI->db->get_where('users', ['email' => $userEmail])->row();

                if ($user) {
                    $session_data = array('user_id' => $user->id, 'role' => $user->role);
                    $this->CI->session->set_userdata($session_data); 
                }
            }
        }
    }

    public function verifyToken($token) { 
        // $url = 'http://127.0.0.1:8000/api/verify-token'; // Replace with your API endpoint
        $url = 'https://cms.dibestspot.com/api/verify-token'; // Replace with your API endpoint

        // Construct the body data
        $body = [
            'role' => $this->CI->input->get('role')
        ];

        // Encode the body data as JSON
        $bodyJson = json_encode($body);

        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
            'Content-Type: application/json', // Specify content type as JSON
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyJson); // Set the body data

        // Execute cURL session
        $response = curl_exec($ch); 
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close cURL session
        curl_close($ch);

        // Handle response
        if ($httpCode == 200) {
            return $response; // Successful response
        } else {
            return false; // Failed request
        }

    }
}
