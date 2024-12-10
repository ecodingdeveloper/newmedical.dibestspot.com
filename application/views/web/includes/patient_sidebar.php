<?php
 $user_detail=user_detail($this->session->userdata('user_id'));
 $user_profile_image=(!empty($user_detail['profileimage']))?base_url().$user_detail['profileimage']:base_url().'assets/img/user.png';
 $order_count = $this->db->where('user_notify',0)->where('user_id',$this->session->userdata('user_id'))->get('orders')->num_rows();
//  echo $order_count;
//  echo '<br>';
//  echo $this->session->userdata('user_id');
//  die("sdfsf");
$unpaid_invoices = $this->db->where('payment_status',0)->where('user_id',$this->session->userdata('user_id'))->get('payments')->num_rows();
// echo $unpaid_invoice;
// die("sdfsf");
?>

				<div class="profile-sidebar">
					<div class="widget-profile pro-widget-content">
						<div class="profile-info-widget">
							<a href="#" class="booking-doc-img">
								<img src="<?php echo $user_profile_image;?>" class="avatar-view-img" alt="User Image">
							</a>
							<div class="profile-det-info">
								<h3><?php echo ucfirst($user_detail['first_name'].' '.$user_detail['last_name']);?></h3>
								<div class="patient-details">
									<?php echo (!empty($user_detail['dob']))?'<h5><i class="fas fa-birthday-cake"></i> '. date('d M Y',strtotime($user_detail['dob'])).', '.age_calculate($user_detail['dob']).'</h5>':'';?>
									<?php echo (!empty($user_detail['city']))?'<h5 class="mb-0"><i class="fas fa-map-marker-alt"></i>'.$user_detail['cityname'].', '.$user_detail['countryname'].'</h5>':'';?>
									
								</div>
							</div>
						</div>
					</div>
					<div class="dashboard-widget">
						<nav class="dashboard-menu">
							<ul>
								<li <?php
                                     
           							 /** @var string $page */
          							/** @var string $module */
								 echo ($module == 'patient' && $page=='patient_dashboard')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>dashboard">
										<i class="fas fa-columns"></i>
										<span><?php 
                                       /** @var array $language */
										echo $language['lg_dashboard'];?></span>
									</a>
								</li>
								<li <?php echo ($module == 'patient' && $page=='appoinments')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>appoinments">
										<i class="fas fa-calendar-check"></i>
										<span><?php echo $language['lg_appointments'];?></span>
									</a>
								</li>
								<li <?php echo ($module == 'patient' && $page=='lab_appoinments')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>appoinments/lab_appoinments">
										<i class="fas fa-calendar-check"></i>
										<span>Lab <?php echo $language['lg_appointments'];?></span>
									</a>
								</li>
								<li <?php echo ($module == 'calendar' && $page=='calendar')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>calendar">
										<i class="fas fa-calendar-check"></i>
										<span><?php echo $language['lg_calendar']; ?></span>
									</a>
								</li>
								<li  <?php echo ($module == 'invoice')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>invoice">
										<i class="fas fa-file-invoice"></i>
										<span><?php echo $language['lg_invoice']; ?></span>
										<small class="unread-msg unread_order_count"><?php echo $unpaid_invoices." pending"; ?></small>
									</a>
								</li>
								<li <?php echo ($module == 'patient' && $page=='favourites')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>favourites">
										<i class="fas fa-heart"></i>
										<span><?php echo $language['lg_favourites'];?></span>
									</a>
								</li>
								<li <?php echo ($module == 'patient' && $page=='messages')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>messages">
										<i class="fas fa-comments"></i>
										<span><?php echo $language['lg_messages'];?></span>
										<small class="unread-msg unread_msg_count">0</small>
									</a>
								</li>
								<li  <?php echo ($module == 'patient' && $page=='accounts')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>accounts">
										<i class="fas fa-file-invoice"></i>
										<span><?php echo $language['lg_accounts']; ?></span>
									</a>
								</li>
								<li <?php echo ($module == 'patient' && $page=='reviews-list')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>reviews-list">
										<i class="fas fa-star"></i>
										<span><?php echo $language['lg_reviews'];?></span>
									</a>
								</li> 
								<li  <?php echo ($module == 'pharmacy' && $page=='orderlist')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>pharmacy/orders_list">
										<i class="fas fa-file-invoice"></i>
										<span><?php echo $language['lg_orders']; ?></span>
										<small class="unread-msg unread_order_count"><?php echo $order_count; ?></small>
									</a>
								</li>
								<li <?php echo ($module == 'patient' && $page=='patient_profile')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>profile">
										<i class="fas fa-user-cog"></i>
										<span><?php echo $language['lg_profile_setting'];?></span>
									</a>
								</li>
								<li <?php echo ($module == 'patient' && $page=='change_password')?'class="active"':'';?>>
					                <a href="<?php echo base_url();?>change-password">
										<i class="fas fa-lock"></i>
										<span><?php echo $language['lg_change_password'];?></span>
									</a>
								</li>
								<li>
									<a href="<?php echo base_url();?>sign-out">
										<i class="fas fa-sign-out-alt"></i>
										<span><?php echo $language['lg_signout'];?></span>
									</a>
								</li>
							</ul>
						</nav>
					</div>

				</div>
			