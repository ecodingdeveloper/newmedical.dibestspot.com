<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><?php 
									/** @var array $language  */
									echo $language['lg_dashboard']; ?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_invoice']; ?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_invoice']; ?></h2>
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
							if($this->session->userdata('role')=='1' || $this->session->userdata('role')=='6')
							{
								$this->load->view('web/includes/doctor_sidebar');
							}
							if($this->session->userdata('role')=='4')
							{
								$this->load->view('web/includes/lab_sidebar');
							}
							if($this->session->userdata('role')=='5'||$this->session->userdata('role')=='7')
							{
								$this->load->view('web/includes/pharmacy_sidebar');
							}
							if($this->session->userdata('role')=='2')
							{
								$this->load->view('web/includes/patient_sidebar');
							}
							
                            ?>
							<!-- /Profile Sidebar -->
							
						</div>
						
						<div class="col-md-7 col-lg-8 col-xl-9">
							<div class="card card-table">
								<div class="card-body">
								
									<!-- Invoice Table -->
									<div class="table-responsive">
										<table class="table table-hover table-center mb-0" id="invoice_table">
											<thead>
												<tr>
													<th><?php echo $language['lg_sno']; ?> </th>
													<th><?php echo $language['lg_invoice_no']; ?> </th>

													<th>
														<?php
														if ($this->session->userdata('role')=='4') {
															echo $language['lg_patient_name'];
														} else {
															echo ($this->session->userdata('role')=='1' || $this->session->userdata('role')=='6')?$language['lg_patient4']:$language['lg_doctor2'].'/'.$language['lg_clinic'];
														}
														?>
													</th>

													<th><?php echo $language['lg_amount']; ?></th>
													<th><?php echo $language['lg_payment_status']; ?></th>
													<?php if($this->session->userdata('role')=='2'){ ?>
													<th><?php echo settings('fixed_label_2')."($)" ?></th>
													<th><?php echo settings('percentage_label_2')."(%)" ?></th>
													<th>Bill to Pay</th>
												   <?php } ?>
													<th><?php echo $language['lg_paid_on']; ?></th>
													<th><?php echo $language['lg_action']; ?></th>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
									<!-- /Invoice Table -->
									
								</div>
							</div>
						</div>
					</div>

				</div>

			</div>		
			<!-- /Page Content -->