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

							<form method="post" action="#" id="doctor_profile_form" autocomplete="off">

								<input type="hidden" value="<?php echo date('d/m/Y', strtotime('-20 years')); ?>" id="maxDate">
								

							<?php

							$user_profile_image=(!empty($profile['profileimage']))?base_url().$profile['profileimage']:base_url().'assets/img/user.png';
							 ?>
						
							<!-- Basic Information -->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title"><?php 
                                     /** @var array $language */
									echo $language['lg_basic_informati'];?></h4>
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
												<label>

													<?php 
													if($this->session->userdata['role']!=6){ 
														echo $language['lg_first_name'];
													}
													else
													{
														echo $language['lg_first_name'];
													}
													?>

													 <span class="text-danger">*</span></label>
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
												<label><?php echo $language['lg_country_code']; ?><span class="text-danger">*</span></label>
                          						<select name="country_code" class="form-control" id="country_code">
                          						</select>
                          					</div>
                        				</div>
										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_mobile_number'];?> <span class="text-danger">*</span></label>
												<input type="text" name="mobileno" id="mobileno" value="<?php echo $profile['mobileno'];?>" class="form-control">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_select_gender'];?> <span class="text-danger">*</span></label>
												<select class="form-control" name="gender" id="gender">
													<option value=""><?php echo $language['lg_select'];?></option>
													<option value="Male" <?php echo ($profile['gender']=='Male')?'selected':'';?>><?php echo $language['lg_male'];?></option>
													<option value="Female" <?php echo ($profile['gender']=='Female')?'selected':'';?>><?php echo $language['lg_female'];?></option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group mb-0">
												<label><?php echo $language['lg_date_of_birth'];?> <span class="text-danger">*</span></label>
												<input type="text" name="dob" id="dob" value="<?php echo !empty($profile['dob'])?date('d/m/Y',strtotime(str_replace('-', '/', $profile['dob']))):'';?>" class="form-control">
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- /Basic Information -->
							
							<!-- About Me -->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title"><?php echo $language['lg_about_me'];?></h4>
									<div class="form-group mb-0">
										<label><?php echo $language['lg_biography'];?></label>
										<textarea class="form-control" name="biography" id="biography" rows="5"><?php echo $profile['biography'];?></textarea>
									</div>
								</div>
							</div>
							<!-- /About Me -->
							
							<!-- Clinic Info -->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title"><?php echo $language['lg_clinic_info'];?></h4>
									<div class="row form-row">
									 <div class="col-md-12">
											<div class="form-group">
												<label><?php echo $language['lg_clinic_name'];?></label>
												<input type="text" value="<?php echo $profile['clinic_name'];?>" name="clinic_name" id="clinic_name" class="form-control">
											</div>
										</div>  
										
										<div class="col-md-12">
											<div class="form-group">
												<label><?php echo $language['lg_clinic_images'];?>  [allowed types: .JPEG|.JPG|.PNG|.GIF Only]</label>
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
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_clinic_address1'];?></label>
												<input type="text" value="<?php echo $profile['clinic_address'];?>" name="clinic_address" id="clinic_address" class="form-control">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_clinic_address2'];?></label>
												<input type="text" value="<?php echo $profile['clinic_address2'];?>" name="clinic_address2" id="clinic_address2" class="form-control">
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_clinic_city'];?></label>
												<input type="text" value="<?php echo $profile['clinic_city'];?>" name="clinic_city" id="clinic_city" class="form-control">
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_clinic_state'];?></label>
												<input type="text" value="<?php echo $profile['clinic_state'];?>" name="clinic_state" id="clinic_state" class="form-control">
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_clinic_country'];?></label>
												<input type="text" value="<?php echo $profile['clinic_country'];?>" name="clinic_country" id="clinic_country" class="form-control">
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_clinic_postal'];?></label>
												<input type="text" value="<?php echo $profile['clinic_postal'];?>" name="clinic_postal" id="clinic_postal" class="form-control">
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- /Clinic Info -->

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
												<label class="control-label"><?php echo $language['lg_address_line_2'];?> <span class="text-danger"></span></label>
												<input type="text" name="address2" id="address2" value="<?php echo $profile['address2'];?>" class="form-control">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label"><?php echo $language['lg_country'];?> <span class="text-danger">*</span></label>
												<select class="form-control" name="country" id="country">
													<option value=""><?php echo $language['lg_select_country'];?></option>
												</select>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label"><?php echo $language['lg_state__province'];?> <span class="text-danger">*</span></label>
												<select class="form-control" name="state" id="state">
													<option value=""><?php echo $language['lg_select_state'];?></option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label"><?php echo $language['lg_city'];?> <span class="text-danger">*</span></label>
												<select class="form-control" name="city" id="city">
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
							
							<!-- Pricing -->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title"><?php echo $language['lg_pricing'];?> <span class="text-danger">*</span></h4>
									
									<div class="form-group mb-0">
										<div id="pricing_select">
											<div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="price_type" name="price_type" class="custom-control-input" value="Free" checked <?php echo ($profile['price_type']=='Free')?'checked':'';?>>
												<label class="custom-control-label" for="price_type"><?php echo $language['lg_free'];?></label>
											</div>
											<div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="price_type1" name="price_type" value="Custom Price" class="custom-control-input" <?php echo ($profile['price_type']=='Custom Price')?'checked':'';?>>
												<label class="custom-control-label" for="price_type1"><?php echo $language['lg_custom_price1'];?> (<?php echo $language['lg_per_slot'];?>)</label>
											</div>
										</div>

									</div>
									
									<div class="row custom_price_cont" id="custom_price_cont" style="display: <?php echo ($profile['price_type']=='Free' || empty($profile['price_type']))?'none':'block';?>;">
										<div class="col-md-4">
											<input type="text" class="form-control" id="amount"  name="amount" value="<?php echo $profile['amount'];?>" placeholder="20">
											<small class="form-text text-muted"><?php echo $language['lg_custom_price_yo'];?></small>
										</div>
									</div>
									
								</div>
							</div>
							<!-- /Pricing -->
							
							<?php if($this->session->userdata('role')!='6'){ ?>
							<!-- Services and Specialization -->
							<div class="card services-card">
								<div class="card-body">
									<h4 class="card-title"><?php echo $language['lg_services_and_sp'];?></h4>
									<div class="form-group">
										<label><?php echo $language['lg_services'];?> <span class="text-danger">*</span></label>
										<input type="text" data-role="tagsinput" class="input-tags form-control inputcls" placeholder="Enter Services" name="services" value="<?php echo $profile['services'];?>" id="services" >
										<small class="form-text text-muted"><?php echo $language['lg_note__type__pre'];?></small>
									</div> 
									<div class="form-group mb-0">
										<label><?php echo $language['lg_specialization1'];?> <span class="text-danger">*</span></label>
										<select class="form-control select" name="specialization" id="specialization">
													<option value=""><?php echo $language['lg_select_speciali'];?></option>
									    </select>
									</div> 
								</div>              
							</div>
							<?php } ?>
							<!-- /Services and Specialization -->
							<?php if($this->session->userdata('role')=='1'){ ?>
							<!-- Education -->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title"><?php echo $language['lg_education'];?></h4>
									<div class="education-info">
										<?php
										
										if(!empty($education)){
											$i=1;
											foreach ($education as $erows) {
										echo'<div class="row form-row education-cont">
											<div class="col-12 col-md-10 col-lg-11">
												<div class="row form-row">
													<div class="col-12 col-md-6 col-lg-4">
														<div class="form-group">
															<label>'.$language['lg_degree'].' <span class="text-danger">*</span></label>
															<input type="text" name="degree[]" value="'.$erows['degree'].'" class="form-control degree inputcls">
														</div> 
													</div>
													<div class="col-12 col-md-6 col-lg-4">
														<div class="form-group">
															<label>'.$language['lg_collegeinstitut'].' <span class="text-danger">*</span></label>
															<input type="text" name="institute[]" value="'.$erows['institute'].'" class="form-control institute inputcls">
														</div> 
													</div>
													<div class="col-12 col-md-6 col-lg-4">
														<div class="form-group">
															<label>'.$language['lg_year_of_complet'].' <span class="text-danger">*</span></label>
															<input type="text" name="year_of_completion[]" value="'.$erows['year_of_completion'].'" readonly class="form-control years year_of_completion inputcls">
														</div> 
													</div>
												</div>
											</div>';
											if($i!=1){ 
											echo'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>';
										   }
										   echo'</div>';
										$i++; } }
										?>
										<div class="row form-row education-cont">
											<div class="col-12 col-md-10 col-lg-11">
												<div class="row form-row">
													<div class="col-12 col-md-6 col-lg-4">
														<div class="form-group">
															<label><?php echo $language['lg_degree'];?> <span class="text-danger">*</span></label>
															<input type="text" name="degree[]" class="form-control degree inputcls">
														</div> 
													</div>
													<div class="col-12 col-md-6 col-lg-4">
														<div class="form-group">
															<label><?php echo $language['lg_collegeinstitut'];?> <span class="text-danger">*</span></label>
															<input type="text" name="institute[]" class="form-control institute inputcls">
														</div> 
													</div>
													<div class="col-12 col-md-6 col-lg-4">
														<div class="form-group">
															<label><?php echo $language['lg_year_of_complet'];?> <span class="text-danger">*</span></label>
															<input type="text" name="year_of_completion[]" readonly class="form-control years year_of_completion inputcls">
														</div> 
													</div>
												</div>
											</div>
											<?php 
											/** @var array $education */
												if(count($education)>=1){ 
											echo'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>';
										   }
											 ?>
										</div>
									</div>
									<div class="add-more">
										<a href="javascript:void(0);" class="add-education"><i class="fa fa-plus-circle"></i><?php echo $language['lg_add_more'];?> </a>
									</div>
								</div>
							</div>
							<!-- /Education -->
							<?php } ?>

							<?php if($this->session->userdata('role')!='6'){ ?>
							<!-- Experience -->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title"><?php echo $language['lg_experience'];?></h4>
									<div class="experience-info">
										<?php
										
										if(!empty($experience)){
											$j=1;
										foreach ($experience as $exrows) {
											echo'<div class="row form-row experience-cont">
											<div class="col-12 col-md-10 col-lg-11">
												<div class="row form-row">
													<div class="col-12 col-md-6 col-lg-4">
														<div class="form-group">
															<label>'.$language['lg_hospital_name'].'</label>
															<input type="text" name="hospital_name[]" value="'.$exrows['hospital_name'].'" class="form-control">
														</div> 
													</div>
													<div class="col-12 col-md-6 col-lg-4">
														<div class="form-group">
															<label>'.$language['lg_from'].'</label>
															<input type="text" name="from[]" id="from" value="'.$exrows['from'].'" readonly class="form-control years">
														</div> 
													</div>
													<div class="col-12 col-md-6 col-lg-4">
														<div class="form-group">
															<label>'.$language['lg_to3'].'</label>
															<input type="text" name="to[]" id="to" value="'.$exrows['to'].'" readonly class="form-control years">
														</div> 
													</div>
													<div class="col-12 col-md-6 col-lg-4">
														<div class="form-group">
															<label>'.$language['lg_designation'].'</label>
															<input type="text" name="designation[]" value="'.$exrows['designation'].'" class="form-control">
														</div> 
													</div>
												</div>
											</div>';
											if($j!=1){ 
											echo'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>';
										   }
										   echo'</div>';
										 $j++; } } ?>
											<div class="row form-row experience-cont">
											<div class="col-12 col-md-10 col-lg-11">
												<div class="row form-row">
													<div class="col-12 col-md-6 col-lg-4">
														<div class="form-group">
															<label><?php echo $language['lg_hospital_name'];?></label>
															<input type="text" name="hospital_name[]" class="form-control">
														</div> 
													</div>
													<div class="col-12 col-md-6 col-lg-4">
														<div class="form-group">
															<label><?php echo $language['lg_from'];?></label>
															<input type="text" name="from[]" id="from" readonly class="form-control years">
														</div> 
													</div>
													<div class="col-12 col-md-6 col-lg-4">
														<div class="form-group">
															<label><?php echo $language['lg_to3'];?></label>
															<input type="text" name="to[]" id="to" readonly class="form-control years">
														</div> 
													</div>
													<div class="col-12 col-md-6 col-lg-4">
														<div class="form-group">
															<label><?php echo $language['lg_designation'];?></label>
															<input type="text" name="designation[]" class="form-control">
														</div> 
													</div>
												</div>
											</div>
											<?php 

											   /** @var array $experience */
												if(count($experience)>=1){ 
											echo'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>';
										   }
											 ?>
											 <input type="hidden" id="experience_count" value="<?php echo count($experience);?>">
										</div>
									</div>
									<div class="add-more">
										<a href="javascript:void(0);" class="add-experience"><i class="fa fa-plus-circle"></i><?php echo $language['lg_add_more'];?></a>
									</div>
								</div>
							</div>
							<!-- /Experience -->
							
							<!-- Awards -->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title"><?php echo $language['lg_awards'];?></h4>
									<div class="awards-info">
										<?php
										if(!empty($awards)){
											$k=1;
										foreach ($awards as $arows) {
										echo'<div class="row form-row awards-cont">
											<div class="col-12 col-md-5">
												<div class="form-group">
													<label>'.$language['lg_awards'].'</label>
													<input type="text" name="awards[]" value="'.$arows['awards'].'" class="form-control">
												</div> 
											</div>
											<div class="col-12 col-md-5">
												<div class="form-group">
													<label>'.$language['lg_year'].'</label>
													<input type="text" name="awards_year[]" value="'.$arows['awards_year'].'" readonly class="form-control years">
												</div> 
											</div>';
											if($k!=1){ 
											echo'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>';
										   }
										   echo'</div>';
										 $k++; } } ?>
										<div class="row form-row awards-cont">
											<div class="col-12 col-md-5">
												<div class="form-group">
													<label><?php echo $language['lg_awards'];?></label>
													<input type="text" name="awards[]" class="form-control">
												</div> 
											</div>
											<div class="col-12 col-md-5">
												<div class="form-group">
													<label><?php echo $language['lg_year'];?></label>
													<input type="text" name="awards_year[]" readonly class="form-control years">
												</div> 
											</div>
											<?php 
											/** @var array $awards */
												if(count($awards)>=1){ 
											echo'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>';
										   }
											 ?>
										</div>
									</div>
									<div class="add-more">
										<a href="javascript:void(0);" class="add-award"><i class="fa fa-plus-circle"></i><?php echo $language['lg_add_more'];?></a>
									</div>
								</div>
							</div>
							<!-- /Awards -->
							
							<!-- Memberships -->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title"><?php echo $language['lg_memberships'];?></h4>
									<div class="membership-info">
										<?php
										
										if(!empty($memberships)){
											$l=1;
										foreach ($memberships as $mrows) {
										echo'<div class="row form-row membership-cont">
											<div class="col-12 col-md-10 col-lg-5">
												<div class="form-group">
													<label>'.$language['lg_memberships'].'</label>
													<input type="text" name="memberships[]" value="'.$mrows['memberships'].'" class="form-control">
												</div> 
											</div>';
											if($l!=1){ 
											echo'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>';
										   }
										   echo'</div>';
										 $l++; } } ?>
										<div class="row form-row membership-cont">
											<div class="col-12 col-md-10 col-lg-5">
												<div class="form-group">
													<label><?php echo $language['lg_memberships'];?></label>
													<input type="text" name="memberships[]" class="form-control">
												</div> 
											</div>
											<?php 
											/** @var array $memberships*/
												if(count($memberships)>=1){ 
											echo'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>';
										   }
											 ?>
										</div>
									</div>
									<div class="add-more">
										<a href="javascript:void(0);" class="add-membership"><i class="fa fa-plus-circle"></i><?php echo $language['lg_add_more'];?> </a>
									</div>
								</div>
							</div>
							<!-- /Memberships -->
							
							<!-- Registrations -->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title"><?php echo $language['lg_registrations'];?></h4>
									<div class="registrations-info">
										<?php
										/** @var array $registrations */
										if(!empty($registrations)){
											$m=1;
										foreach ($registrations as $rrows) {
											echo'<div class="row form-row reg-cont">
											<div class="col-12 col-md-5">
												<div class="form-group">
													<label>'.$language['lg_registrations'].'</label>
													<input type="text" name="registrations[]" value="'.$rrows['registrations'].'" class="form-control">
												</div> 
											</div>
											<div class="col-12 col-md-5">
												<div class="form-group">
													<label>'.$language['lg_year'].'</label>
													<input type="text" readonly name="registrations_year[]" value="'.$rrows['registrations_year'].'" class="form-control years">
												</div> 
											</div>';
											if($m!=1){ 
											echo'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>';
										   }
										   echo'</div>';
										 } } ?>
										<div class="row form-row reg-cont">
											<div class="col-12 col-md-5">
												<div class="form-group">
													<label><?php echo $language['lg_registrations'];?></label>
													<input type="text" name="registrations[]" class="form-control">
												</div> 
											</div>
											<div class="col-12 col-md-5">
												<div class="form-group">
													<label><?php echo $language['lg_year'];?></label>
													<input type="text" readonly name="registrations_year[]" class="form-control years">
												</div> 
											</div>
											<?php 
												if(count($registrations)>=1){ 
											echo'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>';
										   }
											 ?>
										</div>
									</div>
									<div class="add-more">
										<a href="javascript:void(0);" class="add-reg"><i class="fa fa-plus-circle"></i><?php echo $language['lg_add_more'];?> </a>
									</div>
								</div>
							</div>
							<!-- /Registrations -->
							<?php } ?>
							
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