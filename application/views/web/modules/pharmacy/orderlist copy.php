<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<?php 
					 /** @var array $language */
					if($this->session->userdata('role')=='5'){ ?>
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><?php

									 echo $language['lg_dashboard']; ?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_order_list']; ?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_order_list']; ?></h2>
						</div>
					</div>
					<?php }else{ ?>
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><?php echo $language['lg_dashboard'];?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_orders']; ?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_orders']; ?></h2>
						</div>
					</div>
					<?php }?>
				</div>
			</div>
			<!-- /Breadcrumb -->
			
			<!-- Page Content -->
			<div class="content">
				<div class="container-fluid">

					<div class="row">
						<div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">
						
							<!-- Profile Sidebar -->
							<?php if($this->session->userdata('role')=='5'){ ?>
								<?php $this->load->view('web/includes/pharmacy_sidebar.php');?>
							<?php }else{ ?>
								<?php $this->load->view('web/includes/patient_sidebar.php');?>
							<?php }?>
							<!-- /Profile Sidebar -->
							
						</div>
						
						<div class="col-md-7 col-lg-8 col-xl-9">
							<div class="card card-table">
								<div class="card-body">
								
									<!-- Invoice Table -->
									<div class="table-responsive">
										<table id="orders_table" class="table table-hover table-center mb-0 w-100">
											<thead>
												<tr>
													
													<th>#</th>
						                          	<th><?php echo $language['lg_order_id']; ?></th>
						                          	<?php if($this->session->userdata('role')=='5'){ ?>
						                          	<th><?php echo $language['lg_customer_name']; ?></th>
						                          	<?php }else{ ?>
						                          	<th><?php echo $language['lg_pharmacy_name']; ?></th>
						                          	<?php }?>
						                          	<th><?php echo $language['lg_quantity']; ?></th>
						                          	<th><?php echo $language['lg_amount']; ?></th>
						                          	<th><?php echo $language['lg_payment_gateway']; ?></th>
						                          	<th><?php echo $language['lg_status']; ?></th>
						                          	<?php if($this->session->userdata('role')=='5'){ ?>
						                          	<th><?php echo $language['lg_change_status']; ?></th>
						                          	<?php }?>
						                          	<th><?php echo $language['lg_order_date']; ?></th>
						                          	<th><?php echo 'Invoice'; ?></th>
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
