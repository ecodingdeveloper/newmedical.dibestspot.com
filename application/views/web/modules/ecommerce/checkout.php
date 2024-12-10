<?php
$user_currency = get_user_currency();
$user_currency_code = $user_currency['user_currency_code'];
$user_currency_sign = $user_currency['user_currency_sign'];
//print_r($user_currency);
//die('sefrewr');
?>
<!-- Page Content -->
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

											<label><?php echo $language['lg_name']; ?> <span class="text-danger">*</span></label>
											<input type="text" id="ship_name" name="ship_name" class="form-control" value="<?php echo !empty($patients) ? $patients['patient_name'] : ''; ?>" required>

										</div>
									</div>

									<div class="col-md-6 col-sm-12">
										<div class="form-group card-label">
											<label><?php echo $language['lg_email']; ?> <span class="text-danger">*</span></label>
											<input type="email" name="ship_email" value="<?php echo !empty($patients) ? $patients['email'] : ''; ?>" id="ship_email" class="form-control" required>

										</div>
									</div>

									<div class="col-md-6 col-sm-12">
										<div class="form-group card-label">
											<label><?php echo $language['lg_mobile_number']; ?> <span class="text-danger">*</span></label>
											<input type="text" value="<?php echo !empty($patients) ? $patients['mobileno'] : ''; ?>" name="ship_mobile" id="ship_mobile" class="form-control" required>
										</div>
									</div>

									<div class="col-md-6 col-sm-12">
										<div class="form-group card-label">
											<label><?php echo $language['lg_address_line_1']; ?> <span class="text-danger">*</span></label>
											<input type="text" value="<?php echo !empty($patients) ? $patients['address1'] : ''; ?>" name="ship_address_1" id="ship_address_1" class="form-control" required>
										</div>
									</div>

									<div class="col-md-6 col-sm-12">
										<div class="form-group card-label">
											<label><?php echo $language['lg_address_line_2']; ?></label>
											<input type="text" value="<?php echo !empty($patients) ? $patients['address2'] : ''; ?>" name="ship_address_2" id="ship_address_2" class="form-control">
										</div>
									</div>

									<div class="col-md-6 col-sm-12">
										<div class="form-group card-label">
											<label><?php echo $language['lg_country']; ?> <span class="text-danger">*</span></label>

											<select name="ship_country" id="country" class="form-control" required>
												<option value=""><?php echo $language['lg_select_country']; ?></option>

											</select>
										</div>
									</div>



									<div class="col-md-6 col-sm-12">
										<div class="form-group card-label">
											<label><?php echo $language['lg_state__province']; ?> <span class="text-danger">*</span></label>

											<select name="ship_state" id="state" class="form-control" required>
												<option value=""><?php echo $language['lg_select_state']; ?></option>

											</select>
										</div>
									</div>

									<div class="col-md-6 col-sm-12">
										<div class="form-group card-label">
											<label><?php echo $language['lg_city']; ?> <span class="text-danger">*</span></label>

											<select name="ship_city" id="city" class="form-control" required>
												<option value=""><?php echo $language['lg_select_city']; ?></option>

											</select>
										</div>
									</div>

									<div class="col-md-6 col-sm-12">
										<div class="form-group card-label">
											<label><?php echo $language['lg_postal_code']; ?> <span class="text-danger">*</span></label>
											<input type="text" value="<?php echo !empty($patients) ? $patients['postal_code'] : ''; ?>" name="postal_code" id="postal_code" class="form-control" required>
										</div>
									</div>


								</div>
								<!-- <div class="exist-customer">Existing Customer? <a href="#">Click here to login</a></div>  -->
							</div>
							<!-- /Personal Information -->

							<!-- Shipping Details -->
							<div class="info-widget">
								<h4 class="card-title"><?php echo $language['lg_shipping_detail']; ?></h4>
								<div class="terms-accept" hidden>
									<div class="custom-checkbox">
										<input type="checkbox" id="terms_accept">
										<label for="terms_accept"><?php echo $language['lg_ship_to_a_diffe']; ?></label>
									</div>
								</div>
								<div class="form-group card-label">
									<label class="pl-0 ml-0 mb-2"><?php echo $language['lg_order_notes_opt']; ?></label>
									<textarea rows="5" class="form-control" id="shipping" name="shipping"></textarea>
								</div>
							</div>

							<!-- /Shipping Details -->

							<div class="payment-widget">
								<h4 class="card-title"><?php echo $language['lg_payment_method']; ?></h4>


								<div class="stripe_payment" style="display: none;">
									<!-- <form action="#" method="post" id="payment-form"> -->
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
									<!-- </form> -->
								</div>

								<!-- Cybersource Payment -->
								<div class="payment-list">
									<label class="payment-radio cybersource-option">
										<input type="radio" value="Cybersource" name="payment_methods">
										<span class="checkmark"></span>
										<?php echo $language['lg_credit_or_debit']; ?>
									</label>
								</div>

								<!-- /Cybersource Payment -->



								<!-- Submit Section -->


								<div class="submit-section mt-4">
									<div class="cybersource_payment" style="display: none;">
										<div class="submit-section mt-4">
											<button type="button" id="pay_buttons" onclick="pharmacy_payment('Cybersource')" class="btn btn-primary submit-btn"><?php echo $language['lg_confirm_and_pay1']; ?></button>
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
		</form>
	</div>

</div>

</div>
<div class="col-md-6 col-lg-5 theiaStickySidebar">

	<!-- Booking Summary -->
	<div class="card booking-card">
		<div class="card-header">
			<h3 class="card-title"><?php echo $language['lg_your_order1']; ?></h3>
		</div>




		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-center mb-0">
					<tr>
						<th><?php echo $language['lg_product8']; ?></th>

						<th class="text-right"><?php echo $language['lg_price']; ?></th>
						<th class="text-right"><?php echo $language['lg_qty']; ?></th>
						<th class="text-right"><?php echo $language['lg_total1']; ?></th>

					</tr>
					<tbody>

						<?php

						$cart_total_amount = 0;
						/** @var array $cart_list */
						if ($this->cart->total_items() > 0) { ?>
							<?php
							
							foreach ($cart_list as $rows) { 
								//echo $rows['price']."-";

								// $user_currency = get_user_currency();
								// $user_currency_code = $user_currency['user_currency_code'];
								// $user_currency_sign = $user_currency['user_currency_sign'];

								$sale_price = get_doccure_currency($rows['price'], $rows['pharmacy_currency'], $user_currency['user_currency_code']); 
								$sale_price = number_format($sale_price,2);
								//echo $sale_price."|";

								$cart_total_amount += $rows['qty'] * $sale_price;

								//echo $rows['price'] * $rows['qty'].$rows['pharmacy_currency'];

							?>
								<input type="hidden" name="currency_code" id="currency_code" value="<?php echo $user_currency_code ?>">
								<tr>
									<td><?php echo $rows['name']; ?> </td>
									<td class="text-right"><?php echo $sale_price; ?></td>
									<td class="text-right"><?php echo $rows['qty']; ?></td>
									<td class="text-right"><?php echo $rows['qty'] * $sale_price; ?></td>
								</tr>
						<?php }
						} ?>
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
																			echo $user_currency_sign; ?><?php echo $cart_total_amount; ?></span></li>

						<li><?php echo $language['lg_transcation_cha']; ?> (<?php echo $transcation_charge_amt ?>%)<span><?php echo $user_currency_sign; ?><?php echo number_format($transcation_charge, 2, '.', ''); ?></span></li>

						<li><?php echo $language['lg_tax_amount']; ?> (<?php echo $tax ?>%)<span><?php echo $user_currency_sign; ?><?php echo $tax_amount; ?></span></li>

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
								<span class="total-cost"><?php echo $user_currency_sign; ?><?php echo number_format($total_amount, 2, '.', ''); ?></span>
							</li>
							<input type="hidden" name="total_amount" id="total_amount" value="<?php echo number_format($total_amount, 2, '.', ''); ?>">
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	if ($this->session->userdata('role') == 2) { 
	
	 //$this->session->set_userdata("amount",100);
	 //print_r($this->session->userdata);
	 //die("asffe");
	?>
	
	
		<form id="payment_confirmation" action="<?php echo $paymentFormURL; ?>" method="post" autocomplete="off">
			<?php
			/** @var array $patients */
			$address = !empty($patients['address1']) ? $patients['address1'] : $language['lg_no_address_spec'];
			$countryname = !empty($patients['sortname']) ? $patients['sortname'] : 'US';
			$statename = !empty($patients['state_code']) ? $patients['state_code'] : '';
			$cityname = !empty($patients['cityname']) ? $patients['cityname'] : $language['lg_no_address_spec'];
			$postal_code = !empty($patients['postal_code']) ? $patients['postal_code'] : '94043';

			$encryptValue = encryptor_decryptor('encrypt', $session_id . "_Pharmacy");
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
				"amount" => number_format($total_amount, 2, '.', ''),
				//"currency" => $user_currency_code,
				"currency" => $this->session->userdata('pharmacy_currency_code'),
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

			// echo '<pre>';
			// print_r($params);
			// echo '</pre>';
			// echo (sign($params));
			// die("dds");

			foreach ($params as $name => $value) {
				echo "<input hidden type=\"hidden\" id=\"" . $name . "\" name=\"" . $name . "\" value=\"" . $value . "\"/>\n";
			}
			echo "<input type=\"hidden\" id=\"signature\" name=\"signature\" value=\"" . sign($params) . "\"/>\n";
			?>

		</form>
	<?php   } ?>


	<!-- /Booking Summary -->

</div>

</div>
</form>
</div>
<!-- /Page Content -->


<?php
$stripe_option = !empty(settings("stripe_option")) ? settings("stripe_option") : "";
if ($stripe_option == '1') {
	$stripe_api_key = !empty(settings("sandbox_api_key")) ? settings("sandbox_api_key") : "";
}
if ($stripe_option == '2') {
	$stripe_api_key = !empty(settings("live_api_key")) ? settings("live_api_key") : "";
}
?>

<script type="text/javascript">
	var stripe_api_key = '<?php
							/** @var string $stripe_api_key */
							echo $stripe_api_key; ?>';

	var country = '<?php echo (!empty($patients)) ? $patients['country'] : ""; ?>';
	var state = '<?php echo (!empty($patients)) ? $patients['state'] : ""; ?>';
	var city = '<?php echo (!empty($patients)) ? $patients['city'] : ""; ?>';


	var country_code = '';
</script>