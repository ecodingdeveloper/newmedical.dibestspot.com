<!-- Breadcrumb -->
<div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php
																						/** @var array $language  */
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

			<div class="col-md-6 col-lg-6 theiaStickySidebar">

				<!-- Booking Summary -->
				<div class="card booking-card">
					<div class="card-header">
						<h4 class="card-title"><?php echo $language['lg_booking_summary']; ?></h4>
					</div>
					<div class="card-body">
						<?php
						$profileimage = (!empty($lab_booking_details['profileimage'])) ? base_url() . $lab_booking_details['profileimage'] : base_url() . 'assets/img/user.png';

						$lab_booking_session = $this->session->userdata('lab_test_book_details');
						?>

						<!-- Booking Doctor Info -->
						<div class="booking-doc-info">
							<a href="javascript:void(0);" class="booking-doc-img">
								<img src="<?php echo $profileimage; ?>" alt="User Image">
							</a>
							<div class="booking-info">
								<h4><a href="javascript:void(0);"><?php
																	/** @var array $lab_booking_details  */
																	echo ucfirst($lab_booking_details['first_name'] . ' ' . $lab_booking_details['last_name']); ?></a></h4>
								<span>Lab</span>
								<div class="clinic-details">
									<p class="doc-location"><i class="fas fa-map-marker-alt"></i> <?php echo $lab_booking_details['cityname'] . ', ' . $lab_booking_details['countryname']; ?></p>
								</div>
							</div>
						</div>
						<!-- Booking Doctor Info -->
						<div class="booking-summary">
							<div class="booking-item-wrap">
								<?php
								$tax = !empty(settings("tax")) ? settings("tax") : "0";
								$transcation_charge_amt = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";
								$amount = $lab_booking_session['amount'];
								$transcation_charge = $lab_booking_session['transcation_charge'];
								$tax_amount = $lab_booking_session['tax_amount'];
								$total_amount = $lab_booking_session['total_amount'];
								$rate_symbol = $lab_booking_session['currency_symbol'];
								$grand_total = 0;

								?>
								<ul class="booking-fee">
									<li><?php echo $language['lg_lab_test10']; ?> <span><?php echo $rate_symbol; ?><?php echo number_format($amount, 2); ?></span></li>
									<li><?php echo $language['lg_transaction_cha']; ?> (<?php echo $transcation_charge_amt ?>%)<span><?php echo $rate_symbol; ?><?php echo number_format($transcation_charge, 2); ?></span></li>
									<li><?php echo $language['lg_tax']; ?> (<?php echo $tax ?>%)<span><?php echo $rate_symbol; ?><?php echo $tax_amount; ?></span></li>
									<li>Discount <span><?php echo $rate_symbol; ?>0</span></li>
								</ul>
								<div class="booking-total">
									<ul class="booking-total-list">
										<li>
											<span><?php echo $language['lg_total1']; ?></span>
											<span class="total-cost"><?php echo $rate_symbol; ?><?php echo number_format($total_amount, 2); ?></span>

											<?php $grand_total = number_format($total_amount, 2); ?>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- /Booking Summary -->

			</div>





			<div class="col-md-6 col-lg-6">
				<div class="card">
					<div class="card-body">


						<div class="col-md-12">

							<div class="card">
								<div class="card-body">

									<!-- Checkout Form -->

									<div class="payment-widget">
										<h4 class="card-title"><?php echo $language['lg_payment_method']; ?></h4>


										<div class="stripe_payment" style="display: none;">
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
											<div class="payment-list">
												<label class="payment-radio">
													<input type="radio" value="Cybersource" name="payment_methods">
													<span class="checkmark"></span>
													<?php echo $language['lg_credit_or_debit']; ?>
												</label>
											</div>
											<!-- /Cybersource Payment -->

											<!-- Submit Section -->
											<div class="submit-section mt-4">

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
											$encryptValue = encryptor_decryptor('encrypt', $this->session->userdata('custom_value') . "_Lab");
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
												"amount" => number_format($total_amount, 2, '.', ''),
												"currency" => $patients['currency_code'],
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


									<?php /* ?>

										<form role="form" method="POST" id="payment_formid" action="<?php echo base_url().'lab/paypal_pay';?>">
						 <?php	

						 	 

						 $address = !empty($patients['address1'])?$patients['address1']:$language['lg_no_address_spec']; 
						 $info = $language['lg_booking_appoinm']; 

						// echo "ssssss".$this->session->userdata('total_amount');
			              ?>
			              <input type="hidden" name="productinfo" id="productinfo"   value="<?php echo $info ?>" />
			              <input type="hidden"  name="name" id="name"  value="<?php 
			              /** @var array $patients  
			              echo $patients['first_name'].' '.$patients['last_name']; ?>" /> 
			              <input type="hidden"  name="phone" id="phone"  value="<?php echo $patients['mobileno'] ?>"/>  
			              <input type="hidden"  name="email" id="email"  value="<?php echo $patients['email'] ?>" />
			              <input type="hidden"  name="address1" id="address1" value="<?php echo $address; ?>">
			              <input type="text" class="form-control" id="amount" name="amount" value="<?php echo number_format($total_amount,2,'.',''); ?>"  readonly/>
			              <input type="hidden" class="form-control" id="currency_code" name="currency_code" value="<?php echo $patients['currency_code']; ?>"  readonly/>
			              <input type="hidden" name="access_token" id="access_token" > 
			              <input type="hidden" name="payment_id" id="payment_id" > 
			              <input type="hidden" name="order_id" id="order_id" > 
			              <input type="hidden" name="signature" id="signature" > 
			              <input type="hidden" name="firstname" id="firstname" value="<?php echo $patients['first_name']; ?>"> 
			              


			              <input type="hidden" name="payment_method" id="payment_method" value="Card Payment" >
										</form>
									
									<!-- /Checkout Form -->
									<?php */ ?>
								<?php } ?>
								</div>
							</div>

						</div>

					</div>
				</div>

			</div>


		</div>

	</div>

</div>

<button id="my_book_appoinment" style="display: none;"><?php echo $language['lg_purchase']; ?></button>
<!-- /Page Content -->


<?php
$stripe_option = !empty(settings("stripe_option")) ? settings("stripe_option") : "";
/** @var string $stripe_api_key */
if ($stripe_option == '1') {
	$stripe_api_key = !empty(settings("sandbox_api_key")) ? settings("sandbox_api_key") : "";
}
if ($stripe_option == '2') {
	$stripe_api_key = !empty(settings("live_api_key")) ? settings("live_api_key") : "";
}
?>

<script type="text/javascript">
	var stripe_api_key = '<?php echo $stripe_api_key; ?>';

	var country = '';
	var country_code = '';
	var state = '';
	var city = '';
</script>