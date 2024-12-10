<!-- Page Content -->

<div class="content">
        <div class="container-fluid">
          
          <div class="row">
            <div class="col-md-8 offset-md-2">
                
              <!-- Register Content -->
              <div class="account-content">
                <div class="row align-items-center justify-content-center">
                  <div class="col-md-7 col-lg-6 login-left">
                     <img src="<?php echo !empty(base_url().settings("login_image"))?base_url().settings("login_image"):base_url()."assets/img/login-banner.png";?>" class="img-fluid" alt="Doccure Login">  
                  </div>
                  <div class="col-md-12 col-lg-6 login-right">
                    <div class="login-header">
                    <button type="button" onclick="handleRoleChange(2)" id="pat_btn" class="active btn btn-outline-primary"><?php echo $language['lg_patient4']; ?></button>
                    <button type="button" onclick="handleRoleChange(1)" id="doc_btn" class="btn btn-outline-primary"><?php echo $language['lg_doctor2']; ?></button>
                      <button type="button" onclick="handleRoleChange(5)" id="pha_btn" class="btn btn-outline-primary"><?php echo $language['lg_pharmacy']; ?></button>
                      <button type="button" onclick="handleRoleChange(4)" id="lab_btn" class="btn btn-outline-primary">Labs</button>
                       <button type="button" onclick="handleRoleChange(6)" id="cli_btn" class="btn btn-outline-primary">Clinic</button>
                    </div>
                    
                    <!-- Register Form -->
                    <form method="post" id="register_form" autocomplete="off" action="#">
                      <input type="hidden" id="role" name="role" value="2">
                      <div class="form-group form-focus">
                        <input type="text" name="first_name" id="first_name" minlength="3" class="form-control floating">
                        <label class="focus-label"><?php echo $language['lg_first_name'];?></label>
                      </div>
                      <div class="form-group form-focus">
                        <input type="text" name="last_name" id="last_name" class="form-control floating">
                        <label class="focus-label"><?php echo $language['lg_last_name'];?></label>
                      </div>
                      <div class="form-group form-focus">
                        <input type="email" name="email" id="email" class="form-control floating">
                        <label class="focus-label"><?php echo $language['lg_email'];?></label>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                          <select name="country_code" class="form-control select" id="country_code" class="required">
                          <option value=""><?php echo $language['lg_select_country_'];?></option> 
                        </select>
                          
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group form-focus">
                        <input type="text" name="mobileno" id="mobileno" class="form-control floating">
                        <label class="focus-label"><?php echo $language['lg_mobile_number']; ?></label>
                        </div>
                        </div>
                      </div>
                      <?php if(settings('tiwilio_option')=='1') { ?>
                      <div class="text-right otp_load">
                        <a class="forgot-link"  href="javascript:void(0);" id="sendotp"><?php echo $language['lg_send_otp']; ?></a>
                      </div>
                      <div class="form-group form-focus OTP">
                        <input type="text" name="otpno" id="otpno" class="form-control floating">
                        <label class="focus-label"><?php echo $language['lg_otp2']; ?></label>
                      </div>
                     <?php } ?>
                     
                          
                      
                      <div class="form-group form-focus">
                        <input type="password" name="password" id="password" class="form-control floating">
                        <span class="far fa-eye" id="togglePassword1"></span>
                        <label class="focus-label"><?php echo $language['lg_password'];?></label>
                      </div>
                      <div class="form-group form-focus">
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control floating">
                        <span class="far fa-eye" id="togglePassword2"></span>
                        <label class="focus-label"><?php echo $language['lg_confirm_passwor'];?></label>
                      </div>
                      

                      <div class="patient_role_2" style="    text-align: center;
    padding: 15px 0;
    font-weight: 600;
    background: #f9f9f9;
    margin-bottom: 10px;
    border-radius: 3px;" >
                            <label class="focus-label"><span>Registration Charges: </span><span><?php echo '$'.settings('registration_fee_2');
                            $amount = settings('registration_fee_2');
                            ?></span></label>
                            
                          </div>
                          <div class="doctor_role_1" style="    text-align: center;
    padding: 15px 0;
    font-weight: 600;
    background: #f9f9f9;
    margin-bottom: 10px;
    border-radius: 3px;">
                            <label class="focus-label"> <span>Registration Charges: </span><span><?php echo '$'.settings('registration_fee_1'); ?></span</label>
                          
                            
                          </div>
                          <div class="lab_role_4" style="    text-align: center;
    padding: 15px 0;
    font-weight: 600;
    background: #f9f9f9;
    margin-bottom: 10px;
    border-radius: 3px;">
                            <label class="focus-label"><span>Registration Charges: </span><span><?php echo '$'.settings('registration_fee_4'); ?></span></label>
                          
                            
                          </div>
                          <div class="pharmacy_role_5" style="    text-align: center;
    padding: 15px 0;
    font-weight: 600;
    background: #f9f9f9;
    margin-bottom: 10px;
    border-radius: 3px;">
                            <label class="focus-label"><span>Registration Charges: </span><span><?php echo '$'.settings('registration_fee_5'); ?></span></label>
                            
                            
                          </div>
                          <div class="clinic_role_6" style="    text-align: center;
    padding: 15px 0;
    font-weight: 600;
    background: #f9f9f9;
    margin-bottom: 10px;
    border-radius: 3px;">
                           <label class="focus-label"><span>Registration Charges: </span><span><?php echo '$'.settings('registration_fee_6'); ?></span></label>
                            
                            
                          </div>
                          <div style="padding-bottom:5px;">
                            <span><span style="font-weight:600;">Note:</span> These costs help us operate our platform and offer customer support</span>
                          </div>

                      <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" name="agree_statement" id="agree_statement" class="custom-control-input" value="1">
                        <label class="custom-control-label" for="agree_statement"><?php echo $language['lg_i_agree_to'];?>&nbsp;<?php echo !empty(settings("website_name"))?settings("website_name"):"Doccure";?>&nbsp;<a style="border-bottom: 1px solid;" target="_blank" href="<?php base_url(); ?>terms-conditions"><?php echo $language['lg_terms_and_condi'];?></a> & <a style="border-bottom: 1px solid;" target="_blank" href="<?php base_url(); ?>privacy-policy"><?php echo $language['lg_privacy_policy'];?></a> </label>
                      </div>

                      <div class="text-right">
                        <a class="forgot-link" href="<?php echo base_url();?>signin"><?php echo $language['lg_already_have_an'];?></a>
                      </div>
                      <button class="btn btn-primary btn-block btn-lg login-btn" id="register_btn" type="submit"><?php echo $language['lg_signup'];?> </button>
                      <!-- <button type="button" id="pay_buttons" onclick="pharmacy_reg_payment('Cybersource')" class="btn btn-primary submit-btn"><?php echo $language['lg_confirm_and_pay1']; ?></button> -->
                      <div class="row s-btn" style="margin-top: 10px">
                        <!-- <div class="col-2"></div> -->
                        <div class="col-12">
                            <button class="btn btn-social p-0 btngoogle btn-full-width" type="button" id="googleloginbtn"><i class="fab fa-google float-left"></i><?php echo $language['lg_signup'];?></button>
                            <?php /* ?><button class="btn btn-social btn-facebook" type="button" onclick="fbLogin()"><i class="fab fa-facebook-f float-left"></i><?php echo $language['lg_signup'];?></button>
                            <?php   */ ?>
                        </div>
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

                    </form>
                    <!-- /Register Form -->
                    
                  </div>
                </div>
              </div>
              <!-- /Register Content -->
                
            </div>
          </div>

        </div>

      </div>    
      <!-- /Page Content -->


      

<!-- <form id="payment_reg_confirmation" action="<?php echo $paymentFormURL; ?>" method="post" autocomplete="off">
      <?php
			/** @var array $patients */
		

			$encryptValue = encryptor_decryptor('encrypt', "unique" . "_register");
			$encryptType = "";
			
			$params = array(

				"access_key" => $cyb_access_key,
				"profile_id" => $cyb_profileid,

				"transaction_uuid" => uniqid(),
				"signed_field_names" => "access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency",
				"unsigned_field_names" => "",
				"signed_date_time" => gmdate("Y-m-d\TH:i:s\Z"),
				"locale" => "en",
				"transaction_type" => "authorization",
				"reference_number" => time(),
				"amount" => number_format($amount, 2, '.', ''),
				//"currency" => $user_currency_code,
				"currency" => "USD",
				
			);
      // $signed_params = sign($params);
      // if($signed_params){
      //   echo "signed";
      // }else{
      // echo $signed_params;}
      // // die("sd");
      // echo '<pre>';
      // print_r($params);
      // echo '</pre>';
      // echo (sign($params));
      //  die("sd");

			foreach ($params as $name => $value) {
				echo "<input hidden type=\"hidden\" id=\"" . $name . "\" name=\"" . $name . "\" value=\"" . $value . "\"/>\n";
			}
    
			// echo "<input type=\"hidden\" id=\"signature\" name=\"signature\" value=\"" . sign($params) . "\"/>\n";
      // echo "<input type=\"submit\" name=\"submit\" id=\"submit\" value=\"submit\"/>";
			?>
      </form> -->

      <script>
// Function to handle button click and manage role changes
			

</script>
<!-- <script src="<?php echo base_url();?>assests/js/web.js"></script> -->


