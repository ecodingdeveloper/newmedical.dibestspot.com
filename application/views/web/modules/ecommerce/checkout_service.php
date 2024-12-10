<style>
        /* #payment_confirmation {
            display: none; 
        } */
    </style>
<div class="content">
	<div class="container">
		<?php include 'security.php' ?>
		<!-- Checkout Form -->
<form action="#" method="post" id="payment-form">

			<div class="row">
				<div class="col-md-6 col-lg-7">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title"><?php
													/** @var array $language  */
													echo $language['lg_billing_details']; ?></h3>
						</div>

						<div class="card-body">

							<!-- Personal Information -->
							<div class="info-widget">
								<h4 class="card-title"><?php echo $language['lg_personal_inform']; ?></h4>
								<div class="row">


									<div class="col-md-6 col-sm-12">
										<div class="form-group card-label">

											<label>FullName <span class="text-danger">*</span></label>
											<input type="text" id="ship_name" name="ship_name" onblur="updateHiddenValues()" class="form-control" required>

										</div>
									</div>

									<div class="col-md-6 col-sm-12">
										<div class="form-group card-label">
											<label><?php echo $language['lg_email']; ?> <span class="text-danger">*</span></label>
											<input type="email" id="ship_email" name="ship_email" onblur="updateHiddenValues()" class="form-control" required>

										</div>
									</div>

									<div class="col-md-6 col-sm-12">
										<div class="form-group card-label">
											<label><?php echo $language['lg_mobile_number']; ?> <span class="text-danger">*</span></label>
											<input type="text" id="ship_mobile"  name="ship_mobile" class="form-control" onblur="updateHiddenValues()" required>
										</div>
									</div>

								
								</div>
								<!-- <div class="exist-customer">Existing Customer? <a href="#">Click here to login</a></div>  -->
							</div>
							<!-- /Personal Information -->
  <!-- 2nd/////////////////////////////////////////////////////////////////////////////////////////////////////////////////// div  -->
  

								<!-- /Cybersource Payment -->



								<!-- Submit Section -->


							  <div class="submit-section mt-4">
									<div class="cybersource_payment" style="text-align:center;" >
										<div class="submit-section mt-4">
											<button type="submit" id="pay_buttons" class="btn btn-primary submit-btn"><?php echo $language['lg_confirm_and_pay1']; ?></button>
											 <!-- <button type="submit">submit</button> -->
										</div>
									</div>
		                      </div>
								<!-- /Submit Section -->

								<?php
								$cybersource_option = !empty(settings("cybersource_option")) ? settings("cybersource_option") : "";
								if ($cybersource_option == '1') {
									$cyb_access_key = !empty(settings("sandbox_cyb_access_key")) ? settings("sandbox_cyb_access_key") : "";
									$cyb_profileid = !empty(settings("sandbox_profileid")) ? settings("sandbox_profileid") : "";
									$paymentFormURL = "https://testsecureacceptance.cybersource.com/pay";
								}
								if ($cybersource_option == '2') {
									$cyb_access_key = !empty(settings("live_cyb_access_key")) ? settings("live_cyb_access_key") : "";
									$cyb_profileid = !empty(settings("live_profileid")) ? settings("live_profileid") : "";
									$paymentFormURL = "https://secureacceptance.cybersource.com/pay";
								}
								?>

							

             
		    
		  </div>
	    </div>
      </div>
</form>
		
	
	<div class="col-md-6 col-lg-5 theiaStickySidebar">
 <div class="card booking-card">
		<div class="card-header">
			<h3 class="card-title"><?php echo $language['lg_your_order1']; ?></h3>
		</div>

    <?php

 // echo '<pre>';
 // print_r($package_data);
 // echo '</pre>';
 // die("checkout_package");
 $cart_total_amount = $service_data->service_price;

 // echo $cart_total_amount;


 ?>


		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-center mb-0">
					<tr>
						<th>Service</th>

						<th class="text-right"><?php echo $language['lg_price']; ?></th>
						<!-- <th class="text-right"><?php echo $language['lg_qty']; ?></th> -->
						<th class="text-right"><?php echo $language['lg_total1']; ?></th>

					</tr>
					<tbody>

								<input type="hidden" name="currency_code" id="currency_code" value="<?php echo $user_currency_code ?>">
								<tr>
									<td><?php echo $service_data->specialization_list; ?> </td>
									<td class="text-right"><?php echo '$'.$service_data->service_price; ?></td>
									<!-- <td class="text-right"><?php echo $rows['qty']; ?></td> -->
									<td class="text-right">1</td>
								</tr>
						
					</tbody>
				</table>
			</div>

			<?php
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
			?>

			<div class="booking-summary pt-5">
				<div class="booking-item-wrap">
					<ul class="booking-date">
						<li><?php echo $language['lg_subtotal']; ?> <span><?php
																			/** @var string $user_currency_sign  */
																			echo $user_currency_sign; ?><?php echo '$'.$cart_total_amount; ?></span></li>

						<li><?php echo $language['lg_transcation_cha']; ?> (<?php echo $transcation_charge_amt ?>%)<span><?php echo $user_currency_sign; ?><?php echo '$'.number_format($transcation_charge, 2, '.', ''); ?></span></li>

						<li><?php echo $language['lg_tax_amount']; ?> (<?php echo $tax ?>%)<span><?php echo '$'.$user_currency_sign; ?><?php echo $tax_amount; ?></span></li>

					</ul>

					<?php

					// $user_currency = get_user_currency();
					// $user_currency_code = $user_currency['user_currency_code'];
					// $user_currency_rate = $user_currency['user_currency_rate'];


					?>

					<ul class="booking-fee">

					</ul>


					<div class="booking-total">
						<ul class="booking-total-list">
							<li>
								<span><?php echo $language['lg_total1']; ?></span>
								<span class="total-cost"><?php echo $user_currency_sign; ?><?php echo '$'.number_format($total_amount, 2, '.', ''); ?></span>
							</li>
							<input type="hidden" name="total_amount" id="total_amount" value="<?php echo number_format($total_amount, 2, '.', ''); ?>">
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	
<form id="payment_confirmation" action="<?php echo $paymentFormURL; ?>" method="post" autocomplete="off">
			<?php
			/** @var array $patients */
		
			$name = '';
			$email = '';
			$mobile = '';
			

			$encryptValue = encryptor_decryptor('encrypt',"_service");
			$encryptType = "";
			if ($statename == '' || is_numeric($statename)) {
				$statename = !empty($patients['statename']) ? substr($patients['statename'], 0, 2)
					: 'CA';
				$statename = strtoupper($statename);
			}



			$params = array(

				"access_key" => $cyb_access_key,
				"profile_id" => $cyb_profileid,

				"transaction_uuid" => uniqid() . "_" . $encryptValue,
				"signed_field_names" => "access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency",
				"unsigned_field_names" => "",
				"signed_date_time" => gmdate("Y-m-d\TH:i:s\Z"),
				"locale" => "en",
				"transaction_type" => "authorization",
				"reference_number" => time(),
				"amount" => number_format($total_amount, 2, '.', ''),
				//"currency" => $user_currency_code,
				"currency" => 'USD',
  /*				"email" =>$email,
				"mobile" =>$mobile,*/
				
			);

			// echo '<pre>';
			// print_r($params);
			// echo '</pre>';
			// echo (sign($params));
			// die("dds");

			foreach ($params as $name => $value) {
				echo "<input hidden type=\"hidden\" id=\"" . $name . "\" name=\"" . $name . "\" value=\"" . $value . "\"/>\n";
			}?>
			<?php echo "<input type=\"hidden\" id=\"signature\" name=\"signature\" value=\"" . sign($params) . "\"/>\n";
			 ?>
			<input type="hidden" id="conf_name" name="name" value=""/>
			<input type="hidden" id="conf_email" name="email" value=""/>
			<input type="hidden" id="conf_mobile" name="mobile" value=""/>
      
</form>
</div>
</div>
	<!-- /Booking Summary -->
	
		</div>
  <script>
	

	//  function showForm() {
  //           const form = document.getElementById('payment_confirmation');
  //           form.style.display = 'block';
  //           // Optional: Automatically submit the form
  //           // form.submit();
  //       }
  //       window.onload = function() {
  //           const intervalId = setInterval(function() {
							
  //               clearInterval(intervalId); // Stop after showing the form once
  //           }, 1000); // Show form every 0.1 seconds
  //       };

 function package_payment(type) {  
    // var terms_accept=$("input[name='terms_accept']:checked").val();
    var terms_accept = 1;
    if (terms_accept == '1') {
        if (type == 'paypal') {
            $('#pay_buttons').attr('disabled', true);
            $('#pay_buttons').html('<div class="spinner-border text-light" role="status"></div>');
            $('#payment-form').attr('action', base_url + 'cart/paypal_pay');
            $('#payment-form').submit();
        } else if (type == 'Cybersource') {
					// document.getElementById('payment_confirmation').style.display = 'block';
           $("#payment_confirmation").submit();
            //return false;
        } else {
            var payment_method = $("input[name='payment_methods']:checked").val();
            if (payment_method != 'Card Payment') {
                $("#my_book_appoinment").click();
            }

            return false;
        }
    } else {
        toastr.warning(lg_please_accept_t);
    }
 }
// document.getElementById("pay_buttons").addEventListener("click", function() {
//         package_payment('Cybersource');
//     });
document.getElementById("pay_buttons").addEventListener("click", function(event) {
    event.preventDefault(); 

    // Validate input fields
    var name = document.getElementById("ship_name").value.trim();
    var email = document.getElementById("ship_email").value.trim();
    var mobile = document.getElementById("ship_mobile").value.trim();
    var company_name = document.getElementById("company_name").value.trim();


    if (name && email && mobile && company_name) {
			//$("#conf_name").attr('value',name);
         package_payment('Cybersource');
    } else {
        toastr.warning("Please fill in all required fields.");
    }
});
function updateHiddenValues() {
    // Get values from input fieldsa
		//console.log("test");
    const name = document.getElementById('ship_name').value;
    const email = document.getElementById('ship_email').value;
    const mobile = document.getElementById('ship_mobile').value;
		// if( name != '' )
		// {
		// 	document.querySelector('input[name="name"]').value = name;
		// }
   //console.log(name);
    // Update hidden inputs in the payment confirmation form
    document.querySelector('input[name="name"]').value = name;
    document.querySelector('input[name="email"]').value = email;
    document.querySelector('input[name="mobile"]').value = mobile;
}
</script>