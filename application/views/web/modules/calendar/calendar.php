
			
			<!-- Page Content -->
			<div class="content">
				<div class="container-fluid">

					<div class="row">
						<div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">
						
							<!-- Profile Sidebar -->

							<?php 
							if($this->session->userdata('role')=='1' || $this->session->userdata('role')=='6')
							{
								$this->load->view('web/includes/doctor_sidebar');
							}
							if($this->session->userdata('role')=='2')
							{
								$this->load->view('web/includes/patient_sidebar');
							}
							
                            ?>
							<!-- /Profile Sidebar -->
							
						</div>
						
							<!-- Calendar -->
						<div class="col-md-7 col-lg-8 col-xl-9">
							<div class="card">
								<div class="card-body">
									<div id="calendar"></div>
								</div>
							</div>
						</div>
						<!-- /Calendar -->

						
					</div>

				</div>

			</div>		