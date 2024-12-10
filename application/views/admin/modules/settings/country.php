<div class="page-wrapper">
                <div class="content container-fluid">
					
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col">
								<h3 class="page-title">Profile</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
									<li class="breadcrumb-item active">Country Add</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Header -->
					
					<div class="row">
						<div class="col-md-12">
						
						
							<div class="tab-content profile-tab-cont">
								
															
								<!-- Change Password Tab -->
								<div id="password_tab" class="tab-pane fade show active">
								
									<div class="card">
										<div class="card-body">
											<h5 class="card-title">Create Country</h5>
											<div class="row">
												<div class="col-md-10 col-lg-6">
													<form method="post" id="country_add">
														<div class="form-group">
															<label>Sortname</label>
															<input type="text" class="form-control" id="sortname" name="sortname">
														</div>
														<div class="form-group">
															<label>Country name</label>
															<input type="text" class="form-control" id="country" name="country">
														</div>
														<div class="form-group">
															<label>Phone Code</label>
															<input type="text" class="form-control" id="phone_code" name="phone_code">
														</div>
														<button id="country_add" class="btn btn-primary" type="submit">Save</button>
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