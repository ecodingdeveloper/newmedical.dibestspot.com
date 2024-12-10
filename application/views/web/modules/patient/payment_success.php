<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php 
									/** @var array $language */
									echo $language['lg_home']; ?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_booking2']; ?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_booking2']; ?></h2>
						</div>
					</div>
				</div>
			</div>
			<!-- /Breadcrumb -->
			
			<!-- Page Content -->
			<div class="content success-page-cont">
				<div class="container-fluid">
				
					<div class="row justify-content-center">
						<div class="col-lg-6">
						
							<!-- Success Card -->
							<div class="card success-card">
								<div class="card-body">
									<div class="success-cont">
										<i class="fas fa-check"></i>
										<h3><?php echo $language['lg_appointment_boo']; ?></h3>
										<p><?php echo $language['lg_appointment_boo1']; ?> <strong id="doctor_name"><?php if($appointment_details['role']!=6){ echo $language['lg_dr']; } ?> <?php 
										/** @var array $appointment_details */
										echo $appointment_details['doctor_name']; ?></strong><br> <?php echo $language['lg_on1']; ?> <strong id="appt_time"><?php echo date("d M Y  h:i a",strtotime($appointment_details['from_date_time'])); ?></strong></p>
										<div><a href="<?php echo base_url(); ?>invoice-view/<?php echo base64_encode($appointment_details['payment_id']); ?>" class="btn btn-primary view-inv-btn"><?php echo $language['lg_view_invoice']; ?></a></div>
									</div>
								</div>
							</div>
							<!-- /Success Card -->
							
						</div>
					</div>
					
				</div>
			</div>		
			<!-- /Page Content -->