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
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_subscription'];?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_subscription'];?> </h2>
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
							<?php
							if($this->session->userdata('role')=='3') {
							$this->load->view('web/includes/pharmacy_sidebar');
							} 
							else if($this->session->userdata('role')=='1' || $this->session->userdata('role')=='6') {
							    $this->load->view('web/includes/doctor_sidebar');
							}
							?>
							<!-- /Profile Sidebar -->
							
						</div>
						<div class="col-md-7 col-lg-8 col-xl-9">
							<input type="hidden" name="page" id="page_no_hidden" value="1" >
							<div class="row row-grid" id="patients-list">
							</div>

							<div class="load-more text-center d-none" id="load_more_btn">
									<a class="btn btn-primary btn-sm" href="javascript:void(0);"><?php echo $language['lg_load_more'];?></a>	
							</div>
                                                        <div class="row">
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            <div class="tab-content">
              
                <!-- Monthly Tab -->
                <div class="tab-pane fade active show" id="monthly">
                  <div class="row mb-30 equal-height-cards subscription_reload">
                      <?php
                        if(isset($plans) && !empty($plans)){
                            foreach ($plans as $plans_key => $plans_val) {
                                $id = $plans_val['id'];
                                $plan_name = (!empty($plans_val['plan_name'])) ? ucfirst($plans_val['plan_name']) : '';
                                $plan_amount = (!empty($plans_val['plan_amount'])) ? $plans_val['plan_amount'] : '';
                                $plan_status = (!empty($plans_val['status']) && $plans_val['status'] == 1) ? $plans_val['status'] : 0;
                                $plan_type = $plans_val['plan_type'];
                                $plan_exipre_mnth_yr = $plans_val['plan_exipre_mnth_yr'];
                                $expires_in = '';
                                if($plan_type == 'free') {
                                    $expires_in = '<p class="plan-vali">Free Plan</p>';
                                } else { 
                                    $expires_in = '<p class="plan-vali">'.ucfirst($plan_type).'ly Plan - Expires in '.$plan_exipre_mnth_yr.' '.$plan_type.'</p>';
                                }
                                if($plan_amount == '' || $plan_amount == 0) { $plan_amount = 0; }
                                $doctorid = $this->session->userdata('user_id');
                                ?>
                                    <div class="card">
                                        <div  class="card-body img-bg-card">
                                          <h2 class="card-title text-center"><?php echo $plan_name; ?></h2>
                                          <hr class="hr-text" data-content="&">
                                          <h2 class="text-center"><span class="plan-amount">GFr <?php echo $plan_amount; ?></span></h2>
                                          <?php echo $expires_in; ?>
                                          <div class="sub-btn text-center">
                                              <form role="form" method="POST" id="doctor_subscribenow" action="<?php echo base_url().'subscriptions/doctor_subscribenow';?>">
						 
			              <input type="hidden" name="doctor_id" id="doctor_id"   value="<?php echo $doctorid ?>" />
			              <input type="hidden" name="plan_id" id="plan_id"   value="<?php echo $plans_val['id']; ?>" />
			              <input type="hidden" name="plan_type" id="plan_type"   value="<?php echo $plan_type; ?>" />
			              <input type="hidden" name="plan_amount" id="plan_amount"   value="<?php echo $plan_amount; ?>" />
			              <input type="submit" name="submitbtn" id="submitbtn" class="btn schedule-header doctor_subscribenow" style="background: #ff4877;" value="<?php echo $language['lg_upgrade_plan']; ?>">
										</form>
                                              
                                            <!--<a href="#" class="btn btn-edit text-white btn-block" data-toggle="modal" data-target="#edit_plan" id="<?php //echo $id; ?>" status="<?php //echo $plan_status; ?>" onclick="edit_subscription(<?php //echo $id; ?>)">Edit Now </a>
                                            <span class="float-right"><i class="fa fa-thumbs-up"></i></span>-->
                                          </div>
                                        </div>
                              </div>
                        
                                <?php
                            }
                        }
                      ?>
                      
                  </div>
                </div>
              
              </div>
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
                                                            
								<div class="col-md-12">
									<h4 class="mb-4"><?php echo $language['lg_current_subscription_plan']; ?></h4>
                                                        <div class="card card-table mb-0">
													<div class="card-body">
														<div class="table-responsive">
															<input type="hidden" id="type">
															<table id="subscriptions_table" class="table table-hover table-center mb-0">
																<thead>
																	<tr>
																		<!--<th><?php // echo $language['lg_pharmacy_name']; ?></th>-->
                                                                                                                                                <th><?php echo 'Name'; ?></th>
																		<th><?php echo $language['lg_plan']; ?></th>
                                                                                                                                                <th><?php echo $language['lg_plan_type']; ?></th>
                                                                                                                                                <th><?php //echo $language['lg_expires_in']; ?>Plan Start Date</th>
                                                                                                                                                <th><?php //echo $language['lg_expires_in']; ?>Plan End Date</th>
																		<th><?php echo $language['lg_plan_amount']; ?></th>
																	</tr>
																</thead>
																<tbody>
                                                                                                                                    <?php
                                                                                                                                        if(isset($subscription_list) && !empty($subscription_list)){
                                                                                                                                            foreach ($subscription_list as $subscription_key => $subscription_val) {
                                                                                                                                                $expires_in = $subscription_val['plan_exipre_mnth_yr'];
                                                                                                                                                $plan_amount = $subscription_val['plan_amount'];
                                                                                                                                                if($subscription_val['plan_type'] == 'free') {
                                                                                                                                                    $expires_in = '0';
                                                                                                                                                    $plan_amount = '0';
                                                                                                                                                }
                                                                                                                                                ?>
                                                                                                                                    <tr>
                                                                                                                                        <td><?php echo ucfirst($subscription_val['username']); ?></td>
                                                                                                                                        <td><?php echo ucfirst($subscription_val['plan_name']); ?></td>
                                                                                                                                        <td><?php echo ucfirst($subscription_val['plan_type']); ?></td>
                                                                                                                                        <td><?php echo date('d F Y', strtotime($subscription_val['subscription_plan_start_date'])); ?></td>
                                                                                                                                        <td><?php echo date('d F Y', strtotime($subscription_val['subscription_plan_end_date'])); ?></td>
                                                                                                                                        <td>GFr <?php echo $plan_amount; ?></td>
                                                                                                                                    </tr>
                                                                                                                                                <?php
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                    ?>
																</tbody>
															</table>		
														</div>
													</div>
												</div>
                                                        
                                                                </div>
                                                        
                                                        
                                                        
                                                        
                                                        

						</div>
					</div>

				</div>

			</div>	
                            
                            </div>		
			<!-- /Page Content -->