<!-- Breadcrumb -->
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
						<h4 class="card-title"><?php echo $language['lg_payment_summary']; ?></h4>
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
									<li><?php echo $language['lg_date1']; ?> <span> <?php echo date('d M Y') ?></span></li>
								</ul>
								<?php //print_r($invoice_details);
								$tax = !empty($invoice_details['tax']) ? $invoice_details['tax'] : "0";;
								$transcation_charge_amt = !empty($invoice_details['transaction_charge_percentage']) ? $invoice_details['transaction_charge_percentage'] : "0";
								$amount = $invoice_details['per_hour_charge'];
								$transcation_charge = $invoice_details['transcation_charge'];
								$tax_amount = $invoice_details['tax_amount'];
								$total_amount = $invoice_details['total_amount'];

								$rate_symbol = currency_code_sign($invoice_details['currency_code']);
								$discount = 0;

								?>
								<ul class="booking-fee">
									<li><?php echo $language['lg_amount']; ?> <span><?php echo $rate_symbol; ?><?php echo number_format($amount, 2, '.', ''); ?></span></li>
									<li><?php echo $language['lg_transaction_cha']; ?> (<?php echo $transcation_charge_amt ?>%)<span><?php echo $rate_symbol; ?><?php echo number_format($transcation_charge, 2, '.', ''); ?></span></li>
									<li><?php echo $language['lg_tax']; ?> (<?php echo $tax ?>%)<span><?php echo $rate_symbol; ?><?php echo number_format($tax_amount, 2, '.', ''); ?></span></li>
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

							<?php if ($this->session->userdata('user_id')) { ?>

								<!-- Cybersource Payment -->
								<div class="payment-list">
									<label class="payment-radio">
										<input type="radio" value="Cybersource" name="payment_methods" checked>
										<span class="checkmark"></span>
										<?php echo $language['lg_credit_or_debit']; ?>
									</label>
								</div>

								<!-- Submit Section -->
								<div class="submit-section mt-4">

									<!-- Paystack pay btn -->
									<div class="cybersource_payment">
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

											<button type="button" id="paystack_pay_btn" onclick="pay_invoice()" class="btn btn-primary submit-btn"><?php echo $language['lg_confirm_and_pay1']; ?></button>
										</div>
									</div>
									<!-- Paystack pay btn -->

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
							$encryptValue = encryptor_decryptor('encrypt', $invoice_id . "_Invoice");
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
								"signed_field_names" => "access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency,bill_to_forename,bill_to_surname,bill_to_email,bill_to_phone,bill_to_address_line1,bill_to_address_city,bill_to_address_state,bill_to_address_country,bill_to_address_postal_code",
								"unsigned_field_names" => "",
								"signed_date_time" => gmdate("Y-m-d\TH:i:s\Z"),
								"locale" => "en",
								"transaction_type" => "authorization",
								"reference_number" => time(),
								"amount" => $invoice_details['total_amount'],
								"currency" => $invoice_details['currency_code'],
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

							foreach ($params as $name => $value) {
								echo "<input hidden type=\"hidden\" id=\"" . $name . "\" name=\"" . $name . "\" value=\"" . $value . "\"/>\n";
							}
							echo "<input type=\"hidden\" id=\"signature\" name=\"signature\" value=\"" . sign($params) . "\"/>\n";
							?>

						</form>


					</div>
				</div>

			</div>


		</div>

	</div>

</div>
<?php  ?>