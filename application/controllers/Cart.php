<?php
require_once(APPPATH . '../vendor/stripe/stripe-php/init.php');
require_once(APPPATH . '../vendor/autoload.php');
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {

    public $data;
    public $session;
    public $timezone;
    public $lang;
    public $language;
    public $calendar;
    public $input;
    public $carts;
    public $cart;
    public $db;
    public $paypal_lib;
	public $sum=0;
	public $kamal;

public function __construct() {

        parent::__construct();
        $this->data['theme']     = 'web';
        $this->data['module']    = 'ecommerce';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
		$this->sum=0;
		$this->kamal;
        $this->timezone = $this->session->userdata('time_zone');
        if(!empty($this->timezone)){
          date_default_timezone_set($this->timezone);
        }

        // $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):'english';
        $lan=default_language();
        $lang = !empty($this->session->userdata('language'))?strtolower($this->session->userdata('language')):strtolower($lan['language']);
        $this->data['language'] = $this->lang->load('content', $lang, true);
        $this->language = $this->lang->load('content', $lang, true);
        
       $this->load->model('cart_model','carts');
        
                 
    }

   public function add_cart()
  {
      
     // $datas = array("pop"=>1);

    //$this->load->library('cart');

     $product_id=$this->input->post('product_id'); 
     $cart_qty=!empty($this->input->post('cart_qty'))?$this->input->post('cart_qty'):'1';

     $product_details = $this->carts->get_product_details($product_id);
     $image_url=explode(',', $product_details['upload_image_url']);

      $cart_data = array(
            'id'    => $product_details['id'],
            'qty'    => $cart_qty,
            'pharmacy_currency'  =>  $product_details['pharmacy_currency'],
            'pharmacy_id'  =>  $product_details['pharmacy_id'],
            'price'    => $product_details['sale_price'],
            'name'    => $product_details['name'],
            'image' => $image_url[0],
             );
        $this->cart->product_name_rules = '[:print:]';
        $datas = $cart_data;
        
        $result=$this->cart->insert($cart_data);
        
        if(!empty($result))
        {
              $datas['result']='true';
              $datas['msg']=$this->language['lg_cart_added_succ'];
              $datas['cart_count']=$this->cart->total_items();
        }
        else
        {
              $datas['result']='false';
              $datas['msg']=$this->language['lg_cart_added_fail'];
              $datas['cart_count']=$this->cart->total_items();
        } 

        echo json_encode($datas);   
   }

   public function cart_list()
   {
	  // die("sda");
		if(empty($this->session->userdata('user_id'))){
		   redirect(base_url().'signin');
		}
      $this->data['page'] = 'cart_list';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
   }

   public function cart_lists()
   {
		if(empty($this->session->userdata('user_id'))){
	redirect(base_url().'signin');
		}
        $cart_list = $this->cart->contents();
        $cart_total_amount = $this->cart->total();
        $html='';
        $checkout_html='';
		
        $checkout_cart_html='';
        $datas['cart_count']=0;
        $user_currency=get_user_currency();
        $user_currency_code=$user_currency['user_currency_code'];
        $user_currency_sign=$user_currency['user_currency_sign'];

        if($this->cart->total_items() > 0){ 

          foreach($cart_list as $rows){
			  //echo"<pre>";
			 // print_r($rows);
			  //echo"</pre>";

          $sale_price=get_doccure_currency(round( $rows['price'],2),$rows['pharmacy_currency'],$user_currency['user_currency_code']);
          $tot_sale_price=get_doccure_currency($rows['subtotal'],$rows['pharmacy_currency'],$user_currency['user_currency_code']);
  $this->sum+=$tot_sale_price;
          $new_cart_total_amount=get_doccure_currency(round( $cart_total_amount),$rows['pharmacy_currency'],$user_currency['user_currency_code']);

            $imagess = file_exists($rows['image'])?$rows['image']:'assets/img/no-image.png';


          $html .='<tr>
                  <td>
                    <h2 class="table-avatar">
                      <a href="javascript:void(0);" class="avatar avatar-sm mr-2"><img class="avatar-img rounded" src="'.base_url().$imagess.'" alt="User Image"></a>
                      
                    </h2>
                  </td>
                  <td><a href="javascript:void(0);">'.$rows['name'].'</a></td>
                  <td>'.$user_currency_sign.''.$sale_price.'</td>
                  
                  <td class="text-center">
                    <div class="quant-input">
                        <div class="cart-info quantity">
                              <div class="input-group mb-3">
                                <div class="input-group-prepend btn-increment-decrement" onClick="decrement_quantity(\''.$rows['rowid'].'\')">
                                  <span class="input-group-text">-</span>
                                </div>
                                <input type="text" class="form-control input-quantity" readonly id="input-quantity-'.$rows['rowid'].'" value="'.$rows['qty'].'">
                                <div class="input-group-append btn-increment-decrement" onClick="increment_quantity(\''.$rows['rowid'].'\')">
                                  <span class="input-group-text">+</span>
                                </div>
                              </div>
                         </div>
                    </div>
                  </td>
                  <td>'.$user_currency_sign.''.number_format($tot_sale_price,2,'.','').'</td>
                  <td class="text-right">
                    <div class="table-action">
                      <a onclick="remove_cart(\''.$rows['rowid'].'\')" href="javascript:void(0);" class="btn btn-sm bg-danger-light">
                        <i class="fas fa-times"></i>
                      </a>
                    </div>
                  </td>
                </tr>';
          }
        }
        else{
          $html .='<tr><td colspan="5" style="text-align: center;">'.$this->language['lg_cart_empty'].'</td></tr>';
        }
      //  $new_cart_total_amount=0;
         if($this->cart->total_items() > 0){
			 $this->kamal=$this->sum;
          $checkout_html='<tr>
                      <td colspan="4" class="text-right">'.$this->language['lg_total_amount'].'</td>
					  <td class="text-center"><b>'.$user_currency_sign.''.round( $this->sum,2).'</b></td>

                      <td class="text-right">
                        <div class="table-action">
                          <a href="'.base_url().'cart-checkout" class="btn btn-sm book-btn1">
                           <i class="fas fa-shopping-cart"></i> '.$this->language['lg_checkout'].'
                          </a>
                        
                        </div>
                      </td>
                    </tr>';

              $checkout_cart_html='<tr>
                      <td colspan="4" class="text-right">'.$this->language['lg_total_amount'].'</td>
                      <td class="text-center"><b>'.$user_currency_sign.''.number_format($new_cart_total_amount,2,'.','').'</b></td>
                      <td class="text-right">
                        
                      </td>
                    </tr>';  

          $datas['cart_count']=1;    
         }

        $datas['cart_list']=$html;
        $datas['checkout_html']=$checkout_html;
        $datas['checkout_cart_html']=$checkout_cart_html;

        echo json_encode($datas);

   }


   public function cart_count()
   {
      $datas['cart_count']=$this->cart->total_items();
      echo json_encode($datas);
   }

   public function remove_cart()
  {
     $id=$this->input->post('id');
     $remove = $this->cart->remove($id);
     echo $remove;
  }

  public function update_cart() 
  {
    $update = 10;
        
        // Get cart item info
        $rowid = $this->input->post('cart_id');
        $qty = $this->input->post('new_quantity');
      
        
        // Update item in the cart
        if(!empty($rowid) && !empty($qty)){
            $data = array(
                'rowid' => $rowid,
                'qty'   => $qty
            );
            $update = $this->cart->update($data);
        }

        echo $update; 
        
        // Return response
        //echo $update?'ok':'err';
  }

  public function checkout()
  {
	  //die("checkoir");
		if(empty($this->session->userdata('user_id'))){
		 redirect(base_url().'signin');
		}
      $user_currency=get_user_currency();
      $user_currency_code=$user_currency['user_currency_code'];
      $user_currency_rate=$user_currency['user_currency_rate'];
      //$inputdata['ship_address_1']=$this->input->post('ship_address_1');	   
      $user_details = $this->db->select('*')->from('users_details')->where('user_id',$this->session->userdata('user_id'))->get()->result_array();  
      $pharmacy_currency_code = $user_details[0]['currency_code'];

      $this->data['user_currency_sign'] = $user_currency['user_currency_sign'];
      $cart_list = $this->cart->contents();
      $cart_total_amountFin = $this->cart->total();
      $pharmacy_currency = ''; $cart_total_amount = 0;
      foreach ($this->cart->contents() as $item){
        $pharmacy_currency = $item['pharmacy_currency'];
        break;
      }
      foreach ($this->cart->contents() as $rows){
        $sale_price = get_doccure_currency($rows['price'], $rows['pharmacy_currency'], $user_currency['user_currency_code']);
        $cart_total_amount += $rows['qty'] * $sale_price;
      }
	  
	  $cart_total_amount=$this->kamal;
	  echo"The total amount is",$cart_total_amount;
	  echo"The total cart_amount is",$this->kamal;
	 // dd("Hello");
      
      $tax = !empty(settings("tax")) ? settings("tax") : "0";
			$transcation_charge_amt = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";
			if ($transcation_charge_amt > 0) {
				$transcation_charge = ($cart_total_amount * ($transcation_charge_amt / 100));
			} else {
				$transcation_charge = 0;
			}
			$total_amount = $cart_total_amount + $transcation_charge;
			$tax_amount = (number_format($total_amount, 2, '.', '') * $tax / 100);
			$total_amount = $total_amount + $tax_amount;

      $total_amount = number_format($total_amount, 2, '.', '');

      $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $pharmacy_currency;
      $rate_symbol = currency_code_sign($currency_option);

      
      $new_data = array(
        'pharmacy_amount' => $cart_total_amount,
        'pharmacy_transcation_charge' => $transcation_charge,
        'pharmacy_tax_value' => $tax,
        'pharmacy_tax_amount' => $tax_amount,
        'pharmacy_total_amount' => $total_amount,
        'pharmacy_hourly_rate' => $cart_total_amount,
        'pharmacy_currency_code' => $currency_option,
        'pharmacy_currency_symbol' => $rate_symbol,
        'pharmacy_discount' => 0,
        'pharmacy_cart' => $this->cart->contents(),
        'pharmacy_transcation_charge_percent' => $transcation_charge_amt
      );
      $this->session->set_userdata($new_data);
      
      $this->db->insert('session_details', array('session_data' => json_encode($this->session->userdata)));
      $session_id    = $this->db->insert_id();

      $new_data['custom_value'] = $session_id;
      $this->session->set_userdata($new_data);
      $this->db->where('id', $session_id);
      $this->db->update('session_details', array('session_data' => json_encode($this->session->userdata)));

      $this->data['session_id'] = $session_id;
	  
	  //add_shipping_details();
		
      $html='';
      $checkout_html='';
      $checkout_cart_html='';
      $datas['cart_count']=0;
      $cart_total_amount = number_format($cart_total_amountFin,2,'.','');
      $this->cart->total_items();
       
      $this->data['cart_list'] = $cart_list;
      $this->data['total_items'] = $this->cart->total_items(); 

      $user_id=$this->session->userdata('user_id');
      $this->data['page'] = 'checkout';
      $this->data['shipping'] = $this->db->where('user_id',$user_id)->get('shipping_details')->row_array();
      $this->data['patients'] = $this->carts->getUserData($this->session->userdata('user_id'));

      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
  }

  public function add_shipping_details()
  {
	//die("shipping");
    $inputdata=array();
    $inputdata['user_id']=$this->session->userdata('user_id');
    $inputdata['ship_name']=$this->input->post('ship_name');
    $inputdata['ship_mobile']=$this->input->post('ship_mobile');
    $inputdata['ship_email']=$this->input->post('ship_email');
    $inputdata['ship_address_1']=$this->input->post('ship_address_1');
    $inputdata['ship_address_2']=$this->input->post('ship_address_2');
    $inputdata['ship_country']=$this->input->post('country');
    $inputdata['ship_state']=$this->input->post('state');
    $inputdata['ship_city']=$this->input->post('city');
    $inputdata['postal_code']=$this->input->post('postal_code');
    $this->session->set_userdata('shipping_details',$inputdata);
    $already_exits=$this->db->where('user_id',$inputdata['user_id'])->get('shipping_details')->result_array();
    if(count($already_exits) > 0)
    {
      $this->db->where('user_id',$inputdata['user_id']);
      $this->db->update('shipping_details',$inputdata);
    }
    else
    {
      $this->db->insert('shipping_details',$inputdata);
    }

    //echo $this->db->last_query();  exit;


  }

  public function payment_sucess()
  {
      $this->data['page'] = 'payment_sucess';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
  }

  public function paypal_pay()
  {

      $this->load->library('paypal_lib');
    // Set variables for paypal form
      $returnURL = base_url().'cart/success';
      $cancelURL = base_url().'cart/failure';
      $notifyURL = base_url().'cart/ipn';
      $paypal_email='';
      $paypal_option=!empty(settings("paypal_option"))?settings("paypal_option"):"";
      if($paypal_option=='1'){
        $paypal_email=!empty(settings("sandbox_email"))?settings("sandbox_email"):"";
      }
      if($paypal_option=='2'){
        $paypal_email=!empty(settings("live_email"))?settings("live_email"):"";
      }

      $amount = $this->input->post('total_amount');
      
      $name = $this->input->post('ship_name');
      $currency_code = $this->input->post('currency_code'); 
      $productinfo = "Orders";
      $patient_id=$this->session->userdata('user_id');


      $amount=get_doccure_currency($amount,$currency_code,'USD');
      $amount=number_format($amount,2, '.', '');

      $ordItemDetails['full_name']     = $this->input->post('ship_name');
      $ordItemDetails['email']     = $this->input->post('ship_email');

      $ordItemDetails['address1'] = $this->input->post('ship_address_1');
        
      $ordItemDetails['address2']     = $this->input->post('ship_address_2');
      $ordItemDetails['state']     = $this->input->post('ship_state');
      $ordItemDetails['postal_code']     = $this->input->post('postal_code');
      $ordItemDetails['city']     = $this->input->post('ship_city');

      $ordItemDetails['country']     = $this->input->post('ship_country');
      $ordItemDetails['payment_method']     = '2';
      $ordItemDetails['phoneno']     = $this->input->post('ship_mobile');
      $ordItemDetails['total_amount']     = $amount;
      
      $ordItemDetails['user_id']     = $this->session->userdata('user_id');
      $ordItemDetails['pharmacy_id']     = $this->session->userdata('pharmacy_id');
      $ordItemDetails['created_at']     = date('Y-m-d H:i:s');
      $ordItemDetails['currency']     = '$';
      $ordItemDetails['shipping']     = $this->input->post('shipping');
      
      // currency_code_sign($currency_code)

      $ordItemDetails['status'] = 0;

      $this->db->insert('order_user_details',$ordItemDetails);
      $user_order_id = $this->db->insert_id();

      
            
            $oreder_id = 'OD'.time().rand();
            $i=0;

            $currency_code=$this->input->post('currency_code');

            // $amount=get_doccure_currency($this->input->post('total_amount'),$currency_code,'INR');
            
            $amount=number_format($amount,2, '.', '');
            $tax = !empty(settings("tax"))?settings("tax"):"0";
			
			$transcation_charge_amt = !empty(settings("transaction_charge"))?settings("transaction_charge"):"0";
			if($transcation_charge_amt > 0) {
				$transcation_charge = ($this->cart->total() * ($transcation_charge_amt/100));
			} else {
				$transcation_charge = 0;
			}
            $totals_amount = $this->cart->total() + $transcation_charge;
            $tax_amount = (number_format($totals_amount,2,'.','') * $tax/100);
            
            $invoice = $this->db->order_by('id','desc')->limit(1)->get('payments')->row_array();
              if(empty($invoice)){
                $invoice_id = 1;   
              }else{
                $invoice_id = $invoice['id'];    
              }
              $invoice_id++;
              $invoice_no = 'I0000'.$invoice_id;
              $transaction_status='success';
              $txnid=time().rand();
              $payments_data = array(
               'user_id' => $this->session->userdata('user_id'),
               'doctor_id' => $this->session->userdata('pharmacy_id'),
               'invoice_no' => $invoice_no,
               'per_hour_charge' => $this->cart->total(),
               'total_amount' => $amount,
               'currency_code' => $currency_code,
               'txn_id' => $txnid,
               'order_id' => $oreder_id,
               'transaction_status' => $transaction_status,  
               'payment_type' =>'Paypal',
               'tax'=>!empty(settings("tax"))?settings("tax"):"0",
               'tax_amount' => $tax_amount,
               'transcation_charge' => $transcation_charge,
               'transaction_charge_percentage' => !empty(settings("transaction_charge"))?settings("transaction_charge"):"0",
               'payment_status' => 1,
               'payment_date' => date('Y-m-d H:i:s'),
               );

              $this->db->insert('payments',$payments_data);
              // echo $this->db->last_query();exit();
              $payment_id = $this->db->insert_id();

              $cartItems = $this->cart->contents();

              foreach($cartItems as $item){
             
                $ordItemData[$i]['user_id']     = $this->session->userdata('user_id');
                $ordItemData[$i]['payment_id']     = $payment_id;
                $ordItemData[$i]['pharmacy_id']     = $item['pharmacy_id'];
                $ordItemData[$i]['order_id']     = $oreder_id;
                $ordItemData[$i]['product_id']     = $item['id'];
                $ordItemData[$i]['product_name']     = $item['name'];
                $ordItemData[$i]['quantity']     = $item['qty'];
                $ordItemData[$i]['price']     = $item['price'];
                $ordItemData[$i]['subtotal']     = $item['subtotal'];
                $ordItemData[$i]['transaction_status'] = $transaction_status;
                $ordItemData[$i]['payment_type']  ='Paypal';
                $ordItemData[$i]['ordered_at']     =date('Y-m-d H:i:s');
                $ordItemData[$i]['user_order_id']     =$user_order_id;
                $i++;
              }
              $insertOrderItems = $this->carts->insertOrderItems($ordItemData);

      

      
      // print_r($payment_id);exit();

      $this->db->insert('session_details',array('session_data' => json_encode($this->session->userdata)));
      $session_id    = $this->db->insert_id();
      
      // Add fields to paypal form
      $this->paypal_lib->add_field('return', $returnURL);
      $this->paypal_lib->add_field('cancel_return', $cancelURL);
      $this->paypal_lib->add_field('notify_url', $notifyURL);
      $this->paypal_lib->add_field('item_name', $productinfo);
      $this->paypal_lib->add_field('custom', $orderId);
      $this->paypal_lib->add_field('amount',  $amount);
      $this->paypal_lib->add_field('item_number',  $session_id);
      $this->paypal_lib->add_field('currency_code', 'USD');  
      $this->paypal_lib->add_field('business', $paypal_email); 
      $this->paypal_lib->paypal_auto_form();
     
  }

  public function success()
  {

    // print_r("kjf");exit();
      if(isset($_POST["txn_id"]) && !empty($_POST["txn_id"]))
      {
        $paypalInfo =  $this->input->post();

        $txnid= $paypalInfo['txn_id'];  
        //$orderId=$paypalInfo['custom'];
        $sessID=$paypalInfo['item_number'];
        $amount=$paypalInfo['payment_gross'];
        
      }
      else
      {
        $paypalInfo =  $this->input->get();
        $txnid= $paypalInfo['txn_id'];
        //$orderId=$paypalInfo['custom'];
        $sessID=$paypalInfo['item_number'];
        $amount=$paypalInfo['payment_gross'];
      }
        $transaction_status = json_encode($paypalInfo);

        $sessionDetails = $this->db->where('id', $sessID)->get('session_details')->row_array();
        $session        = (array) json_decode($sessionDetails['session_data']);
      
        $this->session->set_userdata($session);

         $status='success';

         if($status == 'success'){

            // if(!empty($ordItemData)){
                // Insert order items
                // $insertOrderItems = $this->carts->insertOrderItems($ordItemData);
                // if($insertOrderItems){

                    $notification=array(
                      'user_id'=>$this->session->userdata('user_id'),
                      'to_user_id'=>$this->session->userdata('pharmacy_id'),
                      'type'=>"Pharmacy",
                      'text'=>"have ordered products to",
                      'created_at'=>date("Y-m-d H:i:s"),
                      'time_zone'=>$this->session->userdata('time_zone')
                    );

                    $this->db->insert('notification',$notification);

                    
                    $this->cart->destroy();
                    //$this->session->set_flashdata('success_message',$this->language['lg_your_order_has_']);
                    redirect(base_url().'pharmacy/orders_list');
                 // }
                 // else
                 // {
                 //    $this->session->set_flashdata('error_message',$this->language['lg_transaction_fai']);
                 //    redirect(base_url().'dashboard');
                 // }
            // }
               


           }else{

              $this->session->set_flashdata('error_message',$this->language['lg_transaction_fai']);
              redirect(base_url().'dashboard');
          }


  }

  public function failure(){

        $this->session->set_flashdata('error_message',$this->language['lg_transaction_fai']);
        redirect(base_url().'dashboard');

  }

  public function stripe_payment(){

    $stripe_option=!empty(settings("stripe_option"))?settings("stripe_option"):"";
     if($stripe_option=='1'){
        $stripe_secert_key=!empty(settings("sandbox_rest_key"))?settings("sandbox_rest_key"):"";
     }
     if($stripe_option=='2'){
        $stripe_secert_key=!empty(settings("live_rest_key"))?settings("live_rest_key"):"";
     }

     $currency_code=$this->input->post('currency_code');

     $amount=get_doccure_currency($this->input->post('total_amount'),$currency_code,'INR');

     $amount=number_format($amount,2, '.', '');
   
     \Stripe\Stripe::setApiKey($stripe_secert_key);

     $intent = null;

     try {
          if (isset($_POST['payment_method_id'])) {
            # Create the PaymentIntent
            $intent = \Stripe\PaymentIntent::create([
              'payment_method' => $_POST['payment_method_id'],
              'amount' => ($amount*100),
              'currency' => 'INR',
              'confirmation_method' => 'manual',
              'confirm' => true,
            ]);
          }

          if (isset($_POST['payment_intent_id'])) {
            $intent = \Stripe\PaymentIntent::retrieve(
              $_POST['payment_intent_id']
            );
            $intent->confirm();
          }

          $this->generateResponse($intent);

      } catch (\Stripe\Exception\ApiErrorException $e) {
        # Display error on client
        $results = array('status'=>500,'message'=>$e->getMessage());
        echo json_encode($results);
       
      }    

  }

  private function generateResponse($intent){
      # Note that if your API version is before 2019-02-11, 'requires_action'
      # appears as 'requires_source_action'.
      if (($intent->status == 'requires_action' || $intent->status == 'requires_source_action') && $intent->next_action->type == 'use_stripe_sdk') {
        # Tell the client to handle the action
        $results = array('status'=>201,'requires_action'=>true,'payment_intent_client_secret' => $intent->client_secret);
        echo json_encode($results);
        
      } else if ($intent->status == 'succeeded') {
        # The payment didn’t need any additional actions and completed!
        # Handle post-payment fulfillment
        $this->placeorder($intent);

        
      } else {
        # Invalid status
        $results = array('status'=>500,'message'=>'Transaction failure!.Please try again','error'=>$intent);
        echo json_encode($results);
      }
  }


  public function placeorder($intent)
  {

    $transaction_status = json_encode($intent);

    $txnid=time().rand();
   
    $user_data = $_POST;
           
           

          $ordItemDetails['full_name']     = $user_data['ship_name'];
          $ordItemDetails['email']     = $user_data['ship_email'];

          $ordItemDetails['address1'] = $user_data['ship_address_1'];
         
          $ordItemDetails['address2']     = $user_data['ship_address_2'];
          $ordItemDetails['state']     = $user_data['state'];
          $ordItemDetails['postal_code']     = $user_data['postal_code'];
          $ordItemDetails['city']     = $user_data['city'];

        $ordItemDetails['country']     = $user_data['country'];
          $ordItemDetails['payment_method']     = '1';
        $ordItemDetails['phoneno']     = $user_data['ship_mobile'];
        $ordItemDetails['total_amount']     = $this->cart->total();
       
        $ordItemDetails['user_id']     = $this->session->userdata('user_id');
        $ordItemDetails['pharmacy_id']     = $this->session->userdata('pharmacy_id');
        $ordItemDetails['created_at']     = date('Y-m-d H:i:s');
        $ordItemDetails['currency']     = '$';
        $ordItemDetails['shipping']     = $user_data['shipping'];
       
          $ordItemDetails['status'] = 1;

          $this->db->insert('order_user_details',$ordItemDetails);
          $orderId = $this->db->insert_id();

      $oreder_id = 'OD'.time().rand();

      $currency_code=$this->input->post('currency_code');

      // $amount=get_doccure_currency($this->input->post('total_amount'),$currency_code,'INR');
      $amount=$this->input->post('total_amount');

      $amount=number_format($amount,2, '.', '');
      $tax = !empty(settings("tax"))?settings("tax"):"0";
	  
	  $transcation_charge_amt = !empty(settings("transaction_charge"))?settings("transaction_charge"):"0";
	  if($transcation_charge_amt > 0) {
		$transcation_charge = ($this->cart->total() * ($transcation_charge_amt/100));
	  } else {
		$transcation_charge = 0;
	  }
      $totals_amount = $this->cart->total() + $transcation_charge;
      $tax_amount = (number_format($totals_amount,2,'.','') * $tax/100);

      $invoice = $this->db->order_by('id','desc')->limit(1)->get('payments')->row_array();
      if(empty($invoice)){
        $invoice_id = 1;  
      }else{
        $invoice_id = $invoice['id'];    
      }
      $invoice_id++;
      $invoice_no = 'I0000'.$invoice_id;

      $payments_data = array(
       'user_id' => $this->session->userdata('user_id'),
       'doctor_id' => $this->session->userdata('pharmacy_id'),
       'invoice_no' => $invoice_no,
       'per_hour_charge' => $this->cart->total(),
       'total_amount' => $amount,
       'currency_code' => $currency_code,
       'txn_id' => $txnid,
       'order_id' => $oreder_id,
       'transaction_status' => $transaction_status,  
       'payment_type' =>'Stripe',
       'tax'=>!empty(settings("tax"))?settings("tax"):"0",
       'tax_amount' => $tax_amount,
       'transcation_charge' => $transcation_charge,
       'transaction_charge_percentage' => !empty(settings("transaction_charge"))?settings("transaction_charge"):"0",
       'payment_status' => 1,
       'payment_date' => date('Y-m-d H:i:s'),
       );
      $this->db->insert('payments',$payments_data);
      $payment_id = $this->db->insert_id();


      $cartItems = $this->cart->contents();
    //print_r($cartItems);
      $ordItemData = array();
      $i=0;
      foreach($cartItems as $item){
          $ordItemData[$i]['user_id']     = $this->session->userdata('user_id');
          $ordItemData[$i]['payment_id']     = $payment_id;
          $ordItemData[$i]['pharmacy_id']     = $item['pharmacy_id'];
          $ordItemData[$i]['order_id']     = $oreder_id;
          $ordItemData[$i]['product_id']     = $item['id'];
          $ordItemData[$i]['product_name']     = $item['name'];
          $ordItemData[$i]['quantity']     = $item['qty'];
          $ordItemData[$i]['price']     = $item["price"];
          $ordItemData[$i]['subtotal']     = $item["subtotal"];
          $ordItemData[$i]['transaction_status'] = $transaction_status;
          $ordItemData[$i]['payment_type']  ='Stripe';
          $ordItemData[$i]['ordered_at']     =date('Y-m-d H:i:s');
          $ordItemData[$i]['user_order_id']     = $orderId;
          $ordItemData[$i]['currency_code']     = $currency_code;
          $i++;
      }

     
      if(!empty($ordItemData)){
          // Insert order items
          $insertOrderItems = $this->carts->insertOrderItems($ordItemData);
          if($insertOrderItems){
             $this->cart->destroy();
             $this->add_shipping_details();
             $this->session->set_userdata('trans_id',$orderId);
             $results = array('status'=>200);
       
        $notification=array(
          'user_id'=>$this->session->userdata('user_id'),
          'to_user_id'=>$this->session->userdata('pharmacy_id'),
          'type'=>"Pharmacy",
          'text'=>"have ordered products to",
          'created_at'=>date("Y-m-d H:i:s"),
          'time_zone'=>$this->session->userdata('time_zone')
        );

        $this->db->insert('notification',$notification);
       
       
             echo json_encode($results);
           }
           else
           {
               $results = array('status'=>500);
               echo json_encode($results);
           }
      }  

  }

}