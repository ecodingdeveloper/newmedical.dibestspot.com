<!-- Breadcrumb -->
<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-8 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>"><?php 
									/** @var array $language  */
									/** @var array $specialization  */
									echo $language['lg_home'];?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo ($role==6)?$language['lg_clinic_search']:$language['lg_doctor_search'];?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title search-results"></h2>
						</div>
						<div class="col-md-4 col-12 d-md-block d-none">
							<div class="sort-by">
								<span class="sort-title"><?php echo $language['lg_sort_by'];?></span>
								<span class="sortby-fliter">
									<select class="select form-control" id="orderby" onchange="search_doctor(0)">
										<option value=""><?php echo $language['lg_select'];?></option>
										<option class="sorting" value="Rating"><?php echo $language['lg_rating'];?></option>
										
										<option class="sorting" value="Latest"><?php echo $language['lg_latest'];?></option>
										<option class="sorting" value="Free"><?php echo $language['lg_free'];?></option>
									</select>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /Breadcrumb -->
			
			<!-- Page Content -->
			<div class="content">
				<div class="container-fluid">

					<div class="row" style="justify-content:center;" >
						
						
						<div class="col-md-12 col-lg-8 col-xl-9">
							<input type="hidden" name="page" id="page_no_hidden" value="1" >
							<div id="service-list"></div>

						
							<div class="spinner-border text-success text-center" role="status" id="loading"></div>
							<div class="load-more text-center d-none" id="load_more_btn">
								<a class="btn btn-primary btn-sm" href="javascript:void(0);"><?php echo $language['lg_load_more'];?></a>	
							</div>

							<!-- Doctor Widget -->
							
							<!-- /Doctor Widget -->

								
						</div>
					</div>

				</div>

			</div>		
			<!-- /Page Content -->

			<script type="text/javascript">
				var country='';
				var country_code='';
			    var state='';
			    var city='';
			    var citys='<?php if(isset($_GET['location'])) { echo ($_GET['location']); }?>';
			    var specialization='';
			    
			</script>