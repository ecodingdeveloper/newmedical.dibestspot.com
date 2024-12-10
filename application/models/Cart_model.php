<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @property object $db
 */
class Cart_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
  }

  public function get_product_details($product_id)
  {

    $this->db->select("p.*,ph.currency_code as pharmacy_currency,ph.user_id as pharmacy_id");
    $this->db->from('products p');
    $this->db->join('users_details ph', 'ph.user_id = p.user_id');
    $this->db->where("p.status = 1 AND md5(p.id)='" . $product_id . "'");
    $result = $this->db->get()->row_array();
    return $result;
  }

  public function insertOrderItems($data = array())
  {
    $insert = "";
    for ($j = 0; $j < count($data); $j++) {

      $insert = $this->db->insert('orders', $data[$j]);
    }


    // Return the status

    return $insert ? true : false;
  }

  public function getUserData($id)
  {
    $this->db->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=u.id) as rating_value,u.role, c.sortname,  s.state_code, CONCAT(u.first_name," ", u.last_name) as patient_name');
    $this->db->from('users u');
    $this->db->join('users_details ud', 'ud.user_id = u.id', 'left');
    $this->db->join('country c', 'ud.country = c.countryid', 'left');
    $this->db->join('state s', 'ud.state = s.id', 'left');
    $this->db->join('city ci', 'ud.city = ci.id', 'left');
    $this->db->join('specialization sp', 'ud.specialization = sp.id', 'left');
    $this->db->where('u.status', '1');
    $this->db->where('u.is_verified', '1');
    $this->db->where('u.is_updated', '1');
    $this->db->where('u.id', $id);
    return $result = $this->db->get()->row_array();
  }

  public function pharmacyPlaceorder($session_id, $intent)
  {
	  

    $transaction_status = $intent;

    $txnid = time() . rand();

    $sessionDetails = $this->db->where('id', $session_id)->get('session_details')->row_array(); 
    $booking_details_session        = (array) json_decode($sessionDetails['session_data']);
    
    $tax_amount = $booking_details_session['pharmacy_tax_amount'];
    $transcation_charge = $booking_details_session['pharmacy_transcation_charge'];
    $transcation_charge_percent = $booking_details_session['pharmacy_transcation_charge_percent'];
    $currency_code = $booking_details_session['pharmacy_currency_code'];
    $phar_time_zone = $booking_details_session['time_zone'];
    $tax_value = $booking_details_session['pharmacy_tax_value'];
    $pharmacy_amount   =  $booking_details_session['pharmacy_amount'];
    $amount = $booking_details_session['pharmacy_total_amount'];

    $user_data = $this->getUserData($booking_details_session['user_id']);

$pharmacy_carts=$booking_details_session['pharmacy_cart'];
$ph_ids=array();
foreach($pharmacy_carts as $pharmacy_cart) {
	//print_r($pharmacy_cart);
	$ph_ids[]=$pharmacy_cart->pharmacy_id;
}
//echo count($ph_ids);
//print_r($booking_details_session['cart_contents']);
//print_r($user_data); 

    $ordItemDetails['full_name']     = $user_data['patient_name'];
    $ordItemDetails['email']     = $user_data['email'];

    $ordItemDetails['address1'] = $user_data['address1'];

    $ordItemDetails['address2']     = $user_data['address2'];
    $ordItemDetails['state']     = $user_data['state'];
    $ordItemDetails['postal_code']     = $user_data['postal_code'];
    $ordItemDetails['city']     = $user_data['city'];

    $ordItemDetails['country']     = $user_data['country'];
    $ordItemDetails['payment_method']     = '1';
    $ordItemDetails['phoneno']     = $user_data['mobileno'];
   // $ordItemDetails['total_amount']     = $this->cart->total();
    $ordItemDetails['total_amount']     = $booking_details_session['pharmacy_total_amount'];

    $ordItemDetails['user_id']     = $booking_details_session['user_id'];
  //  $ordItemDetails['pharmacy_id']     = $booking_details_session['pharmacy_id'];
    $ordItemDetails['pharmacy_id']     = count($ph_ids);
    $ordItemDetails['created_at']     = date('Y-m-d H:i:s');
    $ordItemDetails['currency']     = '$';
    $ordItemDetails['shipping']     = '';

    $ordItemDetails['status'] = 1;
	//echo "<pre>";
//	print_r($ordItemDetails);
//die;
    $this->db->insert('order_user_details', $ordItemDetails);
    $orderId = $this->db->insert_id();

    $oreder_id = 'OD' . time() . rand();

    // $currency_code = $this->input->post('currency_code');

    // // $amount=get_doccure_currency($this->input->post('total_amount'),$currency_code,'INR');
    // $amount = $this->input->post('total_amount');

    // $amount = number_format($amount, 2, '.', '');
    // $tax = !empty(settings("tax")) ? settings("tax") : "0";

    // $transcation_charge_amt = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";
    // if ($transcation_charge_amt > 0) {
    //   $transcation_charge = ($this->cart->total() * ($transcation_charge_amt / 100));
    // } else {
    //   $transcation_charge = 0;
    // }
    // $totals_amount = $this->cart->total() + $transcation_charge;
    // $tax_amount = (number_format($totals_amount, 2, '.', '') * $tax / 100);

    $invoice = $this->db->order_by('id', 'desc')->limit(1)->get('payments')->row_array();
    if (empty($invoice)) {
      $invoice_id = 1;
    } else {
      $invoice_id = $invoice['id'];
    }
    $invoice_id++;
    $invoice_no = 'I0000' . $invoice_id;

    $payments_data = array(
      'user_id' => $booking_details_session['user_id'],
      'doctor_id' => $booking_details_session['pharmacy_id'],
      'invoice_no' => $invoice_no,
      'per_hour_charge' => $pharmacy_amount,
      'total_amount' => $amount,
      'currency_code' => $currency_code,
      'txn_id' => $txnid,
      'order_id' => $oreder_id,
      'transaction_status' => $transaction_status,
      'payment_type' => 'Cybersource',
      'tax' => !empty($tax_value) ? $tax_value : "0",
      'tax_amount' => $tax_amount,
      'transcation_charge' => $transcation_charge,
      'transaction_charge_percentage' => !empty($transcation_charge_percent) ? $transcation_charge_percent : "0",
      'payment_status' => 1,
      'payment_date' => date('Y-m-d H:i:s'),
      "time_zone"=>$this->session->userdata('time_zone')
    );
    $this->db->insert('payments', $payments_data);
    $payment_id = $this->db->insert_id();


    $cartItems = $booking_details_session['pharmacy_cart'];
    //echo "<pre>";   print_r($cartItems);
    $ordItemData = array();
    $i = 0; $pharmacy_id = 0;
    foreach ($cartItems as $item) {  
      $ordItemData[$i]['user_id']     = $booking_details_session['user_id'];
      $ordItemData[$i]['payment_id']     = $payment_id;
      $ordItemData[$i]['pharmacy_id']     =  $item->pharmacy_id;
      $ordItemData[$i]['order_id']     = $oreder_id;
      $ordItemData[$i]['product_id']     = $item->id;
      $ordItemData[$i]['product_name']     = $item->name;
      $ordItemData[$i]['quantity']     = $item->qty;
      $ordItemData[$i]['price']     = $item->price;
      $ordItemData[$i]['subtotal']     = $item->subtotal;
      $ordItemData[$i]['transaction_status'] = $transaction_status;
      $ordItemData[$i]['payment_type']  = 'Cybersource';
      $ordItemData[$i]['ordered_at']     = date('Y-m-d H:i:s');
      $ordItemData[$i]['user_order_id']     = $orderId;
      $ordItemData[$i]['currency_code']     = $currency_code;      
      $i++;
      $pharmacy_id = $item->pharmacy_id;
    }


    if (!empty($ordItemData)) {
      $this->db->where('id', $payment_id);
      $this->db->update('payments', array('doctor_id' => $pharmacy_id));
      // Insert order items
      $insertOrderItems = $this->insertOrderItems($ordItemData);
      if ($insertOrderItems) {
        $this->cart->destroy();
        //$this->add_shipping_details();
        $this->session->set_userdata('trans_id', $orderId);

        $notification = array(
          'user_id' => $this->session->userdata('user_id'),
          'to_user_id' => $item->pharmacy_id,
          'type' => "Pharmacy",
          'text' => "have ordered products to",
          'created_at' => date("Y-m-d H:i:s"),
          'time_zone' => $this->session->userdata('time_zone')
        );

        $this->db->insert('notification', $notification);


        return true;
      } else {
        return false;
      }
    }
  }
}
