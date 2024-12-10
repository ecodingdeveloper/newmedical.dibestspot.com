
      
      <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <div class="sidebar-inner slimscroll">
          <div id="sidebar-menu" class="sidebar-menu">
            <ul>
              <li class="menu-title"> 
                <span>Main</span>
              </li>
              <li <?php 
 /**
 * @var string $module
 */
              echo ($module=='dashboard')?'class="active"':'';?>> 
                <a href="<?php echo base_url();?>admin/dashboard"><i class="fe fe-home"></i> <span>Dashboard</span></a>
              </li>
              <li <?php echo ($module=='appointments')?'class="active"':'';?>> 
                <a href="<?php echo base_url();?>admin/appointments"><i class="fe fe-layout"></i> <span>Appointments</span></a>
              </li>
              <li <?php echo ($module=='specialization')?'class="active"':'';?>> 
                <a href="<?php echo base_url();?>admin/specialization"><i class="fas fa-certificate"></i> <span>Specialization</span></a>
              </li>
              <li <?php echo ($module=='pricing')?'class="active"':'';?>> 
                <a href="<?php echo base_url();?>admin/users/pricing_plan"><i class="fas fa-dollar-sign"></i> <span>Pricing Plan</span></a>
              </li>
              <li class="submenu">
                <a href="#"><i class="fa fa-user-plus font-set"></i>&nbsp;<span> Medical Store </span> <span class="menu-arrow"></span></a>
                <ul style="display: none;">
                  <li> <a href="<?php echo base_url();?>admin/users/package"> Packages </a>
                  </li>
                  <li><a href="<?php echo base_url();?>admin/users/service"> Services </a></li>
                </ul>
              </li>
              <li <?php 
/**
 * @var string $page
 */

              echo ($module=='users' && $page=='doctors' )?'class="active"':'';?>>  
                <a href="<?php echo base_url();?>admin/users/doctors"><i class="fas fa-user-md"></i> <span>Doctors</span></a>
              </li>
              <li <?php echo ($module=='users' && $page=='clinic' )?'class="active"':'';?>>  
                <a href="<?php echo base_url();?>admin/users/clinic"><i class="fe fe-user-plus"></i> <span>Clinic</span></a>
              </li>
              <li <?php echo ($module=='users' && $page=='staff' )?'class="active"':'';?>>  
                <a href="<?php echo base_url();?>admin/users/staff"><i class="fe fe-user-plus"></i> <span>Staff Member</span></a>
              </li>
              <li <?php echo ($module=='users' && $page=='team' )?'class="active"':'';?>>  
                <a href="<?php echo base_url();?>admin/users/team"><i class="fe fe-user-plus"></i> <span>Team Member</span></a>
              </li>
              <li <?php echo ($module=='users' && $page=='patients' )?'class="active"':'';?>>
                <a href="<?php echo base_url();?>admin/users/patients"><i class="fa fa-users font-set"></i>&nbsp;<span>Patients</span></a>
              </li>
              <li <?php echo ($module=='payment_requests' && $page=='index')?'class="active"':'';?>> 
                <a href="<?php echo base_url();?>admin/payment_requests"><i class="fe fe-activity"></i> <span>Payment Requests</span></a>
              </li>
              <li class="submenu">
                <a href="#"><i class="fa fa-user-plus font-set"></i>&nbsp;<span> Pharmacy </span> <span class="menu-arrow"></span></a>
                <ul style="display: none;">
                  <li> <a href="<?php echo base_url();?>admin/users/pharmacies"> Pharmacies </a>
                  </li>
                  <li><a href="<?php echo base_url();?>admin/products"> Products </a></li>
                  <li><a href="<?php echo base_url();?>admin/categories"> Categories </a></li>
                  <li><a href="<?php echo base_url();?>admin/subcategories"> Sub Categories </a></li>
                  <li><a href="<?php echo base_url();?>admin/unit"> Units </a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="#"><i class="fas fa-flask"></i> <span> Lab </span> <span class="menu-arrow"></span></a>
                <ul style="display: none;">
                  <li> <a href="<?php echo base_url();?>admin/users/labs"> Lab Lists </a>
                  </li>
                  <li> <a href="<?php echo base_url();?>admin/users/labtest_booked"> Lab Booking </a>
                  </li>
                  <li><a href="<?php echo base_url();?>admin/lab_tests"> Lab Test Details </a></li>
                </ul>
              </li>
              <li <?php echo ($module=='reviews')?'class="active"':'';?>> 
                <a href="<?php echo base_url();?>admin/reviews"><i class="fe fe-star-o"></i> <span>Reviews</span></a>
              </li>
              <li <?php echo ($module=='settings')?'class="active"':'';?>>
                <a href="<?php echo base_url();?>admin/settings"><i class="fa fa-cog font-set"></i> <span>Settings</span></a>
              </li>
               <li <?php echo ($module=='email_template')?'class="active"':'';?>>
                <a href="<?php echo base_url();?>admin/email_template"><i class="fa fa-envelope font-set"></i> <span>Email Template</span></a>
              </li>
               <li <?php echo ($module=='cms')?'class="active"':'';?>>
                <a href="<?php echo base_url();?>admin/cms"><i class="fe fe-vector"></i> <span>CMS</span></a>
              </li>
              <li <?php echo ($module=='chat')?'class="active"':'';?>>
                <a href="<?php echo base_url();?>admin/chat"><i class="fas fa-comments font-set"></i> <span>Chat</span></a>
              </li>

              <li class="submenu">
                <a href="#"><i class="fa fa-language font-set"></i>&nbsp;<span> Language </span> <span class="menu-arrow"></span></a>
                <ul style="display: none;">
                  <li><a href="<?php echo base_url();?>admin/language"> Language </a></li>
                  <li><a href="<?php echo base_url();?>admin/language/keywords">Language Keywords </a></li>
                  <li><a href="<?php echo base_url();?>admin/language/pages"> App Language Keywords </a></li>
                  </ul>
              </li>
             
              <li <?php echo ($module=='profile')?'class="active"':'';?>>
                <a href="<?php echo base_url();?>admin/profile"><i class="fe fe-user-plus"></i> <span>Profile</span></a>
              </li>
              <li class="menu-title"> 
                <span>Blogs</span>
              </li>
              <li class="submenu">
                <a href="#"><i class="fe fe-folder-open"></i> <span> Categories </span> <span class="menu-arrow"></span></a>
                <ul style="display: none;">
                  <li><a href="<?php echo base_url();?>blog/categories"> Categories </a></li>
                  <li><a href="<?php echo base_url();?>blog/subcategories"> Sub Categories </a></li>
                  </ul>
              </li>
              <li class="submenu">
                <a href="#"><i class="fa fa-blog font-set"></i> <span> Post </span> <span class="menu-arrow"></span></a>
                <ul style="display: none;">
                  <li><a href="<?php echo base_url();?>blog/post"> Post </a></li>
                  <li><a href="<?php echo base_url();?>blog/pending-post">Pending Post </a></li>
                  <li><a href="<?php echo base_url();?>blog/add-post"> Add Post </a></li>
                  </ul>
              </li>

              <li class="submenu">
                <a href="#"><i class="fa fa-flag font-set"></i> <span> Country Config </span> <span class="menu-arrow"></span></a>
                <ul style="display: none;">
                  <li><a href="<?php echo base_url();?>admin/country/country"> Add Country </a></li>
                  <li><a href="<?php echo base_url();?>admin/country/state">Add State </a></li>
                  <li><a href="<?php echo base_url();?>admin/country/city"> Add City </a></li>
                  </ul>
              </li>
              
            </ul>
          </div>
                </div>
            </div>
      <!-- /Sidebar -->