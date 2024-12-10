<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><?php
									 /** @var array $language */
									 echo $language['lg_dashboard']; ?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_products']; ?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_products']; ?></h2>
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
							<div class="card card-table">
								<div class="card-body">
								
									<!-- Invoice Table -->
									<div class="table-responsive">
										<table id="products_table" class="table table-hover table-center mb-0 w-100">
											<thead>
												<tr>
													<th>S.NO</th>
													<th><?php echo $language['lg_product_image']; ?></th>
													<th><?php echo $language['lg_product_name']; ?></th>
													<th><?php echo $language['lg_category']; ?></th>
													<th><?php echo $language['lg_subcategory']; ?></th>
													<th><?php echo $language['lg_unit']; ?></th>
													<th><?php echo $language['lg_created_date']; ?></th>
													<th><?php echo $language['lg_status']; ?></th>
													<th><?php echo $language['lg_action'] ;?></th>
													
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
