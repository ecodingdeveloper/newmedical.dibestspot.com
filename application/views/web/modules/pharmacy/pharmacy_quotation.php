			
			<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>"><?php
									 /** @var array $language */
									  echo $language['lg_home'];?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_quotation'];?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_quotation'];?></h2>
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
							<?php $this->load->view('web/includes/pharmacy_sidebar.php');?>
							<!-- /Profile Sidebar -->


							
						</div>
						
						<div class="col-md-7 col-lg-8 col-xl-9">
							
							
							<div class="row">
								<div class="col-md-12">
									<h4 class="mb-4"><?php echo $language['lg_quotation']; ?></h4>
									<div class="appointment-tab">
									
										<!-- Appointment Tab -->
										<ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded">
											<li class="nav-item">
												<a class="nav-link active" href="#new_quotation_requests" data-toggle="tab"><?php echo $language['lg_new_quotation'];?></a>
											</li>
                                                <li class="nav-item">
												<a class="nav-link" href="#waiting_quotation_requests" data-toggle="tab"><?php echo $language['lg_waiting_quotation'];?></a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="#completed_quotation_requests" data-toggle="tab"><?php echo $language['lg_completed_quotation'];?></a>
											</li> 
                                                                                        
                                                                                        <li class="nav-item">
												<a class="nav-link" href="#cancelled_quotation_requests" data-toggle="tab"><?php echo $language['lg_cancelled_quotation'];?></a>
											</li> 
										</ul>
										<!-- /Appointment Tab -->
										
										<div class="tab-content">
										
											
											<div class="tab-pane show active" id="new_quotation_requests">
												<div class="card card-table mb-0">
													<div class="card-body">
														<div class="table-responsive">
															<input type="hidden" id="type">
															<table id="new_quotation_table" class="table table-hover table-center mb-0">
																<thead>
																	<tr>
                                                                                                                                                <th>#</th>
																		<th><?php echo $language['lg_patient_name'];?></th>
                                                                                                                                                <th><?php echo $language['lg_quotation_date'];?></th>
																		<th><?php echo $language['lg_action'];?></th>
																		
																	</tr>
																</thead>
																<tbody>
																</tbody>
															</table>		
														</div>
													</div>
												</div>
											</div>
                                                                                    
                                                                                        <div class="tab-pane " id="completed_quotation_requests">
												<div class="card card-table mb-0">
													<div class="card-body">
														<div class="table-responsive">
															<input type="hidden" id="type">
															<table id="completed_quotation_table" class="table table-hover table-center mb-0">
																<thead>
																	<tr>
                                                                                                                                                <th>#</th>
																		<th><?php echo $language['lg_patient_name'];?></th>
																		<th><?php echo $language['lg_quotation_date'];?></th>
																		<th><?php echo $language['lg_action'];?></th>
																		
																	</tr>
																</thead>
																<tbody>
																</tbody>
															</table>		
														</div>
													</div>
												</div>
											</div>
                                                                                    
                                                                                        <div class="tab-pane " id="cancelled_quotation_requests">
												<div class="card card-table mb-0">
													<div class="card-body">
														<div class="table-responsive">
															<input type="hidden" id="type">
															<table id="cancelled_quotation_table" class="table table-hover table-center mb-0">
																<thead>
																	<tr>
                                                                                                                                                <th>#</th>
																		<th><?php echo $language['lg_patient_name'];?></th>
																		<th><?php echo $language['lg_quotation_date'];?></th>
																		<th><?php echo $language['lg_action'];?></th>
																		
																	</tr>
																</thead>
																<tbody>
																</tbody>
															</table>		
														</div>
													</div>
												</div>
											</div>
                                                                                    
                                                                                    
                                                                                        <div class="tab-pane " id="waiting_quotation_requests">
												<div class="card card-table mb-0">
													<div class="card-body">
														<div class="table-responsive">
															<input type="hidden" id="type">
															<table id="waiting_quotation_table" class="table table-hover table-center mb-0">
																<thead>
																	<tr>
                                                                                                                                                <th>#</th>
																		<th><?php echo $language['lg_patient_name'];?></th>
																		<th><?php echo $language['lg_quotation_date'];?></th>
																		<th><?php echo $language['lg_action'];?></th>
																		
																	</tr>
																</thead>
																<tbody>
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
					</div>

				</div>

			</div>		
			<!-- /Page Content -->
                        
                        
        <div class="modal fade" id="quotation_modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
          <div style="max-width: 872px;" class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title"><?php echo $language['lg_quotation_prepare']; ?></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
              </div>
                  <div class="modal-body pharmacy_quotation"> 
                                        
                  </div>
                  
                </div>
              </div>
            </div>
   
		