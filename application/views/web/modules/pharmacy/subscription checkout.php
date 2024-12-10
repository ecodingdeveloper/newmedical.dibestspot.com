<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><?php 
									 /** @var array $language */
									echo $language['lg_dashboard'];?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_subscription_checkout'] ?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_checkout'] ?></h2>
						</div>
					</div>
				</div>
			</div>
			<!-- /Breadcrumb -->
			
			<!-- Page Content -->
			<div class="content">
				<div class="container-fluid">
					
					<div class="row">
						<div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">
							
							<!-- Profile Sidebar -->
							<?php $this->load->view('web/includes/pharmacy_sidebar');?>
							<!-- /Profile Sidebar -->
							
						</div>
						
						<div class="col-md-7 col-lg-8 col-xl-9">
						<div id="account">
					<div class="row">
						
                                            <div class="col-md-7 col-lg-8">
							<div class="card">
								<div class="card-body">
								
									<!-- Checkout Form -->
																			
										<div class="payment-widget">
											<h4 class="card-title"><?php echo $language['lg_payment_method'];?></h4>
											
											<!-- Credit Card Payment -->
											<div class="payment-list">
												<label class="payment-radio credit-card-option">
													<input type="radio" value="Card Payment" name="payment_methods">
													<span class="checkmark"></span>
													<?php echo $language['lg_credit_card'];?>
												</label>
											</div>
											<!-- /Credit Card Payment -->
											<div class="stripe_payment" style="display: none;">
											  <form action="#" method="post" id="payment-form">
											  <div>
											    <label for="card-element">
											      <?php echo $language['lg_credit_or_debit'];?>
											    </label>
											    <div id="card-element" style="width: 100%">
											      <!-- A Stripe Element will be inserted here. -->
											    </div>

											    <!-- Used to display form errors. -->
											    <div id="card-errors" role="alert"></div>
											  </div>
											<div class="submit-section mt-4 mb-4">
											  <button class="btn btn-primary submit-btn" id="stripe_pay_btn"><?php echo $language['lg_confirm_and_pay1'];?></button>
											    </div>
											</form>
											</div>
											
											<!-- Paypal Payment -->
											<div class="payment-list">
												<label class="payment-radio paypal-option">
													<input type="radio" value="PayPal" name="payment_methods">
													<span class="checkmark"></span>
													<?php echo $language['lg_paypal'];?>
												</label>
											</div>
											<!-- /Paypal Payment -->

											
											
											<!-- Terms Accept -->
											<!-- <div class="terms-accept">
												<div class="custom-checkbox">
												    <input type="checkbox" name="terms_accept" id="terms_accept" value="1">
												   <label for="terms_accept">I have read and accept <a href="#">Terms &amp; Conditions</a></label>
												</div>
											</div> -->
											<!-- /Terms Accept -->
											
											<!-- Submit Section -->
											<div class="submit-section mt-4">
												<div class="paypal_payment" style="display: none;">
												<div class="submit-section mt-4">
												  <button type="button"  id="pay_buttons" onclick="appoinment_payment('paypal')" class="btn btn-primary submit-btn"><?php echo $language['lg_confirm_and_pay'];?></button>
												</div>
												</div>
												<div class="clinic_payment" style="display: none;">
												<div class="submit-section mt-4">
												   <button type="button" id="pay_button" onclick="appoinment_payment('stripe')" class="btn btn-primary submit-btn"><?php echo $language['lg_book_appointmen'];?></button>
												</div>
												</div>
											</div>
											<!-- /Submit Section -->
											
										</div>

										<form role="form" method="POST" id="payment_formid" action="<?php echo base_url().'subscriptions/paypal_pay';?>">
						 <?php					
						 $address = !empty($doctors['address1'])?$doctors['address1']:$language['lg_no_address_spec']; 
						 //$info = $language['lg_booking_appoinm'].' '.$doctors['first_name'].' '.$doctors['last_name']; 
						  /** @var array $subscription_plan_detail */
						  /** @var array $doctors */
						 $info = $subscription_plan_detail->plan_name;; 
			              ?>
			              <input type="hidden" name="productinfo" id="productinfo"   value="<?php echo $info ?>" />
			              <input type="hidden"  name="name" id="name"  value="<?php echo $doctors['first_name'].' '.$doctors['last_name']; ?>" /> 
			              <input type="hidden"  name="phone" id="phone"  value="<?php echo $doctors['mobileno'] ?>"/>  
			              <input type="hidden"  name="email" id="email"  value="<?php echo $doctors['email'] ?>" />
			              <input type="hidden"  name="address1" id="address1" value="<?php echo $address; ?>">
			              <input type="hidden" class="form-control" id="amount" name="amount" value="<?php echo $this->session->userdata('plan_amount'); ?>"  readonly/>
			              <input type="hidden" name="access_token" id="access_token" > 
			              <input type="hidden" name="payment_method" id="payment_method" value="Card Payment" >
										</form>
									
									<!-- /Checkout Form -->
									
								</div>
							</div>
							
						</div>
						
						<!--<div class="col-md-5 col-lg-4 theiaStickySidebar">-->
						<div class="col-md-5 col-lg-4">
						
							<!-- Booking Summary -->
							<div class="card booking-card">
								<div class="card-header">
									<h4 class="card-title"><?php echo $language['lg_booking_summary'];?></h4>
								</div>
								<div class="card-body">
									<?php
											$profileimage=(!empty($doctors['profileimage']))?base_url().$doctors['profileimage']:base_url().'assets/img/user.png';
										?>
								
									<!-- Booking Doctor Info -->
									<div class="booking-doc-info">
										<a href="<?php echo base_url().'doctor-preview/'.$doctors['username'];?>" class="booking-doc-img">
											<img src="<?php echo $profileimage;?>" alt="User Image">
										</a>
										<div class="booking-info">
											<h4><a href="<?php echo base_url().'doctor-preview/'.$doctors['username'];?>"><?php echo $language['lg_dr'];?> <?php echo ucfirst($doctors['first_name'].' '.$doctors['last_name']);?></a></h4>
											<?php /* <div class="rating">
												<?php
						                        $rating_value=$doctors['rating_value'];
						                        for( $i=1; $i<=5 ; $i++) {
						                          if($i <= $rating_value){                                        
						                          echo'<i class="fas fa-star filled"></i>';
						                          }else { 
						                          echo'<i class="fas fa-star"></i>';
						                          } 
						                        } 
						                      ?>
												<span class="d-inline-block average-rating">(<?php echo $doctors['rating_count'];?>)</span>
											</div> */ ?>
											<div class="clinic-details">
												<p class="doc-location"><i class="fas fa-map-marker-alt"></i> <?php echo $doctors['cityname'].', '. $doctors['countryname'];?></p>
											</div>
										</div>
									</div>
									<!-- Booking Doctor Info -->
									
									<div class="booking-summary">
										<div class="booking-item-wrap">
											<ul class="booking-date"> <br /><br />
										<li> <?php echo $language['lg_subscription_plan_name'] ?> :  <span> <?php echo $subscription_plan_detail->plan_name;
					                       
	                                       ?></span>
                                        </li><br>
										<li><?php echo $language['lg_subscription_plan_amount'] ?> : <span>$ <?php echo $subscription_plan_detail->plan_amount; ?></span>
					                    </li>
											</ul><br>
										<?php
										 /** @var array $subscription_details */
                                                                                    $tax=!empty(settings("tax"))?settings("tax"):"0";;
                                                                                    $amount = $subscription_details['plan_amount'];
                                                                                    //$transcation_charge = $this->session->userdata('transcation_charge');
                                                                                    $transcation_charge = 0;
                                                                                    //$tax_amount = $this->session->userdata('tax_amount');                
                                                                                    $tax_amount = 0;                
                                                                                    $total_amount = number_format($amount,2); 

                                                                                ?>    
											<ul class="booking-fee">
												<!--<li><?php //echo $language['lg_call_charge'];?> <span>$<?php //echo number_format($amount,2); ?></span></li>
												<li><?php //echo $language['lg_transaction_cha'];?> <span>$<?php //echo number_format($transcation_charge,2); ?></span></li>
												<li><?php //echo $language['lg_tax'];?> (<?php //echo $tax ?>%)<span>$<?php //echo $tax_amount; ?></span></li>-->
											</ul>
											<div class="booking-total">
												<ul class="booking-total-list">
													<li>
														<span><?php echo $language['lg_total1'];?></span>
														<span class="total-cost">GFr <?php echo $total_amount; ?></span>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- /Booking Summary -->
							
						</div>
                                            
                                            
						
					</div>
					
					

				   
				
				</div>

			</div>
					</div>
				</div>

			</div>		
                        
                         <button id="my_book_appoinment" style="display: none;"><?php echo $language['lg_purchase'];?></button>
                        
			<!-- /Page Content -->
   
                        
                        <?php
				$stripe_option=!empty(settings("stripe_option"))?settings("stripe_option"):"";
		         if($stripe_option=='1'){
		            $stripe_api_key=!empty(settings("sandbox_api_key"))?settings("sandbox_api_key"):"";
		         }
		         if($stripe_option=='2'){
		            $stripe_api_key=!empty(settings("live_api_key"))?settings("live_api_key"):"";
		         }
		         ?>
			
			<script type="text/javascript">
				var stripe_api_key='<?php 
				 /** @var string $stripe_api_key */
				echo $stripe_api_key;?>';
                                
			</script>