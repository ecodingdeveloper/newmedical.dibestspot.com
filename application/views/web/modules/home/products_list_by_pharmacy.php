           <div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.html">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">Products</li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title search-results"></h2>
						</div>
					</div>
				</div>
			</div>
			
			<?php $this->load->view('web/includes/product_navbar');?>

			<!-- Home Banner -->
			<section class="section">
				<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-md-8">
							<div class="banner-wrapper-ecom">
								<!-- Search -->
								<?php /* <div class="search-box1">
									<h5 class="mt-3">Browse Medicines & Health Products</h5>
									
										<form>
										<div class="form-group search-location1 postion-relative">


											<input type="hidden" value="<?php if(isset($_GET['category']) && !empty($_GET['category'])) echo $_GET['category'];?>" id="category" class="form-control">
											<input type="hidden" value="<?php if(isset($_GET['subcategory']) && !empty($_GET['subcategory'])) echo $_GET['subcategory'];?>" id="subcategory" class="form-control">

											<input type="text" value="<?php if(isset($_POST['keywords']) && !empty($_POST['keywords'])) echo $_POST['keywords'];?>" id="keywords" class="form-control" id="keywords" placeholder="Search..." style="padding-left:15px;">
											<!-- <span class="form-text">Based on your Location</span> -->
											<!-- <span class="search-detect">Detect</span> -->
										</div>
										
										<button type="button" onclick="get_products(0)" class="btn btn-primary search-btn"><i class="fas fa-search"></i> <span>Go</span></button>

										<!--<button type="button" onclick="reset_products()" class="btn btn-primary search-btn"><i class="fas fa-refresh"></i> <span>Reset</span></button>-->
									</form>
								</div>  <?php */ ?>
								<!-- /Search -->
								
							</div>
							
						</div>
					</div>
				</div>
			</section>
			<!-- /Home Banner -->
		  	<!-- Page Content -->
			<div class="content">
				<div class="container-fluid">

					<div class="row">

							<?php /* <div class="col-md-5 col-lg-3 col-xl-3 theiaStickySidebar">
							
							<!-- Search Filter -->
							<div class="card search-filter">
								<div class="card-header">
									<h4 class="card-title mb-0">Filter</h4>
								</div>
								<div class="card-body">
								<!-- <div class="filter-widget">
									<div class="cal-icon">
										<input type="text" class="form-control datetimepicker" placeholder="Select Date">
									</div>			
								</div> -->
								<div class="filter-widget">
									<h4>Categories</h4>
									<?php if(!empty(product_categories())) { $i=1; foreach (product_categories() as $crows) { ?>
									<div>
										<label class="custom_check">
											<input type="checkbox" name="gender_type">
											<span class="checkmark"></span> <?php echo $crows['category_name'];?> 
										</label>
									</div>
								<?php } } ?>
								
								</div>
									<div class="btn-search">
										<button type="button" class="btn btn-block">Search</button>
									</div> 	
								</div>
							</div>
							<!-- /Search Filter -->
							
						</div>  <?php */ ?>

						<?php /* <div class="col-md-5 col-lg-3 col-xl-3 theiaStickySidebar">
							
							<!-- Profile Sidebar -->
							<div class="profile-sidebar">
							
                     <!-- Filter Area -->
                     
                       
					<div id="accordion" class="accordion p-3">
						<div class="card mb-0 border-0">
							<h4 class="card-title px-3 pt-3">Categories</h4>

							<?php if(!empty(product_categories())) { $i=1; foreach (product_categories() as $crows) { ?>
							<div class="card-header bg-white border-0 p-3 mb-0">
								<a href="#collapse<?php echo $i;?>" class="card-title text-dark d-inline-block mb-0 w-100 collapsed" data-toggle="collapse" aria-expanded="false">
									<p class="d-inline font-bold text-uppercase"><?php echo $crows['category_name'];?></p>
								</a>
							</div>

							<div id="collapse<?php echo $i;?>" class="card-body pt-0 pb-3 collapse" data-parent="#accordion" style="">
								<?php if(!empty(product_subcategories($crows['id']))) { foreach (product_subcategories($crows['id']) as $srows) { ?>
								<div class="mb-3 ml-3"><a onclick="search_subcategory('<?php echo $srows['slug'];?>')" href="javascript:void(0);">- <?php echo $srows['subcategory_name'];?></a></div>
								
							<?php } } ?>
							</div>

							<?php $i++; }  } ?>
							
							
							
						</div>
					</div>

                     <!-- /Filter Area -->

					</div>
							<!-- /Profile Sidebar -->
							
						</div>  <?php */ ?>
						
						<div class="col-md-12 col-lg-12 col-xl-12">
							

							<div class="row align-items-center pb-3">	
								<div class="col-xl-8 col-lg-7 col-md-7 d-none d-md-block">
									<h3 class="title pharmacy-title">Popular Products</h3>
									<!-- <p class="doc-location mb-2 text-ellipse pharmacy-location"><i class="fas fa-map-marker-alt mr-1"></i> 96 Red Hawk Road Cyrus, MN 56323 </p>
									<span class="sort-title">Showing 6 of 98 products</span> -->
								</div>
								<div class="col-xl-4 col-lg-5 col-md-5">
																<div class="pro-search ui-widget">
  <input class="form-control" type="text" id="keywords" onkeyup="getproduct_key()">
  <button class="btn" type="button" onclick="get_products(0)"><i class="fas fa-search"></i></button>
</div>
								</div>
								<!-- <div class="col-md-8 col-12 d-md-block d-none custom-short-by">
									<div class="sort-by pb-3">
										<span class="sort-title">Sort by</span>
										<span class="sortby-fliter">
											<select class="select">
												<option>Select</option>
												<option class="sorting">Rating</option>
												<option class="sorting">Popular</option>
												<option class="sorting">Latest</option>
												<option class="sorting">Free</option>
											</select>
										</span>
									</div>
								</div>  -->
							 </div>
							 	<input type="hidden" name="page" id="page_no_hidden" value="1" >
							 	

							 	

							 	
					<div class="spinner-border text-success text-center" role="status" id="loading"></div>
						
							 	<div class="row" id="product-list">
								

                            	

                             </div>

                             <div class="load-more text-center d-none" id="load_more_btn">
								<a class="btn btn-primary btn-sm" href="javascript:void(0);">Load More</a>	
							</div>













							<!-- <h4 class="mb-4">Best Sellers</h4>
							<input type="hidden" name="page" id="page_no_hidden" value="1" >
							
							<div class="row" id="product-list">
							
							<div class="load-more text-center d-none" id="load_more_btn">
								<a class="btn btn-primary btn-sm" href="javascript:void(0);">Load More</a>	
							</div>
                               

                             </div>  -->
                             
						</div>
					</div>
				</div>
			</div>
                        
                        
                        <input type="hidden" name="pharmacy_id" id="pharmacy_id" value="<?php echo base64_decode($this->uri->segment(3) ?? ''); ?>">