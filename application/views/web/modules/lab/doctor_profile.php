<!-- Page Content -->
			<div class="content">
				<div class="container-fluid">

					<div class="row">
						<div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">
						
							<!-- Profile Sidebar -->
							<?php $this->load->view('web/includes/lab_sidebar');?>
							<!-- /Profile Sidebar -->
							
						</div>
						<div class="col-md-7 col-lg-8 col-xl-9">
						<form method="post" action="#" id="lab_profile_form" autocomplete="off">

								<input type="hidden" value="<?php echo date('d/m/Y'); ?>" id="maxDate">
								

							<?php

							$user_profile_image=(!empty($profile['profileimage']))?base_url().$profile['profileimage']:base_url().'assets/img/user.png';
							 ?>
						
							<!-- Basic Information -->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title"><?php echo $language['lg_basic_informati'];?></h4>
									<div class="row form-row">
										<div class="col-md-12">
											<div class="form-group">
												<div class="change-avatar">
													<div class="profile-img">
														<img src="<?php echo $user_profile_image;?>" alt="User Image" class="avatar-view-img">
													</div>
													<div class="upload-img">
														<div class="change-photo-btn avatar-view-btn">
															<span><i class="fa fa-upload"></i> <?php echo $language['lg_upload_photo'];?></span>
															<input type="hidden" id="crop_prof_img" name="profile_image">
														</div>
														<small class="form-text text-muted"><?php echo $language['lg_allowed_jpg_gif'];?></small>
													</div>
												</div>
											</div>
										</div>
										
										
										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_first_name'];?> <span class="text-danger">*</span></label>
												<input type="text" name="first_name" id="first_name" value="<?php 
												/** @var array $profile */
												echo $profile['first_name'];?>" class="form-control">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_last_name'];?> <span class="text-danger">*</span></label>
												<input type="text" name="last_name" id="last_name" value="<?php echo $profile['last_name'];?>" class="form-control">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_email'];?> <span class="text-danger">*</span></label>
												<input type="email"  value="<?php echo $profile['email'];?>" class="form-control" disabled>
											</div>
										</div>
										<div class="col-md-6">
										 	<div class="form-group">
												<label>Country Code<span class="text-danger">*</span></label>
                          						<select name="country_code" class="form-control" id="country_code">
                          						</select>
                          					</div>
                        				</div>
										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_mobile_number'];?> <span class="text-danger">*</span></label>
												<input type="text"  id="mobileno" name="mobileno" value="<?php echo $profile['mobileno'];?>" class="form-control">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_select_gender'];?> <span class="text-danger">*</span></label>
												<select class="form-control select" name="gender" id="gender">
													<option value=""><?php echo $language['lg_select'];?></option>
													<option value="Male" <?php echo ($profile['gender']=='Male')?'selected':'';?>><?php echo $language['lg_male'];?></option>
													<option value="Female" <?php echo ($profile['gender']=='Female')?'selected':'';?>><?php echo $language['lg_female'];?></option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group mb-0">
												<label><?php echo $language['lg_date_of_birth'];?> <span class="text-danger">*</span></label>
												<input type="text" name="dob" id="dob" value="<?php echo !empty($profile['dob'])?date('d/m/Y',strtotime(str_replace('-', '/', $profile['dob']))):'';?>" readonly class="form-control">
											</div>
										</div>
										<div class="col-md-6">
												<div class="form-group">
													<label><?php echo $language['lg_blood_group'];?> <span class="text-danger">*</span></label>
													<select class="form-control select" name="blood_group" id="blood_group">
														<option value=""><?php echo $language['lg_select'];?></option>
														<option value="A-" <?php echo ($profile['blood_group']=='A-')?'selected':'';?>><?php echo $language['lg_a2'];?></option>
														<option value="A+" <?php echo ($profile['blood_group']=='A+')?'selected':'';?>><?php echo $language['lg_a3'];?></option>
														<option value="B-" <?php echo ($profile['blood_group']=='B-')?'selected':'';?>><?php echo $language['lg_b6'];?></option>
														<option value="B+" <?php echo ($profile['blood_group']=='B+')?'selected':'';?>><?php echo $language['lg_b7'];?></option>
														<option value="AB-" <?php echo ($profile['blood_group']=='AB-')?'selected':'';?>><?php echo $language['lg_ab1'];?></option>
														<option value="AB+" <?php echo ($profile['blood_group']=='AB+')?'selected':'';?>><?php echo $language['lg_ab2'];?></option>
														<option value="O-" <?php echo ($profile['blood_group']=='O-')?'selected':'';?>><?php echo $language['lg_o4'];?></option>
														<option value="O+" <?php echo ($profile['blood_group']=='O+')?'selected':'';?>><?php echo $language['lg_o5'];?></option>
													</select>
												</div>
											</div>
									</div>
								</div>
							</div>
							<!-- /Basic Information -->
							
							<!-- Contact Details -->
							<div class="card contact-card">
								<div class="card-body">
									<h4 class="card-title"><?php echo $language['lg_contact_details'];?></h4>
									<div class="row form-row">
										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_address_line_1'];?> <span class="text-danger">*</span></label>
												<input type="text" name="address1" id="address1" value="<?php echo $profile['address1'];?>" class="form-control">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label"><?php echo $language['lg_address_line_2'];?> <span class="text-danger">*</span></label>
												<input type="text" name="address2" id="address2" value="<?php echo $profile['address2'];?>" class="form-control">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label"><?php echo $language['lg_country'];?> <span class="text-danger">*</span></label>
												<select class="form-control select" name="country" id="country">
													<option value=""><?php echo $language['lg_select_country'];?></option>
												</select>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label"><?php echo $language['lg_state__province'];?> <span class="text-danger">*</span></label>
												<select class="form-control select" name="state" id="state">
													<option value=""><?php echo $language['lg_select_state'];?></option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label"><?php echo $language['lg_city'];?> <span class="text-danger">*</span></label>
												<select class="form-control select" name="city" id="city">
													<option value=""><?php echo $language['lg_select_city'];?></option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label"><?php echo $language['lg_postal_code'];?> <span class="text-danger">*</span></label>
												<input type="text" name="postal_code" id="postal_code" value="<?php echo $profile['postal_code'];?>" class="form-control">
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- /Contact Details -->
							
							
							<div class="submit-section submit-btn-bottom">
								<button type="submit" id="save_btn" class="btn btn-primary submit-btn"><?php echo $language['lg_save_changes'];?></button>
							</div>
							</form>
						</div>
					</div>

				</div>

			</div>		
			<!-- /Page Content -->



			<script type="text/javascript">
				var country='<?php echo $profile['country'];?>';
			    var state='<?php echo $profile['state'];?>';
			    var city='<?php echo $profile['city'];?>';
			    var specialization='<?php echo $profile['specialization'];?>';
			    var country_code='<?php echo $profile['country_code'];?>';
			</script>