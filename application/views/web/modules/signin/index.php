      
      <!-- Page Content -->
      <div class="content">
        <div class="container-fluid">
          
          <div class="row">
            <div class="col-md-8 offset-md-2">
              
              <!-- Login Tab Content -->
              <div class="account-content">
                <div class="row align-items-center justify-content-center">
                  <div class="col-md-7 col-lg-6 login-left">
                       <img src="<?php echo !empty(base_url().settings("login_image"))?base_url().settings("login_image"):base_url()."assets/img/login-banner.png";?>" class="img-fluid" alt="Doccure Login"> 
                    
                  </div>
                  <div class="col-md-12 col-lg-6 login-right">



                    <div class="login-header">

                    
                      <h3><?php
                      /** @var array $language */
                       echo $language['lg_login'];?> <span><?php echo !empty(settings("meta_title"))?settings("meta_title"):"Doccure";?></span></h3>
                    </div>

                    <form action="#" id="signin_form" method="post" autocomplete="off">
                      <div class="form-group form-focus">
                        <input type="text" name="email" id="email" class="form-control floating">
                        <label class="focus-label"><?php echo $language['lg_email_or_mobile']; ?></label>
                      </div>
                      <div class="form-group form-focus">
                        <input type="password" name="password" id="password" class="form-control floating">
                        <span class="far fa-eye" id="togglePassword1"></span>
                        <label class="focus-label"><?php echo $language['lg_password'];?></label>
                      </div>
                      <div class="text-right">
                        <a class="forgot-link" href="<?php echo base_url();?>forgot-password"><?php echo $language['lg_forgot_password'];?></a>
                      </div>
                      <button class="btn btn-primary btn-block btn-lg login-btn" id="signin_btn" type="submit"><?php echo $language['lg_signin'];?></button>
                      <div class="row s-btn" style="margin-top: 10px">
                        <div class="col-12">
                            <button class="btn btn-social p-0 btngoogle btn-full-width" type="button" id="googleloginbtn"><i class="fab fa-google float-left"></i><?php echo $language['lg_signin'];?></button>
                            <?php /*?><button class="btn btn-social btn-facebook" type="button" onclick="fbLogin()"><i class="fab fa-facebook-f float-left"></i><?php echo $language['lg_signin'];?></button>
                            <?php */?>
                        </div>
                      </div>
                      <div class="text-center dont-have"><?php echo $language['lg_dont_have_an_ac'];?> <a href="<?php echo base_url();?>register"><?php echo $language['lg_register'];?></a></div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- /Login Tab Content -->
                
            </div>
          </div>

        </div>

      </div>    
      <!-- /Page Content -->

     
  
   
  
