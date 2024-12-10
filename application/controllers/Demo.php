<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Demo extends CI_Controller {

		public $sendemail;

	public function __construct(){

		parent::__construct();

	}

	public function mail(){

		$this->load->library('sendemail');
    	$this->sendemail->email_test('naveenkumar.t@dreamguys.co.in');

	}

}