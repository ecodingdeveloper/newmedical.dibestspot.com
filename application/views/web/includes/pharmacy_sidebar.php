<?php
 $user_detail=user_detail($this->session->userdata('user_id'));
 $user_profile_image=(!empty($user_detail['profileimage']))?base_url().$user_detail['profileimage']:base_url().'assets/img/user.png';
 $order_count = $this->db->where('pharmacy_notify',0)->where('pharmacy_id',$this->session->userdata('user_id'))->get('orders')->num_rows();
//  echo(settings('registration_fee_5'));
// die("dddd");
?>

				<div class="profile-sidebar">
					<div class="widget-profile pro-widget-content">
						<div class="profile-info-widget">
							<a href="#" class="booking-doc-img">
								<img src="<?php echo $user_profile_image;?>" class="avatar-view-img" alt="User Image">
							</a>
							<div class="profile-det-info">
								<h3><?php echo ucfirst($user_detail['first_name'].' '.$user_detail['last_name']);?></h3>
								
							</div>
						</div>
					</div>
					<div class="dashboard-widget">
						<nav class="dashboard-menu">
							<ul>
								<li <?php 
                                        /** @var string $page */
          							/** @var string $module */
								echo ($module == 'pharmacy' && $page=='pharmacy_dashboard')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>dashboard">
										<i class="fas fa-columns"></i>
										<span><?php 
                                        /** @var array $language */
										echo $language['lg_dashboard']; ?></span>
									</a>
								</li>
                     
								<?php if($this->session->userdata('role')==5 ){?>
								<li <?php echo ($module == 'pharmacy' && $page=='product_list')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>product-list">
										<i class="fas fa-calendar-check"></i>
										<span><?php echo $language['lg_products']; ?></span>
									</a>
								</li>
								<?php }?>
								

								<?php if($this->session->userdata('role')==5 ){?>
									<li <?php echo ($module == 'pharmacy' && $page=='add_product')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>add-product">
										<i class="fas fa-calendar-check"></i>
										<span><?php echo $language['lg_add_product']; ?></span>
									</a>
								</li>
								<?php }?>

								<?php if($this->session->userdata('role')==5 ){?>
								<li <?php echo ($module == 'pharmacy' && $page=='orderlist')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>orders-list">
										<i class="fas fa-calendar-check"></i>
										<span><?php echo $language['lg_order_list']; ?></span>
										<small class="unread-msg unread_order_count"><?php echo $order_count; ?></small>
									</a>
								</li>
								<?php }?>

								<li  <?php echo ($module == 'invoice')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>invoice">
										<i class="fas fa-file-invoice"></i>
										<span><?php echo $language['lg_invoice']; ?></span>
									</a>
								</li>

								<?php if($this->session->userdata('role')==7 ){?>
								<li <?php echo ($page=='messages')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>chat">
										<i class="fas fa-comments"></i>
										<span><?php echo $language['lg_messages'];?></span>
										<small class="unread-msg unread_msg_count">0</small>
									</a>
								</li>
								<?php }else{?>	
									<li <?php echo ($page=='messages')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>messages">
										<i class="fas fa-comments"></i>
										<span><?php echo $language['lg_messages'];?></span>
										<small class="unread-msg unread_msg_count">0</small>
									</a>
								</li>

									<?php }?>

								<?php if($this->session->userdata('role')==5 ){?>
							    <li  <?php echo ($module == 'pharmacy' && $page=='accounts')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>accounts">
										<i class="fas fa-file-invoice"></i>
										<span><?php echo $language['lg_accounts']; ?></span>
									</a>
								</li>
								<?php }?>				
										
								<li <?php echo ($module == 'pharmacy' && $page=='pharmacy_profile')?'class="active"':'';?>>
									<a href="<?php echo base_url();?>profile">
										<i class="fas fa-user-cog"></i>
										<span><?php echo $language['lg_profile_setting']; ?></span>
									</a>
								</li>
								<li <?php echo ($module == 'pharmacy' && $page=='change_password')?'class="active"':'';?>>
					                <a href="<?php echo base_url();?>change-password">
										<i class="fas fa-lock"></i>
										<span><?php echo $language['lg_change_password']; ?></span>
									</a>
								</li>
								<li>
									<a href="<?php echo base_url();?>sign-out">
										<i class="fas fa-sign-out-alt"></i>
										<span><?php echo $language['lg_signout']; ?></span>
									</a>
								</li>
							</ul>
						</nav>
					</div>

				</div>
			