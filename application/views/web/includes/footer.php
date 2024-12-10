<!-- Footer -->
<footer class="footer">

  <!-- Footer Top -->
  <div class="footer-top">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-4 col-md-6">

          <!-- Footer Widget -->
          <div class="footer-widget footer-about">
            <div class="footer-logo">
              <img style="width: 201px;height: auto;" src="<?php echo !empty(base_url() . settings("logo_footer")) ? base_url() . settings("logo_footer") : base_url() . "assets/img/logo.png"; ?>" alt="logo">
            </div>
            <div class="footer-about-content">
              <p><?php
                  /** @var array $language */
                  echo $language['lg_footer_content_']; ?></p>
              <div class="social-icon">
                <ul>
                  <?php
                  if (!empty(settings("facebook"))) {
                    echo '<li>
                                <a href="' . settings("facebook") . '" target="_blank"><i class="fab fa-facebook-f"></i> </a>
                              </li>';
                  }

                  if (!empty(settings("twitter"))) {
                    echo '<li>
                                <a href="' . settings("twitter") . '" target="_blank"><i class="fab fa-twitter"></i> </a>
                              </li>';
                  }

                  if (!empty(settings("linkedIn"))) {
                    echo '<li>
                              <a href="' . settings("linkedIn") . '" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                              </li>';
                  }
                  if (!empty(settings("instagram"))) {
                    echo '<li>
                              <a href="' . settings("instagram") . '" target="_blank"><i class="fab fa-instagram"></i></a>
                              </li>';
                  }
                  if (!empty(settings("google_plus"))) {
                    echo '<li>
                               <a href="' . settings("google_plus") . '" target="_blank"><i class="fab fa-google-plus"></i> </a>
                              </li>';
                  }
                  ?>
                </ul>
              </div>
            </div>
          </div>
          <!-- /Footer Widget -->

        </div>

        <div class="col-lg-4 col-md-6">

          <!-- Footer Widget -->
          <div class="footer-widget footer-menu">
            <h2 class="footer-title"><?php echo $language['lg_for_patients']; ?></h2>
            <ul>

              <?php
              if ($this->session->userdata('role') == 2) {
                $patient_dashboard_link = base_url() . 'dashboard';
              } else {
                $patient_dashboard_link = '';
              }
              ?>


              <li><a href="<?php echo base_url(); ?>doctors-search"><?php echo $language['lg_search_for_doct']; ?></a></li>
              <li><a href="<?php echo base_url(); ?>signin"><?php echo $language['lg_login']; ?></a></li>
              <li><a href="<?php echo base_url(); ?>register"><?php echo $language['lg_register']; ?></a></li>
              <li><a href="<?php echo $patient_dashboard_link ?>"><?php echo $language['lg_patient_dashboa']; ?></a></li>
            </ul>
          </div>
          <!-- /Footer Widget -->

        </div>

        

        <div class="col-lg-4 col-md-6">

          <!-- Footer Widget -->
          <div class="footer-widget footer-menu">
            <h2 class="footer-title"><?php echo $language['lg_for_doctors']; ?></h2>
            <ul>

              <?php
              if ($this->session->userdata('role') == 1) {
                $doctor_dashboard_link = base_url() . 'dashboard';
              } else {
                $doctor_dashboard_link = '';
              }


              if ($this->session->userdata('role') != 4 || $this->session->userdata('role') != 5) {
                $appoinment_link = '';
              } else {
                $appoinment_link = base_url() . 'appoinments';
              }

              if ($this->session->userdata('role') != 4 || $this->session->userdata('role') != 5) {
                $message_link = '';
              } else {
                $message_link = base_url() . 'messages';
              }
              ?>

              <li><a href="<?php echo $appoinment_link ?>"><?php echo $language['lg_appointments']; ?></a></li>
              <li><a href="<?php echo $message_link ?>"><?php echo $language['lg_chat1']; ?></a></li>
              <li><a href="<?php echo base_url(); ?>signin"><?php echo $language['lg_login']; ?></a></li>
              <li><a href="<?php echo base_url(); ?>register"><?php echo $language['lg_register']; ?></a></li>
              <li><a href="<?php echo $doctor_dashboard_link ?>"><?php echo $language['lg_doctor_dashboar']; ?></a></li>
            </ul>
          </div>
          <!-- /Footer Widget -->

        </div>

        <div class="col-lg-4 col-md-6">

          <!-- Footer Widget -->
          <div class="footer-widget footer-menu">
            <h2 class="footer-title">Other Links</h2>
            <ul>

              <li><a href="https://cms.dibestspot.com/register" target="_blank" >Register to Join the Marketplace</a></li>
              <li><a href="https://dibestspot.com/" target="_blank">DiBest Spot Platform</a></li>
              <li><a href="https://crm.dibestspot.com/authentication/login" target="_blank">DiBest Spot Business Solutions</a></li>
              <li><a href="https://dpaymoney.dibestspot.com/" target="_blank">DPAYmoney</a></li>
              <li><a href="https://plaza.dibestspot.com/" target="_blank">DiBest Spot: Plaza</a></li>

            </ul>
          </div>
          <!-- /Footer Widget -->

        </div>

        <div class="col-lg-4 col-md-6">

          <!-- Footer Widget -->
          <div class="footer-widget footer-contact">
            <h2 class="footer-title"><?php echo $language['lg_contact_us']; ?></h2>
            <div class="footer-contact-info">
              <div class="footer-address">
                <span><i class="fas fa-map-marker-alt"></i></span>
                <p> <?php echo !empty(settings("address")) ? settings("address") : ""; ?> <?php echo !empty(settings("zipcode")) ? settings("zipcode") : ""; ?> </p>
              </div>
              <p>
                <i class="fas fa-phone-alt"></i>
                <?php echo !empty(settings("contact_no")) ? settings("contact_no") : "9876543210"; ?>
              </p>
              <p class="mb-0">
                <i class="fas fa-envelope"></i>
                <?php echo !empty(settings("email")) ? settings("email") : ""; ?>
              </p>
            </div>
          </div>
          <!-- /Footer Widget -->

        </div>

      </div>
    </div>
  </div>
  <!-- /Footer Top -->

  <!-- Footer Bottom -->
  <div class="footer-bottom">
    <div class="container-fluid">

      <!-- Copyright -->
      <div class="copyright">
        <div class="row">
          <div class="col-md-6 col-lg-6">
            <div class="copyright-text">
              <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo !empty(settings("website_name")) ? settings("website_name") : "DiBest Spot"; ?>&nbsp;<?php echo $language['lg_limited']; ?>. <?php echo $language['lg_all_rights_rese']; ?></p>
            </div>
          </div>
          <div class="col-md-6 col-lg-6">

            <!-- Copyright Menu -->
            <div class="copyright-menu">
              <ul class="policy-menu">
                <li><a href="<?php echo base_url(); ?>terms-conditions"><?php echo $language['lg_terms_and_condi']; ?></a></li>
                <li><a href="<?php echo base_url(); ?>privacy-policy"><?php echo $language['lg_privacy_policy']; ?></a></li>
              </ul>
            </div>
            <!-- /Copyright Menu -->

          </div>
        </div>
      </div>
      <!-- /Copyright -->

    </div>
  </div>
  <!-- /Footer Bottom -->
   <?php
   $message="DiBest Medical Support";
   ?>
 <a href="https://wa.me/+16232007731/?text=<?php echo $message;?>" class="whatsapp-floating-button" style="position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    border-radius: 50%;
    background-color: #25D366;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);" target="_blank">
    <img style=" width: 60px;
    height: 60px;
    display: block;" src="<?php echo base_url(); ?>uploads/logo/whatsapp.png" alt="WhatsApp" />
</a>
</footer>	
<!-- /Footer -->

</div>
<!-- /Main Wrapper -->

<!-- <?php if($module=='chat' && $page=='index') { ?>
      <script src="<?php echo base_url();?>assets/js/admin_chat.js"></script>
      <?php } ?> -->

<!--modal Section---->

<?php
/** @var string $module */
/** @var string $page */
if ($module == 'doctor' || $module == 'patient' || $module == 'pharmacy' || $module == 'lab') {
  if ($page == 'appoinments' || $page == 'doctor_dashboard' || $page == 'lab_appoinments') { ?>
    <div class="modal fade" id="view_docs" aria-hidden="true" role="dialog">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <!--  <div class="modal-header">
          <h5 class="modal-title">Delete</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>-->
          <div class="modal-body">
            <div class="form-content p-2">
              <input type="hidden" id="user_id">
              <h4 class="modal-title">Lab Results</h4>

              <div>

              </div>
              <div class="modal-body">
                <div class="form-content p-2">
                  <ul id="links" style="list-style-type: none;">

                  </ul>

                </div>
              </div>


              <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade custom-modal" id="appoinments_details">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?php echo $language['lg_appointment_det']; ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="info-details">
              <li>
                <div class="details-header">
                  <div class="row">
                    <div class="col-md-8">
                      <span class="title"><?php echo $language['lg_appointment_dat']; ?></span>
                      <span class="text app_date"></span>
                    </div>
                    <div class="col-md-6">
                      <div class="text-right">
                        <!--  <button type="button" class="btn bg-success-light btn-sm" id="topup_status">Completed</button> -->
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <span class="title"><?php echo $language['lg_appoinment_type']; ?></span>
                <span class="text type"></span>
              </li>
              <li>
                <span class="title"><?php echo $language['lg_confirm_date']; ?></span>
                <span class="text book_date"></span>
              </li>
              <li>
                <span class="title"><?php echo $language['lg_paid_amount']; ?></span>
                <span class="text amount"></span>
              </li>

            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade custom-modal" id="reschedule_details">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?php echo $language['lg_appointment_det'];?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="">

            <div class="card booking-schedule schedule-widget">
							
              <!-- Schedule Header -->
              <div class="schedule-header border-0">
                <div class="row text-center">
                  <!-- <div class="booking-option">
                <label class="payment-radio credit-card-option">
                  <input type="radio" name="type" value="online" id="online">
                  <span class="checkmark"></span>
                  <?php echo $language['lg_online'];?>
                </label>
                <label class="payment-radio credit-card-option">
                  <input type="radio" name="type" value="clinic" id="clinic">
                  <span class="checkmark"></span>
                  <?php echo $language['lg_clinic'];?>
                </label>
                <label class="payment-radio credit-card-option">
                  <input type="radio" name="type" value="both" id="both" checked="checked">
                  <span class="checkmark"></span>
                  <?php echo $language['lg_both'];?>
                </label>
              </div> -->
                  <div class="col-md-12">
                    <input type="hidden" name="doctor_id" id="doctor_id" value="<?php echo $doctors['userid'];?>">
                    <input type="hidden" name="price_type" id="price_type" value="<?php echo $doctors['price_type'] ?>">
                                          <input type="hidden" name="hourly_rate" id="hourly_rate" value="<?php echo $doctors['amount'] ?>">
                                          <input type="hidden" name="role_id" id="role_id" value="<?php echo $doctors['role'] ?>">
                    <div class="row">

                    <div class="col-sm-6 col-12 avail-time">
                    
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><?php echo $language['lg_date1']; ?>:</span>
                      </div>
                      <input type="text" class="form-control" name="schedule_date" id="schedule_date" readonly="" value="<?php 
                    /** @var string $schedule_date */
                      echo $schedule_date;?>" min="<?php echo date("Y-m-d"); ?>" onchange="getSchedule('');">
                      <!-- <div class="input-group-append">
                        <input type="button" name="submit" value="Search" class="btn btn-primary" onclick="getSchedule();">
                      </div> -->
                    </div>
                    <div id="schedule_date_error"></div>
                   </div>
                    <!-- <div class="col-sm-6 col-12 schedule-back text-right">           
                    <a href="javascript:void(0);" class="btn btn-primary" onclick="history.back();"><i class="fas fa-chevron-left"></i> <?php echo $language['lg_back']; ?></a>
                    </div> -->
                  </div>
                    <div class="card border-0 mb-0">
                      <div class="card-body pb-0 bookings-schedule">
                        
                        
                      </div>
                    </div>
                    
                  </div>
                </div>
              </div>
              <!-- /Schedule Header -->
              
              <!-- Schedule Content -->
              <div class="schedule-cont">
                <div class="row">
                  <div class="col-md-12">
                  
                    
                  </div>
                </div>
              </div>
              <!-- /Schedule Content -->
              
            </div>

            <div class="modal-body">
              <p>Want to reschedule the appointment?</p>
            </div>
            <div class="modal-footer">
              <button type="submit" id="change_btn" class="btn btn-primary"><?php echo $language['lg_yes']; ?></button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $language['lg_no6']; ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade custom-modal" id="assign_doctor">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?php echo $language['lg_assign_doctor']; ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="info-details">


              <?php if ($this->session->userdata('role') == 6) { ?>
                <li>

                  <span class="text ">
                    <input type="hidden" id="app_id_assign" class="app_id" value="">
                    <select name="assign_doc" id="assign_doc" onchange="assign_doc()" class="form-control">
                      <option>Select Doctor</option>
                    </select>
                  </span>
                </li>
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade custom-modal" id="appoinments_status_modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="app-modal-title"><?php echo $language['lg_accept']; ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="<?php echo base_url('appoinments/change_status') ?>">
            <input type="hidden" id="appoinments_id" name="appoinments_id">
            <input type="hidden" id="appoinments_status" name="appoinments_status">

            <div class="modal-body">
              <p><?php echo $language['lg_are_you_sure_wa1']; ?> <span id="app-modal-title"></span> <?php echo $language['lg_this_appoinment']; ?></p>
            </div>
            <div class="modal-footer">
              <button type="submit" id="change_btn" class="btn btn-primary"><?php echo $language['lg_yes']; ?></button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $language['lg_no6']; ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade custom-modal" id="appoinments_status_complete_modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="app-modal-title"><?php echo $language['lg_complete']; ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="<?php echo base_url('appoinments/change_complete_status') ?>">
            <input type="hidden" id="complete_appoinments_id" name="complete_appoinments_id">

            <div class="modal-body">
              <p><?php echo $language['lg_are_you_sure_wa1']; ?> <span id="app-complete-modal-title"></span> <?php echo $language['lg_this_appoinment']; ?></p>
            </div>
            <div class="modal-footer">
              <button type="submit" id="change_complete_btn" class="btn btn-primary"><?php echo $language['lg_yes']; ?></button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $language['lg_cancel']; ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>

  <?php }
  if ($page == 'checkout') { ?>

    <!-- Forgot Password Modal -->
    <div class="modal fade show" id="forgot_password_modal" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3><?php echo $language['lg_forgot_password']; ?></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form id="reset_password" method="post" autocomplete="off">
            <div class="modal-body">
              <p><?php echo $language['lg_enter_your_emai']; ?></p>
              <div class="form-group form-focus">
                <input type="email" name="resetemail" id="resetemail" class="form-control floating">
                <label class="focus-label"><?php echo $language['lg_email']; ?></label>
              </div>
              <div class="text-right">
                <a class="forgot-link" href="javascript:;" onclick="login()"><?php echo $language['lg_remember_your_p']; ?></a>
              </div>
              <div class="modal-footer">
                <button id="reset_pwd" class="btn btn-primary btn-block btn-lg login-btn" type="submit"><?php echo $language['lg_reset_password']; ?></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /Forgot Password Modal -->

    <!-- Login Modal -->
    <div class="modal fade show" id="login_modal" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title"><?php echo $language['lg_login']; ?> <span><?php echo !empty(settings("meta_title")) ? settings("meta_title") : "Doccure"; ?></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form id="signin_form" method="post">
            <div class="modal-body">
              <div class="form-group form-focus">
                <input type="text" name="email" id="login_email" class="form-control floating">
                <label class="focus-label"><?php echo $language['lg_email_or_mobile'] ?></label>
              </div>
              <div class="form-group form-focus">
                <input type="password" name="password" id="password" class="form-control floating">
                <label class="focus-label"><?php echo $language['lg_password']; ?></label>
              </div>
              <div class="text-right">
                <a class="forgot-link" href="javascript:;" onclick="forgot_password()"><?php echo $language['lg_forgot_password']; ?></a>
              </div>
              <div class="modal-footer d-block pl-0 pr-0">

                <button class="btn btn-primary btn-block btn-lg login-btn" id="signin_btn" type="submit"><?php echo $language['lg_signin']; ?></button>
                <div class="row w-100" style="margin-top: 10px;margin-bottom: 10px;">
                  <div class="col-md-6">
                    <button class="btn btn-social btn-google" type="button" id="googlecheckoutsigninbtn" style="width: 100%;"><i class="fab fa-google float-left"></i><?php echo $language['lg_signin']; ?></button>
                  </div>
                  <div class="col-md-6">
                    <button class="btn btn-social btn-facebook" type="button" onclick="fbcheckoutsignup()" style="width: 100%;"><i class="fab fa-facebook-f float-left"></i><?php echo $language['lg_signin']; ?></button>
                  </div>
                </div>
                <div class="text-center dont-have"><?php echo $language['lg_dont_have_an_ac']; ?> <a href="javascript:;" onclick="register()"><?php echo $language['lg_register']; ?></a></div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /Login Modal -->

    <!-- Register Modal -->
    <div class="modal fade show" id="register_modal" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title"><?php echo $language['lg_patient4']; ?> <?php echo $language['lg_register']; ?></h3>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form method="post" id="register_form" autocomplete="off">
            <div class="modal-body">
              <input type="hidden" id="role" name="role" value="2">
              <div class="form-group form-focus">
                <input type="text" name="first_name" id="first_name" class="form-control floating">
                <label class="focus-label"><?php echo $language['lg_first_name']; ?></label>
              </div>
              <div class="form-group form-focus">
                <input type="text" name="last_name" id="last_name" class="form-control floating">
                <label class="focus-label"><?php echo $language['lg_last_name']; ?></label>
              </div>
              <div class="form-group form-focus">
                <input type="email" name="email" id="register_email" class="form-control floating">
                <label class="focus-label"><?php echo $language['lg_email']; ?></label>
              </div>
              <!-- <input type="hidden" id="country_code" name="country_code" value="972"> -->
              <div class="row form-group form-focus">
                <div class="col-md-6">
                  <select name="country_code" class="form-control" id="country_code" style="padding-top:5px;">
                  </select>
                  <!-- <input type="email" name="email" id="register_email" class="form-control floating"> -->
                  <!-- <label class="focus-label" style="left:30px;"><?php echo $language['lg_email']; ?></label> -->
                </div>
                <div class="col-md-6">
                  <input type="text" name="mobileno" id="mobileno" class="form-control floating">
                  <label class="focus-label" style="left:30px;"><?php echo $language['lg_mobile_number'] ?></label>
                </div>
              </div>
              <?php if (settings('tiwilio_option') == '1') { ?>
                <div class="text-right otp_load">
                  <a class="forgot-link" href="javascript:void(0);" id="sendotp"><?php echo $language['lg_send_otp'] ?></a>
                </div>
                <div class="form-group form-focus OTP">
                  <input type="text" name="otpno" id="otpno" class="form-control floating">
                  <label class="focus-label"><?php echo $language['lg_otp'] ?></label>
                </div>
              <?php } ?>
              <div class="row form-group form-focus">
                <div class="col-md-6">
                  <input type="password" name="password" id="register_password" class="form-control floating">
                  <label class="focus-label" style="left:30px;"><?php echo $language['lg_password']; ?></label>
                </div>
                <div class="col-md-6">
                  <input type="password" name="confirm_password" id="register_confirm_password" class="form-control floating">
                  <label class="focus-label" style="left:30px;"><?php echo $language['lg_confirm_passwor']; ?></label>
                </div>
              </div>
              <div class="text-left check_ctrl">
                <div class="text-right">
                  <a class="forgot-link" href="javascript:;" onclick="login()" style="color: #008FF8 "><?php echo $language['lg_already_have_an']; ?></a>
                </div>
              </div>
            </div>
            <div class="modal-footer d-block">
              <button class="btn btn-primary btn-block btn-lg login-btn" id="register_btn" type="submit"><?php echo $language['lg_signup']; ?> </button>
              <div class="row w-100" style="margin-top: 10px;margin-bottom: 10px;">
                <div class="col-md-6">
                  <button class="btn btn-social btn-google" type="button" id="googlecheckoutsignupbtn" style="width: 100%;"><i class="fab fa-google float-left"></i><?php echo $language['lg_signup']; ?></button>
                </div>
                <div class="col-md-6">
                  <button class="btn btn-social btn-facebook" type="button" onclick="fbcheckoutsignin()" style="width: 100%;"><i class="fab fa-facebook-f float-left"></i><?php echo $language['lg_signup']; ?></button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /Register Modal -->

  <?php }
  if ($page == 'doctor_profile' || $page == 'patient_profile' || $page == 'pharmacy_profile' || $page == 'lab_profile') { ?>

    <div class="modal fade custom-modal" id="avatar-modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close profile_image_popup_close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title"><i><?php echo $language['lg_profile_image']; ?></i></h4>
          </div>
          <?php $curprofileimage = (!empty($profile['profileimage'])) ? $profile['profileimage'] : ''; ?>
          <form class="avatar-form" action="<?php echo base_url('profile/crop_profile_img/' . $curprofileimage) ?>" enctype="multipart/form-data" method="post">
            <div class="modal-body">
              <div class="avatar-body">
                <!-- Upload image and data -->
                <div class="avatar-upload">
                  <input class="avatar-src" name="avatar_src" type="hidden">
                  <input class="avatar-data" name="avatar_data" type="hidden">
                  <label for="avatarInput"><?php echo $language['lg_select_image']; ?></label>
                  <input class="avatar-input" id="avatarInput" name="avatar_file" type="file" required>
                  <span id="image_upload_error" class="error" style="display:none;"> <?php echo $language['lg_please_upload_i']; ?> </span>
                  <span id="image_upload_size_error" class="error" style="display:none;"> </span>
                </div>
                <!-- Crop and preview -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="avatar-wrapper"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <div class="row avatar-btns">
                <div class="col-md-12">
                  <button class="btn btn-success avatar-save" type="submit"><?php echo $language['lg_save']; ?></button>
                  <button type="button" class="btn btn-secondary submit-btn" data-dismiss="modal"><?php echo $language['lg_cancel']; ?></button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade custom-modal" id="clinicModel">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title"><?php echo "Doctor Info"; //$clinicInfo;
                                    ?></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="card">
            <div class="card-body">
              <h4 class="card-title"></h4>
              <form action="#" method="post" id="createClinic">
                <div class="row form-row">

                  <input type="hidden" name="clinicdetailsid" id="clinicdetailsid">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label><?php echo $language['lg_name']; ?></label>
                      <label><?php
                              /** @var string $role_name */
                              echo $role_name; ?></label>
                      <input type="text" value="<?php //echo $profile['clinic_name'];
                                                ?>" name="clinic_name" id="clinic_name" class="form-control">
                    </div>
                  </div>

                  <?php if ($this->session->userdata('role') == '6') {

                    $specialization_data = $this->db->select('*')->from('specialization')->where('status', 1)->get()->result_array();

                  ?>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label><?php echo $language['lg_specialization1']; ?></label>
                        <select class="form-control select" name="doc_specialization" id="doc_specialization">
                          <option value=""><?php echo $language['lg_select_speciali']; ?></option>
                          <?php foreach ($specialization_data as $specialization_da) { ?>
                            <option value="<?php echo $specialization_da['id']; ?>">
                              <?php echo $specialization_da['specialization']; ?></option>


                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  <?php } ?>


                  <?php /*      <div class="col-md-12">
                      <div class="form-group">
                        <label><?php echo $language['lg_clinic_images'];?></label>
                                  <div action="<?php echo base_url(); ?>Profile/upload_files" class="dropzone"></div>

                              </div>
                              <div class="upload-wrap">

                                  <?php 

                                  if(!empty($clinic_images)){
                                    $i=1;
                                    foreach ($clinic_images as $c) {
                                        echo '
                          <div class="upload-images" id="clinic_'.$c['id'].'">
                            <img src="'.base_url().'uploads/clinic_uploads/'.$c['user_id'].'/'.$c['clinic_image'].'" alt="">
                            <a href="javascript:void(0);" class="btn btn-icon btn-danger btn-sm" onclick="delete_clinic_image('.$c['id'].')"><i class="far fa-trash-alt"></i></a>
                          </div>';
                                                }
                                  }

                                  ?>
                              </div>
                      </div> */ ?>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label><?php echo $language['lg_clinic_address1']; ?></label>
                      <input type="text" value="<?php //echo $profile['clinic_address'];
                                                ?>" name="clinic_address" id="clinic_address" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><?php echo $language['lg_clinic_address2']; ?></label>
                      <input type="text" value="<?php
                                                /** @var array $profile */
                                                echo $profile['clinic_address2']; ?>" name="clinic_address2" id="clinic_address2" class="form-control">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label><?php echo $language['lg_clinic_city']; ?></label>
                      <input type="text" value="<?php echo $profile['clinic_city']; ?>" name="clinic_city" id="clinic_city" class="form-control">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label><?php echo $language['lg_clinic_state']; ?></label>
                      <input type="text" value="<?php echo $profile['clinic_state']; ?>" name="clinic_state" id="clinic_state" class="form-control">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label><?php echo $language['lg_clinic_country']; ?></label>
                      <input type="text" value="<?php echo $profile['clinic_country']; ?>" name="clinic_country" id="clinic_country" class="form-control">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label><?php echo $language['lg_clinic_postal']; ?></label>
                      <input type="text" value="<?php echo $profile['clinic_postal']; ?>" name="clinic_postal" id="clinic_postal" class="form-control">
                    </div>
                  </div>
                </div>
                <button type="submit" id="clinicsave_btn" class="btn btn-primary submit-btn"><?php echo $language['lg_save_changes']; ?></button>
                <button type="button" class="btn btn-secondary submit-btn" data-dismiss="modal"><?php echo $language['lg_cancel']; ?></button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

  <?php }
  if ($page == 'schedule_timings') { ?>

    <!-- Add Time Slot Modal -->
    <div class="modal fade custom-modal" id="time_slot_modal">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content slotdetails">

        </div>
      </div>
    </div>
    <!-- /Add Time Slot Modal -->

  <?php }
  if ($page == 'mypatient_preview' || $page == 'patient_dashboard' || $page == "add_doctor" || $page == "add_team") { ?>

    <!-- Delete modal-->

    <div class="modal fade custom-modal" id="delete_modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?php echo $language['lg_delete']; ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <input type="hidden" id="delete_id">
          <input type="hidden" id="delete_table">
          <div class="modal-body">
            <p><?php echo $language['lg_are_you_sure_wa']; ?> <span id="delete_title"></span> ?</p>
          </div>
          <div class="modal-footer">
            <button type="button" id="delete_btn" onclick="delete_details()" class="btn btn-primary"><?php echo $language['lg_yes']; ?></button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $language['lg_no6']; ?></button>
          </div>
        </div>
      </div>
    </div>

    <!-- View Prescription -->
    <div class="modal fade custom-modal" id="view_modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document" style="width: 90%">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title"><?php echo $language['lg_view1']; ?> <span class="view_title"></span></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <label><?php echo $language['lg_date1']; ?> : <span id="view_date"></span></label><br>
            <label><?php echo $language['lg_patient_name']; ?> : <span id="patient_name"></span></label>

            <div class="view_details"></div>
          </div>
          <div class="clearfix"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $language['lg_close1']; ?></button>
          </div>
        </div>
      </div>
    </div>


    <!-- Add Medical Records Modal -->
    <div class="modal fade custom-modal" id="add_medical_records">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Add <?php echo $language['lg_medical_records']; ?></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form id="medical_records_form" enctype="multipart/form-data">
            <div class="modal-body">
              <input type="hidden" name="medical_record_id" id="medical_record_id" value="">
              <input type="hidden" name="patient_id" id="patient_id" value="<?php
                                                                            /** @var int $patient_id */
                                                                            echo $patient_id; ?>">

              <div class="form-group">
                <label><?php echo $language['lg_description__op']; ?></label>
                <textarea class="form-control" name="description" id="description" rows="5"></textarea>
              </div>
              <div class="form-group">
                <label><?php echo $language['lg_upload_file']; ?>[Allowed Types: jpeg/jpg/png/docx/xlsx/pdf Only]</label>
                <input class="form-control" type="file" name="user_file" id="user_files_mr">
                <a href="" id="show_med_rec_url" style="display:none;" target="_blank">Click to view previous medical record</a>
              </div>

              <div class="submit-section text-center">
                <button type="submit" id="medical_btn" class="btn btn-primary submit-btn"><?php echo $language['lg_submit']; ?></button>
                <button type="button" class="btn btn-secondary submit-btn" data-dismiss="modal"><?php echo $language['lg_cancel']; ?></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /Add Medical Records Modal -->

    <!-- Edit Medical Records Modal -->
    <!-- <div class="modal fade custom-modal" id="edit_medical_records" >
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Edit <?php echo $language['lg_medical_records']; ?></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form id="medical_records_form"  enctype="multipart/form-data">          
            <div class="modal-body">
              <input type="hidden" name="patient_id" value="<?php /** @var int $patient_id */ echo $patient_id; ?>">
                              
              <div class="form-group">
                <label><?php echo $language['lg_description__op']; ?></label>
                <textarea class="form-control" name="description" id="description" rows="5"></textarea>
              </div>
              <div class="form-group">
                <label><?php echo $language['lg_upload_file']; ?>[Allowed Types: jpeg/jpg/png/docx/xlsx/pdf Only]</label> 
                <input class="form-control" type="file" name="user_file" id="user_files_mr">
              </div>
              
              <div class="submit-section text-center">
                <button type="submit" id="medical_btn" class="btn btn-primary submit-btn"><?php echo $language['lg_submit']; ?></button>
                <button type="button" class="btn btn-secondary submit-btn" data-dismiss="modal"><?php echo $language['lg_cancel']; ?></button>             
              </div>
            </div>
          </form>
        </div>
      </div>
    </div> -->
    <!-- /Edit Medical Records Modal -->

    <!-- Show Description Modal -->
    <div class="modal fade custom-modal" id="show_desc_medical_records">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title"><?php echo $language['lg_medical_records'] . ' - ' . $language['lg_description']; ?></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body" id="med_desc">
          </div>

        </div>
      </div>
    </div>
    <!-- Show Description Modal -->

  <?php }
  if ($page == 'add_prescription' || $page == 'edit_prescription' || $page == 'add_billing' || $page == 'edit_billing') {  ?>

    <div class="modal fade" id="sign-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content" id="signature-pad">
          <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-pencil"></i> <?php echo $language['lg_add_signature']; ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <canvas width="460" height="318" id="sign"></canvas>
            <input type="hidden" id="rowno" name="rowno" value="<?php echo rand(); ?>">
            <input type="hidden" id="signname" value="">
            <input type="hidden" id="scount" value="">
          </div>
          <div class="modal-footer clearfix">
            <button type="submit" id="save2" class="btn btn-success" data-action="save"><i class="fa fa-check"></i> <?php echo $language['lg_save']; ?></button>
            <button type="button" data-action="clear" class="btn btn-default"><i class="fa fa-trash-o"></i> <?php echo $language['lg_clear']; ?></button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i><?php echo $language['lg_cancel']; ?></button>
          </div>
        </div>
      </div>
    </div>

  <?php }
}
if ($module == 'post' && $page == 'add_post' || $page == 'edit_post') { ?>

  <div class="modal fade" id="avatar-image-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header d-block">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $language['lg_upload_image1']; ?></h4>
          <span id="image_size"><?php echo $language['lg_please_upload_a']; ?></span>
        </div>

        <div class="modal-body">
          <div id="imageimg_loader" class="loader-wrap" style="display: none;">
            <div class="loader"><?php echo $language['lg_loading']; ?></div>
          </div>

          <div class="image-editor">
            <input type="file" id="fileopen" name="file" class="cropit-image-input" required>
            <span class="error_msg help-block" id="error_msg_model"></span>
            <div class="cropit-preview"></div>
            <div class="row resize-bottom">
              <div class="col-md-4">
                <div class="image-size-label"><?php echo $language['lg_resize_image']; ?></div>
              </div>
              <div class="col-md-4"><input type="range" class="custom cropit-image-zoom-input"></div>
              <div class="col-md-4 text-right"><button class="btn btn-primary export"><?php echo $language['lg_done']; ?></button></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php }

if ($module == 'pharmacy' && $page == 'add_product' || $page == 'edit_product') { ?>


  <div class="modal fade" id="avatar-image-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header d-block">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $language['lg_upload_image1']; ?></h4>
          <span id="image_size"><?php echo $language['lg_please_upload_a']; ?></span>
        </div>

        <div class="modal-body">
          <div id="imageimg_loader" class="loader-wrap" style="display: none;">
            <div class="loader"><?php echo $language['lg_loading']; ?></div>
          </div>

          <div class="image-editor">
            <input type="file" id="fileopen" name="file" class="cropit-image-input" required>
            <label>Image types allowed: JPEG,JPG,PNG,GIF </label>
            <span class="error_msg help-block" id="error_msg_model"></span>
            <div class="cropit-preview"></div>
            <div class="row resize-bottom">
              <div class="col-md-4">
                <div class="image-size-label"><?php echo $language['lg_resize_image']; ?></div>
              </div>
              <div class="col-md-4"><input type="range" class="custom cropit-image-zoom-input"></div>
              <div class="col-md-4 text-right">
                <button class="btn btn-primary export"><?php echo $language['lg_done']; ?></button>
                <button class="btn btn-secondary class" data-dismiss="modal"><?php echo $language['lg_cancel']; ?></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php }

if ($page == 'accounts') { ?>

  <div class="modal fade show" id="account_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="accounts_modal_title"><?php echo $language['lg_account_details1']; ?></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form id="accounts_form" method="post">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label"><?php echo $language['lg_bank_name']; ?> <span class="text-danger">*</span></label>
                  <input type="text" name="bank_name" class="form-control bank_name" value="" id="bank_name">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label"><?php echo $language['lg_branch_name']; ?> <span class="text-danger">*</span></label>
                  <input type="text" name="branch_name" class="form-control branch_name" value="" id="branch_name">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
               <div class="form-group">
               <label class="control-label">Account Type <span class="text-danger">*</span></label>
                   <select name="account_type" class="form-control branch_name" >
                     <option value="" >select</option>
                     <option value="savings">Savings</option>
                     <option value="current">Current</option>
                   </select>
                 </div>
                 </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Account Currency <span class="text-danger">*</span></label>
                  <input type="text" name="account_currency" class="form-control bank_name" value="" id="account_currency">
                  <span class="help-block"></span>
                </div>
              </div>
              
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">ACH account number <span class="text-danger">*</span></label>
                  <input type="text" name="ach_number" class="form-control bank_name" value="" id="ach_number">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">SWIFT <span class="text-danger">*</span></label>
                  <input type="text" name="swift" class="form-control branch_name" value="" id="swift">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Bank Address <span class="text-danger">*</span></label>
                  <input type="text" name="bank_address" class="form-control bank_name" value="" id="bank_address">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Bank Country <span class="text-danger">*</span></label>
                  <input type="text" name="bank_country" class="form-control branch_name" value="" id="bank_country">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label"><?php echo $language['lg_account_number']; ?> <span class="text-danger">*</span></label>
                  <input type="text" name="account_no" class="form-control account_no" value="" id="account_no" maxlength="20">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label"><?php echo $language['lg_account_holder_']; ?> <span class="text-danger">*</span></label>
                  <input type="text" name="account_name" class="form-control acc_name" value="" id="account_name">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Routing Number <span class="text-danger">*</span></label>
                  <input type="text" name="routing_number" class="form-control branch_name" value="" id="routing_number">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" id="acc_btn" class="btn btn-primary"><?php echo $language['lg_save']; ?></button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $language['lg_cancel']; ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Insurance Details -->

  <div class="modal fade show" id="insurance_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="insurance_modal_title">Insurance Details</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form id="insurance_form" method="post">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Insurance Company  <span class="text-danger">*</span></label>
                  <input type="text" name="insurance_company" class="form-control insurance_company" value="" id="insurance_company">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Insurance Card Number <span class="text-danger">*</span></label>
                  <input type="text" name="insurance_card_number" class="form-control insurance_card_number" value="" id="insurance_card_number">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">Insurance Type <span class="text-danger">*</span></label>
                  <input type="text" name="insurance_type" class="form-control insurance_type" value="" id="insurance_type">
                  <span class="help-block"></span>
                </div>
                 </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Insurance Expiration <span class="text-danger">*</span></label>
                  <input type="date" name="insurance_expiration" class="form-control insurance_expiration" value="" id="insurance_expiration">
                  <span class="help-block"></span>
                </div>
              </div>
              
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">benefits <span class="text-danger"></span></label>
                  <input type="text" name="benefits" class="form-control benefits" value="" id="benefits">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Phone Number <span class="text-danger">*</span></label>
                  <input type="text" name="phone_number" class="form-control phone_number" value="" id="phone_number">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Dependants <span class="text-danger"></span></label>
                  <input type="text" name="dependants" class="form-control dependants" value="" id="dependants">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">DOB <span class="text-danger">*</span></label>
                  <input type="date" name="dob" class="form-control dob" value="" id="dob">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
           
            <div class="modal-footer">
              <button type="submit" id="acc_btn" class="btn btn-primary"><?php echo $language['lg_save']; ?></button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $language['lg_cancel']; ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Payment Modal -->
  <div class="modal fade show" id="payment_request_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title"><?php echo $language['lg_payment_request3']; ?></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form id="payment_request_form" method="post">
            <input type="hidden" name="payment_type" id="payment_type">
            <div class="form-group">
              <label><?php echo $language['lg_request_amount'];?> <span class="text-danger">*</span></label>
              <input type="text" name="request_amount" id="request_amount" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
              <span class="help-block"></span>
            </div>
            <div class="form-group">
              <label><?php echo $language['lg_description_opt']; ?></label>
              <textarea class="form-control" name="description" id="description"></textarea>
              <span class="help-block"></span>
            </div>
            <div class="modal-footer">
              <button type="submit" id="request_btn" class="btn btn-primary"><?php echo $language['lg_request1']; ?></button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $language['lg_cancel']; ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

<?php }
if ($this->session->userdata('user_id') != '') { ?>


  <!-- Video Call Modal -->
  <div class="modal fade call-modal" id="appoinment_user">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">

          <!-- Incoming Call -->
          <div class="call-box incoming-box">
            <div class="call-wrapper appoinments_users_details">

            </div>
          </div>
          <!-- /Incoming Call -->

        </div>
      </div>
    </div>
  </div>
  <!-- Video Call Modal -->


  <div class="modal fade" id="ratings_review_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $language['lg_ratings__review']; ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="write-review">
            <h4><?php echo $language['lg_write_a_review_']; ?> <strong><?php echo $language['lg_dr']; ?> <span id="doctor_name"></span></strong></h4>

            <!-- Write Review Form -->
            <form method="post" id="rating_reviews_form">
              <div class="form-group">
                <label><?php echo $language['lg_review2']; ?></label>
                <div class="star-rating">
                  <input id="star-5" type="radio" name="rating" value="5">
                  <label for="star-5" title="5 stars">
                    <i class="active fa fa-star"></i>
                  </label>
                  <input id="star-4" type="radio" name="rating" value="4">
                  <label for="star-4" title="4 stars">
                    <i class="active fa fa-star"></i>
                  </label>
                  <input id="star-3" type="radio" name="rating" value="3">
                  <label for="star-3" title="3 stars">
                    <i class="active fa fa-star"></i>
                  </label>
                  <input id="star-2" type="radio" name="rating" value="2">
                  <label for="star-2" title="2 stars">
                    <i class="active fa fa-star"></i>
                  </label>
                  <input id="star-1" type="radio" name="rating" value="1">
                  <label for="star-1" title="1 star">
                    <i class="active fa fa-star"></i>
                  </label>
                </div>
              </div>
              <!-- hidden fileds -->


              <input type="hidden" name="doctor_id" id="doctor_id">
              <input type="hidden" name="appointment_id" id="rating_appointment_id">
              <div class="form-group">
                <label><?php echo $language['lg_title_of_your_r']; ?></label>
                <input class="form-control" name="title" type="text" placeholder="<?php echo $language['lg_if_you_could_sa'] ?>">
              </div>
              <div class="form-group">
                <label><?php echo $language['lg_your_review']; ?></label>
                <textarea id="review_desc" name="review" maxlength="100" class="form-control"></textarea>

                <div class="d-flex justify-content-between mt-3"><small class="text-muted"><span id="chars">100</span> <?php echo $language['lg_characters_rema']; ?></small></div>
              </div>
              <hr>

              <div class="submit-section">
                <button id="review_btn" type="submit" class="btn btn-primary submit-btn"><?php echo $language['lg_add_review']; ?></button>
              </div>
            </form>
            <!-- /Write Review Form -->

          </div>
        </div>

      </div>
    </div>
  </div>

<?php }
if ($module == 'signin' && $page == 'register') { ?>

  <div class="modal fade call-modal" id="user_role_modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">

          <!-- Incoming Call -->
          <select class="form-control" id="user_role" onchange="social_register()">
            <option value=""><?php echo $language['lg_select']; ?></option>
            <option value="1"><?php echo $language['lg_doctor2']; ?></option>
            <option value="2"><?php echo $language['lg_patient4']; ?></option>
            <option value="5"><?php echo $language['lg_pharmacy']; ?></option>
            <option value="4"><?php echo $language['lg_lab15']; ?></option>
            <option value="6"><?php echo $language['lg_clinic']; ?></option>
          </select>
          <!-- /Incoming Call -->

        </div>
      </div>
    </div>
  </div>



<?php } ?>

<?php if ($page == 'lab_tests') { ?>
  <div id="lab_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="#" enctype="multipart/form-data" autocomplete="off" id="lab_form" method="post">
          <div class="modal-header">
            <h5 class="modal-title">Add Lab Test</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" value="" name="id" />
            <input type="hidden" value="" name="method" />
            <div class="form-group">
              <label class="control-label mb-10"> Test Name <span class="text-danger">*</span></label>
              <input type="text" parsley-trigger="change" id="lab_test_name" name="lab_test_name" class="form-control">
            </div>
            <div class="form-group">
              <label for="slug" class="control-label mb-10">Amount <span class="text-danger">*</span></label>
              <input type="text" parsley-trigger="change" id="amount" name="amount" class="form-control">
            </div>
            <div class="form-group">
              <label for="slug" class="control-label mb-10" onload='document.form1.text1.focus()'>Duration <span class="text-danger">*</span></label>
              <input type="text" parsley-trigger="change" id="duration" onclick="duration(document.form1.text1)" name="duration" class="form-control">
            </div>
            <div class="form-group">
              <label for="slug" class="control-label mb-10">Description <span class="text-danger">*</span></label>
              <input type="text" parsley-trigger="change" id="description" name="description" class="form-control">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline btn-default btn-sm btn-rounded" data-dismiss="modal">Cancel</button>
            <button type="submit" id="btnlabtestsave" class="btn btn-outline btn-success ">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Delete Modal -->
  <div class="modal fade" id="delete_lab_test" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="form-content p-2">
            <input type="hidden" id="lab_test_id">
            <input type="hidden" id="lab_test_status">
            <h4 class="modal-title">Change</h4>
            <p class="mb-4">Are you sure want to change the status?</p>
            <button type="button" id="change_btn" onclick="lab_test_delete()" class="btn btn-primary">Yes </button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /Delete Modal -->
<?php }

if (($page == 'appointments' ||  $page == 'lab_dashboard') && $module == 'lab') {    ?>

  <div class="modal fade custom-modal" id="upload_labdocs_modal">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Upload Patient Reports</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <form id="upload_lab_form" enctype="multipart/form-data">
          <div class="modal-body">
            <input type="hidden" name="appointment_id">

            <div class="form-group">
              <label><?php echo $language['lg_upload_file']; ?></label>
              <input class="form-control" type="file" name="user_file" id="user_files_mr" multiple="multiple">
            </div>

            <!--  <div class="form-group">
                <label><?php echo $language['lg_description__op']; ?></label>
                <textarea class="form-control" name="description" id="description" rows="5"></textarea>
              </div> -->


            <div class="submit-section text-center">
              <button type="submit" id="medical_btn" class="btn btn-primary submit-btn"><?php echo $language['lg_submit']; ?></button>
              <button type="button" class="btn btn-secondary submit-btn" data-dismiss="modal"><?php echo $language['lg_cancel']; ?></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php }
if ($page == "add_doctor") { ?>

  <!-- Add Modal -->
  <div class="modal fade" id="user_modal" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form action="#" enctype="multipart/form-data" autocomplete="off" id="register_form" method="post">
          <input type="hidden" id="role" name="role" value="1">
          <div class="modal-header">
            <h5 class="modal-title">Add Doctor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="row form-row">
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label>First Name <span class="text-danger">*</span></label>
                  <input type="text" name="first_name" id="first_name" class="form-control">
                  <input type="hidden" name="user_id" id="user_id" class="form-control">
                  <input type="hidden" name="role" id="role" value='1'>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label>Last Name <span class="text-danger">*</span></label>
                  <input type="text" name="last_name" id="last_name" class="form-control">
                </div>
              </div>
              <div class="col-12 col-sm-12">
                <div class="form-group">
                  <label>Email<span class="text-danger">*</span></label>
                  <input type="email" name="email" id="email" class="form-control">
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label>Country Code <span class="text-danger">*</span></label>
                  <select name="country_code" class="form-control" id="country_code">
                    <option value="">Select Country Code</option>
                  </select>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label>Mobile No <span class="text-danger">*</span></label>
                  <input type="text" name="mobileno" id="mobileno" pattern="[1-15]{1}[0-15]{15}" class="form-control">
                </div>
              </div>
              <div class="col-12 col-sm-6 pass">
                <div class="form-group">
                  <label>Password <span class="text-danger">*</span></label>
                  <input type="password" name="password" id="password" class="form-control">
                </div>
              </div>
              <div class="col-12 col-sm-6 pass">
                <div class="form-group">
                  <label>Confirm Password <span class="text-danger">*</span></label>
                  <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                </div>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline btn-default btn-sm btn-rounded" data-dismiss="modal">Close</button>
            <button type="submit" id="register_btn" class="btn btn-outline btn-success ">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /ADD Modal-->

<?php  }  ?>

<?php if ($page == "add_team") { ?>

<!-- Add Modal -->
<div class="modal fade" id="user_modal" aria-hidden="true" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="#" enctype="multipart/form-data" autocomplete="off" id="register_form" method="post">
        <input type="hidden" id="role" name="role" value="7">
        <div class="modal-header">
          <h5 class="modal-title">Add Team Member</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="row form-row">
            <div class="col-12 col-sm-6">
              <div class="form-group">
                <label>First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name" id="first_name" class="form-control">
                <input type="hidden" name="user_id" id="user_id" class="form-control">
                <input type="hidden" name="role" id="role" value='7'>
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="form-group">
                <label>Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" id="last_name" class="form-control">
              </div>
            </div>
            <div class="col-12 col-sm-12">
              <div class="form-group">
                <label>Email<span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control">
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="form-group">
                <label>Country Code <span class="text-danger">*</span></label>
                <select name="country_code" class="form-control" id="country_code">
                  <option value="">Select Country Code</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="form-group">
                <label>Mobile No <span class="text-danger">*</span></label>
                <input type="text" name="mobileno" id="mobileno" pattern="[1-15]{1}[0-15]{15}" class="form-control">
              </div>
            </div>
            <div class="col-12 col-sm-6 pass">
              <div class="form-group">
                <label>Password <span class="text-danger">*</span></label>
                <input type="password" name="password" id="password" class="form-control">
              </div>
            </div>
            <div class="col-12 col-sm-6 pass">
              <div class="form-group">
                <label>Confirm Password <span class="text-danger">*</span></label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control">
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline btn-default btn-sm btn-rounded" data-dismiss="modal">Close</button>
          <button type="submit" id="register_btn" class="btn btn-outline btn-success ">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /ADD Modal-->

<?php  }  ?>

<audio id="myAudio">
  <source src="<?php echo base_url(); ?>assets/ring/phone_ring.mp3" type="audio/mp3">
</audio>

<?php $this->load->view('web/modules/language_scripts/scripts'); ?>

<script type="text/javascript">
  var base_url = '<?php echo base_url(); ?>';
  var modules = '<?php echo $module; ?>';
  var pages = '<?php echo $page; ?>';
  var roles = '<?php echo $this->session->userdata('role'); ?>';
</script>

<!-- jQuery -->
<?php if ($page == 'add_post' || $page == 'edit_post' || $page == 'add_product' || $page == 'edit_product') { ?>
  <script src="<?php echo base_url(); ?>assets/js/jquery2.js"></script>
<?php } else { ?>
  <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<?php } ?>
<!-- Bootstrap Core JS -->
<script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>


<?php
/** @var string $theme */
if ($module=='team'||$module == 'doctor' || $module == 'patient' || $module == 'calendar' || $module == 'invoice' || $module == 'lab' || $theme == 'blog' || $page == 'doctors_search'  || $page == 'doctors_searchmap'  || $page == 'doctors_mapsearch' || $page == 'patients_search' || $module == 'pharmacy' || $page == 'products_list' || $page == 'pharmacy_search_bydoctor' || $page == 'products_list_by_pharmacy') { ?>

  <script type="text/javascript" src="<?php echo base_url(); ?>assets/multiselect/dist/js/bootstrap-multiselect.js"></script>
  <!-- Sticky Sidebar JS -->
  <script src="<?php echo base_url(); ?>assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>

  <!-- Circle Progress JS -->
  <script src="<?php echo base_url(); ?>assets/js/circle-progress.min.js"></script>

<?php }

if (($module=='team'||$module == 'doctor' || $module == 'patient' || $module == 'lab') && ($page == 'doctor_profile' || $page == 'patient_profile' || $module == 'pharmacy') || $page == 'doctors_search' || $page == 'doctors_searchmap' || $page == 'doctors_mapsearch' || $page == 'patients_search' || $page == 'schedule_timings' || $page == 'pharmacy_profile' || $page == 'lab_tests_preview') { ?>

  <?php }

if (($module=='team'||$module == 'doctor' || $module == 'subscription' || $module == 'patient' || $module == 'post' || $module == 'calendar' || $module == 'invoice' || $module == 'pharmacy' || $module == 'home' || $module == 'ecommerce' || $module == 'lab')) {
  if ($page == 'book_appoinments' || $page == 'doctor_profile' || $page == 'patient_profile' || $page == 'hospital_profile' || $page == 'pharmacy_profile' || $page == 'lab_profile' || $page == 'lab_tests_preview' || $page == 'add_product' || $page == 'products_list_by_pharmacy') { ?>

    <script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>

  <?php }




  if ($page == 'doctor_profile' || $page == 'patient_profile' || $page == 'lab_profile' || $page == 'hospital_profile' || $page == 'pharmacy_profile' || $page == 'add_product') { ?>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/cropper_profile.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/cropper.min.js"></script>

  <?php }

  if ($page == 'products_list_by_pharmacy' || $page == 'index') { ?>
    <script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
  <?php
  }

  if ($page == 'doctor_profile' || $page == 'add_product') { ?>

    <script src="<?php echo base_url(); ?>assets/plugins/dropzone/dropzone.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/profile-settings.js"></script>
  <?php }
  if ($page == 'calendar' || $page == 'add_product') { ?>

    <script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/fullcalendar/fullcalendar.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/calendar.js"></script>



  <?php }
  if ($page == 'doctor_dashboard' || $page == 'mypatient_preview' || $page == 'patient_dashboard' || $page == 'index' || $page == 'pending_post' || $page == 'invoice' || $page == 'accounts' || $page == 'pharmacy_quotation' || $page == 'product_list' || $page == 'patient_quotation_list' || $page == 'orderlist' || $page == 'pharmacy_dashboard' || $page == 'lab_appoinments' || $page == 'appointments' || $page == "add_doctor"  || $page=="add_team" || $page == 'lab_appointment_list' || $page == 'lab_tests' || $page == 'lab_dashboard' || $module == 'pharmacy') { ?>

    <script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/datatables/datatables.min.js"></script>

  <?php }
  if ($page == 'add_prescription' || $page == 'edit_prescription' || $page == 'add_billing' || $page == 'edit_billing') { ?>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/signature-pad.js"></script>

<?php }
} ?>

<!-- Slick JS -->
<script src="<?php echo base_url(); ?>assets/js/slick.js"></script>

<!-- Custom JS -->
<script src="<?php echo base_url(); ?>assets/js/script.js"></script>
<?php if ($page == 'signeddatafields' || $page == 'unsigneddatafields' || $page == 'payment_form') { ?>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/payment_form.js"></script>
<?php } ?>
<?php


/*if($module=='signin'||($module=='doctor' || $module=='patient' || $module=='post' || $module=='pharmacy' || $module=='ecommerce' || $module=='lab') && ($page=='doctor_profile' || $page=='patient_profile' || $page=='schedule_timings'|| $page=='add_prescription' || $page=='edit_prescription' || $page=='add_billing' || $page=='edit_billing'|| $page=='change_password' || $page=='add_post' || $page=='edit_post' || $page=='social_media' || $page=='checkout' || $page=='add_product' || $page=='edit_product' || $page=='pharmacy_profile' || $page=='checkout' || $page == 'lab_profile' || $page=='add_doctor' || $page=='accounts' || $page == 'lab_tests' || $page=='appoinments')){ ?>

    <script src="<?php echo base_url();?>assets/js/jquery.validate.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/js/jquery.password-validation.js" type="text/javascript"></script>
    <?php }*/
?>
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.password-validation.js" type="text/javascript"></script>

<?php if (($module == 'patient' || $module == 'ecommerce' || $module == 'subscription' || $module == 'lab')  && $page == 'checkout') { ?>
  <script src="https://js.stripe.com/v3/"></script>
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<?php } ?>
<script src="<?php echo base_url(); ?>assets/js/toastr.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jstz-1.0.7.min.js"></script>

<script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.min.js"></script>

<script type="text/javascript">
  if ($('.select').length > 0) {
    $('.select').select2({
      //minimumResultsForSearch: -1,
      width: '100%'
    });
  }
</script>

<!-- Fancybox JS -->
<script src="<?php echo base_url(); ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/web.js?v=0.0009"></script>
<?php if ($module == 'messages') { ?>
  <script src="<?php echo base_url(); ?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
  <?php }
if ($this->session->userdata('user_id') != '') {
  if ($page != 'checkout' && $page != 'schedule_timings') { ?>
    <script src="<?php echo base_url(); ?>assets/js/messages.js?v=0.0002"></script>
  <?php }
  if ($module != 'pharmacy' && $page != 'checkout' && $page != 'schedule_timings') { ?>
    <script src="<?php echo base_url(); ?>assets/js/appoinments.js"></script>
  <?php }
}
if ($theme == 'blog' || $module == 'pharmacy') {
  if ($module == 'home' && $page == 'blog_details') { ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-confirm.min.js"></script>
    <?php }

  if ($module == 'post' || $module == 'pharmacy') {
    if ($page == 'add_post' || $page == 'edit_post' || $page == 'add_product' || $page == 'edit_product') { ?>
      <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.js"></script>
      <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.cropit.js"></script>
      <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/cropper_image.js"></script>
    <?php }
    if ($page == 'add_product' || $page == 'edit_product') { ?>
      <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/product_cropper_image.js"></script>
  <?php }
  } ?>
  <script src="<?php echo base_url(); ?>assets/js/blog.js"></script>
<?php } ?>

<?php if (($module == 'doctor' || $module == 'home' || $module == 'calendar') && ($page == 'doctor_profile' || $page == 'doctors_search' || $page == 'doctors_mapsearch' || $page == 'calendar')) { ?>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/multiselect/dist/js/bootstrap-multiselect.js"></script>
<?php } ?>
<?php if ($this->session->flashdata('error_message')) {  ?>
  <script>
    toastr.error('<?php echo $this->session->flashdata('error_message'); ?>');
  </script>
<?php $this->session->unset_userdata('error_message');
}
if ($this->session->flashdata('success_message')) {  ?>

  <script>
    toastr.success('<?php echo $this->session->flashdata('success_message'); ?>');
  </script>

<?php $this->session->unset_userdata('success_message');
} ?>


<?php if ($module == 'signin' && ($page == 'index' || $page == 'register')) {  ?>


  <script type="text/javascript">
    var googleclientid = '<?php echo !empty(settings("googleclientid")) ? settings("googleclientid") : ""; ?>';
    var fbappid = '<?php echo !empty(settings("facebookclientid")) ? settings("facebookclientid") : ""; ?>';
  </script>
  <script src="https://apis.google.com/js/api:client.js"></script>
  <script src="https://accounts.google.com/gsi/client" async defer></script>


  <script type="text/javascript">
    var first_name = '';
    var last_name = '';
    var email = '';

    //google login ------------------------------------------

    function parseJwt(token) {
      var base64Url = token.split('.')[1];
      var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
      var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
      }).join(''));

      return JSON.parse(jsonPayload);
    }

    function handleCredentialResponse(response) {
      const responsePayload = parseJwt(response.credential);
      first_name = responsePayload.given_name;
      last_name = responsePayload.family_name;
      email = responsePayload.email;
      <?php if ($page == 'register') { ?>
        //register
        $.post(base_url + 'signin/check_already_register', {
          'email': email
        }, function(response) {
          var obj = JSON.parse(response);
          if (obj.status === 200) {
            $('#user_role_modal').modal('show');
          }
          if (obj.status === 500) {
            toastr.error(obj.msg);
          }
        });
      <?php } else { ?>
        $.post(base_url + 'signin/social_signin', {
          'email': email
        }, function(response) {
          var obj = JSON.parse(response);
          if (obj.status === 200) {
            window.location.href = base_url + 'dashboard';
          }
          if (obj.status === 500) {
            toastr.error(obj.msg);
          }
        });

      <?php } ?>

    }
    <?php if ($page == 'register') { ?>
      var googleText = 'signup_with';
    <?php } else { ?>
      var googleText = 'signin_with';
    <?php } ?>
    window.onload = function() {
      google.accounts.id.initialize({
        client_id: googleclientid,
        callback: handleCredentialResponse
      });
      google.accounts.id.renderButton(
        document.getElementById("googleloginbtn"), {
          type: "standard",
          theme: "filled_blue",
          size: "large",
          shape: "rectangular",
          logo_alignment: "left",
          text: googleText
        } // customization attributes
      );

    }


    var googleUser = {};
         
          var startApp = function() {   
         
            gapi.load('auth2', function(){
              // Retrieve the singleton for the GoogleAuth library and set up the client.
              auth2 = gapi.auth2.init({
                client_id: googleclientid,
                
                cookiepolicy: 'single_host_origin',
                // Request scopes in addition to 'profile' and 'email'
                //scope: 'additional_scope'
              });

              attachSignin(document.getElementById('googleloginbtn'));
              
            });
          };


         
          function attachSignin(element) {
            
            auth2.attachClickHandler(element, {},
                function(googleUser) {
                  
                   first_name=googleUser.getBasicProfile().getGivenName();
                   last_name=googleUser.getBasicProfile().getFamilyName();
                   email=googleUser.getBasicProfile().getEmail();
                   <?php if ($page == 'register') { ?> 
                   //register
                       $.post(base_url + 'signin/check_already_register',{'email':email},function(response){
                          var obj = JSON.parse(response);
                            if (obj.status===200) {
                                $('#user_role_modal').modal('show');
                            }  if (obj.status===500) {
                                toastr.error(obj.msg);
                            }
                      });
                  <?php } else { ?>  
                  // signin

                    $.post(base_url + 'signin/social_signin',{'email':email},function(response){
                          var obj = JSON.parse(response);
                            if (obj.status===200) {
                                window.location.href=base_url+'dashboard';
                            }  if (obj.status===500) {
                                toastr.error(obj.msg);
                            }
                      });
                  
                  <?php } ?>
                }, function(error) {
                    toastr.error(JSON.stringify(error, undefined, 2));
                });
          } 



    startApp();

    //fb ------------
    window.fbAsyncInit = function() {
      // FB JavaScript SDK configuration and setup
      FB.init({
        appId: fbappid, // FB App ID
        cookie: true, // enable cookies to allow the server to access the session
        xfbml: true, // parse social plugins on this page
        version: 'v2.8' // use graph api version 2.8
      });

      // Check whether the user already logged in
      // FB.getLoginStatus(function(response) {
      //     if (response.status === 'connected') {
      //         //display user data
      //         getFbUserData();
      //     }
      // });
    };

    // Load the JavaScript SDK asynchronously
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s);
      js.id = id;
      js.src = "//connect.facebook.net/en_US/sdk.js";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    // Facebook login with JavaScript SDK
    function fbLogin() {
      FB.login(function(response) {
        if (response.authResponse) {
          // Get and display the user profile data
          getFbUserData();
        } else {
          toastr.error('User cancelled login or did not fully authorize');

        }
      }, {
        scope: 'email'
      });
    }

    // Fetch the user profile data from facebook
    function getFbUserData() {
      FB.api('/me', {
          locale: 'en_US',
          fields: 'id,name,first_name,last_name,email,link,gender,locale,picture'
        },
        function(response) {

          first_name = response.first_name;
          last_name = response.last_name;
          email = response.email;

          if (typeof email === "undefined") {
            toastr.error('Email is not available in your Facebook account');
            return false;
          }

          <?php if ($page == 'register') { ?>
            //register
            $.post(base_url + 'signin/check_already_register', {
              'email': email
            }, function(response) {
              var obj = JSON.parse(response);
              if (obj.status === 200) {
                $('#user_role_modal').modal('show');
              }
              if (obj.status === 500) {
                toastr.error(obj.msg);
              }
            });
          <?php } else { ?>
            // signin

            $.post(base_url + 'signin/social_signin', {
              'email': email
            }, function(response) {
              var obj = JSON.parse(response);
              if (obj.status === 200) {
                window.location.href = base_url + 'dashboard';
              }
              if (obj.status === 500) {
                toastr.error(obj.msg);
              }
            });

          <?php } ?>





        });
    }

    function social_register() {
      var user_role = $('#user_role').val();
      $.post(base_url + 'signin/social_register', {
        'first_name': first_name,
        'last_name': last_name,
        'email': email,
        'user_role': user_role
      }, function(response) {
        var obj = JSON.parse(response);
        if (obj.status === 200) {
          window.location.href = base_url + 'dashboard';
        }
        if (obj.status === 500) {
          toastr.error(obj.msg);
        }
      });
    }
  </script>

<?php }
if ($module == 'patient' && $page == 'checkout') {  ?>


  <script type="text/javascript">
    var googleclientid = '<?php echo !empty(settings("googleclientid")) ? settings("googleclientid") : ""; ?>';
    var fbappid = '<?php echo !empty(settings("facebookclientid")) ? settings("facebookclientid") : ""; ?>';
  </script>
  <script src="https://apis.google.com/js/api:client.js"></script>



  <script type="text/javascript">
    //google login ------------------------------------------

    var googleUsersignin = {};

    var startAppsignin = function() {

      gapi.load('auth2', function() {
        // Retrieve the singleton for the GoogleAuth library and set up the client.
        auth2 = gapi.auth2.init({
          client_id: googleclientid,

          cookiepolicy: 'single_host_origin',
          // Request scopes in addition to 'profile' and 'email'
          //scope: 'additional_scope'
        });

        attachSignin(document.getElementById('googlecheckoutsigninbtn'));

      });
    };



    function attachSignin(element) {

      auth2.attachClickHandler(element, {},
        function(googleUser) {

          var first_name = googleUser.getBasicProfile().getGivenName();
          var last_name = googleUser.getBasicProfile().getFamilyName();
          var email = googleUser.getBasicProfile().getEmail();
          var role = 2;


          $.post(base_url + 'signin/social_signin', {
            'email': email,
            'role': role
          }, function(response) {
            var obj = JSON.parse(response);
            if (obj.status === 200) {
              window.location.reload();
            }
            if (obj.status === 500) {
              toastr.error(obj.msg);
            }
          });


        },
        function(error) {
          toastr.error(JSON.stringify(error, undefined, 2));
        });
    }



    startAppsignin();

    //fb ------------
    window.fbAsyncInit = function() {
      // FB JavaScript SDK configuration and setup
      FB.init({
        appId: fbappid, // FB App ID
        cookie: true, // enable cookies to allow the server to access the session
        xfbml: true, // parse social plugins on this page
        version: 'v2.8' // use graph api version 2.8
      });

      // Check whether the user already logged in
      // FB.getLoginStatus(function(response) {
      //     if (response.status === 'connected') {
      //         //display user data
      //         getFbUserData();
      //     }
      // });
    };

    // Load the JavaScript SDK asynchronously
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s);
      js.id = id;
      js.src = "//connect.facebook.net/en_US/sdk.js";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    // Facebook login with JavaScript SDK
    function fbcheckoutsignin() {
      FB.login(function(response) {
        if (response.authResponse) {
          // Get and display the user profile data
          getFbUserData();
        } else {
          toastr.error('User cancelled login or did not fully authorize');

        }
      }, {
        scope: 'email'
      });
    }

    // Fetch the user profile data from facebook
    function getFbUserData() {
      FB.api('/me', {
          locale: 'en_US',
          fields: 'id,name,first_name,last_name,email,link,gender,locale,picture'
        },
        function(response) {

          var first_name = response.first_name;
          var last_name = response.last_name;
          var email = response.email;
          var role = 2;

          if (typeof email === "undefined") {
            toastr.error('Email is not available in your Facebook account');
            return false;
          }

          $.post(base_url + 'signin/social_signin', {
            'email': email,
            'role': role
          }, function(response) {
            var obj = JSON.parse(response);
            if (obj.status === 200) {
              window.location.reload();
            }
            if (obj.status === 500) {
              toastr.error(obj.msg);
            }
          });




        });
    }




    //google signup ------------------------------------------

    var googleUsersignup = {};

    var startAppsignup = function() {

      gapi.load('auth2', function() {
        // Retrieve the singleton for the GoogleAuth library and set up the client.
        auth2 = gapi.auth2.init({
          client_id: googleclientid,
          plugin_name: "dgtdoccure_doctor_signup",
          cookiepolicy: 'single_host_origin',
          ux_mode: 'popup',
          // Request scopes in addition to 'profile' and 'email'
          scope: 'additional_scope'
        });

        attachSignup(document.getElementById('googlecheckoutsignupbtn'));

      });
    };



    function attachSignup(element) {

      auth2.attachClickHandler(element, {},
        function(googleUser) {

          var first_name = googleUser.getBasicProfile().getGivenName();
          var last_name = googleUser.getBasicProfile().getFamilyName();
          var email = googleUser.getBasicProfile().getEmail();
          var role = 2;


          $.post(base_url + 'signin/social_register', {
            'first_name': first_name,
            'last_name': last_name,
            'email': email,
            'user_role': role
          }, function(response) {
            var obj = JSON.parse(response);
            if (obj.status === 200) {
              window.location.reload();
            }
            if (obj.status === 500) {
              toastr.error(obj.msg);
            }
          });


        },
        function(error) {
          toastr.error(JSON.stringify(error, undefined, 2));
        });
    }



    startAppsignup();



    // Load the JavaScript SDK asynchronously
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s);
      js.id = id;
      js.src = "//connect.facebook.net/en_US/sdk.js";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    // Facebook login with JavaScript SDK
    function fbcheckoutsignup() {
      FB.login(function(response) {
        if (response.authResponse) {
          // Get and display the user profile data
          getFbUserDatasignup();
        } else {
          //error_msg('User cancelled login or did not fully authorize');
          toastr.error('User cancelled login or did not fully authorize');

        }
      }, {
        scope: 'email'
      });
    }

    // Fetch the user profile data from facebook
    function getFbUserDatasignup() {
      FB.api('/me', {
          locale: 'en_US',
          fields: 'id,name,first_name,last_name,email,link,gender,locale,picture'
        },
        function(response) {

          var first_name = response.first_name;
          var last_name = response.last_name;
          var email = response.email;
          var role = 2;

          if (typeof email === "undefined") {
            toastr.error('Email is not available in your Facebook account');
            return false;
          }

          $.post(base_url + 'signin/social_register', {
            'first_name': first_name,
            'last_name': last_name,
            'email': email,
            'user_role': role
          }, function(response) {
            var obj = JSON.parse(response);
            if (obj.status === 200) {
              window.location.reload();
            }
            if (obj.status === 500) {
              toastr.error(obj.msg);
            }
          });




        });
    }
  </script>

<?php } ?>

</body>

</html>