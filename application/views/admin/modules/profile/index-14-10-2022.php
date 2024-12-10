<!-- Page Wrapper -->

		<?php
			$profile_image=(!empty($profile['profileimage']))?base_url().$profile['profileimage']:base_url().'assets/img/user.png';
		?>
            <div class="page-wrapper">
                <div class="content container-fluid">
					
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col">
								<h3 class="page-title">Profile</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
									<li class="breadcrumb-item active">Profile</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Header -->
					
					<div class="row">
						<div class="col-md-12">
							<div class="profile-header">
								<div class="row align-items-center">
									<div class="col-auto profile-image">
										<a href="#">
											<img class="rounded-circle avatar-view-img" alt="User Image" src="<?php echo $profile_image;?>">
										</a>
										<div class="upload-img">
										<div class="change-photo-btn avatar-view-btn">
											<span><i class="fa fa-upload"></i> Upload Photo</span>
											<input type="hidden" id="crop_prof_img" name="profile_image">
										</div>
										<small class="form-text text-muted">Allowed JPG, GIF or PNG.</small>
									</div>
									</div>
									<div class="col ml-md-n2 profile-user-info">
										<h4 class="user-name mb-0 admin_name"><?php 
                       if(!empty($profile['name'])){
                       	echo ucfirst($profile['name']);
                       }else{}
										?></h4>
										<h6 class="text-muted admin_email"><?php if(!empty($profile['email'])){
                       	echo ($profile['email']);
                       }else{}?></h6>
										<div class="user-Location"><i class="fa fa-map-marker"></i> <span class="admin_location"><?php if(!empty($profile['city']) || !empty($profile['country']) ){echo ucfirst($profile['city'].', '.$profile['country']);}else{} ?></span></div> 
										<div class="about-text admin_biography"><?php if(!empty($profile['biography'])){echo $profile['biography'];}else{} ?></div>
									</div>
									<div class="col-auto profile-btn">
										
										<a data-toggle="modal" href="#profile_modal" class="btn btn-primary">
											Edit
										</a>
									</div>
								</div>
							</div>
							<div class="profile-menu">
								<ul class="nav nav-tabs nav-tabs-solid">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#password_tab">Password</a>
									</li>
								</ul>
							</div>	
							<div class="tab-content profile-tab-cont">
								
															
								<!-- Change Password Tab -->
								<div id="password_tab" class="tab-pane fade show active">
								
									<div class="card">
										<div class="card-body">
											<h5 class="card-title">Change Password</h5>
											<div class="row">
												<div class="col-md-10 col-lg-6">
													<form method="post" id="change_password">
														<div class="form-group">
															<label>Current Password</label>
															<input type="password" class="form-control" id="currentpassword" name="currentpassword">
														</div>
														<div class="form-group">
															<label>New Password</label>
															<input type="password" class="form-control" id="password" name="password" placeholder="Your password must be 6 characters" maxlength="6">
														</div>
														<div class="form-group">
															<label>Confirm Password</label>
															<input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Your password must be 6 characters" maxlength="6">
														</div>
														<button id="password_btn" class="btn btn-primary" type="submit">Update</button>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- /Change Password Tab -->
								
							</div>
						</div>
					</div>
				
				</div>			
			</div>
			<!-- /Page Wrapper -->
		
        </div>


        <div class="modal fade custom-modal" id="avatar-modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title"><i>Profile Image</i></h4>
      </div>
      <?php $curprofileimage = (!empty($profile['profileimage']))?$profile['profileimage']:''; ?>
      <form class="avatar-form" action="<?php echo base_url('admin/profile/crop_profile_img/'.$curprofileimage)?>" enctype="multipart/form-data" method="post">
        <div class="modal-body">
          <div class="avatar-body">
            <!-- Upload image and data -->
            <div class="avatar-upload">
              <input class="avatar-src" name="avatar_src" type="hidden">
              <input class="avatar-data" name="avatar_data" type="hidden">
              <label for="avatarInput">Select Image</label>
              <input class="avatar-input" id="avatarInput" name="avatar_file" type="file">
              <span id="image_upload_error" class="error" style="display:none;"> Please Upload JPEG, GIF, PNG Image Only. </span>
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
              <button class="btn btn-success avatar-save" type="submit">Save</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>


        <div class="modal fade" id="profile_modal" aria-hidden="true" role="dialog">
			<div class="modal-dialog modal-dialog-centered" role="document" >
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Admin Details</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form method="post" id="profile_form" action="#" autocomplete="off">
							<div class="row form-row">
								<div class="col-12 col-sm-6">
									<div class="form-group">
										<label>Name</label>
										<input type="text" class="form-control" id="name" name="name" value="<?php if(!empty($profile['name'])){echo ucfirst($profile['name']);}else{}?>">
									</div>
								</div>
								<div class="col-12 col-sm-6">
									<div class="form-group">
										<label>Email</label>
										<input type="email" class="form-control" readonly="" id="email" name="email" value="<?php if(!empty($profile['email'])){echo $profile['email'];}else{}?>">
									</div>
								</div>
								<div class="col-12 col-sm-6">
									<div class="form-group">
										<label>Country</label>
										<input type="text" class="form-control" id="country" name="country" value="<?php if(!empty($profile['country'])){echo ucfirst($profile['country']);}else{} ?>">
									</div>
								</div>
								<div class="col-12 col-sm-6">
									<div class="form-group">
										<label>City</label>
										<input type="text" class="form-control" id="city" name="city" value="<?php if(!empty($profile['city'])){echo ucfirst($profile['city']);}else{}?>">
									</div>
								</div>
								<div class="col-12 col-sm-12">
									<div class="form-group">
										<label>Biography</label>
										<textarea class="form-control" id="biography" name="biography"><?php if(!empty($profile['biography'])){echo $profile['biography'];}else{} ?></textarea>
									</div>
								</div>
								
							</div>
							<button type="submit" id="profile_btn" class="btn btn-primary btn-block">Update</button>
						</form>
					</div>
				</div>
			</div>
		</div>
											<!-- /Edit Details Modal -->