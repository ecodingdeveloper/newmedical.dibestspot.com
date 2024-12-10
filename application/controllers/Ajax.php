<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {

   public $data;
   public $session;
   public $timezone;
   public $input;
   public $db;
   public $uri;

  public function __construct() {

    parent::__construct();
    $this->data['theme']     = 'web';
    $this->data['module']    = 'home';
    $this->data['page']     = '';
    $this->data['base_url'] = base_url();
    $this->load->model('doctor_model','doctor');
	$this->load->model('commission_model', 'commission');
  $this->load->model('commission_phar_model','commission_phar');
  $this->load->model('commission_clinic_model','commission_clinic');
  $this->load->model('commission_lab_model','commission_lab');
  $this->load->model('commission_pat_model','commission_pat');
  $this->load->model('invoice_payment_update_model','invoice_payment_update');
  $this->load->model('reschedule_appointment_model','reschedule_appointment');



    $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
    }



  }

  public function get_hospital_doctor()
  {
    $user_id = $this->session->userdata('user_id'); 
    $list = $this->doctor->get_datatables_verfied_doctors($user_id);
   
    $json=array();
    if(!empty($list)) {
      foreach ($list as $rows) {
        $data['value']=$rows['id'];
        $data['label']=$rows['username'];
        $json[]=$data;
      }
    }
    echo json_encode($json);
  }
  
  public function update_user_status(){

    if($this->session->userdata('user_id')){
    $user_id=$this->session->userdata('user_id');
    $data=array(
            'date_time'=>date('Y-m-d H:i:s'),
            'time_zone'=>$this->timezone,
            'user_id'=>$user_id
    );
    $count=$this->db->where('user_id',$user_id)->get('user_online_status')->num_rows();
     
     if($count > 0){

      $this->db->where('user_id',$user_id);
      $this->db->update('user_online_status',$data);

     }else{

      
        $this->db->insert('user_online_status',$data);
     }
   }

  }

  Public function set_timezone()
  {
    if(isset($_REQUEST['timezone'])){
      $array = array('time_zone' => $_REQUEST['timezone'],
    );
      $this->session->set_userdata( $array );
      echo json_encode($array);

    }
  }


  Public function set_language()
  {
    if(isset($_REQUEST['lang'])){
      $array = array(
        'lang' => $_REQUEST['lang'],
        'language' => $_REQUEST['language'],
      );
      $this->session->set_userdata( $array );
      echo json_encode($array);

    }
  }


  public function get_country_code()
  {
    $result=$this->db->get('country')->result_array();
    $json=array();
    foreach ($result as $rows) {
      $data['value']=$rows['phonecode'];
      $data['label']=$rows['country'].'(+'.$rows['phonecode'].')';
      $json[]=$data;
    }

    echo json_encode($json);
  }

       public function get_users()
    {

       
        $result=$this->db->get('users')->result_array();
        $json=array();
       foreach ($result as $rows) {
          $data['value']=$rows['id'];
          $data['label']=$rows['first_name'];
          $json[]=$data;
       }

       get_users_details($json); 
    }

  public function get_country()
  {
    $result=$this->db->get('country')->result_array();
    $json=array();
    foreach ($result as $rows) {
      $data['value']=$rows['countryid'];
      $data['label']=$rows['country'];
      $json[]=$data;
    }

    echo json_encode($json);
  }

  public function get_state()
  {

    $where=array('countryid' =>$_POST['id']);
    $result=$this->db->get_where('state',$where)->result_array();
    $json=array();
    foreach ($result as $rows) {
      $data['value']=$rows['id'];
      $data['label']=$rows['statename'];
      $json[]=$data;
    }

    echo json_encode($json);

  }

  public function get_city()
  {
    $where=array('stateid' =>$_POST['id']);
    $result=$this->db->get_where('city',$where)->result_array();
    $json=array();
    foreach ($result as $rows) {
      $data['value']=$rows['id'];
      $data['label']=$rows['city'];
      $json[]=$data;
    }

    echo json_encode($json);
  }
  
   public function get_city_of_country()
  {
		
	$where=array('country' =>$_POST['country']);
    $result_country=$this->db->get_where('country',$where)->result_array();
	foreach($result_country as $row){
	 $countryid=$row['countryid'];
	 }
	$this->db->select('c.city');
    $this->db->from('city c');
    $this->db->join('state s', 's.id = c.stateid', 'inner'); 
	$this->db->where('s.countryid',$countryid);
	$this->db->order_by('c.city','ASC');
    $query = $this->db->get();
   $result =  $query->result_array(); 
	 //$where=array('countryid' =>101);
    //$result=$this->db->get_where('state',$where)->result_array();
 
	
    $json=array();
    foreach ($result as $rows) {
      $data['value']=$rows['city'];
      $data['label']=$rows['city'];
      $json[]=$data;
    }

    echo json_encode($json);
  }
 
  public function get_specialization()
  {
    $where=array('status' =>1);
    $result=$this->db->get_where('specialization',$where)->result_array();
    $json=array();
    foreach ($result as $rows) {
      $data['id']=$rows['id'];
      $data['value']=$rows['specialization'];
      $data['label']=$rows['specialization'];
      $json[]=$data;
    }

    echo json_encode($json);
  }
  public function get_doctor_list()
  {
    $where=array('role' =>1);
    $result=$this->db->get_where('users',$where)->result_array();
    // echo '<pre>';
    // print_r($result);
    // echo '</pre>';
    // die("dfsa");
    $json=array();
    foreach ($result as $rows) {
      $data['id']=$rows['id'];
      $data['value']=$rows['first_name'].' '.$rows['last_name'];

      $data['label']=$rows['first_name'].' '.$rows['last_name'];
      $json[]=$data;
    }

    echo json_encode($json);
  }

  public function get_category()
  {
    $where=array('status' =>1);
    $result=$this->db->get_where('categories',$where)->result_array();
    $json=array();
    foreach ($result as $rows) {
      $data['value']=$rows['id'];
      $data['label']=$rows['category_name'];
      $json[]=$data;
    }

    echo json_encode($json);
  }

  public function get_subcategory()
  {

    $where=array('category' =>$_POST['id']);
    $result=$this->db->get_where('subcategories',$where)->result_array();
    $json=array();
    foreach ($result as $rows) {
      $data['value']=$rows['id'];
      $data['label']=$rows['subcategory_name'];
      $json[]=$data;
    }

    echo json_encode($json);

  }



  public function currency_rate(){


    $sql="SELECT * from currency_rate"; 

    $count_row=$this->db->query($sql)->num_rows(); 

    if($count_row == 0){

           $this->currency_rate_update();
    }else{

      $row=$this->db->query($sql)->row_array(); 
      $date=date('Y-m-d H:i:s');

      if($row['updated_at'] < $date ){

         $this->currency_rate_update();
      }

    }



  }

  public function currency_rate_update(){

    $req_url = 'https://v6.exchangerate-api.com/v6/cc726126438b5513e5a41f69/latest/USD';
    $response_json = file_get_contents($req_url);

    // Continuing if we got a result
    if(false !== $response_json) {

    // Try/catch for json_decode operation
      try {

    // Decoding
        $response = json_decode($response_json);

    // Check for success
        if('success' === $response->result) {

          foreach ($response->conversion_rates as $key => $value) {
            $count=$this->db->where('currency_code',$key)->from('currency_rate')->count_all_results();  
            if($count==0){
              $data=array(
                'currency_code'=>$key,
                'rate'=>$value,
                'created_at'=>date('Y-m-d H:i:s')
              );
              $this->db->insert('currency_rate',$data);
            }else{
              $data=array(
                'rate'=>$value,
                'updated_at'=>date('Y-m-d H:i:s')
              );
              $this->db->where('currency_code',$key)->update('currency_rate',$data);
            }

          }
          echo "success";


        }

      }
      catch(Exception $e) {
        echo 'Caught exception: ',  $e->getMessage();
      }

    }



  }


  public function add_user_currency(){
    $params=$this->input->post();
    if(!empty($params['code'])){
      $user_id=$this->session->userdata('user_id');
	  
	  $user_detail=user_detail($this->session->userdata('user_id'));
	  if($user_detail['is_updated']=='0') {
		  echo json_encode(['success'=>false, 'msg' => 'Please update profile']);exit;
	  }

      $where=array('user_id' =>$user_id);
      $user_details=$this->db->get_where('users_details',$where)->row_array();
      $current_amt=get_doccure_currency($user_details['amount'],$user_details['currency_code'],$params['code']);
      
      $result=$this->db->where('user_id',$user_id)->update('users_details',['currency_code'=>$params['code'],'amount'=>$current_amt]);

      if($result==true){
        echo json_encode(['success'=>true, 'msg' => 'success']);exit;
      }else{
        echo json_encode(['success'=>false, 'msg' => 'please try again']);exit;
      }

    }
  }


    public function get_unit()
  {
      $where=array('status' =>1);
      $result=$this->db->get_where('unit',$where)->result_array();
      $json=array();
     foreach ($result as $rows) {
        $data['value']=$rows['id'];
        $data['label']=$rows['unit_name'];
        $json[]=$data;
     }

     echo json_encode($json);
  }

  public function get_product_category()
  {
      $where=array('status' =>1);
      $result=$this->db->get_where('product_categories',$where)->result_array();
      $json=array();
     foreach ($result as $rows) {
        $data['value']=$rows['id'];
        $data['label']=$rows['category_name'];
        $json[]=$data;
     }

     echo json_encode($json);
  }
  public function get_specialization_list()
  {
      $where=array('status' =>1);
      $result=$this->db->get_where('product_categories',$where)->result_array();
      $json=array();
     foreach ($result as $rows) {
        $data['value']=$rows['id'];
        $data['label']=$rows['category_name'];
        $json[]=$data;
     }

     echo json_encode($json);
  }
  
  public function get_product_subcategory()
  {

      
     $where=array('category' =>$_POST['id']);
     $result=$this->db->get_where('product_subcategories',$where)->result_array();
     $json=array();
     foreach ($result as $rows) {
        $data['value']=$rows['id'];
        $data['label']=$rows['subcategory_name'];
        $json[]=$data;
     }
     echo json_encode($json);     
   }


/*public function update_commission(){
	

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $rate = $_POST['rate'];

    if ($id && $rate) {
		
		
		$result = $this->commission->get_commission_by_doc_id($doctor['id']);
		

    if ($result) {
        if ($result->num_rows > 0) {
			
			 $this->update_commission($id, $rate);

			// Execute the query
			if ($conn->query($query1) === TRUE) {
				// Success message
			echo json_encode(['status' => 'success', 'message' => 'Record updated successfully']);
			} else {
			// Error message
			echo json_encode(['status' => 'error', 'message' => 'Error updating record: ' . $conn->error]);
			}
		}
		else{
		
        // Prepare and execute the query
         $this->insert_commission($id, $rate);
		
        if ($conn->query($query) === TRUE)  {
            echo json_encode(['status' => 'success', 'message' => 'Commission updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update commission']);
        }
		}
        
    }
	} else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
}
}*/

/*public function update_commission() {
	
	//echo '<pre>';
	//print_r($_REQUEST);
	//echo '</pre>';
	//die("here");
    // Check if the request is a POST request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the ID and rate from the POST data
       $id = $_POST['id'];
    $rate = $_POST['rate'];
//die("ss");
        // Validate the input
        if ($id && $rate) {
            // Load the commission model if not already loaded
           // $this->load->model('commission_model', 'commission');

            // Get the commission by doctor ID
            $result = $this->commission->get_commission_by_doc_id($id);

            if ($result) {
                // Check if a record exists
                if ($result->num_rows() > 0) {
                    // Record exists, so update it
                    $update_success = $this->commission->update_commission($id, $rate);
                    
                    // Check if update was successful
                    if ($update_success) {
                        echo json_encode(['status' => 'success', 'message' => 'Record updated successfully']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Error updating record']);
                    }
                } else {
                    // Record doesn't exist, insert a new one
                    $insert_success = $this->commission->insert_commission($id, $rate);

                    // Check if insert was successful
                    if ($insert_success) {
                        echo json_encode(['status' => 'success', 'message' => 'Commission inserted successfully']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to insert commission']);
                    }
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Commission record not found']);
            }
        } else {
            // Input validation failed
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
    }
	
}*/

 public function update_commission() {
	 
        // Check if the request is a POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
            // Get the ID and rate from the POST data
             $id = $_POST['id'];
			$rate = $_POST['rate'];
			//echo $id;
			//echo $rate;
			//die("sdssd");

            // Validate the input
            if ($id && $rate) {
                // Get the commission by doctor ID
                $result = $this->commission->get_commission_by_doc_id($id);

                if ($result) {
                    // Record exists, so update it
                    $update_success = $this->commission->update_commission($id,$rate);
                    
                    // Check if update was successful
                    if ($update_success) {
                        echo json_encode(['status' => 'success', 'message' => 'Record updated successfully']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Error updating record']);
                    }
                } else {
                    // Record doesn't exist, insert a new one
                    $insert_success = $this->commission->insert_commission($id,$rate);

                    // Check if insert was successful
                    if ($insert_success) {
                        echo json_encode(['status' => 'success', 'message' => 'Commission inserted successfully']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to insert commission']);
                    }
                }
            } else {
                // Input validation failed
                echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            }
        }
    }


public function update_commission_phar(){
	// Check if the request is a POST request
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
    // Get the ID and rate from the POST data
     $id = $_POST['id'];
$rate = $_POST['rate'];
//echo $id;
//echo $rate;
//die("sdssd");

    // Validate the input
    if ($id && $rate) {
        // Get the commission by doctor ID
        $result = $this->commission_phar->get_commission_by_phar_id($id);

        if ($result) {
            // Record exists, so update it
            $update_success = $this->commission_phar->update_commission($id,$rate);
            
            // Check if update was successful
            if ($update_success) {
                echo json_encode(['status' => 'success', 'message' => 'Record updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error updating record']);
            }
        } else {
            // Record doesn't exist, insert a new one
            $insert_success = $this->commission_phar->insert_commission($id,$rate);

            // Check if insert was successful
            if ($insert_success) {
                echo json_encode(['status' => 'success', 'message' => 'Commission inserted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to insert commission']);
            }
        }
    } else {
        // Input validation failed
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
}
}

public function update_commission_Clinic(){
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
    // Get the ID and rate from the POST data
     $id = $_POST['id'];
$rate = $_POST['rate'];
//echo $id;
//echo $rate;
//die("sdssd");

    // Validate the input
    if ($id && $rate) {
        // Get the commission by doctor ID
        $result = $this->commission_clinic->get_commission_by_clinic_id($id);

        if ($result) {
            // Record exists, so update it
            $update_success = $this->commission_clinic->update_commission($id,$rate);
            
            // Check if update was successful
            if ($update_success) {
                echo json_encode(['status' => 'success', 'message' => 'Record updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error updating record']);
            }
        } else {
            // Record doesn't exist, insert a new one
            $insert_success = $this->commission_clinic->insert_commission($id,$rate);

            // Check if insert was successful
            if ($insert_success) {
                echo json_encode(['status' => 'success', 'message' => 'Commission inserted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to insert commission']);
            }
        }
    } else {
        // Input validation failed
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
}
}

public function update_commission_Lab(){
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
    // Get the ID and rate from the POST data
     $id = $_POST['id'];
$rate = $_POST['rate'];
//echo $id;
//echo $rate;
//die("sdssd");

    // Validate the input
    if ($id && $rate) {
        // Get the commission by doctor ID
        $result = $this->commission_lab->get_commission_by_lab_id($id);

        if ($result) {
            // Record exists, so update it
            $update_success = $this->commission_lab->update_commission($id,$rate);
            
            // Check if update was successful
            if ($update_success) {
                echo json_encode(['status' => 'success', 'message' => 'Record updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error updating record']);
            }
        } else {
            // Record doesn't exist, insert a new one
            $insert_success = $this->commission_lab->insert_commission($id,$rate);

            // Check if insert was successful
            if ($insert_success) {
                echo json_encode(['status' => 'success', 'message' => 'Commission inserted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to insert commission']);
            }
        }
    } else {
        // Input validation failed
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
}
}
public function update_commission_Pat(){
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
    // Get the ID and rate from the POST data
     $id = $_POST['id'];
$rate = $_POST['rate'];
//echo $id;
//echo $rate;
//die("sdssd");

    // Validate the input
    if ($id && $rate) {
        // Get the commission by doctor ID
        $result = $this->commission_pat->get_commission_by_pat_id($id);

        if ($result) {
            // Record exists, so update it
            $update_success = $this->commission_pat->update_commission($id,$rate);
            
            // Check if update was successful
            if ($update_success) {
                echo json_encode(['status' => 'success', 'message' => 'Record updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error updating record']);
            }
        } else {
            // Record doesn't exist, insert a new one
            $insert_success = $this->commission_pat->insert_commission($id,$rate);

            // Check if insert was successful
            if ($insert_success) {
                echo json_encode(['status' => 'success', 'message' => 'Commission inserted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to insert commission']);
            }
        }
    } else {
        // Input validation failed
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
}
}

public function update_Payment(){
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
   
     $id = $_POST['id'];
$payment_status =1;
//echo $id;
//echo $rate;
//die("sdssd");

    // Validate the input


        if ($id && $payment_status) {
            // Record exists, so update it
            $update_success = $this->invoice_payment_update->update_payment($id,$payment_status);
            
            // Check if update was successful
            if ($update_success) {
                echo json_encode(['status' => 'success', 'message' => 'Record updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error updating record']);
            }
        } else {
          echo json_encode(['status' => 'error', 'message' => 'Error updating record']);
      }


    } 

}

// public function update_Appointment(){

//   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
//     // Get the ID and rate from the POST data
//      $id = $_POST['id'];

//      if ($id) {
//       // Record exists, so update it
      
//       $update_success = $this->reschedule_appointment->delete_appointment($id);
      
//       // Check if update was successful
//       if ($update_success) {
//           echo json_encode(['status' => 'success', 'message' => 'Rescheduling appointment']);
//       } else {
//           echo json_encode(['status' => 'error', 'message' => 'Error updating record']);
//       }
//   } else {
//     echo json_encode(['status' => 'error', 'message' => 'Error updating record']);
// }

    
//     }
// }


public function update_Appointment(){

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
    // Get the ID and rate from the POST data
     $id = $_POST['id'];
     $from = $_POST['from'];
     $to = $_POST['to'];


     if ($id) {
         
      // Record exists, so update it
      $result = $this->reschedule_appointment->get_appointment_by_id($id);
      $paymeny_type = $this->reschedule_appointment->get_payment_method_by_appointment_id($id);
      if ($result) {
        if($paymeny_type !='Pay on Arrive'){
            // die("dsssdsdsd");
        $result1 = $this->reschedule_appointment->update_approved_status($id);
        $notification = array(
          'user_id' => $from,
          'to_user_id' => $to,
          'type' => "Appointment",
          'text' => "has rescheduled appointment to",
          'created_at' => date("Y-m-d H:i:s"),
        );
        $this->db->insert('notification', $notification);
        // Check if update was successful
        if ($result1) {
            echo json_encode(['status' => 'success', 'message' => 'Rescheduling Paid appointment']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating record']);
        }
      }
      else{
        $result = $this->reschedule_appointment->delete_appointment($id);
        $notification = array(
          'user_id' => $from,
          'to_user_id' => $to,
          'type' => "Appointment",
          'text' => "has rescheduled appointment to",
          'created_at' => date("Y-m-d H:i:s"),
        );
        $this->db->insert('notification', $notification);
        // Check if update was successful
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Rescheduling GTC appointment']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating record']);
        }
      }
      }
      else{
        echo json_encode(['status' => 'error', 'message' => 'appointment id not found']);
      }
      // $update_success = $this->reschedule_appointment->delete_appointment($id);
      
     
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Error updating record']);
}

    
    }
}

}