
<!-- Bread crumb -->
<div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php
																						/** @var array $language */
																						echo $language['lg_home']; ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_checkout']; ?></li>
					</ol>
				</nav>
				<h2 class="breadcrumb-title"><?php echo $language['lg_checkout']; ?></h2>
			</div>
		</div>
	</div>
</div>
<!-- /Breadcrumb -->
<?php include 'security.php' ?>
<!-- Page Content -->
<div class="content">
	<div class="container">

		<div class="row">
			<div class="col-md-5 col-lg-4 theiaStickySidebar">

				<!-- Booking Summary -->
				<div class="card booking-card">
					<div class="card-header">
						<h4 class="card-title"><?php echo $language['lg_booking_summary']; ?></h4>
					</div>
					<div class="card-body">
						<?php
						/** @var array $doctors */
						$profileimage = (!empty($doctors['profileimage'])) ? base_url() . $doctors['profileimage'] : base_url() . 'assets/img/user.png';
						?>

						<!-- Booking Doctor Info -->
						<div class="booking-doc-info">
							<a href="<?php echo base_url() . 'doctor-preview/' . $doctors['username']; ?>" class="booking-doc-img">
								<img src="<?php echo $profileimage; ?>" alt="User Image">
							</a>
							<div class="booking-info">
								<h4><a href="<?php echo base_url() . 'doctor-preview/' . $doctors['username']; ?>"><?php if ($doctors['role'] != 6) {
																														echo $language['lg_dr'];
																													} ?> <?php echo ucfirst($doctors['first_name'] . ' ' . $doctors['last_name']); ?></a></h4>
								<div class="rating">
									<?php
									$rating_value = $doctors['rating_value'];
									for ($i = 1; $i <= 5; $i++) {
										if ($i <= $rating_value) {
											echo '<i class="fas fa-star filled"></i>';
										} else {
											echo '<i class="fas fa-star"></i>';
										}
									}
									?>
									<span class="d-inline-block average-rating">(<?php echo $doctors['rating_count']; ?>)</span>
								</div>
								<div class="clinic-details">
									<p class="doc-location"><i class="fas fa-map-marker-alt"></i> <?php echo $doctors['cityname'] . ', ' . $doctors['countryname']; ?></p>
								</div>
							</div>
						</div>
						<!-- Booking Doctor Info -->

						<div class="booking-summary">
							<div class="booking-item-wrap">
								<ul class="booking-date">
									<li><?php echo $language['lg_date1']; ?> <span> <?php
																					/** @var array $appointment_details */
																					echo date('d M Y', strtotime($appointment_details[0]->appoinment_date)) ?></span>
									</li><br>
									<li>Time <span><?php echo date('h:i A', strtotime(converToTz($appointment_details[0]->appoinment_start_time, $this->session->userdata('time_zone'), $appointment_details[0]->appoinment_timezone))); ?></span></li>
								</ul><br>
								<?php
								$tax = !empty(settings("tax")) ? settings("tax") : "0";;
								$transcation_charge_amt = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";
								$amount = $this->session->userdata('amount');
								$transcation_charge = $this->session->userdata('transcation_charge');
								$tax_amount = $this->session->userdata('tax_amount');
								$total_amount = $this->session->userdata('total_amount')+settings('fixed_value_2');
								$total_amount_after_telehealth = $total_amount*(settings('percentage_value_2')/100);
								$total_amount+=$total_amount_after_telehealth;
								$rate_symbol = $this->session->userdata('currency_symbol');
								$discount = $this->session->userdata('discount');
								$this->session->set_userdata('total_amount1', $total_amount);
								//echo $total_amount1=$this->session->userdata('total_amount1');

								?>
								<ul class="booking-fee">
									<li><?php echo $language['lg_call_charge']; ?> <span><?php echo $rate_symbol; ?><?php echo number_format($amount, 2, '.', ''); ?></span></li>
									<li><?php echo $language['lg_transaction_cha']; ?> (<?php echo $transcation_charge_amt ?>%)<span><?php echo $rate_symbol; ?><?php echo number_format($transcation_charge, 2, '.', ''); ?></span></li>
									<li><?php echo $language['lg_tax']; ?> (<?php echo $tax ?>%)<span><?php echo $rate_symbol; ?><?php echo number_format($tax_amount, 2, '.', ''); ?></span></li>
									<li>Plarform Fees <span><?php echo $rate_symbol; ?><?php echo settings('fixed_value_2'); ?></span></li>
									
									<li>Telehealth Charges(10%)<span><?php echo $total_amount_after_telehealth; ?></span></li>
									
									<li>Discount <span><?php echo $rate_symbol; ?><?php echo number_format($discount, 2, '.', ''); ?></span></li>
								</ul>
								<div class="booking-total">
									<ul class="booking-total-list">
										<li>
											<span><?php echo $language['lg_total1']; ?></span>
											<span class="total-cost"><?php echo $rate_symbol; ?><?php echo number_format($total_amount, 2, '.', ''); ?></span>
											
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Booking Summary -->

			</div>
			<div class="col-md-7 col-lg-8">

				<div class="card">
					<div class="card-body">

						<!-- Checkout Form -->
						<?php if ($this->session->userdata('user_id')) { ?>
							<div class="payment-widget">
								<h4 class="card-title"><?php echo $language['lg_payment_method']; ?></h4>
							<?php } ?>
							<!-- /Credit Card Payment -->
							<div class="stripepayment" style="display: none;">
								<form action="#" method="post" id="payment-form">
									<div>
										<label for="card-element">
											<?php echo $language['lg_credit_or_debit']; ?>
										</label>
										<div id="card-element" style="width: 100%">
											<!-- A Stripe Element will be inserted here. -->
										</div>

										<!-- Used to display form errors. -->
										<div id="card-errors" role="alert"></div>
									</div>
									<div class="submit-section mt-4 mb-4">
										<button class="btn btn-primary submit-btn" id="stripe_pay_btn"><?php echo $language['lg_confirm_and_pay1']; ?></button>
									</div>
								</form>
							</div>
							<?php if ($this->session->userdata('user_id')) { ?>

								<!-- Cybersource Payment -->
								<div class="payment-list listt">
									<label class="payment-radio">
										<input type="radio" value="Cybersource" name="payment_methods">
										<span class="checkmark"></span>
										<?php echo $language['lg_credit_or_debit']; ?>
									</label>
								</div>
								<!-- /Cybersource Payment -->



								<div class="payment-list invoice">
									<label class="payment-radio credit-card-option">
										<input type="radio" value="Pay on Arrive" name="payment_methods">
										<span class="checkmark"></span>
										<?php echo $language['lg_pay_on_arrive']; ?>
									</label>
								</div>


								<!-- free list  -->

								<div class="free-list">
									<label class="payment-radio credit-card-option">
										<input type="radio" value="free booking" name="payment_methods">
										<span class="checkmark"></span>
										<?php echo ('Free Appoinment'); ?>
									</label>
									<div class="free_payment" style="display: none;">
										<div class="submit-section mt-4">
											<button type="button" id="pay_button" onclick="free_payment()" class="btn btn-primary submit-btn"><?php echo ('Free Booking'); ?></button>
										</div>
									</div>
								</div>

								<script>
									document.addEventListener('DOMContentLoaded', function() {
    // Select the element with class 'free-list'
    const freeList = document.querySelector('.free-list');
    const paymentList = document.querySelector('.listt');
    const invoiceList = document.querySelector('.invoice');
    const clinic = document.querySelector('.cybersource_payment');
    
    // Select the element with class 'free_payment'
    const freePayment = document.querySelector('.free_payment');

    // Add click event listener to the free-list element
    if (freeList && freePayment) {
        freeList.addEventListener('click', function() {
            // Toggle the display of the free_payment class
            if (freePayment.style.display === 'none') {
                freePayment.style.display = 'block'; // Hide it if it's currently displayed
            }
        });
    }
	paymentList.addEventListener('click', function() {
    freePayment.style.display = 'none'; // Hide freePayment when paymentList is clicked
});
	invoiceList.addEventListener('click', function() {
    freePayment.style.display = 'none'; // Hide freePayment when paymentList is clicked
});
	freeList.addEventListener('click', function() {
    clinic.style.display = 'none'; // Hide freePayment when paymentList is clicked
});

});

function free_payment(){
	alert('hc jdbjs kdjdkf jfjdsjf');

	 // die("dsfa"); doctor gtc booking comes here
	//  $invoice = $this->db->order_by('id', 'desc')->limit(1)->get('payments')->row_array();
    // if (empty($invoice)) {
    //   $invoice_id = 0;
    // } else {
    //   $invoice_id = $invoice['id'];
    // }
    // $invoice_id++;
    // $invoice_no = 'I0000' . $invoice_id;
    // $appointment_id = "";

    // $paymentdata = array(
    //   'user_id' => $this->session->userdata('user_id'),
    //   'doctor_id' => $this->session->userdata('doctor_id'),
    //   'invoice_no' => $invoice_no,
    //   'per_hour_charge' => $this->session->userdata('hourly_rate'),
    //   'total_amount' => $this->session->userdata('total_amount'),
    //   'currency_code' => $this->session->userdata('currency_code'),
    //   'txn_id' => '',
    //   'order_id' => 'OD' . time() . rand(),
    //   'transaction_status' => '',
    //   'payment_type' => 'Pay on Arrive',
    //   'tax' => !empty(settings("tax")) ? settings("tax") : "0",
    //   'tax_amount' => $this->session->userdata('tax_amount'),
    //   'transcation_charge' => $this->session->userdata('transcation_charge'),
    //   'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
    //   'payment_status' => 0,
    //   'payment_date' => date('Y-m-d H:i:s'),
    // );
    // $this->db->insert('payments', $paymentdata);
    // $payment_id = $this->db->insert_id();

    // // Sending notification to mentor
    // $doctor_id = $this->session->userdata('doctor_id');
    // $appointment_details = $this->session->userdata('appointment_details');

    // foreach ($appointment_details as $key => $value) {
    //   $appointmentdata['payment_id'] =  $payment_id;
    //   $appointmentdata['appointment_from'] = $this->session->userdata('user_id');
    //   $appointmentdata['appointment_to'] = $this->session->userdata('doctor_id');
    //   $appointmentdata['from_date_time'] = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_start_time;
    //   $appointmentdata['to_date_time'] = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_end_time;
    //   $appointmentdata['appointment_date'] = $appointment_details[0]->appoinment_date;
    //   $appointmentdata['appointment_time'] = $appointment_details[0]->appoinment_start_time;
    //   $appointmentdata['appointment_end_time'] = $appointment_details[0]->appoinment_end_time;
    //   $appointmentdata['appoinment_token'] = $appointment_details[0]->appoinment_token;
    //   $appointmentdata['appoinment_session'] = $appointment_details[0]->appoinment_session;
    //   $appointmentdata['type'] = "Clinic";
    //   $appointmentdata['payment_method'] = $this->input->post('payment_method');
    //   $appointmentdata['tokboxsessionId'] = '';
    //   $appointmentdata['tokboxtoken'] = '';
    //   $appointmentdata['paid'] = 1;
    //   $appointmentdata['approved'] = 1;
    //   $appointmentdata['time_zone'] = $appointment_details[0]->appoinment_timezone;
    //   $appointmentdata['created_date'] = date('Y-m-d H:i:s');
    //   $this->db->insert('appointments', $appointmentdata);
    //   $appointment_id = $this->db->insert_id();
    //   $notification = array(
    //     'user_id' => $this->session->userdata('user_id'),
    //     'to_user_id' => $this->session->userdata('doctor_id'),
    //     'type' => "Appointment",
    //     'text' => "has booked appointment to",
    //     'created_at' => date("Y-m-d H:i:s"),
    //     'time_zone' => $appointment_details[0]->appoinment_timezone
    //   );
    //   $this->db->insert('notification', $notification);
    //   // $this->send_appoinment_mail($appointment_id);
    //   if (settings('tiwilio_option') == '1') {
    //     $this->send_appoinment_sms($appointment_id);
    //   }
    // }


    // $results = array('status' => 200, 'appointment_id' => base64_encode($appointment_id));
    // echo json_encode($results);
}

								</script>

								<!-- free list  -->



								<!-- Submit Section -->
								<div class="submit-section mt-4">


									<div class="clinic_payment" style="display: none;">
										<div class="submit-section mt-4">
											<button type="button" id="pay_button" onclick="appoinment_payment('stripe')" class="btn btn-primary submit-btn"><?php echo $language['lg_book_appointmen']; ?></button>
										</div>
									</div>


									<!-- Cybersource pay btn -->
									<div class="cybersource_payment" style="display: none;">
										<div class="submit-section mt-4">
											<?php /*
											// convert the total amount to NGN currency
											$paystack_accept_currency = settings('paystack_accept_currency');
											if (empty($paystack_accept_currency)) {
												$paystack_accept_currency = $this->session->userdata('currency_code');
											}
											$paystack_total_amount = get_doccure_currency($this->session->userdata('total_amount'), $this->session->userdata('currency_code'), $paystack_accept_currency);
											$paystack_total_amount = number_format($paystack_total_amount, 2, '.', '');*/
											?>

											<button type="button" id="payment_pay_btn" onclick="appoinment_payment('Cybersource')" class="btn btn-primary submit-btn"><?php echo $language['lg_confirm_and_pay1']; ?></button>
										</div>
									</div>
									<!-- Cybersource pay btn -->

								</div>
								<!-- /Submit Section -->

							</div>

						<?php } else { ?>

							<div class="submit-section mt-4">
								<button type="button" data-toggle="modal" data-target="#login_modal" class="btn btn-primary"><?php echo $language['lg_signin']; ?></button>
								<button type="button" data-toggle="modal" data-target="#register_modal" class="btn btn-primary"><?php echo $language['lg_signup']; ?></button>
							</div>

						<?php } ?>
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

						<form id="payment_confirmation" action="<?php echo $paymentFormURL; ?>" method="post" autocomplete="off">
							<?php
							/** @var array $patients */
							$address = !empty($patients['address1']) ? $patients['address1'] : $language['lg_no_address_spec'];
							$countryname = !empty($patients['sortname']) ? $patients['sortname'] : 'US';
							$statename = !empty($patients['state_code']) ? $patients['state_code'] : '';
							$cityname = !empty($patients['cityname']) ? $patients['cityname'] : $language['lg_no_address_spec'];
							$postal_code = !empty($patients['postal_code']) ? $patients['postal_code'] : '94043';
							$info = $language['lg_booking_appoinm'] . ' ' . $doctors['first_name'] . ' ' . $doctors['last_name'];
							$encryptValue = encryptor_decryptor('encrypt', $this->session->userdata('custom_value') . "_Booking");
							if ($statename == '' || is_numeric($statename)) {
								$statename = !empty($patients['statename']) ? substr($patients['statename'], 0, 2)
									: 'CA';
								$statename = strtoupper($statename);
							}


							$params = array(

								"access_key" => $cyb_access_key,
								"profile_id" => $cyb_profileid,

								"transaction_uuid" => uniqid() . "_" . $encryptValue,
								"signed_field_names" => "access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency,bill_to_forename,bill_to_surname,bill_to_email,bill_to_phone,bill_to_address_line1,bill_to_address_city,bill_to_address_state,bill_to_address_country,bill_to_address_postal_code",
								"unsigned_field_names" => "",
								"signed_date_time" => gmdate("Y-m-d\TH:i:s\Z"),
								"locale" => "en",
								"transaction_type" => "authorization",
								"reference_number" => time(),
								"amount" => $this->session->userdata('total_amount1'),
								"currency" => $this->session->userdata('currency_code'),
								"bill_to_forename" => $patients['first_name'],
								"bill_to_surname" => $patients['last_name'],
								"bill_to_email" => $patients['email'],
								"bill_to_phone" => $patients['mobileno'],
								"bill_to_address_line1" => $address,
								"bill_to_address_city" => $cityname,
								"bill_to_address_state" => $statename,
								"bill_to_address_country" => $countryname,
								"bill_to_address_postal_code" => $postal_code,
							);
							/* echo "<pre>";
							print_r($params);
							echo "</pre>"; */
							foreach ($params as $name => $value) {
								echo "<input hidden type=\"text\" id=\"" . $name . "\" name=\"" . $name . "\" value=\"" . $value . "\"/>\n";
							}
							echo "<input type=\"hidden\" id=\"signature\" name=\"signature\" value=\"" . sign($params) . "\"/>\n";
							?>

						</form>

						<form role="form" method="POST" id="payment_formid">
							<?php
							/** @var array $patients */
							$address = !empty($patients['address1']) ? $patients['address1'] : $language['lg_no_address_spec'];
							$info = $language['lg_booking_appoinm'] . ' ' . $doctors['first_name'] . ' ' . $doctors['last_name'];
							?>
							<input type="hidden" name="productinfo" id="productinfo" value="<?php echo $info ?>" />
							<input type="hidden" name="name" id="name" value="<?php echo $patients['first_name'] . ' ' . $patients['last_name']; ?>" />
							<input type="hidden" name="phone" id="phone" value="<?php echo $patients['mobileno'] ?>" />
							<input type="hidden" name="email" id="email" value="<?php echo $patients['email'] ?>" />
							<input type="hidden" name="address1" id="address1" value="<?php echo $address; ?>">
							<input type="hidden" class="form-control" id="amount" name="amount" value="<?php echo $this->session->userdata('total_amount'); ?>" readonly />
							<input type="hidden" class="form-control" id="currency_code" name="currency_code" value="<?php echo $this->session->userdata('currency_code'); ?>" readonly />
							<input type="hidden" name="access_token" id="access_token">
							<input type="hidden" name="payment_id" id="payment_id">
							<input type="hidden" name="order_id" id="order_id">
							<input type="hidden" name="signature" id="signature">



							<input type="hidden" name="payment_method" id="payment_method" value="Card Payment">
						</form>
						<!-- /Checkout Form -->

					</div>
				</div>

			</div>


		</div>

	</div>

</div>

<button id="my_book_appoinment" style="display: none;"><?php echo $language['lg_purchase']; ?></button>
<!-- /Page Content -->


<?php
/** @var string $stripe_api_key */
$stripe_option = !empty(settings("stripe_option")) ? settings("stripe_option") : "";
if ($stripe_option == '1') {
	$stripe_api_key = !empty(settings("sandbox_api_key")) ? settings("sandbox_api_key") : "";
}
if ($stripe_option == '2') {
	$stripe_api_key = !empty(settings("live_api_key")) ? settings("live_api_key") : "";
}
?>

<script type="text/javascript">
	var stripe_api_key = '<?php echo $stripe_api_key; ?>';
</script>