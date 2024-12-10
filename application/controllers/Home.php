<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public $data;
    public $session;
    public $timezone;
    public $lang;
    public $language;
    public $profile;
    public $input;
    public $doctor;
    public $db;
    public $home;
    public $my_patients;
    public $product;
    public $lab;
    public $id;

    public function __construct() {

  parent::__construct();
  $this->data['theme'] = 'web';
  $this->data['module'] = 'home';
  $this->data['page'] = '';
  $this->data['base_url'] = base_url();
  $this->timezone = $this->session->userdata('time_zone');
  $this->session->set_userdata('_id', 1);
  if (!empty($this->timezone)) {
      date_default_timezone_set($this->timezone);
  }

  // $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):'english';
  $lan=default_language();
  $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):strtolower($lan['language']);
  $this->data['language'] = $this->lang->load('content', $lang, true);
  $this->language = $this->lang->load('content', $lang, true);
  $this->load->model('home_model', 'home');
  $this->load->model('profile_model', 'profile');
  $this->load->model('my_patients_model', 'my_patients');
  $this->load->model('product_model', 'product');
    }

    public function index() {
      $this->verifytokenmiddleware->handle();

  $this->data['page'] = 'index';
  $this->data['doctors'] = $this->home->get_doctors();
  $this->data['specialization'] = $this->home->get_specialization();
  // $this->data['blogs'] = $this->home->get_blogs();
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'] . '/template');
    }

    public function search_keywords() {
  $data = array();
  $response = array();
  $sdata = array();
  $sresult = array();
  $result = array();
  $search_keywords = $this->input->post('search_keywords');

  if (!empty($search_keywords)) {
      $doctor_list = $this->home->search_doctors($search_keywords);
      $specialization_list = $this->home->search_specialization($search_keywords);

      foreach ($specialization_list as $srows) {
    $sdata['specialization'] = $srows['specialization'];
    $sresult[] = $sdata;
      }


      if (!empty($doctor_list)) {
    foreach ($doctor_list as $rows) {

        $data['username'] = $rows['username'];
        $data['profileimage'] = (!empty($rows['profileimage'])) ? base_url() . $rows['profileimage'] : base_url() . 'assets/img/user.png';
        $data['first_name'] = ucfirst($rows['first_name']);
        $data['last_name'] = ucfirst($rows['last_name']);
        $data['speciality'] = ucfirst($rows['speciality']);
        $result[] = $data;
    }
      }
  }
  $response['specialist'] = $sresult;
  $response['doctor'] = $result;
  echo json_encode($response);
    }

    public function search_location() {
  $data = array();
  $response = array();
  $sdata = array();
  $sresult = array();
  $result = array();
  $search_location = $this->input->post('search_location');

  if (!empty($search_location)) {
      $location_list = $this->home->search_location($search_location);

      foreach ($location_list as $rows) {
    $data['location'] = $rows['city'];
    $result[] = $data;
      }
  }
  $response['location'] = $result;

  echo json_encode($response);
    }

    public function add_favourities() {
  $response = array();
  if (!empty($this->session->userdata('user_id'))) {
      $doctor_id = $this->input->post('doctor_id');
      $patient_id = $this->session->userdata('user_id');

      if (is_patient()) {

    $where = array('doctor_id' => $doctor_id, 'patient_id' => $patient_id);
    $already_favourities = $this->db->get_where('favourities', $where)->result_array();
    if (count($already_favourities) > 0) {
        $this->db->where('doctor_id', $doctor_id);
        $this->db->where('patient_id', $patient_id);
        $this->db->delete('favourities');
         $response['msg'] = $this->language['lg_favourities_rem'];

        $response['status'] = 201;
    } else {
        $data = array('doctor_id' => $doctor_id, 'patient_id' => $patient_id);
        $this->db->insert('favourities', $data);
        $response['msg'] = $this->language['lg_favourities_add'];
        $response['status'] = 200;
    }
      } else {
    $response['msg'] = $this->language['lg_invalid_login_p'];
    $response['status'] = 204;
      }
  } else {
      $response['msg'] = $this->language['lg_please_login_to'];
      $response['status'] = 204;
  }

  echo json_encode($response);
    }

  public function doctor_preview($username) {
    // die('test');
      $this->data['page'] = 'doctor_preview';
      $this->data['doctors'] = $this->home->get_doctor_details(urldecode($username));
      $this->data['login_role']=!empty($this->session->userdata('role'))?$this->session->userdata('role'):0;
      if (!empty($this->data['doctors'])) {
          $doctor_details = $this->home->get_doctor_details(urldecode($username));

          if ($doctor_details['price_type'] == 'Custom Price') {

        $user_currency = get_user_currency();
        $user_currency_code = $user_currency['user_currency_code'];
        $user_currency_rate = $user_currency['user_currency_rate'];

        $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $doctor_details['currency_code'];
        $rate_symbol = currency_code_sign($currency_option);

        if (!empty($this->session->userdata('user_id'))) {
            $rate = get_doccure_currency($doctor_details['amount'], $doctor_details['currency_code'], $user_currency_code);
        } else {
            $rate = $doctor_details['amount'];
        }
        $this->data['amount'] = $rate_symbol.number_format($rate,2,'.','');
          } elseif ($doctor_details['role'] != 4) {

        $this->data['amount'] = "Free";
          }

          $this->data['clinic_images'] = $this->clinic_images($this->data['doctors']['userid']);
          $this->data['education'] = $this->profile->get_education_details($this->data['doctors']['userid']);
          $this->data['experience'] = $this->profile->get_experience_details($this->data['doctors']['userid']);
          $this->data['awards'] = $this->profile->get_awards_details($this->data['doctors']['userid']);
          $this->data['memberships'] = $this->profile->get_memberships_details($this->data['doctors']['userid']);
          $this->data['registrations'] = $this->profile->get_registrations_details($this->data['doctors']['userid']);
          $this->data['business_hours'] = $this->profile->get_business_hours($this->data['doctors']['userid']);
          $this->data['monday_hours'] = $this->profile->get_monday_hours($this->data['doctors']['userid']);
          $this->data['sunday_hours'] = $this->profile->get_sunday_hours($this->data['doctors']['userid']);
          $this->data['tue_hours'] = $this->profile->get_tue_hours($this->data['doctors']['userid']);
          $this->data['wed_hours'] = $this->profile->get_wed_hours($this->data['doctors']['userid']);
          $this->data['thu_hours'] = $this->profile->get_thu_hours($this->data['doctors']['userid']);
          $this->data['fri_hours'] = $this->profile->get_fri_hours($this->data['doctors']['userid']);
          $this->data['sat_hours'] = $this->profile->get_sat_hours($this->data['doctors']['userid']);
          $this->data['reviews'] = $this->home->review_list_view($this->data['doctors']['userid']);
          // New
          $this->data['social_media'] = $this->profile->get_socialmedia_details($this->data['doctors']['userid']);
          if(!empty($this->data['social_media'])) {
            $this->data['facebook']=$this->data['social_media'][0]['facebook'];
            $this->data['twitter']=$this->data['social_media'][0]['twitter'];
            $this->data['instagram']=$this->data['social_media'][0]['instagram'];
            $this->data['pinterest']=$this->data['social_media'][0]['pinterest'];
            $this->data['linkedin']=$this->data['social_media'][0]['linkedin'];
            $this->data['youtube']=$this->data['social_media'][0]['youtube'];
          } else {
            $this->data['facebook']='';
            $this->data['twitter']='';
            $this->data['instagram']='';
            $this->data['pinterest']='';
            $this->data['linkedin']='';
            $this->data['youtube']='';
          }
          
          $this->load->vars($this->data);
          $this->load->view($this->data['theme'] . '/template');
      } else {
          $this->session->set_flashdata('error_message', $this->language['lg_oops_something_']);
          redirect(base_url() . 'home');
      }
    }
 public function clinic_preview($username) {
      $this->data['page'] = 'clinic_preview';
      $this->data['doctors'] = $this->home->get_clinic_details(urldecode($username));
      if (!empty($this->data['doctors'])) {
          $doctor_details = $this->home->get_clinic_details(urldecode($username));

          if ($doctor_details['price_type'] == 'Custom Price') {

        $user_currency = get_user_currency();
        $user_currency_code = $user_currency['user_currency_code'];
        $user_currency_rate = $user_currency['user_currency_rate'];

        $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $doctor_details['currency_code'];
        $rate_symbol = currency_code_sign($currency_option);

        if (!empty($this->session->userdata('user_id'))) {
            $rate = get_doccure_currency($doctor_details['amount'], $doctor_details['currency_code'], $user_currency_code);
        } else {
            $rate = $doctor_details['amount'];
        }
        $this->data['amount'] = $rate_symbol.number_format($rate,2,'.','');
          } else {

        $this->data['amount'] = "Free";
          }

          $this->data['clinic_images'] = $this->clinic_images($this->data['doctors']['userid']);
          $this->data['education'] = $this->profile->get_education_details($this->data['doctors']['userid']);
          $this->data['experience'] = $this->profile->get_experience_details($this->data['doctors']['userid']);
          $this->data['awards'] = $this->profile->get_awards_details($this->data['doctors']['userid']);
          $this->data['memberships'] = $this->profile->get_memberships_details($this->data['doctors']['userid']);
          $this->data['registrations'] = $this->profile->get_registrations_details($this->data['doctors']['userid']);
          $this->data['business_hours'] = $this->profile->get_business_hours($this->data['doctors']['userid']);
          $this->data['monday_hours'] = $this->profile->get_monday_hours($this->data['doctors']['userid']);
          $this->data['sunday_hours'] = $this->profile->get_sunday_hours($this->data['doctors']['userid']);
          $this->data['tue_hours'] = $this->profile->get_tue_hours($this->data['doctors']['userid']);
          $this->data['wed_hours'] = $this->profile->get_wed_hours($this->data['doctors']['userid']);
          $this->data['thu_hours'] = $this->profile->get_thu_hours($this->data['doctors']['userid']);
          $this->data['fri_hours'] = $this->profile->get_fri_hours($this->data['doctors']['userid']);
          $this->data['sat_hours'] = $this->profile->get_sat_hours($this->data['doctors']['userid']);
          $this->data['reviews'] = $this->home->review_list_view($this->data['doctors']['userid']);
          $this->load->vars($this->data);
          $this->load->view($this->data['theme'] . '/template');
      } else {
          $this->session->set_flashdata('error_message', $this->language['lg_oops_something_']);
          redirect(base_url() . 'home');
      }
    }
    public function doctors_search() {
    
  $this->data['page'] = 'doctors_search';
  $this->data['keywords'] = '';
  $this->data['city'] = '';
  $this->data['state'] = '';
  if (isset($_GET['keywords']) && !empty($_GET['keywords'])) {
      $this->data['keywords'] = urldecode($_GET['keywords']);
  }
  if (isset($_GET['location']) && !empty($_GET['location'])) {
      $location = $this->db->select('id,stateid')->where('city', urldecode($_GET['location']))->get('city')->row_array();
      $this->data['city'] = $location['id'];
  }
  $this->data['specialization'] = $this->home->get_specialization();
  $this->data['role'] = !empty($_GET['type'])?$_GET['type']:1;
  $this->data['login_role'] = !empty($this->session->userdata('role'))?$this->session->userdata('role'):0;
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'] . '/template');
    }

    public function doctors_searchmap() {

  $search_data = $this->input->post();
//print_r($page);
//exit;
  $this->data['page'] = 'doctors_mapsearch';
  $this->data['keywords'] = '';
  $this->data['city'] = '';
  $this->data['state'] = '';
  $this->data['search_data'] = isset($search_data) && !empty($search_data) ? $search_data : "";
  //$this->data['services'] = $this->profile->get_service_types_and_services();
  //$this->data['sub_services'] = $this->profile->get_all_sub_services();
  /* if (isset($_GET['keywords']) && !empty($_GET['keywords'])) {
    $this->data['keywords'] = urldecode($_GET['keywords']);
    }
    if (isset($_GET['location']) && !empty($_GET['location'])) {
    $location = $this->db->select('id,stateid')->where('city', urldecode($_GET['location']))->get('city')->row_array();
    $this->data['city'] = $location['id'];
    } */
  $this->data['role'] = !empty($_GET['type'])?$_GET['type']:1;
  $this->data['login_role'] = !empty($this->session->userdata('role'))?$this->session->userdata('role'):0;
  $this->data['specialization'] = $this->home->get_specialization();
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'] . '/template');
    }


    public function package_search() {
    
      $this->data['page'] = 'package_search';
      $this->data['keywords'] = '';
      $this->data['city'] = '';
      $this->data['state'] = '';
      if (isset($_GET['keywords']) && !empty($_GET['keywords'])) {
          $this->data['keywords'] = urldecode($_GET['keywords']);
      }
      if (isset($_GET['location']) && !empty($_GET['location'])) {
          $location = $this->db->select('id,stateid')->where('city', urldecode($_GET['location']))->get('city')->row_array();
          $this->data['city'] = $location['id'];
      }
      $this->data['specialization'] = $this->home->get_specialization();
      $this->data['role'] = !empty($_GET['type'])?$_GET['type']:1;
      $this->data['login_role'] = !empty($this->session->userdata('role'))?$this->session->userdata('role'):0;
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
        }

        public function search_package() {
          // die('gg');
                $response = $result = $lat = $long = $lat_long = array();
          
                $page = $this->input->post('page');
                $patient_lat = $this->input->post('s_lat');
                $patient_long = $this->input->post('s_long');
                $radius = $this->input->post('s_radius');
                $unit = $this->input->post('s_unit');
                $search_location = $this->input->post('s_location');
                $limit = ($unit != '' && $radius != '' && $search_location != '' && $patient_lat != '' && $patient_long != '') ? 100 : 5;
                
                $response['count'] = $this->home->search_package($page, $limit, 1);
                $response['limit'] = $limit;
                $package_list = $this->home->search_package($page, $limit, 2);
                // echo '<pre>'; // Optional: formats the output
                // print_r($package_list);
                // echo '</pre>';
                // die("dddsds");
          
                if (!empty($package_list)) {
                    foreach ($package_list as $rows) {
                      // print_r($rows);
                      $data['id'] = $rows['id'];
                      $data['package_name'] = $rows['package_name'];
          
                      // $data['profileimage'] = (!empty($rows['profileimage'])) ? base_url() . $rows['profileimage'] : base_url() . 'assets/img/user.png';
          
          
                  //         if (!empty($rows['profileimage']) && file_exists($rows['profileimage'])) {
                  // $data['profileimage'] = $rows['profileimage'];
                  //     } else {
                  // $data['profileimage'] = base_url().'assets/img/user.png';
                  //     }  
          
                      $data['description'] = ucfirst($rows['description']);
                      $data['destination'] = ucfirst($rows['destination']);
                      $data['currency'] = ucfirst($rows['currency']);
                      $data['package_image'] = $rows['package_image'];
                      $data['not_included'] = $rows['not_included'];
                      $data['add_on'] = $rows['add_on'];


                      $data['itinerary'] = ucfirst($rows['itinerary']);
                      $data['cost'] = ucfirst($rows['cost']);


                      //$data['clinicname'] = ucfirst($rows['clinicname']);
                      
          
                          //        if (!empty($rows['specialization_img']) && file_exists($rows['specialization_img'])) {
                          //     $data['specialization_img'] = $rows['specialization_img'];
                          // } else {
                          //     $data['specialization_img'] = base_url().'assets/img/user.png';
                          // }
          
          
                      $data['speciality'] = ($rows['speciality'])?ucfirst($rows['speciality']):'-';
                      $data['cityname'] = ($rows['cityname'])?$rows['cityname']:'';
                      $data['countryname'] = ($rows['countryname'])?$rows['countryname']:'';
                      // die("hhd");
                    
                      $data['services'] = $rows['services'];
                      $data['rating_value'] = $rows['rating_value'];
                      $data['rating_count'] = $rows['rating_count'];
                //$data['roleid'] = $rows['role'];
                
                if($rows['role'] == 1){
                  $data['clinicname'] = '';
                } else {
                  $data['clinicname'] = ($rows['clinicname'])?$rows['clinicname']:$data['first_name'].' '.$data['last_name'];
                }
                      $data['latitude'] = $this->latitude($rows['cityname'] . ' ' . $rows['countryname']);
                      $data['lat'] = $this->latitude($rows['cityname'] . ' ' . $rows['countryname']);
                      $data['latitude'] = $this->latitude($rows['cityname'] . ' ' . $rows['countryname']);
                      $data['longitude'] = $this->longitude($rows['cityname'] . ' ' . $rows['countryname']);
                      $data['clinic_images'] = json_encode($this->clinic_images($rows['user_id']));
                      if ($rows['price_type'] == 'Custom Price') {
                          $user_currency = get_user_currency();
                          $user_currency_code = $user_currency['user_currency_code'];
                          $user_currency_rate = $user_currency['user_currency_rate'];
                          $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $rows['currency_code'];
                          $rate_symbol = currency_code_sign($currency_option);
                          if (!empty($this->session->userdata('user_id'))) {
                        $rate = get_doccure_currency($rows['amount'], $rows['currency_code'], $user_currency_code);
                          } else {
                        $rate = $rows['amount'];
                          }
                          $data['amount'] = $rate_symbol . '' . $rate;
                      } else {
          
                          $data['amount'] = "Free";
                      }
                      $count = 0;// vijay added 20-07-2021
                      if ($unit != '' && $radius != '' && $search_location != '' && $patient_lat != '' && $patient_long != '') {
                          //$data['amount'] = ($rows['price_type'] == 'Custom Price') ? '$' . $rows['amount'] . ' per slot' : 'Free';
                          $data['distance'] = $this->getDistanceBetweenPointsNew($patient_lat, $patient_long, $data['latitude'], $data['longitude'], $unit);
                          if ($radius != '' && $radius > $data['distance']) {
                            $lat[] = $data['latitude'];
                            $long[] = $data['longitude'];
                            $result[] = $data;
                            $count = $count + 1;
                          }
                        } else {
                            $lat[] = $data['latitude'];
                            $long[] = $data['longitude'];
                            $result[] = $data;
                        }
                    }
                }
                // echo '<pre>';
                // print_r($result);
                // echo '</pre>';
                // die("dadsdsdsdsd");
                $response['current_page_no'] = $page;
                $response['total_page'] = ceil($response['count'] / $limit);
                $response['data'] = $result;
                //  echo '<pre>';
                // print_r($response);
                // echo '</pre>';
                // die("dadsdsdsdsd");
                echo json_encode($response);
              }

              public function service_search() {
    
                $this->data['page'] = 'service_search';
                $this->data['keywords'] = '';
                $this->data['city'] = '';
                $this->data['state'] = '';
                if (isset($_GET['keywords']) && !empty($_GET['keywords'])) {
                    $this->data['keywords'] = urldecode($_GET['keywords']);
                }
                if (isset($_GET['location']) && !empty($_GET['location'])) {
                    $location = $this->db->select('id,stateid')->where('city', urldecode($_GET['location']))->get('city')->row_array();
                    $this->data['city'] = $location['id'];
                }
                $this->data['specialization'] = $this->home->get_specialization();
                $this->data['role'] = !empty($_GET['type'])?$_GET['type']:1;
                $this->data['login_role'] = !empty($this->session->userdata('role'))?$this->session->userdata('role'):0;
                $this->load->vars($this->data);
                $this->load->view($this->data['theme'] . '/template');
                  }
                  
                  public function search_service() {
                    // die('gg');
                          $response = $result = $lat = $long = $lat_long = array();
                    
                          $page = $this->input->post('page');
                          $patient_lat = $this->input->post('s_lat');
                          $patient_long = $this->input->post('s_long');
                          $radius = $this->input->post('s_radius');
                          $unit = $this->input->post('s_unit');
                          $search_location = $this->input->post('s_location');
                          $limit = ($unit != '' && $radius != '' && $search_location != '' && $patient_lat != '' && $patient_long != '') ? 100 : 5;
                          
                          $response['count'] = $this->home->search_service($page, $limit, 1);
                          $response['limit'] = $limit;
                          $services_list = $this->home->search_service($page, $limit, 2);
                          // echo '<pre>'; // Optional: formats the output
                          // print_r($services_list);
                          // echo '</pre>';
                          // die("dddsds");
                    
                          if (!empty($services_list)) {
                              foreach ($services_list as $rows) {
                                // print_r($rows);
                                $data['id'] = $rows['id'];
                                $data['specialization_list'] = $rows['specialization_list'];
                    
                                // $data['profileimage'] = (!empty($rows['profileimage'])) ? base_url() . $rows['profileimage'] : base_url() . 'assets/img/user.png';
                    
                    
                            //         if (!empty($rows['profileimage']) && file_exists($rows['profileimage'])) {
                            // $data['profileimage'] = $rows['profileimage'];
                            //     } else {
                            // $data['profileimage'] = base_url().'assets/img/user.png';
                            //     }  
                    
                                $data['operation'] = ucfirst($rows['operation']);
                                $data['doctor_list'] = ucfirst($rows['doctor_list']);
                                $data['service_clinic'] = ucfirst($rows['service_clinic']);
                                $data['country'] = $rows['country'];
                                

          
                                $data['city'] = ucfirst($rows['city']);
                                $data['service_price'] = ucfirst($rows['service_price']);
          
          
                                //$data['clinicname'] = ucfirst($rows['clinicname']);
                                
                    
                                    //        if (!empty($rows['specialization_img']) && file_exists($rows['specialization_img'])) {
                                    //     $data['specialization_img'] = $rows['specialization_img'];
                                    // } else {
                                    //     $data['specialization_img'] = base_url().'assets/img/user.png';
                                    // }
                    
                    
                                $data['speciality'] = ($rows['speciality'])?ucfirst($rows['speciality']):'-';
                                $data['cityname'] = ($rows['cityname'])?$rows['cityname']:'';
                                $data['countryname'] = ($rows['countryname'])?$rows['countryname']:'';
                                // die("hhd");
                              
                                $data['services'] = $rows['services'];
                                $data['rating_value'] = $rows['rating_value'];
                                $data['rating_count'] = $rows['rating_count'];
                          //$data['roleid'] = $rows['role'];
                          
                          if($rows['role'] == 1){
                            $data['clinicname'] = '';
                          } else {
                            $data['clinicname'] = ($rows['clinicname'])?$rows['clinicname']:$data['first_name'].' '.$data['last_name'];
                          }
                                $data['latitude'] = $this->latitude($rows['cityname'] . ' ' . $rows['countryname']);
                                $data['lat'] = $this->latitude($rows['cityname'] . ' ' . $rows['countryname']);
                                $data['latitude'] = $this->latitude($rows['cityname'] . ' ' . $rows['countryname']);
                                $data['longitude'] = $this->longitude($rows['cityname'] . ' ' . $rows['countryname']);
                                $data['clinic_images'] = json_encode($this->clinic_images($rows['user_id']));
                                if ($rows['price_type'] == 'Custom Price') {
                                    $user_currency = get_user_currency();
                                    $user_currency_code = $user_currency['user_currency_code'];
                                    $user_currency_rate = $user_currency['user_currency_rate'];
                                    $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $rows['currency_code'];
                                    $rate_symbol = currency_code_sign($currency_option);
                                    if (!empty($this->session->userdata('user_id'))) {
                                  $rate = get_doccure_currency($rows['amount'], $rows['currency_code'], $user_currency_code);
                                    } else {
                                  $rate = $rows['amount'];
                                    }
                                    $data['amount'] = $rate_symbol . '' . $rate;
                                } else {
                    
                                    $data['amount'] = "Free";
                                }
                                $count = 0;// vijay added 20-07-2021
                                if ($unit != '' && $radius != '' && $search_location != '' && $patient_lat != '' && $patient_long != '') {
                                    //$data['amount'] = ($rows['price_type'] == 'Custom Price') ? '$' . $rows['amount'] . ' per slot' : 'Free';
                                    $data['distance'] = $this->getDistanceBetweenPointsNew($patient_lat, $patient_long, $data['latitude'], $data['longitude'], $unit);
                                    if ($radius != '' && $radius > $data['distance']) {
                                      $lat[] = $data['latitude'];
                                      $long[] = $data['longitude'];
                                      $result[] = $data;
                                      $count = $count + 1;
                                    }
                                  } else {
                                      $lat[] = $data['latitude'];
                                      $long[] = $data['longitude'];
                                      $result[] = $data;
                                  }
                              }
                          }
                          // echo '<pre>';
                          // print_r($result);
                          // echo '</pre>';
                          // die("dadsdsdsdsd");
                          $response['current_page_no'] = $page;
                          $response['total_page'] = ceil($response['count'] / $limit);
                          $response['data'] = $result;
                          //  echo '<pre>';
                          // print_r($response);
                          // echo '</pre>';
                          // die("dadsdsdsdsd");
                          echo json_encode($response);
                        }


    public function search_doctor() {
// die('gg');
      $response = $result = $lat = $long = $lat_long = array();

      $page = $this->input->post('page');
      $patient_lat = $this->input->post('s_lat');
      $patient_long = $this->input->post('s_long');
      $radius = $this->input->post('s_radius');
      $unit = $this->input->post('s_unit');
      $search_location = $this->input->post('s_location');
      $limit = ($unit != '' && $radius != '' && $search_location != '' && $patient_lat != '' && $patient_long != '') ? 100 : 5;
      
      $response['count'] = $this->home->search_doctor($page, $limit, 1);
      $response['limit'] = $limit;
      $doctor_list = $this->home->search_doctor($page, $limit, 2);
    // Print the doctor list in a readable format
// echo '<pre>'; // Optional: formats the output
// print_r($doctor_list);
// echo '</pre>';
// die("dds");

      if (!empty($doctor_list)) {
          foreach ($doctor_list as $rows) {
            // print_r($rows);
            $data['id'] = $rows['id'];
            $data['username'] = $rows['username'];

            // $data['profileimage'] = (!empty($rows['profileimage'])) ? base_url() . $rows['profileimage'] : base_url() . 'assets/img/user.png';


            if (!empty($rows['profileimage']) && file_exists($rows['profileimage'])) {
    $data['profileimage'] = $rows['profileimage'];
        } else {
    $data['profileimage'] = base_url().'assets/img/user.png';
        }

            $data['first_name'] = ucfirst($rows['first_name']);
            $data['last_name'] = ucfirst($rows['last_name']);
            //$data['clinicname'] = ucfirst($rows['clinicname']);
            

                       if (!empty($rows['specialization_img']) && file_exists($rows['specialization_img'])) {
                    $data['specialization_img'] = $rows['specialization_img'];
                } else {
                    $data['specialization_img'] = base_url().'assets/img/user.png';
                }


            $data['speciality'] = ($rows['speciality'])?ucfirst($rows['speciality']):'-';
            $data['cityname'] = ($rows['cityname'])?$rows['cityname']:'';
            $data['countryname'] = ($rows['countryname'])?$rows['countryname']:'';
            // die("hhd");
          
            $data['services'] = $rows['services'];
            $data['rating_value'] = $rows['rating_value'];
            $data['rating_count'] = $rows['rating_count'];
			//$data['roleid'] = $rows['role'];
      
			if($rows['role'] == 1){
				$data['clinicname'] = '';
			} else {
				$data['clinicname'] = ($rows['clinicname'])?$rows['clinicname']:$data['first_name'].' '.$data['last_name'];
			}
            $data['latitude'] = $this->latitude($rows['cityname'] . ' ' . $rows['countryname']);
            $data['lat'] = $this->latitude($rows['cityname'] . ' ' . $rows['countryname']);
            $data['latitude'] = $this->latitude($rows['cityname'] . ' ' . $rows['countryname']);
            $data['longitude'] = $this->longitude($rows['cityname'] . ' ' . $rows['countryname']);
            $data['clinic_images'] = json_encode($this->clinic_images($rows['user_id']));
            if ($rows['price_type'] == 'Custom Price') {
                $user_currency = get_user_currency();
                $user_currency_code = $user_currency['user_currency_code'];
                $user_currency_rate = $user_currency['user_currency_rate'];
                $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $rows['currency_code'];
                $rate_symbol = currency_code_sign($currency_option);
                if (!empty($this->session->userdata('user_id'))) {
              $rate = get_doccure_currency($rows['amount'], $rows['currency_code'], $user_currency_code);
                } else {
              $rate = $rows['amount'];
                }
                $data['amount'] = $rate_symbol . '' . $rate;
            } else {

                $data['amount'] = "Free";
            }
            $count = 0;// vijay added 20-07-2021
            if ($unit != '' && $radius != '' && $search_location != '' && $patient_lat != '' && $patient_long != '') {
                //$data['amount'] = ($rows['price_type'] == 'Custom Price') ? '$' . $rows['amount'] . ' per slot' : 'Free';
                $data['distance'] = $this->getDistanceBetweenPointsNew($patient_lat, $patient_long, $data['latitude'], $data['longitude'], $unit);
                if ($radius != '' && $radius > $data['distance']) {
                  $lat[] = $data['latitude'];
                  $long[] = $data['longitude'];
                  $result[] = $data;
                  $count = $count + 1;
                }
              } else {
                  $lat[] = $data['latitude'];
                  $long[] = $data['longitude'];
                  $result[] = $data;
              }
          }
      }
      $response['current_page_no'] = $page;
      $response['total_page'] = ceil($response['count'] / $limit);
      $response['data'] = $result;
      echo json_encode($response);
    }

    public function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'Mi') {
  $theta = $longitude1 - $longitude2;
  $distance = sin(deg2rad($latitude1)) * sin(deg2rad($latitude2)) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta));

  $distance = acos($distance);
  $distance = rad2deg($distance);
  $distance = $distance * 60 * 1.1515;

  switch ($unit) {
      case 'Mi': break;
      case 'Km' : $distance = $distance * 1.609344;
  }

  return (round($distance, 2));
    }

    public function clinic_images($id) {

  $this->db->select('clinic_image,user_id');
  $this->db->where('user_id', $id);
  $result = $this->db->get('clinic_images')->result_array();
  return $result;
    }

    public function latitude($address) {

  $address = str_replace(" ", "+", $address);

  $google_map_api = settings('google_map_api');

  $url = "https://maps.google.com/maps/api/geocode/json?key=" . $google_map_api . "&address=$address&sensor=false";

  /*$url = "https://maps.google.com/maps/api/geocode/json?key=AIzaSyA_QD2_rlwEFGhCK0oj2n6cixsvX0D3zgk&address=$address&sensor=false"; */

  $ch = curl_init(); //initiating curl
  curl_setopt($ch, CURLOPT_URL, $url); // CALLING THE URL
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  $response = curl_exec($ch);

  $response = json_decode($response);
  if (@$response->status == 'OK') {
      $lat = $response->results[0]->geometry->location->lat;
      $lng = $response->results[0]->geometry->location->lng;

      return $lat;
  }
    }

    public function longitude($address) {

  $address = str_replace(" ", "+", $address);

  $google_map_api = settings('google_map_api');

  $url = "https://maps.google.com/maps/api/geocode/json?key=" . $google_map_api . "&address=$address&sensor=false";

  /* $url = "https://maps.google.com/maps/api/geocode/json?key=AIzaSyA_QD2_rlwEFGhCK0oj2n6cixsvX0D3zgk&address=$address&sensor=false"; */

  $ch = curl_init(); //initiating curl
  curl_setopt($ch, CURLOPT_URL, $url); // CALLING THE URL
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  $response = curl_exec($ch);

  $response = json_decode($response);
  if (@$response->status == 'OK') {
      $lat = $response->results[0]->geometry->location->lat;
      $lng = $response->results[0]->geometry->location->lng;

      return $lng;
  }
    }

    public function patients_search() {
  $this->data['page'] = 'patients_search';
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'] . '/template');
    }

    public function search_patient() {

  $response = array();
  $result = array();
  $page = $this->input->post('page');
  $limit = 2;
  $response['count'] = $this->home->search_patient($page, $limit, 1);
  $patient_list = $this->home->search_patient($page, $limit, 2);

  if (!empty($patient_list)) {
      foreach ($patient_list as $rows) {

    $data['id'] = $rows['id'];
    $data['user_id'] = $rows['user_id'];
    $data['username'] = $rows['username'];
    $data['profileimage'] = (!empty($rows['profileimage'])) ? base_url() . $rows['profileimage'] : base_url() . 'assets/img/user.png';
    $data['first_name'] = ucfirst($rows['first_name']);
    $data['last_name'] = ucfirst($rows['last_name']);
    $data['mobileno'] = $rows['mobileno'];
    $data['dob'] = $rows['dob'];
    $data['age'] = age_calculate($rows['dob']);
    $data['blood_group'] = $rows['blood_group'];
    $data['gender'] = $rows['gender'];
    $data['cityname'] = $rows['cityname'];
    $data['countryname'] = $rows['countryname'];
    $result[] = $data;
      }
  }
  $response['current_page_no'] = $page;
  $response['total_page'] = ceil($response['count'] / $limit);
  $response['data'] = $result;

  echo json_encode($response);
    }

    public function terms_conditions() {

  $this->data['page'] = 'terms_conditions';
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'] . '/template');
    }

    public function privacy_policy() {

  $this->data['page'] = 'privacy_policy';
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'] . '/template');
    }

  public function patient_preview($patient_id) {
    $this->data['page'] = 'patient_preview';
    $this->data['patient'] = $this->my_patients->get_patient_details(base64_decode($patient_id));
    $this->data['patient_id'] = base64_decode($patient_id);
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

// PHARMACY

public function pharmacy_search_bydoctor() {

  $this->data['page'] = 'pharmacy_search_bydoctor';
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'] . '/template');
}

public function pharmacy_preview($pharmacy_id=''){
  $pharmacy_id = base64_decode($pharmacy_id);
  $this->data['pharmacy'] = $this->my_patients->get_selected_pharmacy_details($pharmacy_id);
  $this->data['page'] = 'pharmacy_preview';
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'] . '/template');
}

public function search_pharmacy() {

  $response = array();
  $result = array();
  $page = $this->input->post('page');
  $limit = 10;
  $response['count'] = $this->my_patients->search_pharmacy($page, $limit, 1);
  $pharmacy_list = $this->my_patients->search_pharmacy($page, $limit, 2);

  if (!empty($pharmacy_list)) {
      foreach ($pharmacy_list as $rows) {

    $data['id'] = $rows['pharmacy_id'];
    $data['pharmacy_name'] = (!empty($rows['pharmacy_name'])) ? ucfirst($rows['pharmacy_name']) : ucfirst($rows['first_name']) . ' ' . $rows['last_name'];
    $data['profileimage'] = (!empty($rows['profileimage'])) ? base_url() . $rows['profileimage'] : base_url() . 'assets/img/user.png';
    $data['phonecode'] = $rows['phonecode'];
    $data['mobileno'] = $rows['mobileno'];
    $data['address1'] = $rows['address1'];
    $data['address2'] = $rows['address2'];
    $data['city'] = $rows['city'];
    $data['statename'] = $rows['statename'];
    $data['country'] = $rows['country'];
    $data['pharmacy_opens_at'] = date('g:iA', strtotime($rows['pharamcy_opens_at']));
    $result[] = $data;
      }
  }
  $response['current_page_no'] = $page;
  $response['total_page'] = ceil($response['count'] / $limit);
  $response['data'] = $result;
  echo json_encode($response);
    }

  public function get_phamacy_details() {
  $pharmacy_id = $this->input->post('pharmacy_id');
  $data = $get_pharmacy_details = $this->my_patients->get_selected_pharmacy_details($pharmacy_id);
  if (!empty($get_pharmacy_details)) {
      $response['msg'] = '';
      $response['status'] = 200;
      $response['data'] = $data;
  } else {
      $response['msg'] = "Pharmacy details not available";
      $response['status'] = 500;
      $response['data'] = $data;
  }
  echo json_encode($response);
    }

  public function view_pharmacy_products($pharmacy_id='') {
	
	//if(empty($this->session->userdata('user_id'))){
	  // redirect(base_url().'signin');
	//}
    $user_currency=get_user_currency();
    $user_currency_code=$user_currency['user_currency_code'];
    $user_currency_rate=$user_currency['user_currency_sign'];      
    $data['pharmacy_id'] = $pharmacy_id;
    $phardecodeId = base64_decode($pharmacy_id);
    $this->session->set_userdata('pharmacy_id', $phardecodeId);
    $this->data['page'] = 'products_list_by_pharmacy';
	  //die('dd');
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
      
  }

  public function get_products() {

    $user_currency=get_user_currency();
    $user_currency_code=$user_currency['user_currency_code'];
    $user_currency_sign=$user_currency['user_currency_sign'];

    $response = array();
    $result = array();
    $page = $this->input->post('page');

    $pharmacy_id = $this->input->post('pharmacy_id');

    $limit=12;
    $response['count'] = $this->product->get_products_by_pharmacy_filter($pharmacy_id, $page, $limit, 1);
    $product_list = $this->product->get_products_by_pharmacy_filter($pharmacy_id, $page, $limit, 2); 

    // echo $this->db->last_query();die;

    if (!empty($product_list)) {

      $response['category_name'] = $product_list[0]['category_name'];
      foreach ($product_list as $rows) {
        $image_url = explode(',', $rows['upload_image_url']);

        $data['id'] = $rows['id'];

        $data['user_currency_code']=$user_currency_code;
        $data['user_currency_sign']=$user_currency_sign;
                
        $data['productid'] = md5($rows['id']);
        $data['name'] = $rows['name'];
        $data['slug'] = $rows['slug'];
        $data['product_image'] = file_exists($image_url[0])?base_url() . $image_url[0]:base_url() . 'assets/img/no-image.png';
        $price = $rows['sale_price'];
        $sale_price = $rows['price'];

        if(!empty($this->session->userdata('user_id'))){
          $sale_price= get_doccure_currency(round($rows['price']),$rows['pharmacy_currency'],$user_currency_code);
          $price= get_doccure_currency(round($rows['sale_price']),$rows['pharmacy_currency'],$user_currency_code);
        }

        $data['price'] = number_format($price,2,'.','');
        $data['sale_price'] = number_format($sale_price,2,'.','');


        $data['unit'] = $rows['unit_value'] . $rows['unit_name'];
        $result[] = $data;
      }
    }
    
    $response['current_page_no'] = $page;
    $response['total_page'] = ceil($response['count'] / $limit);
    $response['data'] = $result;

    echo json_encode($response);
  }

  public function mywarmembrace_search() {
    // die("dd");
    $this->data['page'] = 'mywarmembrace_search';
    $this->data['keywords'] ='';
    $this->data['city'] ='';
    $this->data['state'] ='';
    $this->data['_id'] = $this->session->userdata('_id');
    // echo '<pre>';
    // print_r($this->data);
    // echo '</pre>';
    // die("ddd");
    //for showing menu
    if(isset($_GET['keywords']) && !empty($_GET['keywords']))
    {
        $this->data['keywords'] = urldecode($_GET['keywords']);
    }
    if(isset($_GET['location']) && !empty($_GET['location']))
    {
        $location=$this->db->select('id,stateid')->where('city',urldecode($_GET['location']))->get('city')->row_array();
        $this->data['city'] = $location['id'];
        $this->data['state'] = $location['stateid'];
    }
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');
  }

  public function labs_search() {
    // die("dd");
    $this->data['page'] = 'labs_searchmap';
    $this->data['keywords'] ='';
    $this->data['city'] ='';
    $this->data['state'] ='';
    if(isset($_GET['keywords']) && !empty($_GET['keywords']))
    {
        $this->data['keywords'] = urldecode($_GET['keywords']);
    }
    if(isset($_GET['location']) && !empty($_GET['location']))
    {
        $location=$this->db->select('id,stateid')->where('city',urldecode($_GET['location']))->get('city')->row_array();
        $this->data['city'] = $location['id'];
        $this->data['state'] = $location['stateid'];
    }
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');
  }
    
  public function search_lab() {

    $response = $result = $lat = $long = $lat_long = array();

   // $response=array();
   $patient_lat = $this->input->post('s_lat');
  $patient_long = $this->input->post('s_long');
  $radius = $this->input->post('s_radius');
  $unit = $this->input->post('s_unit');
  $search_location = $this->input->post('s_location');
  $limit = ($unit != '' && $radius != '' && $search_location != '' && $patient_lat != '' && $patient_long != '') ? 100 : 3;
 
    $result=array();
    $page=$this->input->post('page');
    $limit=5;
    $response['count'] =$this->home->search_lab($page,$limit,1);
    $doctor_list = $this->home->search_lab($page,$limit,2);

    if (!empty($doctor_list)) {
      foreach ($doctor_list as $rows) {

        $data['id']=$rows['id'];
        $data['username']=$rows['username'];
        $data['profileimage']=(!empty($rows['profileimage']))?base_url().$rows['profileimage']:base_url().'assets/img/user.png';
        $data['first_name']=ucfirst($rows['first_name']);
        $data['last_name']=ucfirst($rows['last_name']);
        $data['cityname']=$rows['cityname'];
        $data['countryname']=$rows['countryname'];
        $result[]=$data;
      }
    }
    $response['current_page_no']= $page;
    $response['total_page']= ceil($response['count']/$limit);
    $response['data']= $result;

    echo json_encode($response);
  }


  public function lab_tests($username) {
	if(empty($this->session->userdata('user_id'))){
	   redirect(base_url().'signin');
	}

    $user_detail=user_detail($this->session->userdata('user_id'));
    if($user_detail['is_updated']=='0' || $user_detail['is_verified']=='0') {
      $this->session->set_flashdata('error_message',$this->language['lg_please_update_p']);
          redirect(base_url().'dashboard');
    }
    else
    {
      $this->data['lab_details'] = $lab_details=$this->home->get_lab_details(urldecode($username));
      $this->data['lab_name'] = $this->data['lab_tests'] = '';
      if(!empty($lab_details)){
        $this->load->model('lab_model','lab');
        $lab_id = $lab_details['user_id'];
        $this->data['lab_name'] = $lab_details['first_name'].' '.$lab_details['last_name'];
        $this->data['lab_tests'] = $this->lab->get_lab_test_by_lab_id($lab_id);
      }
      $this->data['page'] = 'lab_tests_preview';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }

  }

}
