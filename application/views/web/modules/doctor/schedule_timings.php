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
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_schedule_timing'];?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_schedule_timing'];?></h2>
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
							<?php $this->load->view('web/includes/doctor_sidebar');?>
							<!-- /Profile Sidebar -->
							
						</div>
						
						<div class="col-md-7 col-lg-8 col-xl-9">
						 
							<div class="row">
								<div class="col-sm-12">
									<div class="card">
										<div class="card-body">
											<h4 class="card-title"><?php echo $language['lg_schedule_timing'];?></h4>
											<div class="profile-box">
												<div class="row">

													<div class="col-lg-4">
														<div class="form-group">               
															<label><?php echo $language['lg_timing_slot_dur'];?></label>
															<select class="select form-control" id="slots">
																<option value=""><?php echo $language['lg_select8'];?></option>
																<?php if(!empty($slots) && $slots['slot']=='5'){

																	$select='selected';
          
                                                                }else{$select="";} ?>
																<option value="5" <?php echo $select;?>>5<?php echo $language['lg_mins']; ?></option>

																<?php if(!empty($slots) && $slots['slot']=='10'){

																	$select1='selected';
          
                                                                }else{$select1="";} ?>
																<option value="10" <?php echo $select1;?>>10<?php echo $language['lg_mins']; ?></option>

																<?php if(!empty($slots) && $slots['slot']=='15'){

																	$select2='selected';
          
                                                                }else{$select2="";} ?>
																<option value="15" <?php echo $select2;?>>15<?php echo $language['lg_mins']; ?></option>
																
																
																
																<?php if(!empty($slots) && $slots['slot']=='20'){

																	$select3='selected';
          
                                                                }else{$select3="";} ?>
																<option value="20" <?php echo $select3;?>>20<?php echo $language['lg_mins']; ?></option>
																<?php if(!empty($slots) && $slots['slot']=='30'){

																	$select4='selected';
          
                                                                }else{$select4="";} ?>
																<option value="30" <?php echo $select4;?>>30<?php echo $language['lg_mins']; ?></option> 
																<?php if(!empty($slots) && $slots['slot']=='45'){

																	$select5='selected';
          
                                                                }else{$select5="";} ?>
																<option value="45" <?php echo $select5;?>>45<?php echo $language['lg_mins']; ?></option>

																<?php if(!empty($slots) && $slots['slot']=='1'){

																	$select6='selected';
          
                                                                }else{$select6="";} ?>
																<option value="1" <?php echo $select6;?>>1<?php echo $language['lg_hour']; ?></option>

																
															</select>
														</div>
													</div>

												</div>     
												<div class="row">
													<div class="col-md-12">
														<div class="card schedule-widget mb-0">
														
															<!-- Schedule Header -->
															<div class="schedule-header">
															
																<!-- Schedule Nav -->
																<div class="schedule-nav timingsnav">
																	<ul class="nav nav-tabs nav-justified">
																		<li class="nav-item">
																			<a class="nav-link active" data-toggle="tab" id="sunday" data-day-value="1" data-append-value="sunday" href="#slot_sunday"><?php echo $language['lg_sunday']; ?></a>
																		</li>
																		<li class="nav-item">
																			<a class="nav-link" data-toggle="tab" id="monday" data-day-value="2" data-append-value="monday" href="#slot_monday"><?php echo $language['lg_monday']; ?></a>
																		</li>
																		<li class="nav-item">
																			<a class="nav-link" data-toggle="tab" id="tuesday" data-day-value="3" data-append-value="tuesday" href="#slot_tuesday"><?php echo $language['lg_tuesday']; ?></a>
																		</li>
																		<li class="nav-item">
																			<a class="nav-link" data-toggle="tab" id="wednesday" data-day-value="4" data-append-value="wednesday" href="#slot_wednesday"><?php echo $language['lg_wednesday']; ?></a>
																		</li>
																		<li class="nav-item">
																			<a class="nav-link" data-toggle="tab" id="thursday" data-day-value="5" data-append-value="thursday" href="#slot_thursday"><?php echo $language['lg_thursday']; ?></a>
																		</li>
																		<li class="nav-item">
																			<a class="nav-link" data-toggle="tab" id="friday" data-day-value="6" data-append-value="friday" href="#slot_friday"><?php echo $language['lg_friday']; ?></a>
																		</li>
																		<li class="nav-item">
																			<a class="nav-link" data-toggle="tab" id="saturday" data-day-value="7" data-append-value="saturday" href="#slot_saturday"><?php echo $language['lg_saturday']; ?></a>
																		</li>
																	</ul>
																</div>
																<!-- /Schedule Nav -->
																
															</div>
															<!-- /Schedule Header -->
															
															<!-- Schedule Content -->
															<div class="tab-content schedule-cont">
															
																<!-- Sunday Slot -->
																<div id="slot_sunday" class="tab-pane fade show active"></div>
																<!-- /Sunday Slot -->

																<!-- Monday Slot -->
																<div id="slot_monday" class="tab-pane fade"></div>
																<!-- /Monday Slot -->

																<!-- Tuesday Slot -->
																<div id="slot_tuesday" class="tab-pane fade"></div>
																<!-- /Tuesday Slot -->

																<!-- Wednesday Slot -->
																<div id="slot_wednesday" class="tab-pane fade"></div>
																<!-- /Wednesday Slot -->

																<!-- Thursday Slot -->
																<div id="slot_thursday" class="tab-pane fade"></div>
																<!-- /Thursday Slot -->

																<!-- Friday Slot -->
																<div id="slot_friday" class="tab-pane fade"></div>
																<!-- /Friday Slot -->

																<!-- Saturday Slot -->
																<div id="slot_saturday" class="tab-pane fade"></div>
																<!-- /Saturday Slot -->

															</div>
															<!-- /Schedule Content -->
															
														</div>
													</div>
												</div>
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

		